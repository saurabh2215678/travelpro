<?php

namespace App\Http\Controllers\Admin;

use App\Models\SmsTemplate;
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

class SmsTeamplateController extends Controller
{
    protected $currentUrl;

    public function __construct()
    {
        // $this->limit = 10;
        // $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request)
    {
        $data = [];
        $title = (isset($request->title))?$request->title:'';
        $status = (isset($request->status))?$request->status:1;

        $query = SmsTemplate::orderBy('id','desc');    

        if(!empty($title)){
            $query->where('title','like', '%'.$title.'%');
        }

        $query->where('status', $status);

        $records = $query->paginate(30);
        $data['records'] = $records;

        return view('admin.sms_templates.index', $data);
    }

    public function add(Request $request){
        
        $record = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = (isset($request->id))?$request->id:0;
        $title = 'Add Sms Template';

        if(is_numeric($id) && $id > 0){
            $record = SmsTemplate::find($id);
            $title = 'Edit Sms Template ('.$record->title." )";
        }
        if($request->method() == 'POST'){
            $rules = [];
            $rules['title'] = 'required';
            $rules['status'] = 'required';
            if($id == 0){
                $rules['template_id'] = 'required';
            }
            $this->validate($request, $rules);

            $req_data = [];
            $req_data = $request->except(['_token', 'back_url', 'id']);
        
            if(!empty($record) && $record->count() > 0){
                $isSaved = SmsTemplate::where('id', $record->id)->update($req_data);
                $sms_templates_id = $record->id;
                $msg="Sms Templates has been updated successfully.";
            } else {
                $slug = CustomHelper::GetSlug('sms_templates', 'id', $id, $request->title);
                $req_data['slug'] = $slug;
                $isSaved = SmsTemplate::create($req_data);
                $sms_templates_id = $isSaved->id;
                $msg="Sms Templates has been added successfully.";
            }

            if($isSaved){
                //=============Activity Logs===================== 
                $new_data = DB::table('sms_templates')->where('id',$sms_templates_id)->first();
                $module_desc = !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id= $sms_templates_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Sms Templates';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($record->id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);
                //=============Activity Logs=====================  
                cache()->forget('sms_templates');

                return redirect(url('admin/sms-templates'))->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Sms template could not be added, please try again or contact the administrator.');
            }
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['record'] = $record;
        $data['id'] = $id;
        return view('admin.sms_templates.form', $data);
    }
    /* end of controller */
}
