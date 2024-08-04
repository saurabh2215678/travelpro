<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Booking</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<style>
		*{font-family: 'Barlow', sans-serif;}
	</style>
</head>
<body>
<?php
$websiteSettingsArr = CustomHelper::getSettings(['SYSTEM_TITLE', 'FRONTEND_LOGO','SALES_PHONE','SITE_ADDRESS']);

$storage = Storage::disk('public');
$path = "settings/";
$logoSrc = asset(config('custom.assets').'/images/footer_logo.png');
if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
    $logo = $websiteSettingsArr['FRONTEND_LOGO'];
    if($storage->exists($path.$logo)){
        $logoSrc = asset('/storage/'.$path.$logo);
    }
}
if(isset($booking_details)){
?>

	<div class="table-responsive">
		<table width="100%" class="table table-sm">
			<thead>

				<tr>
				  <td><strong>Cab Image :</strong></td>
				  <td><?php if(isset($booking_details['cab_image'])){ ?>
							<img src="{{$booking_details['cab_image']??''}}" width="100px;">
					<?php	} ?></td>
				</tr>
				<tr>
					<td><strong>Pickup Address :</strong></td>
					<td>{{$booking_details['pickup_address']??''}}</td>
				</tr>
				<tr>
				  <td><strong>Drop Address :</strong></td>
				  <td>{{$booking_details['drop_address']??''}}</td>
				</tr>
				<tr>
				  <td><strong>Trip Date :</strong></td>
				  <td>{{$booking_details['trip_date']??''}}</td>
				</tr>
				<tr>
				  <td><strong>Trip Type :</strong></td>
				  <td>{{$booking_details['trip_type']??''}}</td>
				</tr>
				<tr>
				  <td><strong>Cab Name :</strong></td>
				  <td>{{$booking_details['cab_name']??''}}</td>
				</tr>
                <tr>
				  <td><strong>Itinerary :</strong></td>
				  <td>{{$booking_details['itinerary']??''}}</td>
				</tr>
				<tr>
				  <td><strong>Booking Time :</strong></td>
				  <td> {{date('d M,Y H:i A',strtotime($order->created_at))??''}}</td>
				</tr>
				@if(isset($booking_details['adult']) || isset($booking_details['children']))
				<tr>
					<td><strong>Total Pax :</strong></td>
					<td>
						@if(isset($booking_details['adult']) && !empty($booking_details['adult']) && isset($booking_details['children']) && !empty($booking_details['children']))
						<span>{{$booking_details['adult']}} Adults, {{$booking_details['children']}} Child</span>
						@elseif(isset($booking_details['adult']) && !empty($booking_details['adult']))
						<span v-else-if="this?.routeInfo?.adult">{{$booking_details['adult']}} Adults</span>
						@elseif(isset($booking_details['children']) && !empty($booking_details['children']))
						<span v-else-if="this?.routeInfo?.children">{{$booking_details['children']}} Child</span>
						@endif
					</td>
				</tr>
				@endif
				@if(isset($booking_details['selected_addons']) && !empty($booking_details['selected_addons']))
				@foreach($booking_details['selected_addons'] as $addon)
				<tr>
					<td><strong>{{$addon['name']??''}} :</strong></td>
					<td>Qty: {{$addon['qty']??0}}<?php if(isset($addon['days']) && $addon['days'] > 0){ ?><span>, Days: {{$addon['days']??''}}</span><?php } ?><!-- ({{CustomHelper::getPrice($addon['price']??0)}}) --></td>
				</tr>
				@endforeach
				@endif
			</thead>
			<tbody>
		</table>
	</div>
	<?php } ?>
</body>
</html>