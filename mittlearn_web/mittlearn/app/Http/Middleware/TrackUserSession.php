<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\UserLoginLog;
use Symfony\Component\HttpFoundation\Response;

class TrackUserSession
{
    public function handle(Request $request, Closure $next)
    {
        // Let request continue first
        $response = $next($request);

        // If user is NOT logged in but login_log_id is still in session
        if (!Auth::check() && Session::has('login_log_id')) {
            $logId = Session::get('login_log_id');

            // Update logout_at timestamp
            UserLoginLog::where('id', $logId)->update([
                'logout_at' => now()
            ]);

            // Clean up session
            Session::forget('login_log_id');
        }

        return $response;
    }
}
