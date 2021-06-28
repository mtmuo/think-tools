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
use mtmuo\think\exception\AuthException;
use mtmuo\think\facade\JWTAuth;
use mtmuo\think\jwt\Payload;
use think\Request;

class CheckAuthMiddleware
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
        $this->check($request);
        // 设置代理域名
        $this->header['Access-Control-Allow-Origin'] = $request->header('origin', '*');
        return $next($request)->header($this->header);
    }

    /**
     * @param \think\Request $request
     * @return \mtmuo\think\jwt\Payload|null
     * @date: 2021-06-02 14:41
     * @author: zt
     */
    public function check(Request $request): ?Payload
    {
        $Authorization = "";
        if ($request->cookie("Authorization")) {
            $Authorization = $request->cookie("Authorization");
        } elseif ($request->header("Authorization")) {
            $Authorization = $request->header("Authorization");
        }
        try {
            return JWTAuth::auth($Authorization);
        } catch (AuthException $exception) {
            return null;
        }
    }
}
