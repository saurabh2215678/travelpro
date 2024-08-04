<?php 
$tripTimeArr = config('custom.tripTimeArr');

if($records && $records->count() > 0){
	foreach ($records as $key => $row) { ?>
		<div class="row slot-form p_slot">
			<div class="form-group col-md-9">
				<?php if($key == 0){ ?>
					<label>Start Time</label>
				<?php } ?>
				<input type="hidden" name="slot_ids[]" value="{{$row->id}}" class="form-control admin_input1">
				<select name="start_time[{{$row->id}}]" class="form-control">
					<option value="">Select</option>
					<?php foreach ($tripTimeArr as $timekey => $tripTime) { ?>
						<option value="{{$timekey}}" <?php if($row->start_time == $timekey){ echo 'selected'; } ?>>{{$tripTime}}</option>
					<?php } ?>
				</select>
			</div>
			<?php if($key == 0){ ?>
				<a class="col-md-3" href="#" onClick="return addScnt()" style="padding-top: 8px;"><i class="fa fa-plus"></i> Add</a>
			<?php }else{ ?>
				<a class="col-md-3" onClick="return deleteSlot(this)" href="#" data-id="{{$row->id}}" style="padding-top: 8px;"><i class="fa fa-minus-circle" aria-hidden="true"></i> Remove</a>
			<?php } ?>
		</div>
	<?php }
}else{ ?>
	<div class="row slot-form p_slot">
		<div class="form-group col-md-9">
			<label>Start Time</label>
			<input type="hidden" name="slot_ids[]" value="" class="form-control admin_input1">
			<!-- <input type="text" name="start_time[0]" value="" class="form-control admin_input1"> -->
			<select name="start_time[0]" class="form-control">
				<option value="">Select</option>
				<?php foreach ($tripTimeArr as $timekey => $tripTime) { ?>
					<option value="{{$timekey}}">{{$tripTime}}</option>
				<?php } ?>
			</select>
		</div>
		<a class="col-md-3" href="#" onClick="return addScnt()"><i class="fa fa-plus"></i> Add</a>
	</div>
	<?php } ?>