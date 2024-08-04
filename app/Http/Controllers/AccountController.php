<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

//use Socialite;

class AccountController extends Controller {

	/**
	 * URL: /account
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */

	// private $fbAppId;
	// private $fbAppSecret;

	public function __construct(){
		$this->middleware('guest');

		// $this->fbAppId = '392770148048906';
		// $this->fbAppSecret = '49e7e29d59cfc0ce44776bf7466167e0';

		$referer = (isset(request()->referer))?request()->referer:'';

		if(!empty($referer)){
			session(['referer'=>$referer]);
		}
	}

	public function index(){
		echo "index"; die;
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

	public function login(Request $request) {
		$data = [];
		if($request->referer) {
			session(['referer'=>$request->referer]);
		}
		if($request->redirect_url) {
			session(['redirect_url'=>$request->redirect_url]);
		}
		$method = $request->method();
		if($method == 'POST') {
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
			$this->validate($request, $rules, $validation_msg, $nicknames);

            $captchaSuccess = false;
            // prd($RECAPTCHA_FRONT_DISABLED);
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
			if(!$captchaSuccess) {
				return redirect()->back()->withInput()->with('alert-danger', 'Invalid form request.');
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
							return redirect(route('user.agent_signup_2'));
						} else if($user->is_agent == '1' && $user->approved_agent != 1) {
							return redirect(route('user.approval'));
						} else {
							return redirect($this->getRedirectUrlAfterLogin());
						}
					} else {
						return back()->withInput()->with('alert-danger', 'Your login information appears to be incorrect. Please try again or reset password using the option given below.');
					}
				} else {
					if (Hash::check($request->password, $user->password)) {
						if($user->is_verified != 1) {
							$userId = $user->id;
							$otp = rand(10000,99999);
							session(['userId' => $userId,'signup_otp' => $otp,'user_becomig_agent_email'=>$user->email]);
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
							// $email_params = isset($email_params) ? $email_params : [];
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
							return redirect(url('account/otp'))->with('alert-success', 'Otp sent successfully on '.$user->email);
					} else {
						return back()->withInput()->with('alert-danger', 'Your account is currently Deactivated, Please contact Administrator.');
					}

					} else {
						return back()->withInput()->with('alert-danger', 'Your login information appears to be incorrect. Please try again or reset password using the option given below.');
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
							// $email_params = isset($email_params) ? $email_params : [];
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
							return redirect(url('account/otp'))->with('alert-success', 'Otp sent successfully on '.$Tempuser->email);
					} else {
						return back()->withInput()->with('alert-danger', 'Your account is currently Deactivated, Please contact Administrator.');
					}
				} else {
						return back()->withInput()->with('alert-danger', 'Your password do not match please try again.');
				}
			}
		  	return back()->withInput()->with('alert-danger', 'The login information you have provided could not be found. Please review your login details. If you are a new user, kindly sign up first.');
		}
		// $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
		// $data['page_title'] = 'Login - '.$system_title;
		// $data['page_heading'] = 'Login';


		$data['meta_title'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

		return view(config('custom.theme').'.account.login', $data);
	}


	public function vendorlogin(Request $request) {
		$data['meta_title'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
		$data['meta_description'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

		return view(config('custom.theme').'.account.vendorlogin', $data);

	}

	public function signup(Request $request) {
		$data = [];
		if($request->referer) {
			session(['referer'=>$request->referer]);
		}
		if($request->redirect_url) {
			session(['redirect_url'=>$request->redirect_url]);
		}
		$method = $request->method();
		if($method == 'POST') {
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
			$validation_msg = [];
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

            $validation_msg['required'] = ':attribute is required.';
            $validation_msg['digits'] = ':attribute must be :digits digits.';
            $validation_msg['min'] = ':attribute should be minimum :min character.';
            $validation_msg['max'] = ':attribute should not be greater than :max character.';
            $validation_msg['unique'] = 'We\'are sorry. This login email already exists. Please try a different email to register, or login to your existing account.';
			$this->validate($request, $rules, $validation_msg, $nicknames);
			
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

			if(!$captchaSuccess) {
				return redirect()->back()->withInput()->with('alert-danger', 'Invalid form request.');
			}

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

					$redirect_url = $this->getRedirectUrlAfterLogin();
					if($redirect_url) {
						return redirect($redirect_url);
					} else {
						return redirect(route('user.profile'));
					}
				} else {
					$message = $user['msg']??'Something went wrong, please try again.';
					return back()->withInput()->with('alert-danger', $message);
				}
			} else {
				$is_saved = $this->user_save($request, '');
				if($is_saved) {
					$referer = (isset($request->referer))?$request->referer:'';
					return redirect(url('account/otp?referer='.$referer))->with('alert-success', 'Otp sent successfully on '.$request->email);
				}
			}
		}
		$data['countries'] = Country::where('status',1)->get();
		$data['is_agent'] = 0;
		return view(config('custom.theme').'.account.signup', $data);
	}

	public function agent_signup(Request $request) {
		$data = [];
		$method = $request->method();
		if($method == 'POST') {
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
			if($social_signup == 1){
				$rules['password'] = 'nullable|min:6|max:12';
				$rules['confirm_password'] = 'nullable|same:password';
			} else {
				// $rules['email'] = 'required|email|unique:temp_users,email|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
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

            $validation_msg['required'] = ':attribute is required.';
            $validation_msg['digits'] = ':attribute must be :digits digits.';
            $validation_msg['min'] = ':attribute should be minimum :min character.';
            $validation_msg['max'] = ':attribute should not be greater than :max character.';

			$validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
			
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
			if(!$captchaSuccess) {
				return redirect()->back()->withInput()->with('alert-danger', 'Invalid form request.');
			}

			$email = $request->email??'';
			$userEmail = '';
			if(isset($email) && !empty($email)){
				$user = User::where('email',$email)->first();
				if(isset($user) && !empty($user)){
					if($user->is_agent == 1){
						if($user->approved_agent == 1){
							return back()->withInput()->with('alert-danger', 'Your application has already been verified for agent, please login.');
						}else{
							$agentInfo = $user->agentInfo??[];
							if($agentInfo) {
								return back()->withInput()->with('alert-danger', 'Your application for agent is under review, please contact administrator.');
							} else {
								return back()->withInput()->with('alert-danger', 'The email has already been taken, please login.');
							}
						}
					} else {
						$userEmail = $user->email;
					}
				}
			}

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			} else {
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
						return redirect(route('user.agent_signup_2'));
					} else {
						$message = $user['msg']??'Something went wrong, please try again.';
						return back()->withInput()->with('alert-danger', $message);
					}
				} else {
					$is_saved = $this->user_save($request, $userEmail);
					if($is_saved) {
						$referer = (isset($request->referer))?$request->referer:'';
						return redirect(url('account/otp?referer='.$referer))->with('alert-success', 'Otp sent successfully on '.$request->email);
					}
				}				
			}
		}
		$data['countries'] = Country::where('status',1)->get();
		$data['is_agent'] = 1;

		return view(config('custom.theme').'.account.signup', $data);
	}


	public function agent_signup_2(Request $request) {
		if(Session::has('old_user_id')) {
			$old_user_id = Session::get('old_user_id');

			$data = [];
			$method = $request->method();

			if($method == 'POST') {
				$nicknames = [
					'company_name' => 'Company Registered Name',
					'company_brand' => 'Company Brand/Trade Name',
					'pan_no' => 'PAN Number',
					'gst_no' => 'GST Number',
					'gst_image' => 'Upload GST',
				];

				$rules = [];
				$rules['company_name'] = 'required|max:100';
				$rules['company_brand'] = 'required|max:100';
				$rules['company_owner_name'] = 'required|max:100';
				$rules['destinations_sell_most'] = 'nullable|max:100';
				$rules['agency_age'] = 'nullable|max:100';
				$rules['no_of_employees'] = 'nullable|max:100';
				$rules['region'] = 'nullable|max:100';
				$rules['company_profile'] = 'required|max:100';
		        $rules['pan_no'] = 'required|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';
				$rules['pan_image'] = 'nullable|mimes:jpeg,pdf|max:10240';

		        //$rules['gst_no'] = 'required|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([A-Za-z]){2}?$/';
		        $rules['gst_no'] = 'nullable|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/';

				$rules['gst_image'] = 'required|mimes:jpeg,pdf|max:10240';

				$validation_msg['required'] = ':attribute is required.';
	            $validation_msg['digits'] = ':attribute must be :digits digits.';
	            $validation_msg['min'] = ':attribute should be minimum :min character.';
	            $validation_msg['max'] = ':attribute should not be greater than :max character.';
				$this->validate($request, $rules, $validation_msg, $nicknames);
				$is_saved = $this->user_save($request, '');
				if($is_saved) {
					return redirect(route('user.approval'));
				}
			}
			$data['old_user_id'] = $old_user_id;
			$data['countries'] = Country::where('status',1)->get();

			$data['meta_title'] = 'Agent Signup - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        	$data['meta_description'] = 'Agent Signup - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

			return view(config('custom.theme').'.account.agent-signup', $data);
		} else {
			return redirect(route('account.login'))->with('alert-danger','Invalid Request.');
		}
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
	public function signup_otp(Request $request){
		$data = [];
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
					return redirect(url('account/login'));
				}

				$method = $request->method();
				if($method == 'POST'){
					$rules = [];
					$rules['otp'] = 'required|numeric|min:5';
					$this->validate($request, $rules);
					$referer = (isset($request->referer))?$request->referer:'';
					$DEFAULT_OTP = CustomHelper::WebsiteSettings('DEFAULT_OTP');
					if($userDetails->otp == $request->otp || $request->otp == $DEFAULT_OTP){
						Session::forget('signup_otp');
						Session::forget('user_becomig_agent_email');
						if(isset($userEmail) && !empty($userEmail)){
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
									return back()->withInput()->with('alert-danger', $message);
								}
							}

							if($user_id) {
								if($userDetails->is_agent == '0' || ($userDetails->is_agent == '1' && $userDetails->approved_agent==1)){
									Session::forget('userId');
									Auth::loginUsingId($user_id);
									$redirect_url = $this->getRedirectUrlAfterLogin();
									if($redirect_url) {
										return redirect($redirect_url);
									} else {
										return redirect(route('user.profile'));
									}
								} else {
									Session::forget('userId');
									session(['old_user_id'=>$user_id]);
									Auth::loginUsingId($user_id);
									return redirect(route('user.agent_signup_2'));
								}
							} else {
								return back()->withInput()->with('alert-danger', 'Account not found.');
							}
						} else {
							return back()->withInput()->with('alert-danger', 'Something went wrong, please try again.');
						}
					} else {
						return back()->withInput()->with('alert-danger', 'Your otp is not matched, please try again.');
					}					
				}
				return view(config('custom.theme').'.account.otp', $data);
			} else {
				return redirect(route('account.signup'));
			}
		} else {
			return redirect(route('account.signup'));
		}
	}

	public function resend_otp(Request $request) {
		$userId = "";
		if(Session::has('userId')){
			$userId = Session::get('userId');
			if(Session::has('user_becomig_agent_email')){
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
					$params['module_name'] = 'User resend otp';
					$params['record_id'] = $userId ?? 0;
					$isSent = CustomHelper::sendCommonMail($params);
				}

				// if($isSent){
					return redirect(url('account/otp'))->with('alert-success', 'Otp resent successfully on '.$userDetails->email);
				// }
			}else{
				return redirect(url('account/signup'))->with('alert-danger', 'Invalid Request.');
			}
		} else {
			return redirect(url('account/signup'));
		}
	}

	public function forgot_resend_otp(Request $request){

		$forgotEmail = "";
		if(Session::has('forgot_email')){
			$forgotEmail = Session::get('forgot_email');
		}else{
			return redirect(url('account/login'))->with('alert-success','Invalid Request.');
		}

		$userDetails = User::where('email',$forgotEmail)->first();

		if(!empty($userDetails)){

			// $otp = randomNumber(4);
			$otp = rand(10000,99999);
			session(['signup_otp' => $otp]);

			User::where("email", $forgotEmail)->update(["otp" => $otp]);

	        // $subject = 'One Time Password Verification Email | '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
	        // $emailData = [];
	        // $emailData['name'] = $userDetails->name;
	        // $emailData['email'] = $userDetails->email;
	        // $emailData['otp'] = $otp;
	        // $from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
	        // $email = '';
	        // $isMail = CustomHelper::sendEmail('emails.send_forgot_otp', $emailData, $to=$to_email, $from_email, $replyTo = $from_email, $subject);

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
				// $email_params = isset($email_params) ? $email_params : [];
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

	        // if($isMail){
				// return redirect(url('account/otp'))->with('alert-success', 'Otp resent successfully on '.$forgotEmail.' for reset your password.');
				return redirect(url('account/forgot-otp'))->with('alert-success', 'OTP has been successfully resent to '.$forgotEmail.' to initiate the password reset process.');
	        // }
		}else{
			return redirect(url('account/signup'))->with('alert-danger', 'Invalid Request.');
		}

	}

	public function ajaxResend(Request $request){

		$response = [];
		$response['success'] = false;

		$forgotEmail = "";
		if(Session::has('forgot_email')){
			$forgotEmail = Session::get('forgot_email');
		}else{
			$response['message'] = "invalid request.";
			return response()->json($response);
		}

		$userDetails = User::where('email',$forgotEmail)->first();

		if(!empty($userDetails)){

			// $otp = randomNumber(4);
			$otp = rand(10000,99999);
			session(['signup_otp' => $otp]);

			User::where("email", $forgotEmail)->update(["otp" => $otp]);

	        $subject = 'One Time Password Verification Email | '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

	        $emailData = [];

	        $emailData['name'] = $userDetails->name;
	        $emailData['email'] = $userDetails->email;
	        $emailData['otp'] = $otp;
	        $from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
	        $email = '';
	        $to_email = $userDetails->email;

			//THIS FUNCTION IS NOT IN USE
	        // $isMail = CustomHelper::sendEmail('emails.send_otp', $emailData, $to=$to_email, $from_email, $replyTo = $from_email, $subject);

			// if($isMail){

			// 	$response['success'] = TRUE;
			// 	$response['message'] = 'OTP sent successfull for reset your password.';
			// }
			// else{
			// 	$response['message'] = 'Something went wrong, please try again.';
			// }
		}else{
			$response['message'] = 'Something went wrong, please try again.';
		}

		return response()->json($response);

	}

	/* ajax_forgot */
	public function ajaxForgot(Request $request){

		$response = [];
		$response['success'] = false;

		$errors = [];

		$method = $request->method();

		if($method == 'POST'){

			$rules = [];

			$rules['forgot_email'] = 'required|email';

			$validator = Validator::make($request->all(), $rules);

			if($validator->passes()){

				$email = $request->forgot_email;

				$user = User::where('email', $email)->first();

				if(!empty($user)){

					// $otp = randomNumber(4);
					$otp = rand(10000,99999);
					session(['forgot_email'=> $user->email, 'forgot_otp' => $otp]);

					User::where("id", $user->id)->update(["otp" => $otp]);

			        $subject = 'One Time Password Verification Email | '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

			        $emailData = [];

			        $emailData['name'] = $user->name;
			        $emailData['email'] = $user->email;
			        $emailData['otp'] = $otp;
					$from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
					$email_data = [];
					$to_email = $email;
					//THIS FUNCTION IS NOT IN USE
					// $is_mail = CustomHelper::sendEmail('emails.send_otp', $emailData, $to=$to_email, $from_email, $replyTo = $from_email, $subject);



					// if($is_mail){

					// 	$response['success'] = true;

					// 	$response['message'] = 'Otp sent successfully for reset your password.';
					// }
					// else{
					// 	$response['message'] = 'Something went wrong, please try again.';
					// }
				}else{
						$response['message'] = 'This email is not registered with us.';
				}

			}
			else{
				$errors = $validator->errors();
			}
		}

		$response['errors'] = $errors;

		return response()->json($response);
	}

	public function forgotPassword(Request $request){

        $data = [];
        $method = $request->method();
		if($method == 'POST') {
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
			$this->validate($request, $rules, $validation_msg, $nicknames);
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
			if(!$captchaSuccess) {
				return redirect()->back()->withInput()->with('alert-danger', 'Invalid form request.');
			}

			$email = $request->forgot_email;
			$user = User::where('email', $email)->first();

			if(!empty($user)) {
				// $otp = randomNumber(4);
				$otp = rand(10000,99999);
				session(['forgot_email'=> $user->email, 'forgot_otp' => $otp]);

				User::where("id", $user->id)->update(["otp" => $otp]);

		        // $subject = 'One Time Password Verification Email | '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
		        // $emailData = [];
		        // $emailData['name'] = $user->name;
		        // $emailData['email'] = $user->email;
		        // $emailData['otp'] = $otp;
				// $from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
				// $email_data = [];
				// $to_email = $email;
				// $is_mail = CustomHelper::sendEmail('emails.send_forgot_otp', $emailData, $to=$to_email, $from_email, $replyTo = $from_email, $subject);

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
	                // $email_params = isset($email_params) ? $email_params : [];
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

				// if($is_mail){
					return redirect(url('account/forgot-otp'))->with('alert-success', 'OTP has been successfully sent to '.$email.' to initiate the password reset process.');
				// }
				// else{
				// 	return back()->withInput()->with('alert-danger', 'Something went wrong, please try again.');
				// }
			}else{
				return back()->withInput()->with('alert-danger', 'This email is not registered with us.');
			}
		}

		$data['meta_title'] = 'Forgot-Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Forgot-Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        return view(config('custom.theme').'.account.forgot_password', $data);
    }

	/* User Forgot otp page */
	public function forgot_otp(Request $request){
		$data = [];

		$userEmail = "";
		if(Session::has('forgot_email')){
			$userEmail = Session::get('forgot_email');
		}else{
			return redirect(url('account/login'));
		}

		if(!empty($userEmail)){
			$userDetails = User::where('email',$userEmail)->first();
		}

		$method = $request->method();
		if($method == 'POST'){
			$rules = [];
			$rules['otp'] = 'required|numeric|min:5';

			$this->validate($request, $rules);

			$referer = (isset($request->referer))?$request->referer:'';
			if(!empty($userEmail)){
				$DEFAULT_OTP = CustomHelper::WebsiteSettings('DEFAULT_OTP');
				if($userDetails->otp == $request->otp || $request->otp == $DEFAULT_OTP){
					Session::forget('forgot_otp');

					return redirect(url('account/change-password'));

				}else{
					return back()->withInput()->with('alert-danger', 'Your otp is not matched, please try again.');
				}
			}else{
				return redirect(url('account/signup'));
			}
		}

		$data['meta_title'] = 'OTP Verify - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'OTP Verify - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

		return view(config('custom.theme').'.account.forgot_otp', $data);
	}

	public function changePassword(Request $request){
		$data = [];
		$data['meta_title'] = 'Change Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Change Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

		$forgotEmail = "";
		if(Session::has('forgot_email')){
			$forgotEmail = Session::get('forgot_email');
		}else{
			return redirect(url('account/change-password'))->with('alert-danger', 'invalid request.');
		}

		$method = $request->method();
		$user = User::where('email', $forgotEmail)->first();

		if(!empty($user)) {
			if($method == 'POST') {
				$rules = [];
				$rules['password'] = 'required|min:6';
				$rules['confirm_password'] = 'required|same:password';
				$this->validate($request, $rules);
				$email = $user->email;
				$password = bcrypt($request->password);
				$update_data = [
					'password' => $password
				];
				if($user->is_verified==0) {
					$update_data['status'] = 1;
					$update_data['is_verified'] = 1;
					$update_data['email_verified_at'] = date('Y-m-d H:i:s');
				}
				$isSaved = User::where("email", $forgotEmail)->update($update_data);
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
							$location = $country . ',' . $city . ',' . $postalCode; //.',('.$lat.','.$lon.')';
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
                        // $email_params = isset($email_params) ? $email_params : [];
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
					return redirect(url('account/login'))->with('alert-success', 'Your password has been successfully changed. Please proceed to log in.');
				}
			}
		} else {
			return back()->withInput()->with('alert-danger', 'Something went wrong.');
		}
		return view(config('custom.theme').'.account.change_password');
	}

	public function ajaxVerify(Request $request) {
		$response = [];
		$response['success'] = false;
		$errors = [];
		$forgotEmail = "";
		if(Session::has('forgot_email')) {
			$forgotEmail = Session::get('forgot_email');
		} else {
			$response['message'] = "invalid request.";
			return response()->json($response);
		}

		$method = $request->method();
		if($method == 'POST'){
			$rules = [];
			$rules['forgot_otp'] = 'required|numeric|min:5';

			$validator = Validator::make($request->all(), $rules);

			if($validator->passes()) {
				$user = User::where('email', $forgotEmail)->first();
				if(!empty($user)) {
					if($user->otp == $request->forgot_otp) {
						Session::forget('forgot_otp');
						$affectedRows = User::where("email", $user->email)->update(["otp" => NULL]);
						if($affectedRows) {
							$response['success'] = TRUE;
							$response['message'] = "Your Email is Verified to reset your password.";
						}
					} else {
						$response['success'] = FALSE;
						$response['message'] = "OTP doesn't match. Please try again.";
					}
				} else {
					$response['success'] = FALSE;
					$response['message'] = "Something went wrong.";
				}
			} else {
				$response['message'] = "Validation Failed !";
				$errors = $validator->errors();
			}
		}

		$response['errors'] = $errors;
		return response()->json($response);
	}

	public function ajaxReset(Request $request) {
		$response = [];
		$response['success'] = false;
		$errors = [];
		$forgotEmail = "";
		if(Session::has('forgot_email')){
			$forgotEmail = Session::get('forgot_email');
		} else {
			$response['message'] = "invalid request.";
			return response()->json($response);
		}
		$method = $request->method();
		$user = User::where('email', $forgotEmail)->first();
		if(!empty($user)) {
			if($method == 'POST') {
				$rules = [];
				$rules['reset_password'] = 'required|min:6';
				$rules['confirm_reset_pwd'] = 'required|same:reset_password';
				$validator = Validator::make($request->all(), $rules);
				if($validator->passes()) {
					$email = $user->email;
					$password = bcrypt($request->reset_password);
					$isSaved = User::where("email", $forgotEmail)->update(["password" => $password,'is_verified'=>1]);
					if($isSaved) {
						$response['success'] = TRUE;
						$response['message'] = "Your password has been updated successfully, please login.";
						Session::forget('forgot_email');
						Session::flash('alert-success', 'Your password has been updated successfully, please login.');
					}
				} else {
					$response['message'] = "Validation Failed !";
					$errors = $validator->errors();
				}
			}
		} else {
			$response['success'] = FALSE;
			$response['message'] = "Something went wrong.";
		}
		$response['errors'] = $errors;
		return response()->json($response);
	}

/*	// fblogin
	public function fbLogin(Request $request){

		return Socialite::driver('facebook')->redirect();

	}

	public function handleFacebookCallback(Request $request)
	{
		try {

			if ($request->has('error_code')) {
				return redirect('account/signup')->with('alert-danger', $request->error_message);
			}

			$fbUserDetails = Socialite::driver('facebook')->user();
			$finduser = User::where('fb_id', $fbUserDetails->id)->orWhere('email', $fbUserDetails->email)->first();

			$referer = (isset($request->referer))?$request->referer:'';

			if($finduser){

				if($finduser->fblogin == 0){
					User::where("id", $finduser->id)->update(["fblogin" => 1, "fb_id" => $fbUserDetails->id]);
				}

				if($finduser->status == 1){
					Auth::login($finduser);
					return redirect($this->getRedirectUrlAfterLogin());
				}
				else{

					$userId = $finduser->id;
					session(['userId' => $userId]);

					if($finduser->is_verified == 1){
						return redirect(url('account/participate'));
					}else{
						$userId = $finduser->id;
						session(['fbUser' => $fbUserDetails]);

						return redirect(url('account/fbsignup?referer='.$referer));
					}
				}
			}else{

				if(!empty($fbUserDetails->email)){

					$user = new User;

					$verify_token = generateToken(40);
					$password = NULL;

					$otp = NULL;

					$username = CustomHelper::GetSlug('users','id',0,$fbUserDetails->name);

					$user->name = $fbUserDetails->name;
					$user->email = $fbUserDetails->email;
					$user->password = $password;
					$user->verify_token = $verify_token;
					$user->referrer = $referer;
					$user->status = 0;
					$user->is_verified = 1;
					$user->email_verified_at = Carbon::now();
					$user->otp = $otp;
					$user->registration_source = 'WEBSITE_FACEBOOK';
					$user->slug = $username;
					$user->join_as = 0;
					$user->pay = 0;
					$user->category = ($request->category != "") ? $request->category : 0;
					$user->country = 0;
					$user->glogin = 0;
					$user->fblogin = 1;
					$user->fb_id = $fbUserDetails->id;

					$is_saved = 0;

					$is_saved = $user->save();

					if($is_saved){
						$userId = $user->id;
						session(['userId' => $userId]);

						return redirect(url('account/participate?referer='.$referer));
					}
				}else{
					session(['fbUser' => $fbUserDetails]);

					return redirect(url('account/fbsignup?referer='.$referer));
				}
			}
		} catch (\InvalidArgumentException $e) {
			return redirect('account/signup')->with('alert-danger', $e->getMessage());
			//dd($e->getMessage());
		}
	}

	// This work when facebook not return an email id.
	public function fbSignup(Request $request){

		$data = [];

		$method = $request->method();

		$fbUserDetails = "";
		if(Session::has('fbUser')){
			$fbUserDetails = Session::get('fbUser');
		}else{
			return redirect(url('account/signup'));
		}

		$data['fbUserDetails'] = $fbUserDetails;

		if($method == 'POST'){

			$rules = [];

			$rules['name'] = 'required|max:100';
			$rules['email'] = 'required|email|unique:users';
			$rules['password'] = 'nullable|min:6|max:20';

			$this->validate($request, $rules);

			$referer = (isset($request->referer))?$request->referer:'';

			$user = new User;

			$verify_token = generateToken(40);

			$password = (!empty($request->password)) ? bcrypt($request->password) : NULL;

			$otp = randomNumber(4);
			session(['signup_otp' => $otp]);

			$username = CustomHelper::GetSlug('users','id',0,$request->name);

			$user->name = $request->name;
			$user->email = $request->email;
			$user->password = $password;
			$user->verify_token = $verify_token;
			$user->referrer = $referer;
			$user->status = 0;
			$user->is_verified = 0;
			$user->otp = $otp;
			$user->registration_source = 'WEBSITE_FACEBOOK';
			$user->slug = $username;
			$user->join_as = 0;
			$user->pay = 0;
			$user->category = (!empty($request->category)) ? $request->category : 0;
			$user->country = 0;
			$user->glogin = 0;
			$user->fblogin = 1;
			$user->fb_id = $fbUserDetails->id;

			$is_saved = 0;

			$is_saved = $user->save();

			if($is_saved){
				$userId = $user->id;
				session(['userId' => $userId]);

            $subject = 'One Time Password Verification Email ';
            $emailData = [];

            $emailData['name'] = $request->name;
            $emailData['email'] = $request->email;;
            $emailData['otp'] = $otp;
            $from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
            $to_email = $request->email;

            $isMail = CustomHelper::sendEmail('emails.send_otp', $emailData, $to=$to_email, $from_email, $replyTo = $from_email, $subject);

			return redirect(url('account/otp?referer='.$referer));
			}
		}
		return view(config('custom.theme').'.account.fbsignup', $data);
	}


	// Google login
	public function gLogin(Request $request){

		return Socialite::driver('google')->redirect();

	}

	public function handleGoogleCallback(Request $request)
	{
		try {

			$gUserDetails = Socialite::driver('google')->user();
			$finduser = User::where('google_id', $gUserDetails->id)->orWhere('email', $gUserDetails->email)->first();
			if($finduser){
				//prd($gUserDetails);

				if($finduser->glogin == 0){
					User::where("id", $finduser->id)->update(["glogin" => 1, "google_id" => $gUserDetails->id]);
				}

				if($finduser->status == 1){
					Auth::login($finduser);
					return redirect($this->getRedirectUrlAfterLogin());
				}
				else{

					$userId = $finduser->id;
					session(['userId' => $userId]);

					if($finduser->is_verified == 1){
						return redirect(url('account/participate'));
					}
				}
			}else{

				//prd($gUserDetails);
				$referer = (isset($request->referer))?$request->referer:'';
				$user = new User;

				$verify_token = generateToken(40);
				$password = NULL;

				$otp = NULL;

				$username = CustomHelper::GetSlug('users','id',0,$gUserDetails->name);

				$user->name = $gUserDetails->name;
				$user->email = $gUserDetails->email;
				$user->password = $password;
				$user->verify_token = $verify_token;
				$user->referrer = $referer;
				$user->status = 0;
				$user->is_verified = 1;
				$user->email_verified_at = Carbon::now();
				$user->otp = $otp;
				$user->registration_source = 'WEBSITE_GOOGLE';
				$user->slug = $username;
				$user->join_as = 0;
				$user->pay = 0;
				$user->category = ($request->category != "") ? $request->category : 0;
				$user->country = 0;
				$user->glogin = 1;
				$user->fblogin = 0;
				$user->google_id = $gUserDetails->id;

				$is_saved = 0;

				$is_saved = $user->save();

				if($is_saved){
					$userId = $user->id;
					session(['userId' => $userId]);

					return redirect(url('account/participate?referer='.$referer));
				}
			}
		} catch (\InvalidArgumentException $e) {
			return redirect('account/signup')->with('alert-danger', $e->getMessage());
			//dd($e->getMessage());
		}
	}*/

/* end of controller */
}
