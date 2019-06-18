<?php
namespace App\Utils;

class Utils
{
    /**
     * 获取当前时间
     * @return false|string
     * @author curryliu
     * @date 2019/5/9 10:12
     */
    public static function getDateNow(){
        return date("Y-m-d H:i:s",time());
    }

    /**
     * 获取两个时间的差值，单位:秒
     * @param $time1
     * @param $time2
     * @return false|int
     * @author curryliu
     * @date 2019/5/9 16:21
     */
    public static function getTimeStamp($time1="",$time2=""){
        if (empty($time1)){
            $time1 = self::getDateNow();
        }

        if (empty($time2)){
            $time2 = self::getDateNow();
        }
        return strtotime($time1) - strtotime($time2);
    }

    /**
     * Created by PhpStorm.
     * User: curryliu
     * 方法名 getWeekDay
     * @param $time
     * @return mixed
     * Date: 2019/5/16 17:05
     */
    public static function getWeekDay($time=""){
        if (empty($time)){
            $time = date("Y-m-d");
        }

        $week = date("w",strtotime($time));

        $weekDay = ["星期日","星期一","星期二","星期三","星期四","星期五","星期六"];
        return $weekDay[$week];
    }

    /**
     * Created by PhpStorm.
     * User: curry
     * 方法名 logFrom
     * @param $data
     * @param string $logPath
     * Date: 2019/6/18 16:09
     */
    public static function logFrom($data, $logPath='debug.log')
    {

        $enableLog = env('Message_log', true);

        if ($enableLog) {

            if (!is_string($data)) {
                $msg = var_export($data, true);
            } else {
                $msg = $data;
            }

            $timeStamp = time();
            $timeStr = date('y-m-d G:i:s', $timeStamp);
            error_log("[$timeStr] $msg\n", '3', storage_path('logs/' . $logPath));
        }
    }

}