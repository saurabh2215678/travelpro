<?php
//pr(CustomHelper::checkPermission('blogs','add'));
$oldPermission = old('permission');
//$permission_name_arr = [];
if(!empty($modules) && count($modules) > 0){

	foreach ($modules as $module){
		$key = $module->name;
		$oldPermissionData = (isset($oldPermission[$key]))?$oldPermission[$key]:[];

		$currentPermissionData = (isset($permissionData[$key]))?$permissionData[$key]:$oldPermissionData;
		?>
		<div class="col-sm-6">
			<div class="boxs">
				<p><strong>{{$module->display_name}}</strong></p>
				<ul class="lists">
					<?php
					$permission_name_arr = explode(',',$module->permission_names);
					foreach ($permission_name_arr as $perm_name) {
						$checked = '';
						if(in_array($perm_name, $currentPermissionData)){
							$checked = 'checked';
						}

						?>
						<li><label for="{{$perm_name}}_{{$key}}"> <input type="checkbox" class="permission_tag" id="{{$perm_name}}_{{$key}}" name="permission[{{$key}}][]" value="{{$perm_name}}" {{$checked}}> {{ucfirst($perm_name)}}</label></li>
						<?php
					}
					?>

				</ul>
			</div>
		</div>
		<?php
	}
}
?>