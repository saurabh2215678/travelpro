<?php 
   $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>

   
      <div class="toptab"> 
            <ul class="nav nav-tabs"> 

              <li class="nav-item"> 
                <a  <?php if($active_menu=='enquiries_master'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master.index') }}" title="Enquiries Master">Enquiries Master</a> 
              </li> 
              
              <li class="nav-item">   
                <a <?php if($active_menu=='enquiries_master_group'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master_group.index') }}" title="Enquiries Master Group">Enquiries Master Group</a>  
              </li>
            </ul> 
      </div>

