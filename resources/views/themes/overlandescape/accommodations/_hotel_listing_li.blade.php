
<?php 
$storage = Storage::disk('public');
$accommodation_path = "accommodations/";
$accommodation_feature_path = "accommodation_feature/";
$accommodation_name = $accommodation->name;
$accommodation_slug = $accommodation->slug;
$accommodation_brief = $accommodation->brief ?? '';
$accommodation_star = $accommodation->star_classification;
$accommodation_image = $accommodation->image;
$accommodationCategories = $accommodation->accommodationCategories;
$category_name = $accommodationCategories->title ?? '';

$accommodationDefaultThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
$accommodationThumbSrc = $accommodationDefaultThumbSrc;
$accommodation_features_arr = $accommodation->accommodation_feature ? json_decode($accommodation->accommodation_feature): [];

$accommodation_features_arr_obj = App\Models\AccommodationFeature::whereIn('id',$accommodation_features_arr)->where('is_featured',1)->get();
if(!empty($accommodation_image)){
   if($storage->exists($accommodation_path.$accommodation_image)){
      $accommodationThumbSrc = asset('/storage/'.$accommodation_path.'thumb/'.$accommodation_image);
   }
}
$accommodationImages = $accommodation->accommodationImages??[];
$checkin = $checkin??'';
$checkout = $checkout??'';
$adult = $adult??'';
$child = $child??'';
$infant = $infant??'';
$params = [];
$params['slug'] = $accommodation_slug;
if($checkin) {
   $params['checkin'] = $checkin;
}
if($checkout) {
   $params['checkout'] = $checkout;
}
if($adult) {
   $params['adult'] = $adult;
}
if($child) {
   $params['child'] = $child;
}
if($infant) {
   $params['infant'] = $infant;
}
$url = route('hotelDetail',$params);
?>
<div class="hotel-card mb-5">
   <ul>
      <li>
         <div class="flex flex-row items-center">
            <div class="img-list-view w-1/3 m-2">
               <div class="swiper hotelViewgallery2">
                  <div class="swiper-wrapper">
                     <?php if(!empty($accommodationImages)) {
                        foreach($accommodationImages as $image) {
                           $accommodationThumbSrc = $accommodationDefaultThumbSrc;
                           if($image->name) {
                              $accommodationSrc = asset('/storage/'.$accommodation_path.$image->name);
                              $accommodationThumbSrc = asset('/storage/'.$accommodation_path.'thumb/'.$image->name);
                        ?>
                     <div class="swiper-slide 1">
                        <a data-fancybox="{{$accommodation_name}}" href="{{$accommodationSrc}}">
                           <img src="{{$accommodationThumbSrc}}" alt="{{$image->title ?? ''}}" />
                        </a>
                     </div>
                     <?php } } } else { ?>
                     <div class="swiper-slide 2">
                        <img src="{{$accommodationThumbSrc}}" alt="{{$accommodation_name ?? ''}}" />
                     </div>
                     <?php } ?>
                  </div>
                  <div class="swiper-button-next"></div>
                  <div class="swiper-button-prev"></div>
               </div>
               <div thumbsSlider="" class="swiper hotelViewgallery" style="margin-left:0;">
                  <div class="swiper-wrapper">
                     <?php if(!empty($accommodationImages)) {
                        foreach($accommodationImages as $image) {
                           $accommodationThumbSrc = $accommodationDefaultThumbSrc;
                           if($image->name) {
                              $accommodationThumbSrc = asset('/storage/'.$accommodation_path.'thumb/'.$image->name);
                        ?>
                     <div class="swiper-slide">
                        <img src="{{$accommodationThumbSrc}}" alt="{{$image->title ?? ''}}" />
                     </div>
                     <?php } } } else { ?>
                     <div class="swiper-slide">
                        <img src="{{$accommodationThumbSrc}}" alt="{{$accommodation_name ?? ''}}" />
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <div class="hotel-content-list-view w-1/2 p-3">
               <div class="title text-xl"><a href="{{$url}}">{{$accommodation_name}}</a></div>
               <!-- <div class="hotel-catname text-xs py-1"><i class="fa fa-building-o"></i> Modern Apartment</div> -->
               <div class="star-loc">
                  <ul class="rating_list py-1" rating="{{ $accommodation_star }}">
                     @if(isset($accommodation_star) && $accommodation_star > 0)
                     <?php for ($i=0; $i < $accommodation_star ; $i++) { ?>
                        <li>
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                        </li>
                     <?php } ?>
                     @endif
                  </ul>
                  <div class="addrmap text-sm">
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
                     ?>
                     <a href="#"><i class="fa fa-map-marker"></i> {{implode(', ',$name_arr)}}<!--  - View on map --></a>
                     <?php } ?>
                  </div>
               </div>
               <div class="hotel-facilities text-sm mt-1">

                  <?php if(!empty($accommodation_features_arr_obj)){ ?>
                     <ul class="flex">
                        <?php foreach($accommodation_features_arr_obj as $feature_id){ 

                           if(!empty($feature_id->icon)){
                              $accommodationFeatureThumbSrc = '';
                              if($storage->exists($accommodation_feature_path.$feature_id->icon)){
                                 $accommodationFeatureThumbSrc = asset('/storage/'.$accommodation_feature_path.'thumb/'.$feature_id->icon);
                              }
                           }

                           ?>
                           <li>
                              <img src="{{$accommodationFeatureThumbSrc}}" >
                              <span>{{$feature_id->title}}</span>
                           </li>
                        <?php } ?>
                     </ul>
                  <?php } ?>
                  {{CustomHelper::wordsLimit($accommodation_brief)}}
               </div>
               <!-- <div class="mount-view inline-block text-sm bg-blue-100 text-cyan-600 p-1 px-2 tracking-wide cursor-pointer"><i class="fa fa-picture-o"></i> Mountain View</div>
               <div class="cash-reward text-sm py-1 mt-5">
                  <i class="fa fa-money"></i> Cashback Rewards: <span>Rs.320</span>
               </div>
               <div class="coupon-code text-sm text-green-600 py-1">
                  <i class="fa fa-tag"></i> Coupon Code 24 HOURSALE applied - Rs. 182 off!
               </div> -->
            </div>
            <div class="price-list-view w-1/4 p-3 text-right border-l">
               <!-- <div class="rating-txt">
                  <ul class="flex items-center justify-end">
                     <li class="font-bold leading-5">Very Good <br><span class="text-xs font-normal">12 reviews</span></li>
                     <li>9.1</li>
                  </ul>
               </div> -->
               <!-- <div class="hotel-offer bg-red-500 text-white text-xs inline-block p-1 px-2 mt-5">
                  78% OFF TODAY
               </div> -->
               <ul>
                  <!-- <div class="cut-price text-lg py-1 mt-5 line-through">Rs. 4,768</div> -->
                  <!-- <div class="txt-term text-xs py-1">Subject to Cashback Terms</div> -->
                  <!-- <div class="main-price text-xl py-1">Rs. 4,468</div> -->
                  <!-- <div class="coupon-code text-sm text-green-600 py-1">
                     + FREE CANCELLATION
                  </div> -->
               </ul>
               <a class="view-more text-sm mt-1 p-2" href="{{$url}}">Book Now</a>
            </div>
         </div>
      </li>
      <li class="last-card bg-slate-100 p-3 flex items-center">
         <!-- <div class="text-sm">
            <i class="fa fa-heart"></i> Couple<br> <span>User</span>
         </div> -->
         <!-- <div class="text-sm">8.4 Excellent - “The stay is well-located and connected.”</div> -->
      </li>
   </ul>
</div>

<?php /*
<div class="hotel-card mb-5">
   <ul>
<li>
                     <div class="flex flex-row items-center">
                        <div class="img-list-view w-1/3">
                           <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper hotelViewgallery2">
                              <div class="swiper-wrapper">
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-1.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-2.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-3.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-4.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-5.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-6.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-7.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-8.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-9.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-10.jpg" />
                                 </div>
                              </div>
                              <div class="swiper-button-next"></div>
                              <div class="swiper-button-prev"></div>
                           </div>
                           <div thumbsSlider="" class="swiper hotelViewgallery">
                              <div class="swiper-wrapper">
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-1.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-2.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-3.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-4.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-5.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-6.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-7.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-8.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-9.jpg" />
                                 </div>
                                 <div class="swiper-slide">
                                    <img src="https://swiperjs.com/demos/images/nature-10.jpg" />
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="hotel-content-list-view w-1/2 p-3">
                           <div class="title text-xl">Trikaya Hotel</div>
                           <div class="hotel-catname text-xs py-1"><i class="fa fa-building-o"></i> Modern Apartment</div>
                           <div class="star-loc">
                              <ul>
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
                              <div class="addrmap text-sm">
                                 <a href="#"><i class="fa fa-map-marker"></i> Leh, Ladakh - View on map</a>
                              </div>
                           </div>
                           <div class="hotel-facilities mt-1">
                              <ul>
                                 <li>Breakfast</li>
                                 <li>Parking</li>
                                 <li>Lunch Included</li>
                                 <li>+2</li>
                              </ul>
                           </div>
                           <div class="mount-view inline-block text-sm bg-blue-100 text-cyan-600 p-1 px-2 tracking-wide cursor-pointer"><i class="fa fa-picture-o"></i> Mountain View</div>
                           <div class="cash-reward text-sm py-1 mt-5">
                              <i class="fa fa-money"></i> Cashback Rewards: <span>Rs.320</span>
                           </div>
                           <div class="coupon-code text-sm text-green-600 py-1">
                              <i class="fa fa-tag"></i> Coupon Code 24 HOURSALE applied - Rs. 182 off!
                           </div>
                        </div>
                        <div class="price-list-view w-1/4 p-3 text-right border-l">
                           <div class="rating-txt">
                              <ul class="flex items-center justify-end">
                                 <li class="font-bold leading-5">Very Good <br><span class="text-xs font-normal">12 reviews</span></li>
                                 <li>9.1</li>
                              </ul>
                           </div>
                           <div class="hotel-offer bg-red-500 text-white text-xs inline-block p-1 px-2 mt-5">
                              78% OFF TODAY
                           </div>
                           <ul>
                              <div class="cut-price text-lg py-1 mt-5 line-through">Rs. 4,768</div>
                              <div class="txt-term text-xs py-1">Subject to Cashback Terms</div>
                              <div class="main-price text-xl py-1">Rs. 4,468</div>
                              <div class="coupon-code text-sm text-green-600 py-1">
                                 + FREE CANCELLATION
                              </div>
                           </ul>
                        </div>
                     </div>
                  </li>
                  <li class="last-card bg-slate-100 p-3 flex items-center">
                     <div class="text-sm">
                     <i class="fa fa-heart"></i> Couple<br> <span>Gopinath</span>
                     </div>
                     <div class="text-sm">8.4 Excellent - “The stay is well-located and connected.”</div>
                  </li>
                  </ul>
</div>
*/?>

