@component(config('custom.theme').'.layouts.main')

@slot('title')
  {{ $meta_title ?? ''}}
@endslot  

@slot('page_heading')
  {{ $page_heading ?? ''}}
@endslot  

@slot('meta_description')
  {{ $meta_description ?? ''}}
@endslot

@slot('meta_keyword')
  {{ $meta_keyword ?? ''}}
@endslot

@slot('canonical_url')
{{route('blogsDetail',['slug'=>$blogDetails->slug])}}
@endslot

@slot('headerBlock')

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
    blogs_class
@endslot

<?php
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY','FACEBOOK_SHARE','TWITTER_SHARE','INSTAGRAM_SHARE','WHATSAPP_SHARE']);
$facebook = $websiteSettingsArr['FACEBOOK_SHARE'] ?? '';
$twitter = $websiteSettingsArr['TWITTER_SHARE']?? '';
$instagram = $websiteSettingsArr['INSTAGRAM_SHARE']?? '';
$whatsapp = $websiteSettingsArr['WHATSAPP_SHARE']?? '';


$path = 'blogs/';
$thumb_path = 'blogs/thumb/';
$storage = Storage::disk('public');
//prd($blogDetails->blogTags);
$title = (!empty($blogDetails->title)) ? $blogDetails->title : '';
$content = (!empty($blogDetails->content)) ? $blogDetails->content : '';
$blog_date = (!empty($blogDetails->blog_date)) ? CustomHelper::DateFormat($blogDetails->blog_date,'d M') : '';
$category_id = $blogDetails->Category->slug ?? '';
$post_by = (!empty($blogDetails->post_by)) ? $blogDetails->post_by : "";
$blogTags = isset($blogDetails->blogTags) && !empty($blogDetails->blogTags) ? $blogDetails->blogTags : "";

$BlogTags = "";
if(!empty($blogTags)){
  $BlogTags = [];
  foreach ($blogTags as  $tag) {
      $BlogTags[] = $tag->name;
  }
  $BlogTags = implode(',',$BlogTags);
}

$path = 'blogs/';
$thumb_path = 'blogs/thumb/';
$storage = Storage::disk('public');

$title = (!empty($blogDetails->title)) ? $blogDetails->title : '';
$content = (!empty($blogDetails->content)) ? $blogDetails->content : '';
$blog_brief = (!empty($blogDetails->brief)) ? $blogDetails->brief : '';
$blog_date = (!empty($blogDetails->blog_date)) ? CustomHelper::DateFormat($blogDetails->blog_date,'d M Y') : '';
$post_by = (!empty($blogDetails->post_by)) ? $blogDetails->post_by : "";
$blogTags = isset($blogDetails->blogTags) && !empty($blogDetails->blogTags) ? $blogDetails->blogTags : "";
$blog_category_data = $blogDetails->blogToCategory?? [];
$blog_category = $blogDetails->blogToCategory->pluck('name')->toArray()??[];

$blogthumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
$blogSrc =asset(config('custom.assets').'/images/noimagebig.jpg');

$image = $blogDetails->image;
if(!empty($image) && $storage->exists($thumb_path.$image))
{
   $blogthumbSrc = asset('/storage/'.$thumb_path.$image);
}

if(!empty($image) && $storage->exists($path.$image))
{
   $blogSrc = asset('/storage/'.$path.$image);
}
$blogUrl = route('blogsDetail',['slug'=>$blogDetails->slug]);

// $relatedPackagestObj = "";
//    if(!empty($related_packages)){
//       $relatedPackagestObj = App\Models\Package::whereIn('id',$related_packages)->get();
//    }

$b_image =asset(config('custom.assets').'/images/noimage.jpg');
if($banner_image) {
  $b_image = $banner_image;
}
?>
    <section class="inner_banner">
      <div class="inner_banner_main">
         <img src="{{$b_image}}" alt="{{$page_title}}" >
      </div>
      
   </section>


   <section class="search_home py-0 md:px-0">
      <div>
         @include(config('custom.theme').'.includes.search')
      </div>
   </section>



   <section>
      <div class="blog_single_top">
            <div class="xl:container xl:mx-auto">
            <div class="blog_single_info">
                        <div class="about_blog">
                           <h1 class="title text-2xl">{{ $title }}</h1>
                           <!-- <ul class="blog_info">
                              <li>
                              <div class="post_date"> {{ $blog_date }} </div>
                              </li>
                              <li>
                              <div class="author_name">{{ $post_by }} </div>
                              </li>
                                 <li>
                              <div class="author_name"><a href="{{route('blogsListing',$category_id)}}" hreflang="en">{{ isset($blogDetails->Category->name) ? $blogDetails->Category->name:''  }}</a></div>
                              </li>

                              <li>
                              <div class="author_name"><?php 
                                 // $blogTags = (!empty($blogDetails) && !($blogDetails->BlogTags->isEmpty())) ? $blogDetails->BlogTags : "";
                                 // $BlogTags = "";
                                 // if(!empty($blogTags)){
                                 //       $BlogTags = [];
                                 //       foreach ($blogTags as  $tag) {
                                 //          $BlogTags[] = $tag->name;
                                 //       }
                                 //       $BlogTags = implode(',',$BlogTags);
                                 // }
                                 // echo $BlogTags;
                                 //prd($BlogTags);
                                 ?></div>
                              </li>
                              
                           </ul> -->
                        </div>
                        
      <div class="breadcrumb_full">
            <div class="container">
               <ul class="breadcrumb">
                  <li><a href="{{url('/')}}" hreflang="en">Home</a>
                  </li>
               <li><a href="{{route('blogsListing')}}" hreflang="en">{{$breadcrumb_title}}</a>
                  </li>
                  <?php if($category_slug && $category_name){  ?>
                     <li><a href="{{route('blogsListing',[$category_slug])}}" hreflang="en"> <?php echo $category_name; ?></a>
                  </li>
               <?php } ?>
                  <li>
                     <?php echo $title; ?>
                  </li>
               </ul>
            </div>
         </div>
                        
                     </div>
               <div class="leftsec">
                  <div class="single_blog_container">  
                     <div class="single_blog_wrap pb-5">
                        <div class="single_blog_container">
                           <div class="single_blog_img">
                              <img src="{{ $blogSrc }}" class="img_responsive theme_radius" alt="{{$title}}" />
                              <div class="flex flex-wrap mt-5">
                                 <div class="text-base" style="color:rgb(0 0 0 / 46%);width:50%;">Author :  {{ $post_by }}</div>
                                 <div class="text-base" style="text-align:left;color:rgb(0 0 0 / 46%);">Date : {{ $blog_date }}</div>
                              </div>
                           </div>
                           <div class="blog_full_content mb-3">
                           Categories:  <?php if ($blog_category_data) {  
                              foreach ($blog_category_data as $key => $blog_category) {
                                 if($key != 0){
                                    echo '|';  
                                 }
                              ?>
                              <a href="{{route('blogsListing',[$blog_category->slug])}}" hreflang="en"> {{$blog_category->name ?? ''}}</a>                            
                           <?php } } ?>
                           </div>
                           
                           <div class="blog_full_content">
                           {!!$blogDetails->content ?? ''!!}
                           </div>

                           <div class="blog_social mt-5 bg_share ">
                              <p class="mr-3">Share:</p>
                              <ul class="social_icon">
                                 <?php if($facebook) { ?>
                                    <li class="fb"><a href="<?php echo str_replace('{current_url}', $blogUrl, $facebook);?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                 <?php } ?>

                                 <?php if($twitter) { ?>
                                    <li class="twitter"><a href="<?php echo str_replace('{current_url}', $blogUrl, $twitter);?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                 <?php } ?>

                                 <?php if($instagram) { ?>
                                    <li class="insta"><a href="<?php echo str_replace('{current_url}', $blogUrl, $instagram);?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                 <?php } ?>

                                 <?php if($whatsapp) { ?>
                                    <li class="whatsapp"><a href="<?php echo str_replace('{current_url}', $blogUrl, $whatsapp);?>" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
                                 <?php } ?>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php if(!empty($relatedBlogsObj) && !($relatedBlogsObj)->isEmpty()){ ?>
                     <div class="relatedblog">
                        <div class="text_center">
                           <div class="theme_title">
                              <div class="title text-2xl">Similar Blogs</div>
                           </div>
                        </div>
                        <div class="slider_box mt-5 mb-5">
                           <div class="swiper featured_slider">
                              <div class="swiper-wrapper">
                                 <?php foreach ($relatedBlogsObj as $rblogs) {
                                    // $rblog_title = CustomHelper::wordsLimit($rblogs->title);
                                    $rblog_title = '';
                                    if($rblogs->title) {
                                     $rblog_title = strip_tags($rblogs->title);
                                     $rblog_title = CustomHelper::wordsLimit($rblog_title,55);
                                  }

                                    $rblog_brief = '';
                                    if($rblogs->brief) {
                                       $rblog_brief = strip_tags($rblogs->brief);
                                       $rblog_brief = CustomHelper::wordsLimit($rblog_brief,100);
                                    }

                                    $rblog_slug = $rblogs->slug;
                                    $rblog_image = $rblogs->image;
                                    $rblogThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                                    
                                    if(!empty($rblog_image)){
                                       if($storage->exists($thumb_path.$rblog_image)){
                                          $rblogThumbSrc = asset('/storage/'.$thumb_path.$rblog_image);
                                       }
                                    }
                                    ?>
                                 <div class="swiper-slide">
                                    <a class="featured_box" href="{{ route('blogsDetail',['slug'=>$rblog_slug]) }}" hreflang="en">
                                       <div class="images">
                                          <img src="{{$rblogThumbSrc}}" alt="{{$rblog_title}}">
                                       </div>
                                       <div class="featured_content px-1.5 py-3.5" >
                                          <div class="title text-sm">{{$rblog_title}}</div>
                                          <div class="blog_somilar_brief text-sm">{!! $rblog_brief !!}</div>
                                          <!-- <div class="view_all">View Detail</div> -->
                                       </div>
                                    </a>
                                 </div>
                                 <?php } ?>
                              </div>
                           </div>
                           <div class="slider_btns">
                              <div class="featured-prev theme-next"><img src="{{asset(config('custom.assets').'/images/next-sm.png') }}" alt=""></div>
                              <div class="featured-next theme-prev"><img src="{{asset(config('custom.assets').'/images/prev-sm.png') }}" alt=""></div>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
               </div>
            
               <div class="sidebarsec">
               <div class="title text-2xl mb-3">Explore Great Deals</div>
                  <div class="slider_box">
                     <ul class="swiper product_scoller featured_list destination_package_slider">
                        <div class="swiper-wrapper">
                           @include(config('custom.theme').'/includes/package-li-box',['packages'=>$explore_package])
                        </div>
                     </ul>
                     <div class="slider_btns">
                        <div class="cat-next theme-next"><img src="{{asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
                        <div class="cat-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
                     </div>
                  </div>

                  <div class="form blog_form">
                     <h2 class="title text-lg mb-6 text-center">Please fill the form and our expert will get back to you with further details.</h2>
                     {!!formShortCode(['slug' =>'[home_page_form]','class'=>'left_form'])!!}
                  </div>
               </div>
            </div>
      </div>
   </section>



<div class="clear-both"></div>


@slot('bottomBlock')
<script src="https://www.google.com/recaptcha/api.js?render={{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script type="text/javascript">  
grecaptcha.ready(function() {
   grecaptcha.execute("{{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}", {action:'validate_captcha'}).then(function(token) {
   // add token value to form
   document.getElementById('g-recaptcha-response').value = token;
   });
});
</script>
<script>



   var swiper =  new Swiper(".product_scoller", {
           slidesPerView: 2,
           spaceBetween:20,
           loop: false,
           speed:1000,
           navigation: {
            nextEl: ".cat-prev",
            prevEl: ".cat-next",
            },
         breakpoints: {
            0: {
               slidesPerView: 1,
               spaceBetween:0,
            },
            640: {
               slidesPerView: 1,
            },
            768: {
               slidesPerView: 3,
            },
            1024: {
               slidesPerView: 2,
            },
         },
      });


   var featuredSlider =  new Swiper(".featured_slider", {
           slidesPerView: 3,
           spaceBetween:20,
           loop: false,
           speed:1000,
           navigation: {
           prevEl: ".featured-prev",
           nextEl: ".featured-next",
       },
         breakpoints: {
            0: {
               slidesPerView: 1,
               spaceBetween:0,
            },
            640: {
               slidesPerView: 2,
            },
            768: {
               slidesPerView: 3,
            },
            1024: {
               slidesPerView: 3,
            },
         },
      });
</script>

@endslot

@endcomponent