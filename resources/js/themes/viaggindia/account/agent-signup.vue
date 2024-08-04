<template>
    <AgentSignupWrapper class="signup_page_section">
        <div class="container">
            <div class="signupForm">

                <h1 class="text-2xl text-center mb-4 fw500">Travel Agent Signup</h1>

                <a :href="GOOGLE_LOGIN_URL">
                    <img src="../assets/images/google_logo.png" alt="">
                    Continue with Google
                </a>

                <div class="border_line">Or continue with username / email</div>
                <FlashMessage :flashMessages="this.flashMessages" />
                <form @submit="handleFormSubmit">
                    <div class="signup_form">
                        <div class="singup_form_inner">

                            <div class="form_group w-1/2">
                                <label for="company_name" class="text-sm">Company Registered Name<em>*</em></label>
                                <input type="text" name="company_name" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`company_name`]">{{errors['company_name'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="company_brand" class="text-sm">Company Brand/Trade Name<em>*</em></label>
                                <input type="text" name="company_brand" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`company_brand`]">{{errors['company_brand'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="pan_no" class="text-sm">PAN Number<em>*</em></label>
                                <input type="text" name="pan_no" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`pan_no`]">{{errors['pan_no'][0]}}</span>
                            </div>


                            <div class="form_group w-1/2">
                                <label for="pan_image" class="text-sm">Upload PAN Card<em>*</em></label>
                                <input type="file" name="pan_image" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`pan_image`]">{{errors['pan_image'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="gst_no" class="text-sm">GST Number</label>
                                <input type="text" name="gst_no" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`gst_no`]">{{errors['gst_no'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="gst_image" class="text-sm">Upload GST</label>
                                <input type="file" name="gst_image" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`gst_image`]">{{errors['gst_image'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="company_owner_name" class="text-sm">Company Owner Name<em>*</em></label>
                                <input type="text" name="company_owner_name" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`company_owner_name`]">{{errors['company_owner_name'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="agent_logo" class="text-sm">Agent Logo</label>
                                <input type="file" name="agent_logo" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`agent_logo`]">{{errors['agent_logo'][0]}}</span>
                            </div>

                            
                            <div class="form_group w-1/2">
                                <label for="bookings_per_month" class="text-sm">Bookings Per Month</label>
                                <select class="form_control" name="bookings_per_month" @change="handleChange($event)">
                                    <option value="">--Select--</option>
                                    <option 
                                        v-for="val,key in bookings_every_months" 
                                        :value="key"
                                        :selected="key == this.formData.bookings_per_month"
                                    >
                                    {{val}}
                                    </option>
                                </select>
                                <span class="validation_error" v-if="errors[`bookings_per_month`]">{{errors['bookings_per_month'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="no_of_employees" class="text-sm">Number of Employees?<em>*</em></label>
                                <select class="form_control" name="no_of_employees" @change="handleChange($event)">
                                    <option value="">--Select--</option>
                                    <option 
                                        v-for="val,key in total_no_of_employees" 
                                        :value="key"
                                        :selected="key == this.formData.no_of_employees"
                                    >
                                    {{val}}
                                    </option>
                                </select>
                                <span class="validation_error" v-if="errors[`no_of_employees`]">{{errors['no_of_employees'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="agency_age" class="text-sm">How old is you agency?<em>*</em></label>
                                <select class="form_control" name="agency_age" @change="handleChange($event)">
                                    <option value="">--Select--</option>
                                    <option 
                                        v-for="val,key in agency_durations" 
                                        :value="key"
                                        :selected="key == this.formData.agency_age"
                                    >
                                    {{val}}
                                    </option>
                                </select>
                                <span class="validation_error" v-if="errors[`agency_age`]">{{errors['agency_age'][0]}}</span>
                            </div>



                            <div class="form_group w-1/2">
                                <label for="website" class="text-sm">Website</label>
                                <input type="text" name="website" class="form_control" autocomplete="off" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`website`]">{{errors['website'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="destinations_sell_most" class="text-sm">Destinations you sell the most<em>*</em></label>
                                <input type="text" name="destinations_sell_most" class="form_control" autocomplete="off" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`destinations_sell_most`]">{{errors['destinations_sell_most'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2 phone_group">
                                <label for="whatsapp_phone" class="text-sm">Whatsapp Number</label>
                                <select class="form_control" name="whatsapp_country_code" @change="handleChange($event)">
                                    <option 
                                        v-for="country in countryCodes" 
                                        :value="country.dial_code"
                                        :selected="country.dial_code == '+91'"
                                    >
                                    {{ country.dial_code }}
                                    </option>
                                </select>
                                <input type="text" name="whatsapp_phone" class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`whatsapp_phone`]">{{errors['whatsapp_phone'][0]}}</span>
                            </div>

                            <div class="form_group w-1/2">
                                <label for="region" class="text-sm">Your current travelers are from which region? (for international user)<em>*</em></label>
                                <select class="form_control" name="region" @change="handleChange($event)">
                                    <option value="">--Select--</option>
                                    <option 
                                        v-for="val,key in traveler_regions" 
                                        :value="key"
                                        :selected="key == this.formData.region"
                                    >
                                    {{val}}
                                    </option>
                                </select>
                                <span class="validation_error" v-if="errors[`region`]">{{errors['region'][0]}}</span>
                            </div>


                            <div class="form_group w-1/2">
                                <label for="source" class="text-sm">Where did you hear about us?</label>
                                <select class="form_control" name="source" @change="handleChange($event)">
                                    <option value="">--Select--</option>
                                    <option 
                                        v-for="val,key in source_types" 
                                        :value="key"
                                        :selected="key == this.formData.source"
                                    >
                                    {{val}}
                                    </option>
                                </select>
                                <span class="validation_error" v-if="errors[`source`]">{{errors['source'][0]}}</span>
                            </div>
                            
                            <div class="form_group w-full">
                                <label for="company_profile" class="text-sm">Company Profile (Specialization, year of registration, company address, annual revenue)<em>*</em></label>
                                <input type="text" name="company_profile" class="form_control" @change="handleChange($event)">
                                <span class="validation_error" v-if="errors[`company_profile`]">{{errors['company_profile'][0]}}</span>
                            </div>                            
                            
                            <div class="form_group submit_btn">
                                <button type="button" class="btn" disabled v-if="this.isProcessing" >Processing</button>
                                <button type="submit" class="btn" name="submit" v-else>Submit</button>
                            </div>
                            <div class="create_account text-sm mb-2">Skip as a customer <a :href="PROFILE_URL">Click here!</a></div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </AgentSignupWrapper>
</template>
<script>
import Layout from '../components/layout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import FlashMessage from '../components/FlashMessage.vue';
import { store } from '../store';
import {showErrorToast} from '../utils/commonFuntions.js';
import countryCodes from '../utils/CountryCodes.json';
import axios from "axios";
import { AgentSignupWrapper } from '../styles/AgentSignupWrapper';

export default {
    name: 'agent-signup',
    layout: Layout,
    created(){
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.flashMessages = this.flash_messages;
    },
    data(){
        return{
            store,
            countryCodes,
            formData : {
                'company_name':'',
                'company_brand':'',
                'pan_no':'',
                'pan_image':'',
                'gst_no':'',
                'gst_image':'',
                'company_owner_name':'',
                'agent_logo':'',
                'bookings_per_month':'',
                'no_of_employees':'',
                'agency_age':'',
                'website':'',
                'destinations_sell_most':'',
                'whatsapp_country_code':'',
                'whatsapp_phone':'',
                'region':'',
                'source':'',
                'company_profile':'',
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
                if(event.target.type == 'file') {
                    value = event.target.files[0];
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

            let formData = new FormData();

            var fd = currentObj.formData;
            if(fd.company_name) {
                formData.append('company_name',fd.company_name);
            } if(fd.company_brand) {
                formData.append('company_brand',fd.company_brand);
            } if(fd.pan_no) {
                formData.append('pan_no',fd.pan_no);
            } if(fd.pan_image) {
                formData.append('pan_image',fd.pan_image);
            } if(fd.gst_no) {
                formData.append('gst_no',fd.gst_no);
            } if(fd.gst_image) {
                formData.append('gst_image',fd.gst_image);
            } if(fd.company_owner_name) {
                formData.append('company_owner_name',fd.company_owner_name);
            } if(fd.agent_logo) {
                formData.append('agent_logo',fd.agent_logo);
            } if(fd.bookings_per_month) {
                formData.append('bookings_per_month',fd.bookings_per_month);
            } if(fd.no_of_employees) {
                formData.append('no_of_employees',fd.no_of_employees);
            } if(fd.agency_age) {
                formData.append('agency_age',fd.agency_age);
            } if(fd.website) {
                formData.append('website',fd.website);
            } if(fd.destinations_sell_most) {
                formData.append('destinations_sell_most',fd.destinations_sell_most);
            } if(fd.whatsapp_country_code) {
                formData.append('whatsapp_country_code',fd.whatsapp_country_code);
            } if(fd.whatsapp_phone) {
                formData.append('whatsapp_phone',fd.whatsapp_phone);
            } if(fd.region) {
                formData.append('region',fd.region);
            } if(fd.source) {
                formData.append('source',fd.source);
            } if(fd.company_profile) {
                formData.append('company_profile',fd.company_profile);
            }

                // 'company_name':'',
                // 'company_brand':'',
                // 'pan_no':'',
                // 'pan_image':'',
                // 'gst_no':'',
                // 'gst_image':'',
                // 'company_owner_name':'',
                // 'agent_logo':'',
                // 'bookings_per_month':'',
                // 'no_of_employees':'',
                // 'agency_age':'',
                // 'website':'',
                // 'destinations_sell_most':'',
                // 'whatsapp_country_code':'',
                // 'whatsapp_phone':'',
                // 'region':'',
                // 'source':'',
                // 'company_profile':'',




            axios.post('/user/agent-signup-2', formData)  
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
                        // currentObj.$inertia.get(response.redirect_url);
                        window.location.href = response.redirect_url;
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
        AgentSignupWrapper,
        FlashMessage
    },
    props: ["search_data", "seo_data", "flash_messages", "PROFILE_URL", "source_types", "bookings_every_months", "total_no_of_employees", "agency_durations", "traveler_regions"]
}
</script>