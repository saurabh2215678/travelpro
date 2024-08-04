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
<?php $tripTimeArr = config('custom.tripTimeArr'); ?>
</style>
<div class="slot_section">
<h2>{{$package->title ?? ''}}</h2>
<p>Time slots for "{{$package_price->title ?? ''}}"</p>
<form onSubmit="return validateSlot()" id="slot_form" role="form">
	<div class="ajax_msg"></div>
	<table class="table table-striped table-bordered table-hover" >
		<tr>
			<td>
				<label>Duration</label>
				<input type="text" name="duration" value="{{$record->duration ?? ''}}" class="form-control admin_input1">
			</td>
			<td>
				<label>Duration Type</label>
				<select name="duration_type" class="form-control admin_input1" id="duration_type" onchange="onChangeDuration(this.value)">
					<option value="hour"<?php if($duration_type == 'hour'){ echo 'selected'; } ?>>Hour</option>
					<option value="day"<?php if($duration_type == 'day'){ echo 'selected'; } ?>>Day</option>
					<option value="minute"<?php if($duration_type == 'minute'){ echo 'selected'; } ?>>Minutes</option>
				</select>
			</td>
		</tr>
	</table>
	<div id="frm_slot" class="start_time">
		@include('admin.packages._packageprice_slot_hours')				
	</div>
	<input type="submit" name="" class="btn_admin2 location_form_submit save_submit" value="Submit">
	<input type="button" name=""  data-fancybox-close="" class="btn_admin2 location_form_submit " value="Cancel">
</form>
</div>
<script type="text/javascript">
	function validateSlot() {
		var data = [];
		var _token = '{{ csrf_token() }}';
		$.ajax({
			url: "{{ url($routeName.'/packages/'.$package_id.'/packageprice_slot/'.$price_id) }}",
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
	}

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
		<?php foreach ($tripTimeArr as $key => $tripTime) { ?>
			select += '<option value="{{$key}}">{{$tripTime}}</option>'
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
					url: '{{ url($routeName.'/packages/'.$package_id.'/packageprice_slot_delete/'.$price_id) }}',
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
</script>