<template>
    <div class="steps_sec">
        <div class="container">
            <ul class="steps-nav">
                <li class="step-item" :class="handleStepClass(1)" @click="handleStepClick(1)">
                    <div class="step_icon"><i class="fa-solid fa-jet-fighter-up"></i></div>
                    <div class="step_txt"><h4>FIRST STEP</h4><span>Flight Itinerary</span></div>
                </li>
                <li class="step-item" :class="handleStepClass(2)" @click="handleStepClick(2)">
                    <div class="step_icon"><i class="fa-solid fa-wheelchair"></i></div>
                    <div class="step_txt"><h4>SECOND STEP</h4><span>Passenger Details</span></div>
                </li>
                <li class="step-item" :class="handleStepClass(3)" @click="handleStepClick(3)">
                    <div class="step_icon"><i class="fa-solid fa-sheet-plastic"></i></div>
                    <div class="step_txt"><h4>THIRD STEP</h4><span>Review</span></div>
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
                <Step2 v-if="store.bookingCurrentStep == 2" :info="info" :price_id="price_id" />
                <Step3 v-if="store.bookingCurrentStep == 3" :info="info" :price_id="price_id" />
                <Step4 v-if="store.bookingCurrentStep == 4" :info="info" :price_id="price_id" :payment_gateways="payment_gateways" />
            </div>
            <div class="fare-summary"><FareSummary :info="info"/></div>
        </div>
    </div>

</template>
<script>
import {showErrorToast} from '../utils/commonFuntions.js';
import FareSummary from '../components/cab/FareSummary.vue';
import { store } from '../store';
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
                window.location.href='/flight';
                // currentObj.$inertia.get(`/flight`);
            },3000);
        }
    },
    data() {
        return {
            info: this.tripInfos,
            store,
        }
    },

    beforeUnmount() {
    //console.log(this.$el)
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
        handleStepClick(stepId){
            if(store.bookingPassedStep + 1 < stepId){
                alert('Please Complete the Current Step first')
            }
            if(store.bookingPassedStep >= stepId || stepId == store.bookingPassedStep + 1){
                // console.log('change the tab');
                store.bookingCurrentStep = stepId;
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
    props: ["tripInfos", "tripInfos_errors", "price_id", "payment_gateways"],
}
</script>