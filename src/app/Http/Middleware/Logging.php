<?php

namespace App\Http\Middleware;

use App\Jobs\LoggingJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Logging
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $action = $request->getRequestUri();

        $method = Route::currentRouteAction();
        if ($method) {
            $method = explode('@', $method);
            $method = str_replace('App\Http\Controllers\\', '', $method[0]);
        } else {
            $method = null;
        }

        $ip = $request->getClientIp();

        $type = $request->getMethod();

        $data = json_encode($request->input());

        LoggingJob::dispatch($action, $method, $ip, $type, $data);

        return $response;
    }
}
