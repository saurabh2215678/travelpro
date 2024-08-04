<?php

namespace App\Http\Controllers\Admin;

use App\Models\EnquiriesMaster;
use App\Models\EnquiriesMasterGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;

use Validator;
use Storage;
use Image;
use DB;

class EnquiriesMasterController extends Controller
{
    private $limit;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 100;
        $this->currentUrl = url()->current();
    }

    public function index(Request $request){
        $data = [];
        $parent_name ='';
        $limit = $this->limit;
        $parent_id = ($request->parent_id) ? $request->parent_id : 0;
        $group_id = (isset($request->group_id))?$request->group_id:'';
        $status = (isset($request->status))?$request->status:'';
        $records = EnquiriesMaster::with('GroupData')->withCount('child')->where('parent_id',$parent_id)->orderBy('name','asc');
        if($parent_id){
            $parent_data = EnquiriesMaster::where('id',$parent_id)->first();
            $parent_name = !empty($parent_data)?$parent_data->name:'';
        }

        if(!empty($group_id)) {
            $records->where('group_id', $group_id);
        }

        if( strlen($status) > 0 ){
            $records->where('status', $status);
        }

        $records = $records->paginate($limit);
        $groups = EnquiriesMasterGroup::orderBy('name','asc')->get();
        $data['records'] = $records;
        $data['parent_id'] = $parent_id;
        $data['groups'] = $groups;
        $data['parent_name'] = $parent_name;
        // prd($data);
        return view('admin.enquiries_master.index', $data);
    }

    public function add(Request $request){

    //prd($request->toArray());
        $id = (isset($request->id))?$request->id:0;
        $parent_id = $request->parent_id??0;
        $record = '';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $title = 'Add Enquiries Master Data';

        if(is_numeric($id) && $id > 0){
            $record = EnquiriesMaster::find($id);
            $title = 'Edit Enquiries Master Data';
        }
        if($request->method() == 'POST' || $request->method() == 'post'){

            $validation_msg = [];
            $rules = [];

            $rules['name'] = 'required';
            $rules['status'] = 'required';

            $validation_msg['name.required'] = 'The Name of the Enquiries Master data field is required.';
            $validation_msg['name.regex'] = 'The Name of the Enquiries Master data format is Invalid.';

            $this->validate($request, $rules, $validation_msg);
            $req_data = [];
            $req_data = $request->except(['_token', 'back_url','id', 'parent_id']);
            $req_data['name'] = $request->name;
            $req_data['sort_order'] = $request->sort_order??0;
            $req_data['color_code'] = $request->color_code??'';
            $req_data['color_name'] = $request->color_name??'';
            if(!empty($id)) {
                $isSaved = EnquiriesMaster::where('id', $id)->update($req_data);
                $msg="The Enquiries Master data has been updated successfully.";

                $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'enquiries_master';
                $row_id = $id;
                $action_type = 'Edit On Enquiries Master';
                $action_description = 'Edit On ('.$request->name.")";
                $description = 'Update('.$request->name.") ".$description;
            } else {
                $slug = $slug = CustomHelper::GetSlug('enquiries_master','slug','',$req_data['name'],'_');
                if(!empty($parent_id)) {                    
                    $record = EnquiriesMaster::find($parent_id);
                    if(!empty($record)) {
                        $slug = '';
                    } else {
                        $parent_id = '';
                    }
                }
                $req_data['parent_id'] = $parent_id;
                $req_data['slug'] = $slug;
                $isSaved = EnquiriesMaster::create($req_data);
                $msg="The Enquiries Master data has been added successfully.";
                $id = $isSaved->id;
                $parent_id = $isSaved->parent_id;

                $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'enquiries_master';
                $row_id = $id;
                $action_type = 'Add On Enquiries Master';
                $action_description = 'Edit On ('.$request->name.")";
                $description = 'Add('.$request->name.") ".$description;
            }

            if ($isSaved) {
                // CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);

                $new_data = DB::table('enquiries_master')->where('id',$row_id)->first();
                $module_desc = !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $row_id;
                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Enquiries Master';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                if($parent_id){
                    $back_url = url(CustomHelper::getAdminRouteName().'/enquiries_master')."?parent_id=".$parent_id;   
                }else{
                    $back_url = url(CustomHelper::getAdminRouteName().'/enquiries_master');
                }
                return redirect($back_url)->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Enquiries Master data cannot be added, please try again or contact the administrator.');
            }
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['record'] = $record;
        $data['parent_id'] = $parent_id;
        $data['groups'] = EnquiriesMasterGroup::orderBy('name','asc')->get();
        $data['id'] = $id;
        return view('admin.enquiries_master.form', $data);
    }

    public function delete(Request $request)
    {
        $id=$request->id;
        $method=$request->method();
        $is_deleted = 0;

        if($method=="POST"){
            if(is_numeric($id) && $id > 0)
            {
                $record = EnquiriesMaster::find($id);
                if(!empty($record))
                {
                    $is_deleted = $record->delete();
                }
            }
        }

        if($is_deleted)
        {
            return redirect(url(CustomHelper::getAdminRouteName().'/enquiries_master'))->with('alert-success', 'The Enquiries Master data has been deleted successfully.');
        }
        else
        {
            return redirect(url(CustomHelper::getAdminRouteName().'/enquiries_master'))->with('alert-danger', 'The Enquiries Master data cannot be deleted, please try again or contact the administrator.');
        }

    }


    public function ajax_enquiries_master(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $parent_id = $request->parent_id??'';
            if($parent_id) {
                $query = EnquiriesMaster::where('status',1);
                $query->where('parent_id',$parent_id);
                $enquiries_master = $query->orderBy('sort_order','asc')->get();
                $enquiries_master_arr = [];
                if($enquiries_master) {
                    foreach($enquiries_master as $em) {
                        $enquiries_master_arr[] = [
                            'id' => $em->id,
                            'name' => $em->name
                        ];
                    }
                }
                $response['success'] = true;
                $response['enquiries_master'] = $enquiries_master_arr;
            }
            return response()->json($response);
        }
    }


/* end of controller */
}