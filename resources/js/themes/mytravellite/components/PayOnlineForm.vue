<template>
    <PayOnlineFormWrapper>
        <form @submit="handleFormSubmit" ref="formRef" id="pay_now_form" class="needs-validation" novalidate>
            <h1 class="title text-2xl mb-5">Online Payment</h1>
            <input type="hidden" name="_token">
            <div class="flash-message"></div>

            <div class="form-group row">
                <div class="md:w-1/2">
                    <label for="amount">Amount (INR)<span class="required">*</span></label>
                    <input name="amount" required id="amount" class="form_control" type="text" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
                    <span id="amount_error" class="validation_error"></span>
                </div>
                <div class="md:w-1/2">
                    <label for="description">Description<span class="required">*</span></label>
                    <input name="description" required id="description" class="form_control" type="text"/>
                    <span id="description_error" class="validation_error"></span>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="md:w-1/2">
                    <label for="phone">Phone<span class="required">*</span></label>
                    <div class="phone_wrap">
                        <select name="country_code" class="form-select form_control country_code" @focus="onCountryCodeClick('phone_code')">
                            <option value="91">+91</option>
                            <template v-if="this.countryCodes && this.countryCodes.length > 0">
                                <option 
                                v-for="country in this.countryCodes" 
                                :value="country.phonecode"
                                :selected="country.phonecode == '91'"
                                >
                                    +{{ country.phonecode }}
                                </option>
                            </template>
                        </select>
                        <input name="phone" id="phone" required class="form_control" type="text" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    </div>
                    <span id="phone_error" class="validation_error"></span>
                </div>
                <div class="md:w-1/2">
                    <label for="email">Email<span class="required">*</span></label>
                    <input name="email" id="email" required class="form_control" type="text"/>
                    <span id="email_error" class="validation_error"></span>
                </div>
            </div>

            <div class="form-group row">
                <div class="md:w-1/2">
                    <label for="contact_name">Name<span class="required">*</span></label>
                    <input name="name" id="contact_name" required class="form_control" type="text"/>
                    <span id="name_error" class="validation_error"></span>
                </div>
                <div class="md:w-1/2">
                    <label for="address">Address<span class="required">*</span></label>
                    <input name="address" id="address" required class="form_control" type="text"/>
                    <span id="address_error" class="validation_error"></span>
                </div>
            </div>
           
            <div class="form-group row">
                <div class="md:w-1/2">
                    <label for="city">City</label>
                    <input name="city" id="city" class="form_control" type="text"/>
                    <span id="city_error" class="validation_error"></span>
                </div>
                <div class="md:w-1/2">
                    <label for="state">State</label>
                    <input name="state" id="state" class="form_control" type="text"/>
                    <span id="state_error" class="validation_error"></span>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="md:w-1/2">
                    <label for="country">Country</label>
                    <select name="country" class="form_control">
                        <option 
                        v-for="country in countries" 
                        :value="country.name"
                        :selected="country.phonecode == '91'"
                        >
                            {{ country.name }}
                        </option>
                    </select>
                </div>
                <div class="md:w-1/2">
                    <label for="postal_code">Postal code</label>
                    <input name="zip_code" id="postal_code" class="form_control" type="text"/>
                    <span id="zip_code_error" class="validation_error"></span>
                </div>
            </div>

            <div class="submit_btn">
                <button type="submit" class="btnSubmit">Pay Now</button>
            </div>

        </form>
    </PayOnlineFormWrapper>
</template>
<script>
import {store} from '../store';
import {showErrorToast} from '../utils/commonFuntions';
import axios from "axios";
import { PayOnlineFormWrapper } from '../styles/PayOnlineFormWrapper';



export default {
    name:'PayOnlineForm',
     created() {
      document.body.classList.add('PayOnlineForm');
      store.seoData = this.seo_data;
    },
    beforeUnmount() {
      document.body.classList.remove('PayOnlineForm');
   },
    data() {
        return {
            store,
            countryCodes: []
        }
    },
    methods:{
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
        handleFormSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentObj = this;
            let form = e.target;
            var formID = 'pay_now_form';
            $('#'+formID).find('.ajax_msg').html('');
            $('#'+formID).find('.validation_error').html('');
            $('#'+formID).find('.error').html('');
            $('#'+formID).find('.btnSubmit').html('Please wait...');
            $('#'+formID).find('.btnSubmit').attr("disabled", true);
            axios.post('/payment/pay_online', new FormData($('#'+formID)[0]))  
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
                form.classList.add('was-validated');
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
    components:{
        PayOnlineFormWrapper
    },
    props: ["seoData","countries"],
}
</script>