<?php if(!empty($accommodations_days) && count($accommodations_days) > 0){ ?>
<div id="hotel_accom" class="hotel_accordion mt-5">
   <h3 class="no_line text-2xl pb-1 font-bold">Accommodation</h3>
   <p class="pb-1"> <strong>Note:-</strong> We Will provide you these or similar accomodation depending on availability </p>
   <?php foreach ($accommodations_days as $key => $accommodation_days) {
      // prd($accommodation_days);
      // prd($itenary_arr);
      $accommodation_days->itenary_data;
      $itenary_arr = $accommodation_days->itenary_data?json_decode($accommodation_days->itenary_data):[];
      $accommodation_arr = $accommodation_days->accommodation_data?json_decode($accommodation_days->accommodation_data):[];
      ?>
   <div class="accordion">
      <div class="accordion-item">
         <div class="accordion-item-header <?php $index= 1; if($index== 1) echo '' ?>">
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
               {!!implode('<i class="fa fa-long-arrow-right px-3"></i>' ,$itineraries_title)!!}
            </div>
            @endif
         </div>
         <!-- /.accordion-item-header -->
         <div class="accordion-item-body">
            <div class="accordion-item-body-content">
               <div class="hotel_package_detail">
                  <div class="slider_box">
                     <div class="swiper hotel_inner_slider">
                        <div class="swiper-wrapper">
                           <?php foreach($accommodation_arr as $key => $accommodation){
                              $accommodation = CustomHelper::getAccommodationdata($accommodation);
                              $disabled ='';
                              if($accommodation){
                              
                                 if($accommodation->status == 0){
                                    $disabled = 'disabled';
                                 }
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
                              $accommodation_brief = $accommodation->brief ?? '';
                              ?>
                           <div class="swiper-slide {{$disabled}}">
                              <div class="hodel_detail_list">
                                 <div class="hotel_info hotel_info_typeid385 " style="">
                                    <div class="hotel_info_box itenery_info">
                                       <div class="itn_img">
                                          <img src="{{ $accommodationThumbSrc }}" alt="{{$accommodation->name ?? '' }}">
                                       </div>
                                       <div class="box-content" @if(count($accommodation_arr) == '1') style="position: absolute;" @endif>
                                       <h6 class="dest_title"><a class="fancy_popup fancybox.iframe" data-fancybox="" data-type="iframe" href="{{url('packagespopup/hotel-details',['slug'=>$accommodation_slug])}}" rel="nofollow">{{$accommodation->name ?? '' }}</a></h6>
                                       <div class="star-loc">
                                          <ul class="rating_list" rating="{{ $accommodation_star }}">
                                             <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                   <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                                                </svg>
                                             </li>
                                             <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                   <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                                                </svg>
                                             </li>
                                             <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                   <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                                                </svg>
                                             </li>
                                             <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                   <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                                                </svg>
                                             </li>
                                             <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                   <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                                                </svg>
                                             </li>
                                          </ul>
                                          <div class="hotel_destination text-sm"><img class="map-i" src="{{asset(config('custom.assets').'/images/map.png')}}">
                                             <?php
                                                $name_arr = [];
                                                $place_name = $accommodation->AccommodationDefaultLocation->DestinationLocation->name??'';
                                                if($place_name) {
                                                   $name_arr[] = $place_name;
                                                }
                                                $distination_name = $accommodation->accommodationDestination->name??'';
                                                if($distination_name) {
                                                   $name_arr[] = $distination_name;
                                                }
                                                if(!empty($name_arr)) {
                                                   echo implode(', ',$name_arr);
                                                }
                                                ?>
                                          </div>
                                       </div>
                                       <div class="brief-text text-sm mt-3"></div>
                                    </div>
                                 </div>
                                 <div class="clearfix"></div>
                              </div>
                           </div>
                        </div>
                        <?php } } ?>
                     </div>
                  </div>
                  <div class="slider_btns">
                     <div class="hotel-inner-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt="Next Icon"></div>
                     <div class="hotel-inner-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt="Prev Icon"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.accordion-item-body -->
   </div>
   <!-- /.accordion-item -->
</div>
<?php } } ?> 

<script>
  const accordionItemHeaders = document.querySelectorAll(".accordion-item-header");

accordionItemHeaders.forEach(accordionItemHeader => {
   accordionItemHeader.addEventListener("click", event => {

     accordionItemHeader.classList.toggle("active");
     const accordionItemBody = accordionItemHeader.nextElementSibling;
     if(accordionItemHeader.classList.contains("active")) {
      accordionItemBody.style.maxHeight = accordionItemBody.scrollHeight + "px";
     }
     else {
       accordionItemBody.style.maxHeight = 0;
     }
    
   });
});
</script>

<script>
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
<script type="text/javascript">
   $('.rating_list').each(function(){
      var ratingValue = parseInt($(this).attr('rating'))
      $(this).children('li').each(function(i){
         if(i < ratingValue){
            $(this).addClass('active');
         }
      })
   });
</script>
<script type="text/javascript">
   $('accordion').each(function(){
      var ratingValue = parseInt($(this).attr('hotel_package_detail'))
      $(this).children('hotel_package_detail').each(function(i){
         if(i < ratingValue){
            $(this).addClass('active');
         }
      })
   });
</script>
