<?php $routeName = CustomHelper::getAdminRouteName(); 
?>
<div class="page_btns">
	<a <?php if($active_menu=="cms"){echo 'class="active"' ;}?> href="{{ route($routeName.'.cms.view', ['id'=>$id]) }}" title="View Cms"><i class="fas fa-edit"></i>Cms </a>
	<a <?php if($active_menu=="cms".$id.'/gallery'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$id,'module'=>'cms_pages','category'=>'gallery'])}}" title="Gallery"><i class="fas fa-image"></i>Gallery</a>

	<a  <?php if($active_menu=="cms".$id.'/seo_view' || $active_menu=="cms".$id.'/seo_meta'){echo 'class="active"' ;}?>  href="{{ route($routeName.'.cms.seo_view',['id'=>$id]) }}" title="SEO Meta"><i class="fas fa-home"></i>SEO Meta</a>

	<a <?php if($active_menu=="cms".$id.'/faqs'){echo 'class="active"' ;}?> href="{{route($routeName.'.faqs.index',['tid'=>$id,'module'=>'cms_pages','category'=>'faqs']) }}" title="Faqs"><i class="fa fa-question-circle"></i>Faqs</a>
</div>