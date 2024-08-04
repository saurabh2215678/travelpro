<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\CabRoute;
use App\Models\OrderPayments;
use App\Models\Accommodation;
use App\Models\OrderServiceVoucher;
use App\Models\EmailTemplate;
use App\Models\ActivityLog;
use App\Helpers\CustomHelper;
use App\Models\Itenary;
use App\Models\OrderStatusHistory;
use App\Models\OrderTraveller;
use Storage;
use DB;
use PDF;
use File;
use auth;

class VoucherController extends Controller {

    //private $limit;
    public function __construct() {
        //$this->limit = 20;
    }
    public function cab(Request $request)
    {
        $data = [];
        $order_id = $request->order_id;
        $order = Order::find($request->order_id);

        if($order->order_type == 4){
            $package_id = $order->package_id ?? '';

            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($request->method() == "POST" || $request->method() == "post"){
                // prd($request->toArray());

                $rules = [];
                $validation_msg = [];
                $rules['title'] = 'required';
                $rules['name'] = 'required';

                $this->validate($request, $rules, $validation_msg);

                $post_data = $request->toArray();

                $cab_data = array(
                    'itinerary' => $request->itinerary ?? '',
                    'origin' => $request->origin ?? '',
                    'destination' => $request->destination ?? '',
                    'pickup_address' => $request->pickup_address ?? '',
                    'pickup_date' => $request->pickup_date ?? '',
                    'drop_address' => $request->drop_address ?? '',
                    'car_type' => $request->car_type ?? '',
                    'trip_date' => $request->trip_date ?? '',
                    'cab_charge' => $request->cab_charge ?? '',
                    'exclusion' => $request->exclusion ?? '',
                    'paid_amount' => $request->paid_amount ?? '',
                    'be_paid_to_driver' => $request->be_paid_to_driver ?? '',
                );

                $booking_details = $order->booking_details ? json_decode($order->booking_details, true): [];

                $booking_details_arr = [];
                if($booking_details) {
                    if(isset($booking_details['adult'])) {
                        $cab_data['adult'] = $post_data['booking_details']['adult']??0;
                    }
                    if(isset($booking_details['children'])) {
                        $cab_data['children'] = $post_data['booking_details']['children']??0;
                    }
                    if(isset($booking_details['selected_addons']) && !empty($booking_details['selected_addons'])) {
                        $selected_addons_arr = [];
                        foreach($booking_details['selected_addons'] as $addon) {
                            $qty = $post_data['booking_details']['selected_addons'][$addon['id']]['qty']??0;
                            $unit_price = $addon['unit_price']??0;
                            $price = $unit_price*$qty;
                            $addons_row = [
                                'id' => $addon['id'],
                                'name' => $addon['name'],
                                'qty' => $qty,
                                'unit_price' => $addon['unit_price']??0,
                                'price' => $price,
                            ];
                            if(isset($addon['daily_rental'])) {
                                $days = $post_data['booking_details']['selected_addons'][$addon['id']]['days']??0;
                                if($days) {
                                    $price = $price * $days;
                                }
                                $addons_row['daily_rental'] = $addon['daily_rental'];
                                $addons_row['days'] = $days;
                                $addons_row['price'] = $price;
                            }
                            $selected_addons_arr[] = $addons_row;
                        }
                        $cab_data['selected_addons'] = $selected_addons_arr;
                    }
                }

                $req_data  = array(
                    'order_id' => $request->order_id ?? '',
                    'package_id' => $package_id ?? '',
                    'title' => $request->title ?? '',
                    'trip_type' => $request->trip_type ?? '',
                    // 'gst_no' => $request->gst_no ?? '',
                    'name' => $request->name ?? '',
                    'phone' => $request->phone ?? '',
                    'email' => $request->email ?? '',
                    'cab_data' => json_encode($cab_data) ?? '',
                    'remarks' => $request->remarks ?? '',
                );

                if($OrderServiceVoucher){
                    $is_saved = OrderServiceVoucher::where('order_id',$order_id)->update($req_data);
                    $voucher_id = $OrderServiceVoucher->id;
                    $msg = 'Cab Voucher update successfully.';

                }else{
                    $is_saved = OrderServiceVoucher::create($req_data);
                    $voucher_id = $is_saved->id;
                    $msg = 'Cab Voucher generate successfully.';

                    $voucherCreated = CustomHelper::getOrderStatus('voucher created');
                    $orders_status = CustomHelper::showEnquiryMaster($voucherCreated);
                    $order->orders_status = $voucherCreated;
                    $order->save();

                }

                if($is_saved){

                    $params = [];
                    $params['order_id'] = $order_id;
                    $params['comments'] = $request->remarks;
                    $params['orders_status'] = CustomHelper::showEnquiryMaster($order->orders_status);

                    CustomHelper::RecordOrderStatusHistory($params);


                    //=======================================
                    $user_id = auth()->user()->id;
                    $user_name = auth()->user()->name;
                    $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
                    $module_desc =  !empty($new_data->title)?$new_data->title:'';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $module_id= $order_id;
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Cab Voucher';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = '';
                    $params['sub_module_id'] = $voucher_id;
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = (!empty($OrderServiceVoucher->id)) ? "Update" : "Generate";
                    CustomHelper::RecordActivityLog($params);

                    //=============Activity Logs=====================

                    return back()->with('alert-success', $msg);
                }else{
                    return back()->with('alert-danger', 'Cab Voucher not generated');

                }
            }

            $data['order'] = $order;

            if($OrderServiceVoucher){
                $cab_data = $OrderServiceVoucher->cab_data ? json_decode($OrderServiceVoucher->cab_data, true): [];

                $title = $OrderServiceVoucher->title ?? '';
                $origin = $cab_data['origin'] ?? '';

                $destination = $cab_data['destination'] ?? '';
                $trip_type = $OrderServiceVoucher->trip_type ?? '';
                $pickup_address = $cab_data['pickup_address'] ?? '';
                $pickup_date = $cab_data['pickup_date'] ?? '';
                $exclusion = $cab_data['exclusion'] ?? '';
                $drop_address = $cab_data['drop_address'] ?? '';
                $car_type = $cab_data['car_type'] ?? '';
                $adult = $cab_data['adult'] ?? '';
                $children = $cab_data['children'] ?? '';
                $car_type = $cab_data['car_type'] ?? '';
                $selected_addons = $cab_data['selected_addons'] ?? '';
                $cab_charge = $cab_data['cab_charge'] ?? '';
                $paid_amount = $cab_data['paid_amount'] ?? '';

                $data['booking_details'] = $cab_data;

            }else{

                $booking_details = $order->booking_details ? json_decode($order->booking_details, true): [];
                $title = $booking_details['itinerary'] ?? '';
                $origin = $booking_details['origin'] ?? '';
                $destination = $booking_details['destination'] ?? '';
                $trip_type = $booking_details['trip_type'] ?? '';
                $pickup_address = $booking_details['pickup_address'] ?? '';
                $pickup_date = $booking_details['trip_date'] ?? '';
                $drop_address = $booking_details['drop_address'] ?? '';
                $car_type = $booking_details['cab_name'] ?? '';
                $adult = $booking_details['adult'] ?? '';
                $children = $booking_details['children'] ?? '';
                $selected_addons = $booking_details['selected_addons'] ?? '';

                $cab_route_id = $booking_details['cab_route_id']??'';
                $cab_route_data = CabRoute::find($cab_route_id);
                $cab_route_group_data = $cab_route_data->CabRouteGroup??'';

                $exclusion = $cab_route_group_data->exclusion??'';

                $cab_charge = $order->total_amount ?? '';
                $paid_amount = $order->partial_amount ?? '';

                $data['booking_details'] = $booking_details;
            }

            $data['OrderServiceVoucher'] = $OrderServiceVoucher;
            $data['title'] = $title;
            $data['origin'] = $origin;
            $data['destination'] = $destination;
            $data['trip_type'] = $trip_type;
            $data['pickup_address'] = $pickup_address;
            $data['pickup_date'] = $pickup_date;
            $data['drop_address'] = $drop_address;
            $data['exclusion'] = $exclusion;
            $data['car_type'] = $car_type;
            $data['adult'] = $adult;
            $data['children'] = $children;
            $data['selected_addons'] = $selected_addons;
            $data['cab_charge'] = $cab_charge;
            $data['paid_amount'] = $paid_amount;
            $data['be_paid_to_driver'] = (int)$cab_charge -(int)$paid_amount ;

            return view("admin.vouchers.cab_form", $data);
        }else{
            abort(404);
        }

    }

    public function rental(Request $request)
    {

        $data = [];
        $order_id = $request->order_id;
        $order = Order::find($request->order_id);

        if($order->order_type == 8){
            $package_id = $order->package_id ?? '';

            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($request->method() == "POST" || $request->method() == "post"){

                $rules = [];
                $validation_msg = [];
                // $rules['bike_name'] = 'required';
                // $rules['name'] = 'required';

                $this->validate($request, $rules, $validation_msg);

                // prd($request->toArray());

                $rental_data = array(
                    'bike_name' => $request->bike_name ?? '',
                    'city_name' => $request->city_name ?? '',
                    'location_name' => $request->location_name ?? '',
                    'pickup_date' => $request->pickup_date ? CustomHelper::DateFormat($request->pickup_date,'Y-m-d h:i A'): '',
                    'drop_date' => $request->drop_date ? CustomHelper::DateFormat($request->drop_date,'Y-m-d h:i A'): '',
                    'paid_amount' => $request->paid_amount ?? '',
                );

                // prd($rental_data);

                $req_data  = array(
                    'order_id' => $request->order_id ?? '',
                    'title' => $request->title ?? '',
                    'trip_type' => $request->pickup_date ?? '',
                    'name' => $request->name ?? '',
                    'phone' => $request->phone ?? '',
                    'email' => $request->email ?? '',
                    'rental_data' => json_encode($rental_data) ?? '',
                    'remarks' => $request->remarks ?? '',
                );

                if($OrderServiceVoucher){
                    $is_saved = OrderServiceVoucher::where('order_id',$order_id)->update($req_data);
                    $voucher_id = $OrderServiceVoucher->id;
                    $msg = 'Rental Voucher update successfully.';
                } else {
                    $is_saved = OrderServiceVoucher::create($req_data);
                    $voucher_id = $is_saved->id;
                    $msg = 'Rental Voucher generate successfully.';

                    $voucherCreated = CustomHelper::getOrderStatus('voucher created');
                    $orders_status = CustomHelper::showEnquiryMaster($voucherCreated);

                    $order->orders_status = $voucherCreated;
                    $order->save();
                }
                if($is_saved) {
                    $params = [];
                    $params['order_id'] = $order_id;
                    $params['comments'] = $request->remarks;
                    $params['orders_status'] = CustomHelper::showEnquiryMaster($order->orders_status);

                    CustomHelper::RecordOrderStatusHistory($params);


                    $user_id = auth()->user()->id;
                    $user_name = auth()->user()->name;
                    $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();

                    $booking_details = $order->booking_details ? json_decode($order->booking_details): [];

                    $module_desc = $booking_details->bike_name ?? '';
                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $module_id= $order_id;
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Rental Voucher';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = '';
                    $params['sub_module_id'] = $voucher_id;
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = (!empty($OrderServiceVoucher->id)) ? "Update" : "Generate";
                    CustomHelper::RecordActivityLog($params);

                    return back()->with('alert-success', $msg);
                }else{
                    return back()->with('alert-danger', 'Rental Voucher not generated');

                }
            }

            $data['order'] = $order;

            if($OrderServiceVoucher){
                $rental_data = $OrderServiceVoucher->rental_data ? json_decode($OrderServiceVoucher->rental_data): [];

                $bike_name = $rental_data->bike_name ?? '';
                $city_name = $rental_data->city_name ?? '';

                $location_name = $rental_data->location_name ?? '';
                // $trip_type = $OrderServiceVoucher->trip_type ?? '';
                // $pickup_address = $cab_data->pickup_address ?? '';
                $pickup_date = isset($rental_data->pickup_date) ? CustomHelper::DateFormat($rental_data->pickup_date,'Y-m-d h:i A'):'';
                $drop_date = isset($rental_data->drop_date) ?  CustomHelper::DateFormat($rental_data->drop_date,'Y-m-d h:i A'): '';
                $exclusion = $rental_data->exclusion ?? '';
                $drop_address = $rental_data->drop_address ?? '';
                $paid_amount = $rental_data->paid_amount ?? '';

            }else{

                $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
                $bike_name = $booking_details->bike_name ?? '';
                $city_name = $booking_details->city_name ?? '';
                $location_name = $booking_details->location_name ?? '';
                // $trip_type = $booking_details->trip_type ?? '';
                // $pickup_address = $booking_details->pickup_address ?? '';
                $pickup_date = isset($booking_details->pickupDate) ? CustomHelper::DateFormat($booking_details->pickupDate,'Y-m-d h:i A'): '';
                $drop_date = isset($booking_details->dropDate) ? CustomHelper::DateFormat($booking_details->dropDate,'Y-m-d h:i A'): '';
                $drop_address = $booking_details->drop_address ?? '';
                $paid_amount = $order->partial_amount ?? '';

            }

            $data['OrderServiceVoucher'] = $OrderServiceVoucher;
            $data['bike_name'] = $bike_name;
            $data['city_name'] = $city_name;
            $data['location_name'] = $location_name;
            // $data['trip_type'] = $trip_type;
            // $data['pickup_address'] = $pickup_address;
            $data['pickup_date'] = $pickup_date;
            $data['drop_date'] = $drop_date;
            $data['drop_address'] = $drop_address;
            $data['paid_amount'] = $paid_amount;


            return view("admin.vouchers.rental_form", $data);
        }else{
            abort(404);
        }

    }

    public function rental_voucher_view(Request $request) {

        $data = [];
        $order_id = $request->order_id ?? '';
        $order = Order::find($request->order_id);

        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){


                $rental_data = $OrderServiceVoucher->rental_data ? json_decode($OrderServiceVoucher->rental_data): [];
                // prd($rental_data);

                $bike_name = $rental_data->bike_name ?? '';
                $city_name = $rental_data->city_name ?? '';
                $location_name = $rental_data->location_name ?? '';
                $pickup_date = $rental_data->pickup_date ?? '';
                $drop_date = $rental_data->drop_date ?? '';
                $paid_amount = $rental_data->paid_amount ?? '';


                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                $data = array(
                 'logo' => $logoSrc,
                 'order_no' =>  $order->order_no??'',
                 'bike_name' =>  $bike_name??'',
                 'city_name' =>  $city_name??'',
                 'location_name' =>  $location_name??'',
                 'pickup_date' =>  $pickup_date??'',
                 'drop_date' =>  $drop_date??'',
                 'remarks' =>  $OrderServiceVoucher->remarks??'',
                 'gst_no' =>  $OrderServiceVoucher->gst_no??'',
                 'name' =>  $OrderServiceVoucher->name??'',
                 'phone' =>  $OrderServiceVoucher->phone??'',
                 'email' =>  $OrderServiceVoucher->email??'',
                 'paid_amount' =>  $paid_amount??'',
             );


                $data['order_id'] = $order_id;

                $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Rental Voucher')->get();

                return view("admin.vouchers.rental_voucher_view", $data);

            }else{

                abort(404);
            }
        }else{

            abort(404);
        }

    }

    public function package(Request $request)
    {
        $data = [];
        $order_id = $request->order_id;
        $order = Order::find($request->order_id);
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($request->method() == "POST" || $request->method() == "post"){

            $rules = [];
            $validation_msg = [];
            $rules['name_of_pax'] = 'required';
            $rules['pax_contact'] = 'required';
            $rules['local_contact'] = 'required|numeric';
            $rules['agent_contact'] = 'required|numeric';
            // $rules['location'] = 'required|numeric';
            // $rules['hotel'] = 'required|numeric';

            $validation_msg['name_of_pax.required'] = 'Name of the Pax field is required.';
            $validation_msg['pax_contact.required'] = 'The Pax Contact field is required.';
            $validation_msg['local_contact.required'] = 'The Local Contact field is required.';
            $validation_msg['agent_contact.required'] = 'The Agent Contact field is required.';

            $this->validate($request, $rules, $validation_msg);

            // prd($request->toArray());
            $locations = $request->location;
            $dots = $request->dot;
            $hotel_data = [];
            $flight_data = [];

            foreach ($locations as $key => $location) {

                $hotel_id = $request->hotel[$key]?? '';

                if($hotel_id){
                    $locations_name = CustomHelper::getLocationName($location);
                    $hotel_row = CustomHelper::getAccommodationdata($hotel_id);

                    $hotel_data[] = array(

                        'location' => $location,
                        'location_name' => $locations_name,
                        'hotel' => $hotel_id,
                        'hotel_name' => $hotel_row ? $hotel_row->name : '',
                        'date' => $request->date[$key],
                        'night' => $request->night[$key],
                        'rooms' => $request->rooms[$key],
                        'meals' => $request->meals[$key],
                    );
                }
            }

            foreach ($dots as $key => $dot) {

                $flight_data[] = array(

                    'dot' => $dot,
                    'flight' => $request->flight[$key],
                    'sector' => $request->sector[$key],
                    'departures' => $request->departures[$key],
                    'arrivals' => $request->arrivals[$key],
                );
            }

                // prd($flight_data);
            $package_data = array(
                'package_name' => $request->title ?? '',
                'trip_date' => $request->trip_date ?? '',
                'destination' => $request->destination ?? '',
                'duration' => $request->duration ?? '',
                'package_charges' => $request->total_amount ?? '',
                'paid_amount' => $request->paid_amount ?? '',
                'due_amount' => $request->due_amount ?? '',
                'address' => $request->address ?? '',
                'exclusion' => $request->exclusion ?? '',
                'contact_person' => $request->contact_person ?? '',
                'contact_phone' => $request->contact_phone ?? '',
                'contact_email' => $request->contact_email ?? '',
            );

            $req_data  = array(

                'order_id' => $request->order_id ?? '', 
                'package_id' => $package_id ?? '',
                'title' => $request->title ?? '',
                'name' => $request->name ?? '',
                'phone' => $request->phone ?? '',
                'email' => $request->email ?? '', 
                'name_of_pax' => $request->name_of_pax ?? '', 
                'pax_contact' => $request->pax_contact ?? '', 
                'local_contact' => $request->local_contact ?? '',
                'agent_contact' => $request->agent_contact ?? '',
                'hotel_data' => json_encode($hotel_data) ?? '',
                'flight_data' => json_encode($flight_data) ?? '',
                'package_data' => json_encode($package_data) ?? '', 
                'payment_mode' => $request->payment_mode ?? '',
                'vehicle_type' => $request->vehicle_type ?? '',
                'remarks' => $request->remarks ?? '',
            );

            if($OrderServiceVoucher){
                $is_saved = OrderServiceVoucher::where('order_id',$order_id)->update($req_data);
                $voucher_id = $OrderServiceVoucher->id;
                $msg = 'Package Voucher update successfully.'; 
            }else{
                $is_saved = OrderServiceVoucher::create($req_data);
                $voucher_id = $is_saved->id;
                $msg = 'Package Voucher generate successfully.';
                $voucherCreated = CustomHelper::getOrderStatus('voucher created');
                $order->orders_status = $voucherCreated;
                $order->save();
            }
            if($is_saved){


                $params = [];
                $params['order_id'] = $order_id;
                $params['comments'] = $request->remarks;
                $params['orders_status'] = CustomHelper::showEnquiryMaster($order->orders_status);

                CustomHelper::RecordOrderStatusHistory($params);

                //=============================================
                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;
                $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id= $order_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Package Voucher';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = '';
                $params['sub_module_id'] = $voucher_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($OrderServiceVoucher->id)) ? "Update" : "Generate";
                CustomHelper::RecordActivityLog($params);

                return back()->with('alert-success', $msg);
            }else{
                return back()->with('alert-danger', 'Package Voucher not generated');
            }
        }

            // prd($req_data);
        $hotel_data_str = $OrderServiceVoucher ? $OrderServiceVoucher->hotel_data: '';
        $locations_arr = [];
        $hotels_arr = [];
        $dates_arr = [];
        $rooms_data = [];
        $nights_data = [];
        $meals_data = [];
        if($hotel_data_str){
            $hotel_data_arr = json_decode($hotel_data_str);
            foreach ($hotel_data_arr as $key => $hotel_row) {
                $locations_arr[] = $hotel_row->location;
                $hotels_arr[] = $hotel_row->hotel ?? '';
                $dates_arr[] = $hotel_row->date ?? '';
                $nights_data[] = $hotel_row->night ?? '';
                $rooms_data[] = $hotel_row->rooms ?? '';
                $meals_data[] = $hotel_row->meals ?? '';
            }
        }
        $itineraries = Itenary::where('package_id',$package_id)->groupBy('location_id')->get();
        if(empty($locations_arr)){
           foreach ($itineraries as $key => $itinerary_row) {
            if(!in_array($itinerary_row->location_id, $locations_arr)){

                $locations_arr[] = $itinerary_row->location_id;
            }
        }  
    }
    // prd($locations_arr);

    $data['order'] = $order;
    $address ='';
    $exclusion ='';
    if($OrderServiceVoucher){

        $packages_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
        $title = $OrderServiceVoucher->title ?? '';
        $destination = $packages_data->destination ?? '';
        $duration = $packages_data->duration ?? '';
        $trip_date = $packages_data->trip_date ?? '';
        $package_charges = $packages_data->package_charges ?? '';
        $paid_amount = $packages_data->paid_amount ?? '';
        $address = $packages_data->address ?? '';
        $exclusion = $packages_data->exclusion ?? '';
        $contact_person = $packages_data->contact_person ?? '';
        $contact_phone = $packages_data->contact_phone ?? '';
        $contact_email = $packages_data->contact_email ?? '';

    }else{

        $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
        $title = $order->package_name ?? '';
        $trip_date = $order->trip_date ?? '';
        $packages_data = $order->Package ?? '';
        $duration = $packages_data->package_duration ?? '';
        $packageDestination = $packages_data->packageDestination ?? '';
        $destination = $packageDestination->name ?? '';
        $package_charges = $order->total_amount ?? '';
        $paid_amount = $order->partial_amount ?? '';
        $contact_person = $booking_details->contact_person ?? '';
        $contact_phone = $booking_details->contact_phone ?? '';
        $contact_email = $booking_details->contact_email ?? '';

    }

    $data['locations_arr'] = $locations_arr;
    $data['hotels_arr'] = $hotels_arr;
    $data['dates_arr'] = $dates_arr;
    $data['nights_data'] = $nights_data;
    $data['rooms_data'] = $rooms_data;
    $data['meals_data'] = $meals_data;
    $data['OrderServiceVoucher'] = $OrderServiceVoucher;
    $data['selected_location'] = $locations_arr;
    $data['itineraries'] = $itineraries;
    $data['title'] = $title;
    $data['trip_date'] = !empty($trip_date)?date('d M,Y',strtotime($trip_date)):'';
    $data['destination'] = $destination;
    $data['duration'] = $duration;
    $data['address'] = $address;
    $data['contact_person'] = $contact_person;
    $data['contact_phone'] = $contact_phone;
    $data['contact_email'] = $contact_email;
    $data['package_charges'] = $package_charges;
    $data['paid_amount'] = $paid_amount;
    $data['exclusion'] = $exclusion;
    $data['due_amount'] = (int)$package_charges -(int)$paid_amount ;

    return view("admin.vouchers.package_form", $data);  
    
}
//===========
public function activity(Request $request)
{
    $data = [];
    $order_id = $request->order_id;
    $order = Order::find($request->order_id);

    if($order->order_type == 6){
        $package_id = $order->package_id ?? '';

        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($request->method() == "POST" || $request->method() == "post"){

            $rules = [];
            $rules['title'] = 'required';
            $rules['name'] = 'required';

            $this->validate($request, $rules);

            $package_data = array(
                'package_name' => $request->title ?? '',
                'trip_date' => $request->trip_date ?? '',
                'destination' => $request->destination ?? '',
                'duration' => $request->duration ?? '',
                'package_charges' => $request->total_amount ?? '',
                'paid_amount' => $request->paid_amount ?? '',
                'due_amount' => $request->due_amount ?? '',
                'address' => $request->address ?? '',
                'contact_person' => $request->contact_person ?? '',
                'contact_phone' => $request->contact_phone ?? '',
                'contact_email' => $request->contact_email ?? '',

            );
            $req_data  = array(
                'order_id' => $request->order_id ?? '',
                'package_id' => $package_id ?? '',
                'title' => $request->title ?? '',
                'name' => $request->name ?? '',
                'phone' => $request->phone ?? '',
                'email' => $request->email ?? '',
                'package_data' => json_encode($package_data) ?? '',
                'remarks' => $request->remarks ?? '',
            );
                // prd($req_data);

            if($OrderServiceVoucher){
                $is_saved = OrderServiceVoucher::where('order_id',$order_id)->update($req_data);
                $voucher_id = $OrderServiceVoucher->id;
                $msg = 'Activity Voucher update successfully.';

            }else{
                $is_saved = OrderServiceVoucher::create($req_data);
                $voucher_id = $is_saved->id;
                $msg = 'Activity Voucher generate successfully.';
                $voucherCreated = CustomHelper::getOrderStatus('voucher created');
                $order->orders_status = $voucherCreated;
                $order->save();

            }
            if($is_saved){

               $params = [];
               $params['order_id'] = $order_id;
               $params['comments'] = $request->remarks;
               $params['orders_status'] = CustomHelper::showEnquiryMaster($order->orders_status);

               CustomHelper::RecordOrderStatusHistory($params);

                    //===================================
               $user_id = auth()->user()->id;
               $user_name = auth()->user()->name;
               $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
               $module_desc =  !empty($new_data->title)?$new_data->title:'';
               $new_data = (array) $new_data;
               $new_data = json_encode($new_data);
               $module_id= $order_id;
               $params['user_id'] = $user_id;
               $params['user_name'] = $user_name;
               $params['module'] = 'Activity Voucher';
               $params['module_desc'] = $module_desc;
               $params['module_id'] = $module_id;
               $params['sub_module'] = '';
               $params['sub_module_id'] = $voucher_id;
               $params['sub_sub_module'] = "";
               $params['sub_sub_module_id'] = 0;
               $params['data_after_action'] = $new_data;
               $params['action'] = (!empty($OrderServiceVoucher)) ? "Update" : "Generate";
               CustomHelper::RecordActivityLog($params);

                //=============Activity Logs=====================

               return back()->with('alert-success', $msg);
           }else{
            return back()->with('alert-danger', 'Activity Voucher not generated');

        }
    }

    $data['order'] = $order;
    $address = '';
    if($OrderServiceVoucher){
        $packages_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];

        $title = $OrderServiceVoucher->title ?? '';
        $destination = $packages_data->destination ?? '';
        $duration = $packages_data->duration ?? '';
        $trip_date = $packages_data->trip_date ?? '';
        $package_charges = $packages_data->package_charges ?? '';
        $paid_amount = $packages_data->paid_amount ?? '';
        $address = $packages_data->address ?? '';
        $contact_person = $packages_data->contact_person ?? '';
        $contact_phone = $packages_data->contact_phone ?? '';
        $contact_email = $packages_data->contact_email ?? '';

    }else{

        $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
        $title = $order->package_name ?? '';
        $trip_date = $order->trip_date ?? '';
        $package_data = $order->Package ?? '';
        $address = $package_data->address ?? '';
        //$duration = $package_data->activity_duration ?? '';
        $duration = $booking_details->duration ?? '';
        $contact_person = $booking_details->contact_person ?? '';
        $contact_phone = $booking_details->contact_phone ?? '';
        $contact_email = $booking_details->contact_email ?? '';
        $packageDestination = $package_data->packageDestination ?? '';
        $destination = $packageDestination->name ?? '';
        $package_charges = $order->total_amount ?? '';
        $paid_amount = $order->partial_amount ?? '';

    }

    $data['OrderServiceVoucher'] = $OrderServiceVoucher;
    $data['title'] = $title;
    $data['trip_date'] = !empty($trip_date)?date('D, M dS Y h:i A',strtotime($trip_date)):'';
    $data['destination'] = $destination;
    $data['duration'] = $duration;
    $data['contact_person'] = $contact_person;
    $data['contact_phone'] = $contact_phone;
    $data['contact_email'] = $contact_email;
    $data['package_charges'] = $package_charges;
    $data['address'] = $address;
    $data['paid_amount'] = $paid_amount;
    $data['due_amount'] = (int)$package_charges -(int)$paid_amount ;
    $data['hide_price_details'] = $order->hide_price_details??0;

    return view("admin.vouchers.activity_form", $data);
}else{
    abort(404);
}

}
public function hotel(Request $request)
{

    $data = [];
    $order_id = $request->order_id;
    $order = Order::find($request->order_id);

    if($order->order_type == 5){
        $package_id = $order->package_id ?? '';

        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($request->method() == "POST" || $request->method() == "post"){

            $rules = [];
            $validation_msg = [];
            $rules['title'] = 'required';
            $rules['name'] = 'required';

            $this->validate($request, $rules, $validation_msg);

                // prd($request->toArray());

            $hotel_data = array(
                'hotel_name' => $request->title ?? '',
                'contact_phone' => $request->contact_phone ?? '',
                'contact_email' => $request->contact_email ?? '',
                'contact_person' => $request->contact_person ?? '',
                'room_name' => $request->room_name ?? '',
                'plan_name' => $request->plan_name ?? '',
                'destination_name' => $request->destination_name ?? '',
                'checkin' => $request->checkin ?? '',
                'checkin_time' => $request->checkin_time ?? '',
                'checkout' => $request->checkout ?? '',
                'checkout_time' => $request->checkout_time ?? '',
                'trip_date' => $request->trip_date ?? '',
                'created_at' => $request->created_at ?? '',
                'adult' => $request->adult ?? '',
                'child' => $request->child ?? '',
                'hotel_charge' => $request->total_amount ?? '',
                'paid_amount' => $request->paid_amount ?? '',
                'address' => $request->address ?? '',
                'inventory' => $request->inventory ?? '',
                'inclusions' => $request->inclusions ?? '',
            );

            $req_data  = array(
                'order_id' => $request->order_id ?? '',
                'package_id' => $package_id ?? '',
                'title' => $request->title ?? '',
                    //'trip_type' => $request->trip_type ?? '',
                'name' => $request->name ?? '',
                'phone' => $request->phone ?? '',
                'email' => $request->email ?? '',
                'hotel_data' => json_encode($hotel_data) ?? '',
                'remarks' => $request->remarks ?? '',
            );

            if($OrderServiceVoucher){
                $is_saved = OrderServiceVoucher::where('order_id',$order_id)->update($req_data);
                $voucher_id = $OrderServiceVoucher->id;
                $msg = 'Hotel Voucher update successfully.';
            }else{
                $is_saved = OrderServiceVoucher::create($req_data);
                $voucher_id = $is_saved->id;
                $msg = 'Hotel Voucher generate successfully.';

                $voucherCreated = CustomHelper::getOrderStatus('voucher created');
                $order->orders_status = $voucherCreated;
                $order->save();
            }
            if($is_saved){

                $params = [];
                $params['order_id'] = $order_id;
                $params['comments'] = $request->remarks;
                $params['orders_status'] = CustomHelper::showEnquiryMaster($order->orders_status);

                CustomHelper::RecordOrderStatusHistory($params);

                    //================================
                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;
                $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id= $order_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Hotel Voucher';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = '';
                $params['sub_module_id'] = $voucher_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($OrderServiceVoucher->id)) ? "Update" : "Generate";
                CustomHelper::RecordActivityLog($params);

                    //=============Activity Logs=====================

                return back()->with('alert-success', $msg);
            }else{
             return back()->with('alert-danger', 'Hotel Voucher not generated');

         }
     }

     $data['order'] = $order;

     if($OrderServiceVoucher){
        $hotel_data = $OrderServiceVoucher->hotel_data ? json_decode($OrderServiceVoucher->hotel_data): [];

        $title = $OrderServiceVoucher->title ?? '';
        $room_name = $hotel_data->room_name ?? '';
        $contact_phone = $hotel_data->contact_phone ?? '';
        $contact_email = $hotel_data->contact_email ?? '';
        $contact_person = $hotel_data->contact_person ?? '';
        $plan_name = $hotel_data->plan_name ?? '';
        $destination = $hotel_data->destination_name ?? '';
        $checkin = $hotel_data->checkin ?? '';
        $checkin_time = $hotel_data->checkin_time ?? '';
        $checkout = $hotel_data->checkout ?? '';
        $checkout_time = $hotel_data->checkout_time ?? '';
        $trip_date = $hotel_data->trip_date ?? '';
        $created_at = $hotel_data->created_at ?? '';
        $adult = $hotel_data->adult ?? '';
        $child = $hotel_data->child ?? '';
        $hotel_charge = $hotel_data->hotel_charge ?? '';
        $paid_amount = $hotel_data->paid_amount ?? '';
        $address = $hotel_data->address ?? '';
        $inventory = $hotel_data->inventory ?? '';
        $inclusions = $hotel_data->inclusions ?? '';

    }else{

        $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
        $title = $booking_details->hotel_name ?? '';
        $contact_phone = $booking_details->contact_phone ?? '';
        $contact_email = $booking_details->contact_email ?? '';
        $contact_person = $booking_details->contact_person ?? '';
        $room_name = $booking_details->room_name ?? '';
        $trip_date = $booking_details->trip_date ?? '';
        $created_at = !empty($order->created_at)?CustomHelper::DateFormat($order->created_at,'Y-m-d h:i A'): '';
        $plan_name = $booking_details->plan_name ?? '';
        $checkin = $booking_details->checkin ?? '';

        $hotel_id = $booking_details->hotel_id??'';
        $hotel_info = Accommodation::find($hotel_id);
        $checkin_time = $hotel_info->checkin_time ?? '';
        $checkout_time = $hotel_info->checkout_time ?? '';

        //$checkin_time = $booking_details->checkin_time ?? '';
        //$checkout_time = $booking_details->checkout_time ?? '';
        $checkout = $booking_details->checkout ?? '';
        $address = $booking_details->address??'';
        $destination = $booking_details->destination_name??'';
        //$hotel_id = $booking_details->hotel_id??'';
        //$hotel_data = Accommodation::find($hotel_id);

        //$hotelDestination = $hotel_data->accommodationDestination??'';
        //$destination = $hotelDestination->name ?? '';
        $adult = $booking_details->adult ?? '';
        $child = $booking_details->child ?? '';
        $hotel_charge = $order->total_amount ?? '';
        $paid_amount = $order->partial_amount ?? '';
        $inventory = $booking_details->inventory ?? '';
        $inclusions = $booking_details->inclusions ?? '';

    }

    $data['OrderServiceVoucher'] = $OrderServiceVoucher;
    $data['title'] = $title;
    $data['room_name'] = $room_name;
    $data['contact_phone'] = $contact_phone;
    $data['contact_email'] = $contact_email;
    $data['contact_person'] = $contact_person;
    $data['plan_name'] = $plan_name;
    $data['trip_date'] = $trip_date;
    $data['created_at'] = $created_at;
    $data['checkin'] = $checkin;
    $data['checkout'] = $checkout;
    $data['checkin_time'] = !empty($checkin_time)?date('h:i A',strtotime($checkin_time)):'';   
    $data['checkout_time'] = !empty($checkout_time)?date('h:i A',strtotime($checkout_time)):''; 
    $data['adult'] = $adult;
    $data['child'] = $child;
    $data['address'] = $address;
    $data['inclusions'] = $inclusions;
    $data['inventory'] = $inventory;
    $data['destination'] = $destination;
    $data['hotel_charge'] = $hotel_charge;
    $data['paid_amount'] = $paid_amount;
       //$data['due_amount'] = (int)$hotel_charge -(int)$paid_amount ;

    return view("admin.vouchers.hotel_form", $data);
}else{
    abort(404);
}

}
//===========

public function package_voucher_view(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);

    if($order){
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){

            $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
            // prd($package_data);

            $title = $OrderServiceVoucher->title ?? '';
            $trip_date = !empty($package_data->trip_date)?date('d M,Y',strtotime($package_data->trip_date)):'';
            //$trip_date = $package_data->trip_date??'';
            $duration = $package_data->duration ?? '';
            $destination = $package_data->destination ?? '';
            $package_charge = $package_data->package_charges ?? '';
            $paid_amount = $package_data->paid_amount ?? '';
            $due_amount = $package_data->due_amount ?? '';
            $address = $package_data->address ?? '';
            $contact_person = $package_data->contact_person ?? '';
            $contact_phone = $package_data->contact_phone ?? '';
            $contact_email = $package_data->contact_email ?? '';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');

            $path = "settings/";
            $logoSrc =asset(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            $data = array(
             'logo' => $logoSrc,
             'order_no' =>  $order->order_no??'',
             'title' =>  $OrderServiceVoucher->title??'',
             'remarks' =>  $OrderServiceVoucher->remarks??'',
             'trip_date' => $trip_date??'',
             'duration' => $duration??'',
             'address' => $address??'',
             'contact_person' =>  $contact_person??'',
             'contact_phone' =>  $contact_phone??'',
             'contact_email' =>  $contact_email??'',
             'destination' => $destination??'',
             'package_charge' => $package_charge??'',
             'name' =>  $OrderServiceVoucher->name??'',
             'phone' =>  $OrderServiceVoucher->phone??'',
             'email' =>  $OrderServiceVoucher->email??'',
             'paid_amount' =>  $paid_amount??'',
             'due_amount' =>  $due_amount??'',
             'package_data' =>  $package_data??[],
         );

          // prd($data);

           // $email_template = EmailTemplate::where('slug', 'car-booking-voucher')->where('status', 1)->first();
           // $email_content = isset($email_template->content) ? $email_template->content : '';
           //$email_params = isset($email_params) ? $email_params : [];
           //$email_content= view("emails.package_voucher_pdf", $email_params)->render();
           //$html = strtr($email_content, $email_params);
            $data['order_id'] = $order_id;
            $data['OrderServiceVoucher'] = $OrderServiceVoucher;

            $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Package Voucher')->get();

            return view("admin.vouchers.package_voucher_view", $data);

           // $html = preg_replace('/>\s+</', "><", $html);
           // $pdf = PDF::loadHTML($html);
           // $pdf->setPaper('A4', 'portrait');
           // $package_name = $package_data->title ?? 'itinerary';
           // return $pdf->download('aaas.pdf');
            // return $pdf->stream('itinerary.pdf');


        }else{

            abort(404);
        }
    }else{

        abort(404);
    }

}
public function cab_voucher_view(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);

    if($order){
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){


            $cab_data = $OrderServiceVoucher->cab_data ? json_decode($OrderServiceVoucher->cab_data, true): [];
            // prd($cab_data);

            $title = $OrderServiceVoucher->title ?? '';
            $trip_type = $OrderServiceVoucher->trip_type ?? '';
            $pickup_address = $cab_data['pickup_address'] ?? '';
            $pickup_date = $cab_data['pickup_date'] ?? '';
            $drop_address = $cab_data['drop_address'] ?? '';
            $trip_date = $cab_data['trip_date'] ?? '';
            $car_type = $cab_data['car_type'] ?? '';
            $origin = $cab_data['origin'] ?? '';
            $destination = $cab_data['destination'] ?? '';
            $exclusion = $cab_data['exclusion'] ?? '';
            $cab_charge = $cab_data['cab_charge'] ?? '';
            $paid_amount = $cab_data['paid_amount'] ?? '';
            $be_paid_to_driver = $cab_data['be_paid_to_driver'] ?? '';
            $itinerary = $cab_data['itinerary'] ?? '';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc =asset(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            $data = array(
             'logo' => $logoSrc,
             'order_no' =>  $order->order_no??'',
             'itinerary' =>  $itinerary??'',
             'title' =>  $OrderServiceVoucher->title??'',
             'remarks' =>  $OrderServiceVoucher->remarks??'',
             'trip_type' =>  $OrderServiceVoucher->trip_type??'',
             'gst_no' =>  $OrderServiceVoucher->gst_no??'',
             'pickup_address' =>  $pickup_address??'',
             'pickup_date' =>  $pickup_date??'',
             'pickup_details' =>  $pickup_details??'',
             'drop_address' =>  $drop_address??'',
             'origin' =>  $origin??'',
             'destination' =>  $destination??'',
             'car_type' =>  $car_type??'',
             'exclusion' =>  $exclusion??'',
             'name' =>  $OrderServiceVoucher->name??'',
             'phone' =>  $OrderServiceVoucher->phone??'',
             'email' =>  $OrderServiceVoucher->email??'',
             'cab_charge' =>  $cab_charge??'',
             'paid_amount' =>  $paid_amount??'',
             'be_paid_to_driver' =>  $be_paid_to_driver??'',
             '{distance}' =>  '--',
             '{extra_fare}' =>  '--',
         );

            $data['booking_details'] = $cab_data;


            $data['order_id'] = $order_id;

            $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Cab Voucher')->get();

            return view("admin.vouchers.cab_voucher_view", $data);

           // $html = preg_replace('/>\s+</', "><", $html);
           // $pdf = PDF::loadHTML($html);
           // $pdf->setPaper('A4', 'portrait');
           // $package_name = $package_data->title ?? 'itinerary';
           // return $pdf->download('aaas.pdf');
            // return $pdf->stream('itinerary.pdf');


        }else{

            abort(404);
        }
    }else{

        abort(404);
    }

}

//===========
//===========


public function activity_voucher_view(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);

    if($order){
        $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
        $total_pax = $booking_details->total_pax ?? '';
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){


            $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
            $title = $OrderServiceVoucher->title ?? '';
            $package_charges = $package_data->package_charges ?? 0;
            $package_charges = (int)$package_charges+(int)$order->markup;
            $paid_amount = $package_data->paid_amount ?? 0;
            $paid_amount = (int)$paid_amount+(int)$order->markup;
            $trip_date = !empty($package_data->trip_date)?date('D, M dS Y h:i A',strtotime($package_data->trip_date)):'';
                //$trip_date = $package_data->trip_date??'';
            $duration = $package_data->duration ?? '';
            $destination = $package_data->destination ?? '';
            $due_amount = $package_data->due_amount ?? '';
            $address = $package_data->address ?? '';
            $contact_person = $package_data->contact_person ?? '';
            $contact_phone = $package_data->contact_phone ?? '';
            $contact_email = $package_data->contact_email ?? '';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc =public_path(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            $data = array(
               'logo' => $logoSrc,
               'order_no' =>  $order->order_no??'',
               'title' =>  $OrderServiceVoucher->title??'',
               'remarks' =>  $OrderServiceVoucher->remarks??'',
               'trip_date' =>  $trip_date??'',
               'duration' =>  $duration??'',
               'destination' =>  $destination??'',
               'address' =>  $address??'',
               'contact_person' =>  $contact_person??'',
               'contact_phone' =>  $contact_phone??'',
               'contact_email' =>  $contact_email??'',
               'total_pax' =>  $total_pax??'',

               'name' =>  $OrderServiceVoucher->name??'',
               'phone' =>  $OrderServiceVoucher->phone??'',
               'email' =>  $OrderServiceVoucher->email??'',
               'package_charges' =>  $package_charges??'',
               'paid_amount' =>  $paid_amount??'',
               'due_amount' =>  $due_amount??'',
               'hide_price_details' =>  $order->hide_price_details??0,
           );

            $data['order_id'] = $order_id;
            $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Activity Voucher')->get();

            return view("admin.vouchers.activity_voucher_view", $data);

        }else{

            abort(404);
        }
    }else{

        abort(404);
    }

}
public function hotel_voucher_view(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);

    if($order){
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){


            $hotel_data = $OrderServiceVoucher->hotel_data ? json_decode($OrderServiceVoucher->hotel_data): [];
            // prd($hotel_data);

            $title = $OrderServiceVoucher->title ?? '';
            $room_type = $hotel_data->room_name ?? '';
            $contact_phone = $hotel_data->contact_phone ?? '';
            $contact_email = $hotel_data->contact_email ?? '';
            $contact_person = $hotel_data->contact_person ?? '';
            $plan_name = $hotel_data->plan_name ?? '';
            //$checkin = $hotel_data->checkin ?? '';
            //$checkout = $hotel_data->checkout ?? '';
            $checkin = !empty($hotel_data->checkin)?date('d, M Y',strtotime($hotel_data->checkin)):'';
            $checkout = !empty($hotel_data->checkout)?date('d, M Y',strtotime($hotel_data->checkout)):'';
            $checkin_time = $hotel_data->checkin_time ?? '';
            $checkout_time = $hotel_data->checkout_time ?? '';
            $inventory = $hotel_data->inventory ?? '';
            $destination = $hotel_data->destination_name ?? '';
            $address = $hotel_data->address ?? '';
            $inclusions = $hotel_data->inclusions ?? '';
            $trip_date = !empty($hotel_data->trip_date)?date('D, M dS Y h:i A',strtotime($hotel_data->trip_date)):'';
            $created_at = !empty($hotel_data->created_at)?date('D, M dS Y h:i A',strtotime($hotel_data->created_at)):'';
            $adult = $hotel_data->adult ?? '';
            $child = $hotel_data->child ?? '';
            $hotel_charge = $hotel_data->hotel_charge ?? '';
            $paid_amount = $hotel_data->paid_amount ?? '';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');

            $path = "settings/";
            $logoSrc =asset(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            $data = array(
             'logo' => $logoSrc,
             'order_no' =>  $order->order_no??'',
             'title' =>  $OrderServiceVoucher->title??'',
             'remarks' =>  $OrderServiceVoucher->remarks??'',
             'trip_date' => $trip_date??'',
             'created_at' => $created_at??'',
             'contact_email' => $contact_email??'',
             'contact_phone' => $contact_phone??'',
             'contact_person' => $contact_person??'',
             'room_name' => $room_type??'',
             'plan_name' => $plan_name??'',
             'checkin' => $checkin??'',
             'checkout' => $checkout??'',
             'checkin_time' => $checkin_time??'',
             'checkout_time' => $checkout_time??'',
             'inventory' => $inventory??'',
             'destination' => $destination??'',
             'address' => $address??'',
             'inclusions' => $inclusions??'',
             'adult' => $adult??'',
             'child' => $child??'',
             'hotel_charge' => $hotel_charge??'',
             'name' =>  $OrderServiceVoucher->name??'',
             'phone' =>  $OrderServiceVoucher->phone??'',
             'email' =>  $OrderServiceVoucher->email??'',
             'paid_amount' =>  $paid_amount??'',
         );
            $data['order_id'] = $order_id;
            $data['order'] = $order;

            $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Hotel Voucher')->get();

            return view("admin.vouchers.hotel_voucher_view", $data);

           // $html = preg_replace('/>\s+</', "><", $html);
           // $pdf = PDF::loadHTML($html);
           // $pdf->setPaper('A4', 'portrait');
           // $package_name = $package_data->title ?? 'itinerary';
           // return $pdf->download('aaas.pdf');
            // return $pdf->stream('itinerary.pdf');


        }else{

            abort(404);
        }
    }else{

        abort(404);
    }

}
public function package_voucher_pdf(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);

    if($order){
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){

            //$package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
            // prd($package_data);

            $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
            $title = $OrderServiceVoucher->title ?? '';
            $package_name = $package_data->package_name ?? '';
            $package_charges = $package_data->package_charges ?? '';
            $total_amount = $order->total_amount ?? '';
            $gst_amount = number_format($order->gst_amount) ?? '';
            $tcs_amount = number_format($order->tcs_amount) ?? '';
            $paid_amount = $package_data->paid_amount ?? '';
            $trip_date = !empty($package_data->trip_date)?date('d M,Y',strtotime($package_data->trip_date)):'';

            $duration = $package_data->duration ?? '';
            $destination = $package_data->destination ?? '';
            $due_amount = $package_data->due_amount ?? '';
            $address = $package_data->address ?? '';
            $contact_person = $package_data->contact_person ?? '';
            $contact_phone = $package_data->contact_phone ?? '';
            $contact_email = $package_data->contact_email ?? '';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc =public_path(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            // AGENT LOGO
            $userAgentInfo = ''; $agentLogo = '';
            $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
            if($agent_id && $agent_id > 0){
                $userAgentInfo = $order->agentInfo ?? '';
                if($userAgentInfo){
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
                        $logoSrc = public_path('/storage/'.$path.$agentLogo);
                    }
                }
                $total_amount = $order->sub_total_amount ?? 0;
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

            $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
            $person = '';
            $total_amount = 0;
            $person = '';
            if(!empty($price_category_choice_record_arr)) {
                foreach($price_category_choice_record_arr as $key => $pccr) {
                    $input_label = $pccr['input_label']??'';
                    $input_value = $pccr['input_value']??0;
                    $input_price = $pccr['input_price']??0;
                    $sub_total = $input_value*$input_price;
                    $total_amount += $sub_total;

                    $price = CustomHelper::getPrice($input_price);
                    if($key != 0){
                      $person.= ", ";
                  }
                  $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;

              }
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
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            /*$contact_details .= '<tr>
            <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
            <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

            <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
            </td>
            </tr>';*/
        }


        $email_params = array(
           '{header}' => $common_logo,
           '{order_no}' =>  $order->order_no??'',
           '{package_name}' =>  $package_name??'',
           '{itinerary}' =>  $itinerary??'',
           '{title}' =>  $OrderServiceVoucher->title??'',
           '{trip_date}' =>  $trip_date??'',
           '{duration}' =>  $duration??'',
           '{destination}' =>  $destination??'',
           '{name}' =>  $OrderServiceVoucher->name??'',
           '{phone}' =>  $OrderServiceVoucher->phone??'',
           '{email}' =>  $OrderServiceVoucher->email??'',
           '{package_charges}' =>  $package_charges??'',
           '{paid_amount}' =>  $paid_amount??'',
           '{due_amount}' =>  $due_amount??'',
           '{amount}' =>  $total_amount??0,
           //'{gst_amount}' =>  $gst_price??'',
           //'{tcs_amount}' =>  $tcs_price??'',
           '{address}' =>  $address??'',
           '{person}' => $person,
           '{contact_person}' => $contact_person??'',
           '{contact_phone}' => $contact_phone??'',
           '{contact_details}' => $contact_details??'',
           '{contact_email}' => $contact_email??'',

       );

        $price_details = '';
        if($order->hide_price_details != 1){
            $price_details = '';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid</p>
                </td>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.number_format($paid_amount).'</p>
                </td>
                </tr>';
            }

            $price_details .= '<tr>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Total Booking Amount</p>
            </td>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.number_format($total_amount).'</p>
            </td>
            </tr>';

            if(!empty($gst_amount)){
              $price_details .= '<tr>
              <td style="padding: 2px 15px 2px 15px;border: 0;">
              <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">GST Amount</p>
              </td>
              <td style="padding: 2px 15px 2px 15px;border: 0;">
              <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$gst_amount.'</p>
              </td>
              </tr>';
            }

            if(!empty($tcs_amount)){
              $price_details .= '<tr>
              <td style="padding: 2px 15px 2px 15px;border: 0;">
              <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">TCS Amount</p>
              </td>
              <td style="padding: 2px 15px 2px 15px;border: 0;">
              <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$tcs_amount.'</p>
              </td>
              </tr>';
            }

            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.number_format($due_amount).'</p>
                </td>
                </tr>';
            }
        }
        $email_params['{price_details}'] = $price_details;

           // $email_params = isset($email_params) ? $email_params : [];
           //$email_content= view("admin.vouchers.package_voucher_pdf", $email_params)->render();
        $email_content = view(config('custom.theme').'.common.vouchers.package_voucher_pdf', $email_params)->render();
        $html = strtr($email_content, $email_params);

           // return view("admin.vouchers.cab_voucher_view", $data);
           // $html = preg_replace('/>\s+</', "><", $html);
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf_name = $order->order_no.'.pdf';
        return $pdf->download($pdf_name);

    }else{
        abort(404);
    }
}else{
    abort(404);
}

}
//===========
public function cab_voucher_pdf(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);
    $agent_id = $order->agent_id??'';
    $tour_operator = CustomHelper::WebsiteSettings('SALE_PHONE');
    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');
    $agent_phone = '';
    $agent_email = '';
    if(!empty($agent_id)){
        $agent_data = User::find($agent_id);
        if($agent_data->phone) {
            $country_code = $agent_data->country_code ?? '91';
            $agent_phone = '+'.$country_code.'-'.$agent_data->phone;
        }
        $agent_email = !empty($agent_data->email)?$agent_data->email:'';
    }
        // Driver Deatils
    $booking_details = $order->booking_details ? json_decode($order->booking_details): '';
    $driver_details  = $booking_details->driver_details ?? [];

    $vehicle_no = $driver_details->vehicle_no ?? '';
    $vehicle_type = $driver_details->vehicle_type ?? '';
    $driver_name = $driver_details->driver_name ?? '';
    $driver_phone = $driver_details->mobile_no ?? '';

    if($order){
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){

            $cab_data = $OrderServiceVoucher->cab_data ? json_decode($OrderServiceVoucher->cab_data, true): [];
            $user_phone = '';
            if($order_id) {
                $country_code = $order->country_code ?? '91';
                $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
            }
            // prd($cab_data);

            $title = $OrderServiceVoucher->title ?? '';
            $trip_type = $OrderServiceVoucher->trip_type ?? '';
            $created_at = !empty($order->created_at)?date('D, M dS Y h:i A',strtotime($order->created_at)):'';
            $pickup_address = $cab_data['pickup_address'] ?? '';
            $pickup_date = $cab_data['pickup_date'] ?? '';
            $drop_address = $cab_data['drop_address'] ?? '';
            $origin = $cab_data['origin'] ?? '';
            $destination = $cab_data['destination'] ?? '';
            $trip_date = $cab_data['trip_date'] ?? '';
            $car_type = $cab_data['car_type'] ?? '';
            $exclusion = $cab_data['exclusion'] ?? '';
            $cab_charge = $cab_data['cab_charge'] ?? '';
            $total_amount = $order->total_amount ?? 0;
            $paid_amount = $cab_data['paid_amount'] ?? '';
            $be_paid_to_driver = $cab_data['be_paid_to_driver'] ?? '';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc =public_path(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            // AGENT LOGO
            $userAgentInfo = ''; $agentLogo = '';
            $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
            if($agent_id && $agent_id > 0){
                $userAgentInfo = $order->agentInfo ?? '';
                if($userAgentInfo){
                    $path = 'agent_logo/';
                    $agentLogo = $order->agentInfo->agent_logo ?? '';
                }
                if(!empty($agentLogo)){
                    if($storage->exists($path.$agentLogo)){
                        $logoSrc = public_path('/storage/'.$path.$agentLogo);
                    }
                }
                $total_amount = $order->sub_total_amount ?? 0;
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

            if($driver_details){

                $driver_html = '<p style="color: #333;font-size: 14px;font-weight: 400;font-family: "Roboto", sans-serif, Arial;margin: 4px 0px 0;">'.$vehicle_no.'<br>Driver:'.$driver_name.'<br>Mobile: '.$driver_phone.'</p>';
            }else{
                $driver_html = '<p style="color: #333;font-size: 14px;font-weight: 400;font-family: "Roboto", sans-serif, Arial;margin: 4px 0px;"> Driver details will be shared up to 30 mins prior to departure</p>';
            }

            $email_params = array(
               '{header}' => $common_logo,
               '{order_id}' =>  $order->order_no??'',
               '{booking_date}' =>  $created_at??'',
               '{title}' =>  $OrderServiceVoucher->title??'',
               '{trip_type}' =>  $OrderServiceVoucher->trip_type??'',
               '{gst_no}' =>  $OrderServiceVoucher->gst_no??'',

               '{pickup_address}' =>  $pickup_address??'',
               '{pickup_date}' =>  $pickup_date??'',
               '{drop_address}' =>  $drop_address??'',
               '{car_type}' =>  $car_type??'',
               '{vehicle_type}' =>  $vehicle_type??'',
               '{exclusion}' =>  $exclusion??'',

               '{name}' =>  $OrderServiceVoucher->name??'',
               '{phone}' =>  $user_phone??'',
               '{email}' =>  $OrderServiceVoucher->email??'',
               '{amount}' =>  $total_amount??0,
               '{cab_charge}' =>  $cab_charge??'',
               '{paid_amount}' =>  $paid_amount??'',
               '{be_paid_to_driver}' =>  $be_paid_to_driver??'',
               '{tour_operator}' =>  $tour_operator??'',
               '{agency}' =>  $agent_phone?$agent_phone:$tour_operator,
               '{company_email}' =>  $agent_email?$agent_email:$company_email,
               '{departing}' =>  $origin??'',
               '{arrival}' =>  $destination??'',
               '{driver_name}' =>  $driver_name??'',
               '{driver_details}' =>  $driver_html??'',
               '{driver_phone}' =>  $driver_phone??'',
               '{gst_amount}' =>  '--',
               '{taxi_no}' =>  $vehicle_no??'',
               '{distance}' =>  '--',
               '{extra_fare}' =>  '--',

           );

            $price_details = '';
            if($order->hide_price_details != 1){
                if(empty($agent_id)) {
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;line-height: normal;">Basic Fare</p>
                    </td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                    </td>
                    </tr>';
                }

                if(empty($agent_id)) {
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Other Taxes</p>
                    </td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. --</p>
                    </td>
                    </tr>';
                }
                $price_details .= '<tr>
                <td>&nbsp;</td>
                <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Total Amount</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
                </td>
                </tr>';
            }
            $email_params['{price_details}'] = $price_details;
            $email_params['booking_details'] = $cab_data;


           // $email_params = isset($email_params) ? $email_params : [];
           //$email_content= view("emails.cab_voucher_pdf", $email_params)->render();
            $email_content = view(config('custom.theme').'.common.vouchers.cab_voucher_pdf', $email_params)->render();
            $html = strtr($email_content, $email_params);

           // return view("admin.vouchers.cab_voucher_view", $data);
           // $html = preg_replace('/>\s+</', "><", $html);
            $pdf = PDF::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf_name = $order->order_no.'.pdf';
            return $pdf->download($pdf_name);

        }else{
            abort(404);
        }
    }else{
        abort(404);
    }

}

public function rental_voucher_pdf(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);
    $agent_id = $order->agent_id??'';
    $tour_operator = CustomHelper::WebsiteSettings('SALE_PHONE');
    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');
    $agent_phone = '';
    $agent_email = '';
    if(!empty($agent_id)){
        $agent_data = User::find($agent_id);
        if($agent_data->phone) {
            $country_code = $agent_data->country_code ?? '91';
            $agent_phone = '+'.$country_code.'-'.$agent_data->phone;
        }
        $agent_email = !empty($agent_data->email)?$agent_data->email:'';
    }

    if($order){
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){

            $rental_data = $OrderServiceVoucher->rental_data ? json_decode($OrderServiceVoucher->rental_data): [];

            $bike_name = $rental_data->bike_name??'';
            $city_name = $rental_data->city_name??'';
            $location_name = $rental_data->location_name??'';
            $pickup_date = !empty($rental_data->pickup_date)?date('d/m/Y h:i A',strtotime($rental_data->pickup_date)):'';
            $drop_date = !empty($rental_data->drop_date)?date('d/m/Y h:i A',strtotime($rental_data->drop_date)):'';
            $paid_amount = $rental_data->paid_amount ?? '';
            $total_amount = $order->total_amount??0;

            $user_phone = '';
            if($order_id) {
                $country_code = $order->country_code ?? '91';
                $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
            }

            $title = $OrderServiceVoucher->title ?? '';
            $trip_type = $OrderServiceVoucher->trip_type ?? '';
            $created_at = !empty($order->created_at)?date('D, M dS Y',strtotime($order->created_at)):'';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc =public_path(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            // AGENT LOGO
            $userAgentInfo = ''; $agentLogo = '';
            $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
            if($agent_id && $agent_id > 0){
                $userAgentInfo = $order->agentInfo ?? '';
                if($userAgentInfo){
                    $path = 'agent_logo/';
                    $agentLogo = $order->agentInfo->agent_logo ?? '';
                }
                if(!empty($agentLogo)){
                    if($storage->exists($path.$agentLogo)){
                        $logoSrc = public_path('/storage/'.$path.$agentLogo);
                    }
                }
                $total_amount = $order->sub_total_amount??0;
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

            $email_params = array(
               '{header}' => $common_logo,
               '{order_id}' =>  $order->order_no??'',
               '{booking_date}' =>  $created_at??'',
               '{title}' =>  $title??'',
               '{trip_type}' =>  $trip_type??'',
               '{gst_no}' =>  $OrderServiceVoucher->gst_no??'',
               '{bike_name}' =>  $bike_name??'',
               '{city_name}' =>  $city_name??'',
               '{location_name}' =>  $location_name??'',
               '{pickup_date}' =>  $pickup_date??'',
               '{drop_date}' =>  $drop_date??'',
               '{name}' =>  $OrderServiceVoucher->name??'',
               '{phone}' =>  $user_phone??'',
               '{email}' =>  $OrderServiceVoucher->email??'',
               '{amount}' =>  $total_amount??0,
               '{paid_amount}' =>  $paid_amount??'',
               '{tour_operator}' =>  $tour_operator??'',
               '{agency}' =>  $agent_phone?$agent_phone:$tour_operator,
               '{company_email}' =>  $agent_email?$agent_email:$company_email,
               '{gst_amount}' =>  '--',
           );

            $price_details = '';
            if($order->hide_price_details != 1){
                if(empty($agent_id)) {
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;line-height: normal;">Basic Fare</p>
                    </td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                    </td>
                    </tr>';
                }

                if(empty($agent_id)) {
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Other Taxes</p>
                    </td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. --</p>
                    </td>
                    </tr>';
                }
                $price_details .= '<tr>
                <td>&nbsp;</td>
                <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Total Amount</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
                </td>
                </tr>';
            }
            $email_params['{price_details}'] = $price_details;


           // $email_params = isset($email_params) ? $email_params : [];
           //$email_content= view("emails.rental_voucher_pdf", $email_params)->render();
            $email_content = view(config('custom.theme').'.common.vouchers.rental_voucher_pdf', $email_params)->render();
            $html = strtr($email_content, $email_params);

           // return view("admin.vouchers.cab_voucher_view", $data);
           // $html = preg_replace('/>\s+</', "><", $html);
            $pdf = PDF::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf_name = $order->order_no.'.pdf';
            return $pdf->download($pdf_name);

        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
}

public function activity_voucher_pdf(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);
    if($order){
        $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
        $total_pax = $booking_details->total_pax ?? '';
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        $user_phone = '';
        if($order_id) {
            $country_code = $order->country_code ?? '91';
            $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
        }
        if($OrderServiceVoucher){

            $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
            $title = $OrderServiceVoucher->title ?? '';
            $package_name = $package_data->package_name ?? '';
            $package_charges = $package_data->package_charges ?? 0;
            $package_charges = (int)$package_charges+(int)$order->markup;
            $paid_amount = $package_data->paid_amount ?? 0;
            $paid_amount = (int)$paid_amount+(int)$order->markup;
            $trip_date = !empty($package_data->trip_date)?date('D, M dS Y h:i A',strtotime($package_data->trip_date)):'';
            //$trip_date = $package_data->trip_date??'';
            $duration = $package_data->duration ?? '';
            $destination = $package_data->destination ?? '';
            $due_amount = $package_data->due_amount ?? '';
            if($package_data->address){    
                $address = $package_data->address ?? '';
            }
            $contact_person = $package_data->contact_person ?? '';
            $contact_phone = $package_data->contact_phone ?? '';
            $contact_email = $package_data->contact_email ?? '';
            $total_amount = $order->total_amount??0;
            $booking_date = !empty($order->created_at)?date('d M,Y h:i A',strtotime($order->created_at)):'';
            $orders_status = CustomHelper::showOrderStatus($order->orders_status)??'';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc =public_path(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            // AGENT LOGO
            $userAgentInfo = ''; $agentLogo = '';
            $agent_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
            $COMPANY_ADDRESS = CustomHelper::WebsiteSettings('COMPANY_ADDRESS');
            $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
            if($agent_id && $agent_id > 0){
                $userAgentInfo = $order->agentInfo ?? '';
                if($userAgentInfo) {
                    $path = 'agent_logo/';
                    $agentLogo = $order->agentInfo->agent_logo ?? '';
                    $COMPANY_ADDRESS = $order->agentInfo->company_name ?? '';

                    $userData = $userAgentInfo->User ?? '';

                    //$agent_phone = '';
                    $agent_email = '';
                    if($userData->phone) {
                        $country_code = $userData->country_code ?? '91';
                        $agent_phone = '+'.$country_code.'-'.$userData->phone;
                    }
                    $agent_email = !empty($userData->email)?$userData->email:'';

                }
                if(!empty($agentLogo)){
                    if($storage->exists($path.$agentLogo)){
                        $logoSrc = public_path('/storage/'.$path.$agentLogo);
                    }
                }
                $total_amount = $order->sub_total_amount??0; 
            }

            //Vendor Logo
            $packages = $OrderServiceVoucher->Package??'';
            $vendor_data = $packages->Admin??'';

            $is_vendor = isset($vendor_data->is_vendor)?$vendor_data->is_vendor:'';
            //$user_id = isset($vendor_data->id)?$vendor_data->id:'';
            $vendor_logo = '';
            $vendor_company_name = '';
            if($is_vendor == 1){
                $path = 'vendor_logo/';
                $vendor_logo = $vendor_data->vendor_logo ?? '';
                $vendor_company_name = $vendor_data->vendor_company_name ?? '';
                $vendorLogo = asset(config('custom.assets').'/images/default_vendor_logo.jpg');
                //$vendorLogo = public_path(config('custom.assets').'/images/default_vendor_logo.jpg');
                if(!empty($vendor_logo)){
                    if($storage->exists($path.$vendor_logo)){
                        //$vendorLogo = public_path('/storage/'.$path.$vendor_logo);
                        $vendorLogo = asset('/storage/'.$path.$vendor_logo);
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

            $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
            $person = '';
            $total_amount = 0;
            if(!empty($price_category_choice_record_arr)) {
                foreach($price_category_choice_record_arr as $key => $pccr) {
                    $input_label = $pccr['input_label']??'';
                    $input_value = $pccr['input_value']??0;
                    $input_price = $pccr['input_price']??0;
                    $sub_total = $input_value*$input_price;
                    $total_amount += $sub_total;

                    $price = CustomHelper::getPrice($input_price);
                    if($key != 0){
                      $person.= ", ";
                  }
                  $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;

              }
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
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            /*$contact_details .= '<tr>
            <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
            <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

            <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
            </td>
            </tr>';*/
        }
        //prd($contact_details);

        $email_params = array(
         '{header}' => $common_logo,
         '{order_no}' =>  $order->order_no??'',
         '{orders_status}' =>  $orders_status??'',
         '{package_name}' =>  $package_name??'',
         '{itinerary}' =>  $itinerary??'',
         '{title}' =>  $OrderServiceVoucher->title??'',
         '{trip_date}' =>  $trip_date??'',
         '{booking_date}' =>  $booking_date??'',
         '{duration}' =>  $duration??'',
         '{destination}' =>  $destination??'',
         '{name}' =>  $OrderServiceVoucher->name??'',
         '{vendor_logo}' =>  $vendorLogo??'',
         '{vendor_company_name}' =>  $vendor_company_name??'',
         '{phone}' =>  $user_phone??'',
         '{email}' =>  $OrderServiceVoucher->email??'',
         '{package_charges}' =>  $package_charges??'',
         '{paid_amount}' =>  $paid_amount??'',
         '{amount}' =>  $total_amount??0,
         '{due_amount}' =>  $due_amount??'',
         '{address}' =>  $address??'',
         '{company_agent_phone}' =>  $agent_phone??'',
         '{company_address}' =>  $COMPANY_ADDRESS??'',
          //  '{person}' => $person,
         '{person}' => $total_pax,
         '{contact_person}' => $contact_person,
         '{contact_details}' => $contact_details??'',
         '{contact_phone}' => $contact_phone,
         '{contact_email}' => $contact_email,
     );

        $price_details = '';
        if($order->hide_price_details != 1){
            
            $price_details .= '<table border="0" style="width:100%; border-collapse: collapse;">
            <tr>
            <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-top:0;border-left:0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;">Total</p>
            </td>
            <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-top:0;border-right:0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
            </td>
            </tr>';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 4px 15px 2px 10px;border:1px solid #cfcfcf;border-left:0;">
                <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid</p>
                </td>
                <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-right:0;">
                <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                </td>
                </tr>';
            }
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 4px 10px 20px 10px;border:1px solid #cfcfcf;border-left:0;">
                <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
                </td>
                <td style="padding: 4px 10px 20px 10px;border:1px solid #cfcfcf;border-right:0;">
                <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$due_amount.'</p>
                </td>
                </tr>';
            }
            $price_details .= '</table>';
        }
        $email_params['{price_details}'] = $price_details;

           // $email_params = isset($email_params) ? $email_params : [];
           //$email_content= view("admin.vouchers.activity_voucher_pdf", $email_params)->render();
        $email_content = view(config('custom.theme').'.common.vouchers.activity_voucher_pdf', $email_params)->render();
        $html = strtr($email_content, $email_params);
           // return view("admin.vouchers.cab_voucher_view", $data);
           // $html = preg_replace('/>\s+</', "><", $html);
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf_name = $order->order_no.'.pdf';
        return $pdf->download($pdf_name);

    }else{
        abort(404);
    }
}else{
    abort(404);
}

}
public function hotel_voucher_pdf(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);

    if($order){
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();

                //$booking_details = $order->booking_details ? json_decode($order->booking_details): [];

        if($OrderServiceVoucher){

            $hotel_data = $OrderServiceVoucher->hotel_data ? json_decode($OrderServiceVoucher->hotel_data): [];
            // prd($hotel_data);
            //$hotel_id = $booking_details->hotel_id??'';
            //$hotel_data = Accommodation::find($hotel_id);
            $contact_phone = $hotel_data->contact_phone??'';
            $contact_email = $hotel_data->contact_email??'';
            $contact_person = $hotel_data->contact_person??'';
            $title = $OrderServiceVoucher->title ?? '';
            $room_type = $hotel_data->room_name ?? '';
            $plan_name = $hotel_data->plan_name ?? '';
            //$checkin = $hotel_data->checkin ?? '';
            //$checkout = $hotel_data->checkout ?? '';
            $checkin = !empty($hotel_data->checkin)?date('d, M Y',strtotime($hotel_data->checkin)):'';
            $checkout = !empty($hotel_data->checkout)?date('d, M Y',strtotime($hotel_data->checkout)):'';
            $checkin_time = $hotel_data->checkin_time ?? '';
            $checkout_time = $hotel_data->checkout_time ?? '';
            $inventory = $hotel_data->inventory ?? '';
            $destination = $hotel_data->destination_name ?? '';
            $address = $hotel_data->address ?? '';
            $inclusions = $hotel_data->inclusions ?? '';
            $trip_date = !empty($hotel_data->trip_date)?date('D, M dS Y h:i A',strtotime($hotel_data->trip_date)):'';
            $created_at = !empty($hotel_data->created_at)?date('D, M dS Y',strtotime($hotel_data->created_at)):'';
            $adult = $hotel_data->adult ?? '';
            $child = $hotel_data->child ?? '';
            $hotel_charge = $hotel_data->hotel_charge ?? '';
            $paid_amount = $hotel_data->paid_amount ?? '';
            $total_amount = $order->total_amount ?? 0;

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc =public_path(config('custom.assets').'/images/logo.png');
            //$logoSrc = asset(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    //$logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

                // AGENT LOGO
            $userAgentInfo = ''; $agentLogo = '';
            $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
            if($agent_id && $agent_id > 0){
                $userAgentInfo = $order->agentInfo ?? '';
                if($userAgentInfo){
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
                        $logoSrc = public_path('/storage/'.$path.$agentLogo);
                        //$logoSrc = asset('/storage/'.$path.$agentLogo);
                    }
                }
                $total_amount = $order->sub_total_amount ?? 0;
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
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            /*$contact_details .= '<tr>
            <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
            <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

            <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
            </td>
            </tr>';*/
        }
        $email_params = array(
           '{header}' => $common_logo,
           '{booking_id}' =>  $order->order_no??'',
           '{hotel_name}' =>  $OrderServiceVoucher->title??'',
           '{customer_name}' =>  $OrderServiceVoucher->name??'',
                         //'{phone}' =>  $OrderServiceVoucher->phone??'',
           '{phone}' =>  $contact_phone??'',
                         //'{email}' =>  $OrderServiceVoucher->email??'',
           '{email}' =>  $contact_email??'',
           '{contact_person}' =>  $contact_person??'',
           '{room_type}' =>  $room_type??'',
           '{plan_name}' =>  $plan_name??'',
           '{checkin_date}' =>  $checkin??'',
           '{checkout_date}' =>  $checkout??'',
           '{checkin_time}' =>  $checkin_time??'',
           '{checkout_time}' =>  $checkout_time??'',
           '{inventory}' =>  $inventory??'',
           '{destination}' =>  $destination??'',
           '{address}' =>  $address??'',
           '{inclusions}' =>  $inclusions??'',
           '{adult}' =>  $adult??'',
           '{child}' =>  $child??'',
                         //'{booking_date}' =>  $trip_date??'',
           '{booking_date}' =>  $created_at??'',
           '{hotel_charge}' =>  $hotel_charge??'',
           '{contact_details}' =>  $contact_details??'',
           '{amount}' =>  $total_amount??0,
           '{paid_amount}' =>  $paid_amount??'',

       );

        $price_details = '';
        if($order->hide_price_details != 1){
            $price_details .= '<tr>
            <td style="padding: 10px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;line-height: normal;"><strong>Total Charges</strong></p>
            </td>
            <td colspan="3" style="padding: 10px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
            </td>
            </tr>';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;"><strong>Amount Paid</strong></p>
                </td>
                <td colspan="3" style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                </td>
                </tr>';
            }
        }
        $email_params['{price_details}'] = $price_details;

           // $email_params = isset($email_params) ? $email_params : [];
           //$email_content= view("admin.vouchers.hotel_voucher_pdf", $email_params)->render();
        $email_content = view(config('custom.theme').'.common.vouchers.hotel_voucher_pdf', $email_params)->render();
        $html = strtr($email_content, $email_params);

           // return view("admin.vouchers.cab_voucher_view", $data);
           // $html = preg_replace('/>\s+</', "><", $html);
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf_name = $order->order_no.'.pdf';
        return $pdf->download($pdf_name);

    }else{
        abort(404);
    }
}else{
    abort(404);
}

}
public function hotel_voucher_sendmail(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);

    if($order){
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();

        //$booking_details = $order->booking_details ? json_decode($order->booking_details): [];

        if($OrderServiceVoucher){

              //=============
            $sendVoucherMail = CustomHelper::sendVoucherMail($OrderServiceVoucher,$order);

                /*$voucherCreated = CustomHelper::getOrderStatus('voucher sent'); 

                $order->orders_status = $voucherCreated;
                $order->save();

                $params = [];
                $params['order_id'] = $order_id;
                $params['comments'] = "Voucher sent successfully";
                $params['orders_status'] = CustomHelper::showEnquiryMaster($voucherCreated);

                CustomHelper::RecordOrderStatusHistory($params);*/
                //=================
                $msg = "Mail sent successfully.";
                return back()->with('alert-success', $msg);
                
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }
    public function package_voucher_sendmail(Request $request) {

        $data = [];
        $order_id = $request->order_id ?? '';
        $order = Order::find($request->order_id);

        if($order){
            $package_id = $order->package_id ?? '';
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            if($OrderServiceVoucher){
               $voucher_id = $OrderServiceVoucher->id ?? '';

               $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
               $title = $OrderServiceVoucher->title ?? '';
               $package_name = $package_data->package_name ?? '';
               $package_charges = $package_data->package_charges ?? '';
               $paid_amount = $package_data->paid_amount ?? '';
               $trip_date = !empty($package_data->trip_date)?date('d M,Y',strtotime($package_data->trip_date)):'';
                //$trip_date = $package_data->trip_date ?? '';
               $duration = $package_data->duration ?? '';
               $destination = $package_data->destination ?? '';
               $due_amount = $package_data->due_amount ?? '';
               $address = $package_data->address ?? '';
               $contact_person = $package_data->contact_person ?? '';
               $contact_phone = $package_data->contact_phone ?? '';
               $contact_email = $package_data->contact_email ?? '';
               $total_amount = $order->total_amount??0;
               $gst_amount = number_format($order->gst_amount)??'';
               $tcs_amount = number_format($order->tcs_amount)??'';

               $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
               $storage = Storage::disk('public');

        /*$path = "settings/";
        $logoSrc =public_path(config('custom.assets').'/images/logo.png');
        if(!empty($FRONTEND_LOGO)){
            if($storage->exists($path.$FRONTEND_LOGO)){
                $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
            }
        }*/
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
            $total_amount = $order->sub_total_amount??0;
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

        $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
        $person = '';
        $total_amount = 0;
        if(!empty($price_category_choice_record_arr)) {
            foreach($price_category_choice_record_arr as $key => $pccr) {
                $input_label = $pccr['input_label']??'';
                $input_value = $pccr['input_value']??0;
                $input_price = $pccr['input_price']??0;
                $sub_total = $input_value*$input_price;
                $total_amount += $sub_total;

                $price = CustomHelper::getPrice($input_price);
                if($key != 0){
                  $person.= ", ";
              }
              $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;

          }
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
            '{company_title}' => $system_title
        );
        $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
        /*$contact_details .= '<tr>
        <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
        <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
        <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

        <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

        <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

        <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
        </td>
        </tr>';*/
    }
    $email_params = array(
       '{header}' => $common_logo,
       '{order_no}' =>  $order->order_no??'',
       '{package_name}' =>  $package_name??'',
       '{title}' =>  $OrderServiceVoucher->title??'',
       '{trip_date}' =>  $trip_date??'',
       '{duration}' =>  $duration??'',
       '{destination}' =>  $destination??'',
       '{name}' =>  $OrderServiceVoucher->name??'',
       '{phone}' =>  $OrderServiceVoucher->phone??'',
       '{email}' =>  $OrderServiceVoucher->email??'',
       '{amount}' =>  $total_amount??'',
       //'{gst_amount}' =>  $gst_price??'',
       //'{tcs_amount}' =>  $tcs_price??'',
       '{paid_amount}' =>  $paid_amount??'',
       '{due_amount}' =>  $due_amount??'',
       '{address}' =>  $address??'',
       '{person}' => $person,
       '{contact_person}' => $contact_person??'',
       '{contact_details}' => $contact_details??'',
       '{contact_phone}' => $contact_phone??'',
       '{contact_email}' => $contact_email??'',
    );

    $price_details = '';
    if($order->hide_price_details != 1){
        $price_details = '';
        if(empty($agent_id)){
            $price_details .= '<tr>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid</p>
            </td>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.number_format($paid_amount).'</p>
            </td>
            </tr>';
        }
        $price_details .= '<tr>
        <td style="padding: 2px 15px 2px 15px;border: 0;">
        <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Total Booking Amount</p>
        </td>
        <td style="padding: 2px 15px 2px 15px;border: 0;">
        <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.number_format($total_amount).'</p>
        </td>
        </tr>';

        if(!empty($gst_amount)){
          $price_details .= '<tr>
          <td style="padding: 2px 15px 2px 15px;border: 0;">
          <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">GST Amount</p>
          </td>
          <td style="padding: 2px 15px 2px 15px;border: 0;">
          <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$gst_amount.'</p>
          </td>
          </tr>';
        }

        if(!empty($tcs_amount)){
          $price_details .= '<tr>
          <td style="padding: 2px 15px 2px 15px;border: 0;">
          <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">TCS Amount</p>
          </td>
          <td style="padding: 2px 15px 2px 15px;border: 0;">
          <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$tcs_amount.'</p>
          </td>
          </tr>';
        }
       if(empty($agent_id)){
            $price_details .= '<tr>
            <td style="padding: 2px 15px 20px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
            </td>
            <td style="padding: 2px 15px 20px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.number_format($due_amount).'</p>
            </td>
            </tr>';
        }
    }
    $email_params['{price_details}'] = $price_details;

    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
    $email_template = EmailTemplate::where('slug', 'package-voucher')->where('status', 1)->first();
    $email_content = isset($email_template->content) ? $email_template->content : '';
    // $email_params = isset($email_params) ? $email_params : [];
    //Send mail
    $email_content = strtr($email_content, $email_params);
    $email_subject = isset($email_template->subject) ? $email_template->subject : '';
    $email_subject = strtr($email_subject, $email_params);
    $email_bcc_admin = isset($email_template->bcc_admin) ? $email_template->bcc_admin : 0;
    $email_type = isset($email_template->email_type) ? $email_template->email_type : '';
    //=PDF
    //$pdf_content= view("admin.vouchers.package_voucher_pdf", $email_params)->render();
    $pdf_content = view(config('custom.theme').'.common.vouchers.package_voucher_pdf', $email_params)->render();
    $html = strtr($pdf_content, $email_params);

    $pdf = PDF::loadHTML($html);
    $path = 'temp/';
    $pdf_name = $order->order_no.'.pdf';
    if (!File::exists(public_path("storage/" . $path))) {
        File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
    }
    $pdf->save(public_path("storage/" . $path).$pdf_name);
    $attachments = public_path("storage/".$path).$pdf_name;

    //============

    $REPLYTO = $ADMIN_EMAIL;
    //$to_email = $order->email;
    $to_email = $OrderServiceVoucher->email ? $OrderServiceVoucher->email : '';
    $cc_email = '';
    $bcc_email = '';
    $params = [];
    $params['to'] = $to_email;
    $params['cc'] = $cc_email;
    $params['bcc'] = $bcc_email;
    $params['reply_to'] = $to_email;
    $params['subject'] = $email_subject;
    $params['email_content'] = $email_content;
    if(isset($attachments)) {
        $params['attachments'] = $attachments;
    }
    $isSent = CustomHelper::sendCommonMail($params);

    //===============

    $voucherCreated = CustomHelper::getOrderStatus('voucher sent'); 

    $order->orders_status = $voucherCreated;
    $order->save();

    $params = [];
    $params['order_id'] = $order_id;
    $params['comments'] = "Voucher sent successfully";
    $params['orders_status'] = CustomHelper::showEnquiryMaster($voucherCreated);

    CustomHelper::RecordOrderStatusHistory($params);

    //voucher Send email log

    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
    $module_desc =  !empty($title)?$title:'';
    $new_data =(array) $new_data;
    $new_data = json_encode($new_data);
    $module_id= $order_id;
    $params['user_id'] = $user_id;
    $params['user_name'] = $user_name;
    $params['module'] = 'Package Voucher';
    $params['module_desc'] = $module_desc;
    $params['module_id'] = $module_id;
    $params['sub_module'] = '';
    $params['sub_module_id'] = $voucher_id;
    $params['sub_sub_module'] = "";
    $params['sub_sub_module_id'] = 0;
    $params['data_after_action'] = $new_data;
    $params['action'] = "Email Sent";

    CustomHelper::RecordActivityLog($params);

    $msg = "Mail sent successfully.";

    return back()->with('alert-success', $msg);

    // $data['order_id'] = $order_id;
    // return view("admin.vouchers.cab_voucher_view", $data);

    // $html = preg_replace('/>\s+</', "><", $html);
    // $pdf = PDF::loadHTML($html);
    // $pdf->setPaper('A4', 'portrait');
    // $package_name = $package_data->title ?? 'itinerary';
    // return $pdf->download('aaas.pdf');
    // return $pdf->stream('itinerary.pdf');

    }else{
        abort(404);
    }
    }else{
        abort(404);
    }
}

//===========

public function cab_voucher_sendmail(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);
    $agent_id = $order->agent_id??'';
    $tour_operator = CustomHelper::WebsiteSettings('SALE_PHONE');
    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');
    $agent_phone = '';
    $agent_email = '';
    if(!empty($agent_id)){
        $agent_data = User::find($agent_id);
        if($agent_data->phone) {
            $country_code = $agent_data->country_code ?? '91';
            $agent_phone = '+'.$country_code.'-'.$agent_data->phone;
        }
        $agent_email = !empty($agent_data->email)?$agent_data->email:'';
    }
    // Driver Deatils
    $booking_details = $order->booking_details ? json_decode($order->booking_details): '';
    $driver_details  = $booking_details->driver_details ?? [];

    $vehicle_no = $driver_details->vehicle_no ?? '';
    $vehicle_type = $driver_details->vehicle_type ?? '';
    $driver_name = $driver_details->driver_name ?? '';
    $driver_phone = $driver_details->mobile_no ?? '';

    if($order){
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){
            $voucher_id = $OrderServiceVoucher->id ?? '';

            // prd($req_data);
            $cab_data = $OrderServiceVoucher->cab_data ? json_decode($OrderServiceVoucher->cab_data, true): [];
            $user_phone = '';
            if($order_id) {
                $country_code = $order->country_code ?? '91';
                $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
            }

            $title = $OrderServiceVoucher->title ?? '';
            $trip_type = $OrderServiceVoucher->trip_type ?? '';
            $created_at = !empty($order->created_at)?date('D, M dS Y h:i A',strtotime($order->created_at)):'';
            $pickup_address = $cab_data['pickup_address'] ?? '';
            $pickup_date = $cab_data['pickup_date'] ?? '';
            $drop_address = $cab_data['drop_address'] ?? '';
            $origin = $cab_data['origin'] ?? '';
            $destination = $cab_data['destination'] ?? '';
            $trip_date = $cab_data['trip_date'] ?? '';
            $car_type = $cab_data['car_type'] ?? '';
            $exclusion = $cab_data['exclusion'] ?? '';
            $cab_charge = $cab_data['cab_charge'] ?? '';
            $total_amount = $order->total_amount ?? 0;
            $paid_amount = $cab_data['paid_amount'] ?? '';
            $be_paid_to_driver = $cab_data['be_paid_to_driver'] ?? '';

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
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
                if($userAgentInfo){
                    $path = 'agent_logo/';
                    $agentLogo = $order->agentInfo->agent_logo ?? '';
                }
                if(!empty($agentLogo)){
                    if($storage->exists($path.$agentLogo)){
                        $logoSrc = asset('/storage/'.$path.$agentLogo);
                    }
                }
                $total_amount = $order->sub_total_amount ?? 0;
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

            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');

            if($driver_details){
                $driver_html = '<p style="color: #333;font-size: 14px;font-weight: 400;font-family: "Roboto", sans-serif, Arial;margin: 4px 0px 0;">'.$vehicle_no.'<br>Driver:'.$driver_name.'<br>Mobile: '.$driver_phone.'</p>';
            }else{
                $driver_html = '<p style="color: #333;font-size: 14px;font-weight: 400;font-family: "Roboto", sans-serif, Arial;margin: 4px 0px;"> Driver details will be shared up to 30 mins prior to departure</p>';
            }

            $email_params = array(
             '{header}' => $common_logo,
             '{order_id}' =>  $order->order_no??'',
             '{booking_date}' =>  $created_at??'',
             '{title}' =>  $OrderServiceVoucher->title??'',
             '{trip_type}' =>  $OrderServiceVoucher->trip_type??'',
             '{gst_no}' =>  $OrderServiceVoucher->gst_no??'',

             '{pickup_address}' =>  $pickup_address??'',
             '{pickup_date}' =>  $pickup_date??'',
             '{drop_address}' =>  $drop_address??'',
             '{car_type}' =>  $car_type??'',
             '{vehicle_type}' =>  $vehicle_type??'',
             '{exclusion}' =>  $exclusion??'',

             '{name}' =>  $OrderServiceVoucher->name??'',
             '{phone}' =>  $user_phone??'',
             '{email}' =>  $OrderServiceVoucher->email??'',
             '{amount}' =>  $total_amount??'',
             '{cab_charge}' =>  $cab_charge??'',
             '{paid_amount}' =>  $paid_amount??'',
             '{be_paid_to_driver}' =>  $be_paid_to_driver??'',
             '{tour_operator}' =>  $tour_operator??'',
             '{agency}' =>  $agent_phone?$agent_phone:$tour_operator,
             '{company_email}' =>  $agent_email?$agent_email:$company_email,
             '{departing}' =>  $origin??'',
             '{arrival}' =>  $destination??'',
             '{driver_name}' =>  $driver_name??'',
             '{driver_phone}' =>  $driver_phone??'',
             '{driver_details}' =>  $driver_html??'',
             '{gst_amount}' =>  '--',
             '{taxi_no}' =>  $vehicle_no??'',
             '{distance}' =>  '--',
             '{extra_fare}' =>  '--',
         );
            $price_details = '';
            if($order->hide_price_details != 1){
                if(empty($agent_id)) {
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;line-height: normal;">Basic Fare</p>
                    </td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                    </td>
                    </tr>';
                }

                if(empty($agent_id)) {
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Other Taxes</p>
                    </td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. --</p>
                    </td>
                    </tr>';
                }
                $price_details .= '<tr>
                <td>&nbsp;</td>
                <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Total Amount</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
                </td>
                </tr>';
            }
            $email_params['{price_details}'] = $price_details;

            $booking_details = $cab_data??[];

            $paxinfo = '';
            if(isset($booking_details['adult']) || isset($booking_details['children'])) {
                $paxinfo .= 'Total Pax:';
                if(isset($booking_details['adult']) && isset($booking_details['children'])) {
                    $paxinfo .= $booking_details['adult'].' Adults, '.$booking_details['children'].' Child';
                } else if(isset($booking_details['adult'])) {
                    $paxinfo .= $booking_details['adult'].' Adults';
                } else if(isset($booking_details['children'])) {
                    $paxinfo .= $booking_details['children'].' Child';
                }
            }

            $addons = '';
            if(isset($booking_details['selected_addons']) && !empty($booking_details['selected_addons'])) {
                foreach($booking_details['selected_addons'] as $addon) {
                    $addon_str = ($addon['name']??'').': Qty:'.($addon['qty']??'');
                    if(isset($addon['daily_rental'])) {
                        $addon_str .= ' Days:'.($addon['days']??'');
                    }
                    $addons .= $addon_str.' <br />';
                }
            }
            $email_params['{paxInfo}'] = $paxinfo;
            $email_params['{addOns}'] = $addons;

            $email_params['booking_details'] = $booking_details;

            $email_template = EmailTemplate::where('slug', 'car-booking-voucher')->where('status', 1)->first();
            $email_content = isset($email_template->content) ? $email_template->content : '';
               // $email_params = isset($email_params) ? $email_params : [];

               //Send mail
            $email_content = strtr($email_content, $email_params);
            $email_subject = isset($email_template->subject) ? $email_template->subject : '';
            $email_subject = strtr($email_subject, $email_params);
            $email_bcc_admin = isset($email_template->bcc_admin) ? $email_template->bcc_admin : 0;
            $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

               //=PDF

                //$pdf_content= view("emails.cab_voucher_pdf", $email_params)->render();
            $pdf_content = view(config('custom.theme').'.common.vouchers.cab_voucher_pdf', $email_params)->render();
            $html = strtr($pdf_content, $email_params);

            $pdf = PDF::loadHTML($html);
            $path = 'temp/';
            $pdf_name = $order->order_no.'.pdf';
            if (!File::exists(public_path("storage/" . $path))) {
                File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
            }
            $pdf->save(public_path("storage/" . $path).$pdf_name);
            $attachments = public_path("storage/".$path).$pdf_name;

               //============

            $REPLYTO = $ADMIN_EMAIL;
            $to_email = $OrderServiceVoucher->email ? $OrderServiceVoucher->email : '';
            $cc_email = '';
            $bcc_email = '';
            $params = [];
            $params['to'] = $to_email;
            $params['cc'] = $cc_email;
            $params['bcc'] = $bcc_email;
            $params['reply_to'] = $to_email;
            $params['subject'] = $email_subject;
            $params['email_content'] = $email_content;
            if(isset($attachments)) {
                $params['attachments'] = $attachments;
            }
            $isSent = CustomHelper::sendCommonMail($params);

               //===============

            $voucherCreated = CustomHelper::getOrderStatus('voucher sent');  

            $order->orders_status = $voucherCreated;
            $order->save();

            $params = [];
            $params['order_id'] = $order_id;
            $params['comments'] = "Voucher sent successfully";
            $params['orders_status'] = CustomHelper::showEnquiryMaster($voucherCreated);

            CustomHelper::RecordOrderStatusHistory($params);

            //voucher Send email log

            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
            $module_desc =  !empty($title)?$title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $module_id= $order_id;
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Cab Voucher';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = '';
            $params['sub_module_id'] = $voucher_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = "Email Sent";

            CustomHelper::RecordActivityLog($params);

            $msg = "Mail sent successfully.";

            return back()->with('alert-success', $msg);


           // $data['order_id'] = $order_id;
           // return view("admin.vouchers.cab_voucher_view", $data);

           // $html = preg_replace('/>\s+</', "><", $html);
           // $pdf = PDF::loadHTML($html);
           // $pdf->setPaper('A4', 'portrait');
           // $package_name = $package_data->title ?? 'itinerary';
           // return $pdf->download('aaas.pdf');
           // return $pdf->stream('itinerary.pdf');

        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
}

public function rental_voucher_sendmail(Request $request) {
    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);
    $agent_id = $order->agent_id??'';
    $tour_operator = CustomHelper::WebsiteSettings('SALE_PHONE');
    $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');
    $agent_phone = '';
    $agent_email = '';
    if(!empty($agent_id)){
        $agent_data = User::find($agent_id);
        if($agent_data->phone) {
            $country_code = $agent_data->country_code ?? '91';
            $agent_phone = '+'.$country_code.'-'.$agent_data->phone;
        }
        $agent_email = !empty($agent_data->email)?$agent_data->email:'';
    }
    if($order){
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){
            $voucher_id = $OrderServiceVoucher->id ?? '';
            $rental_data = $OrderServiceVoucher->rental_data ? json_decode($OrderServiceVoucher->rental_data): [];

            $bike_name = $rental_data->bike_name??'';
            $city_name = $rental_data->city_name??'';
            $location_name = $rental_data->location_name??'';
            $paid_amount = $rental_data->paid_amount ?? '';
            $pickup_date = !empty($rental_data->pickup_date)?date('d/m/Y h:i A',strtotime($rental_data->pickup_date)):'';
            $drop_date = !empty($rental_data->drop_date)?date('d/m/Y h:i A',strtotime($rental_data->drop_date)):'';
            $created_at = !empty($order->created_at)?date('D, M dS Y',strtotime($order->created_at)):'';

            $user_phone = '';
            if($order_id) {
                $country_code = $order->country_code ?? '91';
                $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
            }
            $total_amount = $order->total_amount??0;

            $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
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
                if($userAgentInfo){
                    $path = 'agent_logo/';
                    $agentLogo = $order->agentInfo->agent_logo ?? '';
                }
                if(!empty($agentLogo)){
                    if($storage->exists($path.$agentLogo)){
                        $logoSrc = asset('/storage/'.$path.$agentLogo);
                    }
                }
                $total_amount = $order->sub_total_amount??0;
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

            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');

            $email_params = array(
                '{header}' => $common_logo,
                '{order_id}' =>  $order->order_no??'',
                '{booking_date}' =>  $created_at??'',
                '{title}' =>  $OrderServiceVoucher->title??'',
                '{trip_type}' =>  $OrderServiceVoucher->trip_type??'',
                '{gst_no}' =>  $OrderServiceVoucher->gst_no??'',
                '{bike_name}' =>  $bike_name??'',
                '{city_name}' =>  $city_name??'',
                '{location_name}' =>  $location_name??'',
                '{pickup_date}' =>  $pickup_date??'',
                '{drop_date}' =>  $drop_date??'',
                '{name}' =>  $OrderServiceVoucher->name??'',
                '{phone}' =>  $user_phone??'',
                '{email}' =>  $OrderServiceVoucher->email??'',
                '{amount}' =>  $total_amount??'',
                '{paid_amount}' =>  $paid_amount??'',
                '{tour_operator}' =>  $tour_operator??'',
                '{agency}' =>  $agent_phone?$agent_phone:$tour_operator,
                '{company_email}' =>  $agent_email?$agent_email:$company_email,
                '{gst_amount}' =>  '--',
            );
            $price_details = '';
            if($order->hide_price_details != 1){
                if(empty($agent_id)) {
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;line-height: normal;">Basic Fare</p>
                    </td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                    </td>
                    </tr>';
                }

                if(empty($agent_id)) {
                    $price_details .= '<tr>
                    <td>&nbsp;</td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Other Taxes</p>
                    </td>
                    <td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
                    <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. --</p>
                    </td>
                    </tr>';
                }
                $price_details .= '<tr>
                <td>&nbsp;</td>
                <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;">Total Amount</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 1px solid #585858;">
                <p style="color: #333;font-size: 15px;font-family: \'Roboto\', sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
                </td>
                </tr>';
            }
            $email_params['{price_details}'] = $price_details;

            $email_template = EmailTemplate::where('slug', 'rental-booking-voucher')->where('status', 1)->first();
            $email_content = isset($email_template->content) ? $email_template->content : '';
                   // $email_params = isset($email_params) ? $email_params : [];

                   //Send mail
            $email_content = strtr($email_content, $email_params);
            $email_subject = isset($email_template->subject) ? $email_template->subject : '';
            $email_subject = strtr($email_subject, $email_params);
            $email_bcc_admin = isset($email_template->bcc_admin) ? $email_template->bcc_admin : 0;
            $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

            //=PDF

            // $pdf_content= view("emails.rental_voucher_pdf", $email_params)->render();
            $pdf_content = view(config('custom.theme').'.common.vouchers.rental_voucher_pdf', $email_params)->render();

            $html = strtr($pdf_content, $email_params);

            $pdf = PDF::loadHTML($html);
            $path = 'temp/';
            $pdf_name = $order->order_no.'.pdf';
            if (!File::exists(public_path("storage/" . $path))) {
                File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
            }
            $pdf->save(public_path("storage/" . $path).$pdf_name);
            $attachments = public_path("storage/".$path).$pdf_name;

            //============

            $REPLYTO = $ADMIN_EMAIL;
            $to_email = $OrderServiceVoucher->email ? $OrderServiceVoucher->email : '';
            $cc_email = '';
            $bcc_email = '';
            $params = [];
            $params['to'] = $to_email;
            $params['cc'] = $cc_email;
            $params['bcc'] = $bcc_email;
            $params['reply_to'] = $to_email;
            $params['subject'] = $email_subject;
            $params['email_content'] = $email_content;
            if(isset($attachments)) {
                $params['attachments'] = $attachments;
            }
            $isSent = CustomHelper::sendCommonMail($params);

            //===============

            $voucherCreated = CustomHelper::getOrderStatus('voucher sent');
            $order->orders_status = $voucherCreated;
            $order->save();

            $params = [];
            $params['order_id'] = $order_id;
            $params['comments'] = "Voucher sent successfully";
            $params['orders_status'] = CustomHelper::showEnquiryMaster($voucherCreated);

            CustomHelper::RecordOrderStatusHistory($params);

            //voucher Send email log

            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
            $module_desc =  !empty($title)?$title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $module_id= $order_id;
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Rental Voucher';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = '';
            $params['sub_module_id'] = $voucher_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = "Email Sent";

            CustomHelper::RecordActivityLog($params);

            $msg = "Mail sent successfully.";

            return back()->with('alert-success', $msg);

           // $data['order_id'] = $order_id;
           // return view("admin.vouchers.cab_voucher_view", $data);

           // $html = preg_replace('/>\s+</', "><", $html);
           // $pdf = PDF::loadHTML($html);
           // $pdf->setPaper('A4', 'portrait');
           // $package_name = $package_data->title ?? 'itinerary';
           // return $pdf->download('aaas.pdf');
           // return $pdf->stream('itinerary.pdf');
        }else{
            abort(404);
        }
    }else{
        abort(404);
    }
}

//==========
public function activity_voucher_sendmail(Request $request) {

    $data = [];
    $order_id = $request->order_id ?? '';
    $order = Order::find($request->order_id);

    if($order){
        $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
        $total_pax = $booking_details->total_pax ?? '';
        $package_id = $order->package_id ?? '';
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
        if($OrderServiceVoucher){
           $voucher_id = $OrderServiceVoucher->id ?? '';

           $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
           $title = $OrderServiceVoucher->title ?? '';
           $package_name = $package_data->package_name ?? '';
           $package_charges = $package_data->package_charges ?? '';
           $package_charges = (int)$package_charges+(int)$order->markup;
           $paid_amount = $package_data->paid_amount ?? '';
           $paid_amount = (int)$paid_amount+(int)$order->markup;
           $trip_date = !empty($package_data->trip_date)?date('D, M dS Y h:i A',strtotime($package_data->trip_date)):'';
            //$trip_date = $package_data->trip_date??'';
           $duration = $package_data->duration ?? '';
           $destination = $package_data->destination ?? '';
           $due_amount = $package_data->due_amount ?? '';
           if($package_data->address){    
                $address = $package_data->address ?? '';
           }
           $contact_person = $package_data->contact_person ?? '';
           $contact_phone = $package_data->contact_phone ?? '';
           $contact_email = $package_data->contact_email ?? '';
           $total_amount = $order->total_amount ?? 0;
           $booking_date = !empty($order->created_at)?date('d M,Y h:i A',strtotime($order->created_at)):'';
           $voucherSent = CustomHelper::getOrderStatus('voucher sent');
           $orders_status = CustomHelper::showOrderStatus($voucherSent)??'';

           $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
           $storage = Storage::disk('public');
            /*$path = "settings/";
            $logoSrc =public_path(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
                }
            }*/
            $path = "settings/";
            $logoSrc =asset(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                }
            }

            // AGENT LOGO
            $userAgentInfo = ''; $agentLogo = '';
            $agent_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
            $COMPANY_ADDRESS = CustomHelper::WebsiteSettings('COMPANY_ADDRESS');
            $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
            if($agent_id && $agent_id > 0){
                $userAgentInfo = $order->agentInfo ?? '';
                if($userAgentInfo){
                    $path = 'agent_logo/';
                    $agentLogo = $order->agentInfo->agent_logo ?? '';
                    $COMPANY_ADDRESS = $order->agentInfo->company_name ?? '';

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
                $total_amount = $order->sub_total_amount ?? 0;
            }

            //Vendor Logo
            $packages = $OrderServiceVoucher->Package??'';
            $vendor_data = $packages->Admin??'';

            $is_vendor = isset($vendor_data->is_vendor)?$vendor_data->is_vendor:'';
            //$user_id = isset($vendor_data->id)?$vendor_data->id:'';
            $vendor_logo = '';
            $vendor_company_name = '';
            if($is_vendor == 1){
                $path = 'vendor_logo/';
                $vendor_logo = $vendor_data->vendor_logo ?? '';
                $vendor_company_name = $vendor_data->vendor_company_name ?? '';
                $vendorLogo = asset(config('custom.assets').'/images/default_vendor_logo.jpg');
                //$vendorLogo = public_path(config('custom.assets').'/images/vendor_logo.jpg');
                if(!empty($vendor_logo)){
                    if($storage->exists($path.$vendor_logo)){
                        //$vendorLogo = public_path('/storage/'.$path.$vendor_logo);
                        $vendorLogo = asset('/storage/'.$path.$vendor_logo);
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

            $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
            $person = '';
            $total_amount = 0;
            if(!empty($price_category_choice_record_arr)) {
                foreach($price_category_choice_record_arr as $key => $pccr) {
                    $input_label = $pccr['input_label']??'';
                    $input_value = $pccr['input_value']??0;
                    $input_price = $pccr['input_price']??0;
                    $sub_total = $input_value*$input_price;
                    $total_amount += $sub_total;

                    $price = CustomHelper::getPrice($input_price);
                    if($key != 0){
                      $person.= ", ";
                  }
                  $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;
              }
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
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            /*$contact_details .= '<tr>
            <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
            <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

            <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
            </td>
            </tr>';*/
        }

        $email_params = array(
         '{header}' => $common_logo,
         '{order_no}' =>  $order->order_no??'',
         '{orders_status}' =>  $orders_status??'',
         '{package_name}' =>  $package_name??'',
         '{itinerary}' =>  $itinerary??'',
         '{title}' =>  $OrderServiceVoucher->title??'',
         '{trip_date}' =>  $trip_date??'',
         '{booking_date}' =>  $booking_date??'',
         '{duration}' =>  $duration??'',
         '{destination}' =>  $destination??'',
         '{name}' =>  $OrderServiceVoucher->name??'',
         '{phone}' =>  $OrderServiceVoucher->phone??'',
         '{email}' =>  $OrderServiceVoucher->email??'',
         '{vendor_logo}' =>  $vendorLogo??'',
         '{vendor_company_name}' =>  $vendor_company_name??'',
         '{amount}' =>  $total_amount??0,
         '{paid_amount}' =>  $paid_amount??'',
         '{due_amount}' =>  $due_amount??'',
         '{address}' =>  $address??'',
         '{company_agent_phone}' =>  $agent_phone??'',
         '{company_address}' =>  $COMPANY_ADDRESS??'',
          // '{person}' => $person,
         '{person}' => $total_pax,
         '{contact_person}' => $contact_person,
         '{contact_details}' => $contact_details??'',
         '{contact_phone}' => $contact_phone,
         '{contact_email}' => $contact_email,
     );

    $price_details = '';
    /*if($order->hide_price_details != 1){
        if(empty($agent_id)){
            $price_details .= '<tr>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid to Overland Escape</p>
            </td>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
            </td>
            </tr>';
        }
        $price_details .= '<tr>
        <td style="padding: 2px 15px 2px 15px;border: 0;">
        <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;">Total Booking Amount</p>
        </td>
        <td style="padding: 2px 15px 2px 15px;border: 0;">
        <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
        </td>
        </tr>';

        if(empty($agent_id)){
            $price_details .= '<tr>
            <td style="padding: 2px 15px 20px 15px;border: 0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
            </td>
            <td style="padding: 2px 15px 20px 15px;border: 0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$due_amount.'</p>
            </td>
            </tr>';
        }
    }*/

    if($order->hide_price_details != 1){
            
        $price_details .= '<table border="0" style="width:100%; border-collapse: collapse;">
        <tr>
        <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-top:0;border-left:0;">
        <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;">Total</p>
        </td>
        <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-top:0;border-right:0;">
        <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
        </td>
        </tr>';
        if(empty($agent_id)){
            $price_details .= '<tr>
            <td style="padding: 4px 15px 2px 10px;border:1px solid #cfcfcf;border-left:0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid</p>
            </td>
            <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-right:0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
            </td>
            </tr>';
        }
        if(empty($agent_id)){
            $price_details .= '<tr>
            <td style="padding: 4px 10px 20px 10px;border:1px solid #cfcfcf;border-left:0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
            </td>
            <td style="padding: 4px 10px 20px 10px;border:1px solid #cfcfcf;border-right:0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$due_amount.'</p>
            </td>
            </tr>';
        }
        $price_details .= '</table>';
    }
    $email_params['{price_details}'] = $price_details;

    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');


    $email_template = EmailTemplate::where('slug', 'activity-voucher')->where('status', 1)->first();
    $email_content = isset($email_template->content) ? $email_template->content : '';
    // $email_params = isset($email_params) ? $email_params : [];

    //Send mail
    $email_content = strtr($email_content, $email_params);
    $email_subject = isset($email_template->subject) ? $email_template->subject : '';
    $email_subject = strtr($email_subject, $email_params);
    $email_bcc_admin = isset($email_template->bcc_admin) ? $email_template->bcc_admin : 0;
    $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

    //=PDF
    //$pdf_content= view("admin.vouchers.activity_voucher_pdf", $email_params)->render();
    $pdf_content = view(config('custom.theme').'.common.vouchers.activity_voucher_pdf', $email_params)->render();
    $html = strtr($pdf_content, $email_params);
    $pdf = PDF::loadHTML($html);
    $path = 'temp/';
    $pdf_name = $order->order_no.'.pdf';
    if (!File::exists(public_path("storage/" . $path))) {
        File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
    }
    $pdf->save(public_path("storage/" . $path).$pdf_name);
    $attachments = public_path("storage/".$path).$pdf_name;

    //============

    $REPLYTO = $ADMIN_EMAIL;
    $to_email = $OrderServiceVoucher->email ? $OrderServiceVoucher->email : '';
    $cc_email = '';
    $bcc_email = '';
    $params = [];
    $params['to'] = $to_email;
    $params['cc'] = $cc_email;
    $params['bcc'] = $bcc_email;
    $params['reply_to'] = $to_email;
    $params['subject'] = $email_subject;
    $params['email_content'] = $email_content;
    if(isset($attachments)) {
        $params['attachments'] = $attachments;
    }
    $isSent = CustomHelper::sendCommonMail($params);

    //===============

    $voucherCreated = CustomHelper::getOrderStatus('voucher sent');

    $order->orders_status = $voucherCreated;
    $order->save();

    $params = [];
    $params['order_id'] = $order_id;
    $params['comments'] = "Voucher sent successfully";
    $params['orders_status'] = CustomHelper::showEnquiryMaster($voucherCreated);

    CustomHelper::RecordOrderStatusHistory($params);

    //voucher Send email log

    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
    $module_desc =  !empty($title)?$title:'';
    $new_data =(array) $new_data;
    $new_data = json_encode($new_data);
    $module_id= $order_id;
    $params['user_id'] = $user_id;
    $params['user_name'] = $user_name;
    $params['module'] = 'Activity Voucher';
    $params['module_desc'] = $module_desc;
    $params['module_id'] = $module_id;
    $params['sub_module'] = '';
    $params['sub_module_id'] = $voucher_id;
    $params['sub_sub_module'] = "";
    $params['sub_sub_module_id'] = 0;
    $params['data_after_action'] = $new_data;
    $params['action'] = "Email Sent";

    CustomHelper::RecordActivityLog($params);

    $msg = "Mail sent successfully.";

    return back()->with('alert-success', $msg);

   // $data['order_id'] = $order_id;
   // return view("admin.vouchers.cab_voucher_view", $data);

   // $html = preg_replace('/>\s+</', "><", $html);
   // $pdf = PDF::loadHTML($html);
   // $pdf->setPaper('A4', 'portrait');
   // $package_name = $package_data->title ?? 'itinerary';
   // return $pdf->download('aaas.pdf');
   // return $pdf->stream('itinerary.pdf');

}else{
    abort(404);
}
}else{
    abort(404);
}
}

public function flight_voucher_view(Request $request) {
    $data = [];
    $order_id = $request->order_id ?? '';
    if($request->order_id) {
        $order = Order::find($request->order_id);
        // prd($order->toArray());
        if($order && $order->order_type == 3 && $order->payment_status == 1 && !empty($order->bookingId)) {
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
            }

            $order_id = $order->id;
            $phone = '';
            if($order->phone) {
                $phone_country = $order->phone_country ?? '91';
                $phone = '+'.$phone_country.'-'.$order->phone;
            }
            $segments3 = $request->segment(3);
            // prd($segments3);
            if($segments3 == 'flight_voucher_sendmail') {
                $record_name = 'Booking Flight';
                $form_values = [];
                $form_values['{header}'] = $common_logo??'';
                $form_values['{logo}'] = $logoSrc??'';
                $form_values['{contact_details}'] = $contact_details??'';
                $form_values['{e_date}'] = date("l jS \of F Y h:i A");
                $form_values['{SYSTEM_TITLE}'] = $SYSTEM_TITLE;
                $form_values['{name}'] = $order->name;
                $form_values['{email}'] = $order->email;
                $form_values['{phone}'] = $phone;
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

                $pdf = PDF::loadView("emails._flight_booking_pdf", $params);

                $path = 'temp/';
                $pdf_name = $order->order_no.'.pdf';
                if (!File::exists(public_path("storage/" . $path))) {
                    File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
                }
                $pdf->save(public_path("storage/" . $path).$pdf_name);
                $attachments = public_path("storage/".$path).$pdf_name;
                $isSent = false;
                if(!empty($order->email) && !empty($email_template)) {
                    $email = $order->email;
                    $cc_email = '';
                    $bcc_email = '';

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
                        $bcc_email = $ADMIN_EMAIL;
                    }

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

                if($isSent) {
                    $user_id = auth()->user()->id;
                    $user_name = auth()->user()->name;
                    $module_desc = 'Flight';
                    $module_id = $order_id;
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Flight Voucher';
                    $params['module_desc'] = $module_desc;
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = '';
                    $params['sub_module_id'] = '';
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = '';
                    $params['action'] = "Email Sent";

                    CustomHelper::RecordActivityLog($params);
                    $msg = "Mail sent successfully.";
                    return back()->with('alert-success', $msg);
                } else {
                    $msg = "Error while sending email";
                    return back()->with('alert-danger', $msg);
                }
            } else if($segments3 == 'flight_voucher_pdf') {
                $params = [];
                $params['order'] = $order;
                if($order->booking_details) {
                    $params['booking_details'] = json_decode($order->booking_details, true);
                }
                $print_name = $request->name??'';
                $traveller_id = $request->traveller_id??'';
                if($traveller_id) {
                    $traveller = OrderTraveller::find($traveller_id);
                    if($traveller) {
                        $title = $traveller->title??'';
                        $first_name = $traveller->first_name??'';
                        $last_name = $traveller->last_name??'';
                        $print_name = $title.' '.$first_name.' '.$last_name;
                    }
                }
                // prd($print_name);
                $params['print_name'] = $print_name;
                $pdf = PDF::loadView("emails._flight_booking_pdf", $params);
                $path = 'temp/';
                $pdf_name = $order->order_no.((!empty($params['print_name']))?'-'.$params['print_name']:'').'.pdf';
                return $pdf->download($pdf_name);
            } else {
                $data = array(
                    'name' =>  $order->name??'',
                    'phone' =>  $phone,
                    'email' =>  $order->email??'',
                );
                $data['order_id'] = $order_id;

                $params = [];
                $params['order'] = $order;
                if($order->booking_details) {
                    $params['booking_details'] = json_decode($order->booking_details, true);
                }
                $params['width_percent'] = '100%';
                $params['width_px'] = '100%';
                $params['print_flight'] = true;
                $params['print_route'] = 'admin.voucher.flight_voucher_pdf';
                $data['itineraries'] = view("emails._flight_booking_email", $params)->render();                    
            }

            $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Flight Voucher')->get();

            return view("admin.vouchers.flight_voucher_view", $data);
        } else {
            abort(404);
        }
    } else {
        abort(404);
    }
}
//==========
}