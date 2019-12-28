<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\users;

class PersonalCenterController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getUserName(Request $request)
    {
        $info = users::getUserName($request->id);
        return $info ?
            response()->success(200, '成功', $info) :
            response()->fail(100, '失败');

    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function updatePassword(Request $request)
    {
        return users::updatePassword($request->id, $request->pwd) ?
            response()->success(200, '保存成功!') :
            response()->fail(100, '保存失败!');
    }
}
