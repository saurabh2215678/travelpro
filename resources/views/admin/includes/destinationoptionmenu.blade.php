<?php $routeName = CustomHelper::getAdminRouteName(); 
?>
<div class="page_btns">
	<a <?php if($active_menu=="destinations"){echo 'class="active"' ;}?> href="{{ route($routeName.'.destinations.view', ['id'=>$destination_id]) }}" title="View Destination"><i class="fas fa-edit"></i>Destination </a>

	<a <?php if($active_menu=="destinations".$destination_id.'/additional-info'){echo 'class="active"' ;}?> href="{{ route($routeName.'.destinations.additional_info', $destination_id) }}" title="Additional Info"><i class="fas fa-info"></i>Additional Info</a>

	<a <?php if($active_menu=="destinations".$destination_id.'/gallery'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$destination_id,'module'=>'destinations','category'=>'gallery'])}}" title="Gallery"><i class="fas fa-image"></i>Gallery</a>
	<a <?php if($active_menu=="destinations".$destination_id.'/banner'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$destination_id,'module'=>'destinations','category'=>'banner'])}}" title="Banner"><i class="fas fa-image"></i>Banner</a>

	<a  <?php if($active_menu=="destinations".$destination_id.'/seo_view' || $active_menu=="destinations".$destination_id.'/seo_meta'){echo 'class="active"' ;}?>  href="{{ route($routeName.'.destinations.seo_view',['destination_id'=>$destination_id]) }}" title="SEO Meta"><i class="fas fa-home"></i>SEO Meta</a>

	<a <?php if($active_menu=="destinations".$destination_id.'/faqs'){echo 'class="active"' ;}?> href="{{route($routeName.'.faqs.index',['tid'=>$destination_id,'module'=>'destinations','category'=>'faqs']) }}" title="Faqs"><i class="fa fa-question-circle"></i>Faqs</a>

	<a <?php if($active_menu=="destination_packages".$destination_id.'/faqs'){echo 'class="active"' ;}?> href="{{route($routeName.'.faqs.index',['tid'=>$destination_id,'module'=>'destination_packages','category'=>'faqs']) }}" title="Tour Packages List by Destination Faqs"><i class="fa fa-question-circle"></i>Tour Packages List by Destination Faqs</a>

	@if(CustomHelper::isAllowedModule('activity_listing'))
	<a <?php if($active_menu=="destination_activity".$destination_id.'/faqs'){echo 'class="active"' ;}?> href="{{route($routeName.'.faqs.index',['tid'=>$destination_id,'module'=>'destination_activity','category'=>'faqs']) }}" title="Tour Activity List by Destination"><i class="fa fa-question-circle"></i>Tour Activity List by Destination Faqs</a>
	@endif
</div>