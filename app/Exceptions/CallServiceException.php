<?php

namespace App\Exceptions;


use App\Enums\ResponseEnum;
use Exception;


/**
 * 调用服务异常类
 *
 * @author: echo
 * @date:2019.05.07
 */
class CallServiceException extends Exception
{
    private $beforeCode;
    public function __construct($message = "", $beforeCode=null)
    {

        $this->beforeCode = $beforeCode;
        parent::__construct($message, ResponseEnum::CALL_SERVICE_ERR, null);
    }

    public function getBeforeCode(){
        return $this->beforeCode;
    }
}