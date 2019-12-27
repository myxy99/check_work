<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;

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

}
