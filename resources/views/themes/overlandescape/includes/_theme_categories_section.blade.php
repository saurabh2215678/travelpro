<?php if($result){ ?>
 <link rel="preload stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>  
<section class="pb-0" >
<div class="xl:container xl:mx-auto">
 <div class="theme_title mb-6">
   <div class="title text-2xl"> {{$section_title}}</div>
   <div class="view_all_btn"><a href="{{route('tourCategoryListing')}}">View All</a>
   </div>
</div>
<div class="slider_box">
  <div class="swiper category_slider ">
     <div class="swiper-wrapper">
        <?php 
        foreach ($result as $theme) {
            $theme_title = $theme['title'] ?? '';
            $themeSrc = $theme['image_src'] ?? '';
          ?>            
          <div class="swiper-slide">
            <a class="tour_category_box" href="{{ route('tourCategoryDetail',[$theme['slug']]) }}" >
               <div class="images">
                  <img src="{{ $themeSrc }}" class="theme_radius0" alt="{{ $theme_title }} Tour Package">
               </div>
               <div class="featured_content py-4 px-2">
                  <div class="title text-sm"> {!! $theme_title !!}</div>
               </div>
            </a>
         </div>
      <?php } ?>
   </div>
</div>
<div class="slider_btns">
  <div class="cat-next theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt="Next Icon"></div>
  <div class="cat-prev theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt="Prev Icon"></div>
</div>
</div>
</div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
<script type="text/javascript">
   var epSlider = new Swiper(".category_slider", {
            slidesPerView: 3,
            spaceBetween:15,
            loop: false,
            speed:1000,
            navigation: {
                prevEl: ".theme-prev",
                nextEl: ".theme-next",
            },
            breakpoints: {
                0: {
                  slidesPerView: 1.5,
                },
                640: {
                  slidesPerView: 2,
                },
                768: {
                  slidesPerView: 2,
                },
                1024: {
                  slidesPerView: 3,
                },
            },
            watchSlidesVisibility: true
          });
</script>
<?php } ?>