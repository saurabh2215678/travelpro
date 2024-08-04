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
$COMPANY_NAME ='';
$COMPANY_ADDRESS ='';
$SALES_EMAIL ='';
$SALE_PHONE ='';
$GOOGLE_TRANSLATOR_MANAGEMENT ='';
$FAVICON_LOGO ='';
$FRONTEND_LOGO ='';
$BOOKING_QUERIES_NO ='';
$new_array =[];

     array_push($new_array,'COMPANY_NAME','SALES_EMAIL','FROM_EMAIL','SALE_PHONE','COMPANY_ADDRESS','BOOKING_QUERIES_NO');

    foreach ($settings as $key => $setting) {

        // echo $setting->name;
        // echo '<br>';

          foreach ($new_array as $name) {
             if($setting->name == $name){
                // echo $name ;
                // echo "<br>";
                 $$name = $setting->value ?? '';
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

 $GOOGLE_TRANSLATOR_MANAGEMENT;
?>
    <div class="centersec">
            <div class="bgcolor">
                  <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
                        <label for="title" class="control-label">COMPANY NAME :</label>
                        <input type="text" id="title" class="form-control" name="COMPANY_NAME" value="{{$COMPANY_NAME}}" />
                        @include('snippets.errors_first', ['param' => 'SYSTEM_TITLE'])
                    </div>
                    <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
                        <label for="title" class="control-label">Sales Email :</label>
                        <input type="text" id="title" class="form-control" name="SALES_EMAIL" value="{{$SALES_EMAIL}}" />
                        @include('snippets.errors_first', ['param' => 'SALES_EMAIL'])
                    </div>
                   <?php /* <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
                        <label for="title" class="control-label">From Email :</label>
                        <input type="text" id="title" class="form-control" name="FROM_EMAIL" value="{{$FROM_EMAIL}}" />
                        @include('snippets.errors_first', ['param' => 'FROM_EMAIL'])
                    </div> */ ?>

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
                        <label for="title" class="control-label">ADDRESS :</label>
                        <input type="text" id="title" class="form-control" name="COMPANY_ADDRESS" value="{{$COMPANY_ADDRESS}}" />
                        @include('snippets.errors_first', ['param' => 'SALES_EMAIL'])
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