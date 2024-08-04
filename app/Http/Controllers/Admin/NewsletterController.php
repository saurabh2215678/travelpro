<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductImage;
use App\Models\ColorsMaster;

use App\Models\NewsletterSubscriber;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Storage;

use App\Helpers\CustomHelper;

use Image;
use DB;

class NewsletterController extends Controller{


    private $limit;
    //private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 10;
        //$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request){

        $data = [];

        $limit = $this->limit;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        
        $export_xls = (isset($request->export_xls))?$request->export_xls:'';
        $name = isset($request->name) ? $request->name:'';
        $email = isset($request->email) ? $request->email:'';
        $phone = isset($request->phone) ? $request->phone:'';

        $from = (isset($request->from))?$request->from:'';
        $to = (isset($request->to))?$request->to:'';

        $from_date = CustomHelper::DateFormat($from, 'Y-m-d', 'd/m/Y');
        $to_date = CustomHelper::DateFormat($to, 'Y-m-d', 'd/m/Y');

        $newsletterQuery = NewsletterSubscriber::orderBy('id', 'desc');

        if(!empty($name)){
            $newsletterQuery->where("name", "like", "%" . $name . "%");
        }
        if(!empty($email)){
            $newsletterQuery->where("email", "like", "%" . $email . "%");
        }
        if(!empty($phone)){
            $newsletterQuery->where("phone", "like", "%" . $phone . "%");
        }

        if(!empty($from_date)){
            $newsletterQuery->whereRaw('DATE(created_at) >= "'.$from_date.'"');
        }

        if(!empty($to_date)){
            $newsletterQuery->whereRaw('DATE(created_at) <= "'.$to_date.'"');
        }

        $newsletter_download_logs = $request->all();
        $new_data = json_encode($newsletter_download_logs);

        $action = '';
        if($export_xls == 1) {
            $action = 'Export';
        }

        if($action == 'Export') {
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Newsletter';
            $params['module_desc'] = 'Newsletter Subscriber List';
            $params['module_id'] = '';
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = $action;
            CustomHelper::RecordActivityLog($params);
        }

        if(!empty($export_xls) && ($export_xls == 1 || $export_xls == '1') ){
            //prd($newsletters);
            return $this->exportXls($newsletterQuery);
        }

        $newsletters = $newsletterQuery->paginate($limit);

        $data['newsletters'] = $newsletters;
        $data['limit'] = $limit;


        return view('admin.newsletter.index', $data);

    }

    public function delete(Request $request){

        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        if($method == 'POST'){
            //prd($request->toArray());

            $id = (isset($request->id))?$request->id:0;

            if(is_numeric($id) && $id > 0){

                $newsletter = NewsletterSubscriber::find($id);


                $new_data = DB::table('newsletter_subscribers')->where('id',$id)->first();
                $module_desc =  !empty($new_data->email)?$new_data->email:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                /*$function_name = $this->currentUrl;
                $action_table = 'newsletter_subscribers';
                $row_id = $id;
                $action_type = 'Delete Newsletter Subscriber';
                $action_description = 'Delete('.$request->id.")";
                $description = 'Delete('.$request->id.")";*/

                if(isset($newsletter->id) && $newsletter->id == $id){
                    $newsletter->delete();

                    /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

                    //=============Activity Logs=====================

                    $params = [];
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Newsletter';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $id;
                    $params['sub_module'] = "";
                    $params['sub_module_id'] = 0;
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = 'Delete';

                    CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

                    return back()->with('alert-success', 'Newsletter Subscriber deleted successfully.');
                }
            }
        }

        return back()->with('alert-danger', 'something went wrong, please try again...');

    }



    private function exportXls($newsletterQuery){

        //$fieldNames = $newsletterQuery->getModel()->getFillable();

        $select = ['id', 'email', 'created_at'];

        $newsletters = $newsletterQuery->select($select)->get();

        $fileName = 'newsletter_'.date('Y-m-d-H-i-s').'.xlsx';

        $viewData = [];
        $viewData['newsletters'] = $newsletters;

        //$viewHtml = view('admin.newsletter._newsletter_export', $viewData)->render();

        //echo $viewHtml; die;       

        header('Content-Type: application/vnd.ms-excel');
        //tell browser what's the file name
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        //no cache
        header('Cache-Control: max-age=0');

        return view('admin.newsletter._newsletter_export', $viewData);
    }

    /* end of controller */
}