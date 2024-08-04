@component('admin.layouts.main')
@slot('title')
Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<?php 
// $routeName = CustomHelper::getAdminRouteName();
$tripTimeArr = config('custom.tripTimeArr'); 
$payment_status = (isset(request()->order))?request()->order:'';
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
               <div class="col-sm-3 col-form-label font-weight-bold"><h4>Hotel Voucher</h4></div>
               <div class="col-sm-9 font-weight-bold">
               <div class="text-right" style="margin-top: 10px;">
               @if(CustomHelper::checkPermission('accommodations','edit_voucher'))
               <a href="<?php echo route('admin.voucher.hotel', [$order_id,'order'=>$payment_status]) ?>" title="Hotel Voucher" class="btn btn_admin cab_view_fancy"> Edit Voucher </a>
               @endif

               @if(CustomHelper::checkPermission('accommodations','view_voucher'))
               <a href="javascript:;" title="Hotel Voucher" class="btn sent_email btn_admin2" id="cust_btn"> Send Mail </a>
               @endif

               @if(CustomHelper::checkPermission('accommodations','view_voucher'))
               <a href="<?php echo route('admin.voucher.hotel_voucher_pdf',[$order_id]) ?>" title="Hotel Voucher PDF" class="btn btn-success cab_view_fancy">Download PDF </a>
              @endif
              <!-- Refund -->
              <?php /*if($order->payment_status == 1){ ?>
                <a href="javascript:void(0);" class="btn_admin" id="orderCancelBtn" data-order_id="{{$order->id}}" title="Refund Payment">Cancel Order</a>
            <?php }*/ ?>
            <!-- Refund -->

         </div>
               </div>
            </div>
             <h3>Hotel Booking Details</h3>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Hotel Name</label>
               <div class="col-sm-8">
                  {{$title}}
               </div>
            </div>
           <?Php /* <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Hotel Phone</label>
               <div class="col-sm-8">
                  {{$contact_phone}}
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Hotel Email</label>
               <div class="col-sm-8">
                  {{$contact_email}}
               </div>
            </div> */ ?>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Room Type</label>
               <div class="col-sm-8">
                   {{$room_name}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Plan Name</label>
               <div class="col-sm-8">
                   {{$plan_name}}
                  
               </div>
            </div>
           <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Booking Date </label>
               <div class="col-sm-8">
                {{$created_at}}
               </div>
            </div> 
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">CheckIN Date</label>
               <div class="col-sm-8">
                 {{$checkin}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">CheckIN Time</label>
               <div class="col-sm-8">
                 {{$checkin_time}}
               </div>
            </div>
            <?php /*<div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Check IN Time</label>
                  <div class="col-sm-8">
                  <?php foreach ($tripTimeArr as $timekey => $tripTime) { 
                        if($checkin_time == $timekey){
                           echo $tripTime; 
                        } 
                    } 
                   
               </div>
               </div> */ ?>
           
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">CheckOUT Date</label>
               <div class="col-sm-8">
                 {{$checkout}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">CheckOUT Time</label>
               <div class="col-sm-8">
                 {{$checkout_time}}
               </div>
            </div> 
            <?php /*<div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Checkout OUT Time</label>
               <div class="col-sm-8">
                  <?php foreach ($tripTimeArr as $timekey => $tripTime) { 
                        if($checkout_time == $timekey){
                            echo $tripTime; 
                        } 
                    } 
                   
               </div>
            </div> */ ?>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Adult </label>
               <div class="col-sm-8">
                 {{$adult}}
               </div>
            </div>  
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Child </label>
               <div class="col-sm-8">
                 {{$child}}
               </div>
            </div>

             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">No. of Rooms</label>
               <div class="col-sm-8">
                 {{$inventory}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Destination</label>
               <div class="col-sm-8">
                 {{$destination}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Address</label>
               <div class="col-sm-8">
                 {{$address}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Inclusions</label>
               <div class="col-sm-8">
                 {{$inclusions}}
               </div>
            </div>

            <div class="clearfix"></div>
            <h3>Contact Details</h3>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Contact Person </label>
               <div class="col-sm-8">
                 {{$contact_person}}
               </div>
            </div>

            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Contact Phone </label>
               <div class="col-sm-8">
                {{$contact_phone}} 
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Contact Email </label>
               <div class="col-sm-8">
                 {{$contact_email}}
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
               <label for="input" class="col-sm-4 col-form-label">Total Charges </label>
               <div class="col-sm-8">
                 {{$hotel_charge}}
               </div>
            </div>

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
          <h4 class="modal-title">Hotel Booking Mail Voucher</h4>
        </div>
        <div class="modal-body">
          <label>To:</label>
          <span>{{$email}}</span>
          <br>
          <label>From:</label>
          <span>{{$ADMIN_EMAIL}}</span>
        </div>
        <div class="modal-footer">
         <a href="<?php echo route('admin.voucher.hotel_voucher_sendmail',[$order_id]) ?>" title="Hotel Voucher" class="btn btn-success cab_view_fancy btn_admin" id="sendMail">Send Mail</a>
          <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


          <!-- Modal -->
<div class="modal fade" id="order_cancel" role="dialog">
    <div class="modal-dialog">
    <?php 
         $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');?>
      <!-- Modal content-->
      <div class="modal-content">
            <form action="<?php echo route('admin.orders.refund') ?>" class="form-inline" method="GET">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Order Refund/Cancellation</h4>
          <input type="hidden" id="refund_order_id" name="order_id" value="">
        </div>
          <div class="modal-body">
          <label>Reason for Cancellation:</label><br>
          <textarea name="reason" class="form-control" style="width: 100%;" ></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success cab_view_fancy btn_admin">Submit</button>
          <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button>
        </div>
    </form>
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
      $("#model_click").click(function() {
               $("#sendMail").html(
                    'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
                );
                $("#sendMail").attr("disabled", true);
      });

      $(document).on("click","#orderCancelBtn",function(){

        var order_id = $(this).attr('data-order_id');
        // alert(order_id);
        $('#refund_order_id').val(order_id);
        $("#order_cancel").modal("toggle");

      })
</script>
<script>  
$(document).on("click","#cust_btn",function(){
  
  $("#myModal").modal("toggle");
  
})
</script>
@endslot
@endcomponent