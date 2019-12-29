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

    //登录记录

    /**
     * @param $name
     * @param $phone_num
     * @param $user_id
     * @return bool
     * @throws \Exception
     */
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
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany(users::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     * @throws \Exception
     */
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
                ->paginate(env('PAGE_NUM'));
            return $result;
        } catch (\Exception $e) {
            Logs::logError('获取所有单位失败!', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     * @throws \Exception
     */
    public static function getsearchUnits($request)
    {
        try {
            $sql = '(select b.user_id as id,b.name,b.phone_munber,b.department_name from (select l.*,users.department_name,users.id as userId from login_records as l inner join users on users.id = l.user_id where users.department_name like \'%' . $request . '%' . '\' order by l.created_at desc limit 99999) as b group by b.user_id order by b.created_at desc) one';
            $result = DB::table(DB::raw($sql))->paginate(env('PAGE_NUM'));
            return $result;
        } catch (\Exception $e) {
            Logs::logError('搜索单位失败!', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
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
