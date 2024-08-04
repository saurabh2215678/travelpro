 <?php
	$routeName = CustomHelper::getAdminRouteName();
	$record = $records[0] ?? '';
	$duration_type = $record->duration_type ?? '';
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
.slot_section .table > tbody > tr td, .popup_slot_section tbody tr td {
    font-size: 13px;
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
  .select2-selection__rendered li:nth-child(-n + 2) { display: block !important;  
   }
</style>
<?php 
$meal_types = config('custom.meal_types');
// $plan_includes = config('custom.plan_includes');
?>
<link href="{{asset('/')}}css/bootstrap.min.css" rel="stylesheet" type="text/css" /> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{url('')}}/css/site.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{url('')}}/css/jquery.mCustomScrollbar.css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900" rel="stylesheet" /> 
	 <!-- Include Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php 

$plan_name = $plan_data->plan_name ?? '';
$meal_type_key = $plan_data->meal_type ?? '';
$status = $plan_data->status ??1;
$plan_json = $plan_data->plan_json_data ?? '';

$plan_arr = json_decode($plan_json);
$includes = $plan_arr->includes ?? [];

// pr($plan_arr);
?>
<div class="slot_section">
<h2>{{$page_heading}} </h2>
@include('snippets.errors')
@include('snippets.flash')
<form method="post" id="slot_form" role="form">
	 {{csrf_field()}}
	<div class="ajax_msg"></div>
	<table class="table table-striped table-bordered table-hover" >
		<tr>
			<td>
				<div class="{{ $errors->has('plan_name') ? ' has-error' : '' }}">
				<label class="control-label required">Plan name</label>
				<input type="text" name="plan_name" value="{{$plan_name}}" class="form-control admin_input1">
			</div>
			</td>
			<td>
				<label>Meal Type</label>
				<select name="meal_type" class="form-control admin_input1" id="meal_type">
					<option value="">Select</option>
					<?php foreach($meal_types as $key => $meal_type){ ?>
						<option value="{{$key}}" {{($key==$meal_type_key) ? 'selected' : ''}}>{{$meal_type}}</option>
					<?php } ?>
				</select>
			</td>
			
			<td>
				<label>Plan Includes</label><br>
				<select name="plan_include[]" class="form-control admin_input1 select2 myselect" id="plan_include" multiple='multiple'>
					<option value="">Select</option>
					<?php foreach($plan_includes as $key => $plan_include){ ?>
						<option value="{{$plan_include->id}}" {{ (!empty($includes) && is_array($includes) && in_array($plan_include->id, $includes)) ? 'selected' : ''}}>{{$plan_include->name}}</option>
					<?php } ?>
				</select>
			</td>
			<td>
				 <label class="control-label required">Status:</label>
                    &nbsp;&nbsp; <br>
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?>>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                    @include('snippets.errors_first', ['param' => 'status'])
			</td>
		</tr>
	</table>

	<input type="submit" name="" class="btn_admin2 location_form_submit save_submit" value="Submit">
	<a href="{{ url($routeName.'/accommodations/'.$accommodation_id.'/plan/'.$room_id) }}" class="btn_admin2" title="Cancel">Cancel</a>
</form>
</div>

	<table class="popup_slot_section .table table table-striped table-bordered table-hover">
		<tr>
			<th>Plan name</th>
			<th>Meal Type</th>
			<th>Plan Include</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
		<?php foreach ($records as $row) { ?>
		<tr>
			<td>{{$row->plan_name??''}}</td>
			<td>{{$meal_types[$row->meal_type]??''}}</td>
			<td>
			<?php
			$plan_json = $row->plan_json_data ?? '';
			$plan_arr = json_decode($plan_json);
			$includes = $plan_arr->includes ?? [];

			 foreach($plan_includes as $key => $plan_include){
				if(!empty($includes) && is_array($includes) && in_array($plan_include->id, $includes)){
				 echo $plan_include->name; echo '<br>';
				}
			 } ?>
			</td>

			<td><?php if($row->status ){ echo '<span style="color:green">Active</span>'; }else{ echo '<span style="color:red">Inactive</span>' ;} ?></td>
			<td>
				<a href="{{ url($routeName.'/accommodations/'.$accommodation_id.'/plan/'.$room_id)}}?plan_id={{$row->id}}" class="room_plan_fancy"><i class="fa fa-pencil"></i> Edit</a>
			    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
			    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.accommodations.room_plan_delete', ['id'=>$accommodation_id,'room_id'=>$room_id,'plan_id'=>$row->id]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
				  	{{ csrf_field() }}
			 	</form>
			</td>
		</tr>
		<?php } ?>
		
	</table>

	<script type="text/javascript" src="{{asset('/js/jquery.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('/js/bootstrap.min.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" referrerpolicy="no-referrer"></script>
	<!-- Include Date Range Picker -->
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"  />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
        $(document).on("click", ".sbmtDelForm", function(e){
            e.preventDefault();

            $(this).siblings("form.delForm").submit();
        });
	function validateSlot() {/*
		var data = [];
		var _token = '{{ csrf_token() }}';
		$.ajax({
			url: "{{ url($routeName.'/packages/'.$accommodation_id.'/packageprice_slot/'.$room_id) }}",
			type: "POST",
			data: new FormData($('#slot_form')[0]),
			dataType:"JSON",
			headers:{'X-CSRF-TOKEN': _token},
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			cache: false,
			beforeSend:function(){
				$(".ajax_msg").html("");
			},
			success: function(resp){
				if(resp.success) {
					msg = ' <div class="alert alert-success" role="alert">'+resp.msg+'</div>';
					$("#frm_slot").html(resp.htmlData);
				} else {
					msg = '<div class="alert alert-danger" role="alert">'+resp.msg+'</div>';
				}
				$(".ajax_msg").html(msg);
				setTimeout(function(){
					$(".ajax_msg").html('');
				},2000);
			}
		});
		return false;
	*/}


	

	$( document ).ready(function() {
		var val ="{{$duration_type}}";
		onChangeDuration(val)
	});

	function onChangeDuration(val) {
		if(val == 'day') {
			$('.start_time').hide();
		} else {
			$('.start_time').show();
		}
	}

	var scntDiv = $("#frm_slot");
	var i = $(".p_slot").length + 1;

	function addScnt() {
		var select  = '<select name="start_time['+ i +']" class="form-control"><option value="">Select</option>';
		<?php  foreach ($meal_types as $key => $meal_type) { ?>
			select += '<option value="{{$key}}">{{$meal_type}}</option>'
		<?php } ?>
		select +='</select>';
		$('<div class="row p_slot"><div class="form-group col-md-9">'+select+'</div><a class="col-md-3" onClick="return deleteSlot(this)" href="#"><i class="fa fa-minus-circle" aria-hidden="true"></i> Remove</a></div>').appendTo(scntDiv);
		i++;
		return false;
	}

	function deleteSlot(_this) {
		var _this = $(_this);
		var slot_id = _this.attr('data-id');
		if(slot_id) {
			if(confirm('Do you want to delete this?')) {
				var data = [];
				var _token = '{{ csrf_token() }}';
				$.ajax({
					type: 'POST',
					headers:{'X-CSRF-TOKEN': _token},
					url: '{{ url($routeName.'/packages/'.$accommodation_id.'/packageprice_slot_delete/'.$room_id) }}',
					data: {'slot_id':slot_id},
					dataType: 'json',
					cache: false,
					beforeSend:function(){
						$(".ajax_msg").html("");
					},
					success: function(resp)
					{
						if(resp.success) {
							msg = ' <div class="alert alert-success" role="alert">'+resp.msg+'</div>';
							_this.parent().remove();
						} else {
							msg = '<div class="alert alert-danger" role="alert">'+resp.msg+'</div>';
						}
						$(".ajax_msg").html(msg);
						setTimeout(function(){
							$(".ajax_msg").html('');
						},2000);
					}
				});
			}
		} else {
			_this.parent().remove();
		}
		return false;		
	}

	//=====

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


	$('.myselect').each(function(){
  var counter = $(this).next('.select2-container').find(".select2-selection__choice").length;
  if(counter > 2){
    if($(this).next('.select2-container').find('.counter').length <= 0){
      $(this).next('.select2-container').find('.select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter"> ('+counter+' selected) </div>');
    }else{
      $(this).next('.select2-container').find('.counter').text(`(${counter} selected)`);
    }
  }else{
    $(this).next('.select2-container').find('.counter').remove();
  }
})

	//====================
</script>
