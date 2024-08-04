<?php 
  $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>
<div class="toptab"> 
      <ul class="nav nav-tabs">  
        <li class="nav-item"> 
          <a <?php if($active_menu=='category'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.accommodations_categories') }}" title="Accommodation Categories">Accommodation Categories</a> 
        </li>
         <li class="nav-item"> 
          <a <?php if($active_menu=='accoommodation_type'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.accoomodation_types') }}" title="Accommodation Type">Accommodation Type</a> 
        </li> 
        
         <li class="nav-item"> 
          <a <?php if($active_menu=='features'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.accommodations_features') }}" title="Accommodation Features">Accommodation Features & Amenities</a> 
        </li>
        <li class="nav-item"> 
          <a <?php if($active_menu=='facility'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.accommodations_facilities') }}" title="Accommodation Facilities">Accommodation Facilities</a> 
        </li> 
        <li class="nav-item"> 
          <a <?php if($active_menu=='room_type'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.room_types') }}" title="Room Types">Room Types</a> 
        </li> 
        <li class="nav-item"> 
          <a <?php if($active_menu=='room_feature'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.room_features') }}" title="Room Features">Room Features & Amenities</a> 
        </li> 
        <li class="nav-item"> 
          <a <?php if($active_menu=='plan_includes'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.room_plan_includes') }}" title="Rate Plan Inclusion">Rate Plan Inclusion</a> 
        </li>
        <li class="nav-item"> 
          <a <?php if($active_menu=='room_view'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.room_master.view',['room_view']) }}" title="Room View">Room View</a> 
        </li>
        <li class="nav-item"> 
          <a <?php if($active_menu=='bed_type'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.room_master.view',['bed_type']) }}" title="Room Bed Type">Room Bed Type</a> 
        </li>
        <li class="nav-item"> 
          <a <?php if($active_menu=='extra_bed_type'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.accommodations.room_master.view',['extra_bed_type']) }}" title="Room Extra Bed Type">Room Extra Bed Type</a> 
        </li>

      </ul> 
</div>