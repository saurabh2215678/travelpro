@component('admin.layouts.main')

@slot('title')
Admin - Manage Order Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<style>
    td.iconlist a i {
        font-size: 17px !important;
    }
    td.iconlist a {
        display: inline-block;
        /* margin-bottom: 10px; */
    }
    .booked i {
        color: #00b2a7 !important;
    }
    td.iconlist img {
        filter: opacity(0.7);
    }
    td.iconlist a.booked img {
        filter: opacity(1);
    }
    .centersec {
        min-height: auto;
    }
    .counter{
        position: absolute;
        top: -33px;
        right: -3px;
        font-size: 11px;
    }
    .select2-search--inline{
        background-color:Gainsboro;
        display:none;
    }
    .select2-selection__rendered {
        display: flex !important;
        overflow: hidden;
        margin-right: 20px;
        margin-bottom: 0;
    }
    .select2-selection__rendered li { display: none !important;}
    .select2-selection__rendered li:nth-child(-n + 2) { display: block !important; }
    span.select2.select2-container.select2-container--default {z-index: 99;}
    .centersec.fancybox-content {width: 900px;height: 450px;padding: 20px;}
    a#cust_btn {position: relative;top: 40px;right: 25px;}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="centersec">
    <?php
    $routeName = CustomHelper::getAdminRouteName();
    $order_id = (isset($order->id))?$order->id:0;
    $user_id = (isset($order->user_id))?$order->user_id:'';
    $package_id = (isset($order->package_id))?$order->package_id:'';
    $order_no = (isset($order->order_no))?$order->order_no:'';
    $name = (isset($order->name))?$order->name:'';
    $phone = (isset($order->phone))?$order->phone:'';
    $email = (isset($order->email))?$order->email:'';
    $country = (isset($order->country))?$order->country:'';
    $comment = (isset($order->comment))?$order->comment:'';
    // $payment_description = (isset($order->payment_description))?$order->payment_description:'';
    // $payment_response = (isset($order->payment_response))?$order->payment_response:'';
    $service_level = (isset($order->service_level))?$order->service_level:'';
    $sub_total_amount = (isset($order->sub_total_amount))?$order->sub_total_amount:'';
    $total_amount = (isset($order->total_amount))?$order->total_amount:'';
    // $created_at = (isset($order->created_at))?$order->created_at:'';
    // $created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');
    $back = (request()->has('back'))?request()->back:'';
    $show_cancel = $show_cancel??0;
    ?>
        <div class="bgcolor viewsec flight_service-vaucher scroll_view">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="alert_msg"></div>
            <div class="table-responsive flight_inner_view">

                <div class="form-title">
                    <div class="toplist">
                        <div class="titleid col-form-label font-weight-bold"><h4>Order Details (Order ID: #{{$order->order_no??''}})</h4></div>
                        <?php if(CustomHelper::checkPermission('orders','edit')){ ?>
                        <div class="titleid font-weight-bold">
                            <?php
                            $statusId = 0; 
                            $text = ""; 
                            $style = "display:none;"; 
                            $curr_status = '';
                            if($order->payment_status == 1) {
                                $curr_status = CustomHelper::showEnquiryMaster($order->orders_status) ?? '';
                                $newId = CustomHelper::getOrderStatus("New");
                                $processingId = CustomHelper::getOrderStatus("Processing");
                                $voucherCreatedId = CustomHelper::getOrderStatus("Voucher created");
                                $voucherSentId = CustomHelper::getOrderStatus("Voucher Sent");
                                if($curr_status == "New" || $order->orders_status == $newId){
                                    $statusId = $processingId??0;
                                    $text = "Accept";
                                    $style = "";
                                }else if($curr_status == "Processing" || $order->orders_status == $processingId){
                                    $statusId = $voucherCreatedId??0;
                                    $text = "Create Voucher";
                                    $style = "";
                                }else if(($curr_status == "Voucher created" || $order->orders_status == $voucherCreatedId) || ($curr_status == "Voucher Sent" || $order->orders_status == $voucherSentId)){
                                    $statusId = $voucherSentId??0;
                                    $text = "Send Voucher";
                                    $style = "";
                                }

                                if($order->cancel_request == 1){ ?>
                                    <div class="" style="margin-top: 10px;">
                                        <button type="submit" class="btn btn-success accept_cancel_request" id="accept_cancel_request" data-order_id="{{$order_id}}">Accept</button>
                                        <button type="submit" class="btn_admin2 reject_cancel_request" id="reject_cancel_request" data-order_id="{{$order_id}}">Reject</button>
                                    </div>
                                <?php } else if($text) { ?>
                                    <div class="text-right inl_btn" style="margin-top: 10px;">
                                        
                                        <button style="{{$style}}" type="submit" class="btn btn-success accept_btn" id="accept_btn" data-order_id="{{$order_id}}" data-orders_status="{{$statusId}}">{{$text}}</button>
                                        <?php if($show_cancel == 1){ ?>
                                            <button type="submit" class="btn_admin2 cancel_btn" id="cust_btn" data-order_id="{{$order_id}}">Cancel</button>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="order_status_form" style="display:none">
                            <form class="form-inline" id="acceptForm">
                                <div class="modal-title alert_msg"></div>
                                <input type="hidden" id="order_id" name="order_id" value="{{$order_id}}">
                                <input type="hidden" id="orders_status" name="orders_status" value="{{$statusId}}">
                                <input type="hidden" id="saved_order_status" name="saved_order_status" value="{{$order->orders_status}}">
                                <input type="hidden" id="curr_status" name="curr_status" value="{{$curr_status}}">
                                <input type="hidden" id="order_type" name="order_type" value="{{$order->order_type??0}}">
                                <div class="modal-body">
                                    <label>Description:</label><br>
                                    <textarea name="description" id="description" class="form-control" style="width: 100%;" ></textarea>
                                </div>
                                <div class="text-center p10">
                                    <button type="submit" class="btn btn-success submit_accept btn_admin">Submit</button>
                                    <!-- <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button> -->
                                </div>
                            </form>
                        </div>
                        <div class="order_cancel_form" style="display:none">
                            <form action="<?php echo route('admin.orders.refund') ?>" class="form-inline" method="GET" id="cancelForm">
                                <div class="modal-title alert_msg"></div>
                                <?php if($order->cancel_request == 1){ ?>
                                <input type="hidden" name="action" value="cancel_request_accept">
                                <?php }else{ ?>
                                    <input type="hidden" name="action" value="order_cancel">
                                <?php } ?>
                                <input type="hidden" id="refund_order_id" name="order_id" value="">
                                <div class="modal-body">
                                    <label class="required">Reason for Cancellation:</label><br>
                                    <textarea name="reason" id="reason" class="form-control" style="width: 100%;" ></textarea>
                                </div>
                                <div class="text-center p10">
                                    <button type="submit" class="btn btn-success submit_cancel btn_admin">Submit</button>
                                    <!-- <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button> -->
                                </div>
                            </form>
                        </div>

                        <div class="order_reject_form" style="display:none">
                            <form action="<?php echo route('admin.orders.reject') ?>" 
                                class="form-inline" method="post" id="cancelForm">
                                {{ csrf_field() }}
                                <div class="modal-title alert_msg"></div>
                                <input type="hidden" id="reject_order_id" name="order_id" value="">
                                <div class="modal-body">
                                    <label class="required">Reason for Rejection:</label><br>
                                    <textarea name="reason" id="reject_reason" class="form-control" style="width: 100%;" ></textarea>
                                </div>
                                <div class="text-center p10">
                                    <button type="submit" class="btn btn-success submit_reject btn_admin">Submit</button>
                                    <!-- <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button> -->
                                </div>
                            </form>
                        </div>                            
                    </div>
                    
                </div>

                <div class="form-group flex">
                    @include('admin.orders._order_info')

                    @if(isset($itineraries) && (isset($booking_status)) && $booking_status == 'SUCCESS')
                    <h2>Flight Booking Details</h2>
                    {!!$itineraries!!}
                    @endif 

                    @if(isset($cab_itineraries))
                    <h2>Cab Booking Details</h2>
                    {!!$cab_itineraries!!}
                    @endif
                </div>
                @include('admin.orders._payment_logs')
            </div>
        </div>

        <div class="modal fade" id="acceptModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-inline" id="acceptForm">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title alert_msg"></h3>
                            <input type="hidden" id="order_id" name="order_id" value="{{$order->id}}">
                            <input type="hidden" id="orders_status" name="orders_status" value="{{$statusId}}">
                            <input type="hidden" id="saved_order_status" name="saved_order_status" value="{{$order->orders_status}}">
                            <input type="hidden" id="curr_status" name="curr_status" value="{{$curr_status}}">
                            <input type="hidden" id="order_type" name="order_type" value="{{$order->order_type??0}}">
                        </div>
                        <div class="modal-body">
                            <label>Description:</label><br>
                            <textarea name="description" id="description" class="form-control" style="width: 100%;" ></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success submit_accept btn_admin">Submit</button>
                            <button type="button" class="btn btn-default btn_admin2" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @slot('bottomBlock')
    <style>
        body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-stage .fancybox-content iframe {
            padding: 15px;
        }
        body.fancybox-active .fancybox-container.fancybox-is-open .fancybox-slide--iframe .fancybox-content {
            height: 80% !important;
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
            height : 450px;
            max-width  : 80%;
            max-height : 80%;
            margin: 0;
        }
    </style>

    <link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
    <script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).on("click","#cust_btn",function(){
            var order_id = $(this).attr('data-order_id');
            $('#refund_order_id').val(order_id);
            $(".order_status_form").hide();
            if ($(".order_cancel_form").is(":hidden")) {
                $(".order_cancel_form").show();
            } else {
                $(".order_cancel_form").hide();
            }

    //$("#myModal").modal("toggle");
})

        $("#model_click").click(function() {
            $("#sendMail").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
            $("#sendMail").attr("disabled", true);
        });

        $(document).on("click", ".submit_cancel", function() {
            var reason = $("#reason").val();
            var action = $("#action").val();
            if (!reason) {
                alert("Please add reason for order cancellation.");
                $("#reason").focus();
                return false;
            }
            $(".submit_cancel").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
        });

        $(document).on("click","#accept_cancel_request",function(){
            var order_id = $(this).attr('data-order_id');
            $('#refund_order_id').val(order_id);
            $(".order_status_form").hide();
            $(".order_reject_form").hide();
            if ($(".order_cancel_form").is(":hidden")) {
                $(".order_cancel_form").show();
            } else {
                $(".order_cancel_form").hide();
            }
//$("#myModal").modal("toggle");
})
        $(document).on("click","#reject_cancel_request",function(){
            var order_id = $(this).attr('data-order_id');
            $('#reject_order_id').val(order_id);
            $(".order_status_form").hide();
            $(".order_cancel_form").hide();
            if ($(".order_reject_form").is(":hidden")) {
                $(".order_reject_form").show();
            } else {
                $(".order_reject_form").hide();
            }
//$("#myModal").modal("toggle");
})

        $(document).on("click","#accept_btn",function(){
            var order_id = $(this).attr('data-order_id');
            var orders_status = $(this).attr('data-orders_status');
            var description = $(".order_status_form").find("#description").val();
            var curr_status = "{{CustomHelper::showEnquiryMaster($order->orders_status) ?? ''}}";
            var order_type = $(".order_status_form").find("#order_type").val();
            var saved_order_status = $(".order_status_form").find("#saved_order_status").val();
            var processingId = "{{CustomHelper::getOrderStatus('Processing')}}";
            var voucherCreatedId = "{{CustomHelper::getOrderStatus('Voucher created')}}";
            var voucherSentId = "{{CustomHelper::getOrderStatus('Voucher Sent')}}";

            if((curr_status == "Processing" || saved_order_status == processingId) || (curr_status == "Voucher Sent" || saved_order_status == voucherSentId) || (curr_status == "Voucher created" || saved_order_status == voucherCreatedId)){
                if((curr_status == "Voucher created" || saved_order_status == voucherCreatedId)){
                $(".accept_btn").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
                }
                update_order_status(order_id, description, orders_status, curr_status, order_type, saved_order_status, processingId, voucherCreatedId, voucherSentId);
            } else {
                if ($(".order_status_form").is(":hidden")) {
                    $(".order_status_form").show();
                } else {
                    $(".order_status_form").hide();
                }
            }
        })

        $(document).on("click", ".submit_reject", function() {
            var reason = $("#reject_reason").val();
            if (!reason) {
                alert("Please add reason for order rejectioon.");
                $("#reason").focus();
                return false;
            }
        });

        $(document).on("click", ".submit_accept", function(e) {
            e.preventDefault();
            var order_id = $(".order_status_form").find("input[name='order_id']").val();
            var description = $(".order_status_form").find("#description").val();
            var orders_status = $(".order_status_form").find("#orders_status").val();
            var curr_status = "{{CustomHelper::showEnquiryMaster($order->orders_status) ?? ''}}";
            var order_type = "{{$order->order_type ?? 0}}";
            var saved_order_status = "{{$order->orders_status ?? 0}}";
            var processingId = "{{CustomHelper::getOrderStatus('Processing')}}";
            var voucherCreatedId = "{{CustomHelper::getOrderStatus('Voucher created')}}";
            var voucherSentId = "{{CustomHelper::getOrderStatus('Voucher Sent')}}";
            update_order_status(order_id, description, orders_status, curr_status, order_type, saved_order_status, processingId, voucherCreatedId, voucherSentId);
        });


        function update_order_status(order_id, description, orders_status, curr_status, order_type, saved_order_status, processingId, voucherCreatedId, voucherSentId){
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url('admin/orders/change_status') }}",
                type: "POST",
                data: {
                    order_id: order_id,
                    description: description,
                    orders_status: orders_status
                },
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                cache: false,
                beforeSend: function() {
                    $(".submit_accept").html('Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
                    $(".submit_accept").attr("disabled", true);
                },
                success: function(response) {
// $.fancybox.getInstance().current.opts.$orig.trigger('click');
// $('.centersec').html(response.htmlData);
$(".order_status_form").find(".alert_msg").html(response.message);
setTimeout(function() {
    $(".order_status_form").fadeOut();
}, 4000);
$(".submit_accept").html('Submit');
$(".submit_accept").attr("disabled", false);
$(".order_status_form").find("input[name='order_id']").val('');
$(".order_status_form").find("#description").val('');
$(".order_status_form").find("#orders_status").val('');
if(curr_status == "Processing" || saved_order_status == processingId){
    if(order_type == 4){
        window.open("<?php echo route('admin.voucher.cab')?>/"+ order_id +"?order=pending", "_blank");
    }
    else if(order_type == 1){
        window.open("<?php echo route('admin.voucher.package')?>/"+ order_id +"?order=pending", "_blank");
    }
    else if(order_type == 5){
        window.open("<?php echo route('admin.voucher.hotel')?>/"+ order_id +"?order=pending", "_blank");
    }
    else if(order_type == 6){
        window.open("<?php echo route('admin.voucher.activity')?>/"+ order_id +"?order=pending", "_blank");
    }
    else if(order_type == 8){
        window.open("<?php echo route('admin.voucher.rental')?>/"+ order_id +"?order=pending", "_blank");
    }
}
else if((curr_status == "Voucher Sent" || saved_order_status == voucherSentId) || (curr_status == "Voucher created" || saved_order_status == voucherCreatedId)){
    if(order_type == 4){
        window.location.href = "<?php echo route('admin.voucher.cab_voucher_sendmail')?>/"+ order_id;
    }
    else if(order_type == 1){
        window.location.href = "<?php echo route('admin.voucher.package_voucher_sendmail')?>/"+ order_id;
    }
    else if(order_type == 5){
        window.location.href = "<?php echo route('admin.voucher.hotel_voucher_sendmail')?>/"+ order_id;
    }
    else if(order_type == 6){
        window.location.href = "<?php echo route('admin.voucher.activity_voucher_sendmail')?>/"+ order_id;
    }
    else if(order_type == 8){
        window.location.href = "<?php echo route('admin.voucher.rental_voucher_sendmail')?>/"+ order_id;
    }
}
}
});
        }


        $(document).on('click','.check_booking_status',function(){
            var _this = $(this);
            var order_id = _this.attr('data-id');
            if(order_id) {
                var _token = '{{csrf_token()}}';
                $.ajax({
                    url: "{{url($routeName.'/orders/check_booking_status')}}",
                    type: "POST",
                    data: {order_id},
                    dataType:"JSON",
                    headers:{'X-CSRF-TOKEN': _token},
                    cache: false,
                    beforeSend: function () {

                    },
                    success: function(resp) {
                        if(resp.success) {
                            _this.parent().html('<p><strong>Booking Status: </strong> '+resp.booking_status+'</p>');
                        } else if(resp.message) {
                            alert(resp.message);
                        }
                    }
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.recharge_fancy').fancybox({
                'type': "iframe",
                'width':'500',
                'toolbar'  : false,
                'smallBtn' : true,
                'autosize':false,

                beforeClose: function(){

                }
            });

            $(document).on('click','#admin_notes_edit',function(){
                $('#admin_notes_edit').hide();
                $('#admin_notes_input').show();
                $('#admin_notes_actions').show();
            });

            $(document).on('click','.admin_notes_cancel',function(){
                $('#admin_notes_edit').show();
                $('#admin_notes_input').hide();
                $('#admin_notes_actions').hide();
            });

            $(document).on('click','.admin_notes_save',function(){
                var order_id = '{{$order_id}}';
                var action = 'update_admin_notes';
                var _token = '{{csrf_token()}}';
                var admin_notes = $('#admin_notes').val();
                $.ajax({
                    url: "{{url($routeName.'/orders/updateOrder')}}",
                    type: "POST",
                    data: {order_id,action,admin_notes},
                    dataType:"JSON",
                    headers:{'X-CSRF-TOKEN': _token},
                    cache: false,
                    beforeSend: function () {

                    },
                    success: function(resp) {
                        if(resp.success) {
                            $('#admin_notes_html').html(nl2br(admin_notes));
                            $('#admin_notes_edit').show();
                            $('#admin_notes_input').hide();
                            $('#admin_notes_actions').hide();
                            if(resp.message) {
                                alert(resp.message);
                            }
                        } else if(resp.message) {
                            alert(resp.message);
                        }
                    }
                });
            
            });

            
        });

        function nl2br (str, is_xhtml) {   
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
        }
    </script>

    @endslot
    @endcomponent