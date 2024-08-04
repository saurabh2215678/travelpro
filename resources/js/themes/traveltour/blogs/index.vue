<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <BlogWrapper>
        <div class="container">

            <div class="flex justify-between">
                <h3 class="title">Blog</h3>
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



export default {
    name:'BlogListing',
    created(){
        this.blogList = this.results;
    },
    data() {
        return {
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
    props: ["results","categories","breadcrumbData"],
}
</script>