<?php
namespace App\Services;

/**
 * 服务抽象类
 * 扩展使用
 *
 * @author: echo
 * @date: 2019.05.10
 */
abstract class Service
{


    private static $singletonArr = array();

    /**
     * 获取实例
     * @return static
     */
    public static function instance()
    {
        return (new static);
    }


    /**
     * 获取单例
     * @return Service
     */
    public static function singleton()
    {
        $name = static::class;
        if (!isset(self::$singletonArr[$name])) {
            self::$singletonArr[$name] = self::instance();
        }
        return self::$singletonArr[$name];
    }

    /**
     * 清除单例对象
     * @param null $name
     */
    public static function clearSingleton($name = null)
    {
        if ($name === null) {
            self::$singletonArr = array();
            return;
        }

        unset(self::$singletonArr[$name]);
    }
}