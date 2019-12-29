<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Check\searchCheckRequest;
use App\Http\Requests\Admin\Check\setCheckRequest;
use App\Models\login_records;
use App\Models\punch_time_records;
use App\Models\punch_time_settings;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class CheckController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAllCheck(Request $request)
    {
        $puchtimerecords = punch_time_records::getPuchTime();
        $duty = login_records::getNewDuty();
        if (!$duty) {
            return response()->fail(100, '获取不到当前值班人员', null);
        } else {
            foreach ($puchtimerecords as $val) {
                $val['name'] = $duty->name;
                $val['phone'] = $duty->phone_munber;
            }
        }
        $paginator = self::getPaginator($request, $puchtimerecords->toArray(), env('PAGE_NUM'));
        return response()->success(200, '成功获取全部打卡信息', $paginator);
    }

    /**
     * @param setCheckRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function setCheck(setCheckRequest $request)
    {
        $b_time = $request['begin_time'];
        $e_time = $request['end_time'];
        $status = $request['status'];
        if ($status == 0) {
            $count = $request['count'];
            $count_array = self::getCountArray($b_time, $e_time, $count);
            $check = punch_time_settings::createPunchTimeSettings($count_array);
        } else if ($status == 1) {
            $check = punch_time_settings::createPunchTimeSettings($request['count_array']);
        } else {
            return response()->fail(100, '传入值不对', null);
        }
        return response()->success(200, '打卡设置成功', null);
    }

    /**
     * @param searchCheckRequest $request
     * @return mixed
     */
    public function searchCheck(searchCheckRequest $request)
    {
        $puchtimerecords = punch_time_records::getsearchPuchTime($request);
        $duty = login_records::getNewDuty();
        if (!$duty) {
            return response()->fail(100, '获取不到当前值班人员', null);
        } else {
            foreach ($puchtimerecords as $val) {
                $val['name'] = $duty->name;
                $val['phone'] = $duty->phone_munber;
            }
        }
        $paginator = self::getPaginator($request, $puchtimerecords->toArray(), env('PAGE_NUM'));
        return response()->success(200, '成功获取全部打卡信息', $paginator);
    }

    /**
     * @param $start
     * @param $end
     * @param $count
     * @return mixed
     */
    private function getCountArray($start, $end, $count)
    {
        $start = array_map('intval', explode(':', $start));
        $end = array_map('intval', explode(':', $end));
        $all_time = self::getAllTime($start, $end);
        $ave_time = intval($all_time / $count);
        $temp = $start;
        for ($i = 0; $i < $count; $i++) {
            $temp[1] += $ave_time;
            $temp[0] += intval($temp[1] / 60);
            $temp[1] %= 60;
            $count_array[$i] = self::getTimePattern($temp[0]) . ':' . self::getTimePattern($temp[1]) . ':00';
        }
        return $count_array;
    }

    /**
     * @param $start
     * @param $end
     * @return float|int
     */
    private function getAllTime($start, $end)
    {
        $time = 0;
        if ($start[1] > $end[1]) {
            $end[1] += 60;
            --$end[0];
            $time = $end[1] - $start[1];
        } else {
            $time = $end[1] - $start[1];
        }
        // dd($time);
        $time += ($end[0] - $start[0]) * 60;
        return $time;
    }

    /**
     * @param $time
     * @return string
     */
    private function getTimePattern($time)
    {
        return $time >= 10 ? $time : ('0' . $time);
    }

    /**
     * @param $request
     * @param $data
     * @param $perPage
     * @return LengthAwarePaginator
     */
    private function getPaginator($request, $data, $perPage)
    {
        if ($request->has('page')) {
            $current_page = $request->input('page');
            $current_page = $current_page <= 0 ? 1 : $current_page;
        } else {
            $current_page = 1;
        }

        $item = array_slice($data, ($current_page - 1) * $perPage, $perPage);
        $total = count($data);

        $paginator = new LengthAwarePaginator($item, $total, $perPage, $current_page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        return $paginator;
    }
}
