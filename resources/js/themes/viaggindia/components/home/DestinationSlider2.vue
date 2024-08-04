<template>
    <DestinationBox2Wrapper class="destination_box_2_wrapper">
        <div class="title_box justify-center">
            <h2 class="main_heading">DESTINAZIONI</h2>
        </div>
        <div class="destination_slider">
            <div class="slider_box">
                <div class="swiper" ref="sliderRef">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="item in destinations">
                            <div class="destination_view">
                                <a class="destimg" :href="item?.url">
                                    <img :src="item?.imageSrc" :alt="item?.name" class="main_img">
                                    <img :src="'assets/viaggindia/images/border_img.png'" class="desti_border"/>
                                </a>
                                <div class="package_info">
                                    <h3><a :href="item?.url"><span>{{ item?.name }}</span></a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider_btns">
                    <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-arrow-left"></i></div>
                    <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </DestinationBox2Wrapper>
</template>
<script>
import {DestinationBox2Wrapper} from '../../styles/DestinationBox2Wrapper';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    name : 'DestinationSlider2',
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
                spaceBetween: 20
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
        DestinationBox2Wrapper,
        Link
    },
    props:['destinations']
}
</script>