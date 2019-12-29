<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class notices extends Model
{
    public $timestamps = true;
    protected $table = 'notices';
    protected $primaryKey = 'id';
    protected $guarded = [];

    //获取通知

    /**
     * @param $user_id
     * @return bool
     * @throws \Exception
     */
    public static function getNotices($user_id)
    {
        try {
            $result = self::join('notice_relations', 'notices.id', 'notice_relations.notice_id')
                ->where('notice_relations.user_id', $user_id)
                ->select('notices.id as id', 'notices.title as title', 'notices.content as content', 'notices.created_at')
                ->paginate(env('PAGE_NUM'));
            return $result;
        } catch (\Exception $e) {
            Logs::logError('获取通知失败！', [$e->getMessage()]);
            return false;
        }
    }

    //显示所有已发公告

    /**
     * @return |null
     * @throws \Exception
     */
    public static function getAllSendNotices()
    {
        try {
            $res = self::select('title', 'content', 'created_at')
                ->orderby('updated_at', 'DESC')
                ->paginate(env('PAGE_NUM'))
                ->toarray();
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('查询失败!', [$e->getMessage()]);
            return null;
        }
    }

    //搜索框搜索

    /**
     * @param $text
     * @return |null
     * @throws \Exception
     */
    public static function searchNotice($text)
    {
        try {
            $res = self::select('id', 'title', 'content', 'created_at')
                ->where('content', 'like', '%' . $text . '%')
                ->orwhere('title', 'like', '%' . $text . '%')
                ->orderby('updated_at', 'DESC')
                ->paginate(env('PAGE_NUM'))
                ->toarray();
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('搜索失败!', [$e->getMessage()]);
            return null;
        }
    }

    //新增通知

    /**
     * @param $title
     * @param $id
     * @param $content
     * @return int
     * @throws \Exception
     */
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

    /**
     * @param array $array
     * @return bool
     * @throws \Exception
     */
    public static function createNotices($array = [])
    {
        try {
            $Notices = self::create($array);
            return $Notices ? $Notices->id : false;
        } catch (\Exception $e) {
            Logs::logError('通知内容表添加失败!', [$e->getMessage()]);
            return false;
        }
    }
}
