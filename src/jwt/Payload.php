<?php
// +----------------------------------------------------------------------
// | think-utils
// +----------------------------------------------------------------------
// | Copyright (c) think-utils
// +----------------------------------------------------------------------
// | Date: 2021/04/20 14:47
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------

namespace mtmuo\think\jwt;

/**
 * Class Payload
 * @method Payload iss($value)
 * @method Payload sub($value)
 * @method Payload aud($value)
 * @method Payload exp($value)
 * @method Payload nbf($value)
 * @method Payload iat($value)
 * @method Payload jti($value)
 * @package
 */
class Payload
{
    public $claims = [];

    // 签发者
    public $iss;

    // 面向用户
    public $sub;

    // 接收方
    public $aud;

    // 过期时间
    public $exp;

    // 生效时间
    public $nbf;

    // 签发时间
    public $iat;

    // 身份标识
    public $jti;

    public function getClaim($key = null, $default = null)
    {
        if (empty($key)) {
            return $this->claims;
        }
        return $this->claims[$key] ?? $default;
    }

    public function setClaims($data): Payload
    {
        $this->claims = $data;
        return $this;
    }

    public function toJson()
    {
        return json_encode([
            'iss' => $this->iss,
            'sub' => $this->sub,
            'aud' => $this->aud,
            'exp' => $this->exp,
            'nbf' => $this->nbf,
            'iat' => $this->iat ?? time(),
            'jti' => $this->jti,
            'claims' => $this->claims
        ]);
    }

    public function setClaim(string $key, $value): Payload
    {
        $this->claims[$key] = $value;
        return $this;
    }

    public function get($key, $default = null)
    {
        if (empty($key)) {
            return $this;
        }
        return $this->{$key} ?? $default;
    }

    public function __call($name, $arguments)
    {
        $this->{$name} = $arguments[0];
        return $this;
    }
}
