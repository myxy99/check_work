<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;

class punch_time_settings extends Model
{
    public $timestamps = true;
    protected $table = 'punch_time_settings';
    protected $primaryKey = 'id';
    protected $guarded = [];

    /**
     * @param array $array
     * @return bool
     * @throws \Exception
     */
    public static function createPunchTimeSettings($array = [])
    {
        try {
            self::where('unable_at', null)
                ->update(['unable_at' => date('Y-m-d H:i:s')]);
            foreach ($array as $value) {
                $create = self::create([
                    'clock_time' => $value,
                ]);
                if (!$create) return false;
            }
            return true;
        } catch (\Exception $e) {
            Logs::logError('创建打卡记录表!', [$e->getMessage()]);
            return false;
        }
    }
}
