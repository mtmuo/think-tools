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
        $this->header['Access-Control-Allow-Origin'] = $request->header('origin', '*');
        $this->before($request);
        $response = $next($request);
        $this->after();
        // 设置代理域名
        return $response->header($this->header);
    }

    public function before($request)
    {
        $this->check($request);
    }

    public function after()
    {
        if (JWTAuth::isAuth() && (int)JWTAuth::getPayload('exp') < strtotime("+5 min")) {
            JWTAuth::refresh();
        }
    }

    /**
     * @param Request $request
     * @return Payload|null
     * @date: 2021-06-02 14:41
     * @author: zt
     */
    public function check(Request $request): ?Payload
    {
        $token = JWTAuth::token();
        try {
            return JWTAuth::auth($token);
        } catch (AuthException $exception) {
            return null;
        }
    }
}
