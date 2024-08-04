{{ csrf_field() }}
<div class="col-md-12">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="ajax_msg"></div>
  <div class="dateRow_list d-flex">
    <?php
    $cabsCityPrices = $cityData->cabsCityPrices??[];
    if($cabsCityPrices){ ?>
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
          $curr_date =strtotime(date('Y-m-d'));
          $before_date = date('Y-m-d',strtotime('-15 days',$curr_date));
          // pr($cabsCityPrices->toArray());
          foreach ($cabsCityPrices as $key => $cabsCityPrice) {
          $cab_id = $cabsCityPrice->cab_id;
          ?>
          <tr>
            <td style="width: 150px;vertical-align: middle;">{{$cabsCityPrice->cabData->name ?? ''}}</td>
            <?php foreach($period as $dt){
              $date =  $dt->format("Y-m-d");
              $date_id =  $dt->format("Ymd");
              $key_id = $date_id.'_'.$cabsCityPrice->cab_id;
              $inventory= '';
              $booked= '';
              foreach ($cabsInventories as $key => $cabInventory) {
                if($cab_id == $cabInventory->cab_id && $date == $cabInventory->trip_date ){
                  $val = $cabInventory->inventory??'';
                  $booked = $cabInventory->booked??'';
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
                <input type="text" name="inventory[{{$date_id}}][{{$cab_id}}]" value="{{$inventory}}" class="form-control cab_inventory" id="" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" {{$disabled}}>
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