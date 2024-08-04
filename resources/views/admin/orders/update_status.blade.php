 <?php
	// $routeName = CustomHelper::getAdminRouteName();
?>
 <style>
form#frm_slot {
    min-width: 700px;
}

.fancybox-content {
    padding: 25px;
    min-width: 600px;
}

#frm_slot .p_slot:first-child a.col-md-3 {
    padding-top: 28px !important;
}

.slot_section p {
    padding: 0 5px;
    font-size: 16px;
    color: #009688;
}

.slot_section h2 {
    margin-bottom: 0;
    padding-top: 0;
    padding-left: 0;
    padding-bottom: 5px;
}

.slot_section .table>tbody>tr td,
.popup_slot_section tbody tr td {
    font-size: 13px;
}

.counter {
    position: absolute;
    top: -33px;
    right: -3px;
    font-size: 11px;
}

.select2-search--inline {
    background-color: Gainsboro;
    display: none;
}

.select2-selection__rendered {
    display: flex !important;
    overflow: hidden;
    margin-right: 20px;
    margin-bottom: 0;
}

.select2-selection__rendered li {
    display: none !important;
}

.select2-selection__rendered li:nth-child(-n + 2) {
    display: block !important;
}
.OrderSatus{
    display: flex;
    align-content: center;
    width: 100%;
    align-items: center;
}

.OrderSatus input{
    width: 20px;
    margin: 0 10px !important;
}

 </style>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="{{asset('/')}}css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="{{url('')}}/css/site.css" rel="stylesheet" type="text/css" />

 <h2>{{$page_heading}} </h2>
 <div class="slot_section">
     @include('snippets.errors')
     @include('snippets.flash')
     <form method="post" id="slot_form" role="form">
         {{csrf_field()}}
         <div class="ajax_msg"></div>
         <table class="table table-striped table-bordered table-hover">
             <tr>
                 <td colspan="2">
                     <div class="OrderSatus">
                     <label class="control-label required">Order Status</label>
                         @if($order_statuses)
                            @foreach($order_statuses as $order_statuse)
                            <input type="radio" name="orders_status" value="{{$order_statuse->id}}" class="form-control " @if(old('orders_status') == $order_statuse->id) checked @endif > {{$order_statuse->name}}
                            @endforeach
                         @endif
                     </div>
                 </td>
            </tr>
            <tr>
                <td>
                    <label class="control-label">Comment</label>
                    <input type="text" name="comments" value="{{old('comments')}}" class="form-control admin_input1">
				</td>			
             </tr>
         </table>
         <input type="submit" name="" class="btn_admin2 location_form_submit save_submit" value="Submit">
     </form>
 </div>
@if(!empty($order_status_histories) && count($order_status_histories) > 0)
 <table class="popup_slot_section .table table table-striped table-bordered table-hover">
		<tr>
			<th>Comment</th>
			<th width="160">Order Status</th>
			<th width="150">Date</th>
			<th width="120">Added By</th>
		</tr>
		<?php foreach ($order_status_histories as $row) { ?>
		<tr>
			<td>{{$row->comments ?? ''}}</td>
			<td>{{isset($row->GetCategory->name) ? $row->GetCategory->name : ''}}</td>
			<td>{{CustomHelper::DateFormat($row->created_at, 'd-m-Y h:i A')}}</td>
            <td>
            <i class="fa fa-user-o"></i>
            <span id="name">
            @if($row->created_type == 'backend')
            <?php /*{{CustomHelper::showAdmin($row->created_by??'')}}*/ ?>
            {{ucfirst($row->created_by_name)}}
            @else
            {{ucfirst($row->created_type)}}
            @endif
            </span>
          </td>
		</tr>
		<?php } ?>
</table>
@endif