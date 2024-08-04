<template>
    <section class="swiper banner_slider" ref="sliderRef">
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-for="banner_image in this.banner_images" :style="`background-image: url(${banner_image.src})`">
                <div class="banner_item" >
                    <div class="container">
                        <div class="banner_content">
                            <div class="banner_content_inner">
                                <div class="lg_text heading" v-if="banner_image.title">{{banner_image.title}}</div>
                                <div class="lg_sm heading_sm1" v-if="banner_image.sub_title">{{banner_image.sub_title}}</div>
                                <ul class="banner_list">
                                    <li v-if="banner_image.link_1">
                                        <a :href="banner_image.link_1" hreflang="en">
                                            {{banner_image.link_text_1}}
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                                    <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                                                </svg>
                                            </span>
                                        </a>
                                    </li>

                                    <li v-if="banner_image.link_2">
                                        <a :href="banner_image.link_2" hreflang="en">
                                            {{banner_image.link_text_2}} 
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                                                    <path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/>
                                                </svg>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
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
    name:'HomeBannerSlider',
    created() {
        this.loadHomeBanners();
    },
    mounted () {
        var sliderElem = this.$refs.sliderRef;
        //var sliderPagination = this.$refs.sliderPagination;

        var slidesCount = 1;
        var spacebetween = 0;

        new Swiper(sliderElem, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            parallax: true,
            effect: 'fade',
            grabCursor: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });


        // console.log(StarIcon);
    //     var bannerSlider = new Swiper(".banner_slider", {
    //       parallax: true,
    //       effect: 'fade',
    //       speed:1000,
    //       autoplay: {
    //          delay: 3000,
    //      },
    //      loop: true,
    //      pagination: {
    //          el: ".banner-pagination",
    //          clickable: true
    //      },
    //  });
    },
    data() {
        return {
            store,
            banner_images: []
        }
    },
    methods:{
        loadHomeBanners(){
            var currentObj = this;
            let form_data = {};
            form_data['limit'] = this.limit;
            axios.post('/ajaxHomeBanners',form_data)
            .then(function (response) {
                if(response.data.success) {
                    currentObj.banner_images = response.data?.banner_images;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
    },
    components: {
        Link
    },
    props: ["limit"],
};
</script>