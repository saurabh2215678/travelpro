<template>
    <SliderSectionWrapper class="home_featured pb-0 latoFont" :class="className">
        <div class="container">
            <div class="theme_title mb-6">
                <div class="title">{{ title }}</div>
                <div class="view_all_btn" v-if="sectionData?.url"><a :href="sectionData.url">View All</a></div>
            </div>
            <div class="slider_box">
                <div class="swiper category_slider" ref="sliderRef">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="row in sectionData.data">
                            <Link class="tour_category_box" :href="row?.url || row?.slug">
                                <div class="images">
                                    <img :src="row.thumbSrc"
                                    class="theme_radius0" :alt="row.title">
                                </div>

                                <div class="featured_content px-1.5 py-3.5" v-if="withPrice">
                                    <div class="title text-sm">{{row.title}}</div>
                                    <div class="price" v-if="row?.search_price && parseInt(row?.search_price) > 0">
                                        <p class="text-xs "> 
                                            Starting From : 
                                            <span class="amount heading_sm3">
                                                {{showPrice(row?.search_price,true)}}/-
                                            </span>
                                        </p>
                                        <small></small>
                                    </div>
                                </div>
                                <div v-else-if="row.title" class="featured_content">
                                    <div class="title text-sm" v-html="row.title"></div>
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

            var slidesCount = this.slidePerView ? this.slidePerView : 6;
            var spacebetween = this.spacebetween ? this.spacebetween : 15;

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
                    spaceBetween: 15
                    },
                    640: {
                    slidesPerView: 2,
                    spaceBetween: 15
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