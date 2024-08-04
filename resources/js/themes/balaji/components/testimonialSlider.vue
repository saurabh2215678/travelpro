<template>
    <section class="home_testimonial home_featured " v-if ="testimonials">
        <div class="lg:container lg:mx-auto">
            <div class="theme_title mb-6 justify-center	">
                <div class="title">Customer Reviews</div>
            </div>
            <div class="slider_box">
                <div class="swiper testimonial_slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="testimonial in testimonials">
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
                        <a :href="testimonial.url" class="tsti_hover">
                            <div class="testimonial_box testcont ml-5">
                                    <!-- <div class="testi_heading pb-1 text-2xl Lato"> 
                                        <a
                                        :href="store.websiteSettings?.TESTIMONIAL_URL"> {{testimonial.title}}</a>
                                    </div> -->
                                    <img src="../assets/images/test_quotes.png" />
                                    <div v-html="testimonial.description" class="testi_text"></div>
                                    <div class="client_info">
                                            <div class="client_name pt-2">
                                                <div class="name para_lg2">
                                                    {{testimonial.name}}
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
                        src="../assets/images/left_arrow.png" alt="Next Icon">
                    </div>
                    <div class="testimonial-next theme-next">
                        <img
                        src="../assets/images/right_arrow.png" alt="Prev Icon">
                    </div>
                </div>
            
            </div>
        </div>
    </section>
</template>

<script>
import { store } from '../store.js';
import { Link } from "@inertiajs/inertia-vue3";
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
    components: {

        Link
    },
    props: ["testimonials"],
}
</script>