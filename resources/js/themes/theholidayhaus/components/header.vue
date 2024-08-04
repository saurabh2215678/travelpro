<template>
  <HeaderWrapper ref="headerRef">
      <div class="header-top">
        <div class="container w-full header_inner">
          <div class="header_left">
            <div class="header-logo" v-if="processing"><div class="placeholder-logo"></div></div>
            <Link v-else :href="store.websiteSettings?.FRONTEND_URL" class="header-logo"><img :src="store.websiteSettings?.FRONTEND_LOGO" alt="logo" v-if="store.websiteSettings" preload></Link>
          </div>
          <div class="header_right">
            <div class="search_formbtn">
                <div class="searchbtn cursor-pointer" @click="handleSearchActive"><input name="" id="" class="form_control" type="text" placeholder="Where are you going?" readonly> <i class="fa-solid fa-magnifying-glass"></i></div>
              </div>
            <!-- <HeaderStyledFormWrapper>
                <SearchForm type="package" v-if="store.websiteSettings"/>
            </HeaderStyledFormWrapper> --> 
            <div class="header_menus">
              <div class="search_btn" @click="handleMobileSearchBoxOpen"><i class="fa-solid fa-magnifying-glass"></i></div>
              <div v-if="store?.websiteSettings?.SALES_PHONE" class="top_phone">
                <span>Call Now</span>
                <a :href="'tel:'+store?.websiteSettings?.SALES_PHONE">
                  <div class="svg_box">
                    <i class="fa-solid fa-phone"></i>
                  </div>
                  {{store?.websiteSettings?.SALES_PHONE}}</a>
              </div>
              <div v-if="store?.websiteSettings?.SITE_EMAIL" class="top_email">
                <span>Email US</span>
                  <a :href="'mailto:'+store?.websiteSettings?.SITE_EMAIL">
                    <div class="svg_box">
                      <i class="fa-solid fa-envelope"></i>
                  </div>
                    {{store?.websiteSettings?.SITE_EMAIL}}</a>
              </div>
              <div class="last_options">
                  <UserLoginOptions v-if="store && store.userInfo && store.userInfo.name"/>
                  <template v-else>
                    <Link :href="store.websiteSettings?.FRONTEND_LOGIN_URL"><SvgRenderer svgPath="../assets/svgs/user.svg"/> My Account <i class="fa-solid fa-angle-down"></i></Link>
                    <!-- for mobile signin button -->
                    <Link :href="store.websiteSettings?.FRONTEND_LOGIN_URL" class="mob_signin"><i class="fa-solid fa-user"></i></Link>
                  </template>
              </div>
            </div>
            <div class="search_box" :class="{ 'active': searchActive }">
              <SearchForm type="package" v-if="searchActive" :closeSearch="closeSearch"/>
          </div>
          </div>
          
        </div>
        
      </div>

      <div class="header-bottom">
        <div class="menu_toggle" @click="toggleSideMenu">
                <span></span>
                <span></span>
                <span></span>
              </div>
        <div class="menu_backdrop" @click="closeSideMenu"></div>
        <div class="container w-full header_middle">
            <div class="search_backdrop" @click="handleMobileSearchBoxClose"></div>
            <div class="header_top">
              <div class="search_close_btn" @click="handleMobileSearchBoxClose"><i class="fa-solid fa-xmark"></i></div>
            </div>
            <div class="header_bottom">
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
import SearchForm from './SearchForm.vue';
import {HeaderStyledFormWrapper} from '../styles/HeaderStyledFormWrapper';
import InlineSvg from 'vue-inline-svg';
import SvgRenderer from './SvgRenderer.vue';

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
      
  },
  data() {
    return {
      store,
      sidemenuActive : false,
      searchActive : false
    }
  },
  mounted(){
    document.addEventListener('click', this.handleDocumentClick);
    handleHeaderHeight();
    document.addEventListener('resize', handleHeaderHeight);
    const resizeObserver = new ResizeObserver(handleHeaderHeight);
    resizeObserver.observe(document.querySelector('header'));


    function handleDomChanges(mutationsList, observer) {
        mutationsList.forEach((mutation) => {
            if (mutation.type === 'childList') {
                // check if any img has noimagebig.jpg this image then add class to that img tag
                const images = document.querySelectorAll('img');
                images.forEach((img) => {
                    if (img.src.includes('noimagebig.jpg') || img.src.includes('noimage.jpg')) {
                        img.classList.add('no_bg_img');
                    }
                });

              //

               var allElements = document.querySelectorAll('*');

              // Iterate through each element
              allElements.forEach(function(element) {
                // Get the computed style of the element
                var computedStyle = window.getComputedStyle(element);

                // Get the text color property value
                var textColor = computedStyle.color;

                // Check if the text color is '#e31c23'
                if (textColor === 'rgb(227, 28, 35)') {
                  // Add the 'highlight' class
                  element.classList.add('highlight');
                }
              });

            }
        });
    }

    const observer = new MutationObserver(handleDomChanges);
    const observerOptions = {
        childList: true,
        subtree: true,
    };

    const targetNode = document.body;
    observer.observe(targetNode, observerOptions);

    // To disconnect the observer later (when you don't need it anymore)
    // observer.disconnect();

  },
  methods: {
    preloadLogo() {
      const img = new Image();
      img.src = this.store.websiteSettings?.FRONTEND_LOGO;
      img.onload = () => {
          console.log(`Logo preloaded:`, this.store.websiteSettings?.FRONTEND_LOGO);
      };
    },
    handleSearchActive(e) {
      e.stopPropagation();
      this.searchActive = !this.searchActive;
    },
    closeSearch(){
      this.searchActive = false;
    },
    openSideMenu(){
      this.sidemenuActive = true;
      document.body.classList.add('sidemenu_active');
    },
    handleDocumentClick(event) {
      const clickedElement = $(event.target);
      
      if (!(clickedElement.closest('.search_box').length > 0 || 
            clickedElement.hasClass('search_box') || 
            (clickedElement.attr('data-slug') && clickedElement.attr('data-slug').length > 0))) {

        this.searchActive = false;
      }
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

    },
    handleMobileSearchBoxOpen(){
      $('body').addClass('search_opened');
    },
    handleMobileSearchBoxClose(){
      $('body').removeClass('search_opened');
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
        if(updatedStore?.websiteSettings?.FRONTEND_LOGO){
          this.preloadLogo();
        }
      },
      deep : true
    }
  },
  components:{
    Link,
    HeaderWrapper,
    UserLoginOptions,
    SearchForm,
    HeaderStyledFormWrapper,
    InlineSvg,
    SvgRenderer
},

  props:["headerMenuList", "processing"]
}
</script>