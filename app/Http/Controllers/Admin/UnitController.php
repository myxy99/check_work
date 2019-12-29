<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Unit\addNotificationRequest;
use App\Http\Requests\Admin\Unit\addUnitRequest;
use App\Http\Requests\Admin\Unit\searchRequest;
use App\Http\Requests\Admin\Unit\updateUnitRequest;
use App\Models\login_records;
use App\Models\notice_relations;
use App\Models\notices;
use App\Models\users;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAllUnit()
    {
        $unitInfo = login_records::getAllUnits();
        return $unitInfo !== null ?
            response()->success(200, '获取全部信息成功', $unitInfo) :
            response()->fail(100, '获取全部信息失败', null);
    }

    /**
     * @param addUnitRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function addUnit(addUnitRequest $request)
    {
        return users::createUnit(self::getRequest($request)) ?
            response()->success(200, '添加单位成功', null) :
            response()->fail(100, '添加单位失败', null);
    }

    /**
     * @param updateUnitRequest $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function updateUnit(updateUnitRequest $request, $id)
    {
        return users::updateUnit(self::getRequest($request), $id) ?
            response()->success(200, '更新单位成功', null) :
            response()->fail(100, '更新单位失败', null);
    }

    /**
     * @param addNotificationRequest $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function addNotification(addNotificationRequest $request, $id)
    {
        $notices_id = notices::createNotices(self::modifyDate($request)->toArray());
        if (!$notices_id) return response()->fail(100, '添加内容表失败!', null);
        return notice_relations::createNotiRela(self::getNotiRela($id, $notices_id)) ?
            response()->success(200, '添加通知成功', null) :
            response()->fail(100, '添加通知失败', null);
    }

    /**
     * @param searchRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function searchUnit(searchRequest $request)
    {
        $unitInfo = login_records::getsearchUnits($request->department_name);
        return $unitInfo !== null ?
            response()->success(200, '获取全部信息成功', $unitInfo) :
            response()->fail(100, '获取全部信息失败', null);
    }

    /**
     * @param $request
     * @return mixed
     */
    private function getRequest($request)
    {
        if ($request['passwd']) $request['passwd'] = bcrypt($request['passwd']);
        return $request->toArray();
    }

    /**
     * @param $id
     * @param $notices_id
     * @return mixed
     */
    private function getNotiRela($id, $notices_id)
    {
        $request['user_id'] = $id;
        $request['notice_id'] = $notices_id;
        return $request;
    }
}
