<?php
use \Milon\Barcode\DNS2D;
$d = new DNS2D();
if(isset($booking_details) && !empty($booking_details)){ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<title>Booking</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	
	<style>
		*{font-family: 'Barlow', sans-serif;}
		.fancybox-content {
		padding: 20px !important;
		max-width: 800px !important;
		height: 300px !important;
		width: 100%;
		font-family: 'Barlow', sans-serif;
}
	</style>
	
</head>
<body>
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$websiteSettingsArr = CustomHelper::getSettings(['SYSTEM_TITLE', 'EMAIL_LOGO','SALES_PHONE','SITE_ADDRESS']);
$cancel_flight = $cancel_flight??false;
$print_flight = $print_flight??false;
$edit_passengers = $edit_passengers??false;
$print_route = $print_route??'user.flight_voucher_pdf';

$pendingOrderAmendments = $pendingOrderAmendments??0;
$company_name = $websiteSettingsArr['SYSTEM_TITLE']??'';
$company_phone = $websiteSettingsArr['SALES_PHONE']??'';
$company_address = strip_tags($websiteSettingsArr['SITE_ADDRESS'])??'';
$inventory_id = $order->inventory_id??0;
if($inventory_id) {
	$admin_markup = 0;
} else {
	$admin_markup = $order->admin_markup??0;
}
$markup = $order->markup??0;
$discount = $order->discount??0;

$total_markup = $markup - $discount;
if($admin_markup > 0){
	$total_markup = $total_markup+$admin_markup;
}

$storage = Storage::disk('public');
$path = "settings/";
$logoSrc = asset(config('custom.assets').'/images/logo1.png');
if(!empty($websiteSettingsArr['EMAIL_LOGO'])){
    $logo = $websiteSettingsArr['EMAIL_LOGO'];
    if($storage->exists($path.$logo)){
        $logoSrc = asset('/storage/'.$path.$logo);
    }
}

// AGENT LOGO
$userAgentInfo = ''; $agentLogo = '';
$agent_id = isset($order->agent_id) ? $order->agent_id : 0;
if($agent_id && $agent_id > 0){
	$userAgentInfo = $order->agentInfo ?? '';
	if($userAgentInfo && $userAgentInfo->count() > 0){
		$logoSrc = '';
		$path = 'agent_logo/';
		$agentLogo = $order->agentInfo->agent_logo??'';
		$company_name = $order->agentInfo->company_name??'';
		$phone = $order->agentInfo->User->phone??'';
		$country_code = $order->agentInfo->User->country_code??'91';
		if($phone) {
			$company_phone = '+'.$country_code.'-'.$phone;
		}
		$address1 = $order->agentInfo->User->address1??'';
		$address2 = $order->agentInfo->User->address2??'';
		$city = $order->agentInfo->User->city??'';
		$state = $order->agentInfo->User->state??'';
		$country = $order->agentInfo->User->country??'';
		$zipcode = $order->agentInfo->User->zipcode??'';
		$company_address = $address1;
		if($address2) {
			$company_address .= ', '.$address2;
		}
		if($city) {
			$company_address .= ', '.$city;
		}
		if($state) {
			$company_address .= ', '.$state;
		}
		if($country) {
			$company_address .= ', '.CustomHelper::GetCountry($country,'name');
		}
		if($zipcode) {
			$company_address .= ', '.$zipcode;
		}
		$hide_markup = $order->hide_markup??0;
		if($hide_markup == 1) {
			$markup = 0;
		}
	}
	if(!empty($agentLogo)){
		if($storage->exists($path.$agentLogo)){
			$logoSrc = asset('/storage/'.$path.$agentLogo);
		}
	}
}

$order_id = $order->id??0;
$hide_agent_details = $order->hide_agent_details??0;
$hide_price_details = $order->hide_price_details??0;

if(isset($booking_details)){
	$width_percent = (isset($width_percent) && !empty($width_percent))?$width_percent:'1100px';
	$width_px = (isset($width_px) && !empty($width_px))?$width_px:1100;
?>

	<div class="w-medium-model" style="width: <?php echo $width_percent; ?>; border: 1px solid #b1b1b1; margin: 12px auto;">
		<table width="<?php echo $width_px; ?>" >
			<thead>
				<tr>
					<!-- <td style="padding: 3px 8px;">
						
					</td> -->
					<td colspan="3" style="padding: 3px 8px;">
					<div style="display: flex;align-items: center;">
						@if($logoSrc)
						<div style="font-weight: 600;margin-bottom: 0;margin-right: 1rem;">
							<?php if($hide_agent_details == 0){ ?>
								<img src="{{$logoSrc}}" alt="{{$company_name}}" style="max-width:10rem;max-height: 7rem;object-fit: contain;">
							<?php } ?>
						</div>
						@endif
						<div>
						<?php if($hide_agent_details == 0){ ?>
						<p style="margin: 0; line-height: 1.6;"><b style="font-weight: 600;">{{$company_name}}</b></p>
						<p style="margin: 0; line-height: 1.6;font-size: 12px;"><b style="font-weight: 600;">Contact</b> : {{$company_phone}}</p>
						@if(!empty($company_address))
						<p style="margin: 0; line-height: 1.6;font-size: 12px;"><b style="font-weight: 600;">Address</b> : {{$company_address}}</p>
						@endif
						<?php } ?>
						</div>
						</div>
					</td>
					<td colspan="2" style="padding: 3px 8px;">
						<p style="margin: 0;"><b style="font-weight: 600;font-size: 12px;">Booking ID: OV{{$order->order_no??''}}</b></p>
						<p style="margin: 0;"><b style="font-weight: 600;font-size: 12px;">Booking Time:</b> {{CustomHelper::DateFormat($booking_details['order']['createdOn'],'d/m/Y H:i:s')??''}}</p>
						<p style="margin: 0; font-size:15px;color:green;"><strong>Booking Status: {{$booking_details['order']['status']??''}}</strong></p>
					</td>
				</tr>
			</thead>
			<tbody>
				@foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo)
				<?php /*<tr>
					<td colspan="5" style="padding: 15px;">
						<span style="font-weight: 500; font-size: 17px;">
							{{$tripInfo['sI'][0]['da']['city']??''}} - 
							{{$tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['city']??''}}
						</span>&nbsp;
						<span style="color: #808080;">
							on 
						{{CustomHelper::DateFormat($tripInfo['sI'][0]['dt'],'d/m/Y')??''}}
						</span>
					</td>
				</tr>*/?>
				<?php if(isset($tripInfo['sI'][0]['isRs']) && $tripInfo['sI'][0]['isRs'] == false){ ?>
				<tr>
					<td colspan="5" style="font-size: 13px;">
						<table width="100%" style="width: 100%;background: #18afa6;color: #fff;font-size: 13px;">
							<tr>
								<td style="padding: 3px 8px;"><i class="fa fa-fighter-jet"></i> Onward Flight Details</td>
								<td style="padding: 3px 8px;" align="right" style="font-size: 11px;">*Please verify flight times with the airlines prior to departure</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php } else { ?>
				<tr>
					<td colspan="5" style="font-size: 13px;">
						<table width="100%" style="width: 100%;background: #18afa6;color: #fff;font-size: 13px;">
							<tr>
								<td style="padding: 3px 8px;"><i class="fa fa-fighter-jet" style=" transform: rotate(180deg); "></i> Return Flight Details</td>
								<td style="padding: 3px 8px;" align="right" style="font-size: 11px;">*Please verify flight times with the airlines prior to departure</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php } ?>
				@foreach($tripInfo['sI'] as $counter => $flight)
				<tr>
					<td align="" style="padding: 5px 15px;background-color: #e6e6e6;font-weight: 500;font-size: 12px;">Flight {{$counter+1}}</td>
					<td align="" style="padding: 5px 15px;background-color: #e6e6e6;font-weight: 500;font-size: 12px;">Departing</td>
					<td align="center" style="padding: 5px 15px;background-color: #e6e6e6;font-weight: 500;font-size: 12px;">Stop</td>
					<td align="" style="padding: 5px 15px;background-color: #e6e6e6;font-weight: 500;font-size: 12px;">Arriving</td>
					<td align="center" style="padding: 5px 15px;background-color: #e6e6e6;font-weight: 500;font-size: 12px;">Duration / Class</td>
				</tr>
				<tr>
					<td style="padding: 5px 8px;/* display: flex; */flex-direction: row;align-content: center;height: 100%;">
						<img src="{{CustomHelper::showAirlineLogo($flight['fD']['aI']['code']??'')}}" style="width: 45px; height: 45px; background-color: #0000; float: left; margin-right: 15px;margin-top: 5px;">
						<div style="float: left; margin-top: 9px; font-size: 15px; font-weight: 500;">
							<p style="margin: 0;font-size: 13px;">{{$flight['fD']['aI']['name']??''}}</p>
							<p style="margin: 0; color: #8a8a8a;font-size: 12px;">{{$flight['fD']['aI']['code']??''}}-{{$flight['fD']['fN']??''}}</p>
						</div>
					</td>
					<td style="padding: 5px 8px; font-size: 15px; font-weight: 500; width: 15%;">
						<p style="font-size: 18px;margin: 0;">{{$flight['da']['code']??''}}</p>
						<p style="margin: 0;font-size: 12px;">{{CustomHelper::DateFormat($flight['dt'],'D, d M \'y, H:i')??''}}</p>
						<p style="margin: 0;  color: #8a8a8a;font-size: 12px;">{{$flight['da']['city']??''}}, {{$flight['da']['country']??''}}</p>
						<p style="margin: 0;  color: #8a8a8a;font-size: 12px;">{{$flight['da']['terminal']??''}}</p>
					</td>
					<td style="text-align: center; padding: 5px 8px; font-size: 15px; font-weight: 500;">
						{{(isset($flight['stops']) && (int)$flight['stops']>0)?$flight['stops'].' (stops)':'Non Stop'}}
					</td>
					<td style="padding: 5px 8px; font-size: 15px; font-weight: 500; width: 15%;">
						<p style="font-size: 18px;margin: 0;">{{$flight['aa']['code']??''}}</p>
						<p style="margin: 0;font-size: 12px;">{{CustomHelper::DateFormat($flight['at'],'D, d M \'y, H:i')??''}}</p>
						<p style="margin: 0;  color: #8a8a8a;font-size: 12px;">{{$flight['aa']['city']??''}}, {{$flight['aa']['country']??''}}</p>
						<p style="margin: 0;  color: #8a8a8a;font-size: 12px;">{{$flight['aa']['terminal']??''}}</p>
					</td>
					<td style="width: 25%; padding: 5px 8px; padding-left: 120px; font-size: 15px; font-weight: 500;">
						<p style="margin: 0;font-size: 12px;">{{CustomHelper::m2h($flight['duration'])}}</p>
						<p style="margin: 0; color: #8a8a8a; font-size: 12px;">{{CustomHelper::showCabinClass($booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['bI']['tI'][0]['fd']['cc']??'')}}</p>
						<?php
						$rT = $booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['bI']['tI'][0]['fd']['rT']??0;
						?>
						<p style="margin: 0; color: #8a8a8a; font-size: 12px; min-width: 90px;">
							@if($rT==0)
							<font style="color: red;">Non-Refundable</font>
							@elseif($rT==1)
							<font style="color: green;">Refundable</font>
							@elseif($rT==2)
							<font style="color: green;">Partial Refundable</font>
							@endif
						</p>
					</td>
				</tr>
				<?php if(isset($flight['cT']) && !empty($flight['cT']) && $flight['cT'] > 0){ ?>
				<tr>
					<td colspan="5" style="text-align: center;">
						<div class="change-plane" style="text-align: center;font-size: 10px;background: #ffffff4f;border-radius: 50px;display: inline-block;box-shadow: 0 2px 5px 1px rgb(64 60 67 / 37%);padding: 0.5rem 1rem;margin-bottom: 0.5rem;color: var(--theme-color);font-weight: 600;">
	                        <span class="">Require to change Plane</span> - 
	                        <span class="">Layover Time - {{CustomHelper::m2h($flight['cT']??0)}}</span>
	                    </div>
                    </td>
				</tr>
				<?php } ?>
				@endforeach
				@endforeach
		
				@if($booking_details['itemInfos']['AIR']['travellerInfos'])
				<tr>
					<td colspan="3" style="padding: 5px 15px; font-size: 18px; color: #18afa6; font-weight: 500;">Passenger Details ({{count($booking_details['itemInfos']['AIR']['travellerInfos'])}})</td>
					<td colspan="2" style="padding: 5px 15px; font-size: 18px; color: #18afa6; font-weight: 500;text-align:right;">
						<?php if($cancel_flight && empty($inventory_id)){ if(!empty($pendingOrderAmendments)){ ?>
		                  <a href="javascript:void(0);" title="Cancel Selected Passenger" class="btn btn-warning"  style="display: inline-block;">Your amendment in progress.</a>
		                <?php }else{ ?>
		                  <a href="javascript:void(0);" title="Cancel Selected Passenger" class="btn btn-success" id="cancel_btn" style="display: inline-block;">Cancel Selected Passenger</a>
		                <?php } } ?>
					</td>
				</tr>
				<tr>
					<td colspan="6" style="padding-bottom: 15px;padding-top: 0;">
						<table style="width: 100%; font-size: 12px; border-collapse: collapse;">
							<tr>
								<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;white-space: nowrap;" >S.N.</td>
								<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;white-space: nowrap;" >Name & FF</td>
								<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;white-space: nowrap;" >Sector</td>
								<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;white-space: nowrap;" >PNR & Ticket No.</td>
								<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;white-space: nowrap;" >Baggage | Check-in | Cabin<!-- <p style="margin: 0;">Baggage</p><p style="margin: 0;">Check-in | Cabin</p> --></td>
								<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;white-space: nowrap;" >Meal, Seat & Other Preference</td>
								<!-- <td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;white-space: nowrap;" >Document Id</td> -->
							</tr>
							@foreach($booking_details['itemInfos']['AIR']['travellerInfos'] as $index => $travellerInfo)
							<?php
							$name = $travellerInfo['ti'].' '.$travellerInfo['fN'].' '.$travellerInfo['lN'];

							$sector = [];
							$pnr_arr = [];
							foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo) {
								$src = $tripInfo['sI'][0]['da']['code']??'';
								$dest = $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'';

								$flight_code = $tripInfo['sI'][0]['fD']['aI']['code']??'';
								$flight_number = $tripInfo['sI'][0]['fD']['fN']??'';

								$skey = $src.$dest.$flight_code.' '.$flight_number;
								$sector[] = $skey;

								if(isset($travellerInfo['pnrDetails'])){
									foreach($travellerInfo['pnrDetails'] as $key => $val) {
										if(isset($travellerInfo['statusMap'])){
											foreach($travellerInfo['statusMap'] as $skey => $sval) {
												if($skey == $key) {
													$val = $sval;
												}
											}
										}
										$pnr_arr[] = $val;
									}
								}
							}

							$barcode = 'M1'.$travellerInfo['lN'].'/'.$travellerInfo['fN'].' '.implode('/', array_unique($pnr_arr)).' '.implode(' ', array_unique($sector));
							?>
							<tr>
								<td style="padding: 3px 8px; border: 1px solid #d8d8d8;">{{$index+1}}</td>
								<td style="padding: 3px 8px; border: 1px solid #d8d8d8; font-size:15px;">
									<strong>
										{{$name}} ({{substr($travellerInfo['pt'],0,1)}}) 
										@if($edit_passengers && $inventory_id)
										<a href="<?php echo route($ADMIN_ROUTE_NAME.'.orders.updateOfflineFlightPassenger',[$order_id,'name'=>$name]); ?>" class="edit_passenger_details" title="Edit Passenger" style="padding-left: 10px;color: #337ab7 !important;font-size: 13px;"><i class="fa fa-pencil"></i> Edit</a>
										@endif
									</strong>
									@if($print_flight)
									<a href="<?php echo route($print_route,[$order_id,'name'=>$name]); ?>" title="Download PDF" onClick="return confirm('Are you sure to continue?')" style="float: right;color: #337ab7 !important;font-size: 13px;"><i class="fa fa-print"></i> Print</a>
									@endif
									<br />
									<img src="data:image/png;base64,{{$d->getBarcodePNG($barcode, 'PDF417')}}" alt="{{$barcode}}" style="width: 170px;height: 50px;" />
								</td>
								<td style="padding: 3px 8px; border: 1px solid #d8d8d8; width: {{($cancel_flight)?110:80;}}px;">
									@foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo)
									<?php
									$src = $tripInfo['sI'][0]['da']['code']??'';
									$dest = $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'';
									$key = $src.'-'.$dest;

									$statusMap_arr = [];
									if(isset($travellerInfo['statusMap'])){
										foreach($travellerInfo['statusMap'] as $skey => $sval) {
											if(!in_array($sval, $statusMap_arr)) {
												$statusMap_arr[] = $sval;
											}
										}
									}
									?>
										<p style="margin: 0;font-size: 12px;">
										{{$key}}
										<?php if($cancel_flight && empty($inventory_id) && empty($pendingOrderAmendments) && empty($statusMap_arr)){ ?>
										&nbsp;&nbsp;<input type="checkbox" class="cancel_passengers" name="cancel_passengers[]" data-sector="{{$key}}" data-fN="{{$travellerInfo['fN']}}" data-lN="{{$travellerInfo['lN']}}" value="1" style="width: auto;">
										<?php } ?>
										</p>
									@endforeach
								</td>
								<td style="padding: 3px 8px; border: 1px solid #d8d8d8;">
									@foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo)
										<?php
										$src = $tripInfo['sI'][0]['da']['code']??'';
										$dest = $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'';
										$key = $src.'-'.$dest;
										$pnr_arr = [];
										if(isset($travellerInfo['pnrDetails'])){
											foreach($travellerInfo['pnrDetails'] as $key => $val) {
												if(isset($travellerInfo['statusMap'])){
													foreach($travellerInfo['statusMap'] as $skey => $sval) {
														if($skey == $key) {
															$val = $sval;
														}
													}
												}
												$airline_ticket_no = $travellerInfo['airline_ticket_no'][$key]??'';
												if($airline_ticket_no) {
													$val = $val.' / '.$airline_ticket_no;
												}
												$pnr_arr[] = $val;
											}
										}
										?>
										<p style="margin: 0;font-size: 15px;">
										<strong><?php echo implode(', ', array_unique($pnr_arr)); ?></strong>
										</p>
									@endforeach
								</td>
								<td style="padding: 3px 8px; border: 1px solid #d8d8d8;">
									@if(isset($booking_details['itemInfos']['AIR']['tripInfos']))
									@foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo)
									<?php /*@foreach($tripInfo['sI'] as $flight) $flight=$tripInfo['sI'][0]; */ ?>
									<p style="font-size: 12px;margin-bottom: 0;">
									{{$tripInfo['sI'][0]['bI']['tI'][$index]['fd']['bI']['iB']??''}}, &nbsp; | &nbsp;{{$tripInfo['sI'][0]['bI']['tI'][$index]['fd']['bI']['cB']??''}}
									</p>
									<?php /*@endforeach*/ ?>
									@endforeach
									@endif
								</td>
								<td style="padding: 3px 8px; border: 1px solid #d8d8d8;">
									@if(isset($travellerInfo['ssrBaggageInfos']))
										<p style="margin: 0;font-size: 12px;">Excess Baggage:</p>
										@foreach($travellerInfo['ssrBaggageInfos'] as $key => $val)
										<p style="margin: 0;font-size: 12px;">{{$key}}:{{$val['desc']??''}}</p>
										@endforeach
									@endif
									@if(isset($travellerInfo['ssrMealInfos']))
										<p style="margin: 0;font-size: 12px;">Meal:</p>
										@foreach($travellerInfo['ssrMealInfos'] as $key => $val)
										<p style="margin: 0;font-size: 12px;">{{$key}}:{{$val['desc']??''}}</p>
										@endforeach
									@endif
								</td>
								<!-- <td style="padding: 3px 8px; border: 1px solid #d8d8d8;">
									@if(isset($travellerInfo['pNum']))
									<p style="margin: 0;font-size: 12px;">{{$travellerInfo['pNum']??''}}</p>
									@endif
								</td> -->
							</tr>
							@endforeach
						</table>
					</td>
				</tr>
				
				@endif
				<tr>
					<td colspan="7" style="padding: 5px 8px;background-color: #0096882b;border-bottom: 1px solid #d4d4d4;font-size: 12px;"><strong>Customer Contact - </strong> <span style="padding: 0 8px;">E-mail: <a href="mailto:{{$order->email}}">{{$order->email}}</a></span> | <span style="padding: 0 8px;">Contact No: <a href="tel:{{$order->country_code.$order->phone}}">{{'+'.$order->country_code.'-'.$order->phone}}</a></span></td>
				</tr>
				
				<?php /*if($booking_details['gstInfo']){ ?>
				<tr>
					<td align="center" style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;">GST Name</td>
					<td align="center" style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;">GST No</td>
					<td align="center" style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;">GST Address</td>
					<td align="center" style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;">GST email</td>
					<td align="center" style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;">GST Phone</td>
				</tr>
				<tr>
					<td align="center" style="border: 1px solid #d8d8d8; color: #333; padding: 3px 8px;">{{$booking_details['gstInfo']['registeredName']??''}}</td>
					<td align="center" style="border: 1px solid #d8d8d8; color: #333; padding: 3px 8px;">{{$booking_details['gstInfo']['gstNumber']??''}}</td>
					<td align="center" style="border: 1px solid #d8d8d8; color: #333; padding: 3px 8px;">{{$booking_details['gstInfo']['address']??''}}</td>
					<td align="center" style="border: 1px solid #d8d8d8; color: #333; padding: 3px 8px;">{{$booking_details['gstInfo']['email']??''}}</td>
					<td align="center" style="border: 1px solid #d8d8d8; color: #333; padding: 3px 8px;">{{$booking_details['gstInfo']['mobile']??''}}</td>
				</tr>
				<?php }*/ ?>

				@if($hide_price_details == 0 && isset($booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']))
				<tr>
					<td  colspan="5" style="padding: 5px 15px; font-size: 18px;color: #18afa6; font-weight: 500; border-bottom: 1px solid #d4d4d4;">Payment Details</td>
				</tr>
				<tr>
					<td colspan="3" style="padding: 6px 15px; padding-top: 15px;font-size: 12px;" >Base Fare</td>
					<td colspan="2" style="padding: 6px 15px; padding-top: 15px; text-align: right;font-size: 12px;" >{{CustomHelper::getPrice(($booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['BF']??0),'',true)}}</td>
				</tr>
				<tr>
					<td colspan="3" style="padding: 6px 15px;font-size: 12px;" >Taxes and Fees</td>
					<td colspan="2" style="padding: 6px 15px; text-align: right;font-size: 12px;" >{{CustomHelper::getPrice((($booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['TAF']??0)+$total_markup),'',true)}}</td>
				</tr>
				@if(empty($inventory_id))
				<tr>
					<td colspan="3" style="padding: 6px 15px;font-size: 12px;" >SSR (Baggage, Meal, Seat...) Fees</td>
					<td colspan="2" style="padding: 6px 15px; text-align: right;font-size: 12px;" >{{CustomHelper::getPrice(($booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['SSRP']??0),'',true)}}</td>
				</tr>
				@endif
				<tr>
					<td colspan="3" style="padding: 6px 15px; padding-bottom: 15px; border-bottom: 1px solid #d4d4d4;font-size: 12px;" >Total</td>
					<td colspan="2" style="padding: 6px 15px; text-align: right; padding-bottom: 15px; border-bottom: 1px solid #d4d4d4;font-size: 12px;" >{{CustomHelper::getPrice((($booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['TF']??0)+$total_markup),'',true)}}</td>
				</tr>
				<tr>
					<td colspan="3" style="padding: 5px 8px; font-weight: 600;font-size: 12px;" >Amount Paid</td>
					<td colspan="2" style="padding: 5px 8px; font-weight: 600; text-align: right;font-size: 12px;" >{{CustomHelper::getPrice(($order->total_amount+$markup),'',true)}}</td>
				</tr>
				@endif
		      
				
			</tbody>
		</table>
	</div>

	<div style="background: #18afa6;color: #fff;padding: 2px 8px 5px;line-height: normal;">Important Information</div>
	<div style="font-size: 12px;">
		1 - You must web check-in on the airline website and obtain a boarding pass.<br />
		2 - You must download & register on the Aarogya Setu App and carry a valid ID.<br />
		3 - It is mandatory to wear a mask and carry other protective gear.<br />
		4 - Reach the terminal at least 2 hours prior to the departure for domestic flight and 4 hours prior to the departure of international flight.<br />
		5 - For departure terminal please check with the airline first.<br />
		6 - Date & Time is calculated based on the local time of the city/destination.<br />
		7 - Use the Airline PNR for all Correspondence directly with the Airline<br />
		8 - For rescheduling/cancellation within 4 hours of the departure time contact the airline directly<br />
		9 - Your ability to travel is at the sole discretion of the airport authorities and we shall not be held responsible.<br />
	</div>
	<div>
		<img src="{{asset('images/inclusion-exclusion.jpg')}}" style="width: 100%; float: left;">
	</div>
	<?php } ?>

</body>
</html>
<?php } ?>