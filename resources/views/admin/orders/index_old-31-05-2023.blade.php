    @component('admin.layouts.main')

    @slot('title')
        Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
<style>
    td.iconlist a i {
    font-size: 20px !important;
}
td.iconlist a {
    display: block;
    margin-bottom: 10px;
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
</style>
    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $package_id = (request()->has('package_id'))?request()->package_id:'';
    $package_id = old('package_id',$package_id);

    $order_id = (request()->has('order_id'))?request()->order_id:'';
    $order_id = old('order_id',$order_id);

    $name = (request()->has('name'))?request()->name:'';
    $name = old('name',$name);

    $email = (request()->has('email'))?request()->email:'';
    $email = old('email',$email);

    $phone = (request()->has('phone'))?request()->phone:'';
    $phone = old('phone',$phone);
    
    $order_type = (request()->has('order_type'))?request()->order_type:'';
    $order_type = old('order_type',$order_type);

    // $order_id = (request()->has('order_id'))?request()->order_id:'';
    // $order_id = old('order_id',$order_id);

    $order_type_arr = config('custom.order_type');
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>Orders List ({{ $orders->total() }})</h2>
    </div>
    </div>

<div class="centersec">
    <!-- Start Search box section-->
    <div class="bgcolor pb-10">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
            <div class="col-md-2{{$errors->has('order_type')?' has-error':''}}">
                    <label for="FormControlSelect1">Order Type</label><br />
                    <select name="order_type" class="form-control admin_input1 select2">
                        <option value="">--Select Order Type--</option>
                        @if(isset($order_type_arr) && !empty($order_type_arr))
                            @foreach($order_type_arr as $k => $v)
                                <option value="{{$k}}" @if($order_type==$k) selected @endif >{{$v}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Package Name:</label><br/>
                    <input type="text" name="package_id" class="form-control admin_input1" value="{{$package_id}}">
                </div>
                <div class="col-md-2">
                    <label>Order Id:</label><br/>
                    <input type="text" name="order_id" class="form-control admin_input1" value="{{$order_id}}">
                </div>
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
                <div class="col-md-6">
                    <!-- <label>&nbsp;</label><br> -->
                    <input type="hidden" name="order" value="{{$payment_status}}">
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.orders.index',['order'=>$payment_status])}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->

    <div class="centersec" style="padding:0;">
        <div class="bgcolor p10">

            @include('snippets.errors')
            @include('snippets.flash')

            <?php if(!empty($orders) && $orders->count() > 0){?>
            <div class="table-responsive">
                <!--{{ $orders->appends(request()->query())->links('vendor.pagination.default') }}-->
                <table class="table table-bordered">
                    <tr>
                        <th class="">Order</th>
                        <th class="">User</th>
                        <th class="">Data</th>
                        <th class="">Action</th>
                    </tr>
                    <?php
                        foreach ($orders as $order) {
                            $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';
                        ?>

                        <tr>
                            <td>
                                <p><strong>Order Id: </strong>{{$order->order_no??''}}</p>
                                <p><strong>Order Date: </strong>{{ date('d M,Y h:i A',strtotime($order->created_at)) }}</p>
                                <p><strong>Order Type: </strong>{{$order_type??''}}</p>
                                <?php if($order->order_type == 1){ //Package?>
                                    <p><strong>Package: </strong>{{$order->package_name??''}}</p>
                                    <p><strong>Package Type: </strong>{{$order->package_type_name??''}}</p>
                                    <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                <?php } else if($order->order_type == 4){ //Cab
                                    if($order->booking_details){
                                        $booking_details = (array)json_decode($order->booking_details);
                                    }
                                ?>
                                     <p><strong>Itinerary: </strong>{{$booking_details['itinerary']??''}}</p>
                                     <p><strong>Pickup Date: </strong>{{$booking_details['trip_date']??''}}</p>
                                     <p><strong>Car Type: </strong>{{$booking_details['cab_name']??''}}</p>
                                     <p><strong>Trip Type: </strong>{{$booking_details['trip_type']??''}}</p>
                                <?php }else if($order->order_type == 5){ //Hotel
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
                                <?php } ?>
                                
                                <?php
                                $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
                                if(!empty($price_category_choice_record_arr)) {
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
                            <td class="user_icon_top">
                                <p>
                                    <strong>Name: </strong>{{$order->name??''}}
                                    @if(!empty($order->agent_id))
                                    (Agent)
                                    @endif
                                </p>
                                <p><strong>Email: </strong>{{$order->email??''}}</p>
                                @if($order->phone)
                               <?php  $country_code = !empty($order->country_code)?$order->country_code:'91'; ?>
                                <p><strong>Phone: </strong>+{{$country_code.'-'.$order->phone??''}}</p>
                                @endif 
                                @if($order->address1)
                                <p><strong>Address: </strong>{{$order->address1??''}}, {{$order->address2??''}}
                                  @endif @if($order->city)
                                  &nbsp;&nbsp; <strong>City: </strong>{{$order->city??''}} &nbsp;&nbsp; </p>
                                  @endif 
                                  @if($order->state)
                                  <p><strong>State: </strong>{{$order->state??''}} &nbsp;&nbsp; 
                                    &nbsp;&nbsp; <strong>Zip Code: </strong>{{$order->zip_code??''}}</p>
                                    @endif 
                                @if($order->Country)
                                <strong>Country: </strong> {{$order->Country->name??''}} 
                                @endif
                                @if(!empty($order->agent_id)) 
                                <img style="width: 25px;" class="img-responsive" src="/images/admin-agent-agency.png">
                                @endif
                            </td>
                            <td>
                                <p><strong>Payment Method: </strong>{{($order->method)?$order->method:'NA'}}</p>
                                <p><strong>Payment Status: </strong>
                                    <?php if($order->payment_status == 1){
                                    echo '<span style="color:green">Confirmed</span>';
                                        }elseif($order->payment_status == 2){
                                            echo '<span style="color:red">Failed</span>';

                                        }else{
                                            echo '<span style="color:orange">Pending</span>';
                                        } ?>
                                </p>
                                @if(isset($order->pay_type) && !empty($order->pay_type))<p><strong>Payment Type: </strong>{{ ucwords(str_replace("_", " ", $order->pay_type)) }}</p>@endif
                                <?php /*<p><strong>Sub Total Amount: </strong>{{CustomHelper::getPrice($order->sub_total_amount)??''}}</p> */ ?>
                                <?php if( (!empty($order->discount) && $order->discount > 0) || (!empty($order->fees) && $order->fees > 0) ){ ?>
                                <p><strong>Sub Total: </strong>{{CustomHelper::getPrice($order->sub_total_amount)??''}}</p>

                                <?php if(!empty($order->discount) && $order->discount > 0){ ?>
                                    <p><strong>Discount: </strong>{{CustomHelper::getPrice($order->discount)??''}}</p>
                                <?php } ?>

                                <?php if(!empty($order->fees) && $order->fees > 0){ ?>
                                    <p><strong>Fees: </strong>{{CustomHelper::getPrice($order->fees)??''}}</p>
                                <?php } ?>
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
                                    <?php if($order->payment_status == 1){ 
                                        echo '<strong>Amount Paid:</strong><span style="color:orange">'.CustomHelper::getPrice($partial_amount).'</span>'; } ?></p>
                              <?php }

                              
                              if($payment_due == 0){ ?>
                                <p>
                                    <?php if($order->payment_status == 1){
                                        echo '<strong>Total Amount Paid: </strong><span style="color:green">'.CustomHelper::getPrice($total_amount).'</span>';
                                    }elseif($order->payment_status == 2){
                                        echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($total_amount).'</span>';

                                    }elseif($order->payment_status == 0){
                                        echo '<strong>Amount Due: </strong><span style="color:orange">'.CustomHelper::getPrice($total_amount).'</span>';
                                    } ?></p>
                                <?php }else {
                                    if($order->pay_type != 'book_without_payment'){
                                        if($order->payment_status == 1){
                                            echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($payment_due).'</span>';

                                        }elseif($order->payment_status == 2){
                                            echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($total_amount).'</span>';

                                        }elseif($order->payment_status == 0){
                                            echo '<strong>Amount Due: </strong><span style="color:orange">'.CustomHelper::getPrice($total_amount).'</span>';
                                        }?>
                                    <?php } } ?>
                            </td>                            
                            <td class="iconlist">
                                @if(CustomHelper::checkPermission('orders','view'))
                                <a href="javascript:void(0);" data-src="<?php echo route('admin.orders.view', [$order->id]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('orders','view'))
                                <a href="javascript:void(0);" data-src="<?php echo route('admin.orders.view_payments', [$order->id]) ?>" data-fancybox data-type="ajax" title="View payments"><img style="width: 30px;" class="img-responsive" src="/images/View-Payment.png"> </a>
                                @endif
                                
                                <?php  if($order->payment_status == 1){
                                    ?>
                                    <?php if($order->order_type==3 && !empty($order->bookingId)) { ?>
                                        <a class="booked" href="<?php echo route('admin.voucher.flight_voucher_view', [$order->id]) ?>" title="Flight Voucher" class="cab_view_fancy"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                             <?php  }else if($order->order_type==4){
                                       if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('cabs','view_voucher'))
                                        <a class="booked" href="<?php echo route('admin.voucher.cab_voucher_view', [$order['id']]) ?>" title="Cab Voucher" class="cab_view_fancy"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                        @endif
                                    <?php  }else{ ?>
                                        @if(CustomHelper::checkPermission('cabs','edit_voucher'))
                                        <a href="<?php echo route('admin.voucher.cab', [$order['id']]) ?>" title="Cab Voucher"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                        @endif

                                    <?php } 
                                      $booking_details = json_decode($order->booking_details,true);
                                      $driver_details = $booking_details['driver_details'] ?? '';

                                      if($driver_details){ ?>
                                        @if(CustomHelper::checkPermission('cabs','driver_details'))
                                     <a href="javascript:void(0);" data-src="<?php echo route('admin.orders.cab_driver', [$order->id]) ?>" data-fancybox data-type="ajax" title="View"><img style="width: 30px;" class="img-responsive" src="/images/driver-details-icon-color.png"></a>
                                     @endif

                                    <?php  }else{
                                    ?>
                                     @if(CustomHelper::checkPermission('cabs','driver_details'))
                                      <a href="javascript:void(0);" data-src="<?php echo route('admin.orders.cab_driver', [$order->id]) ?>" data-fancybox data-type="ajax" title="View"><img style="width: 30px;" class="img-responsive" src="/images/driver-details-icon.png"></a>
                                      @endif
                                    <?php  } ?>

                                    <?php } elseif($order->order_type==1){
                                      if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('packages','view_voucher'))
                                       <a class="booked" href="<?php echo route('admin.voucher.package_voucher_view', [$order->id]) ?>" title="Package Voucher" class="cab_view_fancy"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                       @endif
                                    <?php  }else{ ?>
                                         @if(CustomHelper::checkPermission('packages','edit_voucher'))
                                        <a href="<?php echo route('admin.voucher.package', [$order->id]) ?>" title="Package Voucher"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                        @endif
                                    <?php }

                                    } elseif($order->order_type==5){
                                      if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('accommodations','view_voucher'))
                                       <a class="booked" href="<?php echo route('admin.voucher.hotel_voucher_view', [$order->id]) ?>" title="Hotel Voucher" class="cab_view_fancy"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                       @endif
                                    <?php  }else{ ?>
                                        @if(CustomHelper::checkPermission('accommodations','edit_voucher'))
                                        <a href="<?php echo route('admin.voucher.hotel', [$order->id]) ?>" title="Hotel Voucher"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                        @endif

                                    <?php } 
                                    }elseif($order->order_type==6){
                                      if($order->OrderServiceVoucher){
                                      ?>
                                      @if(CustomHelper::checkPermission('activity','view_voucher'))
                                       <a class="booked" href="<?php echo route('admin.voucher.activity_voucher_view', [$order['id']]) ?>" title="Activity Voucher" class="cab_view_fancy"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher.png"></a>
                                       @endif
                                <?php  }else{ ?>
                                        @if(CustomHelper::checkPermission('activity','edit_voucher'))
                                        <a href="<?php echo route('admin.voucher.activity', [$order['id']]) ?>" title="Activity Voucher"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a>
                                        @endif
                                <?php }

                                }  else { /*?>       
                                    <a href="<?php echo route('admin.orders.service_voucher', [$order->id]) ?>" title="Service Voucher"><img style="width: 30px;" class="img-responsive" src="/images/View-Voucher-Block.png"></a> 
                                <?php */} }?>


                                <?php /*@if(CustomHelper::checkPermission('orders','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$order->id}}"><i class="fas fa-trash-alt"></i></i></a> </br></br>
                                <form method="POST" action="{{ route('admin.orders.delete', $order->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to Delete this Order?');" id="delete-form-{{$order->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                </form>
                                @endif */ ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{$orders->appends(request()->query())->links('vendor.pagination.default')}}
            <?php } else { ?>
            <div class="alert alert-warning">There are no Records at the present.</div>
            <?php } ?>
        </div>
    </div>
@slot('bottomBlock')
@endslot
@endcomponent