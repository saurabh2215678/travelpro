@component(config('custom.theme').'.layouts.main')
    @slot('title')
        {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
    @slot('headerBlock')

    @endslot
    @slot('bodyClass')
    	home_class
    @endslot
	<?php
	$storage = Storage::disk('public');
	$websiteSetting = CustomHelper::getSettings(['BANK_TRANSFER']);
	$bankDetails = (!empty($websiteSetting['BANK_TRANSFER'])) ? $websiteSetting['BANK_TRANSFER']:"";

	// prd($bankData);

	?>
	<div class="breadcrumb_full">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="{{url('/')}}">Home</a>
				</li>
				<li><a href="javascript:void(0)">Bank Details </a>
				</li>
			</ul>
		</div>
	</div>
	<section class="payment-section">
    	<div class="container">
    	<div class="paymen-detail">
        <div class="row">
        	<h1 style="width: 100%; text-align: center; background: #e5e5e5;font-size: 1.5rem;font-weight: 600;">
        		<?php if($bankData['status'] == 1){
        			echo '<i class="fa fa-check" style="color: #008000; border: 1px solid;border-radius: 50px;"></i>';
        		}elseif($bankData['status'] == 2){
        			echo '<i class="fa fa-times" style="color: #f00;"></i> ';
        		}?>
        		 Bank Transfer Details</h1>
				<div class="payment_box">
					<table>
						<tr>        		
							<td>
								<p><strong>Order No: </strong> {{ $bankData['order_no'] }}</p>
							</td>
							<td>
								<?php   $order_date = (isset($bankData['order_date']) && !empty($bankData['order_date']))?CustomHelper::DateFormat($bankData['order_date'], 'd/m/Y'):NULL; ?>
								<p><strong>Booking Date :</strong> {{ $order_date }}</p>
							</td>
						</tr>

							<?php  
							if($bankData['pay_type'] == 'partial_amount'){ ?>
							<tr>
							<td><strong>Partial Amount :</strong></td>
							<td>{{ CustomHelper::getPrice($bankData['partial_amount'])}}</td>
							</tr>
								
								
							<?php }elseif($bankData['pay_type'] == 'booking_price'){ ?>
								<td>
									<p>
										<strong>Booking Amount :</strong>
										{{ CustomHelper::getPrice($bankData['partial_amount'])}}
									</p>
								</td>
								<td>
							<?php }else{ ?>
								<td colspan="2">
							<?php }
							?>

						<tr>
							<td><p><strong>Total Amount :</strong> {{ CustomHelper::getPrice($bankData['total_amount'])}}</td>
							<td><p><strong>Payment Method :</strong> {{ $bankData['method']}}</p></td>
						</tr>
							<tr>
							<?php  
							if($bankData['order_type'] == 1){ ?>
							<td>
								<p><strong>Package Name :</strong> {{ $bankData['itemname'] }}</p>
							</td>
							<?php }else{
							$order_type_arr = config('custom.order_type');
							$order_type = !empty($order_type_arr[$bankData['order_type']])?$order_type_arr[$bankData['order_type']]:'';
							?>
							<td>
								<p><strong>Order Type :</strong> {{ $order_type }}</p>
							</td>
						<?php } ?>
							<td>
								<p><strong>Payment Status :</strong> 
									<?php if($bankData['status'] == 1){
										echo 'Confirmed';
									}elseif($bankData['status'] == 2){
										echo 'Failed';
									}else{
										echo 'Pending';
									} ?>
								</p>
							</td>
						</tr>
						<!-- <tr>
							<td>
								<p><strong>Payment Method :</strong> {{ $bankData['method']}}</p>
							</td>
							<td>
								<p><strong>Comment :</strong> {{ $bankData['comment'] }}</p>
							</td>
							<td>
								<p><strong>Order No :</strong> {{ $bankData['order_no'] }}</p>
							</td>
						</tr> -->
						<tr>	
							<td>
								<p><strong>Payer Name : </strong>{{ $bankData['payer_name'] }}</p>
							</td>
							<td>
								<p><strong>Payer Email : </strong>{{ $bankData['payer_email'] }}</p>
							</td>
						</tr>
						<tr>
							<td>
								<p><strong>Comment :</strong> {!!nl2br($bankData['comment']) !!}</p>
							</td>
							<!-- <td>
								<p><strong>Order No :</strong> {{ $bankData['order_no'] }}</p>
							</td> -->
						</tr>
						<?php /*<tr>
							<td colspan="2">
								<p><strong>Comment :</strong> {{ $bankData['comment'] }}</p>
							</td>
							<td>
								<p><strong>Bank Transfer ID :</strong> Test</p>
							</td>
						</tr> */ ?>
						<?php 
						$manager_email = '';
						if($bankData['order_type'] == 1){
							$package_listing = CustomHelper::getSeoModules('package_listing');
							$manager_email = $package_listing->admin_email??'';
						}if($bankData['order_type'] == 4){
							$cab = CustomHelper::getSeoModules('cab');
							$manager_email = $cab->admin_email??'';
						}

						if($bankData['order_type'] == 5){
							$hotel_listing = CustomHelper::getSeoModules('hotel_listing');
							$manager_email = $hotel_listing->admin_email??'';
						}if($bankData['order_type'] == 6){
							$activity_listing = CustomHelper::getSeoModules('activity_listing');
							$manager_email = $activity_listing->admin_email??'';
						}
						if($bankData['order_type'] == 8){
							$bike = CustomHelper::getSeoModules('rental');
							$manager_email = $bike->admin_email??'';
						} ?>
						
						<hr>
						<tr>
							<td colspan="2" style="padding-top: 15px;">
								<?php if($manager_email){ ?>
								<span class="" style="color: #a3650b;padding-bottom: 15px;">
									<strong>Note:</strong>
									Please Email your payment details to {{$manager_email}} after your payment
								</span>
							<?php } ?>
								<p style="font-size: 20px;">
								<strong>Bank Transfer Account Details:</strong> 
								{!! $bank_details !!}
								<!-- {{ $bankDetails}} -->
								</p>
							</td>
						</tr>
					</table>	
					</div>
				<div style="text-align: center;">
					<?php 
					if($bankData['status'] == 2){ ?>
						<a href="{{ url('pay_now/'.$bankData['order_no']) }}" class="btn" style="margin-top: 30px;">Retry payment</a>
					<?php } elseif($bankData['status'] == 0 && Auth::check()){ ?>
				<a href="{{route('user.mybooking')}}" class="btn" style="margin-top: 10px;">View orders</a>
				<?php } ?>  
					<a href="/" class="btn" style="margin-top: 30px;">continue</a>
				</div>
           </div>
       </div>
    </div>
</section>
	@slot('bottomBlock')
	<style type="text/css">
	.paymen-detail {
	    background: #fff;
	    padding: 15px;
	    border-radius: 7px;
	    box-shadow: 0 2px 5px 1px rgb(64 60 67 / 16%);
	    max-width: 60rem;
	    margin: 0 auto;
}
	</style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript">
		$('#root').addClass('white-header');
	</script>
	
	@endslot
@endcomponent