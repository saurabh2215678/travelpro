<style>
.centersec.fancybox-content {
    width: 900px;
    height: 450px;
    padding-top: 8px;
}
.add_button {
    width: auto;
    margin: 0 auto;
    margin-bottom: 12px;
    padding-right: 18px;
}
</style>
<?php 
$routeName = CustomHelper::getAdminRouteName();
?>
<div class="centersec">
    <div class="bgcolor viewsec">
        <div class="alert_msg"></div>
        @if($order_detail->user_id && ($order_detail->payment_status == 0 || $order_detail->payment_status == 2) )
        <div class="add_button">
            <a href="{{ route($routeName.'.orders.addPayment', $order_detail->id) }}" style="margin-left: -58px;" class="btn_admin recharge_fancy"><i class="fas fa-wallet" style="font-size:10px; color:#fff;"></i> Add Transaction</a>
        </div>
        @endif
                <div class="table-responsive" style="padding-right: 18px;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec table">
            <tr>
                <td width="100%" valign="top" class="innersec">
                    <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                        <h2>Order Payments Details (Order ID: #{{$order_detail->order_no??''}}) </h2>
                        <?php if(!empty($order_payments) && $order_payments->count() > 0){?>
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
                            <td>{{date('d M,Y H:i A',strtotime($order_payment->created_at)) ?? ''}}</td>
                        </tr>
                        <?php } }else { ?>
                        <div class="alert alert-warning">No transaction details found for this order.</div>
                        <?php } ?>
                    </table>
                </td>
            </tr>
        </table>
        </div>
    </div>
</div>
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
});
</script>