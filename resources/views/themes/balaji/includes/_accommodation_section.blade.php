<?php if(isset($featuredAccommodations) && !$featuredAccommodations->isEmpty() && $featuredAccommodations->count() > 0){ ?>
<section class="home_hotel bggray home_featured">
   <div class="xl:container xl:mx-auto">
      <div class="theme_title mb-6">
         <div class="title text-2xl">Our Domestic and International Hotels</div>
         <div class="view_all_btn"><a href="{{$view_all_url}}">View All</a>
         </div>
      </div>
      <div class="p_relative">
         <div class="swiper hotel_slider">
            <ul class="hotel_list swiper-wrapper">
               <?php  $storage = Storage::disk('public');
               foreach($featuredAccommodations as $accommodation){
                    // prd($accommodation->toArray());
                  $aTitle = $accommodation->name;
                  $aBrief = CustomHelper::wordsLimit($accommodation->brief,150);
                  $aStar = $accommodation->star_classification;
                  $aSlug = $accommodation->slug;
                  $aImage = $accommodation->image;

                  $path = 'accommodations/';
                  $thumbPath = 'accommodations/thumb/';

                  $accommodationSrc = asset(config('custom.assets').'/img/blog_default.png');
                  $accommodationThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');

                  if(!empty($aImage)){
                     if($storage->exists($path.$aImage)){
                        $accommodationSrc = asset('/storage/'.$path.$aImage);
                     }

                     if($storage->exists($thumbPath.$aImage)){
                        $accommodationThumbSrc = asset('/storage/'.$thumbPath.$aImage);
                     }
                  }
                  ?>      
                  <li class="swiper-slide">
                     <a class="hotel_box" href="{{route('hotelDetail',['slug' => $aSlug])}}">
                        <div class="images">
                           <img src="{{ $accommodationThumbSrc }}" alt="{{ $aTitle }}">
                        </div>
                        <div class="hotel_content py-4 px-2">
                           <div class="title text-sm">{{ $aTitle }}</div>
                           <div class="flex flex-wrap justify-between items-center">
                              <p class="para_md"><img src="{{ asset(config('custom.assets').'/img/map_hotel.png') }}" alt="Map Hotel">{{$accommodation->accommodationDestination->name??''}}</p>
                              <ul class="rating_list py-3" rating="{{$accommodation->star_classification??0}}">
                                 <?php for ($i=0; $i < $accommodation->star_classification ; $i++) { ?>   
                                    <li>
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                                    </li>
                                 <?php } ?>
                              </ul>
                           </div>
                        </div>
                     </a>
                  </li>
               <?php } ?>
            </ul>
         </div>
         <div class="slider_btns">
            <div class="hotel-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt="Next Icon"></div>
            <div class="hotel-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt="Prev Icon"></div>
         </div>
      </div>
   </div>
</section>
<?php } ?>