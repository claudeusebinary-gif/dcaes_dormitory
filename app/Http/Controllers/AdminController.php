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
use App\Support\DormStats;
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
               $outstanding = (float) $unpaid()->get()->sum(fn ($u) => Billing::rentFor($u));
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
public function tenants(): View
{
    $rows = User::where('role', 'tenant')->whereNotNull('room_number')->orderBy('full_name')->get();

    $list = $rows->map(function ($u) {
        $moved = $u->move_in_date ? Carbon::parse($u->move_in_date) : null;

        return [
            'id'       => $u->id,
            'name'     => $u->display_name,
            'email'    => $u->email,
            'initial'  => mb_strtoupper(mb_substr(trim($u->display_name), 0, 1)),
            'room'     => (string) $u->room_number,
            'floor'    => (int) floor(((int) $u->room_number) / 100),
            'bed'      => $u->bed_number ? 'Bed ' . $u->bed_number . ($u->bed_deck ? ', ' . $u->bed_deck : '') : 'No bed',
            'since'    => $moved ? $moved->format('M j, Y') : '—',
            'since_ts' => $moved ? $moved->timestamp : 0,
            'status'   => match ($u->payment_status) { 'up_to_date' => 'paid', 'overdue' => 'overdue', default => 'due' },
        ];
    })->values();

    $total  = $list->count();
    $counts = [
        'paid'    => $list->where('status', 'paid')->count(),
        'due'     => $list->where('status', 'due')->count(),
        'overdue' => $list->where('status', 'overdue')->count(),
    ];

    $stays = $rows->filter(fn ($u) => $u->move_in_date && Carbon::parse($u->move_in_date)->lte(now()))
        ->map(fn ($u) => abs(Carbon::parse($u->move_in_date)->diffInMonths(now())));

    $roomsUsed = $list->pluck('room')->unique()->count();
    $newCount  = $rows->filter(fn ($u) => $u->created_at && $u->created_at->gte(now()->startOfMonth()))->count();
    $avgStay   = $stays->count() ? round($stays->avg(), 1) : 0;
    $bedsTotal = (int) Room::sum('total_beds');
    $occPct    = $bedsTotal ? (int) round($total / $bedsTotal * 100) : 0;

    return view('admin.tenants', compact('list', 'total', 'counts', 'roomsUsed', 'newCount', 'avgStay', 'bedsTotal', 'occPct'));
}
public function tenantShow(int $id): View
{
    $tenant = User::where('role', 'tenant')->findOrFail($id);

    /* ---- billing: the same data the tenant sees in Settings > Billing ---- */
    $bill     = Billing::forUser($tenant);
    $history  = $bill['history'] ?? [];
    $paidRows = Payment::where('user_id', $tenant->id)->get()->keyBy('period');

    $overdueCount = collect($bill['invoices'] ?? [])->where('status', 'overdue')->count();
    $late = collect($history)->filter(function ($h) use ($paidRows) {
        $p = $paidRows->get($h['period']);
        return $p && $p->paid_at->copy()->startOfDay()->gt($h['due']);
    })->count();
    $billsDue = count($history) + $overdueCount;
    $onTime   = count($history) - $late;

    $closed = ['resolved', 'closed'];
    $stats = [
        'rent'       => $bill['rent_text'],
        'due_text'   => $bill['due_day_text'] ? 'Due on the ' . $bill['due_day_text'] : 'No room yet',
        'total'      => Billing::money((float) collect($history)->sum('paid_amount')),
        'ontime'     => $billsDue ? round($onTime / $billsDue * 100) . '%' : '—',
        'ontime_sub' => $billsDue ? $onTime . ' of ' . $billsDue . ' bills on time' : 'No bills yet',
        'open'       => Complaint::where('user_id', $tenant->id)->whereNotIn('status', $closed)->count(),
        'resolved'   => Complaint::where('user_id', $tenant->id)->whereIn('status', $closed)->whereYear('updated_at', now()->year)->count(),
    ];

    /* ---- activity log: built from records that already exist ---- */
    $events = collect();

    $events->push(['at' => $tenant->created_at, 'type' => 'account', 'title' => 'Account created', 'detail' => '']);

    if ($tenant->move_in_date) {
        $events->push([
            'at' => Carbon::parse($tenant->move_in_date), 'type' => 'room', 'title' => 'Moved in',
            'detail' => 'Room ' . $tenant->room_number . ($tenant->bed_number ? ', Bed ' . $tenant->bed_number : ''),
        ]);
    }

    foreach ($paidRows as $p) {
        $events->push([
            'at' => $p->paid_at, 'type' => 'payment', 'title' => 'Paid ' . Billing::money((float) $p->amount),
            'detail' => Carbon::parse($p->period . '-01')->format('F Y') . ' rent · ' . $p->method,
        ]);
    }

    foreach (Complaint::where('user_id', $tenant->id)->get() as $c) {
        $events->push(['at' => $c->created_at, 'type' => 'complaint', 'title' => 'Filed a complaint', 'detail' => $c->type_label]);
    }

    foreach (RoomRequest::where('user_id', $tenant->id)->get() as $r) {
        $events->push([
            'at' => $r->created_at, 'type' => 'request',
            'title' => match ($r->type) {
                'maintenance' => 'Sent a maintenance request',
                'transfer'    => 'Requested a room transfer',
                'move_out'    => 'Requested to move out',
                default       => 'Sent a request',
            },
            'detail' => match ($r->type) {
                'maintenance' => ucfirst((string) $r->category),
                'transfer'    => 'To Room ' . $r->target_room,
                'move_out'    => 'On ' . $r->move_out_date,
                default       => '',
            },
        ]);
    }

    foreach (\App\Models\Suggestion::where('user_id', $tenant->id)->get() as $s) {
        $events->push(['at' => $s->created_at, 'type' => 'suggestion', 'title' => 'Sent a suggestion', 'detail' => '']);
    }

    foreach (\App\Models\MarketplaceItem::where('seller_id', $tenant->id)->get() as $m) {
        $events->push(['at' => $m->created_at, 'type' => 'market', 'title' => 'Listed an item for sale', 'detail' => $m->title]);
    }

    $activity = $events->sortByDesc(fn ($e) => $e['at']->timestamp)->take(30)->values();

    return view('tenant.profile', [
        'profileUser' => $tenant,
        'stats'       => $stats,
        'payHistory'  => $history,
        'activity'    => $activity,
    ]);
}
public function tenantDestroy(Request $request, int $id): JsonResponse
{
    $tenant = User::where('role', 'tenant')->findOrFail($id);
    abort_if($tenant->id === $request->user()->id, 422, "You can't delete your own account.");

    $name = $tenant->display_name;
    $tenant->delete();   // payments, complaints, messages, listings... are removed by the database cascades

    return response()->json(['ok' => true, 'message' => $name . ' was deleted.']);
}

public function announcements(): View
{
      $annItems = Announcement::latest()->get()
        ->map(fn ($a) => $this->annPayload($a))
        ->values();

    return view('admin.announcement', compact('annItems'));
}

public function rooms(): View
{
    $rooms   = Room::orderBy('room_number')->get();
    $all     = User::where('role', 'tenant')->whereNotNull('room_number')->get();
    $tenants = $all->groupBy('room_number');
    $decks   = DormStats::DECKS_PER_BED;

    $occupiedBeds = 0;
    $vacant = 0;
    $full = 0;

    foreach ($rooms as $room) {
        $in = $tenants->get($room->room_number, collect());

        if ($in->count() >= $room->total_beds * $decks) $full++;
        if ($in->isEmpty() && ! $room->under_maintenance) $vacant++;

        for ($b = 1; $b <= $room->total_beds; $b++) {
            if ($in->where('bed_number', $b)->count() >= $decks) $occupiedBeds++;
        }
    }

    return view('admin.rooms', [
        'rooms'   => $rooms,
        'tenants' => $tenants,
        'summary' => [
            'total'         => $rooms->count(),
            'occupied_beds' => $occupiedBeds,
            'vacant'        => $vacant,
            'full'          => $full,
            'revenue'       => $all->sum(fn ($t) => Billing::rentFor($t)),
        ],
    ]);
}

private function roomExtras(): array
{
    return [
        'amenities'    => ['nullable', 'array', 'max:20'],
        'amenities.*'  => ['string', 'max:40'],
        'keep'         => ['nullable', 'array'],
        'keep.*'       => ['string'],
        'new_photos'   => ['nullable', 'array', 'max:8'],
        'new_photos.*' => ['image', 'mimes:jpg,jpeg,png', 'max:5120'],
    ];
}

// keeps the photos the admin kept, saves the new uploads, deletes the removed files
private function storeRoomPhotos(Request $request, array $current = []): array
{
    $photos = array_values(array_intersect((array) $request->input('keep', []), $current));

    foreach ((array) $request->file('new_photos', []) as $file) {
        if (count($photos) >= 8) break;
        $name = 'room-' . Str::random(12) . '.' . $file->extension();
        $file->move(public_path('images/rooms'), $name);
        $photos[] = 'images/rooms/' . $name;
    }

    foreach (array_diff($current, $photos) as $gone) {
        if (str_starts_with($gone, 'images/rooms/room-')) {
            @unlink(public_path($gone));
        }
    }

    return $photos;
}

public function roomStore(Request $request): JsonResponse
{
    // "Room 118" -> "118" (the tenant pages already add the word "Room")
    $request->merge(['room_number' => trim(preg_replace('/^room\s*/i', '', (string) $request->input('room_number')))]);

    $data = $request->validate([
        'room_number'   => ['required', 'string', 'max:20', Rule::unique('rooms', 'room_number')],
        'sharing_type'  => ['required', 'string', 'max:30'],
        'bed_type'      => ['required', 'string', 'max:30'],
        'total_beds'    => ['required', 'integer', 'min:1', 'max:20'],
        'price_per_bed' => ['required', 'numeric', 'min:0', 'max:1000000'],
    ] + $this->roomExtras(), [
        'room_number.required' => 'Enter a room number or name.',
        'room_number.unique'   => 'That room number already exists.',
    ]);

    $photos = $this->storeRoomPhotos($request);
    $draft  = $request->boolean('draft');

    Room::create([
        'room_number'       => $data['room_number'],
        'sharing_type'      => $data['sharing_type'],
        'bed_type'          => $data['bed_type'],
        'total_beds'        => $data['total_beds'],
        'price_per_bed'     => $data['price_per_bed'],
        'photos'            => $photos,
        'photo'             => $photos[0] ?? null,
        'amenities'         => array_values($data['amenities'] ?? []),
        'under_maintenance' => $draft,   // a draft is kept closed for booking
    ]);

    return response()->json([
        'ok'      => true,
        'message' => $draft ? 'Saved as draft. It stays closed for booking until you reopen it.' : 'Room published.',
    ], 201);
}

public function roomUpdate(Request $request, int $id): JsonResponse
{
    $room = Room::findOrFail($id);

    $data = $request->validate([
        'sharing_type'  => ['required', 'string', 'max:30'],
        'bed_type'      => ['required', 'string', 'max:30'],
        'price_per_bed' => ['required', 'numeric', 'min:0', 'max:1000000'],
    ] + $this->roomExtras());

    $photos = $this->storeRoomPhotos($request, $room->photo_paths);

    $room->update([
        'sharing_type'  => $data['sharing_type'],
        'bed_type'      => $data['bed_type'],
        'price_per_bed' => $data['price_per_bed'],
        'photos'        => $photos,
        'photo'         => $photos[0] ?? null,
        'amenities'     => array_values($data['amenities'] ?? []),
    ]);

    return response()->json(['ok' => true, 'message' => 'Room updated.']);
}

public function roomMaintenance(int $id): JsonResponse
{
    $room = Room::findOrFail($id);
    $room->update(['under_maintenance' => ! $room->under_maintenance]);

    return response()->json([
        'ok'      => true,
        'message' => $room->under_maintenance ? 'Room marked as under maintenance.' : 'Room is open for booking again.',
    ]);
}

public function roomDestroy(int $id): JsonResponse
{
    $room = Room::findOrFail($id);

    if (User::where('role', 'tenant')->where('room_number', $room->room_number)->exists()) {
        return response()->json(['message' => 'This room still has tenants. Move them out first.'], 422);
    }

    foreach ($room->photo_paths as $p) {
        if (str_starts_with($p, 'images/rooms/room-')) {
            @unlink(public_path($p));
        }
    }

    $room->delete();

    return response()->json(['ok' => true, 'message' => 'Room deleted.']);
}

public function payments(): View
{
    $payments = Payment::with('user')->latest('paid_at')->get();
    return view('admin.payments', compact('payments'));
}

public function complaints(): View
{
    $complaints = Complaint::with('user')->latest()->get();
    return view('admin.complaints', compact('complaints'));
}

public function approvals(): View
{
    $approvals = Approval::with('user')->latest()->get();
    return view('admin.approvals', compact('approvals'));
}

public function settings(): View
{
    return view('admin.settings', ['admin' => auth()->user()]);
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
            'category' => ['sometimes', Rule::in(['utility', 'safety', 'payment', 'event', 'general'])],
            'pinned'   => ['sometimes', 'boolean'],
            'ends_at'  => ['sometimes', 'nullable', 'date'],
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
            'category' => $a->category ?: 'general',
            'pinned'   => (bool) $a->pinned,
            'posted'   => $a->created_at->format('Y-m-d'),
            'expires'  => $a->ends_at ? $a->ends_at->format('Y-m-d') : null,
        ];
    }

    /** The expiry comes in as a plain date: keep the notice up until the end of that day. */
    private function annData(array $data): array
    {
        if (array_key_exists('ends_at', $data)) {
            $data['ends_at'] = $data['ends_at'] ? Carbon::parse($data['ends_at'])->endOfDay() : null;
        }

        return $data;
    }

    public function announcementStore(Request $request): JsonResponse
    {
        $data = $request->validate(array_merge($this->annRules(), [
            'ends_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:today'],
        ]));

        $a = Announcement::create($this->annData($data) + ['is_active' => true]);

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
       $a->update($this->annData($request->validate($this->annRules())));

        return response()->json($this->annPayload($a));
    }

    public function announcementDestroy(int $id): JsonResponse
    {
        Announcement::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}