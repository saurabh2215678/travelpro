<template>
    <HomePageBannerSliderWrapper>
        <div class="swiper" ref="sliderRef">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="(image, key) in bannerImages" :key="key">
                    <template v-if="key == '0'">
                        <img :src="image.src" :alt="image?.title" preload/>
                    </template>
                    <template v-else>
                        <img :src="image.src" :alt="image?.title"/>
                    </template>
                    <div class="banner_content">
                        <div class="container">
                            <div class="slider-caption">
                                <div class="slider_heading font59 color_theme fw700 tracking-wide" v-html="image?.title"></div>
                                <div class="slider_para font15 color_dark mb-7 fw600" v-html="image?.sub_title"></div>
                                <a class="btn_banner font18 fw600" :href="image?.link_1" v-if="image?.link_1"> <i class="fa-solid fa-angle-right"></i> 
                                <span v-if="image?.link_text_1">
                                    {{ image?.link_text_1 }}
                                </span>
                                </a> 
                                <!-- <a :href="image?.link_1" class="btn_banner font18 fw600 "> <span><img :src="'../assets/ladakhbikerental/images/arrow.png'" alt=""></span> Learn More</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="swiper-pagination" ref="sliderPagination"></div> -->
        <!-- <div class="bottom_image"></div> -->
        <div class="mobile_image hidden"><a href="/search-packages"><img class="banner_img" src="../../assets/images/mobile-banner1.jpg" alt=""></a></div>
    </HomePageBannerSliderWrapper>
</template>
<script>
import {HomePageBannerSliderWrapper} from '../../styles/HomePageBannerSliderWrapper';
export default {
    name: 'HomePageBannerSlider',
    created(){
        // console.log('inside component =', this.bannerImages);
        this.preloadBanners();
    },
    data(){
        return {
        }
    },
    components: {
        HomePageBannerSliderWrapper
    },
    mounted() {
        

        var sliderElem = this.$refs.sliderRef;
        // var sliderPagination = this.$refs.sliderPagination;

        var slidesCount = 1;
        var spacebetween = 0;

        new Swiper(sliderElem, {
            slidesPerView: slidesCount,
            spaceBetween: spacebetween,
            loop: true,
            effect: "fade",
            speed:1000,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            // pagination: {
            //     el: sliderPagination,
            //     clickable: true
            // },
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });
    },
    methods:{
        preloadBanners() {
            const firstBanner = [this.bannerImages[0]];
                firstBanner.forEach((image) => {
                    const img = new Image();
                    img.src = image.src;
                    img.onload = () => {
                        console.log(`Image preloaded:`, image);
                    };
                }); 
        },
    },
    props:['bannerImages',]
}
</script>