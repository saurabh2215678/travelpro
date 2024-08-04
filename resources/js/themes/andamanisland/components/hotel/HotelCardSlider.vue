<template>
    <HotelCardSliderWrapper class="HotelCardSlider roomgallery2">
        <div class="slider_main">
            <div class="swiper" ref="sliderRef">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" v-for="item in images">
                        <img
                        :src="item.thumbSrc"
                        :alt="item.title" />
                    </div>
                </div>
            </div>
            <div class="slider_btns">
                <div class="cat-next theme-next" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="cat-prev theme-prev" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
            </div>
        </div>

        <div class="slider_thumb roomgallery">
            <div class="swiper" ref="sliderThumbRef">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" v-for="item in images">
                        <img
                        :src="item.thumbSrc"
                        :alt="item.title" />
                    </div>
                </div>
            </div>
        </div>

    </HotelCardSliderWrapper>
</template>
<script>
import {HotelCardSliderWrapper} from '../../styles/HotelCardSliderWrapper'
export default{
    name : 'HotelCardSlider',
    components:{
        HotelCardSliderWrapper
    },
    mounted() {
        const sliderElem = this.$refs.sliderRef;
        const sliderThumbElem = this.$refs.sliderThumbRef;
        const sliderNextBtn = this.$refs.sliderNextRef;
        const sliderPrevBtn = this.$refs.sliderPrevRef;

        const thumbSwiper = new Swiper(sliderThumbElem, {
            loop: false,
            spaceBetween: 10,
            slidesPerView: 4,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            
        });

        new Swiper(sliderElem, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: false,
            speed:1000,
            navigation: {
                nextEl: sliderPrevBtn,
                prevEl: sliderNextBtn,
            },
            thumbs: {
                swiper: thumbSwiper,
            },
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });

    },
    props :['images']
}
</script>