<template>
    <TestimonialListWrapper class="destination_testi bg-gray-100 mt-7" v-if="testimonials">
        <div class="container">
            <div class="box_style testimonials_inner">
                <div class="theme_title mb-3">
                    <div class="top_title">Testimonials</div>
                </div>

                <div class="testimonial_list_outer swiper" ref="sliderRef"> 
                    <div class="swiper-wrapper">
                    <div class="testimonial-item swiper-slide" v-for="testimonial,key in testimonials">
                        <div class="bg_tesi_box shadow-md bg-white">
                            <span class="quotes"><i class="fa-solid fa-quote-left"></i></span>
                            <p class="text-sm" v-html="testimonial.description"></p>
                            <div class="flex customer_name_img items-center mt-5">
                                <a :href="testimonial.url">
                                    <div class="customer_img">
                                        <img :src="testimonial.thumbSrc" :alt="testimonial.name" />
                                    </div>
                                </a>
                                <div class="customer_name"><a :href="testimonial.url" class="text-base font-semibold">{{testimonial.name}}</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="slider_btns relative z-50 flex justify-center pt-5">
                    <div class="slider_btn p-3" ref="sliderPrevRef"><i class="fa-solid fa-chevron-left"></i></div>
                    <div class="slider_btn p-3" ref="sliderNextRef"><i class="fa-solid fa-chevron-right"></i></div>
                </div>

                <div class="view_all_btn text-center mt-5" v-if="viewAllUrl">
                    <a :href="viewAllUrl" >View All</a>
                </div>
            </div>
        </div>
    </TestimonialListWrapper>
</template>
<script>
import { TestimonialListWrapper } from '../../styles/TestimonialListWrapper';

export default {
    name:'DestinationTestimonialSlider',
    mounted(){
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderPrevRef;
        var sliderPrevBtn = this.$refs.sliderNextRef;

        new Swiper(sliderElem, {
            slidesPerView: 1,
            spaceBetween:20,
            loop: false,
            autoHeight:true,
            speed:1000,
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
            navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
            watchSlidesProgress: true,
        });

        
    },
    components:{
        TestimonialListWrapper
    },
    props: ["destination","testimonials","viewAllUrl"],
}
</script>