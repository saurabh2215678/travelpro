@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')

<style>
  .service-vaucher .form-group.row { display: flex; align-items: center; margin-bottom: 0; }
  .service-vaucher .form-group.row label.col-form-label { margin-bottom: 0; /* border: 1px solid #a7a7a7; */ height: 30px; box-shadow: none; padding: 6px 12px; display: block; }
  .service-vaucher .form-group.row .col-sm-10 {padding-left: 0;}
  .service-vaucher .form-group.row .form-control { border-radius: 0; border: 1px solid #a7a7a7; height: 35px; }
  .right-content.float-right {float: right;}
  .service-vaucher .form-group.row .form-group label.col-form-label {padding-left: 0;}
  .panel-body {padding: 0px;}
  .service-vaucher .panel-body button.btn.btn-success { position: absolute; right: 0; top: 30px; border-radius: 0; padding: 9px 20px; }
  .service-vaucher .panel-body button.btn.btn-danger { position: absolute; right: 0; top: 0px; border-radius: 0; padding: 9px 20px; }
  .service-vaucher .panel-body .removeclass1 button.btn.btn-danger {top: 30px;}
  .service-vaucher .panel-body .form-group {position: relative;}
  .service-vaucher .form-group.row .input-group {display: block;}
  .form-title {background: #e5e5e5;margin-bottom: 15px;}
  .form-title h4 {font-weight: 600;}
  .service-vaucher h3 { padding: 0; margin: 7px 10px 0; font-size: 1.2rem; font-weight:600; }
  .service-vaucher label {margin-left: 10px;}
  .modal-open .modal .modal-content .modal-header { padding: 5px 15px; background: #00b2a7; color: #fff; }
  .modal-open .modal .modal-content .modal-header button.close { color: #fff; opacity: 1; font-size: 32px; }
  ul.inline-right li {display: inline-block;}
  ul.inline-right li .inputbox span { padding-left: 1rem; padding-right: 1rem; }
  ul.inline-right li .inputbox input { position: absolute; left: 0; width: auto; }
  ul.inline-right li .inputbox { position: relative; display: flex; align-items: center; }

  .modal { position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); opacity: 0; visibility: hidden; transform: scale(1.1); transition: visibility 0s linear 0.25s, opacity 0.25s 0s, transform 0.25s; }
  .modal-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 0; width: 50rem; border-radius: 0.5rem; }
  .show-modal { opacity: 1; visibility: visible; transform: scale(1.0); transition: visibility 0s linear 0s, opacity 0.25s 0s, transform 0.25s; }
  .modal-content .modal-header { background: var(--theme-color); color: #fff; font-size: 1.5rem; padding: 0.5rem 1rem; }
  .modal-content .modal-header span.close-button, .modal-content .modal-header button.close {float: right;cursor: pointer;}
  .modal-content .modal-body, .modal-content .modal-footer {padding: 1rem;}
  .modal-content .modal-footer button.btn-default { color: #000 !important; border-radius: 5rem; padding: 0.5rem 1.5rem; margin-right: 0.5rem; }
  .modal-content .modal-footer button.btn-default:hover {color: var(--theme-color)!important;}
  .bgcolor.service-vaucher ul.inline-right a.btn.btn-warning {background: #c1621cad;border-color: #c1621cad;}
  .bgcolor.service-vaucher ul.inline-right a.btn.btn-warning:hover {background: #c1621cd6;color: #fff !important;}
  .modal-content .modal-footer {padding-top: 0;}
  #cancellationModal .modal-body input[type="radio"] { float: left; width: auto; margin-top: 0.5rem; margin-right: 0.5rem; }
  @media (max-width: 767px){
    .bgcolor.service-vaucher form {padding: 25px 20px !important;}
    .w-medium-model {overflow: auto;}
    ul.inline-right li .inputbox span {padding-left: 1.5rem;}
  }
</style>
@endslot
<?php
$login_user_id = auth()->user() ? auth()->user()->id :0;
?>
<div class="centersec">
  <div class="bgcolor service-vaucher">
    @include('snippets.front.flash')
    <div class="md:container">
      <div class="row">
        <div class="form-title flex mt-5">
          <div class="w-1/2 col-form-label text-xl font-weight-bold p-3"><h4>Flight Details</h4></div>
          <div class="w-1/2 font-weight-bold text-right">
            <ul class="inline-right p-1">
              <li>
<?php /*if($pendingOrderAmendments){ ?>
<a href="javascript:void(0);" title="Cancel Selected Passenger" class="btn btn-warning">Your amendment in progress.</a>
<?php }else{ ?>
<a href="javascript:void(0);" title="Cancel Selected Passenger" class="btn btn-success" id="cancel_btn">Cancel Selected Passenger</a>
<?php }*/ ?>
<?php if($order->payment_status == 1 && $order->order_type == 3 && $order->status != 'Cancelled' && $order->supplier_id == $login_user_id) { ?>
  <a href="javascript:void(0);" class="btn btn-success" id="offline_order_cancel_btn" data-order_id="{{$order_id}}">Cancel</a>
<?php } ?>

</li>
</ul>
<div class="order_cancel_form" style="display:none">
  <form action="" class="form-inline" method="post" id="cancelForm">
    {{ csrf_field() }}
    <div class="modal-title alert_msg"></div>
    <input type="hidden" id="refund_order_id" name="order_id" value="">
    <div class="modal-body">
      <label class="required"><strong>Reason for Cancellation:</strong></label><br>
      <textarea name="reason" id="reason" class="form-control" style="width: 100%;" ></textarea>
    </div>
    <div class="text-center">
      <button type="submit" class="btn s-btn btn-info sky_blue rounded-full submit_cancel">Submit</button>
    </div>
  </form>
</div>
</div>


</div>

<div class="clearfix"></div>
<h3>Traveller Contact Details</h3>

<div class="form-group flex">
  <div class="col-form-label px-3"><strong>Name:</strong> <span class="text-sm">{{$name}}</span> | <strong>Phone:</strong> <span class="text-sm"><a href="tel:{{$phone}}">{{$phone}}</a></span> | <strong>Email:</strong> <span class="text-sm"><a href="mailto:{{$email}}">{{$email}}</a></span></div>
</div>
<form style="padding:25px 120px;padding-top: 0;" method="POST">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      {!!$itineraries!!}
    </div>
  </div>
</form>
</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="pleaseNoteModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <span class="close-button">×</span>
        <h4 class="modal-title">Please Note</h4>
      </div>
      <div class="modal-body">
        <p>Cancellation will occur in the next 4 working hours.</p>
        <p>Working hours are from 8 A.M. to 10 P.M everyday.</p>
        <p>If the flight departs in the next 12 hours you are requested to cancel with the airline directly to prevent NO SHOWS.</p>
        <p>Are you sure you want to cancel?</p>
      </div>
      <div class="modal-footer flex pt-0">
        <button type="button" class="btn btn-default btn_admin2" id="cancel_modal_btn">Cancel</button>
        <button type="button" class="btn btn-default btn_admin" id="ok_modal_btn">Ok</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cancellationModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancellation</h4>
      </div>
      <div class="modal-body">
        <div class="ajax_cancellation_msg"></div>
        <?php
        $flight_cancellation_reasons = config('custom.flight_cancellation_reasons');
        if($flight_cancellation_reasons) {
          foreach($flight_cancellation_reasons as $key => $val) {
            ?>
            <p><input type="radio" name="reason" value="{{$key}}"> {{CustomHelper::showFlightCancellationReason($key)}}</p>
            <?php
          }
        }
        ?>
        <div id="other_reason_section" style="display: none;">
          <p>Kindly Note:- Please provide us the right option in order to enable us to Process the refund at the earliest.</p>
          <label>Comments:</label>
          <input type="text" name="other_reason" id="other_reason">
        </div>
        <span class="reason_error validation_error"></span>
      </div>
      <div class="modal-footer flex pt-0">
        <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Discard</button>
        <button type="button" class="btn btn-default btn_admin" id="cancel_done">Done</button>
      </div>
    </div>

  </div>
</div>

</div>
</div>
@slot('bottomBlock')
<script type="text/javascript">
  var modal = document.querySelector("#pleaseNoteModal");
  var CancellationModal = document.querySelector("#cancellationModal");

  var closeButton = document.querySelector(".close-button");
  var CancellationCloseButton = document.querySelector("#cancellationModal .close");

  $(document).on("click","#cancel_btn",function(){
    $('.ajax_cancellation_msg').html('');
    $('.validation_error').html('');

    var selected_passengers = validateCancellation();
    if(selected_passengers && selected_passengers.length > 0) {
//alert('hi');
modal.classList.add("show-modal");
} else {
  alert('Please select passengers to cancel the ticketss.')
}
});

  function validateCancellation() {
    var sector = '';
    var fN = '';
    var lN = '';
    var selected_passengers = [];
    $('.cancel_passengers').each(function(){
      if($(this).is(":checked")) {
        sector = $(this).attr('data-sector');
        fN = $(this).attr('data-fN');
        lN = $(this).attr('data-lN');
        selected_passengers.push({'sector':sector,'fN':fN,'lN':lN});
      }
    });
    return selected_passengers;
  }

  $(document).on("click","#cancellation_btn",function(){
    $("#pleaseNoteModal").modal('hide');
    $("#cancellationModal").modal('show');
  });

  $(document).on('change','input[name=reason]',function(){
    var reason = $(this).val();
    if(reason == 'other') {
      $('#other_reason_section').show();
    } else {
      $('#other_reason_section').hide();
      $('#other_reason').val('');
    }
  });

  $(document).on("click","#cancel_done",function(){
    $('.ajax_cancellation_msg').html('');
    $('.validation_error').html('');
    var selected_passengers = validateCancellation();
    if(selected_passengers && selected_passengers.length > 0) {
      var _token = '{{ csrf_token() }}';
      var reason = $('input[name=reason]:checked').val();
      var other_reason = $('#other_reason').val();
      if(reason) {
        if(reason == 'other' && other_reason == '') {
          $('.ajax_cancellation_msg').html('<span class="validation_error">Other reason for cancellation is required.</span>');
          return false;
        }
      } else {
        $('.ajax_cancellation_msg').html('<span class="validation_error">Reason for cancellation is required.</span>');
        return false;
      }
      $.ajax({
        url: "{{ route('user.cancel_flight',$order_id) }}",
        type: "POST",
        data: {'passengers':selected_passengers,'reason':reason,'other_reason':other_reason},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        beforeSend: function () {},
        success: function(resp){
          if(resp.success) {
            alert(resp.message);
            window.location.reload();
          } else if(resp.message) {
            $('.ajax_cancellation_msg').html('<span class="validation_error">'+resp.message+'</span>');
          }
        },error: function(e) {
          var response = e.responseJSON;
          if(response) {
            $.each(response.errors, function (key, val) {
              $(document).find("." + key + "_error").html(val[0]);
            });
            if(response.message) {
              $('.ajax_cancellation_msg').html('<span class="validation_error">'+response.message+'</span>');
            }
          }
        }
      });
    } else {
      $('.ajax_cancellation_msg').html('<span class="validation_error">Please select passengers to cancel the ticket.</span>');
    }
  });




  function toggleModal() {
    modal.classList.toggle("show-modal");
  }

  function toggleCancellationModal() {
    CancellationModal.classList.toggle("show-modal");
  }

  function hideModal() {
    modal.classList.remove("show-modal");
  }

  function hideCancellationModal() {
    CancellationModal.classList.remove("show-modal");
  }

  function windowOnClick(event) {
    if (event.target === modal) {
      hideModal();
    }

    if (event.target === CancellationModal) {
      hideCancellationModal();
    }
  }



  closeButton.addEventListener("click", toggleModal);
  CancellationCloseButton.addEventListener("click", toggleCancellationModal);
  window.addEventListener("click", windowOnClick);

  $('#cancel_modal_btn').click(function(){
    modal.classList.remove("show-modal");
  });

  $('.btn_admin2').click(function(){
    CancellationModal.classList.remove("show-modal");
  });

  $('#ok_modal_btn').click(function(){
    modal.classList.remove("show-modal");
    CancellationModal.classList.add("show-modal");
  });

  $(document).on("click","#offline_order_cancel_btn",function(){
    var order_id = $(this).attr('data-order_id');
    $('#refund_order_id').val(order_id);
// $(".order_status_form").hide();
if ($(".order_cancel_form").is(":hidden")) {
  $(".order_cancel_form").show();
} else {
  $(".order_cancel_form").hide();
}
});

  $(document).on("click", ".submit_cancel", function() {
    var order_id = $("#refund_order_id").val();
    var reason = $("#reason").val();
    if (!reason) {
      alert("Please add reason for order cancellation.");
      $("#reason").focus();
      return false;
    }else{
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ url('user/cancelOfflineFlight') }}",
        type: "POST",
        data: {
          order_id: order_id,
          reason: reason
        },
        dataType: "JSON",
        headers: {
          'X-CSRF-TOKEN': _token
        },
        cache: false,
        beforeSend: function() {
          $(".alert_msg").show();
          $(".submit_cancel").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
          $(".submit_cancel").attr("disabled", true);
        },
        success: function(response) {
          $(".submit_cancel").html('Submit');
          $(".submit_cancel").attr("disabled", false);
          $(".alert_msg").html(response.msg).hide();
          $(".alert_msg").html(response.msg).fadeIn();
          $("#offline_order_cancel_btn").hide();
          setTimeout(function() {
            $(".order_cancel_form").fadeOut();
            window.location.href = "{{route('user.myFlightBooking',['order_type'=>'bookings'])}}";
          }, 3000);
        }
      });
    }
  });

</script>
@endslot
@endcomponent