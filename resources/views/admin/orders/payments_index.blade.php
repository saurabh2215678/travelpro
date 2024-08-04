@component('admin.layouts.main')

@slot('title')
Admin - Manage Transactions - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$routeName = CustomHelper::getAdminRouteName();

$order_id = (request()->has('order_id'))?request()->order_id:'';
$order_id = old('order_id',$order_id);

$payment_status = (request()->has('payment_status'))?request()->payment_status:'';
$payment_status = old('payment_status',$payment_status);

$old_method = (request()->has('method'))?request()->method:'';
$old_method = old('method',$old_method);

$old_payment_method = (request()->has('payment_method'))?request()->payment_method:'';
$old_payment_method = old('payment_method',$old_payment_method);

$from = (request()->has('from'))?request()->from:'';
$from = old('from',$from);

$to = (request()->has('to'))?request()->to:'';
$to = old('to',$to);

$order_type = (request()->has('order_type'))?request()->order_type:'';

$payment_status_arr = config('custom.payment_status_arr');

?>
<div class="top_title_admin">
    <div class="title">
        <h2>Transactions List ({{ $order_payments->total() }})</h2>
    </div>
</div>

<div class="centersec">
    <!-- Start Search box section-->
    <div class="bgcolor pb-10">

        <div class="tab_block toptab">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a <?php if($order_type==''){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.transactions',['order_type'=>''])}}">All</a>
                </li>


                <?php if(CustomHelper::isOnlineBooking('flight') && CustomHelper::checkPermission('flight','view')){ ?>
                <li class="nav-item">
                    <a <?php if($order_type=='3'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.transactions',['order_type'=>3])}}">Flight</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('cab') && CustomHelper::checkPermission('cabs','view')){ ?>
                <li class="nav-item">
                    <a <?php if($order_type=='4'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.transactions',['order_type'=>4])}}">Cab</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('package_listing') && CustomHelper::checkPermission('packages','view')){ ?>
                <li class="nav-item">
                    <a <?php if($order_type=='1'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.transactions',['order_type'=>1])}}">Package</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('activity_listing') && CustomHelper::checkPermission('activity','view')){ ?>
                <li class="nav-item">
                    <a <?php if($order_type=='6'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.transactions',['order_type'=>6])}}">Activity</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('hotel_listing') && CustomHelper::checkPermission('accommodations','view')){ ?>
                <li class="nav-item">
                    <a <?php if($order_type==5){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.transactions',['order_type'=>5])}}">Hotel</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('rental') && CustomHelper::checkPermission('bike','view')){ ?>
                <li class="nav-item">
                    <a <?php if($order_type=='8'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.transactions',['order_type'=>8])}}">Rental</a>
                </li>
                <?php } ?>
            </ul>
        </div>

        <div class="table-responsive">
            <form class="form-inline" method="GET">
                    <input type="hidden" name="order_type" class="form-control admin_input1" value="{{$order_type}}">
                <div class="col-md-2">
                    <label>Order Id:</label><br />
                    <input type="text" name="order_id" class="form-control admin_input1" value="{{$order_id}}">
                </div>
                <div class="col-md-2{{$errors->has('payment_method')?' has-error':''}}">
                    <label for="FormControlSelect1">Payment Method</label><br />
                    <select name="payment_method" class="form-control admin_input1 select2">
                        <option value="">--Select--</option>
                        <option value="wallet" {{isset($old_payment_method) && $old_payment_method == 'wallet' ? 'selected':''}}>Wallet Recharge</option>
                         <?php foreach ($payment_gateways as $key => $payment_gateway) { ?>
                          
                          <option value="{{$payment_gateway->name}}" {{$payment_gateway->name == $old_payment_method ? 'selected':''}}>{{$payment_gateway->name}}</option>
                        <?php } ?>
                    </select> 
                </div>  

               <?php /*  <div class="col-md-2{{$errors->has('payment_method')?' has-error':''}}">
                    <label for="FormControlSelect1">Payment Method</label><br />
                    <select name="payment_method" class="form-control admin_input1 select2">
                        <option value="">--Select--</option>
                        <?php if(!empty($payment_methods)){
                            foreach($payment_methods as $method){ 
                               if(!empty($method->payment_method)){ 
                                    <option value="{{$method->payment_method}}" {{(isset($old_payment_method) && $old_payment_method == $method->payment_method)?'selected':'' }}>{{ucwords(str_replace("_", " ", $method->payment_method))}}</option>
                                    <?php 
                                }
                            }
                        }
                        
                    </select>
                    </div> */ ?>
              <?php  /*<div class="col-md-2{{$errors->has('payment_status')?' has-error':''}}">
                    <label for="FormControlSelect1">Payment Status</label><br />
                    <select name="payment_status" class="form-control admin_input1 select2">
                        <option value="">--Select--</option>
                        <option value="1" {{$old_payment_status == 1 ? 'selected':''}}>Confirmed</option>
                        <option value="0" {{isset($old_payment_status) && $old_payment_status == 0 ? 'selected':''}}>Pending</option>
                        <option value="2" {{$old_payment_status == 2 ? 'selected':''}}>Failed</option>
                    </select>
                </div> */ ?>

                 <div class="col-md-2{{$errors->has('payment_status')?' has-error':''}}">
                    <label for="FormControlSelect1">Payment Status:</label><br />
                    <select name="payment_status" class="form-control admin_input1 select2">
                         <option value="">--Select--</option>
                         @if(isset($payment_status_arr) && !empty($payment_status_arr))
                         @foreach($payment_status_arr as $k => $v)
                         <option value="{{$k}}" @if($payment_status==$k) selected @endif >{{$v}}</option>
                         @endforeach
                         @endif
                    </select>
                </div>
                <div class="col-md-2{{$errors->has('from')?' has-error':''}}">
                    <label for="FormControlSelect1">From Date</label><br />
                    <input type="text" name="from" class="form-control from_date" placeholder="From Date"
                        value="{{$from}}" autocomplete="off">
                </div>
                <div class="col-md-2{{$errors->has('to')?' has-error':''}}">
                    <label for="FormControlSelect1">To Date</label><br />
                    <input type="text" name="to" class="form-control to_date" placeholder="To Date" value="{{$to}}"
                        autocomplete="off">
                </div>
               <div class="clearfix"></div>
                <div class="col-md-12">
                    <label for="FormControlSelect1"> </label>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.orders.transactions')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->

    <div class="centersec" style="padding:0;">
        <div class="bgcolor p10">

            <?php if(!empty($order_payments) && $order_payments->count() > 0){?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Order ID/ <br>Order Type</th>
                        <th>Agent/ Customer</th>
                        <th>Traveller</th>
                        <th>Payment Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Type</th>
                        <th>Payment Status</th>
                        <th>Description</th>
                        <?php //<th>Payment Details</th> ?>
                        <th>Payment Date</th>
                        <th>Details</th>
                    </tr>
                    <?php
                        $order_type_arr = config('custom.order_type');
                            foreach ($order_payments as $order_payment) {
                                $orderData  = $order_payment->orderData;  
                                $userData = $orderData->userData ?? '';
                                $order_type = !empty($orderData->order_type)?$order_type_arr[$orderData->order_type]:'';
                                /*$tpsl_txn_id = '';
                                $txn_amt = '';  
                                $txn_msg = '';  

                            if(isset($order_payment->pg_response) && !empty($order_payment->pg_response)){
                                $getPgResponse = json_decode($order_payment->pg_response);

                                if(isset($getPgResponse[1])) {
                                    $txn_msg = explode('=', $getPgResponse[1]);
                                }
                                if(isset($getPgResponse[5])) {
                                    $tpsl_txn_id = explode('=', $getPgResponse[5]);
                                }
                                if(isset($getPgResponse[6])) {
                                    $txn_amt = explode('=', $getPgResponse[6]);
                                }

                                $txn_msg = $txn_msg[1];
                                $tpsl_txn_id = $tpsl_txn_id[1];
                                $txn_amt = $txn_amt[1];
                            }*/
                    ?>
                    <tr>
                        <td>{{$order_payment->order_no ?? ''}}<br>({{$order_type ?? ''}})</td>
                        <td>
                           @if(!empty($orderData->userData))
                           @if(!empty($userData->is_agent == 1))
                           {{$userData->agentInfo->company_brand??''}}
                           (A)
                           @else
                           {{$userData->name??''}}
                           @endif
                           @endif
                        </td>
                        <td>
                        @if($orderData && $orderData->order_type == 7)
                        @else
                        {{$orderData->name??''}}
                        @endif
                        </td>

                        <td>
                            <?php
                            $add = '';
                             if($order_payment->pg_payment_status == 4){
                            $add = '-';
                             } ?>
                            {{$add}}{{CustomHelper::getPrice($order_payment->amount,'',true) ?? ''}}
                        </td>
                      <td>{{isset($order_payment->payment_method) && !empty($order_payment->payment_method) ? ucwords(str_replace("_", " ", $order_payment->payment_method)) : ''}}</td> 
                        <td>{{isset($order_payment->payment_type) && !empty($order_payment->payment_type) ? ucwords(str_replace("_", " ", $order_payment->payment_type)) : '' }}</td>
                        <td>
                            {!!CustomHelper::showPaymentStatus($order_payment->pg_payment_status??0)!!}
                        </td>
                        <td>
                            {{$order_payment->description??''}}
                        </td>
                       <?php /* <td>
                            @if(isset($tpsl_txn_id) && !empty($tpsl_txn_id))<small><b>Transaction Id :</b><p>{{$tpsl_txn_id}}</p></small>@endif
                            @if(isset($txn_msg) && !empty($txn_msg))<small><b>Transaction Message :</b><p>{{$txn_msg}}</p></small>@endif
                            @if(isset($txn_amt) && !empty($txn_amt))<small><b>Transaction Amount :</b><p>{{CustomHelper::getPrice($txn_amt)}}</p></small>@endif
                        </td> */ ?>
                        <td>{{date('d M,Y H:i A',strtotime($order_payment->created_at)) ?? ''}}</td>
                        <td>
                        @if(CustomHelper::checkPermission('orders','view'))
                        <a href="javascript:void(0);" data-src="<?php echo route('admin.orders.transactions_view', [$order_payment->id]) ?>" data-fancybox data-type="ajax" title="View Transactions Details"><img style="width: 20px;" class="img-responsive" src="/images/View-Payment.png"> </a>
                        @endif
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            {{$order_payments->appends(request()->query())->links('vendor.pagination.default')}}
            <?php } else { ?>
            <div class="alert alert-warning">There are no Records at the present.</div>
            <?php } ?>
        </div>
    </div>
@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{asset('assets/css/jquery.fancybox.min.css')}}">
<script src="{{asset('/assets/js/jquery.fancybox.min.js')}}"></script>
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script type="text/javascript">
    $(".to_date, .from_date").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        yearRange: "-90:+01"

    });
</script>
@endslot
@endcomponent