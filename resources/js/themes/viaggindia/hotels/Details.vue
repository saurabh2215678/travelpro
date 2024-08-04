<template>
    <SearchFormWithBanner type="hotel" />
    <div class="container text-right pt-5 pb-3">
        <Breadcrumb :data="breadcrumbData"/>
    </div>

    <HotelDetailGallerySection :accommodation="accommodation"/>

    <HotelDetailPageWrapper>
        <FancyboxWrapper>
            <section class="pt-0 pb-0">
                <div class="container">
                    <div class="flex flex-wrap">
                        <div class="left_side w-full">
                            <div class="left_side_inner">
                                <HotelsDetailsOverview :accommodation="accommodation" :accomodation_info="accomodation_info"/>
                                <template v-if="accommodation.accommodationRooms && accommodation.accommodationRooms.length > 0">
                                    <HotelsRoomType
                                    :accommodation="accommodation" 
                                    :checkin="checkin" 
                                    :checkout="checkout" 
                                    :inventory="inventory" 
                                    :adult="adult" 
                                    :child="child" 
                                    :infant="infant"
                                    />    
                                </template>
                            <div class="flex-wrap flex bg-white mt-5 mb-5 w-full" id="content_location" v-if="accommodation.map_src">
                                <div class="tablediv w-full">
                                    <p class="map-detail">
                                        <iframe
                                        :src="accommodation.map_src"
                                        style="border:0" allowfullscreen="" width="100%" height="390" frameborder="0"></iframe>
                                    </p>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    
            <section class="pt-0 pb-0">
               <div class="container">
                <div class="flex-wrap">
                    <div class="accommodation accordion pt-5">
                        <div class="container1">
                            <div class="faqlist">
                                <!-- Frequently Asked Questions -->
                                <template v-if="faq_row && faq_row.length > 0">
                                    <br>
                                    <div class="theme_title mb-3">
                                        <h3 class="text-xl font-bold">FAQs About {{accommodation.name}}</h3>
                                    </div>
                                    <ul>
                                        <li class="faq_main"  v-for="faq in faq_row">
                                            <div>
                                                <div class="faq_title heading6"><strong>Q </strong>{{faq.question}}</div>
                                                <div class="faq_data" style="">
                                                    <p v-html="faq.answer"></p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </template>
                                <!-- Frequently Asked Questions Closed -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </section>
    
            <section class="hotelpolicy" id="content_policies" v-if="accommodation.policies">
                <div class="container">
                    <div class="hotelpolicy_row">
                        <div class="border border-slate-20 p-3">
                            <div class="title text-2xl">Property Policies</div>
                            <div class="text-sm pt-2" v-html="accommodation.policies">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <template v-if="relatedAccommodations[0]">
                <RelatedHotels :relatedAccommodations="relatedAccommodations" :destination="destination"/>
            </template>
        </FancyboxWrapper>
    </HotelDetailPageWrapper>

</template>

<script>
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
// https://www.npmjs.com/package/vue-toast-notification
import Layout from '../components/layout.vue';
import SearchForm from '../components/SearchForm.vue';
import HotelsDetailsSlider from '../components/hotel/HotelsDetailsSlider.vue';
import HotelsDetailsOverview from '../components/hotel/HotelsDetailsOverview.vue';
import RelatedHotels from '../components/hotel/RelatedHotels.vue';
import HotelsRoomType from '../components/hotel/HotelsRoomType.vue';
import { store } from '../store';
import { Link } from "@inertiajs/inertia-vue3";
import FancyboxWrapper from '../components/FancyboxWrapper.vue';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import HotelDetailGallerySection from '../components/hotel/HotelDetailGallerySection.vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import {HotelDetailPageWrapper} from '../styles/HotelDetailPageWrapper'

const $toast = useToast();

export default {
    name:'HotelDetail',
    created() {
        document.body.classList.add('hotel_class');
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
    },
    beforeUnmount() {
        document.body.classList.remove('hotel_class');
        $('.left_area').empty();
        $('.right_area').empty();
        $('.bottom_area').empty();
   },
    data() {
        return {
            store,
        }
    },
    methods: {
        showToast(toastObj){
            $toast.open(toastObj);
        }
    },
    mounted () {
        $('.grid_gallery li:nth-child(1) .gallery_wrapper').appendTo('.left_area');
        $('.grid_gallery li:nth-child(2) .gallery_wrapper, .grid_gallery li:nth-child(3) .gallery_wrapper, .grid_gallery li:nth-child(4) .gallery_wrapper, .grid_gallery li:nth-child(5) .gallery_wrapper').appendTo('.right_area');
        $('.grid_gallery li:nth-child(n+6) .gallery_images').appendTo('.bottom_area');
        // console.log('running');
    },
    beforeDestroy () {
        $('.left_area').empty();
        $('.right_area').empty();
        $('.bottom_area').empty();
    },
    watch:{
    },
    components: {
        SearchForm,
        FancyboxWrapper,
        HotelsDetailsSlider,
        HotelsDetailsOverview,
        HotelsRoomType,
        RelatedHotels,
        Link,
        SearchFormWithBanner,
        HotelDetailGallerySection,
        Breadcrumb,
        HotelDetailPageWrapper
    },
    layout: Layout,
    props: ["search_data", "seo_data", "breadcrumbData", "accommodation", "accomodation_info", "checkin", "checkout", "inventory", "adult", "child", "infant", "relatedAccommodations","destination","faq_row"],
};
</script>