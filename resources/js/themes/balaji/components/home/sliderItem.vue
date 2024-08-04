<template>

    <div class="theme_title lg_title mb-6">
        <div class="title">{{ this.title }}</div>
        <div class="title_right">
            <div class="view_all_btn"><a :href="this.viewAllUrl">{{ this.viewAllTitle }}</a></div>
            <div class="slider_btns">
                <div class="cat-next theme-next" ref="prevBtnRef"><img src="../../assets/images/next.png" alt=""></div>
                <div class="cat-prev theme-prev" ref="nextBtnRef"><img src="../../assets/images/prev.png" alt=""></div>
            </div>
        </div>
    </div>
    <div class="slider_box">
        <div class="theme_title mb_title mb-6">
            <div class="title ">{{ this.title }}</div>
        </div>
        <div class="swiper category_slider " ref="sliderRef">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="destination in this.destinations.data">
                    <a class="tour_category_box" :href="destination.url">
                        <div class="images">
                            <img v-if="destination.imageSrc" :src="destination.imageSrc" class="theme_radius0" :alt="destination.name">
                            <img v-else :src="destination.thumbSrc" class="theme_radius0" :alt="destination.name">
                        </div>
                        <div class="theme_content py-4 px-2 test_class">
                            <div class="days_flex" v-if="destination.days && destination.nights">
                                <div>{{destination.days}} Days</div> /
                                <div>{{destination.nights}} Nights</div>
                            </div>
                            <div v-if="destination.name" class="title text-sm" v-html="destination.name"></div>
                            <div v-else class="title text-sm" v-html="destination.title"></div>
                            <div class="price" v-if="destination.search_price && parseInt(destination.search_price) > 0">
                                <p class="text-xs "> Starting From : <span class="amount heading_sm3">{{showPrice(destination.search_price,hideDecimal=true)}}
                                /-</span></p>
                                <small></small>
                            </div>
                        </div>
                    </a>
                </div>
                
            </div>
        </div>

    </div>

</template>
<script>
import {showPrice} from '../../utils/commonFuntions';
export default {
    created(){
        console.log('title', this.title);
        console.log('destinations', this.destinations.data);
    },
    methods:{        
        showPrice,
    },
    mounted(){
        var sliderElement = this.$refs.sliderRef;
        var nextButton = this.$refs.nextBtnRef;
        var prevButton = this.$refs.prevBtnRef;
        new Swiper(sliderElement, {
            slidesPerView: 6,
            spaceBetween:15,
            loop: false,
            speed:1000,
            navigation: {
                nextEl: nextButton,
                prevEl: prevButton,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1.5,
                },
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: this.itemsPerView ? this.itemsPerView : 6,
                },
            },
            watchSlidesVisibility: true
            });
},
    props: ['destinations', 'title', 'viewAllTitle', 'viewAllUrl', 'itemsPerView']
}
</script>