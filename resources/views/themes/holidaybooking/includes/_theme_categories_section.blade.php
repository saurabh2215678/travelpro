<?php 
$storage = Storage::disk('public');?>

<section class="home_featured pb-0"  data-aos="fade-up">
   <div class="container-xl">

      <div class="text-center mb-6 padding_x">
         <h2 class="font30  color_secondary">{{$section_title}}</h2>
         <a class="arrow_btn mx-auto" href="{{route('tourCategoryListing')}}">View All <img src="{{ asset(config('custom.assets').'/images/right_arrow.svg') }}" alt=""></a>
      </div>

      <div>
         <div>
            <div class="indian_destination_box">

               <div class="swiper destination_swiper">
                  <div class="swiper-wrapper">
                   <?php 
                   foreach ($result as $theme) {
                     $theme_title = $theme['title'] ?? '';
                     $themeSrc = $theme['image_src'] ?? '';
                     ?>   
                        <div class="swiper-slide">
                           <div class="indian_destination" >
                              <a class="destination_category_box" href="{{ route('tourCategoryDetail',[$theme['slug']]) }}" >
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

            </div>
         </div>
      
      </div>

   </div>

</section>


