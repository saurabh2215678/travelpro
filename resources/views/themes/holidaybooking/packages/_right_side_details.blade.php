<link href="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/core/main.css" rel='stylesheet' />
<link href="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/daygrid/main.css" rel='stylesheet' />
<link href="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/list/main.css" rel='stylesheet' />
<?php
if(isset($package) && !empty($package) && !empty($package->id)) {
   $package_tour_type = $package->tour_type??'';
   if($package->is_activity==0){
      if(isset($package_tour_type) && $package_tour_type == 'group'){
         $package_tour_type_text = "Group Package";
      }elseif (isset($package_tour_type) && ($package_tour_type == 'fixed' || $package_tour_type == 'open')) {
         $package_tour_type_text = "Flexi Package";
      }
   }else{
      $package_tour_type_text = "Activity Package";
   }

   $package_id = $package->id;
   $package_slug = $package->slug;
   $package_inclusions = $package->package_inclusions??[];
   if($package_inclusions) {
      $package_inclusions = json_decode($package_inclusions);
      // $package_inclusions = CustomHelper::showPackageInclusions($package_inclusions);
      $package_inclusions = CustomHelper::showPackageInclusionsOther($package_inclusions);
   }

   $package_price_id = (isset($package_price_id))?$package_price_id:'';
   $sumpPriceVal = 0;
   $original_price = 0;
   $packagePrices = $package->packagePrices??[];

   $packagePriceCategory = $package->packagePriceCategory??[];
   $priceCategoryElements = $packagePriceCategory->priceCategoryElements??[];

   $bookingPrice = 0;
   $totalPrice = 0;
   if(!empty($package_price_id) && isset($packageSelectedPrice) && isset($packageSelectedPrice->id)) {
      $bookingPrice = (int)$packageSelectedPrice->booking_price??0;
      $startingPrice = CustomHelper::getPackagePriceFrom($package_id,$package_price_id);
      $totalPrice = CustomHelper::getPackagePriceTotal($package_id,$package_price_id);
   }

   $agent_price = 0;
   $discount_amount = 0;
   $is_agent = (auth()->user()) ? auth()->user()->is_agent : '';
   if($is_agent == 1){
      $module_type_id = $package->discount_category_id ?? '';
      $group_id = (auth()->user()) ? auth()->user()->group_id : '';
      if($totalPrice > 0){
         $discount_amount = CustomHelper::getDiscountPrice('package_listing',$module_type_id,$totalPrice,$group_id);
      }
      $agent_price = $totalPrice - $discount_amount;
   }

   $packagePrices = $package->packagePrices;
   $priceInfoListing = [];
   if(!empty($packagePrices)) {
      foreach ($packagePrices as $pp) {
         $priceInfoListing[$pp->id] = array(
            'id' => $pp->id,
            'package_id' => $pp->package_id,
            'title' => $pp->title,
            'discount_type' => $pp->discount_type,
            'discount' => $pp->discount,
            // 'description' => $pp->description,
            'booking_price' => $pp->booking_price,
            'final_amount' => $pp->final_amount,
            'category_choices_record' => json_decode($pp->category_choices_record,true),
            'show_choices_record' => json_decode($pp->show_choices_record,true),
            'is_default' => $pp->is_default
         );
      }
   }
   $gThumbImgSrc = asset(config('custom.assets').'/images/noimage.jpg');

   $storage = Storage::disk('public');
   $package_path = 'packages/';
   $package_images = (!($package->packageImages->isEmpty())) ? $package->packageImages : "";

   $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';
   $actual_link = route($packageDetailName,['slug'=>$package_slug]);

   $startingPrice = CustomHelper::getPackagePriceFrom($package_id,$package_price_id);
   $calender_price = CustomHelper::getPrice($startingPrice);

   $ii = 0;
   $package_inventory = CustomHelper::getPackageInventory($package_id,$package_price_id);
   $full_calender_data_ar = [];
   if(!empty($package_inventory)) {
      foreach ($package_inventory as $key => $pi) {
         if(!empty($pi->trip_date) && strtotime($pi->trip_date) > strtotime(date('Y-m-d')) && $pi->inventory  > 0) {
            $full_calender_data_ar[$ii]['title'] = $calender_price;
            $full_calender_data_ar[$ii]['start'] = $pi->trip_date;
            $ii++;
         }
      }
      $mostRecent = 0;
      $mostRecentDate = [];
      $mostRecent2 = 0;
      $now = time();
      foreach($full_calender_data_ar as $fDate) {
         $curDate = strtotime($fDate['start']);
         if ($curDate > $mostRecent && $curDate > $now) {
            $mostRecent = $curDate;
            $mostRecentDate[] = $fDate['start'];
         }
      }
      if(!empty($mostRecentDate)) {
         $min = min(array_map('strtotime', $mostRecentDate));
      } else {
         $min = $now;
      }
      $mostRecent2 = date('Y-m-d', $min);
      $full_calender_data = json_encode($full_calender_data_ar);
   }
   ?>

   <style>
      /* .form_group.icon_calender input { width: 100%; }
      .form_group.icon_calender { width: fit-content; flex-direction: column; }
      .form_group.icon_calender label { width: 100%; } */

      .fade:not(.show) {opacity: 1;}
      .form_list li { width: calc(50% - 0.35rem)!important; }
      ul.form_list { padding: 0; flex-direction: row-reverse; }
      .form_group.icon_calender.flex { flex-direction: column; }
      .package_type { flex-direction: column; }
      .form_group.icon_calender label, .form_group.icon_calender input { width: 100%; }
      .form_list li .package_type .custom_select { width: 100%; }
      .form_group.icon_calender label { font-weight: 400!important; margin: 0!important; }
      /*.validation_error:empty { display: block; }*/
      .form_group.icon_calender label, .package_type label { font-weight: 400!important; font-size: 0.85rem!important; color: var(--black900)!important; }
      .right_price_box #trip_date_error {font-size: 0.75rem; font-family: 'SF-Pro-Display'; }
      select.form_control option { font-family: 'SF-Pro-Display'; font-size: 0.85rem; } 
      .form_control { font-family: 'SF-Pro-Display'; font-size: 0.85rem; }
      .single_btn .flex { margin: 0; padding: 0; }
      .single_btn ul .traveller_pricing_inner { width: 33.33%; }
      .single_package_details .form_group.icon_calender:after { top: auto!important; bottom: 0.4rem; transform: none; filter: hue-rotate(199deg); }
      .traveller_pricing_inner .custom_select+div { font-family: 'SF-Pro-Display'; font-weight: 500; }
      legend {all:unset; border: none; background: url(<?php echo asset(config('custom.assets').'/img/select-travel.png'); ?>) 5px center no-repeat; margin-bottom: 5px; width: auto; padding: 9px 10px 3px 40px; font-size: 0.9rem; text-transform: uppercase; font-weight: 700; }
      .booking_fields legend{font-size: 0.8rem;}
      .booknow_btn { border-color: var(--black200); border-radius: 4px; }
      .booknow_btn .price_box:first-child, .booknow_btn .price_box:nth-child(2) { width: auto!important; }
      .price_box { width: auto!important; padding: 0!important; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; }
      .price_box .text-lg { margin: 0; font-size: 1rem; font-weight: 600; }
      #myForm .booknow_btn .btn_group input.btn { padding: 0.5rem 1rem; }
      .fancybox-overlay-fixed { z-index: 999999; }
      .fancybox-wrap.fancybox-desktop.fancybox-type-html.fancybox-opened { z-index: 9999999; }
      .fancybox-wrap{max-width: 1170px;}
      .fancybox-inner{max-width: 1140px;}
      ul.btn_group.listing_btn { display: flex; white-space: nowrap; align-items: center; }
   </style>
<form id="myForm">
   <div class="theme_title">
      <div class="title text-3xl	">
         @if($package->is_activity==0)
         <p class="day_night_detail"><strong class="day_night text-base">{{$package->package_duration??''}}</strong></p>
         @endif
         <h1 class="package_heading">{!!$package->title??''!!}</h1>
         
      </div>
      
      <?php
            $websiteSetting = CustomHelper::getSettings(['FACEBOOK_SHARE','TWITTER_SHARE','INSTAGRAM_SHARE','WHATSAPP_SHARE','LINKEDIN','HTML_VALIDATION']);
            $facebook = $websiteSetting['FACEBOOK_SHARE'] ?? '';
            $twitter = $websiteSetting['TWITTER_SHARE']?? '';
            $instagram = $websiteSetting['INSTAGRAM_SHARE']?? '';
            $whatsapp = $websiteSetting['WHATSAPP_SHARE']?? '';
            $linkedin = $websiteSetting['LINKEDIN']?? '';
            $HTML_VALIDATION = $websiteSetting['HTML_VALIDATION']?? '';

            ?>
            <div class="share-section">
               <div class="mx-auto ">
                  <div class="flex items-center">
                     <div class="bg_share">
                        <p class="mb-0 mr-3">Share It On:</p>
                        <div class="footer_bottom_right">
                           <ul class="social_icon">
                              <?php if($facebook) { ?>
                                 <li class="facebook"><a href="<?php echo str_replace('{current_url}', $actual_link, $facebook);?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                              <?php } ?>
                              <?php if($twitter) { ?>
                                 <li class="twitter"><a href="<?php echo str_replace('{current_url}', $actual_link, $twitter);?>" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                              <?php } ?>

                              <?php if($whatsapp) { ?>
                                 <li class="whatsapp"><a href="<?php echo str_replace('{current_url}', $actual_link, $whatsapp);?>" target="_blank"><i class="fa-brands fa-whatsapp"></i></a></li>
                              <?php } ?>
                              <?php if($instagram) { ?>
                                 <li class="instagram"><a href="<?php echo str_replace('{current_url}', $actual_link, $instagram);?>" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                              <?php } ?>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
   </div>
   <div class="day_box">
      <!-- <span class="para_md">India to Bhutan</span> -->
   </div>   
  
   <div class="form_box">
      <div class="city-list-block mb-3">
         <ul>
           <?php /* <li>
               <p class="text-sm">Type Of Trip</p>
               <h4 class="location_name">Road Trip</h4>
            </li> */ ?>
            <li class="full_field ">
               <p class="text-sm"></p>
                  <h4 class="location_name">
                  <span class="package_tour_type_text">{{$package_tour_type_text ?? ''}}</span>
                  @if(!empty($package->days_nights_city))
                  <?php 
                     $days_nights_city = unserialize($package->days_nights_city);
                     if(!empty($days_nights_city)) {
                        $ii = 0;
                        foreach($days_nights_city as $dnc_key => $dnc_value) {
                           $ii++;
                           echo $dnc_key.' ('.$dnc_value.'D)';
                           if($ii < count($days_nights_city) ){ echo ' <i class="fa fa-long-arrow-right"></i> '; }
                        }
                     }
                     ?>
                  @endif
               </h4>
            </li>
            <?php /* <li>
               <p class="text-sm">End City</p>
               <h4 class="location_name">
                  @if(!empty($package->days_nights_city))
                  <?php 
                     $days_nights_city = unserialize($package->days_nights_city);
                     if(!empty($days_nights_city)) {
                        $ii = 0;
                        foreach($days_nights_city as $dnc_key => $dnc_value) {
                           $ii++;
                           echo $dnc_key.' ('.$dnc_value.'D)';
                           if($ii < count($days_nights_city) ){ echo ' <i class="fa fa-long-arrow-right"></i> '; }
                        }
                     }
                     ?>
                  @endif
                  </h4>
            </li> */ ?>
            
         </ul>
         <div id="element" class="btn btn-default show-modal">Download Itinerary</div>
      </div>
      <div class="flex items-center flex-wrap justify_btwn">
         <div class="right_price_box">
            <div class="scac flex items-center mb-2">
            <ul class="form_list w-full float-left flex flex-wrap justify-between">
               <?php
               $form_show = 1;
               $select_departure_date = 0;
               if($package->tour_type != "open") {
                  if(!empty($full_calender_data_ar)) {
                     $select_departure_date = 1 ;
                  }
               }
               ?>
               <?php if($package_tour_type != 'open' && Customhelper::isOnlineBooking('package_listing')){ ?>
               <li class="full_field">
                  <!-- <label>{{($select_departure_date==1)?'Departure date':'Select Trip Date'}}</label> -->
                  @if($select_departure_date==1)
                     <div class="form_group icon_calender flex">
                  @endif
                  <label>{{($select_departure_date==1)?'Departure date':''}}</label>
                  @if($select_departure_date==1)
                     <input type="text" name="trip_date" id="trip_date" value="" class="form_control" autocomplete="off" data-popup-btn="departure-date">
                  </div>
                     @else
                     <!-- <input type="text" name="trip_date" id="trip_date" value="" class="form_control calendar" autocomplete="off"> -->
                     @endif
                     <span id="trip_date_error" class="validation_error"></span>
               </li>
               <?php } ?>

               <li>
                  <?php if($packagePrices && count($packagePrices) > 0){ ?>
                     <div class="package_type flex justify-between">
                     <label>Package Type</label>
                     <div class="custom_select">
                        <select class="form_control" name="package_type">
                           <?php foreach($packagePrices as $price){ ?>
                              <option value="{{$price->id}}" {{($price->id==$package_price_id)?'selected':''}} >{{$price->title}}</option>
                           <?php } ?>
                        </select>
                           </div>
                     </div>
                  
                  <?php } ?>
               </li>
            </ul>
            </div>
            
            <?php if(!empty($packagePrices) && count($packagePrices) > 0){ ?>
            <div class="single_package_day" <?php 
               if($form_show == 1) { echo " "; }else{ echo 'style= "display:none"'; } ?>>
               <fieldset class="scheduler-border booking_fields w-full">
                  <legend>Select Travellers</legend>
                  <div class="single_package_white">
                     @if(auth()->check())
                     <!-- <div class="wishlist_btn">
                        <span id="favid-{{$package_id}}" class="pkg_fav pkg_fav_{{$package_id}} {{(isset($package_fab[$package_id]))?'pkg_fav_clicked liked_btn':'pkg_fav'}}" >
                           <img class="wishlist" src="{{asset(config('custom.assets').'/images/wishlist.png') }}" alt="">
                           <img class="wishlist_active" src="{{asset(config('custom.assets').'/images/wishlist-active.png') }}" alt="">
                        </span>
                        </div> -->
                     @endif
                     <div class="single_btn">
                        <ul class="flex flex-wrap">
                           <?php
                              $show_total_price = false;
                              if(!($priceCategoryElements->isEmpty())) {
                                 $show_choices_record = $priceInfoListing[$package_price_id]['show_choices_record']??[];
                                 foreach ($priceCategoryElements as $element) {
                                    $default = $show_choices_record['pce'.$element->id]['default']??'';
                                    if($default && $default == 'pce'.$element->id.'_null') {
                                       continue;
                                    }
                                    $show_total_price = true;
                              ?>
                           <li class="traveller_pricing_inner">
                              <div class="form_group">
                                 <label class="text-xs">{{ $element->input_label }} </label>
                                 <div class="{{($element->input_type == 'select')?'custom_select':''}}">
                                    <?php
                                       if($element->input_type == "select") {
                                          $choices = json_decode($element->input_choices,true);
                                          if(!empty($choices)) {
                                       ?>
                                    <select class="form_control element_choice" data-element-id="{{$element->id}}" name="pce{{$element->id}}">
                                       <?php for($i=0;$i<count($choices);$i++){ ?>
                                       <option value="{{ $choices[$i] }}">{{ $choices[$i] }}</option>
                                       <?php } ?>
                                    </select>
                                    <?php
                                       }
                                       }
                                       else {
                                       ?>
                                       <input type="text" class="form_control element_choice custominput" data-element-id="{{$element->id}}" name="pce{{$element->id}}" />
                                       <?php } ?>
                                 </div>
                                 <div style="color:#f0562f;font-size:13px;" class="price_select{{$element->id}}">₹0</div>
                              </div>
                           </li>
                           <?php
                              }
                              }
                              ?>
                        </ul>
                     </div>
                  </div>
               </fieldset>
               <div class="note_text text-sm  my-3"><strong>Note* : </strong> More than 6 persons please contact.</div>
         </div>
            <?php } ?>
         <div class="booknow_btn ">

            <?php if(!empty($packagePrices) && count($packagePrices) > 0){ ?>
               <?php if($show_total_price){ ?>
               <div class="price_box">
                  <p class="text-lg font-semibold">Total Price:</p>
                  <?php if($is_agent == 1){ ?>
                     <span class="heading_sm3 old_price" id="final_pay_price"></span>
                     <span class="heading_sm3" id="agent_price"></span>
                  <?php }else{ ?>
                     <span class="heading_sm3" id="final_pay_price"></span>
                  <?php } ?>
               </div>
               <?php } ?>


               <?php if(!empty($bookingPrice) && $bookingPrice>0){ ?>
               <div class="price_box">
                  <input type="hidden" name="booking_price" id="booking_price" value="{{$bookingPrice}}">
                  <!-- <p class="text-lg font-semibold">Booking Price:</p> -->
                  <p class="text-lg font-semibold">Price:</p>
                  <span class="heading_sm3">{{CustomHelper::getPrice($bookingPrice)}}</span>
               </div>
               <?php } ?>

            <?php } ?>
            <ul class="btn_group listing_btn">
               <?php if($packagePrices && count($packagePrices) > 0){ ?>
               <li>
                  <input type="hidden" name="action" value="booking">
                  <input type="hidden" name="package" value="{{$package_id}}">
                  @if($package_tour_type != 'open' && Customhelper::isOnlineBooking('package_listing') && ($show_total_price || !empty($bookingPrice)) && (!empty($full_calender_data_ar))) <input type="submit" class="btn w-full" name="submit" value="Book Online"> @endif
               </li>
            <?php } else{ 
               if($packagePrices && count($packagePrices) <= 0){?>
                  <div class="mr-3" >
                   <div class="req_price" style="color: var(--secondary-color);">Price On Request</div>
                </div>
             <?php } } ?>
               <li class="text-center">
                  <a href="{{route('enquire-this-trip',['package'=>$package_slug,'by'=>'enquire','type'=> $package_price_title ??''])}}" class="btn2">Enquire Now</a>
               </li>
            </ul>
         </div>

         </div>
         <div class="left_price_box">
            <div class="swiper package_detail_img">
               <div class="swiper-wrapper">
                 @if(!empty($package_images) && count($package_images) > 0)
                 @foreach($package_images as $image)
                 <div class="swiper-slide">
                  @if($storage->exists($package_path.$image->name))
                  <a href="{{asset('/storage/'.$package_path.$image->name)}}" data-fancybox="package_banner">
                     <img src="{{asset('/storage/'.$package_path.$image->name)}}" alt="{{$package->title??''}}" />
                  </a>
                  @else
                  <a href="{{$gThumbImgSrc}}" data-fancybox="package_banner">
                     <img src="{{$gThumbImgSrc}}" alt="{{$package->title??''}}" />
                  </a>
                  @endif                            
                 </div>
                 @endforeach 
               @else 
               <div class="swiper-slide"><img src="{{ $gThumbImgSrc }}"></div>                   
               @endif
               </div>
               <div class="swiper-button-next"></div>
               <div class="swiper-button-prev"></div>
            </div>
         </div>
      </div>
   </div>
   
   
   <div class="left_side my-5 icon-wishlist relative flex">
      @if($package_inclusions)
      @php $path = 'inclusion/'; @endphp
      <ul class="inclusions inclusions_list">
         @foreach($package_inclusions as $inclusion_key => $inclusion_val)
         <!-- <li data-text="{{$inclusion_key??''}}"><i class="fa"></i>{{$inclusion_val??''}}</li> -->
         <li data-text="<?php echo $inclusion_val;?>">
            <?php
               $i_image = asset(config('custom.assets').'/images/ico3.png');
               if(!empty($inclusion_key)){
               $i_image = url('storage/'.$path.'thumb/'.$inclusion_key);
            } ?>
            <img src="{{$i_image}}"/>
            <?php echo ucwords($inclusion_val);?>
         </li>
         @endforeach
      </ul>
      <a href="javascript:void(0)" class="more_btn"> more... </a>
      @if(auth()->check())
      <?php $package_fab = Customhelper::userFavourite(); ?>
      <div class="wishlist_btn">
         <span id="favid-{{$package_id}}" class="pkg_fav pkg_fav_{{$package_id}} {{(isset($package_fab[$package_id]))?'pkg_fav_clicked liked_btn':'pkg_fav'}}">
         <img class="wishlist" src="{{asset(config('custom.assets').'/images/wishlist.png') }}" alt="">
         <img class="wishlist_active" src="{{asset(config('custom.assets').'/images/wishlist-active.png') }}" alt="">
         </span>
      </div>
      @endif
      @endif
  


            
   </div>
</form>
<?php } ?>

<div class="modal fade" id="viewFullCalModal" data-popup-modal="departure-date">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" id="hmodel-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <span class="heading">Select the date of travel</span>
        <span class="sub_title_pop">The price in calendar represent the starting price per person.</span>
        
       
      </div>
      <div class="modal-body">
       <div id="full_calender"></div>
      </div>
      
    </div>
  </div>
</div>

<script src="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/core/main.js"></script>
<script src="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/interaction/main.js"></script>
<script src="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/daygrid/main.js"></script>
<script src="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/timegrid/main.js"></script>

<!-- modal script -->
<style>
  [data-popup-modal] {
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    justify-content: center;
    width: 100%;
    height: 100%;
        z-index: 1000;
        /*display: none;*/
       visibility: hidden;
        pointer-events: none;
        padding: 5rem;

}
[data-popup-modal].active{
   display: flex;
   visibility: visible;
}
.backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #0000007d;
    z-index: 999;
    display: none;
}
.backdrop.active{  display: block;}

[data-popup-modal] .modal-dialog {
  max-width: 40rem;
    background-color: #fff;
    margin: auto;
        max-height: calc(100vh - 7rem);
}
[data-popup-modal].active .modal-dialog {
      pointer-events: all;
}
</style>
<script type="text/javascript">

// ======= Share BTN End=======

$(document).ready(function(){
   var totlaItem = $('.inclusions_list li').length;
   var showmoreLimit = 9;

// alert(totlaItem)
   if(totlaItem > showmoreLimit){
      $('.inclusions_list').addClass('inclusions_extra')

   }

   $('.more_btn').text(' + ' + (totlaItem - showmoreLimit)  )

   $('.more_btn').click(function(){
      $('.inclusions_list').toggleClass('inclusions_extra')
      $('.more_btn').addClass('more_btn_show')
   })

});


  $('[data-popup-btn]').click(function(){
    var modalName = $(this).attr('data-popup-btn');
    $(`[data-popup-modal=${modalName}]`).addClass('active');
    if($('body>.backdrop').length < 1){
         $('body').append('<div class="backdrop"></div>') 
    }
    $('.backdrop').addClass('active');
  });

  $(document).click(function(e){
    var element=$(e.target);
    if(element.hasClass('backdrop')){
      $('[data-popup-modal]').removeClass('active');
       $('.backdrop').removeClass('active');
    }
  
  })
  $('.close').click(function(){
 $(this).closest('.modal ').removeClass('active')
 $('.backdrop').removeClass('active')
//  $('.fc-event').css("opacity", "0");
})

$(document).on("click",".show-modal",function() {
   $(".modal").show();
});
$(document).on("click",".close",function() {
   $(".modal").hide();
});
</script>

<?php if($package->tour_type != "open") { ?>
<script type="text/javascript">
  <?php if(!empty($full_calender_data_ar)){ ?>
  //document.addEventListener('DOMContentLoaded', function() {
   $(document).ready(function(){
    var calendarEl = document.getElementById('full_calender');
    var myEvents = [];
    myEvents = <?php echo $full_calender_data; ?>;
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth'
      },
      defaultDate: "<?php echo (!empty($mostRecent2))?$mostRecent2:date('Y-m-d'); ?>",
      firstDay: 1,
      selectable: true,
      selectMirror: true,
      validRange: {
        start: '<?php echo date('Y-m-d'); ?>'
      },
      eventClick: function(info) 
      {
        var title = info.event.title;
        if(title!='') {
          var st = info.event.start;

          var rams = st.toString();
          var dasy = rams.split(" ");
          var mnths = {Jan:"01", Feb:"02", Mar:"03", Apr:"04", May:"05", Jun:"06",Jul:"07", Aug:"08", Sept:"09", Oct:"10", Nov:"11", Dec:"12"};

          var my_month = mnths[dasy[1]];
          var my_day   = dasy[2];
          var my_year  = dasy[3];
          var start_date= my_day+'/'+my_month+'/'+my_year;
          document.getElementById('trip_date').value=start_date;
          calendar.unselect();
          document.getElementById("trip_date_error").innerHTML="";
          var button = document.getElementById("hmodel-close"); 
          button.click();
           $('[data-popup-modal]').removeClass('active');
          $('.backdrop').removeClass('active');
        }

      },
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events: myEvents
    });
    calendar.render();
  });
  <?php } else { ?>
  $('.calendar').datepicker({
    minDate: 0,
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    beforeShowDay:function(date){
       return [false, ''];
    }
  });
  <?php } ?>
</script>
<?php }else{ ?>

<script type="text/javascript">
$('.calendar').datepicker({
  minDate: 0,
  dateFormat: 'dd/mm/yy',
  changeMonth: true,
  changeYear: true
});


</script>

<?php } ?>