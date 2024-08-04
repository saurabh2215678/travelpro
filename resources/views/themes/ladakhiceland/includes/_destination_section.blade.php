<?php if(isset($destinations) && !$destinations->isEmpty() && $destinations->count() > 0){ ?>
<section class="home_featured ">
<div class="container">
  <div class="text-center mb-6 padding_x">
      <div class="text-center mb-6 padding_x">
         <div class="text-sky-500">Find out what the best destinations</div>
            <h3 class="section_heading">Popular Destinations</h3>
            <!-- <p class="sub_heading">India is incredible! It has mountains, beaches, waterfalls, rivers, lakes, seas, and innumerable other natural treasures. Take a look at the most sought-after holiday destinations!</p> -->
      </div>
   </div>
 <div>
    <div class="swiper desti_slider">
       <div class="swiper-wrapper">
          <?php $storage = Storage::disk('public');
          foreach ($destinations as $key => $national_destinations) {
            if($key==0){

              $theme_path = "destinations/";

           }else{
            $theme_path = "destinations/";

           }
             $theme_title = CustomHelper::wordsLimit($national_destinations->name);
             $theme_brief = CustomHelper::wordsLimit($national_destinations->brief);
             $theme_slug = $national_destinations->slug;
             $theme_image = $national_destinations->image;

             $themeSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
             if(!empty($theme_image) && $storage->exists($theme_path.$theme_image)) {
              $themeSrc = asset('/storage/'.$theme_path.$theme_image);
             }
             ?>            
             <div class="swiper-slide" >
               <a class="destination_category_box" href="{{ url('destination/'.$national_destinations->slug) }}" >
                  <div class="images">
                     <img src="{{ $themeSrc }}"  alt="{{ $theme_title }} Tour Packages" >
                  </div>
                  <div class="featured_content desti_text py-4 px-2">
                     <div class="title text-sm"> {{ $theme_title }}</div>
                     <div class="desti_brief">{{ $theme_brief }}</div>
               </div>
               </a>
            </div>
         <?php } ?>
      </div>
   </div>
  
</div>
</div>
<div class="view_all_btn text-center mt-5 mb-3"><a href="{{url('destinations')}}">View All <i class="fa fa-long-arrow-right"></i></a>
</div>
</section>
   <?php } ?>