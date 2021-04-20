<?php
// +----------------------------------------------------------------------
// | think-utils
// +----------------------------------------------------------------------
// | Copyright (c) think-utils
// +----------------------------------------------------------------------
// | Date: 2021/04/20 16:43
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------

namespace mtmuo\think\middleware;


use Closure;
use mtmuo\think\facade\JWTAuth;
use think\facade\Cookie;
use think\Request;

class CheckRequestAuth
{
    protected $header = [
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Max-Age' => 86400,
        'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With',
    ];

    public function handle(Request $request, Closure $next, array $config = ['force' => true, 'cookie' => true])
    {
        if ($origin = $request->header('origin')) {
            $this->header['Access-Control-Allow-Origin'] = $origin;
        }
        $Authorization = "";
        if ($request->cookie("Authorization")) {
            $Authorization = $request->cookie("Authorization");
        } elseif ($request->header("Authorization")) {
            $Authorization = $request->header("Authorization");
        }
        // 开始验证登录状态
        try {
            $payload = JWTAuth::auth($Authorization);
            if ($payload->exp - 3600 < time()) {
                $Authorization = JWTAuth::refresh();
                $this->header['Authorization'] = $Authorization;
                if ($config['cookie']) {
                    Cookie::set("Authorization", $Authorization, [
                        'secure' => true,
                        'httponly' => true,
                    ]);
                }
            }
        } catch (\Exception $exception) {
            if ($config['force']) {
                throw $exception;
            }
        }
        return $next($request)->header($this->header);
    }
}
