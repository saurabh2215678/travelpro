<template>
    <SignupWrapper class="signup_page_section">
        <div class="container">
            <div class="signupForm">

                <h1 class="text-2xl text-center mb-4 fw500" v-if="this.isAgent==1">Travel Agent Signup</h1>
                <h1 class="text-2xl text-center mb-4 fw500" v-else>Signup</h1>

                <a :href="GOOGLE_LOGIN_URL">
                    <img src="../assets/images/google_logo.png" alt="">
                    Continue with Google
                </a>

                <div class="border_line">Or continue with username / email</div>
                <FlashMessage :flashMessages="this.flashMessages" />
                <form @submit="handleFormSubmit">
                    <div class="signup_form">
                        <div class="singup_form_inner">
                            <div class="form_group w-full">
                                <label for="name" class="text-sm">Name<em>*</em></label>
                                <input type="text" name="name" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`name`]">{{errors['name'][0]}}</span>
                            </div>
                            
                            <div class="form_group w-1/2 phone_group">
                                <label for="phone" class="text-sm">Phone<em>*</em></label>
                                <select class="form_control" name="country_code" @change="handleChange($event)" @focus="onCountryCodeClick('country_code')">
                                    <option value="+91">+91</option>
                                    <template v-if="this.countryCodes && this.countryCodes.length > 0">
                                        <option 
                                            v-for="country in this.countryCodes" 
                                            :value="country.dial_code"
                                            :selected="country.dial_code == '+91'"
                                        >
                                        {{ country.dial_code }}
                                        </option>
                                    </template>
                                </select>
                                <input type="text" name="phone" class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`phone`]">{{errors['phone'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="email" class="text-sm">Email<em>*</em></label>
                                <input type="text" name="email" class="form_control" autocomplete="off" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`email`]">{{errors['email'][0]}}</span>
                            </div>
                            
                            <div class="form_group w-1/2">
                                <label for="password" class="text-sm">Password<em>*</em></label>
                                <input type="password" name="password" class="form_control" autocomplete="new-password" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`password`]">{{errors['password'][0]}}</span>
                            </div>
                            
                            <div class="form_group w-1/2">
                                <label for="confirm_password" class="text-sm">Confirm Password<em>*</em></label>
                                <input type="password" name="confirm_password" class="form_control" autocomplete="off" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`confirm_password`]">{{errors['confirm_password'][0]}}</span>
                            </div>
                            
                            <div class="create_account termsuse text-sm">
                                <input type="checkbox" name="termsuse" value="termsuse" @change="handleChange($event)">
                                <label for="termsuse" class="font-semibold text-xs"> By signing up, you agree to the Terms of Service and <a :href="store.websiteSettings?.PRIVACY_LINK">Privacy Policy,</a> including Cookie Use.</label>
                                <span class="validation_error" v-if="errors[`termsuse`]">{{errors['termsuse'][0]}}</span>
                            </div>
                            <div class="form_group submit_btn">
                                <button type="button" class="btn" disabled v-if="this.isProcessing">Processing...</button>
                                <button type="submit" class="btn" name="submit" :disabled="this.formData.termsuse==0" v-else >Submit</button>
                            </div>
                            <div class="create_account text-sm mb-2">Already have an account? <Link :href="LOGIN_URL">Login Now</Link></div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </SignupWrapper>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import Layout from '../components/layout.vue';
import FlashMessage from '../components/FlashMessage.vue';
import { store } from '../store';
import {showErrorToast} from '../utils/commonFuntions.js';
//import countryCodes from '../utils/CountryCodes.json';
import axios from "axios";
import { SignupWrapper } from '../styles/SignupWrapper';

export default {
    name: 'signup',
    layout: Layout,
    created(){
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.flashMessages = this.flash_messages;
        if(this.isAgent) {
            let formData = this.formData;
            formData['is_agent'] = this.isAgent;
            this.formData = formData;
        }
    },
    data(){
        return{
            store,
            countryCodes: [],
            formData : {
                'is_agent': 0,
                'name': '',
                'country_code': '',
                'phone': '',
                'email': '',
                'password': '',
                'confirm_password': '',
                'termsuse': 0,
            },
            errors: {},
            flashMessages: [],
            isProcessing: false,
        }
    },
    methods: {
        showErrorToast,
        onCountryCodeClick(e){
            let currentObj = this;
            axios.post(store.websiteSettings.COUNTRY_CODE_URL, {
                type: e,
            })
            .then(function (response) {
                currentObj.countryCodes = response.data.options;
            });
        },
        handleChange(event) {
            let currentObj = this;
            if(event?.target?.name){
                var name = event.target.name;
                var value = event.target.value;
                if(name == 'termsuse') {
                    if(event.target.checked) {
                        value = 1;
                    } else {
                        value = 0;
                    }
                }                
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
            axios.post(this.SIGNUP_URL, formData)  
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
        SignupWrapper,
        FlashMessage
    },
    props: ["search_data", "seo_data", "flash_messages", "SIGNUP_URL", "LOGIN_URL", "GOOGLE_LOGIN_URL", "isAgent" ]
}
</script>