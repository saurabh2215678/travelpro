<template>
    <PopularHolidayWrapper>
        <div class="container">
            <div class="section_title mb-5">
                <p class="title_small">{{ subtitle }}</p>
                <h2 class="title_big color_theme">{{ title }}</h2>
            </div>
        </div>
        <div class="section_content">
            <div class="swiper popular_holiday_slider" ref="sliderRef">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" v-for="item in sectionData.data">
                        <Link :href="item.url">
                            <img v-if="item.thumbSrc" :src="item.thumbSrc" />
                            <img v-else src="../../assets/images/no_image.jpg" />
                            <span v-html="item.title"></span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PopularHolidayWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { PopularHolidayWrapper } from '../../styles/PopularHolidayWrapper';

export default {
    name:"Popular Holidays",
    created() {
    },
    mounted() {
        var sliderElem = this.$refs.sliderRef;
        new Swiper(sliderElem, {
            slidesPerView: 4,
            spaceBetween:0,
            loop: false,
            speed:1000,
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
                slidesPerView: 4,
                },
            },
            watchSlidesVisibility: true
        });
    },
    components: {
        PopularHolidayWrapper,
        Link
    },
    props: ["sectionData", "title", "subtitle"],
}
</script>