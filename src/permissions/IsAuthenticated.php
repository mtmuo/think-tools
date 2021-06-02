<?php
// +----------------------------------------------------------------------
// | think-utils
// +----------------------------------------------------------------------
// | Copyright (c) think-utils
// +----------------------------------------------------------------------
// | Date: 2021/06/01 14:32
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------

namespace mtmuo\think\permissions;

use mtmuo\think\exception\AuthException;
use mtmuo\think\facade\JWTAuth;
use think\facade\Request;

/**
 * 必须授权访问
 * @property array $exclude
 * @property string $sub
 * @package mtmuo\think\permissions
 */
class IsAuthenticated
{
    /**
     * 必选全部授权中间件
     * @throws \mtmuo\think\exception\AuthException
     */
    public function __construct()
    {
        if (property_exists($this, 'exclude') && in_array(strtolower(Request::action()), array_map('strtolower', $this->exclude))) {
            return;
        }
        // 判断授权对象
        if (property_exists($this, 'sub') && auth_payload('sub') != $this->sub) {
            throw new AuthException("授权身份验证不通过");
        } elseif (!JWTAuth::isAuth()) {
            throw new AuthException("身份验证不通过");
        }
    }
}
