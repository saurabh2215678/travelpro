<template>
  <main-header-wrapper class="topmain-header" style="display:none;">
    <div class="md:container">  
      <div class="theme_header flex justify-between flex-row">
        <div class="header-logo" v-if="processing"></div>
        <Link v-else :href="store.websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="store.websiteSettings?.FRONTEND_LOGO" alt="logo" v-if="store.websiteSettings"></Link>
        <div class="theme_nav_search flex items-center">
          <ul class="menu_list" v-if="store.websiteSettings">
            <li v-if="store.websiteSettings?.FLIGHT_URL"><Link :href="store.websiteSettings?.FLIGHT_URL"><i class="fa fa-plane"></i> Flight</Link></li>
            <li v-if="store.websiteSettings?.PACKAGE_URL"> <Link :href="store.websiteSettings?.PACKAGE_URL"> <i class="fa fa-suitcase"></i> Holiday Packages</Link></li>
            <li v-if="store.websiteSettings?.ACTIVITY_URL"><Link :href="store.websiteSettings?.ACTIVITY_URL"><i class="fa-solid fa-person-running"></i> Activities</Link></li>
            <li v-if="store.websiteSettings?.HOTEL_URL"> <Link :href="store.websiteSettings?.HOTEL_URL"><i class="fa-solid fa-hotel"></i> Hotels</Link></li>
            <li v-if="store.websiteSettings?.CAB_URL"><Link :href="store.websiteSettings?.CAB_URL" class="active"><i class="fa fa-taxi"></i> Cab</Link></li>
            <li v-if="store.websiteSettings?.CAB_URL"><Link :href="store.websiteSettings?.CAB_URL" class="active"><i class="fa-solid fa-motorcycle"></i> Rental</Link></li>
            <li class="menulist"> <a href="#"> <i class="fa fa-bars"></i> More</a> <div class="more-menu" v-html="headerMenuList" ></div></li>
          </ul>

          <div class="user_login user_login_before">
            <template v-if="store && store.userInfo && store.userInfo.name">
              <div class="user_login user_login_logged">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                  <path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                </svg>
                <span class="text-xs text-white ml-2 whitespace-nowrap">Hello {{store.userInfo.name}}</span>
                <span v-if="this.isOnlineBooking()" class="text-xs text-white ml-2 whitespace-nowrap balance"> Balance :{{showPrice(store.userInfo.balance)}}</span>
                <ul class="user_login_list">
                  <li v-if="this.isOnlineBooking()"><a :href="store.userInfo.MY_BOOKING_URL">My Booking</a> </li>
                  <li v-if="this.isOnlineBooking()"><a :href="store.userInfo.MY_WALLET_URL">My Wallet</a> </li>
                  <li><a :href="store.userInfo.MY_PROFILE_URL">My Profile</a> </li>
                  <li><a :href="store.userInfo.MY_FAVOURITE_URL">My Favorite</a> </li>
                  <li><a :href="store.userInfo.LOGOUT_URL">Logout</a> </li>
                </ul>
              </div>
            </template>
            <template v-else>
              <a :href="websiteSettings?.FRONTEND_LOGIN_URL" class="btn"> <i class="iconmobile fa-solid fa-circle-user"></i>  My Account </a>
            </template>
          </div>
        </div>
      </div>

    </div>
  </main-header-wrapper>
  <main-header-wrapper class="main-header">
    <div class="md:container">  
      <!-- <div class="top-section" style="display:none;">
        <div class="top_bar">
          <div class="top_flex">
            <ul class="social_links"></ul>
            <ul class="contact_info">
              <li class="px-2.5"><a href="tel:+91 8491947052"><span><strong>Ticketing:</strong> +91 8491947052</span></a></li>
              <li class="px-2.5"><a href="tel:+91 9858394405"><span><strong>Tour &amp; Trek:</strong> +91 9858394405 </span></a></li>
              <li class="px-2.5" v-if="store.websiteSettings?.SALES_PHONE"><a :href="`tel:${store.websiteSettings?.SALES_PHONE}`"><span><strong>Hotels:</strong>{{store.websiteSettings?.SALES_PHONE}} </span></a></li>

              <li class="px-2.5" v-if="store.websiteSettings?.SALES_EMAIL">
                <div class="w-48 p-2 mt-3 skeleton_loading" v-if="processing"></div>
                <a v-else :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`"><span> {{store.websiteSettings?.SALES_EMAIL}}</span></a>
              </li>

              <li class="px-2.5 google_translator">
                <div class="lang-converter">

                </div>
              </li>
            </ul>
          </div>
          <div class="clearfix"></div>
        </div>
      </div> -->

      <header class="theme_header py-0">
        <div class="header-logo" v-if="this.processing">
          <!-- <img src="../assets/images/logo.png" alt="logo"> -->
        </div>

        <Link v-else :href="store.websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="store.websiteSettings?.FRONTEND_LOGO" :alt="store.websiteSettings?.FRONTEND_LOGO_AlT_TEXT"></Link>
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

        <div class="user_login bell_icon user_login_before">
          <template v-if="store && store.userInfo && store.userInfo.name">
            <div class="topright" v-if="this.isOnlineBooking('flight') && store?.userInfo?.PENDING_BOOKING_URL">
              <ul class="top-nav">
                  <li class="dropdown">
                      <div class="parent">
                          <a :href="store?.userInfo?.PENDING_BOOKING_URL">
                              <div class="number">{{store?.userInfo?.PENDING_BOOKING_COUNT}}</div>
                              <span><i class="fa fa-bell"></i></span>
                          </a>
                      </div>
                  </li>
                  </ul>
              </div>
            <div class="user_login user_login_logged">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0S96 57.3 96 128s57.3 128 128 128zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
              </svg>
              <span class="text-xs text-white ml-2 whitespace-nowrap">Hello {{store.userInfo.name}}</span>

              <span v-if="this.isOnlineBooking()" class="text-xs text-white ml-2 whitespace-nowrap balance"> Balance :{{showPrice(store.userInfo.balance)}}</span>
              <ul class="user_login_list">
                <li v-if="this.isOnlineBooking()"><a :href="store.userInfo.MY_BOOKING_URL">My Booking</a> </li>
                <li v-if="this.isOnlineBooking()"><a :href="store.userInfo.MY_WALLET_URL">My Wallet</a> </li>
                <li><a :href="store.userInfo.MY_PROFILE_URL">My Profile</a> </li>
                <li><a :href="store.userInfo.MY_FAVOURITE_URL">My Favorite</a> </li>
                <li><a :href="store.userInfo.LOGOUT_URL">Logout</a> </li>
              </ul>
            </div>
          </template>
          <template v-else>
            <a :href="store.websiteSettings?.FRONTEND_LOGIN_URL" class="btn"> <i class="iconmobile fa-solid fa-circle-user"></i>  My Account </a>
          </template>
        </div>

      </header>

    </div>
  </main-header-wrapper>
</template>
<script>
import {showPrice, isOnlineBooking} from '../utils/commonFuntions.js';
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
  data() {
    return {
      store,
    }
  },
  methods: {
    showPrice,
    isOnlineBooking,
  },
  components:{
    Link,
    'main-header-wrapper' : MainHeaderWrapper
  },
  props:["headerMenuList", "processing"]
}
</script>