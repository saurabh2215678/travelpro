<template>
    <destination-wrapper class="destination-slider-section">
        <div class="container">

            <div class="theme_title mb-6">
                <div class="top_title font30 fw700">{{destination.name}} Tour Packages</div>
                <div class="view_all_btn text-center" v-if="viewAllUrl"><Link :href="viewAllUrl">View All</Link></div>
            </div>
           
            <div class="swiper" ref="sliderRef">
                <ul class="swiper-wrapper" v-if="packages">
                    <template v-for="packageData in packages">
                        <PackageCard :packageData="packageData" />
                    </template>
                </ul>
            </div>
            
            <div class="slider_btns">
                <div class="slider_btn" ref="sliderPrevRef"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="slider_btn" ref="sliderNextRef"><i class="fa-solid fa-chevron-right"></i></div>
            </div>
        </div>
    </destination-wrapper>
</template>
 
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store.js';
import PackageCard from '../../components/package/PackageCard.vue';
import { DestinationWrapper } from "../../styles/destinationWrapper";

export default { 
    name:'DestinationPackageSlider',
    mounted(){
        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderPrevRef;
        var sliderPrevBtn = this.$refs.sliderNextRef;

        new Swiper(sliderElem, {
            slidesPerView: 5,
            spaceBetween:20,
            loop: false,
            speed:1000,
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                640: {
                    slidesPerView: 2.5,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 5,
                },
            },
            navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
            watchSlidesProgress: true,
        });

        
    },
    data() {
        return {
            store,
        }
    },
    methods:{ 
    },
    components:{
        PackageCard,
        'destination-wrapper' : DestinationWrapper,
        Link,
    },
    props: ["destination","packages","viewAllUrl"],
}
</script>