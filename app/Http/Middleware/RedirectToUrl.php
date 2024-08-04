<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UrlRedirection;

class RedirectToUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request); 
        //=======
            
            $currentUrl = \Request::path();
           
            $redirectionsArr = $this->redirections();
            // prd($redirectionsArr);
            $is_have_redirection = array_search($currentUrl, array_column($redirectionsArr, 'source_url'));
            // echo "<pre>";print_r($is_have_redirection);die;
            
            if(is_numeric($is_have_redirection) && isset($redirectionsArr[$is_have_redirection])){
                // prd($redirectionsArr[$is_have_redirection]->status_code);
                $redirectUrl = $redirectionsArr[$is_have_redirection]['destination_url'];
                $redirectCode = $redirectionsArr[$is_have_redirection]['status_code'];
                // $redirectCode = 301;

                // echo $redirectUrl;
                // exit;
                 // redirect($redirectUrl,'location',$redirectCode);
                if($redirectCode == 301) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: /".$redirectUrl);
                    exit();
                } else if ($redirectCode == 410) {
                    header("HTTP/1.1 410 Gone");
                    return response()->view('errors.' . '410', [], 410);
                    exit();
                } else {
                    header("HTTP/1.1 307 Temporary Redirect");
                    header("Location: /".$redirectUrl);
                    exit();
                }
            } else {
                 return $next($request); 
            }

    }


    public function redirections() {
        // return $redirectionsArr = UrlRedirection::where('show',1)->get();
        $redirectionsArr = \Cache::rememberForever('redirections', function () {
            $redirectionsArr = UrlRedirection::where('show',1)->get()->toArray();
            return $redirectionsArr;
        });
        return $redirectionsArr;
    }
}
