<?php
namespace App\Transport;

use App\Dto\Responses\IResponseDto;

/**
 * 传输调用接口
 *
 * @author: echo
 * @date: 2019-05-05
 */
interface ITransport
{


    /**
     * 是否开启熔断器
     */
    public function enableCircuitBreaker();

    /**
     * 编码请求
     * @param: $req 请求参数
     * @param: $ext 扩展参数
     * @return reqEntity 请求实体
     */
    public function encodeRequest($req, $ext);

    /**
     * 传输调用
     * @param: $reqEntity 请求实体
     * @param: $ext 扩展参数
     * @return rsp 响应
     */
    public function call($reqEntity, $ext);


    /**
     * 解码响应
     * @param: $rep 响应
     * @param: $ext 扩展参数
     * @return rspEntity 返回响应实体
     */
    public function decodeResponse($rep, $ext): IResponseDto;

}