<template>
    <SearchForm type="activity" />
    <SingleActivityDetails class="single_package_details activity_right_side_details py-0">
        <div class="w-full overflow-hidden">
            <div class="row">
                <div class="xl:container xl:mx-auto">
                    <div class="right_side">
                        <div id="myForm">
                            <div class="theme_title mt-7">
                                <h1 class="title text-2xl">
                                    <p class="day_night_detail">
                                        <strong class="day_night text-base" v-if="package.package_duration">{{package.package_duration}}</strong>
                                    </p> 
                                    {{package.title}}
                                </h1>
                            </div>
                            <div class="form_box">
                                <div class="city-list-block">
                                    <ul>
                                        <li class="full_field mb-3">
                                            <p class="text-sm">
                                                <span class="package_tour_type_text">{{package.package_tour_type_text}}</span> 
                                                <template v-for="dnc_value in package.days_nights_city" :key="dnc_value" >
                                                    <span v-html="dnc_value"></span>

                                                </template>
                                            </p>
                                        </li>
                                    </ul>
                                    <div id="element" class="btn btn-default show-modal mt-5" @click="showItenaryPopup" v-if="store?.myEvents?.length > 0">Download Itinerary</div>
                                </div>
                                <div :class="`flex items-center flex-wrap justify_btwn ${(package?.packagePrices?.length > 0)?'':'no_price'}`">
                                <ActivityRightPrice  
                                :package="package"
                                :defaultPriceId="default_price_id"
                                :faq_row="faq_row"
                                :destinations="destinations"
                                :itenaries="itenaries"
                                :calenderPop="this.calenderPop"
                                :showCalenderPopup="this.showCalenderPopup"
                                :hideCalenderPopup="this.hideCalenderPopup"
                                />

                                <FancyboxWrapper className="left_price_box">
                                    <div class="swiper package_detail_img">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide" v-for="imageData in package.images">
                                                <img  data-fancybox="gallery"
                                                :src="imageData.srcimage"
                                                alt="{{imageData.title}}" />
                                            </div>
                                        </div>
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                                </FancyboxWrapper>

                            </div>
                        </div>

                            <div class="inclusions_share flex flex-wrap">
                                <div class="left_side mb-5 pr-8 w-6/12 icon-wishlist relative">
                                    <ul class="inclusions inclusions_list" v-if="package.package_inclusions && package.package_inclusions.length > 0">
                                        <li :data-text="package_inclusion.value" v-for="package_inclusion in package.package_inclusions">
                                            <img :src="package_inclusion.image" />
                                            {{package_inclusion.value}}
                                        </li>
                                    </ul>
                                    <a href="javascript:void(0)" class="more_btn"> more... </a>

                                </div>
                                <div class="share-section w-6/12">
                                    <div class="flex py-0 pt-3 pl-1.5 items-center">
                                        <div class="bg_share">
                                            <p class="mr-3">Share It On:</p>
                                            <div class="footer_bottom_right">
                                                <ul class="social_icon">
                                                    <li class="facebook" v-if="sharing_links.facebook"><a
                                                        :href="sharing_links.facebook"
                                                        target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                                    </li>
                                                    <li class="twitter" v-if="sharing_links.twitter"><a
                                                        :href="sharing_links.twitter"
                                                        target="_blank"><i class="fa-brands fa-twitter"></i></a>
                                                    </li>
                                                    <li class="whatsapp" v-if="sharing_links.whatsapp"><a
                                                        :href="sharing_links.whatsapp"
                                                        target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                                                    </li>
                                                    <li class="whatsapp" v-if="sharing_links.instagram"><a
                                                        :href="sharing_links.instagram"
                                                        target="_blank"><i class="fa-brands fa-instagram"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="xl:container xl:mx-auto">
                    <div class="flex flex-wrap">
                        <div class="row-cols-2 w-70 pr-9">
                            <div class="package_disc flex items-center mt-5 mb-5">
                                <h2 class="text-base font-semibold mr-3">Activities </h2>
                                <div class="list_row_right" v-if="package.packageCategories">
                                    <ul class="activ_list">
                                        <li v-for="pc in package.packageCategories" :key="pc.id" v-html="pc.title"></li>
                                    </ul>
                                </div>
                            </div>
                            <!--- ======== Description  ======== -->
                            <div class="package_disc overview_box py-3.5 px-5" v-if="package.brief">
                                <div class="text-2xl font-semibold pb-3">Overview</div>
                                <p v-html="package.brief"></p>
                                <template v-if="package.description">
                                    <div class="text-2xl font-semibold pb-3">Activity Details</div>
                                    <p v-html="package.description"></p>
                                </template>
                            </div>
                            <!--- ======== Flight  ======== -->
                            <div class="package_disc bg-slate-100 mt-3 p-4" v-if="package.PackageFlights && package.PackageFlights.length > 0">
                                <div class="text-xl font-bold pt-3">{{package.PackageFlights.length}} Flights in the package</div>
                                <p class="para_lg2">
                                    <ul class="flight_list">
                                        <li class="mt-0 rowid-24" v-for="flight in package.PackageFlights">
                                            <img src="../assets/images/flight.gif" alt="Air India"
                                            class="logo">{{flight.airline_name}} | {{flight.flight_number}} | {{flight.flight_departure}} <i class="fa fa-long-arrow-right"></i>
                                            {{flight.flight_arrival}}
                                        </li>
                                    </ul>
                                </p>
                            </div>
                            <!--- ======== Package Days  ======== -->
                            <div class="package_day secpad mt-5" v-if="this.package_itenaries && this.package_itenaries.length > 0 && package.is_activity == 0">
                                <div class="container-full">
                                    <h3 class="text-xl font-bold">Package Itinerary</h3>
                                    <div class="faqlist faqlist_in">
                                        <ul>
                                            <li class="faq_main" v-for="itenary in this.package_itenaries"> 
                                                <div class="ml-14">
                                                    <div class="faq_title">{{itenary.itenary_title}}</div>
                                                    <div class="theme_tags mb-5" v-if="itenary.itenaryTags && itenary.itenaryTags.length > 0">
                                                        <span class="tags mr-2" v-for="itag in itenary.itenaryTags">
                                                            {{itag}}
                                                        </span>
                                                    </div>


                                                    <p v-if="itenary.meal_option_arr && itenary.meal_option_arr.length > 0">
                                                        Meal :
                                                        <template v-for="meal_option_val,index in itenary.meal_option_arr">
                                                            {{(index>0)?', ':''}}
                                                            {{meal_option_val}}
                                                        </template>
                                                    </p>

                                                    <div class="day_curcle"><span>{{itenary.itenary_day_title}}</span></div>

                                                    <div class="faq_data">
                                                        <div class="faq_service heading6 mb-0" v-if="itenary.itenary_inclusions_arr">
                                                            <div class="title2 mb-3 text-base">Services Included</div>


                                                            <ul class="include_list inclusions" style="clear: both;display: block;width: 100%;height: 80px;">
                                                                <template v-for="inclusion in itenary.itenary_inclusions_arr">
                                                                    <li :data-text="inclusion.title" style="float: left;text-align: center;margin-right: 15px;" >
                                                                        <img style="width:50px;" :src="inclusion.image"/>
                                                                        <span>{{inclusion.title}}</span>
                                                                    </li>
                                                                </template>
                                                            </ul>
                                                        </div>


                                                        <div class="left-img-itinerary" v-if="itenary.itenarySrc"><img :src="itenary.itenarySrc" :alt="itenary.itenary_title" /></div>

                                                        <div class="heading6 font-semibold" v-if="itenary.itenary_location">{{itenary.itenary_location}}</div>

                                                        <div class="right-content-itinerary" v-html="itenary.itenary_description"></div>









                                                    </div>
                                                </div>
                                            </li>                                           
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--- ======== Accommodation Hotel ======== -->

                            <PackageAccommodation v-if="store.accommodations_days && store.accommodations_days.length > 0" />

                            <!--- ======== Accommodation  Old ======== -->
                            <div class="accommodation accordion pt-5" v-if="this.activityPolicy()">
                                <div class="container1">
                                    <h3 class="text-2xl font-semibold pb-3">Policy</h3>
                                    <div class="faqlist">
                                        <ul>
                                            <li class="faq_main" v-if="package.inclusions">
                                                <div class="faq_title heading6">Inclusions</div>
                                                <div class="faq_data">
                                                    <span v-html="package.inclusions"></span>
                                                </div>
                                            </li>
                                            <li class="faq_main" v-if="package.exclusions">
                                                <div class="faq_title heading6">Exclusions</div>
                                                <div class="faq_data">
                                                    <span v-html="package.exclusions"></span>
                                                </div>
                                            </li>

                                            <li class="faq_main" v-if="package.payment_policy">
                                                <div>
                                                    <div class="faq_title heading6">Payment Policy</div>
                                                    <div class="faq_data" style="">
                                                        <span  v-html="package.payment_policy"></span>

                                                    </div>
                                                </div>
                                            </li>
                                            <li class="faq_main" v-if="package.cancellation_policy">
                                                <div>
                                                    <div class="faq_title heading6">Cancellation Policy</div>
                                                    <div class="faq_data" style="">
                                                        <span  v-html="package.cancellation_policy"></span>

                                                    </div>
                                                </div>
                                            </li>
                                            <li class="faq_main" v-if="package.terms_conditions">
                                                <div>
                                                    <div class="faq_title heading6">Terms and Conditions</div>
                                                    <div class="faq_data" style="">
                                                        <span v-html="package.terms_conditions"></span>

                                                    </div>
                                                </div>
                                            </li>


                                            <template v-if="package.PackageInfo" v-for="info in package.PackageInfo">
                                                <li class="faq_main" v-if="info.title && info.description">
                                                    <div>
                                                        <div class="faq_title heading6">{{info.title}}</div>
                                                        <div class="faq_data" style="">
                                                            <span v-html="info.description"></span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>

                                        <!-- Frequently Asked Questions -->
                                        <template v-if="faq_row && faq_row.length > 0">
                                            <br>
                                            <div class="theme_title mb-6">
                                                <h3 class="text-2xl font-semibold pb-3">FAQs About {{package.title}}</h3>
                                            </div>
                                            <ul>
                                                <li class="faq_main" v-for="faq in faq_row">
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

                                        <div v-if="package.video_link" v-html="package.video_link"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-cols-2 w-30 mt-5" v-if="this.package && this.package.id">
                            <div class="form_box-new single_package_form">
                                <div class="form_box_inner">
                                    <div class="text-2xl font-semibold pb-3">Your Preference</div>
                                    <formShortCode slug="[your_preference]" class="left_form" :hidden="{'package':this.package.id}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SingleActivityDetails>
    <testimonialSlider :testimonials="this.testimonials" v-if="this.testimonials && this.testimonials.length > 0"/>

    <!-- FEATURED  Similar START -->
    <section class="home_featured latoFont" v-if="this.similarPackages && this.similarPackages.length > 0">
        <div class="xl:container xl:mx-auto">
            <div class="text_left p_relative">
                <div class="theme_title mb-6">
                    <div class="title text-2xl">Similar Activities</div>
                </div>
                <div class="slider_btns">
                    <div class="featured-next theme-next" ref="sliderNextRef"><img src="../assets/images/next.png" alt=""></div>
                    <div class="featured-prev theme-prev" ref="sliderPrevRef"><img src="../assets/images/prev.png" alt=""></div>
                </div>
                <div class="slider_box">
                    <div class="swiper" ref="sliderRef">
                        <div class="swiper-wrapper">
                            <template v-for="packageData in similarPackages" :key="packageData.id">
                                <PackageCard :packageData="packageData" />
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- FEATURED  Similar END -->

    <MoreTripsDeparture class="departure_single" v-if="destinations">
        <div class="xl:container xl:mx-auto">
            <div class="text_center">
                <div class="theme_title mb-5">
                    <div class="title text-2xl">More Trips Departure</div>
                </div>
            </div>
            <div class="slider_box mt40">
                <div class="swiper departure_slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" v-for="destination in destinations"> 
                            <div class="more_trips">
                                <div class="images">
                                    <a :href="destination.slug">
                                        <img :src="destination.image"
                                        :alt="destination.name" class="img_responsive theme_radius" />
                                        <div class="place_name heading_md1">
                                            <span>{{destination.name}}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider_btns">
                    <div class="departure-next theme-next">
                        <img src="../assets/images/next-sm.png"
                        alt="Next Icon" />
                    </div>
                    <div class="departure-prev theme-prev">
                        <img src="../assets/images/prev-sm.png"
                        alt="Prev Icon" />
                    </div>
                </div>
            </div>
        </div>
    </MoreTripsDeparture>

    <template v-if="store.popupType != 'innerHtml' && this.itenaryPopup">
        <Popup className="download_itnery_pop_wrapper">
            <div class="modal-body download-itinerary">
                <div class="modal-header form-floating mb-3 w-full">
                    <h4 class="modal-title text-2xl">Enquiry Form</h4>
                    <p class="text-sm ">Download itinerary for :<span class="text-teal-500  italic">
                    {{package.title}}</span></p>
                </div>
                <div class="book-space">
                    <formShortCode slug="[download_itinerary]" class="form_list" :hidden="{'package':this.package.id}" />
                </div>
            </div>
        </Popup>
    </template>


</template>

<script>
import SearchForm from '../components/SearchForm.vue';
import PackageCard from '../components/package/PackageCard.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import FancyboxWrapper from '../components/FancyboxWrapper.vue';
import formShortCode from '../components/formShortCode.vue';
import Popup from '../components/popup.vue';
import ActivityRightPrice from '../components/activity/ActivityRightPrice.vue';
import {showPopup, hidePopup} from '../utils/commonFuntions';
import testimonialSlider from '../components/testimonialSlider.vue';
import axios from "axios";
import {SingleActivityDetails, MoreTripsDeparture} from './detailStyles';

export default {
    created() {
        document.body.classList.add('package-detail-page');
        document.body.classList.add('holiday_package');
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.package_itenaries = this.itenaries;
    },
    data() {
        return {
            store,
            package_itenaries: false,
            readMore:false,
            itenaryPopup:false,
            calenderPop :false,
        }
    },
    methods: {

        activityPolicy(){
            let activity_policy = false;
            let activityData = this.package;
            if(activityData.inclusions || activityData.exclusion || activityData.payment_policy || activityData.cancellation_policy || activityData.terms_conditions || (activityData.PackageInfo && activityData.PackageInfo.length > 0) || (this.faq_row && this.faq_row.length > 0) || activityData.video_link){
              activity_policy = true; 
            }
            return activity_policy;
        },

        handleReadMore() {
            this.readMore = !this.readMore;
        },
        showItenaryPopup(){
            var currentObj = this;
            currentObj.processing = true;
            currentObj.errors = {};
            axios.post('/book_now', new FormData($('#bookNowForm')[0]))
            .then(function (response) {
                currentObj.processing = false;
                if(response.data.success) {
                    // window.location.href = response.data.redirect_url;
                    // currentObj.$inertia.get(response.data.redirect_url);
                    store.popupType = 'simple';
                    currentObj.itenaryPopup = true;
                    currentObj.calenderPop = false;
                    showPopup();
                } else {
                    alert('Something went wrong, please try again.');
                }
            }).catch(function (e) {
                var response = e.response.data;
                if(response) {
                    currentObj.parseBookingErrorMessages(response, 'myForm', 'Book Online');
                }
            });
        },
        parseBookingErrorMessages(response, formID, boxText) {
            if(response.errors) {
                var errors = response.errors;
                var message='';
                $.each(errors, function (key, val) {
                    $('#'+formID).find("#" + key + "_error").text(val[0]);
                });
            }
        },
        showCalenderPopup(){
            this.calenderPop = true;
            this.itenaryPopup = false;
            store.popupType = 'simple';
            showPopup();
        },
        hideCalenderPopup(){
            this.calenderPop = false;
            this.itenaryPopup = false;
            hidePopup();
        }

    },
    mounted () {
        // ====== REMOVE SPACE IN P TAG =======
        var faqData = document.querySelectorAll('.faq_data p');
        faqData.forEach(function(element) {
            var innerText = element.textContent.trim();
            if (innerText === '') {
                element.style.display = 'none';
            }
        });

// ====== Similar Packages =======
var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;

        var slidesCount = 5;
        var spacebetween = 15;

        new Swiper(sliderElem, {
            slidesPerView: slidesCount,
            spaceBetween: spacebetween,
            loop: false,
            speed:1000,
            navigation: {
                nextEl: sliderPrevBtn,
                prevEl: sliderNextBtn,
            },
            breakpoints: {
                0: {
                slidesPerView: 1,
                },
                640: {
                slidesPerView: 2,
                },
                768: {
                slidesPerView: 3,
                },
                1024: {
                slidesPerView: slidesCount,
                },
            },
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });

    },
    beforeUnmount() {
        document.body.classList.remove('package-detail-page');
        document.body.classList.remove('holiday_package');
    },
    beforeDestroy () {
    },

    watch:{
    },
    components: {
        SearchForm,
        FancyboxWrapper,
        Popup,
        ActivityRightPrice,
        formShortCode,
        PackageCard,
        testimonialSlider,
        SingleActivityDetails,
        MoreTripsDeparture
    },
    layout: Layout,
    props: ["package","default_price_id","faq_row","destinations","itenaries","testimonials","sharing_links","search_data","seo_data","similarPackages"],
};
</script>
<style>
  .package-detail-page .flight-banner {display:none;}
</style>