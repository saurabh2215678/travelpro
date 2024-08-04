<?php
$storage = Storage::disk('public');
$package_path = 'packages/';
foreach ($packages as $package) {

   $package_tour_type = $package->tour_type??'';
   if(empty($package_tour_type)) {
      $package_tour_type = 'fixed';
   }

   if($package->is_activity==0) {
      if(isset($package_tour_type) && $package_tour_type == 'group') {
         $package_tour_type_text = "Group Package";
      } elseif (isset($package_tour_type) && ($package_tour_type == 'fixed' || $package_tour_type == 'open')) {
         $package_tour_type_text = "Flexi Package";
      } else {
         $package_tour_type_text = "Flexi Package";
      }
   } else {
      $package_tour_type_text = "Activity Package";
   }
   $package_id = $package->id;
   $package_title = CustomHelper::wordsLimit($package->title);
   $package_slug = $package->slug;
   $package_image = $package->image;
   $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
   if(!empty($package_image)) {
      if($storage->exists($package_path.$package_image)){
         $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package_image);
      }
   }
   // $isDefaultPackagePrice = $package->packagePrices->first()??[];
   $final_amount = $package->search_price??0;
   $sub_total_amount = $final_amount;
   $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';
   ?>
   <li class="swiper-slide">
      <div class="featured_box">
      <span class="package_tour_type_text pb-1"> {{$package_tour_type_text ?? ''}}</span>
         <a class="featured_content " href="{{route($packageDetailName,['slug'=>$package_slug])}}" >
         <div class="images">
           <img src="{{$packageThumbSrc}}" alt="{{$package_title}}" />
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
               <span class="<?php echo $pack_nights_only;?>"><?php if($package->nights > 0){ if($package->nights > 0){echo ''; } echo $package->nights.'N'; } ?></span>
               <span class="<?php echo $pack_day_only;?>"><?php if($package->days > 0){ echo $package->days.'D'; } ?></span>
            </div>
            <?php } ?>
            
         </div>
         </a>
            @if(auth()->check())
            <div class="wishlist_btn">
               <span id="favid-{{ $package_id }}" class="pkg_fav pkg_fav_{{ $package_id }} {{ (isset($package_fab[$package_id])) ? 'pkg_fav_clicked liked_btn' : 'pkg_fav' }} " >
                  <img class="wishlist" src="{{asset(config('custom.assets').'/images/wishlist.png') }}" alt="Wishlist">
                  <img class="wishlist_active" src="{{asset(config('custom.assets').'/images/wishlist-active.png') }}" alt="Wishlist">
               </span>
            </div>
            @endif
             <a class="featured_content px-1.5 py-3.5" href="{{route($packageDetailName,['slug'=>$package_slug])}}" >            
            <div class="title text-sm">
               {{ $package_title }}</div>
        
            @if(!empty($final_amount))
            <div class="price">
               <p class="text-xs "> Starting From : <span class="amount heading_sm3"><?php echo ($sub_total_amount == $final_amount) ? "" : "<small class='old_price'>".CustomHelper::getPrice($sub_total_amount)."</small>"; ?>{{CustomHelper::getPrice($final_amount)}}</span></p>
               <small>{{$priceCategoryDetails->name??''}}</small>
            </div>
            @endif
         </a>
      </div>
   </li>
<?php } ?>