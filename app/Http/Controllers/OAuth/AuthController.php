<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OAuth\Auth\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        return auth()->attempt(self::credentials($request), false) ? (auth()->user()->is_admin ?
            response()->success(200, '登陆成功！', env('SKIP_ADMIN')) :
            response()->success(200, '登陆成功！', env('SKIP_INDEX'))) :
            response()->fail(100, '账号或者密码错误！');
    }

    /**
     * @param $request
     * @return array
     */
    protected function credentials($request)
    {
        return ['user_name' => $request->user_name, 'password' => $request->password];
    }
}
