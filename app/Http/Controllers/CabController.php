<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AirportCodesMaster;
use App\Models\CabRoute;
use App\Models\CabRouteGroup;
use App\Models\Destination;
use App\Models\PaymentGateway;
use App\Models\SeoMetaTag;
use App\Models\ThemeCategories;
use App\Models\Package;
use App\Models\Country;
use App\Models\CabCities;
use App\Models\CabRoutePrice;
use App\Models\CabRouteInventory;
use App\Models\UserWallet;
use App\Models\UserGstInfo;
use App\Models\Coupon;
use App\Models\CabAddons;
use App\Helpers\CustomHelper;
use App\Helpers\CabHelper;
use Inertia\Inertia;
use DB;
use Validator;
use Response;
use URL;
use Storage;

class CabController extends Controller  {

    private $theme;

    public function __construct() {
        if(!CustomHelper::isAllowedModule('cab')) {
            if (Auth::guard('admin')->check()) {
                $ADMIN_ROUTE_NAME = config('custom.ADMIN_ROUTE_NAME');
                return redirect(url($ADMIN_ROUTE_NAME));
            }
            return redirect(url(''));
        }
        CabHelper::getAPIBaseUrl();
        $this->theme = config('custom.themejs');
    }

    public function index() {
        $data = [];
        $seo_data = [];
        $destinationLists = CabCities::where('status',1)->where(function($query1) {
            $query1->where('is_airport',0);
            $query1->orWhereNull('is_airport');
        })->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['destinationLists'] = $destinationLists;

        $airportLists = CabCities::where('is_airport',1)->where('status',1)->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['airportLists'] = $airportLists;

        //Sightseening = 2
        $sightseeningDestinationLists = CabRoute::with('originCity')->where('status',1)->where('route_type',2)->whereNotNull('origin')->orderBy('name', 'asc')->groupBy('origin')->get()->sortBy('originCity.name')->take(100)->pluck('originCity.name','origin');
        // prd($sightseeningDestinationLists->toArray());
        $sightseeningDestinationLists_arr = [];
        if($sightseeningDestinationLists) {
            foreach($sightseeningDestinationLists as $key => $val) {
                $sightseeningDestinationLists_arr[] = [
                    'id' => $key,
                    'name' => $val,
                ];
            }
        }
        $data['sightseeningDestinationLists'] = $sightseeningDestinationLists_arr;

        //Sightseening = 2
        $routeOriginLocations = CabRoute::with('originCity')->where('status',1)->where('featured',1)->where('route_type',2)->whereNotNull('origin')->groupBy('origin')->orderBy('name', 'asc')->get();

        $originCityData = [];

        $dep = date('Y-m-d');
        $pre_date =  date('Y-m-d', strtotime($dep. ' -1 days'));
        $dep = CustomHelper::CabDateSelect($dep,$pre_date,'dep');
      

        $time= '08:00';
        foreach ($routeOriginLocations as $key => $routeOriginLocation) {

            $origin_id = $routeOriginLocation->origin ;
            $sightseeningDestinationLists_arr = [];
            $view_all_url = route('cab.routeSearchResult').'?tripType=2&from='.$origin_id.'&to=&dep='.$dep.'&time='.$time;

            $sightseeningDestinationLists = CabRoute::with('originCity')->where('status',1)->where('featured',1)->where('route_type',2)->where('origin',$origin_id)->whereNotNull('origin')->orderBy('name', 'asc')->get();
            $cab_ids = [];
            
            if($sightseeningDestinationLists) {
                foreach($sightseeningDestinationLists as $key => $val) {               
                    $dep = '';
                    $time = '';
                    $CabRouteToGroup = $val->CabRouteToGroup;
                    $CabRouteToGroupData = $val->CabRouteToGroup;
                    foreach ($CabRouteToGroupData as $key => $groupRow) {
                        $cab_data = $groupRow->cab_data ? json_decode($groupRow->cab_data) : [];
                        foreach ($cab_data as $key => $cab_row) {
                            $cab_ids[] = $cab_row->id?? 0;
                        }
                    }
                   $CabRouteToGroup = $val->CabRouteToGroup->pluck('id')->toArray();
                   $parseCabRoute = Self::parseCabRoute($val,$dep,$time,$CabRouteToGroup,$cab_ids);
                   if($parseCabRoute){
                     $sightseeningDestinationLists_arr[] = $parseCabRoute;
                   }
                }
            }

            $originCityData[] = [
                'view_all_url' => $view_all_url,
                'origin_name' => $routeOriginLocation->originCity->name ?? '',
                'data' => $sightseeningDestinationLists_arr,
            ];
        }

        $data['originSightSeenRouteData'] = $originCityData;

        $routeList = CabRoute::where('status',1)->where('route_type',2)->where('featured',1)->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['routeList'] = $routeList;
        $storage = Storage::disk('public');

        $data['data'] = 'cab';
        $identifier = 'cab';
        $seo_tags = SeoMetaTag::where(['identifier'=>$identifier,'status'=>1])->first();
        $banner_image = '';
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
        $data['bannerImage'] = $banner_image;
        $data['seo_data'] = $seo_data;
        // prd($data);
        return Inertia::render('cab/Home', $data);
    }


    public function sightseening() {
        $data = [];
        $seo_data = [];

        $destinationLists = CabCities::where('status',1)->where(function($query1) {
            $query1->where('is_airport',0);
            $query1->orWhereNull('is_airport');
        })->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['destinationLists'] = $destinationLists;

        $airportLists = CabCities::where('is_airport',1)->where('status',1)->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['airportLists'] = $airportLists;

        //Sightseening = 2
        $sightseeningDestinationLists = CabRoute::with('originCity')->where('status',1)->where('featured',1)->where('route_type',2)->whereNotNull('origin')->orderBy('name', 'asc')->groupBy('origin')->get()->sortBy('originCity.name')->take(100)->pluck('originCity.name','origin');
        // prd($sightseeningDestinationLists->toArray());
        $sightseeningDestinationLists_arr = [];
        if($sightseeningDestinationLists) {
            foreach($sightseeningDestinationLists as $key => $val) {
                $sightseeningDestinationLists_arr[] = [
                    'id' => $key,
                    'name' => $val,
                ];
            }
        }
        $data['sightseeningDestinationLists'] = $sightseeningDestinationLists_arr;

        $routeList = CabRoute::where('status',1)->where('featured',1)->where('route_type',2)->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['routeList'] = $routeList;
        $storage = Storage::disk('public');

        $identifier = 'cab';
        $seo_tags = SeoMetaTag::where(['identifier'=>$identifier,'status'=>1])->first();
        $banner_image = '';
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
        $data['bannerImage'] = $banner_image;
        $data['seo_data'] = $seo_data;
        // prd($data);
        return Inertia::render('cab/Home', $data);
    }

    function parsePackage($package, $storage, $package_path='packages/') {
        // prd($package);
        $packageData = [];
        if($package) {
            $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($package->image)) {
                if($storage->exists($package_path.$package->image)){
                    $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package->image);
                }
            }

            $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';

            $packageData = [
                'id' => $package->id,
                'url' => route($packageDetailName,['slug'=>$package->slug]),
                'isActivity' => $package->is_activity??0,
                'title' => CustomHelper::wordsLimit($package->title),
                'thumbSrc' => $packageThumbSrc,
                'nights' => $package->nights,
                'days' => $package->days,
                'price' => (int)$package->search_price??0,
            ];
        }
        return $packageData;
    }

    public function get_menu() {
        $resp = CabHelper::getMenu();

        $userId = Auth::user()->id??0;
        if($userId) {
            $user = Auth::user();
            $agentInfo = $user->agentInfo??[];
            $name = $user->name??'';
            if($agentInfo && $agentInfo->company_brand) {
                $name = $agentInfo->company_brand??'';
            }
            $balance = UserWallet::where('user_id',$userId)->sum('amount');
            $gstInfos = UserGstInfo::where('user_id',$userId)->orderBy('id','desc')->get();
            $userInfo = [
                'is_agent' => $user->is_agent??0,
                'name' => $name,
                'email' => $user->email??'',
                'phone' => $user->phone??'',
                'country_code' => $user->country_code??'',
                'balance' => $balance,
                'gstInfos' => $gstInfos,
                'MY_BOOKING_URL' => route('user.mybooking'),
                'MY_WALLET_URL' => route('user.myWallet'),
                'MY_PROFILE_URL' => route('user.profile'),
                'MY_FAVOURITE_URL' => route('user.myfavorite'),
                'LOGOUT_URL' => route('user.logout'),
            ];
            $resp['userInfo'] = $userInfo;
        }

        return Response::json($resp, 200);
    }

    public function searchDestinations(Request $request) {
        $success = false;
        $destinationLists = [];
        if($request->value) {
            $value = $request->value;
            $query = Destination::where('status',1);
            $query->where(function($q1) use($value) {
                $q1->Where('name','like','%'.$value.'%');
            });
            $destinationLists = $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
            $success = true;
        }
        return Response::json(array(
            'success' => true,
            'destinationLists' => $destinationLists
        ), 200);
    }

    public function search_cities(Request $request) {
        $success = false;
        $destinationLists = [];
        $value = $request->value??'';
        if($value) {
            $tripType = $request->tripType;
            $origin = $request->origin;
            if($tripType == 2) {
                $query = CabRoute::with('originCity')->where('status',1)->where('featured',1)->where('route_type',2)->whereNotNull('origin')->orderBy('name', 'asc');
                $query->whereHas('originCity',function($q1) use($value) {
                    $q1->where('name','like','%'.$value.'%');
                });
                $sightseeningDestinationLists = $query->groupBy('origin')->get()->sortBy('originCity.name')->take(100)->pluck('originCity.name','origin');
                // prd($sightseeningDestinationLists->toArray());
                $sightseeningDestinationLists_arr = [];
                if($sightseeningDestinationLists) {
                    foreach($sightseeningDestinationLists as $key => $val) {
                        $sightseeningDestinationLists_arr[] = [
                            'id' => $key,
                            'name' => $val,
                        ];
                    }
                }
                $destinationLists = $sightseeningDestinationLists_arr;
            } else {
                $query = CabCities::where('status',1);
                if($tripType == 3 && $origin == 'fromAirport') {
                    $query->where('is_airport',1);
                } else {
                    $query->where(function($q1){
                        $q1->where('is_airport',0);
                        $q1->orWhereNull('is_airport');
                    });
                }
                $query->where('name','like','%'.$value.'%');
                $destinationLists = $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
            }
            // prd($destinationLists);
            $success = true;
        }
        return Response::json(array(
            'success' => true,
            'destinationLists' => $destinationLists
        ), 200);
    }


    public function search_route(Request $request) {
        $success = false;
        $destinationLists = [];
        if($request->origin) {

            $origin = $request->origin;
            $query = CabRoute::where('status',1)->where('route_type',2);
            if($origin){
             $query->where('origin',$origin);
         }           
         $destinationLists = $query->orderBy('sort_order', 'asc')->limit(100)->get()->toArray();
         $success = true;
     }
     return Response::json(array(
        'success' => true,
        'destinationLists' => $destinationLists
    ), 200);
 }


    public function searchResult(Request $request) {
        // prd($request->toArray());
        $data = [];
        $errors = [];
        $cabroute = $request->cabroute??'';
        $tripType = $request->tripType??0;
        $pickupType = $request->pickupType??0;
        $adult = $request->adult??0;
        $children = $request->children??0;
        $data['title'] = 'Cab Search Result';
        $from = $request->from ?? '';
        $to = $request->to ?? '';
        $dep = $request->dep ?? '';
        $pre_date =  date('Y-m-d', strtotime($dep. ' -1 days'));
        $pre_date = CustomHelper::CabDateSelect($dep,$pre_date);
        $next_date =  date('Y-m-d', strtotime($dep. ' + 1 days'));
        $time = $request->time ?? '';
        $query = CabRoute::with('CabRouteGroup','CabRouteinventory')->where('status',1);
        if($tripType==0 || $tripType == 1) {
            $query->where('route_type',0);
        } else {
            if($tripType == 2 && empty($cabroute)) {
                $sightseen_url = route('cab.routeSearchResult').'?tripType='.$tripType.'&from='.$from.'&to='.$to.'&dep='.$dep.'&time='.$time.'&adult='.$adult.'&children='.$children;
                return redirect($sightseen_url);
            } else {
                $query->where('route_type',$tripType);
            }
        }
        $query->groupBy('cab_route_group_id')->orderBy('name', 'asc');
        /* ->whereHas('CabRouteinventory',function($q1) use($dep){
            $q1->where('cab_route_inventory.trip_date',$dep);
            $q1->where('cab_route_inventory.inventory','>',0);
        });*/

        if($cabroute){
            $query->where('id',$cabroute);
        } else {

            $origin  = $request->from??'';
            $destination  = $request->to??'';

            if($tripType == 3) {
                if($pickupType == 1) {
                    $destination  = $request->from??'';
                    $origin = $request->to??'';
                }
            }
            if($from) {
                $query->where('origin',$from);
            }
            if($to) {
                $query->where('destination',$to);
            }
        }
        /*echo $query->toSql();*/
        $cab_routes = $query->limit(100)->get();
        $cab_list = [];
        $cab_sharing = [];
        $cab_exclusive = [];
        $new_cab_list = [];
        $cab_ids = [];
        $sort_arr = [];

        $cab_route_cabs = [];
        $tripTimeArr = config('custom.tripTimeArr');
        foreach ($cab_routes as $key => $cab_route) {
            $module_name = 'cab';
            $module_record_id = $cab_route->id;
            $discount_category_id = $cab_route->discount_category_id;
            $agent_group_id = '-1';
            $is_agent = Auth::user()->is_agent??0;
            if($is_agent==1) {
                $agent_group_id = Auth::user()->group_id??0;
            }

            $CabRouteinventory = $cab_route->CabRouteinventory;
            $from = $cab_route->origin ??'';
            $to = $cab_route->destination ??'';
            $CabRouteToGroup = $cab_route->CabRouteToGroup?$cab_route->CabRouteToGroup:'';
            if($CabRouteToGroup) {

                foreach ($CabRouteToGroup as $CabRouteGroup) {
                    $cab_id_arr = [];
                    if($CabRouteGroup->cab_data) {
                        $cab_jsondata = json_decode($CabRouteGroup->cab_data);
                        if($cab_jsondata) {
                            foreach($cab_jsondata as $row) {
                                $cab_id_arr[] = $row->id;
                                $cab_id = $row->id;
                                $cab_data = CabHelper::getCabData($cab_id);
                                $cab_route_price = CabHelper::getCabRoutePrice($cab_id,$cab_route->id,$CabRouteGroup->id);
                                $cab_price = $cab_route_price->price??0;
                                if($tripType == 1) {
                                    $cab_price = $cab_route_price->round_trip_price??0;
                                }

                                $publish_price = $cab_price;
                                $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group_id,$module_record_id);
                                if($publish_price > $discount_amount) {
                                    $cab_price = $publish_price - $discount_amount;
                                }

                                $equivalent = $cab_data->equivalent??'';
                                $sort_order = $cab_data->sort_order??0;
                                $status = $cab_data->status??0;

                                $cab_route_cab_id= $cab_route->id.'_'.$CabRouteGroup->id.'_'.$cab_id;
                                $inventory = 0;
                                $CabRouteInventory =  CabRouteInventory::where('trip_date',$dep)->where('cab_route_group_id',$CabRouteGroup->id)->where('cab_id',$cab_id)->first();
                                $inventory = $CabRouteInventory->inventory ?? 0;
                                $booked = $CabRouteInventory->booked ?? 0;
                                $sold = 1;
                                if($inventory > 0 && ($inventory > $booked) && $cab_price > 0){
                                    $sold = 0;
                                }

                                if($CabRouteGroup->sharing == 1){
                                    $cab_sharing[]  = array(
                                        'cab_route_id' => $cab_route->id??'',
                                        'cab_route_group' => $CabRouteGroup->id??'',
                                        'id' => $cab_id??'',
                                        'name' => $cab_data->name??'',
                                        'image' => $cab_data->image??'',
                                        'sort_order' => $cab_data->sort_order??0,
                                        'publish_price' => $publish_price??'',
                                        'price' => $cab_price??'',
                                        'equivalent' => $equivalent??'',
                                        'sharing' => $CabRouteGroup->sharing??'',
                                        'route_sharing' => $cab_route->sharing??'',
                                        'sold' =>$sold,
                                        'start_time' => $cab_route->start_time?$tripTimeArr[$cab_route->start_time]:'',
                                    );

                                }else{

                                    if($sold == 0){
                                        $cab_exclusive[]  = array(
                                            'cab_route_id' => $cab_route->id??'',
                                            'cab_route_group' => $CabRouteGroup->id??'',
                                            'id' => $cab_id??'',
                                            'name' => $cab_data->name??'',
                                            'image' => $cab_data->image??'',
                                            'sort_order' => $cab_data->sort_order??0,
                                            'publish_price' => $publish_price??'',
                                            'price' => $cab_price??'',
                                            'equivalent' => $equivalent??'',
                                            'sharing' => $CabRouteGroup->sharing??'',
                                            'route_sharing' => $cab_route->sharing??'',
                                            'sold' =>$sold,
                                            'start_time' => $cab_route->start_time?$tripTimeArr[$cab_route->start_time]:'',
                                        );
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }

        $cab_list = array_merge($cab_exclusive,$cab_sharing);

        $places = '';
        $description = '';
        $duration = '';
        $distance = '';
        $inclusion = '';
        $exclusion = '';
        $term = '';
        if($cabroute) {
            $cab_route = CabRoute::find($cabroute);

            $sightseeing_name = $cab_route->name ??'';
            $sightseeing_id = $cab_route->id ??'';
            $to = '';
            $places = $cab_route->places ??'';
            $description = $cab_route->description ??'';
            $duration = $cab_route->duration ??'';
            $distance = $cab_route->distance ??'';

            $cab_route_group_id = $cab_route->cab_route_group_id ?? 0;
            if($cab_route_group_id) {
                $cabRouteGroup = CabRouteGroup::where('id',$cab_route_group_id)->first();
                $inclusion = $cabRouteGroup->inclusion ?? '';
                $exclusion = $cabRouteGroup->exclusion ?? '';
                $term = $cabRouteGroup->term ?? '';
            }
        }

        $routeInfos = [];
        $fromCity   = CabCities::where('id',$from)->first();

        $fromCity_name =$fromCity->name ?? '';
        $toCity     = CabCities::where('id',$to)->first();

        $pre_url = '';
        if($pre_date){
            $pre_url = url('/cab/list').'?tripType='.$tripType.'&from='.$from.'&to='.$to.'&dep='.$pre_date.'&time='.$time.'&adult='.$adult.'&children='.$children;
            if($cabroute){
                $pre_url.= '&cabroute='.$cabroute;
            }
            if($pickupType){
                $pre_url.= '&pickupType='.$pickupType;
            }
        }

        $next_url = '';
        if($next_date){
            $next_url =url('/cab/list').'?tripType='.$tripType.'&from='.$from.'&to='.$to.'&dep='.$next_date.'&time='.$time.'&adult='.$adult.'&children='.$children;
            if($cabroute){
                $next_url.= '&cabroute='.$cabroute;
            }
            if($pickupType){
                $next_url.= '&pickupType='.$pickupType;
            }
        }

        $routeInfos[0] = [
            "fromCity" => [
                "name" => $fromCity->name ?? '',
                "id" => $fromCity->id ?? '',
            ],
            "toCity" => [
                "name" => $toCity->name ?? '',
                "id" => $toCity->id ?? '',
            ],
            "travelDate" => $dep,
            "travelTime" => $time,
            "sightseening" =>[
                "name" => $sightseeing_name ?? '',
                "id" => $sightseeing_id ?? '',
                "duration" => $duration ?? '',
                "distance" => $distance ?? '',
                "inclusion" => $inclusion ?? '',
                "exclusion" => $exclusion ?? '',
                "term" => $term ?? '',
            ],
            "places" =>$places ??'',
            "description" =>$description ? $description :'',
            "adult" =>$adult ? $adult :'',
            "children" =>$children ? $children :'',
            "pre_url" => $pre_url,
            "next_url" =>$next_url,

        ];
        // prd($routeInfos);

        $searchResult = [];
        $searchResult['ONWARD'] = $cab_list;
        $data['routeInfos'] = $routeInfos;
        $data['searchResult'] = $searchResult;
        $data['form_data'] = $request->toArray();
        $data['url'] = URL::full();
        $destinationLists = CabCities::where('status',1)->where(function($query1){
            $query1->where('is_airport',0);
            $query1->orWhereNull('is_airport');
        })->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['destinationLists'] = $destinationLists;
        $routeList = CabRoute::where('status',1)->where('route_type',2)->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['routeList'] = $routeList;
        $airportLists = CabCities::where('is_airport',1)->where('status',1)->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['airportLists'] = $airportLists;

        $sightseeningDestinationLists = CabRoute::with('originCity')->where('status',1)->where('route_type',2)->whereNotNull('origin')->orderBy('name', 'asc')->groupBy('origin')->get()->sortBy('originCity.name')->take(100)->pluck('originCity.name','origin');
        // prd($sightseeningDestinationLists->toArray());
        $sightseeningDestinationLists_arr = [];
        if($sightseeningDestinationLists) {
            foreach($sightseeningDestinationLists as $key => $val) {
                $sightseeningDestinationLists_arr[] = [
                    'id' => $key,
                    'name' => $val,
                ];
            }
        }
        $data['sightseeningDestinationLists'] = $sightseeningDestinationLists_arr;
        $data['tripType'] = $tripType;
        $data['metaTitle'] = 'Cab Search Result';
        $data['metaDescription'] = 'Cab Search Result';

        $addons_arr = [];
        $addons = CabAddons::where('status',1)->where('is_deleted',0)->orderBy("sort_order", "asc")->orderBy("id", "desc")->get();
        if($addons) {
            foreach($addons as $row) {
                $addons_arr[] = CabAddons::getRecord($row);
            }
        }
        $addons = [];
        $addons['data'] = $addons_arr;
        $data['addons'] = $addons;
        // prd($data);
        return Inertia::render('cab/CabSearch', $data);
    }

  public function routeSearch(Request $request) {
        $data = [];
        $errors = [];
        $cabroute = $request->cabroute??'';
        $tripType = $request->tripType??0;
        $pickupType = $request->pickupType??0;
        $adult = $request->adult??0;
        $children = $request->children??0;

        $data['title'] = 'Cab Search Result';
        $from = $request->from ?? '';
        $to = $request->to ?? '';
        $dep = $request->dep ?? '';
       
        $pre_date =  date('Y-m-d', strtotime($dep. ' -1 days'));

        //========
        $pre_date = CustomHelper::CabDateSelect($dep,$pre_date);
        //========

        $next_date =  date('Y-m-d', strtotime($dep. ' + 1 days'));
        $time = $request->time ?? '08:00';

        $query = CabRoute::with('CabRouteGroup','CabRouteinventory')->where('status',1);
        if($tripType==0 || $tripType == 1) {
            $query->where('route_type',0);
        } else {
            $query->where('route_type',$tripType);
        }
        $query->groupBy('cab_route_group_id')->orderBy('name', 'asc')->whereHas('CabRouteinventory',function($q1) use($dep){
            $q1->where('cab_route_inventory.trip_date',$dep);
            $q1->where('cab_route_inventory.inventory','>',0);
        });

        if($cabroute){
            $query->where('id',$cabroute);
        } else {

           $origin  = $request->from??'';
           $destination  = $request->to??'';

            if($tripType == 3) {
                if($pickupType == 1) {
                    $destination  = $request->from??'';
                    $origin = $request->to??'';
                }
            }
            if($from) {
                $query->where('origin',$from);
            }
            if($to) {
                $query->where('destination',$to);
            }
        }
        /*echo $query->toSql();*/
        $cab_routes = $query->limit(100)->get();
        $cab_list = [];
        $new_cab_list = [];
        $cab_ids = [];
        $sort_arr = [];

        $cab_route_cabs = [];
                       
        $places = '';
        $description = '';
        if($cabroute) {
            $cab_route = CabRoute::find($cabroute);
            $sightseeing_name = $cab_route->name ??'';
            $sightseeing_id = $cab_route->id ??'';
            $to = '';
            $places = $cab_route->places ??'';
            $description = $cab_route->description ??'';

        }

        $routeInfos = [];
        $fromCity   = CabCities::where('id',$from)->first();

        $fromCity_name =$fromCity->name ?? '';
        $toCity     = CabCities::where('id',$to)->first();

        $routeInfos[0] = [
            "fromCity" => [
                "name" => $fromCity->name ?? '',
                "id" => $fromCity->id ?? '',
            ],
            "toCity" => [
                "name" => $toCity->name ?? '',
                "id" => $toCity->id ?? '',
            ],
            "travelDate" => $dep,
            "travelTime" => $time,
            "sightseening" =>[
                "name" => $sightseeing_name ?? '',
                "id" => $sightseeing_id ?? '',
            ],
            "places" => $places ??'',
            "description" => $description ? $description :'',
            "adult" => $adult ? $adult :0,
            "children" => $children ? $children :0,
        ];

        $book_url = url('/cab/list').'?tripType='.$tripType.'&from='.$from.'&to='.$to.'&dep='.$dep.'&time='.$time.'&adult='.$adult.'&children='.$children;
 
        $searchResult = [];
        $searchResult['ONWARD'] = $cab_list;
        $data['routeInfos'] = $routeInfos;
        $data['searchResult'] = $searchResult;
        $data['form_data'] = $request->toArray();
        $data['url'] = URL::full();
        $destinationLists = CabCities::where('status',1)->where(function($query1){
            $query1->where('is_airport',0);
            $query1->orWhereNull('is_airport');
        })->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['destinationLists'] = $destinationLists;
        $routeList = CabRoute::where('status',1)->where('featured',1)->where('route_type',2)->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['routeList'] = $routeList;
        $airportLists = CabCities::where('is_airport',1)->where('status',1)->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['airportLists'] = $airportLists;
      

        //Sightseening = 2
        $sightseeningDestinationLists = CabRoute::with('originCity')->where('status',1)->where('route_type',2)->whereNotNull('origin')->orderBy('name', 'asc')->groupBy('origin')->get()->sortBy('originCity.name')->take(100)->pluck('originCity.name','origin');
        // prd($sightseeningDestinationLists->toArray());
        $sightseeningDestinationLists_arr = [];
        if($sightseeningDestinationLists) {
            foreach($sightseeningDestinationLists as $key => $val) {
                $sightseeningDestinationLists_arr[] = [
                    'id' => $key,
                    'name' => $val,
                ];
            }
        }
        $data['sightseeningDestinationLists'] = $sightseeningDestinationLists_arr;

        ///=========

        $originCitylists = CabRoute::with('originCity')->where('status',1)->where('route_type',2)->where('origin',$from)->whereNotNull('origin')->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->get();

        // prd($originCitylists);
        $originCityData = [
            'data' => [],
        ];

        if($originCitylists) {
            $cab_ids = [];
            $originCitylists_arr = [];
            $params = $routeInfos[0];
            foreach($originCitylists as $key => $val) {                
                $CabRouteToGroup = $val->CabRouteToGroup;
                $CabRouteToGroupData = $val->CabRouteToGroup;
                foreach ($CabRouteToGroupData as $key => $groupRow) {
                   
                    $cab_data = $groupRow->cab_data ? json_decode($groupRow->cab_data) : [];

                     foreach ($cab_data as $key => $cab_row) {

                        $cab_ids[] = $cab_row->id?? 0;
                    }
            }

                $CabRouteToGroup = $val->CabRouteToGroup->pluck('id')->toArray();
                
                $parseCabRoute = Self::parseCabRoute($val,$dep,$time,$CabRouteToGroup,$cab_ids, $params);

                if($parseCabRoute){
                    $originCitylists_arr[] = $parseCabRoute;
                }
         }

         // prd($cab_ids);

         // prd($originCitylists_arr);

        $originCityData = [
            'data' => $originCitylists_arr,
        ];

        }
        $data['originSightSeenRouteData'] = $originCityData;

        // prd($data['sightseeningDestinationLists']);

        $data['tripType'] = $tripType;
        $data['metaTitle'] = 'Cab Search Result';
        $data['metaDescription'] = 'Cab Search Result';
        // prd($data);
        return Inertia::render('cab/RouteSearch', $data);
    }


    public function parseCabRoute($CabRoutedata,$dep='',$time='',$CabRouteToGroup=[],$cab_ids=[], $params=[]) {
        $sightseeningDestinationLists_arr = [];
        if($CabRoutedata && $CabRoutedata->id) {
            if(empty($dep)) {
                $dep = date('Y-m-d');
                $pre_date =  date('Y-m-d', strtotime($dep. ' -1 days'));
                $dep = CustomHelper::CabDateSelect($dep,$pre_date,'dep');
            }
            if(empty($time)) {
                $time = '08:00';
            }
            if($CabRoutedata->route_type==2) {
                if(isset($CabRoutedata->slug)) {
                    $slug = $CabRoutedata->slug;
                } else {
                    $slug = CustomHelper::slugify($CabRoutedata->name);
                }
                $book_url = route('cab.cabRouteResult',[$slug]).'?tripType=2&from='.$CabRoutedata->origin.'&to=&dep='.$dep.'&time='.$time.'&cabroute='.$CabRoutedata->id;
            } else {
                $book_url = url('/cab/list').'?tripType=2&from='.$CabRoutedata->origin.'&to=&dep='.$dep.'&time='.$time.'&cabroute='.$CabRoutedata->id;
            }

            if(isset($params['adult'])) {
                $book_url = $book_url.'&adult='.$params['adult'];
            }
            if(isset($params['children'])) {
                $book_url = $book_url.'&children='.$params['children'];
            }
            $image = $CabRoutedata->image;
            $storage = Storage::disk('public');
            $path = 'cab_route/';
            $image_url = asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($image)){
                if($storage->exists($path.$image)){
                    $image_url=  url('storage/'.$path.'thumb/'.$image);
                }
            }
            /*$query = CabRoutePrice::where('cab_route_id',$CabRoutedata->id)->whereHas('CabData',function($q1){
                $q1->where('status',1);
            });
            if($CabRouteToGroup) {
                $query->whereIn('cab_route_group_id',$CabRouteToGroup);
            }
            if($cab_ids){
                $query->whereIn('cab_id',$cab_ids);

            }
            $cab_price_data = $query->where('price','>',0)->orderBy('price', 'asc')->first();
            $min_price = $cab_price_data->price?? 0;*/
            
            if($CabRoutedata->search_price > 0) {
                $sightseeningDestinationLists_arr = [
                    'id' => $CabRoutedata->id,
                    'name' => $CabRoutedata->name,
                    'url' => $CabRoutedata->id,
                    'places' => $CabRoutedata->places,
                    'duration' => $CabRoutedata->duration,
                    'distance' => $CabRoutedata->distance ? $CabRoutedata->distance :'',
                    'description' => $CabRoutedata->description,
                    'sharing' => $CabRoutedata->sharing,
                    'publish_price' => $CabRoutedata->publish_price??0,
                    'search_price' => $CabRoutedata->search_price??0,
                    'book_url' => $book_url,
                    'image_url' => $image_url,
                ];
            }
        }
        return $sightseeningDestinationLists_arr;
    }

    public static function sort_order($sort_arr,$sort_order) {
        if(in_array($sort_order,$sort_arr)) {
            $new_sort_order = $sort_order + 1;            
            return self::sort_order($sort_arr,$new_sort_order);
        } else {
           return $sort_order;
        }
    }

    public function addons(Request $request) {
        $data = [];
        $addons_arr = [];
        $addons = CabAddons::where('status',1)->where('is_deleted',0)->orderBy("sort_order", "asc")->orderBy("id", "desc")->get();
        if($addons) {
            foreach($addons as $row) {
                $addons_arr[] = CabAddons::getRecord($row);
            }
        }
        $addons = [];
        $addons['data'] = $addons_arr;
        $data['addons'] = $addons;

        $method = $request->method();
        if($method == 'POST') {
            $response = [];
            $response['success'] = false;
            $response['message'] = '';
            $post_data = $request->toArray();
            $nicknames = [];
            $rules = [];
            $validation_msg = [];
            $validation_msg['required'] = ':attribute is required.';
            if($addons_arr) {
                foreach($addons_arr as $addon) {
                    if(isset($post_data['addons_'.$addon['id']])) {
                        $qty = $post_data['qty_'.$addon['id']]??0;
                        if(empty($qty)) {
                            $rules['qty_'.$addon['id']] = 'required';
                            $validation_msg['qty_'.$addon['id'].'.required'] = 'Qty is required.';
                        }
                        if($addon['daily_rental']==1) {
                            $days = $post_data['days_'.$addon['id']]??0;
                            if(empty($days)) {
                                $rules['days_'.$addon['id']] = 'required';
                                $validation_msg['days_'.$addon['id'].'.required'] = 'Days is required.';
                            }
                        }
                    }
                }
            }
            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
            if ($validator->fails()) {
                $errors = $validator->getMessageBag()->toArray();
                return Response::json(array(
                    'success' => false,
                    'errors' => $errors
                ), 400); // 400 being the HTTP code for an invalid request.
            } else {
                $selected_addons = [];
                if($addons_arr) {
                    foreach($addons_arr as $addon) {
                        if(isset($post_data['addons_'.$addon['id']])) {
                            $qty = $post_data['qty_'.$addon['id']]??1;
                            $price = $qty*$addon['price'];
                            $addon_row = [
                                'id' => $addon['id'],
                                'name' => $addon['name'],
                                'qty' => $qty,
                                'unit_price' => $addon['price'],
                                'price' => $price
                            ];
                            if($addon['daily_rental']==1) {
                                $days = $post_data['days_'.$addon['id']]??0;
                                if($days) {
                                    $price = $price * $days;
                                }
                                $addon_row['daily_rental'] = $addon['daily_rental'];
                                $addon_row['days'] = $days;
                                $addon_row['price'] = $price;
                            }
                            $selected_addons[] = $addon_row;
                        }
                    }
                }
                session(['selected_addons'=>$selected_addons]);
                $response['success'] = true;
                $redirect_url = route('cab.index');
                if(session()->has('addons_redirect_url')) {
                    $redirect_url = str_replace('/addons/', '/book/', session('addons_redirect_url'));
                }
                $response['redirect_url'] = $redirect_url;
                return Response::json($response, 200);
            }
        }
        $data['url'] = URL::full();
        session(['addons_redirect_url'=>$data['url']]);
        $tripType = $request->tripType??0;
        $cab_id = $request->cab ?? '';
        $cab_route_id = $request->cab_route_id ?? '';
        $cab_route_group = $request->cab_route_group ?? '';
        $dep = $request->dep ?? '';
        $time = $request->time ?? '';
        $routeData = CabRoute::where('id',$cab_route_id)->first();
        // prd($routeData->toArray());

        $cab_route_group_id =$request->cab_route_group ?? 0;
        $cabRouteGroup = CabRouteGroup::where('id',$cab_route_group_id)->first();

        if($routeData->sharing == 1 && $routeData->start_time && $cabRouteGroup->sharing == 1){
            
            $time = $routeData->start_time ? $routeData->start_time : $time;
        }
        //=====

        $currentHour = date('H');
        $Date = date('Y-m-d');
        $currentDate =  date('Y-m-d', strtotime($Date. ' +1 days'));    
         if($currentHour > 16){
            $currentDate =  date('Y-m-d', strtotime($Date. ' +2 days'));    
         }

         if($dep <= $currentDate){     
                $dep = $currentDate;  
            }

        //========

        $from = $routeData->origin ?? '';
        $to = $routeData->destination ?? '';
        $sightseening_name = $routeData->name ?? '';

        $fromCity = CabCities::where('id',$from)->first();
        $toCity = CabCities::where('id',$to)->first();

        $cab_route_price = CabHelper::getCabRoutePrice($cab_id,$cab_route_id,$cab_route_group);
        $cab_price = $cab_route_price->price ?? 0 ;
        if($tripType == 1) {
          $cab_price = $cab_route_price->round_trip_price??0;
        }

        $cab_data = CabHelper::getCabData($cab_id);
        $cab_route_types = config('custom.cab_route_types');

        $cab_name = $cab_data->name ?? '';
        $app_name = CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        $privacy_link = url('/terms-conditions');
        $terms_service_link = url('/terms-conditions');

        $inclusion = $cabRouteGroup->inclusion ?? '';
        $exclusion = $cabRouteGroup->exclusion ?? '';
        $term = $cabRouteGroup->term ?? '';

        $total_amount = $cab_price;
        $discount_amount = 0;
        //========
        // if(auth()->user() && auth()->user()->is_agent == 1){

            $discount_category_id = $routeData->discount_category_id ?? '' ;
            $module_reocrd_id = $routeData->id ?? '' ;
            if($discount_category_id != 0){
                // $group_id = (auth()->user()) ? auth()->user()->group_id : '';
                $group_id = '-1';
                $is_agent = Auth::user()->is_agent??0;
                if($is_agent==1) {
                    $group_id = Auth::user()->group_id??0;
                }
                /*$discount_result = CustomHelper::getDiscountData('cab',$discount_category_id,$cab_price,$group_id);
                $discount_type = $discount_result->discount_type ?? '';
                $discount_value = $discount_result->discount_value ?? '';
                if($discount_type && $discount_type == 'flat'){
                    $discount_amount = $discount_value;
                }elseif($discount_type && $discount_type == 'percentage'){
                    $discount_amount = ($totalPrice*$discount_value)/100 ;
                }
                if($discount_amount > $totalPrice){
                    $discount_amount = 0;
                }*/

               $module_name = 'cab';
               $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$module_reocrd_id);
               $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$group_id,$module_reocrd_id);
           }
       // }

       $totalPrice  = $cab_price - $discount_amount ;

        $routeInfo = [
            "fromCity" => [
                "name" => $fromCity->name ?? '',
                "id" => $fromCity->id ?? '',
            ],
            "toCity" => [
                "name" => $toCity->name ?? '',
                "id" => $toCity->id ?? '',
            ],
            "sightseening" => [
                "name" => $sightseening_name ?? '',
                "id" => $cab_route_id?? '',
            ],
            "travelDate" => $dep,
            "travelTime" => $time,
            "sub_price" => $cab_price,
            "price" => $totalPrice,
            "car_type" => $cab_name,
            "cab_id" => $cab_id,
            "cab_route_id" => $cab_route_id,
            "cab_route_group" => $cab_route_group,
            "tripType" => $cab_route_types[$tripType]??'',
            "tripTypeId" => $tripType??0,
            "app_name" => $app_name??0,
            "privacy_link" => $privacy_link??0,
            "terms_service_link" => $terms_service_link??0,
            "inclusion" => $inclusion??'',
            "exclusion" => $exclusion??'',
            "term" => $term??'',
            "agent_discount" => $discount_amount??'',
        ];

        $countryData = Country::where('status',1)->get();
        $data['countryData'] = $countryData;
        $data['routeInfo'] = $routeInfo;
        $data['tripType'] = $tripType;
        $data['metaTitle'] = 'Cab Booking';
        $data['metaDescription'] = 'Cab Booking';

        $userId = Auth::user()->id??0;
        $is_agent = Auth::user()->is_agent??0;
        $total_amount = $totalPrice;
        $module_type_id = 4; //Cab
        $coupons_arr = [];
        if($userId && $is_agent != 1 && !empty($module_type_id)) {
            $curr_date = date('Y-m-d');
            $coupons = Coupon::where('status',1)->whereJsonContains('modules',[(string)$module_type_id])->whereDate('start_date','<=',$curr_date)->whereDate('expiry_date','>=',$curr_date)->get();
            if($coupons) {
                foreach($coupons as $coupon) {
                    $discount_coupon = 0;
                    $couponDiscount = $coupon->discount;
                    $coupon_count = $coupon->couponUsedCount();
                    if($coupon->type == 'percentage') {
                        $discount_coupon = ($total_amount * ($couponDiscount/100));
                    } else {
                        $discount_coupon = $coupon->discount;
                    }
                    if($discount_coupon > $coupon->max_discount_amt) {
                        $discount_coupon = $coupon->max_discount_amt;
                    }
                    if($coupon_count < $coupon->use_limit && $total_amount >= $coupon->min_amount && $discount_coupon < $total_amount) {
                        $row = $coupon->toArray();
                        $row['discount_coupon'] = $discount_coupon;
                        $coupons_arr[] = $row;
                    }
                }
            }
        }
        $data['coupons'] = $coupons_arr;
        // prd($data['coupons']);

        if(!empty($addons_arr)) {
            return Inertia::render('cab/CabAddons', $data);
        } else {
            return Inertia::render('cab/CabBook', $data);
        }
    }

    public function book(Request $request) {
        $data = [];
        $data['url'] = URL::full();
        $tripType = $request->tripType??0;
        $cab_id = $request->cab ?? '';
        $cab_route_id = $request->cab_route_id ?? '';
        $cab_route_group = $request->cab_route_group ?? '';
        $dep = $request->dep ?? '';
        $time = $request->time ?? '';
        $adult = $request->adult ?? 0;
        $children = $request->children ?? 0;
        $routeData = CabRoute::where('id',$cab_route_id)->first();
        // prd($routeData->toArray());

        $cab_route_group_id =$request->cab_route_group ?? 0;
        $cabRouteGroup = CabRouteGroup::where('id',$cab_route_group_id)->first();

        if($routeData->sharing == 1 && $routeData->start_time && $cabRouteGroup->sharing == 1){
            
            $time = $routeData->start_time ? $routeData->start_time : $time;
        }
        //=====

        $currentHour = date('H');
        $Date = date('Y-m-d');
        $currentDate =  date('Y-m-d', strtotime($Date. ' +1 days'));    
         if($currentHour > 16){
            $currentDate =  date('Y-m-d', strtotime($Date. ' +2 days'));    
         }

         if($dep <= $currentDate){     
                $dep = $currentDate;  
            }

        //========

        $from = $routeData->origin ?? '';
        $to = $routeData->destination ?? '';
        $sightseening_name = $routeData->name ?? '';

        $fromCity = CabCities::where('id',$from)->first();
        $toCity = CabCities::where('id',$to)->first();

        $cab_route_price = CabHelper::getCabRoutePrice($cab_id,$cab_route_id,$cab_route_group);
        $cab_price = $cab_route_price->price ?? 0 ;
        if($tripType == 1) {
          $cab_price = $cab_route_price->round_trip_price??0;
        }

        $cab_data = CabHelper::getCabData($cab_id);
        $cab_route_types = config('custom.cab_route_types');

        $cab_name = $cab_data->name ?? '';
        $app_name = CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        $privacy_link = url('/terms-conditions');
        $terms_service_link = url('/terms-conditions');

        $inclusion = $cabRouteGroup->inclusion ?? '';
        $exclusion = $cabRouteGroup->exclusion ?? '';
        $term = $cabRouteGroup->term ?? '';

        $total_amount = $cab_price;
        $discount_amount = 0;
        //========
        // if(auth()->user() && auth()->user()->is_agent == 1){

            $discount_category_id = $routeData->discount_category_id ?? '' ;
            $module_reocrd_id = $routeData->id ?? '' ;
            if($discount_category_id != 0){
                // $group_id = (auth()->user()) ? auth()->user()->group_id : '';
                $group_id = '-1';
                $is_agent = Auth::user()->is_agent??0;
                if($is_agent==1) {
                    $group_id = Auth::user()->group_id??0;
                }
                /*$discount_result = CustomHelper::getDiscountData('cab',$discount_category_id,$cab_price,$group_id);
                $discount_type = $discount_result->discount_type ?? '';
                $discount_value = $discount_result->discount_value ?? '';
                if($discount_type && $discount_type == 'flat'){
                    $discount_amount = $discount_value;
                }elseif($discount_type && $discount_type == 'percentage'){
                    $discount_amount = ($totalPrice*$discount_value)/100 ;
                }
                if($discount_amount > $totalPrice){
                    $discount_amount = 0;
                }*/

               $module_name = 'cab';
               $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$module_reocrd_id);
               $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$group_id,$module_reocrd_id);
           }
       // }

        $selected_addons = [];
        if(session()->has('selected_addons')) {
            $selected_addons = session('selected_addons');
            if($selected_addons) {
                foreach($selected_addons as $selected_addon) {
                    $cab_price = $cab_price + $selected_addon['price'];
                }
            }
        }

       $totalPrice  = $cab_price - $discount_amount ;

        $routeInfo = [
            "fromCity" => [
                "name" => $fromCity->name ?? '',
                "id" => $fromCity->id ?? '',
            ],
            "toCity" => [
                "name" => $toCity->name ?? '',
                "id" => $toCity->id ?? '',
            ],
            "sightseening" => [
                "name" => $sightseening_name ?? '',
                "id" => $cab_route_id?? '',
            ],
            "travelDate" => $dep,
            "travelTime" => $time,
            "sub_price" => $cab_price,
            "price" => $totalPrice,
            "car_type" => $cab_name,
            "cab_id" => $cab_id,
            "cab_route_id" => $cab_route_id,
            "cab_route_group" => $cab_route_group,
            "tripType" => $cab_route_types[$tripType]??'',
            "tripTypeId" => $tripType??0,
            "app_name" => $app_name??0,
            "privacy_link" => $privacy_link??0,
            "terms_service_link" => $terms_service_link??0,
            "inclusion" => $inclusion??'',
            "exclusion" => $exclusion??'',
            "term" => $term??'',
            "agent_discount" => $discount_amount??'',
            "adult" => $adult??'',
            "children" => $children??'',
        ];

        if($selected_addons) {
            $routeInfo['selected_addons'] = $selected_addons;
        }
        // prd($routeInfo);

        $countryData = Country::where('status',1)->get();
        $data['countryData'] = $countryData;
        $data['routeInfo'] = $routeInfo;
        $data['tripType'] = $tripType;
        $data['metaTitle'] = 'Cab Booking';
        $data['metaDescription'] = 'Cab Booking';

        $userId = Auth::user()->id??0;
        $is_agent = Auth::user()->is_agent??0;
        $total_amount = $totalPrice;
        $module_type_id = 4; //Cab
        $coupons_arr = [];
        if($userId && $is_agent != 1 && !empty($module_type_id)) {
            $curr_date = date('Y-m-d');
            $coupons = Coupon::where('status',1)->whereJsonContains('modules',[(string)$module_type_id])->whereDate('start_date','<=',$curr_date)->whereDate('expiry_date','>=',$curr_date)->get();
            if($coupons) {
                foreach($coupons as $coupon) {
                    $discount_coupon = 0;
                    $couponDiscount = $coupon->discount;
                    $coupon_count = $coupon->couponUsedCount();
                    if($coupon->type == 'percentage') {
                        $discount_coupon = ($total_amount * ($couponDiscount/100));
                    } else {
                        $discount_coupon = $coupon->discount;
                    }
                    if($discount_coupon > $coupon->max_discount_amt) {
                        $discount_coupon = $coupon->max_discount_amt;
                    }
                    if($coupon_count < $coupon->use_limit && $total_amount >= $coupon->min_amount && $discount_coupon < $total_amount) {
                        $row = $coupon->toArray();
                        $row['discount_coupon'] = $discount_coupon;
                        $coupons_arr[] = $row;
                    }
                }
            }
        }
        $data['coupons'] = $coupons_arr;
        // prd($data['coupons']);
        return Inertia::render('cab/CabBook', $data);
    }
}