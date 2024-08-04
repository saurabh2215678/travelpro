<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AirportCodesMaster;
use App\Models\AirlinePriceMarkup;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Validator;
use DB;

class AirportMasterController extends Controller {

    // private $limit;
    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        // $this->limit = 100;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request){
        $data = [];
        $limit = 50;
        $status = (isset($request->status))?$request->status:'';
        $query = AirportCodesMaster::orderBy('featured','desc')->orderBy('sort_order','asc')->orderBy('name','asc');
        if($request->name) {
            $name = $request->name;
            $query->where(function($q1) use($name) {
                $q1->where('code','like','%'.$name.'%');
                $q1->orWhere('name','like','%'.$name.'%');
                $q1->orWhere('citycode','like','%'.$name.'%');
                $q1->orWhere('city','like','%'.$name.'%');
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
        return view('admin.airport_master.index', $data);
    }

    public function add(Request $request) {
        $id = (isset($request->id))?$request->id:0;
        $record = '';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $title = 'Add Airtport Master Data';

        if(is_numeric($id) && $id > 0){
            $record = AirportCodesMaster::find($id);
            $title = 'Edit Airtport Master Data';
        }
        if($request->method() == 'POST' || $request->method() == 'post') {
            $validation_msg = [];
            $rules = [];
            $nicknames = [
                'name' => 'Airport Name',
                'code' => 'Code',
                'city' => 'City',
                'citycode' => 'City Code',
                'country' => 'Country',
                'countrycode' => 'Country Code',
                'sort_order' => 'Sort Order',
                'featured' => 'Featured',
                'status' => 'Status',
            ];

            $rules['name'] = 'required';
            $rules['code'] = 'required|min:3|max:3';
            $rules['city'] = 'required';
            $rules['citycode'] = 'required|min:3|max:3';
            $rules['country'] = 'required';
            $rules['countrycode'] = 'required|min:2|max:2';
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
            $req_data['code'] = $request->code??'';
            $req_data['city'] = $request->city??'';
            $req_data['citycode'] = $request->citycode??'';
            $req_data['country'] = $request->country??'';
            $req_data['countrycode'] = $request->countrycode??'';
            $req_data['sort_order'] = $request->sort_order??'';
            $req_data['featured'] = $request->featured??'';
            $req_data['status'] = $request->status??'';

            if(!empty($id)) {
                $isSaved = AirportCodesMaster::where('id', $id)->update($req_data);
                $msg = "The Airtport Master data has been updated successfully.";

                $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'airport_codes_master';
                $row_id = $id;
                $action_type = 'Edit On Airtport Master';
                $action_description = 'Edit On ('.$request->name.")";
                $description = 'Update('.$request->name.") ".$description;
            } else {
                $isSaved = AirportCodesMaster::create($req_data);
                $msg="The Airtport Master data has been added successfully.";
                $id = $isSaved->id;

                $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'airport_codes_master';
                $row_id = $id;
                $action_type = 'Add On Airtport Master';
                $action_description = 'Edit On ('.$request->name.")";
                $description = 'Add('.$request->name.") ".$description;
            }

            if ($isSaved) {
                $new_data = DB::table('airport_codes_master')->where('id',$row_id)->first();
                $module_desc = !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $row_id;
                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'AirtportMaster';
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
                    $back_url = url($this->ADMIN_ROUTE_NAME.'/airport_master');
                }
                return redirect($back_url)->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Airtport Master data cannot be added, please try again or contact the administrator.');
            }
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['record'] = $record;
        $data['id'] = $id;
        return view('admin.airport_master.form', $data);
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
            return redirect(url(CustomHelper::getAdminRouteName().'/airport_master'))->with('alert-success', 'The Enquiries Master data has been deleted successfully.');
        }
        else
        {
            return redirect(url(CustomHelper::getAdminRouteName().'/airport_master'))->with('alert-danger', 'The Enquiries Master data cannot be deleted, please try again or contact the administrator.');
        }
    }


    public function ajax_airport_master_21feb2024(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $query = AirportCodesMaster::where('status',1);
            if($request->term) {
                $search_text = addslashes($request->term);
                $query->where(function($q1) use($search_text) {
                    $q1->where('code','like','%'.$search_text.'%');
                    $q1->orWhere('name','like','%'.$search_text.'%');
                    $q1->orWhere('citycode','like','%'.$search_text.'%');
                    $q1->orWhere('city','like',$search_text.'%');
                });
                $query->orderByRaw("IF(`code` = '$search_text', 1, 0)  DESC");
            }
            $result = $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(15)->get();
            $items_arr = [];
            if($result) {
                foreach($result as $row) {
                    $items_arr[] = [
                        'id' => $row->code,
                        'text' => $row->city.' ('.$row->code.') - '.$row->name
                    ];
                }
            }
            $response['success'] = true;
            $response['items'] = $items_arr;
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
                $back_url = url($this->ADMIN_ROUTE_NAME.'/airport_master/price-markup');
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
        return view('admin.airport_master.price_markup', $data);
    }

/* end of controller */
}