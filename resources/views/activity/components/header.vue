<template>
    <div class="topmain-header" style="display:none;">
      <div class="md:container">  
       <div class="theme_header flex justify-between flex-row">
           <div class="header-logo" v-if="this.processing">
               <!-- <div class="w-40 p-4 skeleton_loading"></div> -->
           </div>
           <a v-else :href="websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="websiteSettings?.FRONTEND_LOGO" alt="logo" v-if="websiteSettings"></a>
           <div class="theme_nav_search flex items-center">
           <ul class="menu_list" v-if="websiteSettings">
            <li v-if="websiteSettings.FLIGHT_URL"><a :href="websiteSettings?.FLIGHT_URL"><i class="fa fa-plane"></i> Flight</a></li>
            <li v-if="websiteSettings.CAB_URL"><a :href="websiteSettings?.CAB_URL" class="active"><i class="fa fa-taxi"></i> Cab</a></li>
            <li v-if="websiteSettings.PACKAGE_URL"> <a :href="websiteSettings?.PACKAGE_URL"> <i class="fa fa-suitcase"></i> Holiday Packages</a></li>
            <li v-if="websiteSettings.ACTIVITY_URL"><a :href="websiteSettings?.ACTIVITY_URL"><i class="fa-solid fa-person-running"></i> Short Activities</a></li>
            <li v-if="websiteSettings.HOTEL_URL"> <a :href="websiteSettings?.HOTEL_URL"><i class="fa-solid fa-hotel"></i> Hotels</a></li>
            <li class="menulist"> <a href="#"> <i class="fa fa-bars"></i> More</a> <div class="more-menu" v-html="headerMenuList" ></div></li>
          </ul>
   
          <div class="user_login user_login_before">
            <template v-if="store && store.userInfo && store.userInfo.name">
              <div class="user_login user_login_logged">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                  <path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
               </svg>
               <span class="text-xs text-white ml-2 whitespace-nowrap">Hello {{store.userInfo.name}}</span>
               
                <span class="text-xs text-white ml-2 whitespace-nowrap balance"> Balance :{{this.showPrice(store.userInfo.balance)}}</span>
               <ul class="user_login_list">
                  <li><a :href="store.userInfo.MY_BOOKING_URL">My Booking</a> </li>
                  <li><a :href="store.userInfo.MY_WALLET_URL">My Wallet</a> </li>
                  <li><a :href="store.userInfo.MY_PROFILE_URL">My Profile</a> </li>
                  <li><a :href="store.userInfo.MY_FAVOURITE_URL">My Favorite</a> </li>
                  <li><a :href="store.userInfo.LOGOUT_URL">Logout</a> </li>
               </ul>
            </div>
            </template>
            <template v-else>
              <a :href="websiteSettings?.FRONTEND_LOGIN_URL" class="btn"> <i class="iconmobile fa-solid fa-circle-user"></i>  My Account <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"></path></svg>--></a>
            </template>
           </div>
           </div>
        </div>
       
   </div>
   </div>
    
   
   
   
   <div class="main-header">
      <div class="md:container">  
       <div class="top-section" style="display:none;">
           <div class="top_bar">
            <div class="top_flex">
               <ul class="social_links"></ul>
               <ul class="contact_info">
                   <li class="px-2.5"><a href="tel:+91 8491947052"><span><strong>Ticketing:</strong> +91 8491947052</span></a></li>
                   <li class="px-2.5"><a href="tel:+91 9858394405"><span><strong>Tour &amp; Trek:</strong> +91 9858394405 </span></a></li>
                   <li class="px-2.5" v-if="websiteSettings?.SALES_PHONE"><a :href="`tel:${websiteSettings?.SALES_PHONE}`"><span><strong>Hotels:</strong>{{websiteSettings?.SALES_PHONE}} </span></a></li>
                   
                   <li class="px-2.5" v-if="websiteSettings?.SALES_EMAIL">
                       <div class="w-48 p-2 mt-3 skeleton_loading" v-if="this.processing"></div>
                       <a v-else :href="`mailto:${websiteSettings?.SALES_EMAIL}`"><span> {{websiteSettings?.SALES_EMAIL}}</span></a>
                   </li>
   
                   <li class="px-2.5 google_translator">
                       <div class="lang-converter">
                           
                       </div>
                   </li>
               </ul>
            </div>
           <div class="clearfix"></div>
           </div>
       </div>

       <header class="theme_header py-0">
           <div class="header-logo" v-if="this.processing">
            <!-- <div class="w-40 p-4 skeleton_loading"></div> -->
            <img src="https://www.overlandescape.com/storage/settings/090523013516-290323043219-overlandescape-logo.png" alt="logo">
           </div>
           <a v-else :href="websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="websiteSettings?.FRONTEND_LOGO" alt="logo" v-if="websiteSettings"></a>
           <div class="theme-nav_wrap" v-if="this.processing">
                <!-- <div class="theme-nav">
                   <div class="w-40 ml-2 p-2 skeleton_loading"></div>
                   <div class="w-40 ml-2 p-2 skeleton_loading"></div>
                   <div class="w-40 ml-2 p-2 skeleton_loading"></div>
                   <div class="w-40 ml-2 p-2 skeleton_loading"></div>
                </div> -->
                <ul class="theme-nav">
                    <li class=""><a href="javascript:void(0);">Home</a></li>
                    <li class=""><a href="javascript:void(0);">Company</a></li>
                    <li class=""><a href="javascript:void(0);">Destination Guides</a></li>
                    <li class=""><a href="javascript:void(0);">Contact Us</a></li>
                    <li class=""><a href="javascript:void(0);">Pay Online</a></li>
                </ul>
           </div>
           <div class="theme-nav_wrap" v-html="headerMenuList" v-else></div>
   
           <!-- <div class="theme_nav_search">
               <div class="search_icon"><a :href="websiteSettings?.FRONTEND_PACKAGE_LISTING_URL">
                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                       <path d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"></path>
                   </svg></a>
               </div>
           </div> -->
    
           <div class="user_login user_login_before">
            <template v-if="store && store.userInfo && store.userInfo.name">
              <div class="user_login user_login_logged">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                  <path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
               </svg>
               <span class="text-xs text-white ml-2 whitespace-nowrap">Hello {{store.userInfo.name}}</span>
               
                <span class="text-xs text-white ml-2 whitespace-nowrap balance"> Balance :{{this.showPrice(store.userInfo.balance)}}</span>
               <ul class="user_login_list">
                  <li><a :href="store.userInfo.MY_BOOKING_URL">My Booking</a> </li>
                  <li><a :href="store.userInfo.MY_WALLET_URL">My Wallet</a> </li>
                  <li><a :href="store.userInfo.MY_PROFILE_URL">My Profile</a> </li>
                  <li><a :href="store.userInfo.MY_FAVOURITE_URL">My Favorite</a> </li>
                  <li><a :href="store.userInfo.LOGOUT_URL">Logout</a> </li>
               </ul>
            </div>
            </template>
            <template v-else>
              <a :href="websiteSettings?.FRONTEND_LOGIN_URL" class="btn"> <i class="iconmobile fa-solid fa-circle-user"></i>  My Account <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"></path></svg>--></a>
            </template>
           </div>
   
       </header>
       
   </div>
   </div>
   </template>
   <script>
   import {showPrice} from '../utils/commonFuntions.js';
   import { store } from '../store';
   export default {
       name:"Header",
       data(){
           return{
            store,
            showPrice,            
           }
       },
       components:{
       },
       props:["headerMenuList","websiteSettings", 'processing']
   }
   </script>