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
$HTML_HEAD_TOP ='';
$HTML_BODY_TOP ='';
$HTML_HEAD_BOTTOM ='';
$HTML_BODY_BOTTOM ='';
$new_array =[];
     array_push($new_array,'HTML_HEAD_TOP','HTML_BODY_TOP','HTML_HEAD_BOTTOM','HTML_BODY_BOTTOM');
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
$head = "<head>";
$body = "<Body>";
?>
            <div class="centersec">
                <div class="bgcolor">
                      <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                     
                        <?php /*<div class="form-group col-md-12{{$errors->has('title')?' has-error':''}}">
                            <label for="title" class="control-label">HTML_HEAD_TOP :</label>
                            <input type="text" id="title" class="form-control" name="HTML_HEAD_TOP" value="{{$HTML_HEAD_TOP}}" />
                            @include('snippets.errors_first', ['param' => 'HTML_HEAD_TOP'])
                        </div> */ ?>

                        <div class="form-group col-md-12{{$errors->has('HTML_HEAD_TOP')?' has-error':''}}">
                            <label for="title" class="control-label">Scripts in Head Top :</label><div class="alert alert-warning" role="alert" style="margin-top: 3px;padding: 4px;margin-bottom: 5px;"><b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> These scripts will be added in top of {{$head}} section.</div> 
                            <textarea type="text" id="title" class="form-control" name="HTML_HEAD_TOP" value="{{$HTML_HEAD_TOP}}" />{{$HTML_HEAD_TOP}}</textarea>
                            @include('snippets.errors_first', ['param' => 'HTML_HEAD_TOP'])
                        </div>

                        <div class="form-group col-md-12{{$errors->has('HTML_HEAD_BOTTOM')?' has-error':''}}">
                            <label for="title" class="control-label">Scripts in Head Bottom :</label><div class="alert alert-warning" role="alert" style="margin-top: 3px;padding: 4px;margin-bottom: 5px;"><b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> These scripts will be added in bottom of {{$head}} section.</div>
                            <textarea type="text" id="title" class="form-control" name="HTML_HEAD_BOTTOM" value="{{$HTML_HEAD_BOTTOM}}" />{{$HTML_HEAD_BOTTOM}}</textarea>
                            @include('snippets.errors_first', ['param' => 'HTML_HEAD_TOP'])
                        </div>  
                       
                        <div class="form-group col-md-12{{$errors->has('HTML_BODY_TOP')?' has-error':''}}">
                            <label for="title" class="control-label">Scripts in Body Top :</label><div class="alert alert-warning" role="alert" style="margin-top: 3px;padding: 4px;margin-bottom: 5px;"><b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> These scripts will be added in top of {{$body}} section.</div>
                            <textarea type="text" id="title" class="form-control" name="HTML_BODY_TOP" value="{{$HTML_BODY_TOP}}" />{{$HTML_BODY_TOP}}</textarea>
                            @include('snippets.errors_first', ['param' => 'HTML_HEAD_TOP'])
                        </div> 
                              
                        <div class="form-group col-md-12{{$errors->has('HTML_BODY_BOTTOM')?' has-error':''}}">
                            <label for="title" class="control-label">Scripts in Body Bottom :</label><div class="alert alert-warning" role="alert" style="margin-top: 3px;padding: 4px;margin-bottom: 5px;"><b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> These scripts will be added in bottom of {{$body}} section.</div>
                            <textarea type="text" id="title" class="form-control" name="HTML_BODY_BOTTOM" value="{{$HTML_BODY_BOTTOM}}" />{{$HTML_BODY_BOTTOM}}</textarea>
                            @include('snippets.errors_first', ['param' => 'HTML_HEAD_TOP'])
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