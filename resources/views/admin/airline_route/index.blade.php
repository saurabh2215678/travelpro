@component('admin.layouts.main')

@slot('title')
Admin - Manage Airline Routes - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$name = (request()->has('name'))?request()->name:'';
$status = (request()->has('status'))?request()->status:'';
$featured = (request()->has('featured'))?request()->featured:'';
?>


<div class="top_title_admin">
    <div class="title">
        <h2> {{$page_heading??''}} ({{$records->total()}})</h2>
    </div>
    @empty($type)
    <div class="add_button">
        @if(CustomHelper::checkPermission('flight','add'))
        <a href="{{ route($ADMIN_ROUTE_NAME.'.airline_route.add').'?back_url='.$BackUrl }}" class="btn_admin btn btn-sm btn-success pull-right" style="margin-right:5px;"><i class="fa fa-plus"></i> Add Flight Route</a>
        @endif
    </div>
    @endif
</div>

<div class="centersec">
    <div class="bgcolor">
        <div class="tab_block toptab">
            <ul class="nav nav-tabs">
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline_route.index')}}" class="{{(empty($type))?'active':''}}">Admin Route</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory')}}">Admin Inventory</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline_route.index',['agent'])}}" class="{{(!empty($type))?'active':''}}">Agent Route</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory',['type'=>'agent'])}}">Agent Inventory</a></li>
                <!-- <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.booking_list')}}">Booking List</a></li> -->
            </ul>
        </div>
        <div class="table-responsive">
            <form class="" method="GET">
                <div class="col-sm-6">
                    <label>Search:</label><br/>
                    <input type="text" name="name" placeholder="{{(!empty($type))?'Agent Name / ':''}}Route Name / Source / Destination / Airline / Flight No" class="form-control" value="{{$name}}" />
                </div>
                <div class="col-sm-2">
                    <label>Status:</label><br/>
                    <select name="status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="1" {{($status == '1')?'selected':'1' }}>Active</option>
                        <option value="0" {{($status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-sm-1" style="display: none;">
                    <label>Featured:</label><br/>
                    <input type="checkbox" name="featured" class="form-control" value="1" {{($featured==1)?'checked':''}} />
                </div>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-success btn1search">Search</button>
                    <a href="{{route($ADMIN_ROUTE_NAME.'.airline_route.index',[$type])}}" class="btn_admin2 btn resetbtn btn-primary btn1search">Reset</a>
                </div>
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
                    @if(!empty($type))
                    <td>
                        {{$record->userData->name??'System'}}
                    </td>
                    @endif
                    <td>
                        <a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory',[$type,'route_id'=>$record->id])}}" class="view_cancel" title="View">{{$record->name}}</a>
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
                        <a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory',[$type,'route_id'=>$record->id])}}" class="view_cancel" title="View"><i class="fas fa-eye"></i> View</a>
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
                        <a href="{{route($ADMIN_ROUTE_NAME.'.airline_route.view',[$record->id])}}" title="View" data-fancybox data-type="ajax"><i class="fas fa-eye"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('flight','edit'))
                        <a href="{{ route($ADMIN_ROUTE_NAME.'.airline_route.edit',[ $record->id, $record->parent_id]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('flight','delete'))
                        <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.airline_route.delete', $record->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Airline Route?');" id="delete-form-{{$record->id}}">
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
@slot('bottomBlock')
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot
@endcomponent