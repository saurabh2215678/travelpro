<?php 
$tpsl_txn_id = '';
$txn_amt = '';  
$txn_msg = '';  

if(isset($transactions_view->pg_response) && !empty($transactions_view->pg_response)){
    $getPgResponse = json_decode($transactions_view->pg_response);
    if(is_object($getPgResponse) && isset($getPgResponse->id)) {
        $tpsl_txn_id = $getPgResponse->id;
        $txn_msg = $getPgResponse->status;
        $txn_amt = ($getPgResponse->amount / 100);
    } else {
        if(isset($getPgResponse[1])) {
            $txn_msg = explode('=', $getPgResponse[1]);
            $txn_msg = $txn_msg[1]??'';
        }
        if(isset($getPgResponse[5])) {
            $tpsl_txn_id = explode('=', $getPgResponse[5]);
            $tpsl_txn_id = $tpsl_txn_id[1]??'';
        }
        if(isset($getPgResponse[6])) {
            $txn_amt = explode('=', $getPgResponse[6]);
            $txn_amt = $txn_amt[1]??'';
        }
    }
}
?>
<div class="enq-view activity-log">
    <h2>Transactions Details (Order ID: #{{$transactions_view->order_no??''}}) </h2>
    <div class="row">
        <div class="col-md-12">
            <div class="perdataSec">
                <ul>
                    <tr>
                        <td width="806" valign="top" class="innersec">
                            <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                               
                                <tr>
                                    @if(isset($tpsl_txn_id) && !empty($tpsl_txn_id))
                                    <td>
                                        <div><b>Transaction ID: </b></div>
                                        <div>{{$tpsl_txn_id}}</div>
                                    </td>
                                    @endif

                                    @if(isset($txn_msg) && !empty($txn_msg))
                                    <td>
                                        <div><b>Transaction Message: </b></div>
                                        <div>{{$txn_msg}}</div>
                                    </td>
                                    @endif

                                    @if(isset($txn_amt) && !empty($txn_amt))
                                    <td>
                                        <div><b>Transaction Amount: </b></div>
                                        <div>{{CustomHelper::getPrice($txn_amt)}}</div>
                                </td>
                                @endif
                            </tr>
                </table>
            </ul>
            </div>
        </div>
    </div>