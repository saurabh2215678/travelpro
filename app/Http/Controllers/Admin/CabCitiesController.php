<?php
namespace App\Http\Controllers\Admin;
use App\Models\CabCities;
use App\Models\State;
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

class CabCitiesController extends Controller {

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
        $status = isset($request->status) ? $request->status :1;
        $featured = isset($request->featured) ? $request->featured : "";
        $cabCityQuery = CabCities::orderBy("sort_order", "asc");

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

        $cabCities = $cabCityQuery->paginate($limit);
        $data["cabCities"] = $cabCities;
        return view("admin.cab_cities.index", $data);
    }

    public function add(Request $request) {

        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $cabCityQuery = [];
        $title = "Add City";

        if (is_numeric($id) && $id > 0) {
            $cabCityQuery = CabCities::find($id);
            $title = "Edit (" . $cabCityQuery->name. " )";
        }

        //prd($request->toArray());

        if ($request->method() == "POST" || $request->method() == "post") {
            $ext = "jpg,jpeg,png,gif";
            $rules = [];

            if (is_numeric($id) && $id > 0) {
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
            }else{
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u|unique:cab_cities|max:100';
            }

            $rules["status"] = "required";
            // $validation_msg = [];
            // $validation_msg["name.required"] = "Name is required.";

            $this->validate($request, $rules);
            // $req_data = [];
            // $req_data = $request->except([
            //     "_token",
            //     "old_image",
            //     "icon",
            //     "back_url",
            // ]);
            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['status'] = $request->status??'0';
            $req_data['featured'] = $request->featured??0;
            $req_data['sort_order'] = $request->sort_order??0;
            $req_data['is_airport'] = $request->is_airport??0;
            $req_data['is_deleted'] = $request->is_deleted??0;
            if (!empty($cabCityQuery) && $cabCityQuery->id == $id) {
                $isSaved = CabCities::where("id",$cabCityQuery->id)->update($req_data);
                $cab_cities_id = $cabCityQuery->id;
                $msg = "Cab City has been updated successfully.";
            } else {
                $isSaved = CabCities::create($req_data);
                $cab_cities_id = $isSaved->id;
                $msg = "Cab City has been added successfully.";
            }

            if ($isSaved) {

                cache()->forget("cab_cities");

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

                return redirect(url($this->ADMIN_ROUTE_NAME ."/cab_cities"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Cab City could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["cabCityQuery"] = $cabCityQuery;
        $data["states"] = State::where('status',1)->get();
        $data["id"] = $id;
        return view("admin.cab_cities.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $cabQuery = "";
        $title = "Cab City Details";
        if (is_numeric($id) && $id > 0) {
            $cabCityQuery = CabCities::where("id", $id)->first();
            $title = "Cab City Details (".$cabCityQuery->name.")";
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["cabCityQuery"] = $cabCityQuery??[];
        $data["id"] = $id;
        return view("admin.cab_cities.view", $data);
    }

    public function delete(Request $request)
    {
    $id = isset($request->id)?$request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $method = $request->method();
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {

            $cabCityQuery = CabCities::find($id);
                $new_data = DB::table('cab_cities')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = CabCities::cabCityDelete($id);
                    if ($is_delete['status'] != 'ok') {

                        return redirect(url('admin/cab_cities'))->with('alert-danger', $is_delete['message']);

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

                //$is_deleted = $cabCityQuery->delete();
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

       return redirect(url($this->ADMIN_ROUTE_NAME . "/cab_cities"))->with("alert-success", "Cab City has been deleted successfully.");
   } else {

    return redirect(url($this->ADMIN_ROUTE_NAME . "/cab_cities"))->with("alert-danger","Cab City cannot be deleted, please try again or contact the administrator.");
}
}


    //==============

/* end of controller */
}
