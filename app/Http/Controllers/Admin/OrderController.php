<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderTraveller;
use App\Models\OrderPayments;
use App\Models\OrderServiceVoucher;
use App\Models\ActivityLog;
use App\Models\EmailTemplate;
use App\Models\Itenary;
use App\Models\OrderStatusHistory;
use App\Models\EnquiriesMaster;
use App\Models\PaymentGateway;
use App\Models\SmsTemplate;
use App\Models\UserWallet;
use App\Models\AirlineRouteInventory;
use App\Helpers\CustomHelper;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon as Carbon;
use Response;
use DB;
use Storage;
use Validator;
use PDF;
use File;

class OrderController extends Controller {

    private $limit;

    public function __construct() {
        $this->limit = 30;
    }

    public function index(Request $request) {

        // $rules = [];
        // $niceNames = [
        //     'range' => 'Range',
        //     'from' => 'From',
        //     'to' => 'To',
        // ];
        // $rules['from'] = 'required_if:range,custom';
        // $rules['to'] = 'required_if:range,custom';
        // $validation_msg = [];
        // $validation_msg['required'] = ':attribute is required.';
        // $this->validate($request, $rules, $validation_msg, $niceNames);

        $payment_status = $request->order??'confirmed';
        $inventory_id = $request->inventory_id??0;
        $data = [];
        $limit = $this->limit;

        $from_date = '';
        $to_date = '';

        if(!empty($payment_status)) {
            $query = Order::orderBy('id', 'desc');
            $range = $request->range;
            if(!empty($range) && $range != 'custom' && (empty($request->todays_orders) && empty($request->yesterdays_orders))) {
                $date_between_arr = CustomHelper::get_to_from_date($range);
                $from_date = $date_between_arr['from'];
                $to_date = $date_between_arr['to'];
            } else if(!empty($request->todays_orders)) {
                $from_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else if(!empty($request->yesterdays_orders)) {
                $from_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else {
                if(!empty($inventory_id)) {
                    $from = '';
                    $to = '';
                    if($request->from) {
                        $from = $request->from ?? date('d/m/Y') ;
                        $to = $request->to ?? date('d/m/Y') ;
                    }
                } else {
                    $from = $request->from ?? date('d/m/Y') ;
                    $to = $request->to ?? date('d/m/Y') ;
                }
                if($from && $to) {
                    $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                    $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                    $to_date = $to_date.' 23:59:59';
                } else if($from) {
                    $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                } else if($to) {
                    $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                    $to_date = $to_date.' 23:59:59';
                }
            }

            if($payment_status=='amendment_progress') {

            } else {
                if(!empty($from_date)) {
                    $query->whereDate('created_at','>=',$from_date);
                }
                if(!empty($to_date)) {
                    $query->whereDate('created_at','<=',$to_date);
                }
            }


            if($payment_status=='confirmed') {
                $status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
                $status_query->where(function($q1){
                    $q1->where('category','confirmed');
                });
                $status_arr = $status_query->get()->pluck('id')->toArray();
                $query->where('cancel_request',0);
                $query->whereIn('payment_status',[1,3]);
                $query->where(function($q1) use ($status_arr) {
                    $q1->where(function($q2) use ($status_arr) {
                        $q2->where('order_type','!=',3);
                        // $q2->whereIn('status',['Order Accepted','Voucher created']);
                        $q2->whereIn('orders_status',$status_arr);
                    });
                    $q1->orWhere(function($q2) {
                        $q2->where('order_type',3);
                        $q2->whereIn('status',['SUCCESS','PENDING','ON_HOLD','CANCELLED']);
                    });
                });
            } else if($payment_status=='pending') {
                $status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
                $status_query->where(function($q1){
                    $q1->where('category','processing');
                    $q1->orWhere('category','new');
                });
                $status_arr = $status_query->get()->pluck('id')->toArray();
                $query->where('cancel_request',0);
                $query->where('payment_status',1);
                $query->where(function($q1) use ($status_arr) {
                    $q1->where(function($q2) use ($status_arr) {
                        $q2->where('order_type','!=',3);
                        // $q2->whereNotIn('status',['Order Accepted','Voucher created']);
                        $q2->whereIn('orders_status',$status_arr);
                    });
                    $q1->orWhere(function($q2) {
                        $q2->where('order_type',3);
                        $q2->whereNotIn('status',['SUCCESS','PENDING','ON_HOLD','CANCELLED']);
                    });
                });
            } else if($payment_status=='amendment_progress') {
                $query->whereHas('orderAmendments',function($q1) use($from_date, $to_date){
                    $q1->where('status',0);
                    if(!empty($from_date)) {
                        $q1->whereDate('created_at','>=',$from_date);
                    }
                    if(!empty($to_date)) {
                        $q1->whereDate('created_at','<=',$to_date);
                    }
                });
            } else if($payment_status=='cancel_request') {
                $query->where('cancel_request',1);
                /*$status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
                $status_query->where(function($q1){
                    $q1->where('category','processing');
                });
                $status_arr = $status_query->get()->pluck('id')->toArray();
                $query->where(function($q1) use ($status_arr) {
                    $q1->where(function($q2) use ($status_arr) {
                        $q2->where('order_type','!=',3);
                        $q2->whereIn('orders_status',$status_arr);
                    });
                });*/
            } else {
                $query->where('cancel_request',0);
                // $query->where('payment_status','!=',1);
                $query->whereNotIn('payment_status',[1,3]);
            }

            if($request->flight_type=='offline') {
                $query->where('inventory_id','>',0);
            }
            if($request->package_id) {
                $packageSearch = $request->package_id;
                if(is_numeric($packageSearch)) {
                    $query->where('package_id',$packageSearch);
                } else {
                    $query->whereHas('Package',function($q1) use ($packageSearch){
                        $q1->where("title", "like", "%".$packageSearch."%");
                    });
                }
            }
            if($request->search) {
                $search = $request->search;
                $query->where(function($q1) use ($search){
                    $q1->where('package_name','like',"%$search%");
                    $q1->orwhere('booking_details','like',"%$search%");
                });
            }

            $user = auth()->user();
            $user_id = $user?$user->id:'';
            $is_vendor = $user?$user->is_vendor:'';

            if($is_vendor == 1){
                $query->where('vendor_id',$user_id);
            }

            if($request->order_id) {
                $query->where('order_no',$request->order_id);
            }
            if($request->pnr) {
                $pnr = $request->pnr;
                $query->where('booking_details','like',"%$pnr%");
            }
            if($request->order_type) {
                $query->where('order_type',$request->order_type);
            } else {
                $query->where('order_type','!=',7);
            }
            if($request->name) {
                $name = $request->name;
                $query->where('name','like',"%$name%");
            }
            if($request->email) {
                $query->where('email',$request->email);
            }
            if($request->phone) {
                $phone = $request->phone;
                $query->where('phone','like',"%$phone");
            }

            // if(empty($request->todays_orders) && empty($request->yesterdays_orders) && empty($request->all_orders)) {
                if($request->order_status && is_array($request->order_status)) {
                    $getIds = EnquiriesMaster::whereIn('category', $request->order_status)->select('id')->get();
                    $xArray=[];
                    foreach ($getIds as $key => $get_id) {
                        $xArray[] = $get_id->id;
                    }
                    if(in_array('new',$request->order_status)){
                        $query->where(function ($sub_query) use ($xArray) {
                            $sub_query->where('orders_status',0);
                            $sub_query->orWhereIn('orders_status', $xArray);
                        });
                    } else {
                        $query->where('orders_status','!=',0);
                        $query->WhereIn('orders_status', $xArray);
                    }
                }/* else {
                    $autoSelStatus = ['new','accepted'];
                    $getIds = EnquiriesMaster::whereIn('category', $autoSelStatus)->select('id')->get();
                    $xArray=[];
                    foreach ($getIds as $get_id) {
                        $xArray[]=$get_id->id;
                    }
                    $query->where(function ($sub_query) use ($xArray) {
                        $sub_query->whereNull('orders_status');
                        $sub_query->orWhereIn('orders_status', $xArray);
                    });
                        $query->where(function ($sub_query) use ($xArray) {
                            $sub_query->whereNull('orders_status');
                            $sub_query->orWhereIn('orders_status', $xArray);
                        });
                }*/
            // }

            if($inventory_id) {
                $query->where('inventory_id',$inventory_id);
            }
            $orders = $query->paginate($limit);
        } else {
            $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
            return redirect($ADMIN_ROUTE_NAME);
        }
        $data['orders'] = $orders;
        $data['limit'] = $limit;
        $data['payment_status'] = $payment_status;

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $search_data = $request->toArray();
        unset($search_data['order']);
        unset($search_data['order_type']);
        $data['search_data'] = $search_data;
        // prd($data['search_data']);
        return view('admin.orders.index', $data);
    }

    public function bulk_action(Request $request) {
        $method = $request->method();
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $is_saved = 0;
        if($method == 'POST') {
            $action = $request->action??'';
            $booking_id = $request->booking_id??'';
            if($action && $booking_id) {
                if(is_array($booking_id)) {
                    $booking_ids = $booking_id;
                } else {
                    $booking_ids = explode(',', $booking_id);
                }
                $orders = Order::whereIn('id',$booking_ids)->get();
                if($orders) {
                    if($action=='request_payment') {
                        foreach($orders as $order) {
                            $resp = Order::requestOrderPayment($order);
                            if($resp['success']) {
                                $response['success'] = true;
                            }
                        }
                        if($response['success']) {
                            $response['message'] = 'Payment request email has been sent successfully.';
                        }
                        return Response::json($response);
                    } else if($action=='print') {
                        $data['orders'] = $orders;
                        return view('admin.orders._order_print', $data);
                    } else {
                        $exportArr = [];
                        foreach($orders as $order) {
                            $booking_details = json_decode($order->booking_details,true);
                            $airline_name = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['fD']['aI']['name']??'';
                            $source = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['da']['city']??'';
                            $destination = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][count($booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'])-1]['aa']['city']??'';
                            $OrderTraveller = $order->OrderTraveller??[];
                            if($OrderTraveller) {
                                foreach($OrderTraveller as $traveller) {
                                    $status = 'Confirmed';
                                    if($order->status=='Cancelled') {
                                        $status = 'Cancelled';
                                    }

                                    $row_arr = [
                                        'Ref Number' => $order->order_no??'',
                                        'Date' => CustomHelper::DateFormat($order->created_at,'j F, Y')??'',
                                        'Airline' => $airline_name??'',
                                        'From' => $source??'',
                                        'To' => $destination??'',
                                        'Title' => $traveller->title??'',
                                        'First Name' => $traveller->first_name??'',
                                        'Last Name' => $traveller->last_name??'',
                                        'Date of Birth' => CustomHelper::DateFormat($traveller->dob,'j F, Y')??'',
                                        'Passenger Contact' => $order->phone??'',
                                        'PNR' => $traveller->pnrDetails??'',
                                        'E-Ticket' => $traveller->airline_ticket_no??'',
                                        'Agent Name' => $order->supplierInfo->company_name??'',
                                        'Contact No' => $order->supplierInfo->User->phone??'',
                                        'Status' => $status??'',
                                    ];
                                    $exportArr[] = $row_arr;
                                }
                            }
                        }
                        $fieldNames = array_keys($exportArr[0]);
                        if($action=='download') {
                            $fileName = 'ticket_list'.time().'.xlsx';
                            return Excel::download(new OrderExport($exportArr, $fieldNames), $fileName);
                        } else if($action=='email'){
                            $data['exportArr'] = $exportArr;
                            $emailData = view('admin.orders._order_email', $data)->render();

                            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                            $to_email = $request->email??'';
                            if($to_email) {
                                if(filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
                                    $REPLYTO = '';
                                    $cc_email = $ADMIN_EMAIL;
                                    $bcc_email = '';

                                    $email_subject = 'Flight Ticket Detail';
                                    $email_content = $emailData;

                                    $params = [];
                                    $params['to'] = $to_email;
                                    $params['reply_to'] = $REPLYTO;
                                    $params['cc'] = $cc_email;
                                    $params['bcc'] = $bcc_email;
                                    $params['subject'] = $email_subject;
                                    $params['email_content'] = $email_content;
                                    $params['module_name'] = 'Flight Ticket Detail';
                                    $params['record_id'] = 0;
                                    $is_mail = CustomHelper::sendCommonMail($params);
                                    $response['success'] = true;
                                    $response['message'] = 'Mail has been sent successfully';
                                } else {
                                    $response['message'] = 'Please enter valid email address.';
                                }
                            } else {
                                $response['message'] = 'Please enter email address.';
                            }
                            return Response::json($response);
                        }
                    }
                }
            }
        }
        if($response['success']) {
            $message = $response['message']??'';
            return back()->with("alert-success", $message);
        } else {
            $message = $response['message']??'';
            return back()->with("alert-danger", $message);
        }
    }

    public function refund(Request $request) {
        if ($request->order_id) {
            $order = Order::find($request->order_id);
            if($order) {
                $user_id = auth()->user()->id;
                $comments = $request->reason??'';
                $action = $request->action??'';
                $reason = 'Booking payment reversed.';
                if($action=='cancel_request_accept') {
                    $reason = "Cancel Request Accepted. \r\nReason: ".$comments??'';
                } else if($action=='order_cancel') {
                    $reason = "Order Cancelled. \r\nReason: ".$comments??'';
                }
                $is_saved = Order::refundOrderPayment($order->id,['reason' => $reason]);
                if($is_saved){
                    return back()->with('alert-success', 'Approval of refund amount has been submitted successfully');
                } else {
                    return back()->with('alert-danger', 'The refund amount could not be initiated, please try again or contact the administrator.');
                }
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function cancel_request_reject(Request $request) {
        if ($request->order_id) {
            $order = Order::find($request->order_id);
            if($order) {
                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;

                $order->cancel_request = 0;
                $order->save();

                $order_status_history = array(
                    'order_id' => $order->id,
                    'comments' => "Cancel Request Rejected. \r\nReason: ".$request->reason,
                    'orders_status' => CustomHelper::showEnquiryMaster($order->orders_status)??'',
                    'created_type' => 'backend',
                    'created_by' => $user_id,
                    'created_by_name' => $user_name,
                );
                $isSaved = OrderStatusHistory::create($order_status_history);

                if($isSaved) {

                    $order_id = $order->id;
                    $new_data = DB::table('orders')->where('id',$order_id)->first();

                    $new_data =(array) $new_data;
                    $new_data = json_encode($new_data);
                    $module_id= $order_id;
                    $params['user_id'] = $user_id;
                    $params['user_name'] = $user_name;
                    $params['module'] = 'Order Reject';
                    $params['module_desc'] = '';
                    $params['module_id'] = $module_id;
                    $params['sub_module'] = '';
                    $params['sub_module_id'] = '';
                    $params['sub_sub_module'] = "";
                    $params['sub_sub_module_id'] = 0;
                    $params['data_after_action'] = $new_data;
                    $params['action'] = "Cancel Reject";

                    CustomHelper::RecordActivityLog($params);

                    return back()->with('alert-success', ' Order has been rejected successfully');
                } else {
                    return back()->with('alert-danger','The Order could not be rejected, please try again or contact the administrator.');

                }                
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function view_20july2023(Request $request) {
        if ($request->order_id) {
            $order = Order::find($request->order_id);
            $show_cancel = 0;
            $before_date = '';
            if($order->order_type == 1){
                $before_days = CustomHelper::WebsiteSettings('PACKAGE_BOOKING_CANCEL_HOURS')??0;
                
                $date = date('Y-m-d h:i A', strtotime($order->trip_date));

                $before_date = date('Y-m-d h:i A',strtotime("$date -$before_days hour")); 
            }

             if($order->order_type == 6){
                $before_days = CustomHelper::WebsiteSettings('ACTIVITY_BOOKING_CANCEL_HOURS')??0;

                $date = date('Y-m-d h:i A', strtotime($order->trip_date));

                $before_date = date('Y-m-d h:i A',strtotime("$date -$before_days hour"));
            }
             if($order->order_type == 5){
                $before_days = CustomHelper::WebsiteSettings('HOTEL_BOOKING_CANCEL_HOURS')??0;
                $date = date('Y-m-d h:i A', strtotime($order->trip_date));

                $before_date = date('Y-m-d h:i A',strtotime("$date -$before_days hour"));
            }
            if($order->order_type == 4){
                $before_days = CustomHelper::WebsiteSettings('CAB_BOOKING_CANCEL_HOURS')??0;
                //$before_days = 2;
                $date = date('Y-m-d h:i A', strtotime($order->trip_date));

                //$before_date = date('Y-m-d',strtotime("$date -2 hour"));
                $before_date = date('Y-m-d h:i A',strtotime("$date -$before_days hour"));
                //prd($before_date);
            }
            $current_date = date('Y-m-d h:i A'); 
            

            $trip_date_show = strtotime($before_date);
            $current_date_show = strtotime($current_date);

            if($current_date_show <= $trip_date_show){
               $show_cancel = 1;
            }
            $data = [];
            $data["page_heading"] = "Order Details";
            $data["show_cancel"] = $show_cancel;
            $data["order"] = $order;

            $order_type = $order->order_type??'1';
            if($order_type == 3) {
                $params = [];
                $params['order'] = $order;
                if($order->booking_details) {
                    $params['booking_details'] = json_decode($order->booking_details, true);
                    // prd($data['booking_details']);
                    $params['width_percent'] = '100%';
                    $params['width_px'] = 830;
                }
                $data['itineraries'] = view("emails._flight_booking_email", $params)->render();
            }elseif($order_type == 4) {
                $params = [];
                $params['order'] = $order;
                if($order->booking_details) {
                    $params['booking_details'] = json_decode($order->booking_details, true);
                    // prd($data['booking_details']);
                }
                $data['cab_itineraries'] = view("emails._cab_booking_email", $params)->render();

                // prd($data['cab_itineraries']);

            }

            return view("admin.orders.view", $data);
        } else {
            abort(404);
        }
    }


    public function details(Request $request) {
        $order_no = $request->order_id??'';
        if (!empty($order_no)) {
            $order = Order::where('order_no',$order_no)->first();
            if($order) {
                $data = [];
                $data["page_heading"] = "Order Details";
                $data["order"] = $order;

                $order_type = $order->order_type??'1';
                if($order_type == 3) {
                    return self::flight_details($request, $order);
                } else {
                    if($order_type == 4) {
                        $params = [];
                        $params['order'] = $order;
                        if($order->booking_details) {
                            $params['booking_details'] = json_decode($order->booking_details, true);
                        }
                        $data['cab_itineraries'] = view("emails._cab_booking_email", $params)->render();
                    }

                    $show_cancel = 0;
                    $before_date = '';
                    // prd($order->order_type);
                    if($order->order_type == 1){
                        $before_days = CustomHelper::WebsiteSettings('PACKAGE_BOOKING_CANCEL_HOURS')??0;
                        $date = date('Y-m-d H:i:s', strtotime($order->trip_date));
                        $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour")); 
                    } else if($order->order_type == 6) {
                        $before_days = CustomHelper::WebsiteSettings('ACTIVITY_BOOKING_CANCEL_HOURS')??0;
                        $date = date('Y-m-d H:i:s', strtotime($order->trip_date));
                        $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour"));
                    } else if($order->order_type == 5) {
                        $before_days = CustomHelper::WebsiteSettings('HOTEL_BOOKING_CANCEL_HOURS')??0;
                        $date = date('Y-m-d H:i:s', strtotime($order->trip_date));
                        $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour"));
                    } else if($order->order_type == 4) {
                        $before_days = CustomHelper::WebsiteSettings('CAB_BOOKING_CANCEL_HOURS')??0;
                        $date = date('Y-m-d H:i:s', strtotime($order->trip_date));
                        $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour"));
                    } else if($order->order_type == 8) {

                        $before_days = CustomHelper::WebsiteSettings('RENTAL_BOOKING_CANCEL_HOURS')??0;

                        $booking_details = $order->booking_details ?? '';
                        $booking_details_arr = json_decode($booking_details);
                        $date = CustomHelper::DateFormat($booking_details_arr->pickupDate,'Y-m-d')??'';

                        // $date = date('Y-m-d H:i:s', strtotime($trip_date));
                        $before_date = date('Y-m-d H:i:s',strtotime("$date -$before_days hour"));
                    }
                    $current_date = date('Y-m-d H:i:s');
                    $trip_date_show = strtotime($before_date);
                    $current_date_show = strtotime($current_date);
                    if($current_date_show <= $trip_date_show) {
                        $show_cancel = 1;
                    }
                    // prd($show_cancel);
                    $data['show_cancel'] = $show_cancel;

                    // $data['email_log'] = ActivityLog::where('module_id',$order->id)->where('module','Flight Voucher')->get();

                    $order_payments = OrderPayments::where('order_id',$order->id)->orderBy('id','asc')->get();
                    $data["order_payments"] = $order_payments;
                
                    $order_status_histories = OrderStatusHistory::where('order_id',$order->id)->orderBy('id','asc')->get();
                    // prd($order_status_histories->toArray());
                    $data['order_status_histories'] = $order_status_histories;

                    return view("admin.orders.view-details", $data);
                }
            }
        } else {
            abort(404);
        }
    }

    public function cab_driver(Request $request) {
        $order_id = $request->order_id??'';
        if($order_id){
            $order = Order::find($order_id);
            if($order) {
                $result = [];
                $data = [];
                $booking_details = json_decode($order->booking_details,true);
                if($request->method() == "POST" || $request->method() == "post") {

                    $rules = [];
                    $validation_msg = [];
                    $rules['vehicle_type'] = 'required';
                    $rules['vehicle_no'] = 'required';
                    $rules['driver_name'] = 'required';
                    $rules['mobile_no'] = 'required|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10';

                    $validator = Validator::make($request->all(), $rules, $validation_msg);
                    if ($validator->fails()) {
                        return Response::json(array(
                            'success' => false,
                            'errors' => $validator->getMessageBag()->toArray()
                        ), 400);
                    } else {
                    //   $this->validate($request, $rules, $validation_msg);
                        $driver_data = array(
                            'vehicle_type' =>  $request->vehicle_type,
                            'vehicle_no'  => $request->vehicle_no,
                            'driver_name'  => $request->driver_name,
                            'mobile_no'  => $request->mobile_no,
                        );
                        $booking_details['driver_details'] = $driver_data;
                        $order->booking_details = json_encode($booking_details);
                      $is_saved = $order->save();
                    if($is_saved){

                        //=============Activity Logs=====================

                        $user_id = auth()->user()->id;
                        $user_name = auth()->user()->name;
                        $new_data = DB::table('orders')->where('id',$order_id)->first();

                        // Driver Name
                        $booking_details =  json_decode($new_data->booking_details)??'';
                        $driver_details  = $booking_details->driver_details??'';
                        $module_desc = $driver_details->driver_name??'';

                        $new_data =(array) $new_data;
                        $new_data = json_encode($new_data);
                        $module_id= $order_id;
                        $params['user_id'] = $user_id;
                        $params['user_name'] = $user_name;
                        $params['module'] = 'Order Driver Details';
                        $params['module_desc'] = $module_desc;
                        $params['module_id'] = $module_id;
                        $params['sub_module'] = '';
                        $params['sub_module_id'] = '';
                        $params['sub_sub_module'] = "";
                        $params['sub_sub_module_id'] = 0;
                        $params['data_after_action'] = $new_data;
                        $params['action'] = "Update";
                        CustomHelper::RecordActivityLog($params);

                        //=============Activity Logs=====================
                    }
                    $result['success'] = true;
                    $result['msg'] = '<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Driver details saved successfully.</div></div>';
                    return response()->json($result);
                  }
                }
                $driver_details  = $booking_details['driver_details'] ?? [];
                $data['order_id'] = $order->id;
                $data['email'] = $order->email;
                $data['driver_details'] = $driver_details;
                $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Driver Details Sent')->get();
                return view("admin.orders.cab_driver_view", $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

public function cab_driver_mail(Request $request) {
        if($request->order_id){

            $order = Order::find($request->order_id);
            $result = [];
            $data = [];
            $booking_details = json_decode($order->booking_details,true);
            $order_id = $request->order_id ?? '';
            $driver_details  = $booking_details['driver_details'] ?? [];
            $origin  = $booking_details['origin'] ?? '';
            $pickup_address  = $booking_details['pickup_address'] ?? '';
            $trip_date  = $booking_details['trip_date'] ?? '';

            $trip_date_show = '';
            $trip_time_show = '';
            if($trip_date) {
                $trip_date_show = date('d M', strtotime($trip_date));
                $trip_time_show = date('h:i A', strtotime($trip_date));
            } else {
                $trip_date_show = date('d M', strtotime($order->created_at));
                $trip_time_show = date('h:i A', strtotime($order->created_at));
            }


            $drop_address = $booking_details['drop_address'] ?? '';
            $destination = $booking_details['destination'] ?? '';
            $drop_destination_address = $drop_address."( ".$destination." )";
            $vehicle_no = $driver_details['vehicle_no'] ?? '';
            $vehicle_type = $driver_details['vehicle_type'] ?? '';
            $driver_name = $driver_details['driver_name'] ?? '';
            $mobile_no = $driver_details['mobile_no'] ?? '';

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

            $email_params = array(
                '{payer_name}' => $order->name,
                '{phone}' => $order->phone,
                '{vehicle_no}' => $vehicle_no,
                '{vehicle_type}' => $vehicle_type,
                '{driver_name}' => $driver_name,
                '{driver_mobile_no}' => $mobile_no,
                '{header}' => $common_logo,
                '{contact_details}' => $contact_details??'',
                '{date}' => CustomHelper::DateFormat($order->trip_date, 'd M Y', 'Y-m-d'),
            );

            $subject_params = array();

            $sms_template_slug = 'cab-driver-details';
            $template_slug = 'driver-detail';
            $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
            $sms_content_data = SmsTemplate::where('slug', $sms_template_slug)->where('status', 1)->first();
                // $content = "Cab Driver Details : Driver ".$driver_name." Mob:".$mobile_no." Vehicle No :".$vehicle_no.' '.$vehicle_type."; Allocated for Pickup: ".$origin." from ".$pickup_address." Time:".$trip_time_show." Date:".$trip_date_show."; Drop: ".$drop_destination_address." on ".$trip_date_show."  - overlandescape.com";

            $sms_params = array(
                '{#var1#}' => $driver_name,
                '{#var2#}' => $mobile_no,
                '{#var3#}' => $vehicle_no.' '.$vehicle_type,
                '{#var4#}' => $origin,
                '{#var5#}' => $pickup_address,
                '{#var6#}' => $trip_time_show,
                '{#var7#}' => $trip_date_show,
                '{#var8#}' => $drop_destination_address,
                '{#var9#}' => $trip_date_show,
            );

            $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
            $email_content = strtr($email_content, $email_params);
            $sms_content = isset($sms_content_data->content) ? $sms_content_data->content : '';
            $content = strtr($sms_content, $sms_params);

            $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';
                // $subject_params = isset($subject_params) ? $subject_params : [];
            $email_subject = strtr($email_subject, $subject_params);
            $email_type = isset($email_content_data->email_type) ? $email_content_data->email_type : '';
            $email_bcc_admin = isset($email_content_data->bcc_admin) ? $email_content_data->bcc_admin : 0;

            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
            $to_email = $order->email;
            $REPLYTO = '';
            $cc_email = '';
            $bcc_email = '';
            if($email_type == 'admin'){
                $to_email = $ADMIN_EMAIL;
            }
            if($email_bcc_admin == 1){
                $bcc_email = $ADMIN_EMAIL;
            }

            if(isset($sms_content_data) && !empty($sms_content_data)) {
                $content = urlencode($content);
                $params  = [];
                $params['phone'] = $mobile_no ?? '';
                $params['content'] = $content;
                $params['template_id'] = $sms_content_data->template_id ?? '';
                $is_sms_sent = CustomHelper::send_sms($params);
            }

            if(isset($email_content_data) && !empty($email_content_data)) {
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


                //Send email log
                 //=============Activity Logs=====================

            $user_id = auth()->user()->id;
            $user_name = auth()->user()->name;
            $new_data = DB::table('orders')->where('id',$order_id)->first();
            $module_desc =  'Cab';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $module_id= $order_id;
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Driver Details Sent';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = '';
            $params['sub_module_id'] = '';
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = "Email Sent";

            CustomHelper::RecordActivityLog($params);

                //=============Activity Logs=====================

            // $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
            // return redirect($ADMIN_ROUTE_NAME."/orders/cab_driver/".$order_id);
            $result['success'] = true;
            $result['msg'] = '<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Driver details sent to customer email / SMS</div></div>';
            return response()->json($result);

        } else {
            abort(404);
        }
    }


    public function view_payments(Request $request) {
        if ($request->order_id) {
            $order_payments = OrderPayments::where('order_id',$request->order_id)->orderBy('id','asc')->get();
            $order_detail = Order::find($request->order_id);
            $data = [];
            $data["page_heading"] = "Order Details";
            $data["order_detail"] = $order_detail;
            $data["order_payments"] = $order_payments;
            return view("admin.orders.view_payments", $data);
        } else {
            abort(404);
        }
    }


    public function service_voucher(Request $request) {

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
                'hotel_data' => json_encode($hotel_data),
                'flight_data' => json_encode($flight_data),
                'package_data' => json_encode($package_data),
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
            }
            if($is_saved){

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
        if($OrderServiceVoucher){
        $packages_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];
        $title = $OrderServiceVoucher->title ?? '';
        $destination = $packages_data->destination ?? '';
        $duration = $packages_data->duration ?? '';
        $trip_date = $packages_data->trip_date ?? '';
        $package_charges = $packages_data->package_charges ?? '';
        $paid_amount = $packages_data->paid_amount ?? '';
        $address = $packages_data->address ?? '';

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
       $data['package_charges'] = $package_charges;
       $data['paid_amount'] = $paid_amount;
       $data['due_amount'] = (int)$package_charges -(int)$paid_amount ;

       return view("admin.orders.service_voucher", $data);
    }

  public function service_voucher_view(Request $request) {

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
        $duration = $package_data->duration ?? '';
        $destination = $package_data->destination ?? '';
        $package_charge = $package_data->package_charges ?? '';
        $paid_amount = $package_data->paid_amount ?? '';
        $due_amount = $package_data->due_amount ?? '';
        $address = $package_data->address ?? '';

           // prd($req_data);
           $hotel_data_str = $OrderServiceVoucher->hotel_data ? $OrderServiceVoucher->hotel_data : '';
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
            // prd($locations_arr);
            $itineraries = Itenary::where('package_id',$package_id)->groupBy('location_id')->get();
                if(empty($locations_arr)){
                   foreach ($itineraries as $key => $itinerary_row) {
                    $locations_arr[] = $itinerary_row->location_id;
                }
            }
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
               'trip_date' => $trip_date,
               'duration' => $duration??'',
               'address' => $address??'',
               'destination' => $destination??'',
               'package_charge' => $package_charge??'',
               'name' =>  $OrderServiceVoucher->name??'',
               'phone' =>  $OrderServiceVoucher->phone??'',
               'email' =>  $OrderServiceVoucher->email??'',
               'paid_amount' =>  $paid_amount??'',
               'due_amount' =>  $due_amount??'',
           );

            $data['order_id'] = $order_id;

           $data['locations_arr'] = $locations_arr;
           $data['hotels_arr'] = $hotels_arr;
           $data['dates_arr'] = $dates_arr;
           $data['nights_data'] = $nights_data;
           $data['rooms_data'] = $rooms_data;
           $data['meals_data'] = $meals_data;
           $data['OrderServiceVoucher'] = $OrderServiceVoucher;
           $data['selected_location'] = $locations_arr;
           $data['itineraries'] = $itineraries;

           $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Package Voucher')->get();
           return view("admin.orders.service_voucher_view", $data);

           }else{

            abort(404);
           }
       }else{

            abort(404);
       }

  }

  public function transactions(Request $request) {
    $data = [];
    $limit = $this->limit;
    $order_type = $request->order_type ?? '';
    //$method = $request->method ?? '';

    if (!empty($request->from)) {
        $date1 = strtr($request->from, '/', '-');
        $from_date = date('Y-m-d', strtotime($date1));
    }
    if (!empty($request->to)) {
        $date2 = strtr($request->to, '/', '-');
        $to_date = date('Y-m-d', strtotime($date2));
    }
    $query = OrderPayments::with('orderData')->orderBy('id', 'desc');
    if($request->order_id) {
        $query->where('order_no','like',"%$request->order_id%");
    }
    if($request->order_type) {
       $query->whereHas('orderData',function($q1) use($order_type){
          $q1->where('order_type',$order_type);
      });
    }
    if(isset($request->payment_status)) {
        $query->where('pg_payment_status',$request->payment_status);
    }
    if(isset($request->payment_method)) {
        $query->where('payment_method',$request->payment_method);
    }
    /*if(isset($method)) {
        $query->whereHas('orderData',function($q1) use($method){
          $q1->where('method',$method);
      });
    }*/
    if (!empty($from_date)) {
        $query->where(DB::raw('DATE(created_at)'), '>=', $from_date);
    }
    if (!empty($to_date)) {
        $query->where(DB::raw('DATE(created_at)'), '<=', $to_date);
    }
    $orders = $query->paginate($limit);
    $data['order_payments'] = $orders;
    $data['payment_gateways'] = PaymentGateway::where(['status'=>1,'show'=> 1])->get();
    //$data['payment_methods'] = OrderPayments::select('payment_method')->groupBy('payment_method')->get();

    $data['limit'] = $limit;
    return view('admin.orders.payments_index', $data);
}

    public function transactions_view(Request $request) {
        $data = [];
        $order_id = $request->order_id??'';
        if($order_id){
            $transactions_view = OrderPayments::find($order_id);
            $data['transactions_view'] = $transactions_view;
        }
        return view('admin.orders.transactions_view', $data);
    }

    public function checkBookingStatus(Request $request) {
        $response = [];
        $response['success'] = false;
        $order_id = $request->order_id??'';
        if($order_id) {
            $response = Order::checkBookingStatus($order_id);
        }
        return Response::json($response);
    }

    public function flight_details($request, $order) {
        $data = [];
        if($order && $order->order_type == 3) {
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
            $order_no = $order->order_no;
            $phone = '';
            if($order->phone) {
                $phone_country = $order->phone_country ?? '91';
                $phone = '+'.$phone_country.'-'.$order->phone;
            }

            $action = $request->action??'';
            $method = $request->method();
            // prd($action);
            if($method == 'POST') {
                $response = [];
                $response['success'] = false;
                $response['message'] = '';
                if($action == 'cancellation') {
                    $nicknames = [];
                    $nicknames['passengers'] = 'Passengers';
                    $nicknames['reason'] = 'Reason';
                    $nicknames['other_reason'] = 'Other Reason';
                    $rules = [];
                    $rules['passengers'] = 'required';
                    $rules['reason'] = 'required';
                    $rules['other_reason'] = 'required_if:reason,other';
                    $validation_msg['required'] = ':attribute is required.';
                    $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
                    if ($validator->fails()) {
                        return Response::json(array(
                            'success' => false,
                            'errors' => $validator->getMessageBag()->toArray()
                        ), 400); // 400 being the HTTP code for an invalid request.
                    } else {
                        $flight_cancellation_reasons = config('custom.flight_cancellation_reasons');
                        $passengers = $request->passengers??[];
                        $reason = $request->reason??'';
                        $other_reason = $request->other_reason??'';
                        $remarks = '';
                        if($other_reason) {
                            $remarks = $other_reason;
                        } else if($reason) {
                            $remarks = $flight_cancellation_reasons[$reason]??'';
                        }
                        $params = [];
                        $params['remarks'] = $remarks;
                        $params['passengers'] = $passengers;
                        $resp = Order::cancelFlight($order_id,$params);
                        // prd($resp);
                        if($resp['success']) {
                            $response['success'] = true;
                            $response['message'] = $resp['message']??'';;
                        } else {
                            $response['message'] = $resp['message']??'';
                            $response['errors'] = $resp['errors']??[];
                        }
                        return Response::json($response, 200);
                    }
                } else if($action == 'sendmail') {
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
                        // $msg = "Mail sent successfully";
                        // return back()->with('alert-success', $msg);
                        $response['success'] = true;
                        $response['message'] = '<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Mail sent successfully</div></div>';
                    } else {
                        // $msg = "Error while sending email";
                        // return back()->with('alert-danger', $msg);
                        $response['success'] = false;
                        $response['message'] = '<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Error while sending email</div></div>';
                    }
                    return Response::json($response, 200);                    
                } else if($action == 'download_pdf') {
                    $params = [];
                    $params['order'] = $order;
                    if($order->booking_details) {
                        $params['booking_details'] = json_decode($order->booking_details, true);
                    }
                    $params['print_name'] = $request->name??'';
                    $pdf = PDF::loadView("emails._flight_booking_pdf", $params);
                    $path = 'temp/';
                    $pdf_name = $order->order_no.((!empty($params['print_name']))?'-'.$params['print_name']:'').'.pdf';
                    return $pdf->download($pdf_name);
                }
            }
            $data = array(
                'name' =>  $order->name??'',
                'phone' =>  $phone,
                'email' =>  $order->email??'',
            );
            $data['order'] = $order;
            $data['order_id'] = $order_id;
            $data['order_no'] = $order_no;
            $pendingOrderAmendments = $order->pendingOrderAmendments();

            $params = [];
            $params['order'] = $order;
            if($order->booking_details) {
                if($order->inventory_id) {
                    $OrderTraveller = $order->OrderTraveller??[];
                    if($OrderTraveller && isset($OrderTraveller[0])) {
                        $data['pnrDetails'] = $OrderTraveller[0]->pnrDetails;
                    }
                }
                $params['booking_details'] = json_decode($order->booking_details, true);
            }
            $params['width_percent'] = '100%';
            $params['width_px'] = '100%';
            $params['edit_passengers'] = true;
            if($order->status == 'PENDING' || $order->status == 'ON_HOLD' || $order->status == 'SUCCESS') {
                $params['cancel_flight'] = true;
                $params['edit_passengers'] = false;
            }
            $params['print_flight'] = true;
            $params['print_route'] = 'admin.voucher.flight_voucher_pdf';
            $params['pendingOrderAmendments'] = $pendingOrderAmendments;

            $data['itineraries'] = view("emails._flight_booking_email", $params)->render();
            // $data['email_log'] = ActivityLog::where('module_id',$order_id)->where('module','Flight Voucher')->get();

            $order_payments = OrderPayments::where('order_id',$order->id)->orderBy('id','asc')->get();
            $data["order_payments"] = $order_payments;

            $order_status_histories = OrderStatusHistory::where('order_id',$order->id)->orderBy('id','asc')->get();
                    // prd($order_status_histories->toArray());
            $data['order_status_histories'] = $order_status_histories;


            return view("admin.orders.flight_details", $data);
        } else {
            abort(404);
        }
    }

    public function cancelOfflineFlight(Request $request) {
        $response = [];
        $order_id = isset($request->order_id) ? $request->order_id : 0;
        $order = "";
        $user_id = auth()->user() ? auth()->user()->id :0;
        if (is_numeric($order_id) && $order_id > 0) {
            $order = Order::find($order_id);
            if($order && $order->inventory_id) { // && $order->status == 'NEW'
                $reason = $request->reason??'';
                $params = [];
                $params['wallet_comment'] = $reason;
                $resp = Order::refundOrderPayment($order_id,$params);
                if($resp) {
                    $response['success'] = true;
                    $response['message'] = ' Flight has been cancelled successfully';
                } else {
                    $response['success'] = false;
                    $response['message'] = ' Flight has been not been cancelled, please try again or contact the adminstrator!';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Invalid request';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Invalid request';
        }
        return response()->json($response);
    }

    public function changeFareOfflineFlight(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $order_id = isset($request->order_id) ? $request->order_id : 0;
        $order = "";
        $user_id = auth()->user()->id??0;
        $user_name = auth()->user()->name??'';
        $created_type = 'backend';
        if (is_numeric($order_id) && $order_id > 0) {
            $order = Order::find($order_id);
            if($order && $order->inventory_id) { // && $order->status == 'NEW'
                $old_adult_price = 0;
                $old_child_price = 0;
                $old_infant_price = 0;

                $adult_price = $request->adult_price??0;
                $child_price = $request->child_price??0;
                $infant_price = $request->infant_price??0;
                $booking_details = json_decode($order->booking_details, true);
                // prd($booking_details);
                $old_total_amount = $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['TF']??0;
                $sIs = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI']??[];
                if($sIs) {
                    $total_segments = count($sIs);
                    $adult_BF = $adult_price/$total_segments;
                    $child_BF = $child_price/$total_segments;
                    $infant_BF = $infant_price/$total_segments;
                    $newSIs = [];
                    foreach($sIs as $sI) {
                        $newSI = $sI;
                        $tIs = $sI['bI']['tI']??[];
                        // prd($tIs);
                        if($tIs) {
                            $newTIs = [];
                            foreach($tIs as $tI) {
                                $newTI = $tI;
                                $NF = $tI['fd']['fC']['NF']??0;
                                $BF = $tI['fd']['fC']['BF']??0;
                                $TAF = $tI['fd']['fC']['TAF']??0;
                                $TF = $tI['fd']['fC']['TF']??0;
                                $pt = $tI['pt']??'';
                                if($pt == 'ADULT') {
                                    $NF = $adult_BF;
                                    $BF = $adult_BF;
                                } else if($pt == 'CHILD') {
                                    $NF = $child_BF;
                                    $BF = $child_BF;
                                } else if($pt == 'INFANT') {
                                    $NF = $infant_BF;
                                    $BF = $infant_BF;
                                }
                                $newTI['fd']['fC']['NF'] = sprintf("%.2f",($NF+$TAF));
                                $newTI['fd']['fC']['BF'] = $BF;
                                $newTI['fd']['fC']['TAF'] = $TAF;
                                $newTI['fd']['fC']['TF'] = sprintf("%.2f",($BF+$TAF));
                                $newTIs[] = $newTI;
                            }
                            $newSI['bI']['tI'] = $newTIs;
                        }
                        $newSIs[] = $newSI;
                    }
                    $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'] = $newSIs;
                }

                $totalPriceLists = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList']??[];
                if($totalPriceLists) {
                    $newTotalPriceList = [];
                    foreach($totalPriceLists as $totalPriceList) {
                        $newTotalPriceListRow = $totalPriceList;
                        if(isset($totalPriceList['fd'])) {
                            foreach($totalPriceList['fd'] as $pt => $val) {
                                $newVal = $val;
                                $NF = $val['fC']['NF']??0;
                                $BF = $val['fC']['BF']??0;
                                $TAF = $val['fC']['TAF']??0;
                                $TF = $val['fC']['TF']??0;
                                if($pt == 'ADULT') {
                                    $old_adult_price = $BF;
                                    $BF = $adult_price;
                                } else if($pt == 'CHILD') {
                                    $old_child_price = $BF;
                                    $BF = $child_price;
                                } else if($pt == 'INFANT') {
                                    $old_infant_price = $BF;
                                    $BF = $infant_price;
                                }
                                $newVal['fC']['NF'] = sprintf("%.2f",($BF+$TAF));
                                $newVal['fC']['BF'] = $BF;
                                $newVal['fC']['TAF'] = $TAF;
                                $newVal['fC']['TF'] = sprintf("%.2f",($BF+$TAF));
                                $newTotalPriceListRow['fd'][$pt] = $newVal;
                            }
                        }
                        $newTotalPriceList[] = $newTotalPriceListRow;
                    }
                    $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList'] = $newTotalPriceList;

                    $ADT = 0;
                    $CHD = 0;
                    $INF = 0;
                    $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
                    if($travellerInfos) {
                        foreach($travellerInfos as $travellerInfo) {
                            $pt = $travellerInfo['pt']??'';
                            if($pt == 'ADULT') {
                                $ADT+=1;
                            } else if($pt == 'CHILD') {
                                $CHD+=1;
                            } else if($pt == 'INFANT') {
                                $INF+=1;
                            }
                        }
                    }
                    $newTotalFareDetail = $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']??[];
                    $NF = $newTotalFareDetail['fC']['NF']??0;
                    $BF = $newTotalFareDetail['fC']['BF']??0;
                    $TAF = $newTotalFareDetail['fC']['TAF']??0;
                    $TF = $newTotalFareDetail['fC']['TF']??0;

                    $BF = ($adult_price*$ADT)+($child_price*$CHD)+($infant_price*$INF);

                    $newTotalFareDetail['fC']['NF'] = sprintf("%.2f",($BF+$TAF));
                    $newTotalFareDetail['fC']['BF'] = $BF;
                    $newTotalFareDetail['fC']['TAF'] = $TAF;
                    $newTotalFareDetail['fC']['TF'] = sprintf("%.2f",($BF+$TAF));
                    $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail'] = $newTotalFareDetail;
                }
                $total_amount = $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['TF']??0;
                if($total_amount > $old_total_amount) {
                    $booking_details['order']['amount'] = $total_amount;
                    // $booking_status = 'NEW';
                    // $booking_details['order']['status'] = $booking_status;
                    // $flight_details = json_decode($order->flight_details, true);
                    // $flight_details['info']['tripInfos'][0]['sI'] = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI']??[];
                    // $flight_details['info']['tripInfos'][0]['totalPriceList'] = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList']??[];
                    // $flight_details['info']['totalPriceInfo'] = $booking_details['itemInfos']['AIR']['totalPriceInfo']??[];
                    // $order->flight_details = json_encode($flight_details);
                    $order->booking_details = json_encode($booking_details);
                    $order->total_amount = $total_amount;
                    // $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                    // $order->status = $booking_status;
                    $isSaved = $order->save();
                    if($isSaved) {
                        $reason = 'Change Fare';
                        if($adult_price) {
                            $reason .= ' Adult: '.CustomHelper::getPrice($old_adult_price).' -> '.CustomHelper::getPrice($adult_price);
                        }
                        if($child_price) {
                            $reason .= ' Child: '.CustomHelper::getPrice($old_child_price).' -> '.CustomHelper::getPrice($child_price);
                        }
                        if($infant_price) {
                            $reason .= ' Infant: '.CustomHelper::getPrice($old_infant_price).' -> '.CustomHelper::getPrice($infant_price);
                        }
                        $order_status_history  = array(
                            'order_id' => $order->id,
                            'orders_status' => $order->status,
                            'comments' => $reason,
                            'created_type' => $created_type,
                            'created_by' => $user_id,
                            'created_by_name' => $user_name,
                        );
                        $isSaved = OrderStatusHistory::create($order_status_history);

                        $new_data = DB::table('orders')->where('id',$order_id)->first();

                        $booking_details =  json_decode($new_data->booking_details)??'';
                        $driver_details  = $booking_details->driver_details??'';
                        $module_desc = $driver_details->driver_name??'';

                        $new_data =(array) $new_data;
                        $new_data = json_encode($new_data);
                        $module_id = $order_id;
                        $params['user_id'] = $user_id;
                        $params['user_name'] = $user_name;
                        $params['module'] = 'Order Update';
                        $params['module_desc'] = '';
                        $params['module_id'] = $module_id;
                        $params['sub_module'] = '';
                        $params['sub_module_id'] = '';
                        $params['sub_sub_module'] = "";
                        $params['sub_sub_module_id'] = 0;
                        $params['data_after_action'] = $new_data;
                        $params['action'] = "Change Fare";
                        CustomHelper::RecordActivityLog($params);

                        $response['success'] = true;
                        $response['message'] = 'Fare has been changed successfully.';
                    }
                } else {
                    $response['message'] = 'New fare cannot be less than initial fare!';
                }                
            } else {
                $response['message'] = 'Invalid request';
            }
        } else {
            $response['message'] = 'Invalid request';
        }
        return response()->json($response);
    }

    public function confirmOfflineFlight(Request $request) {
        $response = [];
        $response['success'] = true;
        $response['message'] = '';
        $user_id = auth()->user()->id??0;
        $user_name = auth()->user()->name??'';
        if($request->method() == 'POST') {
            $validation_msg = [];
            $rules = [];
            $nicknames = [
                'order_id' => 'Order',
                'pnrDetails' => 'PNR Details',
                'airline_ticket_no' => 'E-Ticket',
                'supplier_id' => 'Agent',
            ];
            $rules['order_id'] = 'required';
            $rules['pnrDetails'] = 'required';
            $rules['airline_ticket_no'] = 'nullable';
            $rules['supplier_id'] = 'required';

            $validation_msg['required'] = ':attribute is required.';
            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
            if ($validator->fails()) {
                return Response::json(array(
                    'success' => false,
                    'message' => 'Invalid request!',
                    'errors' => $validator->getMessageBag()->toArray()
                ), 200);
            }
            $order_id = $request->order_id;
            if(is_numeric($order_id) && $order_id > 0) {
                $order = Order::find($order_id);
                if($order && $order->inventory_id) {
                    $total_amount = $order->total_amount;
                    $partial_amount = $order->partial_amount;
                    $payment_due = $total_amount - $partial_amount;
                    if($payment_due > 0) {
                        return Response::json(array(
                            'success' => false,
                            'message' => 'The order has amount due! Please clear all the dues before confirming the order.',
                        ), 200);
                    }

                    $data['orderData'] = $order;
                    $inventory_id = $order->inventory_id??0;
                    $inventoryData = AirlineRouteInventory::where('id',$inventory_id)->first();//->where('user_id',0)
                    if($inventoryData && $inventoryData->id) {
                        $data['inventoryData'] = $inventoryData;
                        $record = $inventoryData->routeData??[];
                        if($record && $record->id) {
                            $supplier_id = $request->supplier_id??$order->supplier_id;
                            $pnrDetails = $request->pnrDetails??'';
                            $airline_ticket_no = $request->airline_ticket_no??'';

                            $OrderTraveller = $order->OrderTraveller??[];
                            if($OrderTraveller) {
                                foreach($OrderTraveller as $traveller) {
                                    $traveller->pnrDetails = $pnrDetails;
                                    $traveller->airline_ticket_no = $airline_ticket_no;
                                    $traveller->save();
                                }
                            }

                            $booking_status = 'SUCCESS';
                            $order_status_history  = array(
                                'order_id' => $order->id,
                                'orders_status' => $booking_status,
                                'comments' => 'Flight booking confirmation with PNR: '.$pnrDetails,
                                'created_type' => 'backend',
                                'created_by' => $user_id,
                                'created_by_name' => $user_name,
                            );
                            $isSaved = OrderStatusHistory::create($order_status_history);

                            $order->supplier_id = $supplier_id;
                            $order->orders_status = CustomHelper::getOrderStatus($booking_status);
                            $order->status = $booking_status;
                            $isSaved = $order->save();
                            if($isSaved) {
                                $order = Order::find($order_id);
                                $resp = Order::parseIIAIRBookingDetails($order);
                                $order->booking_details = json_encode($resp);
                                $order->save();

                                if($supplier_id) {
                                    $userId = $supplier_id;
                                    $amount = Order::getOrderSupplierPayment($order);
                                    $payment_method = 'wallet';//$order->payment_method;

                                    $old_balance = UserWallet::where('user_id',$userId)->sum('amount');
                                    $payment_method_name = CustomHelper::getPaymentGatewayName($payment_method);
                                    $wallet_comment = 'Received for flight booking after admin confirmation.';
                                    $balance = $old_balance + $amount ; 
                                    $walletData = array(
                                        'user_id' => $userId,
                                        'type' => 'credit',
                                        'amount' => $amount??0,
                                        'fees' => $order->fees??0,
                                        'payment_method' => 'Order',
                                        'balance' => $balance,
                                        'for_date' => date('Y-m-d H:i:s'),
                                        'txn_id' => $order->order_no,
                                        'comment' => $wallet_comment,
                                    );
                                    $isSavedWallet = UserWallet::create($walletData);
                                    if($isSavedWallet) {
                                        $order_payments = new OrderPayments;
                                        $order_payments->payment_method = $payment_method_name;
                                        $order_payments->order_id = $order->id;
                                        $order_payments->order_no = $order->order_no;
                                        $order_payments->user_id = $order->user_id;
                                        $order_payments->amount = $amount??0;
                                        $order_payments->payment_type = 'Supplier Payment Credited - '.CustomHelper::showUserName($userId);
                                        $order_payments->pg_payment_status = 1;
                                        $order_payments->save();
                                        $last_payment_id = $order_payments->id??NULL;

                                        $order->last_payment_id = $last_payment_id;
                                        $order->save();
                                    } else {
                                        CustomHelper::captureSentryMessage('Flight Payment Success, But Agent Account Credited Failed!');
                                    }
                                }

                                CustomHelper::applyFlightCommission($order->id);

                                $orderNo = sha1($order->id);
                                $resp = Order::sendBookingEmail($orderNo);
                            }

                            if ($isSaved) {
                                $row_id = $order->id;
                                $new_data = DB::table('orders')->where('id',$row_id)->first();
                                $module_desc = !empty($new_data->name)?$new_data->name:'';
                                $new_data = (array) $new_data;
                                $new_data = json_encode($new_data);
                                $module_id = $row_id;
                                $params = [];
                                $params['user_id'] = $user_id;
                                $params['user_name'] = $user_name;
                                $params['module'] = 'Order';
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
                                $response['message'] = 'The Booking has been confirmed successfully';
                            } else {
                                $response['message'] = 'The Booking cannot be confirmed, please try again or contact the administrator.';
                            }
                        } else {
                            $response['message'] = 'Invalid Route Data!';
                        }
                    } else {
                        $response['message'] = 'Invalid Inventory Data!';
                    }                
                } else {
                    $response['message'] = 'Invalid Order!';
                }
            } else {
                $response['message'] = 'Invalid Order Id!';
            }
        } else {
            $response['message'] = 'Invalid request!';
        }
        return response()->json($response);
    }


    public function updateStatus(Request $request)
    {
        $order_status_history = [];
        $order = [];
        $order_id = (isset($request->order_id))?$request->order_id:0;
        $id = isset($request->id) ? $request->id : 0;

        if (is_numeric($id) && $id > 0) {
            $order_status_history = OrderStatusHistory::find($id);
        }

        if (is_numeric($order_id) && $order_id > 0) {
            $order_status_histories = OrderStatusHistory::where('order_id',$order_id)->orderBy('id','asc')->get();
            $order = Order::find($order_id);
        }else{
            return back()->with("alert-danger", "Un authorize request!.");
        }

        if ($request->method() == "POST") {
            $rules = [];
            $rules['orders_status'] = 'required';
            $this->validate($request, $rules);
            $user_id = auth()->user()?auth()->user()->id:0;
            $user_name = auth()->user()?auth()->user()->name:'system';

            $req_data = [];
            $req_data = $request->except(["_token", "back_url", "id"]);
            $req_data['created_type'] = 'backend';
            $req_data['created_by'] = $user_id;
            $req_data['created_by_name'] = $user_name;

            $order->orders_status = $request->orders_status ?? 0;
            // $order->status = CustomHelper::showEnquiryMaster($request->orders_status) ?? '';
            $order->save();

            if (!empty($order_status_history) && $order_status_history->id == $id) {
                $isSaved = OrderStatusHistory::where("id",$order_status_history->id)->update($req_data);
                $msg = "Order status has been added successfully.";
            } else {
                $req_data['order_id'] = $order_id;
                $isSaved = OrderStatusHistory::create($req_data);
                $msg = "Order status has been updated successfully.";
            }

            if ($isSaved) {
                return redirect(route("admin.orders.update",$order_id))->with("alert-success", $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Order status could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["order_status_history"] = $order_status_history;
        $data["id"] = $id;
        $old_order_status = isset($order->status) && !empty($order->status) ? $order->status.', ' : '';
        $data["order_status_histories"] = $order_status_histories;
        $page_heading = "Order Status".' ('.$old_order_status.'Order ID: #'.$order->order_no.')';
        $data['page_heading'] = $page_heading;
        $data['order'] = $order;
        $data['order_statuses'] = EnquiriesMaster::whereNotNull('category')->where(['parent_id'=>0,'group_id'=>null,'status'=>1,'type'=>'order-status'])->orderBy('sort_order','asc')->get();

        return view("admin.orders.update_status", $data);
    }


    public function calendarBooking(Request $request) {
        $payment_status = $request->order??'';
        $data = [];
        $limit = $this->limit;
        if(!empty($payment_status)) {

            $status_arr = [];

            $status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
                $status_query->where(function($q1){
                    $q1->where('category','confirmed');
                });
                $status_arr = $status_query->get()->pluck('id')->toArray();
             

            //$getStatues = EnquiriesMaster::where('status',1)->where(['type'=>'order-status'])->get();
            // $getStatues = EnquiriesMaster::whereNotNull('category')->where(['parent_id'=>0,'group_id'=>null,'status'=>1,'type'=>'order-status'])->get();
            // if($getStatues){
            //     foreach($getStatues as $statuses){
            //         if($statuses->slug == 'order_accepted' || $statuses->slug == 'confirmed'){
            //             $status_arr[] = $statuses->id??0;
            //         }
            //     }
            // }

            // $query = Order::with('Country')->orderBy('trip_date', 'desc');

            $query = Order::with('Country')->where('payment_status',1)->whereNotNull('trip_date')->orderBy('trip_date', 'desc')->where('cancel_request',0);
            $group_date_query=Order::select('trip_date')->where('payment_status',1)->whereNotNull('trip_date')->orderBy('trip_date', 'desc')->where('cancel_request',0);
            $user = auth()->user();
            $user_id = $user?$user->id:'';
            $is_vendor = $user?$user->is_vendor:'';

            if($is_vendor ==1){
                $query->where('vendor_id',$user_id);
                $group_date_query->where('vendor_id',$user_id);
            }

            $group_date_query->where(function($q1) use ($status_arr) {
                    $q1->where(function($q2) use ($status_arr) {
                        $q2->where('order_type','!=',3);
                        // $q2->whereIn('status',['Order Accepted','Voucher created']);
                        $q2->whereIn('orders_status',$status_arr);
                    });
                    $q1->orWhere(function($q2) {
                        $q2->where('order_type',3);
                        $q2->whereIn('status',['SUCCESS','PENDING','ON_HOLD']);
                    });
                });

             // $query->where('payment_status',1);
                  $query->where(function($q1) use ($status_arr) {
                    $q1->where(function($q2) use ($status_arr) {
                        $q2->where('order_type','!=',3);
                        // $q2->whereIn('status',['Order Accepted','Voucher created']);
                        $q2->whereIn('orders_status',$status_arr);
                    });
                    $q1->orWhere(function($q2) {
                        $q2->where('order_type',3);
                        $q2->whereIn('status',['SUCCESS','PENDING','ON_HOLD']);
                    });
                });

            /*if($request->package_id) {
                $packageSearch = $request->package_id;
                if(is_numeric($packageSearch)) {
                    $query->where('package_id',$packageSearch);
                } else {
                    $query->whereHas('Package',function($q1) use ($packageSearch){
                        $q1->where("title", "like", "%".$packageSearch."%");
                    });
                }
            }*/
            /*if($request->search) {
                $search = $request->search;
                $query->where(function($q1) use ($search){
                    $q1->where('package_name','like',"%$search%");
                    $q1->orwhere('booking_details','like',"%$search%");
                });
            }*/


            // if(empty($request->todays_orders) && empty($request->yesterdays_orders) && empty($request->all_orders)) {
            //     if($request->order_status && is_array($request->order_status)) {

            //         $getIds = EnquiriesMaster::whereIn('category', $request->order_status)->select('id')->get();
            //         $xArray=[];
            //         foreach ($getIds as $key => $get_id) {
            //             $xArray[]=$get_id->id;
            //         }

            //         if(in_array('new',$request->order_status)){

            //             $query->where(function ($sub_query) use ($xArray) {
            //                 $sub_query->whereNull('orders_status');
            //                 $sub_query->orWhereIn('orders_status', $xArray);
            //             });

            //             $group_date_query->where(function ($sub_query) use ($xArray) {
            //                 $sub_query->whereNull('orders_status');
            //                 $sub_query->orWhereIn('orders_status', $xArray);
            //             });

            //         }else{
            //             $query->whereNotNull('orders_status');
            //             $query->WhereIn('orders_status', $xArray);

            //             $group_date_query->whereNotNull('orders_status');
            //             $group_date_query->WhereIn('orders_status', $xArray);
            //         }

            //     } else {

            //         $autoSelStatus = ['new','accepted'];
            //         $getIds = EnquiriesMaster::whereIn('category', $autoSelStatus)->select('id')->get();
            //         $xArray=[];
            //         foreach ($getIds as $get_id) {
            //             $xArray[]=$get_id->id;
            //         }
            //             $query->where(function ($sub_query) use ($xArray) {
            //                 $sub_query->whereNull('orders_status');
            //                 $sub_query->orWhereIn('orders_status', $xArray);
            //             });

            //             $group_date_query->where(function ($sub_query) use ($xArray) {
            //                 $sub_query->whereNull('orders_status');
            //                 $sub_query->orWhereIn('orders_status', $xArray);
            //             });

            //     }
            // }

            $from_date = '';
            $to_date = '';
            $range = $request->range;
            if(!empty($range) && $range != 'custom' && (empty($request->todays_orders) && empty($request->yesterdays_orders))) {
                $date_between_arr = CustomHelper::get_to_from_date($range);
                $from_date = $date_between_arr['from'];
                $to_date = $date_between_arr['to'];
            } else if(!empty($request->todays_orders)) {
                $from_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else if(!empty($request->yesterdays_orders)) {
                $from_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat(date("Y-m-d", strtotime("-1 day")),'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else {
                $from = $request->from ?? date('d/m/Y') ;
                $to = $request->to ?? date('d/m/Y') ;
                if($from && $to) {
                    $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                    $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                    $to_date = $to_date.' 23:59:59';
                } else if($from) {
                    $from_date = CustomHelper::DateFormat($from,'Y-m-d','d/m/Y');
                    $from_date = $from_date.' 00:00:00';
                } else if($to) {
                    $to_date = CustomHelper::DateFormat($to,'Y-m-d','d/m/Y');
                    $to_date = $to_date.' 23:59:59';
                }
            }

            if(!empty($from_date)) {
                $query->whereDate('trip_date','>=',$from_date);
                $group_date_query->whereDate('trip_date','>=',$from_date);
            }
            if(!empty($to_date)) {
                $query->whereDate('trip_date','<=',$to_date);
                $group_date_query->whereDate('trip_date','<=',$to_date);
            }

            if($request->order_type) {
                $query->where('order_type',$request->order_type);
                $group_date_query->where('order_type',$request->order_type);
            }

            $orders = $query->get();
            $group_date = $group_date_query->groupBy(DB::raw('Date(trip_date)'))->get();
        }else{
            $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
            return redirect($ADMIN_ROUTE_NAME);
        }
        $data['orders'] = $orders;
        $data['group_date'] = $group_date;
        $data['limit'] = $limit;
        $data['payment_status'] = $payment_status;

        $search_data = $request->toArray();
        unset($search_data['order']);
        unset($search_data['order_type']);
        $data['search_data'] = $search_data;
        // prd($data['search_data']);
        return view('admin.orders.calendar_booking', $data);
    }

    public function addPayment(Request $request) {
        if($request->order_id) {
            $order_id = $request->order_id??'';
            if($order_id) {
                $order = Order::find($order_id);
                if($order) { //$order->payment_status == 0 || $order->payment_status == 2
                    $method = $request->method();
                    if($method == "POST"){
                        $rules = [];
                        // $rules['type'] = 'required';
                        // $rules['amount'] = 'required|numeric|integer';
                        // $rules['payment_method'] = 'required';
                        $rules['for_date'] = 'required';
                        $rules['comment'] = 'required';
                        $this->validate($request, $rules);

                        $user_id = $order->user_id;
                        $old_balance = UserWallet::where('user_id',$user_id)->sum('amount');
                        $amount = $order->total_amount;
                        $payment_status = $order->payment_status??0;
                        $partial_amount = 0;
                        if($payment_status == 1) {
                            $partial_amount = $order->partial_amount??0;
                        }
                        if($partial_amount) {
                            $amount = $amount - $partial_amount;
                        }
                        if(empty($amount)) {
                            return back()->with("alert-danger","The order does not have any due amount! Please try again or contact the adminstrator!");
                        } else if($old_balance >= $amount) {
                            $type = 'debit';
                            $for_date = isset($request->for_date) ? $request->for_date.' '.date("H:i:s") : date('Y-m-d H:i:s');
                            $comment = $request->comment??'';
                            $payment_method = 'wallet';
                            $payment_method_name = CustomHelper::getPaymentGatewayName($payment_method);
                            $balance = 0;
                            $balance = $old_balance - $amount;

                            $req_data = array(
                                'user_id' => $user_id,
                                'type' => $type ?? null,
                                'amount' => -$amount,
                                'fees' => 0,
                                'payment_method' => 'Order',
                                'balance' => $balance,
                                'for_date' => $for_date,
                                'txn_id' => $order->order_no,
                                'comment' => $comment,
                            );
                            $isSaved = UserWallet::create($req_data);
                            $msg = "Transaction has been added successfully.";

                            if($isSaved) {
                                $order_payments = new OrderPayments;
                                $order_payments->payment_method = $payment_method_name;
                                $order_payments->order_id = $order->id;
                                $order_payments->order_no = $order->order_no;
                                $order_payments->user_id = $order->user_id;
                                $order_payments->amount = $amount??0;
                                $order_payments->payment_type = $order->pay_type??'total_price';
                                $order_payments->pg_payment_status = 1; //Paid
                                $order_payments->save();
                                $last_payment_id = $order_payments->id??NULL;

                                $order->last_payment_id = $last_payment_id;
                                $order->method = $payment_method;
                                $order->partial_amount = $partial_amount+$amount;
                                $order->payment_status = 1;
                                $order->save();

                                $resp = Order::processPaymentSuccess($order->id);
                                if($resp['success']) {
                                    if($resp['redirect_url']) {
                                        return back()->with("alert-success",$msg);
                                    } else {
                                        $message = $resp['message']??'';
                                        return back()->with("alert-danger",$message);
                                    }
                                } else {
                                    $message = $resp['message']??'';
                                    return back()->with("alert-danger",$message);
                                }
                            } else {
                                return back()->with("alert-danger","The Transaction could not be added, please try again or contact the administrator.");
                            }
                        } else {
                            return back()->with("alert-danger","User wallet balance is insufficient to make the order payment, please try again or contact the administrator.");
                        }
                    }
                    $data = [];
                    $page_heading = "Order Payment";
                    $data['page_heading'] = $page_heading;
                    $data['order'] = $order;
                    $data['order_id'] = $order_id;
                    // prd($data);
                    return view("admin.orders.add_payment", $data);
                }
            }            
        }
    }

    public function changeOrderStatus(Request $request)
    {
        $response = [];
        $order_id = (isset($request->order_id))?$request->order_id:0;
        $orders_status = (isset($request->orders_status))?$request->orders_status:0;
        $description = (isset($request->description))?$request->description:'';

        if (is_numeric($order_id) && $order_id > 0) {
            $order = Order::find($order_id);
        }else{
            $response['success'] = false;
            $message = '<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Invalid request.</div></div>';
            return Response::json($response);
        }
            $user_id = auth()->user()?auth()->user()->id:0;
            $user_name = auth()->user()?auth()->user()->name:'system';
            $req_data = [];
            $req_data['order_id'] = $order_id;
            $req_data['orders_status'] = CustomHelper::showEnquiryMaster($orders_status) ?? '';
            $req_data['comments'] = $description;
            $req_data['created_type'] = 'backend';
            $req_data['created_by'] = $user_id;
            $req_data['created_by_name'] = $user_name;
            $order->orders_status = $orders_status;

            $voucherCreatedId = CustomHelper::getOrderStatus("Voucher created");
            $voucherSentId = CustomHelper::getOrderStatus("Voucher sent");

            if($voucherCreatedId == $orders_status){
                //create voucher case
                $isSaved = 1;
            }else if($voucherSentId == $orders_status){
                //sent voucher case
                $isSaved = 1;
            }else{
                $order->save();
                $isSaved = OrderStatusHistory::create($req_data);
            }

            if ($isSaved) {
                $message = '<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Order status has been updated successfully.</div></div>';
                $response['success'] = true;
            } else {
                $message = '<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>The Order status could be added, please try again or contact the administrator.</div></div>';
                $response['success'] = false;
            }
            $response['order'] = $order;
            $response['htmlData'] = view("admin.orders.view", $response)->render();
            $response['message'] = $message;
            return Response::json($response);
    }

    public function updateOrder(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->order_id) {
            $order_id = $request->order_id;
            $order = Order::find($order_id);
            if($order) {
                $admin_notes = $request->admin_notes??'';
                $order->admin_notes = $admin_notes;
                $isSaved = $order->save();
                if($isSaved) {
                    $response['success'] = true;
                    $response['message'] = 'Notes updated successfully';
                } else {
                    $response['message'] = 'Something went wrong, please try again.';
                }
            }
        }        
        return Response::json($response);
    }

    public function updateOfflineFlightPassenger(Request $request) {
        $data = [];
        $created_type = 'backend';
        $created_by = 0;
        $created_by_name = 'system';
        $adminUser = auth::guard('admin')->user();
        if($adminUser && $adminUser->id) {
            $created_type = 'backend';
            $created_by = $adminUser->id??0;
            $created_by_name = $adminUser->name??'system';
        }
        if($request->order_id) {
            $order_id = $request->order_id??0;
            $name = $request->name??'';
            $order = Order::find($order_id);
            if($order) {
                $OrderTraveller = $order->OrderTraveller??[];
                if($OrderTraveller) {
                    foreach($OrderTraveller as $traveller) {
                        $traveller_name = $traveller->title.' '.$traveller->first_name.' '.$traveller->last_name;
                        if($name==$traveller_name) {
                            $data['traveller'] = $traveller;
                            break;
                        }
                    }
                }
                if(!(isset($data['traveller']) && !empty($data['traveller']))) {
                    abort(404);
                }
                if($request->method() == 'POST' && isset($traveller)) {
                    $response = [];
                    $response['success'] = true;
                    $response['message'] = '';
                    $user_id = auth()->user()->id??0;
                    $user_name = auth()->user()->name??'';
                    $validation_msg = [];
                    $rules = [];
                    $nicknames = [
                        'title' => 'Title',
                        'first_name' => 'First Name',
                        'last_name' => 'Last Name',
                    ];
                    $rules['title'] = 'required';
                    $rules['first_name'] = 'required';
                    $rules['last_name'] = 'nullable';
                    $validation_msg['required'] = ':attribute is required.';
                    $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
                    $validator->after(function ($validator) use ($request, $name) {
                        $title = $request->title??'';
                        $first_name = $request->first_name??'';
                        $last_name = $request->last_name??'';
                        $new_name = $title.' '.$first_name.' '.$last_name;
                        if($name == $new_name) {
                            $validator->errors()->add('first_name', 'The Name has not changed!');
                        }
                    });
                    if ($validator->fails()) {
                        return Response::json(array(
                            'success' => false,
                            'errors' => $validator->getMessageBag()->toArray()
                        ), 200);
                    }
                    $update_data = [
                        'title' => $request->title??'',
                        'first_name' => $request->first_name??'',
                        'last_name' => $request->last_name??'',
                    ];
                    $isSaved = OrderTraveller::where('order_id',$order_id)->where('id',$traveller->id)->update($update_data);
                    if($isSaved) {
                        $new_name = $update_data['title'].' '.$update_data['first_name'].' '.$update_data['last_name'];

                        $order_status_history  = array(
                            'order_id' => $order->id,
                            'orders_status' => $order->status,
                            'comments' => 'Passenger name changed from:'.$name.' to '.$new_name,
                            'created_type' => $created_type,
                            'created_by' => $created_by,
                            'created_by_name' => $created_by_name,
                        );
                        $isSaved = OrderStatusHistory::create($order_status_history);

                        if($isSaved) {
                            $order = Order::find($order_id);
                            $resp = Order::parseIIAIRBookingDetails($order);
                            $order->booking_details = json_encode($resp);
                            $order->save();

                            if ($isSaved) {
                                $row_id = $order->id;
                                $new_data = DB::table('orders_traveller')->where('id',$traveller->id)->first();
                                $module_desc = !empty($new_data->order_no)?$new_data->order_no:'';
                                $new_data = (array) $new_data;
                                $new_data = json_encode($new_data);
                                $module_id = $row_id;
                                $params = [];
                                $params['user_id'] = $created_by;
                                $params['user_name'] = $created_by_name;
                                $params['module'] = 'Order';
                                $params['module_desc'] = $module_desc;
                                $params['module_id'] = $module_id;
                                $params['sub_module'] = "OrderTraveller";
                                $params['sub_module_id'] = $traveller->id??0;
                                $params['sub_sub_module'] = "";
                                $params['sub_sub_module_id'] = 0;
                                $params['data_after_action'] = $new_data;
                                $params['action'] = "Update";
                                CustomHelper::RecordActivityLog($params);

                                $response['success'] = true;
                                $response['message'] = 'The passenger details has been updated successfully';
                            } else {
                                $response['message'] = 'The passenger details cannot be updated, please try again or contact the administrator.';
                            }
                        }
                    }
                    return Response::json($response);
                }
                $data['order_id'] = $order_id;
                $data['name'] = $name;
                return view('admin.orders.update_offline_flight_passenger', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
}