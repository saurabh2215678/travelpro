@component('admin.layouts.main')

@slot('title')
Admin - Manage Itinerary Enquiries View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
//prd($request_detail);
$id = (isset($request_detail->id))?$request_detail->id:'';
$package_id = (isset($request_detail->package_id))?$request_detail->package_id:'';
$name = (isset($request_detail->name))?$request_detail->name:'';
$phone = (isset($request_detail->phone))?$request_detail->phone:'';
$contact_email = (isset($request_detail->email))?$request_detail->email:'';
$country = (isset($request_detail->country))?$request_detail->country:'';
$zip_code = (isset($request_detail->zip_code))?$request_detail->zip_code:'';
$plan_to_travel = (isset($request_detail->plan_to_travel))?$request_detail->plan_to_travel:'';
$travelled_with = (isset($request_detail->travelled_with))?$request_detail->travelled_with:'';
$created_at = (isset($request_detail->created_at))?$request_detail->created_at:'';
// $created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');
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
            <h2>Request Details</h2> 

            <tr>
                <td><b>Package Name : </b></td>
                <td>{{$request_detail->requestitineraryPackage->title}}</td>
            </tr>

            <tr>
                <td><b>Name : </b></td>
                <td>{{$request_detail->name}}</td>
            </tr>

            <tr>
                <td><b>Phone : </b></td>
                <td>{{$request_detail->phone}}</td>
            </tr>

            <tr>
                <td><b>Email : </b></td>
                <td>{{$request_detail->email}}</td>
            </tr>

            <tr>
                <td><b>Country : </b></td>
                <td>{{$request_detail->country}}</td>
            </tr>

            <tr>
                <td><b>Zip Code: </b></td>
                <td>{{$request_detail->zip_code}}</td>
            </tr>

            <tr>
                <td><b>Wen Do You Plan To Travel?: </b></td>
                <td>{{$request_detail->plan_to_travel}}</td>
            </tr>

            <tr>
                <td><b>Have You Travelled With Us Before?: </b></td>
                <td><?php 
                    echo ($request_detail->travelled_with == 1) ? "Yes" : "No";
                    ?>       
                </td>
            </tr>
           
            <tr>
                <td><b>Date Created: </b></td>
                <td>{{ CustomHelper::DateFormat($request_detail->created_at, 'd/m/Y') }}</td>
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