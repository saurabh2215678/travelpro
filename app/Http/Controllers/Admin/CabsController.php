<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Illuminate\Validation\Rule;
use App\Models\Cabs;
use App\Models\CabsCities;
use App\Models\CabsCityPrice;
use App\Models\CabsSightseeing;
use App\Models\CabsInventory;
use App\Models\AgentGroup;
use App\Models\ModuleDiscountCategory;
use App\Models\DiscountModuleToGroup;
use App\Models\CustomModuleRecordDiscount;
// use App\Models\CabRoute;
// use App\Models\CabRouteGroup;
// use App\Models\CabRoutePrice;
// use App\Models\CabRouteToGroup;
use Mail;
use Validator;
use Storage;
use DB;
use Response;
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\Carbon as Carbon;

class CabsController extends Controller {

    protected $currentUrl;
    protected $limit;
    protected $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->limit = 15;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    // Cabs Start
    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : 1;
        $cabQuery = Cabs::orderBy("sort_order", "asc");
        if (!empty($name)) {
            $cabQuery->where("name", "like", "%" . $name . "%");
        }
        if (strlen($status) > 0) {
            $cabQuery->where("status", $status);
        }
        $cabs = $cabQuery->paginate($limit);
        $data["cabs"] = $cabs;
        return view("admin.cabs.index", $data);
    }

    public function add(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $cabData = [];
        $title = "Add Cab";
        if (is_numeric($id) && $id > 0) {
            $cabData = Cabs::find($id);
            $title = "Edit Cab(" . $cabData->name. " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $ext = "jpg,jpeg,png,gif";
            $nicknames = [
                'name' => 'Name',
                'equivalent' => 'Equivalent',
                'seats' => 'Passenger Seats',
                'chauffeur_charge' => 'Driver charge Per Day',
                'night_stay_allowance' => 'Night Stay Allowance Per Night',
                'image' => 'Image',
                'sort_order' => 'Sort Order',
                'status' => 'Status',
            ];
            $rules = [];
            $rules["name"] = 'required|max:255|regex:/^[\pL\s\-]+$/u';
            $rules["equivalent"] = "nullable";
            $rules["seats"] = "required";
            $rules["chauffeur_charge"] = "nullable|numeric";
            $rules["night_stay_allowance"] = "nullable|numeric";
            $rules["image"] = "nullable|image|mimes:" . $ext;
            $rules["sort_order"] = "nullable";
            $rules["status"] = "required";
            $validation_msg = [];
            $validation_msg["required"] = ":Attribute is required.";
            $validation_msg["name.regex"] = ":Attribute format is Invalid.";
            $this->validate($request, $rules, $validation_msg, $nicknames);
            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['equivalent'] = $request->equivalent??'';
            $req_data['seats'] = $request->seats??0;
            $req_data['base_price'] = $request->base_price??0;
            $req_data['chauffeur_charge'] = $request->chauffeur_charge??0;
            $req_data['night_stay_allowance'] = $request->night_stay_allowance??0;
            $req_data['seats'] = $request->seats??0;
            $req_data['sort_order'] = $request->sort_order??0;
            $req_data['status'] = $request->status??0;
            if ($cabData && $cabData->id && $cabData->id == $id) {
                $isSaved = Cabs::where("id",$cabData->id)->update($req_data);
                $cab_id = $cabData->id;
                $msg = "Cab has been updated successfully.";
            } else {
                $isSaved = Cabs::create($req_data);
                $cab_id = $isSaved->id;
                $msg = "Cab has been added successfully.";
            }

            if ($isSaved) {
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImg($file, $cab_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash("alert-danger", "Image could not be added");
                    }
                }

                $new_data = DB::table('cabs')->where('id',$cab_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $cab_id;
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

                return redirect(route($this->ADMIN_ROUTE_NAME.".cabs.index"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Cab could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["cabData"] = $cabData;
        $data["id"] = $id;
        return view("admin.cabs.form", $data);
    }

    public function view(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $cabData = [];
        $title = "Cab Details";
        if (is_numeric($id) && $id > 0) {
            $cabData = Cabs::find($id);
            $title = "Cab Details (".$cabData->name.")";
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["cabData"] = $cabData;
        $data["id"] = $id;
        return view("admin.cab_masters.view", $data);
    }

    public function saveImg($file, $id, $type) {
        $result["org_name"] = "";
        $result["file_name"] = "";
        if ($file) {
            $path = "cabs/";
            $thumb_path = "cabs/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("CAB_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("CAB_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("CAB_IMG_THUMB_WIDTH");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("CAB_IMG_THUMB_HEIGHT");

            $IMG_WIDTH = !empty($IMG_WIDTH) ? $IMG_WIDTH : 768;
            $IMG_HEIGHT = !empty($IMG_HEIGHT) ? $IMG_HEIGHT : 768;
            $THUMB_WIDTH = !empty($THUMB_WIDTH) ? $THUMB_WIDTH : 336;
            $THUMB_HEIGHT = !empty($THUMB_HEIGHT) ? $IMG_WIDTH : 336;

            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext = "", $IMG_WIDTH, $IMG_HEIGHT, $is_thumb = true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT );

            if ($uploaded_data["success"]) {
                $new_image = $uploaded_data["file_name"];
                if (is_numeric($id) && $id > 0) {
                    $cabData = Cabs::find($id);
                    if (!empty($cabData)) {
                        $storage = Storage::disk("public");
                        $old_image = $cabData->image;
                        $cabData->image = $new_image;
                        $isUpdated = $cabData->save();
                        if ($isUpdated) {
                            if (!empty($old_image) && $storage->exists($path . $old_image) ) {
                                $storage->delete($path . $old_image);
                            }
                            if (!empty($old_image) && $storage->exists($thumb_path . $old_image) ) {
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
                $result["msg"] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
            }
        }

        if ($result["success"] == false) {
            $result["msg"] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function delete(Request $request) {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "cabs/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $cabData = Cabs::find($id);
                $new_data = DB::table('cabs')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = Cabs::cabsDelete($id);
                if ($is_delete['status'] != 'ok') {
                    return redirect(route($this->ADMIN_ROUTE_NAME.".cabs.index"))->with('alert-danger', $is_delete['message']);
                } else {
                    $delete_destination_name = $is_delete['name'];
                    $is_deleted = true;
                }

                if (!empty($cabData) && count([$cabData]) > 0) {
                    if (!empty($cabData->image) ) {
                        $image = $cabData->image;
                        if ( !empty($image) && $storage->exists($path . "thumb/" . $image) ) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $image
                            );
                        }
                        if ( !empty($image) && $storage->exists($path . $image) ) {
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

            return redirect(route($this->ADMIN_ROUTE_NAME.".cabs.index"))->with("alert-success","Cab has been deleted successfully.");
        } else {
            return redirect(route($this->ADMIN_ROUTE_NAME.".cabs.index"))->with("alert-danger","Cab cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function delete_image($id, $type) {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "cabs/";
        $cabData = Cabs::find($id);
        $image = isset($cabData->image) ? $cabData->image : "";
        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $cabData->image = "";
                }
                $is_updated = $cabData->save();
            }
        }
        return $is_updated;
    }

    public function update_cab(Request $request) {
        $response['success'] = false;
        $response['msg'] = '';
        $found = 0;
        $getAllData = $request->data;

        if(isset($getAllData) && !empty($getAllData)){
            $found = 1;
            foreach ($getAllData as $key => $value) {
                $getId = $value;
                $req_data['sort_order'] = $key+1;
                $isSaved = Cabs::where('id',$getId)->update($req_data);
            }
        }
        if ($found) {
            $response['success'] = true;
            $response['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cabs has been updated successfully.</div>';
        }
        return response()->json($response);
    }
    // Cabs End

    // Cabs Cities Start
    public function cities(Request $request) {
        $data = [];
        $limit = $this->limit;
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status :1;
        $featured = isset($request->featured) ? $request->featured : "";
        $cabCityQuery = CabsCities::with('cabsCityPrices','cabsCityPrices.cabData')->orderBy("sort_order", "asc");

        $cabCityQuery->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });

        if (!empty($name)) {
            $cabCityQuery->where("name", "like", "%" . $name . "%");
        }
        if (strlen($status) > 0) {
            $cabCityQuery->where("status", $status);
        }
        if (strlen($featured) > 0) {
            $cabCityQuery->where("featured", $featured);
        }

        if($request->is_airport != '') {
            $cabCityQuery->where("is_airport", $request->is_airport);
        }

        if($request->is_railway != '') {
            $cabCityQuery->where("is_railway", $request->is_railway);
        }

        $cabCities = $cabCityQuery->paginate($limit);
        $data["cabCities"] = $cabCities;
        return view("admin.cabs.cities_index", $data);
    }


    public function cities_add(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $cityData = [];
        $title = "Add Cab City";

        $old_cab_ids = [];
        if (is_numeric($id) && $id > 0) {
            $cityData = CabsCities::find($id);
            $title = "Edit Cab City (" . $cityData->name. " )";
            if($cityData->cab) {
                $old_cab_ids = json_decode($cityData->cab, true);
            }
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            // prd($request->toArray());
            $ext = "jpg,jpeg,png,gif";
            $rules = [];

            if (is_numeric($id) && $id > 0) {
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
            } else {
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u|unique:cabs_cities|max:100';
            }

            $rules["status"] = "required";
            $rules["cab"] = "required";
            $this->validate($request, $rules);
            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['cab'] = isset($request->cab) ? json_encode($request->cab) : json_encode([]);
            $req_data['per_day_km'] = $request->per_day_km??'';
            $req_data['inclusions'] = $request->inclusions??'';
            $req_data['exclusions'] = $request->exclusions??'';
            $req_data['terms'] = $request->terms??'';
            $req_data['is_sharing'] = $request->is_sharing??0;
            $req_data['is_sightseeing'] = $request->is_sightseeing??0;
            $req_data['featured'] = $request->featured??0;
            
            $req_data['is_airport'] = $request->is_airport??0;
            if($req_data['is_airport'] == 1) {
                $req_data['airport_entry_charge'] = $request->airport_entry_charge??0;
                $req_data['airport_max_distance'] = $request->airport_max_distance??0;
                $req_data['terminals'] = isset($request->terminals) ? json_encode($request->terminals) : json_encode([]);
            } else {
                $req_data['airport_entry_charge'] = 0;
                $req_data['terminals'] = json_encode([]);
            }

            $req_data['is_railway'] = $request->is_railway??0;
            if($req_data['is_railway'] == 1) {
                $req_data['station_entry_charge'] = $request->station_entry_charge??0;
                $req_data['station_max_distance'] = $request->station_max_distance??0;
                $req_data['stations'] = isset($request->stations) ? json_encode($request->stations) : json_encode([]);
            } else {
                $req_data['station_entry_charge'] = 0;
                $req_data['stations'] = json_encode([]);
            }

            $req_data['sort_order'] = $request->sort_order??0;
            $req_data['is_deleted'] = $request->is_deleted??0;
            $req_data['status'] = $request->status??'0';
            if (!empty($cityData) && $cityData->id == $id) {
                $isSaved = CabsCities::where("id",$cityData->id)->update($req_data);
                $cab_cities_id = $cityData->id;
                $msg = "Cab City has been updated successfully.";
            } else {
                $isSaved = CabsCities::create($req_data);
                $cab_cities_id = $isSaved->id;
                $msg = "Cab City has been added successfully.";
            }

            if ($isSaved) {
                $insert_cab_ids = [];
                if($request->cab) {
                    $new_cab_ids = $request->cab??[];
                    if(is_array($new_cab_ids)) {
                        foreach($new_cab_ids as $new_cab_id) {
                            if(!empty($old_cab_ids) && in_array($new_cab_id, $old_cab_ids)){
                                $key = array_search($new_cab_id, $old_cab_ids);
                                if($key !== NULL) {
                                    unset($old_cab_ids[$key]);
                                }
                            } else {
                                $insert_cab_ids[] = $new_cab_id;
                            }
                        }
                    }
                    if(!empty($insert_cab_ids) && count($insert_cab_ids) > 0) {
                        foreach($insert_cab_ids as $insert_cab_id) {
                            $insert_cab_price = [
                                'city_id' => $cab_cities_id,
                                'cab_id' => $insert_cab_id,
                                'price' => 0,
                            ];
                            $isSaved = CabsCityPrice::create($insert_cab_price);
                        }
                    }
                }
                if(!empty($old_cab_ids) && count($old_cab_ids) > 0) {
                    CabsCityPrice::where('city_id',$cab_cities_id)->whereIn('cab_id',$old_cab_ids)->delete();
                }

                $new_data = DB::table('cab_cities')->where('id',$cab_cities_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $cab_cities_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Cab City';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(route($this->ADMIN_ROUTE_NAME .".cabs.cities"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Cab City could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["cityData"] = $cityData;
        $data["cabs"] = Cabs::where('status',1)->orderBy('sort_order','asc')->orderBy('id','desc')->get();
        $data["id"] = $id;
        return view("admin.cabs.cities_form", $data);
    }

    public function cities_view(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        if (is_numeric($id) && $id > 0) {
            $cityData = CabsCities::where("id", $id)->first();
            $title = "Cab City Details (".$cityData->name.")";

            $data = [];
            $data["page_heading"] = $title;
            $data["cityData"] = $cityData;
            $data["id"] = $id;
            return view("admin.cabs.cities_view", $data);
        } else {
            abort(404);
        }
    }

    public function cities_delete(Request $request) {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {

                $cabCityQuery = CabsCities::find($id);
                $new_data = DB::table('cab_cities')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = CabsCities::cabCityDelete($id);
                if ($is_delete['status'] != 'ok') {
                    return redirect(route($this->ADMIN_ROUTE_NAME.'.cabs.cities'))->with('alert-danger', $is_delete['message']);
                } else {
                    $delete_destination_name = $is_delete['name'];
                    $is_deleted = true;
                }
            }

            if ($is_deleted) {
                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Cab City';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data??'';
                $params['action'] = 'Delete';
                CustomHelper::RecordActivityLog($params);

                return redirect(route($this->ADMIN_ROUTE_NAME . ".cabs.cities"))->with("alert-success", "Cab City has been deleted successfully.");
            } else {
                return redirect(route($this->ADMIN_ROUTE_NAME . ".cabs.cities"))->with("alert-danger","Cab City cannot be deleted, please try again or contact the administrator.");
            }
        }
    }

    public function cities_agent_price(Request $request) {
        $data = [];
        $limit = $this->limit;
        $id = isset($request->id) ? $request->id : "";
        $day_title = (isset($request->day_title))?$request->day_title:'';
        $status = (isset($request->status))?$request->status:'';
        if(!empty($id)) {
            $cityData = CabsCities::find($id);    
            if($cityData) {
                $module_name = 'cab';
                $module_record_id = $cityData->id;

                $discount_category_id = isset($cityData->discount_category_id)?$cityData->discount_category_id : null;
                $discount_category_data = [];
                $is_default_category = 0;
                if($discount_category_id === -1) {
                    $discount_category_name = 'Custom Discount';
                    $moduleRecordDiscounts = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->orderBy('module_name', 'asc')->get();
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
                    }
                } else if($discount_category_id === 0) {
                    $discount_category_name = 'Discount Not Applicable';
                } elseif($discount_category_id === Null) {

                    $is_default_category = 1;
                    $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                    $discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                    $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                    $discount_category_name = $discount_category_data->name ?? '';

                } else {
                    $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                    $discount_category_name = $discount_category_data->name ?? '';
                }
                $discount_categories = ModuleDiscountCategory::where('module_name',$module_name)->orderBy('module_name', 'asc')->get();
                $data['agent_groups'] = AgentGroup::where('status',1)->get();
                $data["heading"] = 'Agent Price ('.$cityData->name.')';
                $data["city_id"] = $id;
                $data['group_datas'] = [];

                $data['cityData'] = $cityData;           
                $data["discount_category_id"] = $discount_category_id;
                $data["is_default_category"] = $is_default_category;
                $data["discount_categories"] = $discount_categories;
                $data["discount_category_name"] = $discount_category_name;
                $data["discount_category_data"] = $discount_category_data;
                $data["module_name"] = $module_name;
                $data["module_record_id"] = $module_record_id;

                $discount_groups = [];
                $discount_groups[] = (object)[
                    'id' => '-1',
                    'name' => 'Custom Discount',
                ];
                $data["discount_groups"] = $discount_groups;
                $data["custom_discount_section"] = true;
                return view("admin.cabs.cities_agent_price", $data);
            }
        }
        abort(404);
    }

    public function cities_add_agent_price(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->id) {
            $id = $request->id;
            $discount_category = isset($request->discount_category) ? $request->discount_category : null;
            $cityData = CabsCities::find($id);
            if($cityData) {
                $cityData->discount_category_id = $discount_category;
                $is_updated = $cityData->save();
                $response['success'] = true;                
            }
        }
        return response()->json($response);
    }
    // Cabs Cities End


    // Cabs Sightseeing Start
    public function sightseeing(Request $request) {

        $parent_id = (isset($request->parent_id))?$request->parent_id:0;
        $name = isset($request->name) ? $request->name: "";
        $status = isset($request->status) ? $request->status : 1;
        $data = array();
        $limit = $this->limit;
        $pageQuery = CabsSightseeing::with('CabRouteGroup','originCity','destinationCity')->orderBy('sort_order','asc');
        if (!empty($name)) {

            $pageQuery->where(function($q1) use($name){

                $q1->where("name", "like", "%" . $name . "%");
                $q1->orWhere("places", "like", "%" . $name . "%");
            });
        }

        if(isset($request->cab_route_group_id)) {
            $cab_route_group_id = $request->cab_route_group_id;
            // $pageQuery->where('cab_route_group_id',$request->cab_route_group_id);
            $pageQuery->whereHas('CabRouteToGroup',function($q2) use($cab_route_group_id){
                $q2->where('cab_route_group_id',$cab_route_group_id);
            });
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

        $pageQuery->where("status", $status);
        $pages = $pageQuery->paginate($limit);

        $discount_result = [];//DiscountModuleToGroup::where('module_name','cab')->where('is_default',1)->first();
        // prd($discount_result);
        $default_group = '';
        $default_group = isset($discount_result->discount_category) ? $discount_result->discount_category->name :'' ;
        $data['default_group'] = $default_group.'(Default)';

        $data['res']= $pages;
        $data["page_heading"] = "Cab Sightseeing";
        $data['cab_route_groups'] = [];//CabRouteGroup::where('status',1)->get();
        $data['cab_cities'] = CabsCities::where('status',1)->get();
        return view('admin.cabs.sightseeing_index',$data);
    }

    public function sightseeing_add(Request $request) {
        $id = (isset($request->id))?$request->id:0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $parent_id = (isset($request->parent_id))?$request->parent_id:0;
        $page = [];
        $title = 'Add Cab Sightseeing';

        if(is_numeric($id) && $id > 0){
            $page = CabsSightseeing::find($id);
            $cab_route_title = $page->name??'';
            $title = 'Edit Cab Sightseeing ('.$cab_route_title.")";
        }

        if($request->method() == 'POST' || $request->method() == 'post') {
            // prd($request->toArray());
            $rules = [];
            $rules['name'] = 'required|max:255';
            $rules['origin'] = 'required';
            $rules['distance'] = 'nullable|numeric|required';
            $this->validate($request, $rules);

            $req_data = [];
            $req_data['name'] = (!empty($request->name))?$request->name:'';
            $req_data['origin'] = $request->origin ?? null;
            $req_data['destination'] = $request->destination ?? null;
            $req_data['duration_type'] = (!empty($request->duration_type))?$request->duration_type:'';
            $req_data['duration_value'] = (!empty($request->duration_value))?$request->duration_value:'';
            $req_data['duration'] = (!empty($request->duration))?$request->duration:'';
            $req_data['distance'] = (!empty($request->distance))?$request->distance:'';
            $req_data['description'] = (!empty($request->description))?$request->description:'';
            $req_data['places'] = (!empty($request->places))?$request->places:'';
            $req_data['sharing'] = (!empty($request->sharing))?$request->sharing:'';
            $req_data['featured'] = (!empty($request->featured))?$request->featured:'';
            $req_data['start_time'] = (!empty($request->start_time))?$request->start_time:'';
            $req_data['status'] = (!empty($request->status))?$request->status:'';
            if(!empty($page) && count(array($page)) > 0){
                $isSaved = CabsSightseeing::where('id', $page->id)->update($req_data);
                $data_id = $page->id;
                $msg = "Cab Sightseeing has been updated successfully.";
            } else {
                $isSaved = CabsSightseeing::create($req_data);
                $data_id = $isSaved->id;
                $msg = "Cab Sightseeing has been added successfully.";
            }

            if ($isSaved) {

                CabsSightseeing::updateCabsSightseeingPrice($id);

                $new_data = DB::table('cabs_sightseeing')->where('id',$data_id)->first();
                $name =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $data_id;
                $module_desc = $name;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Cab Sightseeing';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(route($this->ADMIN_ROUTE_NAME.'.cabs.sightseeing'))->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Cab sightseeing could not be added, please try again or contact the administrator.');
            }
        }

        $data = [];
        $data["page_heading"] = "Cab Sightseeing";
        $data['cab_route'] = $page;
        $data['id'] = $id;
        $data['cab_route_groups'] = [];//CabRouteGroup::where('status',1)->get();
        $data['cab_cities'] = CabsCities::where('status',1)->get();
        return view('admin.cabs.sightseeing_form', $data);
    }

    public function sightseeing_view(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $cab_route = "";
        $title = "Cab Sightseeing";
        if (is_numeric($id) && $id > 0) {
            $cab_route = CabsSightseeing::where("id", $id)->first();
            $title = "Cab Sightseeing";
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["cab_route"] = $cab_route;
        $data["id"] = $id;
        return view("admin.cabs.sightseeing_view", $data);
    }
    // Cabs Sightseeing End


    // Cabs Inventory Start
    public function inventory(Request $request) {
        $data = [];
        $city_id = $request->city_id??'';
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

        $data['cities'] = CabsCities::where('status',1)->orderBy('id','desc')->get();

        $cities_query = CabsCities::where('status',1)->orderBy('id','desc');
        if($city_id){
            $cities_query->where('id',$city_id);
        }
        $cityData = $cities_query->first();
        // prd($cityData->cabsCityPrices->toArray());
        $data['cityData'] = $cityData;
        $city_id = $cityData->id??'';
        $data['city_id'] = $city_id??'';
        $whereDate = [];
        foreach($period as $dt){
            $date =  $dt->format("d-m-Y");
            $whereDate[] =  $dt->format("Y-m-d");
        }
        $cabsInventory = CabsInventory::where('city_id',$city_id)->whereIn('trip_date',$whereDate)->get();
        $data['cabsInventories'] = $cabsInventory;
        $data["page_heading"] = "Cab Inventory";
        return view("admin.cabs.inventory", $data);
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

            $cities_query = CabsCities::where('status',1)->orderBy('id','desc');
            if($city_id){
                $cities_query->where('id',$city_id);
            }
            $cityData = $cities_query->first();
            $data['cityData'] = $cityData;
            $city_id = $cityData->id ?? '';
            $data['city_id'] = $city_id ?? '';
            $whereDate = [];
            foreach($period as $dt){
                $date =  $dt->format("d-m-Y");
                $whereDate[] =  $dt->format("Y-m-d");
            }
            $cabsInventories = CabsInventory::where('city_id',$city_id)->whereIn('trip_date',$whereDate)->get();

            $data['cabsInventories'] = $cabsInventories;
            $view_html = view("admin.cabs.inventory_form",$data)->render();
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
        $isSaved =0;
        if( ($request->method() == 'POST' || $request->method() == 'post')){
            $req_data = [];
            foreach ($inventories as $key => $cab_data) {
                $date = $key ;
                $trip_date = date("Y-m-d", strtotime($date));

                foreach ($cab_data as $cab_key => $cab_inventory) {
                    $cab_id = $cab_key;
                    $req_data = array(
                        'city_id' => $city_id,
                        'cab_id' => $cab_id,
                        'trip_date' => $trip_date,
                        'inventory' => $cab_inventory??0,
                        'status' => 1,
                    );
                    $is_data = CabsInventory::where('city_id',$city_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->first();
                    if($is_data) {
                        $isSaved = CabsInventory::where('city_id',$city_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->update($req_data);
                        if($isSaved) {
                            $success = true;
                            $statusCode = 200;                                
                        }
                    } else {
                        $isSaved = CabsInventory::create($req_data);
                        if($isSaved) {
                            $success = true;
                            $statusCode = 200;                                
                        }
                    }                
                }
            }
            if($success) {
                $before_days = 15;
                $date = strtotime(date('Y-m-d')); 
                $before_date = date('Y-m-d',strtotime('-15 days',$date));
                $is_deleted = CabsInventory::where('city_id',$city_id)->whereDate('trip_date','<',$before_date)->delete();
                $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Inventory Updated Successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Inventory not updated, please try again.</div>';
            }
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);
    }

    public function bulk_inventory(Request $request) {
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;
        $city_id = $request->city_id??'';
        $bulkdaterange = $request->bulkdaterange??'';
        $cab_inventory = $request->cab_inventory??'';
        if(!empty($city_id) && !empty($bulkdaterange) && !empty($cab_inventory)) {
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
                foreach($period as $dt) {
                    $date = $dt->format("d-m-Y");
                    $trip_date = $dt->format("Y-m-d");
                    if($cab_inventory) {
                        foreach($cab_inventory as $cab_id => $val) {
                            if($val != '') {
                                $req_data = [
                                    'city_id' => $city_id,
                                    'cab_id' => $cab_id,
                                    'trip_date' => $trip_date,
                                    'inventory' => $val,
                                    'status' => 1,
                                ];

                                $record = CabsInventory::where('city_id',$city_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->first();
                                if($record) {
                                    $isSaved = CabsInventory::where("id", $record->id)->update($req_data);
                                    if($isSaved) {
                                        $success = true;
                                        $statusCode = 200;
                                    }
                                } else {
                                    $isSaved = CabsInventory::create($req_data);
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
            $is_deleted = CabsInventory::where('city_id',$city_id)->whereDate('trip_date','<',$before_date)->delete();
            $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Inventory Updated Successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Inventory not updated, please try again.</div>';
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);
    }
    // Cabs Inventory End

    // Cabs Price Start
    public function price(Request $request) {
        $data = [];
        $city_id = $request->city_id ?? '';
        $start_date = date('Y-m-d');
        $end_date =  date('Y-m-d', strtotime($start_date. ' + 10 days'));
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $pre_date =  date('Y-m-d', strtotime($start_date. ' - 10 days'));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $data['city_id'] = $city_id;
        $data['pre_date'] = $pre_date;
        $data['start_date'] = $start_date;
        $data['last_date'] = $end_date;
        $data['period'] = $period;
        $data['start_date'] = $start_date;
        $data['cities'] = CabsCities::where('status',1)->orderBy('id','desc')->get();

        $cities_query = CabsCities::where('status',1)->orderBy('id','desc');
        if($city_id){
            $cities_query->where('id',$city_id);
        }
        $cityData = $cities_query->first();
        $data['cityData'] = $cityData;
        $data['city_id'] = $cityData->id??'';
        $whereDate = [];
        foreach($period as $dt){
            $date =  $dt->format("d-m-Y");
            $whereDate[] =  $dt->format("Y-m-d");
        }
        $cabsPrices = CabsCityPrice::where('city_id',$city_id)->get();
        $data['cabsPrices'] = $cabsPrices;
        $data["page_heading"] = "Cab Price";
        return view("admin.cabs.price", $data);
    }

    public function allPrice(Request $request) {
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;
        $data = [];
        $city_id = $request->city_id??'';
        if(!empty($city_id) && is_numeric($city_id)) {
            $cities_query = CabsCities::where('status',1)->orderBy("sort_order", "asc");
            $cities_query->where(function($query){
                $query->where('is_deleted', 0);
                $query->orWhereNull('is_deleted');
            });
            if($city_id){
                $cities_query->where('id',$city_id);
            }
            $cityData = $cities_query->first();
            $data['cityData'] = $cityData;
            $city_id = $cityData->id ?? '';
            $data['city_id'] = $city_id ?? '';
            $cabsPrices = CabsCityPrice::where('city_id',$city_id)->get();
            $data['cabsPrices'] = $cabsPrices;
            $view_html = view("admin.cabs.price_form",$data)->render();
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
            foreach ($price as $cab_id => $cab_price) {
                $cabsCityPrice = CabsCityPrice::where('city_id',$city_id)->where('cab_id',$cab_id)->first();
                if($cabsCityPrice) {
                    $cabsCityPrice->price = $cab_price??0;
                    $isSaved = $cabsCityPrice->save();
                    if($isSaved) {
                        $success = true;
                        $statusCode = 200;
                    }
                } else {
                    $req_data = [
                        'city_id' => $city_id,
                        'cab_id' => $cab_id,
                        'price' => $cab_price??0,
                    ];
                    $isSaved = CabsCityPrice::create($req_data);
                    if($isSaved) {
                        $success = true;
                        $statusCode = 200;                                
                    }
                } 
            }
            if($success) {
                $min_price = 0;
                if($price) {
                    $min_price = min($price);
                }
                $search_price = $min_price??0;
                $publish_price = $min_price??0;
                CabsSightseeing::where('origin',$city_id)->update(['search_price'=>$search_price,'publish_price'=>$publish_price]);
                
                $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Price Updated Successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Price not updated, please try again.</div>';
            }
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);
    }
    // Cabs Price End

    /* end of controller */
}
