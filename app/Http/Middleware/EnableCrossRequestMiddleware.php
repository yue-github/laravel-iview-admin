<?php

namespace App\Http\Middleware;
use Closure;
class EnableCrossRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        $allow_origin = [
            'http://localhost:8000',
            'http://localhost:3000',
            'http://localhost:8080',
            'http://localhost:8866',
            'http://localhost:8081',
            'http://127.0.0.1:10086',
            'http://find333.com',
            'https://www.coral3.com',
            'http://192.168.160.1:8080',
            'http://127.0.0.1:27554',
            'https://www.clinpath.cn'

        ];
        if (in_array($origin, $allow_origin)) {
            $response->header('Access-Control-Allow-Origin', $origin);
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, X-Token, Accept, Authorization, X-XSRF-TOKEN');
            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            $response->header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }
}