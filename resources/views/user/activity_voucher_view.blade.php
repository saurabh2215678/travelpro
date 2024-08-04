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
   .service-vaucher .form-group.row .w-4/6 text-sm {
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

           <div class="clearfix"></div>
            <div class="ticket_edit_mode flex justify-between flex-row">
              <div class="edit_modelist"><h4 class="text-xl font-weight-bold">Activity Voucher</h4></div>
              <?php if(!empty($is_agent)){ ?>
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
                <li><a href="<?php echo route('user.activity_voucher_pdf',[$order_id]); ?>" title="Download PDF" onClick="return confirm('Are you sure to continue?')">Download PDF</a></li>
                <li><a href="<?php echo route('user.activity_voucher_sendsms',[$order_id]); ?>" title="SMS Ticket" onClick="return confirm('Are you sure to continue?')">Send SMS</a></li>
                <li><a href="<?php echo route('user.activity_voucher_sendmail',[$order_id]); ?>" title="Email Ticket" onClick="return confirm('Are you sure to continue?')" >Send Email</a></li>
               </ul>
              </div>
            </div>
            <div class="clearfix">&nbsp;</div>

            <?php /*<div class="form-title flex mt-5">
               <div class="w-1/2 col-form-label text-xl font-weight-bold p-3"><h4>Activity Voucher</h4></div>

               <?php if(!empty($is_agent)){ ?>
               <div class="edit_modelist">
               <ul>
                <li><label><input type="checkbox" class="hidedetails" id="hide_agent_details" name="hide_agent_details" value="1" <?php if($order->hide_agent_details==1){ echo 'checked';}?> /> <span>Hide My Details</span></label></li>
                <li><label><input type="checkbox" class="hide_pricedetails" id="hide_price_details" name="hide_price_details" value="1" <?php if($order->hide_price_details==1){ echo 'checked';}?> /> <span>Hide Price Details</span></label></li>
                <li><label><a href="#" id="editMode">Edit Markup</a> </label></li>
               </ul>
              </div>
              <?php } ?>
               <div class="w-1/2 font-weight-bold text-right">
               <div class="text-right" style="margin-top: 9px;padding-right: 0.2rem;">
              <a href="<?php echo route('user.activity_voucher_pdf',[$order_id]) ?>" title="Activity Voucher PDF" class="btn btn-success cab_view_fancy" style="display: inline-block;border-radius: 5rem;padding: 0.5rem 0.8rem;">Download PDF </a>
            </div>
            </div>
            </div>*/ ?>
            @include('snippets.front.flash')
            @include('snippets.errors')
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Trip Title</label>
               <div class="w-4/6 text-sm">
                  {{$title}}
               </div>
            </div> 
            <div class="clearfix"></div>
            <h3>Activity Details</h3>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Trip Date </label>
               <div class="w-4/6 text-sm">
                 {{$trip_date}}
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Destination / Drop Address </label>
               <div class="w-4/6 text-sm">
                 {{$destination}}
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Duration </label>
               <div class="w-4/6 text-sm">
                 {{$duration}}
               </div>
            </div>
             <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Activity Location </label>
               <div class="w-4/6 text-sm">
                 {{$address}}
               </div>
            </div>
            
            <div class="clearfix"></div>
            <h3>Activity Contact Details</h3>
           
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Contact Person </label>
               <div class="w-4/6 text-sm">
                 {{$contact_person}}
               </div>
            </div>
          
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Contact Phone</label>
               <div class="w-4/6 text-sm">
                 {{$contact_phone}}
               </div>
            </div>
             <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Contact Email</label>
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
             
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Persons</label>
               <div class="w-4/6 text-sm">
                  {{$person}}
               </div>
            </div>

            @if($hide_price_details != 1)
            <div class="clearfix"></div>
            <h3>Fare Details</h3>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Activity Charges </label>
               <div class="w-4/6 text-sm">
                 {{$package_charges}}
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Amount Paid</label>
               <div class="w-4/6 text-sm">
                {{$paid_amount}}
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Amount Due</label>
               <div class="w-4/6 text-sm">
                 {{$due_amount}}
               </div>
            </div>
            <div class="form-group flex">
               <label for="input" class="w-2/6 col-form-label">Remarks</label>
               <div class="w-4/6 text-sm">
                 {{$remarks}}
               </div>
            </div>
            @endif
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
    url: "{{ route('user.showHideActivitySettings',[$order_id]) }}",
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
    url: "{{ route('user.showHideActivitySettings',[$order_id]) }}",
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
    url: "{{ route('user.showHideActivitySettings',[$order_id]) }}",
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
    url: "{{ route('user.showHideActivitySettings',[$order_id]) }}",
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