<?php

namespace App\Http\Requests;

/**
 * 示例请求类
 *
 * @author: echo
 * @date:2019.05.06
 */
class DemoRequest extends Request
{


    /**
     * 规则设定
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'app_id' => 'required',
        ];
    }

    /**
     * 对应消息描述，默认为空
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => 'id不能为空',
            'app_id.required' => 'app_id不能为空',
        ];
    }


    /**
     * 是否开启校验,默认开启
     * @return array
     */
    /*public function enableValid()
    {
        return true;
    }*/
}
