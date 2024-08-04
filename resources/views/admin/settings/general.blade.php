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
$storage = Storage::disk('public');
$path = 'settings/';
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
        $FRONTEND_LOGO_AlT_TEXT ='';
        $COMPANY_NAME ='';
        $SYSTEM_TITLE ='';
        $SALES_EMAIL ='';
        $SALE_PHONE ='';
        $GOOGLE_TRANSLATOR_MANAGEMENT ='';
        $FAVICON_LOGO ='';
        $FRONTEND_LOGO ='';
        $COMPANY_ADDRESS ='';
        $BOOKING_QUERIES_NO ='';
        $new_array =[];


        array_push($new_array,'FRONTEND_LOGO_AlT_TEXT','COMPANY_NAME','SYSTEM_TITLE','SALES_EMAIL','SALE_PHONE','GOOGLE_TRANSLATOR_MANAGEMENT','FROM_EMAIL','COMPANY_ADDRESS','BOOKING_QUERIES_NO','FRONTEND_LOGO','FAVICON_LOGO');

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
$GOOGLE_TRANSLATOR_MANAGEMENT;
?>

<div class="centersec">
    <div class="bgcolor">

      <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">Front End Logo :</label>
            <input type="file" id="title" class="form-control" name="FRONTEND_LOGO" value="{{$FRONTEND_LOGO}}" style="height:auto;" />
            @include('snippets.errors_first', ['param' => 'SYSTEM_TITLE'])

            <?php
            // $storage = Storage::disk('public');
            // $path = "settings/";
            $thumb_path = "settings/thumb/";
            $frontend_img =asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($FRONTEND_LOGO) && $storage->exists($path.$FRONTEND_LOGO)){
                $frontend_img = asset('/storage/'.$path.$FRONTEND_LOGO);
            } ?>

            <div class="col-md-2 image_box pt10">
                <img src="{{ $frontend_img }}" style="width: 100px;"><br>
                <!-- <a href="javascript:void(0)" data-id="{{ $FRONTEND_LOGO }}" data='image' class="del_image">Delete</a> -->
            </div>
        </div>
        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">Fav Icon :</label>
            <input type="file" id="title" class="form-control" name="FAVICON_LOGO" value="{{$FAVICON_LOGO}}" style="height:auto;" />
            @include('snippets.errors_first', ['param' => 'SYSTEM_TITLE'])

            <?php
            // $storage = Storage::disk('public');
            // $path = "settings/";
            $thumb_path = "settings/thumb/";
            $frontend_img =asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($FAVICON_LOGO) && $storage->exists($path.$FAVICON_LOGO)){
                $frontend_img = asset('/storage/'.$path.$FAVICON_LOGO);
            } ?>
            <div class="col-md-2 image_box pt10">
                <img src="{{ $frontend_img }}" style="width: 24px;"><br>
                <!-- <a href="javascript:void(0)" data-id="{{ $FRONTEND_LOGO }}" data='image' class="del_image">Delete</a> -->
            </div>
        </div>

        <div class="form-group col-md-12{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">Frontend Logo Alt Text :</label>
            <textarea type="text" id="title" class="form-control" name="FRONTEND_LOGO_AlT_TEXT" value="{{$FRONTEND_LOGO_AlT_TEXT}}" />{{$FRONTEND_LOGO_AlT_TEXT}}</textarea>
            @include('snippets.errors_first', ['param' => 'FRONTEND_LOGO_AlT_TEXT'])
        </div>

        <div class="clearfix"></div>
        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">COMPANY NAME :</label>
            <input type="text" id="title" class="form-control" name="COMPANY_NAME" value="{{$COMPANY_NAME}}" />
            @include('snippets.errors_first', ['param' => 'COMPANY_NAME'])
        </div>
        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">System Title :</label>
            <input type="text" id="title" class="form-control" name="SYSTEM_TITLE" value="{{$SYSTEM_TITLE}}" />
            @include('snippets.errors_first', ['param' => 'SYSTEM_TITLE'])
        </div>

        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">Sales Contact No <span style="color:#b55603;">(Comma Separated)</span> :</label>
            <input type="text" id="title" class="form-control" name="SALE_PHONE" value="{{$SALE_PHONE}}" />
            @include('snippets.errors_first', ['param' => 'SALE_PHONE'])
        </div>

        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">Sales Mobile No <span style="color:#b55603;">(Comma Separated)</span> :</label>
            <input type="text" id="title" class="form-control" name="BOOKING_QUERIES_NO" value="{{$BOOKING_QUERIES_NO}}" />
            @include('snippets.errors_first', ['param' => 'BOOKING_QUERIES_NO'])
        </div>

        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">Sales Email :</label>
            <input type="text" id="title" class="form-control" name="SALES_EMAIL" value="{{$SALES_EMAIL}}" />
            @include('snippets.errors_first', ['param' => 'SALES_EMAIL'])
        </div>
        <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">From Email <span style="color:#b55603;">(Email Settings Override this field)</span> :</label>
            <input type="text" id="title" class="form-control" name="FROM_EMAIL" value="{{$FROM_EMAIL}}" />
            @include('snippets.errors_first', ['param' => 'FROM_EMAIL'])
        </div>

       

        <div class="form-group col-md-12{{$errors->has('title')?' has-error':''}}">
            <label for="title" class="control-label">ADDRESS :</label>
            <textarea type="text" id="title" class="form-control" name="COMPANY_ADDRESS" value="{{$COMPANY_ADDRESS}}" />{{$COMPANY_ADDRESS}}</textarea>
            @include('snippets.errors_first', ['param' => 'COMPANY_ADDRESS'])
        </div>

         <div class="form-group col-md-6{{$errors->has('price_category')?' has-error':''}}">
            <label for="price_category" class="control-label">Google Translate :</label>
            <select class="form-control select2" name="GOOGLE_TRANSLATOR_MANAGEMENT" id="price_category">

                <option value="" >-Select-</option>
                <option value="1" {{($GOOGLE_TRANSLATOR_MANAGEMENT==1)?'selected':''}} >Yes</option>
                <option value="0" {{($GOOGLE_TRANSLATOR_MANAGEMENT==0)?'selected':''}}>No</option>

            </select>
            @include('snippets.errors_first', ['param' => 'price_category'])
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