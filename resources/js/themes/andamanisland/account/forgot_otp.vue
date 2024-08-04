<template>
    <ForgotOtpWrapper class="forgot_otp_page">

        <div class="container">
            <div class="forgotOtpForm">
                <h1 class="text-2xl text-center mb-4 fw500">Forgot Password OTP</h1>
                <FlashMessage :flashMessages="this.flashMessages" />
                <h3 class="para-lg bold font15 my-2">Please Enter the OTP to Verify your Account</h3>

                <form @submit="handleFormSubmit">
                    <div class="digit-group mb16" data-group-name="digits" data-autosubmit="false">
                        <input class="form_control bg-transparent" type="text" placeholder="X X X X X" name="otp" @change="handleChange($event)" maxlength="5"/>
                        <span class="validation_error" v-if="errors[`otp`]">{{errors['otp'][0]}}</span>
                    </div>
                    <div class="form_group submit_btn">

                        <button type="button" class="btn" disabled v-if="this.isProcessing">Processing...</button>
                        <button type="submit" class="btn" v-else>Validate OTP</button>

                        <button type="button" class="btn" @click="forgotResendOtp" >Resend OTP</button>
                    </div>
                </form>
            </div>
        </div>        
    </ForgotOtpWrapper>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import Layout from '../components/layout.vue';
import FlashMessage from '../components/FlashMessage.vue';
import { store } from '../store';
import {showErrorToast} from '../utils/commonFuntions.js';
import axios from "axios";
import { ForgotOtpWrapper } from '../styles/ForgotOtpWrapper';


export default {
    name: 'ForgotOtp',
    layout: Layout,
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.flashMessages = this.flash_messages;
    },
    data() {
        return {
            store,
            formData : {
                'otp': ''
            },
            errors: {},
            flashMessages: [],
            isProcessing: false,
        }
    },
    methods: {
        showErrorToast,
        handleChange(event) {
            let currentObj = this;
            if(event?.target?.name){
                var name = event.target.name;
                var value = event.target.value;
                let newFormData = currentObj.formData;
                newFormData[name] = value;
                currentObj.formData = newFormData;
            }
            currentObj.errors = {};
            currentObj.flashMessages = [];
        },
        handleFormSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentObj = this;
            currentObj.errors = {};
            currentObj.flashMessages = [];
            currentObj.isProcessing = true;
            var formData = currentObj.formData;
            axios.post('/account/forgot-otp', formData)  
            .then(function (resp) {
                currentObj.isProcessing = false;
                let response = resp.data;
                if(response.success) {
                    if(response.userInfo) {
                        store.userInfo = response.userInfo;
                    }
                    if(response.flash_messages) {
                        currentObj.flashMessages = response.flash_messages;
                    }
                    if(response.redirect_url) {
                        currentObj.$inertia.get(response.redirect_url);
                    }
                    if(response.location_url) {
                        window.location.href = response.location_url;
                    }
                } else if(response.message) {
                   currentObj.showErrorToast(response.message);
                } else {
                   currentObj.showErrorToast('Something went wront, please try again.');
                }
            })
            .catch(function (e) {
                currentObj.isProcessing = false;
                var response = e.response.data;
                if(response.errors) {
                    currentObj.errors = response.errors;
                }
                if(response.message) {
                   currentObj.showErrorToast(response.message);
                }
            });            
        },
        forgotResendOtp(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentObj = this;
            currentObj.errors = {};
            currentObj.flashMessages = [];
            currentObj.isProcessing = true;
            var formData = currentObj.formData;
            axios.post('/account/resend-forgot_otp', formData)  
            .then(function (resp) {
                currentObj.isProcessing = false;
                let response = resp.data;
                if(response.success) {
                    if(response.userInfo) {
                        store.userInfo = response.userInfo;
                    }
                    if(response.flash_messages) {
                        currentObj.flashMessages = response.flash_messages;
                    }
                    if(response.redirect_url) {
                        currentObj.$inertia.get(response.redirect_url);
                    }
                    if(response.location_url) {
                        window.location.href = response.location_url;
                    }
                } else if(response.message) {
                   currentObj.showErrorToast(response.message);
                } else {
                   currentObj.showErrorToast('Something went wront, please try again.');
                }
            })
            .catch(function (e) {
                currentObj.isProcessing = false;
                var response = e.response.data;
                if(response.errors) {
                    currentObj.errors = response.errors;
                }
                if(response.message) {
                   currentObj.showErrorToast(response.message);
                }
            });            
        },
    },
    components: { 
        Link,
        ForgotOtpWrapper,
        FlashMessage
    },
    props: ["search_data", "seo_data", "flash_messages"],
}
</script>