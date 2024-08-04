<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <BlogDetailWrapper>
        <div class="container">

            <div class="flex justify-between">
                <h3 class="title">{{ blogDetails.title }}</h3>
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
import { dummyBlogs, dummyPackageData } from '../data';
import BlogDetailCard from '../components/blog/BlogDetailCard.vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import GreatDealSlider from '../components/blog/GreatDealSlider.vue';
import BlogForm from '../components/blog/BlogForm.vue'
import BlogSlider from '../components/blog/BlogSlider.vue';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import { BlogDetailWrapper } from '../styles/BlogDetailWrapper';

export default {
    created() {
        // console.log('blogDetails ==', this.blogDetails);
        // console.log('relatedBlogsObj ==', this.relatedBlogsObj);
        // console.log('explore_package ==', this.explore_package);
        // console.log('categories ==', this.categories);
    },
    name:'BlogDetail',
    data() {
        return {
            blogs : dummyBlogs,
            blog : dummyBlogs[0],
            dummyPackageData
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
    props:["blogDetails","relatedBlogsObj","explore_package","categories","breadcrumbData"],
}
</script>