<?php
// +----------------------------------------------------------------------
// | think-utils
// +----------------------------------------------------------------------
// | Copyright (c) think-utils
// +----------------------------------------------------------------------
// | Date: 2021/04/20 14:31
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------
return [
    'jwt' => [
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
        // 加密方式
        'algo' => 'SHA256',
        // 返回cookie
        'with_cookie' => true,
        'cookie' => [
            //'secure' => true,
            //'httponly' => true,
        ]
    ]
];
