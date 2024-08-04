@component('admin.layouts.main')
@slot('title')
Admin - Flight Voucher View- {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<?php 
$routeName = CustomHelper::getAdminRouteName();
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
   <div class="bgcolor">
      <div class="order_reference">
         <ul class="order_inner_box">
            <li class="">
               <div class="title">
                  <h3>Order Reference Number:</h3>
               </div>
               <div class="title-details">
                  <span>FMNJA3U3XHC7T</span>
               </div>
            </li>
            <li>
            <div class="title">
                  <h3>User Information:</h3>
               </div>
               <div class="user-information">
                  <ul>
                     <li>User Nmae: <span>Ms. Overland Escape</span></li>
                     <li>Mobile: <span>9876543210</span></li>
                  </ul>
               </div>
               <div class="status-details">
            <ul>
                     <li>Order Status <span>
                        <form class="form-inline" method="GET">
                           <select name="order_type" class="form-control admin_input1 select2">
                              <option value="">--Select Status Type--</option>
                              <option value="1">Package</option>
                              <option value="2">Pay-Online</option>
                              <option value="3">Flight</option>
                              <option value="4">Cab</option>
                              <option value="5">Hotel</option>
                              <option value="6">Activity</option>
                              <option value="7">Wallet</option>
                           </select>
                        </form>
                     </span></li>
                     <li>Submission Time : <span>02/06/2023   10:45am</span></li>
                  </ul>
               </div>
            </li>
            <li class="">
               <div class="title">
                  <h3>Special Request:</h3>
               </div>
               <div class="title-details">
                  <span>Cluster Fare</span>
                  <div class="user-request">
                  <ul>
                     <li>Offline Booking Points: <span>0.0</span></li>
                     <li>Voucher: <span>Null</span></li>
                     <li>EMI Month: <span>0</span></li>
                     <li>PG Reference: <span>FMNJA3U3XHC7T_1685681668527</span></li>
                  </ul>
                  <a href="javascript:;" title="Add Comment" class="btn sent_email btn_admin2" id="cust_btn">Add Comments</a>
                  <a href="#" title="Flight Voucher PDF" class="btn btn-success">Refund FAQs </a>
               </div>
               </div>
            </li>
         </ul>
      </div>

      <div class="cab_reference_Details">
         <ul class="order_inner_box">
            <li class="">
               <div class="title">
                  <h3>Cab Details:</h3>
               </div>
               <div class="title-details">
                  <span>PNR Status(HK)</span>
               </div>
            </li>
            <li>
               <div class="user-information">
                  <ul>
                     <li style="padding-left: 0;">Sringar <i class="fa-solid fa-car-side"></i> Leh</li>
                     <li><i class="fa-solid fa-calendar"></i> 02/06/2023</li>
                     <li><i class="fa-solid fa-clock"></i> 02/06/2023</li>
                  </ul>
               </div>
            </li>
            <li>
               <div class="user-information-price">
                  <ul>
                     <li style="padding-left: 0;"><span style="padding-left: 0;">Fees: Rs 1699</span> + <span>Booking Surcharge: Rs 550</span> + <span>Service Tax: Rs 150</span> + <span>Payment Fee: Rs 242 </span>+ <span>Discount: Rs 120 </span> <strong>Total Amount : Rs 1968</strong></li>
                  </ul>
               </div>
            </li>
            <li>
               <div class="user_status">
               Order Status <span>
                        <form class="form-inline" method="GET">
                           <select name="order_type" class="form-control admin_input1 select2">
                              <option value="">--Select Status Type--</option>
                              <option value="1">Package</option>
                              <option value="2">Pay-Online</option>
                              <option value="3">Flight</option>
                              <option value="4">Cab</option>
                              <option value="5">Hotel</option>
                              <option value="6">Activity</option>
                              <option value="7">Wallet</option>
                           </select>
                        </form>
                     </span>
                     <a href="javascript:;" title="Cancel Selected Passanger" class="btn btn-success" id="cust_btn">Cancel Selected Passanger</a>
                  <a href="#" title="Reschdule Selected" class="btn sent_email btn_admin2">Reschdule Selected</a>
                  <a href="#" title="View Fare Rules" class="btn btn-success">View Fare Rules</a>
                  
               </div>
            </li>
            <li class="passenger-details">
               <div class="table-responsive py-5"> 
               <h4>Passenger Details</h4>
                  <table class="table table-bordered table-hover">
                  <thead class="thead-dark">
                     <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Fare</th>
                        <th scope="col">Tax</th>
                        <th scope="col">Ticket Number</th>
                        <th scope="col">Cancel</th>
                        <th scope="col">Reschedule</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Ms. Rajeev Mehta</td>
                        <td>Adult</td>
                        <td>1690</td>
                        <td>00</td>
                        <td>FMNJA3U3XHC7T</td>
                        <td>
                           <div class="checkbox checkbox-warning">
                           <input id="checkbox" type="checkbox">
                           <label for="checkbox"></label>
                        </div>
                        </td>
                        <td>
                        <div class="checkbox checkbox-warning">
                           <input id="checkbox2" type="checkbox">
                           <label for="checkbox2"></label>
                        </div>
                        </td>
                     </tr>
                  </tbody>
                  </table>
                  <table class="table table-bordered table-hover">
                  <thead class="thead-dark">
                     <tr>
                        <th scope="col">Type</th>
                        <th scope="col">Commission Given</th>
                        <th scope="col">Commission Recalled</th>
                        <th scope="col">TDS</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Basic</td>
                        <td>00</td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>PLB</td>
                        <td>150</td>
                        <td></td>
                        <td>1.8%</td>
                     </tr>
                     <tr>
                        <td>Deposit Incentive</td>
                        <td>65</td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td style="color:#00b2a7;"><strong>Total</strong></td>
                        <td style="color:#00b2a7;"><strong>1920</strong></td>
                        <td>00</td>
                        <td>2.29%</td>
                     </tr>
                  </tbody>
                  </table>
                  </div>
                  
            </li>
         </ul>
      </div>
   </div>
</div>



       
















         
      </div>
      
   </div>
</div>
<!-- Modal -->
</div>

@slot('bottomBlock')


@endslot
@endcomponent