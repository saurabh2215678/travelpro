<?php
if(!empty($image) && count(array($image)) > 0){
	$imgName = $image->name;
	?>

	<tr class="row_{{$image->id}}">
        <td>
        	<input type="text" name="title[{{$image->id}}]" value="{{$image->title}}" placeholder="Title" class="form-control" >
        </td>

        <td>
        	<?php
			$imgUrl = 'javascript:void(0)';
			$imgSrc = '';
			if(!empty($imgName) && $storage->exists($thumb_path.$imgName)){
				$imgUrl = url('/storage/'.$thumb_path.$imgName);
				$imgSrc = url('/storage/'.$thumb_path.$imgName);
			}
			if(!empty($imgName) && $storage->exists($path.$imgName)){
				$imgUrl = url('/storage/'.$path.$imgName);

				if(!empty($imgSrc)){					
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

        <!-- <td>
        	<select name="category_id[{{$image->id}}]" class="form-control" required>
        		<option value="">Select Category</option>
			<?php
			/*if(!empty($categories) && count($categories) > 0){
				foreach($categories as $cat_key=>$cat_val){
                     //prd($pa_val);
					?>
					<option @if($cat_val['id']==$image->category_id) selected @endif value="{{ $cat_val['id'] }}"><?php echo $cat_val['name']; ?></option>
					<?php
				}

			}*/

			if(!empty($categories) && count($categories) > 0){

				foreach($categories as $cat){
					$CategoryBreadcrumb = CustomHelper::CategoryBreadcrumb($cat, '', '');

					$category_hierarchy = str_replace('<i aria-hidden="true" class="fa fa-angle-double-right"></i>', "&gt;", $CategoryBreadcrumb);
					$category_hierarchy = strip_tags($category_hierarchy);

					$selected = '';

					if( $cat->id == $image->category_id ){
						$selected = 'selected';
					}

                    //pr($category_hierarchy);
					?>
					<option value="{{$cat->id}}" {{$selected}} >{{$category_hierarchy}}</option>
					<?php
				}
			}
			?>
		</select>
        </td> -->

        <td>

        	<input type="text" name="link[{{$image->id}}]" value="{{$image->link}}" class="form-control" placeholder="Brief" > &nbsp;
        	<input type="text" name="sort_order[{{$image->id}}]" value="{{$image->sort_order}}" class="form-control" placeholder="Sort Order" >
        </td>

        <td>
        	
        	<a href="javascript:void(0)" data-id="{{$image->id}}" class="delImg" title="Delete"><i class="fas fa-trash-alt"></i></a>
        	
        </td>

      </tr>

      <input type="hidden" name="ids[]" value="{{$image->id}}">

	<?php
}
?>