<?php
//$module = $module??'';
//$category_query = CustomHelper::getImageCategories($module);
if(!empty($image) && !empty($image->name)) {
	$imgName = $image->name;
?>
<tr class="row_{{$image->id}}">
	<td>{{($image->is_default==1)?'Yes':'--'}}</td>
	<td>{{$image->title??''}}</td>
	<td>
		<?php
		$imgUrl = 'javascript:void(0)';
		$imgSrc = '';
		if(!empty($imgName)){ //$storage->exists($thumb_path.$imgName)
			$imgUrl = url('/storage/'.$path.$imgName);
			$imgSrc = url('/storage/'.$thumb_path.$imgName);
		?>
		<a href="{{$imgUrl}}" target="_blank"><img src="{{$imgSrc}}" style="width:100px;"> </a>
		<?php } ?>
	</td>
	<?php /*<td>{{$image->ImageCategory->name??''}}</td> */ ?>
	<td>{{$image->link??''}}</td>
	<td>{{$image->sort_order??''}}</td>
</tr>
<?php } ?>