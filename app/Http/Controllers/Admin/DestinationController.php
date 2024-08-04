<?php
namespace App\Http\Controllers\Admin;

use App\Models\Destination;
use App\Models\DestinationFlag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\DestinationType;
use App\Models\DestinationInfo;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;

use DB;
use Storage;
use Validator;
use Response;

class DestinationController extends Controller
{
    //protected $foo;
    protected $limit;
    protected $select_cols;
    protected $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct()
    {
        $this->limit = 50;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request)
    {
        $data = [];
        $submit = isset($request->submit) ? $request->submit : '';
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        $destination_name = isset($request->destination_name) ? $request->destination_name : "";
        $featured = isset($request->featured) ? $request->featured : "";
        $status = isset($request->status) ? $request->status : 1;
        $limit = $this->limit;
        $data["page_title"] = "Destination";
        $data["title"] = "Destination";

        $query = Destination::with('destinationType')->withCount('Packages')->where('is_city',0)->orderBy("sort_order","asc");
        if (!empty($destination_name)) {
            $query->where("name", "like", "%" . $destination_name . "%");
        }

        if($request->place){
            $place = $request->place;
            $query->whereHas('destinationLocations',function($q1) use($place){
                $q1->where("name", "like", "%" . $place . "%");
            });
        }
        if(empty($submit)) {
            $query->where("parent_id", $parent_id);
        }
        if(strlen($featured) > 0) {
            $query->where("featured", $featured);
        }
        if($request->checkbox){
            if(is_array($request->checkbox)){
                $checkboxs = $request->checkbox;
                   $query->where(function($query) use($checkboxs) {
                       foreach ($checkboxs as $key => $checkbox){
                        $query->orWhereHas('destinationFlags',function($q1) use($checkbox) {
                            $q1->where('flag_id',$checkbox);
                        });
                    }
                });
            }  
        }

        #prd($request->toArray());
        $query->where("status", $status);
        #prd($query->toSql()); 
        $destinations = $query->paginate($limit);
        $data["flags"] = DestinationFlag::where('status',1)->get();
        $data["destinations"] = $destinations;

        return view("admin.destinations.index", $data);
    }

    public function add(Request $request)
    {
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $destination = [];
        $title = "Add Destination";

        if(is_numeric($id) && $id > 0) {
            $destination = Destination::find($id);
            $title = "Edit Destination(" . $destination->name . ")";
        }
        if($request->method() == "POST" || $request->method() == "post"){
            //prd($request->all());

            // prd($request->toArray());
            $ext = "jpg,jpeg,png,gif";
            if (is_numeric($id) && $id > 0) {
                $rules["name"] = 'required|min:2';
            }else{
                $rules["name"] = 'required|min:2|unique:destinations|max:100';
            }
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }

            //$validation_msg['destination_name'] = 'The destination name field is required.';
            //$rules['destination_type'] = 'required';
            //$rules['status'] = 'required';
            $rules["image"] = "nullable|image|mimes:" . $ext;
            $rules["feature_image"] = "nullable|image|mimes:" . $ext;
            $rules["banner_image"] = "nullable|image|mimes:" . $ext;

            $this->validate($request, $rules);

            /*$req_data = [];
            $req_data = $request->except(["_token","back_url","image","feature_image","banner_image","old_image","old_feature_image","old_banner_image","id","featured","related_destinations",
            ]);*/

            $req_data = [];
            $req_data['name'] = $request->name??'';
            $req_data['destination_type'] = (!empty($request->destination_type))?$request->destination_type:0;
            $req_data['brief'] = (!empty($request->brief))?$request->brief:'';
            $req_data['description'] = (!empty($request->description))?$request->description:'';
            $req_data['video_link'] = (!empty($request->video_link))?$request->video_link:'';
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['latitude'] = (!empty($request->latitude))?$request->latitude:'';
            $req_data['longtitude'] = (!empty($request->longtitude))?$request->longtitude:'';
            //$req_data['meta_title'] = (!empty($request->meta_title))?$request->meta_title:'';
            //$req_data['meta_keyword'] = (!empty($request->meta_keyword))?$request->meta_keyword:'';
            //$req_data['meta_description'] = (!empty($request->meta_description))?$request->meta_description:'';
            $req_data["featured"] = isset($request->featured) ? 1 : 0;
            $req_data["status"] = $request->status??0;            
            $req_data["show"] = $request->show??0;                        
            $req_data["is_international"] = $request->is_international??0;            
            if($request->parent_id) {
                $req_data["parent_id"] = $request->parent_id??0;
            }
            $req_data["best_months"] = isset($request->best_months)
            ? json_encode($request->best_months)
            : "[]";
            $req_data['video_link'] = $request->video_link??'';
            //prd($req_data);

            if (!empty($destination->id) && $destination->id == $id) {
                $req_data["slug"] = CustomHelper::GetSlug('destinations', 'id', $id, $request->slug);

                $isSaved = Destination::where("id", $destination->id)->update($req_data);
                $destination_id = $destination->id;
                $msg = "Destination has been updated successfully.";

                }else {
                    $req_data["slug"] = CustomHelper::GetSlug('destinations', 'id', $id, $request->name);
                    if($request->parent_id) {
                        $req_data["parent_id"] = $request->parent_id??0;
                    } else {
                        $req_data["parent_id"] = 0;
                    }
                    $isSaved = Destination::create($req_data);
                    $destination_id = $isSaved->id;
                    $msg = "Destination has been added successfully.";
                }

                if($isSaved) {

                    //if($request->has('destination_flags')) {
                    DB::table('destination_flags')->where('destination_id',$destination_id)->delete();
                    if(!empty($request->destination_flags)) {
                        $destinationFlagsData = [];
                        
                        foreach ($request->destination_flags as $flag_id) {                
                            $destinationFlagsData[] =[
                                'destination_id' => $destination_id,
                                'flag_id' => $flag_id?? '',
                            ];
                        }
                        DB::table('destination_flags')->insert($destinationFlagsData);
                    }
                //}

                if($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImage($file, $destination_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }

                if($request->hasFile("feature_image")) {
                    $file = $request->file("feature_image");
                    $image_result = $this->saveImage($file, $destination_id, "feature_image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Icon could not be added"
                        );
                    }
                }

                if($request->hasFile("banner_image")) {
                    $file = $request->file("banner_image");
                    $image_result = $this->saveImage($file, $destination_id, "banner_image");
                    if($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Icon could not be added"
                        );
                    }
                }

                cache()->forget("destinations");

                $new_data = DB::table('destinations')->where('id',$destination_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $destination_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Destination';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations?parent_id=".$request->parent_id))->with("alert-success", $msg);
            }else {
                return back()->with("alert-danger","The Destination could be added, please try again or contact the administrator.");
            }
        }

        $data = [];
        $data["destinations"] = Destination::where('is_city',0)->where("status", 1)
        ->orderBy("name", "asc")
        ->get();
        $data["destination_types"] = DestinationType::where("status", 1)->get();
        $data["flags"] = DestinationFlag::where('status',1)->get();
        $data["page_heading"] = $title;
        $data["destination"] = $destination;
        $data["id"] = $id;
        $data["destination_id"] = $id;
        $data["parent"] = $parent_id;

        return view("admin.destinations.form", $data);
    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $destination = "";
        $destinationDetails = Destination::find($id);
        $title = "Destination Details (".$destinationDetails->name.")";

        if(is_numeric($id) && $id > 0) {
            $destination = Destination::withCount('Packages')->where("id", $id)->first();
            $destinationTitle = isset($destination->name) ? $destination->name:'';
            $title = "Destination Details (".$destinationTitle.")";
        }
        
        $data = [];
        $data["page_heading"] = $title;
        $data["destination"] = $destinationDetails;
        $data["destination"] = $destination;
        $data["id"] = $id;
        return view("admin.destinations.view", $data);
    }

    public function saveImage($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "destinations/";
            $thumb_path = "destinations/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "DESTINATION_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings("DESTINATION_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "DESTINATION_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "DESTINATION_IMG_THUMB_HEIGHT"
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
                    $destination = Destination::find($id);

                    if (!empty($destination)) {
                        $storage = Storage::disk("public");
                        if ($type == "image") {
                            $old_image = $destination->image;
                            $destination->image = $new_image;
                        }elseif ($type == "feature_image") {
                            $old_feature_image = $destination->feature_image;
                            $destination->feature_image = $new_image;
                        }elseif ($type == "banner_image") {
                            $old_banner_image = $destination->banner_image;
                            $destination->banner_image = $new_image;
                        }

                        $isUpdated = $destination->save();

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
        $type = $request->has("type") ? $request->type : "";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_images($image_id, $type);
            if ($is_img_deleted) {
                $result["success"] = true;
                $result["msg"] =
                '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
            }
        }

        if($result["success"] == false) {
            $result["msg"] =
            '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $id = !empty($request->id)?$request->id:'';
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        //prd($id);
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "destinations/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $destination = Destination::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "destinations";
                $row_id = $id;
                $action_type = "Delete Destination";
                $destinationName = isset($destination->destination_name) ? $destination->destination_name : '';
                $action_description =
                    "Delete (" . $destinationName . ")";
                    $description = "Delete (" . $destinationName . ")";*/
                    $new_data = DB::table('destinations')->where('id',$id)->first();
                    $module_desc =  !empty($new_data->name)?$new_data->name:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $is_delete = Destination::destinationDelete($id);
                    if ($is_delete['status'] != 'ok') {

                        return redirect(url('admin/destinations'))->with('alert-danger', $is_delete['message']);

                    } 
                    else {

                        $delete_destination_name = $is_delete['name'];

                        $is_deleted = true;

                    // if(!$is_deleted){
                    // return redirect(
                    // url($this->ADMIN_ROUTE_NAME . "/destinations")
                    // )->with(
                    // "alert-danger",
                    // "You cannot delete ".$delete_destination_name
                    // );
                    // }
                    }

                    if (!empty($destination) && count(array($destination)) > 0) {
                        if (
                            !empty($destination->image)
                        ) {
                            $image = $destination->image;
                            $feature_image = $destination->feature_image;
                            $banner_image = $destination->banner_image;
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
                                !empty($feature_image) &&
                                $storage->exists($path . "thumb/" . $feature_image)
                            ) {
                                $is_deleted = $storage->delete(
                                    $path . "thumb/" . $feature_image
                                );
                            }
                            if (!empty($feature_image) && $storage->exists($path . $feature_image)) {
                                $is_deleted = $storage->delete($path . $feature_image);
                            }

                            if (
                                !empty($banner_image) &&
                                $storage->exists($path . "thumb/" . $banner_image)
                            ) {
                                $is_deleted = $storage->delete(
                                    $path . "thumb/" . $banner_image
                                );
                            }
                            if (!empty($banner_image) && $storage->exists($path . $banner_image)) {
                                $is_deleted = $storage->delete($path . $banner_image);
                            }
                        }
                    //$is_deleted = $destination->delete();
                    }
                }
            }
            if ($is_deleted) {

                /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Destination';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data??'';
                $params['action'] = 'Delete';

                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations"))->with("alert-success",
                    "The Destination has been deleted successfully.");
            }else {
                return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations"))->with("alert-danger","The Page cannot be deleted, please try again or contact the administrator.");
            }
        }

        public function delete_images($id, $type)
        {
            $is_deleted = "";
            $is_updated = "";
            $storage = Storage::disk("public");
            $path = "destinations/";
            $destination = Destination::find($id);

            $image = isset($destination->image)? $destination->image: "";
            $feature_image = (isset($destination->feature_image))?$destination->feature_image:'';
            $banner_image = (isset($destination->banner_image))?$destination->banner_image:'';

            if($type =='image'){
                if(!empty($image) && $storage->exists($path.'thumb/'.$image))
                {
                    $is_deleted = $storage->delete($path.'thumb/'.$image);
                }
                if(!empty($image) && $storage->exists($path.$image))
                {
                    $is_deleted = $storage->delete($path.$image);
                }
            }

            else if($type =='feature_image'){
                if(!empty($feature_image) && $storage->exists($path.'thumb/'.$feature_image))
                {
                    $is_deleted = $storage->delete($path.'thumb/'.$feature_image);
                }
                if(!empty($feature_image) && $storage->exists($path.$feature_image))
                {
                    $is_deleted = $storage->delete($path.$feature_image);
                }
            }

            else if($type =='banner_image'){
                if(!empty($banner_image) && $storage->exists($path.'thumb/'.$banner_image))
                {
                    $is_deleted = $storage->delete($path.'thumb/'.$banner_image);
                }
                if(!empty($banner_image) && $storage->exists($path.$banner_image))
                {
                    $is_deleted = $storage->delete($path.$banner_image);
                }
            }

            if($is_deleted){
                if($type =='image'){
                    $destination->image = '';
                }
                else if($type =='feature_image'){
                    $destination->feature_image = '';
                }else if($type =='banner_image'){
                    $destination->banner_image = '';
                }
                $is_updated = $destination->save();

            }
            return $is_updated;
        }

        // Destination Type
        public function types(Request $request)
        {
            $data = [];
            $limit = 50;
            $name = isset($request->name) ? $request->name : "";
            $status = isset($request->status) ? $request->status : 1;
            $destination_type = DestinationType::orderBy("name", "asc");
            if (!empty($name)) {
                $destination_type->where("name", "like", "%" . $name . "%");
            }
           
            $destination_type->where("status", $status);

            $destination_types = $destination_type->paginate($limit);
            $data["destination_types"] = $destination_types;
            $data["limit"] = $limit;

            return view("admin.destination_types.index", $data);
        }

        public function type_add(Request $request)
        {
            $id = isset($request->id) ? $request->id : 0;
            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $destination_type = [];
            $name = "Add Destination Type";
            if (is_numeric($id) && $id > 0) {
                $destination_type = DestinationType::find($id);
                // prd($destination_type);
                $name = "Edit Destination Type(" . $destination_type->name . " )";
            }
            if($request->method() == "POST" || $request->method() == "post") {
                $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
                $rules["status"] = "required";

                $this->validate($request, $rules);

                $destination_type = DestinationType::find($id);
                $req_data = [];
                $req_data = $request->except(["_token","page","back_url","featured",
                ]);

                if(!empty($destination_type) && $destination_type->id == $id) {
                    $isSaved = DestinationType::where("id",$destination_type->id)->update($req_data);
                    $destination_type_id = $destination_type->id;
                    $msg = "Destination Type has been updated successfully.";
                } else {
                    $req_data["slug"] = CustomHelper::GetSlug('destination_type', 'id', '', $request->name);
                    $isSaved = DestinationType::create($req_data);
                    $destination_type_id = $isSaved->id;
                    $msg = "Destination Type has been added successfully.";
                }

                if ($isSaved) {
                    cache()->forget("destination_types");

                    $new_data = DB::table('destination_type')->where('id',$destination_type_id)->first();
                    $module_desc =  !empty($new_data->name)?$new_data->name:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);

                    $module_id = $destination_type_id;

                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Destination Type';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = "";
                    $params['sub_module_id'] = 0;
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = (!empty($id)) ? "Update" : "Add";

                    CustomHelper::RecordActivityLog($params);

                    return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations/types"))->with("alert-success", $msg);
                }else {
                    return back()->with("alert-danger","The destination type could be added, please try again or contact the administrator.");
                }
            }
            $data = [];
            $data["page_heading"] = $name;
            $data["destination_type"] = $destination_type;
            $data["id"] = $id;
            return view("admin.destination_types.form", $data);
    }

    public function destination_delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $destination_type = DestinationType::find($id);
                    $new_data = DB::table('destination_type')->where('id',$id)->first();
                    $module_desc =  !empty($new_data->name)?$new_data->name:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $is_deleted = $destination_type->delete();
                }
            }

            if($is_deleted) {
                
               $params = [];
               $params['user_id'] = $user_id;
               $params['user_name'] = $user_name;
               $params['module'] = 'Destination Type';
               $params['module_desc'] = $module_desc;
               $params['module_id'] = $id;
               $params['sub_module'] = "";
               $params['sub_module_id'] = 0;
               $params['sub_sub_module'] = "";
               $params['sub_sub_module_id'] = 0;
               $params['data_after_action'] = $new_data??'';
               $params['action'] = 'Delete';

               CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations/types"))->with("alert-success","Destination Type has been deleted successfully.");
            }else {
                return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations/types"))->with("alert-danger","Destination Type cannot be deleted, please try again or contact the administrator.");
            }
        }

        /*public function view(Request $request)
        {
            $id = isset($request->id) ? $request->id : 0;
            $destination_type = "";
            $title = "Destination Type";

            if (is_numeric($id) && $id > 0) {
                $destination_type = DestinationType::where("id", $id)->first();
                //prd($destination_type);
                $title = "Destination Type";
            }

            //$destination_type = DestinationType::where('status',1)->orderBy('name')->get();
            //prd($destination_type->toArray());

            $data = [];
            $data["page_heading"] = $title;
            $data["destination_type"] = $destination_type;
            $data["id"] = $id;
            return view("admin.destination_types.view", $data);
        }*/

        public function changeUserStatus(Request $request){
            $destination_type = DestinationType::find($request->id);
            $destination_type->status = $request->status;
            $destination_type->save();

            return response()->json(["success" => "Destination Type status change successfully.",]);
        }

    // Destination Additional Info
    public function additional_info(Request $request)
    {
        $destination = [];
        $destination_id = isset($request->id) ? $request->id : 0;
        $destination = Destination::find($destination_id);
        //$destinationDetails = Destination::find($destination_id);
        if(isset($destination_id) && !empty($destination_id) && is_numeric($destination_id)) {
            $data = [];
            $limit = $this->limit;
            $name = isset($request->name) ? $request->name : "";
            $status = isset($request->status) ? $request->status : "";
            $destination_info = DestinationInfo::where("destination_id",
            $destination_id)->orderBy("title", "ASC");

            if (!empty($name)) {
                $destination_info->where("title", "like", "%" . $name . "%");
            }
            if (strlen($status) > 0) {
                $destination_info->where("status", $status);
            }

            $destination_infos = $destination_info->paginate($limit);
            $data["destination_id"] = $destination_id;
            $data["destination"] = $destination;
            $data["destination_infos"] = $destination_infos;
            $data["heading"] = 'Additional Info ('.$destination->name.')';
            $data["limit"] = $limit;

            return view("admin.destinations.info_index", $data);
        }
        abort(404);
    }

    public function additional_info_add(Request $request)
    {
        $destination = [];
        $destination_id = isset($request->id) ? $request->id : 0;
        $destinationDetails = Destination::find($destination_id);
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        //$destination = Destination::find($destination_id);
        $id = isset($request->info_id) ? $request->info_id : 0;
        $destination_info = [];
        $name = "Add Additional Info (".$destinationDetails->name.")";

        if(is_numeric($id) && $id > 0){
            $destination_info = DestinationInfo::find($id);
            $name = "Edit Additional Info(" . $destination_info->title . " )";
        }
        if($request->method() == "POST" || $request->method() == "post"){
            $ext = "jpg,jpeg,png,gif";

            $rules["title"] = "required|max:255";
            $rules["description"] = "required";
            $rules["image"] = "nullable|image|mimes:" . $ext;
            $rules["status"] = "required";

            $this->validate($request, $rules);

            $destination_info = DestinationInfo::find($id);
            $req_data = [];
            $req_data = $request->except(["_token","image","old_image", "page", "back_url"]);
            $req_data["destination_id"] = $destination_id;
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['type'] = $request->type??'';
            $req_data['type'] = (!empty($request->type))?$request->type:0;
            $req_data['brief'] = $request->brief??'';
            $req_data['featured'] = $request->featured?1:0;

            if(!empty($destination_info) && $destination_info->count() > 0) {
                $isSaved = DestinationInfo::where("id", $destination_info->id)->update($req_data);
                $destination_info_id = $destination_info->id;
                $msg = "Destination Additional Info has been updated successfully.";
            }else {
                $isSaved = DestinationInfo::create($req_data);
                $destination_info_id = $isSaved->id;
                $msg = "Destination Additional Info has been added successfully.";
            }

            if($isSaved){
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImages($file, $destination_info_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash("alert-danger","Image could not be added");
                    }
                }

                $new_data = DB::table('destination_info')->where(['id'=>$destination_info_id])->first();
                $module_id = !empty($new_data->destination_id)?$new_data->destination_id:'';
                $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
                $submodule_id = $destination_info_id;
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $package_list = Destination::where(['id'=>$module_id])->first();
                $module_desc = !empty($package_list->name)?$package_list->name:'';

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Destination';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Additional Info";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $submodule_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($destination_info->id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                cache()->forget("destination_info");

                return redirect(route($this->ADMIN_ROUTE_NAME .".destinations.additional_info",$destination_id))->with("alert-success", $msg);
            }else {
                return back()->with("alert-danger","The destination additional info could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["types"] = DestinationType::where("status", 1)->get();
        $data["page_heading"] = $name;
        //$data["destination"] = $destination;
        $data["destination_info"] = $destination_info;
        $data["destinations"] = $destinationDetails;
        $data["destination_id"] = $destination_id;
        $data["id"] = $id;
        return view("admin.destinations.info_form", $data);
    }

    public function saveImages($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "destinations/";
            $thumb_path = "destinations/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "DESTINATION_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "DESTINATION_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "DESTINATION_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "DESTINATION_IMG_THUMB_HEIGHT"
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
                    $destination = DestinationInfo::find($id);

                    if (!empty($destination)) {
                        $storage = Storage::disk("public");

                        $old_image = $destination->image;
                        $destination->image = $new_image;

                        $isUpdated = $destination->save();

                        if($isUpdated) {
                            if(!empty($old_image) && $storage->exists($path . $old_image)){
                                $storage->delete($path . $old_image);
                            }

                            if(!empty($old_image) && $storage->exists($thumb_path . $old_image)){
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

    public function ajax_delete_images(Request $request)
    {
        //prd($request->toArray());
        $result["success"] = false;

        $image_id = $request->has("image_id") ? $request->image_id : 0;
        $type = $request->has("type") ? $request->type : "";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->add_info_delete_images($image_id, $type);
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

    public function additional_info_delete(Request $request)
    {
        $destination_id = !empty($request->id)?$request->id:'';
        $id = !empty($request->info_id)?$request->info_id:'';
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $storage = Storage::disk("public");
        $path = "destinations/";
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $destination_info = DestinationInfo::find($id);

                $new_data = DB::table('destination_info')->where(['id'=>$id])->first();
                $module_id = !empty($new_data->destination_id)?$new_data->destination_id:'';
                $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $package_list = DestinationInfo::where(['id'=>$module_id])->first();
                $module_desc = !empty($package_list->name)?$package_list->name:'';
                // $is_deleted = $destination_info->delete();
                if (!empty($destination_info) && count([$destination_info]) > 0) {
                    if (
                        !empty($destination_info->image)
                    ) {
                        $image = $destination_info->image;
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
                    $is_deleted = $destination_info->delete();
                }
            }
        }

        if ($is_deleted) {

            //===================Activity Logs===============

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Destination';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id??0;
            $params['sub_module'] = "Additional Info";
            $params['sub_module_desc'] = $sub_module_desc??'';
            $params['sub_module_id'] = $id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

            //===================Activity Logs===============

            return redirect(route($this->ADMIN_ROUTE_NAME . ".destinations.additional_info",$destination_id))->with("alert-success",
            "Destination additional info has been deleted successfully.");
        }else { 
            return redirect(route($this->ADMIN_ROUTE_NAME . ".destinations.additional_info",$destination_id))->with("alert-danger","Destination additional info cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function add_info_delete_images($id, $type)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "destinations/";
        $destination = DestinationInfo::find($id);

        $image = isset($destination->image) ? $destination->image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $destination->image = "";
                }
                $is_updated = $destination->save();
            }
        }
        return $is_updated;
    }

    public function ajax_destinations(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $parent_id = $request->parent_id??0;
            if(!is_array($parent_id)) {
                $parent_id = explode(',', $parent_id);
            }
            $query = Destination::where('is_city',0)->where('status',1);
            $query->whereIn('parent_id',$parent_id);
            $destinations = $query->get();
            $destinations_arr = [];
            if($destinations) {
                foreach($destinations as $destination) {
                    $destinations_arr[] = [
                        'id' => $destination->id,
                        'name' => $destination->name
                    ];
                }
            }
            $response['success'] = true;
            $response['destinations'] = $destinations_arr;
            return response()->json($response);
        }
    }

    public function locations(Request $request) {
        $data = [];
        $destination_id = $request->id??0;
        if($destination_id) {
            $data['destination'] = Destination::find($destination_id);
            // prd($data['destination']->toArray());
            return view("admin.destinations.locations", $data);
        }else {
            abort(404);
        }
    }

    public function locations_add(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $destination_id = $request->destination_id??0;
        $data_id = $request->data_id??0;
        if($destination_id) {
            $nicknames = [];
            $nicknames['name'] = 'Location Name';
            $nicknames['status'] = 'Status';
            $rules = [];
            $rules['name'] = 'required';
            $rules['sort_order'] = 'nullable:numeric';
            $rules['status'] = 'required';
            $rules['show'] = 'required';
            $validation_msg['required'] = ':attribute is required.';
            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
            if($validator->fails()) {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ), 400); // 400 being the HTTP code for an invalid request.
            } else {
                $req_data = [
                    'name' => $request->name??'',
                    'sort_order' => $request->sort_order??'0',
                    'status' => $request->status??'0',
                    'show' => $request->show??'0',
                ];
                $record = Destination::where('id',$destination_id)->where('is_city','1')->where('name',$req_data['name'])->first();
                if(!empty($record)) {
                    $already = true;
                    if(!empty($data_id) && $data_id == $record->id) {
                        $already = false;
                    }
                    if($already) {
                        $response['message'] = "Location already exists!";
                        return response()->json($response);
                    }
                }
                if(!empty($data_id)) {
                    $isSaved = Destination::where('id', $data_id)->update($req_data);
                    $response['message'] = "Location has been updated successfully.";
                    $response['success'] = true;
                } else {
                    $slug = "";
                    $is_exist = Destination::where('parent_id',$destination_id)->where('name',$req_data['name'])->first();
                    if(!isset($is_exist) && empty($is_exist)){
                        if (!empty($req_data['name'])) {
                            $slug = CustomHelper::GetSlug("destinations","id",$destination_id,$req_data['name']);
                        }
                        $req_data['slug'] = $slug;
                        $req_data['parent_id'] = $destination_id;
                        $req_data['is_city'] = 1;

                        $isSaved = Destination::create($req_data);
                        $response['message'] = "Location has been added successfully.";
                        $id = $isSaved->id;
                        $response['success'] = true;
                    }else{
                        $response['success'] = false;
                        $response['err'] = true;
                        $response['errMsg'] = "This location has been added already.";
                    }
                }
                $destination = Destination::find($destination_id);
                $response['location_count'] = $destination->destinationLocations->count()??0;
                $data['destination'] = $destination;
                // prd($data['destination']->toArray());
                $htmlData = view("admin.destinations._locations_table", $data)->render();
                $response['htmlData'] = $htmlData;
                
                return response()->json($response);
            }
        } else {
            abort(404);
        }
    }
    public function locations_get(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $data_id = $request->data_id??0;
        if($data_id) {
            $location = Destination::find($data_id);
            if($location) {
                $response['location'] = $location->toArray();
                $response['success'] = true;
            } else {
                $response['message'] = 'Something went wrong, please try again.';
            }
        } else {
            $response['message'] = 'Something went wrong, please try again.';
        }
        return response()->json($response);
    }

    public function location_delete(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $data_id = $request->data_id??0;
        if($data_id) {
            $location = Destination::find($data_id);
            if($location) {
                // $destination_id = $location->destination_id;
                $destination_id = $location->parent_id;
                $location->delete();

                $destination = Destination::find($destination_id);
                $response['location_count'] = $destination->destinationLocations->count() ?? 0;
                
                $data = [];
                $data['destination'] = $destination;
                $htmlData = view("admin.destinations._locations_table", $data)->render();
                $response['htmlData'] = $htmlData;
                $response['message'] = 'Location has been deleted successfully.';
                $response['success'] = true;
            } else {
                $response['message'] = 'Something went wrong, please try again.';
            }
        } else {
            $response['message'] = 'Something went wrong, please try again.';
        }
        return response()->json($response);
    }

    public function ajax_locations(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $destination_id = $request->destination_id??[];
            if(!is_array($destination_id)) {
                $destination_id = explode(',', $destination_id);
            }
            // $query = Destination::where('is_city',1)->where('status',1);
            $query = Destination::where('status',1);
            if(!empty($destination_id) && count($destination_id) > 0) {
                $query->whereIn('parent_id',$destination_id);
            }
            if($request->term) {
                $query->where('name','like','%'.$request->term.'%');
            }
            $locations = $query->get();
            $locations_arr = [];

            $locations_arr[] = [
                'id' => 'Select',
                'text' => ''
            ];
            if($locations) {
                foreach($locations as $location) {
                    $text = $location->name;
                    $destination_name = $location->parent->name??'';
                    if($destination_name) {
                        $text = $text.' ('.$destination_name.')';
                    }
                    $locations_arr[] = [
                        'id' => $location->id,
                        'text' => $text
                    ];
                }
            }
            $response['success'] = true;
            $response['items'] = $locations_arr;
            return response()->json($response);
        }
    }

    // ============Add SEO Meta=============

    public function seoMeta(Request $request){
        $destination = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = isset($request->destination_id) ? $request->destination_id : 0;
        $destination_id = isset($request->destination_id) ? $request->destination_id : 0;
        $des_type = isset($request->type) ? $request->type : '';
        //$destination = Destination::find($id);
        $destinationDetails = Destination::find($id);
        // $id = isset($request->id) ? $request->id : 0;
        $destinationSeo = [];
        $destinationTitle = isset($destinationDetails->name) ? $destinationDetails->name:'';
        $name = "SEO Meta (".$destinationTitle.")";

        if($request->method() == "POST" || $request->method() == "post"){
            $destinationSeo = Destination::find($id);
            $req_data = [];
            //$req_data = $request->except(["_token", 'id', "page", "back_url",'created_at','updated_at']);

            if(empty($des_type)){
                $req_data['meta_title'] = isset($request->meta_title) ? $request->meta_title : "";
                $req_data['meta_keyword'] = isset($request->meta_keyword) ? $request->meta_keyword : "";
                $req_data['meta_description'] = isset($request->meta_description) ? $request->meta_description : "";
            }

            if(isset($des_type) && $des_type == 'package'){
                $req_data['package_meta_title'] = isset($request->meta_title) ? $request->meta_title : "";
                $req_data['package_meta_keyword'] = isset($request->meta_keyword) ? $request->meta_keyword : "";
                $req_data['package_meta_description'] = isset($request->meta_description) ? $request->meta_description : "";
            }
            
            if(isset($des_type) && $des_type == 'activity'){
                $req_data['activity_meta_title'] = isset($request->meta_title) ? $request->meta_title : "";
                $req_data['activity_meta_keyword'] = isset($request->meta_keyword) ? $request->meta_keyword : "";
                $req_data['activity_meta_description'] = isset($request->meta_description) ? $request->meta_description : "";
            }

            if(isset($des_type) && $des_type == 'hotel') {
                $req_data['hotels_pages_title'] = isset($request->hotels_pages_title) ? $request->hotels_pages_title : "";
                $req_data['hotels_pages_description'] = isset($request->hotels_pages_description) ? $request->hotels_pages_description : "";

                $req_data['hotels_meta_title'] = isset($request->meta_title) ? $request->meta_title : "";
                $req_data['hotels_meta_keyword'] = isset($request->meta_keyword) ? $request->meta_keyword : "";
                $req_data['hotels_meta_description'] = isset($request->meta_description) ? $request->meta_description : "";
            }

            if (!empty($destinationSeo) && $destinationSeo->count() > 0) {
                $isSaved = Destination::where("id",$destinationSeo->id)->update($req_data);
                $destination_seo_id = $destinationSeo->id;
                $msg ="Destination Seo Meta has been updated successfully.";
            } else {
                $isSaved = Destination::create($req_data);
                $destination_seo_id = $isSaved->id;
                $msg =
                "Destination Seo Meta has been added successfully.";
            }

            if ($isSaved) {

                $new_data = DB::table('destinations')->where(['id'=>$id])->first();
                $module_id = !empty($new_data->id)?$new_data->id:'';
                $sub_module_desc = !empty($new_data->name)?$new_data->name:'';
                $submodule_id = $destination_seo_id;
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $destination_list = Destination::where(['id'=>$module_id])->first();
                $module_desc = !empty($destination_list->name)?$destination_list->name:'';

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Destination';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Destination Seo";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $submodule_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($destinationSeo->id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                cache()->forget("destinations");

                // return redirect(route($this->ADMIN_ROUTE_NAME .".destinations.seo_meta",$id))->with("alert-success", $msg);
                // return redirect(route($this->ADMIN_ROUTE_NAME .".destinations.seo_meta",['destination_id'=>$id,'type'=>$des_type]))->with("alert-success", $msg);
                return redirect(route($this->ADMIN_ROUTE_NAME .".destinations.seo_view",['destination_id'=>$id,'type'=>$des_type]))->with("alert-success", $msg);
            }else {
                return back()->with("alert-danger","The Destinations Seo Meta could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["destination"] = $destinationDetails;
        $data["destination_id"] = $destination_id;
        $data["page_heading"] = $name;
        $data["destinationSeo"] = $destinationSeo;
        $data["des_type"] = $des_type;
        //$data["destination"] = $destination;
        $data["id"] = $id;
        return view("admin.destinations.seo_meta", $data);
    }

    public function seoView(Request $request){
        $destination = [];
        $data = [];
        $id = isset($request->destination_id) ? $request->destination_id : 0;
        $destination_id = isset($request->destination_id) ? $request->destination_id : 0;
        $des_type = isset($request->type) ? $request->type : '';
        //$destination = Destination::find($id);
        $destinationDetails = Destination::find($id);
        $destinationSeoView = "";
        $destinationTitle = isset($destinationDetails->name) ? $destinationDetails->name:'';
            $title = "SEO Meta (".$destinationTitle.")";

        if (is_numeric($id) && $id > 0) {
            $destinationSeoView = Destination::where("id", $id)->first();
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["destination"] = $destinationDetails;
        $data["destinationData"] = $destinationSeoView;
        //$data["destination"] = $destination;
        $data["id"] = $id;
        $data["destination_id"] = $destination_id;
        $data["des_type"] = $des_type;
        return view("admin.destinations.seo_view", $data);
    }

    //Flags=========
    
    public function flags_index(Request $request){

        $data = [];
        $limit = 50;
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : 1;
        $destination_query = DestinationFlag::orderBy("id", "desc");
       
        if(!empty($name)) {
            $destination_query->where("name","like","%" . $name . "%");
        }
        $destination_query->where("status", $status);

        $destinations = $destination_query->paginate($limit);
        $data["destinations"] = $destinations;

        return view("admin.destination_flags.index", $data);
    }

    public function flags_add(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $destination_query = [];
        $title = "Add Destination Flag";

        if (is_numeric($id) && $id > 0) {
            $destination_query = DestinationFlag::find($id);
            $name = $destination_query->name??'';
            $title = "Edit Destination Flag(" . $name . " )";
            // prd($title);
        }

        if($request->method() == "POST" || $request->method() == "post") {
           
            $rules = [];
            $validation_msg = [];
            $rules["name"] = 'required|max:255';
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }
            $validation_msg['name.required'] = 'The Destination Flag field is required.';

            $this->validate($request,$rules,$validation_msg);
            $req_data = [];
            $req_data['name'] = $request->name ?? '';
            $req_data['status'] = $request->status ?? '';
            $req_data['page_title']=$request->page_title ?? null;
            $req_data['page_brief']=$request->page_brief ?? null;
            $req_data['page_description']=$request->page_description ?? null;
            if(!empty($destination_query) && $destination_query->id == $id) {
                $req_data["slug"] = CustomHelper::GetSlug('destination_flag', 'id', $id, $request->slug);
                $isSaved = DestinationFlag::where("id", $destination_query->id)->update($req_data);
                $destination_flag_id = $destination_query->id;
                $msg = "Destination Flag has been updated successfully.";
            }else {
                $req_data["slug"] = CustomHelper::GetSlug('destination_flag', 'id', $id, $request->name);
                $isSaved = DestinationFlag::create($req_data);
                $destination_flag_id = $isSaved->id;
                $msg = "Destination Flag has been added successfully.";
            }
            if($destination_flag_id) {

                cache()->forget("destination_flag");

                //=============Activity Logs===========

               $new_data = DB::table('destination_flag')->where('id',$destination_flag_id)->first();
               $module_desc =  !empty($new_data->name)?$new_data->name:'';
               $new_data =(array) $new_data;
               $new_data = json_encode($new_data);
               $module_id = $destination_flag_id;
               $params['user_id'] = $user_id;
               $params['user_name'] = $user_name;
               $params['module'] = 'Destination Flag';
               $params['module_desc'] = $module_desc;
               $params['module_id'] = $module_id;
               $params['sub_module'] = "";
               $params['sub_module_id'] = 0;
               $params['sub_sub_module'] = "";
               $params['sub_sub_module_id'] = 0;
               $params['data_after_action'] = $new_data;
               $params['action'] = (!empty($id)) ? "Update" : "Add";

               CustomHelper::RecordActivityLog($params);

                //=============Activity Logs===========

                return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations/flags"))->with("alert-success", $msg);
            }else {
                return back()->with("alert-danger","The Destination Flag could be added, please try again or contact the administrator.");
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["destination_query"] = $destination_query;
        $data["id"] = $id;

        return view("admin.destination_flags.form", $data);
    }

    public function flags_delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {

                $destination_query = DestinationFlag::find($id);

                $new_data = DB::table('destination_flag')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_deleted = $destination_query->delete();
            }
        }
        if ($is_deleted) {

           $params = [];
           $params['user_id'] = $user_id;
           $params['user_name'] = $user_name;
           $params['module'] = 'Destination Flag';
           $params['module_desc'] = $module_desc;
           $params['module_id'] = $id;
           $params['sub_module'] = "";
           $params['sub_module_id'] = 0;
           $params['sub_sub_module'] = "";
           $params['sub_sub_module_id'] = 0;
           $params['data_after_action'] = $new_data;
           $params['action'] = 'Delete';

           CustomHelper::RecordActivityLog($params);

           return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations/flags"))->with("alert-success", "Destination Type has been deleted successfully.");
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/destinations/flags"))->with("alert-danger","Destination Type cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function changeFlagStatus(Request $request)
    {
        $destination_query = DestinationFlag::find($request->id);
        $destination_query->status = $request->status;
        $destination_query->save();

        return response()->json(["success" => "Destination Type status change successfully.",]);
    }
    // End of controller
}
?>
