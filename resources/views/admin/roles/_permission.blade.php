<?php

$oldPermission = old('permission');
// $module_arr = [];
// if(!empty($modules))
// {
// 	foreach ($modules as $key => $module) 
// 	{
// 		$key = $module->name;
// 		$module_arr[] = $key;
// 	}

// 	if(in_array('all_masters',$module_arr))
// 	{
// 		$permission_name_arr = ['view'];
// 	}
// 	else
// 	{
// 		$permission_name_arr = ['view', 'add', 'edit', 'delete','changeStatus'];
// 	}
// }
// else
// {

// }

// $permission_name_arr = ['view', 'add', 'edit', 'delete','changeStatus'];

?>
<?php /*
<div class="col-sm-12">
	<p><strong>Manage Dashboard</strong></p> 
	<div class="boxs">
		<ul class="lists">

			<?php 
			$dashboard_select = "";

			$currentPermissionData_dashboard = (isset($permissionData['dashboard']))?$permissionData['dashboard']:[];
			$checked = '';
			if(in_array('KeyStatistics', $currentPermissionData_dashboard))
			{
				$checked = 'checked';	
			}
			?>
			<li><label for=""><input type="checkbox" class="permission_tag" id="" name="permission[dashboard][]" value="KeyStatistics" {{$checked}}></label> Key Statistics</li>	
		</ul>	
	</div>
</div>*/ ?>
<?php
if(!empty($modules) && count($modules) > 0){

	foreach ($modules as $module){
	$key = $module->name;
	//pr($key);
	$oldPermissionData = (isset($oldPermission[$key]))?$oldPermission[$key]:[];

	$currentPermissionData = (isset($permissionData[$key]))?$permissionData[$key]:$oldPermissionData;
	?>
	<div class="col-sm-6">
		<div class="boxs">
			<p><strong>{{$module->display_name}}</strong></p>
			<ul class="lists">
				<?php
				$permission_name_arr = explode(',', $module->permission_names);
				if(!empty($permission_name_arr)){
					foreach ($permission_name_arr as $perm_name) {
						$checked = '';
						if(in_array($perm_name, $currentPermissionData)){
							$checked = 'checked';
						}
						?>
						<li><label for="{{$perm_name}}_{{$key}}"> <input type="checkbox" class="permission_tag" id="{{$perm_name}}_{{$key}}" name="permission[{{$key}}][]" value="{{$perm_name}}" {{$checked}}> {{ucfirst($perm_name)}}</label></li>
						<?php
					} }
					?>

				</ul>
			</div>
		</div>
		<?php
	}
}
?>