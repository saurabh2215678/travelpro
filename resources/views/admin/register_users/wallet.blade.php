@component('admin.layouts.main')

@slot('title')
Admin - Manage User Wallet - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php 
// $back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();
?>

<div class="top_title_admin">
    <div class="title">
        <h2>Manage User Wallet
            ( {{isset($user->name) && !empty($user->name) ? $user->name : ''}} )</h2>
    </div>
    <div class="add_button topright_inner">
        <div class="balance_txt">
          Balance: {{CustomHelper::getPrice($balance,'',true)}}
        </div>
        <a href="{{ route($routeName.'.register-users.transaction_add', $userId) }}" style="margin-left: -58px;" class="btn_admin recharge_fancy"><svg style=" width: 15px; height: auto; fill: #fff; position: relative; top: 0.3rem; margin-right: .5rem; " version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 458.531 458.531" xml:space="preserve"> <g id="XMLID_830_"> <g> <g> <path d="M336.688,343.962L336.688,343.962c-21.972-0.001-39.848-17.876-39.848-39.848v-66.176 c0-21.972,17.876-39.847,39.848-39.847h103.83c0.629,0,1.254,0.019,1.876,0.047v-65.922c0-16.969-13.756-30.725-30.725-30.725 H30.726C13.756,101.49,0,115.246,0,132.215v277.621c0,16.969,13.756,30.726,30.726,30.726h380.943 c16.969,0,30.725-13.756,30.725-30.726v-65.922c-0.622,0.029-1.247,0.048-1.876,0.048H336.688z"/> <path d="M440.518,219.925h-103.83c-9.948,0-18.013,8.065-18.013,18.013v66.176c0,9.948,8.065,18.013,18.013,18.013h103.83 c9.948,0,18.013-8.064,18.013-18.013v-66.176C458.531,227.989,450.466,219.925,440.518,219.925z M372.466,297.024 c-14.359,0-25.999-11.64-25.999-25.999s11.64-25.999,25.999-25.999c14.359,0,25.999,11.64,25.999,25.999 C398.465,285.384,386.825,297.024,372.466,297.024z"/> <path d="M358.169,45.209c-6.874-20.806-29.313-32.1-50.118-25.226L151.958,71.552h214.914L358.169,45.209z"/> </g> </g> </g> </svg>  Add Transaction</a>
        &nbsp;&nbsp;
        <a href="{{ url($routeName.'/register-users')}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
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
                    <button style="margin-right:15px" type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.register-users.wallet', $userId)}}" class="btn_admin2">Reset</a>
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
                <th>Subject</th>
                <th>Payment Method</th>
                <th>Debit (Dr)</th>
                <th>Credit (Cr)</th>
                <th>Balance</th>
            </tr>
            </tr>
            <?php foreach($wallets as $wallet){ ?>

            <tr>
                <td>{{$wallet->txn_id ?? '(Backend)'}}</td>
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
        <h4 class="text-left">Total Summary222 from {{$from}} to {{$to}}</h4>
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