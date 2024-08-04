<style>
    .centersec.fancybox-content {width: 900px;height: 450px;padding: 20px;}
    a#cust_btn {position: relative;top: 40px;right: 25px;}
</style>
<?php
$order_id = (isset($order->id))?$order->id:0;
$user_id = (isset($order->user_id))?$order->user_id:'';
$package_id = (isset($order->package_id))?$order->package_id:'';
$order_no = (isset($order->order_no))?$order->order_no:'';
$name = (isset($order->name))?$order->name:'';
$phone = (isset($order->phone))?$order->phone:'';
$email = (isset($order->email))?$order->email:'';
$country = (isset($order->country))?$order->country:'';
$comment = (isset($order->comment))?$order->comment:'';
$payment_status = (isset($order->payment_status))?$order->payment_status:'';
// $payment_description = (isset($order->payment_description))?$order->payment_description:'';
// $payment_response = (isset($order->payment_response))?$order->payment_response:'';
$service_level = (isset($order->service_level))?$order->service_level:'';
$sub_total_amount = (isset($order->sub_total_amount))?$order->sub_total_amount:'';
$total_amount = (isset($order->total_amount))?$order->total_amount:'';
// $created_at = (isset($order->created_at))?$order->created_at:'';
// $created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');
$back = (request()->has('back'))?request()->back:'';
$show_cancel = $show_cancel??0;
?>
<div class="centersec">
<div class="bgcolor viewsec scroll_view">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>
<div class="table-responsive">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec table">
<tr>
    <td width="100%" valign="top" class="innersec" style="border:0;">
                <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                    <h2 class="title-btn">Order Details (Order ID: #{{$order->order_no??''}})
                        <?php $statusId = 0; $text = ""; $style = "display:none;"; $curr_status = '';
                        if($back == '' || $back == 'order_listing'){
                            if($order->payment_status == 1 && $order->order_type != 3){ 
                                $curr_status = CustomHelper::showEnquiryMaster($order->orders_status) ?? '';
                                $newId = CustomHelper::getOrderStatus("New");
                                $processingId = CustomHelper::getOrderStatus("Processing");
                                $voucherCreatedId = CustomHelper::getOrderStatus("Voucher created");
                                $voucherSentId = CustomHelper::getOrderStatus("Voucher Sent");

                                if($curr_status == "New" || $order->orders_status == $newId){
                                    $statusId = $processingId??0;
                                    $text = "Accept";
                                    $style = "";
                                }else if($curr_status == "Processing" || $order->orders_status == $processingId){
                                    $statusId = $voucherCreatedId??0;
                                    $text = "Create Voucher";
                                    $style = "";
                                }else if(($curr_status == "Voucher created" || $order->orders_status == $voucherCreatedId) || ($curr_status == "Voucher Sent" || $order->orders_status == $voucherSentId)){
                                    $statusId = $voucherSentId??0;
                                    $text = "Send Voucher";
                                    $style = "";
                                }

                                if($order->cancel_request == 1){ ?>
                                    <div class="">
                                        <button type="submit" class="btn btn-success accept_cancel_request" id="accept_cancel_request" data-order_id="{{$order_id}}">Accept</button>
                                        <button type="submit" class="btn_admin2 reject_cancel_request" id="reject_cancel_request" data-order_id="{{$order_id}}">Reject</button>
                                    </div>
                                <?php } ?>
                                
                            <div class="">
                                <?php if(CustomHelper::checkPermission('orders','edit')){ ?>
                                <button style="{{$style}}" type="submit" class="btn btn-success accept_btn" id="accept_btn" data-order_id="{{$order_id}}" data-orders_status="{{$statusId}}">{{$text}}</button>
                                <?php } ?>
                                <?php if(CustomHelper::checkPermission('orders','edit') && $show_cancel == 1){ ?>
                                <button type="submit" class="btn_admin2 cancel_btn" id="cust_btn" data-order_id="{{$order_id}}">Cancel</button>
                                <?php } ?>
                            </div>
                        
                        <?php } } ?>
                    </h2>
                    <tr>
                        <td colspan="3">
                        <div class="order_status_form" style="display:none">
                        <form class="form-inline" id="acceptForm">
                                <div class="modal-title alert_msg"></div>
                                <input type="hidden" id="order_id" name="order_id" value="">
                                <input type="hidden" id="orders_status" name="orders_status" value="">
                                <input type="hidden" id="saved_order_status" name="saved_order_status" value="{{$order->orders_status}}">
                                <input type="hidden" id="curr_status" name="curr_status" value="{{$curr_status}}">
                                <input type="hidden" id="order_type" name="order_type" value="{{$order->order_type??0}}">
                            <div class="modal-body">
                                <label>Description:</label><br>
                                <textarea name="description" id="description" class="form-control" style="width: 100%;" ></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success submit_accept btn_admin">Submit</button>
                                <!-- <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button> -->
                            </div>
                        </form>
                        </div>


                        <div class="order_cancel_form" style="display:none">
                            <form action="<?php echo route('admin.orders.refund') ?>" class="form-inline" method="GET" id="cancelForm">
                                <div class="modal-title alert_msg"></div>
                                <input type="hidden" id="refund_order_id" name="order_id" value="">
                                <input type="hidden" id="action" name="action" value="1">
                                <div class="modal-body">
                                    <label class="required">Reason for Cancellation:</label><br>
                                    <textarea name="reason" id="reason" class="form-control" style="width: 100%;" ></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success submit_cancel btn_admin">Submit</button>
                                    <!-- <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button> -->
                                </div>
                            </form>
                        </div>

                          <div class="order_reject_form" style="display:none">
                            <form action="<?php echo route('admin.orders.reject') ?>" 
                                class="form-inline" method="post" id="cancelForm">
                                {{ csrf_field() }}
                                <div class="modal-title alert_msg"></div>
                                <input type="hidden" id="reject_order_id" name="order_id" value="">
                                <div class="modal-body">
                                    <label class="required">Reason for Rejection:</label><br>
                                    <textarea name="reason" id="reject_reason" class="form-control" style="width: 100%;" ></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success submit_reject btn_admin">Submit</button>
                                    <!-- <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button> -->
                                </div>
                            </form>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="">Order</th>
                        <th class="">User/ Traveller</th>
                        <th class="">Data</th>
                    </tr>
                        <tr>
                            <td>
                                <?php
                                $order_type_arr = config('custom.order_type');
                                $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';
                                $userData = $order->userData??[];
                                ?>
                                <p><strong>Order Id: </strong>{{$order->order_no??''}}</p>
                                <p><strong>Order Date: </strong>{{ date('d M,Y h:i A',strtotime($order->created_at)) }}</p>
                                <p><strong>Order Type: </strong>{{$order_type??''}}</p>
                                <?php if($order->order_type ==1){ ?>
                                    <p><strong>Package: </strong>{{$order->package_name??''}}</p>
                                    <p><strong>Package Type: </strong>{{$order->package_type_name??''}}</p>
                                    <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                <?php } else if($order->order_type == 3) { //Flight
                                    $booking_details = [];
                                    $booking_status = '';
                                    $bookingId = '';
                                    if($order->booking_details){
                                        $booking_details = json_decode($order->booking_details,true)??[];
                                        $booking_status = $booking_details['order']['status']??'';
                                        $bookingId = $booking_details['order']['bookingId']??'';
                                    }
                                    ?>
                                    <?php 
                                    $user = auth()->user();
                                    $is_vendor = $user?$user->is_vendor:'';
                                    if(!empty($bookingId) && $is_vendor != 1) { ?>
                                    <p>
                                        <strong>Supplier ID: </strong>{{$bookingId}}
                                    </p>
                                    <?php } ?>

                                    <?php if(!empty($booking_status) && $booking_status != 'SUCCESS') { ?>
                                    <p>
                                        <strong>Booking Status: </strong>{{$booking_status}}
                                        <?php if($order->payment_status == 1 && ($booking_status == 'PENDING' || $booking_status == 'HOLD' || $booking_status == 'ON_HOLD') ){ ?>
                                            (<a href="javascript:void(0)" class="check_booking_status" data-id="{{$order->id}}">Check New Status</a>)
                                        <?php } ?>
                                    </p>
                                    <?php } ?>
                                <?php } else if($order->order_type == 5){ //Hotel
                                    $booking_details = [];
                                    if($order->booking_details){
                                        $booking_details = (array)json_decode($order->booking_details);
                                    }
                                    ?>
                                    <p><strong>Hotel: </strong>{{$booking_details['hotel_name']??''}}</p>
                                    <p><strong>Room Type: </strong>{{$booking_details['room_name']??''}}</p>
                                    <?php if(isset($booking_details['plan_name']) && !empty($booking_details['plan_name'])){ ?>
                                    <p><strong>Room Plan: </strong>{{$booking_details['plan_name']??''}}</p>
                                    <?php } ?>
                                    <p><strong>Checkin: </strong>{{$booking_details['checkin']??''}}</p>
                                    <p><strong>Checkout: </strong>{{$booking_details['checkout']??''}}</p>
                                    <p><strong>Room(s): </strong>{{$booking_details['inventory']??''}}</p>
                                    <p><strong>Adult: </strong>{{$booking_details['adult']??''}}</p>
                                    <?php if(isset($booking_details['child']) && !empty($booking_details['child'])){ ?>
                                    <p><strong>Child: </strong>{{$booking_details['child']??''}}</p>
                                    <?php } ?>
                                    <?php if(isset($booking_details['infant']) && !empty($booking_details['infant'])){ ?>
                                    <p><strong>Infant: </strong>{{$booking_details['infant']??''}}</p>
                                    <?php } ?>
                                <?php } else if($order->order_type == 6){ ?>
                                <p><strong>Activity: </strong>{{$order->package_name??''}}</p>
                                <p><strong>Activity Type: </strong>{{$order->package_type_name??''}}</p>
                                <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y h:i A',strtotime($order->trip_date)):''}}</p>
                                <?php }
                                if($order->order_type ==4){ ?>


                                <?php } ?>

                                <?php
                                $price_category_choice_record = $order->price_category_choice_record??[];
                                if(!empty($price_category_choice_record)) {
                                    $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
                                    foreach($price_category_choice_record_arr as $pccr) {
                                        $input_label = $pccr['input_label']??'';
                                        $input_value = $pccr['input_value']??0;
                                        $input_price = $pccr['input_price']??0;
                                        $sub_total = $input_value*$input_price;
                                        ?>
                                        <p><strong>{{$input_label}}:</strong> {{$input_value}} X {{CustomHelper::getPrice($input_price)}} = {{CustomHelper::getPrice($sub_total)}}</p> 
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php  //User Details ?>
                                <p>
                                    <strong>User: </strong>
                                    @if($userData)
                                    @if($userData->is_agent == 1)
                                    {{$userData->agentInfo->company_brand??''}}
                                    (Agent)
                                    @else
                                    {{$userData->name??''}}
                                    @endif
                                    @endif
                                </p>
                                <p><strong>Email: </strong>{{$userData->email??''}}</p>
                                @if($userData && $userData->phone)
                                <p><strong>Phone: </strong>+{{$userData->country_code??91}}-{{$userData->phone??''}}</p>
                                @endif
                                
                                <hr>

                               <?php  //Traveller Details ?>
                                 <p>
                                <strong>Traveller: </strong>{{$order->name??''}}
                                </p>
                                <p><strong>Email: </strong>{{$order->email??''}}</p>
                                @if($order->phone)
                                <p><strong>Phone: </strong>+{{$order->country_code??91}}-{{$order->phone??''}}</p>
                                @endif
                                @if($order->address1)
                                <p><strong>Address: </strong>{{$order->address1??''}}, {{$order->address2??''}}
                                @endif
                                @if($order->city)
                                 &nbsp;&nbsp; <strong>City: </strong>{{$order->city??''}} &nbsp;&nbsp; </p>
                                @endif
                                @if($order->state)
                                <p><strong>State: </strong>{{$order->state??''}} &nbsp;&nbsp; <strong>Country: </strong>{{CustomHelper::GetCountry($order->country??'','name')}} &nbsp;&nbsp; <strong>Zip Code: </strong>{{$order->zip_code??''}}</p>
                                @endif
                            </td>
                            <td>
                                <p>
                                    <strong>Payment Method: </strong>
                                    {{CustomHelper::getPaymentGatewayName($order->method)}}
                                </p>
                                <p>
                                    <strong>Payment Status: </strong>
                                    {!!CustomHelper::showPaymentStatus($order->payment_status??0)!!}
                                </p>
                                @if(isset($order->pay_type) && !empty($order->pay_type))<p><strong>Payment Type: </strong>{{ ucwords(str_replace("_", " ", $order->pay_type)) }}</p>
                                @endif

                                <?php if( (!empty($order->discount) && $order->discount > 0) || (!empty($order->fees) && $order->fees > 0) ){ ?>
                                <p><strong>Sub Total: </strong>{{CustomHelper::getPrice($order->sub_total_amount)??''}}</p>

                                <?php if(!empty($order->discount) && $order->discount > 0){ ?>
                                <p><strong>Discount: </strong>{{CustomHelper::getPrice($order->discount)??''}}</p>
                                <?php } ?>

                                <?php if(!empty($order->fees) && $order->fees > 0){ ?>
                                <p><strong>Fees: </strong>{{CustomHelper::getPrice($order->fees)??''}}</p>
                                <?php } ?>
                                <?php } ?>

                                <?php if(!empty($order->discount_coupon) && $order->discount_coupon > 0){ ?>
                                    <p><strong>Coupon Discount: </strong>{{CustomHelper::getPrice($order->discount_coupon)??''}}</p>
                                <?php } ?>
                                <?php
                                $total_amount = $order->total_amount??0;
                                $partial_amount = $order->partial_amount??0;
                                $payment_due = 0;
                                if($partial_amount) {
                                    $payment_due = $total_amount - $partial_amount;
                                }
                                ?>
                                <p><strong>Total Amount: </strong>{{CustomHelper::getPrice($total_amount)}}</p>
                                <?php if($partial_amount && $total_amount > $partial_amount && $order->pay_type != 'book_without_payment'){ ?>
                                  <!-- <p><strong>Partial Paid Amount:</strong>{{CustomHelper::getPrice($order->partial_amount)}}</p> -->
                                  <p>
                                    <?php if($payment_status == 1){ 
                                        echo '<strong>Amount Paid:</strong><span style="color:orange">'.CustomHelper::getPrice($partial_amount).'</span>'; 
                                    } ?></p>
                                <?php } ?>
                                
                                <?php if($payment_due == 0){ ?>
                                <p>
                                    <?php if($payment_status == 1){
                                        echo '<strong>Total Amount Paid: </strong><span style="color:green">'.CustomHelper::getPrice($total_amount).'</span>';
                                    }elseif($payment_status == 2){
                                        echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($total_amount).'</span>';

                                    }elseif($payment_status == 0){
                                        echo '<strong>Amount Due: </strong><span style="color:orange">'.CustomHelper::getPrice($total_amount).'</span>';
                                    } ?></p>
                                <?php }else {
                                    if($order->pay_type != 'book_without_payment'){
                                        if($payment_status == 1){
                                            echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($payment_due).'</span>';
                                        }elseif($payment_status == 2){
                                            echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($total_amount).'</span>';

                                        }elseif($payment_status == 0){
                                            echo '<strong>Amount Due: </strong><span style="color:orange">'.CustomHelper::getPrice($total_amount).'</span>';
                                        }?>
                                    <?php } } ?>
                            </td>
                        </tr>
                        <?php //Flight GST Details 
                        if(!empty($booking_details['gstInfo'])){ ?>
                        <tr>
                        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                            <thead>
                             <tr>
                                <th colspan="3">GST Name</th>
                                <th colspan="3">GST No</th>
                                <th colspan="3">GST Address</th>
                                <th colspan="3">GST email</th>
                                <th colspan="3">GST Phone</th>
                             </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3">{{$booking_details['gstInfo']['registeredName']??''}}</td>
                                    <td colspan="3">{{$booking_details['gstInfo']['gstNumber']??''}}</td>
                                    <td colspan="3">{{$booking_details['gstInfo']['address']??''}}</td>
                                    <td colspan="3">{{$booking_details['gstInfo']['email']??''}}</td>
                                    <td colspan="3">{{$booking_details['gstInfo']['mobile']??''}}</td>
                                </tr>
                            </tbody>
                           </table>
                        </tr>
                         <?php } ?>
                        <tr>
                           <td colspan="3"><p><strong>Comment: </strong>{!!nl2br($order->comment)!!}</p></td>
                        </tr>
                    </table>
                    @if(isset($itineraries) && (isset($booking_status)) && $booking_status == 'SUCCESS')
                        <h2>Flight Booking Details</h2>
                        {!!$itineraries!!}
                    @endif 

                     @if(isset($cab_itineraries))
                        <h2>Cab Booking Details</h2>
                        {!!$cab_itineraries!!}
                    @endif
    </td>
</tr>
</table> 
                            
    <div class="modal fade" id="acceptModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-inline" id="acceptForm">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title alert_msg"></h3>
                    <input type="hidden" id="order_id" name="order_id" value="">
                    <input type="hidden" id="orders_status" name="orders_status" value="">
                    <input type="hidden" id="saved_order_status" name="saved_order_status" value="{{$order->orders_status}}">
                    <input type="hidden" id="curr_status" name="curr_status" value="{{$curr_status}}">
                    <input type="hidden" id="order_type" name="order_type" value="{{$order->order_type??0}}">
                </div>
                <div class="modal-body">
                    <label>Description:</label><br>
                    <textarea name="description" id="description" class="form-control" style="width: 100%;" ></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success submit_accept btn_admin">Submit</button>
                    <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        </div>
    </div>

  </div>
 </div>
</div>