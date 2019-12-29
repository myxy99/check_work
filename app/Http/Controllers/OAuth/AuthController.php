<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OAuth\Auth\LoginRequest;
use App\Http\Requests\OAuth\Auth\UpdatepwRequest;
use App\Models\users;

class AuthController extends Controller
{
    /**
     * 用户登陆
     * @param LoginRequest $request
     * @return |null
     * @throws \Exception
     */
    public function login(LoginRequest $request)
    {
        try {
            $token = auth()->attempt(self::credentials($request));
            return $token ? (auth()->user()->is_admin ?
                self::respondWithToken($token, '登陆成功！', 201) :
                self::respondWithToken($token, '登陆成功！', 200)) :
                response()->fail(100, '账号或者密码错误！');
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('登陆失败！', [$e->getMessage()]);
            return response()->fail(100, '登陆失败！');
        }
    }

    /**
     * 退出登陆
     * @return mixed
     * @throws \Exception
     */
    public function logout()
    {
        try {
            auth()->logout();
            return !auth()->check() ?
                response()->success(200, '退出登陆成功！') :
                response()->fail(100, '退出登陆失败！');
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('退出登陆失败！', [$e->getMessage()]);
            return response()->fail(100, '退出登陆失败！');
        }
    }

    /**
     * @param UpdatepwRequest $request
     * @throws \Exception
     */
    public function updatepw(UpdatepwRequest $request){
        return users::updatePW($request->new_password) ?
            response()->success(200, '修改密码成功！') :
            response()->fail(200, '修改密码失败！');
    }

    /**
     * 刷新token
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function refresh()
    {
        try {
            $newToken = auth()->refresh();
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('刷新token失败!', [$e->getMessage()]);
        }
        return $newToken != null ?
            self::respondWithToken($newToken, '刷新成功!') :
            response()->fail(100, '刷新token失败!');
    }

    protected function respondWithToken($token, $msg, $code = 200)
    {
        return response()->success($code, $msg, array(
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ));
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
