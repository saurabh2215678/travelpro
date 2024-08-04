<?php
namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\User;
use App\Models\Flag;
use App\Models\Admin;
use App\Models\Itenary;
use App\Models\Package;
use App\Models\PackageInfo;
use App\Models\PackageType;
use App\Models\Destination;
use App\Models\PackageSlot;
use App\Models\ServiceLevel;
use App\Models\PriceCategory;
use App\Models\Accommodation;
use App\Models\TeamManagement;
use App\Models\PackagePriceInfo;
use App\Models\PackageInclusion;
use App\Models\PackageAirline;
use App\Models\ThemeCategories;
use App\Models\PackageAccommodation;
use App\Models\PackageSetting;
use App\Models\PackageToSetting;
use App\Models\PackageDeparture;
use App\Models\PackageDepartureCapacity;
use App\Models\PackageFlights;
use App\Models\AgentGroup;
use App\Models\ModuleDiscountCategory;
use App\Models\DiscountModuleToGroup;
use App\Models\CustomModuleRecordDiscount;
use App\Models\PackageInventory;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon as Carbon;
use DB;
use Mail;
use Session;
use Storage;
use Response;
use DateTime;
use Validator;
use DatePeriod;
use DateInterval;
class PackageController extends Controller {

    protected $currentUrl;
    protected $limit;
    protected $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->limit = 50;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request) {

        $segment2 = $request->segment(2);
        $is_activity = $request->is_activity ?? 0;
        $identifier = "package";
        if($segment2 == 'activity'){
           $is_activity = 1;
           $identifier = "activity";
        }
               
        $status = isset($request->status) ? $request->status : 1;
        $package_themes = isset($request->package_themes) ? $request->package_themes : '';
        $priceCategory = isset($request->price_category) ? $request->price_category : '';
        $package_inclusions = isset($request->package_inclusions) ? $request->package_inclusions :[];
        $featured = isset($request->featured) ? $request->featured : "";
        $data = [];
        $limit = $this->limit;
        /*$query = Package::with('packageParentDestination')->orderBy("status", "desc")->orderBy("sort_order", "asc")->orderBy("id", "desc");*/
        $query = Package::with('packageParentDestination','packageVendorData')->orderBy("featured", "desc")->orderBy("sort_order", "asc");
        $query->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        // prd($request->title);
        if($request->title) {
            $query->where("title","like","%".$request->title."%");
        }
        if($request->destination) {
            $destination_id = $request->destination??'';
            $query->where(function($q1) use($destination_id){
                $q1->where('destination_id',$destination_id);
                $q1->orWhere('sub_destination_id',$destination_id);
            });
        }
        if($request->sub_destination) {
            $query->where('sub_destination_id',$request->sub_destination);
        }
        if ($request->from_package_duration_days) {
            $query->where('package_duration_days','>=',$request->from_package_duration_days);
        }
        if ($request->to_package_duration_days) {
            $query->where('package_duration_days','<=',$request->to_package_duration_days);
        }
        if($request->tour_type) {
            $query->where("tour_type", $request->tour_type);
        }
        if($request->vendor_id) {
            $query->where("vendor_id", $request->vendor_id);
        }
        if($request->package_themes) {
            if(!empty($package_themes)) {
                $query->whereHas("packageCategories", function($q1) use($package_themes) {
                    $q1->where('theme_id',$package_themes);
                });
            }
        }
        if($request->package_type) {
            $query->where("package_type", $request->package_type);
        }
        if(!empty($priceCategory)) {
            $query->where("price_category", $priceCategory);
        }
        if($request->package_inclusions) {
            if(is_array($request->package_inclusions)) {
                $inclusions_arr = $request->package_inclusions;
            }else {
                $inclusions_arr = explode(',', $request->package_inclusions);
            }
            if(!empty($inclusions_arr)) {
                $query->where(function($q1) use($inclusions_arr) {
                    foreach($inclusions_arr as $inclusion_id) {
                        $q1->orWhereJsonContains('package_inclusions', $inclusion_id);
                    }
                });
            }
        }
        if($is_activity != '') {
            $query->where("is_activity", $is_activity);
        }
        //$query->where("status", $status);
        if($status != '-1'){
            $query->where("status", $status);
        }
        if(strlen($featured) > 0) {
            $query->where("featured", $featured);
        }

            /* if($request->checkbox) {
                if(!is_array($request->checkbox)) {
                    $checkbox = explode(',', $request->checkbox);
                } else {
                    $checkbox = $request->checkbox;
                }
                if (in_array('featured', $checkbox)) {
                    $query->where("featured", 1);
                }
                if (in_array('popular', $checkbox)) {
                    $query->where("popular", 1);
                }
            } */

        if($request->checkbox){
            if(is_array($request->checkbox)){
                $checkboxs = $request->checkbox;
                   $query->where(function($query) use($checkboxs) {
                       foreach ($checkboxs as $key => $checkbox){
                        $query->orWhereHas('packageFlags',function($q1) use($checkbox) {
                            $q1->where('flag_id',$checkbox);
                        });
                    }
                });
            }  
        }
        
        $data["vendors"] = Admin::where('is_vendor',1)->get();
        $data["packages"] = $query->paginate($limit);
        $data['destinations'] = Destination::where('is_city',0)->where('status',1)->where('parent_id',0)->orderby('sort_order', 'asc')->get();
        $data['themes'] = ThemeCategories::where('status',1)->where('identifier',$identifier)->orderBy("sort_order", "asc")->get();
        $data['types'] = PackageType::where('status',1)->where('identifier',$identifier)->orderBy("created_at", "asc")->get();
        $data['inclusions'] = PackageInclusion::where('status',1)->where('identifier',$identifier)->orderBy("created_at", "asc")->get();
        $data["flags"] = Flag::where('status',1)->where('identifier',$identifier)->get();
        $data["price_categories"] = PriceCategory::where('status',1)->get();
        $data["segment"] = $segment2;
        return view("admin.packages.index", $data);
    }

    public function add(Request $request) {
        $segment2 = $request->segment(2);
        $is_activity = $request->is_activity ?? 0;
        $id = isset($request->id) ? $request->id : 0;
        //$package_edit = (session()->has('pa_edit'))?session('pa_edit'):'';
        // pr($package_edit);
        
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        $module = "Package";
        $identifier = "package";
        if($segment2 == 'activity'){
           $is_activity = 1;
           $identifier = "activity";
           $module = "Activity";
        }
        $title = "Add ".$module;

        $package = [];
        if (is_numeric($id) && $id > 0) {
            $package = Package::find($id);
             if($segment2 == 'activity'){
                session(['pa_edit'=>'activity']);
                $identifier = "activity";
                $title = "Edit Activity (".$package->title.")";
             }else{
                session(['pa_edit'=>'packages']);
                $identifier = "package";
                $title = "Edit Package (".$package->title.")";
             }
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            $ext = "jpg,jpeg,png,gif";
            $nicknames = [
                'price_category' => 'Price Category',
                'title' => 'Package Title',
                'subtitle' => 'Sub-Title',
                'package_duration' => 'Package Duration Text',
                'package_duration_days' => 'Package Duration Days',
                'destination' => 'Destination',
                'tour_type' => 'Tour Type',
                'slug' => 'Slug',
                // 'sub_destination' => 'Sub Destination',
            ];
            $rules = [];
            $rules["title"] = 'required|max:100';//'required|min:2|regex:/^[\pL\s\-]+$/u|max:100'; //|regex:/^[\pL\s\-]+$/u
            $rules["subtitle"] = 'nullable|max:255'; //|regex:/^[\pL\s\-]+$/u
            $rules["package_duration"] = 'required|max:100';
            if($request->activity_duration_type == 2){
                $rules["package_duration_days"] = 'required';
            } else if($request->activity_duration_type == 5){
                $rules["activity_duration_lap"] = 'required';
            }
            $rules["destination"] = 'required';
            $rules["tour_type"] = 'required';
            if($segment2 == 'activity'){
                $rules["tour_type"] = 'nullable';
            }
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }
            // $rules["sub_destination"] = 'required';
            $rules["price_category"] = 'required';
            // $rules["image"] = "nullable|image|max:5120|mimes:" . $ext;
            // $rules["banner_image"] = "nullable|image|max:5120|mimes:".$ext;
            $validation_msg = [];
            $validation_msg['required'] = ':attribute is required.';
            $validation_msg['regex'] = ':attribute is invalid.';
            $this->validate($request, $rules, $validation_msg, $nicknames);


            $activity_duration = '';
            if($request->activity_duration_type == 5) {
                $activity_duration = $request->activity_duration_lap??'';
            } else {
                $activity_duration = $request->activity_duration??0;
            }

            $req_data = [];
            $req_data['tour_type'] = $request->tour_type??'';
           
            $req_data['price_category'] = $request->price_category??'';
            $req_data['title'] = $request->title??'';
            $req_data['subtitle'] = $request->subtitle??'';
            $req_data['package_duration'] = $request->package_duration??'';
            $req_data['package_duration_days'] = $request->package_duration_days??0;
           
            $req_data['activity_level'] = $request->activity_level??1;
            $req_data['package_type'] = $request->package_type??0;
            $req_data['brief'] = $request->brief??'';
            $req_data['description'] = $request->description??'';
            $req_data['inclusions'] = $request->inclusions??'';
            $req_data['exclusions'] = $request->exclusions??'';
            $req_data['payment_policy'] = $request->payment_policy??'';
            $req_data['cancellation_policy'] = $request->cancellation_policy??'';
            $req_data['terms_conditions'] = $request->terms_conditions??'';
            $req_data['inclusions_chk'] = $request->inclusions_chk??0;
            $req_data['exclusions_chk'] = $request->exclusions_chk??0;
            $req_data['payment_policy_chk'] = $request->payment_policy_chk??0;
            $req_data['cancellation_policy_chk'] = $request->cancellation_policy_chk??0;
            $req_data['terms_conditions_chk'] = $request->terms_conditions_chk??0;
            //$req_data['meta_title'] = $request->meta_title??'';
            //$req_data['meta_description'] = $request->meta_description??'';
            $req_data['star_ratings'] = $request->star_ratings??0;
            $req_data['sort_order'] = $request->sort_order??0;
            $req_data['inladakh'] = $request->inladakh??1;
            $req_data['video_link'] = $request->video_link??'';
            $req_data['destination_id'] = $request->destination??0;
            $req_data['sub_destination_id'] = $request->sub_destination??0;
            $req_data['vendor_id'] = $request->vendor_id??null;

            $related_destinations = $request->related_destination??[];
            $req_data['related_destinations'] = json_encode($related_destinations);

            $related_packages = $request->related_packages??[];
            $req_data['related_packages'] = json_encode($related_packages);

            $best_months = $request->best_months??[];
            $req_data['best_months'] = json_encode($best_months);

            $package_inclusions = $request->package_inclusions??[];
            $req_data['package_inclusions'] = json_encode($package_inclusions);

            $req_data['featured'] = $request->featured??0;
            $req_data['popular'] = $request->popular??0;
            $req_data['status'] = $request->status??1;
            $req_data['activity_duration'] = $activity_duration;
            $req_data['activity_duration_type'] = $request->activity_duration_type??1;
            $req_data['place'] = $request->place??'';
            $req_data['additional_places'] = $request->additional_places??'';
            $req_data['address'] = $request->address??'';
            $req_data['contact_person'] = $request->contact_person??'';
            $req_data['contact_phone'] = $request->contact_phone??'';
            $req_data['contact_email'] = $request->contact_email??'';

            if (!empty($package) && $package->id == $id) {
                $req_data["slug"] = CustomHelper::GetSlug('packages', 'id', $id, $request->slug);
                if($package->price_category != $request->price_category) {
                    DB::table('package_price_info')->where('package_id',$id)->delete();
                }
                $isSaved = Package::where("id", $package->id)->update($req_data);
                $package_id = $package->id;
                $msg = $module." has been updated successfully.";
            } else {
                $req_data["slug"] = CustomHelper::GetSlug('packages', 'id', $id, $request->title);
                $req_data['is_activity'] = $is_activity??0;
                if($req_data['is_activity'] == 1) {
                    $req_data['activity_duration'] = $activity_duration;
                    $req_data['activity_duration_type'] = $request->activity_duration_type??1;
                } else {
                    $req_data['activity_duration'] = 0;
                    $req_data['activity_duration_type'] = 1;
                }
                $isSaved = Package::create($req_data);
                $package_id = $isSaved->id;
                $msg = $module." has been added successfully.";
            }
            if ($isSaved) {
                if(!empty($request->experts)) {
                    $packageExpertsData = [];
                    DB::table('package_team_members')->where('package_id',$package_id)->delete();
                    foreach ($request->experts as $expert_id) {
                        $nowtime = Carbon::now();
                        $packageExpertsData[] = [
                            'package_id' => $package_id,
                            'member_id' => $expert_id,
                            'created_at' => $nowtime,
                        ];
                    }
                    DB::table('package_team_members')->insert($packageExpertsData);
                }

                DB::table('package_themes')->where('package_id',$package_id)->delete();
                if(!empty($request->package_themes)) {
                    $packageThemesData = [];
                    foreach ($request->package_themes as $theme_id) {
                        $nowtime = Carbon::now();
                        $packageThemesData[] =[
                            'package_id' => $package_id,
                            'theme_id' => $theme_id,
                            'created_at' => $nowtime,
                        ];
                    }
                    DB::table('package_themes')->insert($packageThemesData);
                }

                DB::table('package_flags')->where('package_id',$package_id)->delete();
                if(!empty($request->package_flags)) {
                    $packageFlagsData = [];
                        foreach ($request->package_flags as $flag_id) {      $packageFlagsData[] = [
                            'package_id' => $package_id,
                            'flag_id' => $flag_id?? '',
                        ];
                    }
                    DB::table('package_flags')->insert($packageFlagsData);
                }

                DB::table('package_tags')->where('package_id',$package_id)->delete();
                if(!empty($request->tags)) {
                    $tagsArr = explode(',',$request->tags);
                    foreach ($tagsArr as $tag) {
                        $nowtime = Carbon::now();
                        $tagslug = CustomHelper::GetSlug("tags","id",0,$tag);
                        $tagsData = [
                            'name' => $tag,
                            'slug' => $tagslug,
                            'status' => 1,
                            'created_at' => $nowtime,
                            'updated_at' => $nowtime,
                        ];
                        $tagObj = Tag::where('name',$tag)->first();
                        if(!empty($tagObj)){
                            $tagId = $tagObj->id;
                        } else {
                            $tagObj = Tag::create($tagsData);
                            $tagId = $tagObj->id;
                        }
                        $packageTagData[] = [
                            'package_id' => $package_id,
                            'tag_id' => $tagId,
                            'created_at' => $nowtime
                        ];

                    }
                    DB::table('package_tags')->insert($packageTagData);
                }

                $new_data = DB::table('packages')->where('id',$package_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $package_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = $module;
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                $url = $this->ADMIN_ROUTE_NAME."/".$segment2."/edit/".$module_id;
                $back_url = $request->back_url??'';
               /* if($back_url) {
                    $url .= '?back_url='.$back_url;
                }*/
                return redirect(url($url))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The ".$module." could be added, please try again or contact the administrator.");
            }
        }


        $query = Package::where('status',1)->orderBy("sort_order", "asc");
        $query->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        if($is_activity == 1) {
            $query->where('is_activity',1);
        } else {
            $query->where('is_activity',0);
        }
        $related_packages = $query->get();

        $data = [];
        $data["page_heading"] = $title;
        $data["segment"] = $segment2;
        $data["package"] = $package;
        $data["destinations"] = Destination::where('is_city',0)->get();
        $data["price_categories"] = PriceCategory::where('status',1)->get();
        //$data["package_types"] = PackageType::where('status',1)->get();
        $data["package_types"] = PackageType::where('status',1)->where('identifier',$identifier)->get();
        $data["packages"] = $related_packages;
        $data["packageInclusions"] = PackageInclusion::where('status',1)->where('identifier',$identifier)->get();
        $data["packageExperts"] = TeamManagement::where('status',1)->get();
        $data["themes"] = ThemeCategories::where('status',1)->where('identifier',$identifier)->get();
        //$data["themes"] = ThemeCategories::where('status',1)->get();
        $data["vendors"] = User::where('is_vendor',1)->get();
        //$data["flags"] = Flag::where('status',1)->get();
        $data["flags"] = Flag::where('status',1)->where('identifier',$identifier)->get();
        $data["id"] = $id;
        $data["package_id"] = $id;
        return view("admin.packages.form", $data);
    }


    public function update_vendor(Request $request) {
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        //$segment2 = $request->segment(2);
        $is_vendor = auth()->user()->is_vendor;
        $is_activity = $request->is_activity ?? 0;
        $id = isset($request->id) ? $request->id : 0;
        $package = [];

        $is_activity = 0;
        $package = Package::find($id);
        if(isset($package) && !empty($package)){
            $is_activity = $package->is_activity??0;

        }
        $module = "Package";
        if($package->is_activity == 1){
            $module = "Activity";
        }
        
        if($request->method() == "POST" || $request->method() == "post") {
        
            // $req_data = [];
            // $req_data['vendor_id'] = (isset($request->vendor_id) && !empty($request->vendor_id)) ? $request->vendor_id : 0;
            // $isSaved = Accommodation::where("id",$accommodation->id)->update($req_data);

            $package->vendor_id =(isset($request->vendor_id) && !empty($request->vendor_id)) ? $request->vendor_id : 0;
            $isSaved = $package->save();
         
            if($isSaved) {       

                //=============Activity Logs=====================

                $new_data = DB::table('packages')->where('id',$id)->first();
                $name =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $id;
                $module_desc= $name;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = $module;
                $params['module_desc'] = 'Update Vendor';
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                if($is_activity == 1){
                    $msg = "Activity Vendor has been updated successfully.";
                    return redirect(url($this->ADMIN_ROUTE_NAME . "/activity"))->with("alert-success", $msg);
                }else{
                    $msg = "Package Vendor has been updated successfully.";
                    return redirect(url($this->ADMIN_ROUTE_NAME . "/packages"))->with("alert-success", $msg);
                }
            }else {
                return back()->with("alert-danger","The Package could be added, please try again or contact the administrator.");
            }
        }

        $data = [];
        $data["package"] = $package;
        $data["is_activity"] = $is_activity;

        $data["vendors"] = Admin::where('is_vendor',1)->get();
        return view("admin.packages.update_vendor", $data);
    }

    public function update_expert(Request $request) {
        $segment2 = $request->segment(2);
        $is_activity = $request->is_activity ?? 0;

        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_vendor = auth()->user()->is_vendor;
        $package = [];
        $title = "Add Package";

        $selectDestinations = Destination::select('id','name')->where('status',1)->get();
        $is_activity = 0;
        $package = Package::find($id);
        if(isset($package) && !empty($package)){
            $is_activity = $package->is_activity??0;
        }
        
        if ($request->method() == "POST" || $request->method() == "post") {
            // $req_data = [];
            // $req_data['vendor_id'] = (isset($request->vendor_id) && !empty($request->vendor_id)) ? $request->vendor_id : 0;
            // $isSaved = Accommodation::where("id",$accommodation->id)->update($req_data);

            $package->experts =(isset($request->experts) && !empty($request->experts)) ? $request->experts : 0;
            $isSaved = $package->save();
         
            if ($isSaved) {
                $new_data = DB::table('packages')->where('id',$id)->first();
                $name =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $id;
                $module_desc= $name;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Package';
                $params['module_desc'] = 'Update Vendor';
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                if($is_activity == 1){
                    $msg = "Activity experts has been updated successfully.";
                    return redirect(url($this->ADMIN_ROUTE_NAME . "/activity"))->with("alert-success", $msg);
                }else{
                    $msg = "Package experts has been updated successfully.";
                    return redirect(url($this->ADMIN_ROUTE_NAME . "/packages"))->with("alert-success", $msg);
                }

            } else {
                return back()->with(
                    "alert-danger",
                    "The Package could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["package"] = $package;
        $data["segment"] = $segment2;
        $data["is_activity"] = $is_activity;

        $data["packageExperts"] = TeamManagement::where('status',1)->get();
        //prd($data["packageExperts"]);
        return view("admin.packages.update_expert", $data);
    }

public function package_view(Request $request)
{
    $segment2 = $request->segment(2);
    $is_activity = $request->is_activity ?? 0;
    if($segment2 == 'activity'){
       $is_activity = 1;
    }

    $id = isset($request->id) ? $request->id : 0;
    $package_query = "";
    $packageDetails = Package::find($id);
    $title = " Details (".$packageDetails->title.")";

    if (is_numeric($id) && $id > 0) {
        $package_query = Package::where("id", $id)->first();
            //prd($destination_type);
        $packageTitle = isset($package_query->title) ? $package_query->title:"";
        $title = ' Details ('.$packageTitle.')';
    }

    $data = [];
    $data["segment"] = $segment2;
    $data["page_heading"] = $title;
    $data["package"] = $packageDetails;
    $data["package"] = $package_query;
    $data["id"] = $id;
    return view("admin.packages.view", $data);
}
// Add Package Type

public function saveImage($file, $id, $type)
{
        //prd($file);
        //echo $type; die;

    $result["org_name"] = "";
    $result["file_name"] = "";

    if ($file) {
        $path = "packages/";
        $thumb_path = "packages/thumb/";

        $IMG_HEIGHT = CustomHelper::WebsiteSettings("PACKAGE_IMG_HEIGHT");
        $IMG_WIDTH = CustomHelper::WebsiteSettings("PACKAGE_IMG_WIDTH");
        $THUMB_HEIGHT = CustomHelper::WebsiteSettings("PACKAGE_THUMB_HEIGHT");
        $THUMB_WIDTH = CustomHelper::WebsiteSettings("PACKAGE_THUMB_WIDTH");

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
                $package = Package::find($id);

                if (!empty($package) && count([$package]) > 0) {
                    $storage = Storage::disk("public");

                    if ($type == "image") {
                        $old_image = $package->image;
                        $package->image = $new_image;
                    } elseif ($type == "banner_image") {
                        $old_image = $package->banner_image;
                        $package->banner_image = $new_image;
                    }

                    $isUpdated = $package->save();

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


    public function ajax_delete_image(Request $request) {
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

    public function delete(Request $request) {
        $id = $request->id??0;
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "packages/";
        $url = "";
        $module = "";
        if ($method == "POST" && !empty($id)) {
            $package = Package::find($id);
            if ($package && $package->id) {
                $is_activity = $package->is_activity??0;
                $url = "packages";
                $module = "Package";
                if($is_activity == 1) {
                    $module = "Activity";
                    $url = "activity";
                }
                $new_data = DB::table('packages')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                DB::table('package_tags')->where('package_id',$id)->delete();

                $is_delete = Package::packagesDelete($id);
                if ($is_delete['status'] != 'ok') {
                    return redirect(url('admin/'.$url))->with('alert-danger', $is_delete['message']);
                } else {
                    $delete_package_name = $is_delete['name'];
                    $is_deleted = true;
                }
                if (!empty($package) && count([$package]) > 0) {
                    if (!empty($package->image)) {
                        $image = $package->image;
                        $banner_image = $package->banner_image;
                        if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                            $is_deleted = $storage->delete($path . "thumb/" . $image);
                        }
                        if (!empty($image) && $storage->exists($path . $image)) {
                            $is_deleted = $storage->delete($path . $image);
                        }
                        if ( !empty($banner_image) && $storage->exists($path . "thumb/" . $banner_image) ) {
                            $is_deleted = $storage->delete($path . "thumb/" . $banner_image);
                        }
                        if (!empty($banner_image) && $storage->exists($path . $banner_image)) {
                            $is_deleted = $storage->delete($path . $banner_image);
                        }
                    }

                    if($is_deleted) {
                        DB::table('package_team_members')->where('package_id',$id)->delete();
                        DB::table('package_themes')->where('package_id',$id)->delete();
                        DB::table('customize_package_enquaries')->where('package_id',$id)->delete();
                        DB::table('request_details')->where('package_id',$id)->delete();
                        DB::table('book_now_enquiries')->where('package_id',$id)->delete();
                    }
                }
            }
        }
        if ($is_deleted) {
            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = $module;
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = 'Delete';
            CustomHelper::RecordActivityLog($params);

            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url))->with(
                "alert-success",
                "The ".$module." has been deleted successfully."
            );
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url))->with(
                "alert-danger",
                "The ".$module." cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    public function delete_image($id, $type) {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "packages/";
        $package = Package::find($id);

        $image = isset($package->image) ? $package->image : "";
        $banner_image = isset($package->banner_image) ? $package->banner_image : "";

        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $package->image = "";
                }
                $is_updated = $package->save();
            }
        }
        else if ($type == "banner_image") {
            if (!empty($banner_image) && $storage->exists($path . "thumb/" . $banner_image))
            {
                $is_deleted = $storage->delete($path . "thumb/" . $banner_image);
            }

            if (!empty($banner_image) && $storage->exists($path . $banner_image)) {
                $is_deleted = $storage->delete($path . $banner_image);
            }

            if ($is_deleted) {
                if ($type == "banner_image") {
                    $package->banner_image = "";
                }
                $is_updated = $package->save();
            }
        }

        return $is_updated;
    }

    // Itenaries
    public function itenaries(Request $request)
    {
        $data = [];
        $package_query = [];
        $segment2 = $request->segment(2);
        $limit = $this->limit;
        $package_id = isset($request->package_id) ? $request->package_id : "";
        $package_query = Package::find($package_id);

        //$packageDetails = Package::find($package_id);
        //$search_txt = isset($request->search_txt) ? $request->search_txt : "";
        $day_title = (isset($request->day_title))?$request->day_title:'';
        $status = (isset($request->status))?$request->status:1;
        if(!empty($package_id)){

            // $itenary_query = Itenary::where('package_id',$package_id)->orderBy("created_at", "desc");
            $itenary_query = Itenary::with('Location')->where('package_id',$package_id)->orderBy("day", "asc");
            if(!empty($day_title)) {
                $itenary_query->where("day_title","like","%" . $day_title . "%"
                );
            }
           
            $itenary_query->where("status", $status);

            $itenaries = $itenary_query->paginate($limit);

            $data["heading"] = 'Manage Itinerary ('.$package_query->title.')';
            $data["package_id"] = $package_id;
            $data["itenaries"] = $itenaries;
            $data["segment"] = $segment2;
            $data["package"] = $package_query;
            return view("admin.itenaries.index", $data);

        }
        abort(404);
    }

    // add_agent_price
    public function add_agent_price(Request $request) {
        $response = [];
        $response['success'] = false;
        $package_id = $request->package_id??0;
        if(!empty($package_id)) {
            $discount_category = isset($request->discount_category) ? $request->discount_category : null;
            $package = Package::find($package_id);
            if($package) {
                $package->discount_category_id = $discount_category;
                $is_updated = $package->save();
                if($is_updated) {
                    CustomHelper::updatePackagePublishPrice($package->id);
                }
                $response['success'] = true;                
            }
        }
        return response()->json($response);
    }


   // agent_price
    public function agent_price(Request $request) {
        $data = [];
        $package_query = [];
        $limit = $this->limit;
        $segment2 = $request->segment(2);
        $package_id = isset($request->package_id) ? $request->package_id : "";
        $day_title = (isset($request->day_title))?$request->day_title:'';
        $status = (isset($request->status))?$request->status:'';
        if(!empty($package_id)) {
            // prd($package_id);
            $packageDetails = Package::find($package_id);    
            if($packageDetails) {
                $is_activity = $packageDetails->is_activity??'';
                $module_name = 'package_listing';
                if($is_activity == 1) {
                    $module_name = 'activity_listing';
                }
                $module_record_id = $packageDetails->id;
                $discount_category_id = isset($packageDetails->discount_category_id)?$packageDetails->discount_category_id : null;
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
                } elseif($discount_category_id === Null) {
                    $is_default_category = 1;
                    $DiscountModuleToGroup = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();
                    $discount_category_id = $DiscountModuleToGroup->module_discount_type_id ?? '';
                    $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                    $discount_category_name = $discount_category_data->name ?? '';
                } else {
                    $discount_category_data = ModuleDiscountCategory::where('id',$discount_category_id)->orderBy('module_name', 'asc')->first();
                    $discount_category_name = $discount_category_data->name ?? '';
                }
                // prd($package_discount_category);
                // prd($discount_category_data->toArray());
                $discount_categories = ModuleDiscountCategory::where('module_name',$module_name)->orderBy('module_name', 'asc')->get();
                $data['agent_groups'] = AgentGroup::where('status',1)->get();
                $data["heading"] = 'Agent Price ('.$packageDetails->title.')';
                // $data["package_discount_category"] = $package_discount_category;
                $data["package_id"] = $package_id;
                $data["packageDetails"] = $packageDetails;
                $data["discount_category_id"] = $discount_category_id;
                $data["is_default_category"] = $is_default_category;
                $data["discount_categories"] = $discount_categories;
                $data["discount_category_name"] = $discount_category_name;
                $data["package"] = $packageDetails;
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
                $data["segment"] = $segment2;

                return view("admin.packages.agent_price", $data);
            }
        }
        abort(404);
    }
    public function update_itenaries_order(Request $request){

        $response['success'] = false;
        $response['msg'] = '';
        $isSaved = '';
        $package_id = isset($request->package_id) ? $request->package_id : '';
        $getAllData = $request->data;
        if(isset($package_id) && !empty($package_id)){
            foreach ($getAllData as $key => $value) {
                $getId = $value;
                // $req_data['sort_order'] = $key+1;
                $req_data['day'] = $key+1;
                // $req_data['day_title'] = 'Day '.$key+1;
                $isSaved = Itenary::where('id',$getId)->where('package_id',$package_id)->update($req_data);
             }
        }

        if ($isSaved) {
            $response['success'] = true;
            $response['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Order Updated Successfully.</div>';
        }
        return response()->json($response);

    }

    public function itenary_view(Request $request)
    {
        $package_query = [];
        $segment2 = $request->segment(2);
        $package_id = isset($request->package_id) ? $request->package_id : "";
        $id = isset($request->id) ? $request->id : "";
        $package_query = Package::find($package_id);
        $packageDetails = Package::find($package_id);
        $itenary = [];
        $title = "Package Itinerary Details (".$packageDetails->title.")";

        if(is_numeric($id) && $id > 0) {
            $itenary = Itenary::find($id);
            $packageTitle = isset($itenary->title) ? $itenary->title:"";
            $title = "Package Itinerary Details (".$packageTitle.")";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["package"] = $package_query;
        $data["segment"] = $segment2;
        $data["package"] = $packageDetails;
        $data["itenary"] = $itenary;
        $data["package_id"] = $package_id;
        $data["id"] = $id;
        return view("admin.itenaries.view", $data);
    }


    public function itenary_add(Request $request)
    {
        $package_query = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $segment2 = $request->segment(2);
        $package_id = isset($request->package_id) ? $request->package_id : 0;
        //$package_query = Package::find($package_id);
        $id = isset($request->id) ? $request->id : 0;

        if(!empty($package_id)){
            $packageDetails = Package::find($package_id);
            if(!empty($packageDetails)){

                $itenary_query = [];
                $title = "Add Itinerary (".$packageDetails->title.")";

                if (is_numeric($id) && $id > 0) {
                    $itenary_query = Itenary::find($id);
                    $title = "Edit Itinerary (" . $itenary_query->title . " )";
                }

                if($request->method() == "POST" || $request->method() == "post") {

                    if(is_numeric($id) && $id > 0) {
                        $rules["day_title"] = 'required|max:100';
                    }else{
                        // $rules["day_title"] = 'required|unique:itenaries|max:100';
                        $rules["day_title"] = 'required|max:100';
                    }

                    $rules["location_id"] = 'required|numeric';
                    $rules["day"] = 'required|numeric';
                    //$rules["day_title"] = 'required|max:100';
                    $rules["title"] = 'required|max:255';
                    $rules["description"] = 'required';

                    $this->validate($request, $rules);

                    // prd($request->toArray());

                    /*$req_data = [];
                    $req_data = $request->except([
                        "_token",
                        "id",
                        "old_image",
                        "itenary_inclusions",
                        "image",
                        "back_url",
                    ]);*/
                    $req_data = [];
                    $req_data['location_id'] = $request->location_id??0;
                    $req_data['day'] = $request->day??0;
                    $req_data['day_title'] = $request->day_title??'';
                    $req_data['title'] = $request->title??'';
                    $req_data['description'] = $request->description??'';

                    $req_data['itenary_inclusions'] = (isset($request->itenary_inclusions) && !empty($request->itenary_inclusions)) ? json_encode($request->itenary_inclusions) : '[]';
                    $req_data['meal_option'] = (isset($request->meal_option) && !empty($request->meal_option)) ? json_encode($request->meal_option) : '[]';

                    $req_data['package_id'] = $packageDetails->id;
                    $req_data['status'] = isset($request->status) ? $request->status : 0;
                    $req_data['sort_order'] = isset($request->sort_order) ? $request->sort_order : 0;

                    $req_data['tags'] = isset($request->tags) ? $request->tags : '';

                    if(!empty($itenary_query) && $itenary_query->id == $id) {
                        $isSaved = Itenary::where("id", $itenary_query->id)->update($req_data);

                        $itenary_id = $itenary_query->id;
                        $msg = "Itinerary has been updated successfully.";
                    } else {
                        $isSaved = Itenary::create($req_data);
                        $itenary_id = $isSaved->id;
                        $msg = "Itinerary has been added successfully.";
                    }

                    if($request->hasFile('image')) {
                        $file = $request->file('image');

                        if(!empty($file)){
                            $images_result = $this->saveItenaryImage($itenary_id, $file);
                            if($images_result['success']== false){
                                session()->flash('alert-danger', 'Image could not be added');
                            }
                        }
                    }

                    if($isSaved) {

                        // if(!empty($request->tags)) {

                        //     DB::table('itenary_tags')->where('itenary_id',$itenary_id)->delete();
                        //     $tagsArr = explode(',',$request->tags);
                        //     foreach ($tagsArr as $tag) {
                        //     $nowtime = Carbon::now();
                        //     $tagslug = CustomHelper::GetSlug("tags","id",0,$tag);
                        //     $tagsData = [
                        //         'name' => $tag,
                        //         'slug' => $tagslug,
                        //         'status' => 1,
                        //         'created_at' => $nowtime,
                        //         'updated_at' => $nowtime,
                        //     ];
                        //     $tagObj = Tag::where('name',$tag)->first();
                        //     if(!empty($tagObj)){
                        //         $tagId = $tagObj->id;
                        //     } else {
                        //         $tagObj = Tag::create($tagsData);
                        //         $tagId = $tagObj->id;
                        //     }
                        //     $itenaryTagData[] = [
                        //         'itenary_id' => $id,
                        //         'tag_id' => $tagId,
                        //         'created_at' => $nowtime
                        //     ];
                        // }
                        // DB::table('itenary_tags')->insert($itenaryTagData);

                    // }
                        cache()->forget("itenaries");

                    //===================Activity Logs=============

                        $new_data = DB::table('itenaries')->where(['id'=>$itenary_id])->first();
                        $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
                        $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
                        $submodule_id = $itenary_id;
                        $new_data =(array) $new_data;
                        $new_data = json_encode($new_data);

                        $package_list = Package::where(['id'=>$module_id])->first();
                        $module_desc = !empty($package_list->title)?$package_list->title:'';

                        $params['user_id'] = $user_id;
                        $params['user_name'] = $user_name;
                        $params['module'] = 'Package';
                        $params['module_desc'] = $module_desc;
                        $params['module_id'] = $module_id;
                        $params['sub_module'] = "Itinerary";
                        $params['sub_module_desc'] = $sub_module_desc;
                        $params['sub_module_id'] = $submodule_id;
                        $params['sub_sub_module'] = "";
                        $params['sub_sub_module_id'] = 0;
                        $params['data_after_action'] = $new_data;
                        $params['action'] = (!empty($itenary_query->id)) ? "Update" : "Add";

                        CustomHelper::RecordActivityLog($params);


                        CustomHelper::updatePackageDayNight($package_id);

                    //===================Activity Logs============

                        $url = $this->ADMIN_ROUTE_NAME."/packages/".$package_id."/itenary/edit/".$itenary_id;

                        return redirect(url($url))->with("alert-success", $msg);

                    /*return redirect(
                        route($this->ADMIN_ROUTE_NAME . ".packages.itenaries",['package_id' => $package_id])
                    )->with("alert-success", $msg);*/

                } else {
                    return back()->with("alert-danger","The Package Itenaries could be added, please try again or contact the administrator.");
                }
            }

            $data = [];
            $data["page_heading"] = $title;
            //$data["package"] = $package_query;
            $data["package"] = $packageDetails;
            $data["segment"] = $segment2;
            $data["itenary"] = $itenary_query;
            $data["packageInclusions"] = PackageInclusion::where('status',1)->get();

            $data["id"] = $id;
            $data["package_id"] = $packageDetails->id;
            $data["destination_id"] = $packageDetails->sub_destination_id;
            return view("admin.itenaries.form", $data);

        }
        abort(404);
    }
    abort(404);
}

private function saveItenaryImage($itenary_id, $file){

    $itenary = Itenary::find($itenary_id);

    if ($file) {
        $path = 'itenaries/';
        $thumb_path = 'itenaries/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);

        $IMG_HEIGHT = CustomHelper::WebsiteSettings('CMS_IMG_HEIGHT');
        $IMG_WIDTH = CustomHelper::WebsiteSettings('CMS_IMG_WIDTH');
        $THUMB_HEIGHT = CustomHelper::WebsiteSettings('CMS_THUMB_WIDTH');
        $THUMB_WIDTH = CustomHelper::WebsiteSettings('CMS_THUMB_HEIGHT');

        $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:768;
        $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:768;
        $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:336;
        $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

        if($uploaded_data['success']){
            $new_image = $uploaded_data["file_name"];

            if (!empty($itenary)) {
                $storage = Storage::disk("public");

                $old_image = $itenary->image;
                $itenary->image = $new_image;

                $isUpdated = $itenary->save();

                if ($isUpdated) {
                    if(!empty($old_image) && $storage->exists($path . $old_image)) {
                        $storage->delete($path . $old_image);
                    }

                    if(!empty($old_image) && $storage->exists($thumb_path . $old_image)) {
                        $storage->delete($thumb_path . $old_image);
                    }
                }
            }
        }

        if(!empty($uploaded_data)){
            return $uploaded_data;
        }
    }
}

public function ajax_delete_itenary_image(Request $request)
{
    $result["success"] = false;
    $result["msg"] ='<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';

    $id = $request->has("id") ? $request->id : 0;

    if(!empty($id)){

        $is_deleted = "";
        $is_updated = "";

        $storage = Storage::disk("public");
        $path = "itenaries/";
        $itenary = Itenary::find($id);

        $image = isset($itenary->image) ? $itenary->image : "";

        if(!empty($image) && $storage->exists($path . "thumb/" . $image)) {
            $is_deleted = $storage->delete($path . "thumb/" . $image);
        }

        if(!empty($image) && $storage->exists($path . $image)) {
            $is_deleted = $storage->delete($path . $image);
        }

        if ($is_deleted) {
            $itenary->image = "";
            $is_updated = $itenary->save();

            $result["success"] = true;
            $result["msg"] ='<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Package image delete successfully.</div>';

        }
    }
    return response()->json($result);
}

public function itenary_delete(Request $request)
{
    $id = isset($request->id) ? $request->id:"";
    $package_id = isset($request->package_id) ? $request->package_id:"";
    $method = $request->method();
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {

            $itenary_query = Itenary::find($id);

               /* $function_name = $this->currentUrl;
                $action_table = "itenaries";
                $row_id = $id;
                $action_type = "Delete Itenary";
                $itenaryTitle = isset($itenary_query->title) ? $itenary_query->title:"";
                $action_description = "Delete (" . $itenaryTitle . ")";
                $description = "Delete (" . $itenaryTitle . ")";*/

                $new_data = DB::table('itenaries')->where(['id'=>$id])->first();
                $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
                $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $package_list = Package::where(['id'=>$module_id])->first();
                $module_desc = !empty($package_list->title)?$package_list->title:'';


                $is_delete = Itenary::packageItenariesDelete($id);
                if ($is_delete['status'] != 'ok') {

                    return redirect(route($this->ADMIN_ROUTE_NAME . ".packages.itenaries",['package_id' => $package_id]))->with('alert-danger', $is_delete['message']);

                }
                else {

                    $delete_itenaries_name = $is_delete['name'];

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

                $storage = Storage::disk("public");
                $path = "itenaries/";

                $image = isset($itenary_query->image) ? $itenary_query->image : "";

                if(!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                    $is_deleted = $storage->delete($path . "thumb/" . $image);
                }

                if(!empty($image) && $storage->exists($path . $image)) {
                    $is_deleted = $storage->delete($path . $image);
                }

                //$is_deleted = $itenary_query->delete();
            }
        }

        if ($is_deleted) {
           /* CustomHelper::recordActionLog(
                $function_name,
                $action_table,
                $row_id,
                $action_type,
                $action_description,
                $description
            );*/

            //===================Activity Logs==============================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Package';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id??0;
            $params['sub_module'] = "Itinerary";
            $params['sub_module_desc'] = $sub_module_desc??'';
            $params['sub_module_id'] = $id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

                //===================Activity Logs==============================

            return redirect(url($this->ADMIN_ROUTE_NAME . "/packages/".$package_id."/itenaries"))->with("alert-success", "Itenary has been deleted successfully.");
        } else {

            return redirect(url($this->ADMIN_ROUTE_NAME . "/packages/".$package_id."/itenaries"))->with("alert-danger","Itenary cannot be deleted, please try again or contact the administrator.");
        }
    }
    // Package Type
    public function type_index(Request $request)
    {
        $data = [];
        // $limit = $this->limit;
        $limit = 50;
        $segment2 = $request->segment(2);
        $package_type = isset($request->package_type) ? $request->package_type : "";
        $status = isset($request->status) ? $request->status : 1;
        $identifier = 'package';
        if($segment2 == 'activity'){
           $identifier = 'activity';
        }
        $package_query = PackageType::where('identifier',$identifier)->orderBy("package_type", "asc");
        if (!empty($package_type)) {
            $package_query->where("package_type","like","%" . $package_type . "%");
        }
        $package_query->where("status", $status);
        $packages = $package_query->paginate($limit);
        $data["packages"] = $packages;
        $data["segment"] = $segment2;
        return view("admin.package_types.index", $data);
    }

    public function type_add(Request $request)
    {
        $segment2 = $request->segment(2);
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $package_query = [];

        $module = "Package";
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
            $module = "Activity";
        }
        $title = "Add ".$module." Type";

        if (is_numeric($id) && $id > 0) {
           $package_query = PackageType::find($id);
            if($identifier == 'activity'){
                $title = "Edit Activity Type(" . $package_query->package_type . " )";
            }else{
                $title = "Edit Package Type(" . $package_query->package_type . " )";
            }
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["package_type"] = 'required|max:255';

            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except(["_token","page","back_url","featured",
            ]);
            $req_data['identifier'] = $request->identifier ?? '';

            if(empty($package_query->id)) {
                $slug = CustomHelper::GetSlug("package_types","id",$id,$request->package_type);
            }else {
                $slug = CustomHelper::GetSlug("package_types","id",$id,$request->slug);
            }
            $req_data["slug"] = $slug;

            if (!empty($package_query) && $package_query->id == $id) {
                $isSaved = PackageType::where("id", $package_query->id)->update($req_data);
                $package_type_id = $package_query->id;
                $msg = $module." Type has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "package_types";
                $row_id = $id;
                $action_type = "Edit On Package Type";
                $action_description = "Edit On (" . $request->package_type . ")";
                $description =
                "Update(" . $request->package_type . ") with : " . $description;*/
            } else {
                $isSaved = PackageType::create($req_data);
                $package_type_id = $isSaved->id;
                $msg = $module." Type has been added successfully.";

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
                cache()->forget("package_types");

               /* CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
           );*/

                 //=============Activity Logs=====================

           $new_data = DB::table('package_types')->where('id',$package_type_id)->first();
           $module_desc =  !empty($new_data->package_type)?$new_data->package_type:'';
           $new_data =(array) $new_data;
           $new_data = json_encode($new_data);

           $module_id = $package_type_id;

           $params['user_id'] = $user_id;
           $params['user_name'] = $user_name;
           $params['module'] = $module.' Type';
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
            url($this->ADMIN_ROUTE_NAME . "/".$segment2."/types")
        )->with("alert-success", $msg);
       } else {
        return back()->with(
            "alert-danger",
            "The " .$module." Type could be added, please try again or contact the administrator."
        );
    }
}

$data = [];
$data["page_heading"] = $title;
$data["segment"] = $segment2;
$data["identifier"] = $identifier;
$data["package_query"] = $package_query;
$data["id"] = $id;

return view("admin.package_types.form", $data);
}

public function type_delete(Request $request)
{
    $id = isset($request->id)?$request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $method = $request->method();
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {

            $package_query = PackageType::find($id);
            $identifier = $package_query->identifier??'';
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

                $new_data = DB::table('package_types')->where('id',$id)->first();
                $module_desc =  !empty($new_data->package_type)?$new_data->package_type:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_deleted = $package_query->delete();
            }
        }

        if ($is_deleted) {

           /* CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
       );*/

            //=============Activity Logs=====================

       $params = [];
       $params['user_id'] = $user_id;
       $params['user_name'] = $user_name;
       $params['module'] = $module.' Type';
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

       return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/types"))->with("alert-success", $module." Type has been deleted successfully.");
   } else {

    return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/types"))->with("alert-danger",$module." Type cannot be deleted, please try again or contact the administrator.");
}
}

public function type_view(Request $request)
{
    $id = isset($request->id) ? $request->id : 0;
    $package_query = "";
    $segment2 = $request->segment(2);
    $identifier = 'package';
    if($segment2 == 'activity'){
       $identifier = 'activity';
   }
    if (is_numeric($id) && $id > 0) {
        $package_query = PackageType::where("id", $id)->where('identifier',$identifier)->first();
        $title = $module." Type";
    }

    $data = [];
    $data["page_heading"] = $title;
    $data["segment"] = $segment2;
    $data["package_query"] = $package_query;
    $data["id"] = $id;
    return view("admin.package_types.view", $data);
}

public function changeStatus(Request $request)
{

    $package_query = PackageType::find($request->id);
    $package_query->status = $request->status;
    $package_query->save();

    return response()->json([
        "success" => "Package Type status change successfully.",
    ]);
}
// Package Type Closed

    // Package Inclusion List Code Here

public function lists(Request $request)
{
    $data = [];
    $limit = 50;
    $segment2 = $request->segment(2);
    $title = isset($request->title) ? $request->title : "";
    $status = isset($request->status) ? $request->status : 1;
    $identifier = 'package';
    if($segment2 == 'activity'){
        $identifier = 'activity';
    }
    $package_list = PackageInclusion::where('identifier',$identifier)->orderBy("sort_order", "asc");
    if (!empty($title)) {
        $package_list->where("title", "like", "%" . $title . "%");
    }

    $package_list->where("status", $status);
    
    $package_lists = $package_list->paginate($limit);

    $data["package_lists"] = $package_lists;
    $data["segment"] = $segment2;
    //$data['users'] = $users;

    return view("admin.package_inclusion_lists.index", $data);
}

public function add_list(Request $request)
{
    $segment2 = $request->segment(2);
    $id = isset($request->id) ? $request->id : 0;
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    //prd($id);
    $package_list = [];
    $module = "Package";
    $identifier = 'package';
    if($segment2 == 'activity'){
        $module = "Activity";
        $identifier = 'activity';
    }
    $title = "Add ".$module." Inclusion List";

    if (is_numeric($id) && $id > 0) {
        $package_list = PackageInclusion::find($id);
        if($segment2 == 'activity'){
            $title = "Edit Activity Inclusion List(" . $package_list->title . " )";
        }else{
            $identifier = "package";
            $title = "Edit Package Inclusion List(" . $package_list->title . " )";
        }
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
            "image",
            "old_image",
        ]);

        $req_data['sort_order'] = (isset($request->sort_order))?$request->sort_order:0;
        $req_data['identifier'] = !empty($request->identifier)?$request->identifier:'';
        if(empty($package_list->id)) {
                $slug = CustomHelper::GetSlug("package_inclusion_lists","id",$id,$request->title
                );
            }else {
                $slug = CustomHelper::GetSlug("package_inclusion_lists","id",$id,$request->slug);
            }
            $req_data["slug"] = $slug;
        if (!empty($package_list) && $package_list->id == $id) {
            $isSaved = PackageInclusion::where("id",$package_list->id)->update($req_data);
            $package_lists_id = $package_list->id;
            $msg = $module." Inclusion List has been updated successfully.";
            } else {
                $isSaved = PackageInclusion::create($req_data);
                $package_lists_id = $isSaved->id;
                $msg = $module." Inclusion List has been added successfully.";
            }

            if ($isSaved) {

                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveInclusionImg($file, $package_lists_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }
                cache()->forget("package_inclusion_lists");
                cache()->forget("package_inclusions_other");

                //=============Activity Logs=====================

            $new_data = DB::table('package_inclusion_lists')->where('id',$package_lists_id)->first();
            $module_desc =  !empty($new_data->title)?$new_data->title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $module_id = $package_lists_id;

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = $module.' Inclusion List';
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

            return redirect(url($this->ADMIN_ROUTE_NAME.'/'.$segment2.'/lists'))->with("alert-success", $msg);
        }else {
            return back()->with(
                "alert-danger","The " .$module." Inclusion List could be added, please try again or contact the administrator."
            );
        }
    }

    $data = [];
    $data["page_heading"] = $title;
    $data["segment"] = $segment2;
    $data["identifier"] = $identifier;
    $data["package_list"] = $package_list;
    $data["id"] = $id;

    return view("admin.package_inclusion_lists.form", $data);
}


public function saveInclusionImg($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "inclusion/";
            $thumb_path = "inclusion/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "PACKAGE_INCLUSION_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "PACKAGE_INCLUSION_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "PACKAGE_INCLUSION_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "PACKAGE_INCLUSION_IMG_THUMB_HEIGHT"
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
                    $inclusion_query = PackageInclusion::find($id);

                    if (!empty($inclusion_query)) {
                        $storage = Storage::disk("public");

                        $old_image = $inclusion_query->image;
                        $inclusion_query->image = $new_image;

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

public function list_delete(Request $request)
{
    $id = isset($request->id)?$request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $method = $request->method();
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {
            $package_list = PackageInclusion::find($id);
            $identifier = $package_list->identifier??'';
                $url = "packages";
                $module = "Package";
                if($identifier == 'activity') {
                    $module = "Activity";
                    $url = "activity";
                }
                /*$function_name = $this->currentUrl;
                $action_table = "package_inclusion_lists";
                $row_id = $id;
                $action_type = "Delete Package Inclusion List";
                $action_description = "Delete (" . $package_list->title . ")";
                $description = "Delete (" . $package_list->title . ")";*/

                $new_data = DB::table('package_inclusion_lists')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_deleted = $package_list->delete();
            }
        }

        if ($is_deleted) {
           /* CustomHelper::recordActionLog( $function_name,$action_table,$row_id,$action_type,$action_description,$description
       );*/

            //=============Activity Logs=====================

       $params = [];
       $params['user_id'] = $user_id;
       $params['user_name'] = $user_name;
       $params['module'] = $module.' Inclusion List';
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

       return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/lists"))->with(
          "alert-success",$module." Inclusion List has been deleted successfully."
      );
    } else {
        return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/lists"))->with("alert-danger",$module." Inclusion List cannot be deleted, please try again or contact the administrator.");
    }
}

    public function list_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $package_list = "";
        $segment2 = $request->segment(2);
        $module = "Package";
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
            $module = "Activity";
        }
        $title = $module." Inclusion List";

        if (is_numeric($id) && $id > 0) {
            $package_list = PackageInclusion::where("id", $id)->where("identifier", $identifier)->first();
            $title = $module." Inclusion List";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["segment"] = $segment2;
        $data["package_list"] = $package_list;
        $data["id"] = $id;
        return view("admin.package_inclusion_lists.view", $data);
    }

    public function changeUserStatus(Request $request)
    {

        $package_list = PackageInclusion::find($request->id);
        $package_list->status = $request->status;
        $package_list->save();

        return response()->json([
            "success" => "Package Inclusion List status change successfully.",
        ]);
    }

    // Add Package Airline Code Start.....
    public function airlines(Request $request) {
        $data = [];
        $limit = 50;
        $airline_name = isset($request->airline_name) ? $request->airline_name : "";
        $status = isset($request->status) ? $request->status : "";
        $segment2 = $request->segment(2);
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
        }
        $airline_query = PackageAirline::where('identifier',$identifier)->orderBy("sort_order", "asc");
        if (!empty($airline_name)) {
            $airline_query->where("airline_name", "like", "%" . $airline_name . "%");
        }
        if (strlen($status) > 0) {
            $airline_query->where("status", $status);
        }
        $airlines = $airline_query->paginate($limit);
        $data["airlines"] = $airlines;
        $data["segment"] = $segment2;
        return view("admin.package_airlines.index", $data);
    }

    public function add_airline(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $segment2 = $request->segment(2);
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $airline_query = [];
        $module = "Package";
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
            $module = "Activity";
        }
        $title = "Add ".$module." Airline";

        if (is_numeric($id) && $id > 0) {
            $airline_query = PackageAirline::find($id);
            // prd($airline_query->toArray());
            if($identifier == 'activity'){
                $title = "Edit Activity Airline(" . $airline_query->airline_name . " )";
            }else{
                $title = "Edit Package Airline(" . $airline_query->airline_name . " )";
            }
        }

        if ($request->method() == "POST" || $request->method() == "post") {
            $ext = "jpg,jpeg,png,gif";
            $rules = [];
            $rules["airline_name"] = 'required|max:255';
            $rules["airline_code"] = 'required|max:10';
            $rules["status"] = "required";
            $rules["image"] = "nullable|image|mimes:" . $ext;
            $validation_msg = [];
            $validation_msg["airline_name.required"] = "Airline Name is required.";
            $validation_msg["airline_code.required"] = "Airline Code is required.";
            $this->validate($request, $rules, $validation_msg);
            // $req_data = [];
            // $req_data = $request->except([
            //     "_token",
            //     "old_image",
            //     "icon",
            //     "back_url",
            // ]);
            $req_data = [];
            $req_data['airline_name'] = $request->airline_name??'';
            $req_data['airline_code'] = $request->airline_code??'';
            $req_data['sort_order'] = $request->sort_order??'0';
            $req_data['status'] = $request->status??'0';
            $req_data['identifier'] = !empty($request->identifier)?$request->identifier:'';
            if(empty($airline_query->id)) {
                $slug = CustomHelper::GetSlug("package_airlines","id",$id,$request->airline_name);
            }else {
                $slug = CustomHelper::GetSlug("package_airlines","id",$id,$request->slug);
            }
            $req_data["slug"] = $slug;
            if (!empty($airline_query) && $airline_query->id == $id) {
                $isSaved = PackageAirline::where("id",$airline_query->id)->update($req_data);
                $package_airlines_id = $airline_query->id;
                $msg = $module." Airline has been updated successfully.";
            } else {
                $isSaved = PackageAirline::create($req_data);
                $package_airlines_id = $isSaved->id;
                $msg = $module." Airline has been added successfully.";
            }


            if ($isSaved) {
                if ($request->hasFile("image")) {
                    $file = $request->file("image");
                    $image_result = $this->saveImg($file, $package_airlines_id, "image");
                    if ($image_result["success"] == false) {
                        session()->flash(
                            "alert-danger",
                            "Image could not be added"
                        );
                    }
                }
                cache()->forget("package_airlines");

                $new_data = DB::table('package_airlines')->where('id',$package_airlines_id)->first();
                $module_desc =  !empty($new_data->airline_name)?$new_data->airline_name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $package_airlines_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = $module.' Airlines';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME ."/".$segment2."/airlines"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The ".$module." Airline could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["airline_query"] = $airline_query;
        $data["segment"] = $segment2;
        $data["identifier"] = $identifier;
        $data["id"] = $id;
        return view("admin.package_airlines.form", $data);
    }

    public function airline_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $airline_query = "";
        $segment2 = $request->segment(2);
        $module = "Package";
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
            $module = "Activity";
        }
        $title = $module." Airline Details";
        if (is_numeric($id) && $id > 0) {
            $airline_query = PackageAirline::where("id", $id)->where('identifier',$identifier)->first();
            $title = $module." Airline Details (".$airline_query->airline_name.")";
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["segment"] = $segment2;
        $data["airline_query"] = $airline_query;
        $data["id"] = $id;
        return view("admin.package_airlines.view", $data);
    }

    public function saveImg($file, $id, $type)
    {
        $result["org_name"] = "";
        $result["file_name"] = "";

        if ($file) {
            $path = "airlines/";
            $thumb_path = "airlines/thumb/";

            $IMG_HEIGHT = CustomHelper::WebsiteSettings(
                "PACKAGE_AIRLINE_IMG_HEIGHT"
            );
            $IMG_WIDTH = CustomHelper::WebsiteSettings(
                "PACKAGE_AIRLINE_IMG_WIDTH"
            );
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings(
                "PACKAGE_AIRLINE_IMG_THUMB_WIDTH"
            );
            $THUMB_WIDTH = CustomHelper::WebsiteSettings(
                "PACKAGE_AIRLINE_IMG_THUMB_HEIGHT"
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
                    $airline_query = PackageAirline::find($id);

                    if (!empty($airline_query)) {
                        $storage = Storage::disk("public");

                        $old_image = $airline_query->image;
                        $airline_query->image = $new_image;

                        $isUpdated = $airline_query->save();

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

    public function ajax_delete_image_airline(Request $request) {
        $result["success"] = false;
        $image_id = $request->has("image_id") ? $request->image_id : 0;
        $type = $request->has("type") ? $request->type : "";

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_image_airline($image_id, $type);
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

    public function delete_airline(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;
        $storage = Storage::disk("public");
        $path = "airlines/";

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $airline_query = PackageAirline::find($id);
                $identifier = $airline_query->identifier??'';
                $url = "packages";
                $module = "Package";
                if($identifier == 'activity') {
                    $module = "Activity";
                    $url = "activity";
                }
                $new_data = DB::table('package_airlines')->where('id',$id)->first();
                $module_desc =  !empty($new_data->airline_name)?$new_data->airline_name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                if (!empty($airline_query) && count([$airline_query]) > 0) {
                    if (
                        !empty($airline_query->image)
                    ) {
                        $image = $airline_query->image;
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
                    $is_deleted = $airline_query->delete();
                }
            }
        }
        if ($is_deleted) {
            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = $module.' Airlines';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);


            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/airlines"))->with("alert-success","The Package Airline has been deleted successfully.");
        } else {
            return redirect($this->ADMIN_ROUTE_NAME . "/".$url."/airlines")->with("alert-danger","The ".$module."  Airline cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function delete_image_airline($id, $type) {
        $is_deleted = "";
        $is_updated = "";
        $storage = Storage::disk("public");
        $path = "airlines/";
        $airline_query = PackageAirline::find($id);
        $image = isset($airline_query->image) ? $airline_query->image : "";
        if ($type == "image") {
            if (!empty($image) && $storage->exists($path . "thumb/" . $image)) {
                $is_deleted = $storage->delete($path . "thumb/" . $image);
            }
            if (!empty($image) && $storage->exists($path . $image)) {
                $is_deleted = $storage->delete($path . $image);
            }

            if ($is_deleted) {
                if ($type == "image") {
                    $airline_query->image = "";
                }
                $is_updated = $airline_query->save();
            }
        }
        return $is_updated;
    }

// Add Package Airline Code Start.....

    // Package Additional Info
public function additional_info(Request $request)
{
    $package_query = [];
    $segment2 = $request->segment(2);
    $package_id = isset($request->id) ? $request->id:'';

    $package_query = Package::find($package_id);

    //$packageDetails = Package::find($package_id);
    if (isset($package_id) && !empty($package_id) && is_numeric($package_id)) {
        $data = [];
        $limit = $this->limit;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : "";
        $package_info = PackageInfo::where("package_id",$package_id)->orderBy("title", "ASC");
        if (!empty($title)) {
            $package_info->where("title", "like", "%" . $title . "%");
        }
        if (strlen($status) > 0) {
            $package_info->where("status", $status);
        }

        $package_infos = $package_info->paginate($limit);
        $data["page_heading"] = 'Additional Info ('.$package_query->title.')';
        $data["package_id"] = $package_id;
        $data["package_infos"] = $package_infos;
        $data["package"] = $package_query;
        $data["segment"] = $segment2;
        //prd($data["segment"]);
        $data["limit"] = $limit;
//prd($data);
        return view("admin.packages.info_index", $data);
    }
    abort(404);
}

public function additional_info_add(Request $request)
{
    $package_query = [];
    $segment2 = $request->segment(2);
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $package_id = isset($request->id) ? $request->id : 0;
    $package_query = Package::find($package_id);
    //$packageDetails = Package::find($package_id);
    $id = isset($request->info_id) ? $request->info_id : 0;

    $module = "Package";
    if($segment2 == 'activity'){
       $module = "Activity";
    }
    $package_info = [];
    $name = "Add Additional Info (".$package_query->title.")";
    if (is_numeric($id) && $id > 0) {
        $package_info = PackageInfo::find($id);
        $name = "Edit Additional Info (" . $package_info->title . " )";
    }
    if ($request->method() == "POST" || $request->method() == "post") {
        $rules["title"] = "required|max:255";
        $rules["description"] = "required";
        $rules["status"] = "required";

        $this->validate($request, $rules);

        $package_info = PackageInfo::find($id);
        $req_data = [];
        $req_data = $request->except(["_token", "page", "back_url"]);
        $req_data["package_id"] = $package_id;
        $req_data["sort_order"] = (!empty($request->sort_order))?$request->sort_order:0;
        $req_data['brief'] = $request->brief??'';

        if (!empty($package_info) && $package_info->count() > 0) {
            $isSaved = PackageInfo::where("id",$package_info->id)->update($req_data);
            $package_info_id = $package_info->id;
            $msg = $module." Additional Info has been updated successfully.";
        } else {
            $isSaved = PackageInfo::create($req_data);
            $package_info_id = $isSaved->id;
            $msg = $module." Additional Info has been added successfully.";
        }

        if ($isSaved) {

            //===================Activity Logs=============

            $new_data = DB::table('package_info')->where(['id'=>$package_info_id])->first();
            $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $submodule_id = $package_info_id;
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package_list = Package::where(['id'=>$module_id])->first();
            $module_desc = !empty($package_list->title)?$package_list->title:'';

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = $module;
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "Additional Info";
            $params['sub_module_desc'] = $sub_module_desc;
            $params['sub_module_id'] = $submodule_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($package_info->id)) ? "Update" : "Add";

            CustomHelper::RecordActivityLog($params);

            //===================Activity Logs============

            cache()->forget("package_info");
            //return redirect(route($this->ADMIN_ROUTE_NAME . ".packages.additional_info",$package_id))->with("alert-success", $msg);
            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$segment2."/".$package_id."/additional-info"))->with("alert-success", $msg);
        } else {
            return back()->with("alert-danger","The ".$module." additional info could be added, please try again or contact the administrator."
            );
        }
    }
    $data = [];
    //$data["package"] = $packageDetails;
    $data["package"] = $package_query;
    $data["page_heading"] = $name;
    $data["package_info"] = $package_info;
    $data["segment"] = $segment2;
    $data["package_id"] = $package_id;
    $data["id"] = $id;
    return view("admin.packages.info_form", $data);
}

public function additional_info_delete(Request $request)
{
    $package_id = !empty($request->id)?$request->id:'';
    $id = !empty($request->info_id)?$request->info_id:'';
    $segment2 = $request->segment(2);
    $method = $request->method();
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {
            $package_info = PackageInfo::find($id);

            $new_data = DB::table('package_info')->where(['id'=>$id])->first();
            $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package_list = Package::where(['id'=>$module_id])->first();
            $module_desc = !empty($package_list->title)?$package_list->title:'';
            $is_deleted = $package_info->delete();
        }
    }
    if ($is_deleted) {

        //===================Activity Logs====================

        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'Package';
        $params['module_desc'] = $module_desc;
        $params['module_id'] = $module_id;
        $params['sub_module'] = "Additional Info";
        $params['sub_module_desc'] = $sub_module_desc;
        $params['sub_module_id'] = $id;
        $params['sub_sub_module'] = "";
        $params['sub_sub_module_id'] = 0;
        $params['data_after_action'] = $new_data;
        $params['action'] = 'Delete';

        CustomHelper::RecordActivityLog($params);

        //===================Activity Logs===================

        //return redirect(route($this->ADMIN_ROUTE_NAME . ".packages.additional_info",$package_id))->with("alert-success","Package additional info has been deleted successfully.");
        return redirect(url($this->ADMIN_ROUTE_NAME . "/".$segment2."/".$package_id."/additional-info"))->with("alert-success", ucfirst($segment2)." additional info has been deleted successfully.");

    } else {
        return redirect(route($this->ADMIN_ROUTE_NAME . ".packages.additional_info",$package_id))->with("alert-danger", ucfirst($segment2)." additional info cannot be deleted, please try again or contact the administrator.");
    }
}

    // Package Price Info
public function price_info(Request $request)
{
    $package_query = [];
    $package_id = $request->package_id;
    $package_query = Package::find($package_id);
    $packageDetails = Package::find($package_id);
    if (isset($package_id) && !empty($package_id) && is_numeric($package_id)) {
        $data = [];
        $limit = $this->limit;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : "";
        $package_price_info = PackagePriceInfo::where("package_id",$package_id)->orderBy("title", "ASC");
        if (!empty($title)) {
            $package_price_info->where("title", "like", "%" . $title . "%");
        }
        if (strlen($status) > 0) {
            $package_price_info->where("status", $status);
        }

        $package_prices = $package_price_info->paginate($limit);
        $data["package_id"] = $package_id;
        $data["package_prices"] = $package_prices;
        $data["heading"] = 'Price Info ('.$packageDetails->title.')';
        $data["limit"] = $limit;
        $data["package"] = $package_query;

        return view("admin.packages.price_index", $data);
    }
    abort(404);
}

public function price_info_add(Request $request)
{
    $package_query = [];
    $package_id = isset($request->package_id) ? $request->package_id : 0;
    $package_query = Package::find($package_id);
    $packageDetails = Package::find($package_id);
    $module = "Package";
    if($packageDetails->is_activity == 1){
           $module = "Activity";
        }
    $id = isset($request->info_id) ? $request->info_id : 0;
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $package_price_info = [];
    $packageAccommodations = "";

    $name = "Add Price Info (".$packageDetails->title.")";
    if (is_numeric($id) && $id > 0) {
        $package_price_info = PackagePriceInfo::find($id);
        $name = "Edit Price Info (" . $package_price_info->title . " )";

        $packageAccommodations = DB::table('package_accommodations')->where('package_price_id',$id)->get();
    }

    $packageCategoryElements = $packageDetails->packagePriceCategory->priceCategoryElements??[];

    if ($request->method() == "POST" || $request->method() == "post") {
        $rules["title"] = "required|max:255";
        $rules["service_level"] = "required";
        $rules["status"] = "required";

        $this->validate($request, $rules);

        $package_price_info = PackagePriceInfo::find($id);
        $req_data = [];

            //$req_data = $request->except(["_token", "page", "back_url", "package_accomodation", "package_accomodation_type", "sort_order", "status"]);
        $req_data["package_id"] = $package_id;
        $req_data["title"] = (!empty($request->title))?$request->title:"";
        $req_data["discount_type"] = (!empty($request->discount_type))?$request->discount_type:1;
        $req_data["service_level"] = (!empty($request->service_level))?$request->service_level:1;
        $req_data["discount"] = (!empty($request->discount))?$request->discount:0;
        $req_data["description"] = (!empty($request->description))?$request->description:"";
        $req_data["booking_price"] = (!empty($request->booking_price))?$request->booking_price:0;
        $req_data["is_partial_amount"] = (isset($request->is_partial_amount))?$request->is_partial_amount:0;
        $req_data["is_book_without_payment"] = (isset($request->is_book_without_payment))?$request->is_book_without_payment:0;
        $req_data["is_default"] = (isset($request->is_default))?$request->is_default:0;
        $req_data["price_validity"] = (!empty($request->period))?$request->period:'default';
        $req_data["sort_order"] = (!empty($request->sort_order))?$request->sort_order:0;
        $req_data["sub_total_amount"] = (!empty($request->sub_total_amount))?$request->sub_total_amount:0;
        $req_data["final_amount"] = (is_numeric($request->final_amount))?$request->final_amount:0;
        $req_data["status"] = (!empty($request->status))?$request->status:0;
            //prd($req_data);
            //prd($request->all());

        $pkgPriceCount = PackagePriceInfo::where("package_id",$package_id)->count();
        if($pkgPriceCount == 0){
            $req_data["is_default"] = 1;
        }

        if($req_data["is_default"] == 1){
            PackagePriceInfo::where("package_id",$package_id)->update(array('is_default'=>0));

        }
        $requestArr = $request->all();
        # prd($requestArr);
        $priceCategoryAmount = [];
        $priceCategoryDefault = [];
        if(!$packageCategoryElements->isEmpty()){
            foreach ($packageCategoryElements as $packageCategoryElement) {
                $choices = [];
                if($packageCategoryElement->input_type == "select" && $packageCategoryElement->input_choices){
                    $choices = json_decode($packageCategoryElement->input_choices,true);
                }
                if(!empty($choices)){
                    for ($i=0; $i < count($choices); $i++) {
                        $priceCategoryAmount['pce'.$packageCategoryElement->id][$choices[$i]] = $requestArr['pce'.$packageCategoryElement->id.'_'.$choices[$i]];
                        $priceCategoryDefault['pce'.$packageCategoryElement->id]['default'] = $requestArr['pce'.$packageCategoryElement->id.'_default'] ?? null;
                    }
                }
            }
        }

        $req_data["category_choices_record"] = json_encode($priceCategoryAmount);
        $req_data["show_choices_record"] = json_encode($priceCategoryDefault);

        # prd($priceCategoryDefault);
        if (!empty($package_price_info) && $package_price_info->count() > 0) {
            $isSaved = PackagePriceInfo::where("id",$package_price_info->id)->update($req_data);
            $package_price_info_id = $package_price_info->id;

                    // delete package accommodations before update
            DB::table('package_accommodations')->where('package_price_id', $package_price_info->id)->delete();

            $packageAccomArr = array();
            if(!empty($request->package_accomodation)){
                $pkdAccom = $request->package_accomodation;
                $pkdAccomType = $request->package_accomodation_type;
                $pkdAccomItenary = $request->accommodation_itenary;
                for($i=0;$i<count($pkdAccom);$i++){
                    $packageAccomArr[] = array(
                        'package_id' => $package_id,
                        'package_price_id' => $id,
                        'itenary_id' => $pkdAccomItenary[$i],
                        'accommodation_id' => (!empty($pkdAccom[$i])) ? $pkdAccom[$i] : null,
                        'accommodation_type_id' => (!empty($pkdAccomType[$i])) ? $pkdAccomType[$i] : null,
                    );
                }
            }
            //prd($packageAccomArr);
            DB::table('package_accommodations')->insert($packageAccomArr);
            $msg = $module." Price Info has been updated successfully.";
        } else {
            $isSaved = PackagePriceInfo::create($req_data);
            $package_price_info_id = $isSaved->id;

            if(!empty($package_price_info)){
                $packageAccomArr = array();
                if(!empty($request->package_accomodation)){
                    $pkdAccom = $request->package_accomodation;
                    $pkdAccomType = $request->package_accomodation_type;
                    $pkdAccomItenary = $request->accommodation_itenary;
                    for($i=0;$i<count($pkdAccom);$i++){
                        $packageAccomArr[] = array(
                            'package_id' => $package_id,
                            'package_price_id' => $package_price_info_id,
                            'itenary_id' => $pkdAccomItenary[$i],
                            'accommodation_id' => (!empty($pkdAccom[$i])) ? $pkdAccom[$i] : null,
                            'accommodation_type_id' => (!empty($pkdAccomType[$i])) ? $pkdAccomType[$i] : null,
                        );
                    }
                }

                DB::table('package_accommodations')->insert($packageAccomArr);

            }

            $msg = $module." Price Info has been added successfully.";
        }

        if ($isSaved) {

                //===================Activity Logs==============================

            $new_data = DB::table('package_price_info')->where(['id'=>$package_price_info_id])->first();
            $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $submodule_id = $package_price_info_id;
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package_list = Package::where(['id'=>$module_id])->first();
            $module_desc = !empty($package_list->title)?$package_list->title:'';

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Package';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "Price Info";
            $params['sub_module_desc'] = $sub_module_desc;
            $params['sub_module_id'] = $submodule_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($package_price_info->id)) ? "Update" : "Add";

            CustomHelper::RecordActivityLog($params);

                //===================Activity Logs==============================

            cache()->forget("package_price_info");
            return redirect(route($this->ADMIN_ROUTE_NAME .".packages.price_info",$package_id))->with("alert-success", $msg);
        } else {
            return back()->with(
                "alert-danger",
                "The package Price info could be added, please try again or contact the administrator."
            );
        }
    }

    $package_itenaries = Itenary::where('package_id',$package_id)->where('status',1)->orderBy('sort_order','ASC')->get();
    $accommodations = Accommodation::where('status',1)->orderBy('name','ASC')->get();

    $data = [];
    $data["page_heading"] = $name;
    $data["package"] = $package_query;
    $data["package_id"] = $package_id;
    $data["package"] = $packageDetails;
    $data["package_price_info"] = $package_price_info;
    $data["package_itenaries"] = $package_itenaries;
    $data["accommodations"] = $accommodations;
    $data["packageAccommodations"] = $packageAccommodations;
    $data["id"] = $id;
    return view("admin.packages.package_price_add", $data);
}

public function price_info_delete(Request $request)
{
    $package_id = isset($request->package_id) ? $request->package_id:"";
    $id = isset($request->info_id) ? $request->info_id:"";
    $method = $request->method();
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {

            $new_data = DB::table('package_price_info')->where(['id'=>$id])->first();
            $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package_list = Package::where(['id'=>$module_id])->first();
            $module_desc = !empty($package_list->title)?$package_list->title:'';

            $is_delete = PackagePriceInfo::packagepriceInfoDelete($id);
            if ($is_delete['status'] != 'ok') {

                return redirect(route(
                    $this->ADMIN_ROUTE_NAME .
                    ".packages.price_info",
                    $package_id
                ))->with('alert-danger', $is_delete['message']);

            }
            else {

                $delete_category_name = $is_delete['name'];

                $is_deleted = true;

            }
            $package_price_info = PackagePriceInfo::find($id);
                //$is_deleted = $package_price_info->delete();
        }
    }

    if ($is_deleted) {

            //===================Activity Logs==============================

        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'Package';
        $params['module_desc'] = $module_desc;
        $params['module_id'] = $module_id;
        $params['sub_module'] = "Price Info";
        $params['sub_module_desc'] = $sub_module_desc;
        $params['sub_module_id'] = $id;
        $params['sub_sub_module'] = "";
        $params['sub_sub_module_id'] = 0;
        $params['data_after_action'] = $new_data;
        $params['action'] = 'Delete';

        CustomHelper::RecordActivityLog($params);

                //===================Activity Logs==============================
        return redirect(
            route(
                $this->ADMIN_ROUTE_NAME . ".packages.price_info",$package_id))->with( "alert-success","Package price info has been deleted successfully."
    );
            } else {
                return redirect(
                    route(
                        $this->ADMIN_ROUTE_NAME . ".packages.price_info",$package_id))->with("alert-danger","Package price info cannot be deleted, please try again or contact the administrator."
            );
                    }
                }

    // Service Level Code Start Here

                public function serviceLevel(Request $request)
                {
                    $data = [];
                    $limit = 50;
                    $name = isset($request->name) ? $request->name : "";
                    $status = isset($request->status) ? $request->status : "";
                    $service_query = ServiceLevel::orderBy("id", "asc");

                    if (!empty($name)) {
                        $service_query->where(
                            "name",
                            "like",
                            "%" . $name . "%"
                        );
                    }
                    if (strlen($status) > 0) {
                        $service_query->where("status", $status);
                    }
                    $packages = $service_query->paginate($limit);
                    //$packages = $service_query->toSql();
                    //prd($packages);

                    $data["services"] = $packages;
                    return view("admin.service_levels.index", $data);
                }

                public function service_add(Request $request)
                {
                    $id = isset($request->id) ? $request->id : 0;
                    $user_id = auth()->user()->id;
                    $user_name = auth()->user()->name;
                    $service_query = [];
                    $title = "Add Service Level Type";

                    if (is_numeric($id) && $id > 0) {
                        $service_query = ServiceLevel::find($id);
                        $title = "Edit Service Level Type(" . $service_query->name . " )";
                    // prd($title);
                    }

                    if ($request->method() == "POST" || $request->method() == "post") {

                        $rules = [];
                        $validation_msg = [];
                        $rules["name"] = 'required|max:255';

                        $validation_msg["name.required"] = 'The Service Level Type field is required.';

                        $this->validate($request, $rules,$validation_msg);
                        $req_data = [];
                        $req_data = $request->except([
                            "_token",
                            "page",
                            "back_url",
                            "featured",
                        ]);

                        if (!empty($service_query) && $service_query->id == $id) {
                            $isSaved = ServiceLevel::where("id", $service_query->id)->update(
                                $req_data);
                            $service_levels_id = $service_query->id;
                            $msg = "Service Level Type has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "service_levels";
                $row_id = $id;
                $action_type = "Edit On Service Level Type";
                $action_description = "Edit On (" . $request->name . ")";
                $description =
                "Update(" . $request->name . ") with : " . $description;*/
            } else {
                $isSaved = ServiceLevel::create($req_data);
                $service_levels_id = $isSaved->id;
                $msg = "Service Level Type has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "service_levels";
                $row_id = $id;
                $action_type = "Add On Service Level Type";
                $action_description = "Add On (" . $request->name . ")";
                $description =
                "Add(" . $request->name . ") with : " . $description;*/
            }

            if ($isSaved) {
                cache()->forget("service_levels");

               /* CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
           );*/

                //=============Activity Logs=====================

           $new_data = DB::table('service_levels')->where('id',$service_levels_id)->first();
           $module_desc =  !empty($new_data->name)?$new_data->name:'';
           $new_data =(array) $new_data;
           $new_data = json_encode($new_data);

           $module_id = $service_levels_id;

           $params['user_id'] = $user_id;
           $params['user_name'] = $user_name;
           $params['module'] = 'Service Level Type';
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


           return redirect(url($this->ADMIN_ROUTE_NAME . "/packages/services")
       )->with("alert-success", $msg);
       } else {
        return back()->with(
            "alert-danger",
            "The Service Level Type could be added, please try again or contact the administrator."
        );
    }
}

$data = [];
$data["page_heading"] = $title;
$data["service_query"] = $service_query;
$data["id"] = $id;

return view("admin.service_levels.form", $data);
}

public function service_delete(Request $request)
{
    $id = isset($request->id)?$request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $method = $request->method();
    $is_deleted = 0;


    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {

            $service_query = ServiceLevel::find($id);

               /* $function_name = $this->currentUrl;
                $action_table = "service_levels";
                $row_id = $id;
                $action_type = "Delete Service Level Type";
                $action_description = "Delete (" . $service_query->name . ")";
                $description = "Delete (" . $service_query->name . ")";*/
                $new_data = DB::table('service_levels')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $is_deleted = $service_query->delete();
            }
        }

        if ($is_deleted) {
            /*CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
        );*/

            //=============Activity Logs=====================

        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'Service Level Type';
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


        return redirect(url($this->ADMIN_ROUTE_NAME . "/packages/services"))->with("alert-success", "Service Level Type has been deleted successfully.");
    } else {

        return redirect(url($this->ADMIN_ROUTE_NAME . "/packages/services"))->with("alert-danger","Service Level Type cannot be deleted, please try again or contact the administrator.");
    }
}

public function service_view(Request $request)
{
    $id = isset($request->id) ? $request->id : 0;
    $service_query = "";
    $title = "Service Level Type";

    if (is_numeric($id) && $id > 0) {
        $service_query = ServiceLevel::where("id", $id)->first();
            //prd($destination_type);
        $title = "Service Level Type";
    }

    $data = [];
    $data["page_heading"] = $title;
    $data["service_query"] = $service_query;
    $data["id"] = $id;
    return view("admin.service_levels.view", $data);
}

public function serviceStatus(Request $request)
{

    $service_query = ServiceLevel::find($request->id);
    $service_query->status = $request->status;
    $service_query->save();

    return response()->json([
        "success" => "Service Level Type status change successfully.",
    ]);
}
    // Service Level Closed Start Here

 // Package Accommodations
public function accommodation(Request $request)
{
    $package_query = [];
    $package_id = $request->package_id ?? null;
    $package_query = Package::find($package_id);
    $packageDetails = Package::find($package_id);

    if (isset($package_id) && !empty($package_id) && is_numeric($package_id)) {
        $data = [];
        $limit = $this->limit;
        $package_price_info = DB::table('package_accommodations as P')->leftjoin('service_levels as S','S.id','=','P.service_level' )->selectRaw('P.*,S.name as name')->where(["package_id"=>$package_id])->whereNull('package_price_id')->orderBy("name", "ASC");

        $package_prices = $package_price_info->paginate($limit);
        $data["package_id"] = $package_id;
        $data["package_prices"] = $package_prices;
        $data["heading"] = 'Package Accommodation ('.$packageDetails->title.')';
        $data["limit"] = $limit;
        $data["package"] = $package_query;

        return view("admin.packages.accommodation", $data);
    }
    abort(404);
}

public function accommodation_add(Request $request)
{
    $package_query = [];
    $package_id = isset($request->package_id) ? $request->package_id : null;
    $package_query = Package::find($package_id);
    $accommodation_id = isset($request->accommodation_id) ? $request->accommodation_id : null;

    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $packageDetails = Package::find($package_id);
    $id = isset($request->info_id) ? $request->info_id : null;
    $service_level = isset($request->service_level) ? $request->service_level : null;

    $is_default_without_price = isset($request->is_default_without_price) ? $request->is_default_without_price : null;


    $package_price_info = [];
    $packageAccommodations = "";

       prd($request->toArray());
    $name = "Add Package Accomodation (".$packageDetails->title.")";
    if (is_numeric($id) && $id > 0) {
        // $name = "Edit Package Accomodation (" . $package_price_info->title . " )";
        $name = "Edit Package Accomodation (" . $packageDetails->title . " )";
    }
    $isSaved=0;
    if ($request->method() == "POST" || $request->method() == "post") {
        $req_data = [];

        $rules = [];
        $validation_msg = [];
        $rules['service_level'] = 'required';

        $validation_msg['service_level.required'] = 'Please select Service Level.';

        $this->validate($request, $rules,$validation_msg);

        DB::table('package_accommodations')->where(['package_price_id'=>0,'service_level'=>$service_level])->delete();

        if($is_default_without_price==1){
            DB::table('package_accommodations')->where(['package_price_id'=>0])->update(['is_default_without_price'=>0]);
        }

        $requestArr = $request->all();
        $id = $request->id ?? null;

          #  prd($request->package_accomodation);


        $packageAccomArr = array();
        if(!empty($request->package_accomodation)){


            $pkdAccom = $request->package_accomodation;
            $pkdAccomType = $request->package_accomodation_type;
            $pkdAccomItenary = $request->accommodation_itenary;


            for($i=0;$i<count($pkdAccom);$i++){
                $packageAccomArr[] = array(
                    'package_id' => $package_id,
                    'package_price_id' => $id,
                    'service_level'=>$service_level,
                    'is_default_without_price'=>$is_default_without_price,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'itenary_id' => $pkdAccomItenary[$i],
                    'accommodation_id' => (!empty($pkdAccom[$i])) ? $pkdAccom[$i] : null,
                    'accommodation_type_id' => (!empty($pkdAccomType[$i])) ? $pkdAccomType[$i] : null,
                );
            }
        }

                   # prd($packageAccomArr);
        $isSaved=DB::table('package_accommodations')->insert($packageAccomArr);



        $msg ="Package Accommodations has been added successfully.";


        if ($isSaved) {

            //===================Activity Logs==============================

               $new_data = DB::table('packages')->where(['id'=>$package_id])->first();
                $module_desc = !empty($new_data->title)?$new_data->title:'';

                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);


                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Package';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $package_id;
                $params['sub_module'] = "Package Accommodation";
                $params['sub_module_desc'] = '';
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($package_id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                //===================Activity Logs==============================

            cache()->forget("accommodation");
            return redirect(
                route(
                    $this->ADMIN_ROUTE_NAME .
                    ".packages.accommodation",
                    $package_id
                )
            )->with("alert-success", $msg);
        } else {
            return back()->with(
                "alert-danger",
                "The Accommodations info could be added, please try again or contact the administrator."
            );
        }
    }

    $package_itenaries = Itenary::where('package_id',$package_id)->where('status',1)->orderBy('sort_order','ASC')->get();

    $accommodations = DB::table('accommodations as a')->leftjoin('destinations as d','d.id','=','a.destination_id')->selectRaw('d.name,a.name,a.id')->where('a.status',1)->orderBy('a.name','ASC')->get();
        //prd($accommodations);

    $packageAccommodations = DB::table('package_accommodations')->whereNull('package_price_id')->where(['service_level'=>$service_level,'package_id'=>$package_id])->get();

    $service_level_selected=$is_default_without_price_selected='';
    if(!empty($packageAccommodations) && isset($packageAccommodations[0]->service_level)){
        $service_level_selected=$packageAccommodations[0]->service_level;
        $is_default_without_price_selected=$packageAccommodations[0]->is_default_without_price??'';

    }

    $data = [];
    $data["page_heading"] = $name;
    $data["package"] = $package_query;
    $data["package"] = $packageDetails;
    $data["package_price_info"] = $package_price_info;
    $data["package_itenaries"] = $package_itenaries;
    $data["accommodations"] = $accommodations;
    $data["service_level"] = $service_level_selected;
    $data["is_default_without_price"] = $is_default_without_price_selected;
    $data["packageAccommodations"] = $packageAccommodations;
    $data["package_id"] = $package_id;
    $data["id"] = $id;
        //($data);
    return view("admin.packages.accomodation_add", $data);
}



public function accommodation_delete(Request $request)
{
    $package_id = $request->package_id;
    $service_level = isset($request->service_level)?$request->service_level:0;
    $method = $request->method();
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($service_level) && $service_level > 0) {
            $is_deleted = $result=DB::table('package_accommodations')->where(['package_price_id'=>0,'service_level'=>$service_level,'package_id'=>$package_id])->delete();;
        }
    }

    if ($is_deleted) {
        return redirect(
            route(
                $this->ADMIN_ROUTE_NAME . ".packages.accommodation",
                $package_id
            )
        )->with(
            "alert-success",
            "Package Accommodation has been deleted successfully."
        );
    } else {
        return redirect(
            route(
                $this->ADMIN_ROUTE_NAME . ".packages.accommodation",
                $package_id
            )
        )->with(
            "alert-danger",
            "Package Accommodation cannot be deleted, please try again or contact the administrator."
        );
    }
}


// ============Add SEO Meta=============

 public function seoMeta(Request $request)
    {
    $package_query = [];
    $segment2 = $request->segment(2);
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $id = isset($request->package_id) ? $request->package_id : 0;
    $package_id = isset($request->package_id) ? $request->package_id : 0;
    //$package_query = Package::find($id);
    $packageDetails = Package::find($id);
    // $id = isset($request->id) ? $request->id : 0;
    $packageSeo = [];
    $packageTitle = isset($packageDetails->title) ? $packageDetails->title:'';
    $name = "SEO Meta (".$packageTitle.")";

    $module = "Package";
    if($segment2 == 'activity'){
       $module = "Activity";
    }

    if ($request->method() == "POST" || $request->method() == "post") {

        $req_data = [];
        $packageSeo = Package::find($id);
        //$req_data = $request->except(["_token", 'id', "page", "back_url",'created_at','updated_at']);
        $req_data['meta_title'] = isset($request->meta_title) ? $request->meta_title : "";
        $req_data['meta_description'] = isset($request->meta_description) ? $request->meta_description : "";

        if (!empty($packageSeo) && $packageSeo->count() > 0) {
            $isSaved = Package::where("id",$packageSeo->id)->update($req_data);
            $package_seo_id = $packageSeo->id;
            $msg = $module." Seo Meta has been updated successfully.";
        } else {
            $isSaved = Package::create($req_data);
            $package_seo_id = $isSaved->id;
            $msg = $module." Seo Meta has been added successfully.";
        }

        if ($isSaved) {

                //===================Activity Logs==============================

            $new_data = DB::table('packages')->where(['id'=>$id])->first();
            $module_id = !empty($new_data->id)?$new_data->id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $submodule_id = $package_seo_id;
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package_list = Package::where(['id'=>$module_id])->first();
            $module_desc = !empty($package_list->title)?$package_list->title:'';

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = $module;
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = $module." SEO";
            $params['sub_module_desc'] = $sub_module_desc;
            $params['sub_module_id'] = $submodule_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($packageSeo->id)) ? "Update" : "Add";

            CustomHelper::RecordActivityLog($params);

                //===================Activity Logs==============================

            cache()->forget("packages");

            //return redirect(route($this->ADMIN_ROUTE_NAME .".packages.seo_meta",$id))->with("alert-success", $msg);
            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$segment2."/".$id."/seo_meta"))->with("alert-success", $msg);
        } else {
            return back()->with(
                "alert-danger",
                "The package Seo Meta could be added, please try again or contact the administrator."
            );
        }
    }
    $data = [];
    $data["package"] = $packageDetails;
    $data["segment"] = $segment2;
    $data["package_id"] = $package_id;
    $data["page_heading"] = $name;
    $data["packageSeo"] = $packageSeo;
    //$data["package"] = $package_query;
    $data["id"] = $id;
    return view("admin.packages.seo_meta", $data);
}

public function seoView(Request $request)
{
    $package_query = [];
    $segment2 = $request->segment(2);
     $id = isset($request->package_id) ? $request->package_id : 0;
    $package_id = isset($request->package_id) ? $request->package_id : 0;
    //$package_query = Package::find($id);
    $packageDetails = Package::find($id);
    $packageSeoView = "";
    $packageTitle = isset($packageDetails->title) ? $packageDetails->title:'';
    $title = "SEO Meta (".$packageTitle.")";

    if (is_numeric($id) && $id > 0) {
        $packageSeoView = Package::where("id", $id)->first();
    }

    $data = [];
    $data["page_heading"] = $title;
    $data["package"] = $packageDetails;
    $data["segment"] = $segment2;
    $data["packageData"] = $packageSeoView;
    //$data["package"] = $package_query;
    $data["id"] = $id;
    $data["package_id"] = $package_id;
    return view("admin.packages.seo_view", $data);
}

    public function flight(Request $request) {
        $package_query = [];
        $segment2 = $request->segment(2);
        $package_id = $request->package_id;
        $package = Package::find($package_id);
        if (!empty($package)) {
            $data = [];
            $limit = $this->limit;
            $data["package_id"] = $package_id;
            $data["package"] = $package;
            $data["segment"] = $segment2;
            $data["heading"] = 'Flights ('.$package->title.')';
            return view("admin.packages.flights_index", $data);
        }
        abort(404);
    }

    public function flight_add(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $nicknames = [];
        $nicknames['flight_number'] = 'Flight Number';
        $nicknames['flight_time'] = 'Flight Time';
        $nicknames['airline_name'] = 'Airline Name';
        $nicknames['flight_departure'] = 'Origin';
        $nicknames['flight_arrival'] = 'Destination';
        $nicknames['flight_comment'] = 'Flight Comment';
        $rules = [];
        $rules['flight_number'] = 'required|max:255';
        $rules['flight_time'] = 'nullable';
        $rules['airline_name'] = 'required';
        $rules['flight_departure'] = 'required';
        $rules['flight_arrival'] = 'required';
        $rules['flight_comment'] = 'nullable';
        $validation_msg['required'] = ':attribute is required.';
        $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400); // 400 being the HTTP code for an invalid request.
        } else {
            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;

            $package_id = $request->package_id;
            $data_id = $request->data_id;
            $package = Package::find($package_id);
            $req_data = [];
            $req_data['flight_number'] = $request->flight_number??'';
            $req_data['flight_time'] = $request->flight_time??'';
            $req_data['airline_name'] = $request->airline_name??'';
            $req_data['flight_departure'] = $request->flight_departure??'';
            $req_data['flight_arrival'] = $request->flight_arrival??'';
            $req_data['flight_comment'] = $request->flight_comment??'';
            if(!empty($data_id)) {
                $isSaved = PackageFlights::where("id",$data_id)->update($req_data);
                $package_flight_id = $data_id;
                $msg = "Package Flight has been updated successfully.";
            } else {
                $req_data['package_id'] = $package_id;
                $isSaved = PackageFlights::create($req_data);
                $package_flight_id = $isSaved->id;
                $msg = "Package Flight has been added successfully.";
            }

            if ($isSaved) {
                $new_data = DB::table('package_flights')->where(['id'=>$package_flight_id])->first();
                $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
                $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
                $submodule_id = $package_flight_id;
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $package_list = Package::where(['id'=>$module_id])->first();
                $module_desc = !empty($package_list->title)?$package_list->title:'';

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Package';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Package Flights";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $submodule_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($data_id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                cache()->forget("package_flights");

                $response['success'] = true;
                $response['msg'] = $msg;
            } else {
                $response['msg'] = "The package flight could be added, please try again or contact the administrator.";
            }
        }
        return response()->json($response);
    }

    public function flight_list(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->package_id) {
            $data = [];
            $package_id = $request->package_id;
            $data['package'] = Package::find($package_id);
            $data['records'] = PackageFlights::where('package_id',$package_id)->get();
            $htmlData = view("admin.packages._packageflight_loaddata", $data)->render();
            $response['htmlData'] = $htmlData;
            $response['success'] = true;
        }
        return response()->json($response);
    }

    public function flight_get(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->package_id) {
            $data = [];
            $package_id = $request->package_id;
            $package = Package::find($package_id);
            $data['package'] = $package;
            if($request->data_id) {
                $package_flight_id = $request->data_id;
                $data['record'] = PackageFlights::find($package_flight_id);
            }
            $data['airlines'] = PackageAirline::where('status',1)->get();
            $htmlData = view("admin.packages._packageflight_form", $data)->render();
            $response['htmlData'] = $htmlData;
            $response['success'] = true;
        }
        return response()->json($response);
    }

    public function flight_delete(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';

        if ($request->package_id && $request->data_id) {
            $data_id = $request->data_id;
            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $is_deleted = 0;

            $new_data = DB::table('package_flights')->where(['id'=>$data_id])->first();
            $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package = Package::find($module_id);
            $module_desc = $package->title??'';

            $is_deleted = PackageFlights::where('id',$data_id)->delete();
            if ($is_deleted) {
                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Package';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Package Flight";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $data_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = 'Delete';
                CustomHelper::RecordActivityLog($params);

                $response['success'] = true;
                $response['message'] = "Package Flight info has been deleted successfully.";
            } else {
                //SINCE VARIABLE $response['message'] ALREADY DEFINED EMPTY ABOVE
                // if(empty($response['message'])) {
                    $response['message'] = "Package Flight info cannot be deleted, please try again or contact the administrator.";
                // }
            }
        }
        return response()->json($response);
    }


    public function packageprice(Request $request) {
        $package_query = [];
        $segment2 = $request->segment(2);
        $package_id = $request->package_id;
        $package = Package::find($package_id);
        if (!empty($package)) {
            $data = [];
            $limit = $this->limit;
            $title = isset($request->title) ? $request->title : "";
            $status = isset($request->status) ? $request->status : "";
            $package_price_info = PackagePriceInfo::where("package_id",$package_id)->orderBy("title", "ASC");
            if (!empty($title)) {
                $package_price_info->where("title", "like", "%" . $title . "%");
            }
            if (strlen($status) > 0) {
                $package_price_info->where("status", $status);
            }
            $package_prices = $package_price_info->paginate($limit);
            $data["package_id"] = $package_id;
            $data["package_prices"] = $package_prices;
            $data["heading"] = 'Price Info ('.$package->title.')';
            $data["limit"] = $limit;
            $data["segment"] = $segment2;
            $data["package"] = $package;
            $data['itinerary_data'] = Itenary::where('package_id',$package_id)->get();
            $data['accommodations'] = Accommodation::where('destination_id',$package->destination_id)->get();
            // prd($data['hotels']->toArray());
            return view("admin.packages.packageprice_index", $data);
        }
        abort(404);
    }

    public function packageprice_add(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $nicknames = [];
        $nicknames['title'] = 'Price Title';
        $nicknames['status'] = 'Status';
        $rules = [];
        $rules['title'] = 'required|max:255';
        if($request->is_partial_amount == 1){
            $rules['booking_price'] = 'required|numeric|gt:0';
        }
        if($request->is_partial_amount_b2b == 1){
            $rules['booking_price_b2b'] = 'required|numeric|gt:0';
        }
        $rules['status'] = 'required';
        $validation_msg['required'] = ':attribute is required.';
        $validation_msg['required'] = 'Booking price is required.';
        $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400); // 400 being the HTTP code for an invalid request.
        } else {

            // prd($request->toArray());
            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $package_id = $request->package_id;
            $data_id = $request->data_id;
            $package = Package::find($package_id);

            $module = "Package";
            if($package->is_activity == 1){
               $module = "Activity";
            }
            $req_data = [];
            // $req_data["package_id"] = $package_id;
            $req_data["title"] = $request->title??'';
            // $req_data["discount_type"] = (!empty($request->discount_type))?$request->discount_type:1;
            // $req_data["service_level"] = (!empty($request->service_level))?$request->service_level:1;
            // $req_data["discount"] = (!empty($request->discount))?$request->discount:0;
            $req_data["description"] = $request->description??'';
            $req_data["is_partial_amount"] = $request->is_partial_amount??0;
            if($request->is_partial_amount) {
                $req_data["booking_price"] = $request->booking_price??0;    
            } else {
                $req_data["booking_price"] = 0;
            }
            $req_data["is_book_without_payment"] = $request->is_book_without_payment??0;

            $req_data["is_partial_amount_b2b"] = $request->is_partial_amount_b2b??0;
            if($request->is_partial_amount_b2b) {
                $req_data["booking_price_b2b"] = $request->booking_price_b2b??0;
            } else {
                $req_data["booking_price_b2b"] = 0;
            }
            $req_data["is_book_without_payment_b2b"] = $request->is_book_without_payment_b2b??0;
            $req_data["is_default"] = $request->is_default??0;
            $req_data["price_validity"] = $request->price_validity??'default';
            // $req_data["sort_order"] = $request->sort_order??0;
            
            // $req_data["sub_total_amount"] = (!empty($request->sub_total_amount))?$request->sub_total_amount:0;
            // $req_data["final_amount"] = (is_numeric($request->final_amount))?$request->final_amount:0;
            $req_data["sub_total_amount"] = $req_data["booking_price"];
            $req_data["final_amount"] = $req_data["booking_price"];
            $req_data["status"] = $request->status??0;

            $pkgPriceCount = PackagePriceInfo::where("package_id",$package_id)->count();
            if($pkgPriceCount == 0) {
                $req_data["is_default"] = 1;
            }

            if($req_data["is_default"] == 1) {
                PackagePriceInfo::where("package_id",$package_id)->update(array('is_default'=>0));
            }
            // prd($req_data);

            $requestArr = $request->all();
            $priceCategoryAmount = [];
            $priceCategoryDefault = [];
            $packageCategoryElements = $package->packagePriceCategory->priceCategoryElements??[];
            if(!$packageCategoryElements->isEmpty()){
                foreach ($packageCategoryElements as $packageCategoryElement) {
                    $choices = [];
                    if($packageCategoryElement->input_type == "select" && $packageCategoryElement->input_choices){
                        $choices = json_decode($packageCategoryElement->input_choices,true);
                    }
                    if(!empty($choices)){
                        for ($i=0; $i < count($choices); $i++) {
                            $priceCategoryAmount['pce'.$packageCategoryElement->id][$choices[$i]] = $requestArr['pce'.$packageCategoryElement->id.'_'.$choices[$i]];
                            $priceCategoryDefault['pce'.$packageCategoryElement->id]['default'] = $requestArr['pce'.$packageCategoryElement->id.'_default'] ?? null;
                        }
                    } else {
                        $priceCategoryDefault['pce'.$packageCategoryElement->id]['default'] = $requestArr['pce'.$packageCategoryElement->id.'_default'] ?? null;
                    }
                }
            }
            $req_data["category_choices_record"] = json_encode($priceCategoryAmount);
            $req_data["show_choices_record"] = json_encode($priceCategoryDefault);

            # prd($priceCategoryDefault);
            // prd($req_data);
            if(!empty($data_id)) {
                // $package_price_info = PackagePriceInfo::find($data_id);
                $isSaved = PackagePriceInfo::where("id",$data_id)->update($req_data);
                $package_price_info_id = $data_id;

                // delete package accommodations before update
                /*DB::table('package_accommodations')->where('package_price_id', $package_price_info->id)->delete();
                $packageAccomArr = array();
                if(!empty($request->package_accomodation)){
                    $pkdAccom = $request->package_accomodation;
                    $pkdAccomType = $request->package_accomodation_type;
                    $pkdAccomItenary = $request->accommodation_itenary;
                    for($i=0;$i<count($pkdAccom);$i++){
                        $packageAccomArr[] = array(
                            'package_id' => $package_id,
                            'package_price_id' => $id,
                            'itenary_id' => $pkdAccomItenary[$i],
                            'accommodation_id' => (!empty($pkdAccom[$i])) ? $pkdAccom[$i] : null,
                            'accommodation_type_id' => (!empty($pkdAccomType[$i])) ? $pkdAccomType[$i] : null,
                        );
                    }
                }
                //prd($packageAccomArr);
                DB::table('package_accommodations')->insert($packageAccomArr);*/

                $msg = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$module.' Price Info has been updated successfully.</div>';
            } else {
                $req_data['package_id'] = $package_id;
                $isSaved = PackagePriceInfo::create($req_data);
                $package_price_info_id = $isSaved->id;

                /*if(!empty($package_price_info)){
                    $packageAccomArr = array();
                    if(!empty($request->package_accomodation)){
                        $pkdAccom = $request->package_accomodation;
                        $pkdAccomType = $request->package_accomodation_type;
                        $pkdAccomItenary = $request->accommodation_itenary;
                        for($i=0;$i<count($pkdAccom);$i++){
                            $packageAccomArr[] = array(
                                'package_id' => $package_id,
                                'package_price_id' => $package_price_info_id,
                                'itenary_id' => $pkdAccomItenary[$i],
                                'accommodation_id' => (!empty($pkdAccom[$i])) ? $pkdAccom[$i] : null,
                                'accommodation_type_id' => (!empty($pkdAccomType[$i])) ? $pkdAccomType[$i] : null,
                            );
                        }
                    }
                    DB::table('package_accommodations')->insert($packageAccomArr);
                }*/

                $msg = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$module.' Price Info has been added successfully.</div>';
            }

            if ($isSaved) {
                if(!empty($data_id)) {
                    DB::table('package_accommodations')->where('package_price_id', $package_price_info_id)->delete();
                }
                $packageAccomArr = array();
                if(!empty($request->package_accomodation)){
                    $pkdAccom = $request->package_accomodation;
                    $pkdAccomItenary = $request->accommodation_itenary;
                    if(!empty($pkdAccomItenary) && is_array($pkdAccomItenary)){
                        foreach($pkdAccomItenary as $key => $itenary){
                            $accommodation_itenary =$request->package_accomodation;
                            if(isset($accommodation_itenary[$key]) && $itenary) {
                                $itenary_data = json_encode($itenary);
                                $packageAccomArr[] = array(
                                    'package_id' => $package_id,
                                    'package_price_id' => $package_price_info_id,
                                    'itenary_data' => $itenary_data,
                                    'accommodation_data' => isset($accommodation_itenary[$key]) ? json_encode($accommodation_itenary[$key]) : '',
                                    'accommodation_type_id' => null,
                                );
                            }
                        }
                    }
                }
                if(!empty($packageAccomArr)) {
                    DB::table('package_accommodations')->insert($packageAccomArr);
                }

                CustomHelper::updatePackagePublishPrice($package_id);

                $new_data = DB::table('package_price_info')->where(['id'=>$package_price_info_id])->first();
                $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
                $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
                $submodule_id = $package_price_info_id;
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $package_list = Package::where(['id'=>$module_id])->first();
                $module_desc = !empty($package_list->title)?$package_list->title:'';

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = $module;
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Price Info";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $submodule_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($data_id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);
                //===================Activity Logs==============================

                cache()->forget("package_price_info");

                $response['success'] = true;
                $response['msg'] = $msg;
            } else {
                $response['msg'] = "The package Price info could be added, please try again or contact the administrator.";
            }
        }
        return response()->json($response);
    }

    public function ajax_save_booking_mode(Request $request) {
        $data = [];
        $response['msg'] = '';
        $package_id = isset($request->package_id) ? $request->package_id : "";

        $segment2 = $request->segment(2);
        $is_activity = $request->is_activity ?? 0;
       
        $module = "Package";
        if($segment2 == 'activity'){
           $is_activity = 1;
           $module = "Activity";
        }
        //prd($package_id);
        $booking_mode = isset($request->booking_mode) ? $request->booking_mode : null;
        if(!empty($package_id)) {
            $package = Package::find($package_id);
            $package->booking_mode = $booking_mode;
            $is_updated = $package->save();

            if($is_updated){

                 
                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;

                $new_data = DB::table('packages')->where('id',$package_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $package_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = $module;
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

                $response['msg'] = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Booking mode has been saved successfully.</div>';
            }
            return response()->json($response);
        }
        abort(404);
    }

    public function packageprice_list(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $segment2 = $request->segment(2);
        if($request->package_id) {
            $package_id = $request->package_id;
            $package = Package::find($package_id);
            // prd($request->toArray());
            $data = [];
            $data["booking_mode"] = $package->booking_mode??'';
            $data['package_id'] = $package_id;
            $data['package'] = $package;
            $data['segment'] = $segment2;
            $data['records'] = PackagePriceInfo::where('package_id',$package_id)->orderBy("sort_order", "asc")->get();
            $data['ininerary'] = Itenary::where('package_id',$package_id)->orderBy("day", "asc")->get();
            $data['package_accommodation_data'] = PackageAccommodation::where('package_id',$package_id)->get();

            // prd($data['package_accommodations']);

            $htmlData = view("admin.packages._packageprice_loaddata", $data)->render();
            $response['htmlData'] = $htmlData;
            $response['success'] = true;
        }
        return response()->json($response);
    }

  public function packageprice_slot(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->package_id) {

           $package_id =$request->package_id;
           $price_id =$request->price_id;

           $method = $request->method();
           if($method == "POST"){

            $action = 'Added';
            $isNewSaved = 0;

            // prd($request->toArray());

            $duration = $request->duration ?? '';
            $duration_type = $request->duration_type ?? '';
            $start_time_arr = $request->start_time ?? '';
            $slot_arr = $request->slot_ids ?? '';
            $req_data =[];
            // $is_delete = PackageSlot::where('package_id',$package_id)->where('price_id',$price_id)->delete();


            $updateData = array(
                'status' => 0,
            );
            $isSlotUpdate = PackageSlot::where('package_id',$package_id)->where('price_id',$price_id)->update($updateData);

            $isdelete =  PackageInventory::where('package_id',$package_id)->where('price_id',$price_id)->whereNull('slot_id')->delete();


            if($duration_type == 'hour'){

                foreach ($start_time_arr as $slot_id => $start_time) {
                        if($start_time){
                            $req_data = array(

                                'package_id' => $request->package_id,
                                'price_id' => $request->price_id,
                                'duration' => $duration,
                                'duration_type' => $duration_type,
                                'start_time' => $start_time,
                                'status' => 1,
                            );
                            if(($slot_id != 0 ) && in_array($slot_id, $slot_arr)){
                               $isSaved = PackageSlot::where('id',$slot_id)->update($req_data);
                               $action = 'Update';
                               $response['msg'] = "Package Slot Updated";
                           }else{
                               $isSaved = PackageSlot::insert($req_data);
                               $action = 'Added';
                               $response['msg'] = "Package Slot added";
                        }
                        if($isSaved){
                            $isNewSaved = 1;
                        }
                    }
                }

            }elseif($duration_type == 'minute'){

                foreach ($start_time_arr as $slot_id => $start_time) {
                        if($start_time){
                            $req_data = array(

                                'package_id' => $request->package_id,
                                'price_id' => $request->price_id,
                                'duration' => $duration,
                                'duration_type' => $duration_type,
                                'start_time' => $start_time,
                                'status' => 1,
                            );
                            if(($slot_id != 0 ) && in_array($slot_id, $slot_arr)){
                               $isSaved = PackageSlot::where('id',$slot_id)->update($req_data);
                               $action = 'Update';
                               $response['msg'] = "Package Slot Updated";
                           }else{
                               $isSaved = PackageSlot::insert($req_data);
                               $action = 'Added';
                               $response['msg'] = "Package Slot added";
                        }
                        if($isSaved){
                            $isNewSaved = 1;
                        }
                    }
                }
            }else{


                $is_exist = PackageSlot::where('package_id',$package_id)->where('price_id',$price_id)->where('duration_type','day')->first();

                $req_data = array(

                    'package_id' => $request->package_id,
                    'price_id' => $request->price_id,
                    'duration' => $duration,
                    'duration_type' => $duration_type,
                    'status' => 1,
                    // 'start_time' => $start_time,
                );

                if($is_exist){
                    $isSaved = PackageSlot::where('package_id',$package_id)->where('price_id',$price_id)->where('duration_type','day')->update($req_data);
                }else{
                    $isSaved = PackageSlot::insert($req_data);
                }
                
                if($isSaved){
                  $isNewSaved = 1;
                  $response['msg'] = "Package Slot added";
                }
            }
            if($isNewSaved){

                $response['success'] = true;
               
                //=====Activity

                    $new_data = $start_time_arr;
                    $module_id = $package_id;
                    
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $sub_module = DB::table('package_slots')->where(['id'=>$price_id])->first();
                    $sub_module_desc = !empty($sub_module->start_time)?$sub_module->start_time:'';

                    $user_id = auth()->user()->id;
                    $user_name = auth()->user()->name;
                    $package_list = Package::where(['id'=>$module_id])->first();
                    $module_desc = !empty($package_list->title)?$package_list->title:'';

                    $params = [];
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Package';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = "Price Slot";
                    $params['sub_module_desc'] = $sub_module_desc;
                    $params['sub_module_id'] = $price_id;
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = $action;
                    CustomHelper::RecordActivityLog($params);

                    $response['success'] = true;
                    $response['message'] = "Package price info has been saved successfully.";
                
                //==============

                $params = [];
                $params['records'] = PackageSlot::where('package_id',$package_id)->where('price_id',$price_id)->where('status',1)->get();
                $htmlData = view("admin.packages._packageprice_slot_hours", $params)->render();
                $response['htmlData'] = $htmlData;

            }else{
                $response['success'] = false;
                $response['msg'] = "Package Slot not added";
            }
            return response()->json($response);
           }
            // prd($request->toArray());
            $data = [];
            $package_id = $request->package_id;
            $data['price_id'] = $price_id;
            $data['package_id'] = $package_id;
            $data['package'] = Package::find($package_id);
            $data['package_price'] = PackagePriceInfo::select('title')->where('id',$price_id)->first();
            $data['records'] = PackageSlot::where('package_id',$package_id)->where('price_id',$price_id)->where('status',1)->get();
            return view("admin.packages._packageprice_slot", $data);
          } 
    }

    public function packageprice_slot_delete(Request $request) {

        $slot_id = $request->slot_id;
        $package_id = $request->package_id;
        $price_id = $request->price_id;

        $is_deleted = 0;
        $response = [];
        $response['success'] = false;
        $response['msg'] = "Package Slot not deleted because Slot use in package inventory, please disble this Slot";
        $method = $request->method();
        if ($method == "POST") {
            if (is_numeric($slot_id) && $slot_id > 0) {
                $record = PackageSlot::find($slot_id);

                if($record){

                    $PackageInventory =  PackageInventory::where('slot_id',$slot_id)->first();

                    $new_data = $record;
                    $module_id = $package_id;
                    $sub_module_desc = !empty($new_data->start_time)?$new_data->start_time:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);

                  if($PackageInventory){

                    $updateData = array('status' => 0, );
                    $is_deleted =  PackageSlot::where('id',$slot_id)->update($updateData);
                    $response['msg'] = "Package Slot Deactived";
                    $action = 'Deactive';

                  }else{
                    $is_deleted = $record->delete();
                    $response['msg'] = "Package Slot deleted";
                    $action = 'Delete';
                  }
                }
              
                if($is_deleted){

                    $response['success'] = true;

                    //=====Activity
                   
                    $user_id = auth()->user()->id;
                    $user_name = auth()->user()->name;
                    $package_list = Package::where(['id'=>$module_id])->first();
                    $module_desc = !empty($package_list->title)?$package_list->title:'';

                    $params = [];
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Package';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = "Price Slot";
                    $params['sub_module_desc'] = $sub_module_desc;
                    $params['sub_module_id'] = $slot_id;
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = $action??'';
                    CustomHelper::RecordActivityLog($params);

                    $response['success'] = true;
                    $response['message'] = "Package price info has been deleted successfully.";
                
                    //==============
                }else{
                    $response['success'] = false;
                    $response['msg'] = "Package Slot not deleted";
                }
            }
        }
            $data['price_id'] = $price_id;
            $data['package_id'] = $package_id;
            $data['package'] = Package::find($package_id);
            $data['package_price'] = PackagePriceInfo::select('title')->where('id',$price_id)->first();
            $data['records'] = PackageSlot::where('package_id',$package_id)->where('price_id',$price_id)->where('status',1)->get();
            $htmlData = view("admin.packages._packageprice_slot", $data)->render();
            $response['htmlData'] = $htmlData;
            $response['success'] = true;

          return response()->json($response);
    }

    public function packageprice_get(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->package_id) {
            $data = [];
            $package_id = $request->package_id;
            $package = Package::find($package_id);
            $data['package'] = $package;
            $data['itinerary_data'] = Itenary::where('package_id',$package_id)->orderBy("day", "asc")->get();
            $data['accommodations'] = Accommodation::where('destination_id',$package->destination_id)->get();
            $data['accommodations_itenerary'] = [];
            if($request->data_id) {
                $package_price_id = $request->data_id;
                $data['record'] = PackagePriceInfo::find($package_price_id);
                $data['package_accommodations'] = PackageAccommodation::where('package_price_id',$package_price_id)->pluck('accommodation_id','itenary_id')->toArray();
                $data['accommodations_itenerary'] = PackageAccommodation::where('package_price_id',$package_price_id)->get();
            }
            
            // prd($data);
            $htmlData = view("admin.packages._packageprice_form", $data)->render();
            $response['htmlData'] = $htmlData;
            $response['success'] = true;
        }
        return response()->json($response);
    }

    public function packageprice_delete(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';

        if ($request->package_id && $request->data_id) {
            $data_id = $request->data_id;
            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $is_deleted = 0;

            $new_data = DB::table('package_price_info')->where(['id'=>$data_id])->first();
            $module_id = !empty($new_data->package_id)?$new_data->package_id:'';
            $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $package_list = Package::where(['id'=>$module_id])->first();
            $module_desc = !empty($package_list->title)?$package_list->title:'';

            $is_delete = PackagePriceInfo::packagepriceInfoDelete($data_id);
            if ($is_delete['status'] != 'ok') {
                $response['message'] = $is_delete['message'];
            } else {
                $delete_category_name = $is_delete['name'];
                $is_deleted = true;
            }

            if ($is_deleted) {
                $package_id = $request->package_id;
                CustomHelper::updatePackagePublishPrice($package_id);

                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Package';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Price Info";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $data_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = 'Delete';
                CustomHelper::RecordActivityLog($params);

                $response['success'] = true;
                $response['message'] = "Package price info has been deleted successfully.";
            } else {
                if(empty($response['message'])) {
                    $response['message'] = "Package price info cannot be deleted, please try again or contact the administrator.";
                }
            }
        }
        return response()->json($response);
    }

    
    public function update_packageprice_order(Request $request){
        $response['success'] = false;
        $response['message'] = '';
        $isSaved = '';
        $package_id = isset($request->package_id) ? $request->package_id : '';
        $getAllData = $request->data;
        if(isset($package_id) && !empty($package_id)){
            foreach ($getAllData as $key => $value) {
                $getId = $value;
                $req_data['sort_order'] = $key+1;
                $isSaved = PackagePriceInfo::where('id',$getId)->where('package_id',$package_id)->update($req_data);
             }
        }
        if($isSaved) {
            $response['success'] = true;
            $response['message'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Order Updated Successfully.</div>';
        }
        return response()->json($response);
    }
    
    public function update_default_packageprice(Request $request){
        $response['success'] = false;
        $response['message'] = '';
        
        $package_id = isset($request->package_id) ? $request->package_id : '';
        $id_to_update = isset($request->update_id) ? $request->update_id : '';
        $prv_default = PackagePriceInfo::where('package_id',$package_id)->where('is_default',1)->select('id')->first();
        $prv_default_id = $prv_default->id ?? 0;
        $isSaved = 0;
        if(isset($package_id) && !empty($package_id)){
                    $PackagePriceInfo = PackagePriceInfo::where('package_id',$package_id)->get();
                    foreach ($PackagePriceInfo as $key => $ppi) {
                    $isSaved++;
                        if ($ppi->id != $id_to_update) {
                            $ppi->is_default = 0;
                        } else {
                            $ppi->is_default = 1;
                        }
                        $ppi->save();
                    }
        }
        if($isSaved) {
            $response['success'] = true;
            $response['prv_id'] = $prv_default_id;
            $response['message'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Default Package Price Updated Successfully.</div>';
        }
        return response()->json($response);
    }


    // Settings Code Start Here
    public function settings(Request $request)
    {
        $data = [];
        $limit = 50;
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : 1;
        $segment2 = $request->segment(2);
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
        }
        $query = PackageSetting::where('identifier',$identifier)->orderBy("id", "asc");
        if (!empty($name)) {
            $query->where("name","like","%" . $name . "%");
        }

        $query->where("status", $status);
        $results = $query->paginate($limit);
        // prd($results->toArray());
        $data["results"] = $results;
        $data["segment"] = $segment2;
        // prd($data);
        return view("admin.packages.settings_index", $data);
    }

    public function settings_add(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $segment2 = $request->segment(2);
        $user_name = auth()->user()->name;
        $record = [];
        $module = "Package";
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
            $module = "Activity";
        }
        $title = "Add ".$module." Settings";
        if (is_numeric($id) && $id > 0) {
            $record = PackageSetting::find($id);
            if($identifier == 'activity'){
                $title = "Edit Activity Settings(" . $record->name . " )";
            }else{
                $title = "Edit Package Settings(" . $record->name . " )";
            }
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            $rules["name"] = 'required|max:255';
            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except([
                "_token",
                "page",
                "back_url",
                "featured",
            ]);
            $req_data['identifier'] = !empty($request->identifier)?$request->identifier:'';
            if(empty($record->id)) {
                $slug = CustomHelper::GetSlug("package_settings","id",$id,$request->name
                );
            }else {
                $slug = CustomHelper::GetSlug("package_settings","id",$id,$request->slug);
            }
            $req_data["slug"] = $slug;

            if(!empty($record) && $record->id == $id) {
                $isSaved = PackageSetting::where("id", $record->id)->update($req_data);
                $record_id = $record->id;
                $msg = "Setting has been updated successfully.";
            }else {
                $isSaved = PackageSetting::create($req_data);
                $record_id = $isSaved->id;
                $msg = "Setting has been added successfully.";
            }
            if ($isSaved) {
                cache()->forget("package_settings");
                $new_data = DB::table('package_settings')->where('id',$record_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $record_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = $module.' Settings';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME . "/".$segment2."/settings"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Settings could be added, please try again or contact the administrator.");
            }
        }
        $data = [];
        $data["page_heading"] = $title;
        $data["segment"] = $segment2;
        $data["identifier"] = $identifier;
        $data["record"] = $record;
        $data["id"] = $id;
        return view("admin.packages.settings_form", $data);
    }

    public function settings_delete(Request $request) {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = 0;
        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $record = PackageSetting::find($id);
                $identifier = $record->identifier??'';
                $url = "packages";
                $module = "Package";
                if($identifier == 'activity') {
                    $module = "Activity";
                    $url = "activity";
                }
                $new_data = DB::table('package_settings')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_deleted = $record->delete();
            }
        }

        if ($is_deleted) {
            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = $module.' Settings';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = 'Delete';
            CustomHelper::RecordActivityLog($params);
            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/settings"))->with("alert-success", "Settings has been deleted successfully.");
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/settings"))->with("alert-danger","Settings cannot be deleted, please try again or contact the administrator.");
        }
    }

    public function departure_13apr2023(Request $request) {
        $package_query = [];
        $today_date = date('Y-m-d');
        $filter = isset($request->f) ? $request->f : '';
        $package_id = $request->package_id;
        $package = Package::find($package_id);

        if (!empty($package)) {
            $package_id = $package->id;

            $package_departures_query = PackageDeparture::with('PackageDepartureCapacity')->where('package_id',$package_id);
            if($filter == 'ex'){
                $package_departures_query->where(DB::raw('DATE(package_departures.from_date)'), '<', $today_date);
            }else{
                $package_departures_query->where(DB::raw('DATE(package_departures.from_date)'), '>=', $today_date);
            }

            if($request->method()=='POST') {
                $from_dates = $request->from_date;
                $checkUnique = '';
                if(isset($from_dates) && is_array($from_dates)){ $checkUnique = array_unique( array_diff_assoc( $from_dates, array_unique( $from_dates ) ) ); }
                $to_dates = $request->to_date;
                $capacities = $request->capacity;
                $counters = $request->counter;
                $old_ids = $request->old_ids;
                $removed_ids = isset($request->removed_ids) && !empty($request->removed_ids) ? explode (",", $request->removed_ids) : '';
                if(empty($checkUnique)){
                if($filter == 'ex'){
                    PackageDeparture::where(function($query) use ($today_date,$old_ids,$removed_ids){
                        $query->where(DB::raw('DATE(package_departures.from_date)'), '<', $today_date);
                        if(isset($old_ids) && !empty($old_ids)){
                            $query->whereIn('id',$old_ids);
                        }
                        if($removed_ids){
                            $query->orWhereIn('id',$removed_ids);
                        }
                        $query->delete();
                    });
                }elseif($filter == 'up'){
                    PackageDeparture::where(function($query) use ($package_id,$today_date,$old_ids,$removed_ids){
                        $query->where('package_id',$package_id);
                        $query->where(DB::raw('DATE(package_departures.from_date)'), '>=', $today_date);
                        if(isset($old_ids) && !empty($old_ids)){
                            $query->whereIn('id',$old_ids);
                        }
                        if($removed_ids){
                            $query->orWhereIn('id',$removed_ids);
                        }
                        $query->delete();
                    });
                }
                    PackageDepartureCapacity::where(function($query) use ($package_id,$old_ids,$removed_ids){
                        $query->where('package_id',$package_id);
                        if(isset($old_ids) && !empty($old_ids)){
                            $query->whereIn('package_departure_id',$old_ids);
                        }
                        if($removed_ids){
                            $query->orWhereIn('package_departure_id',$removed_ids);
                        }
                        $query->delete();
                    });

                if(!empty($counters)) {
                    $nowtime = Carbon::now();
                    foreach ($counters as $counter) {
                        $from_date = $from_dates[$counter]??'';
                        if($from_date) {
                            $from_date = CustomHelper::DateFormat($from_date,'Y-m-d','d/m/Y');
                        } else {
                            $from_date = NULL;
                        }
                        $to_date = $to_dates[$counter]??'';
                        if($to_date) {
                            $to_date = CustomHelper::DateFormat($to_date,'Y-m-d','d/m/Y');
                        } else {
                            $to_date = NULL;
                        }
                        if(!empty($from_date) && !empty($to_date)) {
                            $departure = [
                                'package_id' => $package_id,
                                'from_date' => $from_date,
                                'to_date' => $to_date,
                            ];
                            $isSaved = PackageDeparture::create($departure);
                            if($isSaved) {
                                $package_departure_id = $isSaved->id;
                                $package_price_capacity = $capacities[$counter]??[];
                                if(!empty($package_price_capacity)) {
                                    $capacity_arr = [];
                                    foreach($package_price_capacity as $package_price_id => $capacity) {
                                        $capacity_arr[] = [
                                            'package_id' => $package_id,
                                            'package_departure_id' => $package_departure_id,
                                            'package_price_id' => $package_price_id,
                                            'capacity' => $capacity??0,
                                            'created_at' => $nowtime,
                                            'updated_at' => $nowtime,
                                        ];
                                    }
                                    if(!empty($capacity_arr)) {
                                        PackageDepartureCapacity::insert($capacity_arr);
                                    }
                                }
                            }
                        }
                    }
                }
                return redirect(url($this->ADMIN_ROUTE_NAME."/packages/".$package_id."/departure?f=".$filter))->with("alert-success", "Package Travel Dates has been updated successfully.");
            }else{
                return redirect(url($this->ADMIN_ROUTE_NAME."/packages/".$package_id."/departure?f=".$filter))->with("alert-danger", "From Date must be unique in Package Travel Dates.");
            }
            }
            $data = [];
            $data["package"] = $package;
            $data["package_id"] = $package_id;
            $data["page_heading"] = 'Travel Dates ('.$package->title.')';
            // $data['package_departures'] = PackageDeparture::with('PackageDepartureCapacity')->where('package_id',$package_id)->get();
            $data['package_departures'] = $package_departures_query->orderBy('from_date','asc')->get();
            
            return view("admin.packages.departure_index", $data);
        } else {
            abort(404);
        }
    }

    public function departure(Request $request) {
        $package = Package::find($request->package_id);
        $segment2 = $request->segment(2);
        if (!empty($package)) {
            $package_id = $package->id;
            if($request->method()=='POST') {
                // prd($request->toArray());
                $success = false;
                $statusCode = 400;
                $message = '';
                $price_id = '';
                if($request->inventory) {
                    $inventory_arr = [];
                    $inventory = $request->inventory??[];
                    if(!empty($inventory) && is_array($inventory)){
                        foreach($inventory as $date => $price_ids) {
                            $trip_date = CustomHelper::DateFormat($date,'Y-m-d','dmY');
                            if($price_ids) {

                                foreach($price_ids as $price_id => $slotval) {

                                    foreach($slotval as $slot_id => $val) {

                                        if($slot_id == 0){
                                            $slot_id = null;
                                        }

                                    $req_data = [
                                        'package_id' => $package_id,
                                        'price_id' => $price_id,
                                        'slot_id' => $slot_id,
                                        'trip_date' => $trip_date,
                                        'inventory' => $val??0,
                                        'status' => 1,
                                    ];
                                    // prd($req_data);

                                    $record = PackageInventory::where('package_id',$package_id)->where('price_id',$price_id)->where('slot_id',$slot_id)->where('trip_date',$trip_date)->first();
                                    if($record) {
                                        $isSaved = PackageInventory::where("id", $record->id)->update($req_data);
                                        if($isSaved) {
                                            $success = true;
                                            $statusCode = 200;
                                            $inventory_arr[$record->id] = $req_data['inventory'];
                                        }
                                    } else {
                                        $isSaved = PackageInventory::create($req_data);
                                        if($isSaved) {
                                            $success = true;
                                            $statusCode = 200;
                                            $inventory_arr[$isSaved->id] = $req_data['inventory'];
                                        }
                                    }
                                }
                                }
                            }
                        }
                    }
                    if($success) {

                        //=====
                            $before_days = 15;
                            $date=strtotime(date('Y-m-d')); 
                            $before_date = date('Y-m-d',strtotime('-15 days',$date));
                            $is_deleted = PackageInventory::where('package_id',$package_id)->whereDate('trip_date','<',$before_date)->delete();
                        //=====

                        $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Inventory Updated Successfully.</div>';

                        //=====Activity
                        $new_data = $inventory_arr;
                        $module_id = $package_id;
                        $new_data =(array) $new_data;
                        $new_data = json_encode($new_data);
                        $sub_module = DB::table('package_price_info')->where(['id'=>$price_id])->first();
                        $sub_module_desc = !empty($sub_module->title)?$sub_module->title:'';
                        $user_id = auth()->user()->id;
                        $user_name = auth()->user()->name;
                        $package_list = Package::where(['id'=>$module_id])->first();
                        $module_desc = !empty($package_list->title)?$package_list->title:'';

                        $params = [];
                        $params['user_id'] = $user_id;
                        $params['user_name'] = $user_name;
                        $params['module'] = 'Package';
                        $params['module_desc'] = $module_desc;
                        $params['module_id'] = $module_id;
                        $params['sub_module'] = "Package Inventory";
                        $params['sub_module_desc'] = $sub_module_desc;
                        $params['sub_module_id'] = $price_id;
                        $params['sub_sub_module'] = "";
                        $params['sub_sub_module_id'] = 0;
                        $params['data_after_action'] = $new_data;
                        $params['action'] = 'Update';
                        CustomHelper::RecordActivityLog($params); 
                        //==============
                    } else {
                        $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Inventory not updated, please try again.</div>';
                    }
                }
                return Response::json(array(
                    'success' => $success,
                    'message' => $message
                ), $statusCode);
            }
            $data = [];
            $data["package"] = $package;
            $data["package_id"] = $package_id;
            $data["page_heading"] = 'Travel Dates ('.$package->title.')';           

            $data['pacakge_inventory'] = PackageInventory::where('package_id',$package_id)->get();
            // prd($pacakge_inventory->toArray());

            $start_date = date('Y-m-d');
            $end_date =  date('Y-m-d', strtotime($start_date. ' + 7 days'));
            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $pre_date =  date('Y-m-d', strtotime($start_date. ' - 7 days'));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $data['pre_date'] = $pre_date;
            $data['last_date'] = $end_date;
            $data['start_date'] = $start_date;
            $data['period'] = $period;
            // prd($data['period']);
            $data['start_date'] = $start_date;
            $data['segment'] = $segment2;
            $data['package_slots'] = PackageSlot::where('package_id',$package_id)->where('status',1)->get();
            
            return view("admin.packages.departure_index", $data);
        } else {
            abort(404);
        }
    }

    public function departure_next(Request $request) {
        // prd($request->toArray());
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;
        $data = [];
        $package_id = $request->package_id??'';
        $date = $request->date??'';
        if(!empty($package_id) && is_numeric($package_id) && !empty($date) && $date != 'undefined') {
            $package = Package::find($package_id);
            $data["package"] = $package;
            $data["packagePrices"] = $package->packagePrices??[];
            $data["package_id"] = $package_id;
            $data['pacakge_inventory'] = PackageInventory::where('package_id',$package_id)->get();
                   
            $start_date = $date;
            $end_date =  date('Y-m-d', strtotime($start_date. ' + 7 days'));
            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $pre_date =  date('Y-m-d', strtotime($start_date. ' - 7 days'));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $data['pre_date'] = $pre_date;
            $data['last_date'] = $end_date;
            $data['start_date'] = $start_date;
            $data['period'] = $period;
            // prd($data['period']);
            $data['start_date'] = $start_date;
            $data['html'] = view("admin.packages._package_inventory", $data)->render();
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

    public function departure_bulk(Request $request) {
        // prd($request->toArray());
        $success = false;
        $message = '';
        $html = '';
        $statusCode = 400;

        $package_id = $request->package_id??'';
        $bulkdaterange = $request->bulkdaterange??'';
        $price_ids = $request->package_price??'';
        // prd($price_ids);
        if(!empty($package_id) && !empty($bulkdaterange) && !empty($price_ids)) {
            $package = Package::find($package_id);
            $bulkdaterange_arr = explode(' - ', $bulkdaterange);
            $from_date = $bulkdaterange_arr[0]??'';
            $to_date = $bulkdaterange_arr[1]??'';
            $from_date = CustomHelper::DateFormat($from_date,'Y-m-d','d/m/Y');
            $to_date = CustomHelper::DateFormat($to_date,'Y-m-d','d/m/Y');
            if($from_date && $to_date) {
                $start_date = $from_date;
                $end_date =  $to_date;
                $begin = new DateTime($start_date);
                $end = new DateTime(date('Y-m-d', strtotime($end_date. ' + 1 days')));
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                // prd($period);
                foreach($period as $dt) {
                    $date = $dt->format("d-m-Y");
                    $trip_date = $dt->format("Y-m-d");
                    if($price_ids) {


                                foreach($price_ids as $price_id => $slotval) {

                                    foreach($slotval as $slot_id => $val) {

                                        if($slot_id == 0){
                                            $slot_id = null;
                                        }

                                    $req_data = [
                                        'package_id' => $package_id,
                                        'price_id' => $price_id,
                                        'slot_id' => $slot_id,
                                        'trip_date' => $trip_date,
                                        'inventory' => $val??0,
                                        'status' => 1,
                                    ];
                                    // prd($req_data);

                                    $record = PackageInventory::where('package_id',$package_id)->where('price_id',$price_id)->where('slot_id',$slot_id)->where('trip_date',$trip_date)->first();
                                    if($record) {
                                        $isSaved = PackageInventory::where("id", $record->id)->update($req_data);
                                        if($isSaved) {
                                            $success = true;
                                            $statusCode = 200;
                                            $inventory_arr[$record->id] = $req_data['inventory'];
                                        }
                                    } else {
                                        $isSaved = PackageInventory::create($req_data);
                                        if($isSaved) {
                                            $success = true;
                                            $statusCode = 200;
                                            $inventory_arr[$isSaved->id] = $req_data['inventory'];
                                        }
                                    }
                                }
                                }
                            
                            
                    }
                }
            }
        }
        if($success) {

            //=====
                $before_days = 15;
                $date=strtotime(date('Y-m-d')); 
                $before_date = date('Y-m-d',strtotime('-15 days',$date));
                $is_deleted = PackageInventory::where('package_id',$package_id)->whereDate('trip_date','<',$before_date)->delete();
            //=====

            $message = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Inventory Updated Successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Inventory not updated, please try again.</div>';
        }
        return Response::json(array(
            'success' => $success,
            'message' => $message
        ), $statusCode);
    }

    public function ajax_departure_row(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->package_id) {
            $package = Package::find($request->package_id);
            if (!empty($package)) {
                $response['success'] = true;
                $data = [];
                $data['counter'] = $request->counter??1;
                $data['packagePrices'] = $package->packagePrices??[];
                $htmlData = view('admin.packages._departure_row',$data)->render();
                $response['htmlData'] = $htmlData;
            } else {
                $response['message'] = 'Package not found.';
            }
        }
        return response()->json($response);
    }


    public function ajax_departure_date(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->package_id) {
            $package = Package::find($request->package_id);
            if (!empty($package)) {
                $package_id = $package->id;
                $response['success'] = true;
                $data = [];
                $capacity = $request->capacity??0;
                $from_date = $request->start_date??'';
                if($from_date) {
                    $checkUnique = PackageDeparture::where('from_date','=',$from_date)->first();
                    if(empty($checkUnique)){
                    $package_duration_days = $package->package_duration_days??0;
                    $to_date = date( "Y-m-d", strtotime("$from_date +$package_duration_days day"));
                    $departure = [
                        'package_id' => $package_id,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                    ];
                    $isSaved = PackageDeparture::create($departure);
                    if($isSaved) {
                        $package_departure_id = $isSaved->id;
                        $packagePrices = $package->packagePrices??[];
                        if(!empty($packagePrices)) {
                            $capacity_arr = [];
                            $nowtime = Carbon::now();
                            foreach($packagePrices as $pp) {
                                $capacity_arr[] = [
                                    'package_id' => $package_id,
                                    'package_departure_id' => $package_departure_id,
                                    'package_price_id' => $pp->id,
                                    'capacity' => $capacity??0,
                                    'created_at' => $nowtime,
                                    'updated_at' => $nowtime,
                                ];
                            }
                            if(!empty($capacity_arr)) {
                                PackageDepartureCapacity::insert($capacity_arr);
                            }
                        }
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'From Date must be unique in Package Travel Dates.';
                  }
                }
            } else {
                $response['message'] = 'Package not found.';
            }
        }
        return response()->json($response);
    }


    public function packagesetting(Request $request) {
        $package_query = [];
        $package_id = $request->package_id;
        $package = Package::find($package_id);
        if (!empty($package)) {
            $package_id = $package->id;
            if($request->method()=='POST') {
                // prd($request->toArray());
                PackageToSetting::where('package_id',$package_id)->delete();
                if($request->package_settings) {
                    $package_settings_arr = [];
                    foreach($request->package_settings as $setting_id) {
                        $package_settings_arr[] = [
                            'package_id' => $package_id,
                            'setting_id' => $setting_id,
                        ];
                    }
                    PackageToSetting::insert($package_settings_arr);
                    return redirect(url($this->ADMIN_ROUTE_NAME."/packages/".$package_id."/packagesetting"))->with("alert-success", "Package Settings has been updated successfully.");
                }
            }
            $data = [];
            $data["package"] = $package;
            $data["package_id"] = $package_id;
            $data["page_heading"] = 'Settings ('.$package->title.')';
            // prd($data);
            $data['package_settings'] = PackageSetting::where('status',1)->get();
            $data['settings_ids'] = PackageToSetting::where('package_id',$package_id)->pluck('setting_id')->toArray();
            return view("admin.packages.packagesetting_index", $data);
        } else {
            abort(404);
        }
    }

    //==================

    public function getHotelList(Request $request) {

        $total_accommodations = [];
        $days_arr = $request->days ?? '';
        if(!is_array($days_arr)){
            $days_arr = explode(',',$request->days);
        }
        $options = "";
        if($days_arr && is_array($days_arr))
        {    
            foreach ($days_arr as $key => $days_val) {
                $iti_data = Itenary::where('id',$days_val)->orderBy("day", "asc")->first();
                $accommodations = CustomHelper::getItineraryAccommodations($iti_data);
                // $total_accommodations[] = $accommodations;
                $selected = '';
                if($accommodations){
                    foreach ($accommodations as $key => $accommodation) {  
                        if(!in_array($accommodation->id,$total_accommodations)){
                            $total_accommodations[] = $accommodation->id ?? '';
                            $options .= '<option value="'.$accommodation->id.'" '.$selected.'>'.$accommodation->name.'</option>';

                        }
                    }
                }
            }
        }

        $response['success'] = true;
        $response['options'] = $options;
        return response()->json($response);

        // return $total_accommodations;

    }

    //Flags=========
    
    public function flags_index(Request $request){

        $data = [];
        $limit = 50;
        $package_type = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : 1;
        $segment2 = $request->segment(2);
        $identifier = 'package';
        if($segment2 == 'activity'){
           $identifier = 'activity';
        }
        $package_query = Flag::where('identifier',$identifier)->orderBy("id", "desc");
       
        if(!empty($package_type)) {
            $package_query->where("name","like", "%" . $package_type . "%"
            );
        }
        $package_query->where("status", $status);

        $packages = $package_query->paginate($limit);
        $data["packages"] = $packages;
        $data["segment"] = $segment2;

        return view("admin.package_flags.index", $data);
    }

    public function flags_add(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $segment2 = $request->segment(2);
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $package_query = [];
        $module = "Package";
        $identifier = 'package';
        if($segment2 == 'activity'){
            $identifier = 'activity';
            $module = "Activity";
        }
        $title = "Add ".$module." Flag";

        if (is_numeric($id) && $id > 0) {
            $package_query = Flag::find($id);
            $package_flag = $package_query->name??'';
            if($identifier == 'activity'){
                $title = "Edit Activity Flag(" . $package_flag . " )";
            }else{
                $title = "Edit Package Flag(" . $package_flag . " )";
            }
        }

        if ($request->method() == "POST" || $request->method() == "post") {
           
            $rules = [];
            $validation_msg = [];
            $rules["name"] = 'required|max:255';

            $validation_msg['name.required'] = 'The Package Flag field is required.';


            $this->validate($request,$rules,$validation_msg);
            $req_data = [];
            $req_data['name'] = $request->name ?? '';
            $req_data['title'] = $request->title ?? '';
            $req_data['brief'] = $request->brief ?? '';
            $req_data['identifier'] = $request->identifier ?? '';
            $req_data['status'] = $request->status ?? '';

            if(empty($package_query->id)) {
                $slug = CustomHelper::GetSlug("flags","id",$id,$request->name);
            }else {
                $slug = CustomHelper::GetSlug("flags","id",$id,$request->slug);
            }

            $req_data["slug"] = $slug;
            if (!empty($package_query) && $package_query->id == $id) {
                $isSaved = Flag::where("id", $package_query->id)->update($req_data);
                $package_flag_id = $package_query->id;
                $msg = $module." Flag has been updated successfully.";
 
            } else {
                $isSaved = Flag::create($req_data);
                $package_flag_id = $isSaved->id;
                $msg = $module." Flag has been added successfully.";
            }
            if ($package_flag_id) {
                cache()->forget("package_flags");
             /* CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description);*/

                 //=============Activity Logs=====================

           $new_data = DB::table('flags')->where('id',$package_flag_id)->first();
           $module_desc =  !empty($new_data->name)?$new_data->name:'';
           $new_data =(array) $new_data;
           $new_data = json_encode($new_data);
           $module_id = $package_flag_id;
           $params['user_id'] = $user_id;
           $params['user_name'] = $user_name;
           $params['module'] = $module.' Flag';
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

           return redirect(url($this->ADMIN_ROUTE_NAME . "/".$segment2."/flags"))->with("alert-success", $msg);
       } else {
        return back()->with(
            "alert-danger",
            "The ".$module." Flag could be added, please try again or contact the administrator."
        );
    }
}

$data = [];
$data["page_heading"] = $title;
$data["package_query"] = $package_query;
$data["segment"] = $segment2;
$data["identifier"] = $identifier;
$data["id"] = $id;

return view("admin.package_flags.form", $data);
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

            $package_query = Flag::find($id);
            $identifier = $package_query->identifier??'';
            $url = "packages";
            $module = "Package";
            if($identifier == 'activity') {
                $module = "Activity";
                $url = "activity";
            }
            $new_data = DB::table('flags')->where('id',$id)->first();
            $module_desc =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $is_deleted = $package_query->delete();
            }
        }

        if ($is_deleted) {

       //=============Activity Logs============
       $params = [];
       $params['user_id'] = $user_id;
       $params['user_name'] = $user_name;
       $params['module'] = $module.' Flag';
       $params['module_desc'] = $module_desc;
       $params['module_id'] = $id;
       $params['sub_module'] = "";
       $params['sub_module_id'] = 0;
       $params['sub_sub_module'] = "";
       $params['sub_sub_module_id'] = 0;
       $params['data_after_action'] = $new_data;
       $params['action'] = 'Delete';

       CustomHelper::RecordActivityLog($params);

        //=============Activity Logs============

       return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/flags"))->with("alert-success", $module." Flag has been deleted successfully.");
   } else {

    return redirect(url($this->ADMIN_ROUTE_NAME . "/".$url."/flags"))->with("alert-danger",$module." Flag cannot be deleted, please try again or contact the administrator.");
}
}
//==============
/* end of controller */
}
