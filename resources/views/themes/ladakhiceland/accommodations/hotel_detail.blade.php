@component(config('custom.theme').'.layouts.main')

@slot('title')
{{ $meta_title ?? 'Hotel'}}
@endslot

@slot('meta_description')
{{ $meta_description ?? 'Hotel'}}
@endslot

@slot('meta_keywords')
{{ $meta_keywords ?? 'Hotel'}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"/>
<style type="text/css">
  .gallery_popup  {position: relative; z-index: 9;  }
  .left_area .gallery_wrapper { height: 100%; }
  .grid_gallery_row .gallery_wrapper:hover .gallery_text{z-index: 10;}
  .grid_gallery_row .gallery_wrapper:hover:after{z-index: 9;}
  .gallery_text, .grid_gallery_row .gallery_wrapper:after{pointer-events: none;}
</style>
@endslot

@slot('bodyClass')
hotel_class
@endslot
<?php
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY']);

$storage = Storage::disk('public');
$accommodation_path = 'accommodations/';
   //prd($packageDetails);
$accommodation_name = $accommodation->name;
$accommodation_brief = $accommodation->brief;
$accommodation_description = $accommodation->description;
$accommodation_slug = $accommodation->slug;
$accommodation_image = $accommodation->image;
$accommodation_star = $accommodation->star_classification;

$accommodationSrc =asset(config('custom.assets').'/images/noimagebig.jpg');

if(!empty($accommodation_image)){
   if($storage->exists($accommodation_path.$accommodation_image)){
      $accommodationSrc = asset('/storage/'.$accommodation_path.$accommodation_image);
   }
}

$accommodation_images = (!($accommodation->accommodationImages->isEmpty())) ? $accommodation->accommodationImages : "";
$accommodationBanners = $accommodation->accommodationBanners??[];

?>

<section class="inner_banner" style="padding:0;">
  @if(!empty($accommodationBanners) && count($accommodationBanners) > 0)
  <div class="header_inner_banner_main">
    <div class="swiper-wrapper">
      @foreach($accommodationBanners as $banner)
      <div class="swiper-slide">
        @if($storage->exists($accommodation_path.$banner->name))
        <img src="{{asset('/storage/'.$accommodation_path.$banner->name)}}" alt="{{$banner->title??''}}" />
        @else
          <img src="{{asset(config('custom.assets').'/images/hotels.jpg')}}" alt="{{$banner->title??''}}" />
        @endif
      </div>
      @endforeach
    </div>
  </div>
  @else
  <div class="inner_banner_main">
    <img src="{{asset(config('custom.assets').'/images/hotels.jpg')}}" alt="{{$accommodation_name??''}}" />
  </div>
  @endif
  @include(config('custom.theme').'/includes/search')
</section>

<div class="breadcrumb_full">
      <div class="container">
         <ul class="breadcrumb">
            <li><a href="{{url('/')}}">Home</a>
            </li>
           <li><a href="{{route('hotelListing')}}">{{$breadcrumb_title}}</a>
            </li>
            <li>{{$accommodation_name??''}}</li>
         </ul>
      </div>
   </div>
<section class="pt-0 pb-0">
   <div class="xl:container xl:mx-auto">
      <div class="detail_title mb-5">
         <div class="title text-3xl font-bold hotel_name">
            <span>{{ $accommodation_name }}</span>
         </div>
      </div>
      <div class="flex flex-wrap">
         <div class="left_side  lg:w-8/12 pr-5">
            <div class="left_side_inner">
               <div class="theme_title pb-0">
                  <div class="title hotel_location">
                     <span class="hotel_name2 pl-5">{{$accommodation->accommodationDestination->name??''}}</span>
                  </div>

                  <ul class="rating_list py-3" rating="{{ $accommodation_star }}">
                              <li>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                                 </li>
                                 <li>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                                 </li>
                             <li>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>

                                 <li>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>

                                 <li>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                           </ul>
                 </div>
                 <div class="right_side">
                  <img src="{{ $accommodationSrc }}" class="theme_radius img_responsive"  alt="" >
               </div>
                  <p class="para_md mt-5">{{ $accommodation_brief }}</p>
                   {!! $accommodation_description !!}

                   <?php
                  if(!empty($accomodation_info)){
                  foreach($accomodation_info as $acc_info){
                        $storage = Storage::disk('public');
                        $accommodation_info_path = "accommodations/";
                        $accommodation_info_image = $acc_info->image;
                     ?>
                  <ul class="description_box detail_info111">
                     <li>
                  <?php if(!empty($acc_info->brief)){ ?>
                     <div class="heading1">
                        <?php echo $acc_info->title; ?>
                     </div>
                     <div class="cosec w-3/5	float-left">

                        <div class="text-sm"><?php echo $acc_info->brief; ?></div>
                     </div>
                     <?php } ?>

                     <?php
                     $accommodationInfoThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                     if(!empty($accommodation_info_image) && $storage->exists($accommodation_info_path.$accommodation_info_image))
                        {
                        $accommodationInfoThumbSrc = asset('/storage/'.$accommodation_info_path.'thumb/'.$accommodation_info_image);
                        ?>
                        <div class="w-2/5 float-right">
                        <a data-fancybox="gallery" rel="group" class="fancybox w-1/3" href="{{$accommodationInfoThumbSrc}}">
                              <div class="imgs"><img src="{{$accommodationInfoThumbSrc}}" alt="<?php echo $acc_info->title; ?>" /></div>
                        </a>
                        </div>
                     <?php } ?>
                     </li>
                  </ul>
                  <div class="clearfix"></div>
                  <?php } } ?>
                  <div class="flex-wrap flex mt-5 w-full">
                   <?php if(!empty($accommodation->address) || !empty($accommodation->contact_phone) || !empty($accommodation->contact_email)){ ?>
                        <div class="tablediv w-1/2 mt-5">

                                    <h4 class="text-2xl font-bold mb-3">For Reservation Contact:</h4>
                                    <?php if(!empty($accommodation->address)){ ?>
                                    <p class="text-sm mb-3"><span class="fa fa-address-card-o" aria-hidden="true"></span>
                                       <?php echo $accommodation->address; ?>
                                    </p>
                                    <?php } ?>

                                    <?php if(!empty($accommodation->contact_phone)){ ?>
                                    <p class="text-sm mb-3"><span class="fa fa-phone-square" aria-hidden="true"></span>
                                       <?php echo $accommodation->contact_phone; ?>
                                    </p>
                                    <?php } ?>
                                    <?php if(!empty($accommodation->contact_email)){ ?>
                                    <p class="text-sm mb-3"><span class="fa fa-envelope" aria-hidden="true"></span>
                                       <?php echo $accommodation->contact_email; ?>
                                    </p>
                                    <?php } ?>
                                    <?php if(!empty($accommodation->map_iframe_src))
                                    {
                                    if (strpos($accommodation->map_iframe_src, 'src=') !== false)
                                    {

                                    }
                                    else
                                    {
                                       ?>
                                       <p class="map-detail">
                                          <iframe src="<?php echo $hotel->map_iframe_src; ?>" style="border:0" allowfullscreen="" width="100%" height="390" frameborder="0"></iframe>
                                       </p>
                                    <?php } }?>
                        </div>
                      <?php } ?>


                  </div>



              </div>
         </div>
         <div class="pull-right lg:w-4/12 pl-5">
            <div class="side_hotel_bar">
               <div class="search_hotel pull-left">
                  <div class="left_form">
                     <form action="/hotels" class="" id="" method="GET" accept-charset="utf-8">
                        <div class="form-inline">
                           <div class="title"> Search Hotel</div>
                           <?php
                           $sdest = (request()->has('destination'))?request()->input('destination'):'';
                           $stars = (request()->has('stars'))?request()->input('stars'):'';
                            ?>
                           <select name="destination" id="" class="form-control" data-placeholder="Select Destination">
                              <option value="">Select Destination</option>
                              {!!CustomHelper::getDestinationOptions('', $sdest,['show_active'=>true])!!}
                           </select>
                           <select name="stars" id="" class="form-control" data-placeholder="Select star">
                              <option value="">Select Stars</option>
                                 <?php
                                 for($i = 1; $i<=5; $i++){
                                 $selected = '';
                                    if($stars == $i){
                                      $selected = 'selected';
                                      }
                                   ?>
                                   <option value="{{$i}}" {{$selected}}>{{$i.' Star'}}</option>
                                   <?php
                                }
                                ?>
                           </select>
                           <div class="pull-right">
                           <input type="submit" value="Search" name="search" class="btn btn-success">
                          </div>
                       </div>
                     </form>
                   </div>
                 </div>
               </div>
               <div class="form_box single_package_form lg:w-full single_hotel">
                         <div class="form_box_inner px-2 py-2">
                            <h2 class="heading2 text-2xl">Book Now</h2>
                           @include('snippets.front.flash')
                            {!!formShortCode(['slug' =>'[hotel_book_now]','class'=>'left_form'])!!}

                         </div>
                      </div>
         </div>

          </div>
       </div>
    </section>
    <?php if(!empty($accommodation_images)){ ?>
      <section class="photo-gallery hotel-gallery pb-12">
       <div class="container">
          <div class="text_center mb40">
          <div class="theme_title mb-5">
            <div class="title text-2xl">Photo Gallery</div>
         </div>
       </div>
      <div class="">
<div id="dest_gallery" class="flex flex-wrap">
         <?php $i=1;
         foreach ($accommodation_images as $aImage) {
            $galleryImage = $aImage->name;
            $galleryTitle = $aImage->title;
            $galleryLink = $aImage->link;

            $gImgSrc =asset(config('custom.assets').'/images/noimagebig.jpg');
            $gThumbImgSrc =asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($galleryImage)){
               if($storage->exists($accommodation_path.$galleryImage)){
                           //$gImgSrc = asset('/storage/'.$accommodation_path.$galleryImage);
                  $gThumbImgSrc = asset('/storage/'.$accommodation_path.'thumb/'.$galleryImage);

                  $gImgSrc = asset('/storage/'.$accommodation_path.$galleryImage);
                           /*if($i==1){
                              $gImgSrc = asset('/storage/'.$destination_path.$galleryImage);
                           }else{
                              $gImgSrc = asset('/storage/'.$destination_path.$galleryImage);
                           }*/
                        }
                     }
                     $i++;
                     ?>
                     
                           <a data-fancybox="gallery" data-caption="<div class='text_center'><strong>{{ $galleryTitle }}</strong><br/>{{ $galleryLink }}</div>" class="gallery_popup" href="{{
                              $gImgSrc  }}">
                              <img src="{{ $gThumbImgSrc }}" alt="" class="img_responsive theme_radius">
                           </a>
                           <div class="gallery_text">
                              <div class="title para_lg0">{{ $galleryTitle }}</div>
                              <p class="para_md">{{ $galleryLink }}</p>
                           </div>
                           
               <?php } ?>
               </div>
                           </div>
         </div>
      </section>
   <?php } ?>
   <?php
   $related_hotels = (!empty($accommodation->related_hotels)) ? json_decode($accommodation->related_hotels,true) : "";

   $relatedAccommodationObj = "";
   if(!empty($related_hotels)){
      $relatedAccommodationObj = App\Models\Accommodation::whereIn('id',$related_hotels)->get();
   }
      //prd($relatedAccommodationObj);
   if(!empty($relatedAccommodationObj) && count($relatedAccommodationObj) > 0){
      ?>
      <section class="" style="background-image: url({{asset(config('custom.assets').'/images/vision-bg.jpg')}}); background-repeat: no-repeat;background-size: cover;">
         <div class="container">
            <div class="text_center">
               <div class="theme_title mb-6">
                  <div class="title text-2xl">Other Partner Hotels</div>
               </div>
         </div>
         <ul class="featured_list">
            <?php
            foreach($relatedAccommodationObj as $relatedHotel){
               $rel_accommodation_name = $relatedHotel->name;
               $rel_accommodation_brief = $relatedHotel->brief;
               $rel_accommodation_description = $relatedHotel->description;
               $rel_accommodation_slug = $relatedHotel->slug;
               $rel_accommodation_image = $relatedHotel->image;

               $rel_accommodationSrc =asset(config('custom.assets').'/images/noimagebig.jpg');

               if(!empty($rel_accommodation_image)){
                  if($storage->exists($accommodation_path.$rel_accommodation_image)){
                     $rel_accommodationSrc = asset('/storage/'.$accommodation_path.$rel_accommodation_image);
                  }
               }
               ?>
               <li data-aos="fade-up" data-aos-duration="2000" class="lg:w-1/3 border-transparent">
                  <a class="featured_box" href="{{route('hotelDetail',['slug' => $rel_accommodation_slug])}}">
                     <div class="images">
                      <img src="{{ $rel_accommodationSrc }}" alt="" class="img_responsive">

                   </div>
                   <div class="featured_content px-3.5 py-3.5">
                     <div class="title heading3">{{ $rel_accommodation_name }}</div>

                     <p class="para_md">{!! $rel_accommodation_brief !!}</p>
                     <!-- <div class="view_all">More Info</div> -->
                  </div>
               </a>
            </li>
         <?php } ?>
      </ul>
   </div>
</section>
<?php } ?>

@slot('bottomBlock')

<script src="https://www.google.com/recaptcha/api.js?render={{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script type="text/javascript">
grecaptcha.ready(function() {
   grecaptcha.execute("{{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}", {action:'validate_captcha'}).then(function(token) {
   // add token value to form
   const element = document.getElementById('g-recaptcha-response');
   if(element) {
    document.getElementById('g-recaptcha-response').value = token;
   }
   });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
   $('.grid_gallery li:nth-child(1) .gallery_wrapper').appendTo('.left_area');
   $('.grid_gallery li:nth-child(2) .gallery_wrapper, .grid_gallery li:nth-child(3) .gallery_wrapper, .grid_gallery li:nth-child(4) .gallery_wrapper, .grid_gallery li:nth-child(5) .gallery_wrapper').appendTo('.right_area');
   $('.grid_gallery li:nth-child(n+6) .gallery_images').appendTo('.bottom_area');

   var tripSlider =  new Swiper(".trip_slider", {
    slidesPerView: 2,
    spaceBetween:80,
    loop: true,
    speed:1000,
    navigation: {
      nextEl: ".trip-prev",
      prevEl: ".trip-next",
   },
});
   var customerSlider = new Swiper(".recommendation_slider", {
     loop: true,
     speed:1000,
     slidesPerView: 3,
     spaceBetween:30,
     navigation: {
      nextEl: ".recommendation-next",
      prevEl: ".recommendation-prev",
   },
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

});
   $('.parallax-window').parallax();

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
@endslot

@endcomponent