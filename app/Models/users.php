<?php

namespace App\Models;
use App\Utils\Logs;
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
        }catch (\Exception $e){
            Logs::logError('获取个人信息失败！', [$e->getMessage()]);
           return false;
        }
    }
}
