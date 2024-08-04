<template>
    <section class="destination_testi" v-if="testimonials">
        <div class="xl:container xl:mx-auto">
            <div class="box_style testimonials_inner">
                <div class="theme_title mb-3">
                    <div class="title text-2xl">Testimonials</div>
                </div>
                <div class="testimonial_list_outer">
                    <div class="testimonial-item" v-for="testimonial,key in testimonials">
                        <div class="bg_tesi_box">
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
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="view_all_btn text-center mt-5" v-if="viewAllUrl">
                    <a :href="viewAllUrl" >View All</a>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import { store } from '../../store.js';
export default {
    name:'DestinationTestimonialSlider',
    created() {

    },
    mounted(){

    },
    data() {
        return {
            store,
        }
    },
    methods:{ 
    },
    components:{

    },
    props: ["destination","testimonials","viewAllUrl"],
}
</script>