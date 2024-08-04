<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;

use DB;

class PaymentGatewayController extends Controller
{

    /**

     * Admin - Settings

     * URL: /admin/settings

     *

     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View

     */

    /*public function index()
    {
        $settings = Setting::orderBy('created_at', 'desc')->get();

        return view('admin.settings.index', compact('settings'));
    }*/

    public function index(Request $request){        

        $data = [];
        $settingRow = '';
        $method = $request->method();
        $name = $request->name ?? '';
        $status = isset($request->status) ? $request->status : 1;
        $admin_id = auth('admin')->user()->id;
        //prd($admin_id);

        $query = PaymentGateway::orderBy('id', 'asc');

        if(!empty($name)){
            $query->where('name', 'like', '%'.$name.'%');
        }     
        $query->where("status", $status); 
        $settings = $query->get();
        $page_heading = 'Payment-Gateways';
        $data['page_heading'] = $page_heading;
        $data['settings'] = $settings;

        return view('admin.payment_gateways.index', $data);
    }

    /**

     * Admin - Update Setting

     * URL: /admin/settings/{setting} (PUT)

     *

     * @param Request $request

     * @param $setting

     * @return \Illuminate\Http\RedirectResponse

     */

    public function update(Request $request, $setting)
    {
        $data = $request->all();
        $setting->state = isset($data['state']);
        $result = $setting->save();
        if ($result) {
            return redirect(route('admin.settings.index'))->with('alert-success', 'The Payment Gateway has been updated successfully.');
        }else {
            return back()->with('alert-danger', 'The Payment Gateway cannot be updated, please try again or contact the administrator.');
        }
    }
    public function add(Request $request){

        $data = [];
        $email = '';
        $payment_gateways_id = isset($request->payment_gateways_id) ? $request->payment_gateways_id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = (isset($request->id))?$request->id:0;
        $type = (isset($request->type))?$request->type:'';
        if($request->method() == 'POST' || $request->method() == 'post'){

            $rules = [];
            $validation_msg = [];
            $rules['image'] = 'nullable|max:2024';
            $validation_msg = [ 'image.max' => 'The upload gateway Image must be under 2MB.'];
            $this->validate($request, $rules,$validation_msg);

            $req_data = [];
            $req_data['sort_order'] = $request->sort_order ?? '';
            $req_data['details'] = $request->details ?? '';
            $req_data['perameter_1'] = $request->perameter_1 ?? '';
            $req_data['perameter_2'] = $request->perameter_2 ?? '';
            $req_data['perameter_3'] = $request->perameter_3 ?? '';
            $req_data['perameter_4'] = $request->perameter_4 ?? '';
            $req_data['perameter_5'] = $request->perameter_5 ?? '';
            $req_data['perameter_6'] = $request->perameter_6 ?? '';
            $req_data['perameter_7'] = $request->perameter_7 ?? '';
            
            // $req_data['url'] = $request->url;
            // $req_data['method'] = $request->method;
            //==========

            $file = $request->file('image');

            if ($file) {
                $path = 'payment-gateways/';
                $thumb_path = 'payment-gateways/thumb/';

                //UploadImage($file, $path, $ext='', $width=768, $height=768, $is_thumb=false, $thumb_path, $thumb_width=300, $thumb_height=300)

                $IMG_HEIGHT = CustomHelper::WebsiteSettings('PAYMENT_GATEWAY_IMG_HEIGHT');
                $IMG_WIDTH = CustomHelper::WebsiteSettings('PAYMENT_GATEWAY_IMG_WIDTH');
                $THUMB_HEIGHT = CustomHelper::WebsiteSettings('PAYMENT_GATEWAY_THUMB_HEIGHT');
                $THUMB_WIDTH = CustomHelper::WebsiteSettings('PAYMENT_GATEWAY_THUMB_WIDTH');

                $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:1600;
                $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:640;
                $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
                $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;
                $ext = 'jpg,jpeg,png,gif';

                $images_data = [];

                $upload_result = CustomHelper::UploadImage($file, $path, $ext, $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

                if($upload_result['success']){
                    $req_data['image'] = $upload_result['file_name'];
                }
            }

        //=======

            $req_data['show'] = $request->show ? $request->show : 0;
            $isSaved = 0;

            // prd($req_data);
            $msg = '';
            if(!empty($id))
            {           
                $isSaved = PaymentGateway::where('id', $id)->update($req_data);
                $payment_gateways_id = $id;
                $msg="Payment Gateway has been updated successfully.";
            }

         if($isSaved){

            //=============Activity Logs===================== 

            $new_data = DB::table('payment_gateways')->where('id',$payment_gateways_id)->first();
            $module_desc = !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $module_id= $payment_gateways_id;

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Payment Gateway';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($id)) ? "Update" : "Add";

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

            return redirect(url('admin/payment-gateways/add/'.$id))->with('alert-success', $msg);
        }else{
            return back()->with('alert-danger', 'The Payment Gateway could not be updated, please try again or contact the administrator.');
        }

        //=============

    }

    $customer_name = '';
    $page_heading = 'Payment-gateways Configuration';


    $PaymentGateways = PaymentGateway::where('status',1)->get();
    $data['page_heading'] = $page_heading;
    $data['PaymentGateways'] = $PaymentGateways;
    $data['id'] = $id;
    $data['active_id'] = $id;

    return view('admin.payment_gateways.form', $data);

}


public function change_status(Request $request){

        $data = [];
        $req_data = [];
        $payment_gateways_id = isset($request->payment_gateways_id) ? $request->payment_gateways_id:'';
        $email = '';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
            $data['success'] = false;
        if($request->method() == 'POST' || $request->method() == 'post'){

           $id = (isset($request->id))?$request->id:0;
           $status = (isset($request->status))?$request->status:'';
        //    $updated_status = '';

           $updated_status = 1;
           if((int)$status == 1){

            $updated_status = 0;
           }

           $req_data['status'] = $updated_status;
           $isSaved = 0;

            // prd($req_data);
            if(!empty($id))
            {           
                $isSaved = PaymentGateway::where('id', $id)->update($req_data);
                $payment_gateways_id = $id;
                $msg="Payment Gateway has been updated successfully.";
            }

            if($isSaved){


            //=============Activity Logs===================== 

            $new_data = DB::table('payment_gateways')->where('id',$payment_gateways_id)->first();
            $module_desc = !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $module_id= $payment_gateways_id;

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Payment Gateway';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($id)) ? "Update" : "Add";

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================


          
            $data['success'] = true;
            $data['status'] = $updated_status;
        } else {
            $data['success'] = false;
            $data['status'] = $updated_status;
        }


    }
        echo json_encode($data);

  
}

public function submitDispalyName(Request $request){


   # prd($request->toArray());
    $query = PaymentGateway::find($request->id);
    if($request->method() == 'POST' || $request->method() == 'post'){
    $query->display_name = $request->display_name??'';
    $query->save();
}
    return response()->json([
        "msg" => "Dispaly Name submit successfully.",
    ]);
}


}