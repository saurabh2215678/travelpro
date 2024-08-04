@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .btn.s-btn { display: initial; background: #2c2d6c; color: #ffffff !important;}
    .btn2.edit_pofile_btn {font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
    a.sold_link {color: #0000ff;text-decoration: underline;}
    a.sold_link:hover {color: #05c5bc;font-weight: 600;}
    .user_profile_inner .right_info .top_info {border-bottom: 0;}
</style>
@endslot
<?php
$name = (request()->has('name'))?request()->input('name'):'';
$status = (request()->has('status'))?request()->input('status'):'';
$destination = (request()->has('destination'))?request()->input('destination'):'';
$route_id = (request()->has('route_id'))?request()->input('route_id'):'';

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

if(isset($search_data)) {

} else {
    $search_data = [];
}
$search_data = (isset($search_data))?$search_data:[];
// $order_type = (request()->has('order_type'))?request()->order_type:'pending';
$order_type = 'inventory';
$range_filters = config('custom.range_filters');
?>
<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">{{$page_heading}} ({{$records->total()}})</h1>
                        </div>
                    </div>
                    @if($order_type=='inventory')
                    <div class="ihr">
                        <a href="{{ route('user.flight_inventory_add') }}" class="btn s-btn"><i class="fa fa-plus"></i> Add Flight Inventory</a>
                    </div>
                    @endif
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

                <form action="" method="GET" class="mt-1">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="city_id">Search:</label>
                            <input type="text" class="form-control" name="name" placeholder="Source / Destination / Airline / Flight No / PNR" value="{{$name}}">
                        </div>

                        <div class="col-md-2 w-1/6 {{$errors->has('range')?' has-error':''}} rangeDiv">
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

                        <div class="col-md-2 w-1/6{{$errors->has('from')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
                            <label for="FormControlSelect1">From Date</label><br/>
                            <input type="text" name="from" class="form-control from_date" placeholder="From Date"
                            value="{{$from}}" autocomplete="off">
                        </div>

                        <div class="col-md-2 w-1/6{{$errors->has('to')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
                            <label for="FormControlSelect1">To Date</label><br/>
                            <input type="text" name="to" class="form-control to_date" placeholder="To Date"
                            value="{{$to}}" autocomplete="off">
                        </div>

                        <div class="col-md-2 w-1/6">
                            <label>Status:</label><br/>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1" {{($status == '1')?'selected':'' }}>Active</option>
                                <option value="0" {{($status == '0')?'selected':'' }}>Pending</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>&nbsp</label>
                            <div class="mb-3">
                                <input type="hidden" name="route_id" value="{{$route_id}}">
                                <button type="submit" class="btn s-btn rounded-full">Search</button>
                                <a class="btn2 edit_pofile_btn" href="{{route('user.flight_inventory')}}">Reset</a>
                            </div>
                        </div>

                    </div>
                </form>
                @include('snippets.front.flash')
                <?php if(!empty($records) && $records->count() > 0){ ?>  
                    <table class="table table_order mt-1">
                        <thead>
                            <tr>
                                <th scope="col">Departure Date</th>
                                <th scope="col">Departure / Arrival Time</th>
                                <th scope="col">Source / Destination</th>
                                <th scope="col">Airline / Flight No</th>
                                <th scope="col">PNR / Class / Stops</th>
                                <th scope="col">Refund<br />-able</th>
                                <th scope="col">Adult Price</th>
                                <th scope="col">Tickets for Sale</th>
                                <th scope="col">Tickets Sold</th>
                                <th scope="col">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach($records as $record) { ?>
                                <tr>
                                    <td>
                                        <strong>{{CustomHelper::DateFormat(($record->start_date??''),'d/m/Y')}}</strong>
                                    </td>
                                    <td>
                                        {{CustomHelper::DateFormat($record->routeData->start_time,'H:i')}} /
                                        {{CustomHelper::DateFormat($record->routeData->end_time,'H:i')}}
                                        @if($record->routeData->is_arrival_next_day)
                                        <br />(Arrival Next Day)
                                        @endif
                                    </td>
                                    <td>
                                        {{$record->routeData->source??''}} / {{$record->routeData->destination??''}}
                                    </td>
                                    <td>
                                        {{$record->routeData->airline??''}}-{{$record->routeData->flight_number??''}}
                                    </td>
                                    <td>
                                        {{$record->pnr??''}} / {{CustomHelper::showCabinClass($record->flight_class??'')}} / {{$record->routeData->stops??''}} Stop(s)
                                    </td>
                                    <td>
                                        <?php if($record->is_refundable == 1){ ?>
                                            <i class="" style="color:green">Yes</i>
                                        <?php }else{ ?>
                                            <i class="" style="color:red">No</i>
                                        <?php } ?>
                                    </td>
                                    <td align="right">
                                        {{CustomHelper::getPrice($record->adult_price??0)}}
                                    </td>
                                    <td align="center">
                                        {{$record->inventory??''}}
                                    </td>
                                    <td align="center">
                                        <a class="sold_link" href="{{route('user.myFlightBooking',['inventory_id'=>$record->id,'order_type'=>'bookings'])}}" title="Sold">{{$record->inventorySoldCount()??''}}</a>
                                        
                                    </td>
                                    <td>
                                        <?php if($record->status == 1){ ?>
                                            <i class="" style="color:green">Active</i>
                                        <?php }else{ ?>
                                            <i class="" style="color:red">Pending</i>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="{{route('user.flight_inventory_view',$record->id)}}" title="View" data-fancybox data-type="ajax"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('user.flight_inventory_edit',[ $record->id]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                    <div class="pagination-wrapper hotel-pagination mt-2 ml-0">
                        {{ $records->appends(request()->input())->links('vendor.pagination.bootstrap-4'); }}
                    </div>
                </div>
            <?php }else{ ?>
                <div class="alert_not_found">No record found.</div>
            <?php } ?>
        </div>
    </div>
</section>
@slot('bottomBlock')

<script type="text/javascript">
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


