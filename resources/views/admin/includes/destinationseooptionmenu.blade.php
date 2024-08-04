<?php $routeName = CustomHelper::getAdminRouteName(); ?>
<div class="page_btns">
	<a  <?php if(empty($des_type)){echo 'class="active"' ;}?>  href="{{ route($routeName.'.destinations.seo_view',['destination_id'=>$destination_id]) }}" title="Destination Detail Meta"><i class="fas fa-home"></i>Destination Detail Meta</a>
	<a  <?php if($des_type=="package"){echo 'class="active"' ;}?>  href="{{ route($routeName.'.destinations.seo_view',['destination_id'=>$destination_id, 'type'=>'package']) }}" title="Packages List by Destination Meta"><i class="fas fa-home"></i>Packages List by Destination Meta</a>
	<a  <?php if($des_type=="activity"){echo 'class="active"' ;}?>  href="{{ route($routeName.'.destinations.seo_view',['destination_id'=>$destination_id, 'type'=>'activity']) }}" title="Activities List by Destination Meta"><i class="fas fa-home"></i>Activities List by Destination Meta</a>
</div>