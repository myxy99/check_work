<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Utils\Logs;

class users extends \Illuminate\Foundation\Auth\User implements JWTSubject, Authenticatable
{
    public $timestamps = true;
    protected $rememberTokenName = NULL;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [
        'passwd',
    ];

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->passwd;
    }

    //获取登录信息
    public static function getLoginNameInfo($user_id){
        try {
            $result = self::join('login_records', 'users.id', 'login_records.user_id')
                ->where('login_records.user_id', $user_id)
                ->select(
                    'users.user_name',
                    'users.department_name',
                    'login_records.name as duty_name',
                    'login_records.phone_munber as duty_phone',
                    'login_records.created_at as created_at'
                )
                ->latest()
                ->first();
            return $result;
        }catch (\Exception $e) {
            Logs::logError('获取个人信息失败！', [$e->getMessage()]);
            return false;
        }
    }
    /**
     * @param $id
     * @return |null
     * @throws \Exception
     */
    public static function getUserName($id)
    {
        try {
            return self::where('id', $id)
                ->select('id', 'user_name')
                ->first();
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('用户名获取失败!', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * @param $id
     * @param $pwd
     * @return bool
     * @throws \Exception
     */
    public static function updatePassword($id, $pwd)
    {
        try {
            $res = self::find($id);
            $res->passwd = bcrypt($pwd);
            $res->save();
            return $res ? true : false;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('密码修改失败!', [$e->getMessage()]);
            return false;
        }
    }


    public static function createUnit($array = [])
    {
        try {
            $id = self::create($array);
            return $id ? true : false;
        } catch (\Excption $e) {
            Logs::logError('添加单位失败!', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * @return int
     * @throws \Exception
     */
    public static function showSendObj()
    {
        try {
            $res = self::select('id', 'department_name')
                ->paginate(env('PAGE_NUM'))
                ->toarray();
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('查询失败!', [$e->getMessage()]);
            return 0;
        }
    }

    /**
     * 修改密码
     * @param $updatePW
     * @return bool
     * @throws \Exception
     */
    public static function updatePW($updatePW)
    {
        try {
            return self::where('id', auth()->id())->update([
                'passwd' => bcrypt($updatePW)
            ]) ? true : false;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('用户修改密码失败！', [$e->getMessage()]);
            return false;
        }
    }

    public static function updateUnit($array = [], $id)
    {
        try {
            $users = self::find($id);
            $users->department_name = $array['department_name'];
            if (isset($array['passwd'])) $users->passwd = $array['passwd'];
            $result = $users->save();
            return $result ? true : false;
        } catch (\Excption $e) {
            Logs::logError('更新单位失败!', [$e->getMessage()]);
            return false;
        }
    }
}
