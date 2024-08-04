<template>
    <SliderSectionWrapper class="home_featured mt-5 pb-0 bg_pattrn" :class="className">
        <div class="container">
            <div class="theme_title mb-3">
                <div class="title">{{ title }}</div>
                <div class="view_all_btn" v-if="sectionData?.url"><a :href="sectionData.url">View All</a></div>
            </div>
            <div class="slider_box">
                <div class="swiper category_slider" ref="sliderRef">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="row in sectionData.data">
                            <div class="tour_category_box">
                                <a class="images" :href="row?.url || row?.slug">
                                    <img :src="row.thumbSrc"
                                    class="theme_radius0" :alt="row.title">
                                </a>
                                <div class="featured_content px-1.5 py-3.5" v-if="withPrice">
                                    <p class="day_night_detail">
                                        <span class="day_night text-sm text-gray-500 font-semibold">{{row.days}}Days {{row.nights}} Nights</span>
                                    </p>
                                    <Link :href="row?.url || row?.slug" class="title text-base">{{row.title}}</Link>
                                    <!-- <div class="raiting-holder">
                                        <ul class="raiting-list">
                                            <li class=""><i class="fa-solid fa-star"></i></li>
                                            <li class=""><i class="fa-solid fa-star"></i></li>
                                            <li class=""><i class="fa-solid fa-star"></i></li>
                                            <li class=""><i class="fa-solid fa-star"></i></li>
                                            <li class=""><i class="fa-regular fa-star"></i></li>
                                        </ul>
                                        <span class="counter text-sm">168 Ratings</span>
                                    </div> -->
                                    <div class="price pt-3" v-if="row?.search_price && parseInt(row?.search_price) > 0">
                                     <div class="btn_groups float-left">
                                        <Link :href="row?.url || row?.slug" class="orange-btn text-sm">Book Now</Link>
                                     </div>
                                        <p class="text-base float-right"> 
                                        <span class="amount font-bold">
                                            {{showPrice(row?.search_price,true)}}/-
                                        </span>
                                        </p>
                                        <small></small>
                                    </div>
                                </div>
                                <div v-else-if="row.title" class="featured_content">
                                    <div class="title text-sm" v-html="row.title"></div>
                                </div>
                                <div v-else-if="row.name" class="featured_content subhead">
                                  <a class="images" :href="row?.url || row?.slug">
                                    <div class="title text-sm" v-html="row.name"></div>
                                 </a>
                                </div>
                            </div>
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
import { Link } from "@inertiajs/inertia-vue3";
import { showPrice } from "../utils/commonFuntions";
import { SliderSectionWrapper } from "../styles/SliderSectionWrapper";

    export default {
        name:'SliderSection',
        created(){
            if(this.tagName){
                this.tagname = this.tagName;
            }
        },
        data() {
            return {
                tagname : 'section'
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
            showPrice,
        },
        components:{
            Link,
            SliderSectionWrapper
        },
        props:['sectionData', 'withPrice', 'title', 'className', 'slidePerView', 'spacebetween']
    }
</script>