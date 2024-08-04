@component(config('custom.theme').'.layouts.main')

@slot('title')
  {{ $meta_title ?? 'Testimonial Detail'}}
@endslot  

@slot('meta_description')
      {{ $meta_description ?? 'Testimonial Detail' }}
@endslot

@slot('meta_keyword')
{{ $meta_keyword ?? 'Testimonial Detail' }}
@endslot  

@slot('headerBlock')
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
    team_detail_class inner_page
@endslot

@slot('bottomBlock')

<?php

$path = 'testimonials/';
$thumb_path = 'testimonials/thumb/';
$storage = Storage::disk('public');

$testimonial_title = $testimonialDetails->title??'';
$testimonial_name =  $testimonialDetails->name??'';

$name =$testimonial_title;

$testimonial_description =  $testimonialDetails->description ??'';


$testimonialSrc = asset(config('custom.assets').'/images/noimage.jpg');

$image = $testimonialDetails->image;

if(!empty($image) && $storage->exists($path.$image))
{
   $testimonialSrc = asset('/storage/'.$path.$image);
}
$testimonialUrl = route('testimonialDetails',['slug'=>$testimonialDetails->slug]);
//$teamPackages = !($testimonialDetails->expertPackages->isEmpty()) ? $testimonialDetails->expertPackages : "";
?>

   <section class="inner_banner">
      <div class="inner_banner_main">
         <img src="{{asset(config('custom.assets').'/images/review-banner.jpg')}}" alt="" >
      </div>
   </section>
   <section class="search_home py-0 md:px-0" data-aos="fade-up">
      <div>
         @include(config('custom.theme').'.includes.search')
      </div>
   </section>
<div class="breadcrumb_full">
   <div class="container">
      <ul class="breadcrumb">
         <li><a href="{{url('/')}}">Home</a>
         </li>
         <li><a href="{{route('testimonialListing')}}">{{$breadcrumb_title}}</a>
         </li>
         <li>
            <?php echo $testimonial_title; ?>
         </li>
      </ul>
   </div>
</div>
<section class="team_single_wrap pt-2 testimonials-details" >
   <div class="container">
   <div class="title text-2xl mb-3">{{ $testimonial_title }}</div>
      <div class="flex flex-wrap">
         <div class="team_single_box leftsec">
            
            <div class="swiper tesiimginner_slider ">
            <!-- <div class="left_box swiper-wrapper" data-aos="fade-left" data-aos-duration="2000">
              <div class="single_images swiper-slide"> -->
               <?php  $path = 'testimonials/';
                  $thumbPath = 'testimonials/thumb/';
                  $testimonialThumbSrc = asset(config('custom.assets').'/images/user.png');
                  if(!empty($image)){
                     if($storage->exists($path.$image)){
                        $testimonialSrc = asset('/storage/'.$path.$image);
                     }
                     if($storage->exists($path.$image)){
                        $testimonialThumbSrc = asset('/storage/'.$path.$image);
                     }
                  }
                  $testimonialImages = $testimonialDetails->testimonialImages??[];
                  if(empty($testimonialThumbSrc) && empty($testimonialImages)) {
                     $testimonialThumbSrc = asset(config('custom.assets').'/images/user.png');
                  }
                  ?>
                 
                        <div class="left_box swiper-wrapper" data-aos="fade-left" data-aos-duration="2000">
                          
                           @if(!empty($testimonialImages) && count($testimonialImages) > 0)
                           @foreach($testimonialImages as $image)
                           <div class="single_images swiper-slide"><img src="{{asset('/storage/'.$path.$image->name)}}"></div>
                           @endforeach                           
                           @endif
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    
                  <!-- </div> -->
               
            <!-- </div> -->
            <div class="swiper-button-next"></div>
               <div class="swiper-button-prev"></div>
            </div>
            <div class="right_box my-3" data-aos="fade-right" data-aos-duration="3000">
               <div class="team_info_single pt-3">
                  @if($testimonialThumbSrc)
                  <!-- <div class="user-icon w-6"><img class="pb-3" src="{{ $testimonialThumbSrc }}"></div> -->
                  @endif
                  <div class="text-description pb-3 text-sm">{!! $testimonial_description !!}</div>
                  <div class="designation para_lg1 pb-3 theme-color">
                     <img class="object-cover" src="{{ $testimonialThumbSrc }}"> 
                     {{ $testimonial_name }}
                  </div>
               </div>
               
            </div>
         </div>
         <div class="sidebarsec">
            <div class="title text-xl mb-5">Explore Great Deals </div>
               <div class="">
                  <div class="swiper great_slider">
                     <ul class=" swiper-wrapper">
                        @include(config('custom.theme').'/includes/package-li-box',['packages'=>$explore_package])
                     </ul>
                  </div>
               </div>
               <div class="writereview mt-5"><a href="{{route('testimonialadd')}}" class="btn btn-default "><!--<i class="fa fa-pencil" aria-hidden="true"></i>-->
                  <span>WRITE <strong>YOUR TESTIMONIAL</strong></span></a>
               </div>
      </div>
      </div>
   </div>
</section>

<?php if(!$related_testimonials->isEmpty()){ ?>
<section class="accommodation_single similar-testimonials">
   <div class="container">
      <div class="text_center">
      <div class="theme_title mb-6">
         <div class="title text-2xl">Similar Testimonials</div>
      </div>
      </div>
      <div class="slider_box mt40">
         <div class="swiper featured_slider">
            <div class="swiper-wrapper">
               <?php foreach ($related_testimonials as $rtestimonials) {

                  $rtestimonial_title = CustomHelper::wordsLimit($rtestimonials->name)??'';
                  $rtestimonial_description = CustomHelper::wordsLimit($rtestimonials->description)??'';
                  $rtestimonial_slug = $rtestimonials->slug??'';
                  $rtestimonial_image = $rtestimonials->image??'';
                  $rtestimonialThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                  
                  if(!empty($rtestimonial_image)){
                     if($storage->exists($thumb_path.$rtestimonial_image)){
                        $rtestimonialThumbSrc = asset('/storage/'.$thumb_path.$rtestimonial_image);
                     }
                  }
                  ?>
               <div class="swiper-slide">
                  <a class="featured_box" href="{{ route('testimonialDetails',['slug'=>$rtestimonial_slug]) }}">
                     <div class="images">
                        <img src="{{$rtestimonialThumbSrc}}" alt="">
                     </div>
                     <div class="featured_content px-1.5 py-3.5">
                        <div class="title heading4">{{$rtestimonial_title}}</div>
                        <p class="para_md">{!! $rtestimonial_description !!}</p>
                        <!-- <div class="view_all">View Detail</div> --> 
                     </div>
                  </a>
               </div>
               <?php } ?>
            </div>
         </div>
         <div class="slider_btns">
            <div class="featured-next theme-next"><img src="{{asset(config('custom.assets').'/images/next-sm.png') }}" alt=""></div>
            <div class="featured-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/prev-sm.png') }}" alt=""></div>
         </div>
      </div>
      <!-- <div class="text_center mt45">
         <a href="#" class="btn">View All</a>
         </div> -->
   </div>
</section>
<?php } ?>
@slot('bottomBlock')

<script>
   var swiper = new Swiper(".tesiimginner_slider", {
   navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
   },
   });

   var greatSlider =  new Swiper(".great_slider", {
           slidesPerView: 1,
           spaceBetween: 0,
           autoplay:true,
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
               slidesPerView: 1,
               spaceBetween:0,
            },
            768: {
               slidesPerView: 1,
               spaceBetween:0,
            },
            1024: {
               slidesPerView: 1,
               spaceBetween:0,
            },
         },
      });

   var featured_list =  new Swiper(".destination_package_slider", {
           slidesPerView: 3,
           spaceBetween:20,
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
               slidesPerView: 2,
            },
            1024: {
               slidesPerView: 1,
            },
         },
      });
</script>



<script>
   // var epSlider = new Swiper(".latest_blog_slider", {
   // // slidesPerView: 5.3,
   // slidesPerView: 1,
   // loop: true,
   // parallax:true,
   // speed:2000,
   // navigation: {
   // nextEl: ".cat-prev",
   // prevEl: ".cat-next",
   // },
   // });

   var featuredSlider =  new Swiper(".featured_slider", {
           slidesPerView: 5,
           spaceBetween:20,
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
               slidesPerView: 2,
            },
            1024: {
               slidesPerView: 5,
            },
         },
      });
</script>
@endslot

@endcomponent