@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>
    .heightform{display:none; }
    .heightform form{border: 1px solid #ddd;width: 100%;padding: 10px;border-radius: 3px;/* margin-top:10px; */display:inline-block;}
    .heightform.show{display:inline-block !important; }
    textarea{width:100%;padding: 10px;}
    .ihr{display:flex;align-items:center;justify-content: flex-end;}
    .ihr a{margin-left:5px;}
    .fix-width table tbody tr th {/* width: 22%; */ word-break: break-all;}
    .fix-width table tbody tr td {word-break: break-all;}
    .some-list{
        color:#ececec
    }
    .some-list .some-item{
        background:#424242;
        padding:20px 30px 10px;
        position:relative;
        margin-bottom:20px
    }
    .some-list .some-item:last-of-type{
        margin-bottom:0
    }
    .some-list .some-item .item-header{
        display:-ms-flexbox;
        display:flex;
        -ms-flex-align:center;
        align-items:center;
        line-height:24px
    }
    .some-list .some-item .item-header i{
        font-size:14px;
        margin-right:10px;
        -ms-flex-item-align:start;
        align-self:flex-start
    }
    .some-list .some-item .item-header i:after{
        content:"♦"
    }
    .some-list .some-item .item-header .header-title{
        font-weight:700
    }
    .some-list .some-item .item-message{
        margin:12px 0 20px;
        padding:20px 0;
        border-top:1px solid #828282;
        border-bottom:1px solid #828282
    }
    .some-list .some-item .item-message .my-message{
        font-size:14px;
        line-height:22px
    }
    .morecontent span{
        display:none
    }
    .morelink{
        display:-ms-flexbox;
        display:flex;
        -ms-flex-align:center;
        align-items:center;
        position:relative;
        margin-top:5px;
        font-size:13px;
        color:#00b2a7;
    }
    .morelink:hover, .morelink:focus{
        color:#ef8d25;
    }
    .morelink:after{
        content:"";
        -ms-flex-item-align:start;
        align-self:flex-start;
        display:inline-block;
        width:5px;
        height:5px;
        margin-top:6px;
        margin-left:10px;
        border:1px solid #ececec;
        border-top:none;
        border-right:none;
        background:transparent;
        transform:rotate(-45deg);
        transform-origin:bottom
    }
    .morelink.less:after{
        margin-left:7px;
        transform:rotate(135deg)
    }


</style>
@endslot
<?php
//pr($settingRow->toArray());
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$setting_id = 0;
$setting_id = (isset($settingRow->id))?$settingRow->id:0;
$type = (isset($settingRow->type))?$settingRow->type:$type;
$name = (isset($settingRow->name))?$settingRow->name:'';
$label = (isset($settingRow->label))?$settingRow->label:'';
$description = (isset($settingRow->description))?$settingRow->description:'';
$field_type = (isset($settingRow->field_type))?$settingRow->field_type:'text';
$css_class = (isset($settingRow->css_class))?$settingRow->css_class:'';
$value = (isset($settingRow->value))?$settingRow->value:'';
$old_value = (isset($settingRow->old_value))?$settingRow->old_value:'';
$default_value = (isset($settingRow->default_value))?$settingRow->default_value:'';

$action_url = url($ADMIN_ROUTE_NAME.'/settings');
$name_readonly = '';
$form_heading = 'Add Setting';
if(is_numeric($setting_id) && $setting_id > 0){
    $action_url = url($ADMIN_ROUTE_NAME.'/settings', $setting_id);
    $name_readonly = 'readonly';

    $form_heading = 'Update Setting';
}

/*$restore = (request()->has('restore'))?request()->restore:'';

if($restore == 'previous'){
$value =$old_value;

}elseif($restore == 'default'){
$value =$default_value;

}*/

$inputTypesArr = config('custom.input_types_arr');
$settingTypesArr = config('custom.setting_types_arr');
$path = 'settings/';
$storage = Storage::disk('public');
?>
<div class="top_title_admin enquiries-top" style="margin-bottom: 15px;padding: 0;">
    <div class="col-sm-6">
        <h1>Manage Settings ({{$settingTypesArr[$type]}})</h1>
    </div>
    <div class="col-sm-4">
        <div class="ihr">
            @if(CustomHelper::checkPermission('settings','add'))
            <a href="javascript:void(0);" class="btn btn-primary btn_admin2" id="add">Add Settings</a>
            @endif
        </div>
    </div>
    <div class="col-sm-3">
        <div class="pull-right setting-box {{ $errors->has('type') ? ' has-error' : '' }}" style="font-size:14px;">
            <strong class="pull-left">Select Type:&nbsp;</strong>
            <select name="select_type">
                <option value="">--select--</option>

                <?php
                if(!empty($settingTypesArr) && count($settingTypesArr) > 0){
                    foreach($settingTypesArr as $saKey=>$saVal){
                        $selected = '';
                        if($saKey == $type){
                            $selected = 'selected';
                        }
                        ?>
                        <option value="{{$saKey}}" {{$selected}}>{{$saVal}}</option>
                        <?php
                    }
                }
                ?>
            </select>
            @include('snippets.errors_first', ['param' => 'type'])

        </div>
    </div>
</div>
<div class="row p20">
 <div class="col-md-12">
    @include('snippets.errors')
    @include('snippets.flash')
</div>
<?php
    //$setting_id = app('request')->input('setting_id');
$show = '';
if(!empty($setting_id))
{
  $show = 'show';
}
?>
<div class="col-md-12">
    <div class="topsearch heightform {{$show}}" style="padding:0;">
        <form name="settingForm" method="POST" action="{{ $action_url }}" accept-charset="UTF-8" role="form" class="form-inline" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="top_title_admin" style="padding: 0;margin: 0px 15px 7px;">
                <div class="title">
                    <h2>{{ $form_heading }}</h2>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="form_group{{ $errors->has('type') ? ' has-error' : '' }} ">
                    <label for="type" class="control-label"><strong>Type:</strong></label>

                    <select name="type" class="form-control">
                        <option value="">--select--</option>

                        <?php
                        if(!empty($settingTypesArr) && count($settingTypesArr) > 0){
                            foreach($settingTypesArr as $saKey=>$saVal){
                                $selected = '';
                                if($saKey == $type){
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="{{$saKey}}" {{$selected}}>{{$saVal}}</option>
                                <?php
                            }
                        }
                        ?>

                    </select>

                    @include('snippets.errors_first', ['param' => 'type'])

                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('field_type') ? ' has-error' : '' }} ">
                    <label for="field_type" class="control-label"><strong>Field Type:</strong></label>

                    <select name="field_type" class="form-control">
                        <option value="">--select--</option>

                        <?php
                        if(!empty($inputTypesArr) && count($inputTypesArr) > 0){
                            foreach($inputTypesArr as $iaKey=>$iaVal){
                                $selected = '';
                                if($iaKey == $field_type){
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="{{$iaKey}}" {{$selected}}>{{$iaVal}}</option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <?php
                    /*@include('snippets.errors_first', ['param' => 'field_type'])*/
                    ?>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('css_class') ? ' has-error' : '' }} ">
                    <label for="css_class" class="control-label"><strong>CSS Class:</strong></label>

                    <input type="text" name="css_class" class="form-control" value="{{ old('css_class', $css_class) }}" maxlength="255" />

                    <?php
                    /*@include('snippets.errors_first', ['param' => 'css_class'])*/
                    ?>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group {{ $errors->has('label') ? ' has-error' : '' }} ">
                    <label for="label" class="control-label required"><strong>Label:</strong></label>

                    <input type="text" name="label" class="form-control" value="{{ old('label', $label) }}" maxlength="255" />

                    <?php
                    /*@include('snippets.errors_first', ['param' => 'label'])*/
                    ?>
                </div>
            </div>
            
            <div class="col-sm-12">
                <div class="form-group  {{ $errors->has('description') ? ' has-error' : '' }} ">
                  <label for="description" class="control-description"><strong>Description:</strong></label>

                  <textarea type="text" name="description" class="form-control" rows="2">{{ old('description', $description) }}</textarea>
              </div>
          </div>

          <div class="col-sm-3">
              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} ">
                <label for="name" class="control-label required"><strong>Name:</strong></label>

                <input type="text" name="name" class="form-control" value="{{ old('name', $name) }}" maxlength="255" {{ $name_readonly }} />

                <?php
                /*@include('snippets.errors_first', ['param' => 'name'])*/
                ?>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group {{ $errors->has('value') ? ' has-error' : '' }} " style="margin-bottom: 0;">
                <label for="value" class="control-label"><strong>Value:</strong></label>
                <div class="fileBox">
                    <input type="file" name="value">
                    <?php
                    if($field_type == 'file'){

                        if(!empty($value) && $storage->exists($path.$value)){
                            ?>
                            <p style="font-size: 12px;">
                                <a href="{{url('/storage/'.$path.$value)}}" target="_blank">
                                   <strong>Uploaded File:</strong> {{$value}}
                               </a>
                           </p>
                           <?php
                       }
                   }
                   ?>
               </div>
              </div>
               <div class="valBox">
                <p style="font-size: 12px;">for multiple values separate with semicolon(;)</p>
                <textarea name="value" id="value" class="" cols="25" />{{ old('value', $value) }}</textarea>
            </div>
            @include('snippets.errors_first', ['param' => 'value'])
        </div>
         <?php if(!empty($setting_id)) { ?>
        <div class="col-sm-3">
            <div class="form-group {{ $errors->has('old_value') ? ' has-error' : '' }} ">
              <label for="value" class="control-label"><strong>Old Value:</strong></label>
             {{$old_value}}
            <?php /*<input type="text" name="old_value" class="form-control" value="{{ old('old_value', $old_value) }}"  /> */ ?>
              @include('snippets.errors_first', ['param' => 'old_value'])
          </div>
  </div>
  <?php } ?>
       <div class="col-sm-3">
        <div class="form-group {{ $errors->has('default_value') ? ' has-error' : '' }} ">
          <label for="value" class="control-label"><strong>Default Value:</strong></label>
          <?php if(empty($setting_id)) { ?>
              <input type="text" name="default_value" class="form-control" value="{{ old('default_value', $default_value) }}"  />
              <?php } else{ ?><br>
              {{$default_value}}
              <input type="hidden" name="default_value" class="form-control" value="{{ old('default_value', $default_value) }}"  />
              <?php
          } ?>

          @include('snippets.errors_first', ['param' => 'default_value'])
      </div>
  </div>
<div class="clearfix"></div>
<input type="hidden" name="type" value="{{ $type }}">
<input type="hidden" name="setting_id" value="{{ $setting_id }}">
<br>
<div class="form-group col-md-12">
    <button class="btn btn-success btn_admin"><i class="fa fa-save"></i>Save</button>

    <?php
            //if($setting_id > 0){
    ?>
    <a href="{{ url($ADMIN_ROUTE_NAME.'/settings') }}" class="btn resetbtn btn-primary btn_admin2" title="Cancel">Cancel</a>
    <?php
            //}
    ?>
</div>
</form>
</div>
</div>

<?php 
$old_name = (request()->has('name'))?request()->name:'';
?>

<div class="col-sm-12">
    <div class="topsearch">
        <div class="">
            <form class="" method="GET">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="setting_id" value="{{ $setting_id }}">
                <div class="row">
                    <div class="col-sm-3">
                        <label><strong>Search:</strong></label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>
                    <div class="col-sm-3">
                        <br>
                        <button type="submit" class="btn btn-success btn_admin">Search</button>
                        <a href="{{ url('admin/settings') }}" class="btn resetbtn btn-primary btn_admin2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-md-12">
    <?php
    if(!empty($settings) && count($settings) > 0){
        ?>
        <div class="table-responsive fix-width">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 18%;">Label</th>
                    <th style="width: 18%;">Name</th>
                    <th style="width: 18%;">Description</th>
                    <th style="width: 7%;white-space: nowrap;">Type</th>
                    <!-- <th>CSS Class</th> -->
                    <!--  <th>Field Type</th> -->
                    <th style="width: 40%;">Value</th>
                    @if(CustomHelper::checkPermission('settings','edit'))
                    <th style="width: 5%;">Action</th>
                    @endif
                </tr>

                <?php
                foreach ($settings as $setting){ ?>
                    <tr>
                        <td>{{$setting->label}}</td>
                        <td>{{$setting->name}}</td>
                        <td>{{$setting->description}}</td>
                        <td>
                            <?php
                            if(isset($settingTypesArr[$setting->type])){
                                echo $settingTypesArr[$setting->type];
                            }
                            ?>
                        </td>
                   <?php /* <td>{{$setting->css_class}}</td>
                   <td>{{$setting->field_type}}</td> */ ?>
                   <td>
                    <div class="content_value">


                        <?php
                        $value = $setting->value;
                        if($setting->field_type == 'file'){
                            if(!empty($value) && $storage->exists($path.$value)){
                                ?>
                                <a href="{{url('/storage/'.$path.$value)}}" target="_blank">{{$value}}</a>
                                <?php
                            }
                        }
                        else{
                            ?>
                            {{$value}}
                            <?php
                        }
                        ?>
                    </div>
                </td>
                @if(CustomHelper::checkPermission('settings','edit'))
                <td>
                    <a href="{{ url($ADMIN_ROUTE_NAME.'/settings/'.$setting->id. '?type='.$setting->type) }}" title="Edit"><i  class="fas fa-edit"></i></a>
                </td>
                @endif
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<?php
}
else{
    ?>
    <div class="alert alert-warning">There are no settings at the present.</div>
    <?php
}
?>
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
$('#add').click(function(){
   $('.heightform').toggle();
});



/*------------ Show / Hide Text ------------*/
function showHideText(sSelector, options) {
    // Def. options
    var defaults = {
        charQty     : 100,
        ellipseText : "...",
        moreText    : "Show more",
        lessText    : "Show less"
    };

    var settings = $.extend( {}, defaults, options );

    var s = this;

    s.container = $(sSelector);
    s.containerH = s.container.height();

    s.container.each(function() {
        var content = $(this).html();

        if(content.length > settings.charQty) {

            var visibleText = content.substr(0, settings.charQty);
            var hiddenText  = content.substr(settings.charQty, content.length - settings.charQty);

            var html = visibleText
            + '<span class="moreellipses">' +
            settings.ellipseText
            + '</span><span class="morecontent"><span>' +
            hiddenText
            + '</span><a href="" class="morelink">' +
            settings.moreText
            + '</a></span>';

            $(this).html(html);
        }

    });

    s.showHide = function(event) {
        event.preventDefault();
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(settings.moreText);

            $(this).prev().fadeToggle('fast', function() {
                $(this).parent().prev().fadeIn();
            });
        } else {
            $(this).addClass("less");
            $(this).html(settings.lessText);

            $(this).parent().prev().hide();
            $(this).prev().fadeToggle('fast');
        }
    }

    $(".morelink").bind('click', s.showHide);
}
/*------------------------------------------*/

</script>
<script>
    var th = new showHideText('.content_value', {
        charQty     : 350,
        ellipseText : "...",
    });
</script>

@endslot
@endcomponent 