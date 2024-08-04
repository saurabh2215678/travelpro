@component('admin.layouts.main')
@slot('title')
Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<style>
   td.iconlist a i{font-size:17px!important}
   td.iconlist a{display:inline-block}
   .booked i{color:#00b2a7!important}
   td.iconlist img{filter:opacity(0.7)}
   td.iconlist a.booked img{filter:opacity(1)}
   .centersec{min-height:auto}
   .counter{position:absolute;top:-33px;right:-3px;font-size:11px}
   .select2-search--inline{background-color:#dcdcdc;display:none}
   .select2-selection__rendered{display:flex!important;overflow:hidden;margin-right:20px;margin-bottom:0}
   .select2-selection__rendered li { display: none !important;}
   .select2-selection__rendered li:nth-child(-n + 2) { display: block !important; }
   span.select2.select2-container.select2-container--default {z-index: 99;}
   body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-stage .fancybox-content iframe{padding:15px}
   body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-slide--iframe .fancybox-content{height:80%!important;width:100rem!important}
   .fancybox-active .fancybox-container.fancybox-is-open button.fancybox-button{background:#009688;top:0;right:0}
   body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-inner .fancybox-toolbar{right:34rem;top:4rem}
   .fancybox-slide--iframe .fancybox-content{width:800px;height:450px;max-width:80%;max-height:80%;margin:0}
   .booking_calender_table tr td table tbody tr:first-child td {border-top: 0;}

</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php
// $BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$package_id = (request()->has('package_id'))?request()->package_id:'';
$package_id = old('package_id',$package_id);

$search = (request()->has('search'))?request()->search:'';
$search = old('search',$search);

$order_id = (request()->has('order_id'))?request()->order_id:'';
$order_id = old('order_id',$order_id);

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
if(empty($order_status)){
  $order_status = ['new','accepted'];
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
$is_vendor =auth()->user()? auth()->user()->is_vendor :'';
?>
<?php
$order_route = 'orders';
if($is_vendor == 1){
  $order_route = 'vendor-orders';
}
?>
<div class="top_title_admin">
   <div class="title">
      <h2> Bookings Calendar </h2>

   </div>
</div>
<div class="centersec">
   @include('snippets.errors')
   @include('snippets.flash')
   <!-- Start Search box section-->
   <div class="bgcolor pb-10">
      <div class="tab_block toptab">
         <ul class="nav nav-tabs">
            <?php if($is_vendor != 1){
             $all_search_data = $search_data;
             $all_search_data['order'] = $order;
             ?>
             <li class="nav-item">
               <a <?php if($order_type==''){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.calendar_booking',$all_search_data)}}">All</a>
            </li>
         <?php } ?>

         <?php if(CustomHelper::isOnlineBooking('flight') && CustomHelper::checkPermission('flight','view')) {
           $flight_search_data = $search_data;
           $flight_search_data['order_type'] = 3;
           $flight_search_data['order'] = $order;
           ?>
           <li class="nav-item">
            <a <?php if($order_type=='3'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.calendar_booking',$flight_search_data)}}">Flight</a>
         </li>
      <?php } ?>

      <?php if(CustomHelper::isOnlineBooking('cab') && CustomHelper::checkPermission('cabs','view')) {
         $cab_search_data = $search_data;
         $cab_search_data['order_type'] = 4;
         $cab_search_data['order'] = $order;
         ?>
         <li class="nav-item">
            <a <?php if($order_type=='4'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.calendar_booking',$cab_search_data)}}">Cab</a>
         </li>
      <?php } ?>

      <?php if(CustomHelper::isOnlineBooking('package_listing') && CustomHelper::checkPermission('packages','view')) {
          $package_search_data = $search_data;
          $package_search_data['order_type'] = 1;
          $package_search_data['order'] = $order;
          ?>
      <li class="nav-item">
         <a <?php if($order_type=='1'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.calendar_booking',$package_search_data)}}">Package</a>
      </li>
      <?php } ?>

      <?php if(CustomHelper::isOnlineBooking('activity_listing') && CustomHelper::checkPermission('activity','view')) {
          $activity_search_data = $search_data;
          $activity_search_data['order_type'] = 6;
          $activity_search_data['order'] = $order;
         ?>
      <li class="nav-item">
      <a <?php if($order_type=='6'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.calendar_booking',$activity_search_data)}}">Activity</a>
      </li>
   <?php } ?>

    <?php if(CustomHelper::isOnlineBooking('hotel_listing') && CustomHelper::checkPermission('accommodations','view')) {
       $hotel_search_data = $search_data;
       $hotel_search_data['order_type'] = 5;
       $hotel_search_data['order'] = $order;
       ?>
   <li class="nav-item">
      <a <?php if($order_type=='5'){echo 'class="active"' ;}?> href="{{route($routeName.'.'.$order_route.'.calendar_booking',$hotel_search_data)}}">Hotel</a>
   </li>
   <?php } ?>

 <?php if(CustomHelper::isOnlineBooking('rental') && CustomHelper::checkPermission('bike_master','view')) {
    $bike_search_data = $search_data;
    $bike_search_data['order_type'] = 8;
    $bike_search_data['order'] = $order;
    ?>
    <li class="nav-item">
       <a <?php if($order_type=='8'){echo 'class="active"' ;}?> href="{{route($routeName.'.orders.calendar_booking',$bike_search_data)}}">Rental</a>
    </li>
 <?php } ?>
</ul>
</div>
      <div class="table-responsive-no">
         <form class="form-inline" method="GET">
            <input type="hidden" name="order_type" value="{{$order_type}}">
         <?php /* <div class="col-md-2{{$errors->has('order_status')?' has-error':''}}">
            <label for="FormControlSelect1">Order Status:</label><br />
            <select name="order_status[]" id="customSelect3" class="form-control admin_input1 select2 myselect" multiple="multiple">
               <option value="">Select Order Status</option>
               @if(isset($order_status_category_arr) && !empty($order_status_category_arr))
               @foreach($order_status_category_arr as $k => $v)
               @if($k != 'rejected')
               <option value="{{$k}}" {{(!empty($order_status) && in_array($k,$order_status))?'selected':''}} >{{$v}}</option>
               @endif
               @endforeach
               @endif
            </select>
         </div> */ ?>

         <div class="col-md-3{{$errors->has('range')?' has-error':''}} rangeDiv">
            <label for="FormControlSelect1">Date Range</label><br/>
            <select name="range" class="form-control admin_input1 range select2">
               <option value="">--Select Date--</option>
               <?php if(isset($range_filters) && !empty($range_filters)){
                  unset($range_filters['tomorrow']);
                  unset($range_filters['next_week']); ?>
                  @foreach($range_filters as $k => $v)
                  <option value="{{$k}}" {{(!empty($range) && $range == $k)?'selected':''}}>{{$v}}</option>
                  @endforeach
               <?php } ?>
            </select>
         </div>
         <div class="col-md-3{{$errors->has('from')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv" >
            <label for="FormControlSelect1">From Date</label><br/>
            <input type="text" name="from" class="form-control from_date" placeholder="From Date"
            value="{{$from}}" autocomplete="off">
         </div>
         <div class="col-md-3{{$errors->has('to')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
            <label for="FormControlSelect1">To Date</label><br/>
            <input type="text" name="to" class="form-control to_date" placeholder="To Date"
            value="{{$to}}" autocomplete="off">
         </div>
         <div  class="col-md-3 search-block">
            <input type="hidden" name="order" value="{{$payment_status}}">
            <button type="submit" class="btn btn-success">Search</button>

            <a href="{{route($routeName.'.'.$order_route.'.calendar_booking',['order_type'=>$order_type,'order'=>$payment_status])}}" class="btn_admin2">Reset</a>
         </div>
         <br>
       
       <?php /*  <div class="col-md-3 search-block days_tab">
            <button type="submit" name="todays_orders" value="todays_orders" class="btn btn-info" title="Today" @if($todays_orders == 'todays_orders') id="active" @endif> <i class="la la-trash"></i> Today</button>
            <button type="submit" name="yesterdays_orders" value="yesterdays_orders" class="btn btn-info" title="Yesterday" @if($yesterdays_orders == 'yesterdays_orders') id="active" @endif> <i class="la la-trash"></i> Yesterday</button>
            <button type="submit" name="all_orders" value="all_orders" class="btn btn-info" title="All" @if($all_orders == 'all_orders') id="active" @endif> <i class="la la-trash"></i> All</button>
         </div> */ ?>
         
      </form>
   </div>
</div>
<!-- End Search box Section -->
<div class="centersec" style="padding:0;">
   <div class="bgcolor p10">
      <?php if(!empty($orders) && $orders->count() > 0){?>
         <div class="table-responsive">
            <?php /* {{ $orders->appends(request()->query())->links('vendor.pagination.default') }} */ ?>


            <table class="table table-striped booking_calender_table" style=" margin-bottom: 0; ">
               <tbody>
                 <?php
                 $grand_total_amount = 0;
                 $grand_trxn_amount = 0;
                 $total_markup_S = 0;
                 $total_markup_A = 0;
                 $total_dis = 0;
                 $total_comm = 0;

                 foreach ($group_date as $date_key => $date_row) {

                    $date_wise_trip_date = date('Y-m-d',strtotime($date_row->trip_date)) ?? '';
                    $key =0; ?>
                    <tr>
                     <?php if($key == 0){
                       ?>
                       <td style="vertical-align: middle;width: 10%;position: relative;">

                          <?php if($date_key == 0){
                           $key =1; ?>
                           <span class="topdate">Date</span>
                        <?php } ?>

                        <span> {{ date('d M,Y',strtotime($date_row->trip_date)) ?? '' }}</span>
                     </td> 
                  <?php } ?>

                  <td>
                     <table class="table table-striped" style=" margin-bottom: 0; ">
                       <?php if($date_key == 0){
                        $key =1; ?>
                        <tr>
                           <th style="width: 10%;">Time</th>
                           <th style="width: 10%;">Booking ID</th>
                           <th style="width: 10%;">Order Type </th>
                           <th style="width:20%;">Agent/Customer </th>
                           <th style="width: 20%;">Traveller </th>
                           <th style="width: 10%;">Payment/ <br>API Status</th>
                           <th style="width: 10%;">Order Status </th>
                           <th style="width: 10%;">Action </th>
                        </tr>
                     <?php } ?>
                     <?php   foreach ($orders as $order) {

                       $trip_date = date('Y-m-d',strtotime($order->trip_date)) ?? '';

                       if(!empty($trip_date) && $trip_date == $date_wise_trip_date){

                          $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';
                          $userData = $order->userData ?? '';
                          ?>
                          <tr>
                           <td style="width: 10%;">{{ date('h:i A',strtotime($order->trip_date)) ?? '' }}</td>
                           <td style="width: 10%;">{{$order->order_no??''}}</td>
                           <td style="width: 10%;">{{$order_type??''}}</td>
                           <td style="width: 20%;">
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
                          <td style="width: 20%;">{{$order->name??''}}</td>
                          <td style="width: 10%;">
                           {!!CustomHelper::showPaymentStatus($order->payment_status??0)!!}
                           @if($order->order_type == 3)
                           <!-- FLIGHT STATUS -->
                           {{$order->status??''}}
                           @endif
                        </td>
                        <?php 
                        //$order_status_category = CustomHelper::showEnquiryMaster($order->orders_status) ?? '';

                        $showOrderStatusColor = CustomHelper::showOrderStatusColor($order->orders_status)??'';
                        ?>
                        <td style="width: 10%;">{!!$showOrderStatusColor??0 !!}</td>
                        <td style="width: 10%;">
                           @if(CustomHelper::checkPermission('orders','view'))

                         <?php /*<strong><a href="javascript:void(0);" data-src="<?php echo route('admin.orders.view', [$order->id, 'back'=>'calendar_booking']) " data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a></strong> */ ?>
                            <a href="{{route('admin.orders.details',[$order->order_no,'order'=>'calendar_booking','back'=>'order_listing'])}}" title="View"><i class="fas fa-eye"></i></a>

                           @endif
                        </td>
                     </tr>

                  <?php } } ?>

               </table>
            </td>
         </tr>
         <tr>
            <td colspan="2" style="background-color:#ddd"></td>
         </tr>
      <?php } ?>

   </tbody>
</table>

</div>
<?php } else { ?>
   <div class="alert alert-warning">There are no Records at the present.</div>
<?php } ?>
</div>
</div>
@slot('bottomBlock')

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
</script>
<script type="text/javascript">
   $(document).on('click','.check_booking_status',function(){
     var _this = $(this);
     var order_id = _this.attr('data-id');
     if(order_id) {
       var _token = '{{csrf_token()}}';
       $.ajax({
         url: "{{url($routeName.'/orders/check_booking_status')}}",
         type: "POST",
         data: {order_id},
         dataType:"JSON",
         headers:{'X-CSRF-TOKEN': _token},
         cache: false,
         beforeSend: function () {

         },
         success: function(resp) {
           if(resp.success) {
             _this.parent().html('<p><strong>Booking Status: </strong> '+resp.booking_status+'</p>');
          } else if(resp.message) {
             alert(resp.message);
          }
       }
    });
    }
 });
</script>
@endslot
@endcomponent