<?php

use mtmuo\think\facade\JWTAuth;
use think\response\Json;

if (!function_exists('response_success')) {
    /**
     * 返回请求请成功
     * @param array|string $data
     * @param int $code
     * @return \think\response\Json
     */
    function response_success($data = [], int $code = 0): Json
    {
        return json([
            'code' => $code,
            'msg' => 'success',
            'timestamp' => time(),
            'result' => $data,
        ]);
    }
}

if (!function_exists('response_error')) {
    function response_error($msg = 'error', int $code = 500): Json
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'timestamp' => time(),
        ]);
    }
}

if (!function_exists('response_status')) {
    function response_status($msg = 'success', $code = 0): Json
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'timestamp' => time(),
        ]);
    }
}

if (!function_exists('auth_builder')) {
    /**
     * @param string|array $claims
     * @param array $config
     * @return string
     * @date: 2021-06-02 11:09
     * @author: zt
     */
    function auth_builder($claims, array $config = []): string
    {
        return JWTAuth::builder($claims,$config);
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
     * @param  $key
     * @param  $default
     * @return mixed
     */
    function auth_claim($key, $default = null)
    {
        return JWTAuth::getClaim($key, $default);
    }
}
if (!function_exists('auth_payload')) {
    /**
     * @param mixed $key
     * @param mixed $default
     * @return \mtmuo\think\jwt\Payload|string
     */
    function auth_payload($key = null, $default = null)
    {
        return JWTAuth::getPayload($key, $default);
    }
}
