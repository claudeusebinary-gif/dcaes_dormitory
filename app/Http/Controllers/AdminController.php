<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Approval;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\Room;
use App\Models\RoomRequest;
use App\Models\User;
use App\Support\Billing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private function initial(string $name): string
    {
        return mb_strtoupper(mb_substr(trim($name), 0, 1));
    }

    private function approvalCounts(): array
    {
        $g = Approval::where('status', 'pending')->selectRaw('type, count(*) as c')->groupBy('type')->pluck('c', 'type');

        return [
            'all'        => (int) $g->sum(),
            'new_tenant' => (int) ($g['new_tenant'] ?? 0),
            'payment'    => (int) ($g['payment'] ?? 0),
            'document'   => (int) ($g['document'] ?? 0),
        ];
    }

    public function dashboard(): View
    {
        $tenants = fn () => User::where('role', 'tenant');

        // stat boxes
        $totalTenants = $tenants()->count();
        $newThisMonth = $tenants()->where('created_at', '>=', now()->startOfMonth())->count();

        // room map: maintenance > occupied > vacant
        $taken = $tenants()->whereNotNull('room_number')->pluck('room_number')->map(fn ($r) => (string) $r)->unique()->all();
        $tiles = Room::orderBy('room_number')->get()->map(fn ($r) => [
            'number' => $r->room_number,
            'state'  => $r->under_maintenance ? 'maint' : (in_array((string) $r->room_number, $taken, true) ? 'occ' : 'vacant'),
        ]);
        $occupied   = $tiles->where('state', 'occ')->count();
        $totalRooms = $tiles->count();
        $pct        = $totalRooms ? (int) round($occupied / $totalRooms * 100) : 0;

        // payments
        $unpaid      = fn () => $tenants()->whereNotNull('room_number')->where('payment_status', '!=', 'up_to_date');
        $unpaidCount = $unpaid()->count();
        $outstanding = (float) $unpaid()->sum('monthly_rent');
        $overdue     = $tenants()->where('payment_status', 'overdue')->count();

        // maintenance requests
        $maint      = fn () => RoomRequest::where('type', 'maintenance')->where('status', 'pending');
        $maintCount = $maint()->count();
        $maintHigh  = $maint()->where('urgency', 'high')->count();

        // approvals
        $counts = $this->approvalCounts();
        $labels = ['new_tenant' => 'New tenant', 'payment' => 'Payment', 'document' => 'Document'];
        $approvals = Approval::with('user')->where('status', 'pending')->latest()->get()->map(fn ($a) => [
            'id'      => $a->id,
            'type'    => $a->type,
            'label'   => $labels[$a->type] ?? ucfirst($a->type),
            'name'    => $a->user->display_name,
            'initial' => $this->initial($a->user->display_name),
            'detail'  => $a->title . ' · ' . $a->created_at->diffForHumans(),
            'href'    => $a->type === 'payment' ? route('admin.payments') : route('admin.tenants'),
        ]);

        // announcements
        $announcements = Announcement::latest()->get()->map(fn ($a) => $this->annPayload($a));

        // recent tenants
        $recentTenants = User::where('role', 'tenant')->whereNotNull('room_number')->latest('id')->limit(5)->get()->map(function ($u) {
            $status = 'Up to date';
            $class  = 'rt-ok';

            if ($u->payment_status === 'overdue') {
                $status = 'Overdue';
                $class  = 'rt-over';
            } elseif ($u->payment_status !== 'up_to_date') {
                $sub    = Billing::forUser($u)['next_due_sub'] ?? 'Payment due';
                $status = str_starts_with($sub, 'In ') ? 'Due ' . lcfirst($sub) : $sub;
                $class  = str_contains($sub, 'overdue') ? 'rt-over' : 'rt-due';
            }

            return [
                'id'      => $u->id,
                'name'    => $u->display_name,
                'initial' => $this->initial($u->display_name),
                'room'    => $u->room_number,
                'moved'   => $u->move_in_date ? Carbon::parse($u->move_in_date)->format('F j, Y') : '—',
                'status'  => $status,
                'class'   => $class,
            ];
        });

        // complaints
        $openComplaints = Complaint::where('status', 'pending')->count();
        $complaints = Complaint::with('user')->latest()->limit(4)->get()->map(fn ($c) => [
            'id'      => $c->id,
            'name'    => $c->user->display_name,
            'room'    => $c->room_number ? 'Room ' . $c->room_number : 'No room yet',
            'type'    => $c->type_label,
            'message' => $c->message,
            'ago'     => $c->created_at->diffForHumans(),
        ]);

        $subtitle = $counts['all'] . ' approval' . ($counts['all'] === 1 ? '' : 's') . ', '
            . $overdue . ' overdue rent and '
            . $openComplaints . ' open complaint' . ($openComplaints === 1 ? '' : 's') . ' need you today.';

        return view('admin.dashboard', compact(
            'totalTenants', 'newThisMonth', 'occupied', 'totalRooms', 'pct', 'tiles',
            'unpaidCount', 'outstanding', 'overdue', 'maintCount', 'maintHigh',
            'counts', 'approvals', 'announcements', 'recentTenants',
            'complaints', 'openComplaints', 'subtitle'
        ));
    }

    /* ---------- approvals ---------- */

        public function approve(int $id): JsonResponse
    {
        $a = Approval::with('user')->where('status', 'pending')->findOrFail($id);

        // a new-tenant application gets its room only now
        if ($a->type === 'new_tenant' && $a->room_number) {
            $error = $this->assignRoom($a);

            if ($error) {
                return response()->json(['ok' => false, 'message' => $error], 409);
            }
        }

        $a->update(['status' => 'approved']);

        Notification::create([
            'tenant_id' => $a->user_id,
            'title'     => 'Approved',
            'message'   => match ($a->type) {
                'new_tenant' => $a->room_number
                    ? "Your booking was approved! Room {$a->room_number}, Bed {$a->bed_number} ({$a->bed_deck}) is now yours. Welcome to D'aces!"
                    : "Your room application was approved. Welcome to D'aces!",
                'payment'    => 'Your payment was verified.',
                default      => 'Your document was approved.',
            },
        ]);

        return response()->json(['ok' => true, 'counts' => $this->approvalCounts()]);
    }

    /** Gives the tenant the room they applied for. Returns an error message, or null when it worked. */
    private function assignRoom(Approval $a): ?string
    {
        return DB::transaction(function () use ($a) {
            $room = Room::where('room_number', $a->room_number)->lockForUpdate()->first();

            if (! $room || $room->under_maintenance) {
                return 'That room is no longer available. Please reject this application.';
            }

            $taken = User::where('role', 'tenant')
                ->where('room_number', $a->room_number)
                ->where('bed_number', $a->bed_number)
                ->where('id', '!=', $a->user_id)
                ->get()
                ->contains(fn ($t) => strtolower((string) $t->bed_deck) === strtolower((string) $a->bed_deck));

            if ($taken) {
                return 'That bed already belongs to another tenant. Please reject this application.';
            }

            $user = $a->user;
            $user->update([
                'room_number'    => $a->room_number,
                'bed_number'     => $a->bed_number,
                'bed_deck'       => $a->bed_deck,
                'move_in_date'   => $a->move_in_date,
                'monthly_rent'   => $a->monthly_rent,
                'payment_status' => 'up_to_date',
            ]);

            // the booking pays the first month (this used to happen inside book())
            $first = collect(Billing::forUser($user)['invoices'])
                ->firstWhere('period', Carbon::parse($a->move_in_date)->format('Y-m'));

            if ($first) {
                Payment::firstOrCreate(
                    ['user_id' => $user->id, 'period' => $first['period']],
                    [
                        'amount'    => $first['amount'],
                        'method'    => 'Booking',
                        'reference' => $first['ref'],
                        'paid_at'   => now(),
                    ]
                );
            }

            return null;
        });
    }

    public function reject(int $id): JsonResponse
    {
        $a = Approval::with('user')->where('status', 'pending')->findOrFail($id);
        $a->update(['status' => 'rejected']);

        // Old rows (made before this change) already had the room on the tenant, so free it.
        // New rows never got a room, so there is nothing to free.
        if ($a->type === 'new_tenant' && ! $a->room_number) {
            $a->user->update([
                'room_number' => null, 'bed_number' => null, 'bed_deck' => null,
                'move_in_date' => null, 'monthly_rent' => null, 'payment_status' => 'due',
            ]);
        }

        Notification::create([
            'tenant_id' => $a->user_id,
            'title'     => 'Not approved',
            'message'   => match ($a->type) {
                'new_tenant' => 'Your room application was declined. The bed was released, so you can book another room.',
                'payment'    => 'We could not verify your payment. Please contact the admin.',
                default      => 'Please upload your document again.',
            },
        ]);

        return response()->json(['ok' => true, 'counts' => $this->approvalCounts()]);
    }
    /* ---------- announcements ---------- */

    private function annRules(): array
    {
        return [
            'title'    => ['required', 'string', 'max:60'],
            'audience' => ['required', Rule::in(['All tenants', 'Unpaid only'])],
            'message'  => ['required', 'string', 'max:150'],
        ];
    }

    private function annPayload(Announcement $a): array
    {
        return [
            'id'       => $a->id,
            'title'    => $a->title ?: 'Announcement',
            'audience' => $a->audience ?: 'All tenants',
            'message'  => $a->message,
            'ago'      => $a->created_at->diffForHumans(),
        ];
    }

       public function announcementStore(Request $request): JsonResponse
    {
        $a = Announcement::create($request->validate($this->annRules()) + ['is_active' => true]);

        $this->notifyTenants($a);

        return response()->json($this->annPayload($a));
    }

    /** Puts a bell notification in every tenant who should get this announcement. */
    private function notifyTenants(Announcement $a): void
    {
        $unpaidOnly = ($a->audience ?: 'All tenants') === 'Unpaid only';

        User::where('role', 'tenant')->get()
            ->filter(fn ($u) => $u->wantsNotification('email_announcements'))
            ->filter(fn ($u) => ! $unpaidOnly || ($u->room_number && $u->payment_status !== 'up_to_date'))
            ->each(fn ($u) => Notification::create([
                'tenant_id' => $u->id,
                'title'     => 'New announcement: ' . ($a->title ?: 'Announcement'),
                'message'   => Str::limit($a->message, 100),
            ]));
    }

    public function announcementUpdate(Request $request, int $id): JsonResponse
    {
        $a = Announcement::findOrFail($id);
        $a->update($request->validate($this->annRules()));

        return response()->json($this->annPayload($a));
    }

    public function announcementDestroy(int $id): JsonResponse
    {
        Announcement::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}