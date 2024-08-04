<template>    
    <div class="flight_page" v-if="store.websiteSettings">
        <div class="flight-banner">
            <div class="xl:container xl:mx-auto container mx-auto">
                <div class="head-search">
                    <FormTopMenu activeForm="flight" />
                    <h3 class="text-lg font-bold pt-1 text-slate-900">Book flights and explore the world with us.</h3>
                    <SearchCountryForm :airportLists="airportLists" TripType="0" />
                </div>
            </div>
        </div>

        <div class="container_flight_loader" :class="store.loadingName == 'searchForm' ? 'active' : ''">
            <div class="flightloader"> <img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/images/loading-ban.gif`" /> </div>
            <div class="text1">Just a moment, we are searching for the flights on this route.</div>
        </div>
        <OneWayPageLoader v-if="store.loadingName == 'searchForm'" />
        

        <SliderSection 
        v-if="tourCategories && tourCategories.data && tourCategories.data.length > 0" 
        :sectionData="tourCategories"
        title="Packages by Theme"
        />
        <SliderSection 
        v-if="activityPackages && activityPackages.data && activityPackages.data.length > 0" 
        :sectionData="activityPackages"
        :withPrice="true"
        title="Short Activities"
        />

        <SliderSection 
        v-if="featuredPackages && featuredPackages.data && featuredPackages.data.length > 0" 
        :sectionData="featuredPackages"
        :withPrice="true"
        title="Group Tour Packages"
        />

        <SliderSection 
        v-if="nonFeaturedPackages && nonFeaturedPackages.data && nonFeaturedPackages.data.length > 0" 
        :sectionData="nonFeaturedPackages"
        :withPrice="true"
        title="Tailor-made Individual Packages"
        />


    </div>
    <FlightPageLoader v-else/>
</template>

<script>
import SearchCountryForm from '../components/flight/SearchCountryForm.vue';
import PackageCard from '../components/flight/packages/PackageCard.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import { Link } from "@inertiajs/inertia-vue3";
import OneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import FlightPageLoader from '../skeletons/flightPageLoader.vue';
import FormTopMenu from '../components/FormTopMenu.vue';
import SliderSection from '../components/home/SliderSection.vue';
export default {
    name: "Home",
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
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
        SearchCountryForm,
        OneWayPageLoader,
        PackageCard,
        FlightPageLoader,
        FormTopMenu,
        Link,
        SliderSection
    },
    layout: Layout,
    props: ["search_data", "seo_data", "airportLists", "tourCategories", "activityPackages", "featuredPackages", "nonFeaturedPackages"],
};
</script>