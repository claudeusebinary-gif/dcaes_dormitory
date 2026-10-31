<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomRequestController extends Controller
{
    public function maintenance(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->room_number) {
            return response()->json(['message' => 'You need an assigned room before sending a request.'], 422);
        }

        $data = $request->validate([
            'category' => ['required', Rule::in(['plumbing', 'electrical', 'furniture', 'ventilation', 'pest', 'other'])],
            'urgency'  => ['required', Rule::in(['low', 'medium', 'high'])],
            'details'  => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'category.required' => 'Please select a category.',
            'urgency.required'  => 'Please select an urgency.',
            'details.required'  => 'Please describe the issue.',
        ]);

        RoomRequest::create($data + [
            'user_id'     => $user->id,
            'type'        => 'maintenance',
            'room_number' => $user->room_number,
        ]);

        return response()->json(['ok' => true, 'message' => 'Request sent. Dorm maintenance will follow up.']);
    }

    public function transfer(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->room_number) {
            return response()->json(['message' => 'You need an assigned room before requesting a transfer.'], 422);
        }

        $data = $request->validate([
            'room'    => ['required', 'string', 'exists:rooms,room_number'],
            'bed'     => ['required', 'regex:/^\d+\|(Lower|Upper)$/'],
            'reason'  => ['required', Rule::in(['roommate_conflict', 'noise', 'entrance', 'medical', 'other'])],
            'details' => ['nullable', 'string', 'max:1000'],
        ], [
            'room.required'   => 'Please select a room.',
            'bed.required'    => 'Please select a bed and deck.',
            'reason.required' => 'Please select a reason.',
        ]);

        [$bedNo, $deck] = explode('|', $data['bed']);
        $room = Room::where('room_number', $data['room'])->first();

        if ($room->under_maintenance || (int) $bedNo > $room->total_beds) {
            return response()->json(['message' => 'That bed is not available.'], 422);
        }

        if ($data['room'] === $user->room_number
            && (string) $bedNo === (string) $user->bed_number
            && strtolower($deck) === strtolower((string) $user->bed_deck)) {
            return response()->json(['message' => 'That is already your bed.'], 422);
        }

        $taken = User::where('role', 'tenant')
            ->where('room_number', $data['room'])
            ->where('bed_number', $bedNo)
            ->where('bed_deck', $deck)
            ->exists();

        if ($taken) {
            return response()->json(['message' => 'Sorry, that bed was just taken. Please pick another.'], 409);
        }

        $hasPending = RoomRequest::where('user_id', $user->id)
            ->where('type', 'transfer')->where('status', 'pending')->exists();

        if ($hasPending) {
            return response()->json(['message' => 'You already have a pending transfer request.'], 409);
        }

        RoomRequest::create([
            'user_id'     => $user->id,
            'type'        => 'transfer',
            'room_number' => $user->room_number,
            'target_room' => $data['room'],
            'target_bed'  => (int) $bedNo,
            'target_deck' => $deck,
            'reason'      => $data['reason'],
            'details'     => $data['details'] ?? null,
        ]);

        return response()->json(['ok' => true, 'message' => 'Transfer request sent. Management will review it.']);
    }
        public function moveOut(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->room_number) {
            return response()->json(['message' => 'You do not have a room to leave.'], 422);
        }

        $data = $request->validate([
            'move_out_date' => ['required', 'date', 'after_or_equal:' . now()->addDays(30)->toDateString()],
            'reason'        => ['required', Rule::in(['graduating', 'transfer', 'moving_off', 'financial', 'family', 'other'])],
            'details'       => ['nullable', 'string', 'max:1000'],
        ], [
            'move_out_date.required'        => 'Please choose your preferred move-out date.',
            'move_out_date.after_or_equal'  => 'Requests must be submitted at least 30 days before your move-out date.',
            'reason.required'               => 'Please select a reason.',
        ]);

        $pending = RoomRequest::where('user_id', $user->id)
            ->where('type', 'move_out')->where('status', 'pending')->exists();

        if ($pending) {
            return response()->json(['message' => 'You already have a pending move-out request.'], 409);
        }

        RoomRequest::create([
            'user_id'       => $user->id,
            'type'          => 'move_out',
            'room_number'   => $user->room_number,
            'reason'        => $data['reason'],
            'details'       => $data['details'] ?? null,
            'move_out_date' => $data['move_out_date'],
        ]);

        return response()->json(['ok' => true, 'message' => 'Move-out request sent. Dorm management will follow up with clearance steps.']);
    }
}