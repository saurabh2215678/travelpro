<?php if(isset($destinations) && !$destinations->isEmpty() && $destinations->count() > 0){ ?>
<section class="home_featured pb-0"  data-aos="fade-up">
   <div class="container-xl">
      <div class="text-center mb-6 padding_x">
         <h1 class="font30 color_secondary">{{$section_title}}</h1>
         <a class="arrow_btn mx-auto" href="{{url('destinations')}}">View All <img src="{{ asset(config('custom.assets').'/images/right_arrow.svg') }}" alt=""></a>
      </div>
      <div>
         <div>
            <div class="indian_destination_box">

               <div class="swiper destination_swiper">
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
                        <div class="swiper-slide">
                           <div class="indian_destination" >
                              <a class="destination_category_box" href="{{ url('destination/'.$national_destinations->slug) }}" >
                                 <div class="images">
                                    <img src="{{ $themeSrc }}"  alt="{{ $theme_title }} Tour Packages" >
                                 </div>
                                 <div class="featured_content py-4 px-2">
                                    <div class="title text-sm"> {{ $theme_title }}</div>
                              </div>
                              </a>
                           </div>
                        </div>        
                     <?php } ?>
                  </div>
               </div>

               <div class="slider_btns">
                  <div class="destination-next-national theme-next"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
                  <div class="destination-prev-national theme-prev"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
               </div>
            </div>
         </div>
      </div>

   </div>

</section>
   <?php } ?>