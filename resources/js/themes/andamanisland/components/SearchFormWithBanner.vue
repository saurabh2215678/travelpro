<template>
    <SectionWrapper :class="handleWrapperClass()">
        <!-- <img  src="../assets/images/home_banner.jpg" class="inner_banner" alt=""> -->
        <HomePageBannerSlider class="slider_section" v-if="isHomepage && banner_images" :bannerImages="banner_images" />
        <template v-else>
            <template v-if="store.seoData.banners && store.seoData.banners.length > 0">
                <HomePageBannerSlider class="slider_section" :bannerImages="store.seoData.banners" />
            </template>
            <template v-else>
                <img v-if="store?.seoData?.banner_image" :src="store.seoData.banner_image" class="inner_banner" alt="">
                <img v-else src="../assets/images/inner_common_banner.jpg" class="inner_banner" alt="">
            </template>
        </template>        
        <SearchForm :type="type"/>
    </SectionWrapper>
</template>
<script>

import SearchForm from './SearchForm.vue';
import {SectionWrapper} from '../styles/SearchFormWithBannerStyles';
import HomePageBannerSlider from './home/HomePageBannerSlider.vue';
import { store } from '../store.js';
import axios from "axios";

export default {
    name: 'SearchFormWithBanner',
    created() {
        this.loadHomeBanners();
        // console.log('abhsihek =',store.seoData);
    },
    data(){
        return {
            store,
            banner_images : null
        }
    },
    components: {
        SearchForm,
        SectionWrapper,
        HomePageBannerSlider
    },
    methods:{
        handleWrapperClass(){
            let wrapperClass = [];
            if(this.title){
                wrapperClass.push('has_title');
            }
            if(this.isHomepage){
                wrapperClass.push('homepageBanner');
            }
            return wrapperClass;
        },

        loadHomeBanners(){
            var currentObj = this;
            let form_data = {};
            form_data['limit'] = this.limit;
            axios.post('/ajaxHomeBanners',form_data)
            .then(function (response) {
                if(response.data.success) {
                    currentObj.banner_images = response.data?.banner_images;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        }
    },
    props:['title', 'type', 'isHomepage']
}
</script>