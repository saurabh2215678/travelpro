<template>
    <TestiImageWrapper class="testimage">
        <div class="swiper tesiimg_slider" ref="sliderRef">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="image in images">
                    <img :src="image.src" :alt="image.alt">
                </div>
            </div>
            <!-- <div class="swiper-button-next" ref="sliderNextRef"></div> 
            <div class="swiper-button-prev" ref="sliderPrevRef"></div> -->
        </div>
    </TestiImageWrapper>
</template> 
<script>
import styled from 'vue3-styled-components';


const TestiImageWrapper = styled.div`
& .swiper-button-lock{
    display:none;
}
`
export default {
    name:"InnerImageSlider",
    mounted(){
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
        TestiImageWrapper
    },
    props:['images']
}
</script>