<?php
namespace App\Http\Controllers\Admin;
use App\Models\ApiCallLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Validator;
use Storage;
use Image;

class ApiLogController extends Controller {

    private $limit;

    public function __construct() {
        $this->limit = 100;
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;

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

        $activity_logs = ApiCallLog::orderBy('id','desc');
        if($request->search) {
            $search = $request->search??'';
            $activity_logs->where(function($q1) use($search) {
                $q1->where('request_json', 'like', '%'.$search.'%');
                $q1->orWhere('response_json', 'like', '%'.$search.'%');
            });
        }
        if($request->ip_address) {
            $ip_address = $request->ip_address??'';
            $activity_logs->where('ip_address', 'like', '%'.$ip_address.'%');
        }
        if(!empty($from_date)) {
            $activity_logs->whereRaw('created_at >= "'.$from_date.'"');
        }
        if(!empty($to_date)) {
            $activity_logs->whereRaw('created_at <= "'.$to_date.'"');
        }
        $api_logs = $activity_logs->paginate($limit);
        $data['api_logs'] = $api_logs;
        return view('admin.api_logs.index', $data);
    }

    public function view(Request $request) {
        $data = array();
        if($request->id) {
            $api_log = ApiCallLog::find($request->id);
            if($api_log) {
                $data = [];
                $data['api_log'] = $api_log;
                $data['page_heading'] = 'API Log Details';
                return view('admin.api_logs.view', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    /* end of controller */
}