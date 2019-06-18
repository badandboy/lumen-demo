<?php
namespace App\Services;


use App\Repositories\DemoRepository;

/**
 * 示例服务类
 *
 * 正常需要先增加示例服务接口，这里实现接口；面向接口编程，目前省去这层
 * 尽量符合单一原则
 * 通过依赖注册
 *
 * @author: echo
 * @date: 2019.05.10
 */
class DemoService extends Service
{
    private $demoRepository;

    public function __construct(DemoRepository $demoRepository)
    {
        $this->demoRepository = $demoRepository;
    }

    public function getInfo($id)
    {
        //缓存操作
        //Cache::add("kk", "1212", 1);
        return $this->demoRepository->getInfo($id);
    }

}