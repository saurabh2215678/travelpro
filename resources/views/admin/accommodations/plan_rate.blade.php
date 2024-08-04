<?php
$routeName = CustomHelper::getAdminRouteName();
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
<div class="slot_section">
	<h2>{{$page_heading}} </h2>
	@include('snippets.errors')
	@include('snippets.flash')
</div>
<form method="post" id="plan_rate_form" role="form" onSubmit="return validatePlanRate(event)">
	{{csrf_field()}}
	<div class="ajax_msg" style="display: none;"></div>
	<table class="popup_slot_section .table table table-striped table-bordered table-hover">
		<tr>
			<th width="30%">Rate Plan</th>
			<th width="15%">Base Occupancy</th>
			<th width="15%">Single Occupancy</th>
			<th width="10%">Extra Adult</th>
			<th width="15%">Extra Child (0-6)</th>
			<th width="15%">Extra Child (7-12)</th>
		</tr>
		<?php if(!empty($room_plans)) { ?>
		<?php foreach($room_plans as $plan){ ?>
			<tr>
				<td>
					{{$plan->plan_name??''}}
					<input type="hidden" name="room_plans[]" value="{{$plan->id}}">
				</td>
				<td>
					<input type="text" name="price[{{$plan->id}}]" class="form-control" value="{{($plan->price > 0)?(int)$plan->price:''}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
				</td>
				<td>
					<input type="text" name="single_price[{{$plan->id}}]" class="form-control" value="{{($plan->single_price > 0)?(int)$plan->single_price:''}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
				</td>
				<td>
					<input type="text" name="extra_adult[{{$plan->id}}]" class="form-control" value="{{($plan->extra_adult > 0)?(int)$plan->extra_adult:''}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
				</td>
				<td>
					<input type="text" name="extra_child_1[{{$plan->id}}]" class="form-control" value="{{($plan->extra_child_1 > 0)?(int)$plan->extra_child_1:''}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
				</td>
				<td>
					<input type="text" name="extra_child_2[{{$plan->id}}]" class="form-control" value="{{($plan->extra_child_2 > 0)?(int)$plan->extra_child_2:''}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="1"></td>
			<td colspan="5">
				<input type="submit" name="" class="btn_admin2 location_form_submit save_submit" value="Submit">
				<!-- <a href="" class="btn_admin2" title="Cancel">Cancel</a> -->
			</td>
		</tr>
		<?php } else { ?>
			<tr>
				<td>
					No plans yet, please add plans to update the rates.
				</td>
			</tr>
		<?php } ?>		
	</table>
</div>
</form>

<script type="text/javascript" src="{{asset('/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" referrerpolicy="no-referrer"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"  />
<script type="text/javascript">
function validatePlanRate(event) {
	event.preventDefault();
	var _token = '{{ csrf_token() }}';
	var formID = 'plan_rate_form';
	$.ajax({
		url : "{{ url($routeName.'/accommodations/'.$accommodation_id.'/rates/'.$room_id) }}",
		type : 'POST',
		data : new FormData($('#'+formID)[0]),
		processData: false,
      	contentType: false,
		dataType : "JSON",
		headers : {'X-CSRF-TOKEN': _token},
		enctype: 'multipart/form-data',
		cache : false,
		beforeSend:function() {
			$(".ajax_msg").html("");
			$(".ajax_msg").show();
		},
		success: function(response) {
			$(".ajax_msg").html(response.message).hide();
			$(".ajax_msg").html(response.message).fadeIn();
			setTimeout(function() { $(".ajax_msg").fadeOut(); }, 3000);
		}
	});
}
</script>