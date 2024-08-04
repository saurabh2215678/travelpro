<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Models\TeamCategory;
use App\Models\TeamManagement;
use App\Models\ThemeCategories;
use Illuminate\Validation\Rule;
use App\Models\AccommodationCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use DB;
use Mail;
use Storage;
use Validator;

class TeamManagementController extends Controller
{
    protected $ADMIN_ROUTE_NAME;
    protected $currentUrl;
    protected $limit;
    
    public function __construct()
    {
        $this->limit = 10;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request)
    {
        $data = [];
        $limit = $this->limit;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $featured = isset($request->featured) ? $request->featured : "";
        $teamManagenent = TeamManagement::orderBy("sort_order", "asc");
        if (!empty($title)) {
            $teamManagenent->where(function($query) use($title){
                $query->where(DB::raw('CONCAT(`title`," ", `sub_title`)'), 'like', '%'.$title.'%');
                $query->orWhere('email', 'like', '%'.$title.'%');
            });
        }

        if(!empty($featured)){
            $teamManagenent->where('featured',$featured);
        }

        if(strlen($status) > 0){
            $teamManagenent->where("status", $status);
        }
        // $teammanagements = $teamManagenent->paginate($limit);
        $teammanagements = $teamManagenent->get();
        //prd($teammanagements);

        $data["teammanagements"] = $teammanagements;

        return view("admin.team_managements.index", $data);
    }

    public function add(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $teamManagenent = [];
        $title = "Add Team";

        if (is_numeric($id) && $id > 0) {
            $teamManagenent = TeamManagement::find($id);
            $title = "Edit Team (" . $teamManagenent->title . ")";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->toArray());
            $rules = [];
            $validation_msg = [];

            $ext = "jpg,jpeg,png,gif";
            $rules["title"] = "required";
            $rules["designation"] = "required";
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }
            // $rules["gender"] = "required";
            // $rules["email"] = "required|email";
            $rules["image"] = "nullable|image|mimes:" . $ext;
            //$validation_msg["gender.required"] = "The gender field is required";
            $validation_msg["title.required"] = "The Name field is required";
            $validation_msg["designation.required"] = "The Designation field is required";

            $this->validate($request, $rules,$validation_msg);
            $req_data = [];
            $req_data = $request->except(["_token","back_url","image","old_image","id","featured","slug",]);

            $req_data['title'] = (!empty($request->title))?$request->title:'';
            $req_data['sub_title'] = (!empty($request->sub_title))?$request->sub_title:'';
            $req_data['gender'] = (!empty($request->gender))?$request->gender:'';
            $req_data['email'] = (!empty($request->email))?$request->email:'';
            $req_data['designation'] = (!empty($request->designation))?$request->designation:'';
            $req_data['alt_email'] = (!empty($request->alt_email))?$request->alt_email:'';
            $req_data['mobile_no'] = (!empty($request->mobile_no))?$request->mobile_no:'';
            $req_data['brief_profile'] = (!empty($request->brief_profile))?$request->brief_profile:'';
            $req_data['detail_profile'] = (!empty($request->detail_profile))?$request->detail_profile:'';
            $req_data['facebook_link'] = (!empty($request->facebook_link))?$request->facebook_link:'';
            $req_data['twitter_link'] = (!empty($request->twitter_link))?$request->twitter_link:'';
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['featured'] = $request->featured??0;

            // $slug = "";
            // $fullName = $request->title;
            // if(empty($id) && !empty($fullName)) {
            //     $slug = CustomHelper::GetSlug("team_management","id",$id,$fullName);
            // }else{
            //    $slug = CustomHelper::GetSlug("team_management","id",$id,$request->slug); 
            // }

            // $req_data["slug"] = $slug;
            $req_data["category"] = isset($request->category)
                ? json_encode($request->category)
                : "[]";
            $req_data["trip_theme"] = isset($request->trip_theme)
                ? json_encode($request->trip_theme)
                : "[]";
                //prd($req_data);
            if(!empty($teamManagenent) && $teamManagenent->id == $id) {
                $req_data["slug"] = CustomHelper::GetSlug('team_management', 'id', $id, $request->slug);
                TeamManagement::where("id",$teamManagenent->id)->update($req_data);
                $teamManagenent_id = $teamManagenent->id;
                $isSaved = TRUE;
                $msg = "Team Management has been updated successfully.";
            } else {
                $req_data["slug"] = CustomHelper::GetSlug('team_management', 'id', $id, $request->title);
                $isSaved = TeamManagement::create($req_data);
                $teamManagenent_id = $isSaved->id;
                $msg = "Team Management has been added successfully.";
            }

            if($isSaved){
                if($request->hasFile("image")){
                    $file = $request->file("image");
                    $image_result = $this->saveImage($file, $teamManagenent_id, "image");
                    if($image_result["success"] == false){
                        session()->flash("alert-danger","Image could not be added");
                    }
                }

                cache()->forget("teammanagements");

                //=============Activity Logs=============

                $new_data = DB::table('team_management')->where('id',$teamManagenent_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $teamManagenent_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Team Management';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                //=============Activity Logs=================

                return redirect(url($this->ADMIN_ROUTE_NAME . "/teammanagements"))->with("alert-success", $msg);
            }else {
                return back()->with("alert-danger","The Team Management could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["teammanagements"] = TeamManagement::where("status", 1)
            ->orderBy("title", "asc")
            ->get();
        $data["teamcategories"] = TeamCategory::where("status", 1)->get();
        $data["themes"] = ThemeCategories::where("status", 1)->get();
        $data["page_heading"] = $title;
        $data["teamManagenent"] = $teamManagenent;
        $data["id"] = $id;

        return view("admin.team_managements.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $teamManagenent = "";
        $title = '';

        if(is_numeric($id) && $id > 0){
            $teamManagenent = TeamManagement::where("id", $id)->first();
            //prd($teamManagenent);
            $title = "Team Management";
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["teamManagenent"] = $teamManagenent;
        $data["id"] = $id;
        return view("admin.team_managements.view", $data);
    }

    public function saveImage($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "teammanagements/";
            $thumb_path = "teammanagements/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "TEAM_MANAGEMENT_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "TEAM_MANAGEMENT_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "TEAM_MANAGEMENT_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "TEAM_MANAGEMENT_IMG_THUMB_HEIGHT"
            );

            $IMG_WIDTH = !empty($IMG_WIDTH) ? $IMG_WIDTH : 768;
            $IMG_HEIGHT = !empty($IMG_HEIGHT) ? $IMG_HEIGHT : 768;
            $THUMB_WIDTH = !empty($THUMB_WIDTH) ? $THUMB_WIDTH : 336;
            $THUMB_HEIGHT = !empty($THUMB_HEIGHT) ? $IMG_WIDTH : 336;

            $uploaded_data = CustomHelper::UploadImage(
                $file,
                $path,
                $ext = "",
                $IMG_WIDTH,
                $IMG_HEIGHT,
                $is_thumb = true,
                $thumb_path,
                $THUMB_WIDTH,
                $THUMB_HEIGHT
            );

            if ($uploaded_data["success"]) {
                $new_image = $uploaded_data["file_name"];

                if (is_numeric($id) && $id > 0) {
                    $teamManagenent = TeamManagement::find($id);

                    if (!empty($teamManagenent)) {
                        $storage = Storage::disk("public");

                        $old_image = $teamManagenent->image;
                        $teamManagenent->image = $new_image;

                        $isUpdated = $teamManagenent->save();

                        if ($isUpdated) {
                            if (
                                !empty($old_image) &&
                                $storage->exists($path . $old_image)
                            ) {
                                $storage->delete($path . $old_image);
                            }

                            if(!empty($old_image) && $storage->exists($thumb_path . $old_image)) {
                                $storage->delete($thumb_path . $old_image);
                            }
                        }
                    }
                }
            }

            if (!empty($uploaded_data)) {
                return $uploaded_data;
            }
        }
    }

    public function ajax_delete_image(Request $request)
    {
        //prd($request->toArray());
        $result["success"] = false;

        $image_id = $request->has("image_id") ? $request->image_id : 0;
        $type = $request->has("type") ? $request->type : "";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_images($image_id, $type);
            if ($is_img_deleted) {
                $result["success"] = true;
                $result["msg"] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
            }
        }

        if ($result["success"] == false) {
            $result["msg"] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $id = isset($request->id) ? $request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "teammanagements/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $teamManagenent = TeamManagement::find($id);

                $new_data = DB::table('team_management')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_delete = TeamManagement::teamMembersDelete($id);

                if($is_delete['status'] != 'ok'){
                    return redirect(url('admin/teammanagements'))->with('alert-danger', $is_delete['message']);
                }else{
                    $delete_team_name = $is_delete['name'];
                    $is_deleted = true;
                }
                if (!empty($teamManagenent) && count([$teamManagenent]) > 0) {
                    if(isset($teamManagenent->image) && !empty($teamManagenent->image)){
                        $image = $teamManagenent->image;
                        if(!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                            $is_deleted = $storage->delete($path . "thumb/" . $image);
                        }
                        if(!empty($image) && $storage->exists($path . $image)){
                            $is_deleted = $storage->delete($path . $image);
                        }
                    }
                   // $is_deleted = $teamManagenent->delete();
                }
            }
        }
        if ($is_deleted) {

            //=============Activity Logs===============

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Team Managenent';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs================

            return redirect(url($this->ADMIN_ROUTE_NAME . "/teammanagements"))->with("alert-success","The Team Management has been deleted successfully.");
        }else{
            return redirect(url($this->ADMIN_ROUTE_NAME . "/teammanagements"))->with("alert-danger","The Page cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function delete_images($id, $type)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "teammanagements/";
        $teamManagenent = TeamManagement::find($id);

        $image = isset($teamManagenent->image) ? $teamManagenent->image : "";

        if ($type == "image") {
            if(!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if(!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if($is_deleted){
                if($type == "image"){
                    $teamManagenent->image = "";
                }
                $is_updated = $teamManagenent->save();
            }
        }
        return $is_updated;
    }

    // Add Team Category Code Start......

    public function category(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $teamCategory = TeamCategory::orderBy("title", "asc");
        if(!empty($title)){
            $teamCategory->where("title", "like", "%" . $title . "%");
        }
        
        $teamCategory->where("status", $status);
        
        $teamcategories = $teamCategory->paginate($limit);
        $data["teamcategories"] = $teamcategories;

        return view("admin.team_categories.index", $data);
    }

    public function add_category(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $teamCategory = [];
        $title = "Add Team Category";

        if (is_numeric($id) && $id > 0) {
            $teamCategory = TeamCategory::find($id);
            $title = "Edit Team Category(" . $teamCategory->title . " )";
        }
        if ($request->method() == "POST" || $request->method() == "post") {

            $rules = [];
            $rules["title"] = 'required|regex:/^[a-zA-Z ""]+$/u|max:255';
            $rules["status"] = "required";

            $this->validate($request, $rules);

            $req_data = [];
            $req_data = $request->except(["_token","page","back_url","featured","id",]);

            if(!empty($teamCategory) && $teamCategory->id == $id){
                $isSaved = TeamCategory::where("id", $teamCategory->id)->update($req_data);
                $team_categories_id = $teamCategory->id;
                $msg = "Team Category has been updated successfully.";
            }else {
                $isSaved = TeamCategory::create($req_data);
                $team_categories_id = $isSaved->id;
                $msg = "Team Category has been added successfully.";
            }

            if ($isSaved) {

                cache()->forget("team_categories");

                //=============Activity Logs=================

                $new_data = DB::table('team_categories')->where('id',$team_categories_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $team_categories_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Team Categories';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                //=============Activity Logs=================

                return redirect(url($this->ADMIN_ROUTE_NAME . "/teammanagements/categories"))->with("alert-success", $msg);
            }else {
                return back()->with("alert-danger","The Team Category List could be added, please try again or contact the administrator.");
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["teamCategory"] = $teamCategory;
        $data["id"] = $id;

        return view("admin.team_categories.form", $data);
    }

    public function category_delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $teamCategory = TeamCategory::find($id);

                $new_data = DB::table('team_categories')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_deleted = $teamCategory->delete();
            }
        }

        if ($is_deleted) {

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Team Categories';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = 'Delete';
            CustomHelper::RecordActivityLog($params);

            return redirect(url($this->ADMIN_ROUTE_NAME . "/teammanagements/categories"))->with("alert-success","Team Category has been deleted successfully.");
        }else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/teammanagements/categories"))->with("alert-danger","Team Category cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function changeUserStatus(Request $request)
    {
        $teamCategory = TeamCategory::find($request->id);
        $teamCategory->status = $request->status;
        $teamCategory->save();

        return response()->json(["success" => "Team Category status change successfully.",
        ]);
    }


    public function update_order(Request $request){

        $response['success'] = false;
        $response['msg'] = '';
        $found = 0;
        $getAllData = $request->data;

        if(isset($getAllData) && !empty($getAllData)){
            $found = 1;
            foreach($getAllData as $key => $value) {
               $getId = $value;
               $req_data['sort_order'] = $key+1;
               $isSaved = TeamManagement::where('id',$getId)->update($req_data);
            }
        }
        if($found) {
            $response['success'] = true;
            $response['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Order Updated Successfully.</div>';
        }
        return response()->json($response);
    }
    // Add Team Category Code Start......
}