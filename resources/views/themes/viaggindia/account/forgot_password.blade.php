@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
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
            <div class="text-2xl">Forgot Password</div>
        </div>
    </div>
   @include('snippets.front.flash')
    {{ Form::open(array('url' => 'account/forgot-password','method' => 'post','class' => 'form','id' => 'forgot_form','autocomplete' => 'off')) }}
        <div class="login_form">
            <div class="form_group">
                <label class="text-sm">Email</label>
                <input type="email" name="forgot_email" id="forgot_email" value="{{old('forgot_email')}}"  placeholder="" class="form_control bg-transparent" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'forgot_email'])
            </div>
            <div class="form_group submit_btn">
                <input type="hidden" id="g-recaptcha-forgot" name="g-recaptcha-forgot">
                @include('snippets.front.errors_first', ['param' => 'g-recaptcha-forgot'])
                <button type="submit" class="btn" id="sbmtBtn" name="submit">Submit</button>
            </div>
        </div>
    {{ Form::close() }}
    </div>
</div>
</section>

@slot('bottomBlock')
<script src="https://www.google.com/recaptcha/api.js?render={{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}"></script>
<script type="text/javascript">

jQuery.support.cors = true;
if ($("#forgot_form").length > 0) {
     $("#forgot_form").validate({
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
  grecaptcha.execute("{{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}", {action:'validate_captcha'}).then(function(token) {
      // add token value to form
      const element = document.getElementById('g-recaptcha-forgot');
      if(element) {
         document.getElementById('g-recaptcha-forgot').value = token;
     }
 });
});

</script>
@endslot
@endcomponent