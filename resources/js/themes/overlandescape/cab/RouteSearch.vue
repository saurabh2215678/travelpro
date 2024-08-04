<template>
    <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <SearchCountryFormWrapper class="inner_banner search_cab">
        <div class="head_band">
            <div class="xl:container xl:mx-auto">
                <div class="search_inner_top_wrapper" v-if="modifySearch">
                   <SearchCountryForm :destinationLists="destinationLists" TripType="0" :routeList="routeList" :airportLists="airportLists" :routeInfos="this.routeInfos" :sightseeningDestinationLists="sightseeningDestinationLists" />
                    <div class="closeModifySearch"  @click="handleModifySearch"><i class="fa-solid fa-xmark"></i></div>
                </div>
                <div class="flight_search_detail" v-else>
                    <div class="flight_info_scroller">

                        <template v-if="this.routeInfos[0]['sightseening']['name']">
                                <div class="fl_box">
                                    <h3 class="mb-0 font17">{{this.routeInfos[0]['fromCity']['name']??''}}</h3>
                                </div>
                                 <div class="fl_icon"><i class="fa-solid fa-car-side"></i></div>
                            <div class="fl_box">
                             <h3>{{ this.routeInfos[0]['sightseening']['name'] }}</h3>
                             </div>
                        </template>
                        <template v-else >
                            <div  v-if="store.tripType == 2" class="sch1" v-for="routeInfo, index in this.routeInfos">
                                <div class="fl_box">
                                    <h3 class="mb-0 font17">{{routeInfo['fromCity']['name']??''}}</h3>
                                    <!-- <p class="mb-0 font14">{{routeInfo['fromCity']['name']??''}}, {{routeInfo['fromCity']['name']??''}}</p> -->
                                </div>
                                <div class="fl_icon"><i class="fa-solid fa-car-side"></i></div>
                                <div class="fl_box">
                                    <h3 class="mb-0 font17">{{routeInfo['toCity']['name']??''}}</h3>
                                    <!-- <p class="mb-0 font14">{{routeInfo['toCity']['name']??''}}, {{routeInfo['toCity']['name']??''}}</p> -->
                                </div>
                            </div>
                            <div v-else class="sch1" v-for="routeInfo, index in this.routeInfos">
                                <div class="fl_box">
                                    <h3 class="mb-0 font17">{{routeInfo['fromCity']['name']??''}}</h3>
                                    <!-- <p class="mb-0 font14">{{routeInfo['fromCity']['name']??''}}, {{routeInfo['fromCity']['name']??''}}</p> -->
                                </div>
                                <div class="fl_icon"><i class="fa-solid fa-car-side"></i></div>
                                <div class="fl_box">
                                    <h3 class="mb-0 font17">{{routeInfo['toCity']['name']??''}}</h3>
                                    <!-- <p class="mb-0 font14">{{routeInfo['toCity']['name']??''}}, {{routeInfo['toCity']['name']??''}}</p> -->
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
                    <div v-if="tripType == 1 && 1==2" class="sch2">
                        <div class="pr_box">
                            <div class="prrt">Return Date</div>
                            <div class="prrb">{{DateFormat(this.routeInfos[1]['travelDate'],'ddd, MMM Do YYYY')}}</div>
                        </div>
                    </div>
                    <div class="sch2">
                        <div class="pr_box">
                            <div class="prrt">Time</div>
                            <div class="prrb">{{showTimeTitle(this.routeInfos[0]['travelTime'])}}</div>
                        </div>
                    </div>
                    <div class="sch2">
                        <div class="pr_box">
                            <div class="prrt">No of Adults</div>
                            <div class="prrb">{{this.routeInfos[0]['adult']}}</div>
                        </div>
                    </div>
                    <div class="sch2">
                        <div class="pr_box">
                            <div class="prrt">No of Child</div>
                            <div class="prrb">{{this.routeInfos[0]['children']}}</div>
                        </div>
                    </div>
                    <div class="sch2">
                        <div class="pr_box">
                            <div class="prrt">Trip Type</div>
                            <div class="prrb">
                                {{
                                    store.tripType == 0 ? 'One way' :
                                    store.tripType == 1 ? 'Round trip' :
                                    store.tripType == 2 ? 'Sightseeing' : 'AIRPORT'
                                }}
                            </div>
                        </div>
                    </div>

                    <div class="modify_btn_sec">
                        <button @click="handleModifySearch">Modify Search <i class="fa-solid fa-angle-down"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </SearchCountryFormWrapper>

        <OneWayPageLoader v-if="store.loadingName == 'searchForm'" />

        <section class="sightseen-tripType p-0" v-if="originSightSeenRouteData.data[0]">
            <div class="xl:container xl:mx-auto">
                <div class="searchtitle mt-5">
                    <h1 class="title text-2xl font-semibold mb-5">Cab from {{this.routeInfos[0]['fromCity']['name']??''}}</h1>
                    <ul>
                        <li><input type="radio" name="cabType" value="all" :checked="cabType=='all'" @change="handleCabTypeChange($event)" > All Cabs</li>
                        <li><input type="radio" name="cabType" value="private" :checked="cabType=='private'" @change="handleCabTypeChange($event)" > Private Cabs</li>
                        <li><input type="radio" name="cabType" value="shared" :checked="cabType=='shared'" @change="handleCabTypeChange($event)" > Shared Cabs</li>
                    </ul>
                    <template v-if="sightSeeingResults.length > 0">
                        <div class="sightseen_cardlist border border-slate-20 p-2 rounded-full" v-for="row in sightSeeingResults" :key="row.id">
                            <SightseeingItem :row="row"/>
                        </div>
                    </template>
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
import {DateFormat, showPrice, showErrorToast, getInfoTotalPrice, showTimeTitle,showPopup, hidePopup} from '../utils/commonFuntions.js';
import { Link } from "@inertiajs/inertia-vue3";
import Slider from '@vueform/slider';
import '@vueform/slider/themes/default.css';
import CabCard from "../components/cab/RouteCard.vue";
import FlightBookCard from "../components/cab/FlightBookCard.vue";
import SearchCountryForm from '../components/cab/SearchCountryForm.vue';
import {SearchCountryFormWrapper} from '../styles/SearchCountryFormWrapper.js';
import moment from 'moment';
import LottieAnimation from "lottie-vuejs/src/LottieAnimation.vue";
// import {searchResultResponse} from "./data.js";
import { store } from '../store.js';
import Layout from '../components/layout.vue'
import OneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import SightseeingItem from '../components/cab/sightseeingItem.vue';

import {paxInfo, airportLists, form_data} from './data.js';

export default {
    created() {
        // console.log('routeInfos',this.routeInfos);
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
        store.tripType = this.tripType;

        if(this.searchResult.ONWARD){

            let newSearchResult = this.searchResult.ONWARD;
            newSearchResult = newSearchResult.sort((a, b) => {
                // console.log("a=:",a);
                return a.sort_order-b.sort_order;
                });
            //this.searchResult.ONWARD = newSearchResult;
            this.filteredSearchResult.ONWARD = newSearchResult;
        }

        if(this.routeInfos[0] && this.routeInfos[0]['cabType']) {
            this.cabType = this.routeInfos[0]['cabType'];
        }
        if(this.originSightSeenRouteData.data) {
            this.sightSeeingResults = this.originSightSeenRouteData.data;
        }
    },
    data() {
        return {
            showErrorToast,
            visible: false,
            // searchResult: this.searchResult,
            filteredSearchResult: {},
            errors: this.errors,
            paxInfo: paxInfo,
            routeInfos: this.routeInfos,
            cabinClass: 'Economy',
            totalFlights: 83,
            form_data: form_data,
            url: this.url,
            store,
            modifySearch: false,
            filter_stops: {},
            filter_departure_arrival: {},
            filter_fare_identifier: [],
            filter_airlines: {},
            loading: false,
            stopsActiveTab : 0,
            activeTab : 0,
            showSingleBooking : false,
            sortAscDesc : {},
            tripType : 0,
            airportLists: airportLists,
            sightSeeingResults: [],
            cabType: 'all'
        }
    },
    beforeUnmount() {
    //console.log(this.$el)
    document.body.classList.remove('headerType2');
  },

    methods : {
        DateFormat,
        showPrice,
        getInfoTotalPrice,
        showTimeTitle,
        handleModifySearch() {
            this.modifySearch = !this.modifySearch;
        },
        handlePopup(){
            store.popupContent = `${this.$refs.popRef.innerHTML}`;
            showPopup();
        },
         closeClick(){
            hidePopup();
        },
        handleDescriptionModel() {
            $('.modal-wrapper').toggleClass('open');
            $('.page-wrapper').toggleClass('blur');
            return false;
        },
        handleCabTypeChange(e) {
            var name = e.target.name;
            var value = e.target.value;
            this.cabType = value;
            if(this.originSightSeenRouteData.data) {
                let sightSeeingResultsNew = this.originSightSeenRouteData.data;
                var newCabRoutes = [];
                if(value=='private') {
                    sightSeeingResultsNew.forEach((cabRoute, index) => {
                        if(cabRoute.sharing==0) {
                            newCabRoutes.push(cabRoute);
                        }
                    });
                    sightSeeingResultsNew = newCabRoutes;
                } else if(value=='shared') {
                    sightSeeingResultsNew.forEach((cabRoute, index) => {
                        if(cabRoute.sharing==1) {
                            newCabRoutes.push(cabRoute);
                        }
                    });
                    sightSeeingResultsNew = newCabRoutes;
                } else {

                }
                this.sightSeeingResults = sightSeeingResultsNew;
            }
        },       
        goback(){
            store.loadingName = "searchForm";
            window.history.back();
        }        
    },
    mounted () {
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
        SearchCountryFormWrapper,
        LottieAnimation,
        Layout,
        OneWayPageLoader,
        SightseeingItem
    },
   props:["destinationLists", "airportLists", "sightseeningDestinationLists","originSightSeenRouteData", "routeList", "searchResult", "routeInfos", "tripType", "metaTitle", "metaDescription"]
}


</script>
