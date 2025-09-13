<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('dashboard');
            }
        }

        // If no guard is specified and user is authenticated on any guard, redirect appropriately
        if (empty($guards) || in_array(null, $guards)) {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::guard('web')->check()) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
