<!DOCTYPE html>
<html>
<head>
	<title></title>
    <style>
        .ui-widget.ui-widget-content{z-index: 99999!important;}
    </style>
</head>
<body>
<?php
$routeName = CustomHelper::getAdminRouteName();

/*$type = (isset($record->type))?$record->type:'';
$type = old('type',$type);*/
$enquiry_for = [];
if(isset($record->EnquiryForType)) {
    $enquiry_for = $record->EnquiryForType->pluck('enquiry_for_id')->toArray();
}
$enquiry_for = old('enquiry_for',$enquiry_for);

$name = (isset($record->name))?$record->name:'';
$name = old('name',$name);

$country_code = (isset($record->country_code))?$record->country_code:'';
$country_code = old('country_code',$country_code);

$phone = (isset($record->phone))?$record->phone:'';
$phone = old('phone',$phone);

$no_of_pax = (isset($record->no_of_pax))?$record->no_of_pax:'';
$no_of_pax = old('no_of_pax',$no_of_pax);

$adu_abo_12 = (isset($record->adu_abo_12))?$record->adu_abo_12:'';
$adu_abo_12 = old('adu_abo_12',$adu_abo_12);

$chi_6_12 = (isset($record->chi_6_12))?$record->chi_6_12:'';
$chi_6_12 = old('chi_6_12',$chi_6_12);

$chi_2_5 = (isset($record->chi_2_5))?$record->chi_2_5:'';
$chi_2_5 = old('chi_2_5',$chi_2_5);

$infants_upto_2 = (isset($record->infants_upto_2))?$record->infants_upto_2:'';
$infants_upto_2 = old('infants_upto_2',$infants_upto_2);

$email = (isset($record->email))?$record->email:'';
$email = old('email',$email);


$other_destination = (isset($record->other_destination))?$record->other_destination:'';
$other_destination = old('other_destination',$other_destination);

$other_package = (isset($record->other_package))?$record->other_package:'';
$other_package = old('other_package',$other_package);

$accommodation_comment = (isset($record->accommodation_comment))?$record->accommodation_comment:'';
$accommodation_comment = old('accommodation_comment',$accommodation_comment);

$other_activity = (isset($record->other_activity))?$record->other_activity:'';
$other_activity = old('other_activity',$other_activity);

$content = (isset($record->content))?$record->content:'';
$content = old('content',$content);

$inclusions = (isset($record->inclusions))?$record->inclusions:'';
$inclusions = old('inclusions',$inclusions);

/*$destination = (isset($record->destination))?$record->destination:'';
$destination = old('destination',$destination);

$sub_destination = (isset($record->sub_destination))?$record->sub_destination:'';
$sub_destination = old('sub_destination',$sub_destination);*/

$destination = [];
if(isset($record->Destination)) {
    $destination = $record->Destination->pluck('destination_id')->toArray();
}
$destination = old('destination',$destination);

$sub_destination = [];
if(isset($record->SubDestination)) {
    $sub_destination = $record->SubDestination->pluck('destination_id')->toArray();
}
$sub_destination = old('sub_destination',$sub_destination);

$package_id = (isset($record->package_id))?$record->package_id:'';
$package_id = old('package_id',$package_id);

/*$accommodation = (isset($record->accommodation))?$record->accommodation:'';
$accommodation = old('accommodation',$accommodation);*/
$accommodation = [];
if(isset($record->Accommodation)) {
    $accommodation = $record->Accommodation->pluck('accommodation_id')->toArray();
}
$accommodation = old('accommodation',$accommodation);

$accommodation_type = [];
if(isset($record->AccommodationType)) {
    $accommodation_type = $record->AccommodationType->pluck('accommodation_type_id')->toArray();
}
$accommodation_type = old('accommodation_type',$accommodation_type);

$activity = (isset($record->activity))?$record->activity:'';
$activity = old('activity',$activity);

$lead_source = (isset($record->lead_source))?$record->lead_source:'';
$lead_source = old('lead_source',$lead_source);

$lead_status = (isset($record->lead_status))?$record->lead_status:'';
$lead_status = old('lead_status',$lead_status);

$lead_status_cat = (isset($record->lead_status))?$record->GetCategory->category:'';

// $getLeadStatusCategory = '';
// if(isset($lead_status) && !empty($lead_status)){
//     $getLeadStatusCategory = CustomHelper::getLeadStatusCategory($lead_status);
// }

$lead_sub_status = (isset($record->lead_sub_status))?$record->lead_sub_status:'';
$lead_sub_status = old('lead_sub_status',$lead_sub_status);

$rating = (isset($record->rating))?$record->rating:'';
$rating = old('rating',$rating);

$user_id = (isset($record->cc_id))?$record->cc_id:'';
$user_id = old('cc_id',$user_id);

$followup_date = (isset($record->followup_date))?CustomHelper::DateFormat($record->followup_date, 'd/m/Y', 'Y-m-d'):'';
$followup_date = old('followup_date',$followup_date);

$date_of_travel = (isset($record->date_of_travel))?CustomHelper::DateFormat($record->date_of_travel, 'd/m/Y', 'Y-m-d'):'';
$date_of_travel = old('date_of_travel',$date_of_travel);

$status = (isset($record->status))?$record->status:'';
$status = old('status',$status);

$enq_status = config('custom.enq_status');
$enq_for_types = config('custom.enq_for_types');

$lead_status_category_arr = config('custom.lead_status_category_arr');

$meal_plans = (isset($record->meal_plans)) ? json_decode($record->meal_plans) : 'dd';
?>

<div class="enq-view">
    <h2>{{($id)?'Update':'Add'}} Enquiry</h2>
    <form method="POST" action="" accept-charset="UTF-8" role="form" id="enquiry_form" onSubmit="return validate_enquiry();">
    	{{ csrf_field() }}
    	<input type="hidden" name="message" id="message" value="">
    	<div class="row">
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    				<label class="control-label required">Name:</label>
    				<input type="text" name="name" class="form-control" value="{{$name}}" maxlength="255" autocomplete="none" />
    				@include('snippets.errors_first', ['param' => 'name'])
    			</div>
    		</div>
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
    				<label class="control-label required">Phone:</label>
    				<input type="text" name="phone" class="form-control" value="{{$phone}}" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" autocomplete="none" />
    				@include('snippets.errors_first', ['param' => 'phone'])
    			</div>
    		</div>
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    				<label class="control-label required">Email:</label>
    				<input type="text" name="email" class="form-control" value="{{$email}}" maxlength="255" autocomplete="none" />
    				@include('snippets.errors_first', ['param' => 'email'])
    			</div>
    		</div>
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('enquiry_for') ? ' has-error' : '' }}">
    				<label class="control-label required">Enquiry For:</label>
     				<select name="enquiry_for[]" class="form-control select2 myselect" multiple="multiple">
    					<option value="">Select Enquiry For</option>
    					@foreach($enq_for_types as $k => $v)
    					<option value="{{$k}}" {{(!empty($enquiry_for) && in_array($k,$enquiry_for))?'selected':''}} >{{$v}}</option>
    					@endforeach
    				</select>
                    <input type="hidden" name="enquiry_for_error">
    				@include('snippets.errors_first', ['param' => 'enquiry_for'])
    			</div>
    		</div>
            <div class="clearfix"></div>
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('destination') ? ' has-error' : '' }}">
    				<label class="control-label">Destination:</label>
    				<select name="destination[]" class="form-control select2 myselect" multiple>
    					<option value="">Select Destination</option>
                        @if($destinations)
                        @foreach($destinations as $row)
                        <option value="{{$row->id}}" {{(!empty($destination) && in_array($row->id,$destination))?'selected':''}}>{{$row->name}}</option>
                        @endforeach
                        @endif
    				</select>
    				@include('snippets.errors_first', ['param' => 'destination'])
    			</div>
    		</div>

    		{{--<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('sub_destination') ? ' has-error' : '' }}">
    				<label class="control-label">Sub Destination:</label>
    				<select name="sub_destination[]" class="form-control select2 myselect" multiple>
    					<option value="">Select Sub Destination</option>
                        @if($sub_destinations)
                        @foreach($sub_destinations as $row)
                        <option value="{{$row->id}}" {{(!empty($sub_destination) && in_array($row->id,$sub_destination))?'selected':''}}>{{$row->name}}</option>
                        @endforeach
                        @endif
    				</select>
    				@include('snippets.errors_first', ['param' => 'sub_destination'])
    			</div>
    		</div>--}}
            <div class="col-sm-3">
    			<div class="form-group{{ $errors->has('other_destination') ? ' has-error' : '' }}">
    				<label class="control-label">Other Destination:</label>
    				<input type="text" name="other_destination" class="form-control" value="{{$other_destination}}" maxlength="255" />
    				@include('snippets.errors_first', ['param' => 'other_destination'])
    			</div>
    		</div>
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('package_id') ? ' has-error' : '' }}">
    				<label class="control-label">Package/Activity:</label>
    				<select name="package_id" class="form-control select2">
    					<option value="">Select Package</option>
                        @if($packages)
                        @foreach($packages as $row)
                        <option value="{{$row->id}}" {{($row->id==$package_id)?'selected':''}}>{{$row->title}}</option>
                        @endforeach
                        @endif
    				</select>
    				@include('snippets.errors_first', ['param' => 'package'])
    			</div>
    		</div>
            <div class="col-sm-3">
    			<div class="form-group{{ $errors->has('other_package') ? ' has-error' : '' }}">
    				<label class="control-label">Other Package:</label>
    				<input type="text" name="other_package" class="form-control" value="{{$other_package}}" maxlength="255" />
    				@include('snippets.errors_first', ['param' => 'other_package'])
    			</div>
    		</div>
            <div class="clearfix"></div>
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('accommodation') ? ' has-error' : '' }}">
    				<label class="control-label">Accommodation:</label>
    				<select name="accommodation[]" class="form-control select2 myselect" multiple>
    					<option value="">Select Accommodation</option>
                        @if($accommodations)
                        @foreach($accommodations as $row)
                        <?php $acc_location = $row->accommodationDestination->name??'';?>
                        <option value="{{$row->id}}" {{(!empty($accommodation) && in_array($row->id,$accommodation))?'selected':''}}>{{$row->name}} ({{$acc_location}})</option>
                        @endforeach
                        @endif
    				</select>
    				@include('snippets.errors_first', ['param' => 'accommodation'])
    			</div>
    		</div>    		
            <div class="col-sm-3">
    			<div class="form-group{{ $errors->has('accommodation_type') ? ' has-error' : '' }}">
    				<label class="control-label">Accommodation Type:</label>
    				<select name="accommodation_type[]" class="form-control select2 myselect" multiple>
    					<option value="">Select Accommodation Type</option>
                        @if($accommodation_types)
                        @foreach($accommodation_types as $row)
                        <option value="{{$row->id}}" {{(!empty($accommodation_type) && in_array($row->id,$accommodation_type))?'selected':''}}>{{$row->title}} </option>
                        @endforeach
                        @endif
    				</select>
    				@include('snippets.errors_first', ['param' => 'accommodation_type'])
    			</div>
    		</div>
            
            <div class="col-sm-3">
    			<div class="form-group{{ $errors->has('accommodation_comment') ? ' has-error' : '' }}">
    				<label class="control-label">Accommodation Comment:</label>
    				<input type="text" name="accommodation_comment" class="form-control" value="{{$accommodation_comment}}" maxlength="255" />
    				@include('snippets.errors_first', ['param' => 'accommodation_comment'])
    			</div>
    		</div>
            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('meal_plans') ? ' has-error' : '' }}">
                    <label class="control-label">Meal Plan:</label>
                    <select name="meal_plans[]" class="form-control select2 myselect" multiple>
                        <?php
                         $meal_options = config('custom.meal_options');

                         $selected_all = '';
                        if(!empty($meal_plans) && !empty($meal_plans) && is_array($meal_plans)){ 
                            if(in_array("All",$meal_plans)){
                                $selected_all = 'selected';
                            } }
                            ?>
                            <option value="All" {{$selected_all}} >All</option>
                            <?php
                            foreach ($meal_options as $key => $meal_option){
                                $selected = '';
                                if(!empty($meal_plans) && !empty($meal_plans) && is_array($meal_plans)){
                                if(in_array($key,$meal_plans)){
                                    $selected = 'selected';
                                } }
                            ?>
                            <option value="{{$key}}"{{$selected}}>{{$meal_option}}</option>
                        <?php }?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'meal_plans'])
                </div>
            </div>
            <div class="clearfix"></div>
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('activity') ? ' has-error' : '' }}">
    				<label class="control-label">Activity:</label>
    				<select name="activity" class="form-control">
    					<option value="">Select Activity</option>
    				</select>
    				@include('snippets.errors_first', ['param' => 'activity'])
    			</div>
    		</div>
            <div class="col-sm-3">
    			<div class="form-group{{ $errors->has('other_activity') ? ' has-error' : '' }}">
    				<label class="control-label">Other Activity:</label>
    				<input type="text" name="other_activity" class="form-control" value="{{$other_activity}}" maxlength="255" />
    				@include('snippets.errors_first', ['param' => 'other_activity'])
    			</div>
    		</div>
            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('date_of_travel') ? ' has-error' : '' }}">
                    <label class="control-label">Date of Travel:</label>
                    <input type="text" name="date_of_travel" id="date_of_travel" class="form-control" value="{{$date_of_travel}}" maxlength="255" autocomplete="off" placeholder='Date of Travel' readonly />
                    @include('snippets.errors_first', ['param' => 'date_of_travel'])
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('no_of_pax') ? ' has-error' : '' }}">
                    <label class="control-label">No Of Pax:</label>
                    <input type="text" name="no_of_pax" class="form-control" value="{{$no_of_pax}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    @include('snippets.errors_first', ['param' => 'no_of_pax'])
                </div>
            </div>
             <div class="clearfix"></div>
            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('adu_abo_12') ? ' has-error' : '' }}">
                    <label class="control-label">Adults - Above 12 Yrs:</label>
                    <input type="text" name="adu_abo_12" class="form-control" value="{{$adu_abo_12}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    @include('snippets.errors_first', ['param' => 'adu_abo_12'])
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('chi_6_12') ? ' has-error' : '' }}">
                    <label class="control-label">Children 6-12 Yrs:</label>
                    <input type="text" name="chi_6_12" class="form-control" value="{{$chi_6_12}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    @include('snippets.errors_first', ['param' => 'chi_6_12'])
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('chi_2_5') ? ' has-error' : '' }}">
                    <label class="control-label">Children 2-5 Yrs:</label>
                    <input type="text" name="chi_2_5" class="form-control" value="{{$chi_2_5}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    @include('snippets.errors_first', ['param' => 'chi_2_5'])
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('infants_upto_2') ? ' has-error' : '' }}">
                    <label class="control-label">Infants upto 2 Yrs:</label>
                    <input type="text" name="infants_upto_2" class="form-control" value="{{$infants_upto_2}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    @include('snippets.errors_first', ['param' => 'infants_upto_2'])
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('inclusions') ? ' has-error' : '' }}">
                    <label class="control-label">Inclusions:</label>
                    <textarea name="inclusions" class="form-control" rows="2">{{$inclusions}}</textarea>
                    @include('snippets.errors_first', ['param' => 'inclusions'])
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                    <label class="control-label">Comment:</label>
                    <textarea name="content" class="form-control" rows="2">{{$content}}</textarea>
                    @include('snippets.errors_first', ['param' => 'content'])
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('lead_source') ? ' has-error' : '' }}">
                    <label class="control-label">Lead Source:</label>
                    <select name="lead_source" class="form-control">
                        <option value="">Select Lead Source</option>
                        @if($lead_source_arr)
                        @foreach($lead_source_arr as $row)
                        <option value="{{$row->id}}" {{($row->id==$lead_source)?'selected':''}}>{{$row->name}}</option>
                        @endforeach
                        @endif
                    </select>
                    @include('snippets.errors_first', ['param' => 'lead_source'])
                </div>
            </div>
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('lead_status') ? ' has-error' : '' }}">
    				<label class="control-label">Lead Status:</label>
    				<select name="lead_status" class="form-control">
    					<option value="">Select Lead Status</option>
                        @if($lead_status_arr)
                        @foreach($lead_status_arr as $row)
                        <option value="{{$row->id}}" {{($row->id==$lead_status)?'selected':''}}>{{$row->name}}</option>
                        @endforeach
                        @endif
    				</select>
    				@include('snippets.errors_first', ['param' => 'lead_status'])
    			</div>
    		</div>
    		{{--<div class="col-sm-4">
    			<div class="form-group{{ $errors->has('lead_sub_status') ? ' has-error' : '' }}">
    				<label class="control-label">Lead Sub Status:</label>
    				<select name="lead_sub_status" class="form-control">
    					<option value="">Select Lead Sub Status</option>
    				</select>
    				@include('snippets.errors_first', ['param' => 'lead_sub_status'])
    			</div>
    		</div>--}}
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
    				<label class="control-label">Rating:</label>
    				<select name="rating" class="form-control">
    					<option value="">Select Rating</option>
                        @if($rating_arr)
                        @foreach($rating_arr as $row)
                        <option value="{{$row->id}}" {{($row->id==$rating)?'selected':''}}>{{$row->name}}</option>
                        @endforeach
                        @endif
    				</select>
    				@include('snippets.errors_first', ['param' => 'rating'])
    			</div>
    		</div>
            
    		<div class="col-sm-3">
    			<div class="form-group{{ $errors->has('followup_date') ? ' has-error' : '' }}">
    				<label class="control-label">Followup Date:</label>
    				<input type="text" name="followup_date" id="followup_date" class="form-control" value="{{$followup_date}}" maxlength="255" autocomplete="off" placeholder="Followup Date" readonly />
    				@include('snippets.errors_first', ['param' => 'followup_date'])
    			</div>
    		</div>
            <div class="clearfix"></div>
            {{--<div class="col-sm-4">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    <label class="control-label">Status:</label>
                    <select name="status" class="form-control select2">
                        <option value="">Select Status</option>
                        @foreach($enq_status as $k => $v)
                        <option value="{{$k}}" {{($k==$status)?'selected':''}} >{{$v}}</option>
                        @endforeach
                    </select>
                    @include('snippets.errors_first', ['param' => 'status'])
                </div>
            </div>--}}
            
            {{--<div class="col-sm-4">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    <label class="control-label">Status:</label>
                    <select name="status" class="form-control select2" disabled>
                        <option value="">Select Status</option>
                        @foreach($lead_status_category_arr as $k => $v)
                        <option value="{{$k}}" {{($k==$lead_status_cat)?'selected':''}} >{{$v}}</option>
                        @endforeach
                    </select>
                    @include('snippets.errors_first', ['param' => 'status'])
                </div>
            </div>--}}

            <div class="clearfix"></div>
            <div class="col-sm-12">
    			<div class="form-group{{ $errors->has('cc_id') ? ' has-error' : '' }}">
    				<label class="control-label">Assign:</label>
    				<select name="cc_id" class="form-control">
    					<option value="">Select Admin</option>
                        @if($users)
                        @foreach($users as $user)
                        <option value="{{$user->id}}" {{($user->id==$user_id)?'selected':''}}> @if(isset($user->name)){{$user->name}} &nbsp;&#8594;&nbsp; @endif @if(isset($user->phone)){{$user->phone}} &nbsp;&#8594;&nbsp; @endif @if(isset($user->email)){{$user->email}}@endif</option>
                        @endforeach
                        @endif
    				</select>
    				@include('snippets.errors_first', ['param' => 'cc_id'])
    			</div>
    		</div>

    		<div class="col-sm-12">
    			<div class="form-group">
    				<input type="hidden" id="id" class="form-control" name="id" value="{{old('id', $id)}}" />
    				<button type="submit" id="sbmtBtn" class="btn btn-success"><i class="fa fa-save"></i>Submit</button>
    			</div>
    		</div>
    	</div>
    </form>

<script type="text/javascript">
      $('.enq-view').ready(function() {
        $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        })

      });


    $( function() {
        $("#followup_date").datepicker({
            'dateFormat':'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            
        });

        $("#date_of_travel").datepicker({
            'dateFormat':'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
           
        });
    });

	function validate_enquiry() {
		$(".validation_error").remove();
        $(".flash-message").remove();
		var status = false;
        $('#sbmtBtn').html('Please Wait...');
        $("#sbmtBtn"). attr("disabled", true);
		$.ajax({
			'url' : '<?php echo route($routeName.'.enquiries.add'); ?>', 
			'type' : 'post',
			'dataType' : 'json',
			'data' : $('#enquiry_form').serialize(),
			'cache' : false,
			'async' : false,
			beforeSend: function(){
				$('#enquiry_form input').css('border','1px solid #ccc');
				$('#enquiry_form select').css('border','1px solid #ccc');
			},
			success: function(response){
                $('#sbmtBtn').html('Submit');
                $("#sbmtBtn"). attr("disabled", false);
				if(response.success) {
                    $('#enquiry_form').html('<div class="flash-message"><div class="alert alert-success"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.msg+'</div></div>');
				} else {
					if(response) {
						parseErrorMessages(response);
					}
				}
			},
			error: function(e) {
                $('#sbmtBtn').html('Submit');
                $("#sbmtBtn"). attr("disabled", false);
				var response = e.responseJSON;
				if(response) {
					parseErrorMessages(response);
				}
			}
		});
		return status;
	}

    function parseErrorMessages(response) {
        if(response) {
            if(response.msg) {
                $('#enquiry_form #message').after('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>'+response.msg+'</div></div>');
            } else {
               $('#enquiry_form #message').after('<div class="flash-message"><div class="alert alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>Invalid request, please check errors below!</div></div>');
            }
        	if(response.errors) {
                $.each(response.errors, function(i, item) {
                    if(i=='enquiry_for') {
                        $("#enquiry_form input[name='enquiry_for_error']").css('border','1px solid #ff0000');
                        $("#enquiry_form input[name='enquiry_for_error']").after('<span class="validation_error">'+item+'</span>');
                    } else {
                        $("#enquiry_form select[name='"+i+"']").css('border','1px solid #ff0000');
                        $("#enquiry_form select[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');

                		$("#enquiry_form input[name='"+i+"']").css('border','1px solid #ff0000');
                		$("#enquiry_form input[name='"+i+"']").after('<span class="validation_error">'+item+'</span>');
                    }
            	});
            }
        }
    }

    $("select[name='destination[]']").on("change",function(){
        var destination_id = $(this).val();
        ajax_destinations(destination_id);
    });

    <?php if($destination) { ?>
    //ajax_destinations(JSON.parse('<?php //echo json_encode($destination); ?>'),JSON.parse('<?php //echo json_encode($sub_destination); ?>'));
    <?php } ?>

    function ajax_destinations(parent_ids, selected_ids) {
        var _token = '{{ csrf_token() }}';
        $.ajax({
            'url' : '<?php echo route($routeName.'.destinations.ajax_destinations'); ?>', 
            'type' : 'post',
            'dataType' : 'json',
            'data' : {parent_id:parent_ids},
            'cache' : false,
            'async' : false,
            'headers' : {'X-CSRF-TOKEN': _token},
            beforeSend: function() {
                $("select[name='sub_destination[]']").empty();
                $("select[name='sub_destination[]']").append('<option value="">Select Sub Destination</option>');
            },
            success: function(response) {
                if(response.success) {
                    if(response.destinations) {
                        $.each(response.destinations, function(index, destination) {
                            $("select[name='sub_destination[]']").append('<option value="'+destination.id+'">'+destination.name+'</option>');
                        });
                        if(selected_ids) {
                            $("select[name='sub_destination[]']").val(selected_ids);
                            /*$.each(selected_ids, function(index, selected_id) {
                                console.log(selected_id);
                                $("select[name='sub_destination[]']").val(selected_id);
                            });*/
                        }
                    }
                } else {
                    if(response.msg) {
                        alert(response.msg);
                    }
                }
            },
        });
        return status;
    }

    $("select[name='lead_status']").on("change",function(){
        var lead_status = $(this).val();
        ajax_enquiries_master(lead_status);
    });

    <?php if($lead_status) { ?>
    ajax_enquiries_master('<?php echo $lead_status; ?>','<?php echo $lead_sub_status; ?>');
    <?php } ?>

    function ajax_enquiries_master(parent_id, selected_id, field_name="lead_sub_status", field_label="Lead Sub Status") {
        var _token = '{{ csrf_token() }}';
        $.ajax({
            'url' : '<?php echo route($routeName.'.enquiries_master.ajax_enquiries_master'); ?>', 
            'type' : 'post',
            'dataType' : 'json',
            'data' : {parent_id:parent_id},
            'cache' : false,
            'async' : false,
            'headers' : {'X-CSRF-TOKEN': _token},
            beforeSend: function() {
                $("select[name='"+field_name+"']").empty();
                $("select[name='"+field_name+"']").append('<option value="">Select '+field_label+'</option>');
            },
            success: function(response) {
                if(response.success) {
                    if(response.enquiries_master) {
                        $.each(response.enquiries_master, function(index, row) {
                            $("select[name='"+field_name+"']").append('<option value="'+row.id+'">'+row.name+'</option>');
                        });
                        if(selected_id) {
                            $("select[name='"+field_name+"']").val(selected_id);
                        }
                    }
                } else {
                    if(response.msg) {
                        alert(response.msg);
                    }
                }
            },
        });
        return status;
    }

// ============== Select Box Start ============

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

// ============== Select Box End ============
</script>

</body>
</html>