    @component(config('custom.theme').'.layouts.main')
    @slot('title')
    {{ $meta_title ?? ''}}
    @endslot
    @slot('headerBlock')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style type="text/css">
        .theme_footer:before {z-index: -1;}
        .booking-lists thead tr th {border-right: 1px solid #dee2e6;background: var(--theme-color);color: #fff;text-align: left;font-size: 12px;line-height: normal;}
        .user_profile_inner .right_info .top_info {border-bottom: 0;padding-bottom: 8px;}
    </style>
    @endslot
    <?php
    $enquiry_for = (request()->has('enquiry_for'))?request()->enquiry_for:[];
    $enquiry_for = old('enquiry_for',$enquiry_for);

    $search = (request()->has('search'))?request()->search:'';
    $search = old('search',$search);

    $destination = (request()->has('destination'))?request()->destination:[];
    $destination = old('destination',$destination);    

    $range = (request()->has('range'))?request()->range:'custom';
    $range = old('range', $range);

    $from = (request()->has('from'))?request()->from:date('d/m/Y');
    $from = old('from', $from);

    $to = (request()->has('to'))?request()->to:date('d/m/Y');
    $to = old('to', $to);

    $order_type = (request()->has('order_type'))?request()->order_type:'';
    $order_type = old('order_type',$order_type);

    $order_type_arr = config('custom.order_type');
    $range_filters = config('custom.range_filters');
    $enq_for_types = config('custom.enq_for_types');
    $is_agent = Auth::user()->is_agent??0;
    ?>
    <section>
        <div class="container-fluid">
            <div class="user_profile_inner">
                @include('user.left_sidebar')
                <div class="right_info">
                    <div class="top_info">
                        <div class="left">
                            <div class="theme_title">
                                <h1 class="title">My Enquiry</h1>
                            </div>
                        </div>
                    </div>

                    <div class="tab_block toptab">
                        <ul class="nav nav-tabs">
                            <?php
                            $all_search_data = $search_data;
                            ?>
                            <li class="nav-item">
                                <a <?php if($order_type==''){echo 'class="active"' ;}?> href="{{route('user.myenquiry',$all_search_data)}}">All</a>
                            </li>

                            <?php if(CustomHelper::isAllowedModule('flight')) {
                                $flight_search_data = $search_data;
                                $flight_search_data['order_type'] = 3;
                                ?>
                                <li class="nav-item">
                                    <a <?php if($order_type=='3'){echo 'class="active"' ;}?> href="{{route('user.myenquiry',$flight_search_data)}}">Flight</a>
                                </li>
                            <?php } ?>

                            <?php if(CustomHelper::isAllowedModule('cab')) {
                                $cab_search_data = $search_data;
                                $cab_search_data['order_type'] = 4;
                                ?>
                                <li class="nav-item">
                                    <a <?php if($order_type=='4'){echo 'class="active"' ;}?> href="{{route('user.myenquiry',$cab_search_data)}}">Cab</a>
                                </li>
                            <?php } ?>

                            <?php if(CustomHelper::isAllowedModule('package_listing')) {
                                $package_search_data = $search_data;
                                $package_search_data['order_type'] = 1;
                                ?>
                                <li class="nav-item">
                                    <a <?php if($order_type=='1'){echo 'class="active"' ;}?> href="{{route('user.myenquiry',$package_search_data)}}">Package</a>
                                </li>
                            <?php } ?>

                            <?php if(CustomHelper::isAllowedModule('activity_listing')) {
                                $activity_search_data = $search_data;
                                $activity_search_data['order_type'] = 6;
                                ?>
                                <li class="nav-item">
                                    <a <?php if($order_type=='6'){echo 'class="active"' ;}?> href="{{route('user.myenquiry',$activity_search_data)}}">Activity</a>
                                </li>
                            <?php } ?>

                            <?php if(CustomHelper::isOnlineBooking('hotel_listing')) {
                                $hotel_search_data = $search_data;
                                $hotel_search_data['order_type'] = 5;
                                ?>
                                <li class="nav-item">
                                    <a <?php if($order_type=='5'){echo 'class="active"' ;}?> href="{{route('user.myenquiry',$hotel_search_data)}}">Hotel</a>
                                </li>
                            <?php } ?>

                            <?php if(CustomHelper::isAllowedModule('rental')) {
                                $bike_search_data = $search_data;
                                $bike_search_data['order_type'] = 8;
                                ?>
                                <li class="nav-item">
                                    <a <?php if($order_type=='8'){echo 'class="active"' ;}?> href="{{route('user.myenquiry',$bike_search_data)}}">Rental</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                        <div class="booking__search">
                            <form class="form-inline" method="GET">
                                <input type="hidden" name="order_type" value="{{$order_type}}">
                                <div class="row">
                                    <?php /*
                                    <div class="w-1/6{{$errors->has('range')?' has-error':''}}">
                                        <label for="FormControlSelect1">Enquiry For</label><br/>
                                        <select name="enquiry_for[]" id="customSelect1" class="form-control admin_input1 select2 myselect" multiple="multiple">
                                            <option value="">--Select Enquiry For--</option>
                                            @foreach($enq_for_types as $k => $v)
                                            <option value="{{$k}}" {{(!empty($enquiry_for) && in_array($k,$enquiry_for))?'selected':''}} >{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>*/ ?>
                                    <div class="w-1/6{{$errors->has('range')?' has-error':''}}">
                                        <label for="FormControlSelect1">Search</label><br/>
                                        <input type="text" name="search" class="form-control" value="{{$search}}">
                                    </div>
                                    <div class="w-1/6{{$errors->has('destination')?' has-error':''}}">
                                        <label for="FormControlSelect1">Destination</label><br/>
                                        <select name="destination[]" id="customSelect2" class="form-control admin_input1 select2 myselect" multiple="multiple">
                                            <option value="">--Select Destination--</option>
                                            {!!CustomHelper::getDestinationOptions('', $destination)!!}
                                        </select>
                                    </div>


                                    <div class="w-1/6{{$errors->has('range')?' has-error':''}} rangeDiv">
                                        <label for="FormControlSelect1">Date Range</label><br/>
                                        <select name="range" class="form-control admin_input1 range select2">
                                            <option value="">--Select Range--</option>
                                            <?php if(isset($range_filters) && !empty($range_filters)){
                                                unset($range_filters['tomorrow']);
                                                unset($range_filters['next_week']); ?>
                                                @foreach($range_filters as $k => $v)
                                                <option value="{{$k}}" {{(!empty($range) && $range == $k)?'selected':''}}>{{$v}}</option>
                                                @endforeach
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="w-1/6{{$errors->has('from')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
                                        <label for="FormControlSelect1">From Date</label><br/>
                                        <input type="text" name="from" class="form-control from_date" placeholder="From Date"
                                        value="{{$from}}" autocomplete="off">
                                    </div>

                                    <div class="w-1/6{{$errors->has('to')?' has-error':''}}{{(!empty($range) && $range=='custom')?'':' hide'}} dateDiv">
                                        <label for="FormControlSelect1">To Date</label><br/>
                                        <input type="text" name="to" class="form-control to_date" placeholder="To Date"
                                        value="{{$to}}" autocomplete="off">
                                    </div>


                                    <div class="w-1/6 flex search-block">
                                        <label for="FormControlSelect1"></label><br/>
                                        <button type="submit" class="btn s-btn btn-info sky_blue rounded-full">Search</button>
                                        <a href="{{route('user.myenquiry')}}" class="btn2 btn-info edit_pofile_btn">Reset</a>
                                    </div> 
                                </div>
                            </form>
                        </div>

                    @include('snippets.front.flash')
                    <?php if(!empty($enquiries) && $enquiries->count() > 0){ ?>
                        <div class="booking-lists table-responsive mt-2">
                            <table id="datatable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Enquiry Date</th>
                                        <th>Enquiry For</th>
                                        <th>
                                            Name /
                                            Contact Number /
                                            Email ID
                                        </th>
                                        <th>Destination</th>
                                        <th>Package/Activity/Accommodation</th>
                                        <th>No.of Persons</th>
                                        <th>Trip Date</th>
                                        <th>Duration</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($enquiries as $enquiry) { ?>
                                        <tr id="row-{{$enquiry->id}}">
                                            <td>
                                                {{CustomHelper::DateFormat($enquiry->created_at,'d/m/Y','Y-m-d')}}
                                            </td>
                                            <td>
                                                <?php
                                                $enquiry_for = $enquiry->EnquiryForType->pluck('enquiry_for_id')->toArray();
                                                if(!empty($enquiry_for)) {
                                                    $enquiry_for_values = CustomHelper::showEnquiryForType($enquiry_for);
                                                    if($enquiry_for_values) {
                                                        echo implode(', ', $enquiry_for_values);
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td class="user_icon_top">
                                                {{$enquiry->name}} <br />
                                                {{$enquiry->phone}} <br />
                                                {{$enquiry->email}}
                                            </td>
                                            <td>
                                                <?php
                                                $destination_ids = $enquiry->Destination->pluck('destination_id')->toArray();
                                                if(!empty($destination_ids)) {
                                                    $destination_values = CustomHelper::showDestination($destination_ids);
                                                    if($destination_values) {
                                                        echo implode(', ', $destination_values);
                                                    }
                                                }
                                                ?>
                                                <hr />
                                                <?php
                                                $destination_ids = $enquiry->SubDestination->pluck('destination_id')->toArray();
                                                if(!empty($destination_ids)) {
                                                    $destination_values = CustomHelper::showDestination($destination_ids);
                                                    if($destination_values) {
                                                        echo implode(', ', $destination_values);
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                {{$enquiry->package_name??''}} <br />
                                                <?php
                                                $accommodation_ids = $enquiry->Accommodation->pluck('accommodation_id')->toArray();
                                                if(!empty($accommodation_ids)) {
                                                    $accommodation_values = CustomHelper::showAccommodation($accommodation_ids);
                                                    if($accommodation_values) {
                                                        echo implode(', ', $accommodation_values);
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>{{$enquiry->no_of_pax}}</td>
                                            <td>{{CustomHelper::DateFormat($enquiry->date_of_travel,'d/m/Y')}}</td>
                                            <td>{{$enquiry->duration}}</td>
                                            <td>{!!nl2br($enquiry->content)!!}</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-wrapper hotel-pagination mt-3 ml-0">
                            {{ $enquiries->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>

                <?php }else{ ?>
                    <div class="alert_not_found">No enquiries data found.</div>
                <?php } ?>
            </div>
        </div>
    </section>
    @slot('bottomBlock')

    <link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
    <script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $('.select2').select2({ placeholder: "Select Date", allowClear: true });
    // ============== Select Box Start ============

    $('.myselect').select2({closeOnSelect: true,  placeholder: "Please Select",}).on("change", function(e) {

        var counter = $(this).next('.select2-container').find(".select2-selection__choice").length;
        if(counter > 2){
            if($(this).next('.select2-container').find('.counter').length <= 0){
                $(this).next('.select2-container').find('.select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter"> ('+counter+' selected)</div>');
            }else{
                $(this).next('.select2-container').find('.counter').text(`(${counter} selected)`);
            }
        }else{
            $(this).next('.select2-container').find('.counter').remove();
        }
    });
    $(document).ready(function() {
        $( ".to_date, .from_date" ).datepicker({
            dateFormat:'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            yearRange: "-90:+01"

        });

        $('.range').on('change', function() {
            if (this.value == 'custom') {
                $('.dateDiv').removeClass('hide');
                $('.days_tab').hide();
            } else {
                $('.days_tab').show();
                $('.dateDiv').addClass('hide');
                $('input[name=from]').val('');
                $('input[name=to]').val('');
            }
        });
    });
</script>
@endslot
@endcomponent