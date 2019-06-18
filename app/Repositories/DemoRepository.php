<?php

namespace App\Repositories;

use App\Models\Audio;

/**
 * demo数据库逻辑
 *
 * 将 Model 依赖注入到 Repository。
 * 将数据库逻辑写在 Repository。
 * 将 Repository 依赖注入到 Service。
 *
 * @author: echo
 * @date: 2019.05.10
 */
class DemoRepository
{

    private $audio;

    public function __construct(Audio $audio)
    {
        $this->audio = $audio;
    }

    /**
     * 获取信息
     * @param $id
     * @return mixed
     */
    public function getInfo($id)
    {
        return $this->audio
            //->where('id', $id)
            ->select(['id', 'app_id', 'title'])->first();
    }
}