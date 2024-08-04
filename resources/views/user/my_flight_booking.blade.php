@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
.theme_footer:before {z-index: -1;}
.booking-lists thead tr th {border-right: 1px solid #dee2e6;background: var(--theme-color);color: #fff;text-align: left;font-size: 12px;line-height: normal;}
.user_profile_inner .right_info .top_info {border-bottom: 0;padding-bottom: 8px;}
#emailModal{display: none;}
.modal.show#emailModal { position: fixed; top: 0; left: 0; background-color: #0000005c; width: 100%; height: 100%; z-index: 99999; display: grid; place-items: start; transition: 0.5s;padding-top:35px;align-items: center;justify-items: center;}
.modal.show#emailModal .modal-content {width: 20rem;}
.modal.show#emailModal .modal-content #email_action {display: flex;justify-content: center;column-gap: 1rem;margin-top: 1rem;}
.modal.show#emailModal .modal-content #email_action button { background: var(--theme-color);border-color: var(--theme-color);}
.modal.show#emailModal .modal-content #email_action button:hover { background: var(--secondary-color);border-color: var(--secondary-color); color:#fff !important;}
.modal.show#emailModal .modal-content input { color: #555; background-color: #fff; font-size:14px; border: 1px solid #ccc;border-radius: 4px;padding: 4px 10px;}
.modal.show#emailModal .modal-content .modal-title {font-weight: 600;}
.modal.show#emailModal .modal-content button.close { color: #fff; float: right; font-size: 20px; font-weight: 700; position: absolute; right: -15px; top: -15px; background: #009688; width: 2rem; text-align: center; height: 2rem; }
.modal.show#emailModal .modal-content #email_action a {padding: 7px 18px;}
</style>
@endslot
<?php
    $BackUrl = CustomHelper::BackUrl();
    $user_details = auth()->user();
    // $order = (request()->has('order'))?request()->order:'confirmed';
    // $order = old('order',$order);

    //$package_id = (request()->has('package_id'))?request()->package_id:'';
    //$package_id = old('package_id',$package_id);

    //$search = (request()->has('search'))?request()->search:'';
    //$search = old('search',$search);

    $order_id = (request()->has('order_id'))?request()->order_id:'';
    $order_id = old('order_id',$order_id);

    //$name = (request()->has('name'))?request()->name:'';
    //$name = old('name',$name);

    $order = (request()->has('order'))?request()->order:'confirmed';
    $order = old('order',$order);

    //$email = (request()->has('email'))?request()->email:'';
    //$email = old('email',$email);

    //$phone = (request()->has('phone'))?request()->phone:'';
    //$phone = old('phone',$phone);

    $order_status = (request()->has('order_status'))?request()->order_status:'';
    $order_status = old('order_status',$order_status);
    if(empty($order_status)){
        $order_status =[];// ['new','accepted'];
    }

    $range = (request()->has('range'))?request()->range:'custom';
    $range = old('range', $range);

    $from = (request()->has('from'))?request()->from:'';
    if(isset($from_date)) {
        $from = CustomHelper::DateFormat($from_date,'d/m/Y','Y-m-d');
    }
    $from = old('from', $from);

    $to = (request()->has('to'))?request()->to:'';
    if(isset($to_date)) {
        $to = CustomHelper::DateFormat($to_date,'d/m/Y','Y-m-d');
    }
    $to = old('to', $to);

    $todays_orders = (request()->has('todays_orders'))?request()->todays_orders:'';
    $yesterdays_orders = (request()->has('yesterdays_orders'))?request()->yesterdays_orders:'';
    $all_orders = (request()->has('all_orders'))?request()->all_orders:'';
    $inventory_id = (request()->has('inventory_id'))?request()->inventory_id:'';

    if(!empty($todays_orders) || !empty($yesterdays_orders) || !empty($all_orders)){
        $order_status = [];
    }

    $order_type = (request()->has('order_type'))?request()->order_type:'';
    $order_type = old('order_type',$order_type);
    $flight_type = 'online';
    if($order_type == 'bookings') {
        // $flight_type = (request()->has('flight_type'))?request()->flight_type:'';
        // $flight_type = old('flight_type',$flight_type);
        $flight_type = 'offline';
    }

    $order_type_arr = config('custom.order_type');
    $order_status_category_arr = config('custom.order_status_category_arr');
    $range_filters = config('custom.range_filters');
    $is_agent = Auth::user()->is_agent??0;
?>
<section>
        <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">{{$page_heading??''}} ({{$uesrOrders->total()}})</h1>
                        </div>
                    </div>
                </div>

                <div class="tab_block toptab">
                    <ul class="nav nav-tabs">
                        <?php /*if(CustomHelper::isOnlineBooking('flight')) {
                            $flight_search_data = $search_data;
                            $flight_search_data['order_type'] = 'pending';
                            ?>
                            <li class="nav-item">
                                <a <?php if($order_type=='pending'){echo 'class="active"' ;}?> href="{{route('user.myFlightBooking',$flight_search_data)}}">Pending</a>
                            </li>
                        <?php }*/ ?>

                        <?php if(CustomHelper::isOnlineBooking('flight')) {
                            $flight_search_data = $search_data;
                            $flight_search_data['order_type'] = 'route';
                            ?>
                            <li class="nav-item">
                                <a <?php if($order_type=='route'){echo 'class="active"' ;}?> href="{{route('user.flight_route',$flight_search_data)}}">Route</a>
                            </li>
                        <?php } ?>

                        <?php if(CustomHelper::isOnlineBooking('flight')) {
                            $flight_search_data = $search_data;
                            $flight_search_data['order_type'] = 'inventory';
                            ?>
                            <li class="nav-item">
                                <a <?php if($order_type=='inventory'){echo 'class="active"' ;}?> href="{{route('user.flight_inventory',$flight_search_data)}}">Inventory</a>
                            </li>
                        <?php } ?>

                        <?php if(CustomHelper::isOnlineBooking('flight')) {
                            $flight_search_data = $search_data;
                            $flight_search_data['order_type'] = 'failed';
                            ?>
                            <li class="nav-item">
                                <a <?php if($order_type=='failed'){echo 'class="active"' ;}?> href="{{route('user.myFlightBooking',$flight_search_data)}}">Failed</a>
                            </li>
                        <?php } ?>

                        <?php if(CustomHelper::isOnlineBooking('flight')) {
                            $flight_search_data = $search_data;
                            $flight_search_data['order_type'] = 'bookings';
                            ?>
                            <li class="nav-item">
                                <a <?php if($order_type=='bookings'){echo 'class="active"' ;}?> href="{{route('user.myFlightBooking',$flight_search_data)}}">Bookings</a>
                            </li>
                        <?php } ?>                        
                    </ul>
                </div>
            <?php if($is_agent == 1){ ?>
            <div class="booking__search">
            <form class="form-inline" method="GET">
                <input type="hidden" name="order_type" value="{{$order_type}}">
                <?php if($order_type != 1 && $order_type != 3 && $order_type != 4 && $order_type != 5 && $order_type != 6 ){ ?>

                <?php } ?>
                <div class="row">
                <div class="w-1/6{{$errors->has('order_status')?' has-error':''}}">
                    <label for="FormControlSelect1">Order Status:</label><br />
                    <select name="order_status[]" id="customSelect3" class="form-control admin_input1 select2 myselect" multiple="multiple">
                        <option value="">Select Order Status</option>
                        @if(isset($order_status_category_arr) && !empty($order_status_category_arr))
                            @foreach($order_status_category_arr as $k => $v)
                                <option value="{{$k}}" {{(!empty($order_status) && in_array($k,$order_status))?'selected':''}} >{{$v}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>


                <?php /*<div class="col-md-2">
                    <label> Search :</label><br/>
                    <input type="text" name="search" class="form-control admin_input1" value="{{$search}}">
                </div> */?>

                <div class="w-1/6">
                    <label>Booking ID:</label><br/>
                    <input type="text" name="order_id" class="form-control admin_input1" value="{{$order_id}}">
                </div>


                <div class="w-1/6{{$errors->has('range')?' has-error':''}} rangeDiv">
                <label for="FormControlSelect1">Date Range</label><br/>
                <select name="range" class="form-control admin_input1 range select2">
                    <option value="">--Select Range--</option>
                    <?php if(isset($range_filters) && !empty($range_filters)){
                          unset($range_filters['tomorrow']);
                          unset($range_filters['next_week']); ?>
                            @foreach($range_filters as $k => $v)
                                <option value="{{$k}}" {{(!empty($range) && $range == $k)?'selected':''}}>{{$v}}</option>
                            @endforeach
                    <?php } ?>
                    </select>
                </div>
                <div class="w-1/6{{$errors->has('from')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
                    <label for="FormControlSelect1">From Date</label><br/>
                    <input type="text" name="from" class="form-control from_date" placeholder="From Date"
                    value="{{$from}}" autocomplete="off">
                </div>
              
                <div class="w-1/6{{$errors->has('to')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
                    <label for="FormControlSelect1">To Date</label><br/>
                    <input type="text" name="to" class="form-control to_date" placeholder="To Date"
                    value="{{$to}}" autocomplete="off">
                </div>

                
                <div class="w-1/6 flex search-block">
                    <?php /*<button type="submit" name="todays_orders" value="todays_orders" class="btn btn-info days_tab" title="Today's Orders" @if($todays_orders == 'todays_orders') id="active" @endif>Today's Orders</button>
                    <button type="submit" name="yesterdays_orders" value="yesterdays_orders" class="btn btn-info days_tab" title="Yesterday's Orders" @if($yesterdays_orders == 'yesterdays_orders') id="active" @endif> Yesterday's Orders</button>
                    <button type="submit" name="all_orders" value="all_orders" class="btn btn-info days_tab" title="All Orders" @if($all_orders == 'all_orders') id="active" @endif> All Orders</button> */ ?>
                     <label for="FormControlSelect1"></label><br/>
                     <input type="hidden" name="inventory_id" value="{{$inventory_id}}">
                    <button type="submit" class="btn s-btn btn-info sky_blue rounded-full">Search</button>
                    <a href="{{route('user.myFlightBooking',['order_type'=>$order_type])}}" class="btn2 btn-info edit_pofile_btn">Reset</a>
                </div> 
              </div>
            </form>
        </div>
    <?php } ?>

            @include('snippets.front.flash')
                <?php if(!empty($uesrOrders) && $uesrOrders->count() > 0){ ?>
                    <div class="booking-lists table-responsive mt-2">
                        <form method="POST" action="{{route('user.bulk_action')}}" id="order_listing" class="form-inline" onSubmit="return validateOrderForm()" >
                    {{ csrf_field() }}
                    <table class="table table-bordered table-sm text-sm">
                        <thead>
                            <tr>
                                @if($flight_type=='offline')
                                <th class="">
                                    <input type="checkbox" name="check_all" value="1" title="Select All">
                                </th>
                                @endif
                                <th>Booking Date</th>
                                <th style="width: 13%;">
                                    Booking Order No<br>
                                    (Trip Type)
                                </th>
                               <?php //<th>Description</th> ?>
                                <th>Sector / Destination / Location</th>
                                <th>Date of Travel</th>
                                <th>Total Amount</th>
                                <th>Amount Paid</th>
                                <th>Amount Due</th>
                                @if($is_agent==1)
                                <th>Markup</th>
                                <th>Discount/ Comm.</th>
                                @endif
                                <th>Payment/ <br>API Status</th>
                                <th>Order Status</th>
                                <th>Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php 
                           $grand_total_amount = 0;
                           $agent_markup = 0;
                           $total_dis = 0;
                           $total_comm = 0;
                           $rejected_id = CustomHelper::getOrderStatus('cancelled');
                        foreach($uesrOrders as $order){

                            $payment_status = $order->payment_status??'';
                            $orders_status = CustomHelper::showOrderStatus($order->orders_status)??'';
                        
                            $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';
                            $total_amount = $order->total_amount??0;
                            if($orders_status == 'Confirmed') {
                                $grand_total_amount = $grand_total_amount+$total_amount;
                            }

                            $amount_paid = 0;
                            $amount_due = 0;

                            $partial_amount = $order->partial_amount??0;
                            $payment_due = 0;
                            if($partial_amount) {
                                $payment_due = $total_amount - $partial_amount;
                            }
                            if($partial_amount && $total_amount > $partial_amount && $order->pay_type != 'book_without_payment'){
                                if($payment_status == 1){
                                    $amount_paid =  $partial_amount;
                                }
                            }
                            if($payment_due == 0){
                                if($payment_status == 1){
                                    $amount_paid = $total_amount;
                                }elseif($payment_status == 2){
                                    $amount_due = $total_amount;
                                }elseif($payment_status == 3){
                                    $amount_paid = $total_amount;
                                }elseif($payment_status == 0){
                                    $amount_due = $total_amount;
                                }
                            }else {
                                if($order->pay_type != 'book_without_payment'){
                                    if($payment_status == 1){
                                        $amount_due = $payment_due;
                                    }elseif($payment_status == 2){
                                        $amount_due = $total_amount;
                                    }elseif($payment_status == 3){
                                        // $amount_due = $total_amount;
                                    }elseif($payment_status == 0){
                                        $amount_due = $total_amount;

                                    }
                                }
                            }                            
                            ?>

                            <?php
                            if(!empty($order->commission) && $order->commission > 0) {
                                if($orders_status == 'Confirmed') {
                                    $total_comm = $total_comm+ $order->commission;
                                }
                            }
                            ?>
                            <tr>
                                @if($flight_type=='offline')
                                <td class="">
                                    <input type="checkbox" name="booking_id[]" value="{{$order->id}}">
                                </td>
                                @endif
                               <td>{{CustomHelper::DateFormat($order->created_at, 'd M,Y h:i A')}}</td>
                               <td>
                                <?php
                                $show_order = true;
                                if($order->payment_status == 1) {
                                    if($order->order_type==3) {
                                        if(!empty($order->bookingId)) {
                                            $booking_details = json_decode($order->booking_details,true)??[];
                                            $booking_status = $booking_details['order']['status']??'';
                                            if($booking_status == 'SUCCESS') {
                                                $show_order = false;
                                                ?>
                                                <a class="view_cancel" href="<?php echo route('user.cancel_flight', [$order->id]) ?>" title="Cancel Flight" >{{$order->order_no??''}} <i class="fa fa-external-link"></i></a>
                                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                                @if($show_order)
                                {{$order->order_no??''}}
                                @endif


                                <br />
                                ({{$order_type??''}})
                            </td>

                              <?php /*<td>
                                    <?php if($order->order_type == 1){
                                <p><strong>Package: </strong>{{$order->package_name??''}}</p>
                                <p><strong>Package Type: </strong>{{$order->package_type_name??''}}</p>
                                <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                <?php } else if($order->order_type == 4){
                                    if($order->booking_details){
                                        $booking_details = (array)json_decode($order->booking_details);
                                    }

                                <p><strong>Itinerary: </strong> {{$booking_details['itinerary']??''}}</p>
                                 <p><strong>Pickup Date: </strong>{{$booking_details['trip_date']??''}}</p>
                                 <p><strong>Car Type: </strong>{{$booking_details['cab_name']??''}}</p>
                                 <p><strong>Trip Type: </strong>{{$booking_details['trip_type']??''}}</p>
                                <?php }else if($order->order_type == 5){ //Hotel
                                    $booking_details = [];
                                    if($order->booking_details){
                                        $booking_details = (array)json_decode($order->booking_details);
                                    }

                                    <p><strong>Hotel: </strong>{{$booking_details['hotel_name']??''}}</p>
                                    <p><strong>Room Type: </strong>{{$booking_details['room_name']??''}}</p>
                                    <?php if(isset($booking_details['plan_name']) && !empty($booking_details['plan_name'])){
                                    <p><strong>Room Plan: </strong>{{$booking_details['plan_name']??''}}</p>
                                    <?php }
                                    <p><strong>Checkin: </strong>{{$booking_details['checkin']??''}}</p>
                                    <p><strong>Checkout: </strong>{{$booking_details['checkout']??''}}</p>
                                    <p><strong>Room(s): </strong>{{$booking_details['inventory']??''}}</p>
                                    <p><strong>Adult: </strong>{{$booking_details['adult']??''}}</p>
                                    <?php if(isset($booking_details['child']) && !empty($booking_details['child'])){
                                    <p><strong>Child: </strong>{{$booking_details['child']??''}}</p>
                                    <?php }
                                    <?php if(isset($booking_details['infant']) && !empty($booking_details['infant'])){
                                    <p><strong>Infant: </strong>{{$booking_details['infant']??''}}</p>
                                    <?php }
                                 <?php } else if($order->order_type == 6){
                                <p><strong>Activity: </strong>{{$order->package_name??''}}</p>
                                <p><strong>Activity Type: </strong>{{$order->package_type_name??''}}</p>
                                <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                <?php }
                                <?php
                                $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
                                if(!empty($price_category_choice_record_arr)) {
                                    foreach($price_category_choice_record_arr as $pccr) {
                                        $input_label = $pccr['input_label']??'';
                                        $input_value = $pccr['input_value']??0;
                                        $input_price = $pccr['input_price']??0;
                                        $sub_total = $input_value*$input_price;

                                        <p><strong>{{$input_label}}:</strong> {{$input_value}} X {{CustomHelper::getPrice($input_price)}} = {{CustomHelper::getPrice($sub_total)}}</p>
                                        <?php
                                    }
                                }

                                <?php if( (!empty($order->discount) && $order->discount > 0) || (!empty($order->fees) && $order->fees > 0) ){
                                <p><strong>Sub Total: </strong>{{CustomHelper::getPrice($order->sub_total_amount)??''}}</p>
                                <?php if(!empty($order->discount) && $order->discount > 0){
                                <p><strong>Discount: </strong>{{CustomHelper::getPrice($order->discount)??''}}</p>
                                <?php }
                                <?php if(!empty($order->fees) && $order->fees > 0){
                                <p><strong>Fees: </strong>{{CustomHelper::getPrice($order->fees)??''}}</p>
                                <?php }
                                <?php }
                                </td> */ ?>

                                <td>
                                   <?php
                                   $booking_details = [];
                                   if($order->booking_details){
                                    $booking_details = json_decode($order->booking_details, true);
                                }
                                if($order->order_type==1){
                                    $package_details = $order->Package;
                                    $destination_des = !empty($package_details->packageDestination)?$package_details->packageDestination->name:'';
                                    echo $destination_des;
                                }
                                else if($order->order_type==6){
                                    $package_details = $order->Package;
                                    $destination_des = !empty($package_details->packageDestination)?$package_details->packageDestination->name:'';
                                    echo $destination_des;
                                }
                                else if($order->order_type==5){
                                    $destination_name = $booking_details['destination_name']??'';
                                    echo $destination_name;
                                } else if($order->order_type==8){
                                    $city_name = $booking_details['city_name']??'';
                                    $location_name = $booking_details['location_name']??'';

                                    echo $city_name.' '.$location_name;
                                }
                                else if($order->order_type==3){
                                    echo $order->name;
                                    if($order->flight_details) {
                                        $flight_details = json_decode($order->flight_details,true)??[];
                                        $routeInfos = $flight_details['info']['searchQuery']['routeInfos']??[];
                                        $routeInfos_arr = [];
                                        if($routeInfos) {
                                            foreach($routeInfos as $routeInfo) {
                                                $routeInfos_arr[] = $routeInfo['fromCityOrAirport']['code'].'-'.$routeInfo['toCityOrAirport']['code'];
                                            }
                                            if($routeInfos_arr) {
                                                echo '<br />('.implode(', ', $routeInfos_arr).')';
                                            }
                                        }
                                    }
                                }
                                else if($order->order_type==4){
                                    $destination_cab = $booking_details['destination']??'';
                                    echo $destination_cab;
                                }
                                ?>
                                </td>

                                <td>
                                    <?php
                                    $tripDateFormat = 'M dS Y';
                                    if($order->order_type == 1) {
                                        $tripDateFormat = 'M dS Y';
                                    } else if($order->order_type == 4) {
                                      $tripDateFormat = 'M dS Y h:i A';
                                    } else if($order->order_type == 6) {
                                      $tripDateFormat = 'M dS Y h:i A';
                                    }
                                    ?>
                                    {{CustomHelper::DateFormat($order->trip_date, $tripDateFormat)}}</td>
                                <td>{{CustomHelper::getPrice($total_amount)}}</td>
                                <td>
                                    @if($total_amount == $amount_paid)
                                    <span style="color:green">{{CustomHelper::getPrice($amount_paid)}}</span>
                                    @elseif($amount_paid)
                                    <span style="color:orange">{{CustomHelper::getPrice($amount_paid)}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($amount_due)
                                    <span style="color:{{($payment_status==0)?'orange':'red'}}">{{CustomHelper::getPrice($amount_due)}}</span>
                                    @endif
                                </td>
                                @if($is_agent==1)
                                <td>
                                    <?php
                                    $markup = $order->markup??0;
                                    $inventory_data = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList'][0]['inventory_data']??[];
                                    $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
                                    if($inventory_data && $travellerInfos) {
                                        foreach($travellerInfos as $traveller) {
                                            $pt = $traveller['pt']??'';
                                            if($pt) {
                                                switch ($pt) {
                                                    case 'ADULT':
                                                            $markup += (($inventory_data['adult_price']??0)-($inventory_data['agent_adult_price']??0));
                                                        # code...
                                                        break;
                                                    case 'CHILD':
                                                            $markup += (($inventory_data['child_price']??0)-($inventory_data['agent_child_price']??0));
                                                        # code...
                                                        break;
                                                    case 'INFANT':
                                                            $markup += (($inventory_data['infant_price']??0)-($inventory_data['agent_infant_price']??0));
                                                        # code...
                                                        break;   
                                                    default:
                                                        # code...
                                                        break;
                                                }
                                            }
                                        }
                                    }
                                    if($markup) {
                                        if($orders_status == 'Confirmed') {
                                            $agent_markup = $agent_markup + $markup;
                                        }
                                     ?>
                                        {{CustomHelper::getPrice($markup,'',true)}}
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if(!empty($order->discount) && $order->discount > 0) { 
                                        if($orders_status == 'Confirmed') {
                                            $total_dis = $total_dis+ $order->discount;
                                        }
                                        ?>
                                        {{CustomHelper::getPrice($order->discount)}}(D)
                                    <?php } ?>
                                    <br>

                                    <?php if(!empty($order->commission) && $order->commission > 0){ 

                                     ?>
                                {{CustomHelper::getPrice($order->commission,'',true)}}(C)
                                <?php } ?>
                                   
                                </td>
                                @endif
                            </td>
                        
                            <td>
                                {!!CustomHelper::showPaymentStatus($order->payment_status??0)!!}<br>
                                @if($order->order_type == 3)
                                <!-- FLIGHT STATUS -->
                                    {{$order->status??''}}
                                @endif
                                <?php 
                                if( ($payment_status == 0 || $payment_status == 2) && $order->orders_status != $rejected_id ){ //&& $order->status == 'ON_HOLD' ?>
                                    <a class="user_paynow_btn" href="{{route('pay_now', [$order->order_no])}}" title="Pay Now">Pay Now</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php //{{$order->status??''}} ?>
                                <?php 
                                //$order_status_category = CustomHelper::showEnquiryMaster($order->orders_status) ?? '';

                                $show_color = CustomHelper::showOrderStatusColor($order->orders_status)??'';
                                if($order->cancel_request == 1){
                                    $show_color = '<strong><span style="color:orange">Cancellation Processing</span></strong>';
                                }
                                ?>
                                {!! $show_color ?? '' !!}
                            </td>
                            <td>{{CustomHelper::getPaymentGatewayName($order->method)}}</td>
                            <td class="iconlist">
                                <a href="javascript:void(0);" data-src="<?php echo route('user.orderDetails', [$order['id'],'show_cancel_confirm'=>1]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>

                                <?php
                                if($order->payment_status == 1){
                                    if($order->order_type==1){
                                        if($order->OrderServiceVoucher){ ?>
                                            <a href="<?php echo route('user.package_voucher_view', [$order['id']]) ?>" title="Package Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                            <?php
                                        }
                                    }
                                    if($order->order_type==3) {
                                        if($order->bookingId) {
                                            $booking_details = json_decode($order->booking_details,true)??[];
                                            $booking_status = $booking_details['order']['status']??'';
                                            if($booking_status == 'SUCCESS') {
                                                ?>
                                                <a href="<?php echo route('user.flight_voucher_view', [$order['id']]) ?>" title="Flight Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                                <?php
                                            }
                                        }
                                    }
                                    if($order->order_type==4){
                                        if($order->OrderServiceVoucher){ ?>
                                            <a href="<?php echo route('user.cab_voucher_view', [$order['id']]) ?>" title="Cab Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                            <?php
                                        }
                                    }if($order->order_type == 8){
                                        if($order->OrderServiceVoucher){ ?>
                                            <a href="<?php echo route('user.rental_voucher_view', [$order['id']]) ?>" title="Rental Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                            <?php
                                        }
                                    }
                                    if($order->order_type==5){
                                        if($order->OrderServiceVoucher){ ?>
                                            <a href="<?php echo route('user.hotel_voucher_view', [$order['id']]) ?>" title="Hotel Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                        <?php }
                                    }if($order->order_type==6){
                                        if($order->OrderServiceVoucher){ ?>
                                            <a href="<?php echo route('user.activity_voucher_view', [$order['id']]) ?>" title="Activity Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                            <?php
                                        }
                                    }
                                }/*else if($order->payment_status == 2){ ?>
                                    <a href="<?php echo route('pay_now', [$order['order_no']]) ?>" title="Retry Payment" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive"><i class="fa-solid fa-repeat"></i></a>
                                <?php }*/ ?>
                            </td>
                        </tr>
                    <?php } ?>

                    @if($flight_type=='offline')
                    <tr>
                        <td colspan="11"></td>
                        <td align="center">
                            <a href="javascript:void(0);" class="bulk_action" data-value="print" title="Print"><i class="fas fa-print"></i></a>
                        </td>
                        <td align="center">
                            <a href="javascript:void(0);" class="bulk_action" data-value="email" title="Email"><i class="fas fa-envelope"></i></a>
                        </td>
                        <td align="center">
                            <a href="javascript:void(0);" class="bulk_action" data-value="download" title="Download"><i class="fas fa-download"></i></a>
                        </td>
                    </tr>
                    @endif

                   
                </tbody>
            </table>
            <input type="hidden" name="back_url" value="{{$BackUrl}}">
            <input type="hidden" name="action" id="bulk_action" value="">

            <div class="modal fade" id="emailModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Enter Email Address</h4>
                        </div>
                        <div class="modal-body">
                            <label>To:</label>
                            <input type="text" name="email" placeholder="Enter Email Address" value="">
                        </div>
                        <div class="modal-footer" id="email_action">
                            <a href="javascript:void(0);" title="Flight Voucher" class="btn btn-success cab_view_fancy btn_admin" id="sendMail"> Send Mail </a>
                        </div>
                        <div class="modal-footer" id="email_processing" style="display: none;">
                            <button type="button" class="btn btn-success" disabled>Processing...</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        </div>
        <?php if($is_agent == 1){ ?>
 <table width="20%">
                <tr>
                    <td>Total Sale </td>
                    <td style="text-align: right;">{{CustomHelper::getPrice($grand_total_amount,'',true) ?? 0.00}}</td>
                </tr>
              
                <tr>
                    <td> Total Markup  </td>
                    <td style="text-align: right;">{{CustomHelper::getPrice($agent_markup,'',true) ?? 0.00}}</td>
                </tr>
                <tr>
                    <td> Total Commission  </td>
                    <td style="text-align: right;">{{CustomHelper::getPrice($total_comm,'',true) ?? 0.00}}</td>
                </tr>
                <tr>
                    <td>Discount  </td>
                    <td style="text-align: right;">{{CustomHelper::getPrice($total_dis,'',true) ?? 0.00}}</td>
                </tr>
                <tr>
                    <td><strong>Net Profit</strong>   </td>
                    <td style="text-align: right;">{{CustomHelper::getPrice(($agent_markup+$total_comm+$total_dis),'',true) ?? 0}}</td>
                </tr>

            </table>
        <?php } ?>
                    <div class="pagination-wrapper hotel-pagination mt-3 ml-0">
                        {{ $uesrOrders->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                         </div>
                    </div>

            <?php }else{ ?>
            <div class="alert_not_found">No Booking data found.</div>
            <?php } ?>
        </div>
      </div>
  </section>
@slot('bottomBlock')

<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">

    $( ".to_date, .from_date" ).datepicker({
        dateFormat:'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        yearRange: "-90:+01"

    });

    $('.range').on('change', function() {
        if (this.value == 'custom') {
            $('.dateDiv').removeClass('hide');
        } else {
            $('.dateDiv').addClass('hide');
            $('input[name=from]').val('');
            $('input[name=to]').val('');
        }
    });
$(document).on('click','.cancel_flight',function(){
    var _this = $(this);
    var order_id = _this.attr('data-id');
    if(order_id) {
        var conf = confirm('Are you sure to cancel the ticket?');
        if(conf) {
            var _token = '{{csrf_token()}}';
            $.ajax({
                url: "{{route('user.cancelFlight')}}",
                type: "POST",
                data: {order_id},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                beforeSend: function () {

                },
                success: function(resp) {
                    if(resp.success) {
                        window.location.reload();
                    } else if(resp.message) {
                        alert(resp.message);
                    }
                }
            });
        }
    }
});

$(document).on("click","#offline_order_cancel_btn",function(){
    var order_id = $(this).attr('data-order_id');
    $('#refund_order_id').val(order_id);
    // $(".order_status_form").hide();
    if ($(".order_cancel_form").is(":hidden")) {
        $(".order_cancel_form").show();
    } else {
        $(".order_cancel_form").hide();
    }
});
$(document).on("click", ".submit_cancel", function() {
    var order_id = $("#refund_order_id").val();
    var reason = $("#reason").val();
    if (!reason) {
        alert("Please add reason for order cancellation.");
        $("#reason").focus();
        return false;
    }else{
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url('user/cancelOfflineFlight') }}",
            type: "POST",
            data: {
                order_id: order_id,
                reason: reason
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend: function() {
                $(".alert_msg").show();
                $(".submit_cancel").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
                $(".submit_cancel").attr("disabled", true);
            },
            success: function(response) {
                $(".submit_cancel").html('Submit');
                $(".submit_cancel").attr("disabled", false);
                $(".alert_msg").html(response.msg).hide();
                $(".alert_msg").html(response.msg).fadeIn();
                setTimeout(function() { $(".order_cancel_form").fadeOut(); }, 3000);
                $("#offline_order_cancel_btn").hide();
            }
        });
    }
});
$(document).on("click","#offline_order_confirm_btn",function(){
    var order_id = $(this).attr('data-order_id');
    $('#confirm_order_id').val(order_id);
    // $(".order_status_form").hide();
    if ($("#offline_order_confirm_form").is(":hidden")) {
        $("#offline_order_confirm_form").show();
    } else {
        $("#offline_order_confirm_form").hide();
    }
});
$(document).on("click", ".submit_confirm", function() {
    var order_id = $("#confirm_order_id").val();
    var pnrDetails = $("#pnrDetails").val();
    if (!pnrDetails) {
        alert("Please enter PNR details for order confirmation.");
        $("#pnrDetails").focus();
        return false;
    } else {
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url('user/updatePNRDetails') }}",
            type: "POST",
            data: {
                order_id: order_id,
                pnrDetails: pnrDetails
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend: function() {
                $("#offline_order_confirm_form .alert_msg").show();
                $("#offline_order_confirm_form .submit_confirm").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
                $("#offline_order_confirm_form .submit_confirm").attr("disabled", true);
            },
            success: function(response) {
                $("#offline_order_confirm_form .submit_confirm").html('Submit');
                $("#offline_order_confirm_form .submit_confirm").attr("disabled", false);
                $("#offline_order_confirm_form .alert_msg").html(response.msg).hide();
                $("#offline_order_confirm_form .alert_msg").html(response.msg).fadeIn();

                $("#offline_order_confirm_btn .modal-body").hide();
                $("#offline_order_confirm_btn .text-center").hide();

                setTimeout(function() {
                    $("#offline_order_confirm_form").fadeOut();
                    window.location.reload();
                }, 1500);
                $("#offline_order_confirm_btn").hide();
            }
        });
    }
});

$(document).on('click',"input[name=check_all]",function(){
    var check_all_checked = $(this).is(':checked');
    $("input[name='booking_id[]']").each(function(){
        $(this).prop( "checked", check_all_checked);
    });
});

$(document).on('click','.bulk_action',function(){
    var action = $(this).attr('data-value');
    $('#bulk_action').val(action);
    setTimeout(function(){
        $('#order_listing').submit();
    },300);
});
function validateOrderForm() {
    if($("input[name='booking_id[]']:checked").length == 0) {
        alert("Please select one of the booking");
        return false;
    } else {
        var action = $('#bulk_action').val();
        if(action=='email') {
            // alert('hi')
            $("#emailModal").addClass("show");
            $('#emailModal').removeClass('hide');
            return false;
        } else {
            return true;
        }
    }
}

$(document).ready(function() {
    $('.close').click(function() {
        $("#emailModal").removeClass("show");
    });
});

$(document).on("click","#sendMail",function(e){
    var formData = $('#order_listing').serialize();
    var _token = '{{ csrf_token() }}';
    $.ajax({
        url: "{{ route('user.bulk_action')}}",
        type: "POST",
        data: formData,
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        beforeSend: function () {
            $('#email_action').hide();
            $('#email_processing').show();
        },
        success: function(resp){
            if(resp.success) {
                alert(resp.message);
                $("#emailModal").removeClass("show");
            } else if(resp.message) {
                alert(resp.message);
            } else {
                alert('Something went wrong, please try again');
            }
            $('#email_action').show();
            $('#email_processing').hide();            
        }
    });
});
</script>
<script type="text/javascript">
    $('.select2').select2({ placeholder: "Select Date", allowClear: true });
// ============== Select Box Start ============

    $('.myselect').select2({closeOnSelect: true,  placeholder: "Please Select",}).on("change", function(e) {

        var counter = $(this).next('.select2-container').find(".select2-selection__choice").length;
        if(counter > 2){
          if($(this).next('.select2-container').find('.counter').length <= 0){
            $(this).next('.select2-container').find('.select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter"> ('+counter+' selected)</div>');
        }else{
            $(this).next('.select2-container').find('.counter').text(`(${counter} selected)`);
        }
    }else{
      $(this).next('.select2-container').find('.counter').remove();
  }
});
    $(document).ready(function() {
        $( ".to_date, .from_date" ).datepicker({
            dateFormat:'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            yearRange: "-90:+01"

        });

        $('.range').on('change', function() {
            if (this.value == 'custom') {
                $('.dateDiv').removeClass('hide');
                 $('.days_tab').hide();
            } else {
                 $('.days_tab').show();
                $('.dateDiv').addClass('hide');
                $('input[name=from]').val('');
                $('input[name=to]').val('');
            }
        });
    });
</script>
@endslot
@endcomponent
