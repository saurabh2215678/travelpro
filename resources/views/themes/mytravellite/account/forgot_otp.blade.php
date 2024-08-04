@component(config('custom.theme').'.layouts.main')

    @slot('title')
        {{ $meta_title ?? ''}}
    @endslot

    @slot('headerBlock')

    @endslot

    @slot('bodyClass')
        home_class
    @endslot
    <section>
<div class="container">
    <div class="user_profile_login login_sec">
    <div class="text_center">
        <div class="theme-title">
            <div class="text-2xl">Forgot Password OTP</div>
        </div>
    </div>
    <div class="otp_wrap">
       @include('snippets.front.flash') 
        <h3 class="para-lg bold">Please Enter the OTP to Verify your Account</h3>
        <!-- <p>A OTP (one time Password) has been sent to your email</p> -->
        {{ Form::open(array('url' => 'account/forgot-otp','method' => 'post','class' => 'enclude-form','id' => 'validate_otp','autocomplete' => 'off')) }}
            <div class="digit-group mb16" data-group-name="digits" data-autosubmit="false">
                <input class="form_control bg-transparent" type="text" placeholder="X X X X X" id="otp" name="otp"/>
                @error('otp')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <ul class="btn_inline">
                <li><a href="{{ route('account.resend_forgot_otp') }}" class="btn2" id="ResendForgotOtp">Resend OTP</a></li>
                <li><button type="submit" class="btn" id="sbmtBtn">Validate OTP</button></li>
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
                form.submit();
                }
            })
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/gsap.min.js"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/ScrollTrigger.min.js?v=3.4.0.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.min.js"></script>
    <script src="{{asset(config('custom.assets').'/js/jquery.splitlines.js') }}"></script>
    <script type="text/javascript">
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 5);
        });

        var clicked = false;
        $('#ResendForgotOtp').click(function(e){
            if(clicked===false){ clicked=true; } else { e.preventDefault(); }
            $("#ResendForgotOtp").html('Please wait...');
            $("#ResendForgotOtp").attr("disabled", true);         
            //$("#ResendForgotOtp").attr("href", "javascript:void(0);");         
        });

    </script>
    @endslot
@endcomponent