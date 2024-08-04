@component(config('custom.theme').'.layouts.main')
    @slot('title')
        {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
    @slot('headerBlock')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"/>
    <link rel="stylesheet" href="{{asset(config('custom.assets').'/css/media.css') }}">
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
        	<h1 style="width: 100%; text-align: center; background: #2c2d6c;color: #fff;">
        		<?php if($bankData['status'] == 1){
        			echo '<i class="fa fa-check" style="color: #008000;"></i>';
        		}elseif($bankData['status'] == 2){
        			echo '<i class="fa fa-times" style="color: #f00;"></i> ';
        		}?>
        		 Bank Transfer Details</h1>
        	<div class="payment_box">
	        	<table>

	        		<tr>
	        		
	        			<td>
	        				<p><strong>Order No :</strong> {{ $bankData['order_no'] }}</p>
	        			</td>
	        			<td>
	        				<?php   $order_date = (isset($bankData['order_date']) && !empty($bankData['order_date']))?CustomHelper::DateFormat($bankData['order_date'], 'Y-m-d h:i A'):NULL; ?>
	        				<p><strong>Date :</strong> {{ $order_date }}</p>
	        			</td>
	        		</tr>
	        		<tr>
	        					
	        					<?php  
	        					if($bankData['pay_type'] == 'partial_amount'){ ?>
	        						<td>
	        							<p>
	        								<strong>Partial Amount :</strong>
	        								{{ CustomHelper::getPrice($bankData['partial_amount'])}}
	        							</p>
	        						</td>
	        						<td>
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
	        				<p><strong>Total Amount :</strong> {{ CustomHelper::getPrice($bankData['total_amount'])}}</p>
	        			</td>
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
	        		<tr>
	        			<td>
	        				<p><strong>Payment Method :</strong> {{ $bankData['method']}}</p>
	        			</td>
	        			<td>
	        				<p><strong>Comment :</strong> {!!nl2br($bankData['comment']) !!}</p>
	        			</td>
	        			<!-- <td>
	        				<p><strong>Order No :</strong> {{ $bankData['order_no'] }}</p>
	        			</td> -->
	        		</tr>
	        		<tr>	
	        			<td>
	        				<p><strong>Payment Account Payer Name :</strong>{{ $bankData['payer_name'] }}</p>
	        			</td>
	        			<td>
	        				<p><strong>Payment Account Payer Email :</strong>{{ $bankData['payer_email'] }}</p>
	        			</td>
	        		</tr>
	        		<?php /*<tr>
	        			<td colspan="2">
	        				<p><strong>Comment :</strong> {{ $bankData['comment'] }}</p>
	        			</td>
	        			<td>
	        				<p><strong>Bank Transfer ID :</strong> Test</p>
	        			</td>
	        		</tr> */ ?>
	        		<tr>
	        			<td colspan="2">
	        				<p>
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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/gsap.min.js"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/ScrollTrigger.min.js?v=3.4.0.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/locomotive-scroll@3.5.4/dist/locomotive-scroll.min.js"></script>
	<script type="text/javascript">
		$('#root').addClass('white-header');
	</script>
	@endslot
@endcomponent