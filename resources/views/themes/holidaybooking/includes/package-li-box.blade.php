<?php
$storage = Storage::disk('public');
$package_path = 'packages/';
foreach ($packages as $package) {
   $package_id = $package->id;
   $package_destination = $package->packageDestination??'';
   $destination_name = $package_destination->name??'';
   $package_title = CustomHelper::wordsLimit($package->title);
   $package_brief = CustomHelper::wordsLimit($package->brief);
   $package_slug = $package->slug;
   $package_image = $package->image;
   $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
   if(!empty($package_image)) {
      if($storage->exists($package_path.$package_image)){
         $packageThumbSrc = asset('/storage/'.$package_path.$package_image);
      }
   }
   $isDefaultPackagePrice = $package->packagePrices->first()??[];
   $final_amount = (int)$package->search_price??0;
   $sub_total_amount = $final_amount;
   /*if($isDefaultPackagePrice) {
      $sub_total_amount = $isDefaultPackagePrice->sub_total_amount??0;
      $final_amount = $isDefaultPackagePrice->final_amount??0;
   }*/
   $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';
   ?>
   <li class="swiper-slide" >
      <a class="package_card" href="{{route($packageDetailName,['slug'=>$package_slug])}}">
         <div class="top_image_box">
            <img src="{{$packageThumbSrc}}" alt="{{$package_title}}" />
            <div class="featured_content py-4 px-2 destination_type">
               <div class="title text-sm"> {{ $destination_name }}</div>
            </div>
         </div>
         <div class="package_content">
            <h3 class="font20">{{ $package_title }}</h3>
   <div class="minimal_detail">
      <div class="m_detail_left">
         <?php
         if($package->is_activity == 0 && ($package->days > 0 || $package->nights > 0)) {
            $pack_day_only="";
            $pack_nights_only="";
            if($package->days > 0 && $package->nights == 0){
               $pack_day_only="pack_day_only";
               $pack_nights_only="";   
            }
            else if($package->days == 0 && $package->nights > 0)
            {
               $pack_day_only="";
               $pack_nights_only="pack_day_only";  
            }
         ?>
         <div class="pack_day_night">
            <i class="fa-regular fa-clock"></i>
            <span class="<?php echo $pack_nights_only;?>"><?php if($package->nights > 0){ if($package->nights > 0){echo ''; } echo $package->nights.'N'; } ?></span> 
            <span class="mx-1">|</span>
            <span class="<?php echo $pack_day_only;?>"><?php if($package->days > 0){ echo $package->days.'D'; } ?></span>
         </div>
         <?php }else{ ?>
            <div class="pack_day_night static_item">
               <i class="fa-regular fa-clock"></i>
               <span>2N</span> 
               <span class="mx-1">|</span>
               <span>3N</span>
            </div>
         <?php } ?>
        <?php /* <div class="rating">
            <img src="{{ asset(config('custom.assets').'/images/rating_stars.png') }}" alt=""> <span>(2)</span>
         </div> */ ?>
      </div>


      @if(!empty($final_amount))
         <div class="m_detail_right">
            <p class="font14">From</p>
            <span class="amount heading_sm3"> 
            <!-- <img src="{{ asset(config('custom.assets').'/images/rupree_symbol.png') }}" alt="">     -->
            <?php echo ($sub_total_amount == $final_amount) ? "" : "<small class='old_price'>".CustomHelper::getPrice($sub_total_amount)."</small>"; ?>{{CustomHelper::getPrice($final_amount)}} </span>
            <small>{{$priceCategoryDetails->name??''}}</small>
         </div>
      @endif

   </div>


         </div>
      </a>
      <!-- @if(auth()->check())
      <span id="favid-{{ $package_id }}" class="pkg_fav pkg_fav_{{ $package_id }}" >Favourite</span>
      @endif -->
   </li>
<?php } ?>