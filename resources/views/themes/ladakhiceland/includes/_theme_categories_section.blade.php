<?php 
$storage = Storage::disk('public');?>
<section class="home_featured  bg_lt_gray">
<div class="container">
<div class="text-center mb-6 padding_x">
      <div class="text-sky-500">Travel Theme</div>
      <h1 class="section_heading">{{$section_title}}</h1>
</div>
 <div class="slider_box">
    <div class="swiper category_slider">
       <div class="swiper-wrapper">
          <?php
          foreach ($result as $theme) {
            $theme = (object)$theme;
             $theme_path = "themes/";
             $theme_title = CustomHelper::wordsLimit($theme->title);
             $theme_brief = CustomHelper::wordsLimit($theme->brief);
             $theme_slug = $theme->slug;
             $themeSrc = $theme->image_src;
             
             ?>            
             <div class="swiper-slide">
               <a class="tour_category_box" href="{{ route('tourCategoryDetail',[$theme->slug]) }}" >
                  <div class="images">
                     <img src="{{ $themeSrc }}" class="theme_radius0" alt="{{ $theme_title }} Tour Package">
                  </div>
                  <div class="featured_content py-2 px-2">
                     <div class="title text-sm uppercase"> {!! $theme_title !!}</div>
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
<div class="view_all_btn text-center mt-5 mb-3"><a href="{{route('tourCategoryListing')}}">View All <i class="fa fa-long-arrow-right"></i></a>
</div>
</section>