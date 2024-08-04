@component(config('custom.theme').'.layouts.main')

@slot('title')
       {{$page_title ?? 'India Overview '}} 
@endslot

@slot('meta_description')
       {{$meta_description ?? 'India Overview '}} 
@endslot

@slot('meta_keyword')
       {{$meta_keyword ?? 'India Overview '}} 
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
      <!-- Bhutan Overview -->
      <section>
         <div class="container">
            <div class="theme-title">
               <div class="title text-2xl pb-5"><?php echo $cms['title'];?></div>
            </div>
            <div class="overview-top">
               <div class="overview_left">
                  <?php echo $cms['content'];?>
               </div>
               <div class="overview_right">
                <div class="overview_img" data-aos="fade-left" data-aos-duration="2000">
                  <img src="{{ $cmsSrc }}">
                </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Bhutan Overview-1 -->
      <section class="festival_area">
         <?php
            $widgetDetail = CustomHelper::getWidget('bhutan-overview-1');
           //prd($widgetDetail);
            if(!empty($widgetDetail)){
               $storage = Storage::disk('public');
               $path = 'widgets/';
               $section_heading = $widgetDetail->section_heading;
               $description = $widgetDetail->description;
               
            
         ?>
         <div class="container">
            <div class="text_left">
              <div class="theme-title">
               <div class="title text-2xl pb-5">
                 {{ $widgetDetail->section_heading }}
               </div>
               <!-- <div class="icon mt15">
                  <img src="{{asset(config('custom.assets').'/images/featured-icon.png') }}" alt="">
               </div> -->
            </div>
            </div>
           <div class="festival_cont" data-aos="fade-up" data-aos-duration="2000">
             {!! $widgetDetail->description !!} 
           </div>
         </div>
      <?php } ?>
      </section>
      <!-- Bhutan Overview-2 -->
      <section >
         <div class="container">
            <div class="culture_area">
               <ul>
                  <li>
                     <?php
                        $widgetDetail = CustomHelper::getWidget('bhutan-overview-2');
                        if(!empty($widgetDetail)){
                        $storage = Storage::disk('public');
                        $path = "widgets/"; 
                        $section_heading = $widgetDetail->section_heading;
                        $description = $widgetDetail->description;
                        ?>
                      <div class="cult_img w-1/4" data-aos="fade-right" data-aos-duration="2000">
                        <img src="{{asset(config('custom.assets').'/images/img1.jpg')}}">
                        <div class="top_img"><img src="{{asset(config('custom.assets').'/images/icon1.png')}}"></div>
                     </div>
                     <div class="cult_content w-3/4">
                     <div class="theme_title text-2xl pb-3">
                    <div class="title">{{ $widgetDetail->section_heading }}</div>
                  </div>
                  {!! $widgetDetail->description !!} 
                     </div>
                  </li>
                  <?php } ?>
                  <!-- Bhutan Overview-3 -->
                    <li>
                     <?php
                        $widgetDetail = CustomHelper::getWidget('bhutan-overview-3');
                        if(!empty($widgetDetail)){
                        $storage = Storage::disk('public');
                        $path = "widgets/"; 
                        $section_heading = $widgetDetail->section_heading;
                        $description = $widgetDetail->description;
                        ?>
                     <div class="cult_img w-1/4" data-aos="fade-left" data-aos-duration="2000">
                        <img src="{{asset(config('custom.assets').'/images/img22.jpg')}}">
                        <div class="top_img">
                           <img src="{{asset(config('custom.assets').'/images/icon2.png')}}"></div>
                     </div>
                     <div class="cult_content w-3/4">
                     <div class="theme_title text-2xl pb-3">
                    <div class="title">{{ $widgetDetail->section_heading }}</div>
                  </div>
                  {!! $widgetDetail->description !!} 
                     </div>
                  </li>
               <?php } ?>

               </ul>
            </div>
         </div>
      </section>
      <!-- Bhutan Overview-4 -->
      </section>
      <section class="environment_area" style="background-image: url({{asset(config('custom.assets').'/iimages/accommodation.jpg') }})">
         <?php
                        $widgetDetail = CustomHelper::getWidget('bhutan-overview-4');
                        if(!empty($widgetDetail)){
                        $storage = Storage::disk('public');
                        $path = "widgets/"; 
                        $section_heading = $widgetDetail->section_heading;
                        $description = $widgetDetail->description;
                        ?>
         <div class="container">
            <div class="text_left">
              <div class="theme-title">
               <div class="title text-2xl pb-3">
                 {{ $widgetDetail->section_heading }}
               </div>
               <!-- <div class="icon mt15">
                  <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}" alt="">
               </div> -->
            </div>
            </div>
           <div class="environment_cont" data-aos="fade-up" data-aos-duration="2000">
              {!! $widgetDetail->description !!} 
           </div>
         </div>
      <?php } ?>
      </section>
      <!-- Bhutan Overview-5 -->
     <section>
      <?php
                        $widgetDetail = CustomHelper::getWidget('bhutan-overview-5');
                        if(!empty($widgetDetail)){
                        $storage = Storage::disk('public');
                        $path = "widgets/"; 
                        $section_heading = $widgetDetail->section_heading;
                        $description = $widgetDetail->description;
                        ?>
     <div class="container">
        <div class="gross_area">
           <ul>
            <li>
               <div class="gros_content w-3/5">
                     <div class="theme_title">
                    <div class="title text-2xl pb-5">{{ $widgetDetail->section_heading }}</div>
                  </div>
                  {!! $widgetDetail->description !!} 
                 </div>
                 <div class="gros_img w-2/5" data-aos="fade-left" data-aos-duration="2000">
                        <img src="{{asset(config('custom.assets').'/images/img33.jpg')}}">
                     </div>
              </li>
           </ul>
        </div>
     </div> 
     <?php } ?>  
     </section>
@slot('bottomBlock')
@endslot

@endcomponent
