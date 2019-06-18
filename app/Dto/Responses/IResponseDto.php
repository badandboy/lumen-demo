<?php
namespace App\Dto\Responses;

/**
 * 响应传输对象接口
 *
 * @author: echo
 * @date: 2019-05-10
 */
interface IResponseDto
{
    /**
     * 获取响应编码
     * @return mixed
     */
    function getCode();

    /**
     * 获取响应描述
     * @return mixed
     */
    function getMsg();

    /**
     * 获取响应数据
     * @return mixed
     */
    function getData();

    /**
     * 响应返回是否成功
     * @return mixed
     */
    function isSuccess();
}