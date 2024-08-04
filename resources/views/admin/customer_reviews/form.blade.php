@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />

@endslot

<?php
$emailData = '';
$BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();
$id = (isset($customer_review->id))?$customer_review->id:'';
$trip_name_duration = (isset($customer_review->trip_name_duration))?$customer_review->trip_name_duration:'';
$local_guide_name = (isset($customer_review->local_guide_name))?$customer_review->local_guide_name:'';
$driver_name = (isset($customer_review->driver_name))?$customer_review->driver_name:'';
$courteous = (isset($customer_review->courteous))?$customer_review->courteous:'';
$helpful = (isset($customer_review->helpful))?$customer_review->helpful:'';
$informative = (isset($customer_review->informative))?$customer_review->informative:'';
$guide_comments = (isset($customer_review->guide_comments))?$customer_review->guide_comments:'';
$sightseeing_tour = (isset($customer_review->sightseeing_tour))?$customer_review->sightseeing_tour:'';
$sight_tour_comments = (isset($customer_review->sight_tour_comments))?$customer_review->sight_tour_comments:'';
$driver = (isset($customer_review->driver))?$customer_review->driver:'';
$vehicle = (isset($customer_review->vehicle))?$customer_review->vehicle:'';
$transportation_comment = (isset($customer_review->transportation_comment))?$customer_review->transportation_comment:'';
$comfort = (isset($customer_review->comfort))?$customer_review->comfort:'';
$services = (isset($customer_review->services))?$customer_review->services:'';
$food = (isset($customer_review->food))?$customer_review->food:'';
$accommodation_comments = (isset($customer_review->accommodation_comments))?$customer_review->accommodation_comments:'';
$foods = (isset($customer_review->foods))?$customer_review->foods:'';
$camp_staff = (isset($customer_review->camp_staff))?$customer_review->camp_staff:'';
$pony_yak = (isset($customer_review->pony_yak))?$customer_review->pony_yak:'';
$trekking_comments = (isset($customer_review->trekking_comments))?$customer_review->trekking_comments:'';
$on_tour = (isset($customer_review->on_tour))?$customer_review->on_tour:'';
$on_trek = (isset($customer_review->on_trek))?$customer_review->on_trek:'';
$garbage_disposal_coomments = (isset($customer_review->garbage_disposal_coomments))?$customer_review->garbage_disposal_coomments:'';
$name = (isset($customer_review->name))?$customer_review->name:'';
$if_so_why = (isset($customer_review->if_so_why))?$customer_review->if_so_why:'';
$highlight_of_trip = (isset($customer_review->highlight_of_trip))?$customer_review->highlight_of_trip:'';
$low_point = (isset($customer_review->low_point))?$customer_review->low_point:'';
$staff_on_the_trip = (isset($customer_review->staff_on_the_trip))?$customer_review->staff_on_the_trip:'';
$trip_expectations = (isset($customer_review->trip_expectations))?$customer_review->trip_expectations:'';
$overall_comments = (isset($customer_review->overall_comments))?$customer_review->overall_comments:'';
$your_name = (isset($customer_review->your_name))?$customer_review->your_name:'';
$address = (isset($customer_review->address))?$customer_review->address:'';
$email = (isset($customer_review->email))?$customer_review->email:'';
$trip_date = (isset($customer_review->trip_date))?$customer_review->trip_date:'';
$recommendation = (isset($customer_review->recommendation))?$customer_review->recommendation:0;
$hotel_data = json_decode($customer_review['hotel_data']) ?? '';
?>
<style>
/*start Feedback */
.form_control { display: block; width: 100%; padding: 0.25rem 0.75rem; font-size: 14px; font-weight: 400; line-height: 1.5; color: #495057; background-color: #f0f4f7; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem; }
.form-experience ul { display: flex; flex-wrap: wrap; padding: 10px 10px; }
.form-experience .form-group {margin-bottom: 0;}
.form-experience ul li { width: 33%; padding: 0 10px; margin-top: 10px; list-style: none; }
/* .form-experience .form-group label {
    margin-bottom: 0;
} */
.featured_box .title { line-height: normal; font-weight: 700; font-size:16px; color: rgb(44, 45, 108); z-index: 99; position: relative; text-transform: inherit; display: inline-block; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 1; line-clamp: 1; -webkit-box-orient: vertical; margin-top: 5px; }
.mb-20 {margin-bottom: 20px;}
.form-experience {border: 1px solid #d1d1d1;}
.w-100 {width: 100% !important;}
.ht-110 {height: 110px !important;}
.bgform {background-color: #fff6ec;}
.brd-top {border-top: 1px dashed #b7b7b7;padding-top: 10px;}
.likeimg {filter: grayscale(100%); width: 50px;}
.opacity0 {opacity: 0;display: none;}
.dflex {display: flex; align-items: center;height: 100%;}
.dflex img {margin-right: 15px;}
input[type='radio']:checked+.likeimg {filter: grayscale(0%);}
.bgform button:hover {cursor: pointer;}
.rating1 { width: 100%; background: transparent; display: flex; justify-content: flex-start; align-items: center; padding: 0px 0; overflow: hidden; }
.rating1 i { color: #cdcdcd; cursor: pointer; margin-right: 10px; font-size: 24px; }
.rating__star { font-size: 1.3em; cursor: pointer; color: #efb23c; transition: filter linear .3s; font-size: 24px; }
.rating__star:hover {color: #efb23c;}
.rating1 i.active {color: #efb23c;}
.tabcontent {display: none;}
.rating { display: inline-block; position: relative; height: 28px; line-height: 28px; font-size: 28px; }
.rating label { position: absolute; top: 0; left: 0; height: 100%; cursor: pointer; }
.rating label:last-child {position: static;}
.rating label:nth-child(1) { z-index: 5;}
.rating label:nth-child(2) {z-index: 4;}
.rating label:nth-child(3) {z-index: 3;}
.rating label:nth-child(4) {z-index: 2;}
.rating label:nth-child(5) {z-index: 1;}
.rating label input { position: absolute !important; top: 0; left: 0; opacity: 0; }
.rating label .icon { float: left; color: transparent; margin-right: 10px; font-size: 20px; }
.rating label:last-child .icon {color: #cdcdcd;}
.rating:not(:hover) label input:checked~.icon,
.rating:hover label:hover input~.icon {color: #efb23c;}
.rating label input:focus:not(:checked)~.icon:last-child {color: #000;text-shadow: 0 0 5px #09f;}
.form-btn-list {padding: 0 15px 15px;}
.pb {padding-bottom: 5px;}

</style>

<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
        @if(isset($BackUrl))
        <a href="{{ url($BackUrl)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>Back</a>
        @endif
    </div>
</div>

<div class="centersec">
    <div class="bgcolor">
        @include('snippets.errors')
        @include('snippets.flash')
        <div class="ajax_msg"></div>
        <div class="form-experience">
            <form method="POST" id="customer-review-frm" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <ul>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Trip Name & Duration :</label>
                            <input type="text" placeholder="" name="trip_name_duration" id="trip_name_duration"
                                value="{{old('trip_name_duration',$trip_name_duration)}}" class="form_control">
                            @include('snippets.front.errors_first', ['param' => 'trip_name_duration'])
                        </div>
                    </li>
                    <!-- <li>
                    <div class="form-group">
                         <label for="exampleInputEmail1">Trip Date :</label>
                         <input type="text" name="trip_date" id="trip_date" value="{{old('trip_date',$trip_date)}}" class="form_control trip_date" placeholder="DD/MM/YYYY">
                         @include('snippets.front.errors_first', ['param' => 'trip_date'])
                       </div>
                     </li> -->
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Local Guide Name :</label>
                            <input type="text" placeholder="" name="local_guide_name" id="local_guide_name"
                                value="{{old('local_guide_name',$local_guide_name)}}" class="form_control">
                            @include('snippets.front.errors_first', ['param' => 'local_guide_name'])
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Driver’s Name :</label>
                            <input type="text" placeholder="" name="driver_name" id="driver_name"
                                value="{{old('driver_name',$driver_name)}}" class="form_control">
                            @include('snippets.front.errors_first', ['param' => 'driver_name'])
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="featured_box">
                            <div class="title heading3">Was Your Guide</div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Courteous :</label>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="courteous" value="1"
                                            {{ old("courteous",$courteous) =='1' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="courteous" value="2"
                                            {{ old("courteous",$courteous) =='2' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="courteous" value="3"
                                            {{ old("courteous",$courteous) =='3' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="courteous" value="4"
                                            {{ old("courteous",$courteous) =='4' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="courteous" value="5"
                                            {{ old("courteous",$courteous) =='5' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Helpful :</label>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="helpful" value="1"
                                            {{ old("helpful", $helpful) =='1' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="helpful" value="2"
                                            {{ old("helpful", $helpful) =='2' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="helpful" value="3"
                                            {{ old("helpful", $helpful) =='3' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="helpful" value="4"
                                            {{ old("helpful", $helpful) =='4' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="helpful" value="5"
                                            {{ old("helpful", $helpful) =='5' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Informative :</label>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="informative" value="1"
                                            {{ old("informative", $informative) =='1' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="informative" value="2"
                                            {{ old("informative", $informative) =='2' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="informative" value="3"
                                            {{ old("informative", $informative) =='3' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="informative" value="4"
                                            {{ old("informative", $informative) =='4' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="informative" value="5"
                                            {{ old("informative", $informative) =='5' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Comments :</label>
                            <textarea class="form_control" placeholder="" id="guide_comments"
                                name="guide_comments"
                                rows="3"><?php echo old('guide_comments',$guide_comments); ?></textarea>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group brd-top">
                            <div class="featured_box">
                                <div class="title heading3">Sightseeing Tour :</div>
                            </div>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="sightseeing_tour" value="1"
                                            {{ old("sightseeing_tour", $sightseeing_tour) =='1' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="sightseeing_tour" value="2"
                                            {{ old("sightseeing_tour", $sightseeing_tour) =='2' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="sightseeing_tour" value="3"
                                            {{ old("sightseeing_tour", $sightseeing_tour) =='3' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="sightseeing_tour" value="4"
                                            {{ old("sightseeing_tour", $sightseeing_tour) =='4' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="sightseeing_tour" value="5"
                                            {{ old("sightseeing_tour", $sightseeing_tour) =='5' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Comments :</label>
                            <textarea class="form_control" placeholder="" id="sight_tour_comments"
                                name="sight_tour_comments"
                                rows="3"><?php echo old('sight_tour_comments', $sight_tour_comments); ?></textarea>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="featured_box">
                            <div class="title heading3">Transportation :</div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Driver :</label>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="driver" value="1"
                                            {{ old("driver", $driver) =='1' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="driver" value="2"
                                            {{ old("driver", $driver) =='2' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="driver" value="3"
                                            {{ old("driver", $driver) =='3' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="driver" value="4"
                                            {{ old("driver", $driver) =='4' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="driver" value="5"
                                            {{ old("driver", $driver) =='5' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Vehicle :</label>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="vehicle" value="1"
                                            {{ old("vehicle", $vehicle) =='1' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="vehicle" value="2"
                                            {{ old("vehicle", $vehicle) =='2' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="vehicle" value="3"
                                            {{ old("vehicle", $vehicle) =='3' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="vehicle" value="4"
                                            {{ old("vehicle", $vehicle) =='4' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="vehicle" value="5"
                                            {{ old("vehicle", $vehicle) =='5' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Comments :</label>
                            <textarea class="form_control" placeholder="" id="transportation_comment"
                                name="transportation_comment"
                                rows="3"><?php echo old('transportation_comment', $transportation_comment); ?></textarea>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="featured_box brd-top">
                            <div class="title heading3">Accommodation :</div>
                        </div>
                    </li>
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
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Hotel name {{$i}} :</label>
                         <input type="text" placeholder="" name="hotel_name_{{$i}}" id="hotel_name_{{$i}}" value="{{old('hotel_name_'.$i,$hotelData)}}" class="form_control">
                         @include('snippets.front.errors_first', ['param' => 'hotel_name'])
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Comfort :</label>
                         <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="1" {{ old('comfort_'.$i, $comfortData) =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="2" {{ old('comfort_'.$i, $comfortData) =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="3" {{ old('comfort_'.$i, $comfortData) =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="4" {{ old('comfort_'.$i, $comfortData) =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="5" {{ old('comfort_'.$i, $comfortData) =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Services :</label>
                         <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="1" {{ old('services_'.$i, $servicesData) =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="2" {{ old('services_'.$i, $servicesData) =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="3" {{ old('services_'.$i, $servicesData) =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="4" {{ old('services_'.$i, $servicesData) =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="5" {{ old('services_'.$i, $servicesData) =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Food :</label>
                         <div class="rating1">
                           <div class="rating">
                            <label>
                                <input type="radio" name="food_{{$i}}" value="1" {{ old('food_'.$i, $foodData) =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="food_{{$i}}" value="2" {{ old('food_'.$i, $foodData) =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="food_{{$i}}" value="3" {{ old('food_'.$i, $foodData) =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                              <label>
                                <input type="radio" name="food_{{$i}}" value="4" {{ old('food_'.$i, $foodData) =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                <input type="radio" name="food_{{$i}}" value="5" {{ old('food_'.$i, $foodData) =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                       </div>
                     </li>
                     <li class="w-100">
                        <div class="form-group">
                         <label for="exampleInputEmail1">Comments :</label>
                         <textarea class="form_control" placeholder="" id="accommodation_comments_{{$i}}" name="accommodation_comments_{{$i}}" rows="3"><?php echo old('accommodation_comments_'.$i, $accommodationCommentData); ?></textarea>
                       </div>
                     </li>
                     @endfor
                    <li class="w-100 mt-35">
                        <div class="featured_box brd-top">
                            <div class="title heading3">Trekking :</div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Food :</label>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="foods" value="1"
                                            {{ old("foods", $foods) =='1' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="foods" value="2"
                                            {{ old("foods", $foods) =='2' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="foods" value="3"
                                            {{ old("foods", $foods) =='3' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="foods" value="4"
                                            {{ old("foods", $foods) =='4' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="foods" value="5"
                                            {{ old("foods", $foods) =='5' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Camp Staff :</label>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="camp_staff" value="1"
                                            {{ old("camp_staff", $camp_staff) =='1' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="camp_staff" value="2"
                                            {{ old("camp_staff", $camp_staff) =='2' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="camp_staff" value="3"
                                            {{ old("camp_staff", $camp_staff) =='3' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="camp_staff" value="4"
                                            {{ old("camp_staff", $camp_staff) =='4' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="camp_staff" value="5"
                                            {{ old("camp_staff", $camp_staff) =='5' ? 'checked' : '' }}
                                            class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Pony/Yak :</label>
                            <div class="rating1">
                                <div class="rating">

                                    <label>
                                        <input type="radio" name="pony_yak" value="1"
                                            {{ old("pony_yak", $pony_yak) =='1' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="pony_yak" value="2"
                                            {{ old("pony_yak", $pony_yak) =='2' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="pony_yak" value="3"
                                            {{ old("pony_yak", $pony_yak) =='3' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="pony_yak" value="4"
                                            {{ old("pony_yak", $pony_yak) =='4' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="pony_yak" value="5"
                                            {{ old("pony_yak", $pony_yak) =='5' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Comments :</label>
                            <textarea class="form_control" placeholder="" id="trekking_comments"
                                name="trekking_comments"
                                rows="3"><?php echo old('trekking_comments', $trekking_comments); ?></textarea>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="featured_box brd-top">
                            <div class="title heading3">Garbage Disposal :</div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">On Tour :</label>
                            <div class="rating1">
                                <div class="rating">

                                    <label>
                                        <input type="radio" name="on_tour" value="1"
                                            {{ old("on_tour", $on_tour) =='1' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="on_tour" value="2"
                                            {{ old("on_tour", $on_tour) =='2' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="on_tour" value="3"
                                            {{ old("on_tour", $on_tour) =='3' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="on_tour" value="4"
                                            {{ old("on_tour", $on_tour) =='4' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="on_tour" value="5"
                                            {{ old("on_tour", $on_tour) =='5' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="exampleInputEmail1">On Trek :</label>
                            <div class="rating1">
                                <div class="rating">
                                    <label>
                                        <input type="radio" name="on_trek" value="1"
                                            {{ old("on_trek", $on_trek) =='1' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="on_trek" value="2"
                                            {{ old("on_trek", $on_trek) =='2' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="on_trek" value="3"
                                            {{ old("on_trek", $on_trek) =='3' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="on_trek" value="4"
                                            {{ old("on_trek", $on_trek) =='4' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="on_trek" value="5"
                                            {{ old("on_trek", $on_trek) =='5' ? 'checked' : '' }} class="str-rat" />
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                        <span class="icon">★</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Comments :</label>
                            <textarea class="form_control" placeholder="" id="garbage_disposal_coomments"
                                name="garbage_disposal_coomments"
                                rows="3"><?php echo old('garbage_disposal_coomments', $garbage_disposal_coomments); ?></textarea>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="featured_box brd-top">
                            <div class="title heading3">Outstanding Performance By Any Staff On The Trip :</div>
                        </div>
                    </li>
                    <li class="w-100 ">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name :</label>
                            <input type="text" placeholder="" id="name" name="name" value="{{old('name', $name)}}"
                                class="form_control">
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group">
                            <label for="exampleInputEmail1">If So Why? :</label>
                            <textarea class="form_control" placeholder="" id="if_so_why" name="if_so_why"
                                rows="3"><?php echo old('if_so_why', $if_so_why); ?></textarea>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group brd-top">
                            <div class="featured_box">
                                <div class="title heading3 pb">What Was The Highlight Of Trip For You?</div>
                            </div>
                            <textarea class="form_control" placeholder="" id="highlight_of_trip"
                                name="highlight_of_trip"
                                rows="3"><?php echo old('highlight_of_trip', $highlight_of_trip); ?></textarea>
                        </div>
                    </li>
                    <li class="w-100 mt-35">
                        <div class="form-group">
                            <div class="featured_box">
                                <div class="title heading3 pb">What Was The Low Point?</div>
                            </div>
                            <textarea class="form_control" placeholder="" id="low_point" name="low_point"
                                rows="3"><?php echo old('low_point', $low_point); ?></textarea>
                        </div>
                    </li>

                    <!-- <li class="w-100 mt-35">
                        <div class="featured_box">
                            <div class="title heading3">Outstanding Performance By Any Staff On The Trip :</div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label class="dflex"><input type="radio" name="staff_on_the_trip" value="1"
                                    {{ old("staff_on_the_trip", $staff_on_the_trip) =='1' ? 'checked' : '' }}
                                    <?php //echo ($emailData == '1')?'checked':''; ?> class="opacity0"><img
                                    src="{{asset(config('custom.assets').'/images/like.svg')}}" class="likeimg">
                                Yes</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label class="dflex"><input type="radio" name="staff_on_the_trip" value="0"
                                    {{ old("staff_on_the_trip", $staff_on_the_trip) =='0' ? 'checked' : '' }}
                                    <?php //echo ( strlen($emailData) > 0 && $emailData == '0')?'checked':''; ?>
                                    class="opacity0"><img src="{{asset(config('custom.assets').'/images/dislike.svg')}}"
                                    class="likeimg"> No</label>
                        </div>
                    </li>
                    <li></li> -->
                    <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">How likely will you recommend us to your friends and family? :</label>
                          <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="recommendation" value="1" {{ old("recommendation", $recommendation) =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="recommendation" value="2" {{ old("recommendation", $recommendation) =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="recommendation" value="3" {{ old("recommendation", $recommendation) =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="recommendation" value="4" {{ old("recommendation", $recommendation) =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="recommendation" value="5" {{ old("recommendation", $recommendation) =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                       </div>
                     </li>

                    <!-- <li class="w-100 mt-35">
                        <div class="featured_box">
                            <div class="title heading3">Did Your Trip Live Up To Your Expectations?</div>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label class="dflex"><input type="radio" name="trip_expectations" value="1"
                                    {{ old("trip_expectations", $trip_expectations) =='1' ? 'checked' : '' }}
                                    <?php //echo ($emailData == '1')?'checked':''; ?> class="opacity0"><img
                                    src="{{asset(config('custom.assets').'/images/like.svg')}}" class="likeimg">
                                Yes</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label class="dflex"><input type="radio" name="trip_expectations" value="0"
                                    {{ old("trip_expectations", $trip_expectations) =='0' ? 'checked' : '' }}
                                    <?php //echo ( strlen($emailData) > 0 && $emailData == '0')?'checked':''; ?>
                                    class="opacity0"><img src="{{asset(config('custom.assets').'/images/dislike.svg')}}"
                                    class="likeimg"> No</label>
                        </div>
                    </li>
                    <li></li> -->
                    <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Did Your Trip Live Up To Your Expectations? :</label>
                          <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="trip_expectations" value="1" {{ old("trip_expectations",$trip_expectations) =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="trip_expectations" value="2" {{ old("trip_expectations",$trip_expectations) =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="trip_expectations" value="3" {{ old("trip_expectations",$trip_expectations) =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="trip_expectations" value="4" {{ old("trip_expectations",$trip_expectations) =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="trip_expectations" value="5" {{ old("trip_expectations",$trip_expectations) =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                       </div>
                     </li>

                    <li class="w-100 mt-35">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Overall Comments</label>
                            <textarea class="form_control" placeholder="" name="overall_comments"
                                id="overall_comments"
                                rows="3"><?php echo old('overall_comments', $overall_comments); ?></textarea>
                        </div>
                    </li>
                </ul>
                <div class="bgform w-100">
                    <div class="row1">
                        <ul>
                            <li>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Your Name :</label>
                                    <input type="text" placeholder="" id="your_name" name="your_name"
                                        value="{{old('your_name', $your_name)}}" class="form_control mt-10">
                                    @include('snippets.front.errors_first', ['param' => 'your_name'])
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Address :</label>
                                    <input type="text" placeholder="" id="address" name="address"
                                        value="{{old('address', $address)}}" class="form_control mt-10">
                                    @include('snippets.front.errors_first', ['param' => 'address'])
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">E-Mail :</label>
                                    <input type="text" placeholder="" id="email" name="email"
                                        value="{{old('email', $email)}}" class="form_control mt-10">
                                    @include('snippets.front.errors_first', ['param' => 'email'])
                                </div>
                            </li>
                        </ul>
                        <div class="form-btn-list">
                            <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                            <a href="{{ route('admin.customer_reviews.index') }}" class="btn_admin2"
                                title="Cancel">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@slot('bottomBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $( ".trip_date" ).datepicker({
            'dateFormat':'dd/mm/yy'
        });
    });
</script>
@endslot
@endcomponent