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
</style>
@endslot
<?php
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

$from = (request()->has('from'))?request()->from:date('d/m/Y');
$from = old('from', $from);

$to = (request()->has('to'))?request()->to:date('d/m/Y');
$to = old('to', $to);

$todays_orders = (request()->has('todays_orders'))?request()->todays_orders:'';
$yesterdays_orders = (request()->has('yesterdays_orders'))?request()->yesterdays_orders:'';
$all_orders = (request()->has('all_orders'))?request()->all_orders:'';

if(!empty($todays_orders) || !empty($yesterdays_orders) || !empty($all_orders)){
    $order_status = [];
}

$order_type = (request()->has('order_type'))?request()->order_type:'';
$order_type = old('order_type',$order_type);

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
                            <h1 class="title">My Booking</h1>
                        </div>
                    </div>
                </div>


<?php /*<table class="table table_order">
<thead>
<tr>
<th scope="col">S No.</th>
<th scope="col">Package Name</th>
<th scope="col">Date of booking</th>
<th scope="col">Action </th>
</tr>
</thead>
<tbody>
<?php
$s_no = 1;
foreach($uesrOrders as $userOrder){

<tr>
<th><?php echo $s_no++; </th>
<td>{{$userOrder->package_name??''}}</td>
<td>{{CustomHelper::DateFormat($userOrder->created_at, 'd M,Y h:i A')}}</td>
<td><a href="{{route('user.orderDetails',[$userOrder['id'], ])}}" title="View"><i class="fa-regular fa-eye"></i></a></td>
</tr>
<?php }
</tbody>
</table> */ ?>

<div class="tab_block toptab">
    <ul class="nav nav-tabs">
        <?php
        $all_search_data = $search_data;
        ?>
        <li class="nav-item">
            <a <?php if($order_type==''){echo 'class="active"' ;}?> href="{{route('user.mybooking',$all_search_data)}}">All</a>
        </li>

        <?php if(CustomHelper::isOnlineBooking('flight')) {
            $flight_search_data = $search_data;
            $flight_search_data['order_type'] = 3;
            ?>
            <li class="nav-item">
                <a <?php if($order_type=='3'){echo 'class="active"' ;}?> href="{{route('user.mybooking',$flight_search_data)}}">Flight</a>
            </li>
        <?php } ?>

<?php /*if(CustomHelper::isOnlineBooking('flight') && $user_details->is_agent == 1 && $user_details->approved_agent == 1) {
$flight_search_data = $search_data;
$flight_search_data['order_type'] = 9;
?>
<li class="nav-item">
<a <?php if($order_type=='9'){echo 'class="active"' ;}?> href="{{route('user.mybooking',$flight_search_data)}}">Offline Tickets</a>
</li>
<?php }*/ ?>

<?php if(CustomHelper::isOnlineBooking('cab')) {
    $cab_search_data = $search_data;
    $cab_search_data['order_type'] = 4;
    ?>
    <li class="nav-item">
        <a <?php if($order_type=='4'){echo 'class="active"' ;}?> href="{{route('user.mybooking',$cab_search_data)}}">Cab</a>
    </li>
<?php } ?>

<?php if(CustomHelper::isOnlineBooking('package_listing')) {
    $package_search_data = $search_data;
    $package_search_data['order_type'] = 1;
    ?>
    <li class="nav-item">
        <a <?php if($order_type=='1'){echo 'class="active"' ;}?> href="{{route('user.mybooking',$package_search_data)}}">Package</a>
    </li>
<?php } ?>

<?php if(CustomHelper::isOnlineBooking('activity_listing')) {
    $activity_search_data = $search_data;
    $activity_search_data['order_type'] = 6;
    ?>
    <li class="nav-item">
        <a <?php if($order_type=='6'){echo 'class="active"' ;}?> href="{{route('user.mybooking',$activity_search_data)}}">Activity</a>
    </li>
<?php } ?>

<?php if(CustomHelper::isOnlineBooking('hotel_listing')) {
    $hotel_search_data = $search_data;
    $hotel_search_data['order_type'] = 5;
    ?>
    <li class="nav-item">
        <a <?php if($order_type=='5'){echo 'class="active"' ;}?> href="{{route('user.mybooking',$hotel_search_data)}}">Hotel</a>
    </li>
<?php } ?>

<?php if(CustomHelper::isOnlineBooking('rental')) {
    $bike_search_data = $search_data;
    $bike_search_data['order_type'] = 8;
    ?>
    <li class="nav-item">
        <a <?php if($order_type=='8'){echo 'class="active"' ;}?> href="{{route('user.mybooking',$bike_search_data)}}">Rental</a>
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
<button type="submit" class="btn s-btn btn-info sky_blue rounded-full">Search</button>
<a href="{{route('user.mybooking')}}" class="btn2 btn-info edit_pofile_btn">Reset</a>
</div> 
</div>
</form>
</div>
<?php } ?>

@include('snippets.front.flash')
<?php if(!empty($uesrOrders) && $uesrOrders->count() > 0){ ?>
    <div class="booking-lists table-responsive mt-2">
        <table class="table table-bordered table-sm text-sm">
            <thead>
                <tr>
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
                        <td>
                            <?php
                            $booking_details = [];
                            if($order->booking_details){
                                $booking_details = (array)json_decode($order->booking_details);
                            }
                            if($order->order_type==1){
                                $package_details = $order->Package;
                                $destination_des = !empty($package_details->packageDestination)?$package_details->packageDestination->name:'';
                                echo $destination_des;
                            }
                            else if($order->order_type==6){
                                $package_details = $order->Package;
                                $destination_des = !empty($package_details->packageDestination)?$package_details->packageDestination->name:'';
                                echo $order->name??'';
                                echo "<br />";
                                echo "(".$order->package_name.")";
                                echo "<br />";
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
                                <?php if(!empty($order->markup) && $order->markup > 0) {
                                    if($orders_status == 'Confirmed') {
                                        $agent_markup = $agent_markup + $order->markup ;
                                    }
                                    ?>
                                    {{CustomHelper::getPrice($order->markup,'',true)}}
                                <?php } ?>
                            </td>
                            <td>
                                <?php if(!empty($order->discount) && $order->discount > 0) { 
                                    if($orders_status == 'Confirmed') {
                                        $total_dis = $total_dis + $order->discount;  
                                    }
                                    ?>
                                    {{CustomHelper::getPrice($order->discount)}}(D)
                                <?php } ?>
                                <br>

                                <?php if(!empty($order->commission) && $order->commission > 0){ ?>
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
                        <?php
                        $show_color = CustomHelper::showOrderStatusColor($order->orders_status)??'';
                        if($order->cancel_request == 1){
                            $show_color = '<strong><span style="color:orange">Cancellation Processing</span></strong>';
                        }
                        ?>
                        {!! $show_color ?? '' !!}
                    </td>
                    <td>{{CustomHelper::getPaymentGatewayName($order->method)}}</td>
                    <td class="iconlist">
                        <a href="javascript:void(0);" data-src="<?php echo route('user.orderDetails', [$order['id']]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                        <?php
                        if($order->payment_status == 1) {
                            if($order->order_type == 1) {
                                if($order->OrderServiceVoucher){ ?>
                                    <a href="<?php echo route('user.package_voucher_view', [$order['id']]) ?>" title="Package Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                    <?php
                                }
                            }
                            if($order->order_type == 3) {
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
                            if($order->order_type == 4){
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
                            if($order->order_type == 5){
                                if($order->OrderServiceVoucher){ ?>
                                    <a href="<?php echo route('user.hotel_voucher_view', [$order['id']]) ?>" title="Hotel Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                <?php }
                            }if($order->order_type == 6){
                                if($order->OrderServiceVoucher){ ?>
                                    <a href="<?php echo route('user.activity_voucher_view', [$order['id']]) ?>" title="Activity Voucher" class="cab_view_fancy"><img style="width: 1.2rem;position: relative;top: 0.15rem;" class="img-responsive" src="{{ asset(config('custom.assets').'/images/View-Voucher.png') }}" alt="View-Voucher"></a>
                                    <?php
                                }
                            }
                        } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
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
    $(document).ready(function() {
        $('#offline_order_cancel_btn').hide();
        $('#offline_order_confirm_btn').hide();

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

    $(document).on("click","#cust_btn",function(){
        var order_id = $(this).attr('data-order_id');
        $('#refund_order_id').val(order_id);
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
                url: "{{ url('user/refund') }}",
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
                    $("#cust_btn").hide();
                }
            });
        }
    });
</script>
<script type="text/javascript">
    $('.select2').select2({ placeholder: "Select Date", allowClear: true });
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
</script>
@endslot
@endcomponent
