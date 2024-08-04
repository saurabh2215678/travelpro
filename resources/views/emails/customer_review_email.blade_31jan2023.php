<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>{{ CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}</title>
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
													<a href="{{url('/')}}"><img src="{{config('custom.assets').'/images/logo.png')}}" alt="logo" style="height: 54px;" ></a>
												</div>
												<div style="font-size: 18px; text-align: right; float: right; margin-top: 5px; color: #952a25; font-weight: 500;">
													Customer Review Us <br>Date - <?php date_default_timezone_set("Asia/Thimphu"); 
													echo date('d M Y'); ?> at (<?php echo date('H:i A'); ?>) 
												</div>
										</td>	
											
										</tr>
										<tr>
											<td style="padding: 30px 0px 0 40px;" colspan="2">
												<span style="font-size: 24px; color: #3f4041; font-family: 'Roboto', sans-serif, Arial;">Hello there!</span>
												<p style="font-size: 16px; font-family: 'Roboto', sans-serif, Arial; color: #51535c; line-height: 32px; margin-bottom: 0; margin-top: 10px;">
													Your have an new customer review enquiry, details given below:
												</p>
											</td>
										</tr>
										
										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Trip Name & Duration : </strong> {{ $trip_name_duration }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Local Guide Name : </strong> {{ $local_guide_name }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Driver’s Name : </strong> {{ $driver_name }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong> Courteous : </strong> {{ $courteous.' /5 Rating' }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong> Address : </strong> {{ $address }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong> Helpful : </strong> {{ $helpful.' /5 Rating' }}</p>
											</td>
										</tr>


										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Informative : </strong> {{ $informative.' /5 Rating' }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Was Your Guide Comments : </strong> {{ $guide_comments }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Sightseeing Tour: </strong> {{ $sightseeing_tour.' /5 Rating' }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Sightseeing Comments: </strong>{{ $sight_tour_comments }}
											</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Driver: </strong> {{ $driver.' /5 Rating' }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Vehicle: </strong> {{ $vehicle.' /5 Rating' }}</p>
											</td>
										</tr>
										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Transportation Comments: </strong> {{ $transportation_comment }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Comfort: </strong> {{ $comfort.' /5 Rating' }}</p>
											</td>
										</tr>
										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Services: </strong> {{ $services.' /5 Rating' }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Food: </strong> {{ $food.' /5 Rating' }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Accommodation Comments: </strong> {{ $accommodation_comments }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Food: </strong> {{ $foods.' /5 Rating' }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Camp Staff: </strong> {{ $camp_staff.' /5 Rating' }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Pony/Yak: </strong> {{ $pony_yak.' /5 Rating' }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Trekking Comments: </strong> {{ $trekking_comments }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>On Tour: </strong> {{ $on_tour.' /5 Rating'}}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>On Trek: </strong> {{ $on_trek.' /5 Rating' }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Garbage Disposal Comments: </strong> {{ $garbage_disposal_coomments}}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Outstanding Performance By Any Staff On The Trip/Name: </strong> {{ $name }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>If So Why?: </strong> {{ $if_so_why}}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>What Was The Highlight Of Trip For You?: </strong> {{ $highlight_of_trip }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>What Was The Low Point?: </strong> {{ $low_point}}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Outstanding Performance By Any Staff On The Trip: </strong><?php echo ($staff_on_the_trip == 1) ? "Yes":"No";?></p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Did Your Trip Live Up To Your Expectations?: </strong><?php echo ($trip_expectations == 1) ? "Yes":"No";?></p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Overall Comments: </strong>{{ $overall_comments }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Name: </strong> {{ $your_name }}</p>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Email: </strong> {{ $email }}</p>
											</td>
											<td style="padding: 10px 0px 10px 40px;">					
												<p style="color: #3a3a3c; font-size: 17px; font-weight: 400; font-family: Roboto; margin: 4px 0px;"><strong>Address: </strong> {{ $address}}</p>
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