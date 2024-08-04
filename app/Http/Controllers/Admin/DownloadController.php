<?php

namespace App\Http\Controllers\Admin;


use App\Models\Download;
use App\Models\Other;
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

class DownloadController extends Controller
{
    protected $currentUrl;
    protected $limit;
    protected $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->limit = 10;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    // Add Download Code Here


    public function index(Request $request)
    {
        $data = [];
        $limit = $this->limit;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : "";
        $download = Download::orderBy("title", "desc");
        if (!empty($title)) {
            $download->Where('title', 'like', '%'.$title.'%');
        
        }
        if (strlen($status) > 0) {
            $download->where("status", $status);
        }
        $downloads = $download->paginate($limit);
        //prd($downloads);

        $data["downloads"] = $downloads;

        return view("admin.downloads.index", $data);
    }

    public function add(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $download = [];
        $title = "Add Download";

        if (is_numeric($id) && $id > 0) {
            $download = Download::find($id);
            $title =
                "Edit Download(" . $download->title . ")";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->toArray());
            $ext = "jpg,jpeg,png,gif";
            
            $rules["title"] = "required|max:255";
            $rules["brief"] = "required";
            $rules["image"] = "nullable|image|mimes:" . $ext;
            $rules["documents"] = 'nullable|max:2000|mimes:doc,docs,pdf';

            
            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "back_url",
                "documents",
                "old_documents",
                "image",
                "old_image",
                "id",
                "featured",
                "slug",
            ]);
            //prd($req_data);
            if (!empty($download) && $download->id == $id) {
                Download::where("id",$download->id)->update($req_data);
                $isSaved = TRUE;
                $download_id = $download->id;
                $msg = "Download has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "downloads";
                $row_id = $id;
                $action_type = "Edit On Download";
                $action_description = "Edit On (" . $request->title . ")";
                $description =
                    "Update(" . $request->title . ") " . $description;*/
            } else {
                $isSaved = Download::create($req_data);
                $download_id = $isSaved->id;
                $msg = "Download has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "downloads";
                $row_id = $id;
                $action_type = "Add On Download";
                $action_description = "Add On (" . $request->title . ")";
                $description =
                    "Add(" . $request->title . ") " . $description;*/
            }
            if ($isSaved) {

            if ($request->hasFile("image")) {
                $file = $request->file("image");
                $image_result = $this->saveImage($file, $download_id, "image");
                if ($image_result["success"] == false) {
                    session()->flash(
                        "alert-danger",
                        "Image could not be added"
                    );
            }
        }

            if($request->hasFile('documents')) {
                $file = $request->file('documents');

                if(!empty($file)){
                    // $pdf_result = $this->saveDocuments($file,$download_id,"documents");
                    $pdf_result = $this->saveDocuments($file,$download_id);
                    //prd($pdf_result);
                    if($pdf_result['success']== false){
                        session()->flash('alert-danger', 'Document could not be added');
                    }
                }
            }
                cache()->forget("downloads");

                /*CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
                );*/

                //=============Activity Logs=====================

                $new_data = DB::table('downloads')->where('id',$download_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $download_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Download';
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
                url($this->ADMIN_ROUTE_NAME . "/downloads"))->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Download could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["download"] = $download;
        $data["id"] = $id;

        return view("admin.downloads.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $download = "";
        $title = "Download Details";

        if (is_numeric($id) && $id > 0) {
            $download = Download::where("id", $id)->first();
            //prd($teamManagenent);
            $title = "Download Details (".$download->title.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["download"] = $download;
        $data["id"] = $id;
        return view("admin.downloads.view", $data);
    }

    public function saveImage($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "downloads/";
            $thumb_path = "downloads/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "DOWNLOAD_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "DOWNLOAD_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "DOWNLOAD_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "DOWNLOAD_IMG_THUMB_HEIGHT"
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
                    $download = Download::find($id);

                    if (!empty($download)) {
                        $storage = Storage::disk("public");

                        $old_image = $download->image;
                        $download->image = $new_image;

                        $isUpdated = $download->save();

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

        if ($result["success"] == false) {
            $result["msg"] =
                '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
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
        $path = "downloads/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $download = Download::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "downloads";
                $row_id = $id;
                $action_type = "Delete Download";
                $action_description = "Delete (" . $download->title . ")";
                $description = "Delete (" . $download->title . ")";*/

                $new_data = DB::table('downloads')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                if (!empty($download) && count([$download]) > 0) {
                    if (
                        !empty($download->image)
                    ) {
                        $image = $download->image;
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
                    }
                    $is_deleted = $download->delete();
                }
            }
        }
        if ($is_deleted) {
            /*CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Download';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/downloads"))->with("alert-success",
                "The Download has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/downloads")
            )->with(
                "alert-danger",
                "The Page cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function delete_image($id, $type)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "downloads/";
        $download = Download::find($id);

        $image = isset($download->image) ? $download->image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $download->image = "";
                }
                $is_updated = $download->save();
            }
        }
        return $is_updated;
    }

    public function saveDocuments($file, $id){
        //prd($file); 
        //echo $id; die;
        $result['org_name'] = '';
        $result['file_name'] = '';

        if ($file) 
        {
            $path = 'downloads/documents/';

            $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');

            if($uploaded_data['success']){
                $new_documents = $uploaded_data['file_name'];

                if(is_numeric($id) && $id > 0){
                    $download = Download::find($id);

                    if(!empty($download) && ($download->count()) > 0){

                        $storage = Storage::disk('public');

                        $old_documents = $download->documents;

                        $download->documents = $new_documents;

                        $isUpdated = $download->save();

                        if($isUpdated){

                            if(!empty($old_documents) && $storage->exists($path.$old_documents)){
                                $storage->delete($path.$old_documents);
                            }
                        }
                    }
                }
                
            }

            if(!empty($uploaded_data))
            {   
                return $uploaded_data;
            }
        }
    }

    public function ajax_delete_documents(Request $request){

        $result['success'] = false;

        $id = ($request->has('id'))?$request->id:0;

        //prd($id);

        if (is_numeric($id) && $id > 0) {
            $is_documents_deleted = $this->delete_documents($id);
            if($is_documents_deleted)
            {
                $result['success'] = true;
                $result['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Pdf has been delete successfully.</div>';
            }
        }

        if($result['success'] == false){
            $result['msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function delete_documents($id)
    {   
        $is_deleted = '';
        $is_updated = '';
        $storage = Storage::disk('public');
        $path = 'downloads/documents/';
        $download = Download::find($id);
        
        $documents = (isset($download->documents))?$download->documents:'';

        if(!empty($documents) && $storage->exists($path.$documents))
        {
            $is_deleted = $storage->delete($path.$documents);
        }
        if($is_deleted){
           $download->documents = '';
           $is_updated = $download->save();

       }
       return $is_updated;
   }
    // ======Download Code Closed========

    // =========Others Code Here=========

    public function others(Request $request)
    {
        $data = [];
        $limit = $this->limit;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : "";
        $other = Other::orderBy("title", "desc");
        if (!empty($title)) {
            $other->Where('title', 'like', '%'.$title.'%');
            
        }
        if (strlen($status) > 0) {
            $other->where("status", $status);
        }
        $others = $other->paginate($limit);
        //prd($others);

        $data["others"] = $others;

        return view("admin.others.index", $data);
    }

    public function add_other(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $other = [];
        $title = "Add Other";

        if (is_numeric($id) && $id > 0) {
            $other = Other::find($id);
            $title =
                "Edit Other(" . $other->title . ")";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->toArray());
            $ext = "jpg,jpeg,png,gif";

            $rules["title"] = "required";
            $rules["brief"] = "required";
            $rules["image"] = "nullable|image|mimes:" . $ext;

            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "back_url",
                "image",
                "old_image",
                "id",
                "featured",
                "slug",
            ]);
            //prd($req_data);
            if (!empty($other) && $other->id == $id) {
                Other::where("id",$other->id)->update($req_data);

                $isSaved = TRUE;
                $other_id = $other->id;
                $msg = "Other has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "others";
                $row_id = $id;
                $action_type = "Edit On Other";
                $action_description = "Edit On (" . $request->title . ")";
                $description =
                    "Update(" . $request->title . ") " . $description;*/
            } else {
                $isSaved = Other::create($req_data);
                $other_id = $isSaved->id;
                $msg = "Other has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "others";
                $row_id = $id;
                $action_type = "Add On Other";
                $action_description = "Add On (" . $request->title . ")";
                $description =
                    "Add(" . $request->title . ") " . $description;*/
            }

            if ($isSaved) {
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImageOther($file, $other_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }

                cache()->forget("others");

                /*CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description, $description
                );*/

                //=============Activity Logs=====================

                $new_data = DB::table('others')->where('id',$other_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $other_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Other';
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
                    url($this->ADMIN_ROUTE_NAME . "/downloads/others"))->with("alert-success", $msg);
            }else {
                return back()->with(
                    "alert-danger",
                    "The Download could be added, please try again or contact the administrator."
                );
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["other"] = $other;
        $data["id"] = $id;

        return view("admin.others.form", $data);
    }

    public function other_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $other = "";
        $title = "Other Details";

        if (is_numeric($id) && $id > 0) {
            $other = Other::where("id", $id)->first();
            //prd($other);
            $title = "Other Details (".$other->title.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["other"] = $other;
        $data["id"] = $id;
        return view("admin.others.view", $data);
    }

    public function saveImageOther($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "others/";
            $thumb_path = "others/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "OTHERS_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "OTHERS_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "OTHERS_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "OTHERST_IMG_THUMB_HEIGHT"
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
                    $other = Other::find($id);

                    if (!empty($other)) {
                        $storage = Storage::disk("public");

                        $old_image = $other->image;
                        $other->image = $new_image;

                        $isUpdated = $other->save();

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

    public function ajax_delete_other_image(Request $request)
    {
        //prd($request->toArray());
        $result["success"] = false;

        $image_id = $request->has("image_id") ? $request->image_id : 0;
        $type = $request->has("type") ? $request->type : "";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_images($image_id, $type);
            if ($is_img_deleted) {
                $result["success"] = true;
                $result["msg"] =
                    '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
            }
        }

        if ($result["success"] == false) {
            $result["msg"] =
                '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function others_delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();

        //prd($id);
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "others/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $other = Other::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "others";
                $row_id = $id;
                $action_type = "Delete Other";
                $action_description = "Delete (" . $other->title . ")";
                $description = "Delete (" . $other->title . ")";*/

                $new_data = DB::table('others')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                if (!empty($other) && count([$other]) > 0) {
                    if (
                        !empty($other->image)
                    ) {
                        $image = $other->image;
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
                    }
                    $is_deleted = $other->delete();
                }
            }
        }
        if ($is_deleted) {
            /*CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Other';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/downloads/others")
            )->with(
                "alert-success",
                "The Other has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/downloads/others")
            )->with(
                "alert-danger",
                "The Page cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function delete_images($id, $type)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "others/";
        $other = Other::find($id);

        $image = isset($other->image) ? $other->image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $other->image = "";
                }
                $is_updated = $other->save();
            }
        }
        return $is_updated;
    }

    // Add Others Code Closed......

    /* end of controller */
}
