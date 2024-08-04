@component(config('custom.theme').'.layouts.main')

@slot('title')
       {{$page_title ?? 'Use Information ' }} 
@endslot

@slot('meta_description')
       {{$meta_description ?? 'Use Information '}} 
@endslot

@slot('meta_keyword')
       {{$meta_keyword ?? 'Use Information '}} 
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
cms-pages
@endslot

<?php
//prd($cms);
$storage = Storage::disk('public');
   $cms_path = 'cms_pages/';

   $cms_image = $cms['image'];
   $cms_banner_image = $cms['banner_image'];

   $cmsSrc =asset(config('custom.assets').'/images/map-overview.jpg');
   $cmsThumbSrc =asset(config('custom.assets').'/images/about-banner.jpg');

   if(!empty($cms_image)){
      if(File::exists("storage/".$cms_path.$cms_image)){
         $cmsSrc = asset('/storage/'.$cms_path.'thumb/'.$cms_image);
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
            <img src="{{ $cmsThumbSrc }}" alt="" >
         </div>
      </section>
      
      <section class="info_area">
         <div class="container">
            <div class="text-center">
              <div class="theme-title">
               <div class="title text-3xl pb-5">
                  <?php echo $cms['title'];?>
               </div>
               <!-- <div class="icon mt15">
                  <img src="/assets/front/images/featured-icon.png" alt="">
               </div> -->
            </div>
            </div>
            <div class="info_sec">
               <table>
                  
               </table>
            </div>
         </div>
      </section>

      <section>
         <div class="container">
            <?php echo $cms['content'];?>
         </div>
  </section>
@slot('bottomBlock')
@endslot

@endcomponent