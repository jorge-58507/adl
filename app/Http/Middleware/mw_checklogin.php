<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

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
        $user = $request->user();
        if (date('n') > '7'|| !$request->user()) {
            return redirect('/login');
        }
        elseif ($user['status'] === 0) {
            Auth::logout();
            return redirect('/login');
        }
        return $next($request);
    }
}
