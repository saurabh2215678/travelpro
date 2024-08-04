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

@slot('headerBlock')

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
$blogTags = (!($blogDetails->blogTags->isEmpty())) ? $blogDetails->blogTags : "";

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
$blogTags = (!($blogDetails->blogTags->isEmpty())) ? $blogDetails->blogTags : "";
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
?>
    <section class="inner_banner">
      <div class="inner_banner_main">
         <img src="{{asset(config('custom.assets').'/images/blog_details_banner.jpg')}}" alt="" >
      </div>
      <?php /* @include(config('custom.theme').'/includes/search')*/ ?>
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
                              <div class="author_name"><a href="{{route('blogsListing',$category_id)}}">{{ isset($blogDetails->Category->name) ? $blogDetails->Category->name:''  }}</a></div>
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
                  <li><a href="{{url('/')}}">Home</a>
                  </li>
               <li><a href="{{route('blogsListing')}}">{{$breadcrumb_title}}</a>
                  </li>
                  <?php if($category_slug && $category_name){  ?>
                     <li><a href="{{route('blogsListing',[$category_slug])}}" hreflang="en"><?php echo $category_name; ?></a>
                  </li>
               <?php } ?>
                  <li>
                     <?php echo $title; ?>
                  </li>
               </ul>
            </div>
         </div>
                        
                     </div>
               <div class="leftsec mb-7">
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
                              <a href="{{route('blogsListing',[$blog_category->slug])}}"> {{$blog_category->name ?? ''}}</a>                            
                           <?php } } ?>
                           </div>
                           
                           <div class="blog_full_content">
                           {!!$blogDetails->content ?? ''!!}
                           </div>

                           <div class="blog_social mt-5">
                              <div class="share pb-3 font-bold">Share</div>
                              <ul class="social_icon">

                                 <?php if($facebook) { ?>
                                    <li class="fb"><a href="<?php echo str_replace('{current_url}', $blogUrl, $facebook);?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg></a></li>
                                 <?php } ?>

                                 <?php if($twitter) { ?>
                                    <li class="twitter"><a href="<?php echo str_replace('{current_url}', $blogUrl, $twitter);?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg></a></li>
                                 <?php } ?>

                                 <?php if($instagram) { ?>
                                    <li class="instagram"><a href="<?php echo str_replace('{current_url}', $blogUrl, $instagram);?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a></li>
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
                                    <a class="featured_box" href="{{ route('blogsDetail',['slug'=>$rblog_slug]) }}">
                                       <div class="images">
                                          <img src="{{$rblogThumbSrc}}" alt="{{$rblog_title}}">
                                       </div>
                                       <div class="featured_content px-1.5 py-3.5" style="background-image: url({{asset(config('custom.assets').'/images/featured-bg.jpg')}});">
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
                              <div class="featured-prev theme-next"><img src="{{asset(config('custom.assets').'/images/next-sm.png') }}" alt="Next icon"></div>
                              <div class="featured-next theme-prev"><img src="{{asset(config('custom.assets').'/images/prev-sm.png') }}" alt="Prev icon"></div>
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
                        <div class="cat-next theme-next"><img src="{{asset(config('custom.assets').'/images/next.png') }}" alt="Next icon"></div>
                        <div class="cat-prev theme-prev"><img src="{{asset(config('custom.assets').'/images/prev.png') }}" alt="Prev icon"></div>
                     </div>
                  </div>

                  <div class="form">
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
<script type="text/javascript">  
grecaptcha.ready(function() {
   grecaptcha.execute("{{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}", {action:'validate_captcha'}).then(function(token) {
   // add token value to form
   const element = document.getElementById('g-recaptcha-response');
   if(element) {
    document.getElementById('g-recaptcha-response').value = token;
   }
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