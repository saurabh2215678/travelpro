@component(config('custom.theme').'.layouts.main')

    @slot('title')
        OTP Verify - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
            <div class="text-2xl">OTP Verify</div>
        </div>
    </div>
    <div class="otp_wrap">
       @include('snippets.front.flash') 
        <h3 class="para-lg bold">Please Enter the OTP to Verify your Account</h3>
        <!-- <p>An OTP (one time Password) has been sent to your email</p> -->
        {{ Form::open(array('url' => 'account/otp','method' => 'post','class' => 'enclude-form','id' => 'validate_otp','autocomplete' => 'off')) }}
            <div class="digit-group" data-group-name="digits" data-autosubmit="false">
                <input class="form_control bg-transparent" type="text" placeholder="X X X X X" id="otp" name="otp"/>
                @error('otp')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <ul class="btn_inline">
                <li><a href="{{ route('account.resend_otp') }}" class="resend-btn btn" id="ResendOtp">Resend OTP</a></li>
           <li> <button type="submit" class="btn-main btn-theme btn2" id="sbmtBtn">Validate OTP</button></li>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
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