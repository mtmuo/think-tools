<?php
// +----------------------------------------------------------------------
// | think-utils
// +----------------------------------------------------------------------
// | Copyright (c) think-utils
// +----------------------------------------------------------------------
// | Date: 2021/06/02 11:27
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------

namespace mtmuo\think\middleware;


use Closure;
use mtmuo\think\facade\JWTAuth;
use think\Request;

/**
 * 自动刷新授权时间
 * Class AutoRefreshAuthMiddleware
 * @package mtmuo\think\middleware
 */
class AutoRefreshAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (JWTAuth::isAuth() && (int)JWTAuth::getPayload('exp') < strtotime("+30 min")) {
            JWTAuth::refresh();
        }
        return $next($request);
    }
}
