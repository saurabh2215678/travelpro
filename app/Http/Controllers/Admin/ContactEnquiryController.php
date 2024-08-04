<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactEnquiry;
use App\Models\CustomizePackage;
use App\Models\RequestDetail;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Package;
use App\Helpers\CustomHelper;
use App\Models\CustomerReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class ContactEnquiryController extends Controller
{
    private $limit;

    public function __construct()
    {
        $this->limit = 20;
    }

    //===========Contact-Us=============

    /*public function index(Request $request)
    {

        $data = [];

        $limit = $this->limit;
        $name = (isset($request->name)) ? $request->name:"";
        $s_query = ContactEnquiry::orderBy('id', 'desc');
        if(!empty($name)){
            $s_query->where("name", "like", "%" . $name . "%");
        }

        $enquiries = $s_query->paginate($limit);
        $data['enquiries'] = $enquiries;     
        $data['limit'] = $limit;
        return view('admin.contact_enquiries.index', $data);

    }

    public function view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $enquiry = "";
        $title = "Enquiry";

        if (is_numeric($id) && $id > 0) {
            $enquiry = ContactEnquiry::where("id", $id)->first();
            //prd($enquiry);
            $title = "Enquiry";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["enquiry"] = $enquiry;
        $data["id"] = $id;
        return view("admin.contact_enquiries.view", $data);
    }



    public function delete(Request $request)
    {   
        $id = isset($request->id)?$request->id:0;
        //prd($id);
        if(is_numeric($id) && $id > 0){
        $model = ContactEnquiry::find($id);
        $model->delete();
        return back()->with('alert-success', 'Enquiry deleted successfully.');
        }

        else{
            return back()->with('alert-danger', 'The Enquiry cannot be Deleted, please try again or contact the administrator.');
        }
       
    }*/

      //============Customize-Package-Trip=============
    /*public function customize_this_trip(Request $request)
    {

        $data = [];
        $limit = $this->limit;

        $packageSearch = (isset($request->package_id))?$request->package_id:'';
       
        $customize_query = CustomizePackage::orderBy('id', 'desc');
        if($packageSearch){

        $customize_query->whereHas('customizePackage', function($query) use ($packageSearch) {
            $query->where("title", "like", "%" . $packageSearch . "%");
         }); 

        }

        $customize_packages = $customize_query->paginate($limit);
        // prd($customize_packages);
        $data['customize_packages'] = $customize_packages;     
        $data['limit'] = $limit;
        return view('admin.customize_packages.index', $data);

    }

    public function customize_this_trip_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $customize_package = "";
        $title = "Customize Packages";

        if (is_numeric($id) && $id > 0) {
            $customize_package = CustomizePackage::where("id", $id)->first();
            //prd($enquiry);
            $title = "Customize Packages";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["customize_package"] = $customize_package;
        $data["id"] = $id;
        return view("admin.customize_packages.view", $data);
    }

    public function customize_this_trip_delete(Request $request)
    {   
        $id = isset($request->id)?$request->id:0;
        //prd($id);
        if(is_numeric($id) && $id > 0){
        $model = CustomizePackage::find($id);
        $model->delete();
        return back()->with('alert-success', 'Customize Package deleted successfully.');
        }

        else{
            return back()->with('alert-danger', 'The Customize Package cannot be Deleted, please try again or contact the administrator.');
        }
       
    }*/
      //===========Request-Details============
    /*public function request_details(Request $request)
    {
        $data = [];
        $limit = $this->limit;
        
        $packageSearch = (isset($request->package_id)) ? $request->package_id:'';
        $form_type = (isset($request->form_type)) ? $request->form_type:'';
        $request_query = RequestDetail::orderBy('id', 'desc');

        if($packageSearch){

        $request_query->whereHas('requestitineraryPackage', function($query) use ($packageSearch) {
            $query->where("title", "like", "%" . $packageSearch . "%");
            });
        }
        if (!empty($form_type)) {
            $request_query->where('form_type',$form_type);
        }
        $request_details = $request_query->paginate($limit);
        //prd($request_details);
        $data['request_details'] = $request_details;     
        $data['limit'] = $limit;
        return view('admin.request_details.index', $data);

    }

    public function request_detail_view(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $request_detail = "";
        $title = "Request Details";

        if (is_numeric($id) && $id > 0) {
            $request_detail = RequestDetail::where("id", $id)->first();
            //prd($request_detail);
            $title = "Request Details";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["request_detail"] = $request_detail;
        $data["id"] = $id;
        return view("admin.request_details.view", $data);
    }



    public function request_detail_delete(Request $request)
    {   
        $id = isset($request->id)?$request->id:0;
        //prd($id);
        if(is_numeric($id) && $id > 0){
        $model = RequestDetail::find($id);
        $model->delete();
        return back()->with('alert-success', 'Request Detail deleted successfully.');
        }

        else{
            return back()->with('alert-danger', 'The Request Detail cannot be Deleted, please try again or contact the administrator.');
        }
       
    }*/
     //===========Booking-Enquiry=============
    /*public function bookingEnquiry(Request $request)
    {
        $data = [];
        $limit = $this->limit;

        $packageSearch = (isset($request->package_id)) ? $request->package_id:'';
        $booking_query = Booking::orderBy('id', 'desc');

        if($packageSearch){
          $booking_query->whereHas('bookingPackage',function($query) use ($packageSearch){
            $query->where("title", "like", "%" . $packageSearch . "%");
          });
        }
        $bookings = $booking_query->paginate($limit);
        // prd($bookings);
        $data['bookings'] = $bookings;     
        $data['limit'] = $limit;
        return view('admin.book_now.index', $data);

    }

    public function bookingView(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $booking = "";
        $title = "Booking Enquiry Details";

        if (is_numeric($id) && $id > 0) {
            $booking = Booking::where("id", $id)->first();
            //prd($booking);
            $title = "Booking Enquiry Details";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["booking"] = $booking;
        $data["id"] = $id;
        return view("admin.book_now.view", $data);
    }



    public function bookingDelete(Request $request)
    {   
        $id = isset($request->id)?$request->id:0;
        //prd($id);
        if(is_numeric($id) && $id > 0){
        $model = Booking::find($id);
        $model->delete();
        return back()->with('alert-success', 'Booking Enquiry deleted successfully.');
        }

        else{
            return back()->with('alert-danger', 'The Booking Enquiry cannot be Deleted, please try again or contact the administrator.');
        }
       
    }*/

    //===========Booking-Order-Detail-Enquiry=============
    /*
    public function bookingOrderEnquiry(Request $request)
    {
        $data = [];
        $limit = $this->limit;

        $packageSearch = (isset($request->package_id)) ? $request->package_id:'';
        $order_query = Order::orderBy('id', 'desc');

        if($packageSearch){
          $order_query->whereHas('Package',function($query) use ($packageSearch){
            $query->where("title", "like", "%" . $packageSearch . "%");
          });
        }
        $orders = $order_query->paginate($limit);
        $data['orders'] = $orders;     
        $data['limit'] = $limit;
        return view('admin.order_details.index', $data);
    }

    public function orderView(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $order = "";
        $title = "Order Details";

        if (is_numeric($id) && $id > 0) {
            $order = Order::where("id", $id)->first();
            //prd($order->all());
            $title = "Order Details";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["order"] = $order;
        $data["id"] = $id;
        return view("admin.order_details.view", $data);
    }

    public function orderDelete(Request $request)
    {   
        $id = isset($request->id)?$request->id:0;
        //prd($id);
        if(is_numeric($id) && $id > 0){
        $model = Order::find($id);
        $model->delete();
        return back()->with('alert-success', 'Order Details deleted successfully.');
        }

        else{
            return back()->with('alert-danger', 'Order Details cannot be Deleted, please try again or contact the administrator.');
        }
       
    }*/
    //=============Customer-Review============
    public function customerReview(Request $request)
    {
        $data = [];
        $limit = $this->limit;

        $customerName = (isset($request->your_name)) ? $request->your_name:"";
        $customer = CustomerReview::orderBy('id', 'desc');
        
        if(!empty($customerName)){
            $customer->where("your_name", "like", "%". $customerName . "%");
        }
        $customers = $customer->paginate($limit);
        //prd($customers->toArray());
        $data['customers'] = $customers;     
        $data['limit'] = $limit;
        return view('admin.customer_reviews.index', $data);

    }


    public function add(Request $request)
    {

        $id = isset($request->id) ? $request->id : 0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $customer_review = [];
        $title = "Add Customer Review";


        if (is_numeric($id) && $id > 0) {
            $customer_review = CustomerReview::find($id);
            $title =
            "Edit Customer Review (" . $customer_review->your_name . ")";
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            $rules = [];
            $rules["trip_name_duration"] = "required";
            $rules["local_guide_name"] = "required";
            $rules["driver_name"] = "required";
            $rules["your_name"] = "required";
            $rules["email"] = "required";
            $rules["address"] = "required";
            $this->validate($request, $rules);

            $saveHotelArr = [];
            $saveHotelArr['hotel_name_1'] = $request->hotel_name_1 ?? '';
            $saveHotelArr['comfort_1'] = $request->comfort_1 ?? 0;
            $saveHotelArr['services_1'] = $request->services_1 ?? 0;
            $saveHotelArr['food_1'] = $request->food_1 ?? 0;
            $saveHotelArr['accommodation_comments_1'] = $request->accommodation_comments_1 ?? 0;
            
            $saveHotelArr['hotel_name_2'] = $request->hotel_name_2 ?? '';
            $saveHotelArr['comfort_2'] = $request->comfort_2 ?? 0;
            $saveHotelArr['services_2'] = $request->services_2 ?? 0;
            $saveHotelArr['food_2'] = $request->food_2 ?? 0;
            $saveHotelArr['accommodation_comments_2'] = $request->accommodation_comments_2 ?? 0;
            
            $saveHotelArr['hotel_name_3'] = $request->hotel_name_3 ?? '';
            $saveHotelArr['comfort_3'] = $request->comfort_3 ?? 0;
            $saveHotelArr['services_3'] = $request->services_3 ?? 0;
            $saveHotelArr['food_3'] = $request->food_3 ?? 0;
            $saveHotelArr['accommodation_comments_3'] = $request->accommodation_comments_3 ?? 0;
            
            $saveHotelArr['hotel_name_4'] = $request->hotel_name_4 ?? '';
            $saveHotelArr['comfort_4'] = $request->comfort_4 ?? 0;
            $saveHotelArr['services_4'] = $request->services_4 ?? 0;
            $saveHotelArr['food_4'] = $request->food_4 ?? 0;
            $saveHotelArr['accommodation_comments_4'] = $request->accommodation_comments_4 ?? 0;

            $req_data = [];
            $req_data = $request->except(["_token", "back_url", "id", 'hotel_name_1', 'comfort_1', 'services_1', 'food_1', 'accommodation_comments_1', 'hotel_name_2', 'comfort_2', 'services_2', 'food_2', 'accommodation_comments_2', 'hotel_name_3', 'comfort_3', 'services_3', 'food_3', 'accommodation_comments_3', 'hotel_name_4', 'comfort_4', 'services_4', 'food_4', 'accommodation_comments_4']);
            // $req_data['hotel_data'] = json_encode($saveHotelArr,true);
            $req_data['hotel_data'] = json_encode($saveHotelArr);
                    
            if (!empty($customer_review) && $customer_review->id == $id) {
                $isSaved = CustomerReview::where("id",$customer_review->id)->update($req_data);
                $customer_review_id = $customer_review->id;
                $msg = "Customer Review has been updated successfully.";
            } else {
                $isSaved = CustomerReview::create($req_data);
                $customer_review_id = $isSaved->id;
                $msg = "Customer Review has been added successfully.";
            }

            if ($isSaved) {
                 //=============Activity Logs=====================
                $new_data = DB::table('customer_reviews')->where('id',$customer_review_id)->first();
                $module_desc =  !empty($new_data->your_name)?$new_data->your_name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id= $customer_review_id;
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Customer Review';
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
                cache()->forget("customer_reviews");
                return redirect(route("admin.customer_reviews.index"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Customer Review could be added, please try again or contact the administrator.");
            }
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["customer_review"] = $customer_review;
        $data["id"] = $id;
        return view("admin.customer_reviews.form", $data);
    }


    public function customerView(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        $customer = "";
        $title = "Customer Review Enquiry Details";

        if (is_numeric($id) && $id > 0) {
            $customer = CustomerReview::where("id", $id)->first();
            //prd($customer->toArray());
            $title = "Customer Review Enquiry Details";
        }

        $data = [];
        $data["page_heading"] = $title;
        $data["customer"] = $customer;
        $data["id"] = $id;
        return view("admin.customer_reviews.view", $data);
    }


    public function customerDelete(Request $request)
    {   
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = isset($request->id)?$request->id:0;
        if(is_numeric($id) && $id > 0){
        $model = CustomerReview::find($id);

        $new_data = DB::table('customer_reviews')->where('id',$id)->first();
        $module_desc =  !empty($new_data->your_name)?$new_data->your_name:'';
        $new_data =(array) $new_data;
        $new_data = json_encode($new_data);

        $model->delete();
        //=============Activity Logs=====================

        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'Customer Review';
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
        return back()->with('alert-success', 'Customer Review Enquiry deleted successfully.');
        }

        else{
            return back()->with('alert-danger', 'The Customer Review Enquiry cannot be Deleted, please try again or contact the administrator.');
        }
       
    }

    /* end of controller */
}