<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\Admin;
use App\Models\LoginToken;
use App\Models\User;
use App\Models\Country;
use App\Models\SmtpSetting;
use App\Models\EmailTemplate;
use App\Models\UserAgentInfo;
use App\Models\TempUser;
use App\Helpers\CustomHelper;
use DB;
use Validator;
use Session;
use Hash;
use Storage;
use File;
use Image;
use Agent;
use Location;
use Carbon\Carbon as Carbon;
use Response;
use Inertia\Inertia;

class AccountController extends Controller {

	/**
	 * URL: /account
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	private $theme;

	public function __construct(){
		$this->middleware('guest');
		$this->theme = config('custom.themejs');
		$referer = (isset(request()->referer))?request()->referer:'';
		if(!empty($referer)) {
			session(['referer'=>$referer]);
		}
	}

	public function index() {
		echo "index"; die;
	}

	private function getRedirectUrlAfterLogin() {
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

	public function login(Request $request) {
		$data = [];
		$seo_data = [];
		if($request->referer) {
			session(['referer'=>$request->referer]);
		}
		if($request->redirect_url) {
			session(['redirect_url'=>$request->redirect_url]);
		}
		$method = $request->method();
		if($method == 'POST') {
			$success = false;
			$message = '';
			$redirect_url = '';
			$location_url = '';

            $RECAPTCHA_FRONT_DISABLED = CustomHelper::WebsiteSettings('RECAPTCHA_FRONT_DISABLED')??0;
			$nicknames = [
				'user_email' => 'Email',
				'password' => 'Password',
				'g-recaptcha-login' => 'Security Code'
			];
			$rules = [];
			$rules['user_email'] = 'required|email';
			$rules['password'] = 'required|min:6';
			if($RECAPTCHA_FRONT_DISABLED == 0) {
				$rules['g-recaptcha-login'] = 'required';
			}
			$validation_msg = [];
			$validation_msg['required'] = ':attribute is required.';
			$validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
			if($validator->fails()) {
				return Response::json(array(
					'success' => false,
					'errors' => $validator->getMessageBag()->toArray()
                ), 400);
			}

            $captchaSuccess = false;
	        if($RECAPTCHA_FRONT_DISABLED == 1) {
	        	$captchaSuccess = true;
	        } else {
				$token = $request['g-recaptcha-login'];
				$captchaResp = CustomHelper::checkGoogleReCaptcha($token);
				$captchaSuccess = false;
				if(!empty($captchaResp) && $captchaResp['success'] == 1) {
					$captchaSuccess = true;
					$RECAPTCHA_FRONT_SCORE = CustomHelper::WebsiteSettings('RECAPTCHA_FRONT_SCORE')??0.5;
					if($captchaResp["score"] < $RECAPTCHA_FRONT_SCORE) {
						$captchaSuccess = false;
					}
				}
			}
			$captchaSuccess = true;
			if(!$captchaSuccess) {
				return Response::json(array(
					'success' => false,
					'message' => 'Invalid form request.'
                ), 400);
			}
			$email = $request->user_email;
			$password = $request->password;
			$remember = (isset($request->remember))?$request->remember:'';
			$user_where = [];
			$user_where['email'] = $email;
			$user = User::where($user_where)->first();
			$Tempuser = TempUser::where($user_where)->first();
			if(!empty($user)){
				if($user->status == 1  && $user->is_verified == 1){
					if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
						$sessionToken = csrf_token();
						$userId = $user->id;
						if($user->is_agent == '1' && (!isset($user->agentInfo) || empty($user->agentInfo))) {
							session(['old_user_id'=>$user->id]);
							$success = true;
							$redirect_url = route('user.agent_signup_2');
						} else if($user->is_agent == '1' && $user->approved_agent != 1) {
							$success = true;
							$location_url = route('user.approval');
						} else {
							$success = true;
							$redirect_url = $this->getRedirectUrlAfterLogin();
						}
					} else {
						$message = 'Your login information appears to be incorrect. Please try again or reset password using the option given below.';
					}
				} else {
					if (Hash::check($request->password, $user->password)) {
						if($user->is_verified != 1) {
							$userId = $user->id;
							$otp = rand(10000,99999);
							session(['userId' => $userId,'signup_otp' => $otp]);
							User::where("id", $userId)->update(["otp" => $otp]);
							$reciver_emails = $user->email;
							$name = isset($user->name) ? $user->name : '';
							$email = isset($user->email) ? $user->email : '';

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

							$email_params = array(
								'{date}' => date("l jS \of F Y h:i A"),
								'{otp}' => $otp,
								'{name}' => $name,
								'{email}' => $email,
								'{header}' => $common_logo,
								'{contact_details}' => $contact_details??'',
								'{type}' => 'Login OTP',
							);

							$template_slug = 'user-signup-otp';
							$email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

							$email_content = isset($email_content_data->content) ? $email_content_data->content : '';
							$email_content = strtr($email_content, $email_params);
							$email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

							$to_email = $reciver_emails;
							$cc_email = '';
							$bcc_email = '';

							if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
								$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
							}

							if(isset($email_content_data) && !empty($email_content_data)){
								$params = [];
								$params['to'] = $to_email;
								$params['cc'] = $cc_email;
								$params['bcc'] = $bcc_email;
								$params['subject'] = $email_subject;
								$params['email_content'] = $email_content;
								$params['module_name'] = 'User login';
								$params['record_id'] = $userId ?? 0;
								$isSent = CustomHelper::sendCommonMail($params);
							}
							$success = true;
							$message = 'Otp sent successfully on '.$user->email;
							$redirect_url = route('account.otp');
					} else {
						$message = 'Your account is currently Deactivated, Please contact Administrator.';
					}

					} else {
						$message = 'Your login information appears to be incorrect. Please try again or reset password using the option given below.';
					}
				}
			} else if(!empty($Tempuser)) {
					if (Hash::check($request->password, $Tempuser->password)) {
						if($Tempuser->is_verified != 1) {
							$userId = $Tempuser->id;
							$otp = rand(10000,99999);
							session(['userId' => $userId,'signup_otp' => $otp]);
							TempUser::where("id", $userId)->update(["otp" => $otp]);
							$reciver_emails = $Tempuser->email;
							$name = isset($Tempuser->name) ? $Tempuser->name : '';
							$email = isset($Tempuser->email) ? $Tempuser->email : '';

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

							$email_params = array(
								'{date}' => date("l jS \of F Y h:i A"),
								'{otp}' => $otp,
								'{name}' => $name,
								'{email}' => $email,
								'{header}' => $common_logo,
								'{contact_details}' => $contact_details??'',
								'{type}' => 'Login OTP',
							);

							$template_slug = 'user-signup-otp';
							$email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

							$email_content = isset($email_content_data->content) ? $email_content_data->content : '';
							$email_content = strtr($email_content, $email_params);
							$email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

							$to_email = $reciver_emails;
							$cc_email = '';
							$bcc_email = '';

							if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
								$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
							}

							if(isset($email_content_data) && !empty($email_content_data)){
								$params = [];
								$params['to'] = $to_email;
								$params['cc'] = $cc_email;
								$params['bcc'] = $bcc_email;
								$params['subject'] = $email_subject;
								$params['email_content'] = $email_content;
								$params['module_name'] = 'User login';
								$params['record_id'] = $userId ?? 0;
								$isSent = CustomHelper::sendCommonMail($params);
							}
							$success = true;
							$redirect_url = route('account.otp');
							$message = 'Otp sent successfully on '.$Tempuser->email;
					} else {
						$message = 'Your account is currently Deactivated, Please contact Administrator.';
					}
				} else {
					$message = 'Your password do not match please try again';
				}
			}
			$response = [];
			$statusCode = 400;
			if($success) {
				$statusCode = 200;
				if($redirect_url) {
					$redirect_url = str_replace(url('/'), '', $redirect_url);
					if(empty($redirect_url)) {
						$redirect_url = '/';
					}
					$response['redirect_url'] = $redirect_url;
				}
				if($location_url) {
					$response['location_url'] = $location_url;
				}
				$response['userInfo'] = CustomHelper::getUserInfo();
			} else if(empty($message)) {
				$message = 'The login information you have provided could not be found. Please review your login details. If you are a new user, kindly sign up first.';
			}
			$response['success'] = $success;
			$response['message'] = $message;			
			return Response::json($response, $statusCode);
		}

		$seo_data['meta_title'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $seo_data['meta_description'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        $data['FORGOT_PASSWORD_URL'] = route('account.forgot-password');
        $data['GOOGLE_LOGIN_URL'] = route('social_login',['provider' => 'google','type'=>'customer']);
        if(CustomHelper::isOnlineBooking()) {
        	$data['ACCOUNT_SIGNUP_URL'] = route('account.signup');
        }
        if(CustomHelper::isAllowedModule('agentuser')) {
        	$data['AGENT_SIGNUP_URL'] = route('account.agent_signup');
        }
        if(CustomHelper::isAllowedModule('vendor')) {
        	$data['VENDOR_LOGIN_URL'] = route('vendorlogin');
        }
        $data['seo_data'] = $seo_data;
		View::share('seo_data', $seo_data);
        $data['flash_messages'] = CustomHelper::getFlashMessages();

        if(session()->has('social_signup')) {
        	Session::forget('social_signup');
        	Session::forget('social_email');
        	Session::forget('social_name');        	
        }
        return Inertia::render('account/login', $data);
	}


	public function vendorlogin(Request $request) {
		$data = [];
		$seo_data = [];

		$seo_data['meta_title'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
		$seo_data['meta_description'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        
        $data['GOOGLE_LOGIN_URL'] = route('social_login',['provider' => 'google','is_vendor'=>1]);
        if(CustomHelper::isOnlineBooking()) {
        	$data['ACCOUNT_SIGNUP_URL'] = route('account.signup');
        }
        if(CustomHelper::isAllowedModule('agentuser')) {
        	$data['AGENT_SIGNUP_URL'] = route('account.agent_signup');
        }
        if(CustomHelper::isAllowedModule('vendor')) {
        	$data['VENDOR_LOGIN_URL'] = route('vendorlogin');
        }

        $data['ACTION_URL'] = route('adminLogin');

        $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

        $data['FORGOT_PASSWORD_URL'] = '/'.$ADMIN_ROUTE_NAME.'/reset-password';
        $data['VENDOR_AJAX_AUTH_URL'] = '/'.$ADMIN_ROUTE_NAME.'/vendor-ajax-auth';        
        $data['VENDOR_LOGIN_OTP_CHECK_URL'] = '/'.$ADMIN_ROUTE_NAME.'/vendor-login-otp-check';        
        $data['csrfToken'] = csrf_token();

        $data['seo_data'] = $seo_data;
		View::share('seo_data', $seo_data);
        $data['flash_messages'] = CustomHelper::getFlashMessages();
		return Inertia::render('account/vendorlogin', $data);
	}

	public function signup(Request $request) {
		$data = [];
		$seo_data = [];
		if($request->referer) {
			session(['referer'=>$request->referer]);
		}
		if($request->redirect_url) {
			session(['redirect_url'=>$request->redirect_url]);
		}
		$method = $request->method();
		if($method == 'POST') {
			$success = false;
			$message = '';
			$redirect_url = '';
			
			$RECAPTCHA_FRONT_DISABLED = CustomHelper::WebsiteSettings('RECAPTCHA_FRONT_DISABLED')??0;
			$nicknames = [
				'name' => 'Name',
				'phone' => 'Phone',
				'email' => 'Email',
				'password' => 'Password',
				'confirm_password' => 'Confirm Password',
				'g-recaptcha-register' => 'Security Code'
			];

			$social_signup = (session()->has('social_signup'))?session('social_signup'):0;
			$rules = [];
			
			$rules['name'] = 'required|max:100';
			if($social_signup == 1) {
				$rules['password'] = 'nullable|min:6|max:12';
				$rules['confirm_password'] = 'nullable|same:password';
			} else {
				$rules['email'] = 'required|email|unique:users,email|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
				$rules['password'] = 'required|min:6|max:12';
				$rules['confirm_password'] = 'required|same:password';
			}
			$name = $request->name??'';
			$phone = $request->phone??'';
			$country_code = 91;
            if($request->country_code) {
                $country_code = $request->country_code??91;
            } else if($request->country) {
                $country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
            }

            if(empty($phone)) {
                $rules['phone'] = 'required';
            } else {
                if(!empty($country_code) && $country_code != 91) {
                    $rules['phone'] = 'regex:/^\d{4,12}$/';
                } else {
                    $rules['phone'] = 'digits:10';
                }
            }
            if($RECAPTCHA_FRONT_DISABLED == 0) {
            	$rules['g-recaptcha-register'] = 'required';
            }
            $validation_msg = [];
            $validation_msg['required'] = ':attribute is required.';
            $validation_msg['digits'] = ':attribute must be :digits digits.';
            $validation_msg['min'] = ':attribute should be minimum :min character.';
            $validation_msg['max'] = ':attribute should not be greater than :max character.';
            $validation_msg['unique'] = 'We\'are sorry. This login email already exists. Please try a different email to register, or login to your existing account.';

            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
			if($validator->fails()) {
				return Response::json(array(
					'success' => false,
					'errors' => $validator->getMessageBag()->toArray()
                ), 400);
			}
			
            $captchaSuccess = false;
            if($RECAPTCHA_FRONT_DISABLED == 1) {
            	$captchaSuccess = true;
            } else {
            	$token = $request['g-recaptcha-register'];
            	$captchaResp = CustomHelper::checkGoogleReCaptcha($token);
            	$captchaSuccess = false;
            	if(!empty($captchaResp) && $captchaResp['success'] == 1) {
            		$captchaSuccess = true;
            		$RECAPTCHA_FRONT_SCORE = CustomHelper::WebsiteSettings('RECAPTCHA_FRONT_SCORE')??0.5;
            		if($captchaResp["score"] < $RECAPTCHA_FRONT_SCORE) {
            			$captchaSuccess = false;
            		}
            	}
            }

			if($captchaSuccess) {

				if($social_signup == 1) {
					$insertUser = [];
					$user_record['name'] = $name;
					$user_record['email'] = (session()->has('social_email'))?session('social_email'):'';
					$user_record['country_code'] = $country_code;
					$user_record['phone'] = $phone;
					$user_record['is_verified'] = 1;
					$user_record['status'] = 1;
					$user_record['password'] = bcrypt($request->password);

					$params = [];
					$user = User::CreateUser($user_record, $params);
					if($user['success']) {
						Session::forget('social_signup');
						Session::forget('social_email');
						Session::forget('social_name');

						$user_id = $user['id'];
						$email = $user['email']??'';
						if($email) {
							TempUser::where('email',$email)->delete();
						}
						Auth::loginUsingId($user_id);
						$success = true;
						$redirect_url = $this->getRedirectUrlAfterLogin();
						if(empty($redirect_url)) {
							$redirect_url = route('user.profile');
						}
					} else {
						$message = $user['msg']??'Something went wrong, please try again.';
					}
				} else {
					$is_saved = $this->user_save($request, '');
					if($is_saved) {
						$success = true;
						$referer = (isset($request->referer))?$request->referer:'';
						$redirect_url = route('account.signup_otp',['referer'=>$referer]);
						Session::flash('alert-success', 'Otp sent successfully on '.$request->email);
					}
				}
			} else {
				$message = 'Invalid form request.';
			}

			$response = [];
			$statusCode = 400;
			if($success) {
				$statusCode = 200;
				if($redirect_url) {
					$redirect_url = str_replace(url('/'), '', $redirect_url);
					if(empty($redirect_url)) {
						$redirect_url = '/';
					}
					$response['redirect_url'] = $redirect_url;
				}
				$response['userInfo'] = CustomHelper::getUserInfo();
			} else if(empty($message)) {
				$message = 'Something went wrong, please try again.';
			}
			$response['success'] = $success;
			$response['message'] = $message;			
			return Response::json($response, $statusCode);

		}
		$data['countries'] = Country::where('status',1)->get();
		$data['is_agent'] = 0;		

		$data['SIGNUP_URL'] = '/account/signup';
		$data['LOGIN_URL'] = route('account.login');
		$data['GOOGLE_LOGIN_URL'] = route('social_login',['provider' => 'google','type'=>'customer']);
        $data['seo_data'] = $seo_data;
		View::share('seo_data', $seo_data);
        $data['flash_messages'] = CustomHelper::getFlashMessages();

		$social_signup = (session()->has('social_signup'))?session('social_signup'):0;
		$social_name = '';
		$social_email = '';
		if($social_signup == 1) {
			$social_name = (session()->has('social_name'))?session('social_name'):'';
			$social_email = (session()->has('social_email'))?session('social_email'):'';
		}
		$data['social_signup'] = $social_signup;
		$data['social_name'] = $social_name;
		$data['social_email'] = $social_email;
		return Inertia::render('account/signup', $data);
	}

	public function agent_signup(Request $request) {
		$data = [];
		$seo_data = [];
		$method = $request->method();
		if($method == 'POST') {
			$success = false;
			$message = '';
			$redirect_url = '';

			$RECAPTCHA_FRONT_DISABLED = CustomHelper::WebsiteSettings('RECAPTCHA_FRONT_DISABLED')??0;
			$social_signup = (session()->has('social_signup'))?session('social_signup'):0;

			$nicknames = [
				'name' => 'Name',
				'phone' => 'Phone',
				'email' => 'Email',
				'password' => 'Password',
				'confirm_password' => 'Confirm Password',
				'g-recaptcha-register' => 'Security Code'
			];

			$rules = [];
			$rules['name'] = 'required|max:100';
			if($social_signup == 1) {
				$rules['password'] = 'nullable|min:6|max:12';
				$rules['confirm_password'] = 'nullable|same:password';
			} else {
				$rules['email'] = 'required|email|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
				$rules['password'] = 'required|min:6|max:12';
				$rules['confirm_password'] = 'required|same:password';
			}

			$name = $request->name??'';
			$phone = $request->phone??'';
			$country_code = 91;
            if($request->country_code) {
                $country_code = $request->country_code??91;
            } else if($request->country) {
                $country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
            }

            if(empty($phone)) {
                $rules['phone'] = 'required';
            } else {
                if(!empty($country_code) && $country_code != 91) {
                    $rules['phone'] = 'regex:/^\d{4,12}$/';
                } else {
                    $rules['phone'] = 'digits:10';
                }
            }
            $whatsapp_phone = $request->whatsapp_phone??'';
            $whatsapp_country_code = 91;
            if($request->whatsapp_country_code) {
                $whatsapp_country_code = $request->whatsapp_country_code??91;
            } else if($request->country) {
                $whatsapp_country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
            }
            if(empty($whatsapp_phone)) {
                $rules['whatsapp_phone'] = 'nullable';
            } else {
                if(!empty($whatsapp_country_code) && $whatsapp_country_code != 91) {
                    $rules['whatsapp_phone'] = 'regex:/^\d{4,12}$/';
                } else {
                    $rules['whatsapp_phone'] = 'digits:10';
                }
            }
            if($RECAPTCHA_FRONT_DISABLED == 0) {
            	$rules['g-recaptcha-register'] = 'required';
            }

			$validation_msg = [];
            $validation_msg['required'] = ':attribute is required.';
            $validation_msg['digits'] = ':attribute must be :digits digits.';
            $validation_msg['min'] = ':attribute should be minimum :min character.';
            $validation_msg['max'] = ':attribute should not be greater than :max character.';

            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
			if($validator->fails()) {
				return Response::json(array(
					'success' => false,
					'errors' => $validator->getMessageBag()->toArray()
                ), 400);
			}
			
            $captchaSuccess = false;
	        if($RECAPTCHA_FRONT_DISABLED == 1){
	        	$captchaSuccess = true;
	        } else {
				$token = $request['g-recaptcha-register'];
				$captchaResp = CustomHelper::checkGoogleReCaptcha($token);
				$captchaSuccess = false;
				if(!empty($captchaResp) && $captchaResp['success'] == 1) {
					$captchaSuccess = true;
					$RECAPTCHA_FRONT_SCORE = CustomHelper::WebsiteSettings('RECAPTCHA_FRONT_SCORE')??0.5;
					if($captchaResp["score"] < $RECAPTCHA_FRONT_SCORE) {
						$captchaSuccess = false;
					}
				}
			}

			if($captchaSuccess) {
				$email = $request->email??'';
				$userEmail = '';
				if(isset($email) && !empty($email)){
					$user = User::where('email',$email)->first();
					if(isset($user) && !empty($user)){
						if($user->is_agent == 1){
							if($user->approved_agent == 1) {
								$message = 'Your application has already been verified for agent, please login.';
							}else{
								$agentInfo = $user->agentInfo??[];
								if($agentInfo) {
									$message = 'Your application for agent is under review, please contact administrator.';
								} else {
									$message = 'The email has already been taken, please login.';
								}
							}
						} else {
							$userEmail = $user->email;
						}
					}
				}


				if($social_signup == 1) {
					$user_record = [];
					$user_record['name'] = $name;
					$user_record['email'] = (session()->has('social_email'))?session('social_email'):'';
					$user_record['country_code'] = $country_code;
					$user_record['phone'] = $phone;
					$user_record['is_verified'] = 1;
					$user_record['is_agent'] = 1;
					$user_record['status'] = 1;
					$user_record['password'] = bcrypt($request->password);
					$params = [];
					$user = User::CreateUser($user_record, $params);
					if($user['success']) {
						Session::forget('social_signup');
						Session::forget('social_email');
						Session::forget('social_name');

						$user_id = $user['id'];
						$email = $user['email']??'';
						if($email) {
							TempUser::where('email',$email)->delete();
						}
						Auth::loginUsingId($user_id);
						session(['old_user_id'=>$user_id]);

						$success = true;
						$redirect_url = route('user.agent_signup_2');
					} else {
						$message = $user['msg']??'Something went wrong, please try again.';
					}
				} else {
					$is_saved = $this->user_save($request, $userEmail);
					if($is_saved) {
						$referer = (isset($request->referer))?$request->referer:'';
						$success = true;
						$redirect_url = route('account.signup_otp',['referer'=>$referer]);
						Session::flash('alert-success', 'Otp sent successfully on '.$request->email);
					}
				}
			} else {
				$message = 'Invalid form request.';
			}

			$response = [];
			$statusCode = 400;
			if($success) {
				$statusCode = 200;
				if($redirect_url) {
					$redirect_url = str_replace(url('/'), '', $redirect_url);
					if(empty($redirect_url)) {
						$redirect_url = '/';
					}
					$response['redirect_url'] = $redirect_url;
				}
				$response['userInfo'] = CustomHelper::getUserInfo();
			} else if(empty($message)) {
				$message = 'Something went wrong, please try again.';
			}
			$response['success'] = $success;
			$response['message'] = $message;			
			return Response::json($response, $statusCode);

		}
		$data['countries'] = Country::where('status',1)->get();
		$data['isAgent'] = 1;

		$data['SIGNUP_URL'] = '/account/agent-signup';
		$data['LOGIN_URL'] = route('account.login');
		$data['GOOGLE_LOGIN_URL'] = route('social_login',['provider' => 'google','type'=>'customer','is_agent'=>1]);
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);
        $data['flash_messages'] = CustomHelper::getFlashMessages();

        $social_signup = (session()->has('social_signup'))?session('social_signup'):0;
        $social_name = '';
        $social_email = '';
        if($social_signup == 1) {
        	$social_name = (session()->has('social_name'))?session('social_name'):'';
        	$social_email = (session()->has('social_email'))?session('social_email'):'';
        }
        $data['social_signup'] = $social_signup;
        $data['social_name'] = $social_name;
        $data['social_email'] = $social_email;

		return Inertia::render('account/signup', $data);
	}

	public function user_save(Request $request, $userEmail=''){
		$referer = $request->referer??'';
		$is_agent = $request->is_agent??0;
		$old_user_id = $request->old_user_id??0;
		if(!empty($userEmail)){
			session(['user_becomig_agent_email' => $userEmail]);
			$user = User::where('email',$userEmail)->first();
			if(empty($user)) {

			}
		} else {
			$user = new TempUser;
		}
		$verify_token = generateToken(40);
		$image = NULL;
		$pan_image = NULL;
		$password = NULL;
		if($request->password) {
			$password = bcrypt($request->password);
		}
		$otp = rand(10000,99999);
		session(['signup_otp' => $otp]);
		$country_code = 91;
		if($request->country_code) {
			$country_code = $request->country_code??91;
		} else if($request->country) {
			$country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
		}

		$whatsapp_country_code = 91;
		if($request->whatsapp_country_code) {
			$whatsapp_country_code = $request->whatsapp_country_code??91;
		} else if($request->country) {
			$whatsapp_country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
		}

		if(empty($old_user_id)){
			$user->name = $request->name;
			$user->email = $request->email;
			$user->password = $password;
			$user->verify_token = $verify_token;
			$user->referrer = $referer;
			$user->status = 0;
			$user->is_verified = 0;
			$user->otp = $otp;
			$user->dob = ($request->dob)?Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'):'';
			$user->gender = $request->gender??'';
			$user->phone = $request->phone;
			$user->country_code = $country_code;
			$user->is_agent = $is_agent;

			$is_saved = 0;
			if($request->hasFile('profile_image')){
				$imgData = $request->file('profile_image');
				$path = 'users/';
				$thumb_path = 'users/thumb/';
				$storage = Storage::disk('public');

				$IMG_HEIGHT = 800;
				$IMG_WIDTH = 800;
				$THUMB_HEIGHT = 400;
				$THUMB_WIDTH = 400;

				// $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:800;
				// $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:800;
				// $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
				// $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

				$uploaded_data = CustomHelper::UploadImage($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
				if($uploaded_data['success']){
					$image = $uploaded_data['file_name'];
					$user->profile_image = $image;
				}
			}
			$is_saved = $user->save();
		} else {
			$is_saved = 1;
		}

		if($is_saved){
			$userId = $user->id ?? 0;
			if($userId == '' || $userId == 0){
				$userId = $request->old_user_id ?? 0;
			}
			session(['userId' => $userId]);

			if(!empty($old_user_id)){
				$req_data = [];
				if($request->hasFile('pan_image')){
					$imgData = $request->file('pan_image');
					$path = 'agentuser/';
					$thumb_path = 'agentuser/thumb/';
					$storage = Storage::disk('public');

					// $IMG_HEIGHT = 800;
					// $IMG_WIDTH = 800;
					// $THUMB_HEIGHT = 400;
					// $THUMB_WIDTH = 400;

					// $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:800;
					// $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:800;
					// $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
					// $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

					// $uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
					//PARAMETER GETTING ONLY 3 IN CustomHelper::UploadFile
					$uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='');

					if($uploaded_data['success']){
						$pan_image = $uploaded_data['file_name'];
					}
				}
				if($request->hasFile('gst_image')){
					$imgData = $request->file('gst_image');
					$path = 'agentuser/';
					$thumb_path = 'agentuser/thumb/';
					$storage = Storage::disk('public');

					// $IMG_HEIGHT = 800;
					// $IMG_WIDTH = 800;
					// $THUMB_HEIGHT = 400;
					// $THUMB_WIDTH = 400;

					// $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:800;
					// $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:800;
					// $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
					// $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

					// $uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
					//PARAMETER GETTING ONLY 3 IN CustomHelper::UploadFile
					$uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='');

					if($uploaded_data['success']){
						$gst_image = $uploaded_data['file_name'];
					}
				}

				$req_data['user_id'] = $userId ?? 0;
				$req_data['company_name'] = $request->company_name ?? '';
				$req_data['company_brand'] = $request->company_brand ?? '';
				$req_data['pan_no'] = $request->pan_no ?? '';
				$req_data['gst_no'] = $request->gst_no ?? '';
				$req_data['company_owner_name'] = $request->company_owner_name ?? '';
				$req_data['pan_image'] = $pan_image??NULL;
				$req_data['gst_image'] = $gst_image??NULL;
				$req_data['bookings_per_month'] = $request->bookings_per_month ?? '';
				$req_data['sales_team_size'] = $request->sales_team_size ?? '';
				$req_data['source'] = $request->source ?? '';
				$req_data['website'] = $request->website ?? '';
				$req_data['whatsapp_phone'] = $request->whatsapp_phone ?? '';
				$req_data['whatsapp_country_code'] = $whatsapp_country_code;
				$req_data['query'] = $request->queries ?? '';
				$req_data['destinations_sell_most'] = $request->destinations_sell_most ?? '';
				$req_data['agency_age'] = $request->agency_age ?? '';
				$req_data['no_of_employees'] = $request->no_of_employees ?? 0;
				$req_data['region'] = $request->region ?? '';
				$req_data['company_profile'] = $request->company_profile ?? '';

				$isSaved = UserAgentInfo::create($req_data);
			}

			$attachments = [];

			$name = isset($request->name) ? $request->name : '';
			$email = isset($request->email) ? $request->email : '';

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

			$email_params = array(
				'{date}' => date("l jS \of F Y h:i A"),
				'{otp}' => $otp,
				'{name}' => $name,
				'{email}' => $email,
				'{header}' => $common_logo,
				'{contact_details}' => $contact_details??'',
				'{type}' => 'Sign up OTP',
			);

			$template_slug = 'user-signup-otp';
			$ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
			$email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
			$email_content = isset($email_content_data->content) ? $email_content_data->content : '';
			// $email_params = isset($email_params) ? $email_params : [];
			$email_content = strtr($email_content, $email_params);
			$email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

			$REPLYTO = $ADMIN_EMAIL;
			$to_email = $email;
			$cc_email = '';
			$bcc_email = '';

			if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
				$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
			}

			if(isset($email_content_data) && !empty($email_content_data)){
				$params = [];
				$params['to'] = $to_email;
				$params['cc'] = $cc_email;
				$params['bcc'] = $bcc_email;
				$params['subject'] = $email_subject;
				$params['email_content'] = $email_content;
				$params['module_name'] = 'User sign up';
				$params['record_id'] = $userId ?? 0;
				$isSent = CustomHelper::sendCommonMail($params);
			}
		}
		return $is_saved;
	}

	/* User Signup otp page */
	public function signup_otp(Request $request) {
		$data = [];
		$seo_data = [];
		$user_id = '';
		$userId = '';
		$userEmail = '';
		$CreateUser = '';
		if(Session::has('userId')){
			$userId = Session::get('userId');
		}
		if(!empty($userId)) {
			$userDetails = [];
			if(Session::has('user_becomig_agent_email')){
				$sessionEmail = Session::get('user_becomig_agent_email');
				$userDetails = User::where('email',$sessionEmail)->first();
				if($userDetails) {
					$user_id = $userDetails->id;
					$userEmail = $userDetails->email;
				}
			} else {
				$userDetails = TempUser::find($userId);
			}
			if(!empty($userDetails)) {
				if($userDetails->is_verified != 0) {
					return Inertia::render('account/login', $data);
					//return redirect(url('account/login'));
				}

				$method = $request->method();
				if($method == 'POST'){
					$success = false;
					$message = '';
					$redirect_url = '';

					$nicknames = [
						'otp' => 'OTP'
					];
					$rules = [];
					$rules['otp'] = 'required|numeric|min:5';

					$validation_msg = [];
					$validation_msg['required'] = ':attribute is required.';
					$validation_msg['numeric'] = ':attribute must be numbers.';
					$validation_msg['min'] = ':attribute must be :min character.';
					$validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
					if($validator->fails()) {
						return Response::json(array(
							'success' => false,
							'errors' => $validator->getMessageBag()->toArray()
						), 400);
					}
					$referer = (isset($request->referer))?$request->referer:'';
					$DEFAULT_OTP = CustomHelper::WebsiteSettings('DEFAULT_OTP');
					if($userDetails->otp == $request->otp || $request->otp == $DEFAULT_OTP) {
						Session::forget('signup_otp');
						Session::forget('user_becomig_agent_email');
						if(isset($userEmail) && !empty($userEmail)) {
							$affectedRows = User::where("id", $userId)->update(["is_verified" => 1,"otp" => NULL,"status" => 1,"email_verified_at" => date('Y-m-d H:i:s')]);
						} else {
							$affectedRows = TempUser::where("id", $userId)->update(["is_verified" => 1,"otp" => NULL,"status" => 1,"email_verified_at" => date('Y-m-d H:i:s')]);
						}
						if($affectedRows){
							if(empty($userEmail)){
								$user_record = $userDetails->toArray();
								$user_record['is_verified'] = 1;
								$user_record['status'] = 1;
								$user_record['password'] = $userDetails->password;
								$params = [];
								$user = User::CreateUser($user_record, $params);
								if($user['success']) {
									$user_id = $user['id'];
									$email = $user['email']??'';
									if($email) {
										TempUser::where('email',$email)->delete();
									}
								} else {
									$message = $user['msg']??'Something went wrong, please try again.';
								}
							}

							if($user_id) {
								if($userDetails->is_agent == '0' || ($userDetails->is_agent == '1' && $userDetails->approved_agent==1)){
									Session::forget('userId');
									Auth::loginUsingId($user_id);
									$redirect_url = $this->getRedirectUrlAfterLogin();
									if(empty($redirect_url)) {
										$redirect_url = route('user.profile');
									}
									$success = true;
								} else {
									Session::forget('userId');
									session(['old_user_id'=>$user_id]);
									Auth::loginUsingId($user_id);
									$success = true;
									$redirect_url = route('user.agent_signup_2');
								}
							} else {
								$message = 'Account not found.';
							}
						} else {
							$message = 'Something went wrong, please try again.';
						}
					} else {
						$message = 'Your otp did not matched, please try again.';
					}

					$response = [];
					$statusCode = 400;
					if($success) {
						$statusCode = 200;
						if($redirect_url) {
							$redirect_url = str_replace(url('/'), '', $redirect_url);
							if(empty($redirect_url)) {
								$redirect_url = '/';
							}
							$response['redirect_url'] = $redirect_url;
						}
						$response['userInfo'] = CustomHelper::getUserInfo();
					} else if(empty($message)) {
						//$message = 'We can not find your existence in the system! Please Signup first.';
						$message = "We couldn't find your information in the system. Please sign up first to create an account.";
					}
					$response['success'] = $success;
					$response['message'] = $message;			
					return Response::json($response, $statusCode);
				}
				return Inertia::render('account/otp', $data);
				//return view(config('custom.theme').'.account.otp', $data);
			} else {
				return Inertia::render('account/signup', $data);
				//return redirect(route('account.signup'));
			}
		} else {
			return Inertia::render('account/signup', $data);
			//return redirect(route('account.signup'));
		}

        $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
        $data['flash_messages'] = CustomHelper::getFlashMessages();		
		return Inertia::render('account/otp', $data);
	}

	public function resend_otp(Request $request) {
		$success = false;
		$message = '';
		$redirect_url = '';
		if(Session::has('userId')) {
			$userId = Session::get('userId');
			if(Session::has('user_becomig_agent_email')) {
				$sessionEmail = Session::get('user_becomig_agent_email');
				$userDetails = User::where('email',$sessionEmail)->first();
				if($userDetails) {
					$userId = $userDetails->id;
				}
			} else {
				$userDetails = TempUser::find($userId);
			}

			if(!empty($userDetails)) {
				$otp = rand(10000,99999);
				session(['signup_otp' => $otp]);

				$userDetails->otp = $otp;
				$userDetails->save();			

				$email = $userDetails->email;
				session(['forgot_email' => $email]);

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

				$email_params = array(
					'{date}' => date("l jS \of F Y h:i A"),
					'{otp}' => $otp,
					'{name}' => $userDetails->name,
					'{email}' => $userDetails->email,
					'{header}' => $common_logo,
					'{contact_details}' => $contact_details??'',
					'{type}' => 'Resend OTP',
				);

				$template_slug = 'user-signup-otp';
				$ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
				$email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

				$email_content = isset($email_content_data->content) ? $email_content_data->content : '';
				$email_content = strtr($email_content, $email_params);
				$email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

				$REPLYTO = $ADMIN_EMAIL;
				$to_email = $email;
				$cc_email = '';
				$bcc_email = '';

				if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1) {
					$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
				}

				if(isset($email_content_data) && !empty($email_content_data)) {
					$params = [];
					$params['to'] = $to_email;
					$params['cc'] = $cc_email;
					$params['bcc'] = $bcc_email;
					$params['subject'] = $email_subject;
					$params['email_content'] = $email_content;
					$params['module_name'] = 'User resend otp';
					$params['record_id'] = $userId ?? 0;
					$isSent = CustomHelper::sendCommonMail($params);
				}
				$success = true;
				Session::flash('alert-success', 'OTP has been successfully resent to '.$userDetails->email.' to Verify your Account');
			} else {
				$message = 'Invalid Request.';
			}
		}

		$response = [];
		$statusCode = 400;
		if($success) {
			$statusCode = 200;
			if($redirect_url) {
				$redirect_url = str_replace(url('/'), '', $redirect_url);
				if(empty($redirect_url)) {
					$redirect_url = '/';
				}
				$response['redirect_url'] = $redirect_url;
			}
			$response['userInfo'] = CustomHelper::getUserInfo();
		} else if(empty($message)) {
			$message = 'Something went wrong, please try again.';
		}
		$response['success'] = $success;
		$response['message'] = $message;
		$response['flash_messages'] = CustomHelper::getFlashMessages();
		return Response::json($response, $statusCode);
	}

	public function forgotPassword(Request $request) {
        $data = [];
        $seo_data = [];
        $method = $request->method();
		if($method == 'POST') {
			$success = false;
			$message = '';
			$redirect_url = '';

			$RECAPTCHA_FRONT_DISABLED = CustomHelper::WebsiteSettings('RECAPTCHA_FRONT_DISABLED')??0;

			$nicknames = [
				'forgot_email' => 'Email',
				'g-recaptcha-forgot' => 'Security Code'
			];
			$rules = [];
			$validation_msg = [];
			$rules['forgot_email'] = 'required|email';
			if($RECAPTCHA_FRONT_DISABLED == 0) {
				$rules['g-recaptcha-forgot'] = 'required';
			}
			$validation_msg['required'] = ':attribute is required.';
			$validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
			if($validator->fails()) {
				return Response::json(array(
					'success' => false,
					'errors' => $validator->getMessageBag()->toArray()
                ), 400); // 400 being the HTTP code for an invalid request.
			}

			$token = $request['g-recaptcha-forgot'];
			$captchaResp = CustomHelper::checkGoogleReCaptcha($token);
			$captchaSuccess = false;
			if($RECAPTCHA_FRONT_DISABLED == 1) {
				$captchaSuccess = true;
			} else {
				if(!empty($captchaResp) && $captchaResp['success'] == 1) {
					$captchaSuccess = true;
					$RECAPTCHA_FRONT_SCORE = CustomHelper::WebsiteSettings('RECAPTCHA_FRONT_SCORE')??0.5;
					if($captchaResp["score"] < $RECAPTCHA_FRONT_SCORE) {
						$captchaSuccess = false;
					}
				}
			}
			$captchaSuccess = true;
			if(!$captchaSuccess) {
				return redirect()->back()->withInput()->with('alert-danger', 'Invalid form request.');
			}

			$email = $request->forgot_email;
			$user = User::where('email', $email)->first();

			if(!empty($user)) {
				$otp = rand(10000,99999);
				session(['forgot_email'=> $user->email, 'forgot_otp' => $otp]);

				User::where("id", $user->id)->update(["otp" => $otp]);

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

                $email_params = array(
					'{date}' => date("l jS \of F Y h:i A"),
                    '{otp}' => $otp,
                    '{name}' => $user->name,
                    '{email}' => $user->email,
					'{header}' => $common_logo,
					'{contact_details}' => $contact_details??'',
					'{type}' => 'Forgot Password OTP',
                );

	                $template_slug = 'user-signup-otp';
	                $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

	                $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
	                $email_content = strtr($email_content, $email_params);
	                $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

	                $to_email = $email;
	                $cc_email = '';
	                $bcc_email = '';

					if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
						$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
					}

					if(isset($email_content_data) && !empty($email_content_data)){
						$params = [];
						$params['to'] = $to_email;
						$params['cc'] = $cc_email;
						$params['bcc'] = $bcc_email;
						$params['subject'] = $email_subject;
						$params['email_content'] = $email_content;
						$params['module_name'] = 'User forgot password otp';
						$params['record_id'] = $user->id ?? 0;
						$is_mail = CustomHelper::sendCommonMail($params);
					}

					$success = true;
					$redirect_url = route('account.forgot_otp');

					Session::flash('alert-success', 'OTP has been successfully sent to '.$email.' to initiate the password reset process.');
			} else {
				$message = 'This email is not registered with us.';
			}
			$response = [];
			$statusCode = 400;
			if($success) {
				$statusCode = 200;
				if($redirect_url) {
					$redirect_url = str_replace(url('/'), '', $redirect_url);
					if(empty($redirect_url)) {
						$redirect_url = '/';
					}
					$response['redirect_url'] = $redirect_url;
				}
			} else if(empty($message)) {
				$message = 'We can not find your existence in the system! Please Signup first.';
			}
			$response['success'] = $success;
			$response['message'] = $message;			
			return Response::json($response, $statusCode);
		}

		$seo_data['meta_title'] = 'Forgot-Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $seo_data['meta_description'] = 'Forgot-Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
        $data['LOGIN_URL'] = route('account.login');
        return Inertia::render('account/forgot_password', $data);
    }

	/* User Forgot otp page */
	public function forgot_otp(Request $request){
		$data = [];
		$seo_data = [];

		$userEmail = "";
		if(Session::has('forgot_email')){
			$userEmail = Session::get('forgot_email');
		} else {
			// return Inertia::render('account/login', $data);
			return redirect(route('account.login'));
		}

		if(!empty($userEmail)){
			$userDetails = User::where('email',$userEmail)->first();

			$method = $request->method();
			if($method == 'POST') {
				$success = false;
				$message = '';
				$redirect_url = '';

				$nicknames = [];
				$nicknames['otp'] = 'OTP';
				$rules = [];
				$rules['otp'] = 'required|numeric|min:5';
				$validation_msg = [];
				$validation_msg['required'] = ':attribute is required.';
				$validation_msg['numeric'] = ':attribute must be numbers.';
				$validation_msg['min'] = ':attribute must be :min character.';
				$validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
				if($validator->fails()) {
					return Response::json(array(
						'success' => false,
						'errors' => $validator->getMessageBag()->toArray()
                	), 400); // 400 being the HTTP code for an invalid request.
				}

				$referer = (isset($request->referer))?$request->referer:'';
				if(!empty($userEmail)){
					$DEFAULT_OTP = CustomHelper::WebsiteSettings('DEFAULT_OTP');
					if($userDetails->otp == $request->otp || $request->otp == $DEFAULT_OTP){
						Session::forget('forgot_otp');
						$success = true;
						$redirect_url = route('account.change_password');
					} else {
						$message = 'Your otp did not matched, please try again.';
					}
				} else {
					$redirect_url = route('account.signup');
				}
				$response = [];
				$statusCode = 400;
				if($success) {
					$statusCode = 200;
					if($redirect_url) {
						$redirect_url = str_replace(url('/'), '', $redirect_url);
						if(empty($redirect_url)) {
							$redirect_url = '/';
						}
						$response['redirect_url'] = $redirect_url;
					}
				} else if(empty($message)) {
					$message = 'Something went wrong, please try again.';
				}
				$response['success'] = $success;
				$response['message'] = $message;			
				return Response::json($response, $statusCode);
			}
		}
		$seo_data['meta_title'] = 'OTP Verify - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $seo_data['meta_description'] = 'OTP Verify - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
        $data['flash_messages'] = CustomHelper::getFlashMessages();
        return Inertia::render('account/forgot_otp', $data);
	}

	public function forgot_resend_otp(Request $request) {
		$success = false;
		$message = '';
		$redirect_url = '';

		$forgotEmail = "";
		if(Session::has('forgot_email')) {
			$forgotEmail = Session::get('forgot_email');
			$userDetails = User::where('email',$forgotEmail)->first();
			if(!empty($userDetails)) {
				$otp = rand(10000,99999);
				session(['signup_otp' => $otp]);
				User::where("email", $forgotEmail)->update(["otp" => $otp]);
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

				$email_params = array(
					'{date}' => date("l jS \of F Y h:i A"),
					'{otp}' => $otp,
					'{name}' => $userDetails->name,
					'{email}' => $userDetails->email,
					'{header}' => $common_logo,
					'{contact_details}' => $contact_details??'',
					'{type}' => 'Forget Resend OTP',
				);

				$template_slug = 'user-signup-otp';
				$email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

				$email_content = isset($email_content_data->content) ? $email_content_data->content : '';
				$email_content = strtr($email_content, $email_params);
				$email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

				$to_email = $userDetails->email;
				$cc_email = '';
				$bcc_email = '';

				if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
					$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
				}

				if(isset($email_content_data) && !empty($email_content_data)){
					$params = [];
					$params['to'] = $to_email;
					$params['cc'] = $cc_email;
					$params['bcc'] = $bcc_email;
					$params['subject'] = $email_subject;
					$params['email_content'] = $email_content;
					$params['module_name'] = 'User forgot resend otp';
					$params['record_id'] = $userDetails->id ?? 0;
					$is_mail = CustomHelper::sendCommonMail($params);
				}
				$success = true;
				Session::flash('alert-success', 'OTP has been successfully resent to '.$forgotEmail.' to initiate the password reset process.');
			}
		}
		$response = [];
		$statusCode = 400;
		if($success) {
			$statusCode = 200;
			if($redirect_url) {
				$redirect_url = str_replace(url('/'), '', $redirect_url);
				if(empty($redirect_url)) {
					$redirect_url = '/';
				}
				$response['redirect_url'] = $redirect_url;
			}
			$response['userInfo'] = CustomHelper::getUserInfo();
		} else if(empty($message)) {
			$message = 'Something went wrong, please try again.';
		}
		$response['success'] = $success;
		$response['message'] = $message;
		$response['flash_messages'] = CustomHelper::getFlashMessages();
		return Response::json($response, $statusCode);
	}


	public function changePassword(Request $request) {
		$data = [];
		$seo_data = [];


		$forgotEmail = "";
		if(Session::has('forgot_email')){
			$forgotEmail = Session::get('forgot_email');
		}else{
			return Inertia::render('account/change-password', $data)->with('alert-danger', 'invalid request.');
			//return redirect(url('account/change-password'))->with('alert-danger', 'invalid request.');
		}

		$method = $request->method();
		$user = User::where('email', $forgotEmail)->first();

		if(!empty($user)){

			if($method == 'POST'){

				$success = false;
				$message = '';
				$redirect_url = '';


				$nicknames = [];
				$nicknames['password'] = 'Password';
				$nicknames['confirm_password'] = 'Confirm Password';

				$rules = [];
				$rules['password'] = 'required|min:6';
				$rules['confirm_password'] = 'required|same:password';
				$validation_msg = [];
				$validation_msg['required'] = ':attribute is required.';
				$validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
				if($validator->fails()) {
					return Response::json(array(
						'success' => false,
						'errors' => $validator->getMessageBag()->toArray()
                	), 400);
				}

				$email = $user->email;

				$password = bcrypt($request->password);

				$isSaved = User::where("email", $forgotEmail)->update(["password" => $password]);

				if($isSaved) {

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

						$operating_system = Agent::platform();
						$browser = Agent::browser();
						$ip_address = $request->ip() ?? '';

						$geoip = Location::get($ip_address);
						$country = isset($geoip->countryName) ? $geoip->countryName : '';
						$city = isset($geoip->cityName) ? $geoip->cityName : '';
						$postalCode = isset($geoip->zipCode) ? $geoip->zipCode : '';
						$location = '';
						if (!empty($country) && !empty($city) && !empty($postalCode)) {
							$location = $country . ',' . $city . ',' . $postalCode;
						}

						$email_params = array(
							'{name}' => $user->name,
							'{header}' => $common_logo??'',
                        	'{contact_details}' => $contact_details ?? '',
							'{operating_system}' => $operating_system,
							'{browser}' => $browser,
							'{ip_address}' => $ip_address,
							'{location}' => $location,
							'{date}' => date("l jS \of F Y h:i A"),
						);

						$template_slug = 'user-password-change';
                        $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                        $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
                        $email_content = strtr($email_content, $email_params);
                        $subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

						$to_email = $user->email;
						$cc_email = '';
						$bcc_email = '';

						if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
							$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
						}

						if(isset($email_content_data) && !empty($email_content_data)){
							$params = [];
							$params['to'] = $to_email;
							$params['cc'] = $cc_email;
							$params['bcc'] = $bcc_email;
							$params['subject'] = $subject;
							$params['email_content'] = $email_content;
							$params['module_name'] = 'User password changed';
							$params['record_id'] = $user->id ?? 0;
							$is_mail = CustomHelper::sendCommonMail($params);
						}
						Session::forget('forgot_email');


						Session::flash('alert-success', 'Your password has been successfully changed. Please proceed to log in.');

						$success = true;
						$redirect_url = route('account.login');


				}

				$response = [];
				$statusCode = 400;
				if($success) {
					$statusCode = 200;
					if($redirect_url) {
						$redirect_url = str_replace(url('/'), '', $redirect_url);
						if(empty($redirect_url)) {
							$redirect_url = '/';
						}
						$response['redirect_url'] = $redirect_url;
					}
				} else if(empty($message)) {
					$message = 'Something went wrong, please try again.';
				}
				$response['success'] = $success;
				$response['message'] = $message;			
				return Response::json($response, $statusCode);

			}
		} else {
			return back()->withInput()->with('alert-danger', 'Something went wrong.');
		}


		$seo_data['meta_title'] = 'Change Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $seo_data['meta_description'] = 'Change Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
        $data['flash_messages'] = CustomHelper::getFlashMessages();
		return Inertia::render('account/change_password', $data);
	}

	public function ajaxVerify(Request $request){

		$response = [];
		$response['success'] = false;

		$errors = [];

		$forgotEmail = "";
		if(Session::has('forgot_email')){
			$forgotEmail = Session::get('forgot_email');
		}else{
			$response['message'] = "invalid request.";
			return response()->json($response);
		}

		$method = $request->method();
		if($method == 'POST'){
			$rules = [];
			$rules['forgot_otp'] = 'required|numeric|min:5';

			$validator = Validator::make($request->all(), $rules);

			if($validator->passes()){

				$user = User::where('email', $forgotEmail)->first();

				if(!empty($user)){
					if($user->otp == $request->forgot_otp){

						Session::forget('forgot_otp');

						$affectedRows = User::where("email", $user->email)->update(["otp" => NULL]);
						if($affectedRows){
							$response['success'] = TRUE;
							$response['message'] = "Your Email is Verified to reset your password.";
						}
					}else{
						$response['success'] = FALSE;
						$response['message'] = "OTP doesn't match. Please try again.";
					}
				}else{
					$response['success'] = FALSE;
					$response['message'] = "Something went wrong.";
				}
			}else{
				$response['message'] = "Validation Failed !";
				$errors = $validator->errors();
			}
		}

		$response['errors'] = $errors;
		return response()->json($response);
	}

	public function ajaxReset(Request $request){

		$response = [];
		$response['success'] = false;

		$errors = [];

		$forgotEmail = "";
		if(Session::has('forgot_email')){
			$forgotEmail = Session::get('forgot_email');
		}else{
			$response['message'] = "invalid request.";
			return response()->json($response);
		}

		$method = $request->method();

		$user = User::where('email', $forgotEmail)->first();

		if(!empty($user)){

			if($method == 'POST'){

				$rules = [];

				$rules['reset_password'] = 'required|min:6';
				$rules['confirm_reset_pwd'] = 'required|same:reset_password';

				$validator = Validator::make($request->all(), $rules);

				if($validator->passes()){

					$email = $user->email;

					$password = bcrypt($request->reset_password);

					$isSaved = User::where("email", $forgotEmail)->update(["password" => $password]);

					if($isSaved){
						$response['success'] = TRUE;
						$response['message'] = "Your password has been updated successfully, please login.";

						Session::forget('forgot_email');
						Session::flash('alert-success', 'Your password has been updated successfully, please login.');

					}
				}else{
					$response['message'] = "Validation Failed !";
					$errors = $validator->errors();
				}
			}
		}else{
			$response['success'] = FALSE;
			$response['message'] = "Something went wrong.";
		}

		$response['errors'] = $errors;
		return response()->json($response);
	}


	public function vendorAjaxAuth(Request $request) {
        $success = false;
        $message = '';
        $redirect_url = '';
        $location_url = '';

        if($request->username && $request->password) {
            $websiteSettingsNamesArr = ['BACKEND_LOGIN_OTP_REQUIRED','BACKEND_LOGIN_DEVICE_RECOGNITION_IN_HOURS','OTP_EXPIRE_TIME'];
            $websiteSettingsArr = CustomHelper::websiteSettingsArray($websiteSettingsNamesArr);
            $validate = (isset($websiteSettingsArr['BACKEND_LOGIN_OTP_REQUIRED']))?$websiteSettingsArr['BACKEND_LOGIN_OTP_REQUIRED']->value:'';
            $expire_hours = (isset($websiteSettingsArr['BACKEND_LOGIN_DEVICE_RECOGNITION_IN_HOURS']))?$websiteSettingsArr['BACKEND_LOGIN_DEVICE_RECOGNITION_IN_HOURS']->value:'';

            $username = (isset($request->username))?$request->username:'';
            $is_vendor = (isset($request->is_vendor))?$request->is_vendor:'';
            $query = Admin::where('email',$username);

            if($is_vendor == 1) {
                $query->where('is_vendor',1);
            } else {
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
                    if($tokenData) {
                        $expire_date = $tokenData->expire_date;
                        if(strtotime(date('Y-m-d H:i:s')) < strtotime($expire_date)) {
                            $cookie = true;
                        }
                    }
                }

                if($cookie == true) {
                    $success = true;
                    $message = "No_OTP";
                } else {
                    if($validate == 'Yes') {
                        $viewData = [];
                        $data = [];
                        $response['htmlData'] = '';

                        $old_password = (isset($request->password))?$request->password:'';
                        $hash_chack = '';
                        $hash_chack = Hash::check($old_password, $adminData->password);
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

                                $success = true;
                                $message = "success";
                            } else {
                                $message = "Something went wrong, please try again.";
                            }
                        } else {
                            $message = "Login invalid";
                        }
                    } else {
                        $success = true;
                        $message = "No_OTP";
                    }
                }
            } else {
                $message = "The username you entered doesn't belong to an account. Please check your Username and try again.";
            }
        } else {
            $message = "Please Enter username and password";
        }

        $response = [];
        $statusCode = 400;
        if($success) {
            $statusCode = 200;
            if($redirect_url) {
                $redirect_url = str_replace(url('/'), '', $redirect_url);
                if(empty($redirect_url)) {
					$redirect_url = '/';
				}
                $response['redirect_url'] = $redirect_url;
            }
            if($location_url) {
                $response['location_url'] = $location_url;
            }
            $response['userInfo'] = CustomHelper::getUserInfo();
        } else{
            if(empty($message)) {
                $message = 'Something went wrong, please try again.';
            }
            $params['user_name'] = $username??'';
            $params['action'] = 'Login Failed';
            $params['msg'] = $message;
            CustomHelper::RecordLoginHistory($params);
        }
        $response['success'] = $success;
        $response['message'] = $message;            
        return Response::json($response, $statusCode);
    }


    public function vendorLoginOtpCheck(Request $request) {
        $success = false;
        $message = '';
        $redirect_url = '';
        $location_url = '';
        if(Session::get('email')) {
            if($request->input('otp')) {
                $user = Admin::where(['email' => Session::get('email')])->first();
                $otp = $request->input('otp');
                $otp_expiry_time = isset($user->otp_expiry_time) ? $user->otp_expiry_time : '';
                $current_time = date('Y-m-d H:i:s');
                if(strtotime($current_time) > strtotime($otp_expiry_time)) {
                    $message = "OTP has expired. Please generate a new OTP and try again";
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
                            $success = true;
                        }

                    } else {
                        $message = "OTP does not match.";
                    }
                }
            } else {
                $message = "OTP is required.";
            }
        } else {
            $message = "Invalid Login Credentials";                    
        }

        $response = [];
        $statusCode = 400;
        if($success) {
            $statusCode = 200;
            if($redirect_url) {
                $redirect_url = str_replace(url('/'), '', $redirect_url);
                if(empty($redirect_url)) {
					$redirect_url = '/';
				}
                $response['redirect_url'] = $redirect_url;
            }
            if($location_url) {
                $response['location_url'] = $location_url;
            }
            $response['userInfo'] = CustomHelper::getUserInfo();
        } else{
            if(empty($message)) {
                $message = 'Something went wrong, please try again.';
            }
            $params['user_name'] = $username??'';
            $params['action'] = 'Login Failed';
            $params['msg'] = $message;
            CustomHelper::RecordLoginHistory($params);
        }
        $response['success'] = $success;
        $response['message'] = $message;            
        return Response::json($response, $statusCode);
    }


/* end of controller */
}
