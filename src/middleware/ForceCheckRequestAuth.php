<?php
// +----------------------------------------------------------------------
// | think-tools
// +----------------------------------------------------------------------
// | Copyright (c) think-tools
// +----------------------------------------------------------------------
// | Date: 2021/04/21 09:39
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------

namespace mtmuo\think\middleware;


use Closure;
use mtmuo\think\facade\JWTAuth;
use mtmuo\think\jwt\Payload;
use think\Request;
use think\Response;

class ForceCheckRequestAuth
{
    protected $header = [
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Max-Age' => 86400,
        'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With',
    ];

    public function __construct(Request $request)
    {
        if ($origin = $request->header('origin')) {
            $this->header['Access-Control-Allow-Origin'] = $origin;
        }
    }

    public function handle(Request $request, Closure $next)
    {
        $this->check($request);
        $response = $next($request);
        $this->refresh();
        return $response->header($this->header);
    }

    public function refresh()
    {
        if (time() - (int)JWTAuth::getPayload('exp') <= 60) {
            JWTAuth::refresh();
        }
    }

    public function check(Request $request): Payload
    {
        $Authorization = "";
        if ($request->header("Authorization")) {
            $Authorization = $request->cookie("Authorization");
        } elseif ($request->cookie("Authorization")) {
            $Authorization = $request->header("Authorization");
        }
        return JWTAuth::auth($Authorization);
    }
}
