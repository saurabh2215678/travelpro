@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.bordered {
    padding: 10px;
    color: #0c5460;
    background-color: #d1ecf166;
    border-color: #bee5eb;
    padding-bottom: 0;
    margin-bottom: 15px;
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

$segments = (isset($record->airlineRouteSegments))?$record->airlineRouteSegments:[];
$old_segments = old('segments');  
if($old_segments) {
    $segments = $old_segments;
}
$counter = 0;
?>
<div class="enq-view fancybox-content">
    <div class="top_title_admin2">
        <div class="title">
            <h2 style="padding: 5px 15px;"><strong>{{ $page_heading }}</strong></h2>
        </div>
    </div>

<div class="top_title_admin2 enquiries-top">
    <div class="col-md-12">
        <div class="bgcolor">
            <?php /*@include('snippets.errors')*/ ?>
            @include('snippets.flash')
            <div class="ajax_msg"></div>

            <form method="POST" class="view-form" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Route Name:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group{{ $errors->has('disable_before_departure_hour') ? ' has-error' : '' }}">
                            <label for="disable_before_departure_hour" class="control-label required">Disable Before Departure (hr):</label>
                            <input type="text" id="disable_before_departure_hour" class="form-control" name="disable_before_departure_hour" value="{{ old('disable_before_departure_hour', $disable_before_departure_hour) }}" />
                            @include('snippets.errors_first', ['param' => 'disable_before_departure_hour'])
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group{{ $errors->has('trip_type') ? ' has-error' : '' }}">
                            <label for="trip_type" class="control-label required">Type:</label>
                            <select class="form-control" name="trip_type">
                                <option value="Domestic" {{($trip_type=='Domestic')?'selected':''}}  >Domestic</option>
                                <option value="International" {{($trip_type=='International')?'selected':''}} >International</option>
                            </select>
                            @include('snippets.errors_first', ['param' => 'trip_type'])
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-12" id="route_segments">
                        @if($segments && !empty($segments) && count($segments) > 0)
                            @foreach($segments as $counter => $segment)
                                @include('admin.airline_route._segment')                        
                            @endforeach
                        @else
                            @include('admin.airline_route._segment')                        
                        @endif
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Sort Order:</label>
                            <input type="text" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}" />

                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>                    

                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="control-label">Status:</label>
                            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
                            <label for="featured" class="control-label">Featured:</label>
                            <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> >
                            @include('snippets.errors_first', ['param' => 'featured'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div> 
</div>
<div class="clearfix"></div>
</div>
</div>