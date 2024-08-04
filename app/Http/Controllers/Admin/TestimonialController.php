<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use App\Models\UsersWallet;
use App\Models\Package;
use App\Models\Destination;
use App\Helpers\CustomHelper;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Storage;
use Validator;
use Carbon\Carbon;


class TestimonialController extends Controller{

    private $limit;
    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 40;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
        
    }

    public function index(Request $request){
        $data = [];
        $limit = $this->limit;
        $id = (isset($request->id))?$request->id:0;
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status :'';
        $featured = isset($request->featured) ? $request->featured : "";

        $testimonial_query = Testimonial::orderBy('id', 'desc');

        $testimonial_query->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        
        if(!empty($name)) {
            $testimonial_query->where("name", "like", "%" . $name . "%");
        }
        
        if(!empty($featured)) {
            $testimonial_query->where('featured',$featured);
        }

        if(strlen($status) > 0) {
            $testimonial_query->where("status", $status);
        }
        $testimonials = $testimonial_query->paginate($limit);

        $data['testimonials'] = $testimonials;

        return view('admin.testimonials.index', $data);
    }

    public function add(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $testimonial = [];
        $title = "Add Testimonial";

        if(is_numeric($id) && $id > 0) {
            $testimonial = Testimonial::find($id);
            $title =
            "Edit Testimonial (" . $testimonial->name . ")";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->toArray());
            $ext = "jpg,jpeg,png,gif";

            $rules['name'] = 'required|max:255';
            $rules['description'] = 'required';
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }
            $rules["image"] = "nullable|image|mimes:" . $ext;

            $this->validate($request, $rules);

            $req_data = [];
            $req_data = $request->except(["_token","back_url","image","old_image","id","destination_id","featured","slug",]);
            $req_data['featured'] = isset($request->featured)?($request->featured):0;
            $date_on = (isset($request->date_on))?$request->date_on:'';
            $date = CustomHelper::DateFormat($date_on, 'Y-m-d H:i:s', 'd/m/y');
            $req_data['date_on'] = (!empty($date))?$date:Carbon::now()->toDateTimeString();
            $req_data['updated_at'] = Carbon::now()->toDateTimeString();
            $req_data['package_id'] = (isset($request->package_id) && !empty($request->package_id)) ? json_encode($request->package_id) : '[]';
            $req_data['destination_id'] = (isset($request->destination_id) && !empty($request->destination_id)) ? json_encode($request->destination_id) : '[]';
            $req_data['title'] =$request->title??'';
            $req_data['position_in_company'] =$request->position_in_company??'';
            $req_data['company_name'] =$request->company_name??'';
            $req_data['client_link'] =$request->client_link??'';
            $req_data['website'] =$request->website??'';
            //$req_data['meta_title'] =$request->meta_title??'';
            //$req_data['meta_description'] =$request->meta_description??'';
            //$req_data['meta_keywords'] =$request->meta_keywords??'';
            $req_data['email'] =$request->email??'';
            $req_data['sort_order'] =$request->sort_order??0;

            // $slug = "";
            // $fullName = $request->title ?? '';
            // if(empty($id) && !empty($fullName)) {
            //     $slug = CustomHelper::GetSlug("testimonials","id",$id,$fullName);
            // }else{

            //     $slug = !empty($request->slug) ? $request->slug : $fullName;
            //     $slug = CustomHelper::GetSlug("testimonials","id",$id,$slug); 
            // }

            // $req_data["slug"] = $slug;

            if(!empty($testimonial) && $testimonial->id == $id) {

                $slug = $request->slug ?? '';
                if(empty($slug)){
                    $slug = $request->title ?? '' ;
                }
                $req_data["slug"] = CustomHelper::GetSlug('testimonials','id',$id,$slug);
                $isSaved = Testimonial::where("id",$testimonial->id)->update($req_data);
                $testimonial_id = $testimonial->id;
                $msg = "Testimonial has been updated successfully.";
            }else {
                $title = $request->title??'';
                if(empty($request->title)){
                    $title = $request->name ?? '';
                }
                $req_data["slug"] = CustomHelper::GetSlug('testimonials', 'id', $id, $title);
                $req_data['created_at'] = Carbon::now()->toDateTimeString();
                $isSaved = Testimonial::create($req_data);
                $testimonial_id = $isSaved->id;
                $msg = "Testimonial has been added successfully.";
            }

            if($isSaved) {
                if($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImage($file, $testimonial_id, "image");
                    if($image_result["success"] == false) {
                        session()->flash("alert-danger","Image could not be added");
                    }
                }
                cache()->forget("testimonials");

                $new_data = DB::table('testimonials')->where('id',$testimonial_id)->first();
                $name =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $testimonial_id;
                $module_desc= $name;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Testimonial';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($testimonial->id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME . "/testimonials"))->with("alert-success", $msg);
            }else {
                return back()->with("alert-danger","The Testimonial could be added, please try again or contact the administrator.");
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["testimonial"] = $testimonial;
        $data['packages'] = Package::where('status',1)->get();
        $data['destinations'] = Destination::where('is_city',0)->where('status',1)->get();
        $data["id"] = $id;

        return view("admin.testimonials.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $testimonial = "";
        $title = "Testimonial Details";

        if (is_numeric($id) && $id > 0){
            $testimonial = Testimonial::where("id", $id)->first();
            //prd($testimonial);
            $title = "Testimonial Details (".$testimonial->name.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["testimonial"] = $testimonial;
        $data["id"] = $id;
        return view("admin.testimonials.view", $data);
    }

    // Add Image Uploading Code Here.......
    public function saveImage($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if($file) {
            $path = "testimonials/";
            $thumb_path = "testimonials/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "TESTIMONIAL_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "TESTIMONIAL_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "TESTIMONIAL_THUMB_HEIGHT"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "TESTIMONIAL_THUMB_WIDTH"
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
                    $testimonial = Testimonial::find($id);

                    if (!empty($testimonial)) {
                        $storage = Storage::disk("public");

                        $old_image = $testimonial->image;
                        $testimonial->image = $new_image;

                        $isUpdated = $testimonial->save();

                        if ($isUpdated) {
                            if (!empty($old_image) && $storage->exists($path . $old_image)) {
                                $storage->delete($path . $old_image);
                            }

                            if (!empty($old_image) &&
                                $storage->exists($thumb_path . $old_image)) {
                                $storage->delete($thumb_path . $old_image);
                        }
                    }
                }
            }
        }

        if(!empty($uploaded_data)) {
            return $uploaded_data;
        }
    }
}

public function ajax_delete_image(Request $request)
{
        //prd($request->toArray());
    $result["success"] = false;

    $image_id = $request->has("image_id") ? $request->image_id : 0;
    $type = $request->has("type") ? $request->type : "image";

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
    $path = "testimonials/";

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {
            $testimonial = Testimonial::find($id);
            $new_data = DB::table('testimonials')->where('id',$id)->first();
            $module_desc =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
               /* $function_name = $this->currentUrl;
                $action_table = "testimonials";
                $row_id = $id;
                $action_type = "Delete Testimonials";
                $action_description = "Delete (" . $testimonial->name . ")";
                $description = "Delete (" . $testimonial->name . ")";*/

                if (!empty($testimonial) && count([$testimonial]) > 0) {
                    if (isset($testimonial->image) && !empty($testimonial->image)) {
                        $image = $testimonial->image;
                        if (
                            !empty($image) &&
                            $storage->exists($path . "thumb/" . $image)
                        ) {
                            $is_deleted = $storage->delete(
                                $path . "thumb/" . $image
                            );
                        }
                        if(!empty($image) &&
                            $storage->exists($path . $image)
                        ) {
                            $is_deleted = $storage->delete($path . $image);
                    }
                }
                $is_deleted = $testimonial->delete();
            }
        }
    }
    if ($is_deleted) {
            /*CustomHelper::recordActionLog(
                $function_name,
                $action_table,
                $row_id,
                $action_type,
                $action_description,
                $description
            ); */

            //=============Activity Logs==============

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Testimonial';
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

            return redirect(url($this->ADMIN_ROUTE_NAME . "/testimonials"))->with("alert-success","The Testimonials has been deleted successfully.");
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/testimonials"))->with("alert-danger","The Testimonials cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function delete_images($id, $type)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "testimonials/";
        $testimonial = Testimonial::find($id);

        $image = isset($testimonial->image) ? $testimonial->image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $testimonial->image = "";
                }
                $is_updated = $testimonial->save();
            }
        }
        return $is_updated;
    }

    public function seoView(Request $request)
    {
        $testimonial = [];
        $data = [];
        $id = isset($request->id) ? $request->id : 0;
        $testimonial = Testimonial::find($id);
        $testimonialTitle = isset($testimonial->name) ? $testimonial->name:'';
        $title = "SEO Meta Details (".$testimonialTitle.")";
        
        $data = [];
        $data["page_heading"] = $title;
        $data["testimonial"] = $testimonial;
        $data["id"] = $id;
        return view("admin.testimonials.seo_view", $data);
    }
    
    
    public function seoMeta(Request $request){
        
        $testimonial = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = isset($request->id) ? $request->id : 0;
        $testimonial = Testimonial::find($id);
        $testimonialSeo = [];
        $testimonialTitle = isset($testimonial->name) ? $testimonial->name:'';
        $name = "SEO Meta (".$testimonialTitle.")";
        
        if ($request->method() == "POST" || $request->method() == "post") {
            
            $testimonialSeo = Testimonial::find($id);
            $req_data = [];

            $req_data['meta_title'] = $request->meta_title??'';
            $req_data['meta_description'] = $request->meta_description??'';
            $req_data['meta_keywords'] = $request->meta_keywords??'';
            $req_data = $request->except(["_token", 'id', "page", "back_url",'created_at','updated_at']);
            if (!empty($testimonialSeo) && $testimonialSeo->count() > 0) {
                $isSaved = Testimonial::where("id",$testimonialSeo->id)->update($req_data);
                $testimonial_seo_id = $testimonialSeo->id;
                $msg = "Testimonial Seo Meta has been updated successfully.";
            } else {
                $isSaved = Testimonial::create($req_data);
                $testimonial_seo_id = $isSaved->id;
                $msg = "Testimonial Seo Meta has been added successfully.";
            }
            
            if ($isSaved) {
                
                //===============Activity Logs==============
                
                $new_data = DB::table('testimonials')->where(['id'=>$id])->first();
                $module_id = !empty($new_data->id)?$new_data->id:'';
                $sub_module_desc = !empty($new_data->name)?$new_data->name:'';
                $submodule_id = $testimonial_seo_id;
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                
                $testimonial_list = Testimonial::where(['id'=>$module_id])->first();
                $module_desc = !empty($testimonial_list->name)?$testimonial_list->name:'';
                
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Testimonial';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Testimonial SEO";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $submodule_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($testimonialSeo->id)) ? "Update" : "Add";
                
                CustomHelper::RecordActivityLog($params);
                
                    //==========Activity Logs=========
                
                cache()->forget("testimonials");    
                return redirect(route($this->ADMIN_ROUTE_NAME.'.testimonials.seo_view',['id'=>$id]))->with('alert-success', $msg);
            } else {
                return back()->with("alert-danger",
                    "The Testimonial Seo Meta could be added, please try again or contact the administrator."
                );
            }
        }
        $data = [];
        $data["testimonial"] = $testimonial;
        $data["id"] = $id;
        $data["page_heading"] = $name;
        $data["testimonialSeo"] = $testimonialSeo;
        return view("admin.testimonials.seo_meta", $data);
    }
    /* end of controller */
}