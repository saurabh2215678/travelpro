@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? ''}}
@endslot
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .btn.s-btn { display: initial; background: #2c2d6c; color: #ffffff !important;}
    .btn2.edit_pofile_btn {font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
    a.sold_link {color: #0000ff;text-decoration: underline;}
    a.sold_link:hover {color: #05c5bc;font-weight: 600; }
    .user_profile_inner .right_info .top_info {border-bottom: 0;}
    .btn2.edit_pofile_btn {
        font-size: 13px;
        padding: 8px 25px 8px;
        text-transform: none;
    }
    .btn.s-btn {
        display: initial;
        background: #2c2d6c;
        color: #ffffff !important;
    }

</style>
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$name = (request()->has('name'))?request()->name:'';
$status = (request()->has('status'))?request()->status:1;
$featured = (request()->has('featured'))?request()->featured:'';
if(isset($search_data)) {

} else {
    $search_data = [];

}
$search_data = (isset($search_data))?$search_data:[];
$order_type = 'route';
?>

<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">

                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">{{$page_heading??''}} ({{$records->total()}})</h1>
                        </div>
                    </div>
                    <div class="ihr">
                        <a href="{{ route('user.flight_route_add',['back_url'=>$BackUrl])}}" class="btn s-btn"><i class="fa fa-plus"></i> Add Flight Route</a>
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



                <div class="centersec">
                    <div class="bgcolor pb-2">
                        <div class="table-responsive">
                            <form class="mt-3 row" method="GET">
                                <div class="col-md-6 w-1/2">
                                    <label>Search:</label><br/>
                                    <input type="text" name="name" placeholder="Route Name / Source / Destination / Airline / Flight No" class="form-control" value="{{$name}}" />
                                </div>
                                <div class="col-md-2 w-1/4">
                                    <label>Status:</label><br/>
                                    <select name="status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" {{($status == '1')?'selected':'1' }}>Active</option>
                                        <option value="0" {{($status == '0')?'selected':'' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-1" style="display: none;">
                                    <label>Featured:</label><br/>
                                    <input type="checkbox" name="featured" class="form-control" value="1" {{($featured==1)?'checked':''}} />
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <div class="mb-3">
                                        <button type="submit" class="btn s-btn rounded-full">Search</button>
                                        <a href="{{route('user.flight_route')}}" class="btn2 edit_pofile_btn">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @include('snippets.front.flash')

                    <?php
                    if(!empty($records) && $records->count() > 0){
                        ?>
                        <div class="table-responsive table_order">

                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Route Name</th>
                                    <th>Source (Terminal)</th>
                                    <th>Destination (Terminal)</th>
                                    <th>Departure Time</th>
                                    <th>Arrival Time</th>
                                    <th>Airline / Flight No</th>
                                    <th>Trip Type</th>
                                    <th>Seat Allocation</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    @if(CustomHelper::checkPermission('activitylogs','view'))
                                    <th>View History</th>
                                    @endif
                                </tr>
                                <?php foreach($records as $record) { ?>
                                    <tr>
                                        <td>
                                            <a href="{{route('user.flight_inventory',['order_type'=>'inventory','route_id'=>$record->id])}}" class="view_cancel" >{{$record->name}}</a>
                                        </td>
                                        <td>
                                            {{$record->source}} ({{$record->source_terminal}})
                                        </td>
                                        <td>
                                            {{$record->destination}} ({{$record->destination_terminal}})
                                        </td>
                                        <td>
                                            {{CustomHelper::DateFormat($record->start_time,'H:i')}}
                                        </td>
                                        <td>
                                            {{CustomHelper::DateFormat($record->end_time,'H:i')}}
                                            @if($record->is_arrival_next_day)
                                            <br />(Arrival Next Day)
                                            @endif
                                        </td>
                                        <td>
                                            {{$record->airline}}-{{$record->flight_number}}
                                        </td>
                                        <td>
                                            {{$record->trip_type}}
                                        </td>
                                        <td>
                                            <a href="{{route('user.flight_inventory',['order_type'=>'inventory','route_id'=>$record->id])}}" class="view_cancel" ><i class="fas fa-eye"></i> View</a>
                                        </td>
                                        <td>
                                            <?php if($record->status == 1){ ?>
                                                <i class="" style="color:green">Active</i>
                                            <?php }else{ ?>
                                                <i class="" style="color:red">Inactive</i>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="{{route('user.flight_route_view',[$record->id])}}" title="View" data-fancybox data-type="ajax"><i class="fas fa-eye"></i></a>

                                            <a href="{{ route('user.flight_route_edit',[$record->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>

                                            @if(CustomHelper::checkPermission('flight','delete'))
                                            <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.airline_route.delete', $record->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Flight Route?');" id="delete-form-{{$record->id}}">
                                                {{ csrf_field() }}
                                                {{ method_field('POST') }}
                                                <input type="hidden" name="propertytype_id" value="<?php echo $record->id; ?>">
                                            </form>
                                            @endif
                                        </td>
                                        @if(CustomHelper::checkPermission('activitylogs','view'))
                                        <td>
                                            <a href="{{ route($ADMIN_ROUTE_NAME.'.activitylogs.index','moduleid='.$record->id.'&module=AirlineRoute') }}" title="View History" target="_blank"><i class="fas fa-history"></i></a>
                                        </td>
                                        @endif
                                    </tr>
                                <?php } ?>
                            </table>
                            {{ $records->appends(request()->query())->links('vendor.pagination.default') }}
                        <?php } else { ?>
                            <div class="alert alert-warning top_title_admin">There is no dependent data present.</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</section>
@slot('bottomBlock')
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot
@endcomponent