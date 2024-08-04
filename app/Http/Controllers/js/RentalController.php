<?php
namespace App\Http\Controllers\js;

use App\Models\Faq;
use Inertia\Inertia;
use App\Models\SeoMetaTag;
use App\Models\BikeCities;
use App\Models\BikeMaster;
use Illuminate\Http\Request;
use App\Models\BikecityPrice;
use App\Helpers\CustomHelper;
use App\Models\BikeCityInventory;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use DB;
use File;
use Storage;
use DateTime;

class RentalController extends Controller {

    private $limit;
    private $theme;

    public function __construct() {
        $this->limit = 20;
        $this->theme = config('custom.themejs');

    }
    public function index(Request $request) {
        $data = [];
        $seo_data = [];

        $identifier = 'rental';
        $seo_tags = SeoMetaTag::where(['identifier'=>$identifier,'status'=>1])->first();

        $seo_meta_tags_id = $seo_tags->id??0;
        $faq_modules = Faq::where('tbl_name','=','seo_meta_tags')->where('status',1)->where('tbl_id',$seo_meta_tags_id)->orderBy("sort_order", "ASC");
        $faqs = $faq_modules->take(40)->get();
        if($faqs) {
            $faqs_arr = [];
            foreach($faqs as $faq) {
                $faqs_arr[] = $faq->toArray();
            }
            $data['faqs'] = $faqs_arr;
        }
        $banner_image = '';
        $identifier = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
            $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $seo_data['page_url_label'] = $seo_tags->page_url_label??'';
            $seo_data['page_brief'] = $seo_tags->page_brief??'';
            $seo_data['page_description'] = $seo_tags->page_description??'';
            $seo_data['page_url'] = $seo_tags->page_url??'';
            $seo_data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
            $seo_data['meta_description'] = $seo_tags->meta_description??'';
            $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
            $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
            if($seo_tags->image) {
                $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
            }
        }

        $city = $request->city ?? '';
        $data['bikeTypes'] = config('custom.rental_type_arr');
        $data['cities'] = BikeCities::where('status',1)->get();
        $cityData =[];
            $cityQuery = BikeCities::where('status',1);
        if($city){
            $cityQuery->where('id',$city);
        }else{
            $cityQuery->where('is_default',1);
        }
        $cityData = $cityQuery->first();
        
        $data['data'] = 'rental';
        $query = BikeMaster::where('status',1)->whereNotNull('modal')->orderBy('sort_order','asc');
        $bike_models = $query->get();
        $bike_models_arr = [];
        foreach ($bike_models as $key => $bike_models_row) {
            if($bike_models_row->modal){
            $bike_models_arr[] = array(
                'id' =>$bike_models_row->id,
                'modal' =>$bike_models_row->modal,
                 );
            }
        }

        $data['bike_models'] = $bike_models_arr;

        // prd($data['bike_models']);
        $city = $cityData->id ?? '';
        $data['locations'] = $cityData->locations ?? [];

        $data['seo_data'] = $seo_data;
        $data['faqs'] = $faqs;
View::share('seo_data', $seo_data);
View::share('faqs', $faqs);

        $params = [];
        $search_data = CustomHelper::getSearchData('rental',$params);
        $search_data['city'] = $city;

        $data['search_data'] = $search_data;

        return Inertia::render('rental/bike', $data);  
    }

    public function ajaxSearchBike(Request $request) {

        $data = [];
        $limit = $this->limit;
        $date = date('Y-m-d');
        $city = $request->city?? '';
        $model = $request->model?? '';
        $key = $request->key?? '';
        $types = $request->types?? [];
        $location_arr = $request->locations?? [];
        $pickupDate = $request->pickupDate;
        $pickupDate = CustomHelper::DateFormat($pickupDate,'Y-m-d','d/m/Y');
        $dropOff = $request->dropDate;
        $dropOff = CustomHelper::DateFormat($dropOff,'Y-m-d','d/m/Y');
        //=====================

        $date1 = new DateTime($pickupDate);
        $date2 = new DateTime($dropOff);
        $total_days = $date1->diff($date2)->format("%a");
        $total_days = $total_days +1;

        $query = BikeMaster::where('status',1)->orderBy('sort_order','asc');
        if($types){
            $query->whereIn('type',$types);
        }   
        if($model){
            $query->where('modal','like','%'.$model.'%');
        }
        if($key){
            $query->where('name','like','%'.$key.'%');
        }  
        $query->whereHas('inventoryData',function($q3) use ($pickupDate,$dropOff,$city) {
            $q3->where(DB::raw('inventory - booked'), '>=', 1); 
            if($pickupDate && $dropOff) {
                $dropOff_date = date('Y-m-d', strtotime($dropOff));
                $period = CustomHelper::DateRange($pickupDate,$dropOff_date);
                $date_arr = [];
                foreach ($period as $key => $value) {
                    $date_arr[] = $value->format('Y-m-d');
                }
                $q3->whereIn('trip_date',$date_arr);
            } else if($pickupDate) {
                $q3->whereDate('trip_date',$pickupDate);
            }
            if($city){
                $q3->where('city_id',$city);
            }
        });
        $bikes = $query->get();

        $storage = Storage::disk('public');
        $path = 'bikes/';
        $bike_arr = [];
        $module_name = 'rental';
        $agent_group_id = '-1';
        $is_agent = Auth::user()->is_agent??0;
        if($is_agent==1) {
            $agent_group_id = Auth::user()->group_id??0;
        }
        foreach ($bikes as $key => $bike) {
            $bike_id = $bike->id;
            $src = asset(config('custom.assets').'/images/noimagebig.jpg');
            $image = $bike->image;
            if(!empty($image)){
                if($storage->exists($path.$image)){ 
                    $src = url('storage/'.$path.'thumb/'.$image);
                } 
            }
            $InventoriesQuery = BikeCityInventory::where('bike_id',$bike_id)->where(DB::raw('inventory - booked'), '>=', 1);
            if($city){
                $InventoriesQuery->where('city_id',$city);
            }

            $BikeCityInventories = $InventoriesQuery->get();
            if($BikeCityInventories){
                $bikeCity_arr=[];
                $city_ids=[];
                $price = 0;
                foreach ($BikeCityInventories as $key => $BikeCityInventory) {

                    $price+= $BikeCityInventory->price;
                    $city_id = $BikeCityInventory->city_id;


                        $bike_cites_query = BikeCities::whereJsonContains('bike', [(string)$bike_id]);              
                        $bike_cites_query->where('id',$city_id);
                        $bike_cites_exist = $bike_cites_query->count();

                        if($bike_cites_exist > 0){
                            if($city_id && (!in_array($city_id, $city_ids))){
                                $params = [];
                                $params['action'] = 'rental_booking';
                                $params['city_id'] = $city_id;
                                $params['bike_id'] = $bike_id;
                                $params['pickupDate'] = $pickupDate;
                                $params['dropDate'] = $dropOff;
                                $params['inventory'] = 1;

                                $check_inventory = CustomHelper::checkInventory($params);

                                if($check_inventory){
                                $priceData = BikecityPrice::where('city_id',$city_id)->where('bike_id',$bike_id)->first();
                                $price = $priceData->price??0;
                                $totalprice = $price*$total_days;
                                if($totalprice > 0){
                                    $bikeCity = BikeCities::where('id',$city_id)->first();

                                    $km_included = $bikeCity->km_included ?? 0;
                                    $total_km = $km_included*$total_days;

                                    $locationData = $bikeCity->locations ;
                                    $bikeCity_arr=[];
                                    foreach ($locationData as $locationRow) {
                                        $bikeCity_arr[] = array(
                                            'id' => $locationRow->id,
                                            'name' => $locationRow->name,
                                        );   
                                    }
                                    $publish_price = $totalprice;
                                    $search_price = $totalprice;

                                    $discount_category_id = $bikeCity->discount_category_id??'';
                                    $module_record_id = $bikeCity->id??'';
                                    $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group_id,$module_record_id);
                                    if($publish_price > $discount_amount) {
                                        $search_price = $publish_price - $discount_amount;
                                    }

                                    $bike_arr[] = array(
                                        'id' => $bike->id,
                                        'name' => $bike->name,
                                        'equivalent' => $bike->equivalent,
                                        'modal' => $bike->modal,
                                        'total_km' => $total_km,
                                        'city_id' => $city_id,
                                        'cities' => $bikeCity_arr,
                                        'publish_price' => $publish_price,
                                        'search_price' => $search_price,
                                        'src' => $src,
                                    );
                                }
                            }
                            $city_ids [] = $city_id ;
                        }
                    }             
                }
            }            
        }
        $data['bikeList'] = $bike_arr;
        $data['success'] = true;
        return response()->json($data);
    }
    public function detail() {
        $data = [];
        return Inertia::render('bike/details', $data);
    }
/* end of controller */
}
