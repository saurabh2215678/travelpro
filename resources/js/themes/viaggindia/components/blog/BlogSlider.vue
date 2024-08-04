<template>
    <BlogSliderWrapper class="blog_slider_wrapper">
        <h3 class="title" v-if="data.length > 0">{{showLangTitle('SimilarBlogs')}}</h3>
        <div class="slider_box">
            <div class="blog_slider swiper" ref="sliderRef">
                <div class="swiper-wrapper">

                    <div class="swiper-slide" v-for="item in data">
                        <BlogCard :data="item" :isBreif="true"/>
                    </div>

                </div>
            </div>
            <div class="slider_btns">
                <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
            </div>
        </div>
    </BlogSliderWrapper>
</template>
<script>
import BlogCard from './BlogCard.vue';
import { BlogSliderWrapper } from '../../styles/BlogSliderWrapper';
import {showLangTitle} from '../../utils/commonFuntions';

export default {
    name:'BlogSlider',
    methods: {
        showLangTitle,
    },
    components:{
        BlogSliderWrapper,
        BlogCard
    },
    mounted() {
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = 3;
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
    props:['data']
}
</script>