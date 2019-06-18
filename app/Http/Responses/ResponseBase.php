<?php
namespace App\Http\Responses;

/**
 * 响应抽象类型
 *
 * @author: echo
 * @date:2019.05.18
 */
abstract class ResponseBase implements ResponseInterface
{

    private $_data;

    function __construct($data)
    {
        $this->_data = $data;
    }

    public function getMapSet()
    {
        return array();
    }

    public function getFillSet()
    {
        return array();
    }

    public function getFilterSet()
    {
        return array();
    }

    /**
     * 过滤是否反正
     * @return bool
     */
    public function isFilterReverse()
    {
        return false;
    }

    /**
     * 最终处理
     * @return array
     */
    private function toProcess()
    {
        return data_process($this->_data)
            ->setMap($this->getMapSet())
            ->setFilter($this->getFilterSet(), $this->isFilterReverse())
            ->setFill($this->getFillSet())
            ->get();
    }

    /**
     * 将数据根据响应设置处理
     * 映射、过滤、填充等
     * @param  $data 需要处理的响应数据
     * @return mixed
     */
    public static function process($data)
    {
        return (new static($data))->toProcess();
    }
}