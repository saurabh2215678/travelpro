@component('admin.layouts.main')

@slot('title')
Admin - Manage Package Enquiries View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$id = (isset($customize_package->id))?$customize_package->id:'';
$package_id = (isset($customize_package->package_id))?$customize_package->package_id:'';
$name = (isset($customize_package->name))?$customize_package->name:'';
$phone = (isset($customize_package->phone))?$customize_package->phone:'';
$contact_email = (isset($customize_package->email))?$customize_package->email:'';
$country = (isset($customize_package->country))?$customize_package->country:'';
$content = (isset($customize_package->content))?$customize_package->content:'';
$zip_code = (isset($customize_package->zip_code))?$customize_package->zip_code:'';
$want_to_travel = (isset($customize_package->want_to_travel))?$customize_package->want_to_travel:'';
$no_of_packs = (isset($customize_package->no_of_packs))?$customize_package->no_of_packs:'';
$duration_of_stay = (isset($customize_package->duration_of_stay))?$customize_package->duration_of_stay:'';
$need_flight = (isset($customize_package->need_flight))?$customize_package->need_flight:'';
$travelled_with = (isset($customize_package->travelled_with))?$customize_package->travelled_with:'';
$created_at = (isset($customize_package->created_at))?$customize_package->created_at:'';
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
            <h2>Customize Package Details</h2> 

            <tr>
                <td><b>Package Name : </b></td>
                <td>{{$customize_package->customizePackage->title}}</td>
            </tr>

            <tr>
                <td><b>Name : </b></td>
                <td>{{$customize_package->name}}</td>
            </tr>

            <tr>
                <td><b>Phone : </b></td>
                <td>{{$customize_package->phone}}</td>
            </tr>

            <tr>
                <td><b>Email : </b></td>
                <td>{{$customize_package->email}}</td>
            </tr>

            <tr>
                <td><b>Country : </b></td>
                <td>{{$customize_package->country}}</td>
            </tr>

            <tr>
                <td><b>Zip Code : </b></td>
                <td>{{$customize_package->zip_code}}</td>
            </tr>

            <tr>
                <td><b>When Do You Want To Travel?: </b></td>
                <td>{{$customize_package->want_to_travel}}</td>
            </tr>

            <tr>
                <td><b>Number Of Packs: </b></td>
                <td>{{$customize_package->no_of_packs}}</td>
            </tr>
           
           <tr>
                <td><b>Duration Of Stay: </b></td>
                <td>{{$customize_package->duration_of_stay}}</td>
            </tr>

            <tr>
                <td><b>Do You Need Flight Also?: </b></td>
                <td>
                    <?php 
                    echo ($customize_package->need_flight == 1) ? "Yes" : "No";
                    ?>
                </td>
            </tr>

            <tr>
                <td><b>Have You Travelled With Us Before?: </b></td>
                <td>
                    <?php 
                    echo ($customize_package->travelled_with == 1) ? "Yes" : "No";
                    ?>
                </td>
            </tr>

            <tr>
                <td><b>Comments: </b></td>
                <td>{{$customize_package->content}}</td>
            </tr>


            <tr>
                <td><b>Date Created: </b></td>
                <td>{{ CustomHelper::DateFormat($customize_package->created_at, 'd/m/Y') }}</td>
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