@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>.heightform .form-control {max-width: 100%;width:100%}textarea{width:100%}form.flex {display: flex;padding: 15px 10px;}
form.flex .form-group {margin-bottom: 0;}</style>
@endslot
<?php
// $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$inputTypesArr = config('custom.input_types_arr');
$settingTypesArr = config('custom.setting_types_arr');
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
        <?php
        $websiteSettingsArr = CustomHelper::websiteSettingsArray($settings_arr);
        $INTERCITY_TRIP = (isset($websiteSettingsArr['INTERCITY_TRIP']))?$websiteSettingsArr['INTERCITY_TRIP']->value:1;
        $OUTSTATION = (isset($websiteSettingsArr['OUTSTATION']))?$websiteSettingsArr['OUTSTATION']->value:1;
        $AIRPORT = (isset($websiteSettingsArr['AIRPORT']))?$websiteSettingsArr['AIRPORT']->value:1;
        $RAILWAYS = (isset($websiteSettingsArr['RAILWAYS']))?$websiteSettingsArr['RAILWAYS']->value:1;
        $SIGHTSEEING = (isset($websiteSettingsArr['SIGHTSEEING']))?$websiteSettingsArr['SIGHTSEEING']->value:1;
        if(old('_token')) {
            $INTERCITY_TRIP = old('INTERCITY_TRIP');
            $OUTSTATION = old('OUTSTATION');
            $AIRPORT = old('AIRPORT');
            $RAILWAYS = old('RAILWAYS');
            $SIGHTSEEING = old('SIGHTSEEING');
        }
     ?>
     <div class="centersec">
        <div class="bgcolor">
          <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data" class="row flex">
            {{ csrf_field() }}

            <div class="form-group col-md-12">
                <label for="INTERCITY_TRIP" class="control-label">INTERCITY_TRIP:</label>
                <input type="checkbox" id="INTERCITY_TRIP" class="form-control" name="INTERCITY_TRIP" value="1" 
                @if($INTERCITY_TRIP == '1') checked @endif />
                
            </div>
            <div class="clearfix"></div> 
            <div class="form-group col-md-12{{$errors->has('OUTSTATION')?' has-error':''}}">
                <label for="OUTSTATION" class="control-label">OUTSTATION:</label>
                <input type="checkbox" id="OUTSTATION" class="form-control" name="OUTSTATION" value="1" 
                @if(old('OUTSTATION',$OUTSTATION) == '1') checked @endif />
                @include('snippets.errors_first', ['param' => 'OUTSTATION'])
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-12{{$errors->has('AIRPORT')?' has-error':''}}">
                <label for="AIRPORT" class="control-label">AIRPORT:</label>
                <input type="checkbox" id="AIRPORT" class="form-control" name="AIRPORT" value="1" 
                @if(old('AIRPORT',$AIRPORT) == '1') checked @endif />
                @include('snippets.errors_first', ['param' => 'AIRPORT'])
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-12{{$errors->has('RAILWAYS')?' has-error':''}}">
            <label for="RAILWAYS" class="control-label">RAILWAYS:</label>
            <input type="checkbox" id="RAILWAYS" class="form-control" name="RAILWAYS" value="1" 
            @if(old('RAILWAYS',$RAILWAYS) == '1') checked @endif />
            @include('snippets.errors_first', ['param' => 'RAILWAYS'])
        </div>
        <div class="clearfix"></div>
         <div class="form-group col-md-12{{$errors->has('SIGHTSEEING')?' has-error':''}}">
            <label for="SIGHTSEEING" class="control-label">SIGHTSEEING:</label>
            <input type="checkbox" id="SIGHTSEEING" class="form-control" name="SIGHTSEEING" value="1" 
            @if(old('SIGHTSEEING',$SIGHTSEEING) == '1') checked @endif />
            @include('snippets.errors_first', ['param' => 'SIGHTSEEING'])
        </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-success" title=""><i class="fa fa-save"></i> Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent 