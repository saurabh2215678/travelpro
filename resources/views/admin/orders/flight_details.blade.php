@component('admin.layouts.main')
@slot('title')
Admin - Cancel Flight Ticket - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot
<?php 
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$BackUrl = CustomHelper::BackUrl();
$order_id = (isset($order->id))?$order->id:0;
$adult_price = 0;
$child_price = 0;
$infant_price = 0;

$supplier_id = (isset($order->supplier_id))?$order->supplier_id:'';
$supplier_id = old('supplier_id',$supplier_id);
$supplier_name = '';
if($supplier_id) {
    $userInfo = CustomHelper::getUserInfo($supplier_id);
    if($userInfo) {
        $company_brand = $userInfo['company_brand']??'';
        $company_name = $userInfo['company_name']??'';
        $company_owner_name = $userInfo['company_owner_name']??'';
        $supplier_name = $company_brand.' / '.$company_name.' ('.$company_owner_name.')';
    }
}

$pnrDetails = '';
$airline_ticket_no = '';
$OrderTraveller = $order->OrderTraveller??[];
if($OrderTraveller && isset($OrderTraveller[0])) {
  $pnrDetails = $OrderTraveller[0]->pnrDetails??'';
  $airline_ticket_no = $OrderTraveller[0]->airline_ticket_no??'';
}

$booking_details = json_decode($order->booking_details, true);
$totalPriceLists = $booking_details['itemInfos']['AIR']['tripInfos'][0]['totalPriceList']??[];
if($totalPriceLists) {
  foreach($totalPriceLists as $totalPriceList) {
    if(isset($totalPriceList['fd'])) {
      foreach($totalPriceList['fd'] as $pt => $val) {
        $NF = $val['fC']['NF']??0;
        $BF = $val['fC']['BF']??0;
        $TAF = $val['fC']['TAF']??0;
        $TF = $val['fC']['TF']??0;
        if($pt == 'ADULT') {
          $adult_price += $BF;
        } else if($pt == 'CHILD') {
          $child_price += $BF;
        } else if($pt == 'INFANT') {
          $infant_price += $BF;
        }
      }
    }
  }
}
?>
<style>
  .service-vaucher .form-group.row {display: flex;align-items: center; margin-bottom: 0; }
  .service-vaucher .form-group.row label.col-form-label {margin-bottom: 0; /* border: 1px solid #a7a7a7; */ height: 30px; box-shadow: none;
    padding: 6px 12px;}
  .service-vaucher .form-group.row .col-sm-10 {padding-left: 0;}
  .service-vaucher .form-group.row .form-control {border-radius: 0;border: 1px solid #a7a7a7; height: 35px;}
  .right-content.float-right {float: right;}
  .service-vaucher .form-group.row .form-group label.col-form-label {padding-left: 0;}
  .panel-body {padding: 0px; }
  .service-vaucher .panel-body button.btn.btn-success {position: absolute; right: 0; top: 30px;border-radius: 0; padding: 9px 20px;}
  .service-vaucher .panel-body button.btn.btn-danger { position: absolute; right: 0; top: 0px; border-radius: 0; padding: 9px 20px;}
  .service-vaucher .panel-body .removeclass1 button.btn.btn-danger {top: 30px;}
  .service-vaucher .panel-body .form-group {position: relative; }
  .service-vaucher .form-group.row .input-group {display: block;}
  .form-title {background: #e5e5e5;margin: 0px 0 15px; padding: 0 10px;}
  .form-title h4 { font-weight: 600;}
  .service-vaucher h3 {padding: 0; margin: 0;font-size: 17px;}
  .modal-open .modal .modal-content .modal-header {padding: 5px 15px; background: #00b2a7; color: #fff;}
  .modal-open .modal .modal-content .modal-header button.close {color: #fff; opacity: 1; font-size: 32px;}
  #order_confirm_form .form-inline .form-group .select2-container { width: 100% !important;}
  #order_confirm_form .form-inline .form-group input, #order_confirm_form .form-inline .form-group, #order_change_fare_form .form-inline .form-group input, #order_change_fare_form .form-inline .form-group { width: 100%;}
  .mt-14 {margin-top: 14px;}
  .btn.active {background-color: #07625d;}
</style>

<div class="centersec">
  <div class="bgcolor flight_service-vaucher service-vaucher">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="ajax_msg"></div>
    <div class="flight_inner_view">
      <div class="form-title">
        <div class="toplist">
          <div class="titleid col-form-label font-weight-bold"><h4>Order Details (Order ID: #{{$order->order_no??''}})</h4></div>
          <?php if($order->bookingId){ ?>
            <div class="titleid font-weight-bold">
              <div class="text-right inl_btn" style="margin-top: 10px;">
                @if($order->inventory_id && $order->status != 'Cancelled')
                <a href="javascript:void(0);" title="Cancel" class="btn btn_admin2" id="order_cancel_btn" data-order_id="{{$order_id}}"> Cancel </a>&nbsp;&nbsp;
                @endif
                @if($order->inventory_id && $order->status == 'NEW' && empty($order->supplier_id))
                <a href="javascript:void(0);" title="Change Fare" class="btn btn_admin2" id="order_change_fare_btn" data-order_id="{{$order_id}}"> Change Fare </a>&nbsp;&nbsp;
                <a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory_confirm',[$order->id,'back_url'=>$BackUrl])}}" title="Change Flight" class="btn btn_admin2" onClick="return confirm('Are you sure to continue?')" > Change Flight </a>&nbsp;&nbsp;
                <a href="javascript:void(0);" title="Confirm" class="btn btn_admin2" id="order_confirm_btn" data-order_id="{{$order_id}}" > Confirm </a>

                @else
                <a href="javascript:;" title="Flight Voucher Mail" class="btn sent_email btn_admin2" id="cust_btn"> Send Mail </a>
                <form class="" method="POST">
                  {{ csrf_field() }}
                  <input type="hidden" name="action" value="download_pdf">
                  <button type="submit" name="submit" class="btn btn-success">Download PDF</button>
                </form>
                @endif
              </div>
            </div>

            <div id="order_cancel_form" style="display:none">
              <form action="" class="form-inline" method="post" id="orderCancelForm" onSubmit="return validateCancelForm();">
                {{ csrf_field() }}
                <div class="modal-title alert_msg"></div>
                <input type="hidden" id="order_cancel_order_id" name="order_id" value="">
                <div class="modal-body">
                  <label class="required"><strong>Reason for Cancellation:</strong></label><br>
                  <textarea name="reason" id="reason" class="form-control" style="width: 100%;" ></textarea>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
              </form>
            </div>
            <div id="order_change_fare_form" style="display:none">
              <form action="" class="form-inline" method="post" id="changeFareForm" onSubmit="return validateChangeFareForm();" >
                {{ csrf_field() }}
                <div class="modal-title alert_msg"></div>
                <input type="hidden" id="change_fare_order_id" name="order_id" value="">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-3" style="display: {{(empty($adult_price))?'none':'block'}}">
                      <div class="form-group">
                        <label for="adult_price" class="control-label required">Adult:</label><br>
                        <input type="text" name="adult_price" value="{{ old('adult_price', $adult_price) }}" id="adult_price" class="form-control"/>
                        @include('snippets.errors_first', ['param' => 'adult_price'])
                      </div>
                    </div>
                    <div class="col-md-3" style="display: {{(empty($child_price))?'none':'block'}}">
                      <div class="form-group">
                        <label for="child_price" class="control-label required">Child:</label><br>
                        <input type="text" name="child_price" value="{{ old('child_price', $child_price) }}" id="child_price" class="form-control"/>
                        @include('snippets.errors_first', ['param' => 'child_price'])
                      </div>
                    </div>
                    <div class="col-md-3" style="display: {{(empty($infant_price))?'none':'block'}}">
                      <div class="form-group">
                        <label for="infant_price" class="control-label required">Infant:</label><br>
                        <input type="text" name="infant_price" value="{{ old('infant_price', $infant_price) }}" id="infant_price" class="form-control"/>
                        @include('snippets.errors_first', ['param' => 'infant_price'])
                      </div>
                    </div>
                    <div class="col-md-3 text-center mt-14">
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
                  </div>
                </div>
                
              </form>
            </div>
            <div id="order_confirm_form" style="display:none">
              <form action="" class="form-inline" method="post" id="orderConfirmForm" onSubmit="return validateConfirmForm();" >
                {{ csrf_field() }}
                <div class="modal-title alert_msg"></div>
                <input type="hidden" id="confirm_order_id" name="order_id" value="">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-3 mb-2">
                      <div class="form-group{{ $errors->has('pnrDetails') ? ' has-error' : '' }}">
                        <label for="pnrDetails" class="control-label required">Airline PNR:</label><br>
                        <input type="text" class="form-control" name="pnrDetails" id="pnrDetails" value="{{old('pnrDetails',$pnrDetails)}}">
                        @include('snippets.errors_first', ['param' => 'pnrDetails'])
                      </div>
                    </div>
                    <div class="col-md-3 mb-2">
                      <div class="form-group{{ $errors->has('airline_ticket_no') ? ' has-error' : '' }}">
                        <label for="airline_ticket_no" class="control-label">Airline Ticket No:</label><br>
                        <input type="text" class="form-control" name="airline_ticket_no" id="airline_ticket_no" value="{{old('airline_ticket_no',$airline_ticket_no)}}">
                        @include('snippets.errors_first', ['param' => 'airline_ticket_no'])
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group{{ $errors->has('supplier_id') ? ' has-error' : '' }}">
                        <label for="source" class="control-label required">Agent:</label><br>
                        <select class="form-control select2 supplier_id" name="supplier_id" id="supplier_id">
                          @if($supplier_id)
                          <option value="{{$supplier_id}}" selected >{{$supplier_name}}</option>
                          @endif
                        </select>
                        @include('snippets.errors_first', ['param' => 'supplier_id'])
                      </div>
                    </div>
                    <div class="col-md-3 mt-14 text-center">
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
                  </div>
                </div>
                
              </form>
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="form-group flex">

        @include('admin.orders._order_info')

        <h3>Traveller Contact Details</h3>
        <div class="col-form-label px-3"><strong>Name:</strong> <span class="text-sm">{{$name}}</span> | <strong>Phone:</strong> <span class="text-sm"><a href="tel:{{$phone}}">{{$phone}}</a></span> | <strong>Email:</strong> <span class="text-sm"><a href="mailto:{{$email}}">{{$email}}</a></span></div>               
      </div>

      <form class="" method="POST">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-md-12">
            <h3>Flight Details</h3>
            @if($itineraries)
              {!!$itineraries!!}
            @else
            <div class="alert alert-warning">Booking Not Found</div>
            @endif
          </div>
        </div>
      </form>

      @include('admin.orders._payment_logs')

    </div>

  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="pleaseNoteModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Please Note</h4>
      </div>
      <div class="modal-body">
        <p>Cancellation will occur in the next 4 working hours.</p>
        <p>Working hours are from 8 A.M. to 10 P.M everyday.</p>
        <p>If the flight departs in the next 12 hours you are requested to cancel with the airline directly to prevent NO SHOWS.</p>
        <p>Are you sure you want to cancel?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-default btn_admin" id="cancellation_btn">Ok</button>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Discard</button>
        <button type="button" class="btn btn-default btn_admin" id="cancel_done">Done</button>
      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <?php 
    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mail voucher</h4>
      </div>
      <div class="modal-body">
        <label>To:</label>
        <span>{{$email}}</span>
        <br>
        <label>From:</label>
        <span>{{$ADMIN_EMAIL}}</span>
      </div>
      <div class="modal-footer">
        <a href="javascript:void(0);" title="Flight Voucher" class="btn btn-success cab_view_fancy btn_admin" id="sendMail"> Send Mail </a>
        <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<input type="hidden" name="passenger_update_status" id="passenger_update_status" value="">

</div>

@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
/*$("#myModal a").click(function() {
$("#sendMail").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
$("#sendMail").attr("disabled", true);
});*/

$(document).ready(function() {
  initSelect2();
});

function initSelect2() {
  $('.supplier_id').select2({
    placeholder: "Please Select",
    allowClear: true,
    ajax: {
      url: "{{ route($ADMIN_ROUTE_NAME.'.register-agents.ajax_agent_list') }}",
      type: "POST",
      data: function (params) {
        return {
          term: params.term,
          type: 'offline_flight'
        };
      },
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'},
      processResults: function (resp) {
        return {
          results:  resp.items
        };
      }
    },
  });
}

$(document).on("click","#cust_btn",function(){
  $("#myModal").modal("toggle");
});

$(document).on("click","#cancel_btn",function(){
  $('.ajax_cancellation_msg').html('');
  $('.validation_error').html('');
  var selected_passengers = validateCancellation();
  if(selected_passengers && selected_passengers.length > 0) {
    $("#pleaseNoteModal").modal("toggle");
  } else {
    alert('Please select passengers to cancel the ticket.')
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

$(document).on("click","#sendMail",function(e){
  e.preventDefault();
  $('.ajax_msg').html('');
  $('.validation_error').html('');
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route($ADMIN_ROUTE_NAME.'.orders.details',$order_no) }}",
    type: "POST",
    data: {'action':'sendmail'},
    dataType:"JSON",
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    beforeSend: function () {
      $("#sendMail").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
      $("#sendMail").attr("disabled", true);        
    },
    success: function(resp){
      if(resp.success) {
        $('.ajax_msg').html('<span class="validation_success">'+resp.message+'</span>');
      } else if(resp.message) {
        $('.ajax_msg').html('<span class="validation_error">'+resp.message+'</span>');
      }
      $("#myModal").modal("toggle");
      $("#sendMail").html('Send Mail');
      $("#sendMail").attr("disabled", false);
    },error: function(e) {
      var response = e.responseJSON;
      if(response) {
        if(response.message) {
          $('.ajax_cancellation_msg').html('<span class="validation_error">'+response.message+'</span>');
        }
      }
      $("#sendMail").html('Send Mail');
      $("#sendMail").attr("disabled", false);
    }
  });

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
      url: "{{ route($ADMIN_ROUTE_NAME.'.orders.details',$order_no) }}",
      type: "POST",
      data: {'action':'cancellation','passengers':selected_passengers,'reason':reason,'other_reason':other_reason},
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


$(document).on("click","#order_cancel_btn",function(){
  $('.btn_admin2').removeClass('active');
  $(this).addClass('active');
  $("#order_change_fare_form").hide();
  $("#order_confirm_form").hide();
  var order_id = $(this).attr('data-order_id');
  $('#order_cancel_order_id').val(order_id);
  // $(".order_status_form").hide();
  if ($("#order_cancel_form").is(":hidden")) {
    $("#order_cancel_form").show();
  } else {
    $("#order_cancel_form").hide();
  }
});

$(document).on("click","#order_change_fare_btn",function(){
  $('.btn_admin2').removeClass('active');
  $(this).addClass('active');
  $("#order_cancel_form").hide();
  $("#order_confirm_form").hide();
  var order_id = $(this).attr('data-order_id');
  $('#change_fare_order_id').val(order_id);
  if ($("#order_change_fare_form").is(":hidden")) {
    $("#order_change_fare_form").show();
  } else {
    $("#order_change_fare_form").hide();
  }
});

$(document).on("click","#order_confirm_btn",function(){
  $('.btn_admin2').removeClass('active');
  $(this).addClass('active');
  $("#order_cancel_form").hide();
  $("#order_change_fare_form").hide();
  var order_id = $(this).attr('data-order_id');
  $('#confirm_order_id').val(order_id);
  if ($("#order_confirm_form").is(":hidden")) {
    $("#order_confirm_form").show();
  } else {
    $("#order_confirm_form").hide();
  }
});

function validateCancelForm() {
  var order_id = $("#order_cancel_order_id").val();
  var reason = $("#reason").val();
  if (!reason) {
    alert("Please add reason for order cancellation.");
    $("#reason").focus();
    return false;
  }else{
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{route($ADMIN_ROUTE_NAME.'.orders.cancelOfflineFlight') }}",
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
        $("#orderCancelForm .alert_msg").show();
        $("#orderCancelForm .btn-success").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
        $("#orderCancelForm .btn-success").attr("disabled", true);
      },
      success: function(response) {
        $("#orderCancelForm .btn-success").html('Submit');
        $("#orderCancelForm .btn-success").attr("disabled", false);
        if(response.success) {
          $("#orderCancelForm .alert_msg").html(response.message).hide();
          $("#orderCancelForm .alert_msg").html(response.message).fadeIn();
          $("#orderCancelForm .modal-body").hide();
          $("#orderCancelForm .text-center").hide();
          setTimeout(function() {
            $("#order_cancel_form").fadeOut();
            window.location.reload();
          }, 1000);
        } else if(response.message) {
          alert(response.message);
        }
      }
    });
  }
  return false;
}

function validateChangeFareForm() {
  var order_id = $("#change_fare_order_id").val();
  var adult_price = $("#adult_price").val();
  var child_price = $("#child_price").val();
  var infant_price = $("#infant_price").val();
  if (!adult_price) {
    alert("Please enter adult price.");
    $("#adult_price").focus();
    return false;
  } else if (!child_price) {
    alert("Please enter child price.");
    $("#child_price").focus();
    return false;
  } else if (!infant_price) {
    alert("Please enter infant price.");
    $("#infant_price").focus();
    return false;
  } else{
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{route($ADMIN_ROUTE_NAME.'.orders.changeFareOfflineFlight') }}",
      type: "POST",
      data: {
        order_id: order_id,
        adult_price: adult_price,
        child_price: child_price,
        infant_price: infant_price
      },
      dataType: "JSON",
      headers: {
        'X-CSRF-TOKEN': _token
      },
      cache: false,
      beforeSend: function() {
        $("#changeFareForm .alert_msg").show();
        $("#changeFareForm .btn-success").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
        $("#changeFareForm .btn-success").attr("disabled", true);
      },
      success: function(response) {
        $("#changeFareForm .btn-success").html('Submit');
        $("#changeFareForm .btn-success").attr("disabled", false);
        if(response.success) {
          $("#changeFareForm .alert_msg").html(response.message).hide();
          $("#changeFareForm .alert_msg").html(response.message).fadeIn();
          $("#changeFareForm .modal-body").hide();
          $("#changeFareForm .text-center").hide();
          setTimeout(function() {
            $("#order_change_fare_form").fadeOut();
            window.location.reload();
          }, 1000);
        } else if(response.message) {
          alert(response.message);
        }
      }
    });
  }
  return false;
}

function validateConfirmForm() {
  var order_id = $("#confirm_order_id").val();
  var pnrDetails = $("#pnrDetails").val();
  var airline_ticket_no = $("#airline_ticket_no").val();
  var supplier_id = $("#supplier_id").val();
  if (!pnrDetails) {
    alert("Please enter Airline PNR.");
    $("#pnrDetails").focus();
    return false;
  } else if (!supplier_id) {
    alert("Please select Agent.");
    $("#supplier_id").focus();
    return false;
  } else {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{route($ADMIN_ROUTE_NAME.'.orders.confirmOfflineFlight') }}",
      type: "POST",
      data: {
        order_id: order_id,
        pnrDetails: pnrDetails,
        airline_ticket_no: airline_ticket_no,
        supplier_id: supplier_id
      },
      dataType: "JSON",
      headers: {
        'X-CSRF-TOKEN': _token
      },
      cache: false,
      beforeSend: function() {
        $("#orderConfirmForm .alert_msg").show();
        $("#orderConfirmForm .btn-success").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
        $("#orderConfirmForm .btn-success").attr("disabled", true);
      },
      success: function(response) {
        $("#orderConfirmForm .btn-success").html('Submit');
        $("#orderConfirmForm .btn-success").attr("disabled", false);
        if(response.success) {
          $("#orderConfirmForm .alert_msg").html(response.message).hide();
          $("#orderConfirmForm .alert_msg").html(response.message).fadeIn();
          $("#orderConfirmForm .modal-body").hide();
          $("#orderConfirmForm .text-center").hide();
          setTimeout(function() {
            $("#order_confirm_form").fadeOut();
            window.location.reload();
          }, 1000);
        } else if(response.message) {
          alert(response.message);
        }
      }
    });
  }
  return false;
}

$('.supplier_id').select2({
  placeholder: "Please Select",
  allowClear: true,
  ajax: {
    url: "{{ route($ADMIN_ROUTE_NAME.'.register-agents.ajax_agent_list') }}",
    type: "POST",
    data: function (params) {
      return {
        term: params.term,
        type: 'offline_flight'
      };
    },
    dataType:"JSON",
    headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'},
    processResults: function (resp) {
      return {
        results:  resp.items
      };
    }
  },
});
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.recharge_fancy').fancybox({
      'type': "iframe",
      'width':'500',
      'toolbar'  : false,
      'smallBtn' : true,
      'autosize':false,

      beforeClose: function(){

      }
    });

    $('.edit_passenger_details').fancybox({
      'type': "iframe",
      'width':'500',
      'toolbar'  : false,
      'smallBtn' : true,
      'autosize':false,
      afterClose: function(){
        var passenger_update_status = $('#passenger_update_status').val();
        if(passenger_update_status == 'success') {
          parent.location.reload(true);
        }
      },
      beforeClose: function() {
        var $iframe = $( '.fancybox-iframe' );
        var update_status = $('#update_passenger input:hidden[name=update_status]', $iframe.contents()).val();
        if(update_status == 'success') {
          $('#passenger_update_status').val(update_status);
        }
      }
    });

    $(document).on('click','#admin_notes_edit',function(){
      $('#admin_notes_edit').hide();
      $('#admin_notes_input').show();
      $('#admin_notes_actions').show();
    });

    $(document).on('click','.admin_notes_cancel',function(){
      $('#admin_notes_edit').show();
      $('#admin_notes_input').hide();
      $('#admin_notes_actions').hide();
    });

    $(document).on('click','.admin_notes_save',function(){
      var order_id = '{{$order_id}}';
      var action = 'update_admin_notes';
      var _token = '{{csrf_token()}}';
      var admin_notes = $('#admin_notes').val();
      $.ajax({
        url: "{{route($ADMIN_ROUTE_NAME.'.orders.updateOrder')}}",
        type: "POST",
        data: {order_id,action,admin_notes},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        beforeSend: function () {

        },
        success: function(resp) {
          if(resp.success) {
            $('#admin_notes_html').html(nl2br(admin_notes));
            $('#admin_notes_edit').show();
            $('#admin_notes_input').hide();
            $('#admin_notes_actions').hide();
            if(resp.message) {
              alert(resp.message);
            }
          } else if(resp.message) {
            alert(resp.message);
          }
        }
      });
    });
        
  });

  function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
  }
</script>

@endslot
@endcomponent