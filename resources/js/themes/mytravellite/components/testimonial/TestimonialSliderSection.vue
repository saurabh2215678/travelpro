<template>
    <SectionWrapper>
        <div class="container">
            <h3 class="title" v-if="data.length > 0">Similar Testimonials</h3>
            <div class="slider_box">
                <div class="testimonial_slider swiper" ref="sliderRef">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide" v-for="item in data">
                            <TestimonialCard :data="item"/>
                        </div>

                    </div>
                </div>
                <div class="slider_btns">
                    <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
                </div>
            </div>
        </div>
    </SectionWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import TestimonialCard from './TestimonialCard.vue';

const SectionWrapper = styled.section`
padding: 1rem 0 4rem;
overflow: hidden;
& .title{
    font-size: 1.45rem;
    font-weight: 600;
    color: var(--theme-color);
    margin-bottom: 1.3rem;
    text-transform: uppercase;
}
& .slider_box {
    position: relative;
    & .swiper{
        overflow:visible;
        & .swiper-slide{
            height: auto;
           &>*{
            opacity: 0;
            transition :0.5s;
           }
           &.swiper-slide-visible>*{
                opacity: 1;
            }
        }
    }
    & .slider_btns {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
        width: calc(100% + 3rem);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        &>*{
            width: 2.5rem;
            height: 2.5rem;
            background-color: var(--theme-color);
            color: var(--white);
            display: grid;
            place-items: center;
            border-radius: 50%;
            font-size: 0.85rem;
            pointer-events: all;
            transition: all ease 0.5s;
            &:hover{
                background-color: var(--secondary-color);
            }
        }
        &>.swiper-button-disabled{
            background-color: #c8c8c8;
            cursor: no-drop;
        }
        &>.swiper-button-lock{
            display:none;
        }
    }
}
`
export default {
    name:'TestimonialSliderSection',
    components:{
        SectionWrapper,
        TestimonialCard
    },
    mounted() {
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = 5;
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
    props: ["data"]
}
</script>