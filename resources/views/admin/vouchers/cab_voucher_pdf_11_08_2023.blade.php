<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
     
      <title>Cab Voucher</title>
   </head>

   <?php
   $order_id = $OrderServiceVoucher->order_id ?? '';
   $name_of_pax = $OrderServiceVoucher->name_of_pax ?? '';
   $pax_contact = $OrderServiceVoucher->pax_contact ?? '';
   $local_contact = $OrderServiceVoucher->local_contact ?? '';
   $agent_contact = $OrderServiceVoucher->agent_contact ?? '';
   $location = $OrderServiceVoucher->location ?? '';
   $hotel_data = $OrderServiceVoucher->hotel_data ? json_decode($OrderServiceVoucher->hotel_data ) :[];
   $date = $OrderServiceVoucher->date ?? '';
   $remarks = $OrderServiceVoucher->remarks ?? '';
   $payment_mode = $OrderServiceVoucher->payment_mode ?? '';
   $vehicle_type = $OrderServiceVoucher->vehicle_type ?? '';
    ?>
   <body>
      <section>
         <div class="container">
            <div class="row">
               <div class="logo text-end" style="padding: 20px 0;">
                  <img src="https://www.overlandescape.com/storage/settings/240223053248-080223040609-overland_logo.jpg" alt="logo">
               </div>
            </div>
            <div class="row">
               <table class="table table-bordered">
                  <thead>
                     <tr>
                        <th scope="col" style="min-width: 225px;">Services Voucher</th>
                        <th scope="col">{{$order_id}}</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <th scope="row">Name of the Pax</th>
                        <td>{{$name_of_pax}}</td>
                     </tr>
                     <tr>
                        <th scope="row">Pax Contact</th>
                        <td>{{$pax_contact}}</td>
                     </tr>
                     <tr>
                        <th scope="row">Local Contact</th>
                        <td colspan="2">{{$local_contact}}</td>
                     </tr>
                     <tr>
                        <th scope="row">Agent Contact</th>
                        <td colspan="2">{{$agent_contact}}</td>
                     </tr>
                 <!--     <tr>
                        <th scope="row">Overland Escape Contact</th>
                        <td colspan="2">....</td>
                     </tr> -->

                     <tr>
                        <th scope="row">Hotel Type</th>
                        <td colspan="2">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th scope="col">Location</th>
									<th scope="col">Hotel</th>
									<th scope="col">Date</th>
									<th scope="col">Nights</th>
									<th scope="col">Rooms</th>
									<th scope="col">Meals</th>
								</tr>
							</thead>
							<?php 
							foreach ($hotel_data as $key => $hotel_row) {
							
						?>
							<tbody>
								<tr>
									<td>{{$hotel_row->location_name ?? ''}}</td>
									<td>{{$hotel_row->hotel_name ?? ''}}</td>
									<td>{{$hotel_row->date ?? ''}}</td>
									<td>{{$hotel_row->night ?? ''}}</td>
									<td>{{$hotel_row->rooms ?? ''}}</td>
									<td>{{$hotel_row->meals ?? ''}}</td>
								
								</tr>
								
							</tbody>
						<?php } ?>
						 </table>
						</td>
                     </tr>
					 <tr>
                        <th scope="row">Vehicle Types</th>
                        <td colspan="2">{{$vehicle_type}}</td>
                     </tr>
					 <tr>
                        <th scope="row">Itinerary Types</th>
                        <td colspan="2"> <p><strong>05 - May: Leh</strong> <br>Arrive at Leh airport, meet our representative and drive to your reserved accommodation. Check In to the hotel and get proper rest to acclimatize. Later, in the evening visit Leh Market, Leh Palace and Shanti Stupa. (O/N Leh)</p>
						<p><strong>06 - June: Leh</strong> <br>Arrive at Leh airport, meet our representative and drive to your reserved accommodation. Check In to the hotel and get proper rest to acclimatize. Later, in the evening visit Leh Market, Leh Palace and Shanti Stupa. (O/N Leh)</p></td>
                     </tr>
					 <tr>
                        <th scope="row">Flight Details</th>
                        <td colspan="2">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th scope="col">DOT</th>
									<th scope="col">Flight No</th>
									<th scope="col">Sector</th>
									<th scope="col">Departures</th>
									<th scope="col">Arrivals</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>03.04.2023</td>
									<td>GTS-52655</td>
									<td>DEL</td>
									<td>12:15 pm</td>
									<td>14:15 pm</td>
								</tr>
								<tr>
									<td>04.04.2023</td>
									<td>GTS-52644</td>
									<td>LEH</td>
									<td>11:45 am</td>
									<td>13:15 pm</td>
								</tr>
							</tbody>
						 </table>
						</td>
                     </tr>
					 <tr>
                        <th scope="row">Payment Mode</th>
                        <td colspan="2">Online , Credit Card, Debit Card</td>
                     </tr>
					 <tr>
                        <th scope="row">Remarks</th>
                        <td colspan="2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sed orci in magna mollis placerat. Nam a placerat orci. Donec quis mattis neque. Nullam nibh purus, commodo ut efficitur quis, rhoncus id mauris. Nullam vehicula finibus mi, quis suscipit ex convallis in.</td>
                     </tr>
                  </tbody>
               </table>
            </div>
			<div class="row">
               <div class="footertxt" style="width: 340px;text-align: center;margin: 0 0 0 auto;">
                  <p style="font-weight: 500;">For Overland Escape Ladakh<br> Overland Escape Travel Service Pvt. Ltd.</p>
				  <p>Overland Escape<br> Overland Escape Travel Service Pvt. Ltd.</p>
               </div>
            </div>
         </div>
      </section>
   </body>
</html>