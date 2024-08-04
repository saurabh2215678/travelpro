<template>
    <div class="stp_wrap">
        <div class="stp_tp_head">
            <h3>Review</h3>
        </div>
        <div class="rts_flight">
            <div class="fts_top" v-for="(tripInfos, key) in info.tripInfos">
                <element v-for="flightData in tripInfos.sI">
                    <div class="minimal_detail_box">
                        <div class="minimal_left">
                            <span>{{flightData.da.city??''}}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                            <span>{{flightData.aa.city??''}}</span>
                            <p>on {{DateFormat(flightData.dt,'ddd, MMM Do YYYY')}}</p>
                        </div>
                        <div class="minimal-right">
                            <span><i class="fa-regular fa-clock"></i>{{timeConvert(flightData.duration)}}</span>
                        </div>
                    </div>
                    <table class="fls_tbl">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="flight_detail">
                                        <img :src="getLogo(flightData.fD.aI.code)" v-bind:alt="flightData.fD.aI.name">
                                        <div class="fl-t">
                                            <span>{{flightData.fD.aI.code}}-{{flightData.fD.fN}}</span>
                                            <span><i class="fa-solid fa-plane"></i>{{flightData.fD.eT??''}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="assd_content departure_content">
                                        <h3>{{DateFormat(flightData.dt,'MMM D, ddd, H:m')}}</h3>
                                        <p>
                                        {{flightData.da.city??''}}, {{flightData.da.country??''}}, {{flightData.da.name??''}}<element v-if="flightData.da.terminal">, {{flightData.da.terminal??''}}</element>
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
                                        <h3>{{DateFormat(flightData.at,'MMM D, ddd, H:m')}}</h3>
                                        <p>
                                        {{flightData.aa.city??''}}, {{flightData.aa.country??''}}, {{flightData.aa.name??''}}<element v-if="flightData.aa.terminal">, {{flightData.aa.terminal??''}}</element>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="assd_content">
                                        <h3>{{timeConvert(flightData.duration)}}</h3>
                                        <p>{{tripInfos.totalPriceList[0].fd.ADULT.cc??''}}</p>
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
                    <div class="extra-info">
                        <h3><i class="fa-solid fa-suitcase"></i> : (Adult) Check-in : {{tripInfos.totalPriceList[0].fd.ADULT.bI['iB']??''}}, Cabin : {{tripInfos.totalPriceList[0].fd.ADULT.bI['cB']??''}}</h3>
                    </div>
                    <div class="change-plane" v-if="flightData && flightData.cT && flightData.cT > 0">
                        <span class="">Require to change Plane</span> - 
                        <span class="">Layover Time - {{timeConvert(flightData.cT)}}</span>
                    </div>
                </element>                
            </div>

            <div class="passenger_dtl">
                <h4 class="font19 fw600 px-4 py-2 mb-2 mt-4 bg-slate-100">Passenger Details ({{info.searchQuery.paxInfo.ADULT+info.searchQuery.paxInfo.CHILD+info.searchQuery.paxInfo.INFANT}})</h4>
                <table class="passenger_dtl_table">
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Name, Age & Passport</th>
                            <th>Seat Booking</th>
                            <th>Meal & Baggage Preference</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="paxType in store.paxInfo_arr">
                            <template v-if="info.searchQuery.paxInfo[paxType.key] > 0" v-for="n in info.searchQuery.paxInfo[paxType.key]">
                                <tr>
                                    <td>
                                        <template v-if="paxType.key=='ADULT'">{{n}}</template>
                                        <template v-else-if="paxType.key=='CHILD'">{{info.searchQuery.paxInfo['ADULT']+n}}</template>
                                        <template v-else-if="paxType.key=='INFANT'">{{info.searchQuery.paxInfo['ADULT']+info.searchQuery.paxInfo['CHILD']+n}}</template>
                                    </td>
                                    <td>{{store.passengers[`${paxType.key}${n}_title`]}} {{store.passengers[`${paxType.key}${n}_first_name`]}} {{store.passengers[`${paxType.key}${n}_last_name`]}} ({{this.showPaxTypeLabel(paxType.key)}})</td>
                                    <td>NA</td>
                                    <td>
                                        <template v-if="this.checkHasSsrData(paxType.key,n,'baggage')">
                                            Excess Baggage:
                                            <template v-for="tripInfo in info.tripInfos">
                                                <template v-for="flightData in tripInfo.sI">
                                                    {{flightData.da.code}}-{{flightData.aa.code}}
                                                    <span v-if="store.passengers[`${paxType.key}${n}_baggage_${flightData.da.code}_${flightData.aa.code}`]">{{this.airBaggageDesc(info, store.passengers[`${paxType.key}${n}_baggage_${flightData.da.code}_${flightData.aa.code}`])??'NA'}}</span>
                                                </template>
                                            </template>
                                        </template>
                                        <template v-if="this.checkHasSsrData(paxType.key,n,'meal')">
                                            Meal: 
                                            <template v-for="tripInfo in info.tripInfos">
                                                <template v-for="flightData in tripInfo.sI">
                                                    {{flightData.da.code}}-{{flightData.aa.code}}:
                                                    <span v-if="store.passengers[`${paxType.key}${n}_meal_${flightData.da.code}_${flightData.aa.code}`]">{{this.airMealDesc(info, store.passengers[`${paxType.key}${n}_meal_${flightData.da.code}_${flightData.aa.code}`])??''}}</span>
                                                </template>
                                            </template>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="passenger_dtl">
                <h4 class="font19 fw600 px-4 py-2 mb-2 mt-4 bg-slate-100">Contact Details</h4>
                <ul>
                    <li v-if="store.contact_mobile"><span>Mobile Number</span> : {{store.contact_country_code}}{{store.contact_mobile}}</li>
                    <li v-if="store.contact_email"><span>Email ID</span> : {{store.contact_email}}</li>
                </ul>
            </div>

            <div class="fts_bottom" v-if="this.processing">
                <button class="btn" :disabled="true" >Processing...</button>
            </div>
            <div class="fts_bottom" v-else>
                <button class="stp_btn" @click="goToStep(2)">Back</button>
                <div class="flex">
                <button v-if="this.isBA == 1" class="stp_btn block_btn" @click="handleProceedToBlock">Block</button>
                <button class="stp_btn" @click="handleProceedToPay">Proceed To Pay</button>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
import {goToStep, DateFormat, timeConvert, getLogo, airBaggageDesc, airMealDesc, getSeatLeft, getSeatColor, showFareTypeColor, showFareTypeUi, showErrorToast} from '../../../utils/commonFuntions.js';
import { store } from '../../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import SlideUpDown from 'vue3-slide-up-down';
import axios from "axios";
export default {
    name:"Step3",
    created() {
        // console.log('store.passengers');
        // console.log(this.store);
        var isBA = 0;
        if(this.info && this.info.conditions && this.info.conditions.isBA == 1) {
            if(store.userInfo && store.userInfo.is_agent == 1) {
                isBA = 1;
            }
        }
        this.isBA = isBA;
    },
    data() {
        return {
            dataStep: 'Step 3',
            store,
            info : this.info,
            processing : false,
            isBA : 0,
        }
    },
    methods:{
        goToStep,
        DateFormat,
        timeConvert,
        getLogo,
        airBaggageDesc,
        airMealDesc,
        getSeatLeft,
        getSeatColor,
        showFareTypeColor,
        showFareTypeUi,
        showErrorToast,
        handleProceedToBlock(){
            var currentObj = this;
            currentObj.processing = true;
            currentObj.errors = {};
            name = store.passengers.ADULT1_first_name+' '+store.passengers.ADULT1_last_name;
            axios.post('/booking', {
                action: 'flight_booking',
                payment_method: 'hold',
                price_id: this.price_id,
                paxInfo: this.info.searchQuery.paxInfo,
                passengers: store.passengers,
                name: name,
                country_code: store.contact_country_code,
                phone: store.contact_mobile,
                email: store.contact_email,
                gst_number: store.gst_number,
                gst_company: store.gst_company,
                gst_email: store.gst_email,
                gst_phone: store.gst_phone,
                gst_address: store.gst_address,
            })  
            .then(function (response) {  
                // currentObj.output = response.data;
                if(response.data.success) {
                    window.location.href = response.data.redirect_url;
                } else {
                    currentObj.showErrorToast(response.data.message,10);
                    currentObj.processing = false;
                }
            })
            .catch(function (error) {
                if(error.response.data.errors) {
                    currentObj.errors = error.response.data.errors;
                    setTimeout(() => {
                        setTimeout(() => {
                            currentObj.$refs.flightRef.scrollIntoView();
                        }, 1);
                    }, 1);
                    currentObj.processing = false;

                    if(error.response.data.errors.tripInfos) {
                        currentObj.showErrorToast(currentObj.errors.tripInfos[0]);
                    }

                    error.response.data.errors.forEach((error,index) => {
                        currentObj.showErrorToast(error['message']);
                    });
                }

                if(error.response.data.message) {
                    currentObj.showErrorToast(error.response.data.message,10);
                }
            });
        },
        handleProceedToPay(){
            if(store.bookingPassedStep < 3){
                store.bookingPassedStep = 3
            }
            goToStep(4)   
        },
        checkHasSsrData(paxType,n,ssrType='baggage') {
            let hasSsrData = false;
            this.info.tripInfos.forEach((tripInfo)=>{
                tripInfo.sI.forEach((flightData)=>{
                    if(store.passengers[`${paxType}${n}_${ssrType}_${flightData.da.code}_${flightData.aa.code}`]) {
                        hasSsrData = true;
                    }
                });
            });
            return hasSsrData;
        },
        showPaxTypeLabel(paxType) {
            let paxTypeLabel = paxType
            if(paxType == 'ADULT') {
                paxTypeLabel = 'A';
            } else if(paxType == 'CHILD') {
                paxTypeLabel = 'C';
            }else if(paxType == 'INFANT') {
                paxTypeLabel = 'I';
            }
            return paxTypeLabel;
        }
    },
    components:{
        Link,
        SlideUpDown,
    },
    watch:{
        store: {
            handler: function(store) {
                // console.log('FareSummary.store=',store);
            },
            deep: true
        },
    },
    props: ["info", "price_id"],
}
</script>