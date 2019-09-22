<?php
/**
 * Created by PhpStorm.
 * User: 13018
 * Date: 2019/7/15
 * Time: 16:22
 */

namespace App\Imports;


use Maatwebsite\Excel\Facades\Excel;

class CompanyUserImport
{
    private $file;
    public function __construct($file_path)
    {
        $this->file = Excel::selectSheetsByIndex(0)->load($file_path,'UTF-8');
    }

    /**
     * Created by PhpStorm.
     * User: curry
     * 方法名 get_data
     * @return mixed
     * Date: 2019/7/15 16:28
     */
    public function get_data(){
        return $this->file->get(array('姓名', '手机号'))->toArray();
    }
}