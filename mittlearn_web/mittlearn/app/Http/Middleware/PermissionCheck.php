<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionCheck
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->hasPermission($request)) {

            return $next($request);
        }
        return $this->denyAccess();
    }
    protected function hasPermission(Request $request): bool
    {
        $permissions = getRoutePermission();

        $routeName = $request->route()?->getName();
        return $routeName && isset($permissions[$routeName]) && $permissions[$routeName] === 1;
    }
    protected function denyAccess()
    {
        return redirect()->route('access-denied');
    }
}
