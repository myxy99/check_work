<?php

namespace App\Exports;

use App\Models\punch_time_records;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;

class TestExport implements FromArray
{
    private $startdate;
    private $enddate;
    public function __construct($startdate,$enddate)
    {
        $this->startdate = $startdate;
        $this->enddate = $enddate;
    }

    public function array(): array
    {
        $datas = punch_time_records::getexport($this->startdate,$this->enddate);
        foreach ($datas as $k => $data){
            $array[$k]['department']=$data['department'];
            if($data['allcard']==0){
                $array[$k]['allcard']='0';
            }else{
                $array[$k]['allcard']=$data['allcard'];
            }
            if($data['offline']==0){
                $array[$k]['offline']='0';
            }else{
                $array[$k]['offline']=$data['offline'];
            }
            if($data['attendance']==0){
                $array[$k]['attendance']='0%';
            }else{
                $array[$k]['attendance']=$data['attendance'];
            }
            $array[$k]['phone_munber']=$data['phone_munber'];
        }
        $data = [['单位','总打卡次数','离岗次数','出勤率'],$array];//测试数据
        return $data;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        // TODO: Implement collection() method.
    }
}
