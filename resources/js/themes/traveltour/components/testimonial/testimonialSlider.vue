<template>
    <TestimonialWrapper class="home_testimonial home_featured" v-if ="testimonials">
        <div class="container">
            <div class="theme_title mb-6">
                <div class="title text-2xl">Testimonials</div>
                <div class="view_all_btn"><a :href="store.websiteSettings?.TESTIMONIAL_URL">View All</a>
                </div>
            </div>
            <div class="slider_box">
                <div class="swiper testimonial_slider" ref="sliderRef">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="testimonial in testimonials">
                            <InnerImageSlider :images="testimonial.images"/>
                            <div class="testimonial_box testcont ml-5">
                                <div class="testi_heading pb-1 text-2xl Lato">
                                    <a
                                    :href="store.websiteSettings?.TESTIMONIAL_URL"> {{testimonial.title}}</a>
                                </div>
                                <p v-html="testimonial.description"></p>
                                <div class="client_info">
                                    <a :href="testimonial.url">
                                        <div class="client_name pt-2">
                                            <div class="h-50">
                                                <img class="h-10"
                                                :src="testimonial.thumbSrc"
                                                :alt="testimonial.name">
                                            </div>
                                            <div class="name para_lg2">
                                                {{testimonial.name}}
                                            </div>
                                        </div>
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
    </TestimonialWrapper>
</template>

<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import InnerImageSlider from './InnerImageSlider.vue';
import { TestimonialWrapper } from '../../styles/TestimonialWrapper';

export default {
    name:'TestimonialSlider',
    created() {
        // console.log('inside testinmonial component', this.testimonials);
    },
    data() {
        return {
            store,
        }
    },
    methods:{ 
    },
    mounted(){
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = 2;
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
                autoHeight: true,
                },
                640: {
                slidesPerView: 1,
                autoHeight: true,
                },
                883: {
                slidesPerView: 2,
                autoHeight: false,
                },
                1024: {
                slidesPerView: slidesCount,
                autoHeight: false,
                },
            },
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });
    },
    components: {
    TestimonialWrapper,
    Link,
    InnerImageSlider
},
    props: ["testimonials"],
}
</script>