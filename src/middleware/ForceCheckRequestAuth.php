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

class ForceCheckRequestAuth
{

    protected $header = [
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Max-Age' => 86400,
        'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With',
    ];


    public function handle(Request $request, Closure $next)
    {
        if ($origin = $request->header('origin')) {
            $this->header['Access-Control-Allow-Origin'] = $origin;
        }
        $this->check($request);
        return $next($request)->header($this->header);
    }

    public function check(Request $request): Payload
    {
        $Authorization = "";
        if ($request->cookie("Authorization")) {
            $Authorization = $request->cookie("Authorization");
        } elseif ($request->header("Authorization")) {
            $Authorization = $request->header("Authorization");
        }
        // 开始验证登录状态
        return JWTAuth::auth($Authorization);
    }
}
