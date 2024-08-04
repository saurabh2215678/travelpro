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
<section class="inner_banner">
         <div class="inner_banner_main">
            <img src="{{asset(config('custom.assets').'/images/blog-and-news.jpg')}}" alt="" >
         </div>
      </section>
      <section class="latest_blog_sec">
         <div class="container">
            <div class="text_center">
               <div class="theme_title">
                  <div class="title">
                     {{ $blogCategoryDetails->Category->name}}
                  </div>
                  <div class="icon mt15">
                     <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
                  </div>
               </div>
            </div>
            <ul class="blog_list">
               <?php 
               //prd($blogCategoryDetails);
                  if(isset($blogCategoryDetails) && !empty($blogCategoryDetails)){
                  foreach($blogCategoryDetails as $blog){
                     $blog_title = (!empty($blog->title)) ?($blog->title): '';
                     $blog_slug = $blog->slug;
                     $category_id = $blog->Category->name;
                     $blog_post_by = (!empty($blog->post_by)) ? ($blog->post_by) : '';
                     $blog_date = (!empty($blog->blog_date)) ? CustomHelper::DateFormat($blog->blog_date,'M d, Y') : '';
                     $blog_brief = (!empty($blog->brief)) ? ($blog->brief) : '';

                     $storage = Storage::disk('public');
                     $blog_path = "blogs/";
                     $blog_thumb_path = "blogs/thumb/";

                     $blogthumbSrc = asset('assets/img/blog_thumb_img.jpg');
                     $blogSrc = asset('assets/img/blog_img.jpg');

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
               <li>
                  <a href="{{route('blogsDetail',['slug'=>$blog_slug])}}" class="latest_blog">
                     <div class="latest_blog_images">
                        <img src="{{ $blogthumbSrc }}" class="img_responsive theme_radius" />
                     </div>
                     <div class="latest_blog_content">
                      
                        <div class="title_font heading_sm1">{{ $blog_title }}</div>
                        <ul class="blog_info">
                           <li> <div class="post_date"> {{ $blog_date }} </div></li>
                           <li><div class="author_name">{{ $blog_post_by }} </div></li>
                        </ul>
                        <p class="para_md">{!! $blog_brief !!}</p>
                     
                     </div>
                  </a>
               </li>
            <?php }}?>
            </ul>
            
         </div>
</section>
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