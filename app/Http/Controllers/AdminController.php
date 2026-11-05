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
use App\Support\AdminLog;
use App\Models\AdminActivityLog;
use App\Support\DormStats;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

        $totalTenants = $tenants()->count();
        $newThisMonth = $tenants()->where('created_at', '>=', now()->startOfMonth())->count();

        $taken = $tenants()->whereNotNull('room_number')->pluck('room_number')->map(fn ($r) => (string) $r)->unique()->all();
        $tiles = Room::orderBy('room_number')->get()->map(fn ($r) => [
            'number' => $r->room_number,
            'state'  => $r->under_maintenance ? 'maint' : (in_array((string) $r->room_number, $taken, true) ? 'occ' : 'vacant'),
        ]);
        $occupied   = $tiles->where('state', 'occ')->count();
        $totalRooms = $tiles->count();
        $pct        = $totalRooms ? (int) round($occupied / $totalRooms * 100) : 0;

        $unpaid      = fn () => $tenants()->whereNotNull('room_number')->where('payment_status', '!=', 'up_to_date');
        $unpaidCount = $unpaid()->count();
               $outstanding = (float) $unpaid()->get()->sum(fn ($u) => Billing::rentFor($u));
        $overdue     = $tenants()->where('payment_status', 'overdue')->count();

        $maint      = fn () => RoomRequest::where('type', 'maintenance')->where('status', 'pending');
        $maintCount = $maint()->count();
        $maintHigh  = $maint()->where('urgency', 'high')->count();

        $counts = $this->approvalCounts();
               $labels = ['new_tenant' => 'New tenant', 'payment' => 'Payment', 'document' => 'Document',
                   'transfer' => 'Room transfer', 'move_out' => 'Move-out', 'guest' => 'Guest'];
        $approvals = Approval::with('user')->where('status', 'pending')->latest()->get()->map(fn ($a) => [
            'id'      => $a->id,
            'type'    => $a->type,
            'label'   => $labels[$a->type] ?? ucfirst($a->type),
            'name'    => $a->name,
            'initial' => $this->initial($a->name),
            'detail'  => $a->title . ' · ' . $a->created_at->diffForHumans(),
            'href'    => $a->type === 'payment' ? route('admin.payments') : route('admin.approvals'),
        ]);

        $announcements = Announcement::latest()->get()->map(fn ($a) => $this->annPayload($a));

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
       'payHistory'  => collect($history)
    ->map(fn ($h) => $h + ['pay_status' => 'Paid'])
    ->concat($paidRows->filter(fn ($p) => $p->partial)->map(fn ($p) => [
        'period'       => $p->period,
        'label'        => Carbon::parse($p->period . '-01')->format('F Y'),
        'paid_amount'  => (float) $p->amount,
        'method'       => $p->method,
        'paid_at_text' => $p->paid_at->format('M j, Y g:i A'),
        'pay_status'   => 'Partial',
    ]))
    ->sortByDesc('period')
    ->values()
    ->all(),
        'activity'    => $activity,
    ]);
}
public function tenantDestroy(Request $request, int $id): JsonResponse
{
    $tenant = User::where('role', 'tenant')->findOrFail($id);
    abort_if($tenant->id === $request->user()->id, 422, "You can't delete your own account.");

    $name = $tenant->display_name;
    $tenant->delete();
    AdminLog::record('tenants', "Deleted tenant {$name}");

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
    AdminLog::record('rooms', ($draft ? 'Saved draft room ' : 'Published room ') . $data['room_number']);

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
AdminLog::record('rooms', 'Updated room ' . $room->room_number);
    return response()->json(['ok' => true, 'message' => 'Room updated.']);
}

public function roomMaintenance(int $id): JsonResponse
{
    $room = Room::findOrFail($id);
    $room->update(['under_maintenance' => ! $room->under_maintenance]);
AdminLog::record('rooms', ($room->under_maintenance ? 'Marked room ' . $room->room_number . ' as under maintenance' : 'Reopened room ' . $room->room_number . ' for booking'));
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

   AdminLog::record('rooms', 'Deleted room ' . $room->room_number);
    $room->delete();

    return response()->json(['ok' => true, 'message' => 'Room deleted.']);
}

public function payments(): View
{
    $tenants = User::where('role', 'tenant')->whereNotNull('room_number')->orderBy('full_name')->get();
    $today   = now()->startOfDay();
    $rank    = ['overdue' => 0, 'partial' => 1, 'pending' => 2, 'paid' => 3];
    $subs = Approval::where('type', 'payment')->where('status', 'pending')->whereNotNull('period')->oldest()->get()->groupBy('user_id');
       $cards = $tenants->map(function ($u) use ($today, $rank, $subs) {
        $c   = $this->paymentCycle($u);
        $due = $c['due'];

        $sub = match ($c['status']) {
            'paid'    => $c['row'] ? 'Paid ' . $c['row']->paid_at->format('M j') : 'Due ' . $due->format('M j'),
            'partial' => 'Balance ' . Billing::money($c['balance']) . ' · Due ' . $due->format('M j'),
            'overdue' => 'Due ' . $due->format('M j') . ' · ' . max(0, (int) $due->diffInDays($today)) . ' days late',
            default   => 'Due ' . $due->format('M j'),
        };

        return [
            'id'          => $u->id,
            'name'        => $u->display_name,
            'room'        => (string) $u->room_number,
            'bed_no'      => $u->bed_number ? (string) $u->bed_number : '',
            'bed'         => $u->bed_number ? 'BED ' . $u->bed_number : 'NO BED',
            'rent'        => Billing::money($c['rent']),
            'status'      => $c['status'],
            'rank'        => $subs->has($u->id) ? -1 : $rank[$c['status']],
            'submitted'   => (float) (($subs->get($u->id) ?? collect())->sum(fn ($s) => (float) $s->amount + (float) $s->fee)),
            'approve_ids' => ($subs->get($u->id) ?? collect())->pluck('id')->values()->all(),
            'label'       => match ($c['status']) { 'paid' => 'AMOUNT PAID', 'partial' => 'PAID SO FAR', default => 'AMOUNT DUE' },
            'amount'      => Billing::money(in_array($c['status'], ['paid', 'partial']) ? $c['paid'] : $c['rent']),
            'sub'         => $sub,
            'balance'     => $c['balance'],
            'due_ts'      => $due->timestamp,
            'url'         => route('admin.tenants.show', ['id' => $u->id, 'from' => 'payments']) . '#payments',
            'can_record'  => $c['can_record'],
            'target'      => $c['target_label'],
            'target_left' => $c['target_left'],
        ];
    })->sortBy([['rank', 'asc'], ['name', 'asc']])->values();

       $collected = (float) Payment::whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->sum(DB::raw('amount + fee'));
    $pending   = (float) $cards->whereIn('status', ['pending', 'overdue', 'partial'])->sum('balance');
    $weekEnd   = $today->copy()->addDays(7)->endOfDay()->timestamp;

    $summary = [
        'collected' => $collected,
        'pending'   => $pending,
        'overdue'   => $cards->where('status', 'overdue')->count(),
        'rate'      => ($collected + $pending) > 0 ? (int) round($collected / ($collected + $pending) * 100) : 0,
        'due7'      => (float) $cards->whereIn('status', ['pending', 'partial'])
                          ->filter(fn ($c) => $c['due_ts'] >= $today->timestamp && $c['due_ts'] <= $weekEnd)
                          ->sum('balance'),
    ];

    return view('admin.payments', compact('cards', 'summary'));
}

private function paymentCycle(User $u): array
{
    $rent   = Billing::rentFor($u);
    $today  = now()->startOfDay();
    $moveIn = Carbon::parse($u->move_in_date ?? $u->created_at)->startOfDay();
    $dueDay = $moveIn->day;
    $rows   = Payment::where('user_id', $u->id)->get()->keyBy('period');

    $dueOf = fn (Carbon $month) => $month->copy()->day(min($dueDay, $month->daysInMonth));

    $first = $moveIn->copy()->startOfMonth();
    $cycle = $first->copy();
    for ($m = $first->copy(); $m->lte($today->copy()->startOfMonth()); $m->addMonthNoOverflow()) {
        if ($dueOf($m)->lte($today)) $cycle = $m->copy();
    }

    $period = $cycle->format('Y-m');
    $row    = $rows->get($period);

    if ($row && $row->partial) {
        $status = 'partial';
    } elseif ($row) {
        $status = 'paid';
   } else {
    $isFirst = $period === $first->format('Y-m');
    $status  = match ($u->payment_status) {
        'overdue'    => 'overdue',
        'up_to_date' => $isFirst
            ? 'paid'
            : ($dueOf($cycle)->lt($today) ? 'overdue' : 'pending'),
        default      => 'pending',
    };
}

    $paid    = $row ? (float) $row->amount : ($status === 'paid' ? $rent : 0);
    $balance = $status === 'paid' ? 0 : max(0, $rent - ($row && $row->partial ? (float) $row->amount : 0));

    $t = $cycle->copy();
    for ($i = 0; $i < 12; $i++) {
        $r       = $rows->get($t->format('Y-m'));
        $settled = $r ? ! $r->partial : ($t->format('Y-m') === $period && $status === 'paid');
        if (! $settled) break;
        $t->addMonthNoOverflow();
    }
    $tRow = $rows->get($t->format('Y-m'));

    return [
        'rent'          => $rent,
        'period'        => $period,
        'due'           => $dueOf($cycle),
        'status'        => $status,
        'row'           => $row,
        'paid'          => $paid,
        'balance'       => $balance,
        'can_record'    => $status !== 'paid',
        'target_period' => $t->format('Y-m'),
        'target_label'  => $t->format('F Y'),
        'target_left'   => max(0, $rent - ($tRow && $tRow->partial ? (float) $tRow->amount : 0)),
    ];
}

public function paymentStore(Request $request): JsonResponse
{
    $data = $request->validate([
        'tenant_id' => ['required', 'integer'],
        'amount'    => ['required', 'numeric', 'min:1', 'max:1000000'],
        'paid_on'   => ['required', 'date', 'before_or_equal:today'],
    ], [
        'amount.min'              => 'Enter an amount of at least ₱1.',
        'paid_on.before_or_equal' => 'The payment date cannot be in the future.',
    ]);

    $tenant = User::where('role', 'tenant')->whereNotNull('room_number')->findOrFail($data['tenant_id']);
    $c      = $this->paymentCycle($tenant);
    if (! $c['can_record']) {
    throw ValidationException::withMessages([
        'amount' => $tenant->display_name . ' is already paid for ' . Carbon::parse($c['period'] . '-01')->format('F Y') . '. Nothing to record.',
    ]);
}
    $amount = round((float) $data['amount'], 2);

    if ($amount > $c['target_left'] + 0.01) {
        throw ValidationException::withMessages([
            'amount' => 'That is more than the ' . Billing::money($c['target_left']) . ' balance for ' . $c['target_label'] . '.',
        ]);
    }

    $period = $c['target_period'];
    $row    = Payment::firstOrNew(['user_id' => $tenant->id, 'period' => $period]);
    $total  = round((float) ($row->exists ? $row->amount : 0) + $amount, 2);

    $row->fill([
        'amount'    => $total,
        'partial'   => $total + 0.01 < $c['rent'],
        'method'    => 'Recorded by admin',
        'reference' => 'DAC-' . str_replace('-', '', $period) . '-' . str_pad($tenant->id, 4, '0', STR_PAD_LEFT),
        'paid_at'   => Carbon::parse($data['paid_on'])->setTimeFrom(now()),
    ])->save();

    // the current bill is now fully paid -> the tenant is up to date
    if (! $row->partial && $period === $c['period']) {
        $tenant->update(['payment_status' => 'up_to_date']);
    }

    AdminLog::record('payments', 'Recorded ' . Billing::money($amount) . ' payment for ' . $tenant->display_name . ' (' . $c['target_label'] . ')' . ($row->partial ? ' - partial' : ''));

    if ($tenant->wantsNotification('email_payment')) {
        Notification::create([
            'tenant_id' => $tenant->id,
            'title'     => 'Payment received',
            'message'   => 'We recorded your ' . Billing::money($amount) . ' payment for ' . $c['target_label'] . '.'
                         . ($row->partial ? ' Remaining balance: ' . Billing::money(max(0, $c['rent'] - $total)) . '.' : ' Thank you!'),
        ]);
    }

    return response()->json([
        'ok'      => true,
        'message' => 'Recorded ' . Billing::money($amount) . ' for ' . $tenant->display_name . ($row->partial ? ' (partial)' : '') . '.',
    ], 201);
}

public function paymentRemind(int $id): JsonResponse
{
    $tenant = User::where('role', 'tenant')->whereNotNull('room_number')->findOrFail($id);
    $c      = $this->paymentCycle($tenant);

    if ($c['status'] === 'paid') {
        return response()->json(['message' => $tenant->display_name . ' has nothing to pay right now.'], 422);
    }

    $already = Notification::where('tenant_id', $tenant->id)
        ->where('title', 'Payment reminder')
        ->whereDate('created_at', today())
        ->exists();

    if ($already) {
        return response()->json(['message' => 'A reminder was already sent to ' . $tenant->display_name . ' today.'], 422);
    }

    $month = $c['due']->format('F Y');
    $owed  = Billing::money($c['balance']);

    Notification::create([
        'tenant_id' => $tenant->id,
        'title'     => 'Payment reminder',
        'message'   => $c['status'] === 'overdue'
            ? "Your rent of {$owed} for {$month} is overdue (it was due {$c['due']->format('M j')}). Please settle it as soon as you can."
            : "Friendly reminder: {$owed} for {$month} is due on {$c['due']->format('M j')}.",
    ]);
AdminLog::record('payments', 'Sent payment reminder to ' . $tenant->display_name);
    return response()->json(['ok' => true, 'message' => 'Reminder sent to ' . $tenant->display_name . '.']);
}

public function complaints(): View
{
    $map   = ['pending' => 'open', 'in_progress' => 'progress', 'resolved' => 'resolved', 'closed' => 'resolved'];
    $label = ['open' => 'OPEN', 'progress' => 'IN PROGRESS', 'resolved' => 'RESOLVED'];
    $fmt   = fn ($d) => $d ? $d->format('M j, Y · g:i A') : null;

    $all = Complaint::with('user')->latest()->get();

    $cards = $all->map(function ($c) use ($map, $label, $fmt) {
        $s = $map[$c->status] ?? 'open';
        return [
            'id'          => $c->id,
            'status'      => $s,
            'label'       => $label[$s],
            'name'        => $c->user->display_name,
            'room'        => $c->room_number ?: '—',
            'bed'         => $c->user->bed_number,
            'type'        => $c->type,
            'type_label'  => $c->type_label,
            'high'        => $c->is_high,
            'title'       => $c->title,
            'message'     => $c->message,
            'phone'       => $c->user->phone_number,
            'email'       => $c->user->email,
            'filed'       => $c->created_at->format('M j'),
            'filed_full'  => $fmt($c->created_at),
            'progress_at' => $fmt($c->in_progress_at),
            'resolved_at' => $fmt($c->resolved_at),
            'status_url'  => route('admin.complaints.status', $c->id),
        ];
    });

    $stats = [
        'open'     => $cards->where('status', 'open')->count(),
        'progress' => $cards->where('status', 'progress')->count(),
        'month'    => $all->filter(fn ($c) => in_array($c->status, ['resolved', 'closed'])
                        && ($c->resolved_at ?? $c->updated_at)->isCurrentMonth())->count(),
        'high'     => $cards->where('status', '!=', 'resolved')->where('high', true)->count(),
    ];

    $tenants = User::where('role', 'tenant')->whereNotNull('room_number')->get()->sortBy('display_name');

    return view('admin.complaints', compact('cards', 'stats', 'tenants'));
}

public function complaintStore(Request $request): JsonResponse
{
    $data = $request->validate([
        'tenant_id' => ['required', 'integer'],
        'type'      => ['required', Rule::in(array_keys(Complaint::TYPES))],
        'message'   => ['required', 'string', 'max:1000'],
    ], [
        'type.required'    => 'Please select a category.',
        'message.required' => 'Please describe what happened.',
    ]);

    $tenant = User::where('role', 'tenant')->whereNotNull('room_number')->findOrFail($data['tenant_id']);

    Complaint::create([
        'user_id'     => $tenant->id,
        'room_number' => $tenant->room_number,
        'type'        => $data['type'],
        'message'     => $data['message'],
        'status'      => 'pending',
    ]);
AdminLog::record('complaints', 'Filed a ' . $data['type'] . ' complaint for ' . $tenant->display_name);
    return response()->json(['ok' => true, 'message' => 'Complaint filed.']);
}

public function complaintStatus(Request $request, int $id): JsonResponse
{
    $data = $request->validate(['status' => ['required', Rule::in(['in_progress', 'resolved'])]]);
    $c = Complaint::findOrFail($id);

    $c->in_progress_at ??= now();
    $c->status = $data['status'];
    if ($data['status'] === 'resolved') {
        $c->resolved_at = now();
    }
    $c->save();
AdminLog::record('complaints', ($data['status'] === 'resolved' ? 'Resolved' : 'Started progress on') . ' complaint #' . $c->id . ' (' . ($c->user?->display_name ?? 'unknown tenant') . ')');
    return response()->json(['ok' => true, 'message' => $data['status'] === 'resolved' ? 'Marked as resolved.' : 'Marked as in progress.']);
}

public function approvals(): View
{
    $labels = Approval::TYPES;
    $fmt    = fn ($d) => $d ? $d->format('M j, Y · g:i A') : null;

    $rows = Approval::with('user.emergencyContact')
        ->whereIn('type', array_keys($labels))
        ->latest()
        ->get();

    $cards = $rows->map(function ($a) use ($labels, $fmt) {
        $u     = $a->user;
        $ec    = $u?->emergencyContact;
        $reqs  = $this->approvalRequirements($a);
        $words = preg_split('/\s+/', trim($a->name));
        $initials = collect($words)->take(2)
            ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
        $last  = count($words) > 1 ? array_pop($words) : '';
        $first = implode(' ', $words);
              preg_match('/\d+/', $a->room_text, $m);

        $rr = ($a->user_id && in_array($a->type, ['transfer', 'move_out'], true))
            ? RoomRequest::where('user_id', $a->user_id)->where('type', $a->type)
                ->whereBetween('created_at', [$a->created_at->copy()->subMinute(), $a->created_at->copy()->addMinute()])
                ->first()
            : null;
        $reasonNames = [
            'graduating' => 'Graduating', 'transfer' => 'Transferring to another dorm', 'moving_off' => 'Moving off',
            'financial' => 'Financial reasons', 'family' => 'Family reasons',
            'roommate_conflict' => 'Roommate conflict', 'noise' => 'Noise / Environment',
            'entrance' => 'Closer to entrance', 'medical' => 'Medical / accessibility need', 'other' => 'Other',
        ];

        return [
            'id'             => $a->id,
            'status'         => $a->status,
            'label'          => strtoupper($a->status),
            'type'           => $a->type,
            'type_label'     => $labels[$a->type],
            'name'           => $a->name,
            'initials'       => $initials,
            'room'           => $m[0] ?? '',
            'room_text'      => $a->room_text,
            'manual'         => (bool) $a->is_manual,
            'submitted'      => $a->created_at->format('M j'),
            'submitted_full' => $fmt($a->created_at),
            'decided'        => $a->status === 'pending' ? null : $fmt($a->updated_at),
            'reqs'           => $reqs,
            'met'            => collect($reqs)->where('met', true)->count(),
            'total'          => count($reqs),
            'approve_url'    => route('admin.approvals.approve', $a->id),
            'reject_url'     => route('admin.approvals.reject', $a->id),
            'check_url'      => route('admin.approvals.check', $a->id),
            'person'         => [
                'first'      => $first,
                'last'       => $last,
                'occupation' => $a->occupation ?: $u?->occupation,
                'civil'      => $a->civil_status ?: $u?->civil_status,
                'birthdate'  => $u?->birthday ? $u->birthday->format('F j, Y') : null,
                'gender'     => $u?->sex ? ucfirst($u->sex) : null,
                'school'     => $a->school,
            ],
            'contact'        => [
                'phone'   => $u?->phone_number,
                'email'   => $u?->email,
                'address' => $u?->address,
            ],
            'emergency'      => [
                'name'         => $ec?->name,
                'relationship' => $ec?->relationship,
                'phone'        => $ec?->phone,
            ],
                       'idcard'         => ['type' => $a->id_type, 'number' => $a->id_number],
            'request'        => $rr ? [
                'from'          => $rr->room_number ? 'Room ' . $rr->room_number : null,
                'to'            => $rr->target_room ? 'Room ' . $rr->target_room . ', Bed ' . $rr->target_bed . ' (' . $rr->target_deck . ')' : null,
                'move_out_date' => $rr->move_out_date ? Carbon::parse($rr->move_out_date)->format('F j, Y') : null,
                'reason'        => $reasonNames[$rr->reason] ?? ucfirst(str_replace('_', ' ', (string) $rr->reason)),
                'details'       => $rr->details,
            ] : null,
            'movein'         => [
                'date'   => $a->move_in_date?->format('F j, Y'),
                'stay'   => $a->length_of_stay,
                'agreed' => (bool) $a->agreed,
            ],
        ];
    });

    $stats = [
        'pending'  => $cards->where('status', 'pending')->count(),
        'approved' => $rows->where('status', 'approved')->filter(fn ($a) => $a->updated_at->isCurrentMonth())->count(),
        'rejected' => $rows->where('status', 'rejected')->filter(fn ($a) => $a->updated_at->isCurrentMonth())->count(),
        'movein'   => $cards->where('status', 'pending')->where('type', 'new_tenant')->count(),
    ];

    return view('admin.approvals', compact('cards', 'stats'));
}

private function approvalRequirements(Approval $a): array
{
    $u      = $a->user;
    $checks = (array) $a->checks;
    $clear  = $u?->payment_status === 'up_to_date';

    $defs = match ($a->type) {
        'new_tenant' => [
            ['id',    'Valid ID verified',   (bool) ($a->id_type && $a->id_number)],
            ['bed',   'Bed still available', $this->approvalBedFree($a)],
            ['terms', 'Terms agreed',        (bool) $a->agreed],
        ],
        'transfer' => [
            ['balance', 'Current balance clear',         $clear],
            ['bed',     'New bed confirmed available',   false],
            ['manager', 'Manager notified',              false],
        ],
        'move_out' => [
            ['notice', '30-days notice given', false],
            ['bill',   'Final bill settled',   $clear],
            ['key',    'Key returned',         false],
        ],
        'guest' => [
            ['id',    'Guest ID checked',       false],
            ['host',  'Host tenant agrees',     false],
            ['rules', 'House rules explained',  false],
        ],
        default => [],
    };

    return array_map(fn ($d) => [
        'key'   => $d[0],
        'label' => $d[1],
        'auto'  => (bool) $d[2],
        'met'   => (bool) $d[2] || ! empty($checks[$d[0]]),
    ], $defs);
}

private function approvalBedFree(Approval $a): bool
{
    if (! $a->room_number) {
        return false;
    }

    $room = Room::where('room_number', $a->room_number)->first();
    if (! $room || $room->under_maintenance) {
        return false;
    }

    return ! User::where('role', 'tenant')
        ->where('room_number', $a->room_number)
        ->where('bed_number', $a->bed_number)
        ->where('id', '!=', $a->user_id)
        ->get()
        ->contains(fn ($t) => strtolower((string) $t->bed_deck) === strtolower((string) $a->bed_deck));
}

public function approvalStore(Request $request): JsonResponse
{
    $data = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'type' => ['required', Rule::in(array_keys(Approval::TYPES))],
        'room' => ['required', 'string', 'max:60'],
    ], [
        'name.required' => 'Please enter the applicant name.',
        'type.required' => 'Please choose a request type.',
        'room.required' => 'Please enter the room / bed.',
    ]);

    Approval::create([
        'user_id'        => null,
        'type'           => $data['type'],
        'title'          => 'Manual ' . strtolower(Approval::TYPES[$data['type']]) . ' · ' . trim($data['room']),
        'status'         => 'pending',
        'applicant_name' => trim($data['name']),
        'room_label'     => trim($data['room']),
        'is_manual'      => true,
    ]);
AdminLog::record('approvals', 'Logged manual ' . strtolower(Approval::TYPES[$data['type']]) . ' request for ' . trim($data['name']));
    return response()->json(['ok' => true, 'message' => 'Request logged.']);
}

public function approvalCheck(Request $request, int $id): JsonResponse
{
    $a    = Approval::with('user')->where('status', 'pending')->findOrFail($id);
    $data = $request->validate([
        'key'   => ['required', 'string', 'max:20'],
        'value' => ['present', 'boolean'],
    ]);

    abort_unless(in_array($data['key'], array_column($this->approvalRequirements($a), 'key'), true), 422);

    $checks = (array) $a->checks;
    $checks[$data['key']] = (bool) $data['value'];
    $a->update(['checks' => $checks]);
AdminLog::record('approvals', ($data['value'] ? 'Checked' : 'Unchecked') . ' requirement "' . $data['key'] . '" for ' . ($a->user?->display_name ?? $a->applicant_name ?? 'a request'));
    $reqs = $this->approvalRequirements($a->fresh('user'));

    return response()->json([
        'ok'    => true,
        'reqs'  => $reqs,
        'met'   => collect($reqs)->where('met', true)->count(),
        'total' => count($reqs),
    ]);
}
public function settings(Request $request): View
{
    return view('admin.settings', [
        'admin'    => auth()->user(),
        'staff'    => User::where('role', 'admin')->whereNotNull('staff_role')->orderBy('id')->get(),
        'pm'       => DormStats::paymentMethods(),
        'rr'       => DormStats::roomSettings(),
        'profile'  => DormStats::profile(),
        'sec'      => DormStats::security(),
        'history'  => AdminActivityLog::latest()->limit(200)->get(),
        'sessions' => app(TenantController::class)->activeSessions($request),
    ]);
}

public function staffStore(Request $request): JsonResponse
{
    $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
    $data = $request->validate([
        'full_name'  => ['required', 'string', 'max:100'],
        'email'      => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
        'staff_role' => ['required', Rule::in(['owner', 'front_desk', 'maintenance'])],
        'password'   => ['required', 'string', 'min:8', 'max:100'],
    ]);
    User::create($data + ['role' => 'admin']);
    AdminLog::record('staff-access', "Added staff member {$data['full_name']} ({$data['staff_role']})");

    return response()->json(['ok' => true, 'message' => 'Staff member added.'], 201);
}

public function staffUpdate(Request $request, int $id): JsonResponse
{
    $m = User::where('role', 'admin')->whereNotNull('staff_role')->findOrFail($id);
    if ($m->id === auth()->id()) {
        return response()->json(['message' => "You can't edit your own account here."], 422);
    }
    $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
    $data = $request->validate([
        'full_name'  => ['required', 'string', 'max:100'],
        'email'      => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($m->id)],
        'staff_role' => ['required', Rule::in(['owner', 'front_desk', 'maintenance'])],
        'password'   => ['nullable', 'string', 'min:8', 'max:100'],
    ]);
    if (empty($data['password'])) unset($data['password']);
    $before = $m->only(['full_name','email','staff_role']); $pwChanged = ! empty($data['password']);
    $m->update($data);
    AdminLog::changed('staff-access', "Edited staff member {$m->full_name}", $before, $m->only(['full_name','email','staff_role']));
    if ($pwChanged) AdminLog::record('staff-access', "Reset password for {$m->full_name}");

    return response()->json(['ok' => true, 'message' => 'Staff member updated.']);
}
public function staffMakeOwner(int $id): JsonResponse
{
    abort_unless(auth()->user()->staff_role === 'owner', 403, 'Only an owner can do this.');

    $m = User::where('role', 'admin')->whereNotNull('staff_role')->findOrFail($id);
    if ($m->staff_role === 'owner') {
        return response()->json(['message' => 'Already an owner.'], 422);
    }
    $m->update(['staff_role' => 'owner']);
    AdminLog::record('staff-access', "Made {$m->full_name} an owner");

    return response()->json(['ok' => true, 'message' => $m->full_name . ' is now an owner.']);
}
public function staffDestroy(int $id): JsonResponse
{
    abort_unless(auth()->user()->staff_role === 'owner', 403, 'Only an owner can do this.');
    $m = User::where('role', 'admin')->whereNotNull('staff_role')->findOrFail($id);
    if ($m->id === auth()->id()) {
        return response()->json(['message' => "You can't remove your own account."], 422);
        
    }
    AdminLog::record('staff-access', "Removed staff member {$m->full_name} ({$m->email})");
    $m->delete();

    return response()->json(['ok' => true, 'message' => 'Staff member removed.']);
}

public function savePaymentMethods(Request $request): JsonResponse
{
    $data = $request->validate([
        'methods'       => ['required', 'array'],
        'methods.gcash' => ['required', 'boolean'],
        'methods.card'  => ['required', 'boolean'],
        'methods.bank'  => ['required', 'boolean'],
    ]);
    if (! in_array(true, array_map('boolval', $data['methods']), true)) {
        return response()->json(['errors' => ['methods' => ['Keep at least one payment method on.']]], 422);
    }
    $old = DormStats::paymentMethods();
    DormStats::putSetting('payment_methods', $data['methods']);
    AdminLog::changed('payment-methods', 'Updated payment methods', $old, array_map('boolval', $data['methods']));

    return response()->json(['ok' => true]);
}

public function saveRoomRent(Request $request): JsonResponse
{
    $data = $request->validate([
        'capacity'    => ['required', 'integer', 'min:1', 'max:20'],
        'rate'        => ['required', 'numeric', 'min:0', 'max:1000000'],
        'due_day'     => ['nullable', 'integer', 'min:1', 'max:31'],
        'grace'       => ['required', 'integer', 'min:0', 'max:60'],
        'late_fee'    => ['required', 'numeric', 'min:0', 'max:100000'],
        'auto_full'   => ['required', 'boolean'],
        'deposit'     => ['required', 'boolean'],
        'amenities'   => ['nullable', 'array', 'max:30'],
        'amenities.*' => ['string', 'max:40'],
    ]);
    $data['amenities'] = array_values(array_unique(array_filter(array_map('trim', $data['amenities'] ?? []))));
    $old = DormStats::roomSettings();
    DormStats::putSetting('room_rent', $data);
    AdminLog::changed('room-rent', 'Updated room & rent settings', $old, $data);

    return response()->json(['ok' => true]);
}
public function saveGeneral(Request $request): JsonResponse
{
    $data = $request->validate([
        'name'    => ['required', 'string', 'max:100'],
        'phone'   => ['nullable', 'string', 'max:30'],
        'address' => ['nullable', 'string', 'max:200'],
        'email'   => ['nullable', 'email', 'max:255'],
    ]);

    $old = DormStats::profile();
    $new = [
        'name'    => trim($data['name']),
        'phone'   => trim($data['phone'] ?? ''),
        'address' => trim($data['address'] ?? ''),
        'email'   => trim($data['email'] ?? ''),
        'logo'    => DormStats::profile()['logo'],
    ];
    DormStats::putSetting('dorm_profile', $new);
    AdminLog::changed('general', 'Updated dormitory details', $old, $new);

    return response()->json(['ok' => true]);
}

public function uploadLogo(Request $request): JsonResponse
{
    $request->validate(['logo' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048']]);

    $profile = DormStats::profile();
    $file    = $request->file('logo');
    $name    = 'logo-' . Str::random(10) . '.' . $file->extension();
    $file->move(public_path('images/logo'), $name);

    if ($profile['logo'] && str_starts_with($profile['logo'], 'images/logo/logo-')) {
        @unlink(public_path($profile['logo']));
    }

    $profile['logo'] = 'images/logo/' . $name;
    DormStats::putSetting('dorm_profile', $profile);
    AdminLog::record('general', 'Uploaded a new logo');

    return response()->json(['ok' => true, 'url' => asset($profile['logo'])]);
}

public function removeLogo(): JsonResponse
{
    $profile = DormStats::profile();

    if ($profile['logo'] && str_starts_with($profile['logo'], 'images/logo/logo-')) {
        @unlink(public_path($profile['logo']));
    }

    $profile['logo'] = null;
    DormStats::putSetting('dorm_profile', $profile);
    AdminLog::record('general', 'Removed the logo');

    return response()->json(['ok' => true]);
}

public function saveSecurity(Request $request): JsonResponse
{
    $data = $request->validate([
        'idle_logout'  => ['required', 'boolean'],
        'idle_minutes' => ['required', 'integer', Rule::in([15, 30, 60, 120])],
    ]);

    $old = DormStats::security();
    DormStats::putSetting('admin_security', $data);
    AdminLog::changed('security', 'Updated security settings', $old, $data);

    return response()->json(['ok' => true]);
}

public function logoutOtherSessions(Request $request): JsonResponse
{
    DB::table('sessions')
        ->where('user_id', $request->user()->id)
        ->where('id', '!=', $request->session()->getId())
        ->delete();
    AdminLog::record('security', 'Logged out all other devices');

    return response()->json(['ok' => true, 'message' => 'All other devices were logged out.']);
}
public function saveFrontDesk(Request $request): JsonResponse
{
    $data = $request->validate([
        'hours'        => ['required', 'array'],
        'hours.*.open' => ['required', 'boolean'],
        'hours.*.from' => ['required', 'date_format:H:i'],
        'hours.*.to'   => ['required', 'date_format:H:i'],
    ]);

    $clean = [];
    foreach (DormStats::DAYS as $day) {
        $h = $data['hours'][$day] ?? null;
        if (! $h) {
            continue;
        }
        if ($h['open'] && $h['to'] <= $h['from']) {
            throw ValidationException::withMessages([
                "hours.$day.to" => "$day: closing time must be after opening time.",
            ]);
        }
        $clean[$day] = ['open' => (bool) $h['open'], 'from' => $h['from'], 'to' => $h['to']];
    }

    $old = DormStats::frontDesk();
    DormStats::putSetting('front_desk_hours', $clean);
    AdminLog::changed('general', 'Updated front desk hours', $old, $clean);

    return response()->json(['ok' => true]);
}

public function saveNotifications(Request $request): JsonResponse
{
    $rules = ['prefs' => ['required', 'array']];
    foreach (array_keys(DormStats::notificationPrefs()) as $key) {
        foreach (['email', 'sms', 'push'] as $ch) {
            $rules["prefs.$key.$ch"] = ['required', 'boolean'];
        }
    }
    $data = $request->validate($rules);

    $old = DormStats::notificationPrefs();
    DormStats::putSetting('admin_notifications', $data['prefs']);
    AdminLog::changed('notification', 'Updated notification preferences', $old, $data['prefs']);

    return response()->json(['ok' => true]);
}
      public function approve(int $id): JsonResponse
{
    $a = Approval::with('user')->where('status', 'pending')->findOrFail($id);

    if (! $a->is_manual && $a->type === 'new_tenant' && $a->room_number) {
        $error = $this->assignRoom($a);

        if ($error) {
            return response()->json(['ok' => false, 'message' => $error], 409);
        }
    }
    if (! $a->is_manual && $a->type === 'transfer' && $a->user_id) {
        $error = $this->applyTransfer($a);

        if ($error) {
            return response()->json(['ok' => false, 'message' => $error], 409);
        }
    }

     $a->update(['status' => 'approved']);
    AdminLog::record('approvals', 'Approved ' . str_replace('_', ' ', $a->type) . ' request from ' . ($a->user?->display_name ?? $a->applicant_name ?? 'unknown'));
    $this->syncRoomRequest($a, 'approved');

    if ($a->type === 'payment' && $a->period) {
        $this->recordPayment($a);
    }

    if ($a->user_id) {
        Notification::create([
            'tenant_id' => $a->user_id,
            'title'     => 'Approved',
            'message'   => match ($a->type) {
                'new_tenant' => $a->room_number
                    ? "Your booking was approved! Room {$a->room_number}, Bed {$a->bed_number} ({$a->bed_deck}) is now yours. Welcome to D'aces!"
                    : "Your room application was approved. Welcome to D'aces!",
                'payment'    => $a->period
                    ? 'We received your ' . Billing::money((float) $a->amount + (float) $a->fee) . ' payment for '
                        . Carbon::parse($a->period . '-01')->format('F Y') . '. Thank you!'
                    : 'Your payment was verified.',
                'transfer'   => 'Your room transfer request was approved.',
                'move_out'   => 'Your move-out request was approved.',
                'guest'      => 'Your guest request was approved.',
                default      => 'Your document was approved.',
            },
        ]);
    }

    return response()->json(['ok' => true, 'message' => 'Request approved.', 'counts' => $this->approvalCounts()]);
}
    private function recordPayment(Approval $a): void
    {
        $tenant = $a->user;
        $rent   = Billing::rentFor($tenant);

        $row   = Payment::firstOrNew(['user_id' => $tenant->id, 'period' => $a->period]);
        $total = round((float) ($row->exists ? $row->amount : 0) + (float) $a->amount, 2);

        $row->fill([
            'amount'    => $total,
            'fee'       => round((float) ($row->exists ? $row->fee : 0) + (float) $a->fee, 2),
            'partial'   => $total + 0.01 < $rent,
           'method'    => \Illuminate\Support\Str::before((string) $a->title, ' payment') ?: 'GCash',
            'reference' => 'DAC-' . str_replace('-', '', $a->period) . '-' . str_pad($tenant->id, 4, '0', STR_PAD_LEFT),
            'paid_at'   => now(),
        ])->save();

        if (! $row->partial
            && collect(Billing::forUser($tenant)['invoices'])->where('status', 'overdue')->isEmpty()) {
            $tenant->update(['payment_status' => 'up_to_date']);
        }
    }
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

           // the booking pays the move-in month
$period = Carbon::parse($a->move_in_date)->format('Y-m');
$first  = collect(Billing::forUser($user)['invoices'])->firstWhere('period', $period);

Payment::firstOrCreate(
    ['user_id' => $user->id, 'period' => $period],
    [
        'amount'    => $first['amount'] ?? Billing::rentFor($user),
        'method'    => 'Booking',
        'reference' => $first['ref'] ?? 'DAC-' . str_replace('-', '', $period) . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
        'paid_at'   => now(),
    ]
);

            return null;
        });
    }
    private function requestFor(Approval $a): ?RoomRequest
    {
        if (! $a->user_id || ! in_array($a->type, ['transfer', 'move_out'], true)) {
            return null;
        }

        return RoomRequest::where('user_id', $a->user_id)
            ->where('type', $a->type)->where('status', 'pending')
            ->latest('id')->first();
    }

    private function syncRoomRequest(Approval $a, string $status): void
    {
        $this->requestFor($a)?->update(['status' => $status]);
    }

    private function applyTransfer(Approval $a): ?string
    {
        $req = $this->requestFor($a);

        if (! $req || ! $req->target_room) {
            return 'The transfer details could not be found. Please reject this request.';
        }

        return DB::transaction(function () use ($a, $req) {
            $room = Room::where('room_number', $req->target_room)->lockForUpdate()->first();

            if (! $room || $room->under_maintenance) {
                return 'That room is no longer available. Please reject this request.';
            }

            $taken = User::where('role', 'tenant')
                ->where('room_number', $req->target_room)
                ->where('bed_number', $req->target_bed)
                ->where('id', '!=', $a->user_id)
                ->get()
                ->contains(fn ($t) => strtolower((string) $t->bed_deck) === strtolower((string) $req->target_deck));

            if ($taken) {
                return 'That bed already belongs to another tenant. Please reject this request.';
            }

            $a->user->update([
                'room_number'  => $room->room_number,
                'bed_number'   => $req->target_bed,
                'bed_deck'     => $req->target_deck,
                'monthly_rent' => $room->price_per_bed,
            ]);

            return null;
        });
    }
  public function reject(int $id): JsonResponse
{
    $a = Approval::with('user')->where('status', 'pending')->findOrFail($id);
     $a->update(['status' => 'rejected']);
    AdminLog::record('approvals', 'Rejected ' . str_replace('_', ' ', $a->type) . ' request from ' . ($a->user?->display_name ?? $a->applicant_name ?? 'unknown'));
    $this->syncRoomRequest($a, 'rejected');

    if (! $a->is_manual && $a->user_id && $a->type === 'new_tenant' && ! $a->room_number) {
        $a->user->update([
            'room_number' => null, 'bed_number' => null, 'bed_deck' => null,
            'move_in_date' => null, 'monthly_rent' => null, 'payment_status' => 'due',
        ]);
    }

    if ($a->user_id) {
        Notification::create([
            'tenant_id' => $a->user_id,
            'title'     => 'Not approved',
            'message'   => match ($a->type) {
                'new_tenant' => 'Your room application was declined. The bed was released, so you can book another room.',
                'payment'    => 'We could not verify your payment. Please contact the admin.',
                'transfer'   => 'Your room transfer request was declined.',
                'move_out'   => 'Your move-out request was declined. Please contact the admin.',
                'guest'      => 'Your guest request was declined.',
                default      => 'Please upload your document again.',
            },
        ]);
    }

    return response()->json(['ok' => true, 'message' => 'Request rejected.', 'counts' => $this->approvalCounts()]);
}

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
        AdminLog::record('announcements', 'Posted announcement: ' . ($a->title ?: 'Untitled'));

        return response()->json($this->annPayload($a));
    }
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
       $keys = ['title', 'audience', 'message', 'category', 'pinned'];
        $old  = $a->only($keys);
        $a->update($this->annData($request->validate($this->annRules())));
        AdminLog::changed('announcements', 'Edited announcement: ' . ($a->title ?: 'Untitled'), $old, $a->only($keys));

        return response()->json($this->annPayload($a));
    }

    public function announcementDestroy(int $id): JsonResponse
    {
              $a = Announcement::findOrFail($id);
        AdminLog::record('announcements', 'Deleted announcement: ' . ($a->title ?: 'Untitled'));
        $a->delete();

        return response()->json(['ok' => true]);
    }
}