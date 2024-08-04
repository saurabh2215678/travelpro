@component(config('custom.theme').'.layouts.main')
@slot('title')
   {{ $meta_title ?? ''}}
@endslot    
@slot('meta_description')
   {{ $meta_description ?? ''}}
@endslot

@slot('meta_keywords')
   {{ $meta_keywords ?? ''}}
@endslot

@slot('headerBlock')

@endslot
@slot('bodyClass')
testimonials_class
@endslot
<?php
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY','BOOKING_QUERIES_NO']);

$storage = Storage::disk('public');

$booking_price = 0;
$is_partial_amount = 0;
$is_book_without_payment = 0;

if(isset($package) && !empty($package)) {
  $package_path = 'packages/';
  $package_image = $package->image;
  $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
  if(!empty($package_image)) {
     if($storage->exists($package_path.$package_image)) {
        $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package_image);
     }
  }  
  if($packagePrice) {
    $booking_price = $packagePrice->booking_price??0;
    $is_partial_amount = (int)$packagePrice->is_partial_amount??0;
    $is_book_without_payment = (int)$packagePrice->is_book_without_payment??0;
  }
  // prd($is_partial_amount);
}
$is_agent = auth()->user()?auth()->user()->is_agent:0 ;
$total_pax = 0;
$price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
if(!empty($price_category_choice_record_arr)) {
  foreach($price_category_choice_record_arr as $pccr) {
    $input_value = $pccr['input_value']??0;
    if($input_value > 0) {
      // $input_label = $pccr['input_label']??'';
      // $input_price = $pccr['input_price']??0;
      // $sub_total = $input_value*$input_price;
      $total_pax += $input_value;
    }
  }
}
$order_type = $order->order_type??1;
?>
<section class="paynow-template">
  <div class="container">
    <form method="POST" id="paynow_form" onSubmit="return validatePayNow();">
      {{ csrf_field() }}
    <div class="row">
      <div class="col-sm-12">
        <div class="title text-2xl">Review Booking Details</div>
        <div class="row">
          <div class="col-sm-7 w-3/5">
            <ul class="user-details pt-3">
            <li><strong>{{$order->name??''}}</strong></li>
            <?php if($order->order_type==4){ ?>
            <?php }else{ ?>
            <li>
            <strong>Address: </strong> <?php
              $address_arr = [];
              if($order->address1) {
                $address_arr[] = $order->address1;
              }
              if($order->address2) {
                $address_arr[] = $order->address2;
              }
              if($order->city) {
                $address_arr[] = $order->city;
              }
              if($order->state) {
                $address_arr[] = $order->state;
              }
              if($order->zip_code) {
                $address_arr[] = $order->zip_code;
              }
              if($order->country) {
                $country = $order->country ?? '';
               if(!empty($country) && is_numeric($country)) {
                $country = CustomHelper::GetCountry($country,'name');
              }
                $address_arr[] = $country;
              }
              if(!empty($address_arr)) {
                echo implode(', ', $address_arr);
              }
              ?>
            </li>
            <?php } ?>
            <li><strong>Mobile: </strong>{{(!empty($order->phone))?'+'.$order->country_code.'-'.$order->phone:''}}</li>
            <li><strong>Email: </strong>{{$order->email??''}}<li>
            </ul>
            <?php if($order->order_type==4){ ?>

            <?php }else{ ?>
            <div class="review_booking_inner">
              <div class="table_view">
                  <table class="table table-striped table-bordered text-sm">
                      <tbody>
                        <?php
                        $total_amount = $order->total_amount?? 0;
                        $discount = $order->discount?? 0;
                         if($order->order_type != 2){ ?>
                        <tr>
                          <td><strong>Description</strong></td>
                          <td><strong>Rate</strong></td>
                          <td><strong>Number of Person</strong></td>
                          <td><strong>Amount</strong></td>
                        </tr>

                        <?php
                        $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
                        if(!empty($price_category_choice_record_arr)) {
                          foreach($price_category_choice_record_arr as $pccr) {
                            $input_value = $pccr['input_value']??0;
                            if($input_value > 0) {
                              $input_label = $pccr['input_label']??'';
                              $input_price = $pccr['input_price']??0;
                              $sub_total = $input_value*$input_price;
                              ?>

                              <tr>
                                <td>{{$input_label}}</td>
                                <td>{{CustomHelper::getPrice($input_price)}}</td>
                                <td>{{$input_value}}</td>
                                <td>{{CustomHelper::getPrice($sub_total)}}</td>
                              </tr>
                              <?php
                            }
                          }
                        }
                      }
                      if($is_agent == 1){
                        ?>                        
                       <tr class="total_fee">
                          <td colspan="3"><strong>Discount</strong></td>
                          <td>{{CustomHelper::getPrice($discount)}}</td> 
                        </tr>
                        <?php } ?> 
                         <tr class="total_fee">
                          <td colspan="3"><strong>Total</strong></td>
                          <td>{{CustomHelper::getPrice($total_amount)}}</td> 
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>

            <?php  $payment_gateways_count =  0;
            if($payment_gateways && $payment_gateways->count() > 0){
            $payment_gateways_count = $payment_gateways->count(); ?>
            <div class="payment_sec">
              <div class="col-sm-12 payment_method">
                
                  <div class="form_group">
                  @if((!empty($booking_price) && $booking_price > 0) || (!empty($is_book_without_payment) && $is_book_without_payment == 1))
                    <div class="title text-lg mb-2">Payment Option</div>
                  @endif
                    @if(!empty($total_amount))
                    <input type="radio" class="hidden_radio " value="total_price" id="payment_total_amount" name="pay_type" checked>
                      @if((!empty($booking_price) && $booking_price > 0) || (!empty($is_book_without_payment) && $is_book_without_payment == 1))
                        <label for="payment_total_amount" class="radio_label"><span>Pay Full Amount</span> <i></i>&nbsp;&nbsp;&nbsp;{{CustomHelper::getPrice($total_amount)}}</label>
                      @endif
                    @endif
                    
                    @if((!empty($booking_price) && $booking_price > 0) || (!empty($is_book_without_payment) && $is_book_without_payment == 1))
        
                    @if(!empty($booking_price) && $booking_price > 0)
                      <input type="radio" class="hidden_radio " value="booking_price" id="payment_booking_price" name="pay_type">
                      <label for="payment_booking_price" class="radio_label"><span>Pay <strong>Booking Amount</strong></span> <i></i>&nbsp;&nbsp;&nbsp;{{CustomHelper::getPrice($booking_price*$total_pax)}}</label>
                    @endif
        
                    @if(!empty($is_partial_amount) && $is_partial_amount == 1)
                    <!-- HIDE AS INSTRUCTION OF SAMMI SIR -->
                      <!-- <input type="radio" class="hidden_radio " value="partial_amount" id="payment_partial_amount" name="pay_type">
                      <label for="payment_partial_amount" class="radio_label" style="flex-wrap: wrap;">
                        <div style="width:20%;">
                          <span>Pay <strong>Partial Amount</strong></span>
                        </div> <i></i>
                        <div id="textboxes" style="display: none">
                          <input type="text" name="partial_amount" id="partial_amount" placeholder="Partial amount" value="" class="form-control">
                        </div>
                        <p class="text-xs inner_line">Please add your convenience advanced payment for your booking.</p>
                      </label> -->
                    @endif
        
                    @if(!empty($is_book_without_payment) && $is_book_without_payment == 1)
                      <input type="radio" class="hidden_radio " value="book_without_payment" id="payment_is_book_without_payment" name="pay_type">
                      <label for="payment_is_book_without_payment" class="radio_label"><span>Book <strong>Without Payment</strong></span> <i></i></label>
                    @endif
        
                    @endif
                        
                  </div>
        
                  <div style="clear: both;"></div>
        
                <div class="form_group" id="payment_method_div">
                  <div class="title text-lg mb-2">Select a payment method</div>
                <!-- <label>Payment Option</label> -->
                <?php $i=1; 
        
                foreach ($payment_gateways as $key => $payment_gateway) {
                  
                  ?>
                <input type="radio" class="hidden_radio " value="{{$payment_gateway->value}}" id="payment{{$i}}" name="payment_method" checked>
                <label for="payment{{$i++}}" class="radio_label">{{$payment_gateway->display_name ? $payment_gateway->display_name : $payment_gateway->name }}</label>
                <?php }?>
        
                <!-- <input type="radio" class="hidden_radio " value="2" id="payment2" name="payment_option">
                <label for="payment2" class="radio_label">Bank Transfer</label> -->
        
        
              </div>
              <div class="clearfix">&nbsp;</div>
              </div>
              <div class="col-sm-12 pay-btn">
                <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                <input type="submit" id="pay_now_active" name="pay_now" class="btn theme_clr text-sm rounded-full inline-block cursor-pointer btnSubmit" value="Pay Now">
                <input type="button" id="pay_now_disabled" class="btn theme_clr text-sm rounded-full inline-block cursor-pointer" value="Please wait..." style="display:none" disabled>
              </div>
            </div>
            <?php } ?>



          </div>
          <div class="w-2/5">
            <?php if($order->order_type==4){ 
              if($order->booking_details) {
                $booking_details = (array)json_decode($order->booking_details);
              }
              ?>
              <div class="detail_card">
              <div class="title text-xl pt-3 pb-3 bkg_top">YOUR BOOKING DETAILS</div>
              <div class="bkg_btm">
              <div class="booking_details">
                <table>
                  <tr>
                    <th>Itinerary</th>
                    <td>:</td>
                    <td>{{$booking_details['itinerary']??''}}</td>
                  </tr>
                  <tr>
                    <th>Pickup Date</th>
                    <td>:</td>
                    <td>{{$booking_details['trip_date']??''}}</td>
                  </tr>
                  <tr>
                    <th>Car Type</th>
                    <td>:</td>
                    <td>{{$booking_details['cab_name']??''}}</td>
                  </tr>
                  <tr>
                    <th>Trip Type</th>
                    <td>:</td>
                   <td>{{$booking_details['trip_type']??''}}</td>
                  </tr>
                <tr>
                    <th>Total Fare</th>
                    <td>:</td>
                    <td>{{CustomHelper::getPrice($order->total_amount)}}</td>
                  </tr>
                </table>
              </div>
            <?php }else if(isset($package) && !empty($package)){ ?>
            <div class="title text-xl pt-3 pb-3">{{($order_type==6)?'Activity':'Package'}} Details</div>
            <div class="flex">
            <div class="theme_title2">
              <div class="images">
                <img src="{{$packageThumbSrc}}" alt="{{$package->title??''}}">
              </div>              
           </div>
           <div class="day_box">
           <ul>
            <li class="title mb-0 text-base">{{$package->title??''}}</li>
            <li class="day_night" style="width: 100%;"><span class="text-white text-xs mb-1 rounded" style="color:#01b3a7;">{{$package->package_duration??''}} </span></li>
            <li><span class="bg-orange-600 text-white text-sm p-1 rounded">{{$packagePrice->title??''}}</span></li>
           </ul>
           </div>
           </div>
           <div class="clearfix"></div>
           <div class="book_pack_other mt-4">
                <div class="more_info_list text-sm">
                  <label><strong>Trip Date:</strong></label>
                  <span>
                    <?php
                    $tripDateFormat = 'D, M dS Y';
                    if($order->order_type == 6) {
                      $tripDateFormat = 'D, M dS Y h:i A';
                    }
                    ?>
                    {{CustomHelper::DateFormat($order->trip_date, $tripDateFormat)??''}}
                  </span>
                </div>

                <?php
                $total_amount = $order->total_amount??0;
                // $discount_amount = $order->discount ?? 0;
                $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
                if(!empty($price_category_choice_record_arr)) {
                  foreach($price_category_choice_record_arr as $pccr) {
                    $input_value = $pccr['input_value']??0;
                    if($input_value > 0) {
                      $input_label = $pccr['input_label']??'';
                      $input_price = $pccr['input_price']??0;
                      $sub_total = $input_value*$input_price;
                      ?>
                      <div class="more_info_list text-sm">
                        <label><strong>{{$input_label}}:</strong></label>
                          <span>{{$input_value}}</span>
                      </div>
                      <?php
                    }
                  }
                }
               ?>                
            </div>
            <?php } ?>
            <?php if(isset($websiteSettingsArr['BOOKING_QUERIES_NO']) && $websiteSettingsArr['BOOKING_QUERIES_NO'] ) { ?>
           <div class="booking_problem mt-3">
            <p>Booking Queries? Call: <strong>
                {{$websiteSettingsArr['BOOKING_QUERIES_NO'] ?? ''}}
              </strong></p>
          </div>
          </div>
          </div>
        <?php } ?>
          </div>
        </div>
        <div class="payment-accpect row pt-7">
       <div class="title text-lg pb-3">We Accept</div>
       <div class="w-full cards">
                <div class="card-img">
                <span>Debit Card/Credit Card/Net Banking</span>
                <img src="{{asset(config('custom.assets').'/images/dcards.png')}}">
                <img src="{{asset(config('custom.assets').'/images/paypal-payment-method-logo.jpg')}}"><em style="position: relative;top: 12px;">And more</em>
            </div>
            </div>
    </div>
      </div>
      
    </div>
    
    </form>
    
  </div>
</section>

@slot('bottomBlock')
<span class="loader_spinner" style="display: none;">&nbsp;<i class="fa fa-spinner fa-spin"></i>Please wait....redirecting to Payment Gateway
</span>

<script src="https://www.google.com/recaptcha/api.js?render={{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}"></script>
<script type="text/javascript">  
grecaptcha.ready(function() {
   grecaptcha.execute("{{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}", {action:'validate_captcha'}).then(function(token) {
   // add token value to form
   const element = document.getElementById('g-recaptcha-response');
   if(element) {
    document.getElementById('g-recaptcha-response').value = token;
   }
   });
});

$('input[name="pay_type"]').on('click', function() {
  $('#payment_method_div').show();
  if ($(this).val() == 'partial_amount') {
    $('#textboxes').show();
  } else {
    if ($(this).val() == 'book_without_payment') {
      $('#payment_method_div').hide();
    }
    $('#textboxes').hide();
  }
});
$("#partial_amount").keypress(function (e) {
  if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
});

jQuery.support.cors = true;
/*if ($("#paynow_form").length > 0) {
   $("#paynow_form").validate({
      submitHandler: function(form) {
        //  $(".btnSubmit").html(
        //        'Please wait...'
        //        );
         $(".btnSubmit").val(
               'Please wait...'
               );
         $(".btnSubmit"). attr("disabled", true);
         form.submit();
      }
   })
}*/

function validatePayNow() {
  $('#pay_now_active').hide();
  $('#pay_now_disabled').show();
  return true;
}

$(document).ready(function(){
  var total_payment_gateway = <?php echo $payment_gateways_count; ?>;
  if(total_payment_gateway==1) {
    $('#pay_now_active').click();
    $('.loader_spinner').show();
  }
});

</script>

@endslot
@endcomponent