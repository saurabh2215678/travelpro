@component('admin.layouts.main')

@slot('title')
Admin - Manage Theme View Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php

$title = (isset($theme_query->title))?$theme_query->title:'';
$package_id = (isset($theme_query->package_id))?$theme_query->package_id:'';
$slug = (isset($theme_query->slug))?$theme_query->slug:'';
$brief = (isset($theme_query->brief))?$theme_query->brief:'';
$description = (isset($theme_query->description))?$theme_query->description:'';
$status = (isset($theme_query->status))?$theme_query->status:1;
$sort_order = (isset($theme_query->sort_order))?$theme_query->sort_order:0;
$featured = (isset($theme_query->featured))?$theme_query->featured:'';

$page_title = (isset($theme_query->page_title))?$theme_query->page_title:'';
$page_description = (isset($theme_query->page_description))?$theme_query->page_description:'';
$page_keywords = (isset($theme_query->page_keywords))?$theme_query->page_keywords:'';
$image = (isset($theme_query->image))?$theme_query->image:'';
$icon = (isset($theme_query->icon))?$theme_query->icon:'';

$storage = Storage::disk('public');
$path = 'themes/';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/theme';
}
$theme_id = $id;
?>

@if(!empty($theme_id))
<?php $active_menu = "themes"; ?>
    @include('admin.includes.themefaqmenu')
@else
<?php $active_menu = "packages.theme"; ?>
    @include('admin.includes.packagemenu')
@endif
<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>
<div class="add_button">
<?php /*if(request()->has('back_url')){ $back_url= request('back_url');  ?>

<a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
Back</a><?php }*/ ?>
<a href="{{ route($routeName.'.'.$segment.'.theme_edit', $theme_query->id.'?back_url='.$back_url) }}" title="Edit Theme" class="btn_admin"><i class="fas fa-edit"></i>Edit Theme</a>
</div>
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
                    <div><b>Title: </b></div>
                    <div>{{$theme_query->title}}</div>
                </td>
                <td>
                    <div><b>{{ucfirst($segment)}} Name: </b></div>
                    <div>{{(isset($theme_query->packagesName)) ? $theme_query->packagesName:''}}</div>
                </td>
                <td>
                    <div><b>Brief: </b></div>
                    <div>{!! $theme_query->brief !!}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Description: </b></div>
                    <div>{!! $theme_query->description !!}</div>
                </td>
             </tr>

            <tr>
                <td>
                    <div><b>Sort Order: </b></div>
                    <div>{{$theme_query->sort_order}}</div>
                </td>
                <td>
                    <div><b>Featured: </b></div>
                    <div>{{CustomHelper::getStatusStr($theme_query->featured)}}</div>
                </td>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($theme_query->status) }}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Page Title: </b></div>
                    <div>{{$theme_query->page_title}}</div>
                </td>
                <td>
                    <div><b>Slug: </b></div>
                    <div>{{$theme_query->slug}}</div>
                </td>

                <td>
                    <div><b>Page Description: </b></div>
                    <div>{{$theme_query->page_description}}</div>
                </td>

            </tr>

             <tr>
                <td>
                    <div><b>Page Keywords: </b></div>
                    <div> {{$theme_query->page_keywords}}</div>
                </td>
                <td>
                    <div><b>Image: </b></div>
                    <div><?php
                    if(!empty($image)){
                        if($storage->exists($path.$image)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                </td>

                <td>
                    <div><b>Icon: </b></div>
                    <div> <?php
                    if(!empty($icon)){
                        if($storage->exists($path.$icon)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$icon) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($theme_query->created_at, 'd/m/Y') }}</div>
                </td>
            </tr>
</table>
</div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent