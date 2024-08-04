<?php 
   $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>

   
      <div class="toptab"> 
            <ul class="nav nav-tabs"> 

              <li class="nav-item"> 
                <a  <?php if($active_menu=='destinations.types'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.destinations.destination_types') }}" title="Destination Types">Destination Types</a> 
              </li> 

              <li class="nav-item"> 
                <a  <?php if($active_menu=='destinations.flags'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.destinations.flags') }}" title="Destination Flags">Destination Flags</a> 
              </li> 

            </ul> 
      </div>

