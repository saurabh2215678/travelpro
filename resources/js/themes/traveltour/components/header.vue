<template>
  <HeaderWrapper ref="headerRef">
      <div class="container-xl">
        <div class="header_inner">

          <div class="header_left">
            <div class="header-logo" v-if="processing"><img :src="'../assets/traveltour/images/header-logo.png'" alt=""></div>
            <Link v-else :href="store.websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="store.websiteSettings?.FRONTEND_LOGO" alt="logo" v-if="store.websiteSettings"></Link>
          </div>

          <div class="header_right">
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
            
            <div class="menu_backdrop" @click="closeSideMenu"></div>
            <div class="menu_toggle" @click="toggleSideMenu">
              <span></span>
              <span></span>
              <span></span>
            </div>


            <UserLoginOptions v-if="store && store.userInfo && store.userInfo.name"/>
            <template v-else>
              <Link :href="store.websiteSettings?.FRONTEND_LOGIN_URL" class="btn font21 fw300 bg_theme color_light">Sign in</Link>
              <!-- for mobile signin button -->
              <Link :href="store.websiteSettings?.FRONTEND_LOGIN_URL" class="btn font21 fw300 bg_theme color_light mob_signin"><i class="fa-solid fa-user"></i></Link>
            </template>

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
  data() {
    return {
      store,
      sidemenuActive : false,
    }
  },
  mounted(){
    handleHeaderHeight();
    document.addEventListener('resize', handleHeaderHeight);
    const resizeObserver = new ResizeObserver(handleHeaderHeight);
    resizeObserver.observe(document.querySelector('header'));
  },
  methods: {
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
  components:{
    Link,
    HeaderWrapper,
    UserLoginOptions
},

  props:["headerMenuList", "processing"]
}
</script>