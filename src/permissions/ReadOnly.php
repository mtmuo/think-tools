<?php
// +----------------------------------------------------------------------
// | think-utils
// +----------------------------------------------------------------------
// | Copyright (c) think-utils
// +----------------------------------------------------------------------
// | Date: 2021/06/01 14:28
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------

namespace mtmuo\think\permissions;

use mtmuo\think\exception\AuthException;
use mtmuo\think\facade\JWTAuth;
use think\facade\Request;

/**
 * 只读权限
 * 除了排除的方法任意GET请求都已通过
 * @property array $exclude
 * @property string $sub
 * @package mtmuo\think\permissions
 */
trait ReadOnly
{
    /**
     * 只读权限
     * ReadOnly constructor.
     * @throws \mtmuo\think\exception\AuthException
     */
    public function __construct()
    {
        $is_exclude = false;
        if (property_exists($this, 'exclude') && in_array(strtolower(Request::action()), array_map('strtolower', $this->exclude))) {
            $is_exclude = true;
        }
        if (Request::isGet() && !$is_exclude) {
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
