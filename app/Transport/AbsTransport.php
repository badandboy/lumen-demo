<?php
namespace App\Transport;
use App\Enums\ResponseEnum;
use App\Dto\Responses\IResponseDto;
use App\Dto\Responses\ResponseBaseDto;
use App\Utils\Utils;

/**
 * 传输调用抽象类
 *
 * @author: echo
 * @date: 2019-05-05
 */
abstract class AbsTransport implements ITransport
{


    /**
     * 是否开启熔断器
     */
    public function enableCircuitBreaker()
    {
        return true;
    }

    /**
     * 获取当前的熔断标识
     */
    public function getCircuitBreakerMark()
    {

        return __CLASS__;
    }

    /**
     * 编码请求
     * @param: $req 请求参数
     * @param: $ext 扩展参数
     * @return reqEntity 请求实体
     */
    public function encodeRequest($req, $ext)
    {

        return $req;
    }


    /**
     * 解码响应
     * @param: $rep 响应
     * @param: $ext 扩展参数
     * @return rspEntity 返回响应实体
     */
    public function decodeResponse($rep, $ext): IResponseDto
    {

        $callback = json_decode($rep, true);

        $data = array_get($callback, 'data', []);
        $code = array_get($callback, 'code', ResponseEnum::FAILED);
        $msg = array_get($callback, 'msg', "");

        $response = new ResponseBaseDto($code, $msg, $data);

        return $response;

    }

    /**
     * 填充url
     * @param $url
     * @param array $queryArr
     * @param bool $fillAppId 是否填充app_id
     * @return string
     */
    protected function fillUrl($url, $queryArr = [], $fillAppId = true)
    {

        if ($fillAppId) {
            $queryArr['app_id'] = Utils::getAppId();
        }
        return get_url($url, $queryArr);
    }


}