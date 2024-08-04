<?php 
$storage = Storage::disk('public');?>
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
</div>