 <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <?php
                  $discount_amount = 0;
                  $discount_module_to_group = $discount_category_data->DiscountModuleToGroup??[];
                  if( (!empty($discount_module_to_group) && count($discount_module_to_group) > 0) || $discount_category_id == '-1' ){?>
                    <tr>
                        <th>Room Type Name</th>
                        <th>Base Price</th>
                        <th>Discount Price</th>
                        <th>Agent Price</th>
                    </tr>
                    <?php
                    if(!empty($rooms) && count($rooms) > 0) {
                      foreach($rooms as $room) {
                        // $search_price = $room->base_price;
                        $search_price = CustomHelper::getAccommodationRoomPublishPrice($room);
                    ?>
                    <tr>
                        <td>{{$room->room_type_name ?? ''}}</td>
                        <td>{{CustomHelper::getPrice($search_price)}}</td>
                        <?php 
                        
                            $agent_price = $search_price;
                            if($search_price > 0 && $discount_category_id != 0){
                               $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$search_price,$userDetails->group_id,$module_record_id);
                               if($search_price > $discount_amount){
                                 $agent_price = $search_price - $discount_amount;
                             }
                           }
                        ?>
                        <td> {{CustomHelper::getPrice($discount_amount)}}</td>
                        <td>{{CustomHelper::getPrice($agent_price)}} </td>
                   
                    </tr>
                  <?php } }else{ ?>
                    <tr>
                      <td align="center" colspan="4"><div class="alert alert-warning">There are no Accommodation Room Block(Accommodation) at the present.</div></td>
                    </tr>

                  <?php } }else if(empty($discount_category_id)) { ?>
                  <tr>
                    <td align="center" colspan="4"><div class="alert alert-warning"><strong> Agent discount not applicable.</strong></div></td>
                  </tr>
                  <?php } else { ?>
                  <tr>
                    <td align="center" colspan="4"><div class="alert alert-warning"><strong> The selected discount category does not contain discount data. Go to "Discount for Agent Groups" and add applicable discount.</strong></div></td>
                  </tr>
                  <?php } ?>
                </table>
            </div> 