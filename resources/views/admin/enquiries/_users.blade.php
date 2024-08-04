<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$enquiry_id = $enquiry->id??'';
$cc_id = $enquiry->cc_id??'';
?>
<input type="text" placeholder="Search User" class="form-control admin_input1 search_assign">
@if(CustomHelper::checkPermission('enquiries','assign'))
<ul>
	@if($users)
	@foreach($users as $user)
	@if($user->id==$cc_id)
	<li {{($user->id==$cc_id)?'class="active"':''}}>
	<a href="{{route($routeName.'.enquiries.remove_assign',[$enquiry_id,$user->id, 'back_url'=>$BackUrl])}}" onClick="return confirm('Are you sure to remove the assignee {{$user->name}}?')" ><i class="fa fa-user-o"></i> {{$user->name}} <i class="fa fa-phone"></i> {{$user->phone}} <i class="fa fa-envelope"></i> {{$user->email}} 
	@if(CustomHelper::checkPermission('enquiries','remove_assign'))
	<i class="fa fa-trash" style="color:#c70000;"></i>
	@endif 
	</a>
	</li>
	@else
	<li {{($user->id==$cc_id)?'class="active"':''}}>
	<a href="{{route($routeName.'.enquiries.assign',[$enquiry_id,$user->id, 'back_url'=>$BackUrl])}}" onClick="return confirm('Are you sure to assign the enquiry to {{$user->name}}?')" ><i class="fa fa-user-o"></i> {{$user->name}} <i class="fa fa-phone"></i> {{$user->phone}}<i class="fa fa-envelope"></i> 
	{{$user->email}}</a>
	</li>
	@endif
	@endforeach
	@endif
</ul>
@endif