@component(config('custom.theme').'.layouts.main')

@slot('title')
  {{ $meta_title ?? 'Teams'}}
@endslot

@slot('meta_description')
  {{ $meta_description ?? 'Teams'}}
@endslot

@slot('headerBlock')
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
    team_detail_class inner_page
@endslot

@slot('bottomBlock')

<?php

$path = 'teammanagements/';
$thumb_path = 'teammanagements/thumb/';
$storage = Storage::disk('public');

$team_title = (!empty($teamDetails->title)) ? $teamDetails->title : '';
$team_sub_title = (!empty($teamDetails->sub_title)) ? $teamDetails->sub_title : '';

$name =$team_title;

$designation = (!empty($teamDetails->designation)) ? $teamDetails->designation : '';
$detail_profile = (!empty($teamDetails->detail_profile)) ? $teamDetails->detail_profile : '';

$teamSrc =asset(config('custom.assets').'/images/noimage.jpg');

$image = $teamDetails->image;

if(!empty($image) && $storage->exists($path.$image))
{
   $teamSrc = asset('/storage/'.$path.$image);
}
$teamUrl = route('teamDetail',['slug'=>$teamDetails->slug]);
$teamPackages = isset($teamDetails->expertPackages) && !empty($teamDetails->expertPackages) ? $teamDetails->expertPackages : "";
?>

<div class="breadcrumb_full">
      <div class="container">
         <ul class="breadcrumb">
            <li><a href="{{url('/')}}">Home</a>
            </li>
           <li><a href="{{route('hotelListing')}}">{{$breadcrumb_title}}</a>
            </li>
            <li>
               <?php echo $team_title; ?>
            </li>
         </ul>
      </div>
   </div>
<section class="team_single_wrap" style="background-image: url({{asset(config('custom.assets').'/images/vision-bg.jpg') }});">
   <div class="container">
     <div class="team_single_box">
        <div class="left_box" data-aos="fade-left" data-aos-duration="2000">
           <div class="single_images">
            <img src="{{ $teamSrc }}" class="img_responsive" alt="" />
           </div>
        </div>
        <div class="right_box" data-aos="fade-right" data-aos-duration="3000">
         <div class="team_info_single">
            <div class="name heading2">{{ $name }}</div>
            <div class="designation para_lg1">{{ $designation }}</div>
            <div class="designation para_lg1">{{ $team_sub_title }}</div>
         </div>
         {!! $detail_profile !!}
        </div>
     </div>
   </div>
</section>

<?php if(!empty($teamPackages)){ ?>
<section class="pb_150">
   <div class="container">
      <div class="text_center">
         <div class="theme_title">
            <div class="title">
               Expert on Trips
            </div>
            <div class="icon mt15">
               <img src="{{asset(config('custom.assets').'/images/featured-icon.png') }}"   alt="">
            </div>
         </div>
      </div>
      <ul class="featured_list">
         <?php foreach ($teamPackages as $package) {
            $package_title = CustomHelper::wordsLimit($package->title);
            $package_brief = CustomHelper::wordsLimit($package->brief);
            $package_slug = $package->slug;
            $package_duration = $package->package_duration;
            $package_image = $package->image;
            $package_path = 'packages/';
            $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

            if(!empty($package_image)){
               if($storage->exists($package_path.$package_image)){
                  $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package_image);
               }
            }
         ?>
         <li data-aos="fade-up" data-aos-duration="2000">
            <a class="featured_box" href="{{route('packageDetail',['slug'=>$package_slug])}}">
               <div class="images">
                  <img src="{{ $packageThumbSrc }}" alt="{{ $package_title }}">
               </div>
               <div class="featured_content" style="background-image: url({{asset(config('custom.assets').'/images/featured-bg.jpg') }});">
                  <div class="title heading3">{{ $package_title }}</div>
                  <div class="duration">{{ $package_duration }}</div>
                  <p class="para_md">{{ $package_brief }}</p>
                  <div class="view_all">View Detail</div>
               </div>
            </a>
         </li>
         <?php } ?>
      </ul>
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