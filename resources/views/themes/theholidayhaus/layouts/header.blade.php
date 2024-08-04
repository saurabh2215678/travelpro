<?php
   //prd(CustomHelper::getSettings('RECAPTCHA_SITE_KEY'));
     $websiteSettingsArr=CustomHelper::getSettings(['FRONTEND_LOGO','FRONTEND_LOGO_AlT_TEXT','SITE_EMAIL','WHATSAPP1','WHATSAPP2','GOOGLE_TRANSLATOR_MANAGEMENT','SALE_PHONE','SALES_EMAIL']);
      $storage = Storage::disk('public');
      $path = "settings/";
      //prd($websiteSettingsArr);
      $logoSrc = asset(config('custom.assets').'/images/footer_logo.png');
   
      if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
         $logo = $websiteSettingsArr['FRONTEND_LOGO'];
         if($storage->exists($path.$logo)){
            $logoSrc = asset('/storage/'.$path.$logo);
         }
      }
      $whatsapp1 = preg_replace("/[^0-9]/", "", $websiteSettingsArr['WHATSAPP2']??'');
      $whatsapp2 = preg_replace("/[^0-9]/", "", $websiteSettingsArr['WHATSAPP2']??'');
   
      // $topMenu = CustomHelper::getMenu('main-header-menu');
      // $menuItems = (isset($topMenu->menuParentItems))?$topMenu->menuParentItems:'';
      $menuItems = CustomHelper::getMenuParentItems('main-header-menu');
   
      // prd($menuItems);
   
      $menuItemsList = CustomHelper::getMenuForFront($menuItems, $is_parent = true, $parent_class='theme-nav', $child_class='', $child_parent_class='sub-menu', $have_child_class='has-dropdown');
      //prd($menuItemsList);
   
      $GOOGLE_TRANSLATOR_MANAGEMENT = $websiteSettingsArr['GOOGLE_TRANSLATOR_MANAGEMENT'] ?? 0 ;
      $FRONTEND_LOGO_AlT_TEXT = $websiteSettingsArr['FRONTEND_LOGO_AlT_TEXT'] ?? '';
         
      $current_url = url()->current();
      $segments1 = request()->segment(1);
      $flight_active = ($current_url==route('flight.index')) ? True : False;
      $cab_active = ($current_url==route('cab.index')) ? True : False;
      $bike_active = ($current_url==route('bikeListing')) ? True : False;
      $hotel_active = ($current_url==route('hotelListing') || (!empty($segments1) && stripos(route('hotelDetail',['slug'=>'slug']), $segments1) !== false)) ? True : False;
      $package_active = ($current_url==route('packageListing') || (stripos($current_url, 'tour-packages') !== false)) ? True : False;
      $activity_active = ($current_url==route('activityListing') || (stripos($current_url, 'tour-activities') !== false) ) ? True : False;   

      $userId = Auth::user()->id??'';
      $balance = App\Models\UserWallet::where('user_id',$userId)->sum('amount');

      $is_agent = Auth::user()->is_agent??'';
      $name = '';
      if($is_agent == 1 && CustomHelper::isAllowedModule('agentuser')){
         $userDetails = App\Models\User::where('id',$userId)->first();
            $name = !empty($userDetails->agentInfo)?$userDetails->agentInfo->company_brand:''; 
      }else{
         $name = Auth::user()->name??'';
      }
      $online_booking = CustomHelper::isOnlineBooking();
      ?>
<div class="topmain-header">
   <div class="container">
      <div class="theme_header flex justify-between flex-row">
         <a href="{{ url('/') }}" class="header-logo"><img src="{{ $logoSrc }}" alt="{!! $FRONTEND_LOGO_AlT_TEXT !!}"></a>
         <!--Top Menu -->
         <div class="theme_nav_search flex items-center">
         <ul class="contact_info">
                  @if(isset($websiteSettingsArr['SALE_PHONE']) && !empty($websiteSettingsArr['SALE_PHONE']))
                  <li class="px-2.5"><span>Call Now</span> <a href="tel:{{$websiteSettingsArr['SALE_PHONE'] ?? ''}}"><i class="fa-solid fa-phone"></i> {{$websiteSettingsArr['SALE_PHONE'] ?? ''}} </a></li>
                  @endif
                  @if(isset($websiteSettingsArr['SALES_EMAIL']) && !empty($websiteSettingsArr['SALES_EMAIL']))
                  <li class="px-2.5"><span>Email US</span>
                     <a href="mailto:{{$websiteSettingsArr['SALES_EMAIL'] ?? ''}}"><i class="fa-solid fa-envelope"></i> 
                     {{$websiteSettingsArr['SALES_EMAIL'] ?? ''}}
                     </a>
                  </li>
                  @endif
                 
               </ul>
            @if(!Auth::check())
            <div class="user_login user_login_before">
               <a href="{{route('account.login')}}" class="btnuser">
                  <i class="iconmobile fa fa-user"></i> My Account <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>-->
               </a>
            </div>
            @else
            <div class="user_login user_login_logged">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                  <path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
               </svg>
               <span class="text-base ml-2 whitespace-nowrap">Hello {{$name}}</span>
                  @if($online_booking)
                  <span class="text-base  ml-2 whitespace-nowrap balance"> Balance :{{CustomHelper::getPrice($balance)}}</span>
                  @endif
               <ul class="user_login_list">
                  @if($online_booking)
                  <li><a href="{{route('user.mybooking')}}">My Booking</a> </li>
                  <li><a href="{{route('user.myWallet')}}">My Wallet</a> </li>
                  @endif
                  <li><a href="{{route('user.profile')}}">My Profile</a> </li>
                  <li><a href="{{route('user.myfavorite')}}">My Favorite</a> </li>
                  <li><a href="{{route('user.logout')}}">Logout</a> </li>
               </ul>
            </div>
            @endif
         </div>
      </div>
   </div>
</div>



<div class="main-header1">
   <div class="full_header">
      <header class="theme_header">
      <div class="container">
         <!-- <a href="{{ url('/') }}" class="header-logo"><img src="{{ $logoSrc }}" alt="{!! $FRONTEND_LOGO_AlT_TEXT !!}"></a> -->
         <!--Mobile Menu -->
         <!-- <div class="mobile_menu_box">
            <div class="header-backdroap"></div>
            <div class="phone-menu">
               <span></span>
               <span></span>
               <span></span>
            </div>
            {!! $menuItemsList !!}
          </div> -->
         <!--Mobile Menu -->
         <div class="theme_nav_search flex items-center justify-center">
            {!! $menuItemsList !!}
            @if(!Auth::check())
            
            @else
            
            @endif
         </div>
         </div>
      </header>
   </div>
</div>


