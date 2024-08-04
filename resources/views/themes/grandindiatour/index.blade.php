@component(config('custom.theme').'.layouts.main')
<?php
   // $websiteSetting = CustomHelper::getSettings(['META_TITLE','META_DESCRIPTION']);
   // $meta_title = $websiteSetting['META_TITLE'];
   // $meta_description = $websiteSetting['META_DESCRIPTION'];
   ?>
@slot('title')
{{ $meta_title ?? 'Home'}}
@endslot    
@slot('meta_description')
{{ $meta_description ?? 'Home'}}
@endslot
@slot('meta_keyword')
{{ $meta_keyword ?? 'Home'}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
<link rel="stylesheet" href="{{ asset(config('custom.assets').'/css/grandindiatourhomepage.css') }}">
<link rel="stylesheet" href="{{ asset(config('custom.assets').'/css/homepage_media.css') }}">
<style>
   .modal{display: none;}
</style>
@endslot
@slot('bodyClass')
home_class
@endslot
<?php
   $websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY','FRONTEND_LOGO_AlT_TEXT']);
   $FRONTEND_LOGO_AlT_TEXT = $websiteSettingsArr['FRONTEND_LOGO_AlT_TEXT']??'';

   
   $storage = Storage::disk('public');
      //prd($banner);
   
   if(!empty($imageBanner) && $imageBanner->type == 1){
      $bannerImages = $imageBanner->Images;
      ?>
<div class="swiper banner_slider">
   <div class="swiper-wrapper">
      <?php
         if(!$bannerImages->isEmpty()){
            foreach ($bannerImages as $image) {
         
               $banner_title = $image->title;
               $banner_sub_title = $image->sub_title;
               $banner_link_text_1 = $image->link_text_1;
               $banner_link_text_2 = $image->link_text_2;
               $banner_link_1 = $image->link_1;
               $banner_link_2 = $image->link_2;
         
               $path = "banners/";
               $bannerSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
         
               if(!empty($image->image_name)){
                  if($storage->exists($path.$image->image_name)){
                     $bannerSrc = asset('/storage/'.$path.$image->image_name);
                  }
               }
               ?>
      <div class="swiper-slide">
         <div class="banner_item" style="background-image: url({{ $bannerSrc }})">
            <div class="container">
               <div class="banner_content">
                  <div class="banner_content_inner">
                     <!-- <h2 class="text-7xl text-white banner_text">Snowleopard Expeditions</h2> -->
                     @if($banner_title)
                     <div class="lg_text text-7xl pb-5">{!! $banner_title !!}</div>
                     @endif
                     @if($banner_sub_title)
                     <div class="lg_sm heading8 pt-5 pb-9">{!! $banner_sub_title !!}</div>
                     @endif
                     <ul class="banner_list">
                        @if($banner_link_1)
                        <li>
                           <a class="rounded-full p-3" href="{{ $banner_link_1 }}">
                              {{ $banner_link_text_1 }}
                              <!-- <span>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                   <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                                 </svg>
                                 </span> -->
                           </a>
                        </li>
                        @endif
                        @if($banner_link_2)
                        <li>
                           <a href="{{ $banner_link_2 }}">
                              {{ $banner_link_text_2 }} 
                              <!-- <span>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                    <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                                 </svg>
                                 </span> -->
                           </a>
                        </li>
                        @endif
                     </ul>
                     <!-- <div class="slider-card-box">
                        <img class="show-video-modal" data-toggle="modal" data-target="#show-video-modal" src="{{ asset(config('custom.assets').'/images/video-play-icon.png') }}" alt="">
                        <h4>Lifelong memories just few seconds away</h4>
                        <p><a href="#">Plan your trip</a></p>
                        
                        Modal
                        <div class="modal fade" id="show-video-modal" role="dialog" style="position: fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1050;outline: 0;transform: translate(-50%, -50%);left: 50%;top: 50%;">
                           <div class="modal-dialog" style="width: 800px;margin: 30px auto;box-shadow: 0 5px 15px rgb(0 0 0 / 50%);">
                           
                              Modal content
                              <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                                 <h4 class="modal-title">Modal Header</h4>
                              </div>
                              <div class="modal-body">
                                 <p><iframe width="100%" height="415" src="https://www.youtube.com/embed/UJb3i1J32pI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></p>
                              </div>
                              </div>
                              
                           </div>
                        </div>
                        
                        </div> -->
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php }} ?>
   </div>
</div>
<?php }
   if(!empty($videoBanner) && $videoBanner->type == 2){
      if($videoBanner->video_type == 2 && !empty($videoBanner->video_embed)){
         preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoBanner->video_embed, $match);
         $youtube_id = $match[1];
      //prd($youtube_id);
   
         preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs);
         $vimeo_id = $regs[3];
         ?>
<div class="video_background">
   <div class="video_foreground">
      <?php if(!empty($youtube_id)){ ?>
      <!-- Youtube -->
      <iframe src="https://www.youtube.com/embed/{{ $youtube_id }}?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&mute=1" frameborder="0" allowfullscreen></iframe> 
      <!-- Youtube -->
      <?php } ?>
      <?php if(!empty($vimeo_id)){ ?>
      <!-- Vimeo -->
      <iframe src="https://player.vimeo.com/video/{{ $vimeo_id }}?background=1" frameborder="0" allowfullscreen></iframe>
      <?php } ?>
   </div>
</div>
<?php }else{
   $video_path = "banners/video/";
   $bannerVideoSrc = "";
   
   if(!empty($videoBanner->video)){
      if($storage->exists($video_path.$videoBanner->video)){
         $bannerVideoSrc = asset('/storage/'.$video_path.$videoBanner->video);
      }
      ?>
<div class="video_slider">
   <video autoplay muted loop>
      <source src="{{ $bannerVideoSrc }}" type="video/mp4">
   </video>
</div>
<?php }}} ?>

   

    <!-- Nationals destinationSECTION START -->
      <?php // 'is_international' => 0 ?>
<div class="section_area">
      {!! destinationSection(['section_title' => 'Top India Destinations', 'is_international' => 0]) !!}

      {!! packageSection(['section_title' => 'Group Tour Packages','tour_type'=> 'group','featured'=>'1']) !!}

<section class="home_featured categories pb-5">
 <div class="container">
      <div class="grand_content mt-7">
         <?php
            $widgetDetail = CustomHelper::getWidget('home-magical-memories');
            //prd($widgetDetail);
            if(!empty($widgetDetail)){
               $storage = Storage::disk('public');
               $path = 'widgets/';
               $section_heading = $widgetDetail->section_heading;
               $description = $widgetDetail->description;
               $image1 = $widgetDetail->image1;
            
               $widgetThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
            
              if(!empty($image1)){
                 if($storage->exists($path.$image1)){
                    $widgetThumbSrc = asset('/storage/'.$path.$image1);
                 }
              }
            
               ?>
         <div class="leftimg w-2/4">
            <img class="w-auto" src="{{$widgetThumbSrc }}" alt="">
         </div>
         <div class="rightcontent w-2/4">
            <div class="heading2 pb-8 leading-normal"> {{ $widgetDetail->section_heading }}</div>
            {!! $widgetDetail->description !!}
         </div>
      </div>
      <?php } ?>
   </div>
<!-- FEATURED  SECTION START -->
</section>
</div>
<!-- FEATURED  SECTION END -->

   
   {!! themeCategoriesSection(['section_title' => 'Package by theme','featured' => 1]) !!}

<!-- TOUR CATEGORY SECTION START -->
<?php if(isset($tour_categories) && !$tour_categories->isEmpty()){ ?>
<section class="home_featured categories pb-7">
   <div class="xl:container xl:mx-auto">
   <div class="pb-7 text-center padding_x">
      <div class="heading2 ">Tailor Made Journeys</div>
      <p>Grand India Tour designs itineraries for our clients that others simply cannot, whether around a theme or a private experience. Our team of highly experienced consultants listen, understand and then create a tailor-made journey for you.</p>
   </div>

   <div class="slider_box">
      <div class="swiper category_slider">
         <div class="swiper-wrapper">
            <?php
               foreach ($tour_categories as $theme) {
                  $theme_path = "themes/";
                  $theme_title = CustomHelper::wordsLimit($theme->title);
                  $theme_brief = CustomHelper::wordsLimit($theme->brief);
                  $theme_slug = $theme->slug;
                  $theme_image = $theme->image;
                  $themeSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                  if(!empty($theme_image) && $storage->exists($theme_path.$theme_image)) {
                   $themeSrc = asset('/storage/'.$theme_path.$theme_image);
                  }
                  ?>            
            <div class="swiper-slide">
               <a class="tour_category_box" href="{{ route('tourCategoryDetail',[$theme->slug]) }}" >
                  <div class="images">
                     <img src="{{ $themeSrc }}"  class="theme_radius0" >
                  </div>
                  <div class="featured_content py-4 px-2">
                     <div class="title text-sm"> {{ $theme_title }}</div>
                  </div>
               </a>
            </div>
            <?php } ?>
         </div>
      </div>
      <div class="slider_btns">
         <div class="cat-next theme-next"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
         <div class="cat-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
      </div>
   </div>
   <!-- <div class="view_all_btn text-center p-7"><a href="{{route('tourCategoryListing')}}">View All</a></div> -->
</section>
<?php } ?>
<!-- TOUR CATEGORY SECTION START -->



<!-- TESTIMONIAL SECTION START -->
{!! testimonialSection(['section_title' => 'Testimonials']) !!}
{!! blogSection(['section_title' => 'Travel News and Blogs']) !!}
{!! teamSection(['section_title' => 'Meet Our Team']) !!}
<!-- BRAND SECTION  -->

<!-- CLIENT SECTION START -->
<?php if(isset($teams) && !empty($teams)){?>
<section class="home_client bggray home_featured" style="display:none;">
   <div class="xl:container xl:mx-auto">
   <div class="text_center ">
      <div class="theme_title mb-6">
         <div class="title text-2xl">Meet Our Team</div>
         <!-- <div class="view_all_btn"><a href="{{route('teamListing')}}">View All</a></div> -->
      </div>
      <div class="p_relative">
         <div class="slider_box">
            <div class="swiper client_slider">
               <div class="swiper-wrapper">
                  <?php
                     foreach($teams as $teamManagenent){
                     $team_title = (!empty($teamManagenent->title)) ?($teamManagenent->title): '';
                     $team_sub_title = (!empty($teamManagenent->sub_title)) ?($teamManagenent->sub_title): '';
                     $team_designation = (!empty($teamManagenent->designation)) ?($teamManagenent->designation): '';
                     $team_slug = $teamManagenent->slug;
                     $team_brief_profile = isset($teamManagenent->brief_profile) ? $teamManagenent->brief_profile:'';
                     
                     $storage = Storage::disk('public');
                     $team_path = "teammanagements/";
                     $team_thumb_path = "teammanagements/thumb/";
                     
                     $teamSrc = asset(config('custom.assets').'/images/noimage.jpg');
                     
                     $image = $teamManagenent->image;
                     
                     if(!empty($image) && $storage->exists($team_path.$image))
                     {
                        $teamSrc = asset('/storage/'.$team_path.$image);
                     }
                     
                     ?>
                  <div class="swiper-slide">
                     <!-- <a href="{{ route('teamDetail',['slug'=>$team_slug]) }}" class="team_management_box"></a> -->
                     <div class="images">
                        <img src="{{ $teamSrc }}" alt="{{ $team_title }}" />
                     </div>
                     <div class="team_info">
                        <div class="text py-4 px-2">
                           <span class="title"> {{ $team_title }}</span>
                           <div class="designation para_md1 text-xs">{{ $team_designation }}</div>
                           <div class="designation para_md1 font-bold">{{ $team_sub_title }}</div>
                        </div>
                        <!-- <div class="designation para_md1">{{ $team_sub_title }}</div> -->
                     </div>
                  </div>
                  <!-- <div class="swiper-slide">
                     <div class="client_logo">
                        <img src="{{ asset(config('custom.assets').'/images/client-logo2.jpg') }}" alt="">
                     </div>
                     </div>
                     <div class="swiper-slide">
                     <div class="client_logo">
                        <img src="{{ asset(config('custom.assets').'/images/client-logo4.jpg') }}" alt="">
                     </div>
                     </div>
                     <div class="swiper-slide">
                     <div class="client_logo">
                        <img src="{{ asset(config('custom.assets').'/images/client-logo5.jpg') }}" alt="">
                     </div>
                     </div>
                     <div class="swiper-slide">
                     <div class="client_logo">
                        <img src="{{ asset(config('custom.assets').'/images/client-logo6.jpg') }}" alt="">
                     </div>
                     </div>
                     
                     <div class="swiper-slide">
                     <div class="client_logo">
                      <img src="{{ asset(config('custom.assets').'/images/client-logo7.jpg') }}" alt="">
                     </div>
                     </div> -->
                  <?php }?>
               </div>
            </div>
         </div>
         <div class="slider_btns">
            <div class="client-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
            <div class="client-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
         </div>
      </div>
   </div>
</section>
<?php }?>
<!-- CLIENT SECTION START -->
@slot('bottomBlock')
<script>
   var bannerSlider = new Swiper(".banner_slider", {
      parallax: true,
      effect: 'fade',
      speed:1000,
      autoplay: {
       delay: 1000,
    },
    loop: true,
    pagination: {
       el: ".banner-pagination",
       clickable: true
    },
   });
   
   
   var epSlider = new Swiper(".category_slider", {
   // slidesPerView: 5.3,
   slidesPerView: 4,
   spaceBetween:20,
   loop: true,
   speed:1000,
   navigation: {
      nextEl: ".cat-prev",
      prevEl: ".cat-next",
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
         slidesPerView: 4,
      },
   },
   watchSlidesVisibility: true
   });
   
   
   var customerSlider = new Swiper(".testimonial_slider", {
      loop: true,
      speed:1000,
      slidesPerView: 1,
      //spaceBetween:45,
      // pagination: {
      //   el: ".swiper-pagination",
      //   clickable:true,
      // },
      navigation: {
         nextEl: ".testi-prev",
         prevEl: ".testi-next",
   },
      breakpoints: {
         0: {
            slidesPerView: 1,
         },
         640: {
            slidesPerView: 1,
         },
         768: {
            slidesPerView: 1,
         },
         1024: {
            slidesPerView: 1,
         },
      },
      navigation: {
       nextEl: ".testimonial-next",
       prevEl: ".testimonial-prev",
    },
   });
   
   var customerSlider = new Swiper(".blog_slider", {
      loop: true,
      freeMode: true,
      // centeredSlides: true,
      // parallax:true,
      speed:1000,
      //slidesPerView: 2.5,
      spaceBetween:20,
      breakpoints: {
   0: {
      slidesPerView: 1.5,
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
   navigation: {
   nextEl: ".blog-next",
   prevEl: ".blog-prev",
   },
   watchSlidesVisibility: true
   });
   
   $('.featured_slider').each(function(){
   var element = $(this)[0];
   var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-next')[0];
   var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-prev')[0];
   new Swiper(element, {
      slidesPerView: 3,
      spaceBetween:20,
      loop: true,
      speed:1000,
      breakpoints: {
         0: {
            slidesPerView: 1.5,
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
      navigation: {
       nextEl: ".featured-next",
       prevEl: ".featured-prev",
    },
   });
   });
   
   
   
   $('.featured_slider1').each(function(){
   var element = $(this)[0];
   var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-next')[0];
   var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-prev')[0];
   new Swiper(element, {
      slidesPerView: 5,
      spaceBetween:20,
      loop: true,
      speed:1000,
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
            slidesPerView: 5,
         },
      },
      navigation: {
       nextEl: nextButton,
       prevEl: prevButton,
    },
   });
   });
   
   
   
   var featuredSlider =  new Swiper(".hotel_slider", {
      slidesPerView: 5,
      spaceBetween:20,
      loop: true,
      speed:1000,
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
            slidesPerView: 5,
         },
      },
      navigation: {
       nextEl: ".hotel-next",
       prevEl: ".hotel-prev",
    },
   });
   
   //===========  Brand Logo ==========//
   
   $(document).ready(function(){
   if ($(window).width() < 767) {
   // $('.property_slider').removeClass('property_slider')
   $('.brand_slider').removeClass('swiper-wrapper')
   
   }
   
   });
   
   
   /* Swiper Slider Cards Home - Show only on mobile */
   var swiper = Swiper;
    var init = false;
    function swiperMode() {
      let mobile = window.matchMedia("(min-width: 768px)");
   
      if (mobile.matches) {
        if (!init) {
          init = true;
          swiper = new Swiper(".brand_slider", {
            loop:true,
            autoplay: {
        delay: 2500,
      
      },
            slidesPerView: 5,
            spaceBetween: 15,
          
          });
        }
      } else {
        swiper.destroy();
        init = false;
      }
    }
   
    window.addEventListener("load", function () {
      swiperMode();
    });
   
   
   //  var swiper = new Swiper(".brand_slider", {
   //       slidesPerView: 1,
   //       spaceBetween: 10,
   //       pagination: {
   //         el: ".swiper-pagination",
   //         clickable: true,
   //       },
   //       breakpoints: {
   //          0: {
   //             slidesPerView: 2,
   //          },
   //          640: {
   //             slidesPerView: 2,
   //          },
   //         768: {
   //           slidesPerView: 4,
   //           spaceBetween: 40,
   //         },
   //         1024: {
   //           slidesPerView: 5,
   //           spaceBetween: 50,
   //         },
   //       },
   //     });
   
   
   
   //    var featuredSlider =  new Swiper(".featured_slider", {
   //       slidesPerView: 6,
   //       spaceBetween:20,
   //       loop: true,
   //       speed:1000,
   //       breakpoints: {
   //          0: {
   //             slidesPerView: 1,
   //          },
   //          640: {
   //             slidesPerView: 2,
   //          },
   //          768: {
   //             slidesPerView: 3,
   //          },
   //          1024: {
   //             slidesPerView: 4,
   //          },
   //       },
   //       navigation: {
   //        nextEl: ".featured-next",
   //        prevEl: ".featured-prev",
   //     },
   //  });
   
   var clientSlider =  new Swiper(".client_slider", {
      slidesPerView: 4,
      spaceBetween:20,
      loop: true,
      speed:1000,
      breakpoints: {
         0: {
            slidesPerView: 2,
         },
         640: {
            slidesPerView: 2,
         },
         768: {
            slidesPerView: 3,
         },
         1024: {
            slidesPerView: 6,
         },
      },
      navigation: {
       nextEl: ".client-next",
       prevEl: ".client-prev",
    },
   });
   
   $('.parallax-window').parallax();
</script>
<script>
   $('.rating_list').each(function(){
      var ratingValue = parseInt($(this).attr('rating'))
      $(this).children('li').each(function(i){
         if(i < ratingValue){
            $(this).addClass('active');
         }
      })
   });
</script>
<script>
   var swiper = new Swiper(".tesiimg_slider", {
     navigation: {
       nextEl: ".swiper-button-next",
       prevEl: ".swiper-button-prev",
     },
   });
</script>
<script>
   $(".show-video-modal").click(function() {
      $(".modal").show();
   });
   $(".close").click(function() {
      $(".modal").hide();
   });
   
   
</script>
<script src="https://www.google.com/recaptcha/api.js?render={{$websiteSettingsArr['RECAPTCHA_SITE_KEY']??''}}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script type="text/javascript">  
   grecaptcha.ready(function() {
      grecaptcha.execute("{{$websiteSettingsArr['RECAPTCHA_SITE_KEY']??''}}", {action:'validate_captcha'}).then(function(token) {
      // add token value to form
      const element = document.getElementById('g-recaptcha-response');
      if(element) {
       document.getElementById('g-recaptcha-response').value = token;
      }
      });
   });
</script>
@endslot
@endcomponent