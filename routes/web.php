<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    clean("<scpt>alert('1')</scpt>");
    return view('welcome');
});

Route::post('OAuth/login', 'OAuth\AuthController@login');
Route::view('/index', 'index');
Route::view('/admin', 'admin');


//登录录入
Route::post('front/loginrecord','Front\LoginRecordController@loginRecord');//*完成
//获取前台通知信息
Route::get('front/getallinfo','Front\FrontInfoController@getAllInfo');//*w完成
//前台个人信息获取
Route::get('front/getpersoninfo','Front\PersonInfoController@getPersonInfo');//*完成
//前台打卡
Route::post('front/puchcard','Front\PunchCardController@puchCard');//*完成
//获取所有信息
Route::get('admin/getallmsg','Admin\MessageController@getAllMsg');//*完成
//搜索信息
Route::get('admin/searchmsg','Admin\MessageController@searchMsg');//*完成
//删除信息
Route::delete('admin/delmsg','Admin\MessageController@delMsg');//*完成
//获取记录信息
Route::get('admin/msgrecord','Admin\MessageController@msgRecord');//*完成
//上传文件
Route::post('admin/uploadfile','Admin\MessageController@uploadFile');//*完成
//取消文件上传
Route::delete('admin/cancelfile','Admin\MessageController@cancelFile');//*完成

