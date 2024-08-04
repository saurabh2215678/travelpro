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
use App\Models\Permission;
use App\Models\AgentGroup;
use App\Models\SeoMetaTag;
use App\Models\Accommodation;
use App\Models\ModuleDiscountCategory;
use App\Models\DiscountModuleToGroup;
use App\Models\CustomModuleRecordDiscount;
use App\Models\Package;
use App\Models\CabRoute;
use App\Models\CabsCities;
use App\Models\BikeCities;
use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Hash;

class ModuleDiscountController extends Controller {

    private $limit;
    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 20;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

//================

    public function category(Request $request){

            $data = [];
            $limit = $this->limit;
            $export_xls = (isset($request->export_xls))?$request->export_xls:'';      
            $name = (isset($request->name))?$request->name:'';
            $status = (isset($request->status))?$request->status:1;
           
            $groups_query = ModuleDiscountCategory::orderBy('module_name', 'asc');
            $page_heading = 'Manage Discount Category';
            if($name){
               $groups_query->where('name','like', '%'.$name.'%');
            }
            $groups_query->where('status',$status);
            $groups = $groups_query->get();
            $data['page_heading'] = $page_heading;
            $data['groups'] = $groups;
            // prd($groups);
            return view('admin.discount.category', $data);
    }

    public function addcategory(Request $request){
        
        $data = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = (isset($request->id))?$request->id:0;
        $back_url = (isset($request->back_url))?$request->back_url:0;
        $user = '';

        if(is_numeric($id) && $id > 0){
            $user = ModuleDiscountCategory::find($id);
            if(empty($user)){
                return redirect($this->ADMIN_ROUTE_NAME.'/discount/category');
            }
        }

        if($request->method() == 'POST' || $request->method() == 'post'){

            $name = (isset($request->name))?$request->name:'';
            $module_name = (isset($request->module_name))?$request->module_name:'';
            $status = (isset($request->status))?$request->status:'';

            $rules = [];
            $validation_msg = [];
            $rules['module_name'] = 'required';
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $validation_msg['name.required'] = 'The Name field is required.';
            $validation_msg['name.regex'] = 'The Name format is Invalid.';

            $this->validate($request,$rules,$validation_msg);

            $req_data = [];
            $req_data['module_name'] = (!empty($module_name))?$module_name:'';
            $req_data['name'] = (!empty($name))?$name:'';
            $req_data['status'] = (!empty($status))?$status:'';

            if($id){
                $createdUser = ModuleDiscountCategory::where('id', $id)->update($req_data);
                $module_discount_category_id = $id;
            }else{
                $createdUser = ModuleDiscountCategory::Create($req_data);
                $module_discount_category_id = $createdUser->id;
            }

            if($createdUser){
                //prd($createdUser);
                $alert_msg = 'Discount category has been added successfully.';
                if(is_numeric($id) && $id > 0){               
                    $alert_msg = 'Discount category has been updated successfully.';
                }

                //=============Activity Logs=====================
                    $new_data = DB::table('module_discount_category')->where('id',$module_discount_category_id)->first();
                    $module_desc =  !empty($new_data->module_name)?$new_data->module_name:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $module_id= $module_discount_category_id;

                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Discount category';
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

       // $modules = Module::orderBy('sort_order', 'asc')->get();
        $user_name = '';
        $page_heading = 'Add Discount category';

        if(isset($user->name)){
            $user_name = $user->name;
            $page_heading = 'Update Discount category - '.$user_name;
        }
        
        $data['modules'] = SeoMetaTag::where('agent_discount',1)->get();
        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['user'] = $user;
        $data['user_name'] = $user_name;
        // $data['modules'] = $modules;

        return view('admin.discount.addcategory', $data);
     
    }

//============

    public function agent_groups(Request $request){

            $data = [];
            $limit = $this->limit;
            $module_name = $request->module_name ?? '';
            $data['module_name'] = $request->module_name ?? '';
            $export_xls = (isset($request->export_xls))?$request->export_xls:'';      
            $name = (isset($request->name))?$request->name:'';
            $email = (isset($request->email))?$request->email:'';
            $status = (isset($request->status))?$request->status:'';
            $from = (isset($request->from))?$request->from:'';
            $to = (isset($request->to))?$request->to:'';
            $from_date = CustomHelper::DateFormat($from, 'Y-m-d', 'd/m/Y');
            $to_date = CustomHelper::DateFormat($to, 'Y-m-d', 'd/m/Y');
            $groups_query = DiscountModuleToGroup::where('module_name',$module_name)->orderBy('id', 'desc');
            $page_heading = 'Manage Discount for Agent Groups';   
            $groups = $groups_query->get();
            $data['module_to_groups'] = $groups;
            $data['modules'] = SeoMetaTag::where('agent_discount',1)->where('module_type','major')->where('is_disable',0)->where('status',1)->get();        
            $discount_groups_query = ModuleDiscountCategory::orderBy('id', 'desc');      
            $discount_groups = $discount_groups_query->get();
            $data['page_heading'] = $page_heading;
            $data['discount_groups'] = $discount_groups;
            $data['agent_groups'] = AgentGroup::where('status',1)->get();
            //$data['discount_module_groups'] = DiscountModuleToGroup::get();
            $data['discount_module_groups'] = DiscountModuleToGroup::where('module_name',$module_name)->get();
            return view('admin.discount.agent_groups', $data);
    }

  public function add_agent_groups(Request $request){

        $data = [];
        $module_name = $request->module_name ?? '';
        $data['module_name'] = $request->module_name ?? '';
        $groups_query = ModuleDiscountCategory::where('module_name',$module_name)->orderBy('id', 'desc');      
        $groups = $groups_query->get();
        $data['discount_groups'] = $groups;

        $data['agent_groups'] = AgentGroup::where('status',1)->get();
        $data['discount_module_groups'] = DiscountModuleToGroup::where('module_name',$module_name)->get();

        // $data['id'] = $id;
        // $data['user'] = $user;
        // $data['user_name'] = $user_name;
        // $data['modules'] = $modules;
        $discount_agent_groups = view('admin.discount.add_agent_groups', $data)->render();

        $response['success'] = true;
        $response['discount_agent_groups'] = $discount_agent_groups;
        // $response['message'] = $message;

        return response()->json($response);
    

  }

    public function add_agent_groups_discount(Request $request) {
        $data = [];
        $module_name = $request->module_name ?? '';
        $module_type_id = $request->group_id ?? '';
        $discount_type = $request->discount_type ?? '';
        $discount_value = $request->discount_value ?? '';
        $is_default = $request->is_default ?? '';
        $agent_group_arr = $request->agent_group ?? '';

        
        $isDeleted = DiscountModuleToGroup::where('module_name',$module_name)->where('module_discount_type_id',$module_type_id)->delete();

        if($is_default ==1){  
            $default_data = array('is_default' => 0,);
            $isupdate = DiscountModuleToGroup::where('module_name',$module_name)->update($default_data);
        }

        $req_data =[];
        foreach($agent_group_arr as $key => $agent_group){
            if($discount_type == 'flat' || ($discount_type == 'percentage' && $discount_value[$key] <= 100)){
                $req_data[] = array(
                    'module_name' => $module_name,
                    'module_discount_type_id' => $module_type_id,
                    'agent_group_id' => $agent_group,
                    'discount_type' => $discount_type,
                    'discount_value' => $discount_value[$key],
                    'is_default' => $is_default, 
                );
            } else {
                $response['discount_value_error'] = true;
            }
        }
        $isSaved = DiscountModuleToGroup::insert($req_data);
        $isDefaultdata = DiscountModuleToGroup::select('is_default')->where('module_name',$module_name)->first();
        // $isDefaultdata = $isDefaultdata->toArray();
        // prd($isDefaultdata);
        // if(in_array(1,$isDefaultdata)) {
        if($isDefaultdata) {
            $params = [];
            $params['module_name'] = $module_name;
            CustomHelper::BulkUpdates($params);
        } else {
            $firstData = DiscountModuleToGroup::where('module_name',$module_name)->orderBy('id','asc')->first();
            $first_id = $firstData->id ?? '';
            $default_data = array('is_default' => 1,);
            $isupdate = DiscountModuleToGroup::where('id',$first_id)->update($default_data);
            $params = [];
            $params['module_name'] = $module_name;
            CustomHelper::BulkUpdates($params);
        }
        $response['success'] = true;
        return response()->json($response);
    }


    public function add_custom_module_record_discount(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $module_name = $request->module_name ?? '';
        $module_record_id = $request->module_record_id ?? '';
        $module_type_id = $request->group_id ?? '';
        $discount_type = $request->discount_type ?? '';
        $discount_value = $request->discount_value ?? '';
        $agent_group_arr = $request->agent_group ?? '';

        $is_updated = 0;
        if($module_name && $module_record_id) {
            if($module_name == 'hotel_listing') {
                $module_record = Accommodation::find($module_record_id);
                $module_record->discount_category_id = '-1';
                $is_updated = $module_record->save();
            } else if($module_name == 'package_listing' || $module_name == 'activity_listing') {
                $module_record = Package::find($module_record_id);
                $module_record->discount_category_id = '-1';
                $is_updated = $module_record->save();
            } else if($module_name == 'cab') {
                if(config('custom.CAB_VERSION')==2) {
                    $module_record = CabsCities::find($module_record_id);
                    $module_record->discount_category_id = '-1';
                    $is_updated = $module_record->save();
                } else {
                    $module_record = CabRoute::find($module_record_id);
                    $module_record->discount_category_id = '-1';
                    $is_updated = $module_record->save();                    
                }
            } else if($module_name == 'rental') {
                $module_record = BikeCities::find($module_record_id);
                $module_record->discount_category_id = '-1';
                $is_updated = $module_record->save();
            }
        }

        if($is_updated) {
            $isDeleted = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->delete();
            $req_data =[];
            foreach($agent_group_arr as $key => $agent_group){
                if($discount_type == 'flat' || ($discount_type == 'percentage' && $discount_value[$key] <= 100)){
                    $req_data[] = array(
                        'module_name' => $module_name,
                        'module_record_id' => $module_record_id,
                        'agent_group_id' => $agent_group,
                        'discount_type' => $discount_type,
                        'discount_value' => $discount_value[$key],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                } else {
                    $response['discount_value_error'] = true;
                }
            }
            $isSaved = CustomModuleRecordDiscount::insert($req_data);
            if($isSaved) {
                if($module_name == 'hotel_listing') {
                    CustomHelper::updateAccommodationPublishPrice($module_record_id);
                } else if($module_name == 'package_listing' || $module_name == 'activity_listing') {
                    CustomHelper::updatePackagePublishPrice($module_record_id);
                } else if($module_name == 'cab') {
                    CustomHelper::updateCabRoutePublishPrice($module_record_id);
                } else if($module_name == 'rental') {
                    CustomHelper::updateRentalPublishPrice($module_record_id);
                }
                $response['success'] = true;
                $response['message'] = 'Discount data saved successfully';
            } else {
                $response['message'] = 'Something went wrong, please try again.';
            }
        } else {
            $response['message'] = 'Something went wrong, please try again.';
        }
        return response()->json($response);
    }

//============
    /* End of controller */
}