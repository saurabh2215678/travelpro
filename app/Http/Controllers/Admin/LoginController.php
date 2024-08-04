<?php
namespace App\Http\Controllers\Admin;
use App\Models\Admin;
use App\Models\EmailTemplate;
use App\Models\LoginToken;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Session;
use Hash;

class LoginController extends Controller {

    private $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request) {
        $data = [];
        if (auth()->guard('admin')->user()) return redirect($this->ADMIN_ROUTE_NAME);
        $method = $request->method();

        $segment = $request->segment(1);
        $is_vendor = 0;
        if(!empty($segment) && $segment == 'vendor'){
            $is_vendor = 1;
        }

        if($method == 'POST') {
            $RECAPTCHA_ADMIN_DISABLED = CustomHelper::WebsiteSettings('RECAPTCHA_ADMIN_DISABLED')??0;
            $nicknames = [
                'username' => 'Email',
                'password' => 'Password',
                'g-recaptcha-login' => 'Security Code'
            ];
            $rules = [];
            $rules['username'] = 'required|email';
            $rules['password'] = 'required';
            if($RECAPTCHA_ADMIN_DISABLED == 0){
                $rules['g-recaptcha-login'] = 'required';
            }
            $validation_msg = [];
            $validation_msg['required'] = ':attribute is required.';
            $this->validate($request, $rules, $validation_msg, $nicknames);

            $params = [];
            $params['user_id'] = 0;
            $params['user_name'] = '';
            $params['username'] = $request->username;
            $params['action'] = 'Login';

            $captchaSuccess = false;
            if($RECAPTCHA_ADMIN_DISABLED == 1) {
                $captchaSuccess = true;
            }else {
                $token = $request['g-recaptcha-login'];
                $captchaResp = CustomHelper::checkGoogleReCaptcha($token);
                if(!empty($captchaResp) && $captchaResp['success'] == 1) {
                    $captchaSuccess = true;
                    $RECAPTCHA_ADMIN_SCORE = CustomHelper::WebsiteSettings('RECAPTCHA_ADMIN_SCORE')??0.5;
                    if($captchaResp["score"] < $RECAPTCHA_ADMIN_SCORE) {
                        $captchaSuccess = false;
                        $params['action'] = 'Login Failed';
                    }
                }
            }
            

            if (auth()->guard('admin')->attempt(['email' => $request->input('username'), 'password' => $request->input('password')])) {

                //===============Login History=============================
                $user = auth::guard('admin')->user();
                $user_id = isset($user->id)?$user->id:'';
                $user_name = isset($user->name)?$user->name:'';
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['action'] = 'Login';
                CustomHelper::RecordLoginHistory($params);
                //===============Login History===============================

                return redirect($this->ADMIN_ROUTE_NAME);
            }
            if(!$captchaSuccess) {

                //===============Login History=============================
                //$params['user_id'] = $user_id;
                $params['user_name'] = $request->username;
                $params['action'] = 'Login Failed';
                CustomHelper::RecordLoginHistory($params);
                //===============Login History===============================
                
                return redirect()->back()->withInput()->with('alert-danger', 'Invalid form request.');
            }
        }
        $data['title'] = 'Login';
        $data['is_vendor'] = $is_vendor;
        return view('admin/login/index',$data);
    }

    public function auth(Request $request) {
        $this->validate($request, [
            'username' => 'required|email',
            'password' => 'required',
        ]);
        if (auth()->guard('admin')->attempt(['email' => $request->input('username'), 'password' => $request->input('password')])) {
            return redirect('admin');
        } else {
            $errors['err_msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Your username or password is wrong</strong>.</div>';
            return back()->with($errors);
        }
    }

    public function ajax_auth(Request $request) {
      $response = [];
      if($request->username && $request->password) {
        $websiteSettingsNamesArr = ['BACKEND_LOGIN_OTP_REQUIRED','BACKEND_LOGIN_DEVICE_RECOGNITION_IN_HOURS','OTP_EXPIRE_TIME'];
        $websiteSettingsArr = CustomHelper::websiteSettingsArray($websiteSettingsNamesArr);
        $validate = (isset($websiteSettingsArr['BACKEND_LOGIN_OTP_REQUIRED']))?$websiteSettingsArr['BACKEND_LOGIN_OTP_REQUIRED']->value:'';
        $expire_hours = (isset($websiteSettingsArr['BACKEND_LOGIN_DEVICE_RECOGNITION_IN_HOURS']))?$websiteSettingsArr['BACKEND_LOGIN_DEVICE_RECOGNITION_IN_HOURS']->value:'';

        $username = (isset($request->username))?$request->username:'';
        $is_vendor = (isset($request->is_vendor))?$request->is_vendor:'';
        $query = Admin::where('email',$username);

        if($is_vendor == 1){
        // prd($is_vendor);
            $query->where('is_vendor',1);
        }else{
            $query->whereNull('is_vendor');
        }

        $adminData = $query->first();
        if($adminData) {

          $cookie = false;
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
    }

    if($cookie == true) {
        $response['success'] = true;
        $response['msg'] = "No_OTP";
    } else {
        if($validate == 'Yes') {
          $viewData = [];
          $data = [];
          $response['htmlData'] = '';

          $old_password = (isset($request->password))?$request->password:'';
          $hash_chack = '';

        //   if($adminData) {
            $hash_chack = Hash::check($old_password, $adminData->password);
        // }
        if ($hash_chack) {
            $email = $adminData->email;
            $country_code = !empty($adminData->country_code)?$adminData->country_code:'91';
            $current_time = date('Y-m-d H:i:s');
            if(Session::has('dbOTP') && $current_time < $adminData->otp_expiry_time && (isset($adminData->otp) && isset($adminData->otp_expiry_time)) && (!empty($adminData->otp_expiry_time) && !empty($adminData->otp_expiry_time))){
                $otp = Session::get('dbOTP') ?? $adminData->otp;
            }else{
                $otp = rand(10000,99999);
            }

            $OTP_EXPIRE_TIME = (isset($websiteSettingsArr['OTP_EXPIRE_TIME']))?$websiteSettingsArr['OTP_EXPIRE_TIME']->value:'';
            $otp_valid_time = date('Y-m-d H:i:s', strtotime('+'.$OTP_EXPIRE_TIME.' minutes', strtotime($current_time)));

            $otpupdate = array('otp' =>  $otp,'otp_expiry_time'=> $otp_valid_time);
            $isUpdated = Admin::where(['email' => $username,])->update($otpupdate);
            if($isUpdated) {

                $websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO']);
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
                    $logo = $websiteSettingsArr['FRONTEND_LOGO'];
                    if($storage->exists($path.$logo)){
                        $logoSrc = asset('/storage/'.$path.$logo);
                    }
                }

                $common_logo = '';
                $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                $b2c_logo_params = array(
                    '{company_logo}' => $logoSrc
                );
                $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);

                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);

                $contact_details = '';
                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $system_title
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);

                Session::put('email',$email);
                Session::put('dbOTP',$otp);

                $email_template = EmailTemplate::where('slug','admin-login-otp')->first();
                $email_subject = isset($email_template->subject)?$email_template->subject : '';
                $email_content = isset($email_template->content)?$email_template->content : '';

                $form_values = [];
                $form_values['{otp}'] = $otp;
                $form_values['{date}'] = date("l jS \of F Y h:i A");
                $form_values['{valid_time}'] = date('d-m-Y H:i A',strtotime($otp_valid_time));
                $form_values['{header}'] = $common_logo;
                $form_values['{contact_details}'] = $contact_details??'';

                $bcc_email = '';
                if($email_template->email_type == 'customer' && $email_template->bcc_admin == 1){
                    $bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL'); 
                }

                if(isset($email_template) && !empty($email_template)){
                    $email_subject = $email_template->subject??'';
                    $email_content = $email_template->content??'';
                    $search_arr = array_keys($form_values);
                    $replace_arr = array_values($form_values);
                    $email_content = str_replace($search_arr, $replace_arr, $email_content);
                    $params = [];
                    $params['to'] = $email;
                    $params['bcc'] = $bcc_email;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    $params['module_name'] = 'Admin login otp';
                    $params['record_id'] = $adminData->id ?? 0;
                    $isSent = CustomHelper::sendCommonMail($params);
                }

                $response['success'] = true;
                $response['msg'] = "success";
                // Session::forget('dbOTP');
            } else {
                $response['success'] = false;
                $response['msg'] = "Something went wrong, please try again.";
            }
        } else {
            $response['success'] = false;
            $response['msg'] = "Login invalid";
        }
    } else {
      $response['success'] = true;
      $response['msg'] = "No_OTP";
  }
}
} else {
    $response['success'] = false;
    $response['msg'] = "The username you entered doesn't belong to an account. Please check your Username and try again.";
}
} else {
    $response['success'] = false;
    $response['msg'] = "Please Enter username and password";
}
if($response['success'] == false){
 //===============Login History====================
    //$params['user_id'] = $user_id;
    $params['user_name'] = $username??'';
    $params['action'] = 'Login Failed';
    $params['msg'] = $response['msg'];
    CustomHelper::RecordLoginHistory($params);
//===============Login History=====================
}
return response()->json($response);
}


public function ajax_authOtpCheck(Request $request) {
        // prd(Session::get('email'));
    if(Session::get('email')) {
        if($request->input('otp')) {
            $user = Admin::where(['email' => Session::get('email')])->first();
            $otp = $request->input('otp');
            $otp_expiry_time = isset($user->otp_expiry_time) ? $user->otp_expiry_time : '';
            $current_time = date('Y-m-d H:i:s');
            if(strtotime($current_time) > strtotime($otp_expiry_time)) {
                $response['success'] = false;
                $response['msg'] = "OTP has expired. Please generate a new OTP and try again";
            } else {
                $websiteSettingsNamesArr = ['DEFAULT_OTP','OTP_EXPIRE_TIME'];
                $websiteSettingsArr = CustomHelper::websiteSettingsArray($websiteSettingsNamesArr);
                $default_otp = (isset($websiteSettingsArr['DEFAULT_OTP']))?$websiteSettingsArr['DEFAULT_OTP']->value:'';
                if($user->otp == $otp || $default_otp == $otp ) {
                    $role_id = $user->role_id;
                    if(empty($role_id)) {
                        $response['success'] = false;
                        $response['msg'] = "You have not been assigned any role yet, please contact administrator.";
                    } else {
                        $cretate_login_token = CustomHelper::CreateLoginToken($user->id);
                        Session::forget('email');
                        $response['success'] = true;
                        $response['msg'] = "success";
                    }

                } else {
                    $response['success'] = false;
                    $response['msg'] = "OTP does not match.";
                }
            }
        } else {
            $response['success'] = false;
            $response['msg'] = "OTP is required.";
        }
    } else {
        $response['success'] = false;
        $response['msg'] = "Invalid Login Credentials";                    
    }
    return response()->json($response);
}

public function forgot(Request $request) {
    $data = [];
    if (auth()->guard('admin')->user()){
        return redirect($this->ADMIN_ROUTE_NAME);
    }
    $data['title'] = 'Reset Password';
    return view('admin/login/forgot', $data);
}

public function forgotOtp(Request $request) {
    $response = [];
    $response['success'] = false;
    if (auth()->guard('admin')->user()) return redirect($this->ADMIN_ROUTE_NAME);

    $method = $request->method();
    if($method == 'POST') {
        if($request->username) {
            $username = $request->username;
            $adminData = Admin::where('email',$username)->first();
            if($adminData) {
                    // if($adminData->email) {
                $email = $adminData->email;
                        // $country_code = !empty($adminData->country_code)?$adminData->country_code:'91';

                $otp = rand(10000,99999);

                $websiteSettingsNamesArr = ['DEFAULT_OTP','OTP_EXPIRE_TIME'];
                $websiteSettingsArr = CustomHelper::websiteSettingsArray($websiteSettingsNamesArr);
                $current_time = date('Y-m-d H:i:s');

                $OTP_EXPIRE_TIME = (isset($websiteSettingsArr['OTP_EXPIRE_TIME']))?$websiteSettingsArr['OTP_EXPIRE_TIME']->value:'';

                $otp_valid_time = date('Y-m-d H:i:s', strtotime('+'.$OTP_EXPIRE_TIME.' minutes', strtotime($current_time)));

                $otpupdate = array('otp' =>  $otp,'otp_expiry_time'=> $otp_valid_time);
                $isdata = Admin::where(['email' => $username,])->update($otpupdate);

                $websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO']);
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
                    $logo = $websiteSettingsArr['FRONTEND_LOGO'];
                    if($storage->exists($path.$logo)){
                        $logoSrc = asset('/storage/'.$path.$logo);
                    }
                }

                $common_logo = '';
                $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                $b2c_logo_params = array(
                    '{company_logo}' => $logoSrc
                );
                $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);

                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);

                $contact_details = '';
                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $system_title
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);

                // $email_template = EmailTemplate::where('slug','admin-password-reset-otp')->first();
                $email_template = EmailTemplate::where('slug','admin-login-otp')->first();
                $email_subject = isset($email_template->subject)?$email_template->subject : '';
                $email_content = isset($email_template->content)?$email_template->content : '';

                $form_values = [];
                $form_values['{otp}'] = $otp;
                $form_values['{header}'] = $common_logo;
                $form_values['{contact_details}'] = $contact_details??'';
                $form_values['{date}'] = date("l jS \of F Y h:i A");

                $bcc_email = '';
                if($email_template->email_type == 'customer' && $email_template->bcc_admin == 1){
                    $bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL'); 
                }

                if(isset($email_template) && !empty($email_template)){
                    $email_subject = $email_template->subject??'';
                    $email_content = $email_template->content??'';
                    $search_arr = array_keys($form_values);
                    $replace_arr = array_values($form_values);
                    $email_content = str_replace($search_arr, $replace_arr, $email_content);
                    $to_email = $email;
                    $params = [];
                    $params['to'] = $to_email;
                    $params['bcc'] = $bcc_email;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    $params['module_name'] = 'Admin forget otp';
                    $params['record_id'] = $adminData->id ?? 0;
                    $isSent = CustomHelper::sendCommonMail($params);
                }

                $response['success'] = true;
                $response['msg'] = "success";

                    // } else {
                    //     $response['msg'] = 'We couldn\'t find an phone number associated with '.$username.'. Please contact your administrator.';
                    // }

            } else {
                $response['msg'] = 'We couldn\'t find an account associated with '.$username.'. Please try with an alternate email.';
            }
        } else {
            $response['msg'] = 'Please enter username.';
        }
    }
    return response()->json($response);
}

public function forgotOtpCheck(Request $request) {
    $response = [];
    $response['success'] = false;
    if (auth()->guard('admin')->user()) return redirect($this->ADMIN_ROUTE_NAME);

    $method = $request->method();
    if($method == 'POST') {
        if($request->username && $request->otp) {

            $username = $request->username;
            $otp = $request->otp;

            $adminData = Admin::where('email',$username)->first();
            if($adminData) {

                    // if($adminData->phone) {

                $otp_expiry_time = ($adminData->otp_expiry_time) ? $adminData->otp_expiry_time : '';

                $current_time = date('Y-m-d H:i:s');

                if($current_time > $otp_expiry_time) {
                    $response['success'] = false;
                    $response['msg'] = "OTP has expired.";
                    $response['error_msg'] = "OTP has expired. Please generate a new OTP and try again";
                } else {

                    $default_otp =  CustomHelper::WebsiteSettings('DEFAULT_OTP');

                    if($adminData->otp == $otp || $default_otp == $otp ) {
                        $cretate_login_token = CustomHelper::CreateLoginToken($adminData->id);

                        Session::put('reset_token', md5($adminData->email.'@@@'.$otp));
                        $response['success'] = true;
                        $response['msg'] = "success";
                    } else {
                        $response['success'] = false;
                        $response['msg'] = "OTP does not match.";
                    }
                }
                    // } else {
                    //     $response['msg'] = 'We couldn\'t find an phone number associated with '.$username.'. Please contact your administrator.';
                    // }

            } else {
                $response['msg'] = "The username you entered doesn't belong to an account. Please check your Username and try again.";
            }
        } else {
            $response['msg'] = 'Please enter OTP.';
        }
    }
    return response()->json($response);
}


public function forgotPasswordReset(Request $request) {
    $response = [];
    $response['success'] = false;
    if (auth()->guard('admin')->user()) return redirect($this->ADMIN_ROUTE_NAME);

    $method = $request->method();
    if($method == 'POST') {
        if($request->username && $request->otp && $request->new_password) {

            $username = $request->username;
            $otp = $request->otp;
            $new_password = $request->new_password;
            $confirm_password = $request->confirm_password;

            $adminData = Admin::where('email',$username)->first();
            if($adminData) {

                    // if($adminData->phone) {
                if(strlen($new_password) >= 6 and strlen($new_password) < 16) {
                    $reset_token = Session::get('reset_token');
                    if($reset_token == md5($adminData->email.'@@@'.$otp)) {
                        if($new_password == $confirm_password) {
                            $adminData->password = bcrypt(trim($new_password));
                            $adminData->save();
                            $response['success'] = true;
                        } else {
                            $response['msg'] = 'Confirm password doesn\'t matched.';
                        }
                    } else {
                        $response['msg'] = 'Something went wrong, please try again.';
                    }
                } else {
                    $response['msg'] = (strlen($new_password) < 6) ? 'Password must be at least 6 characters.' : 'Password should not be greater than 16 characters.';
                }
                    // } else {
                    //     $response['msg'] = 'We couldn\'t find an phone number associated with '.$username.'. Please contact your administrator.';
                    // }

            } else {
                $response['msg'] = "The username you entered doesn't belong to an account. Please check your Username and try again.";
            }
        } else {
            $response['msg'] = 'Please enter password.';
        }
    }
    return response()->json($response);
}



/*End of controller */
}