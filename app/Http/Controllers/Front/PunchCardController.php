<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\PuchCardRequest;
use App\Models\punch_time_records;
use App\Utils\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PunchCardController extends Controller
{
    //打卡
    public function puchCard(PuchCardRequest $request){
        try {
            $time = $request->time;
            $user_id = Auth::id();
            $result = punch_time_records::punchTimeRecord($user_id, $time);
            if ($result) {
                return response()->success(200, '打卡成功！', null);
            } else {
                return response()->fail(100, '打卡失败！', null);
            }
        }catch (\Exception $e){
            Logs::logError('代码存在错误！', [$e->getMessage()]);
            return response()->fail(100,'打卡失败！',null);
        }
    }
}
