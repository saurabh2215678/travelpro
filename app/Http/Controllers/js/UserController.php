<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAgentInfo;
use App\Models\Country;
use App\Models\User;
use App\Models\UserGstInfo;
use App\Models\UserWallet;
use App\Models\Order;
use App\Models\EnquiriesMaster;
use App\Models\OrderServiceVoucher;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\EmailTemplate;
use App\Models\CabRoute;
use App\Models\BikeCities;
use App\Models\BikeMaster;
use App\Models\BikeCityLocation;
use App\Models\BikecityPrice;
use App\Models\OrderStatusHistory;
use App\Models\CabCities;
use App\Models\CabRouteGroup;
use App\Models\ModuleDiscountCategory;
use App\Models\DiscountModuleToGroup;
use App\Models\CustomModuleRecordDiscount;
use App\Models\AccommodationRoom;
use App\Models\AgentGroup;
use App\Models\CabRoutePrice;
use App\Models\AirlinePriceMarkup;
use App\Models\AgentAirlineMarkup;
use App\Models\AirlineMarkup;
use App\Models\SmsTemplate;
use App\Models\Enquiry;
use App\Helpers\CustomHelper;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Response;
use DB;
use PDF;
use Validator;
use Session;
use Hash;
use Storage;
use File;
use Image;
use Agent;
use Location;
use DateTime;
use Carbon\Carbon as Carbon;
use Inertia\Inertia;

class UserController extends Controller {
	private $limit;
	protected $ADMIN_ROUTE_NAME;
	private $theme;

    public function __construct() {
    	$this->limit = CustomHelper::WebsiteSettings('FRONT_PAGE_LIMIT');
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->theme = config('custom.themejs');
    }

    public function index(){
        $data = [];

        $userId = Auth::user()->id;

        $userDetails = '';

        if(!empty($userId)){
            $userDetails = User::where('id',$userId)->first();
        }else{
            return abort(404);
        }

        if(!empty($userDetails)){

            $data['page_title'] = $userDetails->name.' | '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $data['page_heading'] = $userDetails->name;
            $data['userDetails'] = $userDetails;

        }else{
            return abort(404);
        }

        $data['countries'] = Country::where('status',1)->get();
        return Inertia::render('user/profile', $data);
        // return view('user.profile', $data);
    }

    public function dashboard(){
        return Inertia::render('user/agent_sales_graph', $data);
        //return view('user.agent_sales_graph');
    }

    public function loadSalesData(Request $request) {
        $response = [];
        $graph = [];
        $response['success'] = false;

        if(!empty(Auth::check())){
            //$userId = Auth::user()->id;
            $is_agent = Auth::user()->is_agent??0;
            if($is_agent == 1){
                $dayType = $request->dayType??'Daily';
                $startDate = $endDate = '';
                $status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
                $status_query->where(function($q1){
                    $q1->where('category','confirmed');
                });
                $status_arr = $status_query->get()->pluck('id')->toArray();

                $user = auth()->user();
                $user_id = 0;
                if($user) {
                    $user_id = $user->id??0;
                }
                if($dayType == 'Daily') {
                    $endDate = date('Y-m-d');
                    //$startDate = date("Y-m-d", strtotime( "$endDate -6 day" ) );
                    $startDate = date("Y-m-d", strtotime( "$endDate -1 months" ) );
                    $startDate = $startDate.' 00:00:00';
                    $endDate = $endDate.' 23:59:59';
                    $begin = new DateTime($startDate);
                    $end = new DateTime($endDate);
                    $query = Order::selectRaw('sum(total_amount) as total_sales,created_at as date, DATE_FORMAT(created_at, "%Y%m%d") AS yearMonth')->where('payment_status',1)->whereIn('orders_status',$status_arr)->whereBetween('created_at',[$startDate,$endDate])->groupByRaw('DATE_FORMAT(created_at, "%Y%m%d")')->orderBy('date','asc');
                    if($user_id){
                        $query->where('user_id',$user_id);
                    }
                    $records = $query->get();
                    $graph = [];
                    if($records) {
                        $data = [];
                        $labels = [];
                        if($records) {
                            foreach($records as $row) {
                                $data[CustomHelper::DateFormat($row->date,'Ymd')] = $row->total_sales;
                                $labels[] = CustomHelper::DateFormat($row->date,'Y-m-d');
                            }
                        }
                        for($i = $begin; $i <= $end; $i->modify('+1 day')){
                            $date_arr[]=$i->format("d M Y");
                            if(isset($data[$i->format("Ymd")])){
                                $data_array[] = $data[$i->format("Ymd")];
                            } else {
                                $data_array[] = 0;
                            }
                        }
                    }
                } else if($dayType == 'Weekly') {
                    $startDate=$endDate='';
                    $endDate=date('Y-m-d');
                    $startDate = date( "Y-m-d", strtotime( "$endDate -41 day" ) );
                    $startDate = $startDate.' 00:00:00';
                    $endDate = $endDate.' 23:59:59';
                    $begin = new DateTime( $startDate );
                    $end   = new DateTime( $endDate);
                    $date_arr = [];
                    for($i = 7; $i >= 1; $i--){
                        $date_arr[]=$i;
                    }
                    $query =Order::whereBetween('created_at',[$startDate,$endDate])->selectRaw('sum(total_amount) as total_sales, created_at, WEEKDAY(created_at) AS yearMonth')->where('payment_status',1)->whereIn('orders_status',$status_arr)->groupBy('yearMonth')->orderBy('yearMonth','asc'); 
                    if($user_id){
                        $query->where('user_id',$user_id);
                    }
                    $form_data = $query->get()->toArray();

                    $formsname=array();
                    $labels=array();
                    $data_array=array();
                    if(!empty($form_data)){
                        foreach($form_data as $data){
                            $data_array[$data['yearMonth']]['order']=$data['total_sales'];
                            $formsname['order']='order';
                        }
                    }
                    $finalData=[];
                    if(!empty($date_arr)) {
                        $cur_date = new DateTime(date('Y-m-d'));
                        $cur_week = $cur_date->format("W");
                        foreach($date_arr as $key=>$week_no) {
                            $week_arr = CustomHelper::getStartAndEndDateOfWeek($cur_week-($week_no-1),date('Y'));
                            $labels[]= CustomHelper::DateFormat($week_arr['start_date'],'d-M').' - '.CustomHelper::DateFormat($week_arr['end_date'],'d-M');
                            if(!empty($formsname)){
                                foreach($formsname as $frm){
                                    $finalData[$frm][]=isset($data_array[$week_no][$frm])?$data_array[$week_no][$frm]:0;
                                }
                            }else{
                                $finalData['order'][] =0;
                            }       
                        }
                        $finalData['form_name']=$formsname;
                    }
                    $data_array = $finalData['order'] ?? [];
                    $date_arr = $labels;
                } else if($dayType == 'Monthly') {
                    $startDate=$endDate='';
                    $startDate=date('Y-m-01',strtotime('-6 months')); 
                    $endDate=date('Y-m-t');
                    $startDate = $startDate.' 00:00:00';
                    $endDate = $endDate.' 23:59:59';
                    $date_arr=[];
                    $begin = new DateTime( $startDate );
                    $end   = new DateTime( $endDate);

                    for($i = $begin; $i <= $end; $i->modify('+1 months')){
                        $date_arr[$i->format("Ym")]=$i->format("Y-m-d");
                    }
                    $query=Order::whereBetween('created_at',[$startDate,$endDate])
                    ->selectRaw('sum(total_amount) as total_sales,EXTRACT(YEAR_MONTH FROM date(created_at)) AS yearMonth')->where('payment_status',1)->whereIn('orders_status',$status_arr)->groupBy('yearMonth')->orderBy('yearMonth','asc');
                    if($user_id){
                        $query->where('user_id',$user_id);
                    }
                    $form_data = $query->get()->toArray();

                    $formsname=array();
                    $data_array=array();
                    $labels=array();
                    if(!empty($form_data)){
                        foreach($form_data as $data){
                            $data_array[$data['yearMonth']]['Sales']=$data['total_sales'];
                            $formsname['Sales']='Sales';
                        }
                    }
                    $finalSaleData=[];
                    if(!empty($date_arr)){
                        foreach($date_arr as $key=>$rec){
                            $labels[]=date('M Y',strtotime($rec)); 
                            if(!empty($formsname)){
                                foreach($formsname as $frm){
                                    $finalSaleData[$frm][]=isset($data_array[$key][$frm])?$data_array[$key][$frm]:0;
                                }
                            } else {
                                $finalSaleData['Sales'][] =0;
                            }
                        }
                        $finalSaleData['form_name']=$formsname;
                    }
                    $data_array = $finalSaleData['Sales']?? [];
                    $date_arr = $labels;
                }
                $graph['data'] = $data_array;
                $graph['labels'] = $date_arr;       
                $response['success'] = true;
                $response['graph'] = $graph;

            }else{
                return abort(404);
            }
        }else{
            return abort(404);
        }
        return response()->json($response);
    }

    public function agent_info(){
        $data = [];
        $userId = Auth::user()->id;
        $userDetails = '';

        if(!empty($userId)){
            $userDetails = User::where('id',$userId)->first();
        }else{
            return abort(404);
        }

        if(!empty($userDetails)){

            $agentDetails = UserAgentInfo::where('user_id',$userId)->first();
            $data['page_title'] = $userDetails->name.' | '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $data['page_heading'] = $userDetails->name;
            $data['userDetails'] = $userDetails;
            $data['agentDetails'] = $agentDetails;

        }else{
            return abort(404);
        }

        $data['countries'] = Country::where('status',1)->get();
        return Inertia::render('user/agent_info', $data);
        return view('user.agent_info', $data);
    }
    /*public function viewfile(Request $request){
        echo $id = $request->id??0;
        $user_id = Auth::user()->id;
        if($user_id && $user_id != $id){
            return redirect('/');
        }
        $storage = Storage::disk('public');
        $agentDetails = UserAgentInfo::where('user_id',$user_id)->first();
        $agent_file = isset($agentDetails->pan_image)?$agentDetails->pan_image:'';
        $pdf_path = 'agentuser/';
        $pdf_url_path = '';
        if($agent_file) {
            $pdf_url_path = asset('/storage/'.$pdf_path.$agent_file);
        }
        $response = $storage->exists($pdf_path.$agent_file);
        if($response){
            try {
                return Storage::disk('public')->response($pdf_path.$agent_file);
            } catch (S3Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }else{
            return redirect()->back()->with('alert-danger', 'You can not access.');
        }
    }*/
    public function changePassword(Request $request){

        $data = [];

        $user = auth()->user();

        $method = $request->method();

        if($method == 'POST'){

            $rules = [];
            $validation_msg = [];

            if(!empty($user->password)){
                $rules['current_password'] = 'required';
            }

            $rules['new_password'] = 'required|min:6';
            $rules['confirm_password'] = 'required|same:new_password';

            $validator = Validator::make($request->all(), $rules, $validation_msg);

            if(!empty($user->password)){
                $validator->after(function($validator) use ($user){
                    if (!Hash::check(request('current_password'), $user->password)){
                        $validator->errors()->add('current_password', 'Invalid password!');
                    }
                    else{
                        session(['verify_password'=>TRUE, 'verify_time'=>date('Y-m-d H:i:s')]);
                    }
                });
            }

            if ($validator->fails()){
                return back()->withErrors($validator);
            }
            else{
                $password = bcrypt($request->new_password);

                $user->password = $password;

                $isSaved = $user->save();

                if($isSaved){
                    if(!empty($user->email)) {

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
                        $location ='';
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

                        // $subject = 'Password changed - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                        // $viewHtml = view('emails.password_change', $email_params)->render();
                        // $email_content = strtr($viewHtml, $email_params);
                        // $from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
                        // $emailData = [];
                        // $emailData['name'] = $user->name;

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
                            $params['module_name'] = 'User change password';
                            $params['record_id'] = $user->id ?? 0;
                            $is_mail = CustomHelper::sendCommonMail($params);
                        }
                        // $is_mail = CustomHelper::sendEmail('emails.password_change', $emailData, $to=$to_email, $from_email, $replyTo = $from_email, $subject);
                    }
                    return back()->with('alert-success', 'Password has been changed successfully.');
                }
                else{
                    return back()->with('alert-danger', 'something went wrong, please try again...');
                }
            }

        }

        $data['user'] = $user;

        $data['meta_title'] = 'Change-Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Change-Password - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        return Inertia::render('user/change_password', $data);
        return view('user.change_password', $data);

    }

    public function updateUserDetails(Request $request){
        $response = [];
        $response['success'] = false;

        $errors = [];

        $method = $request->method();
        $userId = "";
        if(Auth::check()){
            $userId = Auth::user()->id;
            $userDetails = User::find($userId);
        }

        if($method == 'POST' && !empty($userId)){
            $ext = "jpg,jpeg,png,gif";
            $rules = [];
            $rules['name'] = 'required|max:255';
            //$rules['phone'] = 'required|min:4|max:12|regex:/^([0-9\s\-\+\(\)]*)$/';
                // $rules["profile_image"] = "required|image|max:2048|mimes:" . $ext;
            // $rules['dob'] = 'required';
            // $rules['gender'] = 'required';
            $rules['city'] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
            $rules['state'] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
            $rules['address1'] = 'required|max:255';
            $rules['address2'] = 'nullable|max:255';
            $rules['country'] = 'required';
            // $rules['zipcode'] = 'min:6|max:10|regex:/^[\s\w-]*$/';
            $rules['zipcode'] = 'max:10|regex:/^[\s\w-]*$/';

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

            $messages = [];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->passes()){
                //update user details

                $image = $request->profile_image_old;
                if($request->hasFile('profile_image')){
                    $imgData = $request->file('profile_image');
                    //prd($imgData);
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

                    $oldImg = $request->profile_image_old;
                    if(!empty($oldImg)){
                        if($storage->exists($path.$oldImg)){
                            $storage->delete($path.$oldImg);
                        }
                        if($storage->exists($thumb_path.$oldImg)){
                            $storage->delete($thumb_path.$oldImg);
                        }
                    }

                    $uploaded_data = CustomHelper::UploadImage($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
                    //prd($uploaded_data);
                    if($uploaded_data['success']){
                        $image = $uploaded_data['file_name'];
                    }
                }

                $req_data = [];
                $req_data['dob'] = $request->dob ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'): '';
                $req_data['name'] = !empty($request->name)?$request->name:'';
                $req_data['country'] = !empty($request->country)?$request->country:'';
                $req_data['profile_image'] = $image;
                $req_data['phone'] = !empty($request->phone)?$request->phone:'';
                $req_data['country_code'] = !empty($request->country_code)?$request->country_code:91;
                $req_data['gender'] = !empty($request->gender)?$request->gender:'';
                $req_data['city'] = !empty($request->city)?$request->city:'';
                $req_data['state'] = !empty($request->state)?$request->state:'';
                $req_data['zipcode'] = !empty($request->zipcode)?$request->zipcode:'';
                $req_data['address1'] = !empty($request->address1)?$request->address1:'';
                $req_data['address2'] = !empty($request->address2)?$request->address2:'';

                /*User::where("id", $userId)->update(["name" => $request->name,"country" => $request->country,"profile_image" => $image,"phone" => $request->phone,"gender" => $request->gender,"city" => $request->city,"state" => $request->state,"zipcode" => $request->zipcode,"address1" => $request->address1,"address2" => $request->address2,"dob" =>$dob ]);*/

                //prd($req_data);
                User::where("id", $userId)->update($req_data);

                $response['success'] = true;

                Session::flash('alert-success', 'Details updated successfully!');

            }
            else{
                $response['message'] = "Please check your data there are some details are not up to the mark.";
                $errors = $validator->errors();
            }
        }else{
            $errors['error_msg'] = "Invalid Request.";
        }

        $response['errors'] = $errors;

        //$data['countries'] = Country::where('status',1)->get();

        return response()->json($response);
    }


    public function updateAgentDetails(Request $request){
        $response = [];
        $response['success'] = false;
        $errors = [];

        $method = $request->method();
        $userId = "";
        if(Auth::check()){
            $userId = Auth::user()->id;
            $userDetails = User::find($userId);
        }

        if($method == 'POST' && !empty($userId)){
            $ext = "jpg,jpeg,png,gif";
            $ext2 = 'jpg,jpeg,pdf';
            $rules = [];
            $rules['company_name'] = 'required|max:255';
            $rules['company_brand'] = 'required|max:255';
            $rules['company_owner_name'] = 'required|max:255';
            $rules['pan_no'] = 'required|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';

            //$rules['gst_no'] = 'nullable|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([A-Za-z]){2}?$/';
            $rules['gst_no'] = 'nullable|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/';
            $rules['agent_logo'] = 'nullable|mimes:'.$ext; 

            if(empty($userId)){
                $rules['pan_image'] = 'nullable';
            }else{
                $rules['pan_image'] = 'max:10240|mimes:'.$ext2; 
            }
            if(empty($userId)){
                $rules['gst_image'] = 'nullable';
            }else{
                $rules['gst_image'] = 'max:10240|mimes:'.$ext2; 
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


            $messages = [];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->passes()){
                $checkExist = UserAgentInfo::where("user_id", $userId)->first();
                //update user details

                $agent_info = array(
                    'user_id' => $userId,
                    'company_name' => $request->company_name ?? '',
                    'company_brand' => $request->company_brand ?? '',
                    'company_owner_name' => $request->company_owner_name ?? '',
                    'bookings_per_month' => $request->bookings_per_month ?? 0,
                    'sales_team_size' => $request->sales_team_size ?? 0,
                    'source' => $request->source ?? '',
                    'pan_no' => $request->pan_no ?? '',
                    'gst_no' => $request->gst_no ?? '',
                    'website' => $request->website ?? '',
                    'whatsapp_phone' => $whatsapp_phone ?? 0,
                    'whatsapp_country_code' => $whatsapp_country_code,
                    'destinations_sell_most' => $request->destinations_sell_most ?? '',
                    'agency_age' => $request->agency_age ?? 0,
                    'no_of_employees' => $request->no_of_employees ?? 0,
                    'region' => $request->region ?? '',
                    'company_profile' => $request->company_profile ?? '',
                    'query' => $request->queries ?? '',
                );

                if(!empty($checkExist) && $checkExist->count() > 0){
                    UserAgentInfo::where("user_id", $checkExist->user_id)->update($agent_info);
                    $id = $checkExist->id;
                }else{
                    $isSaved = UserAgentInfo::create($agent_info);
                    $id = $isSaved->id;
                }

                if ($request->hasFile("agent_logo")) {
                    $file = $request->file("agent_logo");
                    $image_result = $this->saveImage($file, $id);
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }
                if ($request->hasFile("pan_image")) {
                    $file = $request->file("pan_image");
                    $image_result = $this->saveFile($file, $id, "pan_image");
                    if ($image_result["success"] == false) {
                        session()->flash("alert-danger","PAN Image could not be added");
                    }
                }
                if($request->hasFile('gst_image')) {
                    $file = $request->file('gst_image');
                    $image_result = $this->saveFile($file,$id,'gst_image');
                    if($image_result['success'] == false){     
                        session()->flash('alert-danger', 'GST Image could not be added');
                    }
                }

                // User::where($agent_info);

                $response['success'] = true;
                Session::flash('alert-success', 'Details updated successfully!');
            }
            else{
                $response['message'] = "Please check your data there are some details are not up to the mark.";
                $errors = $validator->errors();
            }
        }else{
            $errors['error_msg'] = "Invalid Request.";
        }

        $response['errors'] = $errors;

        return response()->json($response);
    }


    public function saveImage($file, $id)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "agent_logo/";
            $thumb_path = "agent_logo/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("COMMON_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("COMMON_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("COMMON_THUMB_HEIGHT");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("COMMON_THUMB_WIDTH");

            $IMG_WIDTH = !empty($IMG_WIDTH) ? $IMG_WIDTH : 800;
            $IMG_HEIGHT = !empty($IMG_HEIGHT) ? $IMG_HEIGHT : 800;
            $THUMB_WIDTH = !empty($THUMB_WIDTH) ? $THUMB_WIDTH : 400;
            $THUMB_HEIGHT = !empty($THUMB_HEIGHT) ? $IMG_WIDTH : 400;

            $uploaded_data = CustomHelper::UploadImage(
                $file,
                $path,
                $ext = "",
                $IMG_WIDTH,
                $IMG_HEIGHT,
                $is_thumb = true,
                $thumb_path,
                $THUMB_WIDTH,
                $THUMB_HEIGHT
            );

            if ($uploaded_data["success"]) {
                $new_image = $uploaded_data["file_name"];
                if (is_numeric($id) && $id > 0) {
                    $agent_info = UserAgentInfo::find($id);

                    if (!empty($agent_info) && $agent_info->count() > 0) {
                        $storage = Storage::disk("public");
                        $old_image = $agent_info->agent_logo;
                        $agent_info->agent_logo = $new_image;
                        $isUpdated = $agent_info->save();

                        if ($isUpdated) {
                            if (!empty($old_image) && $storage->exists($path . $old_image)) {
                                $storage->delete($path . $old_image);
                            }
                            if (!empty($old_image) && $storage->exists($thumb_path . $old_image)) {
                                $storage->delete($thumb_path . $old_image);
                            }
                        }
                    }
                }
            }
            if (!empty($uploaded_data)) {
                return $uploaded_data;
            }
        }
    }
    public function saveFile($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = 'agentuser/';
            $thumb_path = 'agentuser/thumb/';
            $ext='jpg,jpeg,pdf';

            /*$IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "AGENT_USER_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "AGENT_USER_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "AGENT_USER_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "AGENT_USER_IMG_THUMB_HEIGHT"
            );

            $IMG_WIDTH = !empty($IMG_WIDTH) ? $IMG_WIDTH : 768;
            $IMG_HEIGHT = !empty($IMG_HEIGHT) ? $IMG_HEIGHT : 768;
            $THUMB_WIDTH = !empty($THUMB_WIDTH) ? $THUMB_WIDTH : 336;
            $THUMB_HEIGHT = !empty($THUMB_HEIGHT) ? $IMG_WIDTH : 336;*/

            $uploaded_data = CustomHelper::UploadFile($file, $path, $ext);
           // prd($uploaded_data);    

            if ($uploaded_data["success"]) {
                $new_image = $uploaded_data["file_name"];

                if (is_numeric($id) && $id > 0) {

                    $agentQuery = UserAgentInfo::find($id);
                    if (!empty($agentQuery)) {
                        $storage = Storage::disk("public");

                        if($type == 'pan_image'){
                            $old_image = $agentQuery->pan_image;
                            $agentQuery->pan_image = $new_image;
                        }
                        elseif($type == 'gst_image'){
                            $old_image = $agentQuery->gst_image;
                            $agentQuery->gst_image = $new_image;
                        }

                        $isUpdated = $agentQuery->save();

                        if ($isUpdated) {
                            if (!empty($old_image) && $storage->exists($path . $old_image)) {
                                $storage->delete($path . $old_image);
                            }
                            if (!empty($old_image) && $storage->exists($thumb_path . $old_image)
                        ) {
                                $storage->delete($thumb_path . $old_image);
                        }
                    }
                }
            }
        }
        if (!empty($uploaded_data)) {
            return $uploaded_data;
        }
    }
}

public function deleteAgentLogo(Request $request){
    $response = [];
    $response['success'] = false;
    $message = '';
    $id = (isset($request->id))?$request->id:0;
    $is_deleted = 0;
    if(is_numeric($id) && $id > 0){
        $user_agent = UserAgentInfo::find($id);
        if(!empty($user_agent)){
            $storage = Storage::disk('public');
            $path = 'agent_logo/';
            $thumb_path = 'agent_logo/thumb/';
            $agent_logo = $user_agent->agent_logo;
            if(!empty($agent_logo) && $storage->exists($path.$agent_logo)) {
                $is_deleted = $storage->delete($path.$agent_logo);
            }
            if(!empty($agent_logo) && $storage->exists($thumb_path.$agent_logo)) {
                $is_deleted = $storage->delete($thumb_path.$agent_logo);
            }
            if($is_deleted) {
                $user_agent->agent_logo = '';
                $user_agent->save();
            }
        }

        if($is_deleted) {
            $response['success'] = true;
            $message = '<div class="alert alert-success alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been deleted succesfully. </div';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Something went wrong, please try again... </div';
        }
        $response['message'] = $message;
        return response()->json($response);
    }
}

public function signout(Request $request) {
    auth()->logout();
    \Session::flush();
    \Session::flash('alert-success','You are logout successfully');
    return redirect('/');
}

public function myBooking(Request $request) {
    $data = [];
    $limit = $this->limit;
    $userId = "";
    if(!empty(Auth::check())) {
        $userId = Auth::user()->id;
        $is_agent = Auth::user()->is_agent??0;
            $query = Order::with('Package')->orderBy('created_at','desc')->where('order_type','!=',7); //not wallet;
            $query->where(function($q1) use($userId) {
                $q1->orWhere('user_id',$userId);
            });
            if($request->order_id) {
                $query->where('order_no',$request->order_id);
            }
            if($request->order_type) {
                $query->where('order_type',$request->order_type);
            }

            if($is_agent == 1){

                if(empty($request->todays_orders) && empty($request->yesterdays_orders) && empty($request->all_orders)) {
                    if($request->order_status && is_array($request->order_status)) {
                        $getIds = EnquiriesMaster::whereIn('category', $request->order_status)->select('id')->get();
                        $xArray=[];
                        foreach ($getIds as $key => $get_id) {
                            $xArray[]=$get_id->id;
                        }
                        if(in_array('new',$request->order_status)){
                            $query->where(function ($sub_query) use ($xArray) {
                                $sub_query->where('orders_status',0);
                                $sub_query->orWhereIn('orders_status', $xArray);
                            });
                        }else{
                            $query->where('orders_status','!=',0);
                            $query->WhereIn('orders_status', $xArray);
                        }
                    }
                }
                $from_date = '';
                $to_date = '';
                $range = $request->range;
                if(!empty($range) && $range != 'custom' && (empty($request->todays_orders) && empty($request->yesterdays_orders))) {
                    $date_between_arr = CustomHelper::get_to_from_date($range);
                    $from_date = $date_between_arr['from'];
                    $to_date = $date_between_arr['to'];
                } else if(!empty($request->todays_orders)) {
                    $from_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                    $to_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                    $to_date = $to_date.' 23:59:59';
                } else if(!empty($request->yesterdays_orders)) {
                    $from_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                    $to_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                    $to_date = $to_date.' 23:59:59';
                } else {
                    $from = $request->from ?? date('d/m/Y') ;
                    $to = $request->to ?? date('d/m/Y') ;
                    if($from && $to) {
                        $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                        $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                        $from_date = $from_date.' 00:00:00';
                        $to_date = $to_date.' 23:59:59';
                    } else if($from) {
                        $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                        $from_date = $from_date.' 00:00:00';
                    } else if($to) {
                        $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                        $to_date = $to_date.' 23:59:59';
                    }
                }
            }
            if(!empty($from_date)) {
                $query->whereDate('created_at','>=',$from_date);
            }
            if(!empty($to_date)) {
                $query->whereDate('created_at','<=',$to_date);
            }
            $orders = $query->paginate($limit);

            $data['uesrOrders'] = $orders;
            $data['meta_title'] = 'My Booking - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $data['meta_description'] = 'My Booking - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $search_data = $request->toArray();
            unset($search_data['order_type']);
            $data['search_data'] = $search_data;
            return Inertia::render('user/my_booking', $data);
            //return view('user.my_booking', $data);
        } else {
            return abort(404);
        }
    }

    public function myEnquiry(Request $request) {
        $data = [];
        $limit = $this->limit;
        $userId = "";
        if(!empty(Auth::check())) {
            $userId = Auth::user()->id;
            $is_agent = Auth::user()->is_agent??0;
            $query = Enquiry::orderBy('created_at','desc');
            $query->where(function($q1){
                $q1->where('is_deleted',0);
                $q1->orWhereNull('is_deleted');
            });
            $query->where(function($q1) use($userId) {
                $q1->orWhere('user_id',$userId);
            });
            
            if($request->search) {
                $search = $request->search;
                if($search) {
                    $query->where(function($q1) use($search) {
                        $q1->where("name", "like", "%".$search."%");
                        $q1->orWhere("phone", $search);
                        $q1->orWhere("email", $search);
                    });                 
                }
            }

            if($request->order_type) {
                $order_type = $request->order_type;
                $query->whereHas("EnquiryForType", function($q1) use($order_type) {
                    $q1->where('enquiry_for_id',$order_type);
                });
            }

            if($request->enquiry_for) {
                if(is_array($request->enquiry_for)) {
                    $enquiry_for_ids = $request->enquiry_for;
                } else {
                    $enquiry_for_ids = explode(',', $request->enquiry_for);
                }
                if(!empty($enquiry_for_ids)) {
                    $query->whereHas("EnquiryForType", function($q1) use($enquiry_for_ids) {
                        $q1->whereIn('enquiry_for_id',$enquiry_for_ids);
                    });
                }
            }

            if($request->destination) {
                if(is_array($request->destination)) {
                    $destination_ids = $request->destination;
                } else {
                    $destination_ids = explode(',', $request->destination);
                }
                if(!empty($destination_ids)) {
                    $query->where(function($q1) use($destination_ids) {
                        $q1->whereHas("Destination", function($q2) use($destination_ids) {
                            $q2->whereIn('destination_id',$destination_ids);
                        })->orWhereHas("SubDestination", function($q2) use($destination_ids) {
                            $q2->whereIn('destination_id',$destination_ids);
                        });
                    });
                }
            }

            $from_date = '';
            $to_date = '';
            $range = $request->range;
            if(!empty($range) && $range != 'custom' && (empty($request->todays_orders) && empty($request->yesterdays_orders))) {
                $date_between_arr = CustomHelper::get_to_from_date($range);
                $from_date = $date_between_arr['from'];
                $to_date = $date_between_arr['to'];
            } else if(!empty($request->todays_orders)) {
                $from_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else if(!empty($request->yesterdays_orders)) {
                $from_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else {
                $from = $request->from ?? date('d/m/Y') ;
                $to = $request->to ?? date('d/m/Y') ;
                if($from && $to) {
                    $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                    $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                    $to_date = $to_date.' 23:59:59';
                } else if($from) {
                    $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                } else if($to) {
                    $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                    $to_date = $to_date.' 23:59:59';
                }
            }
            if(!empty($from_date)) {
                $query->whereDate('created_at','>=',$from_date);
            }
            if(!empty($to_date)) {
                $query->whereDate('created_at','<=',$to_date);
            }
            $enquiries = $query->paginate($limit);
            // prd($enquiries->toArray());

            $data['enquiries'] = $enquiries;
            $data['meta_title'] = 'My Enquiry - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $data['meta_description'] = 'My Enquiry - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $search_data = $request->toArray();
            unset($search_data['order_type']);
            $data['search_data'] = $search_data;

            return Inertia::render('user/my_enquiry', $data);
            // return view('user.my_enquiry', $data);
        } else {
            return abort(404);
        }
    }


    public function calendarBooking(Request $request) {

        $data = [];
        $limit = $this->limit;
        $userId = "";
        //$payment_status = $request->order??'';
        if(!empty(Auth::check())){
            $userId = Auth::user()->id;
            $is_agent = Auth::user()->is_agent??0;
            if($is_agent == 1){
        //if(!empty($payment_status)) {

                $status_arr = [];
                $status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
                $status_query->where(function($q1){
                    $q1->where('category','confirmed');
                });
                $status_arr = $status_query->get()->pluck('id')->toArray();
            /*$getStatues = EnquiriesMaster::where('status',1)->where(['type'=>'order-status'])->get();
            if($getStatues){
                foreach($getStatues as $statuses){
                    if($statuses->slug == 'order_accepted' || $statuses->slug == 'confirmed'){
                        $status_arr[] = $statuses->id??0;
                    }
                }
            }*/

            $query = Order::where('payment_status',1)->whereNotNull('trip_date')->orderBy('trip_date', 'desc')->where('cancel_request',0);

            
            $query->where(function($q1) use($userId) {
                $q1->orWhere('user_id',$userId);
            });

            $group_date_query=Order::select('trip_date')->where('payment_status',1)->whereNotNull('trip_date')->orderBy('trip_date', 'desc')->where('cancel_request',0);
            $group_date_query->where(function($q1) use($userId) {
                $q1->orWhere('user_id',$userId);
            });

            $group_date_query->where(function($q1) use ($status_arr){
                $q1->where(function($q2) use ($status_arr) {
                    $q2->where('order_type','!=',3);
                    // $q2->whereIn('status',['Order Accepted','Voucher created']);
                    $q2->whereIn('orders_status',$status_arr);
                });
                $q1->orWhere(function($q2) {
                    $q2->where('order_type',3);
                    $q2->whereIn('status',['SUCCESS','PENDING','ON_HOLD']);
                });
            });

            $query->where(function($q1) use ($status_arr) {
                $q1->where(function($q2) use ($status_arr) {
                    $q2->where('order_type','!=',3);
                        // $q2->whereIn('status',['Order Accepted','Voucher created']);
                    $q2->whereIn('orders_status',$status_arr);                        
                });
                $q1->orWhere(function($q2) {
                    $q2->where('order_type',3);
                    $q2->whereIn('status',['SUCCESS','PENDING','ON_HOLD']);
                });
            });


            /*if(empty($request->todays_orders) && empty($request->yesterdays_orders) && empty($request->all_orders)) {
                if($request->order_status && is_array($request->order_status)) {

                    $getIds = EnquiriesMaster::whereIn('category', $request->order_status)->select('id')->get();
                    $xArray=[];
                    foreach ($getIds as $key => $get_id) {
                        $xArray[]=$get_id->id;
                    }

                    if(in_array('new',$request->order_status)){

                        $query->where(function ($sub_query) use ($xArray) {
                            $sub_query->whereNull('orders_status');
                            $sub_query->orWhereIn('orders_status', $xArray);
                        });

                        $group_date_query->where(function ($sub_query) use ($xArray) {
                            $sub_query->whereNull('orders_status');
                            $sub_query->orWhereIn('orders_status', $xArray);
                        });

                    }else{
                        $query->whereNotNull('orders_status');
                        $query->WhereIn('orders_status', $xArray);

                        $group_date_query->whereNotNull('orders_status');
                        $group_date_query->WhereIn('orders_status', $xArray);
                    }

                } else {

                    $autoSelStatus = ['new','accepted'];
                    $getIds = EnquiriesMaster::whereIn('category', $autoSelStatus)->select('id')->get();
                    $xArray=[];
                    foreach ($getIds as $get_id) {
                        $xArray[]=$get_id->id;
                    }
                        $query->where(function ($sub_query) use ($xArray) {
                            $sub_query->whereNull('orders_status');
                            $sub_query->orWhereIn('orders_status', $xArray);
                        });

                        $group_date_query->where(function ($sub_query) use ($xArray) {
                            $sub_query->whereNull('orders_status');
                            $sub_query->orWhereIn('orders_status', $xArray);
                        });

                }
            }*/

            $from_date = '';
            $to_date = '';
            $range = $request->range;
            if(!empty($range) && $range != 'custom' && (empty($request->todays_orders) && empty($request->yesterdays_orders))) {
                $date_between_arr = CustomHelper::get_to_from_date($range);
                $from_date = $date_between_arr['from'];
                $to_date = $date_between_arr['to'];
            } else if(!empty($request->todays_orders)) {
                $from_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else if(!empty($request->yesterdays_orders)) {
                $from_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else {
                $from = $request->from ?? date('d/m/Y') ;
                $to = $request->to ?? date('d/m/Y') ;
                if($from && $to) {
                    $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                    $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                    $to_date = $to_date.' 23:59:59';
                } else if($from) {
                    $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                } else if($to) {
                    $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                    $to_date = $to_date.' 23:59:59';
                }
            }

            if(!empty($from_date)) {
                $query->whereDate('trip_date','>=',$from_date);
                $group_date_query->whereDate('trip_date','>=',$from_date);
            }
            if(!empty($to_date)) {
                $query->whereDate('trip_date','<=',$to_date);
                $group_date_query->whereDate('trip_date','<=',$to_date);
            }

            if($request->order_type) {
                $query->where('order_type',$request->order_type);
                $group_date_query->where('order_type',$request->order_type);
            }

            $orders = $query->get();
            $group_date = $group_date_query->groupBy(DB::raw('Date(trip_date)'))->get();
        //}
            $data['orders'] = $orders;
            $data['group_date'] = $group_date;
        //$data['payment_status'] = $payment_status;
            $data['meta_title'] = 'Calendar Booking - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $data['meta_description'] = 'Calendar Booking - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

            $search_data = $request->toArray();
            unset($search_data['order_type']);
            $data['search_data'] = $search_data;

            return Inertia::render('user/calendar_booking', $data);
            // return view('user.calendar_booking', $data);
        }else{
            return abort(404);
        }
    }else{
        return abort(404);
    }
}
public function myWallet(Request $request){

    $data = [];
    $limit = $this->limit;
    $userId = "";

    if(!empty(Auth::check())){
        $userId = Auth::user()->id;
        $is_agent = Auth::user()->is_agent;
            // if($is_agent == 1){
        $balance = UserWallet::where('user_id',$userId)->sum('amount');
        $credit_query = UserWallet::where('user_id',$userId)->where('type','credit');
        $total_credit = UserWallet::where('user_id',$userId)->where('type','credit')->sum('amount');

        $debit_query = UserWallet::where('user_id',$userId)->where('type','debit');
        $total_debit = UserWallet::where('user_id',$userId)->where('type','debit')->sum('amount');

        $query = UserWallet::where('user_id',$userId)->orderBy('id','desc');

        $today = date("d/m/Y");
        $previous_month = date("d/m/Y", strtotime("-1 months"));
        $from = (isset($request->from))?$request->from:$previous_month;
        $to = (isset($request->to))?$request->to:$today;

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
        // }else{
        //     return abort(404);
        // }
    }else{
        return abort(404);
    }

    $data['date_credit'] = $date_credit;
    $data['date_debit'] = $date_debit;
    $data['date_sum'] = $date_credit + $date_debit;

    $data['total_credit'] = $total_credit;
    $data['total_debit'] = $total_debit;

    $data['balance'] = $balance;
    $data['wallets'] = $wallets;

    $data['from'] = $from;
    $data['to'] = $to;

    $data['meta_title'] = 'My Wallet - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'My Wallet - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    return Inertia::render('user/wallet', $data);
    // return view('user.wallet', $data);
}


public function manageGst_30_06_23(Request $request){

    $data = [];
    $limit = $this->limit;
    $userId = "";

    if(!empty(Auth::check())){

        $gstQuery = '';
        $method = $request->method();
        $id = isset($request->id)?$request->id:0;
        //prd($id);
        $page_heading = 'Manage GST';
        $userId = Auth::user()->id;
        $is_agent = Auth::user()->is_agent;


        if(is_numeric($id) && $id > 0){
            $gstQuery = UserGstInfo::find($id);
            //prd($gstQuery);
        }

        if($method == 'POST' || $method == 'post'){

            //prd($request->toArray());
            $isSaved = false;
            $rules = [];
            $validation_msg = [];
            $rules['gst_number'] = 'required|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/';
            $rules['gst_phone'] = 'required|digits:10';
            $rules['gst_email'] = 'required|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';

            $validation_msg['gst_number.required'] = 'The GST field is required.';
            $validation_msg['gst_phone.required'] = 'The Phone field is required.';
            $validation_msg['gst_email.required'] = 'The Email field is required.';

            $validator = $this->validate($request, $rules,$validation_msg);

            if(!empty($id)){
                $gst = UserGstInfo::find($id);
            }else{

                $gst = new UserGstInfo;
            }


            $action = 'Add';
            $success_msg = 'GST has been added successfully';
            if(isset($gstQuery->id) && !empty($gstQuery->id)){
                $gst = $gstQuery;
                $action = 'Update';
                $success_msg = 'GST has been updated successfully';
            }
            echo   $gstNo = (!empty($request->gst_number))?$request->gst_number:'';

            $row = UserGstInfo::where('gst_number',$gstNo)->where('user_id',$userId)->whereNot('id',$id)->first();

            // prd($row);
            if($row){
                $isSaved = false;

            }else{

                $gst['user_id'] = $userId;
                $gst['gst_number'] = (!empty($request->gst_number))?$request->gst_number:'';
                $gst['gst_company'] = (!empty($request->gst_company))?$request->gst_company:'';
                $gst['gst_phone'] = (!empty($request->gst_phone))?$request->gst_phone:'';
                $gst['gst_email'] = (!empty($request->gst_email))?$request->gst_email:'';
                $gst['gst_address'] = (!empty($request->gst_address))?$request->gst_address:'';
                //prd($gst);
                $isSaved = $gst->save();
                $user_gst_id = $gst->id??'';

            }
            if($isSaved){

                //=============Activity Logs=====================

                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;

                $new_data = DB::table('user_gst_info')->where('id',$user_gst_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $user_gst_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Manage GST';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = $action;

                CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

                session()->flash('alert-success', $success_msg);
                return redirect(url('user/gst'));
            }else{
                session()->flash('alert-danger', 'GST info not saved');
                return redirect(url('user/gst'));
            }

        }


    }
    else{
        return abort(404);
    }

    $data = [];
    $gstList = UserGstInfo::orderBy('id', 'asc');        
    $gstDetails = $gstList->where('user_id',$userId)->paginate($limit);
    $data['page_heading'] = $page_heading;
    $data['gstDetails'] = $gstDetails;
    $data['gstQuery'] = $gstQuery;

    $data['meta_title'] = 'Manage GST - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Manage GST - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');


    return view('user.gst', $data);
}

public function manageGst(Request $request){

    $data = [];
    $limit = $this->limit;
    $userId = "";

    if(!empty(Auth::check())){
        $userId = Auth::user()->id;
        $is_agent = Auth::user()->is_agent;
        $method = $request->method();
        $id = isset($request->id)?$request->id:0;
            //prd($id);
        $page_heading = 'Manage GST';

        $gstQuery = UserGstInfo::orderBy('id','asc');

        if($request->gst_number) {
            $gst_number = $request->gst_number;
            $gstQuery->where('gst_number','like',"%$gst_number");
        }

        if($request->gst_email) {
            $gstQuery->where('gst_email',$request->gst_email);
        }
        if($request->gst_phone) {
            $gst_phone = $request->gst_phone;
            $gstQuery->where('gst_phone','like',"%$gst_phone");
        }

        $gstQueries = $gstQuery->where('user_id',$userId)->paginate($limit);
    }else{
        return abort(404);
    }

    $data['gstQueries'] = $gstQueries;
        //prd($data['gstQueries']);
    $data['page_heading'] = $page_heading;
    $data['meta_title'] = 'Manage GST - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Manage GST - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

    return Inertia::render('user/gst', $data);
    // return view('user.gst', $data);
}

public function addGst(Request $request){
    $data = [];
    $limit = $this->limit;
    $userId = "";

    if(!empty(Auth::check())){

        $gstQuery = '';
        $method = $request->method();
        $id = isset($request->id)?$request->id:0;
        //prd($id);
        $page_heading = 'Add GST Info';
        $userId = Auth::user()->id;
        $is_agent = Auth::user()->is_agent;

        if(is_numeric($id) && $id > 0){
            $gstQuery = UserGstInfo::find($id);
            // $gst_info = $gstQuery->gst_number??'';
            $page_heading = 'Edit Gst Info (GST Number: #'.$gstQuery->gst_number.")";
        }

        if($request->method() == 'POST' || $request->method() == 'post'){

       // prd($request->toArray());
            $isSaved = false;
            $rules = [];
            $nicknames = [];
            $validation_msg = [];
            if (is_numeric($id) && $id > 0) {
                $rules["gst_number"] = 'required|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/';
            }else{
                $rules["gst_number"] = 
                $rules['gst_number'] = 'required|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/|unique:user_gst_info';

            }
            //$rules['gst_number'] = 'required|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/';
            $rules['gst_email'] = 'required|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            $rules['gst_phone'] = 'required|digits:10';
            $nicknames["gst_number"] = "GST Number";
            $nicknames["gst_phone"] = "Phone";
            $nicknames["gst_email"] = "Email";

            $validation_msg['gst_number.required'] = 'The GST field is required.';
            $validation_msg['gst_email.required'] = 'The Email field is required.';
            $validation_msg['gst_phone.required'] = 'The Phone field is required.';

            $validator = $this->validate($request, $rules,$validation_msg,$nicknames);
            if(!empty($id)){
                $gst = UserGstInfo::find($id);
            }else{

                $gst = new UserGstInfo;
            }

            echo $gstNo = (!empty($request->gst_number))?$request->gst_number:'';
            $row = UserGstInfo::where('gst_number',$gstNo)->where('user_id',$userId)->whereNot('id',$id)->first();

            // prd($row);
            if($row){
                $isSaved = false;

            }else{

                $req_data = [];
                $req_data['user_id'] = $userId;
                $req_data['gst_number'] = (!empty($request->gst_number))?$request->gst_number:'';
                $req_data['gst_company'] = (!empty($request->gst_company))?$request->gst_company:'';
                $req_data['gst_phone'] = (!empty($request->gst_phone))?$request->gst_phone:'';
                $req_data['gst_email'] = (!empty($request->gst_email))?$request->gst_email:'';
                $req_data['gst_address'] = (!empty($request->gst_address))?$request->gst_address:'';

                if(!empty($gstQuery) && count(array($gstQuery)) > 0){

                    $isSaved = UserGstInfo::where('id', $gstQuery->id)->update($req_data);
                    $data_id = $gstQuery->id;
                    $success_msg="GST Info has been updated successfully.";
                }
                else{
                    $isSaved = UserGstInfo::create($req_data);
                    $data_id = $isSaved->id;
                    $success_msg="GST Info has been added successfully.";
                }

            }

            if ($isSaved) {

            //=============Activity Logs=====================
                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;

                $new_data = DB::table('user_gst_info')->where('id',$data_id)->first();
                $gst_no =  !empty($new_data->gst_number)?$new_data->gst_number:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $data_id;
                $module_desc= $gst_no;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Manage GST';
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
            // cache()->forget('cms');

                session()->flash('alert-success', $success_msg);
                return redirect(url('user/gst'));
            }else{
                session()->flash('alert-danger', 'GST info not saved');
                return redirect(url('user/gst'));
            }
        }


    }
    else{
        return abort(404);
    }

    $data = [];
    $data["page_heading"] = $page_heading;
    $data['gst'] = $gstQuery;
    $data['id'] = $id;

    return Inertia::render('user/add_gst', $data);
    // return view('user.add_gst', $data);

}
public function addwallet(Request $request){

    $data = [];
    $limit = $this->limit;
    $userId = "";

    if(!empty(Auth::check())){
        $userId = Auth::user()->id;
        $is_agent = Auth::user()->is_agent;

        $query = UserWallet::orderBy('created_at','desc');

        $orders = $query->paginate($limit);
    }else{
        return abort(404);
    }

    $data['uesrOrders'] = $orders;
    $data['meta_title'] = 'My Wallet - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'My Wallet - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

    return Inertia::render('user/wallet', $data);
    // return view('user.wallet', $data);
}



public function packages(Request $request){

    $data = [];
    $limit = $this->limit;
    $userId = "";

    if(!empty(Auth::check())){
        $userId = Auth::user()->id;
        $package_query = Package::where('status',1)->where('is_activity',0)->orderBy('created_at','desc');
        $name = $request->name ?? '';
        $destination_id = $request->destination ?? '';
        if($name){
            $package_query->where('title','like', '%'.$name.'%');
        }
        if($destination_id){
            $package_query->where(function($q1) use($destination_id){
                $q1->where('destination_id',$destination_id);
                $q1->orWhere('sub_destination_id',$destination_id);
            });
        }

        $packages = $package_query->paginate($limit);
        $data['packages'] = $packages;
        $data['page_heading'] = 'Packages';
        $data['page_type'] = 'packages';
            //prd($data['packages']);
        $data['meta_title'] = 'Packages - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Packages - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        return Inertia::render('user/packages', $data);
        // return view('user.packages', $data);

    }else{
        return abort(404);
    }

}

public function activities(Request $request){

    $data = [];
    $limit = $this->limit;
    $userId = "";
    $data['meta_title'] = 'Activities - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Activities - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

    if(!empty(Auth::check())){

        $userId = Auth::user()->id;
        $package_query = Package::where('status',1)->where('is_activity',1)->orderBy('created_at','desc');
        $name = $request->name ?? '';
        if($name){
            $package_query->where('title','like', '%'.$name.'%');
        }
        $destination_id = $request->destination ?? '';
        if($destination_id){
            $package_query->where(function($q1) use($destination_id){
                $q1->where('destination_id',$destination_id);
                $q1->orWhere('sub_destination_id',$destination_id);
            });
        }
        $packages = $package_query->paginate($limit);
        $data['page_heading'] = 'Activities';
        $data['packages'] = $packages;
        $data['page_type'] = 'activities';
            //prd($data['packages']);

        return Inertia::render('user/packages', $data);
        // return view('user.packages', $data);

    }else{
        return abort(404);
    }

}

public function hotels(Request $request){

    $data = [];
    $limit = $this->limit;
    $userId = "";

    if(!empty(Auth::check())){
        $userId = Auth::user()->id;
        $hotel_query = Accommodation::with('accommodationDestination')->where('status',1)->orderBy('created_at','desc');

        $name = $request->name ?? '';
        $destination_id = $request->destination_id ?? '';

        if($name){
            $hotel_query->where('name','like', '%'.$name.'%');
        }
        if (!empty($destination_id)) {
            $hotel_query->where('destination_id',$destination_id);
        }
        $hotels = $hotel_query->paginate($limit);
        $data['userDetails'] = Auth::user();
        $data['hotels'] = $hotels;
        $data['meta_title'] = 'Hotels - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Hotels - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        return Inertia::render('user/hotels', $data);
        // return view('user.hotels', $data);

    }else{
        return abort(404);
    }

}

public function cab(Request $request){

    $data = [];
    $limit = $this->limit;
    $userId = "";

    if(!empty(Auth::check())){

            ///===========
        $name = $request->name ?? '' ;
        $pageQuery = CabRoute::with('CabRouteGroup','originCity','destinationCity')->orderBy('id','desc');
        if (!empty($name)) {
            $pageQuery->where(function($q1) use($name){
                $q1->where("name", "like", "%" . $name . "%");
                $q1->orWhere("places", "like", "%" . $name . "%");
            });
        }

        if(isset($request->cab_route_group_id)) {
            $pageQuery->where('cab_route_group_id',$request->cab_route_group_id);
        }
        if(isset($request->route_type)) {
            $pageQuery->where('route_type',$request->route_type);
        }
        if(isset($request->origin)) {
            $pageQuery->where('origin',$request->origin);
        }
        if(isset($request->destination)) {
            $pageQuery->where('destination',$request->destination);
        }
        $pageQuery->where("status", 1);
        $pages = $pageQuery->paginate($limit);



        $hotel_query = Accommodation::where('status',1)->orderBy('created_at','desc');
        $discount_result = DiscountModuleToGroup::where('module_name','cab')->where('is_default',1)->first();
        $default_group = isset($discount_result->discount_category) ? $discount_result->discount_category->name :'' ;
        $data['default_group'] = $default_group.'(Default)';

        $name = $request->name ?? '';
        if($name){
            $hotel_query->where('name','like', '%'.$name.'%');
        }
        $hotels = $hotel_query->paginate($limit);
        $data['userDetails'] = Auth::user();
        $data['res'] = $pages;
        $data['cab_route_groups'] = CabRouteGroup::where('status',1)->get();
        $data['cab_cities'] = CabCities::where('status',1)->get();

        $data['meta_title'] = 'Cabs - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Cabs - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        return Inertia::render('user/cab', $data);
        // return view('user.cab', $data);
    }else{
        return abort(404);
    }
}

public function rental(Request $request){

    $data = [];
    $userId = "";
    $limit = $this->limit;
    if(!empty(Auth::check())){

        $name = isset($request->name) ? $request->name :'';
        $bike = isset($request->bike) ? $request->bike :'';

        $bikeCityQuery = BikecityPrice::with('city','bikeData')->where('price','>',0)->orderBy("id", "asc");

        $bikeCityQuery->whereHas('city', function ($d_query) use ($name){
            if($name) {
                $d_query->where('name', 'like', '%'.$name.'%');
            }
        });
        $bikeCityQuery->whereHas('bikeData', function ($d_query) use ($bike) {
            if($bike) {
                $d_query->where('name', 'like', '%'.$bike.'%');
            }
        });

        if($request->location) {
            if(is_array($request->location)) {
                $location_arr = $request->location;
            } else {
                $location_arr = explode(',', $request->location);
            }
            if(!empty($location_arr)) {
                $bikeCityQuery->whereHas('bikeCityLocations', function($q1) use($location_arr) {
                    $q1->whereIn('id',$location_arr);
                });
            }
        }           
        $pages = $bikeCityQuery->paginate($limit);

        $discount_result = DiscountModuleToGroup::where('module_name','rental')->where('is_default',1)->first();
        $default_group = isset($discount_result->discount_category) ? $discount_result->discount_category->name :'' ;

        $data['default_group'] = $default_group.'(Default)';
        $data['userDetails'] = Auth::user();
        $data['res'] = $pages;
        $data["bikes"] = BikeMaster::where('status',1)->get();
        $data["bikeCityLocation"] = BikeCityLocation::where('status',1)->get();
            // prd($data["bikeCityLocation"]->toArray());

        $data['meta_title'] = 'Rental - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Rental - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        return Inertia::render('user/rental', $data);
        // return view('user.rental', $data);
    }else{
        return abort(404);
    }
}

    // agent_price
public function cab_price(Request $request) {
    $data = [];

    $limit = $this->limit;
    $id = isset($request->id) ? $request->id : "";
    $day_title = (isset($request->day_title))?$request->day_title:'';
    $status = (isset($request->status))?$request->status:'';
    if(!empty($id)) {

        $userId = Auth::user()->id;
        if(!empty($userId)){
            $userDetails = User::where('id',$userId)->first();
        }else{
            return abort(404);
        }
        $data['userDetails'] = $userDetails;

        $CabRoute = CabRoute::find($id);
        if($CabRoute) {
            $module_name = 'cab';
            $module_record_id = $CabRoute->id;
            $cab_route_group_ids = $CabRoute->CabRouteToGroup->pluck('id')->toArray()??[];
            $discount_category_id = isset($CabRoute->discount_category_id)?$CabRoute->discount_category_id : null;
            $discount_category_data = [];
            if($discount_category_id === -1) {
                $discount_category_name = 'Custom Discount';
                $moduleRecordDiscounts = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->orderBy('module_name', 'asc')->get();
                    // prd($moduleRecordDiscounts->toArray());
                if($moduleRecordDiscounts) {
                    $discount_module_groups = [];
                    foreach($moduleRecordDiscounts as $moduleRecordDiscount) {
                        $discount_module_groups[] = (object)[
                            'module_discount_type_id' => '-1',
                            'agent_group_id' => $moduleRecordDiscount->agent_group_id,
                            'discount_type' => $moduleRecordDiscount->discount_type,
                            'discount_value' => $moduleRecordDiscount->discount_value,
                        ];
                    }
                    $data['discount_module_groups'] = $discount_module_groups;
                } else {
                    $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                    $default_discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                    if($default_discount_category_id) {
                        $discount_category_data = ModuleDiscountCategory::where('id',$default_discount_category_id)->orderBy('module_name', 'asc')->first();
                    }
                        // prd($discount_category_data);
                }
            } else if($discount_category_id === 0) {
                $discount_category_name = 'Discount Not Applicable';
            } elseif($discount_category_id === Null) {
                $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                $discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                $discount_category_name = $discount_category_data->name ?? '';
            } else {
                $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                $discount_category_name = $discount_category_data->name ?? '';
            }
            $data["heading"] = 'Agent Price ('.$CabRoute->name.')';
            $data["cab_route_id"] = $id;

            $groups_query = CabRouteGroup::where('status',1)->orderBy('id','desc');
            if(!empty($cab_route_group_ids)) {
                $groups_query->whereIn('id',$cab_route_group_ids)->get();
            }
            $group_data = $groups_query->get();
            $data['group_datas'] = $group_data;

            $CabRoutePrice = CabRoutePrice::whereIn('cab_route_group_id',$cab_route_group_ids)->get();
            $data['CabRoutePrices'] = $CabRoutePrice;
            $data["discount_category_id"] = $discount_category_id;
            $data["discount_category_data"] = $discount_category_data;
            $data["module_name"] = $module_name;
            $data["module_record_id"] = $module_record_id;

            return Inertia::render('user/cab_price_view', $data);
            // return view('user.cab_price_view', $data);
        }
    }
    abort(404);
}

    //rental price
public function rental_price(Request $request) {

    $data = [];
    $limit = $this->limit;
    $id = isset($request->id) ? $request->id : "";
    $day_title = (isset($request->day_title))?$request->day_title:'';
    $status = (isset($request->status))?$request->status:'';
    if(!empty($id)) {
        $userId = Auth::user()->id;
        if(!empty($userId)){
            $userDetails = User::where('id',$userId)->first();
        }else{
            return abort(404);
        }
        $data['userDetails'] = $userDetails;

        $bikeCityPrice = BikecityPrice::find($id);
        $bikeCities = $bikeCityPrice->city??'';
        if($bikeCities) {
            $module_name = 'rental';
            $module_record_id = $bikeCities->id;
            $discount_category_id = isset($bikeCities->discount_category_id)?$bikeCities->discount_category_id : null;
                // prd($discount_category_id);
            $discount_category_data = [];

            if($discount_category_id === '-1') {
                $discount_category_name = 'Custom Discount';
                $moduleRecordDiscounts = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->orderBy('module_name', 'asc')->get();
                    // prd($moduleRecordDiscounts->toArray());
                if($moduleRecordDiscounts) {
                    $discount_module_groups = [];
                    foreach($moduleRecordDiscounts as $moduleRecordDiscount) {
                        $discount_module_groups[] = (object)[
                            'module_discount_type_id' => '-1',
                            'agent_group_id' => $moduleRecordDiscount->agent_group_id,
                            'discount_type' => $moduleRecordDiscount->discount_type,
                            'discount_value' => $moduleRecordDiscount->discount_value,
                        ];
                    }
                    $data['discount_module_groups'] = $discount_module_groups;
                } else {
                    $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                    $default_discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                    if($default_discount_category_id) {
                        $discount_category_data = ModuleDiscountCategory::where('id',$default_discount_category_id)->orderBy('module_name', 'asc')->first();
                    }
                        // prd($discount_category_data);
                }
            } else if($discount_category_id === 0) {
                $discount_category_name = 'Discount Not Applicable';
            } elseif($discount_category_id === Null) {
                $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                $discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                $discount_category_name = $discount_category_data->name ?? '';
            } else {
                $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                $discount_category_name = $discount_category_data->name ?? '';
            }
            $data["heading"] = 'Agent Price ('.$bikeCities->name.')';

            $data["city_id"] = $bikeCityPrice->city_id;
            $data["bike_id"] = $bikeCityPrice->bike_id;
            $data["discount_category_id"] = $discount_category_id;

            $data["discount_category_data"] = $discount_category_data;
            $data["module_name"] = $module_name;
            $data["module_record_id"] = $module_record_id;

            return Inertia::render('user/rental_price_view', $data);
            // return view('user.rental_price_view', $data);
        }
    }
    abort(404);
}

public function hotel_price(Request $request) {
    $data = [];

    $limit = $this->limit;
    $id = isset($request->id) ? $request->id : "";
    $day_title = (isset($request->day_title))?$request->day_title:'';
    $status = (isset($request->status))?$request->status:'';
    if(!empty($id)) {

        $userId = Auth::user()->id;
        if(!empty($userId)){
            $userDetails = User::where('id',$userId)->first();
        }else{
            return abort(404);
        }
        $data['userDetails'] = $userDetails;

        $Accommodation = Accommodation::find($id);
        if($Accommodation) {
            $module_name = 'hotel_listing';
            $module_record_id = $Accommodation->id;
            // $cab_route_group_id = isset($CabRoute->cab_route_group_id)?$CabRoute->cab_route_group_id : null;
            $discount_category_id = isset($Accommodation->discount_category_id)?$Accommodation->discount_category_id : null;
            $discount_category_data = [];

            if($discount_category_id === -1) {
                $discount_category_name = 'Custom Discount';
                $moduleRecordDiscounts = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->orderBy('module_name', 'asc')->get();
                    // prd($moduleRecordDiscounts->toArray());
                if($moduleRecordDiscounts) {
                    $discount_module_groups = [];
                    foreach($moduleRecordDiscounts as $moduleRecordDiscount) {
                        $discount_module_groups[] = (object)[
                            'module_discount_type_id' => '-1',
                            'agent_group_id' => $moduleRecordDiscount->agent_group_id,
                            'discount_type' => $moduleRecordDiscount->discount_type,
                            'discount_value' => $moduleRecordDiscount->discount_value,
                        ];
                    }
                    $data['discount_module_groups'] = $discount_module_groups;
                } else {
                    $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                    $default_discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                    if($default_discount_category_id) {
                        $discount_category_data = ModuleDiscountCategory::where('id',$default_discount_category_id)->orderBy('module_name', 'asc')->first();
                    }
                    // prd($discount_category_data);
                }
            } else if($discount_category_id === 0) {
                $discount_category_name = 'Discount Not Applicable';
            } elseif($discount_category_id === Null) {
                $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                $discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                $discount_category_name = $discount_category_data->name ?? '';
            } else {
                $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                $discount_category_name = $discount_category_data->name ?? '';

            }
            $data["heading"] = 'Agent Price ('.$Accommodation->name.')';
            $data["cab_route_id"] = $id;

            $data['accommodation'] = $Accommodation;
            $data['rooms'] = AccommodationRoom::with('roomAccommodation')->where("accommodation_id",$Accommodation->id)->orderBy("sort_order","asc")->get();
            $data["discount_category_id"] = $discount_category_id;
            $data["discount_category_data"] = $discount_category_data;
            $data["module_name"] = $module_name;
            $data["module_record_id"] = $module_record_id;

            return Inertia::render('user/hotel_price_view', $data);
            // return view('user.hotel_price_view', $data);
        }
    }
    abort(404);
}

public function orderDetail(Request $request) {
    $id = isset($request->id) ? $request->id : 0;
    $order = "";
    $title = "Order Details";
    $userId = Auth::user()->id??'';
    if (is_numeric($id) && $id > 0 && !empty($userId)) {
        $order = Order::where("id", $id)->first();
        if($order) {
            $title = "Order Details";
            $data = [];
            $data["page_heading"] = $title;
            $data["order"] = $order;
            $data["id"] = $id;

            $order_type = $order->order_type??'1';
            if($order_type == 3) {
                $params = [];
                $params['order'] = $order;
                if($order->booking_details) {
                    $params['booking_details'] = json_decode($order->booking_details, true);
                }
                $data['itineraries'] = view("emails._flight_booking_email", $params)->render();

            } elseif($order_type == 4) {
                $params = [];
                $params['order'] = $order;
                if($order->booking_details) {
                    $params['booking_details'] = json_decode($order->booking_details, true);
                }
                $data['cab_itineraries'] = view("emails._cab_booking_email", $params)->render();
            }

            $show_cancel = 0;
            $before_date = '';
            if($order->order_type == 1){
                $before_days = CustomHelper::WebsiteSettings('PACKAGE_BOOKING_CANCEL_HOURS')??0;
                $date = date('Y-m-d H:i:s', strtotime($order->trip_date));
                $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour")); 
            } else if($order->order_type == 6) {
                $before_days = CustomHelper::WebsiteSettings('ACTIVITY_BOOKING_CANCEL_HOURS')??0;
                $date = date('Y-m-d H:i:s', strtotime($order->trip_date));
                $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour"));
            } else if($order->order_type == 5) {
                $before_days = CustomHelper::WebsiteSettings('HOTEL_BOOKING_CANCEL_HOURS')??0;
                $date = date('Y-m-d H:i:s', strtotime($order->trip_date));
                $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour"));
            } else if($order->order_type == 4) {
                $before_days = CustomHelper::WebsiteSettings('CAB_BOOKING_CANCEL_HOURS')??0;
                $date = date('Y-m-d H:i:s', strtotime($order->trip_date));
                $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour"));
            } else if($order->order_type == 8) {

                $before_days = CustomHelper::WebsiteSettings('RENTAL_BOOKING_CANCEL_HOURS')??0;

                $booking_details = $order->booking_details ?? '';
                $booking_details_arr = json_decode($booking_details);
                $date = CustomHelper::DateFormat($booking_details_arr->pickupDate,'Y-m-d')??'';

                $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour"));
            }
            $current_date = date('Y-m-d H:i:s');
            $trip_date_show = strtotime($before_date);
            $current_date_show = strtotime($current_date);
            if($current_date_show <= $trip_date_show) {
                $show_cancel = 1;
            }
            // prd($show_cancel);
            $data['show_cancel'] = $show_cancel;

        } else {
            abort(404);
        }
    } else {
        abort(404);
    }
    return Inertia::render('user/order_details', $data);
    // return view('user.order_details', $data);
}

public function refund(Request $request) {
    $response = [];
    $order_id = isset($request->order_id) ? $request->order_id : 0;
    $order = "";
    $user_id = auth()->user() ? auth()->user()->id :0;
    if (is_numeric($order_id) && $order_id > 0 && !empty($user_id)) {
        $order = Order::find($order_id);
        if($order){
            $user_name = auth()->user() ? auth()->user()->name:'system'; 
            $order_status_history  = array(
                'order_id' => $order->id,
                'orders_status' => "Cancellation Processing",
                'comments' => $request->reason,
                'created_type' => 'customer',
                'created_by' => $user_id,
                'created_by_name' => $user_name,
            );
            $isSaved = OrderStatusHistory::create($order_status_history);

            $order->cancel_request = 1;
            //$order->comment = $request->reason ?? '';
            //$order->orders_status = $rejected_id??'';
            $isSaved = $order->save();

            $order_type_arr = config('custom.order_type');
            $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk("public");
            $path = "settings/";
            $logoSrc =asset(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                }
            }
            // AGENT LOGO
            $userAgentInfo = ''; $agentLogo = '';
            $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
            if($agent_id && $agent_id > 0){
                $userAgentInfo = $order->agentInfo ?? '';
                if($userAgentInfo && $userAgentInfo->count() > 0){
                    $path = 'agent_logo/';
                    $agentLogo = $order->agentInfo->agent_logo ?? '';

                    $userData = $userAgentInfo->User ?? '';
                    $agent_phone = '';
                    $agent_email = '';
                    if($userData->phone) {
                        $country_code = $userData->country_code ?? '91';
                        $agent_phone = '+'.$country_code.'-'.$userData->phone;
                    }
                    $agent_email = !empty($userData->email)?$userData->email:'';
                }
                if(!empty($agentLogo)){
                    if($storage->exists($path.$agentLogo)){
                        $logoSrc = asset('/storage/'.$path.$agentLogo);
                    }
                }
                $total_amount = $order->sub_total_amount??0;
            }

            $common_logo = '';
            if(!empty($agent_id)){
                $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                $b2b_logo_params = array(
                    '{agent_logo}' => $logoSrc
                );
                $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
            }else{
                $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                $b2c_logo_params = array(
                   '{company_logo}' => $logoSrc
               );
                $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
            }
            $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
            $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
            $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
            $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

            $sales_phone = CustomHelper::getPhoneHref($company_phone);
            $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
            $sales_email = CustomHelper::getEmailHref($company_email);


            $contact_details = '';
            if(!empty($agent_id)){
                $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                $b2b_email_params = array(
                    '{agent_phone}' => $agent_phone,
                    '{agent_email}' => $agent_email
                );
                $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
            }else{
                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $system_title
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            }
            $email_params = array(
                '{header}' => $common_logo,
                '{name}' => $order->name,
                '{order_no}' => $order->order_no,
                '{order_type}' => $order_type,
                '{reason_for_cancellation}' => $request->reason,
                '{contact_details}' => $contact_details??'',
                '{date}' => date("l jS \of F Y h:i A"),
            );
            $email = $order->email;
            $order_phone = '';
            if($order->phone) {
                $country_code = $order->country_code ?? '91';
                $order_phone = '+'.$country_code.'-'.$order->phone;
            }
            $REPLYTO = $email;
            $template_slug = 'booking-cancellation-request';
            $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

            $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
            $email_params = isset($email_params) ? $email_params : [];
            $email_content = strtr($email_content, $email_params);

            $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';
            $subject_params = isset($subject_params) ? $subject_params : [];
            $email_subject = strtr($email_subject, $subject_params);
            $email_type = isset($email_content_data->email_type) ? $email_content_data->email_type : '';
            $email_bcc_admin = isset($email_content_data->bcc_admin) ? $email_content_data->bcc_admin : 0;

            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
            $to_email = $order->email;
            $cc_email = '';
            $bcc_email = '';
            if($email_type == 'admin'){
                $to_email = $ADMIN_EMAIL;
            }
            if($email_bcc_admin == 1){
                $bcc_email = $ADMIN_EMAIL;
            }

            if(isset($email_content_data) && !empty($email_content_data)) {
                $params = [];
                $params['to'] = $to_email;
                $params['reply_to'] = $REPLYTO;
                $params['cc'] = $cc_email;
                $params['bcc'] = $bcc_email;
                $params['subject'] = $email_subject;
                $params['email_content'] = $email_content;
                $params['module_name'] = 'Booking Cancellation Request';
                $params['record_id'] = $order_id ?? 0;

                $is_mail1 = CustomHelper::sendCommonMail($params);
            }

            if($isSaved){
                $response['success'] = true;
                $response['msg'] = ' Your cancellation request has been submitted successfully';
                //$msg = '<div class="alert alert-success alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Your cancellation request has been submitted successfully </div';
            } else {
                $response['success'] = false;
                $response['msg'] = ' Your cancellation request has been not submitted successfully';
                //$msg = '<div class="alert alert-danger alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Something went wrong, please try again... </div';
            }
            //$response['msg'] = $msg;
        } else {
            $response['success'] = false;
            $response['msg'] = 'Invalid request';
        }
    } else {
        $response['success'] = false;
        $response['msg'] = 'Invalid request';
    }

    return response()->json($response);
}
public function cab_voucher_view(Request $request) {
    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    //$order = Order::find($request->order_id);
    $userId = Auth::user()->id??'';
    if (is_numeric($order_id) && $order_id > 0 && !empty($userId)) {
        $order_query = Order::where("id", $order_id);
        $order_query->where(function($q1) use($userId) {
            $q1->where('user_id',$userId);
        });
        $order = $order_query->first();
        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){
                $cab_data = $OrderServiceVoucher->cab_data ? json_decode($OrderServiceVoucher->cab_data): [];
            // prd($cab_data);
                $title = $OrderServiceVoucher->title ?? '';
                $trip_type = $OrderServiceVoucher->trip_type ?? '';
                $pickup_address = $cab_data->pickup_address ?? '';
                $pickup_date = $cab_data->pickup_date ?? '';
                $drop_address = $cab_data->drop_address ?? '';
                $trip_date = $cab_data->trip_date ?? '';
                $car_type = $cab_data->car_type ?? '';
                $cab_charge = $cab_data->cab_charge ?? '';
                $paid_amount = $cab_data->paid_amount ?? '';
                $be_paid_to_driver = $cab_data->be_paid_to_driver ?? '';
                $itinerary = $cab_data->itinerary ?? '';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk("public");
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                $data = array(
                    'logo' => $logoSrc,
                    'order_no' =>  $order->order_no??'',
                    'itinerary' =>  $itinerary??'',
                    'title' =>  $OrderServiceVoucher->title??'',
                    'trip_type' =>  $OrderServiceVoucher->trip_type??'',
                    'gst_no' =>  $OrderServiceVoucher->gst_no??'',
                    'pickup_address' =>  $pickup_address??'',
                    'pickup_date' =>  $pickup_date??'',
                    'pickup_details' =>  $pickup_details??'',
                    'drop_address' =>  $drop_address??'',
                    'car_type' =>  $car_type??'',
                    'name' =>  $OrderServiceVoucher->name??'',
                    'phone' =>  $OrderServiceVoucher->phone??'',
                    'email' =>  $OrderServiceVoucher->email??'',
                    'cab_charge' =>  $cab_charge??'',
                    'paid_amount' =>  $paid_amount??'',
                    'be_paid_to_driver' =>  $be_paid_to_driver??'',
                    '{distance}' =>  '--',
                    '{extra_fare}' =>  '--',
                );

                $data['order_id'] = $order_id;
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
    $data['meta_title'] = 'Cab Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Cab Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

    return Inertia::render('user/cab_voucher_view', $data);
    // return view('user.cab_voucher_view', $data);
}
public function rental_voucher_view(Request $request) {
    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    $userId = Auth::user()->id??'';
    if (is_numeric($order_id) && $order_id > 0 && !empty($userId)) {
        $order_query = Order::where("id", $order_id);
        $order_query->where(function($q1) use($userId) {
            $q1->where('user_id',$userId);
        });
        $order = $order_query->first();
        if($order){
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){

                $rental_data = $OrderServiceVoucher->rental_data ? json_decode($OrderServiceVoucher->rental_data): [];
            // prd($rental_data);

                $bike_name = $rental_data->bike_name ?? '';
                $city_name = $rental_data->city_name ?? '';
                $location_name = $rental_data->location_name ?? '';
                $pickup_date = $rental_data->pickup_date ?? '';
                $drop_date = $rental_data->drop_date ?? '';
                $paid_amount = $rental_data->paid_amount ?? '';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                $data = array(
                    'logo' => $logoSrc,
                    'order_no' =>  $order->order_no??'',
                    'bike_name' =>  $bike_name??'',
                    'city_name' =>  $city_name??'',
                    'location_name' =>  $location_name??'',
                    'pickup_date' =>  $pickup_date??'',
                    'drop_date' =>  $drop_date??'',
                    'remarks' =>  $OrderServiceVoucher->remarks??'',
                    'gst_no' =>  $OrderServiceVoucher->gst_no??'',
                    'name' =>  $OrderServiceVoucher->name??'',
                    'phone' =>  $OrderServiceVoucher->phone??'',
                    'email' =>  $OrderServiceVoucher->email??'',
                    'paid_amount' =>  $paid_amount??'',
                );

                $data['order_id'] = $order_id;
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
    $data['meta_title'] = 'Rental Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Rental Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

    return Inertia::render('user/rental_voucher_view', $data);
    // return view('user.rental_voucher_view', $data);
}
public function rental_voucher_pdf(Request $request) {
    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    $userId = Auth::user()->id??'';
    if (is_numeric($order_id) && $order_id > 0 && !empty($userId)) {
        $order_query = Order::where("id", $order_id);
        $order_query->where(function($q1) use($userId) {
            $q1->where('user_id',$userId);

        });
        $order = $order_query->first();
        $agent_id = $order->agent_id??'';
        $tour_operator = CustomHelper::WebsiteSettings('SALE_PHONE');
        $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');
        $agent_phone = '';
        $agent_email = '';
        if(!empty($agent_id)){
            $agent_data = User::find($agent_id);
            if($agent_data->phone) {
                $country_code = $agent_data->country_code ?? '91';
                $agent_phone = '+'.$country_code.'-'.$agent_data->phone;
            }
            $agent_email = !empty($agent_data->email)?$agent_data->email:'';
        }
        if($order){
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){

                $rental_data = $OrderServiceVoucher->rental_data ? json_decode($OrderServiceVoucher->rental_data): [];

                $bike_name = $rental_data->bike_name??'';
                $city_name = $rental_data->city_name??'';
                $location_name = $rental_data->location_name??'';
                $pickup_date = !empty($rental_data->pickup_date)?date('d/m/Y h:i A',strtotime($rental_data->pickup_date)):'';
                $drop_date = !empty($rental_data->drop_date)?date('d/m/Y h:i A',strtotime($rental_data->drop_date)):'';
                $paid_amount = $rental_data->paid_amount ?? '';

                $user_phone = '';
                if($order_id) {
                    $country_code = $order->country_code ?? '91';
                    $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
                }

                $title = $OrderServiceVoucher->title ?? '';
                $trip_type = $OrderServiceVoucher->trip_type ?? '';
                $created_at = !empty($order->created_at)?date('D, M dS Y',strtotime($order->created_at)):'';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =public_path(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                $total_amount = $order->total_amount??0;

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = public_path('/storage/'.$path.$agentLogo);
                        }
                    }
                    $total_amount = $order->sub_total_amount??0;
                }

                $common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{agent_logo}' => $logoSrc,
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                }else{
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                        '{company_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }

                $email_params = array(
                    '{header}' => $common_logo,
                    '{order_id}' =>  $order->order_no??'',
                    '{booking_date}' =>  $created_at??'',
                    '{title}' =>  $title??'',
                    '{trip_type}' =>  $trip_type??'',
                    '{gst_no}' =>  $OrderServiceVoucher->gst_no??'',
                    '{bike_name}' =>  $bike_name??'',
                    '{city_name}' =>  $city_name??'',
                    '{location_name}' =>  $location_name??'',
                    '{pickup_date}' =>  $pickup_date??'',
                    '{drop_date}' =>  $drop_date??'',
                    '{name}' =>  $OrderServiceVoucher->name??'',
                    '{phone}' =>  $user_phone??'',
                    '{email}' =>  $OrderServiceVoucher->email??'',
                    '{paid_amount}' =>  $paid_amount??'',
                    '{amount}' => $total_amount??0,
                    '{tour_operator}' =>  $tour_operator??'',
                    '{agency}' =>  $agent_phone?$agent_phone:$tour_operator,
                    '{company_email}' =>  $agent_email?$agent_email:$company_email,
                    '{gst_amount}' =>  '--',
                );


                $price_details = '';
                if($order->hide_price_details != 1){
                    if(empty($agent_id)) {
                        $price_details .= '<tr>
                        <td>&nbsp;</td>
                        <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                        <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;line-height: normal;">Basic Fare</p>
                        </td>
                        <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                        <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                        </td>
                        </tr>';
                    }

                    if(empty($agent_id)) {
                        $price_details .= '<tr>
                        <td>&nbsp;</td>
                        <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                        <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Other Taxes</p>
                        </td>
                        <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                        <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. --</p>
                        </td>
                        </tr>';
                    }
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Total Amount</p>
                    </td>
                    <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
                    </td>
                    </tr>';
                }
                $email_params['{price_details}'] = $price_details;

                // $email_params = isset($email_params) ? $email_params : [];
                //$email_content= view('user.rental_voucher_pdf', $email_params)->render();
                $email_content = view(config('custom.theme').'.common.vouchers.rental_voucher_pdf', $email_params)->render();
                $email_content=
                $html = strtr($email_content, $email_params);
                $pdf = PDF::loadHTML($html);
                $pdf->setPaper('A4', 'portrait');
                $pdf_name = $order->order_no.'.pdf';
                return $pdf->download($pdf_name);

            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
}

public function cab_voucher_pdf(Request $request) {

    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    //$order = Order::find($request->order_id);
    $userId = Auth::user()->id??'';
    if (is_numeric($order_id) && $order_id > 0 && !empty($userId)) {
        $order_query = Order::where("id", $order_id);
        $order_query->where(function($q1) use($userId) {
            $q1->where('user_id',$userId);

        });
        $order = $order_query->first();

        $agent_id = $order->agent_id??'';
        $tour_operator = CustomHelper::WebsiteSettings('SALE_PHONE');
        $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');
        $agent_phone = '';
        $agent_email = '';
        if(!empty($agent_id)){
            $agent_data = User::find($agent_id);
            if($agent_data->phone) {
                $country_code = $agent_data->country_code ?? '91';
                $agent_phone = '+'.$country_code.'-'.$agent_data->phone;
            }
            $agent_email = !empty($agent_data->email)?$agent_data->email:'';
        }
        // Driver Deatils
        $booking_details = $order->booking_details ? json_decode($order->booking_details): '';
        $driver_details  = $booking_details->driver_details ?? [];

        $vehicle_no = $driver_details->vehicle_no ?? '';
        $vehicle_type = $driver_details->vehicle_type ?? '';
        $driver_name = $driver_details->driver_name ?? '';
        $driver_phone = $driver_details->mobile_no ?? '';

        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){

                $cab_data = $OrderServiceVoucher->cab_data ? json_decode($OrderServiceVoucher->cab_data): [];
                $user_phone = '';
                if($order_id) {
                    $country_code = $order->country_code ?? '91';
                    $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
                }
                // prd($cab_data);
                $title = $OrderServiceVoucher->title ?? '';
                $trip_type = $OrderServiceVoucher->trip_type ?? '';
                $created_at = !empty($order->created_at)?date('D, M dS Y h:i A',strtotime($order->created_at)):'';
                $pickup_address = $cab_data->pickup_address ?? '';
                $pickup_date = $cab_data->pickup_date ?? '';
                $drop_address = $cab_data->drop_address ?? '';
                $origin = $cab_data->origin ?? '';
                $destination = $cab_data->destination ?? '';
                $trip_date = $cab_data->trip_date ?? '';
                $car_type = $cab_data->car_type ?? '';
                $exclusion = $cab_data->exclusion ?? '';
                $cab_charge = $cab_data->cab_charge ?? '';
                $total_amount = $order->total_amount ?? 0;
                $paid_amount = $cab_data->paid_amount ?? '';
                $be_paid_to_driver = $cab_data->be_paid_to_driver ?? '';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =public_path(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = public_path('/storage/'.$path.$agentLogo);
                        }
                    }
                    $total_amount = $order->sub_total_amount ?? 0;
                }

                $common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{agent_logo}' => $logoSrc,
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                }else{
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                        '{company_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }

                if($driver_details){

                    $driver_html = '<p style="color: #333;font-size: 14px;font-weight: 400;font-family: "Roboto", sans-serif, Arial;margin: 4px 0px 0;">'.$vehicle_no.'<br>Driver:'.$driver_name.'<br>Mobile: '.$driver_phone.'</p>';
                }else{
                    $driver_html = '<p style="color: #333;font-size: 14px;font-weight: 400;font-family: "Roboto", sans-serif, Arial;margin: 4px 0px;"> Driver details will be shared up to 30 mins prior to departure</p>';
                }

                $email_params = array(
                    '{header}' => $common_logo,
                    '{order_id}' =>  $order->order_no??'',
                    '{booking_date}' =>  $created_at??'',
                    '{title}' =>  $OrderServiceVoucher->title??'',
                    '{trip_type}' =>  $OrderServiceVoucher->trip_type??'',
                    '{gst_no}' =>  $OrderServiceVoucher->gst_no??'',

                    '{pickup_address}' =>  $pickup_address??'',
                    '{pickup_date}' =>  $pickup_date??'',
                    '{drop_address}' =>  $drop_address??'',
                    '{car_type}' =>  $car_type??'',
                    '{vehicle_type}' =>  $vehicle_type??'',
                    '{exclusion}' =>  $exclusion??'',

                    '{name}' =>  $OrderServiceVoucher->name??'',
                    '{phone}' =>  $user_phone??'',
                    '{email}' =>  $OrderServiceVoucher->email??'',
                    '{amount}' =>  $total_amount??0,
                    '{cab_charge}' =>  $cab_charge??'',
                    '{paid_amount}' =>  $paid_amount??'',
                    '{be_paid_to_driver}' =>  $be_paid_to_driver??'',
                    '{tour_operator}' =>  $tour_operator??'',
                    '{agency}' =>  $agent_phone?$agent_phone:$tour_operator,
                    '{company_email}' =>  $agent_email?$agent_email:$company_email,
                    '{departing}' =>  $origin??'',
                    '{arrival}' =>  $destination??'',
                    '{driver_name}' =>  $driver_name??'',
                    '{driver_details}' =>  $driver_html??'',
                    '{driver_phone}' =>  $driver_phone??'',
                    '{gst_amount}' =>  '--',
                    '{taxi_no}' =>  $vehicle_no??'',
                    '{distance}' =>  '--',
                    '{extra_fare}' =>  '--',

                );

                $price_details = '';
                if($order->hide_price_details != 1){
                    if(empty($agent_id)) {
                        $price_details .= '<tr>
                        <td>&nbsp;</td>
                        <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                        <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;line-height: normal;">Basic Fare</p>
                        </td>
                        <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                        <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                        </td>
                        </tr>';
                    }

                    if(empty($agent_id)) {
                        $price_details .= '<tr>
                        <td>&nbsp;</td>
                        <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                        <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Other Taxes</p>
                        </td>
                        <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                        <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. --</p>
                        </td>
                        </tr>';
                    }
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Total Amount</p>
                    </td>
                    <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
                    </td>
                    </tr>';
                }
                $email_params['{price_details}'] = $price_details;

                // $email_params = isset($email_params) ? $email_params : [];
                //$email_content = view('user.cab_voucher_pdf', $email_params)->render();
                $email_content = view(config('custom.theme').'.common.vouchers.cab_voucher_pdf', $email_params)->render();
                $html = strtr($email_content, $email_params);
                $pdf = PDF::loadHTML($html);
                $pdf->setPaper('A4', 'portrait');
                $pdf_name = $order->order_no.'.pdf';
                return $pdf->download($pdf_name);

            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
}
public function hotel_voucher_view(Request $request) {
    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    //$order = Order::find($request->order_id);
    $user_id = Auth::user()->id??'';
    if(!empty($order_id) && !empty($user_id)) {
        $query = Order::where("id", $order_id);
        $query->where(function($q1) use($user_id) {
            $q1->where('user_id',$user_id);
        });
        $order = $query->first();
        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){
                $hotel_data = $OrderServiceVoucher->hotel_data ? json_decode($OrderServiceVoucher->hotel_data): [];
                // prd($hotel_data);

                $title = $OrderServiceVoucher->title ?? '';
                $room_type = $hotel_data->room_name ?? '';
                $plan_name = $hotel_data->plan_name ?? '';
                $checkin = $hotel_data->checkin ?? '';
                $checkout = $hotel_data->checkout ?? '';
                $checkin_time = $hotel_data->checkin_time ?? '';
                $checkout_time = $hotel_data->checkout_time ?? '';
                $contact_phone = $hotel_data->contact_phone ?? '';
                $contact_email = $hotel_data->contact_email ?? '';
                $contact_person = $hotel_data->contact_person ?? '';
                $inventory = $hotel_data->inventory ?? '';
                $destination = $hotel_data->destination_name ?? '';
                $address = $hotel_data->address ?? '';
                $inclusions = $hotel_data->inclusions ?? '';
                $trip_date = !empty($hotel_data->trip_date)?date('D, M dS Y h:i A',strtotime($hotel_data->trip_date)):'';
                $created_at = !empty($hotel_data->created_at)?date('D, M dS Y h:i A',strtotime($hotel_data->created_at)):'';
                $adult = $hotel_data->adult ?? '';
                $child = $hotel_data->child ?? '';
                $hotel_charge = $hotel_data->hotel_charge ?? '';
                $paid_amount = $hotel_data->paid_amount ?? '';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk("public");
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                $data = array(
                    'logo' => $logoSrc,
                    'order_no' =>  $order->order_no??'',
                    'title' =>  $OrderServiceVoucher->title??'',
                    'remarks' =>  $OrderServiceVoucher->remarks??'',
                     //'trip_date' => $trip_date??'',
                    'created_at' => $created_at??'',
                    'room_name' => $room_type??'',
                    'plan_name' => $plan_name??'',
                    'checkin' => $checkin??'',
                    'checkout' => $checkout??'',
                    'checkin_time' => $checkin_time??'',
                    'checkout_time' => $checkout_time??'',
                    'contact_email' => $contact_email??'',
                    'contact_phone' => $contact_phone??'',
                    'contact_person' => $contact_person??'',
                    'inventory' => $inventory??'',
                    'destination' => $destination??'',
                    'address' => $address??'',
                    'inclusions' => $inclusions??'',
                    'adult' => $adult??'',
                    'child' => $child??'',
                    'hotel_charge' => $hotel_charge??'',
                    'name' =>  $OrderServiceVoucher->name??'',
                    'phone' =>  $OrderServiceVoucher->phone??'',
                    'email' =>  $OrderServiceVoucher->email??'',
                    'paid_amount' =>  $paid_amount??'',
                );

                $data['order_id'] = $order_id;
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }

    $data['meta_title'] = 'Hotel Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Hotel Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

    return Inertia::render('user/hotel_voucher_view', $data);
    // return view('user.hotel_voucher_view', $data);
}
public function hotel_voucher_pdf(Request $request) {

    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    //$order = Order::find($request->order_id);
    $user_id = Auth::user()->id??'';
    if(!empty($order_id) && !empty($user_id)) {
        $query = Order::where("id", $order_id);
        $query->where(function($q1) use($user_id) {
            $q1->where('user_id',$user_id);
        });
        $order = $query->first();
        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){

                $hotel_data = $OrderServiceVoucher->hotel_data ? json_decode($OrderServiceVoucher->hotel_data): [];
                // prd($hotel_data);

                $title = $OrderServiceVoucher->title ?? '';
                $room_type = $hotel_data->room_name ?? '';
                $plan_name = $hotel_data->plan_name ?? '';
                $contact_phone = $hotel_data->contact_phone ?? '';
                $contact_email = $hotel_data->contact_email ?? '';
                $contact_person = $hotel_data->contact_person ?? '';
                $checkin = $hotel_data->checkin ?? '';
                $checkout = $hotel_data->checkout ?? '';
                $checkin_time = $hotel_data->checkin_time ?? '';
                $checkout_time = $hotel_data->checkout_time ?? '';
                $inventory = $hotel_data->inventory ?? '';
                $destination = $hotel_data->destination_name ?? '';
                $address = $hotel_data->address ?? '';
                $inclusions = $hotel_data->inclusions ?? '';
                $trip_date = !empty($hotel_data->trip_date)?date('D, M dS Y h:i A',strtotime($hotel_data->trip_date)):'';
                $created_at = !empty($hotel_data->created_at)?date('D, M dS Y',strtotime($hotel_data->created_at)):'';
                $adult = $hotel_data->adult ?? '';
                $child = $hotel_data->child ?? '';
                $hotel_charge = $hotel_data->hotel_charge ?? '';
                $paid_amount = $hotel_data->paid_amount ?? '';
                $total_amount = $order->total_amount??0;

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =public_path(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';

                        $userData = $userAgentInfo->User ?? '';
                        $agent_phone = '';
                        $agent_email = '';
                        if($userData->phone) {
                            $country_code = $userData->country_code ?? '91';
                            $agent_phone = '+'.$country_code.'-'.$userData->phone;
                        }
                        $agent_email = !empty($userData->email)?$userData->email:'';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = public_path('/storage/'.$path.$agentLogo);
                        }
                    }
                    $total_amount = $order->sub_total_amount ?? 0;
                }
                $common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{agent_logo}' => $logoSrc,
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                }else{
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                        '{company_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }
                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);


                $contact_details = '';
                if(!empty($agent_id)){
                    $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                    $b2b_email_params = array(
                        '{agent_phone}' => $agent_phone,
                        '{agent_email}' => $agent_email
                    );
                    $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                   /* $contact_details .= 
                    '<tr>
                     <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                     <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Mobile:</b> <a href="tel:'.$agent_phone.'" style="color: #fff;text-decoration: none;">'.$agent_phone.'</a>; <b>Email:</b> <a href="mailto:'.$agent_email.'" style="color: #fff;text-decoration: none;">'.$agent_email.'</a></p>

                     <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>
                     </td>
                     </tr>';*/
                }else{

                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $system_title
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                /*$contact_details .= '<tr>
                <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
                <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

                <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
                </td>
                </tr>';*/
            }

            $email_params = array(
                '{header}' => $common_logo,
                '{booking_id}' =>  $order->order_no??'',
                '{hotel_name}' =>  $OrderServiceVoucher->title??'',
                '{customer_name}' =>  $OrderServiceVoucher->name??'',
                //'{phone}' =>  $OrderServiceVoucher->phone??'',
                '{phone}' =>  $contact_phone??'',
                //'{email}' =>  $OrderServiceVoucher->email??'',
                '{email}' =>  $contact_email??'',
                '{contact_person}' =>  $contact_person??'',
                '{room_type}' =>  $room_type??'',
                '{plan_name}' =>  $plan_name??'',
                '{checkin_date}' =>  $checkin??'',
                '{checkin_time}' =>  $checkin_time??'',
                '{checkout_date}' =>  $checkout??'',
                '{checkout_time}' =>  $checkout_time??'',
                '{inventory}' =>  $inventory??'',
                '{destination}' =>  $destination??'',
                '{address}' =>  $address??'',
                '{contact_details}' =>  $contact_details??'',
                '{inclusions}' =>  $inclusions??'',
                '{adult}' =>  $adult??'',
                '{child}' =>  $child??'',
                //'{booking_date}' =>  $trip_date??'',
                '{booking_date}' =>  $created_at??'',
                '{hotel_charge}' =>  $hotel_charge??'',
                '{amount}' =>  $total_amount??0,
                '{paid_amount}' =>  $paid_amount??'',

            );


            $price_details = '';
            if($order->hide_price_details != 1){
                $price_details .= '<tr>
                <td style="padding: 10px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;line-height: normal;"><strong>Total Charges</strong></p>
                </td>
                <td colspan="3" style="padding: 10px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
                </td>
                </tr>';
                if(empty($agent_id)){
                    $price_details .= '<tr>
                    <td style="padding: 2px 15px 2px 15px;border: 0;">
                    <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;"><strong>Amount Paid</strong></p>
                    </td>
                    <td colspan="3" style="padding: 2px 15px 2px 15px;border: 0;">
                    <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                    </td>
                    </tr>';
                }
            }
            $email_params['{price_details}'] = $price_details;

           // $email_params = isset($email_params) ? $email_params : [];
           //$email_content = view('user.hotel_voucher_pdf', $email_params)->render();
            $email_content = view(config('custom.theme').'.common.vouchers.hotel_voucher_pdf', $email_params)->render();
            $html = strtr($email_content, $email_params);

           // return view("admin.vouchers.cab_voucher_view", $data);
           // $html = preg_replace('/>\s+</', "><", $html);
            $pdf = PDF::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf_name = $order->order_no.'.pdf';
            return $pdf->download($pdf_name);

            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
}
public function package_voucher_view(Request $request) {
    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    //$order = Order::find($request->order_id);
    $user_id = Auth::user()->id??'';
    if(!empty($order_id) && !empty($user_id)) {
        $query = Order::where("id", $order_id);
        $query->where(function($q1) use($user_id) {
            $q1->where('user_id',$user_id);
        });
        $order = $query->first();
        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){
                $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];

                $title = $OrderServiceVoucher->title ?? '';
                $trip_date = !empty($package_data->trip_date)?date('d M,Y',strtotime($package_data->trip_date)):'';
                $duration = $package_data->duration ?? '';
                $destination = $package_data->destination ?? '';
                $package_charge = $package_data->package_charges ?? '';
                $paid_amount = $package_data->paid_amount ?? '';
                $due_amount = $package_data->due_amount ?? '';
                $address = $package_data->address ?? '';
                $contact_person = $package_data->contact_person ?? '';
                $contact_phone = $package_data->contact_phone ?? '';
                $contact_email = $package_data->contact_email ?? '';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk("public");
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                $data = array(
                    'logo' => $logoSrc,
                    'order_no' =>  $order->order_no??'',
                    'title' =>  $OrderServiceVoucher->title??'',
                    'remarks' =>  $OrderServiceVoucher->remarks??'',
                    'trip_date' => $trip_date??'',
                    'duration' => $duration??'',
                    'address' => $address??'',
                    'contact_person' => $contact_person??'',
                    'contact_phone' => $contact_phone??'',
                    'contact_email' => $contact_email??'',
                    'destination' => $destination??'',
                    'package_charge' => $package_charge??'',
                    'name' =>  $OrderServiceVoucher->name??'',
                    'phone' =>  $OrderServiceVoucher->phone??'',
                    'email' =>  $OrderServiceVoucher->email??'',
                    'paid_amount' =>  $paid_amount??'',
                    'due_amount' =>  $due_amount??'',
                );

                $data['order_id'] = $order_id;
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
    $data['meta_title'] = 'Package Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Package Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

    return Inertia::render('user/package_voucher_view', $data);
    // return view('user.package_voucher_view', $data);
}
public function package_voucher_pdf(Request $request) {

    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    //$order = Order::find($request->order_id);
    $user_id = Auth::user()->id??'';
    if(!empty($order_id) && !empty($user_id)) {
        $query = Order::where("id", $order_id);
        $query->where(function($q1) use($user_id) {
            $q1->where('user_id',$user_id);
        });
        $order = $query->first();
        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){

                $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
                $title = $OrderServiceVoucher->title ?? '';
                $package_name = $package_data->package_name ?? '';
                $package_charges = $package_data->package_charges ?? '';
                $total_amount = $order->total_amount ?? '';
                $paid_amount = $package_data->paid_amount ?? '';
                //$trip_date = !empty($package_data->trip_date)?date('d M,Y',strtotime($package_data->trip_date)):'';
                $trip_date = !empty($package_data->trip_date)?date('d M,Y',strtotime($package_data->trip_date)):'';

                $duration = $package_data->duration ?? '';
                $destination = $package_data->destination ?? '';
                $due_amount = $package_data->due_amount ?? '';
                $address = $package_data->address ?? '';
                $contact_person = $package_data->contact_person ?? '';
                $contact_phone = $package_data->contact_phone ?? '';
                $contact_email = $package_data->contact_email ?? '';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =public_path(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';

                        $userData = $userAgentInfo->User ?? '';
                        $agent_phone = '';
                        $agent_email = '';
                        if($userData->phone) {
                            $country_code = $userData->country_code ?? '91';
                            $agent_phone = '+'.$country_code.'-'.$userData->phone;
                        }
                        $agent_email = !empty($userData->email)?$userData->email:'';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = public_path('/storage/'.$path.$agentLogo);
                        }
                    }
                    $total_amount = $order->sub_total_amount ?? 0;
                }

                $common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{agent_logo}' => $logoSrc,
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                }else{
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                        '{company_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }

                $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
                $person = '';
                $total_amount = 0;
                $person = '';
                if(!empty($price_category_choice_record_arr)) {
                    foreach($price_category_choice_record_arr as $key => $pccr) {
                        $input_label = $pccr['input_label']??'';
                        $input_value = $pccr['input_value']??0;
                        $input_price = $pccr['input_price']??0;
                        $sub_total = $input_value*$input_price;
                        $total_amount += $sub_total;

                        $price = CustomHelper::getPrice($input_price);
                        if($key != 0){
                            $person.= ", ";
                        }
                        $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;

                    }
                }

                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);


                $contact_details = '';
                if(!empty($agent_id)){
                    $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                    $b2b_email_params = array(
                        '{agent_phone}' => $agent_phone,
                        '{agent_email}' => $agent_email
                    );
                    $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                /* $contact_details .= 
                '<tr>
                 <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                 <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Mobile:</b> <a href="tel:'.$agent_phone.'" style="color: #fff;text-decoration: none;">'.$agent_phone.'</a>; <b>Email:</b> <a href="mailto:'.$agent_email.'" style="color: #fff;text-decoration: none;">'.$agent_email.'</a></p>

                 <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>
                 </td>
                 </tr>';*/
         }else{

            $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
            $b2c_email_params = array(
                '{company_name}' => $company_name,
                '{sales_mobile}' => $sales_mobile,
                '{sales_phone}' => $sales_phone,
                '{sales_email}' => $sales_email,
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            /*$contact_details .= '<tr>
            <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
            <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

            <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
            </td>
            </tr>';*/
        }

        $email_params = array(
            '{header}' => $common_logo,
            '{order_no}' =>  $order->order_no??'',
            '{package_name}' =>  $package_name??'',
            '{itinerary}' =>  $itinerary??'',
            '{title}' =>  $OrderServiceVoucher->title??'',
            '{trip_date}' =>  $trip_date??'',
            '{duration}' =>  $duration??'',
            '{destination}' =>  $destination??'',
            '{name}' =>  $OrderServiceVoucher->name??'',
            '{phone}' =>  $OrderServiceVoucher->phone??'',
            '{email}' =>  $OrderServiceVoucher->email??'',
            '{package_charges}' =>  $package_charges??'',
            '{paid_amount}' =>  $paid_amount??'',
            '{due_amount}' =>  $due_amount??'',
            '{amount}' =>  $total_amount??0,
            '{address}' =>  $address??'',
            '{person}' => $person,
            '{contact_details}' => $contact_details??'',
            '{contact_person}' => $contact_person??'',
            '{contact_phone}' => $contact_phone??'',
            '{contact_email}' => $contact_email??'',

        );

        $price_details = '';
        if($order->hide_price_details != 1){
            $price_details = '';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid to Overland Escape</p>
                </td>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                </td>
                </tr>';
            }

            $price_details .= '<tr>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Total Booking Amount</p>
            </td>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
            </td>
            </tr>';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$due_amount.'</p>
                </td>
                </tr>';
            }
        }
        $email_params['{price_details}'] = $price_details;

        // $email_params = isset($email_params) ? $email_params : [];
        //$email_content= view('user.package_voucher_pdf', $email_params)->render();
        $email_content = view(config('custom.theme').'.common.vouchers.package_voucher_pdf', $email_params)->render();
        $email_content=
        $html = strtr($email_content, $email_params);

        // return view("admin.vouchers.cab_voucher_view", $data);
        // $html = preg_replace('/>\s+</', "><", $html);
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf_name = $order->order_no.'.pdf';
        return $pdf->download($pdf_name);

    }else{
        abort(404);
    }
}else{
    abort(404);
}
}else{
    abort(404);
}
}

public function activity_voucher_view_04july2023(Request $request) {
    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    //$order = Order::find($request->order_id);
    $user_id = Auth::user()->id??'';
    if(!empty($order_id) && !empty($user_id)) {
        $query = Order::where("id", $order_id);
        $query->where(function($q1) use($user_id) {
            $q1->where('user_id',$user_id);
        });
        $order = $query->first();
       //$order = Order::where("id", $order_id)->where('user_id',$user_id)->first();
        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){
                $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];

                $title = $OrderServiceVoucher->title ?? '';
                $trip_date = !empty($package_data->trip_date)?date('d M,Y',strtotime($package_data->trip_date)):'';
                $duration = $package_data->duration ?? '';
                $destination = $package_data->destination ?? '';
                $package_charge = $package_data->package_charges ?? '';
                $paid_amount = $package_data->paid_amount ?? '';
                $due_amount = $package_data->due_amount ?? '';
                $address = $package_data->address ?? '';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk("public");
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                $data = array(
                    'logo' => $logoSrc,
                    'order_no' =>  $order->order_no??'',
                    'title' =>  $OrderServiceVoucher->title??'',
                    'remarks' =>  $OrderServiceVoucher->remarks??'',
                    'trip_date' => $trip_date??'',
                    'duration' => $duration??'',
                    'address' => $address??'',
                    'destination' => $destination??'',
                    'package_charge' => $package_charge??'',
                    'name' =>  $OrderServiceVoucher->name??'',
                    'phone' =>  $OrderServiceVoucher->phone??'',
                    'email' =>  $OrderServiceVoucher->email??'',
                    'paid_amount' =>  $paid_amount??'',
                    'due_amount' =>  $due_amount??'',
                );


                $data['order_id'] = $order_id;
                $data['order'] = $order;
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
    $data['meta_title'] = 'Activity Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Activity Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    return view('user.activity_voucher_view', $data);
}

public function activity_voucher_view(Request $request) {
    $data = [];
    $order_id = $request->order_id ?? '';
    $user_id = Auth::user()->id??'';
    if(!empty($order_id) && !empty($user_id)) {
        $query = Order::where("id", $order_id);
        $query->where(function($q1) use($user_id) {
            $q1->where('user_id',$user_id);
        });
        $order = $query->first();
        // prd($order->toArray());
        if($order && $order->order_type == 6 && $order->payment_status == 1) {
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher) {
                $voucher_id = $OrderServiceVoucher->id ?? '';
                $SYSTEM_TITLE = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc = asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = asset('/storage/'.$path.$agentLogo);
                        }
                    }
                }

                /*$common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{common_logo}' => $logoSrc,
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                }else{
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                        '{common_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }*/

                $order_id = $order->id;
                $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
                $total_pax = $booking_details->total_pax ?? '';

                $user_phone = '';
                if($order_id) {
                    $country_code = $order->country_code ?? '91';
                    $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
                }

                $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
                $title = $OrderServiceVoucher->title ?? '';
                $package_name = $package_data->package_name ?? '';
                $package_charges = $package_data->package_charges ?? 0;
                $package_charges = (int)$package_charges+(int)$order->markup;
                $paid_amount = $package_data->paid_amount ?? 0;
                $paid_amount = (int)$paid_amount+(int)$order->markup;
                $trip_date = !empty($package_data->trip_date)?date('D, M dS Y h:i A',strtotime($package_data->trip_date)):'';
                $duration = $package_data->duration ?? '';
                $destination = $package_data->destination ?? '';
                $due_amount = $package_data->due_amount ?? '';
                $address = $package_data->address ?? '';
                $contact_person = $package_data->contact_person ?? '';
                $contact_phone = $package_data->contact_phone ?? '';
                $contact_email = $package_data->contact_email ?? '';
                $total_amount = $order->total_amount ?? 0;

                $segments3 = $request->segment(2);
                // prd($segments3);
                if($segments3 == 'activity_voucher_sendmail') {

                    $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                    $storage = Storage::disk('public');
                    $path = "settings/";
                    $logoSrc = public_path(config('custom.assets').'/images/logo.png');
                    if(!empty($FRONTEND_LOGO)){
                        if($storage->exists($path.$FRONTEND_LOGO)){
                            $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                        }
                    }

                    $userAgentInfo = ''; $agentLogo = '';
                    $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                    if($agent_id && $agent_id > 0){
                        $userAgentInfo = $order->agentInfo ?? '';
                        if($userAgentInfo){
                            $path = 'agent_logo/';
                            $agentLogo = $order->agentInfo->agent_logo ?? '';

                            $userData = $userAgentInfo->User ?? '';
                            $agent_phone = '';
                            $agent_email = '';
                            if($userData->phone) {
                                $country_code = $userData->country_code ?? '91';
                                $agent_phone = '+'.$country_code.'-'.$userData->phone;
                            }
                            $agent_email = !empty($userData->email)?$userData->email:'';
                        }
                        if(!empty($agentLogo)){
                            if($storage->exists($path.$agentLogo)){
                                $logoSrc = public_path('/storage/'.$path.$agentLogo);
                            }
                        }
                        $total_amount = $order->sub_total_amount ?? 0;
                    }

                    $common_logo = '';
                    if(!empty($agent_id)){
                        $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                        $b2b_logo_params = array(
                            '{agent_logo}' => $logoSrc,
                        );
                        $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                    }else{
                        $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                        $b2c_logo_params = array(
                            '{company_logo}' => $logoSrc
                        );
                        $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                    }

                    $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
                    $person = '';
                    $total_amount = 0;
                    if(!empty($price_category_choice_record_arr)) {
                        foreach($price_category_choice_record_arr as $key => $pccr) {
                            $input_label = $pccr['input_label']??'';
                            $input_value = $pccr['input_value']??0;
                            $input_price = $pccr['input_price']??0;
                            $sub_total = $input_value*$input_price;
                            $total_amount += $sub_total;

                            $price = CustomHelper::getPrice($input_price);
                            if($key != 0){
                                $person.= ", ";
                            }
                            $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;
                        }
                    }

                    $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                    $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                    $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                    $sales_phone = CustomHelper::getPhoneHref($company_phone);
                    $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                    $sales_email = CustomHelper::getEmailHref($company_email);


                    $contact_details = '';
                    if(!empty($agent_id)){
                        $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                        $b2b_email_params = array(
                            '{agent_phone}' => $agent_phone,
                            '{agent_email}' => $agent_email
                        );
                        $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                       /* $contact_details .= 
                        '<tr>
                         <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                         <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Mobile:</b> <a href="tel:'.$agent_phone.'" style="color: #fff;text-decoration: none;">'.$agent_phone.'</a>; <b>Email:</b> <a href="mailto:'.$agent_email.'" style="color: #fff;text-decoration: none;">'.$agent_email.'</a></p>

                         <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>
                         </td>
                         </tr>';*/
                    }else{

                    $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                    $b2c_email_params = array(
                        '{company_name}' => $company_name,
                        '{sales_mobile}' => $sales_mobile,
                        '{sales_phone}' => $sales_phone,
                        '{sales_email}' => $sales_email,
                        '{company_title}' => $system_title
                    );
                    $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                    /*$contact_details .= '<tr>
                    <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                    <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
                    <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

                    <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

                    <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

                    <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
                    </td>
                    </tr>';*/
                }

        $email_params = array(
            '{header}' => $common_logo,
            '{order_no}' =>  $order->order_no??'',
            '{package_name}' =>  $package_name??'',
            '{itinerary}' =>  $itinerary??'',
            '{title}' =>  $OrderServiceVoucher->title??'',
            '{trip_date}' =>  $trip_date??'',
            '{duration}' =>  $duration??'',
            '{destination}' =>  $destination??'',
            '{name}' =>  $OrderServiceVoucher->name??'',
            '{phone}' =>  $user_phone??'',
            '{email}' =>  $OrderServiceVoucher->email??'',
            '{amount}' =>  $total_amount??0,
            '{paid_amount}' =>  $paid_amount??'',
            '{due_amount}' =>  $due_amount??'',
            '{address}' =>  $address??'',
            '{contact_person}' =>  $contact_person??'',
            '{contact_details}' => $contact_details??'',
            '{contact_phone}' =>  $contact_phone??'',
            '{contact_email}' =>  $contact_email??'',
                            // '{person}' => $person,
            '{person}' => $total_pax,
        );


        $price_details = '';
        if($order->hide_price_details != 1) {
            //if(empty($agent_id)){
            $price_details .= '<tr><td colspan="2" style="padding: 2px 15px 2px 15px;">
            <p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: "Roboto", sans-serif, Arial;margin: 4px 0px;"><strong>Fare Details: </strong></p>
            </td></tr>';
                            //}
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid to Overland Escape</p>
                </td>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                </td>
                </tr>';
            }

            $price_details .= '<tr>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Total Booking Amount</p>
            </td>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
            </td>
            </tr>';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$due_amount.'</p>
                </td>
                </tr>';
            }
        }
        $email_params['{price_details}'] = $price_details;


        $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
        $email_template = EmailTemplate::where('slug', 'activity-voucher')->where('status', 1)->first();
        $email_content = isset($email_template->content) ? $email_template->content : '';
        $email_content = strtr($email_content, $email_params);
        $email_subject = isset($email_template->subject) ? $email_template->subject : '';
        $email_subject = strtr($email_subject, $email_params);
        $email_bcc_admin = isset($email_template->bcc_admin) ? $email_template->bcc_admin : 0;
        $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

        //$pdf_content = view("admin.vouchers.activity_voucher_pdf", $email_params)->render();
        $pdf_content = view(config('custom.theme').'.common.vouchers.activity_voucher_pdf', $email_params)->render();
        $html = strtr($pdf_content, $email_params);
        $pdf = PDF::loadHTML($html);
        $path = 'temp/';
        $pdf_name = $order->order_no.'.pdf';
        if (!File::exists(public_path("storage/" . $path))) {
            File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
        }
        $pdf->save(public_path("storage/" . $path).$pdf_name);
        $attachments = public_path("storage/".$path).$pdf_name;

        $REPLYTO = $ADMIN_EMAIL;
        $to_email = $OrderServiceVoucher->email ? $OrderServiceVoucher->email : '';
        $cc_email = '';
        $bcc_email = '';
        $params = [];
        $params['to'] = $to_email;
        $params['cc'] = $cc_email;
        $params['bcc'] = $bcc_email;
        $params['reply_to'] = $to_email;
        $params['subject'] = $email_subject;
        $params['email_content'] = $email_content;
        if(isset($attachments)) {
            $params['attachments'] = $attachments;
        }
        $isSent = CustomHelper::sendCommonMail($params);
        if($isSent) {
            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
            $module_desc =  !empty($title)?$title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $module_id= $order_id;
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Activity Voucher';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = '';
            $params['sub_module_id'] = $voucher_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = "Email Sent";

            CustomHelper::RecordActivityLog($params);
            $msg = "Mail sent successfully";
            return back()->with('alert-success', $msg);
        } else {
            $msg = "Error while sending email";
            return back()->with('alert-danger', $msg);
        }
    } else if($segments3 == 'activity_voucher_sendsms') {
        $sms_template_slug = 'activity-booking-sms';
        $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();

        $booking_data = $order->booking_details ?? '';
        $booking_details = json_decode($booking_data);
        $sms_content_arr = [];
        if(!empty($booking_details)) {
            $duration = !empty($booking_details->duration) ? $booking_details->duration : "";
            $total_pax = !empty($booking_details->total_pax) ? $booking_details->total_pax : "";
            $date_val = !empty($booking_details->start_time) ? date('d-M-Y', strtotime($booking_details->start_time)):'';
            $time_val = !empty($booking_details->start_time) ? date('h:i A', strtotime($booking_details->start_time)):'';
            $end_time = !empty($booking_details->end_time)?date('D, M dS Y h:i A',strtotime($booking_details->end_time)):'';
            $order_package_name = !empty($order->package_name) ? CustomHelper::wordsLimit($order->package_name, 22) : '';
            $sms_params = array(
                '{#var1#}' => $order_package_name??'',
                '{#var2#}' => $order->order_no??0,
                '{#var3#}' => $date_val??'',
                '{#var4#}' => $time_val??'',
                '{#var5#}' => $duration??'',
                '{#var6#}' => $total_pax??'',
            );
            $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
            $content = strtr($sms_content, $sms_params);
            $sms_content_arr[] = $content;
        }

        $success = false;
        if(isset($sms_content_data) && !empty($sms_content_data)) {
            if(isset($sms_content_arr) && !empty($sms_content_arr)) {
                $sms_content_arr = $sms_content_arr;
            } else if(!empty($content)) {
                $sms_content_arr = [];
                $sms_content_arr[] = $content;
            }
            if(isset($sms_content_arr) && !empty($sms_content_arr)) {
                foreach($sms_content_arr as $content) {
                    if(!empty($content)) {
                        $content = urlencode($content);
                        $params  = [];
                        $params['phone'] = $phone??'';
                        $params['content'] = $content;
                        $params['template_id'] = $sms_content_data->template_id??'';
                        $is_sms_sent = CustomHelper::send_sms($params);
                        if($is_sms_sent) {
                            $success = true;
                        }
                    }
                }
            }
        }
        if($success) {
            $msg = "SMS sent successfully";
            return back()->with('alert-success', $msg);
        } else {
            $msg = "Error while sending SMS";
            return back()->with('alert-danger', $msg);
        }
    } else if($segments3 == 'activity_voucher_pdf') {
        $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
        $storage = Storage::disk('public');
        $path = "settings/";
        $logoSrc = public_path(config('custom.assets').'/images/logo.png');
        if(!empty($FRONTEND_LOGO)){
            if($storage->exists($path.$FRONTEND_LOGO)){
                $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
            }
        }

        $userAgentInfo = ''; $agentLogo = '';
        $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
        if($agent_id && $agent_id > 0){
            $userAgentInfo = $order->agentInfo ?? '';
            if($userAgentInfo){
                $path = 'agent_logo/';
                $agentLogo = $order->agentInfo->agent_logo ?? '';

                $userData = $userAgentInfo->User ?? '';
                $agent_phone = '';
                $agent_email = '';
                if($userData->phone) {
                    $country_code = $userData->country_code ?? '91';
                    $agent_phone = '+'.$country_code.'-'.$userData->phone;
                }
                $agent_email = !empty($userData->email)?$userData->email:'';
            }
            if(!empty($agentLogo)){
                if($storage->exists($path.$agentLogo)){
                    $logoSrc = public_path('/storage/'.$path.$agentLogo);
                }
            }
            $total_amount = $order->sub_total_amount ?? 0;
        }

        $common_logo = '';
        if(!empty($agent_id)){
            $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
            $b2b_logo_params = array(
                '{agent_logo}' => $logoSrc,
            );
            $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
        }else{
            $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
            $b2c_logo_params = array(
                '{company_logo}' => $logoSrc
            );
            $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
        }

        $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
        $person = '';
        $total_amount = 0;
        $person = '';
        if(!empty($price_category_choice_record_arr)) {
            foreach($price_category_choice_record_arr as $pccr) {
                $input_label = $pccr['input_label']??'';
                $input_value = $pccr['input_value']??0;
                $input_price = $pccr['input_price']??0;
                $sub_total = $input_value*$input_price;
                $total_amount += $sub_total;

                $price = CustomHelper::getPrice($input_price);
                $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;
                $person.= ", ";
            }
        }

        $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
        $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
        $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
        $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

        $sales_phone = CustomHelper::getPhoneHref($company_phone);
        $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
        $sales_email = CustomHelper::getEmailHref($company_email);


        $contact_details = '';
        if(!empty($agent_id)){
            $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
            $b2b_email_params = array(
                '{agent_phone}' => $agent_phone,
                '{agent_email}' => $agent_email
            );
            $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
           /* $contact_details .= 
            '<tr>
             <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
             <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Mobile:</b> <a href="tel:'.$agent_phone.'" style="color: #fff;text-decoration: none;">'.$agent_phone.'</a>; <b>Email:</b> <a href="mailto:'.$agent_email.'" style="color: #fff;text-decoration: none;">'.$agent_email.'</a></p>

             <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>
             </td>
             </tr>';*/
            }else{

            $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
            $b2c_email_params = array(
                '{company_name}' => $company_name,
                '{sales_mobile}' => $sales_mobile,
                '{sales_phone}' => $sales_phone,
                '{sales_email}' => $sales_email,
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            /*$contact_details .= '<tr>
            <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
            <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

            <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
            </td>
            </tr>';*/
        }

        $email_params = array(
            '{header}' => $common_logo,
            '{order_no}' =>  $order->order_no??'',
            '{package_name}' =>  $package_name??'',
            '{title}' =>  $OrderServiceVoucher->title??'',
            '{trip_date}' =>  $trip_date??'',
            '{duration}' =>  $duration??'',
            '{destination}' =>  $destination??'',
            '{name}' =>  $OrderServiceVoucher->name??'',
            '{phone}' =>  $user_phone??'',
            '{email}' =>  $OrderServiceVoucher->email??'',
            '{amount}' =>  $total_amount??0,
            '{paid_amount}' =>  $paid_amount??'',
            '{due_amount}' =>  $due_amount??'',
            '{address}' =>  $address??'',
            '{contact_person}' =>  $contact_person??'',
            '{contact_details}' =>  $contact_details??'',
            '{contact_phone}' =>  $contact_phone??'',
            '{contact_email}' =>  $contact_email??'',
                            // '{person}' => $person,
            '{person}' => $total_pax,
        );

        $price_details = '';
        if($order->hide_price_details != 1){
            $price_details .= '<tr><td colspan="2" style="padding: 2px 15px 2px 15px;">
            <p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: "Roboto", sans-serif, Arial;margin: 4px 0px;"><strong>Fare Details: </strong></p>
            </td></tr>';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid to Overland Escape</p>
                </td>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                </td>
                </tr>';
            }

            $price_details .= '<tr>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Total Booking Amount</p>
            </td>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
            </td>
            </tr>';

            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Pending</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$due_amount.'</p>
                </td>
                </tr>';
            }
        }
        $email_params['{price_details}'] = $price_details;
                        //$email_content = view('user.activity_voucher_pdf', $email_params)->render();
        $email_content = view(config('custom.theme').'.common.vouchers.activity_voucher_pdf', $email_params)->render();
        $html = strtr($email_content, $email_params);
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf_name = $order->order_no.'.pdf';
        return $pdf->download($pdf_name);
    } else {
        $data = array(
            'logo' => $logoSrc,
            'order_no' =>  $order->order_no??'',
            'title' =>  $OrderServiceVoucher->title??'',
            'remarks' =>  $OrderServiceVoucher->remarks??'',
            'trip_date' => $trip_date??'',
            'duration' => $duration??'',
            'address' => $address??'',
            'destination' => $destination??'',
            'package_charge' => $package_charge??'',
            'name' =>  $OrderServiceVoucher->name??'',
            'phone' =>  $user_phone??'',
            'email' =>  $OrderServiceVoucher->email??'',
            'paid_amount' =>  $paid_amount??'',
            'due_amount' =>  $due_amount??'',
            'contact_person' =>  $contact_person??'',
            'contact_phone' =>  $contact_phone??'',
            'contact_email' =>  $contact_email??'',
            'person' =>  $total_pax??'',
            'package_charges' =>  $package_charges??'',
            'hide_price_details' =>  $order->hide_price_details??0,
        );
        $data['order_id'] = $order_id;
    }
    // $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Flight Voucher')->get();
    $data['order'] = $order;
    $data['meta_title'] = 'Activity Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'Activity Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

    return Inertia::render('user/activity_voucher_view', $data);
    // return view('user.activity_voucher_view', $data);
} else {
    abort(404);
}
} else {
    abort(404);
}
} else {
    abort(404);
}
}

public function showHideActivitySettings(Request $request) {
    $response = [];
    $response['success'] = false;
    $action = $request->action??'';
    $order_id = $request->order_id??'';
    if($order_id) {
        $order = Order::find($order_id);
        $user_id = Auth::user()->id??'';
        $is_agent = Auth::user()->is_agent??'';
        $agent_id = 0;
        if($is_agent == 1 && ($order->agent_id == $user_id || $order->user_id == $user_id) ) {
            $agent_id = $user_id;
        }
        if($order && $order->order_type == 6 && !empty($agent_id)) {
            if($action == 'hide_markup') {
                $hide_markup = $request->hide_markup??0;
                $order->hide_markup = $hide_markup;
                $response['message'] = 'Markup status updated successfully';
            }
            if($action == 'hide_agent_details') {
                $hide_agent_details = $request->hide_agent_details??0;
                $order->hide_agent_details = $hide_agent_details;
                $response['message'] = 'My Details status updated successfully';
            }
            if($action == 'hide_price_details') {
                $hide_price_details = $request->hide_price_details??0;
                $order->hide_price_details = $hide_price_details;
                $response['message'] = 'Price Details status updated successfully';
            }
            if($action == 'save_markup') {
                $markup = $request->markup??0;
                $order->markup = $markup;
                $response['message'] = 'Markup updated successfully';
            }
            $order->save();
            $response['success'] = true;
        }
    }
    return Response::json($response);
}

public function activity_voucher_pdf_04june2023(Request $request) {

    $data = [];
    $order = "";
    $order_id = $request->order_id ?? '';
    //$order = Order::find($request->order_id);
    $user_id = Auth::user()->id??'';
    if(!empty($order_id) && !empty($user_id)) {
        $query = Order::where("id", $order_id);
        $query->where(function($q1) use($user_id) {
            $q1->where('user_id',$user_id);

        });
        $order = $query->first();
       //$order = Order::where("id", $order_id)->where('user_id',$user_id)->first();
        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){

                $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
                // prd($package_data);

                $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
                $title = $OrderServiceVoucher->title ?? '';
                $package_name = $package_data->package_name ?? '';
                $package_charges = $package_data->package_charges ?? '';
                $paid_amount = $package_data->paid_amount ?? '';
                $trip_date = !empty($package_data->trip_date)?date('d M,Y',strtotime($package_data->trip_date)):'';

                $duration = $package_data->duration ?? '';
                $destination = $package_data->destination ?? '';
                $due_amount = $package_data->due_amount ?? '';
                $address = $package_data->address ?? '';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =public_path(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = public_path('/storage/'.$path.$agentLogo);
                        }
                    }
                }

                $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
                $package_charges = '';
                $total_amount = 0;
                $person = '';
                if(!empty($price_category_choice_record_arr)) {
                    foreach($price_category_choice_record_arr as $pccr) {
                        $input_label = $pccr['input_label']??'';
                        $input_value = $pccr['input_value']??0;
                        $input_price = $pccr['input_price']??0;
                        $sub_total = $input_value*$input_price;
                        $total_amount += $sub_total;

                        $price = CustomHelper::getPrice($input_price);
                        $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;
                        $person.= ", ";

                    }
                }

                $email_params = array(
                    '{logo}' => $logoSrc,
                    '{order_no}' =>  $order->order_no??'',
                    '{package_name}' =>  $package_name??'',
                    '{title}' =>  $OrderServiceVoucher->title??'',
                    '{trip_date}' =>  $trip_date??'',
                    '{duration}' =>  $duration??'',
                    '{destination}' =>  $destination??'',
                    '{name}' =>  $OrderServiceVoucher->name??'',
                    '{phone}' =>  $OrderServiceVoucher->phone??'',
                    '{email}' =>  $OrderServiceVoucher->email??'',
                    '{package_charges}' =>  $package_charges??'',
                    '{paid_amount}' =>  $paid_amount??'',
                    '{due_amount}' =>  $due_amount??'',
                    '{address}' =>  $address??'',
                    '{person}' => $person,

                );

                // $email_params = isset($email_params) ? $email_params : [];
                $email_content= view('user.activity_voucher_pdf', $email_params)->render();
                $email_content=
                $html = strtr($email_content, $email_params);
                // $html = preg_replace('/>\s+</', "><", $html);
                $pdf = PDF::loadHTML($html);
                $pdf->setPaper('A4', 'portrait');
                $pdf_name = $order->order_no.'.pdf';
                return $pdf->download($pdf_name);

            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
}

public function myFavorite(Request $request){

    $limit = $this->limit;
    $data = [];
    $data['meta_title'] = 'My Favorite - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'My Favorite Description';

    if(!empty(Auth::check())){
        $userId = Auth::user()->id;

        $data['popularPackageWishlists'] = DB::table('users_favourite_packages as ufp')->leftjoin('packages as p','p.id','=','ufp.package_id')->leftjoin('users as u','u.id','=','ufp.user_id')->selectRaw('p.*')->where(['p.status'=>1,'ufp.user_id'=> $userId])->groupBy('p.id')->orderBy('p.created_at','desc')->paginate($limit);
            //prd($data['popularPackageWishlists']->toArray());
    }

    $data['meta_title'] = 'My Favorite WishList - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    $data['meta_description'] = 'My Favorite WishList - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    return Inertia::render('user/favorite', $data);
    //return view('user.favorite', $data);
}

public function record_package_favourite(Request $request)
{
    $output = array();
    if(Auth::check())
    {
        $user_id = Auth::user()->id;
        $package_id = isset($request->data_id) ? $request->data_id:"";
        $status = isset($request->status) ? $request->status : '0';
        if(!empty($package_id))
        {
            $favourite_data = DB::table('users_favourite_packages')->where('user_id',$user_id)->where('package_id',$package_id)->get();
                //echo '<pre>'; print_r($favourite_data); die;

                    /*if(!$favourite_data->isEmpty() && $status == 1)
                    {
                        $output['status'] = 'error';
                        $output['message'] = 'already add in favourite.';
                    }else*/
                    if(!$favourite_data->isEmpty() && $status == 0){

                        DB::table('users_favourite_packages')->where('user_id',$user_id)->where('package_id',$package_id)->delete();

                        $output['status'] = 'success';
                        $output['message'] = 'delete favourite';
                    }else{
                        DB::table('users_favourite_packages')->insert(['user_id' => $user_id, 'package_id' => $package_id, 'created_at' => date('Y-m-d H:i:s')]);

                        $output['status'] = 'success';
                        $output['message'] = 'add favourite';
                    }

                }
            }
            else
            {
                $output['status'] = 'error';
                $output['message'] = 'Invalid request!';
            }
            return response()->json($output);
        }


        public function flight_voucher_view(Request $request) {
            $data = [];
            $order_id = $request->order_id ?? '';
            $user_id = Auth::user()->id??'';
            if(!empty($order_id) && !empty($user_id)) {
                $query = Order::where("id", $order_id);
                $query->where(function($q1) use($user_id) {
                    $q1->where('user_id',$user_id);

                });
                $order = $query->first();
                // prd($order->toArray());
                if($order && $order->order_type == 3 && $order->payment_status == 1 && !empty($order->bookingId)) {
                    $SYSTEM_TITLE = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                    $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                    $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                    $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                    $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                    $sales_phone = CustomHelper::getPhoneHref($company_phone);
                    $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                    $sales_email = CustomHelper::getEmailHref($company_email);
                    
                    $storage = Storage::disk('public');
                    $path = "settings/";
                    $logoSrc =asset(config('custom.assets').'/images/logo.png');
                    if(!empty($FRONTEND_LOGO)){
                        if($storage->exists($path.$FRONTEND_LOGO)){
                            $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                        }
                    }

                    // AGENT LOGO
                    $userAgentInfo = ''; $agentLogo = '';
                    $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                    if($agent_id && $agent_id > 0){
                        $userAgentInfo = $order->agentInfo ?? '';
                        if($userAgentInfo && $userAgentInfo->count() > 0){
                            $path = 'agent_logo/';
                            $agentLogo = $order->agentInfo->agent_logo ?? '';

                            $userData = $userAgentInfo->User ?? '';
                            $agent_phone = '';
                            $agent_email = '';
                            if($userData->phone) {
                                $country_code = $userData->country_code ?? '91';
                                $agent_phone = '+'.$country_code.'-'.$userData->phone;
                            }
                            $agent_email = !empty($userData->email)?$userData->email:'';
                        }
                        if(!empty($agentLogo)){
                            if($storage->exists($path.$agentLogo)){
                                $logoSrc = asset('/storage/'.$path.$agentLogo);
                            }
                        }
                    }

                    $common_logo = '';
                    if(!empty($agent_id)){
                        $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                        $b2b_logo_params = array(
                            '{agent_logo}' => $logoSrc
                        );
                        $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                    }else{
                        $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                        $b2c_logo_params = array(
                            '{company_logo}' => $logoSrc
                        );
                        $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                    }

                    $contact_details = '';
                    if(!empty($agent_id)){
                        $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                        $b2b_email_params = array(
                            '{agent_phone}' => $agent_phone,
                            '{agent_email}' => $agent_email
                        );
                        $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                    }else{
                        $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                        $b2c_email_params = array(
                            '{company_name}' => $company_name,
                            '{sales_mobile}' => $sales_mobile,
                            '{sales_phone}' => $sales_phone,
                            '{sales_email}' => $sales_email,
                            '{company_title}' => $SYSTEM_TITLE
                        );
                        $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                    }

                    $order_id = $order->id;
                    $phone = '';
                    if($order->phone) {
                        $phone_country = $order->phone_country ?? '91';
                        $phone = '+'.$phone_country.'-'.$order->phone;
                    }
                    $segments3 = $request->segment(2);
                    // prd($segments3);
                    if($segments3 == 'flight_voucher_sendmail') {
                        $record_name = 'Booking Flight';
                        $form_values = [];
                        $form_values['{header}'] = $common_logo??'';
                        $form_values['{logo}'] = $logoSrc??'';
                        $form_values['{contact_details}'] = $contact_details??'';
                        $form_values['{e_date}'] = date("l jS \of F Y h:i A");
                        $form_values['{SYSTEM_TITLE}'] = $SYSTEM_TITLE;
                        $form_values['{name}'] = $order->name;
                        $form_values['{email}'] = $order->email;
                        $form_values['{phone}'] = $phone;
                        $params = [];
                        $params['order'] = $order;
                        if($order->booking_details) {
                            $params['booking_details'] = json_decode($order->booking_details, true);
                        }
                        $itineraries = view('emails._flight_booking_email',$params)->render();
                        $form_values['{city}'] = $order->city;
                        if($order->trip_date) {
                            $form_values['{date}'] = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                        } else {
                            $form_values['{date}'] = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                        }
                        $form_values['{total_amount}'] = CustomHelper::getPrice($order->total_amount);
                        $form_values['{itineraries}'] = $itineraries;
                        $form_values['{booking_id}'] = $order->order_no??'';

                        $email_template = EmailTemplate::where('slug','flight-booking-email')->first();

                        $pdf = PDF::loadView("emails._flight_booking_pdf", $params);

                        $path = 'temp/';
                        $pdf_name = $order->order_no.'.pdf';
                        if (!File::exists(public_path("storage/" . $path))) {
                            File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
                        }
                        $pdf->save(public_path("storage/" . $path).$pdf_name);
                        $attachments = public_path("storage/".$path).$pdf_name;
                        $isSent = false;
                        if(!empty($order->email) && !empty($email_template)) {
                            $email = $order->email;
                            $cc_email = '';
                            $bcc_email = '';

                            $search_arr = array_keys($form_values);
                            $replace_arr = array_values($form_values);
                            $email_subject = str_replace($search_arr, $replace_arr, $email_template->subject);
                            $search_arr = array_keys($form_values);
                            $replace_arr = array_values($form_values);
                            $email_content = str_replace($search_arr, $replace_arr, $email_template->content);
                            $email_bcc_admin = $email_template->bcc_admin ?? 0;
                            $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

                            //Dynamic send mail customer or Admin
                            if($email_type == 'admin'){
                                $email = $ADMIN_EMAIL;
                            }

                            if($email_bcc_admin == 1){
                                $bcc_email = $ADMIN_EMAIL;
                            }

                            // Email to Admin
                            $params = [];
                            $params['to'] = $email;
                            $params['reply_to'] = $ADMIN_EMAIL;
                            $params['cc'] = $cc_email;
                            $params['bcc'] = $bcc_email;
                            $params['subject'] = $email_subject;
                            $params['email_content'] = $email_content;
                            if(isset($attachments)) {
                                $params['attachments'] = $attachments;
                            }
                            $params['module_name'] = $record_name;
                            $params['record_id'] = $order->id ?? 0;
                            $isSent = CustomHelper::sendCommonMail($params);
                        }

                        if($isSent) {
                            $user_id = auth()->user()->id;
                            $user_name = auth()->user()->name;
                            $module_desc =  !empty($title)?$title:'';
                            $module_id = $order_id;
                            $params['user_id'] = $user_id;
                            $params['user_name'] = $user_name;
                            $params['module'] = 'Flight Voucher';
                            $params['module_desc'] = $module_desc;
                            $params['module_id'] = $module_id;
                            $params['sub_module'] = '';
                            $params['sub_module_id'] = '';
                            $params['sub_sub_module'] = "";
                            $params['sub_sub_module_id'] = 0;
                            $params['data_after_action'] = '';
                            $params['action'] = "Email Sent";

                            CustomHelper::RecordActivityLog($params);
                            $msg = "Mail sent successfully";
                            return back()->with('alert-success', $msg);
                        } else {
                            $msg = "Error while sending email";
                            return back()->with('alert-danger', $msg);
                        }
                    } else if($segments3 == 'flight_voucher_sendsms') {
                        $sms_content_data = SmsTemplate::where('slug', 'flight-booking-sms')->where('status', 1)->first();
                        // prd($sms_content_data->toArray());
                        $sms_content_arr = [];
                        $booking_details = json_decode($order->booking_details, true);
                        if(isset($booking_details['itemInfos']['AIR']['tripInfos']) && !empty($booking_details['itemInfos']['AIR']['tripInfos'])) {
                            foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo) {
                                $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
                                $dep_code = $tripInfo['sI'][0]['da']['code']??'';
                                $arr_code = $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'';
                                $sector = $dep_code.'-'.$arr_code;
                                $dep = $tripInfo['sI'][0]['dt']??'';
                                $arr = $tripInfo['sI'][count($tripInfo['sI'])-1]['at']??'';
                                $flight = $tripInfo['sI'][0]['fD']['aI']['name']??'';
                                $pnr = $travellerInfos[0]['pnrDetails'][$sector]??'';
                                $customer = ($travellerInfos[0]['fN'].' '.$travellerInfos[0]['lN'])?? '';
                                if(count($travellerInfos) > 1) {
                                    $customer = $customer . ' +'.(count($travellerInfos)-1).' ';
                                }
                                $sms_params = array(
                                    '{#var1#}' => $sector,
                                    '{#var2#}' => $dep,
                                    '{#var3#}' => $arr,
                                    '{#var4#}' => $flight,
                                    '{#var5#}' => $pnr,
                                    '{#var6#}' => $customer,
                                );

                                $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                                $content = strtr($sms_content, $sms_params);
                            // $content = "Flight - Your ticket details for Sector : ".$sector." Dep:".$dep." Arr: ".$arr." Flight: ".$flight." PNR: ".$pnr." Customer: ".$customer." overlandescape.com";
                                $sms_content_arr[] = $content;
                            }
                        }
                        $success = false;
                        if(isset($sms_content_data) && !empty($sms_content_data)) {
                            if(isset($sms_content_arr) && !empty($sms_content_arr)) {
                                $sms_content_arr = $sms_content_arr;
                            } else if(!empty($content)) {
                                $sms_content_arr = [];
                                $sms_content_arr[] = $content;
                            }
                            if(isset($sms_content_arr) && !empty($sms_content_arr)) {
                                foreach($sms_content_arr as $content) {
                                    if(!empty($content)) {
                                        $content = urlencode($content);
                                        $params  = [];
                                        $params['phone'] = $phone??'';
                                        $params['content'] = $content;
                                        $params['template_id'] = $sms_content_data->template_id??'';
                                        $is_sms_sent = CustomHelper::send_sms($params);
                                        if($is_sms_sent) {
                                            $success = true;
                                        }
                                    }
                                }
                            }
                        }
                        if($success) {
                            $msg = "SMS sent successfully";
                            return back()->with('alert-success', $msg);
                        } else {
                            $msg = "Error while sending SMS";
                            return back()->with('alert-danger', $msg);
                        }
                    } else if($segments3 == 'flight_voucher_pdf') {
                        $params = [];
                        $params['order'] = $order;
                        if($order->booking_details) {
                            $params['booking_details'] = json_decode($order->booking_details, true);
                        }
                        $params['print_name'] = $request->name??'';
                        $pdf = PDF::loadView("emails._flight_booking_pdf", $params);
                        $path = 'temp/';
                        $pdf_name = $order->order_no.((!empty($params['print_name']))?'-'.$params['print_name']:'').'.pdf';
                        return $pdf->download($pdf_name);
                    } else {
                        $data = array(
                            'name' =>  $order->name??'',
                            'phone' =>  $phone,
                            'email' =>  $order->email??'',
                        );
                        $data['order_id'] = $order_id;

                        $params = [];
                        $params['order'] = $order;
                        if($order->booking_details) {
                            $params['booking_details'] = json_decode($order->booking_details, true);
                        }
                        $params['width_percent'] = '100%';
                        $params['width_px'] = '100%';
                        $params['print_flight'] = true;
                        $params['print_route'] = 'user.flight_voucher_pdf';
                        $data['itineraries'] = view("emails._flight_booking_email", $params)->render();
                    }

                    // $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Flight Voucher')->get();
                    $data['order'] = $order;
                    $data['meta_title'] = 'Flight Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $data['meta_description'] = 'Flight Voucher View - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

                    return Inertia::render('user/flight_voucher_view', $data);
                    // return view('user.flight_voucher_view', $data);
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }
        public function agent_signup_2_03_10_23(Request $request){

            $old_user_id = 0;
            if(Session::has('old_user_id')){
                $old_user_id = Session::get('old_user_id');
            }else{
                auth()->logout();
                \Session::flush();
                \Session::flash('alert-success','Invalid Request');
                return redirect('/');
            }
            $data = [];
            $method = $request->method();

            if($method == 'POST'){
                $rules = [];
                $ext = "jpg,jpeg,png,gif";
                $rules['company_name'] = 'required|max:100';
                $rules['company_brand'] = 'required|max:100';
                $rules['company_owner_name'] = 'required|max:100';
                $rules['destinations_sell_most'] = 'nullable|max:100';
                $rules['agency_age'] = 'nullable|max:100';
                $rules['no_of_employees'] = 'nullable|max:100';
                $rules['region'] = 'nullable|max:100';
                $rules['company_profile'] = 'required|max:100';
                $rules['pan_no'] = 'required|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';
                $rules['pan_image'] = 'required|mimes:jpeg,pdf|max:10240';
                //$rules['gst_no'] = 'nullable|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([A-Za-z]){2}?$/';
                $rules['gst_no'] = 'nullable|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/';

                $rules['gst_image'] = 'nullable|mimes:jpeg,pdf|max:10240';
                $rules['agent_logo'] = 'nullable|mimes:'.$ext; 

                $this->validate($request, $rules);

                if($request->old_user_id > 0){
                    $req_data = [];
                    if($request->hasFile('pan_image')){
                        $imgData = $request->file('pan_image');
                        $path = 'agentuser/';
                        $thumb_path = 'agentuser/thumb/';
                        $storage = Storage::disk('public');

                        $IMG_WIDTH = 800;
                        $IMG_HEIGHT = 800;
                        $THUMB_WIDTH = 400;
                        $THUMB_HEIGHT = 400;
                        $uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
                        if($uploaded_data['success']){
                            $image = $uploaded_data['file_name'];
                        }
                    }
                    if($request->hasFile('gst_image')){
                        $imgData = $request->file('gst_image');
                        $path = 'agentuser/';
                        $thumb_path = 'agentuser/thumb/';
                        $storage = Storage::disk('public');

                        $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:800;
                        $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:800;
                        $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
                        $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

                        $uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

                        if($uploaded_data['success']){
                            $gst_image = $uploaded_data['file_name'];
                        }
                    }

                    $req_data['user_id'] = $request->old_user_id ?? 0;
                    $req_data['company_name'] = $request->company_name ?? '';
                    $req_data['company_brand'] = $request->company_brand ?? '';
                    $req_data['pan_no'] = $request->pan_no ?? '';
                    $req_data['gst_no'] = $request->gst_no ?? '';
                    $req_data['company_owner_name'] = $request->company_owner_name ?? '';
                    $req_data['pan_image'] = $image ?? NULL;
                    $req_data['gst_image'] = $gst_image??NULL;
                    $req_data['bookings_per_month'] = $request->bookings_per_month ?? '';
                    $req_data['sales_team_size'] = $request->sales_team_size ?? '';
                    $req_data['source'] = $request->source ?? '';
                    $req_data['website'] = $request->website ?? '';
                    $req_data['whatsapp_phone'] = $request->whatsapp_phone ?? '';
                    $req_data['whatsapp_country_code'] = $request->whatsapp_country_code ??91;
                    $req_data['query'] = $request->queries ?? '';
                    $req_data['destinations_sell_most'] = $request->destinations_sell_most ?? '';
                    $req_data['agency_age'] = $request->agency_age ?? '';
                    $req_data['no_of_employees'] = $request->no_of_employees ?? 0;
                    $req_data['region'] = $request->region ?? '';
                    $req_data['company_profile'] = $request->company_profile ?? '';
                    $isSaved = UserAgentInfo::create($req_data);

                    if ($request->hasFile("agent_logo")) {
                        $file = $request->file("agent_logo");
                        $image_result = $this->saveImage($file, $isSaved->id);
                        if ($image_result["success"] == false) {
                            session()->flash(
                                "alert-danger",
                                "Image could not be added"
                            );
                        }
                    }

                }

                if(isset($isSaved) && !empty($isSaved)){
                    // session(['need_approval'=>1]);
                    // return redirect(url('account/approval'));
                    // return redirect(route('user.approval'));
                    // return view('user.approval');
                    return redirect(url('user/approval'));
                }
            }

            $data['old_user_id'] = $old_user_id;
            $data['countries'] = Country::where('status',1)->get();

            $data['meta_title'] = 'Agent Signup - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $data['meta_description'] = 'Agent Signup - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

            return view('user.agent-signup', $data);
        }


        public function approval(Request $request){

            $data = [];
            $data['meta_title'] = 'Agent Approval - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $data['meta_description'] = 'Agent Approval - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

            return Inertia::render('user/approval', $data);
            // return view('user.approval');
        }

        public function price_markup(Request $request) {
            $data = [];
            $agent_id = Auth::user()->id??0;
            $is_agent = Auth::user()->is_agent??0;
            if($is_agent==0) {
                return back()->with('alert-danger', 'Invalid access');
            }
            if($request->method() == 'POST') {
                $request_data = $request->toArray();
                $airline_codes = $request->airline_codes??[];
                if(!empty($airline_codes)) {
                    foreach($airline_codes as $code) {
                        if($code) {
                            $record = [
                                'offer_markup_type' => $request_data['offer_markup_type'][$code]??'',
                                'offer_markup_value' => $request_data['offer_markup_value'][$code]??0,
                                'online_markup_type' => $request_data['online_markup_type'][$code]??'',
                                'online_markup_value' => $request_data['online_markup_value'][$code]??0,
                            ];
                            // prd($record);
                            $exists = AgentAirlineMarkup::where('agent_id',$agent_id)->where('code',$code,)->first();
                            if($exists) {
                                $isSaved = AgentAirlineMarkup::where('id',$exists->id)->update($record);
                            } else {
                                $record['code'] = $code;
                                $record['agent_id'] = $agent_id;
                                $isSaved = AgentAirlineMarkup::create($record);
                            }
                        }
                    }
                }
                if($request->back_url) {
                    $back_url = $request->back_url;
                } else {
                    $back_url = url('user/price-markup');
                }
                $msg = 'Price markup settings updated successfully.';
                return redirect($back_url)->with('alert-success', $msg);
            }

            $query = AgentAirlineMarkup::where('agent_id',$agent_id);
            $records = $query->get();
            $data['records'] = $records;

            $query = AirlineMarkup::orderBy('sort_order','asc');
            $airlines = $query->get();
            $data['airlines'] = $airlines;

            $data['meta_title'] = 'Price Markup Settings - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $data['meta_description'] = 'Price Markup Settings - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

            return Inertia::render('user/price_markup', $data);
            // return view('user.price_markup',$data);
        }

        public function getCancelFlightCharges(Request $request) {
            $response = [];
            $response['success'] = false;
            $order_id = $request->order_id??'';
            if($order_id) {
                $params = $request->toArray();
                $response = Order::getCancelFlightCharges($order_id,$params);
            }
            return Response::json($response);
        }

        public function cancelFlight(Request $request) {
            $response = [];
            $response['success'] = false;
            $order_id = $request->order_id??'';
            if($order_id) {
                $params = $request->toArray();
                $response = Order::cancelFlight($order_id,$params);
            }
            return Response::json($response);
        }

        public function showHideFlightSettings(Request $request) {
            $response = [];
            $response['success'] = false;
            $action = $request->action??'';
            $order_id = $request->order_id??'';
            if($order_id) {
                $order = Order::find($order_id);
                $user_id = Auth::user()->id??'';
                $is_agent = Auth::user()->is_agent??'';
                $agent_id = 0;
                if($is_agent == 1 && ($order->agent_id == $user_id || $order->user_id == $user_id) ) {
                    $agent_id = $user_id;
                }
                if($order && $order->order_type == 3 && !empty($agent_id)) {
                    if($action == 'hide_markup') {
                        $hide_markup = $request->hide_markup??0;
                        $order->hide_markup = $hide_markup;
                        $response['message'] = 'Markup status updated successfully';
                    }
                    if($action == 'hide_agent_details') {
                        $hide_agent_details = $request->hide_agent_details??0;
                        $order->hide_agent_details = $hide_agent_details;
                        $response['message'] = 'My Details status updated successfully';
                    }
                    if($action == 'hide_price_details') {
                        $hide_price_details = $request->hide_price_details??0;
                        $order->hide_price_details = $hide_price_details;
                        $response['message'] = 'Price Details status updated successfully';
                    }
                    if($action == 'save_markup') {
                        $markup = $request->markup??0;
                        $order->markup = $markup;
                        $response['message'] = 'Markup updated successfully';
                    }
                    $order->save();
                    $response['success'] = true;
                }
            }
            return Response::json($response);
        }

        public function cancel_flight(Request $request) {
            $data = [];
            $order_id = $request->order_id ?? '';
            if($request->order_id) {
                $order = Order::find($request->order_id);
                // prd($order->toArray());
                if($order && $order->order_type == 3 && $order->payment_status == 1 && !empty($order->bookingId)) {
                    $SYSTEM_TITLE = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                    $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                    $storage = Storage::disk('public');
                    $path = "settings/";
                    $logoSrc =asset(config('custom.assets').'/images/logo.png');
                    if(!empty($FRONTEND_LOGO)){
                        if($storage->exists($path.$FRONTEND_LOGO)){
                            $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                        }
                    }

                    // AGENT LOGO
                    $userAgentInfo = ''; $agentLogo = '';
                    $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                    if($agent_id && $agent_id > 0){
                        $userAgentInfo = $order->agentInfo ?? '';
                        if($userAgentInfo){
                            $path = 'agent_logo/';
                            $agentLogo = $order->agentInfo->agent_logo ?? '';
                        }
                        if(!empty($agentLogo)){
                            if($storage->exists($path.$agentLogo)){
                                $logoSrc = asset('/storage/'.$path.$agentLogo);
                            }
                        }
                    }

                    $order_id = $order->id;
                    $phone = '';
                    if($order->phone) {
                        $phone_country = $order->phone_country ?? '91';
                        $phone = '+'.$phone_country.'-'.$order->phone;
                    }
                    $segments3 = $request->segment(3);
                    // prd($segments3);
                    if($request->method() == 'POST') {
                        $response = [];
                        $response['success'] = false;
                        $nicknames = [];
                        $nicknames['passengers'] = 'Passengers';
                        $nicknames['reason'] = 'Reason';
                        $nicknames['other_reason'] = 'Other Reason';
                        $rules = [];
                        $rules['passengers'] = 'required';
                        $rules['reason'] = 'required';
                        $rules['other_reason'] = 'required_if:reason,other';
                        $validation_msg['required'] = ':attribute is required.';
                        $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
                        if ($validator->fails()) {
                            return Response::json(array(
                                'success' => false,
                                'errors' => $validator->getMessageBag()->toArray()
                        ), 400); // 400 being the HTTP code for an invalid request.
                        } else {
                            $flight_cancellation_reasons = config('custom.flight_cancellation_reasons');
                            $passengers = $request->passengers??[];
                            $reason = $request->reason??'';
                            $other_reason = $request->other_reason??'';
                            $remarks = '';
                            if($other_reason) {
                                $remarks = $other_reason;
                            } else if($reason) {
                                $remarks = $flight_cancellation_reasons[$reason]??'';
                            }
                            $params = [];
                            $params['remarks'] = $remarks;
                            $params['passengers'] = $passengers;
                            $resp = Order::cancelFlight($order_id,$params);
                            // prd($resp);
                            if($resp['success']) {
                                $response['success'] = true;
                                $response['message'] = $resp['message']??'';;
                            } else {
                                $response['message'] = $resp['message']??'';
                                $response['errors'] = $resp['errors']??[];
                            }
                            return Response::json($response, 200);
                        }
                    }
                    $data = array(
                        'name' =>  $order->name??'',
                        'phone' =>  $phone,
                        'email' =>  $order->email??'',
                    );
                    $data['order_id'] = $order_id;
                    $pendingOrderAmendments = $order->pendingOrderAmendments();

                    $params = [];
                    $params['order'] = $order;
                    if($order->booking_details) {
                        $params['booking_details'] = json_decode($order->booking_details, true);
                    }
                    $params['width_percent'] = '100%';
                    $params['width_px'] = '100%';
                    $params['cancel_flight'] = true;
                    $params['pendingOrderAmendments'] = $pendingOrderAmendments;
                    $data['itineraries'] = view("emails._flight_booking_email", $params)->render();

                    $data['meta_title'] = 'Cancel Flight Ticket - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $data['meta_description'] = 'Cancel Flight Ticket - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

                    return Inertia::render('user/cancel_flight', $data);
                    // return view('user.cancel_flight',$data);
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }


    public function agent_signup_2(Request $request) {
        $data = [];
        $seo_data = [];
        $old_user_id = 0;
        if(Session::has('old_user_id')) {
            $old_user_id = Session::get('old_user_id');
            $method = $request->method();
            if($method == 'POST') {
                $success = false;
                $message = '';
                $redirect_url = '';

                $nicknames = [
                    'company_name' => 'Company Name',
                    'company_brand' => 'Brand/Trade Name',
                    'pan_no' => 'PAN Number',
                    //'pan_image' => 'PAN Card',
                    'gst_no' => 'GST Number',
                    'gst_image' => 'GST',
                    'company_owner_name' => 'Company Owner Name',
                    'agent_logo' => 'Agent Logo',
                    'bookings_per_month' => 'Bookings Per Month',
                    //'no_of_employees' => 'Number of Employees',
                    //'agency_age' => 'Agency Age',
                    //'destinations_sell_most' => 'Destinations',
                    'whatsapp_phone' => 'Whatsapp Number',
                    'source' => 'Source',
                    //'region' => 'Region',
                    'company_profile' => 'Company Profile',
                ];

                $rules = [];
                $ext = "jpg,jpeg,png,gif";
                $rules['company_name'] = 'required|max:100';
                $rules['company_brand'] = 'required|max:100';
                $rules['pan_no'] = 'required|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';
                $rules['pan_image'] = 'nullable|mimes:jpeg,pdf|max:10240';
                $rules['gst_no'] = 'nullable|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/';
                $rules['gst_image'] = 'nullable|mimes:jpeg,pdf|max:10240';
                $rules['company_owner_name'] = 'required|max:100';
                $rules['agent_logo'] = 'nullable|mimes:'.$ext;
                $rules['destinations_sell_most'] = 'nullable|max:100';
                $rules['agency_age'] = 'nullable|max:100';
                $rules['no_of_employees'] = 'nullable|max:100';
                $rules['region'] = 'nullable|max:100';
                $rules['company_profile'] = 'required|max:100';
                $validation_msg = [];
                $validation_msg['required'] = ':Attribute is required.';
                $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
                if($validator->fails()) {
                    return Response::json(array(
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray()
                    ), 400);
                }

                if($old_user_id > 0) {
                    $req_data = [];
                    if($request->hasFile('pan_image')){
                        $imgData = $request->file('pan_image');
                        $path = 'agentuser/';
                        $thumb_path = 'agentuser/thumb/';
                        $storage = Storage::disk('public');

                        $IMG_WIDTH = 800;
                        $IMG_HEIGHT = 800;
                        $THUMB_WIDTH = 400;
                        $THUMB_HEIGHT = 400;
                        $uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
                        if($uploaded_data['success']){
                            $image = $uploaded_data['file_name'];
                        } else {
                            session()->flash("alert-danger","PAN Card could not be added");
                        }
                    }
                    if($request->hasFile('gst_image')){
                        $imgData = $request->file('gst_image');
                        $path = 'agentuser/';
                        $thumb_path = 'agentuser/thumb/';
                        $storage = Storage::disk('public');

                        $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:800;
                        $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:800;
                        $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
                        $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

                        $uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

                        if($uploaded_data['success']){
                            $gst_image = $uploaded_data['file_name'];
                        } else {
                            session()->flash("alert-danger","GST File could not be added");
                        }
                    }
                    if($request->hasFile('agent_logo')){
                        $imgData = $request->file('agent_logo');
                        $path = 'agent_logo/';
                        $thumb_path = 'agent_logo/thumb/';
                        $storage = Storage::disk('public');

                        $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:800;
                        $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:800;
                        $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
                        $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

                        $uploaded_data = CustomHelper::UploadFile($imgData, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

                        if($uploaded_data['success']) {
                            $agent_logo = $uploaded_data['file_name'];
                        } else {
                            session()->flash("alert-danger","Agent Logo could not be added");
                        }
                    }

                    $req_data['user_id'] = $old_user_id;
                    $req_data['company_name'] = $request->company_name ?? '';
                    $req_data['company_brand'] = $request->company_brand ?? '';
                    $req_data['pan_no'] = $request->pan_no ?? '';
                    $req_data['gst_no'] = $request->gst_no ?? '';
                    $req_data['company_owner_name'] = $request->company_owner_name ?? '';
                    $req_data['pan_image'] = $image??NULL;
                    $req_data['gst_image'] = $gst_image??NULL;
                    $req_data['agent_logo'] = $agent_logo??NULL;
                    $req_data['bookings_per_month'] = $request->bookings_per_month??'';
                    $req_data['sales_team_size'] = $request->sales_team_size ?? '';
                    $req_data['source'] = $request->source ?? '';
                    $req_data['website'] = $request->website ?? '';
                    $req_data['whatsapp_phone'] = $request->whatsapp_phone ?? '';
                    $req_data['whatsapp_country_code'] = $request->whatsapp_country_code ??91;
                    $req_data['query'] = $request->queries ?? '';
                    $req_data['destinations_sell_most'] = $request->destinations_sell_most ?? '';
                    $req_data['agency_age'] = $request->agency_age ?? '';
                    $req_data['no_of_employees'] = $request->no_of_employees ?? 0;
                    $req_data['region'] = $request->region ?? '';
                    $req_data['company_profile'] = $request->company_profile ?? '';
                    $isSaved = UserAgentInfo::create($req_data);
                    if(isset($isSaved) && !empty($isSaved)) {
                        $success = true;
                        $redirect_url = route('user.approval');
                    }                
                }

                $response = [];
                $statusCode = 400;
                if($success) {
                    $statusCode = 200;
                    if($redirect_url) {
                        // $redirect_url = str_replace(url('/'), '/', $redirect_url);
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

            $data['source_types'] = config('custom.source_types');
            $data['bookings_every_months'] = config('custom.bookings_per_month');
            $data['total_no_of_employees'] = config('custom.no_of_employees');
            $data['agency_durations'] = config('custom.agency_durations');
            $data['traveler_regions'] = config('custom.traveler_regions');
            $data['PROFILE_URL'] = route('user.profile');

            $seo_data['meta_title'] = 'Agent Signup - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
            $seo_data['meta_description'] = 'Agent Signup - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

            $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
            return Inertia::render('account/agent-signup', $data);
        }else{
            auth()->logout();
            \Session::flush();
            \Session::flash('alert-success','Invalid Request');
            return redirect('/');
        }
    }


/* end of controller */
}
