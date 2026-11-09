<?php

namespace App\Http\Controllers;

use App\Models\RoommateMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoommateChatController extends Controller
{
    private function room(User $u): string
    {
        return $u->room_number ? 'Room ' . $u->room_number : 'No room yet';
    }

    /** You can only message someone who shares your room. */
    private function guard(User $me, User $other): void
    {
        abort_if((int) $me->id === (int) $other->id, 422, "You can't message yourself.");
        abort_unless($me->room_number && $me->room_number === $other->room_number, 403);
    }

    private function payload(RoommateMessage $m, int $meId): array
    {
        return [
            'id'   => $m->id,
            'mine' => (int) $m->sender_id === $meId,
            'body' => $m->body,
            'at'   => $m->created_at->toIso8601String(),
        ];
    }

    /** Message icon dropdown: my roommate conversations + unread total. */
    public function inbox(Request $request): JsonResponse
    {
        $me = $request->user();

        $threads = RoommateMessage::with(['sender', 'recipient'])
            ->where(fn ($q) => $q->where('sender_id', $me->id)->orWhere('recipient_id', $me->id))
            ->latest('id')->limit(300)->get()
            ->groupBy(fn ($m) => (int) $m->sender_id === $me->id ? $m->recipient_id : $m->sender_id)
            ->map(function ($group) use ($me) {
                $last  = $group->first();
                $other = (int) $last->sender_id === $me->id ? $last->recipient : $last->sender;

                // skip people who are no longer in my room
                if (! $me->room_number || $me->room_number !== $other->room_number) {
                    return null;
                }

                return [
                    'kind'       => 'roommate',
                    'item_id'    => $other->id,
                    'item_title' => 'Roommate',
                    'item_price' => $this->room($other),
                    'photo'      => null,
                    'with_id'    => $other->id,
                    'with_name'  => $other->display_name,
                    'with_room'  => $this->room($other),
                    'last'       => Str::limit($last->body, 60),
                    'last_mine'  => (int) $last->sender_id === $me->id,
                    'at'         => $last->created_at->toIso8601String(),
                    'unread'     => $group->where('recipient_id', $me->id)->whereNull('read_at')->count(),
                ];
            })->filter()->values();

        return response()->json(['unread' => $threads->sum('unread'), 'threads' => $threads]);
    }

    /** Messages between me and one roommate. */
    public function thread(Request $request): JsonResponse
    {
        $me = $request->user();

        $data  = $request->validate(['with' => ['required', 'integer', 'exists:users,id']]);
        $other = User::where('role', 'tenant')->findOrFail($data['with']);
        $this->guard($me, $other);

        if (! $request->boolean('peek')) {
            RoommateMessage::where('sender_id', $other->id)->where('recipient_id', $me->id)
                ->whereNull('read_at')->update(['read_at' => now()]);
        }

        $messages = RoommateMessage::where(function ($q) use ($me, $other) {
                $q->where(fn ($w) => $w->where('sender_id', $me->id)->where('recipient_id', $other->id))
                  ->orWhere(fn ($w) => $w->where('sender_id', $other->id)->where('recipient_id', $me->id));
            })
            ->latest('id')->limit(200)->get()->reverse()->values()
            ->map(fn ($m) => $this->payload($m, $me->id));

        return response()->json([
            'item'     => ['id' => $other->id, 'title' => 'Roommate', 'price' => $this->room($other), 'photo' => null],
            'other'    => ['id' => $other->id, 'name' => $other->display_name, 'room' => $this->room($other)],
            'messages' => $messages,
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $me = $request->user();

        $data = $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id'],
            'body'         => ['required', 'string', 'min:1', 'max:500'],
        ]);

        $to = User::where('role', 'tenant')->findOrFail($data['recipient_id']);
        $this->guard($me, $to);

        $m = RoommateMessage::create([
            'sender_id'    => $me->id,
            'recipient_id' => $to->id,
            'body'         => trim($data['body']),
        ]);

        return response()->json($this->payload($m, $me->id));
    }
}