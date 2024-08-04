<section class="home_featured pb-0">
<div class="container">
  <div class="theme_title mb-6">
      <h2 class="section_heading">Top International Destinations</h2>
      <div class="view_all_btn"><a href="{{url('destinations')}}?international=1" hreflang="en">View All International Destinations <i class="fa-solid fa-chevron-right"></i></a>
   </div>
</div>
 <div class="slider_box">
    <div class="swiper destination_slider">
       <div class="swiper-wrapper">
          <?php $storage = Storage::disk('public');
          foreach ($destinations as $key => $international_destination) {
              if($key==0){

              $theme_path = "destinations/";

           }else{
            $theme_path = "destinations/thumb/";

           }
             $theme_title = CustomHelper::wordsLimit($international_destination->name);
             $theme_brief = CustomHelper::wordsLimit($international_destination->brief);
             $theme_slug = $international_destination->slug;
             $theme_image = $international_destination->image;
             $themeSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
             if(!empty($theme_image) && $storage->exists($theme_path.$theme_image)) {
              $themeSrc = asset('/storage/'.$theme_path.$theme_image);
             }
             ?>            
             <div class="swiper-slide" data-aos="fade-up-right">
               <a class="tour_category_box" href="{{ url('destination/'.$international_destination->slug)}}" >
                  <div class="images">
                     <img src="{{ $themeSrc }}"  class="theme_radius0" alt="{{ $theme_title }} Tour Package">
                  </div>
                  <div class="featured_content py-4 px-2">
                     <h3 class="title"> {{ $theme_title }}</h3>
               </div>
               </a>
            </div>
         <?php } ?>
      </div>
   </div>
   <div class="slider_btns">
    <div class="destination-next theme-next"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
    <div class="destination-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
 </div>
</div>
</div>
</section>