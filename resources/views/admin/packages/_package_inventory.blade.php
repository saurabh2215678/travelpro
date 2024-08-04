<style>
    tbody.date_input_box tr:nth-child(even) td {
    background: #efefef;
}
</style>
{{ csrf_field() }}

<?php 
$tripTimeArr = config('custom.tripTimeArr');
?>
<div class="col-md-12">
    @include('snippets.errors')
    @include('snippets.flash')
    <div class="ajax_msg"></div>

    <div class="dateRow_list d-flex">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th></th>
                    <?php
                    foreach($period as $dt) {
                        $date = $dt->format("d-m-Y");
                        $trip_date = $dt->format("Y-m-d");
                        $date_id =  $dt->format("dmY");
                        ?>
                        <th class="active week">{{CustomHelper::DateFormat($trip_date,'D','Y-m-d')}}</th>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="width: 150px;"><i class="fa fa-3x fa-calendar" style="font-size: 25px;"></i></td>
                    <?php
                    foreach($period as $dt) {
                        $date = $dt->format("d-m-Y");
                        $trip_date = $dt->format("Y-m-d");
                        $date_id =  $dt->format("dmY");
                        ?>
                        <td class="active date">{{CustomHelper::DateFormat($trip_date,'d','Y-m-d')}}</td>
                    <?php } ?>
                </tr>
                <tr>
                    <td></td>
                    <?php
                    foreach($period as $dt) {
                        $date = $dt->format("d-m-Y");
                        $trip_date = $dt->format("Y-m-d");
                        $date_id =  $dt->format("dmY");
                        ?>
                        <td class="active months">{{CustomHelper::DateFormat($trip_date,'M','Y-m-d')}}</td>
                    <?php } ?>
                </tr>
                <?php /*
                <tr>
                    <th></th>
                    <?php
                    foreach($period as $dt) {
                        $date = $dt->format("d-m-Y");
                        $last_date = $dt->format("Y-m-d");
                        $date_id =  $dt->format("dmY");
                        ?>
                        <th class="active week">{{$date}}</th>
                    <?php } ?>
                </tr>
                */ ?>
            </tbody>
            <tbody class="date_input_box">
                <?php 
                $curr_date =strtotime(date('Y-m-d'));
                $before_date = date('Y-m-d',strtotime('-15 days',$curr_date));

                            
                foreach($packagePrices as $packagePrice){
                    $price_id = $packagePrice->id;
                    ?>
                    <tr>
                        <td style="width: 150px;vertical-align: top;">{{$packagePrice->title}}</td>
                        <?php
                        foreach($period as $dt) {
                            $date = $dt->format("d-m-Y");
                            $last_date = $dt->format("Y-m-d");
                            $date_id =  $dt->format("dmY");

                            $inventory = '';
                            $booked = '';
                            foreach($pacakge_inventory as $pi) {
                                if($pi->price_id == $price_id && $pi->trip_date == $last_date) {
                                    $val = $pi->inventory??'';
                                    $booked = $pi->booked??'';
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

                                    <?php
                                    $package_slots = CustomHelper::getPackageSlots($package_id,$price_id);

                                     if($package_slots && $package_slots->count() > 0 ){
                                        foreach ($package_slots as $key => $package_slot_row) {
                                            if($package_slot_row->price_id == $price_id ){
                                               
                                               $inventory = '';
                                               $booked = '';
                                               foreach($pacakge_inventory as $pi) {
                                                if($pi->price_id == $price_id && $pi->trip_date == $last_date && $pi->slot_id == $package_slot_row->id) {
                                                    $val = $pi->inventory??'';
                                                    $booked = $pi->booked??'';
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
                                    <label for="" class="label-text"><?php $time_slot = $tripTimeArr[$package_slot_row->start_time] ?? '' ;
                                                echo "<br>".$time_slot; ?></label>
                                      <input type="text" class="form-control pp_inventory" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="inventory[{{$date_id}}][{{$packagePrice->id}}][{{$package_slot_row->id}}]" value="{{$inventory}}" {{$disabled}}/>
                                      <?php if($booked > 0){?>
                                        <label for=" " class="label-text">Booked: {{$booked}} </label><br>
                                    <?php }else{ ?>
                                         <label for=" " class="label-text"></label><br>
                                   <?php } ?>
                             <?php }
                               }
                            }else{
                                ?>
                                <input type="text" class="form-control pp_inventory" placeholder="" style="width: 80px;margin: 0 auto;" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" name="inventory[{{$date_id}}][{{$packagePrice->id}}][0]" value="{{$inventory}}" {{$disabled}}/>
                               <?php if($booked > 0){?>
                                <label for=" " class="label-text">Booked: {{$booked}} </label>
                                <?php } ?>
                            <?php } ?>

                            </td>
                        <?php } ?>
                    </tr>
                    
                <?php } ?>
                <hr>
            </tbody>
        </table>
    </div>
    <div class="btn pull-right">
        <input type="hidden" name="package_id" value="{{$package_id}}" />
        <input type="hidden" id="has_unsaved" value="0">
        <button type="submit" class="btn_admin2 btnSubmit" disabled>Submit</button>
    </div>
</div>
