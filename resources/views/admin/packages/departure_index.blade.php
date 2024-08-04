@component('admin.layouts.main')

    @slot('title')
        Admin - Travel Dates({{ucfirst($segment)}}) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
    body {
    background-color: #fff6ec;
}
    .right-bar.d-flex {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-bottom: 0px;
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
button.btn_admin2.btnSubmit[disabled] {
    opacity: .5;
}
.top_title_admin h2 {
    margin-top: 0;
}
.row1 > .col-md-12 {
    background: #fff;
    margin-top: 8px;
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    padding: 5px 0;
}
.page_btns>a:hover, .page_btns>a:focus {
    color: #fff;
    text-decoration: none;
}
</style>
<?php
// $BackUrl = CustomHelper::BackUrl();
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
    <div class="row1">
        <?php if($package->tour_type !='open'){ ?>
        <div class="col-md-12">
            <div class="col-md-8">
                <div class="top_title_admin">
                    <div class="title">
                    <h2>{{ $page_heading }}</h2>
                    </div>
                
                </div>
            </div>
            <div class="col-md-4">
                <div class="right-bar d-flex">
                    <div class="btn">
                        <a data-toggle="modal" href="#bulkModal" class="btn_admin2">Bulk Update</a>
                    </div>
                    <!-- Modal -->
                    <div id="bulkModal" class="modal modal-wide fade">
                        <form method="POST" action="" accept-charset="UTF-8" role="form" id="bulk_inventory" onSubmit="return validateBulkInventory()" >
                            {{ csrf_field() }}
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">Bulk Update</h4>
                                </div>
                                <div class="modal-body">
                                    @include('snippets.errors')
                                    @include('snippets.flash')
                                    <div class="ajax_msg"></div>
                                    <div class="dateRow_list">
                                        <div class="dateselect"><span class="btn_date"><i class="fa fa-calendar"></i><input type="text" name="bulkdaterange" value="" /></span>
                                        </div>
                                        <table class="table table-borderless">
                                            <tbody class="date_input_box">
                                                <tr>
                                                    
                                              
                                              
                                                    <?php
                                                    if(!empty($packagePrices)) {
                                                        foreach($packagePrices as $packagePrice){
                                                            $price_id = $packagePrice->id;
                                                    ?>
                                                    <td style="width: 100px;" class="date">
                                                       {{$packagePrice->title}} 
                                                      
                                                    <?php  $package_slots = CustomHelper::getPackageSlots($package_id,$price_id);

                                                         if($package_slots && $package_slots->count() > 0 ){
                                                            foreach ($package_slots as $key => $package_slot_row) {
                                                                if($package_slot_row->price_id == $price_id ){
                                                    ?>
                                                        <br>  
                                                        <label for=" " class="label-text">{{$package_slot_row->start_time ?? ''}}</label>
                                                        <input type="text" class="form-control bulk_pp_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="package_price[{{$packagePrice->id}}][{{$package_slot_row->id}}]">
                                                        <!-- <label for=" " class="label-text">2 Sold</label> -->
                                                  
                                                    <?php } } }else{ ?>
                                                        <br>
                                                        <label for=" " class="label-text"></label>
                                                               
                                                        <input type="text" class="form-control bulk_pp_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="package_price[{{$packagePrice->id}}][0]">
                                                        <!-- <label for=" " class="label-text">2 Sold</label> -->
                                                    
                                                    
                                                   
                                                    <?php } }  ?>
                                                </td>
                                           
                                                    <?php } ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" name="save_bulk_inventory" class="btn btn-primary btnSubmit" disabled>Save changes</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div>
                    </form>
                    </div>
                    <div class="bulk-btn">
                        <span class="btn_left pre-btn" data-value="{{$pre_date}}"><i class="fa fa-chevron-left"></i></span>
                        <span class="btn_date singledate"><i class="fa fa-calendar"></i></span>
                        <span class="btn_right next-btn" data-value="{{$last_date}}"><i class="fa fa-chevron-right"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php if($package->tour_type !='open'){ ?>
    <div class="dateRow">
        <?php if(!empty($packagePrices) && count($packagePrices) > 0){ ?>   
        <form method="POST" action="" accept-charset="UTF-8" role="form" id="package_inventory" onSubmit="return validateDepartures()" >
            @include('admin.packages._package_inventory')
        </form>
        <?php }else{ ?>
        @if($segment == 'activity')
        <div class="alert alert-warning">Please add Activity Price first!</div>
        @else
        <div class="alert alert-warning">Please add Accommodation & Package Price first!</div>
        @endif
        <?php } ?>
    </div>
    <?php }else{ ?>
    <div class="alert alert-warning">This feature is available only for Group and Fixed Tour</div>
    <?php } ?>
</section>


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

    <script type="text/javascript">

    function validateBulkInventory() {
        var formID = 'bulk_inventory';
        var _token = '{{ csrf_token() }}';
        var boxText = $('#'+formID).find('.btnSubmit').html();
        $.ajax({
          url: "{{route($routeName.'.packages.departure_bulk',['package_id'=>$package_id]) }}",
          type: "POST",
          data: new FormData($('#'+formID)[0]),
          processData: false,
          contentType: false,
          dataType:"JSON",
          headers:{'X-CSRF-TOKEN': _token},
          enctype: 'multipart/form-data',
          cache: false,
          beforeSend:function() {
            $('#'+formID).find('.ajax_msg').html('');
            $('#'+formID).find('.btnSubmit').html('Please wait...');
            $('#'+formID).find('.btnSubmit'). attr("disabled", true);
          },
          success: function(response) {
            if(response.success) {
                $('#'+formID).find(".ajax_msg").html(response.message);
                $('#'+formID).find('#has_unsaved').val(0);
            } else {
                $('#'+formID).find(".ajax_msg").html(response.message);
                $('#'+formID).find('.btnSubmit').attr("disabled", false);
            }
            $('#'+formID).find('.btnSubmit').html(boxText);
          },
          error: function(e) {
            var response = e.responseJSON;
            if(response) {
                $('#'+formID).find(".ajax_msg").html(response.message);
            }
          }
        });
        return false;
    }

    function validateDepartures() {
        var formID = 'package_inventory';
        var _token = '{{ csrf_token() }}';
        var boxText = $('#'+formID).find('.btnSubmit').html();
        $.ajax({
          url: "{{route($routeName.'.packages.departure',['package_id'=>$package_id]) }}",
          type: "POST",
          data: new FormData($('#'+formID)[0]),
          processData: false,
          contentType: false,
          dataType:"JSON",
          headers:{'X-CSRF-TOKEN': _token},
          enctype: 'multipart/form-data',
          cache: false,
          beforeSend:function() {
            $('#'+formID).find('.ajax_msg').html('');
            $('#'+formID).find('.btnSubmit').html('Please wait...');
            $('#'+formID).find('.btnSubmit'). attr("disabled", true);
          },
          success: function(response) {
            if(response.success) {
                $('#'+formID).find(".ajax_msg").html(response.message);
                $('#'+formID).find('#has_unsaved').val(0);
            } else {
                $('#'+formID).find(".ajax_msg").html(response.message);
                $('#'+formID).find('.btnSubmit').attr("disabled", false);
            }
            $('#'+formID).find('.btnSubmit').html(boxText);
          },
          error: function(e) {
            var response = e.responseJSON;
            if(response) {
                $('#'+formID).find(".ajax_msg").html(response.message);
            }
          }
        });
        return false;
    }



    $(document).on('keyup','.bulk_pp_inventory',function(e){
        var formID = 'bulk_inventory';
        $('#'+formID).find('.btnSubmit').removeAttr('disabled');
    });

    $(document).on('keyup','.pp_inventory',function(e){
        var formID = 'package_inventory';
        $('#'+formID).find('.btnSubmit').removeAttr('disabled');
        $('#'+formID).find('#has_unsaved').val(1);
    });

    $(document).on('click','.pre-btn,.next-btn',function(e){
        var date = $(this).attr('data-value');
        loadDepartures(date);
    });
    function loadDepartures(date) {
        if(date && date !== 'undefined') {
            var formID = 'package_inventory';
            var has_unsaved =$('#'+formID).find('#has_unsaved').val();
            if(has_unsaved == 1) {
                if(confirm("You have unsaved Changed.") == true) {
                    $('#'+formID).find('#has_unsaved').val(0);
                } else {
                    return false;
                }
            }
            var package_id = <?php echo $package_id; ?>;        
            var _token = '{{ csrf_token() }}';
            var formData = new FormData();
            formData.append('package_id',package_id)
            formData.append('date',date)
            $.ajax({
                url: "{{route($routeName.'.packages.departure_next',['package_id'=>$package_id]) }}",
                type: "POST",
                data: formData,
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                cache: false,
                beforeSend:function(){
                    $('#'+formID).find(".ajax_msg").html('');
                },
                success: function(response){
                    if(response.success) {
                        $(document).find(".pre-btn").attr('data-value',response.data.pre_date);
                        $(document).find(".next-btn").attr('data-value',response.data.last_date);
                        $('#'+formID).html(response.data.html);
                        $('#'+formID).find('.btnSubmit').attr("disabled", true);
                    } else {
                        $('#'+formID).find(".ajax_msg").html(response.message);
                    }
                }
            });
        } else {
            alert('Please select valid date');
        }
    }
    </script>
    
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
        $(function() {
            $('.singledate').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true
            }, 
            function(start, end, label) {
                var date = moment(start).format('YYYY-MM-DD');
                loadDepartures(date);
                // var years = moment().diff(start, 'years');
                // alert("You are " + years + " years old.");
            });
        });

    // ========== daterangepicker ============
    // $('.daterange').daterangepicker({
    //     locale: {
    //         format: 'DD/MM/YYYY'
    //     }
    // }); 

    $('input[name="bulkdaterange"]').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        },
        minDate: new Date(),
    });


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