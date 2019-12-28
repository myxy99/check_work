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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('OAuth/login', 'OAuth\AuthController@login');//登陆
Route::post('OAuth/logout', 'OAuth\AuthController@logout');//退出登陆
Route::post('OAuth/refresh', 'OAuth\AuthController@refresh');//刷新token
