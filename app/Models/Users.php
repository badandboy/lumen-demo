<?php
/**
 * Created by PhpStorm.
 * User: lucky
 * Date: 2019/5/16
 * Time: 16:59
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $connection = 'mysql';
    protected $table = 't_users';

    /**
     * Created by PhpStorm.
     * User: curry
     * 方法名 getUserInfo
     * @param $user_id
     * @return mixed
     * Date: 2019/6/18 15:59
     */
    public static function getUserInfo($user_id)
    {
        return self::select('id','user_name', 'password')
            ->where('id',$user_id)
            ->first();
    }

}