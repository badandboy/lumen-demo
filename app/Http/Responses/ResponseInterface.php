<?php
namespace App\Http\Responses;

/**
 * 响应规则接口
 *
 * @author: echo
 * @date:2019.05.18
 */
interface ResponseInterface
{
    /**
     * 获取字段映射设置
     * @return mixed
     */
    public function getMapSet();

    /**
     * 获取过滤映射设置
     * @return mixed
     */
    public function getFilterSet();

    /**
     * 获取填充映射设置
     * @return mixed
     */
    public function getFillSet();

}
