<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class login_records extends Model
{
    public $timestamps = true;
    protected $table = 'login_records';
    protected $primaryKey = 'id';
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany(users::class, 'id', 'user_id');
    }
    public static function getAllUnits()
    {
        try {
            DB::enableQueryLog();
            $sql = login_records::query()->from('login_records')
                ->join('users', 'users.id', 'login_records.user_id')
                ->orderByDesc('login_records.created_at')
                ->limit(99999)
                ->select(['login_records.*', 'users.department_name', 'users.id as userId'])
                ->get();
            $resql = DB::getQueryLog();
            $result = login_records::query()
                ->from(DB::raw("({$resql[0]["query"]}) as s"))
                ->orderByDesc('s.created_at')
                ->groupBy('s.user_id')
                ->paginate();
            return $result;
        } catch (\Exception $e) {
            Logs::logError('获取所有单位失败!', [$e->getMessage()]);
            return null;
        }
    }

    public static function getsearchUnits($request)
    {
        try {
            $sql = '(select b.user_id as id,b.name,b.phone_munber,b.department_name from (select l.*,users.department_name,users.id as userId from login_records as l inner join users on users.id = l.user_id where users.department_name like \'%' . $request . '%' . '\' order by l.created_at desc limit 99999) as b group by b.user_id order by b.created_at desc) one';
            $result = DB::table(DB::raw($sql))->paginate(10);
            return $result;
        } catch (\Exception $e) {
            Logs::logError('搜索单位失败!', [$e->getMessage()]);
            return null;
        }
    }

    public static function getNewDuty()
    {
        try {
            $result = self::orderByDesc('created_at')->select('name', 'phone_munber')->first();
            return $result !== null ? $result : false;
        } catch (\Exception $e) {
            Logs::logError('获取值班人员失败!', [$e->getMessage()]);
            return false;
        }
    }
}
