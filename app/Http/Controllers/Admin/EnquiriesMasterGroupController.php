<?php

namespace App\Http\Controllers\Admin;

use App\Models\EnquiriesMasterGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Helpers\CustomHelper;

use Validator;
use Storage;
use Image;
use DB;

class EnquiriesMasterGroupController extends Controller
{
   private $limit;
   protected $currentUrl;

   public function __construct() {
        $this->limit = 30;
        $this->currentUrl = url()->current();
    }

    public function index(Request $request){

        $data = [];
        $parent_name ='';
        $limit = $this->limit;
        $id = ($request->id) ? $request->id : 0;
        $records = EnquiriesMasterGroup::orderBy('name','asc')->paginate($limit);

        $data['records'] = $records;
        return view('admin.enquiries_master_group.index', $data);
    }

    public function add(Request $request){
        // prd($request->toArray());
        $id = (isset($request->id))?$request->id:0;
        $record = '';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $title = 'Add Enquiries Master Group Data';

        if(is_numeric($id) && $id > 0){
            $record = EnquiriesMasterGroup::find($id);
            $title = 'Edit Enquiries Master Group Data';
        }
        if($request->method() == 'POST' || $request->method() == 'post'){

        $validation_msg = [];
        $rules = [];
        $rules['name'] = 'required';
        $rules['status'] = 'required';

        $validation_msg['name.required'] = 'The Name of the Enquiries Master Group data field is required.';
       $validation_msg['name.regex'] = 'The Name of the Enquiries Master data format is Invalid.';

        $this->validate($request, $rules, $validation_msg);
            $req_data = [];

            // $parent_id = $request->parent_id;
            $req_data = $request->except(['_token', 'back_url','id',]);
            $req_data['name'] = $request->name;
            
            $slug = CustomHelper::GetSlug('enquiries_master_group', 'id', $id, $request->name);

            if(!empty($id)) {
                $isSaved = EnquiriesMasterGroup::where('id', $id)->update($req_data);
                $msg="The Enquiries Master Group data has been updated successfully.";

                $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'enquiries_master_group';
                $row_id = $id;
                $action_type = 'Edit On Enquiries Master Group';
                $action_description = 'Edit On ('.$request->name.")";
                $description = 'Update('.$request->name.") ".$description;
            } else {
                $req_data['slug'] = $slug;

                $isSaved = EnquiriesMasterGroup::create($req_data);
                $msg="The Enquiries Master Group data has been added successfully.";
                $id = $isSaved->id;

                $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'enquiries_master_group';
                $row_id = $id;
                $action_type = 'Add On Enquiries Master Group';
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
                $params['module'] = 'Enquiries Master Group';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                $back_url = (isset($request->back_url))?$request->back_url:'';
                if(empty($back_url)){
                    $back_url = url(CustomHelper::getAdminRouteName().'/enquiries_master_group');
                }
                return redirect($back_url)->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Enquiries Master Group data cannot be added, please try again or contact the administrator.');
            }
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['record'] = $record;
        $data['id'] = $id;
        return view('admin.enquiries_master_group.form', $data);
    }


    public function delete(Request $request)
    {
        $id=$request->id;
        $method=$request->method();
        $is_deleted = 0;

        if($method=="POST"){
            if(is_numeric($id) && $id > 0)
            {
                $record = EnquiriesMasterGroup::find($id);
                if(!empty($record))
                {
                    $is_deleted = $record->delete();
                }
            }
        }

        if($is_deleted)
        {
            return redirect(url(CustomHelper::getAdminRouteName().'/enquiries_master_group'))->with('alert-success', 'The Enquiries Master Group data has been deleted successfully.');
        }
        else
        {
            return redirect(url(CustomHelper::getAdminRouteName().'/enquiries_master_group'))->with('alert-danger', 'The Enquiries Master Group data cannot be deleted, please try again or contact the administrator.');
        }

    }

    /* end of controller */
}