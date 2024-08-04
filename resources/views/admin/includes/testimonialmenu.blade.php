<?php $routeName = CustomHelper::getAdminRouteName();?>
<div class="page_btns">
	{{--<a <?php if($active_menu=="testimonials"){echo 'class="active"' ;}?> href="{{ route($routeName.'.testimonials.view', ['id'=>$id]) }}" title="View Testimonial"><i class="fas fa-edit"></i>Testimonial </a>--}}
	{{--<a <?php if($active_menu=="testimonials"){echo 'class="active"' ;}?> href="javascript:void(0);" data-src="<?php echo route($routeName.'.testimonials.view', ['id'=>$id]) ?>" data-fancybox data-type="ajax" title="View Testimonial"><i class="fas fa-edit"></i>Testimonial</a>--}}
	<a <?php if($active_menu=="testimonials"){echo 'class="active"' ;}?> href="{{ route($routeName.'.testimonials.edit', ['id'=>$id, 'back_url'=>$back_url]) }}" title="edit Testimonial"><i class="fas fa-edit"></i>Testimonial</a>
	<a <?php if($active_menu=='testimonials/'.$id.'/gallery'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$id,'module'=>'testimonials'])}}" title="Gallery"><i class="fas fa-image"></i>Gallery</a>
	<a  <?php if($active_menu=="testimonials".$id.'/seo_view' || $active_menu=="testimonials".$id.'/seo_meta'){echo 'class="active"' ;}?>  href="{{ route($routeName.'.testimonials.seo_view',['id'=>$id]) }}" title="SEO Meta"><i class="fas fa-home"></i>SEO Meta</a>
</div>