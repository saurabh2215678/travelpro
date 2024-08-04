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
$hotel_active = ($current_url==route('hotelListing') || (!empty($segments1) && stripos(route('hotelDetail',['slug'=>'slug']), $segments1) !== false)) ? True : False;
$package_active = ($current_url==route('packageListing') || (stripos($current_url, 'tour-packages') !== false)) ? True : False;
$activity_active = ($current_url==route('activityListing') || (stripos($current_url, 'tour-activities') !== false) ) ? True : False;   

$userId = Auth::user()->id??'';
$balance = App\Models\UserWallet::where('user_id',$userId)->sum('amount');

$is_agent = Auth::user()->is_agent??'';
if($is_agent == 1){
   $userDetails = App\Models\User::where('id',$userId)->first();
   $name = !empty($userDetails->agentInfo)?$userDetails->agentInfo->company_brand:'';
}else{
   $name = Auth::user()->name??'';
}
?>
<div class="topmain-header" style="display:none;">
   <div class="md:container">
      <div class="theme_header flex justify-between flex-row">
         <a href="{{ url('/') }}" class="header-logo"><img src="{{ $logoSrc }}" alt="{!! $FRONTEND_LOGO_AlT_TEXT !!}"></a>
         <!--Top Menu -->
         <div class="theme_nav_search flex items-center">
            <ul class="menu_list">
            @if(CustomHelper::isAllowedModule('flight'))
            <li>
              <a href="{{route('flight.index')}}" <?php if($flight_active){ echo 'class="active"';}?> ><i class="fa fa-plane"></i> Flight</a>
            </li>
            @endif
            @if(CustomHelper::isAllowedModule('cab'))
            <li>
              <a href="{{route('cab.index')}}" <?php if($cab_active){ echo 'class="active"';}?> ><i class="fa fa-taxi"></i> Cab</a>
            </li>
            @endif
            <li>
              <a href="{{route('packageListing')}}" <?php if($package_active){ echo 'class="active"';}?> > <i class="fa fa-suitcase"></i> Holiday Packages</a>
            </li>
            <li>
              <a href="{{route('activityListing')}}" <?php if($activity_active){ echo 'class="active"';}?> ><img class="icon_img" src="{{asset(config('custom.assets').'/images/activities-icon.png')}}" alt="Short Activities"> Short Activities</a>
            </li>
            <li>
              <a href="{{route('hotelListing')}}" <?php if($hotel_active){ echo 'class="active"';}?> ><i class="fa fa-building-o"></i> Hotels</a>
            </li>
            <li class="menulist"> <a href="#"> <i class="fa fa-bars"></i> More</a> <div class="more-menu"> {!!$menuItemsList!!} </div>
            </li>
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
               <span class="text-xs ml-2 whitespace-nowrap">Hello {{$name}}</span>
               
                  <span class="text-xs ml-2 whitespace-nowrap balance"> Balance :{{CustomHelper::getPrice($balance)}}</span>
               <ul class="user_login_list">
                  <li><a href="{{route('user.mybooking')}}">My Booking</a> </li>
                  <li><a href="{{route('user.myWallet')}}">My Wallet</a> </li>
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
   <div class="md:container">
      <div class="top-section" style="display:none;">
         <div class="top_bar">
            <div class="top_flex">
               <ul class="social_links">
                  <a href="{{ url('/') }}" class="header-logo w-1/2"><img src="{{ $logoSrc }}" alt="{!! $FRONTEND_LOGO_AlT_TEXT !!}"></a>
               </ul>
               <ul class="contact_info">
                  <li class="px-2.5	">
                     <!-- <i class="phoneicon"></i> --> <a href="tel:+91 8491947052"><span><strong>Ticketing:</strong> +91 8491947052</span></a>
                  </li>
                  <li class="px-2.5	"><a href="tel:+91 9858394405"><span><strong>Tour &amp; Trek:</strong> +91 9858394405 </span></a></li>
                  @if(isset($websiteSettingsArr['SALE_PHONE']) && !empty($websiteSettingsArr['SALE_PHONE']))
                  <li class="px-2.5	"><a href="tel:{{$websiteSettingsArr['SALE_PHONE'] ?? ''}}"><span><strong>Hotels:</strong>{{$websiteSettingsArr['SALE_PHONE'] ?? ''}} </span></a></li>
                  @endif
                  @if(isset($websiteSettingsArr['SALES_EMAIL']) && !empty($websiteSettingsArr['SALES_EMAIL']))
                  <li class="px-2.5	">
                     <a href="mailto:{{$websiteSettingsArr['SALES_EMAIL'] ?? ''}}">
                        <!-- <i class="mailicon"></i>  --><span> {{$websiteSettingsArr['SALES_EMAIL'] ?? ''}}</span>
                     </a>
                  </li>
                  @endif
                  <?php if($GOOGLE_TRANSLATOR_MANAGEMENT=='1'){?>
                  <li class="px-2.5  google_translator">
                     <div class="lang-converter">
                        <div id="google_translate_element"></div>
                        <script type="text/javascript">
                           function googleTranslateElementInit() {
                              new google.translate.TranslateElement({
                                 pageLanguage: 'en'
                              }, 'google_translate_element');
                           }
                        </script>
                        <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                     </div>
                  </li>
                  <?php }?>
               </ul>
            </div>
            <div class="clearfix"></div>
         </div>
      </div>
      <header class="theme_header">
         <a href="{{ url('/') }}" class="header-logo"><img src="{{ $logoSrc }}" alt="{!! $FRONTEND_LOGO_AlT_TEXT !!}"></a>
         <!--Mobile Menu -->
         <div class="mobile_menu_box">
            <div class="header-backdroap"></div>
            <div class="phone-menu">
               <span></span>
               <span></span>
               <span></span>
            </div>
            {!! $menuItemsList !!}
          </div>
         <!--Mobile Menu -->
         <div class="theme_nav_search flex items-center">
            {!! $menuItemsList !!}
            <!-- <div class="search_icon"><a href="{{route('packageListing')}}">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"/>
               </svg></a>
               </div> -->
            @if(!Auth::check())
            <div class="user_login user_login_before">
               <a href="{{route('account.login')}}" class="btnuser">
                  <i class="iconmobile fa fa-user-circle-o"></i> My Account <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>-->
               </a>
                  
            </div>
            @else
            <div class="user_login user_login_logged">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                  <path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
               </svg>
               
               <span class="text-xs ml-2 whitespace-nowrap">Hello {{$name}}</span>
               <span class="text-xs ml-2 whitespace-nowrap balance"> Balance : {{CustomHelper::getPrice($balance)}}</span>
               <ul class="user_login_list">
                  <li><a href="{{route('user.mybooking',['todays_orders'=>'todays_orders'])}}">My Booking</a> </li>
                  <li><a href="{{route('user.myWallet')}}">My Wallet</a> </li>
                  <li><a href="{{route('user.profile')}}">My Profile</a> </li>
                  <li><a href="{{route('user.myfavorite')}}">My Favorite</a> </li>
                  <li><a href="{{ route('user.logout') }}">Logout</a> </li>
               </ul>
            </div>
            @endif
         </div>
      </header>
   </div>
</div>


