    @component('admin.layouts.main')

    @slot('title')
        Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_package_id = (request()->has('package_id'))?request()->package_id:'';
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>Order Details ({{ $orders->count() }})</h2>
    </div>
    </div>

<!-- Start Search box section-->
<div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Package Name:</label><br/>
                        <input type="text" name="package_id" class="form-control admin_input1" value="{{$old_package_id}}">
                    </div>
                     
                   <!--  <div class="clearfix"></div> -->

                    <div class="col-md-6">
                    <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.enquiries.book_now')}}" class="btn_admin2">Reset</a>

                    </div>
                </form>
            </div>
        </div>
<!-- End Search box Section -->

    <div class="centersec">

            @include('snippets.errors')
            @include('snippets.flash')

        <?php

        if(!empty($orders) && $orders->count() > 0){?>
            <div class="table-responsive">

            {{ $orders->appends(request()->query())->links('vendor.pagination.default') }}

                <table class="table table-bordered">
                    <tr>
                        <th class="">Package</th>
                        <th class="">User</th>
                        <th class="">Data</th>
                        <th class="">Action</th>
                    </tr>
                    <?php
                    
                    foreach ($orders as $orderDetail){ ?>
                        <tr>
                            <td>
                                <p><strong>Package: </strong>{{$orderDetail->Package->title??''}}</p>
                                <p><strong>Package Type: </strong>{{$orderDetail->PackagePrice->title??''}}</p>
                                <p><strong>Trip Date: </strong>{{(!empty($orderDetail->trip_date))?date('d M,Y',strtotime($orderDetail->trip_date)):''}}</p>
                                <?php
                                $total_amount = 0;
                                $price_category_choice_record_arr = json_decode($orderDetail->price_category_choice_record,true)??[];
                                if(!empty($price_category_choice_record_arr)) {
                                    foreach($price_category_choice_record_arr as $pccr) {
                                        $input_label = $pccr['input_label']??'';
                                        $input_value = $pccr['input_value']??0;
                                        $input_price = $pccr['input_price']??0;
                                        $sub_total = $input_value*$input_price;
                                        $total_amount += $sub_total
                                        ?>
                                        <p><strong>{{$input_label}}:</strong> {{$input_value}} X {{CustomHelper::getPrice($input_price)}} = {{CustomHelper::getPrice($sub_total)}}</p> 
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <p><strong>Name: </strong>{{$orderDetail->name??''}}</p>
                                <p><strong>Email: </strong>{{$orderDetail->email??''}}</p>
                                <p><strong>Phone: </strong>{{$orderDetail->phone??''}}</p>
                                
                                <p><strong>Address: </strong>{{$orderDetail->address1??''}}, {{$orderDetail->address2??''}} &nbsp;&nbsp; <strong>City: </strong>{{$orderDetail->city??''}} &nbsp;&nbsp; </p>
                                <p><strong>State: </strong>{{$orderDetail->state??''}} &nbsp;&nbsp; <strong>Country: </strong>{{$orderDetail->Country->name??''}} &nbsp;&nbsp; <strong>Zip Code: </strong>{{$orderDetail->zip_code??''}}</p>
                            </td>
                            <td>
                                <p><strong>Order Id: </strong>{{$orderDetail->order_no??''}}</p>
                                <p><strong>Order Date: </strong>{{ date('d M,Y H:i A',strtotime($orderDetail->created_at)) }}</p>
                                <p><strong>Payment Method: </strong>{{($orderDetail->method=='1')?'PAYPAL':'BANK TRANSFER'}}</p>
                                <p><strong>Payment Status: </strong>{{($orderDetail->status=='1')?'Completed':'Pending'}}</p>
                                <p><strong>Sub Total Amount: </strong>{{CustomHelper::getPrice($orderDetail->sub_total_amount)??''}}</p>
                                <p><strong>Total Amount: </strong>{{CustomHelper::getPrice($orderDetail->total_amount)}}</p>
                            </td>
                            
                            <td>

                                <a href="{{route('admin.enquiries.orderView',[$orderDetail['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>

                                <?php /*@if(CustomHelper::checkPermission('enquiries','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$orderDetail->id}}"><i class="fas fa-trash-alt"></i></i></a> </br></br>

                                <form method="POST" action="{{ route('admin.enquiries.orderDelete', $orderDetail->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Order Enquiry?');" id="delete-form-{{$orderDetail->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="enquiry_id" value="<?php echo $orderDetail->id; ?>">

                                </form>
                                @endif */ ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $orders->appends(request()->query())->links('vendor.pagination.default') }}
            <?php
                    }
                    else{
                ?>
                <div class="alert alert-warning">There are no Records at the present.</div>
                <?php
            }
            ?>
            </div>
      


        @slot('bottomBlock')

        <script type="text/javaScript">
            $('.sbmtDelForm').click(function(){
                var id = $(this).attr('id');
                $("#delete-form-"+id).submit();
            });
        </script>

        @endslot


@endcomponent

