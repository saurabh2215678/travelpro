<?php
namespace App\Http\Controllers\Admin;

use App\Models\CabRouteGroup;
use App\Models\CabRoute;
use App\Models\CabCities;
use App\Models\CabMaster;
use App\Models\AgentGroup;
use App\Models\ModuleDiscountCategory;
use App\Models\DiscountModuleToGroup;
use App\Models\CustomModuleRecordDiscount;
use App\Models\CabRoutePrice;
use App\Models\CabRouteToGroup;
use App\Models\CmsPages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use App\Helpers\CabHelper;
use Storage;
use Validator;
use DB;

class CabRouteController  extends Controller {

    //protected $foo;

    private $limit;
    protected $select_cols;
    protected $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){

        $this->limit = 30;

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request) {

        $parent_id = (isset($request->parent_id))?$request->parent_id:0;
        $name = isset($request->name) ? $request->name: "";
        $status = isset($request->status) ? $request->status : 1;
        $data=array();
        $limit = $this->limit;


        $pageQuery = CabRoute::with('CabRouteGroup','originCity','destinationCity')->orderBy('sort_order','asc');
        if (!empty($name)) {

            $pageQuery->where(function($q1) use($name){

             $q1->where("name", "like", "%" . $name . "%");
             $q1->orWhere("places", "like", "%" . $name . "%");
            });
        }

        if(isset($request->cab_route_group_id)) {
            $cab_route_group_id = $request->cab_route_group_id;
            // $pageQuery->where('cab_route_group_id',$request->cab_route_group_id);
             $pageQuery->whereHas('CabRouteToGroup',function($q2) use($cab_route_group_id){
                   $q2->where('cab_route_group_id',$cab_route_group_id);
                });
        }

        if(isset($request->route_type)) {

           $pageQuery->where('route_type',$request->route_type);
        }

        if(isset($request->origin)) {
        $pageQuery->where('origin',$request->origin);
        }

        if(isset($request->destination)) {
        $pageQuery->where('destination',$request->destination);
        }

        $pageQuery->where("status", $status);
        $pages = $pageQuery->paginate($limit);

        $discount_result = DiscountModuleToGroup::where('module_name','cab')->where('is_default',1)->first();
        // prd($discount_result);
        $default_group = '';
        $default_group = isset($discount_result->discount_category) ? $discount_result->discount_category->name :'' ;
         $data['default_group'] = $default_group.'(Default)';

        $data['res']= $pages;
        $data["page_heading"] = "Cab Route";
        $data['cab_route_groups'] = CabRouteGroup::where('status',1)->get();
        $data['cab_cities'] = CabCities::where('status',1)->get();
        return view('admin.cab_route.index',$data);
    }

   public function groups(Request $request) {

        $parent_id = (isset($request->parent_id))?$request->parent_id:0;
        $name = isset($request->name) ? $request->name: "";
        $status = isset($request->status) ? $request->status : 1;
        $data=array();
        $limit = $this->limit;
        $data['page_title'] = 'Cab Route group Page';
        $data['title'] = 'Cab Route group Page';
        $Query = CabRouteGroup::withCount('cabRoutes')->orderBy('id','desc');
        if (!empty($name)) {
          $Query->where("name", "like", "%" . $name . "%");
        }
        $Query->where("status", $status);
        $result = $Query->paginate($limit);
        $data['res']= $result;
        $data["page_heading"] = "Cab Route Group";
        return view('admin.cab_route.groups',$data);
    }

    public function add(Request $request){
        $id = (isset($request->id))?$request->id:0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $parent_id = (isset($request->parent_id))?$request->parent_id:0;
        $page = [];
        $title = 'Add Cab Route';

        if(is_numeric($id) && $id > 0){
            $page = CabRoute::find($id);
            $cab_route_title = $page->name??'';
            $title = 'Edit Cab Route ('.$cab_route_title.")";
        }

        if($request->method() == 'POST' || $request->method() == 'post'){

       // prd($request->toArray());

            $rules = [];
            $rules['name'] = 'required|max:255';
            $rules['cab_route_group_id'] = 'required';
            $rules['route_type'] = 'required';
            $rules['origin'] = 'required';
            $rules['distance'] = 'nullable|numeric';

            $this->validate($request, $rules);

            $req_data = [];
            $req_data['name'] = (!empty($request->name))?$request->name:'';
            // $req_data['cab_route_group_id'] = (!empty($request->cab_route_group_id))?$request->cab_route_group_id:'';
            $req_data['origin'] = $request->origin ?? null;
            $req_data['destination'] = $request->destination ?? null;
            $req_data['duration'] = (!empty($request->duration))?$request->duration:'';
            $req_data['distance'] = (!empty($request->distance))?$request->distance:'';
            $req_data['description'] = (!empty($request->description))?$request->description:'';
            $req_data['places'] = (!empty($request->places))?$request->places:'';
            $req_data['sharing'] = (!empty($request->sharing))?$request->sharing:'';
            $req_data['featured'] = (!empty($request->featured))?$request->featured:'';
            $req_data['start_time'] = (!empty($request->start_time))?$request->start_time:'';
            $req_data['status'] = (!empty($request->status))?$request->status:'';
            $req_data['route_type'] = (!empty($request->route_type))?$request->route_type:'';
            //$req_data['cab_city_id'] = (!empty($request->cab_city_id))?$request->cab_city_id:'';
            //prd($req_data);

            if(!empty($page) && count(array($page)) > 0){
                if($request->slug){
                    $req_data["slug"] = CustomHelper::GetSlug('cab_route', 'id', $id, $request->slug);
                } else if(empty($page->slug)) {
                    $req_data["slug"] = CustomHelper::GetSlug('cab_route', 'id', $id, $request->name);
                }
                $isSaved = CabRoute::where('id', $page->id)->update($req_data);
                $data_id = $page->id;
                $msg="Cab Route has been updated successfully.";
            } else {
                if($request->name) {
                    $req_data["slug"] = CustomHelper::GetSlug('cab_route', 'id', $id, $request->name);
                }
                $isSaved = CabRoute::create($req_data);
                $data_id = $isSaved->id;
                $msg="Cab Route has been added successfully.";
            }

            if ($isSaved) {

                $cab_route_group_ids = (!empty($request->cab_route_group_id))?$request->cab_route_group_id:[];
                $CabRouteToGroup = [];
                if($cab_route_group_ids){
                    $isDeleted = CabRouteToGroup::where('cab_route_id',$data_id)->delete();

                    foreach ($cab_route_group_ids as $key => $cab_route_group_id) {
                        $CabRouteToGroup[] = array(
                            'cab_route_id' =>$data_id ,
                            'cab_route_group_id' => $cab_route_group_id,
                        );
                    }
                }

                $isinsert = CabRouteToGroup::insert($CabRouteToGroup);

            //=============Activity Logs=====================

            $new_data = DB::table('cab_route')->where('id',$data_id)->first();
            $name =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $module_id = $data_id;
            $module_desc= $name;
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Cab Route';
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
            // cache()->forget('cms');

            return redirect(url($this->ADMIN_ROUTE_NAME.'/cab_route'))->with('alert-success', $msg);
        } else {
            return back()->with('alert-danger', 'The cab route could be added, please try again or contact the administrator.');
        }
    }

    $data = [];
    $data["page_heading"] = "Cab Route";
    $data['cab_route'] = $page;
    $data['id'] = $id;
    $data['cab_route_groups'] = CabRouteGroup::where('status',1)->get();
    $data['cab_cities'] = CabCities::where('status',1)->get();
    return view('admin.cab_route.add', $data);

}

  public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $cab_route = "";
        $title = "Cab Route";

        if (is_numeric($id) && $id > 0) {
            $cab_route = CabRoute::where("id", $id)->first();
            //prd($cab_route);
            $title = "Cab Route";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["cab_route"] = $cab_route;
        $data["id"] = $id;
        return view("admin.cab_route.view", $data);
    }

    public function update_cab_route(Request $request){

        $response['success'] = false;
        $response['msg'] = '';
        $found = 0;

        $getAllData = $request->data;

        if(isset($getAllData) && !empty($getAllData)){
            $found = 1;
            foreach ($getAllData as $key => $value) {
             $getId = $value;
             $req_data['sort_order'] = $key+1;
             $isSaved = CabRoute::where('id',$getId)->update($req_data);
         }
     }
     if ($found) {
        $response['success'] = true;
        $response['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Cab Route has been updated successfully.</div>';
    }
    return response()->json($response);        

}


  public function addgroup(Request $request){

        $id = (isset($request->id))?$request->id:0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $parent_id = (isset($request->parent_id))?$request->parent_id:0;
        $page = [];
        $title = 'Add Route Group';

        if(is_numeric($id) && $id > 0){
            $page = CabRouteGroup::find($id);
            $title = 'Edit Page ('.$page->name.")";
        }
        if($request->method() == 'POST' || $request->method() == 'post'){

            $rules = [];
            $validation_msg = [];
            $rules["name"] = 'required|max:255|regex:/^[\pL\s\-]+$/u';
            $rules['cab'] = 'required';
            $validation_msg["name.required"] = "Name is required.";
            $validation_msg["name.regex"] = "Name format is Invalid.";
            $this->validate($request, $rules,$validation_msg);

            $req_data = [];
            $req_data['name'] = (!empty($request->name))?$request->name:'';
            $req_data['inclusion'] = (!empty($request->inclusion))?$request->inclusion:null;
            $req_data['exclusion'] = (!empty($request->exclusion))?$request->exclusion:null;
            $req_data['term'] = (!empty($request->term))?$request->term:null;
            $req_data['sharing'] = (!empty($request->sharing))?$request->sharing:null;
            $cab_arr  = (isset($request->cab)) ? $request->cab:[];

            // prd($cab_arr);
            $req_data['status'] = (isset($request->status)) ? $request->status:0;
            $car_data = [];
            $cab_data = [];
            foreach ($cab_arr as $key => $cab_id) {

              $cab_res = CabHelper::getCabData($cab_id);
              $cab_name = $cab_res->name ?? '';
              $cab_data[]  = array(
                               'id' => $cab_id,
                               'name' => $cab_name,
                            );
                // prd($cab_data);
            }

            $req_data['cab_data'] = json_encode($cab_data);
            if(!empty($page) && count(array($page)) > 0){
                $isSaved = CabRouteGroup::where('id', $page->id)->update($req_data);
                $data_id = $page->id;
                $msg="Car Route Group has been updated successfully.";
            }else{
                $isSaved = CabRouteGroup::create($req_data);
                $data_id = $isSaved->id;
                $msg="Car Route Group has been added successfully.";
            }

            if ($isSaved) {

            //=============Activity Logs=====================

            $new_data = DB::table('cab_route_group')->where('id',$data_id)->first();
            $title =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $module_id = $data_id;
            $module_desc= $title;
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Cab Route Group';
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
            // cache()->forget('cms');

                return redirect(url($this->ADMIN_ROUTE_NAME.'/cab_route/groups'))->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Route group could be added, please try again or contact the administrator.');
            }
    }

    $data = [];
    $data["page_heading"] = "Cab Route Group";
    $data['group'] = $page;
    $data['id'] = $id;
    $data['parent_id'] = $parent_id;
    $data['cabs'] = CabMaster::where('status',1)->get();
    return view('admin.cab_route.addgroup', $data);

}

// public function ajax_delete_image(Request $request){
//         //prd($request->toArray());
//     $result['success'] = false;

//     $image_id = ($request->has('image_id'))?$request->image_id:0;
//     $type = ($request->has('type'))?$request->type:'';

//     if (is_numeric($image_id) && $image_id > 0) {
//         $is_img_deleted = $this->delete_images($image_id,$type);
//         if($is_img_deleted)
//         {
//             $result['success'] = true;
//             $result['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
//         }
//     }

//     if($result['success'] == false){
//         $result['msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
//     }
//     return response()->json($result);
// }

public function delete(Request $request)
{
    $id = !empty($request->id)?$request->id:'';
    $method = $request->method();
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $parent_id = 0;

        //prd($id);
    $is_deleted = 0;
    $storage = Storage::disk('public');
    $path = 'cms_pages/';

    if($method=="POST"){
        if(is_numeric($id) && $id > 0)
        {
            $page = CmsPages::find($id);
            $new_data = DB::table('cms_pages')->where('id',$id)->first();
            $module_desc =  !empty($new_data->title)?$new_data->title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $is_delete = CabRoute::cabRouteDelete($id);
                    if ($is_delete['status'] != 'ok') {

                        return redirect(url('admin/cab_route'))->with('alert-danger', $is_delete['message']);

                    }
                    else {

                        $delete_destination_name = $is_delete['name'];

                        $is_deleted = true;

                    // if(!$is_deleted){
                    // return redirect(
                    // url($this->ADMIN_ROUTE_NAME . "/cab_master")
                    // )->with(
                    // "alert-danger",
                    // "You cannot delete ".$delete_destination_name
                    // );
                    // }
                    }

           /* $function_name = $this->currentUrl;
            $action_table = 'cms_pages';
            $row_id = $id;
            $action_type = 'Delete Page';
            $action_description = 'Delete ('.$page->title.")";
            $description = 'Delete ('.$page->title.")";*/

            if(!empty($page) && count(array($page)) > 0){
                $parent_id = $page->parent_id??0;
                if(!empty($page->image))
                {
                   $image = $page->image;
                   $banner_image = $page->banner_image??'';
                   if(!empty($image) && $storage->exists($path.'thumb/'.$image))
                   {
                    $is_deleted = $storage->delete($path.'thumb/'.$image);
                }
                if(!empty($image) && $storage->exists($path.$image))
                {
                    $is_deleted = $storage->delete($path.$image);
                }

                if(!empty($banner_image) && $storage->exists($path.'thumb/'.$banner_image))
                {
                    $is_deleted = $storage->delete($path.'thumb/'.$banner_image);
                }
                if(!empty($banner_image) && $storage->exists($path.$banner_image))
                {
                    $is_deleted = $storage->delete($path.$banner_image);
                }
            }
            //$is_deleted = $page->delete();

        }
    }
}
if($is_deleted){

    /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

    //=============Activity Logs=====================

    $params = [];
    $params['user_id'] = $user_id;
    $params['user_name'] = $user_name;
    $params['module'] = 'CMS Pages';
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

    return redirect(url($this->ADMIN_ROUTE_NAME.'/cms?parent_id='.$parent_id))->with('alert-success', 'The Page has been deleted successfully.');
}else
{
    return redirect(url($this->ADMIN_ROUTE_NAME.'/cms'))->with('alert-danger', 'The Page cannot be deleted, please try again or contact the administrator.');
}

}

// agent_price
    public function agent_price(Request $request) {
        $data = [];
        $limit = $this->limit;
        $id = isset($request->id) ? $request->id : "";

        $day_title = (isset($request->day_title))?$request->day_title:'';
        $status = (isset($request->status))?$request->status:'';
        if(!empty($id)) {
         
            $CabRoute = CabRoute::find($id);    
            if($CabRoute) {

                $module_name = 'cab';
                $module_record_id = $CabRoute->id;
                $cab_route_group_ids = $CabRoute->CabRouteToGroup->pluck('id')->toArray()??[];
                // prd($cab_route_group_ids);

                $discount_category_id = isset($CabRoute->discount_category_id)?$CabRoute->discount_category_id : null;
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
           
                $discount_categories = ModuleDiscountCategory::where('module_name',$module_name)->orderBy('module_name', 'asc')->get();
                $data['agent_groups'] = AgentGroup::where('status',1)->get();
                $data["heading"] = 'Agent Price ('.$CabRoute->name.')';
                $data["cab_route_id"] = $id;

                $groups_query = CabRouteGroup::where('status',1)->orderBy('id','desc');
                if(!empty($cab_route_group_ids)) {
                    $groups_query->whereIn('id',$cab_route_group_ids)->get();
                }
                $group_data = $groups_query->get();
                $data['group_datas'] = $group_data;

                $CabRoutePrice = CabRoutePrice::whereIn('cab_route_group_id',$cab_route_group_ids)->get();
                $data['CabRoutePrices'] = $CabRoutePrice;
                
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
                return view("admin.cab_route.agent_price", $data);
            }
        }
        abort(404);
    }

    // add_agent_price
    public function add_agent_price(Request $request) {
        $response = [];
        $response['success'] = false;
        $cab_route_id = $request->id??0;
        if(!empty($cab_route_id)) {
            $CabRoute = CabRoute::find($cab_route_id);
            if($CabRoute) {
                $discount_category = isset($request->discount_category) ? $request->discount_category : null;
                $CabRoute->discount_category_id = $discount_category;
                $is_updated = $CabRoute->save();
                if($is_updated) {
                    CustomHelper::updateCabRoutePublishPrice($cab_route_id);
                    $response['success'] = true;
                }
            }
        }
        return response()->json($response);
    }


// End of controller
}
?>