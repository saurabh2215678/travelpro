<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards){
        $referer = $request->path();
        $redirect_url = \Request::getRequestUri();

        // if(Auth::user() && Auth::user()->status == 0){
        //     Auth::logout();
        //     session(['redirect_url'=>$redirect_url]);
        //     return redirect()->guest('account/login?referer='.$referer)->with('alert-danger', 'Your account is currently Deactivated, Please contact Administrator.');
        // }else if(Auth::user() && (Auth::user()->is_agent == 1 && Auth::user()->approved_agent == 0)){
        //     Auth::logout();
        //     session(['need_approval'=>1]);
		// 	return redirect(url('account/approval'));
        // }

        $guards = empty($guards) ? [null] : $guards;
        //prd($guards);
        foreach ($guards as $guard) {
            if(Auth::viaRemember()){
                //echo 'viaRemember'; die;
            }       
            else if (Auth::guard($guard)->guest()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Unauthorized.', 401);
                } else {
                    session(['redirect_url'=>$redirect_url]);
                    return redirect()->guest('account/login?referer='.$referer);
                }
            }
        }
        
        return $next($request);
    }
}
