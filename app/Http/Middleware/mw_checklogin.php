<?php

namespace App\Http\Middleware;

use Closure;

class mw_checklogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if (!$request->user()) {
        if (!$request->user() || date('Y') > '2020') {
             return redirect('/');
        }
        return $next($request);
    }
}
