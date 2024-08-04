<?php
namespace App\Http\Controllers\Admin;
use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Validator;
use Storage;
use Image;

class ActivityLogController extends Controller {

    private $limit;

    public function __construct() {
        $this->limit = 100;
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;

        $name = (isset($request->name))?$request->name:'';
        $userid = (isset($request->user_id))?$request->user_id:'';
        $moduleid = (isset($request->moduleid))?$request->moduleid:'';
        $submoduleid = (isset($request->submoduleid))?$request->submoduleid:'';
        $module_name = (isset($request->module))?$request->module:'';
        $module_desc = (isset($request->module_desc))?$request->module_desc:'';
        $submodule_name = (isset($request->sub_module))?$request->sub_module:'';
        $sub_module_desc = (isset($request->sub_module_desc))?$request->sub_module_desc:'';
        $action = (isset($request->action))?$request->action:'';
        $ip_address = (isset($request->ip_address))?$request->ip_address:'';

        $from_date = '';
        $to_date = '';
        $range = $request->range;
        if(!empty($range) && $range != 'custom') {
            $date_between_arr = CustomHelper::get_to_from_date($range);
            $from_date = $date_between_arr['from'] ?? null;
            $to_date = $date_between_arr['to'] ?? null;
        } else {
            if($request->from && $request->to) {
                $from_date = CustomHelper::DateFormat($request->from,'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat($request->to,'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else if($request->from) {
                $from_date = CustomHelper::DateFormat($request->from,'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
            } else if($request->to) {
                $to_date = CustomHelper::DateFormat($request->to,'Y-m-d','d/m/Y');
                $to_date = $to_date.' 23:59:59';
            }
        }

        $activity_logs = ActivityLog::orderBy('id','desc');

        if(!empty($userid)){
            $activity_logs->where('user_id', $userid);
        }
        if(!empty($moduleid)){
            $activity_logs->where('module_id', $moduleid);
        }
        if(!empty($submoduleid)){
            $activity_logs->where('sub_module_id', $submoduleid);
        }
        if(!empty($module_name)){
            $activity_logs->where('module', 'like', '%'.$module_name.'%');
        }
        if(!empty($module_desc)){
            $activity_logs->where('module_desc', 'like', '%'.$module_desc.'%');
        }
        if(!empty($submodule_name)){
            $activity_logs->where('sub_module', 'like', '%'.$submodule_name.'%');
        }
        if(!empty($sub_module_desc)){
            $activity_logs->where('sub_module_desc', 'like', '%'.$sub_module_desc.'%');
        }
        if(!empty($action)){
            $activity_logs->where('action', 'like', '%'.$action.'%');
        }
        if(!empty($ip_address)){
            $activity_logs->where('ip_address', 'like', '%'.$ip_address.'%');
        }
        if(!empty($from_date)){
            $activity_logs->whereRaw('created_at >= "'.$from_date.'"');
        }
        if(!empty($to_date)){
            $activity_logs->whereRaw('created_at <= "'.$to_date.'"');
        }

        $modules = ActivityLog::select('module')->groupBy('module')->get();
        $submodules = ActivityLog::select('sub_module')->groupBy('sub_module')->get();
        $actions = ActivityLog::select('action')->groupBy('action')->get();
        $users= Admin::select('name','id')->orderBy('name','asc')->get();

        $logs = $activity_logs->paginate($limit);
        $data['activity_logs'] = $logs;
        $data['modules'] = $modules;
        $data['submodules'] = $submodules;
        $data['actions'] = $actions;
        $data['users'] = $users;
        return view('admin.activity_logs.index', $data);
    }

    public function view(Request $request) {
        $data = array();
        if($request->id) {
            $activity_log = ActivityLog::find($request->id);
            $diff = [];
            $new_data = [];
            $old_data = [];
            $title = 'Activity Log Details ('.$activity_log->user_name.')';

            if(!empty($activity_log)) {
                $data['activity_log'] = $activity_log;
                if(!empty($activity_log->data_after_action)) {
                    $new_data = (array) json_decode($activity_log->data_after_action);
                    $old_activity_log = ActivityLog::where([
                        'module'=> !empty($activity_log->module)?$activity_log->module:'',
                        'module_id'=>!empty($activity_log->module_id)?$activity_log->module_id:'',
                        'sub_module_id'=>!empty($activity_log->sub_module_id)?$activity_log->sub_module_id:'',
                        'sub_sub_module_id'=>!empty($activity_log->sub_sub_module_id)?$activity_log->sub_sub_module_id:''
                    ])->where('id','<',!empty($activity_log->id)?$activity_log->id:'')->orderBy('id','desc')->first();
                    if(!empty($old_activity_log) && !empty($old_activity_log->data_after_action)) {
                        $old_data = (array) json_decode($old_activity_log->data_after_action);
                        try{
                            $diff = array_diff_assoc($new_data, $old_data);
                        } catch (\Exception $e) {
                            $diff = array_diff($new_data, $old_data);
                        }
                    } else {
                        $diff = $new_data;
                    }
                }
            }
            $data['activity_log'] = $activity_log;
            $data['page_heading'] = $title;
            $data['diff'] = $diff;
            $data['new_data'] = $new_data;
            $data['old_data'] = $old_data;
        }
        return view('admin.activity_logs.view', $data);
    }

    /* end of controller */
}