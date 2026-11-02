<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class AuthOrGuest
{
   
    private const ALLOWED = ['tenant.home', 'tenant.dorm', 'tenant.rooms', 'tenant.location'];

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return $next($request);
        }

       
        if (! $request->session()->get('guest')) {
            return redirect()->guest(route('login'));
        }

      
        Auth::setUser((new User)->forceFill([
            'full_name' => 'Guest',
            'role'      => 'tenant',
            'is_guest'  => true,
        ]));

        if ($request->isMethod('GET') && in_array($request->route()?->getName(), self::ALLOWED, true)) {
            return $next($request);
        }

       
        if ($request->expectsJson() || ! $request->isMethod('GET')) {
            return response()->json(['message' => 'Please log in to use this feature.'], 401);
        }

        return response()->view('guest.locked');
    }
}