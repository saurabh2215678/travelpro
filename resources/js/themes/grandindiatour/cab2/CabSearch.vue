<template>
    <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <section class="inner_banner search_cab">
        <div class="head_band">
            <div class="xl:container xl:mx-auto">
                <div class="search_inner_top_wrapper" v-if="modifySearch">
                   <SearchCountryForm :TripType="tripType" />
                    <div class="closeModifySearch" @click="handleModifySearch"><i class="fa-solid fa-xmark"></i></div>
                </div>
                <div class="flight_search_detail" v-else-if="this.routeInfos && this.routeInfos.length > 0">
                    <div class="flight_info_scroller">

                        <template v-if="tripType==5">
                            <div class="fl_box">
                                <h3 class="mb-0 font14">{{this.routeInfos[0]['fromCity']['name']??''}}</h3>
                            </div>
                            <div class="fl_icon">
                                <i class="fa-solid fa-car-side"></i>
                            </div>
                            <div class="fl_box">
                                <h3>&nbsp;</h3>
                            </div>
                        </template>
                        <template v-else >
                            <div  v-if="tripType == 2 && this.routeInfos" class="sch1" v-for="routeInfo, index in this.routeInfos">
                                <div class="fl_box">
                                    <h3 class="mb-0 font14">{{routeInfo['fromCity']['name']??''}}</h3>
                                </div>
                                <div class="fl_icon"><i class="fa-solid fa-car-side"></i></div>
                                <div class="fl_box">
                                    <h3 class="mb-0 font14">{{routeInfo['toCity']['name']??''}}</h3>
                                </div>
                            </div>
                            <div v-else class="sch1" v-for="routeInfo, index in this.routeInfos">
                                <div class="fl_box">
                                    <h3 class="mb-0 font14">{{routeInfo['fromCity']['name']??''}}</h3>
                                </div>
                                <div class="fl_icon"><i class="fa-solid fa-car-side"></i></div>
                                <div class="fl_box">
                                    <h3 class="mb-0 font14">{{routeInfo['toCity']['name']??''}}</h3>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div  class="sch2">
                        <div class="pr_box">
                            <div class="prrt">Departure Date</div>
                            <div class="prrb">{{DateFormat(this.routeInfos[0]['travelDate'],'ddd, MMM Do YYYY')}}</div>
                        </div>
                    </div>
                    <div class="sch2">
                        <div class="pr_box">
                            <div class="prrt">Time</div>
                            <div class="prrb">{{showTimeTitle(this.routeInfos[0]['travelTime'])}}</div>
                        </div>
                    </div>
                    <div v-if="tripType == 2 && this.routeInfos[0]['returnDate']" class="sch2">
                        <div class="pr_box">
                            <div class="prrt">Return Date</div>
                            <div class="prrb">{{DateFormat(this.routeInfos[0]['returnDate'],'ddd, MMM Do YYYY')}}</div>
                        </div>
                    </div>
                    <div v-if="tripType == 2 && this.routeInfos[0]['returnTime']" class="sch2">
                        <div class="pr_box">
                            <div class="prrt">Time</div>
                            <div class="prrb">{{showTimeTitle(this.routeInfos[0]['returnTime'])}}</div>
                        </div>
                    </div>
                    <div class="sch2">
                        <div class="pr_box">
                            <div class="prrt">Trip Type</div>
                            <div class="prrb">
                                {{showTripTypeName(tripType)}}
                            </div>
                        </div>
                    </div>
    
                    <div class="modify_btn_sec">
                        <button @click="handleModifySearch">Modify Search <i class="fa-solid fa-angle-down"></i></button>
                    </div>                
                </div>
            </div>
        </div>
    </section>

        <OneWayPageLoader v-if="store.loadingName == 'searchForm'" />

        <section class="flight_res_sec" :class="(tripType == 1 || tripType == 2) ? 'bottom_action_active' : ''" v-if="this.filteredSearchResult.length > 0 || this.filteredSightseeingResult.length > 0">

            <div class="sightseen-tripType p-0" v-if="this.filteredSightseeingResult.length > 0">
                <div class="xl:container xl:mx-auto">
                    <div class="mt-5">
                        <h1 class="title text-2xl font-semibold mb-5">Sightseeing in {{this.routeInfos[0]['fromCity']['name']??''}}</h1>
                        <div class="sightseen_cardlist border border-slate-20 p-2 rounded-full" v-for="row in this.filteredSightseeingResult">
                            <SightseeingItem :row="row"/>
                        </div>
                    </div>
                </div>
            </div>

           <div class="xl:container xl:mx-auto" v-else-if="this.routeInfos && this.routeInfos.length > 0">
                <div class="flight_res_wrap cabsSearchlist pt-5">
                    <div class="flight_res_right">
                        <h1 class="directions-panel" v-if="store.seoData?.page_title" v-html="store.seoData?.page_title"></h1>
                        <div class="last_options">
                            <!-- <div class="results_option_left">
                            </div> -->
                            <div class="results_option_right">
                                <span class="result_day_option" v-if="this.routeInfos[0] && this.routeInfos[0]['pre_url']"><Link :href="this.routeInfos[0]['pre_url'] ">Previous Day</Link></span>
                                <span v-if="this.routeInfos[0]['pre_url']">|</span>
                                <span class="result_day_option" v-if="this.routeInfos[0] && this.routeInfos[0]['next_url']"><Link :href="this.routeInfos[0]['next_url']">Next Day</Link></span>
                            </div>
                        </div>
    
                        <div>
                            <div v-if="this.filteredSearchResult.length > 0" class="flight_table search_table_inr">
                                <table class="search_result_table cab_results">
                                    <tbody>
                                        <template v-if="this.filteredSearchResult.length > 0" v-for="cab, index in this.filteredSearchResult" :key="cab.id">
                                            <CabCard :id="index" :info="cab" :routeInfos="this.routeInfos" priceIdName="ONWARD" :tripType="tripType" :handleFareDetailPopup="handleFareDetailPopup" />
                                        </template>
                                    </tbody>
                                </table>
                            </div>    
                        </div>
                    </div>
                    <div class="flight_res_left map_inner">
                        <div class="directions-panel">Distance for selected route is {{store.searchData?.distance_text}} &nbsp;|&nbsp;Approx. {{store.searchData?.duration_text}}</div>
                        <div class="sightseen_swiper" v-if="tripType == 5">
                            <div  class="swiper" ref="sliderRef" v-if="sightseeingData?.images?.length > 0">
                             <div class="swiper-wrapper" >
                                <template v-for="image in sightseeingData?.images">
                                    <img class="swiper-slide" :src="image.image_thumb_src" :alt="image.title">
                                </template>
                             </div>
                            <div class="slider_pagination sightseen_Pagination" ref="sliderPagination"></div>
                          </div>
                            <div v-else-if="sightseeingData?.image_url">
                                <img
                                    :src="sightseeingData.image_url" :alt="sightseeingData.name">
                            </div>
                            
                        </div>
                        
                        <div class="" v-else>
                            <div id="floating-panel" class="mb-3" style="display:none">
                                <b>Mode of Travel: </b>
                                <select id="mode">
                                    <option value="DRIVING">Driving</option>
                                    <option value="WALKING">Walking</option>
                                    <option value="BICYCLING">Bicycling</option>
                                    <option value="TRANSIT">Transit</option>
                                </select>
                            </div>
                            <div id="map" style="width:100%;height:300px;"></div>
                            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.606679405773!2d77.37601177653477!3d28.6115740756761!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce4ffd0000001%3A0x1bb32545855ba5d9!2sAlliance%20Web%20Solution%20Pvt.%20Ltd.%20-%20IndiaInternets!5e0!3m2!1sen!2sin!4v1695809093848!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                        </div>
                    </div>
                </div>
            </div>
            
        </section>

        <section class="p-0" v-else>
            <div class="no_flight_found">
                <p class="errorMessage">
                    <strong>Sorry,</strong>
                    <p>We couldn't find any cabs for you.</p>
                    <button class="btn" @click="goback">Search again</button>
                </p>
                <div class="loading_img">
                    <img src="./assets/images/no-cab-found.jpg" class="img-fluid" alt="">
                </div>
            </div>
        </section>

        <div class="modal-mask fare_detailpopup" v-if="fareDetailPopup">
            <div class="modal-dialog fare_details">
                <button class="close" @click="handleFareDetailPopup(false)"><i class="fa-regular fa-circle-xmark"></i></button>
                <div class="modal-content" style="overflow-y: scroll;">
                    <div class="text-2xl font-semibold pb-2">Fare Details</div>
                    <!-- <div class="text-2xl">Total billing {{showPrice(this.fareDetailData.price??0)}}</div> -->
                    <table class="table table-bordered" style="font-size: 12px;">
                        <tbody>
                            <tr v-if="this.fareDetailData?.fareDetails?.per_day_km > 0">
                                <td>
                                    Package (Per Day KM)
                                </td>
                                <td style="text-align:right">
                                    {{this.fareDetailData?.fareDetails?.per_day_km??0}}
                                </td>
                            </tr>
                            <tr v-if="this.fareDetailData?.fareDetails?.no_of_days > 1">
                                <td>
                                    Number of Days
                                </td>
                                <td style="text-align:right">
                                    {{this.fareDetailData?.fareDetails?.no_of_days??0}}
                                </td>
                            </tr>
                            <tr v-if="this.fareDetailData?.fareDetails?.total_days_km > 0">
                                <td>
                                    Total Package (KM)
                                </td>
                                <td style="text-align:right">
                                    {{this.fareDetailData?.fareDetails?.total_days_km??0}}
                                </td>
                            </tr>
                            <tr v-if="this.fareDetailData?.fareDetails?.billable_distance > 0">
                                <td>
                                    Billable Distance (KM)
                                </td>
                                <td style="text-align:right">
                                    {{this.fareDetailData?.fareDetails?.billable_distance??0}}
                                </td>
                            </tr>
                            <tr style="background-color:#dae3f3">
                                <td>
                                    Base Fare
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.base_fare??0)}}
                                </td>
                            </tr>
                            <tr style="background-color:#dae3f3" v-if="this.fareDetailData?.fareDetails?.airport_entry_charge > 0">
                                <td>
                                    Airport Entry Charges and Toll
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.airport_entry_charge??0)}}
                                </td>
                            </tr>
                            <tr style="background-color:#dae3f3" v-if="this.fareDetailData?.fareDetails?.station_entry_charge > 0">
                                <td>
                                    Station Entry Charges and Toll
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.station_entry_charge??0)}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Rate per KM
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.price_per_km??0)}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Total KM
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.total_distance??0)}}
                                </td>
                            </tr>
                            <tr style="background-color:#dae3f3">
                                <td>
                                    Total KM Cost
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.total_distance_price??0)}}
                                </td>
                            </tr>
                            <tr v-if="this.fareDetailData?.fareDetails?.chauffeur_charge > 0">
                                <td>
                                    Chauffeur Charge Per Day
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.chauffeur_charge??0)}}
                                </td>
                            </tr>
                            <tr style="background-color:#dae3f3" v-if="this.fareDetailData?.fareDetails?.total_chauffeur_charge > 0">
                                <td>
                                    Total Chauffeur Charge
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.total_chauffeur_charge??0)}}
                                </td>
                            </tr>

                            <tr v-if="this.fareDetailData?.fareDetails?.night_stay_allowance > 0">
                                <td>
                                    Night Stay Allowance Per Night
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.night_stay_allowance)}}
                                </td>
                            </tr>
                            <tr v-if="this.fareDetailData?.fareDetails?.no_of_nights > 0">
                                <td>
                                    Number of Night
                                </td>
                                <td style="text-align:right">
                                    {{this.fareDetailData?.fareDetails?.no_of_nights??0}}
                                </td>
                            </tr>
                            <tr style="background-color:#dae3f3" v-if="this.fareDetailData?.fareDetails?.total_night_stay_allowance > 0">
                                <td>
                                    Total Night Stay Allowance
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData?.fareDetails?.total_night_stay_allowance??0)}}
                                </td>
                            </tr>
                            
                            <tr style="background-color:#dae3f3">
                                <td>
                                    Total Cost
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData.price??0)}}
                                </td>
                            </tr>
                            <tr style="background-color:#bdd7ee; border-top:solid; border-bottom:solid; font-weight: bold; border-top-color:var(--theme-color);">
                                <td>
                                    Grand Total
                                </td>
                                <td style="text-align:right">
                                    {{showPrice(this.fareDetailData.price??0)}}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <template v-if="this.fareDetailData?.inclusions">
                        <div class="text-xl">Includes</div>
                        <div class="text-sm" v-html="this.fareDetailData?.inclusions">
                        </div>
                    </template>

                    <template v-if="this.fareDetailData?.exclusions">
                        <div class="text-xl">Exclusions</div>
                        <div class="text-sm" v-html="this.fareDetailData?.exclusions">
                        </div>
                    </template>

                    <template v-if="this.fareDetailData?.terms">
                        <div class="text-xl">T&C</div>
                        <div class="text-sm" v-html="this.fareDetailData?.terms">
                        </div> 
                    </template>
                </div>
            </div>
        </div>

        <div class="pageLoader" :class="loading ? 'active' : ''">
            <LottieAnimation
            path="../assets/lottieFiles/loader.json"
            :loop="true"
            :autoPlay="true"
            :speed="1"
            :width="256"
            :height="256"
            />
        </div>
    </Layout>
</template>
<script>
import {DateFormat, showPrice, showErrorToast, getInfoTotalPrice, showTimeTitle, showTripTypeName} from '../utils/commonFuntions.js';
import { Link } from "@inertiajs/inertia-vue3";
import Slider from '@vueform/slider';
import '@vueform/slider/themes/default.css';
import CabCard from "../components/cab2/CabCard.vue";
import FlightBookCard from "../components/cab2/FlightBookCard.vue";
import SearchCountryForm from '../components/cab2/SearchCountryForm.vue';
import moment from 'moment';
import LottieAnimation from "lottie-vuejs/src/LottieAnimation.vue";
import { store } from '../store';
import Layout from '../components/layout.vue'
import OneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import SightseeingItem from '../components/cab2/sightseeingItem.vue';

export default {
    name: "CabSearch",
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        document.body.classList.add('headerType2');
        if(this.errors && this.errors.length > 0) {
            let currentObj = this;
            this.errors.forEach((error,index) => {
                currentObj.showErrorToast(error['message']);
            });
            setTimeout(() => {
                window.location.href='/flight';
                // currentObj.$inertia.get(`/flight`);
            },3000);
        }
        store.loadingName = false
        if(this.TripType) {
            store.tripType = this.TripType;
            this.tripType = this.TripType;
        }
        if(this.searchResult.data.length > 0) {
            this.filteredSearchResult =  this.searchResult.data;
        }
        if(this.searchResult.sightseeing.length > 0) {
            this.filteredSightseeingResult =  this.searchResult.sightseeing;
        }

    },

    beforeUnmount() {
        document.body.classList.remove('headerType2');
    },
    data() {
        return {
            store,
            showErrorToast,
            visible: false,
            filteredSearchResult: [],
            filteredSightseeingResult: [],
            errors: this.errors,
            routeInfos: this.routeInfos,
            modifySearch: false,
            loading: false,
            activeTab : 0,
            tripType : 1,
            fareDetailPopup:false,
            fareDetailData:[],
        }
    },
    methods : {
        DateFormat,
        showPrice,
        getInfoTotalPrice,
        showTimeTitle,
        showTripTypeName,
        handleModifySearch() {
            this.modifySearch = !this.modifySearch;
        },
        handleDescriptionModel() {
            $('.modal-wrapper').toggleClass('open');
            $('.page-wrapper').toggleClass('blur');
            return false;
        },
        goback(){
            store.loadingName = "searchForm";
            window.history.back();
        },
        handleFareDetailPopup(id) {
            if(id) {
                this.fareDetailData = this.filteredSearchResult.find((cab) => cab.id == id);
                // console.log('fareDetailData=',this.fareDetailData);
                this.fareDetailPopup = true;
            } else {
                this.fareDetailPopup = false;
            }
        },
        initMap() {
            let currentObj = this;
            if(currentObj?.search_data?.pickup?.lat && currentObj?.search_data?.pickup?.lng && currentObj?.search_data?.destination?.lat && currentObj?.search_data?.destination?.lng) {
                const directionsRenderer = new google.maps.DirectionsRenderer();
                const directionsService = new google.maps.DirectionsService();
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 14,
                    center: { lat: currentObj.search_data.pickup.lat, lng: currentObj.search_data.pickup.lng },
                });
                directionsRenderer.setMap(map);
                currentObj.calculateAndDisplayRoute(directionsService, directionsRenderer);
                document.getElementById("mode").addEventListener("change", () => {
                    currentObj.calculateAndDisplayRoute(directionsService, directionsRenderer);
                });
            }
        },
        calculateAndDisplayRoute(directionsService, directionsRenderer) {
            let currentObj = this;
            if(currentObj?.search_data?.pickup?.lat && currentObj?.search_data?.pickup?.lng && currentObj?.search_data?.destination?.lat && currentObj?.search_data?.destination?.lng) {
                const selectedMode = document.getElementById("mode").value;
                directionsService
                .route({
                    origin: { lat: currentObj.search_data.pickup.lat, lng: currentObj.search_data.pickup.lng },
                    destination: { lat: currentObj.search_data.destination.lat, lng: currentObj.search_data.destination.lng },
                    // Note that Javascript allows us to access the constant
                    // using square brackets and a string value as its
                    // "property."
                    travelMode: google.maps.TravelMode[selectedMode],
                })
                .then((response) => {
                    directionsRenderer.setDirections(response);
                })
                .catch((e) => window.alert("Directions request failed due to " + status));
            }
        }
    },
    
    mounted () {
        let currentObj = this;
        setTimeout(() => {
            currentObj.initMap();
        },500);

        var sliderElem = this.$refs.sliderRef;
        var sliderNextBtn = this.$refs.sliderNextRef;
        var sliderPrevBtn = this.$refs.sliderPrevRef;
        var sliderPagination = this.$refs.sliderPagination;

        new Swiper(sliderElem, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            speed:1000,
            
            navigation: {
                nextEl: sliderPrevBtn,
                prevEl: sliderNextBtn,
            },
            pagination: {
                el: sliderPagination,
                clickable: true,
                dynamicBullets: true,
            },
            
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });
    },
    beforeDestroy (){

    },
    watch:{

    },
    components: {
        Link,
        Slider,
        CabCard,
        FlightBookCard,
        SearchCountryForm,
        LottieAnimation,
        Layout,
        OneWayPageLoader,
        SightseeingItem
    },
   props:["search_data", "seo_data", "searchResult", "routeInfos", "TripType", "sightseeingData"]
}
</script>
