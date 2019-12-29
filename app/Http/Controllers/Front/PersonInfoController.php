<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\users;
use App\Utils\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonInfoController extends Controller
{
    //个人信息获取
    /**
     * @return mixed
     * @throws \Exception
     */
    public function getPersonInfo(){
        try {
            $user_id = Auth::id();
            $result = users::getLoginNameInfo($user_id);
            if ($result) {
                return response()->success(200, '查询成功！', $result);
            } else {
                return response()->fail(200, '查询个人信息为空！', null);
            }
        }catch (\Exception $e){
            Logs::logError('获取个人信息失败！',[$e->getMessage()]);
            return response()->fail(100,'获取个人消息失败!',null);
        }
    }


}
