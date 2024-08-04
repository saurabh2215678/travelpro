<template>
    <flight-card-tab class="det_row">
        <td colspan="6">
            <SlideUpDown :duration="400">
                <div class="det_wrap">
                    <div class="det_btns">
                        <button :class="activeTab == 1 ? 'active' : ''" @click="handleActiveTab(1)" v-if="!hideFlightTab">Flight Details</button>
                        <button :class="activeTab == 2 ? 'active' : ''" @click="handleActiveTab(2)">Fare Details</button>
                        <button :class="activeTab == 3 ? 'active' : ''" @click="handleActiveTab(3)">Fare Rules</button>
                        <button :class="activeTab == 4 ? 'active' : ''" @click="handleActiveTab(4)">Baggage Information</button>
                        <button class="hideRow" @click="this.handleHideDetails"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="det_content_wrap" v-if="tabData?.tripInfos">
                        
                        <div class="det_content" v-if="activeTab == 1 && !hideFlightTab">
                            <template v-for="tripInfo,index in tabData?.tripInfos">
                                <element v-if="index==this.routeIndex || showSingleBooking">
                                    <div class="city_bx">
                                        <h4>
                                            <span>{{tripInfo.sI[0].da.city}}</span>
                                            <i class="fa-solid fa-arrow-right-long"></i>
                                            <span>{{tripInfo.sI[tripInfo.sI.length-1].aa.city}}</span>
                                        </h4>
                                        <span>{{DateFormat(tripInfo.sI[0].dt,'ddd, MMM Do YYYY')}}</span>
                                    </div>
    
                                    <div class="rts_flight">
    
                                        <table class="fls_tbl">
                                            <tbody>
                                                <element v-for="flightData in tripInfo.sI">
                                                <tr>
                                                    <td>
                                                      <div class="flight_detail">
                                                           <img :src="getLogo(flightData.fD.aI.code)" v-bind:alt="flightData.fD.aI.name">
                                                            <div class="fl-t">
                                                                <p>{{flightData.fD.aI.name}}</p>
                                                                <span>{{flightData.fD.aI.code}}-{{flightData.fD.fN}}</span>
                                                                <span><i class="fa-solid fa-plane"></i>{{flightData.fD.eT}}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="assd_content departure_content">
                                                            <h3>{{DateFormat(flightData.dt,'MMM D, ddd,  HH:mm')}}</h3>
                                                            {{flightData.da.city??''}}, {{flightData.da.country??''}}<br />{{flightData.da.name??''}}<element v-if="flightData.da.terminal"><br />{{flightData.da.terminal??''}}</element>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="mmd_sec">
                                                            <p v-if="flightData.stops == 0">Non-Stop</p>
                                                             <p v-else>{{flightData.stops}}-Stop</p>
                                                            <span class="mmd_arrow"></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="assd_content arrival_content">
                                                            <h3>{{DateFormat(flightData.at,'MMM D, ddd,  HH:mm')}}</h3>
                                                            {{flightData.aa.city??''}}, {{flightData.aa.country??''}}<br />{{flightData.aa.name??''}}<element v-if="flightData.aa.terminal"><br />{{flightData.aa.terminal??''}}</element>
                                                        </div>
                                                    </td>
                                                    <td>
                                                       <div class="assd_content">
                                                            <h3>{{timeConvert(flightData.duration)}}</h3>
                                                            <p v-if="tabData.searchQuery">{{this.showCabinClass(tabData.searchQuery.cabinClass)}}</p>    
                                                            <h5>
                                                                CB:{{getSeatCB(tripInfo.totalPriceList)}}
                                                                <span v-if="getSeatLeft(tripInfo, tripInfo.totalPriceList[0].id) > 0" class="seat_info">Seats left:  <p :class="getSeatColor(tripInfo, tripInfo.totalPriceList[0].id)">{{getSeatLeft(tripInfo, tripInfo.totalPriceList[0].id)}}</p></span>

                                                                <!-- <span v-if="store.websiteSettings && store.websiteSettings.FLIGHTS_FEW_SEATS_LEFT_ALERT && getSeatLeft(tripInfo.totalPriceList) < store.websiteSettings.FLIGHTS_FEW_SEATS_LEFT_ALERT">Few seats left</span> -->
                                                            </h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr v-if="flightData && flightData.cT && flightData.cT > 0">
                                                    <td colspan="5" align="center">
                                                        <div class="change-plane">
                                                            <span class="">Require to change Plane</span> - 
                                                            <span class="">Layover Time - {{timeConvert(flightData.cT)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </element>
                                            </tbody>
                                        </table>
                                    </div>
                                </element>
                            </template>
                        </div>

                        <div class="det_content" v-if="activeTab == 2">
                            <div class="fare_detail_bx">
                                <element v-for="(tripInfo, index) in tabData?.tripInfos">
                                <template v-if="index==this.routeIndex || showSingleBooking">
                                <h3 class="fitted_box">{{tripInfo.sI[0].da.code}}-{{tripInfo.sI[tripInfo.sI.length-1].aa.code}}</h3>
                                <table>
                                    <thead>
                                        <tr>
                                            <td>TYPE</td>
                                            <td>Fare</td>
                                            <td>Total</td>
                                        </tr>
                                    </thead>
                                    <tbody v-for="tripPrice in tripInfo.totalPriceList">
                                        <template v-for="paxType in store.paxInfo_arr">
                                        <template v-if="tabData.searchQuery.paxInfo[paxType.key] > 0">
                                        <tr>
                                            <td colspan="3"><span>Fare Details for {{paxType.title}} (CB: {{tripPrice.fd[`${paxType.key}`]['cB']}})</span></td>
                                        </tr>
                                        <tr v-if="tripPrice.fd[paxType.key]['fC']['BF']">
                                            <td class="whitespace-nowrap">Base Price</td>
                                            <td>{{showPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0))}}  x {{tabData.searchQuery.paxInfo[paxType.key]}}</td>
                                            <td>{{showPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0) * tabData.searchQuery.paxInfo[paxType.key])}}</td>
                                        </tr>
                                        <tr style="z-index: 5;" >
                                            <td class="whitespace-nowrap">Taxes and fees 
                                                <Popper hover arrow>
                                                    <i class="fa-solid fa-circle-question"></i>
                                                    <template #content>
                                                        <div class="tooltip_details">
                                                            <div class="flex" v-if="tripPrice.fd[paxType.key]['afC']['TAF']['AGST'] > 0"><span class="me-4" >Airline GST</span><span class="ms-auto">{{showPrice(tripPrice.fd[paxType.key]['afC']['TAF']['AGST']??0)}}</span></div>

                                                            <div class="flex" v-if="tripPrice.fd[paxType.key]['afC']['TAF']['MF'] > 0"><span class="me-4">Management Fee</span> <span class="ms-auto">{{showPrice(tripPrice.fd[paxType.key]['afC']['TAF']['MF']??0)}}</span></div>

                                                            <div class="flex" v-if="tripPrice.fd[paxType.key]['afC']['TAF']['MFT'] > 0"><span>Management Fee Tax</span> <span>{{showPrice(tripPrice.fd[paxType.key]['afC']['TAF']['MFT']??0)}}</span></div>

                                                            <div class="flex"><span class="me-4">Other Taxes</span> <span class="ms-auto">{{showPrice((tripPrice.fd[paxType.key]['afC']['TAF']['OT']??0)+getSupplierMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier))}}</span></div>

                                                            <div class="flex" v-if="tripPrice.fd[paxType.key]['afC']['TAF']['YQ'] > 0"><span class="me-4">Fuel Surcharge</span> <span class="ms-auto">{{showPrice(tripPrice.fd[paxType.key]['afC']['TAF']['YQ']??0)}}</span></div>
                                                            <div class="flex" v-if="tripPrice.fd[paxType.key]['afC']['TAF']['YR'] > 0"><span class="me-4">Carrier Misc Fee</span> <span class="ms-auto">{{showPrice(tripPrice.fd[paxType.key]['afC']['TAF']['YR']??0)}}</span></div>
                                                            <div class="flex" v-if="tripPrice.fd[paxType.key]['afC']['TAF']['MU'] > 0"><span class="me-4">MU</span> <span class="ms-auto">{{showPrice(tripPrice.fd[paxType.key]['afC']['TAF']['MU']??0)}}</span></div>

                                                            <div class="flex" v-if="getAgentMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier) > 0"><span class="me-4" >Markup</span><span class="ms-auto">{{showPrice(getAgentMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier))}}</span></div>

                                                            <div class="flex" v-if="getAgentDiscountPrice(getSupplierMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier),tabData.searchQuery,null,tripPrice.fareIdentifier) > 0"><span class="me-4" >Discount</span><span class="ms-auto">-{{showPrice(getAgentDiscountPrice(getSupplierMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier),tabData.searchQuery,null,tripPrice.fareIdentifier))}}</span></div>
                                                        </div>
                                                    </template>
                                                </Popper>
                                            </td>
                                            <td>{{showPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['TAF']??0)+getSupplierMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier)+getAgentMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier)-getAgentDiscountPrice(getSupplierMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier),tabData.searchQuery,null,tripPrice.fareIdentifier))}} x {{tabData.searchQuery.paxInfo[paxType.key]}}</td>
                                            <td>{{showPrice((parseFloat(tripPrice.fd[paxType.key]['fC']['TAF']??0)+getSupplierMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier)+getAgentMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier)-getAgentDiscountPrice(getSupplierMarkupPrice(parseFloat(tripPrice.fd[paxType.key]['fC']['BF']??0),tabData.searchQuery,null,tripPrice.fareIdentifier),tabData.searchQuery,null,tripPrice.fareIdentifier)) * tabData.searchQuery.paxInfo[paxType.key])}}</td>
                                        </tr>
                                        </template>
                                        </template>

                                        <tr class="total_row">
                                          <td colspan="2">Total</td>
                                          <td>{{showPrice(getInfoTotalPrice(tripInfo,tripInfo.totalPriceList[0].id, this.paxInfo))}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </template>
                            </element>
                            </div>
                        </div>

                        <div class="det_content" v-if="activeTab == 3">
                            <div class="dt-rl">
                                <button class="detail_rules">Detailed Rules</button>
                            </div>

                            <element v-if="tabData?.tripInfos"  v-for="(tripInfo, index) in tabData?.tripInfos">
                                <div v-if="index==this.routeIndex || showSingleBooking">
                                <h3 class="fitted_box">{{tripInfo.sI[0].da.code}}-{{tripInfo.sI[tripInfo.sI.length-1].aa.code}}</h3>

                                <div class="srt_rules"  v-if="tripInfo.totalPriceList[0].fareRuleInformation">
                                    <div class="star_txt">Mentioned fees are Per Pax Per Sector</div>
                                    <div class="star_txt">Apart from airline charges, GST + RAF + applicable charges if any, will be charged.</div>
                                </div>

                                <div class="str_tbl" v-if="tripInfo.totalPriceList[0].fareRuleInformation && tripInfo.totalPriceList[0].fareRuleInformation.fr">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Cancellation Fee</th>
                                                <th>Date Change Fee</th>
                                                <th>No Show</th>
                                                <th>Seat Chargeable</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    ALL
                                                </td>
                                                <td>
                                                    <template v-if="tripInfo.totalPriceList[0].fareRuleInformation.fr.CANCELLATION">
                                                        <template v-if="tripInfo.totalPriceList[0].fareRuleInformation.fr.CANCELLATION?.DEFAULT?.amount || tripInfo.totalPriceList[0].fareRuleInformation.fr.CANCELLATION?.DEFAULT?.additionalFee">
                                                        {{showPrice(tripInfo.totalPriceList[0].fareRuleInformation.fr.CANCELLATION.DEFAULT.amount ??'')}}  + {{showPrice(tripInfo.totalPriceList[0].fareRuleInformation.fr.CANCELLATION.DEFAULT.additionalFee ??'')}} <br/> 
                                                        </template>
                                                        {{sanitizeText(tripInfo.totalPriceList[0].fareRuleInformation.fr.CANCELLATION.DEFAULT.policyInfo??'')}}
                                                   </template>

                                                    <template v-else-if="tripInfo.totalPriceList[0].fareRuleInformation.tfr.CANCELLATION">
                                                        {{showPrice(tripInfo.totalPriceList[0].fareRuleInformation.tfr.CANCELLATION[0].amount ??'')}} + {{showPrice(tripInfo.totalPriceList[0].fareRuleInformation.tfr.CANCELLATION[0].additionalFee ??'')}} <br/>
                                                        {{sanitizeText(tripInfo.totalPriceList[0].fareRuleInformation.tfr.CANCELLATION[0].policyInfo??'')}}                    
                                                   </template>
                                                </td>
                                                <td>
                                                    <template v-if="tripInfo.totalPriceList[0].fareRuleInformation.fr.DATECHANGE">
                                                        <template v-if="tripInfo.totalPriceList[0].fareRuleInformation.fr.DATECHANGE?.DEFAULT?.amount || tripInfo.totalPriceList[0].fareRuleInformation.fr.DATECHANGE?.DEFAULT?.additionalFee">
                                                            {{showPrice(tripInfo.totalPriceList[0].fareRuleInformation.fr.DATECHANGE.DEFAULT.amount ??'')}} + {{showPrice(tripInfo.totalPriceList[0].fareRuleInformation.fr.DATECHANGE.DEFAULT.additionalFee ??'')}} <br/>
                                                        </template>
                                                        {{sanitizeText(tripInfo.totalPriceList[0].fareRuleInformation.fr.DATECHANGE.DEFAULT.policyInfo??'')}}
                                                     </template>
                                                     <template v-else-if="tripInfo.totalPriceList[0].fareRuleInformation.tfr.DATECHANGE">
                                                        {{showPrice(tripInfo.totalPriceList[0].fareRuleInformation.tfr.DATECHANGE[0].amount??'')}} + {{showPrice(tripInfo.totalPriceList[0].fareRuleInformation.tfr.DATECHANGE[0].additionalFee??'')}} <br/>

                                                        {{sanitizeText(tripInfo.totalPriceList[0].fareRuleInformation.tfr.DATECHANGE[0].policyInfo??'')}}
                                                     </template>
                                                </td>
                                                <td>
                                                    <template v-if="tripInfo.totalPriceList[0].fareRuleInformation.fr.NO_SHOW">
                                                        {{sanitizeText(tripInfo.totalPriceList[0].fareRuleInformation.fr.NO_SHOW.DEFAULT.policyInfo??'')}}
                                                    </template>

                                                     <template v-else-if="tripInfo.totalPriceList[0].fareRuleInformation.tfr.NO_SHOW">
                                                        {{sanitizeText(tripInfo.totalPriceList[0].fareRuleInformation.tfr.NO_SHOW[0].policyInfo??'')}}
                                                    </template>
                                                </td>
                                                <td>
                                                    <template v-if="tripInfo.totalPriceList[0].fareRuleInformation.fr.SEAT_CHARGEABLE">
                                                        {{sanitizeText(tripInfo.totalPriceList[0].fareRuleInformation.fr.SEAT_CHARGEABLE.DEFAULT.policyInfo ??'')}}
                                                    </template>

                                                    <template v-else-if="tripInfo.totalPriceList[0].fareRuleInformation.tfr.SEAT_CHARGEABLE">
                                                        {{sanitizeText(tripInfo.totalPriceList[0].fareRuleInformation.tfr.SEAT_CHARGEABLE[0].policyInfo??'')}}
                                                    </template>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div v-else>No Fare Rule Found. Please contact Customer Care!!!</div>

                                </div>
                            </element>
                        </div>

                        <div class="det_content" v-if="activeTab == 4">
                            <element v-for="(tripInfo, index) in tabData?.tripInfos">
                                <table v-if="index==this.routeIndex || showSingleBooking" class="baggage-info_table">
                                    <thead>
                                        <tr>
                                            <th>Sector</th>
                                            <th>CheckIn</th>
                                            <th>Cabin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="flightData in tripInfo.sI">
                                            <td>{{flightData.da.code}}-{{flightData.aa.code}}</td>
                                            <td class="checkin">
                                                <template v-for="paxType in store.paxInfo_arr">
                                                    <template v-if="tabData.searchQuery.paxInfo[paxType.key] > 0">
                                                        <span v-if="tripInfo.totalPriceList[0].fd[paxType.key] && tripInfo.totalPriceList[0].fd[paxType.key]['bI']['iB']">  {{paxType.title}}: {{tripInfo.totalPriceList[0].fd[paxType.key]['bI']['iB']??''}}</span>
                                                    </template>
                                                </template>
                                            </td>
                                            <td class="cabin_data">
                                                <template v-for="paxType in store.paxInfo_arr">
                                                    <template v-if="tabData.searchQuery.paxInfo[paxType.key] > 0">
                                                        <span v-if="tripInfo.totalPriceList[0].fd[paxType.key] && tripInfo.totalPriceList[0].fd[paxType.key]['bI']['cB']">  {{paxType.title}}: {{tripInfo.totalPriceList[0].fd[paxType.key]['bI']['cB']??''}}</span>
                                                    </template>
                                                </template>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </element>
                        </div>
                    </div>
                </div>
            </SlideUpDown>
        </td>
    </flight-card-tab>
</template>
<script>
import SlideUpDown from 'vue3-slide-up-down';
import { store } from '../../store';
//import FareDetailCard from "./components/FlightCard.vue";
import { getLogo, DateFormat, timeConvert, showCabinClass, showPrice, getInfoTotalPrice, getSeatLeft, getSeatColor, getSupplierMarkupPrice, getAgentMarkupPrice, getAgentDiscountPrice } from '../../utils/commonFuntions.js';
import axios from "axios";
import Popper from "vue3-popper";
import styled from 'vue3-styled-components';

const flightCardTab = styled.tr`
.flight_table & td {
        border:none;
        background-color: #0000;
    }
    .flight_table & td .det_wrap .det_btns button.hideRow{
        color:initial;
    }


`;

    export default {
        name:"Airlinetab",
        created() {
        },
        mounted () {
            if(this.hideFlightTab) {
                this.activeTab = 2;
            }
        },
        data() {
            return {
                type: 'hello',
                activeTab: 1,
                adult: this.adult,
                routeIndex: this.routeIndex,
                store
            }
        },
        methods:{
            getLogo,
            DateFormat,
            timeConvert,
            showCabinClass,
            showPrice,
            getInfoTotalPrice,
            getSeatLeft,
            getSeatColor,
            getSupplierMarkupPrice,
            getAgentMarkupPrice,
            getAgentDiscountPrice,
            handleActiveTab(tabId){
                this.activeTab = tabId;
            },
            getSeatCB:function(totalPriceList) {
                let cB = "";
                totalPriceList.forEach((priceList, index) => {
                    if(priceList.fd.ADULT.cB) {
                        cB = priceList.fd.ADULT.cB;
                    }
                });
                return cB;
            },
            getEconomy(priceDetails) {
                let totalTime = 0;
                priceDetails.forEach((value, index) => {
                    //totalTime = totalTime + value.duration;
                    //console.log("ANMOL VAL=",value);
                });
            },
            sanitizeText(text) {
                if(text) {
                    text = text.replace(/\__nls__/g,"\n");
                    text = text.replace(/\__be__/g,"\n");
                }
                return text;
            },
        },
        props: ["tabData", "active", "adult", "showSingleBooking", "routeIndex", "hideFlightTab", "handleHideDetails", "paxInfo"],
        components: {
            SlideUpDown,
            Popper,
            'flight-card-tab' : flightCardTab
        }
    }
</script>