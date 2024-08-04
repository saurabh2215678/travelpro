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
$FACEBOOK_SHARE = '';
$INSTAGRAM_SHARE = '';
$TWITTER_SHARE = '';
$WHATSAPP_SHARE = '';
$new_array =[];
     array_push($new_array,'FACEBOOK_SHARE','INSTAGRAM_SHARE','WHATSAPP_SHARE','TWITTER_SHARE');
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
          
    }
?>
            
            <div class="centersec">
                <div class="bgcolor p10">
                      <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group col-md-4{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">FACEBOOK_SHARE :</label>
                            <input type="text" id="title" class="form-control" name="FACEBOOK_SHARE" value="{{$FACEBOOK_SHARE}}" />
                            @include('snippets.errors_first', ['param' => 'FACEBOOK_SHARE'])
                        </div>
                        <div class="form-group col-md-4{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">TWITTER_SHARE :</label>
                            <input type="text" id="title" class="form-control" name="TWITTER_SHARE" value="{{$TWITTER_SHARE}}"/>
                            @include('snippets.errors_first', ['param' => 'TWITTER_SHARE'])
                        </div>
                        <div class="form-group col-md-4{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">WHATSAPP_SHARE :</label>
                            <input type="text" id="title" class="form-control" name="WHATSAPP_SHARE" value="{{$WHATSAPP_SHARE}}" 
                             />
                            @include('snippets.errors_first', ['param' => 'WHATSAPP_SHARE'])
                        </div>         
                       <div class="form-group col-md-4{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">INSTAGRAM_SHARE :</label>
                            <input type="text" id="title" class="form-control" name="INSTAGRAM_SHARE" value="{{$INSTAGRAM_SHARE}}" 
                             />
                            @include('snippets.errors_first', ['param' => 'INSTAGRAM_SHARE'])
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