<template>
    <TestimonialCardWrapper class="testimonial_card">
        <Link :href="data.url">
            <img :alt="data.name" :src="data.testimonialThumbSrc"/>
            <div class="testimonial_bottom">
                <div class="testimonial_author">
                    <img :src="data.imageSrc" :alt="data.name">
                    <p>{{ data.name }}</p>
                </div>
                <div class="card_info" v-html="data.description"></div>
            </div>
        </Link>
    </TestimonialCardWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { TestimonialCardWrapper } from '../../styles/TestimonialCardWrapper';

export default {
    name:'TestimonialCard',
    created(){
         console.log('testimonials', this.data);
    },
    components:{
        TestimonialCardWrapper,
        Link
    },
    props:['data']
}
</script>