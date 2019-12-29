<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;

class notice_relations extends Model
{
    public $timestamps = true;
    protected $table = 'notice_relations';
    protected $primaryKey = 'id';
    protected $guarded = [];

    /**
     * @param array $array
     * @return bool
     * @throws \Exception
     */
    public static function createNotiRela($array = [])
    {
        try {
            return self::create($array) ? true : false;
        } catch (\Exception $e) {
            Logs::logError('通知关系表添加失败!', [$e->getMessage()]);
            return false;
        }
    }
}
