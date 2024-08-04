@component(config('custom.theme').'.layouts.main')

<?php
$cms_title = isset($cms['title']) ? $cms['title'] : '';
if(!empty($cms_title)){
  $cms_title = str_replace(" ","-",$cms_title);
  $cms_title = strtolower($cms_title);
}
?>
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
cms-pages {{$cms_title}}
@endslot
<?php
$b_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
if(isset($cms['bannerImageSrc']) && !empty($cms['bannerImageSrc'])) {
  $b_image = $cms['bannerImageSrc'];
}
?>
<section class="p-0">
  <div class="home_banner">
    <img src="{{$b_image}}" class="w-100" alt="{{$cms['title']??''}}">
  </div>
</section>

<section class="inner-cms-page">
  <div class="xl:container xl:mx-auto">
    <div class="text_center">
      <div class="theme_title">
        <h1 class="title text-2xl">{{$cms['title']??''}}</h1>
        <div class="breadcrumb_full">
          <div class="container">
            <ul class="breadcrumb">
              <li><a href="{{url('/')}}">Home</a>
              </li>
              <li><a>{{$cms['title']??''}}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="about_inner">
      @if(!empty($cms['imageSrc']))
      <div class="singleimgs mb-6">
        <div class="single_imgs"><img class="w-full" src="{{$cms['imageSrc']}}" alt="{{$cms['title']??''}}" ></div>
      </div>
      @endif

      <?php if(!empty($cms['brief']) || !empty($cms['content'])){ ?>
      <div class="about_text">
        <?php if(!empty($cms['brief'])){ ?>
        <p>{!!$cms['brief']!!}</p>
        <?php } ?>

        <?php if(!empty($cms['content'])){ ?>
        {!!$cms['content']!!}
        <?php } ?>
      </div>
      <?php } ?>
    </div>

    <?php if( isset($cms['child_data']) && !empty($cms['child_data']) && count($cms['child_data']) > 0){ ?>
      <!-- <div class="animation-elements">
        <span class="packages-star" data-aos="fade-right" data-aos-duration="2500"></span> 
        <span class="courses-fish" data-aos="fade-left" data-aos-duration="500"></span>                 
      </div>       -->
      <div class="container_no">
        <!-- <h2 class="heading2" data-aos="fade-up" data-aos-duration="2000"></h2> -->
        <div class="slidecourses owl-carousel">
          <?php foreach ($cms['child_data'] as $child) { ?>
            <div class="listbox">
              <div class="border_box">

                <?php if(!empty($child['heading']) || !empty($child['brief'])){ ?>

                <?php if(!empty($child['thumbSrc'])){ ?>
                  <div class="courseimg">
                    <img src="{{$child['thumbSrc']}}" alt="{{ $child['title']??''}}">
                  </div>
                <?php } ?>

                <div class="titles">
                  <?php if(!empty($child['heading'])){ ?>
                  <h3>{{$child['heading']??''}}</h3>
                  <?php } ?>

                  <?php if(!empty($child['brief'])){ ?>
                  <p>{!!$child['brief']??''!!}</p>
                  <?php } ?>
                </div>
                <?php } ?>

                <?php if(!empty($child['content'])){ ?>
                <div>{!!$child['content']??''!!}</div>
                <?php } ?>

                <?php if(isset($child['images']) && !empty($child['images'])){ ?>
                <div class="flex flex-wrap gall_cms">
                  <?php foreach($child['images'] as $image){ ?>
                  <a href="{{$image['imageSrc']}}" data-fancybox="destination-gallery"><img src="{{$image['thumbSrc']}}" alt="{{$image['title']}}"></a>
                  <?php } ?>
                </div>
                <?php } ?>

              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

    <?php if(isset($cms['images']) && !empty($cms['images'])){ ?>
    <div>
      <div class="flex flex-wrap gall_cms">
        <?php foreach($cms['images'] as $image){ ?>
        <a href="{{$image['imageSrc']}}" data-fancybox="destination-gallery"><img src="{{$image['thumbSrc']}}" alt="{{$image['title']}}"></a>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>
</section>

@slot('bottomBlock')
<script>
  $('.featured_slider').each(function(){
    var element = $(this)[0];
    var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-next')[0];
    var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-prev')[0];
    new Swiper(element, {
      slidesPerView: 6,
      spaceBetween:20,
      loop: false,
      speed:1000,
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
          slidesPerView: 6,
        },
      },
      navigation: {
        nextEl: nextButton,
        prevEl: prevButton,
      },
    });
  });

  var epSlider = new Swiper(".category_slider", {
    slidesPerView: 6,
    spaceBetween:15,
    loop: true,
    speed:1000,
    navigation: {
      nextEl: ".cat-prev",
      prevEl: ".cat-next",
    },
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
        slidesPerView: 6,
      },
    },
    watchSlidesVisibility: true
  });

</script>
@endslot

@endcomponent