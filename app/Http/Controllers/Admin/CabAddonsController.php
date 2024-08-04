<?php
namespace App\Http\Controllers\Admin;
use App\Models\CabAddons;
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

class CabAddonsController extends Controller {

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
        $query = CabAddons::orderBy("sort_order", "asc")->orderBy("id", "desc");

        $query->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });

        if (!empty($name)) {
            $query->where("name", "like", "%" . $name . "%");
        }
        if (strlen($status) > 0) {
            $query->where("status", $status);
        }
        if (strlen($featured) > 0) {
            $query->where("featured", $featured);
        }

        if($request->price != '') {
            $query->where("price", $request->price);
        }

        $records = $query->paginate($limit);
        $data["records"] = $records;
        return view("admin.cab_addons.index", $data);
    }

    public function add(Request $request) {

        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $record = [];
        $title = "Add Cab Addon";

        if (is_numeric($id) && $id > 0) {
            $record = CabAddons::find($id);
            $title = "Edit (" . $record->name. " )";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            $ext = "jpg,jpeg,png,gif";
            $rules = [];

            if (is_numeric($id) && $id > 0) {
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
            } else {
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u|unique:cab_addons|max:100';
            }

            $rules["status"] = "required";
            // $validation_msg = [];
            // $validation_msg["name.required"] = "Name is required.";
            $this->validate($request, $rules);
            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['status'] = $request->status??'0';
            $req_data['featured'] = $request->featured??0;
            $req_data['daily_rental'] = $request->daily_rental??0;
            $req_data['sort_order'] = $request->sort_order??0;
            $req_data['price'] = $request->price??0;
            $req_data['is_deleted'] = $request->is_deleted??0;
            if (!empty($record) && $record->id == $id) {
                $isSaved = CabAddons::where("id",$record->id)->update($req_data);
                $cab_addons_id = $record->id;
                $msg = "Cab Addon has been updated successfully.";
            } else {
                $isSaved = CabAddons::create($req_data);
                $cab_addons_id = $isSaved->id;
                $msg = "Cab Addon has been added successfully.";
            }

            if ($isSaved) {
                cache()->forget("cab_addons");

                $new_data = DB::table('cab_addons')->where('id',$cab_addons_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $cab_addons_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Cab Addon';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME ."/cab_addons"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Cab Addon could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["record"] = $record;
        $data["states"] = State::where('status',1)->get();
        $data["id"] = $id;
        return view("admin.cab_addons.form", $data);
    }

    public function view(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $record = [];
        $title = "Cab Addon Details";
        if (is_numeric($id) && $id > 0) {
            $record = CabAddons::where("id", $id)->first();
            $title = "Cab Addon Details (".$record->name.")";
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["record"] = $record??[];
        $data["id"] = $id;
        return view("admin.cab_addons.view", $data);
    }

    public function delete(Request $request) {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {

                $record = CabAddons::find($id);
                $new_data = DB::table('cab_addons')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = CabAddons::cabAddonsDelete($id);
                if ($is_delete['status'] != 'ok') {
                    return redirect(url('admin/cab_addons'))->with('alert-danger', $is_delete['message']);
                } else {
                    $delete_destination_name = $is_delete['name'];
                    $is_deleted = true;
                }
            }
        }

        if ($is_deleted) {
            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Cab Addons';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';
            CustomHelper::RecordActivityLog($params);
            return redirect(url($this->ADMIN_ROUTE_NAME . "/cab_addons"))->with("alert-success", "Cab Addons has been deleted successfully.");
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/cab_addons"))->with("alert-danger","Cab Addons cannot be deleted, please try again or contact the administrator.");
        }
    }

    /* end of controller */
}
