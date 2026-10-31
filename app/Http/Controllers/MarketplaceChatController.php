<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceItem;
use App\Models\MarketplaceMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketplaceChatController extends Controller
{
    private function photo(MarketplaceItem $item): ?string
    {
        $first = ($item->photos ?? [])[0] ?? null;
        return $first ? asset('storage/' . $first) : null;
    }

    private function price(MarketplaceItem $item): string
    {
        return (int) $item->price === 0 ? 'Free' : '₱' . number_format($item->price);
    }

    /** A chat is always between the item's seller and one other tenant. */
    private function guard(MarketplaceItem $item, int $meId, int $otherId): void
    {
        abort_if($meId === $otherId, 422, "You can't message yourself.");
        abort_unless((int) $item->seller_id === $meId || (int) $item->seller_id === $otherId, 403);
    }

    private function payload(MarketplaceMessage $m, int $meId): array
    {
        return [
            'id'   => $m->id,
            'mine' => (int) $m->sender_id === $meId,
            'body' => $m->body,
            'at'   => $m->created_at->toIso8601String(),
        ];
    }

    private function room(User $u): string
    {
        return $u->room_number ? 'Room ' . $u->room_number : 'No room yet';
    }

    /** Message icon dropdown: all my conversations + unread total. */
    public function inbox(Request $request): JsonResponse
    {
        $me = $request->user();

        $threads = MarketplaceMessage::with(['item', 'sender', 'recipient'])
            ->where(fn ($q) => $q->where('sender_id', $me->id)->orWhere('recipient_id', $me->id))
            ->latest('id')->limit(300)->get()
            ->groupBy(fn ($m) => $m->item_id . '-' . ((int) $m->sender_id === $me->id ? $m->recipient_id : $m->sender_id))
            ->map(function ($group) use ($me) {
                $last  = $group->first();
                $other = (int) $last->sender_id === $me->id ? $last->recipient : $last->sender;

                return [
                    'item_id'    => $last->item_id,
                    'item_title' => $last->item->title,
                    'item_price' => $this->price($last->item),
                    'photo'      => $this->photo($last->item),
                    'with_id'    => $other->id,
                    'with_name'  => $other->display_name,
                    'with_room'  => $this->room($other),
                    'last'       => Str::limit($last->body, 60),
                    'last_mine'  => (int) $last->sender_id === $me->id,
                    'at'         => $last->created_at->toIso8601String(),
                    'unread'     => $group->where('recipient_id', $me->id)->whereNull('read_at')->count(),
                ];
            })->values();

        return response()->json([
            'unread'  => MarketplaceMessage::where('recipient_id', $me->id)->whereNull('read_at')->count(),
            'threads' => $threads,
        ]);
    }

    /** Messages between me and one tenant about one item. */
    public function thread(Request $request): JsonResponse
    {
        $me = $request->user();

        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:marketplace_items,id'],
            'with'    => ['required', 'integer', 'exists:users,id'],
        ]);

        $item  = MarketplaceItem::findOrFail($data['item_id']);
        $other = User::findOrFail($data['with']);
        $this->guard($item, $me->id, $other->id);

        if (! $request->boolean('peek')) {
            MarketplaceMessage::where('item_id', $item->id)
                ->where('sender_id', $other->id)->where('recipient_id', $me->id)
                ->whereNull('read_at')->update(['read_at' => now()]);
        }

        $messages = MarketplaceMessage::where('item_id', $item->id)
            ->where(function ($q) use ($me, $other) {
                $q->where(fn ($w) => $w->where('sender_id', $me->id)->where('recipient_id', $other->id))
                  ->orWhere(fn ($w) => $w->where('sender_id', $other->id)->where('recipient_id', $me->id));
            })
            ->latest('id')->limit(200)->get()->reverse()->values()
            ->map(fn ($m) => $this->payload($m, $me->id));

        return response()->json([
            'item'     => ['id' => $item->id, 'title' => $item->title, 'price' => $this->price($item), 'photo' => $this->photo($item)],
            'other'    => ['id' => $other->id, 'name' => $other->display_name, 'room' => $this->room($other)],
            'messages' => $messages,
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $me = $request->user();

        $data = $request->validate([
            'item_id'      => ['required', 'integer', 'exists:marketplace_items,id'],
            'recipient_id' => ['required', 'integer', 'exists:users,id'],
            'body'         => ['required', 'string', 'min:1', 'max:500'],
        ]);

        $item = MarketplaceItem::findOrFail($data['item_id']);
        $to   = User::where('role', 'tenant')->findOrFail($data['recipient_id']);
        $this->guard($item, $me->id, $to->id);

        $m = MarketplaceMessage::create([
            'item_id'      => $item->id,
            'sender_id'    => $me->id,
            'recipient_id' => $to->id,
            'body'         => trim($data['body']),
        ]);

        return response()->json($this->payload($m, $me->id));
    }
}