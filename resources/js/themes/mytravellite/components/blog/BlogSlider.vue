<template>
    <BlogSliderWrapper class="blog_slider_wrapper">
        <h3 class="title" v-if="this.blogs.data.length > 0">Similar Blogs</h3>
        <div class="slider_box">
            <div class="blog_slider swiper" ref="sliderRef">
                <div class="swiper-wrapper">

                    <div class="swiper-slide" v-for="blog in this.blogs.data">
                        <BlogCard :data="blog" :isBreif="true"/>
                    </div>

                </div>
            </div>
            <div class="slider_btns">
                <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
            </div>
        </div>
    </BlogSliderWrapper>
</template>
<script>
import BlogCard from './BlogCard.vue';
import { BlogSliderWrapper } from '../../styles/BlogSliderWrapper';
import { store } from '../../store.js';
import axios from "axios";

export default {
    name:'BlogSlider',
    created() {
        this.loadBlogs();
    },
    data() {
        return {
            store,
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
            form_data['category_slug'] = this.categorySlug;
            axios.post('/blog/ajaxSearchBlog',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    currentObj.blogs = response.data?.results;
                    setTimeout(function(){
                        currentObj.initSlider();
                    },50)
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
        initSlider() {
            var sliderElem = this.$refs.sliderRef;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;

            new Swiper(sliderElem, {
                slidesPerView: 3,
                spaceBetween: 20,
                loop: false,
                speed:1000,
                navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
                breakpoints: {
                    0: {
                    slidesPerView: 1,
                    },
                    640: {
                    slidesPerView: 1.5,
                    },
                    768: {
                    slidesPerView: 2,
                    },
                    1024: {
                    slidesPerView: 3,
                    },
                },
                watchSlidesVisibility: true
          });
        },
    },
    components:{
        BlogSliderWrapper,
        BlogCard
    },
    props: ["featured", "limit", "title", "subTitle", "className", "categorySlug"],
}
</script>