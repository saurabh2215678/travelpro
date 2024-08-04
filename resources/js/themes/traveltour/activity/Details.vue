<template>
    <SearchFormWithBanner
        title="Search Activity"
        type="activity"
    />
    <SingleActivityWrapper class="single_package_details py-0 mb-5">
        <div class="w-full overflow-hidden">
            <div class="row">
                <div class="container">
                    <div class="right_side">
                        <div id="myForm">

                            <div class="theme_title mt-7">
                                <h1 class="title text-2xl">
                                    <p class="day_night_detail">
                                        <strong class="day_night text-base">{{package.package_duration}}</strong>
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
                                    <div id="element" class="btn btn-default show-modal mt-5" @click="showItenaryPopup">Download Itinerary</div>
                                </div>
                                <div class="flex items-center flex-wrap justify_btwn">
                                    <ActivityRightPrice  
                                    :package="package" 
                                    :packageSelectedPrice="packageSelectedPrice" 
                                    :faq_row="faq_row" 
                                    :destinations="destinations" 
                                    :itenaries="itenaries" 
                                    :calenderPop="this.calenderPop"
                                    :showCalenderPopup="this.showCalenderPopup"
                                    :hideCalenderPopup="this.hideCalenderPopup"
                                    />


                                    <FancyboxWrapper className="left_price_box">
                                        <ActivityImageSlider :data="package.images"/>
                                    </FancyboxWrapper>

                                </div>
                            </div>

                            <div class="inclusions_share flex flex-wrap">
                                <div class="left_side mb-5 pr-8 w-6/12 icon-wishlist relative">
                                    <ul class="inclusions inclusions_list">
                                        <!-- <li data-text="220523063628-flight.png"><i class="fa"></i>Flight</li> -->

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
                <div class="container">
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
                            <div class="accommodation accordion pt-5">
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
                                                        <span  v-html="package.payment_policy"></span>

                                                    </div>
                                                </div>
                                            </li>
                                            <li class="faq_main" v-if="package.terms_conditions">
                                                <div>
                                                    <div class="faq_title heading6">Terms and Conditions</div>
                                                    <div class="faq_data" style="">
                                                        <span  v-html="package.terms_conditions"></span>

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
    </SingleActivityWrapper>
    <testimonialSlider :testimonials="this.testimonials"/>

    

    <SliderSection 
    v-if="this.similarPackages && this.similarPackages.length > 0"
    :sectionData="{data: this.similarPackages}"
    :withPrice="true"
    title="Similar Packages"
    />

    <DestinationWrapper>
        <SliderSection 
        v-if="this.destinationsSectionData && this.destinationsSectionData?.data.length > 0"
        :sectionData="this.destinationsSectionData"
        title="More Trips Departure"
        className="more_trips_departure"
        :slidePerView="4"
        :spacebetween="30"
        />
    </DestinationWrapper>

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
import PackageCard from '../components/package/PackageCard.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import FancyboxWrapper from '../components/FancyboxWrapper.vue';
import formShortCode from '../components/formShortCode.vue';
import Popup from '../components/popup.vue';
import ActivityRightPrice from '../components/activity/ActivityRightPrice.vue';
import {showPopup, hidePopup} from '../utils/commonFuntions';
import testimonialSlider from '../components/testimonial/testimonialSlider.vue';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import {SingleActivityWrapper} from './detailStyles';
import ActivityImageSlider from '../components/activity/ActivityImageSlider.vue';
import styled from 'vue3-styled-components';
import SliderSection from '../components/SliderSection.vue';

const DestinationWrapper = styled.section`
    background-image: url(../images/vision-bg.jpg);
    background-size: cover;
    padding-bottom: 3rem;
    padding-top: 1rem;
`


export default {
    created() {
        document.body.classList.add('package-detail-page');
        document.body.classList.add('holiday_package');
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.package_itenaries = this.itenaries;
        this.package_total_price = this.packageSelectedPrice?.final_amount;
        this.package_booking_price = this.packageSelectedPrice?.booking_price;
        // console.log('similarPackages=',this.similarPackages);

        const newSectionData = this.destinations;
        newSectionData.forEach(item => item.thumbSrc = item.image);
        this.destinationsSectionData = {
            data : newSectionData
        }
    },
    data() {
        return {
            store,
            package_itenaries: false,
            package_booking_price: 0,
            package_total_price: 0,
            readMore:false,
            itenaryPopup:false,
            calenderPop :false,
            destinationsSectionData: {}
        }
    },
    methods: {
        handleReadMore() {
            this.readMore = !this.readMore;
        },
        showItenaryPopup(){
            store.popupType = 'simple';
            this.itenaryPopup = true;
            this.calenderPop = false;
            showPopup();
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

    },
    beforeUnmount() {
        document.body.classList.remove('package-detail-page');
        document.body.classList.remove('holiday_package');
    },

    components: {
        FancyboxWrapper,
        Popup,
        ActivityRightPrice,
        formShortCode,
        PackageCard,
        testimonialSlider,
        SearchFormWithBanner,
        SingleActivityWrapper,
        ActivityImageSlider,
        DestinationWrapper,
        SliderSection
    },
    layout: Layout,
    props: ["package","packageSelectedPrice","faq_row","destinations","testimonials","itenaries","sharing_links", "search_data", "seo_data", "similarPackages"],
};
</script>