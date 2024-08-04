<template>
    <SliderSectionWrapper class="related-hotel pb-5" style="background:#fff;" v-if="this.relatedHotels && this.relatedHotels.length > 0">
        <div class="container">
            <div class="text_center ml-2">
                <div class="theme_title mb-3">
                    <div class="title text-2xl font-semibold">Other Hotels in {{destination}}</div>
                </div>
            </div>
            <div class="slider_box">
            <div class="swiper category_slider" ref="sliderRef">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="relatedaccommodation in this.relatedHotels">
                    <Link class="tour_category_box" :href="relatedaccommodation.url">
                        <div class="images">
                            <img :src="relatedaccommodation.thumbSrc" :alt="relatedaccommodation.name" />
                        </div>
                        <div class="featured_content px-3.5 py-3.5">
                            <div class="title text-lg font-semibold">{{relatedaccommodation.name}}</div>
                            <!-- <p class="text-xs"  v-html="relatedaccommodation.brief"></p> -->
                        </div>
                    </Link>
                </div>
            </div>
        </div>
        <div class="slider_btns">
                <div class="cat-next theme-next" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="cat-prev theme-prev" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
            </div>
        </div>
    </div>
    </SliderSectionWrapper>
</template>
<script>
import { store } from '../../store.js';
import axios from "axios";
import { Link } from "@inertiajs/inertia-vue3"; 
import { SliderSectionWrapper } from "../../styles/SliderSectionWrapper";
export default {
    name:'RelatedHotels',
created() {
    this.relatedHotels = this.relatedAccommodations;
},  
data() {
    return {
        store,
        relatedHotels:[],
    }
},
mounted() {
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = this.slidePerView ? this.slidePerView : 4;
        var spacebetween = this.spacebetween ? this.spacebetween : 30;

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
methods:{
    },
components: {
    Link,
    SliderSectionWrapper
 },
props: ["relatedAccommodations","accommodation", "inventory", "adult", "child", "infant", "checkin", "checkout","destination"],
}
</script>