<?php $websiteSetting = CustomHelper::getSettings(['FACEBOOK','TWITTER','INSTAGRAM','LINKEDIN','HTML_VALIDATION','FRONTEND_LOGO','SITE_ADDRESS','SITE_EMAIL','SITE_PHONE','SITE_PHONE1','SITE_PHONE2','RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY']);

$HTML_VALIDATION = $websiteSetting['HTML_VALIDATION']??'';  
$RECAPTCHA_SITE_KEY = $websiteSetting['RECAPTCHA_SITE_KEY']??'';  ?>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
<script src="{{ asset(config('custom.assets').'/js/script.js') }}"></script>
<script src="{{ asset(config('custom.assets').'/js/form_validation.js') }}"></script>



<script type="module" defer>
  import { polyfillCountryFlagEmojis } from "https://cdn.skypack.dev/country-flag-emoji-polyfill";
  polyfillCountryFlagEmojis();
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