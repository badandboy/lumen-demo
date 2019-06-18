<?php

namespace App\Http\Middleware;


use App\Enums\ResponseEnum;
use App\Exceptions\RequestValidateException;
use App\Http\Responses\ResponseBuilder;
use App\Utils\Utils;
use Closure;
use Illuminate\Support\Facades\Log;

/**
 * 请求记录中间件
 *
 * @author: echo
 * @date:2019.04.30
 */
class RequestLogMiddleware
{
    public function handle($request, Closure $next)
    {
        $curr = get_micro_time();

        $response = $next($request);

        $isSuccess = $response->isOk();
        $isOnlyLogErr = config('app.log_only_error_request');
        if ($isOnlyLogErr && $isSuccess)
            return $response;

        $ex = $response->exception;
        if (!$isSuccess && $ex instanceof RequestValidateException) { //目前参数校验不做日志
            return ResponseBuilder::instance(ResponseEnum::PARAM_ERR, $ex->getMessage())->response();
        }

        $logData = array();
        $logData['message'] = $isSuccess ? ($response->original) : (Utils::getErrMsg($response->exception));
        $logData['url'] = $request->url();
        $logData['params'] = $request->all();
        $logData['ip'] = $request->getClientIp();
        $logData['server_ip'] = $request->server('SERVER_ADDR');
        $logData['method'] = $request->getMethod();
        $logData['state'] = $isSuccess ? 1 : 0;
        $logData['request_time'] = get_micro_time() - $curr;

        if($isSuccess){
            Log::info(json_encode($logData));
        }else {
            Log::error(json_encode($logData));
        }

        if (!$isSuccess && config('app.debug') === false) { //异常统一返回值
            //$msg = $ex->getMessage();
            return ResponseBuilder::instance(ResponseEnum::UN_KNOW)->response();
        }

        return $response;
    }

}