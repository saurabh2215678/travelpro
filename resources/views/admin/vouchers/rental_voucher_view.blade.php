@component('admin.layouts.main')
@slot('title')
Admin - Manage Rental Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php $payment_status = (isset(request()->order))?request()->order:''; ?> 
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
               <div class="col-sm-6 col-form-label font-weight-bold"><h4>Rental Voucher</h4></div>
               <div class="col-sm-6 font-weight-bold">
               <div class="text-right" style="margin-top: 10px;">
                @if(CustomHelper::checkPermission('rental','edit_voucher'))
               <a href="<?php echo route('admin.voucher.rental', [$order_id,'order'=>$payment_status]) ?>" title="Rental Voucher" class="btn btn_admin cab_view_fancy"> Edit Voucher </a>
               @endif

               @if(CustomHelper::checkPermission('rental','view_voucher'))
               <a href="javascript:;" title="Rental Voucher" class="btn sent_email btn_admin2" id="cust_btn"> Send Mail </a>
               @endif

               @if(CustomHelper::checkPermission('rental','view_voucher'))
              <a href="<?php echo route('admin.voucher.rental_voucher_pdf',[$order_id]) ?>" title="Rental Voucher PDF" class="btn btn-success cab_view_fancy">Download PDF </a>
              @endif
         </div>
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Bike Name</label>
               <div class="col-sm-8">
                  {{$bike_name}}
               </div>
            </div>
          
            <div class="clearfix"></div>
            <h3>Rental Details</h3>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">City</label>
               <div class="col-sm-8">
                {{$city_name}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Location </label>
               <div class="col-sm-8">
                {{$location_name}}
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Pickup Date </label>
               <div class="col-sm-8">
                 {{ CustomHelper::DateFormat($pickup_date,'d/m/Y h:i A')}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Drop Date </label>
               <div class="col-sm-8">
                 {{ CustomHelper::DateFormat($drop_date,'d/m/Y h:i A')}}
               </div>
            </div>
           
          
            <div class="clearfix"></div>
            <h3>Traveller Details</h3>
           
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Name </label>
               <div class="col-sm-8">
                 {{$name}}
               </div>
            </div>
          
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Phone</label>
               <div class="col-sm-8">
                 {{$phone}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Email</label>
               <div class="col-sm-8">
                  {{$email}}
               </div>
            </div>

            <div class="clearfix"></div>
            <h3>Fare Details</h3>
          
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Amount Paid</label>
               <div class="col-sm-8">
                {{$paid_amount}} 
               </div>
            </div>
          
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Remarks</label>
               <div class="col-sm-8">
                 {{$remarks}}
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
          <h4 class="modal-title">Rental Mail Voucher</h4>
        </div>
        <div class="modal-body">
          <label>To:</label>
          <span>{{$email}}</span>
          <br>
          <label>From:</label>
          <span>{{$ADMIN_EMAIL}}</span>
        </div>
        <div class="modal-footer">
         <a href="<?php echo route('admin.voucher.rental_voucher_sendmail',[$order_id]) ?>" title="Cab Voucher" class="btn btn-success cab_view_fancy btn_admin" id="sendMail">Send Mail</a>
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