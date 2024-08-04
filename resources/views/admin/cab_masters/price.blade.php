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
@endslot
<?php  $routeName = CustomHelper::getAdminRouteName();
 // $cab_route_group_id =1;
  ?>
<!-- Swiper -->

<div class="topnav">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
      </div>
   <div class="col-md-12">
      <div class="bgcolor">
         <div class="page_btns">
            <?php foreach($route_groups as $key => $group ) { ?>
            <a <?php if($cab_route_group_id == $group->id){ echo 'class="active"';} ?> href="{{route('admin.cab_master.price',[$group['id']])}}" title="Edit Package">{{$group->name}}</a>
        <?php } ?>
         </div>
      </div>
   </div>
</div>

  <div class="ajax_msg"></div>
<form id="inventory_form">
 <input type="hidden" name="cab_route_group_id" id="cab_route_group_id" value="{{$cab_route_group_id}}">
 <?php if(!empty($cab_routes) && $cab_routes->count() > 0){
  ?>
    <div id="inventory_next_form">
        @include('admin.cab_masters.price_form')
    </div>
<?php } ?>

</form>
@slot('bottomBlock')
<!-- Initialize Swiper -->
<script>
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

   $(document).on('keyup','.cab_price',function(e){

        var is_disble = $('.save_submit').removeAttr('disabled');
        $('.save_submit').val(1);
   });

$(document).on('click','.next',function(e){

    var save_submit = $('.save_submit').val();
  
    if(save_submit == 1){

          if(confirm("You have unsaved Changed.") == true){
              $('#is_saved').val(0);
          }else{
            return false;
          }
    }

    var date = $(this).attr('data-value');
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
                        $("#inventory_next_form").html(resp.html);      
                        
                        // if(resp.success){
                        // element.closest('.location_wrapper').siblings('.hotel_wrapper').find(".hotel_list").html(resp.options);
                        // }
                    }
                });
    });


$(document).on('click','.save_submit',function(e){

    var save_submit = $('.save_submit').val();
    $('#is_saved').val(1);
    var date = $(this).attr('data-value');
    var data = [];
    var _token = '{{ csrf_token() }}';
                    $.ajax({
                        url: "{{ route($routeName.'.cab_master.saveAllPrice') }}",
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
                        if(resp.success){
                            msg = ' <div class="alert alert-success" role="alert">'+resp.msg+'</div>';
                        }
                        else{
                            msg = '<div class="alert alert-danger" role="alert">'+resp.msg+'</div>';
                        }

                            $(".ajax_msg").html(msg);
                            setTimeout(function(){
                            $(".ajax_msg").html('');
                          },2000);
                    }
                });
    });

$(document).on('click','.pre',function(e){

    var save_submit = $('.save_submit').val();
  
    if(save_submit == 1){

          if(confirm("You have unsaved Changed.") == true){
              $('#is_saved').val(0);
          }else{
            return false;
          }
    }

    var date = $(this).attr('data-value');
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
                        $("#inventory_next_form").html(resp.html);      
                        
                        // if(resp.success){
                        // element.closest('.location_wrapper').siblings('.hotel_wrapper').find(".hotel_list").html(resp.options);
                        // }
                    }
                });
    });

</script>
@endslot
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
@endcomponent