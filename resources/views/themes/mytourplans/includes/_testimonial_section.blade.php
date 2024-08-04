<section class="bggray home_featured"> 
     <div class="container">
       <div class="theme_title mb-6">
               <h3 class="section_heading">Testimonials</h3>
               <div class="view_all_btn"><a href="{{route('testimonialListing')}}" hreflang="en">View All Testimonials <i class="fa-solid fa-chevron-right"></i></a>
            </div>
         </div>
        <!--<p class="para_md">Our clients love us! Here's what a few of them have to say about their visit to Bhutan with Yangphel.</p>-->
        <div class="slider_box">
           <div class="swiper testimonial_slider">
              <div class="swiper-wrapper">
               <?php $storage = Storage::disk('public');
               foreach($testimonials as $testimonial){
                  $tName = $testimonial->name ?? '';
                  $tSlug = $testimonial->slug ?? '';
                  $tTitle = $testimonial->title ??'';
                  $tLocation = $testimonial->location ?? '';
                  $tDescription = CustomHelper::wordsLimit($testimonial->description,150,'','','Read more');
                  $tImage = $testimonial->image;

                  $path = 'testimonials/';
                  $thumbPath = 'testimonials/thumb/';
                  $testimonialThumbSrc = asset(config('custom.assets').'/images/default_user.png');
                  $testimonialGalleryDefaultImage = asset(config('custom.assets').'/images/noimagebig.jpg');
                  if(!empty($tImage)){
                     if($storage->exists($path.$tImage)){
                        $testimonialSrc = asset('/storage/'.$path.$tImage);
                     }
                     if($storage->exists($thumbPath.$tImage)){
                        $testimonialThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                     }
                  }
                  $testimonialImages = $testimonial->testimonialImages??[];
                  if(empty($testimonialThumbSrc) && empty($testimonialImages)) {
                     $testimonialThumbSrc = asset(config('custom.assets').'/images/default_user.png');
                  }
                  ?>
                  <div class="swiper-slide">
                   

                     <div class="testimonial_box testcont">
                        <!-- <div class="testi_heading pb-3 text-2xl Lato"><a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}" hreflang="en">{{ $tTitle }}</a></div> -->
                        <div class="reviews"><a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}" hreflang="en">{!! $tDescription !!}</a></div>
                        <div class="client_info">
                          <a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}" hreflang="en"> 
                           <div class="client_name pt-2">
                              @if($testimonialThumbSrc)
                           <!-- <div class="h-50"><img class="h-10" src="{{ $testimonialThumbSrc }}"></div> -->
                           @endif
                              <div class="name para_lg2 pt-2">{{ $tName }}</div>
                              <!-- <div class="name para_lg2 pt-2">{{ $tTitle }}</div> -->
                              <!--<div class="city">{{ $tLocation }}</div>-->
                           </div>
                         </a>
                        </div>
                     </div>
                  </div>
               <?php } ?>
               </div>
              <div class="swiper-pagination"></div>
            </div>
          <div class="testimonial_all">
         </div>
       </div>
     </div>
   </section>