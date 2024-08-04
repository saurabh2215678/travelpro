<template>
    <HomePackageSliderWrapper class="HomePackageSlider">
        <div class="container">
            <div class="title_wrapper">
                <div class="title_left" v-if="this.flagData && (this.flagData?.title || this.flagData?.brief) ">
                    <h3 :class="headingTitleClass ? headingTitleClass : 'title fw600'">{{ this.flagData?.title }}</h3>
                    <p class="subtitle">{{ this.flagData?.brief }}</p>
                </div>
                <div class="title_right">
                    <Link :class="titleBtnClass ? titleBtnClass : 'btn btn_theme'" :href="this.viewAllUrl" v-if="this.viewAllUrl">{{this.viewAllTitle}}</Link>
                    <div class="slider_btns">
                        <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-arrow-left"></i></div>
                        <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="package_slider_wrapper">
                <div class="slider_box">
                    <div class="swiper" ref="sliderRef">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide" v-for="item in this.packages.data">
                                <PackageSliderCard :data="item" :hiderating="hiderating"/>
                            </div>

                        </div>
                    </div>
                    <div class="slider_pagination" ref="sliderPagination" v-if="pagination != 'off'"></div>
                </div>
            </div>
        </div>
    </HomePackageSliderWrapper>
</template>
<script>
import {HomePackageSliderWrapper} from '../../styles/HomePackageSliderWrapper';
import PackageSliderCard from './PackageSliderCard.vue';
import { Link } from "@inertiajs/inertia-vue3"; 
import { store } from '../../store.js';
import axios from "axios";

export default{
    name : 'HomePackageSlider',
    created() {
        var currentObj = this;
        if(!this.sectionData){ 
            this.loadPackages(); 
        }else{ 
            this.packages = this.sectionData;
            setTimeout(() => {
                currentObj.initSlider();
            }, 100);
        }
    },
    data() {
        return {
            store,
            packages:{
                'data': [],
                'links': ''
            },
            flagData: {
                'slug': this.flagSlug,
                'title': this.title,
                'brief': this.subTitle
            }
        }
    },
    methods:{
        loadPackages(){
            var currentObj = this;
            let form_data = {};
            form_data['is_activity'] = this.isActivity;
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
            form_data['flag_slug'] = this.flagSlug;
            if(this.destinationSlug) {
                if(this.isActivity == 1) {
                    form_data['segments1'] = 'tour-activities';
                } else {
                    form_data['segments1'] = 'tour-packages';
                }
                form_data['segments2'] = this.destinationSlug;
            }
            form_data['theme_category_slug'] = this.themeCategorySlug;
            axios.post('/package/ajaxSearchPackage',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    currentObj.packages = response.data?.results;
                    if(response.data.flagData) {
                        currentObj.flagData = response.data.flagData;
                    }
                    setTimeout(function(){
                        currentObj.initSlider();
                    },50)
                } else {
                    alert('Something went wrong, please try again.');
                }

            });
        },
        initSlider() {
            var currentObj = this;
            var sliderElem = this.$refs.sliderRef;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;
            var sliderPagination = this.$refs.sliderPagination;

            var slidesCount = currentObj.itemsPerView?currentObj.itemsPerView:4;
            var spacebetween = 20;

            new Swiper(sliderElem, {
                slidesPerView: slidesCount,
                spaceBetween: spacebetween,
                loop: false,
                speed:1000,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
                pagination: (currentObj.pagination != 'off') ? {
                    el: sliderPagination,
                    clickable: true,
                    dynamicBullets: true,
                }:{},
                breakpoints: {
                    0: {
                    slidesPerView: 1.1,
                    spaceBetween: 30
                    },
                    640: {
                    slidesPerView: 1.4,
                    spaceBetween: 30
                    },
                    768: {
                    slidesPerView: 1.8,
                    spaceBetween: spacebetween,
                    },
                    1024: {
                    slidesPerView: slidesCount,
                    spaceBetween: spacebetween,
                    },
                },
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });
        }
    },
    mounted() {},
    components:{
        HomePackageSliderWrapper,
        PackageSliderCard,
        Link
    },
    props:['isActivity', 'featured', 'limit', 'title', 'subTitle', 'viewAllUrl', 'viewAllTitle', 'destinationSlug',  'themeCategorySlug', 'sliderClass', 'itemsPerView','flagSlug', 'pagination', 'sectionData', 'titleBtnClass', 'headingTitleClass']
}
</script>