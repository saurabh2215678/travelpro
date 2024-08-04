@component('admin.layouts.main')
@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
<style>
   .inline-markup span {
   padding-right: 5px;
   display: inline-block;
   margin-bottom: 10px;
   }
   .form-group.inline-markup {
   margin-bottom: 0;
   }
   .tooltip {
      position: relative;
    display: inline-block;
    border-bottom: 2px dotted #FF5722;
    opacity: 1;
    font-style: normal;
    color: #FF5722;
    font-size: 15px;
    margin-right: 7px;
    cursor: pointer;
}
.tooltip .tooltiptext {
   visibility: hidden;
   min-width: 80px;
    background-color: #ffffff;
    color: #000;
    text-align: center;
    border-radius: 0;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 0;
    font-size: 14px;
    box-shadow: 2px 4px 15px #00000070;
}
.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>
<?php
   $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
   $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
   $id = (isset($record->id))?$record->id:'';
   $fare_type = (isset($record) && isset($record->fare_type))?$record->fare_type:'1';
   $status = (isset($record->status))?$record->status:1;
   $markup = (isset($record->markup))?$record->markup:'';
   $markup_type = (isset($record->markup_type))?$record->markup_type:0;
   $admin_markup = (isset($admin_markup))?$admin_markup:0;
   ?>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">
   <div class="col-md-6">
      <h2 class="top_title_admin enquiries-top">{{ $page_heading }}</h2>
   </div>
   <div class="col-md-6">
      <?php if($back_url){ ?>
      <a href="{{url($back_url)}}" class="btn_admin btn btn-success btn-sm" style="float: right;">Back</a>
      <?php } else { ?>
      <a href="{{route($ADMIN_ROUTE_NAME.'.airline.offline_inventory')}}" class="btn_admin btn btn-success btn-sm" style="float: right;" >Back</a>
      <?php } ?>
   </div>
</div>
<div class="row top_title_admin enquiries-top centersec" style="min-height: auto; padding-left:0; padding-right:0;">
   <div class="col-md-12">
      <div class="bgcolor">
         @include('snippets.errors')
         @include('snippets.flash')
         <div class="ajax_msg"></div>
         <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
               @if($fare_type==2)
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Agent Name:</label> <br />
                     {{$record->userData->name??'System'}}
                  </div>
               </div>
               @endif
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Departure Date:</label> <br />
                     <strong>{{CustomHelper::DateFormat(($record->start_date??''),'d/m/Y')}}</strong>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Departure / Arrival Time:</label> <br />
                     {{CustomHelper::DateFormat($record->routeData->start_time,'h:i A')}} /
                     {{CustomHelper::DateFormat($record->routeData->end_time,'h:i A')}}
                     @if($record->routeData->is_arrival_next_day)
                     (Arrival Next Day)
                     @endif
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Source / Destination:</label> <br />
                     {{$record->routeData->source??''}} / {{$record->routeData->destination??''}}
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Airline / Flight No:</label> <br />
                     {{$record->routeData->airline??''}}-{{$record->routeData->flight_number??''}}
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">PNR / Class / Stops:</label> <br />
                     {{$record->pnr??''}} / {{CustomHelper::showCabinClass($record->flight_class??'')}} / {{$record->routeData->stops??''}} Stop(s)
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Refundable:</label> <br />
                     <?php if($record->is_refundable == 1){ ?>
                     <i class="" style="color:green">Yes</i>
                     <?php }else{ ?>
                     <i class="" style="color:red">No</i>
                     <?php } ?>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Tickets In Stock:</label> <br />{{$record->initial_inventory??''}}
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Tickets for Sale:</label> <br />{{$record->inventory??''}}
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Tickets Sold:</label> <br />
                     {{$record->inventorySoldCount()??''}}
                  </div>
               </div>
               <div class="clearfix"></div>
               @if($fare_type==2)
               <div class="col-md-4 price_wrapper">
                  <div class="form-group">
                     <div class="row">
                        <div class="col-md-12">
                           <label for="end_time" class="control-label labeltxt">Agent Actual Price:</label>
                        </div>                        
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Adult:</label> <br />
                              {{CustomHelper::getPrice($record->agent_adult_price)??''}}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Child:</label> <br />
                              {{CustomHelper::getPrice($record->agent_child_price)??''}}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Infant:</label> <br />
                              {{CustomHelper::getPrice($record->agent_infant_price)??''}}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4 price_wrapper">
                  <div class="form-group">
                     <div class="row">
                        <div class="col-md-12">
                           <label for="end_time" class="control-label labeltxt">Agent Selling Price:</label>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Adult:</label> <br />
                              {{CustomHelper::getPrice($record->adult_price)??''}}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Child:</label> <br />
                              {{CustomHelper::getPrice($record->child_price)??''}}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Infant:</label> <br />
                              {{CustomHelper::getPrice($record->infant_price)??''}}
                           </div>
                        </div>                        
                     </div>
                  </div>
               </div>
               @endif

               <div class="col-md-4 price_wrapper">
                  <div class="form-group">
                     <div class="row">
                        <div class="col-md-12">
                           <label for="end_time" class="control-label labeltxt">Admin Price:</label>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Adult:</label> <br />
                              {{CustomHelper::getPrice($record->admin_adult_price)??''}}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Child:</label> <br />
                              {{CustomHelper::getPrice($record->admin_child_price)??''}}
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="control-label">Infant:</label> <br />
                              {{CustomHelper::getPrice($record->admin_infant_price)??''}}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label class="control-label">Remark:</label> <br />
                     {!!$record->remark!!}
                  </div>
               </div>
               <div class="clearfix"></div>
               <div class="col-md-6">
                  <div class="form-group{{ $errors->has('markup') ? ' has-error' : '' }} inline-markup">
                     <label for="markup" class="control-label">Markup <span style="color:#b55603;">(Custom Markup will be on fixed basis only) :</span></label> <br />
                     <span>Default <span style="color:#b55603;font-weight: 600;">({{($admin_markup_details['markup_type']=='fixed')?CustomHelper::getPrice($admin_markup_details['markup_value']):$admin_markup_details['markup_value']}} {{ucfirst($admin_markup_details['markup_type'])}}) :</span> <input type="radio" name="markup_type" value="0" {{($markup_type=='0')?'checked':''}} > {{CustomHelper::getPrice($admin_markup)}}</span>
                     <span>Custom: <input type="radio" name="markup_type" value="1" {{($markup_type=='1')?'checked':''}} ></span>
                     <span><input type="text" name="markup" class="form-control" value="{{old('markup',$markup)}}"></span>
                  </div>
               </div>
               <div class="clearfix"></div>
               <div class="col-md-4">
                  <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                     <label for="status" class="control-label">Status:</label>
                     Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                     &nbsp;
                     Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                     @include('snippets.errors_first', ['param' => 'status'])
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <input type="hidden" name="back_url" value="{{ $back_url }}" >
                     <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>
                  </div>
               </div>
            </div>
      </div>
   </div>
   </form>
</div>
</div>
<div class="clearfix"></div>
</div>
@slot('bottomBlock')
<script type="text/javascript">
   $(document).ready(function() {
       show_markup('<?php echo $markup_type; ?>');
   })
   $('input[name=markup_type]').on('click',function() {
       var markup_type = $(this).val();
       show_markup(markup_type);
   });
   function show_markup(markup_type) {
       if(markup_type == 1) {
           $('input[name=markup]').show();
       } else {
           $('input[name=markup]').hide();
       }
   }    
</script>
@endslot
@endcomponent