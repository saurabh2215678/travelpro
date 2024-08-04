<template>
    <!-- <SearchFormWithBanner
       title="Search For Packages"
       /> --> 
    <!-- <DetailGalleryWrapper>
       <FancyboxWrapper className="left_price_box">
           <PackageImageSlider :data="package.images"/>
           <PackageImageSlider :data="package.images" />
       </FancyboxWrapper>
       </DetailGalleryWrapper> -->
    <SinglePackageWrapper class="single_package_details py-0 mb-5">
       <div class="w-full overflow-hidden">
          <div class="row">
             <div class="detailtop">
                <div class="container">
                   <div class="w-full flex py-9">
                      <div class="theme_title max-w-4xl">
                         <h1 class="title text-2xl">
                            {{package.title}}
                         </h1>
                         <div class="package-inc mt-5">
                            <ul class="flex">
                               <li v-if="this.isOnlineBooking('package_listing') && store?.myEvents?.length > 0"><span>Trip Date</span><span class="fw600" id="trip_date_change">DD/MM/YYYY</span></li>
                               <li><span>Duration</span>{{package.package_duration}}</li>
                               <li><span>Type of Tour </span> {{package.package_tour_type_text}}</li>
                               <li><span>Location Wise </span> <template v-for="dnc_value in package.days_nights_city" :key="dnc_value" >
                                <div class="font16 pr-2" v-html="dnc_value"></div>
                                </template>
                               </li>
                            </ul>
                         </div>
                      </div>
                      <div class="share-section w-1/2">
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
                                     <li class="instagram" v-if="sharing_links.instagram"><a
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
             <div class="container">
                <div class="right_side">
                   <div id="myForm">
                      <div class="form_box w-full">
                         <div class="city-list-block">
                            <!-- <ul>
                               <li class="full_field mb-3">
                                  <p class="text-sm">
                                     <span class="package_tour_type_text">{{package.package_tour_type_text}}</span> 
                                     <template v-for="dnc_value in package.days_nights_city" :key="dnc_value" >
                                        <span v-html="dnc_value"></span>
                                     </template>
                                  </p>
                               </li>
                            </ul> -->
                            <div id="element" class="btn btn-default show-modal mt-5" @click="showItenaryPopup" v-if="this.isOnlineBooking('package_listing') && store?.myEvents?.length > 0">Download Itinerary</div>
                         </div>
                         <div class="flex items-center flex-wrap justify_btwn mt-3">
                            <PackageRightPrice  
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
                               <PackageImageSlider :data="package.images"/>
                            </FancyboxWrapper>
                         </div>
                      </div>
                      
                   </div>
                </div>
             </div>
          </div>
          <div class="row">
            <!--- ======== Description  ======== -->
            <div class="container">
            <div class="package_disc overview_box mt-5 mb-5 py-3.5 px-5 text-center" v-if="package.brief">
                <div class="package_disc flex items-center justify-center mt-5 mb-5">
                    <h4 class="font20 font-semibold mr-3">Activities</h4>
                    <div class="list_row_right">
                        <ul class="activ_list">
                            <li v-for="pc in package.packageCategories" :key="pc.id" v-html="pc.title"></li>
                        </ul>
                    </div>
                </div>
                <div class="font30 font-bold theme-color-dark pb-3">Trip Overview</div>
                    <p v-html="package.brief"></p>
                    <p v-html="package.description" v-if="this.readMore"></p>
                <template v-if="package.description"><span id="s_more" @click="handleReadMore">{{(this.readMore)?'Read Less':'Read More'}}</span></template>
                </div>
            </div>
          </div>
          <div class="row fullbg pb-11">
             <div class="container">
                <div id="myForm" class="flex flex-wrap pt-16 flex_package right_side">
                    <div class="md:w-4/6 md:pr-9 mo_full w-full">
                        <!--- ======== Flight  ======== -->
                        <div class="bg-slate-100 mt-3 p-4" v-if="package.PackageFlights && package.PackageFlights.length > 0">
                        <div class="text-xl font-semibold pt-2">{{package.PackageFlights.length}} Flights in the package</div>
                        <div class="py-2 text-sm"><strong>Note:</strong> Flight details are tentative only. The airline, departure, arrival times and routing may change</div>
                        <div class="para_lg2">
                            <ul class="flight_list">
                                <li class="mt-0 rowid-24" v-for="flight in package.PackageFlights">
                                    <img src="../assets/images/flight.gif" alt="Air India"
                                    class="logo">{{flight.airline_name}} | {{flight.flight_number}} | {{flight.flight_departure}} <i class="fa fa-long-arrow-right"></i>
                                    {{flight.flight_arrival}}
                                </li>
                            </ul>
                        </div>
                        </div>
                        <!--- ======== Package Days  ======== -->
                        <div class="listSec">
                        <ul>
                            <li class="active"><a href="#packageIt">Itinerary</a></li>
                            <li v-if="store.accommodations_days && store.accommodations_days.length > 0"><a href="#package_accommodations">Accommodation</a></li>
                            <li v-if="this.testimonials && this.testimonials.length > 0"><a href="#review">Review</a></li>
                        </ul>
                        </div>
                        <div id="packageIt" class="package_day secpad py-3.5 px-5 border border-gray-300 rounded bg-white" v-if="this.package_itenaries && this.package_itenaries.length > 0 && package.is_activity == 0">
                        <div class="container-full">
                            <h3 class="text-xl font-semibold pb-2">Package Itinerary</h3>
                            <div class="faqlist faqlist_in">
                                <ul>
                                    <li class="faq_main" v-for="itenary in this.package_itenaries">
                                    <div class="ml-14">
                                        <div class="faq_title">{{itenary.itenary_title}}</div>
                                        <div class="theme_tags mb-3" v-if="itenary.itenaryTags && itenary.itenaryTags.length > 0">
                                            <span class="tags mr-2" v-for="itag in itenary.itenaryTags">
                                            {{itag}}
                                            </span>
                                        </div>
                                        <p class="pb-2 font14" v-if="itenary.meal_option_arr && itenary.meal_option_arr.length > 0">
                                            Meal :
                                            <template v-for="meal_option_val,index in itenary.meal_option_arr">
                                                {{(index>0)?', ':''}}
                                                {{meal_option_val}}
                                            </template>
                                        </p>
                                        <div class="day_curcle"><span>{{itenary.itenary_day_title}}</span></div>
                                        <div class="faq_data">
                                            <div class="faq_service package_disc overview_box pb-3 mb-3" v-if="itenary.itenary_inclusions_arr">
                                                <div class="title2 mb-1 text-base font-semibold" v-if="itenary.itenary_inclusions_arr.length > 0">Services Included</div>
                                                <ul class="include_list inclusions" style="clear: both;display: block;width: 100%;">
                                                <template v-for="inclusion in itenary.itenary_inclusions_arr">
                                                    <li :data-text="inclusion.title" style="float: left;text-align: center;margin-right: 15px;" >
                                                        <img style="" :src="inclusion.image"/>
                                                        <span>{{inclusion.title}}</span>
                                                    </li>
                                                </template>
                                                </ul>
                                            </div>
                                            <br/>
                                            <div class="text-lg font-semibold" v-if="itenary.itenary_location">{{itenary.itenary_location}}</div>
                                            <div class="right-content-itinerary" v-html="itenary.itenary_description"></div>
                                            <div class="left-img-itinerary pt-2" v-if="itenary.itenarySrc">
                                                <img :src="itenary.itenarySrc" :alt="itenary.itenary_title" />
                                            </div>
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
                        <div class="accommodation accordion mt-5 mb-5 pt-5 py-3.5 px-5 border border-gray-300 rounded bg-white" v-if="this.packagePolicy()">
                                <div class="container1">
                                    <h3 class="text-xl font-semibold mb-3">Policy</h3>
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
                                                        <span  v-html="package.terms_conditions"></span>

                                                    </div>
                                                </div>
                                            </li>


                                            <template v-if="package.PackageInfo && package.PackageInfo.length > 0" v-for="info in package.PackageInfo">
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
                                            <div class="theme_title mb-3">
                                                <h3 class="text-xl font-semibold">FAQs About {{package.title}}</h3>
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

                                        <div v-if="package.video_link" v-html="package.video_link"></div>

                                    </div>
                                    
                                </div>
                            </div>
                        <!-- <div class="btn_groups">
                        <a href="#packageEnquire_form" class="btn btn_enquire">Enquire Now</a>
                        </div>  -->
                        <testimonialSlider :testimonials="this.testimonials" v-if="this.testimonials && this.testimonials.length > 0"/>
                        
                    </div>
                    <div class="md:w-4/12 mt-5 mo_full w-full" v-if="this.package && this.package.id">
                        <div class="form_box-new single_package_form" id="packageEnquire_form">
                        <div class="form_box_inner">
                            <h3 class="text-xl font-semibold pb-2">Your Preference</h3>
                            <formShortCode slug="[your_preference]" class="left_form" :hidden="{'package':this.package.id}" />
                        </div>
                        </div>
                        <!-- <div class="wcu">
                        <div class="title">Why Choose Us?</div>
                            <ul>
                                <li>
                                    <div class="wcu-img">
                                        <img src="https://www.andamanisland.in/assets/site1/images/icon/Experience.png" class="img-responsive" alt="Experience"> 
                                    </div>
                                    <div class="wcu-para">
                                        <span>Experience</span>
                                        <p>Located in the Andaman Islands and having great love and knowledge for it, we have gained specialisation in the Andaman tourism industry. We have over 16 years of experience in the field and this makes us the best travel expert who can give you the best recommendations.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wcu-img">
                                        <img src="https://www.andamanisland.in/assets/site1/images/icon/Tour-Packages.png" class="img-responsive" alt="Tour Packages"> 
                                    </div>
                                    <div class="wcu-para">
                                        <span>Tour Packages</span>
                                        <p>Whatever your budget is, our offered customized Andamans tour packages will help you explore this beautiful destination without burning a hole in your pocket. We offer first-class services to all kinds of travellers—luxury/budget/solo/family/romantic, etc.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wcu-img">
                                        <img src="https://www.andamanisland.in/assets/site1/images/icon/Customer-Support-24X7.png" class="img-responsive" alt="Customer Support 24X7"> 
                                    </div>
                                    <div class="wcu-para">
                                        <span>Customer Support 24X7</span>
                                        <p>We aim at offering the most affordable tour packages so that everyone can have the best time at the pristine land of the Andaman Islands. Our staff comprising reservations, sales, and tour experts are locals of the Andamans. Thus, you are sure to get the best suggestions for your vacation. Moreover, our customer support service is available 24X7.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wcu-img">
                                        <img src="https://www.andamanisland.in/assets/site1/images/icon/Offers-&amp;-Discounts.png" class="img-responsive" alt="Offers &amp; Discounts"> 
                                    </div>
                                    <div class="wcu-para">
                                        <span>Offers &amp; Discounts</span>
                                        <p>Holding vast expertise in promoting Andaman Island tourism, our committed representatives makes sure that you get the best deals on all your bookings. We also guarantee you the best discounts on your stay as we have tie-ups with the most amazing hotels and resorts across the Andaman Islands along with ourselves owning lavish properties as well.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wcu-img">
                                        <img src="https://www.andamanisland.in/assets/site1/images/icon/Service-Guarantee.png" class="img-responsive" alt="Service Guarantee"> 
                                    </div>
                                    <div class="wcu-para">
                                        <span>Service Guarantee</span>
                                        <p>With the excellent services, our dedicated hospitality team members make you feel completely comfortable, thereby offering you holidays of a lifetime.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wcu-img">
                                        <img src="https://www.andamanisland.in/assets/site1/images/icon/Our-Experts.png" class="img-responsive" alt="Our Experts"> 
                                    </div>
                                    <div class="wcu-para">
                                        <span>Our Experts</span>
                                        <p>The members of our operations team are local residents of the Andaman Islands. Therefore, they are proficient in handling all your queries before, during, and after the tour in the best way possible.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wcu-img">
                                        <img src="https://www.andamanisland.in/assets/site1/images/icon/Special-Request-Services.png" class="img-responsive" alt="Special Request Services"> 
                                    </div>
                                    <div class="wcu-para">
                                        <span>Special Request Services</span>
                                        <p>When it comes to your special requests like midnight cake cutting with music and wine, candlelight dinner at the beach, champaign by the poolside, or anything else, we make sure that no stone is left unturned to give you what you have asked for.</p>
                                    </div>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                </div>
             </div>
          </div>
       </div>
    </SinglePackageWrapper>
    <!--  <SinglePackageWrapper>
       <SliderSection 
       v-if="this.similarPackages && this.similarPackages.length > 0"
       :sectionData="{data: this.similarPackages}"
       :withPrice="true"
       title="Similar Packages"
       />
       </SinglePackageWrapper> --> 
    <SinglePackageWrapper>
       <HomePackageSlider 
          v-if="this.similarPackages && this.similarPackages.length > 0"
          :sectionData="{data: this.similarPackages}"
          :withPrice="true"
          title="Similar Tour Packages"
          />
    </SinglePackageWrapper>
    <SliderSection 
          v-if="this.destinationsSectionData && this.destinationsSectionData?.data.length > 0"
          :sectionData="this.destinationsSectionData"
          title="More Trips Departure"
          className="more_trips_departure bg-gray-100"
          :slidePerView="4"
          :spacebetween="30"
          />

    <template v-if="store.popupType != 'innerHtml' && this.itenaryPopup">
       <Popup className="download_itnery_pop_wrapper">
          <div class="modal-body download-itinerary">
             <div class="modal-header form-floating mb-3 w-full">
                <h4 class="modal-title text-2xl">Enquiry Form</h4>
                <p class="text-sm ">Download itinerary for :<span class="text-teal-500  italic">
                   {{package.title}}</span>
                </p>
             </div>
             <div class="book-space">
                <formShortCode slug="[download_itinerary]" class="form_list" :hidden="{'package':this.package.id}" name="download_itinerary" />
             </div>
          </div>
       </Popup>
    </template>
 </template>
 <script>
    import SearchForm from '../components/SearchForm.vue';
    import Layout from '../components/layout.vue';
    import { store } from '../store';
    import FancyboxWrapper from '../components/FancyboxWrapper.vue';
    import formShortCode from '../components/formShortCode.vue';
    import Popup from '../components/popup.vue';
    import PackageRightPrice from '../components/package/PackageRightPrice.vue';
    import PackageAccommodation from '../components/package/PackageAccommodation.vue';
    import {showPopup, hidePopup, isOnlineBooking} from '../utils/commonFuntions';
    import testimonialSlider from '../components/testimonial/testimonialSlider.vue';
    import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
    import {SinglePackageWrapper} from './detailStyles';
    import SliderSection from '../components/SliderSection.vue';
    import HomePackageSlider from "../components/home/HomePackageSlider.vue";
    import styled from 'vue3-styled-components';
    import PackageImageSlider from '../components/package/PackageImageGallery.vue';
    import axios from "axios";
    
    const DestinationWrapper = styled.section`
        /* background-image: url(../images/vision-bg.jpg);
        background-size: cover; 
        padding-bottom: 3rem;*/
        padding-top: 1rem;
    
        @media (max-width: 767px){
            padding-bottom: 0;
        }
    `
    
    const DetailGalleryWrapper = styled.section`
    position: relative;
    `
    
    export default {
        created() {
            document.body.classList.add('package-detail-page');
            document.body.classList.add('holiday_package');
            store.searchData = this.search_data;
            store.seoData = this.seo_data;                    
            this.package_itenaries = this.itenaries;
            //this.package_total_price = this.packageSelectedPrice?.final_amount;
            //this.package_booking_price = this.packageSelectedPrice?.booking_price;
            console.log('similarPackages==', this.similarPackages);
            //console.log('destinationsSectionData', this.destinations);
            // console.log('package_inclusions==', this.package_inclusions);
    
            const newSectionData = this.destinations;
            newSectionData.forEach(item => item.thumbSrc = item.image);
            this.destinationsSectionData = {
                data : newSectionData
            }
        },
        data() {
            return {
                store,
                package_itenaries: [],
                package_booking_price: 0,
                package_total_price: 0,
                itenaryPopup:false,
                calenderPop :false,
                readMore:false,
                destinationsSectionData: {}
            }
        },
        methods: {
            isOnlineBooking,
    
            packagePolicy(){
                let package_policy = false;
                let packageData = this.package;
                if(packageData.inclusions || packageData.exclusion || packageData.payment_policy || packageData.cancellation_policy || packageData.terms_conditions || (packageData.PackageInfo && packageData.PackageInfo.length > 0) || (this.faq_row && this.faq_row.length > 0) || packageData.video_link){
                  package_policy = true; 
                }
                return package_policy;
            },
            handleReadMore() {
                this.readMore = !this.readMore;
            },
            showItenaryPopup(){
                var currentObj = this;
                currentObj.processing = true;
                currentObj.errors = {};
                let formData = new FormData($('#bookNowForm')[0]);
                if(this.package.package_tour_type == 'open') {
                    formData.append('is_download_itinerary',1);
                }
                axios.post('/book_now', formData)
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
            PackageRightPrice,
            PackageAccommodation,
            formShortCode,
            testimonialSlider,
            SearchFormWithBanner,
            SinglePackageWrapper,
            SliderSection,
            DestinationWrapper,
            PackageImageSlider,
            DetailGalleryWrapper,
            HomePackageSlider
        },
        layout: Layout,
        props: ["search_data", "seo_data", "package", "default_price_id","faq_row","destinations","itenaries","sharing_links", "testimonials", "similarPackages"],
    };
 </script>
 <style>
    html {
    scroll-behavior: smooth;
    scroll-padding-top: 80px;
    }
 </style>