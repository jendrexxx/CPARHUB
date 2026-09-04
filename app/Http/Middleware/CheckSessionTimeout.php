<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        // 30 minutes inactivity
        $timeout = 30 * 60;

        if (Auth::check()) {

            $lastActivity = session('last_activity');

            if ($lastActivity && (time() - $lastActivity > $timeout)) {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()
                    ->route('auth.login')
                    ->with(
                        'status',
                        'You have been logged out due to inactivity.'
                    );
            }

            // Update activity timestamp
            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}
