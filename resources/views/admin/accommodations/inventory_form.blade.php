{{ csrf_field() }}
<div class="col-md-12">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="ajax_msg"></div>
  <div class="dateRow_list d-flex">   
    <table class="table table-borderless" >
      <tbody>
        <tr>
          <th></th>
          <?php 
          foreach($period as $dt){
            $date =  $dt->format("d-m-Y");
            $last_date =  $dt->format("Y-m-d");
            $date_id =  $dt->format("dmY");
            ?>
            <th class="active week">{{$date}}</th>
          <?php }
          ?>
        </tr>
      </tbody>
      <tbody class="date_input_box" >
        <?php 
        function check($number){
          if($number % 2 == 0){
              echo "Even"; 
          }
          else{
              echo "Odd";
          }
      }

        foreach ($rooms_data as $key => $room) {
          $room_id= $room->id;
          $plan_data= $room->planData; ?>
          <tr class="parent {{ check($room_id) }}" data-id="{{$room_id}}" data-show="">
            <td style="width: 150px;vertical-align: middle;">{{$room->room_type_name ?? ''}}<br><div class="w-full"><p class="view-rate">Show Rates</p><p class="hide-rate">Hide Rates</p><span></span></div>
            </td>
        
            <?php 
            $curr_date =strtotime(date('Y-m-d'));
            $before_date = date('Y-m-d',strtotime('-15 days',$curr_date));
            foreach($period as $dt){
              $date =  $dt->format("Y-m-d");
              $date_id =  $dt->format("Ymd");
              $key_id = $date_id.'_'.$room->id;
              $inventory= '';
              $booked= '';
                
              foreach ($inventories as $inventory_row) {               
                if($inventory_row->room_id == $room_id & $date == $inventory_row->date){
                  $inventory = $inventory_row->inventory ;
                  $booked = $inventory_row->booked ;
                  break;
                }
              }

              $disabled = '';
              if(strtotime($before_date) > strtotime($date) ){
                $disabled = 'disabled';
              } ?>
              <td style="width: 100px;" class="date">   
                 <input type="text" name="inventory[{{$date_id}}][{{$room_id}}]" value="{{$inventory}}" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7" {{$disabled}}>

                 <?php if($booked){ ?>
                <label for=" " class="label-text"> {{$booked}} Booked</label>
              <?php } ?>
             </td>
        <?php }  ?>
          </tr>

            <?php
              if($plan_data && $plan_data->count() > 0){
              foreach ($plan_data as $key => $plan_row) { ?>

              <tr class="child child_{{$room_id}}" style="display:none;">
               <td>         
                <strong style="font-size:13px;color:#004a43;display: flex;justify-content: space-between;position:relative;">{{$plan_row->plan_name}} Price <div class="text-right" style="color: #4a4a4a;"><span>2<i class="fa fa-user" style="color: #009688;"></i> </span><br>base</div><br><div class="text-right" style="color: #4a4a4a;position: absolute;top: 40px;right: 0;right: 0rem;"><span>1<i class="fa fa-user" style="color: #009688;"></i> </span><br>base</div></strong>
               </td>
                <?php foreach($period as $dt){
              $date =  $dt->format("Y-m-d");
              $date_id =  $dt->format("Ymd");
              $key_id = $date_id.'_'.$room->id;
              ?>
                <?php
                $plan_id =$plan_row->id ?? '';

                // $inventory ='';
                $price ='';
                $single_price ='';
                $extra_adult ='';
                $extra_child_1 ='';
                $extra_child_2 ='';
              foreach ($inventories as $inventory_row) {
               
                if($inventory_row->room_id == $room_id && $inventory_row->plan_id == $plan_id && $date == $inventory_row->date){

                  // $inventory = $inventory_row->inventory ;
                  $price = $inventory_row->price ;
                  $single_price = $inventory_row->single_price ;
                  $extra_adult = $inventory_row->extra_adult ;
                  $extra_child_1 = $inventory_row->extra_child_1 ;
                  $extra_child_2 = $inventory_row->extra_child_2 ;
                  break;
                }

              } 
              //plans ?>
               <td>
                <input type="text" name="price[{{$date_id}}][{{$room_id}}][{{$plan_id}}]" value="{{$price}}" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7"><br>

                <input type="text" name="single_price[{{$date_id}}][{{$room_id}}][{{$plan_id}}]" value="{{$single_price}}" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7">
                
              </td>
            <?php }  ?>
          </tr>
<!-- Extra -->
              <tr class="child child_{{$room_id}}" style="display:none;">
               <td>
              <br>
              Extra Adult Charge :
              <br>
              <br>
              <br>
              Extra Child (0-6):
              <br>
              <br>
              <br>
              Extra Child (7-12):
               </td>
                <?php foreach($period as $dt){
              $date =  $dt->format("Y-m-d");
              $date_id =  $dt->format("Ymd");
              $key_id = $date_id.'_'.$room->id;
              ?>
                <?php
                $plan_id =$plan_row->id ?? '';

                // $inventory ='';
                $price ='';
                $extra_adult ='';
                $extra_child_1 ='';
                $extra_child_2 ='';
              foreach ($inventories as $inventory_row) {
               
                if($inventory_row->room_id == $room_id && $inventory_row->plan_id == $plan_id && $date == $inventory_row->date){

                  // $inventory = $inventory_row->inventory ;
                  $price = $inventory_row->price ;
                  $extra_adult = $inventory_row->extra_adult ;
                  $extra_child_1 = $inventory_row->extra_child_1 ;
                  $extra_child_2 = $inventory_row->extra_child_2 ;
                  break;
                }
              } ?>
              <!-- //plans -->
               <td>
                
                <br>
                <input type="text" name="extra_adult[{{$date_id}}][{{$room_id}}][{{$plan_id}}]" value="{{$extra_adult}}" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7">
                <br>
                <input type="text" name="extra_child_1[{{$date_id}}][{{$room_id}}][{{$plan_id}}]" value="{{$extra_child_1}}" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7">
                <br>
                <input type="text" name="extra_child_2[{{$date_id}}][{{$room_id}}][{{$plan_id}}]" value="{{$extra_child_2}}" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7">
              </td>
            <?php }  ?>

          </tr>

<?php }  ?>
            <?php }else{ ?>
              <tr  class="child child_{{$room_id}}" style="display:none">
                  <td colspan="12">
                  <div class="alert alert-warning" role="alert" style="padding: 5px;margin-bottom: 0;"> <i class="fa fa-lightbulb-o fa-1x"></i>Plans are not available in this room type</div>

                <?php
                foreach($period as $dt){
                  $date =  $dt->format("Y-m-d");
                  $date_id =  $dt->format("Ymd");
                  $key_id = $date_id.'_'.$room->id;
                  ?>   
                  <!-- //no plans -->
                
                    <input type="hidden" name="price[{{$date_id}}][{{$room_id}}][0]" value="" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7">
                    <input type="hidden" name="extra_adult[{{$date_id}}][{{$room_id}}][0]" value="" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7">
                    <input type="hidden" name="extra_child_1[{{$date_id}}][{{$room_id}}][0]" value="" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7">
                    <input type="hidden" name="extra_child_2[{{$date_id}}][{{$room_id}}][0]" value="" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" maxlength="7">
                  </td>
                <?php  }  ?>
              </tr>
            <?php  } }  ?>
      
       
        
      </tbody>

    </table>
   
  </div>
  <div class="btn pull-right">
    <input type="hidden" name="accommodation_id" value="{{$accommodation_id}}" />
    <input type="hidden" id="has_unsaved" value="0">
    <button type="submit" class="btn_admin btnSubmit" disabled value="submit"> Save</button>
  </div>
</div>


<style>
td.labelbtn {
    cursor: pointer;
}
  .ext_field {
    background-color: #fff;
    border-radius: 0.5rem;
    color: #000;
    max-height: 18rem;
    opacity: 0;
    overflow: auto;
    padding: 1rem 0;
    pointer-events: none;
    top: 15rem;
    transition: all .5s ease;
}
.ext_field.active td {
    background: #0000000d !important;
}
.ext_field2.active td {
    background: #1b94b624 !important;
}
.ext_field.active {
    opacity: 1;
    pointer-events: all;
    top: calc(100% + 0.5rem);
}

.parent td span {
    background: #00b2a7;
    height: 12px;
    position: relative;
    width: 2px;
    display: inline-block;
    margin-left: 15px;
    top: 2px;
}
.parent td span:after {
    background: #00b2a7;
    content: "";
    height: 2px;
    left: -6px;
    position: absolute;
    top: 5px;
    width: 14px;
}
tbody.date_input_box [data-show=show] span {
    background: transparent;
}
.dateRow_list tbody.date_input_box tr.parent>td {
    cursor: pointer;
    font-weight: 600;
    font-size:14px;
}
.dateRow_list tbody.date_input_box tr.child.child_2 td input.form-control {
    font-size: 11px;
}
.dateRow_list tbody.date_input_box tr.child.child_2 td, .dateRow_list tbody.date_input_box tr.child.child_3 td, .dateRow_list tbody.date_input_box tr.child.child_4 td, .dateRow_list tbody.date_input_box tr.child.child_5 td, .dateRow_list tbody.date_input_box tr.child.child_6 td {
    font-size: 11px;
}
.dateRow_list tbody.date_input_box tr.parent td p:hover {
    color: #00b2a7;
}
.parent td.icon span {
    height: 0;
    top: 0;
}
.parent td.icon span:after {
    top: -5px;
}

td.labelbtn2 {
    cursor: pointer;
}
  .ext_field2 {
    background-color: #fff;
    border-radius: 0.5rem;
    color: #000;
    max-height: 18rem;
    opacity: 0;
    overflow: auto;
    padding: 1rem 0;
    pointer-events: none;
    top: 15rem;
    transition: all .5s ease;
}
.ext_field2.active {
    opacity: 1;
    pointer-events: all;
    top: calc(100% + 0.5rem);
}

td.labelbtn2 span {
    background: #00b2a7;
    height: 12px;
    position: relative;
    width: 2px;
    display: inline-block;
    margin-left: 15px;
    top: 2px;
}
td.labelbtn2 span:after {
    background: #00b2a7;
    content: "";
    height: 2px;
    left: -6px;
    position: absolute;
    top: 5px;
    width: 14px;
}
td.labelbtn2.icon span {
    height: 0;
    top: 0;
}
td.labelbtn2.icon span:after {
    top: -5px;
}
.dateRow_list tbody.date_input_box .parent.Even td {
    background: #f1f1f1;
}
.dateRow_list tbody.date_input_box tr.parent:nth-child(2) td{
  background:#e2e2e2;
}

.date_input_box [data-show='show'] .view-rate {
   display: none;
}
.date_input_box .hide-rate {
   display: none;
}

.date_input_box [data-show='show'] .hide-rate {
   display: block;
}
.dateRow_list tbody.date_input_box tr td .w-full {
    display: flex;
    justify-content: center;
}
.dateRow_list tbody.date_input_box tr td .w-full p {
    margin-bottom: 0;
    font-weight: 500;
    font-size: 10px;
    /* text-transform: uppercase; */
    color: #837777;
}
</style>
