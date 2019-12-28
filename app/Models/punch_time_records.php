<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class punch_time_records extends Model
{
    public $timestamps = true;
    protected $table = 'punch_time_records';
    protected $primaryKey = 'id';
    protected $guarded = [];

    //获取全部
    public static function getall()
    {
        try {
            $statisticss=users::select('department_name')->distinct()->paginate(5);//有哪些项目
            $departments = self::with('user')->get();//所有得打卡数据
            $phones = login_records::with('user')->orderBy('updated_at','desc')->get();//所有得电话号码
            foreach ($statisticss as $k => $statistics){
                $statisticss[$k]['phone_munber'] = null;
                foreach ($phones as $i => $phone){
                    if($phone['user'][0]['department_name']==$statistics['department_name']){
                        $statisticss[$k]['phone_munber'] = $phone['phone_munber'];
                        break;
                    }
                }
                $statisticss[$k]['department'] = $statistics['department_name'];
                $statisticss[$k]['allcard'] = 0;
                $statisticss[$k]['offline'] = 0;
                foreach ($departments as $department) {
                    if($department['user'][0]['department_name']==$statistics['department_name']){
                        $statisticss[$k]['allcard']=$statisticss[$k]['allcard']+1;
                        if($department['actual_time']==null){
                            $statisticss[$k]['offline']=$statisticss[$k]['offline']+1;
                        }
                    }
                }
                if($statisticss[$k]['allcard']==0){
                    $statisticss[$k]['attendance']=0;
                }else{
                    $statisticss[$k]['attendance']=number_format((($statisticss[$k]['allcard']-$statisticss[$k]['offline'])*100/$statisticss[$k]['allcard']),2).'%';
                }
            }
            return $statisticss;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('用户名获取失败!', [$e->getMessage()]);
            return null;
        }
    }

    //查询
    public static function getSearch($department)
    {
        try {
            $statisticss=users::select('department_name')->where('department_name','like','%'.$department.'%')->distinct()->paginate(5);//有哪些项目
            $departments = self::with('user')->get();//所有得打卡数据
            $phones = login_records::with('user')->orderBy('updated_at','desc')->get();//所有得电话号码
            foreach ($statisticss as $k => $statistics){
                $statisticss[$k]['phone_munber'] = null;
                foreach ($phones as $i => $phone){
                    if($phone['user'][0]['department_name']==$statistics['department_name']){
                        $statisticss[$k]['phone_munber'] = $phone['phone_munber'];
                        break;
                    }
                }
                $statisticss[$k]['department'] = $statistics['department_name'];
                $statisticss[$k]['allcard'] = 0;
                $statisticss[$k]['offline'] = 0;
                foreach ($departments as $department) {
                    if($department['user'][0]['department_name']==$statistics['department_name']){
                        $statisticss[$k]['allcard']=$statisticss[$k]['allcard']+1;
                        if($department['actual_time']==null){
                            $statisticss[$k]['offline']=$statisticss[$k]['offline']+1;
                        }
                    }
                }
                if($statisticss[$k]['allcard']==0){
                    $statisticss[$k]['attendance']=0;
                }else{
                    $statisticss[$k]['attendance']=number_format((($statisticss[$k]['allcard']-$statisticss[$k]['offline'])*100/$statisticss[$k]['allcard']),2).'%';
                }
            }
            return $statisticss;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('用户名获取失败!', [$e->getMessage()]);
            return null;
        }
    }

    //导出
    public static function getexport($startdate,$enddate)
    {
        try {
            $statisticss=users::select('department_name')->distinct()->paginate(5);//有哪些项目
            $departments = self::with('user')->where('required_time','>',$startdate)->where('required_time','<',$enddate)->get();//所有得打卡数据
            $phones = login_records::with('user')->orderBy('updated_at','desc')->get();//所有得电话号码
            foreach ($statisticss as $k => $statistics){
                $statisticss[$k]['phone_munber'] = null;
                foreach ($phones as $i => $phone){
                    if($phone['user'][0]['department_name']==$statistics['department_name']){
                        $statisticss[$k]['phone_munber'] = $phone['phone_munber'];
                        break;
                    }
                }
                $statisticss[$k]['department'] = $statistics['department_name'];
                $statisticss[$k]['allcard'] = 0;
                $statisticss[$k]['offline'] = 0;
                foreach ($departments as $department) {
                    if($department['user'][0]['department_name']==$statistics['department_name']){
                        $statisticss[$k]['allcard']=$statisticss[$k]['allcard']+1;
                        if($department['actual_time']==null){
                            $statisticss[$k]['offline']=$statisticss[$k]['offline']+1;
                        }
                    }
                }
                if($statisticss[$k]['allcard']==0){
                    $statisticss[$k]['attendance']=0;
                }else{
                    $statisticss[$k]['attendance']=number_format((($statisticss[$k]['allcard']-$statisticss[$k]['offline'])*100/$statisticss[$k]['allcard']),2).'%';
                }
            }
            return $statisticss;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('用户名获取失败!', [$e->getMessage()]);
            return null;
        }
    }
    public function user(){
        return $this->hasMany(users::class,'id','user_id');
    }
}
