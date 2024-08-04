<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Activity Voucher</title>
</head>
<body> 
	<div style="width: 700px;  margin: 8px auto;">
		<table width="100%" style="border-collapse: collapse; width:100%; border: 1px solid #b1b1b1; font-family: 'Roboto', sans-serif, Arial;">

			<tbody>
				<tr style="background-color: #18afa6;">
					<td align="left" colspan="2" style="padding: 20px 40px; border-bottom: 1px solid #00000017;">
						<div><a href="{{url('/')}}"><img alt="logo" src="{logo}" style="height: 55px;" /></a></div>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding: 16px 15px 2px 15px;"><span style="font-size: 24px; color: #3f4041; font-family: 'Roboto', sans-serif, Arial;">Hi {name},</span>
						<p style="font-size: 13px;font-family: 'Roboto', sans-serif, Arial;color: #2b2c2c;line-height: 20px;margin-bottom: 8px;margin-top: 0;">Your booking for {package_name} is confirmed. Please be sure to check the instructions on how to redeem your activity.</p>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding: 2px 15px 2px 20px;">
						<p style="color: #3a3a3c;font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Booking Number:</strong> {order_no}</p>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding: 2px 15px 2px 15px;">
						<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Activity Details: </strong></p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;">{package_name}, {destination}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Trip Date:</strong> {trip_date}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Duration:</strong> {duration}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Activity Location:</strong> {address}</p>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding: 2px 15px 2px 15px;">
						<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Contact Details: </strong></p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"> <strong>Contact Person:</strong> {contact_person}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"> <strong>Contact Phone:</strong> {contact_phone}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Contact Email:</strong> {contact_email}</p>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding: 2px 15px 2px 15px;">
						<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Traveller Details: </strong></p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;">{name}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;">{phone} | {email}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Persons:</strong> {person}</p>
					</td>
				</tr>
				{price_details}
				<tr>
					<td colspan="2" style="padding: 2px 15px 2px 15px;background: #e9e9e9;">
						<p style="color: #3a3a3c;font-size: 20px;font-family: 'Roboto', sans-serif, Arial;margin: 12px 0px 0;line-height: normal;"><strong>Cancellation Policy</strong></p>

						<ul style="padding: 0 22px;margin-top: 5px;font-family: 'Roboto', sans-serif, Arial;/* font-size: 14px; */">
							<li>Zero penalty within 15 minutes of booking</li>
							<li>Zero penalty before 1 hours of departure</li>
							<li>30% penalty up to Rs 500 within 1 hours of departure</li>
							<li>100% penalty beyond departure time</li>
							<li>For cancellation, please call our support team at 011-28041602, 8491947040</li>
							<li>Overland Escape will not be held responsible for any cancellation or delay of service in case of any natural calamity, agitation or strike, traffic jam or road blockage, etc.</li>
							<li>Any modifications/amendments to the booking are not allowed</li>
							<li>In the event of cancellation of a cab trip, Overland Escape&#39;s liability will be limited only to the extent of refunding the sum paid by the passenger for the price of the e-ticket</li>
						</ul>
					</td>
				</tr>
				{contact_details}
				</tbody>
			</table>
		</div>
	</body>
	</html>

