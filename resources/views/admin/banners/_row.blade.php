<?php
if(!empty($image) && count(array($image)) > 0){
	$imgName = $image->image_name;
	?>

	<tr class="row_{{$image->id}}">
        <td>
        	<input type="text" name="title[{{$image->id}}]" placeholder="Title" value="{{$image->title}}" class="form-control" > &nbsp;
        	<input type="text" name="sub_title[{{$image->id}}]" placeholder="Sub Title" value="{{$image->sub_title}}" class="form-control" >
        </td>

        <td>
        	<?php
			$imgUrl = 'javascript:void(0)';
			$imgSrc = '';
			if(!empty($imgName) && $storage->exists($thumb_path.$imgName)){
				$imgSrc = url('/storage/'.$thumb_path.$imgName);
			}
			if(!empty($imgName) && $storage->exists($path.$imgName)){
				$imgUrl = url('/storage/'.$path.$imgName);
				//prd($imgUrl);
				if(empty($imgSrc)){					
					$imgSrc = url('/storage/'.$path.$imgName);
				}
			}

			if(!empty($imgSrc)){
				?>
				<a href="{{$imgUrl}}" target="_blank">
					<img src="{{$imgSrc}}" style="width:100px;">
				</a>
				<?php
			}
			?>
        </td>

        <td>
        	<input type="text" name="link_text_1[{{$image->id}}]" placeholder="Link Text 1" value="{{$image->link_text_1}}" class="form-control" > &nbsp;
        	<input type="text" name="link_1[{{$image->id}}]" placeholder="Link 1" value="{{$image->link_1}}" class="form-control" > &nbsp;
        	<input type="text" name="link_text_2[{{$image->id}}]" placeholder="Link Text 2" value="{{$image->link_text_2}}" class="form-control" > &nbsp;
        	<input type="text" name="link_2[{{$image->id}}]" placeholder="Link 2" value="{{$image->link_2}}" class="form-control" >
        </td>

        <td>
        	<input type="number" name="sort_order[{{$image->id}}]" placeholder="Sort Order" value="{{$image->sort_order}}" class="form-control" >
        </td>

        @if(CustomHelper::checkPermission('banners','delete'))
        <td>
        <a href="javascript:void(0)" data-id="{{$image->id}}" class="delImg" title="Delete"><i class="fas fa-trash-alt"></i></a>
        </td>
        @endif	

      </tr>

      <input type="hidden" name="ids[]" value="{{$image->id}}">

	<?php
}
?>