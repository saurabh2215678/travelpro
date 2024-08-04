    <?php
    $payment_status = (isset($order->payment_status))?$order->payment_status:'';
    ?>
    <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
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
                <?php } else if($order->order_type == 3) {
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
                <?php } else if($order->order_type == 5){
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
                <p><strong>Phone: </strong>+{{(!empty($userData->country_code))?$userData->country_code:91}}-{{$userData->phone??''}}</p>
                @endif
                <hr>
                <p>
                    <strong>Traveller: </strong>{{$order->name??''}}
                </p>
                <p><strong>Email: </strong>{{$order->email??''}}</p>
                @if($order->phone)
                <p><strong>Phone: </strong>+{{$order->country_code??91}}-{{$order->phone??''}}</p>
                @endif
                <p>
                    @if($order->address1)
                    <strong>Address: </strong>{{$order->address1??''}}, {{$order->address2??''}}
                    @endif
                    @if($order->city)
                    &nbsp;&nbsp; <strong>City: </strong>{{$order->city??''}} &nbsp;&nbsp; 
                    @endif
                </p>
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
                    <tr>
                        <td colspan="3"><p><strong>Customer Comment: </strong>{!!nl2br($order->comment)!!}</p></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p>
                                <strong>Admin Notes: </strong> 
                                <div id="admin_notes_html">{!!nl2br($order->admin_notes)??''!!}</div>
                            </p>
                            <div style="display: none;" id="admin_notes_input">
                                <textarea name="admin_notes" id="admin_notes" rows="3" style="width: 100%">{!!$order->admin_notes!!}</textarea>
                            </div>
                        </td>
                        <td>
                        <div class="text-right inl_btn ">
                            <button class="clickbtn" id="admin_notes_edit" style="curser:pointer;border:0;background:#0000;"> <i class="fa fa-pencil"></i></button>
                            <div style="display: none;" id="admin_notes_actions">
                                <button style="" type="submit" class="btn btn-success admin_notes_save">Save</button>
                                <button type="submit" class="btn_admin2 cancel_btn admin_notes_cancel">Cancel</button>
                            </div>
                        </div>
                        </td>
                    </tr>
                </table>