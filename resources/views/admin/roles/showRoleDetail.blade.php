<?php
$userPermission = (isset($role->permissions))?$role->permissions:'';
$permissionData = '';
if(!empty($userPermission)){
    $permissionData = json_decode($role->permissions,true);
}
?>
<div class="enq-view view-role-permissions">
    <h2>View Role Permissions</h2>
    <div class="row">
       <?php
       $permission_params = [];
       $permission_params['modules'] = $modules;
       $permission_params['permissionData'] = $permissionData;
       ?>
      @include('admin.roles._permission', $permission_params)
   </div>

<script>
    $('.enq-view input').each(function(){
        $(this).attr("disabled", true);
    })
</script>