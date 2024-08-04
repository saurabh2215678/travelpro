<template>
    <ActivityDetailImageWrapper>
        <div class="swiper package_detail_img" ref="sliderRef" v-if="data.length > 0">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="imageData in data">
                    <img  data-fancybox="gallery"
                    :src="imageData.srcimage"
                    alt="{{imageData.title}}" />
                </div>
            </div>
            <div class="swiper-button-next" ref="sliderPrevRef"></div>
            <div class="swiper-button-prev" ref="sliderNextRef"></div>
        </div>
        <div class="no_imges" v-else>
            <img src="../../assets/images/no_image.jpg" />
        </div>
    </ActivityDetailImageWrapper>
</template>
<script>
import styled from 'vue3-styled-components';

const ActivityDetailImageWrapper = styled.div`
    height: 100%;
& .no_imges{
    width: 100%;
    height: 100%;
    & img{
        width: 100%;
        height: 100%;
        object-fit: contain;
        background-color: #e9e9e9;
    }
}
`
export default {
    name:'ActivityImageSlider',
    components:{
        ActivityDetailImageWrapper
    },
    mounted() {
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = 1;
        var spacebetween = 15;

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
    props: ['data']
}
</script>