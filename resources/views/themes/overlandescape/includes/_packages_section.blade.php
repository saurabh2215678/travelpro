<?php if(isset($packages) && !$packages->isEmpty() && $packages->count() > 0){
 ?>
 <section class="blog-package pb-0"  data-aos="fade-up">
 <div class="container-xl">
   <div class="text_left p_relative">
      
           <div class="text-center mb-6 padding_x">
            <h2 class="font30 color_secondary">{{$section_title}}</h2>
            <a class="arrow_btn mx-auto" href="{{$view_all_url}}">View All <i class="fa-solid fa-arrow-right-long"></i></a>
         </div>
      <div class="slider_btns">
       <div class="pack_slider-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
       <div class="pack_slider-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
    </div>
    <div class="slider_box">
       <div>
          <div class="swiper pack_slider">
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