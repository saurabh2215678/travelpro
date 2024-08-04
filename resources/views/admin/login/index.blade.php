<!DOCTYPE html>
<html>
<title>{{ $title ?? config('app.name') . ' - Admin Panel' }}</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
<link rel="icon" href="{{ asset('assets/img/fav-icon.png') }}" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css" />
<script type="text/javascript" src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<style>
body, html {  height: 100%;}
body { color:#232325; }
*{box-sizing: border-box; margin: 0; padding: 0;}
.bodybg {display:flex; align-items: center;justify-content: center;}
.bodybg{font-family: 'Open Sans', sans-serif;  background:#f1f1f1;}
.boxbg{background: #57575712;width: 100%; display:flex;/*background:#fff; */}
.loglogo{position: fixed; margin: 20px 35px; top: 0; left: 0; float: left;}
.loginmain{box-sizing: border-box;  width:850px;  font-size: 14px;}
.logbg{width:60%; padding: 15px 45px; float: left; /*background-image:url({{url('')}}/images/featured-bg.jpg)*/; border-radius: 3px;}
.logo_sec {margin-bottom:25px; text-align: center;/*background: #00968830;*/padding: 8px;border-radius: 3px;}
input::-webkit-input-placeholder { color: #28272c !important; font-weight:600;font-size:15px; }
input:-moz-placeholder {color: #28272c !important; font-weight:600;font-size:15px;}
input::-moz-placeholder {color: #28272c !important; font-weight:600;font-size:15px;}
input:-ms-input-placeholder {color: #28272c !important; font-weight:600;font-size:15px;}
.form-control { height:45px; }
.username, .password_field {position: relative; }

.username:after {width:16px; height:17px; content:''; position: absolute;top: 40px;right: 15px; background-image:url({{url('')}}/images/user.png); }
.password_field:after {width:14px; height:18px; content:'';position: absolute;top: 40px;right: 15px; background-image:url({{url('')}}/images/password.png); }
.logtitle{ margin: 0; font-weight: 700; font-size: 20px; margin-bottom: 20px;}
.loginbtn { font-weight: 600; background-color: #18afa6; border-radius: 5rem; padding: 8px 30px; text-transform: uppercase; font-size: 14px; color: #fff; transition: all ease 0.5s; border: solid 2px var(--theme-color);}
.loginbtn:hover {background-color: #2c2d6c;}
.box2 {background-image:url({{url('')}}/images/login-img.jpg); width: 40%;background-size: cover; background-position: center center; }
img.glogo {width: 32px;}
.google_with_login a.loginbtn { background: #fff; color: #5ca1ff; text-transform: none; width: 100%; display: block; /*box-shadow: 0 2px 5px 1px rgba(64,60,67,.16);*/ padding: 10px; text-align: center; border-radius: 50px; }
.border {text-align: center;position: relative; padding-bottom: 5px;}
.border:before { content: ''; position: absolute; right: 0; background: #ddd; width: 65px; height: 2px; top: 10px; }
.border:after { content: ''; position: absolute; left: 0; background: #ddd; width: 65px; height: 2px; top: 10px; }
.form-group label{color: #6b6b6b;font-weight:600;}
.loginmain .alert.alert-danger {padding: 5px 10px;}
.loginmain #loginForm .form-control::placeholder{font-weight:400;}
.logo1{max-width: 250px;max-height: 100px;}
</style>
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
<body class="bodybg">
	<div class="loginmain">
		<div class="boxbg">
			<div class="logbg">
				
				<div class="fullwidth formbox"> 
					<?php
					if(session()->has('err_msg')) {
						echo session('err_msg');
					}
					?>
					<?php if($logoSrc){ ?>
						<div class="logo_sec">
							<a href="/"><img src="{{$logoSrc}}" alt="" class="logo1" /></a>
						</div>
					<?php } else { ?>
						<h4>Admin</h4> 
					<?php } ?>
					<?php if(CustomHelper::WebsiteSettings('GOOGLE_CLIENT_ID')){ ?>
						<div class="form-group google_with_login">
							<a href="{{route('social_login',['provider' => 'google','is_vendor'=>$is_vendor])}}" class="loginbtn"><img src="{{url('/images/google.png')}}" alt="" class="glogo" /> Continue with Google</a>
						</div>
						<p class="border">Or continue with username / email</p>
					<?php }else{ ?>
					<h4 class="logtitle">Admin Login</h4>
					<?php } ?>

					<form class="" role="form" method="POST" action="" id="loginForm" autocomplete="off" onSubmit="return loginForm()" >
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
							<p class="forgot">Forgot your password? <a href="{{route('adminResetPassword')}}">Reset Password</a></p>
						</div>
						<div class="form-group">
							<input type="hidden" id="g-recaptcha-login" name="g-recaptcha-login">
							<button type="submit" class="loginbtn" id="login_continue">Login</button>
						</div>
					</form>
					<div class="col-md">
						<div id="alertmsg"> </div>
						@include('snippets.errors')
						@include('snippets.flash')
					</div>
					<form class="" role="form" method="POST" action="" id="otpForm" autocomplete="off" style="display: none;" onSubmit="return otpForm()" >
						{!! csrf_field() !!}

						<div class="form-group">
							<label class="col-md-12 control-label" style="padding: 0;">Enter the OTP sent to </label>
							<label style="padding: 0;" class="col-md-12 control-label adminEmail"></label>
							<div class="col-md-12" style="padding: 0;">
								<input type="text" class="form-control" name="otp" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  maxlength="5" autocomplete="off">
								@if ($errors->has('otp'))
								<span class="help-block">
									<strong>{{ $errors->first('otp') }}</strong>
								</span>
								@endif
							</div>
						</div>

						<div class="col-sm-12" style="margin-top:18px; padding: 0;">
							<button type="submit" class="loginbtn" id="otp_continue">Validate OTP</button>
						</div>

						<div class="col-sm-12" style="margin-top:10px; padding: 0;">
							<p class="forgot" style="margin-top:0px"><a href="javascript:void(0);" id="btnResend" onClick="loginForm('resendOtp');">Resend OTP</a></p>
						</div>

						<div class="col-sm-12" style="padding: 0;">
							<p class="forgot">Click here to <a href="javascript:void(0);" onClick="showForm('loginForm','otpForm');">Change User</a></p>
						</div>
					</form>
				</div>
			</div>
			<div class="box2">
			</div>
		</div>
	</div>
<script src="https://www.google.com/recaptcha/api.js?render={{CustomHelper::WebsiteSettings('RECAPTCHA_SITE_KEY')}}"></script>
<script type="text/javascript">
	var No_OTP = false;
	function loginForm(resendOtp) {
		if(No_OTP) {
			return true;
		} else {
	        var username =   $('input[name=username]').val();
	        var password =   $('input[name=password]').val();
	        var is_vendor =   {{$is_vendor}};
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
</body>
</html>