<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;

class attachments extends Model
{
    public $timestamps = true;
    protected $table = 'attachments';
    protected $primaryKey = 'id';
    protected $guarded = [];


    //上传文件存入数据库
    public static function uploadsFile($user_id,$file_path){
        try {
            $result = users::getLoginNameInfo($user_id);
            $attFile = new attachments();
            $attFile->file_path = $file_path;
            $attFile->user_id = $user_id;
            $attFile->update_user_name = $result->user_name;
            $result = $attFile->save();
            return $result;
        }catch (\Exception $e){
            Logs::logError('插入数据库失败！',[$e->getMessage()]);
            return false;
        }
    }
    //删除附件数据库记录
    public static function delectFileMsg($file_id){
        try {
            $result = self::destroy($file_id);
            return $result;
        }catch (\Exception $e){
            Logs::logError("删除失败！",[$e->getMessage()]);
            return false;
        }
    }
}
