<template>
    <section class="home_blog home_featured" v-if="this.blogs && this.blogs.data && this.blogs.data.length > 0">
        <div class="xl:container xl:mx-auto">
            <div class="row">
                <div class="theme_title mb-6">
                    <div class="title text-2xl">{{title}}</div>
                    <div class="view_all_btn"><a :href="store.websiteSettings?.BLOG_URL">View All &nbsp; <i class="fa fa-angle-down"></i>
                        <ul class="dropmenu">
                            <li><a href="/news">News</a></li>
                            <li><a :href="store.websiteSettings?.BLOG_URL">Blog</a></li>
                        </ul></a>
                    </div>
                </div>
                <div class="blog_right">
                    <div class="slider_box">
                        <div class="swiper" ref="sliderRef">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" v-for="blog in this.blogs.data">
                                    <a class="blog_box" :href="blog.url">
                                    <img class="theme_radius"
                                        :src="blog.blogSrc"
                                        :alt="blog.title" />
                                    <div class="text py-4 px-2">
                                        <span class="title">{{blog.title}}</span>
                                    </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="slider_btns">
                            <div class="blog-prev theme-prev" ref="sliderNextRef"><img
                                src="../../assets/images/next.png"
                                alt="Next Icon"></div>
                            <div class="blog-next theme-next" ref="sliderPrevRef"><img
                                src="../../assets/images/prev.png"
                                alt="Prev Icon"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
export default {
    name:'HomeBlogSlider',
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
            form_data['type'] = 'all';
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
                slidesPerView: 4,
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
                        slidesPerView: 4,
                    },
                },
                watchSlidesVisibility: true
            });
        }
    },
    components: {
      
        Link
    },
    props: ["featured", "limit", "title", "subTitle", "className", "categorySlug"],
}
</script>
<style scoped>
.home_blog .view_all_btn a {
    position: relative;
}
ul.dropmenu {
    position: absolute;
    left: 0;
    visibility: hidden;
    background-color: var(--theme-color);
    top: 100%;
    width: 120px;
    border-radius: 0.3rem;
    z-index: 99;
}
ul.dropmenu li a {
    background: transparent;
    color: #fff;
    width: 100%;
    border-radius: 0.3rem;
}
ul.dropmenu li a:hover {
    background:var(--secondary-color);
}
.view_all_btn a:hover ul.dropmenu{
    visibility: visible;
}
</style>