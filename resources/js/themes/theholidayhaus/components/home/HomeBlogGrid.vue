<template>
    <HomeBlogGridWrapper class="HomeBlogGrid">
        <div class="container">
            <div class="blog_top">
                <div class="title_wrapper">
                    <div class="title_left">
                        <p class="font19">{{ subTitle }}</p>
                        <h3 class="font40 fw600">{{ title }}</h3>
                    </div>
                    <div class="title_right">
                        <a class="view_all" href="/blog">View All</a>
                    </div>
                </div>
            </div>

           
            <div class="blog_grid">
                <ul>
                    <li v-for="item in blogs.data">
                        <div class="blog_box">
                            <div class="blog_image">
                                <img :src="item.blogThumbSrc" alt="item.title">
                            </div>
                            <div class="blog_content">
                                <span class="pill">{{ item.blog_date }}</span>
                                <h3 class="blog_title">{{ item.title }}</h3>
                                <p class="blog_desc">{{ item.brief }}</p>
                                <Link class="blog_link" :href="item.url">Read More</Link>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </HomeBlogGridWrapper>
</template>
<script>
import {HomeBlogGridWrapper} from '../../styles/HomeBlogGridWrapper';
import axios from "axios";
import { Link } from "@inertiajs/inertia-vue3";

export default{
    name : 'HomeBlogGrid',
    created() {
        this.loadBlogs();
    },
    data() {
        return {
            blogs:{
                'data': [],
                'links': ''
            },
        }
    },
    methods:{
        loadBlogs(){
            var currentObj = this;
            let form_data = {};
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
            axios.post('/blog/ajaxSearchBlog',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    currentObj.blogs = response.data?.results;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        }
    },
    components: {
        HomeBlogGridWrapper,
        Link
    },
    props:['title', 'subTitle', 'limit', 'featured']
}
</script>