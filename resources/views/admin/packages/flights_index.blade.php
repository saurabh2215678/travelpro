@component('admin.layouts.main')

@slot('title')
Admin - {{ $heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
// $BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$active_menu = "packages".$package_id.'/flight';
?>
@if(!empty($package_id))
@include('admin.includes.packageoptionmenu')
@endif
<div class="top_title_admin">
  <div class="title">
    <h2>{{$heading}}</h2>
  </div>
  @if(CustomHelper::checkPermission('packages','add'))
  <div class="add_button">
    <a href="javascript:void(0);" class="btn_admin slide_open"><i class="fa fa-plus"></i> Add Flight</a>
    <a href="javascript:void(0);" class="btn_admin slide_close hide"><i class="fa fa-minus"></i> Close</a>
  </div>
  @endif
</div>

@include('snippets.errors')
@include('snippets.flash')
<div class="ajax_msg"></div>
<div class="table-responsive">
  <div id="loaddata"></div>
  <div id="flight_form" class="hide pull-right"></div>
</div>

@slot('bottomBlock')

<script type="text/javascript">
  $(document).ready(function(){
    flight_list();
  });
  $(document).on('click','.edit',function(e){
    e.preventDefault();
    var data_id = $(this).attr('data-id');
    packageflight_form(data_id);
  });

  $(document).on('click','.delete_data',function(e){
    e.preventDefault();
    var data_id = $(this).attr('data-id');
    var conf = confirm('Do you really want to remove this?');
    if(conf) {
      flight_delete(data_id);
    }
  });

  $(document).on('click','.slide_open',function(){
    packageflight_form();
  });

  $(document).on('click','.slide_close',function(){
    close_flight_form();
    $('.slide_open').removeClass('hide');
    $('.slide_close').addClass('hide');
    $('.flightData').removeClass('hide');
    
  });

  function close_flight_form() {
    $('#loaddata').animate({"width":"100%"}, "fast");
    $('#flight_form').animate({"width":"0%"}, "fast");
    $('#flight_form').addClass('hide');
  }

  function validate_flight_form() {
    var formID = 'packageflight_form';
    var _token = '{{ csrf_token() }}';
    var boxText=$('#'+formID).find('.btnSubmit').html(); 
    $.ajax({
      url: "{{ url($routeName.'/packages/'.$package_id.'/flight_add') }}",
      type: "POST",
      data: new FormData($('#'+formID)[0]),
      processData: false,
      contentType: false,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      enctype: 'multipart/form-data',
      cache: false,
      beforeSend:function() {
        $('#'+formID).parent().find('.ajax_msg').html('');
        $('#'+formID).parent().find('.error').html('');
        $('#'+formID).parent().find('.btnSubmit').html('Please wait...');
      },
      success: function(response) {
        if(response.success) {
          close_flight_form();
          $('.slide_open').removeClass('hide');
          $('.slide_close').addClass('hide');          
          $('.ajax_msg').html('<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.msg+'</div></div>');
          flight_list();
        } else {
          parseErrorMessages(response);
        }
      },
      error: function(e) {
        var response = e.responseJSON;
        if(response) {
          parseErrorMessages(response);
        }
      }
    });
    return false;    
  }

  function parseErrorMessages(response) {
    if(response) {
      if(response.msg) {
        $('#flight_form #message').after('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.msg+'</div></div>');
      } else {
        $('#flight_form #message').after('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Invalid request, please check errors below!</div></div>');
      }
      if(response.errors) {
        $.each(response.errors, function(i, item) {
          $("#flight_form select[name='"+i+"']").css('border','1px solid #ff0000');
          $("#flight_form select[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');

          $("#flight_form input[name='"+i+"']").css('border','1px solid #ff0000');
          $("#flight_form input[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');
        });
      }
    }
  }

  function packageflight_form(data_id) {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url($routeName.'/packages/'.$package_id.'/flight_get') }}",
      type: "POST",
      data: {data_id},
      // processData: false,
      // contentType: false,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      // enctype: 'multipart/form-data',
      cache: false,
      beforeSend:function() {
        $('#loaddata').animate({"width":"50%"}, "fast");
        $('#flight_form').animate({"width":"100%"}, "fast");
      },
      success: function(response) {
        if(response.success) {
          $('#flight_form').html(response.htmlData);
          $('#flight_form').removeClass('hide');
          $('.slide_open').addClass('hide');
          $('.slide_close').removeClass('hide');
          $('.flightData').addClass('hide');
        }
      },
      error: function(e) {

      }
    });
  }

  function flight_list() {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url($routeName.'/packages/'.$package_id.'/flight_list') }}",
      type: "POST",
      data: {},
      processData: false,
      contentType: false,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      enctype: 'multipart/form-data',
      cache: false,
      beforeSend:function() {

      },
      success: function(response) {
        if(response.success) {
          $('#loaddata').html(response.htmlData);
          $('.flightData').removeClass('hide');
        }
      },
      error: function(e) {

      }
    });
  }

  function flight_delete(data_id) {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url($routeName.'/packages/'.$package_id.'/flight_delete') }}",
      type: "POST",
      data: {data_id},
      // processData: false,
      // contentType: false,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      // enctype: 'multipart/form-data',
      cache: false,
      beforeSend:function() {
        $('.ajax_msg').html('');
      },
      success: function(response) {
        if(response.success) {          
          $('.ajax_msg').html('<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.message+'</div></div>');
          flight_list();
        } else {
          if(response.message) {
            $('.ajax_msg').html('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.message+'</div></div>');
          } else {
            $('.ajax_msg').html('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Invalid request, please check errors below!</div></div>');
          }          
        }
      },
      error: function(e) {

      }
    });
  }

</script>

@endslot

@endcomponent