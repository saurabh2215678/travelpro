<?php
namespace App\Http\Controllers\Admin;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use DB;
use Validator;

class CouponController extends Controller{

    private $limit;
    public function __construct(){
        $this->limit = 100;
    }

    public function index(Request $request){
        $data = [];
        $limit = $this->limit;
        $modules = (isset($request->modules))?$request->modules:'';
        $name = (isset($request->name))?$request->name:'';
        $code = (isset($request->code))?$request->code:'';
        $status = (isset($request->status))?$request->status:'';
        $start = (isset($request->start))?$request->start:'';
        $expiry = (isset($request->expiry))?$request->expiry:'';
        $start_date = CustomHelper::DateFormat($start, 'Y-m-d', 'd M Y');
        $expiry_date = CustomHelper::DateFormat($expiry, 'Y-m-d', 'd M Y');


        $coupon_query = Coupon::orderBy('created_at', 'desc');

   
        if($request->modules) {
            if(is_array($request->modules)) {
                $module_ids = $request->modules;
            } else {
                $module_ids = explode(',', $request->modules);
            }
            if(!empty($module_ids)) {
                $coupon_query->where(function($q1) use($module_ids) {
                    //foreach($module_ids as $moduleId) {
                        $q1->orWhereJsonContains('modules', $module_ids);
                    //}
                });
            }
        }


        if(!empty($name)){
            $coupon_query->where('name','like', '%'.$name.'%');
        }
        if(!empty($code)){
            $coupon_query->where('code','like', '%'.$code.'%');
        }
        if( strlen($status) > 0 ){
            $coupon_query->where('status', $status);
        }
        if(!empty($start_date)){
            $coupon_query->whereRaw('DATE(start_date) >= "'.$start_date.'"');
        }
        if(!empty($expiry_date)){
            $coupon_query->whereRaw('DATE(expiry_date) <= "'.$expiry_date.'"');
        }

        $coupons = $coupon_query->paginate($limit);
        $data['coupons'] = $coupons;

        return view('admin.coupons.index', $data);
    }

    public function add(Request $request){
        $data = array();
        if($request->method() == 'POST' || $request->method() == 'post'){
            $is_saved = $this->save($request);
            if($is_saved['status'] > 0){
                return redirect(route('admin.coupons.index'))->with('alert-success', $is_saved['msg']);
            } else {
                return back()->with('alert-danger', 'The coupon cannot be added, please try again or contact the administrator.');
            }

        }
        $data['coupon_id'] = 0;
        $data['title'] = "Add Coupon";
        $data['heading'] = "Add Coupon";
        return view('admin.coupons.form',$data);
    }


    public function edit(Request $request){
        $data = array();
        $coupon_id = ($request->id) ? $request->id : 0;

        $CouponModel = new Coupon;
        $coupons = array();
        if(is_numeric($coupon_id) && $coupon_id > 0){
            $coupons = $CouponModel->where('id', $coupon_id)->first();
        }
        if($request->method() == 'POST' || $request->method() == 'post'){
            $is_saved = $this->save($request);
            if($is_saved['status'] > 0){
                return redirect(route('admin.coupons.index'))->with('alert-success', $is_saved['msg']);
            } else {
                return back()->with('alert-danger', 'The coupon cannot be added, please try again or contact the administrator.');
            }
        }
        $data['coupon_id'] = 0;
        $data['title'] = "Update Coupon";
        $data['coupons'] = $coupons;
        $data['heading'] = "Update Coupon (".$coupons['name'].")";
        return view('admin.coupons.form',$data);
    }
    
    function save($req){
        $data = [];
        $coupon_id = (int)$req->coupon_id;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $type = isset($req->type)?$req->type:'';

        $rules = [];
        $rules['name'] = 'required|min:3';
        if(is_numeric($coupon_id) && $coupon_id > 0){
            $rules['code'] = ['required', Rule::unique('coupons')->ignore($coupon_id)];
        } else {
            $rules['code'] = 'required|min:3|unique:coupons';
        }
        $rules['discount'] = 'required|numeric';
        $rules['min_amount'] = 'required|numeric';
        if($type == 'value'){
            // $rules['max_discount_amt'] = 'required|numeric|max:discount';
            $rules['max_discount_amt'] = 'required|numeric';
        } else {
            $rules['max_discount_amt'] = 'required|numeric';
        }
        $rules['use_limit'] = 'numeric';
        $rules['start_date'] = 'required';
        $rules['expiry_date'] = 'required';

        $validation_msg = [];
        $validation_msg['required'] = ':Attribute is required.';

        $this->validate($req, $rules, $validation_msg);
        $msg_data = array();
        $data = $req->except(['_token', 'coupon_id', 'back_url','start_date','expiry','modules']);
        $start_from_date = CustomHelper::DateFormat($req->start_date, 'Y-m-d', 'd/m/Y');
        $expire_date_formated = CustomHelper::DateFormat($req->expiry_date, 'Y-m-d', 'd/m/Y');
        $data['modules'] = (isset($req->modules) && !empty($req->modules)) ? json_encode($req->modules) : '[]';
        $data['start_date'] = $start_from_date;
        $data['expiry_date'] = $expire_date_formated;

        if(is_numeric($coupon_id) && $coupon_id > 0){
            $savedata = Coupon::where('id', $coupon_id)->update($data);
            $savedata = 1;
            $insertedId = $coupon_id;
            $sccMsg = "Coupon has been updated successfully.";
        } else {
            $savedata = Coupon::create($data);
            $insertedId = $savedata->id;
            $sccMsg = "Coupon has been added successfully.";
        }
        if($savedata){
            //=============Activity Logs=====================
            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $new_data = DB::table('coupons')->where('id',$insertedId)->first();
            $module_desc =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $module_id = $insertedId;
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Coupon';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($coupon_id)) ? "Update" : "Add";
            CustomHelper::RecordActivityLog($params);
            //=============Activity Logs=====================

            $msg_data['status'] = 1;
            $msg_data['msg'] = $sccMsg;
        } else {
            $msg_data['status'] = 0;
            $msg_data['msg'] = "Something went wrong, please try again or contact the administrator.";
        }
        return $msg_data;
    }

    
    public function delete(Request $request)
    {
        $method = $request->method();
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = '';
        if($method == 'POST'){
            $new_data = DB::table('coupons')->where('id',$id)->first();
            $module_desc =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $is_deleted = Coupon::where('id', $id)->delete();
        }

        if($is_deleted)
        {
            //=============Activity Logs=====================
            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Coupon';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';
            CustomHelper::RecordActivityLog($params);
            //=============Activity Logs=====================

            return redirect(route('admin.coupons.index'))->with('alert-success', "Coupon has been deleted successfully.");
        } else {
            return redirect(route('admin.coupons.index'))->with('alert-danger', "Coupon can n't delete. please try again or contact the administrator.");
        }
    }
}