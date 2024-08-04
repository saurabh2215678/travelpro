<template>
    <DestinationBoxWrapper class="destination_box_wrapper">
        <div class="title_box">
            <h2 class="title">{{title}}</h2>
            <div class="slider_btns">
                <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-arrow-left"></i></div>
                <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-arrow-right"></i></div>
            </div>
        </div>
        <div class="destination_slider">
            <div class="slider_box">
                <div class="swiper" ref="sliderRef">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide" v-for="item in destinations">
                            <div class="destination_view">
                                <Link :href="item?.url" class="destination_inner">
                                    <img :src="item?.imageSrc" :alt="item?.name">
                                    <div class="destination_info">
                                        <h3>{{ item?.name }}</h3>
                                    </div>
                                </Link>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </DestinationBoxWrapper>
</template>
<script>
import {DestinationBoxWrapper} from '../../styles/DestinationBoxWrapper';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    name : 'DestinationSlider',
    mounted() {
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = 5;
        var spacebetween = 20;

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
                spaceBetween: 30
                },
                640: {
                slidesPerView: 2,
                spaceBetween: 30
                },
                768: {
                slidesPerView: 3,
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

    },
    components:{
        DestinationBoxWrapper,
        Link
    },
    props:['destinations','title']
}
</script>