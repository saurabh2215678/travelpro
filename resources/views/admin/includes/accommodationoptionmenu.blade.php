<?php $routeName = CustomHelper::getAdminRouteName();
$user = auth()->user();
$is_vendor = $user?$user->is_vendor:'';
?>
<div class="page_btns">
	<a <?php if($active_menu=="accommodations"){echo 'class="active"' ;}?> href="{{ route($routeName.'.accommodations.edit', $accommodation_id) }}" title="Edit Accommodation"><i class="fas fa-edit"></i> Accommodation</a>

	<a <?php if($active_menu=="accommodations".$accommodation_id.'/additional-info'){echo 'class="active"' ;}?> href="{{ route($routeName.'.accommodations.additional_info', $accommodation_id) }}" title="Additional Info"><i class="fas fa-info"></i>Additional Info</a>

	<a <?php if($active_menu=="accommodations".$accommodation_id.'/accommodation-room'){echo 'class="active"' ;}?> href="{{route($routeName.'.accommodations.accommodation_room', $accommodation_id) }}" title="Accommodation Rooms"><i class="fas fa-bath"></i> Rooms & Pricing </a>

	<?php if(CustomHelper::isOnlineBooking('hotel_listing')){ ?>
		<a <?php if($active_menu=="accommodations".$accommodation_id.'/inventory'){echo 'class="active"' ;}?> href="{{route($routeName.'.accommodations.inventory', $accommodation_id) }}" title="Accommodation inventory"><i class="fa fa-cube"></i> Room Inventory</a>
	<?php } ?>

	<a <?php if($active_menu=="accommodations".$accommodation_id.'/gallery'){echo 'class="active"' ;}?> href="{{ route($routeName.'.images.upload_view',['tid'=>$accommodation_id,'module'=>'accommodations','category'=>'gallery']) }}" title="Images"><i class="fas fa-image"></i>Gallery</a>
	<a <?php if($active_menu=="accommodations".$accommodation_id.'/banner'){echo 'class="active"' ;}?> href="{{ route($routeName.'.images.upload_view',['tid'=>$accommodation_id,'module'=>'accommodations','category'=>'banner']) }}" title="Images"><i class="fas fa-image"></i>Banner</a>

	<a  <?php if($active_menu=="accommodations".$accommodation_id.'/seo_view' || $active_menu=="accommodations".$accommodation_id.'/seo_meta'){echo 'class="active"' ;}?>  href="{{ route($routeName.'.accommodations.seo_view',['accommodation_id'=>$accommodation_id]) }}" title="SEO Meta"><i class="fas fa-home"></i>SEO Meta</a>

	<a <?php if($active_menu=="accommodations".$accommodation_id.'/faqs'){echo 'class="active"' ;}?> href="{{route($routeName.'.faqs.index',['tid'=>$accommodation_id,'module'=>'accommodations','category'=>'faqs']) }}" title="Faqs"><i class="fa fa-question-circle"></i>Faqs</a>

	@if(CustomHelper::isAllowedModule('agentuser') && CustomHelper::isOnlineBooking('hotel_listing') && $is_vendor != 1)
	<a <?php if($active_menu=="accommodations".$accommodation_id.'/agent_price'){echo 'class="active"' ;}?> href="{{route($routeName.'.accommodations.agent_price', ['accommodation_id'=>$accommodation_id]) }}" title="Agent Price"><i class="fas fa-dollar"></i>Agent Price</a>
	@endif
</div>