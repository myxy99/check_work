<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class punch_time_settings extends Model
{
    public $timestamps = true;
    protected $table = 'punch_time_settings';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public static function createPunchTimeSettings($array = [])
    {
        try {
            $result = punch_time_settings::where('unable_at', null)
                ->update(['unable_at' => date('Y-m-d H:i:s')]);
            foreach ($array as $value) {
                $create = self::create([
                    'clock_time' => $value,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                if (!$create) return false;
            }
            return true;
        } catch (\Excption $e) {
            Logs::logError('创建打卡记录表!', [$e->getMessage()]);
            return false;
        }
    }
}
