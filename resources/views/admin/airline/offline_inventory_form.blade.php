@component('admin.layouts.main')
@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
<style>
    .inline-markup span {
    padding-right: 20px;
    display: inline-block;
    margin-bottom: 10px;
}
.form-group.inline-markup {
    margin-bottom: 0;
}
.btn1search {
    margin-top: 0;
}
</style>
@endslot
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$fare_type = (isset($record) && isset($record->fare_type))?$record->fare_type:'1';
$fare_type = old('fare_type',$fare_type);
$airline_route_id = (isset($record) && isset($record->id))?$record->airline_route_id:'';
$airline_route_id = old('airline_route_id',$airline_route_id);
$flight_class = (isset($record) && isset($record->id))?$record->flight_class:'Economy';
$flight_class = old('flight_class',$flight_class);
$available_for = (isset($record) && isset($record->available_for))?$record->available_for:'all_customer_agent';
$available_for = old('available_for',$available_for);

$p_agents = (isset($record) && isset($record->p_agents))?$record->p_agents:'';
if($p_agents && is_array($p_agents)) {
    $p_agents_arr = $p_agents;
} else {
    $p_agents_arr = explode(',', $p_agents);
}
$p_agents = old('p_agents',$p_agents_arr);

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
$admin_adult_price = (isset($record) && isset($record->admin_adult_price))?$record->admin_adult_price:'';
$admin_child_price = (isset($record) && isset($record->admin_child_price))?$record->admin_child_price:'';
$admin_infant_price = (isset($record) && isset($record->admin_infant_price))?$record->admin_infant_price:'';

// $markup = (isset($record) && isset($record->markup))?$record->markup:'';

$is_refundable = (isset($record) && isset($record->is_refundable))?$record->is_refundable:0;
$is_refundable = old('is_refundable',$is_refundable);
$remark = (isset($record) && isset($record->remark))?$record->remark:'';
$status = (isset($record) && isset($record->id))?$record->status:1;
$user_id = (isset($record) && isset($record->user_id))?$record->user_id:0;

$flight_available_for_arr = config('custom.flight_available_for_arr');
$flight_class_arr = config('custom.flight_class_arr');
?>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">
    <div class="col-md-6">
        <h2 class="top_title_admin enquiries-top">{{ $page_heading }} ({{($user_id)?'Instant Offer Fare':'Offer Fare'}})</h2>
    </div>
    <div class="col-md-6">
        <?php if($back_url){ ?>
        <a href="{{url($back_url)}}" class="btn_admin btn btn-success btn-sm" style="float: right;">Back</a>
        <?php } else { ?>
        <a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory')}}" class="btn_admin btn btn-success btn-sm" style="float: right;" >Back</a>
        <?php } ?>
    </div>
</div>
<div class="top_title_admin enquiries-top centersec" style="min-height: auto; padding-left:0; padding-right:0;">

    <div class="col-md">
        <div class="bgcolor">
            @include('snippets.flash')
            <div class="ajax_msg"></div>
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="w-full dflex flex-wrap mt-3">
                            <div class="mb-2 px-4 pb-0 w-2/6" style="display: none;">
                                <div class="form-group{{ $errors->has('fare_type') ? ' has-error' : '' }}" style="display: flex;column-gap: 15px;">
                                    <div class="inline_btn">
                                    Instant Offer Fare: <input type="radio" name="fare_type" value="2" {{($fare_type==2)?'checked':''}} >
                                    @include('snippets.errors_first', ['param' => 'fare_type'])
                                    </div>
                                    <div class="inline_btn">
                                    Offer Fare: <input type="radio" name="fare_type" value="1" {{($fare_type==1)?'checked':''}} >
                                    </div>                                    
                                </div>
                            </div>
                            <div class="mb-2 px-4 pb-0 col-md-4 flight_route">
                                <div class="form-group{{ $errors->has('airline_route_id') ? ' has-error' : '' }}">
                                    <label for="airline_route_id" class="required">Airline Route:</label>
                                    <select name="airline_route_id" id="airline_route_id" class="form-control select2">
                                        @if($airline_route_id)
                                        <option value="{{$airline_route_id}}" selected >{{CustomHelper::showAirlineRouteName($airline_route_id)}}</option>
                                        @endif
                                    </select>
                                    @include('snippets.errors_first', ['param' => 'airline_route_id'])
                                </div>
                            </div>

                            <div class="mb-2 px-4 pb-0 col-md-3">
                                <div class="form-group{{ $errors->has('flight_class') ? ' has-error' : '' }}">
                                    <label for="flight_class" class="required">Flight Class:</label>
                                    <select class="form-control" name="flight_class">
                                        @foreach($flight_class_arr as $key => $val)
                                        <option value="{{$key}}" {{($key==$flight_class)?'selected':''}} >{{$val}}</option>
                                        @endforeach
                                    </select>
                                    @include('snippets.errors_first', ['param' => 'flight_class'])
                                </div>
                            </div>

                            <div class="mb-2 px-4 pb-0 col-md-3">
                                <div class="form-group{{ $errors->has('available_for') ? ' has-error' : '' }}">
                                    <label for="available_for" class="required">Inventory Available For:</label>
                                    <select class="form-control" name="available_for" id="available_for" >
                                        @foreach($flight_available_for_arr as $key => $val)
                                        <option value="{{$key}}" {{($key==$available_for)?'selected':''}} >{{$val}}</option>
                                        @endforeach
                                    </select>
                                    @include('snippets.errors_first', ['param' => 'available_for'])
                                </div>
                            </div>

                            <div class="mb-2 px-4 pb-0 col-md-4" id="p_agents_div" style="display: none;">
                                <div class="form-group{{ $errors->has('p_agents') ? ' has-error' : '' }}">
                                    <label for="p_agents" class="required">Available Agents:</label>
                                    <select name="p_agents[]" id="p_agents" class="form-control select2" multiple="multiple" >
                                        @if($p_agents)
                                        @foreach($p_agents as $agent_id)
                                        <option value="{{$agent_id}}" selected >{{CustomHelper::showUserName($agent_id)}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @include('snippets.errors_first', ['param' => 'p_agents'])
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-2 mb-2">
                                <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                    <label for="start_date" class="required">Departure Date:</label>
                                    <input type="text" class="form-control" name="start_date" id="start_date" value="{{$start_date}}" readonly >
                                    @include('snippets.errors_first', ['param' => 'start_date'])
                                </div>
                            </div>

                            <div class="col-md-2 mb-2" style="display: none;">
                                <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
                                    <label for="end_date" class="required">End Date:</label>
                                    <input type="text" class="form-control" name="end_date" id="end_date" value="{{$end_date}}" readonly >
                                    @include('snippets.errors_first', ['param' => 'end_date'])
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <div class="form-group{{ $errors->has('initial_inventory') ? ' has-error' : '' }}">
                                    <label for="initial_inventory" class="control-label required">Tickets In Stock:</label>
                                    <input type="text" class="form-control" name="initial_inventory" value="{{old('initial_inventory',$initial_inventory)}}">
                                    @include('snippets.errors_first', ['param' => 'initial_inventory'])
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <div class="form-group{{ $errors->has('inventory') ? ' has-error' : '' }}">
                                    <label for="inventory" class="control-label required">Tickets for Sale:</label>
                                    <input type="text" class="form-control" name="inventory" value="{{old('inventory',$inventory)}}">
                                    @include('snippets.errors_first', ['param' => 'inventory'])
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group{{ $errors->has('pnr') ? ' has-error' : '' }}">
                                    <label for="pnr" class="control-label{{(!empty($user_id))?' required':''}} ">Airline PNR:</label>
                                    <input type="text" class="form-control" name="pnr" value="{{old('pnr',$pnr)}}">
                                    @include('snippets.errors_first', ['param' => 'pnr'])
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="form-group{{ $errors->has('airline_ticket_no') ? ' has-error' : '' }}">
                                    <label for="airline_ticket_no" class="control-label">E-Ticket:</label>
                                    <input type="text" class="form-control" name="airline_ticket_no" value="{{old('airline_ticket_no',$airline_ticket_no)}}">
                                    @include('snippets.errors_first', ['param' => 'airline_ticket_no'])
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-6 price_wrapper" style="display: {{($fare_type==1)?'none':'block'}};">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="end_time" class="control-label labeltxt">Agent Actual Price:</label>
                                        </div>
                                        <div class="col-md-4" style="display: {{($fare_type==1)?'none':'block'}};" >
                                            <div class="form-group{{ $errors->has('agent_adult_price') ? ' has-error' : '' }}">
                                                <label for="agent_adult_price" class="control-label required">Adult:</label>
                                                <input type="text" class="form-control" name="agent_adult_price" value="{{old('agent_adult_price',$agent_adult_price)}}">
                                                @include('snippets.errors_first', ['param' => 'agent_adult_price'])
                                            </div>
                                        </div>

                                        <div class="col-md-4" style="display: {{($fare_type==1)?'none':'block'}};">
                                            <div class="form-group{{ $errors->has('agent_child_price') ? ' has-error' : '' }}">
                                                <label for="agent_child_price" class="control-label">Child:</label>
                                                <input type="text" class="form-control" name="agent_child_price" value="{{old('agent_child_price',$agent_child_price)}}">
                                                @include('snippets.errors_first', ['param' => 'agent_child_price'])
                                            </div>
                                        </div>

                                        <div class="col-md-4" style="display: {{($fare_type==1)?'none':'block'}};">
                                            <div class="form-group{{ $errors->has('agent_infant_price') ? ' has-error' : '' }}">
                                                <label for="agent_infant_price" class="control-label">Infant:</label>
                                                <input type="text" class="form-control" name="agent_infant_price" value="{{old('agent_infant_price',$agent_infant_price)}}" >
                                                @include('snippets.errors_first', ['param' => 'agent_infant_price'])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-6 price_wrapper" style="display: {{($fare_type==1)?'none':'block'}};">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="end_time" class="control-label labeltxt">Agent Selling Price:</label>
                                        </div>
                                        <div class="col-md-4" style="display: {{($fare_type==1)?'none':'block'}};">
                                            <div class="form-group{{ $errors->has('adult_price') ? ' has-error' : '' }}">
                                                <label for="adult_price" class="control-label required">Adult:</label>
                                                <input type="text" class="form-control" name="adult_price" value="{{old('adult_price',$adult_price)}}">
                                                @include('snippets.errors_first', ['param' => 'adult_price'])
                                            </div>
                                        </div>

                                        <div class="col-md-4" style="display: {{($fare_type==1)?'none':'block'}};">
                                            <div class="form-group{{ $errors->has('child_price') ? ' has-error' : '' }}">
                                                <label for="child_price" class="control-label">Child:</label>
                                                <input type="text" class="form-control" name="child_price" value="{{old('child_price',$child_price)}}">
                                                @include('snippets.errors_first', ['param' => 'child_price'])
                                            </div>
                                        </div>

                                        <div class="col-md-4" style="display: {{($fare_type==1)?'none':'block'}};">
                                            <div class="form-group{{ $errors->has('infant_price') ? ' has-error' : '' }}">
                                                <label for="infant_price" class="control-label">Infant:</label>
                                                <input type="text" class="form-control" name="infant_price" value="{{old('infant_price',$infant_price)}}" >
                                                @include('snippets.errors_first', ['param' => 'infant_price'])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                              <div class="clearfix"></div>
                              <div class="col-md-6 price_wrapper">
                             <div class="form-group">
                                <div class="row">
                                 <div class="col-md-12">
                                   <label for="end_time" class="control-label labeltxt">Admin Price:</label>
                                  </div>
                                  <div class="col-md-4">
                                <div class="form-group{{ $errors->has('admin_adult_price') ? ' has-error' : '' }}">
                                    <label for="admin_adult_price" class="control-label required">Adult:</label>
                                    <input type="text" class="form-control" name="admin_adult_price" value="{{old('admin_adult_price',$admin_adult_price)}}">
                                    @include('snippets.errors_first', ['param' => 'admin_adult_price'])
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group{{ $errors->has('admin_child_price') ? ' has-error' : '' }}">
                                    <label for="admin_child_price" class="control-label">Child:</label>
                                    <input type="text" class="form-control" name="admin_child_price" value="{{old('admin_child_price',$admin_child_price)}}">
                                    @include('snippets.errors_first', ['param' => 'admin_child_price'])
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group{{ $errors->has('admin_infant_price') ? ' has-error' : '' }}">
                                    <label for="admin_infant_price" class="control-label">Infant:</label>
                                    <input type="text" class="form-control" name="admin_infant_price" value="{{old('admin_infant_price',$admin_infant_price)}}" >
                                    @include('snippets.errors_first', ['param' => 'admin_infant_price'])
                                </div>
                            </div>

                                  </div>
                                  </div>
                                  </div>

                            
                                  <div class="col-md-6 price_wrapper" style="padding-top:28px;">
                                    <div class="form-group">
                                         <div class="row">
                                            <?php /*
                                            <div class="col-md-3">
                                                <div class="form-group{{ $errors->has('markup') ? ' has-error' : '' }}">
                                                    <label for="markup" class="control-label">Markup:</label>
                                                    <input type="text" class="form-control" name="markup" value="{{old('markup',$markup)}}">
                                                    @include('snippets.errors_first', ['param' => 'markup'])
                                                </div>
                                            </div>*/ ?>

                                            <div class="col-md-6 mt-2">
                                                <div class="form-group{{ $errors->has('is_refundable') ? ' has-error' : '' }}">
                                                    <label for="is_refundable" class="control-label">Is Refundable:</label> <br />
                                                    <input class="w-auto" type="checkbox" name="is_refundable" value="1" {{($is_refundable==1)?'checked':''}} > Yes
                                                    @include('snippets.errors_first', ['param' => 'is_refundable'])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            

                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                    <label for="remark" class="control-label">Remark:</label>
                                    <textarea name="remark" class="form-control">{{old('remark',$remark)}}</textarea>
                                    @include('snippets.errors_first', ['param' => 'remark'])
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label for="status" class="control-label">Status:</label>
                                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                                    &nbsp;
                                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                                    @include('snippets.errors_first', ['param' => 'status'])
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-3">
                                <div class="form-group flex">
                                    <input type="hidden" name="back_url" value="{{$back_url}}">
                                    <input type="submit" name="submit" class="btn btn-success btn1search rounded-full w-auto cursor-pointer" value="Save">
                                    <a onclick="history.back()" class="btn_admin2 btn resetbtn btn-primary btn1search">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>

        
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
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
            url: "{{ route($ADMIN_ROUTE_NAME.'.airline_route.ajax_airline_route') }}",
            type: "POST",
            data: function (params) {
                return {
                    term: params.term,
                    user_id: '{{$user_id}}'
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

    $('#p_agents').select2({
        placeholder: "Please Select",
        allowClear: true,
        width: '100%',
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

    $('#available_for').on('change',function(){
        var available_for = $(this).val();
        showHidePAgentsDiv(available_for);
    });
    showHidePAgentsDiv('<?php echo $available_for; ?>');
});
function showHidePAgentsDiv (available_for) {
    if(available_for == 'p_agents') {
        $('#p_agents_div').show();
    } else {
        $('#p_agents_div').hide();
    }
}
</script>
@endslot


@endcomponent