{{ csrf_field() }}
<div class="col-md-12">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="ajax_msg"></div>
  <div class="dateRow_list d-flex">
    <?php $cabsCityPrices = $cityData->cabsCityPrices??[];
    if($cabsCityPrices){ ?>
    <table class="table table-borderless" >
      <tr>
      <th>Cab Name</th>
      <th>Per K.M. Price</th>
      </tr>
      <tbody class="date_input_box" >
        <?php 
          foreach ($cabsCityPrices as $key => $cabsCityPrice) {
            $cab_id = $cabsCityPrice->cab_id;
          $added_price = '';
            foreach($cabsPrices as $k => $cabsPrice){
              if($cabsPrice->city_id == $cabsCityPrice->city_id){
                $added_price = $cabsCityPrice->price??0;
              }
            }
        ?>
          <tr>
            <td style="width: 150px;vertical-align: middle;">
              {{$cabsCityPrice->cabData->name??''}}</td>
              <td style="width: 100px;" class="date">
                <input type="text" name="price[{{$cab_id}}]" value="{{$added_price}}" class="form-control cab_price" id="" placeholder="Price" style="width: 200px;margin: 0 auto;" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')">
              </td>
          </tr>
        <?php } ?>   
      </tbody>
    </table>
    <?php } ?>   
  </div>
  <div class="btn pull-right">
    <input type="hidden" name="city_id" value="{{$city_id}}" />
    <input type="hidden" id="has_unsaved" value="0">
    <button type="submit" class="btn btn-primary btnSubmit" disabled value="submit"> Save</button>
  </div>
</div>