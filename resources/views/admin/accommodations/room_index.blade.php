@component('admin.layouts.main')

@slot('title')
    Admin - Accommodation Room(Accommodation) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$active_menu = "accommodations".$accommodation_id.'/accommodation-room';
$storage = Storage::disk('public');
$path = 'accommodation_rooms/';
?>
@if(!empty($accommodation_id))
    @include('admin.includes.accommodationoptionmenu')
@endif
    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $heading }}</h2>
    </div>
    <div class="add_button"> 
        @if(CustomHelper::checkPermission('accommodations','add'))
    <a href="{{ route('admin.accommodations.room_add', $accommodation_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Room</a>
    @endif
    </div>
    </div>

    <div class="centersec">
        <div class="bgcolor">
            <?php if(!($accommodation_rooms->isEmpty())){ ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th>Sort</th>
                            <th width="10%" class="text-center">Room Type Name</th>
                            <th width="10%" class="text-center">Image</th>
                            <th width="10%" class="text-center">Base Occupancy</th>
                            <!-- <th width="10%" class="text-center">Base price</th>
                            <th width="10%" class="text-center">Publish Rate</th> -->
                            <th width="10%" class="text-center">Rate Plan</th>
                            <th width="10%" class="text-center">Rate Plan Price</th>
                            <!-- <th width="10%" class="text-center">Sort Order</th>-->
                            <th width="10%" class="text-center">Status</th>
                            <th width="10%" class="text-center">Default</th>
                            <th width="10%" class="text-center">Date Created</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                         <tbody class="row_position">

                        <?php
                        $i=0;
                        foreach($accommodation_rooms as $room) {
                        $i++;
                            $planData = $room->planData->pluck('plan_name')->toArray()??[];
                            $plans = '';
                            if(!empty($planData)) {
                                $plans = '<span>'.implode('</span><span>', $planData).'</span>';
                            }
                            $roomImages = $room->accommodationRoomImages??[];
                        ?>
                            <tr id="{{$room->id}}">
                                <!-- <td>{{ $room->roomAccommodation->name }}</td> -->
                                <td style="width:2%;"><span class="rows handle"><span style="cursor:pointer"><i class="fa fa-arrows-v" style="font-size:21px;color: #00b2a7;"></span></i> </span></td>
                                <td>{{ $room->room_type_name ? $room->room_type_name:'' }}</td>
                                <td>
                                    <?php if(!empty($roomImages) && count($roomImages) > 0){ ?>
                                        <a href="{{ route($routeName.'.images.upload',['tid'=>$accommodation_id, 'table_id'=>$room->id,'module'=>'accommodation_rooms', 'category'=>'gallery', 'back_url'=>$BackUrl]) }}">
                                        <img src="{{ url('storage/'.$path.'thumb/'.$roomImages[0]->name) }}" style="width: 100px;">
                                        </a>
                                    <?php } ?>
                                    <a class="rooms_img" href="{{ route($routeName.'.images.upload',['tid'=>$accommodation_id, 'table_id'=>$room->id,'module'=>'accommodation_rooms', 'category'=>'gallery', 'back_url'=>$BackUrl]) }}" target="_blank"><i class="fas fa-image"  title="Add Images"> ({{count($roomImages)??0}})</i> </a>
                                </td>
                                <td align="center">{{ $room->base_occupancy??''}}</td>
                                <!-- <td>{{ $room->base_price ??''}}</td>
                                <td>{{ $room->publish_price ??''}}</td> -->
                                <td>
                                    <a href="{{ url($routeName.'/accommodations/'.$room->roomAccommodation->id.'/plan/'.$room->id) }}" class="room_plan_fancy">{!!$plans!!} <i class="fa fa-pencil"></i></a>

                                    <!-- <a href="javascript:void(0);" data-src="{{ url($routeName.'/accommodations/'.$room->roomAccommodation->id.'/plan/'.$room->id) }}" title="Time-Slot" data-fancybox data-type="iframe"><i class="fa fa-pencil"></i> Time-Slot</a>  -->
                             </td>
                             <td>
                                @if($plans)
                                <a href="{{ url($routeName.'/accommodations/'.$room->roomAccommodation->id.'/rates/'.$room->id) }}" class="room_plan_fancy">Update <i class="fa fa-pencil"></i></a>
                                @endif

                                    <!-- <a href="javascript:void(0);" data-src="{{ url($routeName.'/accommodations/'.$room->roomAccommodation->id.'/plan/'.$room->id) }}" title="Time-Slot" data-fancybox data-type="iframe"><i class="fa fa-pencil"></i> Time-Slot</a>  -->
                             </td>
                             <?php /*<td>{{ isset($room->sort_order) ? $room->sort_order:0}}</td> */ ?>
 
                                <td align="center"><?php if($room->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                                <?php } ?>
                                </td>

                                <td align="center"><?php  if($room->is_default == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   } ?> 
                                </td>
                                
                                <td align="center">{{ CustomHelper::DateFormat($room->created_at, 'd/m/Y') }}</td>

                                <td>

                                    <a href="{{route($routeName.'.accommodations.room_view',[$room['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                                    
                                    @if(CustomHelper::checkPermission('accommodations','edit'))
                                    <a href="{{ route($routeName.'.accommodations.room_edit', ['id'=>$accommodation_id,'room_id'=>$room->id, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                    @endif
                                    @if(CustomHelper::checkPermission('accommodations','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>

                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.accommodations.room_delete', ['id'=>$accommodation_id,'room_id'=>$room->id, 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                    </form>
                                    @endif
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                </div>
                {{ $accommodation_rooms->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no Accommodation Room Block(Accommodation) at the present.</div>
        <?php } ?>
        </div>

    </div>
    </div>
    </div>

    @slot('bottomBlock')
<style>
    body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-stage .fancybox-content iframe {
    padding: 15px;
}
body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-slide--iframe .fancybox-content {
    height: 70% !important;
    width: 100rem !important;
}
.fancybox-active .fancybox-container.fancybox-is-open button.fancybox-button {
    background: #009688;
    top: 0px;
    right: 0;
}
body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-inner .fancybox-toolbar {
    right: 34rem;
    top: 4rem;
}
.fancybox-slide--iframe .fancybox-content {
	width  : 800px;
	height : 600px;
	max-width  : 80%;
	max-height : 80%;
	margin: 0;
}
</style>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $(document).on("click", ".sbmtDelForm", function(e){
            e.preventDefault();

            $(this).siblings("form.delForm").submit();
        });

/*$('.edit_form').click(function(){
    $('#booking_mode_form').show();
    $('.booking_mode_data').hide();
});*/


/*function save_booking_mode(){
    var booking_mode = $("input[name = 'booking_mode']:checked").val();
    var accommodation_id = '{{$accommodation_id}}';
    var _token = '{{ csrf_token() }}';
        $.ajax({
        url: "{{ route('admin.accommodations.save_booking_mode') }}",
        type: "POST",
        data: { booking_mode:booking_mode, accommodation_id:accommodation_id },
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        async: false,
        beforeSend:function(){
            $(".ajax_success_msg").hide();
            $("#SaveBooking").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
            $("#SaveBooking").attr("disabled", true);
        },
        success: function(resp){
            if(resp.success){
                $(".ajax_success_msg").html('Booking mode saved');
                $(".ajax_success_msg").show();
                setTimeout( function() {
                        $(".ajax_success_msg").hide();
                        window.location.reload();
                }, 700 );
            }
        }
    });

}*/


// Media Library Box
$(document).ready(function() {
    $('.room_plan_fancy').fancybox({
        'type': "iframe",
        'width':'500',
        'toolbar'  : false,
        'smallBtn' : true,
        'autosize':false,
        
        beforeClose: function(){

        }
    });
});

$(document).on('mouseover','.row_position',function(e){
    e.preventDefault();
    $( ".row_position" ).sortable({  
        delay: 150,
        handle: ".handle",
        stop: function(event, ui) {
                var selectedData = new Array();  
                $('.row_position>tr').each(function() {  
                    selectedData.push($(this).attr("id"));  
                });  
                updateRoom(selectedData);  
            }  
    });
  });

  function updateRoom(data) {  
      var _token = '{{ csrf_token() }}';
      var accommodationId = '{{$accommodation_id}}';
      $.ajax({  
          url : "{{ route('admin.accommodations.update_hotel_order') }}",
          type : 'POST',  
          data:{data:data,accommodation_id:accommodationId},
          dataType:"JSON",
          headers:{'X-CSRF-TOKEN': _token},
          cache: false,
          beforeSend:function(){
              $(".ajax_msg").html("");
              $(".ajax_msg").show();
          },
          success: function(response) {
              $(".ajax_msg").html(response.message).hide();
              $(".ajax_msg").html(response.message).fadeIn();
              setTimeout(function() { $(".ajax_msg").fadeOut(); }, 3000);
          } 
      })  
    }
</script>
@endslot
@endcomponent