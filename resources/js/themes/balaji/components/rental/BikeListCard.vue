    <template>
        <BikeCardWrapper class="border rounded">
            <div class="topimg">
                <img :src="bike.src">
                <div class="title text-lg text-center text-slate-800 bg-slate-100 p-1">{{bike.name}}</div>
            </div>
            <div class="buttom_content p-3">
                <div class="searchlocation pb-3">
                    <div class="select-wrapper" :class="!locationId && submitted ? 'has_error' : ''">
                        <label>Available at</label>
                        <!-- <select class="select" @change="handleOnChange($event)">
                            <option value="">Location</option>
                            <option v-if="bike.cities" v-for="city in bike.cities" :value="city.id">{{city.name}}</option>
                        </select> -->
                        <ModelListSelect :list="bike.cities" v-model="bikeCityModal" placeholder="Location" option-value="id" option-text="name" @change="handleOnChange($event)"/>
                        <span v-if="!locationId && submitted" class="error_text">Please select Location</span>
                    </div>
                </div>
                <div class="timing pb-5">
                    <div class="flex justify-between items-center">
                        <div class="datelist text-sm"><span>{{TimeFormat(store.searchData?.dropDate+' '+store.searchData?.dropTime)}}</span> <span>{{DateFormat(store.searchData?.dropDate)}}</span></div>
                        <div><img src="../../assets/images/d-arrow.png"></div>
                        <div class="datelist text-sm"><span>{{TimeFormat(store.searchData?.pickupDate+' '+store.searchData?.pickupTime)}}</span><span>{{DateFormat(store.searchData?.pickupDate)}}</span></div>
                    </div>
                </div>
                <div class="pricebook">
                    <div class="flex flex-row justify-between items-center">
                        <div class="pricebold"><em>{{showPrice(this.bike.price)}}</em><span class="text-sm">({{bike.total_km}} km included)</span></div>
                        <div class="sightseen-bookbtn"><a href="#" @click="bookNow">Book</a></div>
                    </div>
                </div>
            </div>
        </BikeCardWrapper>
    </template> 

 <script>
    import { store } from '../../store.js';
    import {showPrice, DateFormat, TimeFormat} from '../../utils/commonFuntions';
    import 'vue-toast-notification/dist/theme-default.css';
    import { ModelListSelect } from 'vue-search-select';
    import axios from "axios";
    import styled from 'vue3-styled-components';


    const BikeCardWrapper = styled.div`
    & .has_error .error_text{
        color:red;
        font-size: 0.86rem;
    }
    & .has_error .dropdown:not(.active) {
        border-color: #ff000047!important;
    }
    `;


    export default {
        created() {

            this.cityId = this.bike.city_id;
            this.bikeId = this.bike.id;

            let location_id = 0;
            this.locationId = location_id;


             //this.bikePrice = this.bike.price;
            //this.bikeCityPrice(cityId);
        },
        data() {
            return {
                store,
                locationId:'',
                cityId:'',
                bikePrice:0,
                bikeId:'',
                bikeCityModal:{},
                submitted : false
            }
        },
        methods:{
            TimeFormat,
            showPrice,
            DateFormat,
            handleOnChange(selectedCity){
                // let location_id = e.target.value;
                // console.log(e.target);
                // alert(location_id);
                this.locationId = selectedCity.id;
                // alert(this.locationId);
                //this.bikeCityPrice(cityId);

            },
            bikeCityPrice(cityId){
                this.bike.cities.forEach((city, index) => {
                    if(city.id == cityId) {
                        this.bike.price = city.price;
                    }
                });
            },
            bookNow(e) {
                e.preventDefault();
                var currentObj = this;
                currentObj.processing = true;
                currentObj.submitted = true;
                currentObj.errors = {};
                let form_data = {};
                this.bikePrice = this.bike.price;
                this.bikeId = this.bike.id;
                this.cityId = this.bike.city_id;
                this.total_km = this.bike.total_km;
                //let location_id = this.bike.cities[0]?.id;
               // this.locationId = location_id;

               if(this.locationId == 0){
                // alert('Please select Location');
                return true;
               }
               
                form_data['action'] = 'rental_booking';
                form_data['location_id'] = this.locationId;
                form_data['city_id'] = this.cityId;
                form_data['bike_id'] = this.bikeId;
                form_data['price'] =  this.bikePrice;
                form_data['total_km'] =  this.total_km;
                form_data['trip_date'] = this.store.searchData?.pickupDate;
                form_data['pickupDate'] = this.store.searchData?.pickupDate;
                form_data['pickupTime'] = this.store.searchData?.pickupTime;
                form_data['dropDate'] = this.store.searchData?.dropDate;
                form_data['dropTime'] = this.store.searchData?.dropTime;

               // console.log(form_data);

                axios.post('/book_now', form_data)
                .then(function (response) {  
                    currentObj.processing = false;
                    if(response.data.success) {

                        //alert(response.data.redirect_url);
                        // window.location.href = response.data.redirect_url;
                        currentObj.$inertia.get(response.data.redirect_url);

                        // this.$inertia.get(`/${response.data.redirect_url}`);

                        } else {
                            alert('Something went wrong, please try again.');
                        }
                        }).catch(function (e) {
                            var response = e.response.data;
                            if(response) {
                                currentObj.parseBookingErrorMessages(response, 'myForm', 'Book Online');
                    }
                });
            },

        },
        components: {
            ModelListSelect,
            BikeCardWrapper
        },
    watch: {
        bikeCityModal : function(selectedCity){
            this.handleOnChange(selectedCity);
        }
    },
    props: ["bike"],
    }
    </script>