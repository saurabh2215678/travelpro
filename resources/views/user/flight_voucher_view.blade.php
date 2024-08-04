@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<style>
   .service-vaucher .form-group.row { display: flex; align-items: center; margin-bottom: 0; }
   .service-vaucher .form-group.row label.col-form-label { margin-bottom: 0; /* border: 1px solid #a7a7a7; */ height: 30px; box-shadow: none; padding: 6px 12px; display: block; }
   .service-vaucher .form-group.row .col-sm-10 {padding-left: 0;}
   .service-vaucher .form-group.row .form-control { border-radius: 0; border: 1px solid #a7a7a7; height: 35px; }
   .right-content.float-right {float: right;}
   .service-vaucher .form-group.row .form-group label.col-form-label {padding-left: 0;}
   .panel-body {padding: 0px;}
   .service-vaucher .panel-body button.btn.btn-success { position: absolute; right: 0; top: 30px; border-radius: 0; padding: 9px 20px; }
   .service-vaucher .panel-body button.btn.btn-danger { position: absolute; right: 0; top: 0px; border-radius: 0; padding: 9px 20px; }
   .service-vaucher .panel-body .removeclass1 button.btn.btn-danger {top: 30px;}
   .service-vaucher .panel-body .form-group {position: relative;}
   .service-vaucher .form-group.row .input-group {display: block;}
    .form-title {background: #e5e5e5;margin-bottom: 15px;}
    .form-title h4 {font-weight: 600;}
    .service-vaucher h3 { padding: 0; margin: 7px 10px 0; font-size: 1rem; font-weight:600; }
    .ticket_edit_mode ul li a:hover {background: var(--secondary-color);}
    .service-vaucher label {margin-left: 10px;}
    .modal-open .modal .modal-content .modal-header { padding: 5px 15px; background: #00b2a7; color: #fff; }
    .modal-open .modal .modal-content .modal-header button.close { color: #fff; opacity: 1; font-size: 32px; }
    ul.inline-right li {display: inline-block;}
    ul.inline-right li .inputbox span { padding-left: 1rem; padding-right: 1rem; }
    ul.inline-right li .inputbox input { position: absolute; left: 0; width: auto; }
    ul.inline-right li .inputbox { position: relative; display: flex; align-items: center; }
    @media (max-width: 767px){  
    .bgcolor.service-vaucher form {padding: 25px 20px !important;}
    .w-medium-model {overflow: auto;}
    ul.inline-right li .inputbox span {padding-left: 1.5rem;}
    }
</style>
@endslot
<?php
$user_id = Auth::user()->id??'';
$is_agent = Auth::user()->is_agent??'';
$agent_id = 0;
if($is_agent == 1 && $order->agent_id == $user_id) {
  $agent_id = $user_id;
}
?>
<div class="centersec">
   <div class="bgcolor service-vaucher">
      <div class="md:container">
      <div class="row">
         <form style="padding:25px 120px;padding-top: 0;" method="POST">
           {{ csrf_field() }}
            <!-- <div class="form-title flex mt-5">
               <div class="w-1/2 col-form-label text-xl font-weight-bold p-3"></div>
               <?php /*<div class="w-1/2 font-weight-bold text-right">
                <ul class="inline-right p-1">
                 <?php if(!empty($is_agent)){ ?>
                 <li><div class="inputbox"><input type="checkbox" class="hidemarkup" id="hide_markup" name="hide_markup" value="1" <?php if($order->hide_markup==1){ echo 'checked';}?> > <span>Hide MarkUp</span></div></li>
                <?php } ?>

                 <li><a href="<?php echo route('user.flight_voucher_pdf',[$order_id]) ?>" title="Flight Voucher PDF" class="btn btn-success cab_view_fancy p-3">Download PDF</a></li>
               </ul>
               </div>*/ ?>
            </div> -->
            
            <div class="clearfix"></div>
            <div class="ticket_edit_mode flex justify-between flex-row">
              <?php if(!empty($is_agent)){ ?>
                <div class="edit_modelist"><h4 class="text-xl font-weight-bold">Flight Ticket</h4></div>
              <div class="edit_modelist">
               <ul>
                <li><label><input type="checkbox" class="hidedetails" id="hide_agent_details" name="hide_agent_details" value="1" <?php if($order->hide_agent_details==1){ echo 'checked';}?> /> <span>Hide My Details</span></label></li>
                <li><label><input type="checkbox" class="hide_pricedetails" id="hide_price_details" name="hide_price_details" value="1" <?php if($order->hide_price_details==1){ echo 'checked';}?> /> <span>Hide Price Details</span></label></li>
                <li><label><a href="#" id="editMode">Edit Markup</a> </label></li>
               </ul>
              </div>
              <?php } ?>
              <div class="edit_modelist">
              <ul>
                <li><a href="<?php echo route('user.flight_voucher_pdf',[$order_id]); ?>" title="Download PDF" onClick="return confirm('Are you sure to continue?')">Download PDF</a></li>
                <li><a href="<?php echo route('user.flight_voucher_sendsms',[$order_id]); ?>" title="SMS Ticket" onClick="return confirm('Are you sure to continue?')">SMS Ticket</a></li>
                <li><a href="<?php echo route('user.flight_voucher_sendmail',[$order_id]); ?>" title="Email Ticket" onClick="return confirm('Are you sure to continue?')" >Email Ticket</a></li>
               </ul>
              </div>
            </div>
            <div class="clearfix"></div>

            @include('snippets.front.flash')
            @include('snippets.errors')

            <h3>Traveller Contact Details</h3>
            <div class="form-group flex">
               <div class="col-form-label px-3"><strong>Name:</strong> <span class="text-sm">{{$name}}</span> | <strong>Phone:</strong> <span class="text-sm"><a href="tel:{{$phone}}">{{$phone}}</a></span> | <strong>Email:</strong> <span class="text-sm"><a href="mailto:{{$email}}">{{$email}}</a></span></div>               
            </div>

            <div class="row">
              <div class="col-md-12">
                <h3 class="px-3" style="max-width: 1200px;">Flight Details</h3>
                {!!$itineraries!!}
              </div>
            </div>
         </form>
      </div>
      </div>
   </div>
</div>
</div>

<div id="editModeModal" class="editmode_modal">
  <div class="editmode_modal-content">
    <span class="editmode_close">&times;</span>
    <h4>Edit Markup</h4>
    <input type="text" name="markup" id="markup" value="{{$order->markup}}">
    <input type="button" name="save_markup" id="save_markup" value="Save">
  </div>

</div>



@slot('bottomBlock')
<script type="text/javascript">
$("#hide_markup").change(function(){
  var hide_markup = 0;
  if($(this).is(":checked")){
    hide_markup = 1;
  }
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route('user.showHideFlightSettings',[$order_id]) }}",
    type: 'post',
    dataType: 'json',
    data: {'action':'hide_markup','hide_markup':hide_markup},
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    beforeSend: function() {

    },
    complete: function() {

    },
    success: function(response) {
      if(response.success) {
        if(response.message) {
          alert(response.message)
        }
      } else if(response.message) {
        alert(response.message)
      }
      window.location.reload();
    }
  });
});


$("#hide_agent_details").change(function(){
  var hide_agent_details = 0;
  if($(this).is(":checked")){
    hide_agent_details = 1;
  }
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route('user.showHideFlightSettings',[$order_id]) }}",
    type: 'post',
    dataType: 'json',
    data: {'action':'hide_agent_details','hide_agent_details':hide_agent_details},
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    beforeSend: function() {

    },
    complete: function() {

    },
    success: function(response) {
      if(response.success) {
        if(response.message) {
          alert(response.message)
        }
        window.location.reload();
      } else if(response.message) {
        alert(response.message)
      }
    }
  });
});

$("#hide_price_details").change(function(){
  var hide_price_details = 0;
  if($(this).is(":checked")){
    hide_price_details = 1;
  }
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route('user.showHideFlightSettings',[$order_id]) }}",
    type: 'post',
    dataType: 'json',
    data: {'action':'hide_price_details','hide_price_details':hide_price_details},
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    beforeSend: function() {

    },
    complete: function() {

    },
    success: function(response) {
      if(response.success) {
        if(response.message) {
          alert(response.message)
        }
      } else if(response.message) {
        alert(response.message)
      }
      window.location.reload();
    }
  });
});


$("#save_markup").on('click',function(){
  var markup = $('#markup').val();
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route('user.showHideFlightSettings',[$order_id]) }}",
    type: 'post',
    dataType: 'json',
    data: {'action':'save_markup','markup':markup},
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    beforeSend: function() {

    },
    complete: function() {

    },
    success: function(response) {
      if(response.success) {
        if(response.message) {
          alert(response.message)
        }
      } else if(response.message) {
        alert(response.message)
      }
      window.location.reload();
    }
  });
});


// Get the modal
var ebModal = document.getElementById('editModeModal');

// Get the button that opens the modal
var ebBtn = document.getElementById("editMode");

// Get the <span> element that closes the modal
var ebSpan = document.getElementsByClassName("editmode_close")[0];

// When the user clicks the button, open the modal 
ebBtn.onclick = function() {
    ebModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
ebSpan.onclick = function() {
    ebModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == ebModal) {
        ebModal.style.display = "none";
    }
}

</script>
@endslot
@endcomponent