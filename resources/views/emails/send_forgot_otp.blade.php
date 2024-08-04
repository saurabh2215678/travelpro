<?php
//prd(CustomHelper::getSettings('RECAPTCHA_SITE_KEY'));
  $websiteSettingsArr=CustomHelper::getSettings(['FRONTEND_LOGO']);
   $storage = Storage::disk('public');
   $path = "settings/";
   //prd($websiteSettingsArr);
   $logoSrc =asset(config('custom.assets').'/images/logo.png');

   if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
      $logo = $websiteSettingsArr['FRONTEND_LOGO'];
      if($storage->exists($path.$logo)){
         $logoSrc = asset('/storage/'.$path.$logo);
      }
   }
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Overland Escape</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="font-family: 'Roboto'">
    <table style="padding: 15px; text-align: center; margin:auto;">
        <tr>
            <td><a href="{{url('/')}}" style="margin-bottom: 17px; display: block;"><img src="{{ $logoSrc }}" alt=""></a></td>
        </tr>
        <tr>
            <td>
                <div style="border:1px solid #b9b9b9b5; border-radius: 8px; overflow: hidden; box-shadow: 0px 3px 5px 0px #00000036; margin: auto; background-color: #fff; border-collapse: collapse; display:inline-block;">
                <table style="text-align: center;">
                    <tr><td style="padding: 0;text-align: center;"><img src="{{config('custom.assets').'/images/mailer-img.png')}}" alt="" style="width: 100%;"></td></tr>
                    <tr><td><h3 style="margin-bottom: 0; text-align: center;">Hi {{$name}}</h3></td></tr>
                    <tr><td><p style="text-align: center;">Welcome to <a href="{{url('/')}}">{{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}</a></p></td></tr>
                    <tr><td><h4 style="font-size: 16px; padding: 0 25px ; margin-bottom: 0; text-align: center;">Your otp to verify you email for change a password is :</h4></td></tr>
                    <tr><td><h2 style="color: #ed6b69; letter-spacing: 10px; text-align: center;">{{$otp}}</h2></td></tr>
                </table>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>