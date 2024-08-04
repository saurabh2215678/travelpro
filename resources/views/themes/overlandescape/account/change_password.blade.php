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
            <div class="text-2xl">Change Password</div>
        </div>
    </div>
   @include('snippets.front.flash')
    {{ Form::open(array('url' => 'account/change-password','method' => 'post','class' => 'form','id' => 'change_password_form','autocomplete' => 'off','onSubmit'=>'return validateChangePassword()')) }}
        <div class="login_form">
            <div class="form_group">
                <label class="text-sm">New Password</label>
                <input type="password" name="password" id="password" value="{{old('password')}}"  placeholder="" class="form_control bg-transparent" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'password'])
            </div>
            <div class="form_group">
                <label class="text-sm">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" value="{{old('confirm_password')}}"  placeholder="" class="form_control bg-transparent" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'confirm_password'])
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
    /*if ($("#change_password_form").length > 0) {
            $("#change_password_form").validate({
                submitHandler: function(form) {
                $("#sbmtBtn").html(
                        'Please wait...'
                        );
                $("#sbmtBtn"). attr("disabled", true);
                $("#change_password_form").submit();
                }
            })
    }*/
    function validateChangePassword() {
        $("#sbmtBtn").html(
            'Please wait...'
            );
        $("#sbmtBtn"). attr("disabled", true);
        return true;
    }
    </script>
@endslot
@endcomponent