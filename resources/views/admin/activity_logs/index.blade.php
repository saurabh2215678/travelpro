	@component('admin.layouts.main')

    @slot('title')
    Admin - Backend User Manage Activity Logs - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php 
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_name = (request()->has('name'))?request()->name:'';
    $user_id = (request()->has('user_id'))?request()->user_id:'';
    $old_module = (request()->has('module'))?request()->module:'';
    $old_module_desc = (request()->has('module_desc'))?request()->module_desc:'';
    $old_sub_module = (request()->has('sub_module'))?request()->sub_module:'';
    $old_sub_module_desc = (request()->has('sub_module_desc'))?request()->sub_module_desc:'';
    $old_action = (request()->has('action'))?request()->action:'';
    $old_ip_address = (request()->has('ip_address'))?request()->ip_address:'';

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
        <h2>Backend User Activity Logs ({{ $activity_logs->total() }})</h2>
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
            <label>Module:</label><br/>
            <select name="module" class="form-control">
                <option value="">Select Module</option>
                @if(!empty($modules))
                @foreach($modules as $module)
                <?php if(!empty($module->module)){ ?>
                 <option value="{{$module->module}}" {{($old_module == $module->module)?'selected':'' }}>{{$module->module}}</option>
                 <?php 
             }
             ?>
             @endforeach
             @endif

         </select>
     </div>
     <div class="col-md-3">
        <label>Module Object Name:</label><br/>
        <input type="text" name="module_desc" class="form-control admin_input1" value="{{$old_module_desc}}" autocomplete="off">
    </div> 
    <div class="col-md-3">
        <label>Sub Module:</label>
        <select name="sub_module" class="form-control admin_input1">
            <option value="">Select SubModule</option>
            @if(!empty($submodules))
            @foreach($submodules as $submodule)
            <?php if(!empty($submodule->sub_module)){ ?>
               <option value="{{$submodule->sub_module}}" {{($old_sub_module == $submodule->sub_module)?'selected':'' }}>{{$submodule->sub_module}}</option>
               <?php 
           }
           ?>
           @endforeach
           @endif

       </select>
   </div>

   <div class="col-md-3">
    <label>SubModule Object Name:</label><br/>
    <input type="text" name="sub_module_desc" class="form-control admin_input1" value="{{$old_sub_module_desc}}" autocomplete="off">
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

<div class="col-md-3">
    <label>IP Address:</label><br/>
    <input type="text" name="ip_address" class="form-control admin_input1" value="{{$old_ip_address}}" autocomplete="off">
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
    <a href="{{url($routeName.'/activitylogs')}}" class="btn resetbtn btn-primary btn_admin2">Reset</a>
</div>
</form>
</div>
</div>

    @include('snippets.errors')
    @include('snippets.flash')

@if(!empty($activity_logs) && count($activity_logs) > 0)
<div class="table-responsive">           
<table class="table table-striped table-bordered table-hover">
        <tr>
            <th colspan="2">User</th>
            <th rowspan="2">Module</th>
            <th colspan="2">Module Object</th>
            <th rowspan="2">Sub Module</th>
            <th colspan="2">Sub Module Object</th>
            <th rowspan="2">Action</th>
            <th rowspan="2">IP Address</th>
            <th rowspan="2">TimeStamp</th>
            <th rowspan="2">View</th>
        </tr>
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Name</th>
            <th>ID</th>
            <th>Name</th>
            <th>ID</th> 

        </tr>
        @foreach ($activity_logs as $activity_log)
        <tr>

            <?php /*<td>{{isset($activity_log->user_name) ? $activity_log->user_name : '' }}</td> */ ?>

            <td> 
                <?php 
                           /* $total_user_by_activityLog = CustomHelper::total_user_by_activityLog($activity_log->id);
                           $total_user_by_activityLog;*/
                           ?>
                           <a href="{{route($routeName.'.activitylogs.index',['user_id'=>$activity_log->user_id,'BackUrl'=>$BackUrl])}}" title="Users">{{$activity_log->user_name}}</a>        
                       </td> 

                       <td>{{isset($activity_log->user_id) ? $activity_log->user_id : '' }}</td>

                       <td>{{isset($activity_log->module) ? $activity_log->module : ''}}</td>

                       <td>{!!isset($activity_log->module_desc) ? $activity_log->module_desc : '' !!} </td>
                       <td>{{isset($activity_log->module_id) ? $activity_log->module_id : '' }}</td>

                       <td>{{isset($activity_log->sub_module) ? $activity_log->sub_module : ''}}</td>

                       <td>{{isset($activity_log->sub_module_desc) ? $activity_log->sub_module_desc : ''}}</td>
                       <td>{{isset($activity_log->sub_module_id) ? $activity_log->sub_module_id : ''}}</td>

                       <td>{{isset($activity_log->action) ? $activity_log->action : ''}}</td>

                       <?php /* <td>{{isset($activity_log->user_agent) ? $activity_log->user_agent : ''}}</td> */ ?>

                       <td>{{isset($activity_log->ip_address) ? $activity_log->ip_address : ''}}</td>

                       <td>{{CustomHelper::DateFormat($activity_log->created_at,'d M,Y h:i A')}}</td>
                       <td>
                        <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.activitylogs.view', [$activity_log->id]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
       {{ $activity_logs->appends(request()->query())->links('vendor.pagination.default') }}

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

      /*$('.to_date, .from_date').datetimepicker({
          dayOfWeekStart : 1,
          lang:'en',
          hours24:true,
          allowTimes:['10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30'],
          step: 30,
              // minDate:true,
          scrollTime:true,
          defaultTime:'10:00',
          format:'d-m-Y H:i',
      });*/
      $("#active_user").select2({
          placeholder: "Select Users",
          allowClear: true
      });


      $(document).on("click", ".sbmtDelForm", function(e){
        e.preventDefault();

        $(this).siblings("form.delForm").submit();                
    });

</script>

@endslot

@endcomponent