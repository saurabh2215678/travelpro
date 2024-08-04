@component(config('custom.theme').'.layouts.main')

@slot('title')
{{$meta_title}} 
@endslot

@slot('meta_description')
{{ $meta_description ?? '' }}
@endslot

@slot('meta_keyword')
{{ $meta_keyword ?? '' }}
@endslot

    @slot('headerBlock')
    <style type="text/css">
       .gallery_popup  {position: relative; z-index: 9;  }
       .left_area .gallery_wrapper { height: 100%; }
       .grid_gallery_row .gallery_wrapper:hover .gallery_text{z-index: 10;}
       .grid_gallery_row .gallery_wrapper:hover:after{z-index: 9;}
       .gallery_text, .grid_gallery_row .gallery_wrapper:after{pointer-events: none;}
    </style>
    @endslot

    @slot('bodyClass')
        experiences
    @endslot

   <?php
  // prd($popular_packages);
      $storage = Storage::disk('public');
      $package_path = 'packages/';
      $destination_path = 'destinations/';
      //prd($btDestination);
      $destination_images = (!($destinationDetails->destinationImages->isEmpty())) ? $destinationDetails->destinationImages : "";
      $destination_name = $destinationDetails->name;
      $destination_banner_image = $destinationDetails->banner_image;
      $destinationThumbSrc =asset(config('custom.assets').'/images/noimagebig.jpg');

   if(!empty($destination_banner_image)){
      if($storage->exists($destination_path.$destination_banner_image)){
         $destinationThumbSrc = asset('/storage/'.$destination_path.$destination_banner_image);
      }
   }
   

   $SALES_EMAIL =CustomHelper::WebsiteSettings('SALES_EMAIL');

   ?>

      <?php 
$storage = Storage::disk('public');
$path = 'banners/';
$b_image =asset(config('custom.assets').'/images/noimage.jpg');
// dd($banners);
foreach($banners as $banner){
  $images = (isset($banner->Images))?$banner->Images:'';
  // prd($images->toArray());
  if(!empty($images) && count($images) > 0){
    foreach($images as $image){
      if(!empty($image->image_name) && $storage->exists($path.$image->image_name)){

        $b_image = url('/storage/banners/'.$image->image_name);
      }
    }
  }
}
    ?>
   <section class="inner_banner">
      <div class="inner_banner_main">
         <!-- <img src="{{ $destinationThumbSrc }}" alt="{{$destination_name}}" > -->
         <img src="{{ $b_image }}" alt="Banner" >
      </div>
   </section>
   <form action="{{ route('packageListing') }}" method="GET" name="package_search">
   <div class="enquiry_search">
      <div class="container">
         <h1 class="title para_lg2">
         Start your Trip here...
         </h1>
         <ul class="search_list">
               <li>
                  <div class="form_group">
                     <div class="custom_select">
                     <select class="form_control" name="destination" id="destination">
                        <option value="">Destination</option>
                        <?php if(!($destinations->isEmpty())){
                           $parent_destinations = $destinations->where('parent_id', 0);
                           if(!($parent_destinations->isEmpty())){
                        ?>
                        @foreach($parent_destinations as $destination_it)
                        <option value="{{$destination_it->id}}"
                           @if($destination_it->id == 21) selected 
                           @endif
                           >{{ $destination_it->name }}</option>
                        @endforeach
                     <?php }}?>
                     </select>
                  </div>
               </div>
               </li>
               <li>
                  <div class="form_group">
                     <div class="custom_select">
                        <select class="form_control" name="sub_destination" id="sub_destination">
                           <option value="">All Locations</option>
                        </select>
                     </div>
                  </div>
               </li>               
               <li>
                  <div class="form_group">
                     <div class="custom_select">
                     <select class="form_control" name="categories">
                        <option value="">Theme/Categories</option>
                        <?php if(!($themes->isEmpty())){?>
                        @foreach($themes as $them_cat)
                        <option value="{{$them_cat->id}}">{{ $them_cat->title}}</option>
                        @endforeach
                     <?php } ?>
                     </select>
                  </div>
               </div>
               </li>
               <li>
                  <div class="form_group">
                     <div class="custom_select">
                     <select class="form_control" name="month">
                        <option value="">Months</option>
                        <?php
                        $monthsArr = config('custom.months_arr');
                        if(!empty($monthsArr)){ ?>
                            <?php
                            foreach ($monthsArr as $month_id=>$month){
                            ?>
                            <option value="{{$month_id}}">{{$month}}</option>
                        <?php }}?>
                     </select>
                  </div>
                  </div>
               </li>
               <li>
                  <button type="submit" class="btn">Search</button>
               </li>
               <li><a href="{{ route('packageListing') }}" class="advanced_btn">Advanced search</a> </li>
         </ul>
      </div>
   </div>
   </form>

   <section>
      <div class="container">
         <div class="text_center">
            <div class="theme_title">
               <div class="title">
                  Our most popular Discovery tours
               </div>
               <div class="icon mt15">
                  <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}" alt="featured-icon">
               </div>
            </div>
         </div>
         <?php if(!$popular_packages->isEmpty()){ ?>
         <ul class="featured_list">
            <?php 
               foreach ($popular_packages as $package) {
                  $package_id = $package->id;
                  $package_title = CustomHelper::wordsLimit($package->title);
                  $package_brief = CustomHelper::wordsLimit($package->brief);
                  $package_slug = $package->slug;
                  $package_duration = $package->package_duration;
                  $package_image = $package->image;

                  $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

                  if(!empty($package_image)){
                     if($storage->exists($package_path.$package_image)){
                        $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package_image);
                     }
                  }

            ?>
            <li data-aos="fade-up" data-aos-duration="2000">
               <div class="featured_box">
                  <div class="images">
                     <img src="{{$packageThumbSrc}}" alt="{{$package_title}}">
               
                  </div>
                  <a class="featured_content" href="{{route('packageDetail',['slug'=>$package_slug])}}" style="background-image: url({{asset(config('custom.assets').'/images/featured-bg.jpg')}});">
                     @if(auth()->check())
                     <div class="wishlist_btn">
                     <span id="favid-{{ $package_id }}" class="pkg_fav pkg_fav_{{ $package_id }} {{ (isset($package_fab[$package_id])) ? 'pkg_fav_clicked liked_btn' : 'pkg_fav' }} " >
                     <img class="wishlist" src="{{asset(config('custom.assets').'/images/wishlist.png') }}" alt="Wishlist">
                     <img class="wishlist_active" src="{{asset(config('custom.assets').'/images/wishlist-active.png') }}" alt="Wishlist">
                     </span>
                     </div>
                     @endif
                     <!-- @if(auth()->check())
                     <div class="wishlist_btn">
                     <span id="favid-{{ $package_id }}" class="pkg_fav pkg_fav_{{ $package_id }} {{ $package->users_fav_pack ? 'pkg_fav_clicked liked_btn' : 'pkg_fav' }} " >
                     <img class="wishlist" src="{{asset(config('custom.assets').'/images/wishlist.png') }}" alt="">
                     <img class="wishlist_active" src="{{asset(config('custom.assets').'/images/wishlist-active.png') }}" alt="">
                     </span>
                     </div>
                     @endif -->
                     <div class="title heading3">{{ $package_title }}</div>
                     <div class="duration">{{$package_brief}}</div>
                     <p class="para_md">{{$package_duration}}</p>
                     <div class="view_all">View Detail</div>
                  </a>
               </div>
               <!-- @if(auth()->check())
               <span id="favid-{{ $package_id }}" class="pkg_fav pkg_fav_{{ $package_id }}" >Favourite</span>
               @endif -->
            </li>
            <?php } ?>
         </ul>
      <?php }else{ ?>
         <div class="no_data text_center">
         <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<lottie-player src="{{asset(config('custom.assets').'/images/no-pack.json')}}"  background="transparent"  speed="1"  style="width: 300px; height: 222px;"  loop autoplay></lottie-player>
          <div class="text">  Currently there are no Packages for this Destination!</div>
          <div class="text">Kindly contact our sale team <a href="{{$SALES_EMAIL}}">({{$SALES_EMAIL}}) </a> for more information.</div>
         </div>
      <?php } ?>
      </div>
   </section>

   <?php 
      //$widgetDetail = CustomHelper::getWidget('experience-home-page-widget');
      //if(!empty($widgetDetail)){
         // $storage = Storage::disk('public');
         // $path = 'widgets/';
         // $section_heading = $widgetDetail->section_heading;
         // $description = $widgetDetail->description;
         // $image1 = $widgetDetail->image1;
         
         // $widgetSrc1 =asset(config('custom.assets').'/images/noimage.jpg');
         
         // if(!empty($image1)){
         //    if($storage->exists($path.$image1)){
         //       $widgetSrc1 = asset('/storage/'.$path.$image1);
         //    }
         // }
      $section_heading = $destinationDetails->name;
      $description = $destinationDetails->description;
      $destination_image = $destinationDetails->image;

      $destinationSrc =asset(config('custom.assets').'/images/noimage.jpg');

      if(!empty($destination_image)){
         if($storage->exists($destination_path.$destination_image)){
         $destinationSrc = asset('/storage/'.$destination_path.$destination_image);
         }
      }
   ?>
   <section>
      <div class="container">
         <div class="text_center">
            <div class="theme_title">
               <div class="title">Gateways into
                  {{ $section_heading }} 
               </div>
               <div class="icon mt15">
                  <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
               </div>
            </div>
         </div>
         <div class="gateways_grid">
            <div class="left_side">
               <div class="left_side_inner">
                  <div class="para_lg2">
               
                 {!! $description !!}

               </div>
            </div>
         </div>
            <div class="right_side">
               <img src="{{ $destinationSrc }}" class="theme_radius img_responsive"  alt="">
            </div>
         </div>
      </div>
   </section>
<?php //} ?>
<?php if(!empty($btDestination) && $btDestination->count() > 0){ ?>
   <section class="recommendation_place" style="background-image: url({{asset(config('custom.assets').'/images/vision-bg.jpg')}});">
      <div class="container">
      <div class="text_center mb50">
         <div class="theme_title">
            <div class="title">
               Must See Locations
            </div>
            <div class="icon mt15">
               <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
            </div>
         </div>
      </div>
      <div class="slider_box">
         <div class="swiper recommendation_slider">
           
            <div class="swiper-wrapper">
                <?php
            //prd($destination->children);
               foreach ($btDestination as $destination) {
                  $storage = Storage::disk('public');
                  $destination_path = "destinations/";
                  $destination_name = $destination->name;
                  $destination_id = $destination->parent_id;
                  $sub_destination_id = $destination->id;
                  $destination_slug = $destination->slug;
                  $destination_brief = CustomHelper::wordsLimit($destination->brief);
                  $destination_image = $destination->image;

                  $destinationSrc =asset(config('custom.assets').'/images/noimage.jpg');

                  if(!empty($destination_image)){
                  if($storage->exists($destination_path.$destination_image)){
                     $destinationSrc = asset('/storage/'.$destination_path.$destination_image);
                  }
               }
               ?>   
               <div class="swiper-slide">
               
                  <a class="hotel_box" href="{{route('packageListing',['destination'=>$destination_id,'sub_destination'=>$sub_destination_id])}}">
                     <div class="images">
                        <img src="{{ $destinationSrc }}" alt="">
                     </div>
                     <div class="hotel_content">
                        <div class="title heading_sm3">{{ $destination_name }}</div>
                        <p class="para_md">{{ $destination_brief }}</p>
                     </div>
                  </a>
               </div>
            <?php } ?>
            </div>
         </div>
         <div class="slider_btns">
            <div class="recommendation-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/next-sm.png')}}" alt=""></div>
            <div class="recommendation-next theme-next"><img src="{{asset(config('custom.assets').'/images/prev-sm.png')}}" alt=""></div>
         </div>
      </div>
   </section>
<?php } ?>
<?php if(!empty($destination_images)){ ?>
   <section class="recommendation_place">
      <div class="container">
         <div class="text_center mb50">
            <div class="theme_title">
               <div class="title">
                  Random Good Pictures of {{ $destinationDetails->name}}
               </div>
               <div class="icon mt15">
                  <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
               </div>
            </div>
         </div>
         <div class="grid_gallery_row">
            <div class="left_area">
            </div>
            <div class="right_area">
            </div>
            <div class="bottom_area" style="display:none;" >
            </div>
         </div>
         <ul class="grid_gallery">
            <?php $i=1;
                foreach ($destination_images as $dImage) {
                     $galleryImage = $dImage->name;
                     $galleryTitle = $dImage->title;
                     $galleryLink = $dImage->link;
                     
                     $gImgSrc =asset(config('custom.assets').'/images/noimagebig.jpg');
                     $gThumbImgSrc =asset(config('custom.assets').'/images/noimage.jpg');
                     if(!empty($galleryImage)){
                        if($storage->exists($destination_path.$galleryImage)){
                           //$gImgSrc = asset('/storage/'.$accommodation_path.$galleryImage);
                           $gThumbImgSrc = asset('/storage/'.$destination_path.'thumb/'.$galleryImage);

                           $gImgSrc = asset('/storage/'.$destination_path.$galleryImage);
                           /*if($i==1){
                              $gImgSrc = asset('/storage/'.$destination_path.$galleryImage);
                           }else{
                              $gImgSrc = asset('/storage/'.$destination_path.$galleryImage);
                           }*/
                        }
                     }
                     $i++;
                  ?>
            <li>
               <div class="gallery_images">
                  <div class="gallery_wrapper">
                  <a data-fancybox="gallery" data-caption="<div class='text_center'><strong>{{ $galleryTitle }}</strong><br/>{{ $galleryLink }}</div>" class="gallery_popup" href="{{
                  $gImgSrc  }}">
                  <img src="{{ $gThumbImgSrc }}" alt="" class="img_responsive theme_radius">
                  </a>
                  <div class="gallery_text">
                     <div class="title para_lg0">{{ $galleryTitle }}</div>
                     <p class="para_md">{{ $galleryLink }}</p>
                  </div>
               </div>
               </div>
            </li>
         <?php } ?>
         </ul>
      </div>
   </section>
   <?php } ?>
   <?php /*
   <?php if(!($destination_infos->isEmpty())){ ?>
   <section class="experiences_facts pb_100">
      <div class="container">
         <?php
         foreach ($destination_infos as $destinationInfoDetails) {
            $destination_des = $destinationInfoDetails->description;
            echo html_entity_decode($destination_des);
         ?>
      </div>
   <?php } ?>
   </section>
   <?php } ?>
   <?php */?>
@slot('bottomBlock')
<script>
      $('.pkg_fav').click(function(e){
        e.preventDefault();
    });
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
   var _token = '{{ csrf_token() }}';

   $(document).ready(function () {

      // var destination_id = e.target.value;
         populateSubDestination(21);

     $('#destination').on('change',function(e) {
         var destination_id = e.target.value;
         populateSubDestination(destination_id);
     });
   });

   function populateSubDestination(destination_id,setDestination=""){
      $.ajax({
         url:"{{ route('common.ajax_load_sub_destinations') }}",
         type:"POST",
         headers:{'X-CSRF-TOKEN': _token},
         data: {
             destination_id: destination_id
         },
         success:function (data) {
             let text = "";
             $('#sub_destination').empty();
             text +=  `<option value="">All Locations</option>`
             text += data.options;
             /*data.states.forEach(function(item, index){
                 text +=  `<option value="${item.id}">${item.name}</option>`
             })*/
             $("#sub_destination").html(text)
         },complete:function(){
             $('#sub_destination').val(setDestination);
         }
      });
   }

// Favourite add/remove script
   $("body").on('click','.pkg_fav',function() {
       var id = $(this).attr("id").replace('favid-','');
       //alert(id);
       if($(this).hasClass('liked_btn'))
       {
           $.ajax({
               type: 'POST',
               headers:{'X-CSRF-TOKEN': _token},
               url: '{{ URL("user/record-package-favourite") }}',
               data: {'data_id':id,'status':'0'},
               dataType: 'json',
               cache: false,
               success: function(response)
               {
                   if(response.status == 'success')
                   {
                       $(".pkg_fav_"+id).removeClass("liked_btn");
                       $(".pkg_fav_"+id).removeClass("pkg_fav_clicked");
                       //$("#ar_fav_count_"+id).html(response.fav_count);
                   }
                   else if(response.message)
                   {
                       alert(response.message);
                   }

               }
           });

       }
       else
       {
           $.ajax({
               type: 'POST',
               headers:{'X-CSRF-TOKEN': _token},
               url: '{{ URL("user/record-package-favourite") }}',
               data: {'data_id':id,'status':'1'},
               dataType: 'json',
               cache: false,
               success: function(response)
               {
                   if(response.status == 'success')
                   {
                       $(".pkg_fav_"+id).addClass("liked_btn");
                       $(".pkg_fav_"+id).addClass("pkg_fav_clicked");
                       //$("#pkg_fav_count_"+id).html(response.fav_count);
                   }
                   else if(response.message)
                   {
                       alert(response.message);
                   }
               }
           });
       }
   });

</script>
@endslot

@endcomponent
