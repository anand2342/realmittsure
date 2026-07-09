<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = 'web')
    {
        // Check if user is authenticated with the specified guard
        if (Auth::guard($guard)->check() && Auth::guard($guard)->user()->is_admin) {
            return $next($request);
        }

        return redirect('/login');
        
        //here 'files/*' have to be replaced by your download url.
        if ($request->is('files/*')) {
            // Skip adding the CORS headers for URLs starting with '/uploads/'
            return $next($request);
        }

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*');
    }

}
