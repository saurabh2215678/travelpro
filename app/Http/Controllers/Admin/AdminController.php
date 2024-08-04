<?php

namespace App\Http\Controllers\Admin;   

use App\Models\Admin;
use App\Models\Role;
use App\Models\Domain;
use App\Models\TableToDomain;
use App\Models\Module;
use App\Models\Permission;
use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Hash;
use Auth;
use File;
use Image;
use Storage;
use Validator;

class AdminController extends Controller {

    private $limit;
    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 20;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function logout(Request $request) {
        //===============Login History=============================

        $user = auth::guard('admin')->user();
        $user_id = isset($user->id)?$user->id:'';
        $user_name = isset($user->name)?$user->name:'';
        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['action'] = 'Logout';
        
        CustomHelper::RecordLoginHistory($params);

        //===============Login History===============================
        auth()->guard('admin')->logout();
        $request->session()->invalidate();

        return redirect($this->ADMIN_ROUTE_NAME);
    }

    public function index(Request $request){
      /*  if(CustomHelper::isAdmin()){*/
        $data = [];
        $limit = $this->limit;
        $name = (isset($request->name))?$request->name:'';
        $email = (isset($request->email))?$request->email:'';
        $role_id = (isset($request->role_id))?$request->role_id:'';
        $status = (isset($request->status))?$request->status:1;
        $from = (isset($request->from))?$request->from:'';
        $to = (isset($request->to))?$request->to:'';
        $from_date = CustomHelper::DateFormat($from, 'Y-m-d', 'd/m/Y');
        $to_date = CustomHelper::DateFormat($to, 'Y-m-d', 'd/m/Y');

        $segment = $request->segment(2);
        $is_vendor = 0;
        if(!empty($segment) && $segment == 'vendors'){
            $is_vendor = 1;
        }

            //$user_query = Admin::where('role_id',2)->orderBy('id', 'desc');
        $user_query = Admin::with('roles')->orderBy('id', 'desc');

        if(!empty($name)){
            $user_query->where('name', 'like', '%'.$name.'%');
        }
        if($is_vendor == 1){
            $user_query->where('is_vendor',1);
        }else{
            $user_query->whereNull('is_vendor');
        }
        if(!empty($email)){
            $user_query->where('email', 'like', '%'.$email.'%');
        }
        if( strlen($role_id) > 0 ){
            $user_query->where('role_id', $role_id);
        }
        $user_query->where('status', $status);

        if(!empty($from_date)){
            $user_query->whereRaw('DATE(created_at) >= "'.$from_date.'"');
        }
        if(!empty($to_date)){
            $user_query->whereRaw('DATE(created_at) <= "'.$to_date.'"');
        }
        $users = $user_query->paginate($limit);
        $roles = Role::where('id','!=',1)->orderBy('name','asc')->get();
        $data['roles'] = $roles;
        $data['segment'] = $segment;
        $data['users'] = $users;
        return view('admin.admins.index', $data);
    }

    public function add(Request $request){
        
      /*  if(CustomHelper::isAdmin()){*/
        $data = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = (isset($request->id))?$request->id:0;

        $user = '';
        if(is_numeric($id) && $id > 0){
            $user = Admin::find($id);
            if(empty($user)){
                return redirect($this->ADMIN_ROUTE_NAME.'/users');
            }
        }

        $segment = $request->segment(2);
        $is_vendor = 0;
        $module = 'User';
        if(!empty($segment) && $segment == 'vendors'){
            $is_vendor = 1;
            $module = 'Vendor';
        }

        if($request->method() == 'POST' || $request->method() == 'post'){

            $back_url = (isset($request->back_url))?$request->back_url:'';
            if(empty($back_url)){
                $back_url = $this->ADMIN_ROUTE_NAME.'/users';
            }
            $name = (isset($request->name))?$request->name:'';
            $rules = [];
            $validation_msg = [];
            $ext = "jpg,jpeg,png,gif";
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['email'] = 'required|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            $rules['phone'] = 'required|digits:10';

            if($segment != 'vendors'){
                $rules['role_id'] = 'required';
            }
            $rules['vendor_company_name'] = 'nullable';
            $rules['vendor_logo'] = 'nullable|mimes:'.$ext;

            $validation_msg['name.required'] = 'The Name field is required.';
            $validation_msg['name.regex'] = 'The Name format is Invalid.';
            $validation_msg['email.required'] = 'The Email field is required.';
            $validation_msg['email.regex'] = 'The Email format is Invalid.';
            $validation_msg['phone.required'] = 'The Phone field is required.';
            $validation_msg['phone.regex'] = 'The Phone format is Invalid.';
            $validation_msg['role_id.required'] = 'The Role field is required.';

            if(!empty($request->password)){
                $rules['password'] = 'sometimes|min:6|max:255';
            }
            if(empty($user) && empty($id)){
                $rules['email'] = 'required|max:255|email|unique:admins';
                $rules['password'] = 'sometimes|min:6|max:255';
            }
            else{
                $rules['email'] = 'required|email|unique:admins,email,' . $id.',id';
            }
            //prd($rules);
            $this->validate($request, $rules,$validation_msg);
            $req_data = [];
            $req_data['phone'] = (!empty($user->phone))?$user->phone:'';
            $req_data['address'] = (!empty($user->address))?$user->address:'';
            $req_data['role_id'] = (!empty($user->role_id))?$user->role_id:'';
            $req_data['vendor_company_name'] = (!empty($user->vendor_company_name))?$user->vendor_company_name:'';

            if($is_vendor == 1){

                $roleRow = Role::select('id','name')->where('name','Vendor')->first();
                $role_id = $roleRow ? $roleRow->id :'';
                if($role_id){
                   $req_data['role_id'] = $role_id;
               }
           }

            $req_data = $request->except(['id', 'name', 'email', 'password']);
            $createdUser = $this->save($request, $id);
            $req_data['is_vendor'] = $is_vendor;

            if ($createdUser){
                $alert_msg = $module.' '.'has been added successfully.';
                
                if(is_numeric($id) && $id > 0){
                    
                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'admins';
                $row_id = $id;
                $action_type = 'Edit User';
                $action_description = 'Edit ('.$request->name.")";
                $description = 'Update('.$request->name.") ".$description;*/
                $alert_msg = $module.' '.'has been updated successfully.';
                }

                if($request->hasFile("vendor_logo")) {
                    $file = $request->file("vendor_logo");
                    $image_result = $this->saveImage($file, $createdUser);
                    if ($image_result["success"] == false) {
                        session()->flash("alert-danger","Vendor Logo could not be added"
                    );
                    }
                }

                /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

                //=============Activity Logs=====================

                $new_data = DB::table('admins')->where('id',$createdUser)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id= $createdUser;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = $module;
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
            }
        }

        //$modules = Module::orderBy('sort_order', 'asc')->get();
      # prd( $modules ); 
        $user_name = '';
        $page_heading = 'Add User';
        if($segment == 'vendors'){
            $page_heading = 'Add Vendor';
        }

        if(isset($user->name)){
            $user_name = $user->name;
            $page_heading = 'Update User - '.$user_name;
            if($segment == 'vendors'){
             $page_heading = 'Update Vendor - '.$user_name;
            }
        }
        $roles = Role::where('id','!=',1)->orderBy('name','asc')->get();
        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['roles'] = $roles;
        $data['user'] = $user;
        $data['user_name'] = $user_name;
        //$data['modules'] = $modules;
        $data['segment'] = $segment;
        return view('admin.admins.form', $data);
        /*}
        else{
            return redirect($this->ADMIN_ROUTE_NAME);
        }*/

    }
    public function saveImage($file, $id) {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "vendor_logo/";
            $thumb_path = "vendor_logo/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("VENDOR_USER_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("VENDOR_USER_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("VENDOR_USER_IMG_THUMB_HEIGHT");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("VENDOR_USER_IMG_THUMB_WIDTH");

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
                    $vendor_info = Admin::where('id',$id)->first();

                    if (!empty($vendor_info) && $vendor_info->count() > 0) {
                        $storage = Storage::disk("public");
                        $old_image = $vendor_info->vendor_logo;
                        $vendor_info->vendor_logo = $new_image;
                        $isUpdated = $vendor_info->save();

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
    public function vendor_logo_delete(Request $request){
        $response = [];
        $response['success'] = false;
        $message = '';
        $id = (isset($request->id))?$request->id:0;
        $is_deleted = 0;

        if(is_numeric($id) && $id > 0){
            $user_vendor = Admin::where('id',$id)->first();
            if(!empty($user_vendor)){
                $storage = Storage::disk('public');
                $path = 'vendor_logo/';
                $thumb_path = 'vendor_logo/thumb/';
                $vendor_logo = $user_vendor->vendor_logo;

                if(!empty($vendor_logo) && $storage->exists($path.$vendor_logo)){
                    $is_deleted = $storage->delete($path.$vendor_logo);
                }
                if(!empty($vendor_logo) && $storage->exists($thumb_path.$vendor_logo)){
                    $is_deleted = $storage->delete($thumb_path.$vendor_logo);
                }
                if($is_deleted){
                    $user_vendor->vendor_logo = '';
                    $user_vendor->save();
                }
            }
            if($is_deleted){

                $response['success'] = true;

                $message = '<div class="alert alert-success alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Vendor Logo has been deleted succesfully. </div';
            }
            else{
                $message = '<div class="alert alert-danger alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Something went wrong, please try again... </div';
            }
            $response['message'] = $message;

            return response()->json($response);
        }
    }

    public function delete(Request $request){

        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;
        $url = "";
        $module = "";

        if($method == 'POST'){
        $id = isset($request->id) ? $request->id:"";
            if(is_numeric($id) && $id > 0){
                $user_query = Admin::find($id);
                $is_vendor = $user_query->is_vendor??0;
                $url = "users";
                $module = "User";
                if($is_vendor == 1){
                 $module = "Vendor";
                 $url = "vendors";
                }

                $last_data = DB::table('admins')->where('id',$id)->first();
                $module_desc =  !empty($last_data->name)?$last_data->name:'';
                $last_data =(array) $last_data;
                $last_data = json_encode($last_data);
                /*$function_name = $this->currentUrl;
                $action_table = "admins";
                $row_id = $id;
                $action_type = "Delete User";
                $action_description = "Delete(" . $request->name . ")";
                $description = "Delete(" . $request->name . ")";*/
                $is_delete = Admin::adminuserPermissionDelete($id);
                if ($is_delete['status'] != 'ok') {

                return redirect(url($this->ADMIN_ROUTE_NAME. "/".$url))->with('alert-danger', $is_delete['message']);
                } 
                else {
                $delete_user_name = $is_delete['name'];
                $is_deleted = true;

                    // if($is_deleted != null){
                    // return redirect(
                    // url($this->ADMIN_ROUTE_NAME . "/accommodations/category")
                    // )->with(
                    // "alert-danger",
                    // "You cannot delete ".$delete_category_name
                    // );
                    // }
                }
                //$is_deleted = $user_query->delete();
            }

        }

       /* CustomHelper::recordActionLog(
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
        $params['module'] = $module;
        $params['module_desc'] = $module_desc??'';
        $params['module_id'] = $id??0;
        $params['sub_module'] = "";
        $params['sub_module_id'] = 0;
        $params['sub_sub_module'] = "";
        $params['sub_sub_module_id'] = 0;
        $params['data_after_action'] = $last_data??'';
        $params['action'] = "Delete";

        CustomHelper::RecordActivityLog($params);

        return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url))->with("alert-success","The ".$module." has been deleted successfully."
        );

    }

    public function save(Request $request, $id=0){



        $data = $request->except(['_token', 'back_url','permission','password']);
        $data['role_id'] = $request->role_id;

        if(!empty($request->password)){
            $data['password'] = bcrypt($request->password);
        }
        $segment = $request->segment(2);
        $is_vendor = 0;
        if(!empty($segment) && $segment == 'vendors'){
            $is_vendor = 1;
        }
        if($is_vendor == 1){

            $data['is_vendor'] = $is_vendor;
            $roleRow = Role::select('id','name')->where('name','Vendor')->first();
            $role_id = $roleRow ? $roleRow->id :'';
            if($role_id){
               $data['role_id'] = $role_id;
           }

       }

        // prd($data);
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
        //prd($admin);

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


    public function media_gallery(Request $request) {
        $data = [];
        $data['title'] = 'Media Gallery';
        $data['heading'] = 'Media Gallery';
        return view('admin.media-gallery', $data);
    }

    /* End of controller */
}