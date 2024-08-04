@component(config('custom.theme').'.layouts.main')
<?php
$websiteSetting = CustomHelper::getSettings(['FACEBOOK','TWITTER','INSTAGRAM','LINKEDIN','YOUTUBE','FRONTEND_LOGO_AlT_TEXT']);

$facebook = $websiteSetting['FACEBOOK']??'';
$twitter = $websiteSetting['TWITTER']??''; 
$instagram = $websiteSetting['INSTAGRAM']??'';
$linkedin = $websiteSetting['LINKEDIN']??'';
$youtube = $websiteSetting['YOUTUBE']??'';
$FRONTEND_LOGO_AlT_TEXT = $websiteSetting['FRONTEND_LOGO_AlT_TEXT']??'';
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
   <div class="banner_main">
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
                  <div class="banner_item">
                    <img src="{{ $bannerSrc }}" alt="" class="banner_img">
                 </div>
                 <div class="socialicons_banner" style="display:none;">
                  <ul>
                     <?php if($facebook){ ?>
                        <li class="facebook"><a href="{{$facebook}}" target="_blank" hreflang="en"><i class="fa fa-facebook"></i></a></li>
                     <?php } ?>
                     <?php if($twitter){ ?>
                        <li class="twitter"><a href="{{$twitter}}" rel="nofollow" target="_blank" hreflang="en"><i class="fa fa-twitter"></i></a></li>
                     <?php } ?>
                     <?php if($instagram){ ?>
                        <li class="instagram"> <a href="{{$instagram}}" rel="nofollow" target="_blank" hreflang="en"><i class="fa fa-instagram"></i></a></li>
                     <?php } ?>
                     <?php if($linkedin){ ?>
                        <li class="linkedin"><a href="{{$linkedin}}" rel="nofollow" target="_blank" hreflang="en"><i class="fa fa-linkedin"></i></a></li>
                     <?php } ?>
                     <?php if($youtube){ ?>
                        <li class="youtube"><a href="{{$youtube}}" rel="nofollow" target="_blank" hreflang="en"><i class="fa fa-youtube"></i></a></li>
                     <?php } ?>
                  </ul>
               </div>
            </div>
         <?php }} ?>
      </div>
   </div>
   @include(config('custom.theme').'.includes.search')
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

<!-- features band -->
<section class="features_section">
   <div class="container">
      <ul class="core_featured_list">
         <li>
            <div class="feature_left">
               <img src="{{ asset(config('custom.assets').'/images/feature1.png') }}" alt="">
            </div>
            <div class="feature_right">
               <h3>40+ Destinations</h3>
               <p>Our expert team handpicked all <br>destinations in this site</p>
            </div>
         </li>
         <li>
            <div class="feature_left">
               <img src="{{ asset(config('custom.assets').'/images/feature2.png') }}" alt="">
            </div>
            <div class="feature_right">
               <h3>Best Price Guarantee</h3>
               <p>Price match within 48 hours of <br>order confirmation</p>
            </div>
         </li>
         <li>
            <div class="feature_left">
               <img src="{{ asset(config('custom.assets').'/images/feature3.png') }}" alt="">
            </div>
            <div class="feature_right">
               <h3>Top Notch support</h3>
               <p>We are here to help, before, <br>during, and even after your trip.</p>
            </div>
         </li>
      </ul>
   </div>
</section>
<!-- features band end -->
 

 <!-- nationals destinationSECTION START -->
      <?php // 'is_international' => 0 ?>
      {!! destinationSection(['section_title' => 'Popular Destinations', 'is_international' => 0]) !!}

      <?php /* Popular Packages
       for activity|package  is_activity = 0|1 */?>
      {!!packageSection(['section_title' => 'Popular Tours','is_activity'=>0,'featured'=>'1'])!!}

      
      {!! themeCategoriesSection(['section_title' => 'Package by Theme']) !!}
     
      
      <?php /* Domestic Packages
       for activity  is_international = 0|1  and flag = 4 for tranding package flag*/?>
      {!!packageSection(['section_title' => 'Trending Domestic Packages','is_international'=>0,'flag'=>4,'featured'=>'1'])!!}


<!-- NON FEATURED  SECTION START -->
<?php if(!empty($best_h_packages)){ ?>
   <section class="home_featured pb-0">
      <div class="container-xl">

         <div class="theme_title">
            <h3 class="font30 color_secondary">{{$cat_details2->title ?? ''}}</h3>
            <a class="arrow_btn" href="{{route('tourCategoryDetail',$cat_details2->slug)}}">View All <img src="{{ asset(config('custom.assets').'/images/right_arrow.svg') }}" alt=""></a>
         </div>

         <div class="text_left p_relative" >
            <div class="slider_btns" data-aos="fade-up">
               <div class="featured-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
               <div class="featured-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
            </div>
            <div class="slider_box" data-aos="fade-up">
            <div class="swiper featured_slider1">
               <div class="swiper-wrapper">
                  @include(config('custom.theme').'/includes/package-li-box',['packages'=>$best_h_packages])
                  <?php /*foreach ($nonFeaturedPackages as $package) {
                     $package_title = CustomHelper::wordsLimit($package->title);
                     $package_brief = CustomHelper::wordsLimit($package->brief);
                     $package_slug = $package->slug;
                     $package_duration = $package->package_duration;
                     $package_image = $package->image;
                     $package_path = 'packages/';
                     $packageSrc = asset(config('custom.assets').'/images/noimage.jpg');

                     if(!empty($package_image)){
                        if($storage->exists($package_path.$package_image)){
                           $packageSrc = asset('/storage/'.$package_path.$package_image);
                        }
                     }
                     ?>
                     <div class="swiper-slide">
                        <a class="featured_box" href="{{route('packageDetail',['slug'=>$package_slug])}}">
                           <div class="images">
                              <img src="{{$packageSrc}}" alt="{{$package_title}}">
                           </div>
                           <div class="featured_content py-4 px-2" style="background-image: url({{ asset(config('custom.assets').'/images/featured-bg.jpg') }});">
                              <div class="title text-sm">{{ $package_title }}</div>
                              <!--<div class="duration">{{$package_duration}}</div>
                              <p class="para_md">{{$package_brief}}</p>
                              <div class="view_all">View Detail</div>-->
                           </div>
                        </a>
                     </div>
                  <?php }*/ ?>
               </div>
            </div>
        
     </div>
  </div>
  <!-- <div class="text_center mt45">
      <a href="{{url('tour-packages/india')}}" hreflang="en" class="btn">View All</a>
   </div> -->
</section>
<?php } ?>
<!-- FEATURED  SECTION END -->

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
         <section class="home_about d-none">
            <!--<span class="flag_top ">
               <img src="{{asset(config('custom.assets').'/images/about-flag1.png') }}" alt="yangphel" data-aos="fade-left" data-aos-duration="2000">
            </span>
            <span class="flag_bottom" data-aos="fade-right" data-aos-duration="3000">
               <img src="{{ asset(config('custom.assets').'/images/about-flag2.png') }}" alt="yangphel">
            </span>-->
            <div class="container">
               <div class="top_about">
                  
                  <div class="about_content">
                     <div class="theme_title">
                        <!--<div class="icon">
                           <img src="{{ asset(config('custom.assets').'/images/about-icon.png') }}"  alt="">
                        </div>-->
                        <h3 class="section_heading">{{ $widgetDetail->section_heading }}</h3>
                     </div>
                     {!! $widgetDetail->description !!}
                     <div class="read_more_btn">
                        <a href="{{url('about-us')}}" hreflang="en">Read More</a>
                     </div>
                  </div>
                  <div class="about_images">
                     <div class="images_inner">
                        <div class="play_icon" data-width="100%" data-height="360" data-fancybox href="https://www.youtube.com/watch?v=0VIhza1IyE0">
                        <svg width="147" height="148" viewBox="0 0 147 148" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M73.2 121.909C99.6524 121.909 121.096 100.465 121.096 74.0125C121.096 47.5601 99.6524 26.1162 73.2 26.1162C46.7476 26.1162 25.3037 47.5601 25.3037 74.0125C25.3037 100.465 46.7476 121.909 73.2 121.909Z" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                           <path d="M63.2593 55.0349L92.1778 74.0127L63.2593 92.9905V55.0349Z" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                           </svg>
                        <img src="{{ $widgetSrc1 }}" class="theme_radius about1 img_responsive" alt="about us"></a>
                        <!--<div class="images_content">
                           <p class="para_md">We are the pioneers in handling your travel arrangements in Bhutan. You are assured of the finest service available in the Kingdom with us.</p>
                        </div>-->
                     </div>
                  </div>
               </div>
               <!--<div class="single_about" data-aos="fade-up" data-aos-duration="3000">
                  <div class="images_single">
                     <img src="{{ $widgetSrc2 }}" class="theme_radius img_responsive about2" alt="yangphel">

                     <div class="images_content">
                        <p class="para_md">We are the pioneers in handling your travel arrangements in Bhutan. You are assured of the finest service available in the Kingdom with us.</p>
                     </div>
                  </div>
               </div>-->
            </div>
         </section>
      <?php }?>
      <!-- ABOUT US SECTION END -->

<!-- FEATURED  SECTION START -->
<?php if(!empty($family_h_packages)){ ?>
   <section class="home_featured pb-0">
      <div class="container-xl">

         <div class="theme_title">
            <h3 class="font30 color_secondary">{{$cat_details3->title ?? ''}}</h3>
            <a class="arrow_btn" href="{{route('tourCategoryDetail',$cat_details3->slug)}}">View All <img src="{{ asset(config('custom.assets').'/images/right_arrow.svg') }}" alt=""></a>
         </div>

         <div class="text_left p_relative" >
            <div class="slider_btns" data-aos="fade-up">
               <div class="featured-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
               <div class="featured-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
            </div>
            <div class="slider_box" data-aos="fade-up">
            <div class="swiper featured_slider">
               <div class="swiper-wrapper">
                  @include(config('custom.theme').'/includes/package-li-box',['packages'=>$family_h_packages])
               </div>
            </div>
        
     </div>
  </div>
</section>
<?php } ?>
<!-- FEATURED  SECTION END -->
@if(isset($honeymoon_package_photography) && !empty($honeymoon_package_photography))
<section  class="padding_bottom" style="display: none;">
   <div class="container">
      <div class="row">
         <ul class="twoimg_flex">
            @foreach($honeymoon_package_photography as $hpp_data)
            <?php 
            $imageSrc =  asset(config('custom.assets').'/img/honeymoon_package.jpg');
            $path = 'cms_pages/';
            if(!empty($hpp_data->image)){
               if($storage->exists($path.$hpp_data->image)){
                  $imageSrc = asset('/storage/'.$path.$hpp_data->image);
               }
            } 
            ?>
            <li data-aos="fade-left">
               <a href="{{url($hpp_data->slug)}}" hreflang="en"><img src="{{$imageSrc}}" alt="{{$hpp_data->title}}"> <h3>{{$hpp_data->title}}</h3></a>
            </li>
            @endforeach
         </ul>
      </div>
   </div>
</section>
@endif

@if(isset($DestinationWeddings) && !empty($DestinationWeddings))
<section class="bgred">
   <div class="container">
      <div class="row">
         <div class="dflex">
            <div class="heart_left" data-aos="fade-right">
               {!!$DestinationWeddings->heading!!}
               <div class="read_more_btn">
                  <a href="{{url($DestinationWeddings->slug)}}" hreflang="en">Explore Me More</a>
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

<?php if($seo_tags){ ?>
     <?php
        $index = 1;
        $tTitle = $seo_tags->page_title;
        $tBrief =  CustomHelper::wordsLimit($seo_tags->page_brief,15000,'','',' more');
        $tImage = $seo_tags->image;

        $path = 'seo_tags/';
        $thumbPath = 'seo_tags/thumb/';

        $imgSrc = asset('assets/img/blog_default.png');
        $imgThumbSrc = asset(config('custom.assets').'/images/full_bg.jpg');

        if(!empty($tImage)){
          if($storage->exists($path.$tImage)){
             $imgSrc = asset('/storage/'.$path.$tImage);
          }

          if($storage->exists($thumbPath.$tImage)){
            $imgThumbSrc = asset('/storage/'.$thumbPath.$tImage);
         }
      }
      ?>
     <section class="home_blog home_featured ftr_<?php echo $index; ?>" style="background-image: url({{ $imgThumbSrc }});">
        <div class="container">
           <div class="row">
           <div class="blog_right">
        <div class="slider_box">
           <div>
              <div class="blog_flex">
                 <div class="blog_list">
                    <div class="blog_box" href="{{route('blogsDetail',['slug'=>'test'])}}">
                     
                       <div class="text">
                         <span class="title" data-aos="fade-up"> {{ $tTitle}}</span>
                         <div data-aos="fade-up">{!! $tBrief !!}</div>
                         <a class="btn btn-secondary" data-aos="fade-up" href="{{url('about-us')}}" hreflang="en">
                          Find Out More 
                          <img src="{{ asset(config('custom.assets').'/images/arrow_white.svg') }}" alt="">
                         </a>
                    </div>
                </div>
          </div>
       </div>
  </div>
</div>
        </div>
        </div> 
     </div>
</section>
<?php } ?>


{!! testimonialSection(['section_title' => 'Customer Review']) !!}

{!! blogSection(['section_title' => 'Travel News and Blogs']) !!}


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
            spaceBetween:0,
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
      spaceBetween:15,
      loop: false,
      speed:1000,
      breakpoints: {
         0: {
            slidesPerView: 1.5,
            spaceBetween:0,
         },
         640: {
            slidesPerView: 1.5,
            spaceBetween:0,
         },
         768: {
            slidesPerView: 2,
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
       delay: 1000,
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
         slidesPerView: 1,
         spaceBetween:20,
      },
      640: {
         slidesPerView: 1,
      },
      768: {
         slidesPerView: 2,
      },
      1024: {
         slidesPerView: 4,
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
         slidesPerView: 1,
      },
      640: {
         slidesPerView: 1,
      },
      768: {
         slidesPerView: 2,
      },
      1024: {
         slidesPerView: 4,
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
   watchSlidesVisibility: true
});


   var customerSlider = new Swiper(".testimonial_slider", {
      loop: false,
      speed:1000,
      slidesPerView: 3,
      spaceBetween:40,
      pagination: {
        el: ".swiper-pagination",
        clickable:true,
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
            slidesPerView: 3,
         },
      },
      navigation: {
       nextEl: ".testimonial-next",
       prevEl: ".testimonial-prev",
    },
 });

   var customerSlider = new Swiper(".blog_slider", {
      loop: false,
      
      speed:1000,
      slidesPerView: 4,
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
      slidesPerView:3,
      spaceBetween:20,
      loop: false,
      speed:1000,
      breakpoints: {
         0: {
            slidesPerView: 1.5,
            spaceBetween:0,
         },
         640: {
            slidesPerView: 1.5,
            spaceBetween:0,
         },
         768: {
            slidesPerView: 2,
         },
         1024: {
            slidesPerView: 3,
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
      slidesPerView: 4,
      spaceBetween:20,
      loop: false,
      speed:1000,
      breakpoints: {
         0: {
            slidesPerView: 1.5,
            spaceBetween:0,
         },
         640: {
            slidesPerView: 1.5,
            spaceBetween:0,
         },
         768: {
            slidesPerView: 2,
         },
         1024: {
            slidesPerView: 4,
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
            slidesPerView: 1,
         },
         768: {
            slidesPerView: 2,
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
            slidesPerView: 1,
         },
         640: {
            slidesPerView: 1,
         },
         768: {
            slidesPerView: 2,
         },
         1024: {
            slidesPerView: 6,
         },
      },
      navigation: {
       nextEl: ".client-next",
       prevEl: ".client-prev",
       breakpoints: {
         0: {
            slidesPerView: 1,
         },
         640: {
            slidesPerView: 1,
         },
         768: {
            slidesPerView: 2,
         },
         1024: {
            slidesPerView: 6,
         },
      },
    },
 });


 var destinationSwiper = new Swiper(".destination_swiper", {
      slidesPerView: 5,
      spaceBetween: 15,
      navigation: {
      nextEl: ".destination-prev-national",
      prevEl: ".destination-next-national",
   },
      breakpoints: {
         0: {
            slidesPerView: 2,
         },
         640: {
            slidesPerView: 2.5,
         },
         768: {
            slidesPerView: 3.5,
         },
         1024: {
            slidesPerView: 5,
         },
      },
    });


   
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">  
$(document).ready(function() {
    $('.destination_search').select2();
});

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
