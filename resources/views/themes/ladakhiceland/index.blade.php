@component(config('custom.theme').'.layouts.main')
<?php
$websiteSetting = CustomHelper::getSettings(['FACEBOOK','TWITTER','INSTAGRAM','LINKEDIN','YOUTUBE','FRONTEND_LOGO_AlT_TEXT']);

$facebook = $websiteSetting['FACEBOOK']??'';
$twitter = $websiteSetting['TWITTER']??''; 
$instagram = $websiteSetting['INSTAGRAM']??'';
$linkedin = $websiteSetting['LINKEDIN']??'';
$youtube = $websiteSetting['YOUTUBE']??'';
$FRONTEND_LOGO_AlT_TEXT = $websiteSetting['FRONTEND_LOGO_AlT_TEXT']??'';

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
<style>
   .pack_day_night {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      font-size: 0.8rem;
      color: #dee2e6;
      margin-top: 0.3rem;
   }
.book-space select.form-select{
   border-radius:5rem;
}
</style>
@endslot

@slot('bodyClass')
home_class
@endslot
<?php
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY']);

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
                        @if($banner_title)
                        
                        <div class="lg_text heading">{{ $banner_title }}</div>
                        @endif
                        @if($banner_sub_title)
                        <div class="lg_sm heading_sm1">{{ $banner_sub_title }}</div>
                        @endif
                        <ul class="banner_list">
                           @if($banner_link_1)
                           <li>
                             <a href="{{ $banner_link_1 }}" hreflang="en">
                              {{ $banner_link_text_1 }}
                              <span>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                   <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                                </svg>
                             </span>
                          </a>
                       </li>
                       @endif
                       @if($banner_link_2)
                       <li>
                          <a href="{{ $banner_link_2 }}" hreflang="en">
                           {{ $banner_link_text_2 }} 
                           <span>
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                             </svg>
                          </span>
                       </a>
                    </li>
                    @endif
                 </ul>
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


 <?php /* Domestic Packages
       for activity  is_international = 0|1 */?>
      {!!packageSection(['section_title' => 'Top Tour Packages','is_international'=>0,'featured'=>'1'])!!}


<!-- TOUR CATEGORY SECTION START -->
 {!! themeCategoriesSection(['section_title' => 'Our Popular Tours Type']) !!}
<!-- TOUR CATEGORY SECTION START -->

  <!-- Nationals destinationSECTION START -->
      <?php // 'is_international' => 0 ?>
      {!! destinationSection(['section_title' => 'Top India Destinations', 'is_international' => 0]) !!}
<!-- Nationals destination SECTION END -->


@if(isset($DestinationWeddings) && !empty($DestinationWeddings))
<section class="bgred">
   <div class="container">
      <div class="row">
         <div class="dflex">
            <div class="heart_left" data-aos="fade-right">
               {!!$DestinationWeddings->heading!!}
               <div class="read_more_btn">
                  <a href="{{url($DestinationWeddings->slug)}}" hreflang="en">Explore Me More <i class="fa fa-long-arrow-right"></i></a>
               </div>
            </div>
            <div class="heart_right" data-aos="fade-left">
            <?php 
            $imageSrc =  asset(config('custom.assets').'/img/heart_img.png') ;
            $path = 'cms_pages/';
            if(!empty($DestinationWeddings->image)){
               if($storage->exists($path.$DestinationWeddings->image)){
                  $imageSrc = asset('/storage/'.$path.$DestinationWeddings->image);
               }
            } 
            ?>
               <img src="{{$imageSrc}}" alt="{!! $FRONTEND_LOGO_AlT_TEXT !!}">
            </div>
         </div>
      </div>
   </div>
</section>
@endif


<!-- WHAT'S NEW SECTION START -->
<?php

$widgetDetail = $popularNews;//CustomHelper::getWidget('home-page-widget-3');
     #prd($widgetDetail);
if(!empty($widgetDetail)){
   $storage = Storage::disk('public');
   $path = 'blogs/';
   $section_heading = $widgetDetail->title;
   $description = CustomHelper::wordsLimit($widgetDetail->content,450);
   $image1 = $widgetDetail->image;

#prd( $description);
   $widgetSrc1 = asset(config('custom.assets').'/images/noimage.jpg');

   if(!empty($image1)){
            //prd($image1);
      if($storage->exists($path.$image1)){
         $widgetSrc1 = asset('/storage/'.$path.$image1);
      }
   }

   ?>
   <section class="home_latest home_featured">
      <div class="container">
         <div class="text_center">
            <div class="theme_title">
               <div class="title">What's new</div>
               <div class="icon">
                  <img src="{{ asset(config('custom.assets').'/images/featured-icon.png') }}" alt="">
               </div>
            </div>
         </div>
         <div class="home_latest_wrap">
            <div class="home_latest_left"  data-aos="fade-left" data-aos-duration="1500">
               <img src="{{ $widgetSrc1 }}" class="img_responsive theme_radius" alt="">
            </div>
            <div class="home_latest_right"  data-aos="fade-right" data-aos-duration="2000">
               <div class="text_lg para_lg">{{ $section_heading }}</div>
               <p class="para_lg2">{!! $description !!}</p>
            </div>
         </div>
      </div>
   </section>
<?php } ?>


<!-- ABOUT US SECTION START -->
<?php 
      $widgetDetail = CustomHelper::getWidget('home-page-widget-1');
      //prd($widgetDetail);
      if(!empty($widgetDetail)){
         $storage = Storage::disk('public');
         $path = 'widgets/';
         $section_heading = $widgetDetail->section_heading;
         $description = $widgetDetail->description;
         $image1 = $widgetDetail->image1;
         $image2 = $widgetDetail->image2;

         $widgetSrc1 = asset(config('custom.assets').'/images/noimage.jpg');
         $widgetSrc2 = asset(config('custom.assets').'/images/noimage.jpg');

         if(!empty($image1)){
            if($storage->exists($path.$image1)){
               $widgetSrc1 = asset('/storage/'.$path.$image1);
            }
         }

         if(!empty($image2)){
            if($storage->exists($path.$image2)){
               $widgetSrc2 = asset('/storage/'.$path.$image2);
            }
         }

         ?>
         <section class="home_about">
            <div class="container">
               <div class="top_about">
                  <div class="about_images">
                     <div class="images_inner">
                        <div class="play_icon" data-width="100%" data-height="360" >
                        
                        <img src="{{ $widgetSrc1 }}" class="theme_radius about1 img_responsive" alt="about us"></a>
                        <!--<div class="images_content">
                           <p class="para_md">We are the pioneers in handling your travel arrangements in Bhutan. You are assured of the finest service available in the Kingdom with us.</p>
                        </div>-->
                     </div>
                  </div>
                  </div>

                  <div class="about_content">
                     <div class="theme_title1">
                        <!--<div class="icon">
                           <img src="{{ asset(config('custom.assets').'/images/about-icon.png') }}"  alt="">
                        </div>-->
                        <div class="text-sky-500">{{ $widgetDetail->widget_name }}</div>
                        <h3 class="section_heading">{!! $widgetDetail->section_heading !!}</h3>
                     </div>
                     {!! $widgetDetail->description !!}
                     <div class="view_all_btn ">
                        <a href="{{url('about-us')}}" hreflang="en">Read More <i class="fa fa-long-arrow-right"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      <?php }?>
      <!-- ABOUT US SECTION END -->
      <section class="book-form">
        <div class="container">
          <div class="row">
            <div class="text-center mb-6 padding_x">
              <div class="text-sky-500">Enquire Us</div>
              <div class="section_heading">Book Your Packages</div>
            </div>
            {!! formShortCode(['slug' =>'[home_page_form]','class'=>'left_form']) !!}
          </div>
        </div>
      </section>

 {!! testimonialSection(['section_title' => 'What they are talking about our policy','section_brief' => '']) !!}


@slot('bottomBlock')
<script>
var swiper =  new Swiper(".celebrities_slider", {
      slidesPerView: 6,
      spaceBetween:20,
      loop: false,
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
            slidesPerView: 6.5,
         },
      },
      navigation: {
       nextEl: ".celebrities-next",
       prevEl: ".celebrities-prev",
    },
 });
var swiper =  new Swiper(".domestic_package_slider", {
      slidesPerView: 4,
      spaceBetween:20,
      loop: false,
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
            slidesPerView: 4,
         },
      },
      navigation: {
       nextEl: ".package-next",
       prevEl: ".package-prev",
    },
 });
   var bannerSlider = new Swiper(".banner_slider", {
      parallax: true,
      effect: 'fade',
      speed:1000,
      autoplay: {
       delay: 3000,
    },
    loop: true,
    pagination: {
       el: ".banner-pagination",
       clickable: true
    },
 });
 var epSlider = new Swiper(".video_blog_slider", {
   // slidesPerView: 5.3,
   slidesPerView: 5,
   spaceBetween:20,
   loop: false,
   speed:1000,
   navigation: {
      nextEl: ".video_blog-prev",
      prevEl: ".video_blog-next",
   },
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
         slidesPerView: 5,
      },
   },
   watchSlidesVisibility: true
});
 var epSlider = new Swiper(".destination_slider", {
   // slidesPerView: 5.3,
   slidesPerView: 5,
   spaceBetween:20,
   loop: false,
   speed:1000,
   navigation: {
      nextEl: ".destination-prev",
      prevEl: ".destination-next",
   },
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
         slidesPerView: 5,
      },
   },
   watchSlidesVisibility: true
});
   var epSlider = new Swiper(".category_slider", {
   // slidesPerView: 5.3,
   slidesPerView: 5,
   spaceBetween:20,
   loop: false,
   speed:1000,
   navigation: {
      nextEl: ".cat-prev",
      prevEl: ".cat-next",
   },
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
         slidesPerView: 5,
      },
   },
   watchSlidesVisibility: true
});
   var customerSlider = new Swiper(".testimonial_slider", {
      loop: false,
      speed:1000,
      slidesPerView: 1,
      spaceBetween:40,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
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
      loop: false,
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
      slidesPerView: 4,
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
      slidesPerView:5,
      spaceBetween:20,
      loop: false,
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

$('.featured_slider1').each(function(){
   var element = $(this)[0];
   var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-next')[0];
   var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-prev')[0];
  new Swiper(element, {
      slidesPerView: 5,
      spaceBetween:20,
      loop: false,
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

var swiper = new Swiper(".desti_slider", {
   slidesPerView: 3,
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
            slidesPerView: 3,
         },
      },
      navigation: {
       nextEl: ".desti-next",
       prevEl: ".desti-prev",
    },
   });

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
<script src="https://www.google.com/recaptcha/api.js?render={{$websiteSettingsArr['RECAPTCHA_SITE_KEY']??''}}"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
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
