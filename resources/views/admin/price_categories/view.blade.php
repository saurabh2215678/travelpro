@component('admin.layouts.main')

@slot('title')
Admin - Manage Price Category View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

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

?>
<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>

<table width="1100" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
            <h2>Widget Detail</h2> 
            <tr>
                <td><b>Widget Name : </b></td>
                <td>{{$widget_query->widget_name}}</td>
            </tr>

            <tr>
                <td><b>Widget Identifier : </b></td>
                <td>{{$widget_query->slug}}</td>
            </tr>

            <tr>
                <td><b>Section Heading: </b></td>
                <td>{{$widget_query->section_heading}}</td>
            </tr>
            <tr>
                <td><b>Section Sub Heading: </b></td>
                <td>{{$widget_query->section_sub_heading}}</td>
            </tr>

             <tr>
                <td><b>About Widget Description: </b></td>
                <td>{!!$widget_query->about_widget_desc!!}</td>
            </tr>

            <tr>
                <td><b>Description: </b></td>
                <td>{!!$widget_query->description!!}</td>
            </tr>
            <tr>
                <td><b>Status: </b></td>
                <td>{{ CustomHelper::getStatusStr($widget_query->status) }}</td>
            </tr>
            <tr>
                <td><b>Image1: </b></td>
                <td>
                    <?php
                    if(!empty($image1)){
                        if($storage->exists($path.$image1)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image1) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </td>
            </tr>   
            <tr>
                <td><b>Image2: </b></td>
                <td>
                    <?php
                    if(!empty($image2)){
                        if($storage->exists($path.$image2)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image2) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td><b>Date Created: </b></td>
                <td>{{ CustomHelper::DateFormat($widget_query->created_at, 'd/m/Y') }}</td>
            </tr>
        </table>
    </td>
</tr>
</table> 
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent