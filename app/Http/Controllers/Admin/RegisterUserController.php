<?php

namespace App\Http\Controllers\Admin;   

use File;
use Image;
use Storage;
use Validator;
use App\Models\Admin;
use App\Models\Domain;
use App\Models\TableToDomain;
use App\Models\Module;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Country;
use App\Models\Permission;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Auth;


class RegisterUserController extends Controller {

    private $limit;
    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 20;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }


    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;

        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        $export_xls = (isset($request->export_xls))?$request->export_xls:'';
        $name = (isset($request->name))?$request->name:'';
        $email = (isset($request->email))?$request->email:'';
        $phone = (isset($request->phone))?$request->phone:'';
        $status = (isset($request->status))?$request->status:1;
        $from = (isset($request->from))?$request->from:'';
        $to = (isset($request->to))?$request->to:'';

        $from_date = CustomHelper::DateFormat($from, 'Y-m-d', 'd/m/Y');
        $to_date = CustomHelper::DateFormat($to, 'Y-m-d', 'd/m/Y');

        $user_query = User::orderBy('id', 'desc');

        $user_query->where(function($query){
            $query->where('is_agent',0);
            $query->orwhereNull('is_agent');
        });
        $user_query->where(function($query){
            $query->where('is_vendor',0);
            $query->orwhereNull('is_vendor');
        });

        if(!empty($name)){
            $user_query->where('name', 'like', '%'.$name.'%');
        }

        if(!empty($email)){
            $status = '';
            $user_query->where('email', 'like', '%'.$email.'%');
        }

        if(!empty($phone)){
            $status = '';
            $phone = str_replace(['+','-'], ['',''], $phone);
            if($phone) {
                $phone = trim($phone);
                if($phone) {
                    $user_query->where(function($q1) use($phone){
                        $q1->where(DB::raw('CONCAT(`country_code`,"", `phone`)'), 'like', '%'.$phone.'%');
                        $q1->orWhere('phone', 'like', '%'.$phone.'%');
                    });
                }
            }
        }

        if( strlen($status) > 0){
            $user_query->where('status', $status);
        }

        if(!empty($from_date)){
            $user_query->whereRaw('DATE(created_at) >= "'.$from_date.'"');
        }

        if(!empty($to_date)){
            $user_query->whereRaw('DATE(created_at) <= "'.$to_date.'"');
        }

        $customers_download_logs = $request->all();
        $new_data = json_encode($customers_download_logs);

        $action = '';
        if($export_xls == 1) {
            $action = 'Export';
        }

        if($action == 'Export') {
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Customer';
            $params['module_desc'] = 'Customer List';
            $params['module_id'] = '';
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = $action;
            CustomHelper::RecordActivityLog($params);
        }

        if(!empty($export_xls) && ($export_xls == 1 || $export_xls == '1') ){
            return $this->exportXls($user_query);
        }

        $users = $user_query->paginate($limit);
        $data['users'] = $users;
        return view('admin.register_users.index', $data);
    }

    public function login(Request $request) {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if($user) {
            $res = auth()->guard('web')->loginUsingId($user_id);
            if($res && $res->id) {
                $online_booking = CustomHelper::isOnlineBooking();
                if($online_booking) {
                    return redirect(route('user.mybooking'));
                } else {
                    return redirect(route('user.profile'));
                }
            } else {
                return back()->with('alert-danger', 'User not found.');
            }
        } else {
            return back()->with('alert-danger', 'User ID is empty.');
        }
    }


    public function add(Request $request){
        
      /*  if(CustomHelper::isAdmin()){*/
        $data = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        $id = (isset($request->id))?$request->id:0;

        $user = '';
        if(is_numeric($id) && $id > 0){
            $user = User::find($id);
            if(empty($user)){
                return redirect($this->ADMIN_ROUTE_NAME.'/register-users');
            }
        }

        if($request->method() == 'POST' || $request->method() == 'post'){

            $back_url = (isset($request->back_url))?$request->back_url:'';

            if(empty($back_url)){
                $back_url = $this->ADMIN_ROUTE_NAME.'/register-users';
            }

            $name = (isset($request->name))?$request->name:'';

            $rules = [];
            $validation_msg = [];

            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['email'] = 'required|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            //$rules['phone'] = 'required|regex:/^([0-9\s\-\+\(\)]*)$/';

            $phone = $request->phone??'';
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

            $validation_msg['name.required'] = 'The Name field is required.';
            $validation_msg['name.regex'] = 'The Name format is Invalid.';
            $validation_msg['email.required'] = 'The Email field is required.';
            $validation_msg['email.regex'] = 'The Email format is Invalid.';
            $validation_msg['phone.required'] = 'The Phone field is required.';
            $validation_msg['phone.regex'] = 'The Phone format is Invalid.';


            if(!empty($request->password)){
                $rules['password'] = 'sometimes|min:6|max:255';
            }

            /*if(!empty($request->phone)){
                $rules['phone'] = 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10';
            }*/

            if(empty($user)){
                $rules['email'] = 'required|max:255|email|unique:users';
                $rules['password'] = 'sometimes|min:6|max:255';
            }
            else{
                $rules['email'] = 'required|email|unique:users,email,' . $id.',id';
            }

            $this->validate($request,$rules,$validation_msg);

            $req_data = [];
            $req_data['phone'] = (!empty($user->phone))?$user->phone:'';
            $req_data['country_code'] = (!empty($user->country_code))?$user->country_code:91;
            $req_data['address1'] = (!empty($user->address1))?$user->address1:'';

            $update_data = [];
            $update_data = $request->except(['id', 'password', '_token', 'back_url']);

            $UserpasswordUpdate = isset($request->password) ? $request->password : '';

                if(!empty($UserpasswordUpdate)){

                    $update_data['password'] = bcrypt($UserpasswordUpdate);
                }

            $update_data['is_agent'] = isset($request->is_agent) ? $request->is_agent : '';
            // $update_data['is_vendor'] = isset($request->is_vendor) ? $request->is_vendor : '';
            if($request->is_agent == 1){
                $update_data['is_verified'] = 1;
            }


            $createdUser = DB::table('users')->where('id', $id)->update($update_data);

            if ($createdUser){
                //prd($createdUser);
                $alert_msg = 'Registered User has been added successfully.';
                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'users';
                $row_id = $createdUser;
                $action_type = 'Add User';
                $action_description = 'Add ('.$request->name.")";
                $description = 'Add('.$request->name.") ".$description;*/

                if(is_numeric($id) && $id > 0){
                    
                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'users';
                $row_id = $id;
                $action_type = 'Edit User';
                $action_description = 'Edit ('.$request->name.")";
                $description = 'Update('.$request->name.") ".$description;*/
                $alert_msg = 'Registered User has been updated successfully.';
                }

                /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

                //=============Activity Logs=====================

                $new_data = DB::table('users')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id= $id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Customer';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                //=============Activity Logs=====================

                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {
                // return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
                return redirect(url($back_url))->with('alert-success', 'Registered User has been updated successfully.');
            }
        }

        //$modules = Module::orderBy('sort_order', 'asc')->get();
       
       
        $user_name = '';
        $page_heading = 'Add User';

        if(isset($user->name)){
            $user_name = $user->name;
            $page_heading = 'Update User - '.$user_name;
        }

        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['user'] = $user;
        $data['user_name'] = $user_name;
        $data['countries'] = Country::where('status',1)->get();
        //$data['modules'] = $modules;

        return view('admin.register_users.form', $data);
      /*  }
        else{
            return redirect($this->ADMIN_ROUTE_NAME);
        }*/

    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user = "";
        $title = "Registered User Details";

        if (is_numeric($id) && $id > 0) {
            $user = User::where("id", $id)->first();
            //prd($accommodation);
            $title = "Registered User Details (".$user->name.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["user"] = $user;
        $data["id"] = $id;
        return view("admin.register_users.view", $data);
    }

    public function delete(Request $request){

        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;

        if($method == 'POST'){
            if(is_numeric($id) && $id > 0){
                $user_query = User::find($id);
                $last_data = DB::table('users')->where('id',$id)->first();
                $module_desc =  !empty($last_data->name)?$last_data->name:'';
                $last_data =(array) $last_data;
                $last_data = json_encode($last_data);
                /*$function_name = $this->currentUrl;
                $action_table = "users";
                $row_id = $id;
                $action_type = "Delete User";
                $action_description = "Delete(" . $request->name . ")";
                $description = "Delete(" . $request->name . ")";*/
                $is_deleted = $user_query->delete();
            }

        }

        /*CustomHelper::recordActionLog(
            $function_name,
            $action_table,
            $row_id,
            $action_type,
            $action_description,
            $description
        );*/
        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'Customer';
        $params['module_desc'] = $module_desc;
        $params['module_id'] = $id;
        $params['sub_module'] = "";
        $params['sub_module_id'] = 0;
        $params['sub_sub_module'] = "";
        $params['sub_sub_module_id'] = 0;
        $params['data_after_action'] = $last_data??'';
        $params['action'] = "Delete";

        CustomHelper::RecordActivityLog($params);

        return back()->with("alert-success","The Register User has been removed successfully."
        );

    }

    private function exportXls($user_query){

        //$fieldNames = $newsletterQuery->getModel()->getFillable();

        //$select = DB::table('users as u')->selectRaw('u.*')->get();

        $select = ['id','name','email','phone','gender','dob','profile_image','address1','address2','city','state','country','zipcode','password','referrer','remember_token','verify_token','is_verified','otp','status','forgot_token','email_verified_at','created_at'];

        $users = $user_query->select($select)->get();

        $fileName = 'register_user_'.date('Y-m-d-H-i-s').'.xlsx';

        $viewData = [];
        $viewData['users'] = $users;

        //$viewHtml = view('admin.newsletter._newsletter_export', $viewData)->render();

        //echo $viewHtml; die;       

        header('Content-Type: application/vnd.ms-excel');
        //tell browser what's the file name
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        //no cache
        header('Cache-Control: max-age=0');

        return view('admin.register_users._register_user_export', $viewData);
    }

    public function save(Request $request, $id=0){

        $data = $request->except(['_token', 'back_url','permission','password']);
        $data['role_id'] = 2;

        if(!empty($request->password)){
            $data['password'] = bcrypt($request->password);
        }

        //prd($request->toArray());
        $admin = new Admin;

        if(is_numeric($id) && $id > 0){
            $exist = Admin::find($id);

            if(isset($exist->id) && $exist->id == $id){
                $admin = $exist;
            }
        }

        foreach($data as $key=>$val){
            $admin->$key = $val;
        }

        $isSaved = $admin->save();
        //prd($isSaved);

        if($isSaved){
            $this->savePermission($request, $admin->id);
        }

        return $admin->id;
    }


    private function savePermission($request, $id){

        $permissionArr = (isset($request->permission))?$request->permission:'';

        $existData = Permission::where('user_id',$id)->first();

        if(!empty($permissionArr) && count($permissionArr) > 0){

            $jsonData = json_encode($permissionArr);

            $dataArr = [];
            $dataArr['permissions'] = $jsonData;

            if(!empty($existData)){
                Permission::where('user_id',$id)->update($dataArr);
            }
            else{
                $dataArr['user_id'] = $id;
                Permission::insert($dataArr);
            }
        }
    }
    
    public function change_password(Request $request) {

        $auth_user = auth()->guard('admin')->user();
        $admin_id = $auth_user->id;

        $method = $request->method();

        //prd($method);

        if($method == 'POST' || $method =="post"){
            $post_data = $request->all();
            $rules = [];

            $rules['old_password'] = 'required|min:6|max:20';
            $rules['new_password'] = 'required|min:6|max:20';
            $rules['confirm_password'] = 'required|min:6|max:20|same:new_password';

            $validator = Validator::make($post_data, $rules);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            else{
                //prd($request->all());

                $old_password = $post_data['old_password'];

                $user = Admin::where(['id'=>$admin_id])->first();

                $existing_password = (isset($user->password))?$user->password:'';

                $hash_chack = Hash::check($old_password, $user->password);

                if($hash_chack){
                    $update_data['password']=bcrypt(trim($post_data['new_password']));

                    $is_updated = DB::table('admins')->where('id', $admin_id)->update($update_data);

                    $message = [];

                    if($is_updated){

                        $message['alert-success'] = "Password updated successfully.";
                    }
                    else{
                        $message['alert-danger'] = "something went wrong, please try again later...";
                    }
                    
                    return back()->with($message);


                }
                else{
                    $validator = Validator::make($post_data, []);
                    $validator->after(function ($validator) {
                        $validator->errors()->add('old_password', 'Invalid Password!');
                    });
                    //prd($validator->errors());
                    return back()->withErrors($validator)->withInput();
                }
            }
        }
        
        $data = [];
        $data['title'] = 'Change Password';
        $data['heading'] = 'Change Password';

        return view('admin.change_password', $data);
    }


    public function walletList(Request $request)
    {
        $limit = $this->limit;
        $userId = isset($request->id) ? $request->id : 0;
        $data = [];

            $balance = UserWallet::where('user_id',$userId)->sum('amount');
            $credit_query = UserWallet::where('user_id',$userId)->where('type','credit');
            $total_credit = UserWallet::where('user_id',$userId)->where('type','credit')->sum('amount');

            $debit_query = UserWallet::where('user_id',$userId)->where('type','debit');
            $total_debit = UserWallet::where('user_id',$userId)->where('type','debit')->sum('amount');

            $query = UserWallet::where('user_id',$userId)->orderBy('id','desc');

            $today = date("d/m/Y");
            $previous_month = date("d/m/Y", strtotime("-1 months"));
            $from = (isset($request->from)) ? $request->from : $previous_month;
            $to = (isset($request->to)) ? $request->to : $today;

            if(!empty($from)){
            	$from_date = CustomHelper::DateFormat($from, 'Y-m-d', 'd/m/Y');
            	if(!empty($from_date)){
            		$query->whereRaw('DATE(for_date) >= "'.$from_date.'"');
            		$credit_query->whereRaw('DATE(for_date) >= "'.$from_date.'"');
            		$debit_query->whereRaw('DATE(for_date) >= "'.$from_date.'"');
            	}
            }
            if(!empty($to)){
            	$to_date = CustomHelper::DateFormat($to, 'Y-m-d', 'd/m/Y');
            	if(!empty($to_date)){
            		$query->whereRaw('DATE(for_date) <= "'.$to_date.'"');
            		$credit_query->whereRaw('DATE(for_date) <= "'.$to_date.'"');
            		$debit_query->whereRaw('DATE(for_date) <= "'.$to_date.'"');
            	}
            }

            $date_credit = $credit_query->sum('amount');
            $date_debit = $debit_query->sum('amount');

            $wallets = $query->paginate($limit);
      
        $data['date_credit'] = $date_credit;
        $data['date_debit'] = $date_debit;
        $data['date_sum'] = $date_credit + $date_debit;
        
        $data['total_credit'] = $total_credit;
        $data['total_debit'] = $total_debit;

        $data['balance'] = $balance;
        $data['wallets'] = $wallets;

        $data['from'] = $from;
        $data['to'] = $to;

        $data['userId'] = $userId;
        $data['user'] = User::find($userId);

        return view("admin.register_users.wallet", $data);
    }

    public function transactionAdd(Request $request) {
        if($request->id) {
           $id =$request->id ?? '';
           $method = $request->method();
           $getUser = User::find($id);

           $phone_no = '';
            if($getUser->phone) {
                $country_code = $getUser->country_code ?? '91';
                $phone_no = '+'.$country_code.'-'.$getUser->phone;
            }

           if($method == "POST"){
            $rules = [];
            $rules['type'] = 'required';
            $rules['amount'] = 'required|numeric|integer';
            $rules['comment'] = 'required';
            $rules['for_date'] = 'required';
            $rules['payment_method'] = 'required';
            $this->validate($request, $rules);

            $old_balance = UserWallet::where('user_id',$id)->sum('amount');
            $type = isset($request->type) ? $request->type : '';
            $amount = isset($request->amount) ? $request->amount : 0;
            $comment = isset($request->comment) ? $request->comment : '';
            $for_date = isset($request->for_date) ? $request->for_date.' '.date("H:i:s") : date('Y-m-d H:i:s');
            $payment_method = isset($request->payment_method) ? $request->payment_method : '';
            $balance = 0;
            if($type == 'credit'){
                $balance = $old_balance + $amount;   
            }
            else if($type == 'debit'){
                $balance = $old_balance - $amount;   
                $amount = -$amount;
            }

            $req_data = array(
                'user_id' => isset($request->user_id) ? $request->user_id : 0,
                'type' => $type ?? null,
                'amount' => $amount,
                'balance' => $balance,
                'comment' => $comment,
                'for_date' => $for_date,
                'payment_method' => $payment_method,
             );

            $isSaved = UserWallet::create($req_data);
            $msg = "Transaction has been added successfully.";
            
            if($isSaved){

                if($type == 'credit'){
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
                        '{total_amount}' => isset($amount) ? CustomHelper::getPrice($amount) : '',
                        '{method}' => $payment_method ?? '',
                        '{status}' => 'Confirmed',
                        '{comment}' => $comment ?? '',
                        '{name}' => $getUser->name ?? '',
                        '{header}' => $common_logo??'',
                        '{contact_details}' => $contact_details ?? '',
                        '{date}' => date("l jS \of F Y h:i A"),
                    );
    
                    $sms_params = array(
                        '{#var1#}' => CustomHelper::getPrice($amount)??0,
                        '{#var2#}' => date("d-M-Y h:i A"),
                        '{#var3#}' => CustomHelper::getPrice($balance)??0,
                    );

                    $template_slug = 'wallet-email';
                    $sms_template_slug = 'wallet-sms-update';
                    $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();
    
                    $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
                    $email_content = strtr($email_content, $email_params);
                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $sms_content = strtr($sms_content, $sms_params);
                    $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';
                    $email_type = isset($email_content_data->email_type) ? $email_content_data->email_type : '';
                    $email_bcc_admin = isset($email_content_data->bcc_admin) ? $email_content_data->bcc_admin : 0;
    
                    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                    $to_email = $getUser->email ?? '';
                    $cc_email = '';
                    $bcc_email = '';
                    if($email_type == 'admin'){
                        $to_email = $ADMIN_EMAIL;
                    }
                    if($email_bcc_admin == 1){
                        $bcc_email = $ADMIN_EMAIL;
                    }
    
                    if(isset($sms_content_data) && !empty($sms_content_data)) {
                        $sms_content = urlencode($sms_content);
                        $params  = [];
                        $params['phone'] = $phone_no;
                        $params['content'] = $sms_content;
                        $params['template_id'] = $sms_content_data->template_id ?? '';
                        $is_sms_sent = CustomHelper::send_sms($params);
                    }
    
                    if(isset($email_content_data) && !empty($email_content_data)) {
                        $params = [];
                        $params['to'] = $to_email;
                        $params['reply_to'] = '';
                        $params['cc'] = $cc_email;
                        $params['bcc'] = $bcc_email;
                        $params['subject'] = $email_subject;
                        $params['email_content'] = $email_content;
                        $params['module_name'] = 'User Wallet';
                        $params['record_id'] = $isSaved->id ?? 0;
                        $is_mail = CustomHelper::sendCommonMail($params);
                    }
                }

                return back()->with("alert-success",$msg);
            }else{
                return back()->with("alert-danger","The Transaction could not be added, please try again or contact the administrator."
                );
            }
           }
            $data = [];
            $page_heading = "Add Transaction";
            $data['page_heading'] = $page_heading;
            $data['id'] = $id;
            return view("admin.register_users.transaction_form", $data);
          } 
    }

    /* End of controller */
}