<?php
namespace App\Utils;

use Closure;

/**
 * 数据字段映射、填充、过滤处理工具类
 * 处理会从第1层开始处理，注意优先级
 * 先处理过滤，然后映射，然后填充
 *
 * @author: echo
 * @date:2019.05.08
 */
class DataProcessUtils
{
    /**
     * @var string 分隔符
     */
    private $splitChar = '.';

    private $data;

    /**
     * 填充自己标识
     */
    const FILL_SELF = '@FS@';
    /**
     * 填充父亲
     */
    const FILL_SUB_PARENT = '@FSP@';

    /**
     * 要求过滤
     */
    const TO_FILTER = '@TF@';

    /**
     * 无设置或者空值就过滤，否则填充
     */
    const FILL_OMIT_EMPTY = 'omitempty';

    /**
     * 无设置键则过滤，否则填充
     */
    const FILL_OMIT_UNSET = 'omitunset';

    /**
     * 方法选项
     */
    const OPTION_FUNC = 'func';


    /**
     * @var 映射设置数组
     */
    private $mapSetArr = array();

    /**
     * @var 过滤设置数组
     */
    private $filterSetArr = array();

    /**
     * @var 过滤设置是否反正，是则留住设置的，否则过滤设置的
     */
    private $filterReverse;

    /**
     * @var 填充设置数组
     */
    private $fillSetArr = array();



    function __construct($data)
    {
        $this->data = $data;
    }

    public function setSplitChar($char)
    {
        $this->splitChar = $char;
    }

    /**
     * 设置映射
     * @param array $mapArr 映射设置表数组
     * @return DataProcessUtils
     */
    public function setMap($mapArr): DataProcessUtils
    {
        if (empty($mapArr))
            return $this;
        $this->mapSetArr = $mapArr;

        $this->analyzeMap();
        return $this;

    }

    /**
     * 设置映射
     * @param array $fillArr 填充设置表数组
     * @return DataProcessUtils
     * @date:2019.05.14
     */
    public function setFill($fillArr): DataProcessUtils
    {
        if (empty($fillArr))
            return $this;
        $this->fillSetArr = $fillArr;

        $this->analyzeFill();
        return $this;

    }

    /**
     * 设置过滤设置
     * @param $filterArr
     * @param bool $filterReverse 反转如果为true的话，则只留下设置的
     * @return DataProcessUtils
     * @author: echo
     * @date: 2019.05.14
     */
    public function setFilter($filterArr, $filterReverse = false): DataProcessUtils
    {
        if (empty($filterArr))
            return $this;
        $this->filterSetArr = $filterArr;
        $this->filterReverse = $filterReverse;

        $this->analyzeFilter();
        return $this;

    }

    /**
     * 执行返回
     * @return array
     * @date: 2019.05.14
     */
    public function get()
    {
        if (empty($this->data)) {
            if (empty($this->fillSetArr))
                return $this->data;
            else {
                return $this->fillInfo(array(), $this->fillSetArr[0], '', 0);
            }
        }else if (is_object($this->data)) {
            $this->data = collect($this->data)->toArray();
        }

        return $this->toRun($this->data, 0, "");
    }

    /**
     * 分析映射转成内部映射格式，便于处理
     * 分层格式，
     *
     */
    private function analyzeMap()
    {
        $htMap = array();
        foreach ($this->mapSetArr as $key => $v) {

            $arr = explode($this->splitChar, $key);
            $lv = count($arr);
            --$lv;
            if (!isset($htMap[$lv])) {
                $htMap[$lv] = array();
            }
            //bf为之前字段，af为之后字段，pre为前缀限制要求
            $htMap[$lv][] = array('bf' => $arr[$lv], 'af' => $v, 'pre' => $this->getSetArrPre($arr));

        }
        $this->mapSetArr = $htMap;

    }

    /**
     * 分析过滤设置转成内部映射格式，便于处理
     * 分层格式
     */
    private function analyzeFilter()
    {

        $htMap = array();
        foreach ($this->filterSetArr as $key) {

            $arr = explode($this->splitChar, $key);
            $lv = count($arr);
            --$lv;
            $this->fillSet($htMap, $arr, $lv);
            if ($this->filterReverse == true) {
                while ($lv > 0) {
                    --$lv;
                    array_pop($arr);
                    $this->fillSet($htMap, $arr, $lv);
                }

            }

        }
        $this->filterSetArr = $htMap;
    }

    /**
     * 填充内部对应映射表
     * @param $htMap 映射表
     * @param $splitCharArr
     * @param $lv
     * @param $v
     */
    private function fillSet(&$htMap, $splitCharArr, $lv,$v = null)
    {

        if (!isset($htMap[$lv])) {
            //f为字段，pre为前缀限制要求
            $htMap[$lv] = array();
            $row = array('f' => $splitCharArr[$lv], 'pre' => $this->getSetArrPre($splitCharArr));
            if ($v != null)
                $row['v'] = $v;
            $htMap[$lv][] = $row;
        } else if (!$this->checkSetExist($htMap, $splitCharArr, $lv)) {
            $row = array('f' => $splitCharArr[$lv], 'pre' => $this->getSetArrPre($splitCharArr));
            if ($v != null)
                $row['v'] = $v;
            $htMap[$lv][] = $row;
        }

    }

    /**
     * 查询对应映射表是否已存在
     * @param $htMap
     * @param $splitCharArr
     * @param $lv
     * @return bool
     */
    private function checkSetExist($htMap, $splitCharArr, $lv)
    {
        $htArr = $htMap[$lv];
        foreach ($htArr as $sArr) {
            if ($sArr['f'] == $splitCharArr[$lv]) {
                return true;
            }
        }
        return false;
    }

    /**
     * 分析填充设置转成内部映射格式，便于处理
     * 分层格式
     */
    private function analyzeFill()
    {
        $htMap = array();
        foreach ($this->fillSetArr as $key => $v) {

            if (is_int($key)) {
                $key = $v;
                $v = self::FILL_SELF;
            }

            $arr = explode($this->splitChar, $key);
            $lv = count($arr);
            --$lv;
            $this->fillSet($htMap, $arr, $lv, $v);
            while ($lv > 0) {
                --$lv;
                array_pop($arr);
                $this->fillSet($htMap, $arr, $lv, self::FILL_SUB_PARENT);
            }


        }
        $this->fillSetArr = $htMap;
    }

    /**
     * 获取设置数组的前缀要求
     * @param $arr 映射表数组
     * @return string
     */
    private function getSetArrPre($arr)
    {

        $hasV = false;
        $pre = '';
        for ($i = 0, $j = count($arr) - 1; $i < $j; $i++) {
            $v = $arr[$i];
            if ($v != '') {
                $hasV = true;
                $pre .= $v;
            }
            $pre .= $this->splitChar;
        }
        if ($hasV === false)
            return '';//空为不限制前缀
        return $pre;
    }

    /**
     * 递归循环处理每一层
     * @param $data 待处理数组
     * @param $lv 当前层数
     * @param $pre 当前层的前缀
     * @return array
     */
    private function toRun($data, $lv, $pre)
    {

        $hasLvMap = isset($this->mapSetArr[$lv]) ? true : false;
        if ($hasLvMap)
            $mapSubs = $this->mapSetArr[$lv];

        $filterSubs = array();
        if (isset($this->filterSetArr[$lv]))
            $filterSubs = $this->filterSetArr[$lv];

        $hasLvFill = isset($this->fillSetArr[$lv]) ? true : false;
        if ($hasLvFill)
            $fillSubs = $this->fillSetArr[$lv];

        $newData = array();
        foreach ($data as $k => $v) {

            //过滤处理
            $hasMatch = $this->hasMatchField($k, $filterSubs, $pre);
            if ($this->filterReverse)
                $hasMatch = !$hasMatch;
            if ($hasMatch)
                continue;

            if (!$hasLvMap || is_int($k)) { //这层无匹配映射表或是数组（非键值）
                $nk = $k;
            } else {

                $nk = $this->findChangeField($k, $mapSubs, $pre);
                if ($nk === false) { //不在该层匹配内容中，则字段不需要变

                    $nk = $k;
                }
            }


            $cPre = $this->getNewPre($nk, $pre);//获取下一层的前缀
            $nv = $v;
            if (is_array($v)) {
                $nv = $this->toRun($v, $lv + 1, $cPre);
            }

            $newData[$nk] = $nv;
        }

        //填充处理
        if ($hasLvFill) {
            $newData = $this->fillInfo($newData, $fillSubs, $pre,$lv);
        }
        return $newData;
    }


    /**
     * 获取新层的匹配前缀
     * @param $k 当前键
     * @param $pre 当前层前缀
     * @return string
     */
    private function getNewPre($k, $pre)
    {
        $pre .= $k . $this->splitChar;
        return $pre;
    }


    /**
     * 查询字段在该层映射设置表对应的新字段
     * @param $k 被查询键
     * @param $maps 当前层的映射设置映射表
     * @param $pre 当前层前缀
     * @return bool
     */
    private function findChangeField($k, $maps, $pre)
    {
        $currPreArr = explode($this->splitChar, $pre);
        foreach ($maps as $map) {
            if ($k == $map['bf'] && $this->isMatchPre($currPreArr, $map['pre'])) {
                return $map['af'];
            }
        }
        return false;
    }

    /**
     * 查询字段在该层设置表是否有对应设置字段
     * @param $k 被查询键
     * @param $maps 当前层的设置映射表
     * @param $pre 当前层前缀
     * @return bool
     */
    private function hasMatchField($k, $maps, $pre)
    {
        if (!empty($maps)) {
            $currPreArr = explode($this->splitChar, $pre);
            foreach ($maps as $map) {
                if (($k === $map['f'] || $map['f'] == '') && $this->isMatchPre($currPreArr, $map['pre'])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 填充数据
     * @param $row
     * @param $maps
     * @param $pre
     * @return mixed
     */
    private function fillInfo($row, $maps, $pre, $lv)
    {
        $currPreArr = explode($this->splitChar, $pre);
        foreach ($maps as $map) {
            if ($this->isMatchPre($currPreArr, $map['pre'])) {
                $v = $map['v'];
                $f = $map['f'];
                if ($v == self::FILL_SUB_PARENT) {
                    if (empty($f))
                        continue;
                    if (isset($row[$f]))//说明已经填充过了
                        continue;
                    $row[$f] = $this->fillSubInfo(array(), $f, $lv + 1, $pre);
                    continue;
                }
                $v = $this->getFillValue($row, $f, $v);
                if ($v === self::TO_FILTER) {
                    unset($row[$f]);
                } else {
                    $row[$f] = $v;
                }
            }
        }
        return $row;
    }

    /**
     * 填充子类
     * @param $info 待填充的内容
     * @param $f 当前字段
     * @param $lv
     * @param $pre
     * @return mixed
     * @author: ehco
     * @date: 2019.05.22
     */
    private function fillSubInfo($info,$f,$lv,$pre)
    {

        $hasLvFill = isset($this->fillSetArr[$lv]) ? true : false;
        if ($hasLvFill) {
            $fillSubs = $this->fillSetArr[$lv];
            $cPre = $this->getNewPre($f, $pre);//获取下一层的前缀

            $info = $this->fillInfo($info, $fillSubs, $cPre, $lv);
        }
        return $info;
    }

    /**
     * 获取填充的值
     * @param $item 当前元素
     * @param $f 当前字段
     * @param $v 填充设置
     * @return mixed
     */
    private function getFillValue($item, $f, $v)
    {
        if ($v == self::FILL_SELF) { //填充自己，无则空

            return isset($item[$f]) ? $item[$f] : '';
        }

        if ($v instanceof Closure) {
            $callback = $v;
            return $callback($item);
        }

        $arr = explode('|', $v);
        if (count($arr) > 1) { //说明有设默认值
            $nf = $arr[0];
            if (!empty($nf)) { //说明是读取其他字段值
                $f = $nf;
            }
            $v = $arr[1];
            if($v===self::OPTION_FUNC) {
                $rv = isset($item[$f]) ? $item[$f] : null;
                return call_user_func($arr[2], $rv, isset($arr[3]) ? $arr[3] : null, $item, $f);
            }
            if($v===self::FILL_OMIT_EMPTY || $v===self::FILL_OMIT_UNSET) {

                if(!isset($item[$f]))
                    return self::TO_FILTER;//返回过滤

                if ($v===self::FILL_OMIT_EMPTY && (empty($item[$f]) && $item[$f] !== "0"))
                    return self::TO_FILTER;//返回过滤
            }

            return isset($item[$f]) ? $item[$f] : $this->getValue($v);
        }
        return $v;
    }


    /**
     * 检测匹配映射表的前缀是否符合当前层前缀
     * @param $currPreArr 分隔后的当前层前缀数组
     * @param $mapPre 映射表匹配的对应的前缀
     * @return bool
     */
    private function isMatchPre($currPreArr, $mapPre)
    {
        if ($mapPre == '')
            return true;

        //分隔作比较
        $mArr = explode($this->splitChar, $mapPre);

        $cNum = count($currPreArr);

        for ($i = 0; $i < $cNum; $i++) {

            if ($mArr[$i] != '' && $mArr[$i] != $currPreArr[$i])
                return false;
        }
        return true;
    }


    /**
     * 获取值
     * @param $v
     * @return bool|float|int
     * @author: echo
     * @date: 2019.05.21
     */
    private function getValue($v)
    {
        switch ($v) {
            case '':
                return '';
            case is_numeric($v):
                return floatval($v);
                break;
            case '0':
                return 0;
                break;
            case 'True':
                return true;
                break;
            case 'False':
                return false;
                break;
            case '[]':
                return array();
                break;
            case '{}':
                return new \stdClass();
                break;
            case 'null':
                return null;
                break;
        }
        return $v;
    }


}