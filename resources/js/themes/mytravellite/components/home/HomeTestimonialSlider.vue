<template>
    <HomeTestimonialWrapper v-if="this.testimonials && this.testimonials.data && this.testimonials.data.length > 0">
        <div class="container">
            <div class="testisec">
                <div class="testi_head">
                    <h3 class="fw600">My Travel Lite tour reviews</h3>
                    <p class="font17">What are you waiting for? Pack Your Bags, Adventure Awaits!</p>
                </div>

                <div class="testimonial_slider_wrap">
                    <div class="testimonial_slider_box swiper" ref="sliderRef">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide" v-for="item in this.testimonials.data">
                                
                                <Link class="testi_item" :href="item.url">
                                    <img class="quote-img" src="../../assets/images/quote-orange.png" alt="">
                                    <div class="testimonial_item_bottom">
                                        <div class="profile_left">
                                            <img :src="item.thumbSrc" :alt="item.name">
                                        </div>
                                        <div class="profile_right">
                                            <h4>{{ item.name }}</h4>
                                        </div>
                                    </div>
                                    <p class="font14" v-html="item.description"></p>
                                </Link>


                            </div>

                        </div>
                    </div>
                    <div class="slider_btns">
                        <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-angle-left"></i></div>
                        <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="testi_foot text-center">
                    <Link href="/testimonial" class="btn btn_theme">View All Testimonials</Link>
                </div>
                <!-- <div class="testimonial_nav_dots" ref="sliderPagination"></div> -->
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
            // var sliderPaginationElement = this.$refs.sliderPagination;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;

            new Swiper(sliderElem, {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: false,
                speed:1000,
                // pagination: {
                //     el: sliderPaginationElement,
                //     clickable: true
                // },
                navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1.3,
                    },
                    640: {
                        slidesPerView: 1.3,
                    },
                    768: {
                        slidesPerView: 3,
                    },
                    1024: {
                        slidesPerView: 3,
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