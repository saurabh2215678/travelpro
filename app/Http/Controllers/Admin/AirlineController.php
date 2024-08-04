<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentGroup;
use App\Models\AirlineMarkup;
use App\Models\AirlineDc;
use App\Models\AirlineMaster;
use App\Models\AirlineRouteInventory;
use App\Models\AirlineRouteSegment;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\OrderPayments;
use App\Models\OrderTraveller;
use App\Models\UserWallet;
use App\Helpers\CustomHelper;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Storage;
use Validator;
use DB;

class AirlineController extends Controller {

    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct() {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request){
        $data = [];
        $limit = 50;
        $status = (isset($request->status))?$request->status:'';
        $query = AirlineMaster::orderBy('featured','desc')->orderBy('sort_order','asc')->orderBy('name','asc');
        if($request->name) {
            $name = $request->name;
            $query->where(function($q1) use($name) {
                $q1->where('code','like','%'.$name.'%');
                $q1->orWhere('name','like','%'.$name.'%');
            });
        }
        if($request->featured != '') {
            $query->where('featured', $request->featured);
        }
        if( strlen($status) > 0 ) {
            $query->where('status', $status);
        } else {
            $query->where('status', 1);
        }
        $records = $query->paginate($limit);
        $data['records'] = $records;
        return view('admin.airline.index', $data);
    }

    public function add(Request $request) {
        $id = (isset($request->id))?$request->id:0;
        $record = '';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $title = 'Add Airline Master';

        if(is_numeric($id) && $id > 0){
            $record = AirlineMaster::find($id);
            $title = 'Edit Airline Master';
        }
        if($request->method() == 'POST' || $request->method() == 'post') {
            $validation_msg = [];
            $rules = [];
            $nicknames = [
                'name' => 'Name',
                'image' => 'Image',
                'code' => 'Code',
                'sort_order' => 'Sort Order',
                'featured' => 'Featured',
                'status' => 'Status',
            ];

            $rules['name'] = 'required';
            $rules['code'] = 'required|min:2|max:2';
            $rules['sort_order'] = 'nullable|numeric';
            $rules['featured'] = 'nullable';
            $rules['status'] = 'required';

            $validation_msg['required'] = ':attribute is required.';
            $validation_msg['digits'] = ':attribute must be :digits digits.';
            $validation_msg['min'] = ':attribute should be minimum :min character.';
            $validation_msg['max'] = ':attribute should not be greater than :max character.';

            $this->validate($request, $rules, $validation_msg, $nicknames);
            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['code'] = ucwords($request->code)??'';
            $req_data['sort_order'] = $request->sort_order??'';
            $req_data['featured'] = $request->featured??'';
            $req_data['status'] = $request->status??'';

            if(!empty($id)) {
                $isSaved = AirlineMaster::where('id', $id)->update($req_data);
                $msg = "The Airline Master has been updated successfully.";
                $row_id = $id;
            } else {
                $isSaved = AirlineMaster::create($req_data);
                if($isSaved) {
                    $msg = "The Airline Master has been added successfully.";
                    $row_id = $isSaved->id;
                }
            }

            if ($isSaved) {
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImage($file, $row_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }

                $new_data = DB::table('airline_masters')->where('id',$row_id)->first();
                $module_desc = !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $row_id;
                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'AirlineMaster';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                if($request->back_url) {
                    $back_url = $request->back_url;
                } else {
                    $back_url = route($this->ADMIN_ROUTE_NAME.'.airline.index');
                }
                return redirect($back_url)->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Airline Master cannot be added, please try again or contact the administrator.');
            }
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['record'] = $record;
        $data['id'] = $id;
        return view('admin.airline.form', $data);
    }

    public function saveImage($file, $id, $type) {
        $result["org_name"] = "";
        $result["file_name"] = "";
        if ($file) {
            $path = "airlines/";
            $thumb_path = "airlines/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("PACKAGE_AIRLINE_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("PACKAGE_AIRLINE_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("PACKAGE_AIRLINE_IMG_THUMB_HEIGHT");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("PACKAGE_AIRLINE_IMG_THUMB_WIDTH");

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
                    $record = AirlineMaster::find($id);

                    if ($record && $record->id) {
                        $storage = Storage::disk("public");
                        if ($type == "image") {
                            $old_image = $record->image;
                            $record->image = $new_image;
                        } elseif ($type == "banner_image") {
                            $old_image = $record->banner_image;
                            $record->banner_image = $new_image;
                        }

                        $isUpdated = $record->save();

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
        $result["msg"] ='<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        $image_id = $request->has("image_id") ? $request->image_id : 0;
        $type = $request->has("type") ? $request->type : "image";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_image($image_id, $type);
            if ($is_img_deleted) {
                $result["success"] = true;
                $result["msg"] ='<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
            }
        }
        return response()->json($result);
    }

    public function delete_image($id, $type) {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "airlines/";
        $thumb_path = "airlines/thumb/";
        $record = AirlineMaster::find($id);

        $image = isset($record->image) ? $record->image : "";
        $banner_image = isset($record->banner_image) ? $record->banner_image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($thumb_path.$image)) {
                $is_deleted = $storage->delete($thumb_path.$image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }
            if ($is_deleted) {
                if ($type == "image") {
                    $record->image = "";
                }
                $is_updated = $record->save();
            }
        }
        else if ($type == "banner_image") {
            if (!empty($banner_image) && $storage->exists($path . "thumb/" . $banner_image))
            {
                $is_deleted = $storage->delete($path . "thumb/" . $banner_image);
            }

            if (!empty($banner_image) && $storage->exists($path . $banner_image)) {
                $is_deleted = $storage->delete($path . $banner_image);
            }

            if ($is_deleted) {
                if ($type == "banner_image") {
                    $record->banner_image = "";
                }
                $is_updated = $package->save();
            }
        }

        return $is_updated;
    }

    public function delete(Request $request) {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if($method == "POST") {
            if(is_numeric($id) && $id > 0) {
                $record = AirlineMaster::find($id);
                if(!empty($record)) {
                    $is_deleted = $record->delete();
                }
            }
        }

        if($is_deleted) {
            return redirect(route($this->ADMIN_ROUTE_NAME.'.airline.index'))->with('alert-success', 'The Airline Master has been deleted successfully.');
        } else {
            return redirect(route($this->ADMIN_ROUTE_NAME.'airline.index'))->with('alert-danger', 'The Airline Master cannot be deleted, please try again or contact the administrator.');
        }
    }

    public function offline_inventory(Request $request) {
        $data = [];
        $limit = 30;
        $status = ($request->has('status'))?$request->status:'';
        $type = $request->type??'';
        $route_id = $request->route_id??'';
        $inventory_id = $request->inventory_id??'';

        $title = '';
        $query = AirlineRouteInventory::orderBy('start_date','asc')->orderBy('id','desc');
        if($inventory_id) {
            $query->where('id', $inventory_id);
        } else {
            if($type == 'agent') {
                $query->where('user_id','!=',0);
                $title = 'Offline Flight Agent Inventory';
            } else {
                $query->where('user_id',0);
                $title = 'Offline Flight Admin Inventory';
            }
        }
        if($request->name) {
            $name = $request->name;
            $query->where(function($q1) use($name) {
                $q1->whereHas('userData',function($q1) use($name) {
                    $q1->where('name','like','%'.$name.'%');
                });
                $q1->orWhereHas('routeData',function($q2) use($name) {
                    $q2->where('source',$name);
                    $q2->orWhere('destination',$name);
                    $q2->orWhere(DB::raw("CONCAT(source,' / ',destination)"),'like','%'.$name.'%');
                    $q2->orWhere(DB::raw("CONCAT(airline,'-',flight_number)"),'like','%'.$name.'%');
                });
                $q1->orWhere('pnr','like','%'.$name.'%');
            });
        }
        if($route_id) {
            $query->where('airline_route_id', $route_id);
        }
        if($status != '') {
            $query->where('status', $status);
        }
        $from_date = '';
        $to_date = '';      
        $range = $request->range;
        if(!empty($range) && $range != 'custom') {
            $date_between_arr = CustomHelper::get_to_from_date($range);
            $from_date = $date_between_arr['from']??'';
            $to_date = $date_between_arr['to']??'';
        } else {
            $from = $request->from??date('d/m/Y');
            $to = $request->to??'';
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
        if(empty($inventory_id)) {
            if(!empty($from_date)) {
                $query->whereDate('start_date','>=',$from_date);
            }
            if(!empty($to_date)) {
                $query->whereDate('start_date','<=',$to_date);
            }
        }
        $records = $query->paginate($limit);
        if($inventory_id && $records->count() > 0) {
            if($records[0]->user_id) {
                $type = 'agent';
            } else {
                $type = '';
            }
        }
        $data['records'] = $records;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['page_heading'] = $title;
        $data['status'] = $status;
        $data['type'] = $type;
        $data['route_id'] = $route_id;
        return view('admin.airline.offline_inventory', $data);
    }

    public function offline_inventory_add(Request $request) {
        $data = [];
        $id = (isset($request->id))?$request->id:0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $title = 'Add Flight Inventory';

        $record = [];
        if(is_numeric($id) && $id > 0){
            $record = AirlineRouteInventory::where('id',$id)->first();//->where('user_id',0)
            if($record && $record->id) {
                $title = 'Edit Flight Inventory';

                if($record->fare_type=='2') {
                    $fareIdentifier = 'IIAIR_OFFER_FARE_WITH_PNR';
                } else {
                    $fareIdentifier = 'IIAIR_OFFER_FARE_WITHOUT_PNR';
                }

                $searchQuery = [];
                $routeData = $record->routeData??[];
                $trip_type = $routeData->trip_type??'Domestic';            
                if($trip_type == 'Domestic') {
                    $searchQuery['isDomestic'] = 1;
                } else {
                    $searchQuery['isDomestic'] = 0;
                }
                $paxInfo = [
                    'ADULT' => 1,
                    'CHILD' => 0,
                    'INFANT' => 0,
                ];
                $searchQuery['paxInfo'] = $paxInfo;
                $admin_markup = 0;
                $admin_markup_details = CustomHelper::getAdminMarkupDetails($record->admin_adult_price,$searchQuery,1,$fareIdentifier);
                if(!empty($admin_markup_details) && isset($admin_markup_details['markup_price']) && $admin_markup_details['markup_price'] > 0) {
                    $admin_markup = $admin_markup_details['markup_price'];
                }
                $data['admin_markup'] = $admin_markup;
            } else {
                abort(404);
            }            
        }
        if($request->method() == 'POST') {
            $request_data = $request->toArray();
            // prd($request_data);
            $nicknames = [
                'fare_type' => 'Fare Type',
                'airline_route_id' => 'Flight Route',
                'available_for' => 'Available For',
                'p_agents' => 'Available Agents',
                'flight_class' => 'Flight Class',
                'start_date' => 'Departure Date',
                // 'end_date' => 'End Date',
                'initial_inventory' => 'Tickets In Stock',
                'inventory' => 'Seats Available',
                'pnr' => 'PNR',
                'airline_ticket_no' => 'E-Ticket',
                'agent_adult_price' => 'Actual Adult Price',
                'agent_child_price' => 'Actual Child Price',
                'agent_infant_price' => 'Actual Infant Price',
                'adult_price' => 'Selling Adult Price',
                'child_price' => 'Selling Child Price',
                'infant_price' => 'Selling Infant Price',
                'admin_adult_price' => 'Admin Adult Price',
                'admin_child_price' => 'Admin Child Price',
                'admin_infant_price' => 'Admin Infant Price',
                'markup' => 'Markup',
                'is_refundable' => 'Is Refundable',
                'remark' => 'Remark',
            ];
            $rules = [];
            $validation_msg = [];
            $fare_type = $request->fare_type??0;
            $rules['fare_type'] = 'required';
            $rules['airline_route_id'] = 'required';
            $rules['available_for'] = 'required';
            $rules['flight_class'] = 'required';
            $rules['start_date'] = 'required';
            // $rules['end_date'] = 'required';
            $rules['initial_inventory'] = 'required|numeric';
            $rules['inventory'] = 'required|numeric';
            if($fare_type==2) {
                $rules['pnr'] = 'required';
            } else {
                $rules['pnr'] = 'nullable';
            }
            $rules['airline_ticket_no'] = 'nullable';
            if($fare_type==2) {
                $rules['agent_adult_price'] = 'required|numeric';
                $rules['agent_child_price'] = 'nullable|numeric';
                $rules['agent_infant_price'] = 'nullable|numeric';
                $rules['adult_price'] = 'required|numeric';
                $rules['child_price'] = 'nullable|numeric';
                $rules['infant_price'] = 'nullable|numeric';
            } else {
                $rules['admin_adult_price'] = 'required|numeric';
                $rules['admin_child_price'] = 'nullable|numeric';
                $rules['admin_infant_price'] = 'nullable|numeric';
            }
            $rules['markup'] = 'nullable|numeric';
            $rules['remark'] = 'nullable';
            if($request->available_for == 'p_agents') {
                $rules['p_agents'] = 'required';
            }

            $validation_msg['required'] = ':Attribute is required';

            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
            $validator->after(function($validator) use ($request, $fare_type){
                // $start_date = CustomHelper::DateFormat($request->start_date,'Y-m-d','d/m/Y');
                // $end_date = CustomHelper::DateFormat($request->end_date,'Y-m-d','d/m/Y');
                // if(strtotime($start_date) > strtotime($end_date)) {
                //     $validator->errors()->add('start_date', 'Start Date should be less than end date');
                // }
                if($request->inventory > $request->initial_inventory) {
                    $validator->errors()->add('inventory', 'Seats Available should be less than Tickets In Stock');
                }
                if($fare_type==2 && 1==2) { // Allow the selling price to be less than actual
                    if($request->agent_adult_price > $request->adult_price) {
                        $validator->errors()->add('agent_adult_price', 'Actual Adult Price should be less than Selling Adult Price');
                    }
                    if($request->agent_child_price > $request->child_price) {
                        $validator->errors()->add('agent_child_price', 'Actual Child Price should be less than Selling Child Price');
                    }
                    if($request->agent_infant_price > $request->infant_price) {
                        $validator->errors()->add('agent_infant_price', 'Actual Infant Price should be less than Selling Infant Price');
                    }
                    if($request->adult_price > $request->admin_adult_price) {
                        $validator->errors()->add('adult_price', 'Selling Adult Price should be less than Admin Adult Price');
                    }
                    if($request->child_price > $request->admin_child_price) {
                        $validator->errors()->add('child_price', 'Selling Child Price should be less than Admin Child Price');
                    }
                    if($request->infant_price > $request->admin_infant_price) {
                        $validator->errors()->add('infant_price', 'Selling Infant Price should be less than Admin Infant Price');
                    }
                }
            });
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $start_date = NULL;
                if($request->start_date) {
                    $start_date = CustomHelper::DateFormat($request->start_date,'Y-m-d','d/m/Y');
                }
                $end_date = NULL;
                if($request->end_date) {
                    $end_date = CustomHelper::DateFormat($request->end_date,'Y-m-d','d/m/Y');
                }
                $p_agents_str = '';
                if($request->p_agents && $request->available_for == 'p_agents') {
                    $p_agents_str = implode(',', $request->p_agents);
                }
                $req_data = [
                    'airline_route_id' => $request->airline_route_id??0,
                    'available_for' => $request->available_for??'',
                    'p_agents' => $p_agents_str??'',
                    'flight_class' => $request->flight_class??'',
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'initial_inventory' => $request->initial_inventory??0,
                    'inventory' => $request->inventory??0,
                    'pnr' => $request->pnr??'',
                    'airline_ticket_no' => $request->airline_ticket_no??'',
                    'admin_adult_price' => $request->admin_adult_price??0,
                    'admin_child_price' => $request->admin_child_price??0,
                    'admin_infant_price' => $request->admin_infant_price??0,
                    'is_refundable' => $request->is_refundable??0,
                    'remark' => $request->remark??'',
                    'status' => $request->status??0
                ];
                if($request->markup) {
                    $req_data['markup'] = $request->markup??0;
                    $req_data['markup_type'] = 1;
                }
                if($fare_type==2) {
                    $req_data['agent_adult_price'] = $request->agent_adult_price??0;
                    $req_data['agent_child_price'] = $request->agent_child_price??0;
                    $req_data['agent_infant_price'] = $request->agent_infant_price??0;
                    $req_data['adult_price'] = $request->adult_price??0;
                    $req_data['child_price'] = $request->child_price??0;
                    $req_data['infant_price'] = $request->infant_price??0;
                } else {
                    $req_data['agent_adult_price'] = $request->admin_adult_price??0;
                    $req_data['agent_child_price'] = $request->admin_child_price??0;
                    $req_data['agent_infant_price'] = $request->admin_infant_price??0;
                    $req_data['adult_price'] = $request->admin_adult_price??0;
                    $req_data['child_price'] = $request->admin_child_price??0;
                    $req_data['infant_price'] = $request->admin_infant_price??0;
                }

                $msg = '';
                if($record && $record->id == $id) {
                    $isSaved = AirlineRouteInventory::where('id',$id)->update($req_data);
                    if($isSaved) {
                        $msg = 'Flight inventory has been updated successfully.';
                        $row_id = $id;
                    }
                } else {
                    $req_data['user_id'] = 0;
                    $req_data['fare_type'] = $request->fare_type??'';
                    $isSaved = AirlineRouteInventory::create($req_data);
                    if($isSaved) {
                        $msg = 'Flight inventory has been added successfully.';
                        $row_id = $isSaved->id;
                    }
                }
                if($isSaved) {

                    $new_data = DB::table('airline_route_inventory')->where('id',$row_id)->first();
                    $module_desc = !empty($new_data->name)?$new_data->name:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $module_id = $row_id;
                    $params = [];
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'AirlineRouteInventory';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = "";
                    $params['sub_module_id'] = 0;
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = (!empty($id)) ? "Update" : "Add";
                    CustomHelper::RecordActivityLog($params);

                    if($request->back_url) {
                        $back_url = $request->back_url;
                    } else {
                        $back_url = route($this->ADMIN_ROUTE_NAME.'.airline.offline_inventory');
                    }
                    return redirect($back_url)->with('alert-success', $msg);
                } else {
                    return back()->with('alert-danger', 'Something went wrong, please try again...');
                }
            }
        }
        $data['page_heading'] = $title;
        $data['record'] = $record;
        $data['id'] = $id;
        return view('admin.airline.offline_inventory_form', $data);
    }

    public function offline_inventory_view(Request $request) {
        $data = [];
        $id = (isset($request->id))?$request->id:0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $record = [];
        if(is_numeric($id) && $id > 0){
            $record = AirlineRouteInventory::find($id);
            $title = 'View Flight Inventory';
            if($record->fare_type=='2') {
                $fareIdentifier = 'IIAIR_OFFER_FARE_WITH_PNR';
            } else {
                $fareIdentifier = 'IIAIR_OFFER_FARE_WITHOUT_PNR';
            }
            $searchQuery = [];
            $routeData = $record->routeData??[];
            $trip_type = $routeData->trip_type??'Domestic';            
            if($trip_type == 'Domestic') {
                $searchQuery['isDomestic'] = 1;
            } else {
                $searchQuery['isDomestic'] = 0;
            }
            $paxInfo = [
                'ADULT' => 1,
                'CHILD' => 0,
                'INFANT' => 0,
            ];
            $searchQuery['paxInfo'] = $paxInfo;
            $admin_markup = 0;
            $admin_markup_details = CustomHelper::getAdminMarkupDetails($record->admin_adult_price,$searchQuery,1,$fareIdentifier);
            if(!empty($admin_markup_details) && isset($admin_markup_details['markup_price']) && $admin_markup_details['markup_price'] > 0) {
                $admin_markup = $admin_markup_details['markup_price'];
            }
            if($request->method() == 'POST') {
                $validation_msg = [];
                $rules = [];
                $nicknames = [
                    'markup' => 'Markup',
                    'markup_type' => 'Markup Type',
                    'status' => 'Status'
                ];
                $rules['markup'] = 'nullable|numeric';
                $rules['markup_type'] = 'required|numeric';
                $rules['status'] = 'nullable';

                $validation_msg['required'] = ':Attribute is required.';
                $validation_msg['numeric'] = ':Attribute must be number.';
                $validation_msg['digits'] = ':attribute must be :digits digits.';
                $validation_msg['min'] = ':attribute should be minimum :min character.';
                $validation_msg['max'] = ':attribute should not be greater than :max character.';

                $this->validate($request, $rules, $validation_msg, $nicknames);
                $req_data = [];
                $req_data['markup_type'] = $request->markup_type??0;
                if($req_data['markup_type']==1) {
                    $req_data['markup'] = $request->markup??0;
                } else {
                    $req_data['markup'] = 0;
                }
                $req_data['status'] = $request->status??0;
                if(!empty($id)) {
                    $isSaved = AirlineRouteInventory::where('id', $id)->update($req_data);
                    $msg = "The Flight Inventory has been updated successfully.";
                    $row_id = $id;
                } else {
                    $isSaved = 0;
                    $row_id = 0;
                    // $isSaved = AirlineRouteInventory::create($req_data);
                    // if($isSaved) {
                    //     $msg = "The Flight Inventory has been added successfully.";
                    //     $row_id = $isSaved->id;
                    // }
                }

                if ($isSaved) {
                    $new_data = DB::table('airline_route_inventory')->where('id',$row_id)->first();
                    $module_desc = !empty($new_data->name)?$new_data->name:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $module_id = $row_id;
                    $params = [];
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'AirlineRouteInventory';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = "";
                    $params['sub_module_id'] = 0;
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = (!empty($id)) ? "Update" : "Add";
                    CustomHelper::RecordActivityLog($params);

                    $back_url = '';
                    if($request->back_url) {
                        $back_url = $request->back_url;
                    }
                    $back_url = route($this->ADMIN_ROUTE_NAME.'.airline.offline_inventory_view',[$row_id,'back_url'=>$back_url]);
                    return redirect($back_url)->with('alert-success', $msg);
                } else {
                    return back()->with('alert-danger', 'The Flight Inventory cannot be added, please try again or contact the administrator.');
                }
            }
            $data['admin_markup'] = $admin_markup;
            $data['admin_markup_details'] = $admin_markup_details;
            $data['page_heading'] = $title;
            $data['record'] = $record;
            $data['id'] = $id;
            return view('admin.airline.offline_inventory_edit', $data);
        } else {
            abort(404);
        }
    }

    public function offline_inventory_confirm(Request $request) {
        $data = [];
        $id = (isset($request->id))?$request->id:0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $title = 'Change Flight';

        $record = [];
        if(is_numeric($id) && $id > 0) {
            $order = Order::find($id);
            if($order && $order->inventory_id) {
                $data['order'] = $order;
                $inventory_id = $order->inventory_id??0;
                $inventoryData = AirlineRouteInventory::where('id',$inventory_id)->first();//->where('user_id',0)
                if($inventoryData && $inventoryData->id) {
                    $data['inventoryData'] = $inventoryData;
                    $record = $inventoryData->routeData??[];
                    if($record && $record->id) {
                        $title = 'Change Flight ('.$order->order_no.')';
                        if($request->method() == 'POST') {
                            $validation_msg = [];
                            $rules = [];
                            $nicknames = [
                                // 'name' => 'Route Name',
                                'segments.*.source' => 'Source',
                                'segments.*.source_terminal' => 'Source Terminal',
                                'segments.*.destination' => 'Destination',
                                'segments.*.destination_terminal' => 'Destination Terminal',
                                'segments.*.start_time' => 'Start Time',
                                'segments.*.end_time' => 'End Time',
                                'segments.*.is_arrival_next_day' => ' Is Arrival Next Day',
                                'segments.*.airline' => 'Airline',
                                'segments.*.flight_number' => 'Flight Number',
                                // 'trip_type' => 'trip_type',
                                // 'disable_before_departure_hour' => 'Disable Before Departure',
                                // 'sort_order' => 'Sort Order',
                                // 'featured' => 'Featured',
                                // 'status' => 'Status',
                                // 'pnrDetails' => 'PNR Details',
                                // 'supplier_id' => 'Agent',
                            ];

                            // $rules['name'] = 'required';
                            $rules['segments.*.source'] = 'required';
                            $rules['segments.*.source_terminal'] = 'required';
                            $rules['segments.*.destination'] = 'required';
                            $rules['segments.*.destination_terminal'] = 'required';
                            $rules['segments.*.start_time'] = 'required';
                            $rules['segments.*.end_time'] = 'required';
                            $rules['segments.*.is_arrival_next_day'] = 'nullable';
                            // $rules['segments.*.airline'] = 'required';
                            $rules['segments.*.flight_number'] = 'required';
                            // $rules['trip_type'] = 'required';
                            // $rules['disable_before_departure_hour'] = 'required|numeric';
                            // $rules['sort_order'] = 'nullable|numeric';
                            // $rules['featured'] = 'nullable';
                            // $rules['status'] = 'required';
                            // $rules['pnrDetails'] = 'required';
                            // $rules['supplier_id'] = 'required';

                            $validation_msg['required'] = ':attribute is required.';
                            $validation_msg['digits'] = ':attribute must be :digits digits.';
                            $validation_msg['min'] = ':attribute should be minimum :min character.';
                            $validation_msg['max'] = ':attribute should not be greater than :max character.';
                            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);

                            $validator->after(function ($validator) use ($request) {
                                if($request->segments) {
                                    foreach($request->segments as $key => $val) {
                                        $is_arrival_next_day = $val['is_arrival_next_day']??0;
                                        if(strtotime($val['start_time']) >= strtotime($val['end_time']) && $is_arrival_next_day == 0) {
                                            $validator->errors()->add('segments.'.$key.'.start_time', 'Departure Time must be less than Arrival Time!');
                                        }
                                    }
                                }
                            });
                            if ($validator->fails()) {
                                return back()->withErrors($validator)->withInput();
                            }
                            $order_id = $order->id;
                            // $supplier_id = $request->supplier_id??$order->supplier_id;
                            // $pnrDetails = $request->pnrDetails??'';
                            // $airline_ticket_no = $request->airline_ticket_no??'';

                            // $OrderTraveller = $order->OrderTraveller??[];
                            // if($OrderTraveller) {
                            //     foreach($OrderTraveller as $traveller) {
                            //         $traveller->pnrDetails = $pnrDetails;
                            //         $traveller->airline_ticket_no = $airline_ticket_no;
                            //         $traveller->save();
                            //     }
                            // }

                            $booking_status = 'SUCCESS';
                            $order_status_history  = array(
                                'order_id' => $order->id,
                                'orders_status' => $order->status,
                                'comments' => 'Change Flight',
                                'created_type' => 'backend',
                                'created_by' => $user_id,
                                'created_by_name' => $user_name,
                            );
                            $isSaved = OrderStatusHistory::create($order_status_history);

                            // $order->supplier_id = $supplier_id;
                            // $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                            // $order->status = $booking_status;
                            // $isSaved = $order->save();
                            if($isSaved) {
                                $order = Order::find($order_id);
                                // $resp = Order::parseIIAIRBookingDetails($order);
                                $booking_details = json_decode($order->booking_details,true);
                                $flight_details = json_decode($order->flight_details,true);
                                $params = (isset($params))?$params:[];

                                $adult_price = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList'][0]['fd']['ADULT']['fC']['BF']??0;
                                $child_price = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList'][0]['fd']['CHILD']['fC']['BF']??0;
                                $infant_price = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList'][0]['fd']['INFANT']['fC']['BF']??0;

                                $inventoryData = [];
                                $inventoryData['is_refundable'] = $is_refundable??0;
                                $inventoryData['adult_price'] = $adult_price??0;
                                $inventoryData['child_price'] = $child_price??0;
                                $inventoryData['infant_price'] = $infant_price??0;

                                $searchQuery = $flight_details['info']['searchQuery']??[];
                                $params['inventoryData'] = $inventoryData;
                                $params['pClass'] = $searchQuery['cabinClass']??'';
                                $params['ADT'] = $searchQuery['paxInfo']['ADULT']??1;
                                $params['CHD'] = $searchQuery['paxInfo']['CHILD']??0;
                                $params['INF'] = $searchQuery['paxInfo']['INFANT']??0;

                                $dep = CustomHelper::DateFormat($order->trip_date,'Y-m-d')??'';
                                $sI = [];
                                $airlineRouteSegments = $request->segments??[];
                                if($airlineRouteSegments) {
                                    $arrival_date_counter = 0;
                                    $departure_date = $dep;
                                    $params['total_segments'] = count($airlineRouteSegments);
                                    foreach($airlineRouteSegments as $key => $row) {
                                        $segment_params = $params;
                                        $segment_params['sN'] = $key;
                                        $segment_params['departure_date'] = $departure_date;
                                        $arrival_date = $departure_date;
                                        $is_arrival_next_day = $row['is_arrival_next_day']??0;
                                        if($is_arrival_next_day) {
                                            $arrival_date_counter += 1;
                                            $arrival_date = date('Y-m-d', strtotime($departure_date. ' + '.$arrival_date_counter.' days'));
                                            $departure_date = $arrival_date;
                                        }                    
                                        $segment_params['arrival_date'] = $arrival_date;
                                        $connecting_time = 0;
                                        if(isset($airlineRouteSegments[$key+1])) {
                                            $connecting_time = ((strtotime($departure_date.' '.$airlineRouteSegments[$key+1]['start_time'])-strtotime($arrival_date.' '.$airlineRouteSegments[$key]['end_time']))/60);
                                        }
                                        $segment_params['connecting_time'] = $connecting_time;
                                        $tripInfosSI = AirlineRouteSegment::parseAirlineRouteSegment((object)$row, $segment_params);
                                        // prd($tripInfosSI);
                                        $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
                                        $newTripInfos = [];
                                        if($tripInfosSI) {
                                            $newTIs = [];
                                            if($travellerInfos) {
                                                foreach($travellerInfos as $index => $travellerInfo) {
                                                    $tI = $tripInfosSI['bI']['tI'][$index]??[];
                                                    $newTI = $tI;
                                                    $pt = $travellerInfo['pt']??'';
                                                    $newTI['ti'] = $travellerInfo['ti']??'';
                                                    $newTI['pt'] = $travellerInfo['pt']??'';
                                                    $newTI['fN'] = $travellerInfo['fN']??'';
                                                    $newTI['lN'] = $travellerInfo['lN']??'';
                                                    $newTIs[] = $newTI;
                                                }
                                            }
                                            $tripInfosSI['bI']['tI'] = $newTIs;
                                        }
                                        $sI[] = $tripInfosSI;
                                    }
                                    if(isset($booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'])) {
                                        $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'] = $sI;
                                    }
                                }
                                $params = [];
                                $params['flight_details'] = $flight_details;
                                $params['booking_details'] = $booking_details;
                                $params['adult_price'] = $adult_price??0;
                                $params['child_price'] = $child_price??0;
                                $params['infant_price'] = $infant_price??0;
                                $resp = Order::parseFlightOfflineFare($params);
                                if($resp['success']) {
                                    if(isset($resp['flight_details'])) {
                                        $order->flight_details = json_encode($resp['flight_details']);
                                    }
                                    if(isset($resp['booking_details'])) {
                                        $order->booking_details = json_encode($resp['booking_details']);
                                    }
                                    $isSaved = $order->save();             

                                    if($isSaved) {
                                        /*if($supplier_id) {
                                            $total_amount = $order->total_amount;
                                            $userId = $supplier_id;
                                            $amount = $total_amount-($order->admin_markup??0);
                                            $payment_method = 'wallet';//$order->payment_method;

                                            $old_balance = UserWallet::where('user_id',$userId)->sum('amount');
                                            $payment_method_name = CustomHelper::getPaymentGatewayName($payment_method);
                                            $wallet_comment = 'Received for flight booking after admin confirmation.';
                                            $balance = $old_balance + $amount ; 
                                            $walletData = array(
                                                'user_id' => $userId,
                                                'type' => 'credit',
                                                'amount' => $amount??0,
                                                'fees' => $order->fees??0,
                                                'payment_method' => 'Order',
                                                'balance' => $balance,
                                                'for_date' => date('Y-m-d H:i:s'),
                                                'txn_id' => $order->order_no,
                                                'comment' => $wallet_comment,
                                            );
                                            $isSavedWallet = UserWallet::create($walletData);
                                            if($isSavedWallet) {
                                                $order_payments = new OrderPayments;
                                                $order_payments->payment_method = $payment_method_name;
                                                $order_payments->order_id = $order->id;
                                                $order_payments->order_no = $order->order_no;
                                                $order_payments->user_id = $order->user_id;
                                                $order_payments->amount = $amount??0;
                                                $order_payments->payment_type = $order->pay_type;
                                                $order_payments->pg_payment_status = 1;
                                                $order_payments->save();
                                                $last_payment_id = $order_payments->id??NULL;

                                                $order->last_payment_id = $last_payment_id;
                                                $order->save();
                                            } else {
                                                CustomHelper::captureSentryMessage('Flight Payment Success, But Agent Account Credited Failed!');
                                            }
                                        }

                                        CustomHelper::applyFlightCommission($order->id);*/
                                        // $orderNo = sha1($order->id);
                                        // $resp = Order::sendBookingEmail($orderNo);

                                        $row_id = $order->id;
                                        $new_data = DB::table('orders')->where('id',$row_id)->first();
                                        $module_desc = !empty($new_data->name)?$new_data->name:'';
                                        $new_data = (array) $new_data;
                                        $new_data = json_encode($new_data);
                                        $module_id = $row_id;
                                        $params = [];
                                        $params['user_id'] = $user_id;
                                        $params['user_name'] = $user_name;
                                        $params['module'] = 'Order';
                                        $params['module_desc'] = $module_desc;
                                        $params['module_id'] = $module_id;
                                        $params['sub_module'] = "";
                                        $params['sub_module_id'] = 0;
                                        $params['sub_sub_module'] = "";
                                        $params['sub_sub_module_id'] = 0;
                                        $params['data_after_action'] = $new_data;
                                        $params['action'] = "Update";
                                        CustomHelper::RecordActivityLog($params);

                                        if($request->back_url) {
                                            $back_url = $request->back_url;
                                        } else {
                                            $back_url = route($this->ADMIN_ROUTE_NAME.'.orders.index',['order'=>'pending','range'=>'custom']);
                                        }
                                        return redirect($back_url)->with('alert-success', 'The Flight has been changed successfully');
                                    }
                                }
                            }
                            return back()->with('alert-danger', 'The Flight cannot be changed, please try again or contact the administrator.');
                        }
                        $data['page_heading'] = $title;
                        $data['record'] = $record;
                        $data['id'] = $id;
                        return view('admin.airline.offline_inventory_confirm', $data);
                    } else {
                        abort(404);
                    }
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

    public function Markup(Request $request){
        if($request->method() == 'POST') {
            $request_data = $request->toArray();
            $airline_codes = $request_data['airline_codes']??[];
            if(!empty($airline_codes)) {
                $updateData = [];
                $type_arr = ['markup']; //,'commission','discount'
                foreach($airline_codes as $code) {
                    if($code) {
                        $update = ['code' => $code];
                        foreach($type_arr as $type) {
                            $update['offer_'.$type.'_type'] = $request_data['offer_'.$type.'_type'][$code]??'';
                            $update['offer_'.$type.'_value'] = $request_data['offer_'.$type.'_value'][$code]??'';
                            $update['online_'.$type.'_type'] = $request_data['online_'.$type.'_type'][$code]??'';
                            $update['online_'.$type.'_value'] = $request_data['online_'.$type.'_value'][$code]??'';
                            $update['agent_'.$type.'_type'] = $request_data['agent_'.$type.'_type'][$code]??'';
                            $update['agent_'.$type.'_value'] = $request_data['agent_'.$type.'_value'][$code]??'';
                        }
                        $updateData[] = $update;
                    }
                }
                if(!empty($updateData)) {
                    $isSaved = batch()->update(new AirlineMarkup, $updateData, 'code');
                    // prd($isSaved);
                }
            }
            if($request->back_url) {
                $back_url = $request->back_url;
            } else {
                $back_url = url($this->ADMIN_ROUTE_NAME.'/airport_master/flight-commission');
            }
            $msg = 'Flight markup updated successfully.';
            return redirect($back_url)->with('alert-success', $msg);
        }
        $data = [];
        $data['title'] = 'Flight Markup';
        $query = AirlineMarkup::orderBy('sort_order','asc');
        $records = $query->get();
        $data['records'] = $records;
        return view('admin.airline.markup', $data);
    }

    public function DiscountCommission(Request $request) {
        $segment_type = $request->segment(3);
        if($request->method() == 'POST') {
            $request_data = $request->toArray();
            $agent_group_ids = $request_data['agent_group_id']??[];
            if(!empty($agent_group_ids)) {
                $type_arr = ['domestic','international'];
                foreach($agent_group_ids as $agent_group_id) {
                    if($agent_group_id) {
                        $update = [
                            'agent_group_id' => $agent_group_id,
                            'type' => $segment_type
                        ];
                        foreach($type_arr as $type) {
                            $update[$type.'_offer_type'] = $request_data[$type.'_offer_type'][$agent_group_id]??'';
                            $update[$type.'_offer_value'] = $request_data[$type.'_offer_value'][$agent_group_id]??'';
                            $update[$type.'_online_type'] = $request_data[$type.'_online_type'][$agent_group_id]??'';
                            $update[$type.'_online_value'] = $request_data[$type.'_online_value'][$agent_group_id]??'';
                            $update[$type.'_agent_type'] = $request_data[$type.'_agent_type'][$agent_group_id]??'';
                            $update[$type.'_agent_value'] = $request_data[$type.'_agent_value'][$agent_group_id]??'';

                        }
                        $exists = AirlineDc::where('agent_group_id',$agent_group_id)->where('type',$segment_type)->first();
                        if($exists) {
                            AirlineDc::where('id',$exists->id)->update($update);
                        } else {
                            AirlineDc::create($update);
                        }
                    }
                }
            }
            if($request->back_url) {
                $back_url = $request->back_url;
            } else {
                $back_url = url($this->ADMIN_ROUTE_NAME.'/airline/'.$segment_type);
            }
            $msg = 'Flight '.$segment_type.' updated successfully.';
            return redirect($back_url)->with('alert-success', $msg);
        }
        $data = [];
        $data['title'] = 'Flight '.ucfirst($segment_type);
        $query = AgentGroup::where('status',1);
        $agent_groups = $query->get();
        $query = AirlineDc::where('type',$segment_type);
        $records = $query->get();
        $data['agent_groups'] = $agent_groups;
        $data['records'] = $records;
        // prd($data);
        return view('admin.airline.discount', $data);
    }

    public function ajax_airline(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $query = AirlineMaster::where('status',1);
            $value = $request->term??'';
            if($value) {
                $query->where(function($q1) use($value) {
                    $q1->where('code','like',$value.'%');
                    $q1->orWhere('name','like','%'.$value.'%');
                });
                $query->orderByRaw("IF(`code` = '$value', 1, 0)  DESC");
            }
            $result = $query->orderBy('featured','desc')->orderBy('sort_order','asc')->orderBy('name','asc')->take(15)->get();
            $items_arr = [];
            if($result) {
                foreach($result as $row) {
                    $items_arr[] = [
                        'id' => $row->code,
                        'text' => $row->name
                    ];
                }
            }
            $response['success'] = true;
            $response['items'] = $items_arr;
            return response()->json($response);
        }
    }

    public function booking_list(Request $request) {        
        $data = [];
        $limit = 30;
        $status = ($request->has('status'))?$request->status:1;
        $type = $request->type??'';
        $inventory_id = $request->inventory_id??'';
        if($inventory_id) {
            $limit = 'all';
        }
        $title = 'Offline Booked Flight';
        $method = $request->method();
        if($method == 'POST') {
            $created_type = 'backend';
            $created_by = 0;
            $created_by_name = 'system';
            $adminUser = auth::guard('admin')->user();
            if($adminUser && $adminUser->id) {
                $created_type = 'backend';
                $created_by = $adminUser->id??0;
                $created_by_name = $adminUser->name??'system';
            }            
            $action = $request->action??'';
            $booking_id = $request->booking_id??'';
            if($action && $booking_id) {
                if(is_array($booking_id)) {
                    $booking_ids = $booking_id;
                } else {
                    $booking_ids = explode(',', $booking_id);
                }
                if(empty($booking_ids)) {
                    $message = 'Please select the passengers!';
                    return back()->with("alert-danger", $message);
                }
                $query = Order::query();
                $query->withWhereHas('OrderTraveller',function($q1) use($booking_ids) {
                    $q1->whereIn('id',$booking_ids);
                });
                $orders = $query->get();
                if($orders) {
                    if($action=='update_traveller') {
                        $response['success'] = false;
                        $response['message'] = '';
                        $field_name = $request->field_name??'';
                        $field_value = $request->field_value??'';
                        $field_title = $field_name;
                        switch ($field_name) {
                            case 'airline_ticket_no':
                                $field_title = 'E-Ticket No';
                                break;
                            default:
                                $field_title = ucwords(str_replace('_', ' ', $field_name));
                                break;
                        }
                        if($field_name && ($field_value || $field_name=='airline_ticket_no')) {
                            foreach($orders as $order) {
                                $order_id = $order->id;
                                $OrderTraveller = $order->OrderTraveller??[];
                                if($OrderTraveller) {
                                    foreach($OrderTraveller as $traveller) {
                                        if($traveller->id == $booking_id) {
                                            if($traveller->$field_name == $field_value) {
                                                $response['success'] = true;
                                                // $response['message'] = 'The '.$field_title.' has not changed!';
                                            } else {
                                                $name = $traveller->title.' '.$traveller->first_name.' '.$traveller->last_name;
                                                $update_data = [];
                                                $update_data[$field_name] = $field_value;
                                                $isSaved = OrderTraveller::where('order_id',$order_id)->where('id',$traveller->id)->update($update_data);
                                                if($isSaved) {
                                                    $traveller = OrderTraveller::find($traveller->id);
                                                    $new_name = $traveller->title.' '.$traveller->first_name.' '.$traveller->last_name;

                                                    $order_status_history  = array(
                                                        'order_id' => $order->id,
                                                        'orders_status' => $order->status,
                                                        'comments' => 'Passenger name changed from:'.$name.' to '.$new_name,
                                                        'created_type' => $created_type,
                                                        'created_by' => $created_by,
                                                        'created_by_name' => $created_by_name,
                                                    );
                                                    $isSaved = OrderStatusHistory::create($order_status_history);

                                                    if($isSaved) {
                                                        $order = Order::find($order_id);
                                                        $resp = Order::parseIIAIRBookingDetails($order);
                                                        $order->booking_details = json_encode($resp);
                                                        $order->save();

                                                        if ($isSaved) {
                                                            $row_id = $order->id;
                                                            $new_data = DB::table('orders_traveller')->where('id',$traveller->id)->first();
                                                            $module_desc = !empty($new_data->order_no)?$new_data->order_no:'';
                                                            $new_data = (array) $new_data;
                                                            $new_data = json_encode($new_data);
                                                            $module_id = $row_id;
                                                            $params = [];
                                                            $params['user_id'] = $created_by;
                                                            $params['user_name'] = $created_by_name;
                                                            $params['module'] = 'Order';
                                                            $params['module_desc'] = $module_desc;
                                                            $params['module_id'] = $module_id;
                                                            $params['sub_module'] = "OrderTraveller";
                                                            $params['sub_module_id'] = $traveller->id??0;
                                                            $params['sub_sub_module'] = "";
                                                            $params['sub_sub_module_id'] = 0;
                                                            $params['data_after_action'] = $new_data;
                                                            $params['action'] = "Update";
                                                            CustomHelper::RecordActivityLog($params);

                                                            $response['success'] = true;
                                                            $response['message'] = 'The passenger details has been updated successfully';
                                                        } else {
                                                            $response['message'] = 'The passenger details cannot be updated, please try again or contact the administrator.';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if($response['success']) {

                        } else {
                            if(empty($response['message'])) {
                                $response['message'] = $field_title.' is required!';
                            }
                        }
                        return Response::json($response);
                    } else if($action=='print') {
                        $data['orders'] = $orders;
                        return view('admin.orders._order_print', $data);
                    } else {
                        $exportArr = [];
                        foreach($orders as $order) {
                            $booking_details = json_decode($order->booking_details,true);
                            $airline_name = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['fD']['aI']['name']??'';
                            $source = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['da']['city']??'';
                            $destination = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][count($booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'])-1]['aa']['city']??'';
                            $OrderTraveller = $order->OrderTraveller??[];
                            if($OrderTraveller) {
                                foreach($OrderTraveller as $traveller) {
                                    $status = 'Confirmed';
                                    if($order->status=='Cancelled') {
                                        $status = 'Cancelled';
                                    }

                                    $row_arr = [
                                        'Ref Number' => $order->order_no??'',
                                        'Date' => CustomHelper::DateFormat($order->trip_date,'j F, Y')??'',
                                        'Airline' => $airline_name??'',
                                        'From' => $source??'',
                                        'To' => $destination??'',
                                        'Title' => $traveller->title??'',
                                        'First Name' => $traveller->first_name??'',
                                        'Last Name' => $traveller->last_name??'',
                                        'Date of Birth' => CustomHelper::DateFormat($traveller->dob,'j F, Y')??'',
                                        'Passenger Contact' => $order->phone??'',
                                        'PNR' => $traveller->pnrDetails??'',
                                        'E-Ticket' => $traveller->airline_ticket_no??'',
                                        'Agent Name' => $order->supplierInfo->company_name??'',
                                        'Contact No' => $order->supplierInfo->User->phone??'',
                                        'Status' => $status??'',
                                    ];
                                    $exportArr[] = $row_arr;
                                }
                            }
                        }
                        $fieldNames = array_keys($exportArr[0]);
                        if($action=='download') {
                            $fileName = 'ticket_list'.time().'.xlsx';
                            return Excel::download(new OrderExport($exportArr, $fieldNames), $fileName);
                        } else if($action=='email'){
                            $data['exportArr'] = $exportArr;
                            $emailData = view('admin.orders._order_email', $data)->render();

                            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                            $to_email = $request->email??'';
                            if($to_email) {
                                if(filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
                                    $REPLYTO = '';
                                    $cc_email = $ADMIN_EMAIL;
                                    $bcc_email = '';

                                    $email_subject = 'Flight Ticket Detail';
                                    $email_content = $emailData;

                                    $params = [];
                                    $params['to'] = $to_email;
                                    $params['reply_to'] = $REPLYTO;
                                    $params['cc'] = $cc_email;
                                    $params['bcc'] = $bcc_email;
                                    $params['subject'] = $email_subject;
                                    $params['email_content'] = $email_content;
                                    $params['module_name'] = 'Flight Ticket Detail';
                                    $params['record_id'] = 0;
                                    $is_mail = CustomHelper::sendCommonMail($params);
                                    $response['success'] = true;
                                    $response['message'] = 'Mail has been sent successfully';
                                } else {
                                    $response['message'] = 'Please enter valid email address.';
                                }
                            } else {
                                $response['message'] = 'Please enter email address.';
                            }
                            return Response::json($response);
                        }
                    }
                }
            }
        }

        $query = Order::where('inventory_id','>',0)->where('supplier_id','>',0)->where('status','SUCCESS')->orderBy('created_at','desc');
        if($request->pnr) {
            $pnr = $request->pnr;
            $query->where('booking_details','like','%'.$pnr.'%');
        }
        if($request->airline) {
            $airline = $request->airline;
            $query->where('booking_details','like','%"fD":{"aI":{"code":"'.$airline.'%');
        }
        if($inventory_id) {
            $query->where('inventory_id', $inventory_id);
        }
        if($status != '') {
            // $query->where('status', $status);
        }
        $from_date = '';
        $to_date = '';      
        $range = $request->range;
        if(!empty($range) && $range != 'custom') {
            $date_between_arr = CustomHelper::get_to_from_date($range);
            $from_date = $date_between_arr['from']??'';
            $to_date = $date_between_arr['to']??'';
        } else {
            $from = $request->from??'';
            $to = $request->to??'';
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
        if($limit=='all') {
            $records = $query->get();
        } else {
            $records = $query->paginate($limit);
        }
        // prd($records->toArray());
        $data['records'] = $records;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['page_heading'] = $title;
        $data['status'] = $status;
        $data['type'] = $type;
        $data['inventory_id'] = $inventory_id;
        return view('admin.airline.booking_list', $data);
    }


/* end of controller */
}