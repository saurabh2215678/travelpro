<?php
$routeName = CustomHelper::getAdminRouteName();
$price_category_elements = $package->packagePriceCategory->priceCategoryElements??[];
$tripTimeArr = config('custom.tripTimeArr');
$module = "packages";
if($package->is_activity == 1){
    $module = "activity";
}
$online_booking = CustomHelper::isOnlineBooking(); ?>
<div class="centersec">
@if($online_booking)
 <div class="table-responsive" id="booking_mode_form" style="display: none;margin-bottom: 10px;">
     <div class="full">
         <div class="form-group{{ $errors->has('booking_mode') ? ' has-error' : '' }} col-md-6">
            <label class="control-label ">Booking Mode:</label>
            <input type="radio" name="booking_mode" value="0"
            <?php echo (old('booking_mode', $booking_mode) == '0')?'checked':''; ?> /> Automatic booking mode
            &nbsp;&nbsp;
            <input type="radio" name="booking_mode" value="1"
            <?php echo (old('booking_mode', $booking_mode) == '1')?'checked':''; ?> /> Booking needs approval
            @include('snippets.errors_first', ['param' => 'booking_mode'])
        </div>
    </div>
    <div class="col-md-6">
        <button type="submit" class="btn btn-success" id="SaveBooking" onclick="save_booking_mode_data()">Save</button>
        <?php //<a href="{{route($routeName.'.packages.packageprice',$package_id)}}" class="btn_admin2">Cancel</a> ?>
        <a href="<?php echo url($routeName.'/'.$module.'/'.$package_id).'/packageprice' ?>" class="btn_admin2" title="Cancel">Cancel</a>
    </div>
</div>
@endif

@include('snippets.errors')
@include('snippets.flash')

<div class="ajax_msg"></div>
@if($online_booking)
<div class="row">
<div class="col-md-12">
<table class="table table-striped table-bordered table-hover booking_mode_data">
    <tr>
     <td><strong> Selected booking mode</strong></td>
     <td>@if(isset($booking_mode)) @if($booking_mode == 1) Booking needs approval @elseif($booking_mode == 0) Automatic booking mode @endif @endif</td>
     <td>&nbsp <button class="btn btn_admin2 edit_form">Edit</button></td>
 </tr>
</table>
</div>
</div>
@endif

<?php if(!empty($records) && count($records) > 0){?>
    <div class="table-responsive col-md-12">           
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th>Sort</th>
                <th>Price Title</th>
                <th>Itinerary : Accommondation</th>
                <?php foreach($price_category_elements as $pce){ ?>
                    <th><?php echo $pce->input_label; ?></th>
                <?php } ?>
                <th>Status</th>
                <th>Default</th>
                <?php if($package->is_activity == 1){ ?>
                    <th class="text-center">Time Slot</th>
                <?php } ?>
                <th>Action</th>
            </tr>
            <tbody class="row_position">
                <?php $i=0; foreach($records as $row){ $i++;
                    $package_accommodation_data = $row->PackageAccommodation ?? '';
                    ?>
                    <tr id="{{$row->id}}">
                        <td><span class="rows handle"><span style="cursor:pointer"><i class="fa fa-arrows-v" style="font-size:21px;color: #00b2a7;"></span></i> </span></td>
                        <td><?php echo $row->title; ?> <?php if($row->is_default == '1'){ ?><cite>(Default)</cite><?php } ?> </td>
                        <td>
                            <?php $package_accommodation_data = $row->PackageAccommodation ?? '';
                            foreach($package_accommodation_data as $package_accommodation){ 
                                $itenary_data = $package_accommodation->itenary_data ? json_decode($package_accommodation->itenary_data ) : [];
                                $accommodation_data = $package_accommodation->accommodation_data ? json_decode($package_accommodation->accommodation_data ) : [];
                                $itenary_name = [];
                                foreach ($itenary_data as $key => $itenary_id) {
                                    $itenary_data = CustomHelper::getitenarydata($itenary_id);
                                    if($itenary_data && !empty($itenary_data->day_title)){
                                     $itenary_name[] = $itenary_data->day_title;
                                 }
                             }
                             echo '<b>';
                             echo implode(', ', $itenary_name);
                             echo '</b>: ';
                             $accommodation_name = [];
                             foreach ($accommodation_data as $key => $accommodation) {
                                $accommodation = CustomHelper::getAccommodationdata($accommodation);
                                $accommodation_name[] = $accommodation->name ??'';
                            }
                            echo implode(', ', $accommodation_name);
                            echo '<br>';
                        }
                        ?> 
                    </td>
                    <?php
                    if(!empty($price_category_elements)){
                        foreach($price_category_elements as $pce){ ?>
                            <td>
                                <?php $category_choices_record = json_decode($row->category_choices_record);
                                if(!empty($category_choices_record)){
                                    foreach($category_choices_record as $pck_key => $pcd_val){ ?>
                                        <?php if('pce'.$pce->id == $pck_key){ ?>
                                            <?php foreach($pcd_val as $pv){ echo $pv; echo ', ';} ?>
                                        <?php  } } } ?>
                                    </td>
                                <?php } } ?>
                                <td>
                                    <?php if($row->status == '1'){ echo '<font style="color:green">Active</font>'; }else{ echo '<font style="color:red">Inactive</font>'; } ?>
                                </td>

                                <td>
                                    <input type="checkbox" name="is_default" class="checkInput_{{ $row->id }}" onclick="isDefault({{ $row->id }})"
                                    <?php $row->is_default == 1 ? print 'checked value="0"' : print 'value="1"'; ?>>
                                </td>
                                <?php if($package->is_activity == 1){ ?>
                                    <td class="text-right">
                                     <a href="javascript:void(0);" data-src="{{ url($routeName.'/packages/'.$package->id.'/packageprice_slot/'.$row->id) }}" title="Time-Slot" data-fancybox data-type="ajax"><i class="fa fa-pencil"></i> Time-Slot 
                                        <?php 
                                        $package_slots = CustomHelper::getPackageSlots($package->id,$row->id);

                                        if($package_slots && $package_slots->count() > 0 ){
                                            foreach ($package_slots as $key => $package_slot_row) {

                                                $time_slot = $tripTimeArr[$package_slot_row->start_time] ?? '' ;
                                                echo "<br>".$time_slot;
                                            } } ?>

                                        </a> 
                                    </td>
                                <?php } ?>
                                <td>
                                    @if(CustomHelper::checkPermission('packages','edit'))
                                    <a href="javascript:void(0)" class="edit" data-id="{{$row->id}}"><i class="fa fa-pencil-square-o"></i>Edit</a>&nbsp;
                                    @endif
                                    @if(CustomHelper::checkPermission('packages','delete'))
                                    | <a href="javascript:void(0)" class="delete_data" data-id="{{$row->id}}"><i class="fa fa-trash-o"></i>Delete</a>
                                    @endif
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php }else{ ?>
            <div class="listboxhotal">
                <div class="alert alert-warning">
                    There are no Price Info at the present.
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

    $('.edit_form').click(function(){
        $('#booking_mode_form').show();
        $('.booking_mode_data').hide();
    });
    function save_booking_mode_data(){
        var booking_mode = $("input[name = 'booking_mode']:checked").val();
        var package_id = '{{$package_id}}';
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ route('admin.'.$module.'.save_booking_mode') }}",
            type: "POST",
            data: { booking_mode:booking_mode, package_id:package_id },
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            async: false,
            beforeSend:function(){
                $(".ajax_msg").hide();
                $("#SaveBooking").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
                $("#SaveBooking").attr("disabled", true);
            },
            success: function(resp){
                if(resp.success){
                    //$(".ajax_msg").html(resp.msg);
                    $(".ajax_msg").show();
                    setTimeout( function() {
                        $(".ajax_msg").hide();
                        window.location.reload();
                    }, 1000 );
                }
            }
        });
    }
</script>   