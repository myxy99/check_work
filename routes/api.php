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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('Admin')->namespace('Admin')->group(function () {
    Route::get('/getUserName', 'PersonalCenterController@getUserName');
    Route::post('/updatePassword', 'PersonalCenterController@updatePassword');
});

Route::post('OAuth/login', 'OAuth\AuthController@login');//登陆
Route::post('OAuth/logout', 'OAuth\AuthController@logout');//退出登陆
Route::post('OAuth/refresh', 'OAuth\AuthController@refresh');//刷新token
Route::post('OAuth/updatepw', 'OAuth\AuthController@updatepw');//修改密码

Route::prefix('Admin')->namespace('Admin')->group(function () {
    Route::get('getAllSendNotices', 'NoticeController@getAllSendNotices');//显示所有已发公告
    Route::post('searchNotice', 'NoticeController@searchNotice');//搜索框搜索
    Route::get('showSendObj', 'NoticeController@showSendObj');//通知界面显示发送对象
    Route::post('addNotice', 'NoticeController@addNotice');//新增通知
});

