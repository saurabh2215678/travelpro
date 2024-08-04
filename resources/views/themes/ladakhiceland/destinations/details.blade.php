@component(config('custom.theme').'.layouts.main')

@slot('title')
{{ $meta_title ?? ''}}
@endslot    

@slot('meta_description')
{{ $meta_description ?? '' }}
@endslot

@slot('meta_keyword')
{{ $meta_keyword ?? '' }}
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
experiences
@endslot

<?php
  // prd($popular_packages);
$storage = Storage::disk('public');
$package_path = 'packages/';
$destination_path = 'destinations/';
$destination_thumb_path = 'destinations/thumb/';
      //prd($btDestination);
$destinationImages = $destinationDetails->destinationImages??[];
$destination_name = $destinationDetails->name;
$destination_slug = $destinationDetails->slug;
$destination_banner_image = $destinationDetails->banner_image;

/*$destinationThumbSrc =asset(config('custom.assets').'/images/noimagebig.jpg');
if(!empty($destination_banner_image)){
   if($storage->exists($destination_path.$destination_banner_image)){
      $destinationThumbSrc = asset('/storage/'.$destination_path.$destination_banner_image);
   }
}*/

$destinationBanners = $destinationDetails->destinationBanners??[];
?>
<?php 
//$banners = [];
$storage = Storage::disk('public');
$path = 'banners/';
// $b_image =asset(config('custom.assets').'/images/noimage.jpg');
$b_image =asset(config('custom.assets').'/img/default_banner.jpg');
/*foreach($banners as $banner){
   $images = (isset($banner->Images))?$banner->Images:'';
   // prd($images->toArray());
   if(!empty($images) && count($images) > 0){
      foreach($images as $image){
         if(!empty($image->image_name) && $storage->exists($path.$image->image_name)){
            $b_image = url('/storage/banners/'.$image->image_name);
         }
      }
   }
}*/
if($banner_image) {
  $b_image = $banner_image;
}
?>
<section class="inner_banner" style="padding:0;">
  @if(!empty($destinationBanners) && count($destinationBanners) > 0)
  <div class="header_inner_banner_main">
    <div class="swiper-wrapper">
      @foreach($destinationBanners as $banner)
        <div class="swiper-slide">
          @if($storage->exists($destination_path.$banner->name))
          <img src="{{asset('/storage/'.$destination_path.$banner->name)}}" alt="{{$banner->title??''}}" />
          @else
          <img src="{{$b_image}}" alt="{{$banner->title??''}}" />
          @endif
        </div>
      @endforeach
    </div> 
  </div>
  @else
   <div class="inner_banner_main">
      <img src="{{$b_image}}" alt="{{$destination_name??''}}" />
   </div>
   @endif

</section>


<section class="search_home py-0 md:px-0 hidden">
   <div>
      @include(config('custom.theme').'.includes.search')
   </div>
</section>


<?php /*
<form action="{{ route('packageListing') }}" method="GET" name="package_search">
   <div class="enquiry_search">
      <div class="container">
         <div class="title para_lg2">
            Start your Trip here...
         </div>
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
      </form>*/ ?>

     

      <section class="destination-slider">
         <div class="container">
            <div class="text_center">
            <div class="theme_title mb-6">
               <h1 class="title text-2xl">{{$destination_name}} Tour Packages</h1>
               <div class="breadcrumb_full">
                  <div class="container">
                     <ul class="breadcrumb">
                        <li><a href="{{url('/')}}">Home</a></li>
                        <li><a href="{{route('destinationListing')}}">{{$breadcrumb_title??''}}</a></li>
                        <li>{{$destination_name??''}}</li>
                     </ul>
                  </div>
               </div>
            </div>
               
            </div>
            <?php if(!$popular_packages->isEmpty()){ ?>
               <div class="swiper featured_list destination_package_slider">
                  <ul class="swiper-wrapper">
                     <!-- == Package Li == -->
                     @include(config('custom.theme').'/includes/package-li-box',['packages'=>$popular_packages])
                  </ul>
                  </div>
                  <div class="slider_btns">
                     <div class="hotel-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
                     <div class="hotel-next theme-next"><img src="{{asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
                  </div>
               <div class="view_all_btn text-center mt-5"><a href="{{route('tourPackages',['slug'=>$destination_slug])}}">View All</a></div>
         </div>
         
   <?php }else{
       $SALES_EMAIL =CustomHelper::WebsiteSettings('SALES_EMAIL');
      ?>
      <div class="no_data text_center no_pack_flex">
       <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
       <div class="no_pack_box">
         <lottie-player src="{{asset(config('custom.assets').'/images/no-pack.json')}}"  background="transparent"  speed="1"  style="width: 300px; height: 222px;"  loop autoplay></lottie-player>
         <div class="text">  Currently there are no Packages for this Destination!</div>
         <div class="text">Kindly contact our sale team <a href="mailto:{{$SALES_EMAIL}}">({{$SALES_EMAIL}}) </a> for more information.</div>
      </div>
            <!-- <div class="international_form mt-5">
             <div class="form_box-new single_package_form">
               <div class="form_box_inner">
                  <h2 class="heading_form">Enquire For Best Price</h2>
                  @include('snippets.front.flash')
                  {!!formShortCode(['slug' =>'[international_destination]','class'=>'left_form'])!!}
                  </div>
               </div>
            </div> -->
               
      </div>
   <?php } ?>
</div>
</section>

<?php if(!$popular_activities->isEmpty()){ ?>
  <section class="destination-slider">
    <div class="container">
      <div class="text_center">
        <div class="theme_title mb-6">
          <h2 class="title text-2xl">{{$destination_name}} Tour Activity</h2>
        </div>
      </div>
      <div class="swiper featured_list destination_package_slider1">
        <ul class="swiper-wrapper">
          @include(config('custom.theme').'/includes/package-li-box',['packages'=>$popular_activities])
        </ul>
      </div>
      <div class="slider_btns">
        <div class="featured-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
        <div class="featured-next theme-next"><img src="{{asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
      </div>
      <div class="view_all_btn text-center mt-5"><a href="{{route('tourActivities',['slug'=>$destination_slug])}}">View All</a></div>
    </div>
  </section>
<?php } ?>
   <section class="tab-panel">
      <div class="container">
         <div class="tab">
          <button class="tablinks uppercase" onclick="openCity(event, 'dest_about')" id="defaultOpen">About {{$destinationDetails->name??''}}</button>
          <?php
          if(isset($destination_type) && !empty($destination_type) > 0)
          {
            foreach($destination_type as $info)
            {
               $info_title = isset($info->name) ? $info->name:'';
               $info_slug = isset($info->name) ? $info->slug:'';
               // $info_description = isset($info->description) ? $info->description:'';
               ?>
               <button class="tablinks uppercase" onclick="openCity(event, '{{$info_slug}}')" id="defaultOpen">{{$info_title}}</button>
          <?php
            }
          }
          ?>
          @if(!empty($destinationImages) && count($destinationImages)>0)
          <button class="tablinks uppercase" onclick="openCity(event, 'dest_gallery')">Photo Gallery</button>
          @endif
         </div>
          <div id="dest_about" class="tabcontent">
            <div class="">
               <div class="destination_about_content">
                  <div class="desti_about_img">
                     @if($destinationDetails->image)
                        <img src="{{asset('/storage/'.$destination_path.$destinationDetails->image)}}" alt="{{$destinationDetails->name}}">
                     @endif
                  </div>
                  <div class="text-justify font-normal"> {!!$destinationDetails->description!!}</div>
                 
               </div>
            </div>
          </div>
         <?php if(isset($destination_type) && !empty($destination_type) > 0) {
          foreach($destination_type as $info) {
            //$info_title = isset($info->name) ? $info->name:'';
            //$info_description = isset($info->description) ? $info->description:'';
            $destination_info = CustomHelper::getDestinationTypeInfo($destination_id,$info->type);
            // prd($destination_info);
            ?>
            <div id="{{$info->slug??''}}" class="tabcontent">                 
             @foreach($destination_info as $di)
             <div class="accordia_box">
               <div class="open_text">
                  <i class="fa fa-angle-down"></i>
                  
                  @if($di->image)
                  <div class="image">
                     <img src="{{asset('/storage/'.$destination_thumb_path.$di->image)}}" alt="{{$di->title??''}}">
                  </div>
                  @endif
                  <div class="brief">
                  <h2 class="text-lg font-semibold">{{$di->title??''}}</h2>
                     {!! $di->brief??'' !!}
                  </div>
               </div>
              <div class="description">
                {!! $di->description??'' !!}
              </div>
             </div>
             @endforeach
            </div>
       <?php } } ?>

        @if(!empty($destinationImages) && count($destinationImages)>0)
          <div id="dest_gallery" class="tabcontent">
            <div class="flex flex-wrap">
               @foreach($destinationImages as $image)
               <a href="{{asset('/storage/'.$destination_path.$image->name)}}" data-fancybox="destination-gallery"><img src="{{asset('/storage/'.$destination_thumb_path.$image->name)}}"></a>
               @endforeach
            </div>
          </div>
          @endif

    </div>
 </section>
<?php //} ?>


<?php if(!empty($testimonials) && count($testimonials) > 0){ ?>
  <section class="destination-slider destination_testi">
    <div class="container">
      <div class="box_style testimonials_inner">
         <div class="theme_title mb-6">
          <div class="title text-2xl">Testimonials</div>
         </div>
        <div class="testimonial_list_outer">
          <?php  

          $testimonialGalleryDefaultImage =asset(config('custom.assets').'/images/noimagebig.jpg');
          foreach($testimonials as $testimonial) 
          { 

            $short_description=strip_tags($testimonial->description); 

            $short_description=$short_description??'';//limit_text($short_description,0,50); 
            ?>
            <div class="testimonial-item">
               <div class="bg_tesi_box">
                  <p class="text-sm"><?php echo $short_description;//$testimonial->contents; ?></p>
                  <div class="flex customer_name_img items-center mt-5">
                     <a href="{{route('testimonialDetails',['slug'=>$testimonial->slug])}}">
                        <div class="customer_img">
                           @if($testimonial->image)
                           <img src="{{asset('/storage/testimonials/thumb/'.$testimonial->image)}}" alt="{{$testimonial->name??''}}">
                           @else
                           <img src="{{$testimonialGalleryDefaultImage}}" alt="{{$testimonial->name??''}}">
                           @endif
                        </div>
                     </a>
                     <div class="customer_name"><a href="{{route('testimonialDetails',['slug'=>$testimonial->slug])}}" class="text-base font-semibold"><?php echo $testimonial->name; ?></a></div>
                  </div>
               </div>
                <div class="clearfix"></div>
              </div>
            <?php } ?>
            <div class="clearfix"></div>
          </div>
          <div class="view_all_btn text-center mt-5">
            <a href="{{route('testimonialListing')}}" >View All</a>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>




<!-- Frequently Asked Questions -->
<?php if(!empty($faq_row) && count($faq_row) > 0){?>
   <div class="secpad inner-page faq faq-cms-block">
      <div class="container">
      <div class="theme_title mb-6">
         <div class="title text-2xl">FAQs About {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}</div>
      </div>
        <?php $i = 1; ?>
        <div class="faqlist">
         <ul>
            @foreach($faq_row as $row)
            <?php 
            $qusArr = isset($row->question)?$row->question:''; 
            $ansArr = isset($row->answer)?$row->answer:'';
            ?>
            <li class="faq_main">
               <div>
                  <div class="faq_title heading6"><span><strong>Q{{$i}}. </strong></span>{{$qusArr}}</div>

                  <div class="faq_data" style="">
                     <p>{!! $ansArr !!}</p>
                  </div>
               </div>
            </li>
            <?php $i = $i+1; ?>
            @endforeach
         </ul>
      </div> 
      <!-- <div class="text_center"><button class="btn" id="load">Load More Faq</button></div> -->
   </div>
</div>
<?php } ?>
<!-- Frequently Asked Questions Closed -->

@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>

   $(".open_text").click(function(){
      $(this).siblings(".description").slideToggle();
      $(this).find(".fa-angle-down").toggleClass("upaarow");
   });
</script>
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
<script>
 // ===== Tab =======
   function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
     tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
     tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
<script>
   $('.ftitle').click( function(){
      $(this).parent().toggleClass('active'); 
      
   });
   $(".faq_title").on("click", function(e){
      e.preventDefault();
      if($(this).hasClass("active")){
         $(this).removeClass("active");
         $(".faq_data").slideUp(300);
      } else {
         $(".faq_title").removeClass("active");
         $(".faq_listing > li").removeClass("active");
         $(".faq_data").slideUp(300);
         $(this).addClass("active");
         $(this).closest("li").toggleClass("active");
         $(this).parent().find(".faq_data").slideDown(300);
      } 
   });

   $(document).ready(function () {
      $(".faq_main").slice(0, 6).show();
      if ($(".faq_main:hidden").length != 0) {
         $("#load").show();
      }
      $("#load").on("click", function (e) {
         e.preventDefault();
         $(".faq_main:hidden").slice(0, 6).slideDown();
         if ($(".faq_main:hidden").length == 0) {
            $("#load").text("No More FAQ's").fadOut("slow");
         }
      });
   })
</script>
<script type="text/javascript">


var destinationSlider =  new Swiper(".destination_package_slider", {
      slidesPerView: 4,
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
            slidesPerView: 4,
         },
      },
      navigation: {
       nextEl: ".hotel-next",
       prevEl: ".hotel-prev",
    },
 });

 var destinationSlider =  new Swiper(".destination_package_slider1", {
      slidesPerView: 5,
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
            slidesPerView: 5,
         },
      },
      navigation: {
       nextEl: ".featured-next",
       prevEl: ".featured-prev",
    },
 });

//    var destinationSlider =  new Swiper(".destination_package_slider", {
//       slidesPerView: 4,
//       spaceBetween:30,
//       loop: true,
//       speed:1000,
//       breakpoints: {
//          0: {
//             slidesPerView: 1,
//          },
//          640: {
//             slidesPerView: 2,
//          },
//          768: {
//             slidesPerView: 3,
//          },
//          1024: {
//             slidesPerView: 4,
//          },
//       },
//       navigation: {
//        nextEl: ".featured-next",
//        prevEl: ".featured-prev",
//     },
//  });
</script>
@endslot
@endcomponent
