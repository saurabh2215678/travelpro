<template>
  <main-header-wrapper class="topmain-header" style="display:none;">
    <div class="md:container">  
      <div class="theme_header flex justify-between flex-row">
        <div class="header-logo" v-if="processing">
        </div>
        <a v-else :href="websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="websiteSettings?.FRONTEND_LOGO" alt="logo" v-if="websiteSettings"></a>
        <div class="theme_nav_search flex items-center">
          <ul class="menu_list" v-if="websiteSettings">
            <li v-if="websiteSettings.FLIGHT_URL"><Link :href="websiteSettings?.FLIGHT_URL"><i class="fa fa-plane"></i> Flight</Link></li>
            <li v-if="websiteSettings.PACKAGE_URL"> <Link :href="websiteSettings?.PACKAGE_URL"> <i class="fa fa-suitcase"></i> Holiday Packages</Link></li>
            <li v-if="websiteSettings.ACTIVITY_URL"><Link :href="websiteSettings?.ACTIVITY_URL"><i class="fa-solid fa-person-running"></i> Activities</Link></li>
            <li v-if="websiteSettings.HOTEL_URL"> <Link :href="websiteSettings?.HOTEL_URL"><i class="fa-solid fa-hotel"></i> Hotels</Link></li>
            <li v-if="websiteSettings.CAB_URL"><Link :href="websiteSettings?.CAB_URL" class="active"><i class="fa fa-taxi"></i> Cab</Link></li>
            <li v-if="websiteSettings.CAB_URL"><Link :href="websiteSettings?.CAB_URL" class="active"><i class="fa-solid fa-motorcycle"></i> Rental</Link></li>
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
              <a :href="store.websiteSettings?.FRONTEND_LOGIN_URL">My Account</a>
              <!-- for mobile signin button -->
              <a :href="store.websiteSettings?.FRONTEND_LOGIN_URL" class="mob_signin"><i class="fa-solid fa-user"></i></a>
            </template>
          </div>
        </div>
      </div>

    </div>
  </main-header-wrapper>
  <main-header-wrapper class="main-header">
    <div class="md:container">  
      <header class="theme_header py-0">
        <div class="header-logo" v-if="processing">
          <!-- <img src="../assets/images/logo.png" alt="logo"> -->
        </div>
        <a v-else :href="store.websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="store.websiteSettings?.FRONTEND_LOGO" alt="logo"></a>
        <div class="theme-nav_wrap" v-if="processing">
          <ul class="theme-nav">
            <li class=""><a href="javascript:void(0);">Home</a></li>
            <li class=""><a href="javascript:void(0);">Company</a></li>
            <li class=""><a href="javascript:void(0);">Destination Guides</a></li>
            <li class=""><a href="javascript:void(0);">Contact Us</a></li>
            <li class=""><a href="javascript:void(0);">Pay Online</a></li>
          </ul>
        </div>
        <div class="theme-nav_wrap" v-html="headerMenuList" v-else></div>

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
            <a :href="store.websiteSettings?.FRONTEND_LOGIN_URL" class="pr-2">My Account</a>
              <!-- for mobile signin button -->
              <a :href="store.websiteSettings?.FRONTEND_LOGIN_URL" class="mob_signin"><i class="fa-solid fa-user"></i></a>
          </template>
        </div>

      </header>

    </div>
  </main-header-wrapper>
</template>
<script>
import {showPrice} from '../utils/commonFuntions.js';
import { store } from '../store';
import { Link } from "@inertiajs/inertia-vue3";
import styled from 'vue3-styled-components';

const MainHeaderWrapper = styled.div`
.headerType2 &.topmain-header{
  display:none!important;
}
.headerType2 &.main-header{
  display:block;
}


`;


export default {
  name:"Header",
  created() {
    let currentObj = this;
    setTimeout(function(){
      currentObj.showActive();
    },500);
  },
  
  data() {
    return {
      store,
      showPrice,            
    }
  },

  methods:{
    showActive() {
      var currentUrl = window.location.href;
      var headerLinks = document.querySelectorAll('header .theme-nav > li a');

      headerLinks.forEach(function(link) {
        var anchorUrl = link.getAttribute('href');
          if (anchorUrl === currentUrl) {
            link.closest('li').classList.add('active');
         }
      });      
    },
  },

  components:{
    Link,
    'main-header-wrapper' : MainHeaderWrapper
  },

  props:["headerMenuList", "processing"]
}
</script>