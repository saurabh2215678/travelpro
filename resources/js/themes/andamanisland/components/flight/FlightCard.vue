<template>
       <flight-card-wrapper v-if="this.flightSegments.length > 0 && this.showFlightCard">
            <td>
                <div class="mnt_wrap">
                    <template v-for="flightSegment in this.flightSegments">
                        <div class="flight_detail">
                            <img :src="getLogo(flightSegment[0].fD.aI.code)" v-bind:alt="flightSegment[0].fD.aI.name">
                            <div class="fl-t">
                                <p v-if="flightSegment[0].fD.aI.name">{{flightSegment[0].fD.aI.name}}</p>
                                <span>{{getFlightCodes(flightSegment)}}</span>
                            </div>
                        </div>
                    </template>
                    <div class="flight_actions">
                        <button @click="handleDetails" :class="buttonLoading ? 'loading_btn' : ''">
                            View Details
                            <i class="fa-solid fa-minus" v-if="this.viewDetails"></i>
                            <i class="fa-solid fa-plus" v-else></i>
                        </button>
                        <span v-if="this.priceId && getSeatLeft(this.info, this.priceId) > 0" class="seat_info">Seats left:&nbsp;<p :class="getSeatColor(this.info)"> {{getSeatLeft(this.info, this.priceId)}}</p></span>
                        <!-- <span v-if="store.websiteSettings && store.websiteSettings.FLIGHTS_FEW_SEATS_LEFT_ALERT && getSeatLeft(this.info) < store.websiteSettings.FLIGHTS_FEW_SEATS_LEFT_ALERT" class="seat_info"><p class="red">Few seats left</p></span> -->
                    </div>
                </div>
            </td>
            <td>
                <template v-for="flightSegment in this.flightSegments">
                    <div class="assd_content departure_content">
                        <h4>{{flightSegment[0].da.code}}</h4>
                        <p>{{DateFormat(flightSegment[0].dt,'HH:mm')}}</p>
                        <p>{{DateFormat(flightSegment[0].dt,'MMM D')}}</p>
                    </div>
                </template>
            </td>
            <td>
                <template v-for="flightSegment in this.flightSegments">
                    <div class="mmd_sec">
                        <p>{{getFlightStop(flightSegment)}}</p>
                        <span class="mmd_arrow"></span>
                        <p>{{timeConvert(getTotalDuration(flightSegment))}}</p>
                    </div>
                </template>
            </td>
            <td>
                <template v-for="flightSegment in this.flightSegments">
                    <div class="assd_content arrival_content">
                        <h4>{{flightSegment[flightSegment.length-1].aa.code}}</h4>
                        <p>{{DateFormat(flightSegment[flightSegment.length-1].at,'HH:mm')}}</p>
                        <p>{{DateFormat(flightSegment[flightSegment.length-1].at,'MMM D')??''}}</p>
                    </div>
                </template>
            </td>
            
            <td>
                <ul class="assd_price_list" :class="toggleContent ? 'showall' : 'showless'">
                    <template v-for="(priceList,index) in this.priceSegments">
                        
                    <li v-if="priceList.fareIdentifier != 'SPECIAL_RETURN'" :class="(index>2)?'toggle_price':''" >
                        <label>
                            <div class="prc_chk_top">
                                <input v-if="showSingleBooking" type="radio" :name="`price_chk_${id}`" :value="`${priceList.id}`" :checked="index == 0" @change="handlePriceChange($event, this.info)" />
                                <input v-else type="radio" :name="`price_chk_${this.priceIdName}`" :value="`${priceList.id}`" @change="handlePriceChange($event, this.info)" :checked="store.priceIds[`price_chk_${this.priceIdName}`]==priceList.id" />
                                <div class="prc_right">
                                    <span v-if="1==2">612112</span>
                                    <p v-if="priceList.fd.ADULT.fC.TF">
                                        {{showPrice(getInfoTotalPrice(info, priceList.id, this.paxInfo))}} 
                                        <span v-if="store.filter_showIncv">INC {{showPrice(getInfoTotalPrice(info, priceList.id, this.paxInfo)-getNetPrice(priceList))}}</span>
                                        <span v-if="store.filter_showNet">NET {{showPrice(getNetPrice(priceList))}}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="prc_chk_btm">
                                <h4 v-if="priceList.fareIdentifier" :class="this.showFareTypeColor(priceList.fareIdentifier)">{{this.showFareTypeUi(priceList.fareIdentifier)}}</h4>
                                <span v-if="priceList.fd.ADULT.cc">{{this.showCabinClass(priceList.fd.ADULT.cc)}}</span>
                                <span v-if="priceList.fd.ADULT.mI==1">Free Meal</span>
                                <span v-if="priceList.fd.ADULT.rT==0" class="red">Non-Refundable</span>     
                                <span v-else-if="priceList.fd.ADULT.rT==1" class="green">Refundable</span>
                                <span v-else-if="priceList.fd.ADULT.rT==2" class="green">Partial Refundable</span>
                            </div>
                        </label>
                    </li>
                    
                    </template>
                    <span class="price_arrow" @click="this.toggleContent = !this.toggleContent"></span>

                
                </ul>
            </td>
            <td v-if="showSingleBooking">
                <button class="btn" @click="handleBook">Book</button>
                <!-- <Link class="btn" @click="handleBook">Book</Link> -->
            </td>
        </flight-card-wrapper>

        <Airlinetab 
        v-if="this.tabData.tripInfos && this.tabData.tripInfos[routeIndex]['totalPriceList'][0]['id'] && this.viewDetails && !buttonLoading" 
        :tabData="this.tabData" 
        :handleHideDetails="this.handleHideDetails"
        :active="true" 
        :key="this.tabData.tripInfos[routeIndex]['totalPriceList'][0]['id']" 
        :showSingleBooking="showSingleBooking" 
        :routeIndex="routeIndex"
        :paxInfo="this.paxInfo"
        />
    
</template>

<script>
import {DateFormat, timeConvert, getLogo, showPrice, showErrorToast, showCabinClass, getSeatLeft, getSeatColor, getInfoTotalPrice, showFareTypeColor, showFareTypeUi, getTotalDuration} from '../../utils/commonFuntions.js';
import { Link } from "@inertiajs/inertia-vue3";
import Airlinetab from "./Airlinetab.vue";
import { store } from '../../store';
import axios from "axios";
import styled from 'vue3-styled-components';

const FlightCardWrapper = styled.tr`
.flight_table & td {
        border:none;
        background-color: #0000;
        color: initial;
    } 

`;

export default {
    name: "FlightCard",
    created() {
        if(this.info.sI && this.info.sI.length > 0) {
            let flightSegments = [];
            let counter = -1;
            this.info.sI.forEach((flight, index) => {
                if(flight.sN==0) {
                    counter = counter + 1;
                }
                if(!flightSegments[counter]) {
                    flightSegments[counter] = [];
                }
                flightSegments[counter].push(flight);
            });
            // console.log('flightSegments=',flightSegments);
            this.flightSegments = flightSegments;
        }

        if(this.info.totalPriceList && this.info.totalPriceList.length > 0) {
            let newTotalPriceList = this.info.totalPriceList;
            newTotalPriceList = newTotalPriceList.sort((a, b) => {
              return a.fd.ADULT.fC.TF-b.fd.ADULT.fC.TF;
            });
            this.priceSegments = newTotalPriceList;
            this.priceId = newTotalPriceList[0].id;

            if( (newTotalPriceList[0] && newTotalPriceList[0].fareIdentifier == 'SPECIAL_RETURN') && this.priceIdName != 'COMBO' ) {
                this.showFlightCard = false;
            }
        }
    },

    data() {
        return {
            showErrorToast,
            ratingNum: [0, 0],
            viewDetails: false,
            tripType: this.tripType,
            activeTab: this.activeTab,
            flightSegments: [],
            priceSegments: [],
            tabData: { 'data1': 'content1' },
            info: this.info,
            paxInfo: this.paxInfo,
            totalFlights: this.totalFlights,
            priceId : '',
            priceIdName: this.priceIdName,
            routeInfos: this.routeInfos,
            store,
            buttonLoading: false,
            totalPriceList: [],
            showFlightCard: true,
            toggleContent:false,
        }
    },

    methods: {
        DateFormat,
        timeConvert,
        getLogo,
        showPrice,
        showCabinClass,
        getSeatLeft,
        getSeatColor,
        getInfoTotalPrice,
        showFareTypeColor,
        showFareTypeUi,
        getTotalDuration,
        getFlightCodes: function(flights) {
            let arr = [];
            flights.forEach((value, index) => {
                arr.push(value.fD.aI.code+'-'+value.fD.fN);
            });
            return arr.join(', ');
        },
        getFlightStop : function(flights){
            let stop = flights.length - 1;
            if(stop == 0) {
                return 'Non-Stop'
            } else {
                return stop+'-Stop(s)';
            }
        },
        getNetPrice : function(priceList){
            // console.log("getNetPrice.paxInfo=",this.paxInfo);
            let totalPrice = 0;
            let totalAdultPrice = 0;          
            let totalChildPrice = 0;
            let totalInfantPrice = 0;
            if(this.paxInfo && priceList.fd) {
                if(this.paxInfo.ADULT > 0) {
                    totalAdultPrice = priceList.fd.ADULT.fC.NF * this.paxInfo.ADULT; 
                }
                if(this.paxInfo.CHILD > 0) {
                    totalChildPrice = priceList.fd.CHILD.fC.NF * this.paxInfo.CHILD; 
                }
                if(this.paxInfo.INFANT > 0) {
                    totalInfantPrice = priceList.fd.INFANT.fC.NF * this.paxInfo.INFANT; 
                }
            }
            totalPrice = totalAdultPrice + totalChildPrice + totalInfantPrice;
            return parseFloat(totalPrice).toFixed(2);
        },
        handleHideDetails : function(){
            this.viewDetails = false;
        },
        async handleDetails() {
            this.viewDetails = !this.viewDetails;
            this.buttonLoading = true;
            if(this.viewDetails) {
                let oldPriceIds = store.priceIds;
                // oldPriceIds[`price_chk_${this.priceIdName}`] = this.priceSegments[0].id;
                if(this.priceId) {
                    oldPriceIds[`price_chk_${this.priceIdName}`] = this.priceId;
                } else {
                    oldPriceIds[`price_chk_${this.priceIdName}`] = this.priceSegments[0].id;
                }

                var priceIds = [];
                if(this.showSingleBooking) {
                    priceIds.push(this.priceId);
                } else {
                    if(this.tripType==1) {
                        if(oldPriceIds['price_chk_ONWARD'] && oldPriceIds['price_chk_RETURN']) {
                            priceIds.push(oldPriceIds['price_chk_ONWARD']);
                            priceIds.push(oldPriceIds['price_chk_RETURN']);
                        }
                    } else if(this.tripType==2) {
                        this.routeInfos.forEach((value, index) => {
                            if(oldPriceIds[`price_chk_${index}`]) {
                                var price_id = oldPriceIds[`price_chk_${index}`];
                                priceIds.push(price_id);
                            }
                        });                
                    } else if(this.priceId) {
                        priceIds.push(this.priceId);
                    }
                }
                // console.log('priceIds=',priceIds);
                
                if(this.showSingleBooking || priceIds.length == this.routeInfos.length) {
                    var priceIdsstr = priceIds.join(',');
                    const priceDetailResponse = await axios.post('/flight/getFareDetails', {
                        price_id: priceIdsstr,
                    });
                    // this.tabData = priceDetailResponse.data.PriceDetail;
                    if(priceDetailResponse.data.PriceDetail && priceDetailResponse.data.PriceDetail.status.success) {
                        this.tabData = priceDetailResponse.data.PriceDetail;
                    } else {
                        if(priceDetailResponse.data.PriceDetail.errors && priceDetailResponse.data.PriceDetail.errors.length > 0) {
                            let currentObj = this;
                            priceDetailResponse.data.PriceDetail.errors.forEach((error,index) => {
                                currentObj.showErrorToast(error['message']);
                            });
                        }
                    }
                } else {
                    this.viewDetails = false;
                    alert('Please select all segments flights to Book');
                }
            }
            this.buttonLoading = false;
        },
        async handlePriceChange(e, info) {
            var name = e.target.name;
            var info_name = name+'_info';
            var priceId = e.target.value;
            var priceIds = store.priceIds;
            priceIds[name] = priceId;
            priceIds[info_name] = info;
            store.priceIds = priceIds;
            this.priceId = e.target.value;
        },
        handleBook() {
            if(this.priceId) {
                store.bookingPassedStep= 0;
                store.bookingCurrentStep = 1;
                store.loadingName = 'iternity'
                this.$inertia.get(`/flight/itinerary/${this.priceId}`);
            } else {
                alert('Please select price to Book');
            }
        },
    },

    props: ["id", "info", "paxInfo", "priceIdName", "routeIndex", "routeInfos", "totalFlights", "tripType", "showSingleBooking"],
    components: {
        Airlinetab,
        Link,
        'flight-card-wrapper' : FlightCardWrapper
    }
}

</script>