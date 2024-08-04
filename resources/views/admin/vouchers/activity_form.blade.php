@component('admin.layouts.main')
@slot('title')
Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<style>
   .service-vaucher .form-group.row {
   display: flex;
   align-items: center;
   margin-bottom: 5px;
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

</style>
<?php
   // $BackUrl = CustomHelper::BackUrl();
   $routeName = CustomHelper::getAdminRouteName();
 
   // $order_id = (request()->has('order_id'))?request()->order_id:'';
   // $order_id = old('order_id',$order_id);
   // prd($OrderServiceVoucher);

   $order_id = $OrderServiceVoucher->order_id ?? '';
   $name = isset($OrderServiceVoucher->name) ?$OrderServiceVoucher->name : $order->name;
   $phone = !empty($OrderServiceVoucher->phone) ?$OrderServiceVoucher->phone : $order->phone;
   $email = !empty($OrderServiceVoucher->email) ?$OrderServiceVoucher->email : $order->email;
   // $cab_charge = !empty($OrderServiceVoucher->cab_charge) ?$OrderServiceVoucher->cab_charge : $order->cab_charge;
   // $total_amount = !empty($OrderServiceVoucher->total_amount) ?$OrderServiceVoucher->total_amount : $order->total_amount;
  // $itinerary = $title;
   //$trip_title = str_replace(">","to",$title);
   $remarks = $OrderServiceVoucher->remarks ?? '';
 
  /* $name_of_pax = $OrderServiceVoucher->name_of_pax ?? '';
   $pax_contact = $OrderServiceVoucher->pax_contact ?? '';
   $local_contact = $OrderServiceVoucher->local_contact ?? '';
   $agent_contact = $OrderServiceVoucher->agent_contact ?? '';
   $location = $OrderServiceVoucher->location ?? '';
   $hotel = $OrderServiceVoucher->hotel_data ?? '';
   $date = $OrderServiceVoucher->date ?? '';
   $payment_mode = $OrderServiceVoucher->payment_mode ?? '';
   $vehicle_type = $OrderServiceVoucher->vehicle_type ?? ''; */

   $payment_status = (isset(request()->order))?request()->order:''; ?>
<div class="centersec">

<div class="container">
   <div class="bgcolor service-vaucher">
      @include('snippets.errors')
      @include('snippets.flash')
      <div class="row">
         <form style="padding:25px 120px;" method="POST">
           {{ csrf_field() }}
            <div class="form-title row">
               <div class="col-sm-6 col-form-label font-weight-bold"><h4>Activity Voucher</h4></div>
               <div class="col-sm-6 font-weight-bold">
                <?php if($order_id){?>
                  <a style="float:right;margin-top: 10px;" href="<?php echo route('admin.voucher.activity_voucher_view', [$order_id,'order'=>$payment_status]) ?>" class="btn_admin2"><i class="fa fa-file-pdf-o"></i> PDF View</a>
               <?php } ?>
               </div>
            </div>
                     
              <div class="clearfix"></div>
            <h3>Activity Details</h3>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Activity Name </label>
               <div class="col-sm-10">
                  <input type="text" name="title" value="{{ old('title',$title) }}" class="form-control" >
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Trip Date </label>
               <div class="col-sm-10">
                  <input type="text" name="trip_date" value="{{ old('trip_date',$trip_date) }}" class="form-control" >
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Duration </label>
               <div class="col-sm-10">
                  <input type="text" name="duration" value="{{ old('duration',$duration) }}" class="form-control" >
               </div>
            </div>
                <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Destination </label>
               <div class="col-sm-10">
                  <input type="text" name="destination" value="{{ old('destination',$destination) }}" class="form-control" >
               </div>
            </div>
           
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Activity Location </label>
               <div class="col-sm-10">
                  <input type="text" name="address" value="{{ old('address',$address) }}" class="form-control" >
               </div>
            </div>

            <div class="clearfix"></div>
            <h3>Activity Contact Details</h3>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Contact Person </label>
               <div class="col-sm-10">
                  <input type="text" name="contact_person" value="{{ old('contact_person',$contact_person) }}" class="form-control" >
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Contact Phone </label>
               <div class="col-sm-10">
                  <input type="text" name="contact_phone" value="{{ old('contact_phone',$contact_phone) }}" class="form-control" >
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Contact Email </label>
               <div class="col-sm-10">
                  <input type="text" name="contact_email" value="{{ old('contact_email',$contact_email) }}" class="form-control" >
               </div>
            </div>

            <div class="clearfix"></div>
            <h3>Traveller Details</h3>
           
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Name </label>
               <div class="col-sm-10">
                  <input type="text" name="name" value="{{ old('name',$name) }}" class="form-control" >
               </div>
            </div>
          
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Phone</label>
               <div class="col-sm-10">
                  <input type="text" name="phone" value="{{ old('phone',$phone) }}" class="form-control" placeholder="">
               </div>
            </div>
             <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Email</label>
               <div class="col-sm-10">
                  <input type="text" name="email" value="{{ old('email',$email) }}" class="form-control" placeholder="">
               </div>
            </div>
     
            @if($hide_price_details != 1)
            <div class="clearfix"></div>
            <h3>Fare Details</h3>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Activity Charges </label>
               <div class="col-sm-10">
                  <input type="text" name="total_amount" value="{{ old('package_charges',$package_charges) }}" class="form-control" >
               </div>
            </div>

            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Amount Paid</label>
               <div class="col-sm-10">
                  <input type="text" name="paid_amount" value="{{ old('paid_amount',$paid_amount) }}" class="form-control" >
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Amount Due</label>
               <div class="col-sm-10">
                  <input type="text" name="due_amount" value="{{ old('due_amount',$due_amount) }}" class="form-control" >
               </div>
            </div>
            @endif

            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Remarks</label>
               <div class="col-sm-10">
                  <input type="text" name="remarks" value="{{ old('remarks',$remarks) }}" class="form-control" placeholder="">
               </div>
            </div>
               <div class="col-sm-12 text-right" style="padding: 10px 0;">
                 <button class="btn btn-success" type="submit">Submit</button>
                 <a href="{{route($routeName.'.orders.index',['order'=>'confirmed'])}}" class="btn_admin2">Cancel</a>
              </div>
         </form>
      </div>
   </div>
</div>
@slot('bottomBlock')
<script type="text/javaScript">
   $('.sbmtDelForm').click(function(){
       var id = $(this).attr('id');
       $("#delete-form-"+id).submit();
   });
</script>
<script type="text/javaScript">
// ============ hotel_fields =========
   var room = 1;
   function hotel_fields() {
   room++;
   var objTo = document.getElementById('hotel_fields')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "form-group removeclass"+room);
   var rdiv = 'removeclass'+room;
   divtest.innerHTML = '<div class="col-sm-2 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Locationname" name="location[]"  placeholder="Location"></div></div><div class="col-sm-2 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Hotel" name="hotel[]"  placeholder="Hotel"></div></div><div class="col-sm-2 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Date" name="Date[]"  placeholder="Date"></div></div><div class="col-sm-2 nopadding"><div class="form-group"><div class="input-group"> <select class="form-control" id="nightsDate" name="nightsDate[]"><option >Select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option> </select><div class="input-group-btn"> </div></div></div></div> <div class="col-sm-2 nopadding"><div class="form-group"><div class="input-group"> <select class="form-control" id="rooms" name="rooms[]"><option >Date</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option> </select></div></div></div><div class="col-sm-2 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Mealsname" name="Mealsname[]"  placeholder="Meals"> <button class="btn btn-danger" type="button" onclick="remove_hotel_fields('+ room +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></div></div><div class="clear"></div>';

   objTo.appendChild(divtest)
   }
   function remove_hotel_fields(rid) {
   $('.removeclass'+rid).remove();
   }

// ============ hotel_fields End=========
// ============ flight_fields =========
   var room = 1;
   function flight_fields() {
   room++;
   var objTo = document.getElementById('flight_fields')
   var divtest = document.createElement("div");
   divtest.setAttribute("class", "form-group removeflightclass"+room);
   var rdiv = 'removeflightclass'+room;
   divtest.innerHTML = '<div class="col-sm-2 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Locationname" name="dot[]" value="" placeholder="Name2"></div></div><div class="col-sm-2 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Flight" name="flight[]" value="" placeholder="Flight No"></div></div><div class="col-sm-3 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Sector" name="sector[]" value="" placeholder="Sector"></div></div><div class="col-sm-2 nopadding"><div class="form-group"><div class="input-group"> <select class="form-control" id="departures" name="departures[]"><option value="">Select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option> </select><div class="input-group-btn"> </div></div></div></div> <div class="col-sm-3 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Arrivals" name="arrivals[]" value="" placeholder="Arrivals"> <button class="btn btn-danger" type="button" onclick="remove_flight_fields('+ room +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></div></div><div class="clear"></div>';

   objTo.appendChild(divtest)
   }
   function remove_flight_fields(rid) {
   $('.removeflightclass'+rid).remove();
   }


function handledayChange(selectObject){
    // alert($(e.target).val());
    // if(isFirstLoad){
    //     return true;
    // }
   var  element = $(selectObject);

   var days =  selectObject.value;
  
   var _token = '{{ csrf_token() }}';
                    $.ajax({
                        url: "{{ route($routeName.'.packages.getHotelList') }}",
                        type: "POST",
                        data: {days,days},
                        dataType:"JSON",
                        headers:{'X-CSRF-TOKEN': _token},
                        cache: false,
                        beforeSend:function(){
                         $(".ajax_msg").html("");
                     },
                     success: function(resp){
                        if(resp.success){
                            // $(".hotel_list").html(resp.options);
                           
                            element.closest('.location_wrapper').siblings('.hotel_wrapper').find(".hotel_list").html(resp.options);
                        }
                    }
                });
        }

</script>
@endslot
@endcomponent