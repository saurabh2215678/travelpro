
@component('admin.layouts.main')

@slot('title')
Admin - Accommodation Feature Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php

$title = (isset($feature->title))?$feature->title:'';
$status = (isset($feature->status))?$feature->status:1; 
$sort_order = (isset($feature->sort_order))?$feature->sort_order:0;

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/airlines';
}
?>
 <?php
$active_menu = "features";
?>
@include('admin.includes.accommodationmenu')

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
                    <div>{{$feature->title}}</div>
                </td>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{CustomHelper::getStatusStr($feature->status)}}</div>
                </td>
                <td>
                    <div><b>Sort Order: </b></div>
                    <div>{{$feature->sort_order}}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{CustomHelper::DateFormat($feature->created_at, 'd/m/Y')}}</div>
                </td>
            </tr>
</table>
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent