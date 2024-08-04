@component('admin.layouts.main')
@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot

<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();
if(empty($back_url)) {
    $back_url = $routeName.'/cabs/cities';
}
$routeName = CustomHelper::getAdminRouteName();

$id = (isset($cityData->id))?$cityData->id:'';
$name = (isset($cityData->name))?$cityData->name:'';
$per_day_km = (isset($cityData->per_day_km))?$cityData->per_day_km:'';
$inclusions = (isset($cityData->inclusions))?$cityData->inclusions:'';
$exclusions = (isset($cityData->exclusions))?$cityData->exclusions:'';
$terms = (isset($cityData->terms))?$cityData->terms:'';
$status = (isset($cityData->status))?$cityData->status:1;
$sort_order = (isset($cityData->sort_order))?$cityData->sort_order:'';
$is_sharing = (isset($cityData->is_sharing))?$cityData->is_sharing:'';
$is_sightseeing = (isset($cityData->is_sightseeing))?$cityData->is_sightseeing:'';
$featured = (isset($cityData->featured))?$cityData->featured:'';
$is_airport = (isset($cityData->is_airport))?$cityData->is_airport:'';
$airport_entry_charge = (isset($cityData->airport_entry_charge))?$cityData->airport_entry_charge:'';
$airport_max_distance = (isset($cityData->airport_max_distance))?$cityData->airport_max_distance:'';
$terminals = (isset($cityData->terminals))?json_decode($cityData->terminals):[];
$is_railway = (isset($cityData->is_railway))?$cityData->is_railway:'';
$station_entry_charge = (isset($cityData->station_entry_charge))?$cityData->station_entry_charge:'';
$station_max_distance = (isset($cityData->station_max_distance))?$cityData->station_max_distance:'';
$stations = (isset($cityData->stations))?json_decode($cityData->stations):[];
$selected_cab = (isset($cityData->cab))?json_decode($cityData->cab):[];
?>

<?php if($id){ ?>
<div class="page_btns">
    <a class="active" href="{{route($routeName.'.cabs.cities_view', ['id'=>$id])}}" title="Edit Cab City"><i class="fas fa-view"></i>Cab City</a>
    <a href="{{route($routeName.'.cabs.cities_agent_price', ['id'=>$id])}}" title="Edit Agent Price"><i class="fas fa-edit"></i>Agent price</a>
</div>
<?php } ?>

<div class="centersec">
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if(request()->has('back_url')){ $back_url= request('back_url'); ?>
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                Back</a>
            <?php } ?>
        </div>
    </div>
    <div class="bgcolor cabCity_inner">
        @include('snippets.flash')

        <div class="ajax_msg"></div>
        <div class="bgcolor1">
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <div class="form-group  col-md-6{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label required">Name:</label>
                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"/>
                    @include('snippets.errors_first', ['param' => 'name'])
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('cab') ? ' has-error' : '' }}">
                        <label class="required">Cabs (Select Cab available from this city):</label><br/>
                        <select name="cab[]" class="form-control admin_input1 select2" multiple="multiple">
                            <?php
                            if(!empty($cabs)) {
                                foreach ($cabs as $cabData) {
                                    $selected = "";
                                    if(in_array($cabData->id,$selected_cab)) {
                                        $selected = "selected";
                                    }
                            ?>
                            <option value="{{$cabData->id}}" {{$selected}}>{{$cabData->name}}</option>
                            <?php } } ?>
                        </select>                  
                        @include('snippets.errors_first', ['param' => 'cab'])
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="form-group  col-md-3{{ $errors->has('per_day_km') ? ' has-error' : '' }}">
                <label for="per_day_km" class="control-label">Minimum KM Per Day:</label>
                <input type="number" name="per_day_km" id="per_day_km" class="form-control" value="{{ old('per_day_km',$per_day_km) }}"> 
                @include('snippets.errors_first', ['param' => 'per_day_km'])
            </div>

            <div class="form-group  col-md-3{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                <label for="sort_order" class="control-label">Sort Order:</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                @include('snippets.errors_first', ['param' => 'sort_order'])
            </div>

            <div class="form-group{{ $errors->has('is_sharing') ? ' has-error' : '' }} col-md-2" style="display: none;">
                <br />
                <label for="is_sharing" class="control-label ">Sharing:</label>
                <input type="checkbox" name="is_sharing" value="1" <?php echo ($is_sharing == '1')?'checked':''; ?> />
                @include('snippets.errors_first', ['param' => 'is_sharing'])
            </div>

            <div class="form-group{{ $errors->has('is_sightseeing') ? ' has-error' : '' }} col-md-2" style="display: none;">
                <br />
                <label for="is_sightseeing" class="control-label ">Sightseeing:</label>
                <input type="checkbox" name="is_sightseeing" value="1" <?php echo ($is_sightseeing == '1')?'checked':''; ?> />
                @include('snippets.errors_first', ['param' => 'is_sightseeing'])
            </div>

            <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-2">
                <br />
                <label for="featured" class="control-label ">Featured:</label>
                <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />
                @include('snippets.errors_first', ['param' => 'featured'])
            </div>
            <div class="clearfix"></div>

            <div class="form-group{{ $errors->has('is_airport') ? ' has-error' : '' }} col-md-12">
                <label for="is_airport" class="control-label ">Airport:</label>
                <input type="checkbox" name="is_airport" value="1" <?php echo ($is_airport == '1')?'checked':''; ?> />
                @include('snippets.errors_first', ['param' => 'is_airport'])
            </div>

            <div class="form-group col-md-12" id="airport_data" style="display: none;">
                <div class="row">
                    <div class="form-group{{ $errors->has('airport_entry_charge') ? ' has-error' : '' }} col-md-3">
                        <label for="airport_entry_charge" class="control-label ">Airport Entry Charge & Toll:</label>
                        <input type="number" name="airport_entry_charge" id="airport_entry_charge" class="form-control" value="{{ old('airport_entry_charge',$airport_entry_charge) }}"> 
                        @include('snippets.errors_first', ['param' => 'airport_entry_charge'])
                    </div>

                    <div class="form-group{{ $errors->has('airport_max_distance') ? ' has-error' : '' }} col-md-3">
                        <label for="airport_max_distance" class="control-label ">Maximum allowed distance (KM):</label>
                        <input type="number" name="airport_max_distance" id="airport_max_distance" class="form-control" value="{{ old('airport_max_distance',$airport_max_distance) }}"> 
                        @include('snippets.errors_first', ['param' => 'airport_max_distance'])
                    </div>

                    <div class="form-group col-md-3 terminals">
                        <label for="terminals" class="control-label ">Add Terminal:</label>
                        <div id="terminals_table">
                            <?php if(!empty($terminals)) { foreach($terminals as $terminal){ ?>
                            <div class="row listItem">
                                <div class="controls col-sm-10">
                                    <input type="text" name="terminals[]" class="form-control" value="{{$terminal}}">
                                </div>
                                <div class="controls col-sm-2">
                                    <button class="addMore" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    <button class="removeItem" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <?php } } else { ?>
                            <div class="row listItem">
                                <div class="controls col-sm-10">
                                    <input type="text" name="terminals[]" class="form-control" value="">
                                </div>
                                <div class="controls col-sm-2">
                                    <button class="addMore" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    <button class="removeItem" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group{{ $errors->has('is_railway') ? ' has-error' : '' }} col-md-12">
                <label for="is_railway" class="control-label ">Railway:</label>
                <input type="checkbox" name="is_railway" value="1" <?php echo ($is_railway == '1')?'checked':''; ?> />
                @include('snippets.errors_first', ['param' => 'is_railway'])
            </div>

            <div class="form-group col-md-12" id="railway_data" style="display: none;">
                <div class="row">
                    <div class="form-group{{ $errors->has('station_entry_charge') ? ' has-error' : '' }} col-md-3">
                        <label for="station_entry_charge" class="control-label ">Station Entry Charge:</label>
                        <input type="number" name="station_entry_charge" id="station_entry_charge" class="form-control" value="{{ old('station_entry_charge',$station_entry_charge) }}"> 
                        @include('snippets.errors_first', ['param' => 'station_entry_charge'])
                    </div>

                    <div class="form-group{{ $errors->has('station_max_distance') ? ' has-error' : '' }} col-md-3">
                        <label for="station_max_distance" class="control-label ">Maximum allowed distance (KM):</label>
                        <input type="number" name="station_max_distance" id="station_max_distance" class="form-control" value="{{ old('station_max_distance',$station_max_distance) }}"> 
                        @include('snippets.errors_first', ['param' => 'station_max_distance'])
                    </div>

                    <div class="form-group col-md-3 stations">
                        <label for="stations" class="control-label ">Add Station:</label>
                        <div id="terminals_table">
                        <?php if(!empty($stations)) { foreach($stations as $station){ ?>
                        <div class="row listItem">
                            <div class="controls col-sm-10">
                                <input type="text" name="stations[]" class="form-control" value="{{$station}}">
                            </div>
                            <div class="controls col-sm-2">
                                <button class="addMore" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                <button class="removeItem" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <?php } } else { ?>
                        <div class="row listItem">
                            <div class="controls col-sm-10">
                                <input type="text" name="stations[]" class="form-control" value="">
                            </div>
                            <div class="controls col-sm-2">
                                <button class="addMore" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                <button class="removeItem" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>




            <div class="form-group col-md-12{{ $errors->has('inclusions') ? ' has-error' : '' }}">
                <label for="inclusions" class="control-label">Inclusions:</label>
                <textarea name="inclusions" id="inclusions" class="form-control ckeditor" ><?php echo old('inclusions', $inclusions); ?></textarea>
                @include('snippets.errors_first', ['param' => 'inclusions'])
            </div>

            <div class="form-group col-md-12{{ $errors->has('exclusions') ? ' has-error' : '' }}">
                <label for="exclusions" class="control-label">Exclusions:</label>
                <textarea name="exclusions" id="exclusions" class="form-control ckeditor" ><?php echo old('exclusions', $exclusions); ?></textarea>
                @include('snippets.errors_first', ['param' => 'exclusions'])
            </div>

            <div class="form-group col-md-12{{ $errors->has('terms') ? ' has-error' : '' }}">
                <label for="terms" class="control-label">Terms:</label>
                <textarea name="terms" id="terms" class="form-control ckeditor" ><?php echo old('terms', $terms); ?></textarea>
                @include('snippets.errors_first', ['param' => 'terms'])
            </div>
            <div class="clearfix"></div>

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-6">
                <label for="status" class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'status'])
            </div>

            <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
            <div class="form-group col-md-12"><button type="submit" class="btn btn-success" title="Create this new Cab"><i class="fa fa-save"></i> Submit</button></div>

        </form>
    </div>
</div>
</div>
@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
$('.select2').select2({ placeholder: "Please Select", allowClear: true }); 
var editor = CKEDITOR.replace('inclusions',{
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
});
var editor = CKEDITOR.replace('exclusions',{
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
});
var editor = CKEDITOR.replace('terms',{
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
});

$(document).ready(function(){
    showHideAirportData('{{$is_airport}}');
    showHideRailwayData('{{$is_railway}}');
    $("input[name=is_airport]").on('change',function(){
        let is_airport = 0;
        if($(this).is(":checked")) {
            is_airport = 1;
        }
        showHideAirportData(is_airport);
    });
    $("input[name=is_railway]").on('change',function(){
        let is_railway = 0;
        if($(this).is(":checked")) {
            is_railway = 1;
        }
        showHideRailwayData(is_railway);
    });

    $(".terminals").on('click','.addMore',function(){
        addMoreTerminal();
    });

    $(".terminals").on('click','.removeItem',function(){
        $(this).parent().parent().remove();
    });

    $(".stations").on('click','.addMore',function(){
        addMoreStation();
    });

    $(".stations").on('click','.removeItem',function(){
        $(this).parent().parent().remove();
    });
});

function showHideAirportData(is_airport) {
    if(is_airport == 1) {
        $('#airport_data').show();
    } else {
        $('#airport_data').hide();
    }
}
function showHideRailwayData(is_railway) {
    if(is_railway == 1) {
        $('#railway_data').show();
    } else {
        $('#railway_data').hide();
    }
}

function addMoreTerminal() {
    let terminal = '\
    <div class="row listItem">\
    <div class="controls col-sm-10">\
    <input type="text" name="terminals[]" class="form-control" value="">\
    </div>\
    <div class="controls col-sm-2">\
    <button class="addMore" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>\
    <button class="removeItem" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>\
    </div>\
    </div>';
    $(terminal).insertAfter($('.terminals .row').last());
}

function addMoreStation() {
    let station = '\
    <div class="row listItem">\
    <div class="controls col-sm-10">\
    <input type="text" name="stations[]" class="form-control" value="">\
    </div>\
    <div class="controls col-sm-2">\
    <button class="addMore" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>\
    <button class="removeItem" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>\
    </div>\
    </div>';
    $(station).insertAfter($('.stations .row').last());
}
</script>
@endslot

@endcomponent