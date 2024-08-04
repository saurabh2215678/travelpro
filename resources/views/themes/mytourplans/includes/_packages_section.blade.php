<?php if(isset($packages) && !$packages->isEmpty() && $packages->count() > 0){ ?>
<section class="home_featured pb-0" >
<div class="container">
   <div class="text_left p_relative">
      <div class="theme_title mb-6">
         <h3 class="section_heading">{{$section_title}}</h3>
         <div class="view_all_btn"><a href="{{$view_all_url}}">View All</a>
         </div>
      </div>
      <div class="slider_btns">
       <div class="featured-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
       <div class="featured-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
    </div>
    <div class="slider_box">
       <div>
          <div class="swiper featured_slider">
            <ul class="swiper-wrapper">
               @include(config('custom.theme').'/includes/package-li-box',['packages'=>$packages])
            </ul>
            </div>
         </div>
      </div>
   </div>
</div>
</section>
<?php } ?>