<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css" />
<?php
$websiteSetting = CustomHelper::getSettings(['FACEBOOK','TWITTER','INSTAGRAM','LINKEDIN','YOUTUBE','HTML_VALIDATION','FRONTEND_LOGO','SITE_ADDRESS','SITE_EMAIL','SITE_PHONE','RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY']);

$storage = Storage::disk('public');
   $path = "settings/";
   //prd($websiteSettingsArr);
   $logoSrc = asset(config('custom.assets').'/images/footer_logo.png');

   if(!empty($websiteSetting['FRONTEND_LOGO'])){
      $logo = $websiteSetting['FRONTEND_LOGO'];
      if($storage->exists($path.$logo)){
         $logoSrc = asset('/storage/'.$path.$logo);
      }
   }
$address = $websiteSetting['SITE_ADDRESS']??'';
$email = $websiteSetting['SITE_EMAIL']??''; 
$phone = $websiteSetting['SITE_PHONE']??'';
$facebook = $websiteSetting['FACEBOOK']??'';
$twitter = $websiteSetting['TWITTER']??''; 
$instagram = $websiteSetting['INSTAGRAM']??''; 
$linkedin = $websiteSetting['LINKEDIN']??''; 
$youtube = $websiteSetting['YOUTUBE']??''; 
$HTML_VALIDATION = $websiteSetting['HTML_VALIDATION']??''; 

$RECAPTCHA_SITE_KEY = $websiteSetting['RECAPTCHA_SITE_KEY']??''; 
$RECAPTCHA_SECRET_KEY = $websiteSetting['RECAPTCHA_SECRET_KEY']??''; 
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY']);

// $topMenu = CustomHelper::getMenu('main-footer-menu');
// $menuItems = (isset($topMenu->menuParentItems))?$topMenu->menuParentItems:'';
$menuItems = CustomHelper::getMenuParentItems('main-footer-menu');
$menuItemsList = CustomHelper::getMenuForFront($menuItems, $is_parent = true, $parent_class='theme-nav', $child_class='', $child_parent_class='sub-menu', $have_child_class='');
$SENTRY_JS_DSN = config('custom.SENTRY_JS_DSN');
?>

<a id="to_top_scrollup"></a>

<!-- <footer class="theme_footer">
    <div class="xl:container xl:mx-auto">
        <div class="newslateer_inner">
            <div class="newsletter_left">
                <div class="text_lg heading2">Newsletter Subscription</div>
            </div>
            <div class="newsletter_right">
                <div class="newsletter_input">
                    <div class="newsletter_input_inner">
                    <input type="text" class="form_flied subscribe_email" placeholder="Enter Your Email Address"  name="subscribe_email">
                    </div>
                    <button class="btn subscribeBtn">Send</button>
                    <span class="newsletter_messages ml-5"></span>
                </div>
            </div>
        </div>
    </div> 
</footer> -->
<div class="footer_bottom" >
    <div class="xl:container xl:mx-auto">
        <div class="footer_bottom_inner pb-6">
            
            <div class="footer_bottom_right ">
              {!! $menuItemsList !!}
            </div>
            <div class="footer_bottom_left">
              <!-- <div class="footer_logo"><a href="{{url('/')}}"><img src="{{ $logoSrc }}" /></a></div> -->

              <p class="follow_us">Follow Us: </p>
                <ul class="social_icon">
                <?php if($facebook){ ?>
                  <li class="facebook"><a href="{{ $facebook }}" target="_blank" ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg></a></li>
                <?php } ?>
                <?php if($twitter){ ?>
                  <li class="twitter"><a href="{{ $twitter }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg></a></li>
                <?php } ?>
                <?php if($instagram){ ?>
                  <li class="instagram"><a href="{{ $instagram }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a></li>
                  <?php } ?>
                  <?php if($linkedin){ ?>
                  <li class="linkedin"><a href="{{$linkedin}}" rel="nofollow" target="_blank" hreflang="en"><i class="fa fa-linkedin"></i></a></li>
                <?php } ?>

                <?php if($youtube){ ?>
                  <li class="youtube"><a href="{{$youtube}}" rel="nofollow" target="_blank" hreflang="en"><i class="fa fa-youtube"></i></a></li>
                <?php } ?>
                </ul>
              <div class="copyright text-sm"> &copy; {{ date('Y') }} {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}} All Rights Reserved.</a></div>
              <a href="tel:+918934011111" class="whatsapp-box" target="_blank"><i class="fa-solid fa-phone-volume"></i></a>
            </div>
            

              <div class="footer_bottom_right">
                <div class="footer_heading mb-5">Contact Us</div>
                  <ul class="links_list">

                    @if(!empty($address))
                    <li class="pb-2.5">
                        <div class="footer_icon mr-2.5">
                          <img src="{{ asset(config('custom.assets').'/img/location.png') }}" alt="Location">
                        </div>
                        <div class="footer_add">
                          {!! $address !!}
                        </div>
                      </li>
                      @endif

                      @if(!empty($phone))
                      <li class="pb-2.5">
                        <div class="footer_icon mr-2.5">
                          <img src="{{ asset(config('custom.assets').'/img/telephone.png') }}" alt="Telephone">
                        </div>
                        {!! $phone !!}
                      </li>
                      @endif
                      
                      @if(!empty($email))
                      <li class="pb-2.5">
                        <div class="footer_icon mr-2.5 mt-1.5">
                          <img src="{{ asset(config('custom.assets').'/img/mail.png') }}" alt="Email"> 
                        </div>
                        <div class="footer_add">
                          {!! $email !!}
                        </div>
                      </li>
                      @endif
                  </ul>
                  <!-- <div class="footer_heading text-lg mt-5 mb-3">Newsletter Subscription</div>
                    <input type="email" class="form-control sub_input"  aria-describedby="emailHelp" placeholder="Enter email address">
                    <button class="subscribe_btn">Subscribe Now</button> -->
                  
                </div>


                <div class="footer_bottom_right">
                    <div class="footer_heading text-lg mb-5">Pay Safely With Us</div>
                    <p class="footer_text">The payment is encrypted and transmitted securely with an SSL protocol.</p>
                    <img src="{{ asset(config('custom.assets').'/images/payment_img.jpg') }}" class="paymnt_img">
                </div>

            </div>


           
        </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/ScrollTrigger.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
<script src="{{ asset(config('custom.assets').'/js/script.js') }}"></script>
<script src="{{ asset(config('custom.assets').'/js/form_validation.js') }}"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<?php if($SENTRY_JS_DSN){ ?>
<script src="{{$SENTRY_JS_DSN}}" crossorigin="anonymous"></script>
<?php } ?>

<script>

$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
  });
});
</script>

<script type="text/javascript">

  var btn = $('#to_top_scrollup');

    $(window).scroll(function() {
    if ($(window).scrollTop() > 300) {
    btn.addClass('show');
    } else {
    btn.removeClass('show');
    }
    });

    btn.on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop:0}, '300');
    });

    
</script>



<script type="text/javascript">
  // ======== Hotel Search header sticky code ======
// $(window).scroll(function() {    
//     var scroll = $(window).scrollTop();    
//     if (scroll <= 500) {
//         $(".main-header").removeClass("main-header").addClass("sticky_header");
//     }
// }


$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    if (scroll >= 150) {
        $(".main-header").addClass("sticky_header");
    } else {
        $(".main-header").removeClass("sticky_header");
    }
});
</script>

<script type="module" defer>
  import { polyfillCountryFlagEmojis } from "https://cdn.skypack.dev/country-flag-emoji-polyfill";
  polyfillCountryFlagEmojis();
</script>

<script>
    var swiper = new Swiper(".package_detail_img", {
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
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
  
<script type="text/javascript">
  // ============== Header Banner ================

   var swiper = new Swiper(".header_inner_banner_main", {
     slidesPerView: 1,
      loop: true,
      parallax:true,
      speed:1000,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
    });
//$(document).ready(function () {
    //Disable full page
    //$('body').bind('cut copy paste', function (e) {
      //  e.preventDefault();
    //});
  //  document.addEventListener('contextmenu', event => event.preventDefault());
//});
</script>
<script>
  $('.icon_calender').click(function(){
      $('#trip_date').blur();
   })

  $('.js-monthpicker').monthpicker({ altFormat: 'MM yy', minDate: 0 });
</script>
<script>
  $(document).ready(function() {
    
      $(function() {
          $( ".datepicker" ).datepicker({
            minDate: 0
          });
      });
  })
</script>

<script>  
  $(function() {  
    $( "#accordion" ).accordion();  
  });  
  </script> 
<script>
    AOS.init();
    AOS.init({disable: 'mobile'});
    $(function(){
        var lastScrollTop = 0, delta = 15;
        $(window).on('load scroll', function(event){
        var st = $(this).scrollTop();
        // console.log(window.innerHeight)
        
        if(Math.abs(lastScrollTop - st) <= delta)
            return;
            if ((st > lastScrollTop) && (lastScrollTop>150)) {
                // downscroll code
                $("header").addClass('scrolling-down')
                $("header").removeClass('scrolling-up')
            } else {
                // upscroll code
                $("header").addClass('scrolling-up')
                $("header").removeClass('scrolling-down')
            }
        lastScrollTop = st;


        if(st>150){
            $('header').addClass('sml-header')
        }else{
            $('header').removeClass('sml-header')
        }
        });
    });

    $('.events .prd-btns .btn-outline').each(function(){
        if($(this).text().length >= 12 ){
            $(this).css('width', '100%')
        }
    });   
</script>
<!-- Useing ajax Newsletter Subscriber -->
<script>
$('.subscribeBtn').click(function(e)
{
   e.preventDefault();

   var currSelector = $(this);
   var y= true;
   var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/; 
   var email = $("input[name='subscribe_email']").val().trim();
   //alert(email); return false;

   if(email==''){

     $('.newsletter_messages').html('<span style="color:#e41881">Email is required.</span>');
     y=false;
   } 
   if(email!=''){
    if (!filter.test(email)){
      $('.newsletter_messages').html('<span style="color:#e41881">Invalid Email.</span>');
      y=false;
    }
  }
   
  if(y) {
    var _token = '{{csrf_token()}}';
    $.ajax({
      url : "{{route('newsletterSubscribe')}}",
      type : 'POST',
      data : {email:email},
      dataType: 'JSON',
      async : false,
      headers:{'X-CSRF-TOKEN': _token},
      success: function(resp){
        if(resp.success){

          if(resp.message){
            $('.newsletter_messages').html('<span>'+resp.message+'</span>');
            $(".subscribe_email").val('');
            $(".newsletter_messages").addClass('succ_msg');
            //currSelector.siblings(".newsletter_messages").fadeOut(3000);

          }

        }
        else{
          if(resp.message){
            $('.newsletter_messages').html('<span>'+resp.message+'</span>');
            $(".subscribe_email").val('');
          }
        }
      }

    });
  }
   return false; 
});

</script>
<!-- Useing ajax Newsletter Subscriber closed -->


<script>
 //   alert($('.phone').length);
 /*function phoneControl () {
    $('.phone').each(function(){
        var name = $(this).attr('name');
         $(this).siblings('input[type=hidden]').attr('name', `${name}_full_number`);
        var phoneNunmber = $(this).val();
        // var countrycode = window.intlTelInput($(this)[0]);
        var countrycode = $(this).siblings('.iti__flag-container').find('.iti__selected-flag').attr('title')?.split(':')[1];
        $(this).siblings('input[type=hidden]').val(countrycode + phoneNunmber);
    })
 }


 $(document).on('keyup keydown load click', phoneControl);*/


 $(document).ready(function() {
  // Rakshit 
//  $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
//   $(".datetimepiker").datetimepicker({format:'Y-m-d H:m'});
//    $('.timepicker').timepicker({ timeFormat: 'HH:mm'});
// Rakshit end


     /*var phoneInputID = ".phone";
     var input = document.querySelectorAll(phoneInputID);
     input.forEach((userItem) => {
     
      var Vlaue123 = userItem.name
        var iti = window.intlTelInput(userItem, {

            formatOnDisplay: true,

            hiddenInput: "full_number",

            preferredCountries: ['in'],

            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
        });

        iti.promise.then(function() {
            $(phoneInputID).trigger("countrychange");
            phoneControl ();
        }); 

        $(phoneInputID).on("countrychange", function(event) {

            var selectedCountryData = iti.getSelectedCountryData();

            $('#country').val(selectedCountryData.name);


            newPlaceholder = intlTelInputUtils.getExampleNumber(selectedCountryData.iso2, true, intlTelInputUtils.numberFormat.INTERNATIONAL),


            iti.setNumber("");
            mask = newPlaceholder.replace(/[1-9]/g, "0");
        }); 

    });*/

 });


/*$(document).on('keypress','.phone',function(){


})*/





/* jQuery(document).ready(function () {
  jQuery(".phone").keypress(function (e) {
   var length = jQuery(this).val().length;
   if(length > 9) {
    return false;
} else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    return false;
} else if((length == 0) && (e.which == 48)) {
    return false;
}
});
});
*/

//==============

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
<?php if($HTML_VALIDATION==1){?>

<script type="text/javascript">
  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          } else {
            var formID=form.id;
            var _token = '{{ csrf_token() }}';
            var boxText=$('#'+formID).find('.btnSubmit').html(); 
            $('.btnSubmit').html('Please wait...');
            $(".btnSubmit"). attr("disabled", true);
            $.ajax({
              url: "{{ url('/forms') }}",
              type: "POST",
              data: new FormData($('#'+formID)[0]),
              processData: false,
              contentType: false,
              dataType:"JSON",
              headers:{'X-CSRF-TOKEN': _token},
              enctype: 'multipart/form-data',
              cache: false,
              beforeSend:function() {
                $('#'+formID).find('.ajax_msg').html('');
                $('#'+formID).find('.error').html('');
                $('#'+formID).find('.btnSubmit').html('Please wait...');
              },
              success: function(response) {
                $('.btnSubmit').html('Submit');
                $(".btnSubmit"). attr("disabled", false);
                event.preventDefault();
                event.stopPropagation();
                if(response.success) {
                  $('#'+formID).find(".ajax_msg").html(response.message);
                  $(".btnSubmit").attr("disabled", false);
                  $('#'+formID).find('.ajax_msg').addClass('success');
                  $('#'+formID).find('.form-floating').hide('');
                  $('#'+formID).find('.btn-book-space').hide('');
                  $('#'+formID).find('.ajax_msg').removeClass('error');
                  $('#'+formID)[0].reset();
                  $('#'+formID).removeClass('was-validated');
                  $('#'+formID).find(".error").hide();
                  $('#'+formID).find('.btnSubmit').html(boxText);
                  if(response.redirect_url) {
                    setTimeout(function(){
                      window.location.href = response.redirect_url;
                    },500);
                  }
                } else {
                  parseFormsErrorMessages(response, formID, boxText);
                }
              },
              error: function(e) {
                var response = e.responseJSON;
                if(response) {
                  parseFormsErrorMessages(response, formID, boxText);
                }
              }
            });
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();

  $(document).on('click','.btnReset',function(event){
    var form = $(this).closest("form");
    event.preventDefault();
    event.stopPropagation();
    var formID=form.attr('id');
    $('#'+formID).find('.ajax_msg').html('');
    $('#'+formID).find('.error').html('');
    $('#'+formID).trigger('reset');
  });

  function parseFormsErrorMessages(response, formID, boxText) {
    if(response.message) {
      $('#'+formID).find('.ajax_msg').addClass('error');
      $('#'+formID).find('.ajax_msg').removeClass('success');
      $('#'+formID).find(".ajax_msg").html(response.message);
    }
    if(response.errors_fields) {
      var errors = response.errors_fields;//$.parseJSON(resp.errors_fields);
      var message='';
      $.each(errors, function (key, val) {
        $('#'+formID).find("." + key + "_error").text(val[0]);
      });
      $(".btnSubmit").attr("disabled", false);
      $('#'+formID).find('.ajax_msg').removeClass('success');
      $('#'+formID).find('.ajax_msg').addClass('error');
      $('#'+formID).find(".error").show();
      $('#'+formID).find('.btnSubmit').html(boxText);
      grecaptcha.ready(function () {
        grecaptcha.execute("{{$RECAPTCHA_SITE_KEY}}", { action: 'forms' }).then(function (token) {
          $('.g-recaptcha-response').val(token);
        });
      });
    }
  }
</script>
<?php }else{ ?>
<script type="text/javascript">
  $(document).on('click','.btnSubmit',function(event){
    var form = $(this).closest("form");//.getElementsByClassName('needs-validation');
    event.preventDefault();
    event.stopPropagation();
    var formID=form.attr('id');
    var _token = '{{ csrf_token() }}';
    var boxText=$('#'+formID).find('.btnSubmit').html();
    $.ajax({
      url: "{{ url('/forms') }}",
      type: "POST",
      data: new FormData($('#'+formID)[0]),
      processData: false,
      contentType: false,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      enctype: 'multipart/form-data',
      cache: false,
      beforeSend:function() {
        $('#'+formID).find('.ajax_msg').html('');
        $('#'+formID).find('.error').html('');
        $('#'+formID).find('.btnSubmit').html('Please wait...');
      },
      success: function(resp) {
        if(resp.success) {
          $('#'+formID).find(".ajax_msg").html(resp.message);
          $(".btnSubmit").attr("disabled", false);
          $('#'+formID).find('.ajax_msg').addClass('success');
          $('#'+formID).find('.ajax_msg').removeClass('error');
          $('#'+formID)[0].reset();
          $('#'+formID).removeClass('was-validated');
          $('#'+formID).find(".error").hide();
          $('#'+formID).find('.btnSubmit').html(boxText);
          if(resp.redirect_url) {
            setTimeout(function(){
              window.location.href = resp.redirect_url;
            },500);
          }
        } else {
          var errors = resp.errors_fields;//$.parseJSON(resp.errors_fields);
          var message='';
          $.each(errors, function (key, val) {
            $('#'+formID).find("." + key + "_error").text(val[0]);
          });
          $(".btnSubmit").attr("disabled", false);
          $('#'+formID).find('.ajax_msg').removeClass('success');
          $('#'+formID).find('.ajax_msg').addClass('error');
          $('#'+formID).find(".error").show();
          $('#'+formID).find('.btnSubmit').html(boxText);
          grecaptcha.ready(function () {
            grecaptcha.execute("{{$RECAPTCHA_SITE_KEY}}", { action: 'forms' }).then(function (token) {
              $('.g-recaptcha-response').val(token);
            });
          });
        }
      }
    });
  });

  $(document).on('click','.btnReset',function(event){
    var form = $(this).closest("form");
    event.preventDefault();
    event.stopPropagation();
    var formID=form.attr('id');
    $('#'+formID).find('.ajax_msg').html('');
    $('#'+formID).find('.error').html('');
    $('#'+formID).trigger('reset');
  });

  

</script>
<?php } ?>

<script type="text/javascript">
function getPrice(amount) {
  if(amount) {
    if(parseInt(amount) > 0) {
      amount = amount.toString();
      var lastThree = amount.substring(amount.length-3);
      var otherNumbers = amount.substring(0,amount.length-3);
      if(otherNumbers != '') lastThree = ',' + lastThree;
      amount = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
    }
  }
  return '₹'+amount;
}

</script>
