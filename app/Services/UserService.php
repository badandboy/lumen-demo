<?php
/**
 * Created by PhpStorm.
 * User: lucky
 * Date: 2019/5/16
 * Time: 16:54
 */

namespace App\Services;

use App\Repositories\UserRepository;

class UserService extends Service
{
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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
        return $this->userRepository->getUserInfo($user_id);
    }
}