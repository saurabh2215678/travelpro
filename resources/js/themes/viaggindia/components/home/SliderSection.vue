<template>
    <HomeThemeWrapper class="home_featured theme_sec" :class="className">
        <div class="container">
            <div class="title_box justify-center">
                <h2 class="main_heading">{{ title }}</h2>
                <!-- <div class="view_all_btn" v-if="sectionData?.url"><a :href="sectionData.url">View All</a></div> -->
            </div>
            <div class="slider_box">
                <div class="swiper category_slider" ref="sliderRef">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="row in sectionData.data">
                            <Link class="tour_category_box" :href="row.url">
                                <div class="images">
                                    <img :src="row.thumbSrc"
                                    class="theme_radius0" :alt="row.title">
                                </div>

                                <div class="featured_content md:px-8 md:py-6" v-if="withPrice">
                                    <h5 class="title">{{row.title}}</h5>
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
                                <div v-else class="featured_content py-4 px-2">
                                    <h5 class="title" v-html="row.title"></h5>
                                </div>
                            </Link> 
                        </div>
                    </div>
                </div>
                <div class="slider_btns">
                    <div class="cat-next theme-next" ref="sliderNextRef"><img src="../../assets/images/next.png" alt=""></div>
                    <div class="cat-prev theme-prev" ref="sliderPrevRef"><img src="../../assets/images/prev.png" alt=""></div>
                </div>
            </div>
        </div>
    </HomeThemeWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { showPrice } from "../../utils/commonFuntions";
import {HomeThemeWrapper} from '../../styles/HomeThemeWrapper.js'






    export default {
        name:'SliderSection',
        created(){
            console.log('sectionData ==', this.sectionData);
        },
        mounted() {
            var sliderElem = this.$refs.sliderRef;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;

            new Swiper(sliderElem, {
                slidesPerView: 4,
                spaceBetween:40,
                loop: false,
                speed:1000,
                navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
                breakpoints: {
                    0: {
                    slidesPerView: 1.5,
                    spaceBetween:20,
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
                watchSlidesVisibility: true
          });

        },
        methods:{        
            showPrice,
        },
        components:{
            Link,
            HomeThemeWrapper
        },
        props:['sectionData', 'withPrice', 'title', 'className']
    }
</script>