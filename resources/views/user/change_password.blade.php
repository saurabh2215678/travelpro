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
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">Change Password </h1>
                        </div>
                      
                    </div>
                    
                 
                </div>
                <div class="profile_info">
                   
                    @include('snippets.front.flash')
    {{ Form::open(array('route' => 'user.changePwd','method' => 'post','class' => 'form','id' => 'change_pass_form','autocomplete' => 'off')) }}
        <div class="login_form mt40">
            @if(!empty($user) && !empty($user->password))
            <div class="form_group mb16">
                <label class="common_label">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="form_control"  placeholder="Current Password" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'current_password'])
            </div>
            @endif
            <div class="form_group mb16">
                <label class="common_label">New Password</label>
                <input type="password" class="form_control" name="new_password" id="new_password"  placeholder="New Password" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'new_password'])
            </div>
            <div class="form_group mb16">
                <label class="common_label">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form_control"  placeholder="Confirm Password" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'confirm_password'])
            </div>
            <div class="form_group submit_btn">
            <button type="submit" class="btn" name="submit" id="sbmtBtn">Submit</button>
            </div>
        </div>
    {{ Form::close() }}

                </div>
            </div>               
        </div>
    </div>
</section>
<div class="edit_profile_field_wrap" style="display: none">
<div class="edit_profile_field" >
    <div class="edit_profile_inner_box">
    <div class="cross">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
    </div>

    <div class="edit_profile_inner">
      
        <div class="form_group">
        <label>Name</label>
        <input type="text" value="Himanshu Shekhar"  placeholder="" class="form_control">
        </div> 
        <div class="form_group">
        <label>Phone Number</label>
        <input type="text"  placeholder="" value="99394102589"  class="form_control">
        </div>
        <div class="form_group">
        <label>Email Id</label>
        <input type="text"  placeholder="" value="hello@gmail.com"  class="form_control">
        </div>
        <div class="form_group">
        <label>State</label>
        <input type="text"  placeholder="" value="Bihar"  class="form_control">
        </div>

        <div class="form_group">
            <label>Name</label>
            <input type="text" value="Himanshu Shekhar"  placeholder="" class="form_control">
            </div> 
            <div class="form_group">
            <label>Phone Number</label>
            <input type="text"  placeholder="" value="99394102589"  class="form_control">
            </div>
            <div class="form_group">
            <label>Email Id</label>
            <input type="text"  placeholder="" value="hello@gmail.com"  class="form_control">
            </div>
            <div class="form_group">
            <label>State</label>
            <input type="text"  placeholder="" value="Bihar"  class="form_control">
            </div>
        <div class="form_group">
        <label>Name</label>
        <input type="text" value="Himanshu Shekhar"  placeholder="" class="form_control">
        </div> 
        <div class="form_group">
        <label>Phone Number</label>
        <input type="text"  placeholder="" value="99394102589"  class="form_control">
        </div>
        <div class="form_group">
        <label>Email Id</label>
        <input type="text"  placeholder="" value="hello@gmail.com"  class="form_control">
        </div>
        <div class="form_group">
        <label>State</label>
        <input type="text"  placeholder="" value="Bihar"  class="form_control">
        </div>
</div>
<button type="button" class="btn">Submit</button>
</div>

</div>

</div>
@slot('bottomBlock')
<script>
    $(document).ready(function(){
       $(".edit_pofile_btn").click(function(){
         $(".edit_profile_field_wrap").fadeIn();
      });
      $(".cross").click(function(){
        $(".edit_profile_field_wrap").fadeOut();
      })
    });

    jQuery.support.cors = true;
   if ($("#change_pass_form").length > 0) {
         $("#change_pass_form").validate({
            submitHandler: function(form) {
               $("#sbmtBtn").html(
                     'Please wait...'
                     );
               $("#sbmtBtn"). attr("disabled", true);
               $("#change_pass_form").submit();
            }
         })
   }

    </script>

@endslot

@endcomponent


