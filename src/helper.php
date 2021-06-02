<?php

use mtmuo\think\facade\JWTAuth;
use think\response\Json;

if (!function_exists('response_success')) {
    /**
     * 返回请求请成功
     * @param array|string $data
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
    function response_status($msg = 'success'): Json
    {
        return json([
            'code' => 0,
            'msg' => $msg,
            'timestamp' => time(),
        ]);
    }
}

if (!function_exists('auth_builder')) {
    function auth_builder(array $claims, array $config = []): string
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
     * @param null $key
     * @param null $default
     * @return mixed
     */
    function auth_claim($key = null, $default = null)
    {
        return JWTAuth::getClaim($key, $default);
    }
}
if (!function_exists('auth_payload')) {
    /**
     * @param null $key
     * @param null $default
     * @return \mtmuo\think\jwt\Payload|string
     */
    function auth_payload($key = null, $default = null)
    {
        return JWTAuth::getPayload($key, $default);
    }
}
