<template>
    <HomeTestimonialWrapper v-if="this.testimonials && this.testimonials.data && this.testimonials.data.length > 0">
        <div class="container">
            <div class="testisec">
                <h2 class="heading2">Unforgettable Travel <br> Experiences</h2>
                <p class="testimonial_sub_title">Whether you're looking for a romantic getaway, a family-friendly adventure, or a
solo journey to explore the world, a travel agency can provide you with a
custom-tailored itinerary that exceeds your expectations.</p>

                <div class="testimonial_slider_wrap">
                    <div class="testimonial_slider_box swiper" ref="sliderRef">
                        <div class="swiper-wrapper">

                            <div class="swiper-slide" v-for="item in this.testimonials.data">

                                

                                <div class="testi_item">
                                    <img class="quote-img" src="../../assets/images/quote-orange.png" alt="">
                                    <p class="font17" v-html="item.description"></p>
                                    <div class="testimonial_item_bottom">
                                        <div class="profile_left">
                                            <img :src="item.thumbSrc" :alt="item.name">
                                        </div>
                                        <div class="profile_right">
                                            <p class="font20">{{ item.name }}</p>
                                            <p class="font14">Interiors and ambiance were worth appreciating</p>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
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
    HomeTestimonialWrapper,
    Link
},
    props: ['featured','limit']
}
</script>