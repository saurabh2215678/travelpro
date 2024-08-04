<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    
    <TestimonialAddWrapper>
        <div class="container mb-8">
            <div class="flex justify-between page_top mt-8">
                <h3 class="title">{{showLangTitle('WriteATestimonial')}}</h3>
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
import { store } from '../store';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import TestimonialForm from '../components/testimonial/TestimonialForm.vue';
import { TestimonialAddWrapper } from '../styles/TestimonialAddWrapper';
import {showLangTitle} from '../utils/commonFuntions';

const breadcrumbData = [{url: '/', label: showLangTitle('Home')},{url: '/testimonial', label: showLangTitle('Testimonials')}, {label: showLangTitle('Add')}];

export default {
    name:'TestimonialAdd',
    created() {
        store.seoData = this.seo_data;
        // console.log('seo_data=',this.store);
    },
    data() {
        return {
            store,
            breadcrumbData
        }
    },
    methods: {
        showLangTitle,
    },
    components:{
        SearchForm,
        Breadcrumb,
        SearchFormWithBanner,
        TestimonialForm,
        TestimonialAddWrapper
    },
    layout: Layout,
    props: ["testimonialData","name_title_arr","seo_data"],
}
</script>