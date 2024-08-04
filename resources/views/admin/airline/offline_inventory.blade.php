@component('admin.layouts.main')

@slot('title')
Admin - Offline Flight Inventory - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$name = (request()->has('name'))?request()->name:'';

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

$range_filters = config('custom.range_filters');

$storage = Storage::disk('public');
$path = 'airlines/';
$thumb_path = 'airlines/thumb/';
?>
<div class="top_title_admin">
    <div class="title">
        <h2> {{$page_heading??''}} ({{$records->total()}})</h2>
    </div>
    @if(empty($type))
    <div class="add_button">
        <a href="{{ route($ADMIN_ROUTE_NAME.'.airline.offline_inventory_add').'?back_url='.$BackUrl }}" class="btn_admin"><i class="fa fa-plus"></i> Add Flight Inventory</a>
    </div>
    @endif
</div>

<div class="centersec">
    <div class="bgcolor">
        <div class="tab_block toptab">
            <ul class="nav nav-tabs">
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline_route.index')}}">Admin Route</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory')}}" class="{{(empty($type))?'active':''}}">Admin Inventory</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline_route.index',['agent'])}}">Agent Route</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory',['type'=>'agent'])}}" class="{{(empty($type))?'':'active'}}">Agent Inventory</a></li>
                <!-- <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.booking_list')}}">Booking List</a></li> -->
            </ul>
        </div>
        <div class="table-responsive">
            <form class="pb-2" method="GET">
                <div class="col-sm-4">
                    <label>Search:</label><br/>
                    <input type="text" name="name" class="form-control" placeholder="{{(!empty($type))?'Agent Name / ':''}}Source / Destination / Airline / Flight No / PNR" value="{{$name}}" />
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
                
                <div class="col-sm-2">
                    <label>Status:</label><br/>
                    <select name="status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="1" {{($status == '1')?'selected':'' }}>Active</option>
                        <option value="0" {{($status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-3">
                    <input type="hidden" name="route_id" value="{{$route_id}}">
                    <button type="submit" class="btn btn-success btn1search">Search</button>
                    <a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory',['type'=>$type])}}" class="btn_admin2 btn resetbtn btn-primary btn1search">Reset</a>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>

@include('snippets.errors')
@include('snippets.flash')

<?php
if(!empty($records) && $records->count() > 0){
    ?>
    <div class="table-responsive ">

        <table class="table table-striped table-bordered">
            <tr>
                @if(!empty($type))
                <th>Agent Name</th>
                @endif
                <th>Departure Date</th>
                <th>Departure / Arrival Time</th>
                <th>Source / Destination</th>
                <th>Airline / Flight No</th>
                <th>PNR / Class / Stops</th>
                <th>Refund<br />-able</th>
                <th>Adult Price</th>
                <th>Tickets for Sale</th>
                <th>Tickets Sold</th>
                <th>Status</th>
                @if(CustomHelper::checkPermission('flight','edit') || CustomHelper::checkPermission('flight','delete'))
                <th>Action</th>
                @endif
                @if(CustomHelper::checkPermission('activitylogs','view'))
                <th>View History</th>
                @endif
            </tr>
            <?php foreach($records as $record) { ?>
                <tr>
                    @if(!empty($type))
                    <td>
                        {{$record->userData->name??'System'}}
                    </td>
                    @endif
                    <td>
                        <strong>{{CustomHelper::DateFormat(($record->start_date??''),'d/m/Y')}}</strong>
                    </td>
                    <td>
                        {{CustomHelper::DateFormat($record->routeData->start_time,'h:i A')}} /
                        {{CustomHelper::DateFormat($record->routeData->end_time,'h:i A')}}
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
                    <td  align="center">
                        <a class="sold_link" href="{{route($ADMIN_ROUTE_NAME.'.airline.booking_list',['inventory_id'=>$record->id])}}" title="Sold">{{$record->inventorySoldCount()??''}}</a>
                    </td>
                    <td>
                        <?php if($record->status == 1){ ?>
                            <i class="" style="color:green">Active</i>
                        <?php }else{ ?>
                            <i class="" style="color:red">Inactive</i>
                        <?php } ?>
                    </td>
                    <td>
                        @if(CustomHelper::checkPermission('flight','view'))
                        <a href="{{route('admin.airline.offline_inventory_view',[$record->id,'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('flight','edit'))
                        <a href="{{ route($ADMIN_ROUTE_NAME.'.airline.offline_inventory_edit',[ $record->id, 'back_url'=>$BackUrl]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                        @endif
                    </td>
                    @if(CustomHelper::checkPermission('activitylogs','view'))
                    <td>
                        <a href="{{ route($ADMIN_ROUTE_NAME.'.activitylogs.index','moduleid='.$record->id.'&module=AirlineRouteInventory') }}" title="View History" target="_blank"><i class="fas fa-history"></i></a>
                    </td>
                    @endif
                </tr>
            <?php } ?>
        </table>
        {{ $records->appends(request()->query())->links('vendor.pagination.default') }}
        <?php
    }
    else{
        ?>
        <div class="alert alert-warning top_title_admin">There is no dependent data present.</div>
        <?php
    }
    ?>
</div>
</div>
@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
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