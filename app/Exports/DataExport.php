<?php
/**
 * Created by PhpStorm.
 * User: 13018
 * Date: 2019/7/9
 * Time: 20:09
 */

namespace App\Exports;

use Maatwebsite\Excel\Facades\Excel;

class DataExport
{
    public function test()
    {
        $cellData = [
            [' 学号 ',' 姓名 ',' 成绩 '],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
        Excel::create (time(),function ($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->store('xls');

    }

}