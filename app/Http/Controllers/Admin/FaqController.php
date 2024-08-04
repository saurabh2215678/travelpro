<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use App\Models\Package;
use App\Models\FaqCategory;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Mail;
use Validator;
use Illuminate\Validation\Rule;

use DB;

class FaqController extends Controller
{
    protected $currentUrl;
    protected $limit;
    protected $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->limit = 40;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    // Add Accommodation Code Here......

    public function index(Request $request)
    {
        $tbl_id = (isset($request->tid))?$request->tid:0;
        $tbl_name = $request->tbl??($request->module??'');
        $category = (isset($request->category))?$request->category:'';

        $data = [];
        if((isset($tbl_id) && !empty($tbl_id)) && (isset($tbl_name) && !empty($tbl_name)) && (isset($category) && !empty($category))){
        $limit = $this->limit;
        $question = isset($request->question) ? $request->question : "";
        $data['destination_id'] = $destination_id = (isset($request->destination) && !empty($request->destination)) ? $request->destination : 0;
        $data['sub_destination_id'] = $sub_destination_id = (isset($request->sub_destination) && !empty($request->sub_destination)) ? $request->sub_destination : 0;
        $status = isset($request->status) ? $request->status : 1;

        $faq = Faq::with('faqParentDestination')->orderBy("sort_order", "asc");

        $packageDetails = Package::find($tbl_id);
        if (!empty($tbl_id)) {
            $faq->where('tbl_id',$tbl_id);
        }
        if (!empty($tbl_name)) {
            $faq->where('tbl_name',$tbl_name);
        }
        if (!empty($category)) {
            $faq->where('tbl_category',$category);
        }
        
        if (!empty($question)) {
            $faq->where("question","like","%" . $question . "%");
        }
        if (!empty($destination_id)) {
            $faq->where('destination_id',$destination_id);
        }

        if (!empty($sub_destination_id)) {
            $faq->where('sub_destination_id',$sub_destination_id);
        }
        
        $faq->where("status", $status);

        $faqs = $faq->paginate($limit);

        $data["faqs"] = $faqs;
        $data["tbl_id"] = $tbl_id;
        $data['segment'] = 'packages';
        $data["tbl_name"] = $tbl_name;
        $data["package"] = $packageDetails;
        $data["category"] = $category;
        $data['segment'] = 'packages';
        //$data["destinations"] = Destination::where('is_city',0)->where('status',1)->get();
        }
        else{
        return redirect(url($this->ADMIN_ROUTE_NAME));
        }

        return view("admin.faqs.index", $data);
    }

    public function add(Request $request)
    {
        $segment = (isset($request->segment))?$request->segment:'';
        $tbl_id = (isset($request->tid))?$request->tid:0;
        $tbl_name = $request->tbl??($request->module??'');
        $tbl_category = (isset($request->category))?$request->category:'';
        $packageDetails = Package::find($tbl_id);

        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $faq = [];
        $title = "Add Faq";

        if((isset($tbl_id) && !empty($tbl_id)) && (isset($tbl_name) && !empty($tbl_name)) && (isset($tbl_category) && !empty($tbl_category))){

        if (is_numeric($id) && $id > 0) {
            $faq = Faq::find($id);
            $title =
            "Edit Faq(" . $faq->question . ")";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->toArray());
            $rules = [];
            $validation_msg = [];

            $rules["question"] = "required";
            $rules["answer"] = "required";
            //$rules["destination_id"] = 'required';
            //$rules["sub_destination_id"] = 'required';

            $validation_msg["question.required"] = "The Question field is required.";
            $validation_msg["answer.required"] = "The Answer field is required.";

            $this->validate($request, $rules,$validation_msg);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "back_url",
                "old_image",
                "id",
                "category",
                "featured",
                "slug",
                "tid",
                "module",
                "segment",
            ]);
            //$req_data['question'] = (!empty($request->question))?$request->question:'';
            //$req_data['answer'] = (!empty($request->answer))?$request->answer:'';
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['destination_id'] = (isset($request->destination_id) && !empty($request->destination_id)) ? $request->destination_id : null;
            $req_data['sub_destination_id'] = (isset($request->sub_destination_id) && !empty($request->sub_destination_id)) ? $request->sub_destination_id : null;
            
            // $category = (isset($request->category) && !empty($request->category)) ? implode(",", $request->category) : 0;
            // $req_data['category'] = $category;

            $req_data['tbl_id'] = $tbl_id;
            $req_data['tbl_category'] = $tbl_category;
            $req_data['tbl_name'] = $tbl_name;
        
            if (!empty($faq) && $faq->id == $id) {
                $isSaved = Faq::where("id",$faq->id)->update($req_data);
                $faqs_id = $faq->id;
                $msg = "Faq has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "faqs";
                $row_id = $id;
                $action_type = "Edit On Faq";
                $action_description = "Edit On (" . $request->question . ")";
                $description =
                "Update(" . $request->question . ") " . $description;*/
            } else {
                $isSaved = Faq::create($req_data);
                $faqs_id = $isSaved->id;
                $msg = "Faq has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "faqs";
                $row_id = $id;
                $action_type = "Add On Faq";
                $action_description = "Add On(" . $request->question . ")";
                $description =
                "Add(" . $request->question . ") " . $description;*/
            }

            if ($isSaved) {

               /* CustomHelper::recordActionLog(
                    $function_name,
                    $action_table,
                    $row_id,
                    $action_type,
                    $action_description,
                    $description
                );*/

                 //=============Activity Logs=====================
                $new_data = DB::table('faqs')->where('id',$faqs_id)->first();
                $module_desc =  !empty($new_data->question)?$new_data->question:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id= $faqs_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Faq';
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

                cache()->forget("faqs");

                // return redirect(url($this->ADMIN_ROUTE_NAME . "/faqs"))->with("alert-success", $msg);
                return redirect(route("admin.faqs.index",['tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$tbl_category,'segment'=>$segment]))->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Team Management could be added, please try again or contact the administrator."
                );
            }
        }
        //$destination = Destination::where('is_city',0)->orderBy('name')->get();

        $data = [];
        $data["page_heading"] = $title;
        $data["faq"] = $faq;
        //$data["destinations"] = $destination;
        $data["categories"] = FaqCategory::where('status',1)->get();
        $data["id"] = $id;
        $data["tbl_id"] = $tbl_id;
        $data['segment'] = $segment;
        $data["tbl_name"] = $tbl_name;
        $data['segment'] = 'packages';
        $data["package"] = $packageDetails;
        $data["tbl_category"] = $tbl_category;

        }else{
            return redirect(url($this->ADMIN_ROUTE_NAME));
            }

        return view("admin.faqs.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $faq = "";
        $title = "Faq Details";

        if (is_numeric($id) && $id > 0) {
            $faq = Faq::where("id", $id)->first();
            //prd($faq_query);
            $title = "Faq Details (".$faq->question.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["faq"] = $faq;
        $data['segment'] = 'packages';
        $data["id"] = $id;
        return view("admin.faqs.view", $data);
    }

    public function delete(Request $request)
    {
        $tbl_id = (isset($request->tid))?$request->tid:0;
        $tbl_name = $request->tbl??($request->module??'');
        $segment = $request->segment??($request->segment??'');
        $category = (isset($request->category))?$request->category:'';  

        $id = !empty($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $faq = Faq::find($id);
                $last_data = DB::table('faqs')->where('id',$id)->first();
                $module_desc =  !empty($last_data->question)?$last_data->question:'';
                $last_data =(array) $last_data;
                $last_data = json_encode($last_data);
                /*$function_name = $this->currentUrl;
                $action_table = "faqs";
                $row_id = $id;
                $action_type = "Delete Faq";
                $faqQuestion = isset($faq->question) ? $faq->question:'';
                $action_description = "Delete (" . $faqQuestion . ")";
                $description = "Delete (" . $faqQuestion . ")";*/
                $is_delete = Faq::faqDelete($id);

                if ($is_delete['status'] != 'ok') {

                    // return redirect(url('admin/faqs'))->with('alert-danger', $is_delete['message']);
                    return redirect(route("admin.faqs.index",['tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$category,'segment'=>$segment]))->with('alert-danger', $is_delete['message']);

                } 
                else {

                    $delete_faq_name = $is_delete['name'];

                    $is_deleted = true;

                }
                //$is_deleted = $faq->delete();
            }
        }

        if ($is_deleted) {

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Faq';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $last_data??'';
            $params['action'] = "Delete";

            CustomHelper::RecordActivityLog($params);

            // return redirect(url($this->ADMIN_ROUTE_NAME . "/faqs"))->with(
            //     "alert-success",
            //     "Faq has been deleted successfully."
            // );
            return redirect(route("admin.faqs.index",['tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$category,'segment'=>$segment]))->with('alert-success', "Faq has been deleted successfully.");
        } else {
            // return redirect(url($this->ADMIN_ROUTE_NAME . "/faqs"))->with(
            //     "alert-danger",
            //     "Faq cannot be deleted, please try again or contact the administrator."
            // );
            return redirect(route("admin.faqs.index",['tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$category,'segment'=>$segment]))->with('alert-danger', "Faq cannot be deleted, please try again or contact the administrator.");
        }
    }

// Add Faqs Code Closed


    /* end of controller */
}
