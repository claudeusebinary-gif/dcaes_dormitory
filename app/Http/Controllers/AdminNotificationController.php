<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Approval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminNotificationController extends Controller
{
    private function seenCacheKey(Request $request): string
    {
        return 'notif_seen_admin_' . $request->user()->id;
    }

    private function markSeen(Request $request, array $keys): void
    {
        $keys = array_filter($keys);
        if (! $keys) return;

        $seen = array_merge(Cache::get($this->seenCacheKey($request), []), $keys);
        Cache::forever($this->seenCacheKey($request), array_slice(array_values(array_unique($seen)), -50));
    }

    // returns [pendingCount, key or null]
    private function pendingInfo(): array
    {
        $ids = Approval::where('status', 'pending')->pluck('id');

        return [$ids->count(), $ids->count() ? 'appr:' . $ids->max() : null];
    }

    public function index(Request $request): JsonResponse
    {
        AdminNotification::where('created_at', '<', now()->subDays(4))->delete();
        $items = AdminNotification::latest()->limit(20)
            ->get(['id', 'title', 'message', 'is_read', 'created_at']);

        [$pending, $key] = $this->pendingInfo();
        $seen = Cache::get($this->seenCacheKey($request), []);
        $isRead = $key !== null && in_array($key, $seen, true);

        $extra = $pending ? collect([[
            'id'         => 0,
            'key'        => $key,
            'title'      => 'Pending approvals',
            'message'    => $pending . ' request' . ($pending > 1 ? 's' : '') . ' waiting for your review.',
            'is_read'    => $isRead,
            'created_at' => Approval::where('status', 'pending')->latest()->value('created_at') ?? now()->toISOString(),
        ]]) : collect();

        return response()->json([
            'unread' => AdminNotification::where('is_read', false)->count() + ($pending && ! $isRead ? $pending : 0),
            'items'  => $extra->concat($items)->values(),
        ]);
    }

    public function read(Request $request, int $id): JsonResponse
    {
        if ($id === 0) {
            $this->markSeen($request, [$request->input('key')]);
        } else {
            AdminNotification::whereKey($id)->update(['is_read' => true]);
        }

        return response()->json(['ok' => true]);
    }

    public function readAll(Request $request): JsonResponse
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);
        $this->markSeen($request, [$this->pendingInfo()[1]]);

        return response()->json(['ok' => true]);
    }
}