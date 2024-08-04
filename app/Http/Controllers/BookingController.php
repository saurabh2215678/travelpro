<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\PackagePriceInfo;
use App\Models\PackageAirline;
use App\Models\Accommodation;
use App\Models\Destination;
use App\Models\Booking;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Coupon;
use App\Models\OrderPayments;
use App\Models\Country;
use App\Models\User;
use App\Models\Enquiry;
use App\Models\PaymentGateway;
use App\Models\OrderTraveller;
use App\Models\CabMaster;
use App\Models\CabRoute;
use App\Models\CabRouteGroup;
use App\Helpers\CustomHelper;
use App\Helpers\FlightHelper;
use App\Helpers\CabHelper;
use App\Models\EmailTemplate;
use App\Models\EnquiriesMaster;
use App\Models\AccommodationInventory;
use App\Models\UserWallet;
use App\Models\OrderInventory;
use App\Models\PackageInventory;
use App\Models\CabRouteInventory;
use App\Models\CabsSightseeing;
use App\Models\BikeCityInventory;
use App\Models\BikeCityLocation;
use App\Models\BikeMaster;
use App\Models\BikeCities;
use App\Models\SmsTemplate;
use App\Models\UserGstInfo;
use App\Models\BikecityPrice;
use App\Models\OrderServiceVoucher;
use App\Models\CabsCityPrice;
use App\Models\ApiCallLog;
use Razorpay\Api\Api;
use Response;
use Mail;
use Carbon\Carbon;
use DB;
use Validator;
use Storage;
use PDF;
use File;
use DateTime;

// namespace Worldline\Sips\Tests;

use PHPUnit\Framework\TestCase;
use Worldline\Sips\Common\Field\Contact;
use Worldline\Sips\Common\SipsEnvironment;
use Worldline\Sips\Paypage\InitializationResponse;
use Worldline\Sips\Paypage\PaypageRequest;
use Worldline\Sips\SipsClient;

class BookingController extends Controller {

	private $limit;

    public function __construct() {
    	$this->limit = 20;
    }

    public function booknow(Request $request) {
        // prd($request->toArray());
        $response = [];
        $response['success'] = false;
        $response['message'] = '';

        $data = [];
        $rules = [];

        $action = $request->action??'';
        if($action == 'hotel_booking') {
            $rules['inventory_id'] = 'required';
        } else if($action == 'rental_booking') {
            $rules['bike_id'] = 'required';
        } else {
            $rules['package'] = 'required';
            $rules['package_type'] = 'required';
            $rules['trip_date'] = 'required';
            $rules['trip_slot'] = 'nullable';
        }

        $message = [];
        $validator = Validator::make($request->all(), $rules,$message);
        if($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400); // 400 being the HTTP code for an invalid request.
        } else {
            $booking_data = $request->toArray();
            session(['booking_data'=>$booking_data]);
            $data['booking_data'] = $booking_data;
            $data['action'] = $action;
            $userId = Auth::user()->id??0;
            $is_agent = Auth::user()->is_agent??0;
            if($action == 'hotel_booking') {
                $inventory_data = AccommodationInventory::find($request->inventory_id);
                if($inventory_data) {
                    $accommodation = $inventory_data->Accommodation??[];
                    $data['accommodation'] = $accommodation;
                    $data['inventory_data'] = $inventory_data;

                    $checkin = '';
                    $checkout = '';
                    $inventory = 1;
                    $adult = 2;
                    $child = 0;
                    $infant = 0;
                    if($request->checkin) {
                        $checkin = CustomHelper::DateFormat($request->checkin,'Y-m-d','d/m/Y');
                        if($request->checkout) {
                            $checkout = CustomHelper::DateFormat($request->checkout,'Y-m-d','d/m/Y');
                        }
                        if($request->adult) {
                            $inventory = $request->inventory??1;
                            $adult = $request->adult??2;
                            $child = $request->child??0;
                            $infant = $request->infant??0;
                            $total_pax = (int)$adult + (int)$child + (int)$infant;
                            // if($total_pax > 0) {
                            //     $inventory = ceil($total_pax/3);
                            // }
                        }
                    }
                    $data['checkin'] = $checkin;
                    $data['checkout'] = $checkout;
                    $data['inventory'] = $inventory;
                    $data['adult'] = $adult;
                    $data['child'] = $child;
                    $data['infant'] = $infant;
                }
                if($userId && $is_agent != 1) {
                  $curr_date = date('Y-m-d');
                  $module_type_id = 5;
                  $coupons = Coupon::where('status',1)->whereJsonContains('modules',[(string)$module_type_id])->whereDate('start_date','<=',$curr_date)->whereDate('expiry_date','>=',$curr_date)->get();
                  $data['coupons'] = $coupons;
              }
            }  else if($action == 'rental_booking') {
                $module_type_id = 8;

                $pickupDate = $request->pickupDate;
                $pickupTime = $request->pickupTime;
                $dropTime = $request->dropTime;
                $pickupDate = CustomHelper::DateFormat($pickupDate,'Y-m-d','d/m/Y');

                $dropOff = $request->dropDate;
                $dropOff = CustomHelper::DateFormat($dropOff,'Y-m-d','d/m/Y');
                $date1 = new DateTime($pickupDate);
                $date2 = new DateTime($dropOff);

                $total_days = $date1->diff($date2)->format("%a");
                $total_days = $total_days +1;
                $bikeResult = BikeMaster::find($request->bike_id);
                $bike_arr= BikeMaster::parseBike($bikeResult,$total_days);

                $priceData = BikecityPrice::where('city_id',$request->city_id)->where('bike_id',$request->bike_id)->first();
                $price = $priceData->price ?? 0;
                $total_amount = $price*$total_days;

                $location_id = $request->location_id ?? '';
                $data['booking_data']['bike_id'] = $request->bike_id;
                $data['booking_data']['bike_data'] = $bike_arr;
                $data['booking_data']['total_km'] = $request->total_km ?? 0;
                $data['booking_data']['location_id'] = $location_id ?? '';
                $data['booking_data']['location_data'] = BikeCityLocation::find($location_id);
                $data['booking_data']['city_id'] = $request->city_id; 
                $data['booking_data']['city_data'] = BikeCities::find($request->city_id);
                $data['booking_data']['trip_date'] = $pickupDate ?? '';
                $data['booking_data']['pickupDate'] = $pickupDate.' '.$pickupTime;
                $data['booking_data']['dropDate'] = $dropOff.' '.$dropTime;

                // $total_amount = $request->price ;
                // prd($data);

            } else {
                $package = Package::find($request->package);

                $is_activity = $package->is_activity??'';
                $module_type_id = 1;
                if($is_activity == 1){
                    $module_type_id = 6;
                }
                if($userId && $is_agent != 1) {
                  $curr_date = date('Y-m-d');
                  $coupons = Coupon::where('status',1)->whereJsonContains('modules',[(string)$module_type_id])->whereDate('start_date','<=',$curr_date)->whereDate('expiry_date','>=',$curr_date)->get();
                  $data['coupons'] = $coupons;
                }

                $packagePrice = PackagePriceInfo::find($request->package_type);
                $data['package'] = $package;
                $data['packagePrice'] = $packagePrice;
                $data['package_id'] = $package->id??'';
                $data['package_type'] = $packagePrice->id??'';
            }
            if($is_agent == 1) {
                $userId = 0;
            }
            $userObject = [];
            if($userId) {
                $userObject = User::where('id',$userId)->first();
            }
            
            $data['userObject'] = $userObject;
            $data['countries'] = Country::where('status',1)->get();
            return view(config('custom.theme').'.packages.booking', $data);
        }
    }

    public function booking(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $statusCode = 400;

        $userId = Auth::user()->id??'';
        $is_agent = Auth::user()->is_agent??'';
        $approved_agent = Auth::user()->approved_agent??'';
        $group_id = Auth::user()->group_id??'';
        if(empty($group_id)) {
            $group_id = '-1';
        }
        $agent_id = 0;
        $created_by = 0;
        $created_by_name = '';

        $action = $request->action??'';
        $nicknames = [];
        $nicknames['action'] = 'Action';
        $rules = [];
        $rules['action'] = 'required';
        $validation_msg = [];

        $sub_total_amount = 0;
        $display_price = 0;
        $total_amount = 0;
        $trip_date = NULL;
        $total_pax = 0;
        $getWebId = EnquiriesMaster::where('status',1)->where(['type'=>'lead-source', 'slug'=>'website'])->first();
        if($action == 'package_booking') {
            $nicknames['name'] = 'Name';
            $nicknames['country_code'] = 'Country Code';
            $nicknames['phone'] = 'Phone';
            $nicknames['email'] = 'Email';
            $nicknames['address1'] = 'Address 1';
            $nicknames['address2'] = 'Address 2';
            $nicknames['city'] = 'City';
            $nicknames['state'] = 'State';
            $nicknames['zip_code'] = 'Zip Code';
            $nicknames['country'] = 'Country';
            $nicknames['comment'] = 'Comments';
            // $rules['trip_date'] = 'required';
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['country_code'] = 'nullable';
            // $rules['phone'] = 'required|min:4|max:12|regex:/^([0-9\s\-\+\(\)]*)$/';
            $rules['email'] = 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            // $rules['country'] = 'required|max:255';
            $rules['address1'] = 'nullable';
            $rules['address2'] = 'nullable';
            $rules['city'] = 'nullable|regex:/^[\pL\s\-]+$/u';
            $rules['state'] = 'nullable|regex:/^[\pL\s\-]+$/u';
            // $rules['zip_code'] = 'min:6|max:10|regex:/^[\s\w-]*$/';

            $phone = $request->phone??'';
            if($request->country_code) {
                $country_code = $request->country_code??91;
            } else if($request->country) {
                $country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
            } else {
                $country_code = 91;
            }
            if(empty($phone)) {
                $rules['phone'] = 'required';
            } else {
                if(!empty($country_code) && $country_code != 91) {
                    $rules['phone'] = 'regex:/^\d{4,12}$/';
                } else {
                    $rules['phone'] = 'digits:10';
                }
            }
            $zip_code = $request->zip_code??'';
            if($zip_code) {
                if(!empty($country_code) && $country_code != 91) {
                    $rules['zip_code'] = 'max:10|regex:/^[\s\w-]*$/';
                } else {
                    $rules['zip_code'] = 'digits:6';
                }
            }
            $rules['country'] = 'nullable';
            $rules['comment'] = 'nullable';
        } else if($action == 'hotel_booking') {
            $nicknames['name'] = 'Name';
            $nicknames['country_code'] = 'Country Code';
            $nicknames['phone'] = 'Phone';
            $nicknames['email'] = 'Email';
            $nicknames['address1'] = 'Address 1';
            $nicknames['address2'] = 'Address 2';
            $nicknames['city'] = 'City';
            $nicknames['state'] = 'State';
            $nicknames['zip_code'] = 'Zip Code';
            $nicknames['country'] = 'Country';
            $nicknames['comment'] = 'Comments';
            // $rules['trip_date'] = 'required';
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['country_code'] = 'nullable';
            // $rules['phone'] = 'required|min:4|max:12|regex:/^([0-9\s\-\+\(\)]*)$/';
            $rules['email'] = 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            // $rules['country'] = 'required|max:255';
            $rules['address1'] = 'nullable';
            $rules['address2'] = 'nullable';
            $rules['city'] = 'nullable|regex:/^[\pL\s\-]+$/u';
            $rules['state'] = 'nullable|regex:/^[\pL\s\-]+$/u';
            // $rules['zip_code'] = 'min:6|max:10|regex:/^[\s\w-]*$/';

            $phone = $request->phone??'';
            if($request->country_code) {
                $country_code = $request->country_code??91;
            } else if($request->country) {
                $country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
            } else {
                $country_code = 91;
            }
            if(empty($phone)) {
                $rules['phone'] = 'required';
            } else {
                if(!empty($country_code) && $country_code != 91) {
                    $rules['phone'] = 'regex:/^\d{4,12}$/';
                } else {
                    $rules['phone'] = 'digits:10';
                }
            }
            $zip_code = $request->zip_code??'';
            if($zip_code) {
                if(!empty($country_code) && $country_code != 91) {
                    $rules['zip_code'] = 'max:10|regex:/^[\s\w-]*$/';
                } else {
                    $rules['zip_code'] = 'digits:6';
                }
            }
            $rules['country'] = 'nullable';
            $rules['comment'] = 'nullable';
        } else if($action == 'flight_booking') {
            $nicknames['payment_method'] = 'Payment Method';
            $nicknames['price_id'] = 'Price ID';
            $nicknames['paxInfo'] = 'paxInfo';
            $nicknames['passengers'] = 'Passengers';
            $nicknames['name'] = 'Name';
            $nicknames['country_code'] = 'Country Code';
            $nicknames['phone'] = 'Phone';
            $nicknames['email'] = 'Email';
            $nicknames["gst_number"] = "Registration Number";
            $nicknames["gst_company"] = "Registered Company Name";
            $nicknames["gst_email"] = "Registered Email";
            $nicknames["gst_phone"] = "Registered Phone";
            $nicknames["gst_address"] = "Registered Address";

            $rules['payment_method'] = 'required';
            $rules['price_id'] = 'required';
            $rules['paxInfo'] = 'required';
            $rules['passengers'] = 'required';
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['country_code'] = 'nullable';
            $rules['phone'] = 'required|min:4|max:12';
            $rules['email'] = 'nullable|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            $rules['gst_number'] = 'nullable';
            $rules['gst_company'] = 'nullable|regex:/^[\pL\s\-]+$/u|required_with:gst_number';
            $rules['gst_email'] = 'nullable|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/|required_with:gst_number';
            $rules['gst_phone'] = 'nullable|digits:10|required_with:gst_number';
            $rules['gst_address'] = 'nullable|required_with:gst_number';

            if($request->price_id) {
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
                $apiStatusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                $success = !empty($apiResult)?$apiResult['status']['success']:'';
                if (isset($apiStatusCode) && $apiStatusCode == 200  && isset($success)) {
                    $tripInfos = $apiResult;
                    $trip_date = $tripInfos['tripInfos'][0]['sI'][0]['dt']??'';
                    if($trip_date) {
                        $trip_date = CustomHelper::DateFormat($trip_date,'Y-m-d H:i:s');
                    }
                    $total_amount = $tripInfos['totalPriceInfo']['totalFareDetail']['fC']['TF']??0;
                    if(empty($total_amount)) {
                        $rules['tripInfos'] = 'required';
                    }
                    $passengers = $request->passengers;

                    $hold_ticket = false;
                    $payment_method = $request->payment_method??'';
                    if($payment_method == 'hold') {
                        $isBA = $tripInfos['conditions']['isBA']??'';
                        if($is_agent && $isBA == 1) {
                            $hold_ticket = true;
                        } else {
                            $rules['tripInfos'] = 'required';
                        }
                    }
                } else {
                    $rules['tripInfos'] = 'required';
                }
            }
            if(isset($apiResult['errors'][0]['message'])) {
                $validation_msg['tripInfos.required'] = $apiResult['errors'][0]['message'];// 'The flight key has been expired, please search again.';
            } else {
                if(isset($apiResult)) {
                    $validation_msg['tripInfos.required'] = serialize($apiResult);// 'The flight key has been expired, please search again.';
                } else {
                    $validation_msg['tripInfos.required'] = 'required';
                }
            }
        } else if ($action == 'cab_booking') {
            $nicknames['name'] = 'Name';
            $nicknames['country_code'] = 'Country Code';
            $nicknames['phone'] = 'Phone';
            $nicknames['email'] = 'Email';
            // $rules['trip_date'] = 'required';
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['country_code'] = 'nullable';
            // $rules['phone'] = 'required|min:4|max:12|regex:/^([0-9\s\-\+\(\)]*)$/';
            $phone = $request->phone??'';
            if($request->country_code) {
                $country_code = $request->country_code??91;
            } else if($request->country) {
                $country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
            } else {
                $country_code = 91;
            }
            if(empty($phone)) {
                $rules['phone'] = 'required';
            } else {
                if(!empty($country_code) && $country_code != 91) {
                    $rules['phone'] = 'regex:/^\d{4,12}$/';
                } else {
                    $rules['phone'] = 'digits:10';
                }
            }
            $rules['email'] = 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            $rules['pickup'] = 'required';
            $rules['drop'] = 'required';
        } else if ($action == 'rental_booking') {
            $nicknames['name'] = 'Name';
            $nicknames['country_code'] = 'Country Code';
            $nicknames['phone'] = 'Phone';
            $nicknames['email'] = 'Email';
            // $rules['trip_date'] = 'required';
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['country_code'] = 'nullable';
            // $rules['phone'] = 'required|min:4|max:12|regex:/^([0-9\s\-\+\(\)]*)$/';
            $phone = $request->phone??'';
            if($request->country_code) {
                $country_code = $request->country_code??91;
            } else if($request->country) {
                $country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
            } else {
                $country_code = 91;
            }
            if(empty($phone)) {
                $rules['phone'] = 'required';
            } else {
                if(!empty($country_code) && $country_code != 91) {
                    $rules['phone'] = 'regex:/^\d{4,12}$/';
                } else {
                    $rules['phone'] = 'digits:10';
                }
            }
            $rules['email'] = 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
            // $rules['pickupDate'] = 'required';
            // $rules['dropDate'] = 'required';
        }
        $booking_for_other = $request->booking_for_other??0;
        if($booking_for_other == 1) {
            $nicknames['other_name'] = 'Name';
            $nicknames['other_country_code'] = 'Country Code';
            $nicknames['other_phone'] = 'Phone';
            $nicknames['other_email'] = 'Email';

            $rules['other_name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['other_country_code'] = 'nullable';
            $rules['other_phone'] = 'required|min:4|max:12';
            $rules['other_email'] = 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
        }
        $validation_msg['required'] = ':attribute is required.';
        $validation_msg['regex'] = ':attribute is invalid.';
        $validation_msg['digits'] = ':attribute must be :digits digits.';
        $validation_msg['min'] = ':attribute should be minimum :min character.';
        $validation_msg['max'] = ':attribute should not be greater than :max character.';
        $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);

        if($validator->fails()) {
            $errors = $validator->getMessageBag()->toArray();
            CustomHelper::captureSentryMessage(str_replace('_', ' ', ucfirst($action)).' validation failed with errors: '.json_encode($errors));
            return Response::json(array(
                'success' => false,
                'errors' => $errors
            ), $statusCode); // 400 being the HTTP code for an invalid request.
        } else {
            $discount_category_id = '';
            $discount_type = '';

            $name = $request->name??'';
            $country_code = $request->country_code??'';
            $phone = $request->phone??'';
            if(!empty($phone)) {
                $country_code = (!empty($country_code))?$country_code:'91';
            }
            $email = $request->email??'';
            $address1 = $request->address1??'';
            $address2 = $request->address2??'';
            $city = $request->city??'';
            $state = $request->state??'';
            $country = $request->country??'';
            if(!empty($country) && is_numeric($country)) {
                $country = CustomHelper::GetCountry($country,'name');
            }

            $zip_code = $request->zip_code??'';
            $comment = $request->comment??'';

            $userId = Auth::user()->id??'';
            if(empty($userId)) {
                $user_record = [];
                $user_record['name'] = $name;
                $user_record['country_code'] = $country_code;
                $user_record['phone'] = $phone;
                $user_record['email'] = $email;
                $params = [];
                $params['action'] = 'booking';
                $user = User::CreateUser($user_record, $params);
                if($user['success']) {
                    $userId = $user['id'];
                } else {
                    $response['message'] = $user['msg'];
                    return Response::json($response, $statusCode);
                }
            }

            $group_id = '-1';
            $agent_id = 0;

            $created_type = 'customer';
            $user = auth()->user();
            $user_id = $user->id??0;
            if($user_id) {
                $created_by = $user_id;
                $created_by_name = $user->name??'system';
                $is_agent = $user->is_agent??0;
                $approved_agent = $user->approved_agent??0;
                if($is_agent == 1 && $approved_agent == 1){
                    $agent_id = $user_id;
                    $created_type = 'agent';
                }
                $group_id = $user->group_id??'';
                if(empty($group_id)) {
                    $group_id = '-1';
                }
            } else {
                $created_by = $userId;
                $created_by_name = $name;
            }

            $adminUser = auth::guard('admin')->user();
            if($adminUser && $adminUser->id) {
                $created_type = 'backend';
                $created_by = $adminUser->id??0;
                $created_by_name = $adminUser->name??'system';
            }

            if($booking_for_other == 1) {
                $name = $request->other_name??'';
                $country_code = $request->other_country_code??'';
                $phone = $request->other_phone??'';
                if(!empty($phone)) {
                    $country_code = (!empty($country_code))?$country_code:'91';
                }
                $email = $request->other_email??'';
            }
            $price_category_choice_record = [];
            $formData = [];
            $formData['user_id'] = $userId;
            $formData['name'] = $name;
            $formData['country_code'] = $country_code;
            $formData['phone'] = $phone;
            $formData['email'] = $email;
            $formData['address1'] = $address1;
            $formData['address2'] = $address2;
            $formData['city'] = $city;
            $formData['state'] = $state;
            $formData['country'] = $country;
            $formData['zip_code'] = $zip_code;

            if($action == 'package_booking') {

                $booking_data = (session()->has('booking_data'))?session('booking_data'):[];
                if(isset($booking_data['trip_date']) && !empty($booking_data['trip_date'])){
                    $trip_date = CustomHelper::DateFormat($booking_data['trip_date'], 'Y-m-d', 'd/m/Y');
                    if(isset($booking_data['trip_slot']) && !empty($booking_data['trip_slot'])){
                        $trip_date = $trip_date.' '.$booking_data['trip_slot'];
                    }
                }

                $package = Package::find($booking_data['package']);
                $packagePrice = PackagePriceInfo::find($booking_data['package_type']);
                $packagePriceCategory = isset($package->packagePriceCategory) ? $package->packagePriceCategory:'';
                $is_activity = isset($package->is_activity) ? $package->is_activity:0;
                $priceCategoryElements = isset($packagePriceCategory->priceCategoryElements) ? $packagePriceCategory->priceCategoryElements:'';
                $category_choices_record = $packagePrice->category_choices_record??[];
                $category_choices_record_arr = [];
                if($category_choices_record) {
                    $category_choices_record_arr = json_decode($category_choices_record,true);
                }
                $sub_total_amount = $packagePrice->booking_price??0;
                if($is_agent == 1) {
                    $sub_total_amount = $packagePrice->booking_price_b2b??0;
                }
                if(!empty($priceCategoryElements) && count($priceCategoryElements) > 0) {
                    $sub_total_amount = 0;
                    foreach ($priceCategoryElements as $element) {
                        if(array_key_exists('pce'.$element->id, $booking_data)) {
                            $input_value = $booking_data['pce'.$element->id]??0;
                            $input_price = 0;
                            if(array_key_exists('pce'.$element->id, $category_choices_record_arr)) {
                                $input_price = $category_choices_record_arr['pce'.$element->id][$input_value]??0;
                            }
                            $sub_total = $input_value*$input_price;
                            $sub_total_amount += $sub_total;
                            $price_category_choice_record[] = [
                                'input_label' => $element->input_label,
                                'input_value' => $input_value,
                                'input_price' => $input_price
                            ];
                            $total_pax = $total_pax + (int)$input_value;
                        }
                    }
                }

                $params = [];
                $params['action'] = $action;
                $params['inventory'] = $total_pax;
                $params['package'] = $request->package;
                $params['package_type'] = $request->package_type;
                $params['trip_date'] = CustomHelper::DateFormat($booking_data['trip_date'], 'Y-m-d', 'd/m/Y');
                if($package && $package->is_activity == 1){     
                    $params['slot_time'] = $booking_data['trip_slot'] ?? '';
                }
                $check_inventory = CustomHelper::checkInventory($params);

                if($check_inventory) {
                    $total_amount = $sub_total_amount;
                    $package_id = $package->id??'';
                    $package_name = $package->title??'';
                    $package_type = $packagePrice->id??'';
                    $package_type_name = $packagePrice->title??'';
                    $discount_category_id = $package->discount_category_id??'';
                    $enquiry_for = 1;
                    $interaction_content = 'Package booking initiated by '.$created_type;
                    if($is_activity == 1){
                        $enquiry_for = 4;
                        $interaction_content = 'Activity booking initiated by '.$created_type;
                    }
                    $formData['enquiry_for'] = $enquiry_for; //custom.enq_for_types=Package
                    $formData['trip_date'] = $trip_date;
                    $formData['package_id'] = $package_id;
                    if($package->destination_id) {
                        $formData['destination'] = $package->destination_id;
                    }

                    $discount_amount = 0;
                    $discount_category_id = $package->discount_category_id??'';
                    $module_name = 'package_listing';
                    $module_record_id = $package->id??'';
                    $is_activity = $package->is_activity??'';
                    if($is_activity == 1) {
                        $module_name = 'activity_listing';
                    }
                    $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$module_record_id,$total_pax);
                    $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$group_id,$module_record_id);
                    $formData['discount_type'] = $discount_type ?? '';

                    $totalPrice  = $total_amount - $discount_amount;
                    $sub_total_amount = $total_amount;
                    $total_amount = $totalPrice;


                    $GST_TAX = 0;
                    $TCS_TAX = 0;
                    $GST_TAX = CustomHelper::WebsiteSettings('PACKAGE_GST_TAX');
                    $TCS_TAX = CustomHelper::WebsiteSettings('PACKAGE_TCS_TAX');

                    $grand_total = $total_amount;
                    if($GST_TAX) {
                        $gst_amount = ($grand_total * $GST_TAX) / 100;
                        $total_amount = $total_amount + $gst_amount;
                    }
                    if($TCS_TAX) {
                        $tcs_amount = ($grand_total * $TCS_TAX) / 100;
                        $total_amount = $total_amount + $tcs_amount;
                    }
                    $formData['sub_total_amount'] = $sub_total_amount??0;
                    $formData['total_amount'] = $total_amount??0;
                    $formData['comment'] = $comment;
                    $formData['followup_date'] = date('Y-m-d H:i:s');
                    $formData['interaction_content'] = $interaction_content;
                } else {
                    return Response::json(array(
                        'success' => false,
                        'message' => "We're sold out for your dates",
                        'errors' => $validator->getMessageBag()->toArray()
                    ), $statusCode); 
                }
            } else if($action == 'rental_booking') {
                $booking_data = (session()->has('booking_data'))?session('booking_data'):[];
                $city_id = $booking_data['city_id'] ?? 0;
                $bike_id = $booking_data['bike_id'] ?? 0;
                $location_id = $booking_data['location_id'] ?? 0;
                $pickupDate = $booking_data['pickupDate'] ?? 0;
                $pickupTime = $booking_data['pickupTime'] ?? 0;
                $pickupDate = CustomHelper::DateFormat($pickupDate,'Y-m-d','d/m/Y');
                $dropOff = $booking_data['dropDate'];

                $trip_date = $pickupDate.' '.$pickupTime;

                $dropOff = CustomHelper::DateFormat($dropOff,'Y-m-d','d/m/Y');
                $city_data = BikeCities::where('id',$city_id)->first();
                $bike_data = BikeMaster::where('id',$bike_id)->first();

                $params = [];
                $params['action'] = $action;
                $params['city_id'] = $city_id;
                $params['bike_id'] = $bike_id;
                $params['pickupDate'] = $pickupDate;
                $params['dropDate'] = $dropOff;
                $params['inventory'] = 1;

                $check_inventory = CustomHelper::checkInventory($params);

                if($check_inventory) {

                    $formData['enquiry_for'] = 7;
                    $date1 = new DateTime($pickupDate);
                    $date2 = new DateTime($dropOff);
                    $total_days = $date1->diff($date2)->format("%a");
                    $total_days = $total_days +1;
                    $priceData = BikecityPrice::where('city_id',$city_id)->where('bike_id',$bike_id)->first();

                    $booking_data['duration']= $total_days.' Days';

                    $total_amount = $priceData->price*$total_days;
                    $sub_total_amount = $priceData->price*$total_days;
                    $formData['sub_total_amount'] = $total_amount;
                    $formData['total_amount'] = $total_amount;

                    $discount_amount = 0;
                    $discount_category_id = $city_data->discount_category_id??'';
                    $module_name = 'rental';
                    $module_record_id = $city_data->id??'';
                    $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$module_record_id,$total_pax);
                    $totalPrice  = $total_amount - $discount_amount;
                    $sub_total_amount = $total_amount;
                    $total_amount = $totalPrice;

                } else {
                    if(empty($check_inventory_message)) {
                        $check_inventory_message = "We're sold out for your dates";
                    }

                    $response['message'] = $check_inventory_message;
                    return Response::json($response, $statusCode);
                }
            } else if($action == 'hotel_booking') {
                $booking_data = (session()->has('booking_data'))?session('booking_data'):[];
                $checkin = '';
                $checkout = '';
                $inventory = 1;
                $adult = 2;
                $child = 0;
                $infant = 0;

                if(isset($booking_data['checkin']) && !empty($booking_data['checkin'])) {
                    $checkin = CustomHelper::DateFormat($booking_data['checkin'], 'Y-m-d', 'd/m/Y');
                    $trip_date = $checkin;
                    if(isset($booking_data['checkout']) && !empty($booking_data['checkout'])) {
                        $checkout = CustomHelper::DateFormat($booking_data['checkout'], 'Y-m-d', 'd/m/Y');
                    }
                    if(isset($booking_data['adult']) && !empty($booking_data['adult'])) {
                        $inventory = $booking_data['inventory']??1;
                        $adult = $booking_data['adult']??2;
                        $child = $booking_data['child']??0;
                        $infant = $booking_data['infant']??0;
                        $total_pax = (int)$adult + (int)$child + (int)$infant;
                    }
                }

                $params = [];
                $params['action'] = $action;
                $params['inventory_id'] = $booking_data['inventory_id'];
                $params['checkin'] = $checkin;
                $params['checkout'] = $checkout;
                $params['inventory']= $booking_data['inventory']??1;
                $check_inventory = CustomHelper::checkInventory($params);

                if($check_inventory) {
                    $inclusions = [];
                    $inventory_data = AccommodationInventory::find($booking_data['inventory_id']);
                    if($inventory_data) {
                        $module_record_id = $inventory_data->Accommodation->id??0;
                        $discount_category_id = $inventory_data->Accommodation->discount_category_id??'';
                        if($inventory_data->plan_id) {
                            $room_plan = $inventory_data->AccommodationPlan??[];
                            if($room_plan) {
                                $includes = [];
                                $options = [];
                                if($room_plan->plan_json_data) {
                                    $plan_json_data = json_decode($room_plan->plan_json_data)??[];
                                    $includes = $plan_json_data->includes??[];
                                    $options = $plan_json_data->options??[];
                                }
                                if($includes) {
                                    foreach($includes as $include) {
                                        $inclusions[] = CustomHelper::showRoomPlanInclude($include)??'';
                                    }
                                }
                                if($options) {
                                    foreach($options as $option) {
                                        $inclusions[] = $option??'';
                                    }
                                }
                            }
                        } else {
                            $room = $inventory_data->AccommodationRoom??[];
                            if($room) {
                                $room_features = [];
                                if($room->room_features) {
                                    $room_features = json_decode($room->room_features)??[];
                                }
                                if($room_features) {
                                    foreach($room_features as $feature_id){
                                        $inclusions[] = CustomHelper::showAccommodationFeature($feature_id);
                                    }
                                }
                            }
                        }
                        $inclusions = implode(', ', $inclusions)??'';

                        $params = [];
                        $params['room_id'] = $inventory_data->room_id??'';
                        $params['plan_id'] = $inventory_data->plan_id??'';
                        $params['checkin'] = $checkin??'';
                        $params['checkout'] = $checkout??'';
                        $params['inventory'] = $inventory??'';
                        $inventory_price_data = CustomHelper::getAccommodationInventory($params);
                        if(isset($inventory_price_data['success']) && !empty($inventory_price_data['success'])) {
                            $sub_total_amount = $inventory_price_data['publish_price'];
                            $display_price = $inventory_price_data['display_price'];
                        }
                        $total_amount = $sub_total_amount;
                        $discount_amount = 0;

                        $module_name = 'hotel_listing';
                        $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$module_record_id,$inventory);
                        $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$group_id,$module_record_id);
                        $formData['discount_type'] = $discount_type ?? '';

                        $totalPrice  = $total_amount - $discount_amount;
                        $sub_total_amount = $total_amount;
                        $total_amount = $totalPrice;

                        if($display_price) {
                            $discount_amount = $display_price - $total_amount;
                            $sub_total_amount = $display_price;
                        }

                        $inventory_id = $inventory_data->id??'';
                        $hotel_id = $inventory_data->accommodation_id??'';
                        $hotel_name = $inventory_data->Accommodation->name??'';
                        $vendor_id = $inventory_data->Accommodation->vendor_id??'';
                        $hotel_phone = $inventory_data->Accommodation->contact_phone??'';
                        $hotel_email = $inventory_data->Accommodation->contact_email??'';
                        $contact_person = $inventory_data->Accommodation->contact_person??'';
                        $hotel_address = $inventory_data->Accommodation->address??'';
                        $room_id = $inventory_data->room_id??'';
                        $room_name = $inventory_data->AccommodationRoom->room_type_name??'';
                        $plan_id = $inventory_data->plan_id??'';
                        $plan_name = $inventory_data->AccommodationPlan->plan_name??'';

                        $enquiry_for = 2;
                        $interaction_content = 'Hotel booking initiated by '.$created_type;
                        $formData['enquiry_for'] = $enquiry_for; //custom.enq_for_types=Hotels
                        $formData['trip_date'] = $trip_date;
                        $formData['accommodation'] = $hotel_id;
                        $formData['room_id'] = $room_id;
                        $formData['room_name'] = $room_name;
                        $formData['plan_id'] = $plan_id;
                        $formData['plan_name'] = $plan_name;
                        $accommodation_comment = $room_name;
                        if($plan_name) {
                            $accommodation_comment = $accommodation_comment.', '.$plan_name;
                        }
                        $formData['accommodation_comment'] = $accommodation_comment;

                        $formData['checkin'] = $checkin;
                        $formData['checkout'] = $checkout;
                        $formData['inventory'] = $inventory;
                        $formData['adult'] = $adult;
                        $formData['child'] = $child;
                        $formData['infant'] = $infant;

                        $destination_id = $inventory_data->Accommodation->destination_id??'';
                        $destination_name = $inventory_data->Accommodation->accommodationDestination->name??'';
                        if($destination_id) {
                            $formData['destination'] = $destination_id;
                        }


                        $formData['sub_total_amount'] = $sub_total_amount??0;
                        $formData['discount'] = $discount_amount ?? 0;
                        $formData['total_amount'] = $total_amount??0;
                        $formData['comment'] = $comment;
                        $formData['followup_date'] = date('Y-m-d H:i:s');
                        $formData['interaction_content'] = $interaction_content;
                    }
                } else {
                    return Response::json(array(
                        'success' => false,
                        'message' => "We're sold out for your dates",
                        'errors' => $validator->getMessageBag()->toArray()
                    ), $statusCode); // 400 being the HTTP code for an invalid request.
                }

            } else if($action == 'flight_booking') {
                $same_amount_order = Order::where('user_id',$userId)->where('total_amount',$total_amount)->first();
                if($same_amount_order && $same_amount_order->id && strtotime($same_amount_order->created_at) > strtotime(date('Y-m-d H:i:s').' -3 minutes') ) {
                    return Response::json(array(
                        'success' => false,
                        'message' => "Duplicate amount booking detected, please wait for 3 minutes and then try again.",
                    ), $statusCode); // 400 being the HTTP code for an invalid request.
                }
                $formData['enquiry_for'] = 3; //custom.enq_for_types=Flight
                $formData['trip_date'] = $trip_date;
                $formData['sub_total_amount'] = $sub_total_amount??0;
                $formData['total_amount'] = $total_amount??0;
                $formData['comment'] = $comment;
                $formData['followup_date'] = date('Y-m-d H:i:s');
                $formData['interaction_content'] = 'Flight booking initiated by '.$created_type;

                if($request->gst_number) {
                    $formData['gst_number'] = $request->gst_number??'';
                    $formData['gst_company'] = $request->gst_company??'';
                    $formData['gst_email'] = $request->gst_email??'';
                    $formData['gst_phone'] = $request->gst_phone??'';
                    $formData['gst_address'] = $request->gst_address??'';
                } else {
                    $formData['gst_number'] = '';
                    $formData['gst_company'] = '';
                    $formData['gst_email'] = '';
                    $formData['gst_phone'] = '';
                    $formData['gst_address'] = '';
                }
                $supplier_id = $apiResult['totalPriceInfo']['supplier_id']??0;
                $inventory_id = $apiResult['totalPriceInfo']['inventory_id']??0;
                if($inventory_id) {
                    $paxInfo = $apiResult['searchQuery']['paxInfo']??[];
                    $inventory = ($paxInfo['ADULT']??0)+($paxInfo['CHILD']??0);//+($paxInfo['INFANT']??0);
                    $params = [];
                    $params['action'] = $action;
                    $params['supplier_id'] = $supplier_id;
                    $params['inventory_id'] = $inventory_id;
                    $params['trip_date'] = $trip_date;
                    $params['inventory'] = $inventory;
                    $check_inventory = CustomHelper::checkInventory($params);
                    if($check_inventory) {

                    } else {
                        return Response::json(array(
                            'success' => false,
                            'message' => "We're sold out for your dates",
                            'errors' => $validator->getMessageBag()->toArray()
                        ), $statusCode); // 400 being the HTTP code for an invalid request.
                    }
                }
            } else if($action == 'cab_booking') {
                $cab_route_id = $request->cab_route_id??'';

                $sub_total_amount = 0;
                $total_amount = 0;
                $comment = '';
                $tripType = $request->tripType??0;
                $travelDate = $request->travelDate??'';
                $travelTime = $request->travelTime??'';
                $adult = $request->adult??'';
                $children = $request->children??'';
                $trip_date = $travelDate;
                if($travelTime) {
                    $trip_date = $trip_date.' '.$travelTime;
                }

                $params = [];
                $params['action'] = $action;
                $params['price_id'] = $request->price_id;
                $params['cab_id'] = $request->cab_id;
                $params['cab_route_id'] = $request->cab_route_id;
                $params['cab_route_group_id'] = $request->cab_route_group;
                $params['trip_date'] = $travelDate;
                $check_inventory = CustomHelper::checkInventory($params);

                if($check_inventory) {
                    $formData['enquiry_for'] = 6;
                    $formData['trip_date'] = $trip_date;
                    $formData['adult'] = $adult;
                    $formData['children'] = $children;
                    $total_amount = 0;
                    $discount_category_id = '';
                    $module_record_id = '';
                    if(config('custom.CAB_VERSION')==2) {
                        $price_id = $request->price_id??'';
                        $search_data = (session()->has('searchdata'))?session('searchdata'):[];
                        if($price_id && !empty($search_data)) {
                            $cabsCityPrice = CabsCityPrice::find($price_id);
                            if($cabsCityPrice) {
                                $cab_id = $cabsCityPrice->cab_id??'';
                                $cab_route_group_id = $cabsCityPrice->cab_route_group??'';

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
                                $distance = 0;
                                $direction_resp_success = $direction_resp['success']??'';
                                if($direction_resp_success) {
                                    $direction_data = $direction_resp['rows'][0]['elements'][0]??[];
                                    $distance_text = $direction_data['distance']['text']??'';
                                    if($distance_text) {
                                        $distance = str_replace([' km',','], ['',''], $distance_text);
                                    }
                                    $duration_text = $direction_data['duration']['text']??'';
                                    $origin_addresses = $direction_resp['origin_addresses'][0];
                                    $destination_addresses = $direction_resp['destination_addresses'][0];
                                    $page_title = 'Showing cabs from &nbsp;<b>'.$origin_addresses.'</b> to &nbsp;<b>'.$destination_addresses.'</b>';
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
                                $total_amount = $cab_price;
                            }
                        }
                    } else {
                        $cab_id = $request->cab_id??'';
                        $cab_route_id = $request->cab_route_id??'';
                        $cab_route_group_id = $request->cab_route_group??'';
                        $cab_route_price = CabHelper::getCabRoutePrice($cab_id,$cab_route_id,$cab_route_group_id);
                        $cab_price = $cab_route_price->price??0;
                        if($tripType == 1) {
                            $cab_price = $cab_route_price->round_trip_price??0;
                        }
                        $routeData = CabRoute::where('id',$cab_route_id)->first();
                        $cabRouteGroup = CabRouteGroup::where('id',$cab_route_group_id)->first();
                        $total_amount = $cab_price;
                        $discount_amount = 0;

                        $discount_category_id = $routeData->discount_category_id??'' ;
                        $module_record_id = $routeData->id??'';
                    }

                    $module_name = 'cab';
                    if(config('custom.CAB_VERSION')==2) {
                        $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$price_per_km,$group_id,$module_record_id,$total_pax);
                        $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$group_id,$module_record_id);
                        $formData['discount_type'] = $discount_type ?? '';

                        if($discount_amount && $distance) {
                            $discount_amount =  $discount_amount * ceil($distance);
                            if($discount_amount >= $cab_price) {
                                $discount_amount = 0;
                            }
                        }
                    } else {
                        $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$module_record_id,$total_pax);
                        $discount_type = CustomHelper::getDiscountType($module_name,$discount_category_id,0,$group_id,$module_record_id);
                        $formData['discount_type'] = $discount_type ?? '';
                    }

                    $selected_addons = [];
                    if(session()->has('selected_addons')) {
                        $selected_addons = session('selected_addons');
                        if($selected_addons) {
                            foreach($selected_addons as $selected_addon) {
                                $cab_price = $cab_price + $selected_addon['price'];
                            }
                        }
                        $formData['selected_addons'] = $selected_addons;
                    }

                    $totalPrice  = $cab_price - $discount_amount;
                    $sub_total_amount = $cab_price;
                    $total_amount = $totalPrice;
                    $formData['sub_total_amount'] = $sub_total_amount ??0;
                    $formData['discount'] = $discount_amount ?? 0;
                    $formData['total_amount'] = $total_amount ?? 0;
                    $formData['followup_date'] = date('Y-m-d H:i:s');
                    $formData['interaction_content'] = 'Cab booking initiated by '.$created_type;
                } else {
                    return Response::json(array(
                        'success' => false,
                        'message' => "We're sold out for your dates",
                        'errors' => $validator->getMessageBag()->toArray()
                    ), $statusCode);
                }
            }

            $enquiry_record = $formData;
            $enquiry_record['form_data'] = $formData;
            $enquiry_record['created_by'] = $created_by;
            $enquiry_record['created_type'] = $created_type;
            $enquiry_record['lead_source'] = $getWebId->id ?? 0;
            // $enquiry = Enquiry::CreateEnquiry($enquiry_record);
            $enquiry_success = true;
            // if($enquiry['success']) { Stop creating enquiry for orders
            if($enquiry_success) {
                $getOrderStatusId = CustomHelper::getOrderStatus('new');
                try {
                    $discount_coupon = 0;
                    $discount_coupon_details = '';
                    $coupon_id = $request->fav_offer??'';
                    if(!empty($coupon_id)){
                        $couponData = Coupon::find($coupon_id);
                        $discount_coupon = $couponData->discount??0;
                        $couponDiscount = $couponData->discount??0;
                        if($couponData->type == 'percentage') {
                            $discount_coupon = ( $total_amount * ($couponDiscount/100) );
                        } else {
                            $discount_coupon = $couponData->discount??0;

                        }
                        if($discount_coupon > $couponData->max_discount_amt) {
                            $discount_coupon = $couponData->max_discount_amt;
                        }

                        $couponData['name'] = $couponData->name??'';
                        $couponData['code'] = $couponData->code??'';
                        $couponData['type'] = $couponData->type??'';
                        $couponData['use_limit'] = $couponData->use_limit??'';
                        $couponData['min_amount'] = $couponData->min_amount?? 0;
                        $couponData['max_amount'] = $couponData->min_amount?? 0;
                        $couponData['max_discount_amt'] = $couponData->max_discount_amt??'';
                        $couponData['start_date'] = $couponData->start_date??'';
                        $couponData['expiry_date'] = $couponData->expiry_date?? '';
                        $couponData['modules'] = $couponData->modules?? '';
                        $discount_coupon_details = $couponData ? json_encode($couponData,true): '';
                    }
                    $total_amount = $total_amount - $discount_coupon;

                    $order_no = CustomHelper::genrateOrderNoId($userId);
                    $order = new Order;
                    $order->order_no = $order_no;
                    $order->user_id = $userId;
                    $order->agent_id = $agent_id;
                    $order->name = $name;
                    $order->email = $email;
                    $order->phone = $phone;
                    $order->country_code = $country_code;
                    $order->address1 = $address1;
                    $order->address2 = $address2;
                    $order->city = $city;
                    $order->state = $state;
                    $order->country = $country;
                    $order->zip_code = $zip_code;
                    $order->comment = $comment;
                    $order->payment_status = 0;
                    $order->payment_description = NULL;
                    $order->payment_response = NULL;
                    $order->sub_total_amount = $sub_total_amount??0;
                    $order->discount_type = $discount_type??'';
                    $order->discount = $discount_amount??0;
                    $order->GST_TAX = $GST_TAX??0;
                    $order->gst_amount = $gst_amount??0;
                    $order->TCS_TAX = $TCS_TAX??0;
                    $order->tcs_amount = $tcs_amount??0;
                    $order->total_amount = $total_amount??0;
                    $order->discount_coupon = $discount_coupon??0;
                    $order->discount_coupon_details = $discount_coupon_details??0;

                    if(!empty($trip_date)) {
                        $order->trip_date = $trip_date;
                    }
                    if(!empty($price_category_choice_record)) {
                        $order->price_category_choice_record = json_encode($price_category_choice_record);
                    }
                    if($action == 'package_booking') {
                        $order->order_type = 1; //custom.order_type=Package
                        if($package->is_activity == 1) {
                            $order->order_type = 6; //custom.order_type=Activity
                        }
                        $order->package_id = $package_id;
                        $order->package_name = $package_name;
                        $order->package_type = $package_type;
                        $order->package_type_name = $package_type_name;

                        $booking_details = [];
                        if(!empty($package_id) && !empty($package_type) && !empty($trip_date)) {
                            $slot_date = CustomHelper::DateFormat($trip_date,'Y-m-d');
                            $slot_time = CustomHelper::DateFormat($trip_date,'H:i');

                            $query = PackageInventory::where(function($q1) use($slot_time){
                                $q1->whereHas('packageSlot',function($q2) use($slot_time){
                                    $q2->where('status',1);
                                    $q2->where('start_time',$slot_time);
                                });
                                $q1->orWhereNull('slot_id');
                            });
                            $query->where("status",1);
                            $query->where("package_id",$package_id);
                            $query->where("price_id",$package_type);
                            $query->whereDate('trip_date',$slot_date);
                            $inventory_data = $query->first();
                            if($inventory_data) {
                                $slot_data = $inventory_data->packageSlot??[];
                                $start_time = $trip_date;
                                $end_time = '';
                                if($slot_data) {
                                    $slot_duration = $slot_data->duration??'';
                                    $slot_duration_type = $slot_data->duration_type??'';
                                    $duration = $slot_duration.' '.ucfirst($slot_duration_type);

                                    if($slot_duration && $slot_duration_type){
                                        $end_time = date('Y-m-d H:i:s', strtotime($start_time. ' + '.(int)$slot_duration.' '.(($slot_duration_type=='hour')?'hours':'days')));
                                    }
                                    $booking_details['duration'] = $duration??'';
                                    $booking_details['slot_id'] = $slot_data->id??'';
                                }
                                $booking_details['start_time'] = $start_time??'';
                                $booking_details['end_time'] = $end_time??'';
                                $booking_details['total_pax'] = $total_pax??0;
                                $booking_details['contact_person'] = $package->contact_person??'';
                                $booking_details['contact_phone'] = $package->contact_phone??'';
                                $booking_details['contact_email'] = $package->contact_email??'';
                            }
                        }
                        $order->inventory = $total_pax ?? 0;
                        $order->booking_details = json_encode($booking_details,true);
                    } else if($action == 'hotel_booking') {
                        $order->order_type = 5; //custom.order_type=Hotel
                        $booking_details = [];
                        $booking_details['trip_date'] = CustomHelper::DateFormat($trip_date,'D, M dS Y h:i A');
                        $booking_details['inventory_id'] = $inventory_id??'';
                        $booking_details['destination_id'] = $destination_id??'';
                        $booking_details['destination_name'] = $destination_name??'';
                        $booking_details['hotel_id'] = $hotel_id??'';
                        $booking_details['hotel_name'] = $hotel_name??'';
                        $booking_details['contact_phone'] = $hotel_phone??'';
                        $booking_details['contact_email'] = $hotel_email??'';
                        $booking_details['contact_person'] = $contact_person??'';
                        $booking_details['address'] = $hotel_address??'';
                        $booking_details['room_id'] = $room_id??'';
                        $booking_details['room_name'] = $room_name??'';
                        $booking_details['plan_id'] = $plan_id??'';
                        $booking_details['plan_name'] = $plan_name??'';

                        $booking_details['checkin'] = $booking_data['checkin']??'';
                        $booking_details['checkout'] = $booking_data['checkout']??'';
                        $booking_details['inventory'] = $inventory??'';
                        $booking_details['adult'] = $adult??'';
                        $booking_details['child'] = $child??'';
                        $booking_details['infant'] = $infant??'';
                        $booking_details['inclusions'] = $inclusions??'';

                        $order->inventory = $inventory ?? 0;
                        $order->vendor_id = $vendor_id ?? 0;
                        $order->booking_details = json_encode($booking_details,true);
                    } else if($action == 'flight_booking') {
                        $order->order_type = 3; //custom.order_type=Flight
                        $gst_info = [];
                        if($request->gst_number) {
                            $gst_info['gst_number'] = $request->gst_number??'';
                            $gst_info['gst_company'] = $request->gst_company??'';
                            $gst_info['gst_email'] = $request->gst_email??'';
                            $gst_info['gst_phone'] = $request->gst_phone??'';
                            $gst_info['gst_address'] = $request->gst_address??'';
                        }
                        $flight_details = [];
                        $flight_details['price_id'] = implode(',', $priceIds)??'';
                        $flight_details['info'] = $apiResult??'';
                        $flight_details['gst_info'] = $gst_info??'';
                        $order->flight_details = json_encode($flight_details,true);
                        $supplier_id = $apiResult['totalPriceInfo']['supplier_id']??0;
                        $inventory_id = $apiResult['totalPriceInfo']['inventory_id']??0;
                        $pnrDetails = '';
                        if($inventory_id){
                            $order->inventory = $inventory??0;
                            $order->inventory_id = $inventory_id;
                            $order->supplier_id = $supplier_id;
                            $pnrDetails = $apiResult['totalPriceInfo']['pnrDetails']??'';
                        }
                    } else if($action == 'cab_booking') {
                        $order->order_type = 4; //custom.order_type=Cab Booking
                        $pickup = $request->pickup??'';
                        $drop = $request->drop??'';
                        $booking_details = [];
                        if(isset($cabData)) {
                            $fromCityName = $search_data['pickup']['name']??'';
                            $toCityName = $search_data['destination']['name']??'';
                            if($tripType == 5) {
                                $fromCityName = $search_data['city']['name']??'';
                                $fromCityId = $search_data['city']['id']??'';
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
                                    $toCityName = $routeName;
                                }
                            }

                            $booking_details['price_id'] = $price_id;
                            $booking_details['itinerary'] = $fromCityName.' > '.$toCityName;
                            $cab_route_types = config('custom.cab_route_types');
                            $booking_details['tripType'] = $tripType;
                            $booking_details['trip_type'] = $cab_route_types[$tripType]??'';
                            $booking_details['origin'] = $fromCityName ??'';
                            $booking_details['destination'] = $toCityName ??'';
                            $booking_details['duration'] = $duration_text??'';
                            $booking_details['distance'] = $cabData['fareDetails']['total_distance']??0;
                            $booking_details['cab_name'] = $cabData['name']??0;
                            $booking_details['cab_image'] = $cabData['image']??'';
                        }
                        $booking_details['pickup_address'] = $pickup;
                        $booking_details['drop_address'] = $drop;
                        $booking_details['trip_date'] = CustomHelper::DateFormat($trip_date,'D, M dS Y h:i A');
                        $booking_details['cab_id'] = $cab_id;
                        $booking_details['cab_route_id'] = $cab_route_id??'';
                        $booking_details['cab_route_group_id'] = $cab_route_group_id;
                        $booking_details['adult'] = $adult;
                        $booking_details['children'] = $children;
                        $booking_details['selected_addons'] = $selected_addons;
                        if($cab_id) {
                            $cab_data = CabMaster::find($cab_id);
                            if($cab_data) {
                                $booking_details['cab_name'] = $cab_data->name;
                                if($cab_data->image) {
                                    $booking_details['cab_image'] = asset('/storage/cabs/thumb/'.$cab_data->image);
                                } else {
                                    $booking_details['cab_image'] = '';
                                }
                            }
                            if($cab_route_id) {
                                $cab_route_data = CabRoute::find($cab_route_id);
                                if($cab_route_data) {
                                    $origin = $cab_route_data->originCity->name??'';
                                    $destination = $cab_route_data->destinationCity->name ?? $cab_route_data->name;
                                    $booking_details['itinerary'] = $origin.' > '.$destination;
                                    $cab_route_types = config('custom.cab_route_types');
                                    $booking_details['tripType'] = $tripType;
                                    $booking_details['trip_type'] = $cab_route_types[$tripType]??'';
                                    $booking_details['origin'] = $origin ??'';
                                    $booking_details['destination'] = $destination ??'';
                                    $booking_details['duration'] = $cab_route_data->duration ??'';
                                    $booking_details['distance'] = $cab_route_data->distance ??'';
                                }
                            }
                        }
                        $order->inventory = 1; //one cab book
                        $order->booking_details = json_encode($booking_details,true);
                    } else if($action == 'rental_booking') {
                        $order->order_type = 8; 
                        $order->inventory = 1;
                        $location_data = BikeCityLocation::find($location_id);
                        $booking_data['city_name'] = $city_data->name ?? '';
                        $booking_data['bike_name'] = $bike_data->name ?? '';
                        $booking_data['location_name'] = $location_data->name ?? '';
                        $pickupTime = $booking_data->pickupTime ?? '00:00';
                        $dropTime = $booking_data['dropTime'] ?? '00:00';
                        $dropDate  = $booking_data->dropDate ?? '';

                        $booking_data['trip_date']= CustomHelper::DateFormat($trip_date,'d/m/Y h:i A');
                        $booking_data['pickupDate']= $trip_date;
                        $booking_data['dropDate']= $dropOff.' '.$dropTime;
                        $order->booking_details = json_encode($booking_data,true);
                    }

                    $order->orders_status = $getOrderStatusId??0; //New order status id.
                    // $order->status = 'New';
                    $isSaved = $order->save();
                    if($isSaved) {
                        $order_id = $order->id;
                        // $enquiry_update = [];
                        // $enquiry_update['order_id'] = $order_id;
                        // Enquiry::where('id',$enquiry_id)->update($enquiry_update);

                        if(isset($gst_info) && isset($gst_info['gst_number']) && !empty($gst_info['gst_number'])) {
                            $old_gst_info = UserGstInfo::where('user_id',$userId)->where('gst_number',$gst_info['gst_number'])->orderBy('id','desc')->first();
                            if($old_gst_info) {
                                // Do nothing
                            } else {
                                $gst_info['user_id'] = $userId;
                                $isSaved = UserGstInfo::create($gst_info);
                                if($isSaved) {
                                    // Ok
                                } else {
                                    CustomHelper::captureSentryMessage('Error creating user gst info for data: '.json_encode($gst_info));
                                }
                            }
                        }

                        if($action == 'flight_booking') {
                            $now = date('Y-m-d H:i:s');
                            if(isset($tripInfos) && !empty($tripInfos) && !empty($passengers)) {
                                $passengers_arr = [];
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
                                $serial_no = 0;
                                $totalSsrBaggage = 0;
                                $totalSsrMeal = 0;
                                foreach($paxInfo_arr as $paxType) {
                                    if($tripInfos['searchQuery']['paxInfo'][$paxType['key']]) {
                                        $serial_no++;
                                        for ($i=1; $i <= $tripInfos['searchQuery']['paxInfo'][$paxType['key']]; $i++) {
                                            $title_key = $paxType['key'].$i.'_title';
                                            $title = $passengers[$title_key]??'';
                                            $first_name_key = $paxType['key'].$i.'_first_name';
                                            $first_name = $passengers[$first_name_key]??'';
                                            $last_name_key = $paxType['key'].$i.'_last_name';
                                            $last_name = $passengers[$last_name_key]??'';

                                            $dob_key = $paxType['key'].$i.'_dob';
                                            $dob = $passengers[$dob_key]??'';

                                            $passport_nationality_key = $paxType['key'].$i.'_passport_nationality';
                                            $passport_nationality = $passengers[$passport_nationality_key]??'';

                                            $passport_no_key = $paxType['key'].$i.'_passport_no';
                                            $passport_no = $passengers[$passport_no_key]??'';

                                            $passport_exp_date_key = $paxType['key'].$i.'_passport_exp_date';
                                            $passport_exp_date = $passengers[$passport_exp_date_key]??'';

                                            $passport_issue_date_key = $paxType['key'].$i.'_passport_issue_date';
                                            $passport_issue_date = $passengers[$passport_issue_date_key]??'';

                                            $ssrMealInfos = [];
                                            $ssrBaggageInfos = [];
                                            foreach($tripInfos['tripInfos'] as $tripInfo) {
                                                foreach($tripInfo['sI'] as $flightData) {
                                                    $baggage_key = $paxType['key'].$i.'_baggage_'.$flightData['da']['code'].'_'.$flightData['aa']['code'];
                                                    $baggage = $passengers[$baggage_key]??'';
                                                    if($baggage) {
                                                        $baggage_desc = CustomHelper::airBaggageDesc($tripInfos,$baggage)??'';
                                                        $baggage_amount = (int)CustomHelper::airBaggageDesc($tripInfos, $baggage,'amount')??0;

                                                        $totalSsrBaggage += $baggage_amount;
                                                        $ssrBaggageInfos[$flightData['da']['code'].'-'.$flightData['aa']['code']] = [
                                                            'key' => $flightData['id'],
                                                            'code' => $baggage,
                                                            'desc' => $baggage_desc,
                                                            'amount' => $baggage_amount,
                                                        ];
                                                    }

                                                    $meal_key = $paxType['key'].$i.'_meal_'.$flightData['da']['code'].'_'.$flightData['aa']['code'];
                                                    $meal = $passengers[$meal_key]??'';
                                                    if($meal) {
                                                        $meal_desc = CustomHelper::airMealDesc($tripInfos, $meal)??'';
                                                        $meal_amount = (int)CustomHelper::airMealDesc($tripInfos, $meal,'amount')??0;
                                                        $totalSsrMeal += $meal_amount;
                                                        $ssrMealInfos[$flightData['da']['code'].'-'.$flightData['aa']['code']] = [
                                                            'key' => $flightData['id'],
                                                            'code' => $meal,
                                                            'desc' => $meal_desc,
                                                            'amount' => $meal_amount,
                                                        ];
                                                    }
                                                }
                                            }

                                            $save_passenger_details_key = $paxType['key'].$i.'_save_passenger_details';
                                            $save_passenger_details = $passengers[$save_passenger_details_key]??'0';
                                            $passenger = [
                                                'order_id' => $order_id,
                                                'serial_no' => $serial_no,
                                                'title' => $title,
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'pax_type' => $paxType['key'],
                                                'ssrBaggageInfos' => json_encode($ssrBaggageInfos,true),
                                                'ssrMealInfos' => json_encode($ssrMealInfos,true),
                                                'save_passenger_details' => $save_passenger_details,
                                                'created_at' => $now,
                                                'updated_at' => $now,
                                            ];
                                            if($dob) {
                                                $passenger['dob'] = CustomHelper::DateFormat($dob,'Y-m-d','d/m/Y');
                                            }
                                            if($passport_nationality) {
                                                $passenger['passport_nationality'] = $passport_nationality;
                                            }
                                            if($passport_no) {
                                                $passenger['passport_no'] = $passport_no;
                                            }
                                            if($passport_exp_date) {
                                                $passenger['passport_exp_date'] = CustomHelper::DateFormat($passport_exp_date,'Y-m-d','d/m/Y');
                                            }
                                            if($passport_issue_date) {
                                                $passenger['passport_issue_date'] = CustomHelper::DateFormat($passport_issue_date,'Y-m-d','d/m/Y');
                                            }
                                            if($inventory_id && isset($pnrDetails) && !empty($pnrDetails)) {
                                                $passenger['pnrDetails'] = $pnrDetails;
                                            }
                                            $passengers_arr[] = $passenger;
                                        }
                                    }
                                }
                                if(!empty($passengers_arr)) {
                                    foreach($passengers_arr as $passenger) {
                                        OrderTraveller::create($passenger);
                                    }
                                }
                            }
                            $total_amount = $order->total_amount;
                            if(!empty($totalSsrBaggage) || !empty($totalSsrMeal)) {
                                $total_amount = $order->total_amount + $totalSsrBaggage + $totalSsrMeal;
                            }

                            $supplier_total_amount = $tripInfos['totalPriceInfo']['totalFareDetail']['fC']['TF'] + $totalSsrBaggage + $totalSsrMeal;

                            $fareIdentifier = $tripInfos['tripInfos'][0]['totalPriceList'][0]['fareIdentifier'];
                            $admin_markup = 0;
                            if($inventory_id) {
                                $admin_markup = $tripInfos['totalPriceInfo']['totalFareDetail']['fC']['TAF']??0;
                                if($admin_markup) {
                                    $admin_markup_details = [
                                        'markup_type' => 'fixed',
                                        'markup_value' => $admin_markup,
                                        'markup_price' => $admin_markup,
                                    ];
                                    $order->admin_markup = $admin_markup;
                                    $order->admin_markup_details = json_encode($admin_markup_details);
                                    $total_amount = $total_amount - $admin_markup;
                                }
                            } else {
                                $admin_markup_details = CustomHelper::getAdminMarkupDetails($tripInfos['totalPriceInfo']['totalFareDetail']['fC']['BF'],$tripInfos['searchQuery'],1,$fareIdentifier);
                                if(!empty($admin_markup_details) && isset($admin_markup_details['markup_price']) && $admin_markup_details['markup_price'] > 0) {
                                    $admin_markup = $admin_markup_details['markup_price'];
                                    $order->admin_markup = $admin_markup;
                                    $order->admin_markup_details = json_encode($admin_markup_details);
                                }
                            }

                            $markup = 0;
                            $markup_details = CustomHelper::getMarkupDetails($tripInfos['totalPriceInfo']['totalFareDetail']['fC']['BF'],$tripInfos['searchQuery'],1,$fareIdentifier);
                            if(!empty($markup_details) && isset($markup_details['markup_price']) && $markup_details['markup_price'] > 0) {
                                $markup = $markup_details['markup_price'];
                                $order->markup = $markup;
                                $order->markup_details = json_encode($markup_details);
                            }

                            $discount = 0;
                            $discount_details = CustomHelper::getAgentDiscountDetails($markup,$tripInfos['searchQuery'],1,$fareIdentifier);
                            if(!empty($discount_details) && isset($discount_details['discount_price']) && $discount_details['discount_price'] > 0) {
                                $discount = $discount_details['discount_price'];
                                $order->discount = $discount;
                                $order->discount_details = json_encode($discount_details);
                            }

                            $sub_total_amount = $total_amount + $admin_markup;
                            $total_amount = $total_amount + $admin_markup - $discount;
                            $order->supplier_total_amount = $supplier_total_amount;
                            $order->sub_total_amount = $sub_total_amount;
                            $order->total_amount = $total_amount;
                            $order->partial_amount = $total_amount;
                            $order->pay_type = 'total_price';
                            $order->save();

                            $resp = Order::processPayment($order_id,$request->payment_method);
                            if($resp['success']) {
                                $isBA = $tripInfos['conditions']['isBA']??'';
                                if($isBA==1) {
                                    $order_status_history  = array(
                                        'order_id' => $order->id,
                                        'orders_status' => CustomHelper::showEnquiryMaster($order->orders_status) ?? '',
                                        'comments' => 'Hold (Block) option applicable for selected flight and processing for hold.',
                                        'created_type' => $created_type,
                                        'created_by' => $created_by,
                                        'created_by_name' => $created_by_name,
                                    );
                                    $isSaved = OrderStatusHistory::create($order_status_history);

                                    // Hold the flight as payment is being processing, if Blocking is allowed
                                    $holdResp = Order::bookFlight($order_id,'hold');
                                    if($holdResp['success']==true) {
                                        $response['success'] = true;
                                        if($hold_ticket) {
                                            $response['redirect_url'] = route('user.mybooking');
                                        } else {
                                            $response['redirect_url'] = $resp['redirect_url']??'';
                                        }
                                        $statusCode = 200;
                                    } else {
                                        $message = $holdResp['errors'][0]['message']??'';
                                        $response = $holdResp;
                                        $response['success'] = false;
                                        $response['error_debug'] = 'booking.Order::bookFlight';
                                        $statusCode = 400;

                                        $booking_status = 'FAILED';
                                        if(stripos($message, 'Duplicate Booking') !== false) {
                                            $booking_status = 'DUPLICATE';
                                        }
                                        $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                                        $order->status = $booking_status;
                                        $order->save();
                                    }
                                } else {
                                    $order_status_history  = array(
                                        'order_id' => $order->id,
                                        'orders_status' => CustomHelper::showEnquiryMaster($order->orders_status) ?? '',
                                        'comments' => 'Hold (Block) option not applicable for selected flight and processing for payment.',
                                        'created_type' => $created_type,
                                        'created_by' => $created_by,
                                        'created_by_name' => $created_by_name,
                                    );
                                    $isSaved = OrderStatusHistory::create($order_status_history);

                                    $response['success'] = true;
                                    $response['redirect_url'] = $resp['redirect_url']??'';
                                    $statusCode = 200;
                                }

                                if($request->payment_method == 'wallet' && isset($resp['success']) && $resp['success'] == true) {
                                    $refund = false;
                                    session(['order_id'=>$order_id]);
                                    if(isset($response['success']) && $response['success'] == true) {
                                        $resp = Order::bookFlight($order_id,'confirm-book');
                                        if($resp['success']==true) {
                                            $inventory_update = Order::inventoryUpdate($order);
                                            // Success Flight Booking
                                            $orderNo = sha1($order->id);
                                            $resp = Self::bookingPayment($orderNo);
                                            $response['redirect_url'] = url('payment/thankyou');
                                        } else {
                                            $message = $resp['errors'][0]['message']??'';
                                            $refund = true;
                                        }
                                    } else {
                                        $refund = true;
                                    }
                                    if($refund) {
                                        // Error Flight Booking
                                        $wallet_comment = 'Booking payment reversed for flight booking failed. <br />Reason: '.$message;
                                        Order::refundOrderPayment($order->id,['wallet_comment'=>$wallet_comment]);

                                        $booking_status = 'FAILED';
                                        if(stripos($message, 'Duplicate Booking') !== false) {
                                            $booking_status = 'DUPLICATE';
                                        }
                                        $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                                        $order->status = $booking_status;
                                        $order->save();
                                        CustomHelper::captureSentryMessage('Payment Success, But Flight Booking Failed Wallet, Reversed wallet='.$message);
                                        $response['redirect_url'] = url('payment/failed');
                                    }
                                }
                            } else {
                                $response['message'] = $resp['message'];
                                $response['error_debug'] = 'booking.Order::processPayment';
                            }
                        } else {
                            $to_url = url('pay_now/'.$order_no);
                            $response['success'] = true;
                            $response['redirect_url'] = $to_url;
                            $statusCode = 200;
                        }
                    } else {
                        $message = 'Something went wrong, please try again.';
                        $response['message'] = $message;
                        $response['error_debug'] = 'booking.order->save';
                        CustomHelper::captureSentryMessage(str_replace('_', ' ', ucfirst($action)).' order save errors: '.$response['message']);
                    }
                } catch (\Exception $e) {
                    $response['message'] = $e->getMessage();
                    CustomHelper::captureSentryMessage(str_replace('_', ' ', ucfirst($action)).' try catch errors: '.$response['message']);
                    $response['error_debug'] = 'booking.try.order->save';
                }
            } else {
                $message = $enquiry['msg']??'';
                $response['message'] = $message;
            }
        }
        return Response::json($response, $statusCode);
    }

    public function response_tpsl(Request $request) {
        include public_path("/tpsl_payment_gatway/TransactionResponseBean.php");
        $payment = PaymentGateway::where(['value'=>'tpsl','status'=>1])->first();
        $tpsl_type = $payment->perameter_1;
        $merchantCode = $payment->perameter_2;
        $key = $payment->perameter_3;
        $iv = $payment->perameter_4;
        $schemeCode = $payment->perameter_5;
        $str = $request->msg??'';
        $transactionResponseBean = new \TransactionResponseBean();
        $transactionResponseBean->setResponsePayload($str);
        $transactionResponseBean->key = $key;
        $transactionResponseBean->iv = $iv;
        try {
            $response = $transactionResponseBean->getResponsePayload();
            if($response) {
                CustomHelper::captureSentryMessage($response);
            } else {
                CustomHelper::captureSentryMessage('TPSL Payment gateway no response');
            }
            $response1 = explode('|', $response);
            if(!$response1) {
                $sendtoSentry  = 'No response from gateway';
                CustomHelper::captureSentryMessage($message = $response);
            } else {
                CustomHelper::captureSentryMessage(json_encode($response1));
                $firstToken = explode('=', $response1[0]);
                $txn_msg = explode('=', $response1[1]);
                $clnt_txn_ref = '';
                $tpsl_txn_id = '';
                $txn_amt = '';
                if(isset($response1[3])) {
                    $clnt_txn_ref = explode('=', $response1[3]);
                }

                if(isset($response1[5])) {
                    $tpsl_txn_id = explode('=', $response1[5]);
                }
                if(isset($response1[6])) {
                    $txn_amt = explode('=', $response1[6]);
                }
                $status = $firstToken[1];
                $clnt_txn_ref = $clnt_txn_ref[1];
                $txn_msg = $txn_msg[1];
                $tpsl_txn_id = $tpsl_txn_id[1];
                $txn_amt = $txn_amt[1];

                $order = Order::where('order_no',$clnt_txn_ref)->first();
                $order_id = $order->id;
                $order_type = $order->order_type;
                session(['order_id'=>$order_id]);
                // $req_data = serialize($_GET);
                // $lines = explode("\n", $res);
                $keyarray = array();
                $payDetails = array();
                $payDetails['txn_id'] = $tpsl_txn_id; //$keyarray['txn_id'];
                $payDetails['response'] = $response;
                $payDetails['name'] = $order->name ?? '';
                $payDetails['email'] = $order->email ?? '';
                $payDetails['amount'] = $order->txn_amt ?? '';
                $payDetails['payment_date'] = date('Y-m-d H:i A') ;
                $payDetails['itemname'] = $order->package_name ?? '';
                $payDetails['payer_email']= $order->email ?? '';
                $payDetails['payer_name']=  $order->name ?? '';
                $payDetails['comment'] = $order->comment;

                // $payDetails['payer_id'] = $keyarray['payer_id'];
                // $payDetails['firstname'] = $keyarray['first_name'];
                // $payDetails['lastname'] = $keyarray['last_name'];
                // $payDetails['itemnumber'] = $keyarray['item_number'];

                //Do your paypal success codes here
                $success_transaction = true;
                $order->payment_description = json_encode($payDetails); //Recieved
                // $isSaved = $order->save();

                if($order->last_payment_id) {
                    $order_payments = OrderPayments::where('id',$order->last_payment_id)->first();;
                    $order_payments->payment_method = CustomHelper::getPaymentGatewayName($order->method);
                $order_payments->pg_description = json_encode($payDetails); //Recieved
                $order_payments->pg_response = json_encode($response1);
                $order_payments->pg_payment_id = $tpsl_txn_id;
                $order_payments->pg_response_date = date('Y-m-d H:i:s');

                if($status == '300' || $status == '0300') {
                    $order_payments->pg_payment_status = 1; //confirmed
                }else{
                    $order_payments->pg_payment_status = 2; //Failed
                }
                    $order_payments->save();
                }
                if($status == '300' || $status == '0300') {
                    $order->payment_status = 1; //confirmed
                    $isSaved = $order->save();
                    session(['order_id'=>$order->id]);
                    $resp = Order::processPaymentSuccess($order->id);
                    if($resp['success']) {
                        if($resp['redirect_url']) {
                            return redirect($resp['redirect_url']);
                        } else {
                            return redirect(url('payment/failed'));
                        }
                    } else {
                        return redirect(url('payment/failed'));
                    }
                } else {
                    // $orderNo = sha1($order->id);
                    $order->payment_status = 2; //Failed
                    $order->orders_status = 0;
                    // $order->status = '';
                    $isSaved = $order->save();
                    return redirect(url('payment/failed'));
                }
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            // prd($message);
            CustomHelper::captureSentryMessage('TPSL try catch error: '.$message);
            return redirect(url('payment/failed'));
        }
    }

    public function response_razorpay(Request $request) {
        $response = [];
        $response['success'] = false;
        $order_no = $request->order_no??'';
        $input = $request->all();
        $razorpay_payment_id = $input['razorpay_payment_id']??'';
        if(!empty($order_no) && !empty($razorpay_payment_id)) {
            $order = Order::where('order_no',$order_no)->first();
            if($order && $order->id) {
                if($order->payment_status == 1) {
                    // Payment might get success using webhook before this request.
                    $response['success'] = true;
                    $response['redirect_url'] = url('payment/thankyou');
                } else {
                    $txn_amt = $order->txn_amt;
                    $capture_amount = $txn_amt * 100;

                    try {
                        $payment = PaymentGateway::where(['value'=>'razorpay','status'=>1])->first();
                        $RAZORPAY_KEY = $payment->perameter_1;
                        $RAZORPAY_SECRET = $payment->perameter_2;
                        $api = new Api($RAZORPAY_KEY, $RAZORPAY_SECRET);
                        $resp = $api->payment->fetch($razorpay_payment_id);//->capture(array('amount'=>$capture_amount));
                        // if($response && $response->id && $response->description == $order_no) {
                            $pg_response = $resp->toArray();
                            $pg_payment_id = $resp->id??'';
                            $pg_payment_status = $resp->status??'';

                            $payDetails = [];
                            $payDetails['txn_id'] = $pg_payment_id;
                            $payDetails['response'] = json_encode($resp->toArray());
                            $payDetails['name'] = $order->name ?? '';
                            $payDetails['email'] = $order->email ?? '';
                            $payDetails['amount'] = ($resp->amount / 100);
                            $payDetails['currency'] = $resp->currency;
                            $payDetails['payment_date'] = date('Y-m-d H:i A') ;
                            $payDetails['itemname'] = $order->package_name ?? '';
                            $payDetails['payer_email']= $order->email ?? '';
                            $payDetails['payer_name']=  $order->name ?? '';
                            $payDetails['comment'] = $order->comment;
                            $order->payment_description = json_encode($payDetails);
                            if($order->last_payment_id) {
                                $orderPayments = OrderPayments::where('id',$order->last_payment_id)->first();
                                if($orderPayments && $orderPayments->id) {
                                    $orderPayments->payment_method = CustomHelper::getPaymentGatewayName($order->method);
                                    $orderPayments->pg_description = json_encode($payDetails);
                                    $orderPayments->pg_response = json_encode($resp->toArray());
                                    $orderPayments->pg_payment_id = $pg_payment_id;
                                    $orderPayments->pg_response_date = date('Y-m-d H:i:s');

                                    if($pg_payment_status == 'created' || $pg_payment_status == 'captured') {

                                        $orderPayments->pg_payment_status = 1;
                                        $orderPayments->status = 1;
                                        $orderPayments->save();

                                        $order->payment_status = 1;
                                        $isSaved = $order->save();
                                        session(['order_id'=>$order->id]);
                                        $resp = Order::processPaymentSuccess($order->id);
                                        if($resp['success']) {
                                            $response['success'] = true;
                                            if($resp['redirect_url']) {
                                                $response['redirect_url'] = $resp['redirect_url'];
                                            }
                                        }
                                    } else {
                                        $orderPayments->pg_payment_status = 2;
                                        $orderPayments->save();

                                        $order->payment_status = 2;
                                        $order->orders_status = 0;
                                        $isSaved = $order->save();
                                    }
                                } else {
                                    $message = 'Razorpay response last_payment_id details not found';
                                    CustomHelper::captureSentryMessage($message);
                                }
                            }                            
                        //}
                    } catch (Exception $e) {
                        $message = $e->getMessage();
                        CustomHelper::captureSentryMessage('Razorpay response try catch error: '.$message);
                    }
                }
            } else {
                $message = 'Razorpay response error: Empty order details';
                CustomHelper::captureSentryMessage('Razorpay try catch error: '.$message);
            }
        } else {
            $message = 'Razorpay response error: Empty order_no or razorpay_payment_id';
            CustomHelper::captureSentryMessage('Razorpay try catch error: '.$message);
        }
        return Response::json($response);
    }

   
    public function inventory_update($order) {


    //Inventory
    if($order->order_type==1) { //Package-booking
    //======
        $booking_details = json_decode($order->booking_details);
        $user_id = $order->user_id;
        $package_id   = $order->package_id;
        $price_id = $order->package_type;
        $inventory = $order->inventory;
        $trip_date = CustomHelper::DateFormat( $order->trip_date, 'Y-m-d');

        $PackageInventory = CustomHelper::AvailblePackageInventory($package_id,$price_id,$trip_date);

        $inventory_id = $PackageInventory->id;
    // $total_pax_unit = CustomHelper::getTotalPaxUnit($order);

        if($inventory > 0){

            $old_booked_inventory = $PackageInventory->booked ?? 0 ;
            $booked_inventory  = $old_booked_inventory + $inventory;
            $package_inventory = array('booked' => $booked_inventory, );
            $isUpdateinventory = PackageInventory::where('id',$inventory_id)->update($package_inventory);

        }

    //======
    }else if($order->order_type==6) { //Activity-booking
    //======
        $booking_details = json_decode($order->booking_details);
        $user_id = $order->user_id;
        $package_id   = $order->package_id;
        $price_id = $order->package_type;
        $inventory = $order->inventory;
        $slot_id = $booking_details->slot_id ?? '';
        $trip_date = CustomHelper::DateFormat( $order->trip_date, 'Y-m-d');

        $PackageInventory = CustomHelper::AvailblePackageInventory($package_id,$price_id,$trip_date,$slot_id);
        $inventory_id = $PackageInventory->id;
    // $total_pax_unit = CustomHelper::getTotalPaxUnit($order);

        if($inventory > 0){

            $old_booked_inventory = $PackageInventory->booked ?? 0 ;
            $booked_inventory  = $old_booked_inventory + $inventory;
            $package_inventory = array('booked' => $booked_inventory, );
            $isUpdateinventory = PackageInventory::where('id',$inventory_id)->update($package_inventory);

        }
    // prd($isUpdateinventory);
    //======
    }else if($order->order_type==4){ //Cab-booking
    //======
        $user_id = $order->user_id;
        if($order->booking_details){

            $booking_details = json_decode($order->booking_details);

            $cab_id   = $booking_details->cab_id;
            $cab_route_id   = $booking_details->cab_route_id;
            $cab_route_group_id   = $booking_details->cab_route_group_id;

            $price_id = $order->package_type;
            $trip_date = CustomHelper::DateFormat( $order->trip_date, 'Y-m-d');

            $CabInventory = CustomHelper::AvailbleCabInventory($cab_route_group_id,$cab_id,$trip_date);
            $inventory_id = $CabInventory->id;

            $inventory = $order->inventory;
            $old_booked_inventory = $CabInventory->booked ?? 0 ;
            $booked_inventory  = $old_booked_inventory + $inventory;
            $cab_inventory = array('booked' => $booked_inventory, );
            $isUpdateinventory = CabRouteInventory::where('id',$inventory_id)->update($cab_inventory);

    //======
        }
    }else if($order->order_type==8){ //Rental-booking
    //======
        $user_id = $order->user_id;
        $inventory = $order->inventory;
        if($order->booking_details){

            $booking_details = json_decode($order->booking_details);
            $bike_id   = $booking_details->bike_id;
            $city_id   = $booking_details->city_id;

            $pickupDate = $booking_details->pickupDate;
            $dropDate = $booking_details->dropDate;

            $period = CustomHelper::DateRange($pickupDate,$dropDate);
            $date_arr = [];
            foreach ($period as $key => $value) {
                $date_arr[] = $value->format('Y-m-d');
            }

            $inventory_data =BikeCityInventory::where('bike_id',$bike_id)->where('city_id',$city_id)->whereIn('trip_date',$date_arr)->get();



            foreach ($inventory_data as $key => $row) {

                $BikeCityInventory = BikeCityInventory::find($row->id);
                $old_booked_inventory = $BikeCityInventory->booked ?? 0 ;
                $booked_inventory  = $old_booked_inventory + $inventory;
                $BikeCityInventory->booked = $booked_inventory;
                $BikeCityInventory->save();

            }

    //======
        }
    }elseif ($order->order_type == 5) {
    //=======

        $booking_details = json_decode($order->booking_details);
        $inventory_id = $booking_details->inventory_id ?? 0;
        $checkout = $booking_details->checkout ? CustomHelper::DateFormat($booking_details->checkout,'Y-m-d','d/m/Y') : '';
        $checkin = $booking_details->checkin ? CustomHelper::DateFormat($booking_details->checkin,'Y-m-d','d/m/Y') : '';

        $inventory_row = AccommodationInventory::find($inventory_id);
        $room_id = $inventory_row->room_id;
        $plan_id = $inventory_row->plan_id;
        $accommodation_id = $inventory_row->accommodation_id;
        $inventory = $order->inventory ;

        $query = AccommodationInventory::where('status',1);
        $query->where('room_id',$room_id);
        if($plan_id) {
            $query->where('plan_id',(int)$plan_id);
        }
        $date_arr = [];
        if($checkin && $checkout) {
            $checkout_date = date('Y-m-d', strtotime($checkout. ' - 1 days'));
            $period = CustomHelper::DateRange($checkin,$checkout_date);
            foreach ($period as $key => $value) {
                $date_arr[] = $value->format('Y-m-d');
            }
            $query->whereIn('date',$date_arr);
        } else {
            $date_arr[] = $checkin;
            $query->whereIn('date',$date_arr);
        }
        $inventory_data = $query->orderBy('date','ASC')->get();

        foreach ($inventory_data as $key => $row) {

            $AccommodationInventory = AccommodationInventory::find($row->id);
            $old_booked_inventory = $AccommodationInventory->booked ?? 0 ;
            $booked_inventory  = $old_booked_inventory + $inventory;
            $AccommodationInventory->booked = $booked_inventory;
            $AccommodationInventory->save();

        }
    }

}

public function pay_now(Request $request) {
    $data = [];
    if($request->order_no) {
        $action = $request->action??'';
        $order_no = $request->order_no;
        $order = Order::where('order_no',$order_no)->first();
        $order_payments = new OrderPayments;
        $userId = Auth::user()->id??'';
        $is_agent = Auth::user()->is_agent??'';
        $agent_id = 0;
        if($is_agent == 1) {
            $agent_id = Auth::user()->id??'';
            // $userId = '';
        }
        $data['is_agent'] = $is_agent;
        if($order && ($order->payment_status == 0 || $order->payment_status == 2)) {
            $name = $order->name ?? '';
            $phone = '';
            if($order->phone) {
                $country_code = $order->country_code ?? '91';
                $phone = $country_code.$order->phone;
            }
            $email = $order->email ?? '';

            $wallet_comment = '';
            $order_type = $order->order_type??'';
            $package = $order->Package??[];
            $packagePrice = $order->PackagePrice??[];
            $userObject = $order->User??[];

            $data['order'] = $order;
            $data['package'] = $package;
            $data['packagePrice'] = $packagePrice;
            $data['userObject'] = $userObject;
            if($request->pay_now) {
                $check_inventory_message = '';
                $total_amount = $order->total_amount??0;
                $amount = $total_amount;

                $payment_method_name = '';
                $payment_method = $request->payment_method??'';
                if($request->wallet == 1) {
                    $payment_method_name = 'wallet';
                    $payment_method = 'wallet';
                } else if($payment_method) {
                    $payment = PaymentGateway::where(['value'=>$payment_method,'status'=>1])->first();
                }
                $payment_method_name = CustomHelper::getPaymentGatewayName($payment_method);

                $pay_type = $request->pay_type??'total_price';
                $booking_price = 0;
                $is_book_without_payment = 0;
                $traveller = '';

                if(isset($package) && !empty($package)) {
                    if($packagePrice) {
                        $booking_price = (int)$packagePrice->booking_price??0;
                        $is_book_without_payment = (int)$packagePrice->is_book_without_payment??0;
                        if($is_agent == 1) {
                            $booking_price = (int)$packagePrice->booking_price_b2b??0;
                            $is_book_without_payment = (int)$packagePrice->is_book_without_payment_b2b??0;
                        }
                    }

                    $total_pax = 0;
                    $packagePriceCategory = isset($package->packagePriceCategory) ? $package->packagePriceCategory:'';
                    $priceCategoryElements = isset($packagePriceCategory->priceCategoryElements) ? $packagePriceCategory->priceCategoryElements:'';
                    $category_choices_record = $packagePrice->category_choices_record??[];
                    $category_choices_record_arr = [];
                    if($category_choices_record) {
                        $category_choices_record_arr = json_decode($category_choices_record,true);
                    }

                    $booking_data = (session()->has('booking_data'))?session('booking_data'):[];
                    $traveller.= '<p>';
                    if(!empty($priceCategoryElements) && count($priceCategoryElements) > 0) {
                        $sub_total_amount = 0;
                        foreach ($priceCategoryElements as $element) {
                            if(array_key_exists('pce'.$element->id, $booking_data)) {
                                $traveller.=  $element->input_label.': ';

                                $input_value = $booking_data['pce'.$element->id]??0;
                                $traveller.=  $input_value.', <br>';
                                $input_price = 0;
                                if($input_value > 0 && array_key_exists('pce'.$element->id, $category_choices_record_arr)) {
                                    $input_price = $category_choices_record_arr['pce'.$element->id][$input_value]??0;
                                    $total_pax += $input_value;
                                }
                            }
                        }
                    }
                    $traveller.= '</p>';
                }

                $check_inventory = true; //for Pay-online,Flight

                if($order->order_type == 1 || $order->order_type == 6) {
                    $booking_details = json_decode($order->booking_details);

                    $inventory = $order->inventory;
                    $params = [];
                    $params['action'] = 'package_booking';
                    // $params['discount_coupon'] = $discount_coupon;
                    $params['inventory'] = $inventory;
                    $params['package'] = $order->package_id;
                    $params['package_type'] = $order->package_type;
                    $params['trip_date'] = CustomHelper::DateFormat($order->trip_date, 'Y-m-d');
                    if($package && $package->is_activity == 1){
                        $params['slot_time'] = CustomHelper::DateFormat($order->trip_date, 'H:i');
                    }

                    $check_inventory = CustomHelper::checkInventory($params);
                } elseif ($order->order_type == 3) {
                    if($order->bookingId && $order->status == 'ON_HOLD') {
                        $bookingId = $order->bookingId;
                        $reqParams = [
                            "bookingId" => $bookingId,
                            "requirePaxPricing" => true
                        ];
                        $endPoint = '/oms/v1/booking-details';
                        $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                        $apiResult = json_decode($resp, true);
                        $statusCode = $apiResult['status']['httpStatus']??'';
                        $success = $apiResult['status']['success']??'';
                        if ($statusCode == 200) {
                            $booking_status = $apiResult['order']['status']??'';
                            if($booking_status == 'ON_HOLD') {
                                // Ok
                            } else {
                                $check_inventory = false;
                                $check_inventory_message = 'Session is already expired. Please try again with new session';
                            }
                        } else {
                            $message = $apiResult['errors'][0]['message']??'';
                            $check_inventory = false;
                            $check_inventory_message = $message;
                        }
                    } else if($order->flight_details) {
                        $flight_details = json_decode($order->flight_details);
                        $price_id = $flight_details->price_id??'';
                        if($price_id) {
                            if(is_array($price_id)) {
                                $priceIds = $price_id;
                            } else {
                                $priceIds = explode(',', $price_id);
                            }
                            $review_slug = CustomHelper::slugify(implode(',', $priceIds));
                            // $resp = \Cache::rememberForever("review-".$review_slug, function () use($priceIds) {
                            if(!empty($priceIds) && isset($priceIds[0]) && strpos($priceIds[0], 'IIAIR') !== false ) {
                                $resp = CustomHelper::flightReviewDetails($review_slug, $priceIds);
                                $apiResult = json_decode($resp, true);
                                $apiStatusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                                $success = !empty($apiResult)?$apiResult['status']['success']:'';
                                if (isset($apiStatusCode) && $apiStatusCode == 200  && isset($success)) {
                                    $tripInfos = $apiResult;
                                    $trip_date = $tripInfos['tripInfos'][0]['sI'][0]['dt']??'';
                                    if($trip_date) {
                                        $trip_date = CustomHelper::DateFormat($trip_date,'Y-m-d H:i:s');
                                    }
                                    $supplier_id = $apiResult['totalPriceInfo']['supplier_id']??0;
                                    $inventory_id = $apiResult['totalPriceInfo']['inventory_id']??0;
                                    if($inventory_id) {
                                        $paxInfo = $apiResult['searchQuery']['paxInfo']??[];
                                        $inventory = ($paxInfo['ADULT']??0)+($paxInfo['CHILD']??0);//+($paxInfo['INFANT']??0);
                                        $params = [];
                                        $params['action'] = $action;
                                        $params['supplier_id'] = $supplier_id;
                                        $params['inventory_id'] = $inventory_id;
                                        $params['trip_date'] = $trip_date;
                                        $params['inventory'] = $inventory;
                                        $check_inventory = CustomHelper::checkInventory($params);
                                        if($check_inventory) {
                                            $check_inventory = true;
                                        } else {
                                            $check_inventory = false;
                                            $check_inventory_message = "We're sold out for your dates";   
                                        }
                                    }
                                }
                            } else {
                                $reqParams = [
                                    "priceIds" => $priceIds,
                                ];
                                $endPoint = '/fms/v1/review';
                                $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                                //     return $resp;
                                // });
                                $apiResult = json_decode($resp, true);
                                // prd($apiResult);
                                $statusCode = !empty($apiResult)?$apiResult['status']['httpStatus']:'';
                                $success = !empty($apiResult)?$apiResult['status']['success']:'';
                                if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                                    $check_inventory = true;
                                } else {
                                    $check_inventory = false;
                                    $check_inventory_message = 'Session is already expired. Please try again with new session';
                                }
                            }
                        }
                    }
                } elseif ($order->order_type == 4) {
                    $booking_details = json_decode($order->booking_details);
                    $cab_id   = $booking_details->cab_id ?? 0;
                    $cab_route_id   = $booking_details->cab_route_id ?? 0;
                    $cab_route_group_id   = $booking_details->cab_route_group_id ?? 0;  
                    if($cab_id && $cab_route_id && $cab_route_group_id){
                        $params = [];
                        $params['action'] = 'cab_booking';
                        $params['cab_id'] = $cab_id;
                        $params['cab_route_id'] = $cab_route_id;
                        $params['cab_route_group_id'] = $cab_route_group_id;
                        $params['trip_date'] = CustomHelper::DateFormat($order->trip_date, 'Y-m-d');
                        $check_inventory = CustomHelper::checkInventory($params);
                    }
                } elseif ($order->order_type == 5) {
                    $booking_details = json_decode($order->booking_details);
                    $inventory = $order->inventory;
                    $params = [];
                    $params['action'] = 'hotel_booking';
                    $params['inventory_id'] = $booking_details->inventory_id ?? 0;
                    $params['checkin'] = $booking_details->checkin ? CustomHelper::DateFormat($booking_details->checkin,'Y-m-d','d/m/Y') : '';
                    $params['checkout'] = $booking_details->checkout ? CustomHelper::DateFormat($booking_details->checkout,'Y-m-d','d/m/Y') : '';
                    $params['inventory']= $inventory ?? 1;
                    $check_inventory = CustomHelper::checkInventory($params);
                } elseif ($order->order_type == 7) {
                    $order_payments->description = 'Wallet Recharged by '.$payment_method_name;
                }

                if($check_inventory) {
                    if($pay_type == 'total_price') {
                        $amount = $total_amount;
                    } else if($pay_type == 'booking_price' && !empty($booking_price)) {
                        $amount = $booking_price*$total_pax;
                    } /*else if($pay_type == 'partial_amount' && !empty($is_partial_amount) && $is_partial_amount == 1) {
                        $amount = $request->partial_amount;
                    } */else if($pay_type == 'book_without_payment' && !empty($is_book_without_payment) && $is_book_without_payment == 1) {
                        $amount = 0;
                    }
                    $order_payments->payment_method = $payment_method_name;
                    $order_payments->order_id = $order->id;
                    $order_payments->order_no = $order->order_no;
                    $order_payments->user_id = $order->user_id;
                    $order_payments->payment_channel = $payment_method_name;
                    $order_payments->amount = $amount ?? 0;
                    $order_payments->currency = 'INR';
                    $order_payments->payment_type = $pay_type;
                    $order_payments->status = 0; //pending
                    $order_payments->save();
                    $last_payment_id = $order_payments->id ?? NULL;

                    $order->partial_amount = $amount;
                    $order->pay_type = $pay_type;
                    $order->last_payment_id = $last_payment_id;
                    $order->save();
                    $order = Order::find($order->id);
                    $data['order'] = $order;

                    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');

                    $websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO']);
                    $storage = Storage::disk('public');
                    $path = "settings/";
                    $logoSrc =asset(config('custom.assets').'/images/logo.png');
                    if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
                        $logo = $websiteSettingsArr['FRONTEND_LOGO'];
                        if($storage->exists($path.$logo)){
                            $logoSrc = asset('/storage/'.$path.$logo);
                        }
                    }
                    // AGENT LOGO
                    $userAgentInfo = ''; $agentLogo = '';
                    $agent_phone = '';
                    $agent_email = '';
                    $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                    if($agent_id && $agent_id > 0){
                        $userAgentInfo = $order->agentInfo ?? '';
                        if($userAgentInfo && $userAgentInfo->count() > 0){
                            $path = 'agent_logo/';
                            $agentLogo = $order->agentInfo->agent_logo ?? '';

                            $userData = $userAgentInfo->User ?? '';
                            
                            if($userData->phone) {
                                $country_code = $userData->country_code ?? '91';
                                $agent_phone = '+'.$country_code.'-'.$userData->phone;
                            }
                            $agent_email = !empty($userData->email)?$userData->email:'';
                        }
                        if(!empty($agentLogo)){
                            if($storage->exists($path.$agentLogo)){
                                $logoSrc = asset('/storage/'.$path.$agentLogo);
                            }
                        }
                    }

                    $common_logo = '';
                    if(!empty($agent_id)){
                        $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                        $b2b_logo_params = array(
                            '{agent_logo}' => $logoSrc
                        );
                        $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                    }else{
                        $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                        $b2c_logo_params = array(
                           '{company_logo}' => $logoSrc
                       );
                        $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                    }

                    $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                    $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                    $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                    $sales_phone = CustomHelper::getPhoneHref($company_phone);
                    $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                    $sales_email = CustomHelper::getEmailHref($company_email);


                    $contact_details = '';
                    if(!empty($agent_id)){
                        $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                        $b2b_email_params = array(
                            '{agent_phone}' => $agent_phone??'',
                            '{agent_email}' => $agent_email
                        );
                        $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                    }else{

                        $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                        $b2c_email_params = array(
                            '{company_name}' => $company_name,
                            '{sales_mobile}' => $sales_mobile,
                            '{sales_phone}' => $sales_phone,
                            '{sales_email}' => $sales_email,
                            '{company_title}' => $system_title
                        );
                        $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                    }

                    $order_trip_date = '';
                    if($order->trip_date) {
                        $order_trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $order_trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }

                    $order_phone = '';
                    if($order->phone) {
                        $country_code = $order->country_code ?? '91';
                        $order_phone = '+'.$country_code.'-'.$order->phone;
                    }
                    $destination = $package ? $package->packageDestination->name:'';

                    $order_address = '';
                    $order_address_1 = isset( $order->address1) ?  $order->address1 : '';
                    $order_address_2 = isset( $order->address2) ?  $order->address2 : '';
                    $order_address = $order_address_1.', '.$order_address_2;

                    $email_params = array(
                        '{order_id}' =>  $order->order_no??'',
                        '{order_trip_date}' => $order_trip_date,
                        '{package_name}' => $order->package_name??'',
                        '{destination}' => $destination ?? '',
                        '{payment_method}' => $payment_method_name ??'',
                        '{name}' => $order->name??'',
                        '{email}' => $order->email??'',
                        '{phone}' => $order_phone,
                        '{city}' => $order->city??'',
                        '{address}' => $order->address1??'',
                        '{order_address2}' => $order->address2??'',
                        '{state}' => $order->state??'',
                        '{country_name}' => isset($order->country) && is_numeric($order->country) ? CustomHelper::GetCountry($order->country,'name') : $order->country,
                        '{country}' => isset($order->country) && is_numeric($order->country) ? CustomHelper::GetCountry($order->country,'name') : $order->country,
                        '{zip_code}' => $order->zip_code??'',
                        // '{package_name}' => $order->Package->title??'',
                        '{order_packageprice_title}' => $order->PackagePrice->title??'',
                        '{ordersub_total_amount}' => isset($order->sub_total_amount) ? CustomHelper::getPrice($order->sub_total_amount) : '',
                        '{total_amount}' => isset($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : '',
                        '{paid_amount}' => isset($order->partial_amount) ? CustomHelper::getPrice($order->partial_amount) : '',
                        '{pending_amount}' => $order->total_amount - $order->partial_amount ,
                        '{header}' => $common_logo,
                        '{contact_details}' => $contact_details??'',
                        '{date}' => date("l jS \of F Y h:i A"),
                        '{comment}' => $order->comment??'',
                    );

                    $record_name = "";
                    if($order_type ==1) { //package
                        $wallet_comment = 'Paid for package booking';
                        $template_slug = 'package-booking';
                        $email_params['{travellers}'] = $traveller??'';
                        $record_name = "Package Booking";

                    }  elseif($order_type == 6){ //Activity
                        $wallet_comment = 'Paid for activity booking';
                        $template_slug = 'activity-booking';
                        $email_params['{travellers}'] = $traveller??'';
                        $record_name = "Activity Booking";

                    } elseif($order_type ==4) { //Cab
                        $wallet_comment = 'Paid for cab booking';
                        $template_slug = 'cab-booking-email';
                        $record_name = "Cab Booking";

                        $booking_details = $order->booking_details ?? '';
                        $booking_details_arr = json_decode($booking_details);
                        $paxinfo = '';
                        if($booking_details_arr->adult || $booking_details_arr->children) {
                            $paxinfo .= 'Total Pax:';
                            if($booking_details_arr->adult && $booking_details_arr->children) {
                                $paxinfo .= $booking_details_arr->adult.' Adults, '.$booking_details_arr->children.' Child';
                            } else if($booking_details_arr->adult) {
                                $paxinfo .= $booking_details_arr->adult.' Adults';
                            } else if($booking_details_arr->children) {
                                $paxinfo .= $booking_details_arr->children.' Child';
                            }
                        }

                        $addons = '';
                        if(isset($booking_details_arr->selected_addons) && !empty($booking_details_arr->selected_addons)) {
                            foreach($booking_details_arr->selected_addons as $addon) {
                                $addon_str = $addon->name.': Qty:'.$addon->qty;
                                if(isset($addon->daily_rental)) {
                                    $addon_str .= ' Days:'.$addon->days;
                                }
                                $addons .= $addon_str.' <br />';
                            }
                        }

                        $email_params['{booking_id}'] = $order->order_no??'';
                        $email_params['{pickup_date}'] = $booking_details_arr->trip_date??'';
                        $email_params['{pickup_address}'] = $booking_details_arr->pickup_address??'';
                        $email_params['{drop_address}'] = $booking_details_arr->drop_address??'';
                        $email_params['{trip_date}'] = $order->trip_date??'';
                        $email_params['{cab}'] = $booking_details_arr->cab_name??'';
                        $email_params['{service_city}'] = $booking_details_arr->origin??'';
                        $email_params['{destination}'] = $booking_details_arr->destination??'';
                        $email_params['{name}'] = $order->name??'';
                        $email_params['{phone_number}'] = $order_phone;
                        $email_params['{email}'] = $order->email??'';
                        $email_params['{trip_type}'] = $booking_details_arr->trip_type??'';
                        $email_params['{paxInfo}'] = $paxinfo;
                        $email_params['{addOns}'] = $addons;
                        $email_params['{total_amount}'] = $order->total_amount??'';
                        $email_params['{itinerary}'] = $booking_details_arr->itinerary??'';

                    } elseif($order_type == 5) { //Hotel
                        $wallet_comment = 'Paid for hotel booking';
                        $record_name = "Hotel booking";

                    } elseif($order_type ==2) { //Pay-online
                        $wallet_comment = 'Pay Online';
                        $template_slug = 'pay-online';
                        $record_name = "Pay Online";
                    } else {
                        $wallet_comment = 'Book Now';
                        $template_slug = 'book-now-enquiry';
                        $record_name = "Book Now";
                    }

                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $email_params = array_merge($email_params,$booking_details_arr);
                        }
                    }

                    if($amount == 0) {
                        $order->method = $payment_method;
                        $order->payment_status =0; //Pending
                        $order->save();
                        $order_data = $this->offline_mail($order->id);
                        session(['order_id'=>$order->id]);
                        return redirect('payment/thankyou');
                    } 
                    if($request->wallet != 1){

                        if($payment_method == 'tpsl') {
                            $tpsl_type = $payment->perameter_1;
                            $merchantCode = $payment->perameter_2;
                            $key = $payment->perameter_3;
                            $iv = $payment->perameter_4;
                            $schemeCode = $payment->perameter_5;
                            $order->method = $payment_method;
                            $order->save();

                            // For Test account of tpsl
                            if($tpsl_type=='test') {
                                $amount = '1.00';
                            }

                            include public_path("/tpsl_payment_gatway/TransactionRequestBean.php");
                            $webServiceLocator = 'https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl';

                            //Setting all values here
                            $transactionRequestBean = new \TransactionRequestBean();
                            $val = $request;
                            $transactionRequestBean->merchantCode = $merchantCode;
                            $transactionRequestBean->ITC = $email;
                            $transactionRequestBean->customerName = $name;
                            $transactionRequestBean->requestType = 'T';
                            $transactionRequestBean->merchantTxnRefNumber = $order_no;
                            $transactionRequestBean->amount = $amount;
                            $transactionRequestBean->currencyCode ='INR';
                            $transactionRequestBean->returnURL = route('response_tpsl');
                            $transactionRequestBean->shoppingCartDetails = $schemeCode.'_'.$amount.'_0.0';
                            $transactionRequestBean->TPSLTxnID = rand(1, 10000);
                            $transactionRequestBean->mobileNumber = $phone;
                            $transactionRequestBean->txnDate = date('Y-m-d');
                            $transactionRequestBean->bankCode = '470';
                            $transactionRequestBean->custId = $order->id ?? rand(000000,999999);
                            $transactionRequestBean->key = $key;
                            $transactionRequestBean->iv = $iv;
                            $transactionRequestBean->accountNo = '';
                            $transactionRequestBean->webServiceLocator = $webServiceLocator;
                            $transactionRequestBean->timeOut = 30;

                            $responseDetails = $transactionRequestBean->getTransactionToken();
                            // prd($responseDetails);
                            $responseDetails = (array)$responseDetails;
                            if($responseDetails && isset($responseDetails[0])) {
                                $response = $responseDetails[0];
                            } else {
                                $response = url('/');
                            }
                            echo "<script>window.location = '" . $response . "'</script>";
                            ob_flush();

                        } elseif($payment_method == 'razorpay') {
                            $order->method = $payment_method;
                            $order->save();
                            session(['order_id'=>$order->id]);
                            $razorpay_amount = $amount * 100;
                            $notes = [];
                            $notes['order_no'] = $order->order_no;
                            // prd($payment->toArray());
                            $razorpay_order_data = array(
                                'receipt' => $order->order_no,
                                'amount' => $razorpay_amount,
                                'currency' => 'INR',
                                'notes' => $notes,
                                // 'transfers' => $accounts_arr,
                                // 'notes'=> array('key1'=> 'value3','key2'=> 'value2')
                            );
                            // prd($razorpay_order_data);
                            /*if(!empty($accounts_arr) && count($accounts_arr) > 0) {
                                $razorpay_order_data['transfers'] = $accounts_arr;
                            }*/
                            $RAZORPAY_KEY = $payment->perameter_1;
                            $RAZORPAY_SECRET = $payment->perameter_2;
                            // pr($RAZORPAY_KEY);
                            // prd($RAZORPAY_SECRET);
                            $api = new Api($RAZORPAY_KEY, $RAZORPAY_SECRET);
                            $pg_order_resp = $api->order->create($razorpay_order_data);
                            // prd($pg_order_resp);
                            /*Razorpay\Api\Order Object
                            (
                                [attributes:protected] => Array
                                    (
                                        [id] => order_MfH9yse2ByacI1
                                        [entity] => order
                                        [amount] => 350000
                                        [amount_paid] => 0
                                        [amount_due] => 350000
                                        [currency] => INR
                                        [receipt] => AKGND4067195
                                        [offer_id] => 
                                        [status] => created
                                        [attempts] => 0
                                        [notes] => Razorpay\Api\Order Object
                                            (
                                                [attributes:protected] => Array
                                                    (
                                                    )

                                            )

                                        [created_at] => 1695362682
                                    )

                            )*/

                            if(isset($pg_order_resp->id)) {
                                $pg_order_id = $pg_order_resp->id;
                                $order_payments->pg_order_id = $pg_order_id;
                                $order_payments->pg_response = json_encode($pg_order_resp->toArray());
                                $order_payments->save();
                            }
                        } elseif($payment_method == 'bank_payment') {

                            // $payment = PaymentGateway::where(['value'=>'bank_payment','status'=>1])->first();
                            $order->method = $payment_method;
                            $order->payment_status = 0; //Pending
                            $order->save();
                            $order_data = $this->offline_mail($order->id);
                            session(['order_id'=>$order->id]);
                            return redirect('payment/thankyou');
                        } 
                    } else {
                        $inventory_update = Self::inventory_update($order);

                        $old_balance = UserWallet::where('user_id',$userId)->sum('amount');
                        if($old_balance >= $amount){
                            $balance = $old_balance - $amount ;
                            $walletData  = array(
                                'user_id' =>$userId,
                                'type' => 'debit',
                                'amount' => -$amount ?? 0,
                                'fees' => $order->fees ?? 0,
                                'payment_method' => 'Order',
                                'balance' => $balance,
                                'for_date' => date('Y-m-d H:i:s'),
                                'txn_id' => $order->order_no,
                                'comment' => $wallet_comment,
                            );
                            $isSavedWallet = UserWallet::create($walletData);

                            $order->method = $payment_method;
                            $order->payment_status =1; //Confirmed
                            $order->save();

                            /*if($order->order_type == 5) {
                                $booking_details = json_decode($order->booking_details);
                                $accommodation_id = $booking_details->hotel_id?? 0;
                                $accommodation_data = Accommodation::find($accommodation_id);
                                $booking_mode = $accommodation_data->booking_mode?? 1;
                                if($booking_mode === 0 ){
                                    //vocher create for automatic hotel 
                                    // $order_no = $order->order_no?? '';
                                    // CustomHelper::createVoucher($order_no);
                                    // $order->orders_status = CustomHelper::getOrderStatus('Voucher Sent');
                                    // $order->status = 'Processing';
                                    // $order->save();
                                }
                            }elseif ($order->order_type == 6) {

                                $package_id = $order->package_id?? 0;
                                $package_data = Package::find($package_id);
                                $booking_mode = $package_data->booking_mode?? 1;
                                if($booking_mode === 0 ){
                                    //vocher create for automatic Activity 
                                    // $order_no = $order->order_no?? '';
                                    // CustomHelper::createVoucher($order_no);
                                    // $order->orders_status = CustomHelper::getOrderStatus('Voucher Sent');
                                    // $order->save();
                                }
                            }*/

                            if($order->last_payment_id) {
                                $order_payments = OrderPayments::where('id',$order->last_payment_id)->first();
                                $order_payments->pg_payment_status = 1; //confirmed
                                $order_payments->save();
                            }

                            // $orderNo = sha1($order->id);
                            // $resp = Self::bookingPayment($orderNo);
                            // session(['order_id'=>$order->id]);
                            // return redirect('payment/thankyou');

                            $response = [];
                            $response['success'] = false;
                            $response['redirect_url'] = '';
                            session(['order_id'=>$order->id]);
                            $resp = Order::processPaymentSuccess($order->id);
                            if($resp['success']) {
                                $response['success'] = true;
                                if($resp['redirect_url']) {
                                    $response['redirect_url'] = $resp['redirect_url'];
                                }
                            }

                            if($response['success']) {
                                if($response['redirect_url']) {
                                    return redirect($response['redirect_url']);
                                } else {
                                    return redirect('payment/thankyou');
                                }
                            } else {
                                return redirect('payment/thankyou');
                            }
                        } else {
                            return back()->with('alert-danger', 'Your wallet have insufficient fund');
                        }
                    }
                } else {
                    if(empty($check_inventory_message)) {
                        $check_inventory_message = "We're sold out for your dates";
                    }
                    return back()->with('alert-danger', $check_inventory_message);
                }
            }

            // $pg_order_id = 'order_MfJAMxaI6NXJzn';
            // $amount = 3500;
            // $razorpay_amount = 350000;
            // $name = 'Braham Dev Yadav';
            // $email = 'braham@indiainternets.com';
            // $phone = '9971655324';
            // $country_code = '91';
            // $payment_method = '';
            // $RAZORPAY_KEY = 'rzp_test_5Ef8Nr7NtZVmYN';
            if(isset($pg_order_id) && !empty($pg_order_id)) {
                $is_already_paid = false;
                $data['amount'] = $amount;
                $data['razorpay_amount'] = $razorpay_amount;
                $data['name'] = $name;
                $data['email'] = $email;
                $data['phone'] = $phone;
                $data['phone_country'] = $country_code;
                $data['is_already_paid'] = $is_already_paid;
                $data['order_no'] = $order->order_no;
                $data['pg_order_id'] = $pg_order_id;

                $data['method'] = $payment_method;
                $data['description'] = $order->order_no;
                $data['logoSrc'] = $logoSrc??'';
                $data['SYSTEM_TITLE'] = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $data['RAZORPAY_KEY'] = $RAZORPAY_KEY;

                if($is_already_paid == true) {
                    Session::flash('success', 'You have already paid for this order.');
                }
                return view('razorpay.razorpayView', $data);
            } else {
                $balance = 0;
                if($userId){
                    $balance = UserWallet::where('user_id',$userId)->sum('amount');
                }

                if($order->order_type == 2 || $order->order_type == 3 || $order->order_type == 7 || $order->order_type == 8) {
                    $data['payment_gateways'] = PaymentGateway::where(['status'=>1,'show'=> 1,'is_online'=>1])->get();
                } elseif($order->order_type == 4 && ($balance >= $order->total_amount)){
                    $data['payment_gateways'] = PaymentGateway::where(['status'=>1,'show'=> 1,'is_online'=>1])->get();
                } else {
                    $data['payment_gateways'] = PaymentGateway::where(['status'=>1,'show'=> 1])->get();
                }
                $data['balance'] = $balance;
                $data['meta_title'] = 'Review Booking Details - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $data['meta_description'] = 'Review Booking Details - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                return view(config('custom.theme').'.packages.pay_now', $data);                
            }
        } else {
            abort(404);
        }
    } else {
        abort(404);
    }
}

    public function thankyou(Request $request) {
        $order_id = (session()->has('order_id'))?session('order_id'):0;
        if($order_id) {
            session(['order_id'=>'']);
            // session()->forget('order_id');
            $order = Order::where('id',$order_id)->first();
            if($order) {
                $order_type = $order->order_type??'1';
                if($order_type == 5){
                    $booking_details = json_decode($order->booking_details);
                    $inventory = $order->inventory;
                    $params = [];
                    $params['action'] = 'hotel_booking';
                    $params['inventory_id'] = $booking_details->inventory_id ?? 0;
                    $params['checkin'] = $booking_details->checkin ? CustomHelper::DateFormat($booking_details->checkin,'Y-m-d','d/m/Y') : '';
                    $params['checkout'] = $booking_details->checkout ? CustomHelper::DateFormat($booking_details->checkout,'Y-m-d','d/m/Y') : '';
                    $params['inventory']= $inventory ?? 1;
                    $check_inventory = CustomHelper::checkInventory($params);

                    if($check_inventory){
                        $order_id = $order->id;
                        $booking_details = json_decode($order->booking_details);
                        $hotel_id = $booking_details->hotel_id??'';
                        $hotel_info = Accommodation::find($hotel_id);
                        $checkin_time = $hotel_info->checkin_time ?? '';
                        $checkout_time = $hotel_info->checkout_time ?? '';

                        $total_amount = $order->total_amount??0;
                        $sub_total_amount = $order->sub_total_amount??0;
                        /*$agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                        if($agent_id && $agent_id > 0) {
                            $total_amount = $order->sub_total_amount??0;
                        }*/

                        $hotel_data = array(
                            'hotel_name' => $booking_details->hotel_name ?? '',
                            'contact_phone' => $booking_details->contact_phone ?? '',
                            'contact_email' => $booking_details->contact_email ?? '',
                            'contact_person' => $booking_details->contact_person ?? '',
                            'room_name' => $booking_details->room_name ?? '',
                            'plan_name' => $booking_details->plan_name ?? '',
                            'destination_name' => $booking_details->destination_name ?? '',
                            'checkin' => !empty($booking_details->checkin)?date('d M Y',strtotime($booking_details->checkin)):'',
                            'checkin_time' => !empty($checkin_time)?date('h:i A',strtotime($checkin_time)):'',
                            'checkout' => !empty($booking_details->checkout)?date('d M Y',strtotime($booking_details->checkout)):'',
                            'checkout_time' => !empty($checkout_time)?date('h:i A',strtotime($checkout_time)):'',
                            'trip_date' => $booking_details->trip_date ?? '',
                            'created_at' => $order->created_at ? CustomHelper::DateFormat($order->created_at,'Y-m-d h:i A'): '',
                            'adult' => $booking_details->adult ?? '',
                            'child' => $booking_details->child ?? '',
                            'hotel_charge' => $total_amount ?? '',
                            'sub_total_amount' => $sub_total_amount ?? '',
                            'paid_amount' => $order->partial_amount ?? '',
                            'address' => $booking_details->address ?? '',
                            'inventory' => $booking_details->inventory ?? '',
                            'inclusions' => $booking_details->inclusions ?? '',
                        );
                        $data['hotel_data']= $hotel_data;
                        //prd($data['hotel_data']);
                    }
                }
                if($order_type == 3) {
                    $params = [];
                    $params['order'] = $order;
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details, true);
                        $params['booking_details'] = $booking_details;
                        // $data['flight_status'] = $booking_details['order']['status']??'';
                    }
                    $data['itineraries'] = view("emails._flight_booking_email", $params)->render();
                    $data['flight_status'] = $order->status??'';
                    $last_1_months = date('Y-m-d H:i:s', strtotime('-1 month'));
                    ApiCallLog::where('created_at','<',$last_1_months)->delete();

                    $seo_tags = CustomHelper::getSeoModules('flight');
                    $data['manager_email'] = $seo_tags->admin_email??'';
                    $data['manager_phone'] = $seo_tags->admin_phone??'';
                }
                if($order->method == 'tpsl') {
                    $emailData = $this->bookingPaymentDetail($order->id);
                    $data['paymentData'] = $emailData;
                    return view(config('custom.theme').'.paymentDetails', $data);
                } else if($order->method == 'razorpay') {
                    $emailData = $this->bookingPaymentDetail($order->id);
                    $data['paymentData'] = $emailData;
                    return view(config('custom.theme').'.paymentDetails', $data);
                } else if($order->method == 'wallet') {
                    $emailData = $this->bookingPaymentDetail($order->id);

                    $data['paymentData'] = $emailData;
                    return view(config('custom.theme').'.paymentDetails', $data);
                } else {
                    $order_data = $this->bankTransferDetails($order->id);
                    $payment = PaymentGateway::where(['value'=>'bank_payment','status'=>1])->first();
                    $perameter_1 = $payment->perameter_1;
                    $order_data['bank_details'] = $perameter_1;
                    return view(config('custom.theme').'.banktransferDetails', $order_data);
                }
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    //Package Booking

    public function response(Request $request){
        $orderNo = isset($request->item_number) ? $request->item_number:$item_number;
        if(!empty($orderNo)) {
            $order = Order::where('id',$orderNo)->first();
            $order->order_no = $orderNo;
            $order->save();
            $paymentMethod = $order->method;
            if($paymentMethod == 1) {
                return $this->paypalResponse($order, $request);
            }
        }
        return redirect(url('/'));
    }

    private function paypalResponse($order, $request){

      if(strtolower($order->method) == 1) {

        $success_transaction = false;

        $req = 'cmd=_notify-synch';
        $tx_token =$request->tx;

        $message2 = ""; //initialize the variable

        //demo
        $auth_token = '0g6Kf4n81BD1emz2tqs1s1o3s2_vTyO0vGfed_8cny8xxBxEw97byqgxRYK';

        $req .= "&tx=$tx_token&at=$auth_token";

        // post back to PayPal system to validate
        /*$header .= "POST /cgi-bin/webscr HTTP/1.0 \n";
        $header .= "Content-Type: application/x-www-form-urlencoded \n";
        $header .= "Content-Length: " . strlen($req) . " \r\n\r\n";
        //Online
        $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);*/


        $pp_hostname = "www.sandbox.paypal.com";//sandbox URL
        //$pp_hostname = "www.paypal.com"; //Live
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        //set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
        //if your server does not bundled with default verisign certificates.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
        $res = curl_exec($ch);
        curl_close($ch);

         $order->payment_response = json_encode($res);

         // Send data to the sentry
         $sendtoSentry  = serialize($res);

        if(!$res) {
            // HTTP ERROR
          $sendtoSentry  = 'No response from paypal';
          //CustomHelper::captureSentryMessage($message = $sendtoSentry);
        }
        else {
            //$req_data = serialize($_GET);
            $lines = explode("\n", $res);
            $keyarray = array();
            $payDetails = array();
            //prd($lines);
            if (strcmp ($lines[0], "SUCCESS") == 0) {

                for ($i=1; $i<count($lines);$i++) {
                    if(array_key_exists($i, $lines) && !empty($lines[$i])) {
                        list($key,$val) = explode("=", $lines[$i]);
                        $keyarray[urldecode($key)] = urldecode($val);
                    }
                }
                $payDetails['txn_id'] = $keyarray['txn_id'];
               // $ctc =  $request->cc;
                //$paypal_address_id = $keyarray['paypal_address_id'];
                $payDetails['payer_id'] = $keyarray['payer_id'];
                $payDetails['firstname'] = $keyarray['first_name'];
                $payDetails['lastname'] = $keyarray['last_name'];
                $payDetails['itemnumber'] = $keyarray['item_number'];
                $payDetails['itemname'] = $keyarray['item_name'];
                //$amount = $request->amt;
                $payDetails['payment_date'] = $keyarray['payment_date'];
                $payDetails['payer_email']= $keyarray['payer_email'];
                $payDetails['payer_name']= $payDetails['firstname']. ' ' .$payDetails['lastname'];

                //Do your paypal success codes here

                $success_transaction = true;
                //$order->order_status = 'confirmed';
            }

            elseif (strcmp ($lines[0], "FAIL") == 0) {
                $message2 = "Your payment transaction has failed.";

                //$sendtoSentry  = serialize($message2);
                //CustomHelper::captureSentryMessage($message = $sendtoSentry);
            }
        }

        if(!($success_transaction == true)){

        }else{
            $order->payment_status = 1; //confirmed
            $order->payment_description = json_encode($payDetails); //Recieved
            $isSaved = $order->save();
            $orderNo = sha1($order->id);
            // return redirect(url('booking/payment/'.$orderNo));
            $resp = Self::bookingPayment($orderNo);
            return redirect('payment/thankyou');
        }
      }
    }

    public function bookingPayment($orderNo) {
        if(!empty($orderNo)) {
            $data = [];
            $sms_template_slug = '';
            $order = Order::where(DB::raw('sha1(id)'), $orderNo)->first();
            $order_id =$order->id ;
            session(['order_id'=>$order->id]);
            if(!empty($order) &&  $order->payment_status == 1) {
                $order_type = $order->order_type??'1';
                $SYSTEM_TITLE = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo && $userAgentInfo->count() > 0){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';

                        $userData = $userAgentInfo->User ?? '';
                        $agent_phone = '';
                        $agent_email = '';
                        if($userData->phone) {
                            $country_code = $userData->country_code ?? '91';
                            $agent_phone = '+'.$country_code.'-'.$userData->phone;
                        }
                        $agent_email = !empty($userData->email)?$userData->email:'';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = asset('/storage/'.$path.$agentLogo);
                        }
                    }
                }

                $common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{agent_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                }else{
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                     '{company_logo}' => $logoSrc
                 );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }

                $contact_details = '';
                if(!empty($agent_id)){
                    $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                    $b2b_email_params = array(
                        '{agent_phone}' => $agent_phone,
                        '{agent_email}' => $agent_email
                    );
                    $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                   /* $contact_details .= 
                    '<tr>
                     <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                     <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Mobile:</b> <a href="tel:'.$agent_phone.'" style="color: #fff;text-decoration: none;">'.$agent_phone.'</a>; <b>Email:</b> <a href="mailto:'.$agent_email.'" style="color: #fff;text-decoration: none;">'.$agent_email.'</a></p>

                     <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>
                     </td>
                     </tr>';*/
                }else{

                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $SYSTEM_TITLE
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                /*$contact_details .= '<tr>
                <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
                <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

                <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

                <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$SYSTEM_TITLE.'. All Rights Reserved.</p>
                </td>
                </tr>';*/
            }

                $content = '';
                $record_name = '';
                $form_values = [];
                $form_values['{header}'] = $common_logo;
                $form_values['{logo}'] = $logoSrc;
                $form_values['{contact_details}'] = $contact_details??'';
                $form_values['{e_date}'] = date("l jS \of F Y h:i A");
                $form_values['{SYSTEM_TITLE}'] = $SYSTEM_TITLE;
                $form_values['{name}'] = $order->name;
                $form_values['{email}'] = $order->email;
                $phone = '';
                if($order->phone) {
                    $phone_country = $order->phone_country ?? '91';
                    $phone = '+'.$phone_country.'-'.$order->phone;
                }
                $form_values['{phone}'] = $phone;
                if($order_type == 3) { //Flight
                    $record_name = 'Booking Flight';
                    $sms_template_slug = 'flight-booking-sms';
                    $params = [];
                    $params['order'] = $order;
                    if($order->booking_details) {
                        $params['booking_details'] = json_decode($order->booking_details, true);
                    }
                    $itineraries = view('emails._flight_booking_email',$params)->render();
                    $form_values['{city}'] = $order->city;
                    if($order->trip_date) {
                        $form_values['{date}'] = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $form_values['{date}'] = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $form_values['{total_amount}'] = CustomHelper::getPrice($order->total_amount);
                    $form_values['{itineraries}'] = $itineraries;
                    $form_values['{booking_id}'] = $order->order_no??'';

                    $email_template = EmailTemplate::where('slug','flight-booking-email')->first();

                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();
                    $sms_content_arr = [];
                    $booking_details = json_decode($order->booking_details, true);
                    if(isset($booking_details['itemInfos']['AIR']['tripInfos']) && !empty($booking_details['itemInfos']['AIR']['tripInfos'])) {
                        foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo) {
                            $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
                            $dep_code = $tripInfo['sI'][0]['da']['code']??'';
                            $arr_code = $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'';
                            $sector = $dep_code.'-'.$arr_code;
                            $dep = $tripInfo['sI'][0]['dt']??'';
                            $arr = $tripInfo['sI'][count($tripInfo['sI'])-1]['at']??'';
                            $flight = $tripInfo['sI'][0]['fD']['aI']['name']??'';
                            $pnr = $travellerInfos[0]['pnrDetails'][$sector]??'';
                            $customer = ($travellerInfos[0]['fN'].' '.$travellerInfos[0]['lN'])?? '';
                            if(count($travellerInfos) > 1) {
                                $customer = $customer . ' +'.(count($travellerInfos)-1).' ';
                            }

                            $sms_params = array(
                                '{#var1#}' => $sector,
                                '{#var2#}' => $dep,
                                '{#var3#}' => $arr,
                                '{#var4#}' => $flight,
                                '{#var5#}' => $pnr,
                                '{#var6#}' => $customer,
                            );

                            $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                            $content = strtr($sms_content, $sms_params);
                            // $content = "Flight - Your ticket details for Sector : ".$sector." Dep:".$dep." Arr: ".$arr." Flight: ".$flight." PNR: ".$pnr." Customer: ".$customer." overlandescape.com";
                            $sms_content_arr[] = $content;
                        }
                    }
                    // prd($sms_content_arr);

                    $pdf = PDF::loadView("emails._flight_booking_pdf", $params);

                    $path = 'temp/';
                    $pdf_name = $order->order_no.'.pdf';
                    if (!File::exists(public_path("storage/" . $path))) {
                        File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
                    }
                    $pdf->save(public_path("storage/" . $path).$pdf_name);
                    $attachments = public_path("storage/".$path).$pdf_name;
                
                 } else if($order_type == 8) { // Rental

                    $trip_date = '';
                    if($order->trip_date) {
                        $trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $order_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');

                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $record_name = 'Rental Booking';
                    $template_slug = 'common-order-paid-email';
                    $sms_template_slug = 'rental-booking-sms';
                    $booking_details = $order->booking_details ?? '';
                    $booking_details_arr = json_decode($booking_details);

                    $form_values['{name}'] = $order->name??'';
                    $form_values['{order_date}'] = $order_date;
                    $form_values['{trip_date}'] =  CustomHelper::DateFormat($booking_details_arr->pickupDate,'d/m/Y')??'';
                    $form_values['{booking_for}'] = 'Rental';
                    $form_values['{booking_id}']  = $order->order_no??'';
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{package}']  = $booking_details_arr->bike_name.' '.$booking_details_arr->city_name;
                    $form_values['{duration}'] = '';
                    $form_values['{persons}'] = '';
                    $form_values['{start_time}'] = CustomHelper::DateFormat($booking_details_arr->pickupDate,'d/m/Y h:i A');
                    $form_values['{end_time}'] = CustomHelper::DateFormat($booking_details_arr->dropDate,'d/m/Y h:i A');
                    $form_values['{total_amount}'] = isset($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : '';
                    $form_values['{paid_amount}'] = isset($order->partial_amount) ? CustomHelper::getPrice($order->partial_amount) : '';
                    $form_values['{cab}'] = $booking_details_arr->bike_name??'';
                    $form_values['{pickup_address}'] = '';
                    $drop_address = $booking_details_arr->drop_address??'';
                    if($drop_address){
                        $drop_address = '/'.$drop_address;
                    }
                    $form_values['{drop_address}'] = '';
                    $form_values['{service_city}'] = $booking_details_arr->city_name??'';
                    $form_values['{destination}'] = $booking_details_arr->location_name??'';
                    $form_values['{phone}'] = $phone;
                    $form_values['{email}'] = $order->email??'';
                    $city = $order->city??'';
                    if($city){
                       $city = '/'.$city;
                    }
                    $form_values['{city}'] = $city_name??'';
                    $form_values['{address}'] = '';
                    $form_values['{trip_type}'] = '';
                    $form_values['{total_amount}'] = $order->total_amount??'';
                    $form_values['{payment_status}'] = $p_status??'';
                    $form_values['{payment_method}'] = CustomHelper::getPaymentGatewayName($order->method)??'';

                    $pickup_address = $booking_details_arr->pickup_address??'';
                    $drop_address = $booking_details_arr->drop_address??'';
                    $origin = $booking_details_arr->city_name??'';
                    $destination = $booking_details_arr->destination??'';
                    $order_no = $order->order_no??'';
                    $customer_name = $order->name??'';
                    $drop_destination_address = $drop_address."( ".$destination." )";

                    $trip_date_time = '';
                    if($order->trip_date) {
                        $trip_date_time = date('h:i A d M', strtotime($order->trip_date));
                        $trip_time = date('d M', strtotime($order->trip_date));
                    } else {
                        $trip_date_time = date('h:i A d M', strtotime($order->created_at));
                        $trip_time = date('d M', strtotime($order->created_at));
                    }

                    $sms_params = array(
                        '{#var1#}' => $origin,
                        '{#var2#}' => $pickup_address,
                        '{#var3#}' => $trip_date_time,
                        '{#var4#}' => $order_no, //drop_destination_address
                        '{#var5#}' => $customer_name, //trip_time
                    );
                    // $content = "Cab - Your cab details for Pickup: ".$origin." from ".$pickup_address." Time:".$trip_date_time."; Drop: ".$drop_destination_address." on ".$trip_time." Booking ID: ".$order_no." Customer: ".$customer_name." - overlandescape.com";
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }

                    $seo_tags = CustomHelper::getSeoModules('cab');
                    $manager_email = $seo_tags->admin_email??'';
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();                    
                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);
                } else if($order_type == 4) { // Cab

                    $trip_date = '';
                    if($order->trip_date) {
                        $trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $order_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');

                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $record_name = 'Booking Cab';
                    $template_slug = 'common-order-paid-email';
                    $sms_template_slug = 'cab-booking-sms';
                    $booking_details = $order->booking_details ?? '';
                    $booking_details_arr = json_decode($booking_details);

                    $form_values['{name}'] = $order->name??'';
                    $form_values['{order_date}'] = $order_date;
                    $form_values['{trip_date}'] = $trip_date;
                    $form_values['{booking_for}'] = 'Cab';
                    $form_values['{booking_id}']  = $order->order_no??'';
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{package}']  = $booking_details_arr->origin.' to '.$booking_details_arr->destination;
                    $form_values['{duration}'] = $booking_details_arr->duration??'';
                    $form_values['{persons}'] = '';
                    $form_values['{start_time}'] = '';
                    $form_values['{end_time}'] = '';
                    $form_values['{total_amount}'] = isset($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : '';
                    $form_values['{paid_amount}'] = isset($order->partial_amount) ? CustomHelper::getPrice($order->partial_amount) : '';
                    $form_values['{cab}'] = $booking_details_arr->cab_name??'';
                    $form_values['{pickup_address}'] = $booking_details_arr->pickup_address??'';
                    $drop_address = $booking_details_arr->drop_address??'';
                    if($drop_address){
                        $drop_address = '/'.$drop_address;
                    }
                    $form_values['{drop_address}'] = $drop_address??'';
                    $form_values['{service_city}'] = $booking_details_arr->origin??'';
                    $form_values['{destination}'] = $booking_details_arr->destination??'';
                    $form_values['{phone}'] = $phone;
                    $form_values['{email}'] = $order->email??'';
                    $city = $order->city??'';
                    if($city){
                       $city = '/'.$city;
                    }
                    $form_values['{city}'] = $city??'';
                    $form_values['{address}'] = $order->address1??'';
                    $form_values['{trip_type}'] = $booking_details_arr->trip_type??'';
                    $form_values['{total_amount}'] = $order->total_amount??'';
                    $form_values['{payment_status}'] = $p_status??'';
                    $form_values['{payment_method}'] = CustomHelper::getPaymentGatewayName($order->method)??'';


                    $pickup_address = $booking_details_arr->pickup_address??'';
                    $drop_address = $booking_details_arr->drop_address??'';
                    $origin = $booking_details_arr->origin??'';
                    $destination = $booking_details_arr->destination??'';
                    $order_no = $order->order_no??'';
                    $customer_name = $order->name??'';
                    $drop_destination_address = $drop_address."( ".$destination." )";

                    $trip_date_time = '';
                    if($order->trip_date) {
                        $trip_date_time = date('h:i A d M', strtotime($order->trip_date));
                        $trip_time = date('d M', strtotime($order->trip_date));
                    } else {
                        $trip_date_time = date('h:i A d M', strtotime($order->created_at));
                        $trip_time = date('d M', strtotime($order->created_at));
                    }
                    $sms_params = array(
                        '{#var1#}' => $origin,
                        '{#var2#}' => $pickup_address,
                        '{#var3#}' => $trip_date_time,
                        '{#var4#}' => $drop_destination_address,
                        '{#var5#}' => $trip_time??'',
                        '{#var6#}' => $order_no,
                        '{#var7#}' => $customer_name,
                    );
                    // $content = "Cab - Your cab details for Pickup: ".$origin." from ".$pickup_address." Time:".$trip_date_time."; Drop: ".$drop_destination_address." on ".$trip_time." Booking ID: ".$order_no." Customer: ".$customer_name." - overlandescape.com";
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();                    
                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);
                } else if($order_type == 5) { // Hotel


                        if($order->booking_details) {
                            $booking_details = json_decode($order->booking_details);
                            $accommodation_id = $booking_details->hotel_id?? 0;
                            $accommodation_data = Accommodation::find($accommodation_id);
                            $booking_mode = $accommodation_data->booking_mode?? 1;
                            if($booking_mode === 0 && ($order->total_amount == $order->partial_amount)){
                                    //vocher create for automatic hotel 
                                $order_no = $order->order_no?? '';
                                CustomHelper::createVoucher($order_no);
                                
                                //Order Status=====
                                // $order->orders_status = CustomHelper::getOrderStatus('Voucher Sent');
                                // $order->status = 'Processing';
                                // $order->save();
                                //======
                            }
                        }

                    $trip_date = '';
                    if($order->trip_date) {
                        $trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $order_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');

                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $record_name = 'Booking Hotel';
                    $template_slug = 'common-order-paid-email';
                    $sms_template_slug = 'hotel-booking-sms';
                    $booking_details = $order->booking_details ?? '';
                    $booking_details_arr = json_decode($booking_details);
                    $city = $booking_details_arr->destination_name;

                    $adult = !empty($booking_details_arr->adult) ? $booking_details_arr->adult:'';
                    $child = !empty($booking_details_arr->child) ? $booking_details_arr->child :'';

                    $form_values['{name}'] = $order->name??'';
                    $form_values['{order_date}'] = $order_date;

                    $form_values['{booking_for}'] = 'Hotel';
                    $form_values['{booking_id}']  = $order->order_no??'';
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{total_amount}'] = isset($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : '';
                    $form_values['{paid_amount}'] = isset($order->partial_amount) ? CustomHelper::getPrice($order->partial_amount) : '';
                    $form_values['{phone}'] = $phone;
                    $form_values['{email}'] = $order->email??'';
                    $form_values['{city}'] = $city??'';
                    $form_values['{address}'] = $order->address1??'';
                    $form_values['{trip_type}'] = $booking_details_arr->trip_type??'';
                    $form_values['{total_amount}'] = $order->total_amount??'';
                    $form_values['{payment_status}'] = $p_status??'';
                    $form_values['{payment_method}'] = CustomHelper::getPaymentGatewayName($order->method)??'';

                    $form_values['{package}'] = $booking_details_arr->hotel_name ?? '';
                    if($child){
                        $child = '/'.$child.' '."Child";
                    }
                    $form_values['{persons}'] = $adult.' '.'Adult'.' '.$child;
                    $form_values['{pickup_address}'] = '';
                    $form_values['{drop_address}'] = '';
                    $form_values['{cab}'] = '';
                    $form_values['{trip_date}'] = $trip_date;

                    
                    $order_no = isset($order->order_no) ? $order->order_no : '';
                    $checkin =  !empty($booking_details_arr->checkin) ? CustomHelper::DateFormat($booking_details_arr->checkin,'d-M-Y','d/m/Y') : '';
                    $checkout =  !empty($booking_details_arr->checkout) ? CustomHelper::DateFormat($booking_details_arr->checkout,'d-M-Y','d/m/Y') : '';
                    $hotel_name = isset($booking_details_arr->hotel_name) ? $booking_details_arr->hotel_name : '';
                    $guest_name = isset($order->name) ? $order->name : '';
                    $adult = isset($booking_details_arr->adult) ? $booking_details_arr->adult-1 : '';
                    $child = isset($booking_details_arr->child) ? $booking_details_arr->child : 0;
                    if(isset($adult) && $adult >= 1){
                        $extra_guests = $guest_name." + ".$adult+$child;
                    } else {
                        $extra_guests = $guest_name;
                    }
                    $sms_params = array(
                        '{#var1#}' => $order_no,
                        '{#var2#}' => $checkin,
                        '{#var3#}' => $checkout,
                        '{#var4#}' => $hotel_name,
                        '{#var5#}' => $extra_guests,
                    );
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }
                    $hotel_id = $booking_details->hotel_id??'';
                    $hotel_info = Accommodation::find($hotel_id);
                    $checkin_time = $hotel_info->checkin_time ?? '';
                    $checkout_time = $hotel_info->checkout_time ?? '';

                    //duration
                    $checkin_show = strtotime($checkout);
                    $checkout_show =  strtotime($checkin);
                    $days_between = ceil(abs($checkout_show - $checkin_show) / 86400);
                    $form_values['{duration}'] = $days_between.' Days';

                    $form_values['{start_time}'] = !empty($checkin_time)?date('h:i A',strtotime($checkin_time)):'';
                    $form_values['{end_time}'] = !empty($checkout_time)?date('h:i A',strtotime($checkout_time)):'';                     

                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();
                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);

                } else if($order_type == 2) { // Pay-online
                    $record_name = 'Booking Pay Online';
                    $template_slug = 'pay-online-success-transaction';
                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $form_values['{transaction_id}'] = !empty($payment_details) ? $payment_details->txn_id : "";
                    $form_values['{sub_total_amount}'] = !empty($order->sub_total_amount) ? CustomHelper::getPrice($order->sub_total_amount) : "";
                    $form_values['{payment_date}'] = $payment_details->payment_date??'';
                    $form_values['{total_amount}'] = !empty($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : "";
                    // $form_values['{method}'] = ($order->method == 1) ? "PAYPAL":"BANK TRANSFER";
                    $form_values['{method}'] = CustomHelper::getPaymentGatewayName($order->method);
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{phone_number}'] = $phone;
                    $form_values['{package_name}'] = $payment_details->itemname ?? '';
                    $form_values['{payment_status}'] = $p_status;
                    $form_values['{payer_name}'] = $order->name??'';
                    $form_values['{comment}'] = $order->comment??'';
                    $form_values['{payment_acc_payer_name}'] = $order->name??'';
                    $form_values['{payer_email}'] = $order->email??'';
                    $form_values['{paypal_id}'] = '';
                    $form_values['{email}'] = $order->email??'';
                    $form_values['{date}'] = date("l jS \of F Y h:i A");

                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

                    //Save Orders Status
                    $order->orders_status = CustomHelper::getOrderStatus('SUCCESS');
                    $order->status = 'SUCCESS';
                    $order->save();

                }else if($order_type == 7) { // Pay-online
                    $record_name = 'Booking Pay Online';
                    $template_slug = 'pay-online-success-transaction';
                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                    $form_values['{transaction_id}'] = !empty($payment_details) ? $payment_details->txn_id : "";
                    $form_values['{sub_total_amount}'] = !empty($order->sub_total_amount) ? CustomHelper::getPrice($order->sub_total_amount) : "";
                    $form_values['{payment_date}'] = $payment_details->payment_date??'';
                    $form_values['{total_amount}'] = !empty($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : "";
                    // $form_values['{method}'] = ($order->method == 1) ? "PAYPAL":"BANK TRANSFER";
                    $form_values['{method}'] = CustomHelper::getPaymentGatewayName($order->method);
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{phone_number}'] = $phone;
                    $form_values['{package_name}'] = $payment_details->itemname ?? '';
                    $form_values['{payment_status}'] = $p_status;
                    $form_values['{payer_name}'] = $order->name??'';
                    $form_values['{comment}'] = $order->comment??'';
                    $form_values['{payment_acc_payer_name}'] = $order->name??'';
                    $form_values['{payer_email}'] = $order->email??'';
                    $form_values['{paypal_id}'] = '';
                    $form_values['{email}'] = $order->email??'';
                    $form_values['{date}'] = date("l jS \of F Y h:i A");

                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

                    //Save Orders Status
                    $order->orders_status = CustomHelper::getOrderStatus('SUCCESS');
                    $order->status = 'SUCCESS';
                    $order->save();

                } else { //Package and activity payment
                    $trip_date = '';
                    $template_slug = 'common-order-paid-email';
                    if($order->trip_date) {
                        $trip_date = CustomHelper::DateFormat($order->trip_date, 'd/m/Y', 'Y-m-d');
                    } else {
                        $trip_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    }
                    $order_date = CustomHelper::DateFormat($order->created_at, 'd/m/Y', 'Y-m-d');
                    $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                    $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }
                    $booking_for = (isset($order->Package->is_activity) && $order->Package->is_activity == 1) ? "Activity" : "Package";
                    $record_name = $booking_for.' '.'Booking';

                    $booking_data = $order->booking_details ?? '';
                    $booking_details = json_decode($booking_data);
                    if($order->Package->is_activity == 1){
                        $duration = !empty($booking_details->duration) ? $booking_details->duration : "";
                        $total_pax = !empty($booking_details->total_pax) ? $booking_details->total_pax : "";
                        $form_values['{persons}'] = $total_pax;
                        $date_val = !empty($booking_details->start_time) ? date('d-M-Y', strtotime($booking_details->start_time)):'';
                        $time_val = !empty($booking_details->start_time) ? date('h:i A', strtotime($booking_details->start_time)):'';
                        $end_time = !empty($booking_details->end_time)?date('D, M dS Y h:i A',strtotime($booking_details->end_time)):'';
                        $order_package_name = !empty($order->package_name) ? CustomHelper::wordsLimit($order->package_name, 22) : '';
                        $sms_template_slug = 'activity-booking-sms';
                        $sms_params = array(
                            '{#var1#}' => $order_package_name??'',
                            '{#var2#}' => $order->order_no??0,
                            '{#var3#}' => $date_val??'',
                            '{#var4#}' => $time_val??'',
                            '{#var5#}' => $duration??'',
                            '{#var6#}' => $total_pax??'',
                        );

                        $package_id = $order->package_id?? 0;
                        $package_data = Package::find($package_id);
                        $booking_mode = $package_data->booking_mode?? 1;
                        if($booking_mode === 0 && ($order->total_amount == $order->partial_amount)){
                        //vocher create for automatic Activity 
                            $order_no = $order->order_no?? '';
                            CustomHelper::createVoucher($order_no);

                        //Order Status=====
                            /*$order->orders_status = CustomHelper::getOrderStatus('Voucher Sent');
                            // $order->status = 'Processing';
                            $order->save();*/
                                //======
                        }

                    }else{
                        $duration = (isset($order->Package->package_duration) && !empty($order->Package->package_duration)) ? $order->Package->package_duration : "";
                        $start_time = '';
                        $end_time = '';
                        $destination = (isset($order->Package->packageDestination->name) && !empty($order->Package->packageDestination->name)) ? $order->Package->packageDestination->name : "";
                        $start_time_val = !empty($booking_details->start_time) ? date('d-M-Y h:i A', strtotime($booking_details->start_time)): date('d-M-Y h:i A', strtotime($order->trip_date));
                        $end_time_val = !empty($booking_details->end_time) ? date('d-M-Y h:i A',strtotime($booking_details->end_time)):'N/A';
                        $total_pax = !empty($booking_details->total_pax) ? $booking_details->total_pax-1 : "";
                        $order_package_name = !empty($order->package_name) ? CustomHelper::wordsLimit($order->package_name, 22) : '';
                        $show_total_pax = '';
                        if($total_pax >= 1){
                            $show_total_pax = $order->name." + ".$total_pax;
                        }else{
                            $show_total_pax = $order->name ?? '';
                        }
                        $sms_template_slug = 'holiday-booking-sms';
                        $sms_params = array(
                            '{#var1#}' => $order->order_no??'',
                            '{#var2#}' => $destination??'',
                            '{#var3#}' => $start_time_val??'',
                            '{#var4#}' => $end_time_val??'',
                            '{#var5#}' => $order_package_name??'',
                            '{#var6#}' => $show_total_pax??'',
                        );

                        $total_pax = !empty($booking_details->total_pax) ? $booking_details->total_pax : "";

                        $form_values['{persons}'] = $total_pax;
                    }

                    

                    //$start_time = date('D, M dS Y h:i A',strtotime($booking_details->start_time)):'';
                    //$end_time = date('D, M dS Y h:i A',strtotime($booking_details->end_time)):'';

                    $order_address1 = (isset($order->address1) && !empty($order->address1)) ? $order->address1 : "";
                    $order_address2 = (isset($order->address2) && !empty($order->address2)) ? $order->address2 : "";
                    $order_addresses = $order_address1.", ".$order_address2;
                    $destination = (isset($order->Package->packageDestination->name) && !empty($order->Package->packageDestination->name)) ? $order->Package->packageDestination->name : "";


                    /*$total_pax_unit = CustomHelper::getTotalPaxUnit($order);
                    if($total_pax_unit > 0){
                        $total_pax_unit = '';
                    }*/

                    $form_values['{name}'] = $order->name??'';
                    $form_values['{order_date}'] = $order_date;
                    $form_values['{trip_date}'] = $trip_date;
                    $form_values['{booking_for}'] = $booking_for;
                    $form_values['{order_no}'] = $order->order_no??'';
                    $form_values['{package}'] = $order->package_name ?? '';
                    $form_values['{duration}'] = $duration;
                    $form_values['{total_amount}'] = isset($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : '';
                    $form_values['{paid_amount}'] = isset($order->partial_amount) ? CustomHelper::getPrice($order->partial_amount) : '';
                    $form_values['{phone}'] = $phone;
                    $form_values['{email}'] = $order->email ?? '';
                    // $form_values['{city}'] = $order->city ?? '';
                    $form_values['{city}'] = $destination ?? '';
                    $form_values['{pickup_address}'] = '';
                    $form_values['{drop_address}'] = '';
                    $form_values['{cab}'] = '';
                    $form_values['{address}'] = $order_addresses ?? '';
                    $form_values['{payment_status}'] = $p_status;
                    $form_values['{payment_method}'] = CustomHelper::getPaymentGatewayName($order->method)??'';
                    // $form_values['{transaction_id}'] = !empty($payment_details) ? $payment_details->txn_id : "";
                    // $form_values['{sub_total_amount}'] = !empty($order->sub_total_amount) ? CustomHelper::getPrice($order->sub_total_amount) : "";
                    // $form_values['{payment_date}'] = $payment_details->payment_date??'';
                    // $form_values['{total_amount}'] = !empty($order->total_amount) ? CustomHelper::getPrice($order->total_amount) : "";
                    // // $form_values['{method}'] = ($order->method == 1) ? "PAYPAL":"BANK TRANSFER";
                    // $form_values['{method}'] = $order->method;
                    // $form_values['{order_no}'] = $order->order_no??'';
                    // $form_values['{package_name}'] = $payment_details->itemname ?? '';
                    // $form_values['{payment_status}'] = $p_status;
                    // $form_values['{payer_name}'] = $order->name??'';
                    // $form_values['{comment}'] = $order->comment??'';
                    // $form_values['{payment_acc_payer_name}'] = $order->name??'';
                    // $form_values['{payer_email}'] = $order->email??'';
                    // $form_values['{paypal_id}'] = '';
                    // $form_values['{email}'] = $order->email??'';
                    // $form_values['{date}'] = date("l jS \of F Y h:i A");
                    if($order->booking_details) {
                        $booking_details = json_decode($order->booking_details);
                        if(!empty($booking_details)) {
                            $booking_details_arr = [];
                            foreach($booking_details as $key => $val) {
                                $booking_details_arr['{'.$key.'}'] = $val;
                            }
                            $form_values = array_merge($form_values,$booking_details_arr);
                        }
                    }
                    if($order->Package->is_activity == 1){
                    $start_time = !empty($booking_details->start_time)?date('D, M dS Y h:i A',strtotime($booking_details->start_time)):'';
                    $end_time = !empty($booking_details->end_time)?date('D, M dS Y h:i A',strtotime($booking_details->end_time)):'';
                    }
                    $form_values['{start_time}'] = $start_time;
                    $form_values['{end_time}'] = $end_time;
                    // $email_template = EmailTemplate::where('slug', 'transaction-successfull-email')->where('status', 1)->first();
                    $email_template = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                    $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();
                    $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                    $content = strtr($sms_content, $sms_params);
                }

                if(isset($sms_content_data) && !empty($sms_content_data)) {
                    if(isset($sms_content_arr) && !empty($sms_content_arr)) {
                        $sms_content_arr = $sms_content_arr;
                    } else if(!empty($content)) {
                        $sms_content_arr = [];
                        $sms_content_arr[] = $content;
                    }
                    if(isset($sms_content_arr) && !empty($sms_content_arr)) {
                        foreach($sms_content_arr as $content) {
                            if(!empty($content)) {
                                $content = urlencode($content);
                                $params  = [];
                                $params['phone'] = $phone??'';
                                $params['content'] = $content;
                                $params['template_id'] = $sms_content_data->template_id??'';
                                $is_sms_sent = CustomHelper::send_sms($params);
                            }
                        }
                    }
                }

                if(!empty($order->email) && !empty($email_template)) {
                    $email = $order->email;
                    $cc_email = '';
                    $bcc_email_arr = [];
                    if($order->inventory_id) {
                        $supplier_email = $order->supplierData->email??'';
                        if($supplier_email) {
                            $bcc_email_arr[] = $supplier_email;
                        }
                    }
                    $search_arr = array_keys($form_values)??[];
                    $replace_arr = array_values($form_values)??[];
                    $email_subject = str_replace($search_arr, $replace_arr, $email_template->subject);
                    $search_arr = array_keys($form_values)??[];
                    $replace_arr = array_values($form_values)??[];
                    $email_content = str_replace($search_arr, $replace_arr, $email_template->content);
                    $email_bcc_admin = $email_template->bcc_admin ?? 0;
                    $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

                    //Dynamic send mail customer or Admin
                    if($email_type == 'admin'){
                        $email = $ADMIN_EMAIL;
                    }

                    if($email_bcc_admin == 1){
                        $bcc_email_arr[] = $ADMIN_EMAIL;
                    }
                    $bcc_email = (!empty($bcc_email_arr))?implode(',', $bcc_email_arr):'';

                    // Email to Admin
                    $params = [];
                    $params['to'] = $email;
                    $params['reply_to'] = $ADMIN_EMAIL;
                    $params['cc'] = $cc_email;
                    $params['bcc'] = $bcc_email;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    if(isset($attachments)) {
                        $params['attachments'] = $attachments;
                    }
                    $params['module_name'] = $record_name;
					$params['record_id'] = $order->id ?? 0;
                    $isSent = CustomHelper::sendCommonMail($params);
                }
            }
        }
    }

    public function bookingPaymentDetail($order_id) {
        $orderNo = isset($request->order_no) ? $request->order_no:'';
        if(!empty($order_id)) {
            $data = [];
            $order = '';
            //DB::enableQueryLog();
            // $order = Order::where(DB::raw('sha1(id)'), $orderNo)->first();
            $order = Order::where(DB::raw('id'), $order_id)->first();
            if(!empty($order)) {

                $total_amount = $order->total_amount??0;
                $payment_details = !empty($order->payment_description) ? json_decode($order->payment_description) : "";
                $emailData['txn_id']= !empty($payment_details) ? $payment_details->txn_id : "";
                $emailData['sub_total_amount']= $order->sub_total_amount;
                $emailData['total_amount']= $total_amount??0;
                $emailData['method'] = CustomHelper::getPaymentGatewayName($order->method)??'';
                $emailData['order_no'] = $order->order_no;
                $emailData['status'] = $order->payment_status;
                $emailData['itemname'] = $payment_details->itemname ?? '';
                $emailData['payer_name'] = $order->name ?? '';
                $emailData['payer_email'] = $order->email ?? '';
                $emailData['order_type'] = $order->order_type ?? '';
                $emailData['payment_date'] = $payment_details->payment_date ?? '';
                $emailData['name'] = $order->name;
                $emailData['comment'] = $order->comment;
                $emailData['pay_type'] = $order->pay_type;
                $emailData['partial_amount'] = $order->partial_amount;
                $emailData['order_type'] = $order->order_type;
                $emailData['order_date'] = $order->created_at;
                $email = $order->email;
                $emailData['email'] = $email;

                /*$email_subject = "Payment Of ".$emailData['itemname'];
                $REPLYTO = $email;
                $to_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                $from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
                $query_email = CustomHelper::sendEmail('emails.transaction_successfull_email', $emailData, $to=$to_email, $from_email, $REPLYTO = $from_email, $email_subject);
                $query_email2 = CustomHelper::sendEmail('emails.transaction_successfull_email', $emailData, $to=$email, $from_email, $REPLYTO = $from_email, $email_subject);*/

                return $emailData;
                // return view(config('custom.theme').'.paymentDetails', $data);
            }
        }
    }

    public function bankTransferDetails($order_id){
        if(!empty($order_id)){
            $data = [];
            // $order = '';
            //DB::enableQueryLog();
            $order = Order::where('id', $order_id)->first();
            // if($order && $order->package_id) {
            if($order) {

                $total_amount = $order->total_amount??0;

                $package = $order->Package??[];
                $package_title = $package->title??'';
                $emailData = [];
                $emailData['sub_total_amount']= $order->sub_total_amount;
                $emailData['total_amount']= $total_amount??0;
                $emailData['method'] = CustomHelper::getPaymentGatewayName($order->method)??'';
                $emailData['order_no'] = $order->order_no??'';
                $emailData['status'] = $order->payment_status;
                $emailData['itemname'] = $package_title;
                $emailData['payer_name'] = $order->name;
                $emailData['payer_email'] = $order->email;
                $emailData['comment'] = $order->comment;
                $emailData['pay_type'] = $order->pay_type;
                $emailData['partial_amount'] = $order->partial_amount;
                $emailData['order_type'] = $order->order_type;
                $emailData['order_date'] = $order->created_at;
                $data['bankData'] = $emailData;
                return $data;
            }
        }
    }

    public function offline_mail($order_id ,$type='') {
        if(!empty($order_id)){
            $data = [];
            $userBalance = 0;
            $order = Order::find($order_id);
            if($order) {
                $userBalance = UserWallet::where('user_id', $order->user_id)->sum('amount');
                $package = $order->Package??[];
                $package_title = $package->title??'';
                $destination_name = $package->packageDestination->name??'';
                $emailData = [];
                $emailData['sub_total_amount']= $order->sub_total_amount;
                $emailData['total_amount']= $order->total_amount;
                $emailData['method'] = CustomHelper::getPaymentGatewayName($order->method);
                $emailData['order_no'] = $order->order_no??'';
                $emailData['status'] = $order->payment_status;
                $emailData['itemname'] = $package_title;
                $emailData['payer_name'] = $order->name;
                $emailData['payer_email'] = $order->email;
                $emailData['comment'] = $order->comment;

                $payment = PaymentGateway::where(['value'=>'bank_payment','status'=>1])->first();
                $perameter_1 = $payment->perameter_1;
                $emailData['bank_details'] = $perameter_1;

                $email = $order->email;
                $order_phone = '';
                if($order->phone) {
                    $country_code = $order->country_code ?? '91';
                    $order_phone = '+'.$country_code.'-'.$order->phone;
                }
                // $email_subject = "Payment Of ".$package_title;
                $REPLYTO = $email;

                $websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO']);
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
                    $logo = $websiteSettingsArr['FRONTEND_LOGO'];
                    if($storage->exists($path.$logo)){
                        $logoSrc = asset('/storage/'.$path.$logo);
                    }
                }

                // AGENT LOGO
                $userAgentInfo = ''; $agentLogo = '';
                $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo && $userAgentInfo->count() > 0){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';

                        $userData = $userAgentInfo->User ?? '';
                        $agent_phone = '';
                        $agent_email = '';
                        if($userData->phone) {
                            $country_code = $userData->country_code ?? '91';
                            $agent_phone = '+'.$country_code.'-'.$userData->phone;
                        }
                        $agent_email = !empty($userData->email)?$userData->email:'';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = asset('/storage/'.$path.$agentLogo);
                        }
                    }
                }

                $common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{agent_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                }else{
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                       '{company_logo}' => $logoSrc
                   );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }

                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);


                $contact_details = '';
                if(!empty($agent_id)){
                    $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                    $b2b_email_params = array(
                        '{agent_phone}' => $agent_phone,
                        '{agent_email}' => $agent_email
                    );
                    $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                }else{

                    $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                    $b2c_email_params = array(
                        '{company_name}' => $company_name,
                        '{sales_mobile}' => $sales_mobile,
                        '{sales_phone}' => $sales_phone,
                        '{sales_email}' => $sales_email,
                        '{company_title}' => $system_title
                    );
                    $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                }

                $p_status = "Pending"; if($order->payment_status == 1){ $p_status = "Confirmed"; }elseif($order->payment_status == 0){ $p_status = "Pending"; }elseif($order->payment_status == 2){ $p_status = "Failed"; }

                $actual_amt = 0;
                if($type == 'wallet') {
                    $actual_amt = $order->sub_total_amount??0;
                } else {
                    $actual_amt = $order->total_amount??0;
                }

                $email_params = array(
                    '{sub_total_amount}' => isset($order->sub_total_amount) ? CustomHelper::getPrice($order->sub_total_amount) : '',
                    '{total_amount}' => isset($actual_amt) ? CustomHelper::getPrice($actual_amt) : '',
                    '{method}' => CustomHelper::getPaymentGatewayName($order->method),
                    '{order_no}' => $order->order_no,
                    // '{itemname}' => $package_title,
                    '{status}' => $p_status,
                    '{comment}' => $order->comment,
                    '{name}' => $order->name,
                    '{payer_name}' => $order->name,
                    '{phone}' => $order_phone,
                    '{payer_email}' => $order->email,
                    '{bank_details}' => $perameter_1,
                    '{header}' => $common_logo,
                    '{contact_details}' => $contact_details??'',
                    //'{date}' => date("l jS \of F Y h:i A"),
                    '{date}' => date("d/m/Y"),
                );

                if($order->order_type == 5) {
                    $booking_details = $order->booking_details;
                    $booking_details_arr = json_decode($booking_details);
                    $package_title = $booking_details_arr->hotel_name ??'';
                    $destination_name = $booking_details_arr->destination_name ??'';
                }
                $email_params['{itemname}'] = $package_title;
                $email_params['{city}'] = $destination_name;

                $subject_params = array(
                    '{package_title}' => $package_title,
                );

                $template_slug = 'bank-transfer-details-email';
                $sms_template_slug = '';
                if($type == 'wallet') {
                    $template_slug = 'wallet-email';
                    $sms_template_slug = 'wallet-sms-update';
                } 
                    $sms_params = array(
                        '{#var1#}' => CustomHelper::getPrice($actual_amt)??0,
                        '{#var2#}' => date("d-M-Y h:i A"),
                        '{#var3#}' => CustomHelper::getPrice($userBalance)??0,
                    );

                $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();

                $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
                $content = strtr($sms_content, $sms_params);

                $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
                // $email_params = isset($email_params) ? $email_params : [];
                $email_content = strtr($email_content, $email_params);

                $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';
                // $subject_params = isset($subject_params) ? $subject_params : [];
                $email_subject = strtr($email_subject, $subject_params);
                $email_type = isset($email_content_data->email_type) ? $email_content_data->email_type : '';
                $email_bcc_admin = isset($email_content_data->bcc_admin) ? $email_content_data->bcc_admin : 0;

                // $viewHtml = view('emails.banktransferDetails_email', $email_params)->render();
                // $email_content = strtr($viewHtml, $email_params);

                $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                $to_email = $order->email;
                $cc_email = '';
                $bcc_email_arr = [];
                if($order->inventory_id) {
                    $supplier_email = $order->supplierData->email??'';
                    if($supplier_email) {
                        $bcc_email_arr[] = $supplier_email;
                    }
                }
                if($email_type == 'admin'){
                    $to_email = $ADMIN_EMAIL;
                }
                if($email_bcc_admin == 1){
                    $bcc_email_arr[] = $ADMIN_EMAIL;
                }

                if(isset($sms_content_data) && !empty($sms_content_data) && !empty($content)) {
                    $content = urlencode($content);
                    $params  = [];
                    $params['phone'] = $order_phone ?? '';
                    $params['content'] = $content;
                    $params['template_id'] = $sms_content_data->template_id ?? '';
                    $is_sms_sent = CustomHelper::send_sms($params);
                }

                if(isset($email_content_data) && !empty($email_content_data)) {
                    $bcc_email = (!empty($bcc_email_arr))?implode(',', $bcc_email_arr):'';
                    $params = [];
                    $params['to'] = $to_email;
                    $params['reply_to'] = $REPLYTO;
                    $params['cc'] = $cc_email;
                    $params['bcc'] = $bcc_email;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    $params['module_name'] = 'Book without payment / Bank payment';
					$params['record_id'] = $order_id ?? 0;
                    $is_mail1 = CustomHelper::sendCommonMail($params);
                }
                $data['bankData'] = $emailData;
                return $data;
            }
        }
    }

    public function orderDetails(Request $request) {
        $data = [];
        $limit = $this->limit;
        $data['meta_title'] = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        $id = isset($request->id) ? $request->id : 0;
        $order = "";
        $title = "Order Details";

        if (is_numeric($id) && $id > 0) {
            $order = Order::where("id", $id)->first();
            $title = "Order Details";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["order"] = $order;
        $data["id"] = $id;
        return view("config('custom.theme').'.order_details", $data);
    }

    public function success(Request $request) {
        $data = [];
        $limit = $this->limit;
        $data['meta_title'] = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        return view("config('custom.theme').'.success_fail", $data);
    }

/* end of controller */
}
