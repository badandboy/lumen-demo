<?php
namespace App\Http\Responses;

use App\Dto\Responses\IResponseDto;
use App\Enums\ResponseEnum;

/**
 * 响应构造类
 *
 * @author: echo
 * @date:2019.05.06
 */
class ResponseBuilder
{
    private $code;

    private $msg;

    private $data = '';
    /**
     * @var array 额外顶层参数
     */
    private $_extParam = array();

    public function __construct(int $code, $msg, $data)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;

    }

    public static function instance($code = ResponseEnum::SUCCESS, $msg = null, $data = null): ResponseBuilder
    {

        return new self($code, $msg, $data);
    }


    public function setCode(int $code): ResponseBuilder
    {
        $this->code = $code;
        return $this;
    }


    public function setMsg($msg): ResponseBuilder
    {
        $this->msg = $msg;
        return $this;
    }


    public function setData($data): ResponseBuilder
    {
        $this->data = $data;
        return $this;
    }

    public function setResponseDto(IResponseDto $responseDto): ResponseBuilder
    {
        $this->code = $responseDto->getCode();
        $this->msg = $responseDto->getMsg();
        $this->data = $responseDto->getData();
        return $this;
    }

    public function setExtParam(array $extParam): ResponseBuilder
    {
        $this->_extParam = $extParam;
        return $this;
    }

    public function response()
    {
        $msg = $this->msg;
        if ($msg == null) {
            $msg = ResponseEnum::getDescription($this->code);
        }
        $data = ['code' => $this->code, "msg" => $msg, 'data' => $this->data];
        if (!empty($this->_extParam)) {
            $data = array_merge($data, $this->_extParam);
        }
        return response()->json($data);
    }
}