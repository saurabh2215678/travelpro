<?php

namespace App\Http\Controllers\Admin;

use App\Models\Widget;
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

class WidgetController extends Controller
{
    protected $ADMIN_ROUTE_NAME;
    protected $currentUrl;
    private $limit;

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
        $limit = $this->limit;
        $widget_name = isset($request->widget_name) ? $request->widget_name : "";
        $status = isset($request->status) ? $request->status : 1;
        $widget_query = Widget::orderBy("created_at", "desc");
        //prd($widget_query);
        if (!empty($widget_name)) {
            $widget_query->where("widget_name","like","%" . $widget_name . "%"
            );
        }
    
        $widget_query->where("status", $status);
        $widgets = $widget_query->paginate($limit);

        $data["widgets"] = $widgets;

        return view("admin.widgets.index", $data);
    }

    public function add(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $widget_query = [];
        $title = "Add Widget";

        if (is_numeric($id) && $id > 0) {
            $widget_query = Widget::find($id);
            $title =
            "Edit Widget(" . $widget_query->widget_name . ")";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->toArray());
            $ext = "jpg,jpeg,png,gif";
            
            $rules["widget_name"] = "required";
            $rules["section_heading"] = "required";
            $rules["description"] = "required";
            $rules["status"] = "required";
            if(!empty($id)) {
                $rules["widget_identifier"] = 'required';
            }
            $rules["image1"] = "nullable|image|mimes:" . $ext;
            $rules["image2"] = "nullable|image|mimes:" . $ext;

            $this->validate($request, $rules);

            $req_data = [];
            $req_data = $request->except([
                "_token",
                "back_url",
                "widget_identifier",
                "image",
                "old_image1",
                "old_image2",
                "id",
                "featured"
            ]);

            $req_data['section_sub_heading'] = (!empty($request->section_sub_heading))?$request->section_sub_heading:'';
            $req_data['about_widget_desc'] = (!empty($request->about_widget_desc))?$request->about_widget_desc:'';

            $widget_identifier = '';
            if(empty($id) && !empty($request->widget_identifier)){
                $widget_identifier = '';CustomHelper::GetSlug('widgets', 'id', $id, $request->widget_identifier);
            }
            else if(!empty($id)){
                $widget_identifier = CustomHelper::GetSlug('widgets', 'id', $id, $request->widget_identifier);
            }

            //$req_data['slug'] = $widget_identifier;

            if (!empty($widget_query) && $widget_query->id == $id) {
                $req_data["slug"] = CustomHelper::GetSlug('widgets', 'id', $id, $request->widget_identifier);
                //prd($req_data);
                $isSaved = Widget::where("id",$widget_query->id)->update($req_data);
                $widget_query_id = $widget_query->id;
                $msg = "Widget has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "widgets";
                $row_id = $id;
                $action_type = "Edit On Widget";
                $action_description = "Edit On (" . $request->widget_query . ")";
                $description =
                "Update(" . $request->widget_query . ") " . $description;*/
            } else {
                $req_data["slug"] = CustomHelper::GetSlug('widgets', 'id', $id, $request->widget_name);
                //prd($req_data);
                $isSaved = Widget::create($req_data);
                $widget_query_id = $isSaved->id;

                if(empty($req_data['slug']) && !empty($widget_query_id)){
                    $widgetIdent = 'widget_'.$widget_query_id;
                    Widget::where("id",$widget_query_id)->update(['slug' => $widgetIdent]);
                }
                $msg = "Widget has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "widgets";
                $row_id = $id;
                $action_type = "Add On Widget";
                $action_description = "Add On (" . $request->widget_query . ")";
                $description =
                "Add(" . $request->widget_query . ") " . $description;*/
            }

            if ($isSaved) {
                if ($request->hasFile("image1")) {
                    $file = $request->file("image1");
                    $image_result = $this->saveImage($file, $widget_query_id, "image1");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }

                if ($request->hasFile("image2")) {
                    $file = $request->file("image2");
                    $image_result = $this->saveImage($file, $widget_query_id, "image2");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Icon could not be added"
                        );
                    }
                }

                cache()->forget("widgets");

                /*CustomHelper::recordActionLog(
                    $function_name,
                    $action_table,
                    $row_id,
                    $action_type,
                    $action_description,
                    $description
                );*/

                //=============Activity Logs=====================

                $new_data = DB::table('widgets')->where('id',$widget_query_id)->first();
                $module_desc =  !empty($new_data->widget_name)?$new_data->widget_name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $widget_query_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Widget';
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
                url($this->ADMIN_ROUTE_NAME . "/widget"))->with("alert-success", $msg);
            }else {
                return back()->with("alert-danger","The Widget could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["widget_query"] = $widget_query;
        $data["id"] = $id;

        return view("admin.widgets.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $widget_query = "";
        $title = "Widget";

        if (is_numeric($id) && $id > 0) {
            $widget_query = Widget::where("id", $id)->first();
            //prd($widget_query);
            $title = "Widget";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["widget_query"] = $widget_query;
        $data["id"] = $id;
        return view("admin.widgets.view", $data);
    }

    public function saveImage($file, $id, $type)
    {
        //prd($file);
        //echo $type; die;

        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "widgets/";
            $thumb_path = "widgets/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("WIDGET_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("WIDGET_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("WIDGET_THUMB_HEIGHT");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("WIDGET_THUMB_WIDTH");

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
                    $widget_query = Widget::find($id);

                    if (!empty($widget_query) && count([$widget_query]) > 0) {
                        $storage = Storage::disk("public");

                        if ($type == "image1") {
                            $old_image = $widget_query->image1;
                            $widget_query->image1 = $new_image;
                        } elseif ($type == "image2") {
                            $old_image = $widget_query->image2;
                            $widget_query->image2 = $new_image;
                        }

                        $isUpdated = $widget_query->save();

                        if ($isUpdated) {
                            if (
                                !empty($old_image) &&
                                $storage->exists($path . $old_image)
                            ) {
                                $storage->delete($path . $old_image);
                            }

                            if (
                                !empty($old_image) &&
                                $storage->exists($thumb_path . $old_image)
                            ) {
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
            $is_img_deleted = $this->delete_image($image_id, $type);
            if ($is_img_deleted) {
                $result["success"] = true;
                $result["msg"] =
                '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
            }
        }

        if($result['success'] == false){
            $result['msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }

        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();

        //prd($id);
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "widgets/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $widget_query = Widget::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "widgets";
                $row_id = $id;
                $action_type = "Delete Widget";
                $action_description = "Delete (" . $widget_query->widget_name . ")";
                $description = "Delete (" . $widget_query->widget_name . ")";*/

                $new_data = DB::table('widgets')->where('id',$id)->first();
                $module_desc =  !empty($new_data->widget_name)?$new_data->widget_name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                if (!empty($widget_query) && count(array($widget_query)) > 0) {
                    if (isset($widget_query->image) && !empty($widget_query->image)) {
                        $image1 = $widget_query->image1;
                        $image2 = $widget_query->image2;
                        if (
                            !empty($image1) &&
                            $storage->exists($path . "thumb/" . $image1)
                        ) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $image1
                            );
                        }
                        if (
                            !empty($image1) &&
                            $storage->exists($path . $image1)
                        ) {
                            $is_deleted = $storage->delete($path . $image1);
                        }
                        if (
                            !empty($image2) &&
                            $storage->exists($path . "thumb/" . $image2)
                        ) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $image2
                            );
                        }
                        if (!empty($image2) && $storage->exists($path . $image2)) {
                            $is_deleted = $storage->delete($path . $image2);
                        }
                    }
                    $is_deleted = $widget_query->delete();
                }
            }
        }
        if ($is_deleted) {
            /*CustomHelper::recordActionLog($function_name,$action_table, $row_id,$action_type,$action_description,$description);*/

            //=============Activity Logs=====================

            $params = [];
            $new_data = '';
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Widget';
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

            return redirect(url($this->ADMIN_ROUTE_NAME . "/widget"))->with(
                "alert-success",
                "The Widget has been deleted successfully."
            );
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/widget"))->with(
                "alert-danger",
                "The Widget cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function delete_image($id,$type){

        $is_deleted = '';
        $is_updated = '';
        $storage = Storage::disk('public');
        $path = 'widgets/';
        $widget_query = Widget::find($id);

        $image1 = (isset($widget_query->image1))?$widget_query->image1:'';
        $image2 = (isset($widget_query->image2))?$widget_query->image2:'';


        if($type =='image1'){
            if(!empty($image1) && $storage->exists($path.'thumb/'.$image1))
            {
                $is_deleted = $storage->delete($path.'thumb/'.$image1);
            }
            if(!empty($image1) && $storage->exists($path.$image1))
            {
                $is_deleted = $storage->delete($path.$image1);
            }
        }

        else if($type =='image2'){
            if(!empty($image2) && $storage->exists($path.'thumb/'.$image2))
            {
                $is_deleted = $storage->delete($path.'thumb/'.$image2);
            }
            if(!empty($image2) && $storage->exists($path.$image2))
            {
                $is_deleted = $storage->delete($path.$image2);
            }
        }

        if($is_deleted){
            if($type =='image1'){
                $widget_query->image1 = '';
            }
            else if($type =='image2'){
                $widget_query->image2 = '';
            }
            $is_updated = $widget_query->save();

        }
        return $is_updated;
    }

    // Add Team Management Code Closed......

    /* end of controller */
}
