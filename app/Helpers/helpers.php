<?php
/**
 * 通用函数方法
 *
 * @author: echo
 * @date:2019.04.30
 */


if (! function_exists('dump')) {
    /**
     * var_dump 值
     *
     * @param $value
     * @author: echo
     */
    function dump($value)
    {
        var_dump($value);
    }
}

if (! function_exists('get_micro_time')) {
    /**
     * 获取毫秒戳
     *
     * @author: echo
     * @date: 2019.05.06
     */
    function get_micro_time()
    {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}
if (! function_exists('get_url')) {
    /**
     * 获取url地址
     *
     * @param $url
     * @param $q 其他字符串或数组
     * @return string
     * @author: echo
     * @date: 2019.05.08
     */
    function get_url($url,$q)
    {
        if (is_array($q))
            $q = http_build_query($q);
        if(empty($q))
            return $url;

        if (strpos($url, '?') !== false)
            $url .= '&';
        else
            $url .= '?';

        return $url . $q;
    }
}
if (! function_exists('data_process')) {
    /**
     * 数据处理
     *
     * @param $data 待处理的数据
     * @return \App\Utils\DataProcessUtils
     */
    function data_process($data)
    {
        return new \App\Utils\DataProcessUtils($data);
    }
}

if (! function_exists('fill_app_gray_scale')) {

    /**
     * 填充app标识信息，灰度用
     * @param $param
     * @return array
     * @author: echo
     * @date: 2019.05.24
     */
    function fill_app_gray_scale($param)
    {
        $appMark = 'app_version';
        $appVersion = \Illuminate\Support\Facades\Input::get($appMark) ?? null;

        if (empty($appVersion))
            return $param;
        if (empty($param))
            $param = array();
        $param[$appMark] = $appVersion;

        return $param;
    }
}

if (! function_exists('number_to_string')) {
    /**
     * @param $value
     * @param $default
     * @param $row
     * @param $fieldName
     * @return string
     * User: "luckytan"
     * Time: 2019/5/2917:38
     */
    function number_to_string($value, $default, $row, $fieldName)
    {
        if ($value === null)
            return '';

        return $value . '';
    }
}