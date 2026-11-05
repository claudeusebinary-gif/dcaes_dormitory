<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Support\AdminLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComplaintChatController extends Controller
{
    /** Admins can open any complaint; a tenant only their own. */
    private function guard(Request $request, Complaint $c): void
    {
        abort_unless($request->user()->isAdmin() || (int) $c->user_id === $request->user()->id, 403);
    }

    private function payload(ComplaintMessage $m, int $meId): array
    {
        return [
            'id'   => $m->id,
            'mine' => (int) $m->sender_id === $meId,
            'body' => $m->body,
            'at'   => $m->created_at->toIso8601String(),
        ];
    }

    /** Tenant's Messages dropdown: their complaint conversations. */
    public function inbox(Request $request): JsonResponse
    {
        $me = $request->user();

        $threads = Complaint::where('user_id', $me->id)->whereHas('messages')
            ->with(['messages' => fn ($q) => $q->latest('id')])->get()
            ->map(function ($c) use ($me) {
                $last = $c->messages->first();

                return [
                    'kind'       => 'complaint',
                    'item_id'    => $c->id,
                    'item_title' => 'Complaint: ' . $c->type_label,
                    'item_price' => Str::limit($c->message, 70),
                    'photo'      => null,
                    'with_id'    => 0,
                    'with_name'  => 'Dorm Admin',
                    'with_room'  => 'Management',
                    'last'       => Str::limit($last->body, 60),
                    'last_mine'  => (int) $last->sender_id === $me->id,
                    'at'         => $last->created_at->toIso8601String(),
                    'unread'     => $c->messages->where('sender_id', '!=', $me->id)->whereNull('read_at')->count(),
                ];
            })->values();

        return response()->json(['unread' => $threads->sum('unread'), 'threads' => $threads]);
    }
    /** Admin's Messages dropdown: every complaint conversation that has messages. */
    public function adminInbox(Request $request): JsonResponse
    {
        $me = $request->user();

        $threads = Complaint::with(['user', 'messages' => fn ($q) => $q->latest('id')])
            ->whereHas('messages')->get()
            ->map(function ($c) use ($me) {
                $last = $c->messages->first();

                return [
                    'kind'       => 'complaint',
                    'item_id'    => $c->id,
                    'item_title' => 'Complaint: ' . $c->type_label,
                    'item_price' => Str::limit($c->message, 70),
                    'photo'      => null,
                    'with_id'    => $c->user_id,
                    'with_name'  => $c->user->display_name,
                    'with_room'  => $c->room_number ? 'Room ' . $c->room_number : 'No room yet',
                    'last'       => Str::limit($last->body, 60),
                    'last_mine'  => (int) $last->sender_id === $me->id,
                    'at'         => $last->created_at->toIso8601String(),
                    // unread = messages the tenant sent that nobody has opened yet
                    'unread'     => $c->messages->where('sender_id', $c->user_id)->whereNull('read_at')->count(),
                ];
            })->sortByDesc('at')->values();

        return response()->json(['unread' => $threads->sum('unread'), 'threads' => $threads]);
    }
    public function thread(Request $request): JsonResponse
    {
        $me = $request->user();
        $data = $request->validate(['complaint_id' => ['required', 'integer', 'exists:complaints,id']]);

        $c = Complaint::with('user')->findOrFail($data['complaint_id']);
        $this->guard($request, $c);

        if (! $request->boolean('peek')) {
            ComplaintMessage::where('complaint_id', $c->id)->where('sender_id', '!=', $me->id)
                ->whereNull('read_at')->update(['read_at' => now()]);
        }

        $messages = ComplaintMessage::where('complaint_id', $c->id)->orderBy('id')->limit(200)->get()
            ->map(fn ($m) => $this->payload($m, $me->id));

        return response()->json([
            'item'  => ['id' => $c->id, 'title' => 'Complaint: ' . $c->type_label, 'price' => Str::limit($c->message, 70), 'photo' => null],
            'other' => $me->isAdmin()
                ? ['id' => $c->user->id, 'name' => $c->user->display_name, 'room' => $c->room_number ? 'Room ' . $c->room_number : 'No room yet']
                : ['id' => 0, 'name' => 'Dorm Admin', 'room' => 'Management'],
            'messages' => $messages,
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $me = $request->user();
        $data = $request->validate([
            'complaint_id' => ['required', 'integer', 'exists:complaints,id'],
            'body'         => ['required', 'string', 'min:1', 'max:500'],
        ]);

        $c = Complaint::findOrFail($data['complaint_id']);
        $this->guard($request, $c);

        $m = ComplaintMessage::create([
            'complaint_id' => $c->id,
            'sender_id'    => $me->id,
            'body'         => trim($data['body']),
        ]);

      if ($me->isAdmin()) {
            AdminLog::record('complaints', 'Replied to complaint #' . $c->id);
            Notification::create([
                'tenant_id' => $c->user_id,
                'title'     => 'Reply about your complaint',
                'message'   => Str::limit($m->body, 100),
            ]);
        } else {
            AdminNotification::fromTenant($me, 'Complaint reply', 'replied about a complaint: ' . Str::limit($m->body, 60));
        }

        return response()->json($this->payload($m, $me->id));
    }
}