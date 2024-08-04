 <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover text-xs cab-pricelight-box">
                  
                  <?php
                  foreach ($group_datas as $key => $group_data) {
                  $discount_module_to_group = $discount_category_data->DiscountModuleToGroup??[];
                  if( (!empty($discount_module_to_group) && count($discount_module_to_group) > 0) || $discount_category_id == '-1'){?>
                    <tr>
                        <th>Cab ({{$group_data->name ?? ''}})</th>
                        <th>Retail Price<br><span class="text-teal-500">(Oneway) - (Roundtrip)</span></th>
                      <?php $search_price = $packageDetails->search_price?? '' ;
                     $discount_amount = 0;
                      ?>
                      <th>Discount Price <br><span class="text-teal-500">(Oneway) - (Roundtrip)</span></th>
                        <th>Agent Price <br><span class="text-teal-500">(Oneway) - (Roundtrip)</span></th>
                    </tr>                

                  <?php 
                   $car_data = $group_data->cab_data??'';
                    $car_arr = json_decode($car_data);
                    if(!empty($car_arr) && is_array($car_arr)){
                    foreach ($car_arr as $key => $car) {
                        $car_id = $car->id ;
                  ?>
                  <tr>
                      <?php 

                      $price = 0;
                      $round_trip_price = 0;
                      $round_trip_price_discount = 0;
                      $round_trip_price_discount = 0;
                      $price_discount = 0;
                      foreach ($CabRoutePrices as $key => $CabRoutePrice) {
                        if($CabRoutePrice->cab_route_id == $cab_route_id && $CabRoutePrice->cab_id == $car_id && $CabRoutePrice->cab_route_group_id == $group_data->id) {
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
                      <td>{{$car->name}}</td>
                      <td>
                      {{CustomHelper::getPrice($price)}} - {{CustomHelper::getPrice($round_trip_price)}}</td>

                      <?php 
                                                
                         if($price && $discount_category_id != 0){
                          $price_discount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$price,$userDetails->group_id,$module_record_id);
                         }
                          $price_discount_amount = $price - $price_discount;
                          if($round_trip_price  && $discount_category_id != 0){
                              $round_trip_price_discount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$round_trip_price,$userDetails->group_id,$module_record_id);
                          }
                          $round_trip_price_discount_amount = $round_trip_price - $round_trip_price_discount;

                           ?>
                           <td>
                           ({{CustomHelper::getPrice($price_discount)}}) - ({{CustomHelper::getPrice($round_trip_price_discount)}})<br>
                       </td>
                           <td>
                           {{CustomHelper::getPrice($price_discount_amount)}} - {{CustomHelper::getPrice($round_trip_price_discount_amount)}}<br> </td>

                        
                  </tr>
                <?php } } ?>
                <?php }else if(empty($discount_category_id)) { ?>
                  <tr>
                    <td align="center"><div class="alert alert-warning"><strong> Agent discount not applicable.</strong></div></td>
                  </tr>
                  <?php } else { ?>
                  <tr>
                    <td align="center"><div class="alert alert-warning"><strong> The selected discount category does not contain discount data. Go to "Discount for Agent Groups" and add applicable discount.</strong></div></td>
                  </tr>
                  <?php } ?>
                  <?php } ?>
                </table>
            </div> 