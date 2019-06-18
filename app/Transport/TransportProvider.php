<?php
namespace App\Transport;

use App\Exceptions\CallServiceException;
use App\Dto\Responses\IResponseDto;
use App\Utils\Utils;
use Exception;

/**
 * 传输调用接口
 *
 * @author: echo
 * @date: 2019-05-05
 */
class TransportProvider
{

    /**
     * 通过名称调用服务
     *
     * @param $serviceName
     * @param $req
     * @param array $ext
     * @return mixed
     * @throws CallServiceException
     */
    public static function call($serviceName, $req, $ext = []):IResponseDto
    {
        $service = self::getService($serviceName);
        try {
            $reqEntity = $service->encodeRequest($req, $ext);
        } catch (Exception $ex) {

            $errMessage = $ex->getMessage();
            $errMessage = '#encodeRequest#' . PHP_EOL . json_encode(compact('serviceName', 'req', 'ext', 'errMessage'));
            throw new CallServiceException($errMessage);
        }

        $enableCB = $service->enableCircuitBreaker();
        //TODO 熔断处理

        try {
            $rsp = $service->call($reqEntity, $ext);
        } catch (Exception $ex) {
            $errMessage = $ex->getMessage();
            $errMessage = '#callService#' . PHP_EOL . json_encode(compact('serviceName', 'reqEntity', 'ext', 'errMessage'));
            throw new CallServiceException($errMessage);
        }
        try {
            $rspEntity = $service->decodeResponse($rsp, $ext);
        } catch (Exception $ex) {

            $errMessage = $ex->getMessage();
            $errMessage = '#decodeResponse#' . PHP_EOL . json_encode(compact('serviceName', 'rsp', 'ext', 'errMessage'));
            throw new CallServiceException($errMessage);
        }

        return $rspEntity;

    }

    /**
     * 通过名称调用服务(异步)
     *
     * @param $serviceName
     * @param $req
     * @param array $ext
     * @return mixed
     */
    public static function asyncCall($serviceName, $req, $ext = []):IResponseDto
    {

        //TODO 目前还是同步
        return self::call($serviceName, $req, $ext);
    }

    /**
     * 通过名称获取对应的服务
     *
     * @param $serviceName
     * @return ITransport
     * @throws CallServiceException
     */
    private static function getService($serviceName): ITransport
    {
        try {

            $instance = App('App\Transport\CallServices\\' . $serviceName);
        } catch (Exception $ex) {
            $errMessage = '#getService#' . PHP_EOL . $serviceName . '不存在';
            throw new CallServiceException($errMessage);
        }

        return $instance;
    }


}