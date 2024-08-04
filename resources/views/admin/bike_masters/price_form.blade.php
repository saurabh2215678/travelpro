{{ csrf_field() }}
<div class="col-md-12">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="ajax_msg"></div>
  <div class="dateRow_list d-flex">
    <?php $bike_data = $bike_city_data->bike ?? [];
    if($bike_data){ ?>
    <table class="table table-borderless" >
      <tr>
      <th>Bike Name</th>
      <th>Per Day Price</th>
      </tr>
      <tbody class="date_input_box" >
        <?php 
          $bike_arr = json_decode($bike_data);
          foreach ($bike_arr as $key => $bike) { 
          $added_price = '';
            foreach($BikecityPrice as $k => $BikecityPriceVal){
              if($BikecityPriceVal->bike_id == $bike){
                $added_price = $BikecityPriceVal->price??0;
              }
            }
        ?>
          <tr>
            <td style="width: 150px;vertical-align: middle;">
              {{$bike_city_data->bikeName($bike)??''}}</td>
              <td style="width: 100px;" class="date">
                <input type="text" name="price[{{$bike}}]" value="{{$added_price}}" class="form-control bike_price" id="" placeholder="Price" style="width: 200px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
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