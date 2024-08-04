<?php
namespace App\Http\Controllers\Admin;
use App\Models\BikeMaster;
use App\Models\BikeCities;
use App\Models\BikeCityInventory;
use App\Models\BikecityPrice;
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

class BikeMasterController extends Controller {

    protected $currentUrl;
    protected $limit;
    protected $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->limit = 40;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;
        $name = isset($request->name) ? $request->name : "";
        $bike_type = isset($request->bike_type) ? $request->bike_type : 0;
        $status = isset($request->status) ? $request->status : 1;
        $bikeQuery = BikeMaster::orderBy("sort_order", "asc");
        if (!empty($name)) {
            $bikeQuery->where("name", "like", "%" . $name . "%");
        }
        if (strlen($status) > 0) {
            $bikeQuery->where("status", $status);
        }
        if ($bike_type > 0) {
            $bikeQuery->where("type", $bike_type);
        }
        $bikes = $bikeQuery->paginate($limit);
        $data["bikes"] = $bikes;
        return view("admin.bike_masters.index", $data);
    }

    public function add(Request $request) {

        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $bikeQuery = [];
        $title = "Add Bike";

        if (is_numeric($id) && $id > 0) {
            $bikeQuery = BikeMaster::find($id);
            $title = "Edit Bike(" . $bikeQuery->name. " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $ext = "jpg,jpeg,png,gif";
            $rules = [];
            $rules["name"] = "required|max:255|regex:/^[0-9A-Za-z.\s,'-]*$/";
            $rules["status"] = "required";
            $rules["type"] = "required";
            $rules["modal"] = "required";
            $rules["image"] = "nullable|image|mimes:" . $ext;
            $validation_msg = [];
            $validation_msg["name.required"] = "Bike name is required.";
            $validation_msg["name.regex"] = "Bike name format is Invalid.";
            $validation_msg["type.required"] = "Bike type is required.";
            $validation_msg["modal.required"] = "Bike model is required.";
            $this->validate($request, $rules, $validation_msg);

            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['equivalent'] = $request->equivalent??'';
            $req_data['type'] = $request->type??'';
            $req_data['modal'] = $request->modal??'';
            $req_data['status'] = $request->status??'0';
            // $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            if (!empty($bikeQuery) && $bikeQuery->id == $id) {
                $isSaved = BikeMaster::where("id",$bikeQuery->id)->update($req_data);
                $bike_master_id = $bikeQuery->id;
                $msg = "Bike has been updated successfully.";
            } else {
                $isSaved = BikeMaster::create($req_data);
                $bike_master_id = $isSaved->id;
                $msg = "Bike has been added successfully.";
            }

            if ($isSaved) {
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImg($file, $bike_master_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }
                cache()->forget("bike_master");

                $new_data = DB::table('bike_master')->where('id',$bike_master_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $bike_master_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Rental';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME ."/bike_master"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Bike could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["bikeQuery"] = $bikeQuery;
        $data["id"] = $id;
        return view("admin.bike_masters.form", $data);
    }

    public function saveImg($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "bikes/";
            $thumb_path = "bikes/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "BIKE_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "BIKE_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "BIKE_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "BIKE_IMG_THUMB_HEIGHT"
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
                    $bikeQuery = BikeMaster::find($id);

                    if (!empty($bikeQuery)) {
                        $storage = Storage::disk("public");
                        $old_image = $bikeQuery->image;
                        $bikeQuery->image = $new_image;
                        $isUpdated = $bikeQuery->save();
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

    public function delete(Request $request){
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "bikes/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $bikeQuery = BikeMaster::find($id);
                $new_data = DB::table('bike_master')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = BikeMaster::bikeMasterDelete($id);
                    if ($is_delete['status'] != 'ok') {
                        return redirect(url('admin/bike_master'))->with('alert-danger', $is_delete['message']);
                    } else {
                        $is_deleted = true;
                    }

                if (!empty($bikeQuery) && count([$bikeQuery]) > 0) {
                    if (!empty($bikeQuery->image)) {
                        $image = $bikeQuery->image;
                        if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $image
                            );
                        }
                        if (!empty($image) && $storage->exists($path . $image)) {
                            $is_deleted = $storage->delete($path . $image);
                        }
                    }
                }
            }
        }
        if ($is_deleted) {
            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Rental';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';
            CustomHelper::RecordActivityLog($params);
            return redirect(url($this->ADMIN_ROUTE_NAME . "/bike_master"))->with("alert-success","Bike has been deleted successfully.");
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/bike_master"))->with("alert-danger","Bike cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function delete_image($id, $type) {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "bikes/";
        $bikeQuery = BikeMaster::find($id);
        $image = isset($bikeQuery->image) ? $bikeQuery->image : "";
        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }
            if ($is_deleted) {
                if ($type == "image") {
                    $bikeQuery->image = "";
                }
                $is_updated = $bikeQuery->save();
            }
        }
        return $is_updated;
    }
    public function inventory(Request $request) {
        $data = [];
        $city_id = $request->city_id ?? '';
        $start_date = date('Y-m-d');
        $end_date =  date('Y-m-d', strtotime($start_date. ' + 10 days'));
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $pre_date =  date('Y-m-d', strtotime($start_date. ' 0 days'));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $bikeCities = [];
        $bikeCityQuery = BikeCities::orderBy("sort_order", "asc");
        $bikeCityQuery->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        $bikeCityQuery->where("status", 1);
        $bikeCities = $bikeCityQuery->get();

        $data['pre_date'] = $pre_date;
        $data['last_date'] = $end_date;
        $data['start_date'] = $start_date;
        $data['period'] = $period;
        $data['start_date'] = $start_date;
        $data['bike_cities'] = $bikeCities;
        $bike_city_query = BikeCities::where('status',1)->orderBy("sort_order", "asc");
        $bike_city_query->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        if($city_id){
            $bike_city_query->where('id',$city_id);
        }
        $bike_city_data = $bike_city_query->first();
        $data['bike_city_data'] = $bike_city_data;
        $city_id = $bike_city_data->id ?? '';
        $data['city_id'] = $city_id ?? '';
        $whereDate = [];
        foreach($period as $dt){
            $date =  $dt->format("d-m-Y");
            $whereDate[] =  $dt->format("Y-m-d");
        }
        $BikeCityInventory = BikeCityInventory::where('city_id',$city_id)->whereIn('trip_date',$whereDate)->get();
        $data['BikeCityInventory'] = $BikeCityInventory;
        $data["page_heading"] = "Bike City Inventory";
        return view("admin.bike_masters.inventory", $data);
    }

    public function price(Request $request) {
        $data = [];
        $city_id = $request->city_id ?? '';
        $bikeCities = [];
        $bikeCityQuery = BikeCities::orderBy("sort_order", "asc");
        $bikeCityQuery->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        $bikeCityQuery->where("status", 1);
        $bikeCities = $bikeCityQuery->get();
        $data['city_id'] = $city_id;
        $data['bike_cities'] = $bikeCities;

        $bike_city_query = BikeCities::where('status',1)->orderBy("sort_order", "asc");
        $bike_city_query->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        $bike_city_data = $bike_city_query->first();
        if(empty($city_id)){
            $city_id = $bike_city_data->id ?? '';
        }else{
            $bike_city_data = $bike_city_query->where('id',$city_id)->first();
        }
        $data['bike_city_data'] = $bike_city_data;
        $data['city_id'] = $city_id ?? '';
        $BikecityPrice = BikecityPrice::where('city_id',$city_id)->get();
        $data['BikecityPrices'] = $BikecityPrice;
        $data["page_heading"] = "Bike City Price";
        return view("admin.bike_masters.price", $data);
    }

    public function allPrice(Request $request) {
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;
        $data = [];
        $city_id = $request->city_id??'';
        if(!empty($city_id) && is_numeric($city_id)) {
            $bike_city_query = BikeCities::where('status',1)->orderBy("sort_order", "asc");
            $bike_city_query->where(function($query){
                $query->where('is_deleted', 0);
                $query->orWhereNull('is_deleted');
            });
            if($city_id){
                $bike_city_query->where('id',$city_id);
            }
            $bike_city_data = $bike_city_query->first();
            $data['bike_city_data'] = $bike_city_data;
            $city_id = $bike_city_data->id ?? '';
            $data['city_id'] = $city_id ?? '';
            $BikecityPrice = BikecityPrice::where('city_id',$city_id)->get();
            $data['BikecityPrice'] = $BikecityPrice;
            $view_html = view("admin.bike_masters.price_form",$data)->render();
            $data['html'] = $view_html;
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

    public function saveAllPrice(Request $request) {
        $success = false;
        $statusCode = 400;
        $message = '';
        $price = isset($request->price) ? $request->price : "";
        $city_id = isset($request->city_id) ? $request->city_id : "";
        $isSaved = 0;
        if(($request->method() == 'POST' || $request->method() == 'post')){
            $req_data = [];
            foreach ($price as $bike_id => $bike_price) {
                    $req_data = array(
                        'city_id' => $city_id,
                        'bike_id' => $bike_id,
                        'price' => $bike_price??0,
                    );
                $is_data = BikecityPrice::where('city_id',$city_id)->where('bike_id',$bike_id)->first();
                if($is_data) {
                    $isSaved = BikecityPrice::where('city_id',$city_id)->where('bike_id',$bike_id)->update($req_data);
                    if($isSaved) {
                        $success = true;
                        $statusCode = 200;                                
                    }
                } else {
                    $isSaved = BikecityPrice::create($req_data);
                    if($isSaved) {
                        $success = true;
                        $statusCode = 200;                                
                    }
                } 
            }
            if($success) {
                $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Bike City Price Updated Successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Bike City Price not updated, please try again.</div>';
            }
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);
    }

    public function nextInventory(Request $request) {
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;
        $data = [];
        $city_id = $request->city_id??'';
        $date = $request->date??'';
        if(!empty($city_id) && is_numeric($city_id) && !empty($date) && $date != 'undefined') {
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

            $bike_city_query = BikeCities::where('status',1)->orderBy("sort_order", "asc");
            $bike_city_query->where(function($query){
                $query->where('is_deleted', 0);
                $query->orWhereNull('is_deleted');
            });
            if($city_id){
                $bike_city_query->where('id',$city_id);
            }
            $bike_city_data = $bike_city_query->first();
            $data['bike_city_data'] = $bike_city_data;
            $city_id = $bike_city_data->id ?? '';
            $data['city_id'] = $city_id ?? '';
            $whereDate = [];
            foreach($period as $dt){
                $date =  $dt->format("d-m-Y");
                $whereDate[] =  $dt->format("Y-m-d");
            }
            $BikeCityInventory = BikeCityInventory::where('city_id',$city_id)->whereIn('trip_date',$whereDate)->get();
            $data['BikeCityInventories'] = $BikeCityInventory;
            $view_html = view("admin.bike_masters.inventory_form",$data)->render();
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
        $city_id = isset($request->city_id) ? $request->city_id : "";
        $isSaved = 0;
        if( ($request->method() == 'POST' || $request->method() == 'post')){
            $req_data = [];
            foreach ($inventories as $key => $bike_data) {
                $date = $key ;
                $trip_date = date("Y-m-d", strtotime($date));
                foreach ($bike_data as $bike_key => $bike_inventory) {
                    $bike_id = $bike_key;
                    $req_data = array(
                        'city_id' => $city_id,
                        'bike_id' => $bike_id,
                        'trip_date' => $trip_date,
                        'inventory' => $bike_inventory??0,
                        'status' => 1,
                    );
                    $is_data = BikeCityInventory::where('city_id',$city_id)->where('bike_id',$bike_id)->where('trip_date',$trip_date)->first();
                    if($is_data) {
                        $isSaved = BikeCityInventory::where('city_id',$city_id)->where('bike_id',$bike_id)->where('trip_date',$trip_date)->update($req_data);
                        if($isSaved) {
                            $success = true;
                            $statusCode = 200;                                
                        }
                    } else {
                        $isSaved = BikeCityInventory::create($req_data);
                        if($isSaved) {
                            $success = true;
                            $statusCode = 200;                                
                        }
                    }                
                }
            }
            if($success) {
                    $before_days = 15;
                    $date=strtotime(date('Y-m-d')); 
                    $before_date = date('Y-m-d',strtotime('-15 days',$date));
                    $is_deleted = BikeCityInventory::where('city_id',$city_id)->whereDate('trip_date','<',$before_date)->delete();
                $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Bike City Inventory Updated Successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Bike City Inventory not updated, please try again.</div>';
            }
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);
    }

    public function update_bike(Request $request){
        $response['success'] = false;
        $response['msg'] = '';
        $found = 0;
        $getAllData = $request->data;
        if(isset($getAllData) && !empty($getAllData)){
            $found = 1;
            foreach ($getAllData as $key => $value) {
             $getId = $value;
             $req_data['sort_order'] = $key+1;
             $isSaved = BikeMaster::where('id',$getId)->update($req_data);
         }
     }
     if ($found) {
        $response['success'] = true;
        $response['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Bike has been updated successfully.</div>';
    }
    return response()->json($response);        

    }

    public function bulk_inventory(Request $request) {
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;
        $city_id = $request->city_id??'';
        $bulkdaterange = $request->bulkdaterange??'';
        $bike_city_inventory = $request->bike_inventory??'';
        if(!empty($city_id) && !empty($bulkdaterange) && !empty($bike_city_inventory)) {
            $bike_city = BikeCities::find($city_id);
            $bulkdaterange_arr = explode(' - ', $bulkdaterange);
            $from_date = $bulkdaterange_arr[0] ?? '';
            $to_date = $bulkdaterange_arr[1] ?? '';
            $from_date = CustomHelper::DateFormat($from_date,'Y-m-d','d/m/Y');
            $to_date = CustomHelper::DateFormat($to_date,'Y-m-d','d/m/Y');
            if($from_date && $to_date) {
                $start_date = $from_date;
                $end_date =  $to_date;
                $begin = new DateTime($start_date);
                $end = new DateTime(date('Y-m-d', strtotime($end_date. ' + 1 days')));
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                foreach($period as $dt) {
                    $date = $dt->format("d-m-Y");
                    $trip_date = $dt->format("Y-m-d");
                    if($bike_city_inventory) {
                        foreach($bike_city_inventory as $bike_id => $val) {
                            if($val != '') {
                                $req_data = [
                                    'city_id' => $city_id,
                                    'bike_id' => $bike_id,
                                    'trip_date' => $trip_date,
                                    'inventory' => $val,
                                    'status' => 1,
                                ];
                                $record = BikeCityInventory::where('city_id',$city_id)->where('bike_id',$bike_id)->where('trip_date',$trip_date)->first();
                                if($record) {
                                    $isSaved = BikeCityInventory::where("id", $record->id)->update($req_data);
                                    if($isSaved) {
                                        $success = true;
                                        $statusCode = 200;
                                    }
                                } else {
                                    $isSaved = BikeCityInventory::create($req_data);
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
                $before_days = 15;
                $date = strtotime(date('Y-m-d')); 
                $before_date = date('Y-m-d',strtotime('-15 days',$date));
                $is_deleted = BikeCityInventory::where('city_id',$city_id)->whereDate('trip_date','<',$before_date)->delete();
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
