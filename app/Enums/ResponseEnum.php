<?php
namespace App\Enums;


/**
 * 全局响应枚举类
 *
 * @author: echo
 * @date:2019.05.06
 */
class ResponseEnum
{
    use EnumTrait;


    const SUCCESS = 0;
    const FAILED = 1;

    const UN_KNOW = 1000;
    const DB_ERROR = 1001; //数据库错误
    const DB_EMPTY = 1002; //数据库查不到
    const PARAM_ERR = 1003;

    //枚举对应的信息
    static $CODE_DES_MAP = [
        self::SUCCESS => '成功',
        self::FAILED => '失败',
        self::UN_KNOW => '未知错误',
        self::DB_ERROR => '数据库错误',
        self::DB_EMPTY => '数据库查不到',
        self::PARAM_ERR => '参数错误',
    ];

}