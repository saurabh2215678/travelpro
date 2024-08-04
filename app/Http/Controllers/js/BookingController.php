<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\PackagePriceInfo;
use App\Models\Accommodation;
use App\Models\BikeMaster;
use App\Models\AccommodationInventory;
use App\Models\Country;
use App\Models\BikeCities;
use App\Models\BikeCityLocation;
use App\Models\BikecityPrice;
use App\Models\Coupon;
use App\Models\User;
use App\Helpers\CustomHelper;
use Inertia\Inertia;
use Validator;
use Response;
use File;
use DateTime;

class BookingController extends Controller {

	private $limit;
    private $theme;

    public function __construct() {
    	$this->limit = 20;
        $this->theme = config('custom.themejs');
    }

    public function book_now(Request $request) {
        $method = $request->method();
        if($method == 'POST') {
            $booking_data = $request->toArray();
            $response = [];
            $response['success'] = false;
            $response['message'] = '';
            $rules = [];
            $nicknames = [];
            $action = $request->action??'';

            // prd($request->toArray());

            if($action == 'hotel_booking') {
                $nicknames['inventory_id'] = 'Room';
                $rules['inventory_id'] = 'required';
            }else if($action == 'rental_booking') {
                $rules['bike_id'] = 'required';
            }  else {
                $nicknames['package'] = 'Package';
                $nicknames['package_type'] = 'Package Type';
                $nicknames['trip_date'] = 'Trip Date';
                $nicknames['trip_slot'] = 'Trip Slot';
                $nicknames['travellers'] = 'Travellers';

                $rules['package'] = 'required';
                $rules['package_type'] = 'required';
                if($request->is_download_itinerary == 1) {
                    $rules['trip_date'] = 'nullable';
                } else {
                    $rules['trip_date'] = 'required';
                }
                $rules['trip_slot'] = 'nullable';

                $total_amount = 0;
                $total_pax = 0;
                if($request->package && $request->package_type) {
                    $package = Package::find($request->package);
                    $packagePrice = PackagePriceInfo::find($request->package_type);
                    $packagePriceCategory = $package->packagePriceCategory??[];
                    $priceCategoryElements = $packagePriceCategory->priceCategoryElements??[];
                    $category_choices_record = $packagePrice->category_choices_record??[];
                    $show_choices_record = $packagePrice->show_choices_record??[];

                    $category_choices_record_arr = [];
                    if($category_choices_record) {
                        $category_choices_record_arr = json_decode($category_choices_record,true);
                    }

                    $show_choices_record_arr = [];
                    if($show_choices_record) {
                        $show_choices_record_arr = json_decode($show_choices_record,true);
                    }
                    // prd($category_choices_record_arr);
                    $travellers = [];
                    if(!empty($priceCategoryElements)) {
                        foreach($priceCategoryElements as $pce) {
                            if(array_key_exists('pce'.$pce->id, $show_choices_record_arr)) {
                                $default = $show_choices_record_arr['pce'.$pce->id]['default']??'';
                                if($default != 'pce'.$pce->id.'_null') {
                                    $pce_val = $booking_data['pce'.$pce->id]??0;
                                    if($pce_val > 0) {
                                        $pce_price = $category_choices_record_arr['pce'.$pce->id][$pce_val]??0;
                                        $sub_total = $pce_val*$pce_price;
                                        $total_amount += $sub_total;
                                        $total_pax += $pce_val;
                                        $travellers[] = [
                                            'pce_label' => $pce->input_label,
                                            'pce_val' => $pce_val,
                                            'pce_price' => $pce_price,
                                            'sub_total' => $sub_total
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                if($total_pax == 0) {
                    $rules['travellers'] = 'required';
                }
            }
            $message = [];
            $message['required'] = ':attribute is required';
            $message['inventory_id.required'] = 'Please select Room Option';
            $message['travellers.required'] = 'Please select Travellers';
            $validator = Validator::make($request->all(), $rules, $message, $nicknames);
            if($validator->fails()) {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ), 400); // 400 being the HTTP code for an invalid request.
            }
        }
        if($method == 'POST') {
            $booking_data = $request->toArray();
            
            session(['booking_data'=>$booking_data]);
            return Response::json(array(
                'success' => true,
                'redirect_url' => '/book_now',//route('book_now')
            ), 200);
        } else {
            $booking_data = (session()->has('booking_data'))?session('booking_data'):[];
            if(empty($booking_data)) {
                return redirect('/');
            }
        }
        // prd($booking_data);
        $data = [];
        $data['booking_data'] = $booking_data;
        $action = $booking_data['action']??'';
        $data['action'] = $action;
        $request = (object) $booking_data;
        $userId = Auth::user()->id??0;
        $is_agent = Auth::user()->is_agent??0;
        $group_id = Auth::user()->group_id??0;
        if(empty($group_id)) {
            $group_id = '-1';
        }
        $userLoggedIn = false;
        if($userId) {
            $userLoggedIn = true;
        }

        $travellers = [];
        $total_amount = 0;
        $display_price = 0;
        $total_pax = 0;
        $module_type_id = '';
        // prd($action);
        if($action == 'hotel_booking') {
            $module_type_id = 5;
            // prd($request->inventory_id);
            $inventory_data = AccommodationInventory::find($request->inventory_id);
            if($inventory_data) {
                $accommodation = $inventory_data->Accommodation??[];
                $data['accommodation'] = Accommodation::parseHotel($accommodation);
                $data['inventory_data'] = AccommodationInventory::parseData($inventory_data);
                // prd($data['inventory_data']);

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

                $params = [];
                $params['room_id'] = $inventory_data->room_id??'';
                $params['plan_id'] = $inventory_data->plan_id??'';
                $params['checkin'] = $checkin??'';
                $params['checkout'] = $checkout??'';
                $params['inventory'] = $inventory??'';
                $inventory_price_data = CustomHelper::getAccommodationInventory($params);
                if(isset($inventory_price_data['success']) && !empty($inventory_price_data['success'])) {
                    $total_amount = $inventory_price_data['publish_price'];
                    $display_price = $inventory_price_data['display_price'];
                }
            }
          // prd($data);
        } else if($action == 'rental_booking') {
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

            $packagePrice = PackagePriceInfo::find($request->package_type);
            $data['package'] = Package::parsePackage($package);
            $data['packagePrice'] = $packagePrice;
            $data['package_id'] = $package->id??'';
            $data['package_type'] = $packagePrice->id??'';

            $packagePriceCategory = $package->packagePriceCategory??[];
            $priceCategoryElements = $packagePriceCategory->priceCategoryElements??[];
            $sumpPriceVal = $packagePrice->final_amount;
            $original_price = $packagePrice->sub_total_amount;
            $booking_price = $packagePrice->booking_price??0;
            $category_choices_record = $packagePrice->category_choices_record??[];
            $show_choices_record = $packagePrice->show_choices_record??[];

            $category_choices_record_arr = [];
            if($category_choices_record) {
                $category_choices_record_arr = json_decode($category_choices_record,true);
            }

            $show_choices_record_arr = [];
            if($show_choices_record) {
                $show_choices_record_arr = json_decode($show_choices_record,true);
            }
            // prd($category_choices_record_arr);
            if(!empty($priceCategoryElements)) {
                foreach($priceCategoryElements as $pce) {
                    if(array_key_exists('pce'.$pce->id, $show_choices_record_arr)) {
                        $default = $show_choices_record_arr['pce'.$pce->id]['default']??'';
                        if($default != 'pce'.$pce->id.'_null') {
                            $pce_val = $booking_data['pce'.$pce->id]??0;
                            if($pce_val > 0) {
                                $pce_price = $category_choices_record_arr['pce'.$pce->id][$pce_val]??0;
                                $sub_total = $pce_val*$pce_price;
                                $total_amount += $sub_total;
                                $total_pax += $pce_val;

                                $travellers[] = [
                                    'pce_label' => $pce->input_label,
                                    'pce_val' => $pce_val,
                                    'pce_price' => $pce_price,
                                    'sub_total' => $sub_total
                                ];
                            }
                        }
                    }
                }
            }
            $data['booking_data']['travellers'] = $travellers;
            $data['booking_data']['total_pax'] = $total_pax;
        }
        if($is_agent == 1) {
            $userId = 0;
        }
        $userObject = [];
        if($userId) {
            $userObject = User::where('id',$userId)->first();
        }

        $discount_amount = 0;
        if($is_agent == 1 || $action == 'hotel_booking' || $action == 'package_booking' || $action == 'activity_booking' || $action == 'rental_booking') {
            if($action == 'hotel_booking') {
                $discount_category_id = $accommodation->discount_category_id??'';
                $discount_amount = CustomHelper::getDiscountPrice('hotel_listing',$discount_category_id,$total_amount,$group_id,$accommodation->id,$inventory);
            } else if($action == 'rental_booking') {
                $discount_category_id = $data['booking_data']['city_data']->discount_category_id??'';
                $module_name = 'rental';
                $module_record_id = $data['booking_data']['city_data']->id??'';
                $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$module_record_id,$total_pax);

            } else {
                if($package && $package->id) {
                    $discount_category_id = $package->discount_category_id??'';  
                    $module_name = 'package_listing';
                    $is_activity = $package->is_activity??'';
                    if($is_activity == 1) {
                        $module_name = 'activity_listing';
                    }
                    $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id,$package->id,$total_pax);
                }
            }
        }
        $sub_total_amount = $total_amount;
        $total_amount = $total_amount - $discount_amount;
        if($display_price) {
            $discount_amount = $display_price - $total_amount;
            $sub_total_amount = $display_price;
        }
        $GST_TAX = 0;
        $TCS_TAX = 0;
        if($action == 'package_booking') {
            $GST_TAX = CustomHelper::WebsiteSettings('PACKAGE_GST_TAX');
            $TCS_TAX = CustomHelper::WebsiteSettings('PACKAGE_TCS_TAX');
        }

        $grand_total = $total_amount;
        if($GST_TAX) {
            $gst_amount = ($grand_total * $GST_TAX) / 100;
            $total_amount = $total_amount + $gst_amount;
            $data['booking_data']['GST_TAX'] = $GST_TAX;
            $data['booking_data']['gst_amount'] = $gst_amount;
        }
        if($TCS_TAX) {
            $tcs_amount = ($grand_total * $TCS_TAX) / 100;
            $total_amount = $total_amount + $tcs_amount;
            $data['booking_data']['TCS_TAX'] = $TCS_TAX;
            $data['booking_data']['tcs_amount'] = $tcs_amount;
        }

        $data['booking_data']['sub_total_amount'] = $sub_total_amount;
        $data['booking_data']['discount_amount'] = $discount_amount;
        $data['booking_data']['total_amount'] = $total_amount;
        // prd($data);

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

        $data['userObject'] = $userObject;
        $data['userLoggedIn'] = $userLoggedIn;
        $data['countries'] = Country::where('status',1)->get();

        $seo_data = [];
        $seo_data['meta_title'] = 'Book Now';
        $seo_data['meta_description'] = 'Book Now';
        $seo_data['meta_keyword'] = '';
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);
        $BackUrl = CustomHelper::BackUrl();
        $data['back_url'] = $BackUrl;
        // prd($data);
        return Inertia::render('booknow', $data);
    }

/* end of controller */
}
