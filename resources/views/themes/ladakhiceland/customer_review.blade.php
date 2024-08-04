@component(config('custom.theme').'.layouts.main')

@slot('title')
{{ $meta_title ?? 'Customer Review '}}
@endslot    

@slot('meta_description')
{{ $meta_description ?? 'Customer Review '}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endslot

@slot('bodyClass')
  customer_review_class
@endslot
<?php
$emailData = ''; 
?>
<section>
         <div class="container">
            <div class="heading mb-20">
               <div class="theme_title">
                  <div class="title">
                     Rate Your Experience
                  </div>
               </div>
            </div>
            <div class="form-experience">
              
              @include('snippets.front.flash')
                  {{ Form::open(array('route' => 'customer-review','method' => 'post','id'=>'customer-review-frm','class' => '','autocomplete' => 'off')) }}
                  <ul>
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Trip Name & Duration :</label>
                         <input type="text" placeholder="" name="trip_name_duration" id="trip_name_duration" value="{{old('trip_name_duration')}}" class="form_control mt-10">
                         @include('snippets.front.errors_first', ['param' => 'trip_name_duration'])
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Local Guide Name :</label>
                         <input type="text" placeholder="" name="local_guide_name" id="local_guide_name" value="{{old('local_guide_name')}}" class="form_control mt-10">
                         @include('snippets.front.errors_first', ['param' => 'local_guide_name'])
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Driver’s Name :</label>
                         <input type="text" placeholder="" name="driver_name" id="driver_name" value="{{old('driver_name')}}" class="form_control mt-10">
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
                                  <input type="radio" name="courteous" value="1" {{ old("courteous") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="courteous" value="2" {{ old("courteous") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="courteous" value="3" {{ old("courteous") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="courteous" value="4" {{ old("courteous") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="courteous" value="5" {{ old("courteous") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                                  <input type="radio" name="helpful"  value="1" {{ old("helpful") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="helpful" value="2" {{ old("helpful") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="helpful" value="3" {{ old("helpful") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="helpful" value="4" {{ old("helpful") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="helpful" value="5" {{ old("helpful") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                                <input type="radio" name="informative"  value="1" {{ old("informative") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="informative" value="2" {{ old("informative") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="informative" value="3" {{ old("informative") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="informative" value="4" {{ old("informative") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="informative" value="5" {{ old("informative") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                         <textarea class="form_control mt-10" placeholder=""
                         id="guide_comments" name="guide_comments" rows="3"><?php echo old('guide_comments'); ?></textarea>
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
                                  <input type="radio" name="sightseeing_tour" value="1" {{ old("sightseeing_tour") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="sightseeing_tour" value="2" {{ old("sightseeing_tour") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="sightseeing_tour" value="3" {{ old("sightseeing_tour") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="sightseeing_tour" value="4" {{ old("sightseeing_tour") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="sightseeing_tour" value="5" {{ old("sightseeing_tour") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                         <textarea class="form_control mt-10" placeholder="" id="sight_tour_comments" name="sight_tour_comments" rows="3"><?php echo old('sight_tour_comments'); ?></textarea>
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
                                  <input type="radio" name="driver" value="1" {{ old("driver") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="driver" value="2" {{ old("driver") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="driver" value="3" {{ old("driver") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="driver" value="4" {{ old("driver") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="driver" value="5" {{ old("driver") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                                  <input type="radio" name="vehicle" value="1" {{ old("vehicle") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="vehicle" value="2" {{ old("vehicle") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="vehicle" value="3" {{ old("vehicle") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="vehicle" value="4" {{ old("vehicle") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="vehicle" value="5" {{ old("vehicle") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                         <textarea class="form_control mt-10" placeholder="" id="transportation_comment" name="transportation_comment" rows="3"><?php echo old('transportation_comment'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100 mt-35">
                        <div class="featured_box brd-top">
                           <div class="title heading3">Accommodation :</div>
                        </div>  
                     </li> 
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Comfort :</label>
                         <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="comfort" value="1" {{ old("comfort") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort" value="2" {{ old("comfort") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort" value="3" {{ old("comfort") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="comfort" value="4" {{ old("comfort") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort" value="5" {{ old("comfort") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                                  <input type="radio" name="services" value="1" {{ old("services") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services" value="2" {{ old("services") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services" value="3" {{ old("services") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="services" value="4" {{ old("services") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services" value="5" {{ old("services") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                                <input type="radio" name="food" value="1" {{ old("food") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="food" value="2" {{ old("food") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="food" value="3" {{ old("food") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                              <label>
                                <input type="radio" name="food" value="4" {{ old("food") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                <input type="radio" name="food" value="5" {{ old("food") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                         <textarea class="form_control mt-10" placeholder="" id="accommodation_comments" name="accommodation_comments" rows="3"><?php echo old('accommodation_comments'); ?></textarea>
                       </div>
                     </li>
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
                                <input type="radio" name="foods" value="1" {{ old("foods") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="foods" value="2" {{ old("foods") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="foods" value="3" {{ old("foods") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                              <label>
                                <input type="radio" name="foods" value="4" {{ old("foods") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                <input type="radio" name="foods" value="5" {{ old("foods") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                                <input type="radio" name="camp_staff" value="1" {{ old("camp_staff") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="camp_staff" value="2" {{ old("camp_staff") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="camp_staff" value="3" {{ old("camp_staff") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                              <label>
                                <input type="radio" name="camp_staff" value="4" {{ old("camp_staff") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                <input type="radio" name="camp_staff" value="5" {{ old("camp_staff") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                                <input type="radio" name="pony_yak" value="1" {{ old("pony_yak") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="pony_yak" value="2" {{ old("pony_yak") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="pony_yak" value="3" {{ old("pony_yak") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                              <label>
                                <input type="radio" name="pony_yak" value="4" {{ old("pony_yak") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                <input type="radio" name="pony_yak" value="5" {{ old("pony_yak") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                         <textarea class="form_control mt-10" placeholder="" id="trekking_comments" name="trekking_comments" rows="3"><?php echo old('trekking_comments'); ?></textarea>
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
                                <input type="radio" name="on_tour" value="1" {{ old("on_tour") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="on_tour" value="2" {{ old("on_tour") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="on_tour" value="3" {{ old("on_tour") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                              <label>
                                <input type="radio" name="on_tour" value="4" {{ old("on_tour") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                <input type="radio" name="on_tour" value="5" {{ old("on_tour") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                                <input type="radio" name="on_trek" value="1" {{ old("on_trek") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="on_trek" value="2" {{ old("on_trek") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="on_trek" value="3" {{ old("on_trek") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                              <label>
                                <input type="radio" name="on_trek" value="4" {{ old("on_trek") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                <input type="radio" name="on_trek" value="5" {{ old("on_trek") =='5' ? 'checked' : '' }} class="str-rat"/>
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
                         <textarea class="form_control mt-10" placeholder="" id="garbage_disposal_coomments" name="garbage_disposal_coomments" rows="3"><?php echo old('garbage_disposal_coomments'); ?></textarea>
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
                         <input type="text" placeholder="" id="name" name="name" value="{{old('name')}}" class="form_control mt-10">
                       </div>
                     </li>
                     <li class="w-100 mt-35">
                        <div class="form-group">
                         <label for="exampleInputEmail1">If So Why? :</label>
                         <textarea class="form_control mt-10" placeholder="" id="if_so_why" name="if_so_why" rows="3"><?php echo old('if_so_why'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100 mt-35">
                        <div class="form-group brd-top">
                         <div class="featured_box">
                           <div class="title heading3">What Was The Highlight Of Trip For You?</div>
                        </div>  
                         <textarea class="form_control mt-10" placeholder="" id="highlight_of_trip" name="highlight_of_trip" rows="3"><?php echo old('highlight_of_trip'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100 mt-35">
                        <div class="form-group">
                         <div class="featured_box">
                           <div class="title heading3">What Was The Low Point?</div>
                        </div>  
                         <textarea class="form_control mt-10" placeholder="" id="low_point" name="low_point" rows="3"><?php echo old('low_point'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100 mt-35">
                        <div class="featured_box">
                           <div class="title heading3">Outstanding Performance By Any Staff On The Trip :</div>
                        </div>  
                     </li> 
                     <li>
                        <div class="form-group">
                         <label class="dflex"><input type="radio" name="staff_on_the_trip"  value="1" {{ old("staff_on_the_trip") =='1' ? 'checked' : '' }} <?php echo ($emailData == '1')?'checked':''; ?> class="opacity0"><img src="{{asset(config('custom.assets').'/images/like.svg')}}" class="likeimg"> Yes</label>
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                         <label class="dflex"><input type="radio" name="staff_on_the_trip" value="0" {{ old("staff_on_the_trip") =='0' ? 'checked' : '' }} <?php echo ( strlen($emailData) > 0 && $emailData == '0')?'checked':''; ?> class="opacity0"><img src="{{asset(config('custom.assets').'/images/dislike.svg')}}" class="likeimg"> No</label>
                       </div>
                     </li>
                     <li></li>
                     <li class="w-100 mt-35">
                        <div class="featured_box">
                           <div class="title heading3">Did Your Trip Live Up To Your Expectations?</div>
                        </div>  
                     </li> 
                     <li>
                        <div class="form-group">
                       <label class="dflex"><input type="radio" name="trip_expectations" value="1" {{ old("trip_expectations") =='1' ? 'checked' : '' }} <?php echo ($emailData == '1')?'checked':''; ?> class="opacity0"><img src="{{asset(config('custom.assets').'/images/like.svg')}}" class="likeimg"> Yes</label>
                       </div>
                     </li>
                     <li>
                    <div class="form-group">
                    <label class="dflex"><input type="radio" name="trip_expectations" value="0" {{ old("trip_expectations") =='0' ? 'checked' : '' }} <?php echo ( strlen($emailData) > 0 && $emailData == '0')?'checked':''; ?> class="opacity0"><img src="{{asset(config('custom.assets').'/images/dislike.svg')}}" class="likeimg"> No</label>
                       </div>
                     </li>
                     <li></li>
                     <li class="w-100 mt-35">
                        <div class="form-group">
                         <label for="exampleInputEmail1">Overall Comments</label>
                         <textarea class="form_control mt-10" placeholder="" name="overall_comments" id="overall_comments" rows="3"><?php echo old('overall_comments'); ?></textarea>
                       </div>
                     </li>
                     </ul>
                     <div class="bgform w-100"> 
                        <div class="row">
                           <ul>
                              <li>
                                 <div class="form-group">
                                  <label for="exampleInputEmail1">Your Name :</label>
                                  <input type="text" placeholder="" id="your_name" name="your_name" value="{{old('your_name')}}" class="form_control mt-10">
                                 @include('snippets.front.errors_first', ['param' => 'your_name'])
                                </div>
                              </li>
                              <li>
                                 <div class="form-group">
                                  <label for="exampleInputEmail1">Address :</label>
                                  <input type="text" placeholder="" id="address" name="address" value="{{old('address')}}" class="form_control mt-10">
                                 @include('snippets.front.errors_first', ['param' => 'address'])
                                </div>
                              </li>
                              <li>
                                 <div class="form-group">
                                  <label for="exampleInputEmail1">E-Mail :</label>
                                  <input type="text" placeholder="" id="email" name="email" value="{{old('email')}}" class="form_control mt-10">
                                 @include('snippets.front.errors_first', ['param' => 'email'])
                                </div>
                              </li>
                              <li>
                                 <button type="submit" class="btn mt-35" name="submit">Submit Review</button>
                              </li>
                           </ul>
                        </div>
                     </div>
                  {{ Form::close() }}
            </div>
         </div>
      </section>
@slot('bottomBlock')
<script>
$('.grid_gallery li:nth-child(1) .gallery_wrapper').appendTo('.left_area');
$('.grid_gallery li:nth-child(2) .gallery_wrapper, .grid_gallery li:nth-child(3) .gallery_wrapper, .grid_gallery li:nth-child(4) .gallery_wrapper, .grid_gallery li:nth-child(5) .gallery_wrapper').appendTo('.right_area');
$('.grid_gallery li:nth-child(n+6) .gallery_images').appendTo('.bottom_area');

 var tripSlider =  new Swiper(".trip_slider", {
       slidesPerView: 2,
       spaceBetween:80,
       loop: true,
       speed:1000,
       navigation: {
     nextEl: ".trip-prev",
     prevEl: ".trip-next",
   },
  });
  var customerSlider = new Swiper(".recommendation_slider", {
   loop: true,
   speed:1000,
   slidesPerView: 3,
   spaceBetween:30,
   navigation: {
     nextEl: ".recommendation-prev",
     prevEl: ".recommendation-next",
   },
   breakpoints: {
        0: {
        slidesPerView: 1,
        },
        640: {
        slidesPerView: 2,
        },
        768: {
           slidesPerView: 3,
        },
        1024: {
           slidesPerView: 3,
        },
        },
  
 });
 $('.parallax-window').parallax();
</script>
<script>
         // $(document).ready(function () {
         //   $(".rating i").click(function() {
         //       $(this).prev().prev().prev().prev().prev().addClass('active');
         //       $(this).prev().prev().prev().prev().addClass('active');
         //       $(this).prev().prev().prev().addClass('active');
         //       $(this).prev().prev().addClass('active');
         //       $(this).prev().addClass('active');
         //       $(this).addClass('active');
         //       $(this).next().removeClass('active');
         //       $(this).next().next().removeClass('active');
         //       $(this).next().next().next().removeClass('active');
         //       $(this).next().next().next().next().removeClass('active');
         //   });
         // });


         $('.str-rat').change(function() {
           console.log('New star rating: ' + this.value);
         });
</script>
@endslot

@endcomponent
