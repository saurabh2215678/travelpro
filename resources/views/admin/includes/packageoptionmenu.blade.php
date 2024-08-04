<?php
$routeName = CustomHelper::getAdminRouteName(); 
$back_url = request()->has('back_url') ? request()->input('back_url') : '';
$pa_edit ='packages';
if(session()->has('pa_edit')) {
  $pa_edit = session('pa_edit');
}
?>
<div class="page_btns">
  <?php if(isset($package) && isset($package->is_activity) && $package->is_activity ==1 ){ ?>

    <a <?php if($active_menu=='packages'){echo 'class="active"' ;}?> href="{{route($routeName.'.activity.activity_view', ['id'=>$package_id,'back_url'=>$back_url]) }}" title="Edit Activity"><i class="fas fa-edit"></i>Activity</a>
  <?php }else{ ?>

    <a <?php if($active_menu=='packages'){echo 'class="active"' ;}?> href="{{route($routeName.'.packages.package_view', ['id'=>$package_id,'back_url'=>$back_url]) }}" title="Edit Package"><i class="fas fa-edit"></i>Package</a>
  <?php } ?>

    <a <?php if($active_menu=="packages".$package_id.'/additional-info'){echo 'class="active"' ;}?> href="{{route($routeName.'.'.$pa_edit.'.additional_info', [$package_id]) }}" title="Additional Info"><i class="fas fa-info"></i>Additional Info</a>
    
  <?php if(isset($package->is_activity) && $package->is_activity == 0 ){ ?>
    <a <?php if($active_menu=="packages".$package_id.'/itenaries'){echo 'class="active"' ;}?> href="{{route($routeName.'.'.$pa_edit.'.itenaries', ['package_id'=>$package_id]) }}" title="Itinerary"><i class="fas fa-cocktail"></i>Itinerary</a>

    <a <?php if($active_menu=="packages".$package_id.'/flight'){echo 'class="active"' ;}?> href="{{route($routeName.'.'.$pa_edit.'.flight', ['package_id'=>$package_id]) }}" title="Flight"><i class="fas fa-plane"></i>Flight</a>
  <?php } ?>

<?php if($package->is_activity ==1 ){ ?>
    <a <?php if($active_menu=="packages".$package_id.'/packageprice'){echo 'class="active"' ;}?> href="{{route($routeName.'.activity.packageprice',['package_id'=>$package_id]) }}" title="Activity Price"><i class="fas fa-dollar"></i>Activity Price</a>

     <?php }else{ ?>
      <a <?php if($active_menu=="packages".$package_id.'/packageprice'){echo 'class="active"' ;}?> href="{{route($routeName.'.packages.packageprice',['package_id'=>$package_id]) }}" title="Accommodation & Package Price"><i class="fas fa-dollar"></i>Accommodation & Package Price</a>
    <?php } ?>

    <a <?php if($active_menu=="packages".$package_id.'/departure'){echo 'class="active"' ;}?> href="{{route($routeName.'.'.$pa_edit.'.departure',['package_id'=>$package_id, 'f'=>'up']) }}" title="Travel Dates"><i class="fas fa-calendar"></i>Travel Dates</a>
    <?php /* <a <?php if($active_menu=="packages".$package_id.'/packagesetting'){echo 'class="active"' ;}?> href="{{route($routeName.'.packages.packagesetting',['package_id'=>$package_id]) }}" title="Setting"><i class="fas fa-gear"></i>Setting</a> */ ?>

    <?php /*<a <?php if($active_menu=="packages".$package_id.'/price-info'){echo 'class="active"' ;}?> href="{{route($routeName.'.packages.price_info',['package_id'=>$package_id]) }}"><i class="fas fa-dollar"  title="Accommodations &amp; price"></i>Price Info</a>*/ ?>

    <a <?php if($active_menu=="packages".$package_id.'/gallery'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$package_id,'module'=>'packages','category'=>'gallery']) }}" title="Images"><i class="fas fa-image"></i>Gallery</a>

    <?php /*<a <?php if($active_menu=="packages".$package_id.'/banner'){echo 'class="active"' ;} href="{{route($routeName.'.images.upload_view',['tid'=>$package_id,'module'=>'packages','category'=>'banner']) }}" title="Banner"><i class="fas fa-image"></i>Banner</a> 

    <a <?php if($active_menu=="packages".$package_id.'/accommodation'){echo 'class="active"' ;}href="{{route($routeName.'.packages.accommodation',['package_id'=>$package_id]) }}"><i class="fas fa-home"  title="Accommodations"></i>Accommodations</a>*/ ?>

    <a <?php if($active_menu=="packages".$package_id.'/seo_view' || $active_menu=="packages".$package_id.'/seo_meta'){echo 'class="active"' ;}?>  href="{{route($routeName.'.'.$pa_edit.'.seo_view',['package_id'=>$package_id]) }}" title="SEO Meta"><i class="fas fa-home"></i>SEO Meta</a>

    <a <?php if($active_menu=="packages".$package_id.'/faqs'){echo 'class="active"' ;}?> href="{{route($routeName.'.faqs.index',['tid'=>$package_id,'module'=>'packages','category'=>'faqs']) }}" title="Faqs"><i class="fa fa-question-circle"></i>Faqs</a>

    @if(CustomHelper::isAllowedModule('agentuser') && CustomHelper::checkPermission('agents','view'))
      <a <?php if($active_menu=="packages".$package_id.'/agent_price'){echo 'class="active"' ;}?> href="{{route($routeName.'.'.$pa_edit.'.agent_price', ['package_id'=>$package_id]) }}" title="Discount Price"><i class="fas fa-dollar"></i>Discount Price</a>
    @endif
</div>