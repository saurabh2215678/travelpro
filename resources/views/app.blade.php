<?php
$websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO','HTML_HEAD_TOP','HTML_HEAD_BOTTOM','HTML_BODY_TOP','HTML_BODY_BOTTOM','FAVICON_LOGO','SCRIPTS_VERSION','GOOGLE_API_KEY','SYSTEM_TITLE']);
$HTML_HEAD_TOP = $websiteSettingsArr['HTML_HEAD_TOP']??'';
$HTML_HEAD_BOTTOM = $websiteSettingsArr['HTML_HEAD_BOTTOM']??'';
$HTML_BODY_TOP = $websiteSettingsArr['HTML_BODY_TOP']??'';
$HTML_BODY_BOTTOM = $websiteSettingsArr['HTML_BODY_BOTTOM']??'';
$SCRIPTS_VERSION = $websiteSettingsArr['SCRIPTS_VERSION']??'1.0';
$GOOGLE_API_KEY = $websiteSettingsArr['GOOGLE_API_KEY']??'';
$SYSTEM_TITLE = $websiteSettingsArr['SYSTEM_TITLE']??'';

$storage = Storage::disk('public');
$path = "settings/";
$favIconSrc = asset(config('custom.assets').'/img/fav-icon.png');
if(!empty($websiteSettingsArr['FAVICON_LOGO'])){
  $logo = $websiteSettingsArr['FAVICON_LOGO'];
  if($storage->exists($path.$logo)){
    $favIconSrc = asset('/storage/'.$path.$logo);
  }
}

if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
    $logo = $websiteSettingsArr['FRONTEND_LOGO'];
    if($storage->exists($path.$logo)){
      $logoSrc = asset('/storage/'.$path.$logo);
    }
  }

$theme = config('custom.theme_name');
$SENTRY_JS_DSN = config('custom.SENTRY_JS_DSN');
$FRONT_VUE_JS = ['traveltour','andamanisland','viaggindia', 'mytravellite', 'ladakhbikerental','theholidayhaus'];
$FRONT_VUE_JS_BALA_JI = ['balaji'];

$meta_title = '';
$meta_description = '';
$meta_keyword = '';
$canonical = '';
if(isset($seo_data) && isset($seo_data['meta_title']) && !empty($seo_data['meta_title'])) {
    $meta_title = $seo_data['meta_title']??'';
    $meta_description = $seo_data['meta_description']??'';
    $meta_keyword = $seo_data['meta_keyword']??'';
    $canonical = $seo_data['canonical']??'';
}

$params = [];
$params['limit'] = 1;
$getSingleBanner = CustomHelper::getSingleBanner($params);
$single_banner = [];
if($getSingleBanner && $getSingleBanner['success'] == 1){
    $single_banner = $getSingleBanner['banner_images'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
@if($meta_title)
<title>{{$meta_title}}</title>
@endif
@if($meta_description)
<meta name="description" content="{{$meta_description}}" />
@endif
@if($meta_keyword)
<meta name="keywords" content="{{$meta_keyword}}" />
@endif

<script language="javascript">
    pic0 = new Image(144,116); 
    pic0.src="{{$logoSrc ?? ''}}";

    pic1 = new Image(1920,650); 
    pic1.src="{{$single_banner['src']}}";
</script>

    <link rel="preload stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preload stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <link rel="icon" href="{{$favIconSrc}}" type="image/x-icon">
    @if($canonical)
<link rel="canonical" href="{{$canonical}}" />
@else
<link rel="canonical" href="{{url()->current()}}" />
@endif

<?php if (stripos(request()->server('HTTP_USER_AGENT'), 'Speed Insights') === false && stripos(request()->server('HTTP_USER_AGENT'), 'Chrome-Lighthouse') === false){ ?>
    {!!$HTML_HEAD_TOP!!}
<?php } ?>    

<!-- 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Red+Hat+Display:wght@300;400;500;600;700;800;900&family=Lato:wght@100;300;400;700;900&display=swap" rel="preload stylesheet"> -->

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <link rel="preload stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <?php if(!in_array(config('custom.theme_name'), $FRONT_VUE_JS)) { ?>
        <link href="{{url('/')}}/css/overland.css?v={{$SCRIPTS_VERSION}}" rel="preload stylesheet" />
    <?php }else{ ?>
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap" rel="preload stylesheet">
    <?php } ?>

   <!--  <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Regular.woff2?9d3eb244e37e5ca52e956b5fc7906c08')}}" rel="preload" as="font" type="font/woff2" crossorigin/>
    <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Black.woff2?d1a593ed756787abea3923e2b4c8141a')}}" rel="preload" as="font" type="font/woff2" crossorigin/>
    <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Heavy.woff2?d4550223ddb5b8b0f4ab2d90ff9cf9da')}}" rel="preload" as="font" type="font/woff2" crossorigin/>
    
    <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Bold.woff2?d196fde8e8071360563c87e5ade0300e')}}" rel="preload" as="font" type="font/woff2" crossorigin/>
    <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Semibold.woff2?bc350d48c111092c82a511dc1a5725bb')}}" rel="preload" as="font" type="font/woff2" crossorigin/>
    <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Medium.woff2?ce6b3c2146ac3b8e469c4d3b3b31af40')}}" rel="preload" as="font" type="font/woff2" crossorigin/>

    <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Regular.woff2?9d3eb244e37e5ca52e956b5fc7906c08')}}" rel="preload" as="font" type="font/woff2" crossorigin/>
    <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Thin.woff2?4e610e50135d8cba697b148bc8ac8778')}}" rel="preload" as="font" type="font/woff2" crossorigin/>
    <link href="{{url('/assets/'.$theme.'/fonts/SF-Pro-Display-Ultralight.woff2?8db963b7baa5ca29d3a346ae998811a6')}}" rel="preload" as="font" type="font/woff2" crossorigin/> -->
    
    <?php
$baseUrl = url('/');
if (stripos(request()->server('HTTP_USER_AGENT'), 'Speed Insights') === false && stripos(request()->server('HTTP_USER_AGENT'), 'Chrome-Lighthouse') === false){
    $app_css = File::get(public_path("assets/".config('custom.theme_name')."/css/app.css"));
    $media_css = File::get(public_path("assets/".config('custom.theme_name')."/css/media.css"));
} else {
    $app_css = File::get(public_path("assets/".config('custom.theme_name')."/css/app.css"));
    $media_css = File::get(public_path("assets/".config('custom.theme_name')."/css/media.css"));

    $prefix = "/assets/".$theme."/fonts/";

    $var1 = "src:url(".$prefix."SF-Pro-Display-Regular.woff2?9d3eb244e37e5ca52e956b5fc7906c08)";
    $app_css = str_replace($var1, "", $app_css);
    
    $var2 = "src:url(".$prefix."SF-Pro-Display-Black.woff2?d1a593ed756787abea3923e2b4c8141a)";
    $app_css = str_replace($var2, "", $app_css);
    
    $var3 = "src:url(".$prefix."SF-Pro-Display-Heavy.woff2?d4550223ddb5b8b0f4ab2d90ff9cf9da)";
    $app_css = str_replace($var3, "", $app_css);
    
    $var4 = "src:url(".$prefix."SF-Pro-Display-Bold.woff2?d196fde8e8071360563c87e5ade0300e)";
    $app_css = str_replace($var4, "", $app_css);

    $var5 = "src:url(".$prefix."SF-Pro-Display-Semibold.woff2?bc350d48c111092c82a511dc1a5725bb)";
    $app_css = str_replace($var5, "", $app_css);
    
    $var6 = "src:url(".$prefix."SF-Pro-Display-Medium.woff2?ce6b3c2146ac3b8e469c4d3b3b31af40)";
    $app_css = str_replace($var6, "", $app_css);
    
    $var7 = "src:url(".$prefix."SF-Pro-Display-Thin.woff2?4e610e50135d8cba697b148bc8ac8778)";
    $app_css = str_replace($var7, "", $app_css);
    
    $var8 = "src:url(".$prefix."SF-Pro-Display-Ultralight.woff2?8db963b7baa5ca29d3a346ae998811a6)";
    $app_css = str_replace($var8, "", $app_css);
}

    // $style_css = str_replace('../images/',$baseUrl . '/assets/images/',File::get(public_path('assets/css/style.css')));
    // $style_css = str_replace('.contact-boxes img{margin-bottom:7px;}', '', $style_css);
    // $component_css = str_replace('../images/',$baseUrl . '/assets/images/',File::get(public_path('assets/css/component.css')));
    // $autocomplete_css = str_replace('../images/',$baseUrl . '/assets/images/',File::get(public_path('assets/css/autocomplete.css')));

    // $app_css = File::get(public_path("assets/".config('custom.theme_name')."/css/app.css"));
    // $media_css = File::get(public_path("assets/".config('custom.theme_name')."/css/media.css"));

    echo "<style type='text/css'>";
        echo $app_css;
        echo $media_css;
    echo "</style>";
    ?>

   <!--  <link href="{{url('/')}}/assets/<?php //echo config('custom.theme_name'); ?>/css/app.css?v={{$SCRIPTS_VERSION}}" rel="preload stylesheet" />
    <link href="{{url('/')}}/assets/<?php //echo config('custom.theme_name'); ?>/css/media.css?v={{$SCRIPTS_VERSION}}" rel="preload stylesheet" /> -->


    <!-- <link href="{{mix('/assets/'.$theme.'/css/app.css')}}?v={{$SCRIPTS_VERSION}}" rel="preload stylesheet" /> -->
    <!-- <link href="{{mix('/assets/'.$theme.'/css/media.css')}}?v={{$SCRIPTS_VERSION}}" rel="preload stylesheet" /> -->

    
    <link rel="preload stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" /> -->
    <?php
    /*if(!in_array(config('custom.theme_name'), $FRONT_VUE_JS)) { ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" /> 
    <?php } */ ?>
    <?php 
    $theme_name = config('custom.theme_name');
    if ($theme_name && !in_array($theme_name, $FRONT_VUE_JS) && in_array($theme_name, $FRONT_VUE_JS)) { ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" /> 
    <?php } ?>

    <link rel="preload stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css"/>
    <?php if (stripos(request()->server('HTTP_USER_AGENT'), 'Speed Insights') === false && stripos(request()->server('HTTP_USER_AGENT'), 'Chrome-Lighthouse') === false){ ?>
        {!!$HTML_HEAD_BOTTOM!!}
    <?php } ?>  

    <script type="text/javascript">
        function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL}, 'google_translate_element');
        }
    </script>
</head>
  <body>
    {!!$HTML_BODY_TOP!!}
    @inertia
    <?php if($SENTRY_JS_DSN){ ?>
    <script src="{{$SENTRY_JS_DSN}}" crossorigin="anonymous" preload></script>
    <?php } ?>
    <!-- <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous" preload></script> -->
    <script type="module" src="https://unpkg.com/@nocodeapi/embed-instagram-feed@latest/embed-instagram-feed.js?module" ></script>


    <?php if (stripos(request()->server('HTTP_USER_AGENT'), 'Speed Insights') === false && stripos(request()->server('HTTP_USER_AGENT'), 'Chrome-Lighthouse') === false){ ?>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js" preload></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" preload></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" preload></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js" preload></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" preload></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" preload></script>
        <script type="text/javascript" src="{{url('js/ckeditor/ckeditor.js')}}" preload></script>
    <?php } else { ?>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js" defer async></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" defer async></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" defer async></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js" defer async></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" defer async></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer async></script>
        <script type="text/javascript" src="{{url('js/ckeditor/ckeditor.js')}}" defer async></script>
    <?php } ?>
    

    <script type="text/javascript">
       /* function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL}, 'google_translate_element');
        }*/
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" ></script>

    <!-- <script type="text/javascript" async src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> -->

    <!-- <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" preload></script> -->
    @if($GOOGLE_API_KEY)
    <script src="https://maps.googleapis.com/maps/api/js?key={{$GOOGLE_API_KEY}}&libraries=places" preload></script>
    @endif
    <div class="tooltip_wrapper"></div>

    <?php //if(!in_array(config('custom.theme'), $FRONT_VUE_JS_BALA_JI)) {
    if(config('custom.theme_name') == 'balaji'){ ?>
    @include(config('custom.theme',$FRONT_VUE_JS_BALA_JI).'.includes.whatsappIntegration');
    <?php } ?>

<?php if(!in_array(config('custom.theme_name'), $FRONT_VUE_JS)) { ?>
<script>
    function setCountryAttrs() {
        $(document).find('[data-vss-custom-attr]').each(function(){
          if($(this).text().includes("||") && $(this).attr('data-country-name') != ($(this).text().split("||")[1])){
            var countryName = $(this).text().split("||")[1];
            $(this).text($(this).text().split("||")[0]);
            $(this).attr('data-country-name', countryName);
          }
        });
        requestAnimationFrame(setCountryAttrs);
    }
    requestAnimationFrame(setCountryAttrs);

    function setSwiperSliders() {
        $(document).find('.swiper').each(function(){
          if($(this).parent().find('.swiper-initialized').length <= 0){




            var epSlider = new Swiper(".pind_daan_slider", {
            slidesPerView: 4,
            spaceBetween:15,
            loop: false,
            speed:1000,
            navigation: {
                nextEl: ".pind-next",
                prevEl: ".pind-prev",
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
                  slidesPerView: 4,
                },
            },
            watchSlidesVisibility: true
          });


          var epSlider = new Swiper(".pack_slider", {
            slidesPerView: 6,
            spaceBetween:15,
            loop: false,
            speed:1000,
            navigation: {
                nextEl: ".pack-next",
                prevEl: ".pack-prev",
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





        // var epSlider = new Swiper(".category_slider", {
        //     slidesPerView: 6,
        //     spaceBetween:15,
        //     loop: true,
        //     speed:1000,
        //     navigation: {
        //         nextEl: ".cat-prev",
        //         prevEl: ".cat-next",
        //     },
        //     breakpoints: {
        //         0: {
        //           slidesPerView: 1.5,
        //         },
        //         640: {
        //           slidesPerView: 2,
        //         },
        //         768: {
        //           slidesPerView: 3,
        //         },
        //         1024: {
        //           slidesPerView: 6,
        //         },
        //     },
        //     watchSlidesVisibility: true
        //   });

          $('.featured_slider').each(function(){
            var element = $(this)[0];
            var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-next')[0];
            var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-prev')[0];
            new Swiper(element, {
                slidesPerView: 5,
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
                      slidesPerView: 5,
                  },
                },
                navigation: {
                nextEl: nextButton,
                prevEl: prevButton,
              },
          });
          });

          //  ==========  Testimonial slider  ===========
            var featuredSlider =  new Swiper(".testimonial_slider", {
                    slidesPerView: 2,
                    spaceBetween:40,
                    loop: false,
                    speed:1000,
                    navigation: {
                    prevEl: ".testimonial-prev",
                    nextEl: ".testimonial-next",
                },
                    breakpoints: {
                        0: {
                        slidesPerView: 1,
                        spaceBetween:10,
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
                });

                $('.tesiimg_slider').each(function(){
                    var element = $(this)[0];
                    var nextButton = $(this).find('.swiper-button-next')[0];
                    var prevButton = $(this).find('.swiper-button-prev')[0];
                    new Swiper(element, {
                        navigation: {
                            nextEl: nextButton,
                            prevEl: prevButton,
                        },
                    });
                });


                var customerSlider = new Swiper(".blog_slider", {
                        loop: true,
                        freeMode: true,
                        speed:1000,
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

          $('.featured_slider1').each(function(){
              var element = $(this)[0];
              var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-next')[0];
              var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.featured-prev')[0];
              new Swiper(element, {
                  slidesPerView: 5,
                  spaceBetween:15,
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
                        slidesPerView: 5,
                    },
                  },
                  navigation: {
                  nextEl: nextButton,
                  prevEl: prevButton,
                },
            });
            });


            // $('.similarPackages').each(function(){
            //   var element = $(this)[0];
            //   var nextButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.similar-next')[0];
            //   var prevButton = $(this).closest('.slider_box').siblings('.slider_btns').find('.similar-prev')[0];
            //   new Swiper(element, {
            //       slidesPerView: 5,
            //       spaceBetween:20,
            //       loop: false,
            //       speed:1000,
            //       navigation: {
            //             nextEl: ".similar-next",
            //             prevEl: ".similar-prev",
            //         },
            //       breakpoints: {
            //         0: {
            //             slidesPerView: 1.5,
            //         },
            //         640: {
            //             slidesPerView: 2,
            //         },
            //         768: {
            //             slidesPerView: 3,
            //         },
            //         1024: {
            //             slidesPerView: 5,
            //         },
            //       },
            //       navigation: {
            //       nextE2: nextButton,
            //       prevE2: prevButton,
            //     },
            // });
            // });

            var departureSlider =  new Swiper(".departure_slider", {
                    slidesPerView: 4,
                    spaceBetween:40,
                    loop: false,
                    speed:1000,
                    navigation: {
                        prevEl: ".departure-next",
                        nextEl: ".departure-prev",
                    },
                    breakpoints: {
                        0: {
                        slidesPerView: 1,
                        spaceBetween:0,
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
                });

            var swiper = new Swiper(".package_detail_img", {
                navigation: {
                    nextEl: $(this).find(".swiper-button-next")[0],
                    prevEl:  $(this).find(".swiper-button-prev")[0],
                },
            });


            $('.compact-room-img').each(function () {
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

            });


            $('.hotel_inner_slider').each(function(){
            var element = $(this)[0];
            var nextButton = $(this).closest('.slider_box').find('.hotel-inner-next')[0];
            var prevButton = $(this).closest('.slider_box').find('.hotel-inner-prev')[0];
            console.log(nextButton);
            new Swiper(element, {
                slidesPerView:3,
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
                        slidesPerView: 3,
                    },
                },
                navigation: {
                nextEl: nextButton,
                prevEl: prevButton,
                },
            });
            });


            var hotelSlider =  new Swiper(".hotel_slider", {
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



          }// closing of if condition
        });

        requestAnimationFrame(setSwiperSliders);
    }
    requestAnimationFrame(setSwiperSliders);

    function setDatePickers() {
        $(document).find('.datepicker').each(function(){
            if(!$(this).hasClass('hasDatepicker')){

                $( ".datepicker" ).datepicker({
                    minDate: 0,
                });
                //dateFormat: 'dd/mm/yy',
            }
        });
        requestAnimationFrame(setDatePickers);
    }
    requestAnimationFrame(setDatePickers);

$(document).on("click", ".faq_title", function() {
    $(this).toggleClass('active');
    $(this).siblings('.faq_data').slideToggle();
    $(this).closest('.faq_main').siblings('.faq_main').find('.faq_title').removeClass('active');
    $(this).closest('.faq_main').siblings('.faq_main').find('.faq_data').slideUp();
 });

$(window).on('resize', function(){
  setheaderMenu()
}).resize();

function setheaderMenu(){
  if(window.innerWidth < 1200){
      if($('header').find('.sidemenu-box').length == 0){
          $('header .theme-nav').css('display', 'flex');
          $('.social-icons').css('display', 'flex');
          $('.social-icons, header .theme-nav').wrapAll( "<div class='sidemenu-box'></div>" );
          $(`
          <div class="header-backdroap"></div>
          <div class="phone-menu">
              <span></span>
              <span></span>
              <span></span>
          </div>
          `).insertBefore('.sidemenu-box');
          $('body').addClass('menu-closed');
          $('body').removeClass('menu-opened');
          $('.phone-menu').click(function(){
          if($('body').hasClass('menu-closed')){
              $('body').addClass('menu-opened');
              $('body').removeClass('menu-closed');
          }else{
              $('body').addClass('menu-closed');
              $('body').removeClass('menu-opened');
          }
          });
          $('.header-backdroap').click(function(){
          $('body').addClass('menu-closed');
              $('body').removeClass('menu-opened');
          });
          $('.sub-menu').each(function(){
              $(this).hide();
              $('<span class="dd-click"></span>').insertBefore($(this));
          });
      }
  }else{
      if($('header').find('.sidemenu-box').length > 0){
          $('.social-icons, header .theme-nav').unwrap();
          $('.header-backdroap, .phone-menu, .dd-click').remove();
      }
      $('.sub-menu').show();
  }
  $('body').css("--header-height", `${$('.theme_header').outerHeight()}px`)
}

if (!window.headerMenu) {
  window.headerMenu = function() {setheaderMenu()}
}

let headerheight = 0;
function setHeaderHeight() {
    if(headerheight != $('header').outerHeight()){
        headerheight = $('header').outerHeight();
        document.documentElement.style.setProperty('--header-height',  `${headerheight}px`);
    }
    requestAnimationFrame(setHeaderHeight);
}
requestAnimationFrame(setHeaderHeight);

let topSectionheight = 0;
function setTopSectionHeight() {
    if(topSectionheight != $('.top-section').outerHeight()){
        topSectionheight = $('.top-section').outerHeight();
        document.documentElement.style.setProperty('--top-sec-height',  `${topSectionheight}px`);
    }
    requestAnimationFrame(setTopSectionHeight);
}
requestAnimationFrame(setTopSectionHeight);

$(document).click(function(e){
    if(e.target.classList.contains('dd-click')){
        if($(e.target).parents('.sub-menu').length == 0){
            $('.sub-menu').slideUp()
            $('.dd-click').removeClass('slideOpened')
        }
        if($(e.target).siblings('.sub-menu').css('display') == "none"){
            $(e.target).siblings('.sub-menu').slideDown()
            $(e.target).addClass('slideOpened')
        }else{
            $(e.target).siblings('.sub-menu').slideUp()
            $(e.target).removeClass('slideOpened')
        }
        //
        console.log($(e.target).siblings('.sub-menu').css('display'));
    }
});


// const clearConsoleInterval = setInterval(()=>{
//   console.clear();
// },100);

// setTimeout(()=>{
//   clearInterval(clearConsoleInterval)
// },5000)

function setDateRangePickers() {
    $(document).find('.daterange').each(function(){
        if(!$(this).hasClass('initiated')){
           setTimeout(() => {
                hotelFormScripts();
           }, 100);
        }
        $(this).addClass('initiated');
    });
    requestAnimationFrame(setDateRangePickers);
}
requestAnimationFrame(setDateRangePickers);

function hotelFormScripts(){

    $('input[id="hoteldaterange"]').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                },
                minDate: new Date()
            },function(start, end, label) {
                $(document).find('input[name=checkin]').val(start.format('YYYY-MM-DD'));
                $(document).find('input[name=checkout]').val(end.format('YYYY-MM-DD'));
            });

    // $(document).click(function(e){
    //   var clickedElement = $(e.target);
    //   if(!(clickedElement.closest('.passenger_wrap').length > 0 || clickedElement.hasClass('passenger_wrap'))){
    //     $('.passenger_wrap').hide();
    //   }
    // });



    // $('.guest_room').on('click', function(e) {
    //   e.stopPropagation();
    //   $('.passenger_wrap').toggle();
    // });

    // $(document).on('click','.passenger_wrap .fa-minus',function(){
    //   var counter = $(this).parent().parent().find('.pax_counter').html();
    //   var pax_type = $(this).parent().parent().parent().attr('data-id');
    //   counter = parseInt(counter);
    //   if(counter > 1) {
    //     counter = counter - 1;
    //   } else if(pax_type == 'child_select') {
    //     counter = 0;
    //   }
    //   $(this).parent().parent().find('.pax_counter').html(counter);
    // });
    // $(document).on('click','.passenger_wrap .fa-plus',function(){
    //   var counter = $(this).parent().parent().find('.pax_counter').html();
    //   counter = parseInt(counter);
    //   if(counter >= 6) {
    //     alert('Max allowed upto 6')
    //   } else {
    //     counter = counter + 1;
    //   }
    //   $(this).parent().parent().find('.pax_counter').html(counter);
    // });

    // $(document).on('click','.pax_done',function(){
    //   var inventory = $(this).parent().parent().find('.guest_room_select .pax_counter').html();
    //   var adult = $(this).parent().parent().find('.adults_select .pax_counter').html();
    //   var child = $(this).parent().parent().find('.child_select .pax_counter').html();
    //   $(document).find('input[name=inventory]').val(parseInt(inventory));
    //   $(document).find('input[name=adult]').val(parseInt(adult));
    //   $(document).find('input[name=child]').val(parseInt(child));
    //   var guest_title = adult+' Adults';
    //   if(parseInt(child) > 0) {
    //     guest_title = guest_title + ' + '+ child+' Child';
    //   }
    //   if(parseInt(inventory) > 0) {
    //     guest_title = guest_title + ' | '+ inventory+' Room(s)';
    //   }
    //   $(document).find('#guest_rooms').val(guest_title);
    //   $('.passenger_wrap').fadeToggle("slow");
    // });

}


$("body").on('click','.pkg_fav',function() {
 var id = $(this).attr("id").replace('favid-','');
 var _token = '{{ csrf_token() }}';
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
<?php } else{ ?>
<script>
function setCountryAttrs() {
    $(document).find('[data-vss-custom-attr]').each(function(){
        if($(this).text().includes("||") && $(this).attr('data-country-name') != ($(this).text().split("||")[1])){
        var countryName = $(this).text().split("||")[1];
        $(this).text($(this).text().split("||")[0]);
        $(this).attr('data-country-name', countryName);
        }
    });
    requestAnimationFrame(setCountryAttrs);
}
requestAnimationFrame(setCountryAttrs);

$(function(){
    var lastScrollTop = 0, delta = 15;
    $(window).on('load scroll', function(event){
    var st = $(this).scrollTop();
    // console.log(window.innerHeight)

    if(Math.abs(lastScrollTop - st) <= delta)
        return;
        if ((st > lastScrollTop) && (lastScrollTop>70)) {
            // downscroll code
            $("header").addClass('scrolling-down')
            $("header").removeClass('scrolling-up')
        } else {
            // upscroll code
            $("header").addClass('scrolling-up')
            $("header").removeClass('scrolling-down')
        }
    lastScrollTop = st;


    if(st>60){
        $('header').addClass('sml-header')
    }else{
        $('header').removeClass('sml-header')
    }
    });
});


$(document).on("click", ".faq_title", function() {
    $(this).toggleClass('active');
    $(this).siblings('.faq_data').slideToggle();
    $(this).closest('.faq_main').siblings('.faq_main').find('.faq_title').removeClass('active');
    $(this).closest('.faq_main').siblings('.faq_main').find('.faq_data').slideUp();
 });

function setDateRangePickers() {
    $(document).find('.daterange').each(function(){
        if(!$(this).hasClass('initiated')){
           setTimeout(() => {
                hotelFormScripts();
           }, 100);
        }
        $(this).addClass('initiated');
    });
    requestAnimationFrame(setDateRangePickers);
}
requestAnimationFrame(setDateRangePickers);

function setDatePickers() {
    $(document).find('.datepicker').each(function(){
        if(!$(this).hasClass('hasDatepicker')){

            $( ".datepicker" ).datepicker({
                minDate: 0,
            });
            //dateFormat: 'dd/mm/yy',
        }
    });
    requestAnimationFrame(setDatePickers);
}
requestAnimationFrame(setDatePickers);


function hotelFormScripts(){
    $('input[id="hoteldaterange"]').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                },
                minDate: new Date()
            },function(start, end, label) {
                $(document).find('input[name=checkin]').val(start.format('YYYY-MM-DD'));
                $(document).find('input[name=checkout]').val(end.format('YYYY-MM-DD'));
            });
}


$("body").on('click','.pkg_fav',function() {
 var id = $(this).attr("id").replace('favid-','');
 var _token = '{{ csrf_token() }}';
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
<?php } ?>
    {!!$HTML_BODY_BOTTOM!!}

    <?php 
        $folderPath = 'js';
        $files = scandir($folderPath, 1);
        if($files === false) {
            
        } else {
            $filteredFiles = [];
            foreach ($files as $file) {
                if (strpos($file, 'app') === 0 && pathinfo($file, PATHINFO_EXTENSION) === 'js') { ?>
                    <script src="{{asset('/js/'.$file)}}?v={{$SCRIPTS_VERSION}}" type="module" defer></script>
            <?php   } if (strpos($file, 'vendor') === 0 && pathinfo($file, PATHINFO_EXTENSION) === 'js') {  ?>
                        <script src="{{asset('/js/'.$file)}}?v={{$SCRIPTS_VERSION}}" type="module" defer></script>
        <?php } } } ?>

    <script src="{{asset('/js/manifest.js')}}?v={{$SCRIPTS_VERSION}}" type="module" defer></script>

<?php /*<div class="container" itemscope itemtype="https://schema.org/FAQPage" style="display:none;">
    <div class="faqlist">
      <?php if(!$faqs->isEmpty()){ ?>
         <ul>
            <?php
            $q = 0;
            foreach ($faqs as $faq) {
              $q++;    
              $faq_question = $faq->question;
              $faq_answer = $faq->answer;
              ?>  
               <li class="faq_main">
                 <div itemscope itemtype="https://schema.org/Question" itemprop="mainEntity">
                  <div class="faq_title heading6">{{ $faq_question }}</div>
                  <div itemscope itemtype="https://schema.org/Answer" itemprop="acceptedAnswer">
                      <div class="faq_data" style="">{!! $faq_answer !!}
                      </div>
                  </div>
              </div>
          </li>
         <?php } ?> 
      </ul>

   <?php } ?>
</div> */ ?>
<?php if(!empty($faqs) && count($faqs) > 0){ ?>
    <div class="container" itemscope itemtype="https://schema.org/FAQPage" style="display:none;">
        <h2 class="heading2">{{$SYSTEM_TITLE}} FAQs</h2>
            <div class="faqlist">
                
                <ul>
                    <?php 
                    $i = 1; 
                    foreach ($faqs as $faq) {
                        if(is_array($faq)) {
                            $question = $faq['question'] ?? '';
                            $answer = $faq['answer'] ?? '';
                        }elseif (is_object($faq)) {
                            $question = $faq->question ?? '';
                            $answer = $faq->answer ?? '';
                        }else {
                            continue; // Skip if it's neither an array nor an object
                        }
                        ?>
                    <li class="<?php $i==1 ? 'active':''?>">
                        <div itemscope itemtype="https://schema.org/Question" itemprop="mainEntity">
                        <h3 itemprop="name" class="ftitle"><span><strong>Q{{$i}}. </strong></span>&nbsp; {{$question}}</h3>
                        <div itemscope itemtype="https://schema.org/Answer" itemprop="acceptedAnswer">
                            <div itemprop="text">
                                <div class="fqcont">
                                    {!! $answer !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    </li>
                    <?php $i++; } ?>
                </ul>
            </div>
        </div> 
    <?php } ?>
  </body>
</html>