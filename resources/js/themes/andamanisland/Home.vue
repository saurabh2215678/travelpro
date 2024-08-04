<template>
    <SearchFormWithBanner
    :isHomepage="true"
    />
    <AboutHomePage/>

    <HomeDestinationGallery title="Top Islands in Andaman & Nicobar" :limit="6"/>

    <HomePackageSlider :isActivity="0" :featured="1" :limit="10" title="Andaman Luxury Packages" subTitle="We have some exciting Andaman luxury packages that you can explore with your friends and family." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/andaman-luxury-packages`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="andaman-luxury-packages" />

    <HomePackageSlider :isActivity="0" :featured="1" :limit="10" title="Economical Andaman Tour Packages" subTitle="Choose from a wide range of economical Andaman tour packages & enjoy the beautiful state of sun, sand & sea." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/economical-andaman-tour-packages`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="economical-andaman-tour-packages" />

    <HomePackageSlider :isActivity="0" :featured="1" :limit="10" title="Andaman Honeymoon Packages" subTitle="If you are planning to visit the Andaman for spending your honeymoon, then we present to you the best Andaman Honeymoon Packages." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/andaman-honeymoon-packages`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="andaman-honeymoon-packages" />

    <div class="home_bg">
        <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Romantic Candle Light Dinner In Andaman Islands" subTitle="Make your evening unique and unforgettable with your loved one by having a romantic candlelight meal on Havelock Island's gorgeous beaches." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/romantic-candle-light-dinner-in-andaman-islands`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="romantic-candle-light-dinner-in-andaman-islands" />

        <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Andaman Water Activities Tour Packages"
        subTitle="Choose from a wide range of economical Andaman tour packages & enjoy the beautiful state of sun, sand & sea." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/water-sports-in-andaman-islands`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="water-sports-in-andaman-islands" />

        <!-- <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Andaman Water Sports" :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/andaman-water-sports`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="water-sports-in-andaman-islands" /> -->
    </div>

    <ScubaDivingSection />
    <HotelTabs/>

    <HomeTestimonialSlider featured="1" limit="10"/>

    <CertifySection/>

    <WeddingAtBeach/>

    <!-- <HomeDestinationSlider2/> -->
    <div class="blog_bg">
        <HomeBlogSlider 
            featured="1" 
            limit="4" 
            title="Andaman Islands Travel Guide" 
            subTitle="Whether you're looking for a romantic getaway, a family-friendly adventure, or a solo journey to explore the world, a travel agency can provide you with a custom-tailored itinerary that exceeds your expectations."
            className="pb-0" 
            categorySlug="travel-guide" 
        />
    </div>

    <!-- <HomeBlogSlider featured="1" limit="4" title="Our Latest Blog" className="pt-0" /> -->
    <HomeBlogGrid
        featured="1" 
        limit="5" 
        title="Blogs & News" 
        subTitle="Roaming Tales"
    />

    <FaqSection identitfer="home" title="Andaman Islands FAQs" :faqs_sections="faqs"/>

    <!-- <ClientsSection :clients="clients"/> -->

    <HomeExperience/>


    
</template>
<style scoped>
.home_bg{
    padding: 2.6rem 0;
    margin-top: 2rem;
    background-color: #f3faff;
}
.blog_bg{
    background-color: #f5f5f5;
}
</style>
<script>
import Layout from './components/layout.vue';
import { store } from './store';
import { Link } from "@inertiajs/inertia-vue3";
import HomeBlogSlider from './components/home/HomeBlogSlider.vue'; 
import HomePageBanner from './components/home/HomePageBanner.vue';
import AdventureIdeas from './components/home/AdventureIdeas.vue';
import PopularHolidays from './components/home/PopularHolidays.vue';
import TrustedProfessional from './components/home/TrustedProfessional.vue';
import HomeDestinationGallery from './components/home/HomeDestinationGallery.vue';
import HomeTestimonialSlider from './components/home/HomeTestimonialSlider.vue';
import {clients} from './data';
import ClientsSection from './components/home/ClientsSection.vue';
// import NewsLetterSection from './components/home/NewsLetterSection.vue';
import SearchFormWithBanner from './components/SearchFormWithBanner.vue';
import AboutHomePage from './components/home/AboutHomePage.vue';
import HomeDestinationSlider from './components/home/HomeDestinationSlider.vue';
import SliderSection from './components/SliderSection.vue';
import HomePackageSlider from './components/home/HomePackageSlider.vue';
import HomeHotelSlider from './components/home/HomeHotelSlider.vue';
import ScubaDivingSection from './components/home/ScubaDivingSection.vue';
import CertifySection from './components/home/CertifySection.vue';
import WeddingAtBeach from './components/home/WeddingAtBeach.vue';
import HomeDestinationSlider2 from './components/home/HomeDestinationSlider2.vue';
import FaqSection from './components/home/FaqSection.vue';
import HomeExperience from './components/home/HomeExperience.vue';
import HotelTabs from './components/home/HotelTabs.vue';
import HomeBlogGrid from './components/home/HomeBlogGrid.vue';


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
    HomeBlogSlider,
    Link,
    HomePageBanner,
    AdventureIdeas,
    PopularHolidays,
    TrustedProfessional,
    HomeDestinationGallery,
    HomeTestimonialSlider,
    ClientsSection,
    // NewsLetterSection,
    SearchFormWithBanner,
    AboutHomePage,
    HomeDestinationSlider,
    SliderSection,
    HomePackageSlider,
    HomeHotelSlider,
    ScubaDivingSection,
    CertifySection,
    WeddingAtBeach,
    HomeDestinationSlider2,
    FaqSection,
    HomeExperience,
    HotelTabs,
    HomeBlogGrid
},
    layout: Layout,
    props: ["seo_data","faqs"]
};
</script>