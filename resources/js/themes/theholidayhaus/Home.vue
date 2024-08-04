<template>
    <SearchFormWithBanner
    :isHomepage="true"
    :search="false"
    />
    <template v-if="widgetData && widgetData['the-holiday-haus']">
        <TheHolidayHaus :data="widgetData['the-holiday-haus']"/>
    </template>

    <HomeDestinationSlider2 bgClass="" slider_type="international" subTitle="Places Which You Always Wanted To Explore!" :viewAllUrl="`${store.websiteSettings?.DESTINATION_URL}?flag=international`" />

    <PopularHolidays 
    :featured="1" :limit="10" title="Travel By Interest" subTitle="These packages are meant to cater to every one of your interests, and that too without hampering your comfort in the travel." :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_URL}`" viewAllTitle="View All" themeCategorySlug="" />
    
    <HomeDestinationSlider2 bgClass="domestic_bg" slider_type="domestic" subTitle="Discover India In Luxury Explore India with The Holiday Haus, These packages are meant to cater to every one of your interests, and that too without hampering your comfort in the travel." :viewAllUrl="`${store.websiteSettings?.DESTINATION_URL}?flag=domestic`"/>

    <template v-if="widgetData && widgetData['home-about-us']">
        <HomeAboutUs :data="widgetData['home-about-us']"/>
    </template>

    <template v-if="widgetData && widgetData['customized-tour-packages']">
            <CustomizedTourPackages :data="widgetData['customized-tour-packages']"  :viewAllUrl="`${store.websiteSettings?.PACKAGE_URL}`"/>
    </template>

    <HomeTestimonialSlider featured="1" limit="10"/>
    
    <AllInclusiveTours/>

    <!-- <TrendingThemeWrapper>
        <img class="trending_theme_bg" :src="'./assets/mytravellite/images/trending-packages-bg.jpg'" />
        <HomePackageSlider :isActivity="0" :limit="4" flagSlug="trending-packages" title="Trending Packages" subTitle="Package Perfection: Embark on Adventures with Our Trending Escapes" :viewAllUrl="`${store.websiteSettings?.PACKAGE_URL}?flag=trending-packages`" viewAllTitle="View all Packages" :itemsPerView="4" pagination="off" titleBtnClass="btn btn_outline btn_white" 
        headingTitleClass="title font30 fw700"/>
    </TrendingThemeWrapper> -->


    <!-- <PopularHomeWrapper>
        <img class="mounten" src="./assets/images/mount-bg.jpg"/>

        <HomePackageSlider 
        :isActivity="0" :featured="1" :limit="10" title="Popular Packages" subTitle="These popular destinations have a lot to offer" :viewAllUrl="`${store.websiteSettings?.PACKAGE_URL}`" viewAllTitle="View all Packages" :itemsPerView="4" themeCategorySlug="" pagination="off"/>
    </PopularHomeWrapper> -->

    <!-- <template v-if="widgetData && widgetData['discover-the-world-specially-curated-for-you']">
        <DiscoverTheWorld :data="widgetData['discover-the-world-specially-curated-for-you']"/>
    </template> -->

    <!-- <template v-if="newsBlogs && newsBlogs.length > 0">
        <BlogSlider 
        :sectionData="newsBlogs"
        subtitle="&nbsp;"
        title="Travel tips, hacks, tricks and a whole lot more..."
        />
    </template> -->

    <BlogSlider 
        featured="1"
        limit="4"
        subtitle="Embark on adventures, discover cultures, and create unforgettable memories with us"
        title="A Travel Guide And Expert Advice" 
    />
     
    <AboutHomePage />

    <Popup className="pop_wrap-main">
        <div class="modal-content">
        <div class="modal-header">
            <span class="heading"></span>
        </div>
        <div class="modal-body">
            <div id="full_cont">
                <img class="footer-logo2 pt-3" src="./assets/images/logo.png" style=" margin: 0 auto; " />
                <div class="text-center">
                    <span class="mlogo mb-5"><img class="footer-logo2 pt-3" src="./assets/images/footer-logo2.png" /></span>
                </div>
                <!-- <div class="text-center">
                <a class="btn btn_theme" href="javascript:void(0)">Buy Now</a>
            </div> -->
            </div>
        </div>
        </div>
    </Popup>
    

</template>



<style scoped>

</style>
<script>
import Layout from './components/layout.vue';
import { store } from './store';
import { Link } from "@inertiajs/inertia-vue3";
import BlogSlider from './components/home/BlogSlider.vue';
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
import CustomizedTourPackages from './components/home/CustomizedTourPackages.vue';
import AllInclusiveTours from './components/home/AllInclusiveTours.vue';
import {TrendingThemeWrapper} from './styles/TrendingThemeWrapper';
import TheHolidayHaus from './components/home/TheHolidayHaus.vue';
import HomeAboutUs from './components/home/HomeAboutUs.vue';
import {PopularHomeWrapper} from './styles/PopularHomeWrapper';
import Popup from './components/popup.vue';


export default {
    created() {
        store.seoData = this.seo_data;
        console.log('widgetdata', this.widgetData);
    },
    data() {
        return {
            store,
            clients,
        }
    },
    methods: {
        openPopup() {
            store.popupActive = true;
        }
    },
    mounted() {
        document.body.classList.add('home_page');
        this.openPopup();
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
    CustomizedTourPackages,
    AllInclusiveTours,
    TrendingThemeWrapper,
    TheHolidayHaus,
    HomeAboutUs,
    PopularHomeWrapper,
    Popup
},
    layout: Layout,
    props: ["seo_data","featuredPackages","widgetData"]
};
</script>