<?php
$link_type = (isset($link_type))?$link_type:'';
$page_id = (isset($page_id))?$page_id:'';
$categories = (isset($categories))?$categories:'';
$faqs = (isset($faq_categories))?$faq_categories:'';
$designations = (isset($designations))?$designations:'';
$blogs = (isset($blogs))?$blogs:'';
$news = (isset($news))?$news:'';
$events = (isset($events))?$events:'';
$cms_pages = (isset($cms_pages))?$cms_pages:'';


if(!empty($link_type)){

	?>
	<select name="page_id" class="form-control">
	<?php

	if($link_type == 'category' && !empty($categories)){
		foreach($categories as $cat){
			$url = 'categories/'.$cat->slug;
			$selected = '';
			if($url == $page_id){
				$selected = 'selected';
			}
			?>
			<option value="{{$cat->id}}" data-url="{{$url}}" {{$selected}} >{{$cat->name}}</option>
			<?php
		}
	} 
	elseif($link_type == 'destination' && !empty($destinations)){
		$destination_listing = CustomHelper::getSeoModules('destination_listing');
		$destinationListing = $destination_listing->page_url??'destinations';
		$destinationDetail = $destination_listing->page_detail_url??'destination';
		foreach($destinations as $destination) {
			$url_id = $destination->id;
			$url = $destinationDetail.'/'.$destination->slug;
			$selected = '';
			if($url_id == $page_id){
				$selected = 'selected';
			}
			?>
			<option value="{{$destination->id}}" data-url="{{$url}}" {{$selected}} >{{$destination->name}}</option>
			<?php
		}
	}	
	elseif($link_type == 'designation' && !empty($designations)){
		foreach($designations as $destination_it){
			$url = 'categories/'.$destination_it->slug;
			$selected = '';
			if($url == $page_id){
				$selected = 'selected';
			}
			?>
			<option value="{{$destination_it->id}}" data-url="{{$url}}" {{$selected}} >{{$destination_it->name}}</option>
			<?php
		}
	}
	elseif($link_type == 'blog' && !empty($blogs)){
		foreach($blogs as $blog){
			$url = 'blog/'.$blog->slug;
			$selected = '';
			if($url == $page_id){
				$selected = 'selected';
			}
			?>
			<option value="{{$blog->id}}" data-url="{{$url}}" {{$selected}} >{{$blog->title}}</option>
			<?php
		}
	}
	elseif($link_type == 'news' && !empty($news)){
		foreach($news as $nw){
			$url = 'blogs/'.$nw->slug.'?type=news';
			$selected = '';
			if($url == $page_id){
				$selected = 'selected';
			}
			?>
			<option value="{{$nw->id}}" data-url="{{$url}}" {{$selected}} >{{$nw->title}}</option>
			<?php
		}
	}
	elseif($link_type == 'event' && !empty($events)){
		foreach($events as $event){
			$url = 'events/'.$event->slug;
			$selected = '';
			if($url == $page_id){
				$selected = 'selected';
			}
			?>
			<option value="{{$event->id}}" data-url="{{$url}}" {{$selected}} >{{$event->title}}</option>
			<?php
		}
	}
	elseif($link_type == 'cms' && !empty($cms_pages)){
		$cms_listing = CustomHelper::getSeoModules('cms_listing');
		$cmsListing = $cms_listing->page_url??'';
		$cmsDetail = $cms_listing->page_detail_url??'';
		foreach($cms_pages as $cms){
			$url_id = $cms->id;
			if($cmsDetail) {
				$url = $cmsDetail.'/'.$cms->slug;
			} else {
				$url = $cms->slug;
			}
			$selected = '';
			if($url_id == $page_id){
				$selected = 'selected';
			}
			?>
			<option value="{{$cms->id}}" data-url="{{$url}}" {{$selected}} >{{$cms->title}}</option>
			<?php
		}
	}
	elseif($link_type == 'faq_category' && !empty($faqs)){
		foreach($faqs as $faq){
			$url = 'faqs/'.$faq->slug;
			$selected = '';
			if($url == $page_id){
				$selected = 'selected';
			}
			?>
			<option value="{{$faq->id}}" data-url="{{$url}}" {{$selected}} >{{$faq->title}}</option>
			<?php
		}
	}
	elseif($link_type == 'tour_category' && !empty($tour_categories)) {
		$tour_category = CustomHelper::getSeoModules('tour_category');
		$tourCategoryListing = $tour_category->page_url??'tour-category';
		$tourCategoryDetail = $tour_category->page_detail_url??'tour-category';		
		foreach($tour_categories as $tour_category){
			$url_id = $tour_category->id??0;
			$url = $tourCategoryDetail.'/'.$tour_category->slug;
			$selected = '';
			if($url_id == $page_id) {
				$selected = 'selected';
			}
			?>
			<option value="{{$tour_category->id}}" data-url="{{$url}}" {{$selected}} >{{$tour_category->title}}</option>
			<?php
		}
	}
	elseif($link_type == 'modules' && !empty($modules)) {
		// prd($modules);		
		foreach($modules as $identifier => $title){
			$module = CustomHelper::getSeoModules($identifier);
			$page_url = $module->page_url??'';
			$page_detail_url = $module->page_detail_url??'';
			$url = $page_url;
			$selected = '';
			if($identifier == $page_id) {
				$selected = 'selected';
			}
			?>
			<option value="{{$identifier}}" data-url="{{$url}}" {{$selected}} >{{$title}}</option>
			<?php
		}
	}
	?>
	</select>
	<?php
}
?>