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
use think\facade\Config;
use think\facade\Cookie;

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
        'with_cookie' => true,
        'cookie' => [
            'httponly' => true,
        ]
    ];

    public function __construct()
    {
        $this->config = array_merge($this->config, Config::get('tools.jwt'), []);
        $this->payload = new Payload();
    }

    // 构建验证
    public function builder($data): string
    {
        $this->payload
            ->iss($this->config['iss'])
            ->sub($this->config['sub'])
            ->aud($this->config['aud'])
            ->exp(time() + $this->config['ttl'])
            ->setClaims($data);
        return $this->create();
    }

    private function signature(string $baseString, $algo)
    {
        return $this->base64UrlEncode(hash_hmac($algo, $baseString, $this->config['secret'], true));
    }

    private function base64UrlEncode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    private function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addled = 4 - $remainder;
            $input .= str_repeat('=', $addled);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    // 验证
    public function auth(string $input): Payload
    {
        if (empty($input)) {
            throw new AuthException("authorization token is empty");
        }
        $arr = explode('.', $input);
        if (count($arr) != 3) {
            throw new AuthException("authorization token format error");
        }
        $header = json_decode($this->base64UrlDecode($arr[0]), true);
        $signature = $this->signature($arr[0] . "." . $arr[1], $header['alg']);
        if ($signature != $arr[2]) {
            throw new AuthException("authorization token verification failed");
        }
        $payload = json_decode($this->base64UrlDecode($arr[1]), true);
        if ($payload['exp'] < time()) {
            throw new AuthException("the authorization token has expired");
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

        return $this->payload;
    }


    // 创建
    public function create(): string
    {
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => $this->config['algo'],
        ]);
        $base64 = $this->base64UrlEncode($header) . '.' . $this->base64UrlEncode($this->payload->toJson());
        $key = $base64 . '.' . $this->signature($base64, $this->config['algo']);
        if ($this->config['with_cookie']) {
            Cookie::set('Authorization', $key, array_merge($this->config['cookie'], ['expire' => $this->config['ttl']]));
        }
        header('Authorization:' . $key);
        header('Authorization-Max-Age:' . $this->config['ttl']);
        return $key;
    }

    // 刷新
    public function refresh(): string
    {
        $this->payload->exp(time() + $this->config['ttl'])->toJson();
        return $this->create();
    }

    // 获取携带信息
    public function getClaim(string $key = "", $default = null)
    {
        return $this->payload->getClaim($key, $default);
    }

    // 获取携带信息
    public function getClaims()
    {
        return $this->payload->get('');
    }

    // 获取携带信息
    public function getPayload(string $key = "", $default = null)
    {
        return $this->payload->get($key, $default);
    }
}
