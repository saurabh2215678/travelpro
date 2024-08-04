{{ csrf_field() }}
<div class="col-md-12">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="ajax_msg"></div>
  <div class="dateRow_list d-flex">
    <?php $bike_data = $bike_city_data->bike ?? [];
    if($bike_data){ ?>
    <table class="table table-borderless" >
      <tbody>
        <tr>
          <th></th>
          <?php foreach($period as $dt){
            $date =  $dt->format("d-m-Y");
            $last_date =  $dt->format("Y-m-d");
            $date_id =  $dt->format("dmY"); 
          ?>
            <th class="active week">{{$date}}</th>
          <?php } ?>
        </tr>
      </tbody>
      <tbody class="date_input_box" >
        <?php 
          $curr_date =strtotime(date('Y-m-d'));
          $before_date = date('Y-m-d',strtotime('-15 days',$curr_date));
          $bike_arr = json_decode($bike_data);
          foreach ($bike_arr as $key => $bike) { ?>
          <tr>
            <td style="width: 150px;vertical-align: middle;">{{$bike_city_data->bikeName($bike)??''}}</td>
            <?php foreach($period as $dt){
              $date =  $dt->format("Y-m-d");
              $date_id =  $dt->format("Ymd");
              $key_id = $date_id.'_'.$bike;
              $inventory = '';
              $booked = '';
              foreach ($BikeCityInventories as $key => $BikeCityInventory) {
                if($bike == $BikeCityInventory->bike_id && $date == $BikeCityInventory->trip_date ){
                  $val = $BikeCityInventory->inventory??'';
                  $booked = $BikeCityInventory->booked??'';
                  if(!empty($val)) {
                    $inventory = $val;
                  }
                  break;
                }
              }
              $disabled = '';
              if(strtotime($before_date) > strtotime($date) ){
                $disabled = 'disabled';
              }
              ?>
              <td style="width: 100px;" class="date">
                <input type="text" name="inventory[{{$date_id}}][{{$bike}}]" value="{{$inventory}}" class="form-control bike_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" {{$disabled}}>
                <?php if($booked){ ?>
                <label class="label-text"> {{$booked}} Booked</label>
              <?php } ?>
              </td>
            <?php }  ?>   
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