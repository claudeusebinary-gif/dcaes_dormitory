<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\DirectMessage;
use App\Models\Notification;
use App\Support\AdminLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DirectChatController extends Controller
{
    private function room(User $u): string
    {
        return $u->room_number ? 'Room ' . $u->room_number : 'No room yet';
    }

    /** Admins can open any tenant's thread; a tenant only their own. */
    private function tenantFor(Request $request, int $tenantId): User
    {
        $me = $request->user();
        abort_unless($me->isAdmin() || (int) $me->id === $tenantId, 403);

        return User::where('role', 'tenant')->findOrFail($tenantId);
    }

    private function payload(DirectMessage $m, int $meId): array
    {
        return [
            'id'   => $m->id,
            'mine' => (int) $m->sender_id === $meId,
            'body' => $m->body,
            'at'   => $m->created_at->toIso8601String(),
        ];
    }

    public function thread(Request $request): JsonResponse
    {
        $me = $request->user();
        $data = $request->validate(['tenant_id' => ['required', 'integer', 'exists:users,id']]);
        $tenant = $this->tenantFor($request, (int) $data['tenant_id']);

        if (! $request->boolean('peek')) {
            DirectMessage::where('tenant_id', $tenant->id)->where('sender_id', '!=', $me->id)
                ->whereNull('read_at')->update(['read_at' => now()]);
        }

        $messages = DirectMessage::where('tenant_id', $tenant->id)->orderBy('id')->limit(200)->get()
            ->map(fn ($m) => $this->payload($m, $me->id));

        return response()->json([
            'item'  => ['id' => $tenant->id, 'title' => 'Direct message', 'price' => $me->isAdmin() ? $this->room($tenant) : 'Dorm management', 'photo' => null],
            'other' => $me->isAdmin()
                ? ['id' => $tenant->id, 'name' => $tenant->display_name, 'room' => $this->room($tenant)]
                : ['id' => 0, 'name' => 'Dorm Admin', 'room' => 'Management'],
            'messages' => $messages,
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $me = $request->user();
        $data = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:users,id'],
            'body'      => ['required', 'string', 'min:1', 'max:500'],
        ]);
        $tenant = $this->tenantFor($request, (int) $data['tenant_id']);

        $m = DirectMessage::create([
            'tenant_id' => $tenant->id,
            'sender_id' => $me->id,
            'body'      => trim($data['body']),
        ]);
if ($me->isAdmin()) {
            AdminLog::record('messages', 'Sent a direct message to ' . $tenant->display_name);
            Notification::create([
                'tenant_id' => $tenant->id,
                'title'     => 'New message from the admin',
                'message'   => Str::limit($m->body, 100),
            ]);
        } else {
            AdminNotification::fromTenant($me, 'New message', 'sent you a message: ' . Str::limit($m->body, 60));
        }

        return response()->json($this->payload($m, $me->id));
    }

    /** Tenant's Messages dropdown. */
    public function inbox(Request $request): JsonResponse
    {
        $me = $request->user();
        $last = DirectMessage::where('tenant_id', $me->id)->latest('id')->first();

        if (! $last) {
            return response()->json(['unread' => 0, 'threads' => []]);
        }

        $unread = DirectMessage::where('tenant_id', $me->id)->where('sender_id', '!=', $me->id)->whereNull('read_at')->count();

        return response()->json(['unread' => $unread, 'threads' => [[
            'kind' => 'direct', 'item_id' => $me->id, 'item_title' => 'Direct message', 'item_price' => 'Dorm management',
            'photo' => null, 'with_id' => 0, 'with_name' => 'Dorm Admin', 'with_room' => 'Management',
            'last' => Str::limit($last->body, 60), 'last_mine' => (int) $last->sender_id === $me->id,
            'at' => $last->created_at->toIso8601String(), 'unread' => $unread,
        ]]]);
    }

    /** Admin's Messages dropdown: one thread per tenant. */
    public function adminInbox(Request $request): JsonResponse
    {
        $me = $request->user();

        $threads = DirectMessage::with('tenant')->latest('id')->limit(500)->get()
            ->groupBy('tenant_id')
            ->map(function ($g) use ($me) {
                $last = $g->first();
                $t = $last->tenant;

                return [
                    'kind' => 'direct', 'item_id' => $t->id, 'item_title' => 'Direct message', 'item_price' => $this->room($t),
                    'photo' => null, 'with_id' => $t->id, 'with_name' => $t->display_name, 'with_room' => $this->room($t),
                    'last' => Str::limit($last->body, 60), 'last_mine' => (int) $last->sender_id === $me->id,
                    'at' => $last->created_at->toIso8601String(),
                    'unread' => $g->where('sender_id', $t->id)->whereNull('read_at')->count(),
                ];
            })->values();

        return response()->json(['unread' => $threads->sum('unread'), 'threads' => $threads]);
    }
}