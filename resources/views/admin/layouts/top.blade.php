<style>
    .topright ul.top-nav .parent {
        width: 30px;
        height: 30px;
        background: #00b2a7;
        margin: auto;
        border-radius: 100%;
        position: relative;
        cursor: pointer;
        margin-right: 13px;
        margin-top: 2px;
        display: inline-block;
        padding: 10px;
    }
    @keyframes breathe{
        0%{transform:scale(1)}
        50%{transform:scale(0.5)}
        100%{transform:scale(1)}
    }
    @keyframes breathe2{
        0%{transform:scale(1)}
        50%{transform:scale(1.2)}
        100%{transform:scale(1)}
    }
    .topright ul.top-nav .parent:before {
        content: "";
        position: absolute;
        top: -2px;
        left: -2px;
        z-index: 2;
        width: 35px;
        height: 35px;
        background: #00b2a7;
        opacity: 0.3;
        border-radius: 100%;
        animation: breathe2 2s infinite;
    }
    .topright ul.top-nav .parent:after {
        content: "";
        position: absolute;
        top: -5px;
        left: -5px;
        z-index: 3;
        width: 41px;
        height: 41px;
        background: #00b2a7;
        opacity: 0.2;
        border-radius: 100%;
        animation: breathe 2s infinite;
    }
    .topright ul.top-nav .parent > a span {
        color: #fff;
        font-weight: bold;
        font-size: 14px;
        position: absolute;
        z-index: 4;
        top: 4px;
        left: 8px;
    }
    .topright ul.top-nav .number {
        height: 20px;
        min-width: 20px;
        background-color: #f0562f;
        border-radius: 20px;
        color: white;
        text-align: center;
        position: absolute;
        top: -8px;
        right: -8px;
        padding: 1px;
        border-style: solid;
        border-width: 2px;
        z-index: 99;
    }





</style>
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$back_url = CustomHelper::BackUrl();
$pendingOrdersCount = CustomHelper::getPendingOrdersCount();
$pendingAmendmentCount = CustomHelper::pendingAmendmentCount();
$pendingCancellationCount = CustomHelper::pendingCancellationCount();
$totalPendingCounter = $pendingOrdersCount + $pendingAmendmentCount + $pendingCancellationCount;
$online_booking = CustomHelper::isOnlineBooking();

$is_vendor = auth()->user()->is_vendor;
$keyword = old('keyword');
$flightApiBalance = 0;
if(CustomHelper::isOnlineBooking('flight')){
    $resp = CustomHelper::flightApiBalance();
    $flightApiBalance = CustomHelper::getPrice(0);
    if($resp['success']) {
        $flightApiBalance = $resp['walletBalance']??CustomHelper::getPrice(0);
    }
}
?>
<?php if($online_booking){ ?>
    <div class="topleft">
        <div class="search_bar">
            <form method="post" action="{{route($ADMIN_ROUTE_NAME.'.adminSearch')}}">
                {{csrf_field()}}
                <label>Booking ID</label>
                <input type="text" name="keyword" class="textbox" placeholder="Search..." value="{{$keyword}}">
                <input title="Search" value="" type="submit" class="button">
            </form>
        </div>
    </div>
<?php } ?>
<div class="topright">
    <ul class="top-nav">
        <?php if($online_booking){
            if($is_vendor != 1){ ?>

                @if(CustomHelper::isOnlineBooking('flight'))
                <li class="dropdown">
                    <div class="flightBalance">
                        <b>TripJack Balance:</b> <span class="flightBalanceHtml">{{$flightApiBalance}}</span>
                        <span class="rotate"><i class="fa fa-refresh"></i></span>
                        <span class="rotateing-icon"><i class="fa fa-refresh"></i></span>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        </a>
                    </div>
                    <style>	
                        @-webkit-keyframes rotating {
                        from{
                            -webkit-transform: rotate(0deg);
                        }
                        to{
                            -webkit-transform: rotate(360deg);
                        }
                        }

                        .fetching-balance {
                        -webkit-animation: rotating 2s linear infinite;
                        animation: rotating 2s linear infinite;
                        }
                    </style>
                </li>
                @endif

                @if(CustomHelper::checkPermission('orders','view'))
                <li class="dropdown">
                    <div class="parent">
                        <a href="{{ route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'pending']) }}" class="dropdown-toggle" data-toggle="dropdown">
                            @if($totalPendingCounter)
                            <div class="number">{{$totalPendingCounter}}</div>
                            @endif
                            <span><i class="fa fa-bell"></i></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">                
                            <li><a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'pending','range'=>'custom','order_type'=>''])}}" title="Pending Bookings">Pending Bookings <em>({{$pendingOrdersCount}})</em></a></li>
                            <li><a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'amendment_progress','range'=>'custom','order_type'=>'']) }}" title="Amendment in Progress">Amendment Prog..<em>({{$pendingAmendmentCount}})</em></a></li>
                            <li><a href="{{route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'cancel_request','range'=>'custom','order_type'=>'']) }}" title="Cancellation Request">Cancellation Req..<em>({{$pendingCancellationCount}})</em></a></li>
                        </ul>
                    </div>
                </li>
                @endif
            <?php } } ?>

            @if(auth()->check())
            <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="user_logo"><img src="{{url('/images/user-men.jpg')}}" alt="" class="ulogo" /> </span> <span class="admin_name">{{ auth()->user()->name }}</span> <i aria-hidden="true" class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    @if (auth()->user()->type == 'admin')
                    <li><a href="{{ route($ADMIN_ROUTE_NAME.'.home') }}" title="Admin Panel"><i class="fa fa-desktop"></i> Admin</a></li>
                    @endif

                    <li><a href="{{ route($ADMIN_ROUTE_NAME.'.change_password', ['back_url'=>$back_url]) }}" title="Change Password"><svg xmlns="http://www.w3.org/2000/svg" fill="none" width="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /> </svg> Change Password</a></li>

                    <li> <a href="{{ url('admin/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg class="svg-icon mr-0 text-secondary" id="h-05-p" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path> </svg>Log Out</a>

                        <form id="logout-form" action="{{ url('admin/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
            @else
            <li><a href="login">Login</a></li>
            @endif
        </ul>
    </div>