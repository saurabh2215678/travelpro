@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot
<?php $online_booking = CustomHelper::isOnlineBooking(); ?>
<section>
<div class="container">
    <div class="user_profile_login login_sec">
    <div class="text_center">
        <?php
          if(session()->has('err_msg')) {
            echo session('err_msg');
          }
          ?>

        <div class="theme-title">
            <div class="text-2xl">Vendor Login</div>
        </div>
        <div class="form-group google_with_login">
          <a href="{{route('social_login',['provider' => 'google','is_vendor'=>1])}}" class="loginbtn"><img src="{{url('/images/google.png')}}" alt="Google Icon" class="glogo" /> Continue with Google</a>
          </div>
          <p class="border">Or continue with username / email</p>
    </div>

            
      <form class="" role="form" method="POST" action="{{route('adminLogin')}}" id="loginForm" autocomplete="off" onSubmit="return loginForm()" >
                {!! csrf_field() !!}
                <div class="username form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                  <label>Username or email address</label>
                  <input type="email" class="form-control" placeholder="Username" name="username" value="{{ old('username') }}" autocomplete="off">
                  @if ($errors->has('username'))
                  <span class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                  </span>
                  @endif
                </div>
                <div class="password_field form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                  <label>Password</label>
                  <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="new-password">
                  @if ($errors->has('password'))
                  <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
                </div>
                  <div class="col-password">
              <p class="forgot text-xs pt-2">Forgot your password? <a href="{{route('adminResetPassword')}}">Reset Password</a></p>
            </div>
                  <div class="form_group submit_btn">
                    <input type="hidden" id="g-recaptcha-login" name="g-recaptcha-login">     
                    <button type="submit" class="btn" id="login_continue">Login</button>
                  </div>
                  <?php if($online_booking){ ?>
                  <div class="create_account text-sm"> 
                    You have not an account <a href="{{route('account.signup')}}">Signup Now</a>
                  </div>
                <?php } ?>
                  @if(CustomHelper::isAllowedModule('agentuser'))
                  <div class="create_account text-sm">
                    Signup as Agent <a href="{{route('account.agent_signup')}}">Signup Now</a>
                  </div>
                  @endif
                      <div class="create_account text-sm"> 
            You have an account <a href="{{route('account.signup')}}">Signin</a>
          </div>
      </form>
          <div class="col-md">
            <div id="alertmsg" class="text-xs text-orange-400"> </div>
            @include('snippets.errors')
            @include('snippets.flash')
          </div>
          <form class="" role="form" method="POST" action="" id="otpForm" autocomplete="off" style="display: none;" onSubmit="return otpForm()" >
            {!! csrf_field() !!}

            <div class="form-group">
              <label class="col-md-12 control-label" style="padding-right: 0.3rem;">Enter the OTP sent to </label>
              <label style="padding: 0; color: #01b3a7;" class="col-md-12 control-label adminEmail"></label>
              <div class="w-full flex justify-between flex-row py-2">
              <div class="w-4/6" style="padding: 0;">
                <input type="text" class="form-control" name="otp" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  maxlength="5" autocomplete="off">
                @if ($errors->has('otp'))
                <span class="help-block">
                  <strong>{{ $errors->first('otp') }}</strong>
                </span>
                @endif
              </div>
              <div class="w-2/6" style="padding-left: 0.5rem;">
              <button type="submit" class="loginbtn text-sm" id="otp_continue">Validate OTP</button>
            </div>

              </div>
              
               <div class="w-full flex justify-between flex-row">
                <p class="forgot text-xs pt-2" style="margin-top:0px"><a href="javascript:void(0);" id="btnResend" onClick="loginForm('resendOtp');">Resend OTP</a></p>
                <p class="forgot text-xs pt-2">Click here to <a href="javascript:void(0);" onClick="showForm('loginForm','otpForm');">Change User</a></p>
              </div>
            </div>
            </form>
    </div>
</div>
</section>

@slot('bottomBlock')
<script src="https://www.google.com/recaptcha/api.js?render={{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}"></script>
<script type="text/javascript">
  jQuery.support.cors = true;
  if ($("#login_form").length > 0) {
    $("#login_form").validate({
      submitHandler: function(form) {
        $("#sbmtBtn").html(
          'Please wait...'
          );
        $("#sbmtBtn"). attr("disabled", true);
        $("#login_form").submit();
      }
    })
  }

  grecaptcha.ready(function() {
    grecaptcha.execute("{{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}", {
      action:'validate_captcha'}).then(function(token) {
      // add token value to form
      const element = document.getElementById('g-recaptcha-login');
      if(element) {
        document.getElementById('g-recaptcha-login').value = token;
      }
    });
  });
</script>
<script type="text/javascript">
  var No_OTP = false;
  function loginForm(resendOtp) {
    if(No_OTP) {
      return true;
    } else {
          var username =   $('input[name=username]').val();
          var password =   $('input[name=password]').val();
          var is_vendor =   1;
          var _token = '{{ csrf_token() }}';

          $.ajax({
              url: "{{ route('adminAjaxLogin') }}",
              type: "POST",
              data: {username:username,password:password,is_vendor:is_vendor},
              dataType: "JSON",
              headers:{
                  'X-CSRF-TOKEN': _token
              },
              cache: false,
              beforeSend:function(){
                $('#btnResend').html('Processing...');
          $('#btnResend').attr('disabled',true);
                $('#login_continue').html('Processing...');
                $('#login_continue').attr('disabled',true);
                  $('#alertmsg').html('');
                  $('input[name=otp]').val('');
              },
              success:function(resp ) {
                if(resp.success){
                  if(resp.msg == 'No_OTP') {
                    No_OTP = true;
                    $('#loginForm').submit();
                  } else {
                    $('#btnResend').html('Resend OTP');
                    $('#btnResend').attr('disabled',false);
                    $('#login_continue').html('Login');
                    $('#login_continue').attr('disabled',false);
                    if(resendOtp == 'resendOtp') {
                      $('#alertmsg').html('<div class="alert alert-success">OTP has been sent successfully.</div>');
                    } else {
                      $('#btnResend').html('Resend OTP');
                      $('#loginForm').hide();
                      $('#otpForm').show();                        
                      $('#otpForm .adminEmail').html(username);                        
                    } 
                  }                     
                } else {
                  $('#btnResend').html('Resend OTP');
                  $('#btnResend').attr('disabled',false);
                  $('#login_continue').html('Login');
                  $('#login_continue').attr('disabled',false);
                  $('#alertmsg').html('<div class="alert alert-danger">'+resp.msg+'</div>');
                }
              }
          });
          return false;
      }
    }
 
    function otpForm() {
        var username =   $('input[name=username]').val();
        var otp =   $('input[name=otp]').val();
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ route('adminAjaxLoginOtpCheck') }}",
            type: "POST",
            data: {username:username,otp:otp},
            dataType: "JSON",
            headers:{
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend:function(){
              $('#otp_continue').html('Processing...');
              $('#otp_continue').attr('disabled',true);
                $('#alertmsg').html('');
                $('input[name=otp]').val('');
            },
            success:function(resp ) {             
                if(resp.success) {
                  No_OTP = true;
                  $('#loginForm').submit();
                    // $('#otpForm').hide();
                    // $('#resetForm').show();
                } else {
                  $('#otp_continue').html('Submit');
                $('#otp_continue').attr('disabled',false);
                    $('#alertmsg').html('<div class="alert alert-danger">'+resp.msg+'</div>');
                }
            }
        });
        return false;
    }

    function showForm(showFormId,hideFormId) {
      $('#alertmsg').html('');
      $('#'+hideFormId).hide();
      $('#'+showFormId).show();
    }

  grecaptcha.ready(function() {
    grecaptcha.execute("{{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}", {
      action:'validate_captcha'}).then(function(token) {
      // add token value to form
      const element = document.getElementById('g-recaptcha-login');
      if(element) {
        document.getElementById('g-recaptcha-login').value = token;
      }
    });
  });
</script>
@endslot

@endcomponent


