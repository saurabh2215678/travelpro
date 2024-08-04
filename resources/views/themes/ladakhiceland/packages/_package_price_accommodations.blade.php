<?php $index= 0; ?>

<div id="hotel_accom" class="hotel_accordion mt-5">
   <h3 class="no_line text-2xl pb-1 font-bold">Accommodation</h3>
   <p class="pb-2"><strong>Note:-</strong> We Will provide you these or similar accomodation depending on availability </p>
  <?php foreach ($accommodations_days as $key => $accommodation_days) {
         // prd($accommodation_days);
         // prd($itenary_arr);
         $index++;
         $accommodation_days->itenary_data;
         $itenary_arr = $accommodation_days->itenary_data?json_decode($accommodation_days->itenary_data):[];
         $accommodation_arr = $accommodation_days->accommodation_data?json_decode($accommodation_days->accommodation_data):[];
         ?>
            <button class="accordion accomodation_acc <?php  if($index== 1) echo 'active' ?>">
               <div class="pull-left"><b></b></div>
               <?php $itineraries_title = [];
               foreach($itenary_arr as $key => $itenary_id){
                     $itenary_data = CustomHelper::getitenarydata($itenary_id);
                     // prd($itenary_data);
                     if($itenary_data){              
                     $itineraries_title[]= $itenary_data->day_title ?? '' ;
                    }
                 } ?>
               @if(!empty($itineraries_title))
               <div class="pull-right day_font">
                  <b>
                    {!!implode('<i class="fa fa-long-arrow-right" aria-hidden="true"> </i>',$itineraries_title)!!}
                  </b>
               </div>
               @endif
            </button>

            <div class="hotel_package_detail" style="<?php  if($index== 1) echo 'display:block' ?>">
               <div class="slider_box">
               <div class="swiper hotel_inner_slider">
                  <div class="swiper-wrapper">     
               <?php foreach($accommodation_arr as $key => $accommodation){
                     $accommodation = CustomHelper::getAccommodationdata($accommodation);

                     if($accommodation){
                     // prd($accommodation_data);

                     $storage = Storage::disk('public');
                     $accommodation_path = 'accommodations/';
                     $accommodation_image = isset($accommodation->image) ? $accommodation->image:'';

                     $accommodationThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                     if(!empty($accommodation_image)){
                        if($storage->exists($accommodation_path.$accommodation_image)){
                           $accommodationThumbSrc = asset('/storage/'.$accommodation_path.'thumb/'.$accommodation_image);
                        }
                     }
                     $accommodation_slug = $accommodation->slug;
                     $accommodation_star = $accommodation->star_classification;
                     ?>
                  <div class="swiper-slide">
                     <div class="hodel_detail_list">
                        <div class="hotel_info hotel_info_typeid385 " style="">
                           <div class="hotel_info_box itenery_info">
                              <div class="itn_img">
                                 <img src="{{ $accommodationThumbSrc }}" alt="{{$accommodation->name ?? '' }}">
                              </div>
                              <div class="hotel_destination"><img class="map-i" src="{{asset(config('custom.assets').'/images/map.png')}}"> {{$accommodation->accommodationDestination ? $accommodation->accommodationDestination->name:''}}</div>
                              <div class="box-content">
                                 <h6 class="dest_title"><a class="fancy_popup fancybox.iframe" data-fancybox="" data-type="iframe" href="{{url('packagespopup/hotel-details',['slug'=>$accommodation_slug])}}" rel="nofollow">{{$accommodation->name ?? '' }}</a></h6>
                                 <div class="star_box">
                                    <ul class="rating_list">
                                       @if(isset($accommodation_star) && $accommodation_star > 0)

                                       <?php for ($i=0; $i < $accommodation_star ; $i++) { ?>
                                       <li>
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                                       </li>
                                    <?php } ?>
                                    @endif
                                    </ul>
                                 </div>
                                 <div class="brief-text text-sm mt-3"></div>
                              </div>

                           </div>
                           <div class="clearfix"></div>
                        </div>
                        <!-- View More In Pop Up-->
                        <!-- <div class="popup-details">
                           <a class="fancy_popup fancybox.iframe" data-fancybox="" data-type="iframe" href="{{url('packagespopup/hotel-details',['slug'=>$accommodation_slug])}}" rel="nofollow">Details</a>
                        </div> -->
                     </div>
                  </div>
               <?php } } ?>


                    
                    
                  </div>
                  
               </div>
               <div class="slider_btns">
                  <div class="hotel-inner-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
                  <div class="hotel-inner-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
               </div>
            </div>
            </div>
  <?php } ?>

<script>
   //  var swiper = new Swiper(".hotel_inner_slider", {
   //    slidesPerView: 3,
   //    spaceBetween: 30,
   //    navigation: {
   //     nextEl: ".hotel-inner-next",
   //     prevEl: ".hotel-inner-prev",
   //    },
   //  });


   $('.accomodation_acc').click(function(){
      $(this).toggleClass('active');
      $(this).siblings().removeClass('active');
      $(this).next(".hotel_package_detail").slideToggle();
      $(this).next(".hotel_package_detail").siblings('.hotel_package_detail').slideUp();
   });

   $('.hotel_inner_slider').each(function(){
   var element = $(this)[0];
   var nextButton = $(this).closest('.slider_box').find('.hotel-inner-next')[0];
   var prevButton = $(this).closest('.slider_box').find('.hotel-inner-prev')[0];
   console.log(nextButton);
   new Swiper(element, {
      slidesPerView:3,
      spaceBetween:20,
      loop: false,
      speed:1000,
      breakpoints: {
         0: {
            slidesPerView: 1,
         },
         640: {
            slidesPerView: 2,
         },
         768: {
            slidesPerView: 3,
         },
         1024: {
            slidesPerView: 3,
         },
      },
      navigation: {
       nextEl: nextButton,
       prevEl: prevButton,
    },
 });
});
  </script>


<script>