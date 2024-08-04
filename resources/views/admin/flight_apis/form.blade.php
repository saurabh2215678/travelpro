@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')

    <link rel="stylesheet" type="text/css" href="{{ url('/css/jquery-ui.css') }}"/ >

    <link rel="stylesheet" type="text/css" href="{{ url('/datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >
<style>
.payment-gateway_head { display: flex; align-items: center; justify-content: space-between; }
.payment-gateway-buttons { margin-left: 19px; display: flex; flex-wrap: wrap; }
.tab_box:not(.active) { display: none; }
.payment-gateway-buttons .btn { margin-left: 6px; background-color: #6b6b6b; }
.payment-gateway-buttons .btn.active { background-color: #1f5a54; }
.tab_box { padding: 25px; padding-top: 0; }
.config_sec { font-weight: 500; font-size: 18px; line-height: 27px; color: #415094; }
.config_sec img { height: 85px; width: 200px; object-fit: contain; margin-left: 27px; }
.radio-item span,.tab_box .control-label{font-weight:500;font-size:14px}
.radio_wrapper{margin-bottom:25px;display:flex;align-items:center}
.radio-item{display:flex;margin:0 17px 0 0;align-items:center}
.radio-item input{margin:0 5px 0 0;width:16px;height:16px}
.payment-gateway-contents { max-width: 650px; display: block; margin: auto; padding: 19px; border: 1px solid #ddd; }
.form-gateway { display: flex; align-items: center; }
.input_gateway{position:relative;width:100%;display:flex;justify-content:space-between;align-items:center;padding:8px;border:1px solid #ccc;border-radius:5px;cursor:pointer}
.input_gateway input[type=file]{display:none}
.form-gateway .form-group{width:70%;padding-right:24px}
.gateway-img{width:30%}
.gateway_placeholder{font-weight:500;font-size:14px;padding-left:15px}
.gateway_btn{background-color:#1f5a54;font-weight:500;font-size:14px;color:#fff;padding:9px 15px;border-radius:4px}
.bgcolor { padding-top: 40px; padding-bottom: 40px; }
</style>
    @endslot

    <?php
    //pr($blog->toArray());
    $routeName = CustomHelper::getAdminRouteName();
    $active_id = 1 ;

    ?>
    <div class="row1">
        <div class="col-sm-12">
            <h2 class="payment-gateway_head">
                <span>{{ $page_heading }}</span>
                <div class="payment-gateway-buttons" tab-btns>
                    <?php 
                   
                    $i =1;
                     ?>
                     <button class="btn btn-sm btn-success pull-right <?php if($active_id ==1){ echo 'active'; }?>" data-tab="1">Flight</button>
                     <!-- <button class="btn btn-sm btn-success pull-right" data-tab="2">new</button> -->
                </div>    
            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>

      <?php } ?>
    </h2>
            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
            <div class="bgcolor">
                <!-- tab contents start -->
                <div class="payment-gateway-contents" tab-contents>
                    <!-- tab item -->
                    <div class="tab_box <?php if($active_id ==1){ echo 'active'; }?>" data-tab="1">
                       @include('admin.flight_apis.include_tab.flight')
                    </div> 

                   <!--  <div class="tab_box " data-tab="2"> 
                    </div> --> 
                </div>
                <!-- tab contents end -->
            </div>
        </div>
    </div>


@slot('bottomBlock')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap-tooltip.js"></script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
$('.hasAttachment').click(function() {
    if ($(this).is(':checked')) {
      $('#template_id').attr('readonly', true);
    } else {
      $('#template_id').removeAttr('readonly');
    }
  });
</script>
  <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ url('/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ url('/datetimepicker/jquery-ui-timepicker-addon.js') }}"></script>



<script type="text/javascript">

$(document).ready(function(){
    // SMS
    var cloneCount = 1;
    $(document).click(function(e){
    var __this = $(e.target);
    // console.log(__this);
    if($(e.target).hasClass('addRow')){
        var input=  __this.closest('.row');
        counter = cloneCount++;
        
        input.clone(true).attr('id', 'smsData-'+ counter).attr('elem', counter).insertAfter($('[id^=smsData]:last'));
        allChild = $('.multi-wrapper').children().length;
        $('.multi-wrapper .row:nth-last-child(1)').find('input').val('')
        
        $('.multi-wrapper .row:nth-last-child(2)').find('.addRow').replaceWith('<span class="removeRow"></span>');

    }else{
      if($(e.target).hasClass('removeRow')){
        __this.closest('.row').remove();
      }
    }
    
  })
});

$('.input_gateway input').change(function(){
    var selectedfilename = $(this).val().split('\\').pop();
    $(this).siblings('.gateway_placeholder').text(selectedfilename);
});

$('[tab-btns] [data-tab]').click(function(){
    var selectedTabId = $(this).attr('data-tab');
    $(this).addClass('active');
    $(this).siblings('[data-tab]').removeClass('active');
    $(`[tab-contents] [data-tab=${selectedTabId}]`).addClass('active');
    $(`[tab-contents] [data-tab=${selectedTabId}]`).siblings('[data-tab]').removeClass('active');
});

 </script>

@endslot

@endcomponent