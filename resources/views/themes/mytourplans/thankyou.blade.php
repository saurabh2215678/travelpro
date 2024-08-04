
@component(config('custom.theme').'.layouts.main')
@slot('title')
    Thank You Page - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot
<?php
$websiteSettingsNamesArr = ['FRONTEND_LOGO'];
$websiteSettingsArr = CustomHelper::websiteSettingsArray($websiteSettingsNamesArr);
$FRONTEND_LOGO = (isset($websiteSettingsArr['FRONTEND_LOGO']))?$websiteSettingsArr['FRONTEND_LOGO']->value:'';
$path = 'settings/';
$storage = Storage::disk('public');

/* default logo */
$logoUrl = asset('/images/logo.png');

if(!empty($FRONTEND_LOGO) && $storage->exists($path.$FRONTEND_LOGO)){
    $logoUrl = asset('storage/settings/'.$FRONTEND_LOGO);
}


// if(!empty($FRONTEND_LOGO) && $storage->exists($path.$FRONTEND_LOGO))
// {
//  $logoUrl = asset('storage/settings/'.$FRONTEND_LOGO);
// }

?>

<div class="not_found_page">
<div class="tlogo"><img src="{{$logoUrl}}" class="img-fluid" alt="">
</div>
<h3>{{(!empty($success_message))?$success_message:'Thank You for contacting us.'}}</h3> <br><h2>One of our team member will get in touch with you shortly. </h2>
<a class="btn" href="{{url('/')}}">Go To Home</a>
</div>
@slot('bottomBlock')


@endslot

@endcomponent


