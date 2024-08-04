<template>
<section class="home_testimonial home_featured" v-if ="this.testimonials && this.testimonials.data && this.testimonials.data.length > 0">
        <div class="lg:container lg:mx-auto">
            <div class="theme_title mb-6">
                <div class="title text-2xl">Testimonials</div>
                <div class="view_all_btn"><a :href="store.websiteSettings?.TESTIMONIAL_URL">View All</a>
                </div>
            </div>
            <div class="slider_box">
                <div class="swiper testimonial_slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="testimonial in this.testimonials.data">
                            <div class="testimage">
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
                            </div>
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
                    <div class="testimonial-prev theme-prev">
                        <img
                        src="../../assets/images/next.png" alt="Next Icon">
                    </div>
                    <div class="testimonial-next theme-next">
                        <img
                        src="../../assets/images/prev.png" alt="Prev Icon">
                    </div>
                </div>
                <div class="testimonial_all">
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

export default {
    name:'HomeTestimonialSlider',
    created() {
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
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
    },
    components: {

        Link
    },
    props: ['featured','limit'],
}
</script>