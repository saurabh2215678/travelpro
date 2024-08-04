<?php
namespace App\Http\Controllers\Admin;
use App\Models\LoginHistory;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Validator;

class LoginHistoryController extends Controller {

    private $limit;
    private $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->limit = 100;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        //$this->currentUrl = url()->current();
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;

        $action = (isset($request->action))?$request->action:'';
        $userid = (isset($request->user_id))?$request->user_id:'';
        $ip_address = (isset($request->ip_address))?$request->ip_address:'';
        $os_details = (isset($request->os_details))?$request->os_details:'';
        $browser_details = (isset($request->browser_details))?$request->browser_details:'';

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

        $login_history = LoginHistory::orderBy('id','desc');

        if(!empty($userid)){
            $login_history->where('user_id', $userid);
        }
        if(!empty($action)){
            $login_history->where('action', 'like', '%'.$action.'%');
        }
        if(!empty($ip_address)){
            $login_history->where('ip_address', 'like', '%'.$ip_address.'%');
        }
        if(!empty($os_details)){
            $login_history->where('os_details', 'like', '%'.$os_details.'%');
        }
        if(!empty($browser_details)){
            $login_history->where('browser_details', 'like', '%'.$browser_details.'%');
        }
        if(!empty($from_date)){
            $login_history->whereRaw('created_at >= "'.$from_date.'"');
        }
        if(!empty($to_date)){
            $login_history->whereRaw('created_at <= "'.$to_date.'"');
        }
        $actions = LoginHistory::select('action')->groupBy('action')->get();
        $users= Admin::select('name','id')->orderBy('name','asc')->get();

        $login_history_log = $login_history->paginate($limit);
        $data['login_history_logs'] = $login_history_log;
        $data['actions'] = $actions;
        $data['users'] = $users;
        return view('admin.login_history.index', $data);
    }

    public function view(Request $request) {
        $data = array();
        if($request->id) {
            $login_history = LoginHistory::find($request->id);
            //$diff = [];
            //$new_data = [];
            //$old_data = [];
            $title = 'Login History Details ('.$login_history->user_name.')';

            /*if(!empty($activity_log)) {
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
            }*/
            $data['login_history'] = $login_history;
            $data['page_heading'] = $title;
            //$data['diff'] = $diff;
            //$data['new_data'] = $new_data;
            //$data['old_data'] = $old_data;
        }
        return view('admin.login_history.view', $data);
    }
    
    public function delete_log(Request $request){

        $method = $request->method();
        $before_days = CustomHelper::WebsiteSettings('DELETE_LOGIN_LOG_DAYS')??0;
        //$before_days = 0;
        $date = strtotime(date('Y-m-d')); 
        $before_date = date('Y-m-d',strtotime('-'.$before_days.' days',$date));
        $is_deleted = LoginHistory::whereDate('created_at','<',$before_date)->delete();
        //prd($is_deleted);
        //$is_deleted = LoginHistory::whereDate('created_at','<=',now()->subDays($before_date))->delete();

        if($is_deleted){
            return back()->with(
                "alert-success",
                "The Login History has been removed successfully."
            );   
        }else{
           return redirect(url($this->ADMIN_ROUTE_NAME . "/login_history"))->with("alert-danger","The Login History cannot be deleted, please try again or contact the administrator.");  
       }
   }
   /* end of controller */
}
