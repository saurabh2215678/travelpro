@component(config('custom.theme').'.layouts.main')

@slot('title')
{{ $page_title ?? 'Car Rentals'}}
@endslot    

@slot('meta_description')
{{ $meta_description ?? 'Car Rentals'}}
@endslot

@slot('meta_keyword')
{{$meta_keyword ?? 'Car Rentals'}} 
@endslot

@slot('headerBlock')

@endslot

@slot('bodyClass')
car_rentals_class
@endslot
<?php
//prd($cms);
$storage = Storage::disk('public');
   $cms_path = 'cms_pages/';
   $cms_heading = $cms['heading'];
   $cms_content = $cms['content'];
   $cms_image = $cms['image'];
   $cms_banner_image = $cms['banner_image'];

   $cmsSrc =asset(config('custom.assets').'/images/noimage.jpg');
   $cmsThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

   if(!empty($cms_image)){
      if($storage->exists($cms_path.$cms_image)){
         $cmsSrc = asset('/storage/'.$cms_path.'thumb/'.$cms_image);
      }
   }

   if(!empty($cms_banner_image)){
      if($storage->exists($cms_path.$cms_banner_image)){
         $cmsThumbSrc = asset('/storage/'.$cms_path.$cms_banner_image);
      }
   } 
?>
<section class="inner_banner">
<div class="inner_banner_main">
<img src="{{ $cmsThumbSrc }}" alt="" >
</div>
</section>
<section class="flight_page pb_150">
<div class="container">
<div class="text-center">
<div class="theme-title">
<div class="title text-3xl pb-5">Car Rentals</div>

<!-- <div class="icon mt15">&nbsp;</div>
<img alt="" src="{{asset(config('custom.assets').'/images/featured-icon.png')}}" /></div> -->
</div>
<div class="gateways_grid group_travel_top">
   <div class="visa_assistance">
      <div class="images">
        <!--  <img alt="" class="theme_radius img_responsive" src="{{ $cmsSrc }}" /> -->
      </div>
      <div class="visa_assistance_content">
      <div class="title heading3">{{ $cms_heading }}</div>
      {!! $cms_content !!}
      </div>

   </div>
</div>
</div>
</div>
</section>
@slot('bottomBlock')


@endslot

@endcomponent
