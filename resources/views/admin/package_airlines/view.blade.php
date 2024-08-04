@component('admin.layouts.main')

@slot('title')
Admin - Manage {{ucfirst($segment)}} Airlines Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<?php

$id = (isset($airline_query->id))?$airline_query->id:'';
$airline_name = (isset($airline_query->airline_name))?$airline_query->airline_name:'';
$airline_code = (isset($airline_query->airline_code))?$airline_query->airline_code:'';
// $airline_from = (isset($airline_query->airline_from))?$airline_query->airline_from:'';
// $airline_to = (isset($airline_query->airline_to))?$airline_query->airline_to:'';
// $price = (isset($airline_query->price))?$airline_query->price:'';
$status = (isset($airline_query->status))?$airline_query->status:1;
$sort_order = (isset($airline_query->sort_order))?$airline_query->sort_order:0;
$image = (isset($airline_query->image))?$airline_query->image:'';

$storage = Storage::disk('public');
$path = 'airlines/';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/airlines';
}
?>
<?php
$active_menu = "packages.airlines";
?>
@include('admin.includes.packagemenu')

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
                    <div><b>Airline Name: </b></div>
                    <div>{{$airline_query->airline_name}}</div>
                </td>
                <td>
                    <div><b>Airline Code: </b></div>
                    <div>{{$airline_query->airline_code}}</div>
                </td>
                <?php /*<td>
                    <div><b>Airline From: </b></div>
                    <div>{{$airline_query->airline_from}}</div>
                </td>
                <td>
                    <div><b>Airline To: </b></div>
                    <div>{{$airline_query->airline_to}}</div>
                </td> */ ?>
            </tr>

            <tr>
                <?php /*<td>
                    <div><b>Price: </b></div>
                    <div>{{$airline_query->price}}</div>
                </td>**/ ?>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($airline_query->status) }}</div>
                </td>
                <td>
                    <div><b>Sort Order: </b></div>
                    <div>{{$airline_query->sort_order}}</div>
                </td>
            </tr>

            <tr>
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
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($airline_query->created_at, 'd/m/Y') }}</div>
                </td>

            </tr>
</table>
</div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent