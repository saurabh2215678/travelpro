<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Activity Voucher</title>
</head>
<body> 
	<div style="width: 800px;  margin: 8px auto;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td>
			<table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="800">
				<tbody>
					<tr>
						<td>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td align="center" colspan="5" style="padding: 12px;color: #3f4041; font-size:28px;font-family: 'Roboto', sans-serif, Arial;"><strong>Activity Confirmation Voucher</strong></td>
								</tr>
								<tr>
									<td align="left" colspan="5">{header}</td>
								</tr>
								<tr>
									<td colspan="5" style="padding: 15px 5px 2px 5px;border: 1px solid #585858;border-left: 0;"><span style="font-size: 24px; color: #3f4041; font-family: 'Roboto', sans-serif, Arial;">Hi {name},</span>
									<p style="font-size: 13px;font-family: 'Roboto', sans-serif, Arial;color: #2b2c2c;line-height: 17px;margin-bottom: 8px;margin-top: 0; width:600px;">Your booking for {package_name} is confirmed. Please be sure to check the instructions on how to redeem your activity.</p>
									</td>
								</tr>
								<tr>
									<td colspan="5" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;border-top: 0;border-left: 0;">
									<p style="color: #3a3a3c;font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Booking ID:</strong> {order_no}</p>
									</td>
								</tr>
								<tr style="background: #ebebeb;">
									<td class="align-top" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-bottom: 0;border-left: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Activity Name</strong></p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Destination</strong></p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Trip Date</strong></p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Duration</strong></p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Activity Location</strong></p>
									</td>
								</tr>
								<tr colspan="5">
									<td class="align-top" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-left: 0;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;width: 110px;">{package_name}</p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{destination}</p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;border: 1px solid #585858; vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{trip_date}</p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;width: 70px;">{duration}</p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;border: 1px solid #585858; vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px; width: 120px;">{address}</p>
									</td>
								</tr>
								
								<tr style="background: #ebebeb;">
									<td class="align-top" colspan="2" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-bottom: 0;border-left: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Contact Person</strong></p>
									</td>
									<td class="align-top" colspan="1" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Contact Phone</strong></p>
									</td>
									<td class="align-top" colspan="2" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Contact Email</strong></p>
									</td>
								</tr>
								<tr>
									<td class="align-top" colspan="2" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-left: 0;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{contact_person}</p>
									</td>
									<td class="align-top" colspan="1" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{contact_phone}</p>
									</td>
									<td class="align-top" colspan="2" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{contact_email}</p>
									</td>
								</tr>
								
								<tr style="background: #ebebeb;">
									<td class="align-top" colspan="" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-bottom: 0;border-left: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Traveller Name</strong></p>
									</td>
									<td class="align-top" colspan="" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Phone</strong></p>
									</td>
									<td class="align-top" colspan="2" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Email</strong></p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;border-bottom: 0;">
									<p style="color: #333333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Persons</strong></p>
									</td>
								</tr>
								<tr>
									<td class="align-top" colspan="" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;border-left: 0;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{name}</p>
									</td>
									<td class="align-top" colspan="" style="padding: 2px 5px 2px 5px;vertical-align: top;border: 1px solid #585858;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{phone}</p>
									</td>
									<td class="align-top" colspan="2" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{email}</p>
									</td>
									<td class="align-top" style="padding: 2px 5px 2px 5px;border: 1px solid #585858;vertical-align: top;">
									<p style="color: #333;font-size: 13px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;">{person}</p>
									</td>
								</tr>
								<tr>
									<td colspan="6" style="padding: 2px 5px 2px 5px;background: #12968e;border: 1px solid #12968e;">
									<p style="color: #fff;font-size: 15px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Price Details </strong></p>
									</td>
								</tr>
								<tr>
									<td colspan="5" style="padding: 5px 0px 5px 0px;border: 0;">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td style="color: #333;font-size: 15px;font-weight: 500;font-family: 'Roboto', sans-serif, Arial;margin: 0;line-height: normal;">{price_details}</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
								<tr>
									<td colspan="5">
									<div style="width:665px; background: #f7f7f7;padding: 15px;">
										<p style="color: #3a3a3c;font-size: 16px;font-family: 'Roboto', sans-serif, Arial;margin: 12px 0px 0;line-height: normal;"><strong>Cancellation Policy</strong></p>
	
										<ul style="padding: 0 22px;margin-top: 5px;font-family: 'Roboto', sans-serif, Arial; font-size: 13px;line-height: 22px;">
										<li style="font-family: 'Roboto', sans-serif, Arial;font-size: 13px;line-height:22px;">Zero penalty within 15 minutes of booking</li>
										<li style="font-family: 'Roboto', sans-serif, Arial;font-size: 13px;line-height:22px;">Zero penalty before 1 hours of departure</li>
										<li style="font-family: 'Roboto', sans-serif, Arial;font-size: 13px;line-height:22px;">30% penalty up to Rs 500 within 1 hours of departure</li>
										<li style="font-family: 'Roboto', sans-serif, Arial;font-size: 13px;line-height:22px;">100% penalty beyond departure time</li>
										<li style="font-family: 'Roboto', sans-serif, Arial;font-size: 13px;line-height:22px;">For cancellation, please call our support team at 011-28041602, 8491947040</li>
										<li style="font-family: 'Roboto', sans-serif, Arial;font-size: 13px;line-height:22px;">Overland Escape will not be held responsible for any cancellation or delay of service in case of any natural calamity, agitation or strike, traffic jam or road blockage, etc.</li>
										<li style="font-family: 'Roboto', sans-serif, Arial;font-size: 13px;line-height:22px;">Any modifications/amendments to the booking are not allowed</li>
										<li style="font-family: 'Roboto', sans-serif, Arial;font-size: 13px;line-height:22px;">In the event of cancellation of a cab trip, Overland Escape&#39;s liability will be limited only to the extent of refunding the sum paid by the passenger for the price of the e-ticket</li>

										</ul>
									</div>
									</td>
								</tr>

								<tr>
									<td colspan="4">{contact_details}</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	  </tbody>
     </table>
	</div>
</body>
</html>

