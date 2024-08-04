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
$COOKIES_MESSAGE ='';
$COOKIES_BUTTON_TEXT ='';
$COOKIES_TEXT_COLOR ='';
$COOKIES_BACKGROUND_COLOR ='';
$COOKIES_CONSENT_STATUS ='';
$new_array =[];
   
    array_push($new_array,'COOKIES_MESSAGE','COOKIES_BUTTON_TEXT','COOKIES_TEXT_COLOR','COOKIES_BACKGROUND_COLOR','COOKIES_BUTTON_COLOR','COOKIES_BACKGROUND_COLOR','COOKIES_CONSENT_TEXT','COOKIES_CONSENT_STATUS');

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
                      <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                      <?php   foreach ($new_array as $name) {

                        if($name != 'COOKIES_CONSENT_STATUS'){                            ?>
                        
                        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">{{$name}} :</label>
                            <input type="text" id="title" class="form-control" name="{{$name}}" value="{{$$name ?? ''}}" />
                            @include('snippets.errors_first', ['param' => 'SYSTEM_TITLE'])
                        </div>

                    <?php }else{ ?>

                         <div class="form-group{{ $errors->has('COOKIES_CONSENT_STATUS') ? ' has-error' : '' }} col-md-12">
                        <label class="control-label">Cookie Consent Status:</label>
                        &nbsp;&nbsp;
                        Active: <input type="radio" name="COOKIES_CONSENT_STATUS" value="1" <?php echo ($$name == 1)?'checked':''; ?> >
                        &nbsp;
                        Inactive: <input type="radio" name="COOKIES_CONSENT_STATUS" value="0" <?php echo ( strlen($$name) > 0 && $name == '0')?'checked':''; ?> >
                        @include('snippets.errors_first', ['param' => 'status'])
                    </div>
                    <?php }  }  ?>
                      

                      

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