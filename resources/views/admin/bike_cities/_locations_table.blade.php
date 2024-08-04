	<table class="table table-striped table-bordered table-hover" id="scrolltop">
	    <tr>
	        <th>S.N.</th>
	        <th>Location</th>
	        <th>Sort Order</th>
	        <th>Status</th>
	        <th>Action</th>
	    </tr>
	    @if($bike_city->locations)
	    @foreach($bike_city->locations as $k => $location)
	    <tr>
	        <td>{{$k+1}}</td>
	        <td>{{$location->name}}</td>
	        <td>{{$location->sort_order}}</td>
	        <td>
	            <?php if($location->status == 1){ ?>
	            <span style="color:green">Active</span>
	            <?php }else{ ?>
	            <span style="color:red">Inactive</span>
	            <?php } ?>
	        </td>
	        <td>
	            @if(CustomHelper::checkPermission('rental','edit'))
	            <a href="#scrollup" data-id="{{$location->id}}" class="location_edit" title="Edit"><i
	                    class="fas fa-edit"></i> </a>
	            @endif
	            @if(CustomHelper::checkPermission('rental','delete'))
	            <a href="javascript:void(0);" data-id="{{$location->id}}" class="location_delete" title="Edit"><i
	                    class="fas fa-trash"></i> </a>
	            @endif
	        </td>
	    </tr>
	    @endforeach
	    @endif
	</table>