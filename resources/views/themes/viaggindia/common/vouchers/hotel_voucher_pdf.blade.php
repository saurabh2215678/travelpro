<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Hotel Voucher</title>
</head>
<body>
	<div style="width: 700px;  margin: 8px auto;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; width:100%; border: 1px solid #b1b1b1; font-family: 'Roboto', sans-serif, Arial;">
			<tbody>
				{header}
				<tr>
					<td colspan="3" style="padding: 16px 15px 2px 15px;"><span style="font-size: 24px; color: #3f4041; font-family: 'Roboto', sans-serif, Arial;">Dear {customer_name},</span>
						<p style="font-size: 13px;font-family: 'Roboto', sans-serif, Arial;color: #2b2c2c;line-height: 20px;margin-bottom: 8px;margin-top: 0;">Thank you for choosing Overland Escape</p>

						<p style="font-size: 13px;font-family: 'Roboto', sans-serif, Arial;color: #2b2c2c;line-height: 20px;margin-bottom: 8px;margin-top: 0;">Your hotel booking is confirmed. Your e-ticket is attached with the email sent to your email id.</p>
					</td>
				</tr>
				<tr>
					<td style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Hotel Name:</strong> {hotel_name}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Address:</strong> {address}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Contact Person:</strong> {contact_person}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Contact Phone:</strong> {phone}</p>

						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Contact Email:</strong> {email}</p>
					</td>
					<td style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 15px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #7a7a7a;text-align: center;margin-bottom: 0;"><strong>Check IN:</strong></p>

						<p style="font-size: 20px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;text-align: center;">{checkin_date}</p>
						<p style="font-size: 14px;font-weight: 400;font-family: Roboto;margin: 4px 0px;color: #333;text-align: center;">{checkin_time}</p>

					</td>
					<td style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 15px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #7a7a7a;text-align: center;margin-bottom: 0;"><strong>Check OUT:</strong></p>

						<p style="font-size: 20px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;text-align: center;">{checkout_date}</p>
						<p style="font-size: 14px;font-weight: 400;font-family: Roboto;margin: 4px 0px;color: #333;text-align: center;">{checkout_time}</p>
					</td>
				</tr>
				<tr>
					<td colspan="" style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;;margin: 4px 0px;color: #333;"><strong>Booking ID:&nbsp;</strong>{booking_id}</p>
					</td>
					<td colspan="3" style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Date of Booking:&nbsp;</strong>{booking_date}</p>
					</td>
				</tr>
				<tr>
					<td style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Room Type:</strong>&nbsp;{room_type}</p>
					</td>
					<td colspan="3" style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>No. Of Room:</strong>&nbsp;{inventory}</p>
					</td>
				</tr>
				<tr>
					<td style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Rate Plan:</strong>&nbsp;{plan_name}</p>
					</td>
					<td colspan="3" style="padding: 2px 15px 2px 15px;">
						<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Persons:</strong>&nbsp;{adult} Adult / {child} Child</p>
					</td>
				</tr>
				{price_details}
				<tr>
					<td colspan="3" style="padding: 15px 15px 12px 15px;border: 0;">
						<p style="color: #333;font-size: 15px;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>Inclusions:</strong> {inclusions}</p>
					</td>
				</tr>

				<tr>
					<td colspan="3" style="padding: 15px 15px 12px 15px;border: 0;background: #e7e7e7;">
						<p style="color: #404040;font-size: 18px;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>Cancellation &amp; Amendment Policy</strong></p>

						<ul style="padding-left: 25px;margin-top: 3px;margin-bottom: 0;font-size: 14px;line-height:1.5;">
							<li>Booking is Non-Refundable.</li>
							<li>Any Add On charges are non-refundable</li>
							<li>You can not change the check-in or check-out date</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding: 15px 15px 12px 15px;border: 0;background: #e7e7e7;">
						<p style="color: #404040;font-size: 18px;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>Hotel Policy</strong></p>

						<ul style="padding-left: 25px;margin-top: 3px;font-size: 14px;line-height:1.5;">
							<li>The standard check-in time is 02:00 PM and the standard check-out time is 12:00 PM. Early check-in or late check-out is strictly subjected to availability and may be chargeable by the hotel. Any early check-in or late check-out request must be directed and reconfirmed with hotel directly.</li>
							<li>All children are welcome.</li>
							<li>Free! One child under 11 years stays free of charge when using existing beds.</li>
							<li>Free! One child under 2 years stays free of charge in a child&#39;s cot/crib.</li>
							<li>Breakfast is chargeable for children aged 5 years and above. (Payable directly to hotel)</li>
							<li>The maximum number of extra beds/children&#39;s cots permitted in a room is 1.</li>
							<li>Any increase in the price due to taxes will be borne by you and payable at the hotel.</li>
							<li>The primary age of the guest must be at least 18 years old to be able to check into this hotel</li>
							<li>Your stay does not include additional personal expenses like telephone charges, meals that aren&#39;t part of your meal plan, any hotel services you use (like laundry and room service) or tips. The hotel will charge you directly for these when you&#39;re checking out.</li>
							<li>It is mandatory for guests to present valid photo identification at the time of check-in. According to government regulations, a valid Photo ID has to be carried by every person above the age of 18 staying at the hotel.</li>
							<li>Hotels may charge a mandatory meal surcharge on festive periods e.g. Christmas, New Year&#39;s Eve etc... All additional charges (including mandatory meal surcharges) need to be cleared directly at the hotel.</li>
							<li>Please note that it takes minimum of 4 to 8 working hours to confirm a reservation at the hotel for same day check-ins.</li>
							<li>Hotels may not allow local residents as guests to check-in. This is strictly subjected to the Hotel Policies.</li>
							<li>For Invoice &amp; tax breakup please contact the Hotel as per the details provided or collect the same from the hotel during checkout.</li>
						</ul>
					</td>
				</tr>
				{contact_details}
				</tbody>
			</table>
		</div>
	</body>
	</html>