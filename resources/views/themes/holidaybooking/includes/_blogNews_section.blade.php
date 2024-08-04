<section class="home_featured" style="">
         <div class="container-xl">
            <div class="row">
                <div class="text-center mb-6 padding_x">
            <h2 class="font30 color_secondary">{{$section_title}}</h2>
            <a class="arrow_btn mx-auto" href="{{route('blogsListing')}}">View All <img src="{{ asset(config('custom.assets').'/images/right_arrow.svg') }}" alt=""></a>
         </div>
         
            <div class="blog_right">
         <div class="slider_box">
            <div class="swiper blog_slider">
               <div class="swiper-wrapper">
                  <?php $storage = Storage::disk('public');
                  foreach($blogs as $blog){
                    $tTitle = $blog->title;
                    $tBrief = CustomHelper::wordsLimit($blog->brief,150);
                    $tImage = $blog->image;

                    $path = 'blogs/';
                    $thumbPath = 'blogs/thumb/';

                    $blogSrc = asset(config('custom.assets').'img/blog_default.png');
                    $blogThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');

                    if(!empty($tImage)){
                      if($storage->exists($path.$tImage)){
                         $blogSrc = asset('/storage/'.$path.$tImage);
                      }

                      if($storage->exists($thumbPath.$tImage)){
                        $blogThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                     }
                  }
                  ?>
                  <div class="swiper-slide">
                     <a class="blog_box" href="{{route('blogsDetail',['slug'=>$blog->slug])}}">
                        <img class="theme_radius" src="{{ $blogThumbSrc }}" alt="{{$tTitle}}" />
                        <div>
                          <span class="title"> {{ $tTitle}}
                          </span>
                          <p class="para_md">{!! $tBrief !!}</p>
                          <!-- <div class="arrow_icon_inner">
                             <div class="arrow_icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                   <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                                </svg>
                             </div>
                          </div> -->
                       </div>
                    </a>
                 </div>
              <?php }?>
           </div>
        </div>
        
      <div class="slider_btns">
         <div class="blog-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
         <div class="blog-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
      </div>
   </div>
</div>
         </div>
         </div>
      </div>
</section>