<template>
    <BlogDetailCardWrapper class="blog_detail_wrapper">
        <img class="skeleton_loading" :src="data.blogSrc"/>
        <div class="author_and_date">
            <div class="author_sec">
                <i class="fa-solid fa-user"></i>
                <p>Author : {{ data.author }}</p>
            </div>
            <div class="date_sec">
                <i class="fa-regular fa-calendar"></i>
                <p>Date : {{ data.blog_date }}</p>
            </div>
        </div>
        <div class="blog_categories">
            <span>Categories :</span>
            <Link v-for="item in categories" :href="item.cat_url">{{ item.category_name }}</Link>

        </div>
        <div class="blog_desc" v-html="data.description"></div>
        <div class="share_sec">
            <div class="share pb-3 font-bold">Share</div>
            <SocialShare :shareUrl="this.blogUrl" v-if="this.blogUrl"/>
        </div>
    </BlogDetailCardWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store';
import SocialShare from '../../components/SocialShare.vue';
import { BlogDetailCardWrapper } from '../../styles/BlogDetailCardWrapper';


export default {
    name:'BlogDetailCard',
    created(){
        store.seoData = this.seo_data;
        this.blogUrl = this.data.url;
    },
    data(){
        return{
            store,
            blogUrl : ''
        }
    },
    components:{
        BlogDetailCardWrapper,
        Link,
        SocialShare
    },
    props:['data', 'categories','seo_data']
}
</script>