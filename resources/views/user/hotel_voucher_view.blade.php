@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<?php 
$routeName = CustomHelper::getAdminRouteName();
$tripTimeArr = config('custom.tripTimeArr'); 
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
@endslot

<div class="centersec">
   <div class="bgcolor service-vaucher">
      @include('snippets.errors')
      @include('snippets.flash')
      <div class="row">

         <form style="padding:25px 120px;padding-top: 0;" method="POST">
           {{ csrf_field() }}
           <div class="form-title row flex mt-5">
               <div class="w-1/2 col-form-label text-xl font-weight-bold p-3"><h4>Hotel Voucher</h4></div>
               <div class="w-1/2 font-weight-bold text-right">
                   <div class="inline-block p-1" style="margin-top: 5px;padding-right: 0.2rem;">
                    <a href="<?php echo route('user.hotel_voucher_pdf',[$order_id]) ?>" title="Hotel Voucher PDF" class="btn btn-success cab_view_fancy p-3" style="display: inline-block;border-radius: 5rem;padding: 0.5rem 0.8rem;">Download PDF </a>
                  </div>
              </div>
          </div>
          <h3>Hotel Booking Details</h3>
          <div class="form-group flex">
           <label for="input" class="w-2/6 col-form-label">Hotel Name</label>
           <div class="w-4/6 text-sm">
              {{$title}}
          </div>
      </div>
      <div class="form-group flex">
       <label for="input" class="w-2/6 col-form-label">Room Type</label>
       <div class="w-4/6 text-sm">
           {{$room_name}}
       </div>
   </div>
   <div class="form-group flex">
       <label for="input" class="w-2/6 col-form-label">Plan Name</label>
       <div class="w-4/6 text-sm">
           {{$plan_name}}

       </div>
   </div>
   <div class="form-group flex">
       <label for="input" class="w-2/6 col-form-label">Trip Date </label>
       <div class="w-4/6 text-sm">
        {{$created_at}}
    </div>
</div> 
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Check IN Date</label>
   <div class="w-4/6 text-sm">
     {{$checkin}}
 </div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Check IN Time</label>
   <div class="w-4/6 text-sm">
    {{$checkin_time}}
       <?php /*oreach ($tripTimeArr as $timekey => $tripTime) { 
        if($checkin_time == $timekey){
            echo $tripTime; 
        } 
    } */
    ?>
 </div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Checkout OUT Date</label>
   <div class="w-4/6 text-sm">
     {{$checkout}}
 </div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Checkout OUT Time</label>
   <div class="w-4/6 text-sm">
    {{$checkout_time}}
     <?php /*foreach ($tripTimeArr as $timekey => $tripTime) { 
        if($checkout_time == $timekey){
            echo $tripTime; 
        } 
    } */
    ?>
 </div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Adult </label>
   <div class="w-4/6 text-sm">
     {{$adult}}
 </div>
</div>  
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Child </label>
   <div class="w-4/6 text-sm">
     {{$child}}
 </div>
</div>

<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">No. of Rooms</label>
   <div class="w-4/6 text-sm">
     {{$inventory}}
 </div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Destination</label>
   <div class="w-4/6 text-sm">
     {{$destination}}
 </div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Address</label>
   <div class="w-4/6 text-sm">
     {{$address}}
 </div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Inclusions</label>
   <div class="w-4/6 text-sm">
     {{$inclusions}}
 </div>
</div>

<div class="clearfix"></div>
<h3>Contact Details</h3>
<div class="form-group flex">
 <label for="input" class="w-2/6 col-form-label">Contact Person </label>
 <div class="w-4/6 text-sm">
   {{$contact_person}}
</div>
</div>

<div class="form-group flex">
 <label for="input" class="w-2/6 col-form-label">Contact Phone </label>
 <div class="w-4/6 text-sm">
    {{$contact_phone}} 
</div>
</div>
<div class="form-group flex">
 <label for="input" class="w-2/6 col-form-label">Contact Email </label>
 <div class="w-4/6 text-sm">
   {{$contact_email}}
</div>
</div>

<div class="clearfix"></div>
<h3>Traveller Details</h3>

<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Name </label>
   <div class="w-4/6 text-sm">
     {{$name}}
 </div>
</div>

<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Phone</label>
   <div class="w-4/6 text-sm">
     {{$phone}}
 </div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Email</label>
   <div class="w-4/6 text-sm">
      {{$email}}
  </div>
</div>

<div class="clearfix"></div>
<h3>Fare Details</h3>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Total Charges </label>
   <div class="w-4/6 text-sm">
     {{$hotel_charge}}
 </div>
</div>

<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Amount Paid</label>
   <div class="w-4/6 text-sm">
    {{$paid_amount}} 
</div>
</div>
<div class="form-group flex">
   <label for="input" class="w-2/6 col-form-label">Remarks</label>
   <div class="w-4/6 text-sm">
     {{$remarks}}
 </div>
</div>
</form>
</div>
</div>
</div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent