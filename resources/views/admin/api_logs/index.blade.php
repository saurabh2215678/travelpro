@component('admin.layouts.main')

@slot('title')
Admin - API Logs - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php 
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$search = (request()->has('search'))?request()->search:'';
$ip_address = (request()->has('ip_address'))?request()->ip_address:'';

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
    <h2>API Logs ({{ $api_logs->total() }})</h2>
  </div>
</div>

<div class="centersec">
  <div class="bgcolor p10">
    <div class="row">
      <form class="" method="GET">
        
        <div class="col-md-3">
          <label>Search:</label><br/>
          <input type="text" name="search" class="form-control admin_input1" value="{{$search}}" autocomplete="off">
        </div>

        <div class="col-md-3">
          <label>IP Address:</label><br/>
          <input type="text" name="ip_address" class="form-control admin_input1" value="{{$ip_address}}" autocomplete="off">
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
        <div class=" col-md-3{{ $errors->has('from') ? ' has-error' : '' }} {{ (!empty($range) && $range=='custom') ? '' : 'hide' }} dateDiv">
          <label>From Date</label><br />
          <input type="text" name="from" class="form-control from_date" placeholder="From Date"
          value="{{$from}}" autocomplete="off">
          @include('snippets.errors_first', ['param'=>'from'])
        </div>
        <div class="col-md-3{{ $errors->has('to') ? ' has-error' : '' }} {{ (!empty($range) && $range=='custom') ? '' : 'hide' }} dateDiv">
          <label>To Date</label><br />
          <input type="text" name="to" class="form-control to_date" placeholder="To Date"
          value="{{$to}}" autocomplete="off">
          @include('snippets.errors_first', ['param'=>'to'])
        </div>

        <div class="col-sm-12">
          <br>
          <button type="submit" class="btn btn-success btn_admin">Search</button>
          <a href="{{url($routeName.'/apilogs')}}" class="btn resetbtn btn-primary btn_admin2">Reset</a>
        </div>
      </form>
    </div>
  </div>

  @include('snippets.errors')
  @include('snippets.flash')

  @if(!empty($api_logs) && count($api_logs) > 0)
  <div class="table-responsive">           
    <table class="table table-striped table-bordered table-hover">
      <tr>
        <th width="10%">User/Agent</th>
        <th width="10%">API Name</th>
        <th width="20%">API URL</th>
        <th width="20%">Request</th>
        <th width="25%">Response</th>
        <th width="10%">IP Address</th>
        <th width="8%">Date</th>
        <th width="7%">Action</th>
      </tr>
      @foreach ($api_logs as $api_log)
      <tr>
        <td style="word-break: break-all;">
          @if($api_log->user_id == -1000)
            Cron
          @elseif($api_log->user_id < 0)
            {{CustomHelper::showAdmin(abs($api_log->user_id),'name')}}
          @elseif($api_log->user_id)
          {{$api_log->User->name??''}}
          @else
          Guest
          @endif
        </td>
        <td style="word-break: break-all;">{{$api_log->api_name??''}}</td>
        <td style="word-break: break-all;">{{$api_log->api_url??''}}</td>
        <td style="word-break: break-all;">{{CustomHelper::wordsLimit($api_log->request_json)??''}}</td>
        <td style="word-break: break-all;">{{CustomHelper::wordsLimit($api_log->response_json)??''}}</td>
        <td>{{$api_log->ip_address??''}}</td>
        <td>{{CustomHelper::DateFormat($api_log->created_at,'d/m/y h:i A')??''}}</td>
        <td>
          <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.apilogs.view', [$api_log->id]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
        </td>
      </tr>
      @endforeach
    </table>
  </div>
  {{ $api_logs->appends(request()->query())->links('vendor.pagination.default') }}

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
    if (this.value == 'custom') {
      $('.dateDiv').removeClass('hide');
    } else {
      $('.dateDiv').addClass('hide');
      $('input[name=from]').val('');
      $('input[name=to]').val('');
    }
  });

</script>

@endslot

@endcomponent