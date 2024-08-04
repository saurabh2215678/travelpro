<template>
    <HotelSliderBoxWrapper class="hotel_slider_box">
        <div class="theme_title">
            <div class="title">Our Domestic and International Hotels</div>
            
            <div class="option_right">
                <Link :href="store.websiteSettings?.HOTEL_URL">View All</Link>
                <div class="slider_btns" >
                    <div class="blog-prev theme-prev" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="blog-next theme-next" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
                </div>
            </div>
        </div>

        <div class="slider_box">
            <div class="swiper" ref="sliderRef">
                <div class="swiper-wrapper">

                    <div class="swiper-slide" v-for="hotel in hotelList">
                        <Link class="hotel_box" :href="hotel.url">
                            <div class="images">
                                <img
                                :src="hotel.thumbSrc"
                                :alt="hotel.name">
                            </div>
                            <div class="hotel_content py-4 px-2">
                                <div class="title text-sm">{{hotel.name}}</div>
                                <div class="star-block">
                                    <p class="para_md inline-block"><img
                                    src="../../assets/images/map_hotel.png"
                                    alt="Map Hotel">{{hotel.place_name}}</p>
                                    <ul class="rating_list py-1"> 
                                        <StarRating v-model:rating="hotel.star_classification" read-only :show-rating="false"/>
                                    </ul>
                                </div>
                            </div>
                        </Link>
                    </div>

                </div>
            </div>
        </div>
    </HotelSliderBoxWrapper>
</template>
<script>
import {HotelSliderBoxWrapper} from '../../styles/HotelSliderBoxWrapper'
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import StarRating from 'vue-star-rating'

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

        new Swiper(sliderElem, {
            slidesPerView: 5,
            spaceBetween: 20,
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
                slidesPerView: 2,
                },
                768: {
                slidesPerView: 3,
                },
                1024: {
                slidesPerView: 5,
                },
            },
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });

    },
    components:{
        HotelSliderBoxWrapper,
        StarRating,
        Link
    },
    props:['hotelList']
}
</script>