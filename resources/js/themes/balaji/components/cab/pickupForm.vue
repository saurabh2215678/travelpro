<template>
    <form @submit.prevent="handleSubmit" class="rts_flight">

        <div class="fts_top" ref="cabRef">
            <div class="form_item">
                <div class="input_item floating_input">
                    <input type="text" class="input_control"  placeholder="NAME" name="name" @input="handleInput" >
                    <label>NAME</label>
                </div>
                <div class="error" v-if="this.errors?.name">{{this.errors.name}}</div>
            </div>
            
            <div class="form_item">
                <div class="input_item floating_input">
                    <input type="text" class="input_control"  placeholder="EMAIL" name="email" @input="handleInput" >
                    <label>EMAIL</label>
                </div>
                <div class="error" v-if="this.errors?.email">{{this.errors.email}}</div>
            </div>

            <div class="phone_input">
                <div class="phone_wrappr">
                    <select class="country_select" name="country_code" @change="handleInput">
                    <template v-for="country in countryData">
                        <option :value="country.phonecode" :selected="country.phonecode == 91">+ {{country.phonecode}}</option>
                    </template>
                       
                    </select>
                    <div class="form_item">
                        <div class="input_item floating_input">
                            <input type="text" class="input_control"  placeholder="PHONE" name="phone" @input="handleInput" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" >
                            <label>MOBILE</label>
                        </div>
                    </div>
                </div>
                <div class="error" v-if="axios_errors['phone'] && axios_errors['phone'][0]">{{axios_errors['phone'][0]}}</div>
                <div class="error" v-else-if="this.errors?.phone">{{this.errors.phone}}</div>
            </div>

            <div class="form_item">
                <div class="input_item floating_input">
                    <input type="text" class="input_control"  placeholder="PICKUP" name="pickup" @input="handleInput" >
                    <label>PICKUP</label>
                </div>
                <div class="error" v-if="this.errors?.pickup">{{this.errors.pickup}}</div>
            </div>
            
            <div class="form_item">
                <div class="input_item floating_input">
                    <input type="text" class="input_control"  placeholder="DROP" name="drop" @input="handleInput" >
                    <label>DROP</label>
                </div>
                <div class="error" v-if="this.errors?.drop">{{this.errors.drop}}</div>
            </div>

            <p>By proceeding to book, I Agree to {{routeInfo.app_name}} <a :href="routeInfo.privacy_link" target="_blank">Privacy Policy</a> and  <a :href="routeInfo.terms_service_link" target="_blank">Terms of Service</a></p>

        </div>
        <div class="fts_bottom">
            <span class="stp_btn" @click="clicked">Back</span>
            <button class="stp_btn" v-if="this.processing">Processing...</button>
            <button class="stp_btn" type="submit" v-else>Proceed</button>
        </div>
    </form>
</template>

<script>
import axios from "axios";
import { validateEmail, validatePhone, isEmpty, showErrorToast } from '../../utils/commonFuntions';
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store';

export default {
    created() {
        this.errors = {...this.Errors};

        // console.log(this.routeInfo);
    },
    data() {
        return {
            test : "test",
            errors : {},
            axios_errors : {},
            routeInfo: this.routeInfo,
            countryData: this.countryData,
            formdata : {},
            store
        }
    },
    methods: {
        showErrorToast,
        validateName(){
            if(!this.formdata.name){
                this.errors['name'] = 'Name is required'
            }else{
                delete this.errors.name;
            }
        },
        validateEmail(){
            if(!this.formdata.email){
                this.errors['email'] = 'Email is required'
            }else if(!validateEmail(this.formdata.email)){
                this.errors['email'] = 'Email Not Valid'
            }else{
                delete this.errors.email;
            }
        },
        validatePhone(){
            if(!this.formdata.phone){
                this.errors['phone'] = 'Phone is required'
            }else if(!validatePhone(this.formdata.phone)){
                this.errors['phone'] = 'Phone Not Valid'
            }else{
                delete this.errors.phone;
            }
        },
        validatePickup(){
            if(!this.formdata.pickup){
                this.errors['pickup'] = 'Pickup location is required'
            }else{
                delete this.errors.pickup;
            }
        },
        validateDrop(){
            if(!this.formdata.drop){
                this.errors['drop'] = 'Drop location is required'
            }else{
                delete this.errors.drop;
            }
        },
        validate(){
            this.validateName();
            this.validateEmail();
            this.validatePhone();
            this.validatePickup();
            this.validateDrop();
            return isEmpty(this.errors);
        },
        formSubmit(){
            var currentObj = this;
            currentObj.processing = true;
            this.formdata.action = 'cab_booking';
            this.formdata.tripType = this.tripType;
            this.formdata.cab_id = this.routeInfo.cab_id;
            this.formdata.cab_route_id = this.routeInfo.cab_route_id;
            this.formdata.cab_route_group = this.routeInfo.cab_route_group;
            this.formdata.travelDate = this.routeInfo.travelDate;
            this.formdata.travelTime = this.routeInfo.travelTime;
            this.formdata.fav_offer = store.fav_offer;
            
            /*axios.post('/booking', this.formdata)  
            .then(function (response) {  
                // currentObj.output = response.data;
                if(response.data.success) {
                    window.location.href = response.data.redirect_url;
                } else {
                    currentObj.showErrorToast(response.data.message,10);
                }
                currentObj.processing = false;
            })*/

            axios.post('/booking', this.formdata)  
            .then(function (response) {  
                // currentObj.output = response.data;
                if(response.data.success) {
                    window.location.href = response.data.redirect_url;
                } else {
                    currentObj.showErrorToast(response.data.message,10);
                }
                currentObj.processing = false;
            })
            .catch(function (error) {
                if(error.response.data.errors) {
                    currentObj.axios_errors = error.response.data.errors;
                    setTimeout(() => {
                        setTimeout(() => {
                            // currentObj.$refs.cabRef.scrollIntoView();
                        }, 1);
                    }, 1);
                    currentObj.processing = false;

                    if(error.response.data.tripInfos) {
                        currentObj.showErrorToast(currentObj.axios_errors.tripInfos[0]);
                    }

                    error.response.data.errors.forEach((error,index) => {
                        currentObj.showErrorToast(error['message']);
                    });
                }

                if(error.response.data.message) {
                    currentObj.showErrorToast(error.response.data.message,10);
                }
            });

        },
        handleSubmit(){
            if(!this.validate()){
                console.log(this.errors);
            }else{
                this.formSubmit();
            }
        },
        handleInput(e){
            this.formdata[e.target.name] = e.target.value;
            // console.log(e.target.name);
            e.target.name == 'name' && this.validateName(); 
            e.target.name == 'email' && this.validateEmail(); 
            e.target.name == 'phone' && this.validatePhone(); 
            e.target.name == 'pickup' && this.validatePickup(); 
            e.target.name == 'drop' && this.validateDrop(); 
        },
        clicked(){
            // console.log('going back');
            store.loadingName = "searchForm";
            window.history.back();
        }
    },
    mounted () {},
    beforeDestroy () {},
    watch:{
        errors: {
            handler: function(errors) {
                console.log(errors);
            },
            deep: true
        }
    },
    components: {
        Link
    },
    props: ["Errors", "goBack","routeInfo" ,"countryData","tripType" ],
};
</script>