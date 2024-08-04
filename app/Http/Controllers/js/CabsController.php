<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CabsCities;
use App\Models\CabsSightseeing;
use App\Models\CabsCityPrice;
use App\Models\SeoMetaTag;
use App\Models\Coupon;
use App\Models\Country;
use App\Helpers\CustomHelper;
use App\Helpers\CabHelper;
use Inertia\Inertia;
use DB;
use Validator;
use Response;
use URL;
use Storage;

class CabsController extends Controller  {

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
        $storage = Storage::disk('public');

        $cabsSightseeings = CabsSightseeing::with('originCity')->where('status',1)->where('featured',1)->orderBy('sort_order', 'asc')->get()->take(10);
        $cabsSightseeing_arr = [];
        foreach ($cabsSightseeings as $key => $cabsSightseeing) {
            $cabsSightseeing_arr[] = CabsSightseeing::parseCabsSightseeing($cabsSightseeing);
        }
        $data['cabsSightseeings'] = $cabsSightseeing_arr;

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
        $seo_data = $banner_image;
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);
        // prd($data);
        return Inertia::render('cab2/Home', $data);
    }


    public function searchFilters(Request $request) {
        // prd($request->toArray());
        $response = [];
        $response['success'] = false;
        $response['redirect_url'] = '';
        $search_data = $request->toArray();


        $cityId = $search_data['city']['id']??0;
        $distance = 0;
        $per_day_km = 0;
        $airport_max_distance = 0;
        $station_max_distance = 0;
        $cityData = [];
        if($cityId) {
            $cityRecord = CabsCities::find($cityId);
            if($cityRecord) {
                $cityData = CabsCities::parseCabsCity($cityRecord);
                $per_day_km = $cityData['per_day_km']??0;
                $airport_max_distance = $cityData['airport_max_distance']??0;
                $station_max_distance = $cityData['station_max_distance']??0;
            }
        }

        $pickupType = $request->pickupType??0;
        $tripType = $request->tripType??1;
        switch ($tripType) {
            case 1:
                    $origins = $search_data['pickup']['name']??'';
                    $destinations = $search_data['destination']['name']??'';
                    $resp = CustomHelper::getDirectionDistance($origins, $destinations);
                    if($resp['success']) {
                        $search_data['direction_resp'] = $resp;
                        $direction_data = $resp['rows'][0]['elements'][0]??[];
                        $distance_text = $direction_data['distance']['text']??'';
                        if($distance_text) {
                            $distance = str_replace([' km',','], ['',''], $distance_text);
                        }
                    }
                    $cityName = $search_data['city']['name']??'';
                    if($distance > $per_day_km) {
                        $response['message'] = 'Intercity transfer distance can not be more than '.$per_day_km.' kilometers. Kindly use Outstation.';
                    } else if(!empty($cityName) && stripos($origins, $cityName) !== false) {
                        $response['redirect_url'] = '/cab/intercity';
                        $response['success'] = true;
                    } else {
                        $response['message'] = 'Pickup Location must be within the boundaries of selected city.';
                    }
                break;
            case 2:
                    $origins = $search_data['pickup']['name']??'';
                    $destinations = $search_data['destination']['name']??'';
                    $resp = CustomHelper::getDirectionDistance($origins, $destinations);
                    if($resp['success']) {
                        $search_data['direction_resp'] = $resp;
                        $direction_data = $resp['rows'][0]['elements'][0]??[];
                        $distance_text = $direction_data['distance']['text']??'';
                        if($distance_text) {
                            $distance = str_replace([' km',','], ['',''], $distance_text);
                        }
                    }
                    $cityName = $search_data['city']['name']??'';
                    if(!empty($cityName) && stripos($origins, $cityName) !== false) {
                        $response['redirect_url'] = '/cab/outstation';
                        $response['success'] = true;
                    } else {
                        $response['message'] = 'Pickup Location must be within the boundaries of selected city.';
                    }
                break;
            case 3:
                    if($pickupType == 1) {
                        $origins = $search_data['pickup']['name']??'';
                        $destinations = $search_data['city']['name']??'';
                        $terminal = $search_data['terminal']['name']??'';
                        if($terminal) {
                            $destinations = $destinations.' airport, '.$terminal;
                            $resp = CustomHelper::getAddressLatLng($destinations);
                            if($resp['success']) {
                                $destination = [
                                    'name' => $destinations,//$resp['results'][0]['formatted_address']??$origins,
                                    'lat' => $resp['results'][0]['geometry']['location']['lat']??'',
                                    'lng' => $resp['results'][0]['geometry']['location']['lng']??'',
                                ];
                                $search_data['destination'] = $destination;
                            }
                        }
                    } else {
                        $origins = $search_data['city']['name']??'';
                        $terminal = $search_data['terminal']['name']??'';
                        if($terminal) {
                            $origins = $origins.' airport, '.$terminal;
                            $resp = CustomHelper::getAddressLatLng($origins);
                            if($resp['success']) {
                                $pickup = [
                                    'name' => $origins,//$resp['results'][0]['formatted_address']??$origins,
                                    'lat' => $resp['results'][0]['geometry']['location']['lat']??'',
                                    'lng' => $resp['results'][0]['geometry']['location']['lng']??'',
                                ];
                                $search_data['pickup'] = $pickup;
                            }
                        }
                        $destinations = $search_data['destination']['name']??'';
                    }
                    $resp = CustomHelper::getDirectionDistance($origins, $destinations);
                    if($resp['success']) {
                        $search_data['direction_resp'] = $resp;
                        $direction_data = $resp['rows'][0]['elements'][0]??[];
                        $distance_text = $direction_data['distance']['text']??'';
                        if($distance_text) {
                            $distance = str_replace([' km',','], ['',''], $distance_text);
                        }
                    }
                    if($distance > $airport_max_distance) {
                        $response['message'] = 'Airport transfer distance can not be more than '.$airport_max_distance.' kilometers. Kindly use Outstation.';
                    } else {
                        $response['redirect_url'] = '/cab/airport';
                        $response['success'] = true;
                    }
                break;
            case 4:
                    if($pickupType == 1) {
                        $origins = $search_data['pickup']['name']??'';
                        $destinations = $search_data['city']['name']??'';
                        $station = $search_data['station']['name']??'';
                        if($station) {
                            $destinations = $destinations.', '.$station;
                            $resp = CustomHelper::getAddressLatLng($destinations);
                            if($resp['success']) {
                                $destination = [
                                    'name' => $destinations,//$resp['results'][0]['formatted_address']??$origins,
                                    'lat' => $resp['results'][0]['geometry']['location']['lat']??'',
                                    'lng' => $resp['results'][0]['geometry']['location']['lng']??'',
                                ];
                                $search_data['destination'] = $destination;
                            }
                        }
                    } else {
                        $origins = $search_data['city']['name']??'';
                        $station = $search_data['station']['name']??'';
                        if($station) {
                            $origins = $origins.', '.$station;
                            $resp = CustomHelper::getAddressLatLng($origins);
                            if($resp['success']) {
                                $pickup = [
                                    'name' => $origins,//$resp['results'][0]['formatted_address']??$origins,
                                    'lat' => $resp['results'][0]['geometry']['location']['lat']??'',
                                    'lng' => $resp['results'][0]['geometry']['location']['lng']??'',
                                ];
                                $search_data['pickup'] = $pickup;
                            }
                        }
                        $destinations = $search_data['destination']['name']??'';
                    }
                    $resp = CustomHelper::getDirectionDistance($origins, $destinations);
                    if($resp['success']) {
                        $search_data['direction_resp'] = $resp;
                        $direction_data = $resp['rows'][0]['elements'][0]??[];
                        $distance_text = $direction_data['distance']['text']??'';
                        if($distance_text) {
                            $distance = str_replace([' km',','], ['',''], $distance_text);
                        }
                    }
                    if($distance > $station_max_distance) {
                        $response['message'] = 'Railway transfer distance can not be more than '.$station_max_distance.' kilometers. Kindly use Outstation.';
                    } else {
                        $response['redirect_url'] = '/cab/railway';
                        $response['success'] = true;
                    }
                break;
            case 5:
                    $response['redirect_url'] = '/cab/sightseeing';
                    $response['success'] = true;
                break;
            default:
                # code...
                break;
        }
        
        $dep = $request->dep??'';
        $time = $request->time??'';
        if($dep && $time) {
            $CAB_BOOKING_AFTER_HOURS = (int)CustomHelper::WebsiteSettings('CAB_BOOKING_AFTER_HOURS')??0;
            if($CAB_BOOKING_AFTER_HOURS > 0) {
                $response['success'] = false;
                $CAB_BOOKING_AFTER_TIME = date('Y-m-d H:i:s',strtotime('+'.$CAB_BOOKING_AFTER_HOURS.' hours'));
                if(strtotime($dep.' '.$time.':00') < strtotime($CAB_BOOKING_AFTER_TIME)) {
                    $response['message'] = 'Pickup Date & Time must be after '.$CAB_BOOKING_AFTER_HOURS.' hours from now.';
                } else {
                    $response['success'] = true;
                }
            }
            $return = $request->return??'';
            $return_time = $request->return_time??'';
            if($return && $return_time && $response['success'] == true) {
                $response['success'] = false;
                if(strtotime($return.' '.$return_time.':00') <= strtotime($dep.' '.$time.':00')) {
                    $response['message'] = 'End Date cannot be less than Travel Date & Time.';
                } else {
                    $response['success'] = true;
                }
            }
        }
        if($response['success']) {
            session(['searchdata'=>$search_data]);
        }
        return Response::json($response, 200);
    }

    public function searchResult(Request $request) {
        $data = [];
        $seo_data = [];

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
        $seo_data['banner_image'] = $banner_image;

        $tripType = $request->tripType??0;
        $pickupType = $request->pickupType??0;
        $returnEnabled = $request->returnEnabled??0;
        
        $data['title'] = '';

        $from = $request->from ?? '';
        $fromCityName = '';
        $fromCityId = '';
        $to = $request->to ?? '';
        $toCityName = '';
        $toCityId = '';
        $dep = $request->dep ?? date('Y-m-d', strtotime(date('Y-m-d'). ' + 7 days'));
        $time = $request->time ?? '10:00';
        $return = '';
        $return_time = '';

        $pre_date =  date('Y-m-d', strtotime($dep. ' -1 days'));
        $pre_date = CustomHelper::CabDateSelect($dep,$pre_date);
        $next_date =  date('Y-m-d', strtotime($dep. ' + 1 days'));
        
        $distance = 0;
        $distance_text = '';
        $duration_text = '';

        $sightseeingData = [];

        $routeInfos = [];
        $search_data = (session()->has('searchdata'))?session('searchdata'):[];
        $page_title = 'Cab Search Result';
        if(!empty($search_data)) {
            $tripType = $search_data['tripType']??1;
            $from = $search_data['city']['id']??'';
            $dep = $search_data['dep']??$dep;
            $time = $search_data['time']??$time;
            $returnEnabled = $search_data['returnEnabled']??0;
            $return = $search_data['return']??'';
            $return_time = $search_data['return_time']??'';
            $fromCityName = $search_data['pickup']['name']??'';
            $toCityName = $search_data['destination']['name']??'';
            $direction_resp = $search_data['direction_resp']??[];
            $direction_resp_success = $direction_resp['success']??'';
            if($direction_resp_success) {
                $direction_data = $direction_resp['rows'][0]['elements'][0]??[];
                $distance_text = $direction_data['distance']['text']??'';
                if($distance_text) {
                    $distance = str_replace([' km',','], ['',''], $distance_text);
                }
                $duration_text = $direction_data['duration']['text']??'';
                $page_title = 'Showing cabs from &nbsp;<b>'.$fromCityName.'</b> to &nbsp;<b>'.$toCityName.'</b>';
            }            

            if($tripType == 5) {
                $fromCityName = $search_data['city']['name']??'';
                $fromCityId = $search_data['city']['id']??'';
                $page_title = 'Showing cabs for Sightseeing in &nbsp;<b>'.$fromCityName.'</b>';
            }
            
        } else {
            if($request->sightseeing) {

            } else {
                return redirect(route('cab.index'));
            }
        }
        
        if($request->sightseeing) {
            $sightseeingData = CabsSightseeing::find($request->sightseeing);
            if($sightseeingData) {
                $data['sightseeingData'] = CabsSightseeing::parseCabsSightseeing($sightseeingData,['size'=>'detail']);
                $routeName = $sightseeingData->name;
                $from = $sightseeingData->origin;
                $distance = $sightseeingData->distance;
                $distance_text = $distance.' km';
                $duration_text = $sightseeingData->duration;
                $fromCityName = $sightseeingData->originCity->name??'';
                $page_title = 'Showing cabs for Sightseeing in &nbsp;<b>'.$fromCityName.'</b> with route <b>'.$routeName.'</b>';
                $tripType = 5;

                $search_data['tripType'] = $tripType;
                $search_data['city'] = [
                    'id' => $from,
                    'name' => $fromCityName
                ];
                $search_data['dep'] = $dep;
                $search_data['time'] = $time;
                $search_data['returnEnabled'] = $returnEnabled;
                $search_data['return'] = $return;
                $search_data['return_time'] = $return_time;
            }
            $search_data['sightseeing'] = $request->sightseeing;
        }
        session(['searchdata'=>$search_data]);

        $routeInfosRow = [
            "fromCity" => [
                "name" => $fromCityName??'',
                "id" => $fromCityId??'',
            ],
            "toCity" => [
                "name" => $toCityName??'',
                "id" => $toCityId??'',
            ],
            "travelDate" => $dep??'',
            "travelTime" => $time??'',
            "places" => $places??'',
            "description" => $description??'',
            "pre_url" => $pre_url??'',
            "next_url" => $next_url??'',
        ];
        if($returnEnabled) {
            $routeInfosRow['returnDate'] = $return;
            $routeInfosRow['returnTime'] = $return_time;
        }
        $routeInfos[] = $routeInfosRow;

        $seo_data['page_title'] = $page_title;
        $search_data['distance_text'] = $distance_text;
        $search_data['duration_text'] = $duration_text;

        $data_arr = [];
        $sightseeing_arr = [];
        if($tripType == 5 && empty($sightseeingData)) {
            $query = CabsSightseeing::where('status',1);
            $query->where(function($q1) {
                $q1->where('is_deleted',0);
                $q1->orWhereNull('is_deleted');
            });
            $query->where('origin',$from);
            $query->orderBy('sort_order', 'asc');
            $results = $query->limit(100)->get();
            if($results) {
                foreach ($results as $key => $row) {
                    $sightseeing_arr[] = CabsSightseeing::parseCabsSightseeing($row);
                }
            }
        } else {
            $query = CabsCities::with('cabsCityPrices')->where('status',1);
            $query->where(function($q1) {
                $q1->where('is_deleted',0);
                $q1->orWhereNull('is_deleted');
            });
            if($from) {
                $query->where('id',$from);
            }
            if($tripType == 3) {
                $query->where('is_airport',1);
            } else if($tripType == 4) {
                $query->where('is_railway',1);
            }
            $query->orderBy('sort_order', 'asc');
            $results = $query->limit(100)->get();
            if($results) {
                $params = [];
                $params['search_data'] = $search_data;
                $params['distance'] = $distance;
                $params['returnEnabled'] = $returnEnabled;
                $params['tripType'] = $tripType;
                foreach ($results as $key => $row) {
                    $cabsCityPrices = $row->cabsCityPrices??[];
                    if($cabsCityPrices) {
                        foreach($cabsCityPrices as $cabsCityPrice) {
                            $data_arr[]  = CabsCityPrice::parseCabsCityPrice($cabsCityPrice,$params);
                        }
                    }
                }
            }
        }
        $searchResult = [
            'data' => $data_arr,
            'sightseeing' => $sightseeing_arr,
        ];
        $data['searchResult'] = $searchResult;
        $data['routeInfos'] = $routeInfos;
        $data['search_data'] = $search_data;
        $data['seo_data'] = $seo_data;
        $data['TripType'] = $tripType;
        // prd($data);
        return Inertia::render('cab2/CabSearch', $data);
    }

    public function search_cities(Request $request) {
        $data = [];
        $value = $request->value??'';
        $tripType = $request->tripType??1;
        $cityId = $request->cityId??0;
        $action = $request->action??0;

        $query = CabsCities::where('status',1)->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
        $query->where(function($q1) {
            $q1->where('is_deleted',0);
            $q1->orWhereNull('is_deleted');
        });
        if($tripType == 1) {

        } else if($tripType == 2) {

        } else if($tripType == 3) {
            $query->where('is_airport',1);
        } else if($tripType == 4) {
            $query->where('is_railway',1);
        } else if($tripType == 5) {
            $query->withWhereHas('cabsSightseeings',function($q1){
                $q1->where(function($q2) {
                    $q2->where('is_deleted',0);
                    $q2->orWhereNull('is_deleted');
                });
                $q1->where('status',1);
            });
        }
        if($cityId) {
            $query->where('id', $cityId);
        }
        if($value) {
            $query->Where('name','like','%'.$value.'%');
        }
        $cabsCities = $query->limit(15)->get();
        // prd($cabsCities->toArray());
        $data_arr = [];
        if($cabsCities) {
            foreach($cabsCities as $cabsCity) {
                $data_arr[] = CabsCities::parseCabsCity($cabsCity);
            }
            // prd($data_arr);
            if($action == 'terminalLists') {
                $new_data_arr = [];
                if(!empty($data_arr)) {
                    foreach($data_arr as $data_row) {
                        if($data_row['terminals']) {
                            $terminals = json_decode($data_row['terminals'], true);
                            if(!empty($terminals)) {
                                foreach($terminals as $key => $terminal) {
                                    if($terminal) {
                                        $new_data_arr[] = [
                                            'id' => $terminal,
                                            'name' => $terminal,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                $data_arr = $new_data_arr;
            } else if($action == 'stationLists') {
                $new_data_arr = [];
                if(!empty($data_arr)) {
                    foreach($data_arr as $data_row) {
                        if($data_row['stations']) {
                            $stations = json_decode($data_row['stations'], true);
                            if(!empty($stations)) {
                                foreach($stations as $key => $station) {
                                    if($station) {
                                        $new_data_arr[] = [
                                            'id' => $station,
                                            'name' => $station,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                $data_arr = $new_data_arr;
            }
        }
        $data = $data_arr;
        $results = [
            'data' => $data,
        ];
        return Response::json(array(
            'success' => true,
            'results' => $results,
        ), 200);
    }

    public function book(Request $request) {
        $data = [];
        $data['url'] = URL::full();
        $price_id = $request->price_id??'';
        if($price_id) {
            $search_data = (session()->has('searchdata'))?session('searchdata'):[];
            if(!empty($search_data)) {
                $cabsCityPrice = CabsCityPrice::find($price_id);
                if($cabsCityPrice) {

                    $discount_amount = 0;
                    $discount_category_id = $cabsCityPrice->cityData->discount_category_id??'' ;
                    $module_record_id = $cabsCityPrice->cityData->id??'';

                    $cityData = $cabsCityPrice->cityData??[];
                    $tripType = $search_data['tripType']??1;
                    $from = $search_data['city']['id']??'';
                    $dep = $search_data['dep']??'';
                    $time = $search_data['time']??'';
                    $returnEnabled = $search_data['returnEnabled']??'';
                    $return = $search_data['return']??'';
                    $return_time = $search_data['return_time']??'';
                    $fromCityName = $search_data['pickup']['name']??'';
                    $toCityName = $search_data['destination']['name']??'';
                    $direction_resp = $search_data['direction_resp']??[];
                    // prd($direction_resp);
                    $distance = 0;
                    $direction_resp_success = $direction_resp['success']??'';
                    if($direction_resp_success) {
                        $direction_data = $direction_resp['rows'][0]['elements'][0]??[];
                        // $distance = $direction_data'distance']['value']??0;
                        $distance_text = $direction_data['distance']['text']??'';
                        if($distance_text) {
                            $distance = str_replace([' km',','], ['',''], $distance_text);
                        }
                        // $duration = $resp['duration']['value']??0;
                        $duration_text = $direction_data['duration']['text']??'';
                        // prd($distance);

                        $origin_addresses = $direction_resp['origin_addresses'][0];
                        $destination_addresses = $direction_resp['destination_addresses'][0];
                        $page_title = 'Showing cabs from &nbsp;<b>'.$fromCityName.'</b> to &nbsp;<b>'.$toCityName.'</b>';
                    }

                    if(isset($search_data['sightseeing'])) {
                        $sightseeingData = CabsSightseeing::find($search_data['sightseeing']);
                        if($sightseeingData) {
                            $routeId = $sightseeingData->id;
                            $routeName = $sightseeingData->name;
                            $from = $sightseeingData->origin;
                            $distance = $sightseeingData->distance;
                            $distance_text = $distance.' km';
                            $duration_text = $sightseeingData->duration;

                            $page_title = 'Showing cabs for Sightseeing in &nbsp;<b>'.$fromCityName.'</b> with route <b>'.$routeName.'</b>';

                            $discount_category_id = $sightseeingData->originCity->discount_category_id??'' ;
                            $module_record_id = $sightseeingData->originCity->id??'';
                        }
                    }

                    $app_name = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $cab_route_types = config('custom.cab_route_types');
                    $privacy_link = url('/terms-conditions');
                    $terms_service_link = url('/terms-conditions');

                    $params = [];
                    $params['tripType'] = $tripType;
                    $params['distance'] = $distance;
                    $params['returnEnabled'] = $returnEnabled;
                    $params['search_data'] = $search_data;
                    $cabData = CabsCityPrice::parseCabsCityPrice($cabsCityPrice,$params);
                    $cab_price = $cabData['price']??0;
                    $price_per_km = $cabData['fareDetails']['price_per_km']??0;

                    $group_id = '-1';
                    $is_agent = Auth::user()->is_agent??0;
                    if($is_agent==1) {
                        $group_id = Auth::user()->group_id??0;
                    }

                    $total_amount = $cab_price;

                    $module_name = 'cab';
                    $total_pax = 0;
                    $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$price_per_km,$group_id,$module_record_id,$total_pax);
                    $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$group_id,$module_record_id);

                    if($discount_amount && $distance) {
                        $discount_amount =  $discount_amount * ceil($distance);
                        if($discount_amount >= $cab_price) {
                            $discount_amount = 0;
                        }
                    }

                    $totalPrice  = $cab_price - $discount_amount;                    

                    $routeInfo = [
                        "price_id" => $price_id,
                        "fromCity" => [
                            "name" => $fromCityName,
                            "id" => '',
                        ],
                        "toCity" => [
                            "name" => $toCityName,
                            "id" => '',
                        ],
                        "sightseening" => [
                            "name" => $routeName ?? '',
                            "id" => $routeId?? '',
                        ],
                        "travelDate" => $dep,
                        "travelTime" => $time,

                        "tripType" => $cab_route_types[$tripType]??'',
                        "tripTypeId" => $tripType??0,
                        "app_name" => $app_name??0,
                        "privacy_link" => $privacy_link??0,
                        "terms_service_link" => $terms_service_link??0,
                        "inclusion" => $cityData->inclusions??'',
                        "exclusion" => $cityData->exclusions??'',
                        "term" => $cityData->terms??'',
                        "agent_discount" => $discount_amount??'',
                        "sub_price" => $cabData['price']??0,
                        "price" => $totalPrice??0,
                        "car_type" => $cabData['name']??'',
                    ];
                    if($returnEnabled) {
                        $routeInfo['returnDate'] = $return;
                        $routeInfo['returnTime'] = $return_time;
                    }
                    
                    if($tripType == 5) {
                        $fromCityName = $search_data['city']['name']??'';
                        $fromCityId = $search_data['city']['id']??'';
                        $routeInfo['fromCity'] = [
                            'name' => $fromCityName,
                            'id' => $fromCityId,
                        ];
                        $page_title = 'Showing cabs for Sightseeing in &nbsp;<b>'.$fromCityName.'</b>';
                    }

                    /*$tripType = $search_data['tripType']??1;
                    $dep = $search_data['dep']??'';
                    $time = $search_data['time']??'';
                    $cityData = $cabsCityPrice->cityData??[];

                    $currentHour = date('H');
                    $Date = date('Y-m-d');
                    $currentDate =  date('Y-m-d', strtotime($Date. ' +1 days'));    
                    if($currentHour > 16){
                        $currentDate =  date('Y-m-d', strtotime($Date. ' +2 days'));
                    }
                    if($dep <= $currentDate) {
                        $dep = $currentDate;  
                    }
                    $from = $cityData->origin??'';
                    $to = $cityData->destination??'';
                    $sightseening_name = $cityData->name??'';*/

                    /*$total_amount = $cab_price;
                    $discount_amount = 0;
                    $discount_category_id = $cityData->discount_category_id??'';
                    $module_reocrd_id = $cityData->id??'';
                    if($discount_category_id != 0) {
                        
                        $module_name = 'cab';
                        $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$module_reocrd_id);
                        $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$group_id,$module_reocrd_id);
                    }*/

                    $countryData = Country::where('status',1)->get();
                    $data['countryData'] = $countryData;
                    $data['routeInfo'] = $routeInfo;
                    $data['tripType'] = $tripType;
                    $data['metaTitle'] = 'Cab Booking';
                    $data['metaDescription'] = 'Cab Booking';

                    $userId = Auth::user()->id??0;
                    $is_agent = Auth::user()->is_agent??0;
                    $total_amount = $totalPrice;
                    $module_type_id = 4;
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
                    return Inertia::render('cab2/CabBook', $data);

                } else {
                    abort(404);
                }

            } else {
                return redirect(route('cab.index'));
            }

        } else {
            abort(404);
        }
    }    
}