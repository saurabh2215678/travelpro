<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <BlogDetailWrapper>
        <div class="container">

            <div class="flex justify-between">
                <h1 class="title">{{ blogDetails.title }}</h1>
                <Breadcrumb :data="breadcrumbData"/>
            </div>

            <div class="blog_detail_wrap">
                <div class="blog_detail_left">
                    <div class="blog_detail_left_inner">
                        <BlogDetailCard :data="blogDetails" :categories="categories"/>
                        <BlogSlider :data="relatedBlogsObj"/>
                    </div>
                </div>
                <div class="blog_detail_right">
                    <GreatDealSlider :data="explore_package"/>
                    <BlogForm/>
                </div>

            </div>
        </div>
    </BlogDetailWrapper>

    
</template>
<script>
import Layout from '../components/layout.vue';
import BlogDetailCard from '../components/blog/BlogDetailCard.vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import GreatDealSlider from '../components/blog/GreatDealSlider.vue';
import BlogForm from '../components/blog/BlogForm.vue'
import BlogSlider from '../components/blog/BlogSlider.vue';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import { BlogDetailWrapper } from '../styles/BlogDetailWrapper';
import { store } from '../store';

export default {
    name:'BlogDetail',
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
    },
    data() {
        return {
            store
        }
    },
    components: {
        BlogDetailWrapper,
        BlogDetailCard,
        Breadcrumb,
        GreatDealSlider,
        BlogForm,
        BlogSlider,
        SearchFormWithBanner
    },
    layout: Layout,
    props:["seo_data", "search_data", "blogDetails", "relatedBlogsObj", "explore_package", "categories", "breadcrumbData"],
}
</script>