<?php

use mtmuo\think\facade\JWTAuth;
use think\response\Json;

if (!function_exists('response_success')) {
    /**
     * 返回请求请成功
     * @param array $data
     * @return \think\response\Json
     */
    function response_success($data = []): Json
    {
        return json([
            'code' => 0,
            'msg' => 'success',
            'timestamp' => time(),
            'result' => $data,
        ]);
    }
}

if (!function_exists('response_error')) {
    function response_error($msg = 'error', $code = 500): Json
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'timestamp' => time(),
        ]);
    }
}

if (!function_exists('response_status')) {
    function response_status($msg = 'error'): Json
    {
        return json([
            'code' => 0,
            'msg' => $msg,
            'timestamp' => time(),
        ]);
    }
}

if (!function_exists('auth_builder')) {
    function auth_builder(array $claims): string
    {
        return JWTAuth::builder($claims);
    }
}

if (!function_exists('auth_validate')) {
    function auth_validate(string $jit): bool
    {
        return JWTAuth::validate($jit);
    }
}

if (!function_exists('auth_invalidate')) {
    function auth_invalidate(string $jit): bool
    {
        return JWTAuth::invalidate($jit);
    }
}

if (!function_exists('auth_claim')) {
    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    function auth_claim(string $key = null, $default = null)
    {
        return JWTAuth::getClaim($key, $default);
    }
}
