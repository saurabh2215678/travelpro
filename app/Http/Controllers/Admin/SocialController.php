<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\TempUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Storage;
use App\Helpers\CustomHelper;
use Auth;
use DB;
use Image;
use Socialite;
use session;

class SocialController extends Controller{

    private $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    private function getRedirectUrlAfterLogin(){
        //$redirectUrl = route('user.profile');
        $redirectUrl = url('/');
        $redirect_url = (session()->has('redirect_url'))?session('redirect_url'):'';
        $referer = (session()->has('referer'))?session('referer'):'';
        if(!empty($redirect_url)) {
            $redirectUrl = url($redirect_url);
        } else if(!empty($referer)){
            $redirectUrl = url($referer);
        }
        return $redirectUrl;
    }

    public function redirect(Request $request) {
        $type = $request->type ;
        $is_agent = $request->is_agent??0;
        $provider = $request->provider;
        $is_vendor = $request->is_vendor;
        session(['login_type'=>$type]);
        session(['login_is_agent'=>$is_agent]);
        session(['is_vendor'=>$is_vendor]);

        return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider) {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        $login_type = (session()->has('login_type'))?session('login_type'):'';
        $login_is_agent = (session()->has('login_is_agent'))?session('login_is_agent'):0;
        $is_vendor = (session()->has('is_vendor'))?session('is_vendor'):'';
        $name = $userSocial->getName();
        $email = $userSocial->getEmail();
      
        session()->forget('login_type');
        session()->forget('login_is_agent');
        if($login_type == 'customer') {
            $user = User::where('email',$email)->first();
            if($user) {

                if($user->is_verified != 1){
                  $updateuser = User::where('email',$email)->update(['is_verified'=> 1]);
                }

                if($login_is_agent == 1 && $user->approved_agent != 1 && $user->is_agent != 1) {
                    session(['temp_user_id'=> $user->id]);
                    session(['social_signup'=> 1]);
                    session(['social_name'=>$name]);
                    session(['social_email'=>$email]);
                    return redirect(route('account.agent_signup'));
                } else {
                    Auth::login($user);
                    if($user->is_agent == 1) {
                        $agentInfo = $user->agentInfo??[];
                        if(empty($agentInfo)) {
                            session(['old_user_id'=>$user->id]);
                            return redirect(route('user.agent_signup_2'));
                        } else if($user->approved_agent == 0) {
                            return redirect(route('user.approval'));
                        }
                    }
                    //return redirect(route('user.profile'));
                    $redirect_url = $this->getRedirectUrlAfterLogin();
                    if($redirect_url) {
                        return redirect($redirect_url);
                    } else {
                        return redirect('/');
                    }
                }
            } else {
                $is_agent = 0;  
                $user = TempUser::where('email',$email)->first();
                if(empty($user)){
                    $user = new TempUser;
                }
                $user->name = $name;
                $user->email = $email;
                $user->status = 0;
                $user->is_verified = 0;
                $user->address1 = NULL;
                $user->address2 = NULL;
                $user->zipcode = NULL;
                $user->profile_image = NULL;
                $user->city = NULL;
                $user->state = NULL;
                $user->country = NULL;
                $user->is_agent = $is_agent;
                $is_saved = $user->save();

                session(['temp_user_id'=> $user->id]);
                session(['social_signup'=> 1]);
                session(['social_name'=>$name]);
                session(['social_email'=>$email]);

                if($login_is_agent == 1) {
                    return redirect(route('account.agent_signup'));
                } else {
                    return redirect(route('account.signup'));
                }

            }
        } else {
            $vendor_user  = '';
            if($is_vendor){
              $vendor_user = $is_vendor;
            }

            $query = Admin::where('email',$email);
            $route = 'admin';
            if($vendor_user == 1){
            $route = 'vendor';
               $query->where('is_vendor',1);
            }else{
               $query->whereNull('is_vendor');
            }

            $user = $query->first();

            if($user) {            
                $user_id = $user->id;
                // prd($user);
                /*$cookie = false;
                if($expire_hours == '-1') {
                    $cookie = true;
                } else if($expire_hours == 0) {
                    $cookie = false;
                    } else {
                        $cookie_name = md5('balogin_token'.$adminData->id);
                        $token = Cookie::get($cookie_name);
                        $tokenData = LoginToken::where('token',$token)->first();
                            // prd($tokenData);
                        if($tokenData) {
                          $expire_date = $tokenData->expire_date;
                          if(strtotime(date('Y-m-d H:i:s')) < strtotime($expire_date)) {
                            $cookie = true;
                        }
                    }
                }*/
                auth()->guard('admin')->loginUsingId($user_id);

                //===============Login History=============================
                $user = auth::guard('admin')->user();
                $user_id = isset($user->id)?$user->id:'';
                $user_name = isset($user->name)?$user->name:'';
                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['action'] = 'Google Login';
                CustomHelper::RecordLoginHistory($params);
                //===============Login History===============================

                return redirect($this->ADMIN_ROUTE_NAME);
                /*if($cookie == true) {
                }else{
                }*/
            } else {
                $errors['err_msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Your Email is wrong</strong>.</div>';


                return redirect(url($route.'/login'))->with('alert-danger', "The Email doesn't belong to an account. Please check your Username and try again.");
            }
        }
    }
}