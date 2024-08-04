@component('admin.layouts.main')

@slot('title')
Admin - Manage {{$page_heading??''}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
    z-index: 999 !important;
}
select.form-control.change_passenger {
    padding: 6px 4px;
    width: 55px;
    margin-right: 10px;
}
.passenger_select #EditPSlug {
  top: 5px;
}
.form-control.readonly {
    border: 0;
    cursor: not-allowed;
    background-color: #fff;
    opacity: 1;
}
a#EditPSlug {
    position: absolute;
    right: 8px;
    top: 8px;
    font-size: 15px;
    opacity: .7;
    pointer-events: none;
}
.name_passenger a#EditPSlug {
    top: 2px;
}
a#EditPSlug i {font-size: 10px; color: #3F51B5;}
</style>
@endslot
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$airline = (request()->has('airline'))?request()->airline:'';
$pnr = (request()->has('pnr'))?request()->pnr:'';

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

$order = (request()->has('order'))?request()->order:'confirmed';
$order = old('order',$order);

$search_data = [];
$is_vendor = auth()->user()? auth()->user()->is_vendor :'';

$order_route = 'orders';
if($is_vendor == 1){
    $order_route = 'vendor-orders';
}

$order_type = (request()->has('order_type'))?request()->order_type:'';
$order_type = old('order_type',$order_type);
?>
<div class="top_title_admin">
    <div class="title">
        <h2>
            {{$page_heading??''}}
            @if(empty($inventory_id))
            ({{$records->total()}})
            @endif
        </h2>
    </div>
</div>

<div class="centersec">
    <div class="bgcolor">
        <div class="tab_block toptab">
            <ul class="nav nav-tabs">
                <!-- <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline_route.index')}}">Admin Route</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory')}}">Admin Inventory</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline_route.index',['agent'])}}">Agent Route</a></li>
                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory',['type'=>'agent'])}}">Agent Inventory</a></li> -->


                <?php if($is_vendor != 1) {
                    $all_search_data = $search_data;
                    $all_search_data['order'] = $order;
                ?>
                <li class="nav-item">
                    <a href="{{route($ADMIN_ROUTE_NAME.'.'.$order_route.'.index',$all_search_data)}}">All</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('flight') && CustomHelper::checkPermission('flight','view')) {
                    $flight_search_data = $search_data;
                    $flight_search_data['order_type'] = 3;
                    $flight_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$flight_search_data)}}">Flight</a>
                </li>
                <li class="nav-item">
                    <?php
                    $flight_search_data['flight_type'] = 'offline';
                    ?>
                    <a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$flight_search_data)}}">Offline Flight</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('cab') && CustomHelper::checkPermission('cabs','view')) {
                    $cab_search_data = $search_data;
                    $cab_search_data['order_type'] = 4;
                    $cab_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$cab_search_data)}}">Cab</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('package_listing') && CustomHelper::checkPermission('packages','view')) {
                    $package_search_data = $search_data;
                    $package_search_data['order_type'] = 1;
                    $package_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$package_search_data)}}">Package</a>
                </li>
                <?php } ?>

                <?php if(CustomHelper::isOnlineBooking('activity_listing') && CustomHelper::checkPermission('activity','view')) {
                    $activity_search_data = $search_data;
                    $activity_search_data['order_type'] = 6;
                    $activity_search_data['order'] = $order;
                    ?>
                <li class="nav-item">
                    <a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',$activity_search_data)}}">Activity</a>
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

                <li><a href="{{route($ADMIN_ROUTE_NAME.'.airline.booking_list')}}" class="active">Offline Booked Flight</a></li>
            </ul>
        </div>
        <div class="table-responsive">
            <form class="" method="GET">
                <div class="col-md-3">
                    <label>Airline:</label><br/>
                    <select name="airline" class="form-control airline">
                        @if($airline)
                        <option value="{{$airline}}" selected >{{CustomHelper::showAirlineName($airline)}}</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label>PNR:</label><br/>
                    <input type="text" name="pnr" placeholder="PNR" class="form-control" value="{{$pnr}}" />
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
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($ADMIN_ROUTE_NAME.'.airline.booking_list',[$type])}}" class="btn_admin2 btn resetbtn btn-primary">Reset</a>
                </div>
            </form>
        </div>
    </div>

@include('snippets.errors')
@include('snippets.flash')

<?php if(!empty($records) && $records->count() > 0){ ?>
    <div class="table-responsive ">
        <form method="POST" action="{{route($ADMIN_ROUTE_NAME.'.airline.booking_list')}}" id="booking_list" class="" onSubmit="return validateOrderForm()" >
            {{ csrf_field() }}
            <table class="table table-striped table-bordered">
                <tr>
                    <th>
                        <input type="checkbox" name="check_all" value="1" title="Select All">
                    </th>
                    <th>Sr No</th>
                    <th>Booking Date</th>
                    <th>Airline</th>
                    <th>Title</th>
                    <th>Name</th>
                    <th>PNR</th>
                    <th>E-Ticket No</th>
                    <th>Company Name</th>
                    <th>Contact No</th>
                    <th>Status</th>
                    <th>Action</th>
                    @if(CustomHelper::checkPermission('activitylogs','view'))
                    <th>View History</th>
                    @endif
                </tr>
                <?php
                $counter = 0;
                foreach($records as $order) {
                    $booking_details = json_decode($order->booking_details, true);
                    $airline_arr = [];
                    $sIs = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI']??[];
                    if($sIs) {
                        foreach($sIs as $sI) {
                            $airline_name = $sI['fD']['aI']['name']??'';
                            if($airline_name && !in_array($airline_name, $airline_arr)) {
                                $airline_arr[] = $airline_name;
                            }
                        }
                    }
                    $airline = '';
                    if($airline_arr) {
                        $airline = implode(', ', $airline_arr);
                    }
                    $OrderTraveller = $order->OrderTraveller??[];
                    if($OrderTraveller) {
                        foreach($OrderTraveller as $traveller) {
                            $pax_type = $traveller->pax_type??'';
                            $title = $traveller->title??'';
                            $first_name = $traveller->first_name??'';
                            $last_name = $traveller->last_name??'';
                            $pnrDetails = $traveller->pnrDetails??'';
                            $airline_ticket_no = $traveller->airline_ticket_no??'';
                            $counter++;

                ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="booking_id[]" value="{{$traveller->id}}">
                        </td>
                        <td>
                            {{$counter}}
                        </td>
                        <td>
                            {{CustomHelper::DateFormat($order->created_at,'j F Y')}}
                        </td>
                        <td>
                            {{$airline}}
                        </td>
                        <td class="passenger_select" style="position:relative;">
                            <select class="form-control change_passenger readonly" name="title">
                                <option value="">Title</option>
                                @if($pax_type=='ADULT')
                                <option value="Mr" {{($title=='Mr')?'selected':''}}>Mr</option>
                                <option value="Mrs" {{($title=='Mrs')?'selected':''}}>Mrs</option>
                                @endif
                                <option value="Ms" {{($title=='Ms')?'selected':''}}>Ms</option>
                                @if($pax_type!='ADULT')
                                <option value="Master" {{($title=='Master')?'selected':''}}>Master</option>
                                @endif
                            </select>
                            <a href="javascript:void(0);" class="slugEdit" id="EditPSlug" title="Edit"><i class="fa fa-pencil"></i></a>
                        </td>
                        <td class="name_passenger">
                           <div style="position:relative;">
                           <input type="text" id="first_name" name="first_name" class="form-control change_passenger readonly" value="{{$first_name}}">
                            <a href="javascript:void(0);" class="slugEdit" id="EditPSlug" title="Edit"><i class="fa fa-pencil"></i></a>
                           </div>
                            <div style="position:relative;">
                            <input type="text" id="last_name" name="last_name" class="form-control change_passenger readonly" value="{{$last_name}}" style="margin-top:5px;">
                            <a href="javascript:void(0);" class="slugEdit" id="EditPSlug" title="Edit"><i class="fa fa-pencil"></i></a>
                            </div>
                        </td>
                        <td>
                            {{$pnrDetails??''}}
                        </td>
                        <td>
                            <div style="position:relative;">
                                <input type="text" id="airline_ticket_no" name="airline_ticket_no" class="form-control change_passenger readonly" value="{{$airline_ticket_no}}">
                                <a href="javascript:void(0);" class="slugEdit" id="EditPSlug" title="Edit"><i class="fa fa-pencil"></i></a>
                            </div>
                        </td>
                        <td>
                            {{$order->supplierInfo->company_name??''}}
                        </td>
                        <td>
                            {{$order->supplierInfo->User->phone??''}}
                        </td>
                        <td>
                            {!!CustomHelper::showOrderStatusColor($order->orders_status)!!}
                        </td>
                        <td>
                            <a href="{{route($ADMIN_ROUTE_NAME.'.voucher.flight_voucher_pdf',[$order->id,'traveller_id'=>$traveller->id])}}" title="Print" ><i class="fas fa-print"></i></a> | 
                            <a href="javascript:void(0)" title="Email" class="individual_email" data-value="email"><i class="fas fa-envelope"></i></a> | 
                            <a href="{{route($ADMIN_ROUTE_NAME.'.voucher.flight_voucher_pdf',[$order->id,'traveller_id'=>$traveller->id])}}" title="Download" ><i class="fas fa-download"></i></a> | 
                            <a href="{{route($ADMIN_ROUTE_NAME.'.orders.details',[$order->order_no,'traveller_id'=>$traveller->id,'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                        </td>
                        @if(CustomHelper::checkPermission('activitylogs','view'))
                        <td>
                            <a href="{{ route($ADMIN_ROUTE_NAME.'.activitylogs.index','moduleid='.$order->id.'&module=Order&sub_module=OrderTraveller&sub_module_id='.$traveller->id) }}" title="View History" target="_blank"><i class="fas fa-history"></i></a>
                        </td>
                        @endif
                    </tr>
                <?php } } } ?>            
                <tr>
                    <td colspan="10"></td>
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
        @if(empty($inventory_id))
        {{ $records->appends(request()->query())->links('vendor.pagination.default') }}
        @endif
        <?php } else { ?>
        <div class="alert alert-warning top_title_admin">There is no dependent data present.</div>
        <?php } ?>
    </div>
</div>
@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {

    initSelect2();

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

$(document).on('change',"select.change_passenger",function(){
    var booking_id = $(this).parent().parent().find("input[name='booking_id[]']").val();
    var field_name = $(this).attr('name');
    var field_value = $(this).val();
    updateTraveller(booking_id, field_name, field_value);
});

$(document).on('blur',"input.change_passenger",function(){
    var booking_id = $(this).parent().parent().parent().find("input[name='booking_id[]']").val();
    var field_name = $(this).attr('name');
    var field_value = $(this).val();
    updateTraveller(booking_id, field_name, field_value);
});

function updateTraveller(booking_id, field_name, field_value) {
    var _token = '{{ csrf_token() }}';
    var formData = {};
    formData['action'] = 'update_traveller';
    formData['booking_id'] = booking_id;
    formData['field_name'] = field_name;
    formData['field_value'] = field_value;
    $.ajax({
        url: "{{ route($ADMIN_ROUTE_NAME.'.airline.booking_list')}}",
        type: "POST",
        data: formData,
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        beforeSend: function () {
            
        },
        success: function(resp) {
            if(resp.success) {

            } else if(resp.message) {
                alert(resp.message);
            }
        }
    });
}

$(document).on('click',"input[name=check_all]",function(){
    var check_all_checked = $(this).is(':checked');
    $("input[name='booking_id[]']").each(function(){
        $(this).prop( "checked", check_all_checked);
    });
});

$(document).on('click','.individual_email',function(){
    var action = $(this).attr('data-value');
    $('#bulk_action').val(action);
    $("input[name='booking_id[]']").each(function(){
        $(this).prop( "checked", false);
    });
    $(this).parent().parent().find("input[name='booking_id[]']").prop( "checked", true);
    setTimeout(function(){
        $('#booking_list').submit();
    },300);
});

$(document).on('click','.bulk_action',function(){
    var action = $(this).attr('data-value');
    $('#bulk_action').val(action);
    setTimeout(function(){
        $('#booking_list').submit();
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
    var formData = $('#booking_list').serialize();
    var _token = '{{ csrf_token() }}';
    $.ajax({
        url: "{{ route($ADMIN_ROUTE_NAME.'.airline.booking_list')}}",
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

function initSelect2() {
    $('.airline').select2({
        placeholder: "Please Select",
        allowClear: true,
        ajax: {
            url: "{{ route($ADMIN_ROUTE_NAME.'.airline.ajax_airline') }}",
            type: "POST",
            data: function (params) {
                return {
                    term: params.term
                };
            },
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            processResults: function (resp) {
                return {
                    results:  resp.items
                };
            }
        },
    });
}

// Remove the 'readonly' class//
// Get all input elements with the class 'form-control'
const inputElements = document.querySelectorAll('.form-control');

// Add event listeners for click event to all input elements
inputElements.forEach(inputElement => {
    inputElement.addEventListener('click', function() {
        // Remove the 'readonly' class when the input is clicked
        this.classList.remove('readonly');
    });
});






</script>
@endslot
@endcomponent