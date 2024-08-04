<template>
    <SearchFormWithBanner
    title="Search For Place"
    />

    <DestinationListWrapper class="recommendation_places destination-cat pb_10">
        <div class="container">
            <div class="theme-title flex justify-between">
                <h1 class="title text-2xl mb-5" v-if="store.seoData?.page_title">{{store.seoData?.page_title}}</h1>

                <div class="breadcrumb_full">
                    <div class="xl:container xl:mx-auto">
                        <ul class="breadcrumb">
                            <li><Link :href="store.websiteSettings?.FRONTEND_URL">{{showLangTitle('Home')}}</Link></li>
                            <li>{{store.seoData.page_title}}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="disc-title" class="text-left" v-if="store.seoData?.page_brief || store.seoData?.page_description">
                <p v-if="store.seoData?.page_brief" class="text-sm" v-html="store.seoData.page_brief"></p>
                <p v-if="store.seoData?.page_description" class="text-sm" v-html="store.seoData.page_description"></p>
            </div>

            

            <ul class="theme_listing" v-if="results.data && results.data.length > 0">
                <template v-for="destination in results.data">
                    <li>
                        <Link class="tour_category_box" :href="destination.url">
                            <img :src="destination.imageSrc" class="theme_radius" :alt="destination.name" />
                            <div class="text text-center">
                                <span>{{destination.name}}</span>
                                <div class="tour_info">
                                    <span>{{destination.name}}</span>
                                    <p v-html="destination.brief"></p>
                                </div>
                            </div>
                        </Link>
                    </li>
                </template>
            </ul>
            <Pagination :links="this.results?.links" />
        </div>
    </DestinationListWrapper>
</template>

<script>
import SearchForm from '../components/SearchForm.vue';
import Layout from '../components/layout.vue';
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../store';
import Pagination from '../components/Pagination.vue';
import axios from "axios";
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import { DestinationListWrapper } from '../styles/DestinationListWrapper';
import {showLangTitle} from '../utils/commonFuntions';

export default {
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.loadDestinationData();
    },
    data() {
        return {
            test: "test",
            store,
            results: {
                'data': [],
                'total_count':0,
                'links':false,
            },
        }
    },
    methods: {
        showLangTitle,
        loadDestinationData(){
            var currentObj = this;
            let form_data = store?.searchData;
            axios.post('/destination/ajaxDestinationList',form_data)  
            .then(function (response) {  
                if(response.data.success) {
                    currentObj.results = response.data?.results;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
    },
    mounted() {
    },
    beforeDestroy() {
    },
    watch: {
    },
    components: {
        SearchForm,
        Link,
        Pagination,
        SearchFormWithBanner,
        DestinationListWrapper
    },
    layout: Layout,
    props: ["seo_data","search_data"],
};
</script>