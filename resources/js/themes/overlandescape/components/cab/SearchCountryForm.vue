<template>

<search-wrapper class="book_flight_form" :class="`trip-type-${tripType}`">
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
        >SIGHTSEEING</button>
        <button
            class="tab-btn"
            id="multicity-btn"
            :class="tripType == 3 ? 'active' : ''"
            @click="setActiveTab(3)"
        >AIRPORT</button>
    </div>
    <form class="flight_form" @submit="handleFormSubmit">

        <template v-if="tripType == 3">
            <div class="trp_box">
                <div class="trip-type relative">
                    <label>TRIP</label>
                    <select @change="handlePickup">
                        <option value="0" :selected="pickupType == 0"> Pickup from Airport </option>
                        <option value="1" :selected="pickupType == 1"> Drop to Airport </option>
                    </select>
                    <i class="fa-solid fa-caret-down absolute right-2 text-xs"></i>
                </div>
            </div>

            <div class="select_from_wrap form_item mr-2">
                <ModelListSelect :list="fromAirportList" v-model="fromAirport" @Input="(e)=>handleAirportChange(e,'fromAirport')" option-value="id" :customText="(item)=>handleCustomText(item)" name="fromAirport" option-text="city" :placeholder="pickupType == 0 ? 'PICKUP AIRPORT' : 'DROP AIRPORT'" />
                <span class="err" v-if="this.errors['fromCountryError']">{{this.errors['fromCountryError']}}</span>
            </div>

            <div class="select_from_wrap form_item">
                <ModelListSelect :list="toAirportList" v-model="toAirport" @Input="(e)=>handleAirportChange(e,'toAirport')" option-value="id" :customText="(item)=>handleCustomText(item)" name="toAirport" option-text="city" :placeholder="(pickupType == 0 ? 'DROP' : 'PICKUP') + ' CITY'" />

                <span class="err" v-if="this.errors['toCountryError']">{{this.errors['toCountryError']}}</span>
            </div>

            <div class="ssb-wrap" :class="tripType == 1 ? 'round_trip' : tripType == 2 ? 'multicity_trip' : ''">
                <DatePicker columns="2" v-model="departureDate" mode="date" class="date_wrap" :min-date="this.departureMinDate" >
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="Select Date"
                        />
                    </template>
                </DatePicker>
              <!--   <DatePicker v-if="tripType == 1" columns="2"  v-model="returnDate" mode="date" class="date_wrap" :min-date="(this.departureDate)?new Date(this.departureDate):new Date()">
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="Return Date"
                        />
                    </template>
                </DatePicker> -->

                <div class="ssb_errors">
                    <span class="err from_date_err" v-if="this.errors['fromDateError']">{{this.errors['fromDateError']}}</span>
                    <span class="err to_date_err" v-if="this.errors['toDateError']">{{this.errors['toDateError']}}</span>
                </div>
            </div>

            <div class="trp_box">
                <div class="trip-type relative">
                    <label>PICK UP AT</label>
                    <select class="pickup-time" @change="this.handleTimeChange">
                        <option value=""> Select Time </option>
                        <template v-if="this.tripTimeArr">
                            <template v-for="tripTimeRow,index in this.tripTimeArr">
                                <option :value="tripTimeRow.key" :selected="tripTimeRow.key==time">{{tripTimeRow.title}}</option>
                            </template>
                        </template>
                    </select>
                    <i class="fa-solid fa-caret-down absolute right-2 text-xs"></i>
                </div>
                <span class="err" v-if="this.errors['timeError']">{{this.errors['timeError']}}</span>
            </div>

        </template>

        <template v-else>

            <div class="select_from_wrap" v-if="tripType == 2">
                <ModelListSelect :list="sightseeningDestinationLists" v-model="fromCountry" @Input="(e)=>handleAirportChange(e,'from')" option-value="name" :customText="(item)=>handleCustomText(item)" option-text="city" placeholder="Where From ?" />
                    <span class="err" v-if="this.errors['fromCountryError']">{{this.errors['fromCountryError']}}</span>
            </div>

            <div class="select_from_wrap" v-else>
                <ModelListSelect :list="fromCountryList" v-model="fromCountry" @Input="(e)=>handleAirportChange(e,'from')" option-value="name" :customText="(item)=>handleCustomText(item)" option-text="city" placeholder="Where From ?" />
                <span class="err" v-if="this.errors['fromCountryError']">{{this.errors['fromCountryError']}}</span>
            </div>

            <div class="swap-icon" @click="swapAirports(0)" v-if="tripType != 2">
                <button class="swap_btn" type="button"><i class="fa-solid fa-right-left"></i></button>
            </div>


            <div class="select_to_wrap" v-if="tripType != 2">
                <ModelListSelect :list="toCountryList" v-model="toCountry" @Input="(e)=>handleAirportChange(e,'to')" option-value="name" :customText="(item)=>handleCustomText(item)" option-text="city" placeholder="Where To ?" />
                <span class="err" v-if="this.errors['toCountryError']">{{this.errors['toCountryError']}}</span>
            </div>

            <div class="ssb-wrap" :class="tripType == 1 ? 'round_trip' : tripType == 2 ? 'multicity_trip' : ''">
                <DatePicker columns="2" v-model="departureDate" mode="date" class="date_wrap" :min-date="this.departureMinDate" >
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="Select Date"
                        />
                    </template>
                </DatePicker>
             <!--    <DatePicker v-if="tripType == 1" columns="2"  v-model="returnDate" mode="date" class="date_wrap" :min-date="(this.departureDate)?new Date(this.departureDate):new Date()">
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="Return Date"
                        />
                    </template>
                </DatePicker>
     -->
                <div class="ssb_errors">
                    <span class="err from_date_err" v-if="this.errors['fromDateError']">{{this.errors['fromDateError']}}</span>
                    <span class="err to_date_err" v-if="this.errors['toDateError']">{{this.errors['toDateError']}}</span>
                </div>
            </div>

            <div class="passenger_wrap" >
                <div class="passenger-economy" >
                    <i class="fa-regular fa-clock"></i>
                    <div class="passenger-txt">
                        <select class="pickup-time" @change="this.handleTimeChange">
                            <option value=""> Select Time </option>
                            <template v-if="this.tripTimeArr">
                                <template v-for="tripTimeRow,index in this.tripTimeArr">
                                    <option :value="tripTimeRow.key" :selected="tripTimeRow.key==time">{{tripTimeRow.title}}</option>
                                </template>
                            </template>
                        </select>
                    </div>
                </div>
                <span class="err" v-if="this.errors['timeError']">{{this.errors['timeError']}}</span>

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
                        </div>
                </div>
            </div>

            
        </template>
        <div class="passenger-adults">
            <i class="fa-solid fa-person"></i>
                <div class="passenger-txt">
                    <select class="pickup-time" name="cab_adult" @change="this.handleAdultAge">
                        <option value=""> Adults </option>
                        <template  v-for="counter in 9">
                            <option  :value="counter" :selected="this?.passangers?.adult==counter" >{{counter}}</option>                            
                        </template>
                    </select>
                    <span class="err" v-if="this.errors['adultError']">{{this.errors['adultError']}}</span>
                </div>
            </div>


            <div class="passenger-child">
                <i class="fa-solid fa-children"></i>
                <div class="passenger-txt">
                    <select class="pickup-time" name="cab_children" @change="this.handleChildrenAge">
                        <option value="">Child </option>
                        <template v-for="counter in 8">
                            <option :value="counter" :selected="this?.passangers?.children==counter">{{counter}}</option>
                        </template>
                    </select>
                    <span class="err" v-if="this.errors['childrenError']">{{this.errors['childrenError']}}</span>
                </div>
            </div>


        <div class="search_btn">
            <button type="submit">Search</button>
        </div>

        <div class="form_items_wrap"></div>

    </form>
</search-wrapper>



<!-- <div class="skeletonLoader" v-if="loading">
    <OneWayPageLoader v-if="tripType == 0"/>
</div> -->

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

import styled from 'vue3-styled-components';

const SearchWrapper = styled.div`
& .passenger_wrap {
    display: block !important; top: auto; left: auto; right: auto; flex: initial; width: initial; overflow: initial; box-shadow: none; padding: 0; border-radius: initial; z-index: initial;
}
& .passenger_wrap .passenger-economy .pickup-time{padding-left: 1rem;}
`;

export default {
    name : 'SearchCountryForm',
    created() {
        // console.log('store.tripTimeArr=',store.tripTimeArr);
        // console.log('this.airportLists', this.airportLists);

        let params = new URL(document.location).searchParams;
        let pickupType = parseInt(params.get("pickupType"));
        if(pickupType){
            this.pickupType = pickupType;
        }

        if(isNaN(store.tripType)){
            store.tripType = 0;
        }
        
        this.tripType = store.tripType;
        let TripType = store.tripType;
        this.tripTimeArr = store.tripTimeArr;

        this.fromAirportList = this.airportLists;
        this.toAirportList = this.destinationLists;

        if(this.routeInfos) {
            let multicity = {};
            this.routeInfos.forEach((routeInfo,index)=>{
                // console.log('this.TripType===>=',TripType);
                if(TripType == 0) {
                    if(index==0) {
                        this.fromCountry = routeInfo.fromCity;
                        this.toCountry = routeInfo.toCity;
                        this.departureDate = routeInfo.travelDate;
                        this.fromCountryList = this.destinationLists;
                        this.toCountryList = this.destinationLists;
                        this.time = routeInfo.travelTime;
                    }
                } else if(TripType == 1) {
                    this.fromCountry = routeInfo.fromCity;
                    this.toCountry = routeInfo.toCity;
                    this.departureDate = routeInfo.travelDate;
                    this.fromCountryList = this.destinationLists;
                    this.toCountryList = this.destinationLists;
                    this.time = routeInfo.travelTime;
                }  else if(TripType == 2) {
                    this.fromCountryList = this.destinationLists;
                    this.fromCountry = routeInfo.fromCity;
                    // this.cabRoute = routeInfo.sightseening;
                    this.departureDate = routeInfo.travelDate;
                    this.time = routeInfo.travelTime;
                } else {
                    // console.log('routeInfo.fromCity', routeInfo.fromCity)
                    // console.log('routeInfo.toCity', routeInfo.toCity)
                    this.fromAirport = routeInfo.fromCity;
                    this.toAirport = routeInfo.toCity;
                    this.departureDate = routeInfo.travelDate;
                    this.time = routeInfo.travelTime;
                }

                if(routeInfo?.adult) {
                    this.passangers.adult = parseInt(routeInfo.adult);
                }
                if(routeInfo?.children) {
                    this.passangers.children = parseInt(routeInfo.children);
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

        var curDate = new Date();
        var hours = curDate.getHours();
        if(hours > 16) {
            this.departureMinDate = new Date(moment(curDate).add(2, 'day'));
        } else {
            this.departureMinDate = new Date(moment(curDate).add(1, 'day'));
        }
        this.sightseeningdestinationLists = this.sightseeningDestinationLists;
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
            // countryList: this.destinationLists,
            sightseeningdestinationLists: [],
            fromCountryList: this.destinationLists,
            toCountryList: this.destinationLists,

            multifromCountryList: [
                this.destinationLists,
                this.destinationLists,
            ],
            multitoCountryList: [
                this.destinationLists,
                this.destinationLists,
            ],
            fromCountryDropdown: false,
            toCountryDropdown: false,
            fromDateCalender: false,
            toDateCalender: false,
            passengerPopup: false,

            todateSelectEnabled : false,

            fromAirport:{},
            toAirport:{},
            fromCountry:{},
            toCountry:{},
            departureDate:'',
            returnDate:'',
            passangers: {
                adult: 0,
                children: 0,
                infant: 0,
                total: 0,
            },
            BookingClass : 'Economy',

            fromCountryError:'',
            // cabRouteError:'',
            toCountryError:'',
            FromDateError:'',
            ToDateError:'',
            errors:{},
            isSearched: false,
            processing: false,
            loading:false,
            routeListData: this.routeList,
            // cabRoute:{},
            pickupType : 1,
            time: '',
            fromAirportList : [],
            toAirportList : [],
            departureMinDate : new Date(),
        }
    },
    methods: {
        isNumeric,
        showCabinClass,
        handlePickup(e){
            this.pickupType = parseInt(e.target.value);
        },
        handleTimeChange(e){
            this.time = e.target.value;
            if(this.isSearched){this.handleErrors();}
        },
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
            return item.name;
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
            this.multifromCountryList[multicityCounter] = this.destinationLists;
            this.multitoCountryList[multicityCounter] = this.destinationLists;
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
                tripType: this.tripType
            }

            if(store.tripType == 2) {

                 if(this.fromCountry.name) {
                        form_data['from'] = this.fromCountry.id;
                        delete errors['fromCountryError'];
                    } else {
                        errors['fromCountryError'] = 'Origin is required';
                    }
                /*if(this.cabRoute.name) {
                    // form_data['cabroute'] = this.cabRoute.id;
                    delete errors['cabRouteError'];
                    delete errors['fromCountryError'];
                    delete errors['toCountryError'];
                    // delete errors['fromDateError'];
                    delete errors['toDateError'];
                }*/
            /*  else {
                    errors['cabRouteError'] = 'Sightseeing is required';
                }*/

             if(this.departureDate) {
                    form_data['dep'] =  moment(this.departureDate).format('YYYY-MM-DD');
                    delete errors['fromDateError'];
                } else {
                    errors['fromDateError'] = 'Date is required';
                }
            } else {

                if(store.tripType == 3) {

                     if(this.fromAirport.name) {
                        form_data['from'] = this.fromAirport.id;
                        form_data['pickupType'] = this.pickupType;

                        delete errors['fromCountryError'];

                    } else {
                        errors['fromCountryError'] = 'Origin is required';
                    }

                    if(this.toAirport.name) {

                        //console.log('toAirport = ',this.toAirport.id);
                        form_data['to'] = this.toAirport.id;
                        form_data['pickupType'] = this.pickupType;
                        delete errors['toCountryError'];

                        } else {
                            errors['toCountryError'] = 'Destination is required';
                        }

                }else{

                     if(this.fromCountry.name) {
                        form_data['from'] = this.fromCountry.id;
                        delete errors['fromCountryError'];
                    } else {
                        errors['fromCountryError'] = 'Origin is required';
                    }
                    if(this.toCountry.name) {
                        form_data['to'] = this.toCountry.id;
                        delete errors['toCountryError'];
                        } else {
                            errors['toCountryError'] = 'Destination is required';
                        }
                }

                if(this.departureDate) {
                    form_data['dep'] =  moment(this.departureDate).format('YYYY-MM-DD');
                    delete errors['fromDateError'];
                } else {
                    errors['fromDateError'] = 'Date is required';
                }                

               /* if(store.tripType == 1) {
                    if(this.returnDate) {
                        form_data['ret'] = moment(this.returnDate).format('YYYY-MM-DD');
                        delete errors['toDateError'];
                    } else {
                        errors['toDateError'] = 'Return Date is required';
                    }
                }*/
            }

            if(this.passangers.adult) {
                form_data['adult'] = this.passangers.adult;
                delete errors['adultError'];
            } else {
                errors['adultError'] = 'No of Adults is required';
            }

            if(this.passangers.children) {
                form_data['children'] = this.passangers.children;
                delete errors['childrenError'];
            } else {
                // errors['childrenError'] = 'No of children is required';
            }

            if(this.time == ""){
               errors['timeError'] = 'Time is required';
            }else{
                 form_data['time'] = this.time;
                 delete errors['timeError'];
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
                this.$inertia.get(`/cab/list`, form_data);
            }
        },
        close (el) {
                if(!el.target.closest('.passenger_wrap')){
                    this.passengerPopup = false
                }
        },
        handleAirportChange (e,origin) {
            var currentObj = this;
            var value = e.target.value;
            var tripType = store.tripType;
            if(value && value.length >= 3) {
                axios.post('/cab/search_cities', {
                    value: value,
                    tripType: tripType,
                    origin: origin,
                })
                .then(function (response) {
                    if(response.data.success) {


                        if(tripType == 3){
                            if(origin == 'toAirport'){
                                currentObj['toAirportList'] = response.data.destinationLists;
                                this.toAirportList = currentObj['toAirportList'];
                            }else{
                                currentObj['fromAirportList'] = response.data.destinationLists;
                                this.fromAirportList = currentObj['fromAirportList'];
                            }
                        }else{
                            if(tripType == 2) {
                                if(origin == 'from') {
                                    currentObj['sightseeningdestinationLists'] = response.data.destinationLists;
                                } else if(origin == 'to') {
                                    // currentObj['toCountryList'] = response.data.destinationLists;
                                }
                            } else {
                                if(origin == 'from') {
                                    currentObj['fromCountryList'] = response.data.destinationLists;
                                } else if(origin == 'to') {
                                    currentObj['toCountryList'] = response.data.destinationLists;
                                }                                
                            }

                        }
                    } else if(response.data.message) {
                        alert(response.data.message);
                    }
                })
                .catch(function (error) {

                });
            } else {
                if(tripType == 3){
                    if(origin == 'toAirport'){
                        currentObj['toAirportList'] = this.destinationLists;
                    } else {
                        currentObj['fromAirportList'] = this.airportLists;
                    }
                } else {
                    if(tripType == 2) {
                        if(origin == 'from') {
                            currentObj['sightseeningdestinationLists'] = this.sightseeningDestinationLists;
                        } else if(origin == 'to') {
                            // currentObj['toCountryList'] = this.destinationLists;
                        }
                    } else {
                        if(origin == 'from') {
                            currentObj['fromCountryList'] = this.destinationLists;
                        } else if(origin == 'to') {
                            currentObj['toCountryList'] = this.destinationLists;
                        }                        
                    }
                }
            }
        },
        callAjax(value){
            console.log('selected value', value.id);

            var _this = this;

            var origin = value.id;

            //============

            axios.post('/cab/search_route', {
                    origin: origin,
                  
                })
                .then(function (response) {
                    if(response.data.success) {
                        // currentObj['routeListData'] = response.data.destinationLists;
                        // console.log(response.data.destinationLists);
                        _this.routeListData = response.data.destinationLists;

                    } else if(response.data.message) {

                        alert(response.data.message);
                    }
                    
                })
                .catch(function (error) {
                    
                });


            //===========



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
                this.callAjax(newValue);
                
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
        'search-wrapper' : SearchWrapper
    },
    props: ["destinationLists", "airportLists", "sightseeningDestinationLists", "TripType", "routeInfos", "paxInfo", "cabinClass", "routeList"],
    }
</script>