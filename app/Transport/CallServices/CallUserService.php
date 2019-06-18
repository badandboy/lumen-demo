<?php
/**
 * Created by PhpStorm.
 * User: 13018
 * Date: 2019/6/18
 * Time: 11:02
 */

namespace App\Transport\CallServices;


use App\Transport\AbsTransport;
use App\Transport\TransferWay\Http;

class CallUserService extends AbsTransport
{
    /**
     * Created by PhpStorm.
     * User: curry
     * 方法名 encodeRequest
     * 调用外部接口公用参数封装
     * @param $req
     * @param $ext
     * @return \App\Transport\reqEntity
     * Date: 2019/6/18 16:07
     */
    public function encodeRequest($req, $ext)
    {
        $req['token'] = 'token';
        return $req;
    }

    /**
     * Created by PhpStorm.
     * User: curry
     * 方法名 call
     * @param $reqEntity
     * @param $ext
     * @return \App\Transport\rsp|string
     * @throws \App\Exceptions\CallServiceException
     * Date: 2019/6/18 16:07
     */
    public function call($reqEntity, $ext)
    {
        $requestUrl = $this->fillUrl(config('api.user_service') . $ext['path']);
        $param = $reqEntity;
        $headers = array();
        $headers['Content-Type'] = 'application/json';
        return Http::call($requestUrl, $param, 'POST', false, $headers);
    }

}