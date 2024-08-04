<template>
    <HomeBlogWrapper class="home_blog home_featured" :class="className" v-if="this.blogs && this.blogs.data && this.blogs.data.length > 0" >
        <div class="container">

            <div class="blog_inner">
                <div class="title_box justify-center">
                    <h2 class="main_heading">{{ title }}</h2>
                    <p class="sub-title">{{ subTitle }}</p>
                </div>
                
                <div class="blog_slider">
                    <div class="slider_box">
                        <div class="swiper" ref="sliderRef">
                            <div class="swiper-wrapper">

                                <div class="swiper-slide" v-for="blog in this.blogs.data">
                                    <div class="blogbox">
                                        <div class="blogshadow">
                                            <div class="blogimg" :style="`background: url('${blog?.blogThumbSrc}') center center no-repeat; background-size: cover;`">
                                                <Link :href="blog?.url">
                                                    <img src="../../assets/images/blankact.png" :alt="blog?.title">
                                                </Link>
                                            </div>
                                            <div class="scontent">
                                                <div class="blogdate">{{blog?.blog_date}}</div>
                                                <div class="btitle">
                                                    <a :href="blog?.url">{{ blog?.title }}</a>
                                                </div>
                                                <div class="blog_brief"><p>{{ blog?.brief }}</p></div>
                                                 <a :href="blog?.url" class="read_more_btn">{{showLangTitle('ReadMore')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="slider_btns">
                            <div class="slider_btn_next" ref="sliderNextRef"><i class="fa-solid fa-arrow-left"></i></div>
                            <div class="slider_btn_prev" ref="sliderPrevRef"><i class="fa-solid fa-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </HomeBlogWrapper>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import { HomeBlogWrapper } from '../../styles/HomeBlogWrapper';
import { store } from '../../store.js';
import axios from "axios";
import {showLangTitle} from '../../utils/commonFuntions';


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
        showLangTitle,
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
                spaceBetween: 40,
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
        }
    },
    mounted() {},
    components: {
        HomeBlogWrapper,
        Link
    },
    props: ["featured", "limit", "title", "subTitle", "className", "categorySlug"],
}
</script>