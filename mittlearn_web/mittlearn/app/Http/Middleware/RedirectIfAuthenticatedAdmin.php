<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check() && Auth::user()->is_admin) {
            // Redirect to the admin dashboard if authenticated and user is an admin
            return redirect()->route('dashboard');
        }

        // Continue the request if not authenticated
        return $next($request);
    }
}
