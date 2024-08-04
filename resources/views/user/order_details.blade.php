<style>
    .centersec.fancybox-content {
        width: 900px;
        height: 450px;
        padding: 20px;
    }
    .userorder_de tbody tr td p {
        font-size: 12px;
    }
    .userorder_de tbody tr th {
        background: var(--theme-color);
        color: #fff;
        border-right: 1px solid;
        padding: 5px 12px;
        font-size: 12px;
    }
    .userorder_de h2 {
        font-size: 20px;
    }
    a#cust_btn {position: relative;top: 40px;right: 25px;}
    .userorder_de button.btn-info {background: var(--theme-color);color: #fff !important;border-color: var(--theme-color);padding: 6px 20px;font-size:12px;}
    .order_cancel_form{margin-bottom: .5rem;}
    .order_dtl{display: flex;justify-content: space-between;align-items: center;margin-bottom: 1rem;}
    #already_cancle_btn{opacity: .6;cursor: not-allowed;}
    div#offline_order_confirm_form { margin-bottom: 15px;}
    div#offline_order_confirm_form .modal-body {margin-bottom: 8px;}
    
</style>
<?php
$login_user_id = auth()->user() ? auth()->user()->id :0;
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
$service_level = (isset($order->service_level))?$order->service_level:'';
$sub_total_amount = (isset($order->sub_total_amount))?$order->sub_total_amount:'';
$total_amount = (isset($order->total_amount))?$order->total_amount:'';
$created_at = (isset($order->created_at))?$order->created_at:'';
$created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');
$show_cancel = $show_cancel??0;
$show_cancel_confirm = $show_cancel_confirm??0;
$pnrDetails = (isset($pnrDetails))?$pnrDetails:'';
?>
<div class="centersec">
    <div class="bgcolor viewsec">

        <div class="userorder_de table-responsive">
            <div class="ajax_msg"></div>
            @include('snippets.front.flash')
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec table">
                <tr>
                    <td width="100%" valign="top" class="innersec" style="border:0;padding: 0;">
                        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                            <div class="order_dtl">
                                <h2>Order Details (Order ID: #{{$order->order_no??''}})</h2>

                                <?php if($order->payment_status == 1 && $order->order_type != 3 && $show_cancel == 1){ 

                                    if($order->cancel_request == 0){  ?>
                                        <button type="submit" class="btn2 btn-info edit_pofile_btn cancel_btn" id="cust_btn" data-order_id="{{$order_id}}">Cancel</button>

                                    <?php }else{ ?>

                                        <button type="submit" class="btn2 btn-info edit_pofile_btn cancel_btn" id="already_cancle_btn" data-order_id="{{$order_id}}"disabled>Cancellation Requested</button>
                                    <?php } } ?>
                                    <div class="button_inner">
                                    <?php if($order->payment_status == 1 && $order->order_type == 3 && $order->status != 'SUCCESS' && $order->supplier_id == $login_user_id && $show_cancel_confirm == 1) { ?>
                                        <button style="margin-right:10px;" type="submit" class="btn2 btn-info edit_pofile_btn cancel_btn" id="offline_order_cancel_btn" data-order_id="{{$order_id}}">Cancel</button>
                                        <button type="submit" class="btn2 btn-info edit_pofile_btn cancel_btn" id="offline_order_confirm_btn" data-order_id="{{$order_id}}">Confirm</button>
                                    <?php } ?>
                                    </div>
                                </div>

                                <div class="order_cancel_form" style="display:none">
                                    <form action="" class="form-inline" method="post" id="cancelForm">
                                        {{ csrf_field() }}
                                        <div class="modal-title alert_msg"></div>
                                        <input type="hidden" id="refund_order_id" name="order_id" value="">
                                        <div class="modal-body">
                                            <label class="required"><strong>Reason for Cancellation:</strong></label><br>
                                            <textarea name="reason" id="reason" class="form-control" style="width: 100%;" ></textarea>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn s-btn btn-info sky_blue rounded-full submit_cancel">Submit</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="offline_order_confirm_form" id="offline_order_confirm_form" style="display:none;">
                                    <form action="" class="form-inline" method="post">
                                        {{ csrf_field() }}
                                        <div class="modal-title alert_msg"></div>
                                        <input type="hidden" id="confirm_order_id" name="confirm_order_id" value="">
                                        <div class="modal-body">
                                            <label class="required"><strong>PNR Details:</strong></label><br>
                                            <input type="text" name="pnrDetails" id="pnrDetails" class="form-control" value="{{$pnrDetails}}" />
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn s-btn btn-info sky_blue rounded-full submit_confirm">Submit</button>
                                        </div>
                                    </form>
                                </div>

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
                                        $userData = $order->userData ?? '';
                                        ?>
                                        <p><strong>Order Id: </strong>{{$order->order_no??''}}</p>
                                        <p><strong>Order Date: </strong>{{ date('d M,Y h:i A',strtotime($order->created_at)) }}</p>
                                        <p><strong>Order Type: </strong>{{$order_type??''}}</p>
                                        <?php if($order->order_type ==1){ ?>
                                            <p><strong>Package: </strong>{{$order->package_name??''}}</p>
                                            <p><strong>Package Type: </strong>{{$order->package_type_name??''}}</p>
                                            <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                        <?php } else if($order->order_type == 3) {
                                            $booking_details = [];
                                            $booking_status = '';
                                            if($order->booking_details){
                                                $booking_details = json_decode($order->booking_details,true)??[];
                                                $booking_status = $booking_details['order']['status']??'';
                                            }
                                            ?>
                                            <?php if($booking_status != 'SUCCESS'){ ?>
                                                <p><strong>Booking Status: </strong>{{$booking_status}}</p>
                                            <?php } ?>
                                        <?php } else if($order->order_type == 4){ ?>

                                        <?php }else if($order->order_type == 5){
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
                                            <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                            <?php
                                        }
                                        if($order->order_type ==4){ ?>

                                        <?php } 
                                        if($order->order_type ==5){ ?>

                                        <?php }
                                        $total_amount = 0;
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
                                        <p>
                                            <strong>User: </strong>
                                            @if(!empty($order->userData))
                                            @if(!empty($userData->is_agent == 1))
                                            {{$userData->agentInfo->company_brand??''}}
                                            (Agent)
                                            @else
                                            {{$userData->name??''}}
                                            @endif
                                            @endif
                                        </p>
                                        <p><strong>Email: </strong>{{$userData->email??''}}</p>
                                        @if($userData && $userData->phone)
                                        <p><strong>Phone: </strong>+{{(!empty($userData->country_code))?$userData->country_code:91}}-{{$userData->phone??''}}</p>
                                        @endif
                                        <br />
                                        <hr>
                                        <br />
                                        <p><strong>Traveller: </strong>{{$order->name??''}}
                                        </p>
                                        <p><strong>Email: </strong>{{$order->email??''}}</p>
                                        @if($order->phone)
                                        <?php  $country_code = !empty($order->country_code)?$order->country_code:'91'; ?>
                                        <p><strong>Phone: </strong>+{{$country_code}}-{{$order->phone??''}}</p>
                                        @endif
                                        @if($order->address1)
                                        <p><strong>Address: </strong>{{$order->address1??''}}, {{$order->address2??''}}
                                            @endif
                                            @if($order->city)
                                            &nbsp;&nbsp; <strong>City: </strong>{{$order->city??''}} &nbsp;&nbsp; </p>
                                            @endif
                                            @if($order->state)
                                            <p><strong>State: </strong>{{$order->state??''}} &nbsp;&nbsp; <strong>Country: </strong>{{$order->Country->name??''}} &nbsp;&nbsp; <strong>Zip Code: </strong>{{$order->zip_code??''}}</p>
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
                                            @if(isset($order->pay_type) && !empty($order->pay_type))<p><strong>Payment Type: </strong>{{ ucwords(str_replace("_", " ", $order->pay_type)) }}</p>@endif
                                            <?php if( (!empty($order->discount) && $order->discount > 0) || (!empty($order->fees) && $order->fees > 0) ){ ?>
                                                <p><strong>Sub Total: </strong>{{CustomHelper::getPrice($order->sub_total_amount)??''}}</p>

                                                <?php if(!empty($order->discount) && $order->discount > 0){ ?>
                                                    <p><strong>Discount: </strong>-{{CustomHelper::getPrice($order->discount)??''}}</p>
                                                <?php } ?>

                                                <?php if(!empty($order->discount_coupon) && $order->discount_coupon > 0){ ?>
                                                    <p><strong>Coupon Discount: </strong>-{{CustomHelper::getPrice($order->discount_coupon)??''}}</p>
                                                <?php } ?>                                

                                                <?php if(!empty($order->fees) && $order->fees > 0){ ?>
                                                    <p><strong>Fees: </strong>{{CustomHelper::getPrice($order->fees)??''}}</p>
                                                <?php } ?>
                                            <?php } ?>


                                            <?php if($order->order_type == 1 && !empty($order->gst_amount > 0)){ ?>
                                                <p><strong>GST Amount: </strong>{{number_format($order->gst_amount)??''}}</p>
                                            <?php } ?>

                                            <?php if($order->order_type == 1 && !empty($order->tcs_amount > 0)){ ?>
                                                <p><strong>TCS Amount: </strong>{{number_format($order->tcs_amount)??''}}</p>
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
                                                <?php if(!empty($booking_details['gstInfo'])){ ?>
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

                                                <td colspan="3"><p><strong>Comment: </strong>{!!nl2br($order->comment)!!}</p></td>
                                            </tr>
                                        </table>
                                        @if(isset($itineraries) && (isset($booking_status)) && !empty($booking_status))
                                        <h2>
                                            Flight Booking Details
                                        </h2>
                                        {!!$itineraries!!}
                                        @endif 

                                        @if(isset($cab_itineraries))
                                        <h2>Cab Booking Details</h2>
                                        {!!$cab_itineraries!!}
                                        @endif
                                    </td>
                                </tr>
                            </table> 
                        </div>
                    </div>
                </div>