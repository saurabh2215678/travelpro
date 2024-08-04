<template>
    <div class="right-box">
        <h3>FARE SUMMARY</h3>
        <ul class="fare_details">
            <li><div class="detail-item"><span>Base fare</span><span>{{showPrice(info.totalPriceInfo.totalFareDetail.fC.BF??0)}}</span></div></li>
            <li>
                <div class="detail-item">
                    <span class="base_btn" :class="taxSlide ? 'active' : ''" @click="taxSlide = !taxSlide">Taxes and fees <i class="fa-solid fa-chevron-down"></i></span>
                    <span>{{showPrice(info.totalPriceInfo.totalFareDetail.fC.TAF??0)}} <i class="fa-regular fa-pen-to-square"></i></span>
                </div>
                <div class="detail-list">
                    <SlideUpDown v-model="taxSlide" :duration="400">
                        <ul>
                            <li v-if="info.totalPriceInfo.totalFareDetail.afC.TAF.AGST > 0"><span>Airline GST</span> <span>{{showPrice(info.totalPriceInfo.totalFareDetail.afC.TAF.AGST??0)}}</span></li>
                            <li v-if="info.totalPriceInfo.totalFareDetail.afC.TAF.MF > 0"><span>Management Fee</span> <span>{{showPrice(info.totalPriceInfo.totalFareDetail.afC.TAF.MF??0)}}</span></li>
                            <li v-if="info.totalPriceInfo.totalFareDetail.afC.TAF.MFT > 0"><span>Management Fee Tax</span> <span>{{showPrice(info.totalPriceInfo.totalFareDetail.afC.TAF.MFT??0)}}</span></li>
                            <li v-if="info.totalPriceInfo.totalFareDetail.afC.TAF.OT > 0"><span>Other Taxes</span> <span>{{showPrice(info.totalPriceInfo.totalFareDetail.afC.TAF.OT??0)}}</span></li>
                            <li v-if="info.totalPriceInfo.totalFareDetail.afC.TAF.YQ > 0"><span>Fuel Surcharge</span> <span>{{showPrice(info.totalPriceInfo.totalFareDetail.afC.TAF.YQ??0)}}</span></li>
                            <li v-if="info.totalPriceInfo.totalFareDetail.afC.TAF.YR > 0"><span>Carrier Misc Fee</span> <span>{{showPrice(info.totalPriceInfo.totalFareDetail.afC.TAF.YR??0)}}</span></li>
                            <li v-if="info.totalPriceInfo.totalFareDetail.afC.TAF.MU > 0"><span>MU</span> <span>{{showPrice(info.totalPriceInfo.totalFareDetail.afC.TAF.MU??0)}}</span></li>
                        </ul>
                    </SlideUpDown>
                </div>
            </li>
            <li v-if="this.showSsrBaggage || this.showSsrPrice">
                <div class="detail-item">
                    <span class="base_btn" :class="ssrSlide ? 'active' : ''" @click="ssrSlide = !ssrSlide">Meal, Baggage & Seat <i class="fa-solid fa-chevron-down"></i></span>
                    <span>{{showPrice(this.totalSsrPrice)}} <i class="fa-regular fa-pen-to-square"></i></span>
                </div>
                <div class="detail-list">
                    <SlideUpDown v-model="ssrSlide" :duration="400">
                        <ul>
                            <li v-if="this.showSsrBaggage"><span>Baggage</span> <span>{{showPrice(this.totalSsrBaggage)}}</span></li>
                            <li v-if="this.showSsrPrice"><span>Meal</span> <span>{{showPrice(this.totalSsrMeal)}}</span></li>
                        </ul>
                    </SlideUpDown>
                </div>
            </li>
            <li>
                <div class="detail-item">
                    <span class="base_btn" :class="amountSlide ? 'active' : ''" @click="amountSlide = !amountSlide">Amount to Pay <i class="fa-solid"></i></span>
                    <span>{{showPrice(this.totalPrice)}}</span>
                </div>
                <div class="detail-list">
                    <SlideUpDown v-model="amountSlide" :duration="400">
                        <!-- <i class="fa-solid fa-chevron-down">
                        <ul>
                            <li><span>Commission</span> <span>-{{showPrice(0)}}</span></li>
                            <li><span>Markup</span> <span>{{showPrice(15)}}</span></li>
                            <li><span>TDS</span> <span>+{{showPrice(0)}}</span></li>
                            <li><span>Net Price</span> <span>{{showPrice(1605.70)}}</span></li>
                        </ul> -->
                    </SlideUpDown>
                </div>
            </li>
        </ul>
        <!-- <form class="apply-coupon">
            <div class="coin-coupon-start">
                <h4>TJ Coins:</h4>
                <p>10 Coins = {{showPrice(1)}}</p>
            </div>
            <div class="coin-coupon-center">
                <input placeholder="Enter Coins"/>
                <label>Enter Coins</label>
                <button type="reset" class="clear-coin"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="coin-coupon-end">
                <button class="btn" type="submit">REDEEM</button>
            </div>
        </form> -->
    </div>
</template>
<script>
import {showPrice, airBaggageDesc, airMealDesc} from '../../utils/commonFuntions.js';
import { store } from '../../store.js';
import SlideUpDown from 'vue3-slide-up-down';
export default {
    name:"FareSummary",
    created() {
        if(this.info.totalPriceInfo && this.info.totalPriceInfo.totalFareDetail.fC.TF) {
            this.totalPrice = this.info.totalPriceInfo.totalFareDetail.fC.TF;
        }
    },
    data() {
        return {
            fareData: 'all fares',
            taxSlide: false,
            ssrSlide: false,
            amountSlide: false,
            info : this.info,
            totalPrice : 0,
            totalSsrPrice : 0,
            totalSsrBaggage : 0,
            totalSsrMeal : 0,
            showSsrBaggage : false,
            showSsrPrice : false,
            store,
        }
    },
    methods: {
        showPrice,
        airBaggageDesc,
        airMealDesc,
    },
    components:{
        SlideUpDown,
    },
    watch:{
        store: {
            handler: function(store) {
                // console.log('FareSummary.store=',store);
                if(this.info.searchQuery) {
                    // console.log('FareSummary.this.info.searchQuery=',this.info.searchQuery);
                    // console.log('FareSummary.store.passengers=',store.passengers);

                    if(this.info.tripInfos) {
                        let totalSsrBaggage = 0;
                        let totalSsrMeal = 0;
                        let showSsrBaggage = false;
                        let showSsrPrice = false;
                        this.info.tripInfos.forEach((tripInfo)=>{
                            tripInfo.sI.forEach((flightData)=>{
                                store.paxInfo_arr.forEach((paxType)=>{
                                    if(this.info.searchQuery.paxInfo[paxType.key] && this.info.searchQuery.paxInfo[paxType.key] > 0) {
                                        for (var n = 1; n <= this.info.searchQuery.paxInfo[paxType.key]; n++) {
                                            if(store.passengers[`${paxType.key}${n}_baggage_${flightData.da.code}_${flightData.aa.code}`]) {
                                                showSsrBaggage = true;
                                                let price = this.airBaggageDesc(this.info, store.passengers[`ADULT${n}_baggage_${flightData.da.code}_${flightData.aa.code}`],'amount');
                                                if(price) {
                                                    totalSsrBaggage = totalSsrBaggage + price;
                                                }
                                            }
                                            if(store.passengers[`${paxType.key}${n}_meal_${flightData.da.code}_${flightData.aa.code}`]) {
                                                showSsrPrice = true;
                                                let price  = this.airMealDesc(this.info, store.passengers[`ADULT${n}_meal_${flightData.da.code}_${flightData.aa.code}`],'amount');
                                                if(price) {
                                                    totalSsrMeal = totalSsrMeal + price;
                                                }
                                            }
                                        }
                                    }
                                });                        
                            });
                        });
                        // console.log(totalSsrBaggage);
                        this.totalSsrBaggage = totalSsrBaggage;
                        this.totalSsrMeal = totalSsrMeal;
                        this.totalSsrPrice = totalSsrBaggage+totalSsrMeal;
                        this.totalPrice = this.info.totalPriceInfo.totalFareDetail.fC.TF + this.totalSsrPrice;
                        this.showSsrBaggage = showSsrBaggage;
                        this.showSsrPrice = showSsrPrice;
                    }
                }
            },
            deep: true
        },
    },
    props: ["info"],
}
</script>