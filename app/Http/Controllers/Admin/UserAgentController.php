<?php

namespace App\Http\Controllers\Admin;   

use File;
use Image;
use Storage;
use Validator;
use App\Models\Admin;
use App\Models\UserWallet;
use App\Models\Domain;
use App\Models\TableToDomain;
use App\Models\Module;
use App\Models\User;
use App\Models\Country;
use App\Models\Permission;
use App\Models\UserAgentInfo;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\OrderPayments;
use App\Models\Order;
use App\Models\AgentGroup;
use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Hash;

class UserAgentController extends Controller {

    private $limit;
    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 20;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request){
        /*if(CustomHelper::isAdmin()){*/
            $data = [];
            $limit = $this->limit;
            $export_xls = (isset($request->export_xls))?$request->export_xls:'';
            $name = (isset($request->name))?$request->name:'';
            $email = (isset($request->email))?$request->email:'';
            $status = (isset($request->status))?$request->status:1;
            $approved_agent = (isset($request->approved_agent))?$request->approved_agent:'';
            $group_id = (isset($request->group_id))?$request->group_id:'';
            $from = (isset($request->from))?$request->from:'';
            $to = (isset($request->to))?$request->to:'';
            
            $company_name = (isset($request->company_name))?$request->company_name:'';
            $company_brand = (isset($request->company_brand))?$request->company_brand:'';
            $company_owner_name = (isset($request->company_owner_name))?$request->company_owner_name:'';
            $user_type = (isset($request->user_type))?$request->user_type:'';

            $from_date = CustomHelper::DateFormat($from, 'Y-m-d', 'd/m/Y');
            $to_date = CustomHelper::DateFormat($to, 'Y-m-d', 'd/m/Y');

            $user_query = User::with('agentGroup')->orderBy('id', 'desc');

            $page_heading = 'Manage Agents';

            if($user_type){

            $user_query->where($user_type,1);
            }else{

            $user_query->where(function ($query) {
                   $query->where("is_agent",1)->orWhere("is_vendor",1);
               });

            }
            if(!empty($name)){
                $user_query->where('name', 'like', '%'.$name.'%');
            }
            if(!empty($company_name)) {
                $user_query->whereHas('agentInfoSearch',function($q) use($company_name){
                   $q->where('company_name', 'like', '%'.$company_name.'%');
                });
            }
            if(!empty($company_brand)) {
                $user_query->whereHas('agentInfoSearch',function($q) use($company_brand){
                 $q->where('company_brand', 'like', '%'.$company_brand.'%');
             });
            }
            if(!empty($company_owner_name)) {
                $user_query->whereHas('agentInfoSearch',function($q) use($company_owner_name){
                   $q->where('company_owner_name', 'like', '%'.$company_owner_name.'%');
               });
            }
            if(!empty($email)){
                $user_query->where('email', 'like', '%'.$email.'%');
            }
            if( strlen($status) > 0 ){
                $user_query->where('status', $status);
            }
            if( strlen($approved_agent) > 0 ){
                $user_query->where('approved_agent', $approved_agent);
            }
            if($group_id){
                $user_query->where('group_id', $group_id);
            }
            if(!empty($from_date)){
                $user_query->whereRaw('DATE(created_at) >= "'.$from_date.'"');
            }
            if(!empty($to_date)){
                $user_query->whereRaw('DATE(created_at) <= "'.$to_date.'"');
            }
            $users = $user_query->paginate($limit);
            $data['page_heading'] = $page_heading;
            $data['users'] = $users;
            $data['agent_groups'] = AgentGroup::where('status',1)->get();
            if(!empty($export_xls) && ($export_xls == 1 || $export_xls == '1') ){
            //prd($newsletters);
            return $this->exportXls($user_query);
        }

            return view('admin.register_agents.index', $data);
       /* }
        else{
            return redirect($this->ADMIN_ROUTE_NAME);
        }*/
    }

    public function add(Request $request) {    
        $data = [];
        $users_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = (isset($request->id))?$request->id:0;
        $user = '';

        if(is_numeric($id) && $id > 0){
            $user = User::find($id);
            if(empty($user)){
                return redirect($this->ADMIN_ROUTE_NAME.'/register-agents');
            }
        }
        if($request->method() == 'POST' || $request->method() == 'post'){
            $back_url = (isset($request->back_url))?$request->back_url:'';
            if(empty($back_url)){
                $back_url = $this->ADMIN_ROUTE_NAME.'/register-agents';
            }

            $name = (isset($request->name))?$request->name:'';
            $rules = [];
            $validation_msg = [];
            $ext2 = 'jpg,jpeg,pdf';
            $ext = "jpg,jpeg,png,gif";
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            //$rules['company_name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['company_name'] = 'nullable';
            //$rules['company_brand'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['company_brand'] = 'nullable';
            $rules['no_of_employees'] = 'nullable';
            $rules['agency_age'] = 'nullable';
            $rules['destinations_sell_most'] = 'nullable';
            $rules['region'] = 'nullable';
            $rules['company_profile'] = 'nullable';
            $rules['company_owner_name'] = 'nullable|regex:/^[\pL\s\-]+$/u';
            $rules['email'] = 'required|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            $rules['pan_no'] = 'nullable|regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';
            $rules['gst_no'] = 'nullable|regex:/^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([Z]){1}([a-zA-Z0-9]){1}?$/';
            $rules['agent_logo'] = 'nullable|mimes:'.$ext;
              
            //$rules['phone'] = 'required|regex:/^([0-9\s\-\+\(\)]*)$/';
            if(empty($id)){
                $rules['pan_image'] = 'nullable';
             }else{
               $rules['pan_image'] = 'max:10240|mimes:'.$ext2; 
            }
            if(empty($id)){
                $rules['gst_image'] = 'nullable';
             }else{
               $rules['gst_image'] = 'max:10240|mimes:'.$ext2; 
            }

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
            
            if(!empty($request->password)){
                $rules['password'] = 'sometimes|min:6|max:255';
            }
            if(empty($user)){
                $rules['email'] = 'required|max:255|email|unique:users,email';
                $rules['password'] = 'sometimes|min:6|max:255';
            }
            else{
                $rules['email'] = 'required|email|unique:users,email,' . $id.',id';
            }

            $validation_msg['name.required'] = 'The Name field is required.';
            $validation_msg['name.regex'] = 'The Name format is Invalid.';        
            $validation_msg['company_name.required'] = 'The Company Name field is required.';
            //$validation_msg['company_name.regex'] = 'The Company Name format is Invalid.';      
            $validation_msg['company_brand.required'] = 'The Company Brand field is required.';
            //$validation_msg['company_brand.regex'] = 'The Company Brand format is Invalid.';
            $validation_msg['company_owner_name.required'] = 'The Company Owner Name field is required.';
            $validation_msg['company_owner_name.regex'] = 'The Company Owner Name format is Invalid.';
            $validation_msg['email.required'] = 'The Email field is required.';
            $validation_msg['email.regex'] = 'The Email format is Invalid.';
            $validation_msg['phone.required'] = 'The Phone field is required.';
            $validation_msg['phone.regex'] = 'The Phone format is Invalid.';
            $validation_msg['pan_no.required'] = 'The PAN Number field is required.';
            $validation_msg['pan_no.regex'] = 'The PAN Number format is Invalid.'; 

            //$validation_msg['gst_no.required'] = 'The GST Number field is required.';
            $validation_msg['gst_no.regex'] = 'The GST Number format is Invalid.';

            $this->validate($request,$rules,$validation_msg);

            $req_data = [];
            $req_data['phone'] = (!empty($user->phone))?$user->phone:'';
            $req_data['address1'] = (!empty($user->address1))?$user->address1:'';
            // $update_data = $request->except(['id', 'password', '_token', 'back_url']);
            // $update_data['address1'] = (!empty($user->address1))?$user->address1:'';
            $update_data = [];
            $update_data['name'] = (!empty($request->name))?$request->name:'';
            $update_data['email'] = (!empty($request->email))?$request->email:'';
            $update_data['phone'] = (!empty($request->phone))?$request->phone:'';
            $update_data['country_code'] = (!empty($request->country_code))?$request->country_code:'91';
            $update_data['status'] = (isset($request->status))?$request->status:0;
            $UserpasswordUpdate = isset($request->password) ? $request->password : '';
            $update_data['group_id'] = (!empty($request->group_id))?$request->group_id:null;
            $update_data['is_agent'] = isset($request->is_agent) ? $request->is_agent : '';
            $update_data['is_vendor'] = isset($request->is_vendor) ? $request->is_vendor : '';

            if(!empty($UserpasswordUpdate)){
                $update_data['password'] = bcrypt($UserpasswordUpdate);
            }
            // prd($update_data);
            if($id){
                if($request->is_agent == 1){
                    $update_data['is_verified'] = 1;
                }
                $createdUser = User::where('id', $id)->update($update_data);
                $user_id = $id;
            }else{
                $update_data['status'] = 1;
                $update_data['is_verified'] = 1;
                $createdUser = User::Create($update_data);
                $user_id = $createdUser->id;
            }
            // prd($createdUser);

            if($createdUser){
                $agent_data = [];
                $agent_data['company_name'] = (!empty($request->company_name))?$request->company_name:'';
                $agent_data['company_brand'] = (!empty($request->company_brand))?$request->company_brand:'';
                $agent_data['bookings_per_month'] = (!empty($request->bookings_per_month))?$request->bookings_per_month:'';
                $agent_data['no_of_employees'] = (!empty($request->no_of_employees))?$request->no_of_employees:'';
                $agent_data['agency_age'] = (!empty($request->agency_age))?$request->agency_age:'';
                $agent_data['website'] = (!empty($request->website))?$request->website:'';
                $agent_data['destinations_sell_most'] = (!empty($request->destinations_sell_most))?$request->destinations_sell_most:'';
                $agent_data['region'] = (!empty($request->region))?$request->region:'';
                $agent_data['source'] = (!empty($request->source))?$request->source:'';
                $agent_data['whatsapp_phone'] = (!empty($request->whatsapp_phone))?$request->whatsapp_phone:'';
                $agent_data['whatsapp_country_code'] = (!empty($request->whatsapp_country_code))?$request->whatsapp_country_code:'91';
                $agent_data['company_owner_name'] = (!empty($request->company_owner_name))?$request->company_owner_name:'';        
                $agent_data['company_profile'] = (!empty($request->company_profile))?$request->company_profile:'';        
                $agent_data['pan_no'] = (!empty($request->pan_no))?$request->pan_no:'';        
                $agent_data['gst_no'] = (!empty($request->gst_no))?$request->gst_no:'';        
                //$agent_data['pan_image'] = (!empty($request->pan_image))?$request->pan_image:'';        
                $user_agent = UserAgentInfo::where('user_id',$user_id)->first();

                if($user_agent){    
                    $is_updated = UserAgentInfo::where('user_id',$user_id)->update($agent_data);
                }else{
                    $agent_data['user_id'] = $user_id;
                    $is_updated = UserAgentInfo::create($agent_data);
                }

            // prd($agent_data);
            $alert_msg = 'Registered User has been added successfully.';
            if(is_numeric($id) && $id > 0){               
                $alert_msg = 'Registered User has been updated successfully.';
            }

            if ($request->hasFile("pan_image")) {
                $file = $request->file("pan_image");
                $image_result = $this->saveFile($file, $user_id, "pan_image");
                if ($image_result["success"] == false) {
                    session()->flash("alert-danger","PAN Image could not be added");
                }
            }
            if($request->hasFile('gst_image')) {
                $file = $request->file('gst_image');
                $image_result = $this->saveFile($file,$user_id,'gst_image');
                if($image_result['success'] == false){     
                    session()->flash('alert-danger', 'GST Image could not be added');
                }
            }
            if ($request->hasFile("agent_logo")) {
                $file = $request->file("agent_logo");
                $image_result = $this->saveImage($file, $user_id);
                if ($image_result["success"] == false) {
                    session()->flash(
                        "alert-danger",
                        "Agent Logo could not be added"
                    );
                }
            }
            //=============Activity Logs=====================

                $new_data = DB::table('users')->where('id',$user_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $user_id;
                $params['user_id'] = $users_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Customer-Agents';
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
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            // return redirect(url($back_url))->with('alert-success', 'Registered User has been updated successfully.');
        }
    }

        //$modules = Module::orderBy('sort_order', 'asc')->get();
        $user_name = '';
        $page_heading = 'Add Agent';

        if(isset($user->name)){
            $user_name = $user->name;
            $page_heading = 'Update Agent - '.$user_name;
        }

        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['user'] = $user;
        $data['user_name'] = $user_name;
        //$data['modules'] = $modules;
        $data['groups'] = AgentGroup::where('status',1)->get();
        $data['countries'] = Country::where('status',1)->get();
        return view('admin.register_agents.form', $data);

    }

    public function saveFile($file, $id, $type) {
        // pr($file);
        // pr($id);
        // prd($type);
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            /*if($type == 'pan_image' || $type == 'gst_image') {
                $path = 'agentuser/';
            } else {
                $path = 'agent_logo/';
            }
            $thumb_path = 'agent_logo/thumb/';
            $ext='jpg,jpeg,pdf';*/

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

                    $agentQuery = UserAgentInfo::where('user_id',$id)->first();
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

    public function saveImage($file, $id) {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "agent_logo/";
            $thumb_path = "agent_logo/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("AGENT_USER_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("AGENT_USER_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("AGENT_USER_IMG_THUMB_HEIGHT");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("AGENT_USER_IMG_THUMB_WIDTH");

            $IMG_WIDTH = !empty($IMG_WIDTH) ? $IMG_WIDTH : 800;
            $IMG_HEIGHT = !empty($IMG_HEIGHT) ? $IMG_HEIGHT : 800;
            $THUMB_WIDTH = !empty($THUMB_WIDTH) ? $THUMB_WIDTH : 400;
            $THUMB_HEIGHT = !empty($THUMB_HEIGHT) ? $IMG_WIDTH : 400;

            // $uploaded_data = CustomHelper::UploadFile(
            //     $file,
            //     $path,
            //     $ext = "",
            //     '',
            //     '',
            //     $is_thumb = true,
            //     $thumb_path,
            //     '',
            //     ''
            // );

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
                    $agent_info = UserAgentInfo::where('user_id',$id)->first();

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

    public function ajax_delete_image(Request $request) {
        //prd($request->toArray());
        $result["success"] = false;

        $image_id = $request->has("image_id") ? $request->image_id : 0;
        $type = $request->has("type") ? $request->type :"pan_image";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_images($image_id, $type);
            if ($is_img_deleted) {
                $result["success"] = true;
                $result["msg"] =
                '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
            }
        }

        if ($result["success"] == false) {
            $result["msg"] =
            '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function delete_images($id, $type){
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "agentuser/";
        $agentUser = UserAgentInfo::where('user_id',$id)->first();

        $image = isset($agentUser->pan_image) ? $agentUser->pan_image : "";
        $gst_image = isset($agentUser->gst_image) ? $agentUser->gst_image : "";

        if ($type == "pan_image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }
        }
        elseif($type =='gst_image'){
            if(!empty($gst_image) && $storage->exists($path.'thumb/'.$gst_image)){
                $is_deleted = $storage->delete($path.'thumb/'.$gst_image);
            }
            if(!empty($gst_image) && $storage->exists($path.$gst_image)){
                $is_deleted = $storage->delete($path.$gst_image);
            }
        }

        if ($is_deleted) {
            if ($type == "pan_image") {
                $agentUser->pan_image = "";
            }else if ($type == "gst_image") {
                $agentUser->gst_image = "";
            }
            $is_updated = $agentUser->save();
        }
        return $is_updated;
    }

    public function agent_logo_delete(Request $request){
        $response = [];
        $response['success'] = false;
        $message = '';
        $id = (isset($request->id))?$request->id:0;
        $is_deleted = 0;

        if(is_numeric($id) && $id > 0){
            $user_agent = UserAgentInfo::where('user_id',$id)->first();
            if(!empty($user_agent)){
                $storage = Storage::disk('public');
                $path = 'agent_logo/';
                $thumb_path = 'agent_logo/thumb/';
                $agent_logo = $user_agent->agent_logo;

                if(!empty($agent_logo) && $storage->exists($path.$agent_logo)){
                    $is_deleted = $storage->delete($path.$agent_logo);
                }
                if(!empty($agent_logo) && $storage->exists($thumb_path.$agent_logo)){
                    $is_deleted = $storage->delete($thumb_path.$agent_logo);
                }
                if($is_deleted){
                    $user_agent->agent_logo = '';
                    $user_agent->save();
                }
            }
            if($is_deleted){

                $response['success'] = true;

                $message = '<div class="alert alert-success alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Agent Logo has been deleted succesfully. </div';
            }
            else{
                $message = '<div class="alert alert-danger alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Something went wrong, please try again... </div';
            }
            $response['message'] = $message;

            return response()->json($response);
        }
    }

    public function view(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user = "";
        $title = "Registered User Details";

        if (is_numeric($id) && $id > 0) {
            $user = User::where("id", $id)->first();
            $title = "Registered User Details (".$user->name.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["user"] = $user;
        $data["id"] = $id;
        return view("admin.register_agents.view", $data);
    }

    public function ajax_agent_list(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $query = User::with('agentInfo')->where('is_agent',1)->where('status',1)->where('approved_agent',1);
            if($request->term) {
                $search_text = addslashes($request->term);
                $query->where(function($q1) use($search_text) {
                    $q1->where('name','like','%'.$search_text.'%');
                    $q1->orWhere('email','like','%'.$search_text.'%');
                });
                $query->orWhereHas('agentInfo',function($q1) use($search_text) {
                    $q1->where('company_brand','like','%'.$search_text.'%');
                    $q1->orWhere('company_name','like','%'.$search_text.'%');
                    $q1->orWhere('company_owner_name','like','%'.$search_text.'%');
                });
            }
            if($request->type) {
                $type = $request->type;
                if($type=='offline_flight') {
                    $query->whereHas('agentInfo',function($q1) {
                        $q1->where('is_allowed_offline_flight_inventory',1);
                    });
                }
            }
            $result = $query->orderBy('name', 'asc')->limit(15)->get();
            $items_arr = [];
            if($result) {
                foreach($result as $row) {
                    $company_brand = $row->agentInfo->company_brand??'';
                    $company_name = $row->agentInfo->company_name??'';
                    $company_owner_name = $row->agentInfo->company_owner_name??'';
                    $items_arr[] = [
                        'id' => $row->id,
                        'text' => $company_brand.' / '.$company_name.' ('.$company_owner_name.')'
                    ];
                }
            }
            $response['success'] = true;
            $response['items'] = $items_arr;
            return response()->json($response);
        }
    }

    public function delete(Request $request){

        $id = isset($request->id)?$request->id:'';
        $last_data = '';
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
                $is_deleted = $user_query->delete();
            }

        }
       
        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'Customer-Agents';
        $params['module_desc'] = $module_desc;
        $params['module_id'] = $id;
        $params['sub_module'] = "";
        $params['sub_module_id'] = 0;
        $params['sub_sub_module'] = "";
        $params['sub_sub_module_id'] = 0;
        $params['data_after_action'] = $last_data;
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
//================

    public function groups(Request $request){

            $data = [];
            $limit = $this->limit;

            $export_xls = (isset($request->export_xls))?$request->export_xls:'';
          
            $name = (isset($request->name))?$request->name:'';
            $status = (isset($request->status))?$request->status:1;
         
            $groups_query = AgentGroup::orderBy('id', 'desc');

            $page_heading = 'Manage Agent Group';

            if($name){
               $groups_query->where('name','like', '%'.$name.'%');
            }  
            
            $data['page_heading'] = $page_heading;
            $groups_query->where('status', $status);
            $groups = $groups_query->paginate($limit);
            $data['groups'] = $groups;

            // prd($groups);

            return view('admin.register_agents.groups', $data);
    }


  public function addgroups(Request $request){
        
      /*  if(CustomHelper::isAdmin()){*/
        $data = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = (isset($request->id))?$request->id:0;
        $user = '';

        if(is_numeric($id) && $id > 0){
            $user = AgentGroup::find($id);
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
            $description = (isset($request->description))?$request->description:'';
            $status = (isset($request->status))?$request->status:'';
           
            $rules = [];
            $validation_msg = [];
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
          
            $validation_msg['name.required'] = 'The Name field is required.';
            $validation_msg['name.regex'] = 'The Name format is Invalid.';
        
            $this->validate($request,$rules,$validation_msg);

            $req_data = [];
            $req_data['name'] = (!empty($name))?$name:'';
            $req_data['status'] = (!empty($status))?$status:'';
            $req_data['description'] = (!empty($description))?$description:'';

                // prd($update_data);

                if($id){
                    $createdUser = AgentGroup::where('id', $id)->update($req_data);
                    $agent_groups_id = $id;
                }else{
                    $createdUser = AgentGroup::Create($req_data);
                    $agent_groups_id = $createdUser->id;
                }

                if($createdUser){
                    //prd($createdUser);
                    $alert_msg = 'Agent group has been added successfully.';
                    if(is_numeric($id) && $id > 0){               
                        $alert_msg = 'Agent group has been updated successfully.';
                    }

                //=============Activity Logs=====================

                    $new_data = DB::table('agent_groups')->where('id',$agent_groups_id)->first();
                    $module_desc =  !empty($new_data->name)?$new_data->name:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);

                    $module_id= $agent_groups_id;

                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Customer-Agent-Groups';
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
        $page_heading = 'Add Agent Group';

        if(isset($user->name)){
            $user_name = $user->name;
            $page_heading = 'Update User - '.$user_name;
        }

        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['user'] = $user;
        $data['user_name'] = $user_name;
        //$data['modules'] = $modules;

        return view('admin.register_agents.addgroup', $data);
      /*  }
        else{
            return redirect($this->ADMIN_ROUTE_NAME);
        }*/

    }


public function changeGroupDefault(Request $request)
{

    $defailt_data = array('is_default' => 0,);
    $is_updated = AgentGroup::where('id','>',0)->update($defailt_data);
    $agent_group = AgentGroup::find($request->id);
    $agent_group->is_default = $request->is_default;
    $agent_group->save();

    return response()->json([
        "success" => "Agent Group default successfully.",
    ]);
}

public function updateApproved(Request $request)
{
    // $defailt_data = array('approved_agent' => 0,);
    // $is_updated = AgentGroup::where('id','>',0)->update($defailt_data);
    $user = User::find($request->id);
    $user->approved_agent = $request->approved_agent;
    $isSaved = $user->save();

    //===Agent Approved Email Send 
    if($user->approved_agent == 1){

        $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
        $storage = Storage::disk("public");
        $path = "settings/";
        //$FRONTEND_LOGO = isset($user->FRONTEND_LOGO) ? $user->FRONTEND_LOGO:'';
        $logoSrc =asset(config('custom.assets').'/images/logo.png');
        if(isset($FRONTEND_LOGO) && !empty($FRONTEND_LOGO)){
            if($storage->exists($path.$FRONTEND_LOGO)){
                $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
            }
        }

        $phone = '';
        if($user->phone) {
            $country_code = $user->country_code ?? '91';
            $phone = '+'.$country_code.'-'.$user->phone;
        }

        // AGENT LOGO
        /*$userAgentInfo = ''; $agentLogo = '';
        $is_agent = isset($user->is_agent) ? $user->is_agent : 0;
        if($is_agent && $is_agent > 0){
            $userAgentInfo = $user->agentInfo ?? '';
            if($userAgentInfo && $userAgentInfo->count() > 0){
                $path = 'agent_logo/';
                $agentLogo = $user->agentInfo->agent_logo ?? '';
            }

            $phone = '';
            $agent_email = '';
            if($user->phone) {
                $country_code = $user->country_code ?? '91';
                $phone = '+'.$country_code.'-'.$user->phone;
            }
            $agent_email = !empty($user->email)?$user->email:'';
            if(!empty($agentLogo)){
                if($storage->exists($path.$agentLogo)){
                    $logoSrc = asset('/storage/'.$path.$agentLogo);
                }
            }
        }*/
        $common_logo = '';
        /*if($is_agent == 1){
            $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
            $b2b_logo_params = array(
                '{agent_logo}' => $logoSrc
            );
            $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
        }else{*/
            $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
            $b2c_logo_params = array(
             '{company_logo}' => $logoSrc
         );
            $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
        //}

        $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
        $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
        $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
        $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

        $sales_phone = CustomHelper::getPhoneHref($company_phone);
        $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
        $sales_email = CustomHelper::getEmailHref($company_email);


        $contact_details = '';
        /*if($is_agent == 1){
            $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
            $b2b_email_params = array(
                '{agent_phone}' => $phone??'',
                '{agent_email}' => $agent_email??''
            );
            $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);

        }else{*/

            $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
            $b2c_email_params = array(
                '{company_name}' => $company_name,
                '{sales_mobile}' => $sales_mobile,
                '{sales_phone}' => $sales_phone,
                '{sales_email}' => $sales_email,
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
        //}

        $email_params = array(
           '{header}' => $common_logo,
           '{contact_details}' => $contact_details??'',
           '{agent_name}' => $user->name??'',
           '{agent_email}' => $user->email??'',
           '{agent_phone}' => $phone??'',
       );
        $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
        $email_template = EmailTemplate::where('slug', 'agent-approved')->where('status', 1)->first();
        $email_content = isset($email_template->content) ? $email_template->content : '';
        // $email_params = isset($email_params) ? $email_params : [];

        $email_content = strtr($email_content, $email_params);
        $email_subject = isset($email_template->subject) ? $email_template->subject : '';
        $email_subject = strtr($email_subject, $email_params);
        $email_bcc_admin = isset($email_template->bcc_admin) ? $email_template->bcc_admin : 0;
        $email_type = isset($email_template->email_type) ? $email_template->email_type : '';
        $to_email = $user->email;
        $bcc_email = '';
        if($email_type == 'admin'){
            $to_email = $ADMIN_EMAIL;
        }
        if($email_bcc_admin == 1){
            $bcc_email = $ADMIN_EMAIL;
        }
        $REPLYTO = $ADMIN_EMAIL;
        $cc_email = '';
        $params = [];
        $params['to'] = $to_email;
        $params['cc'] = $cc_email;
        $params['bcc'] = $bcc_email;
        $params['reply_to'] = $to_email;
        $params['subject'] = $email_subject;
        $params['email_content'] = $email_content;

        $isSent = CustomHelper::sendCommonMail($params);
    }
    return response()->json([
        "success" => "Agent Group default successfully.",
    ]);
}

    public function walletList(Request $request) {
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

        return view("admin.register_agents.wallet", $data);
    }

    public function transactionAdd(Request $request) {
        if($request->id) {
           $id = $request->id ?? '';
           $method = $request->method();
           $getUser = User::find($id);

           $phone_no = '';
            if($getUser->phone) {
                $country_code = $getUser->country_code ?? '91';
                $phone_no = '+'.$country_code.'-'.$getUser->phone;
            }

           if($method == "POST"){
            $type = isset($request->type) ? $request->type : '';
            
            $rules = [];
            $rules['type'] = 'required';
            $rules['amount'] = 'required|numeric|min:1';
            // $rules['comment'] = 'required';
            // $rules['for_date'] = 'required';
            if($type == 'credit'){
               $rules['payment_method'] = 'required';
           }
            $this->validate($request, $rules);

            $old_balance = UserWallet::where('user_id',$id)->sum('amount');
            $order_no = isset($request->order_no) ? $request->order_no : '';
            $amount = isset($request->amount) ? $request->amount : 0;
            $comment = isset($request->comment) ? $request->comment : '';
            
            $for_date = isset($request->for_date) ?  CustomHelper::DateFormat($request->for_date,'Y-m-d','d/m/Y').' '.date("H:i:s") : date('Y-m-d H:i:s');

            $payment_method = isset($request->payment_method) ? $request->payment_method : 'wallet';
            
            $payment_status = 1;
            if($type == 'credit'){
                $balance = $old_balance + $amount;
                $payment_status = 1;
            }
            else if($type == 'debit'){
                $balance = $old_balance - $amount;   
                $amount = -$amount;
                $payment_method = 'wallet';
                $payment_status = 4;
            }

            $order_create = false;
            if(empty($order_no)){
                $txn_id =  'TX'.CustomHelper::genrateOrderNoId();

                    $order_create = true;
            }else{
                $txn_id = $order_no;    
                $is_exist = Order::where('order_no' ,$order_no)->first();

                if(empty($is_exist)){

                    $msg = "Order No not match,Please check";
                    return back()->withInput()->with("alert-danger",$msg);
                }

                $order_id = $is_exist->id;
                $order_no = $is_exist->order_no;
            }

            $user_id = isset($request->user_id) ? $request->user_id : 0;
            $req_data = array(
                'user_id' => isset($request->user_id) ? $request->user_id : 0,
                'type' => $type ?? null,
                'amount' => $amount,
                'balance' => $balance??0,
                'comment' => $comment,
                'for_date' => $for_date,
                'txn_id' => $txn_id,
                'payment_method' => $payment_method,
             );

            $isSaved = UserWallet::create($req_data);
            $msg = "Transaction has been added successfully.";

            if($isSaved){

                $user_data = User::find($user_id);
                $total_amount = isset($request->amount) ? $request->amount : 0;

                if($order_create){
                $name  =  $user_data->name ;
                $email  =  $user_data->email ;
                $phone  =  $user_data->phone ;
                $country_code  =  $user_data->country_code ;
                $address1  =  $user_data->address1 ;
                $address2  =  $user_data->address2 ;
                $city  =  $user_data->city ;
                $state  =  $user_data->state ;
                $country  =  $user_data->country ;
                $zip_code  =  $user_data->zip_code ;

                    $order_no = $txn_id;
                    $order = new Order;
                    $order->order_no = $order_no;
                    $order->user_id = $user_id;
                    // $order->name = $name;
                    // $order->email = $email;
                    // $order->phone = $phone;
                    $order->payment_status = $payment_status;
                    $order->payment_description = $comment;
                    $order->payment_response = NULL;
                    $order->sub_total_amount = $total_amount??0;
                    $order->order_type = 7;

                    $order->total_amount = $total_amount??0;
                    $isSaved = $order->save();


                    $order_id = $order->id;
                    $order_no = $order->order_no;
                }

                    $order_payments_data = array(
                        'user_id' => isset($request->user_id) ? $request->user_id : 0,
                        'amount' => $total_amount,
                        'payment_method' => $payment_method,
                        'order_id' => $order_id,
                        'order_no' => $order_no,
                        'description' => $comment,
                        // 'payment_type' => 'total_price',
                        'pg_payment_status' => $payment_status,
                    );
                    $isCreated = OrderPayments::create($order_payments_data);
                

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
                    $userAgentInfo = '';
                    $userAgentInfo = $getUser->agentInfo ?? '';
    
                    // AGENT LOGO
                   /* $agent_phone = '';
                    $agent_email = '';
                    $userAgentInfo = ''; $agentLogo = '';
                    $is_agent = isset($getUser->is_agent) ? $getUser->is_agent : 0;
                    if($is_agent && $is_agent > 0){
                        $userAgentInfo = $getUser->agentInfo ?? '';
                        if($userAgentInfo && $userAgentInfo->count() > 0){
                            $path = 'agent_logo/';
                            $agentLogo = $userAgentInfo->agent_logo ?? '';

                            if($getUser->phone) {
                                $country_code = $getUser->country_code ?? '91';
                                $agent_phone = '+'.$country_code.'-'.$getUser->phone;
                            }
                            $agent_email = !empty($getUser->email)?$getUser->email:'';
                        }
                        if(!empty($agentLogo)){
                            if($storage->exists($path.$agentLogo)){
                                $logoSrc = asset('/storage/'.$path.$agentLogo);
                            }
                        }
                    }*/

                    $common_logo = '';
                    /*if($is_agent == 1){
                        $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                        $b2b_logo_params = array(
                            '{agent_logo}' => $logoSrc
                        );
                        $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                    }else{*/
                        $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                        $b2c_logo_params = array(
                           '{company_logo}' => $logoSrc
                       );
                        $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                    //}

                    $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                    $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                    $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                    $sales_phone = CustomHelper::getPhoneHref($company_phone);
                    $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                    $sales_email = CustomHelper::getEmailHref($company_email);


                    $contact_details = '';
                    /*if($is_agent == 1){
                        $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                        $b2b_email_params = array(
                            '{agent_phone}' => $agent_phone??'',
                            '{agent_email}' => $agent_email
                        );
                        $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);

                    }else{*/

                        $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                        $b2c_email_params = array(
                            '{company_name}' => $company_name,
                            '{sales_mobile}' => $sales_mobile,
                            '{sales_phone}' => $sales_phone,
                            '{sales_email}' => $sales_email,
                            '{company_title}' => $system_title
                        );
                        $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                    //}
    
                    $email_params = array(
                        '{total_amount}' => isset($amount) ? CustomHelper::getPrice($amount) : '',
                        '{method}' => $payment_method ?? '',
                        '{status}' => 'Confirmed',
                        '{comment}' => $comment ?? '',
                        //'{name}' => $getUser->name ?? '',
                        '{name}' => $userAgentInfo->company_name ?? '',
                        '{header}' => $common_logo,
                        '{contact_details}' => $contact_details??'',
                        '{date}' => date("l jS \of F Y h:i A"),
                    );
                    
                    $sms_params = array(
                        '{#var1#}' => CustomHelper::getPrice($amount)??0,
                        '{#var2#}' => date("d-M-Y h:i A"),
                        '{#var3#}' => CustomHelper::getPrice($balance)??0,
                    );
    
                    $sms_content = '';
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
            $data['getUser'] = $getUser;
            $data['id'] = $id;
            return view("admin.register_agents.transaction_form", $data);
          } 
    }

    public function ajax_offline_flight_inventory(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $created_by = auth()->user()->id;
        $created_by_name = auth()->user()->name;

        $user_id = $request->user_id??0;
        $checked = $request->checked??0;
        if($user_id) {
            if($request->checked=='true') {
                $new_val = 1;
            } else {
                $new_val = 0;
            }
            $req_data = [
                'is_allowed_offline_flight_inventory' => $new_val
            ];
            $agentInfo = UserAgentInfo::where('user_id',$user_id)->first();
            if($agentInfo) {
                $isSaved = UserAgentInfo::where('user_id',$user_id)->update($req_data);
                if($isSaved) {
                    $response['success'] = true;
                    $response['message'] = 'Data updated successfully.';
                }
            } else {
                $req_data['user_id'] = $user_id;
                $isSaved = UserAgentInfo::create($req_data);
                if($isSaved) {
                    $response['success'] = true;
                    $response['message'] = 'Data added successfully.';
                }
            }
            if($response['success']) {
                $new_data = DB::table('user_agent_info')->where('user_id',$user_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $user_id;

                $params['user_id'] = $created_by;
                $params['user_name'] = $created_by_name;
                $params['module'] = 'Customer-Agent-Groups';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = "Update";
                CustomHelper::RecordActivityLog($params);
            }
        }
        return response()->json($response);
    }

//============
/* End of controller */
}