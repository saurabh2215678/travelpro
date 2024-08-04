<template>
    <SearchForm />
    <section class="theme_form allbooking_form mt-7">
    <div class="xl:container xl:mx-auto">
        <div class="theme_form_wrap popup-booking">
            <form id="book_now_form" method="POST" autocomplete="off" @submit="handleFormSubmit">
                <div class="flash-message"></div>
                <div class="form_inner">
                    <div class="form_price">
                        <div class="w-4/6 pr-5">
                            <div class="left_sec logincard" v-if="!userLoggedIn">
                                <ul class="flex items-center">
                                    <!-- <li class="pr-2"><img src="./assets/images/login-globe-icon.png"></li> -->
                                    <li><!--<strong>Sign in to use your {{store.websiteSettings?.SYSTEM_TITLE}} Cash!</strong>--> <span class="text-xs"><strong>Signing in to your {{store.websiteSettings?.SYSTEM_TITLE}} account allows for faster bookings.</strong></span></li>
                                    <li class="w-20"><span class="text-xs"><a class="text-teal-500 underline font-bold" :href="`${store.websiteSettings?.FRONTEND_LOGIN_URL}?redirect_url=${this.back_url}`">Sign in</a></span></li>
                                </ul>
                            </div>
                            <div class="left_sec">
                            <h3 class="title text-xl font-bold pt-3">Book Now</h3>
                            <div class="form_list">
                                <div class="w-full p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <label>Name<em>*</em></label>
                                        <input type="text" name="name" id="name" :value="this.userData?.name" class="form_control" @change="handleChange($event)" >
                                        <span id="name_error" class="validation_error"></span>
                                    </div>
                                </div>
                                <div class="w-1/2 p-2 pb-0 pt-0">
                                    <div class="form_group phone_code">
                                        <label>Phone<em>*</em></label>
                                        <select name="country_code" class="form-select country_code" v-if="countries" @change="handleChange($event)">
                                          <template v-for="c in countries">
                                          <option :value="c.phonecode" :selected="c.phonecode==this.userData?.country_code" >+{{c.phonecode}}</option>
                                          </template>
                                        </select>
                                        <input type="text" name="phone" id="phone" :value="this.userData?.phone" class="form_control" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" @change="handleChange($event)">
                                        <span id="phone_error" class="validation_error"></span>
                                    </div>
                                </div>
                                <div class="w-1/2 p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <label>Email<em>*</em></label>
                                        <input type="text" name="email" id="email" :value="this.userData?.email" class="form_control" @change="handleChange($event)">
                                        <span id="email_error" class="validation_error"></span>
                                    </div>
                                </div>
                                <div class="w-full p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <label>Address</label>
                                        <input type="text" name="address1" id="address1" :value="this.userData?.address1" class="form_control" @change="handleChange($event)">
                                        <span id="address1_error" class="validation_error"></span>
                                    </div>
                                </div>
                                <div class="w-1/4 p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <label>City</label>
                                        <input type="text" name="city" id="city" :value="this.userData?.city" class="form_control" @change="handleChange($event)">
                                        <span id="city_error" class="validation_error"></span>
                                    </div>
                                </div>
                                <div class="w-1/4 p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <label>State</label>
                                        <input type="text" name="state" id="state" :value="this.userData?.state" class="form_control" @change="handleChange($event)">
                                        <span id="state_error" class="validation_error"></span>
                                    </div>
                                </div>
                                <div class="w-1/4 p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <label>Zip Code</label>
                                        <input type="text" name="zip_code" id="zip_code" :value="this.userData?.zipcode" class="form_control" maxlength="10" @change="handleChange($event)">
                                        <span id="zip_code_error" class="validation_error"></span>
                                    </div>
                                </div>
                                <div class="w-1/4 p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <label>Country</label>
                                        <select class="form_control" name="country" id="country" v-if="countries" @change="handleChange($event)">
                                            <option value="">Please Select</option>
                                            <template v-for="c in countries">
                                            <option :value="c.id" :selected="c.id==this.userData?.country">{{c.name}}</option>
                                            </template>
                                        </select>
                                        <span id="country_error" class="validation_error"></span>
                                    </div>
                                </div>
                                <div class="mb-0 w-full p-2 pb-0 pt-0">
                                    <div class="form-group">
                                        <input class="mr-2" type="checkbox" name="booking_for_other" id="book" @change="handleForOtherChange" value="1" >
                                        <label for="book" class="text-teal-500">Make this booking for someone else</label>
                                    </div>
                                    <div class="form_group" v-if="this.forOtherChecked">
                                        <div class="w1">
                                            <div class="w-full p-2 pb-0 pt-0">
                                                <div class="form_group">
                                                    <label>Full Name<em>*</em></label>
                                                    <input type="text" name="other_name" id="other_name" :value="this.userData?.other_name" class="form_control" @change="handleChange($event)">
                                                    <span id="other_name_error" class="validation_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w2 flex">
                                            <div class="w-1/2 p-2 pb-0 pt-0">
                                                <div class="form_group phone_code">
                                                    <label>Phone<em>*</em></label>
                                                    <select name="other_country_code" class="form-select country_code" @change="handleChange($event)">
                                                        <template v-for="c in countries">
                                                            <option :value="c.phonecode" :selected="c.phonecode==this.userData?.other_country_code" >+{{c.phonecode}}</option>
                                                        </template>
                                                    </select>
                                                    <input type="text" name="other_phone" id="other_phone" :value="this.userData?.other_phone" class="form_control" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" @change="handleChange($event)">
                                                    <span id="other_phone_error" class="validation_error"></span>
                                                </div>
                                            </div>
                                            <div class="w-1/2 p-2 pb-0 pt-0">
                                                <div class="form_group">
                                                    <label>Email<em>*</em></label>
                                                    <input type="text" name="other_email" id="other_email" :value="this.userData?.other_email" class="form_control" @change="handleChange($event)">
                                                    <span id="other_email_error" class="validation_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-0 w-full p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <label>Comments</label>
                                        <textarea name="comment" id="comment" class="form_control" rows="2" @change="handleChange($event)">{{this.userData?.comment}}</textarea>
                                        <span id="comment_error" class="validation_error"></span>
                                    </div>
                                </div>

                                <div class="mb-0 w-full p-2 pb-0 pt-0">
                                    <div class="form_group">
                                        <p class="text-xs">By proceeding with this booking, I agree to {{store.websiteSettings?.SYSTEM_TITLE}}'s <Link :href="store.websiteSettings?.TERMS_SERVICE_LINK"><b>Terms of Use</b></Link> and <Link :href="store.websiteSettings?.PRIVACY_LINK"><b>Privacy Policy.</b></Link></p>
                                    </div>
                                </div>

                                
                            </div>
                            <div class="mb-0 w-1/2 p-2 pb-0 pt-0">
                                <div class="form_group">
                                    <template v-if="action=='hotel_booking'">
                                        <input type="hidden" name="hotel" :value="accommodation.id">
                                        <input type="hidden" name="inventory" :value="inventory_data.id">
                                    </template>
                                     <template v-else-if="action=='rental_booking'">
                                        <input type="hidden" name="pickupdate" value="1">
                                    </template>
                                    <template v-else>
                                        <input type="hidden" name="package" :value="package.id">
                                        <input type="hidden" name="package_type" :value="packagePrice.id">
                                    </template>
                                    <input type="hidden" name="action" :value="action">
                                    <button type="submit" class="btn primary-btn" name="submit">Submit</button>
                                </div>
                            </div>
                </div>
                        </div>
                        <div class="right_sec w-2/6 bg-gray-100 pt-0">
                            <div>
                                <BookingSummary :action="action" :booking_data="booking_data" :package="package" :packagePrice="packagePrice" :accommodation="accommodation" :inventory_data="inventory_data" :userObject="this.userData" :checkin="checkin" :checkout="checkout" :inventory="inventory" :adult="adult" :child="child" :infant="infant" />
                                <hr>
                                <div class="offers_code" v-if="coupons && coupons.length > 0">
                                    <h4 class="text-sm font-semibold pt-2 pb-2" style=" color: #4CAF50;">{{store.websiteSettings?.SYSTEM_TITLE}} Offers</h4>
                                    <template v-for="coupon in coupons">
                                        <div class="offerlist">
                                            <label class="flex">
                                                <input type="radio" id="coupon_id" name="fav_offer" :value="coupon.id" style="width: auto;">
                                                <div class="w-full pl-2">
                                                <div class="flex flex-row justify-between">
                                                    <span class="text-sm">{{coupon.code}}</span>
                                                    <span class="text-sm">-{{this.showPrice(coupon.discount_coupon)}}</span>
                                                </div>
                                                <div class="text-xs">Congratulations! You have saved {{this.showPrice(coupon.discount_coupon)}} with {{coupon.code}}.</div>
                                                </div>
                                            </label>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </section>
</template>

<script>
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
import SearchForm from './components/SearchForm.vue';
import BookingSummary from './components/BookingSummary.vue';
import Layout from './components/layout.vue';
import {showPrice, showErrorToast} from './utils/commonFuntions';
import { store } from './store';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
const $toast = useToast();

export default {
    created() {
      document.body.classList.add('package-detail-page');
      document.body.classList.add('booknow');
      if(this.userObject && this.userObject.name) {
        this.userData = this.userObject;
      }
      store.seoData = this.seo_data;
    },
    beforeUnmount() {
      document.body.classList.remove('booknow');
      document.body.classList.remove('package-detail-page');
   },
    data() {
        return {
            store,
            total_amount:0,
            forOtherChecked:false,
            userData:{
                country_code: 91,
                other_country_code: 91,
                country: 101
            },
        }
    },
    methods: {
        showPrice,
        showErrorToast,
        showToast(toastObj){
            $toast.open(toastObj);
        },
        handleForOtherChange(e) {
            e.preventDefault();
            this.forOtherChecked = !this.forOtherChecked;
        },
        handleChange(event) {
            var userData = this.userData
            if(event?.target?.name){
                var name = event.target.name;
                if(name == 'zip_code') {
                    name = 'zipcode';
                }
                userData[name] = event.target.value;
                this.userData = userData;
            }else{
                this.userData = {...this.userData, ...event}
            }
        },
        handleFormSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            let currentObj = this;
            let form = e.target;
            var formID = 'book_now_form';
            $('#'+formID).find('.ajax_msg').html('');
            $('#'+formID).find('.validation_error').html('');
            $('#'+formID).find('.error').html('');
            $('#'+formID).find('.btnSubmit').html('Please wait...');
            $('#'+formID).find('.btnSubmit').attr("disabled", true);
            axios.post('/booking', new FormData($('#'+formID)[0]))  
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
        SearchForm,
        BookingSummary,
        Link
    },
    layout: Layout,
    props: ["seo_data","booking_data", "action", "accommodation", "inventory_data", "checkin", "checkout", "inventory", "adult", "child", "infant", "coupons", "package", "packagePrice", "package_id", "package_type", "userObject", "countries", "userLoggedIn","back_url"],
};
</script>