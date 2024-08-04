<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        .enq-view ul>li {
            width: 300px;
            display: inline-table;
        }
    </style>
</head>
<body>
<?php
$order = $enquiry->Order??[];
$order_type_arr = config('custom.order_type');
$enq_for_types = config('custom.enq_for_types');
?>

    <div class="enq-view fancybox-content">
        <div class="row">
            <div class="col-sm-12">
                @include('snippets.errors')
                @include('snippets.flash')
                <h2>{{$enquiry->Form->name??''}} Enquiry Details (Enquiry ID: #{{$enquiry->enquiry_no_id}})</h2>
                <ul>
                    <li><label>Name:</label> {{$enquiry->name??''}}</li>
                    <li><label>Contact Number:</label> {{$enquiry->phone??''}}</li>
                    <li><label>Email ID:</label> {{$enquiry->email??''}}</li>
                    <li>
                        <label>Enquiry For:</label> 
                        <?php
                        $enquiry_for = $enquiry->EnquiryForType->pluck('enquiry_for_id')->toArray();
                        if(!empty($enquiry_for)) {
                            $enquiry_for_values = CustomHelper::showEnquiryForType($enquiry_for);
                            if($enquiry_for_values) {
                                echo implode(', ', $enquiry_for_values);
                            }
                        }
                        ?>
                    </li>
                    <li>
                        <label>Destination:</label> 
                        <?php
                        $destination_ids = $enquiry->Destination->pluck('destination_id')->toArray();
                        if(!empty($destination_ids)) {
                            $destination_values = CustomHelper::showDestination($destination_ids);
                            if($destination_values) {
                                echo implode(', ', $destination_values);
                            }
                        }
                        ?>
                    </li>
                    {{--<li>
                        <label>Sub Destination:</label> 
                        <?php
                        $destination_ids = $enquiry->SubDestination->pluck('destination_id')->toArray();
                        if(!empty($destination_ids)) {
                            $destination_values = CustomHelper::showDestination($destination_ids);
                            if($destination_values) {
                                echo implode(', ', $destination_values);
                            }
                        }
                        ?>
                    </li>--}}
                    <li>
                        <label>Other Destination:</label> 
                        {{$enquiry->other_destination??''}}
                    </li>
                    <?php
                    // $x_var = $enquiry->EnquiryForType;
                    //  $enquiry_type = !empty($enq_for_types[$x_var[0]['enquiry_for_id']])?$enq_for_types[$x_var[0]['enquiry_for_id']]:'';
                    ?>
                    <li><label>Package/Activity:</label> {{$enquiry->package_name??''}}<?php /*{{$enquiry->Package->title??''}}*/ ?></li>
                    <li><label>Other Package:</label> {{$enquiry->other_package??''}}</li>
                    <li>
                        <label>Accommodation:</label> 
                        <?php
                        $accommodation_ids = $enquiry->Accommodation->pluck('accommodation_id')->toArray();
                        if(!empty($accommodation_ids)) {
                            $accommodation_values = CustomHelper::showAccommodation($accommodation_ids);
                            if($accommodation_values) {
                                echo implode(', ', $accommodation_values);
                            }
                        }
                        ?>
                    </li>
                    <li>
                        <label>Accommodation Type:</label> 
                        <?php
                        $accommodation_type_ids = $enquiry->AccommodationType->pluck('accommodation_type_id')->toArray();
                        if(!empty($accommodation_type_ids)) {
                            $accommodation_type_values = CustomHelper::showAccommodationType($accommodation_type_ids);
                            if($accommodation_type_values) {
                                echo implode(', ', $accommodation_type_values);
                            }
                        }
                        ?>
                    </li>
                    <li><label>Accommodation Comment:</label> {{$enquiry->accommodation_comment??''}}</li>
                    <li><label>Meal Plan:</label> <?php if(!empty($enquiry->meal_plans) && !empty($enquiry->meal_plans)) echo implode(', ', json_decode($enquiry->meal_plans));  ?></li>
                    <li><label>Activity:</label> {{$enquiry->activity??''}}</li>
                    <li><label>Other Activity:</label> {{$enquiry->other_activity??''}}</li>
                    <li>
                        <label>Date Of Travel:</label> 
                        @if($enquiry->date_of_travel)
                        <i class="fa fa-calendar"></i>{{CustomHelper::DateFormat($enquiry->date_of_travel,'d/m/Y','Y-m-d')}}
                        @endif
                    </li>
                    <li><label>No Of Pax:</label> {{$enquiry->no_of_pax??''}}</li>
                    <li><label>Adults - Above 12 Yrs:</label> {{$enquiry->adu_abo_12??''}}</li>
                    <li><label>Children 6-12 Yrs:</label> {{$enquiry->chi_6_12??''}}</li>
                    <li><label>Children 2-5 Yrs:</label> {{$enquiry->chi_2_5??''}}</li>
                    <li><label>Infants upto 2 Yrs:</label> {{$enquiry->infants_upto_2??''}}</li>
                    <li><label>Lead Source:</label> {{CustomHelper::showEnquiryMaster($enquiry->lead_source??'')}}</li>
                    <li><label>Lead Status:</label> {{CustomHelper::showEnquiryMaster($enquiry->lead_status??'')}}</li>
                    {{--<li><label>Lead Sub Status:</label> {{CustomHelper::showEnquiryMaster($enquiry->lead_sub_status??'')}}</li>--}}

                    <?php //$color=''; if(CustomHelper::showEnquiryMaster($enquiry->rating) == 'Hot'){$color='red';} elseif(CustomHelper::showEnquiryMaster($enquiry->rating) == 'Cold'){$color='';} elseif(CustomHelper::showEnquiryMaster($enquiry->rating) == 'Warm'){$color='orange';} ?>
                    <li><label>Rating:</label> <span style="color: {{--$color--}};"> {{CustomHelper::showEnquiryMaster($enquiry->rating??'')}} </span> </li>

                    <li>
                        <label>Followup Date:</label> 
                        @if($enquiry->followup_date)
                        <i class="fa fa-calendar"></i>{{CustomHelper::DateFormat($enquiry->followup_date,'d/m/Y','Y-m-d')}}
                        @endif
                    </li>
                    
                    <li>
                        <label>Enquiry Date:</label> 
                        <i class="fa fa-calendar"></i> {{CustomHelper::DateFormat($enquiry->created_at,'d/m/Y','Y-m-d')}}
                    </li>
                    <li><label>Assign:</label> {{CustomHelper::showAdmin($enquiry->cc_id)}}</li>

                    <?php
                
                    if(empty($order)) {
                        if($enquiry->form_data) {
                            $form_data = json_decode($enquiry->form_data,true);
                            if(!empty($form_data)) { ?>
                            <h2 style="background: #0096881c;color: #009688;margin: 10px;margin-right: 15px;padding-left: 10px;">Form Fields Extra Data:</h2>
                            <?php
                                foreach($form_data as $fd_key => $fd_val) {
                                    if($fd_key=='country' && !empty($fd_val) && is_numeric($fd_val)) {
                                        $fd_val = CustomHelper::GetCountry($fd_val,'name');
                                    }
                                    
                                    if($fd_key=='When Do You Plan To Travel?' && !empty($fd_val)) {
                                        $fd_val = CustomHelper::DateFormat($fd_val,'d/m/Y');
                                    }
                    ?>
                    <li><label>{{$fd_key}}:</label> 
                        @if(is_array($fd_val))
                            @foreach ($fd_val as $item)
                                {!! nl2br($item) !!}
                            @endforeach
                        @else
                            {!! nl2br($fd_val) !!}
                            @endif
                        </li>
                    <?php
                                }
                            }
                        }
                    }
                    ?>
                    
                </ul>
            </div>
        </div>

        <?php if(!empty($order)){ ?>
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
            <h2>Order Details (Order ID: #{{$order->order_no??''}})</h2>
            <tr>
                <th class="">Order</th>
                <th class="">User</th>
                <th class="">Data</th>
            </tr>
                <tr>
                    <td>
                        <?php   
                                $order_type = !empty($order_type_arr[$order->order_type])?$order_type_arr[$order->order_type]:'';
                                //prd($order_type);
                                ?>
                                <p><strong>Order Id: </strong>{{$order->order_no??''}}</p>
                                <p><strong>Order Date: </strong>{{ date('d M,Y H:i A',strtotime($order->created_at)) }}</p>
                                <p><strong>Order Type: </strong>{{$order_type}}</p>
                                <?php if($order->order_type ==1){ ?>
                                <p><strong>Package: </strong>{{$order->package_name??''}}<?php /*{{$order->Package->title??''}}*/ ?></p>
                                <p><strong>Package Type: </strong>{{$order->package_type_name??''}}<?php /*{{$order->PackagePrice->title??''}}*/ ?></p>
                                <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                <?php
                                }
                                if($order->order_type == 6){ ?>
                                <p><strong>Activity: </strong>{{$order->package_name??''}}</p>
                                <p><strong>Activity Type: </strong>{{$order->package_type_name??''}}</p>
                                <p><strong>Trip Date: </strong>{{(!empty($order->trip_date))?date('d M,Y',strtotime($order->trip_date)):''}}</p>
                                <?php
                                }
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
                        <p><strong>State: </strong>{{$order->state??''}} &nbsp;&nbsp; <strong>Country: </strong>{{CustomHelper::GetCountry($order->country??'','name')}} &nbsp;&nbsp; <strong>Zip Code: </strong>{{$order->zip_code??''}}</p>
                    </td>
                    <td>
                        <p>
                            <strong>Payment Method: </strong>
                            {{CustomHelper::getPaymentGatewayName($order->method)}}
                        </p>
                        <p>
                            <strong>Payment Status: </strong>
                            {!!CustomHelper::showPaymentStatus($order->payment_status??0)!!}
                        </p>
                        <?php if( (!empty($order->discount) && $order->discount > 0) || (!empty($order->fees) && $order->fees > 0) ){ ?>
                        <p><strong>Sub Total: </strong>{{CustomHelper::getPrice($order->sub_total_amount)??''}}</p>
                        <?php if(!empty($order->discount) && $order->discount > 0){ ?>
                        <p><strong>Discount: </strong>{{CustomHelper::getPrice($order->discount)??''}}</p>
                        <?php } ?>
                        <?php if(!empty($order->fees) && $order->fees > 0){ ?>
                        <p><strong>Fees: </strong>{{CustomHelper::getPrice($order->fees)??''}}</p>
                        <?php } ?>
                        <?php } ?>

                        <?php
                        $total_amount = $order->total_amount??0;
                        $partial_amount = $order->partial_amount??0;
                        $payment_due = 0;
                        if($partial_amount) {
                            $payment_due = $total_amount - $partial_amount;
                        }
                        ?>
                        <p><strong>Total Amount: </strong>{{CustomHelper::getPrice($total_amount)}}</p>
                        <?php if($partial_amount && $total_amount > $partial_amount && $order->pay_type != 'book_without_payment'){ ?>
                          <!-- <p><strong>Partial Paid Amount:</strong>{{CustomHelper::getPrice($order->partial_amount)}}</p> -->

                          <p>
                            <?php if($order->payment_status == 1){ 
                                echo '<strong>Amount Paid:</strong><span style="color:orange">'.CustomHelper::getPrice($partial_amount).'</span>'; } ?></p>
                            <?php }


                            if($payment_due == 0){ ?>
                                <p>
                                    <?php if($order->payment_status == 1){
                                        echo '<strong>Total Amount Paid: </strong><span style="color:green">'.CustomHelper::getPrice($total_amount).'</span>';
                                    }elseif($order->payment_status == 2){
                                        echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($total_amount).'</span>';

                                    }elseif($order->payment_status == 0){
                                        echo '<strong>Amount Due: </strong><span style="color:orange">'.CustomHelper::getPrice($total_amount).'</span>';
                                    } ?></p>
                                <?php }else {
                                    if($order->pay_type != 'book_without_payment'){
                                        if($order->payment_status == 1){
                                            echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($payment_due).'</span>';

                                        }elseif($order->payment_status == 2){
                                            echo '<strong>Amount Due: </strong><span style="color:red">'.CustomHelper::getPrice($total_amount).'</span>';

                                        }elseif($order->payment_status == 0){
                                            echo '<strong>Amount Due: </strong><span style="color:orange">'.CustomHelper::getPrice($total_amount).'</span>';
                                        }?>
                                    <?php } } ?>

                    </td>

                </tr>
                <tr>
                    <td colspan="3"><p><strong>Comment: </strong>{!!nl2br($order->comment)!!}</p></td>
                </tr>
            </table>
        <?php } ?>

        <div class="row">
            <div class="col-sm-12">
            <h5>Interaction ({{$enquiry->EnquiryInteraction->count()}})</h5>
                <table id="user_datatable" class="user-list table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Comment</th>
                            <th>Lead Status</th>
                            {{--<th>Lead Sub Status</th>--}}
                            <th>Rating</th>
                            <th>Followup Date</th>
                            <th>Updated Date</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enquiry->EnquiryInteraction as $interaction)
                        <tr>                            
                            <td>{!!nl2br($interaction->comment)??''!!}</td>
                            <td>{{CustomHelper::showEnquiryMaster($interaction->lead_status??'')}}</td>
                            {{--<td>{{CustomHelper::showEnquiryMaster($interaction->lead_sub_status??'')}}</td>--}}

                            <td style="color: {{$interaction->GetColor->color_code ?? ''}};" >{{CustomHelper::showEnquiryMaster($interaction->rating??'')}}</td>
                            <td>
                                @if($interaction->followup_date)
                                <i class="fa fa-calendar"></i> {{CustomHelper::DateFormat($interaction->followup_date,'d/m/Y','Y-m-d')}}
                                @endif
                            </td>
                            <td>
                                @if($interaction->created_at)
                                <i class="fa fa-calendar"></i> {{CustomHelper::DateFormat($interaction->created_at,'d/m/Y','Y-m-d')}}
                                @endif
                            </td>
                            <td>
                                <i class="fa fa-user-o"></i>
                                <span id="name">
                                    @if($interaction->created_type == 'backend')
                                    {{CustomHelper::showAdmin($interaction->created_by??'')}}
                                    @else
                                    {{ucfirst($interaction->created_type)}}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
