<?php
namespace App\Enums;


/**
 * 枚举通过只获取对应信息
 *
 * @author: echo
 * @date:2019.05.05
 */
trait EnumTrait
{
    /**
     * 常量存储数组
     *
     * @var array
     */
    protected static $constCacheArray = [];

    /**
     * 根据value获取对应的描述
     * 必须定义静态变量$CODE_DES_MAP
     * @param $value
     * @return mixed
     */
    public static function getDescription($value)
    {
        if (!isset(self::$CODE_DES_MAP) || !isset(self::$CODE_DES_MAP[$value]))
            return '未知code:' . $value;

        return self::$CODE_DES_MAP[$value];
    }

    /**
     * 获取类的所有常量
     *
     * @return array
     */
    protected static function getConstants(): array
    {
        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, static::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            static::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return static::$constCacheArray[$calledClass];
    }


    /**
     * 获取所有枚举的值
     *
     * @return array
     */
    public static function getValues(): array
    {
        return array_values(static::getConstants());
    }

}