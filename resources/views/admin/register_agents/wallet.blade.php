@component('admin.layouts.main')

@slot('title')
Admin - Manage Agent Wallet - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php 
// $back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();
?>

<div class="top_title_admin">
    <div class="title">
        <h2>Manage Agent Wallet ( <?php
            echo $user->agentInfo?$user->agentInfo->company_brand:'';
            echo $user->agentInfo?' / '.$user->agentInfo->company_name:''; ?> )</h2>
        </div>
        <div class="add_button topright_inner">
            <div class="balance_txt">
                Balance: {{CustomHelper::getPrice($balance,'',true)}}
            </div>
            <a href="{{ route($routeName.'.register-agents.transaction_add', $userId) }}" class="btn_admin recharge_fancy"><i class='fas fa-wallet' style='font-size:10px'></i> Add Transaction</a>
            &nbsp;&nbsp;
            <a href="{{ url($routeName.'/register-agents')}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
    </div>

    <div class="centersec">
        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="table-responsive">

                <form class="form-inline" method="GET">
                    <div class="col-md-3">
                        <label>From Date:</label><br />
                        <input type="text" name="from" class="form-control admin_input1 to_date" value="{{$from}}"
                        autocomplete="off">
                    </div>

                    <div class="col-md-3">
                        <label>To Date:</label><br />
                        <input type="text" name="to" class="form-control admin_input1 from_date" value="{{$to}}"
                        autocomplete="off">
                    </div>
                    <div class="col-md-6 d_flex pt-15">
                        <label></label><br />
                        <button style="margin-right:15px" type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.register-agents.wallet', $userId)}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <?php
        if(!empty($wallets) && count($wallets) > 0){
            ?>
            <div class="table-responsive">

                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <tr role="row">
                            <th>Transaction No</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Payment Method</th>
                            <th>Debit (Dr)</th>
                            <th>Credit (Cr)</th>
                            <th>Balance</th>
                        </tr>
                    </tr>
                    <?php 
                    $order_type_arr = config('custom.order_type');
                    foreach($wallets as $wallet) {
                        $orderData  = $wallet->orderData;  
                        $order_type = !empty($orderData->order_type)?$order_type_arr[$orderData->order_type]:'';
                        ?>
                        <tr>
                            <td>
                                {{$wallet->txn_id ?? '(Backend)'}}
                                <br>({{$order_type ?? ''}})
                            </td>
                            <td>{{CustomHelper::DateFormat($wallet->for_date, 'd M,Y h:i A')}} </td>
                            <td>{{$wallet->comment ?? ''}}</td>
                            <td>{{$wallet->payment_method ?? ''}}</td>
                            <td> <?php if($wallet->type == 'debit'){
                                echo CustomHelper::getPrice(abs($wallet->amount),'',true) ;
                            } ?>
                        </td>
                        <td> <?php if($wallet->type == 'credit'){
                            echo CustomHelper::getPrice(abs($wallet->amount),'',true) ;
                        } ?>
                    </td>
                    <td>{{CustomHelper::getPrice($wallet->balance,'',true)}} </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="summary_one col-md-12">
        <h4 class="text-left">Total Summary from {{$from}} to {{$to}}</h4>
        <div class="total text-left">
            <p><strong>Credit (Total) :</strong> <span>{{CustomHelper::getPrice($date_credit)}}</span></p>
            <p><strong>Debit (Total) :</strong> <span>{{CustomHelper::getPrice(abs($date_debit))}}</span></p>
            <p><strong>Total Wallet Amount :</strong> <span>{{CustomHelper::getPrice($date_sum)}}</span></p>
        </div>
    </div>
    <div class="summary_two col-md-12">
    <h4 class="text-left pt-3 pb-1">Overall Summary</h4>
    <div class="total text-left">
        <p><strong>Credit (Total) :</strong> <span>{{CustomHelper::getPrice($total_credit)}}</span></p>
        <p><strong>Debit (Total) :</strong> <span>{{CustomHelper::getPrice(abs($total_debit))}}</span></p>
        <p><strong>Total Wallet Amount :</strong> <span>{{CustomHelper::getPrice($balance)}}</span></p>
    </div>
    </div>
    {{ $wallets->appends(request()->query())->links('vendor.pagination.default') }}
<?php } else{ ?>
    <div class="alert alert-warning">There are no Records at the present.</div>
<?php } ?>
</div>
</div>

@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>

<style>
    body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-stage .fancybox-content iframe {
        padding: 15px;
    }
    body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-slide--iframe .fancybox-content {
        height: 50% !important;
        width: 100rem !important;
    }
    .fancybox-active .fancybox-container.fancybox-is-open button.fancybox-button {
        background: #009688;
        top: 0px;
        right: 0;
    }
    body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-inner .fancybox-toolbar {
        right: 34rem;
        top: 4rem;
    }
    .fancybox-slide--iframe .fancybox-content {
       width  : 800px;
       height : 450px;
       max-width  : 80%;
       max-height : 50%;
       margin: 0;
   }
</style>

<script type="text/javaScript">

    $( function() {
        $( ".to_date, .from_date" ).datepicker({
            'dateFormat':'dd/mm/yy',
            changeMonth: true, 
            changeYear: true
        });
    });

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
@endslot
@endcomponent