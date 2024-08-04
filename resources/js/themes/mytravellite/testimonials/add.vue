<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <TestimonialAddWrapper>
        <div class="container mb-8">
            <div class="flex justify-between page_top mt-8">
                <h3 class="title">Write a Testimonial</h3>
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