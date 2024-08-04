@component('admin.layouts.main')
@slot('title')
Admin - Manage Bikes - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
        <?php foreach($bike_cities as $key => $bike_city ) { ?>
        <a <?php if($city_id == $bike_city->id){ echo 'class="active"';} ?> href="{{route('admin.bike_master.price',[$bike_city['id']])}}" title="Bike City Price">{{$bike_city->name}}</a>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<div id="">
  <?php
  $bike_data = $bike_city_data->bike ?? [];
  if($bike_data) {
  $bike_arr = json_decode($bike_data);
  ?>
  <section>
    <div class="col-md-12">
      <div class="col-md-6"></div>
      <div class="col-md-6">
       <div class="right-bar d-flex">
          <div class="bulk-btn">
          </span>
             <input type="hidden" name="is_saved" id="is_saved" value="0">
             <input type="hidden" name="city_id" id="city_id" value="{{$city_id}}">
          </div>
       </div>
    </div>
  </div>
  <div class="dateRow">
    <form method="POST" action="" accept-charset="UTF-8" role="form" id="bike_price" onSubmit="return validatePrice()" >
    </form>
</section>
<?php } ?>
@slot('bottomBlock')
<script type="text/javascript">
  function validatePrice() {
    var formID = 'bike_price';
    var _token = '{{ csrf_token() }}';
    var boxText = $('#'+formID).find('.btnSubmit').html();
    $.ajax({
      url: "{{route($routeName.'.bike_master.saveAllPrice',['city_id'=>$city_id]) }}",
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

  $(document).on('keyup','.bike_price',function(e){
    var formID = 'bike_price';
    $('#'+formID).find('.btnSubmit').removeAttr('disabled');
    $('#'+formID).find('#has_unsaved').val(1);
  });

  $(document).ready(function(){
      loadPrice();
  });

  function loadPrice(date='') {
    var formID = 'bike_price';
    var has_unsaved =$('#'+formID).find('#has_unsaved').val();
    if(has_unsaved == 1) {
      if(confirm("You have unsaved Changed.") == true) {
        $('#'+formID).find('#has_unsaved').val(0);
      } else {
        return false;
      }
    }
    var city_id = <?php echo $city_id; ?>;        
    var _token = '{{ csrf_token() }}';
    var formData = new FormData();
    formData.append('city_id',city_id)
    formData.append('date',date)
    $.ajax({
      url: "{{route($routeName.'.bike_master.allPrice') }}",
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
          $('#'+formID).html(response.data.html);
          $('#'+formID).find('.btnSubmit').attr("disabled", true);
        } else {
          $('#'+formID).find(".ajax_msg").html(response.message);
        }
      }
    });
  }
</script>
@endslot
@endcomponent