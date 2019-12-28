<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;

class notices extends Model
{
    public $timestamps = true;
    protected $table = 'notices';
    protected $primaryKey = 'id';
    protected $guarded = [];


    public static function getNotices($user_id){
        try{
            $result = self::join('notice_relations','notices.id','notice_relations.notice_id')
                ->where('notice_relations.user_id',$user_id)
                ->select('notices.id as id','notices.title as title','notices.content as content','notices.created_at')
                ->paginate(10);
            return $result;
        }catch (\Exception $e){
            Logs::logError('获取通知失败！', [$e->getMessage()]);
            return false;
        }

    }
}
