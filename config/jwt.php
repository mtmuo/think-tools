<?php
// +----------------------------------------------------------------------
// | think-tools
// +----------------------------------------------------------------------
// | Date: 2021/06/2021/6/27 12:04
// +----------------------------------------------------------------------
// | Author: mtmuo <2172614@qq.com>
// +----------------------------------------------------------------------

use mtmuo\think\jwt\Payload;

return [
    // 秘钥信息
    'secret' => "think-tools",
    // 签发者
    'iss' => 'think',
    // 面向用户
    'sub' => 'think',
    // 接收方
    'aud' => 'think',
    // 证书有效时间
    'ttl' => 86400,
    // 加密方式
    'algo' => 'MD5',
    // 严格模式验证黑名单机制
    'strict' => true,
    // 返回cookie
    'with_cookie' => false,

    // 验证cookie设置
    'cookie' => [
        'httponly' => true,
    ],
    // 验证成功回调
    'callback' => function (Payload $payload) {

    },
    // 白名单
    'exclude' => [],
];
