@component('admin.layouts.main')
@slot('title')
Admin - Manage Cabs - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
  </style>

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

@endslot
<?php $routeName = CustomHelper::getAdminRouteName(); ?>
<!-- Swiper -->
<div class="topnav">
  <div class="col-md-12">
    <div class="bgcolor">
      <div class="title">
        <h2>{{ $page_heading }}</h2>
      </div>
      <div class="page_btns">
        <?php foreach($route_groups as $key => $group ) { ?>
        <a <?php if($cab_route_group_id == $group->id){ echo 'class="active"';} ?> href="{{route('admin.cab_master.inventory',[$group['id']])}}" title="Cab Route Group Inventory">{{$group->name}}</a>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<div id="">
  <?php
  $car_data =$group_data->cab_data ?? '';
  if($car_data) {
    $car_arr = json_decode($car_data);
  ?>
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
                              <?php foreach ($car_arr as $key => $car) { ?>
                              <td style="width: 150px;vertical-align: middle;">{{$car->name}}</td>
                              <?php } ?>
                            </tr>
                            <tr>
                              <?php foreach ($car_arr as $key => $car) { ?>
                              <td style="width: 100px;" class="date">
                                <input type="text" class="form-control bulk_cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="cab_inventory[{{$car->id}}]">
                                <!-- <label for=" " class="label-text">2 Sold</label> -->
                              </td>
                              <?php } ?>
                            </tr>


                              <?php /*<tr>
                                  <td style="width: 150px;vertical-align: middle; text-align: left;">Luxury</td>
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
                                  <td style="width: 150px;vertical-align: middle; text-align: left;">Standard</td>
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
                              </tr>*/ ?>
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
             <span class="btn_date singledate"><i class="fa fa-calendar"></i>
              <!-- <input type="text" name="daterange" value="01/01/2015 - 01/31/2015" /> -->
          </span>
             <span class="btn_right next-btn" data-value="{{$last_date}}"><i class="fa fa-chevron-right"></i></span>
             <input type="hidden" name="last_date" id="last_date" value="{{$last_date}}">
             <input type="hidden" name="is_saved" id="is_saved" value="0">
             <input type="hidden" name="cab_route_group_id" id="cab_route_group_id" value="{{$cab_route_group_id}}">
             
          </div>
       </div>
    </div>
  </div>
  <div class="dateRow">
    <form method="POST" action="" accept-charset="UTF-8" role="form" id="cab_inventory" onSubmit="return validateInventory()" >
      <?php /*@include('admin.cab_masters.inventory_form')*/ ?>
    </form>
     
      <div class="alert alert-warning" role="alert" style="margin-top: 12px;padding: 5px;margin-bottom: 0;"><b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> Same day cab booking is not allowed <br>
After 5 pm, cab can be booked for day after tomorrow only</div>
  </div>


</section>
<?php } ?>

@slot('bottomBlock')
<!-- Initialize Swiper -->
<script type="text/javascript">
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
      url: "{{route($routeName.'.cab_master.bulk_inventory',['cab_route_group_id'=>$cab_route_group_id]) }}",
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
      url: "{{route($routeName.'.cab_master.saveAllInventory',['cab_route_group_id'=>$cab_route_group_id]) }}",
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
    <?php if(!empty($pre_date)){ ?>
      loadInventory('<?php echo $pre_date; ?>');
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
    var cab_route_group_id = <?php echo $cab_route_group_id; ?>;        
    var _token = '{{ csrf_token() }}';
    var formData = new FormData();
    formData.append('cab_route_group_id',cab_route_group_id)
    formData.append('date',date)
    $.ajax({
      url: "{{route($routeName.'.cab_master.nextInventory') }}",
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


    /*$(document).on('click','.save_submit',function(e){
      var save_submit = $('.save_submit').val();
      $('#is_saved').val(1);
      var date = $(this).attr('data-value');
      var data = [];
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ route($routeName.'.cab_master.saveAllInventory') }}",
        type: "POST",
        data: new FormData($('#inventory_form')[0]),
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        cache: false,
        beforeSend:function(){
          $(".ajax_msg").html("");
        },
        success: function(resp){
          $("#inventory_next_form").html(resp.html);      
          $('.save_submit').attr("disabled", 'disabled');
          if(resp.success) {
            msg = ' <div class="alert alert-success" role="alert">'+resp.msg+'</div>';
          } else {
            msg = '<div class="alert alert-danger" role="alert">'+resp.msg+'</div>';
          }
          $(".ajax_msg").html(msg);
          setTimeout(function(){
            $(".ajax_msg").html('');
          },2000);
        }
      });
    });
    function loadInventory(date) {
      $('#last_date').val(date);
      var data = [];
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ route($routeName.'.cab_master.nextInventory') }}",
        type: "POST",
        data: new FormData($('#inventory_form')[0]),
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        cache: false,
        beforeSend:function(){
          $(".ajax_msg").html("");
        },
        success: function(resp){
          $(document).find(".pre").attr('data-value',resp.pre_date);
          $(document).find(".next").attr('data-value',resp.last_date);
          $("#inventory_next_form").html(resp.html);
        }
      });
    }
    $(document).on('click','.next,.pre',function(e){
  var save_submit = $('.save_submit').val();
  if(save_submit == 1){
    if(confirm("You have unsaved Changed.") == true) {
      $('#is_saved').val(0);
    } else {
      return false;
    }
  }
  var date = $(this).attr('data-value');
  loadInventory(date);
});
  */





    
</script>
@endslot
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
@endcomponent