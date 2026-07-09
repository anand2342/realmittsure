<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $routeAction = $request->route()->action;
        $actionMethod = $request->route()->methods[0];
        $params = null;
        if ($actionMethod == 'POST' || $actionMethod == 'PUT') {
            $params = json_encode($request->all());
        }
        $where = [
            "user_id" => Auth::user()->id,
            "action_as" => $routeAction['as'],
            "method" => "GET",
            "log_date" => date('Y-m-d'),
        ];
        $logData = [
            "user_id" => Auth::user()->id,
            "uri" => $request->route()->uri,
            "action_as" => $routeAction['as'],
            "controller" => $routeAction['controller'],
            "method" => $actionMethod,
            "json_data" => $params,
            "log_date" => date('Y-m-d'),
        ];
        if (UserLog::where($where)->first()) {
            UserLog::where($where)->increment('counts', 1);
        } else {
            UserLog::updateOrCreate($where, $logData);
        }
        return $next($request);
    }

}
