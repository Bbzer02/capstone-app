<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminIdleTimeout
{
    /**
     * Terminate admin session after a short period of inactivity.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to admin guard
        if (Auth::guard('admin')->check()) {
            $now = now()->getTimestamp();
            $last = (int) ($request->session()->get('last_admin_activity_ts', 0));
            $timeoutSeconds = 2; // 2 seconds idle timeout

            if ($last > 0 && ($now - $last) > $timeoutSeconds) {
                // Invalidate admin session
                $admin = Auth::guard('admin')->user();
                if ($admin) {
                    $admin->forceFill([
                        'last_logout_at' => now(),
                        'is_online' => false,
                    ])->save();
                }
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['message' => 'Session expired'], 401);
                }
                return redirect()->route('admin.login')->with('error', 'Session expired due to inactivity.');
            }

            // Update last activity timestamp
            $request->session()->put('last_admin_activity_ts', $now);
        }

        return $next($request);
    }
}


