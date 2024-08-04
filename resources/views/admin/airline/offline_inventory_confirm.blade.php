@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.bordered {
    padding: 10px;
    /* color: #0c5460; */
    background-color: #9e9e9e33;
    border-color: #bee5eb;
    padding-bottom: 0;
    margin-bottom: 15px;
    box-shadow: 1px 0px 5px 0px rgb(0 0 0 / 15%) inset;
}
.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
    opacity: 1;
    font-style: normal;
}
.tooltip .tooltiptext {
    visibility: hidden;
    width: 330px;
    background-color: #ffeaea;
    color: #000;
    text-align: center;
    border-radius: 6px;
    padding: 10px;
    position: absolute;
    z-index: 1;
    bottom: 0;
}
.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>
@endslot

<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$id = (isset($record->id))?$record->id:'';
$name = (isset($record->name))?$record->name:'';
$trip_type = (isset($record->trip_type))?$record->trip_type:'';
$trip_type = old('trip_type',$trip_type);
$disable_before_departure_hour = (isset($record->disable_before_departure_hour))?$record->disable_before_departure_hour:'';
$sort_order = (isset($record->sort_order))?$record->sort_order:'';
$featured = (isset($record->featured))?$record->featured:'';
$featured = old('featured',$featured);
$status = (isset($record->status))?$record->status:1;
$status = old('status',$status);

$adult_price = 0;
$child_price = 0;
$infant_price = 0;

$supplier_id = (isset($order->supplier_id))?$order->supplier_id:'';
$supplier_id = old('supplier_id',$supplier_id);
$supplier_name = '';
if($supplier_id) {
    $userInfo = CustomHelper::getUserInfo($supplier_id);
    if($userInfo) {
        $company_brand = $userInfo['company_brand']??'';
        $company_name = $userInfo['company_name']??'';
        $company_owner_name = $userInfo['company_owner_name']??'';
        $supplier_name = $company_brand.' / '.$company_name.' ('.$company_owner_name.')';
    }
}

$pnrDetails = '';
$airline_ticket_no = '';
$OrderTraveller = $order->OrderTraveller??[];
if($OrderTraveller && isset($OrderTraveller[0])) {
  $pnrDetails = $OrderTraveller[0]->pnrDetails??'';
  $airline_ticket_no = $OrderTraveller[0]->airline_ticket_no??'';
}

$booking_details = json_decode($order->booking_details, true);
$totalPriceLists = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList']??[];
if($totalPriceLists) {
  foreach($totalPriceLists as $totalPriceList) {
    if(isset($totalPriceList['fd'])) {
        foreach($totalPriceList['fd'] as $pt => $val) {
          $NF = $val['fC']['NF']??0;
          $BF = $val['fC']['BF']??0;
          $TAF = $val['fC']['TAF']??0;
          $TF = $val['fC']['TF']??0;
          if($pt == 'ADULT') {
            $adult_price += $BF;
          } else if($pt == 'CHILD') {
            $child_price += $BF;
          } else if($pt == 'INFANT') {
            $infant_price = $BF;
          }
        }
    }
  }
}

$adult_price = 0;
$child_price = 0;
$infant_price = 0;

$segments = (isset($record->airlineRouteSegments))?$record->airlineRouteSegments:[];
$old_segments = old('segments');  
if($old_segments) {
    $segments = $old_segments;
}
$counter = 0;
?>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">

    <div class="col-md-6">
        <h2 class="top_title_admin enquiries-top">{{ $page_heading }}</h2>
    </div>
    <div class="col-md-6">
        <?php if($back_url){ ?>
        <a href="{{url($back_url)}}" class="btn_admin btn btn-success btn-sm" style='float: right;'>Back</a>
        <?php } else { ?>
        <a href="{{ route($ADMIN_ROUTE_NAME.'.airline_route.index')}}" class="btn_admin btn btn-success btn-sm" style='float: right;'>Back</a>
        <?php } ?>
    </div>
</div>
<div class="row top_title_admin enquiries-top centersec" style="min-height: auto; padding-left:0; padding-right:0;">

    <div class="col-md-12">
        <div class="bgcolor">
            <?php /*@include('snippets.errors')*/ ?>
            @include('snippets.flash')
            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data" onSubmit="return validateForm();" >
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3" style="display: none;">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Route Name:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group{{ $errors->has('disable_before_departure_hour') ? ' has-error' : '' }}">
                            <label for="disable_before_departure_hour" class="control-label required">
                                Disable Before Departure Hours:
                                <em class="tooltip">(i)<span class="tooltiptext">Hours Before Booking Not Allowed</span></em>
                            </label>
                            <input type="text" id="disable_before_departure_hour" class="form-control" name="disable_before_departure_hour" value="{{ old('disable_before_departure_hour', $disable_before_departure_hour) }}" />
                            @include('snippets.errors_first', ['param' => 'disable_before_departure_hour'])
                        </div>
                    </div>

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group{{ $errors->has('trip_type') ? ' has-error' : '' }}">
                            <label for="trip_type" class="control-label required">Type:</label>
                            <select class="form-control" name="trip_type">
                                <option value="Domestic" {{($trip_type=='Domestic')?'selected':''}}  >Domestic</option>
                                <option value="International" {{($trip_type=='International')?'selected':''}} >International</option>
                            </select>
                            @include('snippets.errors_first', ['param' => 'trip_type'])
                        </div>
                    </div>

                    <div class="clearfix" style="display: none;"></div>
                    <div class="col-sm-12" id="route_segments">
                        @if($segments && !empty($segments) && count($segments) > 0)
                            @foreach($segments as $counter => $segment)
                                @include('admin.airline_route._segment')                        
                            @endforeach
                        @else
                            @include('admin.airline_route._segment')                        
                        @endif
                    </div>

                    <div class="col-md-12">
                        <a href="javascript:void(0);" class="btn_admin btn btn-success btn-sm" style='float: right;' onClick="return addMoreSegments()" >Add More Segments</a>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-3 mb-2" style="display: none;">
                        <div class="form-group{{ $errors->has('pnrDetails') ? ' has-error' : '' }}">
                            <label for="pnrDetails" class="control-label required">Airline PNR:</label>
                            <input type="text" class="form-control" name="pnrDetails" value="{{old('pnrDetails',$pnrDetails)}}">
                            @include('snippets.errors_first', ['param' => 'pnrDetails'])
                        </div>
                    </div>

                    <div class="col-md-3 mb-2" style="display: none;">
                        <div class="form-group{{ $errors->has('airline_ticket_no') ? ' has-error' : '' }}">
                            <label for="airline_ticket_no" class="control-label">Airline Ticket No:</label>
                            <input type="text" class="form-control" name="airline_ticket_no" value="{{old('airline_ticket_no',$airline_ticket_no)}}">
                            @include('snippets.errors_first', ['param' => 'airline_ticket_no'])
                        </div>
                    </div>

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group{{ $errors->has('supplier_id') ? ' has-error' : '' }}">
                            <label for="source" class="control-label required">Agent:</label>
                            <select class="form-control select2 supplier_id" name="supplier_id">
                                @if($supplier_id)
                                <option value="{{$supplier_id}}" selected >{{$supplier_name}}</option>
                                @endif
                            </select>
                            @include('snippets.errors_first', ['param' => 'supplier_id'])
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-3" style="display: {{(empty($adult_price))?'none':'block'}}">
                        <div class="form-group">
                            <label for="adult_price" class="control-label required">Adult:</label>
                            <input type="text" name="adult_price" value="{{ old('adult_price', $adult_price) }}" id="adult_price" class="form-control"/>
                            @include('snippets.errors_first', ['param' => 'adult_price'])
                        </div>
                    </div>
                    <div class="col-md-3" style="display: {{(empty($child_price))?'none':'block'}}">
                        <div class="form-group">
                            <label for="child_price" class="control-label required">Child:</label>
                            <input type="text" name="child_price" value="{{ old('child_price', $child_price) }}" id="child_price" class="form-control"/>
                            @include('snippets.errors_first', ['param' => 'child_price'])
                        </div>
                    </div>
                    <div class="col-md-3" style="display: {{(empty($infant_price))?'none':'block'}}">
                        <div class="form-group">
                            <label for="infant_price" class="control-label required">Infant:</label>
                            <input type="text" name="infant_price" value="{{ old('infant_price', $infant_price) }}" id="infant_price" class="form-control"/>
                            @include('snippets.errors_first', ['param' => 'infant_price'])
                        </div>
                    </div>

                    <div class="col-md-3" style="display: none;">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Sort Order:</label>
                            <input type="text" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}" />

                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>
                    <div class="clearfix" style="display: none;"></div>
                    <div class="col-md-3" style="display: none;">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="control-label">Status:</label>
                            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                    </div>
                    <div class="col-md-3" style="display: none;">
                        <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
                            <label for="featured" class="control-label">Featured:</label>
                            <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> >
                            @include('snippets.errors_first', ['param' => 'featured'])
                        </div>
                    </div>
                </div>
                <div class="col-full">
                    <div class="form-group">
                        <input type="hidden" name="back_url" value="{{ $back_url }}" >
                        <button type="submit" name="submit" id="submit_btn" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>
                        <button type="button" id="processing_btn" class="btn btn-success" title="Submit" disabled style="display: none;" ><i class="fa fa-processing"></i> Processing...</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div> 
</div>
<div class="clearfix"></div>
</div>
@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    initSelect2();
});

function initSelect2() {
    $('.source').select2({
        placeholder: "Please Select",
        allowClear: true,
        ajax: {
            url: "{{ route('ajax_airports') }}",
            type: "POST",
            data: function (params) {
                return {
                    term: params.term,
                    action: 'flight_route'
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
    $('.destination').select2({
        placeholder: "Please Select",
        allowClear: true,
        ajax: {
            url: "{{ route('ajax_airports') }}",
            type: "POST",
            data: function (params) {
                return {
                    term: params.term,
                    action: 'flight_route'
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
    $('.supplier_id').select2({
        placeholder: "Please Select",
        allowClear: true,
        ajax: {
            url: "{{ route($ADMIN_ROUTE_NAME.'.register-agents.ajax_agent_list') }}",
            type: "POST",
            data: function (params) {
                return {
                    term: params.term,
                    type: 'offline_flight'
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

let counter = <?php echo $counter; ?>;
function addMoreSegments () {
    var _token = '{{ csrf_token() }}';
    counter = counter + 1;
    $.ajax({
        url: "{{ route($ADMIN_ROUTE_NAME.'.airline_route.ajax_more_segments') }}",
        type: "POST",
        data: {counter},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        async: true,
        beforeSend:function(){

        },
        success: function(resp) {
            if(resp.success){
                $("#route_segments").append(resp.html);
                initSelect2();
            } else {
            }
        }
    });
    return false;
}
function removeMoreSegments(counter) {
    $('.segment_row_'+counter).remove();
}
function validateForm() {
    $('#submit_btn').hide();
    $('#processing_btn').show();
    return true;
}
</script>
@endslot

@endcomponent