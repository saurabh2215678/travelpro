<template>
    <TestimonialFormWrapper>
        <form id="testimonial_add_form" method="POST" autocomplete="off" @submit="handleFormSubmit" class="flex flex-wrap">
            <div class="lg:w-1/3 md:w-1/2 w-full">
                <div class="form-floating mb-3 w-full">
                    <label for="Name">{{showLangTitle('Title')}}*</label>
                    <select class="form-control valid" name="title" id="title" v-if="nameTitleArr" @change="handleChange($event)">
                    <option value="">{{showLangTitle('SelectTitle')}} *</option>
                    <template v-for="val,key in nameTitleArr">
                    <option :value="key">{{val}}</option>
                    </template>
                </select>
                     <span id="title_error" class="validation_error"></span>
                </div>
            </div>
        
            <div class="lg:w-1/3 md:w-1/2 w-full">
                <div class="form-floating mb-3 w-full">
                    <label for="Name">{{showLangTitle('Name')}}*</label>
                    <input type="text" id="name" :placeholder="showLangTitle('Name')" class="form-control " datatypeinput="inputname" name="name" :value="this.userData?.name" @change="handleChange($event)">
                    <span id="name_error" class="validation_error"></span>
                </div>
            </div>
    
            <div class="lg:w-1/3 md:w-1/2 w-full">
                <div class="form-floating mb-3 w-full">
                    <label for="Name">Email*</label>
                    <input type="text" id="email" placeholder="Email*"  class="form-control "  name="email" :value="this.userData?.email" @change="handleChange($event)">
                    <span id="email_error" class="validation_error"></span>
                </div>
            </div>
    
            <div class="lg:w-1/3 md:w-1/2 w-full">
                <div class="form-floating mb-3 w-full">
                    <label for="Name">{{showLangTitle('CompanyName')}}</label><input type="text" id="company_name" :placeholder="showLangTitle('CompanyName')" class="form-control " name="company_name" :value="this.userData?.company_name" @change="handleChange($event)">
                </div>
            </div>
    
            <div class="lg:w-1/3 md:w-1/2 w-full">
                <div class="form-floating mb-3 w-full">
                    <label for="Name">{{showLangTitle('PositionInCompany')}}</label><input type="text" id="position_in_company" :placeholder="showLangTitle('PositionInCompany')" class="form-control " name="position_in_company" :value="this.userData?.position_in_company" @change="handleChange($event)">
                </div>
            </div>
    
            <div class="lg:w-1/3 md:w-1/2 w-full">
                <div class="form-floating mb-3 w-full">
                    <label for="Name">{{showLangTitle('Website')}}</label><input type="text" id="website" :placeholder="showLangTitle('Website')" class="form-control " name="website" :value="this.userData?.website" @change="handleChange($event)">
                </div>
            </div>
    
            <div class="form-floating mb-3 w-full">
                <label for="description" class="control-label">{{showLangTitle('Content')}}*</label>
                <ckeditor v-model="editorData"></ckeditor>
                <textarea name="description" class="form-control" :value="editorData" style="display: none;"></textarea> 
                <!-- <textarea name="description" @change="handleChange($event)" style="height: 140px;" class="form-control ckeditor"></textarea>  -->
                <span id="description_error" class="validation_error"></span>  
            </div>
    
            <div class="submit_btn form-floating mb-3 mt-3">
                <input type="hidden" name="package_id" id="package_id" value="0">
                <input type="hidden" name="action" :value="action">
                <button type="submit" class="custom-btn btnSubmit" name="submit">{{showLangTitle('Submit')}}</button>
            </div>
        </form>
    </TestimonialFormWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import axios from 'axios';
import {showErrorToast, showLangTitle} from '../../utils/commonFuntions';
import { component as ckeditor } from '@mayasabha/ckeditor4-vue3';
import {TestimonialFormWrapper} from '../../styles/TestimonialFormWrapper';

export default {
    created() {
      if(this.userObject && this.userObject.name) {
        this.userData = this.userObject;
      }
      // console.log('name_title_arr=',this.nameTitleArr);
    },
    beforeUnmount() {
      document.body.classList.remove('/testimonial/add');
   },
    data() {
        return{
            editorData : '',
            userData: {}
        }
    },
    methods: {
        showErrorToast,
        showLangTitle,
        handleForOtherChange(e) {
            e.preventDefault();
            this.forOtherChecked = !this.forOtherChecked;
        },
        handleChange(event) {
            var UserData = this.userData
            if(event?.target?.name){
                var name = event.target.name;
                UserData[name] = event.target.value;
                this.UserData = UserData;
            }else{
                this.UserData = {...this.UserData, ...event}
            }
        },
        handleFormSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentObj = this;
            let form = e.target;
            var formID = 'testimonial_add_form';
            $('#'+formID).find('.ajax_msg').html('');
            $('#'+formID).find('.validation_error').html('');
            $('#'+formID).find('.error').html('');
            $('#'+formID).find('.btnSubmit').html('Please wait...');
            $('#'+formID).find('.btnSubmit').attr("disabled", true);
            axios.post('/testimonial/add', new FormData($('#'+formID)[0]))  
                .then(function (resp) {
                    let response = resp.data;
                    if(response.success) {
                       window.location.href = response.redirect_url;
                    } else if(response.message) {
                       currentObj.showErrorToast(response.message);
                    } else {
                       currentObj.showErrorToast('Something went wront, please try again.');
                    }
                    $('#'+formID).find('.btnSubmit').html('Submit');
                    $('#'+formID).find('.btnSubmit').attr("disabled", true);
                })
                .catch(function (e) {
                    var response = e.response.data;
                    if(response) {
                        currentObj.parseBookingNowErrorMessages(response, formID, 'Submit');
                    }
                });
            
        },
        parseBookingNowErrorMessages(response, formID, boxText) {
            let currentObj = this;
            $('#'+formID).find('.btnSubmit').html('Submit');
            $('#'+formID).find('.btnSubmit').attr("disabled", false);
            if(response.errors) {
                var errors = response.errors;
                var message='';
                $.each(errors, function (key, val) {
                    $('#'+formID).find("#" + key + "_error").text(val[0]);
                });
            }
            if(response.message){
                currentObj.showErrorToast(response.message);
            }
        }
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
        TestimonialFormWrapper,
        Link,
        ckeditor,
    },
    props: ["testimonialData","nameTitleArr"],
};
</script>