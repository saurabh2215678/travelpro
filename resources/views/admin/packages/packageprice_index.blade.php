@component('admin.layouts.main')

@slot('title')
Admin - {{ $heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<style type="text/css">
.listboxhotal{ width:100%; float:left;border:1px solid #ccc; border-bottom:none; margin:15px 0; overflow:auto;}
.listboxhotal ul{ list-style:none; display:table; width: 100%; margin-bottom: 0;}
.listboxhotal ul li{ width:100%; padding:0; border-top:1px solid #ccc; display:table-row;}
.listboxhotal ul li:first-child{ border:none;}
.listboxhotal ul li strong{padding:7px 1%; min-width: 200px; background:#F1F1F1; display:table-cell; border-bottom:1px solid #ccc;}
.listboxhotal ul li span{ padding:7px 1%; border-left:1px solid #ccc; min-width: 200px; display:table-cell; border-bottom:1px solid #ccc;}
.listboxhotal ul li span cite{ font-weight:bold;}
.listboxhotal ul li strong small{ font-weight:normal; padding-left:10px; display:block;}
.dbg{background:#F1F1F1;}
.daybox{ width:20%; float:left; background:#fff;}
.listboxhotal ul li .daybox span{ width:100%;}
.listboxhotal ul li.datefild{}
.datefild span{ background:#fff;}
.datefild span small{ display:block; padding-bottom:5px; font-size:13px;}

.listboxhotal a.edit, .listboxhotal a.delete_data, .listboxhotal a.delete_data + a {display:inline-block; font-size:12px; padding: 2px 10px; color:#fff; border-radius:2px;}
.listboxhotal a.edit {background: #155b93;}
.listboxhotal a.delete_data {background: #dd3f3f;}
.listboxhotal a.delete_data + a {background: #5c9e42; margin-top: 5px;}

.hidediv{ display:none;}
.daysdiv span, .daysdiv strong{ min-height:56px;}

.sform{}
.formwidth{ width:50%;}
.form-group.validity label{ display:inline-block; margin-right:20px;}
</style>
<?php
// $BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$active_menu = "packages".$package_id.'/packageprice';
?>
@if(!empty($package_id))
@include('admin.includes.packageoptionmenu')
@endif
<div class="top_title_admin">
  <div class="title">
    <h2>{{ $heading }}</h2>
  </div>
  @if(CustomHelper::checkPermission('packages','add'))
  <div class="add_button">
    <a href="javascript:void(0);" class="btn_admin slide_open"><i class="fa fa-plus"></i> Add {{ucfirst($segment)}} Price</a>
    <a href="javascript:void(0);" class="btn_admin slide_close hide"><i class="fa fa-minus"></i> Close</a>
  </div>
  @endif
</div>

@include('snippets.errors')
@include('snippets.flash')
<div class="ajax_msg"></div>
<div id="price_form" class="hide"></div>
<div class="clearfix"></div>
<div class="table-responsive">
  <div id="loaddata"></div>
</div>


@slot('bottomBlock')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    packageprice_list();
 });

  $(document).on('mouseover','.row_position',function(e){
    e.preventDefault();
    $( ".row_position" ).sortable({  
        delay: 150,
        handle: ".handle",
        stop: function(event, ui) {
                var selectedData = new Array();  
                $('.row_position>tr').each(function() {  
                    selectedData.push($(this).attr("id"));  
                });  
                updateOrder(selectedData);  
            }  
    });
  });
  
  $(document).on('click','.edit',function(e){
    e.preventDefault();
    var data_id = $(this).attr('data-id');
    packageprice_form(data_id);
  });

  $(document).on('click','.delete_data',function(e){
    e.preventDefault();
    var data_id = $(this).attr('data-id');
    var conf = confirm('Do you really want to remove this?');
    if(conf) {
      packageprice_delete(data_id);
    }
  });

  $(document).on('click','.slide_open',function(){
    packageprice_form();
  });

  $(document).on('click','.slide_close',function(){
    $('#price_form').addClass('hide');
    $('.slide_open').removeClass('hide');
    $('.slide_close').addClass('hide');
  });

  function validate_packageprice_form() {
    var formID = 'packageprice_form';
    var _token = '{{ csrf_token() }}';
    var boxText=$('#'+formID).find('.btnSubmit').html(); 
    // $('.validation_error').html('');
    $.ajax({
      url: "{{ url($routeName.'/packages/'.$package_id.'/packageprice_add') }}",
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
        $('.flash-message').remove();
        $('.validation_error').remove();
        $('html, body').animate({scrollTop:0}, '300');

      },
      success: function(response) {
        if(response.success) {
          $('#price_form').addClass('hide');
          $('.slide_open').removeClass('hide');
          $('.slide_close').addClass('hide');
          /* $('.ajax_msg').html('<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.msg+'</div></div>'); */
          $('.ajax_msg').html(response.msg);
          packageprice_list();
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
        $('#packageprice_form #message').after('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.msg+'</div></div>');
      } else {
        // $('#packageprice_form #message').after('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Invalid request, please select Hotel!</div></div>');
      }
      if(response.errors) {
        $.each(response.errors, function(i, item) {
          $("#packageprice_form select[name='"+i+"']").css('border','1px solid #ff0000');
          $("#packageprice_form select[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');

          $("#packageprice_form input[name='"+i+"']").css('border','1px solid #ff0000');
          $("#packageprice_form input[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');
        });
      }
    }
  }

  function packageprice_form(data_id) {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url($routeName.'/packages/'.$package_id.'/packageprice_get') }}",
      type: "POST",
      data: {data_id},
      // processData: false,
      // contentType: false,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      // enctype: 'multipart/form-data',
      cache: false,
      beforeSend:function() {
        $('.flash-message').remove();
      },
      success: function(response) {
        if(response.success) {
          $('#price_form').html(response.htmlData);
          $('#price_form').removeClass('hide');
          $('.slide_open').addClass('hide');
          $('.slide_close').removeClass('hide');
        }
      },
      error: function(e) {

      }
    });
  }

  function packageprice_list() {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url($routeName.'/packages/'.$package_id.'/packageprice_list') }}",
      type: "POST",
      data: {},
      processData: false,
      contentType: false,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      enctype: 'multipart/form-data',
      cache: false,
      beforeSend:function() {
        $('.flash-message').remove();
      },
      success: function(response) {
        if(response.success) {
          $('#loaddata').html(response.htmlData);
        }
      },
      error: function(e) {

      }
    });
  }

  function packageprice_delete(data_id) {
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url($routeName.'/packages/'.$package_id.'/packageprice_delete') }}",
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
        $('.flash-message').remove();
      },
      success: function(response) {
        if(response.success) {          
          $('.ajax_msg').html('<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.message+'</div></div>');
          packageprice_list();
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

  function updateOrder(data) {  
      var _token = '{{ csrf_token() }}';
      var PackageId = '{{$package_id}}';
      $.ajax({  
          url : "{{ route('admin.packages.update_packageprice_order') }}",
          type : 'POST',  
          data:{data:data,package_id:PackageId},
          dataType:"JSON",
          headers:{'X-CSRF-TOKEN': _token},
          cache: false,
          beforeSend:function(){
              $(".ajax_msg").html("");
              $(".ajax_msg").show();
          },
          success: function(response) {
              $(".ajax_msg").html(response.message).hide();
              $(".ajax_msg").html(response.message).fadeIn();
              setTimeout(function() { $(".ajax_msg").fadeOut(); }, 3000);
          } 
      })  
    }

    function isDefault(temp_id) {
            $('.checkInput_'+temp_id).prop('checked', true);
            if(confirm("Are you sure you want to change default package price ?")){
              var _token = '{{ csrf_token() }}';
              var PackageId = '{{$package_id}}';
              var updateId = temp_id;
              $.ajax({  
                  url : "{{ route('admin.packages.update_default_packageprice') }}",
                  type : 'POST',  
                  data:{update_id:updateId,package_id:PackageId},
                  dataType:"JSON",
                  headers:{'X-CSRF-TOKEN': _token},
                  cache: false,
                  beforeSend:function(){
                      $(".ajax_msg").html("");
                      $(".ajax_msg").show();
                  },
                  success: function(response) {
                      $('.checkInput_'+response.prv_id).prop('checked', false);
                      $(".ajax_msg").html(response.message).hide();
                      $(".ajax_msg").html(response.message).fadeIn();
                      setTimeout(function() { $(".ajax_msg").fadeOut(); }, 3000);
                  } 
              })

            }
            else{
                /* window.location.reload(); */
                $('.checkInput_'+temp_id).prop('checked', false);
            }
        }
</script>
@endslot
@endcomponent