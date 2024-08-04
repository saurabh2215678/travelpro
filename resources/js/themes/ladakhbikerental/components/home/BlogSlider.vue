<template>
    <HomeBlogWrapper class="home_blog home_featured" v-if="this.blogs && this.blogs.data && this.blogs.data.length > 0">
        <div class="container">
            <div class="row">
                <div class="section_title mb-5">
                    <p class="title_small" v-if="subtitle">{{ subtitle }}</p>
                    <h3 class="title fw600 font30 color_dark">{{ title }}</h3>

                    <!-- <div class="slider_btns" >
                        <div class="blog-prev theme-prev" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                        <div class="blog-next theme-next" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
                    </div> -->
                </div>
                <div class="blog_right">
                    <div class="slider_box">
                        <div class="swiper blog_slider" ref="sliderRef">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" v-for="blog in blogs.data">
                                    <Link class="blog_box" :href="blog.url">
                                    <img class="theme_radius"
                                        :src="blog.blogSrc"
                                        :alt="blog.title" />
                                    <div class="text">
                                        <span class="pill text-white">{{ blog.blog_date }}</span>
                                        <h4 class="title">{{blog.title}}</h4>
                                    </div>
                                    </Link>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- <div class="view_all_btn">
                    <Link href="/blog" class="btn btn_theme">View All</Link>
                </div> -->
            </div>
        </div>
    </HomeBlogWrapper>
</template>

<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import { HomeBlogWrapper } from '../../styles/HomeBlogWrapper';
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
                        currentObj.mounted();
                    },50)
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
    mounted() {
            var sliderElem = this.$refs.sliderRef;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;

            new Swiper(sliderElem, {
                slidesPerView: 2,
                spaceBetween: 0,
                loop: false,
                speed:1000,
                spaceBetween:30,
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
                    slidesPerView: 2,
                    },
                },
                watchSlidesVisibility: true
          });

        }
    },
    components: {
        HomeBlogWrapper,
        Link
    },
    props: ["featured", "limit", "title", "subTitle"],
}
</script>