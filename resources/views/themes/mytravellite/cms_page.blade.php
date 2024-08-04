@component(config('custom.theme').'.layouts.main')

@slot('title')
       {{$page_title ?? '' }} 
@endslot

@slot('meta_description')
       {{$meta_description ?? ''}} 
@endslot

@slot('meta_keyword')
       {{$meta_keyword ?? ''}} 
@endslot

@slot('headerBlock')
@endslot

@slot('bodyClass')
cms-pages
@endslot
<?php
$b_image = asset(config('custom.assets').'/images/cms_default_banner.jpg');
if(isset($cms['bannerImageSrc']) && !empty($cms['bannerImageSrc'])) {
  $b_image = $cms['bannerImageSrc'];
}
?>
<section class="inner_banner">
  <div class="inner_banner_main">
    <img src="{{$b_image}}" alt="{{$cms['title']??''}}">
  </div>
  @include(config('custom.theme').'/includes/search')
</section>

<?php $cms_data = $cms['cms'] ?? [];
      $slug = $cms_data->slug ?? '';
      $title = $cms_data->title ?? '';
?>
   


<section class="inner-cms-page">
  <div class="container">
    <div class="text_center">
      <div class="theme_title">
        <h1 class="title text-2xl">{{$cms['title']??''}}</h1>
        <div class="breadcrumb_full">
         <div class="container">
            <ul class="breadcrumb">
               <li><a href="{{url('/')}}">Home</a>
               </li>
               <li><a> {{$title}}</a>
               </li>
            </ul>
         </div>
      </div>
      </div>
      
    </div>


    <div class="about_inner">
       @if(!empty($cms_image_src))
      <div class="singleimgs mb-6">
        <div class="single_imgs"><img class="w-full" src="{{$cms_image_src}}" alt="{{$cms['title']??''}}" ></div>
      </div>
      @endif

      <div class="about_text">
      {!! $cms['content'] !!}
      </div.
   </div>


  <?php if( isset($cms_child_data) && !empty($cms_child_data) && count($cms_child_data) > 0){ ?>
    <div class="animation-elements">
      <span class="packages-star" data-aos="fade-right" data-aos-duration="2500"></span> 
      <span class="courses-fish" data-aos="fade-left" data-aos-duration="500"></span>                 
   </div>      
    <div class="container_no">
         <h2 class="heading2" data-aos="fade-up" data-aos-duration="2000"></h2>
          <div class="slidecourses owl-carousel">
            <?php 
            foreach ($cms_child_data as $cmsData) {
              
              $cms_title = CustomHelper::wordsLimit($cmsData->title)??'';
              $cms_brief = $cmsData->brief??'';
              $cms_url = $cmsData->slug??'';
              $cms_image = $cmsData->image??'';
              

              // $cmsSrc =asset(config('custom.assets').'/images/noimage.jpg');
              //$activityThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
              // $activity_url = url('course/detail/'.$course_url);
              $cms_image_src = '';
              if(!empty($cms_image)){
                 if($storage->exists($cms_thumb_path.$cms_image)){
                    $cms_image_src = asset('/storage/'.$cms_thumb_path.$cms_image);
                 }
              }

            ?>
              <div class="listbox">
               <div class="border_box">
                @if($cms_image_src)
                <div class="courseimg">
                   <img src="{{ $cms_image_src }}" alt="{{ $cms_title }}">
                </div>
                @endif
                <div class="titles">
                   <p>{{ $cms_title }}</p>
                   <h3>{!! $cms_brief !!}</h3>
                </div>
               </div>
            </div>
        <?php } ?>
            </div>
    </div>
<?php } ?>

  </div>
</section>


@slot('bottomBlock')
<script type="text/javascript">
$('.featured_slider').each(function(){
   var element = $(this)[0];
   var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-next')[0];
   var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-prev')[0];
   new Swiper(element, {
      slidesPerView: 6,
      spaceBetween:20,
      loop: true,
      speed:1000,
      breakpoints: {
         0: {
            slidesPerView: 2.25,
         },
         640: {
            slidesPerView: 3.25,
         },
         768: {
            slidesPerView: 4,
         },
         1024: {
            slidesPerView: 6,
         },
      },
      navigation: {
       nextEl: nextButton,
       prevEl: prevButton,
    },
 });
});
</script>
@endslot

@endcomponent