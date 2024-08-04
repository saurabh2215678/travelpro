<template>
    <AdventureWrapper>
        <div class="container">
            <img src="../../assets/images/sail_bg.png" alt="" class="sail_bg">
            <div class="section_title text-center mb-5">
                <p class="title_small">{{ subtitle }}</p>
                <h2 class="title_big">{{ title }}</h2>
            </div>
            <div class="section_content">
                <div class="section_grid">

                    <div class="swiper section_grid_slider" ref="sliderRef">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide" v-for="item in sectionData.data">
                                <div class="section_item">
                                    <div class="adv_img_box">
                                        <img v-if="item.thumbSrc" :src="item.thumbSrc" />
                                        <img v-else src="../../assets/images/no_image.jpg" />
                                    </div>
                                    <div class="section_content">
                                        <h3 class="section_card_title" v-html="item.title"></h3>
                                        <p class="section_card_description" v-html="item.desc"></p>
                                        <Link :href="item.url" class="btn">Continue</Link>
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
        </div>
        <img src="../../assets/images/full_bg_advantage.jpg" alt="" class="full_bg">

    </AdventureWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { AdventureWrapper } from '../../styles/AdventureWrapper'; 

export default {
        name:"Adventure Ideas",
        created() {
        },
        mounted(){
            var sliderElem = this.$refs.sliderRef;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;

            var slidesCount = 4;
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
                    slidesPerView: 1,
                    },
                    640: {
                    slidesPerView: 2,
                    },
                    768: {
                    slidesPerView: 2,
                    },
                    992: {
                    slidesPerView: 3,
                    },
                    1025: {
                    slidesPerView: slidesCount,
                    },
                },
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });
        },
        components: {
            AdventureWrapper,
            Link
        },
        props: ["sectionData", "title", "subtitle"],
    }
</script>