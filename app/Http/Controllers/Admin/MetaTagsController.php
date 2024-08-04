<?php
namespace App\Http\Controllers\Admin;


use App\Models\SeoMetaTag;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

use DB;
use Image;
use Storage;
use Validator;

class MetaTagsController extends Controller
{
    private $limit;
    protected $ADMIN_ROUTE_NAME;
    public function __construct(){
        $this->limit = 50;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }
    public function index(Request $request)
    {
        $data = [];
        $limit = $this->limit;
        $id = isset($request->id) ? $request->id:'';

        $identifier = (isset($request->identifier))?$request->identifier:'';
        $status = (isset($request->status))?$request->status:'';

        // $name = (isset($request->name))?$request->name:'';
        // $status = (isset($request->status))?$request->status:'';
        // $from = (isset($request->from))?$request->from:'';
        // $to = (isset($request->to))?$request->to:'';

        $d_query = SeoMetaTag::where('module_type','major')->orderBy('module_type','asc')->orderBy('id', 'asc')->where('is_disable',0);
        if($request->identifier) {
            if(is_array($request->identifier)) {
                $identifier_arr = $request->identifier;
                }else {
                $identifier_arr = explode(',', $request->identifier);
            }
            $d_query->whereIn('identifier', $identifier_arr);
            }
    

        // if(!empty($name)){
        //     $d_query->where(function($query) use($name){
        //         $query->where('name', 'like', '%'.$name.'%');
        //         $query->orWhere('nicename', 'like', '%'.$name.'%');
        //     });
        // }

         if ($request->has('status')) {
           if( strlen($status) > 0 ){
            $d_query->where('status', $status);
           }
        }else{
            $d_query->where('status', 1);

        }
        
        $res = $d_query->paginate($limit);



        $d_queries = SeoMetaTag::where('module_type','minor')->orderBy('module_type','asc')->orderBy('id', 'asc')->where('is_disable',0);
        if($request->identifier) {
            if(is_array($request->identifier)) {
                $identifier_arr = $request->identifier;
                }else {
                $identifier_arr = explode(',', $request->identifier);
            }
            $d_queries->whereIn('identifier', $identifier_arr);
            }
                   

        // if(!empty($name)){
        //     $d_query->where(function($query) use($name){
        //         $query->where('name', 'like', '%'.$name.'%');
        //         $query->orWhere('nicename', 'like', '%'.$name.'%');
        //     });
        // }

         if ($request->has('status')) {
           if( strlen($status) > 0 ){
            $d_queries->where('status', $status);
           }
        }else{
            $d_queries->where('status', 1);

        }
        
        $res2 = $d_queries->paginate($limit);

        

        $data['res'] = $res;    
        $data['res2'] = $res2;        
        $data['limit'] = $limit;

        return view('admin.seo_meta_tags.index', $data);
    }

    public function save(Request $request, $id= '')
    {
     $data= [];
     $id = isset($request->id) ? $request->id:'';
     $user_id = auth()->user()->id;
     $user_name = auth()->user()->name;
     $seoMetaTitle = SeoMetaTag::find($id);
     if(!empty($id)){
       $page_heading= 'Edit Module ('.$seoMetaTitle->identifier.')';
       $data['rec']= SeoMetaTag::where(['id'=>$id])->first(); 
   } 
   $method= $request->method(); 
   if($method=='POST')
   { 
       $ext = "jpg,jpeg,png,gif";
       $rules = [];
       $rules['page_brief'] = 'nullable';
       $rules['page_description'] = 'nullable';
       $rules['page_description'] = 'nullable';
       $rules['page_url_label'] = 'nullable';
       $rules['page_detail_url'] = 'nullable';
       $rules['meta_title'] = 'nullable';
       $rules['meta_keyword'] = 'nullable';
       $rules['meta_description'] = 'nullable';
       $rules['admin_email'] = 'nullable|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
       $rules['admin_phone'] = 'nullable|min:4|max:12|regex:/^([0-9\s\-\+\(\)]*)$/';
       $rules["image"] = "nullable|image|mimes:" . $ext;
       $this->validate($request, $rules);
       $req_data = [];
            $req_data = $request->except([
                "_token",
                "back_url",
                "image",
                "old_image",
                "id",
            ]);
 
       $req_data['description']=$request->description ?? null;
       $req_data['page_title']=$request->page_title ?? null;
       $req_data['page_brief']=$request->page_brief ?? null;
       $req_data['page_description']=$request->page_description ?? null;
       $req_data['page_url_label']=$request->page_url_label ?? null;
       $req_data['page_url']=$request->page_url ?? null;
       $req_data['page_detail_url']=$request->page_detail_url ?? null;
       $req_data['meta_title']=$request->meta_title ?? null;
       $req_data['meta_keyword']=$request->meta_keyword ?? null;
       $req_data['meta_description']=$request->meta_description ?? null;
       $req_data['other_meta_tag']=$request->other_meta_tag ?? null;
       $req_data['image']=$request->image ?? null;
       $req_data['status']=(!empty($request->status))?$request->status:0;
       $req_data['agent_discount']=(!empty($request->agent_discount))?$request->agent_discount:0;
       $req_data['admin_email']=(!empty($request->admin_email))?$request->admin_email:null;
       $req_data['admin_phone']=(!empty($request->admin_phone))?$request->admin_phone:null;
       //$req_data['online_booking']=(!empty($request->online_booking))?$request->online_booking:0;


       if(!empty($id))
       {
           $req_data['page_url'] = $request->page_url;
           $req_data['page_detail_url'] = $request->page_detail_url;
           $req_data['updated_at']= date('Y-m-d H:i:s');
           $isSaved = SeoMetaTag::where('id',$id)->update($req_data);
           $seo_meta_tag_id = $id;
           $msg = 'The module has been updated successfully.';
       }else {
            $req_data['created_at']= date('Y-m-d H:i:s');
            $req_data['updated_at']= date('Y-m-d H:i:s');
            $isSaved = SeoMetaTag::create($req_data);
            $seo_meta_tag_id = $isSaved->id;
            $msg = 'The module has been added successfully.';
        }
        if ($isSaved) {

            if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImage($file, $seo_meta_tag_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }

            //=============Activity Logs=====================

            $new_data = DB::table('seo_meta_tags')->where('id',$seo_meta_tag_id)->first();
            $module_desc =  !empty($new_data->identifier)?$new_data->identifier:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $module_id= $seo_meta_tag_id;

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Seo Modules';
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

            return redirect(url('admin/module_config'))->with('alert-success',$msg);
        }else {
            return back()->with('alert-danger', 'The module cannot be saved, please try again or contact the administrator.');
        }
    }
    $data['page_heading']= $page_heading??''; 
    $data["id"] = $id;
    $data["identifier"] = $seoMetaTitle->identifier??'';
    
    return view('admin.seo_meta_tags.form', $data);
}

public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $seoTag = "";
        $title = "Module Details";

        if (is_numeric($id) && $id > 0) {
            $seoTag = SeoMetaTag::where("id", $id)->first();
            //prd($other);
            $title = "Module Details (".$seoTag->identifier.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["seoTag"] = $seoTag;
        $data["id"] = $id;
        return view("admin.seo_meta_tags.view", $data);
    }

public function saveImage($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "seo_tags/";
            $thumb_path = "seo_tags/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "SEO_META_TAG_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "SEO_META_TAG_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "SEO_META_TAG_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "SEO_META_TAG_IMG_THUMB_HEIGHT"
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
                    $seoTag = SeoMetaTag::find($id);

                    if (!empty($seoTag)) {
                        $storage = Storage::disk("public");

                        $old_image = $seoTag->image;
                        $seoTag->image = $new_image;

                        $isUpdated = $seoTag->save();

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

    public function delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();

        //prd($id);
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "seo_tags/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $seoTag = SeoMetaTag::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "others";
                $row_id = $id;
                $action_type = "Delete Other";
                $action_description = "Delete (" . $other->title . ")";
                $description = "Delete (" . $other->title . ")";*/

                $new_data = DB::table('seo_meta_tag_id')->where('id',$id)->first();
                $module_desc =  !empty($new_data->identifier)?$new_data->identifier:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                if (!empty($seoTag) && count([$seoTag]) > 0) {
                    if (
                        !empty($seoTag->image)
                    ) {
                        $image = $seoTag->image;
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
                    $is_deleted = $seoTag->delete();
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
            $params['module'] = 'Seo Modules';
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
                url($this->ADMIN_ROUTE_NAME . "/module_config")
            )->with(
                "alert-success",
                "The modules has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/module_config")
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
        $path = "seo_tags/";
        $seoTag = SeoMetaTag::find($id);

        $image = isset($seoTag->image) ? $seoTag->image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $seoTag->image = "";
                }
                $is_updated = $seoTag->save();
            }
        }
        return $is_updated;
    }



/* end of controller */
}