<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Support\Billing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    private function seenCacheKey(User $user): string
    {
        return 'notif_seen_tenant_' . $user->id;
    }

    private function markSeen(User $user, array $keys): void
    {
        $keys = array_filter($keys);
        if (! $keys) return;

        $seen = array_merge(Cache::get($this->seenCacheKey($user), []), $keys);
        Cache::forever($this->seenCacheKey($user), array_slice(array_values(array_unique($seen)), -50));
    }

    private function extras(User $user): Collection
    {
        $seen  = Cache::get($this->seenCacheKey($user), []);
        $extra = collect();

        // rent due within 5 days, or already overdue
        $next = collect(Billing::forUser($user)['invoices'])
            ->where('status', '!=', 'paid')
            ->where('awaiting', false)
            ->first();

        if ($next) {
            $days = (int) now()->startOfDay()->diffInDays($next['due']->copy()->startOfDay(), false);
            if ($days <= 5) {
                $key = 'rent:' . $next['due']->format('Y-m') . ':' . ($days < 0 ? 'over' : 'soon');
                $extra->push([
                    'id'         => 0,
                    'key'        => $key,
                    'title'      => $days < 0 ? 'Rent overdue' : 'Rent due soon',
                    'message'    => $days < 0 ? 'Your rent is ' . abs($days) . ' day(s) overdue.'
                        : ($days === 0 ? 'Your rent is due today.' : 'Your rent is due in ' . $days . ' day(s).'),
                    'is_read'    => in_array($key, $seen, true),
                    'created_at' => now()->toISOString(),
                ]);
            }
        }

        // announcements posted in the last 3 days
      $recent = Announcement::current()->where('created_at', '>=', now()->subDays(3))->get(['id', 'created_at']);
        if ($recent->count() > 0) {
            $key = 'ann:' . $recent->max('id');
            $extra->push([
                'id'         => 0,
                'key'        => $key,
                'title'      => 'New announcement',
                'message'    => $recent->count() . ' new announcement' . ($recent->count() > 1 ? 's' : '') . ' posted.',
                             'is_read'    => in_array($key, $seen, true),
                'created_at' => $recent->max('created_at')->toISOString(),
            ]);
        }

        return $extra;
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tenantNotifications()->where('created_at', '<', now()->subDays(4))->delete();    
        $stored = $user->tenantNotifications()
            ->latest()
            ->limit(20)
            ->get(['id', 'title', 'message', 'is_read', 'created_at']);

        $extra = $this->extras($user);

        return response()->json([
            'unread' => $stored->where('is_read', false)->count() + $extra->where('is_read', false)->count(),
            'items'  => $extra->concat($stored)->values(),
        ]);
    }

    public function read(Request $request, int $id): JsonResponse
    {
        if ($id === 0) {
            $this->markSeen($request->user(), [$request->input('key')]);
        } else {
            $request->user()->tenantNotifications()->whereKey($id)->update(['is_read' => true]);
        }

        return response()->json(['ok' => true]);
    }

    public function readAll(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->tenantNotifications()->update(['is_read' => true]);
        $this->markSeen($user, $this->extras($user)->pluck('key')->all());

        return response()->json(['ok' => true]);
    }
}