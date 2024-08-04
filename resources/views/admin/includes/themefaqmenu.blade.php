<?php
$routeName = CustomHelper::getAdminRouteName(); 
$back_url = request()->has('back_url') ? request()->input('back_url') : '';
$module = "Package";
// $segment == 'packages';
if($segment == 'activity'){
  $module = "Activity";
}
?>
<div class="page_btns">

  <a <?php if($active_menu=='themes'){echo 'class="active"' ;}?> href="{{route($routeName.'.'.$segment.'.theme_view', ['id'=>$theme_id,'back_url'=>$back_url]) }}" title="Edit {{$module}} Theme Categories"><i class="fas fa-edit"></i>{{$module}} Theme Categories</a>
    
  <a <?php if($active_menu=="themes".$theme_id.'/faqs'){echo 'class="active"' ;}?> href="{{route($routeName.'.faqs.index',['tid'=>$theme_id,'module'=>'themes','category'=>'faqs','segment'=>$segment]) }}" title="Faqs"><i class="fa fa-question-circle"></i>Faqs</a>
</div>