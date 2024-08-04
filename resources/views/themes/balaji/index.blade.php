@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? 'Home'}}
@endslot

@slot('meta_description')
{{ $meta_description ?? 'Home'}}
@endslot

@slot('meta_keyword')
{{ $meta_keyword ?? 'Home'}}
@endslot

@slot('headerBlock')
@endslot

@slot('bodyClass')
home_class
@endslot
<?php
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY','FRONTEND_LOGO_AlT_TEXT']);
$FRONTEND_LOGO_AlT_TEXT = $websiteSettingsArr['FRONTEND_LOGO_AlT_TEXT']??'';
?>
      <section class="inner_banner">
         <div>
            @include(config('custom.theme').'.includes.search')
         </div>
      </section>
    
         {!! themeCategoriesSection(['section_title' => 'Package by theme']) !!}


      <?php /* <section class="home_featured pb-0" >
         {{destinationSection(['section_title' => 'Package by theme'])}}
      </section> */?>

      <?php /* for activity  is_activity = 1 */?>
      {!!packageSection(['section_title' => 'Short Activities','is_activity'=>'1','featured'=>'1'])!!}
           
      <?php /* tour_type = 'group'|'fixed'|'open' */?>
      {!! packageSection(['section_title' => 'Group Tour Packages','tour_type'=> 'group','featured'=>'1']) !!}
      
      <?php /* flag = '1'(Popular)|'2'(Explore Great Deals)|'3'(Best Selling Packages) */?>
      {!! packageSection(['section_title' => 'Tailor-made Individual Packages','flag'=>'1','featured'=>'1']) !!}
    
      {!! accommodationSection(['section_title' => 'Our Domestic and International Hotels']) !!}
      

      {!! staticSection() !!}


      <section class="content-section home_featured">
         <div class="md:container md:mx-auto w_820">
            <div>
               <h2 class="title text-2xl mb-6 text-center">Please fill the form and our expert will get back to you with further details.</h2>
               {!!formShortCode(['slug' =>'[home_page_form]','class'=>'left_form'])!!}
            </div>
         </div>
      </section>
      
         {!! testimonialSection(['section_title' => 'Testimonials']) !!}
     
         {!! blogSection(['section_title' => 'Travel News and Blogs']) !!}
     
         {!! teamSection(['section_title' => 'Meet Our Team']) !!}

     


@slot('bottomBlock')

<script>
   function main() {
        var bannerSlider = new Swiper(".banner_slider", {
      parallax: true,
      effect: 'fade',
      speed:1000,
      autoplay: {
       delay: 1000,
    },
    loop: true,
    pagination: {
       el: ".banner-pagination",
       clickable: true
    },
 });
   var epSlider = new Swiper(".category_slider", {
   // slidesPerView: 5.3,
   slidesPerView: 6,
   spaceBetween:20,
   loop: true,
   speed:1000,
   navigation: {
      nextEl: ".cat-prev",
      prevEl: ".cat-next",
   },
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
   watchSlidesVisibility: true
});


   var customerSlider = new Swiper(".testimonial_slider", {
      loop: false,
      speed:1000,
      slidesPerView: 2,
      spaceBetween:15,
      slidesPerGroup: 2,
      // pagination: {
      //   el: ".swiper-pagination",
      //   clickable:true,
      // },
      breakpoints: {
         0: {
            slidesPerView: 1,
         },
         640: {
            slidesPerView: 1,
         },
         768: {
            slidesPerView: 1,
         },
         1024: {
            slidesPerView: 2,
         },
      },
      navigation: {
       nextEl: ".testimonial-next",
       prevEl: ".testimonial-prev",
    },
 });

   var customerSlider = new Swiper(".blog_slider", {
      loop: true,
      freeMode: true,
      // centeredSlides: true,
      // parallax:true,
      speed:1000,
      //slidesPerView: 2.5,
      spaceBetween:20,
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
      slidesPerView: 4,
   },
},
navigation: {
   nextEl: ".blog-next",
   prevEl: ".blog-prev",
},
watchSlidesVisibility: true
});

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



$('.featured_slider1').each(function(){
   var element = $(this)[0];
   var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-next')[0];
   var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-prev')[0];
  new Swiper(element, {
      slidesPerView: 5,
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
            slidesPerView: 5,
         },
      },
      navigation: {
       nextEl: nextButton,
       prevEl: prevButton,
    },
 });
});

   var featuredSlider =  new Swiper(".hotel_slider", {
      slidesPerView: 5,
      spaceBetween:20,
      loop: true,
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
            slidesPerView: 5,
         },
      },
      navigation: {
       nextEl: ".hotel-next",
       prevEl: ".hotel-prev",
    },
 });




   var clientSlider =  new Swiper(".client_slider", {
      slidesPerView: 4,
      spaceBetween:20,
      loop: true,
      speed:1000,
      breakpoints: {
         0: {
            slidesPerView: 2,
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
       nextEl: ".client-next",
       prevEl: ".client-prev",
    },
 });

   $('.parallax-window').parallax();
   }

</script>

<script>
   $('.rating_list').each(function(){
      var ratingValue = parseInt($(this).attr('rating'))
      $(this).children('li').each(function(i){
         if(i < ratingValue){
            $(this).addClass('active');
         }
      })
   });
</script>
<script>
    var swiper = new Swiper(".tesiimg_slider", {
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>
<script type="text/javascript">

   $(document).ready(function(){
       main();
<?php /*
     var type = 'Home';
     var _token = '{{ csrf_token() }}';
     $.ajax({
      url:"{{ route('ajaxHomeSection') }}",
      type:"POST",
      headers:{'X-CSRF-TOKEN': _token},
      data: {
       type: type
    },
    success:function (response) {
       $('#search_activities_ul').empty();
       if(response.success) {

         // Success
         <?php foreach ($homeCms as $key => $value) { ?>
         $('#{{$value->name}}_').html(response.{{$value->name}});
        <?php } ?>
         $('#themeCategoriese_section').html(response.themeCategoriese_section);
         $('#activity_section').html(response.activity_section);
         $('#packages_section').html(response.packages_section);
         $('#nonfeaturedpackage_section').html(response.nonfeaturedpackage_section);
         $('#accommodations_section').html(response.accommodations_section);
         $('#testimonial_section').html(response.testimonial_section);
         $('#blog_section').html(response.blog_section);
         $('#team_section').html(response.team_section);
         main();
      } else if(response.message) {
        console.log('false');
     } else {
        console.log('error');
     }
  },complete:function(){
  }
});
     main();
*/ ?>
  });

</script>
@endslot

@endcomponent
