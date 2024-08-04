<template v-if="sectionData.data && sectionData.data.length > 0">
    <PopularHolidayWrapper>
        <div class="container">
            <div class="section_title">
                <h3 class="title fw600">{{ title }}</h3>
                <p class="subtitle">{{ subTitle }}</p>
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
    import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { PopularHolidayWrapper } from '../../styles/PopularHolidayWrapper';

export default {
    name:"Popular Holidays",
    created() {
        this.loadThemes();
    },
    data() {
        return {
            store,
            loading : false,
            sectionData:{
                'data': [],
                'url': '',
                'links': ''
            },
        }
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
    methods:{
        loadThemes(){
            var currentObj = this;
            currentObj.loading = true;
            let form_data = {};
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
            axios.post('/ajaxHomeThemes',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    let sectionData = {};
                    sectionData['data'] = response.data?.results?.data;
                    sectionData['url'] = currentObj.viewAllUrl;
                    sectionData['links'] = response.data?.results?.links;
                    currentObj.sectionData = sectionData;
                    currentObj.loading = false;
                } else {
                    currentObj.loading = false;
                    alert('Something went wrong, please try again.');
                }
            });
        },
    },
    components: {
        PopularHolidayWrapper,
        Link
    },
     props: ["featured", "limit", "viewAllUrl", "title","subTitle"],
}
</script>