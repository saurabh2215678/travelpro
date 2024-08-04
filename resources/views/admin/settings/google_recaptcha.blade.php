@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>.heightform .form-control {max-width: 100%;width:100%}textarea{width:100%}</style>
@endslot
<?php
//pr($settingRow->toArray());
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$inputTypesArr = config('custom.input_types_arr');
$settingTypesArr = config('custom.setting_types_arr');
$path = 'settings/';
// $storage = Storage::disk('public');
$field_type = '';
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
$RECAPTCHA_SITE_KEY ='';
$RECAPTCHA_SECRET_KEY ='';
$RECAPTCHA_FRONT_SCORE ='';
$RECAPTCHA_FRONT_DISABLED ='';
$RECAPTCHA_ADMIN_SCORE ='';
$RECAPTCHA_ADMIN_DISABLED ='';
$new_array =[];
     array_push($new_array,'RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY','RECAPTCHA_FRONT_SCORE','RECAPTCHA_FRONT_DISABLED','RECAPTCHA_ADMIN_SCORE','RECAPTCHA_ADMIN_DISABLED');
    foreach ($settings as $key => $setting) {
        // echo $setting->name;
        // echo '<br>';
          foreach ($new_array as $name) {
             if($setting->name == $name){
                // echo $name ;
                // echo "<br>";
                 $$name = $setting->value;
                // echo $$name ;
            }
           }
            // if($setting->name == 'SYSTEM_TITLE'){
            //      $SYSTEM_TITLE = $setting->value;
            // }
            // if($setting->name == 'SALES_EMAIL'){
            //     $SALES_EMAIL = $setting->value;
            // }
            // if($setting->name == 'SALE_PHONE'){
            //     $SALES_EMAIL = $setting->value;
            // }
    }
?>
            <div class="centersec">
                <div class="bgcolor">
                      <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data" class="row">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">RECAPTCHA_SITE_KEY :</label>
                            <input type="text" id="title" class="form-control" name="RECAPTCHA_SITE_KEY" value="{{$RECAPTCHA_SITE_KEY}}" />
                            @include('snippets.errors_first', ['param' => 'SYSTEM_TITLE'])
                        </div>
                        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">RECAPTCHA_SECRET_KEY :</label>
                            <input type="text" id="title" class="form-control" name="RECAPTCHA_SECRET_KEY" value="{{$RECAPTCHA_SECRET_KEY}}" placeholder="COUNTRY CODE" />
                            @include('snippets.errors_first', ['param' => 'SALES_EMAIL'])
                        </div> 

                       <div class="col-md-6">
                        <h4>Front</h4>
                        </div>
                        <div class="col-md-6">
                        <h4>Admin</h4>
                        </div>
                        
                        <div class="form-group col-md-3{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">RECAPTCHA_FRONT_SCORE :</label>
                            <input type="text" id="title" class="form-control" name="RECAPTCHA_FRONT_SCORE" value="{{$RECAPTCHA_FRONT_SCORE}}" placeholder="" />
                            @include('snippets.errors_first', ['param' => 'SALES_EMAIL'])
                        </div> 
                         <div class="form-group col-md-3{{$errors->has('RECAPTCHA_FRONT_DISABLED')?' has-error':''}}">
                            <label for="RECAPTCHA_FRONT_DISABLED" class="control-label">RECAPTCHA_FRONT_DISABLED:</label>
                            <input type="checkbox" id="RECAPTCHA_FRONT_DISABLED" class="form-control" name="RECAPTCHA_FRONT_DISABLED" value="1" 
                            @if(old('RECAPTCHA_FRONT_DISABLED',$RECAPTCHA_FRONT_DISABLED) == '1') checked @endif />
                            @include('snippets.errors_first', ['param' => 'RECAPTCHA_FRONT_DISABLED'])
                        </div>
                        
                        <div class="form-group col-md-3{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">RECAPTCHA_ADMIN_SCORE :</label>
                            <input type="text" id="title" class="form-control" name="RECAPTCHA_ADMIN_SCORE" value="{{$RECAPTCHA_ADMIN_SCORE}}" placeholder="" />
                            @include('snippets.errors_first', ['param' => 'SALES_EMAIL'])
                        </div> 
                        <div class="form-group col-md-3{{$errors->has('RECAPTCHA_ADMIN_DISABLED')?' has-error':''}}">
                            <label for="RECAPTCHA_ADMIN_DISABLED" class="control-label">RECAPTCHA_ADMIN_DISABLED:</label>
                            <input type="checkbox" id="RECAPTCHA_ADMIN_DISABLED" class="form-control" name="RECAPTCHA_ADMIN_DISABLED" value="1" 
                            @if(old('RECAPTCHA_ADMIN_DISABLED',$RECAPTCHA_ADMIN_DISABLED) == '1') checked @endif />
                            @include('snippets.errors_first', ['param' => 'RECAPTCHA_ADMIN_DISABLED'])
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <p></p>
                                 <button type="submit" class="btn btn-success" title="Create this new package"><i class="fa fa-save"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@slot('bottomBlock')
<script type="text/javascript">
  $(document).on("change", "[name=field_type]", function(){
    var fieldType = $(this).val();
        //alert(fieldType);
        toggleValBox(fieldType);        
    });
  var fieldType = "{{$field_type}}";
  function toggleValBox(fieldType){
    var settingForm = $("form[name=settingForm]");
    fieldType = fieldType.trim();
    if(fieldType == 'file'){
        settingForm.find(".valBox").hide();
        settingForm.find(".fileBox").show();
    }
    else{
        settingForm.find(".valBox").show();
        settingForm.find(".fileBox").hide();
    }
}
toggleValBox(fieldType);
$("select[name=select_type]").change(function(){
    var type = $(this).val();
    window.location = "{{url($ADMIN_ROUTE_NAME.'/settings?type=')}}"+type;
});
</script>

@endslot
@endcomponent 