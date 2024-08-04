@component('admin.layouts.main')
@slot('title')
Admin - Manage Enquiries - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<style>
   .centersec {
   min-height: auto;
   }
   .enquiries-list {
   padding: 10px 35px !important;
   }
   .enquiries-list h5.title-text-left {
   padding: 0 17px;
   }
   .btn-group.switch-btn .btn-default.active {
   background: #01b2a8;
   color: #fff;
   }
   .btn-group.switch-btn .btn-default {
   background: #efedf0;
   color: #000;
   border-radius: 50px;
   padding: 3px 15px;
   font-size: 13px;
   margin-left: 0px;
   box-shadow: none;
   }
   .btn-group.switch-btn {
   border: 5px solid #efedf0;
   border-radius: 50px;
   }
   .users-detail button.button-typ {
   border: 0;
   background: none;
   }
   .enquiries-list table#datatable tbody {
   background: #efedf0;
   padding: 15px;
   }
   .enquiries-list table#datatable thead th {
   font-weight: 500;
   border: 0;
   }
   .enquiries-list table#datatable thead {
   background: #526375;
   color: #fff;
   }
   .enquiries-list table#datatable i.fa {
   color: #00b1a7;
   }
   .users .fa {
   font-size: 22px;
   }
   #target-1 ul li {
   padding: 10px;
   }
   #target-1 ul {
   margin: 0;
   padding: 0;
   list-style: none;
   }
   #target-1 {
   background: #f3ffff;
   /* width: 182px; */
   /* height: 300px; */
   /* height: 160px; */
   padding: 5px;
   display: none;
   position: absolute;
   right: 105px;
   border: 2px solid #79d5d0;
   border-radius: 5px;
   z-index: 999;
   top: 33%;
   }
   .users button {
   border: 0;
   background: #0000;
   }
   span#name {
    font-weight: 600;
    color: #36648b;
}
.enquiries-list .user-list thead th {
    color: #545454;
    font-weight: 600 !important;
}
a.btn-assign {
    background: #fff;
    padding: 5px 25px;
    border-radius: 50px;
}
a.btn-assign:hover {
    background: #01b2a8;
    color: #fff;
}
.enquiries-list .user-list tbody> tr td {
    vertical-align: middle;
}
.article table#user_datatable {
    background: initial;
}
.enquiries-list .user-list thead {
    background: #0000 !important;
}
.enquiries-list .user-list tbody> tr {
    background: #e0dfe4;
}
   #target-1 ul i.fa {
   font-size: 14px;
   }
   .hide
   {
   display:none;
   }
   /*  added this - not working  */
   .hide-1-yes
   {
   display:none;
   }
</style>
<div class="top_title_admin">
   <div class="title">
      <h2>Enquiries (1)</h2>
   </div>
</div>
<!-- Start Search box section-->
<div class="centersec">
   <div class="bgcolor">
      <div class="table-responsive">
         <form class="form-inline" method="GET">
            <div class="col-md-2">
               <label>Name:</label><br/>
               <input type="text" name="name" class="form-control admin_input1" value="">
            </div>
            <div class="col-md-6">
               <label></label><br>
               <button type="submit" class="btn btn-success">Search</button>
               <a href="" class="btn_admin2">Reset</a>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- End Search box Section -->
<div class="container enquiries-list">
   <div class="row">
      <h5 class="title-text-left">Enquiries List</h5>
      <div class="col-md-12">
         <table id="datatable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Contact Number</th>
                  <th>Email ID</th>
                  <th>Country</th>
                  <th>When to travel</th>
                  <th>Have you travel us before?</th>
                  <th>Assign</th>
               </tr>
            </thead>
            <!-- <tfoot>
               <tr>
                  <th>Name</th>
                  <th>Position</th>
                  <th>Office</th>
                  <th>Age</th>
                  <th>Start date</th>
                  <th>Salary</th>
                  <th>Edit</th>
                  <th>Delete</th>
               </tr>
               </tfoot> -->
            <tbody>
               <tr>
                  <td>Suraj Kumar</td>
                  <td>+91 971 814 0897</td>
                  <td>info@yourmail.com</td>
                  <td>India</td>
                  <td><i class="fa fa-calendar"></i> 09 Jan, 23</td>
                  <td>
                     <div class="btn-group switch-btn" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on active">
                        <input type="radio" value="1" name="multifeatured_module[module_id][status]" checked="checked">Yes</label>
                        <label class="btn btn-default btn-off">
                        <input type="radio" value="0" name="multifeatured_module[module_id][status]">No</label>
                     </div>
                  </td>
                  <td>
                     <div class="users">
                        <button class="show-1-yes"><i class="fa fa-plus-circle"></i></button>
                        <button class="hide-1-yes"><i class="fa fa-minus-circle"></i></button>
                        <div id="container-target">
                           <div id="target-1">
                              <ul>
                                 <li><a href="#"><i class="fa fa-user-o"></i> Suraj Kumar Pandit</a></li>
                                 <li><a href="#"><i class="fa fa-user-o"></i> Rohit Kumar</a></li>
                                 <li><a href="#"><i class="fa fa-user-o"></i> Suraj</a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     <div class="users-detail">
                        <button class="button-typ">Details <i class="fa fa-angle-down"></i></button>
                     </div>
                  </td>
               </tr>
               <tr>
                  <td colspan="7">
                     <div class="content-class">
                        <div class="article">
                           <table id="user_datatable" class="user-list table table-striped" cellspacing="0" width="100%">
                              <thead>
                                 <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Suitability</th>
                                    <th></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td>
                                       <i class="fa fa-user-o"></i> <span id="name">Suraj Kumar</span><br>
                                       <span>Last Contact: 05/01-2023</span>
                                    </td>
                                    <td>
                                       Technical support
                                    </td>
                                    <td>
                                       <div class="users-star">
                                          <i class="fa fa-star"></i>
                                          <i class="fa fa-star"></i>
                                          <i class="fa fa-star"></i>
                                          <i class="fa fa-star"></i>
                                          <i class="fa fa-star"></i>
                                       </div>
                                       40% Progress
                                    </td>
                                    <td>
                                       <a class="btn-assign" href="#">Assign</a>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                              <label for="inputState">Destination</label>
                              <select id="inputState" class="form-control">
                                <option selected>Choose...</option>
                                <option>select 1</option>
                                <option>select 2</option>
                                <option>select 3</option>
                                <option>select 4</option>
                              </select>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="inputState">Enquiry for</label>
                              <select id="inputState" class="form-control">
                                <option selected>Choose...</option>
                                <option>select 1</option>
                                <option>select 2</option>
                                <option>select 3</option>
                                <option>select 4</option>
                              </select>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="inputState">Status</label>
                              <select id="inputState" class="form-control">
                                <option selected>Choose...</option>
                                <option>select 1</option>
                                <option>select 2</option>
                                <option>select 3</option>
                                <option>select 4</option>
                              </select>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="inputState">Lead Status</label>
                              <select id="inputState" class="form-control">
                                <option selected>Choose...</option>
                                <option>select 1</option>
                                <option>select 2</option>
                                <option>select 3</option>
                                <option>select 4</option>
                              </select>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="inputState">Sub Lead Status</label>
                              <select id="inputState" class="form-control">
                                <option selected>Choose...</option>
                                <option>select 1</option>
                                <option>select 2</option>
                                <option>select 3</option>
                                <option>select 4</option>
                              </select>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="inputState">Follow update</label>
                              <input type="date" name="">
                            </div>
                            
                            <div class="form-group col-md-4">
                              <label for="inputPassword4">Password</label>
                              <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
                            </div>
                            <div class="form-group col-md-4">
                            <label for="inputAddress">Address</label>
                            <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputAddress2">Address 2</label>
                            <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                          </div>
                          <div class="form-group col-md-4">
                              <label for="inputCity">City</label>
                              <input type="text" class="form-control" id="inputCity">
                            </div>
                            <div class="form-group col-md-4">
                              <label for="inputState">State</label>
                              <select id="inputState" class="form-control">
                                <option selected>Choose...</option>
                                <option>select 1</option>
                                <option>select 2</option>
                                <option>select 3</option>
                                <option>select 4</option>
                              </select>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="inputZip">Zip</label>
                              <input type="text" class="form-control" id="inputZip">
                            </div>
                            <div class="form-group col-md-12">
                            <label for="exampleFormControlTextarea1">Example textarea</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                          </div>
                          </div>
                          <button type="submit" class="btn btn-primary">Sign in</button>
                        </form>
                     </div>

                  </td>
               </tr>
               <tr>
                  <td>Suraj Kumar</td>
                  <td>+91 971 814 0897</td>
                  <td>info@yourmail.com</td>
                  <td>India</td>
                  <td><i class="fa fa-calendar"></i> 09 Jan, 23</td>
                  <td>
                     <div class="btn-group switch-btn" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on active">
                        <input type="radio" value="1" name="multifeatured_module[module_id][status]" checked="checked">Yes</label>
                        <label class="btn btn-default btn-off">
                        <input type="radio" value="0" name="multifeatured_module[module_id][status]">No</label>
                     </div>
                  </td>
                  <td>
                     <a href="#"><i class="fa fa-plus-circle"></i></a>
                  </td>
               </tr>
               <tr>
                  <td>Suraj Kumar</td>
                  <td>+91 971 814 0897</td>
                  <td>info@yourmail.com</td>
                  <td>India</td>
                  <td><i class="fa fa-calendar"></i> 09 Jan, 23</td>
                  <td>
                     <div class="btn-group switch-btn" id="status" data-toggle="buttons">
                        <label class="btn btn-default btn-on active">
                        <input type="radio" value="1" name="multifeatured_module[module_id][status]" checked="checked">Yes</label>
                        <label class="btn btn-default btn-off">
                        <input type="radio" value="0" name="multifeatured_module[module_id][status]">No</label>
                     </div>
                  </td>
                  <td>
                     <a href="#"><i class="fa fa-plus-circle"></i></a>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
<div class="centersec">
   @include('snippets.errors')
   @include('snippets.flash')
</div>
@slot('bottomBlock')
@endslot
@endcomponent
<script>
   /*  show 1 - hide 1  */
   
   $('.show-1-yes').click(function() {
       $('#target-1').show(500);
       $('.show-1-yes').hide(0);
       $('.hide-1-yes').show(0);
   });
   $('.hide-1-yes').click(function() {
       $('#target-1').hide(500);
       $('.show-1-yes').show(0);
       $('.hide-1-yes').hide(0);
   });
   
   /**  // user content button toggle **/
   
   $( document ).ready(function() {
       // content button toggle
           $(".content-class").hide();
       $(".button-typ").click( function(){
               //$(this).text( $(this).text() === "Open Article +" ? "Details ⌄" : "Open Article +" );
               $(".content-class").slideToggle(900);
       } );
   });
   
</script>