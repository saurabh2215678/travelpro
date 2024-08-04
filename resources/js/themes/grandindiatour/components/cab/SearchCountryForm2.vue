<template>

<SearchWrapper class="book_flight_form" :class="`trip-type-${tripType}`">
    <div class="tabs">
        <button
            class="tab-btn"
            :class="tripType == 1 ? 'active' : ''"
            @click="setActiveTab(1);"
            v-if="store.websiteSettings?.INTERCITY_TRIP"
        >INTERCITY TRIP</button>

        <button
            class="tab-btn"
            :class="tripType == 2 ? 'active' : ''"
            @click="setActiveTab(2);"
            v-if="store.websiteSettings?.OUTSTATION"
        >OUTSTATION</button>

        <button
            class="tab-btn"
            :class="tripType == 3 ? 'active' : ''"
            @click="setActiveTab(3)"
            v-if="store.websiteSettings?.AIRPORT"
        >AIRPORT</button>
        <button
            class="tab-btn"
            :class="tripType == 4 ? 'active' : ''"
            @click="setActiveTab(4)"
            v-if="store.websiteSettings?.RAILWAYS"
        >RAILWAYS</button>
        <button
            class="tab-btn"
            :class="tripType == 5 ? 'active' : ''"
            @click="setActiveTab(5)"
            v-if="store.websiteSettings?.SIGHTSEEING"
        >SIGHTSEEING</button>
    </div>

    <form class="flight_form" @submit="handleFormSubmit" ref="formRef">

        <!--  Pickup and Drop Select -->
        <div class="trp_box"  v-if="tripType == 3 || tripType == 4">
            <label>TRIP</label>
            <div class="trip-type relative">
                <select @change="handlePickup">
                    <option value="0" :selected="pickupType == 0"> Pickup </option>
                    <option value="1" :selected="pickupType == 1"> Drop </option>
                </select>
                <i class="fa-solid fa-caret-down absolute right-2 text-xs"></i>
            </div>
        </div>

        <!--  Select City -->
        <div class="select_from_wrap city_select">
            <label>City</label>
            <ModelListSelect 
                :list="this.searchCityLists"
                v-model="selectedCity"
                @Input="(e)=>handleCityChange(e,'selectedCity')"
                option-value="name" 
                option-text="city" 
                :customText="(item)=>handleCustomText(item)" 
                placeholder="Select City" 
                name="selectedCity"
            />
            <span class="err" v-if="this.errors['cityError']">{{this.errors['cityError']}}</span>
        </div>

        <!--  Pick Up Location -->
        <div class="select_from_wrap pickup_loc" v-if="isPickupLocationShown()">
            <label>Pick Up Location</label>
            <input type="text" name="pickup_location" class="free_input" id="places-search" placeholder="Pick Up Location" />
            <span class="err" v-if="this.errors['pickupLocationError']">{{this.errors['pickupLocationError']}}</span>
        </div>

        <!--  Airport Terminal -->
        <div class="select_from_wrap airport_ter" v-if="tripType == 3">
            <label>Terminal</label>
            <ModelListSelect 
                :list="searchTerminalLists" 
                v-model="selectedTerminal"
                option-value="name" 
                :customText="(item)=>handleCustomText(item)" 
                option-text="city" 
                placeholder="Airport Terminal" 
            />
            <span class="err" v-if="this.errors['terminalError']">{{this.errors['terminalError']}}</span>
        </div>

        <!--  Select Station -->
        <div class="select_from_wrap sel_station" v-if="tripType == 4">
            <label>Station</label>
            <ModelListSelect 
                :list="searchStationLists" 
                v-model="selectedStation"
                option-value="name" 
                :customText="(item)=>handleCustomText(item)" 
                option-text="city" 
                placeholder="Select Station" 
            />
            <span class="err" v-if="this.errors['stationError']">{{this.errors['stationError']}}</span>
        </div>

        <!--  Drop Location -->
        <!-- <div class="select_from_wrap drop_loc" v-if="!isPickupLocationShown() && tripType != 5">
            <label>Drop Location</label>
            <input type="text" name="drop_location" class="free_input" placeholder="Drop Location" />
            <span class="err" v-if="this.errors['dropLocationError']">{{this.errors['dropLocationError']}}</span>
        </div> -->

        <!-- Destination -->
        <div class="select_from_wrap dest_sel" v-if="isDestinationLocationShown()">
            <label>Destination</label>
            <input type="text" name="destination" class="free_input" placeholder="Destination" />
            <span class="err" v-if="this.errors['destinationError']">{{this.errors['destinationError']}}</span>
        </div>

        <!-- Date -->
        <div class="ssb-wrap date sel_date">
            <label>Date</label>
            <DatePicker columns="2" v-model="selectedDate" mode="date" class="date_wrap" >
                <template v-slot="{ inputValue, inputEvents, togglePopover }">
                    <input
                    class="date_input"
                    :value="inputValue"
                    @click="togglePopover"
                    placeholder="Date"
                    />
                </template>
            </DatePicker>
            <span class="err" v-if="this.errors['dateError']">{{this.errors['dateError']}}</span>
        </div>

        <!-- Time -->
        <div class="passenger_wrap time sel_time">
            <label>Time</label>
            <div class="passenger-economy" >
                <i class="fa-regular fa-clock"></i>
                <div class="passenger-txt">
                    <select class="pickup-time" @change="this.handleTimeChange">
                        <option value=""> Time </option>
                        <template v-if="this.tripTimeArr">
                            <template v-for="tripTimeRow,index in this.tripTimeArr">
                                <option :value="tripTimeRow.key" :selected="tripTimeRow.key==time">{{tripTimeRow.title}}</option>
                            </template>
                        </template>
                    </select>
                </div>
            </div>
            <span class="err" v-if="this.errors['timeError']">{{this.errors['timeError']}}</span>
        </div>
        
        <!-- Return Date -->
        <div class="ssb-wrap ret_date" v-if="tripType == 2 && returnEnabled">
            <label>Return Date</label>
            <DatePicker columns="2" v-model="returnDate" mode="date" class="date_wrap" >
                <template v-slot="{ inputValue, inputEvents, togglePopover }">
                    <input
                    class="date_input"
                    :value="inputValue"
                    @click="togglePopover"
                    placeholder="Return Date"
                    />
                </template>
            </DatePicker>
            <span class="err" v-if="this.errors['returnDateError']">{{this.errors['returnDateError']}}</span>
        </div>

        <!-- Return Time -->
        <div class="passenger_wrap ret_time" v-if="tripType == 2 && returnEnabled">
            <label>Return Time</label>
            <div class="passenger-economy" >
                <i class="fa-regular fa-clock"></i>
                <div class="passenger-txt">
                    <select class="pickup-time" @change="this.handleReturnTimeChange">
                        <option value=""> Return Time </option>
                        <template v-if="this.tripTimeArr">
                            <template v-for="tripTimeRow,index in this.tripTimeArr">
                                <option :value="tripTimeRow.key" :selected="tripTimeRow.key==time">{{tripTimeRow.title}}</option>
                            </template>
                        </template>
                    </select>
                </div>
            </div>
            <span class="err" v-if="this.errors['returnTimeError']">{{this.errors['returnTimeError']}}</span>
        </div>

        <div class="search_btn">
            <button type="submit">Search</button>
        </div>

    </form>
    <div class="returnCheckBox" v-if="tripType == 2">
        <label>
            <input type="checkbox" @change="handleReturnCheckbox" :checked="returnEnabled">
            <span>Return Trip</span>
        </label>
    </div>
</SearchWrapper>


</template>
<script>
import { DatePicker } from 'v-calendar';
import { ModelListSelect } from 'vue-search-select';
import 'v-calendar/dist/style.css';
import 'vue-search-select/dist/VueSearchSelect.css';
import { SearchWrapper } from "../../styles/SearchWrapper";
import { store } from '../../store';
import axios from "axios";

export default {
    name : 'SearchCountryForm',
    created() {
        this.tripTimeArr = store.tripTimeArr;
        if(this.TripType) {
            this.tripType = this.TripType;
        }
        //console.log('store=',store.websiteSettings);
    },
    data() {
        return {
            store,
            tripType: 1,
            searchCityLists: [],
            searchTerminalLists: [],
            searchStationLists: [],            
            selectedCity: {},
            selectedPickup: {},
            selectedDestination: {},
            selectedTerminal: {},
            selectedStation: {},
            selectedDate: null,
            selectedTime: '',
            returnDate: null,
            returnTime: '',
            errors:{},
            tripTimeArr:[],
            returnEnabled: false,
            pickupType: 0,
            loading:false,
            isSearched: false
        }
    },
    methods: {
        handleCityChange (e,origin) {
            let currentObj = this;
            let value = e.target.value;
            let tripType = this.tripType;
            currentObj.getCities(tripType, value);
        },
        getCities(tripType, value) {
            let currentObj = this;
            axios.post('/cab/search_cities', {
                tripType: tripType,
                value: value
            })
            .then(function (response) {
                if(response.data.success) {
                    currentObj.searchCityLists = response.data.results.data;
                } else if(response.data.message) {
                    alert(response.data.message);
                }
            })
            .catch(function (error) {

            });
        },
        getTerminalLists(cityId){
            let currentObj = this;
            axios.post('/cab/search_cities', {
                cityId: cityId,
                action: 'terminalLists',
            })
            .then(function (response) {
                if(response.data.success) {
                    currentObj.searchTerminalLists = response.data.results.data;
                } else if(response.data.message) {
                    alert(response.data.message);
                }
            })
            .catch(function (error) {

            });        
        },
        getStationLists(cityId){
            let currentObj = this;
            axios.post('/cab/search_cities', {
                cityId: cityId,
                action: 'stationLists',
            })
            .then(function (response) {
                if(response.data.success) {
                    currentObj.searchStationLists = response.data.results.data;
                } else if(response.data.message) {
                    alert(response.data.message);
                }
            })
            .catch(function (error) {

            });
        },
        handleFormSubmit(e){
            e.stopPropagation();
            e.preventDefault();
            let currentObj = this;
            this.isSearched = true;
            var form_data = this.handleErrors();
            if(Object.keys(this.errors).length <= 0){
                this.loading = true;
                this.errors = {};
                store.tripType = this.tripType;
                axios.post('/cab/search_filters', form_data)
                .then(function (response) {
                    if(response.data.success) {
                        currentObj.$inertia.get(response.data.redirect_url);
                    } else if(response.data.message) {
                        alert(response.data.message);
                    }
                })
                .catch(function (error) {

                });
            }
        },
        handleErrors(){
            var errors = {};
            let tripType = this.tripType;
            let pickupType = this.pickupType;
            var form_data = {
                tripType: tripType,
                pickupType: pickupType
            }
            if(this.selectedCity && this.selectedCity.name) {
                form_data['city'] = this.selectedCity;
                delete errors['cityError'];
            } else {
                errors['cityError'] = 'City is required';
            }

            if(this.isPickupLocationShown()) {
                if(this.selectedPickup && this.selectedPickup.name) {
                    form_data['pickup'] = this.selectedPickup;
                    delete errors['pickupLocationError'];
                } else {
                    errors['pickupLocationError'] = 'Pick Up Location is required';
                }
            }

            if(this.isDestinationLocationShown()) {
                if(this.selectedDestination && this.selectedDestination.name) {
                    form_data['destination'] = this.selectedDestination;
                    delete errors['destinationError'];
                } else {
                    errors['destinationError'] = 'Destination is required';
                }
            }

            if(tripType == 3) {
                if(this.selectedTerminal && this.selectedTerminal.name) {
                    form_data['terminal'] = this.selectedTerminal;
                    delete errors['terminalError'];
                } else {
                    errors['terminalError'] = 'Terminal is required';
                }
            }

            if(tripType == 4) {
                if(this.selectedStation && this.selectedStation.name) {
                    form_data['station'] = this.selectedStation;
                    delete errors['stationError'];
                } else {
                    errors['stationError'] = 'Station is required';
                }
            }

            form_data['returnEnabled'] = this.returnEnabled;
            if(tripType == 2 && this.returnEnabled) {
                if(this.returnDate) {
                    form_data['return'] =  moment(this.returnDate).format('YYYY-MM-DD');
                    delete errors['returnDateError'];
                } else {
                    errors['returnDateError'] = 'Date is required';
                }
                if(this.returnTime == ""){
                    errors['returnTimeError'] = 'Time is required';
                } else {
                    form_data['return_time'] = this.returnTime;
                    delete errors['returnTimeError'];
                }
            }

            if(this.selectedDate) {
                form_data['dep'] =  moment(this.selectedDate).format('YYYY-MM-DD');
                delete errors['dateError'];
            } else {
                errors['dateError'] = 'Date is required';
            }

            if(this.selectedTime == ""){
                errors['timeError'] = 'Time is required';
            } else {
                form_data['time'] = this.selectedTime;
                delete errors['timeError'];
            }
            this.errors = errors;
            return form_data;
        },
        handleCustomText(item) {
            return item.name;
        },
        setActiveTab(tabType) {
            this.tripType = tabType;
            this.errors = {};
        },
        handleReturnCheckbox(e){
            this.returnEnabled = e.target.checked
        },
        handlePickup(e){
            this.pickupType = e.target.value;
        },
        handleTimeChange(e){
            this.selectedTime = e.target.value;
        },
        handleReturnTimeChange(e){
            this.returnTime = e.target.value;
        },
        isPickupLocationShown() {
            if(this.tripType == 5) {
                return false;
            }
            if((this.tripType == 3 || this.tripType == 4) && this.pickupType == 0){
                return false;
            }
            return true;
        },
        isDestinationLocationShown() {
            if(this.tripType == 5) {
                return false;
            }
            if((this.tripType == 3 || this.tripType == 4) && this.pickupType == 1){
                return false;
            }
            return true;
        },        
        initAutocomplete() {
            let currentObj = this;
            var formElement = currentObj.$refs.formRef;
            var GoogleInputs = formElement.querySelectorAll('.free_input');

            GoogleInputs.forEach(input => {
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.addListener("place_changed", () => {
                    // infowindow.close();
                    // marker.setVisible(false);
                    const place = autocomplete.getPlace();
                    console.log('place=',place);
                    if (!place.geometry || !place.geometry.location) {
                      // User entered the name of a Place that was not suggested and
                      // pressed the Enter key, or the Place Details request failed.
                      window.alert("No details available for input: '" + place.name + "'");
                      return;
                    }
                    let lat = place.geometry.location.lat();
                    let lng = place.geometry.location.lng();
                    console.log('lat=',lat);
                    console.log('lng=',lng);
                    console.log('input=',input);
                    let input_name = input.name;
                    console.log('input_name=',input_name);
                    if(input_name == 'pickup_location') {
                        let selectedPickup = {
                            'name' : place.name,
                            'lat' : lat,
                            'lng' : lng
                        };
                        currentObj.selectedPickup = selectedPickup;
                    } else if(input_name == 'drop_location') {
                        let selectedDrop = {
                            'name' : place.name,
                            'lat' : lat,
                            'lng' : lng
                        };
                        currentObj.selectedDrop = selectedDrop;
                    } else if(input_name == 'destination') {
                        let selectedDestination = {
                            'name' : place.name,
                            'lat' : lat,
                            'lng' : lng
                        };
                        currentObj.selectedDestination = selectedDestination;
                    }
                });
            });
        },
    },
    updated () {
        this.isPickupLocationShown();
        this.initAutocomplete();
    },
    mounted(){
        this.initAutocomplete();
    },
    components: {
        DatePicker,
        ModelListSelect,
        SearchWrapper
    },
    watch: {
        tripType: {
            handler: function(newTripType) {
                let currentObj = this;
                currentObj.getCities(newTripType);
            },
            deep: true
        },
        selectedCity:{
            handler: function(newCity) {
                // console.log('newCity=',newCity);
                let currentObj = this;
                if(currentObj.tripType == 3) {
                    currentObj.getTerminalLists(newCity.id);
                } else if(currentObj.tripType == 4) {
                    currentObj.getStationLists(newCity.id);
                }
            },
            deep: true
        }
    },
    props: ["TripType", "routeInfos"],
}
</script>