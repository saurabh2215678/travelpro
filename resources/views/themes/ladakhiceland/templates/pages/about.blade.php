@component(config('custom.theme').'.layouts.main')

@slot('title')
       {{$page_title}} 
@endslot

@slot('meta_description')
{{ $meta_description ?? '' }}
@endslot

@slot('meta_keyword')
{{ $meta_keyword ?? '' }}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
cms-pages
@endslot

<?php
   $storage = Storage::disk('public');
   $featured_packages = App\Models\Package::where('status',1)->where('featured',1)->orderBy('created_at','desc')->limit(3)->get();
?>


<?php
//prd($cms);
$storage = Storage::disk('public');
   $cms_path = 'cms_pages/';

   $cms_image = $cms['image'];
   $cms_banner_image = $cms['banner_image'];

   $cmsSrc =asset(config('custom.assets').'/images/map-overview.jpg');
   $cmsThumbSrc =asset(config('custom.assets').'/images/about-banner.jpg');

   if(!empty($cms_image)){
      if(File::exists("storage/".$cms_path.$cms_image)){
         $cmsSrc = asset('/storage/'.$cms_path.'thumb/'.$cms_image);
      }
   }

   if(!empty($cms_banner_image)){
      if(File::exists("storage/".$cms_path.$cms_banner_image)){
         $cmsThumbSrc = asset('/storage/'.$cms_path.$cms_banner_image);
      }
   } 
?>

      <?php 
        $isMobile = CustomHelper::isMobile();
        $bannerType = 'desktop';
        if($isMobile){
            $bannerType = 'mobile';
        }
        $bannerWhere = [];
        $bannerWhere['page'] = 'about';
        $bannerWhere['status'] = 1;
        $bannerWhere['device_type'] = $bannerType;
        $banners = App\Models\BannerImageGallery::where($bannerWhere)->orderBy('sort_order')->limit(9)->get();
        $data['banners'] = $banners;
      
$storage = Storage::disk('public');
$path = 'banners/';
$b_image =asset(config('custom.assets').'/images/noimage.jpg');
// dd($banners);
// foreach($banners as $banner){
//   $images = (isset($banner->Images))?$banner->Images:'';
//   // prd($images->toArray());
//   if(!empty($images) && count($images) > 0){
//     foreach($images as $image){
//       if(!empty($image->image_name) && $storage->exists($path.$image->image_name)){

//         $b_image = url('/storage/banners/'.$image->image_name);
//       }
//     }
//   }
// }

if(isset($cms['banner_image'])){
   $b_image = url('/storage/cms_pages/'.$cms['banner_image']);
}

    ?>

<section class="inner_banner">
   <div class="inner_banner_main">
      <!-- <img src="{{$cmsThumbSrc}}" alt="" > -->
      <img src="{{$b_image}}" alt="" >
   </div>
   
</section>
<section class="about_top_content parallax-window" data-parallax="scroll">
   <div class="container">
      <div class="row">
         <div class="theme_title">
               <h3 class="section_heading"><?php echo $cms['heading']; ?></h3>
         </div>   
      <div class="top_about">
         <div class="about_img">
            <img src="{{$cmsSrc}}" alt="">
         </div>
         <div class="about_text">
            <?php echo $cms['content']; ?>
         </div>
         
         <!-- <div class="images">
            <div class="aos-init aos-animate" data-aos="fade-up" data-aos-duration="2000">
               <p>Etiam ut urna quis nisi ultricies blandit non quis metus. Curabitur risus nisl, efficitur vel ligula a, vestibulum pulvinar massa. Phasellus semper nec lorem dictum convallis. Duis gravida neque est, convallis pulvinar massa hendrerit non. Proin id urna justo. Sed diam velit, molestie et enim id, viverra faucibus mi. Suspendisse sodales elit ut tincidunt tincidunt. Nullam sit amet ex sed nulla rhoncus vehicula. </p>
               <p>Suspendisse placerat, diam nec faucibus dictum, lacus metus bibendum nulla, in suscipit sem ex vel ipsum. Praesent fermentum aliquet eros, eu placerat dolor euismod et. Maecenas ultricies congue ex vitae consectetur. Proin nec eros auctor, rutrum augue ac, ultrices massa. Nullam ac auctor nisi. In pulvinar mattis nunc, ut suscipit sem placerat in.</p>
            </div>
         </div> -->
         <!-- <div class="about_content aos-init aos-animate" data-aos="fade-up" data-aos-duration="2000">
            <p class="para_lg2">Phasellus sed tincidunt orci. Fusce finibus libero in erat consectetur facilisis. Donec consequat massa semper dictum gravida. Integer finibus venenatis finibus. Nullam lobortis vehicula ex, sit amet aliquam justo gravida tincidunt. Nam non sollicitudin mauris, sed suscipit lectus. Suspendisse id urna dignissim, malesuada dui a, placerat nunc. Donec malesuada placerat ante et consectetur.</p>
            <p>Etiam ut urna quis nisi ultricies blandit non quis metus. Curabitur risus nisl, efficitur vel ligula a, vestibulum pulvinar massa. Phasellus semper nec lorem dictum convallis. Duis gravida neque est, convallis pulvinar massa hendrerit non. Proin id urna justo. Sed diam velit, molestie et enim id, viverra faucibus mi. Suspendisse sodales elit ut tincidunt tincidunt.</p>
         </div> -->
      </div>
      
      </div>
      
   </div>
</section>
     
      <!-- <section class="vision_area">
         <div class="container">
            <div class="text_center">
               <div class="theme_title">
                  <div class="title" style="color:#fff;">Combining the Best of Both Worlds</div>
               </div>
               <p class="para_lg2" style="color:#fff;">Vision, Mission & Core Values</p>
            </div>
            <ul class="vision_list">
               <li  data-aos="fade-up" data-aos-duration="2000">
                  <div class="vision_box text_center">
                     <div class="vision_box_inner" style="background-image: url({{asset(config('custom.assets').'/images/vision-box.png') }}) ">
                        <div class="iocn"><img src="{{asset(config('custom.assets').'/images/vision1.png')}}" alt=""></div>
                        <div class="title heading_sm2">Core Values</div>
                        <p class="para_md1">What do we believe in?</p>
                     </div>
                     <p class="para_md1" style="color:#fff;">Teamwork, Integrity, Excellence, <br> Innovation, Sustainability.</p>
                  </div>
               </li>
               <li data-aos="fade-up" data-aos-duration="2000">
                  <div class="vision_box text_center">
                     <div class="vision_box_inner" style="background-image: url({{asset(config('custom.assets').'/images/vision-box.png') }}) ">
                        <div class="iocn"><img src="{{asset(config('custom.assets').'/images/vision2.png')}}" alt=""></div>
                        <div class="title heading_sm2">Mission</div>
                        <p class="para_md1">Why do we exist?</p>
                     </div>
                     <p class="para_md1" style="color:#fff;">To be the most innovative Bhutanese company <br> committed to excellence and delivering authentic<br> services with personal touch.</p>
                  </div>
               </li>
               <li data-aos="fade-up" data-aos-duration="2000">
                  <div class="vision_box text_center">
                     <div class="vision_box_inner" style="background-image: url({{asset(config('custom.assets').'/images/vision-box.png') }}) ">
                        <div class="iocn"><img src="{{asset(config('custom.assets').'/images/vision3.png')}}" alt=""></div>
                        <div class="title heading_sm2">Vision</div>
                        <p class="para_md1">Where do we go to?</p>
                     </div>
                     <p class="para_md1" style="color:#fff;">Leading the way to happiness through <br> world-class services and our commitment to<br> Bhutanese values.</p>
                  </div>
               </li>
            </ul>
         </div>
      </section> -->
      
      <!--<section class="trip_management">
         <div class="container">
            <div class="theme_title">
               <div class="title">Trip Management</div>
            </div>
            <div class="slider_box">
               <div class="swiper trip_slider">
                  <div class="swiper-wrapper">
                     <div class="swiper-slide">
                        <div class="trip_box">
                           <div class="title heading3">Staff Quality</div>
                           <p class="para_md">Our personnel are some of the most highly qualified and experienced in the industry. We reward better than our competitors. Each member has had a broad-based training, which allows the performance of multiple roles and has improved all round understanding. A depth of experience is especially important in developing an open-minded attitude towards many different nationalities and when handling difficult situations and emergencies.</p>
                        </div>
                     </div>
                     <div class="swiper-slide">
                        <div class="trip_box">
                           <div class="title heading3">Superior execution</div>
                           <p class="para_md">Our know-how and infrastructure, together with a network of long-standing relationships within the industry, help us ensure that each trip is implemented to the best achievable standards. A high staff to client ratio improves overall service and provides invaluable flexibility in case of emergencies. From the time of your departure to your return our management will be there as backup, to follow up any queries and offer any necessary assistance.</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="slider_btns">
                  <div class="trip-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/next.png')}}" alt=""></div>
                  <div class="trip-next theme-next"><img src="{{asset(config('custom.assets').'/images/prev.png')}}" alt=""></div>
               </div>
            </div>
         </div>
         </div>
      </section>-->
      
@slot('bottomBlock')
 <script>
         var tripSlider =  new Swiper(".trip_slider", {
               slidesPerView: 2,
               spaceBetween:80,
               loop: true,
               speed:1000,
               navigation: {
             nextEl: ".trip-next",
             prevEl: ".trip-prev",
           },
          });
         
         
              
         
         $('.parallax-window').parallax();

         var customerSlider = new Swiper(".blog_slider", {
      loop: true,
      freeMode: true,
      // centeredSlides: true,
      parallax:true,
      speed:1000,
      //slidesPerView: 2.5,
      spaceBetween:25,
      breakpoints: {
   0: {
      slidesPerView: 1.5,
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
   nextEl: ".blog-next",
   prevEl: ".blog-prev",
},
watchSlidesVisibility: true
});
         
</script>
@endslot

@endcomponent
