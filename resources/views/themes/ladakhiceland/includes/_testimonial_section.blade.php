 <section class=" home_featured testimonial_block"> 
     <div class="container">
       <div class="flex">
         <div class="w-1/2">
         <div class="padding pr-3 mb-3">
               <div class="text-sky-500">Testimonials</div>
               <h3 class="section_heading">{{$section_title}}</h3>
                @if($section_brief)
               <div class="section_brif pt-3">{!!$section_brief??''!!}</div>
               @endif

               <div class="view_all_btn mt-5">
               <a href="{{url('testimonial')}}" hreflang="en">All Testimonials <i class="fa fa-long-arrow-right"></i></a>
            </div>
         </div>
         </div>
         <div class="w-1/2">
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
                        <div class="reviews"><a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}" hreflang="en">{!! $tDescription !!}</a></div>
                        <div class="client_info">
                          <a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}" hreflang="en"> 
                           <div class="client_name pt-2">
                              @if($testimonialThumbSrc)
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
       <div class="testislider_btn mt-3">
         <div class="testimonial-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
         <div class="testimonial-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
       </div>
      </div>
         </div>
         </div>
   </div>
</section>