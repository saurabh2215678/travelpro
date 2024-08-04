<template>
    <HomeTestimonialWrapper v-if="this.testimonials && this.testimonials.data && this.testimonials.data.length > 0" >
        <div class="container">
            <div class="testisec">
                <img src="../../assets/images/bg_testi_shape.png" class="test_shape_bg"/>
                <h2 class="heading2"><a href="/testimonial"> DICONO DI NOI</a></h2>
               

                <div class="testimonial_slider_wrap">
                    <div class="testimonial_slider_box swiper" ref="sliderRef">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide" v-for="item in this.testimonials.data">
                                <div class="testi_item">
                                    <!-- <img class="quote-img" src="../../assets/images/quote-orange.png" alt=""> -->
                                    <p class="font17" v-html="item.description"></p>
                                    <div class="testimonial_item_bottom">
                                        <!-- <div class="profile_left">
                                            <img :src="item.thumbSrc" :alt="item.name">
                                        </div> -->
                                        <div class="profile_right">
                                            <p class="font20">{{ item.name }}</p>
                                            <!-- <p class="font14">Interiors and ambiance were worth appreciating</p> -->
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="slider_btns">
                        <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-arrow-left-long"></i></div>
                        <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-arrow-right-long"></i></div>
                    </div>
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
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;

            new Swiper(sliderElem, {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: false,
                speed:1000,
                navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                    clickable: true,
                },
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
                        slidesPerView:1,
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