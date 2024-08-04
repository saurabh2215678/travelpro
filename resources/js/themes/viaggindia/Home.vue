<template>
    <SearchFormWithBanner
    :isHomepage="true"
    />

    <!-- <HomeDestinationGallery title="Popular Destinations" :limit="6"/> -->


<div class="bg_flower" style="background-image: url(/images/bg_flower.png)">
    <HomeDestinationSlider2/>
    <ScubaDivingSection :widgetDetail="widgetDetail"  />
</div>

<HomeThemeSlider :featured="1" :limit="10" title="OFFERTE" subTitle="" :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_URL}`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="" />

<HomePackageSlider :isActivity="0" :featured="1" :limit="10" title="LE PROPOSTE PIU' VENDUTE" subTitle="" viewAllTitle="" :itemsPerView="4" themeCategorySlug="" />
<AboutHomePage/>


    



    <!-- <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Activities" subTitle="Choose from a wide range of economical Andaman tour packages & enjoy the beautiful state of sun, sand & sea." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/economical-andaman-tour-packages`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="" /> -->

    <!-- <div class="home_bg">
        <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Romantic Candle Light Dinner In Andaman Islands" subTitle="Make your evening unique and unforgettable with your loved one by having a romantic candlelight meal on Havelock Island's gorgeous beaches." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/romantic-candle-light-dinner-in-andaman-islands`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="romantic-candle-light-dinner-in-andaman-islands" />

        <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Andaman Water Activities Tour Packages"
        subTitle="Choose from a wide range of economical Andaman tour packages & enjoy the beautiful state of sun, sand & sea." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/water-sports-in-andaman-islands`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="water-sports-in-andaman-islands" />

        <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Andaman Water Sports" :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/andaman-water-sports`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="water-sports-in-andaman-islands" />
    </div> -->

    
    <!-- <HotelTabs/> -->

    
    <!-- <CertifySection/> -->

    <!-- <WeddingAtBeach/> -->


<div class="bg_testi">

    <HomeTestimonialSlider featured="1" limit="10"/>

    <HomeBlogSlider 
        featured="1" 
        limit="12" 
        title="BLOG & NOTIZIE UTILI" 
        subTitle=""
        className="" 
        categorySlug="travel-guide" 
    />

</div>



<div class="form_conatct">
    <div class="container">
        <h2 class="contact_heading">Contattaci per il tour Personalizzato</h2>
        <formShortCode slug="[home_page_form]" class="left_form" /> 
    </div>
</div>
    <!-- <HomeBlogSlider featured="1" limit="4" title="Our Latest Blog" className="pt-0" /> -->
    <!-- <HomeBlogGrid
        featured="1" 
        limit="5" 
        title="Blogs & News" 
        subTitle="Roaming Tales"
    /> -->

    <!-- <FaqSection identitfer="home" title="Andaman Islands FAQs"/> -->

    <!-- <ClientsSection :clients="clients"/> -->

    <!-- <HomeExperience/> -->
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
.bg_flower{background-size: contain;background-size: 35%;background-repeat: no-repeat;background-position: top center;margin-top: 2rem;}
.form_conatct{background: url(/images/contact_bg.jpg);background-size: cover;    padding: 7.188rem 0;}
.contact_heading{font-size: 55px;font-family: 'Playfair Display', serif;color: rgb(173, 8, 61);font-weight: bold;position: relative;margin-bottom: 3.125rem;}

</style>
<script>
import Layout from './components/layout.vue';
import { store } from './store';
import { Link } from "@inertiajs/inertia-vue3";

import formShortCode from './components/formShortCode.vue';
import HomeThemeSlider from './components/home/HomeThemeSlider.vue';
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
        // console.log('widgetDetail=',this.widgetDetail);
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
    HomeBlogGrid,
    HomeThemeSlider,
    formShortCode,
},
    layout: Layout,
    props: ["seo_data","widgetDetail"]
};
</script>