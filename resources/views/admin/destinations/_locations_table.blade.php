	<style>
		.table-responsive.fancybox-content {
    max-height: 560px;
    border-radius: 5px;
}
	</style>
	<table class="table table-striped table-bordered table-hover" id="scrolltop">
		<tr>
			<th>S.N.</th>
			<th>Place Name</th>
			<th>Sort Order</th>
			<th>Status</th>
			<th>Show</th>
			<th>Action</th>
		</tr>
		@if($destination->destinationLocations)
		@foreach($destination->destinationLocations as $k => $location)
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
				<?php if($location->show == 1){ ?>
          <span style="color:green"><i class="fas fa-check" style="color:green"></i></span>
          <?php }else{ ?>
          <span style="color:#f00;"><i style="color:#f00;" class="fa fa-times"></i></span>
          <?php } ?>				
			</td>
			<td>
				@if(CustomHelper::checkPermission('destinations','edit'))
				<a href="#scrollup" data-id="{{$location->id}}" class="location_edit" title="Edit"><i class="fas fa-edit"></i> </a>
				@endif
				@if(CustomHelper::checkPermission('destinations','delete'))
				<a href="javascript:void(0);" data-id="{{$location->id}}" class="location_delete" title="Edit"><i class="fas fa-trash"></i> </a>
				@endif
			</td>
		</tr>
		@endforeach
		@endif
	</table>

	<!-- <script>
		$(document).ready(function() {
  
  // variables 
  var toTop = $('#dd');
  // logic
  toTop.on('click', function() {
    $('.fancybox-active').animate({
      scrollTop: $('.fancybox-active').offset().top,
    });
  });

});
	</script> -->
	<style>
.fancybox-content {
    padding: 25px;
}
	</style>