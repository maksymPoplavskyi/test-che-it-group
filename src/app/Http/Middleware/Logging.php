<?php

namespace App\Http\Middleware;

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

        if ($request->getMethod() == "POST" || $request->getMethod() == "GET") {

            $method = Route::currentRouteAction();

            if ($method) {
                $method = explode('@', $method);
                $method = str_replace('App\Http\Controllers\\', '', $method[0]);
            } else {
                $method = null;
            }

            $ip = $request->getClientIp();
            $accessKey = config('config.key');

            $ch = curl_init("http://api.ipstack.com/$ip?access_key=$accessKey");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($ch);
            curl_close($ch);
            $api_result = json_decode($json, true);

            \App\Models\Logging::insert([
                'action' => $request->getRequestUri(),
                'method' => $method,
                'ip' => $ip,
                'city' => $api_result['city'] ? $api_result['city'] : null,
                'country' => $api_result['country_code'] ? $api_result['country_code'] : null,
                'type' => $request->getMethod(),
                'data' => json_encode($request->input())
            ]);
        }

        return $response;
    }
}
