<template>

    <div class="steps_sec">
        <div class="container">
            <ul class="steps-nav">
                <li class="step-item" :class="handleStepClass(1)" @click="handleStepClick(1)">
                    <div class="step_icon"><i class="fa-solid fa-jet-fighter-up"></i></div>
                    <div class="step_txt"><h4>FIRST STEP</h4><span>Flight Itinerary</span></div>
                </li>
                <!-- <li class="step-item" :class="handleStepClass(2)" @click="handleStepClick(2)">
                    <div class="step_icon"><i class="fa-solid fa-wheelchair"></i></div>
                    <div class="step_txt"><h4>SECOND STEP</h4><span>Passenger Details</span></div>
                </li> -->
                <li class="step-item" :class="handleStepClass(3)" @click="handleStepClick(3)">
                    <div class="step_icon"><i class="fa-solid fa-sheet-plastic"></i></div>
                    <div class="step_txt"><h4>SECOND STEP</h4><span>Review</span></div>
                </li>
                <li class="step-item" :class="handleStepClass(4)" @click="handleStepClick(4)">
                    <div class="step_icon"><i class="fa-solid fa-credit-card"></i></div>
                    <div class="step_txt"><h4>FINISH STEP</h4><span>Payments</span></div>
                </li>
            </ul>
        </div>
    </div>

    <div class="step_contents">
        <div class="container">
            <div class="step_forms">
                <Step1 v-if="store.bookingCurrentStep == 1" :info="info" :price_id="price_id" />
                <!-- <Step2 v-if="store.bookingCurrentStep == 2" :info="info" :price_id="price_id" /> -->
                <Step3 v-if="store.bookingCurrentStep == 3" :info="info" :price_id="price_id" />
                <Step4 v-if="store.bookingCurrentStep == 4" :info="info" :price_id="price_id" :payment_gateways="payment_gateways" />
            </div>
            <div class="fare-summary"><FareSummary :info="info"/></div>
        </div>
        <div class="time_count">Your session will expire in {{this.sess_exp['min']}} mins: {{this.sess_exp['sec']}} secs</div>

        <div class="styles_overlay" :class="sessionPopup ? 'active' : ''">
            <div class="styles_modal promtbox_wrapper">
                <div class="promtbox_wrapper_area session_expire">
                    <h2 class="promtbox_wrapper-mainheading">Search Result</h2>
            <p class="promtbox_wrapper-content">Your session has expired. Please reload the page to continue.</p>
                <div class="promtbox_wrapper-button"><button class="btn btn-warning asr-book" @click="goback">Reload Page</button></div>
            </div>
            </div>
            </div>
    </div>

</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import {showErrorToast} from '../utils/commonFuntions.js';
import Step1 from '../components/flight/flight_steps/Step1.vue';
import Step2 from '../components/flight/flight_steps/Step2.vue';
import Step3 from '../components/flight/flight_steps/Step3.vue';
import Step4 from '../components/flight/flight_steps/Step4.vue';
import FareSummary from '../components/flight/FareSummary.vue';
import { store } from '../store';
import Layout from '../components/layout.vue'
import moment from 'moment';
export default {
    
    created() {
        document.body.classList.add('headerType2');
        document.body.classList.add('flight_detail_page'); 
        if(this.tripInfos_errors && this.tripInfos_errors.length > 0) {
            let currentObj = this;
            this.tripInfos_errors.forEach((error,index) => {
                currentObj.showErrorToast(error['message']);
            });
            setTimeout(() => {
                window.location.href = '/flight';
                // currentObj.$inertia.get(`/flight`);
            },3000);
        }
        if(this.tripInfos.conditions && this.tripInfos.conditions.st) {
            // this.seconds = this.tripInfos.conditions.st;
            var curDate = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Kolkata' }));
            var endDate = new Date(moment(this.tripInfos.conditions.sct).add(this.tripInfos.conditions.st, 'seconds'));
            var seconds = moment(endDate).diff(moment(curDate),'seconds');
            // console.log('seconds=',seconds);
            // seconds = 10;
            if(seconds) {
                this.seconds = seconds;
            } else {
                this.seconds = 0;
            }
            var IntID = setInterval(this.startTimer,1000);
            this.IntID = IntID;
        }
    },
    data() {
        return {
            info: this.tripInfos,
            sess_exp:{'min':0,'sec':0},
            store,
            sessionPopup: false
        }
    },
    beforeUnmount() {
        //console.log(this.$el)
        document.body.classList.remove('flight_detail_page');
        document.body.classList.remove('headerType2');
    },
    methods:{
        showErrorToast,
        handleStepClass(stepId){
            let stepClass = '';
            if(store.bookingCurrentStep == stepId){
                stepClass += ' active'
            }
            if(store.bookingPassedStep >= stepId){
                stepClass += ' passed'
            }
            return stepClass;
        },
        goback() {
            // window.history.back();
            window.location.href = '/flight';
        },

        handleStepClick(stepId){
            if(store.bookingPassedStep + 1 < stepId){
                alert('Please Complete the Current Step first')
            }
            if(store.bookingPassedStep >= stepId || stepId == store.bookingPassedStep + 1){
                // console.log('change the tab');
                store.bookingCurrentStep = stepId;
            }
        },
        
        startTimer() {
            if(this.seconds) {
                var seconds = this.seconds-1;
                if(seconds > 0) {
                    var min = parseInt(seconds/60);
                    var sec = seconds-(min*60);
                    this.sess_exp = {'min':min,'sec':sec};
                    this.seconds = seconds;
                } else {
                    //Show the session expird box
                    //alert('SESSION EXPIRED');
                    this.sessionPopup = true;
                    // document.body.classList.add('sssss');
                    clearInterval(this.IntID);
                    //window.location.href = '/flight';
                }
            }
            
            
        }
    },
    components: {
        Step1,
        Step2,
        Step3,
        Step4,
        FareSummary
    },
    layout: Layout,
    props: ["tripInfos", "tripInfos_errors", "price_id", "payment_gateways", "metaTitle", "metaDescription"],
}
</script>
<!-- <style>
.flight_detail_page .topmain-header, .flight_detail_page .main-header{background: linear-gradient(to bottom, #3c3a9a, #07c0a9);}
</style> -->