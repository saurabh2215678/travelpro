<?php 
   $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    $module = "Package";
    if($segment == 'activity'){
      $module = "Activity";
    }
?>
      <div class="toptab"> 
            <ul class="nav nav-tabs">
              @if($segment == 'packages')
              <li class="nav-item"> 
                <a  <?php if($active_menu=='packages.types'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.'.$segment.'.type_index') }}" title="{{$module}} Types">{{$module}} Types</a> 
              </li> 
              @endif
               <li class="nav-item"> 
                <a  <?php if($active_menu=='packages.theme'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.'.$segment.'.theme_index') }}" title="{{$module}} Theme Categories">{{$module}} Theme Categories</a> 
              </li> 
              <li class="nav-item"> 
                <a  <?php if($active_menu=='packages.lists'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.'.$segment.'.package_inclusion_lists') }}" title="{{$module}} Inclusion List">{{$module}} Inclusion List</a>
              </li> 
             <?php /* <li class="nav-item"> 
                <a  <?php if($active_menu=='packages.services'){echo 'class="active"' ;} href="{{ route($ADMIN_ROUTE_NAME.'.packages.serviceLevel') }}" title="Package Service">Package Service</a> 
              </li> */ ?>
              @if($segment == 'packages')
                <li class="nav-item"> 
                <a  <?php if($active_menu=='packages.airlines'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.'.$segment.'.package_airlines') }}" title="{{$module}} Airlines">{{$module}} Airlines</a> 
              </li>
              <?php /*<li class="nav-item"> 
                <a  <?php if($active_menu=='packages.settings'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.'.$segment.'.settings') }}" title="{{$module}} Settings">{{$module}} Settings</a> 
              </li> */ ?>
              <li class="nav-item"> 
                <a  <?php if($active_menu=='packages.package-category'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.'.$segment.'.package-category') }}" title="{{$module}} Image Category">{{$module}} Image Category</a> 
              </li> 
              @endif
              <li class="nav-item"> 
                <a  <?php if($active_menu=='packages.flags'){echo 'class="active"' ;}?> href="{{ route($ADMIN_ROUTE_NAME.'.'.$segment.'.flags') }}" title="{{$module}} Flags">{{$module}} Flags</a> 
              </li> 
            </ul> 
      </div>

