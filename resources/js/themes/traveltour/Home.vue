<template>
    <HomePageBanner/>
    <HomeSearch />

    <AdventureIdeas
    v-if="tourCategories && tourCategories.data && tourCategories.data.length > 0" 
    :sectionData="tourCategories"
    subtitle="Find Your Best Adventure"
    title="Adventure Ideas"
    />

    <PopularHolidays
    v-if="featuredPackages && featuredPackages.data && featuredPackages.data.length > 0" 
    :sectionData="featuredPackages"
    subtitle="Explore Packages By"
    title="Popular Holidays"
    />

    <TrustedProfessional/>

    <HomeDestinationSlider :featured="1" :limit="6" :isGallery="true" title="Our Popular Tours Type" subTitle="Destination" :viewAllUrl="store.websiteSettings?.DESTINATION_URL" viewAllTitle="View All Destinations" />

    <template v-if="testimonials && testimonials.length > 0">
        <HomeTestimonial :testimonials="testimonials"/>
    </template>

    <template v-if="newsBlogs && newsBlogs.length > 0">
        <BlogSlider 
        :sectionData="newsBlogs"
        title="Travel tips, hacks, tricks and a whole lot more..."
        />
    </template>

    <ClientsSection :clients="clients"/>
    <NewsLetterSection/>

</template>
<script>
import Layout from './components/layout.vue';
import { store } from './store';
import { Link } from "@inertiajs/inertia-vue3";
import BlogSlider from './components/home/BlogSlider.vue';
import HomePageBanner from './components/home/HomePageBanner.vue';
import HomeSearch from './components/home/HomeSearch.vue';
import AdventureIdeas from './components/home/AdventureIdeas.vue';
import PopularHolidays from './components/home/PopularHolidays.vue';
import TrustedProfessional from './components/home/TrustedProfessional.vue';
import HomeDestinationSlider from './components/home/HomeDestinationSlider.vue';
import HomeTestimonial from './components/home/HomeTestimonial.vue';
import {clients} from './data';
import ClientsSection from './components/home/ClientsSection.vue';
import NewsLetterSection from './components/home/NewsLetterSection.vue';

export default {
    created() {
        store.seoData = this.seo_data;
    },
    data() {
        return {
            store,
            clients
        }
    },
    methods: {

    },
    mounted() {
        document.body.classList.add('home_page');
    },
    beforeUnmount() {
        document.body.classList.remove('home_page');
    },
    watch:{
    },
    components: {
        BlogSlider,
        Link,
        HomePageBanner,
        HomeSearch,
        AdventureIdeas,
        PopularHolidays,
        TrustedProfessional,
        HomeDestinationSlider,
        HomeTestimonial,
        ClientsSection,
        NewsLetterSection
    },
    layout: Layout,
    props: ["tourCategories", "activityPackages", "featuredPackages", "nonFeaturedPackages","featuredAccommodations","testimonials","newsBlogs","teams", "seo_data"],
};
</script>