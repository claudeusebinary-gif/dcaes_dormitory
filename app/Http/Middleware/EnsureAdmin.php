<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
   public function handle(Request $request, Closure $next): Response
{
    if (! $request->user()?->isAdmin()) {
        return redirect()->route('tenant.home');
    }

    $sec = \App\Support\DormStats::security();

    if ($sec['idle_logout']) {
        $last = $request->session()->get('last_seen');

        if ($last && now()->timestamp - $last > $sec['idle_minutes'] * 60) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Signed out after inactivity.'], 401);
            }

            return redirect()->route('login')->with('error', 'You were signed out after being inactive.');
        }
    }

    // the notification bell polls in the background, so it must not count as activity
    if (! $request->routeIs('admin.notifications.*')) {
        $request->session()->put('last_seen', now()->timestamp);
    }

    return $next($request);
}   
}
