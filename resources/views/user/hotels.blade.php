    @component(config('custom.theme').'.layouts.main')
    @slot('title')
    {{ $meta_title ?? ''}}
    @endslot
    @slot('headerBlock')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
    <style type="text/css">
        .theme_footer:before { z-index: -1;  }
        .btn.s-btn { display: initial; background: #2c2d6c; color: #ffffff !important;}
        .btn2.edit_pofile_btn { font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
    </style>
    @endslot
    <?php 
    $name = (request()->has('name'))?request()->input('name'):'';
    $destination_id = (request()->has('destination_id'))?request()->input('destination_id'):'';
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
                                <h1 class="title">Hotels</h1>
                            </div> 
                        </div>
                    </div> 
                    <form action="" method="GET" class="mt-1">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="city_id">Hotel Name:</label>
                                    <input type="text" class="form-control" name="name" value="{{$name}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="destination"> Destination:</label><br/>
                                <select class="form-control admin_input1" name="destination_id">

                                    <option value="">Select Destination</option>
                                    {!!CustomHelper::getDestinationOptions('', $destination_id)!!}
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>&nbsp</label>
                                <div class="mb-3">
                                    <button type="submit" class="btn s-btn rounded-full">Search</button>
                                    <a class="btn2 edit_pofile_btn" href="{{route('user.hotels')}}">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php if(!empty($hotels) && $hotels->count() > 0){ ?>  
                        <table class="table table_order mt-1">
                            <thead>
                                <tr>
                                    <th scope="col">S No.</th>
                                    <th scope="col">Hotel Name</th>
                                    <th scope="col">Destination</th>
                                    <th scope="col">Discount</th>
                                    <th scope="col">Action</th>                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $current_page = $hotels->currentPage()??1;
                                $per_page = $hotels->perPage()??0;
                                $s_no = ($per_page*($current_page-1))+1;
                                foreach($hotels as $hotel) {
                                    $accommodation_slug = $hotel->slug;
                                    $checkin = $checkin??'';
                                    $checkout = $checkout??'';
                                    $adult = $adult??'';
                                    $child = $child??'';
                                    $infant = $infant??'';
                                    $params = [];
                                    $params['slug'] = $accommodation_slug;
                                    if($checkin) {
                                        $params['checkin'] = $checkin;
                                    }
                                    if($checkout) {
                                        $params['checkout'] = $checkout;
                                    }
                                    if($adult) {
                                        $params['adult'] = $adult;
                                    }
                                    if($child) {
                                        $params['child'] = $child;
                                    }
                                    if($infant) {
                                        $params['infant'] = $infant;
                                    }
                                    $url = route('hotelDetail',$params);
                                    ?>
                                    <tr>
                                        <th><?php echo $s_no++; ?></th>
                                        <td>{{$hotel->name??''}}</td>
                                        <td>{{ !empty($hotel->accommodationDestination) ? $hotel->accommodationDestination->name : ""; }}</td>
                                        <td>
                                            <?php
                                            $price_discount = '';
                                            if($hotel->discount_category_id !== 0) {
                                                $price_discount = CustomHelper::getDiscountType('hotel_listing',$hotel->discount_category_id,0,$group_id,$hotel->id);
                                            }
                                            echo $price_discount;
                                            ?>
                                            <a href="javascript:void(0);" data-src="<?php echo route('user.hotel_price', $hotel['id']) ?> " data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a> 
                                        </td>
                                        <td><a href="{{$url}}" title="book" class="btn">{{(CustomHelper::isOnlineBooking('hotel_listing'))?'Book':'Enquire Now'}}</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="pagination-wrapper hotel-pagination mt-3 ml-0">
                            {{ $hotels->appends(request()->input())->links('vendor.pagination.bootstrap-4'); }}
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="alert_not_found">No Booking data found.</div>
                <?php } ?>
            </div>
        </div>
    </section>
    @slot('bottomBlock')

    @endslot

    @endcomponent