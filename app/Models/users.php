<?php

namespace App\Models;
use App\Utils\Logs;
use Exception;
use DB;

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



    //通知界面显示发送对象
    public static function showSendObj(){
        try{
            $res = self::select('id','department_name')
                ->paginate(5)
                ->toarray();
            return $res;
        }catch (\Exception $e){
            \App\Utils\Logs::logError('查询失败!', [$e->getMessage()]);
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
