@component('admin.layouts.main')
@slot('title')
Admin - Manage Enquiries - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<style>
.centersec {
    min-height: auto;
}
.unread {
  font-weight: bold;
}
.counter{
    position: absolute;
    top: -33px;
    right: -3px;
    font-size: 11px;
 }
 .select2-search--inline{
    background-color:Gainsboro;
    display:none;
 }
 .select2-selection__rendered {
    display: flex !important;
    overflow: hidden;
    margin-right: 20px;
    margin-bottom: 0;
  }
  .select2-selection__rendered li { display: none !important;}
  .select2-selection__rendered li:nth-child(-n + 2) { display: block !important;  
   }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$name = (request()->has('name'))?request()->name:'';
$name = old('name', $name);

$phone = (request()->has('phone'))?request()->phone:'';
$phone = old('phone', $phone);

$email = (request()->has('email'))?request()->email:'';
$email = old('email', $email);

$enquiry_for = (request()->has('enquiry_for'))?request()->enquiry_for:[];
$enquiry_for = old('enquiry_for', $enquiry_for);

$destination = (request()->has('destination'))?request()->destination:[];
$destination = old('destination', $destination);

$package_id = (request()->has('package_id'))?request()->package_id:'';
$package_id = old('package_id', $package_id);

$accommodation = (request()->has('accommodation'))?request()->accommodation:[];
$accommodation = old('accommodation', $accommodation);

$lead_source = (request()->has('lead_source'))?request()->lead_source:'';
$lead_source = old('lead_source', $lead_source);

$lead_status = (request()->has('lead_status'))?request()->lead_status:'';
$lead_status = old('lead_status', $lead_status);

$lead_sub_status = (request()->has('lead_sub_status'))?request()->lead_sub_status:'';
$lead_sub_status = old('lead_sub_status', $lead_sub_status);

$rating = (request()->has('rating'))?request()->rating:'';
$rating = old('rating', $rating);

$date_type = (request()->has('date_type'))?request()->date_type:'';
$date_type = old('date_type', $date_type);

$range = (request()->has('range'))?request()->range:'';
$range = old('range', $range);

$from = (request()->has('from'))?request()->from:'';
$from = old('from', $from);

$to = (request()->has('to'))?request()->to:'';
$to = old('to', $to);

$tab = (request()->has('tab'))?request()->tab:'';
$tab = old('tab', $tab);

$status = (request()->has('status'))?request()->status:'';
$status = old('status', $status);

$pending_enquiries = (request()->has('pending_enquiries'))?request()->pending_enquiries:'';
$tomorrow_later = (request()->has('tomorrow_later'))?request()->tomorrow_later:'';
$all_enquiries = (request()->has('all_enquiries'))?request()->all_enquiries:'';

$lead_status_category_status = (request()->has('status'))?request()->status:'';
$lead_status_category_status = old('status', $lead_status_category_status);

if($all_enquiries == 'all_enquiries'){
  $lead_status_category_status = [];
}
elseif($pending_enquiries == 'pending_enquiries'){
  $lead_status_category_status = ['new'];
}
elseif($tomorrow_later == 'tomorrow_later'){
  $lead_status_category_status = [];
}
else{
  if(empty($lead_status_category_status)){
    $lead_status_category_status = ['new','open','in_process'];
  }
}

$enq_status = config('custom.enq_status');
$lead_status_category_arr = config('custom.lead_status_category_arr');

$enq_for_types = config('custom.enq_for_types');
$range_filters = config('custom.range_filters');
$date_types = [
  'enquiry_date' => 'Enquiry Date',
  'updated_date' => 'Updated Date',
  'followup_date' => 'Followup Date'
];
$order_type ='';
$order ='';
?>
<div class="top_title_admin enquiries-top">
    <div class="col-md-6">
     <h5 class="title-text-left">Enquiries ({{$enquiries->total()}})</h5>
</div>
<div class="col-md-6 enquiries_btn">
    @if(CustomHelper::checkPermission('enquiries','add'))
      <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.enquiries.add')?>" title="Add Enquiry" data-fancybox data-type="ajax" class="btn_admin"><i class="fas fa-plus"></i> Add Enquiry</a>
      @endif
</div>
</div>
<!-- Start Search box section-->
<div class="centersec enquiries-cent">
  <div class="bgcolor">
     <div class="tab_block toptab">
      <ul class="nav nav-tabs">
        <li class="nav-item">
            <a <?php if($tab==''){echo 'class="active"' ;}?> href="{{route($routeName.'.enquiries.index',['pending_enquiries'=>'pending_enquiries'])}}">All</a>
        </li>

         <?php if(CustomHelper::isAllowedModule('flight') && CustomHelper::checkPermission('flight','view')){ ?>
        <li class="nav-item">
            <a <?php if($tab=='3'){echo 'class="active"' ;}?> href="{{route($routeName.'.enquiries.index',['tab'=>3])}}&order={{$order}}">Flight</a>
        </li>
        <?php } ?>

         <?php if(CustomHelper::isAllowedModule('cab') && CustomHelper::checkPermission('cabs','view')){ ?>
        <li class="nav-item">
            <a <?php if($tab=='6'){echo 'class="active"' ;}?> href="{{route($routeName.'.enquiries.index',['tab'=>6])}}&order={{$order}}">Cab</a>
        </li>
        <?php } ?>

       <?php if(CustomHelper::isAllowedModule('package_listing') && CustomHelper::checkPermission('packages','view')){ ?>
        <li class="nav-item">
            <a <?php if($tab=='1'){echo 'class="active"' ;}?> href="{{route($routeName.'.enquiries.index',['tab'=>1])}}&order={{$order}}">Package</a>
        </li>
        <?php } ?>
        
         <?php if(CustomHelper::isAllowedModule('activity_listing') && CustomHelper::checkPermission('activity','view')){ ?>
        <li class="nav-item">
            <a <?php if($tab=='4'){echo 'class="active"' ;}?> href="{{route($routeName.'.enquiries.index',['tab'=>4])}}&order={{$order}}">Activity</a>
        </li>
        <?php } ?>

        <?php if(CustomHelper::isOnlineBooking('hotel_listing') && CustomHelper::checkPermission('accommodations','view')){ ?>
        <li class="nav-item">
            <a <?php if($tab==2){echo 'class="active"' ;}?> href="{{route($routeName.'.enquiries.index',['tab'=>2])}}&order={{$order}}">Hotel</a>
        </li>
        <?php } ?>

        <?php if(CustomHelper::isAllowedModule('rental') && CustomHelper::checkPermission('bike_master','view')) { ?>
          <li class="nav-item">
            <a <?php if($tab==7){echo 'class="active"' ;}?> href="{{route($routeName.'.enquiries.index',['tab'=>7])}}&order={{$order}}">Rental</a>
          </li>
        <?php } ?>
        </ul>
        </div>


    <div class="searchtable">

      <div class="centersec" style="padding:6px;">
        @include('snippets.errors')
        @include('snippets.flash')
      </div>

      <form class="form-inline" method="GET">
         <input type="hidden" name="tab" class="form-control admin_input1" value="{{$tab}}">
        <div class="col-md-2{{$errors->has('name')?' has-error':''}}">
          <label>Name:</label><br/>
          <input type="text" name="name" class="form-control admin_input1" value="{{$name}}">
        </div>
        <div class="col-md-2{{$errors->has('phone')?' has-error':''}}">
          <label>Contact Number:</label><br/>
          <input type="text" name="phone" class="form-control admin_input1" value="{{$phone}}" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
        </div>
        <div class="col-md-2{{$errors->has('email')?' has-error':''}}">
          <label>Email ID :</label><br/>
          <input type="email" name="email" class="form-control admin_input1" value="{{$email}}">
        </div>
        <div class="col-md-2{{$errors->has('enquiry_for')?' has-error':''}}">
          <label for="FormControlSelect1">Enquiry For</label><br/>
          <select name="enquiry_for[]" id="customSelect1" class="form-control admin_input1 select2 myselect" multiple="multiple">
            <option value="">--Select Enquiry For--</option>
            @foreach($enq_for_types as $k => $v)
            <option value="{{$k}}" {{(!empty($enquiry_for) && in_array($k,$enquiry_for))?'selected':''}} >{{$v}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2{{$errors->has('destination')?' has-error':''}}">
          <label for="FormControlSelect1">Destination</label><br/>
          <select name="destination[]" id="customSelect2" class="form-control admin_input1 select2 myselect" multiple="multiple">
            <option value="">--Select Destination--</option>
            {!!CustomHelper::getDestinationOptions('', $destination)!!}
          </select>
        </div>
        <div class="col-md-2{{$errors->has('package_id')?' has-error':''}}">
          <label for="FormControlSelect1">Package</label><br/>
          <select name="package_id" class="form-control admin_input1 select2">
            <option value="">--Select Package--</option>
            @if($packages)
            @foreach($packages as $row)
            <option value="{{$row->id}}" {{($row->id==$package_id)?'selected':''}}>{{$row->title}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-2{{$errors->has('accommodation')?' has-error':''}}">
          <label for="FormControlSelect1">Accommodation</label><br/>
          <select name="accommodation[]" id="customSelect3" class="form-control admin_input1 select2 myselect" multiple="multiple">
            <option value="">--Select Accommodation--</option>
            @if($accommodations)
            @foreach($accommodations as $row)
            <?php $acc_location = $row->accommodationDestination->name??'';?>
            <option value="{{$row->id}}" {{(!empty($accommodation) && in_array($row->id,$accommodation))?'selected':''}}>{{$row->name}} ({{$acc_location}})</option>
            @endforeach
            @endif
          </select>
        </div>

        <div class="col-md-2{{$errors->has('lead_source')?' has-error':''}}">
          <label for="FormControlSelect1">Lead Source</label><br/>
          <select name="lead_source" class="form-control admin_input1 select2">
            <option value="">--Select Lead Source--</option>
            @if($lead_source_arr)
            @foreach($lead_source_arr as $row)
            <option value="{{$row->id}}" {{($row->id==$lead_source)?'selected':''}}>{{$row->name}}</option>
            @endforeach
            @endif
          </select>
        </div>

       <?php /*<div class="col-md-2{{$errors->has('lead_status')?' has-error':''}}">
          <label for="FormControlSelect1">Lead Status</label><br/>
          <select name="lead_status" id="lead_status" class="form-control admin_input1 select2">
            <option value="">--Select Lead Status--</option>
            @if($lead_status_arr)
            @foreach($lead_status_arr as $row)
            <option value="{{$row->id}}" {{($row->id==$lead_status)?'selected':''}}>{{$row->name}}</option>
            @endforeach
            @endif
          </select>
        </div> */ ?>
        
        <div class="col-md-2{{$errors->has('lead_status')?' has-error':''}}">
          <label for="FormControlSelect1">Lead Status</label><br/>
          <select name="lead_status" id="lead_status" class="form-control admin_input1 select2">
            <option value="">--Select Lead Status--</option>
            @if($lead_statuses)
            @foreach($lead_statuses as $row)
            <option value="{{$row->id}}" {{($row->id==$lead_status)?'selected':''}}>{{$row->description}}</option>
            @endforeach
            @endif
          </select>
        </div>

        <!-- {{--<div class="col-md-2{{$errors->has('lead_sub_status')?' has-error':''}}">
          <label for="FormControlSelect1">Lead Sub Status</label><br/>
          <select name="lead_sub_status" id="lead_sub_status" class="form-control admin_input1 select2">
            <option value="">--Select Lead Sub Status--</option>
          </select>
        </div>--}} -->

        <div class="col-md-2{{$errors->has('rating')?' has-error':''}}">
          <label for="FormControlSelect1">Rating</label><br/>
          <select name="rating" class="form-control admin_input1 select2">
            <option value="">--Select Rating--</option>
            @if($rating_arr)
            @foreach($rating_arr as $row)
            <option value="{{$row->id}}" {{($row->id==$rating)?'selected':''}}>{{$row->name}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <!-- {{--<div class="col-md-2{{$errors->has('status')?' has-error':''}}">
          <label for="FormControlSelect1">Status</label><br/>
          <select name="status" class="form-control admin_input1 select2">
            <option value="">--Select Status--</option>
            @foreach($enq_status as $k => $v)
            <option value="{{$k}}" {{($k==$status)?'selected':''}} >{{$v}}</option>
            @endforeach
          </select>
        </div>--}} -->
        
        <div class="col-md-4{{$errors->has('status')?' has-error':''}}">
          <label for="FormControlSelect1">Status</label><br/>
          <select name="status[]" id="customSelect4" class="form-control admin_input1 select2 myselect"  multiple="multiple">
            @foreach($lead_status_category_arr as $key => $val)
            <option value="{{$key}}" {{(!empty($lead_status_category_status) && in_array($key,$lead_status_category_status))?'selected':''}} >{{$val}}</option>
            @endforeach
          </select>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-2{{$errors->has('date_type')?' has-error':''}}" style="margin-top:10px;">
          <label for="FormControlSelect1">Search Date Type</label><br/>
          <select name="date_type" class="form-control admin_input1 date_type select2">
            <option value="">--Select Date Type--</option>
            @if($date_types)
            @foreach($date_types as $k => $v)
            <option value="{{$k}}" {{(!empty($date_type) && $date_type == $k)?'selected':''}}>{{$v}}
            </option>
            @endforeach
            @endif
          </select>
        </div>        

        <div class="col-md-2{{$errors->has('range')?' has-error':''}}{{(!empty($date_type))?'':' hide'}} rangeDiv" style="margin-top:10px;">
          <label for="FormControlSelect1">Range</label><br/>
          <select name="range" class="form-control admin_input1 range select2">
            <option value="">--Select Range--</option>
            @foreach($range_filters as $k => $v)
            <option value="{{$k}}" {{(!empty($range) && $range == $k)?'selected':''}}>{{$v}}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2{{$errors->has('from')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv" style="margin-top:10px;">
          <label for="FormControlSelect1">From Date</label><br/>
          <input type="text" name="from" class="form-control from_date" placeholder="From Date"
          value="{{$from}}" autocomplete="off">
        </div>
        <div class="col-md-2{{$errors->has('to')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv" style="margin-top:10px;">
          <label for="FormControlSelect1">To Date</label><br/>
          <input type="text" name="to" class="form-control to_date" placeholder="To Date"
          value="{{$to}}" autocomplete="off">
        </div>
        
       


        <div class="col-md-8 search-block">
          <br>
          <button type="submit" name="pending_enquiries" value="pending_enquiries" class="btn btn-info" title="Pending Enquiries" @if($pending_enquiries == 'pending_enquiries') id="active" @endif> <i class="la la-trash"></i> Pending Enquiries</button>
          <button type="submit" name="tomorrow_later" value="tomorrow_later" class="btn btn-info" title="Tomorrow & Later" @if($tomorrow_later == 'tomorrow_later') id="active" @endif> <i class="la la-trash"></i> Tomorrow & Later</button>
          <button type="submit" name="all_enquiries" value="all_enquiries" class="btn btn-info" title="All Enquiries" @if($all_enquiries == 'all_enquiries') id="active" @endif> <i class="la la-trash"></i> All Enquiries</button>
          <button type="submit" class="btn btn-success">Search</button>
          <a href="{{route($routeName.'.enquiries.index')}}" class="btn_admin2">Reset</a>
        </div>
        <div class="clearfix"></div>
      </form>
      </div>
   </div>
</div>


<!-- End Search box Section -->
<div class="container enquiries-list">
   <div class="row">
      <!-- <h5 class="title-text-left">Enquiries List</h5> -->
      <?php if(!empty($enquiries) && $enquiries->count() > 0){?>
      <div class="col-md-12 table-responsive">         
         <table id="datatable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
               <tr>
                  <!-- <th>Enquiry ID<hr />{{--Form Name--}} Referal Url<hr />{{--Form Name--}} Enquiry Date</th> -->
                  <th>Enquiry Date</th>
                  <th>Enquiry For</th>
                  <th>
                    Name /
                    Contact Number /
                    Email ID
                  </th>
                  <th>Destination</th>
                  <th>Package/Activity</th>
                  <th>Accommodation</th>
                  <th>Lead Source</th>
                  {{--<th>Lead Status<hr />Lead Sub Status</th>--}}
                  <th>Lead Status</th>
                  <th>Rating</th>
                  <th>Followup Date</th>
                  <!-- <th>Enquiry Date</th> -->
                  <th>Assign</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
              <?php foreach($enquiries as $enquiry) {

              $UserData = $enquiry->UserData ?? [];
              $is_agent = $UserData ? $UserData->is_agent : 0;

              ?>
               <tr id="row-{{$enquiry->id}}" <?php if($enquiry->viewed == 0){echo 'class="unread"';} ?> data-id="{{$enquiry->id}}" >
                  <td>
                    <?php /*{{$enquiry->enquiry_no_id}}<hr />
                    @if($enquiry->order_id)
                    Order Enquiry
                    @else
                    {{--{{$enquiry->Form->name??''}}--}}
                    {{$enquiry->refer_url}}<hr/> */?>
                    {{CustomHelper::DateFormat($enquiry->created_at,'d/m/Y','Y-m-d')}}
                    <?php /*@endif */?>
                  </td>
                  <td>
                    <?php
                    $enquiry_for = $enquiry->EnquiryForType->pluck('enquiry_for_id')->toArray();
                    if(!empty($enquiry_for)) {
                      $enquiry_for_values = CustomHelper::showEnquiryForType($enquiry_for);
                      if($enquiry_for_values) {
                        echo implode(', ', $enquiry_for_values);
                      }
                    }
                    ?>
                  </td>
                  <td class="user_icon_top">
                    {{$enquiry->name}} <br />
                    {{$enquiry->phone}} <br />
                    {{$enquiry->email}}
                    <?php if($is_agent == 1){ ?>
                    <div class="tooltip_wrap" position="bottom" arrow>
                      <div class="tooltip_btn"><img style="width: 25px;" class="img-responsive" src="/images/admin-agent-agency.png"/></div>
                      <div class="tooltip_content">
                          <ul class="item-list">
                            <li>Name: {{$UserData->name??''}}</li>
                            <li>Email: {{$UserData->email??''}}</li>
                          </ul>
                      </div>
                    </div>
                    <?php } ?>
                  </td>
                  
                  <td>
                    <?php
                    $destination_ids = $enquiry->Destination->pluck('destination_id')->toArray();
                    if(!empty($destination_ids)) {
                      $destination_values = CustomHelper::showDestination($destination_ids);
                      if($destination_values) {
                        echo implode(', ', $destination_values);
                      }
                    }
                    ?>
                    <hr />
                    <?php
                    $destination_ids = $enquiry->SubDestination->pluck('destination_id')->toArray();
                    if(!empty($destination_ids)) {
                      $destination_values = CustomHelper::showDestination($destination_ids);
                      if($destination_values) {
                        echo implode(', ', $destination_values);
                      }
                    }
                    ?>
                  </td>
                  <td>{{$enquiry->package_name??''}}<?php /*{{$enquiry->Package->title??''}}*/ ?></td>
                  <td>
                    <?php
                    $accommodation_ids = $enquiry->Accommodation->pluck('accommodation_id')->toArray();
                    if(!empty($accommodation_ids)) {
                      $accommodation_values = CustomHelper::showAccommodation($accommodation_ids);
                      if($accommodation_values) {
                        echo implode(', ', $accommodation_values);
                      }
                    }
                    ?>
                  </td>
                  <td>{{CustomHelper::showEnquiryMaster($enquiry->lead_source??'')}}</td>
                  {{--<td>{{CustomHelper::showEnquiryMaster($enquiry->lead_status??'')}}<hr />{{CustomHelper::showEnquiryMaster($enquiry->lead_sub_status??'')}}</td>--}}
                  <td>{{CustomHelper::showEnquiryMaster($enquiry->lead_status??'')}}</td>

                  <td style="color: {{$enquiry->GetColor->color_code ?? ''}};" >{{CustomHelper::showEnquiryMaster($enquiry->rating??'')}}</td>
                  <td>
                    @if($enquiry->followup_date)
                    <!-- <i class="fa fa-calendar"></i> -->
                    {{CustomHelper::DateFormat($enquiry->followup_date,'d/m/Y','Y-m-d')}}
                    @endif
                  </td>
                  <?php /*<td><i class="fa fa-calendar"></i> {{CustomHelper::DateFormat($enquiry->created_at,'d/m/Y','Y-m-d')}}</td> */?>
                  <td>
                    <div class="users">
                      
                      @if($enquiry->cc_id)
                      <button class="show-1-yes show_yes" data-id="{{$enquiry->id}}" style="text-decoration: underline;color: #00b1a7;">{{CustomHelper::showAdmin($enquiry->cc_id)}}</button>
                      @if(CustomHelper::checkPermission('enquiries','assign'))
                      <button class="hide-1-yes hide_yes" data-id="{{$enquiry->id}}"><i class="fa fa-minus-circle"></i></button>
                      @endif
                      @else
                      @if(CustomHelper::checkPermission('enquiries','assign'))
                      <button class="show-1-yes show_yes" data-id="{{$enquiry->id}}"><i class="fa fa-plus-circle"></i></button>
                      <button class="hide-1-yes hide_yes" data-id="{{$enquiry->id}}"><i class="fa fa-minus-circle"></i></button>
                      @endif
                      @endif
                      <div id="container-target">
                        <div id="target-{{$enquiry->id}}"  class="user_menu">
                        </div>
                    </div>
                  </div>                    
                </td>
                <td>
                    <div class="users-detail">
                        <?php /*<a href="javascript:void(0);" data-src="{{route('admin.enquiries.view',[$enquiry['id'], 'back_url'=>$BackUrl])}}" title="View" data-fancybox data-type="ajax"><i class="fas fa-eye"></i></a> */ ?>
                        @if(CustomHelper::checkPermission('enquiries','view'))
                        <a href="{{route('admin.enquiries.view',[$enquiry['id'], 'back_url'=>$BackUrl])}}" title="View" data-fancybox data-type="ajax" class="view_enquiry"><i class="fas fa-eye"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('enquiries','edit'))
                        <a href="javascript:void(0);" data-src="{{route('admin.enquiries.edit',[$enquiry['id'], 'back_url'=>$BackUrl])}}" title="Edit Enquiry" data-fancybox data-type="ajax"><i class="fas fa-edit"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('enquiries','delete'))
                        <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$enquiry->id}}"><i class="fas fa-trash-alt"></i></a>
                        <form method="POST" action="{{ route('admin.enquiries.delete', $enquiry->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Enquiry?');" id="delete-form-{{$enquiry->id}}">
                          {{ csrf_field() }}
                          {{ method_field('POST') }}
                          <input type="hidden" name="enquiry_id" value="<?php echo $enquiry->id; ?>">
                        </form>
                        @endif

                        <button class="button-typ" data-id="{{$enquiry->id}}">Details <i class="fa fa-angle-down"></i></button>
                    </div>
                </td>
               </tr>
               @include('admin.enquiries._last_interaction', ['enquiry' => $enquiry])
               <tr id="row-details{{$enquiry->id}}">
                  <td colspan="12">
                    <div class="content-class">
                      <?php /*@include('admin.enquiries._interactions', ['enquiry' => $enquiry])*/ ?>
                    </div>
                  </td>
               </tr>
              <?php } ?>
            </tbody>
            <tr>
              <td colspan="12">{{ $enquiries->appends(request()->query())->links('vendor.pagination.default') }}</td>
            </tr>
         </table>
         
      <?php } else{ ?>
        <div class="alert alert-warning">There are no Records at the present.</div>
      <?php } ?>
      </div>
      
   </div>
</div>

@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
$('.select2').select2({ placeholder: "Please Select", allowClear: true });
// ============== Select Box Start ============

$('.myselect').select2({closeOnSelect: true,  placeholder: "Please Select",}).on("change", function(e) {

var counter = $(this).next('.select2-container').find(".select2-selection__choice").length;
if(counter > 2){
  if($(this).next('.select2-container').find('.counter').length <= 0){
    $(this).next('.select2-container').find('.select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter"> ('+counter+' selected)</div>');
  }else{
    $(this).next('.select2-container').find('.counter').text(`(${counter} selected)`);
  }
}else{
  $(this).next('.select2-container').find('.counter').remove();
}
});

$('.myselect').each(function(){
  var counter = $(this).next('.select2-container').find(".select2-selection__choice").length;
  if(counter > 2){
    if($(this).next('.select2-container').find('.counter').length <= 0){
      $(this).next('.select2-container').find('.select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter"> ('+counter+' selected) </div>');
    }else{
      $(this).next('.select2-container').find('.counter').text(`(${counter} selected)`);
    }
  }else{
    $(this).next('.select2-container').find('.counter').remove();
  }
})

// ============== Select Box End ============

  $(function(){
    $('.enq-view').ready(function() {
      // $('.select2').select2({
      //   placeholder: "Please Select",
      //   allowClear: true
      // });
    });

    $("#followup_date").datepicker({
      'dateFormat':'dd/mm/yy'
    });

    $( ".to_date, .from_date" ).datepicker({
      dateFormat:'dd/mm/yy',
      changeMonth: true, 
      changeYear: true,
      dateFormat: "dd/mm/yy",
      yearRange: "-90:+01" 

    });

    $('.date_type').on('change', function() {
      if (this.value == '') {
        $('.rangeDiv').addClass('hide');
        $('select[name=range]').val('');
        $('input[name=from]').val('');
        $('input[name=to]').val('');
      } else {
        $('.rangeDiv').removeClass('hide');
      }
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

    $(document).on('click','.view_enquiry',function(event) {
      var enquiryElement = $(this).parent().parent().parent();
      if(enquiryElement && enquiryElement.hasClass('unread')) {
        setTimeout(function(){
          enquiryElement.removeClass('unread');
          var enquiry_id = enquiryElement.attr('data-id');
          ajax_enquiry_last_interaction(enquiry_id);
        },1500);
      }
    });
  });
  /*  show 1 - hide 1  */
  /*$('.show-1-yes').click(function() {
    $('#target-1').show(500);
    $('.show-1-yes').hide(0);
    $('.hide-1-yes').show(0);
  });
  $('.hide-1-yes').click(function() {
    $('#target-1').hide(500);
    $('.show-1-yes').show(0);
    $('.hide-1-yes').hide(0);
  });*/

  /**  // user content button toggle **/
  $( document ).ready(function() {
    // content button toggle
    $(".content-class").hide();
    $(".button-typ").click( function(){
      //$(this).text( $(this).text() === "Open Article +" ? "Details ⌄" : "Open Article +" );
      var enquiry_id = $(this).attr('data-id');
      ajax_enquiry_interaction(enquiry_id);
    });    

    $(".show_yes").click(function() {
      var enquiry_id = $(this).attr('data-id');
      ajax_enquiry_users(enquiry_id);
    });

    $(".hide_yes").click(function() {
      var enquiry_id = $(this).attr('data-id');
      $("#target-"+enquiry_id).hide(500);
      $("#target-"+enquiry_id).parent().parent().find('.show_yes').show(0);
      $("#target-"+enquiry_id).parent().parent().find('.hide_yes').hide(0);
    });

    $(document).on('keyup','.search_assign',function(){
      var listItems = $(this).parent().find('ul li');
      listItems.show();
      var search_assign = $(this).val();
      if(search_assign) {
        search_assign = search_assign.toLowerCase();
        listItems.each(function(idx, li) {
          var user = $(li);
          var name = user[0].innerText;
          if(name) {
            name = name.toLowerCase();
            if(name.indexOf(search_assign) != -1) {
              //console.log(search_assign + " found");
            } else {
              user.hide();
            }
          } else {
            user.hide();
          }
        });        
      }
    });
  });
  $('.sbmtDelForm').click(function(){
    var id = $(this).attr('id');
    $("#delete-form-"+id).submit();
  });

  function ajax_enquiry_last_interaction(enquiry_id) {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      'url' : '<?php echo route($routeName.'.enquiries.ajax_enquiry_last_interaction'); ?>', 
      'type' : 'post',
      'dataType' : 'json',
      'data' : {enquiry_id:enquiry_id},
      'cache' : false,
      'async' : false,
      'headers' : {'X-CSRF-TOKEN': _token},
      beforeSend: function() {
        $("#last_interaction-"+enquiry_id).empty();
      },
      success: function(response) {
        if(response.success) {
          $("#last_interaction-"+enquiry_id).remove();
          $("#row-"+enquiry_id).after(response.htmlData);
        } else if(response.msg) {
          alert(response.msg);
        }
      },
    });
  }

  function ajax_enquiry_interaction(enquiry_id) {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      'url' : '<?php echo route($routeName.'.enquiries.ajax_enquiry_interaction'); ?>', 
      'type' : 'post',
      'dataType' : 'json',
      'data' : {enquiry_id:enquiry_id},
      'cache' : false,
      'async' : false,
      'headers' : {'X-CSRF-TOKEN': _token},
      beforeSend: function() {
        $("#row-details"+enquiry_id+" td .content-class").empty();
      },
      success: function(response) {
        if(response.success) {
          $("#row-details"+enquiry_id+" td .content-class").html(response.htmlData);
          $("#row-details"+enquiry_id+" td .content-class").slideToggle(900);
          $("#interaction_followup_date").datepicker({
            'dateFormat':'dd/mm/yy',
            changeMonth:true,
            changeYear:true,
            minDate:0
          });
        } else if(response.msg) {
          alert(response.msg);
        }
      },
    });
  }

  function validate_interaction(enquiry_id) {
    $(".validation_error").remove();
    $(".flash-message").remove();
    var status = false;
    $.ajax({
      'url' : '<?php echo route($routeName.'.enquiries.add_interaction'); ?>', 
      'type' : 'post',
      'dataType' : 'json',
      'data' : $("#interaction_form"+enquiry_id).serialize(),
      'cache' : false,
      'async' : false,
      beforeSend: function(){
        $("#interaction_form"+enquiry_id+" input").css('border','1px solid #ccc');
        $("#interaction_form"+enquiry_id+" select").css('border','1px solid #ccc');
      },
      success: function(response){
        if(response.success) {
          if(response.htmlData) {
            if($('#row-'+enquiry_id).find('#last_interaction-'+enquiry_id)) {
              $('#last_interaction-'+enquiry_id).remove();
            }
            $('#row-'+enquiry_id).after(response.htmlData);
          }
          $("#row-details"+enquiry_id+" td .content-class").html(response.htmlData1);
          $("#interaction_followup_date").datepicker({'dateFormat':'dd/mm/yy',minDate:0});
          $("#interaction_form"+enquiry_id+" #interaction_message").after('<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Interaction added successfully.</div></div>');
        } else {
          if(response) {
            parseInteractionErrorMessages(enquiry_id, response);
          }
        }
      },
      error: function(e) {
        var response = e.responseJSON;
        if(response) {
          parseInteractionErrorMessages(enquiry_id, response);
        }
      }
    });
    return status;
  }

  function parseInteractionErrorMessages(enquiry_id, response) {
    if(response) {
      if(response.msg) {
        $("#interaction_form"+enquiry_id+" #interaction_message").after('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.msg+'</div></div>');
      } else {
        $("#interaction_form"+enquiry_id+" #interaction_message").after('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Invalid request, please check errors below!</div></div>');
      }
      if(response.errors) {
        $.each(response.errors, function(i, item) {
          $("#interaction_form"+enquiry_id+" select[name='"+i+"']").css('border','1px solid #ff0000');
          $("#interaction_form"+enquiry_id+" select[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');

          $("#interaction_form"+enquiry_id+" input[name='"+i+"']").css('border','1px solid #ff0000');
          $("#interaction_form"+enquiry_id+" input[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');
        });
      }
    }
  }

  $(document).on("change","#lead_status", function(){
    var lead_status = $(this).val();
    ajax_enquiries_master(lead_status,'','lead_sub_status');
  });

  <?php if($lead_status) { ?>
  ajax_enquiries_master('<?php echo $lead_status; ?>','<?php echo $lead_sub_status; ?>','lead_sub_status');
  <?php } ?>

  $(document).on("change","#interaction_lead_status", function(){
    var lead_status = $(this).val();
    ajax_enquiries_master(lead_status,'','interaction_lead_sub_status');
  });

  function ajax_enquiries_master(parent_id, selected_id, field_name="lead_sub_status", field_label="Lead Sub Status") {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      'url' : '<?php echo route($routeName.'.enquiries_master.ajax_enquiries_master'); ?>', 
      'type' : 'post',
      'dataType' : 'json',
      'data' : {parent_id:parent_id},
      'cache' : false,
      'async' : false,
      'headers' : {'X-CSRF-TOKEN': _token},
      beforeSend: function() {
        $("#"+field_name).empty();
        $("#"+field_name).append('<option value="">Select '+field_label+'</option>');
      },
      success: function(response) {
        if(response.success) {
          if(response.enquiries_master) {
            $.each(response.enquiries_master, function(index, row) {
              $("#"+field_name).append('<option value="'+row.id+'">'+row.name+'</option>');
            });
            if(selected_id) {
              $("#"+field_name).val(selected_id);
            }
          }
        } else {
          if(response.msg) {
            alert(response.msg);
          }
        }
      },
    });
    return status;
  }

  function ajax_enquiry_users(enquiry_id) {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      'url' : '<?php echo route($routeName.'.enquiries.ajax_enquiry_users'); ?>', 
      'type' : 'post',
      'dataType' : 'json',
      'data' : {enquiry_id:enquiry_id},
      'cache' : false,
      'async' : false,
      'headers' : {'X-CSRF-TOKEN': _token},
      beforeSend: function() {
        $("#target-"+enquiry_id).empty();
      },
      success: function(response) {
        if(response.success) {
          $("#target-"+enquiry_id).html(response.htmlData);
          $("#target-"+enquiry_id).show(500);
          $("#target-"+enquiry_id).parent().parent().find('.show_yes').hide(0);
          $("#target-"+enquiry_id).parent().parent().find('.hide_yes').show(0);
        } else if(response.msg) {
          alert(response.msg);
        }
      },
    });
  }

</script>
@endslot

@endcomponent