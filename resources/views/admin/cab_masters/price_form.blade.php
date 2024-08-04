<style>
    .right-bar.d-flex {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-bottom: 15px;
}
.bulk-btn button {
    padding: 7px 14px;
}
.bulk-btn span {
    padding: 10px;
    border: 1px solid #ddd;
    font-size: 20px;
    color: #5e5e5e;
    border-radius: 5px;
    cursor: pointer;
}
.dateRow_list tbody tr td, .dateRow_list tbody tr th {
    padding:5px;
    border-left: 1px solid #ddd !important;
    border: 0;
    text-align: center;
    background: #efefef;
    text-transform: uppercase;
}
.dateRow_list tbody tr td.active, .dateRow_list tbody tr th.active {
    background: #009688;
    color: #fff;
    text-align:center;
}
.dateRow_list tbody tr td.months {
    text-transform: uppercase;
}
.dateRow_list tbody.date_input_box tr td {
    background: #fff;
    border: 0 !important;
}
.dateRow_list tbody.date_input_box tr td input {
    text-align: center;
    color: green;
}
.dateRow_list tbody.date_input_box tr td input.sold {
    color: #ef5f57;
}
.dateRow_list tbody.date_input_box tr td input::placeholder {
    text-align: center;
}
tbody.date_input_box {
    border: 0 !important;
}
.dateRow_list tbody.date_input_box label.label-text {
    font-size: 11px;
    color: #838383;
    font-weight: 300;
    text-transform: none;
}


/* modal details */
.modal.modal-wide .modal-dialog {
  width: 50%;
}
.modal-wide .modal-body {
  overflow-y: auto;
}
.dateselect {
    background: #eef1ff;
    padding: 15px;
    margin-bottom: 10px;
}
.dateselect input {
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
}
.dateselect span.btn_date {
    position: relative;
}
.dateselect i {
    position: absolute;
    right: 6px;
    top: 0px;
    font-size: 18px;
    color: #979797;
}
<?php 
$cab_route_types = config('custom.cab_route_types');
?>

</style>
<section>
   <div class="col-md-12">
      <div class="col-md-6">
      </div>
      <div class="col-md-6">
         <div class="right-bar d-flex">
            <div class="btn">
               <!-- <a data-toggle="modal" href="#bulkModal" class="btn_admin2">Bulk Update</a> -->
            </div>
                <!-- Modal -->
                <div id="bulkModal" class="modal modal-wide fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Bulk Update</h4>
                        </div>
                        <div class="modal-body">
                        
                        <div class="dateRow_list">
                        <div class="dateselect"><span class="btn_date"><i class="fa fa-calendar"></i><input type="text" name="bulkdaterange" value="" /></span>
                        </div>
                          <table class="table table-borderless">
                           <tbody class="date_input_box">
                                <tr>
                                    <td style="width: 150px;vertical-align: middle; text-align: left;">Luxury</td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">1 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">0 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control sold" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">3 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">4 Sold</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;vertical-align: middle; text-align: left;">Standard</td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control sold" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">1 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">0 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">3 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date sold">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">2 Sold</label>
                                    </td>
                                    <td style="width: 100px;" class="date">
                                    <input type="text" class="form-control" id="" placeholder="2" style="width: 80px;margin: 0 auto;">
                                    <label for=" " class="label-text">4 Sold</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                    </div>
</div>
           <!--  <div class="bulk-btn">
               <span class="btn_left"><i class="fa fa-chevron-left"></i></span>
               <span class="btn_date daterange"><i class="fa fa-calendar"></i>
            </span>
               <span class="btn_right"><i class="fa fa-chevron-right"></i></span>
            </div> -->
         </div>
      </div>
   </div>
   <div class="dateRow">
      <div class="col-md-12">
         <div class="dateRow_list d-flex">
          <table class="table table-borderless">
            <tbody>
                <tr>
                    <th></th>
                    <?php
                    $cab_route_group_id = 1;
                    $car_data = $group_data->cab_data??'';
                    $car_arr = json_decode($car_data);
                    foreach ($car_arr as $key => $car) {
                    ?>
                    <th class="active week" colspan="2">{{$car->name}}</th>
                    <?php } ?>
                </tr>              
            </tbody>
            <tbody class="date_input_box">
                <tr>
                    <td></td>
                    <?php foreach($car_arr as $car){ ?>
                    <td style="text-align: right;font-size: 9px;font-style: italic;">(Oneway)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td style="text-align: left;font-size: 9px;font-style: italic;">&nbsp;&nbsp;&nbsp;&nbsp;(RoundTrip)</td>
                   <?php } ?>
                </tr>
             <?php   
             foreach ($cab_routes as $key => $cab_route){
                // pr($cab_route->toArray());
                $cab_route_id= $cab_route->id ?? '';
                $route_type = $cab_route->route_type ?? '';
                ?>
                <tr>
                    <td style="width: 150px;vertical-align: middle;text-align: left;">{{$cab_route->name ?? ''}} ({{$cab_route_types[$route_type]}})</td>
                    <?php foreach($car_arr as $car){
                    $car_id = $car->id ;
                    $key_id = $cab_route_id.'_'.$car_id ;
                    $price = '';
                    $round_trip_price = '';
                    foreach ($CabRoutePrices as $key => $CabRoutePrice) {
                        if($CabRoutePrice->cab_route_id == $cab_route_id && $CabRoutePrice->cab_id == $car_id) {
                            $val = $CabRoutePrice->price??'';
                            if(!empty($val) && $val > 0) {
                                $price = $val;
                            }
                            $val2 = $CabRoutePrice->round_trip_price??'';
                            if(!empty($val2) && $val2 > 0) {
                                $round_trip_price = $val2;
                            }
                            break;
                        }
                    }
                    ?>
                    <td style="width: 100px;" class="date">
                        <input type="text" class="form-control cab_price"  name="inventory[{{$cab_route_id}}][{{$car_id}}]" id="" placeholder="Price" value="{{$price}}" style="width: 80px;margin: 0 auto;float: right;">
                    </td>
                    <td style="width: 100px;" class="date">
                        <?php if($route_type == '0'){?>
                        <input type="text" class="form-control cab_price"  name="round_trip_price[{{$cab_route_id}}][{{$car_id}}]" id="" placeholder="Price" value="{{$round_trip_price}}" style="width: 80px;margin: 0 auto;float: left;">
                        <?php } ?>
                    </td>
                   <?php } ?> 
               </tr>
            <?php } ?> 
            </tbody>
            </table>
         </div>
         <div class="btn pull-right">
               <button type="button" class="btn btn-primary save_submit" disabled value="submit"> Save</button>
         </div>
   </div>
</section>










    
    

