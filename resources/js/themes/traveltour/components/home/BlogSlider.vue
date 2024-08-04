<template>
    <HomeBlogWrapper class="home_blog home_featured">
        <div class="container">
            <div class="row">
                <div class="section_title mb-5">
                    <p class="title_small">{{ subtitle }}</p>
                    <h2 class="title_big">{{ title }}</h2>

                    <div class="slider_btns" >
                        <div class="blog-prev theme-prev" ref="sliderNextRef"><i class="fa-solid fa-chevron-left"></i></div>
                        <div class="blog-next theme-next" ref="sliderPrevRef"><i class="fa-solid fa-chevron-right"></i></div>
                    </div>
                </div>
                <div class="blog_right">
                    <div class="slider_box">
                        <div class="swiper blog_slider" ref="sliderRef">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" v-for="blog in sectionData">
                                    <Link class="blog_box" :href="blog.url">
                                    <img class="theme_radius"
                                        :src="blog.blogSrc"
                                        :alt="blog.title" />
                                    <div class="text">
                                        <h4 class="title">{{blog.title}}</h4>
                                        <p v-html="blog.brief"></p>
                                    </div>
                                    </Link>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="view_all_btn">
                    <Link href="/blog">View All</Link>
                </div>
            </div>
        </div>
    </HomeBlogWrapper>
</template>

<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import { HomeBlogWrapper } from '../../styles/HomeBlogWrapper';

export default {
    name:'BlogSlider',
    created() {
        console.log('Blog sectionData', this.sectionData);
    },
    mounted() {
            var sliderElem = this.$refs.sliderRef;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;

            new Swiper(sliderElem, {
                slidesPerView: 3,
                spaceBetween: 0,
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
    data() {
        return {
            store,
        }
    },
    methods:{ 
    },
    components: {
        HomeBlogWrapper,
        Link
    },
    props: ["sectionData", "subtitle", "title"],
}
</script>