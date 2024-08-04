@component(config('custom.theme').'.layouts.main')

    @slot('title')
      {{ $meta_title ?? 'Testimonials'}}
    @endslot    

    @slot('meta_description')
      {{ $meta_description ?? 'Testimonials'}}
    @endslot

    @slot('headerBlock')
   
    @endslot

    @slot('bodyClass')
        testimonials_class
    @endslot

<?php
$b_image =asset(config('custom.assets').'/images/cms_default_banner.jpg');
if($banner_image) {
  $b_image = $banner_image;
}
?>
      <section class="inner_banner">
         <div class="inner_banner_main">
            <img src="{{$b_image}}" alt="{{$page_title}}" >
         </div>
         @include(config('custom.theme').'/includes/search')
      </section>
   

      <section class="client_review_wrap">
         <div class="container">
            <div>
               <div class="theme_title mb-0"> 
                  <div>
                     @if(isset($page_title) && !empty($page_title))
                     <h1 class="title text-2xl mb-5">{{$page_title}}</h1>
                     @endif
                     <div>
                     @if(isset($page_brief) && !empty($page_brief))
                     <p>{!!$page_brief!!}</p>
                     @endif
                     @if(isset($page_description) && !empty($page_description))
                     <div>{!!$page_description!!}</div>
                     @endif
                  </div>
               </div>
               <div class="breadcrumb_full">
                  <div class="container">
                     <ul class="breadcrumb">
                        <li><a href="{{url('/')}}">Home</a>
                        </li>
                        <li><a href="{{route('testimonialListing')}}">{{$page_url_label}}</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            </div>
            <?php if(!$testimonials->isEmpty()){ ?>
            <div class="flex flex-wrap testi_main">
            <?php foreach($testimonials as $testimonial){
               $tName = isset($testimonial->name) ? CustomHelper::wordsLimit($testimonial->name,20) : '';
               $tTitle = $testimonial->title??'';
               $tLocation = $testimonial->location??'';
               $tDescription = $testimonial->description??'';
               $tImage = $testimonial->image;
               $tSlug = $testimonial->slug??'';
               
               $storage = Storage::disk('public');
               $path = 'testimonials/';
               $thumbPath = 'testimonials/thumb/';

               //$testimonialSrc = asset('assets/img/testimonial_default.png');
               //$testimonialSrc =asset(config('custom.assets').'/images/noimage.jpg');
               $testimonialSrc = asset('assets/img/default_user.png');
               $testimonialThumbSrc = asset('assets/img/default_user.png');

               if(!empty($tImage)){
                  if($storage->exists($path.$tImage)){
                    $testimonialSrc = asset('/storage/'.$path.$tImage);
                  }
                  if($storage->exists($thumbPath.$tImage)){
                     $testimonialThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                  }
               }

               $testimonialImages = $testimonial->testimonialImages[0] ?? [];
               $testimonialGalleryImage = '';
               $testimonialGalleryImageName = asset(config('custom.assets').'/images/testimonial-main-default.png');
               if(!empty($testimonialImages)) {
                     $testimonialGalleryImage = $testimonialImages->name;
               if($storage->exists($path.$testimonialGalleryImage)) {
                  $testimonialGalleryImageName = asset('/storage/'.$path.$testimonialGalleryImage);
               } } ?>
               
           <div class="client_review_box lg:w-1/4">
               <div class="client_box_inner">
                  <div class="client_box">
                     <!-- <span class="quote_icon"></span> -->
             <a href="{{ route('testimonialDetails',['slug'=>$tSlug]) }}" class="team_management_box">
                     <div class="images"><img src="{{ $testimonialGalleryImageName }}" class="theme_radius img_responsive" alt="{{ $tName }}"></div>
                     <div class="client_info px-5 mt-3">
                        <div class="name para_lg2 text-center flex justify-center leading-tight"> <img style="width: 22px;height: 22px;" class="object-cover rounded-full mr-3" src="{{ $testimonialSrc }}" alt="{{ $tName }}"> {{ $tName }}</div>
                        <!-- <div class="name para_lg2">{{ $tTitle }}</div> -->
                        <!-- <div class="city">{{ $tLocation }}</div> -->
                        <div class="content py-2">
                     <div class="para_md text-center">
                        {!! strlen($tDescription) > 100 ? substr($tDescription,0,100)."..." : $tDescription !!}
                     </div>
                  </div>
                     </div>
                     </a>
                  </div>
                  
               </div>
           </div>
         <?php } ?>
        </div>
         <?php echo $testimonials->links('vendor.pagination.frontpaginate'); ?>
         <?php } ?>
         </div>

         <div class="testimonial_all">
         <!-- <div class="slider_btns">
               <div class="testimonial-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/next-sm.png') }}" alt=""></div>
               <div class="testimonial-next theme-next"><img src="{{asset(config('custom.assets').'/images/prev-sm.png') }}" alt=""></div>
            </div> -->
         <a href="{{route('testimonialadd')}}" class="btn">WRITE YOUR TESTIMONIAL</a>
      </div>


      </section>

@slot('bottomBlock')


@endslot

@endcomponent
