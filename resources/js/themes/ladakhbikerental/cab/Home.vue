<template>        
    <div class="flight_page" v-if="store.websiteSettings">
        <div class="flight-banner" >
            <div class="xl:container xl:mx-auto container mx-auto">
                <div class="head-search">
                    <FormTopMenu activeForm="cab" />
                    <h3 class="text-lg font-bold pt-1 text-slate-900">Book Car</h3>
                    <SearchCountryForm :destinationLists="destinationLists" TripType="0" :routeList="routeList" :airportLists="airportLists" :sightseeningDestinationLists="sightseeningDestinationLists" />
                </div>
            </div>
        </div>

        <OneWayPageLoader v-if="store.loadingName == 'searchForm'" />

        <!-- FEATURED  SECTION START -->
        <section v-else class="home_featured pb-7 latoFont" v-if="activityPackages"  v-for="sightseeningDestination in originSightSeenRouteData">
            <div class="xl:container xl:mx-auto">
                <div class="text_left p_relative">
                    <div class="theme_title mb-3">
                        <div class="title text-2xl">Sightseeing in {{sightseeningDestination.origin_name}}</div>
                        <div class="view_all_btn"><a :href="sightseeningDestination.view_all_url">View All</a> 
                        </div>
                    </div>
                    <div class="slider_btns">
                        <div class="featured-prev theme-prev"><img
                                :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/next.png`" alt=""></div>
                        <div class="featured-next theme-next"><img
                                :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/prev.png`" alt=""></div>
                    </div>
                    <div class="slider_box">
                        <div>
                            <div class="swiper featured_slider">
                                <ul class="swiper-wrapper">
                                    <template v-for="CabRouteData in sightseeningDestination.data" :key="CabRouteData.id">
                                        <PackageCard :CabRouteData="CabRouteData" />
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FEATURED  SECTION END -->       

    </div>
    <FlightPageLoader v-else/>
</template>

<script>
import SearchCountryForm from '../components/cab/SearchCountryForm.vue';
import PackageCard from '../components/cab/packages/PackageCard.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import OneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import FlightPageLoader from '../skeletons/flightPageLoader.vue';
import FormTopMenu from '../components/FormTopMenu.vue';
export default {
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
    },
    data() {
        return {
            store
        }
    },
    methods: {
        showToast(toastObj){
            $toast.open(toastObj);
        }
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
        SearchCountryForm,
        Layout,
        OneWayPageLoader,
        PackageCard,
        FlightPageLoader,
        FormTopMenu,
    },
    layout: Layout,
    props: ["search_data", "seo_data", "destinationLists", "airportLists", "sightseeningDestinationLists","originSightSeenRouteData", "routeList",  "tourCategories",  "activityPackages", "featuredPackages", "nonFeaturedPackages"],
};
</script>