<template>
  <HeaderWrapper ref="headerRef">
      <div class="md:px-7 py-3">
        <div class="header_inner">

          <div class="header_left">
            <div class="header-logo" v-if="processing"><div class="placeholder-logo"></div></div>
            <Link v-else :href="store.websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="store.websiteSettings?.FRONTEND_LOGO" :alt="store.websiteSettings?.FRONTEND_LOGO_AlT_TEXT" v-if="store.websiteSettings" preload></Link>
          </div>

          <div class="header_right">

            <div class="menu_backdrop" @click="closeSideMenu"></div>
            <div class="menu_toggle" @click="toggleSideMenu">
              <span></span>
              <span></span>
              <span></span>
            </div>
 
            <div class="header_menus">
              <!-- <div class="header_top">
                <ul class="contact_info">
                  <li v-if="store?.websiteSettings?.SITE_PHONE1" v-html="store?.websiteSettings?.SITE_PHONE1" class="top_phone"></li>
                  <li v-if="store?.websiteSettings?.SALES_PHONE" class="top_phone">
                    <a :href="'tel:'+store?.websiteSettings?.SALES_PHONE">{{store?.websiteSettings?.SALES_PHONE}}</a>
                  </li>
                  <li v-if="store?.websiteSettings?.SITE_EMAIL" class="top_email">
                    <a :href="'mailto:'+store?.websiteSettings?.SITE_EMAIL">{{store?.websiteSettings?.SITE_EMAIL}}</a>
                  </li>

                  <li>
                    <Link href="/contact-us">Contact Us</Link>
                  </li>
                  <li class="last_options">
                    <UserLoginOptions v-if="store && store.userInfo && store.userInfo.name"/>
                    <template v-else>
                      <Link :href="store.websiteSettings?.FRONTEND_LOGIN_URL">Sign in</Link>
                      for mobile signin button
                      <Link :href="store.websiteSettings?.FRONTEND_LOGIN_URL" class="mob_signin"><i class="fa-solid fa-user"></i></Link>
                    </template>
                  </li>
                </ul>
              </div> -->
              <div class="header_bottom justify-end">
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
                <div class="cont">
                  <ul class="contact_us">
                    <li v-if="store?.websiteSettings?.SITE_PHONE1" class="header_phone"><span class="flex items-center"><i class="fa-solid fa-mobile-screen-button"></i><p class="pl-2" v-html="store?.websiteSettings?.SITE_PHONE1"></p></span></li>
                  </ul>
                </div>
                <div class="social_inner flex items-center">
                    <ul class="social">
                        <li v-if="store.websiteSettings?.FACEBOOK" ><a :href="store.websiteSettings?.FACEBOOK" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li v-if="store.websiteSettings?.TWITTER" ><a :href="store.websiteSettings?.TWITTER" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                    </ul>
                </div>
              </div>
            </div>

            
          </div>

        </div>
      </div>
  </HeaderWrapper>
</template>
<script>
import { store } from '../store';
import { Link } from "@inertiajs/inertia-vue3";
import {HeaderWrapper} from '../styles/headerStyles';
import { ref, watchEffect } from 'vue';
import {setHeaderHeight} from '../utils/commonFuntions';
import UserLoginOptions from './UserLoginOptions.vue';

function isValidURL(str) {
  try {
    const url = new URL(str);
    return url.protocol !== 'javascript:';
  } catch (error) {
    return false;
  }
}

function handleHeaderHeight() {
  const timeoutId = setInterval(() => {
      setHeaderHeight();
  }, 10);

  setTimeout(() => {
      clearTimeout(timeoutId);
  }, 2000);
}

export default {
  name:"Header",
  created(){
      this.preloadLogo();
  },
  data() {
    return {
      store,
      sidemenuActive : false,
    }
  },
  mounted(){
    // handleHeaderHeight();
    // document.addEventListener('resize', handleHeaderHeight);
    // const resizeObserver = new ResizeObserver(handleHeaderHeight);
    // resizeObserver.observe(document.querySelector('header'));
  },
  methods: {
    preloadLogo() {
      const img = new Image();
      img.src = this.store.websiteSettings?.FRONTEND_LOGO;
      img.onload = () => {
          console.log(`Image preloaded:`, this.store.websiteSettings?.FRONTEND_LOGO);
      };
    },
    openSideMenu(){
      this.sidemenuActive = true;
      document.body.classList.add('sidemenu_active');
    },
    closeSideMenu(){
      this.sidemenuActive = false;
      document.body.classList.remove('sidemenu_active');
    },
    toggleSideMenu(){
      this.sidemenuActive = !this.sidemenuActive;
      if(this.sidemenuActive){
        document.body.classList.add('sidemenu_active');
      }else{
        document.body.classList.remove('sidemenu_active');
      }
    },
    handleNavLink(e) {
        e.preventDefault();
        var element = e.target;
        var subMenuParent = element.closest('.has-dropdown');
        if (subMenuParent) {
          subMenuParent.classList.add('unhover');
        }
        document.body.classList.remove('sidemenu_active');

        var href = element.getAttribute('href');
        if(href) {
            this.$inertia.get(href);
        }
        if (subMenuParent) {
          setTimeout(() => {
            subMenuParent.classList.remove('unhover');
          }, 1000);
        } 
    },
    handleMobileTogglerClick(e){
      const element = $(e.target);
      element.siblings('.sub-menu').slideToggle();
      element.closest('.has-dropdown').toggleClass('active');
      element.closest('.has-dropdown').siblings('.has-dropdown').removeClass('active')
      element.closest('.has-dropdown').siblings('.has-dropdown').find('.sub-menu').slideUp();
    },
    setMobileDropdownMenu(menuElement){
      const dropdownElements = menuElement.querySelectorAll('.has-dropdown');

      dropdownElements.forEach((dropdown) => {

        const togglerSpan = document.createElement('span');
        togglerSpan.classList.add('toggler');
        togglerSpan.addEventListener('click', this.handleMobileTogglerClick)

        const chevronIcon = document.createElement('i');
        chevronIcon.classList.add('fa-solid', 'fa-chevron-down');

        togglerSpan.appendChild(chevronIcon);

        dropdown.appendChild(togglerSpan);
      });

    }
  },
  updated() {
    const headerElement = this.$refs.headerRef.$el;
    var allPageLinks = headerElement.querySelectorAll('a');

    this.setMobileDropdownMenu(headerElement);

    for (var i = 0; i < allPageLinks.length; i++) {
      const targetAttr = allPageLinks[i].getAttribute('target');
      const hrefAttr = allPageLinks[i].getAttribute('href');

      if (targetAttr === '_self' && isValidURL(hrefAttr)) {
        allPageLinks[i].addEventListener('click', this.handleNavLink);
      }
    }
  },
  setup(props) {
    const myPropValue = ref(props.headerMenuList);
    watchEffect(() => {
      myPropValue.value = props.headerMenuList;
      setHeaderHeight();
    });
  },
  watch:{
    store: {
      handler: function(updatedStore) {
        // console.log('updatedStore>>', updatedStore);
      },
      deep : true
    }
  },
  components:{
    Link,
    HeaderWrapper,
    UserLoginOptions
},

  props:["headerMenuList", "processing"]
}
</script>