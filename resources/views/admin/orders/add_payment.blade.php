<style>
    form#frm_slot { min-width: 700px;}
    .fancybox-content {padding: 25px; min-width: 600px;}
    #frm_slot .p_slot:first-child a.col-md-3 {padding-top: 28px !important;}
    .slot_section p {padding: 0 5px; font-size: 16px;color: #009688;}
    .slot_section h3 {margin: 0; padding-top: 0; padding-left: 0;padding-bottom: 5px;}
    .slot_section .table>tbody>tr td, .popup_slot_section tbody tr td {font-size: 13px; }
    .counter {
        position: absolute;
        top: -33px;
        right: -3px;
        font-size: 11px;
    }
    .select2-search--inline {
        background-color: Gainsboro;
        display: none;
    }
    .select2-selection__rendered {
        display: flex !important;
        overflow: hidden;
        margin-right: 20px;
        margin-bottom: 0;
    }
    .select2-selection__rendered li {
        display: none !important;
    }
    .select2-selection__rendered li:nth-child(-n + 2) {
        display: block !important;
    }
    body {
        background: #ffffff !important;
    }
    form#wallet_form {
        padding-top: 10px;
    }
    .slot_section .note {
        display: block;
    }
    .slot_section{
        padding:33px;
    }
    select#type {
    width: 85px !important;
}
    
</style>
<link href="{{asset('/')}}css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="{{url('')}}/css/site.css" rel="stylesheet" type="text/css" />
<?php
$amount = $order->total_amount??0;
$partial_amount = 0;
$payment_status = $order->payment_status??0;
if($payment_status == 1) {
    $partial_amount = $order->partial_amount??0;
}
$payment_due = 0;
if($partial_amount) {
    $amount = $amount - $partial_amount;
}

$user = $order->User??[];
$balance = 0;
$name = '';
if($user) {
    $balance = $user->walletBalance()??0;
    if($user->is_agent == 1) {
        $name = $user->agentInfo->company_brand??'';
        if(empty($name)) {
            $name = $user->name??'';
        }
        // $name .= ' (A)';
    } else {
        $name = $user->name??'';
    }
}
?> 
<div class="slot_section">
    <div class="title22 d_flex" style="align-items: center;">
        <h3>{{$page_heading}}</h3><p style="margin-bottom: 0;">({{$name}})</p>
    </div>
    <div class="title18 d_flex" style="align-items: center;">
        <strong><h6>Wallet Balance: </strong></h6> <p style="margin-bottom: 0;"><strong>{{CustomHelper::getPrice($balance)}}</strong></p>
    </div>
    <span class="note"><strong style="color:#f00;">Note:</strong> The payment will be deducted from the wallet and applied to this order.</span>
    <br>
    <!-- @include('snippets.errors') -->
    @include('snippets.flash')
    <?php if(empty($amount)){ ?>
        <div class="alert alert-warning">The order does not have any due amount!</div>
    <?php } else if($balance >= $amount){ ?>
        <form method="post" id="wallet_form" role="form">
            {{csrf_field()}}
            <div class="ajax_msg"></div>
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <td>
                        <label class="control-label required">Type</label>
                        <div class="{{ $errors->has('type') ? ' has-error' : '' }}">
                            <select name="type" class="form-control admin_input1" id="type" disabled="disabled">
                                <!-- <option value="credit" @if(old('type') == 'credit' ||old('type') == '') selected @endif>Credit</option> -->
                                <option value="debit" @if(old('type') == 'debit') selected @endif>Debit</option>
                            </select>
                        </div>
                    </td>

                    <td style="width: 15rem;">
                        <div class="{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label class="control-label required">Amount</label>
                            <input type="text" name="amount" value="{{$amount}}" class="form-control admin_input1" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" disabled="disabled">
                        </div>
                    </td>

                    <td style="width: 16rem;">
                        <label class="control-label required">Payment Method</label>
                        <div class="{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                            <select name="payment_method" class="form-control admin_input1" id="payment_method" disabled="disabled">
                                <!-- <option value="Bank Transfer" @if(old('payment_method') == 'Bank Transfer') selected @endif>Bank Transfer</option>
                                <option value="Cash" @if(old('payment_method') == 'Cash' || old('payment_method') == '') selected @endif>Cash</option>
                                <option value="Cheque" @if(old('payment_method') == 'Cheque') selected @endif>Cheque</option> -->
                                <option value="wallet" @if(old('payment_method') == 'wallet') selected @endif>Wallet</option>
                            </select>
                        </div>
                    </td>

                    <td style="width: 16rem;">
                        <div class="{{ $errors->has('for_date') ? ' has-error' : '' }}">
                            <label class="control-label required">Date</label>
                            <input type="date" name="for_date" value="<?php if(old('for_date') == ''){ echo date('Y-m-d');} else { echo old('for_date'); } ?>" class="form-control admin_input1">
                        </div>
                    </td>	

                    <td>
                        <div class="{{ $errors->has('comment') ? ' has-error' : '' }}">
                            <label class="control-label required">Comment</label>
                            <input type="text" name="comment" value="{{old('comment')}}" class="form-control admin_input1">
                        </div>
                    </td>

                    <input type="hidden" name="order_id" value="{{$order_id}}" class="form-control admin_input1">
                </tr>
            </table>

            <button id="submitBtn" type="submit" class="btn_admin2 location_form_submit save_submit">Submit</button>
        </form>
    <?php }else{ ?>
        <div class="alert alert-warning">Agent/Customer does not have sufficient wallet balance to pay for this Order.</div>
    <?php } ?>
</div>

<script type="text/javascript" src="{{asset('/js/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script>
    jQuery.support.cors = true;
    if ($("#wallet_form").length > 0) {
        $("#wallet_form").validate({
            submitHandler: function(form) {
                $("#submitBtn").html(
                    'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
                    );
                $("#submitBtn").attr("disabled", true);
                form.submit();
            }
        })
    }
</script>