<style>
    .centersec.fancybox-content {
    width: 1050px;
    height: 550px;
}
</style>

<?php
$id = (isset($customer->id))?$customer->id:'';
$trip_name_duration = (isset($customer->trip_name_duration))?$customer->trip_name_duration:'';
$local_guide_name = (isset($customer->local_guide_name))?$customer->local_guide_name:'';
$driver_name = (isset($customer->driver_name))?$customer->driver_name:'';
$courteous = (isset($customer->courteous))?$customer->courteous:'';
$helpful = (isset($customer->helpful))?$customer->helpful:'';
$informative = (isset($customer->informative))?$customer->informative:'';
$guide_comments = (isset($customer->guide_comments))?$customer->guide_comments:'';
$sightseeing_tour = (isset($customer->sightseeing_tour))?$customer->sightseeing_tour:'';
$sight_tour_comments = (isset($customer->sight_tour_comments))?$customer->sight_tour_comments:'';
$driver = (isset($customer->driver))?$customer->driver:'';
$vehicle = (isset($customer->vehicle))?$customer->vehicle:'';
$transportation_comment = (isset($customer->transportation_comment))?$customer->transportation_comment:'';
$comfort = (isset($customer->comfort))?$customer->comfort:'';
$services = (isset($customer->services))?$customer->services:'';
$food = (isset($customer->food))?$customer->food:'';
$accommodation_comments = (isset($customer->accommodation_comments))?$customer->accommodation_comments:'';
$foods = (isset($customer->foods))?$customer->foods:'';
$camp_staff = (isset($customer->camp_staff))?$customer->camp_staff:'';
$pony_yak = (isset($customer->pony_yak))?$customer->pony_yak:'';
$trekking_comments = (isset($customer->trekking_comments))?$customer->trekking_comments:'';
$on_tour = (isset($customer->on_tour))?$customer->on_tour:'';
$on_trek = (isset($customer->on_trek))?$customer->on_trek:'';
$garbage_disposal_coomments = (isset($customer->garbage_disposal_coomments))?$customer->garbage_disposal_coomments:'';
$name = (isset($customer->name))?$customer->name:'';
$if_so_why = (isset($customer->if_so_why))?$customer->if_so_why:'';
$highlight_of_trip = (isset($customer->highlight_of_trip))?$customer->highlight_of_trip:'';
$low_point = (isset($customer->low_point))?$customer->low_point:'';
$staff_on_the_trip = (isset($customer->staff_on_the_trip))?$customer->staff_on_the_trip:'';
$trip_expectations = (isset($customer->trip_expectations))?$customer->trip_expectations:'';
$overall_comments = (isset($customer->overall_comments))?$customer->overall_comments:'';
$your_name = (isset($customer->your_name))?$customer->your_name:'';
$address = (isset($customer->address))?$customer->address:'';
$email = (isset($customer->email))?$customer->email:'';
$recommendation = (isset($customer->recommendation))?$customer->recommendation:0;
$hotel_data = json_decode($customer['hotel_data']) ?? '';

$created_at = (isset($customer->created_at))?$customer->created_at:'';
$created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');
?>
<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
            <h2>Customer Review Details</h2> 
            <tr>
                <td><b>Trip Name & Duration : </b></td>
                <td>{{$customer->trip_name_duration}}</td>
            </tr>

            <tr>
                <td><b>Local Guide Name: </b></td>
                <td>{{$customer->local_guide_name}}</td>
            </tr>

            <tr>
                <td><b>Driver’s Name: </b></td>
                <td>{{$customer->driver_name}}</td>
            </tr>

            <tr>
                <td><b>Courteous : </b></td>
                <td>{{$customer->courteous.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Helpful : </b></td>
                <td>{{$customer->helpful.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Informative: </b></td>
                <td>{{$customer->informative.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Was Your Guide Comments: </b></td>
                <td>{{$customer->guide_comments}}</td>
            </tr>

            <tr>
                <td><b>Sightseeing Tour: </b></td>
                <td>{{$customer->sightseeing_tour.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Sightseeing Comments: </b></td>
                <td>{{$customer->sight_tour_comments}}</td>
            </tr>

            <tr>
                <td><b>Driver: </b></td>
                <td>{{$customer->driver.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Vehicle: </b></td>
                <td>{{$customer->vehicle.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Transportation Comments: </b></td>
                <td>{{$customer->transportation_comment}}</td>
            </tr>

            @for($i=1; $i<=4; $i++)
            <?php
                $hotel_concat = "hotel_name_$i";
                $comfort_concat = "comfort_$i";
                $services_concat = "services_$i";
                $food_concat = "food_$i";
                $accommodation_comments_concat = "accommodation_comments_$i";

                $hotelData = $hotel_data->$hotel_concat??'';
                $comfortData = $hotel_data->$comfort_concat??0;
                $servicesData = $hotel_data->$services_concat??0;
                $foodData = $hotel_data->$food_concat??0;
                $accommodationCommentData = $hotel_data->$accommodation_comments_concat??'';
            ?>
            <tr>
                <td><b>Hotel name {{$i}}: </b></td>
                <td>{{$hotelData}}</td>
            </tr>
            <tr>
                <td><b>Comfort: </b></td>
                <td>{{$comfortData.' /5 Rating'}}</td>
            </tr>
            <tr>
                <td><b>Services: </b></td>
                <td>{{$servicesData.' /5 Rating'}}</td>
            </tr>
            <tr>
                <td><b>Food: </b></td>
                <td>{{$foodData.' /5 Rating'}}</td>
            </tr>
            <tr>
                <td><b>Accommodation Comments: </b></td>
                <td>{{$accommodationCommentData}}</td>
            </tr>
            @endfor
            <tr>
                <td><b>Food: </b></td>
                <td>{{$customer->foods.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Camp Staff : </b></td>
                <td>{{$customer->camp_staff.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Pony/Yak: </b></td>
                <td>{{$customer->pony_yak.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Trekking Comments: </b></td>
                <td>{{$customer->trekking_comments}}</td>
            </tr>

            <tr>
                <td><b>On Tour: </b></td>
                <td>{{$customer->on_tour.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>On Trek : </b></td>
                <td>{{$customer->on_trek.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Garbage Disposal Comments: </b></td>
                <td>{{$customer->garbage_disposal_coomments}}</td>
            </tr>

            <tr>
                <td><b>Outstanding Performance By Any Staff On The Trip/Name: </b></td>
                <td>{{$customer->name}}</td>
            </tr>

            <tr>
                <td><b>If So Why?: </b></td>
                <td>{{$customer->if_so_why}}</td>
            </tr>

            <tr>
                <td><b>What Was The Highlight Of Trip For You?: </b></td>
                <td>{{$customer->highlight_of_trip}}</td>
            </tr>

            <tr>
                <td><b>What Was The Low Point?: </b></td>
                <td>{{$customer->low_point}}</td>
            </tr>

            <tr>
                <td><b>Outstanding Performance By Any Staff On The Trip: </b></td>
                <td>
                <?php echo ($staff_on_the_trip == 1) ? "Yes" : "No";
                    ?>  
                </td>
            </tr>

            <tr>
                <td><b>Did Your Trip Live Up To Your Expectations?: </b></td>
                <td>{{$customer->trip_expectations.' /5 Rating'}}</td>
            </tr>

            <tr>
                <td><b>Overall Comments: </b></td>
                <td>{{$customer->overall_comments}}</td>
            </tr>

            <tr>
                <td><b>Your Name: </b></td>
                <td>{{$customer->your_name}}</td>
            </tr>

            <tr>
                <td><b>Address: </b></td>
                <td>{{$customer->address}}</td>
            </tr>

            <tr>
                <td><b>E-Mail: </b></td>
                <td>{{$customer->email}}</td>
            </tr>
           
            <tr>
                <td><b>Date Created: </b></td>
                <td>{{ CustomHelper::DateFormat($customer->created_at, 'd/m/Y') }}</td>
            </tr>
        </table>
    </td>
</tr>
</table> 
</div>
</div>