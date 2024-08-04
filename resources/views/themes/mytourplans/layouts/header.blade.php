<?php
//prd(CustomHelper::getSettings('RECAPTCHA_SITE_KEY'));
  $websiteSettingsArr=CustomHelper::getSettings(['FRONTEND_LOGO','FRONTEND_LOGO_AlT_TEXT','SITE_EMAIL','WHATSAPP1','WHATSAPP2','GOOGLE_TRANSLATOR_MANAGEMENT','SALES_PHONE','SALES_EMAIL']);
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
   $menuItemsList = CustomHelper::getMenuForFront($menuItems, $is_parent = true, $parent_class='theme-nav', $child_class='', $child_parent_class='sub-menu', $have_child_class='has-dropdown');
   //prd($menuItemsList);

   $GOOGLE_TRANSLATOR_MANAGEMENT = $websiteSettingsArr['GOOGLE_TRANSLATOR_MANAGEMENT'] ?? 0 ;
   $FRONTEND_LOGO_AlT_TEXT = $websiteSettingsArr['FRONTEND_LOGO_AlT_TEXT'] ?? '';
   $online_booking = CustomHelper::isOnlineBooking();
?>

<div class="top-section">
        <div class="top_bar">
         <div class="top_flex">
            <ul class="social_links">
           <?php /*
               <li><a href="https://www.facebook.com/Overland.Escape.India" rel="nofollow" target="_blank" class="px-3.5"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="https://twitter.com/" rel="nofollow" target="_blank" class="px-3.5"><i class="fa fa-twitter"></i></a></li>
                */ ?>
               </ul>
               <ul class="contact_info">

                  <li class="px-2.5	"><!-- <i class="phoneicon"></i> --> <a href="tel:+91 8491947052"><span><strong>Ticketing:</strong> +91 8491947052</span></a></li>
 
                  <li class="px-2.5	"><a href="tel:+91 9858394405"><span><strong>Tour &amp; Trek:</strong> +91 9858394405 </span></a></li>

                  @if(isset($websiteSettingsArr['SALES_PHONE']) && !empty($websiteSettingsArr['SALES_PHONE']))
                  <li class="px-2.5	"><a href="tel:{{$websiteSettingsArr['SALES_PHONE'] ?? ''}}"><span><strong>Hotels:</strong>{{$websiteSettingsArr['SALES_PHONE'] ?? ''}} </span></a></li>
                  @endif

                  @if(isset($websiteSettingsArr['SALES_EMAIL']) && !empty($websiteSettingsArr['SALES_EMAIL']))
                  <li class="px-2.5	"><a href="mailto:{{$websiteSettingsArr['SALES_EMAIL'] ?? ''}}"><!-- <i class="mailicon"></i>  --><span> {{$websiteSettingsArr['SALES_EMAIL'] ?? ''}}</span></a></li>
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


<header class="theme_header py-0">

   <a href="{{ url('/') }}" class="header-logo"><img src="{{ $logoSrc }}" alt="{!! $FRONTEND_LOGO_AlT_TEXT !!}"></a>
   <!--Top Menu -->
   {!! $menuItemsList !!}
   <!--Top Menu -->
   <!-- <div class="theme_nav_search">
      <div class="search_icon"><a href="{{route('packageListing')}}">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"/>
         </svg></a>
      </div>
   </div> -->
   @if(!Auth::check())
   <div class="user_login user_login_before">
      <a href="{{route('account.login')}}" class="btn"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg></a>
   </div>
   @else
   <div class="user_login user_login_logged">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
      <ul class="user_login_list">
         <li><a href="{{route('user.profile')}}">My Profile</a> </li>
         @if($online_booking)
         <li><a href="{{route('user.mybooking')}}">My Booking</a> </li>
         @endif
         <li><a href="{{route('user.myfavorite')}}">My Favorite</a> </li>
         <li><a href="{{ route('user.logout') }}">Logout</a> </li>
      </ul>
   </div>
   @endif


   <?php /*<ul class="contact_info_wrap">
      <li>
         <div class="contact_info">
            <div class="icon">
               <img src="{{ asset(config('custom.assets').'/images/email.png') }}" alt="email">
            </div>
            <div class="text">
               <a href="mailto:{{ $websiteSettingsArr['SITE_EMAIL'] }}">{{ $websiteSettingsArr['SITE_EMAIL'] }}</a>
            </div>
         </div>
      </li>
      <li>
         <div class="contact_info">
            <div class="icon">
               <img src="{{ asset(config('custom.assets').'/images/whatsapp.png') }}" alt="whatsapp">
            </div>
            <div class="text">
               <a href="https://api.whatsapp.com/send?phone={{ $whatsapp1 }}">{{ $websiteSettingsArr['WHATSAPP1'] }}</a> <a href="https://api.whatsapp.com/send?phone={{ $whatsapp2 }}">{{ $websiteSettingsArr['WHATSAPP2'] }}</a>
            </div>
         </div>
      </li>
   </ul> */ ?>
</header>