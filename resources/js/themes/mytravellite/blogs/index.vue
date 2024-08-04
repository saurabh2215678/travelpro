<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <BlogWrapper>
        <div class="container">

            <div class="flex justify-between">
                <h1 class="title">Blog</h1>
                <Breadcrumb :data="breadcrumbData"/>
            </div>
            <div class="blog_sec">
                <div class="blogLeft">

                    <div class="blog_list_wrap" v-if="this.blogList.data && this.blogList.data.length > 0">
                        <div class="blogList" >
                            <BlogCard  v-for="item in this.blogList.data" :data="item"/>
                        </div>
                        <div class="text-center mt-4">
                            <Pagination :links="this.blogList?.links" /> 
                        </div>
                    </div>
                    <div v-else>
                        <h3>No Data Found</h3>
                    </div>
                </div>
                <div class="blog_right">
                    <BlogCategories :data="categories"/>
                </div>
            </div>
        </div>
    </BlogWrapper>
</template>
<script>
import Breadcrumb from '../components/Breadcrumb.vue';
import Layout from '../components/layout.vue';
import BlogCard from '../components/blog/BlogCard.vue';
import BlogCategories from '../components/blog/BlogCategories.vue';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import Pagination from '../components/Pagination.vue';
import { BlogWrapper } from '../styles/BlogWrapper';
import { store } from '../store';

export default {
    name:'BlogListing',
    created(){
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.blogList = this.results;
    },
    data() {
        return {
            store,
            blogList:{
                "data": [],
                "links": "",
            },
        }
    },
    components:{
        BlogWrapper,
        Breadcrumb,
        BlogCard,
        BlogCategories,
        SearchFormWithBanner,
        Pagination
    },
    layout: Layout,
    props: ["seo_data", "search_data", "results", "categories", "breadcrumbData"],
}
</script>