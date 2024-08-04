<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AirportCodesMaster;
use App\Models\PaymentGateway;
use App\Models\SeoMetaTag;
use App\Models\ThemeCategories;
use App\Models\Package;
use App\Models\UserWallet;
use App\Models\AirlineFaretype;
use App\Models\UserGstInfo;
use App\Models\AirlineRoute;
use App\Helpers\CustomHelper;
use App\Helpers\FlightHelper;
use Inertia\Inertia;
use DB;
use Validator;
use Response;
use URL;
use Storage;

class FlightController extends Controller  {

    private $theme;

    public function __construct() {
        if(!CustomHelper::isAllowedModule('flight')) {
            if (Auth::guard('admin')->check()) {
                $ADMIN_ROUTE_NAME = config('custom.ADMIN_ROUTE_NAME');
                return redirect(url($ADMIN_ROUTE_NAME));
            }
            return redirect(url(''));
        }
        FlightHelper::getAPIBaseUrl();
        $this->theme = config('custom.themejs');
    }

    public function index() {
        $data = [];
        $seo_data = [];
        $airportLists = AirportCodesMaster::where('featured',1)->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(100)->get()->toArray();
        $data['airportLists'] = $airportLists;

        $storage = Storage::disk('public');

        $tour_categories = ThemeCategories::where('status',1)->where('featured',1)->limit(10)->orderBy('sort_order','asc')->get();
        // prd($tour_categories->toArray());

        $tour_categories_arr = [];
        if($tour_categories) {
            foreach ($tour_categories as $theme) {
                $theme_path = "themes/";
                $themeTitle = CustomHelper::wordsLimit($theme->title);
                $themeSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                if(!empty($theme->image) && $storage->exists($theme_path.$theme->image)) {
                    $themeSrc = asset('/storage/'.$theme_path.$theme->image);
                }
                $row = [
                    'url' => route('tourCategoryDetail',[$theme->slug]),
                    'thumbSrc' => $themeSrc,
                    'title' => $themeTitle,
                ];
                $tour_categories_arr[] = $row;
            }
        }

        $data['tourCategories'] = [
            'url' => route('tourCategoryListing'),
            'data' => $tour_categories_arr
        ];
        // prd($data['tour_categories']);
        
        $activityPackages = Package::with('packagePrices')->where('is_activity',1)->where('status',1)->where('featured',1)->limit(10)->orderBy('featured','desc')->orderBy('sort_order','asc')->get();
        $activityPackages_arr = [];
        if($activityPackages) {
            foreach ($activityPackages as $package) {
                $activityPackages_arr[] = Self::parsePackage($package, $storage);
            }
        }
        $data['activityPackages'] = [
            'url' => route('activityListing'),
            'data' => $activityPackages_arr,
        ];
        // prd($data['activityPackages']);

        $featuredPackages = Package::with('packagePrices')->where('is_activity',0)->where('status',1)->where('featured',1)
        ->whereHas('packageFlags',function($q1) {
            $q1->where('flag_id',2);
        })->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();
        $featuredPackages_arr = [];
        if($featuredPackages) {
            foreach ($featuredPackages as $package) {
                $featuredPackages_arr[] = Self::parsePackage($package, $storage);
            }
        }
        $data['featuredPackages'] = [
            'url' => route('packageListing',['flag'=>2]),
            'data' => $featuredPackages_arr,
        ];
        // prd($data['featuredPackages']);


        $nonFeaturedPackages = Package::with('packagePrices')->where('is_activity',0)->where('status',1)->where('featured',1)
        ->whereHas('packageFlags',function($q1) {
            $q1->where('flag_id',1);
        })->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();
        $nonFeaturedPackages_arr = [];
        if($nonFeaturedPackages) {
            foreach ($nonFeaturedPackages as $package) {
                $nonFeaturedPackages_arr[] = Self::parsePackage($package, $storage);
            }
        }
        $data['nonFeaturedPackages'] = [
            'url' => route('packageListing',['flag'=>1]),
            'data' => $nonFeaturedPackages_arr,
        ];
        // prd($data['nonFeaturedPackages']);

        $identifier = 'flight';
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
        $data['data'] = $identifier;
        $data['bannerImage'] = $banner_image;
        $data['seo_data'] = $seo_data;

        return Inertia::render('flight/Home', $data);
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
        $resp = FlightHelper::getMenu();

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

        $airline_faretypes = \Cache::rememberForever("airline-airline_faretypes", function () {
            $airline_faretypes_arr = [];
            $airline_faretypes = AirlineFaretype::get();
            if($airline_faretypes) {
                foreach($airline_faretypes as $row) {
                    $airline_faretypes_arr[$row->api] = [
                        'fare_types' => $row->fare_types,
                        'description' => $row->description,
                        'color' => $row->color,
                        'ui' => $row->ui,
                        'api' => $row->api,
                    ];
                }
            }
            return $airline_faretypes_arr;
        });
        $resp['airline_faretypes'] = $airline_faretypes;

        return Response::json($resp, 200);
    }

    public function searchAirports_21feb2024(Request $request) {
        $success = false;
        $airportLists = [];
        if($request->value) {
            $value = addslashes($request->value);
            $query = AirportCodesMaster::where('status',1);
            $query->where(function($q1) use($value) {
                $q1->where('code','like','%'.$value.'%');
                $q1->orWhere('name','like','%'.$value.'%');
                $q1->orWhere('citycode','like','%'.$value.'%');
                $q1->orWhere('city','like',$value.'%');
            });
            $airportLists = $query->orderByRaw("IF(`code` = '$value', 1, 0)  DESC")->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(15)->get()->toArray();
            $success = true;
        }
        return Response::json(array(
            'success' => true,
            'airportLists' => $airportLists
        ), 200);
    }

    public function searchResult(Request $request) {
        // prd($request->toArray());
        $data = [];
        $data['form_data'] = $request->toArray();
        $errors = [];
        $fromCityOrAirport = $request->from??'';
        $toCityOrAirport = $request->to??'';
        $ADULT = $request->ADT??0;
        $CHILD = $request->CHD??0;
        $INFANT = $request->INF??0;
        $tripType = $request->tripType??0;
        $cabinClass = $request->pClass??'';

        $travelDateShown = '';

        $paxInfo = [];
        if($request->ADT) {
            $paxInfo['ADULT'] = $request->ADT;
        }
        if($request->CHD) {
            $paxInfo['CHILD'] = $request->CHD;
        }
        if($request->INF) {
            $paxInfo['INFANT'] = $request->INF;
        }

        $routeInfos = [];
        if($tripType == 2) {
            for ($counter=0; $counter < 10; $counter++) {
                # code...
                $origin = 'origin_'.$counter;
                $destination = 'destination_'.$counter;
                $depDate = 'depDate_'.$counter;
                if($request->$origin && $request->$destination && $request->$depDate) {
                    $travelDate = CustomHelper::DateFormat($request->$depDate,'Y-m-d','m/d/Y');
                    $fromCityOrAirport = AirportCodesMaster::where('code',$request->$origin)->first();
                    $toCityOrAirport = AirportCodesMaster::where('code',$request->$destination)->first();
                    if($fromCityOrAirport && $toCityOrAirport) {
                        $routeInfos[] = [
                            "fromCityOrAirport" => [
                                "code" => $fromCityOrAirport->code,
                                "city" => $fromCityOrAirport->city,
                                "country" => $fromCityOrAirport->country
                            ],
                            "toCityOrAirport" => [
                                "code" => $toCityOrAirport->code,
                                "city" => $toCityOrAirport->city,
                                "country" => $toCityOrAirport->country
                            ],
                            "travelDate" => $travelDate,            
                        ];
                    }
                    if(isset($routeInfos[$counter-1]) && isset($routeInfos[$counter-1]['travelDate']) && strtotime($travelDate) < strtotime($routeInfos[$counter-1]['travelDate'])) {
                        $errors[$depDate] = 'Travel dates must be in ascending order.';
                    }
                } else {
                    break;
                }
            }
        } else {
            $fromCityOrAirport = AirportCodesMaster::where('code',$request->from)->first();
            $toCityOrAirport = AirportCodesMaster::where('code',$request->to)->first();
            if($request->dep) {
                $travelDate = CustomHelper::DateFormat($request->dep,'Y-m-d','m/d/Y');
                $travelDateShown = CustomHelper::DateFormat($request->dep,'D, M dS Y','m/d/Y');
                if($fromCityOrAirport && $toCityOrAirport) {
                    $routeInfos[] = [
                        "fromCityOrAirport" => [
                            "code" => $fromCityOrAirport->code,
                            "city" => $fromCityOrAirport->city,
                            "country" => $fromCityOrAirport->country
                        ],
                        "toCityOrAirport" => [
                            "code" => $toCityOrAirport->code,
                            "city" => $toCityOrAirport->city,
                            "country" => $toCityOrAirport->country
                        ],
                        "travelDate" => $travelDate,            
                    ];
                }
            }
            if($request->ret) {
                $travelDate = CustomHelper::DateFormat($request->ret,'Y-m-d','m/d/Y');
                if($toCityOrAirport && $fromCityOrAirport) {
                    $routeInfos[] = [
                        "fromCityOrAirport" => [
                            "code" => $toCityOrAirport->code,
                            "city" => $toCityOrAirport->city,
                            "country" => $toCityOrAirport->country
                        ],
                        "toCityOrAirport" => [
                            "code" => $fromCityOrAirport->code,
                            "city" => $fromCityOrAirport->city,
                            "country" => $fromCityOrAirport->country
                        ],
                        "travelDate" => $travelDate,            
                    ];
                }
            }
        }
        // prd($routeInfos);
        // prd($errors);
        $totalFlights = 0;
        switch ($cabinClass) {
            case 'Premium Economy':
                    $searchCabinClass = 'PREMIUM_ECONOMY';
                break;
            case 'Business':
                    $searchCabinClass = 'BUSINESS';
                break;
            case 'First':
                    $searchCabinClass = 'FIRST';
                break;
            default:
                    $searchCabinClass = 'ECONOMY';
                break;
        }

        if(empty($routeInfos) || empty($paxInfo) || empty($searchCabinClass) ) {
            $errors[] = ['message'=>'Invalid request'];
        }
        if(empty($errors)) {
            $reqParams = [
                "searchQuery" => [
                    "cabinClass" => $searchCabinClass,
                    "paxInfo" => $paxInfo,
                    "routeInfos" => $routeInfos,
                    "searchModifiers" => [
                        "isDirectFlight" => false,
                        "isConnectingFlight" => false,
                    ],
                ],
            ];
            $endPoint = '/fms/v1/air-search-all';
            $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
            $apiResult = json_decode($resp, true);
            $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
            $success = !empty($apiResult)?$apiResult['status']['success']:'';

            $data['searchResult'] = [];
            
            if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                if(isset($apiResult['searchResult']['tripInfos']) && !empty($apiResult['searchResult']['tripInfos'])) {
                    $data['searchResult'] = $apiResult['searchResult']??[];
                    if(isset($data['searchResult']['tripInfos']['COMBO'])) {
                        $totalFlights = count($data['searchResult']['tripInfos']['COMBO']);
                    } else if(isset($data['searchResult']['tripInfos']['ONWARD'])) {
                        $totalFlights = count($data['searchResult']['tripInfos']['ONWARD']);
                    } else if(isset($data['searchResult']['tripInfos'][0])) {
                        $totalFlights = count($data['searchResult']['tripInfos'][0]);
                    }
                }
            } else {
                if(isset($apiResult['errors'])) {
                    $errors = $apiResult['errors'];
                    CustomHelper::captureSentryMessage('Flight searchResult errors: '.json_encode($errors));
                } else {
                    // prd($resp);
                }
            }
        }
        $data['errors'] = $errors;

        if($tripType == 0) {
            $userId = Auth::user()->id??0;
            $is_agent = Auth::user()->is_agent??0;
            if($is_agent == 1 && $userId) {
                $data['form_data']['agent_id'] = $userId;
            }
            $offlineSearchResult = AirlineRoute::getAirlineRoutes($data['form_data']);
            if(isset($offlineSearchResult['tripInfos']['ONWARD'])) {
                if(isset($data['searchResult']['tripInfos']['ONWARD'])) {
                    $data['searchResult']['tripInfos']['ONWARD'] = array_merge($offlineSearchResult['tripInfos']['ONWARD'],$data['searchResult']['tripInfos']['ONWARD']);
                } else {
                    $data['searchResult']['tripInfos']['ONWARD'] = $offlineSearchResult['tripInfos']['ONWARD'];
                }
                $totalFlights = count($data['searchResult']['tripInfos']['ONWARD']);
            }
        }
        // prd($data);

        // $source_data = AirportCodesMaster::where('code',$fromCityOrAirport)->first();
        // $destination_data = AirportCodesMaster::where('code',$toCityOrAirport)->first();

        // $data['sourceData'] = $source_data;
        // $data['destinationData'] = $destination_data;
        // $data['travelDateShown'] = $travelDateShown;
        $data['adult'] = $ADULT;
        $data['child'] = $CHILD;
        $data['infant'] = $INFANT;
        $data['paxInfo'] = $paxInfo;
        $data['routeInfos'] = $routeInfos;
        $data['cabinClass'] = $cabinClass;
        $data['tripType'] = $tripType;
        $data['totalFlights'] = $totalFlights;

        $query = AirportCodesMaster::where('featured',1)->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
        if(!empty($routeInfos) && count($routeInfos) > 0) {
            $query->orWhere(function($q1) use($routeInfos) {
                foreach($routeInfos as $routeInfo) {
                    $q1->orWhere('code',$routeInfo['fromCityOrAirport']);
                    $q1->orWhere('code',$routeInfo['toCityOrAirport']);
                }
            });
        }
        $airportLists = $query->limit(100)->get()->toArray();
        // prd($airportLists);
        $data['airportLists'] = $airportLists;
        $data['url'] = URL::full();
        $data['metaTitle'] = 'Flight Search Result';
        $data['metaDescription'] = 'Flight Search Result';

        $FLIGHT_URL = '';
        if(CustomHelper::isAllowedModule('flight')) {
            $FLIGHT_URL = route('flight.index');
        }
        $CAB_URL = '';
        if(CustomHelper::isAllowedModule('cab')) {
            $CAB_URL = route('cab.index');
        }
        $HOTEL_URL = route('hotelListing');
        $PACKAGE_URL = route('packageListing');
        $ACTIVITY_URL = route('activityListing');
        $data['FLIGHT_URL'] = $FLIGHT_URL;
        $data['CAB_URL'] = $CAB_URL;
        $data['HOTEL_URL'] = $HOTEL_URL;
        $data['PACKAGE_URL'] = $PACKAGE_URL;
        $data['ACTIVITY_URL'] = $ACTIVITY_URL;
        // prd($data);
        return Inertia::render('flight/Search', $data);
    }


    public function flightDetails($price_id) {
        // prd($price_id);

        $userId = Auth::user()->id??'';
        $is_agent = Auth::user()->is_agent??'';
        $agent_id = 0;
        if($is_agent == 1){
            $agent_id = Auth::user()->id??'';
        }

        $data = [];
        $data['tripInfos_errors'] = [];
        if(is_array($price_id)) {
            $priceIds = $price_id;
        } else {
            $priceIds = explode(',', $price_id);
        }
        $review_slug = CustomHelper::slugify(implode(',', $priceIds));
        /*$resp = \Cache::rememberForever("review-".$review_slug, function () use($priceIds) {
            $reqParams = [
                "priceIds" => $priceIds,
            ];
            $endPoint = '/fms/v1/review';
            $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
            return $resp;
        });*/
        $resp = CustomHelper::flightReviewDetails($review_slug, $priceIds);
        // prd($resp);

        $apiResult = json_decode($resp, true);
        $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
        $success = !empty($apiResult)?$apiResult['status']['success']:'';
        if (isset($statusCode) && $statusCode == 200  && isset($success)) {
            $data['tripInfos'] = $apiResult;
        } else {
            // pr("TripJack Api Response:");
            // prd($resp);
            // prd($apiResult);
            if(isset($apiResult['errors'])) {
                $data['tripInfos_errors'] = $apiResult['errors'];
                // prd($data['tripInfos_errors']);
            } else {
                prd($resp);
            }
        }
        // prd($data);
        $data['price_id'] = $priceIds;

        $data['payment_gateways'] = PaymentGateway::where(['status'=>1,'show'=> 1, 'is_online'=> 1])->orderBy('sort_order','ASC')->get();
        $data['metaTitle'] = 'Flight Details';
        $data['metaDescription'] = 'Flight Details';
        // prd($data);
        return Inertia::render('flight/Details', $data);
    }


    public function getFareDetails(Request $request) {
        $data = [];
        $price_id = $request->price_id;
        if(is_array($price_id)) {
            $priceIds = $price_id;
        } else {
            $priceIds = explode(',', $price_id);
        }
        $review_slug = CustomHelper::slugify(implode(',', $priceIds));
        /*$resp = \Cache::rememberForever("review-".$review_slug, function () use($priceIds) {
            $reqParams = [
                "priceIds" => $priceIds,
            ];
            $endPoint = '/fms/v1/review';
            $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
            return $resp;
        });*/
        $resp = CustomHelper::flightReviewDetails($review_slug, $priceIds);
        $apiResult = json_decode($resp, true);

        if (!isset($apiResult) && empty($apiResult)) {
            return Response::json(array(
                'success' => false,
                'errors' => '',
                'PriceDetail' => []
            ), 400); // 400 being the HTTP code for an invalid request.
        } else {
            return Response::json(array(
                'success' => true,
                'PriceDetail' => $apiResult
            ), 200);
        }
    }

    public function validatePassengers(Request $request) {
        $nicknames = [];
        $nicknames['price_id'] = 'Price ID';
        $rules = [];
        $rules['price_id'] = 'required';
        $rules['passengers'] = 'required';

        $price_id = $request->price_id;
        if(is_array($price_id)) {
            $priceIds = $price_id;
        } else {
            $priceIds = explode(',', $price_id);
        }
        $review_slug = CustomHelper::slugify(implode(',', $priceIds));
        /*$resp = \Cache::rememberForever("review-".$review_slug, function () use($priceIds) {
            $reqParams = [
                "priceIds" => $priceIds,
            ];
            $endPoint = '/fms/v1/review';
            $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
            return $resp;
        });*/
        $resp = CustomHelper::flightReviewDetails($review_slug, $priceIds);
        $apiResult = json_decode($resp, true);
        $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
        $success = !empty($apiResult)?$apiResult['status']['success']:'';
        if (isset($statusCode) && $statusCode == 200  && isset($success)) {
            $tripInfos = $apiResult;
        } else {
            $rules["tripInfos"] = 'required';
            $rules["tripInfos.required"] = $apiResult;
        }
        
        if(isset($tripInfos) && !empty($tripInfos) && $request->paxInfo) {
            $paxInfo = $request->paxInfo??[];
            $paxInfo_arr = [
                [
                    'key' => 'ADULT',
                    'title' => 'ADULT',
                    'years_title' => '(12 + yrs)'
                ],
                [
                    'key' => 'CHILD',
                    'title' => 'CHILD',
                    'years_title' => '(2 + yrs)'
                ],
                [
                    'key' => 'INFANT',
                    'title' => 'INFANT',
                    'years_title' => '(0-2 yrs)'
                ]
            ];

            foreach($paxInfo_arr as $paxType) {
                if($paxInfo[$paxType['key']]) {
                    for ($i=1; $i <= $paxInfo[$paxType['key']]; $i++) {
                        $rules["passengers.".$paxType['key'].$i."_title"] = 'required';
                        $rules["passengers.".$paxType['key'].$i."_first_name"] = 'required|regex:/^[\pL\s\-]+$/u';
                        $rules["passengers.".$paxType['key'].$i."_last_name"] = 'nullable|regex:/^[\pL\s\-]+$/u';

                        $nicknames["passengers.".$paxType['key'].$i."_title"] = 'Title';
                        $nicknames["passengers.".$paxType['key'].$i."_first_name"] = 'First Name';
                        $nicknames["passengers.".$paxType['key'].$i."_last_name"] = 'Last Name';
                        if($paxType['key']=='INFANT') {
                            $rules["passengers.".$paxType['key'].$i."_dob"] = 'required';
                            $nicknames["passengers.".$paxType['key'].$i."_dob"] = 'Date of Birth';
                        }
                        if(isset($tripInfos['conditions'])) {
                            if(isset($tripInfos['conditions']['pcs'])) {
                                $rules["passengers.".$paxType['key'].$i."_passport_nationality"] = 'required';
                                $nicknames["passengers.".$paxType['key'].$i."_passport_nationality"] = 'Nationality';
                                if($tripInfos['conditions']['pcs']['pped']) {
                                    $rules["passengers.".$paxType['key'].$i."_passport_exp_date"] = 'required';
                                    $nicknames["passengers.".$paxType['key'].$i."_passport_exp_date"] = 'Expiry Date';
                                }
                                if($tripInfos['conditions']['pcs']['pid']) {
                                    $rules["passengers.".$paxType['key'].$i."_passport_issue_date"] = 'required';
                                    $nicknames["passengers.".$paxType['key'].$i."_passport_issue_date"] = 'Issue Date';
                                }
                                if($tripInfos['conditions']['pcs']['pm']) {
                                    $rules["passengers.".$paxType['key'].$i."_passport_no"] = 'required';
                                    $nicknames["passengers.".$paxType['key'].$i."_passport_no"] = 'Passport Number';
                                }
                                if($tripInfos['conditions']['pcs']['dobe']) {
                                    $rules["passengers.".$paxType['key'].$i."_dob"] = 'required';
                                    $nicknames["passengers.".$paxType['key'].$i."_dob"] = 'Date of Birth';
                                } 
                            }
                            if($paxType['key']=='ADULT') {
                                $dob_key = 'adobr';
                            } else if($paxType['key']=='CHILD') {
                                $dob_key = 'cdobr';
                            } else if($paxType['key']=='ADULT') {
                                $dob_key = 'idobr';
                            }
                            if(isset($tripInfos['conditions']['dob'][$dob_key]) && !empty($tripInfos['conditions']['dob'][$dob_key])) {
                                $rules["passengers.".$paxType['key'].$i."_dob"] = 'required';
                                $nicknames["passengers.".$paxType['key'].$i."_dob"] = 'Date of Birth';
                            }
                        }
                    }
                }
            }
        } else {
            $rules['paxInfo'] = 'required';
        }

        $nicknames["contact_country_code"] = 'Country Code';
        $nicknames["contact_mobile"] = 'Mobile Number';
        $nicknames["contact_email"] = 'Email ID';

        $rules['contact_country_code'] = 'nullable';
        $rules['contact_mobile'] = 'required|digits:10';
        $rules['contact_email'] = 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';

        $nicknames["gst_number"] = "Registration Number";
        $nicknames["gst_company"] = "Registered Company Name";
        $nicknames["gst_email"] = "Registered Email";
        $nicknames["gst_phone"] = "Registered Phone";
        $nicknames["gst_address"] = "Registered Address";

        if(isset($tripInfos) && isset($tripInfos['conditions']) && $tripInfos['conditions']['gst']['igm']) {
            $rules['gst_number'] = 'required';
            $rules['gst_company'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['gst_email'] = 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            $rules['gst_phone'] = 'required|digits:10';
            $rules['gst_address'] = 'required';
        } else {
            $rules['gst_number'] = 'nullable';
            $rules['gst_company'] = 'nullable|regex:/^[\pL\s\-]+$/u|required_with:gst_number';
            $rules['gst_email'] = 'nullable|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/|required_with:gst_number';
            $rules['gst_phone'] = 'nullable|digits:10|required_with:gst_number';
            $rules['gst_address'] = 'nullable|required_with:gst_number';
        }

        $validation_msg = [];
        $validation_msg['required'] = ':attribute is required.';
        $validation_msg['regex'] = ':attribute is invalid.';
        $validation_msg['min'] = ':attribute should be minimum :min character.';
        $validation_msg['max'] = ':attribute should not be greater than :max character.';
        $validation_msg['digits'] = ':attribute must be :digits digits.';
        $validation_msg['date_format'] = ':attribute is invalid.';
        $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
        if ($validator->fails()) {
            $errors = $validator->getMessageBag()->toArray();
            CustomHelper::captureSentryMessage('Passengers validation failed with errors: '.json_encode($errors));
            return Response::json(array(
                'success' => false,
                'errors' => $errors
            ), 400); // 400 being the HTTP code for an invalid request.
        } else {
            return Response::json(array(
                'success' => true,
            ), 200);
        }
    }

}