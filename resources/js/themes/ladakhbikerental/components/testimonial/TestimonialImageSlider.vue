<template>
    <TestimonialImagesWrapper class="slider_box">
        <div class="testimonial_slider swiper" ref="sliderRef">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="image in images">
                    <img :src="image.src"  />
                </div>
            </div>
        </div>
        <div class="slider_btns">
            <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
            <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
    </TestimonialImagesWrapper>
</template>


<script>
import { TestimonialImagesWrapper } from '../../styles/TestimonialImagesWrapper';

export default {
    name:'TestimonialImageSlider',
    mounted() {
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = 1;
        var spacebetween = 0;

        new Swiper(sliderElem, {
            slidesPerView: slidesCount,
            spaceBetween: spacebetween,
            loop: false,
            speed:1000,
            navigation: {
                nextEl: sliderPrevBtn,
                prevEl: sliderNextBtn,
            },
            breakpoints: {
                0: {
                slidesPerView: 1,
                },
                640: {
                slidesPerView: 1,
                },
                768: {
                slidesPerView: 1,
                },
                1024: {
                slidesPerView: slidesCount,
                },
            },
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });
    },
    components:{
        TestimonialImagesWrapper
    },
    props:['images']
}
</script>