@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .btn.s-btn { display: initial; background: #2c2d6c; color: #ffffff !important;}
    .btn2.edit_pofile_btn {font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
</style>
@endslot
<?php
$name = (request()->has('name'))?request()->input('name'):'';
$destination = (request()->has('destination'))?request()->input('destination'):'';

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
                            <h1 class="title">{{$page_heading}}</h1>
                        </div>
                    </div>
                </div> 

                <form action="" method="GET" class="mt-1">
                    <div class="row">
                     <div class="col-md-3">
                        <div class="mb-3">
                            <label for="city_id">Name:</label>
                            <input type="text" class="form-control" name="name" value="{{$name}}">
                        </div>
                    </div>

                   
                    <div class="col-md-3">
                        <label for="city_id">Destination:</label>
                        <select name="destination" id="" class="form-control" data-placeholder="Select Destination">
                          <option value="">Select Destination</option>
                          {!!CustomHelper::getDestinationOptions('', $destination, ['show_active'=>true])!!}
                      </select>
                  </div>

                     <div class="col-md-3">
                        <label>&nbsp</label>
                        <div class="mb-3">
                     <button type="submit" class="btn s-btn rounded-full">Search</button>
                     <!-- <a href="{{url('/user/packages')}}" class="btn ">Reset</a> -->
                     <a class="btn2 edit_pofile_btn" href="{{route('user.'.$page_type)}}">Reset</a>
                </div>
                </div>
                
                </div>
            </form>
                <?php if(!empty($packages) && $packages->count() > 0){ ?>  
                    <table class="table table_order mt-1">
                    <thead>
                    <tr>
                    <th scope="col">S No.</th>
                    <th scope="col">Package Name</th>
                    <th scope="col">Duration </th>
                    <th scope="col">Retail Price </th>
                    <th scope="col">Discount </th>
                    <th scope="col">Agent Price </th>
                    <th scope="col" style="width: 115px;">Action </th>
                    </tr>
                    </thead>
                    <tbody class="text-sm">
                    <?php
                    $current_page = $packages->currentPage()??1;
                    $per_page = $packages->perPage()??0;
                    $s_no = ($per_page*($current_page-1))+1;
                    foreach($packages as $package) {
                        $discount_category_id = $package->discount_category_id??'';
                        $publish_price = $package->publish_price ?? 0;
                        $discount_amount = 0;
                        if($publish_price > 0 && $discount_category_id !== 0) {
                            $module_name = 'package_listing';
                            $is_activity = $package->is_activity??'';
                            if($is_activity == 1) {
                                $module_name = 'activity_listing';
                            }
                            $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$group_id,$package->id);
                        }
                        $agent_amount = $publish_price - $discount_amount;
                    ?>
                    <tr>
                    <th><?php echo $s_no++; ?></th>
                    <td>{{$package->title??''}}</td>
                    <td>{{$package->package_duration??''}}</td>
                    <td> {{CustomHelper::getPrice($package->publish_price)??''}}</td>
                    <td> {{CustomHelper::getPrice($discount_amount)??''}}</td>
                    <td> {{CustomHelper::getPrice($agent_amount)??''}}</td>
                    <?php 
                    $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';

                    $isOnlineBooking =  (CustomHelper::isOnlineBooking('package_listing'))?'Book':'Enquire Now';
                    $is_activity = $package->is_activity??'';
                    if($is_activity == 1) {
                    $isOnlineBooking = (CustomHelper::isOnlineBooking('activity_listing'))?'Book':'Enquire Now';
                    }
                    ?>
                    <td><a href="{{route($packageDetailName,['slug'=>$package->slug])}}" title="book" class="btn">{{$isOnlineBooking}}</a></td>
                    </tr>
                     <?php } ?>
                    </tbody>
                    </table>
                    <div class="pagination-wrapper hotel-pagination mt-2 ml-0">
                        {{ $packages->appends(request()->input())->links('vendor.pagination.bootstrap-4'); }}
                    </div>
                </div>
            <?php }else{ ?>
            <div class="alert_not_found">No record found.</div>
            <?php } ?>
        </div>
        </div>
    </section>
@slot('bottomBlock')


@endslot

@endcomponent


