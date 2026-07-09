<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     $response = $next($request);

    //     return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
    //                     ->header('Pragma', 'no-cache')
    //                     ->header('Expires', '0');

    // }
    
    //change by ashmit for word download
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only modify headers **if response supports them**
        if (method_exists($response, 'header')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
