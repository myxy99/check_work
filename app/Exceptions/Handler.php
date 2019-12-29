<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
//        return parent::render($request, $exception);
        $errormsg = '服务器错误！';
        if ($exception instanceof HttpException) {
            if ($exception->getStatusCode() == '404') {
                $StatusCode = $exception->getStatusCode();
                $errors = $exception->getMessage();
            } else {
                $StatusCode = 500;
                $errors = $exception->getMessage();
                \App\Utils\Logs::logError($errormsg, [$errors]);
            }
        } else if ($exception instanceof AuthenticationException) {
            $StatusCode = 403;
            $errormsg = '权限不足！';
            $errors = $exception->getMessage();
        } else {
            $errors = $exception->getMessage();
            $StatusCode = 500;
            \App\Utils\Logs::logError($errormsg, [$errors]);
        }
        if (Request::ajax()) {
            return response()->fail($StatusCode, $errormsg, env('APP_DEBUG') ? $errors : null, $StatusCode);
        }else{
            return response()->view('errors.' . $StatusCode, ['errors' => $errors],$StatusCode);
        }
    }
}
