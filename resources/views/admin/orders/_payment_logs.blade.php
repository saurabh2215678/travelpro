<style type="text/css">
  .form-title {background: #e5e5e5; margin: 0px 0 15px; padding: 0 10px;}
  .form-title h4 {font-weight: 600;}
  .service-vaucher h3 {padding: 0;margin: 0;font-size: 17px;}
  .service-vaucher .add_button {width: auto;padding-bottom: 10px;display: flex;column-gap: 12px;}
  .service-vaucher .add_button a {padding: 6px 10px;}
  .service-vaucher .add_button a.btn_admin2:hover {background: #162e44;}
</style>
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$order_id = $order->id??0;
$total_amount = $order->total_amount??0;
$partial_amount = $order->partial_amount??0;
$payment_due = $total_amount - $partial_amount;
?>
<div class="service-vaucher">
  <div class="table-responsive">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec table">
      <tr>
        <td width="100%" valign="top" class="innersec" style="border:0;">
          <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
            <h3 class="title-btn">Order Payments
              @if($order->user_id && ($order->payment_status == 0 || $order->payment_status == 2 || $payment_due > 0) )
              <div class="add_button">
                <a href="javascript:void(0);" class="btn_admin2" id="request_payment" onClick="return RequestPayment();" ><i class="fas fa-wallet" style="font-size:10px; color:#fff;"></i> Request Payment</a>
                <a href="javascript:void(0);" class="btn_admin2" id="request_payment_processing" style="display: none;" disabled >Processing...</a>
                <a href="{{ route($ADMIN_ROUTE_NAME.'.orders.addPayment', $order->id) }}" class="btn_admin recharge_fancy"><i class="fas fa-wallet" style="font-size:10px; color:#fff;"></i> Add Transaction</a>
              </div>
              @endif
            </h3>
            <?php if(!empty($order_payments) && $order_payments->count() > 0){ ?>
              <tr>
                <th>Payment Amount</th>
                <th>Payment Method</th>
                <th>Payment Type</th>
                <th>Payment Status</th>
                <th>Payment Date</th>
              </tr>
              <?php foreach ($order_payments as $order_payment) { ?>
                <tr>
                  <td>{{CustomHelper::getPrice($order_payment->amount) ?? ''}}</td>
                  <td>{{isset($order_payment->payment_method) && !empty($order_payment->payment_method) ? ucwords(str_replace("_", " ", $order_payment->payment_method)) : ''}}</td>
                  <td>{{isset($order_payment->payment_type) && !empty($order_payment->payment_type) ? ucwords(str_replace("_", " ", $order_payment->payment_type)) : '' }}</td>
                  <td>
                    {!!CustomHelper::showPaymentStatus($order_payment->pg_payment_status??0)!!}
                  </td>
                  <td>{{CustomHelper::DateFormat($order_payment->created_at,'d M,Y h:i A')}}</td>
                </tr>
              <?php } }else { ?>
                <tr>
                  <td colspan="5"><div class="alert alert-warning">No transactions found for this order.</div></td>
                </tr>
              <?php } ?>
            </table>
          </td>
        </tr>
      </table>
    </div>

    <div class="table-responsive">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec table">
        <tr>
          <td width="100%" valign="top" class="innersec" style="border-top: 0;">
            <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
              <h3>Order Logs</h3>
              <tbody>
                <tr>
                  <th>Order Status</th>
                  <th>Comments</th>
                  <th>User</th>
                  <th>Date</th>
                </tr>
                <?php if(!empty($order_status_histories) && $order_status_histories->count() > 0){ ?>
                  <?php foreach($order_status_histories as $row) { ?>
                    <tr>
                      <td>{{$row->orders_status}}</td>
                      <td>{!!nl2br($row->comments)!!}</td>
                      <td>{{$row->created_by_name}}</td>
                      <td>{{CustomHelper::DateFormat($row->created_at,'d M,Y h:i A')}}</td>
                    </tr>
                  <?php } ?>
                <?php }else{ ?>
                  <tr>
                    <td colspan="4"><div class="alert alert-warning">No logs found for this order.</div></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </td>
        </tr>
      </table>
      <a href="javascript:void(0);history.go(-1);" class="btn_admin2">Back</a>
    </div>
  </div>
  <script type="text/javascript">
    function RequestPayment() {
      var booking_id = '{{$order_id}}';
      var action = 'request_payment';
      var _token = '{{csrf_token()}}';
      $.ajax({
        url: "{{route($ADMIN_ROUTE_NAME.'.orders.bulk_action')}}",
        type: "POST",
        data: {booking_id,action},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        beforeSend: function () {
          $('#request_payment').hide();
          $('#request_payment_processing').show();
        },
        success: function(resp) {
          $('#request_payment').show();
          $('#request_payment_processing').hide();
          if(resp.success) {
            if(resp.message) {
              alert(resp.message);
            }
          } else if(resp.message) {
            alert(resp.message);
          }
        }
      });
    }
  </script>