<?php

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
Route::post('OAuth/login', 'OAuth\AuthController@login');//登陆
Route::post('OAuth/logout', 'OAuth\AuthController@logout');//退出登陆
Route::post('OAuth/refresh', 'OAuth\AuthController@refresh');//刷新token
Route::post('OAuth/updatepw', 'OAuth\AuthController@updatepw');//修改密码