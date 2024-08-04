@component('admin.layouts.main')

    @slot('title')
        Admin - Settings (Package) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link href="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/core/main.css" rel="stylesheet" />
<link href="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/daygrid/main.css" rel="stylesheet" />
<link href="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/timegrid/main.css" rel="stylesheet" />
<link href="https://cdn.syncfusion.com/ej2/21.1.35/material.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<!-- Include Date Range Picker -->
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@endslot
<style>
    .right-bar.d-flex {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-bottom: 15px;
}
.bulk-btn button {
    padding: 7px 14px;
}
.bulk-btn span {
    padding: 10px;
    border: 1px solid #ddd;
    font-size: 20px;
    color: #5e5e5e;
    border-radius: 5px;
    cursor: pointer;
}
.dateRow_list tbody tr td, .dateRow_list tbody tr th {
    padding:5px;
    border-left: 1px solid #ddd !important;
    border: 0;
    text-align: center;
    background: #efefef;
    text-transform: uppercase;
}
.dateRow_list tbody tr td.active, .dateRow_list tbody tr th.active {
    background: #009688;
    color: #fff;
    text-align:center;
}
.dateRow_list tbody tr td.months {
    text-transform: uppercase;
}
.dateRow_list tbody.date_input_box tr td {
    background: #fff;
    border: 0 !important;
}
.dateRow_list tbody.date_input_box tr td input {
    text-align: center;
    color: green;
}
.dateRow_list tbody.date_input_box tr td input.sold {
    color: #ef5f57;
}
.dateRow_list tbody.date_input_box tr td input::placeholder {
    text-align: center;
}
tbody.date_input_box {
    border: 0 !important;
}
.dateRow_list tbody.date_input_box label.label-text {
    font-size: 11px;
    color: #838383;
    font-weight: 300;
    text-transform: none;
}


/* modal details */
.modal.modal-wide .modal-dialog {
  width: 50%;
}
.modal-wide .modal-body {
  overflow-y: auto;
}
.dateselect {
    background: #eef1ff;
    padding: 15px;
    margin-bottom: 10px;
}
.dateselect input {
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
}
.dateselect span.btn_date {
    position: relative;
}
.dateselect i {
    position: absolute;
    right: 6px;
    top: 0px;
    font-size: 18px;
    color: #979797;
}

</style>
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$counter = 0;
$active_menu = "packages".$package_id.'/departure';
$packagePrices = $package->packagePrices??[];
$filter_type = (request()->has('f'))?request()->f:'up';
$xArray = [];
?>
@if(!empty($package_id))
    @include('admin.includes.packageoptionmenu')
@endif


<section>
   <div class="col-md-12">
      <div class="col-md-6">
      </div>
      <div class="col-md-6">
         <div class="right-bar d-flex">
            <div class="btn">
               <a data-toggle="modal" href="#bulkModal" class="btn_admin2">Bulk Update</a>
            </div>
                <!-- Modal -->
                <div id="bulkModal" class="modal modal-wide fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Bulk Update</h4>
                        </div>
                        <div class="modal-body">
                        
                        <div class="dateRow_list">
                        <div class="dateselect"><span class="btn_date"><i class="fa fa-calendar"></i><input type="text" name="bulkdaterange" value="" /></span>
                        </div>
                          <table class="table table-borderless">
                           <tbody class="date_input_box">
                                <tr>
                                    <td style="width: 150px;vertical-align: middle;">Luxury</td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">1 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">0 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control sold" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">3 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">4 Sold</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;vertical-align: middle;">Standard</td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control sold" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">1 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">0 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">3 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date sold">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">4 Sold</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                    </div>
</div>
            <div class="bulk-btn">
               <span class="btn_left"><i class="fa fa-chevron-left"></i></span>
               <span class="btn_date daterange"><i class="fa fa-calendar"></i><!--<input type="text" name="daterange" value="01/01/2015 - 01/31/2015" />--></span>
               <span class="btn_right"><i class="fa fa-chevron-right"></i></span>
            </div>
         </div>
      </div>
   </div>
   <div class="dateRow">
      <div class="col-md-12">
         <div class="dateRow_list d-flex">
          <table class="table table-borderless">
            <tbody>
                <tr>
                    <th></th>
                    <th class="active week">Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                    <th>Sun</th>
                </tr>
                <tr>
                <td style="width: 150px;"><i class="fa fa-3x fa-calendar"></i></td>
                    <td class="active date">10</td>
                    <td>11</td>
                    <td>12</td>
                    <td>13</td>
                    <td>14</td>
                    <td>15</td>
                    <td>16</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="active months">April</td>
                    <td>Apr</td>
                    <td>Apr</td>
                    <td>Apr</td>
                    <td>Apr</td>
                    <td>Apr</td>
                    <td>Apr</td>
                </tr>
            </tbody>
            <tbody class="date_input_box">
                <tr>
                    <td style="width: 150px;vertical-align: middle;">Luxury</td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">2 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">1 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">0 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">2 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control sold" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">3 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">2 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">4 Sold</label>
                    </td>
                </tr>
                <tr>
                    <td style="width: 150px;vertical-align: middle;">Standard</td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">2 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control sold" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">1 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">0 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">2 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">3 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date sold">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">2 Sold</label>
                    </td>
                    <td style="width: 100px;" class="date">
                     <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                     <label for=" " class="label-text">4 Sold</label>
                    </td>
                </tr>
            </tbody>
            </table>
         </div>
         <div class="btn pull-right">
               <a class="btn_admin2" href="#">Save</a>
            </div>
   </div>
</section>















    <div class="top_title_admin">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <?php /* @if($package->tour_type=='group' && CustomHelper::checkPermission('packages','add')) */ ?>
        @if($package->tour_type!='open' && CustomHelper::checkPermission('packages','add') && (!empty($packagePrices) && count($packagePrices) > 0))
        <div class="add_btn">
            <a href="javascript:void(0);" class="btn_admin" data-toggle="modal" data-target="#viewFullCalModal" ><i class="fa fa-plus-circle"></i> Add Bulk Dates</a>
        </div>
        @endif
    </div>

    

    <div class="centersec">
        <div class="bgcolor">
            <div class="page_btns">
                <a <?php if($filter_type=="up"){echo 'class="active"';}?> href="{{route($routeName.'.packages.departure',['package_id'=>$package_id, 'f'=>'up']) }}" title="Upcoming"><i class="fa fa-arrow-circle-up" style="font-size:16px"></i>Upcoming</a>
                <a <?php if($filter_type=="ex"){echo 'class="active"';}?>  href="{{route($routeName.'.packages.departure',['package_id'=>$package_id, 'f'=>'ex']) }}" title="Expired"><i class="fa fa-arrow-circle-down" style="font-size:16px"></i>Expired</a>
            </div>

            @include('snippets.errors')
            @include('snippets.flash')

            <?php /* @if($package->tour_type=='group') */ ?>
            @if($package->tour_type!='open')
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data" id="TravelDatesForm">
                {{ csrf_field() }}
                <div class="ajax_msg"></div>
                @if(!empty($packagePrices) && count($packagePrices) > 0)
                <div class="table-responsive col-md-12">
                    <div id="more_elements">
                        <div class="row" id="row{{$counter}}">
                          <div class="col-sm-2">
                            <label>From Date</label>
                          </div>
                          <div class="col-sm-2">
                            <label>To Date</label>
                          </div>
                          @foreach($packagePrices as $pp)
                          <div class="col-sm-2">
                            <label>{{$pp->title??''}}</label>
                          </div>
                          @endforeach
                          <div class="col-sm-1">
                            <label>Action</label>
                          </div>
                        </div>
                        @if($package_departures && count($package_departures) > 0)
                            @foreach($package_departures as $pd)
                                @php
                                    $counter++; 
                                    $xArray[]= date('m/d/Y',strtotime($pd->from_date));
                                @endphp
                                @include('admin.packages._departure_row',['packagePrices'=>$packagePrices,'row'=>$pd,'counter'=>$counter])
                            @endforeach
                        @else
                            @php $counter++; @endphp
                            @include('admin.packages._departure_row',['packagePrices'=>$packagePrices,'row'=>[],'counter'=>$counter])
                        @endif
                            <input type="hidden" name="removed_ids" id="removed_ids" value="">

                        
                    </div>
                    @if(CustomHelper::checkPermission('packages','add'))
                    <?php if($filter_type=="up"){?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="add_more pull-right">+ Add More</div>
                        </div>
                    </div>
                    <div class="row pb-20">
                        <div class="col-sm-12">                        
                            <button type="submit" class="btn btn-success" title="Submit" id="TravelDatesFormBtn"><i class="fa fa-save"></i> Submit</button>
                        </div>
                    </div>
                    <?php } ?>
                    @endif
                </div>
                @else
                <div class="alert alert-warning">Please add Accommodation & Package Price first!</div>
                @endif
            </form>
            @else
            <!-- <div class="alert alert-warning">This feature is only available for Group Tour</div> -->
            <div class="alert alert-warning">This feature is not available for Open Tour</div>
            @endif
        </div>

    </div>

<!-- Modal -->
<div class="modal fade" id="viewFullCalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add/Update Bulk Date of Travel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div id="full_calender"></div>
      </div>
      
    </div>
  </div>
</div>

    @slot('bottomBlock')
    <!-- Include Date Range Picker -->
    
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script src="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/core/main.js"></script>
    <script src="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/interaction/main.js"></script>
    <script src="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/daygrid/main.js"></script>
    <script src="{{asset('assets')}}/admin/fullcalendar-4.1.0/packages/timegrid/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>


    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    
        <script>
            jQuery.support.cors = true;
            if ($("#TravelDatesForm").length > 0) {
                $("#TravelDatesForm").validate({
                    submitHandler: function(form) {
                        $("#TravelDatesFormBtn").html(
                            'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
                            );
                        $("#TravelDatesFormBtn").attr("disabled", true);
                        form.submit();
                    }
                })
            }
        </script>

    <script type="text/javascript">

// ========== daterangepicker ============
   $('.daterange').daterangepicker({
    locale: {
            format: 'DD/MM/YYYY'
        }
   });

   $('input[name="bulkdaterange"]').daterangepicker(
    {
    locale: {
            format: 'DD/MM/YYYY'
        }
   }
   );


// ========== daterangepicker End============
    var counter = <?php $counter++; echo $counter; ?>;
    var package_id = '<?php echo $package_id; ?>';
    var _token = '{{ csrf_token() }}';
    $(document).on('click','.add_more',function(){
        $.ajax({
            url:"{{ route('admin.packages.ajax_departure_row') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
                package_id: package_id,
                counter: counter
            },
            success:function (response) {
                if(response.success) {
                    $("#more_elements").append(response.htmlData);
                    counter = counter+1;
                } else {
                    alert(response.message);
                }
            }
        });
    });

    var i = [];
    $('.removeArr').each(function(elem) {
        $(this).click(function name(e) {
            
            var index = $(this).data('index');
            var id = $(this).data('id');

            i.push(id);          

          $('#row'+index).remove();
          $("#removed_ids").val(i);
        });

    });

    // $(document).on('click','.ui-datepicker-div',function(){
    //     $("#to_date_6").val('04/10/2023');
    // });

    // function remove_elements(id, getId) {
    //     $('#row'+id).remove();
    // }

    $('body').on('focus',".from_date.mycalender", function(){
        //var datesToHide1 = ['04/10/2023', '04/15/2023', '04/27/2023'];
        var datesToHide = [<?php if(isset($xArray) && !empty($xArray)){ foreach ($xArray as $key => $val) { echo "'".$val."',"; } }?>];
        var maxDate = null;
        var to_date = $(this).parent().parent().find('.to_date.mycalender').val();
        if(to_date) {
            var to_date_arr = to_date.split('/');
            to_date = to_date_arr[2]+'-'+to_date_arr[1]+'-'+to_date_arr[0];
            maxDate = new Date(to_date);
        }
        $(this).datepicker("destroy");
        $(this).datepicker({
            minDate: 0,
            maxDate: maxDate,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            beforeShowDay: function(date) {
            var dateString = $.datepicker.formatDate('mm/dd/yy', date);
            if ($.inArray(dateString, datesToHide) !== -1) {
                return [false]; }
            return [true]; },
            onSelect: function(date) {
                var old_date_arr = date.split('/');
                old_date = old_date_arr[2]+'-'+old_date_arr[1]+'-'+old_date_arr[0];
                var selectedDate = new Date(old_date);
                selectedDate.setDate(selectedDate.getDate() + <?php echo $package->package_duration_days ?? 0; ?>);
                var formattedDate = $.datepicker.formatDate('dd/mm/yy', selectedDate);
                /* $("#to_date_6").val(formattedDate); */
                $(this).parent().parent().find('.to_date.Nocalender').val(formattedDate)
            }
        });
    });

    $('body').on('focus',".to_date.mycalender", function(){
        var from_date = $(this).parent().parent().find('.from_date.mycalender').val();
        var minDate = 0;
        if(from_date) {
            var from_date_arr = from_date.split('/');
            from_date = from_date_arr[2]+'-'+from_date_arr[1]+'-'+from_date_arr[0];
            minDate = new Date(from_date);
        }
        $(this).datepicker("destroy");
        $(this).datepicker({
            minDate: minDate,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });
    });



document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('full_calender');
    var myEvents = [];
        //myEvents = <?php //echo $full_calender_data; ?>;

    var calendar = new FullCalendar.Calendar(calendarEl, {
      /* hiddenDays: [0, 1, 2, 3], */
      plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth'
      },      
      defaultDate: "<?php echo date('Y-m-d'); ?>",
      selectable: true,
      selectMirror: true,
      validRange: {
        start: '<?php echo date('Y-m-d'); ?>'
      },   
      select: function(arg) {
        var title = prompt('Capacity:');
        var package_id = "<?php echo $package_id; ?>";
        if (title) {
          calendar.addEvent({
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: arg.allDay
          });
          var st = arg.start;
          var rams = st.toString();
          var dasy = rams.split(" ");
          var mnths = {Jan:"01", Feb:"02", Mar:"03", Apr:"04", May:"05", Jun:"06",Jul:"07", Aug:"08", Sept:"09", Oct:"10", Nov:"11", Dec:"12"};
          var my_month = mnths[dasy[1]];
          var my_day   = dasy[2];
          var my_year  = dasy[3];
          var start_date = my_year+'-'+my_month+'-'+my_day;


        $.ajax({
            url:"{{ route('admin.packages.ajax_departure_date') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
                package_id: package_id,
                capacity: title,
                start_date: start_date
            },
            success:function (response) {
                if(response.success) {
                    // alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
        }
        calendar.unselect()
      },
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: myEvents
    });
    calendar.render();
  });


    $('#viewFullCalModal').on('hidden.bs.modal', function () {
        window.location.reload();
    });

    $("a[data-target=#viewFullCalModal]").click(function(ev) {
        setTimeout( function(){ 
            $('.fc-dayGridMonth-button').click();
        }, 200);
    });

    $(document).on('click','.copy_down',function(){
        var name = $(this).parent().parent().find('.capacity').attr('name');
        var capacity = $(this).parent().parent().find('.capacity').val();
        var matches = name.match(/capacity\[(\d+)\]\[(\d+)\]/);
        var count = matches[1];
        var package_price_id    = matches[2];
        // alert(counter);
        // alert(package_price_id);
        for (var i = count; i <= counter; i++) {
            $("input[name='capacity["+i+"]["+package_price_id+"]']").val(capacity);
        }

    });
    </script>


    @endslot

@endcomponent