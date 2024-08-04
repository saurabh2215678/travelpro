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
$new_array =[];
     array_push($new_array,'CATEGORY_IMG_HEIGHT','CATEGORY_IMG_WIDTH','CATEGORY_THUMB_WIDTH','CATEGORY_THUMB_HEIGHT','BANNER_IMG_HEIGHT','BANNER_IMG_WIDTH','BANNER_THUMB_WIDTH','BANNER_THUMB_HEIGHT','BLOG_IMG_HEIGHT','BLOG_IMG_WIDTH','BLOG_THUMB_WIDTH','BLOG_THUMB_HEIGHT','COMMON_IMG_HEIGHT','COMMON_IMG_WIDTH','COMMON_THUMB_WIDTH','COMMON_THUMB_HEIGHT');
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
                <div class="bgcolor">
                      <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <?php   foreach ($new_array as $name) {
                            ?>
                        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">{{$name}} :</label>
                            <input type="text" id="title" class="form-control" name="{{$name}}" value="{{$$name ?? ''}}" />
                            @include('snippets.errors_first', ['param' => 'SYSTEM_TITLE'])
                        </div>
                    <?php  }  ?>
                        
                               
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