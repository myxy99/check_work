<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;


class notices extends Model
{
    public $timestamps = true;
    protected $table = 'notices';
    protected $primaryKey = 'id';
    protected $guarded = [];


    //显示所有已发公告
    public static function getAllSendNotices()
    {
        try {
            $res = self::select('title', 'content', 'created_at')
                ->orderby('updated_at', 'DESC')
                ->paginate(5)
                ->toarray();
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('查询失败!', [$e->getMessage()]);
            return null;
        }
    }

    //搜索框搜索
    public static function searchNotice($text)
    {
        try {
            $res = self::select('id', 'title', 'content', 'created_at')
                ->where('content', 'like', '%' . $text . '%')
                ->orwhere('title', 'like', '%' . $text . '%')
                ->orderby('updated_at', 'DESC')
                ->paginate(5)
                ->toarray();
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('搜索失败!', [$e->getMessage()]);
            return null;
        }
    }

    //新增通知
    public static function addNotice($title, $id, $content)
    {
        try {
            DB::beginTransaction();
            $notice_id = DB::table('notices')->insertGetId([
                'title' => $title,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            for ($i = 0; $i < count($id); $i++) {
                $res1 = DB::table('notice_relations')->insert([
                    'notice_id' => $notice_id,
                    'user_id' => $id[$i],
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
                if ($res1 != 1) {
                    break;
                }
            }
            if ($notice_id && $res1) {
                DB::commit();
                return 1;
            } else {
                DB::rollback();
                return 0;
            }
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('新增失败!', [$e->getMessage()]);
            DB::rollback();
            return 0;
        }
    }
    public static function createNotices($array = [])
    {
        try {
            $Notices = self::create($array);
            return $Notices ? $Notices->id : flase;
        } catch (\Excption $e) {
            Logs::logError('通知内容表添加失败!', [$e->getMessage()]);
            return false;
        }
    }
}
