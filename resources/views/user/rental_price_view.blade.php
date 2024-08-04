 <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover text-xs cab-pricelight-box">
                  
                  <?php
                  $discount_module_to_group = $discount_category_data->DiscountModuleToGroup??[];
                  if( (!empty($discount_module_to_group) && count($discount_module_to_group) > 0) || $discount_category_id == '-1') { ?>
                    <tr>
                      <th>Bike</th>
                      <th>Retail Price</th>
                      <th>Discount Price</th>
                      <th>Agent Price</th>
                      <?php $search_price = $packageDetails->search_price?? '' ;
                     $discount_amount = 0;
                      ?>
                    </tr>                

                    <?php
                      $price = CustomHelper::getBikeprice($city_id,$bike_id);
                      ?>
                      <tr>
                        <td><?php $bike_row = CustomHelper::getBikeData($bike_id); ?>
                        {{$bike_row->name ?? ''}}</td>
                        <td>{{$price}}</td>
                        <?php 
                          $price_discount = 0;
                          $agent_price = $price;
                          if($price && $discount_category_id != 0){
                            $price_discount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$price,$userDetails->group_id,$module_record_id);
                          }
                           if($price > $price_discount){
                              $agent_price = $price - $price_discount;
                             }
                          ?>
                          <td>{{CustomHelper::getPrice($price_discount)}}</td>
                          <td>{{CustomHelper::getPrice($agent_price)}} </td>
                      </tr>
                    <?php //} ?>
                <?php }else if(empty($discount_category_id)) { ?>
                  <tr>
                    <td align="center"><div class="alert alert-warning"><strong> Agent discount not applicable.</strong></div></td>
                  </tr>
                  <?php } else { ?>
                  <tr>
                    <td align="center"><div class="alert alert-warning"><strong> The selected discount category does not contain discount data. Go to "Discount for Agent Groups" and add applicable discount.</strong></div></td>
                  </tr>
                  <?php } ?>
                </table>
            </div> 