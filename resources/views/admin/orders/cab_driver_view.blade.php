<style>
    .centersec.fancybox-content {
    width: 900px;
    height: 450px;
    padding: 20px;
    padding-top: 0;
}
div#view_mode .col-md-6 {
    border-bottom: 1px solid #ddd;
    padding: 5px 17px;
}
#FormDriver .col-md-6 {
    /* border-bottom: 1px solid #ddd; */
    padding: 5px 17px;
}
.form-title {
    background: #e5e5e5;
    margin-bottom: 15px;
    padding-right:3rem;
}
.fancybox-slide--html .fancybox-close-small {
    top: 6px;
}
</style>
<?php
$routeName = CustomHelper::getAdminRouteName();
$vehicle_no = $driver_details['vehicle_no'] ?? '';
$vehicle_type = $driver_details['vehicle_type'] ?? '';
$driver_name = $driver_details['driver_name'] ?? '';
$mobile_no = $driver_details['mobile_no'] ?? '';
?>
<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

    <!-- <h3>Cab Driver Detail</h3> -->

    
    <?php if($vehicle_no){
        $edit_form = 0;
     }else{

        $edit_form = 1;
     }?>
     <!-- <div class="row">
        <div class="col-md-12">
         <div class="">
            <a href="javascript:void(0);" class="btn_admin" onclick="show_edit()"> <i class="fa fa-edit"></i> Edit</a>
            <a href="#" class="btn_admin" onclick="send_mail()">Send mail</a>
       </div>
       </div>
   </div> -->

   <div class="form-title row">
    <div class="col-sm-6 col-form-label font-weight-bold"><h4>Cab Driver Detail</h4></div>
    <div class="col-sm-6 font-weight-bold">
      @if($driver_details)<a style="float:right;margin-top: 10px;margin-left:7px;" href="javascript:void(0);" class="btn_admin" onclick="show_edit()"> <i class="fa fa-edit"></i> Edit</a>@endif
      <!-- <a style="float:right;margin-top: 10px;" href="#" class="btn_admin" onclick="send_mail()">Send mail</a> -->
      <a href="javascript:;" title="Send email / SMS" class="btn_admin" id="cust_btn" style="float:right;margin-top: 10px;">Send email / SMS</a>
    </div>
  </div>



      <div class="row">
        <div id="alert_msg"></div>
    <form class="form-inline" id="FormDriver" method="GET" <?php if($edit_form ==0){ echo 'style="display:none;"'; }?>>
                
                <div class="col-md-3{{$errors->has('vehicle_no')?' has-error':''}}">
                    <label class="required" for="FormControlSelect1">Vehicle No</label><br>
                    <input type="text" name="vehicle_no" class="form-control" value="{{$vehicle_no}}" placeholder="Vehicle No" autocomplete="off">
                </div>
                <div class="col-md-3{{$errors->has('vehicle_type')?' has-error':''}}">
                    <label class="required" for="FormControlSelect1">Vehicle Type</label><br>
                    <input type="text" name="vehicle_type" class="form-control" value="{{$vehicle_type}}" placeholder="Vehicle Type" autocomplete="off">
                </div>

                <div class="col-md-3{{$errors->has('driver_name')?' has-error':''}}">
                    <label class="required" for="FormControlSelect1">Driver Name</label><br>
                    <input type="text" name="driver_name" class="form-control" value="{{$driver_name}}" placeholder="Driver name" autocomplete="off">
                </div>

                <div class="col-md-3{{$errors->has('mobile_no')?' has-error':''}}">
                    <label class="required" for="FormControlSelect1">Mobile No</label><br>
                    <input type="text" name="mobile_no" class="form-control" value="{{$mobile_no}}" placeholder="Mobile No" autocomplete="off" maxlength="10">
                </div>

                <div class="clearfix"></div>
                <div class="col-md-6">
                      <input type="button" name="btn-submit" class="btn btn-success btn-submit " onclick="submitForm()" value="Submit">
                      @if($driver_details)<a href="javascript:void(0)" class="btn_admin2" onClick="return hide_edit();" >Reset</a>@endif
                </div>
    </form>


 <div class="form-inline" id="view_mode" method="GET" <?php if($edit_form ==1){ echo 'style="display:none;'; }?>">
              
 <div class="clearfix"></div>
                <div class="col-md-6{{$errors->has('vehicle_no')?' has-error':''}}">
                    <label for="FormControlSelect1">Vehicle No</label><br />
                    <span id="vehicle_no">{{$vehicle_no}}</span>
                </div>
                  <div class="col-md-6{{$errors->has('vehicle_no')?' has-error':''}}">
                    <label for="FormControlSelect1">Vehicle Type</label><br />
                    <span id="vehicle_type">{{$vehicle_type}}</span>
                </div>
                <div class="col-md-6{{$errors->has('vehicle_no')?' has-error':''}}">
                    <label for="FormControlSelect1">Driver Name</label><br />
                    <span id="driver_name">{{$driver_name}}</span>
                </div>
                <div class="col-md-6{{$errors->has('vehicle_no')?' has-error':''}}">
                    <label for="FormControlSelect1">Mobile No</label><br />
                    <span id="mobile_no">{{$mobile_no}}</span>
                </div>

 </div>
</div>

    
    <div class="row">
               <div class="col-md-12">
                  <h3 style="max-width: 1200px;margin: 12px auto 0;">Voucher Logs</h3>
                  <table class="table table-bordered" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="border: 1px solid #ddd;max-width: 600px;margin-left: 0;">
                     <tbody>
                        <tr>
                           <th>Date</th>
                           <th>User</th>
                           <th>Module</th>
                           <th>Action</th>
                        </tr>
                        <?php foreach($email_log as $log) { 
                            $created_at = CustomHelper::DateFormat($log->created_at,'d-m-Y h:i:s A');?>
                        <tr>
                           <td>{{$created_at}}</td>
                           <td>{{$log->user_name}}</td>
                           <td>{{$log->module}}</td>
                           <td>{{$log->action}}</td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
            
</div>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <?php 
         $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');?>
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cab Driver Details</h4>
        </div>
        <div class="modal-body">
          <label>To:</label>
          <span><b>Email - </b>{{$email}}, <b>SMS - </b> <span id="modal_mobile_no">{{$mobile_no}}</span></span>
          <br>
          <label>From:</label>
          <span>{{$ADMIN_EMAIL}}</span>
        </div>
        <div class="modal-footer">
         <a href="javascript:void(0);" title="Cab Voucher" class="btn btn-success cab_view_fancy btn_admin" id="sendMail">Send email / SMS</a>
          <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

</div>

<script type="text/javascript">
  /* $(document).on("click","sbmtBtn", function(e){
    submitForm();
  });*/

  function show_edit() {
    $('#FormDriver').show();
    $('#view_mode').hide();
  }

    var vehicle_no = '{{$vehicle_no??''}}';
    var vehicle_type = '{{$vehicle_type??''}}';
    var driver_name = '{{$driver_name??''}}';
    var mobile_no = '{{$mobile_no??''}}';

  function hide_edit() {
    $('#FormDriver').hide();
    $('#view_mode').show();
    $('input[name=vehicle_no]').val(vehicle_no);
    $('input[name=vehicle_type]').val(vehicle_type);
    $('input[name=driver_name]').val(driver_name);
    $('input[name=mobile_no]').val(mobile_no);
  }

  function submitForm(){
    $(".validation_error").remove();
    $(".flash-message").remove();
    var formData = $('#FormDriver').serialize();
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url($routeName.'/orders/cab_driver',$order_id) }}",
      type: "POST",
      data: formData,
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      beforeSend: function () {
        $('#FormDriver input').css('border','1px solid #ccc');
      },
      success: function(resp){
        if(resp.success) {
          $('#FormDriver').hide();
          $('#view_mode').show();
          $("#alert_msg").html(resp.msg);
          vehicle_no = $('input[name=vehicle_no]').val();
          vehicle_type = $('input[name=vehicle_type]').val();
          driver_name = $('input[name=driver_name]').val();
          mobile_no = $('input[name=mobile_no]').val();
          $("#vehicle_no").text(vehicle_no);
          $("#vehicle_type").text(vehicle_type);
          $("#driver_name").text(driver_name);
          $("#mobile_no").text(mobile_no);
          $("#modal_mobile_no").text(mobile_no);
        }else {
					if(response) {
						parseErrorMessages(response);
					}
				}
      },
      error: function(e) {
          var response = e.responseJSON;
          if(response) {
            parseErrorMessages(response);
          }
        }
    });
  }

  function parseErrorMessages(response) {
        if(response.errors) {
              $.each(response.errors, function(i, item) {
                  $("#FormDriver input[name='"+i+"']").css('border','1px solid #ff0000');
                  $("#FormDriver input[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');
            });
          }
    }

  function send_mail() {
    var formData = $('#FormDriver').serialize();
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ url($routeName.'/orders/cab_driver_mail',$order_id) }}",
      type: "POST",
      data: {},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      beforeSend: function () {},
      success: function(resp){
        if(resp.success) {
          $('#FormDriver').hide();
          $('#view_mode').show();
          $("#alert_msg").html(resp.msg);

          $("#myModal").modal("hide");
          $("#sendMail").html('Send email / SMS');
          $("#sendMail").attr("disabled", false);
        }
      }
    });
  }

  $(document).on("click","#cust_btn",function(){

    if(vehicle_no == '' || vehicle_type == '' || driver_name == '' || mobile_no == ''){
      
      alert('Please add driver details first.');

      $('input[name="vehicle_no"]').focus(); 
    }else{
      $("#myModal").modal("show");
    }
    
  });

  $("#myModal a").click(function() {
    send_mail();
    $("#sendMail").html(
      'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
      );
    $("#sendMail").attr("disabled", true);
  });

</script>