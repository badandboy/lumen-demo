<?php
/**
 * Created by PhpStorm.
 * User: 13018
 * Date: 2019/6/18
 * Time: 10:54
 */

namespace App\Repositories;

use App\Enums\CallServiceEnum;
use App\Transport\TransportProvider;
use App\Utils\Utils;

class UserRepository
{

    /**
     * Created by PhpStorm.
     * User: curry
     * 方法名 getUserInfo
     * @param $user_id
     * @return \App\Dto\Responses\IResponseDto|mixed
     * @throws \App\Exceptions\CallServiceException
     * Date: 2019/6/18 16:14
     */
    public function getUserInfo($user_id){
        $params = [
            'user_id' => $user_id,
        ];

        $ext = [
            'path' => 'path',
            'method' => 'post'
        ];

        return TransportProvider::call(CallServiceEnum::USER_SERVICE,$params,$ext);
    }

}