<?php

namespace App\Http\Controllers;

use App\Enums\CallServiceEnum;
use App\Enums\ResponseEnum;
use App\Http\Requests\DemoRequest;
use App\Http\Responses\ResponseBuilder;
use App\Services\DemoService;
use App\Services\UserService;
use App\Transport\TransportProvider;

/**
 * demo相关
 *
 * @author: echo
 * @date:2019.04.30
 */
class DemoController extends Controller
{


    function __construct()
    {
    }

    /**
     * 例子
     *
     * @param DemoRequest $request
     * @param DemoService $demoService
     * @return \Illuminate\Http\JsonResponse
     * @author: echo
     */
    public function demo(DemoRequest $request, DemoService $demoService)
    {

        $data = array();

        $demoInfo = $demoService->getInfo($request->get('id'));

        //可以继续调用其他服务


        //组装数据或根据要求处理数据
        ////将$demoInfo里面的title键改为name
        $map = ['title' => 'name'];
        $demoInfo = data_process(collect($demoInfo)->toArray())->setMap($map)->get();

        $data['demo_info'] = $demoInfo;
        $data['curr_time'] = get_micro_time();//自定义函数

        return ResponseBuilder::instance()->setData($data)->response();
    }

    /**
     * Created by PhpStorm.
     * User: curry
     * 方法名 multiGetUserInfo
     * @param DemoRequest $request
     * @param UserService $userService
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\CallServiceException
     * Date: 2019/6/18 15:50
     */
    public function getUserInfo(DemoRequest $request,UserService $userService)
    {
        $data = $request->get('data');
        $user_id = $data['user_id'];

        if (!$user_id){
            return ResponseBuilder::instance()->setData([])->response();
        }

        $userInfoResponse = $userService->getUserInfo($user_id);
        if (!$userInfoResponse->isSuccess()){
            return ResponseBuilder::instance()->setResponseDto($userInfoResponse)->response();
        }
        $userInfo = $userInfoResponse->getData();

        return ResponseBuilder::instance()->setData($userInfo)->response();
    }

}
