@component('admin.layouts.main')
@slot('title')
Admin Panel - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<style>
    .graph_dropdown{position: absolute;display: flex;right: 1%;list-style: none;z-index: 999;}
    .dashmain ul.dropdown-menu {right: 0;}
    .dashmain .graph_dropdown ul.dropdown-menu {left: auto;}
    #chart text {font-weight: 600;}
</style>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endslot

<?php $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$is_vendor = auth()->user()->is_vendor??'';
$online_booking = CustomHelper::isOnlineBooking();
 ?>
@if(CustomHelper::checkPermission('dashboard','keyStatistics'))
<div class="top_title_admin">
    <div class="title">
        <h2>Dashboard</h2>
    </div>
</div>

<div class="centersec dashmain">
    @include('snippets.errors')
    @include('snippets.flash')
    
    <div class="row">
    @if(CustomHelper::checkPermission('enquiries','view'))
     <div class="col-xs-12 col-sm-6 col-lg-4">
        <div class="boxs greycolor">
            <div>
                <i class="fas fa-tasks fa-5x greybg" aria-hidden="true"></i>
            </div>
            <div class="boxtext booking_list">
                <h3>Total Enquiries</h3>
                <p>Total Enquiries</p>
                <div class="greycolor text-center col-sm-5">
                    <p>Assigned</p>
                    <a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries.index',['enquiry'=>'assigned']) }}">{{ $assign_enquiries }}</a>
                </div>
                <span class="greycolor text-center col-sm-2 border_1"></span>
                <div class="greycolor text-center col-sm-5">
                    <p>Unassigned</p>
                    <a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries.index',['enquiry'=>'unassigned']) }}">{{ $unassign_enquiries }}</a>
                </div>
            </div>
        </div>
    </div>
@endif 
<?php if($online_booking){ ?> 
@if(CustomHelper::checkPermission('orders','view'))
    <div class="col-xs-12 col-sm-6 col-lg-4">
        <div class="boxs greycolor">
            <div>
                <i class="fa fa-bar-chart fa-5x greybg" aria-hidden="true"></i>
            </div>
            <div class="boxtext booking_list">
                <h3>TOTAL BOOKING</h3>
                <p>Total Booking</p>
                <div class="greycolor text-center col-sm-5">
                    <p>Confirm</p>
                    <a href="{{ route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'confirmed']) }}">{{ $orders_confirmed }}</a>
                </div>
                <span class="greycolor text-center col-sm-2 border_1"></span>
                <div class="greycolor text-center col-sm-5">
                    <p>Pending</p>
                    <a href="{{ route($ADMIN_ROUTE_NAME.'.orders.index',['order'=>'pending']) }}">{{ $orders_pending }}</a>
                </div>
            </div>
        </div>
    </div>
    @endif
<?php } ?>

@if(CustomHelper::checkPermission('testimonials','view'))
    <div class="col-xs-12 col-sm-6 col-lg-4">
        <div class="boxs greycolor">
            <div>
                <i class="fa fa-quote-left fa-5x greybg" aria-hidden="true"></i>
            </div>
            <div class="boxtext booking_list">
                <h3>TOTAL Testimonials</h3>
                <p>Total Testimonials</p>
                <div class="greycolor text-center col-sm-5">
                    <p>Active</p>
                    <a
                    href="{{ route($ADMIN_ROUTE_NAME.'.testimonials.index',['status'=>'1']) }}">{{ $active_testimonials }}</a>
                </div>
                <span class="greycolor text-center col-sm-2 border_1"></span>
                <div class="greycolor text-center col-sm-5">
                    <p>Inactive</p>
                    <a
                    href="{{ route($ADMIN_ROUTE_NAME.'.testimonials.index',['status'=>'0']) }}">{{ $inactive_testimonials }}</a>
                </div>
            </div>
        </div>
    </div>
    @endif

@if(CustomHelper::isAllowedModule('package_listing') && CustomHelper::checkPermission('packages','view'))
    <div class="col-xs-12 col-sm-6 col-lg-4">
        <div class="boxs bluecolor">
            <div>
                <i class="fa fa-list-alt fa-5x bluelightbg" aria-hidden="true"></i>
            </div>
            <div class="boxtext">
                <h3>Total Packages</h3>
                <p>Total Packages</p>
                <h1 class="bluecolor"><a href="{{url('admin/packages')}}">{{ $packages }}</a></h1>
            </div>

        </div>
    </div>
@endif

@if(CustomHelper::isAllowedModule('destination_listing') && CustomHelper::checkPermission('destinations','view'))
    <div class="col-xs-12 col-sm-6 col-lg-4">
        <div class="boxs greycolor">
            <div>
                <i class="fa fa-map-o fa-5x greybg" aria-hidden="true"></i>
            </div>
            <div class="boxtext">
                <h3>Total Destinations</h3>
                <p>Total Destinations</p>
                <h1 class="greycolor"><a href="{{url('admin/destinations')}}">{{ $destinations  }}</a></h1>
            </div>
        </div>
    </div>
    @endif

    @if(CustomHelper::isAllowedModule('hotel_listing') && CustomHelper::checkPermission('accommodations','view'))
    <div class="col-xs-12 col-sm-12 col-lg-4">
        <div class="boxs bluedarkcolor">
            <div>
                <i class="fa fa-building-o fa-5x bluedarkbg" aria-hidden="true"></i>
            </div>
            <div class="boxtext">
                <h3>Total Accommodations</h3>
                <p>Total Accommodations</p>
                <h1 class="bluedarkcolor"><a href="{{url('admin/accommodations')}}">{{ $accommodations  }}</a></h1>
            </div>
        </div>
    </div>
    @endif


        <?php /*<div class="col-xs-12 col-sm-6 col-lg-4">
  <div class="boxs bluecolor">  <div>
    <i class="fa fa-codepen fa-5x bluelightbg" aria-hidden="true"></i>
  </div>
  <div class="boxtext">
    <h3>Total Widgets</h3>
    <p>Total Widgets</p>
    <h1 class="bluecolor"><a href="{{url('admin/widget')}}">{{ $widgets }}</a></h1>
  </div>
</div>
</div> */ ?>
@if(CustomHelper::isAllowedModule('agentuser') && CustomHelper::checkPermission('agents','view'))
<div class="col-xs-12 col-sm-6 col-lg-4">
    <div class="boxs bluecolor">
        <div>
            <i class="fa fa-users fa-5x bluelightbg" aria-hidden="true"></i>
        </div>
        <div class="boxtext">
            <h3>Total Agents</h3>
            <p>Total Agents</p>
            <h1 class="bluecolor"><a href="{{url('admin/register-agents')}}">{{ $agents }}</a></h1>
        </div>
    </div>
</div>
@else
<?php if($is_vendor != 1){ ?>
<div class="col-xs-12 col-sm-6 col-lg-4">
    <div class="boxs bluecolor">
        <div>
            <i class="fa fa-rss fa-5x bluelightbg" aria-hidden="true"></i>
        </div>
        <div class="boxtext">
            <h3>Total Blogs</h3>
            <p>Total Blogs</p>
            <h1 class="bluecolor"><a href="{{route('admin.blogs.index', ['type'=>'blogs']) }}">{{ $blogs }}</a></h1>
        </div>
    </div>
</div>
<?php } ?>
@endif


 @if(CustomHelper::checkPermission('cms','view'))
<div class="col-xs-12 col-sm-6 col-lg-4">
    <div class="boxs greycolor">
        <div>
            <i class="fa fa-file-text fa-5x greybg" aria-hidden="true"></i>
        </div>
        <div class="boxtext">
            <h3>Total Cms Pages</h3>
            <p>Total Cms Pages</p>
            <h1 class="greycolor"><a href="{{url('admin/cms')}}">{{ $cms }}</a></h1>
        </div>
    </div>
</div>
@endif


@if(CustomHelper::checkPermission('all_masters','view'))
<div class="col-xs-12 col-sm-6 col-lg-4">
    <div class="boxs greycolor">
        <div>
            <i class="fa fa-quote-left fa-5x greybg" aria-hidden="true"></i>
        </div>
        <div class="boxtext booking_list">
            <h3>TOTAL THEME/ CATEGORY</h3>
            <p>Total Theme / Category</p>
            <?php //<h1 class="bluecolor"><a href="{{url('admin/packages/theme')}}">{{ $themes }}</a></h1> ?>
            @if(CustomHelper::isAllowedModule('package_listing'))
            <div class="greycolor text-center col-sm-5">
                    <p>Package</p>
                    <a href="{{url('admin/packages/theme')}}">{{ $theme_package }}</a>
                </div>
                @endif
                <span class="greycolor text-center col-sm-2 border_1"></span>
                @if(CustomHelper::isAllowedModule('activity_listing'))
                <div class="greycolor text-center col-sm-5">
                    <p>Activity</p>
                    <a href="{{url('admin/activity/theme')}}">{{ $theme_activity }}</a>
                </div>
                @endif
        </div>
    </div>
</div>
@endif

        <?php /* <div class="col-xs-12 col-sm-6 col-lg-4">
      <div class="boxs greycolor">  <div>
        <i class="fa fa-codepen fa-5x greybg" aria-hidden="true"></i>
      </div>
        <div class="boxtext">
          <h3>Total Users</h3>
        <p>Total Users</p>
            <h1 class="greycolor"><a href="{{url('admin/users')}}"></a></h1>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-lg-4">
      <div class="boxs bluecolor">  <div>
        <i class="fa fa-codepen fa-5x bluedarkbg" aria-hidden="true"></i>
      </div>
        <div class="boxtext">
          <h3>Total ActivityLogs</h3>
        <p>Total ActivityLogs</p>
            <h1 class="bluedarkcolor"><a href="{{url('admin/activities')}}"></a></h1>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-lg-4">
      <div class="boxs bluecolor">  <div>
        <i class="fa fa-codepen fa-5x bluedarkbg" aria-hidden="true"></i>
      </div>
        <div class="boxtext">
          <h3>Total Newsletter Subscriber</h3>
        <p>Total Newsletter Subscriber</p>
            <h1 class="bluedarkcolor"><a href="{{url('admin/newsletter')}}"></a></h1>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-lg-4">
      <div class="boxs bluecolor">  <div>
        <i class="fa fa-codepen fa-5x greybg" aria-hidden="true"></i>
      </div>
        <div class="boxtext">
          <h3>Total Team Management</h3>
        <p>Total Team Management</p>
            <h1 class="greycolor"><a href="{{url('admin/teammanagements')}}"></a></h1>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-lg-4">
      <div class="boxs bluecolor">  <div>
        <i class="fa fa-codepen fa-5x bluedarkbg" aria-hidden="true"></i>
      </div>
        <div class="boxtext">
          <h3>Total Team Category</h3>
        <p>Total Team Category</p>
            <h1 class="bluedarkcolor"><a href="{{url('admin/teammanagements/categories')}}"></a></h1>
        </div>
      </div>
      </div> */ ?>
  </div>

  <div class="row">
    @if(CustomHelper::isOnlineBooking() && CustomHelper::isAllowedModule('agentuser') && CustomHelper::checkPermission('agents','view')) 
    <?php if($is_vendor != 1){ ?>
    <div class="col-md-6">
        <?php if(!empty($total_agent_sales)){ ?>
        <div class="table-responsive">           
            <table class="table table-bordered">
                <tr>
                    <td><strong>Top Agents with Most Sales</strong></td>
                    <td style="text-align:right;">
                    <div class="dropdown">
                        <!-- <button class="btn btn-primary dropdown-toggle btn-success" type="button" data-toggle="dropdown">Today
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                        <li><a href="#">This Week</a></li>
                        <li><a href="#">This Month</a></li>
                        </ul> -->
                    </div>
                </td>
                </tr>
                <tr>    
                    <th>Name</th>
                    <th>Amount</th>
                </tr>
                <?php foreach($total_agent_sales as $agent_sale){ 
                    $agentData = $agent_sale->userData->agentInfo??'';
                    $company_owner_name = $agentData->company_owner_name??'';
                    $company_brand = $agentData->company_brand??'';
                ?>
                <tr>
                    <?php //<td>{{$agent_sale->userData->name??''}}</td> ?>
                    <td>{{$company_owner_name}} ({{$company_brand}})</td>
                    <td>{{CustomHelper::getPrice($agent_sale->total_sales,'',true)??0.00}}</td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <?php } ?>
    </div>
<?php } ?>

<?php if($is_vendor != 1){ ?> 
<div class="col-md-6">
    <?php
    if(!empty($agent_wallet_balances)){
        ?>
        <div class="table-responsive">           
            <table class="table table-bordered">
                <tr>
                        <td><strong>Top Agents with Balance in Their Wallet</strong></td>
                        <td style="text-align:right;">
                    <div class="dropdown">
                        <!-- <button class="btn btn-primary dropdown-toggle btn-success" type="button" data-toggle="dropdown">Today
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                        <li><a href="#">This Week</a></li>
                        <li><a href="#">This Month</a></li>
                        </ul> -->
                    </div>
                </td>
                    </tr>
                <tr>    
                    <th>Name</th>
                    <th>Total Amount</th>
                </tr>
                <?php
                foreach($agent_wallet_balances as $agent_balance){
                    $agentData = $agent_balance->UserDetails->agentInfo??'';
                    $company_owner_name = $agentData->company_owner_name??'';
                    $company_brand = $agentData->company_brand??'';
                    ?>
                    <tr>
                    <?php //<td>{{$agent_balance->UserDetails->name??''}}</td> ?>
                    <td>{{$company_owner_name}} ({{$company_brand}})</td>
                   <td>{{CustomHelper::getPrice($agent_balance->total_balances,'',true)??0.00}}</td>
               </tr>
           <?php } ?>
       </table>
   </div>
<?php } ?>
</div>
<?php } ?>
@endif

<?php if($online_booking){ ?> 
@if(CustomHelper::checkPermission('orders','view'))
<div class="col-md-12">
    <td style="text-align:right;">
        <div class="graph_dropdown">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle btn-success" type="button" data-toggle="dropdown" id="tab_btn">Daily
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)" onClick="loadSalesData('Daily')">Daily</a></li>
                        <li><a href="javascript:void(0)" onClick="loadSalesData('Weekly')">Weekly</a></li>
                        <li><a href="javascript:void(0)" onClick="loadSalesData('Monthly')">Monthly</a></li>
                    </ul>
                </div>
            </div>
        </td>
        <div id="chart">
     </div>
  </div>
  @endif
<?php } ?>
</div>
</div>
@endif
@slot('bottomBlock')

<script type="text/javascript">

    $(document).ready(function(){
        loadSalesData();
    });


function loadSalesData(dayType='Daily') {
    var _token = '{{ csrf_token() }}';
    $.ajax({
        url: "{{ route($ADMIN_ROUTE_NAME.'.loadSalesData') }}",
        type: "POST",
        data: {dayType},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        beforeSend:function(){
            $(".ajax_msg").html("");
        },
        success: function(resp){
            if(resp.success) {
                renderSalesData(resp.graph);

                $('#tab_btn').html(dayType+'<span class="caret"></span>');
            }
        }
    });
}

function renderSalesData(graph) {
    // console.log('graph=',graph);
    var options = {
          series: [{
          name: 'Total Sales',
          type: 'column',
          data: graph.data
        }
        // , {
        //   name: 'Social Media',
        //   type: 'line',
        //   data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
        // }
    ],
          chart: {
          height: 350,
          type: 'line',
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          }

        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Reports/Day'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels:  graph.labels,
        fill: {
            colors: ['#00B2A7', '#282658']
            },
        yaxis: [{
          title: {
            text: 'Total Sales',
          },
        
        },
        

        // , {
        //   opposite: true,
        //   title: {
        //     text: 'Social Media'
        //   }
        // }
    ]
    
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        chart.updateOptions(options)
}

</script>
@endslot
@endcomponent