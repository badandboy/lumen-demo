<?php
namespace App\Dto\Responses;
use App\Enums\ResponseEnum;

/**
 * 响应传输对象抽象类
 *
 * @author: echo
 * @date: 2019-05-10
 */
abstract class AbsResponseDto implements IResponseDto
{
    public $code;

    public $msg;

    public $data;

    function __construct($code=ResponseEnum::SUCCESS, $msg=null, $data=null)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
    }

    public function getCode()
    {

        return $this->code;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function getData()
    {
        return $this->data;
    }

    public function isSuccess()
    {
        return $this->code == ResponseEnum::SUCCESS;
    }
}