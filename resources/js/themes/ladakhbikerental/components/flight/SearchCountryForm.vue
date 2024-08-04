<template>
    <search-wrapper class="book_flight_form">
        <div class="tabs">
            <button 
                class="tab-btn" 
                :class="tripType == 0 ? 'active' : ''" 
                @click="setActiveTab(0); todateSelectEnabled = false;"
                id="oneway-btn"
            >ONE WAY</button>

            <button 
                class="tab-btn" 
                id="roundtrip-btn"
                :class="tripType == 1 ? 'active' : ''" 
                @click="setActiveTab(1); todateSelectEnabled = true;"
            >ROUND TRIP</button>

            <button 
                class="tab-btn" 
                id="multicity-btn"
                :class="tripType == 2 ? 'active' : ''" 
                @click="setActiveTab(2)"
            >MULTI CITY</button>
        </div>
        <form class="flight_form" @submit="handleFormSubmit">
            <div class="select_from_wrap">
                <ModelListSelect :list="fromCountryList" v-model="fromCountry" @Input="(e)=>handleAirportChange(e,'from')" option-value="code" :customText="(item)=>handleCustomText(item)" option-text="city" placeholder="Where From ?" />
                <span class="err" v-if="this.errors['fromCountryError']">{{this.errors['fromCountryError']}}</span>
            </div>
            <div class="swap-icon" @click="swapAirports(0)">
                <button class="swap_btn" type="button"><i class="fa-solid fa-right-left"></i></button>
            </div>
            <div class="select_to_wrap">
                <ModelListSelect :list="toCountryList" v-model="toCountry" @Input="(e)=>handleAirportChange(e,'to')" option-value="code" :customText="(item)=>handleCustomText(item)" option-text="city" placeholder="Where To ?" />
                <span class="err" v-if="this.errors['toCountryError']">{{this.errors['toCountryError']}}</span>
            </div>
            <div class="ssb-wrap" :class="tripType == 1 ? 'round_trip' : tripType == 2 ? 'multicity_trip' : ''">
                <DatePicker columns="2" v-model="departureDate" mode="date" class="date_wrap" :min-date="new Date()" >
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="Select Date"
                        />
                    </template>
                </DatePicker>
                <DatePicker v-if="tripType == 1" columns="2"  v-model="returnDate" mode="date" class="date_wrap" :min-date="(this.departureDate)?new Date(this.departureDate):new Date()">
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="Return Date"
                        />
                    </template>
                </DatePicker>

                <div class="ssb_errors">
                    <span class="err from_date_err" v-if="this.errors['fromDateError']">{{this.errors['fromDateError']}}</span>
                    <span class="err to_date_err" v-if="this.errors['toDateError']">{{this.errors['toDateError']}}</span>
                </div>
            </div>

            <div class="passenger_wrap" >
                <div class="passenger-economy" @click.prevent="togglePassengerPopup">
                    <i class="fa-solid fa-wheelchair"></i>
                    <div class="passenger-txt">
                        <div class="passenger-label">Passenger and Class</div>
                        <input type="text" :value="passangers.total + ' Passenger | ' + BookingClass" disabled>
                    </div>
                </div>
                <div class="passenger_pop" :class="passengerPopup ? 'active':''">

                        <div class="passenger-left">
                            <h3 class="font17">Select Passenger</h3>
                            <div class="pl_item">
                                <h4 class="font15 fw500">Adult <span class="font15 fw400 color_dark600">Age 12+</span></h4>
                                <ul>
                                    <li v-for="counter in 9"><label><input type="radio" name="adult_radio" @change="handleAdultAge" :value="counter" :checked="this.passangers.adult==counter"/><span>{{counter}}</span></label></li>
                                </ul> 
                            </div>
                            <div class="pl_item">
                                <h4 class="font15 fw500">Children <span class="font15 fw400 color_dark600">Age 2-12</span></h4>
                                <ul>
                                    <li><label><input type="radio" name="children_radio" @change="handleChildrenAge" value="0"/><span>0</span></label></li>
                                    <li v-for="counter in 8"><label><input type="radio" name="children_radio" @change="handleChildrenAge" :value="counter" :checked="this.passangers.children==counter"/><span>{{counter}}</span></label></li>
                                </ul> 
                            </div>
                            <div class="pl_item">
                                <h4 class="font15 fw500">Infant <span class="font15 fw400 color_dark600">Age 0-2</span></h4>
                                <ul>
                                    <li><label><input type="radio" name="infant_radio" @change="handleInfantAge" value="0"/><span>0</span></label></li>
                                    <li v-for="counter in 8"><label><input type="radio" name="infant_radio" @change="handleInfantAge" :value="counter" :checked="this.passangers.infant==counter"/><span>{{counter}}</span></label></li>
                                </ul> 
                            </div>
                        </div>
                        <div class="passenger-right">
                            <h4 class="font17 fw500 d-flex">Select Class <span class="close_passenger" @click="passengerPopup = false"><i class="fa-solid fa-xmark"></i></span></h4>
                            <ul>
                                <li><label class="font15"><span>Economy</span> <input type="radio" name="flight-class" value="Economy" @change="handleEconomy" :checked="this.BookingClass=='Economy'"></label></li>
                                <li><label class="font15"><span>Premium Economy</span> <input type="radio" name="flight-class" value="Premium Economy" @change="handleEconomy" :checked="this.BookingClass=='Premium Economy'"></label></li>
                                <li><label class="font15"><span>Business</span> <input type="radio" name="flight-class" value="Business" @change="handleEconomy" :checked="this.BookingClass=='Business'"></label></li>
                                <li><label class="font15"><span>First</span> <input type="radio" name="flight-class" value="First" @change="handleEconomy" :checked="this.BookingClass=='First'"></label></li>
                            </ul>
                            <hr>
                            <div class="passenger-select" @click.prevent="togglePassengerPopup">
                            <div class="passenger-txt">
                            <div class="passenger-label">Done</div>
                            </div>
                            </div>
                        </div>
                        
                </div>
            </div>

            <div class="search_btn">
                <button type="submit">Search</button>
            </div>

            <div class="form_items_wrap">
                <div class="form_item" v-if="tripType == 2" v-for="counter in multicityCounter" :key="multicityCounter">
                    <div class="select_from_wrap">
                        <ModelListSelect :list="multifromCountryList[counter]" v-model="multicity[`origin_${counter}`]" @Input="(e)=>handleAirportChange(e,'from',counter)" option-value="code" :customText="(item)=>handleCustomText(item)" option-text="city" placeholder="Where From ?" />
                        <span class="err" v-if="this.errors[`fromCountryError${counter}`]">{{this.errors[`fromCountryError${counter}`]}}</span>
                    </div>
                    <div class="swap-icon" @click="swapAirports(counter)">
                        <button class="swap_btn" type="button"><i class="fa-solid fa-right-left"></i></button>
                    </div>
                    <div class="select_to_wrap">
                        <ModelListSelect :list="multitoCountryList[counter]" v-model="multicity[`destination_${counter}`]"  @Input="(e)=>handleAirportChange(e,'to',counter)" option-value="code" :customText="(item)=>handleCustomText(item)" option-text="city" placeholder="Where To ?" />
                        <span class="err" v-if="this.errors[`toCountryError${counter}`]">{{this.errors[`toCountryError${counter}`]}}</span>
                    </div>
                    <div class="ssb-wrap" 
                    :class="(this.errors[`toDateError${counter}`] || this.errors[`fromDateError${counter}`]) ? 'hasError' : ''"
                    >
                        <DatePicker v-model="multicity[`depDate_${counter}`]" mode="date" columns="2" class="date_wrap" :min-date="(multicity[`depDate_${counter-1}`])?new Date(multicity[`depDate_${counter-1}`]):(this.departureDate)?new Date(this.departureDate):new Date()">
                            <template v-slot="{ inputValue, inputEvents, togglePopover  }">
                                <input
                                class="date_input"
                                :value="inputValue"
                                @click="togglePopover"
                                placeholder="Select Date"
                                />
                            </template>
                        </DatePicker>
                        <div class="ssb_errors">
                            <span class="err from_date_err" v-if="this.errors[`fromDateError${counter}`]">{{this.errors[`fromDateError${counter}`]}}</span>
                            <span class="err to_date_err" v-if="this.errors[`toDateError${counter}`]">{{this.errors[`toDateError${counter}`]}}</span>
                        </div>
                    </div>
                    <div class="search_btn">
                        <button v-if="counter != 1 && counter==multicityCounter" type="button" @click="handleRemoveMore"><i class="fa-solid fa-xmark text-black"></i></button>
                        <button v-if="counter==multicityCounter && multicityCounter != 5" type="button" @click="handleAddOneMore">Add One More</button>
                    </div>
                </div>
            </div>
        </form>
    </search-wrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import {isNumeric, showCabinClass} from '../../utils/commonFuntions.js';
import { SetupCalendar, Calendar, DatePicker } from 'v-calendar';
import moment from 'moment';
import { store } from '../../store';
import { ModelListSelect } from 'vue-search-select';
import axios from "axios";
import 'v-calendar/dist/style.css';
import 'vue-search-select/dist/VueSearchSelect.css';

import OneWayPageLoader from '../../skeletons/oneWayPageLoader.vue';
import { FlightSearchWrapper } from "../../styles/FlightSearchWrapper";


export default {
    name : 'SearchCountryForm',
    created() {
        // console.log('this.routeInfos=',this.routeInfos);
        let TripType = this.TripType;
        this.tripType = TripType;

        if(this.routeInfos) {
            let multicity = {};
            this.routeInfos.forEach((routeInfo,index)=>{
                // console.log('this.routeInfo=',this.routeInfo);
                if(TripType == 0) {
                    if(index==0) {
                        this.fromCountry = routeInfo.fromCityOrAirport;
                        this.toCountry = routeInfo.toCityOrAirport;
                        this.departureDate = routeInfo.travelDate;
                        this.fromCountryList = this.airportLists;
                        this.toCountryList = this.airportLists;
                    }
                } else if(TripType == 1) {
                    if(index==0) {
                        this.fromCountry = routeInfo.fromCityOrAirport;
                        this.toCountry = routeInfo.toCityOrAirport;
                        this.departureDate = routeInfo.travelDate;
                    }
                    if(index==1) {
                        // this.fromCountry = routeInfo.fromCityOrAirport;
                        // this.toCountry = routeInfo.toCityOrAirport;
                        this.returnDate = routeInfo.travelDate;
                    }

                    this.fromCountryList = this.airportLists;
                    this.toCountryList = this.airportLists;
                } else {
                    if(index==0) {
                        this.fromCountry = routeInfo.fromCityOrAirport;
                        this.toCountry = routeInfo.toCityOrAirport;
                        this.departureDate = routeInfo.travelDate;

                        this.fromCountryList = this.airportLists;
                        this.toCountryList = this.airportLists;
                    } else {
                        multicity[`origin_${index}`] = routeInfo.fromCityOrAirport;
                        multicity[`destination_${index}`] = routeInfo.toCityOrAirport;
                        multicity[`depDate_${index}`] = routeInfo.travelDate;

                        this.multifromCountryList[index] = this.airportLists;
                        this.multitoCountryList[index] = this.airportLists;
                    }
                }
            });
            if(Object.keys(multicity).length > 0){
                this.multicity = multicity;
            }

            if(TripType==2 && this.routeInfos && this.routeInfos.length) {
                this.multicityCounter = this.routeInfos.length-1;
            }
        }

        // console.log('this.paxInfo=',this.paxInfo);
        if(this.paxInfo){
            let paxInfo = this.paxInfo;
            if(paxInfo.ADULT) {
                this.passangers.adult = parseInt(paxInfo.ADULT);
            }
            if(paxInfo.CHILD) {
                this.passangers.children = parseInt(paxInfo.CHILD);
            }
            if(paxInfo.INFANT) {
                this.passangers.infant = parseInt(paxInfo.INFANT);
            }
        }
        if(this.cabinClass) {
            this.BookingClass = this.showCabinClass(this.cabinClass);
        }
    },
    data() {
        return {
            tripType: 0,
            multicityCounter: 1,
            multicity: {
                'origin_1':{'city':'','code':''},
                'destination_1':{'city':'','code':''},
                'depDate_1':'',
            },
            // countryList: this.airportLists,
            fromCountryList: this.airportLists,
            toCountryList: this.airportLists,

            multifromCountryList: [
                this.airportLists,
                this.airportLists,
            ],
            multitoCountryList: [
                this.airportLists,
                this.airportLists,
            ],
            fromCountryDropdown: false,
            toCountryDropdown: false,
            fromDateCalender: false,
            toDateCalender: false,
            passengerPopup: false,

            todateSelectEnabled : false,

            fromCountry:{},
            toCountry:{},
            departureDate:'',
            returnDate:'',
            passangers: {
                adult: 1,
                children: 0,
                infant: 0,
                total: 1,
            },
            BookingClass : 'Economy',

            fromCountryError:'',
            toCountryError:'',
            FromDateError:'',
            ToDateError:'',
            errors:{},
            isSearched: false,
            processing: false,
            loading:false
        }
    },
    methods: {
        isNumeric,
        showCabinClass,
        setActiveTab(tabType) {
            this.tripType = tabType;
            store.tripType = tabType;
            if(this.isSearched){this.handleErrors();}
        },
        swapAirports(counter=0) {
            // console.log('swapAirports.counter=',counter);
            if(counter && isNumeric(counter) && counter > 0) {
                var old_multicity = this.multicity;
                let fromCountry = old_multicity[`origin_${counter}`];
                old_multicity[`origin_${counter}`] = old_multicity[`destination_${counter}`];
                old_multicity[`destination_${counter}`] = fromCountry;
                this.multicity = old_multicity;
            } else {
                let fromCountry = this.fromCountry;
                this.fromCountry = this.toCountry;
                this.toCountry = fromCountry;
            }
        },
        handleCustomText(item){
            return item.city+' ('+item.code+')' + '||' + item.name;
        },
        togglefromCountryDropdown (counter) {
            if(counter && isNumeric(counter)) {
                var old_multicity = this.multicity;
                if(old_multicity[`fromCountryDropdown${counter}`]) {
                    old_multicity[`fromCountryDropdown${counter}`] = false;
                } else {
                    old_multicity[`fromCountryDropdown${counter}`] = true;
                }
                this.multicity = old_multicity;
            } else {
                this.fromCountryDropdown = !this.fromCountryDropdown
            }
            
        },
        toggleToCountryDropdown (counter) {
            if(counter && isNumeric(counter)) {
                var old_multicity = this.multicity;
                if(old_multicity[`toCountryDropdown${counter}`]) {
                    old_multicity[`toCountryDropdown${counter}`] = false;
                } else {
                    old_multicity[`toCountryDropdown${counter}`] = true;
                }
                this.multicity = old_multicity;
            } else {
                this.toCountryDropdown = !this.toCountryDropdown
            }
        },
        togglefromDateCalender (counter) {
           if(counter && isNumeric(counter)) {
                var old_multicity = this.multicity;
                if(old_multicity[`fromDateCalender${counter}`]) {
                    old_multicity[`fromDateCalender${counter}`] = false;
                } else {
                    old_multicity[`fromDateCalender${counter}`] = true;
                }
                this.multicity = old_multicity;
            } else {
                this.fromDateCalender = !this.fromDateCalender
            }
        },
        toggleToDateCalender (e) {
            this.toDateCalender = !this.toDateCalender;
        },
        togglePassengerPopup (e) {
            this.passengerPopup = !this.passengerPopup
        },

        handleToCountry(airline, counter){
            if(counter) {
                var old_multicity = this.multicity;
                old_multicity[`toCountryDropdown${counter}`] = false;
                old_multicity[`destination_${counter}`] = airline;
                this.multicity = old_multicity;
            } else {
                this.toCountryDropdown = false; 
                this.toCountry = airline;
                this.toCountryError = ''
                if(this.tripType==2) {
                    var old_multicity = this.multicity;
                    if(old_multicity['origin_1'] && old_multicity['origin_1']['code'] == '') {
                        old_multicity['origin_1'] = airline;
                    }
                    this.multicity = old_multicity;
                }
            }
        },
        handleFromCountry(airline, counter){
            // console.log('handleFromCountry.airline=',airline);
            if(counter) {
                var old_multicity = this.multicity;
                old_multicity[`fromCountryDropdown${counter}`] = false;
                old_multicity[`origin_${counter}`] = airline;
                this.multicity = old_multicity;
            } else {
                this.fromCountryDropdown = false; 
                this.fromCountry = airline;
                this.fromCountryError = '';                
            }
        },
        fromDateSelect(value, counter){
            // console.log('fromDateSelect.value=',value)
            // console.log('fromDateSelect.counter=',counter)
            if(counter) {
                var dateString = value.date.toLocaleDateString().split('/');
                var dateSel = dateString[1] + '/' + dateString[0] + '/' + dateString[2]
                // this.departureDate = dateSel;
                // this.fromDateCalender = false;
                // this.FromDateError = ''

                var old_multicity = this.multicity;
                old_multicity[`depDate_${counter}`] = dateSel;
                old_multicity[`fromDateCalender${counter}`] = false;
                old_multicity[`fromDateError${counter}`] = '';
                this.multicity = old_multicity;
            } else {
                var dateString = value.date.toLocaleDateString().split('/');
                var dateSel = dateString[1] + '/' + dateString[0] + '/' + dateString[2]
                this.departureDate = dateSel;
                this.fromDateCalender = false;
                this.FromDateError = ''                
            }
        },
        toDateSelect(value){
            var dateString = value.date.toLocaleDateString().split('/');
            var dateSel = dateString[1] + '/' + dateString[0] + '/' + dateString[2]
            this.returnDate = dateSel;
            this.toDateCalender = false;
            this.todateSelectEnabled = true;
            this.tripType = 1;
            this.ToDateError = '';
        },
        
        handleAdultAge(el){
            this.passangers.adult = parseInt(el.target.value)
        },
        handleChildrenAge(el){
            this.passangers.children = parseInt(el.target.value)
        },
        handleInfantAge(el){
            this.passangers.infant = parseInt(el.target.value)
        },
        handleEconomy(el){
            this.BookingClass = el.target.value
        },
        handleAddOneMore(){
            var multicityCounter = this.multicityCounter + 1;
            this.multicityCounter = multicityCounter;

            var old_multicity = this.multicity;
            var new_multicity = {};
            for (var counter = 1; counter <= multicityCounter; counter++) {
                if(old_multicity[`origin_${counter}`]) {
                    new_multicity[`origin_${counter}`] = old_multicity[`origin_${counter}`];
                } else {
                    if(old_multicity[`destination_${counter-1}`]) {
                        new_multicity[`origin_${counter}`] = old_multicity[`destination_${counter-1}`];
                    } else {
                        new_multicity[`origin_${counter}`] = {'city':'','code':''};
                    }
                }
                if(old_multicity[`destination_${counter}`]) {
                    new_multicity[`destination_${counter}`] = old_multicity[`destination_${counter}`]??'';
                } else {
                    new_multicity[`destination_${counter}`] = {'city':'','code':''};
                }
                if(old_multicity[`fromitem_${counter}`]) {
                    new_multicity[`fromitem_${counter}`] = old_multicity[`fromitem_${counter}`]??'';
                } else {
                    new_multicity[`fromitem_${counter}`] = {};
                }
                if(old_multicity[`toitem_${counter}`]) {
                    new_multicity[`toitem_${counter}`] = old_multicity[`toitem_${counter}`]??'';
                } else {
                    new_multicity[`toitem_${counter}`] = {};
                }
                new_multicity[`depDate_${counter}`] = old_multicity[`depDate_${counter}`]??'';                
            }
            this.multicity = new_multicity;
            this.multifromCountryList[multicityCounter] = this.airportLists;
            this.multitoCountryList[multicityCounter] = this.airportLists;
        },
        handleRemoveMore(){
            var multicityCounter = this.multicityCounter - 1;
            this.multicityCounter = multicityCounter;

            var old_multicity = this.multicity;
            var new_multicity = {};
            for (var counter = 1; counter <= multicityCounter; counter++) {
                if(old_multicity[`origin_${counter}`]) {
                    new_multicity[`origin_${counter}`] = old_multicity[`origin_${counter}`];
                } else {
                    new_multicity[`origin_${counter}`] = {'city':'','code':''};
                }
                if(old_multicity[`destination_${counter}`]) {
                    new_multicity[`destination_${counter}`] = old_multicity[`destination_${counter}`]??'';
                } else {
                    new_multicity[`destination_${counter}`] = {'city':'','code':''};
                }
                new_multicity[`depDate_${counter}`] = old_multicity[`depDate_${counter}`]??'';
            }
            this.multicity = new_multicity;
        },
        handleErrors(){
            var errors = {};
            var form_data = {
                ADT: this.passangers.adult,
                CHD: this.passangers.children,
                INF: this.passangers.infant,
                tripType: this.tripType,
                pClass : this.BookingClass
            }
            
            if(store.tripType == 2) {
                if(this.fromCountry.code) {
                    form_data['origin_0'] = this.fromCountry.code;
                    delete errors['fromCountryError'];
                } else {
                    errors['fromCountryError'] = 'Origin is required';
                }
                if(this.toCountry.code) {
                    form_data['destination_0'] = this.toCountry.code;
                    delete errors['toCountryError'];
                } else {
                    errors['toCountryError'] = 'Destination is required';
                }
                if(this.departureDate) {
                    form_data['depDate_0'] = moment(this.departureDate).format('YYYY-MM-DD');
                    delete errors['fromDateError'];
                } else {
                    errors['fromDateError'] = 'Departure Date is required';
                }

                var multicity = this.multicity;
                var multicityCounter = this.multicityCounter;
                for (var counter = 1; counter <= multicityCounter; counter++) {
                    if(multicity[`origin_${counter}`]['code']) {
                        form_data[`origin_${counter}`] = multicity[`origin_${counter}`]['code'];
                        delete errors[`fromCountryError${counter}`];
                    } else {
                        errors[`fromCountryError${counter}`] = 'Origin is required'
                    }
                    if(multicity[`destination_${counter}`]['code']) {
                        form_data[`destination_${counter}`] = multicity[`destination_${counter}`]['code'];
                        delete errors[`toCountryError${counter}`];
                    } else {
                        errors[`toCountryError${counter}`] = 'Destination is required';
                    }
                    if(multicity[`depDate_${counter}`]) {
                        form_data[`depDate_${counter}`] = moment(multicity[`depDate_${counter}`]).format('YYYY-MM-DD');
                        delete errors[`fromDateError${counter}`];
                    } else {
                        errors[`fromDateError${counter}`] = 'Departure Date is required';
                    }
                }
            } else {
                if(this.fromCountry.code) {
                    form_data['from'] = this.fromCountry.code;
                    delete errors['fromCountryError'];
                } else {
                    errors['fromCountryError'] = 'Origin is required';
                }
                if(this.toCountry.code) {
                    form_data['to'] = this.toCountry.code;
                    delete errors['toCountryError'];
                } else {
                    errors['toCountryError'] = 'Destination is required';
                }
                if(this.departureDate) {
                    form_data['dep'] =  moment(this.departureDate).format('YYYY-MM-DD');
                    delete errors['fromDateError'];
                } else {
                    errors['fromDateError'] = 'Departure Date is required';
                }
                if(store.tripType == 1) {
                    if(this.returnDate) {
                        form_data['ret'] = moment(this.returnDate).format('YYYY-MM-DD');
                        delete errors['toDateError'];
                    } else {
                        errors['toDateError'] = 'Return Date is required';
                    }
                }
            }
            this.errors = errors;
            return form_data;
        },
        handleFormSubmit(e){
            e.preventDefault();
            this.isSearched = true;
            var form_data = this.handleErrors();
            // console.log('errors=',errors);
            // console.log(this.errors)
            if(Object.keys(this.errors).length <= 0){
                this.loading = true;
                this.errors = {};
                store.tripType = this.tripType;
                this.$inertia.get(`/flight/list`, form_data);
            }
        },
        close (el) {
                if(!el.target.closest('.passenger_wrap')){
                    this.passengerPopup = false
                }
        },
        handleAirportChange (e,origin,counter) {
            var currentObj = this;
            // currentObj.processing = true;
            // currentObj.errors = {};
            var value = e.target.value;
            if(value && value.length >= 3) {
                axios.post('/flight/search_airports', {
                    value: value,
                })
                .then(function (response) {
                    // currentObj.output = response.data;
                    if(response.data.success) {
                        if(counter) {
                            if(origin == 'from') {
                                currentObj.multifromCountryList[counter] = response.data.airportLists;
                            } else if(origin == 'to')  {
                                currentObj.multitoCountryList[counter] = response.data.airportLists;
                            }
                        } else {
                            if(origin == 'from') {
                                currentObj['fromCountryList'] = response.data.airportLists;
                            } else if(origin == 'to') {
                                currentObj['toCountryList'] = response.data.airportLists;
                            }
                        }
                        
                    } else if(response.data.message) {
                        alert(response.data.message);
                    }
                    // currentObj.processing = false;
                })
                .catch(function (error) {
                    // currentObj.errors = error.response.data.errors;
                    // setTimeout(() => {
                    //     setTimeout(() => {
                    //         currentObj.$refs.flightRef.scrollIntoView();
                    //     }, 1);
                    // }, 1);
                    // currentObj.processing = false;
                });
            }
        }
    },
    mounted () {
        document.addEventListener('click', this.close)
    },
    beforeDestroy () {
        document.removeEventListener('click', this.close)
    },
    watch:{
        passangers: {
            handler: function(newValue) {
                this.passangers.total = this.passangers.adult + this.passangers.children + this.passangers.infant;
            },
            deep: true
        },
        departureDate :{
            handler: function(newValue){
                if(this.isSearched){this.handleErrors();}
            }
        },
        returnDate:{
            handler: function(newValue){
                if(this.isSearched){this.handleErrors();}
            }
        },
        fromCountry: {
            handler: function(newValue) {
                // console.log(newValue);
                if(this.isSearched){this.handleErrors();}
                // this.fromitem.total = this.passangers.adult + this.passangers.children + this.passangers.infant;
            },
            deep: true
        },
        toCountry: {
            handler: function(newValue) {
                // console.log(newValue);
                if(this.isSearched){this.handleErrors();}
                // this.fromitem.total = this.passangers.adult + this.passangers.children + this.passangers.infant;
            },
            deep: true
        },
        multicity:{
            handler: function(newValue) {
                // console.log(newValue);
                if(this.isSearched){this.handleErrors();}
                
                // this.fromitem.total = this.passangers.adult + this.passangers.children + this.passangers.infant;
            },
            deep: true
        },
        loading :{
            immediate: true,
            handler: function(newValue){
                if(this.loading){
                    store.loadingName = 'searchForm'
                }else{
                    store.loadingName = false
                }
            }
        }
    },
    components: {
        Link,
        SetupCalendar,
        Calendar,
        DatePicker,
        ModelListSelect,
        OneWayPageLoader,
        'search-wrapper' : FlightSearchWrapper
    },
    props: ["airportLists", "TripType", "routeInfos", "paxInfo", "cabinClass"],
    }
</script>