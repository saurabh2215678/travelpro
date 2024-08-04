<template>
    <HomePageBannerSliderWrapper>
        <div class="swiper" ref="sliderRef">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="image in bannerImages">
                    <img :src="image.src" :alt="image?.title" preload/>
                    <div class="banner_content">
                        <div class="container">
                            <div class="slider-caption">
                                <div class="slider_heading">{{image?.title}}</div>
                                <div class="slider_para">{{image?.sub_title}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination" ref="sliderPagination"></div>
        <div class="bottom_image"></div>
    </HomePageBannerSliderWrapper>
</template>
<script>
import {HomePageBannerSliderWrapper} from '../../styles/HomePageBannerSliderWrapper';
export default {
    name: 'HomePageBannerSlider',
    created(){
        // console.log('inside component =', this.banner_images);
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
        var sliderPagination = this.$refs.sliderPagination;

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
            pagination: {
                el: sliderPagination,
                clickable: true
            },
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