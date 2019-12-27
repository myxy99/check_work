<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddNoticeRequest;
use App\Models\notices;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class NoticeController extends Controller
{
    //显示所有已发公告
    public function getAllSendNotices(){
        $res = notices::getAllSendNotices();
        return $res != 0 ?
            response()->success(200, '获取成功!', $res) :
            response()->fail(100, '获取失败!',null);
    }

    //搜索框搜索
    public function searchNotice(Request $request){
        $text = $request->text;
        $res = notices::searchNotice($text);
        return $res != null ?
            response()->success(200, '获取成功!', $res) :
            response()->fail(100, '未查询到数据!',null);
    }

    //通知界面显示发送对象
    public function showSendObj(){
        $res = users::showSendObj();
        return $res != 0 ?
            response()->success(200, '获取成功!', $res) :
            response()->fail(100, '获取失败!',null);
    }

    //新增通知
    public function addNotice(AddNoticeRequest $request){
        $title = $request->title;
        $id = $request->id;
        $content = $request->content;
        $res = notices::addNotice($title,$id,$content);
        return $res != 0 ?
            response()->success(200, '新增成功!', null) :
            response()->fail(100, '新增失败!',null);
    }
}
