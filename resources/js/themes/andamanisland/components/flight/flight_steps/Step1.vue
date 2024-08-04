<template>
    <OneWayPageLoader v-if="store.loadingName == 'searchForm'" />
    <div class="stp_wrap" v-else>
        <div class="stp_tp_head">
            <h3>Flight Details</h3>
            <Link @click="goback" >Back to Search</Link>
        </div>
        <div class="rts_flight" >
            <div class="fts_top">
                <template v-for="(tripInfos, key) in info.tripInfos">
                <element v-for="flightData in tripInfos.sI">
                    <div class="minimal_detail_box">
                        <div class="minimal_left">
                            <span>{{flightData.da.city??''}}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                            <span>{{flightData.aa.city??''}}</span>
                            <p>on {{DateFormat(flightData.dt,'ddd, MMM Do YYYY')}}</p>
                        </div>
                        <div class="minimal-right">
                            <span><i class="fa-regular fa-clock"></i> {{timeConvert(flightData.duration)}}</span>
                        </div>
                    </div>
                    <table class="fls_tbl">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="flight_detail">
                                        <img :src="getLogo(flightData.fD.aI.code)" v-bind:alt="flightData.fD.aI.name">
                                        <div class="fl-t">
                                            <p>{{flightData.fD.aI.name}}</p>
                                            <span>{{flightData.fD.aI.code}}-{{flightData.fD.fN}}</span>
                                            <span><i class="fa-solid fa-plane"></i> {{flightData.fD.eT??''}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="assd_content departure_content">
                                        <h3>{{DateFormat(flightData.dt,'MMM D, ddd, HH:mm')}}</h3>
                                        <p>
                                        {{flightData.da.city??''}}, {{flightData.da.country??''}}<br />{{flightData.da.name??''}}<element v-if="flightData.da.terminal"><br />{{flightData.da.terminal??''}}</element>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="mmd_sec">
                                        <p>{{(flightData.stops > 0)?flightData.stops:'Non'}}-Stop</p>
                                        <span class="mmd_arrow"></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="assd_content arrival_content">
                                        <h3>{{DateFormat(flightData.at,'MMM D, ddd, HH:mm')}}</h3>
                                        <p>
                                        {{flightData.aa.city??''}}, {{flightData.aa.country??''}}<br />{{flightData.aa.name??''}}<element v-if="flightData.aa.terminal"><br />{{flightData.aa.terminal??''}}</element>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="assd_content">
                                        <h3>{{timeConvert(flightData.duration)}}</h3>
                                        <p>
                                            <span class="pr-1" v-if="tripInfos.totalPriceList[0].fd.ADULT.cc">{{this.showCabinClass(tripInfos.totalPriceList[0].fd.ADULT.cc)}}</span>
                                            <span class="pr-1" v-if="tripInfos.totalPriceList[0].fd.ADULT.mI==1">Free Meal</span>
                                            <span v-if="tripInfos.totalPriceList[0].fd.ADULT.rT==0" class="red pr-1">Non-Refundable</span>
                                            <span v-else-if="tripInfos.totalPriceList[0].fd.ADULT.rT==1" class="green pr-1">Refundable</span>
                                            <span v-if="tripInfos.totalPriceList[0].fd.ADULT.rT==2" class="green">Partial Refundable</span>
                                            
                                        </p>
                                        <h5>
                                            CB:{{tripInfos.totalPriceList[0].fd.ADULT.cB??''}}
                                            <span v-if="getSeatLeft(tripInfos, tripInfos.totalPriceList[0].id) > 0" class="seat_info">Seats left:  <p :class="getSeatColor(tripInfos, tripInfos.totalPriceList[0].id)">{{getSeatLeft(tripInfos, tripInfos.totalPriceList[0].id)}}</p></span>
                                            <!-- <span v-if="tripInfos.totalPriceList[0].fd.ADULT.sR && store.websiteSettings && store.websiteSettings.FLIGHTS_FEW_SEATS_LEFT_ALERT && tripInfos.totalPriceList[0].fd.ADULT.sR < store.websiteSettings.FLIGHTS_FEW_SEATS_LEFT_ALERT" class="red">Few seats left</span> -->
                                        </h5>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="prc_chk_btm">
                        <span v-if="tripInfos.totalPriceList[0].fareIdentifier" :class="this.showFareTypeColor(tripInfos.totalPriceList[0].fareIdentifier)">{{this.showFareTypeUi(tripInfos.totalPriceList[0].fareIdentifier)}}</span>
                    </div>
                    <div class="extra-info" v-if="tripInfos.totalPriceList[0].fd.ADULT.bI">
                        <h3><i class="fa-solid fa-suitcase"></i> : (Adult) Check-in : {{tripInfos.totalPriceList[0].fd.ADULT.bI['iB']??''}}, Cabin : {{tripInfos.totalPriceList[0].fd.ADULT.bI['cB']??''}}</h3>
                    </div>
                    <div class="change-plane-box text-center pb-1 w-full">
                    <div class="change-plane" v-if="flightData && flightData.cT && flightData.cT > 0">
                        <span class="">Require to change Plane</span> - 
                        <span class="">Layover Time - {{timeConvert(flightData.cT)}}</span>
                    </div>
                </div>
                </element>
                </template>

                <button class="details_btn" @click="handleDetails" :class="buttonLoading ? 'loading_btn' : ''">
                    View Details 
                    <i class="fa-solid fa-plus" v-if="!this.viewDetails"></i>
                    <i class="fa-solid fa-minus" v-if="this.viewDetails"></i>
                </button>
                <div class="detailed_rules" v-if="!buttonLoading">
                    <SlideUpDown v-if="this.viewDetails" :duration="400">
                        <div class="detailed_riles_inner">
                            <div class="inr_wrap">
                                <button class="detailed_rules_btn">Detailed Rules</button>
                                <table v-if="this.tabData && this.tabData.tripInfos">
                                    <Airlinetab :tabData="this.tabData" :active="true" :key="this.tabData.tripInfos[0]['totalPriceList'][0]['id']" :showSingleBooking="true" :hideFlightTab="true" :paxInfo="this.paxInfo" />
                                </table>
                                <div v-else class="fw500">&nbsp; No Fare Rule Found. Please contact Customer Care!!!</div>
                            </div>
                        </div>
                    </SlideUpDown>
                </div>

            </div>

            <div class="fts_bottom">
                <Link class="stp_btn" @click="goback">Back</Link>
                <button class="stp_btn" @click="handleAddPassenger">Add Passenger</button>
            </div>
        </div>
    </div>
</template>
<script>
import {goToStep, DateFormat, timeConvert, getLogo, showCabinClass, showErrorToast, getSeatLeft, getSeatColor, showFareTypeColor, showFareTypeUi} from '../../../utils/commonFuntions.js';
import { store } from '../../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import Airlinetab from "../Airlinetab.vue";
import SlideUpDown from 'vue3-slide-up-down';
import axios from "axios";
export default {
    name:"Step1",
    created() {
        // console.log('FlightDetails.Step1.price_id=',this.price_id);
        store.loadingName = false;

        if(this.info.searchQuery && this.info.searchQuery.paxInfo) {
            this.paxInfo = this.info.searchQuery.paxInfo;
        }
    },
    data() {
        return {
            dataStep: 'Step 1',
            viewDetails : false,
            buttonLoading : false,
            info : this.info,
            price_id : this.price_id,
            tabData : [],
            store
        }
    },
    methods:{
        goToStep,
        DateFormat,
        timeConvert,
        getLogo,
        showCabinClass,
        showErrorToast,
        getSeatLeft,
        getSeatColor,
        showFareTypeColor,
        showFareTypeUi,
        async handleDetails(){
            this.viewDetails = !this.viewDetails;
            this.buttonLoading = true;
            if(this.viewDetails) {
                var priceIdsstr = this.price_id.join(',');
                const priceDetailResponse = await axios.post('/flight/getFareDetails', {
                    price_id: priceIdsstr
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
            }
            this.buttonLoading = false;
        },
        handleAddPassenger(){
            store.bookingPassedStep = 1
            goToStep(2);
        },
        goback(){
            store.loadingName = "searchForm";
            window.history.back();
        }
    },
    components:{
        Link,
        SlideUpDown,
        Airlinetab,
    },
    props: ["info","price_id"],
}
</script>