<template>
    <HomePageBannerSliderWrapper>

        <div class="swiper" ref="sliderRef">
            <div class="swiper-wrapper">

                <div class="swiper-slide" v-for="image in banner_images">
                    <img :src="image.bannerSrc" />
                    <div class="banner_content">
                        <div class="container">
                            <div class="slider-caption">
                                <div class="slider_heading">{{ image.title }}</div>
                                <div class="slider_para">{{ image.sub_title }}</div>
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
        console.log('inside component =', this.banner_images);
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
    props:['banner_images',]
}
</script>