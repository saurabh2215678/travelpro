@component(config('custom.theme').'.layouts.main')
@slot('title')
{{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
@endslot
@slot('bodyClass')
home_class
@endslot
<?php $storage = Storage::disk('public'); ?>
<?php
$transactionInfo = "";
if(!empty($paymentData) && !empty($paymentData['payment_description'])){
   $transactionInfo = json_decode($paymentData['payment_description'],true);
}
// prd($paymentData);
?>
<?php
if($paymentData['status'] == 1) {
   $status_title = 'Successful!'; //Confirmed
   $status = 'SUCCESS'; //Confirmed
} elseif($paymentData['status'] == 2) {
   $status_title = 'Failed!'; //Confirmed
   $status = 'Failed';
} elseif($paymentData['status'] == 3) {
   $status_title = 'Refunded!'; //Confirmed
   $status = 'Refunded';
} else {
   $status_title = 'Pending!'; //Confirmed
   $status = 'Pending!';
}
?>
<div class="breadcrumb_full">
   <div class="container">
      <ul class="breadcrumb">
         <li><a href="{{route('homePage')}}">Home</a>
         </li>
         <li><a href="javascript:void(0)">Bank Details </a>
         </li>
      </ul>
   </div>
</div>
<section class="pt-2">
   <div class="container">
      <div class="<?php if(isset($itineraries) && isset($paymentData['status']) && $paymentData['status'] == 1){?>flight-payment-detail<?php }else{ ?>payment-detail<?php } ?>">
         <div class="row">
            <div class="paymentinfo" style="width: 100%; text-align: left;padding-top: 12px;">
            <?php if($paymentData['status'] == 1){ ?>
               <h1 style="color: #464646;font-weight: 600;font-size: 25px;padding-bottom: 5px;">Thank You</h1>
               {{$paymentData['email']??''}} <br />
               <span style="width: 100%; text-align: left;color: green;font-size: 20px;padding: 10px 0;"><i class="fa fa-check" style="font-size: 15px;border-radius: 50px;border: 1px solid;padding: 2px;"></i> Payment {{$status_title}}</span>
            <?php }elseif($paymentData['status'] == 2){ ?>
               <h1 style="color: #464646;font-weight: 600;font-size: 25px;padding-bottom: 5px;">Sorry</h1>
               {{$paymentData['email']??''}} <br />
               <span style="width: 100%; text-align: left;color: #f00;font-size: 20px;padding: 10px 0;"><i class="fa fa-exclamation-circle" style="font-size: 25px;"></i> Payment {{$status_title}}</span>
            <?php }elseif($paymentData['status'] == 3){ ?>
               <h1 style="color: #464646;font-weight: 600;font-size: 25px;padding-bottom: 5px;">Sorry</h1>
               {{$paymentData['email']??''}} <br />
               <span style="width: 100%; text-align: left;color: #f00;font-size: 20px;padding: 10px 0;"><i class="fa fa-exclamation-circle" style="font-size: 25px;"></i> Payment {{$status_title}}</span>
            <?php }else{ ?>
               <h1 style="color: #464646;font-weight: 600;font-size: 25px;padding-bottom: 5px;">Sorry</h1>
               {{$paymentData['email']??''}} <br />
               <span style="width: 100%; text-align: left;color: orange;font-size: 20px;padding: 10px 0;"><i class="fa fa-clock-o" style="color: orange;font-size: 25px;"></i> Payment {{$status_title}}</span>
            <?php } ?>
            </div>

            <div class="payment_box">
               <?php if($paymentData['status'] == 2 || $paymentData['status'] == 3){ ?>
                  <hr>
                  <p class="py-2">Don't Worry Your Money is safe! if money is debited from your account, It will be refunded automatically in 5-7 working days.</p>
                  <hr>
               <?php } ?>

               <table class="table table-borderless">
                  <tr>
                     <td>
                           <?php /*<strong>Transaction Id :</strong> {{ $paymentData['txn_id'] }}*/ ?>
                           <strong>Order ID :</strong> {{$paymentData['order_no']??''}}
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <?php $order_date = (isset($paymentData['order_date']) && !empty($paymentData['order_date']))?CustomHelper::DateFormat($paymentData['order_date'], 'F d, Y \a\t h:i A'):NULL; ?>
                        <strong>Time :</strong> {{ $order_date }}
                     </td>
                  </tr>

                  <?php /*if($paymentData['pay_type'] == 'partial_amount'){ ?>
                  <tr>
                     <td>
                           <strong>Partial Amount :</strong>
                           {{ CustomHelper::getPrice($paymentData['partial_amount'])}}
                     </td>
                  </tr>
                  <?php }elseif($paymentData['pay_type'] == 'booking_price'){ ?>
                  <tr>
                     <td>
                           <strong>Booking Amount :</strong>
                           {{ CustomHelper::getPrice($paymentData['partial_amount'])}}
                     </td>
                  </tr>
                  <?php }*/ ?>

                  <tr>
                     <td>
                        <strong>Transaction Amount :</strong>
                        <?php if($paymentData['pay_type'] == 'partial_amount' || $paymentData['pay_type'] == 'booking_price'){ ?>
                           {{CustomHelper::getPrice($paymentData['partial_amount'])}}
                        <?php } else if($paymentData['status'] == 1 && Auth::check() && $paymentData['order_type'] == 7) { ?> 
                           {{CustomHelper::getPrice($paymentData['sub_total_amount'])}}
                        <?php } else {  ?>
                           {{CustomHelper::getPrice($paymentData['total_amount'])}}
                        <?php } ?>
                     </td>
                  </tr>

                  <tr>
                     <td>
                           <strong>Transaction Status :</strong>
                           {{$status}}
                     </td>
                  </tr>
                  
               </table>

                <?php if($paymentData['status'] == 2 || $paymentData['status'] == 3){ ?>
			   <div class="footer-notice">
               <hr>
				<h5 class="text-sm font-semibold pt-3">Why did it fail?</h5>
				<p class="pt-0 py-2">Thank you for shopping with us. We will Keep you posted regarding the status of your order through email</p>
            <hr>
			   </div>
            <?php } ?>
   			   <!-- <?php /*<table class="">
                  <tr>
                     <td >
                        <p>
                           <strong>Transaction Id :</strong> {{ $paymentData['txn_id'] }}
                        </p>
                     </td>
                     <td>
                        <?php   $order_date = (isset($paymentData['order_date']) && !empty($paymentData['order_date']))?CustomHelper::DateFormat($paymentData['order_date'], 'Y-m-d h:i A'):NULL; ?>
                        <p><strong>Date :</strong> {{ $order_date }}</p>
                     </td>
                  </tr>
                  <tr>
                     <?php  
                        if($paymentData['pay_type'] == 'partial_amount'){ ?>
                     <td>
                        <p>
                           <strong>Partial Amount :</strong>
                           {{ CustomHelper::getPrice($paymentData['partial_amount'])}}
                        </p>
                     </td>
                     <td>
                        <?php 	}elseif($paymentData['pay_type'] == 'booking_price'){ ?>
                     <td>
                        <p>
                           <strong>Booking Amount :</strong>
                           {{ CustomHelper::getPrice($paymentData['partial_amount'])}}
                        </p>
                     </td>
                     <td>
                        <?php }else{ ?>
                     <td colspan="2">
                        <?php }
                           ?>
                        <p><strong>Total Amount :</strong> {{CustomHelper::getPrice($paymentData['total_amount'])}}</p>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <p><strong>Order No :</strong> {{ $paymentData['order_no'] }}</p>
                     </td>
                     <td>
                        <?php if($paymentData['order_type'] == 1){ ?>
                        <p><strong>Package Name :</strong> {{$paymentData['itemname'] }}</p>
                        <?php }else if($paymentData['order_type'] == 2) {
                           $order_type_arr = config('custom.order_type');
                           $order_type = !empty($order_type_arr[$paymentData['order_type']])?$order_type_arr[$paymentData['order_type']]:'';
                                             ?>
                        <p><strong>Order Type :</strong> {{ $order_type }}</p>
                        <?php } ?>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <p><strong>Payment Method :</strong>
                           {{ $paymentData['method']}}
                        </p>
                     </td>
                     <td>
                        <p><strong>Payment Status :</strong> 
                           {{$status}}
                        </p>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <p><strong>Payer Name :</strong> {{ $paymentData['name'] }}</p>
                     </td>
                     <td>
                        <p><strong>Payer Email :</strong>{{ $paymentData['payer_email'] }}</p>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <p><strong>Payment Account Payer Name :</strong>{{ $paymentData['payer_name'] }}</p>
                     </td>
                     <td>
                        <p><strong>Comment :</strong> {{ $paymentData['comment'] }}</p>
                     </td>
                  </tr>
                  <!-- <tr>
                     <td>
                        <p><strong>Email :</strong> {{ $paymentData['email'] }}</p>
                        </td>
                  </tr> -->
                  <!-- <tr>
                     <td>
                     	<p><strong>Paypal ID :</strong> Test</p>
                     </td>
                     <td>
                     </td>
                     </tr> -->
               </table>*/?> -->
               <?php if($paymentData['status'] == 1){ ?>
                  <?php if(isset($flight_status) && !empty($flight_status) && $flight_status != 'SUCCESS'){ ?>
                     <p style="padding-left: 15px;"><strong>Booking Status:</strong> {{$flight_status}} (Please check the updated status in your "My Bookings" section)</p>
                  <?php } ?>
                  <p>
                     <span class="info1" style="text-align: center; font-size: 13px;">Your order has been received and is now being processed. 
                     As soon as we have processed your order, we will send you an email.</span>
                  </p> 
               <?php } ?>

               @if(isset($itineraries) && isset($paymentData['status']) && $paymentData['status'] == 1 && isset($flight_status) && !empty($flight_status) && $flight_status == 'SUCCESS')
               <div class="flightemail_title">Flight Booking Details</div>
               {!!$itineraries!!}
               @endif
            </div>
            <div style="width:100%; text-align: left;">
               <?php if($paymentData['status'] == 1){ ?>
                  <?php if(Auth::check()) { ?>
                     <?php if($paymentData['order_type'] == 7){ ?>
                        <a href="{{route('user.myWallet')}}" class="btn" style="margin-top: 10px;">Go to My Wallet</a>
                     <?php } else { ?>
                        <a href="{{route('user.mybooking')}}" class="btn" style="margin-top: 10px;">Go to My Bookings</a>
                     <?php } ?>
                  <?php } ?>
               <?php } else if($paymentData['status'] == 3) { ?>
                  <!-- Do nothing for refund -->
               <?php } else { ?>
                  <a href="{{ url('pay_now/'.$paymentData['order_no']) }}" class="btn" style="margin-top: 10px;">Retry payment</a>
               <?php } ?>

               <a href="{{route('homePage')}}" class="btn" style="margin-top: 10px;">Go to Home</a>
            </div>
         </div>
      </div>
   </div>
</section>
@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
   $('#root').addClass('white-header');
</script>
@endslot
@endcomponent