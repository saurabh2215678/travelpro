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
   $gst_no = $OrderServiceVoucher->gst_no ?? '';

   $name = isset($OrderServiceVoucher->name) ?$OrderServiceVoucher->name : $order->name;
   $phone = !empty($OrderServiceVoucher->phone) ?$OrderServiceVoucher->phone : $order->phone;
   $email = !empty($OrderServiceVoucher->email) ?$OrderServiceVoucher->email : $order->email;
   // $cab_charge = !empty($OrderServiceVoucher->cab_charge) ?$OrderServiceVoucher->cab_charge : $order->cab_charge;
   // $total_amount = !empty($OrderServiceVoucher->total_amount) ?$OrderServiceVoucher->total_amount : $order->total_amount;
   $itinerary = $title;
   $trip_title = str_replace(">","to",$title);
   $remarks = $OrderServiceVoucher->remarks ?? '';
 
  /* $name_of_pax = $OrderServiceVoucher->name_of_pax ?? '';
   $pax_contact = $OrderServiceVoucher->pax_contact ?? '';
   $local_contact = $OrderServiceVoucher->local_contact ?? '';
   $agent_contact = $OrderServiceVoucher->agent_contact ?? '';
   $location = $OrderServiceVoucher->location ?? '';
   $hotel = $OrderServiceVoucher->hotel_data ?? '';
   $date = $OrderServiceVoucher->date ?? '';
   $payment_mode = $OrderServiceVoucher->payment_mode ?? '';
   $vehicle_type = $OrderServiceVoucher->vehicle_type ?? '';*/

   $payment_status = (isset(request()->order))?request()->order:'';
   $cab_route_types = config('custom.cab_route_types');
   ?>
<div class="centersec">

<div class="container">
  <div class="bgcolor service-vaucher">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="row">
      <form style="padding:25px 120px;" method="POST">
        {{ csrf_field() }}
        <div class="form-title row">
          <div class="col-sm-6 col-form-label font-weight-bold"><h4>Cab Voucher</h4></div>
          <div class="col-sm-6 font-weight-bold">
            <?php if($order_id){?>
              <a style="float:right;margin-top: 10px;" href="<?php echo route('admin.voucher.cab_voucher_view', [$order_id,'order'=>$payment_status]) ?>" class="btn_admin2"><i class="fa fa-file-pdf-o"></i> PDF View</a>
            <?php } ?>
          </div>
        </div>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Trip Title</label>
          <div class="col-sm-10">
            <input type="text" name="title" value="{{ old('title',$trip_title) }}" class="form-control" >
          </div>
        </div>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Trip Type</label>
          <div class="col-sm-10">
            <select name="trip_type" class="form-control" >
              <option value="">Select</option>
              <?php foreach ($cab_route_types as $key => $cab_route_type) { ?>
                <option value="{{$cab_route_type}}" <?php echo ($cab_route_type == $trip_type)?'selected':''; ?>>{{$cab_route_type}}</option>
              <?php } ?>
              <!-- <option value="Transfer Oneway" {{($trip_type == 'Transfer Oneway') ? 'selected' : ''}} >Transfer Oneway</option>
              <option value="Round-Trip" {{($trip_type == 'Round-Trip') ? 'selected' : ''}}  >Round-Trip</option>
              <option value="Sightseeing" {{($trip_type == 'Sightseeing') ? 'selected' : ''}}  >Sightseeing</option>
              <option value="Airport" {{($trip_type == 'Airport') ? 'selected' : ''}}  >Airport Pickup</option>
              <option value="Airport" {{($trip_type == 'Airport') ? 'selected' : ''}}  >Airport Drop</option> -->
            </select>
            <!-- <input type="text" name="trip_type" value="{{ old('trip_type',$trip_type) }}" class="form-control" placeholder=""> -->
          </div>
        </div>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Itinerary</label>
          <div class="col-sm-10">
            <input type="text" name="itinerary" value="{{ old('itinerary',$itinerary) }}" class="form-control" >
          </div>
        </div>
        <?php /*<div class="form-group row">
           <label for="input" class="col-sm-2 col-form-label">GST No</label>
           <div class="col-sm-10">
              <input type="text" name="gst_no" value="{{ old('gst_no',$gst_no) }}" class="form-control" placeholder="">
           </div>
        </div>*/ ?>
        <div class="clearfix"></div>
        <h3>Cab Details</h3>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Origin </label>
          <div class="col-sm-10">
            <input type="text" name="origin" value="{{ old('origin',$origin) }}" class="form-control" >
          </div>
        </div>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Destination</label>
          <div class="col-sm-10">
            <input type="text" name="destination" value="{{ old('destination',$destination) }}" class="form-control" >
          </div>
        </div>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Pickup Address </label>
          <div class="col-sm-10">
            <input type="text" name="pickup_address" value="{{ old('pickup_address',$pickup_address) }}" class="form-control" >
          </div>
        </div>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Pickup Date </label>
          <div class="col-sm-10">
            <input type="text" name="pickup_date" value="{{ old('pickup_date',$pickup_date) }}" class="form-control" >
          </div>
        </div>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Drop Address </label>
          <div class="col-sm-10">
            <input type="text" name="drop_address" value="{{ old('drop_address',$drop_address) }}" class="form-control" >
          </div>
        </div>
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Cab Type </label>
          <div class="col-sm-10">
            <input type="text" name="car_type" value="{{ old('car_type',$car_type) }}" class="form-control" >
          </div>
        </div>

        @if(isset($booking_details['adult']))
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Adults </label>
          <div class="col-sm-10">
            <input type="text" name="booking_details[adult]" value="{{ old('adult',$booking_details['adult']) }}" class="form-control" >
          </div>
        </div>
        @endif

        @if(isset($booking_details['children']))
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Child </label>
          <div class="col-sm-10">
            <input type="text" name="booking_details[children]" value="{{ old('children',$booking_details['children']) }}" class="form-control" >
          </div>
        </div>
        @endif      

        @if(isset($booking_details['selected_addons']) && !empty($booking_details['selected_addons']))
        @foreach($booking_details['selected_addons'] as $addon)
        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">{{$addon['name']??''}} </label>
          <div class="col-sm-5">
            <label for="input" class="col-sm-2 col-form-label">Qty </label>
            <input type="text" name="booking_details[selected_addons][{{$addon['id']}}][qty]" value="{{ old('qty',$addon['qty']) }}" class="form-control" >
          </div>
          @if(isset($addon['days']))
          <div class="col-sm-5">
            <label for="input" class="col-sm-2 col-form-label">Days </label>
            <input type="text" name="booking_details[selected_addons][{{$addon['id']}}][days]" value="{{ old('days',$addon['days']) }}" class="form-control" >
          </div>
          @endif
        </div>
        @endforeach
        @endif

        <div class="form-group row">
          <label for="input" class="col-sm-2 col-form-label">Exclusion </label>
          <div class="col-sm-10">
            <textarea name="exclusion" id="exclusion" class="form-control ckeditor" ><?php echo old('exclusion', $exclusion); ?></textarea> </div>
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

          <div class="clearfix"></div>
          <h3>Fare Details</h3>
          <div class="form-group row">
            <label for="input" class="col-sm-2 col-form-label">Cab Charges </label>
            <div class="col-sm-10">
              <input type="text" name="cab_charge" value="{{ old('cab_charge',$cab_charge) }}" class="form-control" >
            </div>
          </div>

          <div class="form-group row">
            <label for="input" class="col-sm-2 col-form-label">Amount Paid</label>
            <div class="col-sm-10">
              <input type="text" name="paid_amount" value="{{ old('paid_amount',$paid_amount) }}" class="form-control" >
            </div>
          </div>
          <div class="form-group row">
            <label for="input" class="col-sm-2 col-form-label">Amount to be paid to driver</label>
            <div class="col-sm-10">
              <input type="text" name="be_paid_to_driver" value="{{ old('be_paid_to_driver',$be_paid_to_driver) }}" class="form-control" >
            </div>
          </div>


          <div class="form-group row">
            <label for="input" class="col-sm-2 col-form-label">Remarks</label>
            <div class="col-sm-10">
              <input type="text" name="remarks" value="{{ old('remarks',$remarks) }}" class="form-control" placeholder="">
            </div>
          </div>
          <!-- <div class="row">
             <div class="col-sm-3 text-center right-content float-right">
                <p>Highlights of Ladakh Winter Package</p>
                <p>Ladakh, next you will explore bit of North and East of Ladakh</p>
             </div>
          </div> -->
          <div class="col-sm-12 text-right" style="padding: 10px 0;">
           <button class="btn btn-success" type="submit">Submit</button>
           <a href="{{route($routeName.'.orders.index',['order'=>'confirmed'])}}" class="btn_admin2">Cancel</a>
         </div>
       </form>
     </div>
   </div>
 </div>
@slot('bottomBlock')
<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>
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

var editor = CKEDITOR.replace('exclusion',{
 filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
 filebrowserUploadMethod: 'form'
});

</script>
@endslot
@endcomponent