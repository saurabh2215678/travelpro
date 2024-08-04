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
            <div class="flightloader"> <img :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/loading-ban.gif`" /> </div>
            <div class="text1">Just a moment, we are searching for the flights on this route.</div>
        </div>
        <OneWayPageLoader v-if="store.loadingName == 'searchForm'" />
        
        <template v-else>
            <section class="home_featured pb-0 latoFont" v-if="tourCategories">
                <div class="xl:container xl:mx-auto">
                    <div class="theme_title mb-6">
                        <div class="title text-2xl">Packages by Theme</div>
                        <div class="view_all_btn"><a :href="tourCategories.url">View All</a></div>
                    </div>
    
                    <div class="slider_box">
                        <div class="swiper category_slider ">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" v-for="row in tourCategories.data">
                                    <Link class="tour_category_box" :href="row.url">
                                        <div class="images">
                                            <img :src="row.thumbSrc"
                                                class="theme_radius0">
                                        </div>
                                        <div class="featured_content py-4 px-2">
                                            <div class="title text-sm" v-html="row.title"></div>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                        </div>
                        <div class="slider_btns">
                            <div class="cat-next theme-next"><img :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/next.png`" alt="">
                            </div>
                            <div class="cat-prev theme-prev"><img :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/prev.png`" alt="">
                            </div>
                        </div>
                    </div>
    
                </div>
            </section>
    
            <!-- FEATURED  SECTION START -->
            <section class="home_featured pb-0 latoFont" v-if="activityPackages">
                <div class="xl:container xl:mx-auto">
                    <div class="text_left p_relative">
                        <div class="theme_title mb-6">
                            <div class="title text-2xl">Short Activities</div>
                            <div class="view_all_btn"><a :href="activityPackages.url">View All</a>
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
                                        <template v-for="packageData in activityPackages.data" :key="packageData.id">
                                            <PackageCard :packageData="packageData" />
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- FEATURED  SECTION END -->
    
            <!-- FEATURED  SECTION START -->
            <section class="home_featured latoFont" v-if="featuredPackages">
                <div class="xl:container xl:mx-auto">
                    <div class="text_left p_relative">
                        <div class="theme_title mb-6">
                            <div class="title text-2xl">Group Tour Packages</div>
                            <div class="view_all_btn"><a :href="featuredPackages.url">View All</a>
                            </div>
                        </div>
                        <div class="slider_btns">
                            <div class="featured-prev theme-prev"><img
                                    :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/next.png`" alt=""></div>
                            <div class="featured-next theme-next"><img
                                    :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/prev.png`" alt=""></div>
                        </div>
                        <div class="slider_box">
                            <div class="swiper featured_slider">
                                <div class="swiper-wrapper">
                                    <template v-for="packageData in featuredPackages.data" :key="packageData.id">
                                        <PackageCard :packageData="packageData" />
                                    </template>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
            </section>
            <!-- FEATURED  SECTION END -->
    
    
            <!-- NON FEATURED  SECTION START -->
            <section class="home_featured bggray latoFont" v-if="nonFeaturedPackages">
                <div class="xl:container xl:mx-auto">
                    <div class="text_left p_relative">
                        <div class="theme_title mb-6">
                            <div class="title text-2xl">Tailor-made Individual Packages</div>
                            <div class="view_all_btn"><a :href="nonFeaturedPackages.url">View All</a>
                            </div>
                        </div>
                        <div class="slider_btns">
                            <div class="featured-prev theme-prev"><img
                                    :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/next.png`" alt=""></div>
                            <div class="featured-next theme-next"><img
                                    :src="`${store.websiteSettings.FRONTEND_ASSETS_URL}/images/prev.png`" alt=""></div>
                        </div>
                        <div class="slider_box">
                            <div class="swiper featured_slider1">
                                <div class="swiper-wrapper">
                                    <template v-for="packageData in nonFeaturedPackages.data" :key="packageData.id">
                                        <PackageCard :packageData="packageData" />
                                    </template>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
            </section>
            <!-- FEATURED  SECTION END -->
        </template>

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
        Link
    },
    layout: Layout,
    props: ["search_data", "seo_data", "airportLists", "tourCategories",  "activityPackages", "featuredPackages", "nonFeaturedPackages"],
};
</script>