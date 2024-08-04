<template>
    <LoginWrapper class="login_page_section">
        <div class="container">
            <div class="loginForm">

                <h1 class="text-2xl text-center mb-4 fw500">Login</h1>
                <a :href="GOOGLE_LOGIN_URL">
                    <img src="../assets/images/google_logo.png" alt="">
                    Continue with Google
                </a>

                <div class="border_line">Or continue with username / email</div>

                <form @submit="handleFormSubmit">
                    <div class="login_form">
                        <FlashMessage :flashMessages="this.flashMessages" />
                        <div class="form_group">
                            <label class="text-sm">Email<em>*</em></label>
                            <input type="text" name="user_email" class="form_control" autocomplete="off" :value="formData?.user_email" @change="handleChange($event)">
                            <span class="validation_error" v-if="errors[`user_email`]">{{errors['user_email'][0]}}</span>
                        </div>
                        
                        <div class="form_group">
                            <label class="text-sm">Password<em>*</em></label>
                            <input type="password" name="password" class="form_control" autocomplete="off" :value="formData?.password" @change="handleChange($event)">
                            <span class="validation_error" v-if="errors[`password`]">{{errors['password'][0]}}</span>
                        </div>

                        <Link class="forgot_pass text-xs pt-2" :href="FORGOT_PASSWORD_URL">Forgot Password?</Link>
                        <div class="form_group submit_btn">
                            <button type="button" class="btn" disabled v-if="this.isProcessing">Processing...</button>
                            <button type="submit" class="btn" name="submit" v-else>Submit</button> 
                        </div>

                        <div class="create_account text-sm" v-if="ACCOUNT_SIGNUP_URL"> You have not an account <Link :href="ACCOUNT_SIGNUP_URL">Signup Now</Link></div>
                        <div class="create_account text-sm" v-if="AGENT_SIGNUP_URL"> Signup as Agent <Link :href="AGENT_SIGNUP_URL">Signup Now</Link></div>
                         <div class="create_account text-sm" v-if="VENDOR_LOGIN_URL"> Signin as Vendor <Link :href="VENDOR_LOGIN_URL">Signin</Link></div>

                    </div>
                </form>

            </div>
        </div>
    </LoginWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import { Link } from '@inertiajs/inertia-vue3';
import Layout from '../components/layout.vue';
import FlashMessage from '../components/FlashMessage.vue';
import { store } from '../store';
import {showErrorToast} from '../utils/commonFuntions.js';
import axios from "axios";
import {LoginWrapper} from '../styles/LoginWrapper';

export default {
    name: 'Login',
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
                'user_email': '',
                'password': '',
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
            axios.post('/account/login', formData)  
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
        LoginWrapper,
        FlashMessage
    },
    props: ["search_data", "seo_data", "flash_messages", "FORGOT_PASSWORD_URL", "GOOGLE_LOGIN_URL","ACCOUNT_SIGNUP_URL","AGENT_SIGNUP_URL","VENDOR_LOGIN_URL"]
}
</script>