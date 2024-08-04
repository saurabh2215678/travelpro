@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
<style type="text/css">
.theme_footer:before {z-index: -1;}
.booking-lists thead tr th {border-right: 1px solid #dee2e6;background: var(--theme-color); color: #fff;text-align: left;font-size: 12px;line-height: normal;}
.btn.s-btn {display: initial; background: #01b3a7; color: #ffffff !important; width:auto;cursor: pointer; border-color: #01b3a7;}
.btn.s-btn:hover {background: #282658;border-color: #282658; color: #ffffff !important;}
.btn2.edit_pofile_btn {font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
.user_profile_inner .right_info .top_info {padding-bottom: 0px;}
.summary_one, .summary_two {display: flex;flex-direction: column;align-items: flex-start;}
.summary_one > *, .summary_two > * {text-align: left;font-size: 15px;}
.summary_one p, .summary_two p {border-bottom: 1px solid #919191; margin: 0;padding: 5px;color: #444550;font-size: 13px;}
.summary_one h4, .summary_two h4 {font-size: 20px;font-weight: 600;}
.summary_two {margin-top: 15px;}
.summary_one .total, .summary_two .total {border: 0;text-align: left;padding: 0;}
.pagination-wrapper.hotel-pagination {
    justify-content: flex-end;
}
.wallet-wrapper table.table tr:last-child td {
    border-bottom: 1px solid #dee2e6;
}
</style>

@endslot
<?php 
$order_type_arr = config('custom.order_type');

// $from = request()->get('from');
// $to = request()->get('to');
?>
<section>
        <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info pt-1">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">My Wallet</h1>
                        </div>
                    </div>
                    <div>
                        <label>Balance: {{CustomHelper::getPrice($balance,'',true)}}</label><br>
                        <!-- <label>Available Credit: Rs. {{$balance}}</label> -->
                    </div>
                </div>
                  
                    <div class="top_info pt-2">
                    <div class="left">
                        <div class="theme_title">
                            <!-- <h3 class="title">Add Amount</h3> -->
                        </div>
                     
                       <form action="{{route('pay-online')}}" method="POST" class="mt-1">
                        {{ csrf_field() }}
                        <div class="w-full flex">
                         <div class="w-3/6">
                            <div class="mb-1 mr-2">
                                <label for="city_id">Amount:</label>
                                <input type="text" class="form-control" name="amount" value="" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                <input type="hidden" class="form-control" name="action" value="wallet">
                                <sapn class="validation_error">@include('snippets.errors_first', ['param' => 'amount'])</sapn>
                            </div>
                        </div>
                     
                        <div class="w-3/6">
                            <label>&nbsp</label>
                            <div class="mb-1">
                               <button type="submit" class="btn s-btn rounded-full">Recharge Now</button>
                               
                           </div>
                       </div>
                   </div>
                   <p class="text-sm text-orange-600 pb-2">Note: You will be charged of {{CustomHelper::WebsiteSettings('WALLET_PAYMENT_GATEWAY_CHARGE')}}% extra as Payment gateway charge</p>
               </form>
                    </div>

                </div>
                <div class="top_info border-0">
                    <div class="left">
                        <div class="theme_title">
                            <h3 class="font-semibold py-2 pb-0 text-lg">Recharge Wallet</h3>
                        </div>
                    </div>
                </div> 
                 <form action="" method="GET" class="mt-1">

                <div class="Wallet-search">
                  <ul class="list my-2">
                    <li class="input_outer date_input date_box">
                        <input class="form-control" type="date" id="from_date" name="from" value="{{CustomHelper::DateFormat($from, 'Y-m-d', 'd/m/Y')}}" placeholder="From Date">
                    </li>
                    <li class="input_outer date_input date_box mx-2">
                        <input class="form-control" type="date" id="to_date" name="to" value="{{CustomHelper::DateFormat($to, 'Y-m-d', 'd/m/Y')}}" placeholder="To Date">
                    </li>
                    <li class="input_outer">
                        <input type="submit" name="search" id="search" class="btn s-btn rounded-full mr-2" value="Search">
                        <a href="{{route('user.myWallet')}}" class="btn2 edit_pofile_btn">Reset</a>
                        <!--  <a href="" class="btn btn-outline btn-info">Show All</a> -->
                    </li>
                    </ul>
                </div>
            </form>
                <div class="wallet-wrapper mt-5">
                <div class="table-responsive">
                    <table id="" class="table adults table-striped table-hover dataTable no-footer">
                        <thead>
                            <tr role="row">
                            <th>Transaction No</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Payment Method</th>
                            <th>Debit (Dr)</th>
                            <th>Credit (Cr)</th>
                            <th>Balance</th>
                            </tr>
                        </thead>
                         <tbody>
                            <?php 
                            // prd($wallets);
                            $order_type_arr = config('custom.order_type');
                            foreach ($wallets as $key => $wallet) {

                              // prd($wallet->toArray());
                              $orderData  = $wallet->orderData;  
                              $order_type = !empty($orderData->order_type)?$order_type_arr[$orderData->order_type]:'';
                              ?> 
                           <tr>
                               <td>
                                {{$wallet->txn_id ?? '(Backend)'}}
                                <br>({{$order_type ?? ''}})
                               </td>
                               <td>{{CustomHelper::DateFormat($wallet->for_date, 'd M,Y h:i A')}} </td>
                               <td>{!!$wallet->comment??''!!}</td>
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
                        </tbody>
                    </table>
                    <div class="pagination-wrapper hotel-pagination mt-5 text-right" >
                     {{ $wallets->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                   </div>
                </div>
                <div class="summary_one">
                    <h4 class="text-right">Total Summary from {{$from}} to {{$to}}</h4>
                    <div class="total">
                        <p><strong>Credit (Total) :</strong> <span>{{CustomHelper::getPrice($date_credit,'',true)}}</span></p>
                        <p><strong>Debit (Total) :</strong> <span>{{CustomHelper::getPrice(abs($date_debit),'',true)}}</span></p>
                        <p><strong>Total Wallet Amount :</strong> <span>{{CustomHelper::getPrice($date_sum,'',true)}}</span></p>
                    </div>
                </div>
                <div class="summary_two col-md-12">
                <h4 class="text-right pt-3 pb-1">Overall Summary</h4>
                    <div class="total">
                    <p><strong>Credit (Total) :</strong> <span>{{CustomHelper::getPrice($total_credit,'',true)}}</span></p>
                    <p><strong>Debit (Total) :</strong> <span>{{CustomHelper::getPrice(abs($total_debit),'',true)}}</span></p>
                    <p><strong>Total Wallet Amount :</strong> <span>{{CustomHelper::getPrice($balance,'',true)}}</span></p>
                    </div>
                </div>
                </div>
            </div>
          </div>
        </div>
    </section>
@slot('bottomBlock') 
@endslot
@endcomponent


