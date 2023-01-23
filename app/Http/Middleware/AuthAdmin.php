<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdmin{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = "admin"){

        $ADMIN_ROUTE_NAME = config('custom.ADMIN_ROUTE_NAME');
        //prd(Auth::guard($guard)->check());
        if (!Auth::guard($guard)->check()) {
            return redirect($ADMIN_ROUTE_NAME.'/login');
        }
        return $next($request);
    }
}
