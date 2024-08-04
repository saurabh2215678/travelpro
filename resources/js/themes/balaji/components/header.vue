<template>
  <main-header-wrapper class="topmain-header" style="display:none;">
    <div class="md:container">  
      <div class="theme_header flex justify-between flex-row">
        <div class="header-logo" v-if="processing"></div>
        <Link v-else :href="websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="websiteSettings?.FRONTEND_LOGO" alt="logo" v-if="websiteSettings"></Link>
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
                <i class="iconmobile fa-solid fa-circle-user"></i>
                <span class="text-xs ml-2 whitespace-nowrap">Hello {{store.userInfo.name}}</span>
                <span v-if="this.isOnlineBooking()" class="text-xs ml-2 whitespace-nowrap balance"> Balance :{{this.showPrice(store.userInfo.balance)}}</span>
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
      <div class="top-section" :class="store.searchActive ? 'active' : ''">
        <div class="top_bar">
          <div class="top_flex">
            <!-- <ul class="social_links"></ul> -->
            <div class="search_box">
              <SearchForm />
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>

      <header class="theme_header py-0">
        <div class="header-logo" v-if="this.processing">
          <!-- <img src="../assets/images/logo.png" alt="logo"> -->
        </div>

        <Link v-else :href="store.websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="store.websiteSettings?.FRONTEND_LOGO" alt="logo"></Link>
        
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



      <div class="mb_search_flex">
        <div class="mb_serach" @click="toggleSearch"><i class="fa-solid fa-magnifying-glass"></i></div>
        <div class="user_login user_login_before">
          <template v-if="store && store.userInfo && store.userInfo.name">
            <div class="user_login user_login_logged">
              <i class="iconmobile fa-solid fa-circle-user"></i>
              <span class="text-xs ml-2 whitespace-nowrap"><!--Hello {{store.userInfo.name}}--></span>
              <span v-if="this.isOnlineBooking()" class="text-xs ml-2 whitespace-nowrap balance"> Balance :{{this.showPrice(store.userInfo.balance)}}</span>
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
      </div>

      </header>

    </div>
  </main-header-wrapper>
</template>
<script>
import {showPrice, isOnlineBooking} from '../utils/commonFuntions.js';
import SearchForm from '../components/SearchForm.vue';
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
& header{z-index:9;}
& ul#search_activities_ul, ul#search_hotels_ul{z-index: 999999999;position: absolute;width: 100%;color: #000;}
& .form-floating .form-control:focus, .form_control:focus, .form-control:focus{border:0;}
& .sub-menu .theme-nav-li>a+ul {
    position: absolute;
    right: -100%;
    top: 100%;
    width: 100%;
    display: none;
    border-bottom: 0px solid #f2f2f2;
    border-radius: 0 0 3px 3px;
    top: 0;
    background-color: var(--theme-color);
    padding: 0;
}
& .sub-menu .theme-nav-li:hover>a+ul {display: block;}
& .searchbtn button{background: #c72556;border: 1px solid #c72556;border-radius: 50%;height: 48px!important;max-height: 48px;padding: 0;text-transform: none;width: 48px;}
& .theme-nav>li>a{padding:0 .6rem;}
& .main-header{box-shadow: 0 2px 12px #00000042;}

@media (max-width: 767px){
  & .sub-menu .theme-nav-li:hover>a+ul{position:static;margin-left:1rem;}
  & .theme-nav>li>a{padding: .6rem; }
  & .header-logo{top: 1rem;position:static;}
  & .theme_header{align-items:center;}
  & .theme-nav_wrap {margin-top:0;margin-left:0;}
  & .flight_page .head-search{padding: 0;}
  & .search_box {width: 100%;}
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
      searchActive: false
    }
  },
  methods:{
    showPrice,
    isOnlineBooking,
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
    toggleSearch(){
      this.searchActive = !this.searchActive
      store.searchActive = this.searchActive;
    }
  },
  components:{
    SearchForm,
    Link,
    'main-header-wrapper' : MainHeaderWrapper
  },
  props:["headerMenuList", "processing"]
}
</script>