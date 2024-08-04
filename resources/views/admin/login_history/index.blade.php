	@component('admin.layouts.main')

    @slot('title')
    Admin - Backend User Manage Login History - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php 
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $user_id = (request()->has('user_id'))?request()->user_id:'';
    $old_action = (request()->has('action'))?request()->action:'';
    $old_ip_address = (request()->has('ip_address'))?request()->ip_address:'';
    $old_os_details = (request()->has('os_details'))?request()->os_details:'';
    $old_browser_details = (request()->has('browser_details'))?request()->browser_details:'';

    $range = (request()->has('range')) ? request()->range : '';
    $range = old('range', $range);

    $from = (request()->has('from'))?request()->from:'';
    $from = old('from', $from);

    $to = (request()->has('to'))?request()->to:'';
    $to = old('to', $to);

    
    $range_filters = [
      'custom' => 'Custom',
      'yesterday' => 'Yesterday',
      'today' => 'Today',
      'tomorrow' => 'Tomorrow',
      'this_week' => 'This Week',
      'next_week' => 'Next Week',
      'last_week' => 'Last Week',
      'this_month' => 'This Month',
      'last_month' => 'Last Month',
      'last_7_days' => 'Last 7 days',
      'last_30_days' => 'Last 30 days',
      'this_year' => 'Current Year',
      'last_year' => 'Previous Year',
  ];

  ?>
  <style>
      label{height: auto;}
      .categoryselect select.form-control{ width: 100%;}
      .username-logs span.select2.select2-container {z-index: 22;}
  </style>

  <div class="top_title_admin">
    <div class="title">
        <h2>Backend User Login History ({{ $login_history_logs->total() }})</h2>
    </div>
</div>

<div class="centersec">
    <div class="bgcolor p10">
        <div class="row">
            <form class="" method="GET">
                <div class="col-md-3 username-logs">
                    <label>User Name:</label><br/>
                    <select name="user_id" class="form-control" id="active_user">
                        <option value="">Select Users</option>
                        @if(!empty($users))
                        @foreach($users as $user)
                        <option value="{{$user->id}}" {{($user->id==$user_id)?'selected':''}}>{{$user->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label>IP Address:</label><br/>
                    <input type="text" name="ip_address" class="form-control admin_input1" value="{{$old_ip_address}}" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label>Operating System:</label><br/>
                    <input type="text" name="os_details" class="form-control admin_input1" value="{{$old_os_details}}" autocomplete="off">
                </div> 
                <div class="col-md-3">
                    <label>Browser:</label><br/>
                    <input type="text" name="browser_details" class="form-control admin_input1" value="{{$old_browser_details}}" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label>Action:</label><br/>
                    <select name="action" class="form-control admin_input1">
                        <option value="">Select Action</option>
                        @if(!empty($actions))
                        @foreach($actions as $action)
                        <?php if(!empty($action->action)){ ?>
                           <option value="{{$action->action}}" {{($old_action == $action->action)?'selected':'' }}>{{$action->action}}</option>
                           <?php 
                       }
                       ?>
                       @endforeach
                       @endif

                   </select>
               </div>      
               <div class="col-md-3{{ $errors->has('range') ? ' has-error' : '' }} rangediv">
                <label>Date range </label><br />
                <select name="range" class="form-control range">
                    <option value="">Select Date</option>
                    @foreach($range_filters as $k => $v)
                    <option value="{{$k}}" {{(!empty($range) && $range == $k)?'selected':''}}>{{$v}}
                    </option>
                    @endforeach
                </select>
                @include('snippets.errors_first', ['param'=>'range'])
            </div>
            <div class="clearfix"></div>
            <div
            class=" col-md-3{{ $errors->has('from') ? ' has-error' : '' }} {{ (!empty($range) && $range=='custom') ? '' : 'hide' }} dateDiv">
            <label>From Date</label><br />
            <input type="text" name="from" class="form-control from_date" placeholder="From Date"
            value="{{$from}}" autocomplete="off">
            @include('snippets.errors_first', ['param'=>'from'])
        </div>
        <div
        class="col-md-3{{ $errors->has('to') ? ' has-error' : '' }} {{ (!empty($range) && $range=='custom') ? '' : 'hide' }} dateDiv">
        <label>To Date</label><br />
        <input type="text" name="to" class="form-control to_date" placeholder="To Date"
        value="{{$to}}" autocomplete="off">
        @include('snippets.errors_first', ['param'=>'to'])
    </div>

    <div class="col-sm-12">
        <br>
        <button type="submit" class="btn btn-success btn_admin">Search</button>
        <a href="{{url($routeName.'/login_history')}}" class="btn resetbtn btn-primary btn_admin2">Reset</a>
         <a href="{{url($routeName.'/login_history/delete')}}" class="btn resetbtn btn-primary btn_admin2 sbmtDelForm"  onclick="if (confirm('Are you sure you want to Delete Login History older than 30 Days?')){return true;}else{event.stopPropagation(); event.preventDefault();};" title="Delete Login History">Delete Login History older than 30 Days</a>
    </div>
</form>
</div>
</div>

@include('snippets.errors')
@include('snippets.flash')

@if(!empty($login_history_logs) && count($login_history_logs) > 0)
<div class="table-responsive">           
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th>User Name</th>
            <th>User ID</th>
            <th>Login Time</th>
            <th>IP Address</th>
            <th>Operating System</th>
            <th>Browser</th>
            <th>Action</th>
            <th>View</th>
        </tr>
        @foreach ($login_history_logs as $login_history)
        <tr>

            <td> 
               <a href="{{route($routeName.'.login_history.index',['user_id'=>$login_history->user_id,'BackUrl'=>$BackUrl])}}" title="Users">{{$login_history->user_name}}</a>        
           </td> 
           <td>{{isset($login_history->user_id) ? $login_history->user_id : '' }}</td>

           <td><?php echo $added_on = CustomHelper::DateFormat($login_history->created_at,'d-m-Y H:i:s');?></td>

           <td>{{isset($login_history->ip_address) ? $login_history->ip_address : ''}}</td>


           <td>{{isset($login_history->os_details) ? $login_history->os_details : ''}}</td>

           <td>{{isset($login_history->browser_details) ? $login_history->browser_details : ''}}</td><td>{{isset($login_history->action) ? $login_history->action : ''}}</td>

           <td>
            <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.login_history.view', [$login_history->id]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
        </td>
    </tr>
    @endforeach
</table>
</div>
{{ $login_history_logs->appends(request()->query())->links('vendor.pagination.default') }}

@else
<div class="alert alert-warning">There are no records at the present.</div>
@endif
</div>

@slot('bottomBlock')
<link rel="stylesheet" href="{{asset('assets/css/jquery.fancybox.min.css')}}">
<script src="{{asset('/assets/js/jquery.fancybox.min.js')}}"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="{{asset('assets/css/jquery.datetimepicker.min.css')}}">
<script src="{{asset('/assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
  $(".tooltip_title").tooltip();

  $( function() {
    $( ".to_date, .from_date" ).datepicker({
        dateFormat:'dd/mm/yy',
        changeMonth: true, 
        changeYear: true,
        dateFormat: "dd/mm/yy",
        yearRange: "-90:+01" 

    });
});

  $('.range').on('change', function() {
        //alert( this.value );
    if (this.value == 'custom') {
        $('.dateDiv').removeClass('hide');
    } else {
        $('.dateDiv').addClass('hide');
        $('input[name=from]').val('');
        $('input[name=to]').val('');
    }
});
$("#active_user").select2({
    placeholder: "Select Users",
    allowClear: true
});
$('.sbmtDelForm').click(function(){
    //var id = $(this).attr('id');
    $("#delete-form").submit();
});
</script>
@endslot
@endcomponent