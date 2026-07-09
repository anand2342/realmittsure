<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class BreadcrumbMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $routeName = Route::currentRouteName();
        $breadcrumbs = $this->getBreadcrumbs($routeName, $request);
        // Share breadcrumbs with all views
        view()->share('breadcrumbs', $breadcrumbs);

        return $next($request);
    }

    protected function getBreadcrumbs($routeName, Request $request)
    {
        $baseBreadcrumbs = [
            'dashboard' => [
                ['title' => 'Home', 'url' => route('dashboard')],
                ['title' => 'Dashboard', 'url' => ''],
            ],
            'permissions.index' => [
                ['title' => 'Home', 'url' => route('dashboard')],
                ['title' => 'Permissions', 'url' => route('permissions.index')],
            ],
            'roles.index' => [
                ['title' => 'Home', 'url' => route('dashboard')],
                ['title' => 'Roles', 'url' => route('roles.index')],
            ],
            'profile' => [
                ['title' => 'Home', 'url' => route('dashboard')],
                ['title' => 'Profile', 'url' => route('profile')],
            ],
            //Email index Breadcrumbs
            'email-template.index' => [
                ['title' => 'Home', 'url' => route('dashboard')],
                ['title' => 'Email Templates', 'url' => route('email-template.index')],
            ],
            'email-template.add' => [
                ['title' => 'Home', 'url' => route('dashboard')],
                ['title' => 'Email Templates', 'url' => route('email-template.index')],
                ['title' => 'Add New', 'url' => route('email-template.add')],
            ],
            'email-template.edit' => function () use ($request) {
                $id = $request->route('id');

                return [
                    ['title' => 'Home', 'url' => route('dashboard')],
                    ['title' => 'Email Templates', 'url' => route('email-template.index')],
                    ['title' => 'Edit', 'url' => route('email-template.edit', ['id' => $id])],
                ];
            },
            'home.page-content' => [
                ['title' => 'Home', 'url' => route('dashboard')],
                ['title' => 'Website Pages', 'url' => route('home.page-content')],
            ],
           
           

        ];

        return isset($baseBreadcrumbs[$routeName])
            ? (is_callable($baseBreadcrumbs[$routeName]) ? $baseBreadcrumbs[$routeName]() : $baseBreadcrumbs[$routeName])
            : [];
    }
}
