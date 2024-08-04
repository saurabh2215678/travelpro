<template>
    <h3 class="text-lg font-bold pb-1 pt-1 ml-2">Rental Search</h3>
     <form-wrapper class="flight_form rental_search_form">
                <div class="ssb-wrap">
                    <DatePicker columns="2" v-model="pickupDate" mode="date" class="date_wrap" :min-date="this.pickupMinDate" :masks="masks">
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        name="pickupDate"
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="Pickup Date"
                        autocomplete="off"
                        />
                    </template>
                </DatePicker>
                    <div class="ssb_errors">
                        <!----><!---->
                    </div>
                </div>
                <div class="time_wrap">
                    <div class="passenger-economy">
                        <i class="fa-regular fa-clock"></i>
                        <div class="passenger-txt">
                        <select class="pickup-time" name="pickupTime" @change="this.setDropTime()" ref="pickupTimeRef">
                            <option value=""> Time </option>
                            <template v-if="this.tripTimeArr && this.tripTimeArr.length > 0">
                                <template v-for="tripTimeRow,index in this.tripTimeArr">
                                    <option :value="tripTimeRow.key" :selected="tripTimeRow.key==store.searchData?.pickupTime">{{tripTimeRow.title}}</option>
                                </template>
                            </template>
                        </select>
                        </div>
                    </div>

                </div>
                <div class="ssb-wrap">
                    <DatePicker columns="2" v-model="dropDate" mode="date" class="date_wrap" :min-date="this.dropMinDate" :masks="masks">
                    <template v-slot="{ inputValue, inputEvents, togglePopover }">
                        <input
                        name="dropDate"
                        class="date_input"
                        :value="inputValue"
                        @click="togglePopover"
                        placeholder="Dropoff"
                        autocomplete="off"
                        />
                    </template>
                </DatePicker>
                    <div class="ssb_errors">
                        <!----><!---->
                    </div>
                </div>
                <div class="time_wrap">
                    <div class="passenger-economy">
                        <i class="fa-regular fa-clock"></i>
                        <div class="passenger-txt">
                            <select class="pickup-time"  name="dropTime" ref="dropTimeRef">
                                <option value="" class="default_time_option"> Time </option>
                                <template v-if="this.tripTimeArr && this.tripTimeArr.length > 0">
                                    <template v-for="tripTimeRow,index in this.tripTimeArr">
                                        <option :value="tripTimeRow.key" :selected="tripTimeRow.key==store.searchData?.dropTime">{{tripTimeRow.title}}</option>
                                    </template>
                                </template>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="select_from_wrap location_input ml-2">
                    <i class="fa-solid fa-location-dot absolute top-3 left-2 z-50 opacity-50 mr-1"></i>
                    <input type="hidden" name="city" :value="cityModal.id">
                    <ModelListSelect  :list="cityList" v-model="cityModal" placeholder="Where To ?" option-value="id" option-text="name"/>
                </div>
                <div class="search_btn">
                 <button type="submit">Search</button>
              </div>
            </form-wrapper>
</template>

<script>
import { store } from '../../store.js';
import styled from 'vue3-styled-components';
import 'v-calendar/dist/style.css';
import { DatePicker } from 'v-calendar';
import FormTopMenu from '../FormTopMenu.vue';
import 'vue-search-select/dist/VueSearchSelect.css';
import { ModelListSelect } from 'vue-search-select';
import {convertToDateObject, getGreaterDate, getTomorrowDate} from '../../utils/commonFuntions.js';

const FormWrapper = styled.form`
& .default_time_option {
    display:block!important;
}
`;


export default {
    created() {
        document.body.classList.add('rental');

        this.cityList = store.cities;
        
        const urlParams = new URLSearchParams(window.location.search);
        const cityId = urlParams.get('city');

        this.setTimeArray();

        if(cityId){
            let selectedCity = this.cityList.find((o) => o.id === parseInt(cityId));
            this.cityModal = selectedCity;
        }else{

            setTimeout(() => {
                (store.searchData.pickupDate);
                if(store.searchData.city){
                    let selectedCity = this.cityList.find((o) => o.id === store.searchData.city);
                    this.cityModal = selectedCity;
                }
            },300);
        }

    },

    beforeUnmount() {
        //console.log(this.$el)
        document.body.classList.remove('rental');
      },

    data() {
        return {
            visible: false,
            cityList: [],
            cityModal:{},
            store,
            pickupDate: '',
            pickupMinDate: getTomorrowDate(),
            dropDate: '',
            dropMinDate: new Date(),
            tripTimeArr: [],
            masks: {
                  input: 'DD/MM/YYYY',
                },
            }
    },
    methods : {
        getGreaterDate,
        setTimeArray(){
            let selectedPickupdate = convertToDateObject(store?.searchData?.pickupDate);
            this.pickupDate = selectedPickupdate;
            let selecteddropdate = convertToDateObject(store?.searchData?.dropDate);
            this.dropDate = selecteddropdate;
            this.tripTimeArr = store?.websiteSettings?.timeArr;

        },
       
        setDropTime(){
            var dropTimeElement = this.$refs.dropTimeRef;
            var pickupTimeElement = this.$refs.pickupTimeRef;
            var selectedPickupTime = pickupTimeElement.value;
            // Variable to track if the selectedPickupTime option is found
            let foundSelectedPickupTime = false;

            var isSameDate = `${this.pickupDate}` == `${this.dropDate}`;

            console.log('isSameDate', isSameDate);

            // Loop through all the options in the dropTimeElement
            for (let i = 0; i < dropTimeElement.options.length; i++) {
                const option = dropTimeElement.options[i];

                if (foundSelectedPickupTime) {
                    // If the selectedPickupTime option is found, display subsequent options
                    option.style.display = 'block';
                } else {
                    // If the option value matches the selectedPickupTime, set foundSelectedPickupTime to true
                    // and hide previous options
                    if (option.value === selectedPickupTime) {
                        foundSelectedPickupTime = true;
                        option.style.display = 'none'; // Hide the selected option
                    } else {
                        option.style.display = 'none';
                    }
                }
            }

            // Check if the selected option is hidden
            if (dropTimeElement.selectedIndex !== -1) {
                const selectedOption = dropTimeElement.options[dropTimeElement.selectedIndex];
                if (selectedOption.style.display === 'none') {
                    // If the selected option is hidden, find the first non-hidden option and set it as selected
                    for (let i = 0; i < dropTimeElement.options.length; i++) {
                        const option = dropTimeElement.options[i];
                        if (option.style.display !== 'none' && isSameDate) {
                            dropTimeElement.selectedIndex = i;
                            break;
                        }
                    }
                }
            }

            if (!isSameDate) {
                // Set all options to display block if not same date
                for (let i = 0; i < dropTimeElement.options.length; i++) {
                    dropTimeElement.options[i].style.display = 'block';
                }
            }
        }
    },

    mounted () {
    },
    beforeDestroy (){

    },
    watch:{
        pickupDate : function(pickupDate){
            this.dropMinDate = pickupDate;
            this.dropDate = this.getGreaterDate(pickupDate, this.dropDate);
            if(this.$refs.dropTimeRef){
                this.setDropTime();
            }
        },
        dropDate: function(dropDate){
            if(this.$refs.dropTimeRef){
                this.setDropTime();
            }
        },
        store : {
            handler(updatedStore){
                if(updatedStore?.searchData && updatedStore?.websiteSettings){
                    this.setTimeArray();
                }
            },
            deep: true
        },
        tripTimeArr : function(updatedTripTime){
            if(this.$refs.dropTimeRef){
                var currentObj = this;
                setTimeout(() => {
                    currentObj.setDropTime();
                }, 100);
            }
        }
    },
    components: {
        DatePicker,
        FormTopMenu,
        ModelListSelect,
        'form-wrapper' : FormWrapper
    },
   props:["metaTitle", "metaDescription"]
}


</script>
