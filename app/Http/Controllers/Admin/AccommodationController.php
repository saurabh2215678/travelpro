<?php
namespace App\Http\Controllers\Admin;
use App\Models\Admin;
use App\Models\User;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\Destination;
use App\Models\AccommodationFeature;
use App\Models\AccommodationFacility;
use App\Models\AccommodationCategory;
use App\Models\RoomPlanIncludes;
use App\Models\RoomType;
use App\Models\RoomMaster;
use App\Models\RoomFeature;
use App\Models\AccommodationLocation;
use App\Models\AccommodationInfo;
use App\Models\AccomodationType;
use App\Models\AccommodationPlan;
use App\Models\AccommodationInventory;
use App\Models\DiscountModuleToGroup;
use App\Models\ModuleDiscountCategory;
use App\Models\CustomModuleRecordDiscount;
use App\Models\AgentGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;

use Mail;
use Validator;
use session;
use Illuminate\Validation\Rule;

use Storage;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;
use Response;

class AccommodationController extends Controller
{
    //private $currentUrl;
    protected $ADMIN_ROUTE_NAME;
    protected $currentUrl;
    protected $limit;

    public function __construct()
    {
        $this->limit = 50;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    // Add Accommodation Code Here......

    public function index(Request $request)
    {
        $user = auth()->user();
        $user_id = $user?$user->id:'';
        $is_vendor = $user?$user->is_vendor:'';
        $featured = isset($request->featured) ? $request->featured : "";

        $data = [];
        $limit = $this->limit;
        $name = isset($request->name) ? $request->name : "";
        $data['destination_id'] = $destination_id = (isset($request->destination) && !empty($request->destination)) ? $request->destination : 0;
        $status = isset($request->status) ? $request->status : 1;
        $accommodation = Accommodation::orderBy("sort_order", "asc")->with('accommodationDestination','vendorData');

        $accommodation->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        if (!empty($name)) {
            $accommodation->where("name", "like", "%" . $name . "%");
        }
        if($is_vendor ==1){
            $accommodation->where('vendor_id',$user_id);
        }

        if (!empty($destination_id)) {
            $accommodation->where('destination_id',$destination_id);
        }
        if($request->vendor_id) {
            $accommodation->where('vendor_id',$request->vendor_id);
        }

        if (strlen($featured) > 0) {
            $accommodation->where("featured", $featured);
        }
        $accommodation->where("status", $status);
        $accommodations = $accommodation->paginate($limit);
        $data["accommodations"] = $accommodations;
        // $data["destinations"] = Destination::where('status',1)->get();
        //$data['destinations'] = Destination::where('is_city',0)->where('status',1)->where('parent_id',0)->orderby('sort_order', 'asc')->get();
        $data["vendors"] = Admin::where('is_vendor',1)->where('status',1)->get();
        return view("admin.accommodations.index", $data);
    }

    public function add(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_vendor = auth()->user()->is_vendor;
        $accommodation = [];
        $title = "Add Accommodation";

        $selectDestinations = Destination::select('id','name')->where('status',1)->get();

        if (is_numeric($id) && $id > 0) {
            // $accommodation = Accommodation::find($id);
            $query_accommodation = Accommodation::where('id',$id);
            if($is_vendor == 1){
                $query_accommodation->where('vendor_id',$user_id);
            }
            $accommodation = $query_accommodation->first();
            if($accommodation){

            $title = "Edit Accommodation (" . $accommodation->name . ")";
            }

            if($is_vendor == 1 && empty($accommodation)){
                 return redirect(url($this->ADMIN_ROUTE_NAME . "/accommodations"));
            }
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            //prd($request->all());
            $ext = "jpg,jpeg,png,gif";

            $nicknames = [
                'name' => 'Accommodation Name',
                'destination' => 'Destination',
                'default_location_id' => 'Place',
                'slug' => 'Slug',
                'category_id' => 'Accommodation Category',
                'star_classification' => 'Star Classification',
                'status' => 'Status',
                'rating' => 'Traveller Rating',
                'total_reviews' => 'Total Traveller Reviews',
                'image' => 'Image',
            ];

            $rules = [];
            $rules["name"] = 'required|min:2|regex:/^[\pL\s\-]+$/u|max:100';
            $rules["destination"] = 'required';
            $rules["default_location_id"] = 'required';
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }
            $rules["category_id"] = 'required';
            $rules["star_classification"] = "required";
            $rules["status"] = "required";
            $rules["rating"] = "nullable|numeric|between:0,10.00";
            $rules["total_reviews"] = "nullable|numeric";
            $rules["image"] = "nullable|image|max:2048|mimes:" . $ext;

            $validation_msg = [];
            $validation_msg['required'] = ':attribute is required.';

            $this->validate($request, $rules, $validation_msg, $nicknames);

            /*$req_data = [];
            $req_data = $request->except([
                "_token",
                "back_url",
                "image",
                "old_image",
                "destination",
                "id",
                "destination_id",
                "featured",
            ]);*/

            // $slug = "";
            // if (!empty($request->name)) {
            //     $slug = CustomHelper::GetSlug(
            //         "accommodations",
            //         "id",
            //         $id,
            //         $request->name
            //     );
            // }
            $req_data = [];
            // $slug = CustomHelper::GetSlug('accommodations', 'id', $id, $request->name);
            // $req_data["slug"] = $slug;

            if($is_vendor == 1){
                $req_data['vendor_id'] = $user_id;
            }else{

                $req_data['vendor_id'] = (isset($request->vendor_id) && !empty($request->vendor_id)) ? $request->vendor_id : 0;
            }


            $req_data['destination_id'] = (isset($request->destination) && !empty($request->destination)) ? $request->destination : 0;
            $req_data['total_room'] = (!empty($request->total_room))?$request->total_room:0;
            $req_data['category_id'] = (!empty($request->category_id))?$request->category_id:0;
            $req_data['star_classification'] = (!empty($request->star_classification))?$request->star_classification:'';
            $req_data['rating'] = (!empty($request->rating))?$request->rating:0;
            $req_data['total_reviews'] = (!empty($request->total_reviews))?$request->total_reviews:0;
            $req_data['checkin_time'] = (!empty($request->checkin_time))?$request->checkin_time:'';
            $req_data['checkout_time'] = (!empty($request->checkout_time))?$request->checkout_time:'';
            $req_data['brief'] = (!empty($request->brief))?$request->brief:'';
            $req_data['description'] = (!empty($request->description))?$request->description:'';
            $req_data['policies'] = (!empty($request->policies))?$request->policies:'';
            $req_data['address'] = (!empty($request->address))?$request->address:'';
            $req_data['contact_phone'] = (!empty($request->contact_phone))?$request->contact_phone:'';
            $req_data['contact_email'] = (!empty($request->contact_email))?$request->contact_email:'';
            $req_data['map'] = (!empty($request->map))?$request->map:'';
            $req_data['latitude'] = (!empty($request->latitude))?$request->latitude:'';
            $req_data['longtitude'] = (!empty($request->longtitude))?$request->longtitude:'';
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            //$req_data['meta_title'] =$request->meta_title??'';
            //$req_data['meta_description'] =$request->meta_description??'';
            //$req_data['meta_keywords'] =$request->meta_keywords??'';
            $req_data['name'] =$request->name??'';
            $req_data["status"] = isset($request->status) ? $request->status: 0;
            $req_data["featured"] = isset($request->featured) ? $request->featured : 0;
            $req_data["accommodation_feature"] = isset($request->accommodation_feature)?json_encode($request->accommodation_feature):'';
            $req_data["accommodation_facility"] = isset($request->accommodation_facility)?json_encode($request->accommodation_facility):'';
            $req_data["related_hotels"] = isset($request->related_hotels) && !empty($request->related_hotels)?json_encode($request->related_hotels):'';
            $req_data["policies_chk"] = $request->policies_chk ?? 0;
            $req_data['contact_person'] = $request->contact_person??'';
               
            if (!empty($accommodation) && $accommodation->id == $id) {
                if($request->slug){
                    $req_data["slug"] = CustomHelper::GetSlug('accommodations', 'id', $id, $request->slug);
                }
                $isSaved = Accommodation::where(
                    "id",
                    $accommodation->id
                )->update($req_data);
                
                $msg = "Accommodation has been updated successfully.";

                // $description = json_encode($req_data);

                // $function_name = $this->currentUrl;
                // $action_table = "accommodations";
                // $row_id = $id;
                // $action_type = "Edit On Accommodation";
                // $action_description = "Edit On (" . $request->name . ")";
                // $description = "Update(" . $request->name . ") " . $description;
            } else {
                $req_data["slug"] = CustomHelper::GetSlug('accommodations', 'id', $id, $request->name);
                $isSaved = Accommodation::create($req_data);
                $id = $isSaved->id;
                $msg = "Accommodation has been added successfully.";
                // $description = json_encode($req_data);

                // $function_name = $this->currentUrl;
                // $action_table = "accommodations";
                // $row_id = $id;
                // $action_type = "Add On Accommodation";
                // $action_description = "Add On (" . $request->name . ")";
                // $description = "Add(" . $request->name . ") " . $description;
            }

             if($request->hasFile('image')) {
                $file = $request->file('image');

                if(!empty($file)){
                    $images_result = $this->saveImage($file,$id,"image");
                    //prd($images_result);
                    if($images_result['success']== false){
                        session()->flash('alert-danger', 'Image could not be added');
                    }
                }
            }


            if ($isSaved) {
                
                //Update publish and base price
                CustomHelper::updateAccommodationPublishPrice($id);

                if($id) {
                   AccommodationLocation::where('accommodation_id',$id)->delete();
                }
                $location_data_arr = [];
                
                if($request->default_location_id) {
                    $location_data_arr[] = [
                            'accommodation_id' => $id,
                            'destination_location_id' => $request->default_location_id ?? '',
                            'is_default' => 1,
                        ];
                    AccommodationLocation::insert($location_data_arr);
                }
                if($request->location_id) {
                    $location_data_arr = [];
                    foreach($request->location_id as $destination_location_id) {
                        $location_data_arr[] = [
                            'accommodation_id' => $id,
                            'destination_location_id' => $destination_location_id,
                        ];
                    }
                    AccommodationLocation::insert($location_data_arr);
                }

                //=============Activity Logs=====================

                $new_data = DB::table('accommodations')->where('id',$id)->first();
                $name =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $id;
                $module_desc= $name;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Accommodation';
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

                cache()->forget("accommodations");

                /*CustomHelper::recordActionLog(
                    $function_name,
                    $action_table,
                    $row_id,
                    $action_type,
                    $action_description,
                    $description
                );*/

                return redirect(
                    url($this->ADMIN_ROUTE_NAME . "/accommodations")
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Accommodations could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["accommodations"] = Accommodation::where("status", 1)->orderBy("name", "asc")->get();
        $data["selectDestinations"] =$selectDestinations;

        // $data["destinations"] = Destination::where("status", 1)->get();
        //$data['destinations'] = Destination::where('is_city',0)->where('status',1)->where('parent_id',0)->orderby('sort_order', 'asc')->get();
        $data["features"] = AccommodationFeature::where("status", 1)->get();
        $data["facilities"] = AccommodationFacility::where("status", 1)->get();
        //prd($data['features']);
        $data["categories"] = AccommodationCategory::where("status", 1)->get();
        //$data["destinations"] = Destination::where("status", 1)->get();
        $data["page_heading"] = $title;
        $data["accommodation"] = $accommodation;
        $data["id"] = $id;
        $data["accommodation_id"] = $id;
        $data["vendors"] = Admin::where('is_vendor',1)->get();
        return view("admin.accommodations.form", $data);
    }



    public function update_vendor(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_vendor = auth()->user()->is_vendor;
        $accommodation = [];
        $title = "Add Accommodation";

        $selectDestinations = Destination::select('id','name')->where('status',1)->get();
        $accommodation = Accommodation::find($id);
        
        if ($request->method() == "POST" || $request->method() == "post") {
        
            // $req_data = [];
            // $req_data['vendor_id'] = (isset($request->vendor_id) && !empty($request->vendor_id)) ? $request->vendor_id : 0;
            // $isSaved = Accommodation::where("id",$accommodation->id)->update($req_data);

            $accommodation->vendor_id =(isset($request->vendor_id) && !empty($request->vendor_id)) ? $request->vendor_id : 0;
            $isSaved = $accommodation->save();
            $msg = "Accommodation Vendor has been updated successfully.";
         
            if ($isSaved) {       

                //=============Activity Logs=====================

                $new_data = DB::table('accommodations')->where('id',$id)->first();
                $name =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $id;
                $module_desc= $name;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Accommodation';
                $params['module_desc'] = 'Update Vendor';
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

                cache()->forget("accommodations");

                return redirect(
                    url($this->ADMIN_ROUTE_NAME . "/accommodations")
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Accommodations could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["accommodation"] = $accommodation;

        $data["vendors"] = Admin::where('is_vendor',1)->get();
        return view("admin.accommodations.update_vendor", $data);
    }


    public function ajax_save_booking_mode(Request $request) {
        $data = [];
        $accommodation_id = isset($request->accommodation_id) ? $request->accommodation_id : "";
        $booking_mode = isset($request->booking_mode) ? $request->booking_mode : null;
        if(!empty($accommodation_id)) {
            $accommodation = Accommodation::find($accommodation_id);
            $accommodation->booking_mode = $booking_mode;
            $is_updated = $accommodation->save();

            if($is_updated){

                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;

                $new_data = DB::table('accommodations')->where('id',$accommodation_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $accommodation_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Accommodation';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = "Update";

                CustomHelper::RecordActivityLog($params);

                $response['success'] = true;
            }
            return response()->json($response);
        }
        abort(404);
    }


    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $accommodation = "";
        $accommodationDetails = Accommodation::find($id);
        $title = "Accommodation Details (".$accommodationDetails->name.")";

        if (is_numeric($id) && $id > 0) {
            $accommodation = Accommodation::where("id", $id)->first();
            //prd($accommodation);
            $accommodationTitle = isset($accommodation->name) ? $accommodation->name:'';
            $title = "Accommodation Details (".$accommodationTitle.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["accommodation"] = $accommodationDetails;
        $data["accommodation"] = $accommodation;
        $data["id"] = $id;
        return view("admin.accommodations.view", $data);
    }

    public function saveImage($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "accommodations/";
            $thumb_path = "accommodations/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_IMG_THUMB_HEIGHT"
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
                    $accommodation = Accommodation::find($id);

                    if (!empty($accommodation)) {
                        $storage = Storage::disk("public");

                        $old_image = $accommodation->image;
                        $accommodation->image = $new_image;

                        $isUpdated = $accommodation->save();

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
        $id = !empty($request->id)?$request->id:'';
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $accommodation = Accommodation::find($id);

                $new_data = DB::table('accommodations')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                // $function_name = $this->currentUrl;
                // $action_table = "accommodations";
                // $row_id = $id;
                // $action_type = "Delete Accommodation";
                // $action_description = "Delete (" . $accommodation->name . ")";
                // $description = "Delete (" . $accommodation->name . ")";
                // $is_deleted = $accommodation->delete();
                $accommodation->status = 0;
                $accommodation->is_deleted = 1;
                $is_deleted = $accommodation->save();
            }
        }
        if ($is_deleted) {
            // CustomHelper::recordActionLog(
            //     $function_name,
            //     $action_table,
            //     $row_id,
            //     $action_type,
            //     $action_description,
            //     $description
            // );

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Accommodation';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations")
            )->with(
                "alert-success",
                "Accommodation  has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/accommodations")
            )->with(
                "alert-danger",
                "Accommodation  cannot be deleted, please try again or contact the administrator."
            );
        }
}

    public function delete_images($id, $type)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "accommodations/";
        $accommodation = Accommodation::find($id);

        $image = isset($accommodation->image) ? $accommodation->image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $accommodation->image = "";
                }
                $is_updated = $accommodation->save();
            }
        }
        return $is_updated;
    }

    public function ajax_get_accommodation_types(Request $request)
    {
        $result["success"] = false;
        $result['accommodation_room'] = "";
        $accommodation_id = $request->has("accommodation_id") ? $request->accommodation_id : 0;

        if (is_numeric($accommodation_id) && $accommodation_id > 0) {
            $result["success"] = true;
            $accommodation_room = AccommodationRoom::where("accommodation_id",$accommodation_id)->orderBy("room_type_id", "ASC")->get();
            $room_val = [];
            if(!empty($accommodation_room)){
                foreach ($accommodation_room as $room) {
                    $room_val[] = array(
                        'id' => $room->id,
                        'accommodation_type' => $room->roomAccommodationType->title
                    );
                }
            }
            $result['accommodation_room'] = $room_val;
        }
        return response()->json($result);
    }

    // Add Accommodation Code Closed......

    // Accommodation Additional Info
public function additional_info(Request $request)
{
    $accommodation = [];
    $accommodation_id = isset($request->id) ? $request->id : 0;
    $accommodation = AccommodationInfo::find($accommodation_id);
    $accommodationDetails = Accommodation::find($accommodation_id);
    if (
        isset($accommodation_id) &&
        !empty($accommodation_id) &&
        is_numeric($accommodation_id)
    ) {
        $data = [];
        $limit = $this->limit;
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : "";
        $accommodation_info = AccommodationInfo::where(
            "accommodation_id",
            $accommodation_id
        )->orderBy("title", "ASC");
        if (!empty($name)) {
            $accommodation_info->where("title", "like", "%" . $name . "%");
        }
        if (strlen($status) > 0) {
            $accommodation_info->where("status", $status);
        }

        $accommodation_infos = $accommodation_info->paginate($limit);
        $data["accommodation_id"] = $accommodation_id;
        $data["accommodation"] = $accommodation;
        $data["accommodation_infos"] = $accommodation_infos;
        $data["heading"] = 'Additional Info ('.$accommodationDetails->name.')';
        $data["limit"] = $limit;

        return view("admin.accommodations.info_index", $data);
    }
    abort(404);
}

public function additional_info_add(Request $request)
{
    $accommodation = [];
    $accommodation_id = isset($request->id) ? $request->id : 0;
    $accommodationDetails = Accommodation::find($accommodation_id);
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $accommodation = Accommodation::find($accommodation_id);
    $id = isset($request->info_id) ? $request->info_id : 0;
    $accommodation_info = [];
    $name = "Add Additional Info (".$accommodationDetails->name.")";
    if (is_numeric($id) && $id > 0) {
        $accommodation_info = AccommodationInfo::find($id);
        $name = "Edit Additional Info(" . $accommodation_info->title . " )";
    }
    if ($request->method() == "POST" || $request->method() == "post") {
        // prd($request->toArray());
        $ext = "jpg,jpeg,png,gif";

        $rules["title"] = "required|max:255";
        $rules["description"] = "required";
        $rules["image"] = "nullable|image|mimes:" . $ext;
        $rules["status"] = "required";

        $this->validate($request, $rules);

        $accommodation_info = AccommodationInfo::find($id);
        $req_data = [];
        $req_data = $request->except(["_token","image",
                "old_image", "page", "back_url"]);
        $req_data["accommodation_id"] = $accommodation_id;
        $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
        $req_data['brief'] = $request->brief??'';

        if (!empty($accommodation_info) && $accommodation_info->count() > 0) {
            $isSaved = AccommodationInfo::where("id", $accommodation_info->id)->update($req_data);
            $accommodation_info_id = $accommodation_info->id;
            $msg = "Accommodation Additional Info has been updated successfully.";
        } else {
            $isSaved = AccommodationInfo::create($req_data);
            $accommodation_info_id = $isSaved->id;
            $msg = "Accommodation Additional Info has been added successfully.";
        }

        if ($isSaved) {

            if ($request->hasFile("image")) {
                $file = $request->file("image");
                $image_result = $this->saveImages($file, $accommodation_info_id, "image");
                if ($image_result["success"] == false) {
                    session()->flash(
                        "alert-danger",
                        "Image could not be added"
                    );
            }
        }

            //===================Activity Logs==============================

            $new_data = DB::table('accommodation_info')->where(['id'=>$accommodation_info_id])->first();
            $module_id = !empty($new_data->accommodation_id)?$new_data->accommodation_id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $submodule_id = $accommodation_info_id;
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package_list = Accommodation::where(['id'=>$module_id])->first();
            $module_desc = !empty($package_list->name)?$package_list->name:'';

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Accommodation';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "Additional Info";
            $params['sub_module_desc'] = $sub_module_desc;
            $params['sub_module_id'] = $submodule_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($accommodation_info->id)) ? "Update" : "Add";

            CustomHelper::RecordActivityLog($params);

                //===================Activity Logs==============================

            cache()->forget("additional_info");

            return redirect(
                route($this->ADMIN_ROUTE_NAME .".accommodations.additional_info",$accommodation_id))->with("alert-success", $msg);
        } else {
            return back()->with("alert-danger","The accommodation additional info could be added, please try again or contact the administrator."
            );
        }
    }
    // prd($accommodation_info);
    $data = [];
    $data["page_heading"] = $name;
    $data["accommodation"] = $accommodation;
    $data["accommodation_info"] = $accommodation_info;
    $data["accommodation"] = $accommodationDetails;
    $data["accommodation_id"] = $accommodation_id;
    $data["id"] = $id;
    return view("admin.accommodations.info_form", $data);
}

public function saveImages($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "accommodations/";
            $thumb_path = "accommodations/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_IMG_THUMB_HEIGHT"
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
                    $accommodation = AccommodationInfo::find($id);

                    if (!empty($accommodation)) {
                        $storage = Storage::disk("public");

                        $old_image = $accommodation->image;
                        $accommodation->image = $new_image;

                        $isUpdated = $accommodation->save();

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
    $accommodation_id = !empty($request->id)?$request->id:'';
    $id = !empty($request->info_id)?$request->info_id:'';
    $method = $request->method();
    $storage = Storage::disk("public");
    $path = "accommodations/";
    $thumb_path = "accommodations/thumb/";
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {
            $accommodation_info = AccommodationInfo::find($id);
            
            $new_data = DB::table('accommodation_info')->where(['id'=>$id])->first();
            $module_id = !empty($new_data->accommodation_id)?$new_data->accommodation_id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package_list = AccommodationInfo::where(['id'=>$module_id])->first();
            $module_desc = !empty($package_list->name)?$package_list->name:'';

            if (!empty($accommodation_info) && count([$accommodation_info]) > 0) {
                    if (
                        !empty($accommodation_info->image)
                    ) {
                        $image = $accommodation_info->image;
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
                    $is_deleted = $accommodation_info->delete();
                }
        }
    }

    if ($is_deleted) {

        //===================Activity Logs===============

        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'Accommodation';
        $params['module_desc'] = $module_desc??'';
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


        return redirect(route($this->ADMIN_ROUTE_NAME . ".accommodations.additional_info",$accommodation_id))->with("alert-success",
            "Accommodation additional info has been deleted successfully."
        );
    } else { 
        return redirect(
            route(
                $this->ADMIN_ROUTE_NAME . ".accommodations.additional_info",
                $accommodation_id
            )
        )->with(
            "alert-danger",
            "Accommodation additional info cannot be deleted, please try again or contact the administrator."
        );
    }
}

public function add_info_delete_images($id, $type)
    {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "accommodations/";
        $accommodation = AccommodationInfo::find($id);

        $image = isset($accommodation->image) ? $accommodation->image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $accommodation->image = "";
                }
                $is_updated = $accommodation->save();
            }
        }
        return $is_updated;
    }

    // Add Feature Code Here------

    public function feature(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $feature = AccommodationFeature::orderBy("sort_order", "asc");
        if (!empty($title)) {
            $feature->where("title", "like", "%" . $title . "%");
        }
        $feature->where("status", $status);

        $features = $feature->paginate($limit);

        $data["features"] = $features;

        return view("admin.accommodations_features.index", $data);
    }

    public function add_feature(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $feature = [];
        $title = "Add Accommodation Feature";

        if (is_numeric($id) && $id > 0) {
            $feature = AccommodationFeature::find($id);
            $title = "Edit Accommodation Feature(" . $feature->title . " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["title"] = 'required|max:255';
            $rules["status"] = "required";

            $this->validate($request, $rules);

            $req_data = [];
            $req_data = $request->except([
                "_token",
                "page",
                "back_url",
                "featured",
                "image",
                "old_image",
                "id",
            ]);

            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['is_featured'] = (!empty($request->is_featured))?$request->is_featured:0;

            if (!empty($feature->id) && $feature->id == $id) {
                $isSaved = AccommodationFeature::where("id",$feature->id)->update($req_data);
                $accommodation_features_id = $feature->id;
                $msg = "Accommodation Feature has been updated successfully.";

                $description = json_encode($req_data);
            } else {
                $isSaved = AccommodationFeature::create($req_data);
                $accommodation_features_id = $isSaved->id;
                $msg = "Accommodation Feature has been added successfully.";
                $description = json_encode($req_data);
            }

            if ($isSaved) {

                if($request->hasFile('image')) {
                    $file = $request->file('image');

                    if(!empty($file)){
                        $images_result = $this->savefeatureImage($accommodation_features_id, $file);
                        if($images_result['success']== false){
                            session()->flash('alert-danger', 'Image could not be added');
                        }
                    }
                }

                $new_data = DB::table('accommodation_features')->where('id',$accommodation_features_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $accommodation_features_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Accommodation Feature';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(
                    url($this->ADMIN_ROUTE_NAME . "/accommodations/feature")
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Accommodation Feature could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["feature"] = $feature;
        $data["id"] = $id;

        return view("admin.accommodations_features.form", $data);
    }




public function savefeatureImage($id,$file)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "accommodation_feature/";
            $thumb_path = "accommodation_feature/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_FEATURE_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_FEATURE_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_FEATURE_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "ACCOMMODATION_FEATURE_IMG_THUMB_HEIGHT"
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
                    $inclusion_query = AccommodationFeature::find($id);

                    if (!empty($inclusion_query)) {
                        $storage = Storage::disk("public");

                        $old_image = $inclusion_query->icon;
                        $inclusion_query->icon = $new_image;

                        $isUpdated = $inclusion_query->save();

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
    public function feature_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $feature = "";
        $title = "Accommodation Feature Details";

        if (is_numeric($id) && $id > 0) {
            $feature = AccommodationFeature::where("id", $id)->first();
            //prd($feature);
            $title = "Accommodation Feature Details (".$feature->title.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["feature"] = $feature;
        $data["id"] = $id;
        return view("admin.accommodations_features.view", $data);
    }

    public function feature_delete(Request $request)
    {
        $id = isset($request->id) ? $request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $feature = AccommodationFeature::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "accommodation_features";
                $row_id = $id;
                $action_type = "Delete Accommodation Feature";
                $action_description = "Delete (" . $feature->title . ")";
                $description = "Delete (" . $feature->title . ")";*/

                $new_data = DB::table('accommodation_features')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_deleted = $feature->delete();
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
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Accommodation Feature';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations/feature")
            )->with(
                "alert-success",
                "Accommodation Feature has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/accommodations/feature")
            )->with(
                "alert-danger",
                "Accommodation Feature cannot be deleted, please try again or contact the administrator."
            );
        }
    }
    // Add Feature Code Closed------

    //Add Room plan Inclusion

    public function plan_include(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $feature = RoomPlanIncludes::orderBy("sort_order", "asc");
        if(!empty($title)){
         $feature->where("title", "like", "%" . $title . "%");
        }

        $feature->where("status", $status);
        $features = $feature->paginate($limit);
        $data["features"] = $features;
        return view("admin.room_planincludes.index", $data);
    }
    
    public function add_plan_include(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $feature = [];
        $title = "Add Room Plan Inclusion";

        if (is_numeric($id) && $id > 0) {
            $feature = RoomPlanIncludes::find($id);
            // prd($title);
            $title = "Edit Room Plan_include(" . $feature->name . " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["name"] = 'required|max:255';
            $rules["status"] = "required";

            $this->validate($request, $rules);

            $req_data = [];
            $req_data['name'] = (!empty($request->name))?$request->name:0;
            $req_data['available'] = (!empty($request->available))?$request->available:0;
            $req_data['is_featured'] = (!empty($request->is_featured))?$request->is_featured:0;
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['status'] = (!empty($request->status))?$request->status:0;

            if (!empty($feature->id) && $feature->id == $id) {
                $isSaved = RoomPlanIncludes::where("id",$feature->id)->update($req_data);
                $accommodation_features_id = $feature->id;
                $msg = "RoomPlan Includes has been updated successfully.";
                $description = json_encode($req_data);
                //prd($description);
          
            } else {
                $isSaved = RoomPlanIncludes::create($req_data);
                $accommodation_features_id = $isSaved->id;
                $msg = "RoomPlan Includes has been added successfully.";
                $description = json_encode($req_data);
              
            }

            if ($isSaved) {
                cache()->forget("accommodations_features");

            //=============Activity Logs=====================

                $new_data = DB::table('room_plan_includes')->where('id',$accommodation_features_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $accommodation_features_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Plan Include';
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
                    url($this->ADMIN_ROUTE_NAME . "/accommodations/plan_includes")
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Accommodation Feature could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["feature"] = $feature;
        $data["id"] = $id;
        return view("admin.room_planincludes.form", $data);
    }

    public function plan_include_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $feature = "";
        $title = "Room Plan Include";

        if (is_numeric($id) && $id > 0) {
            $feature = RoomPlanIncludes::where("id", $id)->first();
            //prd($feature);
            $title = "Room Plan Inclusion (".$feature->name.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["feature"] = $feature;
        $data["id"] = $id;
        return view("admin.room_planincludes.view", $data);
    }

    public function plan_include_delete(Request $request)
    {
        $id = isset($request->id) ? $request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $feature = RoomPlanIncludes::find($id);
                $new_data = DB::table('room_plan_includes')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_deleted = $feature->delete();
            }
        }

        if ($is_deleted) {
          
        //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Plan Include';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations/plan_includes")
            )->with(
                "alert-success",
                "Plan Include has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/accommodations/plan_includes")
            )->with(
                "alert-danger",
                "Accommodation Feature cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    //close room plan include

    // Add Accommodation Facility Code Here.....

    public function facility(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $facility_query = AccommodationFacility::orderBy("title", "desc");
        if (!empty($title)) {
            $facility_query->where("title", "like", "%" . $title . "%");
        }
        
        $facility_query->where("status", $status);
        
        $facilities = $facility_query->paginate($limit);

        $data["facilities"] = $facilities;
        //$data['users'] = $users;

        return view("admin.accommodations_facilities.index", $data);
    }

    public function add_facility(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $facility_query = [];
        $title = "Add Accommodation Facility";

        if (is_numeric($id) && $id > 0) {
            $facility_query = AccommodationFacility::find($id);
            $title = "Edit Accommodation Facility(" . $facility_query->title . " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["title"] = 'required|max:255';
            $rules["status"] = "required";

            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "page",
                "back_url",
                "featured",
                "id",
            ]);
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            if (!empty($facility_query->id) && $facility_query->id == $id) {
                $isSaved = AccommodationFacility::where("id",$facility_query->id)->update($req_data);
                $accommodation_facilities_id = $facility_query->id;
                $msg = "Accommodation Facility has been updated successfully.";
                $description = json_encode($req_data);
            } else {
                $isSaved = AccommodationFacility::create($req_data);
                $accommodation_facilities_id = $isSaved->id;
                $msg = "Accommodation Facility has been added successfully.";
                $description = json_encode($req_data);
            }

            if ($isSaved) {
                $new_data = DB::table('accommodation_facilities')->where('id',$accommodation_facilities_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $accommodation_facilities_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Accommodation Facility';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME . "/accommodations/facility"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Accommodation Facility could be added, please try again or contact the administrator.");
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["facility_query"] = $facility_query;
        $data["id"] = $id;

        return view("admin.accommodations_facilities.form", $data);
    }

    public function facility_delete(Request $request)
    {
        $id = isset($request->id)?$request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $facility_query = AccommodationFacility::find($id);
               /* $function_name = $this->currentUrl;
                $action_table = "accommodation_facilities";
                $row_id = $id;
                $action_type = "Delete Accommodation Facility";
                $action_description = "Delete (" . $facility_query->title . ")";
                $description = "Delete (" . $facility_query->title . ")";*/

                $new_data = DB::table('accommodation_facilities')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_deleted = $facility_query->delete();
            }
        }

        if ($is_deleted) {
            /*CustomHelper::recordActionLog($function_name,$action_table, $row_id,$action_type,$action_description,$description
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Accommodation Facility';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations/facility")
            )->with(
                "alert-success",
                "Accommodation Facility has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/accommodations/facility")
            )->with(
                "alert-danger",
                "Accommodation Facility cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function facility_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $facility_query = "";
        $title = "Accommodation Facility Details";

        if (is_numeric($id) && $id > 0) {
            $facility_query = AccommodationFacility::where("id", $id)->first();
            //prd($facility_query);
            $title = "Accommodation Facility Details (".$facility_query->title.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["facility_query"] = $facility_query;
        $data["id"] = $id;
        return view("admin.accommodations_facilities.view", $data);
    }
    // Add Accommodation Facility Code Closed.....

    // Add Accommodation Category Code Here....

    public function category(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $category_query = AccommodationCategory::orderBy("sort_order", "asc");
        if (!empty($title)) {
            $category_query->where("title", "like", "%" . $title . "%");
        }
       
        $category_query->where("status", $status);
       
        $categories = $category_query->paginate($limit);

        $data["categories"] = $categories;
        //$data['users'] = $users;

        return view("admin.accommodations_categories.index", $data);
    }

    public function add_category(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $category_query = [];
        $title = "Add Accommodation Category";

        if (is_numeric($id) && $id > 0) {
            $category_query = AccommodationCategory::find($id);
            $title =
                "Edit Accommodation Category(" . $category_query->title . " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["title"] = 'required|max:255';
            $rules["status"] = "required";

            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "page",
                "back_url",
                "featured",
                "id",
            ]);
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;

            if (!empty($category_query->id) && $category_query->id == $id) {
                $isSaved = AccommodationCategory::where("id",$category_query->id)->update($req_data);
                $accommodation_categories_id = $category_query->id;
                $msg = "Accommodation Category has been updated successfully.";

                $description = json_encode($req_data);
                //prd($description);

                /*$function_name = $this->currentUrl;
                $action_table = "accommodation_categories";
                $row_id = $id;
                $action_type = "Edit On Accommodation Category";
                $action_description = "Edit On (" . $request->title . ")";
                $description =
                    "Update(" . $request->title . ") " . $description;*/
            } else {
                $isSaved = AccommodationCategory::create($req_data);
                $accommodation_categories_id = $isSaved->id;
                $msg = "Accommodation Category has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "accommodation_categories";
                $row_id = $id;
                $action_type = "Add On Accommodation Category";
                $action_description = "Add On (" . $request->title . ")";
                $description = "Add(" . $request->title . ") " . $description;*/
            }

            if ($isSaved) {
                cache()->forget("accommodations_categories");

                /*CustomHelper::recordActionLog($function_name,$action_table, $row_id,$action_type,$action_description,$description
                );*/

                //=============Activity Logs=====================

                $new_data = DB::table('accommodation_categories')->where('id',$accommodation_categories_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $accommodation_categories_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Accommodation Category';
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
                    url($this->ADMIN_ROUTE_NAME . "/accommodations/category")
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Accommodation Category could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["category_query"] = $category_query;
        $data["id"] = $id;

        return view("admin.accommodations_categories.form", $data);
    }

    public function category_delete(Request $request)
    {
        $id = isset($request->id)?$request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
               // if($is_deleted != null){
                $category_query = AccommodationCategory::find($id);

                /*$function_name = $this->currentUrl;
                $action_table = "accommodation_categories";
                $row_id = $id;
                $action_type = "Delete Accommodation Category";
                $accommodationName = isset($category_query->title) ? $category_query->title:'';
                $action_description = "Delete (".$accommodationName.")";
                $description = "Delete (".$accommodationName.")";*/

                $new_data = DB::table('accommodation_categories')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = AccommodationCategory::accommodationCategoryDelete($id);
                if ($is_delete['status'] != 'ok') {

                return redirect(url('admin/accommodations/category'))->with('alert-danger', $is_delete['message']);

                } 
                else {

                $delete_category_name = $is_delete['name'];

                $is_deleted = true;

                    // if($is_deleted != null){
                    // return redirect(
                    // url($this->ADMIN_ROUTE_NAME . "/accommodations/category")
                    // )->with(
                    // "alert-danger",
                    // "You cannot delete ".$delete_category_name
                    // );
                    // }
                }

                
               // $is_deleted = $category_query->delete();
            //}
        }
    }

        if ($is_deleted) {

            /*CustomHelper::recordActionLog($function_name,$action_table, $row_id,$action_type,$action_description,$description
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Accommodation Category';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations/category")
            )->with(
                "alert-success",
                "Accommodation Category has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/accommodations/category")
            )->with(
                "alert-danger",
                "Accommodation Category cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function category_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $category_query = "";
        $title = "Accommodation Category Details";

        if (is_numeric($id) && $id > 0) {
            $category_query = AccommodationCategory::where("id", $id)->first();
            //prd($category_query);
            $title = "Accommodation Category Details (".$category_query->title.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["category_query"] = $category_query;
        $data["id"] = $id;
        return view("admin.accommodations_categories.view", $data);
    }
    // Add Accommodation Category Code Closed....

    // Add Manage Room Types Code Start....

    public function roomTypes(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $roomTyp = RoomType::orderBy("sort_order", "asc");
        if (!empty($title)) {
            $roomTyp->where("title", "like", "%" . $title . "%");
        }
        
        $roomTyp->where("status", $status);
        
        $rooms = $roomTyp->paginate($limit);

        $data["rooms"] = $rooms;

        return view("admin.room_types.index", $data);
    }

    public function add_roomtype(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $roomTyp = [];
        $title = "Add Room Type";

        if (is_numeric($id) && $id > 0) {
            $roomTyp = RoomType::find($id);
            $title = "Edit Room Type(" . $roomTyp->title . " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["title"] = 'required|max:255';
            $rules["status"] = "required";

            $this->validate($request, $rules);

            $req_data = [];
            $req_data = $request->except([
                "_token",
                "page",
                "back_url",
                "featured",
                "id",
            ]);
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;

            if (!empty($roomTyp->id) && $roomTyp->id == $id) {
                $isSaved = RoomType::where("id", $roomTyp->id)->update($req_data);
                $room_type_id = $roomTyp->id;
                $msg = "Room Type has been updated successfully.";

                $description = json_encode($req_data);
                //prd($description);

               /* $function_name = $this->currentUrl;
                $action_table = "room_type";
                $row_id = $id;
                $action_type = "Edit On Room Type";
                $action_description = "Edit On (" . $request->title . ")";
                $description =
                    "Update(" . $request->title . ") " . $description;*/
            } else {
                $isSaved = RoomType::create($req_data);
                $room_type_id = $isSaved->id;
                $msg = "Room Type has been added successfully.";

               /* $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "room_type";
                $row_id = $id;
                $action_type = "Add On Room Type";
                $action_description = "Add On (" . $request->title . ")";
                $description = "Add(" . $request->title . ") " . $description;*/
            }

            if ($isSaved) {
                cache()->forget("room_types");

                /*CustomHelper::recordActionLog($function_name,$action_table, $row_id,$action_type,$action_description,$description
                );*/

                //=============Activity Logs=====================

                $new_data = DB::table('room_type')->where('id',$room_type_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $room_type_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Room Types';
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
                    url($this->ADMIN_ROUTE_NAME . "/accommodations/room_type")
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Room Type could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["roomTyp"] = $roomTyp;
        $data["id"] = $id;

        return view("admin.room_types.form", $data);
    }

    public function room_type_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $roomTyp = "";
        $title = "Room Type Details";

        if (is_numeric($id) && $id > 0) {
            $roomTyp = RoomType::where("id", $id)->first();
            //prd($roomTyp);
            $title = "Room Type Details (".$roomTyp->title.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["roomTyp"] = $roomTyp;
        $data["id"] = $id;
        return view("admin.room_types.view", $data);
    }

    public function roomTyp_delete(Request $request)
    {
        $id = isset($request->id)?$request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $roomTyp = RoomType::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "room_type";
                $row_id = $id;
                $action_type = "Delete Room Type";
                $accommodationRoomTypes = isset($roomTyp->title) ? $roomTyp->title:"";
                $action_description = "Delete (" . $accommodationRoomTypes . ")";
                $description = "Delete (" . $accommodationRoomTypes . ")";*/

                $new_data = DB::table('room_type')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = RoomType::accommodationRoomTypeDelete($id);
                if ($is_delete['status'] != 'ok') {

                return redirect(url('admin/accommodations/room_type'))->with('alert-danger', $is_delete['message']);

                } 
                else {

                $delete_roomType_name = $is_delete['name'];

                $is_deleted = true;

                }
            }
        }

        if ($is_deleted) {

            /*CustomHelper::recordActionLog($function_name,$action_table, $row_id,$action_type,$action_description,$description
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Room Types';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations/room_type")
            )->with(
                "alert-success",
                "Room Type has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/accommodations/room_type")
            )->with(
                "alert-danger",
                "Room Type cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function changeStatusUser(Request $request)
    {

        $roomTyp = RoomType::find($request->id);
        $roomTyp->status = $request->status;
        $roomTyp->save();

        return response()->json([
            "success" => "Room Feature status change successfully.",
        ]);
    }
    // Add Manage Room Types Code Closed....


    // Add Manage Room View Code Start....

    public function roomView(Request $request)
    {
        $data = [];
        $limit = 50;
        $slug = isset($request->slug) ? $request->slug : "room_view";
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : 1;
        $room_view = RoomMaster::orderBy("sort_order", "asc");
        
        if (!empty($slug)) {
            $room_view->where("type",$slug);
        }

        if (!empty($name)) {
            $room_view->where("name", "like", "%" . $name . "%");
        }
        
        $room_view->where("status", $status);
        $rooms = $room_view->paginate($limit);

        $data["slug"] = $slug;
        $data["rooms"] = $rooms;
        $data["page_heading"] = ucwords(str_replace('_',' ',$slug));

        return view("admin.room_views.index", $data);
    }

    public function add_roomview(Request $request)
    {
        $slug = isset($request->slug) ? $request->slug : 0;
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $roomView = [];
        $title = "Add ".ucwords(str_replace('_',' ',$slug));

        if (is_numeric($id) && $id > 0) {
            $roomView = RoomMaster::find($id);
            $title = "Edit Room ".ucwords(str_replace('_',' ',$slug))." ( " . $roomView->name . " )";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["name"] = 'required|max:255';
            $rules["status"] = "required";
            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "page",
                "back_url",
                "featured",
                "id",
            ]);
            $req_data['type'] = (!empty($request->slug))?$request->slug:'';
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;

            if (!empty($roomView->id) && $roomView->id == $id) {
                $isSaved = RoomMaster::where("id", $roomView->id)->update($req_data);
                $room_master_id = $roomView->id;
                $msg = "Room Master has been updated successfully.";
            } else {
                $isSaved = RoomMaster::create($req_data);
                $room_master_id = $isSaved->id;
                $msg = "Room Master has been added successfully.";
            }
            if ($isSaved) {
                cache()->forget("room_master");

                //=============Activity Logs=====================

                $new_data = DB::table('room_master')->where('id',$room_master_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $room_master_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Room Master';
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
                    url($this->ADMIN_ROUTE_NAME . "/accommodations/room_master/".$slug)
                )->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Room Master could be added, please try again or contact the administrator."
                );
            }
        }
        $data = [];
        $data["slug"] = $slug;
        $data["page_heading"] = $title;
        $data["roomView"] = $roomView;
        $data["id"] = $id;

        return view("admin.room_views.form", $data);
    }

    public function room_view(Request $request)
    {
        $slug = isset($request->slug) ? $request->slug : 0;
        $id = isset($request->id) ? $request->id : 0;
        $roomView = "";
        $title = ucwords(str_replace('_',' ',$slug))." Details";

        if (is_numeric($id) && $id > 0) {
            $roomView = RoomMaster::where("id", $id)->first();
            $title = ucwords(str_replace('_',' ',$slug))." Details (".$roomView->name.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["slug"] = $slug;
        $data["roomView"] = $roomView;
        $data["id"] = $id;
        return view("admin.room_views.view", $data);
    }

    public function room_view_delete(Request $request)
    {
        $slug = isset($request->slug)?$request->slug:"";
        $id = isset($request->id)?$request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $roomView = RoomMaster::find($id);

                $new_data = DB::table('room_master')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_delete = RoomMaster::accommodationRoomMasterDelete($id);
                if ($is_delete['status'] != 'ok') {

                return redirect(url('admin/accommodations/room_views'))->with('alert-danger', $is_delete['message']);
                } 
                else {

                $delete_roomView = $is_delete['name'];

                $is_deleted = true;

                }
            }
        }

        if ($is_deleted) {

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Room Master';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations/room_master/".$slug)
            )->with("alert-success","Room Master has been deleted successfully.");
        } else {
            return back()->with(
                "alert-danger","Room Master cannot be deleted, please try again or contact the administrator."
            );
        }
    }
    // Add Manage Room View Code Closed....

    // Add Manage Room Features Code Start....
    public function roomFeature(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $roomFea = RoomFeature::orderBy("sort_order", "asc");
        if (!empty($title)) {
            $roomFea->where("title", "like", "%" . $title . "%");
        }

        $roomFea->where("status", $status);
        
        $roomfeatures = $roomFea->paginate($limit);

        $data["roomfeatures"] = $roomfeatures;

        return view("admin.room_features.index", $data);
    }

    public function add_roomfeature(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $roomFea = [];
        $title = "Add Room Feature";
        if (is_numeric($id) && $id > 0) {
            $roomFea = RoomFeature::find($id);
            $title = "Edit Room Feature(" . $roomFea->title . " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["title"] = 'required|max:255';
            $rules["status"] = "required";
            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "page",
                "back_url",
                "featured",
                "id",
            ]);

            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;

            if (!empty($roomFea->id) && $roomFea->id == $id) {
                $isSaved = RoomFeature::where("id", $roomFea->id)->update($req_data);
                $room_feature_id = $roomFea->id;
                $msg = "Room Feature has been updated successfully.";

                $description = json_encode($req_data);
            } else {
                $isSaved = RoomFeature::create($req_data);
                $room_feature_id = $isSaved->id;
                $msg = "Room Feature has been added successfully.";
            }

            if ($isSaved) {
                $new_data = DB::table('room_feature')->where('id',$room_feature_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $room_feature_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Room Features';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                return redirect(
                    url(
                        $this->ADMIN_ROUTE_NAME . "/accommodations/room_feature"
                    )
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Room Feature could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["roomFea"] = $roomFea;
        $data["id"] = $id;

        return view("admin.room_features.form", $data);
    }

    public function room_feature_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $roomFea = "";
        $title = "Room Feature Details";

        if (is_numeric($id) && $id > 0) {
            $roomFea = RoomFeature::where("id", $id)->first();
            //prd($roomFea);
            $title = "Room Feature Details (".$roomFea->title.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["roomFea"] = $roomFea;
        $data["id"] = $id;
        return view("admin.room_features.view", $data);
    }

    public function roomFea_delete(Request $request)
    {
        $id = isset($request->id)?$request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $roomFea = RoomFeature::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "room_feature";
                $row_id = $id;
                $action_type = "Delete Room Feature";
                $action_description = "Delete (" . $roomFea->title . ")";
                $description = "Delete (" . $roomFea->title . ")";*/

                $new_data = DB::table('room_feature')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_deleted = $roomFea->delete();
            }
        }

        if ($is_deleted) {

            /*CustomHelper::recordActionLog($function_name,$action_table, $row_id,$action_type,$action_description,$description
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Room Features';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations/room_feature")
            )->with(
                "alert-success",
                "Room Feature has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/accommodations/room_feature")
            )->with(
                "alert-danger",
                "Room Feature cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function changeUserStatus(Request $request)
    {

        $roomFea = RoomFeature::find($request->id);
        $roomFea->status = $request->status;
        $roomFea->save();

        return response()->json([
            "success" => "Room Feature status change successfully.",
        ]);
    }
    // Add Manage Room Features Code Closed....

    // Package Accommodation Room
    public function accommodation_room(Request $request)
    {
        $accommodation = [];
        $accommodation_id = $request->id;
        $accommodationDetails = Accommodation::find($accommodation_id);
        if (isset($accommodation_id) && !empty($accommodation_id) && is_numeric($accommodation_id)) {
            $data = [];
            $limit = $this->limit;
            $room_type_id = isset($request->room_type_id) ? $request->room_type_id : "";
            $status = isset($request->status) ? $request->status : "";
            $accommodation_room = AccommodationRoom::with('roomAccommodation')->where("accommodation_id",$accommodation_id)->orderBy("sort_order", "asc");
            if (!empty($room_type_id)) {
                $accommodation_room->where("room_type_id", "like", "%" . $room_type_id . "%");
            }
            if (strlen($status) > 0) {
                $accommodation_room->where("status", $status);
            }

            $accommodation_rooms = $accommodation_room->paginate($limit);


            $data["booking_mode"] = $accommodationDetails->booking_mode??'';
            $data["accommodation_id"] = $accommodation_id;
            $data["accommodation_rooms"] = $accommodation_rooms;
            $data["heading"] = 'Rooms & Pricing ('.$accommodationDetails->name.')';
            $data["limit"] = $limit;

            return view("admin.accommodations.room_index", $data);
        }
        abort(404);
    }

    public function accommodation_room_add(Request $request)
    {
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;        
        $accommodation_id = isset($request->id) ? $request->id : 0;
        $id = isset($request->room_id) ? $request->room_id : 0;
        $accommodationDetails = Accommodation::find($accommodation_id);
        $accommodation_room = [];
        $name = "Add Room (".$accommodationDetails->name.")";

        // $accommodation = Accommodation::find($accommodation_id);
        // $name = "Edit Accommodation Room (" . $accommodation->name . " )";

        if (is_numeric($id) && $id > 0) {
            $accommodation_room = AccommodationRoom::find($id);
            // $name = "Edit Accommodation Room(" . $accommodation_room->roomAccommodationType->title . " )";
            $name = "Edit Room (" . $accommodation_room->room_type_name. " )";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            // prd($request->toArray());
            $rules = [];
            $rules["room_type_name"] = "required";
            $rules["room_type_id"] = "required|max:255";
            // $rules["description"] = "required";
            $rules["status"] = "required";
            $rules["base_occupancy"] = "required";
            $rules["max_occupancy"] = "required";
            // $rules["base_price"] = "required";
            // $rules["publish_price"] = 'required|numeric|regex:/^\d+(\.\d{1,2})?$/';
            // $rules["single_price"] = "required";
            // $rules["price_extra_adult"] = "required";
            // $rules["price_extra_child_1"] = "required";
            // $rules["price_extra_child_2"] = "required";

            // $rules["extra_adult_bed"] = "required";
            // $rules["extra_child_bed"] = "required";
            // $rules["quantity"] = 'required|integer|between:1,60';
            // $rules["max_quantity"] = 'required|integer|between:1,60';
            // $rules["minimum_stay"] = 'required|integer|between:1,60';
            // $rules["no_of_exrta_beds"] = 'required|integer|between:1,60';
            // $rules["max_adults"] = 'required|integer|between:1,60';
            // $rules["max_children"] = 'required|integer|between:1,60';
            // $rules["extra_bed"] = "required";

            $this->validate($request, $rules);

            $accommodation_room = AccommodationRoom::find($id);
            $req_data = [];
            // $req_data = $request->except(["_token", "page", "back_url"]);
            $req_data["accommodation_id"] = $accommodation_id;
            $req_data['room_type_name'] = (!empty($request->room_type_name))?$request->room_type_name:'';
            $req_data["room_type_id"] =(!empty($request->room_type_id))?$request->room_type_id:null; ;
            $req_data["total_room"] =(!empty($request->total_room))?$request->total_room:null; ;
            $req_data["room_view"] =(!empty($request->room_view))?$request->room_view:null; ;
            $req_data["bed_type"] =(!empty($request->bed_type))?$request->bed_type:null; ;
            $req_data["extra_bed_type"] =(!empty($request->extra_bed_type))?$request->extra_bed_type:null; ;
            
            $req_data["sort_order"] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['brief'] = (!empty($request->brief))?$request->brief:'';
           
            $req_data['base_occupancy'] = (!empty($request->base_occupancy))?$request->base_occupancy:'';
            $req_data['max_adult_bed'] = (!empty($request->max_adult_bed))?$request->max_adult_bed:'';
            $req_data['base_child_no'] = (!empty($request->base_child_no))?$request->base_child_no:'';
            $req_data['max_child_no'] = (!empty($request->max_child_no))?$request->max_child_no:'';

            $req_data['max_occupancy'] = (!empty($request->max_occupancy))?$request->max_occupancy:'';

            $req_data['description'] = (!empty($request->description))?$request->description:'';

            /*$req_data['base_price'] = (!empty($request->base_price))?$request->base_price:'';
            $req_data['publish_price'] = (!empty($request->publish_price))?$request->publish_price:'';
            $req_data['single_price'] = (!empty($request->single_price))?$request->single_price:'';
            $req_data['price_extra_adult'] = (!empty($request->price_extra_adult))?$request->price_extra_adult:'';
            $req_data['price_extra_child_1'] = (!empty($request->price_extra_child_1))?$request->price_extra_child_1:'';
            $req_data['price_extra_child_2'] = (!empty($request->price_extra_child_2))?$request->price_extra_child_2:'';*/

            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:'';
            $req_data['video_link'] = (!empty($request->video_link))?$request->video_link:'';
            $req_data['status'] = (!empty($request->status))?$request->status:0;
            $req_data['is_default'] = (!empty($request->is_default))?$request->is_default:0;
            $req_data["room_features"] = isset($request->room_features)? json_encode($request->room_features): "[]";

            // $req_data['extra_adult_bed'] = (!empty($request->extra_adult_bed))?$request->extra_adult_bed:'';
            // $req_data['extra_child_bed'] = (!empty($request->extra_child_bed))?$request->extra_child_bed:'';
            // $req_data['quantity'] = (!empty($request->quantity))?$request->quantity:0;
            // $req_data['max_quantity'] = (!empty($request->max_quantity))?$request->max_quantity:0;
            // $req_data['minimum_stay'] = (!empty($request->minimum_stay))?$request->minimum_stay:0;
            // $req_data['no_of_exrta_beds'] = (!empty($request->no_of_exrta_beds))?$request->no_of_exrta_beds:0;
            // $req_data['max_adults'] = (!empty($request->max_adults))?$request->max_adults:0;
            // $req_data['max_children'] = (!empty($request->max_children))?$request->max_children:0;

            $accRoomCount = AccommodationRoom::where("accommodation_id",$accommodation_id)->where('status',1)->count();
            if($accRoomCount == 0) {
                $req_data["is_default"] = 1;
            }

            if($req_data["is_default"] == 1) {
                AccommodationRoom::where("accommodation_id",$accommodation_id)->update(array('is_default'=>0));
            }
            
            if (!empty($accommodation_room) && $accommodation_room->count() > 0) {
                $isSaved = AccommodationRoom::where("id",$accommodation_room->id)->update($req_data);
                $accommodation_room_id = $accommodation_room->id;
                $msg = "Accommodation Room has been updated successfully.";
            } else {
                $isSaved = AccommodationRoom::create($req_data);
                $accommodation_room_id = $isSaved->id;
                $msg =
                    "Accommodation Room has been added successfully.";
            }

            if ($isSaved) {
                //Update publish and base price
                CustomHelper::updateAccommodationPublishPrice($accommodation_id);

                $new_data = DB::table('accommodation_room')->where('id',$accommodation_room_id)->first();
                $module_desc =  !empty($new_data->room_type_name)?$new_data->room_type_name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $accommodationDetails->id??0;
                $module_desc = $accommodationDetails->name??'';
                $sub_module_id = $accommodation_room_id;
                $sub_module_desc = $req_data['room_type_name']??'';

                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Accommodation';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Accommodation Rooms & Pricing";
                $params['sub_module_id'] = $sub_module_id;
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);
                return redirect(
                    route(
                        $this->ADMIN_ROUTE_NAME .
                            ".accommodations.accommodation_room",
                        $accommodation_id
                    )
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The accommodation room could be added, please try again or contact the administrator."
                );
            }
        }
        $data = [];
        $room_master = RoomMaster::where('status',1)->orderBy('sort_order','asc')->get();
        $data["room_master"] = $room_master;
        $data["page_heading"] = $name;
        $data["accommodation_id"] = $accommodation_id;
        $data["accommodation"] = $accommodationDetails;
        $data["accommodation_room"] = $accommodation_room;
        $data["room_types"] = RoomType::where('status',1)->get();
        $data["features"] = RoomFeature::where('status',1)->get();
        $data["id"] = $id;
        return view("admin.accommodations.room_form", $data);
    }

    public function accommodation_room_view(Request $request)
    {
        $accommodation = [];
        $id = isset($request->id) ? $request->id : 0;
        $accommodation_id = isset($request->id) ? $request->id : 0;
        $accommodationDetails = Accommodation::find($accommodation_id);
        //$accommodation_room = "";
        //$accommodationRoom = isset($accommodationDetails->name) ? $accommodationDetails->name:'';
        //$title = "Accommodation Room (".$accommodationRoom.")";

        if (is_numeric($id) && $id > 0) {
            $accommodation_room = AccommodationRoom::where("id", $id)->first();
            //prd($accommodation_room);
            $room_type_name = isset($accommodation_room->room_type_name) ? $accommodation_room->room_type_name:'';
            $title = "Accommodation Room Details (".$room_type_name.")";
        }

        $data = [];
        $data["page_heading"] = $title??'';
        $data["accommodation_id"] = $accommodation_id;
        $data["accommodation"] = $accommodationDetails;
        $data["accommodation_room"] = $accommodation_room??'';
        $data["id"] = $id;
        return view("admin.accommodations.room_view", $data);
    }
    public function update_hotel_order(Request $request){
        $response['success'] = false;
        $response['message'] = '';
        $isSaved = '';
        $accommodation_id = isset($request->accommodation_id) ? $request->accommodation_id : '';
        $getAllData = $request->data;
        if(isset($accommodation_id) && !empty($accommodation_id)){
            foreach ($getAllData as $key => $value) {
                $getId = $value;
                $req_data['sort_order'] = $key+1;
                $isSaved = AccommodationRoom::where('id',$getId)->where('accommodation_id',$accommodation_id)->update($req_data);
             }
        }
        if($isSaved) {
            $response['success'] = true;
            $response['message'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Accommodation Room Updated Successfully.</div>';
        }
        return response()->json($response);
    }

    public function accommodation_room_delete(Request $request)
    {
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;        
        $accommodation_id = $request->id;
        $id = $request->room_id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $roomAccommodation = [];
            if (is_numeric($id) && $id > 0) {
                $accommodation_room = AccommodationRoom::find($id);
                if($accommodation_room) {
                    $accommodation_room_id = $accommodation_room->id;
                    $accommodation_room_name = $accommodation_room->room_type_name;
                    $roomAccommodation = $accommodation_room->roomAccommodation??[];
                    $accommodation_id = $accommodation_room->accommodation_id;

                    $new_data = DB::table('accommodation_room')->where('id',$id)->first();
                    $module_desc =  !empty($new_data->room_type_name)?$new_data->room_type_name:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);


                    $is_deleted = $accommodation_room->delete();

                    if ($is_deleted) {

                        //Update publish and base price
                        CustomHelper::updateAccommodationPublishPrice($accommodation_id);

                        $module_id = $roomAccommodation->id??0;
                        $module_desc = $roomAccommodation->name??'';
                        $sub_module_id = $accommodation_room_id;
                        $sub_module_desc = $accommodation_room_name;

                        $params = [];
                        $params['user_id'] = $user_id;
                        $params['user_name'] = $user_name;
                        $params['module'] = 'Accommodation';
                        $params['module_desc'] = $module_desc??'';
                        $params['module_id'] = $module_id;
                        $params['sub_module'] = "Accommodation Rooms & Pricing";
                        $params['sub_module_id'] = $sub_module_id;
                        $params['sub_module_desc'] = $sub_module_desc;
                        $params['sub_sub_module'] = "";
                        $params['sub_sub_module_id'] = 0;
                        $params['data_after_action'] = $new_data??'';
                        $params['action'] = 'Delete';

                        CustomHelper::RecordActivityLog($params);

                        cache()->forget("accommodation_room");
                        return redirect(
                            route(
                                $this->ADMIN_ROUTE_NAME . ".accommodations.accommodation_room",
                                $accommodation_id
                            )
                        )->with(
                            "alert-success",
                            "Accommodation Room has been deleted successfully."
                        );
                    } else {
                        return redirect(
                            route(
                                $this->ADMIN_ROUTE_NAME . ".accommodations.accommodation_room",
                                $accommodation_id
                            )
                        )->with(
                            "alert-danger",
                            "Accommodation Room cannot be deleted, please try again or contact the administrator."
                        );
                    }


                }
            }

            

        }

        return redirect(
            route(
                $this->ADMIN_ROUTE_NAME . ".accommodations.accommodation_room",
                $accommodation_id
            )
        )->with(
            "alert-danger",
            "Accommodation Room cannot be deleted, please try again or contact the administrator."
        );        
    }

// ============Add SEO Meta=============

 public function seoMeta(Request $request){
    
    $accommodation = [];
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $id = isset($request->accommodation_id) ? $request->accommodation_id : 0;
    $accommodation_id = isset($request->accommodation_id) ? $request->accommodation_id : 0;
    //$accommodation = Accommodation::find($id);
    $accommodationDetails = Accommodation::find($id);
    // $id = isset($request->id) ? $request->id : 0;
    $accommodationSeo = [];
    $accommodationTitle = isset($accommodationDetails->name) ? $accommodationDetails->name:'';
    $name = "SEO Meta (".$accommodationTitle.")";

    if ($request->method() == "POST" || $request->method() == "post") {

        $accommodationSeo = Accommodation::find($id);
        $req_data = [];
        //$req_data = $request->except(["_token", 'id', "page", "back_url",'created_at','updated_at']);

        $req_data['meta_title'] = isset($request->meta_title) ? $request->meta_title : "";
        $req_data['meta_keywords'] = isset($request->meta_keywords) ? $request->meta_keywords : "";
        $req_data['meta_description'] = isset($request->meta_description) ? $request->meta_description : "";

        if (!empty($accommodationSeo) && $accommodationSeo->count() > 0) {
            $isSaved = Accommodation::where("id",$accommodationSeo->id)->update($req_data);
            $accommodation_seo_id = $accommodationSeo->id;
            $msg ="Accommodation Seo Meta has been updated successfully.";
        } else {
            $isSaved = Accommodation::create($req_data);
            $accommodation_seo_id = $isSaved->id;
            $msg =
            "Accommodation Seo Meta has been added successfully.";
        }

        if ($isSaved) {

                //===================Activity Logs==============================

            $new_data = DB::table('accommodations')->where(['id'=>$id])->first();
            $module_id = !empty($new_data->id)?$new_data->id:'';
            $sub_module_desc = !empty($new_data->name)?$new_data->name:'';
            $submodule_id = $accommodation_seo_id;
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $accommodation_list = Accommodation::where(['id'=>$module_id])->first();
            $module_desc = !empty($accommodation_list->name)?$accommodation_list->name:'';

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Accommodation';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "Accommodation Seo";
            $params['sub_module_desc'] = $sub_module_desc;
            $params['sub_module_id'] = $submodule_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($accommodationSeo->id)) ? "Update" : "Add";

            CustomHelper::RecordActivityLog($params);

                //===================Activity Logs==============================

            cache()->forget("accommodations");

            return redirect(route($this->ADMIN_ROUTE_NAME .".accommodations.seo_meta",$id))->with("alert-success", $msg);
        } else {
            return back()->with(
                "alert-danger",
                "The Accommodation Seo Meta could be added, please try again or contact the administrator."
            );
        }
    }
    $data = [];
    $data["accommodation"] = $accommodationDetails;
    $data["accommodation_id"] = $accommodation_id;
    $data["page_heading"] = $name;
    $data["accommodationSeo"] = $accommodationSeo;
    //$data["accommodation"] = $accommodation;
    $data["id"] = $id;
    return view("admin.accommodations.seo_meta", $data);
}

public function seoView(Request $request)
{
    $accommodation = [];
    $data = [];
     $id = isset($request->accommodation_id) ? $request->accommodation_id : 0;
    $accommodation_id = isset($request->accommodation_id) ? $request->accommodation_id : 0;
    //$accommodation = Accommodation::find($id);
    $accommodationDetails = Accommodation::find($id);
    $accommodationSeoView = "";
    $accommodationTitle = isset($accommodationDetails->name) ? $accommodationDetails->name:'';
    $title = "SEO Meta (".$accommodationTitle.")";

    if (is_numeric($id) && $id > 0) {
        $accommodationSeoView = Accommodation::where("id", $id)->first();
    }

    $data = [];
    $data["page_heading"] = $title;
    $data["accommodation"] = $accommodationDetails;
    $data["accommodationData"] = $accommodationSeoView;
    //$data["accommodation"] = $accommodation;
    $data["id"] = $id;
    $data["accommodation_id"] = $accommodation_id;
    return view("admin.accommodations.seo_view", $data);
}


public function accoomodationType(Request $request)
    {
        $data = [];
        $limit = 50;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $acc_type = AccomodationType::orderBy("sort_order", "asc");
        if (!empty($title)) {
            $acc_type->where("title", "like", "%" . $title . "%");
        }

        $acc_type->where("status", $status);
        
        $roomfeatures = $acc_type->paginate($limit);

        $data["roomfeatures"] = $roomfeatures;

        return view("admin.accomodation_types.index", $data);
    }


    public function add_accoomodationType(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $accommodation_type = [];
        $title = "Add Accommodation Type";

        if (is_numeric($id) && $id > 0) {
            $accommodation_type = AccomodationType::find($id);
            $title = "Edit Accommodation Type (" . $accommodation_type->title . " )";
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["title"] = 'required|max:255';
            $rules["status"] = "required";

            $this->validate($request, $rules);

            $req_data = [];
            $req_data = $request->except([
                "_token",
                "page",
                "back_url",
                "featured",
                "id",
            ]);

            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;

            if (!empty($accommodation_type->id) && $accommodation_type->id == $id) {
                $isSaved = AccomodationType::where("id", $accommodation_type->id)->update($req_data);
                $acc_room_id = $accommodation_type->id;
                $msg = "Accommodation Type has been updated successfully.";
                $description = json_encode($req_data);
            } else {
                $isSaved = AccomodationType::create($req_data);
                $acc_room_id = $isSaved->id;
                $msg = "Accommodation Type has been added successfully.";
            }

            if ($isSaved) {
                cache()->forget("accommodation_type");

                //=============Activity Logs=====================
                $new_data = DB::table('accommodation_type')->where('id',$acc_room_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $acc_room_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Room Features';
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
                    url(
                        $this->ADMIN_ROUTE_NAME . "/accommodations/accoommodation_type"
                    )
                )->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Accommodation Type could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["roomFea"] = $accommodation_type;
        $data["id"] = $id;

        return view("admin.accomodation_types.form", $data);
    }


    public function accoomodation_type_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $accommodation_type = "";
        $title = "Accommodation Type Details";

        if (is_numeric($id) && $id > 0) {
            $accommodation_type = AccomodationType::where("id", $id)->first();
            $title = "Accommodation Type Details (".$accommodation_type->title.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["roomFea"] = $accommodation_type;
        $data["id"] = $id;
        return view("admin.accomodation_types.view", $data);
    }

    public function saveAllInventory(Request $request) {
        $success = false;
        $statusCode = 400;
        $message = '';
        $inventories = isset($request->inventory) ? $request->inventory : "";
        $single_prices = isset($request->single_price) ? $request->single_price : "";
        $price = isset($request->price) ? $request->price : "";
        $extra_adult = isset($request->extra_adult) ? $request->extra_adult : "";
        $extra_child_1 = isset($request->extra_child_1) ? $request->extra_child_1 : "";
        $extra_child_2 = isset($request->extra_child_2) ? $request->extra_child_2 : "";

        // prd($request->toArray());

        $accommodation_id = isset($request->accommodation_id) ? $request->accommodation_id : "";
        $last_date = isset($request->last_date) ? $request->last_date : "";
        $is_saved = isset($request->is_saved) ? $request->is_saved : 0;
        $cab_route_group_id = isset($request->cab_route_group_id) ? $request->cab_route_group_id : "";
        $isSaved =0;

        if( ($request->method() == 'POST' || $request->method() == 'post')){
            $req_data = [];
            foreach ($price as $key => $room_data) {
                $date = $key ;
                $book_date = date("Y-m-d", strtotime($date));

                foreach ($room_data as $room_key => $plan_data) {

                    foreach ($plan_data as $plan_key => $plan_price) {
                        if($plan_key == 0){
                            $plan_key = null;
                            $isDeleted =  AccommodationInventory::where('accommodation_id',$accommodation_id)->where('room_id',$room_key)->where('date',$book_date)->delete();
                        }else{
                            $isDeleted =  AccommodationInventory::where('accommodation_id',$accommodation_id)->where('room_id',$room_key)->whereNull('plan_id')->where('date',$book_date)->delete();
                        }

                        $req_data = array(
                            'accommodation_id' => $accommodation_id,
                            'room_id' => $room_key,
                            'plan_id' => $plan_key,
                            'date' => $book_date,
                            'inventory' => $inventories[$key][$room_key]??0,
                            'price' => $plan_price??0,
                            'single_price' => $single_prices[$key][$room_key][$plan_key]??0,
                            'extra_adult' => $extra_adult[$key][$room_key][$plan_key]??0,
                            'extra_child_1' => $extra_child_1[$key][$room_key][$plan_key]??0,
                            'extra_child_2' => $extra_child_2[$key][$room_key][$plan_key]??0,
                            'status' => 1,
                        );

                        $query = AccommodationInventory::where('accommodation_id',$accommodation_id)->where('room_id',$room_key);
                        if($plan_key == null) {
                            $query->whereNull('plan_id');
                        } else {
                            $query->where('plan_id',$plan_key);
                        }
                        $is_data = $query->where('date',$book_date)->first();

                        if($is_data) {
                            $isSaved = AccommodationInventory::where('id',$is_data->id)->update($req_data);
                            if($isSaved) {
                                $success = true;
                                $statusCode = 200;                                
                            }
                        } else {
                            $isSaved = AccommodationInventory::create($req_data);
                            if($isSaved) {
                                $success = true;
                                $statusCode = 200;                                
                            }
                        } 
                    }
                }
            }
            if($success) {
                //=====

                CustomHelper::updateAccommodationPublishPrice($accommodation_id);

                    $before_days = 15;

                    $date=strtotime(date('Y-m-d')); 
                    $before_date = date('Y-m-d',strtotime('-15 days',$date));
                    $is_deleted = AccommodationInventory::where('accommodation_id',$accommodation_id)->whereDate('date','<',$before_date)->delete();

             
                //=====

                $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Accommodation Inventory Updated Successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Accommodation Inventory not updated, please try again.</div>';
            }
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);
    }

    public function accoomodationType_delete(Request $request) {
        $id = isset($request->id)?$request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $acc_type = AccomodationType::find($id);
              
                $new_data = DB::table('accommodation_type')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_deleted = $acc_type->delete();
            }
        }

        if ($is_deleted) {

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Room Features';
            $params['module_desc'] = $module_desc??'';
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
                url($this->ADMIN_ROUTE_NAME . "/accommodations/accoommodation_type")
            )->with(
                "alert-success",
                "Accommodation Type has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/accommodations/accoommodation_type")
            )->with(
                "alert-danger",
                "Accommodation Type cannot be deleted, please try again or contact the administrator."
            );
        }
    }


    public function changeAccTypeStatus(Request $request)
    {
        $roomFea = AccomodationType::find($request->id);
        $roomFea->status = $request->status;
        $roomFea->save();

        return response()->json([
            "success" => "Accommodation Type status changed successfully.",
        ]);
    }


    //=Inventory

    public function inventory(Request $request) {
        $data = [];
        $accommodation_id = $request->id ?? '';

        $start_date = date('Y-m-d');
        $end_date =  date('Y-m-d', strtotime($start_date. ' + 10 days'));
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $pre_date =  date('Y-m-d', strtotime($start_date. ' - 10 days'));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $data['pre_date'] = $pre_date;
        $data['last_date'] = $end_date;
        $data['start_date'] = $start_date;
        $data['period'] = $period;
        $data['start_date'] = $start_date;

        $data['route_groups'] =[];// CabRouteGroup::where('status',1)->orderBy('id','desc')->get();
        $data['rooms_data'] = AccommodationRoom::with('planData')->where('accommodation_id',$accommodation_id)->orderBy('sort_order','asc')->get();
        $data['accommodation_id'] =$accommodation_id;
      
        $whereDate = [];
        foreach($period as $dt){
                   $date =  $dt->format("d-m-Y");
                   $whereDate[] =  $dt->format("Y-m-d");
                }

        $inventories=  AccommodationInventory::where('accommodation_id',$accommodation_id)->whereIn('date',$whereDate)->get();
        $data['inventories'] = $inventories;
        $accommodation = Accommodation::find($accommodation_id);
        $data["booking_mode"] = $accommodation->booking_mode??'';
        $data["page_heading"] = "Room Inventory (" . $accommodation->name . ")";

        return view("admin.accommodations.inventory", $data);
    }


    public function nextInventory(Request $request) {
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;
        $data = [];

        $accommodation_id = $request->accommodation_id??'';
        $date = $request->date??'';
        if(!empty($accommodation_id) && is_numeric($accommodation_id) && !empty($date) && $date != 'undefined') {
            $data = [];
            $start_date = $date ;
            $end_date =  date('Y-m-d', strtotime($date. ' + 10 days'));
            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $interval = DateInterval::createFromDateString('1 day');
            $pre_date =  date('Y-m-d', strtotime($start_date. ' - 10 days'));
            $data['pre_date'] = $pre_date;
            $data['last_date'] = $end_date;
            $period = new DatePeriod($begin, $interval, $end);
            $data['start_date'] = $start_date;
            $data['period'] = $period;
           
            $whereDate = [];
            
            $data['rooms_data'] = AccommodationRoom::where('accommodation_id',$accommodation_id)->orderBy('id','desc')->get();
            $data['accommodation_id'] =$accommodation_id;

            foreach($period as $dt){
                $date =  $dt->format("d-m-Y");
                $whereDate[] =  $dt->format("Y-m-d");
            }
            
            $inventories=  AccommodationInventory::where('accommodation_id',$accommodation_id)->whereIn('date',$whereDate)->get();
            $data['inventories'] = $inventories;
            $view_html = view("admin.accommodations.inventory_form",$data)->render();
            $data['html'] = $view_html;
            $data['pre_date'] = $pre_date;
            $data['last_date'] = $end_date;
            $success = true;
            $statusCode = 200;
        } else {
            $message = 'Invalid request';
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message,
            'data' => $data
        ), $statusCode);
    }



     public function roomPlans(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $user_id = auth()->user()->id??0;
        $user_name = auth()->user()->name??'';
        if($request->accommodation_id) {

           $accommodation_id =$request->accommodation_id ?? '';
           $plan_id =$request->plan_id ?? '';
           $room_id =$request->room_id ?? '';

           $method = $request->method();
        

           if($method == "POST"){
            $action = 'Added';
            $isNewSaved = 0;

            $rules = [];
            $rules['plan_name'] = 'required';
            $this->validate($request, $rules);
            // prd($request->toArray());

            $plan_name = $request->plan_name ?? '';
            $meal_type = $request->meal_type ?? '';
            $status = !empty($request->status)?$request->status:0;
            $plan_include = !empty($request->plan_include)?$request->plan_include:0;
            $plan_options = !empty($request->plan_options)?$request->plan_options:'';

            $plan_data = array(
                'includes' => $plan_include,
                'options' => $plan_options,

            );
            $req_data = array(

                'accommodation_id' => $accommodation_id,
                'room_id' => $room_id,
                'plan_name' => $plan_name,
                'meal_type' => $meal_type,
                'plan_json_data' => json_encode($plan_data),
                'status' => $status,
                   
             );

             //prd($req_data);

                if($plan_id ){
                    $isSaved = AccommodationPlan::where('id',$plan_id)->update($req_data);
                    $accommodation_plans_id = $plan_id;
                  $msg = "Accommodation Plan has been updated successfully.";
                }else{
                    //$isSaved = AccommodationPlan::insert($req_data);
                    $isSaved = AccommodationPlan::create($req_data);
                    $accommodation_plans_id = $isSaved->id??0;
                  $msg = "Accommodation Plan has been added successfully.";

                }
     
            if($isSaved){

            CustomHelper::updateAccommodationPublishPrice($accommodation_id);

            //===================Activity Logs=========================

             $new_data = DB::table('accommodation_plans')->where(['id'=>$accommodation_plans_id])->first();
             $module_id = !empty($new_data->accommodation_id)?$new_data->accommodation_id:'';
             $sub_module_id = !empty($new_data->room_id)?$new_data->room_id:'';
             $sub_sub_module_id = $accommodation_plans_id;
             $sub_sub_module_desc = !empty($new_data->plan_name)?$new_data->plan_name:'';
             $new_data =(array) $new_data;
             $new_data = json_encode($new_data);

             $package_list = Accommodation::where(['id'=>$module_id])->first();
             $module_desc = !empty($package_list->name)?$package_list->name:'';

             $accommodation_room = AccommodationRoom::where(['id'=>$sub_module_id])->first();
             $sub_module_desc = !empty($accommodation_room->room_type_name)?$accommodation_room->room_type_name:'';

             $params['user_id'] = $user_id;
             $params['user_name'] = $user_name;
             $params['module'] = 'Accommodation';
             $params['module_desc'] = $module_desc;
             $params['module_id'] = $module_id;
             $params['sub_module'] = "Accommodation Rooms & Pricing";
             $params['sub_module_desc'] = $sub_module_desc;
             $params['sub_module_id'] = $sub_module_id;
             $params['sub_sub_module'] = "Accommodation Plans";
             $params['sub_sub_module_id'] = $sub_sub_module_id;
             $params['sub_sub_module_desc'] = $sub_sub_module_desc;  
             $params['data_after_action'] = $new_data;
             $params['action'] = (!empty($plan_id)) ? "Update" : "Add";

             CustomHelper::RecordActivityLog($params);

            //===================Activity Logs=========================

            return back()->with("alert-success",$msg);

            }else{
                return back()->with("alert-danger","The Accommodation Plan could not be added, please try again or contact the administrator."
                );
            }
           }
            $data = [];
            $package_id = $request->package_id;
            $data['plan_id'] = $plan_id;
            $plan_data = [];
            $page_heading = "Add Room Plan";
            if($plan_id ){
                $plan_data = AccommodationPlan::find($plan_id);
                $page_heading = "Edit Room Plan";
            }
            $data['page_heading'] = $page_heading;
            $data['plan_data'] = $plan_data;
            $data['plan_includes'] = RoomPlanIncludes::where('status',1)->get();
            $data['room_id'] = $room_id;
            $data['accommodation_id'] = $accommodation_id;
            $data['records'] = AccommodationPlan::where('accommodation_id',$accommodation_id)->where('room_id',$room_id)->get();
            /*$data['package'] = Package::find($package_id);
            $data['package_price'] = PackagePriceInfo::select('title')->where('id',$price_id)->first();*/
            return view("admin.accommodations.room_plan", $data);
          } 
    }

    public function accommodation_room_plan_delete(Request $request)
    {
        $id =$request->plan_id ?? '';
        $method = $request->method();
        $user_id = auth()->user()->id??0;
        $user_name = auth()->user()->name??'';
        $room_id = $request->room_id ?? '';
        $accommodation_id = $request->id ?? '';
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $accommodation_room_plan = AccommodationPlan::find($id);

                $new_data = DB::table('accommodation_plans')->where(['id'=>$id])->first();
                $module_id = !empty($new_data->accommodation_id)?$new_data->accommodation_id:'';
                $sub_module_id = !empty($new_data->room_id)?$new_data->room_id:'';
                $sub_sub_module_id = $id;
                $sub_sub_module_desc = !empty($new_data->plan_name)?$new_data->plan_name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $package_list = Accommodation::where(['id'=>$module_id])->first();
                $module_desc = !empty($package_list->name)?$package_list->name:'';

                $accommodation_room = AccommodationRoom::where(['id'=>$sub_module_id])->first();
                $sub_module_desc = !empty($accommodation_room->room_type_name)?$accommodation_room->room_type_name:'';
               // prd($accommodation_room_plan);
                $is_deleted = $accommodation_room_plan->delete();
            }
        }
        if ($is_deleted) {
            CustomHelper::updateAccommodationPublishPrice($accommodation_id);

             $params['user_id'] = $user_id;
             $params['user_name'] = $user_name;
             $params['module'] = 'Accommodation';
             $params['module_desc'] = $module_desc;
             $params['module_id'] = $module_id;
             $params['sub_module'] = "Accommodation Rooms & Pricing";
             $params['sub_module_desc'] = $sub_module_desc;
             $params['sub_module_id'] = $sub_module_id;
             $params['sub_sub_module'] = "Accommodation Plans";
             $params['sub_sub_module_id'] = $sub_sub_module_id;
             $params['sub_sub_module_desc'] = $sub_sub_module_desc;  
             $params['data_after_action'] = $new_data;
             $params['action'] = "Delete";

             CustomHelper::RecordActivityLog($params);
             
            return back()->with("alert-success", "Accommodation Plan has been deleted successfully.");
        } else {
            return back()->with("alert-danger","The Accommodation Plan could not be deleted, please try again or contact the administrator.");
        }
    }

    public function planRates(Request $request) {
        $accommodation_id = $request->accommodation_id??'';
        $room_id = $request->room_id??'';
        if($accommodation_id && $room_id) {
            $accommodation = Accommodation::find($accommodation_id);
            $room = AccommodationRoom::find($room_id);
            if($accommodation && $room) {
                $method = $request->method();
                if($method == "POST") {
                    $response = [];
                    $response['success'] = false;
                    $response['message'] = '';
                    $room_plans = $request->room_plans??[];
                    $price = $request->price??[];
                    if(!empty($price)) {
                        foreach($price as $value) {
                            if(empty($value)) {
                                $response['message'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Base Occupancy is required for all Rate Plan!</div>';
                            }
                        }
                    }
                    $single_price = $request->single_price??[];
                    $extra_adult = $request->extra_adult??[];
                    $extra_child_1 = $request->extra_child_1??[];
                    $extra_child_2 = $request->extra_child_2??[];
                    if(!empty($room_plans) && empty($response['message'])) {
                        foreach($room_plans as $plan_id) {
                            $plan = AccommodationPlan::where('accommodation_id',$accommodation_id)->where('room_id',$room_id)->where('id',$plan_id)->first();
                            if($plan) {
                                $plan->price = $price[$plan_id]??0;
                                $plan->single_price = $single_price[$plan_id]??0;
                                $plan->extra_adult = $extra_adult[$plan_id]??0;
                                $plan->extra_child_1 = $extra_child_1[$plan_id]??0;
                                $plan->extra_child_2 = $extra_child_2[$plan_id]??0;
                                $isSaved = $plan->save();
                                if($isSaved) {
                                    $response['success'] = true;
                                    $response['message'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Rate Plan Price Updated Successfully.</div>';
                                }
                            }
                        }
                    }
                    CustomHelper::updateAccommodationPublishPrice($accommodation_id);
                    return response()->json($response);
                }
                $data = [];
                $data['page_heading'] = "Rate Plan Price (".$room->room_type_name.")";
                $data['room_id'] = $room_id;
                $data['accommodation_id'] = $accommodation_id;
                $data['room_plans'] = AccommodationPlan::where('accommodation_id',$accommodation_id)->where('room_id',$room_id)->get();
                return view("admin.accommodations.plan_rate", $data);
            }
        }
    }

    //bulk inventory 
    public function bulk_inventory(Request $request) {
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;

        $accommodation_id = $request->accommodation_id??'';
        $bulkdaterange = $request->bulkdaterange??'';
        $bulk_inventory = $request->bulk_inventory??'';
        $bulk_price = $request->bulk_price??[];
        $bulk_single_price = $request->bulk_single_price??[];
        $bulk_extra_adult = $request->bulk_extra_adult??[];
        $bulk_extra_child_1 = $request->bulk_extra_child_1??[];
        $bulk_extra_child_2 = $request->bulk_extra_child_2??[];

        if(!empty($accommodation_id) && !empty($bulkdaterange) && !empty($bulk_inventory)) {
            $bulkdaterange_arr = explode(' - ', $bulkdaterange);
            $from_date = $bulkdaterange_arr[0]??'';
            $to_date = $bulkdaterange_arr[1]??'';
            $from_date = CustomHelper::DateFormat($from_date,'Y-m-d','d/m/Y');
            $to_date = CustomHelper::DateFormat($to_date,'Y-m-d','d/m/Y');

            if($from_date && $to_date) {
                $start_date = $from_date;
                $end_date = $to_date;
                $begin = new DateTime($start_date);
                $end = new DateTime(date('Y-m-d', strtotime($end_date. ' + 1 days')));
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                foreach($period as $dt) {

                    $date = $dt->format("d-m-Y");
                    $book_date = $dt->format("Y-m-d");
                    if($bulk_inventory) {
                        foreach ($bulk_price as $room_key => $plan_data) {
                            foreach ($plan_data as $plan_key => $plan_price) {
                                if($plan_key == 0) {
                                    $plan_key = null;
                                }
                                $req_data = array(
                                    'accommodation_id' => $accommodation_id,
                                    'room_id' => $room_key,
                                    'plan_id' => $plan_key,
                                    'date' => $book_date,
                                    'inventory' => $bulk_inventory[$room_key]??0,
                                    'status' => 1,
                                );

                                if($plan_price != '') {
                                    $req_data['price'] = $plan_price??0;
                                    $req_data['single_price'] = $bulk_single_price[$room_key][$plan_key]??0;
                                    $req_data['extra_adult'] = $bulk_extra_adult[$room_key][$plan_key]??0;
                                    $req_data['extra_child_1'] = $bulk_extra_child_1[$room_key][$plan_key]??0;
                                    $req_data['extra_child_2'] = $bulk_extra_child_2[$room_key][$plan_key]??0;
                                }

                                $is_data = AccommodationInventory::where('accommodation_id',$accommodation_id)->where('room_id',$room_key)->where('plan_id',$plan_key)->where('date',$book_date)->first();
                                if($is_data) {
                                    $isSaved = AccommodationInventory::where('accommodation_id',$accommodation_id)->where('room_id',$room_key)->where('plan_id',$plan_key)->where('date',$book_date)->update($req_data);
                                    if($isSaved) {

                                        $before_days = 15;
                                        $date=strtotime(date('Y-m-d')); 
                                        $before_date = date('Y-m-d',strtotime('-15 days',$date));
                                        $is_deleted = AccommodationInventory::where('accommodation_id',$accommodation_id)->whereDate('date','<',$before_date)->delete();
                                        $success = true;
                                        $statusCode = 200;
                                    }
                                } else {
                                    $isSaved = AccommodationInventory::create($req_data);
                                    if($isSaved) {
                                        $success = true;
                                        $statusCode = 200;                                
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if($success) {
                CustomHelper::updateAccommodationPublishPrice($accommodation_id);
                $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Inventory Updated Successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Inventory not updated, please try again.</div>';
            }
            return Response::json(array(
                'success' => $success,
                'message' => $message
            ), $statusCode);
        }
    }


    // add_agent_price
    public function add_agent_price(Request $request) {
        $response = [];
        $response['success'] = false;
        $accommodation_id = $request->accommodation_id??0;
        if(!empty($accommodation_id)) {
            $accommodation = Accommodation::find($accommodation_id);
            if($accommodation) {
                $discount_category = isset($request->discount_category) ? $request->discount_category : null;
                $accommodation->discount_category_id = $discount_category;
                $is_updated = $accommodation->save();
                if($is_updated) {
                    CustomHelper::updateAccommodationPublishPrice($accommodation_id);
                    $response['success'] = true;
                }
            }
        }
        return response()->json($response);
    }

    // agent_price
    public function agent_price(Request $request) {
        $data = [];
        $limit = $this->limit;
        $accommodation_id = isset($request->accommodation_id) ? $request->accommodation_id : "";
        $day_title = (isset($request->day_title))?$request->day_title:'';
        $status = (isset($request->status))?$request->status:'';
        if(!empty($accommodation_id)) {
            $accommodation = Accommodation::find($accommodation_id);
            if($accommodation) {
                $module_name = 'hotel_listing';
                $module_record_id = $accommodation->id;
                $discount_category_id = isset($accommodation->discount_category_id)?$accommodation->discount_category_id : NULL;
                $discount_category_data = [];
                $is_default_category = 0;
                if($discount_category_id === -1) {
                    $discount_category_name = 'Custom Discount';
                    $moduleRecordDiscounts = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->orderBy('module_name', 'asc')->get();
                    // prd($moduleRecordDiscounts->toArray());
                    if($moduleRecordDiscounts) {
                        $discount_module_groups = [];
                        foreach($moduleRecordDiscounts as $moduleRecordDiscount) {
                            $discount_module_groups[] = (object)[
                                'module_discount_type_id' => '-1',
                                'agent_group_id' => $moduleRecordDiscount->agent_group_id,
                                'discount_type' => $moduleRecordDiscount->discount_type,
                                'discount_value' => $moduleRecordDiscount->discount_value,
                            ];
                        }
                        $data['discount_module_groups'] = $discount_module_groups;
                    } else {
                        $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                        $default_discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                        if($default_discount_category_id) {
                            $discount_category_data = ModuleDiscountCategory::where('id',$default_discount_category_id)->orderBy('module_name', 'asc')->first();
                        }
                        // prd($discount_category_data);
                    }
                } else if($discount_category_id === 0) {
                    $discount_category_name = 'Discount Not Applicable';
                } elseif($discount_category_id === NULL) {
                    $is_default_category = 1;
                    $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                    $discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                    $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                    $discount_category_name = $discount_category_data->name ?? '';
                } else {
                    $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                    $discount_category_name = $discount_category_data->name ?? '';
                }
                $discount_categories = ModuleDiscountCategory::where('module_name',$module_name)->orderBy('module_name', 'asc')->get();
                $data['rooms'] = AccommodationRoom::with('roomAccommodation')->where("accommodation_id",$accommodation_id)->orderBy("sort_order", "asc")->get();

                $data['agent_groups'] = AgentGroup::where('status',1)->get();
                $data["heading"] = 'Agent Price ('.$accommodation->name.')';
                $data["accommodation_id"] = $accommodation_id;
                $data["accommodation"] = $accommodation;
                $data["discount_category_id"] = $discount_category_id;
                $data["is_default_category"] = $is_default_category;
                $data["discount_categories"] = $discount_categories;
                $data["discount_category_name"] = $discount_category_name;
                $data["discount_category_data"] = $discount_category_data;
                $data["module_name"] = $module_name;
                $data["module_record_id"] = $module_record_id;

                $discount_groups = [];
                $discount_groups[] = (object)[
                    'id' => '-1',
                    'name' => 'Custom Discount',
                ];
                $data["discount_groups"] = $discount_groups;
                $data["custom_discount_section"] = true;
                return view("admin.accommodations.agent_price", $data);
            }
        }
        abort(404);
    }


    /* end of controller */
}
