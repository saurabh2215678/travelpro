<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Flight Booking</title>
	<style type="text/css">
	@page {
        margin: 15 !important;
    }
	</style>
</head>
<body>
<?php
use \Milon\Barcode\DNS2D;
$d = new DNS2D();
$websiteSettingsArr = CustomHelper::getSettings(['SYSTEM_TITLE', 'EMAIL_LOGO','SALES_PHONE','SITE_ADDRESS']);

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
$logoSrc = public_path().'/'.(config('custom.assets').'/images/logo1.png');
if(!empty($websiteSettingsArr['EMAIL_LOGO'])){
    $logo = $websiteSettingsArr['EMAIL_LOGO'];
    if($storage->exists($path.$logo)){
        $logoSrc = public_path().'/storage/'.$path.$logo;
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
		$agentLogo = $order->agentInfo->agent_logo ?? '';

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
			$logoSrc = public_path().'/storage/'.$path.$agentLogo;
		}
	}
}

$hide_agent_details = $order->hide_agent_details??0;
$hide_price_details = $order->hide_price_details??0;
$print_name = $print_name??'';
if(isset($booking_details)){
?>
	<div style="width: 100%;">
		<table width="100%" style="border-collapse: collapse; width:100%; border: 1px solid #b1b1b1; font-family: sans-serif;">
		<thead>
		<tr>
			@if($logoSrc)
			<td colspan="3" style="padding: 3px 8px;">
				<p style="padding-right:10px;margin: 0;">
					<?php if($hide_agent_details == 0){ ?>
						<img src="{{$logoSrc}}" alt="{{$websiteSettingsArr['SYSTEM_TITLE']??''}}" style="max-width:10rem;max-height: 7rem; object-fit: contain;">
					<?php } ?>
				</p>				
			</td>
			@endif
					<td colspan="{{($logoSrc)?'3':'6'}}" style="padding: 3px 8px;width:250px; padding-right:10px;color:#323035;">
						<div style="">
							<?php if($hide_agent_details == 0){ ?>
							<p style="margin: 0; line-height: 1.6;font-size:12px;"><b style="font-family: sans-serif;">{{$company_name}}</b></p>
							<p style="margin: 0; line-height: 1.6;font-size:12px;"><b style="font-family: sans-serif;">Contact</b> : {{$company_phone}}</p>
							@if(!empty($company_address))
							<p style="margin: 0;font-size:12px;"><b style="font-family: sans-serif;">Address</b> : {{$company_address}}</p>
							@endif
							<?php } ?>
						</div>
					</td>
					<td colspan="3" style="padding: 3px 8px;color:#323035;">
						<p style="margin: 0; font-size:12px;"><b>Booking ID: </b>OV{{$order->order_no??''}}</p>
						<p style="margin: 0; font-size:12px;"><b>Booking Time: </b>{{CustomHelper::DateFormat($booking_details['order']['createdOn'],'d/m/Y H:i:s')??''}}</p>
						<p style="margin: 0; font-size:15px;color:green;"><strong>Booking Status: {{$booking_details['order']['status']??''}}</strong></p>
					</td>
				</tr>
				</thead>
			</table>
			<table width="100%" style="border-collapse: collapse; width:100%; border: 1px solid #b1b1b1; font-family: sans-serif;">
				<?php foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo){ ?>
				<?php /*<tr>
					<td colspan="5" style="background-color: #f5f5f5; padding: 15px; border:1px solid #B1B1B1;">
						<span style=" font-size: 17px;">
							{{$tripInfo['sI'][0]['da']['city']??''}} - 
							{{$tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['city']??''}}
						</span>&nbsp;
						<span style="color: #808080;">
							on 
						{{CustomHelper::DateFormat($tripInfo['sI'][0]['dt'],'d/m/Y')??''}}
						</span>
					</td>
				</tr>*/ ?>
				<?php if(isset($tripInfo['sI'][0]['isRs']) && $tripInfo['sI'][0]['isRs'] == false){ ?>
                <tr>
					<td colspan="5" style="font-size: 12px;">
						<table width="100%" style="width: 100%;background: #18afa6;color: #fff;font-size: 12px;">
							<tr>
								<td style="padding: 3px 8px;"><img src="{{public_path().'/images/onward-flight-icon.png'}}" style="width: 8%; float: left;margin-right:3px 8px;"> Onward Flight Details</td>
								<td style="padding: 3px 8px;" align="right" style="font-size: 11px;">*Please verify flight times with the airlines prior to departure</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php } else { ?>
				<tr>
					<td colspan="5" style="font-size: 12px;">
						<table width="100%" style="width: 100%;background: #18afa6;color: #fff;font-size: 12px;">
							<tr>
								<td style="padding: 3px 8px;"><img src="{{public_path().'/images/return-flight-icon.png'}}" style="width: 8%; float: left;margin-right:3px 8px;"> Return Flight Details</td>
								<td style="padding: 3px 8px;" align="right" style="font-size: 11px;">*Please verify flight times with the airlines prior to departure</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php } ?>

				<?php foreach($tripInfo['sI'] as $counter => $flight){ ?>
				<tr>
					<td align="" style="padding: 5px 15px;background-color: #e6e6e6;font-size: 12px;font-family: sans-serif;">Flight {{$counter+1}}</td>
					<td align="" style="padding: 5px 15px;background-color: #e6e6e6;font-size: 12px;font-family: sans-serif;">Departing</td>
					<td align="center" style="padding: 5px 15px;background-color: #e6e6e6;font-size: 12px;font-family: sans-serif;">Stop</td>
					<td align="" style="padding: 5px 15px;background-color: #e6e6e6;font-size: 12px;font-family: sans-serif;">Arriving</td>
					<td align="center" style="padding: 5px 10px;background-color: #e6e6e6;font-size: 12px;font-family: sans-serif;"> Duration / Class</td>
				</tr>
				<tr>
					<td style="padding: 5px 8px;vertical-align: middle; padding-top: 10px">
						<img src="{{CustomHelper::showAirlineLogo(($flight['fD']['aI']['code']??''),true)}}" style="width: 45px; height: 45px; background-color: #0000; float: left; margin-right: 15px;margin-top: 5px;">
						<div style="float: left; margin-top: 9px; font-size: 15px; ">
							<p style="margin: 0;font-size: 13px;">{{$flight['fD']['aI']['name']??''}}</p>
							<p style="margin: 0; color: #8a8a8a;font-size: 12px;">{{$flight['fD']['aI']['code']??''}}-{{$flight['fD']['fN']??''}}</p>
						</div>
						<div style="clear:both;"></div>
					</td>
					<td style="padding: 5px 8px; font-size: 15px;  width: 24%; padding-left: 15px">
						<p style="font-size: 13px 8px;margin: 0;">{{$flight['da']['code']??''}}</p>
						<p style="margin: 0;font-size: 12px;">{{CustomHelper::DateFormat($flight['dt'],'D, d M \'y, H:i')??''}}</p>
						<p style="margin: 0;  color: #8a8a8a;font-size: 12px;">{{$flight['da']['city']??''}}, {{$flight['da']['country']??''}}</p>
						<p style="margin: 0;  color: #8a8a8a;font-size: 12px;">{{$flight['da']['terminal']??''}}</p>
					</td>
					<td style="text-align: center; padding: 15px; font-size: 12px; width:5%">
						{{(isset($flight['stops']) && (int)$flight['stops']>0)?$flight['stops'].' (stops)':'Non Stop'}}
					</td>
					<td style="padding: 15px; font-size: 15px;  width: 24%;">
						<p style="font-size: 13px 8px;margin: 0;">{{$flight['aa']['code']??''}}</p>
						<p style="margin: 0;font-size: 12px;">{{CustomHelper::DateFormat($flight['at'],'D, d M \'y, H:i')??''}}</p>
						<p style="margin: 0;  color: #8a8a8a;font-size: 12px;">{{$flight['aa']['city']??''}}, {{$flight['aa']['country']??''}}</p>
						<p style="margin: 0;  color: #8a8a8a;font-size: 12px;">{{$flight['aa']['terminal']??''}}</p>
					</td>
					<td style="width: 15%; padding: 15px; font-size: 15px; ">
						<p style="margin: 0;font-size: 12px;">{{CustomHelper::m2h($flight['duration'])}}</p>
						<p style="margin: 0; color: #8a8a8a;font-size: 12px;">{{CustomHelper::showCabinClass($booking_details['itemInfos']['AIR']['tripInfos'][0]['sI'][0]['bI']['tI'][0]['fd']['cc']??'')}}</p>
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
						<div class="" style="font-family: sans-serif; text-align: center;font-size: 0.8rem;background: #cdcdcd4f;border-radius: 50px;display: inline-block;box-shadow: 0 2px 5px 1px rgb(64 60 67 / 37%);padding: 0.5rem 1rem;margin-bottom: 0.5rem;color: #01b3a7;">
	                        <span class="" style="font-family: sans-serif;"><strong>Require to change Plane</strong></span> - 
	                        <span class="" style="font-family: sans-serif;"><strong>Layover Time - {{CustomHelper::m2h($flight['cT']??0)}}</strong></span>
	                    </div>
                    </td>
				</tr>
				<?php } ?>
				<?php } ?>
				<?php } ?>
			</table>

			<?php if($booking_details['itemInfos']['AIR']['travellerInfos']){ ?>
			<table width="100%" style="border-collapse: collapse; width:100%; border: 1px solid #b1b1b1; font-family: sans-serif;">
				<tr>
					<td colspan="6" style="padding: 3px 8px; font-size: 13px;color: #18afa6; ">
					Passenger Details 
					<?php if(empty($print_name)) { ?>
					({{count($booking_details['itemInfos']['AIR']['travellerInfos'])}})
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;font-size: 12px;"> S.N.</td>
					<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;font-size: 12px;"> Name & FF</td>
					<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;font-size: 12px;"> Sector</td>
					<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;font-size: 12px;"> PNR & Ticket No.</td>
					<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;font-size: 12px;text-align:center;"> Baggage Check-in | Cabin</td>
					<td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;font-size: 12px;">Meal, Seat & Other Preference</td>
					<!-- <td style="background-color: #18afa6; border: 1px solid #d8d8d8; color: #fff; padding: 3px 8px;font-size: 12px;">Document Id</td> -->
				</tr>
				<?php
				$total_pax = count($booking_details['itemInfos']['AIR']['travellerInfos']);
				$base_fare = $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['BF']??0;
				$taxes_and_fees = ($booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['TAF']??0)+$total_markup;
				$ssr_fees = $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['SSRP']??0;
				
				$total = ($booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']['fC']['TF']??0)+$total_markup;
				$amount_paid = $order->total_amount+$markup;

				foreach($booking_details['itemInfos']['AIR']['travellerInfos'] as $index => $travellerInfo) {
					$name = $travellerInfo['ti'].' '.$travellerInfo['fN'].' '.$travellerInfo['lN'];
					if(!empty($print_name)) {
						if(trim($print_name) != trim($name)) {
							continue;
						}
						$base_fare = 0;
						$taxes_and_fees = 0;
						$ssr_fees = 0;
						$total = 0;
						$amount_paid = 0;
					}

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
						// prd($tripInfo);
						if(!empty($print_name)) {
							if(trim($print_name) == trim($name)) {
								foreach($tripInfo['sI'] as $sI) {
									$tIs = $sI['bI']['tI']??[];
									if($tIs) {
										foreach($tIs as $tI) {
											if(isset($tI['ti'])) {
												$tIname = $tI['ti'].' '.$tI['fN'].' '.$tI['lN'];
												if(trim($tIname) == trim($print_name)) {
													if($tI['fd']['fC']) {
														$base_fare += $tI['fd']['fC']['BF']??0;
														$taxes_and_fees += $tI['fd']['fC']['TAF']??0;
														$ssr_fees += $tI['fd']['fC']['SSRP']??0;
														$total += $tI['fd']['fC']['TF']??0;
														if($inventory_id) {
															$taxes_and_fees = ($order->admin_markup/$total_pax);
															// $total = ($total + $taxes_and_fees);
														}
													}
												}
											}
										}
									}
								}
								if($inventory_id) {
									$total = ($total + $taxes_and_fees);
								}
							}
						}
					}
					// prd($total);

					$barcode = 'M1'.$travellerInfo['lN'].'/'.$travellerInfo['fN'].' '.implode('/', array_unique($pnr_arr)).' '.implode(' ', array_unique($sector));

					if(!empty($print_name)) {
						$taxes_and_fees = $taxes_and_fees+($total_markup/$total_pax);
						$total = $total+($total_markup/$total_pax);
						$amount_paid = $total;
					}
				?>
				<tr>
					<td style="padding: 3px 8px; border: 1px solid #d8d8d8;"><p style="font-size: 12px;">{{$index+1}}</p></td>
					<td style="padding: 3px 8px; border: 1px solid #d8d8d8;">
						<p style="font-size: 15px;"><strong>{{$name}} ({{substr($travellerInfo['pt'],0,1)}})</strong></p>
						<img src="data:image/png;base64,{{$d->getBarcodePNG($barcode, 'PDF417')}}" alt="{{$barcode}}" style="width: 170px;height: 50px;" />
					</td>
					<td style="padding: 3px 8px; border: 1px solid #d8d8d8;width: 80px">
						@foreach($booking_details['itemInfos']['AIR']['tripInfos'] as $tripInfo)
						<?php
						$src = $tripInfo['sI'][0]['da']['code']??'';
						$dest = $tripInfo['sI'][count($tripInfo['sI'])-1]['aa']['code']??'';
						$key = $src.'-'.$dest;
						?>
							<p style="margin: 0;font-size: 12px;">
							{{$key}}
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
				<?php } ?>
			</table>
			<?php } ?>

			<table width="100%" style="border-collapse: collapse; width:100%; border: 1px solid #b1b1b1; font-family: sans-serif;">
			<tr>
					<td colspan="7" style="padding: 15px;background-color: #0096882b;border-bottom: 1px solid #d4d4d4;font-size: 12px;"><strong>Customer Contact -</strong> <span style="padding: 0 3px 8px;">E-mail: <a href="mailto:{{$order->email}}">{{$order->email}}</a></span> |<span style="padding: 0 3px 8px;">Contact No: <a href="tel:{{$order->country_code.$order->phone}}">{{'+'.$order->country_code.'-'.$order->phone}}</a></span></td>
				</tr>
			</table>
           
			<?php /*if($booking_details['gstInfo']){ ?>
			<table width="100%" style="border-collapse: collapse; width:100%; border: 1px solid #b1b1b1; font-family: sans-serif;">
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
			</table>
			<?php }*/ ?>

			<?php if($hide_price_details == 0 && $booking_details['itemInfos']['AIR']['totalPriceInfo']['totalFareDetail']){ ?>
			<table width="100%" style="border-collapse: collapse; width:100%; border: 1px solid #b1b1b1; font-family: sans-serif;">
				<tr>
					<td  colspan="5" style="padding: 5px 15px; font-size: 14px; color: #18afa6;  border-bottom: 1px solid #d4d4d4;">Payment Details</td>
				</tr>
				<tr>
					<td colspan="3" style="padding: 6px 15px; padding-top: 15px;font-size: 12px;" >Base Fare</td>
					<td colspan="2" style="padding: 6px 15px; padding-top: 15px; text-align: right;font-size: 12px;" >{{CustomHelper::getPrice($base_fare,'Rs.',true)}}</td>
				</tr>
				<tr>
					<td colspan="3" style="padding: 6px 15px;font-size: 12px;" >Taxes and Fees</td>
					<td colspan="2" style="padding: 6px 15px; text-align: right;font-size: 12px;" >{{CustomHelper::getPrice($taxes_and_fees,'Rs.',true)}}</td>
				</tr>
				@if(empty($inventory_id))
				<tr>
					<td colspan="3" style="padding: 6px 15px;font-size: 12px;" >SSR (Baggage, Meal, Seat...) Fees</td>
					<td colspan="2" style="padding: 6px 15px; text-align: right;font-size: 12px;" >{{CustomHelper::getPrice($ssr_fees,'Rs.',true)}}</td>
				</tr>
				@endif
				<tr>
					<td colspan="3" style="padding: 6px 15px; padding-bottom: 15px; border-bottom: 1px solid #d4d4d4;font-size: 12px;" >Total</td>
					<td colspan="2" style="padding: 6px 15px; text-align: right; padding-bottom: 15px; border-bottom: 1px solid #d4d4d4;font-size: 12px;" >{{CustomHelper::getPrice($total,'Rs.',true)}}</td>
				</tr>
				<tr>
					<td colspan="3" style="padding: 15px;font-size: 12px;" ><b>Amount Paid</b></td>
					<td colspan="2" style="padding: 15px; text-align: right;font-size: 12px;" ><b>{{CustomHelper::getPrice($amount_paid,'Rs.',true)}}</b></td>
				</tr>
			</table>
			<?php } ?>
	</div>
	<div style="background: #18afa6;color: #fff;padding: 2px 3px 8px 5px;line-height: normal;font-family: sans-serif;font-size:14px;">Important Information</div>
	<div style="font-family: sans-serif;font-size:12px">
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
	<img src="{{public_path().'/images/inclusion-exclusion.jpg'}}" style="width: 100%; float: left;">
	</div>
	<?php } ?>
</body>
</html>