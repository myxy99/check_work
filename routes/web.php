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

//郑如缘
Route::prefix('Admin')->namespace('Admin')->group(function(){
    Route::get('getAllSendNotices', 'NoticeController@getAllSendNotices');//显示所有已发公告
    Route::post('searchNotice', 'NoticeController@searchNotice');//搜索框搜索
    Route::get('showSendObj', 'NoticeController@showSendObj');//通知界面显示发送对象
    Route::post('addNotice', 'NoticeController@addNotice');//新增通知
});