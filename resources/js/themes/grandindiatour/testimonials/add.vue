<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <TestimonialAddWrapper>
        <div class="xl:container xl:mx-auto container">
            <div class="breadcrumb_inner">
                <h1 class="title text-2xl mb-1 color_theme fw600">Write a Testimonial</h1>
                <Breadcrumb :data="breadcrumbData"/>
            </div>
            <TestimonialForm :nameTitleArr="name_title_arr"/>
        </div>
    </TestimonialAddWrapper>
</template>
<script>
import Layout from '../components/layout.vue';
import SearchForm from '../components/SearchForm.vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import TestimonialForm from '../components/testimonial/TestimonialForm.vue';
import { TestimonialAddWrapper } from '../styles/TestimonialAddWrapper';

const breadcrumbData = [{url: '/', label: 'Home'},{url: '/testimonial', label: 'Testimonials'}, {label: 'Add'}];

export default {
    name:'TestimonialAdd',
    data() {
        return {
            breadcrumbData
        }
    },
    components:{
        SearchForm,
        Breadcrumb,
        SearchFormWithBanner,
        TestimonialForm,
        TestimonialAddWrapper
    },
    layout: Layout,
    props: ["testimonialData","name_title_arr"],
}
</script>