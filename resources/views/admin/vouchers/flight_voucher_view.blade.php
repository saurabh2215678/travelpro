@component('admin.layouts.main')
@slot('title')
Admin - Flight Voucher View- {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<?php 
// $routeName = CustomHelper::getAdminRouteName();
?>
<style>
   .service-vaucher .form-group.row {
   display: flex;
   align-items: center;
   margin-bottom: 0;
   }
   .service-vaucher .form-group.row label.col-form-label {
   margin-bottom: 0;
   /* border: 1px solid #a7a7a7; */
   height: 30px;
   box-shadow: none;
   padding: 6px 12px;
   }
   .service-vaucher .form-group.row .col-sm-10 {
   padding-left: 0;
   }
   .service-vaucher .form-group.row .form-control {
   border-radius: 0;
   border: 1px solid #a7a7a7;
   height: 35px;
   }
   .right-content.float-right {
   float: right;
   }
   .service-vaucher .form-group.row .form-group label.col-form-label {
    padding-left: 0;
}
.panel-body {
    padding: 0px;
}
.service-vaucher .panel-body button.btn.btn-success {
    position: absolute;
    right: 0;
    top: 30px;
    border-radius: 0;
    padding: 9px 20px;
}
.service-vaucher .panel-body button.btn.btn-danger {
    position: absolute;
    right: 0;
    top: 0px;
    border-radius: 0;
    padding: 9px 20px;
}
.service-vaucher .panel-body .removeclass1 button.btn.btn-danger {
    top: 30px;
}
.service-vaucher .panel-body .form-group {
    position: relative;
}
.service-vaucher .form-group.row .input-group {
    display: block;
}
.form-title {
    background: #e5e5e5;
    margin-bottom: 15px;
}
.form-title h4 {
    font-weight: 600;
}
.service-vaucher h3 {
    padding: 0;
    margin: 7px -5px 0;
    font-size: 17px;
}
.modal-open .modal .modal-content .modal-header {
    padding: 5px 15px;
    background: #00b2a7;
    color: #fff;
}
.modal-open .modal .modal-content .modal-header button.close {
    color: #fff;
    opacity: 1;
    font-size: 32px;
}
</style>

    
   <div class="centersec">
   <div class="bgcolor service-vaucher">
      @include('snippets.errors')
      @include('snippets.flash')
      <div class="row">
         
         <form style="padding:25px 120px;padding-top: 0;" method="POST">
           {{ csrf_field() }}
            <div class="form-title row">
               <div class="col-sm-6 col-form-label font-weight-bold"><h4>Flight Ticket</h4></div>
               <div class="col-sm-6 font-weight-bold">
               <div class="text-right" style="margin-top: 10px;">
               <a href="javascript:;" title="Flight Voucher Mail" class="btn sent_email btn_admin2" id="cust_btn"> Send Mail </a>
              <a href="<?php echo route('admin.voucher.flight_voucher_pdf',[$order_id]) ?>" title="Flight Voucher PDF" class="btn btn-success cab_view_fancy">Download PDF </a>
            </div>
            </div>
            </div>
            
            <div class="clearfix"></div>
            <h3>Traveller Contact Details</h3>
            <div class="form-group flex">
               <div class="col-form-label px-3"><strong>Name:</strong> <span class="text-sm">{{$name}}</span> | <strong>Phone:</strong> <span class="text-sm"><a href="tel:{{$phone}}">{{$phone}}</a></span> | <strong>Email:</strong> <span class="text-sm"><a href="mailto:{{$email}}">{{$email}}</a></span></div>               
            </div>

            <div class="row">
              <div class="col-md-12">
                <h3 style="max-width: 1200px;margin: 12px auto 0;">Flight Details</h3>
                {!!$itineraries!!}
              </div>
            </div>           
           
           
            
         </form>
      </div>
      
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <?php 
         $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');?>
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Mail voucher</h4>
        </div>
        <div class="modal-body">
          <label>To:</label>
          <span>{{$email}}</span>
          <br>
          <label>From:</label>
          <span>{{$ADMIN_EMAIL}}</span>
        </div>
        <div class="modal-footer">
         <a href="<?php echo route('admin.voucher.flight_voucher_sendmail',[$order_id]) ?>" title="Flight Voucher" class="btn btn-success cab_view_fancy btn_admin" id="sendMail"> Send Mail </a>
          <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button>
        </div>
      </div>
   
    </div>
  </div>
</div>

@slot('bottomBlock')
<script>
      $("#myModal a").click(function() {
               $("#sendMail").html(
                    'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
                );
                $("#sendMail").attr("disabled", true);
      });
</script>

<script> 
$(document).on("click","#cust_btn",function(){
  
  $("#myModal").modal("toggle");
  
})

</script>

@endslot
@endcomponent