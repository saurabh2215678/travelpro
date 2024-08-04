<template>
    <BlogDetailCardWrapper class="blog_detail_wrapper">
        <img class="skeleton_loading" :src="data.blogSrc" :alt="data.title"/>
        <div class="author_and_date">
            <div class="author_sec">
                <i class="fa-solid fa-user"></i>
                <p>{{showLangTitle('Author')}} : {{ data.author }}</p>
            </div>
            <div class="date_sec">
                <i class="fa-regular fa-calendar"></i>
                <p>{{showLangTitle('Date')}} : {{ data.blog_date }}</p>
            </div>
        </div>
        <div class="blog_categories">
            <span>{{showLangTitle('Categories')}} :</span>
            <Link v-for="item in categories" :href="item.cat_url">{{ item.category_name }}</Link>

        </div>
        <div class="blog_desc" v-html="data.description" ref="blogDescRef"></div>
        <div class="share_sec">
            <div class="share pb-3 font-bold">{{showLangTitle('Share')}}</div>
            <SocialShare :shareUrl="this.blogUrl" v-if="this.blogUrl"/>
        </div>
    </BlogDetailCardWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import SocialShare from '../../components/SocialShare.vue';
import { BlogDetailCardWrapper } from '../../styles/BlogDetailCardWrapper';
import {showLangTitle} from '../../utils/commonFuntions';

export default {
    name:'BlogDetailCard',
    created(){
        this.blogUrl = this.data.url;
    },
    data(){
        return{
            blogUrl : ''
        }
    },
    mounted(){
        const blogDescElement = this.$refs.blogDescRef;
        var sliderElem = blogDescElement.querySelector('.swiper');
        var sliderNextBtn = blogDescElement.querySelector('.theme-next');
        var sliderPrevBtn = blogDescElement.querySelector('.theme-prev');
        new Swiper(sliderElem, {
                slidesPerView: 3,
                spaceBetween: 15,
                loop: true,
                speed:1000,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
                
                breakpoints: {
                    0: {
                    slidesPerView: 1.1,
                    spaceBetween: 30
                    },
                    640: {
                    slidesPerView: 1.4,
                    spaceBetween: 30
                    },
                    768: {
                    slidesPerView: 1.8,
                    spaceBetween: 20,
                    },
                    1024: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    },
                },
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });
    },
    methods: {
        showLangTitle,
    },
    components:{
        BlogDetailCardWrapper,
        Link,
        SocialShare
    },
    props:['data', 'categories']
}
</script>