<?php
namespace App\Http\Responses;
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2019-05-18
 * Time: 14:04
 */
class ResourceResponse extends ResponseBase
{

    public function getMapSet()
    {
        return [
            'display_state'=>'state',//状态
        ];
    }

    public function getFillSet()
    {
        return [
            'id',
            'resource_id' => 'id|',
            'title',//名称
            'author',//作者
            'img_url',//图片
            'patch_img_url',//封面图
            'patch_img_url_compressed',//封面压缩图
            'img_url_compressed',//压缩图
            'img_url_compressed_larger',//压缩大图
        ];
    }

}