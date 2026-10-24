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
    public function announcement(): View { return view('tenant.announcement'); }
    public function location(): View { return view('tenant.location'); }
      public function settings(Request $request): View
    {
        return view('tenant.settings', ['billing' => Billing::forUser($request->user())]);
    }
    public function marketplace(): View { return view('tenant.marketplace'); }
    public function calendar(): View { return view('tenant.calendar'); }
}