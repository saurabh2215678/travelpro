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

      <section class="inner_banner">
         <div class="inner_banner_main">
            <img src="{{asset(config('custom.assets').'/images/review-banner.jpg')}}" alt="" >
         </div>
      </section>
      
<?php if(isset($testimonials) && !empty($testimonials)){ ?>
      <section class="client_review_wrap">
         <div class="container">
            <div class="text_center">
               <div class="theme_title">
                  <div class="title">
                  What our clients have to say.
                  </div>
                  <div class="icon mt15">
                     <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
                  </div>
               </div>
            </div>
            <?php foreach($testimonials as $testimonial){
            //prd($testimonial->toArray());
               $tName = $testimonial->name??'';
               $tTitle = $testimonial->title??'';
               $tLocation = $testimonial->location??'';
               $tDescription = $testimonial->description??'';
               $tImage = $testimonial->image;
               
               $storage = Storage::disk('public');
               $path = 'testimonials/';
               $thumbPath = 'testimonials/thumb/';

               $testimonialSrc = asset('assets/img/testimonial_default.png');
               $testimonialThumbSrc = asset('assets/img/default_user.png');

               if(!empty($tImage)){
                  if($storage->exists($path.$tImage)){
                    $testimonialSrc = asset('/storage/'.$path.$tImage);
                  }

                  if($storage->exists($thumbPath.$tImage)){
                     $testimonialThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                  }
               }
            ?>
           <div class="client_review_box">
               <div class="client_box_inner">
                  <div class="client_box">
                     <span class="quote_icon"></span>
                     <div class="images"><img src="{{ $testimonialThumbSrc }}" class="theme_radius img_responsive" alt=""></div>
                     <div class="client_info">
                        <div class="name para_lg2">{{ $tName }}</div>
                        <div class="name para_lg2">{{ $tTitle }}</div>
                        <div class="city">{{ $tLocation }}</div>
                     </div>
                  </div>
                  <div class="content">
                     <p class="para_md">
                        {!! $tDescription !!}
                     </p>
                  </div>
               </div>
           </div>
         <?php } ?>
         <?php echo $testimonials->links('vendor.pagination.frontpaginate'); ?>
         </div>
      </section>
   <?php } ?>

@slot('bottomBlock')


@endslot

@endcomponent
