<template>
    <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <div class="xl:container xl:mx-auto flex cab_d">
            <div class="step_forms">
                <div class="stp_wrap">
                    <div class="stp_tp_head">
                        <h3>Contact & Pickup Details</h3>
                        <Link @click="goback" >Back to Search</Link>
                    </div>
                    <PickupForm :goBack="goback" :routeInfo="routeInfo" :countryData="countryData" :tripType="tripType" />
                </div>
            </div>
            <div class="fare-summary">
                <div class="right-box">

                  <!--   <div class="right_card">
                        <h3>OUR PROMISE OF QUALITY</h3>
                        <div class="flex">
                            <div class="ss-img_box">
                                <img class="img_ss" src="./assets/images/ssimg1.png" alt="">
                                <img class="img_ss_hover" src="./assets/images/ssimg11.png" alt="">
                            </div>
                            <div class="ss-img_box">
                                <img class="img_ss" src="./assets/images/ssimg2.png" alt="">
                                <img class="img_ss_hover" src="./assets/images/ssimg22.png" alt="">
                            </div>
                            <div class="ss-img_box">
                                <img class="img_ss" src="./assets/images/ssimg3.png" alt="">
                                <img class="img_ss_hover" src="./assets/images/ssimg33.png" alt="">
                            </div>
                        </div>
                    </div> -->

                    <div class="right_card">
                        <h3>Your Booking Details</h3>
                        <div class="booking_details">
                        <!-- <div class="ss-img_box">
                            <img class="img_car" src="./assets/images/giphy.gif" alt="">
                        </div> -->
                            <table class="table table-striped right_booking_details">
                                <tr>
                                    <th>Itinerary</th>
                                    <td>:</td>
                                    <template v-if="tripType ==2">
                                        <td>{{this.routeInfo.fromCity.name}} <i class="fa-solid fa-arrow-right-long" style="color: #00968880;"></i> {{this.routeInfo.sightseening.name}}</td>
                                    </template>
                                    <template v-else >
                                        <td>{{this.routeInfo.fromCity.name}} <i class="fa-solid fa-arrow-right-long" style="color: #00968880;"></i> {{this.routeInfo.toCity.name}} </td>
                                    </template>
                                </tr>
                                <tr>
                                    <th>Pickup Date</th>
                                    <td>:</td>
                                    <td> {{DateFormat(this.routeInfo.travelDate,'ddd, MMM Do YYYY')}} {{showTimeTitle(this.routeInfo.travelTime)}}</td>
                                </tr>
                                <tr>
                                    <th>Car Type</th>
                                    <td>:</td>
                                    <td> {{this.routeInfo.car_type}} </td>
                                </tr>
                                <tr>
                                    <th>Trip Type</th>
                                    <td>:</td>
                                    <td>{{this.routeInfo.tripType}}</td>
                                </tr> 
                                <tr v-if="this.routeInfo.agent_discount > 0">
                                    <th>Sub-Total</th>
                                    <td>:</td>
                                    <td>{{showPrice(this.routeInfo.sub_price)}}</td>
                                </tr>
                               <tr v-if="this.routeInfo.agent_discount > 0">
                                    <th>Discount</th>
                                    <td>:</td>
                                    <td>{{showPrice(this.routeInfo.agent_discount)}}</td>
                                </tr>
                                <tr>
                                    <th>Total Fare</th>
                                    <td>:</td>
                                    <td>{{showPrice(this.routeInfo.price)}}</td>
                                </tr>
                                <tr v-if="coupons && coupons.length > 0">
                                    <td colspan="3">
                                        <div class="offers_code">
                                            <template v-for="coupon,index in coupons">
                                                    <h4 v-if="index==0" class="text-sm font-semibold pt-2" style=" color: #4CAF50;">{{store.websiteSettings?.SYSTEM_TITLE}} Offers</h4>
                                                    <div class="offerlist">
                                                        <label class="flex">
                                                            <input type="radio" id="coupon_id" name="fav_offer" :value="coupon.id" @change="handleCouponChange($event)">
                                                            <div class="w-full pl-2">
                                                                <div class="flex flex-row justify-between">
                                                                    <span class="text-sm">{{coupon.code??''}}</span>
                                                                    <span class="text-sm">- {{showPrice(coupon.discount_coupon)}}</span>
                                                                </div>
                                                                <div class="text-xs">
                                                                    Congratulations! You have saved {{showPrice(coupon.discount_coupon)}} with {{coupon.code??''}}.
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <template v-if="this.routeInfo.inclusion != '' ||this.routeInfo.exclusion != '' || this.routeInfo.term != ''">
                    <div class="inclusions_tab tab">
                        <template v-if="this.routeInfo.inclusion">
                            <button class="tablinks" :class="this.tabId == 1 ? 'active' : ''" @click="this.tabId = 1">Inclusions</button>
                        </template>
                        <template v-if="this.routeInfo.inclusion">
                            <button class="tablinks" :class="this.tabId == 2 ? 'active' : ''" @click="this.tabId = 2">Exclusions</button>
                        </template>
                        <template v-if="this.routeInfo.inclusion">
                            <button class="tablinks" :class="this.tabId == 3 ? 'active' : ''" @click="this.tabId = 3">T&C</button>
                        </template>
                    </div>
                       
                    <div class="inclusions_txt" v-if="this.tabId == 1">
                        <p v-html="this.routeInfo.inclusion"> </p>
                    </div>

                    <div class="inclusions_txt"  v-if="this.tabId == 2">
                        <p v-html="this.routeInfo.exclusion">  </p> 
                    </div>

                    <div class="inclusions_txt"  v-if="this.tabId == 3">
                        <p v-html="this.routeInfo.term"> </p>
                    </div>

                     </template>

                </div>
            </div>
        </div>
    </Layout>
</template>
<style>
.headerType2 .topmain-header {
    background: linear-gradient(to bottom, #3c3a9a, #07c0a9);
}
</style>
<script>

import { store } from '../store';
import Layout from '../components/layout.vue';
import { Link } from "@inertiajs/inertia-vue3";
import PickupForm from '../components/cab/pickupForm.vue';
import {DateFormat,showTimeTitle,showPrice} from '../utils/commonFuntions.js';

export default {
    created() {
        document.getElementsByTagName('body')[0].classList.add('cab-book')
        document.body.classList.add('headerType2');
        store.fav_offer = 0;
    },
    data() {
        return {
            test : "test",
            store,
            routeInfo: this.routeInfo,
            tabId : 1
        }
    },
    methods: {
        DateFormat,
        showTimeTitle,
        showPrice,
        goback(){
            store.loadingName = "searchForm";
            window.history.back();
        },
        async handleCouponChange(e) {
            var name = e.target.name;
            var value = e.target.value;
            store.fav_offer = value;
        },
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
    Layout,
    Link,
    PickupForm
},
    props: ["routeInfo", "countryData", "tripType", "metaTitle", "metaDescription", "coupons"],
};
  
</script>