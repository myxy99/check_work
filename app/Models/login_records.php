<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;

class login_records extends Model
{
    public $timestamps = true;
    protected $table = 'login_records';
    protected $primaryKey = 'id';
    protected $guarded = [];


    //登录记录
    public static function loginRecord($name,$phone_num,$user_id){
        try {
            $login_record = new login_records();
            $login_record->name = $name;
            $login_record->phone_munber = $phone_num;
            $login_record->user_id = $user_id;
            $result = $login_record->save();
            return $result;
        }catch (\Exception $e){
            Logs::logError('登录记录失败！',[$e->getMessage()]);
            return false;
        }
    }
}
