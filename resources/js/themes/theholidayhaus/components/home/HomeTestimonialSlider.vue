<template>
    <HomeTestimonialWrapper v-if="this.testimonials && this.testimonials.data && this.testimonials.data.length > 0">
        <div class="container">
            <div class="testisec flex">
                <div class="testi_head pr-24">
                    <h3 class="fw700 font35 secondary-font theme-color-dark leading-normal pb-5">Explore the World Through Our Customers' Eyes</h3>
                    <p class="font18">Embark on a virtual journey filled with authentic encounters, hidden gems, and unforgettable moments, all through the lens of those who have ventured before you. </p>
                    <div class="testi_foot">
                    <Link href="/testimonial" class="btn btn_theme">All Testimonials</Link>
                </div>
                </div>
 
                <div class="testimonial_slider_wrap w-1/2">
                    <div class="testimonial_slider_box swiper" ref="sliderRef">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide" v-for="item in this.testimonials.data">
                                <Link class="testi_item" :href="item.url">
                                    <!-- <img class="quote-img" src="../../assets/images/quote-orange.png" alt=""> -->
                                    <div class="textbox mb-5 gap-x-3">
                                        <div class="rating flex mb-3">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                        <div class="font18 leading-8" v-html="item.description"></div>
                                    </div>
                                    <div class="testimonial_item_bottom">
                                        <div class="profile_left">
                                            <img :src="item.thumbSrc" :alt="item.name">
                                        </div>
                                        <div class="profile_right">
                                            <h4 class="font16 fw600">{{ item.name }}</h4>
                                            <p class="font15 text-gray-500">{{ item.destination }}</p>
                                        </div>
                                    </div>
                                </Link>


                            </div>

                        </div>
                    </div>
                    <!-- <div class="slider_btns">
                        <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-angle-left"></i></div>
                        <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-angle-right"></i></div>
                    </div> -->
                </div>
                <div class="testimonial_nav_dots" ref="sliderPagination"></div>
            </div>
        </div>
    </HomeTestimonialWrapper>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import { HomeTestimonialWrapper } from '../../styles/HomeTestimonialWrapper';
import { store } from '../../store.js';
import axios from "axios";

export default {
    name:"HomeTestimonialSlider",
    created(){
        this.loadTestimonials();
    },
    data() {
        return {
            store,
            testimonials:{
                'data': [],
                'links': ''
            },
        }
    },
    methods:{
        loadTestimonials(){
            var currentObj = this;
            let form_data = {};
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
            axios.post('/testimonial/ajaxSearchTestimonial',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    currentObj.testimonials = response.data?.results;
                    setTimeout(function(){
                        currentObj.initSlider();
                    },50)
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
        initSlider() {
            var sliderElem = this.$refs.sliderRef;
            var sliderPaginationElement = this.$refs.sliderPagination;
            // var sliderNextBtn = this.$refs.sliderNextRef;
            // var sliderPrevBtn = this.$refs.sliderPrevRef;

            new Swiper(sliderElem, {
                //slidesPerView: 1,
                spaceBetween: 20,
                loop: false,
                speed:1000,
                pagination: {
                    el: sliderPaginationElement,
                    clickable: true
                },
                // navigation: {
                //     nextEl: sliderPrevBtn,
                //     prevEl: sliderNextBtn,
                // },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    640: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 1,
                    },
                    1024: {
                        slidesPerView: 1,
                    },
                },
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
          });
        }
    },
    mounted() {

    },
    components:{
    HomeTestimonialWrapper,
    Link
},
    props: ['featured','limit']
}
</script>