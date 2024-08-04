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
      <img src="{{$b_image}}" alt="" >
   </div>
    @include(config('custom.theme').'/includes/search')
</section>
<section class="about_top_content parallax-window" data-parallax="scroll">
   <div class="container">
      <div class="row">
         <div class="theme_title">
                <h1 class="title2 text-3xl pb-5">Magical memories, Bespoke experiences</h1>
                <hr>
            </div>
      <div class="top_about">
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
      <div class="fact_grid">
         <div class="col-md-3 box">
            <div class="iocn"><img src="{{asset(config('custom.assets').'/images/fact1.png')}}" alt="Best Track Record"> </div>
            <div class="text para_lg2">Best Track Record</div>
         </div>
         <div class="col-md-3 box">
            <div class="iocn"><img src="{{asset(config('custom.assets').'/images/fact2.png')}}" alt="High Quality Service"> </div>
            <div class="text para_lg2">High Quality Service</div>
         </div>
         <div class="col-md-3 box">
            <div class="iocn"><img src="{{asset(config('custom.assets').'/images/fact3.png')}}" alt="Professional Trip Management"> </div>
            <div class="text para_lg2">Professional Trip Management</div>
         </div>
         <div class="col-md-3 box">
            <div class="iocn"><img src="{{asset(config('custom.assets').'/images/fact4.png')}}" alt="Highly Experienced Guides"> </div>
            <div class="text para_lg2">Highly Experienced Guides</div>
         </div>
         <div class="col-md-3 box">
            <div class="iocn"><img src="{{asset(config('custom.assets').'/images/fact5.png')}}" alt="Customized Tour Packaging"> </div>
            <div class="text para_lg2">Customized Tour Packaging</div>
         </div>
         <div class="col-md-3 box">
            <div class="iocn"><img src="{{asset(config('custom.assets').'/images/fact6.png')}}" alt="Deep Local Knowledge"> </div>
            <div class="text para_lg2">Deep Local Knowledge</div>
         </div>
         <div class="col-md-3 box">
            <div class="iocn"><img src="{{asset(config('custom.assets').'/images/fact7.png')}}" alt="Personalized Service"> </div>
            <div class="text para_lg2">Personalized Service</div>
         </div>
         <div class="col-md-3 box">
            <div class="iocn"><img src="{{asset(config('custom.assets').'/images/fact8.png')}}" alt="Multi-Currency and Multilingual"> </div>
            <div class="text para_lg2">Multi-Currency and Multilingual</div>
         </div>
      </div>
      </div>
      
   </div>
</section>
      <section>
         <div class="container">
            
            <div class="theme-title text-center">
               <!-- <div class="title"><?php //echo $cms['title']; ?></div> -->
               <div class="title text-3xl pb-5">Combining the Best of Both Worlds</div>
               <p style="margin-top: 0;padding-bottom: 35px;"><strong>Vision, Mission & Coare Values</strong></p>
            </div>
            <div class="about_top">
               <div class="about_left">
                  <img class="img-responsive" src="{{asset(config('custom.assets').'/images/Beautiful-Mauritius-Beaches.jpeg') }}" alt="Beautiful-Mauritius-Beaches" >
               </div>
           
               <div class="about_right" id="fade_anchor">
                  <ul class="fact_list">
                     <li data-aos="fade-left" data-aos-duration="1000"  data-aos-anchor="#fade_anchor">
                        <div class="iocn"><img src="{{asset(config('custom.assets').'/images/Core-Values.png')}}" alt="Core-Values"> </div>
                        <div class="text para_lg2">Core Values <p>Teamwork, Integrity, Excellence, Innovation, Sustainability.</p></div>
                     </li>
                     <li data-aos="fade-left" data-aos-duration="1200" data-aos-anchor="#fade_anchor">
                        <div class="iocn"><img src="{{asset(config('custom.assets').'/images/Mission.png')}}" alt="Mission"> </div>
                        <div class="text para_lg2">Mission <p>To be the most innovative Bhutanese company committed to excellence and delivering authentic services with personal touch.</p></div>
                     </li>
                     <li data-aos="fade-left" data-aos-duration="1400" data-aos-anchor="#fade_anchor">
                        <div class="iocn"><img src="{{asset(config('custom.assets').'/images/Vision.png')}}" alt="Vision"> </div>
                        <div class="text para_lg2">Vision <p>Leading the way to happiness through world-class services and our commitment to Bhutanese values.</p></div>
                     </li>
                  </ul>
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
      <section class="benchmarks_area" style="background-image: url({{asset(config('custom.assets').'/images/about-bg.jpg') }}) ">
         <div class="container">
            <div class="benchmarks_text"  data-aos="fade-right" data-aos-duration="1500">
               <div class="theme_title">
                  <div class="title text-3xl pb-5">Quality Benchmarks</div>
               </div>
               <p>A visit to Bhutan promises a special and unique experience. Our role is to ensure that our guests can witness the country's many fascinations and charms without being distracted by unnecessary hitches or avoidable discomforts. You can be confident that with Yangphel you receive the highest quality service in the industry. We aim at excellence in each aspect of your trip, in both the fundamentals and the small details. These various elements then come together to create a superior overall package.</p>
            </div>
         </div>
      </section>
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
      <section class="accommodation_sec">
         <div class="container">
            <div class="theme-title text-center">
               <div class="title text-3xl pb-5">Food & Accommodation</div>
            </div>
            <ul class="accommodation_list">
               <li data-aos="fade-up" data-aos-duration="2000">
                  <div class="accommodation_box">
                     <div class="top_title">
                        <div class="icon"><img src="{{asset(config('custom.assets').'/images/about-icon1.png')}}" alt="Best available services"></div>
                        <div class="title para_lg1">Best available services</div>
                     </div>
                     <p class="para_md">In many parts of the country food and accommodation arrangements are relatively basic. We aim to provide our clients with the best in each location. This is assessed through a combination of the Department of Tourism's hotel, lodge and guesthouse grading and our own experience. Individual hotel details are available on request.</p>
                  </div>
               </li>
               <li data-aos="fade-up" data-aos-duration="2000">
                  <div class="accommodation_box">
                     <div class="top_title">
                        <div class="icon"><img src="{{asset(config('custom.assets').'/images/about-icon2.png')}}" alt="Priority reservations"></div>
                        <div class="title para_lg1">Priority reservations</div>
                     </div>
                     <p class="para_md">In the most popular destinations at peak season there almost always room shortages. Our network of excellent working relationships generally enables us to get our clients bookings at the more comfortable hotels. To better guarantee this, we are also in the process of expanding our own accommodation infrastructure.</p>
                  </div>
               </li>
               <li data-aos="fade-up" data-aos-duration="2000">
                  <div class="accommodation_box">
                     <div class="top_title">
                        <div class="icon"><img src="{{asset(config('custom.assets').'/images/about-icon3.png')}}" alt="Camping comfort"></div>
                        <div class="title para_lg1">Camping comfort</div>
                     </div>
                     <p class="para_md">Our campsites are the best in the industry. The equipment we now use has been proven to be the most suited to particular Bhutanese conditions. For high-altitude treks we favor Marmot tents and accessories. For more gentle tour camping we have had tents custom-made to provide greater comfort and space.</p>
                  </div>
               </li>
               <li data-aos="fade-up" data-aos-duration="2000">
                  <div class="accommodation_box">
                     <div class="top_title">
                        <div class="icon"><img src="{{asset(config('custom.assets').'/images/about-icon4.png')}}" alt="Improved food on trek"></div>
                        <div class="title para_lg1">Improved food on trek</div>
                     </div>
                     <p class="para_md">Although practicality dictates that food during treks is fairly simple, it should be as appetizing as possible. Our cooks have recently undergone extra training, and the improvement in food quality and variety has been well received by our clients.</p>
                  </div>
               </li>
            </ul>
         </div>
      </section>
      <!-- WHAT'S NEW SECTION END -->
<?php if(!$blogs->isEmpty()){ ?>
      <!-- BLOG SECTION START -->
      <section class="home_blog" style="">
         <div class="container">
            <div class="row">
            <div class="home_blog_wrapper">
               <div class="blog_left">
                  <div class="theme-title text-center">
                     <div class="title text_center text-3xl pb-5">Featured Blogs & News</div>
                     <p class="pb-3 max-w-xl">Already have your perfect trip planned? Just looking for a few more incredible experiences to add? Check out our amazing Blogs and Latest News. </p>
                  </div>
               </div>
            </div>
            <div class="blog_right">
         <div class="slider_box">
            <div class="swiper blog_slider">
               <div class="swiper-wrapper">
                  <?php foreach($blogs as $blog){
                    $tTitle = $blog->title;
                    $tBrief = CustomHelper::wordsLimit($blog->brief,150);
                    $tImage = $blog->image;

                    $path = 'blogs/';
                    $thumbPath = 'blogs/thumb/';

                    $blogSrc = asset('assets/img/blog_default.png');
                    $blogThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

                    if(!empty($tImage)){
                      if($storage->exists($path.$tImage)){
                         $blogSrc = asset('/storage/'.$path.$tImage);
                      }

                      if($storage->exists($thumbPath.$tImage)){
                        $blogThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                     }
                  }
                  ?>
                  <div class="swiper-slide">
                     <a class="blog_box" href="{{route('blogsDetail',['slug'=>$blog->slug])}}">
                        <img class="theme_radius" src="{{ $blogThumbSrc }}" alt="{{ $tTitle}}"/>
                        <div class="text">
                          <span class="title text-lg pt-3"> {{ $tTitle}}
                          </span>
                          <p class="">{{ $tBrief }}</p>
                          <div class="arrow_icon_inner">
                             <div class="arrow_icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                   <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                                </svg>
                             </div>
                          </div>
                       </div>
                    </a>
                 </div>
              <?php }?>
           </div>
        </div>
        <div class="mt45 blog_view_all_btn">
         <a href="{{route('blogsListing')}}" class="btn inline-block">View All</a>
      </div>
      <div class="slider_btns">
         <div class="blog-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/next.png') }}" alt="Next Icon"></div>
         <div class="blog-next theme-next"><img src="{{asset(config('custom.assets').'/images/prev.png') }}" alt="Prev Icon"></div>
      </div>
   </div>
</div>
         </div>
         </div>
      </div>

</section>
<!-- BLOG SECTION END -->
<?php } ?>
<?php /*
      <?php if(!$featured_packages->isEmpty()){ ?>
      <section class="transport_about_area parallax-window" data-parallax="scroll" data-image-src="{{asset(config('custom.assets').'/images/bg-cloud.png')}}" >
         <div class="container">
         <div class="text_center">
            <div class="theme_title">
               <div class="title">
                  Our Featured Trips
               </div>
               <div class="icon mt15">
                  <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
               </div>
            </div>
         </div>
         <ul class="hotel_list">
            <?php foreach ($featured_packages as $package) {
               $package_title = CustomHelper::wordsLimit($package->title);
               $package_brief = CustomHelper::wordsLimit($package->brief);
               $package_slug = $package->slug;
               $package_duration = $package->package_duration;
               $package_image = $package->image;
               $package_path = 'packages/';
               $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

               if(!empty($package_image)){
                  if($storage->exists($package_path.$package_image)){
                     $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package_image);
                  }
               }
            ?>            
            <li data-aos="fade-up" data-aos-duration="2000">
               <a class="" href="{{route('packageDetail',['slug'=>$package_slug])}}">
                  <div class="hotel_box">
                     <div class="images">
                        <img src="{{$packageThumbSrc}}" alt="{{$package_title}}">
                     </div>
                     <div class="hotel_content">
                        <div class="title heading_sm3">{{ $package_title }}</div>
                        <p class="para_md">{{$package_brief}}</p>
                        <div class="view_all">View Detail</div>
                     </div>
                  </div>
               </a>
            </li>
         <?php } ?>   
         </ul>
         </div>
      </section>
      <?php } ?>
*/?>
      <section class="p-0">


         
   <div class="container">
      <div class="organizations_logos">
         <div class="text-center">
            <div class="theme-title">
               <div class="title text-3xl pb-5">
                  Yangphel Adventure Travel is associated with <br>
                  the following organizations
               </div>
               <!-- <div class="icon mt15">
                  <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
               </div> -->
            </div>
         </div>
         <ul class="organizations_list">
            <li>
               <div class="organizations_box">
                  <img src="{{asset(config('custom.assets').'/images/organizations-img3.jpg')}}" alt="Organizations">
               </div>
            </li>
            <li>
               <div class="organizations_box">
                  <img src="{{asset(config('custom.assets').'/images/organizations-img2.jpg')}}" alt="Organizations">
               </div>
            </li>
            <li>
               <div class="organizations_box">
                  <img src="{{asset(config('custom.assets').'/images/organizations-img1.jpg')}}" alt="Organizations">
               </div>
            </li>
            <li>
               <div class="organizations_box">
                  <img src="{{asset(config('custom.assets').'/images/organizations-img4.jpg')}}" alt="Organizations">
               </div>
            </li>
            <li>
               <div class="organizations_box">
                  <img src="{{asset(config('custom.assets').'/images/organizations-img5.jpg')}}" alt="Organizations">
               </div>
            </li>
         </ul>
      </div>
   </div>
</section>
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
