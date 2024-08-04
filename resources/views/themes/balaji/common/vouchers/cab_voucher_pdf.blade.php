<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cab Voucher</title>
</head>
<body> 
	<div style="width: 100%;  margin: 8px auto;">
	<table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;border: 1px solid #dee2e6;" width="100%">
		<tbody>
			{header}
			<tr style="">
				<td colspan="3" style="padding: 15px;text-align: center;border: 1px solid #585858;"><span style="font-size: 25px;color: #3f4041;font-family: 'Roboto', sans-serif, Arial;"><strong>Cab/ Taxi Service Voucher</strong></span></td>
			</tr>
			<tr>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>Tour Operator</strong></p>

				<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>{tour_operator}</strong></p>
				</td>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>Agency</strong></p>

				<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>{agency}</strong></p>
				</td>
				<td style="padding: 2px 15px 2px 15px; border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>Booking ID</strong></p>

				<p style="color: #333;font-size: 18px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>{order_id}</strong></p>
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>Booked on:</strong></p>

				<p style="color: #333;font-size: 18px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 0;"><strong>{booking_date}</strong></p>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 2px 15px 2px 15px;background: #12968e;border: 1px solid #585858;">
				<p style="color: #fff;font-size: 20px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Onward Journey Details</strong></p>
				</td>
			</tr>
			<tr style="background: #ebebeb;">
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Vehicle</strong></p>
				</td>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Departing</strong></p>
				</td>
				<td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Arrival</strong></p>
				</td>
			</tr>
			<tr>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{car_type}</p>
				</td>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{departing} <br>{pickup_date}</p>
				</td>
				<td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
				<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{arrival}</p>
				</td>
			</tr>
			<tr style="background: #ebebeb;">
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Driver Details</strong></p>
				</td>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Pickup</strong></p>
				</td>
				<td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
				<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Drop</strong></p>
				</td>
			</tr>
			<tr>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				{driver_details}
				</td>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{pickup_address}</p>
				</td>
				<td class="align-top" style="padding: 2px 15px 2px 15px;vertical-align: top;border: 1px solid #585858;">
				<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{drop_address}</p>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 2px 15px 2px 15px;background: #12968e;border: 1px solid #585858;">
				<p style="color: #fff;font-size: 20px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Passenger Details</strong></p>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
				<p style="font-size: 15px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;">{name} | {phone} | {email}</p>
				</td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
				<p style="color: #333;font-size: 15px;font-family: 'Roboto', sans-serif, Arial;margin: 0;line-height: normal;">Payment Details</p>
				</td>
				<td style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
				<p style="color: #333;font-size: 15px;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;text-align: left;">Amount (INR)</p>
				</td>
			</tr>
			{price_details}
			<tr>
				<td colspan="3" style="padding: 2px 15px 2px 15px;border: 1px solid #585858;">
				<p style="font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;">Customer Details | Email : {company_email} | Contact No : {agency}</p>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 2px 15px 2px 15px;background: #e9e9e9;border: 1px solid #585858;">
				<p style="color: #3a3a3c;font-size: 20px;font-family: 'Roboto', sans-serif, Arial;margin: 12px 0px 15px;line-height: normal;">Terms and Conditions</p>

				<ul style="padding: 0 22px;margin-top: 0px;font-family: 'Roboto', sans-serif, Arial;font-size: 12px;line-height: 1.2;">
					<li>Guests will have to pay the Toll Charges &amp; State Tax directly to the driver at the end of the trip</li>
					<li>In case of partial payment, the balance payment of trip needs to be paid in advance at the time of pick-up</li>
					<li>You need to collect receipts from the driver for any extra charges paid directly to the driver during the trip. Overland Escape is not liable to provide invoices for such amounts.</li>
					<li>Any grievances or claims related to the cab travel should be reported to Overland Escape within 12 hours of travel time</li>
					<li>The booking will be for cab type Suv and we do not commit on providing any preferred cab model (Xylo, Ertiga or similar)</li>
					<li>AC will be switched off in hilly areas</li>
					<li>You can accommodate 1 bag in Hatchback, 2 bags in Sedan and 3 bags in SUV</li>
					<li>We do not commit on particular fuel type vehicle like Petrol, Diesel or CNG. Cab will be provided on the basis of availability.</li>
					<li>You are not allowed to take pets with you inside the cab</li>
					<li>Due to traffic or any other unavoidable reason, pickup may be delayed by 30 mins</li>
					<li>Driver details will be shared up to 30 mins prior to departure.</li>
					<li>Local sightseeing in Origin city is not permitted</li>
					<li>You are solely responsible for managing your own travel schedule. Overland Escape will not be liable for any compensation if you happen to miss your scheduled flight, train or bus due to delay pickup, traffic jam or delay due to any other reason during the trip</li>
				</ul>
				</td>
			</tr>
			<tr style="display: none;">
				<td colspan="2" style="padding: 2px 0px 2px 40px;background: #12968e;">
				<p style="color: #fff; font-size: 13px; font-weight: 400; font-family: 'Roboto', sans-serif, Arial; margin: 4px 0px;"><img src="https://www.overlandescape.com/assets/overlandescape/img/location.png" /> Skara Main Road, Skara Yokma,<br />
				Leh, 194101, Ladakh, India</p>

				<p style="color: #fff; font-size: 13px; font-weight: 400; font-family: 'Roboto', sans-serif, Arial; margin: 4px 0px;"><img src="https://www.overlandescape.com/assets/overlandescape/img/telephone.png" /> <b>Ticket Booking or Enquiry:</b><br />
				<a href="tel:+91 8491947052" style="color: #fff;text-decoration: none;">+91 8491947052</a> / <a href="tel:+91 8491947053" style="color: #fff;text-decoration: none;"> 8491947053</a></p>
				</td>
				<td colspan="2" style="padding: 2px 0px 2px 40px; background: #12968e;">
				<p style="color: #fff; font-size: 13px; font-weight: 400; font-family: 'Roboto', sans-serif, Arial; margin: 4px 0px;"><img src="https://www.overlandescape.com/assets/overlandescape/img/telephone.png" /> <b>Hotel Booking or Enquiry:</b><br />
				<a href="tel:+91 9858394405" style="color: #fff;text-decoration: none;">+91 9858394405</a> / <a href="tel:+91 8491947044" style="color: #fff;text-decoration: none;"> 8491947044</a></p>

				<p style="color: #fff; font-size: 13px; font-weight: 400; font-family: 'Roboto', sans-serif, Arial; margin: 4px 0px;"><img src="https://www.overlandescape.com/assets/overlandescape/img/telephone.png" /> <b>Tour &amp; Trek Booking or Enquiry:</b><br />
				<a href="tel:+ 91 8491947039" style="color: #fff;text-decoration: none;">8491947039</a> / <a href="tel:+ 8491947040" style="color: #fff;text-decoration: none;"> 8491947040</a></p>
				</td>
			</tr>
		</tbody>
	</table>
	</div>
	</body>
	</html>