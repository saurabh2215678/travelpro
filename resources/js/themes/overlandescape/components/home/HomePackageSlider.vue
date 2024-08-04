<template>
    <template v-if="loading">
        <SliderSectionLoader/>
    </template>
    <template v-else>
        <SliderSection 
        v-if="this.sectionData && this.sectionData.data && this.sectionData.data.length > 0" 
        :sectionData="this.sectionData"
        :withPrice="true"
        :title="title"
        />
    </template>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3"; 
import { store } from '../../store.js';
import SliderSection from './SliderSection.vue';
import axios from "axios";
import SliderSectionLoader from "../../skeletons/SliderSectionLoader.vue";

export default{
    name : 'HomePackageSlider',
    created() {
        this.loadPackages();
    },
    data() {
        return {
            store,
            loading : true,
            sectionData:{
                'data': [],
                'url': '',
                'links': ''
            },
            flagData: {
                'slug': this.flagSlug,
                'title': this.title,
                'brief': this.subTitle
            }
        }
    },
    methods:{
        loadPackages(){
            var currentObj = this;
            currentObj.loading = true;
            let form_data = {};
            form_data['is_activity'] = this.isActivity;
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
            form_data['flag_slug'] = this.flagSlug;
            if(this.destinationSlug) {
                if(this.isActivity == 1) {
                    form_data['segments1'] = 'tour-activities';
                } else {
                    form_data['segments1'] = 'tour-packages';
                }
                form_data['segments2'] = this.destinationSlug;
            }
            form_data['theme_category_slug'] = this.themeCategorySlug;
            axios.post('/package/ajaxSearchPackage',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    let sectionData = {};
                    sectionData['data'] = response.data?.results?.data;
                    sectionData['url'] = currentObj.viewAllUrl;
                    sectionData['links'] = response.data?.results?.links;
                    currentObj.sectionData = sectionData;
                    if(response.data.flagData) {
                        currentObj.flagData = response.data.flagData;
                    }
                    setTimeout(function(){
                        currentObj.initSlider();
                    },50)
                    currentObj.loading = false;
                } else {
                    currentObj.loading = false;
                    alert('Something went wrong, please try again.');
                }
            });
        },
        initSlider() {
            var currentObj = this;
            var sliderElem = this.$refs.sliderRef;
            var sliderNextBtn = this.$refs.sliderNextRef;
            var sliderPrevBtn = this.$refs.sliderPrevRef;
            var sliderPagination = this.$refs.sliderPagination;

            var slidesCount = currentObj.itemsPerView?currentObj.itemsPerView:4;
            var spacebetween = 20;

            new Swiper(sliderElem, {
                slidesPerView: slidesCount,
                spaceBetween: spacebetween,
                loop: true,
                speed:1000,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: sliderPrevBtn,
                    prevEl: sliderNextBtn,
                },
                pagination: {
                    el: sliderPagination,
                    clickable: true,
                    dynamicBullets: true,
                },
                breakpoints: {
                    0: {
                    slidesPerView: 1.1,
                    spaceBetween: 30
                    },
                    640: {
                    slidesPerView: 1.4,
                    spaceBetween: 30
                    },
                    768: {
                    slidesPerView: 1.8,
                    spaceBetween: spacebetween,
                    },
                    1024: {
                    slidesPerView: slidesCount,
                    spaceBetween: spacebetween,
                    },
                },
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });
        }
    },
    mounted() {},
    components:{
    SliderSection,
    Link,
    SliderSectionLoader
},
    props:['isActivity', 'featured', 'limit', 'title', 'subTitle', 'viewAllUrl', 'viewAllTitle', 'destinationSlug',  'themeCategorySlug', 'sliderClass', 'itemsPerView','flagSlug']
}
</script>