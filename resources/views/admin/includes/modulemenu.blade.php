<?php
$routeName = CustomHelper::getAdminRouteName();
$back_url = (request()->has('back_url'))?request()->input('back_url'):''; ?>
<div class="page_btns">

  <a <?php if($active_menu=="module_config"){echo 'class="active"' ;}?> href="{{ route($routeName.'.module_config.view',['id'=>$module_config_id,'back_url'=>$back_url]) }}" title="View Modules"><i class="fas fa-edit"></i>Modules </a>

  <a <?php if($active_menu=="module_config".$module_config_id.'/faqs'){echo 'class="active"' ;}?> href="{{route($routeName.'.faqs.index',['tid'=>$module_config_id,'module'=>'seo_meta_tags','category'=>'faqs']) }}" title="Faqs"><i class="fa fa-question-circle"></i>Faqs</a>
</div>