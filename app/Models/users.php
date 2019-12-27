<?php

namespace App\Models;


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
}
