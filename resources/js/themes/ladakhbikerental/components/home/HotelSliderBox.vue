<template>
    <HotelSliderBoxWrapper class="hotel_slider_box">
        <div class="title_wrapper">
            <div class="title_left">
                <h2 class="title font30 fw700">{{this.title}}</h2>
            </div>
            <div class="title_right">
                <Link class="view_all" :href="this.viewAllUrl" v-if="this.viewAllUrl">View All</Link>
                <div class="slider_btns" >
                    <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-arrow-left"></i></div>
                    <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-arrow-right"></i></div>
                </div>
            </div>
        </div>
        <div class="hotel_slider_wrapper">
            <div class="slider_box">

                <div class="swiper" ref="sliderRef">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="hotel in hotelList">
                            <HotelSliderCard :data="hotel"/>
                        </div>
                    </div>
                </div>
                <div class="slider_pagination" ref="sliderPagination"></div>
            </div>
        </div>

    </HotelSliderBoxWrapper>
</template>
<script>
import {HotelSliderBoxWrapper} from '../../styles/HotelSliderBoxWrapper'
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import StarRating from 'vue-star-rating'
import HotelSliderCard from '../hotel/HotelSliderCard.vue';

export default{
    name : 'HotelSliderBox',
    data() {
        return {
            store
        }
    },
    mounted() {
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;
        var sliderPagination = this.$refs.sliderPagination;

        new Swiper(sliderElem, {
            slidesPerView: 4,
            spaceBetween: 20,
            loop: false,
            speed:1000,
            navigation: {
                nextEl: sliderPrevBtn,
                prevEl: sliderNextBtn,
            },
            pagination: {
                el: sliderPagination,
                clickable: true,
                dynamicBullets: true,
            },
            breakpoints: {
                0: {
                slidesPerView: 1,
                },
                640: {
                slidesPerView: 2,
                },
                768: {
                slidesPerView: 3,
                },
                1024: {
                slidesPerView: 4,
                },
            },
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });

    },
    components:{
    HotelSliderBoxWrapper,
    StarRating,
    Link,
    HotelSliderCard
},
    props:['hotelList', 'title', 'viewAllUrl']
}
</script>