@component(config('custom.theme').'.layouts.main')
@slot('title')
    @if($is_agent == 1) Agent Signup @else Signup @endif - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot
<?php
$country_code = old('country_code');
$country_code = isset($country_code) && !empty($country_code) ? $country_code : 91; 
if(!empty($is_agent) && $is_agent == 1){
    $form_action = 'account/agent-signup';
}else{
    $form_action = 'account/signup';
}
 
$social_signup = (session()->has('social_signup'))?session('social_signup'):0;

$name = (session()->has('social_name'))?session('social_name'):'';
$name = old('name',$name);
$email = (session()->has('social_email'))?session('social_email'):'';
$email = old('email',$email);

// $name = old('name');
?>

<section>
<div class="container">
    <div class="user_profile_login">
    <div class="text_center">
        <div class="theme-title">
            <div class="text-2xl">@if($is_agent == 1) Travel Agent Signup @else Signup @endif </div>
        </div>
        <div class="form-group google_with_login">
        <a href="{{route('social_login',['provider' => 'google','type'=>'customer', 'is_agent'=>$is_agent])}}" class="loginbtn"><img src="{{url('/images/google.png')}}" alt="Google Icon" class="glogo" /> Continue with Google</a>
          </div>
          <p class="border">Or continue with username / email</p>
    </div>
   @include('snippets.front.flash')
    {{ Form::open(array('url' => $form_action,'method' => 'post','class' => 'form','id' => 'signup_form','files'=>true,'autocomplete' => 'off','onSubmit'=>'return validateSignup()')) }}
    <div class="signup_form singup_form_wrap">
        <div class="singup_form_inner">
            <div class="form_group w-full" style="width:100%;">
            @if($is_agent == 1)
                <input type="hidden" name="is_agent" value="1">
            @endif
                <label for="name" class="control-label required text-sm font-normal">Name<em>*</em></label>
                <input type="text" name="name" id="name" value="{{$name}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'name'])
            </div>
            <div class="form_group">
                <label for="phone" class="control-label required text-sm font-normal">Phone<em>*</em></label>
                <select name="country_code" class="form-select country_code">
                  <?php /*{{$country->emoji}}*/ ?>
                  @if($countries)
                  @foreach($countries as $c)
                  <option value="{{$c->phonecode}}" {{($c->phonecode==$country_code)?'selected':''}} >+{{$c->phonecode}}</option>
                  @endforeach
                  @endif
              </select>
                <input type="text" name="phone" id="phone" value="{{old('phone')}}"  placeholder="" class="form_control bg-transparent" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12">
                @include('snippets.front.errors_first', ['param' => 'phone'])
            </div> 
            <div class="form_group">
                <label for="email" class="control-label required text-sm font-normal">Email<em>*</em></label>
                <?php if($social_signup){ ?>
                  {{$email}}
                <?php }else { ?>
                  <input type="text" name="email" id="email" value="{{$email}}" placeholder="" class="form_control bg-transparent" autocomplete="off">
                <?php } ?>
                @include('snippets.front.errors_first', ['param' => 'email'])
            </div>
           
            <div class="form_group">
                <label for="password" class="control-label required text-sm font-normal">Password <?php if($social_signup == 1){ echo '(optional)';}else{ echo '<em>*</em>'; } ?> </label>
                <input type="password" name="password" id="password" value="{{old('password')}}" autocomplete="new-password" placeholder="" class="form_control bg-transparent" autocomplete="off">
            @include('snippets.front.errors_first', ['param' => 'password'])
            </div>
            <div class="form_group">
                <label for="password" class="control-label required text-sm font-normal">Confirm Password <?php if($social_signup == 1){ echo '(optional)';}else{ echo '<em>*</em>'; } ?> </label>
                <input type="password" name="confirm_password" id="confirm_password" value="{{old('confirm_password')}}"  placeholder="" class="form_control bg-transparent" autocomplete="off">
            @include('snippets.front.errors_first', ['param' => 'confirm_password'])
            </div> 
        </div>
        <div class="create_account termsuse text-sm"><input type="checkbox" id="termsuse_input" name="termsuse" value="termsuse">
  <label for="vehicle1" class="font-semibold text-xs"> By signing up, you agree to the Terms of Service and <a href="/privacy-policy">Privacy Policy,</a> including Cookie Use.</label> 
    </div>
        <div class="form_group submit_btn">
          <input type="hidden" id="g-recaptcha-register" name="g-recaptcha-register">
            <button type="submit" class="btn_submit" id="sbmtBtn" name="submit" disabled>Submit</button>
        </div>

          <div class="create_account text-sm mb-2">Already have an account? <a href="{{route('account.login')}}">Login Now</a></div>
          
    {{ Form::close() }}
    </div>
</div>
</section>
@slot('bottomBlock')
<script src="https://www.google.com/recaptcha/api.js?render={{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script type="text/javascript">
  jQuery.support.cors = true;
  /*if ($("#signup_form").length > 0) {
    $("#signup_form").validate({
      submitHandler: function(form) {
        $("#sbmtBtn").html(
          'Please wait...'
          );
        $("#sbmtBtn"). attr("disabled", true);
        $("#signup_form").submit();
      }
    })
  }*/

  function validateSignup() {
    $("#sbmtBtn").html(
      'Please wait...'
      );
    $("#sbmtBtn"). attr("disabled", true);
    return true;
  }

  grecaptcha.ready(function() {
    grecaptcha.execute("{{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}", {
      action:'validate_captcha'}).then(function(token) {
      // add token value to form
      const element = document.getElementById('g-recaptcha-register');
      if(element) {
        document.getElementById('g-recaptcha-register').value = token;
      }
    });
  });

    $("#termsuse_input").change(function(){
        console.log($(this).is(":checked"))
        if($(this).is(":checked")){
            $('button#sbmtBtn').removeAttr("disabled")
        }else{
            $('button#sbmtBtn').prop("disabled", true)
        }
    });
    var maxBirthdayDate = new Date();
    maxBirthdayDate.setFullYear( maxBirthdayDate.getFullYear() - 18,11,31);

    $( function() {
        $( "#dob" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            maxDate: maxBirthdayDate,
            yearRange: '1900:'+maxBirthdayDate.getFullYear(),
        });
    } );

   
</script>
@endslot
@endcomponent


