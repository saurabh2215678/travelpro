@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .crop_change { padding-bottom:35px; }
    .user_profile_inner .right_info .btn2{font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
    .col-md-2 {width: 16.66666667%; padding-right: 15px; padding-left: 15px;}
    .col-md-12 {width: 100%;padding-right: 15px; padding-left: 15px;}
    .labeltxt {background: #009688; line-height: normal; padding: 5px; color: #fff;}
    @media (max-width: 767px){ 
        .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
        width: 100%;
        padding:0;
        }
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot
<?php
$fare_type = (isset($record) && isset($record->fare_type))?$record->fare_type:'2';
$fare_type = old('fare_type',$fare_type);
$airline_route_id = (isset($record) && isset($record->id))?$record->airline_route_id:'';
$airline_route_id = old('airline_route_id',$airline_route_id);
$flight_class = (isset($record) && isset($record->id))?$record->flight_class:'Economy';
$flight_class = old('flight_class',$flight_class);
$available_for = (isset($record) && isset($record->available_for))?$record->available_for:'all_customer_agent';
$available_for = old('available_for',$available_for);
$start_date = (isset($record) && isset($record->id))?$record->start_date:'';
if($start_date) {
    $start_date = CustomHelper::DateFormat($start_date,'d/m/Y','Y-m-d');
}
$start_date = old('start_date',$start_date);
$end_date = (isset($record) && isset($record->id))?$record->end_date:'';
if($end_date) {
    $end_date = CustomHelper::DateFormat($end_date,'d/m/Y','Y-m-d');
}
$end_date = old('end_date',$end_date);
$initial_inventory = (isset($record) && isset($record->initial_inventory))?$record->initial_inventory:'';
$inventory = (isset($record) && isset($record->inventory))?$record->inventory:'';
$pnr = (isset($record) && isset($record->pnr))?$record->pnr:'';
$airline_ticket_no = (isset($record) && isset($record->airline_ticket_no))?$record->airline_ticket_no:'';
$agent_adult_price = (isset($record) && isset($record->agent_adult_price))?$record->agent_adult_price:'';
$agent_child_price = (isset($record) && isset($record->agent_child_price))?$record->agent_child_price:'';
$agent_infant_price = (isset($record) && isset($record->agent_infant_price))?$record->agent_infant_price:'';
$adult_price = (isset($record) && isset($record->adult_price))?$record->adult_price:'';
$child_price = (isset($record) && isset($record->child_price))?$record->child_price:'';
$infant_price = (isset($record) && isset($record->infant_price))?$record->infant_price:'';
$is_refundable = (isset($record) && isset($record->is_refundable))?$record->is_refundable:0;
$is_refundable = old('is_refundable',$is_refundable);
$remark = (isset($record) && isset($record->remark))?$record->remark:'';
$status = (isset($record) && isset($record->id))?$record->status:1;

$flight_available_for_arr = config('custom.agent_flight_available_for_arr');
$flight_class_arr = config('custom.flight_class_arr');
?>
<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">{{$page_heading}}</h1>
                        </div>
                    </div>                 
                </div>
                @include('snippets.front.flash')
                <div class="table_scroll">
                    <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="w-full dflex flex-wrap mt-3">
                            <div class="mb-2 px-4 pb-0 w-2/6" style="display: none;">
                                <div class="form-group{{ $errors->has('fare_type') ? ' has-error' : '' }}" style="display: flex;column-gap: 15px;">
                                <div class="inline_btn">
                                    Instant Offer Fare: <input type="radio" name="fare_type" value="2" {{($fare_type==2)?'checked':''}} >
                                    @include('snippets.front.errors_first', ['param' => 'fare_type'])
                                    </div>
                                    <div class="inline_btn">
                                    Offer Fare: <input type="radio" name="fare_type" value="1" {{($fare_type==1)?'checked':''}} >
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 px-4 pb-0 w-1/2 flight_route">
                                <div class="form-group{{ $errors->has('airline_route_id') ? ' has-error' : '' }}">
                                    <label for="airline_route_id">Flight Route<em>*</em>:</label>
                                    <select name="airline_route_id" id="airline_route_id" class="form-control select2">
                                        @if($airline_route_id)
                                        <option value="{{$airline_route_id}}" selected >{{CustomHelper::showAirlineRouteName($airline_route_id)}}</option>
                                        @endif
                                    </select>
                                    @include('snippets.front.errors_first', ['param' => 'airline_route_id'])
                                </div>
                            </div>
                            <div class="mb-2 px-4 pb-0 col-md-3">
                                <div class="form-group{{ $errors->has('available_for') ? ' has-error' : '' }}">
                                    <label for="available_for">Available For<em>*</em>:</label>
                                    <select class="form-control" name="available_for">
                                        @foreach($flight_available_for_arr as $key => $val)
                                        <option value="{{$key}}" {{($key==$available_for)?'selected':''}} >{{$val}}</option>
                                        @endforeach
                                    </select>
                                    @include('snippets.front.errors_first', ['param' => 'available_for'])
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="mb-2 px-4 pb-0 col-md-3">
                                <div class="form-group{{ $errors->has('flight_class') ? ' has-error' : '' }}">
                                    <label for="flight_class">Flight Class<em>*</em>:</label>
                                    <select class="form-control" name="flight_class">
                                        @foreach($flight_class_arr as $key => $val)
                                        <option value="{{$key}}" {{($key==$flight_class)?'selected':''}} >{{$val}}</option>
                                        @endforeach
                                    </select>
                                    @include('snippets.front.errors_first', ['param' => 'flight_class'])
                                </div>
                            </div>
                            
                            <div class="col-md-2 mb-2">
                                <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                    <label for="start_date">Departure Date<em>*</em>:</label>
                                    <input type="text" class="form-control" name="start_date" id="start_date" value="{{$start_date}}" readonly >
                                    @include('snippets.front.errors_first', ['param' => 'start_date'])
                                </div>
                            </div>
 
                            <div class="col-md-2 mb-2" style="display: none;">
                                <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
                                    <label for="end_date">Arrival Date<em>*</em>:</label>
                                    <input type="text" class="form-control" name="end_date" id="end_date" value="{{$end_date}}" readonly >
                                    @include('snippets.front.errors_first', ['param' => 'end_date'])
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <div class="form-group{{ $errors->has('initial_inventory') ? ' has-error' : '' }}">
                                    <label for="initial_inventory" class="control-label">Tickets In Stock<em>*</em>:</label>
                                    <input type="text" class="form-control" name="initial_inventory" value="{{old('initial_inventory',$initial_inventory)}}">
                                    @include('snippets.front.errors_first', ['param' => 'initial_inventory'])
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <div class="form-group{{ $errors->has('inventory') ? ' has-error' : '' }}">
                                    <label for="inventory" class="control-label">Tickets for Sale<em>*</em>:</label>
                                    <input type="text" class="form-control" name="inventory" value="{{old('inventory',$inventory)}}">
                                    @include('snippets.front.errors_first', ['param' => 'inventory'])
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group{{ $errors->has('pnr') ? ' has-error' : '' }}">
                                    <label for="pnr" class="control-label">Airline PNR<em>*</em>:</label>
                                    <input type="text" class="form-control" name="pnr" value="{{old('pnr',$pnr)}}">
                                    @include('snippets.front.errors_first', ['param' => 'pnr'])
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group{{ $errors->has('airline_ticket_no') ? ' has-error' : '' }}">
                                    <label for="airline_ticket_no" class="control-label">E-Ticket:</label>
                                    <input type="text" class="form-control" name="airline_ticket_no" value="{{old('airline_ticket_no',$airline_ticket_no)}}">
                                    @include('snippets.front.errors_first', ['param' => 'airline_ticket_no'])
                                </div>
                            </div>

                            <div class="col-md-12">
                            <label for="agent_adult_price" class="control-label labeltxt">Actual Price:</label>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-2">
                                <div class="form-group{{ $errors->has('agent_adult_price') ? ' has-error' : '' }}">
                                    <label for="agent_adult_price" class="control-label">Adult<em>*</em>:</label>
                                    <input type="text" class="form-control" name="agent_adult_price" value="{{old('agent_adult_price',$agent_adult_price)}}">
                                    @include('snippets.front.errors_first', ['param' => 'agent_adult_price'])
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group{{ $errors->has('agent_child_price') ? ' has-error' : '' }}">
                                    <label for="agent_child_price" class="control-label">Child:</label>
                                    <input type="text" class="form-control" name="agent_child_price" value="{{old('agent_child_price',$agent_child_price)}}">
                                    @include('snippets.front.errors_first', ['param' => 'agent_child_price'])
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group{{ $errors->has('agent_infant_price') ? ' has-error' : '' }}">
                                    <label for="agent_infant_price" class="control-label">Infant:</label>
                                    <input type="text" class="form-control" name="agent_infant_price" value="{{old('agent_infant_price',$agent_infant_price)}}" >
                                    @include('snippets.front.errors_first', ['param' => 'agent_infant_price'])
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                            <label for="agent_adult_price" class="control-label labeltxt">Selling Price:</label>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-2">
                                <div class="form-group{{ $errors->has('adult_price') ? ' has-error' : '' }}">
                                    <label for="adult_price" class="control-label">Adult<em>*</em>:</label>
                                    <input type="text" class="form-control" name="adult_price" value="{{old('adult_price',$adult_price)}}">
                                    @include('snippets.front.errors_first', ['param' => 'adult_price'])
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group{{ $errors->has('child_price') ? ' has-error' : '' }}">
                                    <label for="child_price" class="control-label">Child:</label>
                                    <input type="text" class="form-control" name="child_price" value="{{old('child_price',$child_price)}}">
                                    @include('snippets.front.errors_first', ['param' => 'child_price'])
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group{{ $errors->has('infant_price') ? ' has-error' : '' }}">
                                    <label for="infant_price" class="control-label">Infant:</label>
                                    <input type="text" class="form-control" name="infant_price" value="{{old('infant_price',$infant_price)}}" >
                                    @include('snippets.front.errors_first', ['param' => 'infant_price'])
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-2 mt-2">
                                <div class="form-group{{ $errors->has('is_refundable') ? ' has-error' : '' }}" style=" white-space: nowrap; ">
                                    <label for="is_refundable" class="control-label">Is Refundable:</label>
                                    <input class="w-auto" type="checkbox" name="is_refundable" value="1" {{($is_refundable==1)?'checked':''}} > Yes
                                    @include('snippets.front.errors_first', ['param' => 'is_refundable'])
                                </div>
                            </div>

                            <div class="col-md-12 mb-2 mt-2">
                                <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                    <label for="remark" class="control-label">Remark:</label>
                                    <textarea rows="10" name="remark" class="form-control">{{old('remark',$remark)}}</textarea>
                                    @include('snippets.front.errors_first', ['param' => 'remark'])
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group flex">
                                    <input type="submit" name="submit" class="btn s-btn btn-info sky_blue rounded-full w-auto cursor-pointer" value="Save">
                                    <a onclick="history.back()" class="btn2 edit_pofile_btn cursor-pointer ml-3">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>               
        </div>
    </div>
</section>

@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
$( function() {
        $( "#start_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            minDate: 0,
        });
        $( "#end_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            minDate: 0
        });

        $('#airline_route_id').select2({
            placeholder: "Please Select",
            allowClear: true,
            ajax: {
                url: "{{ route('user.ajax_airline_route') }}",
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
    } );
</script>
@endslot

@endcomponent


