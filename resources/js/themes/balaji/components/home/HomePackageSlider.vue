<template>
    <section class="home_featured latoFont pb-0" v-if="this.packages && this.packages.data && this.packages.data.length > 0">
        <div class="xl:container xl:mx-auto">
            <!-- <sliderItem
                :destinations="this.packages"
                :title="this.title"
                :viewAllTitle ="this.viewAllTitle"
                :viewAllUrl ="this.viewAllUrl"
                /> -->

            <packagewrapper class="text_left p_relative">

                <sliderItem
                :destinations="this.packages"
                :title="this.title"
                :viewAllTitle ="this.viewAllTitle"
                :viewAllUrl ="this.viewAllUrl"
                :itemsPerView ="this.itemsPerView"
                /> 
                
                <!-- <div class="theme_title mb-6">
                    <div class="title text-2xl" v-html="this.title"></div>
                    <div class="title_right">
                        <div class="view_all_btn" v-if="destinationSlug"><a :href="this.viewAllUrl" v-html="this.viewAllTitle"></a></div>
                        <div class="view_all_btn" v-else><Link :href="this.viewAllUrl" v-html="this.viewAllTitle"></Link></div>
                        <div class="slider_btns">
                            <div class="pindprev theme-prev"><img src="../../assets/images/next.png" alt=""></div>
                            <div class="pindnext theme-next"><img src="../../assets/images/prev.png" alt=""></div>
                        </div>
                    </div>
                </div>

                <div class="slider_box">
                    <div :class="`swiper ${this.sliderClass}`">
                        <div class="swiper-wrapper">
                            <template v-for="packageData in this.packages.data" :key="packageData.id">
                                <PackageCard :packageData="packageData" :isContent="true" />
                            </template>
                        </div>
                    </div>

                </div> -->
            </packagewrapper>
        </div>
    </section>
</template>


<script>
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { store } from '../../store.js';
import PackageCard from '../../components/package/PackageCard.vue';
import sliderItem from './sliderItem.vue';
// import sliderItemVue from './sliderItem.vue';
import styled from 'vue3-styled-components';


const packagewrapper = styled.div`

& .tour_category_box .images{height:18rem;border-radius: 20px;}
`



export default {
    name:'HomePackageSlider',
    created() {
        this.loadPackages();
    },
    data() {
        return {
            store,
            packages:{
                'data': [],
                'links': ''
            },
        }
    },
    methods:{
        loadPackages(){
            var currentObj = this;
            let form_data = {};
            form_data['is_activity'] = this.isActivity;
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
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
                    currentObj.packages = response.data?.results;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
    },
    components: {
        Link,
        PackageCard,
        sliderItem,
        packagewrapper
    },
    props: ['isActivity', 'featured', 'limit', 'title', 'viewAllUrl', 'viewAllTitle', 'destinationSlug',  'themeCategorySlug', 'sliderClass', 'itemsPerView'],
}
</script>