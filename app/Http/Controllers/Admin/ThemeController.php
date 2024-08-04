<?php
namespace App\Http\Controllers\Admin;

use App\Models\ThemeCategories;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Mail;
use Validator;
use Illuminate\Validation\Rule;

use Storage;
use DB;

class ThemeController extends Controller
{
    //private $limit;
    protected $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct()
    {
        //$this->limit = 15;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request)
    {
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        $data = [];
        $limit = 50;
        $segment2 = $request->segment(2);
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $identifier = 'package';
        if($segment2 == 'activity'){
           $identifier = 'activity';
        }
        $theme_query = ThemeCategories::withCount('Packages')->where('identifier',$identifier)->orderBy("sort_order", "asc");

        if (is_numeric($parent_id) && $parent_id > 0) {
            $theme_query->where("parent_id", $parent_id);
        } else {
            $theme_query->where(function ($query) {
                $query->where("parent_id", 0);
                $query->orWhereNull("parent_id");
            });
        }
        if (!empty($title)) {
            $theme_query->where("title", "like", "%" . $title . "%");
        }
        if (strlen($status) > 0) {
            $theme_query->where("status", $status);
        }
        $themes = $theme_query->paginate($limit);

        $data["themes"] = $themes;
        $data["segment"] = $segment2;

        return view("admin.themes.index", $data);
    }

    public function add(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $segment2 = $request->segment(2);
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        $theme_query = [];
        $module = "Package";
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
            $module = "Activity";
        }
        $title = "Add ".$module." Theme";
        $data['packages']=array();
        if (is_numeric($id) && $id > 0) {
            $theme_query = ThemeCategories::find($id);
            if($identifier == 'activity'){
                $title = "Edit Activity Theme(" . $theme_query->title . " )";
            }else{
                $title = "Edit Package Theme(" . $theme_query->title . " )";
            }
            $data['packages'] = DB::table('package_themes')->where('theme_id',$id)->get();
        }
        if (is_numeric($parent_id) && $parent_id > 0) {
            $theme_query = ThemeCategories::find($parent_id);
            //prd($theme_query->toArray());
            $title = "Add Theme(" . $theme_query->title . " )";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->toArray());
            $ext = "jpg,jpeg,png,gif";
            $ext2 = "jpg,jpeg,png,gif,pdf";

            $rules["title"] = 'required|max:255';
            $validation_msg["title.required"] = "The Title field is required.";

            if(empty($data['packages'])){
                $rules["status"] = "required";
            }
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }
            $rules["image"] = "nullable|image|mimes:" . $ext;
            $rules["icon"] = "nullable|image|mimes:" . $ext2;

            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "old_image",
                "banner_old_icon",
                "image",
                "page",
                "icon",
                "back_url",
            ]);

            $req_data['brief'] = !empty($request->brief)?$request->brief:'';
            $req_data['identifier'] = !empty($request->identifier)?$request->identifier:'';
            $req_data['description'] = (!empty($request->description))?$request->description:'';
            // $req_data['status'] = (!empty($request->status))?$request->status:1;
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['featured'] = (!empty($request->featured))?$request->featured:0;

            $req_data['page_title'] = (!empty($request->page_title))?$request->page_title:'';
            $req_data['page_description'] = (!empty($request->page_description))?$request->page_description:'';
            $req_data['page_keywords'] = (!empty($request->page_keywords))?$request->page_keywords:'';
            
            if(empty($theme_query->id)) {
                $slug = CustomHelper::GetSlug("themes","id",$id,$request->title
                );
            }else {
                $slug = CustomHelper::GetSlug("themes","id",$id,$request->slug);
            }
            $req_data["slug"] = $slug;
            $req_data["featured"] = isset($request->featured) ? 1 : 0;
            $req_data["sort_order"] = isset($request->sort_order)? $request->sort_order: 0;
            //prd($req_data);
            if (!empty($theme_query) && $theme_query->id == $id) {
                $isSaved = ThemeCategories::where("id", $theme_query->id)->update($req_data);
                $theme_id = $theme_query->id;
                $msg = $module." Theme has been updated successfully.";

               /* $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "themes";
                $row_id = $id;
                $action_type = "Edit On Theme";
                $action_description = "Edit On (" . $request->title . ")";
                $description =
                    "Update(" . $request->title . ") " . $description;*/
            } else {
                $isSaved = ThemeCategories::create($req_data);
                $theme_id = $isSaved->id;
                $msg = $module." Theme has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "themes";
                $row_id = $id;
                $action_type = "Add On Theme";
                $action_description = "Add On (" . $request->title . ")";
                $description = "Add(" . $request->title . ") " . $description;*/
            }

            if ($isSaved) {
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImage($file, $theme_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }

                if ($request->hasFile("icon")) {
                    $file = $request->file("icon");
                    $image_result = $this->saveImage($file, $theme_id, "icon");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Icon could not be added"
                        );
                    }
                }

                cache()->forget("themes");

                /*CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
                );*/

                //=============Activity Logs=====================

                $new_data = DB::table('themes')->where('id',$theme_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $theme_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = $module.' Theme';
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



                return redirect(url($this->ADMIN_ROUTE_NAME ."/".$segment2."/theme?parent_id=".$parent_id))->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The ".$module." Theme could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["theme_query"] = $theme_query;
        $data['packages'] = DB::table('package_themes')->where('theme_id',$id)->get();  
        $data["id"] = $id;
        $data["segment"] = $segment2;
        $data["identifier"] = $identifier;
        $data["parent_id"] = $parent_id;

        return view("admin.themes.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $theme_query = "";
        $title = "Theme Details";
        $segment2 = $request->segment(2);
        $identifier = 'package';
        if($segment2 == 'activity'){
         $identifier = 'activity';
        }

        if (is_numeric($id) && $id > 0) {
            $theme_query = ThemeCategories::leftjoin('package_themes as PT','PT.theme_id','=','themes.id')->leftjoin('packages as P','P.id','=','PT.package_id')->selectRaw('themes.*,group_concat(distinct(P.title)) as packagesName')->where("themes.id", $id)->groupBy('themes.id')->first();
            //prd($theme_query);
            $title = "Theme Details (".$theme_query->title.")";
        }
#prd($theme_query);
        $data = [];
        $data["page_heading"] = $title;
        $data["theme_query"] = $theme_query;
        $data["segment"] = $segment2;
        $data["id"] = $id;
        return view("admin.themes.view", $data);
    }

    public function saveImage($file, $id, $type)
    {
        //prd($file);
        //echo $type; die;

        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "themes/";
            $thumb_path = "themes/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("THEME_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("THEME_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("THEME_THUMB_HEIGHT");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("THEME_THUMB_WIDTH");

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
                    $theme_query = ThemeCategories::find($id);

                    if (!empty($theme_query) && count([$theme_query]) > 0) {
                        $storage = Storage::disk("public");

                        if ($type == "image") {
                            $old_image = $theme_query->image;
                            $theme_query->image = $new_image;
                        } elseif ($type == "icon") {
                            $old_image = $theme_query->icon;
                            $theme_query->icon = $new_image;
                        }

                        $isUpdated = $theme_query->save();

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
        $result["msg"] ='<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';

        $image_id = $request->has("image_id") ? $request->image_id : 0;
        $type = $request->has("type") ? $request->type : "";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_image($image_id, $type);
            if ($is_img_deleted) {
                $result["success"] = true;
                $result["msg"] ='<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
            }
        }

        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $new_data = '';

        //prd($id);
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "themes/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $theme_query = ThemeCategories::find($id);
                $identifier = $theme_query->identifier??'';
                $url = "packages";
                $module = "Package";
                if($identifier == 'activity') {
                    $module = "Activity";
                    $url = "activity";
                }
                /*$function_name = $this->currentUrl;
                $action_table = "themes";
                $row_id = $id;
                $action_type = "Delete Theme";
                $themeTitles = isset($theme_query->title) ? $theme_query->title:"";
                $action_description = "Delete (" . $themeTitles . ")";
                $description = "Delete (" . $themeTitles . ")";*/
                $new_data = DB::table('themes')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = ThemeCategories::packageThemesDelete($id);
                if ($is_delete['status'] != 'ok') {

                return redirect(url('admin/theme'))->with('alert-danger', $is_delete['message']);

                } 
                else {

                $delete_theme_name = $is_delete['name'];

                $is_deleted = true;

                }
                if (!empty($theme_query) && count([$theme_query])) {
                    if (
                        count([$theme_query]) > 0 &&
                        !empty($theme_query->image)
                    ) {
                        $image = $theme_query->image;
                        $icon = $theme_query->icon;
                        if (
                            !empty($image) &&
                            $storage->exists($path . "thumb/" . $image)
                        ) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $image
                            );
                        }
                        if (
                            !empty($image) &&
                            $storage->exists($path . $image)
                        ) {
                            $is_deleted = $storage->delete($path . $image);
                        }
                        if (
                            !empty($icon) &&
                            $storage->exists($path . "thumb/" . $icon)
                        ) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $icon
                            );
                        }
                        if (!empty($icon) && $storage->exists($path . $icon)) {
                            $is_deleted = $storage->delete($path . $icon);
                        }
                    }
                    //$is_deleted = $theme_query->delete();
                }
            }
        }
        if ($is_deleted) {

            /* CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = $module.' Theme';
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

            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/theme"))->with(
                "alert-success",
                "The ".$module." Theme has been deleted successfully."
            );
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."./theme"))->with(
                "alert-danger",
                "The ".$module." Theme cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function delete_image($id, $type)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "themes/";
        $theme_query = ThemeCategories::find($id);

        $image = isset($theme_query->image) ? $theme_query->image : "";
        $icon = isset($theme_query->icon) ? $theme_query->icon : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $theme_query->image = "";
                }
                $is_updated = $theme_query->save();
            }
        }
        else if ($type == "icon") {
            if (!empty($icon) && $storage->exists($path . "thumb/" . $icon))
            {
                $is_deleted = $storage->delete($path . "thumb/" . $icon);
            }

            if (!empty($icon) && $storage->exists($path . $icon)) {
                $is_deleted = $storage->delete($path . $icon);
            }

            if ($is_deleted) {
                if ($type == "icon") {
                    $theme_query->icon = "";
                }
                $is_updated = $theme_query->save();
            }
        }

        return $is_updated;
    }
    // End of controller
}
?>
