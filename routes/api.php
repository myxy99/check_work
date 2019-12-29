<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('Admin')->namespace('Admin')->group(function () {
    Route::get('/getUserName', 'PersonalCenterController@getUserName');
    Route::post('/updatePassword', 'PersonalCenterController@updatePassword');
});
Route::post('OAuth/login', 'OAuth\AuthController@login'); //登陆
Route::post('OAuth/logout', 'OAuth\AuthController@logout'); //退出登陆
Route::post('OAuth/refresh', 'OAuth\AuthController@refresh'); //刷新token
Route::post('OAuth/updatepw', 'OAuth\AuthController@updatepw'); //修改密码
Route::prefix('Admin')->namespace('Admin')->group(function () {
    Route::get('getAllSendNotices', 'NoticeController@getAllSendNotices'); //显示所有已发公告
    Route::post('searchNotice', 'NoticeController@searchNotice'); //搜索框搜索
    Route::get('showSendObj', 'NoticeController@showSendObj'); //通知界面显示发送对象
    Route::post('addNotice', 'NoticeController@addNotice'); //新增通知
    //yikang
    Route::get('getAllCheck', 'CheckController@getAllCheck'); //获取全部打卡信息
    Route::post('setCheck', 'CheckController@setCheck'); //打卡设置
    Route::get('searchCheck', 'CheckController@searchCheck'); //搜索打卡
    Route::get('getAllUnit', 'UnitController@getAllUnit'); //返回所有单位信息
    Route::post('addUnit', 'UnitController@addUnit'); //新增单位
    Route::post('updateUnit/{id}', 'UnitController@updateUnit'); //修改单位
    Route::post('addNotification/{id}', 'UnitController@addNotification'); //新增通知
    Route::get('searchUnit', 'UnitController@searchUnit'); //搜索单位
});
Route::prefix('statistic')->namespace('Admin')->group(function () {
    Route::get('alldata', 'StatisticController@getalldata'); //获取所有的
    Route::post('search', 'StatisticController@getSearch'); //搜索框查询
    Route::post('export', 'StatisticController@getexport'); //导出
});
Route::post('front/loginrecord','Front\LoginRecordController@loginRecord');//登录录入
Route::get('front/getallinfo','Front\FrontInfoController@getAllInfo');//获取前台通知信息
Route::get('front/getpersoninfo','Front\PersonInfoController@getPersonInfo');//前台个人信息获取
Route::post('front/puchcard','Front\PunchCardController@puchCard');//前台打卡
Route::get('admin/getallmsg','Admin\MessageController@getAllMsg');//获取所有信息
Route::get('admin/searchmsg','Admin\MessageController@searchMsg');//搜索信息
Route::delete('admin/delmsg','Admin\MessageController@delMsg');//删除信息
Route::get('admin/msgrecord','Admin\MessageController@msgRecord');//获取记录信息
Route::post('admin/uploadfile','Admin\MessageController@uploadFile');//上传文件
Route::delete('admin/cancelfile','Admin\MessageController@cancelFile');//取消文件上传

