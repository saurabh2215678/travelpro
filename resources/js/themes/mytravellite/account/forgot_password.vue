<template>
    <ForgotPasswordWrapper class="forgot_password_page">
        <div class="container">

            <div class="forgotPasswordForm">
                <h1 class="text-2xl text-center mb-4 fw500">Forgot Password</h1>
                <FlashMessage :flashMessages="this.flashMessages" />
                <form @submit="handleFormSubmit">
                    <div class="forgot_password_form">
                        <div class="form_group">
                            <label class="text-sm">Email<em>*</em></label>
                            <input type="text" name="forgot_email" class="form_control" autocomplete="off" :value="formData?.forgot_email" @change="handleChange($event)">
                            <span class="validation_error" v-if="errors[`forgot_email`]">{{errors['forgot_email'][0]}}</span>
                        </div>
                        <div class="form_group submit_btn">
                            <button type="button" class="btn" disabled v-if="this.isProcessing">Processing...</button>
                            <button type="submit" class="btn" name="submit" v-else>Submit</button>
                            <Link :href="LOGIN_URL" class="btn">Cancel</Link>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </ForgotPasswordWrapper>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import Layout from '../components/layout.vue';
import FlashMessage from '../components/FlashMessage.vue';
import { store } from '../store';
import {showErrorToast} from '../utils/commonFuntions.js';
import axios from "axios";
import { ForgotPasswordWrapper } from '../styles/ForgotPasswordWrapper';


export default {
    name: 'ForgotPassword',
    layout: Layout,
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        store.flashMessages = this.flash_messages;
    },
    data() {
        return {
            store,
            formData : {
                'forgot_email': ''
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
            axios.post('/account/forgot-password', formData)  
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
        ForgotPasswordWrapper,
        FlashMessage
    },
    props: ["search_data", "seo_data", "flash_messages", "LOGIN_URL"],
}
</script>