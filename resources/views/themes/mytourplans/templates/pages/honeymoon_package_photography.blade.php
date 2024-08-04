@component(config('custom.theme').'.layouts.main')

@slot('title')
       {{$page_title}} 
@endslot

@slot('meta_description')
{{ $meta_description ?? '' }}
@endslot

@slot('meta_keyword')
{{ $meta_keyword ?? '' }}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
cms-pages
@endslot
<?php
$storage = Storage::disk('public');
   $cms_path = 'cms_pages/';
   $cms_thumb_path = 'cms_pages/thumb/';

   $cms_image = $cms['image'];
   $cms_banner_image = $cms['banner_image'];

   // $cmsSrc = asset('assets/front/images/noimagebig.jpg');
   $cmsSrc = asset('assets/front/images/noimage.jpg');
   $cmsThumbSrc = asset('assets/front/images/cms_default_banner.jpg');
   $cms_image_src = '';
   if(!empty($cms_image)){
      if(File::exists("storage/".$cms_path.$cms_image)){
         $cms_image_src = asset('/storage/'.$cms_path.$cms_image);
      }
   }

   if(!empty($cms_banner_image)){
      if(File::exists("storage/".$cms_path.$cms_banner_image)){
         $cmsThumbSrc = asset('/storage/'.$cms_path.$cms_banner_image);
      }
   } 
?>
<section class="inner_banner">
  <div class="inner_banner_main">
    <img src="{{$cmsThumbSrc}}" alt="" >
  </div>
</section>

<?php $cms_data = $cms['cms'] ?? [];
      $slug = $cms_data->slug ?? '';
      $title = $cms_data->title ?? '';
?>
   
<section class="inner-cms-page">
  <div class="container">
    <div class="text_center">
      <div class="theme_title">
        <div class="title text-2xl">{{$cms['title']??''}}</div>
        <div class="breadcrumb_full">
         <div class="container">
            <ul class="breadcrumb">
               <li><a href="{{url('/')}}">Home</a>
               </li>
               <li><a> {{$title}}</a>
               </li>
            </ul>
         </div>
      </div>
      </div>
    </div>

    <div class="about_inner">
       @if(!empty($cms_image_src))
      <div class="">
        <div class="single_imgs"><img class="w-full" src="{{$cms_image_src}}" alt="{{$cms['title']??''}}" ></div>
      </div>
      @endif

      <div class="about_text">
      {!! $cms['content'] !!}
</div>
   </div>


  </div>
</section>


@slot('bottomBlock')

@endslot

@endcomponent