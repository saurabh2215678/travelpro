<template>        
    <div class="flight_page" v-if="store.websiteSettings">
        <SearchBannerWrapper class="flight-banner" >
            <div class="xl:container xl:mx-auto mx-auto">
                <div class="head-search">
                    <FormTopMenu activeForm="cab" />
                    <h3 class="text-lg font-bold pt-1 text-slate-900">Book Car</h3>
                    <SearchCountryForm TripType="1" />
                </div>
            </div>
        </SearchBannerWrapper>

        <OneWayPageLoader v-if="store.loadingName == 'searchForm'" />

        <!-- FEATURED  SECTION START -->
        <template v-else-if="cabsSightseeings && cabsSightseeings.length > 0">
            <section class="home_featured pb-7 latoFont">
                <div class="xl:container xl:mx-auto">
                    <div class="text_left p_relative">
                        <div class="theme_title mb-3">
                            <div class="title text-2xl">Cab Sightseeing</div>
                            <div class="view_all_btn"><a :href="`${store.websiteSettings.CAB_URL}/sightseeing`">View All</a> 
                            </div>
                        </div>
                        <div class="slider_btns">
                            <div class="featured-prev theme-prev"><img
                                    :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/images/next.png`" alt=""></div>
                            <div class="featured-next theme-next"><img
                                    :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/images/prev.png`" alt=""></div>
                        </div>
                        <div class="slider_box">
                            <div>
                                <div class="swiper featured_slider">
                                    <ul class="swiper-wrapper">
                                        <template v-for="CabRouteData in cabsSightseeings" :key="CabRouteData.id">
                                            <PackageCard :CabRouteData="CabRouteData" />
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <CabContentWrapper class="static-cab-content">
                <div class="xl:container xl:mx-auto">
                    <h5 class="product_item_h5 font-bold text-3xl text-center pb-2">Affordable On-time cabs</h5>
                    <div class="product_item_h2 text-center pb-7">Grand India Tour offers airport railway station pick drop service outstation cabs</div>
                    <div class="flex gap-4 pb-5">
                        <div class="w-1/3 product_padding_col">
                            <div class="product-thumb">
                                <div class="cabs">
                                <div class="card-header-sustainable">
                                    <img class="cabs_img1" src="../assets/images/Airport-Cab.jpg">
                                </div>
                                <img class="cabs_img2" src="../assets/images/Airport_Cabs.png">
                                <div class="card-body card_cabs">
                                    <div class="elementor-heading-title pt-4">
                                        <a class="cabs_a text-xl font-semibold" href="#">Airport Cabs</a>
                                    </div>
                                    <p class="text-sm">Preparing for a significant flight and seeking a stylish ride to the airport? Grand India Tour is your premier airport cab service. Our fleet boasts pristine vehicles equipped with cutting-edge technology, and our drivers are rigorously vetted for your safety and comfort, guaranteeing an exceptional travel experience.</p>
                                    <p class="card-footer card_footer_airport">
                                    </p>
                                    <ul class="card_footer_ul flex pt-3">
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/On_time_Cabs.png"></li>
                                            <li class="outstation_li_txt">On-Time cabs</li>
                                        </div>
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/Pocket_Friendly.png"></li>
                                            <li class="outstation_li_txt">Pocket  Friendly</li>
                                        </div>
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/Cashless_Rides.png"></li>
                                            <li class="outstation_li_txt">Cashless Rides</li>
                                        </div>
                                    </ul>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/3 product_padding_col">
                            <div class="product-thumb">
                                <div class="cabs">
                                <div class="card-header-Robust">
                                    <img class="cabs_img1" src="../assets/images/Railway-Station-Cabs.jpg">
                                    
                                </div>
                                <img class="cabs_img2" src="../assets/images/Railway_Station_Icon.png">
                                <div class="card-body card_cabs">
                                    <div class="heading-title pt-4">
                                        <a class="cabs_a text-xl font-semibold" href="#">Railway Station Cabs</a>
                                    </div>
                                    <p class="text-sm">Indulge in a truly luxurious experience as you travel to the Railway Station with the Grand India Tour. Feel the comfort envelop you, allowing you to daydream about the exciting adventures that await you. Our commitment to excellence ensures that every journey to the Railway Station is a seamless and delightful one.</p>
                                    <p class="card-footer card_footer_airport">
                                    </p>
                                    <ul class="card_footer_ul flex pt-3">
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/Zero_Cancellations.png"></li>
                                            <li class="outstation_li_txt">
                                            Zero- Cancellation
                                            </li>
                                        </div>
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/Advance_Booking.png"></li>
                                            <li class="outstation_li_txt">
                                            Advance Bookings
                                            </li>
                                        </div>
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/247_call_centre.png"></li>
                                            <li class="outstation_li_txt">
                                                Well-trained Drivers
                                            </li>
                                        </div>
                                    </ul>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/3 product_padding_col">
                            <div class="product-thumb">
                                <div class="cabs">
                                <div class="card-header-frugal">
                                    <img class="cabs_img1" src="../assets/images/pune-outstation-cabs.jpg">
                                </div>
                                <img class="cabs_img2" src="../assets/images/Outstation_Cab_Icon.png">
                                <div class="card-body card_cabs">
                                    <div class="elementor-title pt-4">
                                        <a class="cabs_a text-xl font-semibold" href="#">Outstation Cabs</a>
                                    </div>
                                    <p class="text-sm">Grand India Tour is your ultimate outstation ride companion. Choose from our extensive range of cabs, benefit from competitive pricing, and enjoy sanitized vehicles for a worry-free journey. Experience the freedom to depart at your preferred time or make stops along the way. It's all about catering to your convenience.</p>
                                    <p class="card-footer card_footer_airport">
                                    </p>
                                    <ul class="card_footer_ul flex pt-3">
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/Safe_rides.png"></li>
                                            <li class="outstation_li_txt">Safe Rides</li>
                                        </div>
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/relaxed_rides.png"></li>
                                            <li class="outstation_li_txt">Relaxed Rides</li>
                                        </div>
                                        <div class="card_footer_cabs flex">
                                            <li class="outstation_li"><img class="outstation_li_img" src="../assets/images/one_way_ride.png"></li>
                                            <li class="outstation_li_txt">One-way Cabs</li>
                                        </div>
                                    </ul>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </CabContentWrapper>
        </template>
        <!-- FEATURED  SECTION END -->       

    </div>
    <CabsPageLoader v-else/>
</template>

<script>
import SearchCountryForm from '../components/cab2/SearchCountryForm.vue';
import PackageCard from '../components/cab2/packages/PackageCard.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import OneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import CabsPageLoader from '../skeletons/cabsPageLoader.vue';
import FormTopMenu from '../components/FormTopMenu.vue';
import {SearchBannerWrapper} from '../styles/SearchBannerWrapper';
import {CabContentWrapper} from '../styles/CabContentWrapper';

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
        CabsPageLoader,
        FormTopMenu,
        SearchBannerWrapper,
        CabContentWrapper
    },
    layout: Layout,
    props: ["search_data", "seo_data", "cabsSightseeings"],
};
</script>