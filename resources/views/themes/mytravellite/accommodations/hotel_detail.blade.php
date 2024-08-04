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

@slot('canonical_url')
{{route('hotelDetail',['slug' => $accommodation->slug])?? ''}}
@endslot

@slot('headerBlock')

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"/>
<link rel="stylesheet" href="{{url(''.config('custom.assets').'/css/jquery.fancybox.css')}}">
<script src="{{url(''.config('custom.assets').'/js/jquery.fancybox.js')}}"></script>

<style type="text/css">
   .gallery_popup  {position: relative; z-index: 9;}
   .left_area .gallery_wrapper { height: 100%; }
   .grid_gallery_row .gallery_wrapper:hover .gallery_text{z-index: 10;}
   .grid_gallery_row .gallery_wrapper:hover:after{z-index: 9;}
   .gallery_text, .grid_gallery_row .gallery_wrapper:after{pointer-events: none;}
   .single_package_info {padding-bottom: 0px;}
   html {scroll-padding: 150px;}
   /* body.home.hotel_class.headeradd {
    background: #e8f3ff;
} */
   .main-header .head-search form {max-width: inherit;}
   .main-header .head-search form#search_hotels_form h3 {display: none;}
   section.single_package_details .grid_gallery_row .right_area:before {
      content: '';
      position: absolute;
      bottom: 0.5rem;
      /* left: 0.3rem; */
      right: 0.3rem;
      /* background: linear-gradient(180deg,#0000,#000000bf); */
      height: 65px;
      z-index: 9;
      width: 17.2rem;
   }
/* .compact-room-img .border2:before {
    content: '';
    position: absolute;
    left: 53.12%;
    border: 1px solid #CBD5E1;
    top: 0;
    bottom: 0;
} */
.theme_form.allbooking_form, .fancybox-wrap.fancybox-desktop.fancybox-type-html.fancybox-opened {max-width: 75rem; width:100% !important;}
.fancybox-inner {width: auto !important;}


@media (max-width:767px){
.fancybox-wrap.fancybox-opened {
    margin-top: 5rem;
}
.fancybox-wrap.fancybox-type-html.fancybox-opened .theme_form_inner .left_sec, .fancybox-wrap.fancybox-type-html.fancybox-opened .theme_form_inner .right_sec {
    padding-right: 0;
}
.fancybox-wrap.fancybox-type-html.fancybox-opened section.theme_form .container {padding-left: 0;}
.fancybox-wrap.fancybox-type-html.fancybox-opened .theme_form_inner .form_price {min-height: 68rem;}
.fancybox-wrap.fancybox-type-html.fancybox-opened .theme_form_inner .left_sec h3.text-2xl.pt-3 {padding: 0px 5px 5px;}
}
</style>
<style>
   .sticky_header #search_hotels_form { position: fixed; z-index: 99; background: #009688; width: 100%; max-width: 100%; left: 0; top: 4rem; padding: 10px 25px; box-shadow: -1px 3px 12px #0000001f;color: #fff; }
   .sticky_header #search_hotels_form h3 { display: none; }
   .sticky_header #search_hotels_form .header-top-search { max-width: 750px; margin: auto; }
   .sml-header {box-shadow: none;}
  </style>
@endslot
@slot('bodyClass')
hotel_class
@endslot
<?php
   
   
   $storage = Storage::disk('public');
   $accommodation_path = 'accommodations/';
      //prd($packageDetails);
   $accommodation_id = $accommodation->id;
   $accommodation_name = $accommodation->name;
   $accommodation_brief = $accommodation->brief;
   $accommodation_description = $accommodation->description;
   $accommodation_slug = $accommodation->slug;
   $accommodation_image = $accommodation->image;
   $accommodation_star = $accommodation->star_classification;
   
   /*$accommodationSrc =asset(config('custom.assets').'/images/noimagebig.jpg');
   if(!empty($accommodation_image)){
      if($storage->exists($accommodation_path.$accommodation_image)){
         $accommodationSrc = asset('/storage/'.$accommodation_path.$accommodation_image);
      }
   }*/
   
   $accommodation_images = (!($accommodation->accommodationImages->isEmpty())) ? $accommodation->accommodationImages : "";
   $accommodationBanners = $accommodation->accommodationBanners??[];
   $b_image =asset(config('custom.assets').'/images/hotels.jpg');
   if($banner_image) {
    $b_image = $banner_image;
   }

   $AccommodationRooms = $accommodation->AccommodationRooms??[];
   $accommodation_facilities_arr = $accommodation->accommodation_facility?json_decode($accommodation->accommodation_facility):[];
   $accommodation_features_arr = $accommodation->accommodation_feature?json_decode($accommodation->accommodation_feature):[];

   $map_src = CustomHelper::fetchIframeSrc($accommodation->map);
   ?>
<section class="inner_banner">
   <div class="inner_banner_main">
      <img src="{{$b_image}}" alt="{{$accommodation_name}}" />
      @include(config('custom.theme').'/includes/search')
   </div>
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
      
      <div class="flex flex-wrap">
         <div class="left_side w-full">
            <div class="left_side_inner">

               <?php /* <div class="theme_title pb-1">
                  <div class="title hotel_location">
                     <span class="hotel_name2 pl-5">{{$accommodation->AccommodationDefaultLocation ? $accommodation->AccommodationDefaultLocation->DestinationLocation->name.', ':''}} {{$accommodation->accommodationDestination->name??''}}</span>
                  </div>
                  <ul class="rating_list py-3" rating="{{ $accommodation_star }}">
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
                     <li>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                           <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                        </svg>
                     <li>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                           <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                        </svg>
                  </ul>
               </div> */ ?>

               <?php /*<div class="right_side">
                  <img src="{{ $accommodationSrc }}" class="theme_radius img_responsive"  alt="" >
               </div>*/ ?>


              <section class="single_package_details p-0">
               <div class="single_package_info row w-full">
                  <div class="w-full">
                     <div class="grid_gallery_row">
                     <div class="left_area w-1/2">
                     </div>
                     <div class="right_area w-1/2">
                     </div>
                     <?php
                        $gImgSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                        if(!empty($accommodation_images) && count($accommodation_images) > 5){
                           $pImage = $accommodation_images[4];
                           $galleryImage = $pImage->name;
                           $galleryTitle = $pImage->title;
                           $galleryLink = $pImage->link;
                           $gThumbImgSrc = asset(config('custom.assets').'/images/noimage.jpg');
                           if(!empty($galleryImage)){
                              if($storage->exists($accommodation_path.$galleryImage)){
                                 $gThumbImgSrc = asset('/storage/'.$accommodation_path.'thumb/'.$galleryImage);
                                 $gImgSrc = asset('/storage/'.$accommodation_path.$galleryImage);
                              }
                           }
                           ?>
                        <div class="view-more-img">
                           <!-- <a data-fancybox="main_gallery" data-caption="<div class='text_center'><strong>{{$galleryTitle}}</strong><br/>{{$galleryLink}}</div>" class="gallery_popup" href="{{$gImgSrc}}"> -->
                             <div class="view-more-text">See all photos <!--<span>{{count($accommodation_images)-5}}</span>--></div>
                             <!-- <img src="{{$gThumbImgSrc}}" alt="" class="img_responsive theme_radius" style="display: none;"> -->
                          </a>
                        </div>
                        <?php } ?>
                     <div class="bottom_area" >
                     </div>
                  </div>
                  <?php if(!empty($accommodation_images) && count($accommodation_images) > 0){ ?>
                  <ul class="grid_gallery">
                     <?php $i=1;
                     foreach ($accommodation_images as $pImage) {
                        $galleryImage = $pImage->name;
                        $galleryTitle = $pImage->title;
                        $galleryLink = $pImage->link;
                        
                        $gImgSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                        $gThumbImgSrc = asset(config('custom.assets').'/images/noimage.jpg');
                        if(!empty($galleryImage)){
                           if($storage->exists($accommodation_path.$galleryImage)){
                              if($i == 1) {
                                 $gThumbImgSrc = asset('/storage/'.$accommodation_path.$galleryImage);
                              } else {
                                 $gThumbImgSrc = asset('/storage/'.$accommodation_path.'thumb/'.$galleryImage);
                              }

                              $gImgSrc = asset('/storage/'.$accommodation_path.$galleryImage);
                           }
                        }
                                 $i++;
                                 ?>
                                 <li>
                                 <div class="gallery_images">
                                    <div class="gallery_wrapper w-1/2 ">
                                       <a data-fancybox="main_gallery" data-caption="<div class='text_center'><strong>{{ $galleryTitle }}</strong><br/>{{$galleryLink}}</div>" class="gallery_popup" href="{{$gImgSrc}}">
                                          <img src="{{$gThumbImgSrc}}" alt="{{ $galleryTitle }}" class="img_responsive theme_radius">
                                       </a>
                                       <div class="gallery_text">
                                          <div class="title para_lg0">{{ $galleryTitle }}</div>
                                          <p class="para_md">{{ $galleryLink }}</p>
                                       </div>
                                    </div>
                                 </div>
                              <?php }?>
                              </li>
                           <?php } ?>
                        </ul>
                     <div class="left_side_inner">
                     </div>
                  </div>
                  
               </div>
               </section>


               <div class="hotel-wrapper-content-tab mb-3">
                  <ul class="tabs group border border-slate-20">
                     <li><a href="javascript:void(0)" class="active nav" data-id="content_overview">Overview</a></li>
                     <?php
                     /*if(!empty($accomodation_info)){
                        foreach($accomodation_info as $acc_info){
                           $storage = Storage::disk('public');
                           $accommodation_info_path = "accommodations/";
                           $accommodation_info_image = $acc_info->image;
                     ?>
                     <li><a href="javascript:void(0)" class="nav" data-id="content_{{$acc_info->id}}">{{$acc_info->title}}</a></li>
                     <?php } }*/ ?>
                     <?php /*if(!empty($accommodation_images)){ ?>
                     <?php <li><a href="javascript:void(0)" data-id="content_photo_gallery">Photo Gallery</a></li> ?>
                     <?php }*/ ?>

                     

                     <?php if(!empty($accommodation_features_arr)){ ?>
                     <li><a href="#content_features">Highlights</a></li>
                     <?php } ?>

                     <?php if(!empty($accommodation_facilities_arr)){ ?>
                     <li><a href="#content_facilities">Facilities</a></li>
                     <?php } ?>

                     <?php if(!empty($AccommodationRooms) && count($AccommodationRooms) > 0){ ?>
                     <li><a href="#content_rooms">Rooms</a></li>
                     <?php } ?>

                     <?php if($map_src){ ?>
                     <li><a href="#content_location">Location</a></li>
                     <?php } ?>

                     <?php if(!empty($accommodation->policies)){ ?>
                     <li><a href="#content_policies">Policies</a></li>
                     <?php } ?>
                  </ul>
               
                  <div id="content">
                        <div class="content content_overview">
                           <div class="detail_title mb-5 border border-slate-20">
                             <div class="py-1 px-5 pt-3">
                                <h1 class="text-2xl flex font-bold hotel_name">
                                  <span>{{ $accommodation_name }}</span>
                                  <ul class="rating_list py-3 ml-1" rating="{{ $accommodation_star }}">
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
                                       <li>
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                             <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                                          </svg>
                                          <li>
                                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                                             </svg>
                                          </ul>
                                       </h1>
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
                                       <div class="title hotel_location">
                                          <span class="hotel_name2 pl-3 text-sm">{{implode(', ',$name_arr)}}</span>
                                       </div>
                                       <?php } ?>
                                    </div>
                                    <div class="hotel-brief"><p class="text-sm">{{ $accommodation_brief }}</p></div>   
                                 </div>

                           <!-- <div class="title">This is a Home content</div> -->
                           <!-- <p class="text-sm">{{ $accommodation_brief }}</p> -->
                           <!-- {!! $accommodation_description !!} -->
                        

                        <?php
                        if(!empty($accomodation_info)){
                           foreach($accomodation_info as $acc_info){
                              $storage = Storage::disk('public');
                              $accommodation_info_path = "accommodations/";
                              $accommodation_info_image = $acc_info->image;
                        ?>
                        <div class="content content_{{$acc_info->id}}" style="display: block;">
                           <ul class="description_box detail_info111">
                              <li>
                                 <?php if(!empty($acc_info->brief)){ ?>
                                    <div class="cosec w-2/3 float-left">
                                    <div class="heading1">
                                       <?php echo $acc_info->title; ?>
                                    </div>
                                       <div class="text-sm"><?php echo $acc_info->brief; ?></div>
                                    </div>
                                 <?php } ?>
                                 <?php
                                 $accommodationInfoThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                                 if(!empty($accommodation_info_image) && $storage->exists($accommodation_info_path.$accommodation_info_image))
                                 {
                                    $accommodationInfoThumbSrc = asset('/storage/'.$accommodation_info_path.'thumb/'.$accommodation_info_image);
                                    ?>
                                    <div class="w-1/3 float-right">
                                       <a data-fancybox="{{$acc_info->title??''}}" rel="group" class="fancybox w-1/3" href="{{$accommodationInfoThumbSrc}}">
                                          <div class="imgs"><img src="{{$accommodationInfoThumbSrc}}" alt="<?php echo $acc_info->title; ?>" /></div>
                                       </a>
                                    </div>
                                 <?php } ?>
                              </li>
                           </ul>
                        </div>
                        <?php } } ?>
                        </div>

                        <?php /* if(!empty($accommodation_images)){  
                        <div class="content content_photo_gallery" style="display: none;">
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
                                                         }
                                                      }
                                                      $i++; 
                                                      
                                       <a data-fancybox="gallery" data-caption="<div class='text_center'><strong>{{ $galleryTitle }}</strong><br/>{{ $galleryLink }}</div>" class="gallery_popup" href="{{
                                          $gImgSrc  }}">
                                       <img src="{{ $gThumbImgSrc }}" alt="" class="img_responsive theme_radius">
                                       </a>
                                       <div class="gallery_text">
                                          <div class="title para_lg0">{{ $galleryTitle }}</div>
                                          <p class="para_md">{{ $galleryLink }}</p>
                                       </div>
                                       <?php }  
                                    </div>
                                 </div>
                              </div>
                           </section>
                        </div>
                        <?php } */ ?>

                        <?php if(!empty($accommodation_features_arr)){ ?>
                        <div id="content_features">
                           <div class="facility-highlights-item">
                              <div class="highlights_facility_top mb-3 border border-slate-20 p-3">
                                 <h3 class="font-semibold pb-3">Highlights</h3>
                                 <ul class="flex highlights_facility">
                                    <?php foreach($accommodation_features_arr as $feature_id){ ?>
                                       <li>
                                          <img src="{{CustomHelper::showAccommodationFeature($feature_id,'icon')}}" >
                                          <span>{{CustomHelper::showAccommodationFeature($feature_id)}}</span>
                                       </li>
                                    <?php } ?>
                                 </ul>
                              </div>                              
                           </div>
                        </div>
                        <?php } ?>

                        <?php if(!empty($accommodation_facilities_arr)){ ?>
                        <div id="content_facilities">
                           <div class="highlights_facility_bottom border border-slate-20 p-3">
                              <h3 class="font-semibold pb-3">Facilities</h3>
                              <ul class="flex">
                                 <?php foreach($accommodation_facilities_arr as $facility_id){ ?>
                                    <li><svg width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="SvgIconstyled__SvgIconStyled-sc-1i6f60b-0 RBeKP"><path d="M21.453 4.487l1.094 1.026a.5.5 0 0 1 .023.707L10.412 19.188a1.25 1.25 0 0 1-1.692.122l-.104-.093-7.146-7.146a.5.5 0 0 1 0-.707l1.06-1.061a.5.5 0 0 1 .707 0l6.234 6.234L20.746 4.51a.5.5 0 0 1 .707-.023z"></path></svg> {{CustomHelper::showAccommodationFacility($facility_id)}}</li>
                                 <?php } ?>
                              </ul>
                           </div>
                        </div>
                        <?php } ?>

                  </div>               
               </div>

               @if(!empty($AccommodationRooms) && count($AccommodationRooms) > 0)
               <div id="content_rooms" class="room_type">
                  <ul class="">
                     <!-- <li class="flex">
                        <div class="compact-room-img w-2/5">
                           <div class="room-img-title bg-blue-200 p-2 font-semibold">Room Type</div>
                        </div>
                        <div class="room-options w-2/3">
                           <div class="room-options-title bg-blue-200 font-semibold border-x-1 border-white border-x p-2">Room Options</div>
                        </div>
                        <div class="room-type-price w-1/4">
                           <div class="room-price-title bg-blue-200 font-semibold p-2">Price</div>
                        </div>
                     </li>                   -->

                     <li class="flex">
                        <div class="compact-room-img overflow-hidden w-5/12">
                           <div class="">
                              <div class="room-img-title bg-blue-200 p-2 font-semibold">Room Type</div>
                           </div>
                        </div>

                        <div class="room-options w-2/12">
                           <div class="">
                              <div class="room-img-title bg-blue-200 p-2 font-semibold">Sleeps</div>
                           </div>
                        </div>
                        <div class="room-options w-5/12">
                           <div class="repeater flex justify-between flex-row">
                          
                              <div class="plan_options w-2/4">
                                 <div class="flexa">
                                    <div class="room-options-title bg-blue-200 font-semibold p-2">Room Options</div>
                                 </div>
                              </div>
                              <div class="plan_price w-2/4">
                                 <div class="room-options-title bg-blue-200 font-semibold border-x-0 border-white border-x p-2">Price</div>
                              </div>
                           </div>
                        </div>
                     </li>

                     @foreach($AccommodationRooms as $room)
                        @include(config('custom.theme').'/accommodations/_hotel_detail_rooms_li',['room'=>$room,'checkin'=>$checkin,'checkout'=>$checkout,'inventory'=>$inventory])
                     @endforeach
                  </ul>
               </div>
               @endif

               <div class="flex-wrap flex bg-white mt-5 mb-5 w-full" id="content_location">
                  <?php if(!empty($accommodation->address) || !empty($accommodation->contact_phone) || !empty($accommodation->contact_email)){ ?>
                  <div class="tablediv w-full">
                     <div class="hide">
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
                     </div>

                     <?php if($map_src) { ?>
                     <?php /*<p class="map-detail">
                        {!!$accommodation->map??''!!}
                     </p>*/ ?>                     
                     <p class="map-detail">
                        <iframe src="{{$map_src}}" style="border:0" allowfullscreen="" width="100%" height="390" frameborder="0"></iframe>
                     </p>
                     <?php } ?>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="pull-right w-1/4 pl-5" style="display:none;">
            <div class="side_hotel_bar" style="display:none;">
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

<?php
   $related_hotels = (!empty($accommodation->related_hotels)) ? json_decode($accommodation->related_hotels,true) : "";
   $destination_id = $accommodation->destination_id ?? 0; 
   
   $relatedAccommodationObj = "";
   if(!empty($related_hotels)){
      $relatedAccommodationObj = App\Models\Accommodation::whereIn('id',$related_hotels)->where('id','!=',$accommodation_id)->limit(8)->get();
   }
   // prd($relatedAccommodationObj);
   if(empty($relatedAccommodationObj)){
     $relatedAccommodationObj = App\Models\Accommodation::where('destination_id',$destination_id)->where('id','!=',$accommodation_id)->limit(8)->get();
   
   }
   // prd($relatedAccommodationObj);
   if(!empty($relatedAccommodationObj) && count($relatedAccommodationObj) > 0){
   ?>

@if($accommodation->policies)

<section class="hotelpolicy" id="content_policies">
   <div class="container">
      <div class="hotelpolicy_row">
      <div class="border border-slate-20 p-3">
       <div class="title text-2xl">Property Policies</div>
       <div class="text-sm pt-2">
         {!!$accommodation->policies ?? ''!!}
       </div>
       </div>
      </div>
   </div>
</section>
@endif


<section class="related-hotel" style="background:#fff;">
   <div class="container">
      <div class="text_center ml-2">
         <div class="theme_title mb-6">
            <div class="title text-2xl">Other Hotels in 
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
               if(!empty($name_arr)) { echo implode(', ',$name_arr); }
               ?>
            </div>
         </div>
      </div>
      <ul class="featured_list flex flex-wrap">
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
         <li class="w-64 border-transparent mt-0 m-2">
            <a class="featured_box" href="{{route('hotelDetail',['slug' => $rel_accommodation_slug])}}">
               <div class="images">
                  <img src="{{ $rel_accommodationSrc }}" alt="" class="img_responsive">
               </div>
               <div class="featured_content px-3.5 py-3.5">
                  <div class="title text-lg">{{ $rel_accommodation_name }}</div>
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

   $(document).on('click','.book-room-btn',function(e){
      e.preventDefault();
      e.stopPropagation();
      var checkin = "{{date('d/m/Y',strtotime($checkin))}}";
      var inventory = "{{$inventory}}";
      var adult = "{{$adult}}";
      var child = "{{$child}}";
      var infant = "{{$infant}}";
      var checkout = "{{date('d/m/Y',strtotime($checkout))}}";
      var inventory_id = $(this).attr('data-inventory_id');
      // alert(inventory);
      if(inventory_id) {
         var action = 'hotel_booking';
         var _token = '{{ csrf_token() }}';
         $.ajax({
            type        : "POST",
            url         : "/book_now",
            cache       : false,
            data        : {inventory_id, action, checkin, checkout, inventory, adult, child, infant},
            headers:{'X-CSRF-TOKEN': _token},
            success: function(data) {
               $.fancybox(data);
            },
            error: function(e) {
               var response = e.responseJSON;
               if(response) {
                  if(response.message) {
                     alert(response.message);
                  } else {
                     alert('Something went wrong, please try again!');
                  }
                  // parseBookingErrorMessages(response, 'myForm', 'Book Now');
               }
            }
         });
      } else {
         alert('Please select inventory!')
      }
      return false;
   });

   <?php /*$(document).bind("#myForm submit", function(e) {
    e.preventDefault();
    e.stopPropagation();
    var _this1 = $('.right_side').find('#myForm');
    $('.right_side #trip_date_error').html('');
    // $('html, body').animate({scrollTop:0}, '300');
    var trip_date = $(".right_side #trip_date").val();
    var z = true;
    var trip_date=$(".right_side #trip_date").val().trim();

    if(trip_date=="") {
      $('.right_side #trip_date_error').html('Date is required !');
      z=false;
    }
    if(trip_date!=""){
      if(isValidDate(trip_date)){
        var z = true;
      }
      else{
        $('.right_side #trip_date_error').html('Date format is invalid !');
        z=false;
      }
    }

    if(!z) {
      return false;
    }

    var _token = '{{ csrf_token() }}';
    $.ajax({
      type        : "POST",
      url         : "/book_now",
      cache       : false,
      data        : _this1.serializeArray(),
      headers:{'X-CSRF-TOKEN': _token},
      success: function(data) {
        $.fancybox(data);
      },
      error: function(e) {
        var response = e.responseJSON;
        if(response) {
          parseBookingErrorMessages(response, 'myForm', 'Book Now');
        }
      }
    });
    return false;
  });

  function parseBookingErrorMessages(response, formID, boxText) {
    if(response.errors) {
      var errors = response.errors;
      var message='';
      $.each(errors, function (key, val) {
        $('#'+formID).find("#" + key + "_error").text(val[0]);
      });
    }
  }*/ ?>

  function isValidDate(date) {
    var temp = date.split('/');
    var d = new Date(temp[1] + '/' + temp[0] + '/' + temp[2]);
    return (d && (d.getMonth() + 1) == temp[1] && d.getDate() == Number(temp[0]) && d.getFullYear() == Number(temp[2]));
  }



</script>
<script>
// =======Images Thumb Banner Gallery =====
$('.compact-room-img').each(function(){
   var thumbSlider = $(this).find('.roomgallery')[0];
   var mainSlider = $(this).find('.roomgallery2')[0];

   var nextButton = $(this).find('.swiper-button-next')[0];
   var prevButton = $(this).find('.swiper-button-prev')[0];

   var thumbSwiper = new Swiper(thumbSlider, {
      loop: false,
      protect: true,
      spaceBetween: 3,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });
    var mainSwiper = new Swiper(mainSlider, {
      loop: false,
      protect: true,
      spaceBetween: 5,
      navigation: {
        nextEl: nextButton,
        prevEl: prevButton,
      },
      thumbs: {
        swiper: thumbSwiper,
      },
    });

})
 

 

</script>

@endslot
@endcomponent