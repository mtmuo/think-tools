### 创建Service和Facade

```shell
php think facade:make Login
```

### 同步 service

```shell
php think facade:sysc
```

### MYSQL数据库生成MD

```shell
php think table
```

### JWT

```php
<?php
use mtmuo\think\facade\JWTAuth;
// 创建jwt
$jwt = JWTAuth::builder(["uid" => 1]);
// 验证
$payload = JWTAuth::auth($jwt);

// 获取Claim信息
$uid = JWTAuth::getClaim('uid');
// 获取payload信息
$exp = JWTAuth::getPayload('exp');

// 拉黑Token
JWTAuth::invalidate($payload->jit);
//延迟30秒拉黑
JWTAuth::invalidate($payload->jit,30);
// 判断是否有效
JWTAuth::validate($payload->jit);
```

### JWT中间件
> 强制验证
> 
> mtmuo\think\middleware\CheckRequestAuth::class
> 
> 不强制验证
> 
> mtmuo\think\middleware\ForceCheckRequestAuth::class
>
