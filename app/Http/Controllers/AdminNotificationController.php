<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;

class AdminNotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $items = AdminNotification::latest()->limit(20)
            ->get(['id', 'title', 'message', 'is_read', 'created_at']);

        return response()->json([
            'unread' => AdminNotification::where('is_read', false)->count(),
            'items'  => $items,
        ]);
    }

    public function read(int $id): JsonResponse
    {
        AdminNotification::whereKey($id)->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    public function readAll(): JsonResponse
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}