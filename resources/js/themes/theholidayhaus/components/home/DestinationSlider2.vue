<template>
    <DestinationBox2Wrapper class="destination_box_2_wrapper">
        <div class="title_box">
            <div class="left_item">
                <h1 class="title secondary-font theme-color-dark">Top {{ slider_type }} Destinations</h1>
                <p>{{ subTitle }}</p>
            </div>
            <div class="right_btn">
                <Link :href="viewAllUrl" class="btn btn_theme">View all</Link>
            </div>
        </div>
        <div class="destination_slider">
            <div class="slider_box"> 
                <!-- <div class="slider_btns"> 
                    <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-angle-left"></i></div>
                    <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-angle-right"></i></div>
                </div> -->
                <div class="noswiper" ref="">
                    <div class="noswiper-wrapper grid gap-6 md:grid-cols-4 sm:grid-cols-2">
                        <div class="noswiper-slide" v-for="item in destinations">
                            <DestinationCardForSliders :item="item" className="dst_card" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </DestinationBox2Wrapper>
</template>
<script>
import {DestinationBox2Wrapper} from '../../styles/DestinationBox2Wrapper';
import { Link } from "@inertiajs/inertia-vue3";
import DestinationCardForSliders from './DestinationCardForSliders.vue'
export default {
    name : 'InternationalDestinationSlider',
    mounted() {
        
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;
        var slidesCount = 5;
        var spacebetween = 20;

        const destNewSlider = new Swiper(sliderElem, {
            slidesPerView: slidesCount,
            spaceBetween: spacebetween,
            init: false,
            loop: false,
            loopedSlides: slidesCount * 2,
            loopAddBlankSlides: true,
            speed:1000,
            navigation: {
                nextEl: sliderPrevBtn,
                prevEl: sliderNextBtn,
            },
            breakpoints: {
                0: {
                slidesPerView: 1.5,
                spaceBetween: 10
                },
                640: {
                slidesPerView: 2,
                spaceBetween: 20
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

        const currentObj = this;
        destNewSlider.on('afterInit', function () {
            currentObj.setCardStyle();
        });
        destNewSlider.on('init', function () {
            currentObj.setCardStyle();
        });
        destNewSlider.on('slideChange', function () {
            currentObj.setCardStyle();
        });
        destNewSlider.init();

    },
    methods:{
        setCardStyle(){
            const cardElements = this.$refs.sliderRef?.querySelectorAll('.detail_box');
            cardElements.forEach(item =>{
                const cardheight = item?.offsetHeight;
                item?.style.setProperty("--box-height", `${cardheight}px`);
            });

        }
    },
    components:{
        DestinationBox2Wrapper,
        Link,
        DestinationCardForSliders
    },
    props:['destinations', 'slider_type','viewAllUrl','subTitle']
}
</script>