<?php
// +----------------------------------------------------------------------
// | think-utils
// +----------------------------------------------------------------------
// | Copyright (c) think-utils
// +----------------------------------------------------------------------
// | Date: 2021/04/20 17:17
// +----------------------------------------------------------------------
// | Author: mtmuo
// +--------------------------------------------------------------------

namespace mtmuo\think\exception;


use think\Exception;
use Throwable;

class AuthException extends Exception
{
    public function __construct($message = "", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
