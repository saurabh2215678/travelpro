<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <TestimonialPageWrapper class="testimonials">
        <div class="container">
            <div class="flex justify-between mb-3">
                <h1 class="title text-2xl mb-1 color_theme fw600" v-if="store.seoData?.page_title" v-html="store.seoData?.page_title"></h1>
                <Breadcrumb :data="breadcrumbData"/>
            </div>
            
            <div class="text-left mb-5" v-if="store.seoData?.page_brief || store.seoData?.page_description">
                <p v-if="store.seoData?.page_brief" class="text-sm" v-html="store.seoData.page_brief"></p>
                <p v-if="store.seoData?.page_description" class="text-sm" v-html="store.seoData.page_description"></p>
            </div>
            
            <template v-if="this.testimonialList.data && this.testimonialList.data.length > 0">
                <div class="testimonialList">
                    <template v-for="item in this.testimonialList.data">
                        <TestimonialCard :data="item" />
                    </template>
                </div>
                <div class="text-center mt-7">
                    <Pagination :links="this.testimonialList?.links" />
                </div>
            </template>

            <div class="write_testimonial_btn">
                <Link :href="this.WRITE_YOUR_TESTIMONIAL_URL">WRITE YOUR TESTIMONIAL</Link>
            </div>
        </div>
    </TestimonialPageWrapper>
</template>
<script>
import Layout from '../components/layout.vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import TestimonialCard from '../components/testimonial/TestimonialCard.vue';
import { Link } from "@inertiajs/inertia-vue3";
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import { store } from '../store';
import Pagination from '../components/Pagination.vue';
import { TestimonialPageWrapper } from '../styles/TestimonialPageWrapper';


const breadcrumbData = [{url: '/', label: 'Home'},{label: 'Testimonials'}];


export default {
    name:'testimonial',
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.testimonialList = this.results;
    },
    data() {
        return {
            store,
            breadcrumbData,
            testimonialList:{
                "data": [],
                "links": "",
              },
        }
    },
    components:{
        TestimonialPageWrapper,
        Breadcrumb,
        TestimonialCard,
        Link,
        SearchFormWithBanner,
        Pagination,
    },
    layout: Layout,
    props:["seo_data", "search_data", "results", "WRITE_YOUR_TESTIMONIAL_URL"],
}
</script>