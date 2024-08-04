@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
<style>
    td.iconlist a i {
    font-size: 17px !important;
}
td.iconlist a {
    display: inline-block;
    /* margin-bottom: 10px; */
}
.booked i {
    color: #00b2a7 !important;
}
td.iconlist img {
    filter: opacity(0.7);
}
td.iconlist a.booked img {
    filter: opacity(1);
}
.centersec {
    min-height: auto;
}
.counter{
    position: absolute;
    top: -33px;
    right: -3px;
    font-size: 11px;
 }
 .select2-search--inline{
    background-color:Gainsboro;
    display:none;
 }
 .select2-selection__rendered {
    display: flex !important;
    overflow: hidden;
    margin-right: 20px;
    margin-bottom: 0;
  }
  .select2-selection__rendered li { display: none !important;}
  .select2-selection__rendered li:nth-child(-n + 2) { display: block !important; }
  span.select2.select2-container.select2-container--default {z-index: 99;}

  </style>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <?php
    $BackUrl = CustomHelper::BackUrl();
    $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    $package_id = (request()->has('package_id'))?request()->package_id:'';
    $package_id = old('package_id',$package_id);

    $search = (request()->has('search'))?request()->search:'';
    $search = old('search',$search);

    $order_id = (request()->has('order_id'))?request()->order_id:'';
    $order_id = old('order_id',$order_id);

    $pnr = (request()->has('pnr'))?request()->pnr:'';
    $pnr = old('pnr',$pnr);

    $name = (request()->has('name'))?request()->name:'';
    $name = old('name',$name);

    $order = (request()->has('order'))?request()->order:'confirmed';
    $order = old('order',$order);

    $email = (request()->has('email'))?request()->email:'';
    $email = old('email',$email);

    $phone = (request()->has('phone'))?request()->phone:'';
    $phone = old('phone',$phone);

    $order_status = (request()->has('order_status'))?request()->order_status:'';
    $order_status = old('order_status',$order_status);
    /*if(empty($order_status)){
        $order_status = ['new','accepted'];
    }*/

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
    if($order_type == 3) {
        $flight_type = (request()->has('flight_type'))?request()->flight_type:'';
        $flight_type = old('flight_type',$flight_type);
    }
    $order_type_arr = config('custom.order_type');
    $order_status_category_arr = config('custom.order_status_category_arr');
    $range_filters = config('custom.range_filters');


    $user =auth()->user()? auth()->user() :'';
    $is_vendor =auth()->user()? auth()->user()->is_vendor :'';

    $pendingOrdersCount = CustomHelper::getPendingOrdersCount();
    $pendingAmendmentCount = CustomHelper::pendingAmendmentCount();
    if(isset($search_data['flight_type'])) {
        unset($search_data['flight_type']);
    }
    ?>

    <?php
     $order_route = 'orders';
     if($is_vendor == 1){
        $order_route = 'vendor-orders';
    }
    ?>
    <div class="top_title_admin">
        <div class="title">
            <h2>
                @if($order == 'confirmed')
                View Bookings ({{ $orders->total() }})
                @elseif($order == 'pending' || $order == 'amendment_progress')
                    @if($order == 'pending')
                    <div class="tab_list">
                    <span>Pending Bookings ({{ $orders->total() }}) </span>
                    <a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'amendment_progress','range'=>'custom','order_type'=>'']) }}" title="Amendment in Progress">Amendment in Progress ({{$pendingAmendmentCount}})</a>
                    </div>
                    @elseif($order == 'amendment_progress')
                    <div class="tab_list">
                    <a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'pending','range'=>'custom','order_type'=>''])}}" title="Pending Bookings">Pending Bookings ({{$pendingOrdersCount}})</a>
                    <span>Amendment in Progress ({{ $orders->total() }})</span>
                    </div>
                    @endif
                @elseif($order == 'cancel_request')
                Cancel Request Booking ({{ $orders->total() }})
                @else
                Failed Bookings ({{ $orders->total() }})
                @endif
                
            </h2>
            <span class="" style="font-weight: 300; color: #a3650b;">
                <strong>Note:</strong>
                @if($order == 'confirmed')
                Payment received and order processed or waiting for auto processing
                @elseif($order == 'pending')
                Payment received but order processing requires attention. 
                @elseif($order == 'amendment_progress')
                Order amendment in progress
                @elseif($order == 'cancel_request')
                Cancel Request Booking
                @else
                Payment NOT received, failed or refunded
                @endif
            </span>
        </div>
    </div>

<div class="centersec">
@include('snippets.errors')
@include('snippets.flash')
    <!-- Start Search box section-->
    <div class="bgcolor pb-10">
        <div class="tab_block toptab">
            <ul class="nav nav-tabs">
                <?php if($is_vendor != 1) {
                    $all_search_data = $search_data;
                    $all_search_data['order'] = $order;
                ?>
                <li class="nav-item">
                    <a <?php if($order_type==''){echo 'class="active"' ;}?> href="{{route($ADMIN_ROUTE_NAME.'.'.$order_route.'.index',$all_search_data)}}">All</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('flight') && CustomHelper::checkPermission('flight','view')) {
                    $flight_search_data = $search_data;
                    $flight_search_data['order_type'] = 3;
                    $flight_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a <?php if($order_type=='3' && $flight_type!='offline'){echo 'class="active"' ;}?> href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$flight_search_data)}}">Flight</a>
                </li>
                <li class="nav-item">
                    <?php
                    $flight_search_data['flight_type'] = 'offline';
                    ?>
                    <a <?php if($order_type=='3' && $flight_type=='offline'){echo 'class="active"' ;}?> href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$flight_search_data)}}">Offline Flight</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('cab') && CustomHelper::checkPermission('cabs','view')) {
                    $cab_search_data = $search_data;
                    $cab_search_data['order_type'] = 4;
                    $cab_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a <?php if($order_type=='4'){echo 'class="active"' ;}?> href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$cab_search_data)}}">Cab</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('package_listing') && CustomHelper::checkPermission('packages','view')) {
                    $package_search_data = $search_data;
                    $package_search_data['order_type'] = 1;
                    $package_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a <?php if($order_type=='1'){echo 'class="active"' ;}?> href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$package_search_data)}}">Package</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('activity_listing') && CustomHelper::checkPermission('activity','view')) {
                    $activity_search_data = $search_data;
                    $activity_search_data['order_type'] = 6;
                    $activity_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a <?php if($order_type=='6'){echo 'class="active"' ;}?> href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$activity_search_data)}}">Activity</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('hotel_listing') && CustomHelper::checkPermission('accommodations','view')) {
                    $hotel_search_data = $search_data;
                    $hotel_search_data['order_type'] = 5;
                    $hotel_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a <?php if($order_type=='5'){echo 'class="active"' ;}?> href="{{route($ADMIN_ROUTE_NAME.'.'.$order_route.'.index',$hotel_search_data)}}">Hotel</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('rental') && CustomHelper::checkPermission('bike_master','view')) {
                    $bike_search_data = $search_data;
                    $bike_search_data['order_type'] = 8;
                    $bike_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a <?php if($order_type=='8'){echo 'class="active"' ;}?> href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$bike_search_data)}}">Rental</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('flight') && CustomHelper::checkPermission('flight','view') && $order == 'confirmed') { ?>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.booking_list')}}">Offline Booked Flight</a></li>
                <?php } ?>
            </ul>
        </div>

        <div class="table-responsive-no">
            <form class="form-inline" method="GET">
                <input type="hidden" name="order_type" value="{{$order_type}}">
                <?php if($order_type != 1 && $order_type != 3 && $order_type != 4 && $order_type != 5 && $order_type != 6 ){ ?>

               <?php /* <div class="col-md-2{{$errors->has('order_type')?' has-error':''}}">
                    <label for="FormControlSelect1">Order Type:</label><br />
                    <select name="order_type" class="form-control admin_input1 select2">
                        <option value="">--Select Order Type--</option>
                        @if(isset($order_type_arr) && !empty($order_type_arr))
                        @foreach($order_type_arr as $k => $v)
                        <option value="{{$k}}" @if($order_type==$k) selected @endif >{{$v}}</option>
                        @endforeach
                        @endif
                    </select>
                </div> */ ?>

                <?php } ?>

                <div class="col-md-2">
                    <label> Search :</label><br/>
                    <input type="text" name="search" class="form-control admin_input1" value="{{$search}}">
                </div>

                <div class="col-md-2">
                    <label>Booking ID:</label><br/>
                    <input type="text" name="order_id" class="form-control admin_input1" value="{{$order_id}}">
                </div>
                @if(CustomHelper::isAllowedModule('flight')) 
                <?php if($order_type=='' || $order_type=='3'){ ?>
                <div class="col-md-2">
                    <label>Airline PNR No:</label><br/>
                    <input type="text" name="pnr" class="form-control admin_input1" value="{{$pnr}}">
                </div>
                <?php } ?>
                @endif

                <div class="col-md-2">
                    <label>Name:</label><br/>
                    <input type="text" name="name" class="form-control admin_input1" value="{{$name}}">
                </div>
                <div class="col-md-2">
                    <label>Email:</label><br/>
                    <input type="email" name="email" class="form-control admin_input1" value="{{$email}}">
                </div>
                <div class="col-md-2">
                    <label>Phone:</label><br/>
                    <input type="text" name="phone" class="form-control admin_input1" value="{{$phone}}" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                </div>

                <div class="clearfix"></div>

                <div class="col-md-2{{$errors->has('order_status')?' has-error':''}}">
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

                <div class="col-md-2{{$errors->has('range')?' has-error':''}} rangeDiv">
                <label for="FormControlSelect1">Date range </label><br/>
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

                <div class="col-md-2{{$errors->has('from')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
                    <label for="FormControlSelect1">From Date</label><br/>
                    <input type="text" name="from" class="form-control from_date" placeholder="From Date"
                    value="{{$from}}" autocomplete="off">
                </div>
                <div class="col-md-2{{$errors->has('to')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
                    <label for="FormControlSelect1">To Date</label><br/>
                    <input type="text" name="to" class="form-control to_date" placeholder="To Date"
                    value="{{$to}}" autocomplete="off">
                </div>

                
               <?php /* <div class="col-md-4 search-block days_tab">
                    <button type="submit" name="todays_orders" value="todays_orders" class="btn btn-info" title="Today's Orders" @if($todays_orders == 'todays_orders') id="active" @endif> <i class="la la-trash"></i> Today's Orders</button>
                    <button type="submit" name="yesterdays_orders" value="yesterdays_orders" class="btn btn-info" title="Yesterday's Orders" @if($yesterdays_orders == 'yesterdays_orders') id="active" @endif> <i class="la la-trash"></i> Yesterday's Orders</button>
                    <button type="submit" name="all_orders" value="all_orders" class="btn btn-info" title="All Orders" @if($all_orders == 'all_orders') id="active" @endif> <i class="la la-trash"></i> All Orders</button>
                </div> */ ?>
            
                <div class="col-md-4 search-block">
                    <label>&nbsp;</label>
                    <input type="hidden" name="flight_type" value="{{$flight_type}}">
                    <input type="hidden" name="order" value="{{$payment_status}}">
                    <input type="hidden" name="inventory_id" value="{{$inventory_id}}">
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($ADMIN_ROUTE_NAME.'.'.$order_route.'.index',['order_type'=>$order_type,'order'=>$payment_status,'flight_type'=>$flight_type])}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->

    <div class="centersec" style="padding:0;">
        <div class="bgcolor p10">
            <?php if(!empty($orders) && $orders->count() > 0){?>
            <div class="table-responsive">
                <form method="POST" action="{{route($ADMIN_ROUTE_NAME.'.orders.bulk_action')}}" id="order_listing" class="form-inline" onSubmit="return validateOrderForm()" >
                    {{ csrf_field() }}
                <table class="table table-bordered">
                    <tr>
                        @if($flight_type=='offline')
                        <th class="">
                            <input type="checkbox" name="check_all" value="1" title="Select All">
                        </th>
                        @endif
                        <th class="">Date</th>
                        <!-- <th class="">Trip Date</th> -->
                        <th class="" style="width:15%;">Order/ Product</th>
                        <th class="">Desc</th>
                        <th class="">
                        @if(CustomHelper::isAllowedModule('agentuser'))
                        Agent/
                        @endif
                        Customer</th>
                        <th class="">Traveller</th>
                        <th class="">Medium</th>
                        <th class="">Order<br> Amount</th>
                        <th class="">TRXN<br> Amount</th>
                        <th class="">Supplier/ Admin Markup</th>
                        <th class="">Discount/ Comm.</th>
                        <th width="90" class="">Payment/ <br>API Status</th>
                        <th class="">Order <br>Status</th>
                        <th width="125" class="">Action</th>
                    </tr>
                    <?php
                        $grand_total_amount = 0;
                        $grand_trxn_amount = 0;
                        $total_markup_S = 0;
                        $total_markup_A = 0;
                        $total_dis = 0;
                        $total_comm = 0;
                        foreach ($orders as $order) {
                            $orders_status = CustomHelper::showOrderStatus($order->orders_status)??'';
                            $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';
                            $userData = $order->userData ?? '';
                            $curr_status = CustomHelper::showEnquiryMaster($order->orders_status) ?? '';
                            $voucherCreatedId = CustomHelper::getOrderStatus("Voucher created");
                            $voucherSentId = CustomHelper::getOrderStatus("Voucher Sent");
                        ?>

                        <tr>
                            @if($flight_type=='offline')
                            <td class="">
                                <input type="checkbox" name="booking_id[]" value="{{$order->id}}">
                            </td>
                            @endif
                            <td>{{CustomHelper::DateFormat($order->created_at, 'd M,Y h:i A')}}</td>
                            <?php /* <td>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</td> */ ?>
                            <td>
                                <?php
                                $booking_details = [];
                                $show_order_no = true;
                                if($order->payment_status == 1) {
                                    if($order->order_type==3) {
                                        if(!empty($order->bookingId)) {
                                            $booking_details = json_decode($order->booking_details,true)??[];
                                            $booking_status = $booking_details['order']['status']??'';
                                            if($booking_status == 'SUCCESS') {
                                                $show_order_no = false;
                                ?>
                                <a class="view_cancel" href="<?php echo route('admin.orders.details', [$order->order_no]) ?>" title="Flight Details" >{{$order->order_no??''}}<i class="fa fa-external-link"></i></a>
                                <?php
                                            }
                                        }
                                    } else {
                                        $show_order_no = false;
                                ?>
                                <a class="view_cancel" href="<?php echo route('admin.orders.details', [$order->order_no, 'order'=>$payment_status]) ?>" title="View">{{$order->order_no??''}}<i class="fa fa-external-link"></i></a>
                                <?php
                                    }
                                }
                                if($show_order_no) {
                                    echo $order->order_no;
                                }
                                ?><br>
                                {{$order_type??''}}
                                @if($order->inventory_id)
                                    <br>
                                    @if($order->supplier_id)
                                        <span>
                                            <a class="view_cancel" href="<?php echo route('admin.airline.offline_inventory', ['agent','inventory_id'=>$order->inventory_id]) ?>" title="Offline Agent" target="_blank">Offline Agent<i class="fa fa-external-link"></i></a>
                                        </span>
                                    @else
                                        <span>
                                            <a class="view_cancel" href="<?php echo route('admin.airline.offline_inventory', ['inventory_id'=>$order->inventory_id]) ?>" title="Offline Admin" target="_blank">Offline Admin<i class="fa fa-external-link"></i></a>
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td> <?php if($order->order_type == 7){

                                echo "Recharge";
                            }else{
                                echo "Order";
                            } ?>
                            </td>
                            <td>
                                @if(!empty($order->userData))
                                @if(!empty($userData->is_agent == 1))
                                <?php 
                                $company_brand = $userData->agentInfo->company_brand??'';
                                if(!empty($company_brand)){
                                    echo $company_brand.'(A)';
                                }
                                ?>
                                @else
                                {{$userData->name??''}}
                                @endif
                                @endif
                            </td>
                            <td>@if($order->order_type != 7) {{$order->name??''}} @endif</td>
                            <?php
                                $total_amount = $order->total_amount??0;
                                if($orders_status == 'Confirmed') {
                                    $grand_total_amount = $grand_total_amount+$total_amount;
                                }

                                $paid_amount = $order->partial_amount??0;
                                $grand_trxn_amount = $grand_trxn_amount+$paid_amount;

                                $payment_due = 0;
                                if($paid_amount) {
                                    $payment_due = $total_amount - $paid_amount;
                                }
                                ?>
                            <td>{{CustomHelper::getPaymentGatewayName($order->method)}}</td>
                            <td>{{CustomHelper::getPrice($total_amount,'',true)}}</td>
                            <td>{{CustomHelper::getPrice($paid_amount,'',true)}}</td>
                            <td>
                                <?php
                                $supplier_markup = $order->supplier_markup??0;
                                if(!empty($supplier_markup) && $supplier_markup != '0.00') {
                                    if($orders_status == 'Confirmed') {
                                        $total_markup_S = $total_markup_S + $supplier_markup;
                                    }
                                    ?>
                                    {{CustomHelper::getPrice($supplier_markup)}}(S)
                                <?php } ?>
                                <br />
                                <?php
                                $admin_markup = $order->admin_markup??0;
                                $inventory_data = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList'][0]['inventory_data']??[];
                                $travellerInfos = $booking_details['itemInfos']['AIR']['travellerInfos']??[];
                                if($inventory_data && $travellerInfos) {
                                    foreach($travellerInfos as $traveller) {
                                        $pt = $traveller['pt']??'';
                                        if($pt) {
                                            switch ($pt) {
                                                case 'ADULT':
                                                    $admin_markup += (($inventory_data['admin_adult_price']??0)-($inventory_data['adult_price']??0));
                                                        # code...
                                                break;
                                                case 'CHILD':
                                                    $admin_markup += (($inventory_data['admin_child_price']??0)-($inventory_data['child_price']??0));
                                                        # code...
                                                break;
                                                case 'INFANT':
                                                    $admin_markup += (($inventory_data['admin_infant_price']??0)-($inventory_data['infant_price']??0));
                                                        # code...
                                                break;   
                                                default:
                                                        # code...
                                                break;
                                            }
                                        }
                                    }
                                }
                                if(!empty($admin_markup) && $admin_markup != '0.00') {
                                    if($orders_status == 'Confirmed') {
                                        $total_markup_A = $total_markup_A + $admin_markup;
                                    }
                                ?>
                                    {{CustomHelper::getPrice($admin_markup,'',true)}}(A)
                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                $discount = $order->discount??0;
                                if(!empty($discount) && $discount != '0.00') {
                                    if($orders_status == 'Confirmed') {
                                        $total_dis = $total_dis + $discount;
                                    }
                                    ?>
                                {{CustomHelper::getPrice($discount,'',true)}}(D)
                                <?php } ?>
                                <br />
                                <?php
                                $commission = $order->commission??0;
                                if(!empty($commission) && $commission != '0.00') {
                                    if($orders_status == 'Confirmed') {
                                        $total_comm = $total_comm + $commission;
                                    }
                                ?>
                                {{CustomHelper::getPrice($commission,'',true)}}(C)
                                <?php } ?>
                            </td>
                            <td>
                                {!!CustomHelper::showPaymentStatus($order->payment_status??0)!!}<br>
                                @if($order->order_type == 3)
                                <!-- FLIGHT STATUS -->
                                    {{$order->status??''}}
                                @endif
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
                             <td class="iconlist text-center">
                                
                                @if(CustomHelper::checkPermission('orders','view'))
                                <a href="{{route('admin.orders.details', [$order->order_no,'order'=>$payment_status,'back'=>'order_listing'])}}" title="View"><i class="fas fa-eye"></i></a>
                                <!-- <a href="javascript:void(0);" data-src="<?php //echo route('admin.orders.view', [$order->id, 'back'=>'order_listing']) ?>" data-fancybox data-type="ajax" title="View" class="view_order_details"><i class="fas fa-eye"></i></a> -->
                                @endif

                    <?php if($order->cancel_request == 0){ ?>
                                @if(CustomHelper::checkPermission('orders','view'))
                                <!-- <a href="javascript:void(0);" data-src="<?php //echo route('admin.orders.view_payments', [$order->id]) ?>" data-fancybox data-type="ajax" title="View payments"><img style="width: 20px;" class="img-responsive" src="/images/View-Payment.png"> </a> -->
                                @endif

                                <?php  if($order->payment_status == 1){
                                    ?>
                                    <?php if($order->order_type==3) {  ?>
                                        <?php
                                        if(!empty($order->bookingId)) {
                                            $booking_details = json_decode($order->booking_details,true)??[];
                                            $booking_status = $booking_details['order']['status']??'';
                                            if($booking_status == 'SUCCESS') {
                                        ?>
                                        <a class="booked" href="<?php echo route('admin.voucher.flight_voucher_view', [$order->id, 'order'=>$payment_status]) ?>" title="Flight Voucher" class="cab_view_fancy"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                    <?php
                                        }
                                    }
                                    ?>
                             <?php  }else if($order->order_type==4){
                                       if(($curr_status == "Voucher created" || $order->orders_status == $voucherCreatedId) || ($curr_status == "Voucher Sent" || $order->orders_status == $voucherSentId)){
                                       if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('cabs','view_voucher'))
                                        <a class="booked" href="<?php echo route('admin.voucher.cab_voucher_view', [$order['id'], 'order'=>$payment_status]) ?>" title="Cab Voucher" class="cab_view_fancy"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                        @endif
                                    <?php  }else{ ?>
                                        @if(CustomHelper::checkPermission('cabs','edit_voucher'))
                                        <a href="<?php echo route('admin.voucher.cab', [$order['id'], 'order'=>$payment_status]) ?>" title="Cab Voucher"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                        @endif

                                    <?php } }
                                      $booking_details = json_decode($order->booking_details,true);
                                      $driver_details = $booking_details['driver_details'] ?? '';

                                      if($driver_details){ ?>
                                        @if(CustomHelper::checkPermission('cabs','driver_details'))
                                     <a class="cab_driver_detail_fancy" href="javascript:void(0);" data-src="<?php echo route('admin.orders.cab_driver', [$order->id]) ?>" data-fancybox data-type="ajax" title="Cab Driver Detail View"><img style="width: 20px;" class="img-responsive" src="/images/driver-details-icon-color.png"></a>
                                     @endif

                                    <?php  }else{
                                    ?>
                                     @if(CustomHelper::checkPermission('cabs','driver_details'))
                                      <a class="cab_driver_detail_fancy" href="javascript:void(0);" data-src="<?php echo route('admin.orders.cab_driver', [$order->id]) ?>" data-fancybox data-type="ajax" title="Cab Driver Detail Edit"><img style="width: 20px;" class="img-responsive" src="/images/driver-details-icon.png"></a>
                                      @endif
                                    <?php  } ?>

                                    <?php } elseif($order->order_type==1){
                                    if(($curr_status == "Voucher created" || $order->orders_status == $voucherCreatedId) || ($curr_status == "Voucher Sent" || $order->orders_status == $voucherSentId)){
                                      if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('packages','view_voucher'))
                                       <a class="booked" href="<?php echo route('admin.voucher.package_voucher_view', [$order->id, 'order'=>$payment_status]) ?>" title="Package Voucher" class="cab_view_fancy"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                       @endif
                                    <?php  }else{ ?>
                                         @if(CustomHelper::checkPermission('packages','edit_voucher'))
                                        <a href="<?php echo route('admin.voucher.package', [$order->id, 'order'=>$payment_status]) ?>" title="Package Voucher"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                        @endif
                                    <?php } }

                                    } elseif($order->order_type==5){
                                    if(($curr_status == "Voucher created" || $order->orders_status == $voucherCreatedId) || ($curr_status == "Voucher Sent" || $order->orders_status == $voucherSentId)){
                                      if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('accommodations','view_voucher'))
                                       <a class="booked" href="<?php echo route('admin.voucher.hotel_voucher_view', [$order->id, 'order'=>$payment_status]) ?>" title="Hotel Voucher" class="cab_view_fancy"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                       @endif
                                    <?php  }else{ ?>
                                        @if(CustomHelper::checkPermission('accommodations','edit_voucher'))
                                        <a href="<?php echo route('admin.voucher.hotel', [$order->id, 'order'=>$payment_status]) ?>" title="Hotel Voucher"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                        @endif

                                    <?php } } ?>
                                   

                                    <?php 
                                    }elseif($order->order_type==6){
                                    if(($curr_status == "Voucher created" || $order->orders_status == $voucherCreatedId) || ($curr_status == "Voucher Sent" || $order->orders_status == $voucherSentId)){
                                      if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('activity','view_voucher'))
                                       <a class="booked" href="<?php echo route('admin.voucher.activity_voucher_view', [$order['id'], 'order'=>$payment_status]) ?>" title="Activity Voucher" class="cab_view_fancy"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                       @endif
                                <?php  }else{ ?>
                                        @if(CustomHelper::checkPermission('activity','edit_voucher'))
                                        <a href="<?php echo route('admin.voucher.activity', [$order['id'], 'order'=>$payment_status]) ?>" title="Activity Voucher"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                        @endif
                                <?php } }

                            }elseif($order->order_type==8){
                                if(($curr_status == "Voucher created" || $order->orders_status == $voucherCreatedId) || ($curr_status == "Voucher Sent" || $order->orders_status == $voucherSentId)){
                                  if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('bike','view_voucher'))
                                      <a class="booked" href="<?php echo route('admin.voucher.rental_voucher_view', [$order->id, 'order'=>$payment_status]) ?>" title="Rental Voucher" class="cab_view_fancy"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                      @endif
                                  <?php  }else{ ?>
                                    @if(CustomHelper::checkPermission('bike','edit_voucher'))
                                    <a href="<?php echo route('admin.voucher.rental', [$order->id, 'order'=>$payment_status]) ?>" title="Rental Voucher"><img style="width: 20px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                    @endif
                                <?php } }
                            } 
                         }?>
                         
                <?php } ?>
                            </td>
                        </tr>

                        <?php } ?>
                       <?php /* <tr>
                            <th colspan="6" style="text-align: right;">Total:</th>
                            <th>{{CustomHelper::getPrice($grand_total_amount,'',true) ?? 0}}</th>
                            <th>{{CustomHelper::getPrice($grand_trxn_amount,'',true) ?? 0}}</th>
                            <th>{{CustomHelper::getPrice($total_markup_S,'',true) ?? 0}}(S)
                                <br>
                                {{CustomHelper::getPrice($total_markup_A,'',true) ?? 0}}(A)
                            </th>
                            <th>{{CustomHelper::getPrice($total_dis,'',true) ?? 0}}(D)
                                <br>
                                {{CustomHelper::getPrice($total_comm,'',true) ?? 0}}(C)
                            </th>
                            <th colspan="3"></th>
                        </tr> */ ?>
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
                                    <input type="text" name="email" value="">
                                </div>
                                <div class="modal-footer" id="email_action">
                                    <a href="javascript:void(0);" title="Flight Voucher" class="btn btn-success cab_view_fancy btn_admin" id="sendMail"> Send Mail </a>
                                    <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button>
                                </div>
                                <div class="modal-footer" id="email_processing" style="display: none;">
                                    <button type="button" class="btn btn-success" disabled>Processing...</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{$orders->appends(request()->query())->links('vendor.pagination.default')}}
            <div class="bottom_price">
            <table width="20%">
                <tr>
                    <td>Total Sales  </td>
                    <td style="text-align: right;">{{CustomHelper::getPrice($grand_total_amount,'',true) ?? 0.00}}</td>
                </tr>
                <tr>
                    <td>Supplier Markup  </td>
                    <td style="text-align: right;">{{CustomHelper::getPrice($total_markup_S,'',true) ?? 0.00}}</td>
                </tr>
                <tr>
                    <td>Admin Markup  </td>
                    <td style="text-align: right;">{{CustomHelper::getPrice($total_markup_A,'',true) ?? 0.00}}</td>
                </tr>
                <tr>
                    <td>Commission  </td>
                    <td style="text-align: right;">-{{CustomHelper::getPrice($total_comm,'',true) ?? 0.00}}</td>
                </tr>
                <tr>
                    <td>Discount  </td>
                    <td style="text-align: right;">-{{CustomHelper::getPrice($total_dis,'',true) ?? 0.00}}</td>
                </tr>
                <tr>
                    <th>Net Profit  </th>
                    <th style="text-align: right;">{{CustomHelper::getPrice(($total_markup_S+$total_markup_A-$total_comm-$total_dis),'',true) ?? 0}}</th>
                </tr>
            </table>
            </div>
            <?php } else { ?>
            <div class="alert alert-warning">There are no Records at the present.</div>
            <?php } ?>
        </div>
</div>


@slot('bottomBlock')
<style>
    body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-stage .fancybox-content iframe {
        padding: 15px;
    }
    body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-slide--iframe .fancybox-content {
        height: 80% !important;
        width: 100rem !important;
    }
    .fancybox-active .fancybox-container.fancybox-is-open button.fancybox-button {
        background: #009688;
        top: 0px;
        right: 0;
    }
    body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-inner .fancybox-toolbar {
        right: 34rem;
        top: 4rem;
    }
    .fancybox-slide--iframe .fancybox-content {
        width  : 800px;
        height : 450px;
        max-width  : 80%;
        max-height : 80%;
        margin: 0;
    }
</style>

<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
$('.select2').select2({ placeholder: "Select Date", allowClear: true });
// ============== Select Box Start ============

$('.myselect').select2({closeOnSelect: true,  placeholder: "Please Select",}).on("change", function(e) {
    var counter = $(this).next('.select2-container').find(".select2-selection__choice").length;
    if(counter > 2){
        if($(this).next('.select2-container').find('.counter').length <= 0){
            $(this).next('.select2-container').find('.select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter"> ('+counter+' selected)</div>');
        } else {
            $(this).next('.select2-container').find('.counter').text(`(${counter} selected)`);
        }
    } else {
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
    $('.cab_driver_detail_fancy').fancybox({
        afterClose: function () {
            parent.location.reload(true);
        }
    });

    $('.view_order_details').fancybox({
        afterClose: function () {
            parent.location.reload(true);
        }
    });

    $('.order_update_fancy').fancybox({
        'type': "iframe",
        'width':'500',
        'toolbar'  : false,
        'smallBtn' : true,
        'autosize':false,
        afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
            parent.location.reload(true);
        },
        beforeClose: function(){

        }
    });
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
            $("#emailModal").modal("show");
            return false;
        } else {
            return true;
        }
    }
}
$(document).on("click","#sendMail",function(e){
    var formData = $('#order_listing').serialize();
    var _token = '{{ csrf_token() }}';
    $.ajax({
        url: "{{ route($ADMIN_ROUTE_NAME.'.orders.bulk_action')}}",
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
                $("#emailModal").modal("hide");
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
@endslot
@endcomponent