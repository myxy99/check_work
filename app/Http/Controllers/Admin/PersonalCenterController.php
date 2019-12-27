<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\users;
use DB;

class PersonalCenterController extends Controller
{

/**
 * 用户信息回显
 * @author Varsion
 * JianhuaL
 * @param  Request $request 获取所需要的信息
 * @return JSON             回显信息
 */
    public function getUserName(Request $request)
    {

        $id = $request -> id;
        $info = users::getUserName($id);
       /*$info = DB::table('users')
                ->select('id','user_name')
                ->where('id',$id)
                ->get();*/
       if ($info) {
           return response()->success(200,'成功',$info);
       } else {
           return response()->fail(100,'失败',null);
       }

    }

/**
 * 修改密码
 * @author Varsion
 * JianhuaL
 * @param  Request $request 获取需要的参数
 * @return JSON
 */
    public function updatePassword(Request $request)
    {

        $id = $request -> id;
        $pwd = $request -> pwd;
        $res = users::updatePassword($id,$pwd);
        if ($res) {
            return response()->success(200,'保存成功!',null);
        } else {
           return response()->fail(100,'保存失败!',null);
        }

    }
}
