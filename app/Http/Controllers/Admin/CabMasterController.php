<?php
namespace App\Http\Controllers\Admin;
use App\Models\CabMaster;
use App\Models\CabRoute;
use App\Models\CabRouteGroup;
use App\Models\CabRouteInventory;
use App\Models\CabRoutePrice;
use App\Models\CabRouteToGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Mail;
use Validator;
use Illuminate\Validation\Rule;
use Storage;
use DB;
use Response;
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\Carbon as Carbon;

class CabMasterController extends Controller {

    protected $currentUrl;
    protected $limit;
    protected $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->limit = 15;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : 1;
        $cabQuery = CabMaster::orderBy("sort_order", "asc");
        if (!empty($name)) {
            $cabQuery->where("name", "like", "%" . $name . "%");
        }
        if (strlen($status) > 0) {
            $cabQuery->where("status", $status);
        }
        $cabs = $cabQuery->paginate($limit);
        $data["cabs"] = $cabs;
        return view("admin.cab_masters.index", $data);
    }

    public function add(Request $request) {

        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $cabQuery = [];
        $title = "Add Cab";

        if (is_numeric($id) && $id > 0) {
            $cabQuery = CabMaster::find($id);
            $title = "Edit Cab(" . $cabQuery->name. " )";
        }

        //prd($request->toArray());

        if ($request->method() == "POST" || $request->method() == "post") {
            $ext = "jpg,jpeg,png,gif";
            $rules = [];
            $rules["name"] = 'required|max:255|regex:/^[\pL\s\-]+$/u';
            $rules["seats"] = "required";
            $rules["status"] = "required";
            $rules["image"] = "nullable|image|mimes:" . $ext;
            $validation_msg = [];
            $validation_msg["name.required"] = "Cab is required.";
            $validation_msg["name.regex"] = "Cab format is Invalid.";
            $validation_msg["seats.required"] = "Seats is required.";

            $this->validate($request, $rules, $validation_msg);
            // $req_data = [];
            // $req_data = $request->except([
            //     "_token",
            //     "old_image",
            //     "icon",
            //     "back_url",
            // ]);
            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['equivalent'] = $request->equivalent??'';
            $req_data['seats'] = $request->seats??0;
            $req_data['status'] = $request->status??'0';
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            if (!empty($cabQuery) && $cabQuery->id == $id) {
                $isSaved = CabMaster::where("id",$cabQuery->id)->update($req_data);
                $cab_master_id = $cabQuery->id;
                $msg = "Cab has been updated successfully.";
            } else {
                $isSaved = CabMaster::create($req_data);
                $cab_master_id = $isSaved->id;
                $msg = "Cab has been added successfully.";
            }


            if ($isSaved) {
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImg($file, $cab_master_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }
                cache()->forget("cab_master");

                $new_data = DB::table('cab_master')->where('id',$cab_master_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $cab_master_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Cab';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME ."/cab_master"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Cab could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["cabQuery"] = $cabQuery;
        $data["id"] = $id;
        return view("admin.cab_masters.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $cabQuery = "";
        $title = "Cab Details";
        if (is_numeric($id) && $id > 0) {
            $cabQuery = CabMaster::where("id", $id)->first();
            $title = "Cab Details (".$cabQuery->name.")";
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["cabQuery"] = $cabQuery;
        $data["id"] = $id;
        return view("admin.cab_masters.view", $data);
    }

    public function saveImg($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "cabs/";
            $thumb_path = "cabs/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "CAB_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "CAB_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "CAB_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "CAB_IMG_THUMB_HEIGHT"
            );

            $IMG_WIDTH = !empty($IMG_WIDTH) ? $IMG_WIDTH : 768;
            $IMG_HEIGHT = !empty($IMG_HEIGHT) ? $IMG_HEIGHT : 768;
            $THUMB_WIDTH = !empty($THUMB_WIDTH) ? $THUMB_WIDTH : 336;
            $THUMB_HEIGHT = !empty($THUMB_HEIGHT) ? $IMG_WIDTH : 336;

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
                    $cabQuery = CabMaster::find($id);

                    if (!empty($cabQuery)) {
                        $storage = Storage::disk("public");

                        $old_image = $cabQuery->image;
                        $cabQuery->image = $new_image;

                        $isUpdated = $cabQuery->save();

                        if ($isUpdated) {
                            if (
                                !empty($old_image) &&
                                $storage->exists($path . $old_image)
                            ) {
                                $storage->delete($path . $old_image);
                            }

                            if (
                                !empty($old_image) &&
                                $storage->exists($thumb_path . $old_image)
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

    public function ajax_delete_image(Request $request) {
        $result["success"] = false;
        $image_id = $request->has("image_id") ? $request->image_id : 0;
        $type = $request->has("type") ? $request->type : "";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_image($image_id, $type);
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

    public function delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "cabs/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $cabQuery = CabMaster::find($id);
                $new_data = DB::table('cab_master')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = CabMaster::cabMasterDelete($id);
                    if ($is_delete['status'] != 'ok') {

                        return redirect(url('admin/cab_master'))->with('alert-danger', $is_delete['message']);

                    } 
                    else {

                        $delete_destination_name = $is_delete['name'];

                        $is_deleted = true;

                    // if(!$is_deleted){
                    // return redirect(
                    // url($this->ADMIN_ROUTE_NAME . "/cab_master")
                    // )->with(
                    // "alert-danger",
                    // "You cannot delete ".$delete_destination_name
                    // );
                    // }
                    }

                if (!empty($cabQuery) && count([$cabQuery]) > 0) {
                    if (
                        !empty($cabQuery->image)
                    ) {
                        $image = $cabQuery->image;
                        if (
                            !empty($image) &&
                            $storage->exists($path . "thumb/" . $image)
                        ) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $image
                            );
                        }
                        if (
                            !empty($image) &&
                            $storage->exists($path . $image)
                        ) {
                            $is_deleted = $storage->delete($path . $image);
                        }
                    }
                    //$is_deleted = $cabQuery->delete();
                }
            }
        }
        if ($is_deleted) {
            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'cabs';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);


            return redirect(url($this->ADMIN_ROUTE_NAME . "/cab_master"))->with("alert-success","Cab has been deleted successfully.");
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/cab_master"))->with("alert-danger","Cab cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function delete_image($id, $type) {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "cabs/";
        $cabQuery = CabMaster::find($id);
        $image = isset($cabQuery->image) ? $cabQuery->image : "";
        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $cabQuery->image = "";
                }
                $is_updated = $cabQuery->save();
            }
        }
        return $is_updated;
    }

    //==============


    public function inventory(Request $request) {
        $data = [];
        $cab_route_group_id = $request->group_id ?? '';
        $start_date = date('Y-m-d');
        $end_date =  date('Y-m-d', strtotime($start_date. ' + 10 days'));
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $pre_date =  date('Y-m-d', strtotime($start_date. ' 0 days'));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $data['pre_date'] = $pre_date;
        $data['last_date'] = $end_date;
        $data['start_date'] = $start_date;
        $data['period'] = $period;
        $data['start_date'] = $start_date;
        $data['route_groups'] = CabRouteGroup::where('status',1)->orderBy('id','desc')->get();
        $groups_query = CabRouteGroup::where('status',1)->orderBy('id','desc');
        if($cab_route_group_id){
            $groups_query->where('id',$cab_route_group_id);
        }

        $group_data = $groups_query->first();
        $data['group_data'] = $group_data;
        $cab_route_group_id = $group_data->id ?? '';
        $data['cab_route_group_id'] = $cab_route_group_id ?? '';
        $whereDate = [];
        foreach($period as $dt){
            $date =  $dt->format("d-m-Y");
            $whereDate[] =  $dt->format("Y-m-d");
        }
        $CabRouteInventory = CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->whereIn('trip_date',$whereDate)->get();
        $data['CabRouteInventories'] = $CabRouteInventory;
        $data["page_heading"] = "Cab Route Group Inventory";
        return view("admin.cab_masters.inventory", $data);
    }

    public function price(Request $request) {
        $data = [];
        $cab_route_group_id = $request->group_id ?? '';
        $start_date = date('Y-m-d');
        $end_date =  date('Y-m-d', strtotime($start_date. ' + 10 days'));
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $pre_date =  date('Y-m-d', strtotime($start_date. ' - 10 days'));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $data['cab_route_group_id'] = $cab_route_group_id;
        $data['pre_date'] = $pre_date;
        $data['start_date'] = $start_date;
        $data['last_date'] = $end_date;
        $data['period'] = $period;
        $data['start_date'] = $start_date;
        $data['route_groups'] = CabRouteGroup::where('status',1)->orderBy('id','desc')->get();
        $groups_query = CabRouteGroup::where('status',1)->orderBy('id','desc');
        $group_data = $groups_query->first();

        if(empty($cab_route_group_id)){
            $cab_route_group_id = $group_data->id ?? '';
        }else{
            $group_data =$groups_query->where('id',$cab_route_group_id)->first();
        }
        $data['group_data'] = $group_data;
        $data['cab_route_group_id'] = $cab_route_group_id ?? '';
        $whereDate = [];
        foreach($period as $dt){
            $date =  $dt->format("d-m-Y");
            $whereDate[] =  $dt->format("Y-m-d");
        }
        $CabRoutePrice = CabRoutePrice::where('cab_route_group_id',$cab_route_group_id)->get();
        $cab_route_id_arr = CabRouteToGroup::where('cab_route_group_id',$cab_route_group_id)->pluck('cab_route_id')->toArray();

        $data['cab_routes'] = CabRoute::where('status',1)->whereIn('id',$cab_route_id_arr)->orderBy('id','desc')->get();
        $data['CabRoutePrices'] = $CabRoutePrice;
         $data["page_heading"] = "Cab Route Price";
        return view("admin.cab_masters.price", $data);
    }

    public function saveUnitInventory(Request $request) {

        $response = [];
        $response['success'] = false;
        $message = '';
        $date = isset($request->date) ? $request->date : "";
        $trip_date = date("Y-m-d", strtotime($date));
        $cab_id = isset($request->capId) ? $request->capId : "";
        $value = isset($request->value) ? $request->value : "";
        $cab_route_group_id = isset($request->cab_route_group_id) ? $request->cab_route_group_id : "";

        if($request->method() == 'POST' || $request->method() == 'post'){
            $req_data = [];
            $req_data = array(
                            'cab_route_group_id' => $cab_route_group_id,
                            'cab_id' => $cab_id,
                            'trip_date' => $trip_date,
                            'inventory' => $value,
                            'status' => 1,
                        );
            // prd($req_data);

      $is_data=CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->first();
            if($is_data){
                $isSaved = CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->update($req_data);
                $response['msg'] = 'Cab Route Inventory updated';
            }else{
                $isSaved = CabRouteInventory::create($req_data);
                $response['msg'] = 'Cab Route Inventory saved';
            }

            if($isSaved){
                $response['success'] = true;
            }else{
                $response['msg'] = 'Cab Route Inventory not saved';
            }
        }
            // close post
            echo json_encode($response); exit;
    }

    public function saveUnitPrice(Request $request) {

        $response = [];
        $response['success'] = false;
        $message = '';
        $cab_route_id = isset($request->cab_route_id) ? $request->cab_route_id : "";
        // $trip_date = date("Y-m-d", strtotime($date));
        $cab_id = isset($request->capId) ? $request->capId : "";
        $value = isset($request->value) ? $request->value : 0;
        $cab_route_group_id = isset($request->cab_route_group_id) ? $request->cab_route_group_id : "";

        if($request->method() == 'POST' || $request->method() == 'post'){
            $req_data = [];
            $req_data = array(
                            'cab_route_group_id' => $cab_route_group_id,
                            'cab_id' => $cab_id,
                            'cab_route_id' => $cab_route_id,
                            'price' => $value,
                            // 'status' => 1,
                        );
            // prd($req_data);

      $is_data= CabRoutePrice::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('cab_route_id',$cab_route_id)->first();
            if($is_data){
                $isSaved = CabRoutePrice::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('cab_route_id',$cab_route_id)->update($req_data);
                $response['msg'] = 'Cab Route Price updated';
            }else{
                $isSaved = CabRoutePrice::create($req_data);
                $response['msg'] = 'Cab Route Price saved';
            }
            if($isSaved){
                $response['success'] = true;
            }else{
                $response['msg'] = 'Cab Route Price not saved';
            }
        }
            // close post
            echo json_encode($response); exit;
    }

    public function nextInventory(Request $request) {
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;
        $data = [];
        $cab_route_group_id = $request->cab_route_group_id??'';
        $date = $request->date??'';
        if(!empty($cab_route_group_id) && is_numeric($cab_route_group_id) && !empty($date) && $date != 'undefined') {
            $start_date = $date ;
            $end_date =  date('Y-m-d', strtotime($date. ' + 10 days'));
            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $interval = DateInterval::createFromDateString('1 day');
            $pre_date =  date('Y-m-d', strtotime($start_date. ' - 10 days'));
            $data['pre_date'] = $pre_date;
            $data['last_date'] = $end_date;
            $period = new DatePeriod($begin, $interval, $end);
            $data['start_date'] = $start_date;
            $data['period'] = $period;

            $groups_query = CabRouteGroup::where('status',1)->orderBy('id','desc');
            if($cab_route_group_id){
                $groups_query->where('id',$cab_route_group_id);
            }
            $group_data = $groups_query->first();
            $data['group_data'] = $group_data;
            $cab_route_group_id = $group_data->id ?? '';
            $data['cab_route_group_id'] = $cab_route_group_id ?? '';
            $whereDate = [];
            foreach($period as $dt){
                $date =  $dt->format("d-m-Y");
                $whereDate[] =  $dt->format("Y-m-d");
            }
            $CabRouteInventory = CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->whereIn('trip_date',$whereDate)->get();
                    // prd($CabRouteInventory);

            $data['CabRouteInventories'] = $CabRouteInventory;
            $view_html = view("admin.cab_masters.inventory_form",$data)->render();
            $data['html'] = $view_html;
            $data['pre_date'] = $pre_date;
            $data['last_date'] = $end_date;
            $success = true;
            $statusCode = 200;
        } else {
            $message = 'Invalid request';
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message,
            'data' => $data
        ), $statusCode);
    }

    public function saveAllInventory(Request $request) {
        $success = false;
        $statusCode = 400;
        $message = '';
        $inventories = isset($request->inventory) ? $request->inventory : "";
        $last_date = isset($request->last_date) ? $request->last_date : "";
        $is_saved = isset($request->is_saved) ? $request->is_saved : 0;

        $cab_route_group_id = isset($request->cab_route_group_id) ? $request->cab_route_group_id : "";
        $isSaved =0;
        if( ($request->method() == 'POST' || $request->method() == 'post')){
            $req_data = [];
            foreach ($inventories as $key => $cab_data) {
                $date = $key ;
                $trip_date = date("Y-m-d", strtotime($date));

                foreach ($cab_data as $cab_key => $cab_inventory) {
                    $cab_id = $cab_key;
                    $req_data = array(
                        'cab_route_group_id' => $cab_route_group_id,
                        'cab_id' => $cab_id,
                        'trip_date' => $trip_date,
                        'inventory' => $cab_inventory??0,
                        'status' => 1,
                    );
                    $is_data = CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->first();
                    if($is_data) {
                        $isSaved = CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->update($req_data);
                        if($isSaved) {
                            $success = true;
                            $statusCode = 200;                                
                        }
                    } else {
                        $isSaved = CabRouteInventory::create($req_data);
                        if($isSaved) {
                            $success = true;
                            $statusCode = 200;                                
                        }
                    }                
                }
            }
            if($success) {

                //=====
                    $before_days = 15;
                    $date=strtotime(date('Y-m-d')); 
                    $before_date = date('Y-m-d',strtotime('-15 days',$date));
                    $is_deleted = CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->whereDate('trip_date','<',$before_date)->delete();
                //=====

                $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Route Inventory Updated Successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Route Inventory not updated, please try again.</div>';
            }
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);

    }

    public function saveAllPrice(Request $request) {

        // prd($request->toArray());
        $response = [];
        $response['success'] = false;
        $message = '';
        $inventories = isset($request->inventory) ? $request->inventory : "";
        $round_trip_prices = $request->round_trip_price??[];
        $last_date = isset($request->last_date) ? $request->last_date : "";
        $is_saved = isset($request->is_saved) ? $request->is_saved : 0;
        $cab_route_id = isset($request->cab_route_id) ? $request->cab_route_id : "";

        $cab_route_group_id = isset($request->cab_route_group_id) ? $request->cab_route_group_id : "";
        $isSaved =0;
        if( ($request->method() == 'POST' || $request->method() == 'post')){

           //========
               $req_data = [];
               foreach ($inventories as $key => $cab_data) {
                   $cab_route_id = $key ;
                   // $trip_date = date("Y-m-d", strtotime($date));
                   foreach ($cab_data as $cab_key => $cab_price) {
                        
                        if($cab_price == ''){
                            $cab_price = 0;
                        }
                        $round_trip_price = $round_trip_prices[$cab_route_id][$cab_key]??'';
                        $cab_id = $cab_key;
                        $req_data = array(
                            'cab_route_group_id' => $cab_route_group_id,
                            'cab_id' => $cab_id,
                            'cab_route_id' => $cab_route_id,
                            'price' => $cab_price,
                            'round_trip_price' => $round_trip_price,
                        );
                        // prd($req_data);
                        $is_data = CabRoutePrice::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('cab_route_id',$cab_route_id)->first();
                        if($is_data) {
                            $isSaved = CabRoutePrice::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('cab_route_id',$cab_route_id)->update($req_data);
                            $response['msg'] = 'Cab Route Price updated';
                        } else {
                            $isSaved = CabRoutePrice::create($req_data);
                            $response['msg'] = 'Cab Route Price saved';
                        }
                        CustomHelper::updateCabRoutePublishPrice($cab_route_id);                    
                }
            }
            //========
            if($isSaved){
                $response['success'] = true;
            }else{
                $response['msg'] = 'Cab Route Price not saved';
            }
        }
        echo json_encode($response); exit;

    }
    public function update_cab(Request $request){

        $response['success'] = false;
        $response['msg'] = '';
        $found = 0;

        $getAllData = $request->data;

        if(isset($getAllData) && !empty($getAllData)){
            $found = 1;
            foreach ($getAllData as $key => $value) {
             $getId = $value;
             $req_data['sort_order'] = $key+1;
             $isSaved = CabMaster::where('id',$getId)->update($req_data);
         }
     }
     if ($found) {
        $response['success'] = true;
        $response['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab has been updated successfully.</div>';
    }
    return response()->json($response);        

}


    public function bulk_inventory(Request $request) {
        // prd($request->toArray());
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;

        $cab_route_group_id = $request->cab_route_group_id??'';
        $bulkdaterange = $request->bulkdaterange??'';
        $cab_inventory = $request->cab_inventory??'';
        // prd($price_ids);
        if(!empty($cab_route_group_id) && !empty($bulkdaterange) && !empty($cab_inventory)) {
            $cab_route_group = CabRouteGroup::find($cab_route_group_id);
            $bulkdaterange_arr = explode(' - ', $bulkdaterange);
            $from_date = $bulkdaterange_arr[0]??'';
            $to_date = $bulkdaterange_arr[1]??'';
            $from_date = CustomHelper::DateFormat($from_date,'Y-m-d','d/m/Y');
            $to_date = CustomHelper::DateFormat($to_date,'Y-m-d','d/m/Y');
            if($from_date && $to_date) {
                $start_date = $from_date;
                $end_date =  $to_date;
                $begin = new DateTime($start_date);
                $end = new DateTime(date('Y-m-d', strtotime($end_date. ' + 1 days')));
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                // prd($period);
                foreach($period as $dt) {
                    $date = $dt->format("d-m-Y");
                    $trip_date = $dt->format("Y-m-d");
                    if($cab_inventory) {
                        foreach($cab_inventory as $cab_id => $val) {
                            if($val != '') {
                                $req_data = [
                                    'cab_route_group_id' => $cab_route_group_id,
                                    'cab_id' => $cab_id,
                                    'trip_date' => $trip_date,
                                    'inventory' => $val,
                                    'status' => 1,
                                ];
                                // pr($req_data);

                                $record = CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->first();
                                if($record) {
                                    $isSaved = CabRouteInventory::where("id", $record->id)->update($req_data);
                                    if($isSaved) {
                                        $success = true;
                                        $statusCode = 200;
                                    }
                                } else {
                                    $isSaved = CabRouteInventory::create($req_data);
                                    if($isSaved) {
                                        $success = true;
                                        $statusCode = 200;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if($success) {

             //=====
                $before_days = 15;
                $date=strtotime(date('Y-m-d')); 
                $before_date = date('Y-m-d',strtotime('-15 days',$date));
                $is_deleted = CabRouteInventory::where('cab_route_group_id',$cab_route_group_id)->whereDate('trip_date','<',$before_date)->delete();
                //=====

            $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Inventory Updated Successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Inventory not updated, please try again.</div>';
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);
    }

/* end of controller */
}
