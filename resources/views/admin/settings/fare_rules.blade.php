@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>.heightform .form-control {max-width: 100%;width:100%}textarea{width:100%}</style>
@endslot
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$path = 'settings/';
$field_type = '';
$websiteSettingsArr = CustomHelper::websiteSettingsArray($settings_arr);
$INTERCITY_TRIP = (isset($websiteSettingsArr['INTERCITY_TRIP']))?$websiteSettingsArr['INTERCITY_TRIP']->value:1;
$OUTSTATION = (isset($websiteSettingsArr['OUTSTATION']))?$websiteSettingsArr['OUTSTATION']->value:1;
$AIRPORT = (isset($websiteSettingsArr['AIRPORT']))?$websiteSettingsArr['AIRPORT']->value:1;
$RAILWAYS = (isset($websiteSettingsArr['RAILWAYS']))?$websiteSettingsArr['RAILWAYS']->value:1;
$SIGHTSEEING = (isset($websiteSettingsArr['SIGHTSEEING']))?$websiteSettingsArr['SIGHTSEEING']->value:1;
$FARE_RULE_DOMESTIC_CANCELLATION_FEE = (isset($websiteSettingsArr['FARE_RULE_DOMESTIC_CANCELLATION_FEE']))?$websiteSettingsArr['FARE_RULE_DOMESTIC_CANCELLATION_FEE']->value:'';
$FARE_RULE_DOMESTIC_DATE_CHANGE_FEE = (isset($websiteSettingsArr['FARE_RULE_DOMESTIC_DATE_CHANGE_FEE']))?$websiteSettingsArr['FARE_RULE_DOMESTIC_DATE_CHANGE_FEE']->value:'';
$FARE_RULE_DOMESTIC_NO_SHOW = (isset($websiteSettingsArr['FARE_RULE_DOMESTIC_NO_SHOW']))?$websiteSettingsArr['FARE_RULE_DOMESTIC_NO_SHOW']->value:'';
$FARE_RULE_DOMESTIC_SEAT_CHARGEABLE = (isset($websiteSettingsArr['FARE_RULE_DOMESTIC_SEAT_CHARGEABLE']))?$websiteSettingsArr['FARE_RULE_DOMESTIC_SEAT_CHARGEABLE']->value:'';
$FARE_RULE_INTERNATIONAL_CANCELLATION_FEE = (isset($websiteSettingsArr['FARE_RULE_INTERNATIONAL_CANCELLATION_FEE']))?$websiteSettingsArr['FARE_RULE_INTERNATIONAL_CANCELLATION_FEE']->value:'';
$FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE = (isset($websiteSettingsArr['FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE']))?$websiteSettingsArr['FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE']->value:'';
$FARE_RULE_INTERNATIONAL_NO_SHOW = (isset($websiteSettingsArr['FARE_RULE_INTERNATIONAL_NO_SHOW']))?$websiteSettingsArr['FARE_RULE_INTERNATIONAL_NO_SHOW']->value:'';
$FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE = (isset($websiteSettingsArr['FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE']))?$websiteSettingsArr['FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE']->value:'';

if(old('_token')) {
    $FARE_RULE_DOMESTIC_CANCELLATION_FEE = old('FARE_RULE_DOMESTIC_CANCELLATION_FEE');
    $FARE_RULE_DOMESTIC_DATE_CHANGE_FEE = old('FARE_RULE_DOMESTIC_DATE_CHANGE_FEE');
    $FARE_RULE_DOMESTIC_NO_SHOW = old('FARE_RULE_DOMESTIC_NO_SHOW');
    $FARE_RULE_DOMESTIC_SEAT_CHARGEABLE = old('FARE_RULE_DOMESTIC_SEAT_CHARGEABLE');
    $FARE_RULE_INTERNATIONAL_CANCELLATION_FEE = old('FARE_RULE_INTERNATIONAL_CANCELLATION_FEE');
    $FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE = old('FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE');
    $FARE_RULE_INTERNATIONAL_NO_SHOW = old('FARE_RULE_INTERNATIONAL_NO_SHOW');
    $FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE = old('FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE');
}
?>
<div class="row p20">
    <div class="top_title_admin">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                Back</a><?php } ?>
            </div>
        </div>
        @include('snippets.errors')
        @include('snippets.flash')
        <div class="centersec robot_form">
            <div class="bgcolor flight_tabs">
                <form method="POST" action="" accept-charset="UTF-8" role="form" name="robot_form" id="robot_form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">

                    <div class="col-md-12">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab1">Domestic</a></li>
                            <li><a data-toggle="tab" href="#tab2">International</a></li>
                        </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active">
                        <div class="col-sm-12">
                            @foreach($settings_arr as $key => $name)
                            <div class="">
                                <div class="form-group{{$errors->has('title')?' has-error':''}}">
                                    <label for="title" class="control-label">{{ucwords(strtolower(str_replace('_', ' ', $name)))}}:</label>
                                    <textarea rows="3" type="text" id="title" class="form-control" name="{{$name}}" >{{$$name??''}}</textarea>
                                    @include('snippets.errors_first', ['param' => $name])
                                </div>
                            </div>
                            @if($name=='FARE_RULE_DOMESTIC_SEAT_CHARGEABLE')
                            </div>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                        <div class="col-sm-12">
                            @endif
                            @endforeach
                        </div>
                        </div>
                        
                     </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <p></p>
                            <button type="submit" class="btn btn-success" title="Create this form" id="btn_save"><i class="fa fa-save"  ></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

@slot('bottomBlock')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
@endslot
@endcomponent 