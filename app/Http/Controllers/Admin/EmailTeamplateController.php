<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailTemplate;
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

class EmailTeamplateController extends Controller
{
    protected $currentUrl;
    protected $limit;
    protected $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->limit = 10;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    // Add Accommodation Code Here......

    public function index(Request $request)
    {
        $data = [];
        $title = (isset($request->title))?$request->title:'';
        $status = (isset($request->status))?$request->status:1;

        $query = EmailTemplate::orderBy('id','desc');    

        if(!empty($title)){
            $query->where('title','like', '%'.$title.'%');
        }
        $query->where('status', $status);
        $records = $query->paginate(30);
        //prd($records->toArray());
        $data['records'] = $records;

        return view('admin.email_templates.index', $data);
    }

    public function add(Request $request){
        
        $record = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = (isset($request->id))?$request->id:0;
        $title = 'Add Email Template';

        if(is_numeric($id) && $id > 0){
            $record = EmailTemplate::find($id);
            $title = 'Edit Email Template('.$record->title." )";
        }
        if($request->method() == 'POST'){

            $rules = [];
            $rules['title'] = 'required';
            $rules['content'] = 'required';
            $rules['status'] = 'required';

            $this->validate($request, $rules);

            $req_data = [];
            $req_data = $request->except(['_token', 'back_url', 'id']);
            $req_data['bcc_admin'] = $request->bcc_admin ?? 0;
            $req_data['manager_email'] = $request->manager_email ?? 0;
            $req_data['contact_email'] = $request->contact_email ?? 0;

            // prd($req_data);
            if(!empty($record) && $record->count() > 0){
                $isSaved = EmailTemplate::where('id', $record->id)->update($req_data);
                $email_templates_id = $record->id;
                $msg="Email Templates has been updated successfully.";
            } else {

                $slug = CustomHelper::GetSlug('email_templates', 'id', $id, $request->title);
                $req_data['slug'] = $slug;
                $isSaved = EmailTemplate::create($req_data);
                $email_templates_id = $isSaved->id;
                $msg="Email Templates has been added successfully.";
            }

            if($isSaved){

                //=============Activity Logs===================== 

                $new_data = DB::table('email_templates')->where('id',$email_templates_id)->first();
                $module_desc = !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id= $email_templates_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Email Templates';
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

                cache()->forget('email_templates');

                return redirect(url('admin/email-templates'))->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Email template could not be added, please try again or contact the administrator.');
            }
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['record'] = $record;
        $data['id'] = $id;
        return view('admin.email_templates.form', $data);
    }
    /* end of controller */
}
