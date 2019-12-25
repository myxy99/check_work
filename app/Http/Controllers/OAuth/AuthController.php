<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OAuth\Auth\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (auth()->attempt(self::credentials($request), false)) {
            if (auth()->user()->is_admin) {
                return response()->success(200, '登陆成功！', env('SKIP_ADMIN'));
            } else {
                return response()->success(200, '登陆成功！', env('SKIP_INDEX'));
            }
        } else {
            return response()->fail(100, '账号或者密码错误！');
        }
    }

    protected function credentials($request)
    {
        return ['user_name' => $request->user_name, 'password' => $request->password];
    }
}
