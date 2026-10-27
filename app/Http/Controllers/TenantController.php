<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Support\DormStats;
use Illuminate\View\View;
use App\Models\AdminMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Support\Billing;
use App\Models\Payment;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function home(): View
    {
        return view('tenant.home', [
            'stats' => DormStats::get(),
             'featured' => DormStats::featuredRooms(),
        ]);
    }

    public function profile(): View { return view('tenant.profile'); }

  public function dorm(): View
{
    return view('tenant.dorm', [
        'overview' => DormStats::overview(),
        'rooms'    => DormStats::rooms(),
    ]);
}

   public function rooms(): View
{
    return view('tenant.rooms', [
        'summary' => DormStats::listing(),
        'rooms' => Room::orderBy('room_number')->get(),
         'fees'    => DormStats::fees(), 
        'tenants' => User::where('role', 'tenant')
            ->whereNotNull('room_number')
            ->get()
            ->groupBy('room_number'),
            
    ]);
}
public function book(Request $request): JsonResponse
{
    $user = $request->user();

    // Already has a room
    if ($user->room_number) {
        return response()->json([
            'ok' => false,
            'code' => 'has_room',
            'message' => 'You already have a room.',
            'room' => $user->room_number,
        ], 409);
    }

    $data = $request->validate([
        'room'              => ['required', 'string', 'exists:rooms,room_number'],
        'bed'               => ['required', 'integer', 'min:1'],
        'deck'              => ['required', 'in:Upper,Lower'],
        'first_name'        => ['required', 'string', 'max:50'],
        'last_name'         => ['required', 'string', 'max:50'],
        'date_of_birth'     => ['required', 'date', 'before:today'],
        'sex'               => ['required', 'in:male,female'],
        'mobile'            => ['required', 'string', 'max:20'],
        'home_address'      => ['required', 'string', 'max:500'],
        'ec_full_name'      => ['required', 'string', 'max:100'],
        'ec_relationship'   => ['required', 'string', 'max:50'],
        'ec_contact_number' => ['required', 'string', 'max:20'],
        'ec_address'        => ['required', 'string', 'max:500'],
        'move_in_date'      => ['required', 'date', 'after_or_equal:today'],
    ]);

    return DB::transaction(function () use ($user, $data) {
        $room = Room::where('room_number', $data['room'])->lockForUpdate()->first();

        if ($room->under_maintenance) {
            return response()->json(['ok' => false, 'message' => 'This room is under maintenance.'], 422);
        }
        if ((int) $data['bed'] > $room->total_beds) {
            return response()->json(['ok' => false, 'message' => 'That bed does not exist.'], 422);
        }

        
        $onBed = User::where('role', 'tenant')
            ->where('room_number', $room->room_number)
            ->where('bed_number', $data['bed'])
            ->lockForUpdate()->get();

        $deckTaken = $onBed->contains(fn ($t) => strtolower((string) $t->bed_deck) === strtolower($data['deck']));

        if ($deckTaken || $onBed->count() >= 2) {
            return response()->json(['ok' => false, 'message' => 'Sorry, that bed was just taken. Please pick another.'], 409);
        }

        $user->update([
            'full_name'      => trim($data['first_name'] . ' ' . $data['last_name']),
            'room_number'    => $room->room_number,
            'bed_number'     => $data['bed'],
            'bed_deck'       => $data['deck'],
            'phone_number'   => $data['mobile'],
            'sex'            => $data['sex'],
            'birthday'       => $data['date_of_birth'],
            'address'        => $data['home_address'],
            'move_in_date'   => $data['move_in_date'],
            'monthly_rent'   => $room->price_per_bed,
            'payment_status' => 'up_to_date',
        ]);

             $user->emergencyContact()->updateOrCreate([], [
            'name'         => $data['ec_full_name'],
            'relationship' => $data['ec_relationship'],
            'phone'        => $data['ec_contact_number'],
            'address'      => $data['ec_address'],
        ]);

        // booking pays the first month
        $first = collect(Billing::forUser($user)['invoices'])
            ->firstWhere('period', Carbon::parse($data['move_in_date'])->format('Y-m'));

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

        return response()->json([
            'ok'           => true,
            'ref'          => 'DAC-' . now()->year . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'confirmed_at' => now()->format('M j, Y, g:i A'),
            'tenant'       => $user->full_name,
            'room'         => $room->room_number,
            'bed'          => $data['bed'],
            'deck'         => $data['deck'],
            'move_in'      => Carbon::parse($data['move_in_date'])->format('F j, Y'),
        ]);
    });
}
public function sendMessage(Request $request): JsonResponse
{
    $data = $request->validate([
        'message' => ['required', 'string', 'min:2', 'max:1000'],
        'room'    => ['nullable', 'string', 'max:10'],
    ]);

    AdminMessage::create([
        'tenant_id'   => $request->user()->id,
        'room_number' => $data['room'] ?? $request->user()->room_number,
        'message'     => trim($data['message']),
    ]);

    return response()->json(['ok' => true]);
}
      public function payments(Request $request): View
    {
        return view('tenant.payments', ['billing' => Billing::forUser($request->user())]);
    }

    public function pay(Request $request): JsonResponse
    {
        $data = $request->validate([
            'periods'   => ['required', 'array', 'min:1'],
            'periods.*' => ['required', 'regex:/^\d{4}-\d{2}$/'],
            'method'    => ['required', 'in:GCash'],
        ]);

           $user    = $request->user();

        // only the next two unpaid invoices can be paid
        $unpaid  = collect(Billing::forUser($user)['invoices'])
            ->where('status', '!=', 'paid')
            ->values()
            ->take(2)
            ->keyBy('period');
        $total   = 0;
        DB::transaction(function () use ($data, $user, $unpaid, &$total) {
            foreach (array_unique($data['periods']) as $period) {
                $inv = $unpaid->get($period);
                if (! $inv) continue;                      

                Payment::firstOrCreate(
                    ['user_id' => $user->id, 'period' => $period],
                    [
                        'amount'    => $inv['amount'],
                        'method'    => $data['method'],
                        'reference' => $inv['ref'],
                        'paid_at'   => now(),
                    ]
                );
                $total += $inv['amount'];
            }
        });

        if ($total <= 0) {
            return response()->json(['ok' => false, 'message' => 'Nothing to pay.'], 422);
        }

        // no overdue left -> tenant is up to date
        $after = Billing::forUser($user);
        if (collect($after['invoices'])->where('status', 'overdue')->isEmpty()) {
            $user->update(['payment_status' => 'up_to_date']);
        }

        return response()->json(['ok' => true, 'total' => $total]);
    }

    public function announcement(): View { return view('tenant.announcement'); }
    public function location(): View { return view('tenant.location'); }
    public function settings(Request $request): View
{
    
    $taken = User::where('role', 'tenant')
        ->whereNotNull('room_number')
        ->get(['room_number', 'bed_number', 'bed_deck'])
        ->map(fn ($u) => $u->room_number . '|' . $u->bed_number . '|' . strtolower((string) $u->bed_deck))
        ->flip()
        ->all();

    $transferOptions = [];

    foreach (Room::where('under_maintenance', false)->orderBy('room_number')->get() as $room) {
        $beds = [];

        for ($b = 1; $b <= $room->total_beds; $b++) {
            foreach (['Lower', 'Upper'] as $deck) {
                if (! isset($taken[$room->room_number . '|' . $b . '|' . strtolower($deck)])) {
                    $beds[] = [
                        'value' => $b . '|' . $deck,
                        'label' => 'Bed ' . $b . ' · ' . $deck,
                    ];
                }
            }
        }

        if ($beds) {   
            $transferOptions[$room->room_number] = [
                'free' => count($beds),
                'beds' => $beds,
            ];
        }
    }

    return view('tenant.settings', [
        'billing'         => Billing::forUser($request->user()),
        'transferOptions' => $transferOptions,
        'myRoom' => Room::where('room_number', $request->user()->room_number)->first(),
    ]);
}
    public function updateGeneral(Request $request)
{
    $user = $request->user();

    $data = $request->validate([
        'first_name'      => ['required', 'string', 'max:50'],
        'last_name'       => ['required', 'string', 'max:50'],
        'civil_status'    => ['nullable', 'string', 'max:20'],
        'email'           => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        'phone_number'    => ['nullable', 'string', 'max:20'],
        'sex'             => ['nullable', 'in:male,female'],
        'birthday'        => ['nullable', 'date', 'before:today'],
        'occupation'      => ['nullable', 'string', 'max:150'],
        'ec_name'         => ['required', 'string', 'max:100'],
        'ec_relationship' => ['required', 'string', 'max:50'],
        'ec_phone'        => ['required', 'string', 'max:20'],
    ]);

    $user->update([
        'full_name'    => trim($data['first_name'] . ' ' . $data['last_name']),
        'email'        => $data['email'],
        'phone_number' => $data['phone_number'] ?? null,
        'sex'          => $data['sex'] ?? null,
        'birthday'     => $data['birthday'] ?? null,
        'civil_status' => $data['civil_status'] ?? null,
        'occupation'   => $data['occupation'] ?? null,
    ]);

    $user->emergencyContact()->updateOrCreate(
        ['user_id' => $user->id],
        [
            'name'         => $data['ec_name'],
            'relationship' => $data['ec_relationship'],
            'phone'        => $data['ec_phone'],
        ]
    );

    return redirect(route('tenant.settings') . '#general')
        ->with('status', 'Your changes have been saved.');
}
public function updateBillingEmail(Request $request): JsonResponse
{
    $data = $request->validate([
        'billing_email' => ['required', 'email', 'max:255'],
    ]);

    $request->user()->update(['billing_email' => $data['billing_email']]);

    return response()->json([
        'ok'      => true,
        'message' => 'Billing email updated.',
        'email'   => $data['billing_email'],
    ]);
}
    public function marketplace(): View { return view('tenant.marketplace'); }
    public function calendar(): View { return view('tenant.calendar'); }
}