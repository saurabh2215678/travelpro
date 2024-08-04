@component(config('custom.theme').'.layouts.main')

@slot('title')
  {{ $meta_title ?? 'Blogs '}}
@endslot

@slot('meta_description')
  {{ $meta_description ?? 'Blogs '}}
@endslot

@slot('meta_keyword')
  {{ $meta_keyword ?? 'Blogs '}}
@endslot

@slot('headerBlock')
  
@endslot

@slot('bodyClass')
    blogs_class
@endslot
<?php
   $featured_blogs = [];// $blogs->where('featured',1)->take(6);
   $normal_blogs = $blogs;// $blogs->where('featured',0);
   $categories_arr =  (!empty(\Request::get('categories'))) ? \Request::get('categories') : [];
?>

<?php
$storage = Storage::disk('public');
$path = 'banners/';
/*$b_image =asset(config('custom.assets').'/images/noimage.jpg');
// dd($banners);
foreach($banners as $banner){
  $images = (isset($banner->Images))?$banner->Images:'';
  // prd($images->toArray());
  if(!empty($images) && count($images) > 0){
    foreach($images as $image){
      if(!empty($image->image_name) && $storage->exists($path.$image->image_name)){

        $b_image = url('/storage/banners/'.$image->image_name);
      }
    }
  }
}*/

$b_image =asset(config('custom.assets').'/images/noimage.jpg');
if($banner_image) {
  $b_image = $banner_image;
}

if($segment == 'news') {
   $urlList = route('newsListing');
} else{
   $urlList = route('blogsListing');
}
?>
    <section class="inner_banner">
      <div class="inner_banner_main">
         <img src="{{ $b_image }}" alt="{{isset($page_title)}}" >
      </div>
      @include(config('custom.theme').'/includes/search')
   </section>


      <section class="latest_blog_sec">
         <div class="container">
          <div class="text_center">
            <div class="theme_title mb-5">
              @if(!empty($page_title) || !empty($category_name))
               <h1 class="title text-2xl">{{!empty($category_name) ? $category_name : $page_title}}</h1>
               @endif
              <div class="breadcrumb_full">
               <div class="container">
                  <ul class="breadcrumb">
                     <li><a href="{{url('/')}}">Home</a>
                     </li>
                     <li><a href="{{$urlList}}">{{$page_url_label}}</a>
                     </li>
                     <?php 
                     
                     if($segment == 'news'){
                        $blogsListing = route('newsListing',[$category_slug]);
                     }else{
                        $blogsListing = route('blogsListing',[$category_slug]);
                     }

                     if($category_slug){  ?>
                     <li><a href="{{$blogsListing}}" hreflang="en"> <?php echo $category_name; ?></a>
                     </li>
                     <?php } ?>
                  </ul>
               </div>
            </div>
            </div>
            <div id="disc-title" class="text-left">
             @if(isset($page_brief) && !empty($page_brief))
             <p>{!!$page_brief!!}</p>
             @endif
             @if(isset($page_description) && !empty($page_description))
             <div>{!!$page_description!!}</div>
             @endif
           </div>
         </div>


             <?php
                     if(!empty($featured_blogs)){ ?>
            <?php /*<div class="text_center">
               <div class="theme_title mb-6">
                  <div class="title text-2xl">Blogs</div>

               </div>
            </div>*/ ?>
            <div class="slider_box">
               <div class="swiper latest_blog_slider">
                  <div class="swiper-wrapper flex">
                  <?php
                     if(!empty($featured_blogs)){
                     foreach($featured_blogs as $blog){
                        $blog_title = '';
                        if($blog->title) {
                          $blog_title = strip_tags($blog->title);
                          $blog_title = CustomHelper::wordsLimit($blog_title,55);
                        }

                        $blog_brief = '';
                        if($blog->brief) {
                          $blog_brief = strip_tags($blog->brief);
                          $blog_brief = CustomHelper::wordsLimit($blog_brief);
                        }

                        $blog_slug = $blog->slug??'';
                        $category_id = $blog->Category->slug??'';
                        // $category = $blog->category ?? null;
                        $blog_post_by = (!empty($blog->post_by)) ? ($blog->post_by) : '';
                        $blog_date = (!empty($blog->blog_date)) ? CustomHelper::DateFormat($blog->blog_date,'M d, Y') : '';

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

                        if($category_slug){
                           if($blog->type == 'news') {
                              $url = route('newsDetail',['slug'=>$blog_slug,'category_slug' => $category_slug]);
                           } else{
                              $url = route('blogsDetail',['slug'=>$blog_slug ,'category_slug' => $category_slug]);
                           }
                        }else{
                           if($blog->type == 'news') {
                              $url = route('newsDetail',['slug'=>$blog_slug]);
                           } else{
                              $url = route('blogsDetail',['slug'=>$blog_slug]);
                           }
                        }
                     ?>

                     <div class="swiper-slide blog_box_inner">
                        <div class="latest_blog mb-5">
                        <a href="{{$url}}">
                           <div class="latest_blog_images" data-swiper-parallax="-300">
                              <img src="{{ $blogthumbSrc }}" class="img_responsive theme_radius w-full" alt="{{$blog_title}}" />
                           </div>
                           <div class="latest_blog_content text-center px-1.5 py-3.5" data-swiper-parallax="-200">
                              <div class="title_font text-lg">{{ $blog_title }}</div>
                              <!-- <ul class="blog_info">
                                 <li>
                                    <div class="post_date"> {{ $blog_date }} </div>
                                 </li>
                                 <li>
                                    <div class="author_name">{{ $blog_post_by }}</div>
                                 </li>

                                 <li>
                                    <div class="author_name"><a href="{{route('blogsListing',$category_id)}}">{{ isset($blog->Category->name) ? $blog->Category->name:''  }}</a></div>
                                 </li>

                              </ul> -->
                              <p class="blog_text text-sm">{!! $blog_brief !!}</p>
                           </div>
                           </a>
                        </div>
                     </div>
                  <?php } } ?>
                  </div>
               </div>

            </div>
             <?php } ?>
           <?php if(!empty($normal_blogs) && count($normal_blogs) > 0) { ?>
            <?php /*<div class="text_center">
               <div class="theme_title mb-6">
                  <div class="title text-2xl">{{$page_heading}}</div>
                  <!-- <div class="view_all_btn"><a href="http://localhost:8000/blog">View All</a></div> -->

               </div>
            </div>*/ ?>
            <div class="flex blog_list_flex">
            <ul class="blog_list">
               <?php
                  foreach($normal_blogs as $blog){
                    $blog_title = '';
                    if($blog->title) {
                      $blog_title = strip_tags($blog->title);
                      $blog_title = CustomHelper::wordsLimit($blog_title,55);
                    }

                    $blog_brief = '';
                    if($blog->brief) {
                      $blog_brief = strip_tags($blog->brief);
                      $blog_brief = CustomHelper::wordsLimit($blog_brief);
                    }

                    $blog_slug = $blog->slug;
                    $blog_post_by = (!empty($blog->post_by)) ? ($blog->post_by) : '';
                    $blog_date = (!empty($blog->blog_date)) ? CustomHelper::DateFormat($blog->blog_date,'M d, Y') : '';

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


                  if($category_slug){
                     if($blog->type == 'news') {
                        $url = route('newsDetail',['slug'=>$blog_slug,'category_slug' => $category_slug]);
                     } else{
                        $url = route('blogsDetail',['slug'=>$blog_slug ,'category_slug' => $category_slug]);
                     }
                  }else{
                     if($blog->type == 'news') {
                        $url = route('newsDetail',['slug'=>$blog_slug]);
                     } else{
                        $url = route('blogsDetail',['slug'=>$blog_slug]);
                     }
                  }

                  ?>
               <li class="lg:w-1/3 mt-5">
                  <a href="{{$url}}" class="latest_blog">
                     <div class="latest_blog_images">
                        {{-- <img src="{{ $blogthumbSrc }}" class="img_responsive theme_radius w-full" /> --}}
                        <img src="{{ $blogSrc }}" class="img_responsive theme_radius w-full" alt="{{$blog_title}}" />
                     </div>
                     <div class="latest_blog_content text-center px-1.5 py-3.5">

                        <div class="title_font text-lg">{{ $blog_title }}</div>
                        <!-- <ul class="blog_info">
                           <li> <div class="post_date"> {{ $blog_date }} </div></li>
                           <li><div class="author_name">{{ $blog_post_by }} </div></li>
                        </ul> -->
                        <p class="blog_text text-sm">{!! $blog_brief !!}</p>

                     </div>
                  </a>
               </li>
            <?php }?>
            </ul>

            <form action="{{ $urlList }}" method="GET" id="adv_blogs_search" name="adv_blogs_search">
                  <div class="side_bar">
                     <div class="filter_box">
                        <div class="title para_md">Categories </div>
                           @if(!$blog_category_data->isEmpty())
                           @foreach($blog_category_data as $blog_cat)

                           <?php 
                           $blogsListing = route('blogsListing',[$blog_cat->slug]);
                           if($blog_cat->type == 'news'){
                              $blogsListing = route('newsListing',[$blog_cat->slug]);
                           }?>
                           <div class="checkbox_list">
                              <div class="checkbox_list">
                              <a href="{{$blogsListing}}" hreflang="en"> {{$blog_cat->name ?? ''}}</a>
                              </div>
                           </div>
                           @endforeach
                           @endif
                              <!-- <div class="checkbox_list">
                                 <input type="checkbox" id="categories8" name="categories[]" value="8">
                                 <label for="categories8">White Water Rafting</label><br>
                              </div>
                              <div class="checkbox_list">
                                 <input type="checkbox" id="categories9" name="categories[]" value="9">
                                 <label for="categories9">Trekking and Hiking</label><br>
                              </div>  -->
                        
                     </div>
                  <!-- <ul class="filter_button pt-2">
                        <li><a href="{{ route('blogsListing') }}" class="btn2">Clear</a></li>
                     </ul> -->
                  </div>


               </form>
            </div>
            <?php } else { ?>
              <div class="alert_not_found">No blog data found.</div>
            <?php } ?>

            <?php echo $blogs->links('vendor.pagination.frontpaginate'); ?>
         </div>
</section>
@slot('bottomBlock')
<script>
   $(document).ready(function(){
      $(document).on('change','.side_bar .filter_box input[type=checkbox]', function(){
         $('#adv_blogs_search').submit();
      });
   })
   
   var epSlider = new Swiper(".latest_blog_slider", {
   // slidesPerView: 5.3,
   slidesPerView: 4,
   loop: true,
   navigation: {
   nextEl: ".cat-prev",
   prevEl: ".cat-next",
   },
   breakpoints: {
         0: {
            slidesPerView: 2,
         },
         640: {
            slidesPerView: 2,
         },
         768: {
            slidesPerView: 3,
         },
         1024: {
            slidesPerView: 4,
         },
      },
      navigation: {
       nextEl: ".client-next",
       prevEl: ".client-prev",
    },
   });
</script>

@endslot

@endcomponent