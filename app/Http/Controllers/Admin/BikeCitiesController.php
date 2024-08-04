<?php
namespace App\Http\Controllers\Admin;
use App\Models\BikeCities;
use App\Models\BikeMaster;
use App\Models\State;
use App\Models\AgentGroup;
use App\Models\ModuleDiscountCategory;
use App\Models\DiscountModuleToGroup;
use App\Models\CustomModuleRecordDiscount;
use App\Models\BikecityPrice;
use App\Models\BikeCityLocation;
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
use Carbon\Carbon as Carbon;

class BikeCitiesController extends Controller {

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
        $status = isset($request->status) ? $request->status :1;
        $bike = (isset($request->bike))?$request->bike:'';
        $bikeCityQuery = BikeCities::orderBy("sort_order", "asc");
        $bikeCityQuery->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        if (!empty($name)) {
            $bikeCityQuery->where("name", "like", "%" . $name . "%");
        }
        if (strlen($status) > 0) {
            $bikeCityQuery->where("status", $status);
        }
        if($request->bike) {
            if(is_array($request->bike)) {
                $module_ids = $request->bike??[];
                if(!empty($module_ids)) {
                    $bikeCityQuery->where(function($q1) use($module_ids) {
                        $q1->whereJsonContains('bike', $module_ids);
                    });
                }
            }
        }
        $bikeCities = $bikeCityQuery->paginate($limit);
        $data["bikes"] = BikeMaster::where('status',1)->get();
        $data["bikeCities"] = $bikeCities;
        return view("admin.bike_cities.index", $data);
    }

    public function add(Request $request) {

        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $bikeCityQuery = [];
        $title = "Add City";
        $old_bike_ids = [];
        if (is_numeric($id) && $id > 0) {
            $bikeCityQuery = BikeCities::find($id);
            $title = "Edit (" . $bikeCityQuery->name. " )";
            if($bikeCityQuery->bike) {
                $old_bike_ids = json_decode($bikeCityQuery->bike, true);
            }
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules = [];
            if (is_numeric($id) && $id > 0) {
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
            }else{
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u|unique:bike_cities|max:100';
            }
            $rules["bike"] = "required";
            $rules["km_included"] = "required";
            $validation_msg = [];
            $validation_msg["name.required"] = "City name is required.";
            $validation_msg["name.regex"] = "City name format is Invalid.";
            $validation_msg["name.unique"] = "City name must be unique.";
            $validation_msg["bike.required"] = "Bike is required.";
            $validation_msg["km_included.required"] = "KM included is required.";
            $this->validate($request, $rules, $validation_msg);

            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['status'] = $request->status??'0';
            $req_data['sort_order'] = $request->sort_order??0;
            $req_data['bike'] = isset($request->bike) ? json_encode($request->bike) : '';
            $req_data['inclusions'] = $request->inclusions??'';
            $req_data['exclusions'] = $request->exclusions??'';
            $req_data['km_included'] = $request->km_included??'';

            if (!empty($bikeCityQuery) && $bikeCityQuery->id == $id) {
                $isSaved = BikeCities::where("id",$bikeCityQuery->id)->update($req_data);
                $bike_cities_id = $bikeCityQuery->id;
                $msg = "Bike City has been updated successfully.";
            } else {
                $isSaved = BikeCities::create($req_data);
                $bike_cities_id = $isSaved->id;
                $msg = "Bike City has been added successfully.";
            }

            if ($isSaved) {
                $insert_bike_ids = [];
                if($request->bike) {
                    $new_bike_ids = $request->bike??[];
                    if(is_array($new_bike_ids)) {
                        foreach($new_bike_ids as $new_bike_id) {
                            if(!empty($old_bike_ids) && in_array($new_bike_id, $old_bike_ids)){
                                $key = array_search($new_bike_id, $old_bike_ids);
                                if($key !== NULL) {
                                    unset($old_bike_ids[$key]);
                                }
                            } else {
                                $insert_bike_ids[] = $new_bike_id;
                            }
                        }
                    }
                    if(!empty($insert_bike_ids) && count($insert_bike_ids) > 0) {
                        foreach($insert_bike_ids as $insert_bike_id) {
                            $insert_bike_price = [
                                'city_id' => $bike_cities_id,
                                'bike_id' => $insert_bike_id,
                                'price' => 0,
                                'round_trip_price' => 0,
                            ];
                            BikecityPrice::create($insert_bike_price);
                        }
                    }
                    if(!empty($old_bike_ids) && count($old_bike_ids) > 0) {
                        BikecityPrice::where('city_id',$bike_cities_id)->whereIn('bike_id',$old_bike_ids)->delete();
                    }
                }


                cache()->forget("bike_cities");
                $new_data = DB::table('bike_cities')->where('id',$bike_cities_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $bike_cities_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Bike City';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);
                return redirect(url($this->ADMIN_ROUTE_NAME ."/bike_cities"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Bike City could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["bikeCityQuery"] = $bikeCityQuery;
        $data["states"] = State::where('status',1)->get();
        $data["bikes"] = BikeMaster::where('status',1)->get();
        $data["id"] = $id;
        return view("admin.bike_cities.form", $data);
    }

    public function changeRentalDefault(Request $request)
    {

        $defailt_data = array('is_default' => 0,);
        $is_updated = BikeCities::where('id','>',0)->update($defailt_data);
        $rentalData = BikeCities::find($request->id);
        $rentalData->is_default = $request->is_default;
        $rentalData->save();

        return response()->json([
            "success" => "Rental default successfully.",
        ]);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $bikeCityQuery = [];
        $title = "Bike City Details";
        if (is_numeric($id) && $id > 0) {
            $bikeCityQuery = BikeCities::where("id", $id)->first();
            $title = "Bike City Details (".$bikeCityQuery->name.")";
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["bikeCityQuery"] = $bikeCityQuery??[];
        $data["id"] = $id;
        return view("admin.bike_cities.view", $data);
    }

    public function delete(Request $request){
    $id = isset($request->id)?$request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $method = $request->method();
    $is_deleted = false;
    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {
                $bikeCityQuery = BikeCities::find($id);
                $bikeCityPrice = BikecityPrice::where('city_id',$id)->count();

                if($bikeCityPrice > 0){
                     $is_deleted = false ;
                }else{

                $new_data = DB::table('bike_cities')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_delete = BikeCities::bikeCityDelete($id);
                    if ($is_delete['status'] != 'ok') {
                        return redirect(url('admin/bike_cities'))->with('alert-danger', $is_delete['message']);
                    } else {
                        $is_deleted = true;
                    }
                }
            }
        }

        if ($is_deleted) {
        //=============Activity Logs=====================
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
        //=============Activity Logs=====================
        return redirect(url($this->ADMIN_ROUTE_NAME . "/bike_cities"))->with("alert-success", "Bike City has been deleted successfully.");
    } else {

        return redirect(url($this->ADMIN_ROUTE_NAME . "/bike_cities"))->with("alert-danger","Bike City cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function locations(Request $request) {
        $data = [];
        $bike_city_id = $request->id??0;
        if($bike_city_id) {
            $data['bike_city'] = BikeCities::find($bike_city_id);
            return view("admin.bike_cities.locations", $data);
        } else {
            abort(404);
        }
    }

    public function locations_add(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $bike_city_id = $request->bike_city_id??0;
        $id = $request->data_id??0;
        if($bike_city_id) {
            $nicknames = [];
            $nicknames['name'] = 'Location Name';
            $nicknames['status'] = 'Status';
            $rules = [];
            $rules['name'] = 'required|unique:bike_city_locations';
            if($id > 0){
                $rules['name'] = 'required';
            }
            $rules['sort_order'] = 'nullable:numeric';
            $rules['status'] = 'required';
            $validation_msg['required'] = ':attribute is required.';
            $validation_msg['unique'] = ':attribute must be unique.';
            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
            if ($validator->fails()) {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ), 400); // 400 being the HTTP code for an invalid request.
            } else {
                $req_data = [
                    'name' => $request->name??'',
                    'city_id' => $bike_city_id??'',
                    'sort_order' => $request->sort_order??'0',
                    'status' => $request->status??'0',
                ];
                $record = BikeCityLocation::where('id', $id)->where('city_id', $bike_city_id)->first();
                if (!empty($record) && $record->id == $id) {
                    $isSaved = BikeCityLocation::where("id",$record->id)->update($req_data);
                    $bike_cities_id = $record->id;
                    $response['message'] = "Location has been updated successfully.";
                    $response['success'] = true;
                } else {
                    $req_data["slug"] = CustomHelper::GetSlug('bike_city_locations', 'id', $id, $request->name);
                    $isSaved = BikeCityLocation::create($req_data);
                    $bike_cities_id = $isSaved->id;
                    $response['message'] = "Location has been added successfully.";
                    $response['success'] = true;
                }

                $bike_city = BikeCities::find($bike_city_id);
                $data['bike_city'] = $bike_city;
                $htmlData = view("admin.bike_cities._locations_table", $data)->render();
                $response['htmlData'] = $htmlData;
                $response['location_count'] = $bike_city->locations->count()??0;
                return response()->json($response);
            }
        } else {
            abort(404);
        }
    }

    public function locations_get(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $data_id = $request->data_id??0;
        if($data_id) {
            $location = BikeCityLocation::find($data_id);
            if($location) {
                $response['location'] = $location->toArray();
                $response['success'] = true;
            } else {
                $response['message'] = 'Something went wrong, please try again.';
            }
        } else {
            $response['message'] = 'Something went wrong, please try again.';
        }
        return response()->json($response);
    }


    public function location_delete(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $data_id = $request->data_id??0;
        $bike_city_id = $request->bike_city_id??0;
        if($data_id) {
            $location = BikeCityLocation::find($data_id);
            if($location) {
                $location->delete();
                $bike_city = BikeCities::find($bike_city_id);
                $data['bike_city'] = $bike_city;
                $htmlData = view("admin.bike_cities._locations_table", $data)->render();
                $response['htmlData'] = $htmlData;
                $response['location_count'] = $bike_city->locations->count()??0;
                $response['message'] = 'Location has been deleted successfully.';
                $response['success'] = true;
            } else {
                $response['message'] = 'Something went wrong, please try again.';
            }
        } else {
            $response['message'] = 'Something went wrong, please try again.';
        }
        return response()->json($response);
    }


    //==========

    // agent_price
    public function agent_price(Request $request) {
        $data = [];
        $limit = $this->limit;
        $id = isset($request->id) ? $request->id : "";
        $day_title = (isset($request->day_title))?$request->day_title:'';
        $status = (isset($request->status))?$request->status:'';
        if(!empty($id)) {
            $BikeCities = BikeCities::find($id);    
            if($BikeCities) {
                $module_name = 'rental';
                $module_record_id = $BikeCities->id;
               
                $discount_category_id = isset($BikeCities->discount_category_id)?$BikeCities->discount_category_id : null;
                $discount_category_data = [];
                $is_default_category = 0;
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
                $data["heading"] = 'Agent Price ('.$BikeCities->name.')';
                $data["city_id"] = $id;

                //=======

              
                $data['group_datas'] = [];

                //=======
                $CabRoutePrice = BikecityPrice::where('city_id',$id)->get();

                $data['BikeCity'] = $BikeCities;            
                $data['CabRoutePrices'] = $CabRoutePrice;            
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
                return view("admin.bike_cities.agent_price", $data);
            }
        }
        abort(404);
    }

    // add_agent_price
    public function add_agent_price(Request $request)
    {
        $data = [];
        $package_query = [];
        $limit = $this->limit;
        $id = isset($request->id) ? $request->id : "";
        $discount_category = isset($request->discount_category) ? $request->discount_category : null;
        $bikeCity = BikeCities::find($id);
        if(!empty($id)){
            $bikeCity->discount_category_id = $discount_category;
            $is_updated = $bikeCity->save();
            $response['success'] = true;
            return response()->json($response);
        }
        abort(404);
    }

    //=========================
/* end of controller */
}
