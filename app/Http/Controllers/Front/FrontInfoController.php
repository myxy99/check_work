<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\notices;
use App\Utils\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontInfoController extends Controller
{
    //获取前台通知信息
    public function getAllInfo(){
        try{
            $user_id = Auth::id();
            $result = notices::getNotices($user_id);
            if($result){
                if($result->isEmpty()){
                    return response()->success(200, '目前无任何通知！',null);
                }
                else{
                    return response()->success(200, '查询成功',$result);
                }
            }else{
                return response()->fail(100,'查询失败!',null);
            }
        }
        catch (\Exception $e){
            Logs::logError('获取通知失败！',[$e->getMessage()]);
            return response()->fail(100,'获取通知失败!',null);
        }

    }

}
