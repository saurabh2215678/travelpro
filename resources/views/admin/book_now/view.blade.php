@component('admin.layouts.main')

@slot('title')
Admin - Manage Booking Enquiry View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
//prd($request_detail);
$id = (isset($booking->id))?$booking->id:'';
$package_id = (isset($booking->package_id))?$booking->package_id:'';
$name = (isset($booking->name))?$booking->name:'';
$phone = (isset($booking->phone))?$booking->phone:'';
$contact_email = (isset($booking->email))?$booking->email:'';
$address1 = (isset($booking->address1))?$booking->address1:'';
$address2 = (isset($booking->address2))?$booking->address2:'';
$city = (isset($booking->city))?$booking->city:'';
$state = (isset($booking->state))?$booking->state:'';
$country = (isset($booking->country))?$booking->country:'';
$zip_code = (isset($booking->zip_code))?$booking->zip_code:'';
$trip_date = (isset($booking->trip_date))?$booking->trip_date:'';
$service_level = (isset($booking->service_level))?$booking->service_level:'';
$original_price = (isset($booking->original_price))?$booking->original_price:'';
$final_pay_price = (isset($booking->final_pay_price))?$booking->final_pay_price:'';
$created_at = (isset($booking->created_at))?$booking->created_at:'';
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
            <h2>Booking Enquiry Details</h2> 
            <tr>
                <td><b>Package Name : </b></td>
                <td>{{$booking->bookingPackage->title}}</td>
            </tr>

            <tr>
                <td><b>Name : </b></td>
                <td>{{$booking->name}}</td>
            </tr>

            <tr>
                <td><b>Phone : </b></td>
                <td>{{$booking->phone}}</td>
            </tr>

            <tr>
                <td><b>Email : </b></td>
                <td>{{$booking->email}}</td>
            </tr>

            <tr>
                <td><b> Address 1: </b></td>
                <td>{{$booking->address1}}</td>
            </tr>

            <tr>
                <td><b> Address 2: </b></td>
                <td>{{$booking->address2}}</td>
            </tr>

            <tr>
                <td><b> City : </b></td>
                <td>{{$booking->city}}</td>
            </tr>

            <tr>
                <td><b> State : </b></td>
                <td>{{$booking->state}}</td>
            </tr>

            <tr>
                <td><b>Country : </b></td>
                <td>{{$booking->country}}</td>
            </tr>

            <tr>
                <td><b>Zip Code: </b></td>
                <td>{{$booking->zip_code}}</td>
            </tr>

            <tr>
                <td><b>Select Trip Date: </b></td>
                <td>
                    <?php echo $booking->trip_date ; ?>
                </td>
            </tr>

            <tr>
                <td><b>Service Level: </b></td>
                <td><?php 
                    echo ($booking->service_level == 1) ? "Standard" : "Deluxe";
                    ?>       
                </td>
            </tr>

            <tr>
                <td><b>Price: </b></td>
                <td>{{$booking->original_price}}</td>
            </tr>

            <tr>
                <td><b>Final Price: </b></td>
                <td>{{$booking->final_pay_price}}</td>
            </tr>
           
            <tr>
                <td><b>Date Created: </b></td>
                <td>{{ CustomHelper::DateFormat($booking->created_at, 'd/m/Y') }}</td>
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