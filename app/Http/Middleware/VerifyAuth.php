<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return auth()->check() ?
            $next($request) :
            ($request->ajax() ?
                response()->fail(100, '请先登陆!', null, 401) :
                response()->view('errors.' . 403, ['errors'=>''], 403));
    }
}
