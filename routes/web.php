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

Route::get('front/getallinfo','Front\FrontInfoController@getAllInfo');
Route::get('front/getpersoninfo','Front\PersonInfoController@getPersonInfo');
Route::post('front/puchcard','Front\PunchCardController@puchCard');


Route::get('admin/getallmsg','Admin\MessageController@getAllMsg');
Route::delete('admin/delmsg','Admin\MessageController@delMsg');
Route::delete('admin/cancelfile','Admin\MessageController@cancelFile');
Route::get('admin/msgrecord','Admin\MessageController@msgRecord');
Route::get('admin/searchmsg','Admin\MessageController@searchMsg');
Route::post('admin/uploadfile','Admin\MessageController@uploadFile');
Route::delete('admin/delmsg','Admin\MessageController@delMsg');


