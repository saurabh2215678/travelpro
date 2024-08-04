@component('admin.layouts.main')
@slot('title')
Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<style>
   .service-vaucher .form-group.row {
   display: flex;
   align-items: center;
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
    padding: 9px 12px;
}
.service-vaucher .panel-body button.btn.btn-danger {
    position: absolute;
    right: 0;
    top: 0px;
    border-radius: 0;
    padding: 9px 12px;
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
.panel-body.listItem .nopadding, .panel-body .nopadding {
    padding: 0 3px;
}
.service-vaucher .panel-body .form-group .nopadding {
    padding: 0 3px;
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

   //$order_id = $OrderServiceVoucher->order_id ?? '';
   $name_of_pax = $OrderServiceVoucher->name_of_pax ?? '';
   $pax_contact = $OrderServiceVoucher->pax_contact ?? '';
   $local_contact = $OrderServiceVoucher->local_contact ?? '';
   $agent_contact = $OrderServiceVoucher->agent_contact ?? '';
   $location = $OrderServiceVoucher->location ?? '';
   $hotel = $OrderServiceVoucher->hotel_data ?? '';
   $date = $OrderServiceVoucher->date ?? '';
   $remarks = $OrderServiceVoucher->remarks ?? '';
   $payment_mode = $OrderServiceVoucher->payment_mode ?? '';
   $vehicle_type = $OrderServiceVoucher->vehicle_type ?? '';
   ?>
<div class="top_title_admin">
   <div class="title">
      <h2>Packages Voucher List </h2>
   </div>
</div>
<div class="centersec">

<div class="container">
   <div class="bgcolor service-vaucher">
      @include('snippets.errors')
      @include('snippets.flash')
      <div class="row">
         <form style="padding:25px 120px;" method="POST">
           {{ csrf_field() }}
            <div class="form-title row">
               <div class="col-sm-6 col-form-label font-weight-bold"><h4>Packages Services Voucher</h4></div>
               <div class="col-sm-6 font-weight-bold">
                <?php if($order_id){?>
                  <a style="float:right;margin-top: 10px;" href="<?php echo route('admin.orders.service_voucher_view', [$order_id]) ?>" class="btn btn-primary">PDF View</a>
               <?php } ?>
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Name of the Pax</label>
               <div class="col-sm-10">
                  <input type="text" name="name_of_pax" value="{{ old('name_of_pax',$name_of_pax) }}" class="form-control" >
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Pax Contact</label>
               <div class="col-sm-10">
                  <input type="text" name="pax_contact" value="{{ old('pax_contact',$pax_contact) }}" class="form-control" placeholder="">
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Local Contact</label>
               <div class="col-sm-10">
                  <input type="number" name="local_contact" value="{{ old('local_contact',$local_contact) }}" class="form-control" placeholder="">
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Agent Contact</label>
               <div class="col-sm-10">
                  <input type="number" name="agent_contact" value="{{ old('agent_contact',$agent_contact) }}" class="form-control" placeholder="">
               </div>
            </div>
            <h3>Package Details</h3>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Package Name </label>
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
               <label for="input" class="col-sm-2 col-form-label">Destination / Drop Address</label>
               <div class="col-sm-10">
                  <input type="text" name="destination" value="{{ old('destination',$destination) }}" class="form-control" >
               </div>
            </div>

            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Package Address </label>
               <div class="col-sm-10">
                  <input type="text" name="address" value="{{ old('address',$address) }}" class="form-control" >
               </div>
            </div>

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
               <label for="input" class="col-sm-2 col-form-label">Package Charges </label>
               <div class="col-sm-10">
                  <input type="text" name="total_amount" value="{{ old('total_amount',$package_charges) }}" class="form-control" >
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


            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Remarks</label>
               <div class="col-sm-10">
                  <input type="text" name="remarks" value="{{ old('remarks',$remarks) }}" class="form-control" placeholder="">
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Hotel Type</label>
               <div class="col-sm-10">
                  <div class="panel-body listItem">
                  <?php $selected_itinerary_data = [];
                   $i = 0;
                  $total_row = count($locations_arr);
                   foreach ($locations_arr as $key => $location_id) {
                     $i = $key+1;
                  ?>
                    <div class="<?php if($i != $total_row){ echo 'removeclass'.$i.'';} ?>">
                     <div class="col-sm-2 nopadding location_wrapper">
                        <div class="form-group">
                        <label for="input" class="col-form-label" llkk="ttsst">Location</label>
                           <select class="form-control multiselect " name="location[]" dataName="hotel" placeHolder="Select Hotel" onchange="return handledayChange(this)">
                              <option value="">Select Destination / Place</option>
                              {!!CustomHelper::getDestinationOptions('', $location_id, ['show_locations'=>true])!!}
                           </select>
                           <script>
                              var holtelSelect = `
                              <select class="form-control multiselect " name="location[]" dataName="hotel" placeHolder="Select Hotel" onchange="return handledayChange(this)">
                                 <option value="">Select Destination / Place</option>
                                 {!!CustomHelper::getDestinationOptions('', $location_id, ['show_locations'=>true])!!}
                              </select>`
                           </script>
                        </div>
                     </div>
                  <?php 
                  $HotelList   = CustomHelper::getSelectedAccommodation($locations_arr);

                  // prd($hotels_arr);

                  $select_hotel = isset($hotels_arr[$key])? $hotels_arr[$key]:0;
                  $select_date = isset($dates_arr[$key])? $dates_arr[$key]:'';
                  $select_night = isset($nights_data[$key])? $nights_data[$key]:0;
                  $select_rooms = isset($rooms_data[$key])? $rooms_data[$key]:0;
                  $select_meals = isset($meals_data[$key])? $meals_data[$key]:'';
             
                   ?>
                  <div class="col-sm-2 nopadding hotel_wrapper">
                     <div class="form-group">
                     <label for="input" class="col-form-label">Hotel</label>
                        <!-- <input type="text" class="form-control" id="Hotel" name="hotel[]" placeholder="Hotel"> -->
                      <select class="form-control multiselect hotel_list" name="hotel[]" dataName="hotel" placeHolder="Select Hotel">
                         <?php if($HotelList){ foreach ($HotelList as $HotelRow) {
                           // prd($HotelRow);
                          ?>
                            <option value="{{$HotelRow->id}}" <?php  if($HotelRow->id == $select_hotel){ echo "selected";} ?>>{{$HotelRow->name}}</option>
                          <?php } } ?>
                      </select>
                     </div>
                  </div>
                  <script>
                     var holtelRefSelect = `
                     <select class="form-control multiselect hotel_list" name="hotel[]" dataName="hotel" placeHolder="Select Hotel">
                         <?php if($HotelList){ foreach ($HotelList as $HotelRow) {
                           // prd($HotelRow);
                          ?>
                            <option value="{{$HotelRow->id}}" <?php  if($HotelRow->id == $select_hotel){ echo "selected";} ?>>{{$HotelRow->name}}</option>
                          <?php } } ?>
                      </select>`
                  </script>
                  <div class="col-sm-2 nopadding">
                     <div class="form-group">
                     <label for="input" class="col-form-label">Date</label>
                        <input type="date" class="form-control" id="Date" name="date[]" value="{{$select_date}}"  placeholder="Date">
                     </div>
                  </div>
                  <div class="col-sm-2 nopadding">
                     <div class="form-group">
                        <div class="input-group">
                        <label for="input" class="col-form-label">Nights</label>
                           <select class="form-control" id="nights" name="night[]">
                              <option value="">Select</option>
                              <option value="1" <?php if($select_night == '1'){echo 'selected'; } ?>>1</option>
                              <option value="2" <?php if($select_night == '2'){echo 'selected'; } ?>>2</option>
                              <option value="3" <?php if($select_night == '3'){echo 'selected'; } ?>>3</option>
                              <option value="4" <?php if($select_night == '4'){echo 'selected'; } ?>>4</option>
                              <option value="5" <?php if($select_night == '5'){echo 'selected'; } ?>>5</option>
                              <option value="6" <?php if($select_night == '6'){echo 'selected'; } ?>>6</option>
                              <option value="7" <?php if($select_night == '7'){echo 'selected'; } ?>>7</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-2 nopadding">
                        <div class="form-group">
                           <div class="input-group">
                           <label for="input" class="col-form-label">Rooms</label>
                              <select class="form-control" id="rooms" name="rooms[]">
                              <option value="">Select</option>
                              <option value="1" <?php if($select_rooms == '1'){echo 'selected'; } ?>>1</option>
                              <option value="2" <?php if($select_rooms == '2'){echo 'selected'; } ?>>2</option>
                              <option value="3" <?php if($select_rooms == '3'){echo 'selected'; } ?>>3</option>
                              <option value="4" <?php if($select_rooms == '4'){echo 'selected'; } ?>>4</option>
                              <option value="5" <?php if($select_rooms == '5'){echo 'selected'; } ?>>5</option>
                              <option value="6" <?php if($select_rooms == '6'){echo 'selected'; } ?>>6</option>
                              <option value="7" <?php if($select_rooms == '7'){echo 'selected'; } ?>>7</option>
                              </select>
                           </div>
                        </div>
                  </div>
                  <div class="col-sm-2 nopadding">
                        <div class="form-group">
                        <label for="input" class="col-form-label">Meals</label>
                           <input type="text" class="form-control" id="Mealsname" name="meals[]" value="{{$select_meals}}"  placeholder="Meals">                       
                           <button class="<?php if($i == $total_row){echo 'btn btn-success';}else{ echo 'btn btn-danger'; } ?>" type="button"  onclick="<?php if($i == $total_row ){echo 'hotel_fields();';}else{ $key= $key+1;
                              echo 'remove_hotel_fields('.$key.')';} ?>"><?php if($i == $total_row ){ ?>
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?php }else{ ?>
                               <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                              <?php } ?></button>
                        </div>
                     </div>
                     <div class="clear"></div>
                  </div>
                  <?php } ?>
                  <div id="hotel_fields">
                     </div>
                  </div>
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Vehicle Types</label>
               <div class="col-sm-10">
                  <input type="text" name="vehicle_type" value="{{ old('vehicle_type',$vehicle_type) }}" class="form-control" placeholder="">
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Itinerary</label>
               <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="">
               </div>
            </div>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Flight Details</label>
               <div class="col-sm-10">
                <?php 
                $flight_data_str = $OrderServiceVoucher ? $OrderServiceVoucher->flight_data: '';
                $flight_data_arr = json_decode($flight_data_str);
                // prd($flight_data_arr);
                $total_flight = 1;
                if(is_array($flight_data_arr)){

                  $total_flight = count($flight_data_arr);

                }else{
                  $flight_data_arr = [];
                  $flight_data_arr[] = [

                        'dot' => '',
                        'flight' => '',
                        'sector' => '',
                        'departures' => '',
                     ];
                }
                
                foreach ($flight_data_arr as $key => $flight_row ) {
                  $i =$key+1;

                  $dot =  $flight_row->dot ?? '';
                  $flight =  $flight_row->flight ?? '';
                  $sector =  $flight_row->sector ?? '';
                  $departures =  $flight_row->departures ?? '';
                  $arrivals =  $flight_row->arrivals ?? '';
                ?>
                  <div class="panel-body <?php if($i != $total_flight){ echo 'removeflightclass'.$i.'';} ?>">
                     <div class="col-sm-2 nopadding">
                        <div class="form-group">
                        <label for="input" class="col-form-label">DOT</label>
                           <input type="text" class="form-control" id="Locationname" name="dot[]" value="{{$dot}}" placeholder="Location name">
                        </div>
                     </div>
                     <div class="col-sm-2 nopadding">
                        <div class="form-group">
                        <label for="input" class="col-form-label">Flight No</label>
                           <input type="text" class="form-control" id="Hotel" name="flight[]" value="{{$flight}}"  placeholder="Flight No">
                        </div>
                     </div>
                     <div class="col-sm-3 nopadding">
                        <div class="form-group">
                        <label for="input" class="col-form-label">Sector</label>
                           <input type="text" class="form-control" id="Sector" name="sector[]" value="{{$sector}}"   placeholder="Sector">
                        </div>
                     </div>
                     <div class="col-sm-2 nopadding">
                        <div class="form-group">
                           <div class="input-group">
                           <label for="input" class="col-form-label">Departures</label>
                              <select class="form-control" id="departures" name="departures[]">
                                 <option value="">Select</option>
                                 <option value="1" <?php if($departures == '1'){echo 'selected'; } ?> >1</option>
                                 <option value="2" <?php if($departures == '2'){echo 'selected'; } ?> >2</option>
                                 <option value="3" <?php if($departures == '3'){echo 'selected'; } ?> >3</option>
                                 <option value="4" <?php if($departures == '4'){echo 'selected'; } ?> >4</option>
                                 <option value="5" <?php if($departures == '5'){echo 'selected'; } ?> >5</option>
                                 <option value="6" <?php if($departures == '6'){echo 'selected'; } ?> >6</option>
                                 <option value="7" <?php if($departures == '7'){echo 'selected'; } ?> >7</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3 nopadding">
                        <div class="form-group">
                        <label for="input" class="col-form-label">Arrivals</label>
                           <input type="text" class="form-control" id="Arrivals" name="arrivals[]" value="{{$arrivals}}"  placeholder="Arrivals">
                           <button class="<?php if($i == $total_flight ){echo 'btn btn-success';}else{ echo 'btn btn-danger'; } ?>" type="button"  onclick="<?php if($i == $total_flight ){echo 'flight_fields();';}else{ $key= $key+1;
                              echo 'remove_flight_fields('.$i.')';} ?>">
                              <?php if($i == $total_flight ){ ?>
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                 <?php }else{ ?>
                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                <?php } ?>
                           </button>
                        </div>
                     </div>
                     <div class="clear"></div>
                     <div id="flight_fields">
                     </div>
                  </div>
               <?php } ?>
               </div>
            </div>
            <?php /*<div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Payment Mode</label>
               <div class="col-sm-10">
                  <input type="text" class="form-control" name="payment_mode" value="{{ old('payment_mode',$payment_mode) }}"  placeholder="">
               </div>
            </div> */ ?>
            <div class="form-group row">
               <label for="input" class="col-sm-2 col-form-label">Remarks</label>
               <div class="col-sm-10">
                  <input type="text" name="remarks" value="{{ old('remarks',$remarks) }}" class="form-control" placeholder="">
               </div>
            </div>
           <?php /* <div class="row">
               <div class="col-sm-3 text-center right-content float-right">
                  <p>Highlights of Ladakh Winter Package</p>
                  <p>Ladakh, next you will explore bit of North and East of Ladakh</p>
               </div>
            </div> */ ?>
               <div class="col-sm-3 text-center">
                 <button class="btn btn-success" type="submit"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Submit </button>
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
   console.log(holtelSelect)
   console.log(holtelRefSelect)
   divtest.innerHTML = '<div class="col-sm-2 nopadding location_wrapper"><div class="form-group">'+ holtelSelect +'</div></div><div class="col-sm-2 nopadding hotel_wrapper"><div class="form-group">'+ holtelRefSelect +'</div></div><div class="col-sm-2 nopadding"><div class="form-group"> <input type="date" class="form-control" id="Date" name="date[]"  placeholder="Date"></div></div><div class="col-sm-2 nopadding"><div class="form-group"><div class="input-group"> <select class="form-control" id="nightsDate" name="night[]"><option >Select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option> </select><div class="input-group-btn"> </div></div></div></div> <div class="col-sm-2 nopadding"><div class="form-group"><div class="input-group"> <select class="form-control" id="rooms" name="rooms[]"><option >Date</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option> </select></div></div></div><div class="col-sm-2 nopadding"><div class="form-group"> <input type="text" class="form-control" id="Mealsname" name="meals[]"  placeholder="Meals"> <button class="btn btn-danger" type="button" onclick="remove_hotel_fields('+ room +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button></div></div><div class="clear"></div>';

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