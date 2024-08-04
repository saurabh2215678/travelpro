<template>
    <section class="home_featured pb-0 latoFont" :class="className">
        <div class="xl:container xl:mx-auto">
            <div class="theme_title mb-6">
                <div class="title text-2xl">{{ title }}</div>
                <div class="view_all_btn" v-if="sectionData?.url"><a :href="sectionData.url">View All</a></div>
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
                                <div v-else class="featured_content py-4 px-2">
                                    <div class="title text-sm" v-html="row.title"></div>
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
    </section>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { showPrice } from "../../utils/commonFuntions";


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
                slidesPerView: 6,
                spaceBetween:15,
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
                    slidesPerView: 3,
                    },
                    1024: {
                    slidesPerView: 6,
                    },
                },
                watchSlidesVisibility: true
          });

        },
        methods:{        
            showPrice,
        },
        components:{
            Link
        },
        props:['sectionData', 'withPrice', 'title', 'className']
    }
</script>