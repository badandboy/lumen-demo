<?php
namespace App\Http\Responses;
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2019-05-18
 * Time: 14:04
 */
class UserResponse extends ResponseBase
{

    //映射
    public function getMapSet()
    {
        return [
            'state'=>'display_state',//状态
        ];
    }

    //填充
    public function getFillSet()
    {
        return [
            'id',
            'user_name',
            'password',//密码
            'state' => '|0',//状态
            'created_at',//创建时间
            'updated_at'//更新时间
        ];
    }

}