@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .btn.s-btn { display: initial; background: #2c2d6c; color: #ffffff !important;}
    .btn2.edit_pofile_btn { font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
</style>
@endslot

<?php 
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';
$old_status = (request()->has('status'))?request()->status:1; 
$selected_bike = (request()->has('bike'))?request()->bike:[];
$bike_name = (request()->has('bike'))?request()->bike:'';
$selected_location = (request()->has('location'))?request()->location:[];

$storage = Storage::disk('public');
$group_id =  (auth()->user()) ? auth()->user()->group_id : '';
?>

<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">Rentals</h1>
                        </div> 
                    </div>

                </div> 
                <div class="row">
                    <form action="" method="GET" class="mt-1">
                        <form class="form-inline" method="GET">
                            <div class="col-md-3">
                                <label>City</label><br/>
                                <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                            </div>
                            <div class="col-md-3">
                                <label>Bike</label><br/>
                                <input type="text" name="bike" class="form-control admin_input1" value="{{$bike_name}}">
                            </div>

@if(!empty($bikeCityLocation) && isset($bikeCityLocation))
<div class="col-md-3">
    <label>Pickup Location:</label><br/>
    <select name="location[]" class="form-control admin_input1 select2" multiple="multiple">
        <?php foreach ($bikeCityLocation as $location_data) {
            $selected = "";
            if(in_array($location_data->id,$selected_location)){ $selected = "selected"; }
            ?>
            <option value="{{$location_data->id}}" {{$selected}}>{{$location_data->name}}</option>
        <?php } ?>
    </select>                  
</div>
@endif

<div class="col-md-3">
    <label>&nbsp</label>
    <div class="mb-3">
        <button type="submit" class="btn s-btn rounded-full">Search</button>
        <a class="btn2 edit_pofile_btn" href="{{route('user.rental')}}">Reset</a>
    </div>
</div>
</form>
</form>
</div>
<?php if(!empty($res) && $res->count() > 0){ ?>  
    <table class="table table_order mt-1">
        <tr>
            <th class="">City</th>
            <th class="">Bike</th>
            <th class="">Pickup Location</th>
            <th class="">Km Included</th>
            <th class="">Discount</th>
            <th class="">Action</th>                   
        </tr>
        <tbody>
            <?php foreach ($res as $rec) {
                $city_data = $rec->city ??'';
                $bike_data = $rec->bikeData ??'';
                ?>  
                <tr>
                    <td>{{$city_data->name??''}}</td>

                    <td>
                        {{$bike_data->name}}
                    </td>
                    <td>
                        <?php
                        $locations = $city_data->locations;
                        $location_names = [];
                        foreach ($locations as  $rowLocation) {
                            $location_names[] = $rowLocation->name ;
                        }
                        echo implode(', ',$location_names);
                        ?>
                    </td>
                    <td>
                        <?php if(!empty($city_data->km_included)) { ?> 
                            {{$city_data->km_included}} Km
                        <?php } ?>
                    </td>
                    <td>
                        <?php
                        $price_discount = '';
                        if($city_data->discount_category_id !== 0) {
                            $price_discount = CustomHelper::getDiscountType('rental',$city_data->discount_category_id,0,$group_id,$city_data->id);
                        }
                        echo $price_discount;
                        ?>
                        <a href="javascript:void(0);" data-src="<?php echo route('user.rental_price', $rec['id']) ?> " data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                    </td>
                    <td><a href="{{route('bikeListing',['city'=>$rec->city_id,'model'=>$bike_data->modal])}}" title="book" class="btn">{{(CustomHelper::isOnlineBooking('hotel_listing'))?'Book':'Enquire Now'}}</a></td>
                </tr>
            <?php  } ?>
        </tbody>
    </table>
    <div class="pagination-wrapper hotel-pagination mt-3 ml-0">
        {{ $res->appends(request()->input())->links('vendor.pagination.bootstrap-4'); }}
    </div>
</div>
<?php }else{ ?>
    <div class="alert_not_found">No Booking data found.</div>
<?php } ?>
</div>
</div>
</section>
@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javaScript">
    $('.select2').select2({ placeholder: "Please Select", allowClear: true }); 
</script>
@endslot
@endcomponent


