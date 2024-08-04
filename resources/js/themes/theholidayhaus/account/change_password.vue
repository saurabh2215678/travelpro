<template>
    <ChangePasswordWrapper class="change_password_page">
        <div class="container">

            <div class="change_Password_sec">
                
                <h1 class="text-2xl text-center mb-4 fw500">Change Password</h1>
                <FlashMessage :flashMessages="this.flashMessages" />
                <form @submit="handleFormSubmit">
                    <div class="login_form">
                        <div class="form_group">
                            <label class="text-sm">New Password</label>
                            <input type="password" name="password" placeholder="" class="form_control bg-transparent" autocomplete="off" @change="handleChange($event)" />
                            <span class="validation_error" v-if="errors[`password`]">{{errors['password'][0]}}</span>
                        </div>
                        <div class="form_group">
                            <label class="text-sm">Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="" class="form_control bg-transparent" autocomplete="off" @change="handleChange($event)" />
                            <span class="validation_error" v-if="errors[`confirm_password`]">{{errors['confirm_password'][0]}}</span>
                        </div>
                        <div class="form_group submit_btn">
                            <button type="button" class="btn" disabled v-if="this.isProcessing">Processing...</button>
                            <button type="submit" class="btn" name="submit" v-else>Submit</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>      
    </ChangePasswordWrapper>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';
import FlashMessage from '../components/FlashMessage.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import {showErrorToast} from '../utils/commonFuntions.js';
import axios from "axios";
import { ChangePasswordWrapper } from '../styles/ChangePasswordWrapper';

export default {
    name: 'ChangePassword',
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
                'password': '',
                'confirm_password': ''
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
            axios.post('/account/change-password', formData)  
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
        ChangePasswordWrapper,
        FlashMessage
    },
    props: ["search_data", "seo_data", "flash_messages"],
}
</script>