@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>
.heightform .form-control {
    max-width: 100%;
    width: 100%
}
textarea {
    width: 100%
}
</style>
@endslot
<?php
// $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
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
    $SMS_USERNAME ='';
    $SMS_PASSWORD ='';
    $SMS_SENDERID ='';
    $new_array =[];

     array_push($new_array,'SMS_USERNAME','SMS_SENDERID','SMS_PASSWORD');

    foreach ($settings as $key => $setting) {
          foreach ($new_array as $name) {
             if($setting->name == $name){
                 $$name = $setting->value ?? '';
               }
           }
    }
?>
    <div class="centersec">
        <div class="bgcolor">
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group col-md-6{{$errors->has('SMS_USERNAME')?' has-error':''}}">
                    <label for="SMS_USERNAME" class="control-label">Sms username :</label>
                    <input type="text" id="SMS_USERNAME" class="form-control" name="SMS_USERNAME" value="{{$SMS_USERNAME}}" />
                    @include('snippets.errors_first', ['param' => 'SMS_USERNAME'])
                </div>
                <div class="form-group col-md-6{{$errors->has('SMS_PASSWORD')?' has-error':''}}">
                    <label for="SMS_PASSWORD" class="control-label">Sms Password :</label>
                    <input type="text" id="SMS_PASSWORD" class="form-control" name="SMS_PASSWORD"
                        value="{{$SMS_PASSWORD}}" />
                    @include('snippets.errors_first', ['param' => 'SMS_PASSWORD'])
                </div>
                <div class="form-group col-md-6{{$errors->has('SMS_SENDERID')?' has-error':''}}">
                    <label for="SMS_SENDERID" class="control-label">SMS sender id :</label>
                    <input type="text" id="SMS_SENDERID" class="form-control" name="SMS_SENDERID" value="{{$SMS_SENDERID}}" />
                    @include('snippets.errors_first', ['param' => 'SMS_SENDERID'])
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <p></p>
                        <button type="submit" class="btn btn-success" title="Save Sms setting"><i
                                class="fa fa-save"></i> Submit</button>
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