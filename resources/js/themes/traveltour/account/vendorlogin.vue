<template>
    <LoginWrapper class="login_page_section">
        <div class="container">
            <div class="loginForm">

                <h1 class="text-2xl text-center mb-4 fw500">Vendor Login</h1>
                <a :href="GOOGLE_LOGIN_URL">
                    <img src="../assets/images/google_logo.png" alt="">
                    Continue with Google
                </a>

                <div class="border_line">Or continue with username / email</div>

                <form :action="ACTION_URL" method="POST" @submit="handleFormSubmit($event)" ref="loginForm" :style="`display:${(this.activeForm=='login')?'block':'none'}`">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <div class="login_form">
                        <FlashMessage :flashMessages="this.flashMessages" />
                        <div class="form_group">
                            <label class="text-sm">Email<em>*</em></label>
                            <input type="text" name="username" class="form_control" autocomplete="off" :value="formData?.username" @change="handleChange($event)">
                            <span class="validation_error" v-if="errors[`username`]">{{errors['username'][0]}}</span>
                        </div>
                        
                        <div class="form_group">
                            <label class="text-sm">Password<em>*</em></label>
                            <input type="password" name="password" class="form_control" autocomplete="off" :value="formData?.password" @change="handleChange($event)">
                            <span class="validation_error" v-if="errors[`password`]">{{errors['password'][0]}}</span>
                        </div>

                        <a class="forgot_pass text-xs pt-2" :href="FORGOT_PASSWORD_URL">Forgot Password?</a>
                        <div class="form_group submit_btn">
                            <button type="button" class="btn" disabled v-if="this.isProcessing">Processing...</button>
                            <button type="submit" class="btn" name="submit" v-else>Submit</button>
                        </div>

                        <div class="create_account text-sm" v-if="ACCOUNT_SIGNUP_URL"> You have not an account <Link :href="ACCOUNT_SIGNUP_URL">Signup Now</Link></div>
                        <div class="create_account text-sm" v-if="AGENT_SIGNUP_URL"> Signup as Agent <Link :href="AGENT_SIGNUP_URL">Signup Now</Link></div>
                        <div class="create_account text-sm" v-if="LOGIN_URL"> You have an account <Link :href="LOGIN_URL">Signin</Link></div>

                    </div>
                </form>

                <form @submit="handleOtpSubmit" ref="otpForm" :style="`display:${(this.activeForm=='otp')?'block':'none'}`">
                    <div class="form-group">
                        <label class="col-md-12 control-label" style="padding-right: 0.3rem;">Enter the OTP sent to </label>
                        <label style="padding: 0; color: #01b3a7;" class="col-md-12 control-label adminEmail">{{this.formData.username}}</label>
                        <div class="w-full flex justify-between flex-row py-2">
                            <div class="w-4/6" style="padding: 0;">
                                <input type="text" class="form-control" name="otp" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  maxlength="5" autocomplete="off" :value="formData?.otp" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`otp`]">{{errors['otp'][0]}}</span>
                            </div>
                            <div class="w-2/6" style="padding-left: 0.5rem;">
                                <button type="button" class="loginbtn text-sm" disabled v-if="this.isProcessing" >Processing...</button>
                                <button type="submit" class="loginbtn text-sm" v-else>Validate OTP</button>
                            </div>
                        </div>

                        <div class="w-full flex justify-between flex-row">
                            <p class="forgot text-xs pt-2" style="margin-top:0px">
                                <a href="javascript:void(0);" v-if="this.isProcessing">Processing...</a>
                                <a @click="this.handleFormSubmit($event,'resendOtp')" v-else>Resend OTP</a>
                            </p>
                            <p class="forgot text-xs pt-2">Click here to <a @click="this.changeUser">Change User</a></p>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </LoginWrapper>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import Layout from '../components/layout.vue';
import styled from 'vue3-styled-components';
import FlashMessage from '../components/FlashMessage.vue';
import { store } from '../store';
import {showErrorToast, showSuccessToast} from '../utils/commonFuntions.js';
import axios from "axios";

const LoginWrapper = styled.section`
padding: 3rem 0;
& .loginForm {
    max-width: 25rem;
    padding: 2rem;
    margin: 0 auto;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 0 17px 0 rgba(0,0,0,.09);
    &>a{
        background: 0 0;
        color: #5ca1ff;
        text-transform: none;
        width: 16rem;
        display: block;
        box-shadow: 0px 3px 3px 0px rgba(64,60,67,.16);
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        margin: 0 auto;
        border: 1px solid var(--black60);
        &>img{
            width: 2rem;
            display: inline-block;
            margin-right: 0.3rem;
        }
    }
}
& .border_line {
    text-align: center;
    position: relative;
    padding-bottom: 0.2rem;
    border: 0;
    font-size: .8rem;
    margin-top: 1rem;
    &:before,
    &:after{
        content: '';
        position: absolute;
        width: 3rem;
        background: #ddd;
        height: 2px;
        top: 0.5rem;
    }
    &:before{
        right: 0;
    }
    &:after{
        left: 0;
    }
}
& .forgot_pass {
    font-size: 0.8rem;
    color: #3737b1;
    text-decoration: underline;
    margin-top: 8px;
    display: block;
}
& .submit_btn {
    padding: 1rem 0;
    & .btn {
        color: var(--white);
        background: var(--theme-color);
        border-radius: 5rem;
        padding: 0.4rem 1.5rem;
        font-size: .8rem;
    }
}
.create_account a {
    color: var(--theme-color);
    text-decoration: underline;
}
`

export default {
    name: 'vendorlogin',
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
                'username': '',
                'password': '',
                'is_vendor': 1,
                'otp': '',
            },
            errors: {},
            flashMessages: [],
            isProcessing: false,
            activeForm: 'login',
            No_OTP: false,
        }
    },
    methods: {
        showErrorToast,
        showSuccessToast,
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
        handleFormSubmit(e,action) {
            let currentObj = this;
            if(currentObj.No_OTP) {
                return true;
            } else {
                e.preventDefault();
                e.stopPropagation();
                currentObj.errors = {};
                currentObj.flashMessages = [];
                currentObj.isProcessing = true;
                var formData = currentObj.formData;
                axios.post(currentObj.VENDOR_AJAX_AUTH_URL, formData)  
                .then(function (resp) {
                    currentObj.isProcessing = false;
                    let response = resp.data;
                    if(response.success) {
                        if(response.message == 'No_OTP') {
                            currentObj.No_OTP = true;
                            currentObj.$refs.loginForm.submit();
                        } else {
                            currentObj.activeForm = 'otp';
                            if(action == 'resendOtp') {
                                currentObj.showSuccessToast('OTP has been resent successfully.');
                                
                            }
                        }
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
                    if(e.response && e.response.data) {
                        var response = e.response.data;
                        if(response.errors) {
                            currentObj.errors = response.errors;
                        }
                        if(response.message) {
                           currentObj.showErrorToast(response.message);
                        }
                    }
                });
            }
        },
        handleOtpSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentObj = this;
            currentObj.errors = {};
            currentObj.flashMessages = [];
            currentObj.isProcessing = true;
            var formData = currentObj.formData;
            axios.post(currentObj.VENDOR_LOGIN_OTP_CHECK_URL, formData)  
            .then(function (resp) {
                currentObj.isProcessing = false;
                let response = resp.data;
                if(response.success) {
                    currentObj.No_OTP = true;
                    currentObj.$refs.loginForm.submit();
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
                if(e.response && e.response.data) {
                    var response = e.response.data;
                    if(response.errors) {
                        currentObj.errors = response.errors;
                    }
                    if(response.message) {
                       currentObj.showErrorToast(response.message);
                    }
                }
            });
            
        },
        changeUser(event) {
            let currentObj = this;
            currentObj.activeForm = 'login';
        },
    },
    components: { 
        Link,
        LoginWrapper,
        FlashMessage,
    },
    props: ["search_data", "seo_data", "flash_messages", "csrfToken", "ACTION_URL", "VENDOR_AJAX_AUTH_URL", "VENDOR_LOGIN_OTP_CHECK_URL", "LOGIN_URL", "FORGOT_PASSWORD_URL", "GOOGLE_LOGIN_URL","ACCOUNT_SIGNUP_URL","AGENT_SIGNUP_URL","VENDOR_LOGIN_URL"]
}
</script>