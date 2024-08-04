<!DOCTYPE html>
<html>
<title>{{ $title ?? config('app.name') . ' - Admin Panel' }}</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet"> 

<link rel="icon" href="{{ asset('assets/img/fav-icon.png') }}" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css" />
<script type="text/javascript" src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<?php
$websiteSettingsArr=CustomHelper::getSettings(['FRONTEND_LOGO']);
 $logoSrc = asset('assets/logo.png');
 $storage = Storage::disk('public');
 $path = "settings/";

   if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
      $logo = $websiteSettingsArr['FRONTEND_LOGO'];
      if($storage->exists($path.$logo)){
         $logoSrc = asset('/storage/'.$path.$logo);
      }
   }

?>

<style>
body, html {height: 100%;}
 body {color:#232325;}
*{box-sizing: border-box; margin: 0; padding: 0;}
.bodybg {display:flex;align-items: center;justify-content: center; }
.bodybg{font-family: 'Open Sans', sans-serif;  background:#f1f1f1;}
.boxbg{background: #57575712;width: 100%; display:flex;/*background:#fff; */}
.loglogo{position: fixed; margin: 20px 35px; top: 0; left: 0; float: left;}
.loginmain{box-sizing: border-box; width:850px; font-size: 14px;}
.logbg{width:60%; padding: 15px 45px; float: left;min-height: 475px; /*background-image:url({{url('')}}/images/featured-bg.jpg);*/ border-radius: 3px;}
.logo_sec {margin-bottom:25px; /*background: #00968830;*/padding: 8px;border-radius: 3px;text-align: center;}
input::-webkit-input-placeholder { color: #28272c !important; font-weight:600;font-size:15px; }
input:-moz-placeholder {color: #28272c !important; font-weight:600;font-size:15px;}
input::-moz-placeholder {color: #28272c !important; font-weight:600;font-size:15px;}
input:-ms-input-placeholder { color: #28272c !important; font-weight:600;font-size:15px;}
.form-control { height:45px;}
.username, .password_field {position: relative; }
.username:after {width:16px; height:17px; content:''; position: absolute;top: 18px;right: 15px; background-image:url({{url('')}}/images/user.png); }
.password_field:after {width:14px; height:18px; content:''; position: absolute;top: 18px;right: 15px; background-image:url({{url('')}}/images/password.png); }
.logtitle{margin: 0; font-size: 20px; margin-bottom: 20px; text-align: center;}
.loginbtn {font-weight: 600; background-color:#18afa6; border-radius: 5rem; padding: 8px 30px;text-transform: uppercase; font-size: 14px; color: #fff; transition: all ease 0.5s; border: solid 2px var(--theme-color);}
.box2 {background-image:url({{url('')}}/images/login-img.jpg); width: 40%;background-size: cover;background-position: center center; }
.loginbtn:hover{background-color:#2c2d6c;}
p.forgot {margin-top: 15px;}
.form-group label {color: #6b6b6b;font-weight: 600;}
img.glogo {width: 32px;}
.google_with_login a.loginbtn { background: #fff; color: #5ca1ff; text-transform: none; width: 100%; display: block; /*box-shadow: 0 2px 5px 1px rgba(64,60,67,.16);*/ padding: 10px; text-align: center; border-radius: 50px; }
.border {text-align: center;position: relative;padding-bottom: 5px;}
.border:before { content: ''; position: absolute; right: 0; background: #ddd; width: 65px; height: 2px; top: 10px; }
.border:after { content: ''; position: absolute; left: 0; background: #ddd; width: 65px; height: 2px; top: 10px; }
html{scroll-behavior: smooth;scroll-padding-top: 68px;}
.box2 img.glogo {width: 100%;height: 100%;}
.logo1{max-width: 250px;max-height: 100%;}
</style>

<body class="bodybg">
    <div class="loginmain">
        <div class="boxbg">
            <div class="logbg">
                <div class="fullwidth formbox"> 
                    <div class="logo_sec">
                        <a href="/"><img src="{{$logoSrc}}" alt="" class="logo1" /></a>
                    </div>
                    <h4 class="logtitle">Forgotten password?</h4>
                    
                    <?php if(CustomHelper::WebsiteSettings('GOOGLE_CLIENT_ID')){ ?>
						<div class="form-group google_with_login">
							<a href="{{route('social_login',['provider' => 'google'])}}" class="loginbtn"><img src="{{url('/images/google.png')}}" alt="" class="glogo" /> Continue with Google</a>
						</div>
						<p class="border">Or continue with username / email</p>
					<?php } ?>


                    <div class="col-md-12">
                        <div id="alertmsg" > </div>
                        @include('snippets.errors')
                        @include('snippets.flash')
                    </div>  

                    <form class="" role="form" method="POST" action="" id="forgottonForm" autocomplete="off" onSubmit="return forgottonForm()" >
                        {!! csrf_field() !!}
                        <div id="login_body">
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label class="col-1 control-label">Username</label>
                                <div class="col-2">
                                    <input type="email" class="form-control" name="username" value="{{ old('username') }}">
                                    @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="recaptcha_token_login" id="recaptcha_token_login">

                        <div class="col-3">
                            <button type="submit" class="loginbtn">Reset Password</button>
                        </div>
                        <div class="col-4">
                            <p class="forgot">Back to <a href="{{route('adminLogin')}}">Login</a></p>
                        </div>
                    </form>

                    <form class="" role="form" method="POST" action="" id="otpForm" autocomplete="off" style="display: none;" onSubmit="return otpForm()" >
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-12 control-label">OTP</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="otp" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  maxlength="5" autocomplete="off">
                                @if ($errors->has('otp'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('otp') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-12">
                                <p class="forgot" style="margin-top:0px"><a href="javascript:void(0);" onClick="forgottonForm('resendOtp');">Resend OTP</a></p>
                            </div>
                        </div>



                        <div class="col-sm-12">
                            <button type="submit" class="loginbtn">Submit</button>
                        </div>
                        <div class="col-sm-12">
                            <p class="forgot">Click here to <a href="javascript:void(0);" onClick="showForm('forgottonForm','otpForm');">back</a></p>
                        </div>

                    </form>

                    <form class="" role="form" method="POST" action="" id="resetForm" autocomplete="off" style="display: none;" onSubmit="return resetForm()">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-12 control-label">New Password</label>
                            <div class="col-md-12">
                                <input type="password" class="form-control" name="new_password" autocomplete="off">
                                @if ($errors->has('new_password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('new_password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 control-label">Confirm Password</label>
                            <div class="col-md-12">
                                <input type="password" class="form-control" name="confirm_password" autocomplete="off">
                                @if ($errors->has('confirm_password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('confirm_password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <button type="submit" class="loginbtn">Submit</button>
                        </div>
                        <div class="col-sm-12">
                            <p class="forgot">Click here to <a href="javascript:void(0);" onClick="showForm('otpForm','resetForm');">back</a></p>
                        </div>

                    </form>

                    <form class="" role="form" method="POST" action="" id="successForm" autocomplete="off" style="display: none;">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-12 control-label">Your password has been reset successfully. Please continue login.</label>
                        </div>

                        <div class="col-sm-12">
                            <a href="{{route('adminLogin')}}" class="loginbtn">Continue</a>
                        </div>

                    </form>

                </div>
            </div>
            <div class="box2">
            <!-- <img src="{{url('/images/forget-login.jpg')}}" alt="" class="glogo" /> -->
			</div>
        </div>
    </div>

<script type="text/javascript">

    function forgottonForm(resendOtp) {
        var username =   $('input[name=username]').val();
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ route('adminResetPasswordOtp') }}",
            type: "POST",
            data: {username:username},
            dataType: "JSON",
            headers:{
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend:function(){
                $('#alertmsg').html('');
                $('input[name=otp]').val('');
                $('input[name=new_password]').val('');
                $('input[name=confirm_password]').val('');
            },
            success:function(resp ){
                if(resp.success){
                    if(resendOtp == 'resendOtp') {
                        $('#alertmsg').html('<div class="alert alert-success">OTP has been sent successfully.</div>');
                    } else {
                        $('#forgottonForm').hide();
                        $('#otpForm').show();                        
                    }
                } else {
                    $('#alertmsg').html('<div class="alert alert-danger">'+resp.msg+'</div>');
                }
            }
        });
        return false;
    }

    function otpForm() {
        var username =   $('input[name=username]').val();
        var otp =   $('input[name=otp]').val();
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ route('adminResetPasswordOtpCheck') }}",
            type: "POST",
            data: {username:username,otp:otp},
            dataType: "JSON",
            headers:{
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend:function(){
                $('#alertmsg').html('');
                $('input[name=new_password]').val('');
                $('input[name=confirm_password]').val('');
            },
            success:function(resp ){
                if(resp.success) {
                    $('#otpForm').hide();
                    $('#resetForm').show();
                } else {
                    $('#alertmsg').html('<div class="alert alert-danger">'+resp.msg+'</div>');
                }
            }
        });
        return false;
    }
    
    function resetForm() {
        var username =   $('input[name=username]').val();
        var otp =   $('input[name=otp]').val();
        var new_password =   $('input[name=new_password]').val();
        var confirm_password =   $('input[name=confirm_password]').val();
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ route('adminForgotPasswordReset') }}",
            type: "POST",
            data: {username:username,otp:otp,new_password:new_password,confirm_password:confirm_password},
            dataType: "JSON",
            headers:{
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            beforeSend:function(){
                $('#alertmsg').html('');
            },
            success:function(resp ){
                if(resp.success) {
                    $('#resetForm').hide();
                    $('#successForm').show();
                } else {
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

</script>

</body>
</html>