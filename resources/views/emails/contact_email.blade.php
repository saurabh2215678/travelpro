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
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td>
					<table width="800" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff" style="border: 1px solid #ddd;">
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tbody>
										<tr style="background-color: #fff6ec;">
											<td align="left" style="padding: 20px 40px; border-bottom: 1px solid #00000017;" colspan="2">
												<div style="float: left;">
													<a href="{{url('/')}}"><img src="{{ $logoSrc }}" alt="logo" style="height: 54px;" ></a>
												</div>
												<div style="font-size: 18px; text-align: right; float: right; margin-top: 5px; color: #952a25; font-weight: 500;">
													Contact Us<br>Date - <?php date_default_timezone_set("Asia/Thimphu"); 
													echo date('d M Y'); ?> at (<?php echo date('H:i A'); ?>) 
												</div>
										</td>	
											
										</tr>
										<tr>
											<td style="padding: 30px 0px 0 40px;" colspan="2">
												<span style="font-size: 24px; color: #3f4041; font-family: 'Roboto', sans-serif, Arial;">Hello there!</span>
												<p style="font-size: 16px; font-family: 'Roboto', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;">
													Your have an new enquiry, details given below:
												</p>
											</td>
										</tr>
										
										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Name : </strong> {{ $name }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Email : </strong> {{ $email }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Phone : </strong> {{ $phone }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong> Country : </strong> {{ $country }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong> Month Of Travel : </strong> {{ $month_of_travel }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong> Duration of Stay  : </strong> {{ $duration }}</p>
											</td>
										</tr>


										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>No of Person : </strong> {{ $person_count }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Comment : </strong> {{ $comment }}</p>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>