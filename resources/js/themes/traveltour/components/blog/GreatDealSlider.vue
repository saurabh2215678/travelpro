<template>
    <GreatDealWrapper class="great_deal_slider">
        <div class="title text-2xl mb-3">Explore Great Deals</div>
        <div class="slider_box">
            <div class="package_deal_slider swiper" ref="sliderRef">
                <div class="swiper-wrapper">

                    <div class="swiper-slide" v-for="item in data">
                        <PackageWithTypeCard :data="item"/>
                    </div>

                </div>
            </div>
            <div class="slider_btns">
                <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
            </div>
        </div>
    </GreatDealWrapper>
</template>
<script>

import PackageWithTypeCard from '../PackageWithTypeCard.vue';
import { GreatDealWrapper } from '../../styles/GreatDealWrapper';


export default {
    name:'GreatDealSlider',
    components:{
        GreatDealWrapper,
        PackageWithTypeCard
    },
    mounted() {
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = 2;
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
                slidesPerView: 1.5,
                },
                640: {
                slidesPerView: 2,
                },
                768: {
                slidesPerView: 2,
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