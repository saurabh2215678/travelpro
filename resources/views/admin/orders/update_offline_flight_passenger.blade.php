<!DOCTYPE html>
<html>
<head>
	<title>Update Offline Flight Passenger</title>
	<style>
		*{font-family: 'Barlow', sans-serif;}
		body {padding: 0px 25px;}
		.form-control {height: 30px;box-shadow: none;padding: 6px 12px;	font-size: 12px;width:100% !important;}
		#submit_action { font-weight: 500; background-color: #162e44; border-radius: 5rem; padding: 6px 20px; font-size: 12px; color: #fff; transition: all ease 0.5s; border: 0; line-height:normal;}
		#submit_action:hover {background-color: #00b2a7;}
		h3 {padding-bottom: 10px;font-weight: 500;}
		.btn { margin-top: 15px;}
	</style>
	<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('css/site.css')}}" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$pax_type = (isset($traveller->pax_type))?$traveller->pax_type:'';
$title = (isset($traveller->title))?$traveller->title:'';
$title = old('title',$title);
$first_name = (isset($traveller->first_name))?$traveller->first_name:'';
$first_name = old('first_name',$first_name);
$last_name = (isset($traveller->last_name))?$traveller->last_name:'';
$last_name = old('last_name',$last_name);
?>
<h3>Update Offline Flight Passenger</h3>
<form method="POST" action="" id="update_passenger" class="form-inline" onSubmit="return validateUpdatePassenger()" >
	{{ csrf_field() }}
	<div class="row">
		<div class="ajax_msg"></div>
		<div class="col-sm-3">
			<label for="first_name" class="control-label required">Title:</label>
			<select class="form-control" name="title">
				<option value="">Title</option>
				@if($pax_type=='ADULT')
				<option value="Mr" {{($title=='Mr')?'selected':''}}>Mr</option>
				<option value="Mrs" {{($title=='Mrs')?'selected':''}}>Mrs</option>
				@endif
				<option value="Ms" {{($title=='Ms')?'selected':''}}>Ms</option>
				@if($pax_type!='ADULT')
				<option value="Master" {{($title=='Master')?'selected':''}}>Master</option>
				@endif
			</select>
			<span class="title_error validation_error"></span>
		</div>
		<div class="col-sm-3">
			<label for="first_name" class="control-label required">First Name:</label>
			<input type="text" name="first_name" class="form-control" value="{{$first_name}}" placeholder="First Name" >
			<span class="first_name_error validation_error"></span>
		</div>
		<div class="col-sm-3">
			<label for="last_name" class="control-label required">Last Name:</label>
			<input type="text" name="last_name" class="form-control" value="{{$last_name}}" placeholder="Last Name" >
			<span class="last_name_error validation_error"></span>
		</div>
		<div class="col-sm-3 btn">
			<input type="hidden" name="name" value="{{$name}}">
			<input type="submit" name="submit" id="submit_action" value="Submit">
			<input type="button" name="submit" id="submit_processing" value="Processing..." style="display: none;">
		</div>
	</div>
	<input type="hidden" name="update_status" id="update_status" value="">
</form>
<script type="text/javascript" src="{{asset('/js/jquery.min.js')}}"></script>
<script type="text/javascript">
function validateUpdatePassenger() {
	var formData = $('#update_passenger').serialize();
	var _token = '{{ csrf_token() }}';
	$.ajax({
		url: "{{ route($ADMIN_ROUTE_NAME.'.orders.updateOfflineFlightPassenger', [$order_id,'name'=>$name])}}",
		type: "POST",
		data: formData,
		dataType:"JSON",
		headers:{'X-CSRF-TOKEN': _token},
		cache: false,
		beforeSend: function () {
			$('#submit_action').hide();
			$('#submit_processing').show();
			$('.validation_error').html('');
		},
		success: function(resp){
			if(resp.success) {
				$('#update_status').val('success');
				alert(resp.message);
				parent.jQuery.fancybox.close();
			} else {
				if(resp.errors) {
					$.each(resp.errors, function (key, val) {
						$(document).find("." + key + "_error").html(val[0]);
					});
				} else if(resp.message) {
					alert(resp.message);
				} else {
					alert('Something went wrong, please try again');
				}
			}
			$('#submit_action').show();
			$('#submit_processing').hide();            
		}
	});
	return false;
}
</script>
</body>
</html>