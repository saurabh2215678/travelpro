@component('admin.layouts.main')
@slot('title')
Admin - Accommodation Inventory - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<style>
  .dateSwiper.swiper {
    width: 100%;
    height: 100%;
/* max-width: 900px;
margin-left: 40px;
padding: 50px; */
}
.dateSwiper .swiper-slide {
  text-align: center;
  font-size: 18px;
  background: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
}

.dateSwiper .swiper-slide img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
}
/* .date-view {
border: 1px solid #00b2a7;
padding: 5px;
font-size: 14px;
} */
.date-view {
  border: 1px solid #00b2a7;
  font-size: 12px;
  /* max-width: 77px; */
  width: 10%;
  margin: 7px;
  padding: 5px;
  text-align: center;
  display: inline-block;
}
.dateSwiper-prev i.fa.fa-angle-double-right, .dateSwiper-next i.fa.fa-angle-double-right {
  cursor: pointer;
  display: flex;
  align-items: center;
  height: 100%;
}
.topnav {
  display: inline-block;
  width: 100%;
}
.dateslider {
  display: inline-block;
  width: 100%;
}
.dateslider > .col-md-12 {
  background: #fff;
}
.dateslider .bgcolor.abc_wrapper {
  max-width: 1000px;
  background: #F8F8F8 !important;
  padding: 15px;
  margin-left: 60px;
}
.cab_input_form .form-input {
  /* max-width: 77px; */
  width: 10%;
  margin: 7px;
  padding: 5px;
  text-align: center;
}
.cab_input_form .form-group .d-flex {
  align-items: center;
}
.cab_input_form .form-group {
  /*margin-left: 38px; */
  display: inline-block;
}
.cab_input_form {
  max-width: 1100px;
  width: 100%;
}
button.applyBtn {
  font-weight: 500;
  background-color: #162e44;
  border-radius: 5rem;
  padding: 6px 20px;
  font-size: 12px;
  border: none;
  color: #fff;
  transition: all ease 0.5s;
}
button.cancelBtn.btn{
  font-weight: 500;
  background-color: #00b2a7;
  border-radius: 5rem;
  padding: 6px 20px;
  font-size: 12px;
  display: inline-block;
  border: none;
  color: #fff;
  transition: all ease 0.5s;
}
</style>

<style>
  .right-bar.d-flex {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-bottom: 10px;
    padding-top: 10px;
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
    /* text-transform: uppercase; */
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
    margin-left: 0 !important;
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
    width: 70%;
  }
  .modal-wide .modal-body {
    overflow-y: auto;
  }
  .dateselect {
    /* background: #eef1ff; */
    /* padding: 15px; */
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
    font-size: 14px;
    color: #979797;
  }
  .modal.modal-wide .modal-dialog .dateselect {
    padding: 0px;
  }
  div#bulkModal .modal-header {
    padding: 5px 15px;
  }
  .modal-header .close {
    margin-top: 0px;
    opacity: 0.58;
    font-size: 30px;
  }
  div#bulkModal .modal-body .dateRow_list {
    height: 435px;
    overflow-y: scroll;
  }
  .dateRow_list tbody tr.odd_bg td {
    background: #e7e7e7 !important;
  }
  .dateRow_list tbody tr.odd_bg {
    border-top: 15px solid #fff !important;
    /* border: 1px solid #ddd; */
  }
  .dateRow_list tbody.date_input_box tr.even_bg td {
    background: #F4F4F4;
  }
  .modal-footer button.btn_admin2.btnSubmit{
    cursor: pointer;
  }
  .modal-footer button.btn_admin2.btnSubmit:hover{
    background: #162e44;
  }
  #bulkModal .modal-body .dateRow_list .date_input_box tr.even_bg td:nth-child(3) {
    font-weight: 800;
}


</style> 
@endslot

<?php
$active_menu = "accommodations".$accommodation_id.'/inventory';
?>
@if(!empty($accommodation_id))
@include('admin.includes.accommodationoptionmenu')
@endif
<?php $routeName = CustomHelper::getAdminRouteName(); ?>
<!-- Swiper -->
<div class="top_title_admin">
  <div class="title">
    <h2>{{ $page_heading }}</h2>
  </div>

</div>
<div class="col-md-12">
  <div class="table-responsive" id="booking_mode_form" style="display: none;margin-bottom: 10px;">
    <div class="full">
      <div class="form-group{{ $errors->has('booking_mode') ? ' has-error' : '' }} col-md-6">
        <label class="control-label ">Booking Mode:</label>
        <input type="radio" name="booking_mode" value="0"
        <?php echo (old('booking_mode', $booking_mode) == '0')?'checked':''; ?> /> Automatic booking mode
        &nbsp;&nbsp;
        <input type="radio" name="booking_mode" value="1"
        <?php echo (old('booking_mode', $booking_mode) == '1')?'checked':''; ?> /> Booking needs approval
        @include('snippets.errors_first', ['param' => 'booking_mode'])
      </div>
    </div>
    <div class="col-md-6">
      <button type="submit" class="btn btn_admin2" id="SaveBooking" onclick="save_booking_mode()">Save</button>
      <a href="{{route($routeName.'.accommodations.inventory',$accommodation_id)}}" class="btn_admin">Cancel</a>
    </div>
  </div>

  @include('snippets.errors')
  @include('snippets.flash')

  <div class="ajax_msg"></div>

  <table class="table table-striped table-bordered table-hover booking_mode_data">
    <tr>
      <td><strong> Selected booking mode</strong></td>
      <td>@if(isset($booking_mode)) @if($booking_mode == 1) Booking needs approval @elseif($booking_mode == 0) Automatic booking mode @endif @endif</td>
      <td>&nbsp <button class="btn btn_admin2 edit_form">Edit</button></td>
    </tr>
  </table>
</div>

<div id="">
  <section>
    <div class="col-md-12">
      <div class="col-md-6"></div>
      <div class="col-md-6">
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
                            <th width="25%">Room Type/Inventory</th>
                            <th width="15%">Rate Plan</th>
                            <th width="10%">Base Occupancy</th>
                            <th width="10%">Single Occupancy</th>
                            <th width="10%">Extra Adult</th>
                            <th width="10%">Extra Child (0-6)</th>
                            <th width="10%">Extra Child (7-12)</th>
                          </tr>

                          <?php foreach($rooms_data as $room_key => $room){ ?>
                            <tr class="odd_bg">
                              <td style="vertical-align: middle;" class="date align-middle">
                                <div style="width: 105px;float: left;">
                                  <strong>{{$room->room_type_name}} (Inventory)</strong>
                                </div>
                                <div style="width: 50px;float: left;">
                                  <input type="text" class="form-control bulk_cab_inventory" id="" placeholder="" style="width: 100px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="bulk_inventory[{{$room->id}}]" placeholder="inventory">
                                </div>
                              </td>
                              <td colspan="6" style="vertical-align: middle;width: 175px;text-align: center;">
                              <span style="font-weight: 300; color: #a3650b;"><strong>Note:</strong> Inventory Price Should be less than Base Price</span>
                          </td>
                            </tr>
                            <?php
                            $plan_data = $room->planData??[];
                            if(!empty($plan_data) && $plan_data->count() > 0) {
                              foreach ($plan_data as $plan_key => $plan) {
                                $plan_id = $plan->id??'';
                                ?>
                                <tr class="even_bg">
                                  <td></td>
                                  <td style="width: 100px;vertical-align: middle;" class="date">
                                    {{$plan->plan_name }}
                                  </td>
                                    <td style="width: 100px;" class="date">
                                      {{($plan->price > 0)? CustomHelper::getPrice($plan->price):'--'}}
                                      <input type="text" class="form-control bulk_cab_inventory" id="" placeholder="" style="width: 100px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="bulk_price[{{$room->id}}][{{$plan_id}}]">
                                    </td>
                                    <td style="width: 100px;" class="date">
                                      {{($plan->single_price > 0)? CustomHelper::getPrice($plan->single_price):'--'}}
                                      <input type="text" class="form-control bulk_cab_inventory" id="" placeholder="" style="width: 100px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="bulk_single_price[{{$room->id}}][{{$plan_id}}]">
                                    </td>
                                    <td style="width: 100px;vertical-align: middle;" class="date">
                                      {{($plan->extra_adult > 0)? CustomHelper::getPrice($plan->extra_adult):'--'}}
                                      <input type="text" class="form-control bulk_cab_inventory" id="" placeholder="" style="width: 100px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="bulk_extra_adult[{{$room->id}}][{{$plan_id}}]">
                                    </td>
                                    <td style="width: 100px;vertical-align: middle;" class="date">
                                      {{($plan->extra_child_1 > 0)? CustomHelper::getPrice($plan->extra_child_1):'--'}}
                                      <input type="text" class="form-control bulk_cab_inventory" id="" placeholder="" style="width: 100px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="bulk_extra_child_1[{{$room->id}}][{{$plan_id}}]">
                                    </td>
                                    <td style="width: 100px;vertical-align: middle;" class="date">
                                      {{($plan->extra_child_2 > 0)? CustomHelper::getPrice($plan->extra_child_2):'--'}}
                                      <input type="text" class="form-control bulk_cab_inventory" id="" placeholder="" style="width: 100px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="bulk_extra_child_2[{{$room->id}}][{{$plan_id}}]">
                                    </td>
                                  </tr>

                                <?php } } else{ ?>
                                  <td></td>
                                  <td colspan="6">
                                    <div class="alert alert-warning" role="alert" style="padding: 5px;margin-bottom: 0;"> <i class="fa fa-lightbulb-o fa-1x"></i>Plans are not available in this room type</div>
                                  </td>

                                <?php } } ?>
                              </tbody>
                            </table>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn_admin" data-dismiss="modal">Close</button>
                            <button type="submit" name="save_bulk_inventory" class="btn_admin2 btnSubmit" disabled>Save changes</button>
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div>
                  </form>
                </div>
                <div class="bulk-btn">
                  <span class="btn_left pre-btn" data-value="{{$pre_date}}"><i class="fa fa-chevron-left"></i></span>
                  <span class="btn_date singledate"><i class="fa fa-calendar"></i>
                    <!-- <input type="text" name="daterange" value="01/01/2015 - 01/31/2015" /> -->
                  </span>
                  <span class="btn_right next-btn" data-value="{{$last_date}}"><i class="fa fa-chevron-right"></i></span>
                  <input type="hidden" name="last_date" id="last_date" value="{{$last_date}}">
                  <input type="hidden" name="is_saved" id="is_saved" value="0">
                  <input type="hidden" name="accommodation_id" id="accommodation_id" value="{{$accommodation_id}}">    
                </div>
              </div>
            </div>
          </div>
          <div class="dateRow">
            <form method="POST" action="" accept-charset="UTF-8" role="form" id="cab_inventory" onSubmit="return validateInventory()" >
              @include('admin.accommodations.inventory_form')
            </form>
          </section>
          @slot('bottomBlock')
          <!-- Initialize Swiper -->
          <script type="text/javascript">

            $('.edit_form').click(function(){
              $('#booking_mode_form').show();
              $('.booking_mode_data').hide();
            });


            var swiper = new Swiper(".dateSwiper", {
              slidesPerView: 1,
              spaceBetween: 10,
              navigation: {
                prevEl: ".dateSwiper-prev",
                nextEl: ".dateSwiper-next",
              },
              breakpoints: {
                640: {
                  slidesPerView: 4,
                  spaceBetween: 5,
                },
                768: {
                  slidesPerView: 6,
                  spaceBetween: 5,
                },
                1024: {
                  slidesPerView: 10,
                  spaceBetween: 5,
                },
              },
            });

            function validateBulkInventory() {
              var formID = 'bulk_inventory';
              var _token = '{{ csrf_token() }}';
              var boxText = $('#'+formID).find('.btnSubmit').html();
              $.ajax({
                url: "{{route($routeName.'.accommodations.bulk_inventory',['accommodation_id'=>$accommodation_id]) }}",
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

            function validateInventory() {
              var formID = 'cab_inventory';
              var _token = '{{ csrf_token() }}';
              var boxText = $('#'+formID).find('.btnSubmit').html();
              $.ajax({
                url: "{{route($routeName.'.accommodations.saveAllInventory',['accommodation_id'=>$accommodation_id]) }}",
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

            $(document).on('keyup','.bulk_cab_inventory',function(e){
              var formID = 'bulk_inventory';
              $('#'+formID).find('.btnSubmit').removeAttr('disabled');
            });

            $(document).on('keyup','.cab_inventory',function(e){
              var formID = 'cab_inventory';
              $('#'+formID).find('.btnSubmit').removeAttr('disabled');
              $('#'+formID).find('#has_unsaved').val(1);
            });

            $(document).on('click','.pre-btn,.next-btn',function(e){
              var date = $(this).attr('data-value');
              loadInventory(date);
            });

            $(document).ready(function(){
              <?php if(!empty($start_date)){ ?>
                loadInventory('<?php echo $start_date; ?>');
              <?php } ?>
            });

            function loadInventory(date) {
              var formID = 'cab_inventory';
              var has_unsaved =$('#'+formID).find('#has_unsaved').val();
              if(has_unsaved == 1) {
                if(confirm("You have unsaved Changed.") == true) {
                  $('#'+formID).find('#has_unsaved').val(0);
                } else {
                  return false;
                }
              }
              var accommodation_id = <?php echo $accommodation_id; ?>;        
              var _token = '{{ csrf_token() }}';
              var formData = new FormData();
              formData.append('accommodation_id',accommodation_id)
              formData.append('date',date)
              $.ajax({
                url: "{{route($routeName.'.accommodations.nextInventory') }}",
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
            }

            $( document ).ready(function() {
              $('.singledate').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true
              }, 
              function(start, end, label) {
                var date = moment(start).format('YYYY-MM-DD');
                loadInventory(date);
// var years = moment().diff(start, 'years');
// alert("You are " + years + " years old.");
});
            });

            $('input[name="bulkdaterange"]').daterangepicker({
              locale: {
                format: 'DD/MM/YYYY'
              },
              minDate: new Date(),
            });


            $('.labelbtn').click(function(){
// alert('ssss')
$(this).closest('tr').siblings('.ext_field').toggleClass('active');
});

            $('td.labelbtn').click(function(){
// alert('ssss')
$(this).closest('tr .labelbtn').toggleClass('icon');
});

            $('.labelbtn2').click(function(){
// alert('ssss')
$(this).closest('tr').siblings('.ext_field2').toggleClass('active');
});

            $('td.labelbtn2').click(function(){
// alert('ssss')
$(this).closest('tr .labelbtn2').toggleClass('icon');
});

//============

$(document).on('click','.parent td:first-child',function(){

  var _this1 = $(this).parent();

  var show = _this1.attr('data-show'); 
  var id = _this1.attr('data-id');

  if(show == ''){
// alert(id);
_this1.attr('data-show','show');

$('.child_'+id).show();

}else{
  $('.child_'+id).hide();
  _this1.attr('data-show','');
}
});

function save_booking_mode(){
  var booking_mode = $("input[name = 'booking_mode']:checked").val();
  var accommodation_id = '{{$accommodation_id}}';
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route('admin.accommodations.save_booking_mode') }}",
    type: "POST",
    data: { booking_mode:booking_mode, accommodation_id:accommodation_id },
    dataType:"JSON",
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    async: false,
    beforeSend:function(){
      $(".ajax_success_msg").hide();
      $("#SaveBooking").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
      $("#SaveBooking").attr("disabled", true);
    },
    success: function(resp){
      if(resp.success){
        $(".ajax_success_msg").html('Booking mode saved');
        $(".ajax_success_msg").show();
        setTimeout( function() {
          $(".ajax_success_msg").hide();
          window.location.reload();
        }, 700 );
      }
    }
  });

}


</script>

@endslot
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
@endcomponent