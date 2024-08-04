<template>
    <destination-wrapper class="swiper featured_list destination_package_slider">
        <ul class="swiper-wrapper" v-if="packages">
            <!-- == Package Li == -->
           <template v-for="packageData in packages">
                <PackageCard :packageData="packageData" />
            </template>
        </ul>
    </destination-wrapper>
    <div class="slider_btns">
        <div class="hotel-prev theme-prev"><img src="../../assets/images/next.png" alt="Next Icon"></div>
        <div class="hotel-next theme-next"><img src="../../assets/images/prev.png" alt="Prev Icon"></div>
    </div>
    <div class="view_all_btn text-center mt-5" v-if="viewAllUrl"><Link :href="viewAllUrl">View All</Link></div>
</template>
 
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store.js';
import styled from 'vue3-styled-components';
import PackageCard from '../../components/package/PackageCard.vue';

const DestinationWrapper = styled.div`
    & .package_tour_type_text{
        border: none;
        padding: 0;
        margin: 0;
        line-height: 1.3;
        font-size: 10px;
        padding-bottom: 0.25rem;
    }
    & .featured_box img{
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
    }
`;

export default {
    name:'DestinationPackageSlider',
    created() {

    },
    mounted(){
        new Swiper(".destination_package_slider", {
            slidesPerView: 5,
            spaceBetween:20,
            loop: false,
            speed:1000,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 5,
                },
            },
            navigation: {
                nextEl: ".hotel-next",
                prevEl: ".hotel-prev",
            },
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