@component(config('custom.theme').'.layouts.main')
@slot('title')
   {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
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
   display: block;
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
    margin: 7px 10px 0;
    font-size: 1.2rem;
    font-weight:600;
}
.service-vaucher label {
    margin-left: 10px;
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
@endslot

   <div class="centersec">
   <div class="bgcolor service-vaucher">
      @include('snippets.errors')
      @include('snippets.flash')
      <div class="md:container">
      <div class="row">
         
         <form style="padding:25px 120px;padding-top: 0;" method="POST">
           {{ csrf_field() }}
            <div class="form-title flex mt-5">
               <div class="w-1/2 col-form-label text-xl font-weight-bold p-3"><h4>Rental Voucher</h4></div>
               <div class="w-1/2 font-weight-bold text-right">
               <div class="inline-block p-1" style="margin-top: 5px;padding-right: 0.2rem;">
              <a href="<?php echo route('user.rental_voucher_pdf',[$order_id]) ?>" title="Rental Voucher PDF" class="btn btn-success cab_view_fancy p-3" style="display: inline-block;border-radius: 5rem;padding: 0.5rem 0.8rem;">Download PDF </a>
         </div>
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Bike Name</label>
               <div class="w-4/6">
                   {{$bike_name}}
               </div>
            </div>
            <div class="clearfix"></div>
            <h3>Rental Details</h3>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">City </label>
               <div class="w-4/6">
                {{$city_name}}
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Location </label>
               <div class="w-4/6">
                 {{$location_name}}
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Pickup Date </label>
               <div class="w-4/6">
                 {{ CustomHelper::DateFormat($pickup_date,'d/m/Y h:i A')}}
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Drop Date </label>
               <div class="w-4/6">
                 {{ CustomHelper::DateFormat($drop_date,'d/m/Y h:i A')}}
               </div>
            </div>
            
            <div class="clearfix"></div>
            <h3 class="">Traveller Details</h3>
           
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Name </label>
               <div class="w-4/6">
                 {{$name}}
               </div>
            </div>
          
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Phone</label>
               <div class="w-4/6">
                 {{$phone}}
               </div>
            </div>
             <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Email</label>
               <div class="w-4/6">
                  {{$email}}
               </div>
            </div>

            <div class="clearfix"></div>
            <h3 class="">Fare Details</h3>

            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Amount Paid</label>
               <div class="w-4/6">
                {{$paid_amount}} 
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Remarks</label>
               <div class="w-4/6">
                {{$remarks}}
               </div>
            </div>
         </form>
      </div>
      </div>
   </div>
</div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent