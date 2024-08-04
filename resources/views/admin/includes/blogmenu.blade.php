<?php $routeName = CustomHelper::getAdminRouteName(); 
?>
<div class="page_btns">
	<a <?php if($active_menu=="blogs"){echo 'class="active"' ;}?> href="{{ route($routeName.'.blogs.blog_view', ['id'=>$id, 'type'=>$type]) }}" title="View Blog"><i class="fas fa-edit"></i>Blog </a>
	<a  <?php if($active_menu=="blogs".$id.'/seo_view' || $active_menu=="blogs".$id.'/seo_meta'){echo 'class="active"' ;}?>  href="{{ route($routeName.'.blogs.seo_view',['id'=>$id, 'type'=>$type]) }}" title="SEO Meta"><i class="fas fa-home"></i>SEO Meta</a>
</div>