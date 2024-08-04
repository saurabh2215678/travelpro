<?php if(isset($testimonials) && !$testimonials->isEmpty() && $testimonials->count() > 0){ ?>
<section class="home_testimonial home_featured mt-7">
   <div class="lg:container lg:mx-auto">
 
<div class="slider_box">
<div class="w-2/4">
<div class="theme_title mb-6">
   <div class="title text-2xl">{{$section_title}}</div>
   <p class="para_md" style="color:#fff;text-align: left;">Grand India Tour designs itineraries for our clients that others simply cannot, whether around a theme or a private experience.</p>
</div>
  <div class="swiper testimonial_slider">
     <div class="swiper-wrapper">
      <?php $storage = Storage::disk('public');
      foreach($testimonials as $testimonial){
         $tName = $testimonial->name ?? '';
         $tSlug = $testimonial->slug ?? '';
         $tTitle = $testimonial->title ??'';
         $tLocation = $testimonial->location ?? '';
         $tDescription = CustomHelper::wordsLimit($testimonial->description);
         $tImage = $testimonial->image;
         //======

               $path = 'testimonials/';
               $thumbPath = 'testimonials/thumb/';
               $testimonialThumbSrc = asset(config('custom.assets').'/images/default_user.png');
               $testimonialGalleryDefaultImage = asset(config('custom.assets').'/images/testimonial-main-default.png');
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
            <!-- <div class="testimage">
               <div class="swiper tesiimg_slider">
                  <div class="swiper-wrapper">
                     @if(!empty($testimonialImages) && count($testimonialImages) > 0)
                     @foreach($testimonialImages as $image)
                     <div class="swiper-slide">
                        @if($storage->exists($thumbPath.$image->name))
                        <img src="{{asset('/storage/'.$thumbPath.$image->name)}}" alt="{{$image->title??''}}" />
                        @else
                        <img src="{{$testimonialGalleryDefaultImage}}" alt="{{$image->title??''}}" />
                        @endif
                     </div>
                     @endforeach
                     @else
                     <div class="swiper-slide"><img src="{{ $testimonialGalleryDefaultImage }}" alt="{{$image->title??''}}"></div>
                     @endif
                  </div>
                  <div class="swiper-button-next"></div>
                  <div class="swiper-button-prev"></div>
               </div>
            </div> -->
            <div class="testimonial_box testcont">
               <!-- <div class="testi_heading pb-1 text-2xl Lato"><a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}">{{ $tTitle }}</a></div> -->
               <p>{!! $tDescription !!}</p>
               <div class="client_info">
                <a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}">
                  <div class="client_name pt-2">
                     @if($testimonialThumbSrc)
                     <!-- <div class="h-50"><img class="h-10" src="{{ $testimonialThumbSrc }}" alt="{{ $tName }}"></div> -->
                     @endif
                     <div class="name para_lg2">{{ $tName }}</div>
                  </div>
               </a>
            </div>
         </div>
      </div>
   <?php } ?>
</div>
<div class="slider-btns">
   <div class="testimonial-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt="Next Icon"></div>
   <div class="testimonial-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt="Prev Icon"></div>
</div>

</div>
<div class="view_all_btn"><a href="{{route('testimonialListing')}}">View All</a> </div>
</div>
<div class="w-2/4 test_right pl-28">
            <img class="w-auto" src="{{asset(config('custom.assets').'/images/testimonial-right-img.png') }}" alt="">           
         </div>
 </div>

</div>
</section>
<?php } ?>