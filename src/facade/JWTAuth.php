<?php
// +----------------------------------------------------------------------
// | think-utils-JWTAuth
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://www.bajiukeji.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Date: 2021/04/20 14:21
// +----------------------------------------------------------------------
// | Author: bajiu <bajiu@bajiukeji.com>
// +--------------------------------------------------------------------

namespace mtmuo\think\facade;

use mtmuo\think\jwt\Payload;
use think\Facade;

/**
 * Class JWTAuth
 * @method static string builder($data)
 * @method static Payload auth($data)
 * @method static string refresh()
 * @package mtmuo\think\facade
 */
class JWTAuth extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'mtmuo\think\JWTAuth';
    }
}
