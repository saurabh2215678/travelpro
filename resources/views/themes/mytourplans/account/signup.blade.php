@component(config('custom.theme').'.layouts.main')
@slot('title')
    Signup - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
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

?>

<section>
<div class="container">
    <div class="user_profile_login">
    <div class="text_center">
        <div class="theme-title">
            <div class="title text-3xl">Signup </div>
        </div>
        <!-- <div class="icon mt15"><img alt="" src="/assets/front/images/featured-icon.png" /></div> -->
    </div>
   @include('snippets.front.flash')
    {{ Form::open(array('url' => 'account/signup','method' => 'post','class' => 'form','id' => 'signup_form','files'=>true,'autocomplete' => 'off')) }}
    <div class="signup_form singup_form_wrap">
        <div class="singup_form_inner">
            <div class="form_group w-full" style="width:100%;">
                @if($is_agent == 1)
                <input type="hidden" name="is_agent" value="1">
                @endif
                <label for="name" class="control-label required">Name<em>*</em></label>
                <input type="text" name="name" id="name" value="{{old('name')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'name'])
            </div>
            <div class="form_group">
                <label for="phone" class="control-label required">Phone<em>*</em></label>
                 <select name="country_code" class="form-select country_code">
                  <?php /*{{$country->emoji}}*/ ?>
                  @if($countries)
                  @foreach($countries as $c)
                  <option value="{{$c->phonecode}}" {{($c->phonecode==$country_code)?'selected':''}} >+{{$c->phonecode}}</option>
                  @endforeach
                  @endif
              </select>
                <input type="text" name="phone" id="phone" value="{{old('phone')}}"  placeholder="" class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12">
                @include('snippets.front.errors_first', ['param' => 'phone'])
            </div> 
            <div class="form_group">
                <label for="email" class="control-label required">Email<em>*</em></label>
                <?php if($social_signup){ ?>
                  {{$email}}
                <?php }else { ?>
                <input type="text" name="email" id="email" value="{{old('email')}}"  placeholder="" class="form_control" autocomplete="off">
                 <?php } ?>
                @include('snippets.front.errors_first', ['param' => 'email'])
            </div>
            <!--<div class="form_group">
                <label for="dob" class="control-label required">Date Of Birth<em>*</em></label>
                <input type="text" name="dob" id="dob" value="{{old('dob')}}"  placeholder="" class="form_control" readonly>
                @include('snippets.front.errors_first', ['param' => 'dob'])
            </div>
            <div class="form_group">
                <label for="gender" class="control-label required">Gender<em>*</em></label>
                <input type="radio" name="gender" value="male" {{ old('gender') == "male" ? 'checked':'' }} class="form_control"> Male
                <input type="radio" name="gender" value="female" {{ old('gender') == "female" ? 'checked':'' }} class="form_control"> Female
                {{--<input type="radio" name="gender" value="other" {{ old('gender') == "other" ? 'checked':'' }} class="form_control"> Other --}}
                @include('snippets.front.errors_first', ['param' => 'gender'])
            </div>
            <div class="form_group">
                <label>Profile Photo</label>
                <input type="file" name="profile_image" id="profile_image" value="{{old('profile_image')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'profile_image'])
            </div>
             <div class="form_group">
                <label>Address</label>
                <input type="text" name="address" id="address" value="{{old('address')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'address'])
            </div> 
            <div class="form_group">
                <label>City</label>
                <input type="text" name="city" id="city" value="{{old('city')}}"  placeholder="" class="form_control">
            @include('snippets.front.errors_first', ['param' => 'city'])
            </div> 
            <div class="form_group">
                <label>State</label>
                <input type="text" name="state" id="state" value="{{old('state')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'state'])
            </div> 
            <div class="form_group">
                <label>Zip Code</label>
                <input type="text" name="zip_code" id="zip_code" value="{{old('zip_code')}}"  placeholder="" class="form_control">
            @include('snippets.front.errors_first', ['param' => 'zip_code'])
            </div> 
            <div class="form_group">
                <label>Country</label>
                <input type="text" name="country" id="country" value="{{old('country')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'country'])
            </div> -->
            <div class="form_group">
                <label for="password" class="control-label required">Password <?php if($social_signup == 1){ echo '(optional)';}else{ echo '<em>*</em>'; } ?> </label>
                <input type="password" name="password" id="password" value="{{old('password')}}"  placeholder="" class="form_control" autocomplete="off">
            @include('snippets.front.errors_first', ['param' => 'password'])
            </div>
            <div class="form_group">
                <label for="password" class="control-label required">Confirm Password <?php if($social_signup == 1){ echo '(optional)';}else{ echo '<em>*</em>'; } ?> </label>
                <input type="password" name="confirm_password" id="confirm_password" value="{{old('confirm_password')}}"  placeholder="" class="form_control" autocomplete="off">
            @include('snippets.front.errors_first', ['param' => 'confirm_password'])
            </div> 
        </div>
        <div class="form_group submit_btn">
            <input type="hidden" id="g-recaptcha-register" name="g-recaptcha-register">
            <button type="submit" class="btn_submit" id="sbmtBtn" name="submit" disabled>Submit</button>
        </div>

          <div class="create_account text-sm">Already have an account? <a href="{{route('account.login')}}">Login Now</a></div>
          <div class="create_account termsuse text-sm"><input type="checkbox" id="termsuse_input" name="termsuse" value="termsuse">
  <label for="vehicle1"> By signing up, you agree to the Terms of Service and <a href="/privacy-policy">Privacy Policy,</a> including Cookie Use.</label> 
    </div>
    {{ Form::close() }}
    </div>
</div>
</section>
@slot('bottomBlock')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render={{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}"></script>


<script>
    jQuery.support.cors = true;
  if ($("#signup_form").length > 0) {
    $("#signup_form").validate({
      submitHandler: function(form) {
        $("#sbmtBtn").html(
          'Please wait...'
          );
        $("#sbmtBtn"). attr("disabled", true);
        $("#signup_form").submit();
      }
    })
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
</script>
<script>
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

   jQuery.support.cors = true;
   if ($("#signup_form").length > 0) {
         $("#signup_form").validate({
            submitHandler: function(form) {
               $("#sbmtBtn").html(
                     'Please wait...'
                     );
               $("#sbmtBtn"). attr("disabled", true);
               $("#signup_form").submit();
            }
         })
   }

</script>
@endslot

@endcomponent


