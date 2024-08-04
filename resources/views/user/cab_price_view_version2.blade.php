<div class="table-responsive">
  <table class="table table-striped table-bordered table-hover text-xs cab-pricelight-box">
    <?php
    $discount_module_to_group = $discount_category_data->DiscountModuleToGroup??[];
    if( (!empty($discount_module_to_group) && count($discount_module_to_group) > 0) || $discount_category_id == '-1'){
      ?>
      <tr>
        <th>Cab ({{$sightseeingData->name ?? ''}})</th>
        <th>Retail Price</th>
        <th>Discount Price</th>
        <th>Agent Price</th>
      </tr>
      <?php
      foreach ($car_arr as $key => $cab) {
        $price = $cab['fareDetails']['price_per_km']??0;
        $price_discount = 0;
        if($price && $discount_category_id != 0){
          $price_discount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$price,$userDetails->group_id,$module_record_id);
        }
        $price_discount_amount = $price - $price_discount;
        ?>
        <tr>
          <td>{{$cab['name']??''}}</td>
          <td>{{CustomHelper::getPrice($price)}}</td>
          <td>{{CustomHelper::getPrice($price_discount)}}</td>
          <td>{{CustomHelper::getPrice($price_discount_amount)}}</td>
        </tr>
      <?php } ?>
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