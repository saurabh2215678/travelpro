<?php
$counter = (isset($counter))?$counter:0;
// prd($errors);
$segments = old('segments');
$source = (isset($segment->source))?$segment->source:'';
$source_terminal = (isset($segment->source_terminal))?$segment->source_terminal:'';
$destination = (isset($segment->destination))?$segment->destination:'';
$destination = old('destination',$destination);
$destination_terminal = (isset($segment->destination_terminal))?$segment->destination_terminal:'';
$start_time = (isset($segment->start_time))?CustomHelper::DateFormat($segment->start_time,'H:i'):'';
$start_time = old('start_time',$start_time);

$end_time = (isset($segment->end_time))?CustomHelper::DateFormat($segment->end_time,'H:i'):'';
$end_time = old('end_time',$end_time);
$is_arrival_next_day = (isset($segment->is_arrival_next_day))?$segment->is_arrival_next_day:'';
$is_arrival_next_day = old('is_arrival_next_day',$is_arrival_next_day);
$airline = (isset($segment->airline))?$segment->airline:'';
$airline = old('airline',$airline);
$flight_number = (isset($segment->flight_number))?$segment->flight_number:'';

$stops = (isset($segment->stops))?$segment->stops:'';
$stops = old('stops',$stops);

$cabin_adult = (isset($segment->cabin_adult))?$segment->cabin_adult:'';
$cabin_child = (isset($segment->cabin_child))?$segment->cabin_child:'';
$cabin_infant = (isset($segment->cabin_infant))?$segment->cabin_infant:'';

$checkin_adult = (isset($segment->checkin_adult))?$segment->checkin_adult:'';
$checkin_child = (isset($segment->checkin_child))?$segment->checkin_child:'';
$checkin_infant = (isset($segment->checkin_infant))?$segment->checkin_infant:'';

$time_range = CustomHelper::create_time_range('00:00','23:59','5 mins',24);
if($segments) {
    $source = $segments[$counter]['source']??'';
    $source_terminal = $segments[$counter]['source_terminal']??'';
    $destination = $segments[$counter]['destination']??'';
    $destination_terminal = $segments[$counter]['destination_terminal']??'';
    $start_time = $segments[$counter]['start_time']??'';
    $end_time = $segments[$counter]['end_time']??'';
    $is_arrival_next_day = $segments[$counter]['is_arrival_next_day']??'';
    $airline = $segments[$counter]['airline']??'';
    $flight_number = $segments[$counter]['flight_number']??'';
    $cabin_adult = $segments[$counter]['cabin_adult']??'';
    $cabin_child = $segments[$counter]['cabin_child']??'';
    $cabin_infant = $segments[$counter]['cabin_infant']??'';
    $checkin_adult = $segments[$counter]['checkin_adult']??'';
    $checkin_child = $segments[$counter]['checkin_child']??'';
    $checkin_infant = $segments[$counter]['checkin_infant']??'';
}
$flight_stops_arr = config('custom.flight_stops_arr');
?>
<div class="clearfix segment_row_{{$counter}}">
    <hr />
    <a href="javascript:void(0);" class="btn_admin btn btn-success btn-sm" style='float: right;' onClick="return removeMoreSegments({{$counter}})" >X</a>
</div>
<div class="row bordered segment_row_{{$counter}}">
    <div class="col-md-3">
        <div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
            <label for="source" class="control-label required">Source:</label>
            <select class="form-control select2 source" name="segments[{{$counter}}][source]">
                @if($source)
                <option value="{{$source}}" selected >{{CustomHelper::showAirportName($source)}}</option>
                @endif
            </select>
            @include('snippets.errors_first', ['param' => "segments.$counter.source"])
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('source_terminal') ? ' has-error' : '' }}">
            <label for="source_terminal" class="control-label required">Source Terminal:</label>
            <input type="text" class="form-control" name="segments[{{$counter}}][source_terminal]" value="{{ old('source_terminal', $source_terminal) }}" />
            @include('snippets.errors_first', ['param' => "segments.$counter.source_terminal"])
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('destination') ? ' has-error' : '' }}">
            <label for="destination" class="control-label required">Destination:</label>
            <select class="form-control select2 destination" name="segments[{{$counter}}][destination]">
                @if($destination)
                <option value="{{$destination}}" selected >{{CustomHelper::showAirportName($destination)}}</option>
                @endif
            </select>
            @include('snippets.errors_first', ['param' => "segments.$counter.destination"])
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('destination_terminal') ? ' has-error' : '' }}">
            <label for="destination_terminal" class="control-label required">Desination Terminal:</label>
            <input type="text" class="form-control" name="segments[{{$counter}}][destination_terminal]" value="{{ old('destination_terminal', $destination_terminal) }}" />
            @include('snippets.errors_first', ['param' => "segments.$counter.destination_terminal"])
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-md-2">
        <div class="form-group{{ $errors->has('airline') ? ' has-error' : '' }}">
            <label for="airline" class="control-label required">Airline:</label>
            <select class="form-control select2 airline" name="segments[{{$counter}}][airline]">
                @if($airline)
                <option value="{{$airline}}" selected >{{CustomHelper::showAirlineName($airline)}}</option>
                @endif
            </select>
            @include('snippets.errors_first', ['param' => "segments.$counter.airline"])
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group{{ $errors->has('flight_number') ? ' has-error' : '' }}">
            <label for="flight_number" class="control-label required">Flight Number:</label>
            <input type="text" class="form-control" name="segments[{{$counter}}][flight_number]" value="{{ old('flight_number', $flight_number) }}" />
            @include('snippets.errors_first', ['param' => "segments.$counter.flight_number"])
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
            <label for="start_time" class="control-label required">Departure Time:</label>
            <select class="form-control" name="segments[{{$counter}}][start_time]">
                <option value="">Select</option>
                @foreach($time_range as $key => $val)
                <option value="{{$key}}" {{($key==$start_time)?'selected':''}} >{{$val}}</option>
                @endforeach
            </select>
            @include('snippets.errors_first', ['param' => "segments.$counter.start_time"])
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
            <label for="end_time" class="control-label required">Arrival Time:</label>
            <select class="form-control" name="segments[{{$counter}}][end_time]">
                <option value="">Select</option>
                @foreach($time_range as $key => $val)
                <option value="{{$key}}" {{($key==$end_time)?'selected':''}} >{{$val}}</option>
                @endforeach
            </select>
            @include('snippets.errors_first', ['param' => "segments.$counter.end_time"])
        </div>
    </div>

    <div class="col-md-2 arrival_next">
        <div class="form-group{{ $errors->has('is_arrival_next_day') ? ' has-error' : '' }}">
            <label for="is_arrival_next_day" class="control-label">Is Arrival Next Day?:</label> <br />
            <input type="checkbox" name="segments[{{$counter}}][is_arrival_next_day]" value="1" <?php echo ($is_arrival_next_day == '1')?'checked':''; ?> > Yes
            @include('snippets.errors_first', ['param' => "segments.$counter.is_arrival_next_day"])
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group{{ $errors->has('stops') ? ' has-error' : '' }}">
            <label for="stops" class="control-label">No of Stop(s):</label>
            <select class="form-control" name="segments[{{$counter}}][stops]">
                <option value="">Select No of Stop(s)</option>
                @foreach($flight_stops_arr as $key => $val)
                <option value="{{$key}}" {{($key==$stops)?'selected':''}} >{{$val}}</option>
                @endforeach
            </select>
            @include('snippets.errors_first', ['param' => "segments.$counter.stops"])
        </div>
    </div>

    @if($counter==0)
    <div class="clearfix"></div>
    <div class="col-md-6 cabin_inner">
        <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
            <div class="row">
            <div class="col-md-12">
              <label for="end_time" class="control-label">Cabin Baggage:</label>
            </div>
                <div class="col-md-4">
                <label for="end_time" class="control-label">Adult:</label>
                    <select class="form-control" name="segments[{{$counter}}][cabin_adult]">
                        <option value="">Adult</option>
                        @for($i=1; $i <= 200; $i++)
                        <option value="{{$i}}" {{($i==$cabin_adult)?'selected':''}} >{{$i}}</option>
                        @endfor
                    </select>
                    @include('snippets.errors_first', ['param' => "segments.$counter.cabin_adult"])
                </div>
                <div class="col-md-4">
                <label for="end_time" class="control-label">Child:</label>
                    <select class="form-control" name="segments[{{$counter}}][cabin_child]">
                        <option value="">Child</option>
                        @for($i=1; $i <= 200; $i++)
                        <option value="{{$i}}" {{($i==$cabin_child)?'selected':''}} >{{$i}}</option>
                        @endfor
                    </select>
                    @include('snippets.errors_first', ['param' => "segments.$counter.cabin_child"])
                </div>
                <div class="col-md-4">
                <label for="end_time" class="control-label">Infant:</label>
                    <select class="form-control" name="segments[{{$counter}}][cabin_infant]">
                        <option value="">Infant</option>
                        @for($i=1; $i <= 200; $i++)
                        <option value="{{$i}}" {{($i==$cabin_infant)?'selected':''}} >{{$i}}</option>
                        @endfor
                    </select>
                    @include('snippets.errors_first', ['param' => "segments.$counter.cabin_infant"])
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 cabin_inner">
        <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
            <div class="row">
            <div class="col-md-12">
              <label for="end_time" class="control-label">Check-In Baggage:</label>
            </div>
                <div class="col-md-4">
                <label for="end_time" class="control-label">Adult:</label>
                    <select class="form-control" name="segments[{{$counter}}][checkin_adult]">
                        <option value="">Adult</option>
                        @for($i=1; $i <= 200; $i++)
                        <option value="{{$i}}" {{($i==$checkin_adult)?'selected':''}} >{{$i}}</option>
                        @endfor
                    </select>
                    @include('snippets.errors_first', ['param' => "segments.$counter.checkin_adult"])
                </div>
                <div class="col-md-4">
                <label for="end_time" class="control-label">Child:</label>
                    <select class="form-control" name="segments[{{$counter}}][checkin_child]">
                        <option value="">Child</option>
                        @for($i=1; $i <= 200; $i++)
                        <option value="{{$i}}" {{($i==$checkin_child)?'selected':''}} >{{$i}}</option>
                        @endfor
                    </select>
                    @include('snippets.errors_first', ['param' => "segments.$counter.checkin_child"])
                </div>
                <div class="col-md-4">
                <label for="end_time" class="control-label">Infant:</label>
                    <select class="form-control" name="segments[{{$counter}}][checkin_infant]">
                        <option value="">Infant</option>
                        @for($i=1; $i <= 200; $i++)
                        <option value="{{$i}}" {{($i==$checkin_infant)?'selected':''}} >{{$i}}</option>
                        @endfor
                    </select>
                    @include('snippets.errors_first', ['param' => "segments.$counter.checkin_infant"])
                </div>
            </div>
        </div>
    </div>
    @endif
</div>