<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Package Voucher</title>
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
									<p style="font-size: 13px;font-family: 'Roboto', sans-serif, Arial;color: #2b2c2c;line-height: 20px;margin-bottom: 8px;margin-top: 0;">Your booking for {package_name} is confirmed. Please be sure to check the instructions on how to redeem your Package.</p>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding: 2px 15px 2px 20px;">
									<p style="color: #3a3a3c;font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Booking Number:</strong> {order_no}</p>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding: 2px 15px 2px 15px;">
									<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Package Details: </strong></p>

									<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;">{package_name}, {destination}</p>

									<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Trip Date:</strong> {trip_date}</p>

									<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;"><strong>Duration:</strong> {duration}</p>
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
								<tr>
									<td colspan="2" style="padding: 2px 15px 2px 15px;">
									<p style="color: #12968e;font-size: 16px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;"><strong>Package Address: </strong></p>

									<p style="font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;color: #333;">{address}</p>
									</td>
								</tr>
								{price_details}
								<tr>
									<td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
									<p style="color: #fff;font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
									<a href="www.overlandescape.com" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> www.overlandescape.com</a></p>

									<p style="color: #fff;font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> <a href="tel:01128041602" style="color: #fff;text-decoration: none;">011-28041602</a>; <b>Mobile:</b> <a href="tel:8491947040" style="color: #fff;text-decoration: none;">8491947040</a>; <b>Email:</b> <a href="mailto:info@overlandescape.com" style="color: #fff;text-decoration: none;">info@overlandescape.com</a></p>

									<p style="color: #fff;font-size: 12px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

									<p style="color: #fff;font-size: 14px;font-weight: 400;font-family: 'Roboto', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">Overland Escape. All Rights Reserved.</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</body>
			</html>

