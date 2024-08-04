<template>
    <section class="home_blog home_featured">
        <div class="xl:container xl:mx-auto">
            <div class="row">
                <div class="theme_title mb-6">
                    <div class="title text-2xl">Travel News and Blogs</div>
                    <div class="view_all_btn"><a :href="store.websiteSettings?.BLOG_URL">View All</a>
                    </div>
                </div>
                <div class="blog_right">
                    <div class="slider_box">
                        <div class="swiper blog_slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" v-for="blog in newsBlogs">
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
                            <div class="blog-prev theme-prev"><img
                                src="../assets/images/next.png"
                                alt="Next Icon"></div>
                            <div class="blog-next theme-next"><img
                                src="../assets/images/prev.png"
                                alt="Prev Icon"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { store } from '../store.js';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    name:'BlogSlider',
    created() {
    },
    data() {
        return {
            store,
        }
    },
    methods:{ 
    },
    components: {
      
        Link
    },
    props: ["newsBlogs"],
}
</script>