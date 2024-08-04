@component(config('custom.theme').'.layouts.main')
@slot('title')
    Login - {{ CustomHelper::WebsiteSettings('SYSTEM_TITLE') }}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot

<section>
<div class="container">
    <div class="user_profile_login login_sec">
    <div class="text_center">
        <div class="theme-title">
            <div class="title text-3xl">Login </div>
        </div>
        <!-- <div class="icon mt15"><img alt="" src="/assets/front/images/featured-icon.png" /></div> -->
    </div>
   
    {{ Form::open(array('url' => 'account/login','method' => 'post','class' => 'form','id' => 'login_form','autocomplete' => 'off')) }}
    <div class="login_form">
        <div class="form_group">
            <label>Email<em>*</em></label>
            <input type="email" name="user_email" id="email" value="{{old('email')}}"  placeholder="" class="form_control" autocomplete="off">
            @include('snippets.front.flash')
            @include('snippets.front.errors_first', ['param' => 'user_email'])
            
        </div> 
          <div class="form_group">
            <label>Password<em>*</em></label>
            <input type="password" name="password" id="password" value="{{old('password')}}" autocomplete="new-password"  placeholder="" class="form_control">
            @include('snippets.front.errors_first', ['param' => 'password'])
            <a class="forgot_pass" href="{{route('account.forgot-password')}}">Forgot Password?</a>
          </div>  
          <div class="form_group submit_btn">
            <input type="hidden" id="g-recaptcha-login" name="g-recaptcha-login">
            <button type="submit" class="btn" id="sbmtBtn" name="submit">Submit</button>
          </div>

          <div class="create_account">
            You have not an account <a href="{{route('account.signup')}}">Signup Now</a>
          </div>
          @if(CustomHelper::isAllowedModule('agentuser'))
          <div class="create_account">
            Signup as Agent <a href="{{route('account.agent_signup')}}">Signup Now</a>
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

<script>

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

</script>

@endslot

@endcomponent


