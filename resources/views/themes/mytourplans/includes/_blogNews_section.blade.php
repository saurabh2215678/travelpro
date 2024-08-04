   <!-- BLOG SECTION START -->
      <section class="home_blog home_featured pb-0">
         <div class="container">
            <div class="row">
            <div class="theme_title mb-6 px-2">
               <h3 class="section_heading"> {{$section_title}}</h3>
               <div class="view_all_btn"><a href="{{route('blogsListing')}}" hreflang="en">See All Articles <i class="fa-solid fa-chevron-right"></i></a>
            </div>
            </div>
            <div class="blog_right">
         <div class="slider_box">
            <div>
               <div class="blog_flex">
                  <?php $storage = Storage::disk('public');
                   foreach($blogs as $blog){
                    $tTitle = $blog->title;
                    $tBrief =  CustomHelper::wordsLimit($blog->brief,150,'','',' more');
                    $tImage = $blog->image;

                    $path = 'blogs/';
                    $thumbPath = 'blogs/thumb/';

                    $blogSrc = asset('assets/img/blog_default.png');
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
                  <div class="blog_list" data-aos="flip-left">
                     <a class="blog_box" href="{{route('blogsDetail',['slug'=>$blog->slug])}}">
                        <img class="theme_radius" src="{{ $blogThumbSrc }}" alt="{{ $tTitle}}"/>
                        <div class="text">
                          <span class="title"> {{ $tTitle}}</span>
                          {!! $tBrief !!}
                          <div class="arrow_icon_inner">
                             <div class="arrow_icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                   <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                                </svg>
                             </div>
                          </div>
                       </div>
                    </a>
                 </div>
              <?php }?>
           </div>
        </div>
        
     
   </div>
</div>
         </div>
         </div>
      
</section>
<!-- BLOG SECTION END -->