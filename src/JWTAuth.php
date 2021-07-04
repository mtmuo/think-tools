<?php
// +----------------------------------------------------------------------
// | think-utils-Jwt
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://www.bajiukeji.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Date: 2021/04/20 14:08
// +----------------------------------------------------------------------
// | Author: bajiu <bajiu@bajiukeji.com>
// +--------------------------------------------------------------------

namespace mtmuo\think;

use mtmuo\think\exception\AuthException;
use mtmuo\think\jwt\Payload;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Cookie;
use Closure;
use think\facade\Request;

class JWTAuth
{
    /**
     * @var Payload
     */
    protected $payload;

    /**
     * 配置参数
     * @var array
     */
    protected $config = [
        // 秘钥信息
        'secret' => "think-tools",
        // 签发者
        'iss' => '',
        // 面向用户
        'sub' => '',
        // 接收方
        'aud' => '',
        // 证书有效时间
        'ttl' => 86400,
        // 刷新TOKEN
        'refresh_ttl' => 1800,
        // 加密方式
        'algo' => 'MD5',
        // 黑名单模式验证
        'strict' => true,
        // cookie方式验证
        'with_cookie' => true,
        'callback' => null,
        // cookie属性
        'cookie' => [
            'httponly' => true,
        ]
    ];


    /**
     * 是否授权
     * @var bool
     */
    protected $isAuth = false;

    public function __construct()
    {
        $this->config = array_merge($this->config, Config::get('jwt', []));
        $this->payload = new Payload();
    }

    public function token()
    {
        $token = $this->config['with_cookie'] ? Request::cookie('Authorization', "") : Request::header('Authorization', "");
        return trim(str_replace("Bearer", "", $token));
    }

    /**
     * @param mixed $claims
     * @param array $config
     * @return string
     * @date: 2021-06-02 11:07
     * @author: zt
     */
    public function builder($claims = null, array $config = []): string
    {
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }
        $this->payload
            ->iss($this->config['iss'])
            ->sub($this->config['sub'])
            ->aud($this->config['aud'])
            ->exp(time() + $this->config['ttl'])
            ->setClaims($claims);
        return $this->create();
    }

    private function signature($baseString, $algo)
    {
        return $this->base64UrlEncode(hash_hmac($algo, $baseString, $this->config['secret'], true));
    }

    private function base64UrlEncode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    private function base64UrlDecode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addled = 4 - $remainder;
            $input .= str_repeat('=', $addled);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * @param $input
     * @return Payload
     * @throws AuthException
     * @date: 2021-06-02 11:05
     * @author: zt
     */
    public function auth($input): Payload
    {
        if (empty($input)) {
            throw new AuthException("authorization token is empty");
        }
        $arr = explode('.', $input);
        if (count($arr) != 3) {
            throw new AuthException("authorization token format error");
        }
        $header = json_decode($this->base64UrlDecode($arr[0]), true);
        if (empty($header)) {
            throw new AuthException("authorization header verification failed");
        }
        $signature = $this->signature($arr[0] . "." . $arr[1], $header['alg']);
        if ($signature != $arr[2]) {
            throw new AuthException("authorization signature verification failed");
        }
        $payload = json_decode($this->base64UrlDecode($arr[1]), true);
        if (!is_array($payload)) {
            throw new AuthException("authorization token format error");
        } elseif ($payload['exp'] < time()) {
            throw new AuthException("the authorization token has expired");
        } elseif ($payload['iss'] != $this->config['iss'] || $payload['sub'] != $this->config['sub'] || $payload['aud'] != $this->config['aud']) {
            throw new AuthException("the authorization not not support");
        }
        if ($this->config['strict'] && !$this->validate($payload['jti'])) {
            throw new AuthException("the authorization token has expired or blacklist");
        }

        $this->payload
            ->exp($payload['iss'])
            ->sub($payload['sub'])
            ->aud($payload['aud'])
            ->exp($payload['exp'])
            ->nbf($payload['nbf'])
            ->iat($payload['iat'])
            ->jti($payload['jti'])
            ->setClaims($payload['claims']);

        $callback = $this->config['callback'];
        /// 验证成功
        if ($callback instanceof Closure || (is_string($callback) && function_exists($callback))) {
            call_user_func($this->config['callback'], $this->payload);
        }
        $this->isAuth = true;
        return $this->payload;
    }

    public function create(): string
    {
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => $this->config['algo'],
        ]);
        if (empty($this->payload->jti)) {
            $this->payload->jti = md5(uniqid(microtime(true), true));
        }
        $base64 = $this->base64UrlEncode($header) . '.' . $this->base64UrlEncode($this->payload->toJson());
        $key = $base64 . '.' . $this->signature($base64, $this->config['algo']);
        if ($this->config['with_cookie']) {
            Cookie::set('Authorization', $key, array_merge($this->config['cookie'], ['expire' => $this->config['ttl']]));
        }
        header('Authorization:' . $key);
        header('Authorization-Max-Age:' . $this->config['ttl']);
        Cache::set($this->payload->jti, time(), $this->config['ttl']);
        return $key;
    }

    public function refresh(): string
    {
        $this->payload->exp(time() + $this->config['ttl']);
        return $this->create();
    }

    public function getClaim($key = null, $default = null)
    {
        return $this->payload->getClaim($key, $default);
    }

    public function getClaims()
    {
        return $this->payload->get(null);
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return mixed|Payload|null
     * @date: 2021-06-02 11:17
     * @author: zt
     */
    public function getPayload($key = null, $default = null)
    {
        return $this->payload->get($key, $default);
    }

    public function invalidate($jit = null, int $delay = 0): bool
    {
        $jit = $jit ?? $this->payload->jti;
        if (empty($jit)) {
            return true;
        }
        if ($delay) {
            header('Authorization-Max-Age:' . $delay);
            return Cache::set($jit ?? $this->payload->jti, time(), $delay);
        }
        header('Authorization: ');
        Cookie::delete('Authorization');
        return Cache::delete($jit ?? $this->payload->jti);
    }

    public function validate($jit = null): bool
    {
        return Cache::has($jit ?? $this->payload->jti);
    }

    /**
     * Check whether the current request is authorized to verify
     * @return bool
     * @date: 2021-06-02 11:20
     * @author: zt
     */
    public function isAuth(): bool
    {
        return $this->isAuth;
    }
}
