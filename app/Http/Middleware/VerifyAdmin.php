<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAdmin
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
        return auth()->user()->is_admin
            ? $next($request) :
            ($request->ajax() ?
                response()->fail(403, '权限不足!', null, 403) :
                response()->view('errors.' . 403, null, 403));
    }
}
