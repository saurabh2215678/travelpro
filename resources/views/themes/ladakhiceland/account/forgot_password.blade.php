@component(config('custom.theme').'.layouts.main')
@slot('title')
    Login - {{ config('app.name') }}
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
            <div class="title text-3xl">Forgot Password</div>
        </div>
        <!-- <div class="icon mt15"><img alt="" src="/assets/front/images/featured-icon.png" /></div> -->
    </div>
   @include('snippets.front.flash')
    {{ Form::open(array('url' => 'account/forgot-password','method' => 'post','class' => 'form','id' => 'forgot_form','autocomplete' => 'off')) }}
        <div class="login_form">
            <div class="form_group">
                <label>Email</label>
                <input type="email" name="forgot_email" id="forgot_email" value="{{old('forgot_email')}}"  placeholder="" class="form_control" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'forgot_email'])
            </div>
            <div class="form_group submit_btn">
            <button type="submit" class="btn" id="sbmtBtn" name="submit">Submit</button>
            </div>
        </div>
    {{ Form::close() }}
    </div>
</div>
</section>

@slot('bottomBlock')
<script>

jQuery.support.cors = true;
   if ($("#forgot_form").length > 0) {
         $("#forgot_form").validate({
            submitHandler: function(form) {
               $("#sbmtBtn").html(
                     'Please wait...'
                     );
               $("#sbmtBtn"). attr("disabled", true);
               $("#forgot_form").submit();
            }
         })
   }

</script>
@endslot
@endcomponent