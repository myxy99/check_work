<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class chat_records extends Model
{
    public $timestamps = true;
    protected $table = 'chat_records';
    protected $primaryKey = 'id';
    protected $guarded = [];

    //获取所有消息
    public static function getAllChatMsg($user_id){
        try {
            $result = self::join('users', 'chat_records.from_user_id', 'users.id')
                ->where('chat_records.to_user_id', $user_id)
                ->groupBy('chat_records.from_user_name')
                ->orderBy('chat_records.created_at', 'desc')
                ->select('chat_records.id',
                    'chat_records.from_user_id',
                    'chat_records.from_user_name',
                    'attachment_id as readstatus',
                    'users.department_name',
                    'chat_records.created_at as replaytime')
                ->paginate();
            return $result;
        }catch (\Exception $e){
            Logs::logError('获取失败！',[$e->getMessage()]);
            return false;
        }
    }

    //获取聊天记录
    public static function getMsgRecord($fromUser_id,$touser_id){
        try {
            $result = self::leftjoin('attachments', 'chat_records.attachment_id', 'attachments.id')
                ->orWhere('chat_records.from_user_id', $fromUser_id)
                ->Where('chat_records.to_user_id', $touser_id)
                ->orWhere('chat_records.from_user_id', $touser_id)
                ->Where('chat_records.to_user_id', $fromUser_id)
                ->select('chat_records.id', 'from_user_id',
                    'chat_records.to_user_id',
                    'chat_records.from_user_name',
                    'chat_records.to_user_name',
                    'chat_records.content',
                    'attachments.file_path as atch_addr',
                    'chat_records.created_at as replaytime')
                ->paginate(10);
            return $result;
        }catch (\Exception $e){
            Logs::logError("获取记录失败！",[$e->getMessage()]);
            return false;
        }

    }

    //删除消息
    public static function deleteChatMsg($fromUser_id,$touser_id){
        try {
            $result = self::leftjoin('attachments', 'chat_records.attachment_id', 'attachments.id')
                ->orWhere('chat_records.from_user_id', $fromUser_id)
                ->Where('chat_records.to_user_id', $touser_id)
                ->orWhere('chat_records.from_user_id', $touser_id)
                ->Where('chat_records.to_user_id', $fromUser_id)
                ->delete();
            return $result;
        }catch (\Exception $e){
            Logs::logError('删除失败！',[$e->getMessage()]);
            return false;
        }

    }

    //搜索消息
    public static function searchMsg($keywords,$user_id){
        try {
            $result = self::join('users', 'chat_records.from_user_id', 'users.id')
                ->where('chat_records.to_user_id', $user_id)
                ->Where('chat_records.from_user_name', 'like', '%' . $keywords . '%')
                ->orWhere('users.department_name', 'like', '%' . $keywords . '%')
                ->groupBy('chat_records.from_user_name')
                ->orderBy('chat_records.created_at', 'desc')
                ->select('chat_records.id',
                    'chat_records.from_user_id',
                    'chat_records.from_user_name',
                    'attachment_id as readstatus',
                    'users.department_name',
                    'chat_records.created_at as replaytime')
                ->paginate(10);
            return $result;
        }catch (\Exception $e){
            Logs::logError('搜索失败！',[$e->getMessage()]);
            return false;
        }
    }
}
