<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Helpers\CustomHelper;

class CheckPermission{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $moduleName, $action) {
        $ADMIN_ROUTE_NAME = config('custom.ADMIN_ROUTE_NAME');
        if(!CustomHelper::checkPermission($moduleName,$action)) {
            return redirect(url($ADMIN_ROUTE_NAME))->with('alert-danger', "You don't have permission to access this page");
        }
        return $next($request);
    }
}