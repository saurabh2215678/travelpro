<style>
    .top_title_admin.fancybox-content {
    width: 800px;
    height: 500px;
}
</style>
<?php
$id = (isset($widget_query->id))?$widget_query->id:'';
$widget_name = (isset($widget_query->widget_name))?$widget_query->widget_name:'';
$widget_identifier = (isset($widget_query->widget_identifier))?$widget_query->widget_identifier:'';
$section_heading = (isset($widget_query->section_heading))?$widget_query->section_heading:'';
$section_sub_heading = (isset($widget_query->section_sub_heading))?$widget_query->section_sub_heading:'';
$about_widget_desc = (isset($widget_query->about_widget_desc))?$widget_query->about_widget_desc:'';
$description = (isset($widget_query->description))?$widget_query->description:'';
$status = (isset($widget_query->status))?$widget_query->status:'';
$image1 = (isset($widget_query->image1))?$widget_query->image1:'';
$image2 = (isset($widget_query->image2))?$widget_query->image2:'';

$storage = Storage::disk('public');
$path = 'widgets/';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/widget';
}

?>

<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>

<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="ajax_msg"></div>
<table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">

              <tr>
                <td>
                    <div><b>Widget Name: </b></div>
                    <div>{{$widget_query->widget_name}}</div>
                </td>
                <td>
                    <div><b>Widget Identifier: </b></div>
                    <div>{{$widget_query->slug}}</div>
                </td>
                <td>
                    <div><b>Section Heading: </b></div>
                    <div>{{$widget_query->section_heading}}</div>
                </td>
            </tr>

            <tr>
                 <td colspan="3">
                        <div><b>Description: </b></div>
                        <div>{!! $widget_query->description !!}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Section Sub Heading: </b></div>
                    <div>{{$widget_query->section_sub_heading}}</div>
                </td>
                <td>
                    <div><b>About Widget Description: </b></div>
                    <div>{!!$widget_query->about_widget_desc!!}</div>
                </td>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($widget_query->status) }}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Image1: </b></div>
                    <div><?php
                    if(!empty($image1)){
                        if($storage->exists($path.$image1)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image1) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                </td>
                <td>
                    <div><b>Image2: </b></div>
                    <div> <?php
                    if(!empty($image2)){
                        if($storage->exists($path.$image2)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image2) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                </td>

                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($widget_query->created_at, 'd/m/Y') }}</div>
                </td>
            </tr>
</table>
</div>
</div>