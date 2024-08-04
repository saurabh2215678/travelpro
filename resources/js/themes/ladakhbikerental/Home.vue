<template>
    <SearchFormWithBanner
    :isHomepage="true"
    :search="false"
    />
    
<PartnerSection />

<PopularHomeWrapper>
    <!-- <img class="mounten" src="./assets/images/mount-bg.jpg"/> -->
    <HomePackageSlider :isActivity="0" :featured="1" :limit="10" title="Our Popular Guided Tours" subTitle="" :viewAllUrl="`${store.websiteSettings?.PACKAGE_URL}`" viewAllTitle="View all Packages" :itemsPerView="4" themeCategorySlug="" pagination="off"/>
</PopularHomeWrapper>

<div class="bg_img">
    <AllInclusiveTours/>
    
    <!-- <template v-if="widgetData && widgetData['rajasthan']">
            <ThingsTodo :data="widgetData['rajasthan']" />
    </template>

     <template v-if="widgetData && widgetData['indian-himalayas']">
        <DiscoverTheWorld :data="widgetData['indian-himalayas']"/>
    </template> -->
    <HomeDestinationSlider2/>
</div>



<!-- <TrendingThemeWrapper>
    <img class="trending_theme_bg" :src="'./assets/mytravellite/images/trending-packages-bg.jpg'" />
    <HomePackageSlider :isActivity="0" :limit="4" flagSlug="trending-packages" title="Trending Packages" subTitle="Package Perfection: Embark on Adventures with Our Trending Escapes" :viewAllUrl="`${store.websiteSettings?.PACKAGE_URL}?flag=trending-packages`" viewAllTitle="View all Packages" :itemsPerView="4" pagination="off" titleBtnClass="btn btn_outline btn_white" 
    headingTitleClass="title font30 fw700"/>
</TrendingThemeWrapper> -->

<HomeTestimonialSlider featured="1" limit="10"/>

<section>
    <div class="container">
        <div class="img_sec">
            <iframe width="100%" height="600" src="https://www.youtube-nocookie.com/embed/qSuMqn3oT1U?si=0y5WMsYz1dMe72GE?rel=0&amp;autoplay=1&mute=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            <!-- <img src="./assets/images/video.jpg" alt="" class="w-full">
            <div class="play_btn">
                <a href="https://www.youtube.com/watch?v=YFc5JiLQEqQ" data-fancybox="video">
                    <img src="./assets/images/play-icon.png" alt="">
                </a>
            </div> -->
        </div>
    </div>
</section>

<!-- <PopularHolidays :featured="1" :limit="10" title="Popular Themes" subTitle="Explore Our Popular Holiday Packages" :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_URL}`" viewAllTitle="View All" themeCategorySlug="" /> -->

    
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
        subtitle=" "
        title="Our Recent Blogs" 
    />

    <InstagramPosts />
    
    <!-- <AboutHomePage/> -->
    <!-- <NewsLetterSection /> -->

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
import ThingsTodo from './components/ThingsTodo.vue';
import AllInclusiveTours from './components/home/AllInclusiveTours.vue';
import InstagramPosts from './components/home/InstagramPosts.vue';
import PartnerSection from './components/home/PartnerSection.vue';
import {TrendingThemeWrapper} from './styles/TrendingThemeWrapper';
import DiscoverTheWorld from './components/DiscoverTheWorld.vue';
import {PopularHomeWrapper} from './styles/PopularHomeWrapper';
import Newsletter from './components/home/Newsletter.vue';

export default {
    created() {
        store.seoData = this.seo_data;
        console.log('widgetdata', this.widgetData);
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
    ThingsTodo,
    AllInclusiveTours,
    TrendingThemeWrapper,
    DiscoverTheWorld,
    PopularHomeWrapper,
    PartnerSection,
    InstagramPosts,
    Newsletter
},
    layout: Layout,
    props: ["seo_data","featuredPackages","widgetData"]
};
</script>