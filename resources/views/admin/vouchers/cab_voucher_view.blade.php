@component('admin.layouts.main')
@slot('title')
Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
               <div class="col-sm-6 col-form-label font-weight-bold"><h4>Cab Voucher</h4></div>
               <div class="col-sm-6 font-weight-bold">
               <div class="text-right" style="margin-top: 10px;">
                @if(CustomHelper::checkPermission('cabs','edit_voucher'))
               <a href="<?php echo route('admin.voucher.cab', [$order_id,'order'=>$payment_status]) ?>" title="Cab Voucher" class="btn btn_admin cab_view_fancy"> Edit Voucher </a>
               @endif

               @if(CustomHelper::checkPermission('cabs','view_voucher'))
               <a href="javascript:;" title="Cab Voucher" class="btn sent_email btn_admin2" id="cust_btn"> Send Mail </a>
               @endif

               @if(CustomHelper::checkPermission('cabs','view_voucher'))
              <a href="<?php echo route('admin.voucher.cab_voucher_pdf',[$order_id]) ?>" title="Cab Voucher PDF" class="btn btn-success cab_view_fancy">Download PDF </a>
              @endif
         </div>
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Trip Title</label>
               <div class="col-sm-8">
                  {{$title}}
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Trip Type</label>
               <div class="col-sm-8">
                   {{$trip_type}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Itinerary</label>
               <div class="col-sm-8">
                   {{$itinerary}}
                  
               </div>
            </div>
            <div class="clearfix"></div>
            <h3>Cab Details</h3>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Origin</label>
               <div class="col-sm-8">
                {{$origin}}
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Destination </label>
               <div class="col-sm-8">
                {{$destination}}
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Pickup Address </label>
               <div class="col-sm-8">
                {{$pickup_address}}
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Pickup Date </label>
               <div class="col-sm-8">
                 {{$pickup_date}}
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Drop Address </label>
               <div class="col-sm-8">
                 {{$drop_address}}
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Cab Type </label>
               <div class="col-sm-8">
                 {{$car_type}}
               </div>
            </div>

            @if(isset($booking_details['adult']))
            <div class="form-group row">
              <label for="input" class="col-sm-4 col-form-label">Adults </label>
              <div class="col-sm-8">
                {{$booking_details['adult']??''}}
              </div>
            </div>
            @endif

            @if(isset($booking_details['children']))
            <div class="form-group row">
              <label for="input" class="col-sm-4 col-form-label">Child </label>
              <div class="col-sm-8">
                {{$booking_details['children']??''}}
              </div>
            </div>
            @endif      

            @if(isset($booking_details['selected_addons']) && !empty($booking_details['selected_addons']))
            @foreach($booking_details['selected_addons'] as $addon)
            <div class="form-group row">
              <label for="input" class="col-sm-4 col-form-label">{{$addon['name']??''}} </label>
              <div class="col-sm-8">
                Qty: {{$addon['qty']??''}}@if(isset($addon['days'])), Days: {{$addon['days']}}@endif
              </div>
            </div>
            @endforeach
            @endif

            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Exclusion </label>
               <div class="col-sm-8">
                 {!! $exclusion !!}
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
               <label for="input" class="col-sm-4 col-form-label">Cab Charges </label>
               <div class="col-sm-8">
                 {{$cab_charge}}
               </div>
            </div>

            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Amount Paid</label>
               <div class="col-sm-8">
                {{$paid_amount}} 
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-4 col-form-label">Amount to be paid to driver</label>
               <div class="col-sm-8">
                 {{$be_paid_to_driver}}
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
          <h4 class="modal-title">Cab Booking Mail Voucher</h4>
        </div>
        <div class="modal-body">
          <label>To:</label>
          <span>{{$email}}</span>
          <br>
          <label>From:</label>
          <span>{{$ADMIN_EMAIL}}</span>
        </div>
        <div class="modal-footer">
         <a href="<?php echo route('admin.voucher.cab_voucher_sendmail',[$order_id]) ?>" title="Cab Voucher" class="btn btn-success cab_view_fancy btn_admin" id="sendMail">Send Mail</a>
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