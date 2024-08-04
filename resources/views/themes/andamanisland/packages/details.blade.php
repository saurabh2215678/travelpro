@component(config('custom.theme').'.layouts.main')
@slot('title')
   {{ $meta_title ?? ''}}
@endslot
@slot('meta_description')
   {{ $meta_description ?? ''}}
@endslot

@slot('meta_keywords')
   {{ $meta_keywords ?? ''}}
@endslot
 
@slot('headerBlock')
<?php
$packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';
$actual_link = route($packageDetailName,['slug'=>$package->slug]);

$storage = Storage::disk('public');
$package_path = 'packages/';

$package_image = $package->image;
$packageSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
if(!empty($package_image)) {
  if($storage->exists($package_path.$package_image)) {
     $packageSrc = asset('/storage/'.$package_path.$package_image);
  }
}
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"/>
<link rel="stylesheet" href="{{url(''.config('custom.assets').'/css/jquery.fancybox.css')}}">
<script src="{{url(''.config('custom.assets').'/js/jquery.fancybox.js')}}"></script>



<meta property="og:url"content="<?php echo $actual_link?>" />
    <meta property="og:type"content="website" />
    <meta property="og:title" content="{{ $meta_title ?? ''}}"/>
    <meta property="og:description"   content="{{ $meta_description ?? ''}}" />
    <meta property="og:image" content="{{ $packageSrc }}" />
<style type="text/css">
.gallery_popup  {position: relative; z-index: 9;  }
.left_area .gallery_wrapper { height: 100%; }
.grid_gallery_row .gallery_wrapper:hover .gallery_text{z-index: 10;}
.grid_gallery_row .gallery_wrapper:hover:after{z-index: 9;}
.gallery_text, .grid_gallery_row .gallery_wrapper:after{pointer-events: none;}
.main-header .head-search ul.menu_list {bottom: auto;top: -35px;}
.main-header .head-search {padding: 0;}
.main-header .head-search form {display: none;}
.theme_form.allbooking_form, .fancybox-wrap.fancybox-desktop.fancybox-type-html.fancybox-opened {max-width: 60rem; width:100% !important;z-index: 99999;}
.fancybox-inner {width: auto !important;}
.fancybox-skin {border-radius: 0.5rem;}
.fancybox-inner {overflow: hidden !important;}

@media (max-width: 767px){
   .main-header .head-search ul.menu_list {
    top: inherit !important;
    bottom: -1rem !important;
}
.main-header .head-search {
    padding: 25px 15px 25px;
    margin: 0% 2% 0;
    background: transparent;
}
section.inner_banner {
    padding: 0;
}
.main-header {
    padding-bottom: 0%;
}
}

</style>
<style type="text/css">
.faqlist_in .faq_main.active .faq_data {display:block;}
</style>
@endslot
@slot('bodyClass')
testimonials_class
@endslot
<?php
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY']);

   $storage = Storage::disk('public');
   $package_path = 'packages/';
   $package_id = $package->id??'';
   $inclusions = (isset($package->inclusions))?$package->inclusions:'';
   $exclusions = (isset($package->exclusions))?$package->exclusions:'';
   $payment_policy = (isset($package->payment_policy))?$package->payment_policy:'';
   $cancellation_policy = (isset($package->cancellation_policy))?$package->cancellation_policy:'';
   $terms_conditions = (isset($package->terms_conditions))?$package->terms_conditions:'';
   $video_link = (isset($package->video_link))?$package->video_link:'';

   $PackageInfo = $package->PackageInfo??[];
   $PackageFlights = $package->PackageFlights??[];
   $packageCategories = $package->packageCategories??[];
   // prd($PackageFlights->toArray());


   $package_duration = $package->package_duration;


   // $activityLevelArr = config('custom.activity_level_arr');
   // $package_activity_level = $activityLevelArr[$package->activity_level];

   $package_image = $package->image;
   $packageSrc =asset(config('custom.assets').'/images/noimagebig.jpg');
   if(!empty($package_image)) {
      if($storage->exists($package_path.$package_image)) {
         $packageSrc = asset('/storage/'.$package_path.$package_image);
      }
   }

   $package_itenaries = (!($package->packageItenaries->isEmpty())) ? $package->packageItenaries : "";
   // $package_info = (!($package->packageInfo->isEmpty())) ? $package->packageInfo : "";
   $package_experts = (!($package->packageExperts->isEmpty())) ? $package->packageExperts : "";

   $package_images = (!($package->packageImages->isEmpty())) ? $package->packageImages : "";

   // $related_packages = (!empty($package->related_packages)) ? json_decode($package->related_packages,true) : [];

   // $relatedPackagestObj = "";
   // if(!empty($related_packages)){
   //    $relatedPackagestObj = App\Models\Package::whereIn('id',$related_packages)->get();
   // }

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

  $storage = Storage::disk('public');
  $path = 'banners/';
  $b_image =asset(config('custom.assets').'/images/noimage.jpg');
  $banner_image='';
  if($banner_image) {
     $b_image = $banner_image;
  }

  ?>

<section class="inner_banner">
   <div class="inner_banner_main">
      <img src="{{$b_image}}" />
   </div>
   @include(config('custom.theme').'.includes.search')
</section>

<section class="single_package_details py-0">
   <div class="w-full">
   <div class="row">
      <div class="container mx-auto">
         <div class="right_side">
            @include(config('custom.theme').'.packages._right_side_details')
         </div>
         <div id="testmodal" class="modal fade" style="position: fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1050;outline: 0;transform: translate(-50%, -50%);left: 50%;top: 50%;">
          <div class="modal-dialog" style="width: 600px;margin: 30px auto;box-shadow: 0 5px 15px rgb(0 0 0 / 50%);">
            <div class="modal-content">
              <div class="modal-body download-itinerary">
               <div class="modal-header form-floating mb-3 w-full">
                <button type="button" class="close text-2xl font-bold float-right" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-2xl">Enquiry Form</h4>
                <p class="text-sm ">Download itinerary for :<span class="text-teal-500  italic"> {{$package->title??''}}</span></p>
              </div>
              {!!formShortCode(['slug' =>'[download_itinerary]','class'=>'form_list','hidden'=>['package'=>$package_id]])!!}
            </div>
            </div>
          </div>
        </div>
      </div>
   </div>
   <?php

      $discount_type = '';
      $discount_value = '';
     $package_price_id = (isset($package_price_id))?$package_price_id:'';
     $totalPrice = CustomHelper::getPackagePriceTotal($package_id,$package_price_id);
     $agent_price = 0;
     $discount_amount = 0;
       $is_agent = (auth()->user()) ? auth()->user()->is_agent : '';
      if($is_agent == 1){
         $module_type_id = $package->discount_category_id ?? '';
         $group_id = (auth()->user()) ? auth()->user()->group_id : '';
         if($totalPrice > 0){
          $discount_result = CustomHelper::getDiscountData('package_listing',$module_type_id,$totalPrice,$group_id);

          // prd($discount_result);

         $discount_type = $discount_result->discount_type ?? '';
         $discount_value = $discount_result->discount_value ?? '';

            if($discount_type && $discount_type == 'flat'){
               $discount_amount = $discount_value;
            }elseif($discount_type && $discount_type == 'percentage'){
               $discount_amount = ($totalPrice*$discount_value)/100 ;
            }
            if($discount_amount > $totalPrice){
               $discount_amount = 0;
            }
         }
         // $agent_price = $totalPrice - $discount_amount;
      }

?>
   <!--- ======== Package Itinerary  ======== -->
   
   <div class="row">
      <div class="container mx-auto">
         <div class="flex flex-wrap">
            <div class="row-cols-2 w-70 pr-9">
               @if(!empty($packageCategories))
               <div class="package_disc flex items-center mt-5 mb-5">
                  <h2 class="text-base font-semibold">Activities</h2>
                  <div class="list_row_right">
                  <ul class="activ_list">
                     @foreach($packageCategories as $pc)
                     <li class="ml-5 mb-2">{!!$pc->title??""!!}</li>
                     @endforeach
                  </ul>
                  </div>
               </div>
               @endif

               <!--- ======== Description  ======== -->
               @if($package->brief!='')
               <div class="package_disc overview_box py-3.5 px-5">
               <div class="text-2xl font-semibold pb-3">Overview</div>

               <p>{!!$package->brief!!}
                  @if($package->description!='')

                        <div class="para_lg2 text-sm" id="h_dec" style="display: none">{!!$package->description!!}</div>
                        <span id="s_more">Read More</span>
                  @endif
                     </p>

                  <script>
                  $( "#s_more" ).click(function() {
                     //$(this).text() == '... See More+' ? $(this).text('... See Less-') : $(this).text('Read Less');
                    $( "#h_dec" ).toggle();
                    var $this = $(this);
                     if ($this.text() == "Read More") {
                        $this.text("Read less")
                     } else {
                        $this.text("Read More")
                     }
                  });
                  </script>

               </div>
               @endif

               <!--- ======== Flight  ======== -->
               @if(!empty($PackageFlights) && count($PackageFlights) > 0)
               <div class="package_disc shadow-md mt-3 p-4">
               <h2 class="heading2 pt-3">{{count($PackageFlights)}} Flights in the package</h2>
               <p class="para_lg2">
                  <ul class="flight_list">
                  @foreach($PackageFlights as $flight)
                  <li class="mt-0 rowid-{{$flight->id}}">
                     <img src="{{asset('images')}}/flight.gif" alt="{{$flight->airline_name}}" class="logo">{{$flight->airline_name}} | {{$flight->flight_number}} | {{$flight->flight_departure}} <i class="fa fa-long-arrow-right"></i> {{$flight->flight_arrival}}
                  </li>
                  @endforeach
                  </ul>
               </p>
            </div>
            @endif

               <!--- ======== Package Days  ======== -->
               <?php //if(!$faqs->isEmpty()){ ?>
               <?php if(isset($package_itenaries) && !empty($package_itenaries) && $package->is_activity==0){ ?>
               <div class="package_day secpad mt-5">
                  <div class="container-full">
                     <h3 class="text-xl font-bold">Package Itinerary</h3>
                     <div class="faqlist faqlist_in">
                        <ul>
                        <?php foreach ($package_itenaries as $itenary) {

                           $meal_option_arr = $itenary->meal_option ? json_decode($itenary->meal_option):'';

                           $itenaries_path = 'itenaries/';
                           $itenary_day_title = $itenary->day_title;
                           $itenary_title = $itenary->title;
                           $itenary_description = $itenary->description;
                           $itenary_image = $itenary->image;
                           $itenary_location = $itenary->Location->name??'';
                           // $itenaryTags = $itenary->itenaryTags??[];
                           // prd($itenaryTags->toArray());
                           $itenaryTags = explode(',', $itenary->tags);

                           $itenary_inclusions = $itenary->itenary_inclusions??[];
                           $itenary_inclusions_arr = [];
                           if(!empty($itenary_inclusions)) {
                           $itenary_inclusions = json_decode($itenary_inclusions);
                           // $itenary_inclusions_arr = CustomHelper::showPackageInclusions($itenary_inclusions);
                           $itenary_inclusions_arr = CustomHelper::showPackageInclusionsOther($itenary_inclusions);
                           }
                           // prd($itenary_inclusions_arr);

                           $itenarySrc = '';

                           if(!empty($itenary_image)){
                              if($storage->exists($itenaries_path.$itenary_image)){
                                 $itenarySrc = asset('/storage/'.$itenaries_path.$itenary_image);
                              }
                           }

                           $itenaryThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

                           if(!empty($itenary_image)){
                              if($storage->exists($itenaries_path.$itenary_image)){
                                 $itenaryThumbSrc = asset('/storage/'.$itenaries_path.'thumb/'.$itenary_image);
                              }
                           }
                           ?>
                           <li class="faq_main">
                              <div class="ml-14">
                                 <div class="faq_title heading6">{!! $itenary_title !!}</div>

                                 @if(!empty($itenaryTags))
                                 <div class="theme_tags mb-5">
                                   @foreach($itenaryTags as $itag)
                                     @if(!empty(trim($itag)))
                                     <span class="tags">{{$itag??''}}</span>
                                     @endif
                                   @endforeach
                                 </div>
                                 @endif

                                 @if(!empty($meal_option_arr))
                                 <p> Meal :
                                    @foreach($meal_option_arr as $meal_option_key => $meal_option_val)
                                    {{($meal_option_key == 0 ? '':', ')}}{{$meal_option_val}}
                                    @endforeach
                                 </p>

                                 @endif
                                 <div class="day_curcle"><span>{{ $itenary_day_title }}</span></div>
                                 <div class="faq_data">
                                    @if(!empty($itenary_inclusions_arr))
                                    <div class="faq_service heading6 mb-0">
                                       <div class="title2 mb-3 text-base">Services Included</div>
                                       @include(config('custom.theme').'/packages/_itinerary_inclusions',['inclusions'=>$itenary_inclusions_arr])
                                    </div>
                                    @endif

                                    @if(!empty($itenarySrc))
                                    <div class="left-img-itinerary"><img src="{{ $itenarySrc }}" alt="{{$itenary_title}}" /></div>
                                    @endif

                                    @if($itenary_location)
                                    <div class="heading6 font-semibold">{{$itenary_location}}</div>
                                    @endif
                                    <div class="right-content-itinerary">{!! $itenary_description !!}</div>
                                 </div>
                              </div>
                           </li>
                           <?php } ?>
                        </ul>
                     </div>
                  </div>
               </div>
               <?php } ?>
               <!--- ======== Accommodation Hotel ======== -->
               <div id="package_accommodations" class="mt-9">

               </div>
               <!--- ======== Accommodation  Old ======== -->
               <div class="accommodation accordion pt-5">
                  <div class="container1">
                     <h3 class="text-2xl pb-3">Policy</h3>
                     <div class="faqlist">

                        @if(isset($package->inclusions) && !empty($package->inclusions))
                        <ul>
                          <li class="faq_main">
                            <div>
                              <div class="faq_title heading6">Inclusions</div>
                              <div class="faq_data" style="">
                                {!!$package->inclusions!!}
                              </div>
                            </div>
                          </li>
                        </ul>
                        @endif

                        @if(isset($package->exclusions) && !empty($package->exclusions))
                        <ul>
                          <li class="faq_main">
                            <div>
                              <div class="faq_title heading6">Exclusions</div>
                              <div class="faq_data" style="">
                                {!!$package->exclusions!!}
                              </div>
                            </div>
                          </li>
                        </ul>
                        @endif

                        @if(isset($package->payment_policy) && !empty($package->payment_policy))
                        <ul>
                          <li class="faq_main">
                            <div>
                              <div class="faq_title heading6">Payment Policy</div>
                              <div class="faq_data" style="">
                                {!!$package->payment_policy!!}
                              </div>
                            </div>
                          </li>
                        </ul>
                        @endif

                        @if(isset($package->cancellation_policy) && !empty($package->cancellation_policy))
                        <ul>
                          <li class="faq_main">
                            <div>
                              <div class="faq_title heading6">Cancellation Policy</div>
                              <div class="faq_data" style="">
                                {!!$package->cancellation_policy!!}
                              </div>
                            </div>
                          </li>
                        </ul>
                        @endif

                        @if(isset($package->terms_conditions) && !empty($package->terms_conditions))
                        <ul>
                          <li class="faq_main">
                            <div>
                              <div class="faq_title heading6">Terms and Conditions</div>
                              <div class="faq_data" style="">
                                {!!$package->terms_conditions!!}
                              </div>
                            </div>
                          </li>
                        </ul>
                        @endif

                        <?php /*
                        <ul>
                               {!! $inclusions ?? "" !!}
                              {!! $exclusions ?? "" !!}
                              {!! $payment_policy ?? "" !!}
                              {!! $cancellation_policy ?? "" !!}
                              {!! $terms_conditions ?? "" !!}
                        </ul>
                        */ ?>

                        @if(!empty($PackageInfo))
                          @foreach($PackageInfo as $info)
                            @if(!empty($info->title) && !empty($info->description))
                            <ul>
                               <li class="faq_main">
                                  <div>
                                  <div class="faq_title heading6">{{$info->title}}</div>
                                  <div class="faq_data" style="">
                                     {!!$info->description!!}
                                  </div>
                                  </div>
                               </li>
                            </ul>
                            @endif
                          @endforeach
                        @endif

                        
                        <!-- Frequently Asked Questions -->
                        @if(!empty($faq_row))
                            <br><br><div class="theme_title mb-6">
                                <h3 class="text-2xl pb-3">FAQs About {{$package->title}}</h3>
                            </div>
                        <?php $i = 1; ?>
                          @foreach($faq_row as $row)
                            <?php 
                                $qusArr = isset($row->question)?$row->question:''; 
                                $ansArr = isset($row->answer)?$row->answer:'';
                            ?>
                            <ul>
                               <li class="faq_main">
                                  <div>
                                  <div class="faq_title heading6"><strong>Q{{$i}}. </strong>{{$qusArr}}</div>
                                  <div class="faq_data" style="">
                                    {!! $ansArr !!}
                                  </div>
                                  </div>
                               </li>
                            </ul>
                            <?php $i = $i+1; ?>
                          @endforeach
                        @endif
                        <!-- Frequently Asked Questions Closed -->


                        {!! $video_link ?? "" !!}
                     </div>
                  </div>
               </div>
               <?php //} ?>
            </div>
            <div class="row-cols-2 w-30 mt-5">
               <div class="form_box-new single_package_form">
                  <div class="form_box_inner">
                     <h2 class="heading2">Your Preference</h2>
                    @include('snippets.front.flash')
                     {!!formShortCode(['slug' =>'[your_preference]','class'=>'left_form','hidden'=>['package'=>$package_id]])!!}

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

</section>

<!-- TESTIMONIAL SECTION START -->
<?php if(!$testimonials->isEmpty()){ ?>
<section class="home_testimonial package_testi m-5">
 <div class="container">
   <div class="theme-title text-center mb-5">
      <div class="title text-3xl	">Testimonials</div>
   </div>
    <!-- <p class="para_md">Our clients love us! Here's what a few of them have to say about their visit to India .</p> -->
    <div class="slider_box">
       <div class="swiper testimonial_slider">
          <div class="swiper-wrapper">
            <?php foreach($testimonials as $testimonial){
               $tName = $testimonial->name??'';
               $tSlug = $testimonial->slug??'';
               $tLocation = $testimonial->location;
               $tDescription = $testimonial->description;
               $tImage = $testimonial->image;

               $path = 'testimonials/';
               $thumbPath = 'testimonials/thumb/';

               $testimonialSrc = asset(config('custom.assets').'/img/testimonial_default.png');
               $testimonialThumbSrc = asset(config('custom.assets').'/img/default_user.png');

               if(!empty($tImage)){
                  if($storage->exists($path.$tImage)){
                     $testimonialSrc = asset('/storage/'.$path.$tImage);
                  }
                  if($storage->exists($thumbPath.$tImage)){
                     $testimonialThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                  }
               }
            ?>
            <div class="swiper-slide">
               <div class="testimonial_box">
                  <div class="client_info">
                    <a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}">
                     <div class="client_img">
                        <img src="{{ $testimonialThumbSrc }}" alt="{{ $tName }}">
                     </div>
                     <div class="client_name">
                        <div class="name para_lg2 w-full p-3">{{ $tName }}</div>
                        <div class="city">{{ $tLocation }}</div>
                     </div>
                   </a>
                  </div>
                  <p>{!! $tDescription !!}</p>
               </div>
            </div>
            <?php } ?>
          </div>
       </div>


       <div class="slider_btns">
         <div class="testimonial-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/next-sm.png') }}" alt="Next Icon"></div>
         <div class="testimonial-next theme-next"><img src="{{asset(config('custom.assets').'/images/prev-sm.png') }}" alt="Prev Icon"></div>
      </div>



    </div>
 </div>

 <div class="testimonial_all">
    <a href="{{route('testimonialListing')}}" class="btn">View All</a>
 </div>
</section>
<?php } ?>
<!-- TESTIMONIAL SECTION START -->
<!-- TESTIMONIAL SECTION START -->
<?php if(!empty($relatedPackagestObj) && !($relatedPackagestObj)->isEmpty()){ ?>
<section class="accommodation_single mt-3" style="padding:25px 0; background:#f2f2f2;">
   <div class="container">
      <div class="text_center">
      <div class="theme_title mb-5">
         <div class="title text-2xl	">Similar {{$is_activity==1?'Activity':'Packages'}}</div>
      </div>
      </div>
      <div class="slider_box mt40">
         <div class="swiper featured_slider">
            <div class="swiper-wrapper">
              @include(config('custom.theme').'/includes/package-li-box',['packages'=>$relatedPackagestObj])
               <?php /*foreach ($relatedPackagestObj as $rpackages) {
                  $rpackage_title = CustomHelper::wordsLimit($rpackages->title);
                  $rpackage_brief = CustomHelper::wordsLimit($rpackages->brief);
                  $rpackage_slug = $rpackages->slug;
                  $rpackage_duration = $rpackages->package_duration;
                  $rpackage_image = $rpackages->image;
                  $rpackageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

                  if(!empty($rpackage_image)){
                     if($storage->exists($package_path.$rpackage_image)){
                        $rpackageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$rpackage_image);
                     }
                  }
                  ?>
               <div class="swiper-slide">
                  <?php $packageDetailName = ($rpackages->is_activity==1)?'activityDetail':'packageDetail'; ?>
                  <a class="featured_box" href="{{ route($packageDetailName,['slug'=>$rpackage_slug]) }}">
                     <div class="images">
                        <img src="{{$rpackageThumbSrc}}" alt="{{$rpackage_title}}">
                     </div>
                     <div class="featured_content px-1.5 py-3.5" style="background-image: url({{asset(config('custom.assets').'/images/featured-bg.jpg')}});">
                        <div class="title heading3">{{$rpackage_title}}</div>
                        <div class="duration">{{$rpackage_duration}}</div>
                        <p class="para_md">{!! $rpackage_brief !!}</p>
                        <!-- <div class="view_all">View Detail</div> -->
                     </div>
                  </a>
               </div>
               <?php }*/ ?>
            </div>
         </div>
         <div class="slider_btns">
            <div class="featured-prev theme-next"><img src="{{asset(config('custom.assets').'/images/next-sm.png') }}" alt="Next Icon"></div>
            <div class="featured-next theme-prev"><img src="{{asset(config('custom.assets').'/images/prev-sm.png') }}" alt="Prev Icon"></div>
         </div>
      </div>
      <!-- <div class="text_center mt45">
         <a href="#" class="btn">View All</a>
         </div> -->
   </div>
</section>
<?php } ?>
<?php if(!$destinations->isEmpty()){ ?>
<section class="departure_single">
   <div class="container">
   <div class="text_center">
      <div class="theme_title mb-5">
         <div class="title text-2xl	">More Trips Departure</div>
      </div>
      </div>
      <div class="slider_box mt40">
         <div class="swiper departure_slider">
            <div class="swiper-wrapper">
               <?php
                  foreach ($destinations as $destination) {
                     $destination_path = "destinations/";
                     $destination_name = $destination->name;
                     $destination_id = $destination->id;
                     $destination_slug = $destination->slug;
                     $destination_image = $destination->image;

                     $destinationThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

                     if(!empty($destination_image)){
                        if($storage->exists($destination_path.$destination_image)){
                           $destinationThumbSrc = asset('/storage/'.$destination_path.'thumb/'.$destination_image);
                        }
                     }
                  ?>
               <div class="swiper-slide">
                  <div class="more_trips">
                     <div class="images">
                        <a href="{{route('tourPackages',['slug'=>$destination_slug])}}">
                        <img src="{{ $destinationThumbSrc }}" alt="{{$destination_name}}" class="img_responsive theme_radius" />
                        <div class="place_name heading_md1">
                           <span>{{ $destination_name }}</span>
                        </div>
                        </a>
                     </div>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>
         <div class="slider_btns">
            <div class="departure-next theme-next"><img src="{{asset(config('custom.assets').'/images/next-sm.png') }}" alt="Next Icon"></div>
            <div class="departure-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/prev-sm.png') }}" alt="Prev Icon"></div>
         </div>
      </div>
   </div>
</section>
<?php } ?>

@slot('bottomBlock')
<!--- ======== Script Package Itinerary  ======== -->
<script>
  $('.ftitle').click( function(){
      $(this).parent().toggleClass('active');
   });
   $(".faq_title").on("click", function(e){
      e.preventDefault();
      if($(this).hasClass("active")){
         $(this).removeClass("active");
         $(".faq_data").slideUp(300);
      } else {
         $(".faq_title").removeClass("active");
         $(".faq_listing > li").removeClass("active");
         $(".faq_data").slideUp(300);
         $(this).addClass("active");
         $(this).closest("li").toggleClass("active");
         $(this).parent().find(".faq_data").slideDown(300);
      }
   });
   $( ".faqlist_in li:first-child" ).addClass( "active" );
   $( ".faqlist_in li:first-child .faq_title" ).addClass( "active" );

   $(document).ready(function () {
      $(".faq_main").slice(0, 6).show();
      if ($(".faq_main:hidden").length != 0) {
         $("#load").show();
      }
      $("#load").on("click", function (e) {
         e.preventDefault();
         $(".faq_main:hidden").slice(0, 6).slideDown();
         if ($(".faq_main:hidden").length == 0) {
            $("#load").text("No More FAQ's").fadOut("slow");
         }
      });
    packageCalculation();
   })
</script>
<!--- ======== Script Package Itinerary End ======== -->
<script type="text/javascript">
   $(document).ready(function(){
      $(document).on('change',"select[name=package_type]",function() {
         // var serviceLevel = $(this).val();
         // var loadUrl = window.location.pathname+'?service_level='+serviceLevel;
         // window.location.href = loadUrl;
         var price_id = $(this).val();
         var _token = '{{ csrf_token() }}';
         if(price_id) {
            $.ajax({
               type: 'POST',
               url: '{{url("package/".$package_id."/ajaxPriceDetails")}}',
               data: {price_id},
               headers:{'X-CSRF-TOKEN': _token},
               dataType: 'json',
               cache: false,
               success: function(response) {
                  if(response.success) {
                    $('.right_side').html(response.htmlData);
                    setTimeout(function(){
                      var swiper = new Swiper(".package_detail_img", {
                        navigation: {
                          nextEl: ".swiper-button-next",
                          prevEl: ".swiper-button-prev",
                        },
                      });
                    },300);
                    packageCalculation(true);
                  } else if(response.message) {
                     alert(response.message);
                  }
               }
            });
            package_price_accommodations(price_id);
         }
      });
      package_price_accommodations('<?php echo $package_price_id; ?>');
   });

   function package_price_accommodations (price_id) {
    var _token = '{{ csrf_token() }}';
    if(price_id) {
      $.ajax({
       type: 'POST',
       url: '{{url("package/".$package_id."/ajaxPackagePriceAccommodations")}}',
       data: {price_id},
       headers:{'X-CSRF-TOKEN': _token},
       dataType: 'json',
       cache: false,
       success: function(response) {
        if(response.success) {
          $('#package_accommodations').html(response.htmlData);
        } else if(response.message) {
         alert(response.message);
       }
     }
   });
    }
   }
</script>
<script>
   //-----JS for Price Range slider-----
      var featuredSlider =  new Swiper(".featured_slider", {
           slidesPerView: 3,
           spaceBetween:40,
           loop: false,
           speed:1000,
           navigation: {
           prevEl: ".featured-prev",
           nextEl: ".featured-next",
       },
         breakpoints: {
            0: {
               slidesPerView: 1,
               spaceBetween:0,
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

   //  ==========  Testimonial slider  ===========

      var featuredSlider =  new Swiper(".testimonial_slider", {
           slidesPerView: 2,
           spaceBetween:40,
           loop: false,
           speed:1000,
           navigation: {
           prevEl: ".testimonial-prev",
           nextEl: ".testimonial-next",
       },
         breakpoints: {
            0: {
               slidesPerView: 1,
               spaceBetween:0,
            },
            640: {
               slidesPerView: 1,
            },
            768: {
               slidesPerView: 1,
            },
            1024: {
               slidesPerView: 2,
            },
         },
      });
      var featuredSlider =  new Swiper(".departure_slider", {
           slidesPerView: 4,
           spaceBetween:40,
           loop: false,
           speed:1000,
           navigation: {
         prevEl: ".departure-next",
         nextEl: ".departure-prev",
       },
         breakpoints: {
            0: {
               slidesPerView: 1,
               spaceBetween:0,
            },
            640: {
               slidesPerView: 2,
            },
            768: {
               slidesPerView: 3,
            },
            1024: {
               slidesPerView: 4,
            },
         },
      });
</script>
<script>
      var acc = document.getElementsByClassName("accordion");
      var i;
      for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function() {
          this.classList.toggle("active");
          var panel = this.nextElementSibling;
          if (panel.style.maxHeight){
            panel.style.maxHeight = null;
          } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
          }
        }
      }
</script>
<!-- ===========Add Code For Your Preference Form======== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.css" rel="stylesheet" />
 <script src="https://www.google.com/recaptcha/api.js?render={{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}"></script>
<script type="text/javascript">
grecaptcha.ready(function() {
   grecaptcha.execute("{{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}", {action:'validate_captcha'}).then(function(token) {
   // add token value to form
   const element = document.getElementById('g-recaptcha-response');
   if(element) {
    document.getElementById('g-recaptcha-response').value = token;
   }
   });
});
</script>
<script type="text/javascript">
   $('.grid_gallery li:nth-child(1) .gallery_wrapper').appendTo('.left_area');
   $('.grid_gallery li:nth-child(2) .gallery_wrapper, .grid_gallery li:nth-child(3) .gallery_wrapper, .grid_gallery li:nth-child(4) .gallery_wrapper, .grid_gallery li:nth-child(5) .gallery_wrapper').appendTo('.right_area');
   $('.grid_gallery li:nth-child(n+6) .gallery_images').appendTo('.bottom_area');
</script>
<script type="text/javascript">
  $(document).bind("#myForm submit", function(e) {
    e.preventDefault();
    e.stopPropagation();
    var _this1 = $('.right_side').find('#myForm');
    $('.right_side #trip_date_error').html('');
    $('html, body').animate({scrollTop:0}, '300');
    var trip_date = $(".right_side #trip_date").val();
    var z = true;
    var trip_date=$(".right_side #trip_date").val().trim();
     
      if(trip_date=="") {
        $('.right_side #trip_date_error').html('Date is required !');
        z=false;
      }
      if(trip_date!=""){
         if(isValidDate(trip_date)){
            var z = true;
         }
         else{
            $('.right_side #trip_date_error').html('Date format is invalid !');
            z=false;
         }
      }

      if(!z) {
        return false;
      }
    
    var _token = '{{ csrf_token() }}';
    $.ajax({
      type        : "POST",
      url         : "/book_now",
      cache       : false,
      data        : _this1.serializeArray(),
      headers:{'X-CSRF-TOKEN': _token},
      success: function(data) {
        $.fancybox(data);
      },
      error: function(e) {
        var response = e.responseJSON;
        if(response) {
          parseBookingErrorMessages(response, 'myForm', 'Book Now');
        }
      }
    });
    return false;
  });

function parseBookingErrorMessages(response, formID, boxText) {
    if(response.errors) {
      var errors = response.errors;//$.parseJSON(resp.errors_fields);
      var message='';
      $.each(errors, function (key, val) {
        $('#'+formID).find("#" + key + "_error").text(val[0]);
      });
    }
  }

function isValidDate(date) {
    var temp = date.split('/');
    var d = new Date(temp[1] + '/' + temp[0] + '/' + temp[2]);
     return (d && (d.getMonth() + 1) == temp[1] && d.getDate() == Number(temp[0]) && d.getFullYear() == Number(temp[2]));
}

  function packageCalculation(defaultSelected = true) {
    var priceInfoListing = '<?php echo json_encode($priceInfoListing); ?>';
    var selectedService = $('.right_side').find("select[name=package_type]").val();
    priceInfoListing = JSON.parse(priceInfoListing);


    if(typeof priceInfoListing[selectedService] !== 'undefined'){
      var selected_choice_record = priceInfoListing[selectedService].category_choices_record;
      var selected_default_record = priceInfoListing[selectedService].show_choices_record;
      var selected_discount_type = priceInfoListing[selectedService].discount_type;
      var selected_discount = priceInfoListing[selectedService].discount;
      if(defaultSelected == true){
        $.each(selected_default_record, function(index,value){
          var result = value.default.split('_');
          $('.right_side').find('select[name="'+index+'"]').val(result[1]);
        });
      }

      var total_pax = 0;
      var total_final_val = 0;
      var total_original_val = 0;
      $('.right_side').find(".element_choice").each(function( index ) {
        var __this = $(this);
        var elementId = __this.attr('data-element-id');
        var elementName = __this.attr('name');
        var slVal = 0;
        var $selected_price_value = 0;

        if($('.right_side').find('select[name="'+elementName+'"]').length){

         slVal = $('.right_side').find('select[name="'+elementName+'"]').val();
         if(slVal && selected_choice_record[elementName]) {
          $selected_price_value = selected_choice_record[elementName][slVal];
         } else {
          $selected_price_value = 0;
         }
       }else{
          slVal = $('.right_side').find('input[name="'+elementName+'"]').val();
          $selected_price_value = 0;
       }
       if(parseInt(slVal) > 0) {
        total_pax = total_pax + parseInt(slVal);
       }

        if (typeof selected_default_record[elementName] !== 'undefined') {
          var $selected_price_rr = selected_default_record[elementName]['default'];
        } else {
          var $selected_price_rr = '';
        }
        var is_calculate=1;
        if($selected_price_rr==elementName+'_null'){
          __this.closest('.selectvalue').hide();
          is_calculate=0;
        }else{
          __this.closest('.selectvalue').show();
          is_calculate=1;
        }

        if(is_calculate==1) {
          var selected_single_price_value = $selected_price_value;
          $selected_price_value = original_price_val = $selected_price_value * slVal;
          if(selected_discount_type == 2) {
            $('.right_side').find('#discount_type').val('percent');
            $('.right_side').find('#discount').val(selected_discount);
            $selected_price_value = parseInt($selected_price_value) - ((parseInt($selected_price_value) * parseInt(selected_discount))/100);
          }
          total_original_val = parseInt(total_original_val) + parseInt(original_price_val);
          total_final_val = parseInt(total_final_val) + parseInt($selected_price_value);

          if(parseInt(selected_single_price_value) > 0){
            $('.right_side').find('.price_select'+elementId).html(getPrice(selected_single_price_value));
          } else {
             $('.right_side').find('.price_select'+elementId).html('');
          }
          if(original_price_val != $selected_price_value){
            $('.right_side').find('.price_select'+elementId).html('<s>'+original_price_val+'</s> ₹'+$selected_price_value);
          }
        }
      });

      $('.right_side').find('#final_pay_price').html(getPrice(total_final_val));

      var agent_price = 0;
      var discount_amount = 0;

      var discount_type = '{{$discount_type}}';
      var discount_value = '{{$discount_value}}';

      if(discount_type == 'percentage'){
        discount_amount = ((parseInt(total_final_val) * parseInt(discount_value))/100);
      } else {
        discount_amount = discount_value;
      }
      agent_price = parseInt(total_final_val) - parseInt(discount_amount);
      $('.right_side').find('#agent_price').html(getPrice(agent_price));

      var total_booking_price = 0;
      if(total_pax > 0) {
        var booking_price = $('.right_side').find('#booking_price').val();
        if(parseInt(booking_price) > 0) {
          var total_booking_price = total_pax * parseInt(booking_price);
        }
      }
      $('.right_side').find('#booking_price').parent().find('.heading_sm3').html(getPrice(total_booking_price));
    }
  }

   $(document).on('change','#myForm .element_choice',function(){
    packageCalculation(false);
   });

   //  $('.calendar').datepicker({
   //    minDate: 0,
   //    dateFormat: 'dd/mm/yy',
   //    changeMonth: true,
   //    changeYear: true
   //  });

</script>

@endslot
@endcomponent