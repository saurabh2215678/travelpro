<template>
    

    <section class="home_testimonial home_featured " v-if="this.testimonials && this.testimonials.data && this.testimonials.data.length > 0">
        <div class="lg:container lg:mx-auto">
            <div class="theme_title mb-6 justify-center	">
                <div class="title">Customer Reviews</div>
            </div>
            <div class="slider_box">
                <div class="swiper testimonial_slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="item in this.testimonials.data">
                            <!-- <div class="testimage">
                                <div class="swiper tesiimg_slider">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide" v-for="image in testimonial.images"><img
                                            :src="image.src"
                                            :alt="image.alt">
                                        </div>
                                    </div>
                                    <div class="swiper-button-next"></div> 
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div> -->
                        <a :href="item.url" class="tsti_hover">
                            <div class="testimonial_box testcont ml-5">
                                    <!-- <div class="testi_heading pb-1 text-2xl Lato"> 
                                        <a
                                        :href="store.websiteSettings?.TESTIMONIAL_URL"> {{testimonial.title}}</a>
                                    </div> -->
                                    <img src="../../assets/images/test_quotes.png" />
                                    <div v-html="item.description" class="testi_text"></div>
                                    <div class="client_info">
                                            <div class="client_name pt-2">
                                                <div class="name para_lg2">
                                                    {{item.name}}
                                                </div>
                                            </div>
                                    </div>
                            </div>
                        </a>
                        </div>
                    </div>
                </div>
                <div class="slider_btns">
                    <div class="testimonial-prev theme-prev">
                        <img
                        src="../../assets/images/left_arrow.png" alt="Next Icon">
                    </div>
                    <div class="testimonial-next theme-next">
                        <img
                        src="../../assets/images/right_arrow.png" alt="Prev Icon">
                    </div>
                </div>
            
            </div>
        </div>
    </section>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';

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

            new Swiper(sliderElem, {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: false,
                speed:1000,
                pagination: {
                    el: sliderPaginationElement,
                    clickable: true
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    640: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 2,
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
    Link
},
    props: ['featured','limit']
}
</script>