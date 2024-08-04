@component('admin.layouts.main')

@slot('title')
Admin - Accommodation Room View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php

$room_type = (isset($accommodation_room->RoomType))?$accommodation_room->RoomType->title:'';
$accommodation_id = (isset($accommodation_room->accommodation_id))?$accommodation_room->accommodation_id:0;
$room_type_name = (isset($accommodation_room->room_type_name))?$accommodation_room->room_type_name:'';
$total_room = (isset($accommodation_room->total_room))?$accommodation_room->total_room:'';
$brief = (isset($accommodation_room->brief))?$accommodation_room->brief:'';
$description = (isset($accommodation_room->description))?$accommodation_room->description:'';

$room_features = (isset($accommodation_room->room_features))?json_decode($accommodation_room->room_features,true):[];


$single_bed = (isset($accommodation_room->single_bed))?$accommodation_room->single_bed:'';
$double_bed = (isset($accommodation_room->double_bed))?$accommodation_room->double_bed:'';
$base_occupancy = (isset($accommodation_room->base_occupancy))?$accommodation_room->base_occupancy:'';
$base_price = (isset($accommodation_room->base_price))?$accommodation_room->base_price:'';
$publish_price = (isset($accommodation_room->publish_price))?$accommodation_room->publish_price:'';
$extra_adult_bed = (isset($accommodation_room->extra_adult_bed))?$accommodation_room->extra_adult_bed:'';
$price_extra_adult = (isset($accommodation_room->price_extra_adult))?$accommodation_room->price_extra_adult:'';
$extra_child_bed = (isset($accommodation_room->extra_child_bed))?$accommodation_room->extra_child_bed:'';
$price_extra_child_1 = (isset($accommodation_room->price_extra_child_1))?$accommodation_room->price_extra_child_1:'';
$price_extra_child_2 = (isset($accommodation_room->price_extra_child_2))?$accommodation_room->price_extra_child_2:'';
$max_occupancy = (isset($accommodation_room->max_occupancy))?$accommodation_room->max_occupancy:'';
// $no_of_exrta_beds = (isset($accommodation_room->no_of_exrta_beds))?$accommodation_room->no_of_exrta_beds:'';
// $max_adults = (isset($accommodation_room->max_adults))?$accommodation_room->max_adults:'';
// $max_children = (isset($accommodation_room->max_children))?$accommodation_room->max_children:'';
$room_features = (isset($accommodation_room->room_features))?json_decode($accommodation_room->room_features,true):[];
$sort_order = (isset($accommodation_room->sort_order))?$accommodation_room->sort_order:0;
$status = (isset($accommodation_room->status))?$accommodation_room->status:'';

$base_child_no = (isset($accommodation_room->base_child_no))?$accommodation_room->base_child_no:'';
$max_child_no = (isset($accommodation_room->max_child_no))?$accommodation_room->max_child_no:'';

$room_view = (isset($accommodation_room->room_view))?$accommodation_room->room_view:'';
$bed_type = (isset($accommodation_room->bed_type))?$accommodation_room->bed_type:'';
$extra_bed_type = (isset($accommodation_room->extra_bed_type))?$accommodation_room->extra_bed_type:'';
$is_default = (isset($accommodation_room) && !empty($accommodation_room))?$accommodation_room->is_default:0;
$accommodationRooms = "";
    if(!empty($room_features)){
        $accommodationRooms = App\Models\AccommodationFeature::whereIn('id',$room_features)->get();
    }
$routeName = CustomHelper::getAdminRouteName();
//$back_url=CustomHelper::BackUrl(); 
$back_url = request()->has('back_url') ? request()->input('back_url') : '';

$active_menu = "accommodations".$accommodation_id.'/accommodation-room';    
?>
@if(!empty($accommodation))
@include('admin.includes.accommodationoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('accommodations','edit'))
    <a href="{{ route($routeName.'.accommodations.room_edit', ['id'=>$accommodation_id,'room_id'=>$accommodation_room->id,'back_url'=>$back_url]) }}" class="btn_admin" title="Edit Accommodations Room"><i class="fas fa-edit"></i>Edit Accommodations Room</a>
    @endif
    </div>
    </div>
<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>
<?php 
$room_views = config('custom.room_views');
$bed_types = config('custom.bed_types');
$extra_bed_types = config('custom.extra_bed_types');


?>
<table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td  valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td>
                            <div><b>Display name: </b></div>
                            <div>{{$room_type_name}}</div>
                        </td>
                        <td>
                            <div><b>Room Type: </b></div>
                            <div>{{$room_type}}</div>
                        </td>
                        <td>
                            <div><b>Total Room: </b></div>
                            <div>{{$total_room}}</div>
                        </td>
                         <td>
                        <div><b>Accommodation Name: </b></div>
                        <div>{{ $accommodation_room->roomAccommodation->name ?? "" }}</div>
                    </td>
                    </tr>
                       <tr>
                        <td>
                            <div><b>Room View: </b></div>
                            <div>{{$room_views[$room_view] ?? ''}}</div>
                        </td>
                        <td>
                            <div><b>Bed Type: </b></div>
                            <div>{{$bed_types[$bed_type] ?? ''}}</div>
                        </td>
                        <td>
                            <div><b>Extra Bed Type: </b></div>
                            <div>{{$extra_bed_types[$extra_bed_type] ?? ''}}</div>
                        </td>
                         <td>
                        <div><b>Accommodation Name: </b></div>
                        <div>{{ $accommodation_room->roomAccommodation->name ?? "" }}</div>
                    </td>
                    </tr>
                    <tr>
                   
                    <?php if(!empty($accommodation_room->brief)){ ?>
                    <td>
                        <div><b>Brief: </b></div>
                        <div>{{$accommodation_room->brief}}</div>
                    </td>
                <?php } ?>
               
                    <td colspan="3">
                        <div><b>Features: </b></div>
                        <div><?php
                            if(!empty($accommodationRooms) && !($accommodationRooms->isEmpty())){
                            foreach ($accommodationRooms as $accommFea) {
                                $accomArr[] = $accommFea->title;
                            }
                            echo implode(', ', $accomArr);
                            } ?>
                        </div>
                    </td>
                </tr>
                 <?php /* <tr>
                    <td colspan="3">
                        <div><b>Description: </b></div>
                        <div>{!! $accommodation_room->description !!}</div>
                    </td>
                </tr>
              <tr>
                    <td>
                        <div><b>No. of Rooms to Show: </b></div>
                        <div>{{$accommodation_room->quantity}}</div>
                    </td>
                    <td>
                        <div><b>No. of Rooms: </b></div>
                        <div>{{$accommodation_room->max_quantity}}</div>
                    </td>
                    <td>
                        <div><b>Minimum Stay: </b></div>
                        <div>{{$accommodation_room->minimum_stay}}</div>
                    </td>
                </tr> */ ?>
                <tr>
                    
                    <td>
                        <div><b>Base Occupancy: </b></div>
                        <div>{{$base_occupancy}}</div>
                    </td>
                    <td>
                        <div><b>Base Price: </b></div>
                        <div>{{$base_price}}</div>
                    </td>
                    <td>
                        <div><b>Publish Rate: </b></div>
                        <div>{{$publish_price}}</div>
                    </td>
                    <td>
                        <div><b>Single Price: </b></div>
                        <div>{{$base_price}}</div>
                    </td>
                </tr>
                <tr>
                    
                    <td>
                        <div><b>Number of Max Adult Allowed: </b></div>
                        <div>{{$extra_adult_bed}}</div>
                    </td>
                    <td>
                        <div><b>Price of Extra Adult (12 Years & above): </b></div>
                        <div>{{$price_extra_adult}}</div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <div><b>Number of Base Child Allowed: </b></div>
                        <div>{{$base_child_no}}</div>
                    </td>
                     <td>
                        <div><b>Number of Max Child Allowed: </b></div>
                        <div>{{$max_child_no}}</div>
                    </td>
                    <td>
                        <div><b>Price of Extra Child range (0-5): </b></div>
                        <div>{{$price_extra_child_1}}</div>
                    </td>
                    <td>
                        <div><b>Price of Extra Child (6-12): </b></div>
                        <div>{{$price_extra_child_2}}</div>
                    </td>
                </tr>

                <?php /* <tr>
                    <td>
                        <div><b>Number of Extra bed: </b></div>
                        <div>{{$accommodation_room->no_of_exrta_beds}}</div>
                    </td>
                    <td>
                        <div><b>Max Adults: </b></div>
                        <div>{{$accommodation_room->max_adults}}</div>
                    </td>
                    <td>
                        <div><b>Max Children: </b></div>
                        <div>{{$accommodation_room->max_children}}</div>
                    </td>
                </tr> */ ?>
            
                <tr>
                     <td>
                        <div><b>Max Occupancy: </b></div>
                        <div>{{$max_occupancy}}</div>
                    </td>
    
                    <td>
                    <div><b>Status: </b></div>
                        <?php if($accommodation_room->status == 1){ ?>
                        <span style="color:green">Active</span>
                        <?php   }else{  ?><span style="color:red">Inactive</span>
                    <?php } ?>
                    </td>
                    <td>
                        <div><b>Default: </b></div>
                        <div>{{$accommodation_room->is_default}}</div>
                    </td>
                    <td>
                        <div><b>Sort Order: </b></div>
                        <div>{{$accommodation_room->sort_order}}</div>
                    </td>
                </tr>
            
                <tr>
                    <td>
                        <div><b>Date Created: </b></div>
                        <div>{{ CustomHelper::DateFormat($accommodation_room->created_at, 'd/m/Y') }}</div>
                    </td>
                </tr>
        </table>
    </td>
</tr>

</table>
</div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent