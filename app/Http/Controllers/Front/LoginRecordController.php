<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\LoginRecordRequest;
use App\Models\login_records;
use App\Utils\Logs;
use Illuminate\Support\Facades\Auth;

class LoginRecordController extends Controller
{
    /**
     * @param LoginRecordRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function loginRecord(LoginRecordRequest $request)
    {
        try {
            $user_id = Auth::id();
            $name = $request->name;
            $phone_number = $request->phone_number;
            $result = login_records::loginRecord($name, $phone_number, $user_id);
            if ($result) {
                return response()->success(200, "登录记录成功！", null);
            } else {
                return response()->fail(100, '登录记录失败！', null);
            }
        }catch (\Exception $e){
            Logs::logError('登录记录失败',[$e->getMessage()]);
            return response()->fail(100,'登录记录失败！',null);
        }
    }
}
