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
use Exception;
use think\Request;

class CheckRequestAuth extends ForceCheckRequestAuth
{
    public function handle(Request $request, Closure $next)
    {
        if ($origin = $request->header('origin')) {
            $this->header['Access-Control-Allow-Origin'] = $origin;
        }
        try {
            $this->check($request);
        } catch (Exception $exception) {
            // 系统劫持异常继续
        }
        return $next($request)->header($this->header);
    }
}
