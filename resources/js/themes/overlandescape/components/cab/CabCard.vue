<template>
       <cab-card-wrapper>
            <td>
                <div class="name_content_box">
                  <img v-if="store.websiteSettings?.CAB_IMAGE_PATH" :src="`${store.websiteSettings?.CAB_IMAGE_PATH+this.info.image}`" alt="image">
                    <img src="../../assets/images/car1.jpg" v-else alt="">
                    <div class="name_content">
                        <h3 class="font20 fw600">
                            {{this.info.name}}
                        </h3>
                        <p class="font13 mb-0">
                            or equivalent
                            <Popper hover arrow placement="right"  v-if="this.info.equivalent">
                                <img class="tooltip_icon" src="../../assets/images/Information-icon_new.png"/>
                                <template #content>
                                    <div class="tooltip_details">
                                        <p class="font12">{{ this.info.equivalent }}</p>
                                    </div>
                                </template>
                            </Popper>
                        </p>
                    </div>
                </div>
            </td>
            <td>
                <div class="prc_info">
                    <i class="fa-regular fa-newspaper"></i>
                    <p class="font12">Includes Toll, State Tax & GST</p>
                </div>
            </td>
            <td>
                <div class="prc_info" v-if="this.info.sharing && this.info.start_time && this.info.route_sharing">
                     <img class="shareing_icon" src="../../assets/images/shareing.png"/>
                    Start Time:{{this.info.start_time}}
                </div>

                <div class="prc_info" v-else>
                     <i class="fa-solid fa-award"> </i>
                    <p class="font12">Top Rated Cabs & Drivers</p>
                </div>


            </td>
            <!-- <td style="vertical-align: middle;">
                <div class="discount_txt">
                    <h4>₹ 56696</h4>
                    <p class="font14">Save ₹5154</p>
                </div>
            </td> -->
            <td class="price_bg price-list-view" style="width: 10rem;">
                <div v-if="this.info.publish_price && this.info.price && parseFloat(this.info.publish_price) > parseFloat(this.info.price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{showPrice(this.info.publish_price,true)}}</span></div>
                <h4 class="font22 color_light fw400">{{showPrice(this.info.price,true)}}</h4>
                <!-- <p class="font12">up to 1717 km</p> -->
            </td>
            <td>
                <button class="btn"  v-if="this.info.sold" disabled>Sold out</button>
                <button class="btn" @click="handleBook" v-else>Select</button>
            </td>
        </cab-card-wrapper>
</template>
<script>
import {DateFormat, timeConvert, getLogo, showPrice, showErrorToast} from '../../utils/commonFuntions.js';
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store';
import axios from "axios";
import Popper from "vue3-popper";
import styled from 'vue3-styled-components';

const CabCardWrapper = styled.tr`
.flight_table & td {
        border:none;
        background-color: #0000;
        color: initial;
    }
    .flight_table & td button.btn:hover{
        color: var(--white)!important;
    }
@media (max-width: 767px){
    .car-module .flight_table & td:last-child button{
        height: auto;
        opacity: inherit;
        position: inherit;
        width: auto;
        margin: 0 auto;
  }
}
`;

export default {
    name: "CabCard",
    created() {
        // console.log(this.info);
    },
    data() {
        return {
            showErrorToast,
            info: this.info,
            store,
            buttonLoading: false,
        }
    },
    methods: {
        DateFormat,
        timeConvert,
        getLogo,
        showPrice,
        handleBook() {
        if(this.info.id) {
            store.bookingPassedStep= 0;
            store.bookingCurrentStep = 1;
            store.loadingName = 'iternity'
            if(store.tripType == 2 && this.addons?.data?.length > 0) {
                this.$inertia.get(`/cab/addons/${this.info.cab_route_id}?tripType=${this.tripType}&cab_route_group=${this.info.cab_route_group}&cab=${this.info.id}&dep=${this.routeInfos[0]['travelDate']}&time=${this.routeInfos[0]['travelTime']}&adult=${this.routeInfos[0]['adult']}&children=${this.routeInfos[0]['children']}`);
            } else {
                this.$inertia.get(`/cab/book/${this.info.cab_route_id}?tripType=${this.tripType}&cab_route_group=${this.info.cab_route_group}&cab=${this.info.id}&dep=${this.routeInfos[0]['travelDate']}&time=${this.routeInfos[0]['travelTime']}&adult=${this.routeInfos[0]['adult']}&children=${this.routeInfos[0]['children']}`);
            }
        } else {
            alert('Please select price to Book');
        }
    },
    },
    props: ["info", "id", "routeInfos", "paxInfo", "priceIdName", "totalFlights", "routeIndex", "tripType","addons"],
    components: {
        Link,
        Popper,
        'cab-card-wrapper' : CabCardWrapper
    }
}
</script>