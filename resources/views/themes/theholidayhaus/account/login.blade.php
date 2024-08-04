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
        <div class="theme-title">
            <div class="text-2xl">Login</div>
        </div>
        <div class="form-group google_with_login">
          <a href="{{route('social_login',['provider' => 'google','type'=>'customer'])}}" class="loginbtn"><img src="{{url('/images/google.png')}}" alt="Google Icon" class="glogo" /> Continue with Google</a>
          </div>
          <p class="border">Or continue with username / email</p>
    </div>
   
    {{ Form::open(array('url' => 'account/login','method' => 'post','class' => 'form','id' => 'login_form','autocomplete' => 'off')) }}
    <div class="login_form">
      @include('snippets.front.flash')
        <div class="form_group">
            <label class="text-sm">Email<em>*</em></label>
            <input type="text" name="user_email" id="email" value="{{old('user_email')}}"  placeholder="" class="form_control bg-transparent" autocomplete="off">
            @include('snippets.front.errors_first', ['param' => 'user_email'])
            
        </div> 
          <div class="form_group">
            <label class="text-sm">Password<em>*</em></label>
            <input type="password" name="password" id="password" value="{{old('password')}}" autocomplete="new-password"  placeholder="" class="form_control bg-transparent">
            @include('snippets.front.errors_first', ['param' => 'password'])
            <a class="forgot_pass text-xs pt-2" href="{{route('account.forgot-password')}}">Forgot Password?</a>
          </div>  
          <div class="form_group submit_btn">
            <input type="hidden" id="g-recaptcha-login" name="g-recaptcha-login">
            <button type="submit" class="btn" id="sbmtBtn" name="submit">Submit</button>
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

        @if(CustomHelper::isAllowedModule('vendor'))
          <div class="create_account text-sm"> 
            Signin as Vendor <a href="{{route('vendorlogin')}}">Signin </a>
          </div>
        @endif
    </div>
     {{ Form::close() }}
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
        form.submit();
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
@endslot

@endcomponent


