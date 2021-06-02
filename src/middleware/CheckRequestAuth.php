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
        try {
            $this->check($request);
            $response = $next($request);
            $this->refresh();
            return $response->header($this->header);
        } catch (Exception $exception) {
            return $next($request)->header($this->header);
        }
    }
}
