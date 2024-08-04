<template>
    <div class="stp_wrap">
        <div class="stp_tp_head">
            <h3>Payments</h3>
        </div>
        <div class="rts_flight rts_payment">
            <div class="left_tab_options">
                <template v-if="store.userInfo && store.userInfo.balance && store.userInfo.balance >= this.totalPrice">
                    <button :class="activeTab == 'wallet' ? 'active' : ''" @click="handleTab('wallet')">Use Wallet Amount</button>
                </template>

                <template v-for="payment_gateway in payment_gateways">
                    <button :class="activeTab == payment_gateway.value ? 'active' : ''" @click="handleTab(`${payment_gateway.value}`)">{{payment_gateway.display_name??''}}</button>
                </template>
                <!-- <button :class="activeTab == 1 ? 'active' : ''" @click="handleTab(1)">Deposit</button>
                <button :class="activeTab == 2 ? 'active' : ''" @click="handleTab(2)">Net-banking / Credit Card/ Debit Card</button> -->
            </div>
            <div class="left_tab_content">
                <template v-if="store.userInfo && store.userInfo.balance && store.userInfo.balance >= this.totalPrice">
                    <div class="diposit_content" v-if="activeTab == 'wallet'">
                        <div class="payment-note">Wallet Balance: {{showPrice(store.userInfo.balance)}}</div>
                        <button class="btn" :disabled="true" v-if="this.processing">Processing...</button>
                        <button class="btn" :disabled="'wallet' != termsChecked" @click="handlePayment(`wallet`)" v-else>Pay Now {{showPrice(this.totalPrice)}}</button>
                        <div class="agree_line">
                            <label>
                                <input type="checkbox" class="input_checkbox" @change="handleTermsChange" :checked="'wallet' == termsChecked" :value="`wallet`" />
                                By proceeding to book, I Agree to {{store.websiteSettings.APP_NAME}} <Link :href="store.websiteSettings?.PRIVACY_LINK" target="_blank">Privacy Policy</Link> and <Link :href="store.websiteSettings?.TERMS_SERVICE_LINK" target="_blank">Terms of Service</Link>
                            </label>
                        </div>
                    </div>
                </template>

                <template v-for="payment_gateway in payment_gateways">
                    <div class="diposit_content" v-if="activeTab == payment_gateway.value">
                        <div class="payment-note"><i class="fa-regular fa-credit-card"></i>{{payment_gateway.details??''}}</div>
                        <button class="btn" :disabled="true" v-if="this.processing">Processing...</button>
                        <button class="btn" :disabled="payment_gateway.value != termsChecked" @click="handlePayment(`${payment_gateway.value}`)" v-else>Pay Now {{showPrice(this.totalPrice)}}</button>
                        <div class="agree_line">
                            <label>
                                <input type="checkbox" class="input_checkbox" @change="handleTermsChange" :checked="payment_gateway.value == termsChecked" :value="payment_gateway.value" />
                                By proceeding to book, I Agree to {{store.websiteSettings.APP_NAME}} <Link :href="store.websiteSettings?.PRIVACY_LINK" target="_blank">Privacy Policy</Link> and <Link :href="store.websiteSettings?.TERMS_SERVICE_LINK" target="_blank">Terms of Service</Link>
                            </label>
                        </div>
                    </div>
                </template>

                <!-- <div class="diposit_content" v-if="activeTab == 1">
                    <div class="payment-note"><i class="fa-regular fa-credit-card"></i> By placing this order, you agree to our Terms Of Use and Privacy Policy</div>
                    <button class="btn" :disabled="!depositChecked">Pay Now {{showPrice(info.totalPriceInfo.totalFareDetail.fC.TF??0)}}</button>
                    <div class="agree_line">
                        <label><input type="checkbox" class="input_checkbox" @change="handleDipositChange" :checked="depositChecked"/> I accept</label><a href="#">terms & conditions</a>
                    </div>
                </div>

                <div class="net_banking_content" v-if="activeTab == 2">
                    <div class="payment-note"><i class="fa-regular fa-credit-card"></i> Please note: You may be redirected to bank page to complete your transaction. By making this booking, you agree to our Terms of Use and Privacy Policy.</div>
                    <p>Payment Fee : {{showPrice(25)}}</p>
                    <button class="btn" :disabled="!netBankingChecked">Pay Now {{showPrice(info.totalPriceInfo.totalFareDetail.fC.TF??0)}}</button>
                    <div class="agree_line">
                        <label><input type="checkbox" class="input_checkbox" @change="handleBankingChange" :checked="netBankingChecked"/> I accept</label><a href="#">terms & conditions</a>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <button v-if="!this.processing" class="stp_btn" @click="goToStep(3)">Prev</button>
</template>
<script>
import {goToStep, showPrice, showErrorToast, airBaggageDesc, airMealDesc, getSupplierMarkupPrice, getAgentMarkupPrice, getAgentDiscountPrice} from '../../../utils/commonFuntions.js'
import { store } from '../../../store.js';
import axios from "axios";
export default {
    name:"Step4",
    created() {
        if(this.info.searchQuery) {
            // console.log('FareSummary.this.info.searchQuery=',this.info.searchQuery);
            // console.log('FareSummary.store.passengers=',store.passengers);

            if(this.info.tripInfos) {
                let totalSsrBaggage = 0;
                let totalSsrMeal = 0;
                this.info.tripInfos.forEach((tripInfo)=>{
                    tripInfo.sI.forEach((flightData)=>{
                        store.paxInfo_arr.forEach((paxType)=>{
                            if(this.info.searchQuery.paxInfo[paxType.key] && this.info.searchQuery.paxInfo[paxType.key] > 0) {
                                for (var n = 1; n <= this.info.searchQuery.paxInfo[paxType.key]; n++) {
                                    if(store.passengers[`${paxType.key}${n}_baggage_${flightData.da.code}_${flightData.aa.code}`]) {
                                        let price = this.airBaggageDesc(this.info, store.passengers[`ADULT${n}_baggage_${flightData.da.code}_${flightData.aa.code}`],'amount');
                                        if(price) {
                                            totalSsrBaggage = totalSsrBaggage + price;
                                        }
                                    }
                                    if(store.passengers[`${paxType.key}${n}_meal_${flightData.da.code}_${flightData.aa.code}`]) {
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
                var supplier_markup = this.getSupplierMarkupPrice(this.info.totalPriceInfo.totalFareDetail.fC.BF,this.info.searchQuery,1,this.info.tripInfos[0].totalPriceList[0].fareIdentifier);
                var agent_markup = this.getAgentMarkupPrice(this.info.totalPriceInfo.totalFareDetail.fC.BF,this.info.searchQuery,1,this.info.tripInfos[0].totalPriceList[0].fareIdentifier);
                var agent_discount = this.getAgentDiscountPrice(supplier_markup,this.info.searchQuery,1,this.info.tripInfos[0].totalPriceList[0].fareIdentifier);
                this.totalPrice = this.info.totalPriceInfo.totalFareDetail.fC.TF + this.totalSsrPrice + supplier_markup + agent_markup - agent_discount;
            }
        }

        this.activeTab = (store.userInfo && store.userInfo.balance && store.userInfo.balance >= this.totalPrice)?'wallet':this.payment_gateways[0].value;
    },
    data() {
        return {
            dataStep: 'Step 4',
            store,
            termsChecked : '',
            netBankingChecked :false,
            activeTab : '',
            info : this.info,
            errors: {},
            processing: false,
            totalPrice: 0,
            totalSsrPrice : 0,
            totalSsrBaggage : 0,
            totalSsrMeal : 0,
        }
    },
    methods:{
        goToStep,
        showPrice,
        getSupplierMarkupPrice,
        getAgentMarkupPrice,
        getAgentDiscountPrice,
        showErrorToast,
        airBaggageDesc,
        airMealDesc,
        handleTermsChange(e) {
            if(e.target.checked) {
                this.termsChecked = e.target.value;
            } else {
                this.termsChecked = '';
            }
        },
        handleBankingChange(e){
            this.netBankingChecked = e.target.checked;
        },
        handleTab(tabId){
            this.activeTab = tabId
        },
        handlePayment(payment_method) {
            var currentObj = this;
            currentObj.processing = true;
            currentObj.errors = {};
            name = store.passengers.ADULT1_first_name+' '+store.passengers.ADULT1_last_name;
            axios.post('/booking', {
                action: 'flight_booking',
                payment_method: payment_method,
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
    },
    watch:{
        store: {
            handler: function(store) {
                // console.log('Step4.store=',store);
                if(this.info.searchQuery) {
                    // console.log('FareSummary.this.info.searchQuery=',this.info.searchQuery);
                    // console.log('FareSummary.store.passengers=',store.passengers);

                    if(this.info.tripInfos) {
                        let totalSsrBaggage = 0;
                        let totalSsrMeal = 0;
                        this.info.tripInfos.forEach((tripInfo)=>{
                            tripInfo.sI.forEach((flightData)=>{
                                store.paxInfo_arr.forEach((paxType)=>{
                                    if(this.info.searchQuery.paxInfo[paxType.key] && this.info.searchQuery.paxInfo[paxType.key] > 0) {
                                        for (var n = 1; n <= this.info.searchQuery.paxInfo[paxType.key]; n++) {
                                            if(store.passengers[`${paxType.key}${n}_baggage_${flightData.da.code}_${flightData.aa.code}`]) {
                                                let price = this.airBaggageDesc(this.info, store.passengers[`ADULT${n}_baggage_${flightData.da.code}_${flightData.aa.code}`],'amount');
                                                if(price) {
                                                    totalSsrBaggage = totalSsrBaggage + price;
                                                }
                                            }
                                            if(store.passengers[`${paxType.key}${n}_meal_${flightData.da.code}_${flightData.aa.code}`]) {
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

                        var supplier_markup = this.getSupplierMarkupPrice(this.info.totalPriceInfo.totalFareDetail.fC.BF,this.info.searchQuery,1,this.info.tripInfos[0].totalPriceList[0].fareIdentifier);
                        var agent_markup = this.getAgentMarkupPrice(this.info.totalPriceInfo.totalFareDetail.fC.BF,this.info.searchQuery,1,this.info.tripInfos[0].totalPriceList[0].fareIdentifier);
                        var agent_discount = this.getAgentDiscountPrice(supplier_markup,this.info.searchQuery,1,this.info.tripInfos[0].totalPriceList[0].fareIdentifier);

                        this.totalPrice = this.info.totalPriceInfo.totalFareDetail.fC.TF + this.totalSsrPrice + supplier_markup + agent_markup - agent_discount;
                    }
                }
            },
            deep: true
        },
    },
    props: ["info","price_id", "payment_gateways"],
}
</script>