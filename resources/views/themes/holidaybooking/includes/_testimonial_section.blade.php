<section class="bggray home_featured"> 
     <div class="container-xl">

         <div class="text-center mb-6 padding_x">
            <h2 class="font30  color_secondary">Customer Review</h2>
            <a class="arrow_btn mx-auto" href="{{route('testimonialListing')}}">View All <img src="{{ asset(config('custom.assets').'/images/right_arrow.svg') }}" alt=""></a>
         </div>
        <!--<p class="para_md">Our clients love us! Here's what a few of them have to say about their visit to Bhutan with Yangphel.</p>-->
        <div class="slider_box">
           <div class="swiper testimonial_slider">
              <div class="swiper-wrapper">
               <?php 
               $storage = Storage::disk('public');
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
                              
                           </div>
                         </a>
                        </div>
                     </div>
                  </div>
               <?php } ?>
            </div>
         </div>
       <div class="testimonial_all">
  </div>
      </div>
   </div>
</section>