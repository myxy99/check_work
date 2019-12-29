<?php

namespace App\Models;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class punch_time_records extends Model
{
    public $timestamps = true;
    protected $table = 'punch_time_records';
    protected $primaryKey = 'id';
    protected $guarded = [];

    //打卡信息记录

    /**
     * @param $user_id
     * @param $req_time
     * @return bool
     * @throws \Exception
     */
    public static function punchTimeRecord($user_id,$req_time)
    {
        try {
            $name = users::getLoginNameInfo($user_id)->duty_name;
            $required_time = date("Y-m-d ") . $req_time;
            $actual_time = date("Y-m-d H:i:s");
            $punch_time_record = new punch_time_records();
            $punch_time_record->name = $name;
            $punch_time_record->user_id = $user_id;
            $punch_time_record->required_time = $required_time;
            $punch_time_record->actual_time = $actual_time;
            $punch_time_record->created_at = $actual_time;
            $result = $punch_time_record->save();
            return $result;
        } catch (\Exception $e) {
            Logs::logError('打卡信息插入数据库失败！', [$e->getMessage()]);
            return false;
        }
    }

    //获取全部

    /**
     * @return |null
     * @throws \Exception
     */
    public static function getall()
    {
        try {
            $statisticss = users::select('department_name')->distinct()->paginate(env('PAGE_NUM')); //有哪些项目
            $departments = self::with('user')->get(); //所有得打卡数据
            $phones = login_records::with('user')->orderBy('updated_at', 'desc')->get(); //所有得电话号码
            foreach ($statisticss as $k => $statistics) {
                $statisticss[$k]['phone_munber'] = null;
                foreach ($phones as $i => $phone) {
                    if ($phone['user'][0]['department_name'] == $statistics['department_name']) {
                        $statisticss[$k]['phone_munber'] = $phone['phone_munber'];
                        break;
                    }
                }
                $statisticss[$k]['department'] = $statistics['department_name'];
                $statisticss[$k]['allcard'] = 0;
                $statisticss[$k]['offline'] = 0;
                foreach ($departments as $department) {
                    if ($department['user'][0]['department_name'] == $statistics['department_name']) {
                        $statisticss[$k]['allcard'] = $statisticss[$k]['allcard'] + 1;
                        if ($department['actual_time'] == null) {
                            $statisticss[$k]['offline'] = $statisticss[$k]['offline'] + 1;
                        }
                    }
                }
                if ($statisticss[$k]['allcard'] == 0) {
                    $statisticss[$k]['attendance'] = 0;
                } else {
                    $statisticss[$k]['attendance'] = number_format((($statisticss[$k]['allcard'] - $statisticss[$k]['offline']) * 100 / $statisticss[$k]['allcard']), 2) . '%';
                }
            }
            return $statisticss;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('用户名获取失败!', [$e->getMessage()]);
            return null;
        }
    }

    //查询

    /**
     * @param $department
     * @return |null
     * @throws \Exception
     */
    public static function getSearch($department)
    {
        try {
            $statisticss = users::select('department_name')->where('department_name', 'like', '%' . $department . '%')->distinct()->paginate(env('PAGE_NUM')); //有哪些项目
            $departments = self::with('user')->get(); //所有得打卡数据
            $phones = login_records::with('user')->orderBy('updated_at', 'desc')->get(); //所有得电话号码
            foreach ($statisticss as $k => $statistics) {
                $statisticss[$k]['phone_munber'] = null;
                foreach ($phones as $i => $phone) {
                    if ($phone['user'][0]['department_name'] == $statistics['department_name']) {
                        $statisticss[$k]['phone_munber'] = $phone['phone_munber'];
                        break;
                    }
                }
                $statisticss[$k]['department'] = $statistics['department_name'];
                $statisticss[$k]['allcard'] = 0;
                $statisticss[$k]['offline'] = 0;
                foreach ($departments as $department) {
                    if ($department['user'][0]['department_name'] == $statistics['department_name']) {
                        $statisticss[$k]['allcard'] = $statisticss[$k]['allcard'] + 1;
                        if ($department['actual_time'] == null) {
                            $statisticss[$k]['offline'] = $statisticss[$k]['offline'] + 1;
                        }
                    }
                }
                if ($statisticss[$k]['allcard'] == 0) {
                    $statisticss[$k]['attendance'] = 0;
                } else {
                    $statisticss[$k]['attendance'] = number_format((($statisticss[$k]['allcard'] - $statisticss[$k]['offline']) * 100 / $statisticss[$k]['allcard']), 2) . '%';
                }
            }
            return $statisticss;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('用户名获取失败!', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * @return |null
     * @throws \Exception
     */
    public static function getPuchTime()
    {
        try {
            $time['today'] = date('Y-m-d H:i:s');
            $time['yesterday'] = date('Y-m-d H:i:s', strtotime('-1 day'));

            $result = self::join('users', 'users.id', 'punch_time_records.user_id')->where('actual_time', null)->where('required_time', '<=', $time['today'])->where('required_time', '>=', $time['yesterday'])
                ->groupBy('users.department_name')
                ->select('users.department_name as unit', DB::raw('count(punch_time_records.id) as count'))->get();
            foreach ($result as $val) {
                $last_time = self::join('users', 'users.id', 'punch_time_records.user_id')->where('actual_time', '<>', null)->where('users.department_name', $val->unit)->orderBy('actual_time', 'desc')->first();
                if (isset($last_time)) {
                    $val['last_time'] = $last_time->actual_time;
                } else {
                    $val['last_time'] = null;
                }
            }
            return $result;
        } catch (\Exception $e) {
            Logs::logError('获取离线时间和最近打卡时间失败!', [$e->getMessage()]);
            return null;
        }
    }

    //导出

    /**
     * @param $startdate
     * @param $enddate
     * @return |null
     * @throws \Exception
     */
    public static function getexport($startdate, $enddate)
    {
        try {
            $statisticss = users::select('department_name')->distinct()->get(); //有哪些项目
            $departments = self::with('user')->where('required_time', '>', $startdate)->where('required_time', '<', $enddate)->get(); //所有得打卡数据
            $phones = login_records::with('user')->orderBy('updated_at', 'desc')->get(); //所有得电话号码
            foreach ($statisticss as $k => $statistics) {
                $statisticss[$k]['phone_munber'] = null;
                foreach ($phones as $i => $phone) {
                    if ($phone['user'][0]['department_name'] == $statistics['department_name']) {
                        $statisticss[$k]['phone_munber'] = $phone['phone_munber'];
                        break;
                    }
                }
                $statisticss[$k]['department'] = $statistics['department_name'];
                $statisticss[$k]['allcard'] = 0;
                $statisticss[$k]['offline'] = 0;
                foreach ($departments as $department) {
                    if ($department['user'][0]['department_name'] == $statistics['department_name']) {
                        $statisticss[$k]['allcard'] = $statisticss[$k]['allcard'] + 1;
                        if ($department['actual_time'] == null) {
                            $statisticss[$k]['offline'] = $statisticss[$k]['offline'] + 1;
                        }
                    }
                }
                if ($statisticss[$k]['allcard'] == 0) {
                    $statisticss[$k]['attendance'] = 0;
                } else {
                    $statisticss[$k]['attendance'] = number_format((($statisticss[$k]['allcard'] - $statisticss[$k]['offline']) * 100 / $statisticss[$k]['allcard']), 2) . '%';
                }
            }
            return $statisticss;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('用户名获取失败!', [$e->getMessage()]);
            return null;
        }
    }
    public function user()
    {
        return $this->hasMany(users::class, 'id', 'user_id');
    }

    /**
     * @param $request
     * @return |null
     * @throws \Exception
     */
    public static function getsearchPuchTime($request)
    {
        try {
            $time['today'] = date('Y-m-d H:i:s');
            $time['yesterday'] = date('Y-m-d H:i:s', strtotime('-1 day'));

            $result = self::join('users', 'users.id', 'punch_time_records.user_id')->where('actual_time', null)->where('required_time', '<=', $time['today'])->where('required_time', '>=', $time['yesterday'])
                ->where('users.department_name', 'like', '%' . $request->unit_name . '%')
                ->groupBy('users.department_name')
                ->select('users.department_name as unit', DB::raw('count(punch_time_records.id) as count'))->get();
            foreach ($result as $val) {
                $last_time = self::join('users', 'users.id', 'punch_time_records.user_id')->where('actual_time', '<>', null)->where('users.department_name', $val->unit)->orderBy('actual_time', 'desc')->first();
                if (isset($last_time)) {
                    $val['last_time'] = $last_time->actual_time;
                } else {
                    $val['last_time'] = null;
                }
            }
            return $result;
        } catch (\Exception $e) {
            Logs::logError('搜索离线时间和最近打卡时间失败!', [$e->getMessage()]);
            return null;
        }
    }
}
