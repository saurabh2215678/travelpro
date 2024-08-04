<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AirlineRoute;
use App\Models\AirlineRouteSegment;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Validator;
use DB;

class AirlineRouteController extends Controller {

    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct() {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request) {
        $data = [];
        $limit = 50;
        $status = (isset($request->status))?$request->status:'';
        $type = $request->type??'';
        $query = AirlineRoute::orderBy('featured','desc')->orderBy('sort_order','asc')->orderBy('id','desc');//where('user_id',0)->
        if($type == 'agent') {
            $query->where('user_id','!=',0);
            $page_heading = 'Offline Flight Agent Route';
        } else {
            $query->where('user_id',0);
            $page_heading = 'Offline Flight Admin Route';
        }
        if($request->name) {
            $name = $request->name;
            $query->where(function($q1) use($name) {
                $q1->whereHas('userData',function($q1) use($name) {
                    $q1->where('name','like','%'.$name.'%');
                });
                $q1->orWhere('name','like','%'.$name.'%');
                $q1->orWhere('source',$name);
                $q1->orWhere('destination',$name);
                $q1->orWhere(DB::raw("CONCAT(source,' / ',destination)"),'like','%'.$name.'%');
                $q1->orWhere(DB::raw("CONCAT(airline,'-',flight_number)"),'like','%'.$name.'%');
            });
        }
        if($request->featured != '') {
            $query->where('featured', $request->featured);
        }
        if( strlen($status) > 0 ) {
            $query->where('status', $status);
        } /*else {
            $query->where('status', 1);
        }*/
        $records = $query->paginate($limit);
        $data['records'] = $records;
        $data['page_heading'] = $page_heading;
        $data['type'] = $type;
        return view('admin.airline_route.index', $data);
    }

    public function add(Request $request) {
        $id = (isset($request->id))?$request->id:0;
        $record = '';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $title = 'Add Flight Route';

        if(is_numeric($id) && $id > 0){
            $record = AirlineRoute::where('id',$id)->first();//->where('user_id','0')
            $title = 'Edit Flight Route';
        }
        if($request->method() == 'POST') {
            $validation_msg = [];
            $rules = [];
            $nicknames = [
                'name' => 'Route Name',
                'segments.*.source' => 'Source',
                'segments.*.source_terminal' => 'Source Terminal',
                'segments.*.destination' => 'Destination',
                'segments.*.destination_terminal' => 'Destination Terminal',
                'segments.*.start_time' => 'Start Time',
                'segments.*.end_time' => 'End Time',
                'segments.*.is_arrival_next_day' => ' Is Arrival Next Day',
                'segments.*.stops' => 'No of Stop(s)',
                'segments.*.airline' => 'Airline',
                'segments.*.flight_number' => 'Flight Number',
                'trip_type' => 'trip_type',
                'disable_before_departure_hour' => 'Disable Before Departure',
                'sort_order' => 'Sort Order',
                'featured' => 'Featured',
                'status' => 'Status',
            ];

            $rules['name'] = 'required';
            $rules['segments.*.source'] = 'required';
            $rules['segments.*.source_terminal'] = 'required';
            $rules['segments.*.destination'] = 'required';
            $rules['segments.*.destination_terminal'] = 'required';
            $rules['segments.*.start_time'] = 'required';
            $rules['segments.*.end_time'] = 'required';
            $rules['segments.*.is_arrival_next_day'] = 'nullable';
            $rules['segments.*.stops'] = 'nullable';
            // $rules['segments.*.airline'] = 'required';
            $rules['segments.*.flight_number'] = 'required';
            $rules['trip_type'] = 'required';
            $rules['disable_before_departure_hour'] = 'required|numeric';
            $rules['sort_order'] = 'nullable|numeric';
            $rules['featured'] = 'nullable';
            $rules['status'] = 'required';

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

            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['trip_type'] = $request->trip_type??'';
            $req_data['disable_before_departure_hour'] = $request->disable_before_departure_hour??0;
            $req_data['sort_order'] = $request->sort_order??0;
            $req_data['featured'] = $request->featured??0;
            $req_data['status'] = $request->status??0;

            if(!empty($id)) {
                $isSaved = AirlineRoute::where('id', $id)->update($req_data);
                $msg = "The Flight Route has been updated successfully.";
                $row_id = $id;
            } else {
                $req_data['user_id'] = 0;
                $isSaved = AirlineRoute::create($req_data);
                if($isSaved) {
                    $msg = "The Flight Route has been added successfully.";
                    $row_id = $isSaved->id;
                }
            }

            if ($isSaved) {
                AirlineRouteSegment::where('airline_route_id',$row_id)->delete();
                $source = '';
                $source_terminal = '';
                $destination = '';
                $destination_terminal = '';
                $start_time = '';
                $end_time = '';
                $airline = '';
                $flight_number = '';
                $route_stops = 0;
                $route_is_arrival_next_day = 0;
                if($request->segments) {
                    foreach($request->segments as $counter => $segment) {
                        $is_arrival_next_day = $segment['is_arrival_next_day']??0;
                        $stops = $segment['stops']??0;
                        if($is_arrival_next_day && empty($route_is_arrival_next_day)) {
                            $route_is_arrival_next_day = $is_arrival_next_day;
                        }
                        if($counter==0) {
                            $source = $segment['source']??'';
                            $source_terminal = $segment['source_terminal']??'';
                            $start_time = $segment['start_time']??'';
                            $airline = $segment['airline']??'';
                            $flight_number = $segment['flight_number']??'';
                            if($stops) {
                                $route_stops += $stops;
                            }
                        } else {
                            if($stops) {
                                $route_stops += $stops;
                            } else {
                                $route_stops += 1;
                            }
                        }
                        $destination = $segment['destination']??'';
                        $destination_terminal = $segment['destination_terminal']??'';
                        $end_time = $segment['end_time']??'';
                        $route_segment = $segment;
                        $route_segment['airline_route_id'] = $row_id;
                        $route_segment['stops'] = $stops;
                        $route_segment['cabin_adult'] = $segment['cabin_adult']??0;
                        $route_segment['cabin_child'] = $segment['cabin_child']??0;
                        $route_segment['cabin_infant'] = $segment['cabin_infant']??0;
                        $route_segment['checkin_adult'] = $segment['checkin_adult']??0;
                        $route_segment['checkin_child'] = $segment['checkin_child']??0;
                        $route_segment['checkin_infant'] = $segment['checkin_infant']??0;
                        AirlineRouteSegment::create($route_segment);
                    }
                }

                $update = [];
                $update['source'] = $source;
                $update['source_terminal'] = $source_terminal;
                $update['destination'] = $destination;
                $update['destination_terminal'] = $destination_terminal;
                $update['start_time'] = $start_time;
                $update['end_time'] = $end_time;
                $update['is_arrival_next_day'] = $route_is_arrival_next_day;
                $update['airline'] = $airline;
                $update['flight_number'] = $flight_number;
                $update['stops'] = $route_stops;
                AirlineRoute::where('id',$row_id)->update($update);

                $new_data = DB::table('airline_routes')->where('id',$row_id)->first();
                $module_desc = !empty($new_data->name)?$new_data->name:'';
                $new_data = (array) $new_data;
                $new_data['segments'] = json_encode($request->segments??[]);
                $new_data = json_encode($new_data);
                $module_id = $row_id;
                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'AirlineRoute';
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
                    $back_url = url($this->ADMIN_ROUTE_NAME.'/airline_route');
                }
                return redirect($back_url)->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Flight Route cannot be added, please try again or contact the administrator.');
            }
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['record'] = $record;
        $data['id'] = $id;
        return view('admin.airline_route.form', $data);
    }

    public function view(Request $request) {
        $id = (isset($request->id))?$request->id:0;
        if(is_numeric($id) && $id > 0) {
            $record = AirlineRoute::find($id);
            $title = 'View Flight Route';
            $data = [];
            $data['page_heading'] = $title;
            $data['record'] = $record;
            $data['id'] = $id;
            return view('admin.airline_route.view', $data);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request)
    {
        $id=$request->id;
        $method=$request->method();
        $is_deleted = 0;

        if($method=="POST"){
            if(is_numeric($id) && $id > 0)
            {
                $record = AirportCodesMaster::find($id);
                if(!empty($record))
                {
                    $is_deleted = $record->delete();
                }
            }
        }

        if($is_deleted)
        {
            return redirect(url(CustomHelper::getAdminRouteName().'/airline_route'))->with('alert-success', 'The Airport has been deleted successfully.');
        }
        else
        {
            return redirect(url(CustomHelper::getAdminRouteName().'/airline_route'))->with('alert-danger', 'The Airport cannot be deleted, please try again or contact the administrator.');
        }
    }

    public function ajax_airline_route(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $term = $request->term??'';
            $user_id = $request->user_id??'';
            $query = AirlineRoute::where('status',1)->where('user_id',$user_id);
            if($request->term) {
                $name = $request->term;
                $query->where(function($q1) use($name) {
                    $q1->where('source','like','%'.$name.'%');
                    $q1->orWhere('name','like','%'.$name.'%');
                    $q1->orWhere('destination','like','%'.$name.'%');
                    $q1->orWhere('flight_number','like','%'.$name.'%');
                });
            }
            $result = $query->orderBy('featured','desc')->orderBy('sort_order','asc')->orderBy('id','desc')->take(15)->get();
            $items_arr = [];
            if($result) {
                foreach($result as $row) {
                    $items_arr[] = [
                        'id' => $row->id,
                        'text' => CustomHelper::parseAirlineRouteName($row)
                    ];
                }
            }
            $response['success'] = true;
            $response['items'] = $items_arr;
            return response()->json($response);
        }
    }

    public function ajax_more_segments(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $data = [];
            $data['counter'] = $request->counter??0;
            $html = view('admin.airline_route._segment',$data)->render();
            $response['success'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }
    }

    public function price_markup(Request $request){
        if($request->method() == 'POST') {
            $airline_codes = $request->airline_codes??[];
            $markup_type = $request->markup_type??[];
            $markup_value = $request->markup_value??[];
            $markup_value_nonc = $request->markup_value_nonc??[];
            $max_base_price = $request->max_base_price??[];
            $is_domestic = $request->is_domestic??[];
            if(!empty($airline_codes)) {
                $updateData = [];
                foreach($airline_codes as $code) {
                    if($code) {
                        $updateData[] = [
                            'code' => $code,
                            'markup_type' => $markup_type[$code]??'',
                            'markup_value' => $markup_value[$code]??'',
                            'markup_value_nonc' => $markup_value_nonc[$code]??'',
                            'max_base_price' => $max_base_price[$code]??'',
                            'is_domestic' => $is_domestic[$code]??'',
                        ];
                    }
                }
                if(!empty($updateData)) {
                    $isSaved = batch()->update(new AirlinePriceMarkup, $updateData, 'code');
                    // prd($isSaved);
                }
            }
            if($request->back_url) {
                $back_url = $request->back_url;
            } else {
                $back_url = url($this->ADMIN_ROUTE_NAME.'/airline_route/price-markup');
            }
            $msg = 'Price markup settings updated successfully.';
            return redirect($back_url)->with('alert-success', $msg);
        }
        $data = [];
        $limit = 50;
        $status = (isset($request->status))?$request->status:'';
        $query = AirlinePriceMarkup::where('code','!=','international')->orderBy('featured','desc')->orderBy('sort_order','asc')->orderBy('name','asc');
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
        if($request->is_domestic != '') {
            $query->where('is_domestic', $request->is_domestic);
        }
        $records = $query->paginate($limit);
        $data['records'] = $records;

        $data['international'] = AirlinePriceMarkup::where('code','international')->first();
        return view('admin.airline_route.price_markup', $data);
    }

/* end of controller */
}