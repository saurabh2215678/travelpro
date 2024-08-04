@component('admin.layouts.main')

@slot('title')
Admin - Manage Enquiries View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$id = (isset($enquiry->id))?$enquiry->id:'';
$name = (isset($enquiry->name))?$enquiry->name:'';
$phone = (isset($enquiry->phone))?$enquiry->phone:'';
$contact_email = (isset($enquiry->contact_email))?$enquiry->contact_email:'';
$comment = (isset($enquiry->comment))?$enquiry->comment:'';
$country = (isset($enquiry->country))?$enquiry->country:'';
$month_of_travel = (isset($enquiry->month_of_travel))?$enquiry->month_of_travel:'';
$duration = (isset($enquiry->duration))?$enquiry->duration:'';
$person_count = (isset($enquiry->person_count))?$enquiry->person_count:'';
$created_at = (isset($enquiry->created_at))?$enquiry->created_at:'';
$created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');
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
            <h2>Contact-Us Enquiry Details</h2> 
            <tr>
                <td><b>Name : </b></td>
                <td>{{$enquiry->name}}</td>
            </tr>

            <tr>
                <td><b>Phone : </b></td>
                <td>{{$enquiry->phone}}</td>
            </tr>

            <tr>
                <td><b>Email : </b></td>
                <td>{{$enquiry->contact_email}}</td>
            </tr>

            <tr>
                <td><b>Message : </b></td>
                <td>{{$enquiry->comment}}</td>
            </tr>

            <tr>
                <td><b>Country : </b></td>
                <td>{{$enquiry->country}}</td>
            </tr>

            <tr>
                <td><b>Month Of Travel : </b></td>
                <td>{{$enquiry->month_of_travel}}</td>
            </tr>

            <tr>
                <td><b>Duration Days : </b></td>
                <td>{{$enquiry->duration}}</td>
            </tr>

            <tr>
                <td><b>Number Of Total Person : </b></td>
                <td>{{$enquiry->person_count}}</td>
            </tr>
           
            <tr>
                <td><b>Date Created: </b></td>
                <td>{{ CustomHelper::DateFormat($enquiry->created_at, 'd/m/Y') }}</td>
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