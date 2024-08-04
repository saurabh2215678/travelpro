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
$CURRENCY_NAME ='';
$CURRENCY_SIGN_PREVIEW ='';
$CURRENCY_SIGN_FONTAWESOME ='';
$CURRENCY_VALUE ='';
$new_array =[];
     array_push($new_array,'CURRENCY_NAME','CURRENCY_SIGN_PREVIEW','CURRENCY_SIGN_FONTAWESOME','CURRENCY_VALUE');
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
                        <div class="form-group col-md-12{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">CURRENCY NAME :</label>
                            <input type="text" id="title" class="form-control" name="CURRENCY_NAME" value="{{$CURRENCY_NAME}}" />
                            @include('snippets.errors_first', ['param' => 'CURRENCY_NAME'])
                        </div>
                        <div class="form-group col-md-12{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">CURRENCY SIGN PREVIEW :</label>
                            <input type="text" id="title" class="form-control" name="CURRENCY_SIGN_PREVIEW" value="{{$CURRENCY_SIGN_PREVIEW}}"/>
                            @include('snippets.errors_first', ['param' => 'CURRENCY_SIGN_PREVIEW'])
                        </div>  
                         
                       <div class="form-group col-md-12{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">CURRENCY SIGN (fontawesome 5 free icon) :</label>
                            <input type="text" id="title" class="form-control" name="CURRENCY_SIGN_FONTAWESOME" value="{{$CURRENCY_SIGN_FONTAWESOME}}" 
                             />
                            @include('snippets.errors_first', ['param' => 'CURRENCY_SIGN_FONTAWESOME'])
                        </div>         
                       <div class="form-group col-md-12{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">CURRENCY VALUE (per USD):</label>
                            <input type="text" id="title" class="form-control" name="CURRENCY_VALUE" value="{{$CURRENCY_VALUE}}" 
                             />
                            @include('snippets.errors_first', ['param' => 'CURRENCY_VALUE'])
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