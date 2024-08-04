@component(config('custom.theme').'.layouts.main')

    @slot('title')
        {{ config('custom.app_name') }} - Login/Registration
    @endslot

    @slot('headerBlock')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css">
    <link rel="stylesheet" href="{{asset(config('custom.assets').'/css/media.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css">
    @endslot

    @slot('bodyClass')
        home_class
    @endslot
    <section>
<div class="container">
    <div class="user_profile_login login_sec">
    <div class="text_center">
        <div class="theme-title">
            <div class="title text-3xl">OTP Verify</div>
        </div>
        <!-- <div class="icon mt15"><img alt="" src="/assets/front/images/featured-icon.png" /></div> -->
    </div>
    <div class="otp_wrap">
       @include('snippets.front.flash') 
        <h3 class="para-lg bold">Please Enter the OTP to Verify your Account</h3>
        <p>An OTP (one time Password) has been sent to your email</p>
        {{ Form::open(array('url' => 'account/otp','method' => 'post','class' => 'enclude-form','id' => 'validate_otp','autocomplete' => 'off')) }}
            <div class="digit-group" data-group-name="digits" data-autosubmit="false">
                <input class="form_control" type="text" placeholder="X X X X X" id="otp" name="otp"/>
                @error('otp')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <ul class="btn_inline">
           <li> <button type="submit" class="btn-main btn-theme btn2" id="sbmtBtn">Validate OTP</button></li>
           <li><a href="{{ route('account.resend_otp') }}" class="resend-btn btn" id="ResendOtp">Resend OTP</a></li>
            </ul>
        {{ Form::close() }}
    </div>

</div>
</div>
</div>
 </section>

    @slot('bottomBlock')
    <script>
    jQuery.support.cors = true;
    if ($("#validate_otp").length > 0) {
            $("#validate_otp").validate({
                submitHandler: function(form) {
                $("#sbmtBtn").html(
                        'Please wait...'
                        );
                $("#sbmtBtn"). attr("disabled", true);
                $("#validate_otp").submit();
                }
            })
    }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/gsap.min.js"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/ScrollTrigger.min.js?v=3.4.0.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>
    <script src="{{asset(config('custom.assets').'/js/jquery.splitlines.js') }}"></script>
    <script type="text/javascript">
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 5);
        });

        $('#ResendOtp').click(function(){
            $("#ResendOtp").html(
                     'Please wait...'
                     );
            $("#ResendOtp"). attr("disabled", true);         
            });
    </script>
    @endslot
@endcomponent