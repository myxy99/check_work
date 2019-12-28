<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;

class punch_time_records extends Model
{
    public $timestamps = true;
    protected $table = 'punch_time_records';
    protected $primaryKey = 'id';
    protected $guarded = [];

    //打卡信息记录
    public static function punchTimeRecord($user_id,$req_time){
        try{
            $name = users::getLoginNameInfo($user_id)->duty_name;
            $required_time = date("Y-m-d ").$req_time;
            $actual_time = date("Y-m-d H:i:s");
            $punch_time_record = new punch_time_records();
            $punch_time_record->name = $name;
            $punch_time_record->user_id = $user_id;
            $punch_time_record->required_time = $required_time;
            $punch_time_record->actual_time = $actual_time;
            $punch_time_record->created_at = $actual_time;
            $result = $punch_time_record->save();
            return $result;
        }catch (\Exception $e){
            Logs::logError('打卡信息插入数据库失败！', [$e->getMessage()]);
            return false;
        }

    }
}
