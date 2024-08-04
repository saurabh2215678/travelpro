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
      // prd($module_type_id);
      $group_id = (auth()->user()) ? auth()->user()->group_id : '';
      if($totalPrice > 0){
        $discount_amount = CustomHelper::getDiscountPrice('activity_listing',$module_type_id,$totalPrice,$group_id);
     }
     // prd($discount_amount);
     $agent_price = $totalPrice - $discount_amount;
     // prd($agent_price);
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
$gThumbImgSrc =asset(config('custom.assets').'/images/noimage.jpg');

$storage = Storage::disk('public');
$package_path = 'packages/';
$package_images = (!($package->packageImages->isEmpty())) ? $package->packageImages : "";

$packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';
$actual_link = route($packageDetailName,['slug'=>$package_slug]);

$startingPrice = CustomHelper::getPackagePriceFrom($package_id,$package_price_id);
$calender_price = CustomHelper::getPrice($startingPrice);

$ii = 0;
$package_inventory = CustomHelper::getPackageInventory($package_id,$package_price_id);
// prd($package_inventory->toArray());
$trip_date = '';
$full_calender_data_ar = [];
if(!empty($package_inventory)) {
   $tripTimeArr = config('custom.tripTimeArr');
   $unique_date = [];
   foreach ($package_inventory as $key => $pi) {
      if($pi->inventory > $pi->booked) {
         if(!in_array($pi->trip_date, $unique_date)) {
            if(empty($trip_date)) {
               $trip_date = CustomHelper::DateFormat($pi->trip_date,'d/m/Y','Y-m-d');
            }
            $unique_date[] = $pi->trip_date;
            $date_title = $calender_price;

            $slot_key = '';
            $slot_title = '';
            /*if($pi->slot_id) {
               $slot_key = CustomHelper::getPackageSlot($pi->package_id,$pi->slot_id);
               if($slot_key) {
                  $slot_title = CustomHelper::getPackageSlotTitle($slot_key);
                  if($slot_title) {
                     // $date_title = $date_title.", (".$slot_title.")";
                     $date_title = $slot_title;
                  }
               }
            }*/
            $full_calender_data_ar[$ii]['title'] = $date_title;
            $full_calender_data_ar[$ii]['start'] = $pi->trip_date;
            $full_calender_data_ar[$ii]['slot_key'] = $slot_key;
            $full_calender_data_ar[$ii]['slot_title'] = $slot_title;
            $ii++;
         }
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
   .modal{display: none;}
</style>
<form id="myForm">
   <div class="theme_title mt-7">
      <h1 class="title text-2xl">
         @if($package->is_activity==0)
         <p class="day_night_detail"><strong class="day_night text-base">{{$package->package_duration??''}}</strong></p>
         @endif
         {{$package->title??''}}

      </h1>
   </div>
   <div class="day_box">
      <!-- <span class="para_md">India to Bhutan</span> -->
   </div>
   <div class="form_box">
      <div class="city-list-block">
         <ul>
            <li class="full_field mb-3">
               <p class="text-sm">
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
               </p>
            </li>
         </ul>
         <div id="element" class="btn btn-default show-modal mt-5">Download Itinerary</div>
      </div>
      <div class="flex items-center flex-wrap justify_btwn">
         
         <div class="right_price_box">
         <div class="par_cost pb-2 text-sm">From <span>{{CustomHelper::getPrice($startingPrice)}}</span> per person</div>
            <div class="scac flex items-center">
               <ul class="form_list w-full float-left pr-7">
                  <?php
                  $form_show = 1;
                  $select_departure_date = 0;
                  if($package->tour_type != "open") {
                     if(!empty($full_calender_data_ar)) {
                        $select_departure_date = 1 ;
                     }
                  }
                  ?>
                  <li class="w-2/4">
                     <?php if($packagePrices && count($packagePrices) > 0){ ?>
                        <div class="package_type">
                           <label class="text-sm">Package Type</label>
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



         <div class="single_package_day" <?php
         if($form_show == 1) { echo " "; }else{ echo 'style= "display:none"'; } ?>>

               <ul class="form_list w-full float-left mt-2">
                  <?php
                  $form_show = 1;
                  $select_departure_date = 0;
                  if($package->tour_type != "open") {
                     if(!empty($full_calender_data_ar)) {
                        $select_departure_date = 1 ;
                     }
                  }
                  ?>
               <?php if($package_tour_type != 'open' && CustomHelper::isOnlineBooking('activity_listing') && !empty($full_calender_data_ar)){ ?>
               <li style="display:block;" class="w-2/4">
                  <div class="form_group icon_calender inline-block">
                     <!-- <label class="text-sm">{{(!empty($full_calender_data_ar))?'Departure date':'Select Trip Date'}}</label> -->
                     <label class="text-sm">{{(!empty($full_calender_data_ar))?'Trip Date':''}}</label>
                     <?php if(!empty($full_calender_data_ar)){ ?>
                        <div class="departure_form">
                        <input type="text" name="trip_date" id="trip_date" value="{{$trip_date}}" class="form_control" autocomplete="off" data-popup-btn="departure-date">
                        </div>
                        <?php }else{ ?>
                        <!-- <input type="text" name="trip_date" id="trip_date" value="" class="form_control calendar" autocomplete="off" data-popup-btn="departure-date"> -->
                        <?php } ?>
                        <input type="hidden" name="trip_slot" id="trip_slot" value="">
                        <label id="trip_slot_title" class="text-xs"></label>
                     <span id="trip_date_error" class="validation_error"></span>
                  </div>
               </li>
               <?php } ?>

               <li class="w-2/4">
                  <label class="text-sm font-bold">Number of Persons</label>
                  <fieldset class="scheduler-border booking_fields">
                     <legend class="select_trav font-normal">Select Travellers</legend>
                     <span id="travellers_error" class="validation_error"></span>
                     <div class="single_package_white">

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
                                          <div style="color:#f0562f" class="price_select{{$element->id}}">₹0</div>
                                       </div>
                                    </li>
                                    <?php
                                 }
                              }
                              ?>
                              <li class="li_last"><label class="text-xs"> </label><a class="apbtn" href="#" id="applyTravellers" >Apply</a></li>
                           </ul>

                        </div>
                     </div>
                  </fieldset>
               </li>
            </ul>
            </div>
      
            <?php if($package_tour_type != 'open' && CustomHelper::isOnlineBooking('activity_listing') && !empty($full_calender_data_ar)){ ?>
            <div class="slot_time">
               <a class="apply_slot" href="#" id="applyTripTravellers">Check Availability</a>
               <div class="time_list mb-2">
                  <label class="pb-0">Please select the slot</label>
                  <ul id="cont">
                  </ul>
               </div>
            </div>
            <?php } ?>

            <?php if(!empty($packagePrices) && count($packagePrices) > 0){ ?>

               <div class="booknow_btn">
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

                     <?php if(!empty($bookingPrice) && $bookingPrice > 0){?>
                        <div class="price_box">
                           <input type="hidden" name="booking_price" id="booking_price" value="{{$bookingPrice}}">
                           <p class="text-lg font-semibold">Booking Price:</p>
                           <span class="heading_sm3">{{CustomHelper::getPrice($bookingPrice)}}</span>
                        </div>
                     <?php } ?>


                  <?php } ?>
                  <ul class="btn_group listing_btn">
                     <?php if($packagePrices && count($packagePrices) > 0){ ?>
                        <li>
                           <input type="hidden" name="action" value="booking">
                           <input type="hidden" name="package" value="{{$package_id}}">
                           @if($package_tour_type != 'open' && CustomHelper::isOnlineBooking('activity_listing') && ($show_total_price || !empty($bookingPrice)) && !empty($full_calender_data_ar)) <input type="submit" class="btn w-full" name="submit" value="Book Online" disabled> @endif
                        </li>
                     <?php } ?>
                     <!-- <li class="text-center">
                        <a href="{{route('enquire-this-trip',['package'=>$package_slug,'type'=> $package_price_title ??''])}}" class="btn2">Enquire Now</a>
                     </li> -->

                     <li class="text-center">
                     </li>
                  </ul>
               </div>
               <div class="note_text text-sm mb-3 mt-2"><strong>Note* : </strong> More than 6 persons please contact.</div>

            <?php } ?>
         </div>
         <div class="left_price_box">
            @if(auth()->check()) 
            <?php $package_fab = Customhelper::userFavourite(); ?>
            <div class="wishlist_btn">
               <span id="favid-{{$package_id}}" class="pkg_fav pkg_fav_{{$package_id}} {{(isset($package_fab[$package_id]))?'pkg_fav_clicked liked_btn':'pkg_fav'}}">
                  <img class="wishlist" src="{{asset(config('custom.assets').'/images/wishlist.png') }}" alt="Wishlist">
                  <img class="wishlist_active" src="{{asset(config('custom.assets').'/images/wishlist-active.png') }}" alt="Wishlist">
               </span>
            </div>
            @endif
            <div class="swiper package_detail_img">
               <div class="swiper-wrapper">
                  @if(!empty($package_images) && count($package_images) > 0)
                  @foreach($package_images as $image)
                  <div class="swiper-slide">
                     @if($storage->exists($package_path.$image->name))
                     <img data-fancybox="gallery" src="{{asset('/storage/'.$package_path.$image->name)}}" alt="{{$image->title??''}}" />
                     @else
                     <img data-fancybox="gallery" src="{{$gThumbImgSrc}}" alt="{{$image->title??''}}" />
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
   <div class="inclusions_share flex flex-wrap">
      <div class="left_side mb-5 pr-8 w-6/12 icon-wishlist relative">
         @if($package_inclusions)
         <?php $i_path = 'inclusion/'; ?>
         <ul class="inclusions inclusions_list">
            @foreach($package_inclusions as $inclusion_key => $inclusion_val)
            <!-- <li data-text="{{$inclusion_key??''}}"><i class="fa"></i>{{$inclusion_val??''}}</li> -->

            <li data-text="<?php echo $inclusion_val;?>">
               <?php
                  $i_image = asset(config('custom.assets').'/images/ico3.png');
                  if(!empty($inclusion_key) && File::exists(public_path('storage/inclusion/'.$inclusion_key))){
                  $i_image = url('storage/'.$i_path.'thumb/'.$inclusion_key);
               } ?>
               <img src="{{$i_image}}"/>
               <?php echo ucwords($inclusion_val);?>
            </li>

            @endforeach
         </ul>
         <a href="javascript:void(0)" class="more_btn"> more... </a>

         @endif
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
      <div class="share-section w-6/12">
         <div class="flex py-0 pt-3 pl-1.5 items-center">
            <div class="bg_share">
                  <p class="mr-3">Share It On:</p>
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
  max-width: 35rem;
  background-color: #fff;
  margin: auto;
  max-height: calc(100vh - 6rem);
}
[data-popup-modal].active .modal-dialog {
      pointer-events: all;
}
</style>

<script type="text/javascript">
/////// Time Slot //////
// $('.apply_slot').click(function(){
//       $(this).siblings(".time_list").toggleClass('active');
// });


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

// ======= Share BTN =======
$( document ).ready(function() {
//custom button for homepage
      $( ".share-btn" ).click(function(e) {
      $('.networks-5').not($(this).next( ".networks-5" )).each(function(){
      $(this).removeClass("active");
      });

      $(this).next( ".networks-5" ).toggleClass( "active" );
      });
      });
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
            console.log('eventClick.info=',info);
            var title = info.event.title;
            if(title!='') {
               var st = info.event.start;
               var slot_key = '';
               var slot_title = '';
               if(info.event.extendedProps) {
                  if(info.event.extendedProps.slot_key) {
                     slot_key = info.event.extendedProps.slot_key;
                     slot_title = info.event.extendedProps.slot_title;
                  }
               }
               var rams = st.toString();
               var dasy = rams.split(" ");
               var mnths = {Jan:"01", Feb:"02", Mar:"03", Apr:"04", May:"05", Jun:"06",Jul:"07", Aug:"08", Sept:"09", Oct:"10", Nov:"11", Dec:"12"};

               var my_month = mnths[dasy[1]];
               var my_day   = dasy[2];
               var my_year  = dasy[3];
               var start_date= my_day+'/'+my_month+'/'+my_year;
               document.getElementById('trip_date').value = start_date;
               document.getElementById('trip_slot').value = slot_key;
               if(slot_title) {
                  document.getElementById('trip_slot_title').innerHTML = 'Trip Slot: '+slot_title;
               } else {
                  document.getElementById('trip_slot_title').innerHTML = '';
               }
               calendar.unselect();
               document.getElementById("trip_date_error").innerHTML="";
               var button = document.getElementById("hmodel-close");
               button.click();
               $('[data-popup-modal]').removeClass('active');
               $('.backdrop').removeClass('active');
               resetTripSlot();
            }
         },
         editable: false,
         eventLimit: true, // allow "more" link when too many events
         events: myEvents
      });
      calendar.render();
   });
   <?php } else { ?>
   $(document).ready(function(){
      $('.calendar').datepicker({
         minDate: 0,
         dateFormat: 'dd/mm/yy',
         changeMonth: true,
         changeYear: true,
         beforeShowDay:function(date){
            return [false, ''];
         }
      });
   });
   <?php } ?>
   $(document).ready(function(){
      setTimeout(function(){
         $('.right_side #applyTripTravellers').trigger('click');
      },300);
   });
</script>
<?php }else{ ?>
<script type="text/javascript">
   $(document).ready(function(){
      $('.calendar').datepicker({
         minDate: 0,
         dateFormat: 'dd/mm/yy',
         changeMonth: true,
         changeYear: true
      });
   });
</script>
<?php } ?>