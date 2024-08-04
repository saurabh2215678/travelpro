<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Payment</title>
  <link rel="preload" as="image" imagesrcset="{{$logoSrc}}">
</head>
<body>
  <section class="payment" id="app">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="tdiv">
            <div class="tlogo">
              <img srcset="{{$logoSrc}}" class="img-fluid" alt="">
            </div>
            <div class="th-sec">
              <?php if($message = Session::get('error')){ ?>
              <h1 class="err">Payment Failed!</h1>
              <p><strong>Error!</strong> {{ $message }}</p>
              <a href="{{url('/')}}" class="site-btn mt-4">Go Home</a>
              <?php } ?>

              <?php if($message = Session::get('success')){ ?>
              <h1 class="suss">Payment Successful!</h1>
              <p>{{ $message }}</p>
              <a href="{{url('/')}}" class="site-btn mt-4">Go Home</a>
              <?php } ?>

              <?php if($is_already_paid == false) { ?>
              <div class="paymentLoad">
               <span class="loader"></span>
                <!-- <img src="{{asset('assets/img/loader.gif')}}" class="img-fluid" alt=""> -->
              </div>
              <p id="alert" style="display: none;margin-top:15px;">Please wait while we process your payment.<br> Do not refresh the page while this is happening.<br> Thank you for your patience.</p>
              <form action="" method="GET" >
                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
              </form>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" ></script>
<script type="text/javascript">
  var options = {
    "key": "{{$RAZORPAY_KEY}}",
    "amount": "{{$razorpay_amount}}",
    "buttontext": "Pay now",
    "name": "{{$SYSTEM_TITLE}}",
    "description": "{{$description}}",
    "image": "{{$logoSrc}}",
    "order_id": "{{$pg_order_id}}",
    "prefill": {
      "name": "{{$name}}",
      "email": "{{$email}}",
      "contact": "{{$phone}}",
      "method": "{{$method}}"
    },
    "theme":{
      "color": "#ff7529",
    },
    "handler": function (response) {
      $('#alert').show();
      handleResponse(response);
    },
    "modal":{
      "confirm_close": true,
      "ondismiss": function() {
        location.href = "{{url('payment/failed')}}"
      }
    },
  };
  var rzp1 = new Razorpay(options);

  rzp1.open();

  function handleResponse(response){
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ route('response_razorpay',[$order_no]) }}",
      type: "GET",
      data: response,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      beforeSend:function() {

      },
      success: function(response) {
        if(response.success) {
          if(response.redirect_url) {
            window.location.href = response.redirect_url;
          } else {
            window.location.href = "{{url('payment/thankyou')}}";
          }
        } else {
          window.location.href = "{{url('payment/failed')}}";
        }
      }
    });
  }
</script>
<style>
.tdiv {
    text-align: center;
    margin-top: 60px;
}
.tlogo {
    position: relative;
    z-index: 9;
}
.tdiv p#alert {
    font-family: sans-serif;
    font-size: 17px;
    line-height: 25px;
    color: #fff;
    position: relative;
    z-index: 99;
}
.paymentLoad {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    background: #00000070;
    right: 0;
    display: flex;
    justify-content: center;
    align-content: center;
    align-items: center;
}
.paymentLoad img {
    width: 35px;
    height: 35px;
}
.loader {
    width: 48px;
    height: 48px;
    display: inline-block;
    position: relative;
    border: 3px solid;
    border-color:#de3500 #0000 #fff #0000;
    border-radius: 50%;
    box-sizing: border-box;
    animation: 1s rotate linear infinite;
 }
 .loader:before , .loader:after{
    content: '';
    top: 0;
    left: 0;
    position: absolute;
    border: 10px solid transparent;
    border-bottom-color:#fff;
    transform: translate(-10px, 19px) rotate(-35deg);
  }
 .loader:after {
    border-color: #de3500 #0000 #0000 #0000 ;
    transform: translate(32px, 3px) rotate(-35deg);
  }
   @keyframes rotate {
    100%{    transform: rotate(360deg)}
  }
</style>