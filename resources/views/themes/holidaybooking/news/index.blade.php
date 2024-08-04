@component(config('custom.theme').'.layouts.main')

@slot('title')
{{ $meta_title ?? ''}}
@endslot    

@slot('meta_description')
{{ $meta_description ?? ''}}
@endslot
@slot('meta_keyword')
{{ $meta_keyword ?? ''}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
blogs_class
@endslot
<?php if(!($blogs->isEmpty())){ 

   $featured_blogs = $blogs->where('featured',1)->take(6);
   $normal_blogs = $blogs->where('featured',0);
   ?>
   <section class="inner_banner">
      <div class="inner_banner_main">
         <img src="{{asset(config('custom.assets').'/images/what-s-new.jpg')}}" alt="" >
      </div>
   </section>
   <section class="latest_blog_sec pb-3">
      <div class="container">
         <div class="text-center">
            <div class="theme-title">
               <div class="title text-3xl pb-5">
                  What's & New
               </div>
               <!-- <div class="icon mt15">
                  <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
               </div> -->
            </div>
         </div>
         <div class="slider_box">
            <div class="swiper latest_blog_slider">
               <div class="swiper-wrapper">
                 <?php 
                 if(!empty($featured_blogs)){
                  foreach($featured_blogs as $blog){
                     $blog_title = (!empty($blog->title)) ?($blog->title): '';
                     $blog_slug = $blog->slug;
                     $blog_post_by = (!empty($blog->post_by)) ? ($blog->post_by) : '';
                     $blog_date = (!empty($blog->blog_date)) ? CustomHelper::DateFormat($blog->blog_date,'M d, Y') : '';
                     $blog_brief = (!empty($blog->brief)) ? ($blog->brief) : '';
                     $storage = Storage::disk('public');
                     $blog_path = "blogs/";
                     $blog_thumb_path = "blogs/thumb/";

                     $blogthumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                     $blogSrc =asset(config('custom.assets').'/images/noimage.jpg');

                     $image = $blog->image;
                     if(!empty($image) && $storage->exists($blog_thumb_path.$image))
                     {
                       $blogthumbSrc = asset('/storage/'.$blog_thumb_path.$image);
                    }

                    if(!empty($image) && $storage->exists($blog_path.$image))
                    {
                       $blogSrc = asset('/storage/'.$blog_path.$image);
                    }
                    ?>
                    <div class="swiper-slide">
                     <div class="latest_blog h-full">
                        <div class="latest_blog_images w-1/3 float-left h-full" data-swiper-parallax="-300">
                           <img src="{{ $blogthumbSrc }}" class="img_responsive theme_radius" />
                        </div>
                        <div class="latest_blog_content w-2/3 float-right p-8 h-full" data-swiper-parallax="-200">
                           <div class="text-2xl">{{ $blog_title }}
                           </div>
                           <ul class="blog_info pt-2">
                              <li>
                                 <div class="post_date"> {{ $blog_date }} </div>
                              </li>
                              <li>
                                 <div class="author_name">{{ $blog_post_by }}</div>
                              </li>
                           </ul>
                           <p class="pt-3 pb-3">{!! $blog_brief !!}</p>
                           <a href="{{route('blogsDetail',['slug'=>$blog_slug])}}" class="btn inline-block p-3 text-sm">Continue reading</a>
                        </div>
                     </div>
                  </div>
               <?php }}?>
            </div>
         </div>
         <div class="slider_btns">
            <div class="cat-next theme-next"><img src="{{asset(config('custom.assets').'/images/next-sm.png')}}" alt=""></div>
            <div class="cat-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/prev-sm.png')}}" alt=""></div>
         </div>
      </div>

      <ul class="blog_list pt-10">
         <?php foreach($normal_blogs as $blog){
            $blog_title = (!empty($blog->title)) ?($blog->title): '';
            $blog_slug = $blog->slug;
            $blog_post_by = (!empty($blog->post_by)) ? ($blog->post_by) : '';
            $blog_date = (!empty($blog->blog_date)) ? CustomHelper::DateFormat($blog->blog_date,'M d, Y') : '';
            $blog_brief = (!empty($blog->brief)) ? ($blog->brief) : '';

            $storage = Storage::disk('public');
            $blog_path = "blogs/";
            $blog_thumb_path = "blogs/thumb/";

            $blogthumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
            $blogSrc =asset(config('custom.assets').'/images/noimage.jpg');

            $image = $blog->image;
            if(!empty($image) && $storage->exists($blog_thumb_path.$image))
            {
              $blogthumbSrc = asset('/storage/'.$blog_thumb_path.$image);
           }

           if(!empty($image) && $storage->exists($blog_path.$image))
           {
              $blogSrc = asset('/storage/'.$blog_path.$image);
           }
           ?>
           <li class="w-1/3 mb-7">
            <a href="{{route('newsDetail',['slug'=>$blog_slug])}}" class="latest_blog">
               <div class="latest_blog_images">
                  <img src="{{ $blogthumbSrc }}" class="img_responsive theme_radius w-full" />
               </div>
               <div class="latest_blog_content p-5 hover:bg-slate-200">
                  <div class="text-lg font-semibold leading-tight heading_sm1">{{ $blog_title }}</div>
                  <ul class="blog_info pb-2">
                     <li> <div class="post_date text-xs"> {{ $blog_date }} </div></li>
                     <li><div class="author_name text-xs">{{ $blog_post_by }} </div></li>
                  </ul>
                  <p class="text-sm">{!! $blog_brief !!}</p>
                  
               </div>
            </a>
         </li>
      <?php }?>
   </ul>
   <?php echo $blogs->links('vendor.pagination.frontpaginate'); ?>
</div>
</section>
<?php } ?>
@slot('bottomBlock')
<script>
   var epSlider = new Swiper(".latest_blog_slider", {
   // slidesPerView: 5.3,
   slidesPerView: 1,
   loop: true,
   parallax:true,
   speed:2000,
   navigation: {
      nextEl: ".cat-prev",
      prevEl: ".cat-next",
   },
});
</script>

@endslot

@endcomponent