<?php

namespace App\Models;
use App\Utils\Logs;
use Exception;
use DB;


class users extends \Illuminate\Foundation\Auth\User implements \Illuminate\Contracts\Auth\Authenticatable
{
    public $timestamps = true;
    protected $rememberTokenName = NULL;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [
        'passwd',
    ];

    public function getAuthPassword()
    {
        return $this->passwd;
    }

    /**
     * 获取该用户 用户名
     * @author Varsion
     * JianhuaL
     * @param  int  $id    用户id
     * @return json $info  该用户用户名
     */
    public static function getUserName($id)
    {
        try {
        $info = self::where('id',$id)
                    ->select('id','user_name')
                    ->first();
                    return $info;
        } catch (Exception $e) {
            Logs::logError('用户名获取失败!', [$e->getMessage()]);
            return null;
            }
    }

    public static function updatePassword($id,$pwd)
    {
        try {

        $res = self::find($id);
        $res->passwd = bcrypt($pwd);
        $res->save();

                    if ( $res ) {
                        return true;
                    } else {
                        return false;
                    }
                    return $res;
        } catch (Exception $e) {
            Logs::logError('密码修改失败!', [$e->getMessage()]);
            return false;
        }
    }


}
