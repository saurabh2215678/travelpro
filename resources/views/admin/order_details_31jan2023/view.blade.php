@component('admin.layouts.main')

@slot('title')
Admin - Manage Order Details View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
//prd($order);
$user_id = (isset($order->user_id))?$order->user_id:'';
$package_id = (isset($order->package_id))?$order->package_id:'';
$order_no = (isset($order->order_no))?$order->order_no:'';
$name = (isset($order->name))?$order->name:'';
$phone = (isset($order->phone))?$order->phone:'';
$email = (isset($order->email))?$order->email:'';
$country = (isset($order->country))?$order->country:'';
$method = (isset($order->method))?$order->method:'';
$comment = (isset($order->comment))?$order->comment:'';
$payment_status = (isset($order->payment_status))?$order->payment_status:'';
// $payment_description = (isset($order->payment_description))?$order->payment_description:'';
// $payment_response = (isset($order->payment_response))?$order->payment_response:'';
$service_level = (isset($order->service_level))?$order->service_level:'';
$discount = (isset($order->discount))?$order->discount:'';
$discount_type = (isset($order->discount_type))?$order->discount_type:'';
$sub_total_amount = (isset($order->sub_total_amount))?$order->sub_total_amount:'';
$total_amount = (isset($order->total_amount))?$order->total_amount:'';
$created_at = (isset($order->created_at))?$order->created_at:'';
$created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');
?>
<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="100%" valign="top" class="innersec">


                <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                    <h2>Order Details (Order ID: #{{$order->order_no??''}})</h2>
                    <tr>
                        <th class="">Package</th>
                        <th class="">User</th>
                        <th class="">Data</th>
                    </tr>
                        <tr>
                            <td>
                                <p><strong>Package: </strong>{{$order->Package->title??''}}</p>
                                <p><strong>Package Type: </strong>{{$order->PackagePrice->title??''}}</p>
                                <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                <?php
                                $total_amount = 0;
                                $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
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
                                <p><strong>Name: </strong>{{$order->name??''}}</p>
                                <p><strong>Email: </strong>{{$order->email??''}}</p>
                                <p><strong>Phone: </strong>{{$order->phone??''}}</p>
                                
                                <p><strong>Address: </strong>{{$order->address1??''}}, {{$order->address2??''}} &nbsp;&nbsp; <strong>City: </strong>{{$order->city??''}} &nbsp;&nbsp; </p>
                                <p><strong>State: </strong>{{$order->state??''}} &nbsp;&nbsp; <strong>Country: </strong>{{$order->Country->name??''}} &nbsp;&nbsp; <strong>Zip Code: </strong>{{$order->zip_code??''}}</p>
                            </td>
                            <td>
                                <p><strong>Order Id: </strong>{{$order->order_no??''}}</p>
                                <p><strong>Order Date: </strong>{{ date('d M,Y H:i A',strtotime($order->created_at)) }}</p>
                                <p><strong>Payment Method: </strong>{{($order->method=='1')?'PAYPAL':'BANK TRANSFER'}}</p>
                                <p><strong>Payment Status: </strong>{{($order->status=='1')?'Completed':'Pending'}}</p>
                                <p><strong>Sub Total Amount: </strong>{{CustomHelper::getPrice($order->sub_total_amount)??''}}</p>
                                <p><strong>Total Amount: </strong>{{CustomHelper::getPrice($order->total_amount)}}</p>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="3"><p><strong>Comment: </strong>{!!$order->comment!!}</p></td>
                        </tr>
                    </table>

        <?php /*
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
            <h2>Order Details</h2>

            <tr>
                <td><b>User Name : </b></td>
                <td>{{$order->User->name??''}}</td>
            </tr>

            <tr>
                <td><b>Package Name : </b></td>
                <td>{{$order->Package->title??''}}</td>
            </tr>

            <tr>
                <td><b>Name : </b></td>
                <td>{{$order->name}}</td>
            </tr>

            <tr>
                <td><b>Phone : </b></td>
                <td>{{$order->phone}}</td>
            </tr>

            <tr>
                <td><b>Email : </b></td>
                <td>{{$order->email}}</td>
            </tr>

            <tr>
                <td><b>Country : </b></td>
                <td>{{$order->Country->name??''}}</td>
            </tr>

            <tr>
                <td><b>Comment: </b></td>
                <td>{{$order->comment}}</td>
            </tr>

            <tr>
                <td><b>Payment Method: </b></td>
                <td><?php 
                    echo ($order->method == 1) ? "PAYPAL" : "BANK TRANSFER";
                    ?>
                </td>
            </tr>

            <tr>
                <td><b>Payment Status: </b></td>
                <td><?php echo ($order->status == 1) ? "Completed" : "Pending"; ?>
                </td>
            </tr>

            <!-- <tr>
                <td><b>Payment Description: </b></td>
                <td>{{$order->payment_description}}</td>
            </tr>

            <tr>
                <td><b>Payment Response: </b></td>
                <td>{{$order->payment_response}}</td>
            </tr> -->

            <tr>
                <td><b>Service Level: </b></td>
                <td><?php 
                    echo ($order->service_level == 1) ? "Standard" : "Deluxe";
                    ?>       
                </td>
            </tr>

             <tr>
                <td><b>Discount: </b></td>
                <td>{{$order->discount}}</td>
            </tr>

            <tr>
                <td><b>Discount Type: </b></td>
                <td>{{$order->discount_type}}</td>
            </tr>

            <tr>
                <td><b>Sub Total Amount: </b></td>
                <td>{{$order->sub_total_amount}}</td>
            </tr>
           
           <tr>
                <td><b>Total Amount: </b></td>
                <td>{{$order->total_amount}}</td>
            </tr>

            <tr>
                <td><b>Package Booking Date: </b></td>
                <td>{{ CustomHelper::DateFormat($order->created_at, 'd/m/Y') }}</td>
            </tr>
        </table>*/ ?>
    </td>
</tr>
</table> 
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent