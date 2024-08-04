<?php

namespace App\Http\Controllers\Admin;

use App\Models\PackageCategory;
use App\Models\Package;


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
use Carbon\Carbon as Carbon;

class PackageCategoryController extends Controller
{
    protected $currentUrl;
    protected $limit;
    protected $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->limit = 15;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }
    // Package Categories
    public function packageCategories(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $segment2 = $request->segment(2);
        $identifier = 'package';
        if($segment2 == 'activity'){
           $identifier = 'activity';
        }
        $packageCategory = PackageCategory::where('identifier',$identifier)->orderBy("title", "asc");
        if (!empty($title)) {
                $packageCategory->where("title","like","%" . $title . "%");
        }
        $packageCategory->where("status", $status);
        $packagescategories = $packageCategory->paginate($limit);

        $data["segment"] = $segment2;
        $data["packagescategories"] = $packagescategories;
        return view("admin.package_categories.index", $data);
    }

    public function packageCategoryAdd(Request $request)
    {
        $id = isset($request->id) ? $request->id :'';
        $segment2 = $request->segment(2);
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $packageCategory = [];
        $module = "Package";
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
            $module = "Activity";
        }
        $title = "Add ".$module." Image Category";

        if (is_numeric($id) && $id > 0) {
            $packageCategory = PackageCategory::find($id);
            if($identifier == 'activity'){
                $title = "Edit Activity Image Category(" . $packageCategory->title . " )";
            }else{
                $title = "Edit Package Image Category(" . $packageCategory->title . " )";
            }
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules = [];
            $validation_msg = [];
            $rules["title"] = 'required|max:255';
            $validation_msg['title.required'] = 'The Package Category Title field is required.';

            $this->validate($request, $rules,$validation_msg);
            $req_data = [];
            $req_data = $request->except(["_token","page","back_url","featured",]);

            $req_data['identifier'] = !empty($request->identifier)?$request->identifier:'';
            if(empty($packageCategory->id)) {
                $slug = CustomHelper::GetSlug("package_categories","id",$id,$request->title);
            }else {
                $slug = CustomHelper::GetSlug("package_categories","id",$id,$request->slug);
            }
            $req_data["slug"] = $slug;

            if (!empty($packageCategory) && $packageCategory->id == $id) {
                $isSaved = PackageCategory::where("id", $packageCategory->id)->update($req_data);
                $package_category_id = $packageCategory->id;
                $msg = $module." Image Category has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "package_types";
                $row_id = $id;
                $action_type = "Edit On Package Type";
                $action_description = "Edit On (" . $request->package_type . ")";
                $description =
                "Update(" . $request->package_type . ") with : " . $description;*/
            } else {
                $isSaved = PackageCategory::create($req_data);
                $package_category_id = $isSaved->id;
                $msg = $module." Image Category has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "package_types";
                $row_id = $id;
                $action_type = "Add On Package Type";
                $action_description = "Add On (" . $request->package_type . ")";
                $description =
                "Add(" . $request->package_type . ") with : " . $description;*/
            }

            if ($isSaved) {
                cache()->forget("package_categories");

               /* CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
           );*/

                 //=============Activity Logs=====================

           $new_data = DB::table('package_categories')->where('id',$package_category_id)->first();
           $module_desc =  !empty($new_data->title)?$new_data->title:'';
           $new_data =(array) $new_data;
           $new_data = json_encode($new_data);

           $module_id = $package_category_id;

           $params['user_id'] = $user_id;
           $params['user_name'] = $user_name;
           $params['module'] = $module.' Category';
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

           return redirect(
            url($this->ADMIN_ROUTE_NAME . "/".$segment2."/package-category")
        )->with("alert-success", $msg);
       } else {
        return back()->with(
            "alert-danger","The Package Image Category could be added, please try again or contact the administrator."
        );
    }
}

$data = [];
$data["page_heading"] = $title;
$data["segment"] = $segment2;
$data["identifier"] = $identifier;
$data["packageCategory"] = $packageCategory;
$data["id"] = $id;

return view("admin.package_categories.form", $data);
}

public function packageCategoryDelete(Request $request)
{
    $id = isset($request->id)?$request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $method = $request->method();
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {

            $packageCategory = PackageCategory::find($id);
            $identifier = $packageCategory->identifier??'';
            $url = "packages";
            $module = "Package";
            if($identifier == 'activity') {
                $module = "Activity";
                $url = "activity";
            }

                /*$function_name = $this->currentUrl;
                $action_table = "package_types";
                $row_id = $id;
                $action_type = "Delete Package Type";
                $action_description = "Delete (" . $package_query->package_type . ")";
                $description = "Delete (" . $package_query->package_type . ")";*/

                $new_data = DB::table('package_categories')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_deleted = $packageCategory->delete();
            }
        }

        if ($is_deleted) {

           /* CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
       );*/

            //=============Activity Logs=====================

       $params = [];
       $params['user_id'] = $user_id;
       $params['user_name'] = $user_name;
       $params['module'] = $module.' Category';
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

       return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/package-category"))->with("alert-success", $module." Image Category has been deleted successfully.");
   } else {

    return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/package-category"))->with("alert-danger",$module." Image Category cannot be deleted, please try again or contact the administrator.");
}
}

public function packageCategoryview(Request $request)
{
    $id = isset($request->id) ? $request->id : 0;
    $packageCategory = "";
    $segment2 = $request->segment(2);
    $identifier = 'package';
    if($segment2 == 'activity'){
       $identifier = 'activity';
   }
    $title = $module." Category";

    if (is_numeric($id) && $id > 0) {
        $packageCategory = PackageCategory::where("id", $id)->where('identifier',$identifier)->first();
            //prd($destination_type);
        $title = $module." Category";
    }

    $data = [];
    $data["page_heading"] = $title;
    $data["segment"] = $segment2;
    $data["packageCategory"] = $packageCategory;
    $data["id"] = $id;
    return view("admin.package_categories.view", $data);
}

public function changeStatus(Request $request)
{

    $packageCategory = PackageCategory::find($request->id);
    $packageCategory->status = $request->status;
    $packageCategory->save();
    return response()->json([
        "success" => "Package Image Category status change successfully.",
    ]);
}
// Package Type Closed

/* end of controller */
}
