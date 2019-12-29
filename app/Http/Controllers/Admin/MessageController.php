<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DelMsgRequest;
use App\Http\Requests\Admin\SearchMsgRequest;
use App\Http\Requests\CancelFileRequest;
use App\Http\Requests\UploadFileRequest;
use App\Models\attachments;
use App\Models\chat_records;
use App\Utils\Logs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    //获取所有信息
    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAllMsg()
    {
        try {
            $user_id = auth()->id();
            $result = chat_records::getAllChatMsg($user_id);
            if ($result) {
                if ($result->isEmpty()) {
                    return response()->success(200, '目前没有任何消息！', null);
                } else {
                    return response()->success(200, '获取成功！', $result);
                }
            } else {
                return response()->fail(100, '获取所有消息失败！', null);
            }
        } catch (\Exception $e) {
            Logs::logError('获取所有消息失败！', [$e->getMessage()]);
            return response()->fail(100, '获取所有消息失败!', null);
        }
    }

    //搜索消息

    /**
     * @param SearchMsgRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function searchMsg(SearchMsgRequest $request)
    {
        try {
            $user_id = auth()->id();
            $keywords = $request->keywords;
            $result = chat_records::searchMsg($keywords, $user_id);
            if ($result) {
                if ($result->isEmpty()) {
                    return response()->success(200, '无任何消息！', null);
                } else {
                    return response()->success(200, '搜索成功！', $result);
                }
            } else {
                return response()->fail(100, '搜索失败！', null);
            }
        } catch (\Exception $e) {
            Logs::logError('搜索失败！', [$e->getMessage()]);
            return fail(100, '搜索失败！', null);
        }


    }

    //删除信息

    /**
     * @param DelMsgRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function delMsg(DelMsgRequest $request)
    {
        try {
            $touser_id = Auth::id();
            $fromuser_id = $request->fromuser_id;
            $result = chat_records::deleteChatMsg($fromuser_id, $touser_id);
            if ($result) {
                return response()->success(200, '删除成功！', null);
            } else {
                return response()->fail(100, '删除失败！', null);
            }
        } catch (\Exception $e) {
            Logs::logError('删除失败！', [$e->getMessage()]);
            return response()->fail(100, '删除失败！', null);
        }

    }

    //获取记录信息

    /**
     * @param DelMsgRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function msgRecord(DelMsgRequest $request)
    {
        try {
            $fromuser_id = $request->fromuser_id;
            $touser_id = auth()->id();
            $result = chat_records::getMsgRecord($fromuser_id, $touser_id);
            if ($result) {
                if ($result->isEmpty()) {
                    return response()->success(200, '无任何消息记录！', null);
                }
                return response()->success(200, '获取成功！', $result);
            } else {
                return response()->fail(100, '获取失败！', null);
            }
        } catch (\Exception $e) {
            Logs::logError("获取失败！", [$e->getMessage()]);
            return response()->fail(100, '获取失败！', null);
        }
    }

    //上传文件

    /**
     * @param UploadFileRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function uploadFile(UploadFileRequest $request)
    {
        try {
            $user_id = Auth::id();
            $file = $request->file('attachments');
            if ($file->isValid()) {
                //获取扩展名
                $extend = $file->getClientOriginalExtension();
                //获取文件路径
                $path = $file->getRealPath();
                //取名
                $name = uniqid() . time() . '.' . $extend;
                $file_path = "/uploads/" . $name;
                DB::beginTransaction();
                $result = attachments::uploadsFile($user_id, $file_path);
                $stat = Storage::disk('admin')->put($name, file_get_contents($path));
                if ($result && $stat) {
                    DB::commit();
                    $id = attachments::where('file_path', $file_path)->select('id')->first();
                    $data['attachments_id'] = $id->id;
                    $data['path'] = $file_path;
                    return response()->success(200, '上传成功！', $data);
                } else {
                    DB::rollBack();
                    return response()->fail(100, '上传失败，请重试！', null);
                }
            }
            return response()->fail(100, '上传失败，请重试！', null);
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::logError('上传文件失败！', [$e->getMessage()]);
            return response()->fail(100, '上传文件失败！', null);
        }
    }

    //取消文件上传

    /**
     * @param CancelFileRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function cancelFile(CancelFileRequest $request)
    {
        try {
            $file_id = $request->file_id;
            $result = attachments::delectFileMsg($file_id);
            if ($result) {
                return response()->success(200, '取消上传成功！', null);
            } else {
                return response()->fail(100, '取消上传失败！', null);
            }
        } catch (\Exception $e) {
            Logs::logError("取消上传失败！", [$e->getMessage()]);
            return response()->fail(100, '取消上传失败！', null);
        }

    }
}
