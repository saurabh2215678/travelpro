<template>
    <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <section class="inner_banner search_cab">
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
    </section>

        <OneWayPageLoader v-if="store.loadingName == 'searchForm'" />

        <section class="flight_res_sec" :class="(tripType == 1 || tripType == 2) ? 'bottom_action_active' : ''" v-if="this.filteredSearchResult.ONWARD.length > 0">
           
        <template v-if="this.routeInfos[0]['places'] ">
             <div class="xl:container xl:mx-auto">
             <div class="row">
              <div class="itinerary_list">
                <h3>Itinerary</h3>
                <p>{{ this.routeInfos[0]['places'] }}
                <template v-if="this.routeInfos[0]['description'] "> </template>
                </p>
                <a class="trigger btn-pop" href="javascript:;" @click="handleDescriptionModel">Click for Detailed Itinerary</a>
                <div class="modal-wrapper">
                <div class="modal">
                    <div class="head">

                    <a class="btn-close trigger" href="javascript:;" @click="handleDescriptionModel"></a>
                    </div>
                    <div class="content">
                          <h3 style="color:#01b2a6; font-size:22px">{{ this.routeInfos[0]['sightseening']['name'] }}</h3>
                    <div v-html="this.routeInfos[0]['description']">
                     
                    </div>
                    </div>
                </div>
                </div>
              </div>
             </div>
           </div>
        </template>
          

           <div class="xl:container xl:mx-auto">
                <div class="flight_res_wrap">
                    <div class="flight_res_left" :class="(tripType == 2 || store.price_range[this.activeTab] || 1==2 || tripType == 2 && 1==2 || tripType != 2 && 1==2) ? 'shown' : 'hidden' ">
                        <template v-if="tripType == 2" v-for="routeInfo, index in this.routeInfos">
                            <div v-if="store.price_range[index] && this.activeTab==index" class="price_silder">
                                <h3>Price</h3>
                                <Slider 
                                v-model="store.price_range[index]['range']" 
                                :min= "store.price_range[index]['min']"
                                :max= "store.price_range[index]['max']"
                                :step= "1000"
                                @change="handlePriceChange"
                                />
                            </div>
                        </template>
                        <div v-else-if="store.price_range[this.activeTab]" class="price_silder">
                            <h3>Price</h3>
                            <Slider 
                            v-model="store.price_range[this.activeTab]['range']" 
                            :min= "store.price_range[this.activeTab]['min']"
                            :max= "store.price_range[this.activeTab]['max']"
                            :step= "1000"
                            @change="handlePriceChange"
                            />
                        </div>
                        <div class="show_sec" v-if="1==2">
                            <label><span>Show Incv</span> <input type="checkbox" value="Show Incv" @click="handleShowIncv"></label>
                            <label><span>Show Net</span> <input type="checkbox" value="Show Net" @click="handleShowNet"></label>
                        </div>
                       
                        <template v-for="routeInfo, index in this.routeInfos" v-if="tripType == 2 && 1==2">                    
                            <div class="fare_identifier" v-if="this.activeTab==index">
                                <h3 class="font18 fw600">Fare Identifier</h3>
                                <ul v-if="store.fare_identifier[index]">
                                    <li v-if="store.fare_identifier[index]['published']"><label><input type="checkbox" name="fare-identifier" value="published" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[index]['published']['counter']}}</span></label></li>
                                    <li v-if="store.fare_identifier[index]['sme']"><label><input type="checkbox" name="fare-identifier" value="sme" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[index]['sme']['counter']}}</span></label></li>
                                    <li v-if="store.fare_identifier[index]['corporate']"><label><input type="checkbox" name="fare-identifier" value="corporate" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[index]['corporate']['counter']}}</span></label></li>
                                    <li v-if="store.fare_identifier[index]['sale']"><label><input type="checkbox" name="fare-identifier" value="sale" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[index]['sale']['counter']}}</span></label></li>
                                </ul>
                            </div>
                        </template>
                        <div class="fare_identifier" v-if="tripType != 2 && 1==2" >
                            <h3 class="font18 fw600">Fare Identifier</h3>
                            <ul v-if="store.fare_identifier[this.activeTab]">
                                <li v-if="store.fare_identifier[this.activeTab]['published']"><label><input type="checkbox" name="fare-identifier" value="published" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[this.activeTab]['published']['counter']}}</span></label></li>
                                <li v-if="store.fare_identifier[this.activeTab]['sme']"><label><input type="checkbox" name="fare-identifier" value="sme" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[this.activeTab]['sme']['counter']}}</span></label></li>
                                <li v-if="store.fare_identifier[this.activeTab]['corporate']"><label><input type="checkbox" name="fare-identifier" value="corporate" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[this.activeTab]['corporate']['counter']}}</span></label></li>
                                <li v-if="store.fare_identifier[this.activeTab]['sale']"><label><input type="checkbox" name="fare-identifier" value="sale" @change="handleFareIdentifier"><i></i><span>{{store.fare_identifier[this.activeTab]['sale']['counter']}}</span></label></li>
                            </ul>
                        </div>
                    </div>
                    <div class="flight_res_right">
                        <div class="last_options">
                            <div class="results_option_left">
                               
                            </div>
                            <div class="results_option_right">
                                <span class="result_day_option" v-if="this.routeInfos[0]['pre_url']"><a :href="this.routeInfos[0]['pre_url'] ">Previous Day</a></span>
                                <span v-if="this.routeInfos[0]['pre_url']">|</span>
                                <span class="result_day_option"><a :href="this.routeInfos[0]['next_url'] ">Next Day</a></span>
                            </div>
                        </div>
                        <div class="flights_head" v-if="1==2">
                            <h4 v-if="tripType==0" class="font13">Found {{this.totalFlights}} Flights from {{this.routeInfos[0]['fromCity']['name']}} to {{this.routeInfos[0]['toCity']['name']}}</h4>
                            <div class="share_by">
                                <span><i class="fa-solid fa-share-nodes"></i> Share By: </span>
                                <span><i class="fa-brands fa-whatsapp"></i> Whatsapp</span>
                                <span><i class="fa-solid fa-envelope"></i> Email</span>
                                <span><i class="fa-solid fa-eye"></i> View</span>
                            </div>
                        </div>

                        <!-- <div class="itenory_box">
                            <p><b>Tour</b>: Indus valley to Hemis (1-day tour)</p>
                            <p><b>Itinerary</b>: Leh - Stok - Stakna - Hemis - Thiksey - Shey - Leh</p>
                            <p><b>Total distance</b>: 100 km</p>
                        </div> -->
    
                        <div>
                            <div v-if="this.filteredSearchResult.ONWARD" class="flight_table search_table_inr">
                                <table class="search_result_table cab_results">
                                    <tbody>
                                        <template v-if="this.filteredSearchResult.ONWARD" v-for="cab, index in this.filteredSearchResult.ONWARD" :key="cab.id">
                                            <CabCard :id="index" :info="cab" :routeInfos="this.routeInfos" :paxInfo="this.paxInfo" priceIdName="ONWARD" :totalFlights="this.filteredSearchResult.ONWARD.length" :routeIndex="0" :tripType="tripType" />
                                        </template>
                                    </tbody>
                                </table>
                            </div>
    
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
import {DateFormat, showPrice, showErrorToast, getInfoTotalPrice, showTimeTitle} from '../utils/commonFuntions.js';
import { Link } from "@inertiajs/inertia-vue3";
import Slider from '@vueform/slider';
import '@vueform/slider/themes/default.css';
import CabCard from "../components/cab/CabCard.vue";
import FlightBookCard from "../components/cab/FlightBookCard.vue";
import SearchCountryForm from '../components/cab/SearchCountryForm.vue';
import moment from 'moment';
import LottieAnimation from "lottie-vuejs/src/LottieAnimation.vue";
// import {searchResultResponse} from "./data.js";
import { store } from '../store';
import Layout from '../components/layout.vue'
import OneWayPageLoader from '../skeletons/oneWayPageLoader.vue';

import {paxInfo, airportLists, form_data} from './data.js';

export default {
    created() {
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
          /*  newSearchResult = newSearchResult.sort((a, b) => {
                console.log("a=:",a);
                return a.sort_order-b.sort_order;
                });*/
            //this.searchResult.ONWARD = newSearchResult;
            // this.filteredSearchResult.ONWARD = newSearchResult;
            this.filteredSearchResult.ONWARD =  this.searchResult.ONWARD;
        }


    },

    beforeUnmount() {
        //console.log(this.$el)
        document.body.classList.remove('headerType2');
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
            airportLists: airportLists
        }
    },
    methods : {
        DateFormat,
        showPrice,
        getInfoTotalPrice,
        showTimeTitle,
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
        LottieAnimation,
        Layout,
        OneWayPageLoader
    },
   props:["destinationLists", "airportLists", "sightseeningDestinationLists", "routeList", "searchResult", "routeInfos", "tripType", "metaTitle", "metaDescription"]
}


</script>
