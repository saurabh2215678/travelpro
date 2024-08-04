<template>
    <div class="stp_wrap" id="step_2">
        <div class="stp_tp_head">
            <h3>Passenger Details</h3>
        </div>
        <div class="rts_flight" ref="flightRef">
            <div class="fts_top">
                <template v-for="paxType in store.paxInfo_arr">
                    <template v-if="info.searchQuery.paxInfo[paxType.key] > 0" v-for="n in info.searchQuery.paxInfo[paxType.key]">
                        <div data-tab-box="passanger">
                            <SlideBox :btnTitle="`${paxType.key} ${n}: ${paxType.years_title} ${store.passengers[`${paxType.key}${n}_title`]??''} ${store.passengers[`${paxType.key}${n}_first_name`]??''} ${store.passengers[`${paxType.key}${n}_last_name`]??''}`" :active="paxType.key=='ADULT' && n==1">
                                <!-- <div class="search_from_history">
                                    <div class="input_item floating_input">
                                        <input type="text" class="input_control" placeholder="Select from History">
                                        <label>Select from History</label>
                                    </div>
                                </div> -->
                                <div class="inline-grid gap-4 grid-cols-4 p-4">
                                    <div class="form_item">
                                        <div class="input_item floating_input w-full">
                                            <select class="input_control" :name="`${paxType.key}${n}_title`" @change="onPassengersChange($event)">
                                                <option value="">Title</option>
                                                <option v-if="paxType.key == 'ADULT'" value="Mr" :selected="store.passengers[`${paxType.key}${n}_title`] == 'Mr'">Mr</option>
                                                <option v-if="paxType.key == 'ADULT'" value="Mrs" :selected="store.passengers[`${paxType.key}${n}_title`] == 'Mrs'">Mrs</option>
                                                <option value="Ms" :selected="store.passengers[`${paxType.key}${n}_title`] == 'Ms'">Ms</option>
                                                <option v-if="paxType.key != 'ADULT'" value="Master" :selected="store.passengers[`${paxType.key}${n}_title`] == 'Master'">Master</option>
                                            </select>
                                            <label>Title</label>
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_title`]">{{errors[`passengers.${paxType.key}${n}_title`][0]}}</div>
                                    </div>
        
                                    <div class="form_item">
                                        <div class="input_item floating_input">
                                            <input type="text" class="input_control" :value="store.passengers[`${paxType.key}${n}_first_name`]" placeholder="First Name" :name="`${paxType.key}${n}_first_name`" @input="onPassengersChange($event)">
                                            <label>First Name</label>
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_first_name`]">{{errors[`passengers.${paxType.key}${n}_first_name`][0]}}</div>
                                    </div>
        
                                    <div class="form_item">
                                        <div class="input_item floating_input">
                                            <input type="text" class="input_control" :value="store.passengers[`${paxType.key}${n}_last_name`]" placeholder="Last Name" :name="`${paxType.key}${n}_last_name`" @input="onPassengersChange($event)">
                                            <label>Last Name</label>
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_last_name`]">{{errors[`passengers.${paxType.key}${n}_last_name`][0]}}</div>
                                    </div>
        
                                    <div class="form_item date_pick_box" v-if="isDobRequired(paxType.key)">
                                        <div class="input_item floating_input w-full">
                                            <!-- <input type="text" class="input_control" :value="store.passengers[`${paxType.key}${n}_dob`]" placeholder="Date of Birth" :name="`${paxType.key}${n}_dob`" @input="onPassengersChange($event)"> -->
        
                                            <DatePicker columns="2"  v-model="store.passengers[`${paxType.key}${n}_dob`]" mode="date" class="date_wrap" :min-date="this[`${paxType.key.toLowerCase()}DobMinDate`]" :max-date="this[`${paxType.key.toLowerCase()}DobMaxDate`]">
                                                <template v-slot="{ inputValue, inputEvents, togglePopover }">
                                                    <input
                                                    class="input_control"
                                                    :value="inputValue"
                                                    @click="togglePopover"
                                                    :name="`${paxType.key}${n}_dob`"
                                                    placeholder="Select Date"
                                                    autocomplete="off"
                                                    />
                                                    <label>Date of Birth</label>
                                                </template>
                                            </DatePicker>
        
                                            
        
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_dob`]">{{errors[`passengers.${paxType.key}${n}_dob`][0]}}</div>
                                    </div>
        
                                </div>
        
                                <!-- PASSPORT INFORMATION Start -->
                                <div class="px-8 pt-4 pb-0" v-if="info.conditions.pcs">
                                    <h2>ADD PASSPORT INFORMATION</h2>
                                </div>
                                <div class="inline-grid gap-4 grid-cols-3 px-4 pb-4" v-if="info.conditions.pcs">
                                    <div class="form_item">
                                        <div class="input_item floating_input w-full">
                                            <select class="input_control" :name="`${paxType.key}${n}_passport_nationality`" @change="onPassengersChange($event)">
                                                <option value="">Select</option>
                                                <template v-for="country in CountryCodes">
                                                    <option :selected="country.name == store.passengers[`${paxType.key}${n}_passport_nationality`]" :value="country.code">{{country.name}}</option>
                                                </template>
                                            </select>
                                            <label>Nationality</label>
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_passport_nationality`]">{{errors[`passengers.${paxType.key}${n}_passport_nationality`][0]}}</div>
                                    </div>
        
                                    <div class="form_item">
                                        <div class="input_item floating_input w-full">
                                            <input type="text" class="input_control" :value="store.passengers[`${paxType.key}${n}_passport_no`]" placeholder="Passport Number" :name="`${paxType.key}${n}_passport_no`" @input="onPassengersChange($event)">
                                            <label>Passport Number</label>
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_passport_no`]">{{errors[`passengers.${paxType.key}${n}_passport_no`][0]}}</div>
                                    </div>
        
                                    <div class="form_item">
                                        <div class="input_item floating_input w-full">
                                            <!-- <input type="text" class="input_control" :value="store.passengers[`${paxType.key}${n}_passport_issue_date`]" placeholder="Issue Date" :name="`${paxType.key}${n}_passport_issue_date`" @input="onPassengersChange($event)">
                                            <label>Issue Date</label> -->
        
                                            <DatePicker columns="2" v-model="store.passengers[`${paxType.key}${n}_passport_issue_date`]" mode="date" class="date_wrap" :min-date="this.passportIssueMinDate" :max-date="this.passportIssueMaxDate" >
                                                <template v-slot="{ inputValue, inputEvents, togglePopover }">
                                                    <input
                                                    class="input_control"
                                                    :value="inputValue"
                                                    @click="togglePopover"
                                                    :name="`${paxType.key}${n}_passport_issue_date`"
                                                    placeholder="Issue Date"
                                                    autocomplete="off"
                                                    />
                                                    <label>Issue Date</label>
                                                </template>
                                            </DatePicker>
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_passport_issue_date`]">{{errors[`passengers.${paxType.key}${n}_passport_issue_date`][0]}}</div>
                                    </div>
        
                                    <div class="form_item">
                                        <div class="input_item floating_input w-full">
                                            <!-- <input type="text" class="input_control" :value="store.passengers[`${paxType.key}${n}_passport_exp_date`]" placeholder="Expiry Date" :name="`${paxType.key}${n}_passport_exp_date`" @input="onPassengersChange($event)">
                                            <label>Expiry Date</label> -->
        
                                            <DatePicker columns="2" v-model="store.passengers[`${paxType.key}${n}_passport_exp_date`]" mode="date" class="date_wrap" :min-date="this.passportExpiryMinDate" :max-date="this.passportExpiryMaxDate" >
                                                <template v-slot="{ inputValue, inputEvents, togglePopover }">
                                                    <input
                                                    class="input_control"
                                                    :value="inputValue"
                                                    @click="togglePopover"
                                                    :name="`${paxType.key}${n}_passport_exp_date`"
                                                    placeholder="Issue Date"
                                                    autocomplete="off"
                                                    />
                                                    <label>Expiry Date</label>
                                                </template>
                                            </DatePicker>
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_passport_exp_date`]">{{errors[`passengers.${paxType.key}${n}_passport_exp_date`][0]}}</div>
                                    </div>
        
                                    <div class="form_item"> <!--v-if="paxType.key!='INFANT'"-->
                                        <div class="input_item floating_input w-full">
                                            <!-- <input type="text" class="input_control" :value="store.passengers[`${paxType.key}${n}_dob`]" placeholder="Date of Birth" :name="`${paxType.key}${n}_dob`" @input="onPassengersChange($event)">
                                            <label>Date of Birth</label> -->
        
                                            <DatePicker 
                                                columns="2" 
                                                v-model="store.passengers[`${paxType.key}${n}_dob`]" 
                                                mode="date" class="date_wrap" :min-date="this[`${paxType.key.toLowerCase()}DobMinDate`]" :max-date="this[`${paxType.key.toLowerCase()}DobMaxDate`]" >
                                                <template v-slot="{ inputValue, inputEvents, togglePopover }">
                                                    <input
                                                    class="input_control"
                                                    :value="inputValue"
                                                    :name="`${paxType.key}${n}_dob`"
                                                    @click="togglePopover"
                                                    placeholder="Date of Birth"
                                                    autocomplete="off"
                                                    />
                                                    <label>Date of Birth</label>
                                                </template>
                                            </DatePicker>
                                        </div>
                                        <div class="error" v-if="errors[`passengers.${paxType.key}${n}_dob`]">{{errors[`passengers.${paxType.key}${n}_dob`][0]}}</div>
                                    </div>
                                </div>
                                <!-- PASSPORT INFORMATION End -->
        
                                <div class="save_passenger">
                                    <label>
                                        <input type="checkbox" class="input_checkbox" :name="`${paxType.key}${n}_save_passenger_details`" @input="onPassengersChange($event)" value="1" >
                                        <span>Save Passenger Details</span>
                                    </label>
                                </div>
                            </SlideBox>
                        </div>
                    </template>
                </template>

                <SlideBox btnTitle="Add Baggage, Meal & Other Services to Your Travel" v-if="this.ssrInfo" responsive="true">
                    <template v-for="(tripInfo, key) in info.tripInfos">
                        <template v-for="(flightData, flightIndex) in tripInfo.sI">
                            <div class="flt_line">{{flightData.da.city??''}} <i class="fa-solid fa-arrow-right"></i> {{flightData.aa.city??''}} <span>on {{DateFormat(flightData.dt,'ddd, MMM Do YYYY')}}</span></div>

                            <template v-for="paxType in store.paxInfo_arr">
                                <div class="inline-grid gap-4 grid-cols-3 p-4" v-if="info.searchQuery.paxInfo[paxType.key] > 0 && paxType.key != 'INFANT'" v-for="n in info.searchQuery.paxInfo[paxType.key]">
                                    <div class="slt_line_item">
                                        <h3>{{paxType.key}} {{n}}</h3>
                                    </div>
                                    <div class="slt_line_item" v-if="flightData.ssrInfo && flightData.ssrInfo.BAGGAGE && flightIndex==0">
                                        <div class="input_item floating_input w-full">
                                            <select class="input_control" :name="`${paxType.key}${n}_baggage_${flightData.da.code}_${flightData.aa.code}`" @change="onPassengersChange($event)">
                                                <option value="">Add Baggage</option>
                                                <option :value="baggage.code" v-for="baggage in flightData.ssrInfo.BAGGAGE" :selected="store.passengers[`${paxType.key}${n}_baggage_${flightData.da.code}_${flightData.aa.code}`] == baggage.code" >Excess Baggage - {{baggage.desc}}<template v-if="baggage.amount"> @ {{showPrice(baggage.amount)}}</template></option>
                                            </select>
                                            <label>Baggage Information</label>
                                        </div>
                                    </div>
                                    <div class="slt_line_item" v-if="flightData.ssrInfo && flightData.ssrInfo.MEAL">
                                        <div class="input_item floating_input w-full">
                                            <select class="input_control" :name="`${paxType.key}${n}_meal_${flightData.da.code}_${flightData.aa.code}`" @change="onPassengersChange($event)">
                                                <option value="">Add Meal</option>
                                                <option :value="meal.code" v-for="meal in flightData.ssrInfo.MEAL" :selected="store.passengers[`${paxType.key}${n}_meal_${flightData.da.code}_${flightData.aa.code}`] == meal.code">{{meal.desc}}<template v-if="meal.amount"> @ {{showPrice(meal.amount)}}</template></option>
                                            </select>
                                            <label>Select Meal</label>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </template>
                </SlideBox>

                <SlideBox btnTitle="Select Seats (Optional)" active="true" v-if="1==2" responsive="true">
                    <div class="grid gap-4 grid-cols-3 p-4" v-for="(tripInfo, key) in info.tripInfos">
                        <template v-for="flightData in tripInfo.sI">
                            <div class="ctry_desk">
                                <div class="flt_line p-0">
                                    {{flightData.da.city??''}} <i class="fa-solid fa-arrow-right"></i> {{flightData.aa.city??''}} <br/>
                                    <span>on {{DateFormat(flightData.dt,'ddd, MMM Do YYYY')}}</span>
                                </div>
                            </div>
                            <div class="center-text">
                                <p>No Seat Selected</p>
                            </div>
                            <div class="btn_box">
                                <button class="btn">Show Seat Map</button>
                            </div>
                        </template>
                    </div>
                </SlideBox>

                <SlideBox btnTitle="Contact Details" active="true" disabled="true" responsive="true">
                    <div class="inline-grid gap-4 grid-cols-4 p-4">
                        <div class="input_item floating_input w-full">
                            <select class="input_control" name="contact_country_code" @input="onChange($event)">
                                <template v-for="country in CountryCodes">
                                    <option :selected="country.dial_code == store.contact_country_code">{{ country.name }} ({{ country.dial_code }})</option>
                                </template>
                            </select>
                            <label>Country Code</label>
                        </div>
                        <div class="form_item">
                            <div class="input_item floating_input w-full">
                                <input type="text" class="input_control" placeholder="Mobile Number *" name="contact_mobile" :value="store.contact_mobile" @input="onChange($event)" >
                                <label>Mobile No *</label>
                            </div>
                            <div class="error" v-if="errors['contact_mobile']">{{errors['contact_mobile'][0]}}</div>
                        </div>
                        <div class="form_item">
                            <div class="input_item floating_input w-full">
                                <input type="text" class="input_control" placeholder="Email ID *" name="contact_email" :value="store.contact_email" @input="onChange($event)">
                                <label>Email ID *</label>
                            </div>
                            <div class="error" v-if="errors['contact_email']">{{errors['contact_email'][0]}}</div>
                        </div>
                    </div>
                </SlideBox>

                <SlideBox btnTitle="GST Number for Business Travel (Optional)" responsive="true" :active="true">
                    <!-- <div class="search_from_history flex justify-between items-center p-4">
                        <div class="input_item floating_input">
                            <input type="text" class="input_control" placeholder="Select from History">
                            <label>Select from History</label>
                        </div>
                        <button class="btn btn-outline color_theme">Clear</button>
                    </div> -->
                    <div class="font14 fw500 px-4 pt-4">To claim credit of GST charged by airlines, Please enter your company's GST number</div>
                    <div class="float-selectbox search_from_history flex justify-between items-center p-4" v-if="store.userInfo && store.userInfo.gstInfos && store.userInfo.gstInfos.length > 0">
                        <div class="input_item floating_input">
                            <i class="fa fa-angle-down fonticon-caret fonticon-caret-positionHandle"></i>
                        <select class="input_control" @change="onGstChange($event)">
                            <option value="">Select from History</option>
                            <option v-for="gst_info in store.userInfo.gstInfos" :value="gst_info.id">
                                {{gst_info.gst_number}} - {{gst_info.gst_company}}
                            </option>
                        </select>
                    </div>
                </div>
                    <div class="grid gap-4 grid-cols-4 p-4">
                        <div class="input_item floating_input w-full">
                            <input type="text" class="input_control" placeholder="Registration Number" name="gst_number" :value="store.gst_number" @input="onChange($event)">
                            <label>Registration Number</label>
                            <div class="error" v-if="errors['gst_number']">{{errors['gst_number'][0]}}</div>
                        </div>
                        <div class="input_item floating_input w-full">
                            <input type="text" class="input_control" placeholder="Reg. Company Name" name="gst_company" :value="store.gst_company" @input="onChange($event)">
                            <label>Reg. Company Name</label>
                            <div class="error" v-if="errors['gst_company']">{{errors['gst_company'][0]}}</div>
                        </div>
                        <div class="input_item floating_input w-full">
                            <input type="text" class="input_control" placeholder="Registered Email" name="gst_email" :value="store.gst_email" @input="onChange($event)">
                            <label>Registered Email</label>
                            <div class="error" v-if="errors['gst_email']">{{errors['gst_email'][0]}}</div>
                        </div>
                        <div class="input_item floating_input w-full">
                            <input type="text" class="input_control" placeholder="Registered Phone" name="gst_phone" :value="store.gst_phone" @input="onChange($event)">
                            <label>Registered Phone</label>
                            <div class="error" v-if="errors['gst_phone']">{{errors['gst_phone'][0]}}</div>
                        </div>
                        <div class="input_item floating_input w-full">
                            <input type="text" class="input_control" placeholder="Registered Address" name="gst_address" :value="store.gst_address" @input="onChange($event)">
                            <label>Registered Address</label>
                            <div class="error" v-if="errors['gst_address']">{{errors['gst_address'][0]}}</div>
                        </div>
                    </div>
                </SlideBox>
            </div>
            <div class="fts_bottom">
                <a class="stp_btn" href="#step_1">Back</a>
                <button class="stp_btn" v-if="this.processing">Processing...</button>
                <button class="stp_btn" @click="proceedToReview" v-else>PROCEED TO REVIEW</button>
            </div>
        </div>
    </div>

    <!-- <button class="stp_btn" @click="goToStep(3)" v-if="store.bookingPassedStep >= 2">Next</button>
    <button class="stp_btn" @click="goToStep(1)">Prev</button> -->
</template>
<script>
import {goToStep, DateFormat, showPrice, showErrorToast} from '../../../utils/commonFuntions.js';
import { store } from '../../../store.js';
import SlideBox from '../SlideBox.vue';
import CountryCodes from '../../../utils/CountryCodes.json';
import axios from "axios";
import { DatePicker } from 'v-calendar';
import 'v-calendar/dist/style.css';
import moment from 'moment';

export default {
    name: "Step2",
    created() {
        if(this.info.tripInfos) {
            let currentObj = this;
            this.info.tripInfos.forEach((tripInfo,index1) => {
                tripInfo.sI.forEach((flightData,index2) => {
                    if(flightData.ssrInfo) {
                        currentObj.ssrInfo = true;
                    }
                });
            });

            var traveDate = new Date();
            if(this.info.tripInfos[0] && this.info.tripInfos[0].sI && this.info.tripInfos[0].sI[0].dt) {
                traveDate = new Date(this.info.tripInfos[0].sI[0].dt);
            }

            this.adultDobMinDate = new Date(moment(traveDate).subtract(100, 'years'));
            this.adultDobMaxDate = new Date(moment(traveDate).subtract(12, 'years'));

            this.childDobMinDate = new Date(moment(traveDate).subtract(12, 'years'));
            this.childDobMaxDate = new Date(moment(traveDate).subtract(2, 'years'));

            this.infantDobMinDate = new Date(moment(traveDate).subtract(2, 'years'));
            this.infantDobMaxDate = new Date();

            this.passportIssueMinDate = new Date(moment(traveDate).subtract(20, 'years'));
            this.passportIssueMaxDate = new Date();

            this.passportExpiryMinDate = new Date(moment(traveDate).add(6, 'months'));
            this.passportExpiryMaxDate = new Date(moment(traveDate).add(20, 'years'));
        }
    },
    data() {
        return {
            dataStep: "Step 2",
            CountryCodes,
            store,
            errors: {},
            adultActive : true,
            processing : false,
            ssrInfo : false,
            adultTabResponsive : false,
            /*PassengerTitle: '',
            PassengerName:'',
            PassengerLastName:'',
            PassengerMobileNumber:'',

            PassengerTitleError: '',
            PassengerNameError:'',
            PassengerLastNameError:'',
            PassengerMobileNumberError:'',*/
            dates:{},
            info : this.info,
        };
    },
    methods: {
        goToStep,
        DateFormat,
        showPrice,
        showErrorToast,
        validate(type){
            var passed = true;
            /*if(type == 'passenger-title' || type == 'all'){
                if(this.PassengerTitle == ''){
                    this.PassengerTitleError = 'Please Enter Title'
                    passed = false;
                }else{
                    this.PassengerTitleError = ''
                    passed = true;
                }
                
            }
            if(type == 'passenger-name' || type == 'all'){
                if(this.PassengerName == ''){
                    this.PassengerNameError = 'Please Enter Name'
                    passed = false;
                }else{
                    this.PassengerNameError = ''
                    passed = true;
                }
            }
            if(type == 'passenger-last-name' || type == 'all'){
                if(this.PassengerLastName == ''){
                    this.PassengerLastNameError = 'Please Enter Last Name'
                    passed = false;
                }else{
                    this.PassengerLastNameError = ''
                    passed = true;
                }
            }
            if(type == 'passenger-mobile-number' || type == 'all'){
                if(this.PassengerMobileNumber == ''){
                    this.PassengerMobileNumberError = 'Please Enter Last Name'
                    passed = false;
                }else{
                    this.PassengerMobileNumberError = ''
                    passed = true;
                }
            }*/
            return passed;
        },
        onChange(event) {
            // console.log(event.target.name);
            var name = event.target.name;
            store[name] = event.target.value;
        },
        onGstChange(event) {
            // console.log(event.target.name);
            var gst_id = event.target.value;
            var gst_number = '';
            var gst_company = '';
            var gst_email = '';
            var gst_phone = '';
            var gst_address = '';
            if(gst_id && store.userInfo && store.userInfo.gstInfos) {
                store.userInfo.gstInfos.forEach((gst_info,index) => {
                    if(gst_info.id == gst_id) {
                        gst_number = gst_info.gst_number;
                        gst_company = gst_info.gst_company;
                        gst_email = gst_info.gst_email;
                        gst_phone = gst_info.gst_phone;
                        gst_address = gst_info.gst_address;
                    }
                });
            }
            store.gst_number = gst_number;
            store.gst_company = gst_company;
            store.gst_email = gst_email;
            store.gst_phone = gst_phone;
            store.gst_address = gst_address;
        },
        onPassengersChange(event) {
            var passengers = store.passengers
            if(event?.target?.name){
                var name = event.target.name;
                passengers[name] = event.target.value;
                store.passengers = passengers;
            }else{
                store.passengers = {...store.passengers, ...event}
            }
        },
        proceedToReview() {
            var currentObj = this;
            currentObj.processing = true;
            currentObj.errors = {};
            axios.post('/flight/validate_passengers', {
                price_id: this.price_id,
                paxInfo: this.info.searchQuery.paxInfo,
                passengers: store.passengers,
                contact_country_code: store.contact_country_code,
                contact_mobile: store.contact_mobile,
                contact_email: store.contact_email,
                gst_number: store.gst_number,
                gst_company: store.gst_company,
                gst_email: store.gst_email,
                gst_phone: store.gst_phone,
                gst_address: store.gst_address,
            })  
            .then(function (response) {  
                // currentObj.output = response.data;
                if(response.data.success) {
                    currentObj.adultActive = true
                    if(store.bookingPassedStep < 2){
                        store.bookingPassedStep = 2
                    }
                    goToStep(3);

                    /*this.adultActive = false
                    if(this.validate('all')){
                        if(store.bookingPassedStep < 2){
                            store.bookingPassedStep = 2
                        }
                        goToStep(3)    
                    }
                    var isFildsValidated = this.validate('passenger-title') && this.validate('passenger-name') && this.validate('passenger-last-name');
                    if(!isFildsValidated){
                        setTimeout(() => {
                            this.adultActive = true;
                            setTimeout(() => {
                                this.$refs.flightRef.scrollIntoView();
                            }, 1);
                        }, 1);
                    }else{
                        this.adultActive = true
                    }*/                    
                } else {
                    alert(response.data.message);
                }
                currentObj.processing = false;
                // console.log('running in then');
            })  
            .catch(function (error) {
                currentObj.errors = error.response.data.errors;
                // console.log('running in errors');
                // console.log(currentObj.errors);
                setTimeout(() => {
                    setTimeout(() => {
                        currentObj.$refs.flightRef.scrollIntoView();
                    }, 1);
                }, 1);
                currentObj.processing = false;

                if(currentObj.tripInfos) {
                    currentObj.tripInfos.forEach((error,index) => {
                        currentObj.showErrorToast(error['message']);
                    });
                }
            });            
        },
        isDobRequired(paxType) {
            let dobRequired = false;
            if(paxType == 'INFANT') {
                dobRequired =  true;
            }
            dobRequired =  true;
            if(this.info?.conditions?.pcs) {
                dobRequired =  false;
            }
            return dobRequired;
        }
    },
    watch:{
        dates: {
            handler: function(dates) {
                var DatesObject = {}
                for (const date in dates) {
                    DatesObject[date] = dates[date]?.toLocaleDateString();
                }
                this.onPassengersChange(DatesObject)
            },
            deep: true
        },
        errors: {
            handler: function(errors){

                if(Object.keys(errors).length > 0){
                    var passangerTabs = [...document.querySelectorAll("[data-tab-box]")];
                    passangerTabs.forEach((item)=>{
                        if(item.querySelector('.slide_btn').classList.contains('active')){
                            setTimeout(() => {
                                var contentHeight = item.querySelector('.slide_content').offsetHeight;
                                item.querySelector('.slide-up-down__container').style.height = contentHeight + 'px';
                                item.querySelector('.slide-up-down__container').style.setProperty('--content-height', contentHeight);
                                
                            }, 100);
                        }
                    });
                    
                }
            },
            deep: true
        }
    },
    components: { SlideBox, DatePicker },
    props: ["info","price_id"],
}
</script>