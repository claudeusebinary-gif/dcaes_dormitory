<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** JSON API behind the notification bell (was includes/notifications.php). */
class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = $request->user()
            ->tenantNotifications()
            ->latest()
            ->limit(20)
            ->get(['id', 'title', 'message', 'is_read', 'created_at']);

        return response()->json([
            'unread' => $items->where('is_read', false)->count(),
            'items' => $items,
        ]);
    }

    public function read(Request $request, int $id): JsonResponse
    {
        $request->user()->tenantNotifications()->whereKey($id)->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    public function readAll(Request $request): JsonResponse
    {
        $request->user()->tenantNotifications()->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}
