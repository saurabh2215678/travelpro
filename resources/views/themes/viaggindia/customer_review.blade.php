@component(config('custom.theme').'.layouts.main')

@slot('title')
{{ $meta_title ?? 'Customer Review '}}
@endslot    

@slot('meta_description')
{{ $meta_description ?? 'Customer Review '}}
@endslot

@slot('headerBlock')
@endslot

@slot('bodyClass')
  customer_review_class
@endslot
<?php 
$emailData = ''; 
?>
<style>
/*start Feedback */
#customer-review-frm .form_control { display: block; width: 100%; padding: 0.25rem 0.75rem; font-size: 14px; font-weight: 400; line-height: 1.5; color: #495057; background-color: #ffffff; background-clip: padding-box; border: 1px solid #a1a1a1; border-radius: 0.25rem; }
.form-experience ul { display: flex; flex-wrap: wrap; padding: 10px 10px; }
.form-experience .form-group {margin-bottom: 0;}
.form-experience ul li { width: 33%; padding: 0 10px; margin-top: 5px; list-style: none; }
/* .form-experience .form-group label {
    margin-bottom: 0;
} */
.featured_box .title { line-height: normal; font-weight: 700; font-size:1rem; color:var(--theme-color); z-index: 99; position: relative; text-transform: inherit; display: inline-block; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 1; line-clamp: 1; -webkit-box-orient: vertical; margin-top: 5px; }
.mb-20 {margin-bottom: 20px;}
.form-experience {border: 1px solid #d1d1d1;}
.w-100 {width: 100% !important;}
.w-49 {width: 50% !important;}
.ht-110 {height: 110px !important;}
.bgform {background-color: #fff6ec;}
.brd-top {border-top: 0;padding-top: 0px;}
.likeimg {filter: grayscale(100%); width: 50px;}
.opacity0 {opacity: 0;display: none;}
.dflex {display: flex; align-items: center;height: 100%;}
.dflex img {margin-right: 15px;cursor: pointer;}
input[type='radio']:checked+.likeimg {filter: grayscale(0%);}
.bgform button:hover {cursor: pointer;}
.rating1 { width: auto ; background: transparent; display: flex; justify-content: flex-start; align-items: center; padding: 0px 0; overflow: hidden; }
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
.rating label .icon { float: left; color: transparent; margin-right: 0rem; font-size: 1rem; margin-left: 0.3rem;}
.rating label:last-child .icon {color: #cdcdcd;}
.rating:not(:hover) label input:checked~.icon,
.rating:hover label:hover input~.icon {color: #efb23c;}
.rating label input:focus:not(:checked)~.icon:last-child {color: #000;text-shadow: 0 0 5px #09f;}
.form-btn-list {padding: 0 15px 15px;}
.pb {padding-bottom: 5px;}
.custom-btn {padding: 0.3rem 2rem;font-size:0.8rem;}
.form-experience .form-group label { font-weight: 700; }
#customer-review-frm textarea.form_control::placeholder { color: #495057; font-weight: 700; }
</style>
<section>
         <div class="container">
            <div class="heading mb-3">
               <div class="theme_title justify-center">
                  <h1 class="text-2xl">Rate Your Experience</h1>
               </div>
            </div>
            <div class="form-experience">
              
              @include('snippets.front.flash')
                  {{ Form::open(array('route' => 'customer-review','method' => 'post','id'=>'customer-review-frm','class' => '','autocomplete' => 'off')) }}
                  <ul>
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Trip Name & Duration :</label>
                         <input type="text" placeholder="" name="trip_name_duration" id="trip_name_duration" value="{{old('trip_name_duration')}}" class="form_control">
                         @include('snippets.front.errors_first', ['param' => 'trip_name_duration'])
                       </div>
                     </li>
                     <!-- <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Trip Date :</label>
                         <input type="text" name="trip_date" id="trip_date" value="{{old('trip_date')}}" class="form_control trip_date" placeholder="DD/MM/YYYY">
                         @include('snippets.front.errors_first', ['param' => 'trip_date'])
                       </div>
                     </li> -->
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Local Guide Name :</label>
                         <input type="text" placeholder="" name="local_guide_name" id="local_guide_name" value="{{old('local_guide_name')}}" class="form_control">
                         @include('snippets.front.errors_first', ['param' => 'local_guide_name'])
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                         <label for="exampleInputEmail1">Driver’s Name :</label>
                         <input type="text" placeholder="" name="driver_name" id="driver_name" value="{{old('driver_name')}}" class="form_control">
                         @include('snippets.front.errors_first', ['param' => 'driver_name'])
                       </div>
                     </li>
                     <li class="w-100 flex justify-between  flex-row">
                        <div class="featured_box">
                           <div class="title heading3">Was Your Guide</div>
                        </div>
                        <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Courteous : 
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
                         </label>
                          
                       </div>
                       <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Helpful :
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
                         </label>
                         
                       </div>
                       <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Informative :
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
                         </label>
                         
                       </div>  
                     </li> 
                     <li class="w-100">
                        <div class="form-group">
                         <!-- <label for="exampleInputEmail1">Comments :</label> -->
                         <textarea class="form_control" placeholder="Comments:" id="guide_comments" name="guide_comments" rows="3"><?php echo old('guide_comments'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100">
                        <div class="form-group brd-top">
                         <div class="featured_box">
                           <div class="title heading3">
                           <label class="flex items-center text-base"> Sightseeing Tour :  
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
                           </label>
                           </div>
                         </div> 
                         
                       </div>
                     </li>
                     <li class="w-100">
                        <div class="form-group">
                         <!-- <label for="exampleInputEmail1">Comments :</label> -->
                         <textarea class="form_control" placeholder="Comments:" id="sight_tour_comments" name="sight_tour_comments" rows="3"><?php echo old('sight_tour_comments'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100 w-100 flex justify-between flex-row">
                        <div class="featured_box">
                           <div class="title heading3">Transportation :</div>
                        </div>
                        <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Driver :
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
                         </label>
                         
                       </div>
                       <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Vehicle : 
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
                         </label>
                         
                       </div>  
                     </li> 
                     <li class="w-100">
                        <div class="form-group">
                         <!-- <label for="exampleInputEmail1">Comments :</label> -->
                         <textarea class="form_control" placeholder="Comments:" id="transportation_comment" name="transportation_comment" rows="3"><?php echo old('transportation_comment'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100">
                        <div class="featured_box brd-top">
                           <div class="title heading3">Accommodation :</div>
                        </div>  
                     </li>
                     @for($i=1; $i<=4; $i++)
                     <li class="w-49">
                        <div class="form-group">
                         <label for="exampleInputEmail1">Hotel name {{$i}} :</label>
                         <input type="text" placeholder="" name="hotel_name_{{$i}}" id="hotel_name_{{$i}}" value="{{old('hotel_name_'.$i)}}" class="form_control">
                         @include('snippets.front.errors_first', ['param' => 'hotel_name'])
                       </div>
                       <div class="w-100 flex justify-between flex-row">
                        <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Comfort : 
                          <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="1" {{ old('comfort_'.$i) =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="2" {{ old('comfort_'.$i) =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="3" {{ old('comfort_'.$i) =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="4" {{ old('comfort_'.$i) =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="comfort_{{$i}}" value="5" {{ old('comfort_'.$i) =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                         </label>
                       </div>
                       <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Services : 
                          <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="1" {{ old('services_'.$i) =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="2" {{ old('services_'.$i) =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="3" {{ old('services_'.$i) =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="4" {{ old('services_'.$i) =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="services_{{$i}}" value="5" {{ old('services_'.$i) =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                         </label>
                         
                       </div>
                       <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Food : 
                          <div class="rating1">
                           <div class="rating">
                            <label>
                                <input type="radio" name="food_{{$i}}" value="1" {{ old('food_'.$i) =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="food_{{$i}}" value="2" {{ old('food_'.$i) =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              <label>
                                <input type="radio" name="food_{{$i}}" value="3" {{ old('food_'.$i) =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                              <label>
                                <input type="radio" name="food_{{$i}}" value="4" {{ old('food_'.$i) =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                <input type="radio" name="food_{{$i}}" value="5" {{ old('food_'.$i) =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                         </label>
                       </div>
                     </div>
                     <div class="form-group">
                         <!-- <label for="exampleInputEmail1">Comments :</label> -->
                         <textarea class="form_control" placeholder="Comments:" id="accommodation_comments_{{$i}}" name="accommodation_comments_{{$i}}" rows="3"><?php echo old('accommodation_comments_'.$i); ?></textarea>
                       </div>
                     </li>
                     @endfor
                     <li class="w-100 flex justify-between flex-row">
                        <div class="featured_box brd-top">
                           <div class="title heading3">Trekking :</div>
                        </div>
                        <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Food : 
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
                         </label>
                         
                       </div>
                       <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">Camp Staff : 
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
                         </label>
                         
                       </div>
                       <div class="form-group">
                         <label class="flex items-center"  for="exampleInputEmail1">Pony/Yak : 
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
                         </label>
                       </div>  
                     </li> 
                     
                     <li class="w-100">
                        <div class="form-group">
                         <!-- <label for="exampleInputEmail1">Comments :</label> -->
                         <textarea class="form_control" placeholder="Comments:" id="trekking_comments" name="trekking_comments" rows="3"><?php echo old('trekking_comments'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100 flex justify-between flex-row">
                        <div class="featured_box brd-top">
                           <div class="title heading3">Garbage Disposal :</div>
                        </div>
                        <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">On Tour : 
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
                         </label>
                       </div>
                       <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1">On Trek : 
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
                         </label>
                         
                       </div>  
                     </li> 
                     
                     <li class="w-100">
                        <div class="form-group">
                         <!-- <label for="exampleInputEmail1">Comments :</label> -->
                         <textarea class="form_control" placeholder="Comments:" id="garbage_disposal_coomments" name="garbage_disposal_coomments" rows="3"><?php echo old('garbage_disposal_coomments'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-100">
                        <div class="featured_box brd-top">
                           <div class="title heading3">Outstanding Performance By Any Staff On The Trip :</div>
                        </div>  
                     </li> 
                     <li class="w-49">
                        <div class="form-group">
                         <label for="exampleInputEmail1">Name :</label>
                         <input type="text" placeholder="" id="name" name="name" value="{{old('name')}}" class="form_control">
                       </div>
                     </li>
                     <li class="w-49">
                        <div class="form-group">
                         <label for="exampleInputEmail1">If So Why? :</label>
                         <textarea class="form_control" placeholder="" id="if_so_why" name="if_so_why" rows="1"><?php echo old('if_so_why'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-49">
                        <div class="form-group brd-top">
                         <div class="featured_box">
                           <div class="title heading3">What Was The Highlight Of Trip For You?</div>
                        </div>  
                         <textarea class="form_control" placeholder="" id="highlight_of_trip" name="highlight_of_trip" rows="3"><?php echo old('highlight_of_trip'); ?></textarea>
                       </div>
                     </li>
                     <li class="w-49">
                        <div class="form-group">
                         <div class="featured_box">
                           <div class="title heading3">What Was The Low Point?</div>
                        </div>  
                         <textarea class="form_control" placeholder="" id="low_point" name="low_point" rows="3"><?php echo old('low_point'); ?></textarea>
                       </div>
                     </li>

                     <!-- <li class="w-100">
                        <div class="featured_box">
                           <div class="title heading3">Outstanding Performance By Any Staff On The Trip :</div>
                        </div>  
                     </li>  -->

                     <!-- <li>
                        <div class="form-group">
                         <label class="dflex"><input type="radio" name="staff_on_the_trip"  value="1" {{ old("staff_on_the_trip") =='1' ? 'checked' : '' }} <?php echo ($emailData == '1')?'checked':''; ?> class="opacity0"><img src="{{asset(config('custom.assets').'/images/like.svg')}}" class="likeimg"> Yes</label>
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                         <label class="dflex"><input type="radio" name="staff_on_the_trip" value="0" {{ old("staff_on_the_trip") =='0' ? 'checked' : '' }} <?php echo ( strlen($emailData) > 0 && $emailData == '0')?'checked':''; ?> class="opacity0"><img src="{{asset(config('custom.assets').'/images/dislike.svg')}}" class="likeimg"> No</label>
                       </div>
                     </li>
                     <li></li> -->
                     <li class="w-49">
                        <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1" style=" color: var(--theme-color); ">How likely will you recommend us to your friends and family? : 
                          <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="recommendation" value="1" {{ old("recommendation") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="recommendation" value="2" {{ old("recommendation") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="recommendation" value="3" {{ old("recommendation") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="recommendation" value="4" {{ old("recommendation") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="recommendation" value="5" {{ old("recommendation") =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                         </label>
                          
                       </div>
                     </li>

                     <!-- <li class="w-100">
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
                     <li></li> -->
                     <li class="w-49">
                        <div class="form-group">
                         <label class="flex items-center" for="exampleInputEmail1" style=" color: var(--theme-color); ">Did Your Trip Live Up To Your Expectations? : 
                          <div class="rating1">
                           <div class="rating">
                                <label>
                                  <input type="radio" name="trip_expectations" value="1" {{ old("trip_expectations") =='1' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="trip_expectations" value="2" {{ old("trip_expectations") =='2' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="trip_expectations" value="3" {{ old("trip_expectations") =='3' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>   
                                </label>
                                <label>
                                  <input type="radio" name="trip_expectations" value="4" {{ old("trip_expectations") =='4' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                                <label>
                                  <input type="radio" name="trip_expectations" value="5" {{ old("trip_expectations") =='5' ? 'checked' : '' }} class="str-rat"/>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                  <span class="icon">★</span>
                                </label>
                              </div>
                           </div>
                         </label>
                          
                       </div>
                     </li>
                     <li class="w-100">
                        <div class="form-group">
                         <label for="exampleInputEmail1">Overall Comments</label>
                         <textarea class="form_control" placeholder="" name="overall_comments" id="overall_comments" rows="3"><?php echo old('overall_comments'); ?></textarea>
                       </div>
                     </li>
                     <li>
                        <div class="form-group">
                        <label for="exampleInputEmail1">Your Name :</label>
                        <input type="text" placeholder="" id="your_name" name="your_name" value="{{old('your_name')}}" class="form_control">
                        @include('snippets.front.errors_first', ['param' => 'your_name'])
                      </div>
                     </li>
                    <li>
                        <div class="form-group">
                        <label for="exampleInputEmail1">Address :</label>
                        <input type="text" placeholder="" id="address" name="address" value="{{old('address')}}" class="form_control">
                        @include('snippets.front.errors_first', ['param' => 'address'])
                      </div>
                    </li>
                    <li>
                        <div class="form-group">
                        <label for="exampleInputEmail1">E-Mail :</label>
                        <input type="text" placeholder="" id="email" name="email" value="{{old('email')}}" class="form_control">
                        @include('snippets.front.errors_first', ['param' => 'email'])
                      </div>
                    </li>
                    <li class="w-100 text-center"> 
                        <button type="submit" class="custom-btn btnSubmit my-3" name="submit">Submit Review</button>
                    </li>
                  </ul>
                  {{ Form::close() }}
            </div>
         </div>
      </section>
@slot('bottomBlock')
<script>
$( function() {
        $( ".trip_date" ).datepicker({
            'dateFormat':'dd/mm/yy'
        });
    });

if ($("#customer-review-frm").length > 0) {
         $("#customer-review-frm").validate({
            submitHandler: function(form) {
               $(".btnSubmit").html(
                     'Please wait...'
                     );
               $(".btnSubmit"). attr("disabled", true);
               form.submit();
            }
         })
   }

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
