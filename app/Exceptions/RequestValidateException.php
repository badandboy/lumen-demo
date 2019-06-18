<?php

namespace App\Exceptions;


use App\Enums\ResponseEnum;
use Exception;


/**
 * 请求参数校验异常类
 *
 * @author: echo
 * @date:2019.05.06
 */
class RequestValidateException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message, ResponseEnum::PARAM_ERR, null);
    }
}