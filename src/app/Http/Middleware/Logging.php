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
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $ip = '188.163.20.43'; // my ip

        $type = $_SERVER['REQUEST_METHOD'];

        $action = $_SERVER['REQUEST_URI'];

        $method = Route::currentRouteAction();
        if ($method) {
            $method = explode('@', $method);
            $method = str_replace('App\Http\Controllers\\', '', $method[0]);
        } else {
            $method = null;
        }

        $accessKey = config('config.key');

        $ch = curl_init("http://api.ipstack.com/$ip?access_key=$accessKey");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        curl_close($ch);
        $api_result = json_decode($json, true);

        $county = $api_result['country_code'];
        $city = $api_result['city'];

        \App\Models\Logging::insert([
            'action' => $action,
            'method' => $method,
            'ip' => $ip,
            'city' => $city,
            'country' => $county,
            'type' => $type,
            'data' => implode('|', $request->input())
        ]);

        return $next($request);
    }
}
